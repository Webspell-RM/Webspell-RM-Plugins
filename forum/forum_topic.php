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

    $filepath = $plugin_path."images/icon/ranks/";

if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
}
if (isset($_GET['delete'])) {
    $delete = (bool)$_GET['delete'];
} else {
    $delete = '';
}
if (isset($_GET['edit'])) {
    $edit = (bool)$_GET['edit'];
} else {
    $edit = '';
}
if (isset($_REQUEST['topic'])) {
    $topic = (int)$_REQUEST['topic'];
} else {
    $topic = '';
}
if (isset($_REQUEST['addreply'])) {
    $addreply = (bool)$_REQUEST['addreply'];
} else {
    $addreply = '';
}
if (isset($_GET['type'])) {
    $type = (($_GET['type'] == 'ASC') || ($_GET['type'] == 'DESC')) ? $_GET['type'] : '';
} else {
    $type = '';
}
if (isset($_GET['quoteID'])) {
    $quoteID = (int)$_GET['quoteID'];
} else {
    $quoteID = '';
}
$do_sticky = (isset($_POST['sticky'])) ? true : false;

if (isset($_POST['newreply']) && !isset($_POST['preview'])) {


    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("forum", $plugin_path);

    $_language->readModule('forum');

    if (!$userID) {
        die($plugin_language['not_logged']);
    }

    #$message = cleartext($_POST['message']);
    $message = $_POST['message'];
    $topic = (int)$_POST['topic'];
    $page = (int)$_POST['page'];

    if (!(mb_strlen(trim($message)))) {
        die($plugin_language['forgot_message']);
    }
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT closed, writegrps, boardID FROM " . PREFIX .
            "plugins_forum_topics WHERE topicID='" . $topic . "'"
        )
    );
    if ($ds['closed']) {
        die($plugin_language['topic_closed']);
    }

    $writer = 0;
    if ($ds['writegrps'] != "") {
        $writegrps = explode(";", $ds['writegrps']);
        foreach ($writegrps as $value) {
            if (isinusergrp($value, $userID)) {
                $writer = 1;
                break;
            }
        }
        if (ismoderator($userID, $ds['boardID'])) {
            $writer = 1;
        }
    } else {
        $writer = 1;
    }
    if (!$writer) {
        die($plugin_language['no_access_write']);
    }
    $do_sticky = '';
    if (isforumadmin($userID) || ismoderator($userID, $ds['boardID'])) {
        $do_sticky = (isset($_POST['sticky'])) ? ', sticky=1' : ', sticky=0';
    }

    $spamApi = \webspell\SpamApi::getInstance();
    $validation = $spamApi->validate($message);

    $date = time();
    if ($validation == \webspell\SpamApi::NOSPAM) {
        safe_query(
            "INSERT INTO " . PREFIX . "plugins_forum_posts ( boardID, topicID, date, poster, message ) VALUES( '" .
            $_REQUEST['board'] . "', '$topic', '$date', '$userID', '" . $message . "' ) "
        );
        $lastpostID = mysqli_insert_id($_database);
        safe_query("UPDATE " . PREFIX . "plugins_forum_boards SET posts=posts+1 WHERE boardID='" . $_REQUEST['board'] . "' ");
        safe_query(
            "UPDATE " . PREFIX . "plugins_forum_topics SET lastdate='" . $date . "', lastposter='" . $userID .
            "', lastpostID='" . $lastpostID . "', replys=replys+1 $do_sticky WHERE topicID='$topic' "
        );

        // check if there are more than 1000 unread topics => delete oldest one
        $dv = mysqli_fetch_array(safe_query("SELECT topics FROM " . PREFIX . "user WHERE userID='" . $userID . "'"));
        $array = explode('|', $dv['topics']);
        if (count($array) >= 1000) {
            safe_query(
                "UPDATE " . PREFIX . "user SET topics='|" . implode('|', array_slice($array, 2)) .
                "' WHERE userID='" . $userID . "'"
            );
        }
        unset($array);

        // add this topic to unread
        safe_query(
            "UPDATE " . PREFIX . "user SET topics=CONCAT(topics, '" . $topic . "|') WHERE topics NOT LIKE '%|" .
            $topic . "|%'"
        ); // update unread topics, format: |oldstring| => |oldstring|topicID|

        $emails = array();
        $ergebnis = safe_query(
            "SELECT f.userID, u.email, u.language FROM " . PREFIX . "plugins_forum_notify f JOIN " . PREFIX .
            "user u ON u.userID=f.userID WHERE f.topicID=$topic"
        );
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $emails[] = array('mail' => $ds['email'], 'lang' => $ds['language']);
        }
        safe_query("DELETE FROM " . PREFIX . "plugins_forum_notify WHERE topicID='$topic'");

        if (count($emails)) {
            $de = mysqli_fetch_array(safe_query("SELECT nickname FROM " . PREFIX . "user WHERE userID='$userID'"));
            $poster = $de['nickname'];
            $de = mysqli_fetch_array(safe_query("SELECT topic FROM " . PREFIX . "plugins_forum_topics WHERE topicID='$topic'"));
            $topicname = getinput($de['topic']);

            $link = "http://" . $hp_url . "/index.php?site=forum_topic&topic=" . $topic;
            $maillanguage = new \webspell\Language();
            $maillanguage->setLanguage($default_language);
            $_language->readModule('formvalidation', true);

            foreach ($emails as $email) {
                $maillanguage->setLanguage($email['lang']);
                $maillanguage->readModule('forum');
                $forum_topic_notify = str_replace(
                    array('%poster%', '%topic_link%', '%pagetitle%', '%hpurl%'),
                    array(html_entity_decode($poster), $link, $hp_title, 'http://' . $hp_url),
                    $maillanguage->module['notify_mail']
                );
                $subject = $maillanguage->module['new_reply'] . ' (' . $hp_title . ')';
                $sendmail = \webspell\Email::sendEmail(
                    $admin_email,
                    'Forum',
                    $email['mail'],
                    $subject,
                    $forum_topic_notify
                );

                if ($sendmail['result'] == 'fail') {
                    if (isset($sendmail['debug'])) {
                        $fehler = array();
                        $fehler[] = $sendmail['error'];
                        $fehler[] = $sendmail['debug'];
                        echo generateErrorBoxFromArray($plugin_language['errors_there'], $fehler);
                    }
                }
            }
        }

        if (isset($_POST['notify']) && (bool)$_POST['notify']) {
            safe_query(
                "INSERT INTO " . PREFIX . "plugins_forum_notify (topicID, userID) VALUES('" . $topic . "', '" . $userID .
                "') "
            );
        }
    } else {
        safe_query(
            "INSERT INTO " . PREFIX .
            "plugins_forum_posts_spam ( boardID, topicID, date, poster, message, rating ) VALUES( '" . $_REQUEST['board'] .
            "', '$topic', '$date', '$userID', '" . $message . "', '" . $rating . "' ) "
        );
    }
    header("Location: index.php?site=forum_topic&topic=" . $topic . "&page=" . $page);
    exit();
} elseif (isset($_POST['editreply']) && (bool)$_POST['editreply']) {
    

    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("forum", $plugin_path);

    if (!isforumposter($userID, $_POST['id']) && !isforumadmin($userID) && !ismoderator($userID, $_GET['board'])
    ) {
        die($plugin_language['no_accses']);
    }

    #$message = cleartext($_POST['message']);
    $message = $_POST['message'];
    $id = (int)$_POST['id'];
    $check = mysqli_num_rows(
        safe_query(
            "SELECT postID FROM " . PREFIX . "plugins_forum_posts WHERE postID='" . $id .
            "' AND poster='" . $userID . "'"
        )
    );
    if (($check || isforumadmin($userID) || ismoderator($userID, (int)$_GET['board'])) && mb_strlen(trim($message))
    ) {
        if (isforumadmin($userID) || ismoderator($userID, (int)$_GET['board'])) {
            $do_sticky = (isset($_POST['sticky'])) ? 'sticky=1' : 'sticky=0';
            safe_query(
                "UPDATE " . PREFIX . "plugins_forum_topics SET $do_sticky WHERE topicID='" . (int)$_GET['topic'] .
                "'"
            );
        }

        $date = getformatdatetime(time());
        safe_query("UPDATE " . PREFIX . "plugins_forum_posts SET message = '" . $message . "' WHERE postID='$id' ");
        safe_query(
            "DELETE FROM " . PREFIX . "plugins_forum_notify WHERE userID='$userID' AND topicID='" .
            (int)$_GET['topic'] . "'"
        );
        if (isset($_POST['notify'])) {
            if ((bool)$_POST['notify']) {
                safe_query(
                    "INSERT INTO " . PREFIX .
                    "plugins_forum_notify (`notifyID`, `userID`, `topicID`) VALUES ('', '$userID', '" . (int)$_GET['topic'] .
                    "')"
                );
            }
        }
    }
    header("Location: index.php?site=forum_topic&topic=" . (int)$_GET['topic'] . "&page=" . (int)$_GET['page']);
} elseif (isset($_POST['saveedittopic']) && (bool)$_POST['saveedittopic']) {


    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("forum", $plugin_path);

    $_language->readModule('forum');

    if (!isforumadmin($userID)
        && !isforumposter($userID, $_POST['post']) && !ismoderator($userID, $_GET['board'])
    ) {
        die($plugin_language['no_accses']);
    }

    $board = (int)$_GET['board'];
    $topic = (int)$_GET['topic'];
    $post = $_POST['post'];
    if (isset($_POST['notify'])) {
        $notify = (bool)$_POST['notify'];
    } else {
        $notify = false;
    }
    $topicname = $_POST['topicname'];
    if (!$topicname) {
        $topicname = $plugin_language['default_topic_title'];
    }
    #$message = cleartext($_POST['message']);
    $message = $_POST['message'];
    if (mb_strlen($message)) {
        if (isset($_POST['icon'])) {
            $icon = $_POST['icon'];
        } else {
            $icon = '';
        }
        if (isforumadmin($userID) || ismoderator($userID, $board)) {
            if (isset($_POST['sticky'])) {
                $do_sticky = 1;
            } else {
                $do_sticky = 0;
            }
            safe_query(
                "UPDATE " . PREFIX . "plugins_forum_topics SET sticky='" . $do_sticky . "' WHERE topicID='" . $topic . "'"
            );
        }

        safe_query("UPDATE " . PREFIX . "plugins_forum_posts SET message='" . $message . "' WHERE postID='" . $post . "'");
        safe_query(
            "UPDATE " . PREFIX . "plugins_forum_topics SET topic='" . $topicname . "', icon='" . $icon . "' " .
            "WHERE topicID='" . $topic . "'"
        );



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
				VALUES ('".mysqli_escape_string($_database,$topic)."','".mysqli_escape_string($_database,$_POST['poll'])."', '".mysqli_escape_string($_database,$_POST['head'])."', '".mysqli_escape_string($_database,$_POST['value1'])."', '".mysqli_escape_string($_database,$_POST['value2'])."', '".mysqli_escape_string($_database,$_POST['value3'])."', '".mysqli_escape_string($_database,$_POST['value4'])."', '".mysqli_escape_string($_database,$_POST['value5'])."', '".mktime(0,0,0,$_POST['month'],$_POST['day'],$_POST['year'])."')");
		}
	}else{
		safe_query("DELETE FROM ".PREFIX."plugins_forum_poll WHERE topicID='".$topic."'");
                safe_query("DELETE FROM ".PREFIX."plugins_forum_votes WHERE topicID='".$topic."'");
	}
        if($_POST['vote_del']==1){
          safe_query("DELETE FROM ".PREFIX."plugins_forum_votes WHERE topicID='".$topic."'");
        }

        if ($notify == 1) {
            $notified =
                safe_query(
                    "SELECT * FROM " . PREFIX . "plugins_forum_notify WHERE topicID='" . $topic . "' AND userID='" .
                    $userID . "'"
                );
            if (mysqli_num_rows($notified) != 1) {
                safe_query(
                    "INSERT INTO " . PREFIX .
                    "plugins_forum_notify (notifyID, topicID, userID) VALUES ('', '$topic', '$userID')"
                );
            }
        } else {
            safe_query(
                "DELETE FROM " . PREFIX . "plugins_forum_notify WHERE topicID='" . $topic . "' AND userID='" . $userID .
                "'"
            );
        }
    }
    header("Location: index.php?site=forum_topic&topic=" . $topic);
}

    global $userID;
    global $loggedin;
    global $page;
    global $maxposts;
    global $preview;
    global $message;
    global $picsize_l;
    global $spamapikey;

    # Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("forum", $plugin_path);

    $_language->readModule('forum');
    
    $thread = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_topics WHERE topicID='$topic' ");
    $dt = mysqli_fetch_array($thread);

    $usergrp = 0;
    $writer = 0;
    $ismod = ismoderator($userID, $dt['boardID']);
    if ($dt['writegrps'] != "" && !$ismod) {
        $writegrps = explode(";", $dt['writegrps']);
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
    if ($dt['readgrps'] != "" && !$usergrp && !$ismod) {
        $readgrps = explode(";", $dt['readgrps']);
        foreach ($readgrps as $value) {
            if (isinusergrp($value, $userID)) {
                $usergrp = 1;
                break;
            }
        }
        if (!$usergrp) {
            echo $plugin_language['no_permission'];
            redirect('index.php?site=forum', $plugin_language['no_permission'], 2);
            return;
        }
    }
    $gesamt = mysqli_num_rows(safe_query("SELECT topicID FROM " . PREFIX . "plugins_forum_posts WHERE topicID='$topic'"));
    if ($gesamt == 0) {
        die($plugin_language['topic_not_found'] . " <a href=\"javascript:history.back()\">back</a>");
    }
    
    if (isset($type)) {
        if (!(($type == 'ASC') || ($type == 'DESC'))) {
            $type = "ASC";
        }
    } else {
        $type = "ASC";
    }
    $max = $maxposts;
    $pages = ceil($gesamt / $maxposts);

    $page_link = '';
    if ($pages > 1) {
        $page_link = makepagelink("index.php?site=forum_topic&amp;topic=$topic&amp;type=$type", $page, $pages);
    }
    if ($type == "ASC") {
        $sorter =
            '<a href="index.php?site=forum_topic&amp;topic=' . $topic . '&amp;page=' . $page . '&amp;type=DESC">' .
            $plugin_language['sort'] . ' <i class="bi bi-chevron-down"></i></a>';
    } else {
        $sorter = '<a href="index.php?site=forum_topic&amp;topic=' . $topic . '&amp;page=' . $page . '&amp;type=ASC">' .
            $plugin_language['sort'] . ' <i class="bi bi-chevron-up"></i></a>';
    }

    $start = 0;
    if ($page > 1) {
        $start = $page * $max - $max;
    }

    safe_query("UPDATE " . PREFIX . "plugins_forum_topics SET views=views+1 WHERE topicID='$topic' ");

    // viewed topics
    if($loggedin) {
        if (mysqli_num_rows(safe_query("SELECT userID FROM " . PREFIX . "user WHERE topics LIKE '%|" . $topic . "|%'"))) {
            $gv = mysqli_fetch_array(safe_query("SELECT topics FROM " . PREFIX . "user WHERE userID='$userID'"));
            $array = explode("|", $gv['topics']);
            $new = '|';

            foreach ($array as $split) {
                if ($split != "" && $split != $topic) {
                    $new = $new . $split . '|';
                }
            }

            safe_query("UPDATE " . PREFIX . "user SET topics='" . $new . "' WHERE userID='$userID'");
        }
    }
    // end viewed topics
    $data_array = array();
    $data_array['$title']=$plugin_language[ 'title' ];
    $data_array['$subtitle']='Forum';
    $template = $GLOBALS["_template"]->loadTemplate("forum","title", $data_array, $plugin_path);
    echo $template;

	$topicname = getinput($dt['topic']);

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_boards WHERE boardID='" . $dt['boardID'] . "' ");
    $db = mysqli_fetch_array($ergebnis);
    $boardname = $db['name'];

    $moderators = getmoderators($dt['boardID']);

    $topicactions = '<a href="printview.php?board=' . $dt['boardID'] . '&amp;topic=' . $topic .
        '" target="_blank" class="btn btn-secondary forum"><i class="bi bi-printer"></i></a>&nbsp;';
    if ($loggedin && $writer) {
        $topicactions .=
            '<a href="index.php?site=forum&amp;addtopic=true&amp;action=newtopic&amp;board=' . $dt['boardID'] .
            '" class="btn btn-primary forum hidden">' . $plugin_language['new_topic'] .
            '</a> <a href="index.php?site=forum_topic&amp;topic=' . $topic . '&amp;addreply=true&amp;page=' . $pages .
            '&amp;type=' . $type . '" class="btn btn-primary forum"><i class="bi bi-share"></i> ' . $plugin_language['new_reply'] . '</a>';
    }
    if ($dt['closed']) {
        $closed = $plugin_language['closed_image'];
    } else {
        $closed = '';
    }
    $posttype = 'topic';

    $kathname = getcategoryname($db['category']);
    $data_array = array();
    $data_array['$kathname'] = $kathname;
    $data_array['$category'] = (int)$db['category'];
    $data_array['$board'] = (int)$dt['boardID'];
    $data_array['$boardname'] = $boardname;
    $data_array['$topicname'] = $topicname;

    $data_array['$messageboard']=$plugin_language[ 'messageboard' ];
    $data_array['$you_are_here']=$plugin_language[ 'you_are_here' ];

    $template = $GLOBALS["_template"]->loadTemplate("forum_topics","title", $data_array, $plugin_path);
    echo $template;


    $data_array = array();
    $data_array['$sorter'] = $sorter;
    $data_array['$page_link'] = $page_link;
    $data_array['$topicactions'] = $topicactions;

    $template = $GLOBALS["_template"]->loadTemplate("forum_topics","actions_head", $data_array, $plugin_path);
    echo $template;


    if ($dt['closed']) {
        echo generateAlert($plugin_language['closed_image'], 'alert-danger');
    }

    if ($edit && !$dt['closed']) {
        $id = $_GET['id'];
        $dr = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_forum_posts WHERE postID='" . $id . "'"));
        $topic = $_GET['topic'];
        
        $_sticky = ($dt['sticky'] == '1') ? 'checked="checked"' : '';

        $anz = mysqli_num_rows(
            safe_query(
                "SELECT * FROM " . PREFIX . "plugins_forum_posts WHERE topicID='" . $dt['topicID'] .
                "' AND postID='" . $id . "' AND poster='" . $userID . "' ORDER BY DATE ASC LIMIT 0,1"
            )
        );

        $board = $dt['boardID'];

        if ($anz || isforumadmin($userID) || ismoderator($userID, $board)) {
            if (istopicpost($dt['topicID'], $id)) {
                
                // topicmessage
                $message = getinput($dr['message']);
                $post = $id;


                 //poll
				$ergebnis1=safe_query("SELECT * FROM ".PREFIX."plugins_forum_poll WHERE topicID='".$topic."'");
				if(mysqli_num_rows($ergebnis1)){
					while($dd=mysqli_fetch_array($ergebnis1)){	
						$title=$dd['title'];
						$day=date("d",$dd['enddate']);
						$month=date("m",$dd['enddate']);
						$year=date("Y",$dd['enddate']);
						$value1=$dd['value1'];
						$value2=$dd['value2'];
						$value3=$dd['value3'];
						$value4=$dd['value4'];
						$value5=$dd['value5'];
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
				}


                // notification check
                $notifyqry =
                    safe_query(
                        "SELECT * FROM " . PREFIX . "plugins_forum_notify WHERE topicID='" . $topic . "' AND userID='" .
                        $userID . "'"
                    );
                if (mysqli_num_rows($notifyqry)) {
                    $notify = '<input style="margin-top: 10px;" class="form-check-input" type="checkbox" name="notify" value="1" checked="checked"> ' .
                        $plugin_language['notify_reply'] . '';
                } else {
                    $notify = '<input style="margin-top: 10px;" class="form-check-input" type="checkbox" name="notify" value="1"> ' .
                        $plugin_language['notify_reply'] . '';
                }


                //poll
				if(mysqli_num_rows(safe_query("SELECT * FROM ".PREFIX."plugins_forum_poll WHERE topicID='".$topic."'"))){
					$poll1 = 'checked';
					$poll2 = '';
				}else{
					$poll1 = '';
					$poll2 = 'checked';
				}

                //STICKY
                if (isforumadmin($userID) || ismoderator($userID, $board)) {
                    $chk_sticky =
                        ' <input style="margin-top: 10px;" class="form-check-input" type="checkbox" name="sticky" value="1" ' . $_sticky .
                        '> ' . $plugin_language['make_sticky'];
                } else {
                    $chk_sticky = '';
                }
                
                $data_array = array();

                $data_array['$no_icon'] = $plugin_language['no_icon'];

                $iconlist = $GLOBALS["_template"]->loadTemplate("forum_topic","newtopic_iconlist", $data_array, $plugin_path);               

                if ($dt['icon']) {
                    $iconlist =
                        str_replace(
                            'value="' . $dt['icon'] . '"',
                            'value="' . $dt['icon'] . '" checked="checked"',
                            $iconlist
                        );
                } else {
                    $iconlist = str_replace('value="0"', 'value="0" checked="checked"', $iconlist);
                }
                
                $data_array = array();
                $data_array['$board'] = $board;
                $data_array['$topic'] = $topic;
                $data_array['$iconlist'] = $iconlist;
                $data_array['$topicname'] = $topicname;
                $data_array['$message'] = $message;
                $data_array['$notify'] = $notify;
                $data_array['$chk_sticky'] = $chk_sticky;
                $data_array['$post'] = $post;
                $data_array['$title'] = $title;
                $data_array['$day'] = $day;
                $data_array['$month'] = $month;
                $data_array['$year'] = $year;
                $data_array['$value1'] = $value1;
                $data_array['$value2'] = $value2;
                $data_array['$value3'] = $value3;
                $data_array['$value4'] = $value4;
                $data_array['$value5'] = $value5;
                $data_array['$poll1'] = $poll1;
                $data_array['$poll2'] = $poll2;

                $data_array['$edit_topic']=$plugin_language[ 'edit_topic' ];
                $data_array['$topic_icon']=$plugin_language[ 'topic_icon' ];
                $data_array['$subject']=$plugin_language[ 'subject' ];
                $data_array['$options']=$plugin_language[ 'options' ];
                $data_array['$edit_topic']=$plugin_language[ 'edit_topic' ];

                $data_array['$insert_survey']=$plugin_language[ 'insert_survey' ];
                $data_array['$yes']=$plugin_language[ 'yes' ];
                $data_array['$no']=$plugin_language[ 'no' ];
                $data_array['$clear_results']=$plugin_language[ 'clear_results' ];
                $data_array['$end']=$plugin_language[ 'end' ];
                $data_array['$option']=$plugin_language[ 'option' ];

                $template = $GLOBALS["_template"]->loadTemplate("forum","edittopic", $data_array, $plugin_path);
                echo $template;


            } else {
                // notification check
                $notifyqry =
                    safe_query(
                        "SELECT * FROM " . PREFIX . "plugins_forum_notify WHERE topicID='" . $topic . "' AND userID='" .
                        $userID . "'"
                    );
                if (mysqli_num_rows($notifyqry)) {
                    $notify = '<input style="margin-top: 10px;" class="form-check-input" type="checkbox" name="notify" value="1" checked="checked"> ' .
                        $plugin_language['notify_reply'];
                } else {
                    $notify = '<input style="margin-top: 10px;" class="form-check-input" type="checkbox" name="notify" value="1"> ' .
                        $plugin_language['notify_reply'];
                }

                //STICKY
                if (isforumadmin($userID) || ismoderator($userID, $board)) {
                    $chk_sticky = '<input style="margin-top: 10px;" class="form-check-input" type="checkbox" name="sticky" value="1" ' . $_sticky . '> ' .
                        $plugin_language['make_sticky'];
                } else {
                    $chk_sticky = '';
                }
                
                $dr['message'] = getinput($dr['message']);
                
                $data_array = array();
                $data_array['$boardID'] = $dr['boardID'];
                $data_array['$message'] = $dr['message'];
                $data_array['$topic'] = $topic;
                $data_array['$page'] = $page;
                $data_array['$notify'] = $notify;
                $data_array['$chk_sticky'] = $chk_sticky;
                $data_array['$id'] = $id;

                $data_array['$edit_reply']=$plugin_language[ 'edit_reply' ];
                $data_array['$options']=$plugin_language[ 'options' ];
                
                $template = $GLOBALS["_template"]->loadTemplate("forum","editpost", $data_array, $plugin_path);
                echo $template;
                
            }
        } else {
            echo generateAlert($plugin_language['permission_denied'], 'alert-danger');
        }

        $replys = safe_query(
            "SELECT * FROM " . PREFIX .
            "plugins_forum_posts WHERE topicID='$topic' ORDER BY date DESC LIMIT $start, $max"
        );
    } elseif ($addreply && !$dt['closed']) {
        if ($loggedin && $writer) {
            if (isset($_POST['preview'])) {
                
                $time = getformattime(time());
                $date = $plugin_language['today'];

                $message_preview = getforminput($_POST['message']);
                $postID = 0;

                #$message = cleartext($_POST['message']);
                $message = $_POST['message'];

                $message = $message;
                $username =
                    '<a href="index.php?site=profile&amp;id=' . $userID . '"><strong>' . getnickname($userID) .
                    '</strong></a>';

                $username =
                    '<a href="index.php?site=profile&amp;id=' . $userID . '"><strong>' . getnickname($userID) .
                    '</strong></a>';

                if ($getavatar = getavatar($userID)) {
                    $avatar = '<img class="avatar_small" src="images/avatars/' . $getavatar . '" alt="">';
                } else {
                    $avatar = '';
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

                if (getemail($userID) && !getemailhide($userID)) {
                    $email = '<a href="mailto:' . mail_protect(getemail($userID)) .
                        '">email</a>';
                } else {
                    $email = '';
                }
                if (isset($_POST['notify'])) {
                    $notify = 'checked="checked"';
                } else {
                    $notify = '';
                }
                $pm = '';
                $buddy = '';
                $statuspic = '<span class="badge bg-success">online</span>';
                if (!validate_url(gethomepage($userID))) {
                    $hp = '';
                } else {
                    $hp =
                        '<a href="' . gethomepage($userID) . '" target="_blank" data-toggle="tooltip" data-placement="top" title="' . $plugin_language['homepage'] . '"><i class="bi bi-house"></i> ' . $plugin_language['homepage'] . '</a>';
                }
                $registered = getregistered($userID);
                $posts = getuserforumposts($userID);
                if (isset($_POST['sticky'])) {
                    $post_sticky = $_POST['sticky'];
                } else {
                    $post_sticky = null;
                }
                $_sticky = ($dt['sticky'] == '1' || $post_sticky == '1') ? 'checked="checked"' : '';

                if (isforumadmin($userID)) {
                    $usertype = $plugin_language['admin'];
                    $rang = '<img src="'.$plugin_path.'images/icons/ranks/admin.png" alt="">';
                } elseif (isanymoderator($userID)) {
                    $usertype = $plugin_language['moderator'];
                    $rang = '<img src="'.$plugin_path.'images/icons/ranks/moderator.png" alt="">';
                } else {
                    $ergebnis = safe_query(
                        "SELECT * FROM " . PREFIX .
                        "plugins_forum_ranks WHERE $posts >= postmin AND $posts <= postmax AND postmax >0 AND special='0'"
                    );
                    $ds = mysqli_fetch_array($ergebnis);
                    $usertype = $ds['rank'];
                    $rang = '<img src="'.$plugin_path.'images/icons/ranks/' . $ds['pic'] . '" alt="">';
                }

                $specialrang = "";
                $specialtype = "";
                $getrank = safe_query(
                    "SELECT IF
                        (u.special_rank = 0, 0, CONCAT_WS('__',r.rank, r.pic)) as RANK
                    FROM
                        " . PREFIX . "user u LEFT JOIN " . PREFIX . "plugins_forum_ranks r ON u.special_rank = r.rankID
                    WHERE
                        userID = '" . $userID . "'"
                );
                $rank_data = mysqli_fetch_assoc($getrank);

                if ($rank_data[ 'RANK' ] != '0') {
                    $tmp_rank = explode("__", $rank_data[ 'RANK' ], 2);
                    $specialrang = $tmp_rank[0];
                    if (!empty($tmp_rank[1]) && file_exists("includes/plugins/forum/images/icons/ranks/" . $tmp_rank[1])) {
                        $specialtype =
                            "<img src='includes/plugins/forum/images/icons/ranks/Supporter.png' alt = '" . $specialrang . "' />";
                    }
                }

                if (isforumadmin($userID)) {
                    $chk_sticky = '<input style="margin-top: 10px;" class="form-check-input" type="checkbox" name="sticky" value="1" ' . $_sticky . '> ' .
                        $plugin_language['make_sticky'];
                } elseif (isanymoderator($userID)) {
                    $chk_sticky = '<input style="margin-top: 10px;" class="form-check-input" type="checkbox" name="sticky" value="1" ' . $_sticky . '> ' .
                        $plugin_language['make_sticky'];
                } else {
                    $chk_sticky = '';
                }
                $quote = "";
                $actions = "";
                echo '<div class="card title" style="text-center;margin-top: 10px;margin-bottom: 10px">
                    <div class="card-body" style="text-align:center"><h4>' . $plugin_language['preview'] . '</h4>
                    </div></div>';
                
                #========================================================
                if ($getsignatur = getsignatur($userID)) {
                    $signatur = $getsignatur;

                    $translate = new multiLanguage(detectCurrentLanguage());
                    $translate->detectLanguages($signatur);
                    $signatur = $translate->getTextByLanguage($signatur);
                    
                } else {
                    $signatur = '';
                }
                #============================================================

                $danke = '';
                $forum_thank = '';

                $data_array = array();
                $data_array['$statuspic'] = $statuspic;
                $data_array['$username'] = $username;
                $data_array['$usertype'] = $usertype;
                $data_array['$member'] = $member;
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

                $message = $message_preview;
            } else {
                if ($quoteID) {
                    $ergebnis =
                        safe_query("SELECT poster,message FROM " . PREFIX . "plugins_forum_posts WHERE postID='$quoteID'");
                    $ds = mysqli_fetch_array($ergebnis);
                    $message = '<blockquote>' . getnickname($ds['poster']) . ': ' . getinput($ds['message']) . '</blockquote><br>';
                }
            }
            if (isset($_POST['sticky'])) {
                $post_sticky = $_POST['sticky'];
            } else {
                $post_sticky = null;
            }
            $_sticky = ($dt['sticky'] == '1' || $post_sticky == '1') ? 'checked="checked"' : '';
            if (isforumadmin($userID) || ismoderator($userID, $dt['boardID'])) {
                $chk_sticky = '<input style="margin-top: 10px;" class="form-check-input" type="checkbox" name="sticky" value="1" ' . $_sticky . '> ' .
                    $plugin_language['make_sticky'];
            } else {
                $chk_sticky = '';
            }

            if (isset($_POST['notify'])) {
                $post_notify = $_POST['notify'];
            } else {
                $post_notify = null;
            }
            $mysql_notify =
                mysqli_num_rows(
                    safe_query(
                        "SELECT notifyID FROM " . PREFIX . "plugins_forum_notify WHERE userID='" . $userID .
                        "' AND topicID='" . $topic . "'"
                    )
                );
            $notify = ($mysql_notify || $post_notify == '1') ? 'checked="checked"' : '';

            $board = $dt['boardID'];

            $data_array = array();
            $data_array['$message'] = $message;
            $data_array['$notify'] = $notify;
            $data_array['$chk_sticky'] = $chk_sticky;
            $data_array['$userID'] = $userID;
            $data_array['$board'] = $board;
            $data_array['$topic'] = $topic;
            $data_array['$page'] = $page;

            $data_array['$new_reply']=$plugin_language[ 'new_reply' ];
            $data_array['$options']=$plugin_language[ 'options' ];
            $data_array['$notify_reply']=$plugin_language[ 'notify_reply' ];
            $data_array['$preview_post']=$plugin_language[ 'preview_post' ];
            $data_array['$post_new_reply']=$plugin_language[ 'post_new_reply' ];


            $template = $GLOBALS["_template"]->loadTemplate("forum","newreply", $data_array, $plugin_path);
            echo $template;


        } elseif ($loggedin) {
            #echo generateAlert($plugin_language['no_access_write'], 'alert-danger');
        } else {
            #echo generateAlert($plugin_language['not_logged_msg'], 'alert-danger');
        }
        
        $replys =
            safe_query(
                "SELECT * FROM " . PREFIX . "plugins_forum_posts WHERE topicID='$topic' ORDER BY date DESC LIMIT 0, " .
                $max . ""
            );
    } else {
        $replys =
            safe_query(
                "SELECT * FROM " . PREFIX . "plugins_forum_posts WHERE topicID='$topic' ORDER BY date $type LIMIT " .
                $start . ", " . $max . ""
            );
    }

    #======topicname===========
    $topicname = getinput($dt['topic']);

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_boards WHERE boardID='" . $dt['boardID'] . "' ");
    $db = mysqli_fetch_array($ergebnis);
    
    $data_array = array();
    
    $data_array['$topicname'] = $topicname;

    #==================================

    $template = $GLOBALS["_template"]->loadTemplate("forum_topic","head", $data_array, $plugin_path);
    echo $template;

        $anzahlpoll = '';
		@$anzahlpoll++; #Fehler mit PHP8.3
					
		$insertpoll = '';
					
		// FORENUMFRAGEN
		if($anzahlpoll==1){
			@$ergebnis1=safe_query("SELECT * FROM ".PREFIX."plugins_forum_poll WHERE poll='1' AND topicID='".intval($_GET['topic'])."' AND enddate >= '".time()."' LIMIT 0,1");
			@$ergebnis2=safe_query("SELECT * FROM ".PREFIX."plugins_forum_poll WHERE poll='1' AND topicID='".intval($_GET['topic'])."' LIMIT 0,1");
				$forum_topic_poll_vote = '';
				$forum_topic_poll = '';

			if(mysqli_num_rows($ergebnis1) AND $loggedin){
				$fd=safe_query("SELECT * FROM ".PREFIX."plugins_forum_votes WHERE topicID='".intval($_GET['topic'])."' AND userID='".$userID."'");

				if(mysqli_num_rows($fd)){
					while($dd=mysqli_fetch_array($ergebnis1)){
								
						$erga=mysqli_fetch_array(safe_query("SELECT count(*) FROM ".PREFIX."plugins_forum_votes WHERE value1>0 AND topicID='".intval($_GET['topic'])."'"));	
						$ergb=mysqli_fetch_array(safe_query("SELECT count(*) FROM ".PREFIX."plugins_forum_votes WHERE value2>0 AND topicID='".intval($_GET['topic'])."'"));	
						$ergc=mysqli_fetch_array(safe_query("SELECT count(*) FROM ".PREFIX."plugins_forum_votes WHERE value3>0 AND topicID='".intval($_GET['topic'])."'"));	
						$ergd=mysqli_fetch_array(safe_query("SELECT count(*) FROM ".PREFIX."plugins_forum_votes WHERE value4>0 AND topicID='".intval($_GET['topic'])."'"));	
						$erge=mysqli_fetch_array(safe_query("SELECT count(*) FROM ".PREFIX."plugins_forum_votes WHERE value5>0 AND topicID='".intval($_GET['topic'])."'"));
						$ergf=mysqli_fetch_array(safe_query("SELECT count(*) FROM ".PREFIX."plugins_forum_votes WHERE topicID='".intval($_GET['topic'])."'"));	
											
                        $gesamt=$ergf['count(*)'];
						$title=$dd['title'];
						
                        $data_array = array();
                        $data_array['$title'] = $title;
						$data_array['$poll_head'] = $plugin_language['poll_head'];
						$data_array['$poll_titel'] = $plugin_language['poll_titel'];
						$data_array['$poll_select'] = $plugin_language['poll_select'];

						$template = $GLOBALS["_template"]->loadTemplate("forum_topic","poll_head", $data_array, $plugin_path);
						echo $template;

						if(!empty($dd['value1'])){
							$bg = "class=\"forum_poll_bg1\"";
							$value = $dd['value1'];
							$points=$erga['count(*)'];
							$bar=$points/$gesamt*100;
							$bar=round($bar,2);
							$barimg = 'bar1.png';

							$data_array = array();
                            $data_array['$value'] = $value;
                            $data_array['$points'] = $points;
							$data_array['$bar'] = $bar;
							$data_array['$barimg'] = $barimg;
												
							$template = $GLOBALS["_template"]->loadTemplate("forum_topic","vote", $data_array, $plugin_path);
							echo $template;

						}else{
							$value1 = '';
						}
											
						if(!empty($dd['value2'])){
							$bg = "class=\"forum_poll_bg2\"";
							$value = $dd['value2'];
							$points=$ergb['count(*)'];
							$bar=$points/$gesamt*100;
							$bar=round($bar,2);
							$barimg = 'bar2.png';
	
    						$data_array = array();
                            $data_array['$value'] = $value;
                            $data_array['$points'] = $points;
							$data_array['$bar'] = $bar;
							$data_array['$barimg'] = $barimg;
											
							$template = $GLOBALS["_template"]->loadTemplate("forum_topic","vote", $data_array, $plugin_path);
							echo $template;
						}else{
							$value2 = '';
						}
											
						if(!empty($dd['value3'])){
							$bg = "class=\"forum_poll_bg1\"";
							$value = $dd['value3'];
							$points=$ergc['count(*)'];
							$bar=$points/$gesamt*100;
							$bar=round($bar,2);
							$barimg = 'bar3.png';

							$data_array = array();
                            $data_array['$value'] = $value;
                            $data_array['$points'] = $points;
							$data_array['$bar'] = $bar;
							$data_array['$barimg'] = $barimg;
												
							$template = $GLOBALS["_template"]->loadTemplate("forum_topic","vote", $data_array, $plugin_path);
							echo $template;
						}else{
							$value3 = '';
						}
											
						if(!empty($dd['value4'])){
     						$bg = "class=\"forum_poll_bg2\"";
							$value = $dd['value4'];
							$points=$ergd['count(*)'];
							$bar=$points/$gesamt*100;
							$bar=round($bar,2);
							$barimg = 'bar4.png';
	
    						$data_array = array();
                            $data_array['$value'] = $value;
                            $data_array['$points'] = $points;
							$data_array['$bar'] = $bar;
							$data_array['$barimg'] = $barimg;
												
							$template = $GLOBALS["_template"]->loadTemplate("forum_topic","vote", $data_array, $plugin_path);
							echo $template;
						}else{
							$value4 = '';
						}
											
						if(!empty($dd['value5'])){
							$bg = "class=\"forum_poll_bg1\"";
							$value = $dd['value5'];
							$points=$erge['count(*)'];
							$bar=$points/$gesamt*100;
							$bar=round($bar,2);
							$barimg = 'bar5.png';

							$data_array = array();
                            $data_array['$value'] = $value;
                            $data_array['$points'] = $points;
							$data_array['$bar'] = $bar;
							$data_array['$barimg'] = $barimg;
							$data_array['$poll_head'] = $plugin_language['poll_head'];
							$data_array['$poll_titel'] = $plugin_language['poll_titel'];
							$data_array['$poll_select'] = $plugin_language['poll_select'];
												
							$template = $GLOBALS["_template"]->loadTemplate("forum_topic","vote", $data_array, $plugin_path);
							echo $template;
						}else{
							$value5 = '';
						}
						//$template = $GLOBALS["_template"]->loadTemplate("forum_topic","topic_poll", $data_array, $plugin_path);
						//echo $template;
					}				
				}else{
					while($dd=mysqli_fetch_array($ergebnis1)){
						$title=$dd['title'];
						if(!empty($dd['value1'])){$value1='<table cellpadding="1" cellspacing="1" border="0" class="forum_poll_bg1"><tr><td align="center" width="5%"><input type="radio" style="border:0;" name="value" value="1" onclick="ForumVoteSave(\''.$plugin_language['poll_really'].'\',\''.$_GET['topic'].'\',\'1\',\''.$loggedin.'\',\''.$userID.'\');" /></td><td>'.$dd['value1'].'</td></tr></table>';
                        }else{
                            $value1 = '';
                        }
						if(!empty($dd['value2'])){$value2='<table cellpadding="1" cellspacing="1" border="0" class="forum_poll_bg2"><tr><td align="center" width="5%"><input type="radio" style="border:0;" name="value" value="2" onclick="ForumVoteSave(\''.$plugin_language['poll_really'].'\',\''.$_GET['topic'].'\',\'2\',\''.$loggedin.'\',\''.$userID.'\');" /></td><td>'.$dd['value2'].'</td></tr></table>';
                        }else{
                            $value2 = '';
                        }
						if(!empty($dd['value3'])){$value3='<table cellpadding="1" cellspacing="1" border="0" class="forum_poll_bg1"><tr><td align="center" width="5%"><input type="radio" style="border:0;" name="value" value="3" onclick="ForumVoteSave(\''.$plugin_language['poll_really'].'\',\''.$_GET['topic'].'\',\'3\',\''.$loggedin.'\',\''.$userID.'\');" /></td><td>'.$dd['value3'].'</td></tr></table>';
                        }else{
                            $value3 = '';
                        }
                        if(!empty($dd['value4'])){$value4='<table cellpadding="1" cellspacing="1" border="0" class="forum_poll_bg2"><tr><td align="center" width="5%"><input type="radio" style="border:0;" name="value" value="4" onclick="ForumVoteSave(\''.$plugin_language['poll_really'].'\',\''.$_GET['topic'].'\',\'4\',\''.$loggedin.'\',\''.$userID.'\');" /></td><td>'.$dd['value4'].'</td></tr></table>';
                        }else{
                            $value4 = '';
                        }
						if(!empty($dd['value5'])){$value5='<table cellpadding="1" cellspacing="1" border="0" class="forum_poll_bg1"><tr><td align="center" width="5%"><input type="radio" style="border:0;" name="value" value="5" onclick="ForumVoteSave(\''.$plugin_language['poll_really'].'\',\''.$_GET['topic'].'\',\'5\',\''.$loggedin.'\',\''.$userID.'\');" /></td><td>'.$dd['value5'].'</td></tr></table>';
                        }else{
                            $value5 = '';
                        }					
											
                        $data_array = array();
                        $data_array['$title'] = $title;
                        $data_array['$value1'] = $value1;
                        $data_array['$value2'] = $value2;
                        $data_array['$value3'] = $value3;
                        $data_array['$value4'] = $value4;
                        $data_array['$value5'] = $value5;
                        $data_array['$poll_head'] = $plugin_language['poll_head'];
                        $data_array['$poll_titel'] = $plugin_language['poll_titel'];
                        $data_array['$poll_select'] = $plugin_language['poll_select'];

						$template = $GLOBALS["_template"]->loadTemplate("forum_topic","poll_vote", $data_array, $plugin_path);
						echo $template;
					}
				}
			}else{
				while($dd=mysqli_fetch_array($ergebnis2)){
					$erga=mysqli_fetch_array(safe_query("SELECT count(*) FROM ".PREFIX."plugins_forum_votes WHERE value1>0 AND topicID='".intval($_GET['topic'])."'"));	
					$ergb=mysqli_fetch_array(safe_query("SELECT count(*) FROM ".PREFIX."plugins_forum_votes WHERE value2>0 AND topicID='".intval($_GET['topic'])."'"));
					$ergc=mysqli_fetch_array(safe_query("SELECT count(*) FROM ".PREFIX."plugins_forum_votes WHERE value3>0 AND topicID='".intval($_GET['topic'])."'"));
					$ergd=mysqli_fetch_array(safe_query("SELECT count(*) FROM ".PREFIX."plugins_forum_votes WHERE value4>0 AND topicID='".intval($_GET['topic'])."'"));
					$erge=mysqli_fetch_array(safe_query("SELECT count(*) FROM ".PREFIX."plugins_forum_votes WHERE value5>0 AND topicID='".intval($_GET['topic'])."'"));
					$ergf=mysqli_fetch_array(safe_query("SELECT count(*) FROM ".PREFIX."plugins_forum_votes WHERE topicID='".intval($_GET['topic'])."'"));	

					$gesamt=$ergf['count(*)'];
					$title=$dd['title'];
									
					$data_array = array();
                    $data_array['$title'] = $title;
					$data_array['$poll_head'] = $plugin_language['poll_head'];
					$data_array['$poll_titel'] = $plugin_language['poll_titel'];

					$template = $GLOBALS["_template"]->loadTemplate("forum_topic","poll_head", $data_array, $plugin_path);
					echo $template;

					if(!empty($dd['value1'])){
						$value = $dd['value1'];
						$points=$erga['count(*)'];
                        if($points) {
                            $bar=$points/$gesamt*100;
                        } else {
                            $bar = '0';
                        }
						$bar=round($bar,2);

						$data_array = array();
                        $data_array['$value'] = $value;
                        $data_array['$points'] = $points;
						$data_array['$bar'] = $bar;
						$data_array['$poll_head'] = $plugin_language['poll_head'];
						$data_array['$poll_titel'] = $plugin_language['poll_titel'];
						$data_array['$poll_select'] = $plugin_language['poll_select'];

                        $template = $GLOBALS["_template"]->loadTemplate("forum_topic","vote", $data_array, $plugin_path);
        				echo $template;
					}else{
						$value1 = '';
					}
											
					if(!empty($dd['value2'])){
						$bg = "class=\"forum_pol2_bg1\"";
						$value = $dd['value2'];
						$points=$ergb['count(*)'];
                        if($points) {
                            $bar=$points/$gesamt*100;
                        } else {
                            $bar = '0';
                        }
						$bar=round($bar,2);
												
						$data_array = array();
                        $data_array['$value'] = $value;
                        $data_array['$points'] = $points;
						$data_array['$bar'] = $bar;
						$data_array['$poll_head'] = $plugin_language['poll_head'];
						$data_array['$poll_titel'] = $plugin_language['poll_titel'];
						$data_array['$poll_select'] = $plugin_language['poll_select'];

						$template = $GLOBALS["_template"]->loadTemplate("forum_topic","vote", $data_array, $plugin_path);
						echo $template;
					}else{
						$value2 = '';
					}
											
					if(!empty($dd['value3'])){
						$bg = "class=\"forum_poll_bg1\"";
						$value = $dd['value3'];
						$points=$ergc['count(*)'];
                        if($points) {
                            $bar=$points/$gesamt*100;
                        } else {
                            $bar = '0';
                        }
						$bar=round($bar,2);
						$barimg = 'bar3.png';
						
						$data_array = array();
                        $data_array['$value'] = $value;
                        $data_array['$points'] = $points;
						$data_array['$bar'] = $bar;
						$data_array['$barimg'] = $barimg;
						$data_array['$poll_head'] = $plugin_language['poll_head'];
						$data_array['$poll_titel'] = $plugin_language['poll_titel'];
						$data_array['$poll_select'] = $plugin_language['poll_select'];

						$template = $GLOBALS["_template"]->loadTemplate("forum_topic","vote", $data_array, $plugin_path);
						echo $template;
					}else{
						$value3 = '';
					}
											
					if(!empty($dd['value4'])){
						$bg = "class=\"forum_pol2_bg1\"";
						$value = $dd['value4'];
						$points=$ergd['count(*)'];
                        if($points) {
                            $bar=$points/$gesamt*100;
                        } else {
                            $bar = '0';
                        }
						$bar=round($bar,2);
						
						$data_array = array();
                        $data_array['$value'] = $value;
                        $data_array['$points'] = $points;
						$data_array['$bar'] = $bar;
						$data_array['$barimg'] = $barimg;
						$data_array['$poll_head'] = $plugin_language['poll_head'];
						$data_array['$poll_titel'] = $plugin_language['poll_titel'];
						$data_array['$poll_select'] = $plugin_language['poll_select'];

						$template = $GLOBALS["_template"]->loadTemplate("forum_topic","vote", $data_array, $plugin_path);
						echo $template;
					}else{
						$value4 = '';
					}
											
					if(!empty($dd['value5'])){
						$bg = "class=\"forum_poll_bg1\"";
						$value = $dd['value5'];
						$points=$erge['count(*)'];
                        if($points) {
                            $bar=$points/$gesamt*100;
                        } else {
                            $bar = '0';
                        }
						$bar=round($bar,2);
						
						$data_array = array();
                        $data_array['$value'] = $value;
                        $data_array['$points'] = $points;
						$data_array['$bar'] = $bar;
						$data_array['$barimg'] = $barimg;
						$data_array['$poll_head'] = $plugin_language['poll_head'];
						$data_array['$poll_titel'] = $plugin_language['poll_titel'];
						$data_array['$poll_select'] = $plugin_language['poll_select'];

						$template = $GLOBALS["_template"]->loadTemplate("forum_topic","vote", $data_array, $plugin_path);
						echo $template;
					}else{
						$value5 = '';
					}
					//$template = $GLOBALS["_template"]->loadTemplate("forum_topic","topic_poll", $data_array, $plugin_path);
					//echo $template;
                    $template = $GLOBALS["_template"]->loadTemplate("forum_topic","poll_foot", $data_array, $plugin_path);
                    echo $template;
				}	
			}
			$insertpoll = $forum_topic_poll.''.$forum_topic_poll_vote;
		}else{
			$forum_topic_poll = '';
			$forum_topic_poll_vote = '';
			$insertpoll = $forum_topic_poll.''.$forum_topic_poll_vote;
		}

    $i = 1;
    while ($dr = mysqli_fetch_array($replys)) {

        // -- COMMENTS INFORMATION -- //
    include_once("forum_functions.php");
        
        $date = getformatdate($dr['date']);
        $time = getformattime($dr['date']);

        $today = getformatdate(time());
        $yesterday = getformatdate(time() - 3600 * 24);

        if ($date == $today) {
            $date = $plugin_language['today'];
        } elseif ($date == $yesterday && $date < $today) {
            $date = $plugin_language['yesterday'];
        }

        $message = $dr['message'];
        $postID = $dr['postID'];
        if(deleteduser($dr['poster']) == '0'){
            $username = '<a href="index.php?site=profile&amp;id=' . $dr['poster'] . '"><b>' .
                stripslashes(getnickname($dr['poster'])) . '</b></a>';
        } else {
            $username = '<b>'.stripslashes(getnickname($dr['poster'])).'</b>';

        }
        
        $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
        if (@$dx[ 'modulname' ] != 'squads') {    
            $member = '';                    
        } else {
            if (isclanmember($dr['poster'])) {
                $member = ' <i class="bi bi-person" style="color: #5cb85c"></i>';
            } else {
                $member = '';
            }
        }

        if ($getavatar = getavatar($dr['poster'])) {
            $avatar = '<img class="avatar img-fluid" src="images/avatars/' . $getavatar . '" alt="'.$dr['poster'].'">';
        } else {
            $avatar = '';
        }

        
#========================================================
        if ($getsignatur = getsignatur($dr['poster'])) {
            $signatur = $getsignatur;

            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($signatur);
            $signatur = $translate->getTextByLanguage($signatur);
            
        } else {
            $signatur = '';
        }
#============================================================

#================== Bedanken ==========================
        if($loggedin ){ 
            if($userID !== $dr['poster']){
                $string=$dr['thank'];
                $array=explode("#", $string);
                if(in_array($userID,$array)) {
                    $danke= 'Dislike: <button id="klick" type="button" class="btn btn-like-danger" id="ForumThankIt'.$dr['postID'].'" onclick="ForumThankSave(\''.$dr['postID'].'\',\'0\',\''.$loggedin.'\',\''.$userID.'\');"> <i class="bi bi-hand-thumbs-down text-danger" style="font-size:24px" data-toggle="tooltip" data-placement="top" title="' . $plugin_language['disfeedback'] . '"></i></button>';
                } else {
	                $danke='Like: <button id="klick" type="button" class="btn btn-like-success" id="ForumThankIt'.$dr['postID'].'" onclick="ForumThankSave(\''.$dr['postID'].'\',\'1\',\''.$loggedin.'\',\''.$userID.'\');"> <i class="bi bi-hand-thumbs-up text-success avatar" style="font-size:24px" data-toggle="tooltip" data-placement="top" title="' . $plugin_language['feedback'] . '"></i></button>';
                }
            } else {
                $danke='';
            }
        } else {
            $danke='';
        }

		unset($thankhead, $ergebnisz,$thank,$thanks,$getuser, $thankfoot);
        $thankhead = '';
		$getuser = '';
		$thankfoot = '';

		if($dr['thank'] != '') {
            $thankhead .= '';
		    $thankhead .= $plugin_language['thanksto'];
                                                    
		    $ergebnisz=safe_query("SELECT thank FROM ".PREFIX."plugins_forum_posts WHERE postID='".$dr['postID']."'");
		    $dzs=mysqli_fetch_array($ergebnisz);
		    if(!empty($dzs['thank'])) $thank=explode("#", $dzs['thank']);
                $calc = count($thank);
			    if(is_array($thank)) {
			        $n=1;
			        foreach($thank as $thanks) {
			            if($thanks!="") {				
			                if($n>1) $getuser.=', <a href="index.php?site=profile&amp;id='.$thanks.'" target="_self">'.getnickname($thanks).'</a>';
				                else $getuser ='<a href="index.php?site=profile&amp;id='.$thanks.'" target="_self">'.getnickname($thanks).'</a>';
				                $n++;
			            }
			        }
			    }
			    $thankfoot='';	
		}
		$forum_thank = $thankhead.$getuser.$thankfoot;

#================== Bedanken Ende =====================

        if (getemail($dr['poster']) && !getemailhide($dr['poster'])) {
            $email =
                '<a href="mailto:' . mail_protect(getemail($dr['poster'])) .'">email</a>';
        } else {
            $email = '';
        }

        $pm = '';
        $buddy = '';
        if ($loggedin && $dr['poster'] != $userID && deleteduser($dr['poster']) == '0') {
            $pm = '<a class="badge bg-success" href="index.php?site=messenger&amp;action=touser&amp;touser=' . $dr['poster'] .'" data-toggle="tooltip" data-placement="top" title="' . $plugin_language['message'] . '">' . $plugin_language['message'] . '</a>';
        }

        if (isonline($dr['poster']) == "offline") {
            $statuspic = '<span class="badge bg-danger">offline</span>';
        } else {
            $statuspic = '<span class="badge bg-success">online</span>';
        }

        if (!$dt['closed']) {
            $quote = '';
            if($loggedin) {
              $quote =
                '<a class="btn btn-info btn-sm" href="index.php?site=forum_topic&amp;addreply=true&amp;board=' . $dt['boardID'] . '&amp;topic=' .
                $topic . '&amp;quoteID=' . $dr['postID'] . '&amp;page=' . $page . '&amp;type=' . $type .
                '" data-toggle="tooltip" data-placement="top" title="' . $plugin_language['quote'] . '">' . $plugin_language['quote'] . '</a>
              ';
            }
        } else {
            $quote = '';
        }

        $registered = getregistered($dr['poster']);

        $posts = getuserforumposts($dr['poster']);

        if (isforumadmin($dr['poster'])) {
            $usertype = $plugin_language['admin'];
            $rang = '<img src="'.$plugin_path.'images/icons/ranks/admin.png" alt="">';
        } elseif (isanymoderator($dr['poster'])) {
            $usertype = $plugin_language['moderator'];
            $rang = '<img src="'.$plugin_path.'images/icons/ranks/moderator.png" alt="">';
        } else {
            $ergebnis = safe_query(
                "SELECT * FROM " . PREFIX .
                "plugins_forum_ranks WHERE $posts >= postmin AND $posts <= postmax AND postmax >0 AND special='0'"
            );
            $ds = mysqli_fetch_array($ergebnis);
            $usertype = $ds['rank'];
            $rang = '<img src="'.$plugin_path.'images/icons/ranks/' . $ds['pic'] . '" alt="">';
        }

        $special_rank = '';
        $specialtype = "";
        $getrank = safe_query(
            "SELECT IF
                (u.special_rank = 0, 0, CONCAT_WS('__', r.rank, r.pic)) as RANK
            FROM
                " . PREFIX . "user u LEFT JOIN " . PREFIX . "plugins_forum_ranks r ON u.special_rank = r.rankID
            WHERE
                userID='" . $dr['poster'] . "'"
        );
        $rank_data = mysqli_fetch_assoc($getrank);

        if (@$rank_data[ 'RANK' ] != '0') {
            $special_rank  = '<br/>';
            $tmp_rank = @explode("__", $rank_data[ 'RANK' ], 2);
            $special_rank .= '<p class="mb-0 font-weight-bold">';
            $special_rank .= $tmp_rank[0];
            $special_rank .= '</p>';

            if (!empty($tmp_rank[1]) && file_exists("includes/plugins/forum/images/icons/ranks/" . $tmp_rank[1]) && deleteduser($dr['poster']) == '0') {
                $special_rank .= "<img src='/includes/plugins/forum/images/icons/ranks/" . $tmp_rank[1] . "' alt = 'rank' />";
            }
        }

        $spam_buttons = "";
        if (!empty($spamapikey)) {
            if (ispageadmin($userID) || ismoderator($userID, $dt['boardID'])) {
                $spam_buttons =
                    '<input type="button" value="Spam" onclick="eventfetch(\'ajax_spamfilter.php?postID=' . $postID .
                    '&type=spam\',\'\',\'return\')">
                    <input type="button" value="Ham" onclick="eventfetch(\'ajax_spamfilter.php?postID=' . $postID .
                    '&type=ham\',\'\',\'return\')">';
            }
        }

        $actions = '';
        if (($userID == $dr['poster'] || isforumadmin($userID) || ismoderator($userID, $dt['boardID']))
            && !$dt['closed']
        ) {
            $actions = ' <a class="btn btn-warning text-dark btn-sm" href="index.php?site=forum_topic&amp;topic=' . $topic . '&amp;edit=true&amp;id=' .
                $dr['postID'] . '" data-toggle="tooltip" data-placement="top" title="' . $plugin_language['edit'] . '">' . $plugin_language['edit'] . '</a> ';
        }
        if (isforumadmin($userID) || ismoderator($userID, $dt['boardID'])) {
            $actions .= '<input style="margin-top: 10px;" class="form-check-input" type="checkbox" name="postID[]" value="' . $dr['postID'] . '">';
        }         

        $data_array = array();
        $data_array['$statuspic'] = $statuspic;
        $data_array['$username'] = $username;
        $data_array['$member'] = $member;
        $data_array['$usertype'] = $usertype;
        $data_array['$quote'] = $quote;
        $data_array['$date'] = $date;
        $data_array['$time'] = $time;
        $data_array['$pm'] = $pm;
        $data_array['$buddy'] = $buddy;
        $data_array['$email'] = $email;
        $data_array['$actions'] = $actions;
        $data_array['$avatar'] = $avatar;
        $data_array['$rang'] = $rang;
        $data_array['$posts'] = $posts;
        $data_array['$registered'] = $registered;
        $data_array['$message'] = $message;
        $data_array['$signatur'] = $signatur;
        $data_array['$specialrang'] = $special_rank;
        $data_array['$specialtype'] = $specialtype;
        $data_array['$danke'] = $danke;
        $data_array['$forum_thank'] = $forum_thank;
        $data_array['$forum_postID'] = $dr['postID'];

        $data_array['$post']=$plugin_language[ 'posts' ];
        $data_array['$registere']=$plugin_language[ 'registered' ];
        $data_array['$disfeedback']=$plugin_language[ 'disfeedback' ];
        $data_array['$feedback']=$plugin_language[ 'feedback' ];

        $template = $GLOBALS["_template"]->loadTemplate("forum_topic","content", $data_array, $plugin_path);
        echo $template;

        unset($actions);
        $i++;
    }

    $adminactions = "";
    if (isforumadmin($userID) || ismoderator($userID, $dt['boardID'])) {
        if ($dt['closed']) {
            $close = '<option value="opentopic">- ' . $plugin_language['reopen_topic'] . '</option>';
        } else {
            $close = '<option value="closetopic">- ' . $plugin_language['close_topic'] . '</option>';
        }

        $adminactions = '<div class="card">
            <div class="card-body">
                    <div class="row">
        <div class="col-md-7 text-start">
        <input class="input" type="checkbox" name="ALL" value="ALL" onclick="SelectAll(this.form);" />
            ' . $plugin_language['select_all'] . '</div>
            <div class="col-md-5">
        <div class="input-group text-end">
        <select name="admaction" class="form-select">
            <option value="0">' . $plugin_language['admin_actions'] . ':</option>
            <option value="delposts">- ' . $plugin_language['delete_posts'] . '</option>
            <option value="stickytopic">- ' . $plugin_language['make_topic_sticky'] . '</option>
            <option value="unstickytopic">- ' . $plugin_language['make_topic_unsticky'] . '</option>
            <option value="movetopic">- ' . $plugin_language['move_topic'] . '</option>
            ' . $close . '
            <option value="deletetopic">- ' . $plugin_language['delete_topic'] . '</option>
        </select>
        <span class="input-group-btn">
        <input type="hidden" name="topicID" value="' . $topic . '">
        <input type="hidden" name="board" value="' . $dt['boardID'] . '">
        <input type="submit" name="submit" value="' . $plugin_language['go'] . '" class="btn btn-danger forum">
        </span></div></div>
        </div></div></div>';

    }

    $template = $GLOBALS["_template"]->loadTemplate("forum_topic","foot", $data_array, $plugin_path);
    echo $template;

    $data_array = array();
    $data_array['$sorter'] = $sorter;
    $data_array['$page_link'] = $page_link;
    $data_array['$topicactions'] = $topicactions;

    $template = $GLOBALS["_template"]->loadTemplate("forum_topics","actions_foot", $data_array, $plugin_path);
    echo $template;

    echo '<div class="text-end">' . $adminactions . '</div></form>';

    if ($dt['closed']) {
        echo $plugin_language['closed_image'];
    } else {
        if (!$loggedin && !$edit) {
            echo $plugin_language['not_logged_msg'];
        }
    }

