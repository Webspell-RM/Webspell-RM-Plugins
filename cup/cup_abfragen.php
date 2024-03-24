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
\¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*/



function getanordnung($id,$field) {
  $get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = $id");
  $dc = mysqli_fetch_assoc($get);
  $returnvalue = isset($dc[''.$field.'']) ? $dc[''.$field.''] : '';
  if ($field === 'banner') {
    if (isset($dc['banner']) == '') {
      $returnvalue = '0.png';
    } else {
      $returnvalue = $dc['banner'];
    }
  }
  return $returnvalue;
}

// Viertelfinale

function getviertel($id,$field,$id1,$id2) {
  $get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE viertel = $id && (anordnung = $id1 || anordnung = $id2)");
  $dc = mysqli_fetch_assoc($get);
  $returnvalue = isset($dc[''.$field.'']) ? $dc[''.$field.''] : '';
  if ($field === 'banner') {
    if (isset($dc['banner']) == '') {
      $returnvalue = '0.png';
    } else {
      $returnvalue = $dc['banner'];
    }
  }
  return $returnvalue;
}






// Halbfinale

function gethalb($id,$field,$id1, $id2) {
  $get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE halb = $id1 && gruppe = $id2");
  $dc = mysqli_fetch_assoc($get);
  $returnvalue = isset($dc[''.$field.'']) ? $dc[''.$field.''] : '';
  if ($field === 'banner') {
    if (isset($dc['banner']) == '') {
      $returnvalue = '0.png';
    } else {
      $returnvalue = $dc['banner'];
    }
  }
  return $returnvalue;
}




// Finale

function getfinal($id,$field,$id1, $id2) {
  $get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE finale = $id && (gruppe = $id1 || gruppe = $id2)");
  $dc = mysqli_fetch_assoc($get);
  $returnvalue = isset($dc[''.$field.'']) ? $dc[''.$field.''] : '';
  if ($field === 'banner') {
    if (isset($dc['banner']) == '') {
      $returnvalue = '0.png';
    } else {
      $returnvalue = $dc['banner'];
    }
  }
  return $returnvalue;
}


// Winner
function getwinner($id,$field) {
$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE p1 = 1");
$dc = mysqli_fetch_assoc($get);
$get = safe_query("SELECT preis1 FROM " . PREFIX . "plugins_cup_config");
$db = mysqli_fetch_assoc($get);
$preis1 = $db['preis1'];



  $returnvalue = isset($dc[''.$field.'']) ? $dc[''.$field.''] : '';
  
  if ($field === 'banner') {
    if (isset($dc['banner']) == '') {
      $returnvalue = '0.png';
    } else {
      $returnvalue = $dc['banner'];
    }
  }
  return $returnvalue;
}

function getplace_2($id,$field) {
$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE finale = 1 AND p1 = 0");
$dc = mysqli_fetch_assoc($get);
$get = safe_query("SELECT preis2 FROM " . PREFIX . "plugins_cup_config");
$db = mysqli_fetch_assoc($get);
$preis2 = $db['preis2'];

 $returnvalue = isset($dc[''.$field.'']) ? $dc[''.$field.''] : '';
  
  if ($field === 'banner') {
    if (isset($dc['banner']) == '') {
      $returnvalue = '0.png';
    } else {
      $returnvalue = $dc['banner'];
    }
  }
  return $returnvalue;
}

// Platz 3

function getplace_3($id,$field) {
$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE p3 = 1");
$dc = mysqli_fetch_assoc($get);
$get = safe_query("SELECT preis3 FROM " . PREFIX . "plugins_cup_config");
$db = mysqli_fetch_assoc($get);
$preis3 = $db['preis3'];

 $returnvalue = isset($dc[''.$field.'']) ? $dc[''.$field.''] : '';
  
  if ($field === 'banner') {
    if (isset($dc['banner']) == '') {
      $returnvalue = '0.png';
    } else {
      $returnvalue = $dc['banner'];
    }
  }
  return $returnvalue;
}



// Platz3 Winnner

function getplace3($id,$field,$id1, $id2) {
  $get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE halb = $id AND finale = 0 && (gruppe = $id1 || gruppe = $id2)");
  $dc = mysqli_fetch_assoc($get);
  $returnvalue = isset($dc[''.$field.'']) ? $dc[''.$field.''] : '';
  if ($field === 'banner') {
    if (isset($dc['banner']) == '') {
      $returnvalue = '0.png';
    } else {
      $returnvalue = $dc['banner'];
    }
  }
  return $returnvalue;
}




// Winner

//SEVEN INFO: empty($dc['name']); -> isset($dc['name']) ? $dc['name'] : '';
// AUSSER BANNER KANN SO BLEIBEN





// Config
$get = safe_query("SELECT * FROM " . PREFIX . "plugins_cup_config");
$dx = mysqli_fetch_assoc($get);

$gruppe = $dx['gruppe'];
$register = $dx['register'];
$turnier = $dx['turnier'];
$preis1 = $dx['preis1'];
$preis2 = $dx['preis2'];
$preis3 = $dx['preis3'];

//Gruppenanzeige
function display_groups($id) {

  if (getanordnung($id,'cupID')) {
      $output = '
          <img src=\'/includes/plugins/cup/images/team/'.getanordnung($id,'banner').'\' style=\'width: 54px;height: 30px;\' class=\'border\' />
          <span style=\'font-weight:bold; font-family:Arial; font-size:11px\' >'.getanordnung($id,'name').'</span>
          <br>
          <a style=\'font-family:tahoma; font-size:9px; font-weight:bold\' href=\''.getanordnung($id,'hp').'\' />'.getanordnung($id,'hp').'
          <br>
      ';
  } else {
      $output = '
          <div style=\'padding: 4px;margin-top:2px\'>
              <span>' . @$plugin_language[ 'free_space' ] . '</span>
          </div>
      ';
  }
  return $output;
}


?>