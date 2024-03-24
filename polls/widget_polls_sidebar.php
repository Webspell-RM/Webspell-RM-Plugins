<?php
/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2015 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#                                                                        #
##########################################################################
*/
# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("polls", $plugin_path);

    global $userID;

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = "";
}

if ($action == "") {
    
    $data_array = array();
    $data_array['$title']=$plugin_language['polls'];
    $data_array['$subtitle']='Polls';
    $template = $GLOBALS["_template"]->loadTemplate("polls","title", $data_array, $plugin_path);
    echo $template;

    
    
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    
    $ergebnis =
        safe_query(
            "SELECT
                *
            FROM
                " . PREFIX . "plugins_polls
            WHERE
                intern<=" . (int)isclanmember($userID) . "
                AND published = '1'
            ORDER BY
                pollID DESC"
        );

    $anz = mysqli_num_rows($ergebnis);
    if ($anz) {

    $data_array = array();
    $data_array['$endingtime']=$plugin_language['endingtime'];
    $data_array['$title']=$plugin_language['title'];
    $data_array['$votes']=$plugin_language['votes'];
    
    $template = $GLOBALS["_template"]->loadTemplate("polls","widget_all_head", $data_array, $plugin_path);
    echo $template;
    echo '<ul class="list-group">';
        $i = 1;
        while ($ds = mysqli_fetch_array($ergebnis)) {

            $laufzeit = getformatdatetime($ds[ 'laufzeit' ]);
            if ($ds[ 'intern' ] == 1) {
                $isintern = '(' . $plugin_language[ 'intern' ] . ')';
            } else {
                $isintern = '';
            }
            if ($ds[ 'laufzeit' ] < time() || $ds[ 'aktiv' ] == "0") {
                $timeleft = '<div class="alert alert-danger" role="alert" style="height: 6px;padding: 5px;margin-top: 6px"><p style="font-size: 10px;">'.$plugin_language['poll_ended'].'</p></div>';
                $active = '<hr class="alert alert-danger" style="height: 2px;width: 100%">';
            } else {
                $timeleft = '<div class="alert alert-success" role="alert" style="height: 2px;"><p style="font-size: 10px;margin-top: -7px">'.$plugin_language['poll_end'].' '.$laufzeit.'</p></div>';
                    floor(($ds[ 'laufzeit' ] - time()) / (60 * 60 * 24)) . " " . $plugin_language[ 'days' ] . " (" .
                    date("d.m.Y H:i", $ds[ 'laufzeit' ]) . ")</div>";
                $active = '<span class="alert alert-success" role="alert" style="height: 2px;width: 100%"></span>';
            }
            $options = array();
            for ($n = 1; $n <= 10; $n++) {
                if ($ds[ 'o' . $n ]) {
                    $options[ ] = $ds[ 'o' . $n ];
                }
            }



            
            $votes = safe_query("SELECT * FROM " . PREFIX . "plugins_polls_votes WHERE pollID='" . $ds[ 'pollID' ] . "'");
            $dv = mysqli_fetch_array($votes);
            $gesamtstimmen = $dv[ 'o1' ] + $dv[ 'o2' ] + $dv[ 'o3' ] + $dv[ 'o4' ] + $dv[ 'o5' ] + $dv[ 'o6' ] + $dv[ 'o7' ] + $dv[ 'o8' ] + $dv[ 'o9' ] + $dv[ 'o10' ];
            
            if ($ds[ 'aktiv' ]) {
                $actions = '<div class="alert alert-success" role="alert">'.$plugin_language['poll_end'].'<br>'.$laufzeit.'</div>';
                
            } else {
                $actions = '<div class="alert alert-danger" role="alert">'.$plugin_language['poll_ended'].'</div>';
                
            }


            $pollID = $ds[ 'pollID' ];
            $title = $ds[ 'titel' ];
            $description = $ds[ 'description' ];
            
            $translate = new multiLanguage(detectCurrentLanguage());    
            $translate->detectLanguages($title);
            $title = $translate->getTextByLanguage($title);
   
            
            $data_array = array();
            $data_array[ '$active' ] = $active;
            $data_array[ '$actions' ] = $actions;
            $data_array[ '$timeleft' ] = $timeleft;
            $data_array[ '$laufzeit' ] = $laufzeit;
            $data_array[ '$isintern' ] = $isintern;
            $data_array[ '$gesamtstimmen' ] = $gesamtstimmen;
            $data_array[ '$pollID' ] = $pollID;
            $data_array[ '$title' ] = $title;
            $data_array[ '$description' ] = $description;
            $data_array[ '$result' ]=$plugin_language['result'];
            $data_array['$votes']=$plugin_language['votes'];
            

            $template = $GLOBALS["_template"]->loadTemplate("polls","widget_all_content", $data_array, $plugin_path);
            echo $template;
            $i++;
        }
        echo '</ul>';
        $template = $GLOBALS["_template"]->loadTemplate("polls","widget_all_foot", $data_array, $plugin_path);
        echo $template;
    } else {
        $data_array = array();
        $data_array['$no_entries']=$plugin_language['no_entries'];

        $template = $GLOBALS["_template"]->loadTemplate("polls","all_content-no", $data_array, $plugin_path);
        echo $template;
    }
    

 
}
