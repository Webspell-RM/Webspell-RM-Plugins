<?php
/*¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯\
| _    _  ___  ___  ___  ___  ___  __    __      ___   __  __       |
|( \/\/ )(  _)(  ,)/ __)(  ,\(  _)(  )  (  )    (  ,) (  \/  )      |
| \    /  ) _) ) ,\\__ \ ) _/ ) _) )(__  )(__    )  \  )    (       |
|  \/\/  (___)(___/(___/(_)  (___)(____)(____)  (_)\_)(_/\/\_)      |
|                       ___          ___                            |
|                      |__ \        / _ \                           |
|                         ) |      | | | |                          |
|                        / /       | | | |                          |
|                       / /_   _   | |_| |                          |
|                      |____| (_)   \___/                           |
\___________________________________________________________________/
/                                                                   \
|        Copyright 2005-2018 by webspell.org / webspell.info        |
|        Copyright 2018-2019 by webspell-rm.de                      |
|                                                                   |
|        - Script runs under the GNU GENERAL PUBLIC LICENCE         |
|        - It's NOT allowed to remove this copyright-tag            |
|        - http://www.fsf.org/licensing/licenses/gpl.html           |
|                                                                   |
|               Code based on WebSPELL Clanpackage                  |
|                 (Michael Gruber - webspell.at)                    |
\___________________________________________________________________/
/                                                                   \
|                     WEBSPELL RM Version 2.0                       |
|           For Support, Mods and the Full Script visit             |
|                       webspell-rm.de                              |
\__________________________________________________________________*/
# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("latesttopics", $plugin_path);

global $maxposts;
if (isset($site)) {
    $_language->readModule('latesttopics');
}

    $data_array = array();
    $data_array['$title']=$plugin_language[ 'title' ];
    $data_array['$subtitle']='Latest topics';
    $template = $GLOBALS["_template"]->loadTemplate("widget_forum_sidebar","head", $data_array, $plugin_path);
    echo $template;



$usergroups = array();

$userallowedreadgrps = array();
$userallowedreadgrps[ 'boardIDs' ] = array();
$userallowedreadgrps[ 'catIDs' ] = array();
$get = safe_query("SELECT boardID FROM " . PREFIX . "plugins_forum_boards WHERE readgrps = ''");
while ($ds = mysqli_fetch_assoc($get)) {
    $userallowedreadgrps[ 'boardIDs' ][ ] = $ds[ 'boardID' ];
}
$get = safe_query("SELECT catID FROM " . PREFIX . "plugins_forum_categories WHERE readgrps = ''");
while ($ds = mysqli_fetch_assoc($get)) {
    $userallowedreadgrps[ 'catIDs' ][ ] = $ds[ 'catID' ];
}

if (empty($userallowedreadgrps[ 'catIDs' ])) {
    $userallowedreadgrps[ 'catIDs' ][ ] = 0;
}
if (empty($userallowedreadgrps[ 'boardIDs' ])) {
    $userallowedreadgrps[ 'boardIDs' ][ ] = 0;
}

$ergebnis = safe_query(
        "SELECT t.*, u.nickname, b.name FROM " . PREFIX . "plugins_forum_topics t
    LEFT JOIN
        " . PREFIX . "user u
    ON
        u.userID = t.lastposter
    LEFT JOIN
        " . PREFIX . "plugins_forum_boards b
    ON
        b.boardID = t.boardID 
        WHERE
        b.category
    IN
        (" . implode(",", $userallowedreadgrps[ 'catIDs' ]) . ")
        AND
        t.boardID
    IN
        (" . implode(",", $userallowedreadgrps[ 'boardIDs' ]) . ")
    AND
        t.moveID = '0'
    ORDER BY
        t.lastdate
    DESC
       LIMIT 0,5 " 
);

$anz = mysqli_num_rows($ergebnis);
if ($anz) {
    #$data_array = array();
    #$data_array['$title']=$plugin_language[ 'title' ];
    #$data_array['$subtitle']='Latest topics';
    $template = $GLOBALS["_template"]->loadTemplate("widget_forum_sidebar","head_head", $data_array, $plugin_path);
    echo $template;
    
    $n = 1;
    while ($ds = mysqli_fetch_array($ergebnis)) {
        if ($ds[ 'readgrps' ] != "") {
            $usergrps = explode(";", $ds[ 'readgrps' ]);
            $usergrp = 0;
            foreach ($usergrps as $value) {
                if (isinusergrp($value, $userID)) {
                    $usergrp = 1;
                    break;
                }
            }
            if (!$usergrp && !ismoderator($userID, $ds[ 'boardID' ])) {
                continue;
            }
        }
        
        $topictitle_full = $ds[ 'topic' ];
        $topictitle = $topictitle_full;

        #@$maxlatesttopicchars = $ds[ 'latesttopicchars' ];
        $maxblogchars = 25;
        if(mb_strlen($topictitle)>$maxblogchars) {
            $topictitle=mb_substr($topictitle, 0, $maxblogchars);
            $topictitle.='...';
        }

        /*$maxblogchars = 60;
        if(mb_strlen($text)>$maxblogchars) {
            $text=mb_substr($text, 0, $maxblogchars);
            $text.='...';
        }*/

        $topictitle = htmlspecialchars($topictitle);

        $last_poster = $ds[ 'nickname' ];
        $board = $ds[ 'name' ];
        $date = getformatdatetime($ds[ 'lastdate' ]);
        $small_date = date('d.m H:i', $ds[ 'lastdate' ]);
        $replys = $ds['replys'];

        $replys_text = ($replys == 1) ? $plugin_language['reply'] : $plugin_language['replies'];

        $latesticon = '<img src="images/icons/' . $ds[ 'icon' ] . '" width="15" height="15" alt="">';
        $boardlink = '<a href="index.php?site=forum&amp;board=' . $ds[ 'boardID' ] . '">' . $board . '</a>';
        $topiclink  =   '<li class="list-group-item"><i class="bi bi-chat-text"></i> <a class="" href="index.php?site=forum_topic&amp;topic='.$ds['topicID'].'&amp;type=ASC&amp;page='.ceil(($ds['replys']+1)/$maxposts).'"  data-toggle="tooltip" data-bs-html="true" title="
        <b>'.$topictitle.'</b><br>
        '.$plugin_language['board'].': '.$board.'<br>
        <small>('.$replys.' '.$replys_text.')</small><br>
        <small>'.$plugin_language['last_post'].': '.$last_poster.' '.$date.'</small>">

        <b>'.$topictitle.'</b></a><br>'.$date.'</li>

        ';

        $day = date("d", $ds[ 'lastdate' ]);
        $year = date("Y", $ds[ 'lastdate' ]);

        $monate = array(1=>$plugin_language[ 'jan' ],
                            2=>$plugin_language[ 'feb' ],
                            3=>$plugin_language[ 'mar' ],
                            4=>$plugin_language[ 'apr' ],
                            5=>$plugin_language[ 'mar' ],
                            6=>$plugin_language[ 'jun' ],
                            7=>$plugin_language[ 'jul' ],
                            8=>$plugin_language[ 'aug' ],
                            9=>$plugin_language[ 'sep' ],
                           10=>$plugin_language[ 'oct' ],
                           11=>$plugin_language[ 'nov' ],
                           12=>$plugin_language[ 'dec' ]);


            $monat = date("n", $ds[ 'lastdate' ]);


        $title = '<a class="" href="index.php?site=forum_topic&amp;topic='.$ds['topicID'].'&amp;type=ASC&amp;page='.ceil(($ds['replys']+1)/$maxposts).'"  data-toggle="tooltip" data-bs-html="true" title="
        <b>'.$topictitle.'</b><br>
        '.$plugin_language['board'].': '.$board.'<br>
        <small>('.$replys.' '.$replys_text.')</small><br>
        <small>'.$plugin_language['last_post'].': '.$last_poster.' '.$date.'</small>">

        <b>'.$topictitle.'</b></a>';

        $replys = $ds['replys'];
        $text = '<small>('.$replys.' '.$replys_text.')</small><br>
        <small>'.$plugin_language['last_post'].': '.$last_poster.'</small>';
        $date1 = $small_date;

        
        
        $data_array = array();
        $data_array['$topiclink'] = $topiclink;
        $data_array['$title'] = $title;
        $data_array['$text'] = $text;
        $data_array['$date1'] = $date1;
        $data_array['$day'] = $day;
        $data_array['$date2'] = $monate[$monat];
        $data_array['$year'] = $year;
        
        $template = $GLOBALS["_template"]->loadTemplate("widget_forum_sidebar","content", $data_array, $plugin_path);
        echo $template; 
        $n++;
    }
    
    $data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("widget_forum_sidebar","foot", $data_array, $plugin_path);
    echo $template;
}

