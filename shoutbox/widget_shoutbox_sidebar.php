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

global $userID;
global $loggedin;
global $_database;
###############
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
    
    header('Location:'.$_SERVER['HTTP_REFERER']);
    
}
###########
try {
    $get = mysqli_fetch_assoc(safe_query("SELECT * FROM `".PREFIX."settings_recaptcha`"));
    $webkey = $get['webkey'];
    $seckey = $get['seckey'];
    if ($get['activated']=="1") { $recaptcha=1; } else { $recaptcha=0; }
} Catch (EXCEPTION $e) {
    $recaptcha=0;
}

        $template = $GLOBALS["_template"]->loadTemplate("shoutbox","sc_content_head", array(), $plugin_path);
        echo $template;

    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_shoutbox_settings");
    $ds = mysqli_fetch_array($settings);
    
    $maxshoutboxpost = $ds[ 'max_shoutbox_post' ];
    if (empty($maxshoutboxpost)) {
    $maxshoutboxpost = 10;
    }


$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_shoutbox ORDER BY date DESC LIMIT 0," . $maxshoutboxpost);

    while ($dx = mysqli_fetch_array($ergebnis)) {

        $date = getformatdate($dx[ 'date' ]);
        $time = getformattime($dx[ 'date' ]);
        $nickname = $dx[ 'nickname' ];
		#$avatar = $dx[ 'avatar' ];
        $message = htmlspecialchars($dx[ 'message' ]);
        $message = str_replace("&amp;amp;", "&", $message);

        $savatar = getuserid($nickname);

        if ($getavatar = getavatar($savatar)) {
            $avatar = '<img style="height:30px;width:30px" class="img-fluid rounded-circle" src="./images/avatars/' . $getavatar . '" >';
        } else {
            $avatar = '';
        }

        
        $data_array = array();
        $data_array['$avatar'] = $avatar;
        $data_array['$nickname'] = $nickname;
        $data_array['$date'] = $date;
        $data_array['$time'] = $time;
        $data_array['$message'] = $message;

        $template = $GLOBALS["_template"]->loadTemplate("shoutbox","sc_content", $data_array, $plugin_path);
        echo $template;

    }



        $template = $GLOBALS["_template"]->loadTemplate("shoutbox","sc_content_foot", array(), $plugin_path);
        echo $template;    


if ($loggedin) {
        
        if ($userID) {      
            $name_settings = 'value="' . getinput(getnickname($userID)) . '" readonly="readonly" ';
            $captcha_form = '';
        } else {
            $name_settings = '';
            $captcha_form = '';
        }

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
        
        #$refresh = $sbrefresh * 1000;

        $data_array = array();
        #$data_array['$refresh'] = $refresh;
        $data_array['$name_settings'] = $name_settings;

        $data_array['$shoutbox'] = $plugin_language[ 'shoutbox' ];
        $data_array['$shout'] = $plugin_language[ 'shout' ];
        $data_array['$all_messages'] = $plugin_language[ 'all_messages' ];
        $data_array['$enter_message'] = $plugin_language[ 'enter_message' ];
        $data_array['$all_message'] = $plugin_language[ 'all_message' ];
        
        $template = $GLOBALS["_template"]->loadTemplate("shoutbox","sc_loggedin", $data_array, $plugin_path);
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
        

        #$refresh = $sbrefresh * 1000;
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
                <div class="g-recaptcha" data-size="compact" style="width: 70%; float: left;" data-sitekey="'.$webkey.'"></div>';
        }

        $data_array = array();
        #$data_array['$refresh'] = $refresh;
        $data_array['$name_settings'] = $name_settings;
        $data_array['$_captcha'] = $_captcha;

        $data_array['$shoutbox'] = $plugin_language[ 'shoutbox' ];
        $data_array['$shout'] = $plugin_language[ 'shout' ];
        $data_array['$all_messages'] = $plugin_language[ 'all_messages' ];
        $data_array['$enter_name'] = $plugin_language[ 'enter_name' ];
        $data_array['$enter_message'] = $plugin_language[ 'enter_message' ];
        
        $template = $GLOBALS["_template"]->loadTemplate("shoutbox","sc_notloggedin", $data_array, $plugin_path);
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
       