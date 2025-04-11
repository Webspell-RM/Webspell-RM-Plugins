<?php
/*-----------------------------------------------------------------\
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
\------------------------------------------------------------------*/

# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("clanwars", $plugin_path);

$data_array = array();
$data_array['$title']=$plugin_language['upcoming_matches'];    
$data_array['$subtitle']='Clanwars';

$template = $GLOBALS["_template"]->loadTemplate("clanwars","sc_head", $data_array, $plugin_path);
echo $template;

echo'
  <div class="container">
';

$slider = 1; // 0 = slider disable, 1 = slider activ

if (isset($site)) $_language->readModule('clanwars');
$now = time();



$limit = "LIMIT 0,1";
if($slider=="1") {
  $limit = "";
  echo'<script type="text/javascript" src="./includes/plugins/clanwars/js/contentslider.js">
    /***********************************************
      * Featured Content Slider- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
      * This notice MUST stay intact for legal use
      * Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
    ***********************************************/
    </script>
    <div id="slider1" class="sliderwrapper">';
}
$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_clanwars WHERE date>= ".$now." AND displayed = '1' ORDER BY date ".$limit);
while($ds=mysqli_fetch_array($ergebnis)) {

$date = getformatdate($ds[ 'date' ]);
  $time = getformattime($ds[ 'time' ]);


  $ja=date("Y", $ds['date']);
  $mo=date("m", $ds['date']);
  $ta=date("d", $ds['date']);
  $st=date("H", $ds['time']);
  $mi=date("i", $ds['time']);
  
  $endTime = mktime($st, $mi-1, 60, $mo, $ta, $ja); //Stunde, Minute, Sekunde, Monat, Tag, Jahr;

  //Aktuellezeit des microtimestamps nach PHP5, für PHP4 muss eine andere Form genutzt werden.
  $timeNow = microtime(true);
  
  //Berechnet differenz von der Endzeit vom jetzigen Zeitpunkt aus.
  $diffTime = $endTime - $timeNow;
  //$diffTime = max(0, $endTime - $timeNow);

  
  
 
  //Berechnung für Tage, Stunden, Minuten
  $day = floor($diffTime / (24*3600));
  @$diffTime = $diffTime % (24*3600);
  $houre = floor($diffTime / (60*60));
  $diffTime = $diffTime % (60*60);
  $min = floor($diffTime / 60);
  $sec = $diffTime % 60;

  
  $squad = '<a href="index.php?site=squads&action=show&squadID=' . $ds[ 'squad' ] . '" target="_blank"><strong>' . getsquadname($ds['squad']) .'</strong></a>';
  $league = '<a href="' . getinput($ds[ 'leaguehp' ]) . '" target="_blank"><strong>' . $ds[ 'league' ] . '</strong></a>';
  $opponent = '<a href="' . getinput($ds[ 'opphp' ]) . '" target="_blank"><strong>' . $ds[ 'opptag' ] .'</strong></a>';
  $oppteam = $ds[ 'oppteam' ];
  $homescr = $ds[ 'homescore' ];
  $oppscr = $ds[ 'oppscore' ];

  if ($homescr > $oppscr) {
    $results = '<p class="text-success">' . $homescr . ':' . $oppscr . '</p>';
  } elseif ($homescr < $oppscr) {
    $results = '<p class="text-danger">' . $homescr . ':' . $oppscr . '</p>';
  } else {
    $results = '<p class="text-warning">' . $homescr . ':' . $oppscr . '</p>';
  }

  
        if(file_exists('./includes/plugins/squads/images/squadicons/'.$ds['squad'].'.jpg')){
            $teamicon='./includes/plugins/squads/images/squadicons/'.$ds['squad'].'.jpg';
        } elseif(file_exists('./includes/plugins/squads/images/squadicons/'.$ds['squad'].'.jpeg')){
            $teamicon='./includes/plugins/squads/images/squadicons/'.$ds['squad'].'.jpeg';
        } elseif(file_exists('./includes/plugins/squads/images/squadicons/'.$ds['squad'].'.png')){
            $teamicon='./includes/plugins/squads/images/squadicons/'.$ds['squad'].'.png';
        } elseif(file_exists('./includes/plugins/squads/images/squadicons/'.$ds['squad'].'.gif')){
            $teamicon='./includes/plugins/squads/images/squadicons/'.$ds['squad'].'.gif';
        } elseif(file_exists('./includes/plugins/squads/images/squadicons/'.$ds['squad'].'.avif')){
            $teamicon='./includes/plugins/squads/images/squadicons/'.$ds['squad'].'.avif';
		} elseif(file_exists('./includes/plugins/squads/images/squadicons/'.$ds['squad'].'.webp')){
            $teamicon='./includes/plugins/squads/images/squadicons/'.$ds['squad'].'.webp';
        } else{
           $teamicon='';
        }




        if(file_exists('./includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.jpg')){
            $squadicon='<img style="height: 100px" src="./includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.jpg" alt="">';
        } elseif(file_exists('./includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.jpeg')){
            $squadicon='<img style="height: 100px" src="./includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.jpeg" alt="">';
        } elseif(file_exists('./includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.png')){
            $squadicon='<img style="height: 100px" src="./includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.png" alt="">';
        } elseif(file_exists('./includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.gif')){
            $squadicon='<img style="height: 100px" src="./includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.gif" alt="">';
        } elseif(file_exists('./includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.avif')){
            $squadicon='<img style="height: 100px" src="./includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.avif" alt="">';
        } elseif(file_exists('./includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.webp')){
            $squadicon='<img style="height: 100px" src="./includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.webp" alt="">';
        } else{
           $squadicon='<img style="height: 100px" src="./includes/plugins/clanwars/images/no-image.jpg" alt="">';
        }

  if (!$ds[ 'opplogo'] == '') {
    $opppic = '<img style="height: 100px" src="./includes/plugins/clanwars/images/'.$ds[ 'opplogo'].'" alt="">';
  } else {
    $opppic = '<img style="height: 100px" src="./includes/plugins/clanwars/images/no-image.jpg" alt="">';
  }

 
  $ani_title = $ds[ 'ani_title' ];
  
  $data_array = array();
  $data_array['$time'] = $time;
  $data_array['$date'] = $date;
  $data_array['$ani_title'] = $ani_title;
  $data_array['$opppic'] = $opppic;
  $data_array['$date'] = $date;
  $data_array['$squad'] = $squad;
  $data_array['$teamicon'] = $teamicon;
  $data_array['$squadicon'] = $squadicon;
  $data_array['$opponent'] = $opponent;
  $data_array['$league'] = $league;
  $data_array['$results'] = $results;
  $data_array['$lang_description']=$plugin_language['description'];
  $data_array['$lang_report']=$plugin_language['report'];
  $data_array['$lang_league']=$plugin_language['league'];

  $data_array['$day'] = $day;
  $data_array['$houre'] = $houre;
  $data_array['$min'] = $min;
  $data_array['$sec'] = $sec;
  $data_array['$diffTime'] = $diffTime;
  

  if($slider=="1") echo'<div class="contentdiv col-sm-12">';
  $css = '<link href="'.$plugin_path.'css/clanwars.css" rel="stylesheet">';
  

  $template = $GLOBALS["_template"]->loadTemplate("clanwars","sc_content", $data_array, $plugin_path);
  echo $template;
  if($slider=="1") echo'</div>';
}
echo'</div>';
if($slider=="1") {
  
  $template = $GLOBALS["_template"]->loadTemplate("clanwars","sc_foot", array(), $plugin_path);
  echo $template;
echo '</div><br>';
  echo '
    <script type="text/javascript">
      featuredcontentslider.init({
        id: "slider1",  //id of main slider DIV
        contentsource: ["inline", ""],  //Valid values: ["inline", ""] or ["ajax", "path_to_file"]
        toc: "markup",  //Valid values: "#increment", "markup", ["label1", "label2", etc]
        nextprev: ["prev", "next"],  //labels for "prev" and "next" links. Set to "" to hide.
        revealtype: "click", //Behavior of pagination links to reveal the slides: "click" or "mouseover"
        enablefade: [false, 0.2],  //[true/false, fadedegree]
        autorotate: [false, 3000],  //[true/false, pausetime]
        onChange: function(previndex, curindex){  //event handler fired whenever script changes slide
          //previndex holds index of last slide viewed b4 current (1=1st slide, 2nd=2nd etc)
         //curindex holds index of currently shown slide (1=1st slide, 2nd=2nd etc)
       }
     })
   </script>
';

}
?>
