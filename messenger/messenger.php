<script type="text/javascript">
    
    function SelectAll() {
    "use strict";
    var x, y;
    for (x = 0; x < document.form.elements.length; x++) {
        y = document.form.elements[x];
        if (y.name !== "ALL") {
            y.checked = document.form.ALL.checked;
        }
    }
}
</script><?php
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
$_lang = $pm->plugin_language("messenger", $plugin_path);

if (isset($_POST['delete'])) {
    
    $_language->readModule('messenger');

    if (!isset($userID)) {
        die($_lang['not_logged']);
    }
    if (isset($_POST['messageID'])) {
        $messageID = $_POST['messageID'];
    } else {
        $messageID = array();
    }

    foreach ($messageID as $id) {
        safe_query("DELETE FROM " . PREFIX . "plugins_messenger WHERE messageID='" . $id . "' AND userID='" . $userID . "'");
    }
    header("Location: index.php?site=messenger&action=outgoing");
} elseif (isset($_POST['quickaction'])) {
    
    if (!isset($userID)) {
        die();
    }

    $quickactiontype = $_POST['quickactiontype'];
    if (isset($_POST['messageID'])) {
        $messageID = $_POST['messageID'];
        if ($quickactiontype == "viewed") {
            foreach ($messageID as $id) {
                safe_query("UPDATE " . PREFIX . "plugins_messenger SET viewed='1' WHERE messageID='$id' AND userID='$userID'");
            }
        } elseif ($quickactiontype == "notviewed") {
            foreach ($messageID as $id) {
                safe_query("UPDATE " . PREFIX . "plugins_messenger SET viewed='0' WHERE messageID='$id' AND userID='$userID'");
            }
        } elseif ($quickactiontype == "delete") {
            foreach ($messageID as $id) {
                safe_query("DELETE FROM " . PREFIX . "plugins_messenger WHERE messageID='$id' AND touser='$userID'");
            }
        }
        header("Location: index.php?site=messenger");
    } else {
        header("Location: index.php?site=messenger");
    }
} elseif (isset($_POST['send'])) {
    
    $_language->readModule('messenger');

    $touser = $_POST['touser'];
    if ($touser[0] == "" && $_POST['touser_field'] != "*") {
        $tmp = explode(",", $_POST['touser_field']);
        for ($i = 0; $i < 5; $i++) {
            if (isset($tmp[$i])) {
                $tmp[$i] = trim($tmp[$i]);
                if (!empty($tmp[$i])) {
                    $touser[$i] = getuserid($tmp[$i]);
                } else {
                    break;
                }
            } else {
                break;
            }
        }
    }
    if (isset($_POST['eachmember'])) {
        unset($touser);
        $ergebnis = safe_query("SELECT userID FROM " . PREFIX . "user");
        while ($ds = mysqli_fetch_array($ergebnis)) {
            if (isclanmember($ds['userID'])) {
                $touser[] = $ds['userID'];
            }
        }
    }

    if (isset($_POST['eachuser'])) {
        unset($touser);
        $ergebnis = safe_query("SELECT userID FROM " . PREFIX . "user");
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $touser[] = $ds['userID'];
        }
    }

    $date = time();
    if ($_POST['title'] == "") {
        $title = $_lang['no_subject'];
    } else {
        $title = $_POST['title'];
    }
    $message = $_POST['message'];
    if ($touser[0] != "" && isset($userID)) {
        foreach ($touser as $id) {
            sendmessage($id, $title, $message, $userID);
        }
        if (isset($_SESSION['message_subject'])) {
            unset($_SESSION['message_subject'], $_SESSION['message_body'], $_SESSION['message_error']);
        }
        header("Location: index.php?site=messenger&action=outgoing");
        exit();
    } else {
        $_SESSION['message_subject'] = $title;
        $_SESSION['message_body'] = $message;
        $_SESSION['message_error'] = true;
        header("Location: index.php?site=messenger&action=newmessage");
        exit();
    }
} elseif (isset($_POST['reply'])) {
    

    if (isset($userID)) {
        sendmessage($_POST['id'], $_POST['title'], $_POST['message'], $userID);
    }

    header("Location: index.php?site=messenger&action=outgoing");
    exit();
} elseif ($userID) {
    $_language->readModule('messenger');

    if (isset($_REQUEST['action'])) {
        $action = $_REQUEST['action'];
    } else {
        $action = "incoming";
    }

    $data_array = array();
    $data_array['$title'] = $_lang[ 'messenger' ];
    $data_array['$subtitle']='Messenger';
    $template = $GLOBALS["_template"]->loadTemplate("messenger","head", $data_array, $plugin_path);
    echo $template;
    
    if ($action == "incoming") {
        if (isset($_REQUEST['entries'])) {
            $entries = $_REQUEST['entries'];
        }

        $alle = safe_query("SELECT messageID FROM " . PREFIX . "plugins_messenger WHERE touser='$userID' AND userID='$userID'");
        $gesamt = mysqli_num_rows($alle);
        $pages = 1;
        if (isset($_GET['page'])) {
            $page = (int)$_GET['page'];
        } else {
            $page = 1;
        }
        $sort = "date";
        if (isset($_GET['sort'])) {
            if (($_GET['sort'] == 'date') || ($_GET['sort'] == 'fromuser') || ($_GET['sort'] == 'title')) {
                $sort = $_GET['sort'];
            }
        }
        $type = 'DESC';
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'ASC') {
                $type = 'ASC';
            }
        }

        if (isset($entries) && $entries > 0) {
            $max = (int)$entries;
        } else {
            $max = $maxmessages;
        }
        $pages = ceil($gesamt / $max);

        if ($pages > 1) {
            $page_link =
                makepagelink(
                    "index.php?site=messenger&amp;action=incoming&amp;sort=$sort&amp;type=$type&amp;entries=$max",
                    $page,
                    $pages
                );
        } else {
            $page_link = '';
        }

        if ($page == "1") {
            $ergebnis = safe_query(
                "SELECT
                    *
                FROM
                    " . PREFIX . "plugins_messenger
                WHERE
                    touser='$userID'
                AND
                    userID='$userID'
                ORDER BY
                    $sort $type LIMIT 0,$max"
            );
            if ($type == "DESC") {
                $n = $gesamt;
            } else {
                $n = 1;
            }
        } else {
            $start = $page * $max - $max;
            $ergebnis = safe_query(
                "SELECT
                    *
                FROM
                    " . PREFIX . "plugins_messenger
                WHERE
                    touser='$userID'
                AND
                    userID='$userID'
                ORDER BY
                    $sort $type LIMIT $start,$max"
            );
            if ($type == "DESC") {
                $n = ($gesamt) - $page * $max + $max;
            } else {
                $n = ($gesamt + 1) - $page * $max + $max;
            }
        }

        if ($type == "ASC") {
            $sorter = '<a href="index.php?site=messenger&amp;action=incoming&amp;page=' . $page . '&amp;sort=' . $sort .
                '&amp;type=DESC&amp;entries=' . $max . '">' . $_lang['sort'] .
                '</a> <i class="bi bi-arrow-down"></i>&nbsp;&nbsp;&nbsp;';
        } else {
            $sorter = '<a href="index.php?site=messenger&amp;action=incoming&amp;page=' . $page . '&amp;sort=' . $sort .
                '&amp;type=ASC&amp;entries=' . $max . '">' . $_lang['sort'] .
                '</a> <i class="bi bi-arrow-up"></i>&nbsp;&nbsp;&nbsp;';
        }

        $data_array = array();
        $data_array['$sorter'] = $sorter;
        $data_array['$page_link'] = $page_link;
        $data_array['$max'] = $max;
        
        $data_array['$messages_per_page'] = $_lang[ 'messages_per_page' ];
        $data_array['$incoming'] = $_lang[ 'incoming' ];
        $data_array['$outgoing'] = $_lang[ 'outgoing' ];
        $data_array['$new_message'] = $_lang[ 'new_message' ];
        $data_array['$message'] = $_lang[ 'message' ];
        $data_array['$lang_sender'] = $_lang[ 'sender' ];
        $data_array['$date'] = $_lang[ 'date' ];
        $data_array['$show'] = $_lang[ 'show' ];

        $template = $GLOBALS["_template"]->loadTemplate("messenger","incoming_head", $data_array, $plugin_path);
        echo $template;

        $anz = mysqli_num_rows($ergebnis);
        if ($anz) {
            $n = 1;
            while ($ds = mysqli_fetch_array($ergebnis)) {
                
                $date = getformatdatetime($ds['date']);
                if (trim($ds['fromuser']) != "0") {    
                    if (isonline($ds['fromuser']) == "offline") {
                        $statuspic = '<span class="label label-danger">'.$_lang[ 'lastlogin_inactiv' ].'</span>';
                    } else {
                        $statuspic = '<span class="label label-success">'.$_lang[ 'lastlogin_activ' ].'</span>';
                    }
                } else {                
                    $statuspic = "";
                }
                
                if (trim($ds['fromuser']) != "0") {
                    $sender = '<a href="index.php?site=profile&amp;id=' . $ds['fromuser'] . '"><b>' .
                    getnickname($ds['fromuser']) . '</b></a>';
                } else {                
                    $sender = "<b>System</b>";
                }

                $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
                if (@$dx[ 'modulname' ] != 'squads') {
                    $member = '';
                } else {
                    if (isclanmember($ds['fromuser'])) {
                        $member = '<i class="bi bi-person" style="color: #5cb85c"></i>';
                    } else {
                        $member = '';
                    }
                }

                if (trim($ds['title']) != "") {
                    $title = $ds['title'];
                } else {
                    $title = $_lang['no_subject'];
                }

                $new = '';
                $icon = '';
                if (!$ds['viewed']) {
                    $icon = '<i class="bi bi-envelope" style="color: #5cb85c"></i>';
                    $title = '<strong>' . $title . '</strong>';
                    $new = 'class="warning"';
                }

                $title =
                    '<a href="index.php?site=messenger&amp;action=show&amp;id=' . $ds['messageID'] . '">' . $title .
                    '</a>';

                $data_array = array();
                $data_array['$messageID'] = $ds['messageID'];
                $data_array['$icon'] = $icon;
                $data_array['$title'] = $title;
                $data_array['$sender'] = $sender;
                $data_array['$member'] = $member;
                $data_array['$statuspic'] = $statuspic;
                $data_array['$date'] = $date;
                
                $template = $GLOBALS["_template"]->loadTemplate("messenger","incoming_content", $data_array, $plugin_path);
                echo $template;
                $n++;
            }
        } else {
            echo '<tr>' . $_lang['no_incoming'] . '</td></tr>';
        }

        $data_array['$select_all'] = $_lang[ 'select_all' ];
        $data_array['$mark_viewed'] = $_lang[ 'mark_viewed' ];
        $data_array['$mark_not_viewed'] = $_lang[ 'mark_not_viewed' ];
        $data_array['$delete'] = $_lang[ 'delete' ];
        $data_array['$execute'] = $_lang[ 'execute' ];

        $template = $GLOBALS["_template"]->loadTemplate("messenger","incoming_foot", $data_array, $plugin_path);
        echo $template;

    } elseif ($action == "outgoing") {
        if (isset($_REQUEST['entries'])) {
            $entries = $_REQUEST['entries'];
        }

        $alle =
            safe_query("SELECT messageID FROM " . PREFIX . "plugins_messenger WHERE fromuser='$userID' AND userID='$userID'");
        $gesamt = mysqli_num_rows($alle);
        $pages = 1;

        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = (int)$_GET['page'];
        }
        $sort = 'date';
        $type = 'DESC';
        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'ASC') {
                $type = 'ASC';
            }
        }
        if (isset($entries) && $entries > 0) {
            $max = (int)$entries;
        } else {
            $max = $maxmessages;
        }
        $pages = ceil($gesamt / $max);

        if ($pages > 1) {
            $page_link = makepagelink("index.php?site=messenger&amp;action=outgoing&amp;entries=$max", $page, $pages);
        } else {
            $page_link = '';
        }

        if ($page == "1") {
            $ergebnis = safe_query(
                "SELECT
                    *
                FROM
                    " . PREFIX . "plugins_messenger
                WHERE
                    fromuser='$userID'
                AND
                    userID='$userID'
                ORDER BY
                    $sort $type LIMIT 0,$max"
            );
            if ($type == "DESC") {
                $n = $gesamt;
            } else {
                $n = 1;
            }
        } else {
            $start = $page * $max - $max;
            $ergebnis = safe_query(
                "SELECT
                    *
                FROM
                    " . PREFIX . "plugins_messenger
                WHERE
                    fromuser='$userID'
                AND
                    userID='$userID'
                ORDER BY
                    $sort $type LIMIT $start,$max"
            );
            if ($type == "DESC") {
                $n = ($gesamt) - $page * $max + $max;
            } else {
                $n = ($gesamt + 1) - $page * $max + $max;
            }
        }

        if ($type == "ASC") {
            $sorter = '<a href="index.php?site=messenger&amp;action=outgoing&amp;page=' . $page . '&amp;sort=' . $sort .
                '&amp;type=DESC&amp;entries=' . $max . '">' . $_lang['sort'] .
                '</a> <i class="bi bi-arrow-down"></i>&nbsp;&nbsp;&nbsp;';
        } else {
            $sorter = '<a href="index.php?site=messenger&amp;action=outgoing&amp;page=' . $page . '&amp;sort=' . $sort .
                '&amp;type=ASC&amp;entries=' . $max . '">' . $_lang['sort'] .
                '</a> <i class="bi bi-arrow-up"></i>&nbsp;&nbsp;&nbsp;';
        }

        $data_array = array();
        $data_array['$sorter'] = $sorter;
        $data_array['$page_link'] = $page_link;
        $data_array['$max'] = $max;
        
        $data_array['$messages_per_page'] = $_lang[ 'messages_per_page' ];
        $data_array['$incoming'] = $_lang[ 'incoming' ];
        $data_array['$outgoing'] = $_lang[ 'outgoing' ];
        $data_array['$new_message'] = $_lang[ 'new_message' ];
        $data_array['$message'] = $_lang[ 'message' ];
        $data_array['$receptionist'] = $_lang[ 'receptionist' ];
        $data_array['$date'] = $_lang[ 'date' ];
        $data_array['$show'] = $_lang[ 'show' ];

        $template = $GLOBALS["_template"]->loadTemplate("messenger","outgoing_head", $data_array, $plugin_path);
        echo $template;

        $anz = mysqli_num_rows($ergebnis);
        if ($anz) {
            $n = 1;
            while ($ds = mysqli_fetch_array($ergebnis)) {
                
                $date = getformatdatetime($ds['date']);

               $receptionist = '<a href="index.php?site=profile&amp;id=' . $ds['touser'] . '"><b>' .
                    getnickname($ds['touser']) . '</b></a>';

                $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
                if (@$dx[ 'modulname' ] != 'squads') {    
                    $member = '';                    
                } else {
                    if (isclanmember($ds['touser'])) {
                        $member = ' <i class="bi bi-person" style="color: #5cb85c"></i>';
                    } else {
                        $member = '';
                    }
                }

                if (isonline($ds['touser']) == "offline") {
                    $statuspic = '<span class="label label-danger">'.$_lang[ 'lastlogin_inactiv' ].'</span>';
                } else {
                    $statuspic = '<span class="label label-success">'.$_lang[ 'lastlogin_activ' ].'</span>';
                }

                if (trim($ds['title']) != "") {
                    $title = $ds['title'];
                } else {
                    $title = $_lang['no_subject'];
                }
                $title =
                    ' <a href="index.php?site=messenger&amp;action=show&amp;id=' . $ds['messageID'] . '">' . $title .
                    '</a>';

                $icon = '<img src="images/icons/pm_old.gif" width="14" height="12" alt="">';
                $data_array = array();
                $data_array['$messageID'] = $ds['messageID'];
                $data_array['$title'] = $title;
                $data_array['$receptionist'] = $receptionist;
                $data_array['$member'] = $member;
                $data_array['$statuspic'] = $statuspic;
                $data_array['$date'] = $date;
                
                $template = $GLOBALS["_template"]->loadTemplate("messenger","outgoing_content", $data_array, $plugin_path);
                echo $template;
                $n++;
            }
        } else {
            echo '<tr>' . $_lang['no_outgoing'] . '</td></tr>';
        }

        $data_array['$select_all'] = $_lang[ 'select_all' ];
        $data_array['$delete_selected'] = $_lang[ 'delete_selected' ];

        $template = $GLOBALS["_template"]->loadTemplate("messenger","outgoing_foot", $data_array, $plugin_path);
        echo $template;

    } elseif ($action == "show") {
        $id = (int)$_GET['id'];
        $ds = mysqli_fetch_array(
            safe_query(
                "SELECT
                    *
                FROM
                    " . PREFIX . "plugins_messenger
                WHERE
                    messageID='" . $id . "'
                AND
                    userID='" . $userID . "'"
            )
        );

        if ($ds['touser'] == $userID || $ds['fromuser'] == $userID) {
            safe_query("UPDATE " . PREFIX . "plugins_messenger SET viewed='1' WHERE messageID='$id'");
            $date = getformatdatetime($ds['date']);
            
            if (trim($ds['fromuser']) != "0") {
                $sender = ''.$_lang[ 'from' ].' <a href="index.php?site=profile&amp;id=' . $ds['fromuser'] . '"><b>' .
                getnickname($ds['fromuser']) . '</b></a>';
            } else {
                $sender = ''.$_lang[ 'from the' ].'  <b>System</b>';
            }

            $message = $ds['message'];
            $title = $ds['title'];

            $data_array = array();
            $data_array['$title'] = $title;
            $data_array['$date'] = $date;
            $data_array['$sender'] = $sender;
            $data_array['$message'] = $message;
            $data_array['$id'] = $id;
            
            $data_array['$incoming'] = $_lang[ 'incoming' ];
            $data_array['$outgoing'] = $_lang[ 'outgoing' ];
            $data_array['$new_message'] = $_lang[ 'new_message' ];
            $data_array['$read'] = $_lang[ 'read' ];
            $data_array['$reply'] = $_lang[ 'reply' ];
            $data_array['$delete'] = $_lang[ 'delete' ];

            $template = $GLOBALS["_template"]->loadTemplate("messenger","show", $data_array, $plugin_path);
            echo $template;
        } else {
            redirect('index.php?site=messenger', '', 0);
        }
    } elseif ($action == "touser") {
        $touser = $_GET['touser'];        
        $tousernick = getnickname($touser);
        $touser = getforminput($touser);
                
        $data_array = array();
        $data_array['$tousernick'] = $tousernick;
        $data_array['$touser'] = $touser;        

        $data_array['$incoming'] = $_lang[ 'incoming' ];
        $data_array['$outgoing'] = $_lang[ 'outgoing' ];
        $data_array['$new_message'] = $_lang[ 'new_message' ];
        $data_array['$recipient'] = $_lang[ 'recipient' ];
        $data_array['$title_head'] = $_lang[ 'title' ];
        $data_array['$your_message'] = $_lang[ 'your_message' ];
        $data_array['$options'] = $_lang[ 'options' ];
        $data_array['$send_message'] = $_lang[ 'send_message' ];

        $template = $GLOBALS["_template"]->loadTemplate("messenger","new_touser", $data_array, $plugin_path);
        echo $template;

    } elseif ($action == "reply") {
        $id = $_GET['id'];
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_messenger WHERE messageID='$id'");
        $ds = mysqli_fetch_array($ergebnis);
        if ($ds['touser'] == $userID || $ds['fromuser'] == $userID) {
            $replytouser = $ds['fromuser'];
            $tousernick = getnickname($replytouser);
            $date = getformatdatetime($ds['date']);

            $title = $ds['title'];
            if (!preg_match("#Re\[(.*?)\]:#si", $title)) {
                $title = "Re[1]: " . $title;
            } else {
                preg_match_all("#Re\[(.*?)\]:#si", $title, $re);
                $rep = $re[1][0] + 1;
                $title = preg_replace("#\[(.*?)\]#si", "[$rep]", $title);
            }

            $message = '' . getinput($ds['message']) .' <p class="text-right"><i><b>' . $_lang['sent_by'] . ' '. $tousernick .'</b> ('. $date.')</i></p><hr><br><br>';
            
            $data_array = array();
            $data_array['$title'] = $title;
            $data_array['$tousernick'] = $tousernick;
            $data_array['$message'] = $message;
            $data_array['$replytouser'] = $replytouser;            

            $data_array['$incoming'] = $_lang[ 'incoming' ];
            $data_array['$outgoing'] = $_lang[ 'outgoing' ];
            $data_array['$reply'] = $_lang[ 'reply' ];
            $data_array['$recipient'] = $_lang[ 'recipient' ];
            $data_array['$title_head'] = $_lang[ 'title' ];
            $data_array['$your_message'] = $_lang[ 'your_message' ];
            $data_array['$options'] = $_lang[ 'options' ];
            $data_array['$send_message'] = $_lang[ 'send_message' ];
        
            $template = $GLOBALS["_template"]->loadTemplate("messenger","reply", $data_array, $plugin_path);
            echo $template;

        } else {
            redirect('index.php?site=messenger', '', 0);
        }
    } elseif ($action == "newmessage") {
        
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "user ORDER BY nickname asc");
        $user = '';
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $id = $ds[ 'userID' ];
            $user .= '<option value="' . $ds['userID'] . '">' . getnickname($ds['userID']) . '</option>';
        }

        if ($user == "") {
            $user = '<option value="">' . $_lang['no_buddies'] . '</option>';
        } else {
            $user = '<option value="" selected="selected">---</option>' . $user;
        }

        if (isset($_SESSION['message_error'])) {
            $subject = getforminput($_SESSION['message_subject']);
            $message = getforminput($_SESSION['message_body']);
            $error = generateErrorBoxFromArray($_lang['error'], array($_lang['unknown_user']));
            unset($_SESSION['message_subject'], $_SESSION['message_body'], $_SESSION['message_error']);
        } else {
            $error = $message = $subject = "";
        }

        if (isanyadmin($userID)) {
            $admin = '<strong>' . $_lang['adminoptions'] . '</strong><br>' .
                $_lang['sendeachuser'] . '<input class="input" type="checkbox" name="eachuser" value="true">
                <br>' . $_lang['sendeachmember'] .
                '<input class="input" type="checkbox" name="eachmember" value="true">';
        } else {
            $admin = '';
        }
        
        $data_array = array();
        $data_array['$error'] = $error;
        $data_array['$subject'] = $subject;
        $data_array['$user'] = $user;
        $data_array['$message'] = $message;
        $data_array['$admin'] = $admin;
        
        $data_array['$incoming'] = $_lang[ 'incoming' ];
        $data_array['$outgoing'] = $_lang[ 'outgoing' ];
        $data_array['$new_message'] = $_lang[ 'new_message' ];
        $data_array['$recipient'] = $_lang[ 'recipient' ];
        $data_array['$title_head'] = $_lang[ 'title' ];
        $data_array['$enter_username'] = $_lang[ 'enter_username' ];
        $data_array['$your_message'] = $_lang[ 'your_message' ];
        $data_array['$options'] = $_lang[ 'options' ];
        $data_array['$send_message'] = $_lang[ 'send_message' ];
        $data_array['$formation'] = $_lang[ 'formation' ];
		$data_array['$or'] = $_lang[ 'or' ];
        
        $template = $GLOBALS["_template"]->loadTemplate("messenger","new", $data_array, $plugin_path);
        echo $template;
    }
    
} else {
    $_language->readModule('messenger');
    echo $_lang['not_logged'];
}
