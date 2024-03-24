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
$data_array['$title']=$plugin_language['lastregistered'];
$data_array['$subtitle']='Userlist';
$template = $GLOBALS["_template"]->loadTemplate("userlist","head", $data_array, $plugin_path);
echo $template;

$result=safe_query("SELECT * FROM ".PREFIX."user ORDER BY registerdate DESC LIMIT 0,5");	

	$template = $GLOBALS["_template"]->loadTemplate("userlist","widget_lastregistered_head", $data_array, $plugin_path);
   echo $template;

while($row=mysqli_fetch_array($result)) {

	$username='<a href="index.php?site=profile&amp;id='.$row['userID'].'">'.$row['nickname'].'</a>';
	$registerdate=date('d.m.y', $row['registerdate']);
	$current = strtotime(date("Y-m-d"));
	$date    = $row['registerdate'];

	$datediff = $date - $current;
	$difference = floor($datediff/(60*60*24));
	
	if($difference==0){
	    $register=$plugin_language['today'];
	}else if($difference > 1){
	   $register=$plugin_language['future_date'];
	}else if($difference > 0){
	   $register=$plugin_language['tomorrow'];
	}else if($difference < -1){
	   $register=$plugin_language['long_back'];
	   $register=$registerdate;
	}else{
	   $register=$plugin_language['yesterday'];
	}

	if ($getavatar = getavatar($row['userID'])) {
      $avatar = './images/avatars/' . $getavatar . '';
   } else {
      $avatar = '';
   }

	$data_array = array();
	$data_array['$username'] = $username;
	$data_array['$registerdate'] = $register;
	$data_array['$avatar'] = $avatar;
	$template = $GLOBALS["_template"]->loadTemplate("userlist","widget_lastregistered_content", $data_array, $plugin_path);
   echo $template; 
}
	$data_array = array();
	$template = $GLOBALS["_template"]->loadTemplate("userlist","widget_lastregistered_foot", $data_array, $plugin_path);
   echo $template;
?>