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
  $plugin_language = $pm->plugin_language("projectslider", $plugin_path);


$filepath = $plugin_path."images/";
echo '<div class="section-content-widget">';
 
$carousel = safe_query("SELECT * FROM " . PREFIX . "plugins_projectslider WHERE (displayed = '1') ORDER BY sort");
echo '<div id="projectExampleControls" class="carousel carousel-plugin slide" data-bs-ride="carousel">
    <!-- Indicators -->
    <div class="carousel-indicators">';
       if(mysqli_num_rows($carousel)) {
           for($i=0; $i<=(mysqli_num_rows($carousel)-1); $i++) {
               if($i==0) {
                    echo '<button type="button" data-bs-target="#projectExampleControls" data-bs-slide-to="'.$i.'" class="active" aria-current="true" aria-label="Slide 1"></button>';
               } else {
                    echo '<button type="button" data-bs-target="#projectExampleControls" data-bs-slide-to="'.$i.'" aria-label="Slide 1"></button>';
               }
           }       
       }
       echo '</div>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">';
$x = 1;
if (mysqli_num_rows($carousel)) {
    while ($db = mysqli_fetch_array($carousel)) {
        $title=""; $link=""; $description="";
        if($x==1) { echo '<div class="carousel-item active project-height">'; } else { echo '<div class="carousel-item project-height">'; }
        if (!empty($db[ 'carousel_pic' ])) {
            $carousel_pic = '<img src="' . $filepath . $db[ 'carousel_pic' ] . '" alt="' . htmlspecialchars($db[ 'title' ]) .
                '" class="img-fluid img-mobile">';
        } else {
            $title = $db[ 'title' ];
        }
        $carouselID = $db[ 'carouselID' ];
    $title = $db[ 'title' ];
        if (stristr($db[ 'link' ], "http://")) {
      $link = $db[ 'link' ];
      
    } else {
      $link = 'http://' . $db[ 'link' ] . '';
    }
       

      $ani_title = $db[ 'ani_title' ];
      $ani_link = $db[ 'ani_link' ];
      $ani_description = $db[ 'ani_description' ];
      $title = $db[ 'title' ];
      $description = $db[ 'description' ];
    
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
        $data_array['$ani_link'] = $ani_link;
        $data_array['$ani_description'] = $ani_description;
        $data_array['$link'] = $link;
        $data_array['$description'] = $description;
        
        $template = $GLOBALS["_template"]->loadTemplate("sc_projectslider","content", $data_array, $plugin_path);
        echo $template;

        echo '</div>'; $x++;
    }
    echo '</div>';
}
    echo '<button class="carousel-control-prev" type="button" data-bs-target="#projectExampleControls" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#projectExampleControls" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
          </div>';  
echo '</div>';