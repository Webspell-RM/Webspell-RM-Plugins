<?php
/*#################################################################\
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
\##################################################################*/

$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("fightus", $plugin_path);
include_once('fightus_functions.php');
if(isset($_GET['action'])){
  $action = $_GET['action']; 
}else{
  $action = '';
}

    $data_array = array();
    $data_array['$title']=$plugin_language['title'];
    $data_array['$subtitle']='Fight us';
    $template = $GLOBALS["_template"]->loadTemplate("fightus","head", $data_array, $plugin_path);
    echo $template;

if($action=="save") {
  $_SESSION['opponent'] = $_POST['opponent']; $_SESSION['opponents'] = $_POST['opponents']; $_SESSION['opphp'] = $_POST['opphp'];  
  $_SESSION['league'] = $_POST['league']; $_SESSION['server'] = $_POST['server']; $_SESSION['email'] = $_POST['email']; $_SESSION['info'] = $_POST['info'];
  $_SESSION['hour'] = $_POST['hour']; $_SESSION['minute'] = $_POST['minute']; $_SESSION['contact'] = $_POST['contact']; $_SESSION['messager'] = $_POST['messager']; $_SESSION['fightusmail'] = isset($_POST['fightusmail']);

  //Validere POST-Befehle
  $opponent = validpost('opponent');
  $opponents = validpost('opponents');
  $opphp = validpost('opphp');       
  $league = validpost('league');     
  $server = validpost('server');     
  $email = validpost('email');       
  $info = validpost('info');       
  $hour = validpost('hour');       
  $minute = validpost('minute');     
  $contact = validpost('contact');     
  $messager = validpost('messager');   
  $fightusmail = validpost('fightusmail');   
  $gametype = validpost('gametype');   
  $squad = validpost('squad');   
  $map = validpost('map');   
  $month = validpost('month');   
  $day = validpost('day');   
  $year = validpost('year');   
  $matchtype = validpost('matchtype');   
  $spielanzahl = validpost('spielanzahl');   
  $password = validpost('password');

  $error = array();
  if(!(trim($opponent))) $error[]= $plugin_language['wrongclan'];
  if(!(trim($opponents))) $error[]=$plugin_language['wrongtag'];
  if(!(validate_email($email))) $error[]=$plugin_language['wrongemail'];
  if(!(trim($messager))) $error[]=$plugin_language['wrongmess'];
  if(!(trim($opphp))) $error[]=$plugin_language['wronghp'];
  if(!(trim($server))) $error[]=$plugin_language['wrongserver'];
  if(!(trim($hour))) $error[]=$plugin_language['wrongstd'];
  if(!(trim($minute))) $error[]=$plugin_language['wrongmin'];
  if(!(trim($contact))) $error[]=$plugin_language['wrongname'];

  if($error){
    $gibfehler=implode('<br />&#8226; ',$error);
    $fehler = '<div class="alert alert-danger" role="alert">'.$plugin_language['detailerror'].'<br /> &#8226; '.$gibfehler.'</div><br />';
    $reg_opponent = checksession('opponent');
    $reg_opponents = checksession('opponents');
    $reg_opphp = checksession('opphp');
    $reg_league = checksession('league');
    $reg_server = checksession('server');
    $reg_email = checksession('email');
    $reg_info = checksession('info');       
    $reg_hour = checksession('hour');       
    $reg_minute = checksession('minute');     
    $reg_contact = checksession('contact');     
    $reg_messager = checksession('messager');     
    $reg_fightusmail = checksession('fightusmail'); 

    $make=mktime((int)$hour,(int)$minute,0,(int)$month,(int)$day,(int)$year);
    $type = isset($_GET['type']);
    $day = '';
    for($i=1; $i<32; $i++) {
      if($i==date("d", $make)) $day.='<option selected="selected">'.$i.'</option>';
      else $day.='<option>'.$i.'</option>';
    }
    
    $month = '';
    for($i=1; $i<13; $i++) {
      if($i==date("n", $make)) $month.='<option value="'.$i.'" selected="selected">'.date("M", $make).'</option>';
      else $month.='<option value="'.$i.'">'.date("M", mktime(0,0,0,$i,1,2000)).'</option>';
    }

    $year = '';
    for($i = date("Y")-1; $i < date("Y")+5; $i++) {
      if($i == date("Y", $make)) $year .= '<option value="' . $i . '" selected="selected">' . date("Y", $make) . '</option>';
      else $year .= '<option value="' . $i . '">' . $i . '</option>';
      
    }

    $gamesa = safe_query("SELECT * FROM " . PREFIX . "plugins_squads WHERE gamesquad='1'");
    $squads = '<option value="" selected="selected">'.$plugin_language['plsselect'].'</option>';
    while($ds = mysqli_fetch_array($gamesa)) {
      $squads .= '<option value="'. $ds['squadID'] .'">' . $ds['name'] . '</option>';
    }
    $squadss=str_replace('selected="selected"', "", $squads);
    $squadss=str_replace('value="'.$squad.'"', 'value="'.$squad.'" selected="selected"', $squads);
    if($squad!=="") $squadselect='<select name="squad" class="form-control" onchange="GetMapSelect(this.value) , GetMSelect(this.value), GetGameSelect(this.value), GetMatchSelect(this.value);">'.$squadss.'</select>';
    else $squadselect='<select name="squad" class="form-control" onchange="GetMapSelect(this.value) , GetMSelect(this.value), GetGameSelect(this.value), GetMatchSelect(this.value);">'.$squads.'</select>';
    
    unset($matchh);
    $ma = safe_query("SELECT * FROM " . PREFIX . "plugins_fight_us_matchtype WHERE clanID='$squad' ");
    $match = '<option value="" selected="selected">'.$plugin_language['plsselect'].'</option>';
    while($ds = mysqli_fetch_array($ma)) {
      $match .= '<option   value="'. $ds['matchtypeID'] .'">' . $ds['name'] . '</option>';
    }
    $matchh=str_replace('selected="selected"', "", $match);
    $matchh=str_replace('value="'.$matchtype.'"', 'value="'.$matchtype.'" selected="selected"', $match);
    if($matchtype=="") $matchselect='<div id="matchselect"><select name="matchtype" class="form-control">'.$matchh.'</select></div>';
    else $matchselect='<div id="matchselect"><select name="matchtype" class="form-control">'.$matchh.'</select></div>';
    
    unset($gamet);
    $ma = safe_query("SELECT * FROM " . PREFIX . "plugins_fight_us_gametype WHERE clanID='$squad' ");
    $gamet = '<option value="" selected="selected">'.$plugin_language['plsselect'].'</option>';
    while($ds = mysqli_fetch_array($ma)) {
      $gamet .= '<option   value="'. $ds['gametypeID'] .'">' . $ds['name'] . '</option>';
    }
    $gamett=str_replace('selected="selected"', "", $gamet);
    $gamett=str_replace('value="'.$gametype.'"', 'value="'.$gametype.'" selected="selected"', $gamet);
    
    if($gametype=="") $gameselect='<div id="gameselect"><select name="gametype" class="form-control">'.$gamett.'</select></div>';
    else $gameselect='<div id="gameselect"><select name="gametype" class="form-control">'.$gamett.'</select></div>';
    
    unset($spielt);
    $ma = safe_query("SELECT * FROM " . PREFIX . "plugins_fight_us_spieleranzahl WHERE clanID='$squad' ");
    $spielt = '<option value="" selected="selected">'.$plugin_language['plsselect'].'</option>';
    while($ds = mysqli_fetch_array($ma)) {
      $spielt .= '<option   value="'. $ds['spielanzahlID'] .'">' . $ds['name'] . '</option>';
    }
    $spieltt=str_replace('selected="selected"', "", $spielt);
    $spieltt=str_replace('value="'.$spielanzahl.'"', 'value="'.$spielanzahl.'" selected="selected"', $spielt);
    if($spielanzahl=="") $spieltypselect='<div id="mselect"><select name="spielanzahl" class="form-control">'.$spieltt.'</select></div>';
    else $spieltypselect='<div id="mselect"><select name="spielanzahl" class="form-control">'.$spieltt.'</select></div>';
    
    unset($mapt);
    $gg = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_squads WHERE squadID='$squad' AND gamesquad='1'"));
    $ma = safe_query("SELECT * FROM " . PREFIX . "plugins_fight_us_maps WHERE name='$gg[name]' ");
    $mapt = '<option value="" selected="selected">'.$plugin_language['plsselect'].'</option>';
    while($ds = mysqli_fetch_array($ma)) {
      $mapt .= '<option   value="'. $ds['mapID'] .'">' . $ds['name'] . '</option>';
    }
    $maptt=str_replace('selected="selected"', "", $mapt);
    $maptt=str_replace('value="'.$map.'"', 'value="'.$map.'" selected="selected"', $mapt);
    if($map=="") $mapselect='<div id="mapselect"><select name="map" class="form-control">'.$maptt.'</select></div>';
    else $mapselect='<div id="mapselect"><select name="map" class="form-control">'.$maptt.'</select></div>';

    if($reg_fightusmail) $reg_fightusmail='checked';
    else $reg_fightusmail='';

    $squadse=str_replace('selected="selected"', "", $squads);
    $squadse=str_replace('value="'.$ds['squadID'].'"', 'value="'.$ds['squadID'].'" selected="selected"', $squadse);
    #$countries = getcountries();


    $data_array = array();
    $data_array['$title'] = $plugin_language['title'];
    $data_array['$fehler'] = $fehler;
    $data_array['$day'] = $day;
    $data_array['$month'] = $month;
    $data_array['$year'] = $year;
    $data_array['$reg_hour'] = $reg_hour;
    $data_array['$reg_minute'] = $reg_minute;
    $data_array['$squadselect'] = $squadselect;
    $data_array['$matchselect'] = $matchselect;
    $data_array['$mapselect'] = $mapselect;
    $data_array['$gameselect'] = $gameselect;
    $data_array['$spieltypselect'] = $spieltypselect;
    $data_array['$reg_server'] = $reg_server;
    $data_array['$reg_opponent'] = $reg_opponent;
    $data_array['$reg_opponents'] = $reg_opponents;
    $data_array['$reg_opphp'] = $reg_opphp;
    $data_array['$reg_league'] = $reg_league;
    $data_array['$reg_contact'] = $reg_contact;
    $data_array['$reg_messager'] = $reg_messager;
    $data_array['$reg_email'] = $reg_email;
    #$data_array['$countries'] = $countries;
    $data_array['$reg_info'] = $reg_info;
    $data_array['$reg_fightusmail'] = $reg_fightusmail;
    $data_array['$reg_fightusmail'] = $reg_fightusmail;

    $data_array['$plang_date'] = $plugin_language['date'];
    $data_array['$plang_time'] = $plugin_language['time'];
    $data_array['$plang_messgroupe'] = $plugin_language['messgroupe'];
    $data_array['$plang_matcht'] = $plugin_language['matcht'];
    $data_array['$plang_gamet'] = $plugin_language['gamet'];
    $data_array['$plang_maps'] = $plugin_language['maps'];
    $data_array['$plang_spielt'] = $plugin_language['spielt'];
    $data_array['$plang_server'] = $plugin_language['server'];
    $data_array['$plang_messopptag'] = $plugin_language['messopptag'];
    $data_array['$plang_messopp'] = $plugin_language['messopp'];
    $data_array['$plang_hp'] = $plugin_language['hp'];
    $data_array['$plang_ligalink'] = $plugin_language['ligalink'];
    $data_array['$plang_messkontakt'] = $plugin_language['messkontakt'];
    $data_array['$plang_messmess'] = $plugin_language['messmess'];
    $data_array['$plang_email'] = $plugin_language['email'];
    $data_array['$plang_land'] = $plugin_language['land'];
    $data_array['$plang_finfo'] = $plugin_language['finfo'];
    $data_array['$plang_fcopy'] = $plugin_language['fcopy'];  
    $data_array['$plang_ffight'] = $plugin_language['ffight'];
    $data_array['$plang_fightdate'] = $plugin_language['fightdate'];
    $data_array['$plang_messinfo'] = $plugin_language['messinfo'];


    $template = $GLOBALS["_template"]->loadTemplate("fightus","challenge_new", $data_array, $plugin_path);
    echo $template;
  }
  if(!count($error)) {
    $make=mktime((int)$hour,(int)$minute,0,(int)$month,(int)$day,(int)$year);
    $fcwdate = date('d.m.Y - H:i', $make);
    $fsquad=getsquadname($squad);
    $fgametype=getgametypename($gametype);
    $fmatchtype=getmatchtypename($matchtype);
    $fspielanzahl=getspielanzahlname($spielanzahl);
    $fmap=getmapsname($map);
    if(isset($_POST['fightusmail'])=='1') {
      $subject =''.$plugin_language['fightusreq'].' '.PAGETITLE.'';
      $nachricht = ''.$plugin_language['mess1'].' '.$subject .' <br /><br />';
      $nachricht.= ''.$plugin_language['messdate'].' '.$fcwdate.' <br />';
      $nachricht.= ''.$plugin_language['messgroupe'].' '.$fsquad.' <br />';
      $nachricht.= 'Matchtype: '.$fmatchtype .' <br />';
      $nachricht.= 'Gametype: '.$fgametype .' <br />';
      $nachricht.= ''.$plugin_language['messspiel'].' '.$fspielanzahl .' <br />';
      $nachricht.= 'Map: '.$fmap.' <br />';
      $nachricht.= 'Server: '.$server.' <br />';
      $nachricht.= ''.$plugin_language['messopp'].' '.$opponent .' <br />';
      $nachricht.= ''.$plugin_language['messopptag'].' '.$opponents .' <br />';
      $nachricht.= 'Homepage: '.$opphp .' <br />';
      $nachricht.= ''.$plugin_language['messkontakt'].' '.$contact . '<br />';
      $nachricht.= ''.$plugin_language['messmess'].' '.$messager .' <br />';
      $nachricht.= 'E-M@il: '.$email.' <br />';
      $nachricht.= ''.$plugin_language['messinfo'].' '.$info .' <br />';
      $nachricht.= '<br />';

      #$maillanguage = new \webspell\Language();
      #$maillanguage->setLanguage($default_language);
      #$_language->readModule('formvalidation', true);

      $sendmail = \webspell\Email::sendEmail(
        $admin_email,
        'Fightus',
        $email,
        $subject,
        $nachricht
      );

      if ($sendmail['result'] == 'fail') {
        if (isset($sendmail['debug'])) {
          $fehler = array();
          $fehler[] = $sendmail['error'];
          $fehler[] = $sendmail['debug'];
          echo generateErrorBoxFromArray($plugin_languageuage['errors_there'], $fehler);
        }
      }
    }

    $ergebnis=safe_query("SELECT userID FROM ".PREFIX."plugins_squads_members WHERE warmember='1' AND squadID='".$squad."'");
    while($ds=mysqli_fetch_array($ergebnis)) {
      $touser[]=$ds['userID'];
    }
    if(isset($touser[0]) != "") {
      $email=$email;
      $message = ''.$plugin_language['fightuspm'].' [url=index.php?site=fightus]index.php?site=fightus[/url]
      ';
      foreach($touser as $id) {
        sendmessage($id,''.$plugin_language['newpm'].'',$message,$email);
      }
    }
    
    $date=time();
    $cwdate=mktime((int)$hour,(int)$minute,0,(int)$month,(int)$day,(int)$year);
    safe_query("
      INSERT INTO
        ".PREFIX."plugins_fight_us_challenge ( 
          date,
          cwdate,
          squadID, 
          opponent, 
          opphp, 
          league, 
          map, 
          server, 
          email, 
          info, 
          gametype, 
          matchtype,
          spielanzahl,
          messager,
          contact,
          opponents
        ) values (
          '$date', 
          '$cwdate', 
          '$squad', 
          '".mysqli_escape_string($_database, $opponent)."', 
          '".mysqli_escape_string($_database, $opphp)."', 
          '".mysqli_escape_string($_database, $league)."', 
          '$map', 
          '".mysqli_escape_string($_database, $server)."', 
          '".mysqli_escape_string($_database, $email)."', 
          '".mysqli_escape_string($_database, $info)."', 
          '$gametype', 
          '$matchtype', 
          '$spielanzahl', 
          '".mysqli_escape_string($_database, $messager)."', 
          '".mysqli_escape_string($_database, $contact)."', 
          '".mysqli_escape_string($_database, $opponents)."'
          ) 
    ");
    redirect(
      'index.php?site=fightus',
      generateSuccessBox(''.$plugin_language['thanks'].''),
      3
    );
    //session_destroy();
  }
} elseif($action=="delete") {
  if(intval($_GET['chID'])){
    if(isclanwarsadmin($userID)){
      $chID = $_GET['chID'];
      safe_query("DELETE FROM ".PREFIX."plugins_fight_us_challenge WHERE chID='$chID'");
      redirect(
        'index.php?site=fightus',
        generateSuccessBox(''.$plugin_language['deleted'].''),
        3
      );

    } else {
      $fehler[] = ''.$plugin_language['fighterror'].'';
      echo generateErrorBoxFromArray('Error', $fehler);
    }
  } else {
    $fehler[] = ''.$plugin_language['fighterror'].'';
    echo generateErrorBoxFromArray('Error', $fehler);
  }
} else {
  $fehler = '';
  //SESSIONS hOlen
  $reg_opponent = checksession('opponent');
  $reg_opponents = checksession('opponents');
  $reg_opphp = checksession('opphp');
  $reg_league = checksession('league');
  $reg_server = checksession('server');
  $reg_email = checksession('email');
  $reg_info = checksession('info');       
  $reg_hour = checksession('hour');       
  $reg_minute = checksession('minute');     
  $reg_contact = checksession('contact');     
  $reg_messager = checksession('messager');     
  $reg_fightusmail = checksession('fightusmail');       
  #$countries = getcountries();

  $day = '';
  for($i=1; $i<32; $i++) {
    if($i==date("d", time())) $day.='<option selected="selected">'.$i.'</option>';
    else $day.='<option>'.$i.'</option>';
  }
  $month = '';
  for($i=1; $i<13; $i++) {
    if($i==date("n", time())) $month.='<option value="'.$i.'" selected="selected">'.date("M", time()).'</option>';
    else $month.='<option value="'.$i.'">'.date("M", mktime(0,0,0,$i,1,2000)).'</option>';
  }
  $year = '';
  for($i=date("Y")-1; $i < date("Y")+5; $i++) {
    if($i == date("Y", time())) $year .= '<option value="' . $i . '" selected="selected">' . date("Y", time()) . '</option>';
    else $year .= '<option value="' . $i . '">' . $i . '</option>';
  }

  $gamesa = safe_query("SELECT * FROM " . PREFIX . "plugins_squads WHERE gamesquad='1'");
  $squads = '<option value="" selected="selected">'.$plugin_language['plsselect'].'</option>';
  while($ds = mysqli_fetch_array($gamesa)) {
    $squads .= '<option   value="'. $ds['squadID'] .'">' . $ds['name'] . '</option>';
  }
  $matchselect='<div class="control-label class="form-group"" id="matchselect"></div>';
  $gameselect='<div class="control-label class="form-group"" id="gameselect"></div>';
  $spieltypselect='<div class="control-label class="form-group"" id="mselect"></div>';
  $mapselect='<div class="control-label class="form-group"" id="mapselect"></div>';
  $squadselect='<select class="form-select" name="squad" onchange="GetMapSelect(this.value) , GetMSelect(this.value), GetGameSelect(this.value), GetMatchSelect(this.value);">'.$squads.'</select>';

  $data_array = array();
  $data_array['$title']=$plugin_language['title'];
  $data_array['$fehler'] = $fehler;
  $data_array['$day'] = $day;
  $data_array['$month'] = $month;
  $data_array['$year'] = $year;
  $data_array['$reg_hour'] = $reg_hour;
  $data_array['$reg_minute'] = $reg_minute;
  $data_array['$squadselect'] = $squadselect;
  $data_array['$matchselect'] = $matchselect;
  $data_array['$mapselect'] = $mapselect;
  $data_array['$gameselect'] = $gameselect;
  $data_array['$spieltypselect'] = $spieltypselect;
  $data_array['$reg_server'] = $reg_server;
  $data_array['$reg_opponent'] = $reg_opponent;
  $data_array['$reg_opponents'] = $reg_opponents;
  $data_array['$reg_opphp'] = $reg_opphp;
  $data_array['$reg_league'] = $reg_league;
  $data_array['$reg_contact'] = $reg_contact;
  $data_array['$reg_messager'] = $reg_messager;
  $data_array['$reg_email'] = $reg_email;
  #$data_array['$countries'] = $countries;
  $data_array['$reg_info'] = $reg_info;
  $data_array['$reg_fightusmail'] = $reg_fightusmail;

  $data_array['$plang_date'] = $plugin_language['date'];
  $data_array['$plang_time'] = $plugin_language['time'];
  $data_array['$plang_messgroupe'] = $plugin_language['messgroupe'];
  $data_array['$plang_matcht'] = $plugin_language['matcht'];
  $data_array['$plang_gamet'] = $plugin_language['gamet'];
  $data_array['$plang_maps'] = $plugin_language['maps'];
  $data_array['$plang_spielt'] = $plugin_language['spielt'];
  $data_array['$plang_server'] = $plugin_language['server'];
  $data_array['$plang_messopptag'] = $plugin_language['messopptag'];
  $data_array['$plang_messopp'] = $plugin_language['messopp'];
  $data_array['$plang_hp'] = $plugin_language['hp'];
  $data_array['$plang_ligalink'] = $plugin_language['ligalink'];
  $data_array['$plang_messkontakt'] = $plugin_language['messkontakt'];
  $data_array['$plang_messmess'] = $plugin_language['messmess'];
  $data_array['$plang_email'] = $plugin_language['email'];
  $data_array['$plang_land'] = $plugin_language['land'];
  $data_array['$plang_finfo'] = $plugin_language['finfo'];
  $data_array['$plang_fcopy'] = $plugin_language['fcopy'];
  $data_array['$plang_ffight'] = $plugin_language['ffight'];
  $data_array['$plang_fightdate'] = $plugin_language['fightdate'];
  $data_array['$plang_messinfo'] = $plugin_language['messinfo'];

  $template = $GLOBALS["_template"]->loadTemplate("fightus","challenge_new", $data_array, $plugin_path);
  echo $template;

  if($loggedin) {
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_fight_us_challenge ORDER BY date");
    $anz=mysqli_num_rows($ergebnis);
    echo'<div id="accordion">';
    if($anz) {
      while ($ds = mysqli_fetch_array($ergebnis)) {
        $actions='';
        if(isclanwarsadmin($userID)) {
          if(file_exists('./includes/plugins/calendar/admin/admin_calendar.php')){
            $actions.='<a href="./admin/admincenter.php?site=admin_calendar&amp;action=addwar&amp;chID='.$ds['chID'].'" class="hidden-xs hidden-sm btn btn-warning" type="button">'.$plugin_language['addtocalendar'].'</a>';
          }else { 
            $actions.='';
          }
        } else { 
          $actions.=''; 
        }
        if(isclanwarsadmin($userID)){
          $actions.='
            <a href="index.php?site=fightus&amp;action=delete&amp;chID='.$ds['chID'].'" class="hidden-xs hidden-sm btn btn-primary" type="button">'.$plugin_language['delfightus'].'</a>
          ';
        } else { 
          $actions.=''; 
        }
        $data_array = array();
        $data_array['$fehler'] = $fehler;
        $data_array['$cwdate'] = date('d.m.Y - H:i', $ds['cwdate']);
        $data_array['$squad'] = getsquadname($ds['squadID']);
        $data_array['$matchtype'] = getmatchtypename($ds['matchtype']);
        $data_array['$map'] = getmapsname($ds['map']);
        $data_array['$spielanzahl'] = getspielanzahlname($ds['spielanzahl']);
        $data_array['$server'] = $ds['server'];
        $data_array['$opponent'] = $ds['opponent'];
        $data_array['$opponents'] = $ds['opponents'];
        $data_array['$opphp'] = '<a href="'.$ds['opphp'].'" target="_blank">'.$ds['opphp'].'</a>';
        $data_array['$league'] = '<a href="'.$ds['league'].'" target="_blank">'.$ds['league'].'</a>';
        $data_array['$contact'] = $ds['contact'];
        $data_array['$messager'] = $ds['messager'];
        $data_array['$email'] = '<a href="mailto:'.$ds['email'].'">'.$ds['email'].'</a>';
        #$data_array['$countries'] = $countries;
        $data_array['$info'] = $ds['info'];
        $data_array['$gametype'] = getgametypename($ds['gametype']);
        $data_array['$actions'] = $actions;
        $data_array['$cwID'] = $ds['chID'];


        $data_array['$plang_date'] = $plugin_language['date'];
        $data_array['$plang_time'] = $plugin_language['time'];
        $data_array['$plang_messgroupe'] = $plugin_language['messgroupe'];
        $data_array['$plang_matcht'] = $plugin_language['matcht'];
        $data_array['$plang_gamet'] = $plugin_language['gamet'];
        $data_array['$plang_maps'] = $plugin_language['maps'];
        $data_array['$plang_spielt'] = $plugin_language['spielt'];
        $data_array['$plang_server'] = $plugin_language['server'];
        $data_array['$plang_messopptag'] = $plugin_language['messopptag'];
        $data_array['$plang_messopp'] = $plugin_language['messopp'];
        $data_array['$plang_hp'] = $plugin_language['hp'];
        $data_array['$plang_ligalink'] = $plugin_language['ligalink'];
        $data_array['$plang_messkontakt'] = $plugin_language['messkontakt'];
        $data_array['$plang_messmess'] = $plugin_language['messmess'];
        $data_array['$plang_email'] = $plugin_language['email'];
        $data_array['$plang_land'] = $plugin_language['land'];
        $data_array['$plang_finfo'] = $plugin_language['finfo'];
        $data_array['$plang_fcopy'] = $plugin_language['fcopy'];
        $data_array['$plang_ffigth'] = $plugin_language['ffight'];
        $data_array['$plang_fightdate'] = $plugin_language['fightdate'];
        $data_array['$plang_messinfo'] = $plugin_language['messinfo'];


        $template = $GLOBALS["_template"]->loadTemplate("fightus","challenges", $data_array, $plugin_path);
        echo $template;
        echo'<br />';
      }
      echo'</div>';
    } else {
       $data_array = array();
       $data_array['$info'] = $plugin_language['noentries'];
       $template = $GLOBALS["_template"]->loadTemplate("fightus","noticemessage", $data_array, $plugin_path);
       echo $template;
    }
  }
}
?>