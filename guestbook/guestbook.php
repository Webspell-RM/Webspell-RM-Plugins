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
    $plugin_language = $pm->plugin_language("guestbook", $plugin_path);

try {
    $get = mysqli_fetch_assoc(safe_query("SELECT * FROM `".PREFIX."settings_recaptcha`"));
    $webkey = $get['webkey'];
    $seckey = $get['seckey'];
    if ($get['activated']=="1") { $recaptcha=1; } else { $recaptcha=0; }
} Catch (EXCEPTION $e) {
    $recaptcha=0;
}

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if (isset($_POST[ 'save' ])) {
    
    $date = time();
    $run = 0;

    if ($userID) {
        $name = $_database->escape_string(getnickname($userID));
        if (getemailhide($userID)) {
            $email = '';
        } else {
            $email = getemail($userID);
        }
        $url = gethomepage($userID);
        $discord = getdiscord($userID);
        $run = 1;
    } else {
        $name = $_POST[ 'gbname' ];
        $email = $_POST[ 'gbemail' ];
        $url = $_POST[ 'gburl' ];
        $discord = $_POST[ 'discord' ];
        $CAPCLASS = new \webspell\Captcha;
        
        if($recaptcha!=1) {
            $CAPCLASS = new \webspell\Captcha;
            if (!$CAPCLASS->checkCaptcha($_POST['captcha'], $_POST['captcha_hash'])) {
                $fehler[] = "Securitycode Error";
                $runregister = "false";
            } else {
                $run = 1;
                $runregister = "true";
            }
        } else {
            $runregister = "false";
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $recaptcha=$_POST['g-recaptcha-response'];
                if(!empty($recaptcha)) {
                    include("system/curl_recaptcha.php");
                    $google_url="https://www.google.com/recaptcha/api/siteverify";
                    $secret=$seckey;
                    $ip=$_SERVER['REMOTE_ADDR'];
                    $url=$google_url."?secret=".$secret."&response=".$recaptcha."&remoteip=".$ip;
                    $res=getCurlData($url);
                    $res= json_decode($res, true);
                    //reCaptcha success check 
                    if($res['success'])     {
                    $runregister="true"; $run=1;
                    }       else        {
                        $fehler[] = "reCAPTCHA Error";
                        $runregister="false";
                    }
                } else {
                    $fehler[] = "reCAPTCHA Error";
                    $runregister="false";
                }
            }
        }
    }

    if ($run) {
        if (mb_strlen($_POST[ 'message' ])) {
            safe_query(
                "INSERT INTO
                    " . PREFIX . "plugins_guestbook (date, name, email, hp, discord, ip, comment)
                VALUES
                    ('" . $date . "',
                    '" . $name . "',
                    '" . $email . "',
                    '" . $url . "',
                    '" . $discord . "',
                    '" . $GLOBALS[ 'ip' ] . "',
                    '" . $_POST[ 'message' ] . "');"
            );

            if ($gb_info) {
                $ergebnis = safe_query("SELECT userID FROM " . PREFIX . "user_groups WHERE feedback='1'");
                while ($ds = mysqli_fetch_array($ergebnis)) {
                    $touser[ ] = $ds[ 'userID' ];
                }

                $message = str_replace(
                    '%insertid%',
                    'id_' . mysqli_insert_id($_database),
                    $_database->escape_string($plugin_language[ 'pmtext_newentry' ])
                );
                foreach ($touser as $id) {
                    sendmessage($id, $_database->escape_string($plugin_language[ 'pmsubject_newentry' ]), $message);
                }
            }
            header("Location: index.php?site=guestbook");
        } else {
            header("Location: index.php?site=guestbook&action=add&error=message");
        }
    } else {
        header("Location: index.php?site=guestbook&action=add&error=captcha");
    }
} elseif (isset($_GET[ 'delete' ])) {
    
    if (!isfeedbackadmin($userID)) {
        die($plugin_language[ 'no_access' ]);
    }
    if (isset($_POST[ 'gbID' ])) {
        foreach ($_POST[ 'gbID' ] as $id) {
            safe_query("DELETE FROM " . PREFIX . "plugins_guestbook WHERE gbID='$id'");
        }
    }
    header("Location: index.php?site=guestbook");
} elseif (isset($_POST[ 'savecomment' ])) {
    
    if (!isfeedbackadmin($userID)) {
        die($plugin_language[ 'no_access' ]);
    }

    safe_query(
        "UPDATE
            " . PREFIX . "plugins_guestbook
        SET
            admincomment='" . $_POST[ 'message' ] . "'
        WHERE
            gbID='" . $_POST[ 'guestbookID' ] . "' "
    );

    header("Location: index.php?site=guestbook");
} elseif ($action == 'comment' && is_numeric($_GET[ 'guestbookID' ])) {
    
    if (!isfeedbackadmin($userID)) {
        die($plugin_language[ 'no_access' ]);
    }
    $ergebnis =
        safe_query("SELECT admincomment FROM " . PREFIX . "plugins_guestbook WHERE gbID='" . intval($_GET[ 'guestbookID' ]) . "'");
    
    $ds = mysqli_fetch_array($ergebnis);
    $admincomment = getinput($ds[ 'admincomment' ]);
    
    $data_array= array();
    $data_array['$title']=$plugin_language['guestbook'];    
    $data_array['$subtitle']='Guestbook';
    
    $template = $GLOBALS["_template"]->loadTemplate("guestbook","title", $data_array, $plugin_path);
    echo $template;


    $data_array = array();
    $data_array['$admincomment'] = $admincomment;
    $data_array['$guestbookID'] = (int)$_GET[ 'guestbookID' ];

    $data_array['$lang_guestbook_comment']=$plugin_language['guestbook_comment'];
    $data_array['$lang_comment']=$plugin_language['comment'];
    $data_array['$lang_update_comment']=$plugin_language['update_comment'];
    
    $template = $GLOBALS["_template"]->loadTemplate("guestbook","comment", $data_array, $plugin_path);
    echo $template;
} elseif ($action == 'add') {

    $data_array= array();
    $data_array['$title']=$plugin_language['guestbook'];    
    $data_array['$subtitle']='Guestbook';

    $template = $GLOBALS["_template"]->loadTemplate("guestbook","title", $data_array, $plugin_path);
    echo $template;
    
    $message = '';
    if (isset($_GET[ 'messageID' ])) {
        if (is_numeric($_GET[ 'messageID' ])) {
            $ds = mysqli_fetch_array(
                safe_query(
                    "SELECT
                        comment, name
                    FROM
                        `" . PREFIX . "plugins_guestbook`
                    WHERE gbID='" . intval($_GET[ 'messageID' ]) . "'"
                )
            );
            $message = '<blockquote>' . $ds[ 'name' ] . ':<br>' . getinput($ds[ 'comment' ]) . '</blockquote><br><br>';
        }
    }

    if (isset($_GET[ 'error' ])) {
        if ($_GET[ 'error' ] == "captcha") {
            $error = $plugin_language[ 'error_captcha' ];
        } else {
            $error = $plugin_language[ 'enter_a_message' ];
        }
    } else {
        $error = null;
    }
    if ($loggedin) {
        
        $data_array = array();
        $data_array['$message'] = $message;

        $data_array['$lang_new_entry']=$plugin_language['new_entry'];
        $data_array['$lang_your_message']=$plugin_language['your_message'];
        $data_array['$lang_submit']=$plugin_language['submit'];
        
        $template = $GLOBALS["_template"]->loadTemplate("guestbook","loggedin", $data_array, $plugin_path);
        echo $template;
    } else {
        $CAPCLASS = new \webspell\Captcha;
        $captcha = $CAPCLASS->createCaptcha();
        $hash = $CAPCLASS->getHash();
        $CAPCLASS->clearOldCaptcha();

        if($recaptcha=="1") { 
        $_captcha = '<div class="g-recaptcha" style="width: 70%; float: left;" data-sitekey="'.$webkey.'"></div>'; 
                    } else { 
        $_captcha = '<span class="input-group-addon captcha-img">'.$captcha.'</span>
                        <input type="number" name="captcha" class="form-control" id="input-security-code" required>
                        <input name="captcha_hash" type="hidden" value="'.$hash.'">';
    }

        $data_array = array();
        $data_array['$error'] = $error;
        $data_array['$message'] = $message;
        $data_array['$_captcha'] = $_captcha;
        $data_array['$hash'] = $hash;

        $data_array['$lang_new_entry']=$plugin_language['new_entry'];
        $data_array['$lang_name']=$plugin_language['name'];
        $data_array['$lang_mail']=$plugin_language['mail'];
        $data_array['$lang_discord']=$plugin_language['discord'];
        $data_array['$lang_your_message']=$plugin_language['your_message'];
        $data_array['$lang_submit']=$plugin_language['submit'];
        $data_array['$lang_security_code']=$plugin_language['security_code'];
        $data_array['$lang_GDPRinfo']=$plugin_language['GDPRinfo'];
        
        $template = $GLOBALS["_template"]->loadTemplate("guestbook","notloggedin", $data_array, $plugin_path);
        echo $template;
    }
} else {

    $data_array= array();
    $data_array['$title']=$plugin_language['guestbook'];    
    $data_array['$subtitle']='Guestbook';
    
    $template = $GLOBALS["_template"]->loadTemplate("guestbook","title", $data_array, $plugin_path);
    echo $template;

    $gesamt = mysqli_num_rows(safe_query("SELECT gbID FROM " . PREFIX . "plugins_guestbook"));

    if (isset($_GET[ 'page' ])) {
        $page = (int)$_GET[ 'page' ];
    } else {
        $page = 1;
    }
    $type = "DESC";
    if (isset($_GET[ 'type' ])) {
        if (($_GET[ 'type' ] == 'ASC') || ($_GET[ 'type' ] == 'DESC')) {
            $type = $_GET[ 'type' ];
        }
    }



if (empty($maxguestbook)) {
    $maxguestbook = 20;
}


    $pages = ceil($gesamt / $maxguestbook);

    if ($pages > 1) {
        $page_link = makepagelink("index.php?site=guestbook&amp;type=$type", $page, $pages);
    } else {
        $page_link = '';
    }

    if ($page == "1") {
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_guestbook ORDER BY date $type LIMIT 0,$maxguestbook");
        if ($type == "DESC") {
            $n = $gesamt;
        } else {
            $n = 1;
        }
    } else {
        $start = $page * $maxguestbook - $maxguestbook;
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_guestbook ORDER BY date $type LIMIT $start,$maxguestbook");
        if ($type == "DESC") {
            $n = $gesamt - ($page - 1) * $maxguestbook;
        } else {
            $n = ($page - 1) * $maxguestbook + 1;
        }
    }

    if ($type == "ASC") {
        $sorter =
            '<a href="index.php?site=guestbook&amp;page=' . $page . '&amp;type=DESC">' . $plugin_language[ 'sort' ] .
            ' <i class="bi bi-chevron-down"></i></a>';
    } else {
        $sorter =
            '<a href="index.php?site=guestbook&amp;page=' . $page . '&amp;type=ASC">' . $plugin_language[ 'sort' ] .
            ' <i class="bi bi-chevron-up"></i></a>';
    }

    $data_array = array();
    $data_array['$sorter'] = $sorter;
    $data_array['$page_link'] = $page_link;

    $data_array['$lang_new_entry']=$plugin_language['new_entry'];
    
    $template = $GLOBALS["_template"]->loadTemplate("guestbook","head", $data_array, $plugin_path);
    echo $template;

    while ($ds = mysqli_fetch_array($ergebnis)) {
        
        $date = getformatdatetime($ds[ 'date' ]);

        if (validate_email($ds[ 'email' ])) {
            $email = '<a href="mailto:' . mail_protect($ds[ 'email' ]) .
                '"><i class="bi bi-envelope-fill"></i></a>';
        } else {
            $email = '';
        }

        if (validate_url($ds[ 'hp' ])) {
            $hp = '<a href="' . $ds[ 'hp' ] .
                '" target="_blank"><i class="bi bi-house-door-fill"></i></a>';
        } else {
            $hp = '';
        }

        $sem = '/[0-9]{6,11}/si';
        #$discord_number = str_replace('-', '', $ds[ 'discord' ]);
        $discord_number = @$ds[ 'discord' ];
        #if (preg_match($sem, $ds[ 'discord' ])) {
        if (@$ds[ 'discord' ]) {
            $discord = '<i class="bi bi-discord" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$discord_number.'"></i>';
        } else {
            $discord = "";
        }
        $guestbookID = 'id_' . $ds[ 'gbID' ];
        $name = strip_tags($ds[ 'name' ]);
        $message = $ds[ 'comment' ];
        #$message = $ds[ 'gbID' ];
        unset($admincomment);
        if ($ds[ 'admincomment' ] != "") {
            $admincomment = '<hr><small><strong>' . $plugin_language[ 'admin_comment' ] . ':</strong><br>' .
                $ds[ 'admincomment' ] . '</small>';
        } else {
            $admincomment = '';
        }

        $actions = '';
        $ip = 'logged';
        $quote = '<a href="index.php?site=guestbook&amp;action=add&amp;messageID=' . $ds[ 'gbID' ] .
            '"><i class="bi bi-quote"></i></a>';
        if (isfeedbackadmin($userID)) {
            $actions = '<input class="input" type="checkbox" name="gbID[]" value="' . $ds[ 'gbID' ] .
                '"> <a href="index.php?site=guestbook&amp;action=comment&amp;guestbookID=' . $ds[ 'gbID' ] .
                '" class="btn btn-danger">' . $plugin_language[ 'add_admincomment' ] . '</a>';
            $ip = $ds[ 'ip' ];
        }

        $data_array = array();
        $data_array['$actions'] = $actions;
        $data_array['$name'] = $name;
        $data_array['$date'] = $date;
        $data_array['$email'] = $email;
        $data_array['$hp'] = $hp;
        $data_array['$discord'] = $discord;
        $data_array['$ip'] = $ip;
        $data_array['$quote'] = $quote;
        $data_array['$message'] = $message;
        $data_array['$admincomment'] = $admincomment;

        $data_array['$lang_ip']=$plugin_language['ip'];
        
        $template = $GLOBALS["_template"]->loadTemplate("guestbook","content", $data_array, $plugin_path);
        echo $template;

        if ($type == "DESC") {
            $n--;
        } else {
            $n++;
        }
    }

    if (isfeedbackadmin($userID)) {
        $submit = '<input class="input" type="checkbox" name="ALL" value="ALL" onclick="SelectAll(this.form);"> ' .
            $plugin_language[ 'select_all' ] . '
            <input type="submit" value="' . $plugin_language[ 'delete_selected' ] . '" class="btn btn-danger">';
    } else {
        $submit = '';
    }

    $data_array = array();
    $data_array['$page_link'] = $page_link;
    $data_array['$submit'] = $submit;
    
    $template = $GLOBALS["_template"]->loadTemplate("guestbook","foot", $data_array, $plugin_path);
    echo $template;
}
