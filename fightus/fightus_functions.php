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
$doc = $_SERVER["DOCUMENT_ROOT"];
include(''.$doc.'/system/sql.php');
//ini_set('error_reporting', E_ALL|E_STRICT);
//ini_set('display_errors', 1);
$link = mysqli_connect($host, $user, $pwd, $db);
if( $link === FALSE ) {  
  die('mysql connection error: '.mysqli_error()); 
}

if(isset($_POST['action'])){
  $action = $_POST['action']; 
}else{
  $action = '';
}

if($action == 'getspielselect') {
	$ds = mysqli_fetch_array(mysqli_query($link,"SELECT * FROM " . PREFIX . "plugins_squads WHERE squadID='".$_POST['game']."'"));
	$query = mysqli_query($link,"SELECT * FROM ".PREFIX."plugins_fightus_spieleranzahl WHERE clanID='".$ds['squadID']."' AND clanID!='' ORDER BY name");
	$num = mysqli_num_rows($query);
	if($num) {
		$mselect = '<select name="spielanzahl" class="form-select"><option value=""  selected="selected">--- Please select ---</option>';
		while($row = mysqli_fetch_array($query)) {
			$mselect .= '<option value="'.$row['spielanzahlID'].'">'.$row['name'].'</option>';
		}
		$mselect .= '</select>';
	
	}
	else $mselect = '<div class="alert alert-warning" role="alert">No Game type available!</div>';
	
	echo $mselect;
	
}
elseif($action == 'getgameselect') {
	
	$ds = mysqli_fetch_array(mysqli_query($link,"SELECT * FROM " . PREFIX . "plugins_squads WHERE squadID='".$_POST['game']."'"));
	$query = mysqli_query($link,"SELECT * FROM ".PREFIX."plugins_fightus_gametype WHERE clanID='".$ds['squadID']."' AND clanID!='' ORDER BY name");
	$num = mysqli_num_rows($query);
	if($num) {
		$gameselect = '<select name="gametype" class="form-select"><option value="" selected="selected">--- Please select ---</option>';
		while($row = mysqli_fetch_array($query)) {
			$gameselect .= '<option value="'.$row['gametypeID'].'">'.$row['name'].'</option>';
		}
		$gameselect .= '</select>';
	
	}
	else $gameselect = '<div class="alert alert-warning" role="alert">No Gametype available!</div>';
	
	echo $gameselect;
	
}
elseif($action == 'getmatchselect') {
	
	$ds1 = mysqli_fetch_array(mysqli_query($link,"SELECT * FROM " . PREFIX . "plugins_squads WHERE squadID='".$_POST['game']."'"));
	$query = mysqli_query($link,"SELECT * FROM ".PREFIX."plugins_fightus_matchtype WHERE clanID='".$ds1['squadID']."' AND clanID!='' ORDER BY name");
	$num = mysqli_num_rows($query);
	if($num) {
		$matchselect = '<select  name="matchtype" class="form-select"><option value="" selected="selected">--- Please select ---</option>';
		while($row = mysqli_fetch_array($query)) {
			$matchselect .= '<option value="'.$row['matchtypeID'].'">'.$row['name'].'</option>';
		}
		$matchselect .= '</select>';
	
	}
	else $matchselect = '<div class="alert alert-warning" role="alert">No Matchtype available!</div>';
	
	echo $matchselect;
	
} elseif($action == 'getmapselect') {
	$ds = mysqli_fetch_array(mysqli_query($link,"SELECT * FROM " . PREFIX . "plugins_squads WHERE squadID='".$_POST['game']."'"));
	$query = mysqli_query($link,"SELECT * FROM ".PREFIX."plugins_fightus_maps WHERE tag='".$ds['tag']."' ORDER BY name");
	$num = mysqli_num_rows($query);
	if($num) {
		$mapselect = '<select name="map" class="form-select"><option value="" class="form-group" selected="selected">--- Please select ---</option>';
		while($row = mysqli_fetch_array($query)) {
			$mapselect .= '<option value="'.$row['mapID'].'">'.$row['name'].'</option>';
		}
		$mapselect .= '</select>';
	}
	else $mapselect = '<div class="alert alert-warning" role="alert">There are no maps for this game!</div>';
	
	echo $mapselect;
    echo '<input type="hidden" name="squad" value="'.$ds['squadID'].'">';
}
elseif($action == 'getmapsselect') {
	$ds = mysqli_fetch_array(mysqli_query($link,"SELECT * FROM " . PREFIX . "plugins_squads WHERE squadID='".$_POST['game']."'"));
	$query = mysqli_query($link,"SELECT * FROM ".PREFIX."plugins_fightus_maps WHERE tag='".$ds['tag']."' ORDER BY name");
	$num = mysqli_num_rows($query);
	if($num) {
		$mapsselect = '<select name="homemap" class="form-select"><option value="" selected="selected">--- Please select ---</option>';
		while($row = mysqli_fetch_array($query)) {
			$mapsselect .= '<option value="'.$row['mapID'].'">'.$row['name'].'</option>';
		}
		$mapsselect .= '</select>';
	}
	else $mapsselect = '<div class="alert alert-warning" role="alert">There are no maps for this game!" readonly>';
	
	echo $mapsselect;
}


function post($name, $filter = FILTER_DEFAULT) {
    return filter_input(INPUT_POST, $name, $filter);
}

function postInt($name) {
  return post($name, FILTER_VALIDATE_INT);
}

function validpost($value) {
  if(post($value)) {
    $vari = post($value);
  }else{	
    $vari = ''; 	
  }
  return $vari;
}
function validpost2($value,$reg='$reg') {
  if(post($value)) {
    $vari = '$'.$value.' = post('.$value.');';
  }else{	
    $vari = "$$value = '';"; 	
  }
  return $vari;
}

function checksession($value) {
  if(isset($_SESSION[$value])) {
    $vari = $_SESSION[$value];
  }else{	
    $vari = ''; 	
  }
  return $vari;
}

function getgametype() {
    $ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_fightus_gametype");
    while($ds=mysqli_fetch_array($ergebnis)) {
        if($gametype==$ds['gametypeID']) $gametype.='<option value="'.$ds["gametypeID"].'" selected="selected">'.$ds["name"].'</option>';
        else $gametype.='<option value="'.$ds["gametypeID"].'">'.$ds["name"].'</option>';
    }
    return $gametype;
}

function getgametype_ajax($clanID) {
    $ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_fightus_gametype WHERE clanID='$clanID' ORDER BY name");
    while($ds=mysqli_fetch_array($ergebnis)) {
        if($gametype==$ds['gametypeID']) $gametype.='<option value="'.$ds["gametypeID"].'" selected="selected">'.$ds["name"].'</option>';
        else $gametype.='<option value="'.$ds["gametypeID"].'">'.$ds["name"].'</option>';
    }
    return $gametype;
}

function getmaps() {
    $ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_fightus_maps");
    while($ds=mysqli_fetch_array($ergebnis)) {
        if($map==$ds['mapID']) $map.='<option value="'.$ds["pic"].'" selected="selected">'.$ds["name"].'</option>';
        else $map.='<option value="'.$ds["pic"].'">'.$ds["name"].'</option>';
    }
    return $map;
}

function getgametypename($gametypeID) {
    $ds=mysqli_fetch_array(safe_query("SELECT name FROM ".PREFIX."plugins_fightus_gametype WHERE gametypeID='$gametypeID'"));
    return $ds['name'];
}

function getmatchtype() {
    $ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_fightus_matchtype");
    while($ds=mysqli_fetch_array($ergebnis)) {
        if($gametype==$ds['matchtypeID']) $matchtype.='<option value="'.$ds["matchtypeID"].'" selected="selected">'.$ds["name"].'</option>';
        else $matchtype.='<option value="'.$ds["matchtypeID"].'">'.$ds["name"].'</option>';
    }
    return $matchtype;
}
function getmatchtype_ajax($clanID) {
    $ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_fightus_matchtype WHERE clanID='$clanID' ORDER BY name");
    while($ds=mysqli_fetch_array($ergebnis)) {
        if($gametype==$ds['matchtypeID']) $matchtype.='<option value="'.$ds["matchtypeID"].'" selected="selected">'.$ds["name"].'</option>';
        else $matchtype.='<option value="'.$ds["matchtypeID"].'">'.$ds["name"].'</option>';
    }
    return $matchtype;
}


function getmatchtypename($matchtypeID) {
    $ds=mysqli_fetch_array(safe_query("SELECT name FROM ".PREFIX."plugins_fightus_matchtype WHERE matchtypeID='$matchtypeID'"));
    return $ds['name'];
}

function getspielanzahl() {
    $ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_fightus_spieleranzahl");
    while($ds=mysqli_fetch_array($ergebnis)) {
        if($spielanzahl==$ds['spielanzahlID']) $spielanzahl.='<option value="'.$ds["spielanzahlID"].'" selected="selected">'.$ds["name"].'</option>';
        else $spielanzahl.='<option value="'.$ds["spielanzahlID"].'">'.$ds["name"].'</option>';
    }
    return $spielanzahl;
}
function getspielanzahl_ajax($clanID) {
    $ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_fightus_spieleranzahl WHERE clanID='$clanID' ORDER BY name");
    while($ds=mysqli_fetch_array($ergebnis)) {
        if($spielanzahl==$ds['spielanzahlID']) $spielanzahl.='<option value="'.$ds["spielanzahlID"].'" selected="selected">'.$ds["name"].'</option>';
        else $spielanzahl.='<option value="'.$ds["spielanzahlID"].'">'.$ds["name"].'</option>';
    }
    return $spielanzahl;
}

function getspielanzahlname($spielanzahlID) {
    $ds=mysqli_fetch_array(safe_query("SELECT name FROM ".PREFIX."plugins_fightus_spieleranzahl WHERE spielanzahlID='$spielanzahlID'"));
    return $ds['name'];
}

function getmapsname($mapID) {
    $ds=mysqli_fetch_array(safe_query("SELECT name FROM ".PREFIX."plugins_fightus_maps WHERE mapID='$mapID'"));
    return $ds['name'];
}
?>
