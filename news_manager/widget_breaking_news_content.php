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
  $plugin_language = $pm->plugin_language("news", $plugin_path);

$filepath = $plugin_path."images/";
 
$ds = safe_query("SELECT * FROM " . PREFIX . "plugins_news_manager");
echo '<div class="container">
        <div class="ticker-container">
          <div class="row">

            <div class="ticker-caption col-lg-4">
              <p>Breaking News</p>
            </div>
          <div class="col-lg-8">
            <ul>';
                        
$x = 1;
if (mysqli_num_rows($ds)) {
    while ($db = mysqli_fetch_array($ds)) {
        $headline=""; $url=""; $content="";
        if($x==1) { echo '<div class="ticker-active">'; } else { echo '<div class="not-active">'; }
        
        $headline = $db[ 'headline' ];
        $newsID = $db[ 'newsID' ];
        $content = $db[ 'content' ];
    
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($headline);
        $headline = $translate->getTextByLanguage($headline);
        $translate->detectLanguages($content);
        $content = $translate->getTextByLanguage($content);
    
    
        $data_array = array();
        $data_array['$url'] = '<li><span>'.$headline.' &ndash; <a href="index.php?site=news_manager&action=news_contents&newsID='.$newsID.'">'.$content.'</a></span></li>';
        
        $template = $GLOBALS["_template"]->loadTemplate("breaking_news","widget_content", $data_array, $plugin_path);
        echo $template;

        echo '</div>'; $x++;
    }
    
}

          echo'</ul>
              </div>
            </div>
          </div>
        </div>';
