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
    $plugin_language = $pm->plugin_language("joinus", $plugin_path);


try {
    $get = mysqli_fetch_assoc(safe_query("SELECT * FROM `".PREFIX."settings_recaptcha`"));
    $webkey = $get['webkey'];
    $seckey = $get['seckey'];
    if ($get['activated']=="1") { $recaptcha=1; } else { $recaptcha=0; }
} Catch (EXCEPTION $e) {
    $recaptcha=0;
}

//options

    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_joinus");
    $dn = mysqli_fetch_array($settings);


    if ($dn[ 'show' ] == '1') {
        $show = true;
    } else {
        $show = false;
    }

    $showonlygamingsquads = $show;  //only show gaming squads (=true) or show all squads (=false)?

    

//php below this line ;)

if (isset($site)) {
    $_language->readModule('joinus');
}
    $data_array = array();
    $data_array['$title']=$plugin_language[ 'title_join_us' ];
    $data_array['$subtitle']='Join us';

    $template = $GLOBALS["_template"]->loadTemplate("joinus","title", $data_array, $plugin_path);
    echo $template;



if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = "";
}
$show = true;
if ($action == "save" && isset($_POST['post'])) {
    if (isset($_POST['squad'])) {
        $squad = intval($_POST['squad']);
    } else {
        $squad = 0;
    }
    $nick = $_POST['nick'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $messenger = $_POST['messenger'];
    $age = $_POST['age'];
    $city = $_POST['city'];
    $clanhistory = $_POST['clanhistory'];
    $info = $_POST['info'];
    $run = 0;

    $error = array();
    if (!(mb_strlen(trim($nick)))) {
        $error[] = $plugin_language['forgot_nickname'];
    }
    if (!(mb_strlen(trim($name)))) {
        $error[] = $plugin_language['forgot_realname'];
    }
    if (!validate_email($email)) {
        $error[] = $plugin_language['email_not_valid'];
    }
    if (!(mb_strlen(trim($messenger)))) {
        $error[] = $plugin_language['forgot_messenger'];
    }
    if (!(mb_strlen(trim($age)))) {
        $error[] = $plugin_language['forgot_age'];
    }
    if (!(mb_strlen(trim($city)))) {
        $error[] = $plugin_language['forgot_city'];
    }

    if ($userID) {
        $run = 1;
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

    if (!count($error) && $run) {
        $touser = array();
        $ergebnis =
            safe_query(
                "SELECT
                    userID
                FROM
                    " . PREFIX . "plugins_squads_members
                WHERE
                    joinmember='1'
                AND
                    squadID='" . $squad . "'"
            );
    
        while ($ds = mysqli_fetch_assoc($ergebnis)) {
            $touser[] = $ds['userID'];
        }
        if (!count($touser)) {
            $touser[] = 1;
        }
        
        foreach ($touser as $id) {
            
            $message = '<b>' . $plugin_language['someone_want_to_join_your_squad'] . ' ' .
                $_database->escape_string(getsquadname($squad)) . '!</b><br><br>
                ' . $plugin_language['nick'] . ' ' . $nick . '<br>
                ' . $plugin_language['name'] . ': ' . $name . '<br>
                ' . $plugin_language['age'] . ': ' . $age . '<br>
                ' . $plugin_language['mail'] . ': <a href="mailto:' . $email . '">' . $email . '</a><br>
                ' . $plugin_language['messenger'] . ': ' . $messenger . '<br>
                ' . $plugin_language['city'] . ': ' . $city . '<br>
                ' . $plugin_language['clan_history'] . ': ' . $clanhistory . '<br>
                ' . $plugin_language['info'] . ': ' . $info .'';

            sendmessage($id, $plugin_language['message_title'], $message);
        }
        echo generateAlert($plugin_language['thanks_you_will_get_mail'], 'alert-success');
        redirect("index.php?site=joinus", "", 3);
        unset($_POST['nick'],
            $_POST['name'],
            $_POST['email'],
            $_POST['messenger'],
            $_POST['age'],
            $_POST['city'],
            $_POST['clanhistory'],
            $_POST['info']);
        $show = false;
    } else {
        $show = true;
        $showerror = generateErrorBoxFromArray($plugin_language['problems'], $error);
    }
}
if ($show === true) {
    if ($showonlygamingsquads) {
        $squads = getgamesquads();
    } else {
        $squads = getsquads();
    }
    if(!$showonlygamingsquads) {
        $_r2 = mysqli_num_rows(safe_query("SELECT * FROM `" . PREFIX . "plugins_squads` WHERE `gamesquad` = 0"));
        $_r1 = $_r2 + mysqli_num_rows(safe_query("SELECT * FROM `" . PREFIX . "plugins_squads` WHERE `gamesquad` = 1"));
     } else {
        $_r1 = mysqli_num_rows(safe_query("SELECT * FROM `" . PREFIX . "plugins_squads` WHERE `gamesquad` = 1"));
     }
    if($_r1<1) {
        $data_array = array();
        $data_array['$showerror'] = generateErrorBoxFromArray($plugin_language['squad'], array($plugin_language['no_squads_found']));
        
        $data_array['$lang_joinus_form']=$plugin_language['joinus_form'];

        $template = $GLOBALS["_template"]->loadTemplate("joinus","failure", $data_array, $plugin_path);
        echo $template;
        
    return false;
    } 
    

    if ($loggedin) {
        if (!isset($showerror)) {
            $showerror = '';
        }


        $res = safe_query(
            "SELECT
                *, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(birthday)), '%y') 'age'
            FROM
                " . PREFIX . "user
            WHERE
                userID = '$userID'"
        );
        
        $ds = mysqli_fetch_assoc($res);
        $nickname = getinput($ds['nickname']);
        $name = getinput($ds['firstname'] . " " . $ds['lastname']);
        $email = getinput($ds['email']);
        $messenger = getinput($ds['discord']);
        $age = $ds['age'];
        $city = getinput($ds['town']);

        if (isset($_POST['clanhistory'])) {
            $clanhistory = getforminput($_POST['clanhistory']);
        } else {
            $clanhistory = '';
        }
        if (isset($_POST['info'])) {
            $info = getforminput($_POST['info']);
        } else {
            $info = '';
        }

        $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_joinus");
        $dn = mysqli_fetch_array($settings);

        if ($dn[ 'terms_of_use' ] == '1') {
        $terms_of_use = '';
        $termsofuse = 'return accept()';
        } else {
        $termsofuse = 'return !!(accept() & terms_use())';
        $terms_of_use = '<br><br>
            <input type="checkbox" class="form-check-input" id="accept_terms_of_use" value="0" required> <label class="form-check-label" for="invalidCheck">'.$plugin_language[ 'terms_of_use' ].'</label><br />
                <a class="btn btn-primary btn-sm" href="index.php?site=clan_rules" target="_blank">'.$plugin_language[ 'clan_rules' ].'</a><br />';
        }

                
        $data_array = array();
        $data_array['$showerror'] = $showerror;
        $data_array['$squads'] = $squads;
        $data_array['$nickname'] = $nickname;
        $data_array['$name'] = $name;
        $data_array['$email'] = $email;
        $data_array['$messenger'] = $messenger;
        $data_array['$age'] = $age;
        $data_array['$city'] = $city;
        $data_array['$clanhistory'] = $clanhistory;
        $data_array['$info'] = $info;
        $data_array['$termsofuse'] = $termsofuse;
        $data_array['$terms_of_use'] = $terms_of_use;

        $data_array['$lang_joinus_form']=$plugin_language['joinus_form'];
        $data_array['$lang_squad']=$plugin_language['squad'];
        $data_array['$lang_nick']=$plugin_language['nick'];
        $data_array['$lang_name']=$plugin_language['name'];
        $data_array['$lang_mail']=$plugin_language['mail'];
        $data_array['$lang_discord']=$plugin_language['discord'];
        $data_array['$lang_age']=$plugin_language['age'];
        $data_array['$lang_city']=$plugin_language['city'];
        $data_array['$lang_clan_history']=$plugin_language['clan_history'];
        $data_array['$lang_additional_info']=$plugin_language['additional_info'];
        $data_array['$lang_GDPRinfo']=$plugin_language['GDPRinfo'];
        $data_array['$lang_GDPRaccept']=$plugin_language['GDPRaccept'];
        $data_array['$lang_send']=$plugin_language['send'];

        $data_array['$lang_accept_terms_of_use']=$plugin_language['accept_terms_of_use'];

        $template = $GLOBALS["_template"]->loadTemplate("joinus","loggedin", $data_array, $plugin_path);
        echo $template;
        
    } else {
        $CAPCLASS = new \webspell\Captcha;
        $captcha = $CAPCLASS->createCaptcha();
        $hash = $CAPCLASS->getHash();
        $CAPCLASS->clearOldCaptcha();

        if (!isset($showerror)) {
            $showerror = '';
        }
        if (isset($_POST['nick'])) {
            $nick = getforminput($_POST['nick']);
        } else {
            $nick = '';
        }
        if (isset($_POST['name'])) {
            $name = getforminput($_POST['name']);
        } else {
            $name = '';
        }
        if (isset($_POST['email'])) {
            $email = getforminput($_POST['email']);
        } else {
            $email = '';
        }
        if (isset($_POST['messenger'])) {
            $messenger = getforminput($_POST['messenger']);
        } else {
            $messenger = '';
        }
        if (isset($_POST['age'])) {
            $age = getforminput($_POST['age']);
        } else {
            $age = '';
        }
        if (isset($_POST['city'])) {
            $city = getforminput($_POST['city']);
        } else {
            $city = '';
        }
        if (isset($_POST['clanhistory'])) {
            $clanhistory = getforminput($_POST['clanhistory']);
        } else {
            $clanhistory = '';
        }
        if (isset($_POST['info'])) {
            $info = getforminput($_POST['info']);
        } else {
            $info = '';
        }


        $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_joinus");
        $dn = mysqli_fetch_array($settings);

        if ($dn[ 'terms_of_use' ] == '1') {
        $terms_of_use = '';
        $termsofuse = 'return accept()';
        } else {
        $termsofuse = 'return !!(accept() & terms_use())';
        $terms_of_use = '<br><br>
            <input type="checkbox" class="form-check-input" id="accept_terms_of_use" value="0" required> <label class="form-check-label" for="invalidCheck">'.$plugin_language[ 'terms_of_use' ].'</label><br />
                <a class="btn btn-primary btn-sm" href="index.php?site=clan_rules" target="_blank">'.$plugin_language[ 'clan_rules' ].'</a><br />';
        }


        if($recaptcha=="0") { 
                $CAPCLASS = new \webspell\Captcha;
                $captcha = $CAPCLASS->createCaptcha();
                $hash = $CAPCLASS->getHash();
                $CAPCLASS->clearOldCaptcha();
                $_captcha = '<span class="input-group-addon captcha-img">'.$captcha.'</span>
                        <input type="number" name="captcha" class="form-control" id="input-security-code" required>
                        <input name="captcha_hash" type="hidden" value="'.$hash.'">';
            } else {
                $_captcha = '
                <div class="g-recaptcha" style="width: 70%; float: left;" data-sitekey="'.$webkey.'"></div>';
            }

        $data_array = array();
        $data_array['$showerror'] = $showerror;
        $data_array['$squads'] = $squads;
        $data_array['$info'] = $info;
        $data_array['$_captcha'] = $_captcha;
        $data_array['$hash'] = $hash;
        $data_array['$termsofuse'] = $termsofuse;
        $data_array['$terms_of_use'] = $terms_of_use;

        $data_array['$lang_joinus_form']=$plugin_language['joinus_form'];
        $data_array['$lang_squad']=$plugin_language['squad'];
        $data_array['$lang_nick']=$plugin_language['nick'];
        $data_array['$lang_name']=$plugin_language['name'];
        $data_array['$lang_mail']=$plugin_language['mail'];
        $data_array['$lang_discord']=$plugin_language['discord'];
        $data_array['$lang_age']=$plugin_language['age'];
        $data_array['$lang_city']=$plugin_language['city'];
        $data_array['$lang_clan_history']=$plugin_language['clan_history'];
        $data_array['$lang_additional_info']=$plugin_language['additional_info'];
        $data_array['$lang_GDPRinfo']=$plugin_language['GDPRinfo'];
        $data_array['$lang_GDPRaccept']=$plugin_language['GDPRaccept'];
        $data_array['$lang_send']=$plugin_language['send'];
        $data_array['$lang_security_code']=$plugin_language['security_code'];

        $template = $GLOBALS["_template"]->loadTemplate("joinus","notloggedin", $data_array, $plugin_path);
        echo $template;
        
    }
}