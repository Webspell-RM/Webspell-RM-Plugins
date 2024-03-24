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
$plugin_language = $pm->plugin_language("links", $plugin_path);

$filepath = $plugin_path."images/";


    $data_array = array();
    $data_array['$title']=$plugin_language['title'];    
    $data_array['$subtitle']='Links';

    $template = $GLOBALS["_template"]->loadTemplate("links","head", $data_array, $plugin_path);
    echo $template;


if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if ($action == "show" && is_numeric($_GET[ 'linkcatID' ])) {

    if(isset($_GET['page'])) $page=(int)$_GET['page'];
  else $page = 1;
    
    $linkcatID = $_GET[ 'linkcatID' ];
    $getcat = safe_query("SELECT * FROM " . PREFIX . "plugins_links_categories  WHERE linkcatID='$linkcatID'");
    $ds = mysqli_fetch_array($getcat);
    $linkcatname = $ds[ 'linkcatname' ];

    $articlecat = safe_query("SELECT * FROM " . PREFIX . "plugins_links WHERE linkcatID='$linkcatID' ORDER BY `sort`");
    if (mysqli_num_rows($articlecat)) {
        $data_array = array();
        $data_array['$linkcatname'] = $linkcatname;

        $data_array['$title_categories']=$plugin_language['title_categories'];
        $data_array['$categories']=$plugin_language['categories'];
        $data_array['$category']=$plugin_language['category'];
        

        $template = $GLOBALS["_template"]->loadTemplate("links","details_head", $data_array, $plugin_path);
        echo $template;
        
        $template = $GLOBALS["_template"]->loadTemplate("links","details_header", $data_array, $plugin_path);
        echo $template;

        $alle=safe_query("SELECT linkID FROM ".PREFIX."plugins_links WHERE linkcatID='$linkcatID' AND displayed = '1'");
        $gesamt = mysqli_num_rows($alle);
        $pages=1;

  
        $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_links_settings");
        $dn = mysqli_fetch_array($settings);

    
        $max = $dn[ 'links' ];
        if (empty($max)) {
        $max = 1;
        }

        $maxlinkschars = $dn[ 'linkchars' ];
        if (empty($maxlinkschars)) {
        $maxlinkschars = 200;
        } 
 

  for ($n=$max; $n<=$gesamt; $n+=$max) {
    if($gesamt>$n) $pages++;
  }


  if($pages>1) $page_link = makepagelink("index.php?site=links&action=show&linkcatID=$linkcatID", $page, $pages);
    else $page_link='';

  if ($page == "1") {
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_links  WHERE linkcatID='$linkcatID' AND displayed = '1' ORDER BY date DESC LIMIT 0,$max");
    $n=1;
  }
  else {
    $start=$page*$max-$max;
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_links  WHERE linkcatID='$linkcatID' AND displayed = '1' ORDER BY date DESC LIMIT $start,$max");
    $n = ($gesamt+1)-$page*$max+$max;
  } 


  $ds = safe_query("SELECT linkcatID FROM `" . PREFIX . "plugins_links` WHERE linkcatID='$linkcatID' ORDER BY `date`");
    $anzcats = mysqli_num_rows($ds);
    if ($anzcats) {

   $n=1;

        while ($ds = mysqli_fetch_array($ergebnis)) {

            $question = $ds[ 'question' ];
            $answer = $ds[ 'answer' ];
            $date = getformatdatetime($ds[ 'date' ]);

            
            $poster = '<a href="index.php?site=profile&amp;id=' . $ds[ 'poster' ] . '">
            <strong>' . getnickname($ds[ 'poster' ]) . '</strong>
        </a>';

            #if (mb_strlen($answer) > $maxlinkschars) {
            #$answer = mb_substr($answer, 0, $maxlinkschars);
            #$answer .= '<b class="text-primary">[...]</b><div class="text-end"><a href="index.php?site=links&action=content&linkID=' . $ds[ 'linkID' ] . '" class="btn btn-primary">READMORE</a></div>';
            #} else {
            #    $answer .= '<div class="text-end"><a href="index.php?site=links&action=content&linkID=' . $ds[ 'linkID' ] . '" class="btn btn-primary">READMORE</a></div>';
            #}

            

            
            if ($ds[ 'banner' ]) {
                $banner = '<a href="' . $ds[ 'url' ] . '" target="_blank"><img class="card-img-top" src="' . $filepath . $ds[ 'banner' ] . '" alt="' . $ds[ 'question' ] . '" style="display: block;
    margin-left: auto;
    margin-right: auto" class="img-fluid"></a>';
            } else {
                $banner = '';
            }

            $link = '<a href="' . $ds[ 'url' ] . '" target="_blank">' . $ds[ 'url' ] . '</a>';
            
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($question);
            $question = $translate->getTextByLanguage($question);
            $translate->detectLanguages($answer);
            $answer = $translate->getTextByLanguage($answer);

            $answer = preg_replace("/<div>/", "", $answer);
            $answer = preg_replace("/<p>/", "", $answer);
            $answer = preg_replace("/<strong>/", "", $answer);
            $answer = preg_replace("/<em>/", "", $answer);
            $answer = preg_replace("/<s>/", "", $answer);
            $answer = preg_replace("/<u>/", "", $answer);
            $answer = preg_replace("/<blockquote>/", "", $answer);

            $answer = preg_replace("//", "", substr( $answer, 0, $maxlinkschars  ) ) . ' ... <div class="text-end"> <a href="index.php?site=links&action=content&linkID=' . $ds[ 'linkID' ] . '" class="btn btn-dark btn-sm">' . $plugin_language[ 'read_more' ] . ' <i class="bi bi-chevron-double-right"></i></a></div>';
            

            $data_array = array();
            $data_array['$question'] = $question;
            $data_array['$answer'] = $answer;
            $data_array['$poster'] = $poster;
            $data_array['$date'] = $date;
            $data_array['$banner'] = $banner;
            $data_array['$link'] = $link;
            

            #$data_array['$lang_rating'] = $plugin_language['rating'];
            #$data_array['$lang_votes'] = $plugin_language['votes'];

            $data_array['$lang_link'] = $plugin_language['link'];
            $data_array['$info'] = $plugin_language['info'];
            $data_array['$stand'] = $plugin_language['stand'];
            
            
            $template = $GLOBALS["_template"]->loadTemplate("links","details", $data_array, $plugin_path);
			echo $template;
        }
         $n++;   

        }
            $template = $GLOBALS["_template"]->loadTemplate("links","details_foot", $data_array, $plugin_path);
            echo $template;

    } else {
        echo $plugin_language[ 'no_links' ] . '<br>
        <a href="index.php?site=links"  type="button" class="btn btn-secondary">' . $plugin_language[ 'go_back' ] . '</a>';
    }


echo'<br>';
   if(@$pages>1) echo $page_link;


} elseif (isset($_GET[ 'linkID' ])) {

    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_links WHERE `linkID` = '" . $_GET[ 'linkID' ] . "'");
    $ds = mysqli_fetch_array($settings);
    $question = $ds['question'];

    $getcat = safe_query("SELECT * FROM " . PREFIX . "plugins_links_categories  WHERE linkcatID = '" . $ds[ 'linkcatID' ] . "'");
    $dx = mysqli_fetch_array($getcat);
    $linkcatname = $dx[ 'linkcatname' ];
    $linkcatID = $ds[ 'linkcatID' ];

    $data_array = array();
        $data_array['$linkcatname'] = $linkcatname;
        $data_array['$question'] = $question;
        $data_array['$linkcatID'] = $linkcatID;

        $data_array['$title_categories'] = $plugin_language['title_categories'];
        $data_array['$categories'] = $plugin_language['categories'];
        $data_array['$category'] = $plugin_language['category'];


        $template = $GLOBALS["_template"]->loadTemplate("links","content_details_head", $data_array, $plugin_path);
        echo $template;
        
        
 
    $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_links` WHERE `linkID` = '" . $_GET[ 'linkID' ] . "'");
    if (mysqli_num_rows($ergebnis)) {
        $ds = mysqli_fetch_array(
            safe_query(
                "SELECT * FROM `" . PREFIX . "plugins_links` WHERE `linkID` = '" . (int)$_GET[ 'linkID' ] . "'"
            )
        );
        safe_query(
            "UPDATE
                `" . PREFIX . "plugins_links`
            SET
                `views` = views+1
            WHERE
                `linkID` = '" . (int)$_GET[ 'linkID' ] . "'"
        );


        //rateform

        if ($loggedin) {
            $getgallery = safe_query(
                "SELECT `links` FROM `" . PREFIX . "user` WHERE `userID` = '" . (int)$userID . "'"
            );
            $found = false;
            if (mysqli_num_rows($getgallery)) {
                $ga = mysqli_fetch_array($getgallery);
                if ($ga[ 'links' ] != "") {
                    $string = $ga[ 'links' ];
                    $array = explode(":", $string);
                    $anzarray = count($array);
                    for ($i = 0; $i < $anzarray; $i++) {
                        if ($array[ $i ] == $ds[ 'linkID' ]) {
                            $found = true;
                        }
                    }
                }
            }

            $linkID = $ds[ 'linkID' ];

            if ($found) {
                $rateform = "<i>" . $plugin_language[ 'you_have_already_rated' ] . "</i>";
            } else {
                $rateform = '<form method="post" name="rating_picture' . $ds[ 'linkID' ] . '" action="index.php?site=links_rating" class="form-inline row g-3">
                            <div class="col-auto">
                                ' . $plugin_language[ 'rate_now' ] . '
                            </div>
                            <div class="col-auto">
                                <select name="rating" class="form-control">
                                <option>0 - ' . $plugin_language[ 'poor' ] . '</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                                <option>10 - ' . $plugin_language[ 'perfect' ] . '</option>
                                </select>
                                <input type="hidden" name="userID" value="' . $userID . '">
                                <input type="hidden" name="type" value="ar">
                                <input type="hidden" name="id" value="' . $ds[ 'linkID' ] . '">
                            </div>
                            <div class="col-auto">
                                <input type="submit" name="submit" value="' . $plugin_language[ 'rate' ] . '" class="btn btn-primary mb-3">
                            </div></form>';
            }
        } else {
            $rateform = '<i>' . $plugin_language[ 'rate_have_to_reg_login' ] . '</i>';
        }

        $votes = $ds[ 'votes' ];

        unset($ratingpic);
        $ratings = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        for ($i = 0; $i < $ds[ 'rating' ]; $i++) {
            $ratings[ $i ] = 1;
        }
        
        $ratingpic = '';
        foreach ($ratings as $pic) {
            $ratingpic .= '<img src="/includes/plugins/links/images/rating_' . $pic . '.png" width="21" height="21" alt="">';
        }

        while ($ds = mysqli_fetch_array($ergebnis)) {
        $views = $ds[ 'views' ];

            $question = $ds[ 'question' ];
            $answer = $ds[ 'answer' ];
            $date = getformatdatetime($ds[ 'date' ]);

            
            $poster = '<a href="index.php?site=profile&amp;id=' . $ds[ 'poster' ] . '">
            <strong>' . getnickname($ds[ 'poster' ]) . '</strong>
        </a>';

            if ($ds[ 'banner' ]) {
                $banner = '<a href="' . $ds[ 'url' ] . '" target="_blank"><img class="card-img-top" src="' . $filepath . $ds[ 'banner' ] . '" alt="' . $ds[ 'question' ] . '" style="display: block;
    margin-left: auto;
    margin-right: auto" class="img-fluid"></a>';
            } else {
                $banner = '';
            }

            $link = '<a href="' . $ds[ 'url' ] . '" target="_blank">' . $ds[ 'url' ] . '</a>';

            
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($question);
            $question = $translate->getTextByLanguage($question);
            $translate->detectLanguages($answer);
            $answer = $translate->getTextByLanguage($answer);
            

            $data_array = array();
            $data_array['$question'] = $question;
            $data_array['$answer'] = $answer;
            $data_array['$poster'] = $poster;
            $data_array['$date'] = $date;
            $data_array['$ratingpic'] = $ratingpic;
            $data_array['$votes'] = $votes;
            $data_array['$rateform'] = $rateform;
            $data_array['$views'] = $views;
            $data_array['$banner'] = $banner;
            $data_array['$link'] = $link;

            $data_array['$lang_rating'] = $plugin_language['rating'];
            $data_array['$lang_votes'] = $plugin_language['votes'];

            $data_array['$lang_links'] = $plugin_language['link'];
            $data_array['$links'] = $plugin_language['links'];
            $data_array['$info'] = $plugin_language['info'];
            $data_array['$stand'] = $plugin_language['stand'];
            $data_array['$lang_views'] = $plugin_language['views'];
            
            
            $template = $GLOBALS["_template"]->loadTemplate("links","content_details", $data_array, $plugin_path);
            echo $template;
         
        }

    }



} elseif ($action == "") {
    
    $cats = safe_query("SELECT * FROM " . PREFIX . "plugins_links_categories ORDER BY linkcatname");
    if (mysqli_num_rows($cats)) {
        
        $anzcats = mysqli_num_rows(safe_query("SELECT linkcatID FROM " . PREFIX . "plugins_links_categories"));

        $data_array = array();
        $data_array['$anzcats'] = $anzcats;

        $data_array['$title_categories']=$plugin_language['title_categories'];
        $data_array['$categories']=$plugin_language['categories'];
        $data_array['$category']=$plugin_language['category'];

        $data_array['$link_description'] = $plugin_language['link_description'];
        $data_array['$link_number'] = $plugin_language['link_number'];
        

        $template = $GLOBALS["_template"]->loadTemplate("links","category", $data_array, $plugin_path);
		echo $template;

        $template = $GLOBALS["_template"]->loadTemplate("links","content_head", $data_array, $plugin_path);
        echo $template;

        while ($ds = mysqli_fetch_array($cats)) {
            $anzlinks = mysqli_num_rows(
                safe_query(
                    "SELECT
                        linkID
                    FROM
                        " . PREFIX . "plugins_links
                    WHERE
                        linkcatID='" . $ds[ 'linkcatID' ] . "'"
                )
            );
            $linkcatname =
                '<a href="index.php?site=links&amp;action=show&amp;linkcatID=' . $ds[ 'linkcatID' ] . '"> ' .
                $ds[ 'linkcatname' ] . '</a>';
                
            $description = $ds[ 'description' ];

            $data_array = array();
            $data_array['$linkcatname'] = $linkcatname;
            $data_array['$description'] = $description;
            $data_array['$anzlinks'] = $anzlinks;
            $data_array['$links']=$plugin_language['links'];
            $template = $GLOBALS["_template"]->loadTemplate("links","content", $data_array, $plugin_path);
			echo $template;
            
        }

        $template = $GLOBALS["_template"]->loadTemplate("links","content_foot", $data_array, $plugin_path);
        echo $template;
                
    } else {
        
        echo $plugin_language[ 'no_categories' ];
    }
}
