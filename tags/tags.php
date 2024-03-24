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
    $plugin_language = $pm->plugin_language("tags", $plugin_path);


if (isset($_GET[ 'tag' ])) {
    $data_array= array();
        $data_array['$title']=$plugin_language['search'];
        $data_array['$subtitle']='Tags';

        $template = $GLOBALS["_template"]->loadTemplate("tags","head", $data_array, $plugin_path);
        echo $template;

    $tag = $_GET[ 'tag' ];
    $sql = safe_query("SELECT * FROM " . PREFIX . "tags WHERE tag='" . $tag . "'");
    if ($sql->num_rows) {
        $data = array();
        while ($ds = mysqli_fetch_assoc($sql)) {
            $data_check = null;
            if ($ds[ 'rel' ] == "news") {
                $data_check = \webspell\Tags::getNews($ds[ 'ID' ]);
            } elseif ($ds[ 'rel' ] == "articles") {
                $data_check = \webspell\Tags::getArticle($ds[ 'ID' ]);
            } elseif ($ds[ 'rel' ] == "static") {
                $data_check = \webspell\Tags::getStaticPage($ds[ 'ID' ]);
            } elseif ($ds[ 'rel' ] == "faq") {
                $data_check = \webspell\Tags::getFaq($ds[ 'ID' ]);
            }            

            if (is_array($data_check)) {
                $data[ ] = $data_check;
            }
        }
        

        echo "<p class=\"text-center\"><strong>" . count($data) . "</strong> " . $plugin_language[ 'results_found' ] .
            "</p><br><br>";
        foreach ($data as $entry) {
            $date = getformatdate($entry[ 'date' ]);
            $type = $entry[ 'type' ];
            $auszug = $entry[ 'content' ];
            $link = $entry[ 'link' ];
            $title = $entry[ 'title' ];
            $cat = $entry[ 'cat' ];
            $link_cat = $entry[ 'link_cat' ];

            $data_array = array();
            $data_array['$date'] = $date;
            $data_array['$links'] = $link;
            $data_array['$title'] = $title;
            $data_array['$auszug'] = $auszug;
            $data_array['$cat'] = $cat;
            $data_array['$link_cat'] = $link_cat;
            

            $template = $GLOBALS["_template"]->loadTemplate("tags","search_tags", $data_array, $plugin_path);
            echo $template;
          
        }
    } else {

        $data_array= array();
        $data_array['$title']=$plugin_language['search'];
        $data_array['$subtitle']='Tags';

        $template = $GLOBALS["_template"]->loadTemplate("tags","head", $data_array, $plugin_path);
        echo $template;

        $tag = htmlspecialchars($tag);
        $text = sprintf($plugin_language[ 'no_result' ], $tag);

        $data_array = array();
        $data_array['$text'] = $text;

        $template = $GLOBALS["_template"]->loadTemplate("tags","search_tags_no_result", $plugin_data, $plugin_path);
        echo $template;
    }
} 
