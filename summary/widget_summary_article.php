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
    $plugin_language = $pm->plugin_language("summary", $plugin_path);

    $data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("sc_summary","head", $data_array, $plugin_path);
    echo $template; 

global $maxposts;
if (isset($site)) {
    $_language->readModule('latesttopics');
}
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
       LIMIT 0,2 " 
);

$anz = mysqli_num_rows($ergebnis);

$data_array = array();
    $data_array['$title']=$plugin_language[ 'title' ];
    $data_array['$news-title']=$plugin_language['news-title'];
    $template = $GLOBALS["_template"]->loadTemplate("sc_summary","latesttopics_head", $data_array, $plugin_path);
    echo $template;
if ($anz) {
    
    echo '<ul class="list-group">';
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

        @$maxlatesttopicchars = $ds[ 'latesttopicchars' ];
        if (empty($maxlatesttopicchars)) {
        $maxlatesttopicchars = 50;
        }  
        
        if (mb_strlen($topictitle) > $maxlatesttopicchars) {
        $topictitle = mb_substr($topictitle, 0, $maxlatesttopicchars);
        $topictitle .= '...';
        }

        $topictitle = htmlspecialchars($topictitle);

        $last_poster = $ds[ 'nickname' ];
        $board = $ds[ 'name' ];
        $date = getformatdatetime($ds[ 'lastdate' ]);
        $small_date = date('d.m H:i', $ds[ 'lastdate' ]);
        $replys = $ds['replys'];

        $replys_text = ($replys == 1) ? $plugin_language['reply'] : $plugin_language['replies'];

        $latesticon = '<img src="images/icons/' . $ds[ 'icon' ] . '" width="15" height="15" alt="">';
        $boardlink = '<a href="index.php?site=forum&amp;board=' . $ds[ 'boardID' ] . '">' . $board . '</a>';
        $topiclink  =   '<a class="list-group-item" href="index.php?site=forum_topic&amp;topic='.$ds['topicID'].'&amp;type=ASC&amp;page='.ceil(($ds['replys']+1)/$maxposts).'"  data-toggle="tooltip" data-bs-html="true" title="
        <b>'.$topictitle.'</b><br>
        '.$plugin_language['board'].': '.$board.'<br>
        <small>('.$replys.' '.$replys_text.')</small><br>
        <small>'.$plugin_language['last_post'].': '.$last_poster.' '.$date.'</small>">
        <i class="bi bi-chat-fills"> </i> <b>'.$topictitle.'</b></a>

        ';
        
        
        $data_array = array();
        $data_array['$topiclink'] = $topiclink;
        
        $template = $GLOBALS["_template"]->loadTemplate("sc_summary","latesttopics_content", $data_array, $plugin_path);
        echo $template; 
        $n++;
    }
    echo '</ul>';
    $data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("sc_summary","latesttopics_foot", $data_array, $plugin_path);
    echo $template;
    } else {
    
    $data_array = array();
    $data_array['$no_topics_uploaded']=$plugin_language['no_topics_uploaded'];
    $template = $GLOBALS["_template"]->loadTemplate("sc_summary","latesttopics_no_file", $data_array, $plugin_path);
    echo $template;
    
}

#============================================================

        
        $data_array = array();
        $data_array['$article'] = $plugin_language['article'];
        $data_array['$article-title'] = $plugin_language['article-title'];
        

        $template = $GLOBALS["_template"]->loadTemplate("sc_summary","articles_head", $data_array, $plugin_path);
        echo $template;

$ergebnis = safe_query(
    "SELECT
        *
    FROM
        " . PREFIX . "plugins_articles WHERE displayed = '1' ORDER BY
        date 
    LIMIT 5"
);


if (mysqli_num_rows($ergebnis)) {
    
    while ($ds = mysqli_fetch_array($ergebnis)) {
            
                $question = $ds[ 'question' ];
                $articleID = $ds[ 'articleID' ];

            $data_array = array();
            $data_array['$question'] = $question;
            $data_array['$articleID'] = $articleID;
            $template = $GLOBALS["_template"]->loadTemplate("sc_summary","articles_content", $data_array, $plugin_path);
            echo $template;
            
        }

        $template = $GLOBALS["_template"]->loadTemplate("sc_summary","articles_foot", $data_array, $plugin_path);
        echo $template;
                
    } else {
        
        echo $plugin_language['no_articles_uploaded'];
        $template = $GLOBALS["_template"]->loadTemplate("sc_summary","articles_foot", $data_array, $plugin_path);
        echo $template;
    }


#==========================================================



    $data_array = array();
    $data_array['$downloads']=$plugin_language['downloads'];
    $data_array['$downloads-title']=$plugin_language['downloads-title'];
    $template = $GLOBALS["_template"]->loadTemplate("sc_summary","files_head", $data_array, $plugin_path);
    echo $template;

$getlist = safe_query("SELECT sc_files FROM " . PREFIX . "plugins_files_settings");
$ds = mysqli_fetch_array($getlist);

if ($ds[ 'sc_files' ] == 1) {
    $list = "downloads";
} else {
    $list = "date";
}

$accesslevel = 1;
#if (isclanmember($userID)) {
#    $accesslevel = 2;
#}

$ergebnis = safe_query(
    "SELECT
        *
    FROM
        " . PREFIX . "plugins_files
    WHERE
        accesslevel<=" . $accesslevel . "
    ORDER BY
        " . $list . " DESC
    LIMIT 0,5"
);


$n = 1;
if (mysqli_num_rows($ergebnis)) {
    echo '<ul class="list-group">';
    while ($ds = mysqli_fetch_array($ergebnis)) {
        $fileID = $ds[ 'fileID' ];
        $count = $ds[ 'downloads' ];
        $filename = $ds[ 'filename' ];
        $date = date("d.m.Y - h:i", $ds[ 'date' ]);
        $number = $n;

        $data_array = array();
        $data_array['$count'] = $count;
        $data_array['$fileID'] = $fileID;
        $data_array['$date'] = $date;

        if(strlen($filename)>27) {
            $filename = substr($filename, 0, 27)."..";
        }
        
        $data_array['$filename'] = $filename;

        $template = $GLOBALS["_template"]->loadTemplate("sc_summary","files_content", $data_array, $plugin_path);
        echo $template;
        
        $n++;
    }
    echo '</ul>';
    $data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("sc_summary","files_foot", $data_array, $plugin_path);
    echo $template;

} else {
    
    $data_array = array();
    $data_array['$no_file_uploaded']=$plugin_language['no_file_uploaded'];
    $template = $GLOBALS["_template"]->loadTemplate("sc_summary","files_no_file", $data_array, $plugin_path);
    echo $template;
}
$data_array = array();
    #$data_array['$no_file_uploaded']=$plugin_language['no_file_uploaded'];
    $template = $GLOBALS["_template"]->loadTemplate("sc_summary","foot", $data_array, $plugin_path);
    echo $template;

?>    