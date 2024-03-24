<?php
/*-----------------------------------------------------------------\
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
\------------------------------------------------------------------*/

# Sprachdateien aus dem Plugin-Ordner laden
	$pm = new plugin_manager(); 
	$_lang = $pm->plugin_language("about_sponsor", $plugin_path);


$data_array = array();
$about_sponsor = $GLOBALS["_template"]->loadTemplate("sc_about_sponsor","head", $data_array, $plugin_path);
echo $about_sponsor;


$_language->readModule('about');
$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_about_us");
if (mysqli_num_rows($ergebnis)) {
    $ds = mysqli_fetch_array($ergebnis);
        $title = $ds[ 'title' ];
        $text = $ds[ 'text' ];
            
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($title);
        $title = $translate->getTextByLanguage($title);
        $translate->detectLanguages($text);
        $text = $translate->getTextByLanguage($text);

        $maxaboutchars = $ds[ 'aboutchars' ];
        if (empty($maxaboutchars)) {
        $maxaboutchars = 260;
        }  

        $text = preg_replace("/<div>/", "", $text);
        $text = preg_replace("/<p>/", "", $text);
        $text = preg_replace("/<strong>/", "", $text);
        $text = preg_replace("/<em>/", "", $text);
        $text = preg_replace("/<s>/", "", $text);
        $text = preg_replace("/<u>/", "", $text);
        $text = preg_replace("/<blockquote>/", "", $text);

        $text = preg_replace("//", "", substr( $text, 0, $maxaboutchars  ) ) . ' ... <br><br><div class="text-end"> <a href="index.php?site=about_us" class="btn btn-dark btn-sm">READ MORE <i class="bi bi-chevron-double-right"></i></a></div>';
    
        $data_array = array();
        $data_array['$text'] = $text;

    

} else {
    $text = $_lang[ 'no_about' ];
}

$data_array = array();
$data_array['$text'] = $text;

$data_array['$about_title']=$_lang['about_title'];

$about_sponsor = $GLOBALS["_template"]->loadTemplate("sc_about_sponsor","contentabout", $data_array, $plugin_path);
echo $about_sponsor;

$mainsponsors = safe_query("SELECT * FROM " . PREFIX . "plugins_sponsors WHERE (displayed = '1' AND mainsponsor = '1') ORDER BY sort");
if (mysqli_num_rows($mainsponsors)) {
    if (mysqli_num_rows($mainsponsors) == 1) {
        $main_title = $_lang[ 'mainsponsor' ];
    } else {
        $main_title = $_lang[ 'mainsponsors' ];
    }

    if ($da = mysqli_fetch_array($mainsponsors)) {
        if (!empty($da[ 'banner' ])) {
            $sponsor = '<img src="/includes/plugins/sponsors/images/' . $da[ 'banner' ] . '" alt="' . htmlspecialchars($da[ 'name' ]) . '" class="img-fluid">';
        } else {
            $sponsor = $da[ 'name' ];
        }

        $sponsorID = $da[ 'sponsorID' ];
        $text = $da['info'];
        $url = $da['url'];

        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_about_us");
        $ds = mysqli_fetch_array($ergebnis);

        $maxaboutchars = @$ds[ 'aboutchars' ];
        if (empty($maxaboutchars)) {
            $maxaboutchars = 60;
        }  

        $text = preg_replace("/<div>/", "", $text);
        $text = preg_replace("/<p>/", "", $text);
        $text = preg_replace("/<strong>/", "", $text);
        $text = preg_replace("/<em>/", "", $text);
        $text = preg_replace("/<s>/", "", $text);
        $text = preg_replace("/<u>/", "", $text);
        $text = preg_replace("/<blockquote>/", "", $text);

        $text = preg_replace("//", "", substr( $text, 0, $maxaboutchars  ) ) . ' ... ';

        $data_array = array();
        $data_array['$sponsorID'] = $sponsorID;
        $data_array['$sponsor'] = $sponsor;
        $data_array['$name'] = $da['name'];
        $data_array['$url'] = $url;
        $data_array['$info'] = $text;

        $data_array['$sponsor_title']=$_lang['sponsor_title'];

        $about_sponsor = $GLOBALS["_template"]->loadTemplate("sc_about_sponsor","contentsponsor", $data_array, $plugin_path);
        echo $about_sponsor;
    }
}
$about_sponsor = $GLOBALS["_template"]->loadTemplate("sc_about_sponsor","foot", $data_array, $plugin_path);
echo $about_sponsor;

?>