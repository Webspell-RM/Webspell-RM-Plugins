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
\¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*/

# Sprachdateien aus dem Plugin-Ordner laden
	$pm = new plugin_manager(); 
	$plugin_language = $pm->plugin_language("two_box", $plugin_path);



    $data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("two_box","head", $data_array, $plugin_path);
    echo $template; 

    $data_array = array();
    $data_array['$title']=$plugin_language[ 'title' ];
    $data_array['$news-title']=$plugin_language['news-title'];
    $template = $GLOBALS["_template"]->loadTemplate("two_box","latesttopics_head", $data_array, $plugin_path);
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
       LIMIT 0,4 " 
);

$anz = mysqli_num_rows($ergebnis);
if ($anz) {
    
    
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
        #if (empty($maxlatesttopicchars)) {
        #$maxlatesttopicchars = 30;
        #}  
        
        #if (mb_strlen($topictitle) > $maxlatesttopicchars) {
        #$topictitle = mb_substr($topictitle, 0, $maxlatesttopicchars);
        #$topictitle .= '...';
        #}
		if (mb_strlen($topictitle) > 27) {
        $topictitle = mb_substr($topictitle, 0, 27);
        $topictitle .= '...';
        }
        

        $topictitle = htmlspecialchars($topictitle);

        
        $last_poster = $ds[ 'nickname' ];
        $board = $ds[ 'name' ];
        $date = getformatdatetime($ds[ 'lastdate' ]);
        $small_date = date('d.m H:i', $ds[ 'lastdate' ]);
        $replys = $ds['replys'];

        $latesticon = '<img src="images/icons/' . $ds[ 'icon' ] . '" width="15" height="15" alt="">';
        
        $boardlink = '<a class="list-group-item" href="index.php?site=forum&amp;board=' . $ds[ 'boardID' ] . '">' . $board . '</a>';
        #$topiclink  =   '<a href="index.php?site=forum_topic&amp;topic='.$ds['topicID'].'&amp;type=ASC&amp;page='.ceil(($ds['replys']+1)/$maxposts).'" onmouseover="showWMTT(\'latesttopics_'.$n.'\')" onmouseout="hideWMTT()"><b>'.$topictitle.'</b></a></a>';
        #$topiclink = '
        #<a href="index.php?site=forum_topic&amp;topic='.$ds['topicID'].'&amp;type=ASC&amp;page='.ceil(($ds['replys']+1)/$maxposts).'" class="list-group-item" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="<b>'.$topictitle.'</b><br>'.$last_poster.'<br>'.$date.'<br>Board: '.$board.'">'.$topictitle.'</a>';
        $topiclink = '<a href="index.php?site=forum_topic&amp;topic='.$ds['topicID'].'&amp;type=ASC&amp;page='.ceil(($ds['replys']+1)/$maxposts).'">'.$topictitle.'</a>';

       
        
        $replys_text = ($replys == 1) ? $plugin_language['reply'] : $plugin_language['replies'];

        $data_array = array();
        $data_array['$topiclink'] = $topiclink;
        $data_array['$last_poster'] = $last_poster;
        $data_array['$board'] = $board;
        $data_array['$date'] = $date;
        $data_array['$topictitle_full'] = $topictitle_full;
        $data_array['$topictitle'] = $topictitle;
        $data_array['$replys'] = $replys;

        $data_array['$lang_last_post']=$plugin_language[ 'last_post' ];
        $data_array['$lang_board']=$plugin_language[ 'board' ];
        
        $template = $GLOBALS["_template"]->loadTemplate("two_box","latesttopics_content", $data_array, $plugin_path);
        echo $template; 
        $n++;
    }
    
    
}

$data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("two_box","latesttopics_foot", $data_array, $plugin_path);
    echo $template;

#============================================================


    $data_array = array();
    $data_array['$news']=$plugin_language['news'];
    $data_array['$headlines-title']=$plugin_language['headlines-title'];
    $template = $GLOBALS["_template"]->loadTemplate("two_box","headlines_head", $data_array, $plugin_path);
    echo $template;


if (isset($rubricID) && $rubricID) {
    $only = "AND rubric='" . $rubricID . "'";
} else {
    $only = '';
}

        $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_news_settings");
        $ds = mysqli_fetch_array($settings);

    
        $maxheadlines = $ds[ 'headlines' ];
        if (empty($maxheadlines)) {
        $maxheadlines = 4;
        }
 
$ergebnis = safe_query(
    "SELECT
        *
    FROM
        " . PREFIX . "plugins_news ORDER BY
        date 
    LIMIT 0," . $maxheadlines
);
if (mysqli_num_rows($ergebnis)) {
    
    $n = 1;
    while ($ds = mysqli_fetch_array($ergebnis)) {
        $date = getformatdate($ds[ 'date' ]);
        $time = getformattime($ds[ 'date' ]);
        $news_id = $ds[ 'newsID' ];

        
        $message_array = array();
        $query =
            safe_query(
                "SELECT
                    *
                FROM
                    " . PREFIX . "plugins_news"
            );
        while ($qs = mysqli_fetch_array($query)) {
            $message_array[ ] = array(
                'headline' => $qs[ 'headline' ],
                'message' => $qs[ 'content' ],
            );
          }
        
        $headline = $ds['headline'];
        $headline = $headline;

        if(strlen($headline)>27) {
            $headline = substr($headline, 0, 27)." ...";
        }

        #$line1 = '<a class="p-1 badge bg-primary rounded-0" href="index.php?site=news_contents&amp;newsID=' . $ds[ 'newsID' ] . '" >READMORE</a>';
        #$line = '' . $headline . '';

        #<a href="index.php?site=news_contents&amp;newsID=$news_id" class="list-group-item" data-toggle="tooltip" title="$date - $time">$headline</a>

        $line = '<a href="index.php?site=news_manager&action=news_contents&newsID=' . $ds[ 'newsID' ] . '" class="list-group">'.$headline.'</a>';

        $data_array = array();
        $data_array['$date'] = $date;
        $data_array['$time'] = $time;
        $data_array['$news_id'] = $news_id;
        $data_array['$line'] = $line;
        #$data_array['$line1'] = $line1;
        $data_array['$headline'] = $headline;
        

        $template = $GLOBALS["_template"]->loadTemplate("two_box","headlines_content", $data_array, $plugin_path);
        echo $template;

        $n++;
    }
    
    unset($rubricID);
}
        $data_array = array();
        $template = $GLOBALS["_template"]->loadTemplate("two_box","headlines_foot", $data_array, $plugin_path);
        echo $template;





#==========================================================



$data_array = array();
    $data_array['$downloads']=$plugin_language['downloads'];
    $data_array['$downloads-title']=$plugin_language['downloads-title'];
    $template = $GLOBALS["_template"]->loadTemplate("two_box","files_head", $data_array, $plugin_path);
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
    #echo '<ul class="list-group">';
    while ($ds = mysqli_fetch_array($ergebnis)) {
        $fileID = $ds[ 'fileID' ];
        $count = $ds[ 'downloads' ];
        $filename = $ds[ 'filename' ];
        $date = date("d.m.Y - h:i", $ds[ 'date' ]);
        $number = $n;

        if(strlen($filename)>27) {
            $filename = substr($filename, 0, 27)." ...";
        }

        #$download = '<a href="index.php?site=files&amp;file=$fileID" class="list-gr1oup-item" data-toggle="tooltip" data-html="true" title="Downloads: $count">$filename</a>';

        $download = '<a href="index.php?site=files&amp;file='.$ds[ 'fileID' ].'">'.$filename.'</a>';

        $data_array = array();
        $data_array['$count'] = $count;
        $data_array['$fileID'] = $fileID;
        $data_array['$date'] = $date;

        
        
        $data_array['$filename'] = $filename;

        $data_array['$download'] = $download;



        $template = $GLOBALS["_template"]->loadTemplate("two_box","files_content", $data_array, $plugin_path);
        echo $template;
        
        $n++;
    }
    #echo '</ul>';
    
}

$data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("two_box","files_foot", $data_array, $plugin_path);
        echo $template;



    $data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("two_box","foot", $data_array, $plugin_path);
    echo $template; 
?>   
<script type="text/javascript">
	var exampleEl = document.getElementById('example')
var tooltip = new bootstrap.Tooltip(exampleEl, {
  boundary: document.body // or document.querySelector('#boundary')
})
var exampleTriggerEl = document.getElementById('example')
var tooltip = bootstrap.Tooltip.getOrCreateInstance(exampleTriggerEl) // Returns a Bootstrap tooltip instance

</script> 