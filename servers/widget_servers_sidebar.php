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


$data_array=array();
$data_array['$title'] = $plugin_language[ 'server' ];
$data_array['$subtitle']='Server';

$headtemp = $GLOBALS["_template"]->loadTemplate("servers","head", $data_array, $plugin_path);
echo $headtemp;

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_servers WHERE displayed = '1' ORDER BY sort");

if (mysqli_num_rows($ergebnis)) {
    $template = $GLOBALS["_template"]->loadTemplate("servers","widget_head_content", $data_array, $plugin_path);
    echo $template;
    
    $n = 1;
    while ($ds = mysqli_fetch_array($ergebnis)) {

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
    $serverip = $ds[ 'ip' ];
    $filepath = "../images/games/";
    $showgame = getgamename($ds[ 'game' ]);

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

    $data_array= array();    
    $data_array['$status'] = $status;
    $data_array['$gameicon'] = $gameicon;
    $data_array['$serverip'] = $serverip;
    $data_array['$servername'] = $servername;
    $data_array['$server_status']=$plugin_language['status'];
    
    $template = $GLOBALS["_template"]->loadTemplate("servers","widget_content", $data_array, $plugin_path);
    echo $template;
    $n++;
}
    
    $template = $GLOBALS["_template"]->loadTemplate("servers","widget_foot_content", $data_array, $plugin_path);
    echo $template;

  
    
} else {
    
    echo $plugin_language['no_server'];
}