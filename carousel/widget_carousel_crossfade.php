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
  $plugin_language = $pm->plugin_language("carousel", $plugin_path);

$filepath = $plugin_path."images/";
$filepathvid = $plugin_path."videos/";
 
$ds = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_carousel_settings"));

echo'
<section id="hero" style="height: '.$ds['carousel_height'].';">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">

        <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>

        <div class="carousel-inner" role="listbox">';
$carousel = safe_query("SELECT * FROM " . PREFIX . "plugins_carousel WHERE (displayed = '1') ORDER BY sort");
$x = 1;

if (mysqli_num_rows($carousel)) {
    while ($db = mysqli_fetch_array($carousel)) {

        $title=""; $link=""; $description="";
        $timesec = !empty($db['time_pic']) ? ($db['time_pic'] * 1000) : 10000;
        if($x==1) { echo '<div class="carousel-item active" data-bs-interval="'.$timesec.'">'; } else { echo '<div class="carousel-item" data-bs-interval="'.$timesec.'">'; }
        if (!empty($db['carousel_vid'])) {
            // Se è disponibile un video, visualizzalo
            $carousel_pic = '<video autoplay="autoplay" loop muted playsInline width="100%" class="pic" controls><source src="' . $filepathvid . $db['carousel_vid'] . '" type="video/mp4"></video>';
        } elseif (!empty($db['carousel_pic'])) {
            // Se è disponibile un'immagine, visualizzala
            $carousel_pic = '<img class="pic" src="' . $filepath . $db['carousel_pic'] . '" alt="' . htmlspecialchars($db['title']) .
                '" style="height: '.$ds['carousel_height'].';">';
        } else {
            // Nessun video o immagine disponibile, assegna il titolo
            $title = $db['title'];
        }
        

        $carouselID = $db[ 'carouselID' ];
        $title = $db[ 'title' ];
        $ani_title = $db[ 'ani_title' ];
        $ani_link = $db[ 'ani_link' ];
        $ani_description = $db[ 'ani_description' ];
        $title = $db[ 'title' ];
        $description = $db[ 'description' ];
      

        if ($db[ 'link' ] != '') {
            if (stristr($db[ 'link' ], "https://")) {
                $link = '<a href="'.$db[ 'link' ].'" class="btn-get-started animated '.$ani_link.' scrollto">'.$plugin_language['read_more'].'</a>';//https
            } else {
                $link = '<a href="'.$db[ 'link' ].'" class="btn-get-started animated '.$ani_link.' scrollto">'.$plugin_language['read_more'].'</a>';//http
            }
        } else {
            $link = '';
        }        
    
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($title);
        $title = $translate->getTextByLanguage($title);
        $translate->detectLanguages($description);
        $description = $translate->getTextByLanguage($description);
    
        $data_array = array();
        $data_array['$carouselID'] = $carouselID;
        $data_array['$carousel_pic'] = $carousel_pic;
        $data_array['$title'] = $title;
        $data_array['$ani_title'] = $ani_title;
        $data_array['$ani_description'] = $ani_description;
        $data_array['$link'] = $link;
        $data_array['$description'] = $description;
                
        $template = $GLOBALS["_template"]->loadTemplate("widget_carousel_only","content", $data_array, $plugin_path);
        echo $template;
echo '</div>';
        $x++;
    }
}

    echo '</div><a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
                </a>
                <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
                </a>  
        </div>
</section>';

