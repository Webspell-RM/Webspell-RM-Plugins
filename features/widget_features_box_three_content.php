<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                  Webspell-RM      /                        /   /                                          *
 *                  -----------__---/__---__------__----__---/---/-----__---- _  _ -                         *
 *                   | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                          *
 *                  _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                          *
 *                               Free Content / Management System                                            *
 *                                           /                                                               *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         webspell-rm                                                                              *
 *                                                                                                           *
 * @copyright       2018-2023 by webspell-rm.de                                                              *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de                 *
 * @website         <https://www.webspell-rm.de>                                                             *
 * @forum           <https://www.webspell-rm.de/forum.html>                                                  *
 * @wiki            <https://www.webspell-rm.de/wiki.html>                                                   *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                         *
 *                  It's NOT allowed to remove this copyright-tag                                            *
 *                  <http://www.fsf.org/licensing/licenses/gpl.html>                                         *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                        *
 * @copyright       2005-2011 by webspell.org / webspell.info                                                *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
*/

# Sprachdateien aus dem Plugin-Ordner laden
	$pm = new plugin_manager(); 
	$plugin_language = $pm->plugin_language("features", $plugin_path);



    $data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("features","box_three_head", $data_array, $plugin_path);
    echo $template; 

    $data_array = array();
    $data_array['$title']=$plugin_language[ 'title' ];
    $data_array['$news-title']=$plugin_language['news-title'];
    $template = $GLOBALS["_template"]->loadTemplate("features","box_three_latesttopics_head", $data_array, $plugin_path);
    echo $template;

    $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname= 'forum'"));
    if($dx['modulname']== 'forum' && $dx['activate']== '1') {  

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

                if (mb_strlen($topictitle) > 37) {
                $topictitle = mb_substr($topictitle, 0, 37);
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
                $topiclink = '<a href="index.php?site=forum_topic&amp;topic='.$ds['topicID'].'&amp;type=ASC&amp;page='.ceil(($ds['replys']+1)/$maxposts).'"><i class="bi bi-arrow-bar-right"></i> '.$topictitle.'</a>';       
                
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
                
                $template = $GLOBALS["_template"]->loadTemplate("features","box_three_latesttopics_content", $data_array, $plugin_path);
                echo $template; 
                $n++;
            }    
            
        }

    }else {
        echo''.$plugin_language['no_forum'].'';
    } 

    $data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("features","box_three_latesttopics_foot", $data_array, $plugin_path);
    echo $template;

#============================================================

    $data_array = array();
    $data_array['$news']=$plugin_language['news'];
    $data_array['$headlines-title']=$plugin_language['headlines-title'];
    $template = $GLOBALS["_template"]->loadTemplate("features","box_three_headlines_head", $data_array, $plugin_path);
    echo $template;

    $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname= 'news_manager'"));
    if($dx['modulname']== 'news_manager' && $dx['activate']== '1') {  

        if (isset($rubricID) && $rubricID) {
            $only = "AND rubric='" . $rubricID . "'";
        } else {
            $only = '';
        }

            $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_news_manager_settings");
            $ds = mysqli_fetch_array($settings);
            
            $maxheadlines = $ds[ 'headlines' ];
            if (empty($maxheadlines)) {
                $maxheadlines = 4;
            }
         
        $ergebnis = safe_query(
            "SELECT
                *
            FROM
                " . PREFIX . "plugins_news_manager ORDER BY
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
                            " . PREFIX . "plugins_news_manager"
                    );
                while ($qs = mysqli_fetch_array($query)) {
                    $message_array[ ] = array(
                        'headline' => $qs[ 'headline' ],
                        'message' => $qs[ 'content' ],
                    );
                  }
                
                $headline = $ds['headline'];
                $headline = htmlspecialchars($headline);

                if(strlen($headline)>37) {
                    $headline = substr($headline, 0, 37)." ...";
                }

                $line = '<a href="index.php?site=news_manager&action=news_contents&amp;newsID='.$news_id.'"><i class="bi bi-arrow-right"></i> '.$headline.'</a>';

                $data_array = array();
                $data_array['$date'] = $date;
                $data_array['$time'] = $time;
                $data_array['$news_id'] = $news_id;
                $data_array['$line'] = $line;
                $data_array['$headline'] = $headline;        

                $template = $GLOBALS["_template"]->loadTemplate("features","box_three_headlines_content", $data_array, $plugin_path);
                echo $template;

                $n++;
            }
            
            unset($rubricID);
        }
    }else {
        echo''.$plugin_language['no_news'].'';
    }
    
    $data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("features","box_three_headlines_foot", $data_array, $plugin_path);
    echo $template;

#==========================================================

    $data_array = array();
    $data_array['$downloads']=$plugin_language['downloads'];
    $data_array['$downloads-title']=$plugin_language['downloads-title'];
    $template = $GLOBALS["_template"]->loadTemplate("features","box_three_files_head", $data_array, $plugin_path);
    echo $template;

    $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname= 'files'"));
    if($dx['modulname']== 'files' && $dx['activate']== '1') {   

        $getlist = safe_query("SELECT sc_files FROM " . PREFIX . "plugins_files_settings");
        $ds = mysqli_fetch_array($getlist);

        if ($ds[ 'sc_files' ] == 1) {
            $list = "downloads";
        } else {
            $list = "date";
        }

        $accesslevel = 1;
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
            
            while ($ds = mysqli_fetch_array($ergebnis)) {
                $fileID = $ds[ 'fileID' ];
                $count = $ds[ 'downloads' ];
                $filename = $ds[ 'filename' ];
                $date = date("d.m.Y - h:i", $ds[ 'date' ]);
                $number = $n;

                if(strlen($filename)>37) {
                    $filename = substr($filename, 0, 37)." ...";
                }

                $download = '<a href="index.php?site=files&amp;file='.$ds[ 'fileID' ].'"><i class="bi bi-download"></i> '.$filename.'</a>';

                $data_array = array();
                $data_array['$count'] = $count;
                $data_array['$fileID'] = $fileID;
                $data_array['$date'] = $date;  
                $data_array['$filename'] = $filename;
                $data_array['$download'] = $download;

                $template = $GLOBALS["_template"]->loadTemplate("features","box_three_files_content", $data_array, $plugin_path);
                echo $template;
                
                $n++;
            }
            
        }
    }else {
        echo''.$plugin_language['no_files'].'';
    }    
    $data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("features","box_three_files_foot", $data_array, $plugin_path);
    echo $template;

    $data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("features","box_three_foot", $data_array, $plugin_path);
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