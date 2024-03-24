<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*  
 *                                    Webspell-RM      /                        /   /                                                 *
 *                                    -----------__---/__---__------__----__---/---/-----__---- _  _ -                                *
 *                                     | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                                 *
 *                                    _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                                 *
 *                                                 Free Content / Management System                                                   *
 *                                                             /                                                                      *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         Webspell-RM                                                                                                       *
 *                                                                                                                                    *
 * @copyright       2018-2022 by webspell-rm.de <https://www.webspell-rm.de>                                                          *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de <https://www.webspell-rm.de/forum.html>  *
 * @WIKI            webspell-rm.de <https://www.webspell-rm.de/wiki.html>                                                             *
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                                                  *
 *                  It's NOT allowed to remove this copyright-tag <http://www.fsf.org/licensing/licenses/gpl.html>                    *
 *                                                                                                                                    *
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                                                 *
 * @copyright       2005-2018 by webspell.org / webspell.info                                                                         *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 */

# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("register", $plugin_path);

echo'
<div id="modal-register" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">        
                <h4 class="modal-title">Login </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
      ';

// by ZENITH-Developments.de # read database entries (?)
$_admin_minpasslen = "6";
$_admin_maxpasslen = ""; #empty = no max
$_admin_musthavelow = true;
$_admin_musthaveupp = true;
$_admin_musthavenum = true;
$_admin_musthavespec = true;
function pass_complex($pwd,$_admin_minpasslen,$_admin_maxpasslen,$_admin_musthavelow,$_admin_musthaveupp,$_admin_musthavenum,$_admin_musthavespec) {
    if ($_admin_musthavelow==true) { $_pwd_low = "(?=\S*[a-z])"; } else { $_pwd_low=""; }
    if ($_admin_musthaveupp==true) { $_pwd_upp = "(?=\S*[A-Z])"; } else { $_pwd_upp=""; }
    if ($_admin_musthavenum==true) { $_pwd_num = "(?=\S*[\d])"; } else { $_pwd_num=""; }
    if ($_admin_musthavespec==true) { $_pwd_spec = "(?=\S*[\W])"; } else { $_pwd_spec=""; }
    if (!preg_match_all('$\S*(?=\S{'.$_admin_minpasslen.','.$_admin_maxpasslen.'})'.$_pwd_low.$_pwd_upp.$_pwd_num.$_pwd_spec.'\S*$', $pwd)) {
        return false;
    }
    return true;
}

try {
    $get = mysqli_fetch_assoc(safe_query("SELECT * FROM `".PREFIX."settings_recaptcha`"));
    $webkey = $get['webkey'];
    $seckey = $get['seckey'];
    if ($get['activated']=="1") { $recaptcha=1; } else { $recaptcha=0; }
} Catch (EXCEPTION $e) {
    $recaptcha=0;
}

#$_language->readModule('register');


    #$data_array = array();
    #$data_array['$title'] = $plugin_language[ 'title' ];
    #$template = $tpl->loadTemplate("register","head", $data_array);
    #echo $template;
    #$template = $GLOBALS["_template"]->loadTemplate("register","head", $data_array, $plugin_path);
    #echo $template;

$show = true;
if (isset($_POST['save'])) {
    if (!$loggedin) {
        $nickname = htmlspecialchars(mb_substr(trim($_POST['nickname']), 0, 30));
        if (strpos($nickname, "'") !== false) {
            $nickname = "";     // contains a ' char the nickname will reset (handle as not entered)
        }
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $gender = $_POST['gender'];
        $birthday = $_POST['birthday'];
        $homepage = $_POST['homepage'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];

        $mail = $_POST['mail'];
        $CAPCLASS = new \webspell\Captcha;

        $error = array();
        
        // check nickname
        if (!(mb_strlen(trim($nickname)))) {
            $error[] = $plugin_language['enter_nickname'];
        }

        // check nickname inuse
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "user WHERE nickname = '$nickname' ");
        $num = mysqli_num_rows($ergebnis);
        if ($num) {
            $error[] = $plugin_language['nickname_inuse'];
        }

        // prüfung passwort
        
        if($password == $password2) {
            if(!(strlen(trim($password)))) 
                $error[] = $plugin_language['enter_password'];
            } 
            else $error[] = $plugin_language['repeat_invalid'];

        // check passwort
        if (pass_complex($password,$_admin_minpasslen,$_admin_maxpasslen,$_admin_musthavelow,$_admin_musthaveupp,$_admin_musthavenum,$_admin_musthavespec)==false) {
            $error[] = $plugin_language['enter_password2'];
        }

        // check e-mail
        if (!validate_email($mail)) {
            $error[] = $plugin_language['invalid_mail'];
        }

        // check e-mail inuse
        $ergebnis = safe_query("SELECT userID FROM " . PREFIX . "user WHERE email = '$mail' ");
        $num = mysqli_num_rows($ergebnis);
        if ($num) {
            $error[] = $plugin_language['mail_inuse'];
        }

        // check homepage
        /*if (!(mb_strlen(trim($homepage)))) {
            $error[] = $plugin_language['enter_homepage'];
        }*/

        // check captcha
        if($recaptcha=="0") { 
            if (!$CAPCLASS->checkCaptcha($_POST['captcha'], $_POST['captcha_hash'])) {
                $error[] = "Securitycode Error";
            } else { 
                $runregister = "false"; 
            }
        } else {
      
            $msg='';
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
                    $runregister = "true";
                    //reCaptcha success check 
                    if(!$res['success']) {
                        $error[] = "reCAPTCHA Error";
                        $runregister = "false";
                    }
                } else {
                    $error[] = "reCAPTCHA Error";
                    $runregister = "false";
                }
            }
        }
        
        // check exisitings accounts from ip with same password
        if(!$register_per_ip) {
            $get_users =
                safe_query(
                    "SELECT
                        userID
                    FROM
                        " . PREFIX . "user
                    WHERE
                        ip='" . $GLOBALS['ip'] . "'"
                );
            if (mysqli_num_rows($get_users)) {
                $error[] = 'Only one Account per IP';
            }
        }

        if (count($error)) {
            $_language->readModule('formvalidation', true);
            $showerror = generateErrorBoxFromArray($plugin_language['errors_there'], $error);
        } else {
            // insert in db
            $registerdate = time();
            $activationkey = md5(RandPass(20));
            $activationlink = getCurrentUrl() . '&key=' . $activationkey;
            $newnickname = htmlspecialchars(mb_substr(trim($_POST[ 'nickname' ]), 0, 30));
        $anz = mysqli_num_rows(safe_query(
            "SELECT userID FROM " . PREFIX . "user WHERE (nickname='" . $newnickname . "') "
        ));
            safe_query(
                "INSERT INTO
                    `" . PREFIX . "user` (
                        `registerdate`,
                        `lastlogin`,
                        `nickname`,
                        `email`,
                        `firstname`,
                        `lastname`,
                        `gender`,
                        `birthday`,
                        `homepage`,
                        `activated`,
                        `ip`,
                        `date_format`,
                        `time_format`
                    )
                    VALUES (
                        '$registerdate',
                        '$registerdate',
                        '$newnickname',
                        '$mail',
                        '$firstname',
                        '$lastname',
                        '$gender',
                        '$birthday',
                        '$homepage',
                        '" . $activationkey . "',
                        '" . $GLOBALS['ip'] . "',
                        '" . $default_format_date . "',
                        '" . $default_format_time . "'
                    )"
            );
            safe_query("
              INSERT INTO " . PREFIX . "user_nickname ( userID,nickname ) values ('" . mysqli_insert_id($_database) ."','" . $newnickname ."')
            ");

            $insertid = mysqli_insert_id($_database);
            
            // insert Password
            $pass = Gen_PasswordHash(stripslashes($password), $insertid);
            safe_query("UPDATE `".PREFIX."user` SET `password_hash` = '".$pass."' WHERE `userID` = '".intval($insertid)."'");

            // insert in user_groups
            safe_query("INSERT INTO " . PREFIX . "user_groups ( userID ) values('$insertid' )");

            // mail to user
            $ToEmail = $mail;
            $header = str_replace(
                array('%nickname%', '%activationlink%', '%pagetitle%', '%homepage_url%'),
                array(stripslashes($nickname), stripslashes($activationlink), $hp_title, $hp_url),
                $plugin_language['mail_subject']
            );
            $Message = str_replace(
                array('%nickname%', '%activationlink%', '%pagetitle%', '%homepage_url%'),
                array(stripslashes($nickname), stripslashes($activationlink), $hp_title, $hp_url),
                $plugin_language['mail_text']
            );
            $sendmail = \webspell\Email::sendEmail($admin_email, 'Register', $ToEmail, $header, $Message);

            if ($sendmail['result'] == 'fail') {
                if (isset($sendmail['debug'])) {
                    $fehler = array();
                    $fehler[] = $sendmail[ 'error' ];
                    $fehler[] = $sendmail[ 'debug' ];
                    redirect(
                        "index.php",
                        generateErrorBoxFromArray($plugin_language['mail_failed'], $fehler),
                        10
                    );
                    $show = false;
                } else {
                    $fehler = array();
                    $fehler[] = $sendmail['error'];
                    redirect(
                        "index.php",
                        generateErrorBoxFromArray($plugin_language['mail_failed'], $fehler),
                        10
                    );
                    $show = false;
                }
            } else {
                if (isset($sendmail['debug'])) {
                    $fehler = array();
                    $fehler[] = $sendmail[ 'debug' ];
                    redirect(
                        "index.php",
                        generateBoxFromArray($plugin_language['register_successful'], 'alert-success', $fehler),
                        10
                    );
                    $show = false;
                } else {
                    redirect("index.php", $plugin_language['register_successful'], 3);
                    $show = false;
                }
            }
        }
    } else {
        redirect(
            "index.php?site=register",
            str_replace('%pagename%', $GLOBALS['hp_title'], $plugin_language['no_register_when_loggedin']),
            3
        );
    }
}
if (isset($_GET['key'])) {
    safe_query("UPDATE `" . PREFIX . "user` SET activated='1' WHERE activated='" . $_GET['key'] . "'");
    if (mysqli_affected_rows($_database)) {
        redirect('index.php?site=login', $plugin_language['activation_successful'], 3);
    } else {
        redirect('index.php?site=login', $plugin_language['wrong_activationkey'], 3);
    }
} elseif (isset($_GET['mailkey'])) {
    if (mb_strlen(trim($_GET['mailkey'])) == 32) {
        safe_query(
            "UPDATE
                `" . PREFIX . "user`
            SET
                email_activate='1',
                email=email_change,
                email_change=''
            WHERE
                email_activate='" . $_GET['mailkey'] . "'"
        );
        if (mysqli_affected_rows($_database)) {
            redirect('index.php?site=login', $plugin_language['mail_activation_successful'], 3);
        } else {
            redirect('index.php?site=login', $plugin_language['wrong_activationkey'], 3);
        }
    }
} else {
    if ($show === true) {
        if (!$loggedin)  {
            if(isset($_COOKIE['ws_session'])) {
            
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
                    $_captcha = '<div class="g-recaptcha" style="width: 70%; float: left;" data-sitekey="'.$webkey.'"></div>';
                }
                if (!isset($showerror)) {
                    $showerror = '';
                }
                if (isset($_POST['nickname'])) {
                    $nickname = getforminput($_POST['nickname']);
                } else {
                    $nickname = '';
                }
                if (isset($_POST['password'])) {
                    $password = getforminput($_POST['password']);
                } else {
                    $password = '';
                }
                if (isset($_POST['mail'])) {
                    $mail = getforminput($_POST['mail']);
                } else {
                    $mail = '';
                }
				if (isset($_POST['firstname'])) {
                    $firstname = getforminput($_POST['firstname']);
                } else {
                    $firstname = '';
                }
				if (isset($_POST['firstname'])) {
                    $lastname = getforminput($_POST['lastname']);
                } else {
                    $lastname = '';
                }

                $gender = '
                    <option value="select_gender">' . $plugin_language['select_gender'] . '</option>
                    <option value="male">' . $plugin_language['male'] . '</option>
                    <option value="female">' . $plugin_language['female'] . '</option>
                    <option value="diverse">' . $plugin_language['diverse'] . '</option>';

                if (isset($_POST['homepage'])) {
                    $homepage = getforminput($_POST['homepage']);
                } else {
                    $homepage = '';
                }

                /*$format_date = "<option value='d.m.y'>DD.MM.YY</option>
                    <option value='d.m.Y'>DD.MM.YYYY</option>
                    <option value='j.n.y'>D.M.YY</option>
                    <option value='j.n.Y'>D.M.YYYY</option>
                    <option value='y-m-d'>YY-MM-DD</option>
                    <option value='Y-m-d'>YYYY-MM-DD</option>
                    <option value='y/m/d'>YY/MM/DD</option>
                    <option value='Y/m/d'>YYYY/MM/DD</option>";
                $format_date = str_replace(
                    "value='" . $ds['date_format'] . "'",
                    "value='" . $ds['date_format'] . "' selected='selected'",
                    $format_date
                );

                $format_time = "<option value='G:i'>H:MM</option>
                    <option value='H:i'>HH:MM</option>
                    <option value='G:i a'>H:MM am/pm</option>
                    <option value='H:i a'>HH:MM am/pm</option>
                    <option value='G:i A'>H:MM AM/PM</option>
                    <option value='H:i A'>HH:MM AM/PM</option>
                    <option value='G:i:s'>H:MM:SS</option>
                    <option value='H:i:s'>HH:MM:SS</option>
                    <option value='G:i:s a'>H:MM:SS am/pm</option>
                    <option value='H:i:s a'>HH:MM:SS am/pm</option>
                    <option value='G:i:s A'>H:MM:SS AM/PM</option>
                    <option value='H:i:s A'>HH:MM:SS AM/PM</option>";
                $format_time = str_replace(
                    "value='" . $ds['time_format'] . "'",
                    "value='" . $ds['time_format'] . "' selected='selected'",
                    $format_time
                );

                $birthday = date("Y-m-d", strtotime($ds[ 'birthday' ]));*/

                $data_array = array();
                $data_array['$showerror'] = $showerror;
                $data_array['$nickname'] = $nickname;
                $data_array['$password'] = $password;
                $data_array['$mail'] = $mail;
				$data_array['$firstname'] = $firstname;
				$data_array['$lastname'] = $lastname;
                $data_array['$_captcha'] = $_captcha;
                #$data_array['$birthday'] = $birthday;
                $data_array['$gender'] = $gender;
                $data_array['$homepage'] = $homepage;
                #$data_array['$format_date'] = $format_date;
                #$data_array['$format_time'] = $format_time;

                $data_array['$registration'] = $plugin_language[ 'registration' ];
                $data_array['$info'] = $plugin_language[ 'info' ];
                $data_array['$nickname'] = $plugin_language[ 'nickname' ];
                $data_array['$for_login'] = $plugin_language[ 'for_login' ];
                $data_array['$password'] = $plugin_language[ 'password' ];
                $data_array['$mail'] = $plugin_language[ 'mail' ];
                $data_array['$security_code'] = $plugin_language[ 'security_code' ];
                $data_array['$register_now'] = $plugin_language[ 'register_now' ];
                $data_array['$profile_info'] = $plugin_language[ 'profile_info' ];
                $data_array['$pass_ver'] = $plugin_language[ 'pass_ver' ];
                $data_array['$pass_text'] = $plugin_language[ 'pass_text' ];
                $data_array['$lang_GDPRinfo'] = $plugin_language['GDPRinfo'];
                $data_array['$lang_GDPRaccept'] = $plugin_language['GDPRaccept'];
                $data_array['$lang_GDPRterm'] = $plugin_language['GDPRterm'];
                $data_array['$lang_privacy_policy'] = $plugin_language['privacy_policy'];
                $data_array['$pw1'] = $plugin_language['pw1'];
                $data_array['$pw2'] = $plugin_language['pw2'];
                $data_array['$pw3'] = $plugin_language['pw3'];
                $data_array['$pw4'] = $plugin_language['pw4'];
                $data_array['$pw5'] = $plugin_language['pw5'];
                $data_array['$pw6'] = $plugin_language['pw6'];
                $data_array['$login'] = $plugin_language[ 'login' ];
                $data_array['$email_address'] = $plugin_language[ 'email_address' ];
                $data_array['$already_have_an_account'] = $plugin_language['already_have_an_account'];
                $data_array['$enter_your_email'] = $plugin_language['enter_your_email'];
                $data_array['$enter_your_name'] = $plugin_language['enter_your_name'];
                $data_array['$enter_password'] = $plugin_language['enter_password'];
                $data_array['$repeat'] = $plugin_language['repeat'];
                $data_array['$info1'] = $plugin_language['info1'];
                $data_array['$info2'] = $plugin_language['info2'];
                $data_array['$date_of_birth'] = $plugin_language[ 'date_of_birth' ];
                $data_array['$lang_gender'] = $plugin_language[ 'gender' ];
                $data_array['$homepage1'] = $plugin_language[ 'homepage1' ];
                $data_array['$homepage2'] = $plugin_language[ 'homepage2' ];
                $data_array['$fields_star_required'] = $plugin_language[ 'fields_star_required' ];
                $data_array['$enter_your_firstname'] = $plugin_language['enter_your_firstname'];
                $data_array['$enter_your_lastname'] = $plugin_language['enter_your_lastname'];
                $data_array['$firstname'] = $plugin_language['firstname'];
                $data_array['$lastname'] = $plugin_language['lastname'];
                $data_array['$already_account'] = $plugin_language['already_account'];

                #$template = $tpl->loadTemplate("register","content", $data_array);
                #echo $template;
                $template = $GLOBALS["_template"]->loadTemplate("register","content", $data_array, $plugin_path);
                echo $template;
            } else {
                redirect(
                    "index.php",
                    str_replace(
                        '%pagename%',
                        $GLOBALS['hp_title'],
                        $plugin_language['no_cookie_accept']
                    ),
                    3
                );
            }
        } else {
            redirect(
                "index.php",
                str_replace(
                    '%pagename%',
                    $GLOBALS['hp_title'],
                    $plugin_language['no_register_when_loggedin']
                ),
                3
            );
        }
    }
}
echo'
            </div>
        </div>
    </div>
</div>';