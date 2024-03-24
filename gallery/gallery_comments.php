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
$plugin_language = $pm->plugin_language("comments", $plugin_path);

try {
    $get = mysqli_fetch_assoc(safe_query("SELECT * FROM `".PREFIX."settings_recaptcha`"));
    $webkey = $get['webkey'];
    $seckey = $get['seckey'];
    if ($get['activated']=="1") { $recaptcha=1; } else { $recaptcha=0; }
} Catch (EXCEPTION $e) {
    $recaptcha=0;
}

function checkCommentsAllow($type, $parentID)
{
global $userID;
    $moduls = array();
    $moduls['ga'] = array("gallery","galleryID","comments");
    $allowed = 0;
    $modul = $moduls[$type];
    $get = safe_query("SELECT ".$modul[2]." FROM ".PREFIX.$modul[0]." WHERE ".$modul[1]."='".$parentID."'");
    if(mysql_num_rows($get)){
        $data = mysql_fetch_assoc($get);
        switch($data[$modul[2]]){
            case 0: $allowed = 0; break;
            case 1: if($userID) $allowed = 1; break;
            case 2: $allowed = 2; break;
            default: $allowed=0;
        }
    }
    return $allowed;
}

 
if (isset($_POST[ 'savevisitorcomment' ])) {
    $name = $_POST[ 'name' ];
    $mail = $_POST[ 'mail' ];
    $homepage = $_POST[ 'homepage' ];
    $parentID = $_POST[ 'parentID' ];
    $type = $_POST[ 'type' ];
    $message = $_POST[ 'message' ];
 
    $date = time();
    
    $CAPCLASS = new \webspell\Captcha;
    $captcha = $CAPCLASS->createCaptcha();
    $hash = $CAPCLASS->getHash();
    $CAPCLASS->clearOldCaptcha();
 
    
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
                    include("./system/curl_recaptcha.php");
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
   
 
   if (mysqli_num_rows(safe_query("SELECT * FROM " . PREFIX . "user WHERE nickname = '$name' "))) {
            $name = '*' . $name . '*';
        }
        $name = $name;
    }
   if (!empty($name) && !empty($message)) {
        $date = time();
        $ip = $GLOBALS[ 'ip' ];
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_comments ORDER BY date DESC LIMIT 0,1");
        $ds = mysqli_fetch_array($ergebnis);
        if (($ds[ 'message' ] != $message) ||
            ($ds[ 'name' ] != $name)
        ) {
        
        safe_query(
                "INSERT INTO `" . PREFIX . "plugins_gallery_comments` (
                    `parentID`,
                    `type`,
                    `nickname`,
                    `date`,
                    `comments`,
                    `homepage`,
                    `email`,
                    `ip`
                )
                VALUES (
                    '" . $parentID . "',
                    '" . $type . "',
                    '" . $name . "',
                    '" . $date . "',
                    '" . $message . "',
                    '" . $homepage . "',
                    '" . $mail . "',
                    '" . $ip . "'
                )"
            );
        
       
//neu IM bei neuen comments von einem Gast ///////////////////////////////////////

//$dt = safe_query("SELECT userID FROM " . PREFIX . "user WHERE pmoncomment='1'");

$dt = safe_query("SELECT userID FROM " . PREFIX . "user_groups WHERE page='1'");
 
while ($ds = mysqli_fetch_array($dt)) {
$touser[] = $ds[ 'userID' ];
 }  
 
 
if ($userID) {
$name = getnickname($userID, $id);
} else { $name = ''.$plugin_language[ 'a_guest' ].'' ; }
 
    $msgfrom= array();
    $msgfrom[ 'ga' ] = ''.$plugin_language[ 'to_an_image' ].'';
    if (isset($msgfrom[$_POST['type']])) {
        $_message_from = $msgfrom[$_POST['type']];
    } else {
        $_message_from = "unknown";
    }
 
 
foreach($touser as $id) {
 
$message = ''.$plugin_language[ 'hello' ].' '.getnickname($id).',<br>' . $name . ' '.$plugin_language[ 'a_comment' ].' ' . $_message_from . ' .<br><a href="' . $_POST[ 'referer' ] . '"><i>'.$plugin_language[ 'click_here' ].' .</i></a>';
 
sendmessage($id,''.$plugin_language[ 'new_comment' ].' ' . $_message_from . '',$message);
;}

//ende /////////////////////////////////////////////////////////////////////  
        header("Location: " . $_POST[ 'referer' ]);
    }
} elseif (isset($_POST[ 'saveusercomment' ])) {
    
    #
    if (!$userID) {
        die($plugin_language[ 'access_denied' ]);
    }
 
    $parentID = $_POST[ 'parentID' ];
    $type = $_POST[ 'type' ];
    $message = $_POST[ 'message' ];
 
    
        $date = time();
        safe_query(
                "INSERT INTO
                    `" . PREFIX . "plugins_gallery_comments` (
                        `parentID`,
                        `type`,
                        `userID`,
                        `date`,
                        `comments`
                    )
                    VALUES (
                        '" . $parentID . "',
                        '" . $type . "',
                        '" . $userID . "',
                        '" . time() . "',
                        '" . $message . "'
                    )"
            );
//neu IM bei neuen comments von reg.member ///////////////////////////////////////

//$dt = safe_query("SELECT userID FROM " . PREFIX . "user WHERE pmoncomment='1'");

$dt = safe_query("SELECT userID FROM " . PREFIX . "user_groups WHERE page='1'");
 
while ($ds = mysqli_fetch_array($dt)) {
$touser[] = $ds[ 'userID' ];
 }  
 
 
if ($userID) {
$name = getnickname($userID, $id);
} else { $name = ''.$plugin_language[ 'a_guest' ].'' ; }
 
    $msgfrom= array();
    $msgfrom[ 'ga' ] = ''.$plugin_language[ 'to_an_image' ].'';
    if (isset($msgfrom[$_POST['type']])) {
        $_message_from = $msgfrom[$_POST['type']];
    } else {
        $_message_from = "unknown";
    }
 
 
foreach($touser as $id) {
 
$message = ''.$plugin_language[ 'hello' ].' '.getnickname($id).',<br>' . $name . ' '.$plugin_language[ 'a_comment' ].' ' . $_message_from . ' .<br><a href="' . $_POST[ 'referer' ] . '"><i>'.$plugin_language[ 'click_here' ].' .</i></a>';
 
sendmessage($id,''.$plugin_language[ 'new_comment' ].' ' . $_message_from . '',$message);
;}

//ende /////////////////////////////////////////////////////////////////////     
    header("Location: " . $_POST[ 'referer' ]);
} elseif (isset($_GET[ 'delete' ])) {
    
    if (!isanyadmin($userID)) {
        die($plugin_language[ 'access_denied' ]);
    }

    foreach ($_POST[ 'commentID' ] as $id) {
        safe_query("DELETE FROM " . PREFIX . "plugins_gallery_comments WHERE commentID='" . (int)$id."'");
    }
    header("Location: " . $_POST[ 'referer' ]);

} else {
    
    
    if (isset($_GET[ 'commentspage' ])) {
        $commentspage = (int)$_GET[ 'commentspage' ];
    } else {
        $commentspage = 1;
    }
    if (isset($_GET[ 'sorttype' ]) && strtoupper($_GET[ 'sorttype' ] == "ASC")) {
        $sorttype = 'ASC';
    } else {
        $sorttype = 'DESC';
    }
 
    if (!isset($parentID) && isset($_GET[ 'parentID' ])) {
        $parentID = (int)$_GET[ 'parentID' ];
    }
    if (!isset($type) && isset($_GET[ 'type' ])) {
        $type = mb_substr($_GET[ 'type' ], 0, 2);
    }
 
    $alle = safe_query(
        "SELECT
            `commentID`
        FROM
            `" . PREFIX . "plugins_gallery_comments`
        WHERE
            `parentID` = '" . (int)$parentID . "' AND
            `type` = '" . $type."'"
    );

    # #=========
        #$settings = safe_query("SELECT * FROM " . PREFIX . "plugins_news_settings");
        #$ds = mysqli_fetch_array($settings);

    
        #$maxfeedback = $ds[ 'feedback' ];
        if (empty($maxfeedback)) {
        $maxfeedback = 10;
        }
 
        
  #=========        

    $gesamt = mysqli_num_rows($alle);
    $commentspages = ceil($gesamt / $maxfeedback);
 
    if ($commentspages > 1) {
        $page_link = makepagelink("$referer&amp;sorttype=$sorttype", $commentspage, $commentspages, 'comments');
    } else {
        $page_link = '';
    }
 
    if ($commentspage == "1") {
        $ergebnis = safe_query(
            "SELECT
                *
            FROM
                `" . PREFIX . "plugins_gallery_comments`
            WHERE
                `parentID` = '$parentID' AND
                `type` = '$type'
            ORDER BY
                `date` $sorttype
            LIMIT 0, ".(int)$maxfeedback
        );
        if ($sorttype == "DESC") {
            $n = $gesamt;
        } else {
            $n = 1;
        }
    } else {
        $start = ($commentspage - 1) * $maxfeedback;
        $ergebnis = safe_query(
            "SELECT
                *
            FROM
                `" . PREFIX . "plugins_gallery_comments`
            WHERE
                `parentID` = '$parentID' AND
                `type` = '$type'
            ORDER BY
                `date` $sorttype
            LIMIT $start, " . (int)$maxfeedback
        );
        if ($sorttype == "DESC") {
            $n = $gesamt - ($commentspage - 1) * $maxfeedback;
        } else {
            $n = ($commentspage - 1) * $maxfeedback + 1;
        }
    }
    if ($gesamt) {
        $data_array = array();
        $data_array['$comments']=$plugin_language['comments'];
        $template = $GLOBALS["_template"]->loadTemplate("comments","title", $data_array, $plugin_path);
        echo $template;
 
        if ($sorttype == "ASC") {
            $sorter = '<a href="' . $referer . '&amp;commentspage=' . $commentspage . '&amp;sorttype=DESC">' .
                $plugin_language[ 'sort' ] . '</a> <i class="bi bi-chevron-down" title="' .
                $plugin_language[ 'sort_desc' ] . '"></span>&nbsp;&nbsp;&nbsp;';
        } else {
            $sorter = '<a href="' . $referer . '&amp;commentspage=' . $commentspage . '&amp;sorttype=ASC">' .
                $plugin_language[ 'sort' ] . '</a> <i class="bi bi-chevron-up" title="' .
                $plugin_language[ 'sort_asc' ] . '"></span>&nbsp;&nbsp;&nbsp;';
        }
 
        $data_array = array();
        $data_array['$sorter'] = $sorter;
        
        $template = $GLOBALS["_template"]->loadTemplate("comments","head", $data_array, $plugin_path);
        echo $template;
 
        while ($ds = mysqli_fetch_array($ergebnis)) {
             
            $date = getformatdatetime($ds[ 'date' ]);
 
            if ($ds[ 'userID' ]) {
                $ip = '';
                $poster = '<a class="titlelink" href="index.php?site=profile&amp;id=' . $ds[ 'userID' ] . '"><b>' .
                    strip_tags(getnickname($ds[ 'userID' ])) . '</b></a>';
                $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
                if (@$dx[ 'modulname' ] != 'squads') {    
                    $member = '';                    
                } else {
                    if (isclanmember($ds[ 'userID' ])) {
                        $member = ' <i class="bi bi-person" style="color: #5cb85c"></i>';
                    } else {
                        $member = '';
                    }
                }
 
                $quotemessage = addslashes(getinput($ds[ 'comments' ]));
                $quotemessage = str_replace(array("\r\n", "\r", "\n"), array('\r\n', '\r', '\n'), $quotemessage);
                $quotenickname = addslashes(getinput(getnickname($ds[ 'userID' ])));
                $quote = str_replace(
                    array('%nickname%', '%message%'),
                    array($quotenickname, $quotemessage),
                    $plugin_language[ 'quote_link' ]
                );
 
                if (($email = getemail($ds[ 'userID' ])) && !getemailhide($ds[ 'userID' ])) {
                    $email = str_replace('%email%', mail_protect($email), $plugin_language[ 'email_link' ]);
                } else {
                    $email = '';
                }
                $gethomepage = gethomepage($ds[ 'userID' ]);
                if ($gethomepage != "" && $gethomepage != "http://" && $gethomepage != "http:///"
                    && $gethomepage != "n/a"
                ) {
                    $hp = '<a href="http://' . $gethomepage .
                        '" target="_blank"><i class="bi bi-house-door-fill" title="' .
                        $plugin_language[ 'homepage' ] . '"></i></a>';
                } else {
                    $hp = '';
                }
 
                if (isonline($ds[ 'userID' ]) == "offline") {
                    $statuspic = '<span class="label label-danger">offline</span>';
                } else {
                    $statuspic = '<span class="label label-success">online</span>';
                }
 
                #$avatar = '<img src="images/avatars/' . getavatar($ds[ 'userID' ]) .
                #    '" class="text-left" alt="Avatar">';
 
                if ($loggedin && $ds[ 'userID' ] != $userID) {
                    $pm = '<a href="index.php?site=messenger&amp;action=touser&amp;touser=' . $ds[ 'userID' ] .
                        '"><i class="bi bi-envelope-fill" title="' .
                        $plugin_language[ 'send_message' ] . '"></i></a>';
                    
                } else {
                    $pm = '';
                    
                }
            } else {
                $member = '';
                
                
                $pm = '';
                $statuspic = '';
                $ds[ 'nickname' ] = strip_tags($ds[ 'nickname' ]);
                $ds[ 'nickname' ] = htmlspecialchars($ds[ 'nickname' ]);
                $poster = strip_tags($ds[ 'nickname' ]);
 
                $ds[ 'email' ] = strip_tags($ds[ 'email' ]);
                $ds[ 'email' ] = htmlspecialchars($ds[ 'email' ]);
                if ($ds[ 'email' ]) {
                    $email = str_replace('%email%', mail_protect($ds[ 'email' ]), $plugin_language[ 'email_link' ]);
                } else {
                    $email = '';
                }
 
                $ds[ 'homepage' ] = strip_tags($ds[ 'homepage' ]);
                $ds[ 'homepage' ] = htmlspecialchars($ds[ 'homepage' ]);
                if (!stristr($ds[ 'homepage' ], 'http://')) {
                    $ds[ 'homepage' ] = "http://" . $ds[ 'homepage' ];
                }
                if ($ds[ 'homepage' ] != "http://" && $ds[ 'homepage' ] != "") {
                    $hp = '<a href="' . $ds[ 'homepage' ] .
                        '" target="_blank"><i class="bi bi-house-door-fill" title="' .
                        $plugin_language[ 'homepage' ] . '"></i></a>';
                } else {
                    $hp = '';
                }
                $ip = 'IP: ';
                if (isfeedbackadmin($userID)) {
                    $ip .= $ds[ 'ip' ];
                } else {
                    $ip .= 'saved';
                }
 
                $quotemessage = addslashes(getinput($ds[ 'comments' ]));
                $quotenickname = addslashes(getinput($ds[ 'nickname' ]));
                $quote = str_replace(
                    array('%nickname%', '%message%'),
                    array($quotenickname, $quotemessage),
                    $plugin_language[ 'quote_link' ]
                );
            }
 
            $contents = $ds[ 'comments' ];
            
            if (isfeedbackadmin($userID) || iscommentposter($userID, $ds[ 'commentID' ])) {
                $edit = '<a class="badge text-bg-warning" href="index.php?site=gallery&action=editcomment&id=' . $ds[ 'commentID' ] . '&amp;ref=' .
                    urlencode($referer) . '" title="' . $plugin_language[ 'edit_comment' ] . '">' . $plugin_language[ 'edit_comment' ] . '</a>';        
            } else {
                $edit = '';
            }
 
            if (isfeedbackadmin($userID)) {
                $actions =
                    '<input class="input" type="checkbox" name="commentID[]" value="' . $ds[ 'commentID' ] . '">';
            } else {
                $actions = '';
            }

            

            $avatar = '<img style="height:45px" class="rounded-circle" src="images/avatars/' . getavatar($ds[ 'userID' ]) .
                    '" class="text-left" alt="Avatar">';
 
             
            $data_array = array();
            $data_array['$avatar'] = $avatar;
            $data_array['$contents'] = $contents;
            $data_array['$edit'] = $edit;
            $data_array['$actions'] = $actions;
            $data_array['$poster'] = $poster;
            $data_array['$date'] = $date;
            
            $template = $GLOBALS["_template"]->loadTemplate("comments","content_area", $data_array, $plugin_path);
            echo $template;
 
            unset(
                $member,
                $quote,
                $email,
                $hp,
                $avatar,
                $pm,
                $ip,
                $edit
            );
 
            if ($sorttype == "DESC") {
                $n--;
            } else {
                $n++;
            }
        }
 
        if (isfeedbackadmin($userID)) {
            $CAPCLASS = new \webspell\Captcha;
                            $CAPCLASS->createTransaction();
                            $hash = $CAPCLASS->getHash();
            $submit = '<input type="hidden" name="referer" value="' . $referer . '">
                    <input class="input" type="checkbox" name="ALL" value="ALL" onclick="SelectAll(this.form);"> ' .
                    $plugin_language[ 'select_all' ] . '
                    <input type="submit" value="' . $plugin_language[ 'delete_selected' ] . '" class="btn btn-danger">';
        } else {
            $submit = '';
        }
 
        $data_array = array();
        $data_array['$page_link'] = $page_link;
        $data_array['$submit'] = $submit;

        $template = $GLOBALS["_template"]->loadTemplate("comments","foot", $data_array, $plugin_path);
        echo $template;
    }
 
    if ($comments_allowed) {
        try {
            $get = mysqli_fetch_assoc(safe_query("SELECT * FROM `".PREFIX."settings_recaptcha`"));
            $webkey = $get['webkey'];
            $seckey = $get['seckey'];
            if ($get['activated']=="1") { $recaptcha=1; } else { $recaptcha=0; }
            } Catch (EXCEPTION $e) {
                $recaptcha=0;
            }

        if ($loggedin) {
            
            $data_array = array();
            $data_array['$userID'] = $userID;
            $data_array['$referer'] = $referer;
            $data_array['$parentID'] = $parentID;
            $data_array['$type'] = $type;
            
            $data_array['$title_comment']=$plugin_language['title_comment'];
            $data_array['$post_comment']=$plugin_language['post_comment'];
            
            $template = $GLOBALS["_template"]->loadTemplate("comments","add_user", $data_array, $plugin_path);
            echo $template;
          
        } elseif ($comments_allowed == 2) {
            if (isset($_COOKIE[ 'visitor_info' ])) {
                $visitor = explode("--||--", $_COOKIE[ 'visitor_info' ]);
                $name = getforminput(stripslashes($visitor[ 0 ]));
                $mail = getforminput(stripslashes($visitor[ 1 ]));
                $homepage = getforminput(stripslashes($visitor[ 2 ]));
            } else {
                $homepage = "http://";
                $name = "";
                $mail = "";
            }
 
            if (isset($_GET[ 'error' ])) {
                $err = $_GET[ 'error' ];
            } else {
                $err = "";
            }
            if ($err == "nickname") {
                $error = $plugin_language[ 'error_nickname' ];
                $name = "";
            } elseif ($err == "captcha") {
                $error = $plugin_language[ 'error_captcha' ];
            } else {
                $error = '';
            }
 
            if (isset($_SESSION[ 'comments_message' ])) {
                $message = getforminput($_SESSION[ 'comments_message' ]);
                unset($_SESSION[ 'comments_message' ]);
            } else {
                $message = "";
            }
 
            $CAPCLASS = new \webspell\Captcha();
            $captcha = $CAPCLASS->createCaptcha();
            $hash = $CAPCLASS->getHash();
            $CAPCLASS->clearOldCaptcha();

            if($recaptcha=="0") { 
                $CAPCLASS = new \webspell\Captcha;
                $captcha = $CAPCLASS->createCaptcha();
                $hash = $CAPCLASS->getHash();
                $CAPCLASS->clearOldCaptcha();
                $_captcha = '
                        <span class="input-group-addon captcha-img">'.$captcha.'</span>
                        <input type="number" name="captcha" class="form-control" id="input-security-code">
                        <input name="captcha_hash" type="hidden" value="'.$hash.'">
                    ';
            } else {
                $_captcha = '
                <div class="g-recaptcha" style="width: 70%; float: left;" data-sitekey="'.$webkey.'"></div>';
            }
 
            $data_array = array();
            $data_array['$name'] = $name;
            $data_array['$mail'] = $mail;
            $data_array['$homepage'] = $homepage;
            $data_array['$message'] = $message;
            $data_array['$_captcha'] = $_captcha;
            $data_array['$hash'] = $hash;
            $data_array['$referer'] = $referer;
            $data_array['$parentID'] = $parentID;
            $data_array['$type'] = $type;
            $data_array['$visitorip'] = $GLOBALS['ip'];

            $data_array['$title_comment']=$plugin_language['title_comment'];
            $data_array['$post_comment']=$plugin_language['post_comment'];
            $data_array['$lang_name']=$plugin_language['name'];
            $data_array['$lang_mail']=$plugin_language['mail'];
            $data_array['$lang_homepage']=$plugin_language['homepage'];
            $data_array['$no_access']=$plugin_language['no_access'];


            $template = $GLOBALS["_template"]->loadTemplate("comments","add_visitor", $data_array, $plugin_path);
            echo $template;
            
        } else {
            echo $plugin_language[ 'no_access' ];
        }
    } else {
        echo $plugin_language[ 'comments_disabled' ];
    }

    }

