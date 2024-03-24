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
    $plugin_language = $pm->plugin_language("awaylist", $plugin_path);

if (!isset($_GET[ 'action' ])) {
    $action = '';
} else {
    $action = $_GET[ 'action' ];
} 

	$data_array = array();
    $data_array['$title']=$plugin_language['title_awaylist'];    
    $data_array['$subtitle']='Awaylist';
	$template = $GLOBALS["_template"]->loadTemplate("awaylist","title", $data_array, $plugin_path);
	echo $template;   


if (!isclanmember($userID)) {
    echo $plugin_language[ 'no_accses' ];
} else {

if($action=="add") {
global $userID;
	$ergebnis = safe_query("SELECT * FROM " . PREFIX . "user");
        $member = '';
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $id = $ds[ 'userID' ];

        $member .= '<option name="awayuser" value="' . $ds['userID'] . '">' . getnickname($ds['userID']) . '</option>';
        $member1 = '<option name="awayuser" value="' . $userID . '">' . getnickname($userID) . '</option>';
		}

        if ($member == "") {
            $member = '<option value="">' . $plugin_language['no_buddies'] . '</option>';
        } else {
            $member = '<option value="" selected="selected">---</option>' . $member;
        }


if(!issuperadmin($userID)) {
    $addadmin='<tr><td>'.$plugin_language['nickname'].':</td><td><select name="awayuser" size="1" class="form-select">'.$member1.'</select></td></tr>';
} else {
    $addadmin='<tr><td>'.$plugin_language['nickname'].':</td><td><select name="awayuser" size="1" class="form-select">'.$member.'</select></td></tr>';
}


	$data_array = array();
	$data_array['$addadmin'] = $addadmin;
	$data_array['$id'] = $id;

	$data_array['$title_awaylist']=$plugin_language['title_awaylist'];
	$data_array['$set_you_away']=$plugin_language['set_you_away'];
	$data_array['$away_from']=$plugin_language['away_from'];
	$data_array['$your_reason']=$plugin_language['your_reason'];
	$data_array['$send']=$plugin_language['send'];
	
	
	$data_array['$start']=$plugin_language['start'];
	$data_array['$end']=$plugin_language['end'];

	$template = $GLOBALS["_template"]->loadTemplate("awaylist","add", $data_array, $plugin_path);
	echo $template;
	print_r($userID);
}
elseif($action=="edit") {

	$CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();


	$ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_awaylist WHERE awayID=".$_GET['id']."");
	$ds=mysqli_fetch_array($ergebnis);
	if($userID!=$ds['userID'] and !ispageadmin($userID)) die($plugin_language['no_accses']);
	
	$addadmin='<tr><td>'.$plugin_language['nickname'].':</td><td><input class="form-control" name="awayuser" type="text" value="'.getnickname($ds['userID']).'" aria-label="Disabled input example" disabled readonly></td></tr>';
	
	$startaway = $ds[ 'startaway' ];
	$endaway = $ds[ 'endaway' ];
	$comment=$ds['comment'];
	$id=$ds['userID'];
	$awayid=$ds['awayID'];

	$data_array = array();
	$data_array['$addadmin'] = $addadmin;
  	$data_array['$startaway'] = $startaway;
  	$data_array['$endaway'] = $endaway;
    $data_array['$comment'] = $comment;
    $data_array['$awayid'] = $awayid;
    $data_array['$id'] = $id;

    $data_array['$title_awaylist']=$plugin_language['title_awaylist'];
	$data_array['$set_you_away']=$plugin_language['set_you_away'];
	$data_array['$away_from']=$plugin_language['away_from'];
	$data_array['$your_reason']=$plugin_language['your_reason'];
	$data_array['$send']=$plugin_language['send'];
	$data_array['$start']=$plugin_language['start'];
	$data_array['$end']=$plugin_language['end'];

	$template = $GLOBALS["_template"]->loadTemplate("awaylist","edit", $data_array, $plugin_path);
	echo $template;
}
elseif($action=="show") {

	$CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $data_array = array();
	$ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_awaylist WHERE awayID=".$_GET['id']."");
	$ds=mysqli_fetch_array($ergebnis);
	#$awayfrom=$ds['startaway'];
	#$awayto=$ds['endaway'];

	$start = $ds[ 'startaway' ];
	$awayfrom = date('d.m.Y', strtotime(str_replace('-','/', $start))); 

	$end = $ds[ 'endaway' ];
	$awayto = date('d.m.Y', strtotime(str_replace('-','/', $end))); 

	$comment=$ds['comment'];
	$id=$ds['userID'];
	$awayid=$ds['awayID'];
	$nickname='<a href="index.php?site=profile&id='.$ds['userID'].'"><b>'.getnickname($ds['userID']).'</b></a>';
	$action='<tr><td>'.$plugin_language['action'].':</td><td valign="right" align="right">';
	if($userID!=$ds['userID']) $action.='<a class="btn btn-warning" href="index.php?site=awaylist&action=edit&id='.$ds['awayID'].'" class="input">' . $plugin_language[ 'edit' ] . '</a>

<a class="btn btn-danger" href="javascript:void(0)" onclick="MM_confirm(\''.$plugin_language['really_delete'].'\', \'index.php?site=awaylist&action&amp;delete=true&amp;awayID='.$ds['awayID'].'&amp;captcha_hash=' . $hash . '\')"><i class="bi bi-trash"  style="font-size: 1rem;"></i> '.$plugin_language['delete'].'</a>';
	elseif(ispageadmin($userID)) $action.='<a class="btn btn-warning" href="index.php?site=awaylist&action=edit&id='.$ds['awayID'].'" class="input">' . $plugin_language[ 'edit' ] . '</a>

<a class="btn btn-danger" href="javascript:void(0)" onclick="MM_confirm(\''.$plugin_language['really_delete'].'\', \'index.php?site=awaylist&action&amp;delete=true&amp;awayID='.$ds['awayID'].'&amp;captcha_hash=' . $hash . '\')"><i class="bi bi-trash"  style="font-size: 1rem;"></i> '.$plugin_language['delete'].'</a>';
	$action.='</td></tr>';	
	
	$data_array = array();
	$data_array['$nickname'] = $nickname;
    $data_array['$comment'] = $comment;
  	$data_array['$awayfrom'] = $awayfrom;
	$data_array['$awayto'] = $awayto;
	$data_array['$action'] = $action;

	$data_array['$title_awaylist']=$plugin_language['title_awaylist'];

	$data_array['$lang_nickname']=$plugin_language['nickname'];
	$data_array['$lang_from']=$plugin_language['from'];
	$data_array['$lang_to']=$plugin_language['to'];
	$data_array['$lang_your_reason']=$plugin_language['your_reason'];
	

	$template = $GLOBALS["_template"]->loadTemplate("awaylist","show", $data_array, $plugin_path);
	echo $template;
}
elseif(isset($_POST['save'])) {

		if(!$_POST['userID']=="") {
			$id=getuserid($_POST['awayuser']);
		}else{
			$id=$_POST['userID'];
		}

		$id=$_POST['awayuser'];
		$startaway = $_POST[ 'startaway' ];
		$endaway = $_POST[ 'endaway' ];
		safe_query("INSERT INTO ".PREFIX."plugins_awaylist (userID, startaway, endaway , comment)
		                values('$id', '".$startaway."', '".$endaway."', '".$_POST['comment']."')");
		redirect("index.php?site=awaylist",$plugin_language['saved'],1);
}
elseif(isset($_POST['saveedit'])){
	
		$CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

		
			
		$sql=safe_query("SELECT userID FROM ".PREFIX."plugins_awaylist WHERE awayID=".$_POST['awayID']."");
		$row=mysqli_fetch_array($sql);
		if($userID!=$row['userID'] and !'ispageadmin') die($plugin_language['no_accses']);
		$startaway = $_POST[ 'startaway' ];
		$endaway = $_POST[ 'endaway' ];		

		safe_query("UPDATE ".PREFIX."plugins_awaylist SET startaway='".$startaway."', endaway='".$endaway."', comment='".$_POST['comment']."' WHERE awayID='".$_POST['awayID']."'");

		redirect("index.php?site=awaylist",$plugin_language['saved'],1);
}
elseif(isset($_GET['delete'])){
	$awayID = (int)$_GET['awayID'];
	$check = safe_query("SELECT * FROM ".PREFIX."plugins_awaylist WHERE awayID='".$awayID."'");
	$ds = mysqli_fetch_array($check);
	if(ispageadmin($userID) || ($loggedin && $userID==$ds['userID'])){
		safe_query("DELETE FROM ".PREFIX."plugins_awaylist WHERE awayID='".$awayID."'");
		redirect("index.php?site=awaylist",$plugin_language['to_delete'],1);
	}



}else {

	if(isset($_GET['type'])) $type=(int)$_GET['type'];
	if(isset($_GET['sort'])) $sort=(int)$_GET['sort'];
	if(isset($_GET['page'])) $page=(int)$_GET['page'];
	
	function clear($text) {
	    $text=strip_tags($text);
		$text=str_replace ("javascript:", "", $text);
		$text=stripslashes($text);
		
		return $text;
	}
	
	$alle = safe_query("SELECT userID FROM ".PREFIX."user");
	$gesamt = mysqli_num_rows($alle);
	$pages=1;
	if(!isset($page)) $page = 1;
			
	#$max=$maxusers;
	$max=10;

	for ($n=$max; $n<=$gesamt; $n+=$max) {
    if($gesamt>$n) $pages++;
  }


  if($pages>1) $page_link = makepagelink("index.php?site=registered_users", $page, $pages);
    else $page_link='';

  if ($page == "1") {
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_awaylist ORDER BY  startaway ASC LIMIT 0,$max");
    $n=1;
  }
  else {
    $start=$page*$max-$max;
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_awaylist ORDER BY  startaway ASC LIMIT $start,$max");
    $n = ($gesamt+1)-$page*$max+$max;
  } 

					
	
	
	
	$anz=mysqli_num_rows($ergebnis);
	if($anz) {
	
	$CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    

    		$data_array = array();
    		$data_array['$lang_nickname']=$plugin_language['nickname'];
			$data_array['$lang_contact']=$plugin_language['contact'];
			$data_array['$lang_from']=$plugin_language['from'];
			$data_array['$lang_to']=$plugin_language['to'];
			$data_array['$lang_action']=$plugin_language['action'];
			

			 
		
	    $template = $GLOBALS["_template"]->loadTemplate("awaylist","head", $data_array, $plugin_path);
		echo $template;

		echo'<a class="btn btn-success" href="index.php?site=awaylist&action=add"><i class="bi bi-plus" style="font-size: 1rem;"></i> '. $plugin_language[ 'set_me_away' ] . '</a><br><br>';

		$n=1;
		while($ds=mysqli_fetch_array($ergebnis)) {
		    
			$id=$ds['userID'];
			$nickname='<a href="index.php?site=profile&id='.$ds['userID'].'"><b>'.getnickname($ds['userID']).'</b></a>';
			
			if (isclanmember($userID)) {
                $member = '  <i class="bi bi-person" style="font-size: 1rem;color: #5cb85c" title="Clanmember"></i>';
            } else {
                $member = '';
            }

            if (getemail($userID) && !getemailhide($userID)) {
                $email = '<a href="mailto:' . mail_protect(getemail($userID)) . '">email</a>';
            } else {
                $email = '';
            }


			$pm = '';
        	if ($loggedin && $ds[ 'userID' ] != $userID) {
            	$pm = '<a href="index.php?site=messenger&amp;action=touser&amp;touser=' . $ds[ 'userID' ] . '"><i class="bi bi-chat" style="font-size: 1rem;"></i></a>';
            } else {
        		$pm = "n/a";
        
    		}
			
			
			$start = $ds[ 'startaway' ];
			$startaway = date('d.m.Y', strtotime(str_replace('-','/', $start))); 

			$end = $ds[ 'endaway' ];
			$endaway = date('d.m.Y', strtotime(str_replace('-','/', $end))); 

			$action='<a class="btn btn-info" href="index.php?site=awaylist&action=show&id='.$ds['awayID'].'" class="input">' . $plugin_language[ 'show' ] . '</a> ';
			if($userID==$ds['userID'] or ispageadmin($userID)) 
				$action.='<a class="btn btn-warning" href="index.php?site=awaylist&action=edit&id='.$ds['awayID'].'" class="input">' . $plugin_language[ 'edit' ] . '</a>

<a class="btn btn-danger" href="javascript:void(0)" onclick="MM_confirm(\''.$plugin_language['really_delete'].'\', \'index.php?site=awaylist&action&amp;delete=true&amp;awayID='.$ds['awayID'].'&amp;captcha_hash=' . $hash . '\')"><i class="bi bi-trash" style="font-size: 1rem;"></i> '.$plugin_language['delete'].'</a>';
		
			$data_array = array();

			$data_array['$nickname'] = $nickname;
			$data_array['$member'] = $member;
			$data_array['$email'] = $email;
			$data_array['$pm'] = $pm;
			$data_array['$startaway'] = $startaway;
			$data_array['$endaway'] = $endaway;
			$data_array['$action'] = $action;


			
			

			$template = $GLOBALS["_template"]->loadTemplate("awaylist","content", $data_array, $plugin_path);
			echo $template;
			$n++;
		}
		
    	$template = $GLOBALS["_template"]->loadTemplate("awaylist","foot", $data_array, $plugin_path);
		echo $template;

	}else{ echo'<a class="btn btn-success" href="index.php?site=awaylist&action=add"><i class="bibi-plus" style="font-size: 1rem;"></i> '. $plugin_language[ 'set_me_away' ] . '</a><br><br>'.$plugin_language['no_members_are_away'].'';
}
}
}
?>
