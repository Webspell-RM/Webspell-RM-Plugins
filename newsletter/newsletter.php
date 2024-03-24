<?php
/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2015 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#                                                                        #
##########################################################################
*/

# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("newsletter", $plugin_path);

#$_language->readModule('newsletter');
$_language->readModule('formvalidation', true);

if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = '';
}

if ($action == "save") {
    $email = $_POST['email'];

    if (!validate_email($email)) {
        redirect(
            'index.php?site=newsletter',
            generateAlert($plugin_language['email_not_valid'], 'alert-danger'),
            3
        );
    } else {
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_newsletter WHERE email='" . $email . "'");
        if (!mysqli_num_rows($ergebnis)) {
            $pass = RandPass(7);

            safe_query(
                "INSERT INTO
                    " . PREFIX . "plugins_newsletter (
                        `email`,
                        `pass`
                    )
                    values (
                        '" . $email . "',
                        '" . $pass . "'
                    )"
            );

            $vars = array('%delete_key%', '%homepage_url%', '%mail%');
            $repl = array($pass, $hp_url, $email);
            $subject = $hp_title . ": " . $plugin_language['newsletter_registration'];
            $message = str_replace(
                $vars,
                $repl,
                $plugin_language['success_mail']
            );
            $sendmail = \webspell\Email::sendEmail($admin_email, 'Newsletter', $email, $subject, $message);

            if ($sendmail['result'] == 'fail') {
                if (isset($sendmail['debug'])) {
                    $fehler = array();
                    $fehler[] = $sendmail[ 'error' ];
                    $fehler[] = $sendmail[ 'debug' ];
                    redirect(
                        'index.php?site=newsletter',
                        generateErrorBoxFromArray($plugin_language['errors_there'], $fehler),
                        10
                    );
                } else {
                    $fehler = array();
                    $fehler[] = $sendmail[ 'error' ];
                    redirect(
                        'index.php?site=newsletter',
                        generateErrorBoxFromArray($plugin_language['errors_there'], $fehler),
                        10
                    );
                }
            } else {
                if (isset($sendmail['debug'])) {
                    $fehler = array();
                    $fehler[] = $sendmail[ 'debug' ];
                    redirect(
                        'index.php?site=newsletter',
                        generateBoxFromArray(
                            $plugin_language['thank_you_for_registration'],
                            'alert-success',
                            $fehler
                        ),
                        10
                    );
                } else {
                    redirect(
                        'index.php?site=newsletter',
                        generateAlert($plugin_language['thank_you_for_registration'], 'alert-success'),
                        3
                    );
                }
            }
        } else {
            redirect(
                'index.php?site=newsletter',
                generateAlert($plugin_language['you_are_already_registered'], 'alert-warning'),
                3
            );
        }
    }
} elseif ($action == "delete") {
    $ergebnis = safe_query("SELECT pass FROM " . PREFIX . "plugins_newsletter WHERE email='" . $_POST['email'] . "'");
    $any = mysqli_num_rows($ergebnis);
    if ($any) {
        $dn = mysqli_fetch_array($ergebnis);

        if ($_POST['password'] == $dn['pass']) {
            safe_query("DELETE FROM " . PREFIX . "plugins_newsletter WHERE email='" . $_POST['email'] . "'");
            redirect(
                'index.php?site=newsletter',
                generateAlert($plugin_language['your_mail_adress_deleted'], 'alert-success'),
                3
            );
        } else {
            redirect(
                'index.php?site=newsletter',
                generateAlert($plugin_language['mail_pw_didnt_match'], 'alert-danger'),
                3
            );
        }
    } else {
        redirect(
            'index.php?site=newsletter',
            generateAlert($plugin_language['mail_not_in_db'], 'alert-danger'),
            3
        );
    }
} elseif ($action == "forgot") {
    $ergebnis = safe_query("SELECT pass FROM " . PREFIX . "plugins_newsletter WHERE email='" . $_POST['email'] . "'");
    $dn = mysqli_fetch_array($ergebnis);

    if ($dn['pass'] != "") {
        $email = $_POST['email'];
        $pass = $dn['pass'];

        $vars = array('%delete_key%', '%homepage_url%', '%mail%');
        $repl = array($pass, $hp_url, $email);
        $subject = $hp_title . ": " . $plugin_language['deletion_key'];
        $message = str_replace(
            $vars,
            $repl,
            $plugin_language['request_mail']
        );
        $sendmail = \webspell\Email::sendEmail($admin_email, 'Newsletter', $email, $subject, $message);

        if ($sendmail['result'] == 'fail') {
            if (isset($sendmail['debug'])) {
                $fehler = array();
                $fehler[] = $sendmail[ 'error' ];
                $fehler[] = $sendmail[ 'debug' ];
                redirect(
                    'index.php?site=newsletter',
                    generateErrorBoxFromArray($plugin_language['errors_there'], $fehler),
                    10
                );
            } else {
                $fehler = array();
                $fehler[] = $sendmail['error'];
                redirect(
                    'index.php?site=newsletter',
                    generateErrorBoxFromArray($plugin_language['errors_there'], $fehler),
                    10
                );
            }
        } else {
            if (isset($sendmail['debug'])) {
                $fehler = array();
                $fehler[] = $sendmail['error'];
                redirect(
                    'index.php?site=newsletter',
                    generateBoxFromArray($plugin_language['password_had_been_send'], 'alert-success', $fehler),
                    10
                );
            } else {
                redirect(
                    'index.php?site=newsletter',
                    generateAlert($plugin_language['password_had_been_send'], 'alert-success'),
                    3
                );
            }
        }
    } else {
        redirect(
            'index.php?site=newsletter',
            generateAlert($plugin_language['no_such_mail_adress'], 'alert-danger'),
            3
        );
    }
} else {
    $usermail = getemail($userID);
    if (isset($_GET['mail'])) {
        $get_mail = getforminput($_GET['mail']);
    } else {
        $get_mail = '';
    }
    if ($get_mail == "") {
        $get_mail = $plugin_language['mail_adress'];
    }
    if (isset($_GET['pass'])) {
        $get_pw = getforminput($_GET['pass']);
    } else {
        $get_pw = '';
    }
    if ($get_pw == "") {
        $get_pw = $plugin_language['del_key'];
    }

    $data_array = array();
    $data_array['$title']=$plugin_language[ 'newsletter' ];
    $data_array['$subtitle']='Newsletter';
    $template = $GLOBALS["_template"]->loadTemplate("newsletter","head", $data_array, $plugin_path);
    echo $template;

    $data_array = array();
    $data_array['$usermail'] = $usermail;
    $data_array['$get_mail'] = $get_mail;
    $data_array['$get_pw'] = $get_pw;

    $data_array['$lang_register_newsletter']=$plugin_language[ 'register_newsletter' ];
    $data_array['$lang_mail_adress']=$plugin_language[ 'mail_adress' ];
    $data_array['$lang_submit']=$plugin_language[ 'submit' ];
    
    $template = $GLOBALS["_template"]->loadTemplate("newsletter","register", $data_array, $plugin_path);
    echo $template;


    $data_array = array();
    $data_array['$usermail'] = $usermail;
    $data_array['$get_mail'] = $get_mail;
    $data_array['$get_pw'] = $get_pw;

    $data_array['$lang_lost_deletion_key']=$plugin_language[ 'lost_deletion_key' ];
    $data_array['$lang_mail_adress']=$plugin_language[ 'mail_adress' ];
    $data_array['$lang_send']=$plugin_language[ 'send' ];

    $template = $GLOBALS["_template"]->loadTemplate("newsletter","lost_deletion_key", $data_array, $plugin_path);
    echo $template;

    $data_array = array();
    $data_array['$usermail'] = $usermail;
    $data_array['$get_mail'] = $get_mail;
    $data_array['$get_pw'] = $get_pw;

    $data_array['$lang_del_from_mail_list']=$plugin_language[ 'del_from_mail_list' ];
    $data_array['$lang_lost_deletion_key']=$plugin_language[ 'lost_deletion_key' ];
    $data_array['$lang_mail_adress']=$plugin_language[ 'mail_adress' ];
    $data_array['$lang_del_key']=$plugin_language[ 'del_key' ];
    $data_array['$lang_submit']=$plugin_language[ 'submit' ];

    $template = $GLOBALS["_template"]->loadTemplate("newsletter","del_from_mail_list", $data_array, $plugin_path);
    echo $template;
}
