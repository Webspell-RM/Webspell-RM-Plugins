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
    $plugin_language = $pm->plugin_language("squads", $plugin_path);

    $data_array['$title']=$plugin_language[ 'title' ];
    $data_array['$subtitle']='Squads';

    $template = $GLOBALS["_template"]->loadTemplate("switchsquads","content_title", $data_array, $plugin_path);
    echo $template;


if (isset($_GET[ 'action' ])) {
        $action = $_GET[ 'action' ];
    } else {
        $action = "";
    }


    if (isset($_POST[ 'squadID' ])) {
        $onesquadonly = 'WHERE squadID="' . (int)$_POST[ 'squadID' ] . '"';
        $visible = "block";
    } elseif (isset($_GET[ 'squadID' ])) {
        $onesquadonly = 'WHERE squadID="' . (int)$_GET[ 'squadID' ] . '"';
        $visible = "block";
    } else {
        $visible = "none";
        $onesquadonly = '';
    }

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_squads " . $onesquadonly . " ORDER BY sort");
    if (mysqli_num_rows($ergebnis)) {
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $anzmembers = mysqli_num_rows(
                safe_query(
                    "SELECT
                        sqmID
                    FROM
                        " . PREFIX . "plugins_squads_members
                    WHERE squadID='" . $ds[ 'squadID' ] . "'"
                )
            );
            
            $name = '<b>' . $ds[ 'name' ] . '</b>';    

            if ($ds[ 'icon' ]) {
                $icon = '/includes/plugins/squads/images/squadicons/' . $ds[ 'icon' ] . '';
            } else {
                $icon = '/includes/plugins/squads/images/squadicons/no-image.jpg';
            }
            
            $squadID = $ds[ 'squadID' ];

            if ($anzmembers == 1) {
                $anzmembers = $anzmembers . ' ' . $plugin_language[ 'member' ];
            } else {
                $anzmembers = $anzmembers . ' ' . $plugin_language[ 'members' ];
            }

            $data_array = array();
            $data_array['$squadID'] = $squadID;
            $data_array['$icon'] = $icon;
            $data_array['$name'] = $name;
            $data_array['$anzmembers'] = $anzmembers;
            

            $template = $GLOBALS["_template"]->loadTemplate("switchsquads","content_head", $data_array, $plugin_path);
            echo $template;    

        }
        $data_array = array();
        $template = $GLOBALS["_template"]->loadTemplate("switchsquads","content_foot", $data_array, $plugin_path);
        echo $template;   
        
    } else {
        echo ($plugin_language['no_team']);
    }
