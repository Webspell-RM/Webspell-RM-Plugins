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

include("./../../../system/sql.php");
include("./../../../system/settings.php");

if(isset($_POST['action'])) {
  $action = $_POST['action']; 
} else {
  $action = '';
}

if(isset($_POST['game'])) {
  $getvari = $_POST['game']; 
} else {
  $getvari = '';
}

if($action == 'getopponentselect') {
  $erg = safe_query("SELECT * FROM ".PREFIX."plugins_clanwars WHERE opponent='".$getvari."'");
  $num = mysqli_num_rows($erg);
  if($num) {
    $ds = mysqli_fetch_array($erg);
    $returnselect = '<input class="form-control" id="opponentselect" type="text" name="opponent" size="60" maxlength="255" value="'.$ds['opponent'].'"/>';
  } else { 
    $returnselect = '<input class="form-control" id="opponentselect" type="text" name="opponent" size="60" maxlength="255" value="" />';
  }
  echo $returnselect;
}

if($action == 'getopptagselect') {
  $erg = safe_query("SELECT * FROM ".PREFIX."plugins_clanwars WHERE opponent='".$getvari."'");
  $num = mysqli_num_rows($erg);
  if($num) {
    $ds = mysqli_fetch_array($erg);
    $returnselect = '<input class="form-control" id="opptagselect" type="text" name="opptag" size="60" maxlength="255" value="'.$ds['opptag'].'"/>';
  } else { 
    $returnselect = '<input class="form-control" id="opptagselect" type="text" name="opptag" size="60" maxlength="255" value="" />';
  }
  echo $returnselect;
}


if($action == 'getopphpselect') {
  $erg = safe_query("SELECT * FROM ".PREFIX."plugins_clanwars WHERE opponent='".$getvari."'");
  $num = mysqli_num_rows($erg);
  if($num) {
    $ds = mysqli_fetch_array($erg);
    $returnselect = '<input class="form-control" id="opphpselect" type="text" name="opphp" size="60" maxlength="255" value="'.$ds['opphp'].'"/>';
  } else {  
    $returnselect = '<input class="form-control" id="opphpselect" type="text" name="opphp" size="60" maxlength="255" value=""/>';	
  }
  echo $returnselect;
}


if($action == 'getoppleagueselect') {
  $erg = safe_query("SELECT * FROM ".PREFIX."plugins_clanwars WHERE opponent='".$getvari."'");
  $num = mysqli_num_rows($erg);
  if($num) {
    $ds = mysqli_fetch_array($erg);
    $returnselect = '<input class="form-control" id="oppleagueselect" type="text" name="league" size="60" maxlength="255" value="'.$ds['league'].'"/>';
  } else { 
    $returnselect = '<input class="form-control" id="oppleagueselect" type="text" name="league" size="60" maxlength="255" value="" />';	
  }
  echo $returnselect;
}


if($action == 'getoppleaguehpselect') {
  $erg = safe_query("SELECT * FROM ".PREFIX."plugins_clanwars WHERE opponent='".$getvari."'");
  $num = mysqli_num_rows($erg);
  if($num) {
    $ds = mysqli_fetch_array($erg);
    $returnselect = '<input class="form-control" id="oppleaguehpselect" type="text" name="leaguehp" size="60" maxlength="255" value="'.$ds['leaguehp'].'"/>';
  } else { 
    $returnselect = '<input class="form-control" id="oppleaguehpselect" type="text" name="leaguehp" size="60" maxlength="255" value="" />';	
  }
  echo $returnselect;
}

?>



