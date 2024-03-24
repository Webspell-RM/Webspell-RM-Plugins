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
	$plugin_language = $pm->plugin_language("follow_us", $plugin_path);

$sortierung = 'socialID ASC';	

$ergebnis = safe_query("SELECT * FROM `".PREFIX."settings_social_media` ORDER BY $sortierung");
if(mysqli_num_rows($ergebnis)){
	while ($ds = mysqli_fetch_array($ergebnis)) {

		if ($ds[ 'twitch' ] != '') {
            if (stristr($ds[ 'twitch' ], "https://")) {
                $twitch = '<a class="twitch" href="' . htmlspecialchars($ds[ 'twitch' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-twitch" style="font-size: 3rem;"></i></a>';//https
            } else {
                $twitch = '<a class="twitch" href="http://' . htmlspecialchars($ds[ 'twitch' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-twitch" style="font-size: 3rem;"></i></a>';//http
            }
        } else {
            $twitch = '';
        }

        if ($ds[ 'facebook' ] != '') {
            if (stristr($ds[ 'facebook' ], "https://")) {
                $facebook = '<a class="facebook" href="' . htmlspecialchars($ds[ 'facebook' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-facebook" style="font-size: 3rem;"></i></a>';//https
            } else {
                $facebook = '<a class="facebook" href="http://' . htmlspecialchars($ds[ 'facebook' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-facebook" style="font-size: 3rem;"></i></a>';//http
            }
        } else {
            $facebook = '';
        }

        if ($ds[ 'twitter' ] != '') {
            if (stristr($ds[ 'twitter' ], "https://")) {
                $twitter = '<a class="twitter" href="' . htmlspecialchars($ds[ 'twitter' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-twitter" style="font-size: 3rem;"></i></a>';//https
            } else {
                $twitter = '<a class="twitter" href="http://' . htmlspecialchars($ds[ 'twitter' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-twitter" style="font-size: 3rem;"></i></a>';//http
            }
        } else {
            $twitter = '';
        }

        if ($ds[ 'youtube' ] != '') {
            if (stristr($ds[ 'youtube' ], "https://")) {
                $youtube = '<a class="youtube" href="' . htmlspecialchars($ds[ 'youtube' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-youtube" style="font-size: 3rem;"></i></a>';//https
            } else {
                $youtube = '<a class="youtube" href="http://' . htmlspecialchars($ds[ 'youtube' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-youtube" style="font-size: 3rem;"></i></a>';//http
            }
        } else {
            $youtube = '';
        }

        if ($ds[ 'rss' ] != '') {
            if (stristr($ds[ 'rss' ], "https://")) {
                $rss = '<a class="rss" href="' . htmlspecialchars($ds[ 'rss' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-rss" style="font-size: 3rem;"></i></a>';//https
            } else {
                $rss = '<a class="rss" href="http://' . htmlspecialchars($ds[ 'rss' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-rss" style="font-size: 3rem;"></i></a>';//http
            }
        } else {
            $rss = '';
        }

        if ($ds[ 'vine' ] != '') {
            if (stristr($ds[ 'vine' ], "https://")) {
                $vine = '<a class="vine" href="' . htmlspecialchars($ds[ 'vine' ]) . '" target="_blank" rel="nofollow"><i class="fab fa-vine" style="font-size: 3rem;"></i></a>';//https
            } else {
                $vine = '<a class="vine" href="http://' . htmlspecialchars($ds[ 'vine' ]) . '" target="_blank" rel="nofollow"><i class="fab fa-vine" style="font-size: 3rem;"></i></a>';//http
            }
        } else {
            $vine = '';
        }

        if ($ds[ 'flickr' ] != '') {
            if (stristr($ds[ 'flickr' ], "https://")) {
                $flickr = '<a class="flickr" href="' . htmlspecialchars($ds[ 'flickr' ]) . '" target="_blank" rel="nofollow"><i class="fab fa-flickr" style="font-size: 3rem;"></i></a>';//https
            } else {
                $flickr = '<a class="flickr" href="http://' . htmlspecialchars($ds[ 'flickr' ]) . '" target="_blank" rel="nofollow"><i class="fab fa-flickr" style="font-size: 3rem;"></i></a>';//http
            }
        } else {
            $flickr = '';
        }

        if ($ds[ 'linkedin' ] != '') {
            if (stristr($ds[ 'linkedin' ], "https://")) {
                $linkedin = '<a class="linkedin" href="' . htmlspecialchars($ds[ 'linkedin' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-linkedin" style="font-size: 3rem;"></i></a>';//https
            } else {
                $linkedin = '<a class="linkedin" href="http://' . htmlspecialchars($ds[ 'linkedin' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-linkedin" style="font-size: 3rem;"></i></a>';//http
            }
        } else {
            $linkedin = '';
        }

        if ($ds[ 'instagram' ] != '') {
            if (stristr($ds[ 'instagram' ], "https://")) {
                $instagram = '<a class="instagram" class="url-link" href="' . htmlspecialchars($ds[ 'instagram' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-instagram" style="font-size: 3rem;"></i></a>';//https
            } else {
                $instagram = '<a class="instagram" class="url-link" href="http://' . htmlspecialchars($ds[ 'instagram' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-instagram" style="font-size: 3rem;"></i></a>';//http
            }
        } else {
            $instagram = '';
        }

        if ($ds[ 'steam' ] != '') {
            if (stristr($ds[ 'steam' ], "https://")) {
                $steam = '<a class="steam" href="' . htmlspecialchars($ds[ 'steam' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-steam" style="font-size: 3rem;"></i></a>';//https
        } else {
                $steam = '<a class="steam" href="http://' . htmlspecialchars($ds[ 'steam' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-steam" style="font-size: 3rem;"></i></a>';//http
            }
        } else {
            $steam = '';
        }

        if ($ds[ 'discord' ] != '') {
            if (stristr($ds[ 'discord' ], "https://")) {
                $discord = '<a class="discord" href="' . htmlspecialchars($ds[ 'discord' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-discord" style="font-size: 3rem;"></i></a>';//https
            } else {
                $discord = '<a class="discord" href="http://' . htmlspecialchars($ds[ 'discord' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-discord" style="font-size: 3rem;"></i></a>';//http
            }
        } else {
            $discord = '';
        }

		if($ds['socialID'] == 1){	
	        $data_array = array();


	        
			$data_array['$twitch'] = $twitch;
			$data_array['$facebook'] = $facebook;
			$data_array['$twitter'] = $twitter;
			$data_array['$youtube'] = $youtube;
			$data_array['$rss'] = $rss;
			$data_array['$vine'] = $vine;
			$data_array['$flickr'] = $flickr;
			$data_array['$linkedin'] = $linkedin;
            $data_array['$instagram'] = $instagram;
            $data_array['$steam'] = $steam;
            $data_array['$discord'] = $discord;

			// Prüfen ob Social gesetzt ist
	/*if($data_array['$twitch'] == "n_a") { $data_array['social1'] = "invisible"; } else { $data_array['social1'] = "visible"; }
	if($data_array['$facebook'] == "n_a") { $data_array['social2'] = "invisible"; } else { $data_array['social2'] = "visible"; }
	if($data_array['$twitter'] == "n_a") { $data_array['social3'] = "invisible"; } else { $data_array['social3'] = "visible"; }
	if($data_array['$youtube'] == "n_a") { $data_array['social4'] = "invisible"; } else { $data_array['social4'] = "visible"; }
	if($data_array['$rss'] == "") { $data_array['social5'] = "invisible"; } else { $data_array['social5'] = "visible"; }
	if($data_array['$vine'] == "") { $data_array['social6'] = "invisible"; } else { $data_array['social6'] = "visible"; }
	if($data_array['$flickr'] == "") { $data_array['social7'] = "invisible"; } else { $data_array['social7'] = "visible"; }
	if($data_array['$linkedin'] == "") { $data_array['social8'] = "invisible"; } else { $data_array['social8'] = "visible"; }
    if($data_array['$instagram'] == "") { $data_array['social9'] = "invisible"; } else { $data_array['social9'] = "visible"; }
    if($data_array['$steam'] == "") { $data_array['social10'] = "invisible"; } else { $data_array['social10'] = "visible"; }
    if($data_array['$discord'] == "") { $data_array['social11'] = "invisible"; } else { $data_array['social11'] = "visible"; }*/


			
	        $template = $GLOBALS["_template"]->loadTemplate("sc_follow_us","content", $data_array, $plugin_path);
        	echo $template;
		}
	}
}
?>