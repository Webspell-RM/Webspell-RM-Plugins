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
    $plugin_language = $pm->plugin_language("shoutbox", $plugin_path);


    $data_array = array();
    $data_array['$title']=$plugin_language['shoutbox'];
    $data_array['$subtitle']='Shoutbox';
    $template = $GLOBALS["_template"]->loadTemplate("shoutbox","title", $data_array, $plugin_path);
    echo $template;


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
    $action = "";
}
if ($action == "save_shout") {


    $message = trim($_POST[ 'message' ]);
    $nickname = trim($_POST[ 'nickname' ]);
    $run = 0;
    if ($userID) {
        $run = 1;
        $nickname = $_database->escape_string(getnickname($userID));
    } else {
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
    


       
    }
   if (!empty($nickname) && !empty($message) && $run) {
        $date = time();
        $ip = $GLOBALS[ 'ip' ];
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_shoutbox ORDER BY date DESC LIMIT 0,1");
        $ds = mysqli_fetch_array($ergebnis);
        if (($ds[ 'message' ] != $message) ||
            ($ds[ 'nickname' ] != $nickname)
        ) {
            safe_query(
                "INSERT INTO
                    " . PREFIX . "plugins_shoutbox (
                        `date`,
                        `nickname`,
                        `message`,
                        `ip`
                    )
                VALUES (
                    '$date',
                    '$nickname',
                    '$message',
                    '$ip'
                ) "
            );
        }
    }
    
    #redirect('index.php?site=shoutbox', 'shoutbox', 0);
    header("Location: index.php?site=shoutbox");
    
} elseif ($action == "delete") {
    if (!isfeedbackadmin($userID)) {
        die('No access.');
    }
    if (isset($_POST[ 'shoutID' ])) {
        if (!is_array($_POST[ 'shoutID' ])) {
            $_POST[ 'shoutID' ] = array($_POST[ 'shoutID' ]);
        }
        foreach ($_POST[ 'shoutID' ] as $id) {
            safe_query("DELETE FROM " . PREFIX . "plugins_shoutbox WHERE shoutID='".(int)$id."'");
        }
    }
   // header("Location: index.php?site=shoutbox");
} 

$alle = safe_query("SELECT userID FROM " . PREFIX . "user");
$dx = mysqli_num_rows($alle);
  $tmp = mysqli_fetch_assoc(safe_query("SELECT count(shoutID) as cnt FROM " . PREFIX . "plugins_shoutbox ORDER BY date"));

    $gesamt = $tmp[ 'cnt' ];
    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_shoutbox_settings");
    $ds = mysqli_fetch_array($settings);

    
    $maxshoutboxpost = $ds[ 'max_shoutbox_post' ];
    if (empty($maxshoutboxpost)) {
    $maxshoutboxpost = 10;
    }
    
    $pages = ceil($gesamt / $maxshoutboxpost);
    $max = $maxshoutboxpost;
    if (!isset($_GET[ 'page' ])) {
        $page = 1;
    } else {
        $page = (int)$_GET[ 'page' ];
    }
    $type = 'DESC';
    if (isset($_GET[ 'type' ])) {
        if ($_GET[ 'type' ] == 'ASC') {
            $type = 'ASC';
        }
    }

    if ($pages > 1) {
        $page_link = makepagelink("index.php?site=shoutbox&amp;type=$type", $page, $pages);
    } else {
        $page_link = '';
    }

    if ($page == "1") {
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_shoutbox ORDER BY date $type LIMIT 0,$max");
        if ($type == "DESC") {
            $n = $gesamt;
        } else {
            $n = 1;
        }
    } else {
        $start = $page * $max - $max;
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_shoutbox ORDER BY date $type LIMIT $start,$max");
        if ($type == "DESC") {
            $n = $gesamt - ($page - 1) * $max;
        } else {
            $n = ($page - 1) * $max + 1;
        }
    }

    if ($type == "ASC") {
        $sorter = '<a href="index.php?site=shoutbox&amp;page=' . $page . '&amp;type=DESC">' .
            $plugin_language[ 'sort' ] . '</a> <i class="bi bi-chevron-down"></i>';
    } else {
        $sorter = '<a href="index.php?site=shoutbox&amp;page=' . $page . '&amp;type=ASC">' .
            $plugin_language[ 'sort' ] . '</a> <i class="bi bi-chevron-up"></i>';
    }

    $data_array = array();
    $data_array['$sorter'] = $sorter;
    $data_array['$page_link'] = $page_link;
    $template = $GLOBALS["_template"]->loadTemplate("shoutbox","head", $data_array, $plugin_path);
    echo $template;
    
    $i = 1;
    while ($ds = mysqli_fetch_array($ergebnis)) {
        
        $date = getformatdatetime($ds[ 'date' ]);
        $nickname = $ds[ 'nickname' ];
        $message = $ds[ 'message' ];
        $ip = 'logged';

        if (isfeedbackadmin($userID)) {
            $actions = '<input class="input" type="checkbox" name="shoutID[]" value="' . $ds[ 'shoutID' ] . '">';
            $ip = $ds[ 'ip' ];
        } else {
            $actions = '';
        }
        
        GLOBAL $getavatar,$userID;
        $savatar = getuserid($nickname);

        if ($getavatar = getavatar($savatar)) {
            $avatar = '<img style="height:30px;width:30px" class="img-fluid rounded-circle" src="./images/avatars/' . $getavatar . '" >';
        } else {
            $avatar = '';
        }

        $data_array = array();
        $data_array['$actions'] = $actions;
        $data_array['$n'] = $n;
        $data_array['$nickname'] = $nickname;
        $data_array['$avatar'] = $avatar;
        $data_array['$date'] = $date;
        $data_array['$ip'] = $ip;
        $data_array['$message'] = $message;
        $template = $GLOBALS["_template"]->loadTemplate("shoutbox","content", $data_array, $plugin_path);
        echo $template;
       
        if ($type == "DESC") {
            $n--;
        } else {
            $n++;
        }
        $i++;
    }
 
  

    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(shoutID) as cnt FROM " . PREFIX . "plugins_shoutbox ORDER BY date"));
     $gesamt = $tmp[ 'cnt' ];
    
    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_shoutbox_settings");
    $ds = mysqli_fetch_array($settings);
    
    $maxshoutboxpost = $ds[ 'max_shoutbox_post' ];
    if (empty($maxshoutboxpost)) {
    $maxshoutboxpost = 10;
    }

    $pages = ceil($gesamt / $maxshoutboxpost);
    $max = $maxshoutboxpost;
    if (!isset($_GET[ 'page' ])) {
        $page = 1;
    } else {
        $page = (int)$_GET[ 'page' ];
    }
    $type = 'DESC';
    if (isset($_GET[ 'type' ])) {
        if ($_GET[ 'type' ] == 'ASC') {
            $type = 'ASC';
        }
    }

    if ($pages > 1) {
        $page_link = makepagelink("index.php?site=shoutbox&amp;type=$type", $page, $pages);
    } else {
        $page_link = '';
    }

    if ($page == "1") {
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_shoutbox ORDER BY date $type LIMIT 0,$max");
        if ($type == "DESC") {
            $n = $gesamt;
        } else {
            $n = 1;
        }
    } else {
        $start = $page * $max - $max;
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_shoutbox ORDER BY date $type LIMIT $start,$max");
        if ($type == "DESC") {
            $n = $gesamt - ($page - 1) * $max;
        } else {
            $n = ($page - 1) * $max + 1;
        }
    }

    if ($type == "ASC") {
        $sorter = '<a href="index.php?site=shoutbox&amp;page=' . $page . '&amp;type=DESC">' .
            $plugin_language[ 'sort' ] . '</a> <i class="bi bi-chevron-down"></i>';
    } else {
        $sorter = '<a href="index.php?site=shoutbox&amp;page=' . $page . '&amp;type=ASC">' .
            $plugin_language[ 'sort' ] . '</a> <i class="bi bi-chevron-up"></i>';
    }

    
    if (isfeedbackadmin($userID)) {
        $submit = '<input class="input" type="checkbox" name="ALL" value="ALL" onclick="SelectAll(this.form);"> ' .
            $plugin_language[ 'select_all' ] . '
                <input type="submit" value="' . $plugin_language[ 'delete_selected' ] . '" class="btn btn-danger">';
    } else {
        $submit = '';
    }
    echo '<br><div class="row">
            <div class="col-md-6">' . $page_link . '</div>
            <div class="col-md-6 text-end">' . $submit . '</div>
        </div>
        </form>';
    $template = $GLOBALS["_template"]->loadTemplate("shoutbox","foot", $data_array, $plugin_path);
    echo $template;




try {
    $get = mysqli_fetch_assoc(safe_query("SELECT * FROM `".PREFIX."settings_recaptcha`"));
    $webkey = $get['webkey'];
    $seckey = $get['seckey'];
    if ($get['activated']=="1") { $recaptcha=1; } else { $recaptcha=0; }
} Catch (EXCEPTION $e) {
    $recaptcha=0;
}

if ($loggedin) {
        
    if ($userID) {
      
    $name_settings = 'value="' . getinput(getnickname($userID)) . '" readonly="readonly" ';
    $captcha_form = '';
} else {}

$CAPCLASS = new \webspell\Captcha;
        $captcha = $CAPCLASS->createCaptcha();
        $hash = $CAPCLASS->getHash();
        $CAPCLASS->clearOldCaptcha();

        if (!isset($showerror)) {
            $showerror = '';
        }
        if (isset($_POST['messenge'])) {
            $messenge = getforminput($_POST['messenge']);
        } else {
            $messenge = '';
        }
        
        $data_array = array();
        $data_array['$name_settings'] = $name_settings;

        $data_array['$shoutbox'] = $plugin_language[ 'shoutbox' ];
        $data_array['$shout'] = $plugin_language[ 'shout' ];
        $data_array['$all_messages'] = $plugin_language[ 'all_messages' ];
        $data_array['$enter_message'] = $plugin_language[ 'enter_message' ];
        
        $template = $GLOBALS["_template"]->loadTemplate("shoutbox","loggedin", $data_array, $plugin_path);
        echo $template;
        
    } else {

        # keine post bei nicht reg. user
        if(!empty(@$ds['displayed'] == 1) !== false) {
        # keine post bei nicht reg. user

        $CAPCLASS = new \webspell\Captcha;
        $captcha = $CAPCLASS->createCaptcha();
        $hash = $CAPCLASS->getHash();
        $CAPCLASS->clearOldCaptcha();

        if (!isset($showerror)) {
            $showerror = '';
        }
        if (isset($_POST['name_settings'])) {
            $name_settings = getforminput($_POST['name_settings']);
        } else {
            $name_settings = '';
        }
        if (isset($_POST['messenge'])) {
            $messenge = getforminput($_POST['messenge']);
        } else {
            $messenge = '';
        }
        

        if($recaptcha=="0") { 
                $CAPCLASS = new \webspell\Captcha;
                $captcha = $CAPCLASS->createCaptcha();
                $hash = $CAPCLASS->getHash();
                $CAPCLASS->clearOldCaptcha();
                $_captcha = '<span class="input-group-addon captcha-img">'.$captcha.'</span>
                        <input type="number" name="captcha" class="form-control" id="input-security-code">
                        <input name="captcha_hash" type="hidden" value="'.$hash.'">';
            } else {
                $_captcha = '
                <div class="g-recaptcha" style="width: 70%; float: left;" data-sitekey="'.$webkey.'"></div>';
        }

        $data_array = array();
        $data_array['$name_settings'] = $name_settings;
        $data_array['$_captcha'] = $_captcha;

        $data_array['$shoutbox'] = $plugin_language[ 'shoutbox' ];
        $data_array['$shout'] = $plugin_language[ 'shout' ];
        $data_array['$all_messages'] = $plugin_language[ 'all_messages' ];
        $data_array['$enter_name'] = $plugin_language[ 'enter_name' ];
        $data_array['$enter_message'] = $plugin_language[ 'enter_message' ];
        $data_array['$all_message'] = $plugin_language[ 'all_message' ];
        
        $template = $GLOBALS["_template"]->loadTemplate("shoutbox","notloggedin", $data_array, $plugin_path);
        echo $template;

        # keine post bei nicht reg. user
        }else{ 
        $data_array = array();
        $data_array['$not_logged'] = $plugin_language[ 'not_logged' ];   
        $template = $GLOBALS["_template"]->loadTemplate("shoutbox","sc_content_no", $data_array, $plugin_path);
        echo $template;
        }
        # keine post bei nicht reg. user
        
    }
       