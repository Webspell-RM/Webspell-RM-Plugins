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
$plugin_language = $pm->plugin_language("userlist", $plugin_path);

$data_array = array();
$data_array['$title']=$plugin_language[ 'userlist_title' ];
$data_array['$subtitle']='User online';
$template = $GLOBALS["_template"]->loadTemplate("userlist","head", $data_array, $plugin_path);
echo $template;

$settings = safe_query("SELECT * FROM " . PREFIX . "plugins_userlist");
$ds = mysqli_fetch_array($settings);
$maxusers = $ds[ 'users_online' ];

$ergebnis = safe_query("SELECT w.*, u.nickname FROM ".PREFIX."whowasonline w LEFT JOIN ".PREFIX."user u ON u.userID = w.userID ORDER BY time DESC LIMIT 0 , ".$maxusers."");
	
$template = $GLOBALS["_template"]->loadTemplate("userlist","useronline_head", $data_array, $plugin_path);
echo $template;

$n=1;
while($ds=mysqli_fetch_array($ergebnis)) {	

	if(isonline($ds['userID'])=="offline") {
		$statuspic='<span class="badge bg-danger">OffLine</span> ';
        $timestamp = time();
        $time_now = date("d.m.Y - H:i",$timestamp);
        $time_lastlogin = date("d.m.Y - H:i", $ds['time']);
        $timestamp_lastlogin = $ds['time'];
		$diffzeit = $timestamp - $timestamp_lastlogin;
		$minuten = $diffzeit / 60;
		$minuten_rest = floor(($minuten - floor($minuten / 60) * 60));
		$stunden = floor($minuten / 60);
			if(	$stunden=="0"){
				$stunden='';
			}elseif(	$stunden=="1"){
				$stunden=$stunden.' '.$plugin_language['hour_and'].' ';
				$minuten_rest=str_pad($minuten_rest, 2, "0", STR_PAD_LEFT);
			}else {
				$stunden=$stunden.' '.$plugin_language['hours_and'].' ';
				$minuten_rest=str_pad($minuten_rest, 2, "0", STR_PAD_LEFT);
			}
			$last_active = ''.$plugin_language['was_online'].': '.$stunden.''.$minuten_rest.' '.$plugin_language['minutes'].'';
	}else {	
		$statuspic='<span class="badge bg-success">OnLine</span> ';	// Ausgabe Statuspic "Online"
		$last_active=''.$plugin_language['now_on'].'';
	}
	$nickname=''.$statuspic.' <a href="index.php?site=profile&amp;id='.$ds['userID'].'"><b>'.$ds['nickname'].'</b></a>';
	$ttID='sc_useronline_.'.$ds['userID'].'';				// erzeugt die ID für den Tooltip

	$data_array = array();
    $data_array['$nickname'] = $nickname;
    $data_array['$last_active'] = $last_active;

    $template = $GLOBALS["_template"]->loadTemplate("userlist","useronline_content", $data_array, $plugin_path);
    echo $template;
	$n++;
}
	$template = $GLOBALS["_template"]->loadTemplate("userlist","useronline_foot", array(), $plugin_path);
    echo $template;
?>