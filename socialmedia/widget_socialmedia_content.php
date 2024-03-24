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
	$plugin_language = $pm->plugin_language("socialmedia", $plugin_path);

$sortierung = 'socialID ASC';	

$ergebnis = safe_query("SELECT * FROM `".PREFIX."settings_social_media` ORDER BY $sortierung");
if(mysqli_num_rows($ergebnis)){
	while ($ds = mysqli_fetch_array($ergebnis)) {

		if ($ds[ 'twitch' ] != '') {
            if (stristr($ds[ 'twitch' ], "https://")) {
                $twitch = '<a href="' . htmlspecialchars($ds[ 'twitch' ]) . '" target="_blank" rel="nofollow"><button type="button" class="btn btn-dark twitch">TWITCH.TV</button></a>';//https
            } else {
                $twitch = '<a href="http://' . htmlspecialchars($ds[ 'twitch' ]) . '" target="_blank" rel="nofollow"><button type="button" class="btn btn-dark twitch">TWITCH.TV</button></a>';//http
            }
        } else {
            $twitch = 'n_a';
        }

        if ($ds[ 'facebook' ] != '') {
            if (stristr($ds[ 'facebook' ], "https://")) {
                $facebook = '<a href="' . htmlspecialchars($ds[ 'facebook' ]) . '" target="_blank" rel="nofollow"><button type="button" class="btn btn-dark facebook">FACEBOOK</button></a>';//https
            } else {
                $facebook = '<a href="http://' . htmlspecialchars($ds[ 'facebook' ]) . '" target="_blank" rel="nofollow"><button type="button" class="btn btn-dark facebook">FACEBOOK</button></a>';//http
            }
        } else {
            $facebook = 'n_a';
        }

        if ($ds[ 'twitter' ] != '') {
            if (stristr($ds[ 'twitter' ], "https://")) {
                $twitter = '<a href="' . htmlspecialchars($ds[ 'twitter' ]) . '" target="_blank" rel="nofollow"><button type="button" class="btn btn-dark twitter">TWITTER</button></a>';//https
            } else {
                $twitter = '<a href="http://' . htmlspecialchars($ds[ 'twitter' ]) . '" target="_blank" rel="nofollow"><button type="button" class="btn btn-dark twitter">TWITTER</button></a>';//http
            }
        } else {
            $twitter = 'n_a';
        }

        if ($ds[ 'youtube' ] != '') {
            if (stristr($ds[ 'youtube' ], "https://")) {
                $youtube = '<a href="' . htmlspecialchars($ds[ 'youtube' ]) . '" target="_blank" rel="nofollow"><button type="button" class="btn btn-dark youtube">YOUTUBE</button></a>';//https
            } else {
                $youtube = '<a href="http://' . htmlspecialchars($ds[ 'youtube' ]) . '" target="_blank" rel="nofollow"><button type="button" class="btn btn-dark youtube">YOUTUBE</button></a>';//http
            }
        } else {
            $youtube = 'n_a';
        }

        


		if($ds['socialID'] == 1){	
	        $data_array = array();
            $data_array['$twitch'] = $twitch;
			$data_array['$facebook'] = $facebook;
			$data_array['$twitter'] = $twitter;
			$data_array['$youtube'] = $youtube;
			

			// Prüfen ob Social gesetzt ist
	    if($data_array['$twitch'] == "n_a") { $data_array['social1'] = "invisible"; } else { $data_array['social1'] = "visible"; }
	    if($data_array['$facebook'] == "n_a") { $data_array['social2'] = "invisible"; } else { $data_array['social2'] = "visible"; }
	    if($data_array['$twitter'] == "n_a") { $data_array['social3'] = "invisible"; } else { $data_array['social3'] = "visible"; }
	    if($data_array['$youtube'] == "n_a") { $data_array['social4'] = "invisible"; } else { $data_array['social4'] = "visible"; }
	

        $template = $GLOBALS["_template"]->loadTemplate("socialmedia","wg_content", $data_array, $plugin_path);
        echo $template;
		}
	}
}
?>