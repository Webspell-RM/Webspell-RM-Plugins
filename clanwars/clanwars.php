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
global $userID;

$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("clanwars", $plugin_path);

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if ($action == "") {

$plugin_data= array();
$plugin_data['$title']=$plugin_language['clanwars'];    
$plugin_data['$subtitle']='Clanwars';

$template = $GLOBALS["_template"]->loadTemplate("clanwars","title", $plugin_data, $plugin_path);
echo $template;    


$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_clanwars WHERE displayed = '1' ORDER BY date DESC");




if(mysqli_num_rows($ergebnis)) {   
$data_array = array();
$data_array['$lang_date'] = $plugin_language[ 'date' ];
$data_array['$lang_squad'] = $plugin_language[ 'squad' ];
$data_array['$lang_result'] = $plugin_language[ 'result' ];
$data_array['$lang_opponent'] = $plugin_language[ 'opponent' ];
$data_array['$lang_league'] = $plugin_language[ 'league' ];
$data_array['$lang_details']=$plugin_language['details'];
$data_array['$lang_game']=$plugin_language['game'];

$template = $GLOBALS["_template"]->loadTemplate("clanwars","head", $data_array, $plugin_path);
echo $template; 

  while($ds=mysqli_fetch_array($ergebnis)) {
    $date = getformatdate($ds[ 'date' ]);
    $squad = getsquadname($ds['squad']);
    $squad = '<a href="index.php?site=squads&action=show&squadID=' . getinput($ds[ 'squad' ]) . '" target="_blank">' . getsquadname($ds['squad']) .'</a>';
    $league = '<a href="' . getinput($ds[ 'leaguehp' ]) . '" target="_blank">' . $ds[ 'league' ] . '</a>';
    $opponent = '<a href="' . getinput($ds[ 'opphp' ]) . '" target="_blank">' . $ds[ 'opponent' ] .'</a>';
    $oppteam = $ds[ 'oppteam' ];
    $homescr = $ds[ 'homescore' ];
    $hometeam = $ds[ 'hometeam' ];

    $maps = "";
    $hometeam = "";
    $score = "";
    $nbr = "";

    $homescr = array_sum(unserialize($ds[ 'homescore' ]));
    $oppscr = array_sum(unserialize($ds[ 'oppscore' ]));

    $scoreHome = unserialize($ds[ 'homescore' ]);
    $scoreOpp = unserialize($ds[ 'oppscore' ]);
    $homescr = array_sum($scoreHome);
    $oppscr = array_sum($scoreOpp);


    if ($homescr > $oppscr) {
        $result_map = '<h4 class="text-success">' . $homescr . ':' . $oppscr . '</h4>';
        $result_map2 = 'won';
    } elseif ($homescr < $oppscr) {
        $result_map = '<h4 class="text-danger">' . $homescr . ':' . $oppscr . '</h4>';
        $result_map2 = 'lost';
    } else {
        $result_map = '<h4 class="text-warning">' . $homescr . ':' . $oppscr . '</h4>';
        $result_map2 = 'draw';
    }


    if (!empty($ds[ 'hometeam' ])) {
        $array = unserialize($ds[ 'hometeam' ]);
        $n = 1;
        foreach ($array as $id) {
            if (!empty($id)) {
                if ($n > 1) {
                    $hometeam .= ', <a href="index.php?site=profile&amp;id=' . $id . '">' . getnickname($id) . '</a>';
                } else {
                    $hometeam .= '<a href="index.php?site=profile&amp;id=' . $id . '">' . getnickname($id) . '</a>';
                }
                $n++;
            }
        }
    }

        
    if ($homescr > $oppscr) {
        $results = '<h4 class="text-success">' . $homescr . ' : ' . $oppscr . '</h4>';
    } elseif ($homescr < $oppscr) {
        $results = '<h4 class="text-danger">' . $homescr . ' : ' . $oppscr . '</h4>';
    } else {
        $results = '<h4 class="text-warning">' . $homescr . ' : ' . $oppscr . '</h4>';
    }  

    if ($homescr > $oppscr) {
        $ges_results = '<h4 class="text-success">' . $homescr . ' : ' . $oppscr . '</h4>';
    } elseif ($homescr < $oppscr) {
        $ges_results = '<h4 class="text-danger">' . $homescr . ' : ' . $oppscr . '</h4>';
    } else {
        $ges_results = '<h4 class="text-warning">' . $homescr . ' : ' . $oppscr . '</h4>';
    }          

    $date = getformatdate($ds[ 'date' ]);        
    $league = '<a href="' . $ds[ 'leaguehp' ] . '" target="_blank" data-toggle="tooltip" data-bs-html="true" title="' . $ds[ 'league' ] . '">' . $ds[ 'league' ] . '</a>';

    $details = '<a style="margin-top: 10px;" type="button" class="btn btn-secondary" href="index.php?site=clanwars&action=clanwars_details&amp;cwID=' . $ds[ 'cwID' ] .
                    '"">' . $plugin_language[ 'clanwar_details' ] . '</a>';   

    $homescr = array_sum(unserialize($ds[ 'homescore' ]));
    $oppscr = array_sum(unserialize($ds[ 'oppscore' ]));

    $ani_title = $ds[ 'ani_title' ];

    if ($ds[ 'game' ] == "CO") {
        $game = "HL";
    } else {
        $game = $ds[ 'game' ];
    }

    $showgame = getgamename($ds[ 'game' ]);
    
    if(file_exists('includes/plugins/games_pic/images/'.$ds['game'].'.jpg')){
        $gameicon='../includes/plugins/games_pic/images/'.$ds['game'].'.jpg';
    } elseif(file_exists('includes/plugins/games_pic/images/'.$ds['game'].'.jpeg')){
        $gameicon='../includes/plugins/games_pic/images/'.$ds['game'].'.jpeg';
    } elseif(file_exists('includes/plugins/games_pic/images/'.$ds['game'].'.png')){
        $gameicon='../includes/plugins/games_pic/images/'.$ds['game'].'.png';
    } elseif(file_exists('includes/plugins/games_pic/images/'.$ds['game'].'.gif')){
        $gameicon='../includes/plugins/games_pic/images/'.$ds['game'].'.gif';
    } else{
       $gameicon='../includes/plugins/games_pic/images/no-image.jpg';
    }


    if($ds[ 'opplogo' ] == '') {
        $opppic = '<img style="height: 50px" src="./includes/plugins/clanwars/images/no-image.jpg" alt="" data-toggle="tooltip" data-bs-html="true" title="' . $ds[ 'opponent' ] . '">';
    } else {
        $opppic = '<img style="height: 50px" align="center" src="./includes/plugins/clanwars/images/'. $ds[ 'opplogo' ] .'" alt="{img}"  
          data-toggle="tooltip" data-bs-html="true" title="' . $ds[ 'opponent' ] . '">';
    }

    if(file_exists('includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.jpg')){
        $squadicon='<img style="height: 50px" src="../includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.jpg" alt="" data-toggle="tooltip" data-bs-html="true" title="' . getsquadname($ds['squad']) .'">';
    } elseif(file_exists('includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.jpeg')){
        $squadicon='<img style="height: 50px" src="../includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.jpeg" alt="" data-toggle="tooltip" data-bs-html="true" title="' . getsquadname($ds['squad']) .'">';
    } elseif(file_exists('includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.png')){
        $squadicon='<img style="height: 50px" src="../includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.png" alt="" data-toggle="tooltip" data-bs-html="true" title="' . getsquadname($ds['squad']) .'">';
    } elseif(file_exists('includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.gif')){
        $squadicon='<img style="height: 50px" src="../includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.gif" alt="" data-toggle="tooltip" data-bs-html="true" title="' . getsquadname($ds['squad']) .'">';
    } else{
       $squadicon='<img style="height: 50px" src="../includes/plugins/squads/images/squadicons/no-image.jpg" alt="" data-toggle="tooltip" data-bs-html="true" title="' . getsquadname($ds['squad']) .'">';
    }

    $games="";   
    $gamesa=safe_query("SELECT name FROM ".PREFIX."plugins_games_pic WHERE tag='$game'");
    while($dv=mysqli_fetch_array($gamesa)) {
        $games=$dv['name'];
    }

    $data_array = array();
    $data_array['$game'] = $games;
    $data_array['$gameicon'] = $gameicon;
    $data_array['$hometeam'] = $hometeam;
    $data_array['$ani_title'] = $ani_title;
    $data_array['$opppic'] = $opppic;
    $data_array['$date'] = $date;
    $data_array['$squad'] = $squad;
    $data_array['$squadicon'] = $squadicon;
    $data_array['$opponent'] = $opponent;
    $data_array['$league'] = $league;
    $data_array['$results'] = $results;
    $data_array['$details'] = $details;

    $data_array['$lang_description']=$plugin_language['description'];
    $data_array['$clanwar_details']=$plugin_language['clanwar_details'];

    $template = $GLOBALS["_template"]->loadTemplate("clanwars","content", $data_array, $plugin_path);
    echo $template;
    
    }
    $template = $GLOBALS["_template"]->loadTemplate("clanwars","foot", $data_array, $plugin_path);
    echo $template;
    } else {
        echo $plugin_language[ 'no_clanwar' ];
    }

} elseif ($action == "clanwars_details") {

    $plugin_data= array();
    $plugin_data['$title']=$plugin_language['clanwars'] .' '. $plugin_language['clanwar_details'];    
    $plugin_data['$subtitle']='Clanwars Details';

    $template = $GLOBALS["_template"]->loadTemplate("clanwars_details","title", $plugin_data, $plugin_path);
    echo $template;

    ############################
    $cwID = (int)$_GET[ 'cwID' ];
    $ds = mysqli_fetch_array(safe_query("SELECT * FROM `" . PREFIX . "plugins_clanwars` WHERE `cwID` = '" . (int)$cwID."'"));
    $date = getformatdate($ds[ 'date' ]);
    $squad = getsquadname($ds['squad']);
    $squad = '<a href="index.php?site=squads&action=show&squadID=' . getinput($ds[ 'squad' ]) . '" target="_blank">' . getsquadname($ds['squad']) .'</a>';
    $opponent = '<a href="' . getinput($ds[ 'opphp' ]) . '" target="_blank"><b>' . getinput($ds[ 'opptag' ]) . ' / ' .
                ($ds[ 'opponent' ]) . '</b></a>';
    $opp_team = '<a href="' . getinput($ds[ 'opphp' ]) . '" target="_blank"><b>' .
                ($ds[ 'opponent' ]) . '</b></a>';            
    $league = '<a href="' . getinput($ds[ 'leaguehp' ]) . '" target="_blank">' . getinput($ds[ 'league' ]) . '</a>';

    $homescr = array_sum(unserialize($ds[ 'homescore' ]));
    $oppscr = array_sum(unserialize($ds[ 'oppscore' ]));

    if ($homescr > $oppscr) {
        $results_1 = '<h3 class="text-success">' . $homescr . '</h3>';
        $results_2 = '<h3 class="text-danger">' . $oppscr . '</h3>';
        $results_3 = '<span class="text-success">'.$plugin_language[ 'won' ].'</span>';
    } elseif ($homescr < $oppscr) {
        $results_1 = '<h3 class="text-danger">' . $homescr . '</h3>';
        $results_2 = '<h3 class="text-success">' . $oppscr . '</h3>';
        $results_3 = '<span class="text-danger">'.$plugin_language[ 'lost' ].'</span>';
    } else {
        $results_1 = '<h3 class="text-warning">' . $homescr . '</h3>';
        $results_2 = '<h3 class="text-warning">' . $oppscr . '</h3>';
        $results_3 = '<span class="text-warning">'.$plugin_language[ 'draw' ].'</span>';
    }

    if($ds[ 'opplogo' ] == '') {
        $opppic = '<img style="height: 150px" src="./includes/plugins/clanwars/images/no-image.jpg" alt="" data-toggle="tooltip" data-bs-html="true" title="' . $ds[ 'opponent' ] . '">';
    } else {
        $opppic = '<img style="height: 150px" align="center" src="./includes/plugins/clanwars/images/'. $ds[ 'opplogo' ] .'" alt="{img}"  
          data-toggle="tooltip" data-bs-html="true" title="' . $ds[ 'opponent' ] . '">';
    }

    if(file_exists('includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.jpg')){
        $squadicon='<img style="height: 150px" src="../includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.jpg" alt="" data-toggle="tooltip" data-bs-html="true" title="' . getsquadname($ds['squad']) .'">';
    } elseif(file_exists('includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.jpeg')){
        $squadicon='<img style="height: 150px" src="../includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.jpeg" alt="" data-toggle="tooltip" data-bs-html="true" title="' . getsquadname($ds['squad']) .'">';
    } elseif(file_exists('includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.png')){
        $squadicon='<img style="height: 150px" src="../includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.png" alt="" data-toggle="tooltip" data-bs-html="true" title="' . getsquadname($ds['squad']) .'">';
    } elseif(file_exists('includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.gif')){
        $squadicon='<img style="height: 150px" src="../includes/plugins/squads/images/squadicons/'.$ds['squad'].'_small.gif" alt="" data-toggle="tooltip" data-bs-html="true" title="' . getsquadname($ds['squad']) .'">';
    } else{
       $squadicon='<img style="height: 150px" src="../includes/plugins/squads/images/squadicons/no-image.jpg" alt="" data-toggle="tooltip" data-bs-html="true" title="' . getsquadname($ds['squad']) .'">';
    }

    $linkpage = $ds[ 'linkpage' ];
    $linkpage = str_replace('http://', '', $ds[ 'linkpage' ]);
    if ($linkpage == "") {
        $linkpage = "#";
    }

    $data_array= array();
    $data_array['$date'] = $date;
    $data_array['$squad'] = $squad;
    $data_array['$squadicon'] = $squadicon;
    $data_array['$opp_team'] = $opp_team;
    $data_array['$opppic'] = $opppic;
    $data_array['$opponent'] = $opponent;
    $data_array['$league'] = $league;
    $data_array['$linkpage'] = $linkpage;
    $data_array['$results_1'] = $results_1;
    $data_array['$results_2'] = $results_2;
    $data_array['$results_3'] = $results_3;

    $template = $GLOBALS["_template"]->loadTemplate("clanwars_details","match_details", $data_array, $plugin_path);
    echo $template;


    ############################
    $cwID = (int)$_GET[ 'cwID' ];
    $ds = mysqli_fetch_array(safe_query("SELECT * FROM `" . PREFIX . "plugins_clanwars` WHERE `cwID` = '" . (int)$cwID."'"));
    $data_array= array();
    $template = $GLOBALS["_template"]->loadTemplate("clanwars_details","match_head", $data_array, $plugin_path);
    echo $template;

    $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_clanwars` WHERE `opponent` = '" . $ds[ 'opponent' ]."'");
    while($dx=mysqli_fetch_array($ergebnis)) {

        $homescr = array_sum(unserialize($dx[ 'homescore' ]));
        $oppscr = array_sum(unserialize($dx[ 'oppscore' ]));

        if ($homescr > $oppscr) {
            $results_1 = '<span class="text-success">' . $homescr . '</span>';
            $results_2 = '<span class="text-danger">' . $oppscr . '</span>';
            $results_3 = '<span class="text-success">'.$plugin_language[ 'won' ].'</span>';
        } elseif ($homescr < $oppscr) {
            $results_1 = '<span class="text-danger">' . $homescr . '</span>';
            $results_2 = '<span class="text-success">' . $oppscr . '</span>';
            $results_3 = '<span class="text-danger">'.$plugin_language[ 'lost' ].'</span>';
        } else {
            $results_1 = '<span class="text-warning">' . $homescr . '</span>';
            $results_2 = '<span class="text-warning">' . $oppscr . '</span>';
            $results_3 = '<span class="text-warning">'.$plugin_language[ 'draw' ].'</span>';
        }

        $opponent = $dx[ 'opponent' ];
        $date = getformatdate($dx[ 'date' ]);
        $squad = getsquadname($dx['squad']);

        $data_array = array();
        $data_array['$date'] = $date;
        $data_array['$squad'] = $squad;
        $data_array['$opponent'] = $opponent;
        $data_array['$results_1'] = $results_1;
        $data_array['$results_2'] = $results_2;
        $data_array['$results_3'] = $results_3;

        $template = $GLOBALS["_template"]->loadTemplate("clanwars_details","match_content", $data_array, $plugin_path);
        echo $template;

    }    
    $template = $GLOBALS["_template"]->loadTemplate("clanwars_details","match_foot", $data_array, $plugin_path);
    echo $template;
    #############################   

    $cwID = (int)$_GET[ 'cwID' ];
    $ds = mysqli_fetch_array(safe_query("SELECT * FROM `" . PREFIX . "plugins_clanwars` WHERE `cwID` = '" . (int)$cwID."'"));
    $date = getformatdate($ds[ 'date' ]);
    $squad = getsquadname($ds['squad']);
    $squad = '<a href="index.php?site=squads&action=show&squadID=' . getinput($ds[ 'squad' ]) . '" target="_blank">' . getsquadname($ds['squad']) .'</a>';
    $opponent = '<a href="' . getinput($ds[ 'opphp' ]) . '" target="_blank"><b>' . getinput($ds[ 'opptag' ]) . ' / ' .
                ($ds[ 'opponent' ]) . '</b></a>';
    $opp_team = '<a href="' . getinput($ds[ 'opphp' ]) . '" target="_blank"><b>' .
                ($ds[ 'opponent' ]) . '</b></a>';            
    $league = '<a href="' . getinput($ds[ 'leaguehp' ]) . '" target="_blank">' . getinput($ds[ 'league' ]) . '</a>';

    if (file_exists('images/games/'.$ds['game'].'.jpg')) {
        $game_ico = './images/games/'.$ds['game'].'.jpg';
        $game = '<img style="width: 100px" src="' . $game_ico . '" alt="">';
    } elseif(file_exists('images/games/'.$ds['game'].'.jpeg')){
        $game_ico='./images/games/'.$ds['game'].'.jpeg';
        $game = '<img style="width: 100px" src="' . $game_ico . '" alt="">';
    } elseif(file_exists('images/games/'.$ds['game'].'.png')){
        $game_ico='./images/games/'.$ds['game'].'.png';
        $game = '<img style="width: 100px" src="' . $game_ico . '" alt="">';
    } elseif(file_exists('images/games/'.$ds['game'].'.gif')){
        $game_ico='./images/games/'.$ds['game'].'.gif';
        $game = '<img style="width: 100px" src="' . $game_ico . '" alt="">';
    } else {
        $game = $ds[ 'game' ];
    }     

    $maps = "";
    $hometeam = "";
    $screens = "";
    $score = "";
    $extendedresults = "";
    $screenshots = "";
    $nbr = "";

    $homescr = array_sum(unserialize($ds[ 'homescore' ]));
    $oppscr = array_sum(unserialize($ds[ 'oppscore' ]));
    $theMaps = unserialize($ds[ 'maps' ]);

    if (is_array($theMaps)) {
        $n = 1;
        foreach ($theMaps as $map) {
            if ($n == 1) {
                $maps .= $map;
            } else {
                if ($map == '') {
                    $maps = $plugin_language[ 'no_maps' ];
                } else {
                    $maps .= ', ' . $map;
                }
            }
            $n++;
        }
    }

    if ($homescr > $oppscr) {
        $results_1 = '<h3 class="text-success">' . $homescr . '</h3>';
        $results_2 = '<h3 class="text-danger">' . $oppscr . '</h3>';
    } elseif ($homescr < $oppscr) {
        $results_1 = '<h3 class="text-danger">' . $homescr . '</h3>';
        $results_2 = '<h3 class="text-success">' . $oppscr . '</h3>';
    } else {
        $results_1 = '<h3 class="text-warning">' . $homescr . '</h3>';
        $results_2 = '<h3 class="text-warning">' . $oppscr . '</h3>';
    }

    $report = $ds[ 'report' ];

    if ($report == "") {
        $report = "n/a";
    }

    $squad = '<a href="index.php?site=clanwars&amp;action=showonly&amp;only=squad&amp;id=' . $ds[ 'squad' ] . '"><b>' .
    getsquadname($ds[ 'squad' ]) . '</b></a>';

    $opptag = getinput($ds[ 'opptag' ]);
    $oppteam = getinput($ds[ 'oppteam' ]);
    $server = getinput($ds[ 'server' ]);
    $hltv = getinput($ds[ 'hltv' ]);

    if (!empty($ds[ 'hometeam' ])) {
        $array = unserialize($ds[ 'hometeam' ]);
        $n = 1;
        foreach ($array as $id) {
            if (!empty($id)) {
                if ($n > 1) {
                    $hometeam .= ', <a href="index.php?site=profile&amp;id=' . $id . '">' . getnickname($id) . '</a>';
                } else {
                    $hometeam .= '<a href="index.php?site=profile&amp;id=' . $id . '">' . getnickname($id) . '</a>';
                }
                $n++;
            }
        }
    }
    $screenshots = '';
    if (!empty($ds[ 'screens' ])) {
        $screens = explode("|", $ds[ 'screens' ]);
    }
    $screenshots = '';
    if (!empty($ds[ 'screens' ])) {
        $screens = explode("|", $ds[ 'screens' ]);
    }
    if (is_array($screens)) {
        $n = 1;
        foreach ($screens as $screen) {
            if (!empty($screen)) {
                $screenshots .= '<a href="/includes/plugins/clanwars/images/clanwar-screens/' . $screen .
                '" target="_blank"><img src="/includes/plugins/clanwars/images/clanwar-screens/' . $screen .
                '" width="150" height="100" style="padding-top:3px; padding-right:3px;" alt=""></a>';
                if ($nbr == 2) {
                    $nbr = 1;
                    $screenshots .= '<br>';
                } else {
                    $nbr = 2;
                }
                $n++;
            }
        }
    }

    if (!(mb_strlen(trim($screenshots)))) {
        $screenshots = $plugin_language[ 'no_screenshots' ];
    }

    $linkpage = $ds[ 'linkpage' ];
    $linkpage = str_replace('http://', '', $ds[ 'linkpage' ]);
    if ($linkpage == "") {
        $linkpage = "#";
    }

        // -- v1.0, extended results -- //

    $scoreHome = unserialize($ds[ 'homescore' ]);
    $scoreOpp = unserialize($ds[ 'oppscore' ]);
    $homescr = array_sum($scoreHome);
    $oppscr = array_sum($scoreOpp);

    if ($homescr > $oppscr) {
        $result_map = '<h1 class="text-success">' . $homescr . '</h1>:<h1 class="text-danger">' . $oppscr . '</h1>';
        $result_map2 = 'won';
    } elseif ($homescr < $oppscr) {
        $result_map = '<h1 class="text-danger">' . $homescr . '</h1>:<h1 class="text-success">' . $oppscr . '</h1>';
        $result_map2 = 'lost';
    } else {
        $result_map = '<h1 class="text-warning">' . $homescr . ':' . $oppscr . '</h1>';
        $result_map2 = 'draw';
    }

    if (is_array($theMaps)) {
        $d = 0;
        foreach ($theMaps as $map) {
            $score = '';
            if ($scoreHome[ $d ] > $scoreOpp[ $d ]) {
                $score_1 = '<span class="text-success">' . $scoreHome[ $d ] . '</span>';
                $score_2 = '<span class="text-danger">' . $scoreOpp[ $d ] . '</span>';
            } elseif ($scoreHome[ $d ] < $scoreOpp[ $d ]) {
                $score_1 = '<span class="text-danger">' . $scoreHome[ $d ] . '</span>';
                $score_2 = '<span class="text-success">' . $scoreOpp[ $d ] . '</span>';
            } else {
                $score_1 = '<span class="text-warning">' . $scoreHome[ $d ] . '</span>';
                $score_2 = '<span class="text-warning">' . $scoreOpp[ $d ] . '</span>';
            }

            $data_array = array();
            $data_array['$map'] = $map;
            $data_array['$score_1'] = $score_1;
            $data_array['$score_2'] = $score_2;
            
            $clanwars_details_results = $GLOBALS["_template"]->loadTemplate("clanwars_details","content_results", $data_array, $plugin_path);
            $extendedresults .= $clanwars_details_results;
            unset($score);
            $d++;
        }
    } else {
        $extendedresults = '';
    }

    if($ds[ 'opplogo' ] == '') {
      $opppic = '<img style="height: 150px" src="./includes/plugins/clanwars/images/no-image.jpg" alt="" data-toggle="tooltip" data-bs-html="true" title="' . $ds[ 'opponent' ] . '">';
    } else {
      $opppic = '<img style="height: 150px" align="center" src="./includes/plugins/clanwars/images/'. $ds[ 'opplogo' ] .'" alt="{img}"  
      data-toggle="tooltip" data-bs-html="true" title="' . $ds[ 'opponent' ] . '">';
    }

    if(file_exists('images/squadicons/'.$ds['squad'].'_small.jpg')){
        $squadicon='<img style="height: 150px" src="./images/squadicons/'.$ds['squad'].'_small.jpg" alt="" data-toggle="tooltip" data-bs-html="true" title="' . getsquadname($ds['squad']) .'">';
    } elseif(file_exists('images/squadicons/'.$ds['squad'].'_small.jpeg')){
        $squadicon='<img style="height: 150px" src="./images/squadicons/'.$ds['squad'].'_small.jpeg" alt="" data-toggle="tooltip" data-bs-html="true" title="' . getsquadname($ds['squad']) .'">';
    } elseif(file_exists('images/squadicons/'.$ds['squad'].'_small.png')){
        $squadicon='<img style="height: 150px" src="./images/squadicons/'.$ds['squad'].'_small.png" alt="" data-toggle="tooltip" data-bs-html="true" title="' . getsquadname($ds['squad']) .'">';
    } elseif(file_exists('images/squadicons/'.$ds['squad'].'_small.gif')){
        $squadicon='<img style="height: 150px" src="./images/squadicons/'.$ds['squad'].'_small.gif" alt="" data-toggle="tooltip" data-bs-html="true" title="' . getsquadname($ds['squad']) .'">';
    } else{
       $squadicon='<img style="height: 150px" src="./includes/plugins/clanwars/images/no-image.jpg" alt="" data-toggle="tooltip" data-bs-html="true" title="' . getsquadname($ds['squad']) .'">';
    }

    // -- clanwar output -- //
    global $myclantag;
    $data_array = array();
    $data_array['$report'] = $report;
    $data_array['$date'] = $date;
    $data_array['$game'] = $game;
    $data_array['$squad'] = $squad;
    $data_array['$squadicon'] = $squadicon;
    $data_array['$opp_team'] = $opp_team;
    $data_array['$opppic'] = $opppic;
    $data_array['$opponent'] = $opponent;
    $data_array['$league'] = $league;
    $data_array['$linkpage'] = $linkpage;
    $data_array['$maps'] = $maps;
    $data_array['$extendedresults'] = $extendedresults;
    $data_array['$results_1'] = $results_1;
    $data_array['$results_2'] = $results_2;
    $data_array['$myclantag'] = $myclantag;
    $data_array['$hometeam'] = $hometeam;
    $data_array['$opptag'] = $opptag;
    $data_array['$oppteam'] = $oppteam;
    $data_array['$server'] = $server;
    $data_array['$hltv'] = $hltv;
    $data_array['$screenshots'] = $screenshots;

    $data_array['$lang_report'] = $plugin_language[ 'report' ];
    $data_array['$lang_date'] = $plugin_language[ 'date' ];
    $data_array['$lang_squad'] = $plugin_language[ 'squad' ];
    $data_array['$lang_game'] = $plugin_language[ 'game' ];
    $data_array['$lang_opponent'] = $plugin_language[ 'opponent' ];
    $data_array['$lang_league'] = $plugin_language[ 'league' ];
    $data_array['$lang_map'] = $plugin_language[ 'map' ];
    $data_array['$lang_maps'] = $plugin_language[ 'maps' ];
    $data_array['$lang_result'] = $plugin_language[ 'result' ];
    $data_array['$lang_total'] = $plugin_language[ 'total' ];
    $data_array['$lang_team'] = $plugin_language[ 'team' ];
    $data_array['$lang_server'] = $plugin_language[ 'server' ];
    $data_array['$lang_hltv_server'] = $plugin_language[ 'hltv' ];
    $data_array['$lang_screenshots'] = $plugin_language[ 'screenshots' ];
    $data_array['$lang_matchlink'] = $plugin_language[ 'matchlink' ];

    $template = $GLOBALS["_template"]->loadTemplate("clanwars_details","content", $data_array, $plugin_path);
    echo $template;

} elseif ($action == "clanwar_result") { 

    global $userID;


    if (isset($_GET[ 'action' ])) {
        $action = $_GET[ 'action' ];
    } else {
        $action = "";
    }


    $plugin_data= array();
    $plugin_data['$title']=$plugin_language['clanwars'];    
    $plugin_data['$subtitle']='Clanwars';

    $template = $GLOBALS["_template"]->loadTemplate("clanwars","title", $plugin_data, $plugin_path);
    echo $template; 

    
    echo '<h2>' . $plugin_language[ 'clan_stats' ] . '</h2>';

    $totalHomeScore = 0;
    $totalOppScore = 0;
    $totaldrawall = 0;
    $totalwonall = 0;
    $totalloseall = 0;

    // TOTAL

    $dp = safe_query("SELECT * FROM `" . PREFIX . "plugins_clanwars`");
    // clanwars gesamt
    $totaltotal = mysqli_num_rows($dp);

    while ($cwdata = mysqli_fetch_array($dp)) {
        // total home points
        $unserializeHome = unserialize($cwdata[ 'homescore' ]);
        $theHomeScore = max(array_sum($unserializeHome), 0);
        $totalHomeScore += $theHomeScore;

        $unserializeOpp = unserialize($cwdata[ 'oppscore' ]);
        $theOppScore = max(array_sum($unserializeOpp), 0);
        $totalOppScore += $theOppScore;

        //
        if ($theHomeScore > $theOppScore) {
            $totalwonall++;
        }
        if ($theHomeScore < $theOppScore) {
            $totalloseall++;
        }
        if ($theHomeScore == $theOppScore) {
            $totaldrawall++;
        }
    }

    $totalhome = $totalHomeScore;
    $totalopp = $totalOppScore;

    $totalwonperc = percent($totalwonall, $totaltotal, 2);
    if ($totalwonperc) {
        $totalwon = '<div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: ' . round($totalwonperc, 0) . '%" aria-valuenow="' . round($totalwonperc, 0) . '" aria-valuemin="0" aria-valuemax="' . round($totalwonperc, 0) . '"%"><span class="text-dark">' . $totalwonperc . '%</span></div>
                                    </div>';
    } else {
        $totalwon = '<div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0%"><span class="text-dark"><span class="text-dark">0%</span></div>
                                    </div>';
    }

    $totalloseperc = percent($totalloseall, $totaltotal, 2);
    if ($totalloseperc) {
        $totallost = '<div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: ' . round($totalloseperc, 0) . '%" aria-valuenow="' . round($totalloseperc, 0) . '" aria-valuemin="0" aria-valuemax="' . round($totalloseperc, 0) . '"%"><span class="text-dark">' . $totalloseperc . '%</span></div>
                                    </div>';
    } else {
        $totallost = '<div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0%"><span class="text-dark"><span class="text-dark">0%</span></div>
                                    </div>';
    }

    $totaldrawperc = percent($totaldrawall, $totaltotal, 2);
    if ($totaldrawperc) {
        $totaldraw = '<div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: ' . round($totaldrawperc, 0) . '%" aria-valuenow="' . round($totaldrawperc, 0) . '" aria-valuemin="0" aria-valuemax="' . round($totaldrawperc, 0) . '"%"><span class="text-dark">' . $totaldrawperc . '%</span></div>
                                    </div>';
    } else {
        $totaldraw = '<div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0%"><span class="text-dark"><span class="text-dark">0%</span></div>
                                    </div>';
    }

    $squad = $plugin_language[ 'clan' ];

    $data_array = array();
    $data_array['$totaltotal'] = $totaltotal;
    $data_array['$totalwonall'] = $totalwonall;
    $data_array['$totalloseall'] = $totalloseall;
    $data_array['$totaldrawall'] = $totaldrawall;
    $data_array['$totalhome'] = $totalhome;
    $data_array['$totalopp'] = $totalopp;
    $data_array['$totalwon'] = $totalwon;
    $data_array['$totallost'] = $totallost;
    $data_array['$totaldraw'] = $totaldraw;

    $data_array['$total_clanwars']=$plugin_language['total_clanwars'];
    $data_array['$total_won_clanwars']=$plugin_language['total_won_clanwars'];
    $data_array['$total_lost_clanwars']=$plugin_language['total_lost_clanwars'];
    $data_array['$total_draw_clanwars']=$plugin_language['total_draw_clanwars'];
    $data_array['$total_won_points']=$plugin_language['total_won_points'];
    $data_array['$total_lost_points']=$plugin_language['total_lost_points'];
    $data_array['$won']=$plugin_language['won'];
    $data_array['$lost']=$plugin_language['lost'];
    $data_array['$draw']=$plugin_language['draw'];
    
    $template = $GLOBALS["_template"]->loadTemplate("clanwars","stats_total", $data_array, $plugin_path);
    echo $template;    

    // SQUADS

    $squads = safe_query("SELECT * FROM `" . PREFIX . "plugins_squads` WHERE `gamesquad` = '1' ORDER BY `sort`");
    if (mysqli_num_rows($squads)) {
        while ($squaddata = mysqli_fetch_array($squads)) {
            $squad = getsquadname($squaddata[ 'squadID' ]);

            echo '<h2>' . $squad . ' - ' . $plugin_language[ 'stats' ] . '</h2>';

            $totalHomeScoreSQ = 0;
            $totalOppScoreSQ = 0;
            $drawall = 0;
            $wonall = 0;
            $loseall = 0;

            // SQUAD STATISTICS

            $squadcws =
                safe_query(
                    "SELECT
                        *
                    FROM
                        `" . PREFIX . "plugins_clanwars`
                    WHERE
                        `squad` = '" . (int)$squaddata[ 'squadID' ] . "'"
                );
            $total = mysqli_num_rows($squadcws);
            $totalperc = percent($total, $totaltotal, 2);

            while ($squadcwdata = mysqli_fetch_array($squadcws)) {
                // SQUAD CLANWAR STATISTICS

                // total squad homescore
                $sqHomeScore = array_sum(unserialize($squadcwdata[ 'homescore' ]));
                $totalHomeScoreSQ += $sqHomeScore;

                // total squad oppscore
                $sqOppScore = array_sum(unserialize($squadcwdata[ 'oppscore' ]));
                $totalOppScoreSQ += $sqOppScore;

                //
                if ($sqHomeScore > $sqOppScore) {
                    $wonall++;
                }
                if ($sqHomeScore < $sqOppScore) {
                    $loseall++;
                }
                if ($sqHomeScore == $sqOppScore) {
                    $drawall++;
                }
            }

            // SQUAD STATISTICS - CLANWARS

            // total squad clanwars - home points
            $home = $totalHomeScoreSQ;
            $homeperc = percent($home, $totalhome, 2);
            // total squad clanwars - opponent points
            $opp = $totalOppScoreSQ;
            $oppperc = percent($opp, $totalopp, 2);
            // total squad clanwars won
            $wonperc = percent($wonall, $totaltotal, 2);
            if ($wonperc) {
                $totalwon = '<div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: ' . round($totalwonperc, 0) . '%" aria-valuenow="' . round($totalwonperc, 0) . '" aria-valuemin="0" aria-valuemax="' . round($totalwonperc, 0) . '"%"><span class="text-dark">' . $wonperc . '%</span></div>
                                    </div>';
            } else {
                $totalwon = '<div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0%"><span class="text-dark"><span class="text-dark">0%</span></div>
                                    </div>';
            }
            // total squad clanwars lost
            $loseperc = percent($loseall, $totaltotal, 2);
            if ($loseperc) {
                $totallost = '<div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: ' . round($totalloseperc, 0) . '%" aria-valuenow="' . round($totalloseperc, 0) . '" aria-valuemin="0" aria-valuemax="' . round($totalloseperc, 0) . '"%"><span class="text-dark">' . $loseperc . '%</span></div>
                                    </div>';
            } else {
                $totallost = '<div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0%"><span class="text-dark"><span class="text-dark">0%</span></div>
                                    </div>';
            }
            // total squad clanwars draw
            $drawperc = percent($drawall, $totaltotal, 2);
            if ($drawperc) {
                $totaldraw = '<div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: ' . round($totaldrawperc, 0) . '%" aria-valuenow="' . round($totaldrawperc, 0) . '" aria-valuemin="0" aria-valuemax="' . round($totaldrawperc, 0) . '"%"><span class="text-dark">' . $drawperc . '%</span></div>
                                    </div>';
            } else {
                $totaldraw = '<div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0%"><span class="text-dark"><span class="text-dark">0%</span></div>
                                    </div>';
            }

          // start output for squad details
            $data_array = array();
            $data_array['$total'] = $total;
            $data_array['$totaltotal'] = $totaltotal;
            $data_array['$totalperc'] = $totalperc;
            $data_array['$wonall'] = $wonall;
            $data_array['$totalwonall'] = $totalwonall;
            $data_array['$wonperc'] = $wonperc;
            $data_array['$loseall'] = $loseall;
            $data_array['$totalloseall'] = $totalloseall;
            $data_array['$loseperc'] = $loseperc;
            $data_array['$drawall'] = $drawall;
            $data_array['$totaldrawall'] = $totaldrawall;
            $data_array['$drawperc'] = $drawperc;
            $data_array['$home'] = $home;
            $data_array['$totalhome'] = $totalhome;
            $data_array['$homeperc'] = $homeperc;
            $data_array['$opp'] = $opp;
            $data_array['$totalopp'] = $totalopp;
            $data_array['$oppperc'] = $oppperc;
            $data_array['$totalwon'] = $totalwon;
            $data_array['$totallost'] = $totallost;
            $data_array['$totaldraw'] = $totaldraw;            

            $data_array['$won']=$plugin_language['won'];
            $data_array['$lost']=$plugin_language['lost'];
            $data_array['$draw']=$plugin_language['draw'];
            $data_array['$squad']=$plugin_language['squad'];
            $data_array['$clan']=$plugin_language['clan'];
            $data_array['$percent']=$plugin_language['percent'];
            $data_array['$clanwars']=$plugin_language['clanwars'];
            $data_array['$won_clanwars']=$plugin_language['won_clanwars'];
            $data_array['$lost_clanwars']=$plugin_language['lost_clanwars'];
            $data_array['$draw_clanwars']=$plugin_language['draw_clanwars'];
            $data_array['$won_points']=$plugin_language['won_points'];
            $data_array['$lost_points']=$plugin_language['lost_points'];

            $template = $GLOBALS["_template"]->loadTemplate("clanwars","stats", $data_array, $plugin_path);
            echo $template;  

            unset(
                $opp,
                $home,
                $totalwon,
                $totallost,
                $totaldraw,
                $totalHomeScoreSQ,
                $totalOppScoreSQ,
                $homeperc,
                $oppperc
            );

            // PLAYER STATISTICS

            $hometeam = array();
            $playerlist = "";

            $data_array['$nickname']=$plugin_language['nickname'];
            $data_array['$clanwars']=$plugin_language['clanwars'];
            $data_array['$percent']=$plugin_language['percent'];
            // start output for squad details - players of the squad - head
            $template = $GLOBALS["_template"]->loadTemplate("clanwars","stats_player_head", $data_array, $plugin_path);
            echo $template;

            
            // get playerlist for squad
            $squadmembers =
                safe_query(
                    "SELECT
                        *
                    FROM
                        `" . PREFIX . "plugins_squads_members`
                    WHERE
                        `squadID` = '" . (int)$squaddata[ 'squadID' ]."'"
                );
             $playerlist = array() ;
            while ($player = mysqli_fetch_array($squadmembers)) {
                $playerlist[] = $player[ 'userID' ];
            }


            // get roster for squad and find matches with playerlist
            $playercws =
                safe_query(
                    "SELECT
                        `hometeam`
                    FROM
                        `" . PREFIX . "plugins_clanwars`
                    WHERE
                        `squad` = '" . (int)$squaddata[ 'squadID' ]."'"
                );
            while ($roster = mysqli_fetch_array($playercws)) {
                @$hometeam = array_merge($hometeam, unserialize($roster[ 'hometeam' ])); 
            }


            // counts clanwars the member has taken part in
             $anz = array();
            if (!empty($hometeam)) {
                foreach ($hometeam as $id) {
                    if (!isset($anz[ $id ])) {
                        $anz[$id] = '0';
                    }
                    if (!empty($id)) {
                        if(deleteduser($id) == '0'){
                            $anz[$id] = $anz[$id]+1;
                        
                        } else {
                            $anz[$id] = '0';
                        }
                    }
                }
            }

            // member's details and the output
            if (is_array($playerlist)) {
                $i = 1;
                foreach ($playerlist as $id) {
                    
                    $member = '<a href="index.php?site=profile&amp;id=' . $id . '"><strong>' . getnickname($id) .
                        '</strong></a>';
                    if (!isset($anz[ $id ])) {
                        $anz[ $id ] = '';
                    }
                    $wars = $anz[ $id ];
                    if (empty($wars)) {
                        $wars = '0';
                    }
                    
                    $perc = percent($wars, $total, 2);
            if ($perc) {
                $percpic = '<div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: ' . round($perc, 0) . '%" aria-valuenow="' . round($perc, 0) . '" aria-valuemin="0" aria-valuemax="' . round($perc, 0) . '"%"><span class="text-dark">' . round($perc, 0) . '%</span></div>
                                    </div>';
            } else {
                $percpic = '<div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0%"><span class="text-dark"><span class="text-dark">0%</span></div>
                                    </div>';
            }


                    $data_array = array();
                    $data_array['$member'] = $member;
                    $data_array['$wars'] = $wars;
                    $data_array['$percpic'] = $percpic;

                    $template = $GLOBALS["_template"]->loadTemplate("clanwars","stats_player_content", $data_array, $plugin_path);
                    echo $template; 
                    $i++;
                }
            }
            echo '</table></div></div>';

            unset($wonall);
            unset($loseall);
            unset($drawall);
            unset($playerlist);
            unset($hometeam);
            unset($squadcwdata);
        }
    }
    
}
