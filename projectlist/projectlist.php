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
$plugin_language = $pm->plugin_language("projectlist", $plugin_path);

$filepath = $plugin_path."images/";


    $data_array = array();
    $data_array['$title']=$plugin_language['title'];
    $data_array['$subtitle']='Project List';

    $template = $GLOBALS["_template"]->loadTemplate("projectlist","head", $data_array, $plugin_path);
    echo $template;


if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if ($action == "show" && is_numeric($_GET[ 'projectlistcatID' ])) {

    if(isset($_GET['page'])) $page=(int)$_GET['page'];
  else $page = 1;
    
    $projectlistcatID = $_GET[ 'projectlistcatID' ];
    $getcat = safe_query("SELECT * FROM " . PREFIX . "plugins_projectlist_categories  WHERE projectlistcatID='$projectlistcatID'");
    $ds = mysqli_fetch_array($getcat);
    $projectlistcatname = $ds[ 'projectlistcatname' ];

    $articlecat = safe_query("SELECT * FROM " . PREFIX . "plugins_projectlist WHERE projectlistcatID='$projectlistcatID' ORDER BY `sort`");
    if (mysqli_num_rows($articlecat)) {
        $data_array = array();
        $data_array['$projectlistcatname'] = $projectlistcatname;

        $data_array['$title_categories']=$plugin_language['title_categories'];
        $data_array['$categories']=$plugin_language['categories'];
        $data_array['$category']=$plugin_language['category'];
        

        $template = $GLOBALS["_template"]->loadTemplate("projectlist","details_head", $data_array, $plugin_path);
        echo $template;
        
        $template = $GLOBALS["_template"]->loadTemplate("projectlist","details_header", $data_array, $plugin_path);
        echo $template;

        $alle=safe_query("SELECT projectlistID FROM ".PREFIX."plugins_projectlist WHERE projectlistcatID='$projectlistcatID' AND displayed = '1'");
        $gesamt = mysqli_num_rows($alle);
        $pages=1;

  
        $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_projectlist_settings");
        $dn = mysqli_fetch_array($settings);

    
        $max = $dn[ 'projectlist' ];
        if (empty($max)) {
        $max = 1;
        }

        $maxprojectlistchars = $dn[ 'projectlistchars' ];
        if (empty($maxprojectlistchars)) {
        $maxprojectlistchars = 200;
        } 
 

  for ($n=$max; $n<=$gesamt; $n+=$max) {
    if($gesamt>$n) $pages++;
  }


  if($pages>1) $page_link = makepagelink("index.php?site=projectlist&action=show&projectlistcatID=$projectlistcatID", $page, $pages);
    else $page_link='';

  if ($page == "1") {
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_projectlist  WHERE projectlistcatID='$projectlistcatID' AND displayed = '1' ORDER BY date DESC LIMIT 0,$max");
    $n=1;
  }
  else {
    $start=$page*$max-$max;
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_projectlist  WHERE projectlistcatID='$projectlistcatID' AND displayed = '1' ORDER BY date DESC LIMIT $start,$max");
    $n = ($gesamt+1)-$page*$max+$max;
  } 


  $ds = safe_query("SELECT projectlistcatID FROM `" . PREFIX . "plugins_projectlist` WHERE projectlistcatID='$projectlistcatID' ORDER BY `date`");
    $anzcats = mysqli_num_rows($ds);
    if ($anzcats) {

   $n=1;

        while ($ds = mysqli_fetch_array($ergebnis)) {

            $poster = '<a href="index.php?site=profile&amp;id=' . $ds[ 'poster' ] . '">
            <strong>' . getnickname($ds[ 'poster' ]) . '</strong>
        </a>';

            $projectlistID=$ds['projectlistID'];

            $todolist ='<b>'.$ds['question'].'</b>';
			#$date = date('d.m.Y', str_replace('-','/', $ds['date']));
            #$date =  getformatdate(strtotime($ds['date_time']));
            $date = date("d.m.Y", $ds[ 'date' ]);
			$prozent  =''.$ds['prozent'].'';
			$picwidth = $prozent;
			
			settype($picwidth, "integer");
			
			$chart_balken = '
			<td><table width="104" cellspacing="1" cellpadding="1"><tr><td background="images/icons/poll_bg.gif"><img src="images/icons/poll.gif" width="'.$picwidth.'"
			 height="5" /></td></tr></table></td>';
			
			$info    = $ds['answer'];
			$name   = $ds['question'];

			if($info == "") {
				$info = "<i>".$plugin_language['no_informations']."</i>";
			}		

            if($ds['banner']) {
				$pic='<img class="img-fluid" style="width: 320px" src="/includes/plugins/projectlist/images/'.$ds['banner'].'">';
			}
			else {
				$pic='<img class="img-fluid" style="width: 320px" src="/includes/plugins/projectlist/images/no_rubric_pic.jpg">';
			}

            $button = '<a class="btn btn-primary" href="index.php?site=projectlist&action=content&projectlistID='.$projectlistID.'" role="button">READMORE</a>';

            if ($ds[ 'status' ] == "alpha_test") {
                $status = $plugin_language[ 'alpha_test' ];
            } elseif ($ds[ 'status' ] == "work_complete") {
                $status = $plugin_language[ 'work_complete' ];
            } elseif ($ds[ 'status' ] == "beta_test") {
                $status = $plugin_language[ 'beta_test' ];
            } elseif ($ds[ 'status' ] == "currently_under_construction") {
                $status = $plugin_language[ 'currently_under_construction' ];    
            } else {
                $status = 'n/a';
            }


            $data_array = array();
			$data_array['$todolist'] = $todolist;
			$data_array['$status'] = $status;

			$data_array['$date'] = $date;
			$data_array['$chart_balken'] = $chart_balken;
			$data_array['$prozent'] = $prozent;
			$data_array['$info'] = $info;
					$data_array['$name'] = $name;
			$data_array['$pic'] = $pic;
			$data_array['$poster'] = $poster;
            $data_array['$button'] = $button;

			$data_array['$lang_poster'] = $plugin_language['poster'];
			$data_array['$project']=$plugin_language['project'];
			$data_array['$lang_status']=$plugin_language['status'];
			$data_array['$expected_completion']=$plugin_language['expected_completion'];
			$data_array['$completed']=$plugin_language['completed'];
			$data_array['$information']=$plugin_language['information'];
            
            $template = $GLOBALS["_template"]->loadTemplate("projectlist","details", $data_array, $plugin_path);
			echo $template;
        }
         $n++;   

        }
            $template = $GLOBALS["_template"]->loadTemplate("projectlist","details_foot", $data_array, $plugin_path);
            echo $template;

    } else {
        echo $plugin_language[ 'no_links' ] . '<br>
        <a href="index.php?site=projectlist"  type="button" class="btn btn-secondary">' . $plugin_language[ 'go_back' ] . '</a>';
    }


echo'<br>';
   if(@$pages>1) echo $page_link;


} elseif (isset($_GET[ 'projectlistID' ])) {

$projectlistID = (int)$_GET['projectlistID'];    

    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_projectlist WHERE `projectlistID` = '" . $_GET[ 'projectlistID' ] . "'");
    $ds = mysqli_fetch_array($settings);
    $question = $ds['question'];

    $getcat = safe_query("SELECT * FROM " . PREFIX . "plugins_projectlist_categories  WHERE projectlistcatID = '" . $ds[ 'projectlistcatID' ] . "'");
    $dx = mysqli_fetch_array($getcat);
    $projectlistcatname = $dx[ 'projectlistcatname' ];
    $projectlistcatID = $ds[ 'projectlistcatID' ];

    $data_array = array();
        $data_array['$projectlistcatname'] = $projectlistcatname;
        $data_array['$question'] = $question;
        $data_array['$projectlistcatID'] = $projectlistcatID;

        $data_array['$title_categories'] = $plugin_language['title_categories'];
        $data_array['$categories'] = $plugin_language['categories'];
        $data_array['$category'] = $plugin_language['category'];


        $template = $GLOBALS["_template"]->loadTemplate("projectlist","content_details_head", $data_array, $plugin_path);
        echo $template;
        
        
 
 



    $query = safe_query("SELECT * FROM ".PREFIX."plugins_projectlist WHERE projectlistID='".$projectlistID."'");
    
        
    if(mysqli_num_rows($query)) {
        $dx = mysqli_fetch_array($query);
        
        safe_query("UPDATE ".PREFIX."plugins_projectlist SET views=views+1 WHERE projectlistID='".$projectlistID."'");


    //rateform

        if ($loggedin) {
            $getgallery = safe_query(
                "SELECT `projectlist` FROM `" . PREFIX . "user` WHERE `userID` = '" . (int)$userID . "'"
            );
            $found = false;
            if (mysqli_num_rows($getgallery)) {
                $ga = mysqli_fetch_array($getgallery);
                if ($ga[ 'projectlist' ] != "") {
                    $string = $ga[ 'projectlist' ];
                    $array = explode(":", $string);
                    $anzarray = count($array);
                    for ($i = 0; $i < $anzarray; $i++) {
                        if ($array[ $i ] == $dx[ 'projectlistID' ]) {
                            $found = true;
                        }
                    }
                }
            }

            $projectlistID = $ds[ 'projectlistID' ];

            if ($found) {
                $rateform = "<i>" . $plugin_language[ 'you_have_already_rated' ] . "</i>";
            } else {
                $rateform = '<form method="post" name="rating_picture' . $dx[ 'projectlistID' ] .
                    '" action="index.php?site=projectlist_rating" class="form-inline">' . $plugin_language[ 'rate_now' ] . '&nbsp;
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
                                <input type="hidden" name="id" value="' . $dx[ 'projectlistID' ] . '">&nbsp;
                                <input type="submit" name="submit" value="' . $plugin_language[ 'rate' ] .
                    '" class="btn btn-primary"></form>';
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
            $ratingpic .= '<img src="/includes/plugins/projectlist/images/rating_' . $pic . '.png" width="21" height="21" alt="">';
        }
        
        
        
        $views = $dx[ 'views' ];

            $question = $ds[ 'question' ];
            $answer = $ds[ 'answer' ];
            #$date2 = getformatdate($ds['date']);
            $date = date("d.m.Y", $ds[ 'date' ]);
            $date2 =  getformatdate(strtotime($ds['date_time']));

            
            $poster = '<a href="index.php?site=profile&amp;id=' . $ds[ 'poster' ] . '">
            <strong>' . getnickname($ds[ 'poster' ]) . '</strong>
        </a>';

            if ($ds[ 'banner' ]) {
                $banner = '<img src="' . $filepath . $ds[ 'banner' ] . '" alt="' . $ds[ 'question' ] . '"  width="1247" height="300" style="height: 300px;
                            width: 100% !important;
                            object-fit: cover;
                            object-position: top center;">';

                $pic = '<img src="' . $filepath . $ds[ 'banner' ] . '" alt="' . $ds[ 'question' ] . '" style="display: block;
                        margin-left: auto;
                        margin-right: auto"" class="img-fluid"">';
            } else {
                $banner = '';
                $pic = '';
            }

            if ($dx[ 'status' ] == "alpha_test") {
                $status = $plugin_language[ 'alpha_test' ];
            } elseif ($dx[ 'status' ] == "work_complete") {
                $status = $plugin_language[ 'work_complete' ];
            } elseif ($dx[ 'status' ] == "beta_test") {
                $status = $plugin_language[ 'beta_test' ];
            } elseif ($dx[ 'status' ] == "currently_under_construction") {
                $status = $plugin_language[ 'currently_under_construction' ];    
            } else {
                $status = 'n/a';
            }

            $prozent  =''.$dx['prozent'].'';
            $picwidth = $prozent;
            
            settype($picwidth, "integer");
            
            $chart_balken = '
            <td><table width="104" cellspacing="1" cellpadding="1"><tr><td background="images/icons/poll_bg.gif"><img src="images/icons/poll.gif" width="'.$picwidth.'"
             height="5" /></td></tr></table></td>';

            #$link = '<a href="' . $ds[ 'url' ] . '" target="_blank">' . $ds[ 'url' ] . '</a>';

            
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
            $data_array['$date2'] = $date2;
            $data_array['$ratingpic'] = $ratingpic;
            $data_array['$votes'] = $votes;
            $data_array['$rateform'] = $rateform;
            $data_array['$views'] = $views;
            $data_array['$banner'] = $banner;
            $data_array['$pic'] = $pic;
            $data_array['$status'] = $status;
            $data_array['$prozent'] = $prozent;
            $data_array['$chart_balken'] = $chart_balken;
            #$data_array['$link'] = $link;

            $data_array['$lang_poster'] = $plugin_language['poster'];
            $data_array['$lang_rating'] = $plugin_language['rating'];
            $data_array['$lang_votes'] = $plugin_language['votes'];

            $data_array['$lang_links'] = $plugin_language['link'];
            $data_array['$links'] = $plugin_language['links'];
            $data_array['$info'] = $plugin_language['info'];
            $data_array['$stand'] = $plugin_language['stand'];
            $data_array['$lang_views'] = $plugin_language['views'];

            #$data_array['$lang_poster'] = $plugin_language['poster'];
            $data_array['$project']=$plugin_language['project'];
            $data_array['$lang_status']=$plugin_language['status'];
            $data_array['$expected_completion']=$plugin_language['expected_completion'];
            $data_array['$completed']=$plugin_language['completed'];
            $data_array['$information']=$plugin_language['information'];
            
            
            $template = $GLOBALS["_template"]->loadTemplate("projectlist","content_details", $data_array, $plugin_path);
            echo $template;
         
        }

    #}

#}

} elseif ($action == "") {
    
    $cats = safe_query("SELECT * FROM " . PREFIX . "plugins_projectlist_categories ORDER BY projectlistcatname");
    if (mysqli_num_rows($cats)) {
    	
        
        $anzcats = mysqli_num_rows(safe_query("SELECT projectlistcatID FROM " . PREFIX . "plugins_projectlist_categories"));

        $data_array = array();
        $data_array['$anzcats'] = $anzcats;

        $data_array['$title_categories']=$plugin_language['title_categories'];
        $data_array['$categories']=$plugin_language['categories'];
        $data_array['$category']=$plugin_language['category'];

        $data_array['$link_description'] = $plugin_language['link_description'];
        $data_array['$link_number'] = $plugin_language['link_number'];
        

        $template = $GLOBALS["_template"]->loadTemplate("projectlist","category", $data_array, $plugin_path);
		echo $template;

        $template = $GLOBALS["_template"]->loadTemplate("projectlist","content_head", $data_array, $plugin_path);
        echo $template;

        while($ds=mysqli_fetch_array($cats)) {
		    if($ds['banner']) {
				$image='<img class="img-fluid" style="width: 120px" src="/includes/plugins/projectlist/images/rubriken/'.$ds['banner'].'">';
			}
			else {
				$image='<img class="img-fluid" style="width: 120px" src="/includes/plugins/projectlist/images/no_rubric_pic.jpg">';
			}
            $anzlinks = mysqli_num_rows(
                safe_query(
                    "SELECT
                        projectlistID
                    FROM
                        " . PREFIX . "plugins_projectlist
                    WHERE
                        projectlistcatID='" . $ds[ 'projectlistcatID' ] . "' AND displayed = '1'"
                )
            );
            $projectlistcatname =
                '<a href="index.php?site=projectlist&amp;action=show&amp;projectlistcatID=' . $ds[ 'projectlistcatID' ] . '"><strong style="font-size: 16px">' .
                $ds[ 'projectlistcatname' ] . '</strong></a>';
                
            $description = $ds[ 'description' ];

            

            $data_array = array();
            $data_array['$image'] = $image;
            $data_array['$projectlistcatname'] = $projectlistcatname;
            $data_array['$description'] = $description;
            $data_array['$anzlinks'] = $anzlinks;
            $data_array['$links']=$plugin_language['links'];
            $template = $GLOBALS["_template"]->loadTemplate("projectlist","content", $data_array, $plugin_path);
			echo $template;
            
        }

        $template = $GLOBALS["_template"]->loadTemplate("projectlist","content_foot", $data_array, $plugin_path);
        echo $template;
                
    } else {
        
        echo $plugin_language[ 'no_categories' ];
    }
}
