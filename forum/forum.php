<?php
/*-----------------------------------------------------------------\
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
\------------------------------------------------------------------*/
# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("forum", $plugin_path);

// -- COMMENTS INFORMATION -- //
include_once("forum_functions.php");

if (isset($_POST[ 'board' ])) {
    $board = (int)$_POST[ 'board' ];
} elseif (isset($_GET[ 'board' ])) {
    $board = (int)$_GET[ 'board' ];
} else {
    $board = null;
}

if (!isset($_GET[ 'page' ])) {
    $page = '';
} else {
    $page = (int)$_GET[ 'page' ];
}
if (!isset($_GET[ 'action' ])) {
    $action = '';
} else {
    $action = $_GET[ 'action' ];
}

function forum_stats()
{
    global $plugin_path;
    
    global $wincolor;
    global $loosecolor;
    global $drawcolor;
    global $_language;


    # Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("forum", $plugin_path);

    $_language->readModule('forum');

    // TODAY birthdays
    $ergebnis = safe_query(
        "SELECT
            nickname, userID, YEAR(CURRENT_DATE()) -YEAR(birthday) 'age'
        FROM
            " . PREFIX . "user
        WHERE
            DATE_FORMAT(`birthday`, '%m%d') = DATE_FORMAT(NOW(), '%m%d')"
    );
    $n = 0;
    $birthdays = '';
    while ($db = mysqli_fetch_array($ergebnis)) {
        $n++;
        $years = $db[ 'age' ];
        if ($n > 1) {
            $birthdays .= ', <a href="index.php?site=profile&amp;id=' . $db[ 'userID' ] . '"><b>' . $db[ 'nickname' ] .
                '</b></a> (' . $years . ')';
        } else {
            $birthdays = '<a href="index.php?site=profile&amp;id=' . $db[ 'userID' ] . '"><b>' . $db[ 'nickname' ] .
                '</b></a> (' . $years . ')';
        }
    }
    if (!$n) {
        $birthdays = $plugin_language[ 'n_a' ];
    }

    // WEEK birthdays
    $ergebnis =
        safe_query(
            "SELECT
                nickname, userID, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(birthday)), '%y') + 1 AS age
            FROM
                " . PREFIX . "user
            WHERE
                IF(DAYOFYEAR(NOW())<=358,((DAYOFYEAR(birthday)>DAYOFYEAR(NOW()))
            AND
                (DAYOFYEAR(birthday)<=DAYOFYEAR(DATE_ADD(NOW(), INTERVAL 7 DAY)))),(DAYOFYEAR(BIRTHDAY)>DAYOFYEAR(NOW())
            OR
                DAYOFYEAR(birthday)<=DAYOFYEAR(DATE_ADD(NOW(), INTERVAL 7 DAY))))
            AND
                birthday !='0000-00-00 00:00:00'
            ORDER BY
                `birthday` ASC"
        );
    $n = 0;
    $birthweek = '';
    while ($db = mysqli_fetch_array($ergebnis)) {
        $n++;
        $years = $db[ 'age' ];
        if ($n > 1) {
            $birthweek .= ', <a href="index.php?site=profile&amp;id=' . $db[ 'userID' ] . '"><b>' . $db[ 'nickname' ] .
                '</b></a> (' . $years . ')';
        } else {
            $birthweek = '<a href="index.php?site=profile&amp;id=' . $db[ 'userID' ] . '"><b>' . $db[ 'nickname' ] .
                '</b></a> (' . $years . ')';
        }
    }
    if (!$n) {
        $birthweek = $plugin_language[ 'n_a' ];
    }

    // WHOISONLINE
    $guests = mysqli_num_rows(safe_query("SELECT ip FROM " . PREFIX . "whoisonline WHERE userID=''"));
    $user = mysqli_num_rows(safe_query("SELECT userID FROM " . PREFIX . "whoisonline WHERE ip=''"));
    $useronline = $guests + $user;

    if ($user == 1) {
        $user_on = $plugin_language[ 'registered_user' ];
    } else {
        $user_on = $user . ' ' . $plugin_language[ 'registered_users' ];
    }

    if ($guests == 1) {
        $guests_on = $plugin_language[ 'guest' ];
    } else {
        $guests_on = $guests . ' ' . $plugin_language[ 'guests' ];
    }

    $ergebnis = safe_query(
        "SELECT
            w.*, u.nickname
        FROM
            " . PREFIX . "whoisonline w
        LEFT JOIN
            " . PREFIX . "user u
        ON
            u.userID = w.userID
        WHERE
            w.ip=''
        ORDER BY
            u.nickname"
    );
    $user_names = "";
    if ($user) {
        $n = 1;
        while ($ds = mysqli_fetch_array($ergebnis)) {
            if (isforumadmin($ds[ 'userID' ])) {
                $nickname = '<span class="badge bg-danger">' . $ds[ 'nickname' ] . '</span>';
            } elseif (isanymoderator($ds[ 'userID' ])) {
                $nickname = '<span class="badge bg-warning text-dark">' . $ds[ 'nickname' ] . '</span>';
            } elseif (isclanmember($ds[ 'userID' ])) {
                $nickname = '<span class="badge bg-success">' . $ds[ 'nickname' ] . '</span>';
            } else {
                $nickname = $ds[ 'nickname' ];
            }
            if ($n > 1) {
                $user_names .= ', <a href="index.php?site=profile&amp;id=' . $ds[ 'userID' ] . '"><b>' . $nickname .
                    '</b></a>';
            } else {
                $user_names =
                    '<a href="index.php?site=profile&amp;id=' . $ds[ 'userID' ] . '"><strong>' .
                    $nickname . '</strong></a>';
            }
            $n++;
        }
    }

    $dt = mysqli_fetch_array(safe_query("SELECT sum(topics), sum(posts) FROM " . PREFIX . "plugins_forum_boards"));
    $topics = $dt[ 0 ];
    $posts = $dt[ 1 ];
    $dt = mysqli_fetch_array(safe_query("SELECT count(userID) FROM " . PREFIX . "user WHERE activated='1'"));
    $registered = $dt[ 0 ];
    $newestuser = safe_query(
        "SELECT userID, nickname FROM " . PREFIX .
        "user WHERE activated='1' ORDER BY registerdate DESC LIMIT 0,1"
    );
    $dn = mysqli_fetch_array($newestuser);
    $dm = mysqli_fetch_array(safe_query("SELECT maxonline FROM " . PREFIX . "counter"));
    $maxonline = $dm[ 'maxonline' ];

    $newestmember =
        '<a href="index.php?site=profile&amp;id=' . $dn[ 'userID' ] . '"><strong>' .
        $dn[ 'nickname' ] . '</strong></a>';

    $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='whoisonline'"));
    if (@$dx[ 'modulname' ] != 'whoisonline') {    
        $whoisonline = '';                    
    } else {    
        $whoisonline = '<br><br>
            <a href="index.php?site=whoisonline#was"><strong>'.$plugin_language[ 'who_was_online' ].'</strong></a>';
    }

    $data_array = array();
    $data_array['$birthdays'] = $birthdays;
    $data_array['$birthweek'] = $birthweek;
    $data_array['$user_on'] = $user_on;
    $data_array['$guests_on'] = $guests_on;
    $data_array['$maxonline'] = $maxonline;
    $data_array['$user_names'] = $user_names;
    $data_array['$posts'] = $posts;
    $data_array['$topics'] = $topics;
    $data_array['$registered'] = $registered;
    $data_array['$newestmember'] = $newestmember;
    $data_array['$whoisonline'] = $whoisonline;

    $data_array['$happy_birthday']=$plugin_language[ 'happy_birthday' ];
    $data_array['$today']=$plugin_language[ 'today' ];
    $data_array['$next_7_days']=$plugin_language[ 'next_7_days' ];
    $data_array['$whos_online']=$plugin_language[ 'whos_online' ];
    $data_array['$now_online']=$plugin_language[ 'now_online' ];
    $data_array['$and']=$plugin_language[ 'and' ];
    $data_array['$maximum_online']=$plugin_language[ 'maximum_online' ];
    $data_array['$admin']=$plugin_language[ 'admin' ];
    $data_array['$moderator']=$plugin_language[ 'moderator' ];
    $data_array['$clanmember']=$plugin_language[ 'clanmember' ];
    #$data_array['$who_was_online']=$plugin_language[ 'who_was_online' ];
    $data_array['$stats']=$plugin_language[ 'stats' ];
    $data_array['$posts_in']=$plugin_language[ 'posts_in' ];
    $data_array['$topic']=$plugin_language[ 'topics' ];
    $data_array['$registered_users']=$plugin_language[ 'registered_users' ];
    $data_array['$newest_member']=$plugin_language[ 'newest_member' ];

    $template = $GLOBALS["_template"]->loadTemplate("forum","stats", $data_array, $plugin_path);
    echo $template;

    }

function boardmain()
{
    global $plugin_path;
    global $maxposts;
    global $userID;
    global $action;
    global $loggedin;
    global $_language;
    global $maxtopics;


define('use_utf8_encode', false);

     # Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("forum", $plugin_path);

    $data_array = array();
    $data_array['$title']=$plugin_language[ 'title' ];
    $data_array['$subtitle']='Forum';
    $template = $GLOBALS["_template"]->loadTemplate("forum","title", $data_array, $plugin_path);
    echo $template;

    $data_array['$messageboard']=$plugin_language[ 'messageboard' ];
    $data_array['$you_are_here']=$plugin_language[ 'you_are_here' ];
    $template = $GLOBALS["_template"]->loadTemplate("forum_topic","title", $data_array, $plugin_path);
    echo $template;
    
    if ($action == "markall") {
        safe_query("UPDATE " . PREFIX . "user SET topics='|' WHERE userID='$userID'");
   }

    $data_array = array();
    $data_array['$board']=$plugin_language[ 'board' ];
    $data_array['$topics']=$plugin_language[ 'topics' ];
    $data_array['$posts']=$plugin_language[ 'posts' ];
    $data_array['$latest_post']=$plugin_language[ 'latest_post' ];

    $template = $GLOBALS["_template"]->loadTemplate("forum","main_head", $data_array, $plugin_path);
    echo $template;
    
    // KATEGORIEN
    $sql_where = '';
    if (isset($_GET[ 'cat' ]) && is_numeric($_GET[ 'cat' ])) {
        $sql_where = " WHERE catID='" . (int)$_GET[ 'cat' ] . "'";
    }
    $kath = safe_query(
        "SELECT catID, name, info, readgrps FROM " . PREFIX . "plugins_forum_categories" . $sql_where .
        " ORDER BY sort"
    );
    while ($dk = mysqli_fetch_array($kath)) {


        $kathname = "<a href='index.php?site=forum&amp;cat=" . $dk[ 'catID' ] . "'>" . $dk[ 'name' ] . "</a>";
        

        if ($dk[ 'info' ]) {
            $info = $dk[ 'info' ];
        } else {
            $info = '';
        }

        if ($dk[ 'readgrps' ] != "") {
            $usergrp = 0;
            $readgrps = explode(";", $dk[ 'readgrps' ]);
            foreach ($readgrps as $value) {
                if (isinusergrp($value, $userID)) {
                    $usergrp = 1;
                    break;
                }
            }

            if (!$usergrp) {
                continue;
            }
        }
        $data_array = array();
        $data_array['$kathname'] = $kathname;
        $data_array['$info'] = $info;

        $template = $GLOBALS["_template"]->loadTemplate("forum","main_kath", $data_array, $plugin_path);
        echo $template;


        // BOARDS MIT KATEGORIE
        $boards = safe_query(
            "SELECT * FROM " . PREFIX . "plugins_forum_boards WHERE category='" . $dk[ 'catID' ] .
            "' ORDER BY sort"
        );
        $i = 1;

        while ($db = mysqli_fetch_array($boards)) {
            
            $ismod = ismoderator($userID, $db[ 'boardID' ]);
            $usergrp = 0;
            $writer = 'ro-';
            if ($db[ 'writegrps' ] != "" && !$ismod) {
                $writegrps = explode(";", $db[ 'writegrps' ]);
                foreach ($writegrps as $value) {
                    if (isinusergrp($value, $userID)) {
                        $usergrp = 1;
                        $writer = '';
                        break;
                    }
                }
            } else {
                $writer = '';
            }
            if ($db[ 'readgrps' ] != "" && !$usergrp && !$ismod) {
                $readgrps = explode(";", $db[ 'readgrps' ]);
                foreach ($readgrps as $value) {
                    if (isinusergrp($value, $userID)) {
                        $usergrp = 1;
                        break;
                    }
                }
                if (!$usergrp) {
                    continue;
                }
            }

            $board = $db[ 'boardID' ];
            $anztopics = $db[ 'topics' ];
            $anzposts = $db[ 'posts' ];
            $boardname = '<a href="index.php?site=forum&amp;board=' . $board . '"><strong>' .
                $db[ 'name' ] . '</strong></a>';

            if ($db[ 'info' ]) {
                $boardinfo = $db[ 'info' ];
            } else {
                $boardinfo = '';
            }
            $moderators = getmoderators($db[ 'boardID' ]);
            if ($moderators) {
                $moderators = $plugin_language[ 'moderated_by' ] . ': ' . $moderators;
            }
            
            

            $topictitle = '';
            $postlink = '';
            $date = '';
            $time = '';
            $poster = '';
            $member = '';

            $q = safe_query(
                "SELECT topicID, topic, lastdate, lastposter, replys FROM " . PREFIX .
                "plugins_forum_topics WHERE boardID='" . $db[ 'boardID' ] . "' AND moveID='0' ORDER BY lastdate DESC LIMIT 0," .
                $maxtopics
            );
            $n = 1;
            $board_topics = array();
            while ($lp = mysqli_fetch_assoc($q)) {
                if ($n == 1) {
                    $date = getformatdate($lp[ 'lastdate' ]);
                    $today = getformatdate(time());
                    $yesterday = getformatdate(time() - 3600 * 24);

                    $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
                    if (@$dx[ 'modulname' ] != 'squads') {    
                        $member = '';                    
                    } else {
                        if (isclanmember($lp[ 'lastposter' ])) {
                            $member = ' <i class="bi bi-person" style="color: #5cb85c"></i>';
                        } else {
                            $member = '';
                        }
                    }

                    if ($date == $today) {
                        $date = $plugin_language[ 'today' ];
                    } elseif ($date == $yesterday && $date < $today) {
                        $date = $plugin_language[ 'yesterday' ];
                    }

                    $time = getformattime($lp[ 'lastdate' ]);
                    if(deleteduser($lp[ 'lastposter' ]) == '0') {
                        $poster = '<a href="index.php?site=profile&amp;id=' . $lp[ 'lastposter' ] . '">' .
                            getnickname($lp[ 'lastposter' ]) . '</a>';
                    } else {
                        $poster = getnickname($lp[ 'lastposter' ]);

                    }
                    // postername without link //
                    $postername = '' .
                        getnickname($lp[ 'lastposter' ]) . '';
                    // get userid for avatar //
                    $user = getuserid($poster);
                    // get avatar from lastposter //
                    $avatar = getavatar($lp[ 'lastposter' ]);
                    
                    $topic = $lp[ 'topicID' ];
                    $postlink = 'index.php?site=forum_topic&amp;topic=' . $topic . '&amp;type=ASC&amp;page=' .
                        ceil(($lp[ 'replys' ] + 1) / $maxposts);
                    $topictitletooltip = $lp['topic'];
                    $topictitle = $lp['topic'];
                                if (mb_strlen($topictitle) > 22) {
                    $topictitle = mb_substr($topictitle, 0, 22);
                    $topictitle .= '...';
                    }
                    $topictitle = $topictitle;
                        }
                if ($userID) {
                    $board_topics[ ] = $lp[ 'topicID' ];
                } else {
                    break;
                }
                $n++;
            }

            // get unviewed topics

            $found = false;

            if ($userID) {
                $gv = mysqli_fetch_array(safe_query("SELECT topics FROM " . PREFIX . "user WHERE userID='$userID'"));
                $array = explode("|", $gv[ 'topics' ]);

                foreach ($array as $split) {
                    if ($split != "" && in_array($split, $board_topics)) {
                        $found = true;
                        break;
                    }
                }
            }

            if ($found) {
                $icon = '<button type="button" class="btn btn-primary"><i class="bi bi-chat-dots"></i></button>';
            } else {
                $icon = '<button type="button" class="btn btn-secondary"><i class="bi bi-chat"></i></button>';
            }

            // no entry mod - shows an text if no topic is open, also shows lastposter avatar from user if an topic is open //          
            if($topictitle == ''){
                $noentry = $plugin_language['no_entry'];
            }
            else{
                $noentry = '<img class="avatar_small" src="../../images/avatars/'.$avatar.'" alt="'.$postername.'" title="'.$postername.'" /><a href="'.$postlink.'" data-toggle="tooltip" title="'.$topictitletooltip.'">&nbsp;&nbsp;'.$topictitle.'</a><br /><small>'.$poster.''.$member.' - '.$date.'</small>';
            }


            $data_array = array();
            $data_array['$icon'] = $icon;
            $data_array['$boardname'] = $boardname;
            $data_array['$boardinfo'] = $boardinfo;
            $data_array['$moderators'] = $moderators;
            $data_array['$anztopics'] = $anztopics;
            $data_array['$anzposts'] = $anzposts;
            $data_array['$postlink'] = $postlink;
            $data_array['$date'] = $date;
            $data_array['$time'] = $time;
            $data_array['$poster'] = $poster;
            $data_array['$member'] = $member;
            $data_array['$topictitle'] = $topictitle;
            $data_array['$noentry'] = $noentry; 
            // no entry-mod end //  

            $data_array['$topic']=$plugin_language[ 'topics' ];
            $data_array['$posts']=$plugin_language[ 'posts' ];
            $data_array['$latest_post']=$plugin_language[ 'latest_post' ];

            $template = $GLOBALS["_template"]->loadTemplate("forum","main_board", $data_array, $plugin_path);
            echo $template;

            $i++;
        }
    }

    // BOARDS OHNE KATEGORIE
    $boards = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_boards WHERE category='0' ORDER BY sort");
    $i = 1;
    while ($db = mysqli_fetch_array($boards)) {
        
        $usergrp = 0;
        $writer = 'ro-';
        $ismod = ismoderator($userID, $db[ 'boardID' ]);
        if ($db[ 'writegrps' ] != "" && !$ismod) {
            $writegrps = explode(";", $db[ 'writegrps' ]);
            foreach ($writegrps as $value) {
                if (isinusergrp($value, $userID)) {
                    $usergrp = 1;
                    $writer = '';
                    break;
                }
            }
        } else {
            $writer = '';
        }
        if ($db[ 'readgrps' ] != "" && !$usergrp && !$ismod) {
            $readgrps = explode(";", $db[ 'readgrps' ]);
            foreach ($readgrps as $value) {
                if (isinusergrp($value, $userID)) {
                    $usergrp = 1;
                    break;
                }
            }
            if (!$usergrp) {
                continue;
            }
        }

        $board = $db[ 'boardID' ];
        $anztopics = $db[ 'topics' ];
        $anzposts = $db[ 'posts' ];

        $boardname = $db[ 'name' ];
        $boardname = '<a href="index.php?site=forum&amp;board=' . $db[ 'boardID' ] . '"><strong>' .
            $boardname . '</strong></a>';

        $boardinfo = '';
        if ($db[ 'info' ]) {
            $boardinfo = $db[ 'info' ];
        }
        $moderators = getmoderators($db[ 'boardID' ]);
        if ($moderators) {
            $moderators = $plugin_language[ 'moderated_by' ] . ': ' . $moderators;
        }

        $postlink = '';
        $date = '';
        $time = '';
        $poster = '';
        $member = '';

        $q = safe_query(
            "SELECT topicID, lastdate, lastposter, replys FROM " . PREFIX . "plugins_forum_topics WHERE boardID='" .
            $db[ 'boardID' ] . "' AND moveID='0' ORDER BY lastdate DESC LIMIT 0," . $maxtopics
        );
        $n = 1;
        $board_topics = array();
        while ($lp = mysqli_fetch_assoc($q)) {
            if ($n == 1) {
                $date = getformatdate($lp[ 'lastdate' ]);
                $today = getformatdate(time());
                $yesterday = getformatdate(time() - 3600 * 24);

                if ($date == $today) {
                    $date = $plugin_language[ 'today' ];
                } elseif ($date == $yesterday && $date < $today) {
                    $date = $plugin_language[ 'yesterday' ];
                }

                $time = getformattime($lp[ 'lastdate' ]);
                if(deleteduser($lp[ 'lastposter' ]) == '0') {
                $poster = '<a href="index.php?site=profile&amp;id=' . $lp[ 'lastposter' ] . '">' .
                    getnickname($lp[ 'lastposter' ]) . '</a>';
                } else {
                    $poster = getnickname($lp[ 'lastposter' ]);
                }
                
                $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
                if (@$dx[ 'modulname' ] != 'squads') {    
                    $member = '';                    
                } else {
                    if (isclanmember($lp[ 'lastposter' ])) {
                        $member = ' <i class="bi bi-person" style="color: #5cb85c"></i>';
                    } else {
                        $member = '';
                    }
                }

                $topic = $lp[ 'topicID' ];
                $postlink = 'index.php?site=forum_topic&amp;topic=' . $topic . '&amp;type=ASC&amp;page=' .
                    ceil(($lp[ 'replys' ] + 1) / $maxposts);
            }
            if ($userID) {
                $board_topics[ ] = $lp[ 'topicID' ];
            } else {
                break;
            }
            $n++;
        }

        // get unviewed topics

        $found = false;

        if ($userID) {
            $gv = mysqli_fetch_array(safe_query("SELECT topics FROM " . PREFIX . "user WHERE userID='$userID'"));
            $array = explode("|", $gv[ 'topics' ]);

            foreach ($array as $split) {
                if ($split != "" && in_array($split, $board_topics)) {
                    $found = true;
                    break;
                }
            }
        }

        if ($found) {
            $icon =
                '<img src="images/icons/boardicons/' . $writer . 'on.gif" alt="' . $plugin_language[ 'new_posts' ] . '">';
        } else {
            $icon = '<img src="images/icons/boardicons/' . $writer . 'off.gif" alt="' . $plugin_language[ 'no_new_posts' ] . '">';
        }

        $data_array = array();
        $data_array['$icon'] = $icon;
        $data_array['$boardname'] = $boardname;
        $data_array['$boardinfo'] = $boardinfo;
        $data_array['$moderators'] = $moderators;
        $data_array['$anztopics'] = $anztopics;
        $data_array['$anzposts'] = $anzposts;
        $data_array['$postlink'] = $postlink;
        $data_array['$date'] = $date;
        $data_array['$time'] = $time;
        $data_array['$poster'] = $poster;
        $data_array['$member'] = $member;

        $template = $GLOBALS["_template"]->loadTemplate("forum","main_board", $data_array, $plugin_path);
        echo $template;
        $i++;
    }

    $template = $GLOBALS["_template"]->loadTemplate("forum","main_foot", $data_array, $plugin_path);
    echo $template;

    if ($loggedin) {


        $data_array = array();
        $data_array['$new_posts']=$plugin_language[ 'new_posts' ];
        $data_array['$no_new_posts']=$plugin_language[ 'no_new_posts' ];
        $data_array['$read_only']=$plugin_language[ 'read_only' ];
        $data_array['$mark_all_read']=$plugin_language[ 'mark_all_read' ];

        $template = $GLOBALS["_template"]->loadTemplate("forum","main_legend", $data_array, $plugin_path);
        echo $template;

    }

    forum_stats();
} 

function showboard($board)
{


    global $plugin_path;
    global $userID;
    global $loggedin;
    global $maxtopics;
    global $maxposts;
    global $page;
    global $action;
    
    # Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("forum", $plugin_path);

    $data_array = array();
    $data_array['$title']=$plugin_language[ 'title' ];
    $data_array['$subtitle']='Forum';
    $template = $GLOBALS["_template"]->loadTemplate("forum","title", $data_array, $plugin_path);
    echo $template;

    $alle = safe_query("SELECT topicID FROM " . PREFIX . "plugins_forum_topics WHERE boardID='$board'");
    $gesamt = mysqli_num_rows($alle);

    if ($action == "markall" && $userID) {
        $gv = mysqli_fetch_array(safe_query("SELECT topics FROM " . PREFIX . "user WHERE userID='$userID'"));

        $board_topics = array();
        while ($ds = mysqli_fetch_array($alle)) {
            $board_topics[ ] = $ds[ 'topicID' ];
        }

        $array = explode("|", $gv[ 'topics' ]);
        $new = '|';

        foreach ($array as $split) {
            if ($split != "" && !in_array($split, $board_topics)) {
                $new .= $split . '|';
            }
        }

        safe_query("UPDATE " . PREFIX . "user SET topics='" . $new . "' WHERE userID='$userID'");
    }

    if (!isset($page) || $page == '') {
        $page = 1;
    }
    $max = $maxtopics;
    $pages = ceil($gesamt / $max);

    $page_link = '';
    if ($pages > 1) {
        $page_link = makepagelink("index.php?site=forum&amp;board=$board", $page, $pages);
    }

    if ($page <= 1) {
        $start = 0;
    } else {
        $start = $page * $max - $max;
    }

    $db = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_forum_boards WHERE boardID='" . $board . "' "));
    $boardname = $db[ 'name' ];

    $usergrp = 0;
    $writer = 0;

    $ismod = false;
    if (ismoderator($userID, $board) || isforumadmin($userID)) {
        $ismod = true;
    }

    if ($db[ 'writegrps' ] != "" && !$ismod) {
        $writegrps = explode(";", $db[ 'writegrps' ]);
        foreach ($writegrps as $value) {
            if (isinusergrp($value, $userID)) {
                $usergrp = 1;
                $writer = 1;
                break;
            }
        }
    } else {
        $writer = 1;
    }
    if ($db[ 'readgrps' ] != "" && !$usergrp && !$ismod) {
        $readgrps = explode(";", $db[ 'readgrps' ]);
        foreach ($readgrps as $value) {
            if (isinusergrp($value, $userID)) {
                $usergrp = 1;
                break;
            }
        }
        if (!$usergrp) {
            echo $plugin_language[ 'no_permission' ];
            redirect('index.php?site=forum', '', 2);
            return;
        }
    }

    $moderators = getmoderators($board);
    if ($moderators) {
        $moderators = '(' . $plugin_language[ 'moderated_by' ] . ': ' . $moderators . ')';
    }

    $actions = '<a href="index.php?site=search" class="btn btn-secondary"><i class="bi bi-search"></i> ' . $plugin_language[ 'search' ] . '</a>';
    if ($loggedin) {
        $mark = '<a href="index.php?site=forum&amp;board=' . $board . '&amp;action=markall">' .
            $plugin_language[ 'mark_topics_read' ] . '</a>';
        if ($writer) {
            $actions .= ' <a href="index.php?site=forum&amp;addtopic=true&amp;board=' .$board . '" class="btn btn-primary"><i class="bi bi-chat-dots"></i> ' .$plugin_language[ 'new_topic' ] . '</a>';
        }
    } else {
        $mark = '';
    }

    $cat = $db[ 'category' ];
    $kathname = getcategoryname($cat);
    $data_array = array();
    $data_array['$cat'] = $cat;
    $data_array['$kathname'] = $kathname;
    $data_array['$boardname'] = $boardname;
    $data_array['$moderators'] = $moderators;

    $data_array['$messageboard']=$plugin_language[ 'messageboard' ];
    $data_array['$you_are_here']=$plugin_language[ 'you_are_here' ];
    $template = $GLOBALS["_template"]->loadTemplate("forum","head", $data_array, $plugin_path);
    echo $template;

    // TOPICS

    $topics = safe_query(
        "SELECT * FROM " . PREFIX .
        "plugins_forum_topics WHERE boardID='$board' ORDER BY sticky DESC, lastdate DESC LIMIT $start,$max"
    );
    $anztopics = mysqli_num_rows(safe_query("SELECT boardID FROM " . PREFIX . "plugins_forum_topics WHERE boardID='$board'"));

    $i = 1;
    unset($link);
    if ($anztopics) {
        $data_array = array();
        $data_array['$page_link'] = $page_link;
        $data_array['$actions'] = $actions;

        $data_array['$topic']=$plugin_language[ 'topic' ];
        $data_array['$author']=$plugin_language[ 'author' ];
        $data_array['$replies']=$plugin_language[ 'replies' ];
        $data_array['$views']=$plugin_language[ 'views' ];
        $data_array['$lastpost']=$plugin_language[ 'lastpost' ];

        $template = $GLOBALS["_template"]->loadTemplate("forum_topics","head", $data_array, $plugin_path);
        echo $template;

        while ($dt = mysqli_fetch_array($topics)) {
            
            if ($dt[ 'moveID' ]) {
                $gesamt = 0;
            } else {
                $gesamt = $dt[ 'replys' ] + 1;
            }

            $topicpages = 1;
            $topicpages = ceil($gesamt / $maxposts);

            $topicpage_link = '';
            if ($topicpages > 1) {
                $topicpage_link =
                    makepagelink("index.php?site=forum_topic&amp;topic=" . $dt[ 'topicID' ], 1, $topicpages);
            }

            if ($dt[ 'icon' ]) {
                $icon = '<i class="bi ' . $dt[ 'icon' ] . ' " style="font-size: 2rem;"></i>';
            } else {
                $icon = '';
            }

            // viewed topics

            if ($dt[ 'sticky' ]) {
                $onicon =
                    '<button type="button" class="btn btn-info"><i class="bi bi-pin"></i></button>';
                $officon =
                    '<button type="button" class="btn btn-warning"><i class="bi bi-pin"></i></button>';
                $onhoticon =
                    '<button type="button" class="btn btn-info"><i class="bi bi-pin"></i></button>';
                $offhoticon =
                    '<button type="button" class="btn btn-warning"><i class="bi bi-pin"></i></button>';
            } else {
                $onicon =
                    '<button type="button" class="btn btn-primary"><i class="bi bi-chat-dots"></i></button>';
                $officon =
                    '<button type="button" class="btn btn-secondary"><i class="bi bi-chat"></i></button>';
                $onhoticon =
                    '<button type="button" class="btn btn-primary"><i class="bi bi-chat-dots"></i></button>';
                $offhoticon =
                    '<button type="button" class="btn btn-secondary"><i class="bi bi-chat"></i></button>';
            }

            if ($dt[ 'closed' ]) {
                $folder =
                    '<button type="button" class="btn btn-danger"><i class="bi bi-lock"></i></button>';
            } elseif ($dt[ 'moveID' ]) {
                $folder = '<button type="button" class="btn btn-secondary"><i class="bi bi-arrow-right"></i></button>';
            } elseif ($userID) {
                $is_unread = mysqli_num_rows(
                    safe_query(
                        "SELECT userID FROM " . PREFIX . "user WHERE topics LIKE '%|" .
                        $dt[ 'topicID' ] . "|%' AND userID='" . $userID . "'"
                    )
                );

                if ($is_unread) {
                    if ($dt[ 'replys' ] > 15 || $dt[ 'views' ] > 150) {
                        $folder = $onhoticon;
                    } else {
                        $folder = $onicon;
                    }
                } else {
                    if ($dt[ 'replys' ] > 15 || $dt[ 'views' ] > 150) {
                        $folder = $offhoticon;
                    } else {
                        $folder = $officon;
                    }
                }
            } else {
                if ($gesamt > 15) {
                    $folder = $offhoticon;
                } else {
                    $folder = $officon;
                }
            }
            // end viewed topics

            $topictitle = getinput($dt[ 'topic' ]);
            $topictitle = str_break($topictitle, 40);
            $voteicon = '';
            $ergebnis1=mysqli_num_rows(safe_query("SELECT * FROM ".PREFIX."plugins_forum_poll WHERE topicID='".$dt[ 'topicID' ]."'"));
            if($ergebnis1 > '0' ) {
                $voteicon = '<i class="bi bi-bar-chart-fill" style="font-size: 2rem;"></i>';
            }
            if(deleteduser($dt[ 'userID' ]) == '0') {
                $poster = '<a href="index.php?site=profile&amp;id=' . $dt[ 'userID' ] . '">' .
                    getnickname($dt[ 'userID' ]) . '</a>';
            } else {
                $poster = getnickname($dt[ 'userID' ]);
            }

            $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
            if (@$dx[ 'modulname' ] != 'squads') {    
                $member = '';                    
            } else {
                if (isset($posterID) && isclanmember($posterID)) {
                    $member1 = ' <i class="bi bi-person" style="color: #5cb85c"></i>';
                } else {
                    $member1 = '';
                }
            }
            
            $replys = '0';
            $views = '0';

            if ($dt[ 'moveID' ]) {
                // MOVED TOPIC
                $move = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_topics WHERE topicID='" . $dt[ 'moveID' ] . "'");
                $dm = mysqli_fetch_array($move);

                if ($dm[ 'replys' ]) {
                    $replys = $dm[ 'replys' ];
                }
                if ($dm[ 'views' ]) {
                    $views = $dm[ 'views' ];
                }

                $date = getformatdate($dm[ 'lastdate' ]);
                $time = getformattime($dm[ 'lastdate' ]);
                $today = getformatdate(time());
                $yesterday = getformatdate(time() - 3600 * 24);
                if ($date == $today) {
                    $date = $plugin_language[ 'today' ] . ", " . $time;
                } elseif ($date == $yesterday && $date < $today) {
                    $date = $plugin_language[ 'yesterday' ] . ", " . $time;
                } else {
                    $date = $date . ", " . $time;
                }
                if(deleteduser($dm[ 'lastposter' ]) == '1') {
                    $lastposter = getnickname($dm['lastposter']);
                } else {
                    $lastposter = '<a href="index.php?site=profile&amp;id=' . $dm[ 'lastposter' ] . '">' .
                        getnickname($dm[ 'lastposter' ]) . '</a>';

                }
                $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
                if (@$dx[ 'modulname' ] != 'squads') {    
                    $member = '';                    
                } else {
                    if (isclanmember($dm[ 'lastposter' ])) {
                        $member = ' <i class="bi bi-person" style="color: #5cb85c"></i>';
                    } else {
                        $member = '';
                    }
                }

                $link = '<a href="index.php?site=forum_topic&amp;topic=' . $dt[ 'moveID' ] . '"><b>' .
                    $plugin_language[ 'moved' ] . ': ' . $topictitle . '</b></a>';

                    if ($getavatar = getavatar($dm['lastposter'])) {
                    $avatar = '<img class="avatar_small" src="images/avatars/' . $getavatar . '" alt="'.$dm['lastposter'].'">';
                } else {
                    $avatar = '';
                }

                $postlink = 'index.php?site=forum_topic&amp;topic=' . $dm[ 'topicID' ] . '&amp;type=ASC&amp;page=' .
                        ceil(($dm[ 'replys' ] + 1) / $maxposts).'';

                } else {
                // NO MOVED TOPIC
                if ($dt[ 'replys' ]) {
                    $replys = $dt[ 'replys' ];
                }
                if ($dt[ 'views' ]) {
                    $views = $dt[ 'views' ];
                }

                $date = getformatdate($dt[ 'lastdate' ]);
                $time = getformattime($dt[ 'lastdate' ]);
                $today = getformatdate(time());
                $yesterday = getformatdate(time() - 3600 * 24);

                if ($date == $today) {
                    $date = $plugin_language[ 'today' ] . ", " . $time;
                } elseif ($date == $yesterday && $date < $today) {
                    $date = $plugin_language[ 'yesterday' ] . ", " . $time;
                } else {
                    $date = $date . ", " . $time;
                }

                if(deleteduser($dt[ 'lastposter' ]) == '1') {
                    $lastposter = getnickname($dt['lastposter']);
                } else {
                    $lastposter = '<a href="index.php?site=profile&amp;id=' . $dt[ 'lastposter' ] . '">' .
                        getnickname($dt[ 'lastposter' ]) . '</a>';
                }

                $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
                if (@$dx[ 'modulname' ] != 'squads') {    
                    $member = '';                    
                } else {
                    if (isclanmember($dt[ 'lastposter' ])) {
                        $member = ' <i class="bi bi-person" style="color: #5cb85c"></i>';
                    } else {
                        $member = '';
                    }
                }

                if (mb_strlen($topictitle) > 60) {
                $topictitle = mb_substr($topictitle, 0, 60);
                $topictitle .= ' ...';
                }

                $link = '<a href="index.php?site=forum_topic&amp;topic=' . $dt[ 'topicID' ] . '"><b>' . $topictitle .
                    '</b></a>';

                $postlink = 'index.php?site=forum_topic&amp;topic=' . $dt[ 'topicID' ] . '&amp;type=ASC&amp;page=' .
                        ceil(($dt[ 'replys' ] + 1) / $maxposts).'';

                if ($getavatar = getavatar($dt['lastposter'])) {
                    $avatar = '<img class="avatar_small" src="images/avatars/' . $getavatar . '" alt="'.$dt['lastposter'].'">';
                } else {
                    $avatar = '';
                }
                
            }

            $data_array = array();
            $data_array['$folder'] = $folder;
            $data_array['$icon'] = $icon;
            $data_array['$link'] = $link;
            $data_array['$topicpage_link'] = $topicpage_link;
            $data_array['$poster'] = $poster;
            $data_array['$member'] = $member;
            $data_array['$replys'] = $replys;
            $data_array['$views'] = $views;
            $data_array['$date'] = $date;
            $data_array['$lastposter'] = $lastposter;
            $data_array['$postlink'] = $postlink;
            $data_array['$voteicon'] = $voteicon;
            $data_array['$avatar'] = $avatar;

            $template = $GLOBALS["_template"]->loadTemplate("forum_topics","content", $data_array, $plugin_path);
            echo $template;

            $i++;
            unset($topicpage_link);
            unset($lastposter);
            unset($member);
            unset($member1);
            unset($date);
            unset($time);
            unset($link);
        }
        $template = $GLOBALS["_template"]->loadTemplate("forum_topics","foot", $data_array, $plugin_path);
        echo $template;

    }
    

    $data_array = array();
    $data_array['$page_link'] = $page_link;
    $data_array['$mark'] = $mark;
    $data_array['$actions'] = $actions;

    $template = $GLOBALS["_template"]->loadTemplate("forum","actions", $data_array, $plugin_path);
    echo $template;

    if ($loggedin) {

        $template = $GLOBALS["_template"]->loadTemplate("forum_topics","legend", $data_array, $plugin_path);
        echo $template;

    }

    if (!$loggedin) {
        echo $plugin_language[ 'not_logged_msg' ];
    }

    unset($page_link);
}

if (isset($_POST[ 'submit' ]) || isset($_POST[ 'movetopic' ]) || isset($_GET[ 'addtopic' ])
    || isset($_POST[ 'addtopic' ]) || (isset($_GET[ 'action' ]) && $_GET[ 'action' ] == "admin-action")
    || isset($_POST[ 'admaction' ])
) {
    if (!isset($_POST[ 'admaction' ])) {
        $_POST[ 'admaction' ] = '';
    }

    if ($_POST[ 'admaction' ] == "closetopic") {
       
        $_language->readModule('forum');

        $topicID = (int)$_POST[ 'topicID' ];
        $board = (int)$_POST[ 'board' ];

        if (!isforumadmin($userID) && !ismoderator($userID, $board)) {
            die($plugin_language[ 'no_access' ]);
        }

        safe_query("UPDATE " . PREFIX . "plugins_forum_topics SET closed='1' WHERE topicID='$topicID' ");
        header("Location: index.php?site=forum&board=$board");
    } elseif ($_POST[ 'admaction' ] == "opentopic") {
       
        $_language->readModule('forum');

        $topicID = (int)$_POST[ 'topicID' ];
        $board = (int)$_POST[ 'board' ];

        if (!isforumadmin($userID) && !ismoderator($userID, $board)) {
            die($plugin_language[ 'no_access' ]);
        }

        safe_query("UPDATE " . PREFIX . "plugins_forum_topics SET closed='0' WHERE topicID='$topicID' ");
        header("Location: index.php?site=forum&board=$board");
    } elseif ($_POST[ 'admaction' ] == "deletetopic") {

        $_language->readModule('forum');

        $topicID = (int)$_POST[ 'topicID' ];
        $board = (int)$_POST[ 'board' ];

        if (!isforumadmin($userID) && !ismoderator($userID, $board)) {
            die($plugin_language[ 'no_access' ]);
        }

        $numposts =
            mysqli_num_rows(
                safe_query(
                    "SELECT postID FROM " . PREFIX . "plugins_forum_posts WHERE topicID='" . $topicID .
                    "'"
                )
            );
        $numposts--;

        safe_query(
            "UPDATE " . PREFIX . "plugins_forum_boards SET topics=topics-1, posts=posts-" . $numposts .
            " WHERE boardID='" . $board . "' "
        );
        safe_query("DELETE FROM " . PREFIX . "plugins_forum_topics WHERE topicID='$topicID' ");
        safe_query("DELETE FROM " . PREFIX . "plugins_forum_topics WHERE moveID='$topicID' ");
        safe_query("DELETE FROM " . PREFIX . "plugins_forum_posts WHERE topicID='$topicID' ");
        safe_query("DELETE FROM " . PREFIX . "plugins_forum_poll WHERE topicID='$topicID' ");
        safe_query("DELETE FROM " . PREFIX . "plugins_forum_votes WHERE topicID='$topicID' ");
        header("Location: index.php?site=forum&board=$board");
    } elseif ($_POST[ 'admaction' ] == "stickytopic") {
       
        $_language->readModule('forum');

        $topicID = (int)$_POST[ 'topicID' ];
        $board = (int)$_POST[ 'board' ];

        if (!isforumadmin($userID) && !ismoderator($userID, $board)) {
            die($plugin_language[ 'no_access' ]);
        }

        safe_query("UPDATE " . PREFIX . "plugins_forum_topics SET sticky='1' WHERE topicID='$topicID' ");
        header("Location: index.php?site=forum&board=$board");
    } elseif ($_POST[ 'admaction' ] == "unstickytopic") {
       
        $_language->readModule('forum');

        $topicID = (int)$_POST[ 'topicID' ];
        $board = (int)$_POST[ 'board' ];

        if (!isforumadmin($userID) && !ismoderator($userID, $board)) {
            die($plugin_language[ 'no_access' ]);
        }

        safe_query("UPDATE " . PREFIX . "plugins_forum_topics SET sticky='0' WHERE topicID='$topicID' ");
        header("Location: index.php?site=forum&board=$board");
    } elseif ($_POST[ 'admaction' ] == "delposts") {

        $_language->readModule('forum');

        $topicID = (int)$_POST[ 'topicID' ];
        if (isset($_POST[ 'postID' ]) && is_array($_POST[ 'postID' ])) {
            $postID = $_POST[ 'postID' ];
        } else {
            $postID = array();
        }
        $board = (int)$_POST[ 'board' ];

        if (!isforumadmin($userID) && !ismoderator($userID, $board)) {
            die($plugin_language[ 'no_access' ]);
        }
        $last = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_posts WHERE topicID = '$topicID' ");
        $anz = mysqli_num_rows($last);
        $deleted = false;
        foreach ($postID as $id) {
            if ($anz > 1) {
                safe_query("DELETE FROM " . PREFIX . "plugins_forum_posts WHERE postID='" . (int)$id . "' ");
                safe_query("UPDATE " . PREFIX . "plugins_forum_boards SET posts=posts-1 WHERE boardID='" . $board . "' ");
                $last = safe_query(
                    "SELECT * FROM " . PREFIX .
                    "plugins_forum_posts WHERE topicID = '$topicID' ORDER BY date DESC LIMIT 0,1 "
                );
                $dl = mysqli_fetch_array($last);
                safe_query(
                    "UPDATE " . PREFIX . "plugins_forum_topics SET lastdate='" . $dl[ 'date' ] . "', lastposter='" .
                    $dl[ 'poster' ] . "', lastpostID='" . $dl[ 'postID' ] .
                    "', replys=replys-1 WHERE topicID='$topicID' "
                );
                $deleted = false;
            } else {
                safe_query("DELETE FROM " . PREFIX . "plugins_forum_posts WHERE postID='" . (int)$id . "' ");
                safe_query("DELETE FROM " . PREFIX . "plugins_forum_topics WHERE topicID='$topicID' OR moveID='$topicID'");
                safe_query("UPDATE " . PREFIX . "plugins_forum_boards SET topics=topics-1 WHERE boardID='" . $board . "' ");
                $deleted = true;
            }
        }
        if ($deleted) {
            header("Location: index.php?site=forum&board=$board");
        } else {
            header("Location: index.php?site=forum_topic&topic=$topicID");
        }
    } elseif (isset($_POST[ 'movetopic' ])) {

        $_language->readModule('forum');

        $toboard = (int)$_POST[ 'toboard' ];
        $topicID = (int)$_POST[ 'topicID' ];

        if (!isanyadmin($userID) && !ismoderator($userID, getboardid($topicID))) {
            die($plugin_language[ 'no_access' ]);
        }

        $di = mysqli_fetch_array(
            safe_query(
                "SELECT writegrps, readgrps FROM " . PREFIX .
                "plugins_forum_boards WHERE boardID='$toboard'"
            )
        );

        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_topics WHERE topicID='$topicID'");
        $ds = mysqli_fetch_array($ergebnis);

        if (isset($_POST[ 'movelink' ]) && $ds[ 'boardID' ] != $toboard) {
            safe_query(
                "INSERT INTO " . PREFIX .
                "plugins_forum_topics (boardID, icon, userID, date, topic, lastdate, lastposter, replys, views, closed, moveID)
                values ('" . $ds[ 'boardID' ] . "',
                '', '" . $ds[ 'userID' ] . "',
                '" . $ds[ 'date' ] . "',
                '" . addslashes($ds[ 'topic' ]) . "',
                '" . $ds[ 'lastdate' ] . "',
                '',
                '',
                '',
                '',
                '$topicID') "
            );
        }

        safe_query(
            "UPDATE " . PREFIX . "plugins_forum_topics SET boardID='$toboard', readgrps='" . $di[ 'readgrps' ] .
            "', writegrps='" . $di[ 'writegrps' ] . "' WHERE topicID='$topicID'"
        );
        safe_query("UPDATE " . PREFIX . "plugins_forum_posts SET boardID='$toboard' WHERE topicID='$topicID'");
        $post_num = mysqli_affected_rows($_database) - 1;
        safe_query("UPDATE " . PREFIX . "plugins_forum_boards SET topics=topics+1 WHERE boardID='$toboard'");
        safe_query("UPDATE " . PREFIX . "plugins_forum_boards SET topics=topics-1 WHERE boardID='" . $ds[ 'boardID' ] . "'");
        safe_query(
            "UPDATE " . PREFIX . "plugins_forum_boards SET posts=posts+" . $post_num . " WHERE boardID='" . $toboard .
            "'"
        );
        safe_query(
            "UPDATE " . PREFIX . "plugins_forum_boards SET posts=posts-" . $post_num . " WHERE boardID='" .
            $ds[ 'boardID' ] . "'"
        );

        header("Location: index.php?site=forum&board=$toboard");
    } elseif ($_POST[ 'admaction' ] == "movetopic") {
       
        $_language->readModule('forum');
        if (!isanyadmin($userID) && !ismoderator($userID, getboardid($_POST[ 'topicID' ]))) {
            die($plugin_language[ 'no_access' ]);
        }

        $boards = '';
        $kath = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_categories ORDER BY sort");
        while ($dk = mysqli_fetch_array($kath)) {
            $ergebnis =
                safe_query("SELECT * FROM " . PREFIX . "plugins_forum_boards WHERE category='$dk[catID]' ORDER BY sort");
            while ($db = mysqli_fetch_array($ergebnis)) {
                $boards .= '<option value="' . $db[ 'boardID' ] . '">' . $dk[ 'name' ] . ' - ' . $db[ 'name' ] .
                    '</option>';
            }
        }

        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_boards WHERE category='0' ORDER BY sort");
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $boards .= '<option value="' . $ds[ 'boardID' ] . '">' . $ds[ 'name' ] . '</option>';
        }

        $pagetitle = PAGETITLE;
        
        $data_array = array();
        $data_array['$pagetitle'] = $pagetitle;
        #$data_array['$rewriteBase'] = $rewriteBase;
        $data_array['$boards'] = $boards;
        $data_array['$board'] = (int)$_POST['board'];
        $data_array['$topic'] = (int)$_POST['topicID'];
        
        $data_array['$title']=$plugin_language[ 'title' ];
        $data_array['$subtitle']='Forum';
        $data_array['$you_are_here']=$plugin_language[ 'you_are_here' ];
        $data_array['$messageboard']=$plugin_language[ 'messageboard' ];
        $data_array['$move_topic']=$plugin_language[ 'move_topic' ];
        $data_array['$move_topic_board']=$plugin_language[ 'move_topic_board' ];
        $data_array['$leave_shadowtopic']=$plugin_language[ 'leave_shadowtopic' ];

        $template = $GLOBALS["_template"]->loadTemplate("forum","move_topic", $data_array, $plugin_path);
        echo $template;


    } elseif (isset($_POST[ 'newtopic' ]) && !isset($_POST[ 'preview' ])) {
        
        $_language->readModule('forum');
        
        if (!$userID) {
            die($plugin_language[ 'not_logged' ]);
        }

        $board = (int)$_POST[ 'board' ];
        if (boardexists($board)) {
            /*if (isset($_POST[ 'icon' ])) {
                $icon = $_POST[ 'icon' ];
                if (!file_exists("images/icons/topicicons/" . $icon)) {
                    $icon = "";
                }
            } else {
                $icon = '';
            }*/

            if (isset($_POST['icon'])) {
                $icon = $_POST['icon'];
            } else {
                $icon = '';
            }



            $topicname = $_POST[ 'topicname' ];
            if (!$topicname) {
                $topicname = $plugin_language[ 'default_topic_title' ];
            }
            $message = $_POST['message'];
            $topic_sticky = (isset($_POST[ 'sticky' ])) ? '1' : '0';
            $notify = (isset($_POST[ 'notify' ])) ? '1' : '0';

            $ds = mysqli_fetch_array(
                safe_query(
                    "SELECT readgrps, writegrps FROM " . PREFIX .
                    "plugins_forum_boards WHERE boardID='$board'"
                )
            );

            $writer = 0;
            if ($ds[ 'writegrps' ] != "") {
                $writegrps = explode(";", $ds[ 'writegrps' ]);
                foreach ($writegrps as $value) {
                    if (isinusergrp($value, $userID)) {
                        $writer = 1;
                        break;
                    }
                }
                if (ismoderator($userID, $board)) {
                    $writer = 1;
                }
            } else {
                $writer = 1;
            }
            if (!$writer) {
                die($plugin_language[ 'no_access_write' ]);
            }

            $spamApi = \webspell\SpamApi::getInstance();
            $validation = $spamApi->validate($message);

            $date = time();
            if ($validation == \webspell\SpamApi::NOSPAM) {
                safe_query(
                    "INSERT INTO " . PREFIX .
                    "plugins_forum_topics ( boardID,
                    readgrps,
                    writegrps,
                    userID,
                    date,
                    icon,
                    topic,
                    lastdate,
                    lastposter,
                    replys,
                    views,
                    closed,
                    sticky )
                    values ( '$board',
                    '" . $ds[ 'readgrps' ] . "',
                    '" . $ds[ 'writegrps' ] . "',
                    '$userID',
                    '$date',
                    '" . $icon . "',
                    '" . $topicname . "',
                    '$date',
                    '$userID',
                    '0',
                    '0',
                    '0',
                    '$topic_sticky' ) "
                );
                $id = mysqli_insert_id($_database);
                safe_query("UPDATE " . PREFIX . "plugins_forum_boards SET topics=topics+1 WHERE boardID='" . $board . "'");
                safe_query(
                    "INSERT INTO " . PREFIX .
                    "plugins_forum_posts ( boardID, topicID, date, poster, message )
                    values( '$board',
                    '$id',
                    '$date',
                    '$userID',
                    '" . $message . "' ) "
                );


                //poll

                if($_POST['poll']==1){
                    $ergebnis1=safe_query("SELECT * FROM ".PREFIX."plugins_forum_poll WHERE topicID='".$topic."'");
                    if(mysqli_num_rows($ergebnis1)){
				        safe_query("UPDATE ".PREFIX."plugins_forum_poll SET poll='".mysqli_escape_string($_database,$_POST['poll'])."',
						enddate='".mktime(0,0,0,$_POST['month'],$_POST['day'],$_POST['year'])."', 
						title='".mysqli_escape_string($_database, $_POST['head'])."', 
						value1='".mysqli_escape_string($_database,$_POST['value1'])."', 
						value2='".mysqli_escape_string($_database,$_POST['value2'])."', 
						value3='".mysqli_escape_string($_database,$_POST['value3'])."', 
						value4='".mysqli_escape_string($_database,$_POST['value4'])."', 
						value5= '".mysqli_escape_string($_database,$_POST['value5'])."' WHERE topicID='".$topic."'");
                }else{
				    safe_query("INSERT INTO ".PREFIX."plugins_forum_poll (topicID, poll, title, value1, value2, value3, value4, value5, enddate) 
				    VALUES ('".mysqli_escape_string($_database,$id)."','".mysqli_escape_string($_database,$_POST['poll'])."', '".mysqli_escape_string($_database,$_POST['head'])."', '".mysqli_escape_string($_database,$_POST['value1'])."', '".mysqli_escape_string($_database,$_POST['value2'])."', '".mysqli_escape_string($_database,$_POST['value3'])."', '".mysqli_escape_string($_database,$_POST['value4'])."', '".mysqli_escape_string($_database,$_POST['value5'])."', '".mktime(0,0,0,$_POST['month'],$_POST['day'],$_POST['year'])."')");
                }
	        }else{
                safe_query("DELETE FROM ".PREFIX."plugins_forum_poll WHERE topicID='".$topicID."'");
                safe_query("DELETE FROM ".PREFIX."plugins_forum_votes WHERE topicID='".$topicID."'");
            }
            if($_POST['vote_del']==1){
                safe_query("DELETE FROM ".PREFIX."plugins_forum_votes WHERE topicID='".$topicID."'");
            }

            // end poll

            // check if there are more than 1000 unread topics => delete oldest one
            $dv = mysqli_fetch_array(
                safe_query(
                    "SELECT topics FROM " . PREFIX . "user WHERE userID='" . $userID .
                    "'"
                )
            );
            $array = explode('|', $dv[ 'topics' ]);
            if (count($array) >= 1000) {
                safe_query(
                    "UPDATE " . PREFIX . "user SET topics='|" . implode('|', array_slice($array, 2)) .
                    "' WHERE userID='" . $userID . "'"
                );
            }
            unset($array);

                safe_query(
                    "UPDATE " . PREFIX . "user SET topics=CONCAT(topics, '" . $id .
                    "|')"
                ); // update unread topics, format: |oldstring| => |oldstring|topicID|

                if ($notify) {
                    safe_query("INSERT INTO " . PREFIX . "plugins_forum_notify (topicID, userID) VALUES ('$id', '$userID') ");
                }
            } else {
                safe_query(
                    "INSERT INTO " . PREFIX .
                    "plugins_forum_topics_spam ( boardID, userID, date, icon, topic, sticky, message, rating)
                    values ( '$board',
                    '$userID',
                    '$date',
                    '" . $icon . "',
                    '" . $topicname . "',
                    '$topic_sticky',
                    '" . $message . "',
                    '" . $rating . "') "
                );
            }
            header("Location: index.php?site=forum&board=" . $board . "");
        } else {
            header("Location: index.php?site=forum");
        }
    } elseif (isset($_REQUEST[ 'addtopic' ])) {
        $_language->readModule('forum');
        
        $data_array = array();
        $data_array['$title']=$plugin_language[ 'title' ];
        $data_array['$subtitle']='Forum';
        $template = $GLOBALS["_template"]->loadTemplate("forum","title", $data_array, $plugin_path);
        echo $template;

        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_boards WHERE boardID='$board' ");
        $db = mysqli_fetch_array($ergebnis);
        $boardname = $db[ 'name' ];

        $writer = 0;
        if ($db[ 'writegrps' ] != "") {
            $writegrps = explode(";", $db[ 'writegrps' ]);
            foreach ($writegrps as $value) {
                if (isinusergrp($value, $userID)) {
                    $writer = 1;
                    break;
                }
            }
            if (ismoderator($userID, $board)) {
                $writer = 1;
            }
        } else {
            $writer = 1;
        }
        if (!$writer) {
            die($plugin_language[ 'no_access_write' ]);
        }

        $moderators = '';
        $cat = $db[ 'category' ];
        $kathname = getcategoryname($cat);

        $data_array = array();
        $data_array['$cat'] = $cat;
        $data_array['$kathname'] = $kathname;
        $data_array['$boardname'] = $boardname;
        $data_array['$moderators'] = $moderators;

        $data_array['$messageboard']=$plugin_language[ 'messageboard' ];
        $data_array['$you_are_here']=$plugin_language[ 'you_are_here' ];

        $template = $GLOBALS["_template"]->loadTemplate("forum","head", $data_array, $plugin_path);
        echo $template;

        $message = '';

        if ($loggedin) {
            if (isset($_POST[ 'preview' ])) {
                
                $time = getformattime(time());
                $date = "today";
                $message = 
                    stripslashes(
                        str_replace(
                            array('\r\n', '\n'),
                            array("\n", "\n"),
                            $_POST[ 'message' ]
                        )
                    
                );
                $message = $message;
                $username =
                    '<a href="index.php?site=profile&amp;id=' . $userID . '"><strong>' . getnickname($userID) .
                    '</strong></a>';

                $board = (int)$_POST[ 'board' ];
                $topicname = stripslashes($_POST[ 'topicname' ]);
                if (!isset($postID)) {
                    $postID = '';
                }

                $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
                if (@$dx[ 'modulname' ] != 'squads') {    
                    $member = '';                    
                } else {
                    if (isclanmember($userID)) {
                        $member = ' <i class="bi bi-person" style="color: #5cb85c"></i>';
                    } else {
                        $member = '';
                    }
                }

                if (getavatar($userID)) {
                    $avatar = '<img class="avatar" src="images/avatars/' . getavatar($userID) . '" alt="'.$userID.'">';
                } else {
                    $avatar = '';
                }
                if (getsignatur($userID)) {
                    $signatur = getsignatur($userID);
                } else {
                    $signatur = '';
                }
                if (getemail($userID) && !getemailhide($userID)) {
                    $email = '<a href="mailto:' . mail_protect(getemail($userID)) .
                        '"><i class="bi bi-envelope" title="email"></i> email</a>';
                } else {
                    $email = '';
                }

                $pm = '';
                $buddy = '';
                $statuspic = '<span class="badge bg-success">online</span>';

                if (!validate_url(gethomepage($userID))) {
                    $hp = '';
                } else {
                    $hp = '<a href="' . gethomepage($userID) . '" target="_blank" data-toggle="tooltip" data-placement="top" title="' . $plugin_language['homepage'] . '"><i class="bi bi-house" title="homepage"></i> ' . $plugin_language['homepage'] . '</a>';
                }

                $registered = getregistered($userID);
                $posts = getuserforumposts($userID);
                $_sticky = '';
                if (isforumadmin($userID)) {
                    $usertype = "Administrator";
                    $rang = '<img src="/includes/plugins/forum/images/icons/ranks/admin.png" alt="">';
                } elseif (ismoderator($userID, $board)) {
                    $usertype = $plugin_language[ 'moderator' ];
                    $rang = '<img src="/includes/plugins/forum/images/icons/ranks/moderator.png" alt="">';
                } else {
                    $ergebnis = safe_query(
                        "SELECT * FROM " . PREFIX . "forum_ranks WHERE $posts >= postmin AND $posts <= postmax AND special='0'"
                    );
                    $ds = mysqli_fetch_array($ergebnis);
                    $usertype = $ds[ 'rank' ];
                    $rang = '<img src="/includes/plugins/forum/images/icons/ranks/' . $ds[ 'pic' ] . '" alt="">';
                }

                $specialrang = "";
                $specialtype = "";
                $getrank = safe_query(
                    "SELECT IF
                        (u.special_rank = 0, 0, CONCAT_WS('__',r.rank, r.pic)) as RANK
                    FROM
                        " . PREFIX . "user u LEFT JOIN " . PREFIX . "forum_ranks r ON u.special_rank = r.rankID
                    WHERE
                        userID = '" . $userID . "'"
                );
                $rank_data = mysqli_fetch_assoc($getrank);

                if ($rank_data[ 'RANK' ] != '0') {
                    $tmp_rank = explode("__", $rank_data[ 'RANK' ], 2);
                    $specialrang = $tmp_rank[0];
                    if (!empty($tmp_rank[1]) && file_exists("/includes/plugins/forum/images/icons/ranks/" . $tmp_rank[1])) {
                        $specialtype =
                            "<img src='/includes/plugins/forum/images/icons/ranks/" . $tmp_rank[1] . "' alt = '" . $specialrang . "' />";
                    }
                }

                $actions = '';
                $quote = '';

                echo '<div class="card title" style="text-center;margin-top: 10px;margin-bottom: 10px">
                    <div class="card-body" style="text-align:center"><h4>' . $topicname . '</h4>
                    </div></div>';

                $danke = '';
                $forum_thank = '';

                $data_array = array();
                $data_array['$statuspic'] = $statuspic;
                $data_array['$username'] = $username;
                $data_array['$usertype'] = $usertype;
                $data_array['$quote'] = $quote;
                $data_array['$date'] = $date;
                $data_array['$time'] = $time;
                $data_array['$pm'] = $pm;
                $data_array['$buddy'] = $buddy;
                $data_array['$email'] = $email;
                $data_array['$hp'] = $hp;
                $data_array['$actions'] = $actions;
                $data_array['$avatar'] = $avatar;
                $data_array['$rang'] = $rang;
                $data_array['$posts'] = $posts;
                $data_array['$registered'] = $registered;
                $data_array['$message'] = $message;
                $data_array['$signatur'] = $signatur;
                $data_array['$specialrang'] = $specialrang;
                $data_array['$specialtype'] = $specialtype;
                $data_array['$danke'] = $danke;
                $data_array['$forum_thank'] = $forum_thank;

                $data_array['$post']=$plugin_language[ 'posts' ];
                $data_array['$registere']=$plugin_language[ 'registered' ];
                $data_array['$disfeedback']=$plugin_language[ 'disfeedback' ];
                $data_array['$feedback']=$plugin_language[ 'feedback' ];

                $template = $GLOBALS["_template"]->loadTemplate("forum_topic","content", $data_array, $plugin_path);
                echo $template;

                
            } else {
                $topicname = "";
            }

            
            if (isforumadmin($userID) || ismoderator($userID, $board)) {
                if (isset($_POST[ 'sticky' ])) {
                    $chk_sticky =
                        ' <input class="input" type="checkbox" name="sticky" value="1" '.
                        'checked="checked"> ' . $plugin_language[ 'make_sticky' ];
                } else {
                    $chk_sticky = ' <input class="input" type="checkbox" name="sticky" value="1"> ' .
                        $plugin_language[ 'make_sticky' ];
                }
            } else {
                $chk_sticky = '';
            }

            
            //poll
				$ergebnis1=safe_query("SELECT * FROM ".PREFIX."plugins_forum_poll WHERE topicID='".$board."'");
				if(mysqli_num_rows($ergebnis1)){
					while($dd=mysqli_fetch_array($ergebnis1)){	
						@$topicID=$dd['$board'];
						$title=$dd['title'];
						$day=date("d",$dd['enddate']);
						$month=date("m",$dd['enddate']);
						$year=date("Y",$dd['enddate']);
						$value1=$dd['value1'];
						$value2=$dd['value2'];
						$value3=$dd['value3'];
						$value4=$dd['value4'];
						$value5=$dd['value5'];
						$poll=$dd['poll'];			
					}
				}else{
					$title='';
					$day='';
					$month='';
					$year='';
					$value1='';
					$value2='';
					$value3='';
					$value4='';
					$value5='';
					$poll='';					
				}
			//end poll	


            if (isset($_POST[ 'notify' ])) {
                $notify = ' checked="checked"';
            } else {
                $notify = '';
            }
            if (isset($_POST[ 'topicname' ])) {
                $topicname = getforminput($_POST[ 'topicname' ]);
            }
            if (isset($_POST[ 'message' ])) {
                $message = getforminput($_POST[ 'message' ]);
            }
			
            
            $data_array = array();
            $data_array['$topicname'] = $topicname;
            $data_array['$message'] = $message;
            $data_array['$notify'] = $notify;
            $data_array['$chk_sticky'] = $chk_sticky;
            $data_array['$board'] = $board;
            $data_array['$userID'] = $userID;

            $data_array['$title'] = $title;
            $data_array['$day'] = $day;
            $data_array['$month'] = $month;
            $data_array['$year'] = $year;
            $data_array['$value1'] = $value1;
            $data_array['$value2'] = $value2;
            $data_array['$value3'] = $value3;
            $data_array['$value4'] = $value4;
            $data_array['$value5'] = $value5;

            $data_array['$new_topic']=$plugin_language[ 'new_topic' ];
            $data_array['$topic_icon']=$plugin_language[ 'topic_icon' ];
            $data_array['$no_icon']=$plugin_language[ 'no_icon' ];
            $data_array['$subject']=$plugin_language[ 'subject' ];
            $data_array['$options']=$plugin_language[ 'options' ];
            $data_array['$notif']=$plugin_language[ 'notify' ];
            $data_array['$preview']=$plugin_language[ 'preview' ];
            $data_array['$post_new_topic']=$plugin_language[ 'post_new_topic' ];

            $data_array['$insert_survey']=$plugin_language[ 'insert_survey' ];
            $data_array['$yes']=$plugin_language[ 'yes' ];
            $data_array['$no']=$plugin_language[ 'no' ];
            $data_array['$clear_results']=$plugin_language[ 'clear_results' ];
            $data_array['$end']=$plugin_language[ 'end' ];
            $data_array['$option']=$plugin_language[ 'option' ];

            $template = $GLOBALS["_template"]->loadTemplate("forum","newtopic", $data_array, $plugin_path);
            echo $template;


        } else {
            echo $plugin_language[ 'not_logged_msg' ];
        }
    } elseif (!$_POST[ 'admaction' ]) {
        header("Location: index.php?site=forum");
    }
} elseif (!isset($board)) {
    boardmain();
} else {
    showboard($board);
}
