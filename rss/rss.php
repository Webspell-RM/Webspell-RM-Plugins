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
    $plugin_language = $pm->plugin_language("rss", $plugin_path);

    $plugin_data= array();
    $plugin_data['$title']=$plugin_language['title'];
    $plugin_data['$subtitle']='rss';
    $template = $GLOBALS["_template"]->loadTemplate("rss","widget_head", $plugin_data, $plugin_path);
    echo $template;

$dx = safe_query("SELECT * FROM " . PREFIX . "plugins_rss WHERE displayed = '1' ORDER BY sort");
$anzcats = mysqli_num_rows($dx);
if ($anzcats) {

    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_rss_settings");
                    $dn = mysqli_fetch_array($settings);
    
    $sql = safe_query("SELECT * FROM ".PREFIX."plugins_rss WHERE displayed = '1' ORDER BY sort");
    
    echo'<div class="card"><div class="row">';
    include 'includes/plugins/rss/feed_reader.php';
    while($ds = mysqli_fetch_array($sql)) {
        $rss_name = $ds['rss_name'];
        $rsslink = $ds['rss_id'];
        $rss_number = $ds['rss_num'];
        $rss_letters = $dn['rss_letters'];
    
    echo '<div class="col-sm" style = "margin: 5px 5px 5px 5px;"><i class="bi bi-rss" style="font-size: 1rem;"></i> '.$rss_name.'';
    echo '<div class="row350">';
    // Esempio di utilizzo
    $feeds = [ ''.$rsslink.'' ];

    // Parametri di visualizzazione
    $limit = $rss_number; // Numero di notizie da visualizzare
    $chars = $rss_letters; // Numero di caratteri per notizia
    $includeDate = true; // Includi la data della notizia
    $includeImages = true; // Includi le immagini della notizia
    $includeIcon = false; // Includi l'icona del sito
    $includeCreator = true; // Includi il creatore della notizia

    // Leggi e visualizza i feed
    foreach ($feeds as $feedUrl) {
        $result = readFeed($feedUrl, $limit, $chars, $includeDate, $includeImages, $includeIcon, $includeCreator);
        
        // Icona del sito
        if (!empty($result['siteIcon'])) {
            echo '<img src="' . $result['siteIcon'] . '" alt="Site Icon">';
        }

        // Visualizza le notizie
        foreach ($result['news'] as $newsItem) {
            echo '<strong>' . $newsItem['title'] . '</strong></br>';
            //echo '<p>' . $newsItem['description'] . '</p><a href="' . $newsItem['link'] . '">Continua</a>';
            if ($includeDate) {
                echo '<spam style="font-size:8px"><i class="bi bi-calendar" style="font-size: 10px;"></i> <i>' . $newsItem['pubDate'] . '</i></spam></br>';
            }
            
            if ($newsItem['creator'] <> '') {
                echo '<i class="bi bi-person-vcard" style="font-size: 1rem;"></i><em> ' . $newsItem['creator'] . '</em></br>';
            }
            echo '<div style="min-width: 200px"><p style="text-align: justify;">';
            if ($includeImages && !empty($newsItem['images'])) {
                foreach ($newsItem['images'] as $image) {
                    $variabile_pulita = strip_tags($newsItem['description']); // Rimuove i tag HTML
                    $variabile_pulita = trim($newsItem['description']); // Rimuove spazi all'inizio e alla fine
                    echo '<img class="img-fluid thumbnailUrl" src="' . $image . '">' . $variabile_pulita . '<br><i class="bi bi-chevron-double-right" style="font-size: smaller;"></i> <em><a href="'.$newsItem['link'].'">'.$plugin_language[ 'readmore' ].'</a></em></div>';
                } 
            } else {
                $variabile_pulita = strip_tags($newsItem['description']); // Rimuove i tag HTML
                $variabile_pulita = trim($newsItem['description']); // Rimuove spazi all'inizio e alla fine
                echo '' . $variabile_pulita . '<br><i class="bi bi-chevron-double-right" style="font-size: smaller;"></i> <em><a href="'.$newsItem['link'].'">'.$plugin_language[ 'readmore' ].'</a></em></div>';
         }
      }
    }
    
    echo '</div></div>';   
    
                $data_array = array();
                $template = $GLOBALS["_template"]->loadTemplate("rss","content", $data_array, $plugin_path);
                echo $template;
    }
    echo'</div></div>';
} else {
   echo $plugin_language['no_entries'];
}    
?>