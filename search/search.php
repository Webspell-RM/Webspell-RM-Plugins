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
\¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*/


try {
    $get = mysqli_fetch_assoc(safe_query("SELECT * FROM `".PREFIX."settings_recaptcha`"));
    $webkey = $get['webkey'];
    $seckey = $get['seckey'];
    if ($get['activated']=="1") { $recaptcha=1; } else { $recaptcha=0; }
} Catch (EXCEPTION $e) {
    $recaptcha=0;
}


# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("search", $plugin_path);

if (isset($_POST["action"])) {
    $action = $_POST["action"];
} else {
    $action = '';
}

if(isset($_REQUEST['action'])) $action = $_REQUEST['action'];
else $action='';


if ($action == "search") {
    $_language->readModule('search');

    $run = 0;
    if ($userID) {
        $run = 1;
    } else {

    if($recaptcha!=1) {
            $CAPCLASS = new \webspell\Captcha;
            if (!$CAPCLASS->checkCaptcha($_POST['captcha'], $_POST['captcha_hash'])) {
                $fehler[] = "Securitycode Error";
                $runregister = "false";
            } else {
                $run = 1;
                $runregister = "true";
            }
        } else {
            $runregister = "false";
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $recaptcha=$_POST['g-recaptcha-response'];
                if(!empty($recaptcha)) {
                    include("system/curl_recaptcha.php");
                    $google_url="https://www.google.com/recaptcha/api/siteverify";
                    $secret=$seckey;
                    $ip=$_SERVER['REMOTE_ADDR'];
                    $url=$google_url."?secret=".$secret."&response=".$recaptcha."&remoteip=".$ip;
                    $res=getCurlData($url);
                    $res= json_decode($res, true);
                    //reCaptcha success check 
                    if($res['success'])     {
                    $runregister="true"; $run=1;
                    }       else        {
                        $fehler[] = "reCAPTCHA Error";
                        $runregister="false";
                    }
                } else {
                    $fehler[] = "reCAPTCHA Error";
                    $runregister="false";
                }
            }
        }
    }

    if ($run) {

        $data_array = array();
        $data_array['$title'] = $plugin_language[ 'search' ];
        $data_array['$subtitle']='Search';
        $template = $GLOBALS["_template"]->loadTemplate("search","head", $data_array, $plugin_path);
        echo $template;
        
	
        $text = str_replace(array('%', '*'), array('\%', '%'), $_REQUEST[ 'text' ]);
        if (!isset($_REQUEST[ 'r' ]) || $_REQUEST[ 'r' ] < 1 || $_REQUEST[ 'r' ] > 100) {
            $results = 50;
        } else {
            $results = (int)$_REQUEST[ 'r' ];
        }
        isset($_REQUEST[ 'page' ]) ? $page = (int)$_REQUEST[ 'page' ] : $page = 1;
        isset($_REQUEST[ 'afterdate' ]) ? $afterdate = $_REQUEST[ 'afterdate' ] : $afterdate = 0;
        isset($_REQUEST[ 'beforedate' ]) ? $beforedate = $_REQUEST[ 'beforedate' ] : $beforedate = 0;
        $keywords = preg_split("/ ,/si", strtolower(str_replace(array('\%', '%'), '', $text)));

        if (mb_strlen(str_replace('%', '', $text))) {
            if (!$afterdate) {
                $after = 0;
            } else {
                $after = strtotime($afterdate);
            }
            if (!$beforedate) {
                $before = time();
            } else {
                $before = strtotime($beforedate);
            }

            $i = 0;
            $res_message = array();
            $res_title = array();
            $res_link = array();
            $res_type = array();
            $res_date = array();
            $res_occurr = array();

if (isset($_GET[ 'staticID' ])) {
    $staticID = $_GET[ 'staticID' ];
} else {
    $staticID = '';
}



            if (isset($_REQUEST[ 'static' ])) {
                $ergebnis_static =
                    safe_query(
                        "SELECT
                            `title`,
                            `staticID`,
                            `date`
                        FROM
                            " . PREFIX . "settings_static
                        WHERE
                            date between
                                " . $after . " AND
                                " . $before
                    );

                while ($ds = mysqli_fetch_array($ergebnis_static)) {
                    $staticID = $ds[ 'staticID' ];

                    $ergebnis_static_contents =
                        safe_query(
                            "SELECT
                                `content`
                            FROM
                                " . PREFIX . "settings_static
                            WHERE
                                `staticID` = '" . $staticID . "' AND
                                `content` LIKE '%" . $text . "%'"
                        );
                    if (!mysqli_num_rows($ergebnis_static_contents) &&
                        substr_count(strtolower($ds[ 'title' ]), strtolower(stripslashes($text))) == 0
                    ) {
                        continue;
                    } elseif (!mysqli_num_rows($ergebnis_static_contents)) {
                        $query_result = mysqli_fetch_array(
                            safe_query(
                                "SELECT
                                    content
                                FROM
                                    " . PREFIX . "settings_static
                                WHERE
                                    staticID = '" . $staticID . "'"
                            )
                        );
                        $res_message[ $i ] = $query_result[ 'content' ];
                        $content = array($query_result[ 'content' ]);
                    } else {
                        $content = array();
                        while ($qs = mysqli_fetch_array($ergebnis_static_contents)) {
                            $content[ ] = $qs[ 'content' ];
                        }
                        $res_message[ $i ] = $content[ 0 ];
                    }

$dy = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_static WHERE staticID='" . $staticID . "'"));

$allowed = false;
switch (@$dy[ 'accesslevel' ]) {
    case 0:
        $allowed = true;
        break;
    case 1:
        if ($userID) {
            $allowed = true;
        }
        break;
    case 2:
        if (isclanmember($userID)) {
            $allowed = true;
        }
        break;
}

if ($allowed) {


                    $res_title[ $i ] = $ds[ 'title' ];
                    $res_link[ $i ] =
                        '<a class="btn btn-primary" href="index.php?site=static&staticID=' . $staticID . '">' .
                        $plugin_language[ 'static_link' ] . '</a>';
                    $res_occurr[ $i ] = substri_count_array($content, stripslashes($text)) +
                        substr_count(strtolower($ds[ 'title' ]), strtolower(stripslashes($text))) +
                        count(array_intersect(\webspell\Tags::getTags('static', $staticID, true), $keywords)) * 2;
                    @$res_date[ $i ] = $ds[ 'date' ];
                    $res_type[ $i ] = $plugin_language[ 'static' ];
} else {
    
    echo($plugin_language['no_access' ]);
}
                    $i++;
                }
            }



if (isset($_GET[ 'userID' ])) {
    $userID = $_GET[ 'userID' ];
} else {
    $userID = '';
}

if (isset($_REQUEST['user'])){
    $result_user_contents = safe_query("SELECT `userID`, `nickname`, `firstname`, `lastname`, `about`, `registerdate` FROM `".PREFIX."user` WHERE `nickname` LIKE '%".$text."%' OR `firstname` LIKE '%".$text."%' OR `lastname` LIKE '%".$text."%' OR `about` LIKE '%".$text."%'");
                
                    $user_array = array();
                    while ($qs = mysqli_fetch_array($result_user_contents)) {
                    $user_array[ ] = array('firstname' => $qs[ 'firstname' ],'lastname' => $qs[ 'lastname' ],'nickname' => $qs[ 'nickname' ],'about' => $qs[ 'about' ]);
                      

                    $res_title[ $i ] = $user_array[ 0 ][ 'nickname' ];
                    $res_message[ $i ] = $user_array[ 0 ][ 'about' ];
                    $userID = $qs[ 'userID' ];

                    $res_title[ $i ] = $qs['nickname'];
                    $res_message[ $i ] = $qs['firstname'].' '.$qs['lastname'].' <br>Nickname: '.$qs['nickname'].' <br>About: ' .$qs['about'].'';
                    $res_link[ $i ] ='<a class="btn btn-primary" href="index.php?site=profile&id='.$qs['userID'].'">'.$plugin_language['user_link'].'</a>';
                    $res_occurr[$i]=substri_count_array(array($qs['nickname'], $qs['firstname'], $qs['lastname'], $qs['about']), stripslashes($text));
                    $res_date[ $i ] = $qs['registerdate'];
                    $res_type[ $i ] = $plugin_language['user'];
                    
                    $i++;
                }
            } 
       
if (isset($_REQUEST['squads'])){
                $result_squads =safe_query("SELECT `squadID`, `name`, `info` FROM `".PREFIX."plugins_squads` WHERE `name` LIKE '%".$text."%' OR `info` LIKE '%".$text."%'");
                $squads_array = array();
                while($ds=mysqli_fetch_array($result_squads)){
                    $squads_array[ ] = array('name' => $ds[ 'name' ],'info' => $ds[ 'info' ]);
                    

                    $res_title[ $i ] = $squads_array[ 0 ][ 'name' ];
                    $res_message[ $i ] = $squads_array[ 0 ][ 'info' ];
                    $squadID = $ds[ 'squadID' ];


                    $res_title[$i]=$ds['name'];
                    $res_message[$i]=$ds['info'];
                    $res_link[$i]='<a class="btn btn-primary" href="index.php?site=squads&action=show&squadID='.$ds['squadID'].'">'.$plugin_language['squads_link'].'</a>';
                    $res_occurr[$i]=substri_count_array(array($ds['name'], $ds['info']), stripslashes($text));
                    $res_date[$i]=time();
                    $res_type[$i]=$plugin_language['squads'];
                    
                    $i++;
                }
            }

if (isset($_REQUEST['events'])){
                $result_events =safe_query("SELECT `upID`, `date`, `short`, `title`, `location`, `locationhp`, `dateinfo` FROM `".PREFIX."plugins_upcoming` WHERE `short` LIKE '%".$text."%' OR `title` LIKE '%".$text."%' OR `location` LIKE '%".$text."%' OR `locationhp` LIKE '%".$text."%' OR `dateinfo` LIKE '%".$text."%'");
                $events_array = array();
                while($ds=mysqli_fetch_array($result_events)){
                $events_array[ ] = array('date' => $ds[ 'date' ],'short' => $ds[ 'short' ],'title' => $ds[ 'title' ],'location' => $ds[ 'location' ],'locationhp' => $ds[ 'locationhp' ],'dateinfo' => $ds[ 'dateinfo' ]);

                    $res_title[ $i ] = $events_array[ 0 ][ 'title' ];
                    $res_message[ $i ] = $events_array[ 0 ][ 'dateinfo' ];
                    $upID = $ds[ 'upID' ];

                    $res_title[$i]=$ds['title'];
                    $res_message[$i]=$ds['short'].'<br>'.$ds['location'].'<br>'.$ds['dateinfo'];
                    $res_link[$i]='<a class="btn btn-primary" href="index.php?site=calendar&tag='.date('j', $ds['date']).'&month='.date('n', $ds['date']).'&year='.date('Y', $ds['date']).'#event">'.$plugin_language['events_link'].'</a>';
                    $res_occurr[$i]=substri_count_array(array($ds['short'], $ds['title'], $ds['location'], $ds['locationhp'], $ds['dateinfo']), stripslashes($text));
                    $res_date[$i]=$ds['date'];
                    $res_type[$i]=$plugin_language['events'];
                    
                    $i++;
                }
            }

if (isset($_REQUEST[ 'gallery' ])) {
                $ergebnis_gallery = safe_query(
                    "SELECT
                        galleryID,
                        date
                    FROM
                        " . PREFIX . "plugins_gallery
                    WHERE
                        date between
                            " . $after . " AND
                            " . $before . "
                    ORDER BY
                        date"
                );

                while ($ds = mysqli_fetch_array($ergebnis_gallery)) {
                    $ergebnis_gallery_contents = safe_query(
                        "SELECT
                            name
                        FROM
                            " . PREFIX . "plugins_gallery
                        WHERE
                            galleryID = '" . $ds[ 'galleryID' ] . "' AND 
                        
                        name LIKE '%" . $text . "%'"
                    );
                    if (mysqli_num_rows($ergebnis_gallery_contents)) {
                        $gallery_array = array();
                        while ($qs = mysqli_fetch_array($ergebnis_gallery_contents)) {
                            $gallery_array[ ] = array('name' => $qs[ 'name' ]);
                        }
                        $galleryID = $ds[ 'galleryID' ];
                        

                         $res_title[ $i ] = $gallery_array[ 0 ][ 'name' ];
                            $res_message[ $i ] = $gallery_array[ 0 ][ 'name' ];
                        $res_link[ $i ] =
                            '<a class="btn btn-primary" href="index.php?site=gallery&amp;action=show&amp;galleryID=' . $galleryID . '">' . $plugin_language[ 'gallery_link' ] . '</a>';
                        $res_occurr[ $i ] = substri_count_array($gallery_array, stripslashes($text)) +
                            count(array_intersect(\webspell\Tags::getTags('gallery', $ds[ 'galleryID' ], true), $keywords)) * 2;
                        $res_date[ $i ] = $ds[ 'date' ];
                        $res_type[ $i ] = $plugin_language[ 'gallery' ];

                        $i++;
                    }
                }
            }

               if (isset($_REQUEST[ 'sponsors' ])) {
                $ergebnis_sponsors =
                    safe_query(
                        "SELECT
                            `name`,
                            `sponsorID`,
                            `date`
                        FROM
                            " . PREFIX . "plugins_sponsors
                        WHERE
                            date between
                                " . $after . " AND
                                " . $before
                    );

                while ($ds = mysqli_fetch_array($ergebnis_sponsors)) {
                    $sponsorID = $ds[ 'sponsorID' ];

                    $ergebnis_sponsors_contents =
                        safe_query(
                            "SELECT
                                *
                            FROM
                                " . PREFIX . "plugins_sponsors
                            WHERE
                                `sponsorID` = '" . $sponsorID . "' AND
                                `info` LIKE '%" . $text . "%'"
                        );
                    if (!mysqli_num_rows($ergebnis_sponsors_contents) &&
                        substr_count(strtolower($ds[ 'name' ]), strtolower(stripslashes($text))) == 0
                    ) {
                        continue;
                    } elseif (!mysqli_num_rows($ergebnis_sponsors_contents)) {
                        $query_result = mysqli_fetch_array(
                            safe_query(
                                "SELECT
                                    info
                                FROM
                                    " . PREFIX . "plugins_sponsors
                                WHERE
                                    sponsorID = '" . $sponsorID . "'"
                            )
                        );
                       $res_message[ $i ] = $query_result[ 'info' ];
                        $info = array($query_result[ 'info' ]);
                    } else {
                        $info = array();
                        while ($qs = mysqli_fetch_array($ergebnis_sponsors_contents)) {
                            $info[ ] = $qs[ 'info' ];
                        }
                        $res_message[ $i ] = $info[ 0 ];
                    }

                    $res_title[ $i ] = $ds[ 'name' ];
                    $res_link[ $i ] =
                        '<a class="btn btn-primary" href="index.php?site=sponsors&action=show&sponsorID=' . $sponsorID . '">' .
                        $plugin_language[ 'sponsors_link' ] . '</a>';
                    $res_occurr[ $i ] = substri_count_array($info, stripslashes($text)) +
                        substr_count(strtolower($ds[ 'name' ]), strtolower(stripslashes($text))) +
                        count(array_intersect(\webspell\Tags::getTags('sponsors', $sponsorID, true), $keywords)) * 2;
                    $res_date[ $i ] = $ds[ 'date' ];
                    $res_type[ $i ] = $plugin_language[ 'sponsors' ];

                    $i++;
                }
            }

            if (isset($_REQUEST[ 'links' ])) {
                $ergebnis_links = safe_query(
                    "SELECT
                        question,
                        linkID,
                        date
                    FROM
                        " . PREFIX . "plugins_links
                    WHERE
                        date between
                            " . $after . " AND
                            " . $before . "
                    ORDER BY
                        date"
                );

                while ($db = mysqli_fetch_array($ergebnis_links)) {
            
                   $ergebnis_links_contents = safe_query(
                        "SELECT 
                        question,
                        answer
                        FROM 
                            " . PREFIX . "plugins_links 
                        WHERE 
                            linkID = '" . $db[ 'linkID' ] . "' AND (
                        
                        question LIKE '%" . $text . "%' OR
                         answer LIKE '%" . $text . "%'
                        )"
                        
                    );
                    if (mysqli_num_rows($ergebnis_links_contents)) {
                        $links_array = array();
                        while ($qs = mysqli_fetch_array($ergebnis_links_contents)) {
                            $links_array[ ] = array('answer' => $qs[ 'answer' ], 'question' => $qs[ 'question' ]);
                        } 

                              $linkID = $db['linkID'];
          
                             
                              $res_title[ $i ] = $links_array[ 0 ][ 'question' ];
                              $res_message[ $i ] = $links_array[ 0 ][ 'answer' ];
                              $res_link[ $i ] = '<a class="btn btn-primary" href="index.php?site=links&linkID=' . $linkID . '">'.$plugin_language['links_link'].'</a>';
                              $res_occurr[ $i ] = substri_count_array($links_array, stripslashes($text)) +
                            count(array_intersect(\webspell\Tags::getTags('links', $db[ 'linkID' ], true), $keywords)) * 2;
                              $res_date[ $i ] = $db['date'];
                              $res_type[ $i ] = $plugin_language['links'];
          
                              $i++;
                    }
          }
               }

               if (isset($_REQUEST[ 'projectlist' ])) {
                $ergebnis_projectlist = safe_query(
                    "SELECT
                        question,
                        projectlistID,
                        date
                    FROM
                        " . PREFIX . "plugins_projectlist
                    WHERE
                        date between
                            " . $after . " AND
                            " . $before . "
                    ORDER BY
                        date"
                );

                while ($db = mysqli_fetch_array($ergebnis_projectlist)) {
            
                   $ergebnis_projectlist_contents = safe_query(
                        "SELECT 
                        question,
                        answer
                        FROM 
                            " . PREFIX . "plugins_projectlist 
                        WHERE 
                            projectlistID = '" . $db[ 'projectlistID' ] . "' AND (
                        
                        question LIKE '%" . $text . "%' OR
                         answer LIKE '%" . $text . "%'
                        )"
                        
                    );
                    if (mysqli_num_rows($ergebnis_projectlist_contents)) {
                        $projectlist_array = array();
                        while ($qs = mysqli_fetch_array($ergebnis_projectlist_contents)) {
                            $projectlist_array[ ] = array('answer' => $qs[ 'answer' ], 'question' => $qs[ 'question' ]);
                        } 

                              $projectlistID = $db['projectlistID'];
          
                             
                              $res_title[ $i ] = $projectlist_array[ 0 ][ 'question' ];
                              $res_message[ $i ] = $projectlist_array[ 0 ][ 'answer' ];
                              $res_link[ $i ] = '<a class="btn btn-primary" href="index.php?site=projectlist&action=content&projectlistID=' . $projectlistID . '">'.$plugin_language['projectlist_link'].'</a>';
                              $res_occurr[ $i ] = substri_count_array($projectlist_array, stripslashes($text)) +
                            count(array_intersect(\webspell\Tags::getTags('projectlist', $db[ 'projectlistID' ], true), $keywords)) * 2;
                              $res_date[ $i ] = $db['date'];
                              $res_type[ $i ] = $plugin_language['projectlist'];
          
                              $i++;
                    }
          }
               }


            if (isset($_REQUEST[ 'planning' ])) {
                $ergebnis_planning = safe_query(
                    "SELECT
                        name,
                        planID
                    FROM
                        " . PREFIX . "plugins_planning"
                );

                while ($db = mysqli_fetch_array($ergebnis_planning)) {
            
                   $ergebnis_planning_contents = safe_query(
                        "SELECT 
                        *
                        FROM 
                            " . PREFIX . "plugins_planning 
                        WHERE 
                            planID = '" . $db[ 'planID' ] . "' AND 
                         `name` LIKE '%" . $text . "%'"
                        
                    );
                    
                    
                        if (mysqli_num_rows($ergebnis_planning_contents)) {
                        $planning_array = array();
                        while ($qs = mysqli_fetch_array($ergebnis_planning_contents)) {
                            $planning_array[ ] = array('info' => $qs[ 'info' ], 'name' => $qs[ 'name' ]);
                        } 

                              $planID = $db['planID'];
          
                             
                              $res_title[ $i ] = $planning_array[ 0 ][ 'name' ];
                              $res_message[ $i ] = $planning_array[ 0 ][ 'info' ];
                              $res_link[ $i ] = '<a class="btn btn-primary" href="index.php?site=planning">'.$plugin_language['planning_link'].'</a>';
                              $res_occurr[ $i ] = substri_count_array($planning_array, stripslashes($text));
                              @$res_date[ $i ] = $db['date'];
                              $res_type[ $i ] = $plugin_language['planning'];
          
                              $i++;
                    }
          }
               }

            
               if (isset($_REQUEST[ 'todo' ])) {
                $ergebnis_todo =
                    safe_query(
                        "SELECT
                            `title`,
                            `todoID`,
                            `date`
                        FROM
                            " . PREFIX . "plugins_todo
                        WHERE
                            date between
                                " . $after . " AND
                                " . $before
                    );

                while ($ds = mysqli_fetch_array($ergebnis_todo)) {
                    $todoID = $ds[ 'todoID' ];

                    $ergebnis_todo_contents =
                        safe_query(
                            "SELECT
                                *
                            FROM
                                " . PREFIX . "plugins_todo
                            WHERE
                                `todoID` = '" . $todoID . "' AND
                                `text` LIKE '%" . $text . "%'"
                        );
                    if (!mysqli_num_rows($ergebnis_todo_contents) &&
                        substr_count(strtolower($ds[ 'title' ]), strtolower(stripslashes($text))) == 0
                    ) {
                        continue;
                    } elseif (!mysqli_num_rows($ergebnis_todo_contents)) {
                        $query_result = mysqli_fetch_array(
                            safe_query(
                                "SELECT
                                    text
                                FROM
                                    " . PREFIX . "plugins_todo
                                WHERE
                                    todoID = '" . $todoID . "'"
                            )
                        );
                       $res_message[ $i ] = $query_result[ 'text' ];
                        $info = array($query_result[ 'text' ]);
                    } else {
                        $info = array();
                        while ($qs = mysqli_fetch_array($ergebnis_todo_contents)) {
                            $info[ ] = $qs[ 'text' ];
                        }
                        $res_message[ $i ] = $info[ 0 ];
                    }

                    $res_title[ $i ] = $ds[ 'title' ];
                    $res_link[ $i ] =
                        '<a class="btn btn-primary" href="index.php?site=todo&action=show&todoID=' . $todoID . '">' .
                        $plugin_language[ 'todo_link' ] . '</a>';
                    $res_occurr[ $i ] = substri_count_array($info, stripslashes($text)) +
                        substr_count(strtolower($ds[ 'title' ]), strtolower(stripslashes($text))) +
                        count(array_intersect(\webspell\Tags::getTags('todo', $todoID, true), $keywords)) * 2;
                    $res_date[ $i ] = $ds[ 'date' ];
                    $res_type[ $i ] = $plugin_language[ 'todo' ];

                    $i++;
                }
            }


            if (isset($_REQUEST[ 'partners' ])) {
                $ergebnis_partners =
                    safe_query(
                        "SELECT
                            `name`,
                            `partnerID`,
                            `date`
                        FROM
                            " . PREFIX . "plugins_partners
                        WHERE
                            date between
                                " . $after . " AND
                                " . $before
                    );

                while ($ds = mysqli_fetch_array($ergebnis_partners)) {
                    $partnerID = $ds[ 'partnerID' ];

                    $ergebnis_partners_contents =
                        safe_query(
                            "SELECT
                                *
                            FROM
                                " . PREFIX . "plugins_partners
                            WHERE
                                `partnerID` = '" . $partnerID . "' AND
                                `info` LIKE '%" . $text . "%'"
                        );
                    if (!mysqli_num_rows($ergebnis_partners_contents) &&
                        substr_count(strtolower($ds[ 'name' ]), strtolower(stripslashes($text))) == 0
                    ) {
                        continue;
                    } elseif (!mysqli_num_rows($ergebnis_partners_contents)) {
                        $query_result = mysqli_fetch_array(
                            safe_query(
                                "SELECT
                                    info
                                FROM
                                    " . PREFIX . "plugins_partners
                                WHERE
                                    partnerID = '" . $partnerID . "'"
                            )
                        );
                       $res_message[ $i ] = $query_result[ 'info' ];
                        $info = array($query_result[ 'info' ]);
                    } else {
                        $info = array();
                        while ($qs = mysqli_fetch_array($ergebnis_partners_contents)) {
                            $info[ ] = $qs[ 'info' ];
                        }
                        $res_message[ $i ] = $info[ 0 ];
                    }

                    $res_title[ $i ] = $ds[ 'name' ];
                    $res_link[ $i ] = '<a class="btn btn-primary" href="index.php?site=partners&action=show&partnerID=' . $partnerID . '">' .
                        $plugin_language[ 'partners_link' ] . '</a>';
                    $res_occurr[ $i ] = substri_count_array($info, stripslashes($text)) +
                        substr_count(strtolower($ds[ 'name' ]), strtolower(stripslashes($text))) +
                        count(array_intersect(\webspell\Tags::getTags('partners', $partnerID, true), $keywords)) * 2;
                    $res_date[ $i ] = $ds[ 'date' ];
                    $res_type[ $i ] = $plugin_language[ 'partners' ];

                    $i++;
                }
            }

            

               if (isset($_REQUEST[ 'history' ])) {
                $ergebnis_history = safe_query(
                    "SELECT
                        historyID
                    FROM
                        " . PREFIX . "plugins_history"
                );

                while ($db = mysqli_fetch_array($ergebnis_history)) {
            
                   $ergebnis_history_contents = safe_query(
                        "SELECT 
                        *
                        FROM 
                            " . PREFIX . "plugins_history 
                        WHERE 
                            historyID = '" . $db[ 'historyID' ] . "' AND 
                         `text` LIKE '%" . $text . "%'"
                        
                    );
                    
                    
                        if (mysqli_num_rows($ergebnis_history_contents)) {
                        $history_array = array();
                        while ($qs = mysqli_fetch_array($ergebnis_history_contents)) {
                            $history_array[ ] = array('text' => $qs[ 'text' ], 'title' => $qs[ 'title' ]);
                        } 

                              $historyID = $db['historyID'];
          
                             
                              $res_title[ $i ] = $history_array[ 0 ][ 'title' ];
                              $res_message[ $i ] = $history_array[ 0 ][ 'text' ];
                              $res_link[ $i ] = '<a class="btn btn-primary" href="index.php?site=history">'.$plugin_language['history_link'].'</a>';
                              $res_occurr[ $i ] = substri_count_array($history_array, stripslashes($text)) +
                            count(array_intersect(\webspell\Tags::getTags('history', $db[ 'historyID' ], true), $keywords)) * 2;
                              @$res_date[ $i ] = $db['date'];
                              $res_type[ $i ] = $plugin_language['history'];
          
                              $i++;
                    }
          }
               }


            if (isset($_REQUEST[ 'blog' ])) {
                $ergebnis_blog = safe_query(
                    "SELECT
                        headline,
                        blogID,
                        `date`
                    FROM
                        " . PREFIX . "plugins_blog
                        WHERE
                            date between
                                " . $after . " AND
                                " . $before
                );

                while ($db = mysqli_fetch_array($ergebnis_blog)) {
            
                   $ergebnis_blog_contents = safe_query(
                        "SELECT 
                        *
                        FROM 
                            " . PREFIX . "plugins_blog 
                        WHERE 
                            blogID = '" . $db[ 'blogID' ] . "' AND 
                         `msg` LIKE '%" . $text . "%'"
                        
                    );
                    
                    
                        if (mysqli_num_rows($ergebnis_blog_contents)) {
                        $blog_array = array();
                        while ($qs = mysqli_fetch_array($ergebnis_blog_contents)) {
                            $blog_array[ ] = array('msg' => $qs[ 'msg' ], 'headline' => $qs[ 'headline' ]);
                        } 

                              $blogID = $db['blogID'];
          
                             
                              $res_title[ $i ] = $blog_array[ 0 ][ 'headline' ];
                            $res_message[ $i ] = $blog_array[ 0 ][ 'msg' ];
                              $res_link[ $i ] = '<a class="btn btn-primary" href="index.php?site=blog&action=show&blogID=' . $blogID . '">'.$plugin_language['blog_link'].'</a>';
                              $res_occurr[ $i ] = substri_count_array($blog_array, stripslashes($text));
                              $res_date[ $i ] = $db['date'];
                              $res_type[ $i ] = $plugin_language['blog'];
          
                              $i++;
                    }
          }
               }

               if (isset($_REQUEST[ 'about_us' ])) {
                $ergebnis_about_us = safe_query(
                    "SELECT
                        title,
                        aboutID
                    FROM
                        " . PREFIX . "plugins_about_us"
                );

                while ($db = mysqli_fetch_array($ergebnis_about_us)) {
            
                   $ergebnis_about_us_contents = safe_query(
                        "SELECT 
                        *
                        FROM 
                            " . PREFIX . "plugins_about_us 
                        WHERE 
                            aboutID = '" . $db[ 'aboutID' ] . "' AND 
                         `text` LIKE '%" . $text . "%'"
                        
                    );
                    
                    
                        if (mysqli_num_rows($ergebnis_about_us_contents)) {
                        $about_us_array = array();
                        while ($qs = mysqli_fetch_array($ergebnis_about_us_contents)) {
                            $about_us_array[ ] = array('text' => $qs[ 'text' ], 'title' => $qs[ 'title' ]);
                        } 

                              $aboutID = $db['aboutID'];
          
                             
                              $res_title[ $i ] = $about_us_array[ 0 ][ 'title' ];
                              $res_message[ $i ] = $about_us_array[ 0 ][ 'text' ];
                              $res_link[ $i ] = '<a class="btn btn-primary" href="index.php?site=about_us">'.$plugin_language['about_us_link'].'</a>';
                              $res_occurr[ $i ] = substri_count_array($about_us_array, stripslashes($text));
                              @$res_date[ $i ] = $db['date'];
                              $res_type[ $i ] = $plugin_language['about_us'];
          
                              $i++;
                    }
          }
               }



        if (isset($_REQUEST[ 'articles' ])) {
                $ergebnis_articles = safe_query(
                    "SELECT
                        question,
                        articleID,
                        date
                    FROM
                        " . PREFIX . "plugins_articles
                    WHERE
                        date between
                            " . $after . " AND
                            " . $before . "
                    ORDER BY
                        date"
                );

                while ($db = mysqli_fetch_array($ergebnis_articles)) {
            
                   $ergebnis_articles_contents = safe_query(
                        "SELECT 
                        question,
                        answer
                        FROM 
                            " . PREFIX . "plugins_articles 
                        WHERE 
                            articleID = '" . $db[ 'articleID' ] . "' AND (
                        
                        question LIKE '%" . $text . "%' OR
                         answer LIKE '%" . $text . "%'
                        )"
                        
                    );
                    if (mysqli_num_rows($ergebnis_articles_contents)) {
                        $articles_array = array();
                        while ($qs = mysqli_fetch_array($ergebnis_articles_contents)) {
                            $articles_array[ ] = array('answer' => $qs[ 'answer' ], 'question' => $qs[ 'question' ]);
                        } 

                              $articleID = $db['articleID'];
          
                             
                              $res_title[ $i ] = $articles_array[ 0 ][ 'question' ];
                            $res_message[ $i ] = $articles_array[ 0 ][ 'answer' ];
                              $res_link[ $i ] = '<a class="btn btn-primary" href="index.php?site=articles&articleID=' . $articleID . '">'.$plugin_language['articles_link'].'</a>';
                              $res_occurr[ $i ] = substri_count_array($articles_array, stripslashes($text)) +
                            count(array_intersect(\webspell\Tags::getTags('articles', $db[ 'articleID' ], true), $keywords)) * 2;
                              $res_date[ $i ] = $db['date'];
                              $res_type[ $i ] = $plugin_language['article'];
          
                              $i++;
                    }
          }
               }



        if (isset($_REQUEST[ 'clan_rules' ])) {
                $ergebnis_clan_rules = safe_query(
                    "SELECT
                        title,
                        clan_rulesID,
                        date
                    FROM
                        " . PREFIX . "plugins_clan_rules
                    WHERE
                        date between
                            " . $after . " AND
                            " . $before . "
                    ORDER BY
                        date"
                );

                while ($db = mysqli_fetch_array($ergebnis_clan_rules)) {
            
                   $ergebnis_clan_rules_contents = safe_query(
                        "SELECT 
                        title,
                        text
                        FROM 
                            " . PREFIX . "plugins_clan_rules 
                        WHERE 
                            clan_rulesID = '" . $db[ 'clan_rulesID' ] . "' AND (
                        
                        title LIKE '%" . $text . "%' OR
                         text LIKE '%" . $text . "%'
                        )"
                        
                    );
                    if (mysqli_num_rows($ergebnis_clan_rules_contents)) {
                        $clan_rules_array = array();
                        while ($qs = mysqli_fetch_array($ergebnis_clan_rules_contents)) {
                            $clan_rules_array[ ] = array('text' => $qs[ 'text' ], 'title' => $qs[ 'title' ]);
                        } 

                              $clan_rulesID = $db['clan_rulesID'];
          
                             
                              $res_title[ $i ] = $clan_rules_array[ 0 ][ 'title' ];
                            $res_message[ $i ] = $clan_rules_array[ 0 ][ 'text' ];
                              $res_link[ $i ] = '<a class="btn btn-primary" href="index.php?site=clan_rules&action=show&clan_rulesID=' . $clan_rulesID . '">'.$plugin_language['clan_rules_link'].'</a>';
                              $res_occurr[ $i ] = substri_count_array($clan_rules_array, stripslashes($text)) +
                            count(array_intersect(\webspell\Tags::getTags('clan_rules', $db[ 'clan_rulesID' ], true), $keywords)) * 2;
                              $res_date[ $i ] = $db['date'];
                              $res_type[ $i ] = $plugin_language['clan_rules'];
          
                              $i++;
                    }
          }
               }

            if (isset($_REQUEST[ 'server_rules' ])) {
                $ergebnis_server_rules = safe_query(
                    "SELECT
                        title,
                        server_rulesID,
                        date
                    FROM
                        " . PREFIX . "plugins_server_rules
                    WHERE
                        date between
                            " . $after . " AND
                            " . $before . "
                    ORDER BY
                        date"
                );

                while ($db = mysqli_fetch_array($ergebnis_server_rules)) {
            
                   $ergebnis_server_rules_contents = safe_query(
                        "SELECT 
                        title,
                        text
                        FROM 
                            " . PREFIX . "plugins_server_rules 
                        WHERE 
                            server_rulesID = '" . $db[ 'server_rulesID' ] . "' AND (
                        
                        title LIKE '%" . $text . "%' OR
                         text LIKE '%" . $text . "%'
                        )"
                        
                    );
                    if (mysqli_num_rows($ergebnis_server_rules_contents)) {
                        $server_rules_array = array();
                        while ($qs = mysqli_fetch_array($ergebnis_server_rules_contents)) {
                            $server_rules_array[ ] = array('text' => $qs[ 'text' ], 'title' => $qs[ 'title' ]);
                        } 

                              $server_rulesID = $db['server_rulesID'];
          
                             
                              $res_title[ $i ] = $server_rules_array[ 0 ][ 'title' ];
                            $res_message[ $i ] = $server_rules_array[ 0 ][ 'text' ];
                              $res_link[ $i ] = '<a class="btn btn-primary" href="index.php?site=server_rules&action=show&server_rulesID=' . $server_rulesID . '">'.$plugin_language['server_rules_link'].'</a>';
                              $res_occurr[ $i ] = substri_count_array($server_rules_array, stripslashes($text)) +
                            count(array_intersect(\webspell\Tags::getTags('server_rules', $db[ 'server_rulesID' ], true), $keywords)) * 2;
                              $res_date[ $i ] = $db['date'];
                              $res_type[ $i ] = $plugin_language['server_rules'];
          
                              $i++;
                    }
          }
               }


            if (isset($_REQUEST[ 'servers' ])) {
                $ergebnis_servers = safe_query(
                    "SELECT
                        name,
                        serverID
                    FROM
                        " . PREFIX . "plugins_servers"
                );

                while ($db = mysqli_fetch_array($ergebnis_servers)) {
            
                   $ergebnis_servers_contents = safe_query(
                        "SELECT 
                        name,
                        info
                        FROM 
                            " . PREFIX . "plugins_servers 
                        WHERE 
                            serverID = '" . $db[ 'serverID' ] . "' AND (
                        
                        name LIKE '%" . $text . "%' OR
                         info LIKE '%" . $text . "%'
                        )"
                        
                    );
                    if (mysqli_num_rows($ergebnis_servers_contents)) {
                        $servers_array = array();
                        while ($qs = mysqli_fetch_array($ergebnis_servers_contents)) {
                            $servers_array[ ] = array('info' => $qs[ 'info' ], 'name' => $qs[ 'name' ]);
                        } 

                              $serverID = $db['serverID'];
          
                             
                              $res_title[ $i ] = $servers_array[ 0 ][ 'name' ];
                            $res_message[ $i ] = $servers_array[ 0 ][ 'info' ];
                              $res_link[ $i ] = '<a class="btn btn-primary" href="index.php?site=servers&action=show&serverID=' . $serverID . '">'.$plugin_language['servers_link'].'</a>';
                              $res_occurr[ $i ] = substri_count_array($servers_array, stripslashes($text)) +
                            count(array_intersect(\webspell\Tags::getTags('servers', $db[ 'serverID' ], true), $keywords)) * 2;
                              @$res_date[ $i ] = $ds['date'];
                              $res_type[ $i ] = $plugin_language['servers'];
          
                              $i++;
                    }
          }
               }        

            if (isset($_REQUEST[ 'files' ])) {
                $ergebnis_files = safe_query(
                    "SELECT
                        fileID,
                        filecatID,
                        date
                    FROM
                        " . PREFIX . "plugins_files
                    WHERE
                        date between
                            " . $after . " AND
                            " . $before . "
                    ORDER BY
                        date"
                );

                while ($ds = mysqli_fetch_array($ergebnis_files)) {
            
                   $ergebnis_files_contents = safe_query(
                        "SELECT 
                            filename,
                            info
                        FROM 
                            " . PREFIX . "plugins_files 
                        WHERE 
                            fileID = '" . $ds[ 'fileID' ] . "' AND (
                        
                        filename LIKE '%" . $text . "%' OR
                         info LIKE '%" . $text . "%'
                        )"
                    );
                    
                    
                        if (mysqli_num_rows($ergebnis_files_contents)) {
                        $files_array = array();
                        while ($qs = mysqli_fetch_array($ergebnis_files_contents)) {
                            $files_array[ ] = array('info' => $qs[ 'info' ], 'filename' => $qs[ 'filename' ]);
                        } 

                              $fileID = $ds['fileID'];
                              $filecatID = $ds['filecatID'];
          
                             
                              $res_title[ $i ] = $files_array[ 0 ][ 'filename' ];
                              $res_message[ $i ] = $files_array[ 0 ][ 'info' ];
                              $res_link[ $i ] = '<a class="btn btn-primary" href="index.php?site=files&file='.$fileID.'">'.$plugin_language['files_link'].'</a>';
                              $res_occurr[ $i ] = substri_count_array($files_array, stripslashes($text)) +
                            count(array_intersect(\webspell\Tags::getTags('files', $ds[ 'fileID' ], true), $keywords)) * 2;
                              $res_date[ $i ] = $ds['date'];
                              $res_type[ $i ] = $plugin_language['files'];
          
                              $i++;
                    }
          }
               }
            if (isset($_REQUEST[ 'faq' ])) {
                $ergebnis_faq = safe_query(
                    "SELECT
                        faqID,
                        faqcatID,
                        date
                    FROM
                        " . PREFIX . "plugins_faq
                    WHERE
                        date between
                            " . $after . " AND
                            " . $before . "
                    ORDER BY
                        date"
                );

                while ($ds = mysqli_fetch_array($ergebnis_faq)) {
                    $ergebnis_faq_contents = safe_query(
                        "SELECT
                            question,
                            answer
                        FROM
                            " . PREFIX . "plugins_faq
                        WHERE
                            faqID = '" . $ds[ 'faqID' ] . "' AND (
                                answer LIKE '%" . $text . "%' OR
                                question LIKE '%" . $text . "%'
                            )"
                    );
                    if (mysqli_num_rows($ergebnis_faq_contents)) {
                        $faq_array = array();
                        while ($qs = mysqli_fetch_array($ergebnis_faq_contents)) {
                            $faq_array[ ] = array('question' => $qs[ 'question' ], 'answer' => $qs[ 'answer' ]);
                        }
                        $faqID = $ds[ 'faqID' ];
                        $faqcatID = $ds[ 'faqcatID' ];

                        $res_title[ $i ] = $faq_array[ 0 ][ 'question' ];
                        $res_message[ $i ] = $faq_array[ 0 ][ 'answer' ];
                        $res_link[ $i ] = '<a class="btn btn-primary" href="index.php?site=faq&amp;action=faq&amp;faqID=' . $faqID . '&amp;faqcatID=' .
                            $faqcatID . '">' . $plugin_language[ 'faq_link' ] . '</a>';
                        $res_occurr[ $i ] = substri_count_array($faq_array, stripslashes($text)) +
                            count(array_intersect(\webspell\Tags::getTags('faq', $ds[ 'faqID' ], true), $keywords)) * 2;
                        $res_date[ $i ] = $ds[ 'date' ];
                        $res_type[ $i ] = $plugin_language[ 'faq' ];

                        $i++;
                    }
                }
            }
            if (isset($_REQUEST[ 'wiki' ])) {
                $ergebnis_wiki = safe_query(
                    "SELECT
                        wikiID,
                        wikicatID,
                        date
                    FROM
                        " . PREFIX . "plugins_wiki
                    WHERE
                        date between
                            " . $after . " AND
                            " . $before . "
                    ORDER BY
                        date"
                );

                while ($ds = mysqli_fetch_array($ergebnis_wiki)) {
                    $ergebnis_wiki_contents = safe_query(
                        "SELECT
                            question,
                            answer
                        FROM
                            " . PREFIX . "plugins_wiki
                        WHERE
                            wikiID = '" . $ds[ 'wikiID' ] . "' AND (
                                answer LIKE '%" . $text . "%' OR
                                question LIKE '%" . $text . "%'
                            )"
                    );
                    if (mysqli_num_rows($ergebnis_wiki_contents)) {
                        $wiki_array = array();
                        while ($qs = mysqli_fetch_array($ergebnis_wiki_contents)) {
                            $wiki_array[ ] = array('question' => $qs[ 'question' ], 'answer' => $qs[ 'answer' ]);
                        }
                        $wikiID = $ds[ 'wikiID' ];
                        $wikicatID = $ds[ 'wikicatID' ];

                        $res_title[ $i ] = $wiki_array[ 0 ][ 'question' ];
                        $res_message[ $i ] = $wiki_array[ 0 ][ 'answer' ];
                        $res_link[ $i ] = '<a class="btn btn-primary" href="index.php?site=wiki&amp;action=wiki&amp;wikiID=' . $wikiID . '&amp;wikicatID=' .
                            $wikicatID . '">' . $plugin_language[ 'wiki_link' ] . '</a>';
                        $res_occurr[ $i ] = substri_count_array($wiki_array, stripslashes($text)) +
                            count(array_intersect(\webspell\Tags::getTags('wiki', $ds[ 'wikiID' ], true), $keywords)) * 2;
                        $res_date[ $i ] = $ds[ 'date' ];
                        $res_type[ $i ] = $plugin_language[ 'wiki' ];

                        $i++;
                    }
                }
            }
            if (isset($_REQUEST[ 'forum' ])) {
                $ergebnis_forum = safe_query(
                    "SELECT
                        b.readgrps,
                        t.boardID,
                        p.date,
                        p.topicID,
                        t.topic,
                        t.topic as topicname,
                        p.message
                    FROM
                        " . PREFIX . "plugins_forum_posts p
                    JOIN " . PREFIX . "plugins_forum_topics t ON p.topicID = t.topicID
                    JOIN " . PREFIX . "plugins_forum_boards b ON p.boardID = b.boardID
                    WHERE
                        p.date between " . $after . " AND " . $before . " AND (
                            p.message LIKE '%" . $text . "%' OR
                            t.topic LIKE '%" . $text . "%'
                        )
                    GROUP BY
                        postID
                    ORDER BY
                        date"
                );

                while ($ds = mysqli_fetch_array($ergebnis_forum)) {
                    if ($ds[ 'readgrps' ] != "") {
                        $usergrps = explode(";", $ds[ 'readgrps' ]);
                        $usergrp = 0;
                        foreach ($usergrps as $value) {
                            if (isinusergrp($value, $userID)) {
                                $usergrp = 1;
                                break;
                            }
                        }
                        if (!$usergrp && !ismoderator($userID, $ds[ 'boardID' ])) {
                            continue;
                        }
                    }
                    $topicID = $ds[ 'topicID' ];

                    $res_title[ $i ] = getinput($ds[ 'topicname' ]);
                    $res_message[ $i ] = $ds[ 'message' ];
                    $res_link[ $i ] = '<a class="btn btn-primary" href="index.php?site=forum_topic&amp;topic=' . $topicID . '">' .
                        $plugin_language[ 'forum_link' ] . '</a>';
                    $res_occurr[ $i ] = substr_count(strtolower($ds[ 'message' ]), strtolower(stripslashes($text))) +
                        substr_count(strtolower($ds[ 'topic' ]), strtolower(stripslashes($text)));
                    $res_date[ $i ] = $ds[ 'date' ];
                    $res_type[ $i ] = $plugin_language[ 'forum' ];

                    if (isset($alreadythere)) {
                        unset($alreadythere);
                    }
                    $key = array_search($res_link[ $i ], $res_link);
                    if ($key !== null && $key !== false) {
                        if ($key != $i) {
                            $res_occurr[ $key ] += $res_occurr[ $i ];
                            $alreadythere = true;
                        }
                    }

                    if (isset($alreadythere)) {
                        unset($res_title[ $i ]);
                        unset($res_message[ $i ]);
                        unset($res_link[ $i ]);
                        unset($res_occurr[ $i ]);
                        unset($res_date[ $i ]);
                        unset($res_type[ $i ]);
                    } else {
                        $i++;
                    }
                }
            }
            if (isset($_REQUEST[ 'news' ])) {
                $ergebnis_news = safe_query(
                    "SELECT
                        `date`,
                        `poster`,
                        `newsID`,
                        `headline`,
                            `content`
                    FROM
                        " . PREFIX . "plugins_news
                    WHERE
                            `date` between
                                " . $after . " AND
                                " . $before."
                                AND (
                                `content` LIKE '%" . $text . "%' OR
                                `headline` LIKE '%" . $text . "%'
                            )"
                );

                while ($ds = mysqli_fetch_array($ergebnis_news)) {
                    $ergebnis_news_contents = safe_query(
                        "SELECT
                            `headline`,
                            `content`
                        FROM
                            " . PREFIX . "plugins_news
                        WHERE
                            `newsID` = '" . $ds[ 'newsID' ] . "' AND (
                                `content` LIKE '%" . $text . "%' OR
                                `headline` LIKE '%" . $text . "%'
                            )"
                    );
                    if (mysqli_num_rows($ergebnis_news_contents)) {
                        $message_array = array();
                        while ($qs = mysqli_fetch_array($ergebnis_news_contents)) {
                            $message_array[ ] = array(
                                'headline' => $qs[ 'headline' ],
                                'content' => $qs[ 'content' ]
                            );
                        }
                        

                        $newsID = $ds[ 'newsID' ];

                        $res_title[ $i ] = $ds[ 'headline' ];
                        $res_message[ $i ] = $ds[ 'content' ];
                        $res_link[ $i ] = '<a class="btn btn-primary" href="index.php?site=news_manager&action=news_contents&newsID=' . $newsID . '">' .
                            $plugin_language[ 'news_link' ] . '</a>';
                        $res_occurr[ $i ] = substri_count_array($message_array, stripslashes($text)) +
                            count(
                                array_intersect(
                                    \webspell\Tags::getTags(
                                        'news',
                                        $ds[ 'newsID' ],
                                        true
                                    ),
                                    $keywords
                                )
                            )
                            * 2;
                        $res_date[ $i ] = $ds[ 'date' ];
                        $res_type[ $i ] = $plugin_language[ 'news' ];

                        $i++;
                    }
                }
            }



$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='faq'"));
if (@$dx[ 'modulname' ] != 'faq') {
        @$faq = '';
    } else {
        @$faq = "&amp;faq=" . $_REQUEST[ 'faq' ] . "";
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='wiki'"));
if (@$dx[ 'modulname' ] != 'wiki') {
        @$wiki = '';
    } else {
        #@$wiki = "&amp;wiki=" . $_REQUEST[ 'wiki' ] . "";
    }    
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='news_manager'"));
if (@$dx[ 'modulname' ] != 'news_manager') {
        @$news = '';
    } else {
        @$news = "&amp;news=" . $_REQUEST[ 'news' ] . "";
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='forum'"));
if (@$dx[ 'modulname' ] != 'forum') {
        @$forum = '';
    } else {
        @$forum = "&amp;forum=" . $_REQUEST[ 'forum' ] . "";
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='files'"));
if (@$dx[ 'modulname' ] != 'files') {
        @$files = '';
    } else {
        @$files = "&amp;files=" . $_REQUEST[ 'files' ] . "";
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='articles'"));
if (@$dx[ 'modulname' ] != 'articles') {
        @$articles = '';
    } else {
        @$articles = "&amp;articles=" . $_REQUEST[ 'articles' ] . "";
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='about_us'"));
if (@$dx[ 'modulname' ] != 'about_us') {
        @$about_us = '';
    } else {
        @$about_us = "&amp;about_us=" . $_REQUEST[ 'about_us' ] . "";
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='blog'"));
if (@$dx[ 'modulname' ] != 'blog') {
        @$blog = '';
    } else {
        @$blog = "&amp;blog=" . $_REQUEST[ 'blog' ] . "";
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='history'"));
if (@$dx[ 'modulname' ] != 'history') {
        @$history = '';
    } else {
        @$history = "&amp;history=" . $_REQUEST[ 'history' ] . "";
    } 
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='partners'"));
if (@$dx[ 'modulname' ] != 'partners') {
        @$partners = '';
    } else {
        @$partners = "&amp;partners=" . $_REQUEST[ 'partners' ] . "";
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='todo'"));
if (@$dx[ 'modulname' ] != 'todo') {
        @$todo = '';
    } else {
        @$todo = "&amp;todo=" . $_REQUEST[ 'todo' ] . "";
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='sponsors'"));
if (@$dx[ 'modulname' ] != 'sponsors') {
        @$sponsors = '';
    } else {
        @$sponsors = "&amp;sponsors=" . $_REQUEST[ 'sponsors' ] . "";
    }

$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='planning'"));
if (@$dx[ 'modulname' ] != 'planning') {
        @$planning = '';
    } else {
        @$planning = "&amp;planning=" . $_REQUEST[ 'planning' ] . "";
    }    
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='links'"));
if (@$dx[ 'modulname' ] != 'links') {
        @$links = '';
    } else {
        @$links = "&amp;links=" . $_REQUEST[ 'links' ] . "";
    } 
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='gallery'"));
if (@$dx[ 'modulname' ] != 'gallery') {
        @$gallery = '';
    } else {
        @$gallery = "&amp;gallery=" . $_REQUEST[ 'gallery' ] . "";
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='clan_rules'"));
if (@$dx[ 'modulname' ] != 'clan_rules') {
        @$clan_rules = '';
    } else {
        @$clan_rules = "&amp;clan_rules=" . $_REQUEST[ 'clan_rules' ] . "";
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='server_rules'"));
if (@$dx[ 'modulname' ] != 'server_rules') {
        @$server_rules = '';
    } else {
        @$server_rules = "&amp;server_rules=" . $_REQUEST[ 'server_rules' ] . "";
    } 
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='servers'"));
if (@$dx[ 'modulname' ] != 'servers') {
        @$servers = '';
    } else {
        @$servers = "&amp;servers=" . $_REQUEST[ 'servers' ] . "";
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='calendar'"));
if (@$dx[ 'modulname' ] != 'calendar') {
        @$events = '';
    } else {
        @$events = "&amp;calendar=" . $_REQUEST[ 'calendar' ] . "";
    }          
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='projectlist'"));
if (@$dx[ 'modulname' ] != 'projectlist') {
        @$projectlist = '';
    } else {
        @$projectlist = "&amp;projectlist=" . $_REQUEST[ 'projectlist' ] . "";
    } 



            $count_results = $i;
            echo "<p class=\"text-center\"><strong>" . $count_results . "</strong> " .
                $plugin_language[ 'results_found' ] . "</p><br><br>";

            $pages = ceil($count_results / $results);
            
            if($pages > 1) echo makepagelink(
                    "index.php?site=search&amp;action=search".$articles."" .$about_us . "" . $faq . "" . $news . "" . $files . "" . $forum . "" . $blog . "" . $history . "" . $partners . "" . $todo . "" . $sponsors . "" . $planning . "" . $links . "" . $projectlist . "" . $gallery . "" . $clan_rules . "" . $server_rules . "" . $servers . "" . $events . "&amp;r=" . $_REQUEST[ 'r' ] . "&amp;text=" . $_REQUEST[ 'text' ] . "&amp;afterdate=" . $_REQUEST[ 'afterdate' ] . "&amp;beforedate=" . $_REQUEST[ 'beforedate' ] . "&amp;order=" . $_REQUEST[ 'order' ]."", $page, $pages);

            // sort results
            if ($_REQUEST[ 'order' ] == '2') {
                asort($res_occurr);
            } else {
                arsort($res_occurr);
            }

            $i = 0;
            foreach ($res_occurr as $key => $val) {
                if ($page > 1 && $i < ($results * ($page - 1))) {
                    $i++;
                    continue;
                }
                if ($i >= ($results * $page)) {
                    break;
                }

                $date = getformatdate($res_date[ $key ]);
                $type = $res_type[ $key ];
                $str_len = mb_strlen($res_message[ $key ]);

                if ($str_len > 200) {
                    for ($z = 0; $z < $str_len; $z++) {
                        $tmp = mb_substr($res_message[ $key ], $z, 1);
                        if ($z >= 200 && $tmp == " ") {
                            $res_message[ $key ] = mb_substr($res_message[ $key ], 0, $z) . "...</strong></b></u></i></s></code></small></pre></li></ul></blockquote>";
                            break;
                        }
                    }
                }
                $auszug = str_ireplace(
                    stripslashes($text),
                    '<strong>' . stripslashes($text) . '</strong></strong></b></code></small></pre></li></ul></blockquote>',
                    $res_message[ $key ]
                );
                if (mb_strlen($res_title[ $key ]) > 50) {
                    $title = mb_substr($res_title[ $key ], 0, 50);
                    $title .= '...</strong></b></code></small></pre></li></ul></blockquote>';
                } else {
                    $title = $res_title[ $key ];
                }
                $link = $res_link[ $key ];
                $frequency = $res_occurr[ $key ];

                if ($date == '01.01.1970') {
                    $date = $plugin_language[ 'n_a' ];
                }

                $data_array = array();
                $data_array['$type'] = $type;
                $data_array['$title'] = $title;
                $data_array['$date'] = $date;
                $data_array['$frequency'] = $frequency;
                $data_array['$auszug'] = $auszug;
                $data_array['$link'] = $link;

                $data_array['$lang_frequency'] = $plugin_language[ 'frequency' ];

                $template = $GLOBALS["_template"]->loadTemplate("search","result", $data_array, $plugin_path);
                echo $template;
                

                $i++;
            }
            
        } else {

            # min. Länge der Suche:#
            $search_min_len = $ds[ 'search_min_len' ];
            if (empty($search_min_len)) {
                $search_min_len = '4';
            }
            # min. Länge der Suche: END#
            echo str_replace("%min_chars%", $search_min_len, $plugin_language[ 'too_short' ]);
        }
    } else {
        echo $plugin_language[ 'wrong_securitycode' ];
    }
} else {
    if (!isset($_REQUEST[ 'site' ])) {
        header("Location: index.php?site=search");
    }
    $_language->readModule('search');

    if (isset($_REQUEST[ 'text' ])) {
        $text = getinput($_REQUEST[ 'text' ]);
    } else {
        $text = '';
    }

    $data_array = array();
    $data_array['$title'] = $plugin_language[ 'search' ];
    $data_array['$subtitle']='Search';
    $template = $GLOBALS["_template"]->loadTemplate("search","head", $data_array, $plugin_path);
    echo $template;
    
    if ($userID) {



$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='faq' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'faq') {
        $faq = '';
    } else {
        $faq = '<div class="form-check"><input class="form-check-input" type="checkbox" name="faq" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'faq' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='wiki' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'wiki') {
        $wiki = '';
    } else {
        $wiki = '<div class="form-check"><input class="form-check-input" type="checkbox" name="wiki" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'wiki' ].'</label></div>';
    }    
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='news_manager' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'news_manager') {
        $news = '';
    } else {
        $news = '<div class="form-check"><input class="form-check-input" type="checkbox" name="news" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'news' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='forum' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'forum') {
        $forum = '';
    } else {
        $forum = '<div class="form-check"><input class="form-check-input" type="checkbox" name="forum" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'forum' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='files' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'files') {
        $files = '';
    } else {
        $files = '<div class="form-check"><input class="form-check-input" type="checkbox" name="files" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'files' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='articles' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'articles') {
        $articles = '';
    } else {
        $articles = '<div class="form-check"><input class="form-check-input" type="checkbox" name="articles" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'articles' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='about_us' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'about_us') {
        $about_us = '';
    } else {
        $about_us = '<div class="form-check"><input class="form-check-input" type="checkbox" name="about_us" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'about_us' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='blog' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'blog') {
        $blog = '';
    } else {
        $blog = '<div class="form-check"><input class="form-check-input" type="checkbox" name="blog" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'blog' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='history' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'history') {
        $history = '';
    } else {
        $history = '<div class="form-check"><input class="form-check-input" type="checkbox" name="history" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'history' ].'</label></div>';
    } 
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='partners' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'partners') {
        $partners = '';
    } else {
        $partners = '<div class="form-check"><input class="form-check-input" type="checkbox" name="partners" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'partners' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='todo' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'todo') {
        $todo = '';
    } else {
        $todo = '<div class="form-check"><input class="form-check-input" type="checkbox" name="todo" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'todo' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='sponsors' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'sponsors') {
        $sponsors = '';
    } else {
        $sponsors = '<div class="form-check"><input class="form-check-input" type="checkbox" name="sponsors" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'sponsors' ].'</label></div>';
    }

$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='planning' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'planning') {
        $planning = '';
    } else {
        $planning = '<div class="form-check"><input class="form-check-input" type="checkbox" name="planning" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'planning' ].'</label></div>';
    }    
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='links' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'links') {
        $links = '';
    } else {
        $links = '<div class="form-check"><input class="form-check-input" type="checkbox" name="links" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'links' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='gallery' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'gallery') {
        $gallery = '';
    } else {
        $gallery = '<div class="form-check"><input class="form-check-input" type="checkbox" name="gallery" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'gallery' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='clan_rules' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'clan_rules') {
        $clan_rules = '';
    } else {
        $clan_rules = '<div class="form-check"><input class="form-check-input" type="checkbox" name="clan_rules" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'clan_rules' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='server_rules' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'server_rules') {
        $server_rules = '';
    } else {
        $server_rules = '<div class="form-check"><input class="form-check-input" type="checkbox" name="server_rules" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'server_rules' ].'</label></div>';
    } 
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='servers' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'servers') {
        $servers = '';
    } else {
        $servers = '<div class="form-check"><input class="form-check-input" type="checkbox" name="servers" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'servers' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='calendar' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'calendar') {
        $events = '';
    } else {
        $events = '<div class="form-check"><input class="form-check-input" type="checkbox" name="events" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'events' ].'</label></div>';
    }          
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='projectlist' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'projectlist') {
        $projectlist = '';
    } else {
        $projectlist = '<div class="form-check"><input class="form-check-input" type="checkbox" name="projectlist" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'projectlist' ].'</label></div>';
    } 

        $data_array = array();
        $data_array['$text'] = $text;
        $data_array['$faq'] = $faq;
        $data_array['$wiki'] = $wiki;
        $data_array['$news'] = $news;
        $data_array['$forum'] = $forum;
        $data_array['$files'] = $files;
        $data_array['$articles'] = $articles;
        $data_array['$about_us'] = $about_us;
        $data_array['$blog'] = $blog;
        $data_array['$history'] = $history;
        $data_array['$partners'] = $partners;
        $data_array['$todo'] = $todo;
        $data_array['$sponsors'] = $sponsors;
        $data_array['$planning'] = $planning;
        $data_array['$links'] = $links;
        $data_array['$gallery'] = $gallery;
        $data_array['$clan_rules'] = $clan_rules;
        $data_array['$server_rules'] = $server_rules;
        $data_array['$servers'] = $servers;
        $data_array['$events'] = $events;
        $data_array['$links'] = $links;
        $data_array['$projectlist'] = $projectlist;
        
        $data_array['$text_contains'] = $plugin_language[ 'text_contains' ];
        $data_array['$start_search'] = $plugin_language[ 'start_search' ];
        $data_array['$search_in'] = $plugin_language[ 'search_in' ];
        $data_array['$static'] = $plugin_language[ 'static' ];
        $data_array['$result_quantity'] = $plugin_language[ 'result_quantity' ];
        $data_array['$sort_options'] = $plugin_language[ 'sort_options' ];
        $data_array['$descending'] = $plugin_language[ 'descending' ];
        $data_array['$ascending'] = $plugin_language[ 'ascending' ];
        $data_array['$date_options'] = $plugin_language[ 'date_options' ];
        $data_array['$after'] = $plugin_language[ 'after' ];
        $data_array['$before'] = $plugin_language[ 'before' ];
        $data_array['$options'] = $plugin_language[ 'options' ];
        $data_array['$user'] = $plugin_language[ 'user' ];
        $data_array['$squads'] = $plugin_language[ 'squads' ];

        $template = $GLOBALS["_template"]->loadTemplate("search","form_loggedin", $data_array, $plugin_path);
        echo $template;
        
    } else {

        $CAPCLASS = new \webspell\Captcha;
        $captcha = $CAPCLASS->createCaptcha();
        $hash = $CAPCLASS->getHash();
        $CAPCLASS->clearOldCaptcha();
        
        if($recaptcha=="1") { 
        $_captcha = '<div class="g-recaptcha" style="width: 70%; float: left;" data-sitekey="'.$webkey.'"></div>'; 
                    } else { 
        $_captcha = '<span class="input-group-addon captcha-img">'.$captcha.'</span>
                        <input type="number" name="captcha" class="form-control" id="input-security-code">
                        <input name="captcha_hash" type="hidden" value="'.$hash.'">';
    }

$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='faq'"));
if (@$dx[ 'modulname' ] != 'faq') {
        $faq = '';
    } else {
        $faq = '<div class="form-check"><input class="form-check-input" type="checkbox" name="faq" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'faq' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='wiki'"));
if (@$dx[ 'modulname' ] != 'wiki') {
        $wiki = '';
    } else {
        $wiki = '<div class="form-check"><input class="form-check-input" type="checkbox" name="wiki" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'wiki' ].'</label></div>';
    }    
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='news_manager'"));
if (@$dx[ 'modulname' ] != 'news_manager') {
        $news = '';
    } else {
        $news = '<div class="form-check"><input class="form-check-input" type="checkbox" name="news" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'news' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='forum'"));
if (@$dx[ 'modulname' ] != 'forum') {
        $forum = '';
    } else {
        $forum = '<div class="form-check"><input class="form-check-input" type="checkbox" name="forum" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'forum' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='files'"));
if (@$dx[ 'modulname' ] != 'files') {
        $files = '';
    } else {
        $files = '<div class="form-check"><input class="form-check-input" type="checkbox" name="files" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'files' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='articles'"));
if (@$dx[ 'modulname' ] != 'articles') {
        $articles = '';
    } else {
        $articles = '<div class="form-check"><input class="form-check-input" type="checkbox" name="articles" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'articles' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='about_us'"));
if (@$dx[ 'modulname' ] != 'about_us') {
        $about_us = '';
    } else {
        $about_us = '<div class="form-check"><input class="form-check-input" type="checkbox" name="about_us" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'about_us' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='blog'"));
if (@$dx[ 'modulname' ] != 'blog') {
        $blog = '';
    } else {
        $blog = '<div class="form-check"><input class="form-check-input" type="checkbox" name="blog" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'blog' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='history'"));
if (@$dx[ 'modulname' ] != 'history') {
        $history = '';
    } else {
        $history = '<div class="form-check"><input class="form-check-input" type="checkbox" name="history" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'history' ].'</label></div>';
    } 
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='partners'"));
if (@$dx[ 'modulname' ] != 'partners') {
        $partners = '';
    } else {
        $partners = '<div class="form-check"><input class="form-check-input" type="checkbox" name="partners" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'partners' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='todo'"));
if (@$dx[ 'modulname' ] != 'todo') {
        $todo = '';
    } else {
        $todo = '<div class="form-check"><input class="form-check-input" type="checkbox" name="todo" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'todo' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='sponsors'"));
if (@$dx[ 'modulname' ] != 'sponsors') {
        $sponsors = '';
    } else {
        $sponsors = '<div class="form-check"><input class="form-check-input" type="checkbox" name="sponsors" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'sponsors' ].'</label></div>';
    }

$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='planning'"));
if (@$dx[ 'modulname' ] != 'planning') {
        $planning = '';
    } else {
        $planning = '<div class="form-check"><input class="form-check-input" type="checkbox" name="planning" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'planning' ].'</label></div>';
    }    
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='links'"));
if (@$dx[ 'modulname' ] != 'links') {
        $links = '';
    } else {
        $links = '<div class="form-check"><input class="form-check-input" type="checkbox" name="links" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'links' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='gallery'"));
if (@$dx[ 'modulname' ] != 'gallery') {
        $gallery = '';
    } else {
        $gallery = '<div class="form-check"><input class="form-check-input" type="checkbox" name="gallery" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'gallery' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='clan_rules'"));
if (@$dx[ 'modulname' ] != 'clan_rules') {
        $clan_rules = '';
    } else {
        $clan_rules = '<div class="form-check"><input class="form-check-input" type="checkbox" name="clan_rules" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'clan_rules' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='server_rules'"));
if (@$dx[ 'modulname' ] != 'server_rules') {
        $server_rules = '';
    } else {
        $server_rules = '<div class="form-check"><input class="form-check-input" type="checkbox" name="server_rules" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'server_rules' ].'</label></div>';
    } 
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='servers'"));
if (@$dx[ 'modulname' ] != 'servers') {
        $servers = '';
    } else {
        $servers = '<div class="form-check"><input class="form-check-input" type="checkbox" name="servers" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'servers' ].'</label></div>';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='calendar'"));
if (@$dx[ 'modulname' ] != 'calendar') {
        $events = '';
    } else {
        $events = '<div class="form-check"><input class="form-check-input" type="checkbox" name="events" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'events' ].'</label></div>';
    }             
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='projectlist'"));
if (@$dx[ 'modulname' ] != 'projectlist') {
        $projectlist = '';
    } else {
        $projectlist = '<div class="form-check"><input class="form-check-input" type="checkbox" name="projectlist" value="true" checked><label class="form-check-label" for="gridRadios1"> '.$plugin_language[ 'projectlist' ].'</label></div>';
    }  

        $data_array = array();
        $data_array['$text'] = $text;
        $data_array['$faq'] = $faq;
        $data_array['$wiki'] = $wiki;
        $data_array['$news'] = $news;
        $data_array['$forum'] = $forum;
        $data_array['$files'] = $files;
        $data_array['$articles'] = $articles;
        $data_array['$about_us'] = $about_us;
        $data_array['$blog'] = $blog;
        $data_array['$history'] = $history;
        $data_array['$partners'] = $partners;
        $data_array['$todo'] = $todo;
        $data_array['$sponsors'] = $sponsors;
        $data_array['$planning'] = $planning;
        $data_array['$links'] = $links;
        $data_array['$gallery'] = $gallery;
        $data_array['$clan_rules'] = $clan_rules;
        $data_array['$server_rules'] = $server_rules;
        $data_array['$servers'] = $servers;
        $data_array['$events'] = $events;
        $data_array['$links'] = $links;
        $data_array['$projectlist'] = $projectlist;

        $data_array['$_captcha'] = $_captcha;
        $data_array['$hash'] = $hash;
        
        $data_array['$text_contains'] = $plugin_language[ 'text_contains' ];
        $data_array['$start_search'] = $plugin_language[ 'start_search' ];
        $data_array['$search_in'] = $plugin_language[ 'search_in' ];
        $data_array['$static'] = $plugin_language[ 'static' ];
        $data_array['$result_quantity'] = $plugin_language[ 'result_quantity' ];
        $data_array['$sort_options'] = $plugin_language[ 'sort_options' ];
        $data_array['$descending'] = $plugin_language[ 'descending' ];
        $data_array['$ascending'] = $plugin_language[ 'ascending' ];
        $data_array['$date_options'] = $plugin_language[ 'date_options' ];
        $data_array['$after'] = $plugin_language[ 'after' ];
        $data_array['$before'] = $plugin_language[ 'before' ];
        $data_array['$options'] = $plugin_language[ 'options' ];
        $data_array['$lang_security_code']=$plugin_language['security_code'];
        $data_array['$user'] = $plugin_language[ 'user' ];
        $data_array['$squads'] = $plugin_language[ 'squads' ];

        $template = $GLOBALS["_template"]->loadTemplate("search","form_notloggedin", $data_array, $plugin_path);
        echo $template;
        
    }
}
