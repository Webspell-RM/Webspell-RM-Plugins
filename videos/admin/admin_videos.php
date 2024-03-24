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
$plugin_language = $pm->plugin_language("admin_videos", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='videos'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

# Videos

if (isset($_POST[ 'save' ])) {
    if (!ispageadmin($userID) || !isnewsadmin($userID)) {
        echo generateAlert($plugin_language['no_access'], 'alert-danger');
    } else {
   		if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    	} else {
        $displayed = 0;
    	}
    	if (!$displayed) {
        $displayed = 0;
    	}

    	if (isset($_POST[ "widget_displayed" ])) {
        $widget_displayed = 1;
    	} else {
        $widget_displayed = 0;
    	}
    	if (!$widget_displayed) {
        $widget_displayed = 0;
    	}

    	if (isset($_POST[ "media_widget_displayed" ])) {
        $media_widget_displayed = 1;
    	} else {
        $media_widget_displayed = 0;
    	}
    	if (!$media_widget_displayed) {
        $media_widget_displayed = 0;
    	}
        safe_query(
            "INSERT INTO
                " . PREFIX . "plugins_videos (
                    videoscatID,
                    date,
                    videoname,
                    description,
                    uploader,
                    youtube,
                    comments,
                    displayed,
                    widget_displayed,
                    media_widget_displayed
                )
            values (
                '" . (int)$_POST[ 'cat' ] . "',
                '" . time() . "',
                '" . strip_tags($_POST[ 'videoname' ]) . "',
                '" . $_POST[ 'description' ] . "',
                '" . $_POST[ 'uploader' ] . "',
                '" . $_POST[ 'youtube' ] . "',
                '" . $_POST[ 'comments' ] . "',
                '" . $displayed . "',
                '" . $widget_displayed . "',
                '" . $media_widget_displayed . "'
            ) "
        );

    }
} elseif (isset($_POST[ 'saveedit' ])) {
    if (!ispageadmin($userID) || !isnewsadmin($userID)) {
        echo generateAlert($plugin_language['no_access'], 'alert-danger');
    } else {

    	if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    	} else {
        $displayed = 0;
    	}
    	if (!$displayed) {
        $displayed = 0;
    	}

    	if (isset($_POST[ "widget_displayed" ])) {
        $widget_displayed = 1;
    	} else {
        $widget_displayed = 0;
    	}
    	if (!$widget_displayed) {
        $widget_displayed = 0;
    	}

    	if (isset($_POST[ "media_widget_displayed" ])) {
        $media_widget_displayed = 1;
    	} else {
        $media_widget_displayed = 0;
    	}
    	if (!$media_widget_displayed) {
        $media_widget_displayed = 0;
    	}
    
        safe_query(
            "UPDATE
                " . PREFIX . "plugins_videos
            SET
                videoscatID='" . $_POST[ 'cat' ] . "',
                videoname='" . strip_tags($_POST[ 'videoname' ]) . "',
                description='" . $_POST[ 'description' ] . "',
                uploader='" . $_POST[ 'uploader' ] . "',
                youtube='" . $_POST[ 'youtube' ] . "',
                comments='" . $_POST[ 'comments' ] . "',
                displayed='" . $displayed . "',
                widget_displayed='" . $widget_displayed . "',
                media_widget_displayed='" . $media_widget_displayed . "'
            WHERE
                videosID='" . $_POST[ 'videosID' ] . "'"
        );

    }
}elseif (isset($_GET[ "delete" ])) {   
    
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_videos WHERE videosID='" . $_GET[ "videosID" ] . "'");
        $data = mysqli_fetch_assoc($get);
 
        if (safe_query("DELETE FROM " . PREFIX . "plugins_videos WHERE videosID='" . $_GET[ "videosID" ] . "'")) {
            @unlink($filepath.$data['banner']);
            redirect("admincenter.php?site=admin_videos", "", 0);
        } else {
            redirect("admincenter.php?site=admin_videos", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }


# Videos Categories

}elseif (isset($_POST[ 'videos_categories_save' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('catname'))) {
            safe_query("INSERT INTO " . PREFIX . "plugins_videos_categories ( catname ) values( '" . $_POST[ 'catname' ] . "' ) ");
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'videos_categories_saveedit' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('catname'))) {
            safe_query(
                "UPDATE " . PREFIX . "plugins_videos_categories SET catname='" . $_POST[ 'catname' ] . "' WHERE videoscatID='" .
                $_POST[ 'videoscatID' ] . "'"
            );
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_GET[ 'videos_categories_delete' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        safe_query("DELETE FROM " . PREFIX . "plugins_videos_categories WHERE videoscatID='" . $_GET[ 'videoscatID' ] . "'");
        safe_query("DELETE FROM " . PREFIX . "plugins_videos WHERE videoscatID='" . $_GET[ 'videoscatID' ] . "'");
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
}

# Videos

if ($action == "admin_videos_add") {
    if (ispageadmin($userID) || isnewsadmin($userID)) {
        $rubrics = safe_query("SELECT * FROM " . PREFIX . "plugins_videos_categories ORDER BY catname");
        $linkcats = '';
        while ($dr = mysqli_fetch_array($rubrics)) {
            $linkcats .= '<option value="' . $dr[ 'videoscatID' ] . '">' . htmlspecialchars($dr[ 'catname' ]) . '</option>';
        }

        $comments = '<option value="0">' . $plugin_language[ 'no_comments' ] . '</option>
        			 <option value="1">' . $plugin_language[ 'user_comments' ] . '</option>
        			 <option value="2" selected="selected">' . $plugin_language[ 'visitor_comments' ] . '</option>';

        
        
        $data_array = array();
        $data_array['$linkcats'] = $linkcats;
        $data_array['$userID'] = $userID;
        $data_array['$comments'] = $comments;
        
        $data_array['$video']=$plugin_language['video'];
        $data_array['$new_video']=$plugin_language['new_video'];
        $data_array['$videorubric']=$plugin_language['videorubric'];
        $data_array['$vidname']=$plugin_language['videoname'];
        $data_array['$videotext']=$plugin_language['videotext'];
        $data_array['$youtubecode']=$plugin_language['youtubecode'];
        $data_array['$save_video']=$plugin_language['save_video'];
        $data_array['$lang_comments']=$plugin_language['comments'];
        $data_array['$lang_displayed']=$plugin_language['displayed'];
        $data_array['$lang_widget_displayed']=$plugin_language['widget_displayed'];
        $data_array['$lang_media_widget_displayed']=$plugin_language['media_widget_displayed'];
        
        
        $template = $GLOBALS["_template"]->loadTemplate("admin_videos","new", $data_array, $plugin_path);
        echo $template;

    } else {
        redirect(
            'admincenter.php?site=admin_videos',
            generateAlert($plugin_language[ 'no_access' ], 'alert-danger')
        );
    }
} elseif ($action == "admin_videos_edit") {
    $videosID = $_GET[ 'videosID' ];
    if (ispageadmin($userID) || isnewsadmin($userID)) {
        $ds = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_videos WHERE videosID='$videosID'"));

        $videoname = htmlspecialchars($ds[ 'videoname' ]);
        $description = htmlspecialchars($ds[ 'description' ]);
        $youtube = htmlspecialchars($ds[ 'youtube' ]);
        
        $newsrubrics = safe_query("SELECT * FROM " . PREFIX . "plugins_videos_categories ORDER BY catname");
        if (mysqli_num_rows($newsrubrics)) {
            $linkcats = '';
            while ($dr = mysqli_fetch_array($newsrubrics)) {
                if ($ds[ 'videoscatID' ] == $dr[ 'videoscatID' ]) {
                    $videoscatID = $dr[ 'videoscatID' ];
                    $linkcats .= '<option value="' . $dr[ 'videoscatID' ] . '" selected>' .
                        htmlspecialchars($dr[ 'catname' ]) . '</option>';
                } else {
                    $linkcats .= '<option value="' . $dr[ 'videoscatID' ] . '">' . htmlspecialchars($dr[ 'catname' ]) .
                        '</option>';
                }
            }
        } else {
            $linkcats = '<option>' . $plugin_language[ 'no_categories' ] . '</option>';
        }

        $linkcats = str_replace(" selected", "", $linkcats);
        $linkcats =
            str_replace(
                'value="' . $ds[ 'videoscatID' ] . '"',
                'value="' . $ds[ 'videoscatID' ] . '" selected',
                $linkcats
            );

        $videoID = $ds['youtube'];

        $preview = 'http://img.youtube.com/vi/'.$videoID.'/hqdefault.jpg';    
        if (!empty($ds[ 'youtube' ])) {
        $pic = '<img src="'.$preview.'" alt="Movie Preview" class="img-fluid" />'; 
    	} else {
        $pic = $plugin_language[ 'no_upload' ];
    	}    
        
        $comments = '<option value="0">' . $plugin_language[ 'no_comments' ] . '</option>
        			 <option value="1">' . $plugin_language[ 'user_comments' ] . '</option>
        			 <option value="2">' . $plugin_language[ 'visitor_comments' ] . '</option>';
        $comments =
        str_replace(
            'value="' . $ds[ 'comments' ] . '"',
            'value="' . $ds[ 'comments' ] . '" selected="selected"',
            $comments
        );

        

        if ($ds[ 'displayed' ] == '1') {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />';
    	} else {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" />';
    	}

    	if ($ds[ 'widget_displayed' ] == '1') {
        $widget_displayed = '<input class="form-check-input" type="checkbox" name="widget_displayed" value="1" checked="checked" />';
    	} else {
        $widget_displayed = '<input class="form-check-input" type="checkbox" name="widget_displayed" value="1" />';
    	}

    	if ($ds[ 'media_widget_displayed' ] == '1') {
        $media_widget_displayed = '<input class="form-check-input" type="checkbox" name="media_widget_displayed" value="1" checked="checked" />';
    	} else {
        $media_widget_displayed = '<input class="form-check-input" type="checkbox" name="media_widget_displayed" value="1" />';
    	}


        $data_array = array();
        $data_array['$linkcats'] = $linkcats;
        $data_array['$videoname'] = $videoname;
        $data_array['$description'] = $description;
        $data_array['$youtube'] = $youtube;
        $data_array['$pic'] = $pic;
        $data_array['$videosID'] = $videosID;
        $data_array['$userID'] = $userID;
        $data_array['$comments'] = $comments;
        $data_array['$displayed'] = $displayed;
        $data_array['$widget_displayed'] = $widget_displayed;
        $data_array['$media_widget_displayed'] = $media_widget_displayed;

        $data_array['$video']=$plugin_language['video'];
        $data_array['$edit_video']=$plugin_language['edit_video'];
        $data_array['$videorubric']=$plugin_language['videorubric'];
        $data_array['$vidname']=$plugin_language['videoname'];
        $data_array['$videotext']=$plugin_language['videotext'];
        $data_array['$videoeffects']=$plugin_language['videoeffects'];
        $data_array['$youtubecode']=$plugin_language['youtubecode'];
        $data_array['$update_video']=$plugin_language['update_video'];
        $data_array['$lang_comments']=$plugin_language['comments'];
        $data_array['$lang_displayed']=$plugin_language['displayed'];
        $data_array['$lang_widget_displayed']=$plugin_language['widget_displayed'];
        $data_array['$lang_media_widget_displayed']=$plugin_language['media_widget_displayed'];

        
        $template = $GLOBALS["_template"]->loadTemplate("admin_videos","edit", $data_array, $plugin_path);
        echo $template;

    } else {
        redirect(
            'admincenter.php?site=admin_videos',
            generateAlert($plugin_language[ 'no_access' ], 'alert-danger')
        );
    }



# Videos Categories

} elseif ($action == "admin_videos_categorys_add") {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-youtube"></i> ' . $plugin_language[ 'video_categories' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_videos">' . $plugin_language[ 'video' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_videos&action=admin_videos_categorys">' . $plugin_language[ 'video_categories' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['add_category'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">';




    echo '<form method="post" action="admincenter.php?site=admin_videos&action=admin_videos_categorys">
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td width="25%"><b>' . $plugin_language[ 'category_name' ] . ':</b></td>
      <td width="75%"><input class="form-control" type="text" name="catname" size="60" /></td>
    </tr>
    <tr>
      <td><input type="hidden" name="captcha_hash" value="' . $hash . '" /></td>
      </tr>
    <tr>
      <td><br><input class="btn btn-success" type="submit" name="videos_categories_save" value="' . $plugin_language[ 'add_category' ] . '" /></td>
    </tr>
  </table>
  </form></div></div>';


} elseif ($action == "admin_videos_categorys_edit") {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-youtube"></i> ' . $plugin_language[ 'video_categories' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_videos">' . $plugin_language[ 'video' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_videos&action=admin_videos_categorys">' . $plugin_language[ 'video_categories' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['edit_category'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">';

    $ergebnis =
        safe_query("SELECT * FROM " . PREFIX . "plugins_videos_categories WHERE videoscatID='" . $_GET[ 'videoscatID' ] . "'");
    $ds = mysqli_fetch_array($ergebnis);

    echo '<form method="post" action="admincenter.php?site=admin_videos&action=admin_videos_categorys">
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td width="25%"><b>' . $plugin_language[ 'category_name' ] . ':</b></td>
      <td width="75%"><input class="form-control" type="text" name="catname" value="' . getinput($ds[ 'catname' ]) . '" size="60" /></td>
    </tr>
    <tr>
      <td><input type="hidden" name="captcha_hash" value="' . $hash .
        '" /><input type="hidden" name="videoscatID" value="' . $ds[ 'videoscatID' ] . '" /></td>
        </tr>
    <tr>
      <td><br><input class="btn btn-warning" type="submit" name="videos_categories_saveedit" value="' . $plugin_language[ 'edit_category' ] . '" /></td>
    </tr>
  </table>
  </form></div></div>';


} elseif ($action == "admin_videos_categorys") {

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-youtube"></i> ' . $plugin_language[ 'video_categories' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_videos">' . $plugin_language[ 'video' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_videos&action=admin_videos_categorys">' . $plugin_language[ 'video_categories' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="form-group row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_videos&action=admin_videos_categorys_add" class="btn btn-primary">' . $plugin_language[ 'new_category' ] . '</a>
    </div>
  </div>';


$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_videos_categories ORDER BY catname");

    
    echo '<div class="table-responsive">

     <table class="table">
        <thead>
        <tr>

            <th width="60%" class="title"><b>' . $plugin_language[ 'category_name' ] . ':</b></th>
            <th width="20%" class="title" align="center"><b>' . $plugin_language[ 'actions' ] . '</b></th>
           
            
        </tr>
        </thead>
        <tbody>

    ';

    $i = 1;
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    while ($ds = mysqli_fetch_array($ergebnis)) {
        if ($i % 2) {
            $td = 'td1';
        } else {
            $td = 'td2';
        }

    
        echo '<tr>
            <td width="60%" class="' . $td . '">' . getinput($ds[ 'catname' ]) . '</td>
            <td width="40%" class="' . $td . '">
            <a class="btn btn-warning" href="admincenter.php?site=admin_videos&action=admin_videos_categorys_edit&amp;videoscatID=' . $ds[ 'videoscatID' ] .
                '" >' . $plugin_language[ 'edit' ] . '</a>

                
<!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_videos&action=admin_videos_categorys&amp;videos_categories_delete=true&amp;videoscatID=' . $ds[ 'videoscatID' ] .
            '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'video' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_videos_delete'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->


               </td>
        </tr>';

        $i++;
    }
    echo '</tbody></table>
    </div></div>';


} elseif ($action == "") {

    echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-youtube"></i> ' . $plugin_language[ 'video' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_videos">' . $plugin_language[ 'video' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="form-group row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_videos&action=admin_videos_categorys" class="btn btn-primary">' . $plugin_language[ 'new_category' ] . '</a>

      <a href="admincenter.php?site=admin_videos&action=admin_videos_add" class="btn btn-primary">' . $plugin_language[ 'new_video' ] . '</a>
    </div>
  </div>';
  
    echo'<form method="post" action="admincenter.php?site=admin_videos">
  <table class="table table-striped">
    <thead>
      <th><b>' . $plugin_language['categories'] . ' / ' . $plugin_language['videoname'] . '</b></th>
      <th><b>' . $plugin_language['videos'] . '</b></th>
      <th><b>' . $plugin_language['displayed'] . '</b></th>
      <th><b>' . $plugin_language['widget_displayed'] . '</b></th>
      <th><b>' . $plugin_language['media_widget_displayed'] . '</b></th>
      <th><b>' . $plugin_language['actions'] . '</b></th>
      </thead>';

    $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_videos_categories`");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(videoscatID) as cnt FROM `" . PREFIX . "plugins_videos_categories`"));
    $anz = $tmp[ 'cnt' ];

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    while ($ds = mysqli_fetch_array($ergebnis)) {
        echo '<tr>
            <td class="td_head" colspan="6">
                <b>' . $ds[ 'catname' ] . '</b>
            </td>
        </tr>';
        
        $faq = safe_query("SELECT * FROM `" . PREFIX . "plugins_videos` WHERE `videoscatID` = '$ds[videoscatID]'");
        $tmp = mysqli_fetch_assoc(
            safe_query(
                "SELECT count(videosID) as cnt FROM `" . PREFIX . "plugins_videos` WHERE `videoscatID` = '$ds[videoscatID]'"
            )
        );
        $anzfaq = $tmp[ 'cnt' ];

        $i = 1;
        while ($db = mysqli_fetch_array($faq)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }

            $db[ 'displayed' ] == 1 ?
            $displayed = '<font color="green"><b>' . $plugin_language[ 'yes' ] . '</b></font>' :
            $displayed = '<font color="red"><b>' . $plugin_language[ 'no' ] . '</b></font>';

            $db[ 'widget_displayed' ] == 1 ?
            $widget_displayed = '<font color="green"><b>' . $plugin_language[ 'yes' ] . '</b></font>' :
            $widget_displayed = '<font color="red"><b>' . $plugin_language[ 'no' ] . '</b></font>';

            $db[ 'media_widget_displayed' ] == 1 ?
            $media_widget_displayed = '<font color="green"><b>' . $plugin_language[ 'yes' ] . '</b></font>' :
            $media_widget_displayed = '<font color="red"><b>' . $plugin_language[ 'no' ] . '</b></font>';

        $videoID = $db['youtube'];
        $preview = 'http://img.youtube.com/vi/'.$videoID.'/hqdefault.jpg';    

            echo '<tr>
        <td class="' . $td . '"><b>- '.getinput($db['videoname']).'</b></td>
        <td class="' . $td . '"><img style="height: 80px" src='.$preview.' alt="Movie Preview" class="img-fluid" /></td>
        <td class="' . $td . '"><b>'.$displayed.'</b></td>
        <td class="' . $td . '"><b>'.$widget_displayed.'</b></td>
        <td class="' . $td . '"><b>'.$media_widget_displayed.'</b></td>
        <td class="' . $td . '"><a href="admincenter.php?site=admin_videos&action=admin_videos_edit&amp;videosID='.$db['videosID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_videos&amp;delete=true&amp;videosID='.$db['videosID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'video' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->

      
        </td>
        </tr>';
      
      $i++;
        }
    }

    echo'
  </table>
  </form>';

echo '</div></div>';
}
?>