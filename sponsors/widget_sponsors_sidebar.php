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
    $plugin_language = $pm->plugin_language("sponsors", $plugin_path);


$filepath = $plugin_path."images/";
$_language->readModule('sponsors');


$mainsponsors = safe_query("SELECT * FROM " . PREFIX . "plugins_sponsors WHERE (displayed = '1' AND mainsponsor = '1') ORDER BY RAND() LIMIT 0,4");
if (mysqli_num_rows($mainsponsors)) {
    if (mysqli_num_rows($mainsponsors) == 1) {
        $main_title = $plugin_language[ 'mainsponsor' ];
    } else {
        $main_title = $plugin_language[ 'mainsponsors' ];
    }

    $data_array = array();
    $data_array['$title'] = $main_title;
    $data_array['$subtitle']='Main Sponsors';
    $template = $GLOBALS["_template"]->loadTemplate("sc_sponsors_main","head", $data_array, $plugin_path);
    echo $template;

    $template = $GLOBALS["_template"]->loadTemplate("sc_sponsors_main","head_head", $data_array, $plugin_path);
    echo $template;
    
    while ($da = mysqli_fetch_array($mainsponsors)) {

        if (!empty($da[ 'banner_small' ])) {
        $pic = '<img style="height: 60px" src="' . $filepath . $da[ 'banner_small' ] . '" class="img-fluid" alt="Responsive image">';
        } else {
        $pic = '<img src="' . $filepath . '"no-image.jpg" class="img-fluid" alt="Responsive image">';
        }

        if ($da['url'] != '') {
            if (stristr($da['url'], "https://")) {
                $sponsor = '<a href="' . htmlspecialchars($da['url']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?sponsorID=' . $da['sponsorID'] . '\', 1000})"  target="_blank" rel="nofollow">' . $pic . '</a>';//https
            } else {
                $sponsor = '<a href="http://' . htmlspecialchars($da['url']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?sponsorID=' . $da['sponsorID'] . '\', 1000})"  target="_blank" rel="nofollow">' . $pic . '</a>';//http
            }
        } else {
            $sponsor = $da[ 'name' ];
        }


        $sponsorID = $da[ 'sponsorID' ];

        $data_array = array();
        $data_array['$sponsorID'] = $sponsorID;
        $data_array['$sponsor'] = $sponsor;
        
        $template = $GLOBALS["_template"]->loadTemplate("sc_sponsors_main","side_content", $data_array, $plugin_path);
        echo $template;
    }
    $template = $GLOBALS["_template"]->loadTemplate("sc_sponsors_main","foot_foot", $data_array, $plugin_path);
        echo $template;
}



$sponsors =
    safe_query("SELECT * FROM " . PREFIX . "plugins_sponsors WHERE (displayed = '1' AND mainsponsor = '0') ORDER BY RAND() LIMIT 0,4");
if (mysqli_num_rows($sponsors)) {
    if (mysqli_num_rows($sponsors) == 1) {
        $title = $plugin_language[ 'sponsor' ];
    } else {
        $title = $plugin_language[ 'sponsors' ];
    }
    
    $data_array = array();
    $data_array['$title'] = $title;
    $data_array['$subtitle']='Sponsors';
    $template = $GLOBALS["_template"]->loadTemplate("sc_sponsors","head", $data_array, $plugin_path);
    echo $template;

    $template = $GLOBALS["_template"]->loadTemplate("sc_sponsors","head_head", $data_array, $plugin_path);
    echo $template;

    while ($db = mysqli_fetch_array($sponsors)) {
        
        if (!empty($db[ 'banner_small' ])) {
        $pic = '<img style="height: 60px" src="' . $filepath . $db[ 'banner_small' ] . '" class="img-fluid" alt="Responsive image">';
        } else {
        $pic = '<img src="' . $filepath . '"no-image.jpg" class="img-fluid" alt="Responsive image">';
        }

        if ($db['url'] != '') {
            if (stristr($db['url'], "https://")) {
                $sponsor = '<a href="' . htmlspecialchars($db['url']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?sponsorID=' . $db['sponsorID'] . '\', 1000})"  target="_blank" rel="nofollow">' . $pic . '</a>';//https
            } else {
                $sponsor = '<a href="http://' . htmlspecialchars($db['url']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?sponsorID=' . $db['sponsorID'] . '\', 1000})"  target="_blank" rel="nofollow">' . $pic . '</a>';//http
            }
        } else {
            $sponsor = $db[ 'name' ];
        }


        $sponsorID = $db[ 'sponsorID' ];

        $data_array = array();
        $data_array['$sponsorID'] = $sponsorID;
        $data_array['$sponsor'] = $sponsor;
        
        $template = $GLOBALS["_template"]->loadTemplate("sc_sponsors","side_content", $data_array, $plugin_path);
        echo $template;
    }
    
        $template = $GLOBALS["_template"]->loadTemplate("sc_sponsors","foot_foot", $data_array, $plugin_path);
        echo $template;
}
