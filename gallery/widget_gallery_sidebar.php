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
\¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*/

$pm = new plugin_manager();
$plugin_language = $pm->plugin_language("gallery", $plugin_path);


$plugin_data = array();
$plugin_data['$title'] = $plugin_language['gallery'];
$plugin_data['$subtitle'] = 'Gallery';

$template = $GLOBALS["_template"]->loadTemplate("gallery", "widget_head", $plugin_data, $plugin_path);
echo $template;

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_pictures WHERE displayed_gal = '1' ORDER BY RAND() LIMIT 1");

if (mysqli_num_rows($ergebnis)) {

    while ($dd = mysqli_fetch_array($ergebnis)) {

        $media = $dd['pic_video'];


        if ($media == '0') {
            if (file_exists('./includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.jpg')) {
                $file = './includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.jpg';
            } elseif (file_exists('./includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.gif')) {
                $file = './includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.gif';
            } elseif (file_exists('./includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.png')) {
                $file = './includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.png';
            } elseif (file_exists('./includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.webp')) {
                $file = './includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.webp';
            } elseif (file_exists('./includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.avif')) {
                $file = './includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.avif';
            } else {
                $file = '';
            }

            if (file_exists('./includes/plugins/gallery/images/large/' . $dd['picID'] . '.jpg')) {
                $file_1 = './includes/plugins/gallery/images/large/' . $dd['picID'] . '.jpg';
            } elseif (file_exists('./includes/plugins/gallery/images/large/' . $dd['picID'] . '.gif')) {
                $file_1 = './includes/plugins/gallery/images/large/' . $dd['picID'] . '.gif';
            } elseif (file_exists('./includes/plugins/gallery/images/large/' . $dd['picID'] . '.png')) {
                $file_1 = './includes/plugins/gallery/images/large/' . $dd['picID'] . '.png';
            } elseif (file_exists('./includes/plugins/gallery/images/large/' . $dd['picID'] . '.webp')) {
                $file_1 = './includes/plugins/gallery/images/large/' . $dd['picID'] . '.webp';
            } elseif (file_exists('./includes/plugins/gallery/images/large/' . $dd['picID'] . '.avif')) {
                $file_1 = './includes/plugins/gallery/images/large/' . $dd['picID'] . '.avif';
            } else {
                $file_1 = '';
            }
        } else {

            $videoID = $dd['youtube'];
            $name = $dd['name'];
            $preview = 'https://img.youtube.com/vi/' . $videoID . '/hqdefault.jpg';
            $video = '<img class="card-img-top" src="' . $preview . '" alt="' . $name . '" style="border-top-left-radius: 0px;border-top-right-radius: 0px;">';
        }

        if (isset($_POST['name'])) {
            $name = htmlspecialchars($_POST['name']);
        } else {
            $name = '';
        }



        if ($media == '0') {
            $link = '<a class="thumbnail" href="index.php?site=gallery&picID=' . $dd['picID'] . '">
                <img class="img-fluid galerie" style="max-width: 100%;height: 250px;border-radius: var(--bs-border-radius);"                         
                 src="' . $file . '"
                 alt="' . $dd['name'] . '"></a>';
        } else {

            $link = '<a class="thumbnail" href="index.php?site=gallery&picID=' . $dd['picID'] . '">
                <img class="img-fluid galerie" style="max-width: 100%;height: 250px;border-radius: var(--bs-border-radius);"                         
                 src="' . $preview . '"
                 alt="' . $dd['name'] . '"></a>';
        }

        $data_array = array();
        $data_array['$banner'] = $link;

        $template = $GLOBALS["_template"]->loadTemplate("gallery", "widget_gallery", $data_array, $plugin_path);
        echo $template;
    }
} else {

    echo '<div class="card"><div class="row" style="margin-top: 25px;margin-bottom: 0px;margin-left: 0px;margin-right: 0px"><div class="col-md text-center">Images not found<br><br></div></div></div>';
}
