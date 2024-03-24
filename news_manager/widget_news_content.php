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
    $plugin_language = $pm->plugin_language("news", $plugin_path);

    // -- COMMENTS INFORMATION -- //
include_once("news_functions.php");

    $data_array = array();
    $data_array['$headlines-title']=$plugin_language['headlines-title'];
    $template = $GLOBALS["_template"]->loadTemplate("widget_news_content","head", $data_array, $plugin_path);
    echo $template;


if (isset($rubricID) && $rubricID) {
    $only = "AND rubric='" . $rubricID . "'";
} else {
    $only = '';
}

        $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_news_settings");
        $ds = mysqli_fetch_array($settings);

    
        $maxheadlines = $ds[ 'headlines' ];
        if (empty($maxheadlines)) {
        $maxheadlines = 4;
        }
 
$ergebnis = safe_query(
    "SELECT
        *
    FROM
        " . PREFIX . "plugins_news ORDER BY date DESC 
    LIMIT 0," . $maxheadlines
);
if (mysqli_num_rows($ergebnis)) {
    #echo '<div class="container"><ul class="nav">';
    $n = 1;
    while ($ds = mysqli_fetch_array($ergebnis)) {
        $date1 = date("M", $ds['date']);
        $date2 = date("j", $ds['date']);
        #$date = getformatdate($ds[ 'date' ]);
        $time = getformattime($ds[ 'date' ]);
        $news_id = $ds[ 'newsID' ];

        
        $message_array = array();
        $query =
            safe_query(
                "SELECT
                    *
                FROM
                    " . PREFIX . "plugins_news"
            );
        while ($qs = mysqli_fetch_array($query)) {
            $message_array[ ] = array(
                'headline' => $qs[ 'headline' ],
                'message' => $qs[ 'content' ],
            );
        }

        $headline = $ds['headline'];
        $headline = $headline;

        $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_news_settings");
        $dx = mysqli_fetch_array($settings);

      
        if (empty($maxheadlinechars)) {
        $maxheadlinechars = $dx[ 'headlineschars' ];
        }

        if (mb_strlen($headline) > $maxheadlinechars) {
            $headline = mb_substr($headline, 0, $maxheadlinechars);
            $headline .= '...';
        }
        
        

        $rubrikname = getnewsrubricname($ds[ 'rubric' ]);
        $rubrikname_link = getinput($rubrikname);
        $rubricpic_name = getnewsrubricpic($ds[ 'rubric' ]);
        $rubricpic = $plugin_path.'/images/news-rubrics/' . $rubricpic_name;
        
        if (!file_exists($rubricpic) || $rubricpic_name == '') {
            $rubricpic = '';
        } else {
            $rubricpic = '<img src="' . $rubricpic . '" alt="" class="img-fluid">';
            }

        $line1 = '<a class="p-1 badge bg-primary" href="index.php?site=news_manager&action=news_contents&newsID=' . $ds[ 'newsID' ] . '" >READMORE</a>';
       
        $data_array = array();
        #$data_array['$date'] = $date;
        $data_array['$time'] = $time;
        $data_array['$news_id'] = $news_id;
        $data_array['$line1'] = $line1;
        $data_array['$headline'] = $headline;
        $data_array['$rubricpic'] = $rubricpic;
        $data_array['$date1'] = $date1;
        $data_array['$date2'] = $date2;

        $template = $GLOBALS["_template"]->loadTemplate("widget_news_content","content", $data_array, $plugin_path);
        echo $template;
        $n++;
    }
    #echo '</ul></div>';
    unset($rubricID);
}
        $data_array = array();
        $template = $GLOBALS["_template"]->loadTemplate("widget_news_content","foot", $data_array, $plugin_path);
        echo $template;
