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
$plugin_language = $pm->plugin_language("server", $plugin_path);

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}


if($action=="show"){
    $serverID = $_GET['serverID'];
    if(isset($serverID)){

        $plugin_data= array();
        $plugin_data['$title']=$plugin_language['server'];
        $plugin_data['$subtitle']='Server';

        $template = $GLOBALS["_template"]->loadTemplate("servers","head", $plugin_data, $plugin_path);
        echo $template;

        $template = $GLOBALS["_template"]->loadTemplate("servers","content_head", $plugin_data, $plugin_path);
        echo $template;

        $get=safe_query("SELECT * FROM ".PREFIX."plugins_servers WHERE serverID='".$serverID."' LIMIT 0,1");
        $ds=mysqli_fetch_array($get);

 
        $serverID = $ds[ 'serverID' ];

        if ($ds[ 'game' ] == "CS") {
            $game = "HL";
        } else {
            $game = $ds[ 'game' ];
        }

        $showgame = getgamename($ds[ 'game' ]);
        $filepath = "../images/games/";
        #$gameicon = ''.$filepath.'' . $ds[ 'game' ] . '';

        if(file_exists('./includes/plugins/games_pic/images/'.$ds['game'].'.jpg')){
            $gameicon='../includes/plugins/games_pic/images/'.$ds['game'].'.jpg';
        } elseif(file_exists('./includes/plugins/games_pic/images/'.$ds['game'].'.jpeg')){
            $gameicon='../includes/plugins/games_pic/images/'.$ds['game'].'.jpeg';
        } elseif(file_exists('./includes/plugins/games_pic/images/'.$ds['game'].'.png')){
            $gameicon='../includes/plugins/games_pic/images/'.$ds['game'].'.png';
        } elseif(file_exists('./includes/plugins/games_pic/images/'.$ds['game'].'.gif')){
            $gameicon='../includes/plugins/games_pic/images/'.$ds['game'].'.gif';
        } else{
           $gameicon='../includes/plugins/games_pic/images/no-image.jpg';
        }

        $serverdata = explode(":", $ds[ 'ip' ]);
        $ip = $serverdata[ 0 ];
        if (isset($serverdata[ 1 ])) {
            $port = $serverdata[ 1 ];
        } else {
            $port = '';
        }

        $server_timeout = 2;
        $fp = fsockopen("udp://".$ip, $port, $errno, $errstr);
        if(!$fp) {
            $status = "<span class='badge bg-danger'><em>" . $plugin_language[ 'not_supported' ] . " ERROR: $errno - $errstr</em></span><br />";
        } else {
            @fwrite($fp, "\\xFA\\");
            stream_set_timeout($fp, 2);
            $res = fread($fp, 2000);
            $info = stream_get_meta_data($fp);
            fclose($fp);
            if($info['timed_out']) {
                $status = "<span class='badge bg-danger'><em>" . $plugin_language[ 'not_supported' ] . "</em></span>";
            } elseif($res == '') {
                $status = "<span class='badge bg-danger'><em>" . $plugin_language[ 'timeout' ] . "</em></span>";
            }  else{
                $status = "<span class='badge bg-success'>" . $plugin_language[ 'online' ] . "</span>";
            }
        }
        $servername = $ds[ 'name' ];
        $info = $ds[ 'info' ];
        $ip = $ds[ 'ip' ];

        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($servername);
        $servername = $translate->getTextByLanguage($servername);
        $translate->detectLanguages($info);
        $info = $translate->getTextByLanguage($info);

        if(isset($_COOKIE['im_server'])) {
            $pic = '<a href="https://www.gametracker.com/server_info/'.$ip.'/" target="_blank"><img src="https://cache.gametracker.com/server_info/'.$ip.'/b_560_95_1.png" border="0" width="560" height="95" alt=""/></a>';
        } else {
            $pic = '<div data-service="server_offline" style="height: 95px; width: 560px"></div>';
        }
    
        $data_array = array();
        $data_array['$game'] = $ds[ 'game' ];
        $data_array['$gameicon'] = $gameicon;
        $data_array['$ip'] = $ds[ 'ip' ];
        $data_array['$servername'] = $servername;
        $data_array['$status'] = $status;
        $data_array['$showgame'] = $showgame;
        $data_array['$info'] = $info;
        $data_array['$pic'] = $pic;

        $data_array['$server_ip']=$plugin_language['ip'];
        $data_array['$server_status']=$plugin_language['status'];
        $data_array['$server_game']=$plugin_language['game'];
        $data_array['$server_information']=$plugin_language['information'];
        $data_array['$server_link']=$plugin_language['link'];

        $template = $GLOBALS["_template"]->loadTemplate("servers","content", $data_array, $plugin_path);
        echo $template;
    } else {
        echo $plugin_language['no_server'];
    }       

} else {

if ($action == "") {

if(isset($_GET['page'])) $page=(int)$_GET['page'];
  else $page = 1; 

    $plugin_data= array();
    $plugin_data['$title']=$plugin_language['server'];
    $plugin_data['$subtitle']='Server';

    $template = $GLOBALS["_template"]->loadTemplate("servers","head", $plugin_data, $plugin_path);
    echo $template;

    $template = $GLOBALS["_template"]->loadTemplate("servers","content_head", $plugin_data, $plugin_path);
    echo $template;

    $alle=safe_query("SELECT serverID FROM ".PREFIX."plugins_servers WHERE displayed = '1'");
    $gesamt = mysqli_num_rows($alle);
    $pages=1;
  
    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_servers_settings");
    $dn = mysqli_fetch_array($settings);
    
    $max = $dn[ 'servers' ];
    if (empty($max)) {
        $max = 1;
    }

    for ($n=$max; $n<=$gesamt; $n+=$max) {
        if($gesamt>$n) $pages++;
    }

    if($pages>1) $page_link = makepagelink("index.php?site=servers", $page, $pages);
        else $page_link='';

    if ($page == "1") {
        $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_servers WHERE displayed = '1' ORDER BY date DESC LIMIT 0,$max");
        $n=1;
    }
    else {
        $start=$page*$max-$max;
        $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_servers WHERE displayed = '1' ORDER BY date DESC LIMIT $start,$max");
        $n = ($gesamt+1)-$page*$max+$max;
    } 


    $ds = safe_query("SELECT * FROM `" . PREFIX . "plugins_servers` ORDER BY `date`");
    $anzcats = mysqli_num_rows($ds);
    if ($anzcats) {

    $n=1;

        while ($ds = mysqli_fetch_array($ergebnis)) {

            if ($ds[ 'game' ] == "CS") {
                $game = "HL";
            } else {
                $game = $ds[ 'game' ];
            }

            $showgame = getgamename($ds[ 'game' ]);
            $filepath = "../images/games/";

            if(file_exists('./includes/plugins/games_pic/images/'.$ds['game'].'.jpg')){
                $gameicon='../includes/plugins/games_pic/images/'.$ds['game'].'.jpg';
            } elseif(file_exists('./includes/plugins/games_pic/images/'.$ds['game'].'.jpeg')){
                $gameicon='../includes/plugins/games_pic/images/'.$ds['game'].'.jpeg';
            } elseif(file_exists('./includes/plugins/games_pic/images/'.$ds['game'].'.png')){
                $gameicon='../includes/plugins/games_pic/images/'.$ds['game'].'.png';
            } elseif(file_exists('./includes/plugins/games_pic/images/'.$ds['game'].'.gif')){
                $gameicon='../includes/plugins/games_pic/images/'.$ds['game'].'.gif';
            } else{
               $gameicon='../includes/plugins/games_pic/images/no-image.jpg';
            }

            $serverdata = explode(":", $ds[ 'ip' ]);
            $ip = $serverdata[ 0 ];
            if (isset($serverdata[ 1 ])) {
                $port = $serverdata[ 1 ];
            } else {
                $port = '';
            }

            $server_timeout = 2;
            $fp = fsockopen("udp://".$ip, $port, $errno, $errstr);
            if(!$fp) {
                $status = "<span class='badge bg-danger'><em>" . $plugin_language[ 'not_supported' ] . " ERROR: $errno - $errstr</em></span><br />";
            } else {
                @fwrite($fp, "\\xFA\\");
                stream_set_timeout($fp, 2);
                $res = fread($fp, 2000);
                $info = stream_get_meta_data($fp);
                fclose($fp);
                if($info['timed_out']) {
                    $status = "<span class='badge bg-danger'><em>" . $plugin_language[ 'not_supported' ] . "</em></span>";
                } elseif($res == '') {
                    $status = "<span class='badge bg-danger'><em>" . $plugin_language[ 'timeout' ] . "</em></span>";
                }  else{
                    $status = "<span class='badge bg-success'>" . $plugin_language[ 'online' ] . "</span>";
                }
            }
            $servername = $ds[ 'name' ];
            $info = $ds[ 'info' ];
            $ip = $ds[ 'ip' ];

            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($servername);
            $servername = $translate->getTextByLanguage($servername);
            $translate->detectLanguages($info);
            $info = $translate->getTextByLanguage($info);

            if(isset($_COOKIE['im_server'])) {
                $pic = '<a href="https://www.gametracker.com/server_info/'.$ip.'/" target="_blank"><img src="https://cache.gametracker.com/server_info/'.$ip.'/b_560_95_1.png" border="0" width="560" height="95" alt=""/></a>';
            } else {
                $pic = '<div data-service="server_offline" style="height: 95px; width: 560px"></div>';
            }
        
            $data_array = array();
            $data_array['$game'] = $ds[ 'game' ];
            $data_array['$gameicon'] = $gameicon;
            $data_array['$ip'] = $ds[ 'ip' ];
            $data_array['$servername'] = $servername;
            $data_array['$status'] = $status;
            $data_array['$showgame'] = $showgame;
            $data_array['$info'] = $info;
            $data_array['$pic'] = $pic;

            $data_array['$server_ip']=$plugin_language['ip'];
            $data_array['$server_status']=$plugin_language['status'];
            $data_array['$server_game']=$plugin_language['game'];
            $data_array['$server_information']=$plugin_language['information'];
            $data_array['$server_link']=$plugin_language['link'];

            $template = $GLOBALS["_template"]->loadTemplate("servers","content", $data_array, $plugin_path);
            echo $template;
            $n++;
            
        }        
    } else {
        echo $plugin_language['no_server'];
    }
    echo'<br>';
    if($pages>1) echo $page_link;

    }
}

$template = $GLOBALS["_template"]->loadTemplate("servers","foot", array(), $plugin_path);
echo $template;  
?>