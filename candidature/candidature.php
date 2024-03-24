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
	$plugin_language = $pm->plugin_language("candidature", $plugin_path);

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

// candidature send & überprüfen

if(@$_POST["action"] == "send") {


	$_language->readModule('candidature');
	$plugin_data= array();
    $plugin_data['$title']=$plugin_language['join_headline'];
	$plugin_data['$subtitle']='Candidature';
    
	$template = $GLOBALS["_template"]->loadTemplate("candidature","head", $plugin_data, $plugin_path);
    echo $template;



  $vname = trim(stripslashes($_POST['vname']));
  $nname = trim(stripslashes($_POST['nname'])); 
  $age = trim(stripslashes($_POST['age']));
  $text = trim(stripslashes($_POST['text']));
  $objectives = trim(stripslashes($_POST['objectives'])); 
  $history = trim(stripslashes($_POST['history']));
  $icq = trim(stripslashes($_POST['icq']));
  $email = trim(stripslashes($_POST['email'])); 
  $who = trim(stripslashes($_POST['who']));  
  $date = date('Y-m-d H:i:s');
 
 
  if(!$vname) $fehler[] = $plugin_language['vorname_an'];
  if(!$age) $fehler[] = $plugin_language['alter_an'];  
  if(!$email) $fehler[] = $plugin_language['email_an'];
  if(!validate_email($email)) $fehler[] = $plugin_language['email_wrong'];  
  if(!$text) $fehler[] = $plugin_language['b_text']; 
  if(!$loggedin) {
  	
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

		
		
  if(!$fehler ='') {
  
  safe_query("INSERT INTO ".PREFIX."plugins_candidature ( vname, nname, age, email, icq, squadID, text, who, history, objectives, date ) values ( '".$_POST['vname']."','".$_POST['nname']."','".$_POST['age']."','".$_POST['email']."','".$_POST['icq']."','".$_POST['squad']."','".$_POST['text']."','".$_POST['who']."','".$_POST['history']."','".$_POST['objectives']."','".$date."' )");
	
    
	
    unset($vname); unset($nname); unset($age); unset($squad); unset($email); unset($icq); unset($text); unset($objectives); unset($history); unset($date);	
	
   $ergebnis=safe_query("SELECT userID FROM ".PREFIX."user_groups WHERE user='1'");
	 while($ds=mysqli_fetch_array($ergebnis)) {
		$touser[]=$ds['userID'];
	 }
	 $message = ''.$plugin_language['b_vorhanden'].'
<a href="index.php?site=candidature&amp;action=new">'.$plugin_language['klick_here'].'</a>';
	 foreach($touser as $id) {
    sendmessage($id, $plugin_language['b_vorhanden'], $message);
   }	
   redirect('index.php?site=candidature', $plugin_language['b_erfolg'],4);
} else {

  	$fehler=implode('<br />&#8226; ',$fehler);
    $showerror = '<div class="alert alert-danger" role="alert"><div class="errorbox">
      <b>'.$plugin_language['fehler'].'</b><br /><br />
      &#8226; '.$fehler.'
    </div></div>';
	echo $showerror;
	
	if($userID) {
		
 
			$data_array= array();
 

			$data_array['$lang_join_formular']=$plugin_language['join_formular'];
			$data_array['$lang_firstname']=$plugin_language['firstname'];
			$data_array['$lang_lastname']=$plugin_language['lastname'];
			$data_array['$lang_age']=$plugin_language['age'];
			$data_array['$lang_e_mail']=$plugin_language['e_mail'];
			$data_array['$lang_subject']=$plugin_language['subject'];
			$data_array['$lang_join_text']=$plugin_language['join_text'];
			$data_array['$lang_imaginations']=$plugin_language['imaginations'];
			$data_array['$lang_experience']=$plugin_language['experience'];
			$data_array['$lang_worben_from']=$plugin_language['worben_from'];
			$data_array['$lang_send']=$plugin_language['send'];
			$data_array['$lang_set_back']=$plugin_language['set_back'];
			$data_array['$lang_GDPRinfo']=$plugin_language['GDPRinfo'];
        	$data_array['$lang_GDPRaccept']=$plugin_language['GDPRaccept'];
        	$data_array['$lang_discord']=$plugin_language['discord'];
			

		$template = $GLOBALS["_template"]->loadTemplate("candidature","logged", array(), $plugin_path);
        echo $template;
	
	}
	else {

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

		$data_array= array();
        $data_array['$_captcha'] = $_captcha;

			$data_array['$lang_join_formular']=$plugin_language['join_formular'];
			$data_array['$lang_firstname']=$plugin_language['firstname'];
			$data_array['$lang_lastname']=$plugin_language['lastname'];
			$data_array['$lang_age']=$plugin_language['age'];
			$data_array['$lang_e_mail']=$plugin_language['e_mail'];
			$data_array['$lang_subject']=$plugin_language['subject'];
			$data_array['$lang_join_text']=$plugin_language['join_text'];
			$data_array['$lang_imaginations']=$plugin_language['imaginations'];
			$data_array['$lang_experience']=$plugin_language['experience'];
			$data_array['$lang_worben_from']=$plugin_language['worben_from'];
			$data_array['$lang_send']=$plugin_language['send'];
			$data_array['$lang_set_back']=$plugin_language['set_back'];
			$data_array['$lang_GDPRinfo']=$plugin_language['GDPRinfo'];
        	$data_array['$lang_GDPRaccept']=$plugin_language['GDPRaccept'];
			$data_array['$lang_security_code']=$plugin_language['security_code'];
			$data_array['$lang_discord']=$plugin_language['discord'];

		$template = $GLOBALS["_template"]->loadTemplate("candidature","notlogged", $data_array, $plugin_path);
        echo $template;
		
	}
  }
} 

// Status festlegen (Checkbox)  
  
elseif(@$_POST['quickaction']) {

	$quickactiontype = $_POST['quickactiontype'];
  	#$candidatureID = $_POST['candidatureID'];

  	if(@$_POST['candidatureID']) {
  		@$candidatureID = $_POST['candidatureID'];
  	}else{
  		
  		@$candidatureID = '';
  		echo $plugin_language['error_checkbox'];
  	}
	
   	if($quickactiontype=="viewed") {
    	foreach((array)$candidatureID as $id) {
	    	safe_query("UPDATE ".PREFIX."plugins_candidature SET readed='1' WHERE candidatureID='$id'");
		}
	}
	elseif($quickactiontype=="notviewed") {
    	foreach((array)$candidatureID as $id) {
	    	safe_query("UPDATE ".PREFIX."plugins_candidature SET readed='0' WHERE candidatureID='$id'");
		}
	}
	elseif($quickactiontype=="disagree") {
    	foreach((array)$candidatureID as $id) {
	    	safe_query("UPDATE ".PREFIX."plugins_candidature SET readed='2' WHERE candidatureID='$id'");
		}
	}	
	elseif($quickactiontype=="delete") {
    	foreach((array)$candidatureID as $id) {
			safe_query("DELETE FROM ".PREFIX."plugins_candidature WHERE candidatureID='$id'");
		}
	}		
	
	redirect('index.php?site=candidature&amp;action=new', $plugin_language['a_erfolg'],4);	
}

// Admin-Menü
elseif(@$_GET['action'] == "new") {
 	
	$plugin_data= array();
    $plugin_data['$title']=$plugin_language['join_headline'];
	$plugin_data['$subtitle']='Candidature';

    
	$template = $GLOBALS["_template"]->loadTemplate("candidature","head", $plugin_data, $plugin_path);
    echo $template;

 	$check=safe_query("SELECT candidatureID FROM ".PREFIX."plugins_candidature");
 	$check=mysqli_num_rows($check);
 
 	$back = '<a class="btn btn-secondary" href="index.php?site=candidature">'.$plugin_language['back'].'</a>';
 
 	if($check) {
 
 if(!isuseradmin($userID)) {
 echo ''.$plugin_language['no_acces'].''.$back.'';
 
 } else {

 	$ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_candidature ORDER BY date DESC");
 

	$data_array = array();
	$data_array['$date']=$plugin_language['date'];
	$data_array['$joiner']=$plugin_language['joiner'];
	$data_array['$status']=$plugin_language['status'];
	$data_array['$option']=$plugin_language['option'];
	$data_array['$subject']=$plugin_language['subject'];


 	$template = $GLOBALS["_template"]->loadTemplate("candidature","news_head", $data_array, $plugin_path);
    echo $template;
	
	echo '<form method="post" name="form" action="index.php?site=candidature&amp;action=new">
		  <!--<table class="table table-striped">-->';
		
	$i=1;
 	if(mysqli_num_rows($ergebnis)) {		
		while($ds=mysqli_fetch_array($ergebnis)) {

		
  
		$text 	= $ds['date'];
		$date 	= strtotime($text);
		$bwdate	= date('d.m.Y', $date);

		if($ds['nname']=='') {
			$nname='<i>'.$plugin_language['not_exist'].'</i>';
		} else {
			$nname=$ds['nname'];
		}
			
		$squad = getsquadname($ds['squadID']);

		if($ds['readed']=='0') $status='<b><p class="text-success">'.$plugin_language['open'].'</p></b>';
		if($ds['readed']=='1') $status='<b><p class="text-warning">'.$plugin_language['read'].'</p></b>';
		if($ds['readed']=='2') $status='<b><p class="text-danger">'.$plugin_language['rejected'].'</p></b>';		

		echo '<tr>
		<td><input class="input" type="checkbox" name="candidatureID[]" value="'.$ds['candidatureID'].'" /></td>
		<td>'.$bwdate.'</td>
		<td>'.$ds['vname'].' '.$nname.'</td>
		<td>'.$squad.'</td>
		<td>'.$status.'</td>
		<td>
		<a class="btn btn-warning" href="index.php?site=candidature&amp;action=details&amp;candidatureID='.$ds['candidatureID'].'" class="input">' . $plugin_language[ 'details' ] . '</a>

		</td>
		</tr>'; $i++;}}
		echo '
		<tr>
		<td align="left" colspan="2" valign="middle">
		<input class="input" type="checkbox" name="ALL" value="ALL" onclick="SelectAll(this.form);" /> '.$plugin_language['take_all'].'</td>
		</td>
		<td align="right" colspan="4" valign="middle">
		<select name="quickactiontype">
		<option value="viewed">'.$plugin_language['mark_as_read'].'</option>
		<option value="notviewed">'.$plugin_language['mark_as_unread'].'</option>
		<option value="disagree">'.$plugin_language['mark_as_rejected'].'</option>
		<option value="delete">'.$plugin_language['delete'].'</option>				 
		</select> <input class="btn btn-primary" type="submit" name="quickaction" value="'.$plugin_language['doit'].'" />
		</td></tr>';

		echo '</from> </tbody></table><br /><br />';

		
		$data_array = array();
		$data_array['$lang_back']=$plugin_language['back'];
		$template = $GLOBALS["_template"]->loadTemplate("candidature","new_foot", $data_array, $plugin_path);
	    echo $template;
		
		}
	} else { 
		echo ''.$plugin_language['no_joiner'].''.$back.'';}
  	}
// candidature löschen
elseif(@$_GET['delete']) {
  	$candidatureID = $_GET['candidatureID'];
  	safe_query("DELETE FROM ".PREFIX."candidature WHERE candidatureID='$candidatureID'");
  	redirect('index.php?site=candidature&amp;action=new', $plugin_language['deletet_joiner'],4);
}

// Details (Popup)

elseif(@$_GET['action'] == "details") {
	
	$plugin_data= array();
    $plugin_data['$title']=$plugin_language['join_headline'];
	$plugin_data['$subtitle']='Candidature';

    $template = $GLOBALS["_template"]->loadTemplate("candidature","head", $plugin_data, $plugin_path);
    echo $template;

	if(!isuseradmin($userID)) {
		echo $plugin_language['acces_not_granted'];
	} else {

		
		$candidatureID = $_GET['candidatureID'];
		$ds=mysqli_fetch_array(safe_query("SELECT * FROM ".PREFIX."plugins_candidature WHERE candidatureID='$candidatureID'"));

		if($ds['nname']=='') $nname='<i>'.$plugin_language['not_exist'].'</i>';
		else $nname=$ds['nname'];
		
		if($ds['icq']=='') $icq='<i>'.$plugin_language['not_exist'].'</i>';
		else $icq=$ds['icq'];
		
		if($ds['who']=='') $who='<i>'.$plugin_language['not_exist'].'</i>';
		else $who=$ds['who'];	

		if($ds['objectives']=='' OR $ds['objectives']=='0')  $objectives='<i>'.$plugin_language['not_exist'].'</i>';
		else $objectives=$ds['objectives'];

		if($ds['history']=='' OR $ds['history']=='0')  $history='<i>'.$plugin_language['not_exist'].'</i>';
		else $history=$ds['history'];


		$email	= '<a href="mailto:'.$ds['email'].'">'.$ds['email'].'</a>';
		$squad = getsquadname($ds['squadID']);

		$data_array = array();
		$data_array['$squad'] = $squad;
		$data_array['$nname'] = $nname;
		$data_array['$email'] = $email;
		$data_array['$who'] = $who;
		$data_array['$icq'] = $icq;
		$data_array['$objectives'] = $objectives;
		$data_array['$history'] = $history;
		$data_array['$age'] = $ds['age'];
		$data_array['$vname'] = $ds['vname'];
		$data_array['$text'] = $ds['text'];


		$data_array['$lang_firstname']=$plugin_language['firstname'];
		$data_array['$lang_information']=$plugin_language['information'];
		$data_array['$lang_age']=$plugin_language['age'];
		$data_array['$lang_worben_from']=$plugin_language['worben_from'];
		$data_array['$lang_subject']=$plugin_language['subject'];
		$data_array['$lang_join_text']=$plugin_language['join_text'];
		$data_array['$lang_imaginations']=$plugin_language['imaginations'];
		$data_array['$lang_experience']=$plugin_language['experience'];
		$data_array['$lang_contact']=$plugin_language['contact'];
		$data_array['$lang_e_mail']=$plugin_language['e_mail'];
		$data_array['$lang_back']=$plugin_language['back'];
		$data_array['$lang_discord']=$plugin_language['discord'];


		$template = $GLOBALS["_template"]->loadTemplate("candidature","details", $data_array, $plugin_path);
	    echo $template;
		
	}
  
// Hauptteil  
  
} else {

	
	$plugin_data= array();
    $plugin_data['$title']=$plugin_language['join_headline'];
	$plugin_data['$subtitle']='Candidature';

    $template = $GLOBALS["_template"]->loadTemplate("candidature","head", $plugin_data, $plugin_path);
    echo $template;
 
	
	if(isuseradmin($userID)) {
		$new=safe_query("SELECT candidatureID FROM ".PREFIX."plugins_candidature WHERE readed='0'");
		$new=mysqli_num_rows($new);

		if($new == '0') {
			$new = '<b><font color="#00CC00">'.$plugin_language['not_open'].'</font></b>';
		}elseif ($new =='1') {
			$new = '<b><font color="#FF6600">'.$new.' '.$plugin_language['open_joiner'].'</font></b>';
		}elseif ($new >='2') {
			$new = '<b><font color="#DD0000">'.$new.' '.$plugin_language['open_joiners'].'</font></b>';
		}#else{
			
		echo '<table width="100%" cellpadding="0" cellspacing="0"><tr><td>
		<table width="100%" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr><td align="left"><b>'.$plugin_language['joiners'].' </b>'.$new.'</td>
		<td align="right">

		<a class="btn btn-warning" href="index.php?site=candidature&amp;action=new" class="input">' . $plugin_language[ 'look_all_joiners' ] . '</a>
		</td>
		</tr></table></td></tr></table><br>';
		#}
		if($userID) {

			$squads = getgamesquads();
    	}
    		$data_array= array();
			$data_array['$new'] = $new;
			$data_array['$squads'] = $squads;

			$data_array['$lang_join_formular']=$plugin_language['join_formular'];
			$data_array['$lang_firstname']=$plugin_language['firstname'];
			$data_array['$lang_lastname']=$plugin_language['lastname'];
			$data_array['$lang_age']=$plugin_language['age'];
			$data_array['$lang_e_mail']=$plugin_language['e_mail'];
			$data_array['$lang_subject']=$plugin_language['subject'];
			$data_array['$lang_join_text']=$plugin_language['join_text'];
			$data_array['$lang_imaginations']=$plugin_language['imaginations'];
			$data_array['$lang_experience']=$plugin_language['experience'];
			$data_array['$lang_worben_from']=$plugin_language['worben_from'];
			$data_array['$lang_send']=$plugin_language['send'];
			$data_array['$lang_set_back']=$plugin_language['set_back'];
			$data_array['$lang_GDPRinfo']=$plugin_language['GDPRinfo'];
        	$data_array['$lang_GDPRaccept']=$plugin_language['GDPRaccept'];
        	$data_array['$lang_discord']=$plugin_language['discord'];
			
		$template = $GLOBALS["_template"]->loadTemplate("candidature","logged", $data_array, $plugin_path);
        echo $template;
	
	} else {
		


        if($recaptcha=="0") { 
            $CAPCLASS = new \webspell\Captcha;
            $captcha = $CAPCLASS->createCaptcha();
            $hash = $CAPCLASS->getHash();
            $CAPCLASS->clearOldCaptcha();
            $_captcha = '
                <span class="input-group-addon captcha-img">'.$captcha.'</span>
                <input type="number" name="captcha" class="form-control" id="input-security-code">
                <input name="captcha_hash" type="hidden" value="'.$hash.'">';
        } else {
            $_captcha = '
                <div class="g-recaptcha" style="width: 70%; float: left;" data-sitekey="'.$webkey.'"></div>';
        }
    

        	$squads = getgamesquads();
    
    		$data_array= array();
			$data_array['$squads'] = $squads;
        	$data_array['$_captcha'] = $_captcha;

			$data_array['$lang_join_formular']=$plugin_language['join_formular'];
			$data_array['$lang_firstname']=$plugin_language['firstname'];
			$data_array['$lang_lastname']=$plugin_language['lastname'];
			$data_array['$lang_age']=$plugin_language['age'];
			$data_array['$lang_e_mail']=$plugin_language['e_mail'];
			$data_array['$lang_subject']=$plugin_language['subject'];
			$data_array['$lang_join_text']=$plugin_language['join_text'];
			$data_array['$lang_imaginations']=$plugin_language['imaginations'];
			$data_array['$lang_experience']=$plugin_language['experience'];
			$data_array['$lang_worben_from']=$plugin_language['worben_from'];
			$data_array['$lang_send']=$plugin_language['send'];
			$data_array['$lang_set_back']=$plugin_language['set_back'];
			$data_array['$lang_GDPRinfo']=$plugin_language['GDPRinfo'];
        	$data_array['$lang_GDPRaccept']=$plugin_language['GDPRaccept'];
			$data_array['$lang_security_code']=$plugin_language['security_code'];
			$data_array['$lang_discord']=$plugin_language['discord'];
			
		$template = $GLOBALS["_template"]->loadTemplate("candidature","notlogged", $data_array, $plugin_path);
        echo $template;
	}	
}

?>