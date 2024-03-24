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
    $plugin_language = $pm->plugin_language("awards", $plugin_path);

$_language->readModule('awards');

$filepath = $plugin_path."images/";

if (isset($site)) {
    $_language->readModule('awards');
}


$data_array = array();
$data_array['$title']=$plugin_language['awards'];
$data_array['$subtitle']='Awards';

$template = $GLOBALS["_template"]->loadTemplate("awards","title", $data_array, $plugin_path);
echo $template;


if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = "";
}

if ($action == "showsquad") {
    $squadID = $_GET[ 'squadID' ];
    $page = (isset($_GET[ 'page' ])) ? $_GET[ 'page' ] : 1;
    $sort = (isset($_GET[ 'page' ])) ? $_GET[ 'page' ] : "date";
    $type = (isset($_GET[ 'type' ])) ? $_GET[ 'type' ] : "DESC";

    
    $alle = safe_query("SELECT awardID FROM " . PREFIX . "plugins_awards WHERE squadID='$squadID'");
    $gesamt = mysqli_num_rows($alle);
    $pages = 1;
    $max = $maxawards;

    $pages = ceil($gesamt / $max);

    if ($pages > 1) {
        $page_link =
            makepagelink(
                "index.php?site=awards&amp;action=showsquad&amp;squadID=$squadID&amp;sort=$sort&amp;type=$type",
                $page,
                $pages
            );
    } else {
        $page_link = "";
    }
    if ($page == "1") {
        $ergebnis =
            safe_query(
                "SELECT * FROM `" . PREFIX . "plugins_awards` WHERE `squadID` = '$squadID' ORDER BY $sort $type LIMIT 0,$max"
            );
        if ($type == "DESC") {
            $n = $gesamt;
        } else {
            $n = 1;
        }
    } else {
        $start = $page * $max - $max;
        $ergebnis = safe_query(
            "SELECT
                *
            FROM
                `" . PREFIX . "plugins_awards`
            WHERE
                `squadID` = '$squadID'
            ORDER BY
                $sort $type
            LIMIT $start,$max"
        );
        if ($type == "DESC") {
            $n = ($gesamt) - $page * $max + $max;
        } else {
            $n = ($gesamt + 1) - $page * $max + $max;
        }
    }
    if ($gesamt) {
        if ($type == "ASC") {
            echo '<a href="index.php?site=awards&amp;action=showsquad&amp;squadID=' . $squadID . '&amp;page=' . $page .
                '&amp;sort=' . $sort . '&amp;type=DESC">' . $plugin_language[ 'sort' ] .
                ':</a> <i class="bi bi-chevron-down" style="font-size: 1rem;"></i>&nbsp;&nbsp;&nbsp;';
        } else {
            echo '<a href="index.php?site=awards&amp;action=showsquad&amp;squadID=' . $squadID . '&amp;page=' . $page .
                '&amp;sort=' . $sort . '&amp;type=ASC">' . $plugin_language[ 'sort' ] .
                ':</a> <i class="bi bi-chevron-up" style="font-size: 1rem;"></i>&nbsp;&nbsp;&nbsp;';
        }

        echo $page_link;
        echo '<br><br>';
        $headdate = '<a class="titlelink" href="index.php?site=awards&amp;action=showsquad&amp;squadID=' . $squadID .
            '&amp;page=' . $page . '&amp;sort=date&amp;type=' . $type . '">' . $plugin_language[ 'date' ] . ':</a>';
        $headsquad = $plugin_language[ 'squad' ] . ':';

        $data_array = array();
        $data_array['$headsquad'] = $headsquad;
        $data_array['$headdate'] = $headdate;
        $template = $GLOBALS["_template"]->loadTemplate("awards","head", $data_array, $plugin_path);
        echo $template;
       
        $n = 1;
        while ($ds = mysqli_fetch_array($ergebnis)) {
            
            $date = getformatdate($ds[ 'date' ]);
            $squad = getsquadname($ds[ 'squadID' ]);
            $award = $ds[ 'award' ];
            $homepage = $ds[ 'homepage' ];
            $rang = $ds[ 'rang' ];

            

            $data_array = array();
            $data_array['$rang'] = $rang;
            $data_array['$awardID'] = $ds['awardID'];
            $data_array['$award'] = $award;
            $data_array['$squad'] = $squad;
            $data_array['$date'] = $date;
            

            $template = $GLOBALS["_template"]->loadTemplate("awards","content", $data_array, $plugin_path);
            echo $template;
            
            unset($result);
            $n++;
        }
        $template = $GLOBALS["_template"]->loadTemplate("awards","foot", $data_array, $plugin_path);
            echo $template;
        
    } else {
        echo $plugin_language[ 'no_entries' ];
    }
} elseif ($action == "details") {
    $awardID = $_GET[ 'awardID' ];
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT
                *
            FROM
                `" . PREFIX . "plugins_awards`
            WHERE
                awardID='" . (int)$awardID . "'"
        )
    );

    $rang = $ds[ 'rang' ];
    if ($rang == '') {
        $rang = "-";
    }
    $award = $ds[ 'award' ];
    if ($award == '') {
        $award = "-";
    }
    $squad = getsquadname($ds[ 'squadID' ]);
    $squadID = $ds[ 'squadID' ];
    $date = getformatdate($ds[ 'date' ]);
    $info = $ds[ 'info' ];
    if ($info == '') {
        $info = "-";
    }
    $homepage = '<a href="http://' . getinput(
        str_replace('http://', '', $ds[ 'homepage' ])) . '" target="_blank">' . $ds[ 'homepage' ] . '</a>';


    if (!empty($ds[ 'banner' ])) {
        $pic = '<img class="img-thumbnail img-fluid" src="../' . $filepath . $ds[ 'banner' ] . '" alt="">';
        } else {
        $pic = '<img class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath . 'no-image.jpg" alt="">';
    }

    #$squad = '<a href="index.php?site=members&amp;action=showsquad&amp;squadID=' . $ds[ 'squadID' ] . '">' . getsquadname($ds[ 'squadID' ]) . '</a>';
    $squad = '<a href="index.php?site=squads&amp;action=show&amp;squadID=' . $ds[ 'squadID' ] . '">' . getsquadname($ds[ 'squadID' ]) . '</a>';
 


$ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_squads` WHERE
                        squadID='" . (int)$ds[ 'squadID' ] . "'");

    if (mysqli_num_rows($ergebnis)) {
        
        while ($db = mysqli_fetch_array($ergebnis)) {
            
if ($db[ 'icon' ]) {
                $icon = '<a href="index.php?site=squads&amp;action=show&amp;squadID=' . $ds[ 'squadID' ] .
                    '"><img class="card-img-top" src="./includes/plugins/squads/images/squadicons/' . $db[ 'icon' ] . '" alt="' . getsquadname($ds[ 'squadID' ]) .
                    '"></a>';
            } else {
                $icon = '';
            }
}}
    $data_array = array();
    if($ds['homepage'] != '') $data_array['$homepage'] = '<a href="'.$ds['homepage'].'" target="_blank"><i class="bi bi-globe-europe-africa bi-globe-europe-color-award" style="font-size: 3em;"></i></a>';
        else $data_array['$homepage'] = '';
        if($ds['facebook'] != '') $data_array['$facebook'] = '<a href="'.$ds['facebook'].'" target="_blank"><i class="bi bi-facebook bi-facebook-color-award" style="font-size: 3em;"></i></a>';
        else $data_array['$facebook'] = '';
        if($ds['twitter'] != '') $data_array['$twitter'] = '<a href="'.$ds['twitter'].'" target="_blank"><i class="bi bi-twitter bi-twitter-color-award" style="font-size: 3em;"></i></a>';
        else $data_array['$twitter'] = '';
        if($ds['liga'] != '') $data_array['$liga'] = '<a href="'.$ds['liga'].'" target="_blank"><i class="bi bi-controller bi-liga-color-award" style="font-size: 3em;"></i></a>';
        else $data_array['$liga'] = '';
        if($ds['steam'] != '') $data_array['$steam'] = '<a href="'.$ds['steam'].'" target="_blank"><i class="bi bi-steam bi-steam-color-award" style="font-size: 3em;"></i></a>';
        else $data_array['$steam'] = '';
        if($ds['twitch'] != '') $data_array['$twitch'] = '<a href="'.$ds['twitch'].'" target="_blank"><i class="bi bi-twitch bi-twitch-color-award" style="font-size: 3em;"></i></a>';
        else $data_array['$twitch'] = '';
        if($ds['youtube'] != '') $data_array['$youtube'] = '<a href="'.$ds['youtube'].'" target="_blank"><i class="bi bi-youtube bi-youtube-play-color-award" style="font-size: 3em;"></i></a>';
        else $data_array['$youtube'] = '';
        if($ds['instagram'] != '') $data_array['$instagram'] = '<a href="'.$ds['instagram'].'" target="_blank"><i class="bi bi-instagram bi-instagram-color-award" style="font-size: 3em;"></i></a>';
        else $data_array['$instagram'] = '';
    $data_array['$award'] = $award;
    $data_array['$rang'] = $rang;
    $data_array['$date'] = $date;
    $data_array['$info'] = $info;
    $data_array['$pic'] = $pic;
    $data_array['$squad'] = $squad;
    $data_array['$icon'] = $icon;
    
    $data_array['$ranking']=$plugin_language['ranking'];

    $template = $GLOBALS["_template"]->loadTemplate("awards","info", $data_array, $plugin_path);
    echo $template;
    
} else {
    $page = (isset($_GET[ 'page' ])) ? (int)$_GET[ 'page' ] : 1;
    $sort = (isset($_GET[ 'sort' ]) && $_GET[ 'sort' ] == 'squadID') ? "squadID" : "date";
    $type = (isset($_GET[ 'type' ]) && $_GET[ 'type' ] == 'ASC') ? "ASC" : "DESC";

    

    $alle = safe_query("SELECT awardID FROM " . PREFIX . "plugins_awards");
    $gesamt = mysqli_num_rows($alle);

    
    if (empty($maxawards)) {
    $maxawards = 10;
    }


    $pages = 1;
    $max = $maxawards;
    $pages = ceil($gesamt / $max);

    

    if ($pages > 1) {
        $page_link = makepagelink("index.php?site=awards&sort=$sort&type=$type", $page, $pages);
    } else {
        $page_link = '';
    }

    if ($page == "1") {
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_awards ORDER BY $sort $type LIMIT 0,$max");
        if ($type == "DESC") {
            $n = $gesamt;
        } else {
            $n = 1;
        }
    } else {
        $start = $page * $max - $max;
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_awards ORDER BY $sort $type LIMIT $start,$max");
        if ($type == "DESC") {
            $n = ($gesamt) - $page * $max + $max;
        } else {
            $n = ($gesamt + 1) - $page * $max + $max;
        }
    }
    if ($gesamt) {
        if ($type == "ASC") {
            echo '<a href="index.php?site=awards&amp;page=' . $page . '&amp;sort=' . $sort . '&amp;type=DESC">' .
                $plugin_language[ 'sort' ] . ':</a> <i class="bi bi-chevron-down" style="font-size: 1rem;"></i>';
        } else {
            echo '<a href="index.php?site=awards&amp;page=' . $page . '&amp;sort=' . $sort . '&amp;type=ASC">' .
                $plugin_language[ 'sort' ] . ':</a> <i class="bi bi-chevron-up" style="font-size: 1rem;"></i>';
        }

        echo $page_link;
        echo '<br><br>';
        $headdate =
            '<a class="titlelink" href="index.php?site=awards&amp;page=' . $page . '&amp;sort=date&amp;type=' . $type .
            '">' . $plugin_language[ 'date' ] . ':</a>';
        $headsquad =
            '<a class="titlelink" href="index.php?site=awards&amp;page=' . $page . '&amp;sort=squadID&amp;type=' .
            $type . '">' . $plugin_language[ 'squad' ] . ':</a>';

        $data_array = array();
        $data_array['$headsquad'] = $headsquad;
        $data_array['$headdate'] = $headdate;

        $data_array['$ranking']=$plugin_language['ranking'];
        $data_array['$award']=$plugin_language['award'];

        $template = $GLOBALS["_template"]->loadTemplate("awards","head", $data_array, $plugin_path);
        echo $template;
        

        $n = 1;
        while ($ds = mysqli_fetch_array($ergebnis)) {
            

            $date = getformatdate($ds[ 'date' ]);
            #$squad = '<a href="index.php?site=members&amp;action=showsquad&amp;squadID=' . $ds[ 'squadID' ] . '&amp;page=' . $page . '&amp;sort=' . $sort . '&amp;type=' . $type . '">' . getsquadname($ds[ 'squadID' ]) . '</a>';
            $squad = '<a href="index.php?site=squads&amp;action=show&amp;squadID=' . $ds[ 'squadID' ] . '">' . getsquadname($ds[ 'squadID' ]) . '</a>';    
            $award = $ds[ 'award' ];
            $homepage = $ds[ 'homepage' ];
            $rang = $ds[ 'rang' ];

            
            $data_array = array();
            $data_array['$rang'] = $rang;
            $data_array['$awardID'] = $ds['awardID'];
            $data_array['$award'] = $award;
            $data_array['$squad'] = $squad;
            $data_array['$date'] = $date;

            
            $template = $GLOBALS["_template"]->loadTemplate("awards","content", $data_array, $plugin_path);
            echo $template;
            
            $n++;
        }
        $template = $GLOBALS["_template"]->loadTemplate("awards","foot", $data_array, $plugin_path);
        echo $template;
        
    } else {
        echo $plugin_language[ 'no_entries' ];
    }
}
