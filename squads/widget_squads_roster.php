<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
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
# 
$ergebnis = safe_query("SELECT squadID, icon, icon_small, name FROM " . PREFIX . "plugins_squads WHERE gamesquad = '1' ORDER BY sort");
if (mysqli_num_rows($ergebnis)) {
  $squadIDs = array();
  $BgPics = 'var BgPics = []; ';
  while ($db = mysqli_fetch_array($ergebnis)) {
    if (mysqli_num_rows(safe_query("SELECT userID FROM " . PREFIX . "plugins_squads_members WHERE squadID='" . $db['squadID'] . "'"))) {
      @$icon_small .= '<img onclick="changeViewedSquad(' . $db['squadID'] . ');" style="height: 80px; cursor: pointer; padding-left: 2px;" src="/includes/plugins/squads/images/squadicons/' . $db['icon_small'] . '" alt="' . $db['name'] . '" title="' . $db['name'] . '" />';
      $squadIDs[] = $db['squadID'];
      $BgPics .= 'BgPics[' . $db['squadID'] . '] = "' . $db['icon'] . '"; ';
    }
  }


  $data_array = array();
  $data_array['$icon_small'] = @$icon_small;
  $data_array['$firstsquad'] = @$squadIDs[0];
  $data_array['$BgPics'] = $BgPics;
  #$data_array['$position'] = $position;
  $template = $GLOBALS["_template"]->loadTemplate("switchsquads", "navi", $data_array, $plugin_path);
  echo $template;
}

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_squads WHERE gamesquad = '1' ORDER BY sort");
if (mysqli_num_rows($ergebnis)) {
  $n = 0;
  while ($db = mysqli_fetch_array($ergebnis)) {
    if ($n == 0) {
      $display = '';
    } else {
      $display = 'none;';
    }

    if ($db['icon']) {
      $icon = '/includes/plugins/squads/images/squadicons/' . $db['icon'] . '';
    } else {
      $icon = '/includes/plugins/squads/images/squadicons/no-image.jpg';
    }

    if (file_exists('images/squadicons/' . $db['squadID'] . '.jpg')) {
      $pic = '' . $db['squadID'] . '.jpg';
    } elseif (file_exists('images/squadicons/' . $db['squadID'] . '.jpeg')) {
      $pic = '' . $db['squadID'] . '.jpeg';
    } elseif (file_exists('images/squadicons/' . $db['squadID'] . '.png')) {
      $pic = '' . $db['squadID'] . '.png';
    } elseif (file_exists('images/squadicons/' . $db['squadID'] . '.gif')) {
      $pic = '' . $db['squadID'] . '.gif';
    } elseif (file_exists('images/squadicons/' . $db['squadID'] . '.avif')) {
      $pic = '' . $db['squadID'] . '.avif';
    } elseif (file_exists('images/squadicons/' . $db['squadID'] . '.webp')) {
      $pic = '' . $db['squadID'] . '.webp';
    } else {
      $pic = '';
    }

    echo '  <div class="switchsquad" id="squad_' . $db['squadID'] . '" style="display: ' . $display . '">
        <!--<div class="abouthead">' . $db['name'] . '</div>--><br>

<!--<div class="card-body row switchsquad_bg" style="background-image: linear-gradient(to bottom,grey 20%,black 80%),url(' . $icon . '); 
  background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-blend-mode:multiply;
  background-size: 80% auto; ">-->

<div class="card-body row switchsquad_bg" style="background-image: url(' . $icon . ');

  background-position: center;
  background-repeat: no-repeat;
  background-blend-mode:multiply;
  background-size: 130% auto; "">




<div class="col-12 squa1dpic">
  <h2>' . $db['name'] . '</h2>
   <div class="squad-lo1gos">
 


        ';
    $abfrage = safe_query("SELECT * FROM " . PREFIX . "plugins_squads_members WHERE squadID='" . $db['squadID'] . "' ORDER BY sort");
    if (mysqli_num_rows($abfrage)) {
      echo '<div id="squad-bar" class="partner-logos slider row" style="background: transparent">';
      while ($sm = mysqli_fetch_array($abfrage)) {
        $playerID = $sm['userID'];
        $position = $sm['position'];

        $pi = mysqli_fetch_array(safe_query("SELECT userpic FROM " . PREFIX . "user WHERE userID='" . $playerID . "'"));
        if ($pi['userpic']) {
          $userpic = '<a href="index.php?site=profile&amp;id=' . $playerID . '" title="' . getnickname($playerID) . '">
                      <div class="" 
                      style="margin: 5px;
                      background-image: url(/images/userpics/' . $pi['userpic'] . ');
background-size: cover;
  background-repeat: no-repeat;
  background-position: 50% 50%;
  border-radius: 3px;
  height: 200px;
  width: 150px;
  margin: 10px 0;
  position: relative
                      ">
                         


                         <h4 class="top" 
                         style="
                         color: #afadae;
    font-size: 20px;
    background: linear-gradient(90deg, rgba(15,15,15,1) 0%, rgba(15,15,15,0.23011211320465685) 100%);
    padding: 10px;
    border: 0px;
    height: 50px;
    position: absolute;
    margin-left: 0px;
    bottom: 150px;">' . getnickname($playerID) . '</h4>
                         


                         <h5 class="bottom"
                         style="
                         color: #afadae;
    font-size: 10px;
    text-align: rights;
    background: linear-gradient(90deg, rgba(15,15,15,1) 0%, rgba(15,15,15,0.23011211320465685) 100%);
    padding: 10px;
    border: 0px;
    position: absolute;
   left: 50%;
  bottom: 10px;;">' . $position . '</h5>
                      </div>
                   </a>';
        } else {
          $userpic = '
          <a href="index.php?site=profile&amp;id=' . $playerID . '" title="' . getnickname($playerID) . '">
                      <div class="" 
                      style="margin: 5px;
                      background-image: url(/includes/plugins/squads/images/nouserpic.png);
background-size: cover;
  background-repeat: no-repeat;
  background-position: 50% 50%;
  border-radius: 3px;
  height: 200px;
  width: 150px;
  margin: 10px 0;
  position: relative
                      ">
                         


                         <h4 class="top" 
                         style="
                         color: #afadae;
    font-size: 20px;
    background: linear-gradient(90deg, rgba(15,15,15,1) 0%, rgba(15,15,15,0.23011211320465685) 100%);
    padding: 10px;
    border: 0px;
    margin-left: 0px;
    bottom: 150px;">' . getnickname($playerID) . '</h4>
                         


                         <h5 class="bottom"
                         style="
                         color: #afadae;
    font-size: 10px;
    text-align: rights;
    background: linear-gradient(90deg, rgba(15,15,15,1) 0%, rgba(15,15,15,0.23011211320465685) 100%);
    padding: 10px;
    border: 0px;
    position: absolute;
   left: 50%;
  bottom: 10px;">' . $position . '</h5>
                      </div>
                   </a>';
        }



        $data_array = array();
        $data_array['$nickname'] = getnickname($playerID);
        $data_array['$playerID'] = $playerID;
        $data_array['$userpic'] =  $userpic;

        #$switchsquads_player = $GLOBALS["_template"]->replaceTemplate("switchsquads_player", $data_array);
        #echo $switchsquads_player;
        $template = $GLOBALS["_template"]->loadTemplate("switchsquads", "player", $data_array, $plugin_path);
        echo $template;
      }
    }
    echo '</div></div></div></div></div>';
    $n++;
  }
  echo '</div></div><br>';
}



?>