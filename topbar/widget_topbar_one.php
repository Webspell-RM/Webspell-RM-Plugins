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
$plugin_language = $pm->plugin_language("topbar", $plugin_path);

$_language->readModule('index');
$index_language = $_language->module;

GLOBAL $loggedin,$modRewrite,$de_languages,$en_languages,$it_languages,$querystring;

$sortierung = 'socialID ASC';

$ergebnis = safe_query("SELECT * FROM `".PREFIX."settings_social_media` ORDER BY $sortierung");
if(mysqli_num_rows($ergebnis)){
	while ($ds = mysqli_fetch_array($ergebnis)) {

		if ($ds[ 'twitch' ] != '') {
            if (stristr($ds[ 'twitch' ], "https://")) {
                $twitch = '<a class="twitch" href="' . htmlspecialchars($ds[ 'twitch' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-twitch" style="font-size: 1rem;"></i></a>';//https
            } else {
                $twitch = '<a class="twitch" href="http://' . htmlspecialchars($ds[ 'twitch' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-twitch" style="font-size: 1rem;"></i></a>';//http
            }
        } else {
            $twitch = '';
        }

        if ($ds[ 'facebook' ] != '') {
            if (stristr($ds[ 'facebook' ], "https://")) {
                $facebook = '<a class="facebook" href="' . htmlspecialchars($ds[ 'facebook' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-facebook" style="font-size: 1rem;"></i></a>';//https
            } else {
                $facebook = '<a class="facebook" href="http://' . htmlspecialchars($ds[ 'facebook' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-facebook" style="font-size: 1rem;"></i></a>';//http
            }
        } else {
            $facebook = '';
        }

        if ($ds[ 'twitter' ] != '') {
            if (stristr($ds[ 'twitter' ], "https://")) {
                $twitter = '<a class="twitter" href="' . htmlspecialchars($ds[ 'twitter' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-twitter" style="font-size: 1rem;"></i></a>';//https
            } else {
                $twitter = '<a class="twitter" href="http://' . htmlspecialchars($ds[ 'twitter' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-twitter" style="font-size: 1rem;"></i></a>';//http
            }
        } else {
            $twitter = '';
        }

        if ($ds[ 'youtube' ] != '') {
            if (stristr($ds[ 'youtube' ], "https://")) {
                $youtube = '<a class="youtube" href="' . htmlspecialchars($ds[ 'youtube' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-youtube" style="font-size: 1rem;"></i></a>';//https
            } else {
                $youtube = '<a class="youtube" href="http://' . htmlspecialchars($ds[ 'youtube' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-youtube" style="font-size: 1rem;"></i></a>';//http
            }
        } else {
            $youtube = '';
        }

        if ($ds[ 'rss' ] != '') {
            if (stristr($ds[ 'rss' ], "https://")) {
                $rss = '<a class="rss" href="' . htmlspecialchars($ds[ 'rss' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-rss" style="font-size: 1rem;"></i></a>';//https
            } else {
                $rss = '<a class="rss" href="http://' . htmlspecialchars($ds[ 'rss' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-rss" style="font-size: 1rem;"></i></a>';//http
            }
        } else {
            $rss = '';
        }

        if ($ds[ 'vine' ] != '') {
            if (stristr($ds[ 'vine' ], "https://")) {
                $vine = '<a class="vine" href="' . htmlspecialchars($ds[ 'vine' ]) . '" target="_blank" rel="nofollow"><i class="fab fa-vine" style="font-size: 1rem;"></i></a>';//https
            } else {
                $vine = '<a class="vine" href="http://' . htmlspecialchars($ds[ 'vine' ]) . '" target="_blank" rel="nofollow"><i class="fab fa-vine" style="font-size: 1rem;"></i></a>';//http
            }
        } else {
            $vine = '';
        }

        if ($ds[ 'flickr' ] != '') {
            if (stristr($ds[ 'flickr' ], "https://")) {
                $flickr = '<a class="flickr" href="' . htmlspecialchars($ds[ 'flickr' ]) . '" target="_blank" rel="nofollow"><i class="fab fa-flickr" style="font-size: 1rem;"></i></a>';//https
            } else {
                $flickr = '<a class="flickr" href="http://' . htmlspecialchars($ds[ 'flickr' ]) . '" target="_blank" rel="nofollow"><i class="fab fa-flickr" style="font-size: 1rem;"></i></a>';//http
            }
        } else {
            $flickr = '';
        }

        if ($ds[ 'linkedin' ] != '') {
            if (stristr($ds[ 'linkedin' ], "https://")) {
                $linkedin = '<a class="linkedin" href="' . htmlspecialchars($ds[ 'linkedin' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-linkedin" style="font-size: 1rem;"></i></a>';//https
            } else {
                $linkedin = '<a class="linkedin" href="http://' . htmlspecialchars($ds[ 'linkedin' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-linkedin" style="font-size: 1rem;"></i></a>';//http
            }
        } else {
            $linkedin = '';
        }

        if ($ds[ 'instagram' ] != '') {
            if (stristr($ds[ 'instagram' ], "https://")) {
                $instagram = '<a class="instagram" class="url-link" href="' . htmlspecialchars($ds[ 'instagram' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-instagram" style="font-size: 1rem;"></i></a>';//https
            } else {
                $instagram = '<a class="instagram" class="url-link" href="http://' . htmlspecialchars($ds[ 'instagram' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-instagram" style="font-size: 1rem;"></i></a>';//http
            }
        } else {
            $instagram = '';
        }

        if ($ds[ 'steam' ] != '') {
            if (stristr($ds[ 'steam' ], "https://")) {
                $steam = '<a class="steam" href="' . htmlspecialchars($ds[ 'steam' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-steam" style="font-size: 1rem;"></i></a>';//https
        } else {
                $steam = '<a class="steam" href="http://' . htmlspecialchars($ds[ 'steam' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-steam" style="font-size: 1rem;"></i></a>';//http
            }
        } else {
            $steam = '';
        }

        if ($ds[ 'discord' ] != '') {
            if (stristr($ds[ 'discord' ], "https://")) {
                $discord = '<a class="discord" href="' . htmlspecialchars($ds[ 'discord' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-discord" style="font-size: 1rem;"></i></a>';//https
            } else {
                $discord = '<a class="discord" href="http://' . htmlspecialchars($ds[ 'discord' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-discord" style="font-size: 1rem;"></i></a>';//http
            }
        } else {
            $discord = '';
        }


    if (isset($_GET[ 'new_lang' ])) {
        if (file_exists('languages/' . $_GET[ 'new_lang' ])) {

            $lang = preg_replace("[^a-z]", "", $_GET[ 'new_lang' ]);
            $_SESSION[ 'language' ] = $lang;

            if ($userID) {
                safe_query("UPDATE " . PREFIX . "user SET language='" . $lang . "' WHERE userID='" . $userID . "'");
            }
        }

        if (isset($_GET[ 'query' ])) {
            $query = rawurldecode($_GET[ 'query' ]);
            header("Location: ./" . $query);        
        } else {
            header("Location: index.php");
        }
    } else {
        $_language->readModule('index');

        $filepath = "languages/";
        $langs = array();    

        if ($dh = opendir($filepath)) {
            while ($file = mb_substr(readdir($dh), 0, 2)) {
                if ($file != "." && $file != ".." && is_dir($filepath . $file)) {
                    if (isset($mysql_langs[ $file ])) {
                        $name = $mysql_langs[ $file ];
                        $name = ucfirst($name);
                        $langs[ $name ] = $file;
                    } else {
                        $langs[ $file ] = $file;
                    }
                }
            }
            closedir($dh);
        }
        if (defined("SORT_NATURAL")) {
            $sortMode = SORT_NATURAL;
        } else {
            $sortMode = SORT_LOCALE_STRING;
        }
        ksort($langs, $sortMode);

        $querystring = '';
        if ($modRewrite === true) {
            $path = rawurlencode(str_replace($GLOBALS[ 'rewriteBase' ], '', $_SERVER[ 'REQUEST_URI' ]));

        } else {
            $path = rawurlencode($_SERVER[ 'QUERY_STRING' ]);
            if (!empty($path)) {
                $path = "?".$path;
            }
        }
        if (!empty($path)) {
            $querystring = "&amp;query=" . $path;
        }        

        foreach ($langs as $lang => $flag) {
        }

            $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings WHERE de_lang='1'"));
            if (@$dx[ 'de_lang' ] != '1') {
                $de_languages = 'xxx';
                $de_button = '';
            } else {
                $de_languages = '<a href="index.php?new_lang=de'. $querystring . '" data-toggle="tooltip" title="' . $index_language[ 'de' ] . '"> de </a>';
                
            };

            $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings WHERE en_lang='1'"));
            if (@$dx[ 'en_lang' ] != '1') {
                $en_languages = '';
                $en_button = '';
            } else {
                $en_languages = '<a href="index.php?new_lang=en'. $querystring . '" data-toggle="tooltip" title="' . $index_language[ 'en' ] . '">/ en </a>';
                
            };

            $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings WHERE it_lang='1'"));
            if (@$dx[ 'it_lang' ] != '1') {
                $it_languages = '';
                $it_button = '';
            } else {
                $it_languages = '<a href="index.php?new_lang=it'. $querystring . '" data-toggle="tooltip" title="' . $index_language[ 'it' ] . '">/ it </a>';
                
            };

            if($loggedin) {
                $registration = '';
                $login_here = ''.$de_languages.''.$en_languages.''.$it_languages.'';
            } else {
                $registration = '<li class="list-group-item">
                                    <a href="index.php?site=register" class="btn btn-labeled btn-default">
                                        <span class="btn-label">
                                            <i class="bi bi-chevron-right"></i>
                                        </span>
                                        ' . $plugin_language[ 'registration' ] .'
                                    </a>
                                </li>';
                $login_here = '<li class="list-group-item">
                                    <a href="index.php?site=login" class="btn btn-labeled btn-default">
                                        <span class="btn-label">
                                            <i class="bi bi-chevron-right"></i>
                                        </span>
                                        ' . $plugin_language[ 'login_here' ] .'
                                    </a>
                                </li> | '.$de_languages.''.$en_languages.''.$it_languages.'';
            }

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

            $data_array['$registration'] = $registration;
            $data_array['$login_here'] = $login_here;

            $data_array['$top_search'] = $plugin_language['top_search'];
    	
            $template = $GLOBALS["_template"]->loadTemplate("topbar_one","content", $data_array, $plugin_path);
            echo $template;
    	}
    }
}
/*
<div class="social-links d-none d-md-flex align-items-center">
            <a href="https://discord.gg/qNTgX7B" target="_blank" class="linkedin"><i class="bi bi-discord" style="font-size: 1rem;"></i></a>
            <a href="https://t.me/dkz_gaming_eu_community" target="_blank" class="linkedin"><i class="bi bi-telegram" style="font-size: 1rem;"></i></a>
            <a href="https://www.facebook.com/DKZgaming.eu/" target="_blank" class="facebook"><i class="bi bi-facebook" style="font-size: 1rem;"></i></a>
            <a href="https://instagram.com/digitalkillerz/" target="_blank" class="instagram"><i class="bi bi-instagram" style="font-size: 1rem;"></i></a>
            <a href="https://www.twitch.tv/slimjjs" target="_blank" class="linkedin"><i class="bi bi-twitch" style="font-size: 1rem;"></i></a>
            <a href="https://www.youtube.com/user/DigitalKillerZv" target="_blank" class="linkedin"><i class="bi bi-youtube" style="font-size: 1rem;"></i></a>
            <a href="https://www.tiktok.com/@dkzgaming.eu" target="_blank" class="linkedin"><i class="bi bi-tiktok" style="font-size: 1rem;"></i></a>
            <a href="https://steamcommunity.com/groups/DKZ_Community" target="_blank" class="linkedin"><i class="bi bi-steam" style="font-size: 1rem;"></i></a>         
            <a href="https://twitter.com/DKZ_GAMING" target="_blank" class="twitter"><i class="bi bi-twitter-x" style="font-size: 1rem;"></i></a>
        </div>*/
?>