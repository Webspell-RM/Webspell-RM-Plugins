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
  $plugin_language = $pm->plugin_language("breaking_news", $plugin_path);

$_language->readModule('breaking_news');

$filepath = $plugin_path."images/";
 
$carousel = safe_query("SELECT * FROM " . PREFIX . "plugins_breaking_news WHERE (displayed = '1') ORDER BY sort");
echo '
<div class="container ticker-container">
  <div class="row">

  <div class="ticker-caption col-lg-4">
    <p>Breaking News</p>
  </div>
  <div class="col-lg-8">
  <ul>';
                        
$x = 1;
if (mysqli_num_rows($carousel)) {
    while ($db = mysqli_fetch_array($carousel)) {
        $title=""; $url=""; $description="";
        if($x==1) { echo '<div class="ticker-active">'; } else { echo '<div class="not-active">'; }
        
        $carouselID = $db[ 'breakingnewsID' ];
        $title = $db[ 'title' ];
        
        #if (stristr($db[ 'link' ], "http://")) {
        #$link = $db[ 'link' ];
      
        #} else {
        #$link = 'http://' . $db[ 'link' ] . '';
        #}
       

      $title = $db[ 'title' ];
      $description = $db[ 'description' ];
    
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($title);
        $title = $translate->getTextByLanguage($title);
        $translate->detectLanguages($description);
        $description = $translate->getTextByLanguage($description);
    
    
        $data_array = array();
        if($db['url'] != '') $data_array['$url'] = '<li><span>'.$title.' &ndash; <a href="'.$db['url'].'">'.$description.'</a></span></li>';
        else $data_array['$url'] = '';
        $data_array['$carouselID'] = $carouselID;
        $data_array['$title'] = $title;
        
        $template = $GLOBALS["_template"]->loadTemplate("breaking_news","widget_content", $data_array, $plugin_path);
        echo $template;

        echo '</div>'; $x++;
    }
    
}

        echo '</ul>
</div></div></div>';
