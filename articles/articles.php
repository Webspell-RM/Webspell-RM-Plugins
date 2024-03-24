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
$plugin_language = $pm->plugin_language("articles", $plugin_path);


    $data_array = array();
    $data_array['$title']=$plugin_language['title'];
    $data_array['$subtitle']='Articles';
    $template = $GLOBALS["_template"]->loadTemplate("articles","head", $data_array, $plugin_path);
    echo $template;


if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if ($action == "show" && is_numeric($_GET[ 'articlecatID' ])) {

    if(isset($_GET['page'])) $page=(int)$_GET['page'];
  else $page = 1;
    
    $articlecatID = $_GET[ 'articlecatID' ];
    $getcat = safe_query("SELECT * FROM " . PREFIX . "plugins_articles_categories  WHERE articlecatID='$articlecatID'");
    $ds = mysqli_fetch_array($getcat);
    $articlecatname = $ds[ 'articlecatname' ];

    $articlecat = safe_query("SELECT * FROM " . PREFIX . "plugins_articles WHERE articlecatID='$articlecatID' ORDER BY `sort`");
    if (mysqli_num_rows($articlecat)) {
        $data_array = array();
        $data_array['$articlecatname'] = $articlecatname;

        $data_array['$title_categories']=$plugin_language['title_categories'];
        $data_array['$categories']=$plugin_language['categories'];
        $data_array['$category']=$plugin_language['category'];
        

        $template = $GLOBALS["_template"]->loadTemplate("articles","details_head", $data_array, $plugin_path);
        echo $template;
        
        $template = $GLOBALS["_template"]->loadTemplate("articles","details_header", $data_array, $plugin_path);
        echo $template;

        $alle=safe_query("SELECT articleID FROM ".PREFIX."plugins_articles WHERE articlecatID='$articlecatID' AND displayed = '1'");
        $gesamt = mysqli_num_rows($alle);
        $pages=1;

  
        $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_articles_settings");
        $dn = mysqli_fetch_array($settings);

    
        $max = $dn[ 'articles' ];
        if (empty($max)) {
        $max = 1;
        }
         

  for ($n=$max; $n<=$gesamt; $n+=$max) {
    if($gesamt>$n) $pages++;
  }


  if($pages>1) $page_link = makepagelink("index.php?site=articles&action=show&articlecatID=$articlecatID", $page, $pages);
    else $page_link='';

  if ($page == "1") {
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_articles  WHERE articlecatID='$articlecatID' AND displayed = '1' ORDER BY date DESC LIMIT 0,$max");
    $n=1;
  }
  else {
    $start=$page*$max-$max;
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_articles  WHERE articlecatID='$articlecatID' AND displayed = '1' ORDER BY date DESC LIMIT $start,$max");
    $n = ($gesamt+1)-$page*$max+$max;
  } 


  $ds = safe_query("SELECT articlecatID FROM `" . PREFIX . "plugins_articles` WHERE articlecatID='$articlecatID' ORDER BY `date`");
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

            #if (mb_strlen($answer) > $maxarticleschars) {
            #$answer = mb_substr($answer, 0, $maxarticleschars);
            #$answer .= '<b class="text-primary">[...]</b><br><div class="text-right"> <a href="index.php?site=articles&action=content&articleID=' . $ds[ 'articleID' ] . '" class="btn btn-primary text-right">READMORE</a></div>';
            #}

            $maxarticleschars = $dn[ 'articleschars' ];
            if (empty($maxarticleschars)) {
            $maxarticleschars = 200;
            }


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

            $answer = preg_replace("//", "", substr( $answer, 0, $maxarticleschars  ) ) . ' ... <div class="text-end"> <a href="index.php?site=articles&action=watch&articleID=' . $ds[ 'articleID' ] . '" class="btn btn-dark btn-sm">' . $plugin_language[ 'read_more' ] . ' <i class="bi bi-chevron-double-right"></i></a></div>';
                

            $data_array = array();
            $data_array['$question'] = $question;
            $data_array['$answer'] = $answer;
            $data_array['$poster'] = $poster;
            $data_array['$date'] = $date;
            

            $data_array['$lang_rating'] = $plugin_language['rating'];
            $data_array['$lang_votes'] = $plugin_language['votes'];

            $data_array['$link'] = $plugin_language['link'];
            $data_array['$info'] = $plugin_language['info'];
            $data_array['$stand'] = $plugin_language['stand'];
            
            
            $template = $GLOBALS["_template"]->loadTemplate("articles","details", $data_array, $plugin_path);
			echo $template;
        }
         $n++;   

        }
            

    } else {
        echo $plugin_language[ 'no_articles' ] . '<br><br>[ <a href="index.php?site=articles" class="alert-article">' .
            $plugin_language[ 'go_back' ] . '</a> ]';
    }


echo'<br>';
   if(@$pages>1) echo $page_link;


#} elseif (isset($_GET[ 'articleID' ])) {

} elseif($action=="watch" && is_numeric($_GET[ 'articleID' ])) {

    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_articles WHERE `articleID` = '" . $_GET[ 'articleID' ] . "'");
    $ds = mysqli_fetch_array($settings);
    $question = $ds['question'];

    $getcat = safe_query("SELECT * FROM " . PREFIX . "plugins_articles_categories  WHERE articlecatID = '" . $ds[ 'articlecatID' ] . "'");
    $dx = mysqli_fetch_array($getcat);
        $articlecatname = $dx[ 'articlecatname' ];
        $articlecatID = $ds[ 'articlecatID' ];

        $data_array = array();
        $data_array['$articlecatname'] = $articlecatname;
        $data_array['$question'] = $question;
        $data_array['$articlecatID'] = $articlecatID;

        $data_array['$title_categories'] = $plugin_language['title_categories'];
        $data_array['$categories'] = $plugin_language['categories'];
        $data_array['$category'] = $plugin_language['category'];


        $template = $GLOBALS["_template"]->loadTemplate("articles","content_details_head", $data_array, $plugin_path);
        echo $template;
        
        
 
    $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_articles` WHERE `articleID` = '" . $_GET[ 'articleID' ] . "'");
    if (mysqli_num_rows($ergebnis)) {
        $ds = mysqli_fetch_array(
            safe_query(
                "SELECT * FROM `" . PREFIX . "plugins_articles` WHERE `articleID` = '" . (int)$_GET[ 'articleID' ] . "'"
            )
        );
        safe_query(
            "UPDATE
                `" . PREFIX . "plugins_articles`
            SET
                `views` = views+1
            WHERE
                `articleID` = '" . (int)$_GET[ 'articleID' ] . "'"
        );


        //rateform

        if ($loggedin) {
            $getgallery = safe_query(
                "SELECT `articles` FROM `" . PREFIX . "user` WHERE `userID` = '" . (int)$userID . "'"
            );
            $found = false;
            if (mysqli_num_rows($getgallery)) {
                $ga = mysqli_fetch_array($getgallery);
                if ($ga[ 'articles' ] != "") {
                    $string = $ga[ 'articles' ];
                    $array = explode(":", $string);
                    $anzarray = count($array);
                    for ($i = 0; $i < $anzarray; $i++) {
                        if ($array[ $i ] == $ds[ 'articleID' ]) {
                            $found = true;
                        }
                    }
                }
            }

            $articleID = $ds[ 'articleID' ];

            if ($found) {
                $rateform = "<i>" . $plugin_language[ 'you_have_already_rated' ] . "</i>";
            } else {
                $rateform = '<form method="post" name="rating_picture' . $ds[ 'articleID' ] . '" action="index.php?site=articles_rating" class="form-inline row g-3">
                            <div class="col-auto">
                                ' . $plugin_language[ 'rate_now' ] . '
                            </div>
                            <div class="col-auto">
                                <select name="rating" class="form-select">
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
                                <input type="hidden" name="id" value="' . $ds[ 'articleID' ] . '">
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
            $ratingpic .= '<img src="/includes/plugins/articles/images/rating_' . $pic . '.png" width="21" height="21" alt="">';
        }

        while ($ds = mysqli_fetch_array($ergebnis)) {
        $views = $ds[ 'views' ];

            $question = $ds[ 'question' ];
            $answer = $ds[ 'answer' ];
            $date = getformatdatetime($ds[ 'date' ]);
            
            $poster = '<a href="index.php?site=profile&amp;id=' . $ds[ 'poster' ] . '"><strong>' . getnickname($ds[ 'poster' ]) . '</strong></a>';
            
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

            $data_array['$lang_rating'] = $plugin_language['rating'];
            $data_array['$lang_votes'] = $plugin_language['votes'];

            $data_array['$link'] = $plugin_language['link'];
            $data_array['$info'] = $plugin_language['info'];
            $data_array['$stand'] = $plugin_language['stand'];
            $data_array['$lang_views'] = $plugin_language['views'];
            
            
            $template = $GLOBALS["_template"]->loadTemplate("articles","content_details", $data_array, $plugin_path);
            echo $template;

            $comments_allowed = $ds[ 'comments' ];
        if ($ds[ 'articleID' ]) {
            $parentID = $ds[ 'articleID' ];
            $type = "ar";
        }

        $referer = "index.php?site=articles&action=watch&articleID=".$ds[ 'articleID' ]."";

        include("articles_comments.php");
         
        }

        $template = $GLOBALS["_template"]->loadTemplate("articles","content_details_foot", $data_array, $plugin_path);
        echo $template;

    }
} elseif (isset($_POST[ 'saveeditcomment' ])) {
    
    if (!isfeedbackadmin($userID) && !isvideocommentposter($userID, $_POST[ 'commentID' ])) {
        die('No access');
    }
 
    $message = $_POST[ 'message' ];
    $author = $_POST[ 'authorID' ];
    $referer = urldecode($_POST[ 'referer' ]);
 
    // check if any admin edited the post
    if (safe_query(
        "UPDATE
                `" . PREFIX . "plugins_articles_comments`
            SET
                comments='" . $message . "'
            WHERE
                commentID='" . (int)$_POST[ 'commentID' ] . "'"
    )
    ) {
        header("Location: " . $referer);
    }
} elseif ($action == "editcomment") {  

# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("comments", $plugin_path);

    $id = $_GET[ 'id' ];
    $referer = $_GET[ 'ref' ];
    
    if (isfeedbackadmin($userID) || isvideocommentposter($userID, $id)) {
        if (!empty($id)) {
            $dt = safe_query("SELECT * FROM " . PREFIX . "plugins_articles_comments WHERE commentID='" . (int)$id."'");
            if (mysqli_num_rows($dt)) {
                $ds = mysqli_fetch_array($dt);
                $poster = '<a href="index.php?site=profile&amp;id=' . $ds[ 'userID' ] . '"><b>' .
                    getnickname($ds[ 'userID' ]) . '</b></a>';
                $message = getinput($ds[ 'comments' ]);
                $message = preg_replace("#\n\[br\]\[br\]\[hr]\*\*(.+)#si", '', $message);
                $message = preg_replace("#\n\[br\]\[br\]\*\*(.+)#si", '', $message);
 
                $data_array = array();
                $data_array['$message'] = $message;
                $data_array['$authorID'] = $ds['userID'];
                $data_array['$id'] = $id;
                $data_array['$userID'] = $userID;
                $data_array['$referer'] = $referer;
               
                $data_array['$title_editcomment']=$plugin_language['title_editcomment'];
                $data_array['$edit_comment']=$plugin_language['edit_comment'];    
                
                $template = $GLOBALS["_template"]->loadTemplate("comments","edit", $data_array, $plugin_path);
                echo $template;
            } else {
                redirect($referer, $plugin_language[ 'no_database_entry' ], 2);
            }
        } else {
            redirect($referer, $plugin_language[ 'no_commentid' ], 2);
        }
    } else {
        redirect($referer, $plugin_language[ 'access_denied' ], 2);
    }

} elseif ($action == "") {
    
    $cats = safe_query("SELECT * FROM " . PREFIX . "plugins_articles_categories ORDER BY articlecatname");
    if (mysqli_num_rows($cats)) {
        
        $anzcats = mysqli_num_rows(safe_query("SELECT articlecatID FROM " . PREFIX . "plugins_articles_categories"));

        $data_array = array();
        $data_array['$anzcats'] = $anzcats;

        $data_array['$title_categories']=$plugin_language['title_categories'];
        $data_array['$categories']=$plugin_language['categories'];
        $data_array['$category']=$plugin_language['category'];

        $data_array['$article_description'] = $plugin_language['article_description'];
        $data_array['$articles_number'] = $plugin_language['articles_number'];
        

        $template = $GLOBALS["_template"]->loadTemplate("articles","category", $data_array, $plugin_path);
		echo $template;

        $template = $GLOBALS["_template"]->loadTemplate("articles","content_head", $data_array, $plugin_path);
        echo $template;

        while ($ds = mysqli_fetch_array($cats)) {
            $anzarticles = mysqli_num_rows(
                safe_query(
                    "SELECT
                        articleID
                    FROM
                        " . PREFIX . "plugins_articles
                    WHERE
                        articlecatID='" . $ds[ 'articlecatID' ] . "'"
                )
            );
            $articlecatname =
                '<a href="index.php?site=articles&amp;action=show&amp;articlecatID=' . $ds[ 'articlecatID' ] . '"><strong style="font-size: 16px">' .
                $ds[ 'articlecatname' ] . '</strong></a>';
                
            $description = $ds[ 'description' ];

            $data_array = array();
            $data_array['$articlecatname'] = $articlecatname;
            $data_array['$description'] = $description;
            $data_array['$anzarticles'] = $anzarticles;
            $data_array['$articles']=$plugin_language['articles'];
            $template = $GLOBALS["_template"]->loadTemplate("articles","content", $data_array, $plugin_path);
			echo $template;
            
        }

        $template = $GLOBALS["_template"]->loadTemplate("articles","content_foot", $data_array, $plugin_path);
        echo $template;
                
    } else {
        
        echo $plugin_language['no_categories'];
    }
}
