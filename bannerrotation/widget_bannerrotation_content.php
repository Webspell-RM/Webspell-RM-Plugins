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
    $plugin_language = $pm->plugin_language("bannerrotation", $plugin_path);

# Installiert
$filepath = $plugin_path."images/";

if(file_exists('install-bannerrotation.php')){ echo $plugin_language['del_install']; 
return false; }

//get banner
$allbanner = safe_query("SELECT * FROM " . PREFIX . "plugins_bannerrotation WHERE displayed='1' ORDER BY RAND() LIMIT 0,1");
$total = mysqli_num_rows($allbanner);
if ($total) {
	$data_array = array();
    $db = mysqli_fetch_array($allbanner);

    if (stristr($db['bannerurl'], "https://")) {
                $link = '<a href="' . htmlspecialchars($db['bannerurl']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?bannerID=' . $db['bannerID'] . '\', 1000})"  target="_blank" rel="nofollow"><img class="img-fluid" src="' . $filepath . $db[ 'banner' ] . '" alt="' . htmlspecialchars($db[ 'bannername' ]) . '"></a>';//https
            } else {
                $link = '<a href="http://' . htmlspecialchars($db['bannerurl']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?bannerID=' . $db['bannerID'] . '\', 1000})"  target="_blank" rel="nofollow"><img class="img-fluid" src="' . $filepath . $db[ 'banner' ] . '" alt="' . htmlspecialchars($db[ 'bannername' ]) . '"></a>';//http
            }

    echo '<div class="text-center">'.$link.'</div>';
    
} else {
	echo $plugin_language['no_banners'];
}
unset($link);
?>