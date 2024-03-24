<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                  Webspell-RM      /                        /   /                                          *
 *                  -----------__---/__---__------__----__---/---/-----__---- _  _ -                         *
 *                   | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                          *
 *                  _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                          *
 *                               Free Content / Management System                                            *
 *                                           /                                                               *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         webspell-rm                                                                              *
 *                                                                                                           *
 * @copyright       2018-2023 by webspell-rm.de                                                              *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de                 *
 * @website         <https://www.webspell-rm.de>                                                             *
 * @forum           <https://www.webspell-rm.de/forum.html>                                                  *
 * @wiki            <https://www.webspell-rm.de/wiki.html>                                                   *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                         *
 *                  It's NOT allowed to remove this copyright-tag                                            *
 *                  <http://www.fsf.org/licensing/licenses/gpl.html>                                         *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                        *
 * @copyright       2005-2011 by webspell.org / webspell.info                                                *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
*/

# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("memberslist", $plugin_path);

$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
if (@$dx[ 'modulname' ] != 'squads') {

    $data_array = array();
    $data_array['$name'] = 'Squad';
    $template = $GLOBALS["_template"]->loadTemplate("widget_memberslist","head", $data_array, $plugin_path);
    echo $template;
    echo $plugin_language['no_team'];
    $template = $GLOBALS["_template"]->loadTemplate("widget_memberslist","foot", $data_array, $plugin_path);
    echo $template;
           
} else {

$alle = safe_query("SELECT squadID FROM " . PREFIX . "plugins_squads");
    $gesamt = mysqli_num_rows($alle);
    $ergebnis =
            safe_query(
                "SELECT
                    *
                FROM
                    " . PREFIX . "plugins_squads"
            );

$anz=mysqli_num_rows($ergebnis);
if($anz) {

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

    $xyz = safe_query("SELECT * FROM " . PREFIX . "plugins_squads " . $onesquadonly . " ORDER BY sort");
        while ($dy = mysqli_fetch_array($xyz)) {
            if ($dy['name'] != '') {
                $name = $dy[ 'name' ];    
            } else {
                $name = "n/a";
            }

            $data_array = array();
            $data_array['$name'] = $name;
            $template = $GLOBALS["_template"]->loadTemplate("widget_memberslist","head", $data_array, $plugin_path);
            echo $template;
        
    

            $template = $GLOBALS["_template"]->loadTemplate("widget_memberslist","content_head", $data_array, $plugin_path);
            echo $template;

            $member =
                safe_query(
                    "SELECT
                        *
                    FROM
                        " . PREFIX . "plugins_squads_members s, " . PREFIX . "user u
                    WHERE
                        s.squadID='" . $dy[ 'squadID' ] . "'
                    AND
                        s.userID = u.userID
                    ORDER BY
                        sort"
                );
            while ($dm = mysqli_fetch_array($member)) {
                $n=1;
                if (isclanmember($dm[ 'userID' ])) {
                    $nickname = '<a href="index.php?site=profile&amp;id=' . $dm[ 'userID' ] . '">' . strip_tags($dm[ 'nickname' ]) . '</a>';
                } else {
                    $nickname = '';
                }
                    
                if ($getuserpic = getuserpic($dm[ 'userID' ])) {
                    $avatar = './images/userpics/' . $getuserpic . '';
                } else {
                    $avatar = '';
                }
            
    /*=================Abfrage Position Squad==================*/

                if ($dm['position'] != '') {
                    $position = $dm[ 'position' ]; 
                } else {
                    $position = "n/a";
                }

                $facebook = $dm['facebook'];
                $twitter = $dm['twitter'];                
                $stream = $dm['steam'];

                $data_array = array();
                $data_array['$position'] = $position;        
                $data_array['$avatar'] = $avatar;
                $data_array['$nickname'] = $nickname;
                $data_array['$facebook'] = $facebook;
                $data_array['$twitter'] = $twitter;
                $data_array['$stream'] = $stream;
                $template = $GLOBALS["_template"]->loadTemplate("widget_memberslist","position", $data_array, $plugin_path);
                echo $template;
            }
            $n++;
         
            $template = $GLOBALS["_template"]->loadTemplate("widget_memberslist","content_foot", $data_array, $plugin_path);
            echo $template;   

            $template = $GLOBALS["_template"]->loadTemplate("widget_memberslist","foot", $data_array, $plugin_path);
            echo $template;
        }
}else {
    $data_array = array();
    $data_array['$name'] = 'Squad';
    $template = $GLOBALS["_template"]->loadTemplate("widget_memberslist","head", $data_array, $plugin_path);
    echo $template;
    echo $plugin_language['no_team'];
    $template = $GLOBALS["_template"]->loadTemplate("widget_memberslist","foot", $data_array, $plugin_path);
    echo $template;
}
}