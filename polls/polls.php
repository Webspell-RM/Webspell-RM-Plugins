<?php
/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2015 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#                                                                        #
##########################################################################
*/
# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("polls", $plugin_path);

// -- NEWS INFORMATION -- //
include_once("polls_functions.php");    

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = "";
}

if (isset($pollID)) {
    unset($pollID);
    }
    if (isset($_GET[ 'pollID' ])) {
    $pollID = $_GET[ 'pollID' ];
    }


if ($action == "vote") {
    
    if (isset($_POST[ 'pollID' ]) && isset($_POST[ 'vote' ])) {
        $pollID = (int)$_POST[ 'pollID' ];
        $vote = (int)$_POST[ 'vote' ];
        $_language->readModule('polls');

        $ds = mysqli_fetch_array(
            safe_query(
                "SELECT
                    `userIDs`,
                    `hosts`
                FROM
                    `" . PREFIX . "plugins_polls`
                WHERE
                    `pollID` = '" . (int)$pollID."'"
            )
        );
        $anz = mysqli_num_rows(
            safe_query(
                "SELECT
                    `pollID`
                FROM
                    `" . PREFIX . "plugins_polls`
                WHERE
                    pollID = '" . $pollID . "' AND
                    hosts LIKE '%" . $_SERVER[ 'REMOTE_ADDR' ] . "%' AND
                    intern<=" . (int)isclanmember($userID)
            )
        );

        $anz_user = false;
        if ($userID) {
            if ($ds[ 'userIDs' ]) {
                $user_ids = explode(";", $ds[ 'userIDs' ]);
                if (in_array($userID, $user_ids)) {
                    $anz_user = true;
                }
            } else {
                $user_ids = array();
            }
        }

        $cookie = false;
        if (isset($_COOKIE[ 'poll' ]) && is_array($_COOKIE[ 'poll' ])) {
            $cookie = in_array($pollID, $_COOKIE[ 'poll' ]);
        }
        if (!$cookie && !$anz && !$anz_user && isset($_POST[ 'vote' ])) {
            //write cookie
            $index = count(array($_COOKIE[ 'poll' ]));
            setcookie("poll[" . $index . "]", $pollID, time() + (3600 * 24 * 365));

            //write ip and userID if logged
            $add_query = "";
            if ($userID) {
                $user_ids[ ] = $userID;
                $add_query = ", userIDs='" . implode(";", $user_ids) . "'";
            }

            safe_query(
                "UPDATE
                    " . PREFIX . "plugins_polls
                SET
                    hosts='" . $ds[ 'hosts' ] . "#" . $_SERVER[ 'REMOTE_ADDR' ] . "#'" . $add_query . "
                WHERE
                    pollID='" . (int)$pollID."'"
            );

            //write vote
            safe_query(
                "UPDATE
                    " . PREFIX . "plugins_polls_votes
                SET
                    o" . $vote . " = o" . $vote . "+1
                WHERE
                    pollID='" . (int)$pollID."'"
            );
        }
        header('Location: index.php?site=polls');
    } else {
        header('Location: index.php?site=polls');
    }

}elseif (isset($_GET[ 'pollID' ])) {
    $pollID = (int)$_GET['pollID'];
    $query = safe_query("SELECT * FROM ".PREFIX."plugins_polls WHERE pollID='".$pollID."'");

    $data_array = array();
    $data_array['$title']=$plugin_language['polls'];
    $data_array['$subtitle']='Polls';
    $template = $GLOBALS["_template"]->loadTemplate("polls","title", $data_array, $plugin_path);
    echo $template;

    $pollID = intval($_GET[ 'pollID' ]);
    
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_polls WHERE pollID='$pollID' AND intern<=" . (int)isclanmember($userID));
    $ds = mysqli_fetch_array($ergebnis);
    $translate = new multiLanguage(detectCurrentLanguage());
    $title = $ds[ 'titel' ];

    if ($ds[ 'intern' ] == 1) {
        $isintern = '(' . $plugin_language[ 'intern' ] . ')';
    } else {
        $isintern = '';
    }

    if ($ds[ 'laufzeit' ] < time() || $ds[ 'aktiv' ] == "0") {
        $timeleft = $plugin_language[ 'poll_ended' ];
        $active = '';
    } else {
        $timeleft = floor(($ds[ 'laufzeit' ] - time()) / (60 * 60 * 24)) . " " . $plugin_language[ 'days' ] . " (" . date("d.m.Y H:i", $ds[ 'laufzeit' ]) . ")<br>
                        <a href='index.php?site=polls&amp;vote=" . $ds[ 'pollID' ] . "' class='btn btn-primary'>" . $plugin_language[ 'vote_now' ] . "</a>";
        $active = 'active';
    }

    $options = array();
    for ($n = 1; $n <= 10; $n++) {
        if ($ds[ 'o' . $n ]) {
            $options[ ] = $ds[ 'o' . $n ];
        }
    }

    $votes = safe_query("SELECT * FROM " . PREFIX . "plugins_polls_votes WHERE pollID='" . $pollID . "'");
    $dv = mysqli_fetch_array($votes);
    $gesamtstimmen =
        $dv[ 'o1' ] + $dv[ 'o2' ] + $dv[ 'o3' ] + $dv[ 'o4' ] + $dv[ 'o5' ] + $dv[ 'o6' ] + $dv[ 'o7' ] + $dv[ 'o8' ] +
        $dv[ 'o9' ] + $dv[ 'o10' ];
    $n = 1;

    $translate->detectLanguages($title);
    $title = $translate->getTextByLanguage($title);

    $data_array = array();
    $data_array['$title'] = $title;
    $data_array['$isintern'] = $isintern;
    $data_array['$gesamtstimmen'] = $gesamtstimmen;
    $data_array['$timeleft'] = $timeleft;
    
    $data_array['$votes']=$plugin_language['votes'];
    $data_array['$time_left']=$plugin_language['time_left'];
    $template = $GLOBALS["_template"]->loadTemplate("polls","head", $data_array, $plugin_path);
    echo $template;



    #$comments = "";
    foreach ($options as $option) {
        $stimmen = $dv[ 'o' . $n ];
        if ($gesamtstimmen) {
            $perc = $stimmen / $gesamtstimmen * 10000;
            settype($perc, "integer");
            $perc = $perc / 100;
        } else {
            $perc = 0;
        }
        $picwidth = $perc;
        settype($picwidth, "integer");

        if ($picwidth) {
            $pic = '<div class="progress">
            <div
                class="progress-bar"
                role="progressbar"
                aria-valuenow="' . $picwidth . '"
                aria-valuemin="0"
                aria-valuemax="100"
                style="width: ' . $picwidth . '%"
            >
                ' . $picwidth . ' %
            </div>
        </div>';
        } else {
            $pic = '<div class="progress">
            <div
                class="progress-bar"
                role="progressbar"
                aria-valuenow="0"
                aria-valuemin="0"
                aria-valuemax="100"
                style="width: 0"
            >
                0 %
            </div>
        </div>';
        }

        $translate->detectLanguages($option);
        $option = $translate->getTextByLanguage($option);

        $data_array = array();
        $data_array['$pollID'] = $pollID;
        $data_array['$option'] = $option;
        $data_array['$perc'] = $perc;
        $data_array['$stimmen'] = $stimmen;
        $data_array['$votes']=$plugin_language['votes'];

        $template = $GLOBALS["_template"]->loadTemplate("polls","content", $data_array, $plugin_path);
        echo $template;
        $n++;
    }

    $data_array = array();
    
    $template = $GLOBALS["_template"]->loadTemplate("polls","foot", $data_array, $plugin_path);
    echo $template;

    $comments_allowed = $ds[ 'comments' ];
        if ($_GET['pollID']) {
            $parentID = $_GET['pollID'];
            $type = "po";
        }

    $referer = "index.php?site=polls&pollID=".(int)$_GET['pollID']."";

    include("polls_comments.php");



} elseif (isset($_GET[ 'vote' ])) {
    
    $poll = intval($_GET[ 'vote' ]);

    $lastpoll = safe_query(
        "SELECT
            *
        FROM
            " . PREFIX . "plugins_polls
        WHERE
            aktiv='1' AND
            laufzeit>" . time() . " AND
            intern<=" . (int)isclanmember($userID) . " AND
            pollID='" . $poll . "'
        LIMIT 0,1"
    );

    $anz = mysqli_num_rows($lastpoll);
    $ds = mysqli_fetch_array($lastpoll);
    if ($anz) {
        $translate = new multiLanguage(detectCurrentLanguage()); 

    $translate->detectLanguages($ds[ 'titel' ]);
    $title = $translate->getTextByLanguage($ds[ 'titel' ]);




        $anz = mysqli_num_rows(
            safe_query(
                "SELECT
                    pollID
                FROM
                    `" . PREFIX . "plugins_polls`
                WHERE
                    pollID='" . $ds[ 'pollID' ] . "' AND
                    hosts
                    LIKE
                        '%" . $_SERVER[ 'REMOTE_ADDR' ] . "%' AND
                        intern<=" . (int)isclanmember($userID)
            )
        );

        $anz_user = false;
        if ($userID) {
            $user_ids = explode(";", $ds[ 'userIDs' ]);
            if (in_array($userID, $user_ids)) {
                $anz_user = true;
            }
        }
        $cookie = false;
        if (isset($_COOKIE[ 'poll' ]) && is_array($_COOKIE[ 'poll' ])) {
            $cookie = in_array($ds[ 'pollID' ], $_COOKIE[ 'poll' ]);
        }

        if ($cookie || $anz || $anz_user) {
            redirect('index.php?site=polls&amp;pollID=' . $ds[ 'pollID' ], $plugin_language[ 'already_voted' ], 3);
        } else {
            $data_array = array();
            $data_array['$title']=$plugin_language['polls'];
            $data_array['$subtitle']='Polls';
            $template = $GLOBALS["_template"]->loadTemplate("polls","title", $data_array, $plugin_path);
            echo $template;

            
            echo '<div class="card">
    <div class="card-body">
    <form method="post" action="index.php?site=polls&amp;action=vote">
            
         
                <h4>' . $title . '</h4>
          <ul class="list-group list-group-flush">';
            $options = array();
            for ($n = 1; $n <= 10; $n++) {
                if ($ds[ 'o' . $n ]) {
                    $options[ ] = $ds[ 'o' . $n ];
                }
            }
            $n = 1;

            foreach ($options as $option) {
            
            $translate->detectLanguages($option);
            $option = $translate->getTextByLanguage($option);
                echo '<li class="list-group-item">
                            <div class="form-check">  
                               <input class="form-check-input" type="radio" name="vote" value="' . $n . '">  
                               <label class="form-check-label" for="flexRadioDefault1">' . $option . '</label>
                            </div>      
                        </li>';
                $n++;
            }
            echo '</ul><input type="hidden" name="pollID" value="' . $ds[ 'pollID' ] . '">
                  <input  class="btn btn-primary" style="margin-top:15px" type="submit" value="' . $plugin_language[ 'vote' ] . '"></td>
                  <a class="btn btn-primary" style="margin-top:15px" href="index.php?site=polls">' . $plugin_language[ 'show_polls' ] . '</a>
        </form>
        </div></div>';
        }
    } else {
        redirect('index.php?site=polls&pollID=' . $ds[ 'pollID' ], $plugin_language[ 'poll_ended' ], 3);
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
                `" . PREFIX . "plugins_polls_comments`
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
            $dt = safe_query("SELECT * FROM " . PREFIX . "plugins_polls_comments WHERE commentID='" . (int)$id."'");
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

    
    $data_array = array();
    $data_array['$title']=$plugin_language['polls'];
    $data_array['$subtitle']='Polls';
    $template = $GLOBALS["_template"]->loadTemplate("polls","title", $data_array, $plugin_path);
    echo $template;

    
    
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    
    $ergebnis =
        safe_query(
            "SELECT
                *
            FROM
                " . PREFIX . "plugins_polls
            WHERE
                intern<=" . (int)isclanmember($userID) . "
                AND published = '1'
            ORDER BY
                pollID DESC"
        );

    $anz = mysqli_num_rows($ergebnis);
    if ($anz) {

    $data_array = array();
    $data_array['$endingtime']=$plugin_language['endingtime'];
    $data_array['$title']=$plugin_language['title'];
    $data_array['$votes']=$plugin_language['votes'];
    
    $template = $GLOBALS["_template"]->loadTemplate("polls","all_head", $data_array, $plugin_path);
    echo $template;
        $i = 1;
        while ($ds = mysqli_fetch_array($ergebnis)) {

            $laufzeit = getformatdatetime($ds[ 'laufzeit' ]);
            if ($ds[ 'intern' ] == 1) {
                $isintern = '(' . $plugin_language[ 'intern' ] . ')';
            } else {
                $isintern = '';
            }
            if ($ds[ 'laufzeit' ] < time() || $ds[ 'aktiv' ] == "0") {
                $timeleft = '<div class="alert alert-danger" role="alert">'.$plugin_language['poll_ended'].'</div>';
                $active = '';
            } else {
                $timeleft = '<div class="alert alert-success" role="alert">'.$plugin_language['poll_end'].' '.$laufzeit.'</div>';
                    floor(($ds[ 'laufzeit' ] - time()) / (60 * 60 * 24)) . " " . $plugin_language[ 'days' ] . " (" .
                    date("d.m.Y H:i", $ds[ 'laufzeit' ]) . ")<br>
                        <a href='index.php?site=polls&amp;vote=" . $ds[ 'pollID' ] . "' class='btn btn-primary'>" .
                    $plugin_language[ 'vote_now' ] . "</a></div>";
                $active = 'active';
            }
            $options = array();
            for ($n = 1; $n <= 10; $n++) {
                if ($ds[ 'o' . $n ]) {
                    $options[ ] = $ds[ 'o' . $n ];
                }
            }



            
            $votes = safe_query("SELECT * FROM " . PREFIX . "plugins_polls_votes WHERE pollID='" . $ds[ 'pollID' ] . "'");
            $dv = mysqli_fetch_array($votes);
            $gesamtstimmen = $dv[ 'o1' ] + $dv[ 'o2' ] + $dv[ 'o3' ] + $dv[ 'o4' ] + $dv[ 'o5' ] + $dv[ 'o6' ] + $dv[ 'o7' ] + $dv[ 'o8' ] + $dv[ 'o9' ] + $dv[ 'o10' ];
            
            if ($ds[ 'aktiv' ]) {
                $actions = '<div class="alert alert-success" role="alert">'.$plugin_language['poll_end'].'<br>'.$laufzeit.'</div>';
                
            } else {
                $actions = '<div class="alert alert-danger" role="alert">'.$plugin_language['poll_ended'].'</div>';
                
            }
            
            
            $pollID = $ds[ 'pollID' ];
            $title = $ds[ 'titel' ];
            $description = $ds[ 'description' ];
            
            $translate = new multiLanguage(detectCurrentLanguage());    
            $translate->detectLanguages($title);
            $title = $translate->getTextByLanguage($title);
   
            
            $data_array = array();
            $data_array[ '$actions' ] = $actions;
            $data_array[ '$timeleft' ] = $timeleft;
            $data_array[ '$laufzeit' ] = $laufzeit;
            $data_array[ '$isintern' ] = $isintern;
            $data_array[ '$gesamtstimmen' ] = $gesamtstimmen;
            $data_array[ '$pollID' ] = $pollID;
            $data_array[ '$title' ] = $title;
            $data_array[ '$description' ] = $description;
            $data_array[ '$result' ]=$plugin_language['result'];
            

            $template = $GLOBALS["_template"]->loadTemplate("polls","all_content", $data_array, $plugin_path);
            echo $template;
            $i++;
        }
        $template = $GLOBALS["_template"]->loadTemplate("polls","all_foot", $data_array, $plugin_path);
    echo $template;
    } else {
        $data_array = array();
        $data_array['$no_entries']=$plugin_language['no_entries'];

        $template = $GLOBALS["_template"]->loadTemplate("polls","all_content-no", $data_array, $plugin_path);
        echo $template;
    }
    

 
}
