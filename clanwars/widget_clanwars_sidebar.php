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
    $data_array['$title']='Matches';    
    $data_array['$subtitle']='Clanwars';

    $template = $GLOBALS["_template"]->loadTemplate("clanwars","sidebar_title", $data_array, $plugin_path);
    echo $template;


    $now = time();
    $limit = "LIMIT 0,5";
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_clanwars WHERE DATE_SUB(NOW(), INTERVAL 14 DAY) AND displayed = '1' ORDER BY date DESC ".$limit);


if(mysqli_num_rows($ergebnis)) {   
    $data_array = array();
    $data_array['$lang_date'] = $plugin_language[ 'date' ];
    $data_array['$lang_squad'] = $plugin_language[ 'squad' ];
    $data_array['$lang_result'] = $plugin_language[ 'result' ];
    $data_array['$lang_opponent'] = $plugin_language[ 'opponent' ];
    $data_array['$lang_league'] = $plugin_language[ 'league' ];
    $data_array['$lang_details']=$plugin_language['details'];

    $template = $GLOBALS["_template"]->loadTemplate("clanwars","sidebar_head", $data_array, $plugin_path);
    echo $template; 

    while($ds=mysqli_fetch_array($ergebnis)) {
        $date = getformatdate($ds[ 'date' ]);
        $squad = getsquadname($ds['squad']);
        
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
            $results = '' . $homescr . ' : ' . $oppscr . '';
            $result_map2 = 'win';
        } elseif ($homescr < $oppscr) {
            $results = '' . $homescr . ' : ' . $oppscr . '';
            $result_map2 = 'lost';
        } else {
            $results = '' . $homescr . ' : ' . $oppscr . '';
            $result_map2 = 'draw';
        }  

   
        $date = getformatdate($ds[ 'date' ]);        
        $league = $ds[ 'leaguehp' ];    
       

        $homescr = array_sum(unserialize($ds[ 'homescore' ]));
        $oppscr = array_sum(unserialize($ds[ 'oppscore' ]));

        $ani_title = $ds[ 'ani_title' ];
    

        $data_array = array();
        $data_array['$hometeam'] = $hometeam;
        $data_array['$date'] = $date;
        $data_array['$squad'] = $squad;
        $data_array['$opponent'] = $opponent;
        $data_array['$league'] = $league;
        $data_array['$results'] = $results;
        $data_array['$result_map2'] = $result_map2;

        $data_array['$lang_description']=$plugin_language['description'];
        $data_array['$clanwar_details']=$plugin_language['clanwar_details'];  
      

        $template = $GLOBALS["_template"]->loadTemplate("clanwars","sidebar_content", $data_array, $plugin_path);
        echo $template;
    }

  
  $template = $GLOBALS["_template"]->loadTemplate("clanwars","sidebar_foot", array(), $plugin_path);
  echo $template;
} else {
     echo $plugin_language[ 'no entry' ];
}
?>
