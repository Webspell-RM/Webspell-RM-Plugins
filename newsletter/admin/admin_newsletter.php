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
$plugin_language = $pm->plugin_language("admin_newsletter", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='servers'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
} 

if(isset($_GET['id']))  { 
    $id = $_GET['id'];
} else {
    $id = "";
}

if (isset($_POST[ 'send' ]) || isset($_POST[ 'testen' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $title = $_POST[ 'title' ];
        $testmail = $_POST[ 'testmail' ];
        $date = getformatdate(time());
        $message = str_replace('\r\n', "\n", $_POST[ 'message' ]);
        $message_html = nl2br($message);
        $receptionists = $plugin_language[ 'receptionists' ];
        $error_send = $plugin_language[ 'error_send' ];

        //use page's default language for newsletter
        #$_language->setLanguage($default_language, true);
        $_language->readModule('newsletter', false, true);
        $no_htmlmail = $plugin_language[ 'no_htmlmail' ];
        $remove = $plugin_language[ 'remove' ];
        $profile = $plugin_language[ 'profile' ];

        $emailbody = '<!--
'.$no_htmlmail.'
'.stripslashes($message).'
 --> 

		<div id="newsletter" class="center">
		<a href="' . $hp_url . '" target="_blank" ><img src="' . $hp_url .
            '/includes/themes/default/images/1.png" alt="" class="center" style="display: block;"></a>
			<h3>' . stripslashes($title) . '</h3>
			<span>' . stripslashes($message_html) . '</span>
			<hr>
			<span id="footer">' . $remove . ' <a href="' . $hp_url . '/index.php?site=myprofile">' . $profile .
            '</a>.</span>
		</div>';

        if (isset($_POST[ 'testen' ])) {
            $bcc[ ] = $testmail;
            $_SESSION[ 'emailbody' ] = $message;
            $_SESSION[ 'title' ] = $title;
        } else {
            $emails = array();
            //clanmember

            if (isset($_POST[ 'sendto_clanmembers' ])) {
                $ergebnis = safe_query("SELECT userID FROM " . PREFIX . "plugins_squads_members GROUP BY userID");
                $anz = mysqli_num_rows($ergebnis);
                if ($anz) {
                    while ($ds = mysqli_fetch_array($ergebnis)) {
                        $emails[ ] = getemail($ds[ 'userID' ]);
                    }
                }
            }

            if (isset($_POST[ 'sendto_registered' ])) {
                $ergebnis = safe_query("SELECT * FROM " . PREFIX . "user WHERE newsletter='1'");
                $anz = mysqli_num_rows($ergebnis);
                if ($anz) {
                    while ($ds = mysqli_fetch_array($ergebnis)) {
                        $emails[ ] = $ds[ 'email' ];
                    }
                }
            }

            if (isset($_POST[ 'sendto_newsletter' ])) {
                $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_newsletter");
                $anz = mysqli_num_rows($ergebnis);
                if ($anz) {
                    while ($ds = mysqli_fetch_array($ergebnis)) {
                        $emails[ ] = $ds[ 'email' ];
                    }
                }
            }

            $bcc = $emails;
        }

        $success = true;
        $bcc = array_unique($bcc);
        $subject = $hp_title . " Newsletter";
        foreach ($bcc as $mailto) {
            $sendmail = \webspell\Email::sendEmail($admin_email, 'Newsletter', $mailto, $subject, $emailbody);
            if ($sendmail['result'] == 'fail') {
                $success = false;
            }
        }
        if ($success) {
            echo '<div class="card">
            <div class="card-header">
                            <i class="bi bi-newspaper"></i> ' . $plugin_language[ 'newsletter' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_newsletter">' . $plugin_language[ 'newsletter' ] . '</a></li>
                </ol>
            </nav> 
                        <div class="card-body">
                        <b>' . $receptionists . '</b><br /><br />' . implode(", ", $bcc);
            if (isset($sendmail['debug'])) {
                echo '<b> Debug </b>';
                echo '<br>' . $sendmail['debug'];
            }
            echo '<br></div></div>';
        } else {
            if (isset($sendmail['debug'])) {
                echo '<div class="card">
            <div class="card-header">
                            <i class="bi bi-newspaper"></i> ' . $plugin_language[ 'newsletter' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_newsletter">' . $plugin_language[ 'newsletter' ] . '</a></li>
                </ol>
            </nav> 
                        <div class="card-body"><b>' . $error_send . '</b>';
                echo '<br>' . $sendmail['error'];
                echo '<br>' . $sendmail['debug'];
            } else {
                echo '<b>' . $error_send . '</b>';
                echo '<br>' . $sendmail['error'];
            }
            echo '<br></div></div>';
        }
        redirect("admincenter.php?site=admin_newsletter", "", 5);
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }

}elseif($action=="archiv"){







if(isset($_GET['delete'])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            if(!issuperadmin($id) OR (issuperadmin($id) AND issuperadmin($userID))) {
                safe_query("UPDATE ".PREFIX."user SET newsletter='0' WHERE userID='$id'");
                redirect("admincenter.php?site=admin_newsletter&action=archiv", "", 0);
            }
        }
        elseif(isset($_GET['email'])){
            $email = $_GET['email'];
            if(!issuperadmin($id) OR (issuperadmin($id) AND issuperadmin($userID))) {
                safe_query("DELETE FROM ".PREFIX."plugins_newsletter WHERE email='".$email."'");
                redirect("admincenter.php?site=admin_newsletter&action=archiv", "", 0);
            }
        }       
    } else echo $plugin_language['transaction_invalid'];
}

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-envelope-at-fill"></i> ' . $plugin_language[ 'newsletter' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_newsletter">' . $plugin_language[ 'newsletter' ] . '</a></li>
                <li class="breadcrumb-item">' . $plugin_language[ 'newsletter_receiver' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';

echo'<h3>'.$plugin_language['newsletter_receiver'].'</h3>';
$ergebnis = safe_query("SELECT userID FROM ".PREFIX."user WHERE newsletter='1' ORDER BY nickname");
$abfrage  = safe_query("SELECT email FROM ".PREFIX."plugins_newsletter ORDER BY email");
$anz=mysqli_num_rows($ergebnis);
$anz2=mysqli_num_rows($abfrage);
if($anz || $anz2) {
     $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    if($anz) {
        echo'<table id="plugini" class="table">
        <thead>
        <tr>

            <th width="70%" class="title"><b>' . $plugin_language[ 'nickname_receiver' ] . '</b></th>
            <th width="30%" class="title"><b>' . $plugin_language[ 'actions' ] . '</b></th>
            
        </tr>
        </thead>
        <tbody>';
        $i=1;
        while($ds=mysqli_fetch_array($ergebnis)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }   
            $id=$ds['userID'];
            $nickname=getnickname($ds['userID']);
            echo'<tr>
            <td class="'.$td.'"><a href="../index.php?site=profile&amp;id='.$id.'" target="_blank">'.strip_tags(stripslashes($nickname)).'</a></td>
            <td class="'.$td.'">

            <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_newsletter&action=archiv&amp;delete=true&amp;id='.$ds['userID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['del'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'newsletter' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['del'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->

                    </td>
            </tr>';
            $i++;
        }
        echo'</table><br/>';
    }
    if($anz2) {
        echo'<table id="plugini" class="table">
        <thead>
        <tr>

            <th width="70%" class="title"><b>' . $plugin_language[ 'email_receiver' ] . '</b></th>
            <th width="30%" class="title"><b>' . $plugin_language[ 'actions' ] . '</b></th>
            
        </tr>
        </thead>
        <tbody>';
        $i=1;   
        while($dr=mysqli_fetch_array($abfrage)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }       
            $email=$dr['email'];
            echo'<tr>
            <td class="'.$td.'">'.$email.'</td>
            <td class="'.$td.'">

            <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_newsletter&action=archiv&amp;delete=true&amp;email='.$email.'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['del'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'newsletter' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete_mail'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['del'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->


                    </td>
            </tr>';
            $i++;
        }   
        echo'</table>';
    }
}
else echo $plugin_language['no_users'];


















} else {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    if (isset($_SESSION[ 'emailbody' ])) {
        $message = htmlspecialchars(stripslashes($_SESSION[ 'emailbody' ]));
    } else {
        $message = null;
    }
    if (isset($_SESSION[ 'title' ])) {
        $title = htmlspecialchars(stripslashes($_SESSION[ 'title' ]));
    } else {
        $title = null;
    }

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-envelope-at-fill"></i> ' . $plugin_language[ 'newsletter' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_newsletter">' . $plugin_language[ 'newsletter' ] . '</a></li>
                </ol>
            </nav> 
                        <div class="card-body">

        <div class="mb-3 row row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_newsletter&action=archiv" class="btn btn-primary" type="button">' . $plugin_language[ 'newsletter_receiver' ] . '</a>
    </div>
  </div>';

echo'<form class="form-horizontal" action="admincenter.php?site=admin_newsletter" method="post">
<div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language['title'] . ':</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" name="title" placeholder="' . $title . '"/>
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language['html_mail'] . ':</label>
    <div class="col-sm-8">
      <textarea class="ckeditor" id="ckeditor" class="form-control" cols=""  name="message" placeholder="' . $plugin_language['html_mail'] . '">' . $message . '</textarea>
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language['test_newsletter'] . ':</label>
    <div class="col-sm-5">
<input type="text" class="form-control" name="testmail"  placeholder="user@inter.net"/></div>
<div class="col-sm-5"><br><button class="btn btn-primary" type="submit" name="testen" />' . $plugin_language['test'] . '</button>
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language['send_to'] . ':</label>
    <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
      <input class="form-check-input" type="checkbox" name="sendto_clanmembers" value="1" />&nbsp;&nbsp;
      ' . $plugin_language['user_clanmembers'] . ' 
      [' . mysqli_num_rows(safe_query("SELECT userID FROM ".PREFIX."plugins_squads_members GROUP BY userID")).'&nbsp;'.$plugin_language['users'] . ']
    
    <br /><br /><input class="form-check-input" type="checkbox" name="sendto_registered" value="1" />&nbsp;&nbsp;
    ' . $plugin_language['user_registered'] . ' 
    [' . mysqli_num_rows(safe_query("SELECT * FROM ".PREFIX."user WHERE newsletter='1'")).'&nbsp;'.$plugin_language['users'] . ']
    
    <br /><br /><input class="form-check-input" type="checkbox" name="sendto_newsletter" value="1" />&nbsp;&nbsp;
    ' . $plugin_language['user_newsletter'] . ' 
    [' . mysqli_num_rows(safe_query("SELECT * FROM ".PREFIX."plugins_newsletter")).'&nbsp;'.$plugin_language['users'] . ']

    </div>
  </div>
<div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10"><input type="hidden" name="captcha_hash" value="' . $hash . '"/>
  <button class="btn btn-primary" type="submit" name="send">' . $plugin_language['send'] . '</button>
 </div>
</div>
</form>

</div>
</div>';
}
?>