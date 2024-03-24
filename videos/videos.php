<script>
function goBack() {
    window.history.back();
}
</script>
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
    $plugin_language = $pm->plugin_language("videos", $plugin_path);

// -- NEWS INFORMATION -- //
include_once("videos_functions.php");



$filepath = $plugin_path."images/";

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if (isset($videosID)) {
    unset($videosID);
    }
    if (isset($_GET[ 'videosID' ])) {
    $videosID = $_GET[ 'videosID' ];
    }


        $data_array = array();
        $data_array['$title']=$plugin_language['title'];
        $data_array['$subtitle']='Videos';        
        $template = $GLOBALS["_template"]->loadTemplate("videos","title_head", $data_array, $plugin_path);
        echo $template;

if($action=="watch") {
    $videosID = (int)$_GET['videosID'];
    $query = safe_query("SELECT * FROM ".PREFIX."plugins_videos WHERE videosID='".$videosID."'");
    
        
    if(mysqli_num_rows($query)) {
        $ds = mysqli_fetch_array($query);
        
        safe_query("UPDATE ".PREFIX."plugins_videos SET views=views+1 WHERE videosID='".$videosID."'");


    //rateform

        if ($loggedin) {
            $getgallery = safe_query(
                "SELECT `videos` FROM `" . PREFIX . "user` WHERE `userID` = '" . (int)$userID . "'"
            );
            $found = false;
            if (mysqli_num_rows($getgallery)) {
                $ga = mysqli_fetch_array($getgallery);
                if ($ga[ 'videos' ] != "") {
                    $string = $ga[ 'videos' ];
                    $array = explode(":", $string);
                    $anzarray = count($array);
                    for ($i = 0; $i < $anzarray; $i++) {
                        if ($array[ $i ] == $ds[ 'videosID' ]) {
                            $found = true;
                        }
                    }
                }
            }

            $videosID = $ds[ 'videosID' ];

            if ($found) {
                $rateform = "<i>" . $plugin_language[ 'you_have_already_rated' ] . "</i>";
            } else {
                $rateform = '<form method="post" name="rating_picture' . $ds[ 'videosID' ] . '" action="index.php?site=videos_rating"  class="form-inline row g-3">
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
                                <input type="hidden" name="id" value="' . $ds[ 'videosID' ] . '">
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
            $ratingpic .= '<img src="/includes/plugins/videos/images/rating_' . $pic . '.png" width="21" height="21" alt="">';
        }
        
        $videoID = $ds['youtube'];
        $uploader = getnickname($ds['uploader']);
        $views = $ds['views'];
        $videoname = $ds['videoname'];
        $date = date("d.m.Y", $ds['date']);
        $views = $ds['views'];
        $description = $ds['description'];

            
        

        #$stream = '<div class="ratio ratio-16x9">
  #<iframe src="https://www.youtube.com/embed/'.$videoID.'?rel=0" title="YouTube video" allowfullscreen></iframe>
#</div>';

        $stream = '<div data-service="youtube" data-id="'.$videoID.'" data-autoscale data-youtube></div>';
        
                
        $data_array = array();
        $data_array['$videoID'] = $videoID;
        $data_array['$videoname'] = $videoname;
        $data_array['$date'] = $date;
        $data_array['$views'] = $views;
        $data_array['$uploader'] = $uploader;
        $data_array['$views'] = $views;
        $data_array['$description'] = $description;
        $data_array['$stream'] = $stream;
        $data_array['$ratingpic'] = $ratingpic;
        #$data_array['$rating'] = $rating;
         $data_array['$rateform'] = $rateform;
         $data_array['$votes'] = $votes;
        
        
        
        $data_array['$on'] = $plugin_language[ 'on' ];
        $data_array['$added_by'] = $plugin_language[ 'added_by' ];
        $data_array['$view'] = $plugin_language[ 'view' ];
        $data_array['$back'] = $plugin_language[ 'back' ];
        $data_array['$rating']=$plugin_language['rating'];
        $data_array['$lang_votes'] = $plugin_language['votes'];
       
        
        $template = $GLOBALS["_template"]->loadTemplate("videos","watch", $data_array, $plugin_path);
        echo $template;
        
        $comments_allowed = $ds[ 'comments' ];
        if ($ds[ 'videosID' ]) {
            $parentID = $videosID;
            $type = "vi";
        }

        $referer = "index.php?site=videos&action=watch&videosID=$videosID";

        include("videos_comments.php");
    


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
                `" . PREFIX . "plugins_videos_comments`
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
            $dt = safe_query("SELECT * FROM " . PREFIX . "plugins_videos_comments WHERE commentID='" . (int)$id."'");
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

}else {  
    
    $cats = safe_query("SELECT * FROM " . PREFIX . "plugins_videos_categories ORDER BY catname");
    if (mysqli_num_rows($cats)) {
        
        $anzcats = mysqli_num_rows(safe_query("SELECT videoscatID FROM " . PREFIX . "plugins_videos_categories"));

        $data_array = array();
        $data_array['$anzcats'] = $anzcats;
		$data_array['$lang_all'] = $plugin_language['all'];

        $template = $GLOBALS["_template"]->loadTemplate("videos","category", $data_array, $plugin_path);
        echo $template;

        while ($ds = mysqli_fetch_array($cats)) {
            $anzportfolio = mysqli_num_rows(
                safe_query(
                    "SELECT
                        videosID
                    FROM
                        " . PREFIX . "plugins_videos
                    WHERE
                        videoscatID='" . $ds[ 'videoscatID' ] . "'"
                )
            );

            $catname = $ds['catname'];
            $videoscatID = $ds['videoscatID'];


            $portfoliocatname =
                '<button class="btn btn-primary filter-button" role="button" data-filter="'.$ds['videoscatID'].'" >' .
                $catname . '<small> (videos: ' . $anzportfolio .')</small></button>';


            $data_array = array();
            $data_array['$portfoliocatname'] = $portfoliocatname;
            $data_array['$anzportfolio'] = $anzportfolio;
            $data_array['$videoscatID'] = $videoscatID;
            
            $template = $GLOBALS["_template"]->loadTemplate("videos","content", $data_array, $plugin_path);
            echo $template;

            

        }

        $template = $GLOBALS["_template"]->loadTemplate("videos","foot", $data_array, $plugin_path);
        echo $template;

        

        $template = $GLOBALS["_template"]->loadTemplate("videos","cat_all", $data_array, $plugin_path);
        echo $template;

        $template = $GLOBALS["_template"]->loadTemplate("videos","cat_head", $data_array, $plugin_path);
            echo $template;
        
        $n=1;

       $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_videos ORDER BY videosID");

while($ds=mysqli_fetch_array($ergebnis)) {

   $date = getformatdate($ds[ 'date' ]);
        $time = getformattime($ds[ 'date' ]);

$query = safe_query(
            "SELECT
                videosID
            FROM
                " . PREFIX . "plugins_videos 
            "
        );

            $comments = '';

        if ($ds[ 'comments' ]) {
            if ($ds[ 'videosID' ]) {
                $anzcomments = getanzvideoscomments($ds[ 'videosID' ], 'vi');
                $replace = array('$anzcomments', '$url', '$lastposter', '$lastdate');
                @$vars = array(
                    $anzcomments,
                    'index.php?site=videos&action=watch&videosID=' . $ds[ 'videosID' ],
                    html_entity_decode(getlastvideoscommentposter($ds[ 'videosID' ], 'vi')),
                    getformatdatetime(getlastvideoscommentdate($ds[ 'videosID' ], 'vi'))
                );

                switch ($anzcomments) {
                    case 0:
                        $comments = str_replace($replace, $vars, $plugin_language[ 'no_comment' ]);
                        break;
                    case 1:
                        $comments = str_replace($replace, $vars, $plugin_language[ 'comment' ]);
                        break;
                    default:
                        $comments = str_replace($replace, $vars, $plugin_language[ 'comments' ]);
                        break;
                }
            }
        } else {
            $comments = $plugin_language[ 'off_comments' ];
        }
$videoID = $ds['youtube'];

$preview = 'http://img.youtube.com/vi/'.$videoID.'/hqdefault.jpg';




            $data_array = array();
            $data_array['$videoname'] = $ds['videoname'];
            $data_array['$comments'] = $comments;
            $data_array['$videosID'] = $ds['videosID'];
            $data_array['$videoscatID'] = $ds['videoscatID'];
            $data_array['$preview'] = $preview;
            $data_array['$date'] = $date;


            $cat = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_videos_categories WHERE videoscatID = '". $ds['videoscatID']."' ORDER BY catname"));
            $data_array['$catname'] = $cat['catname']; 

            $template = $GLOBALS["_template"]->loadTemplate("videos","cat", $data_array, $plugin_path);
            echo $template;
           


             $n++;

        }

            $template = $GLOBALS["_template"]->loadTemplate("videos","cat_foot", $data_array, $plugin_path);
            echo $template;


    } else {
        
        echo $plugin_language[ 'no_categories' ];
    }
}
