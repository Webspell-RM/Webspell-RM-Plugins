<script src='https://www.goat1000.com/jquery.tagcanvas.min.js'></script>
  
      <script type="text/javascript">
      $(document).ready(function() {
        if(!$('#myCanvas').tagcanvas({
          textColour: '#000',
          outlineColour: '#FFA641',
          reverse: true,
          depth: 0.8,
          maxSpeed: 0.02
        },'tags')) {
          // something went wrong, hide the canvas container
          $('#myCanvasContainer').hide();
        }
      });
    </script><?php
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

    $plugin_data= array();
    $plugin_data['$title']=$plugin_language['toptags'];
    $plugin_data['$subtitle']='Tags';

    $template = $GLOBALS["_template"]->loadTemplate("tags","head", $plugin_data, $plugin_path);
    echo $template;

if (isset($_GET[ 'tag' ])) {
    $tag = $_GET[ 'tag' ];
    $sql = safe_query("SELECT * FROM " . PREFIX . "tags WHERE tag='" . $tag . "' order by NEWID()");
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
        echo "<h1>" . $_language->module[ 'search' ] . "</h1>";
        usort($data, array('Tags', 'sortByDate'));
        echo "<p class=\"text-center\"><strong>" . count($data) . "</strong> " . $_language->module[ 'results_found' ] .
            "</p><br><br>";
        foreach ($data as $entry) {
            $date = getformatdate($entry[ 'date' ]);
            $type = $entry[ 'type' ];
            $auszug = $entry[ 'content' ];
            $link = $entry[ 'link' ];
            $title = $entry[ 'title' ];

            $data_array = array();
            $data_array['$date'] = $date;
            $data_array['$link'] = $link;
            $data_array['$title'] = $title;
            $data_array['$auszug'] = $auszug;

            $search_tags = $GLOBALS["_template"]->replaceTemplate("search_tags", $data_array);
            echo $search_tags;
        }
    } else {
        $data_array= array();
        $data_array['$title']=$plugin_language['search'];
        $data_array['$subtitle']='Tags';

        $template = $GLOBALS["_template"]->loadTemplate("tags","head", $data_array, $plugin_path);
        echo $template;

        $tag = htmlspecialchars($tag);
        $text = sprintf($_language->module[ 'no_result' ], $tag);
        $data_array = array();
        $data_array['$text'] = $text;

        $search_tags_no_result = $GLOBALS["_template"]->replaceTemplate("search_tags_no_result", $data_array);
        echo $search_tags_no_result;
    }
} else {

    
    function tags_top_10($a1, $a2) {
        if ($a1[ 'count' ] == $a2[ 'count' ]) {
            return 0;
        } else {
            return $a1[ 'count' ] < $a2[ 'count' ] ? -1 : 1;
        }
    }

    $tags = \webspell\Tags::getTagCloud();
    usort($tags[ 'tags' ], "tags_top_10");
    $str = '';

    $counter = min(10, count($tags[ 'tags' ]));
    for ($i = 0; $i < $counter; $i++) {
        $tag = $tags[ 'tags' ][ $i ];
        $size = \webspell\Tags::GetTagSizeLogarithmic($tag[ 'count' ], $tags[ 'min' ], $tags[ 'max' ], 10, 25, 0);
        $str .= " <a type='button' class='btn btn-outline-secondary'style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;margin: 2px;' href='index.php?site=tags&amp;tag=" . $tag[ 'name' ] . "' style='font-size:" . $size .
            "px;text-decoration:none;'>" . $tag[ 'name' ] . "</a> ";        
    }

    echo'<div class="card">
        <div class="card-body post">
       
            <div id="myCanvasContainer">
                <canvas class="w-100" height="300" id="myCanvas" style="">
                </canvas>
            </div>

            <div id="tags" style="display: none;">
                <ul>
                    <li>'.$str.'</li> 
                </ul>
            </div>
        </div></div>';   
}
?>
