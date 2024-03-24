<style type="text/css">

/*Gallery Portfolio Bildanordnung */
.card-columns-ad .card{
  margin-bottom:.75rem;
}

@media (min-width:576px){
  .card-columns-ad{
  /*-webkit-column-count:4;
  -moz-column-count:4;
  column-count:4;
  -webkit-column-gap:1.25rem;
  -moz-column-gap:1.25rem;
  column-gap:1.25rem;
  orphans:1;widows:1;*/
  }
  .card-columns-ad .card{
    display:inline-block;
  width:100%;
  height: auto;
  }
}

.bildmitbildunterschrift span {
    background-color: silver;
    background-color: hsla(0, 0%, 100%, 0.5);
    position: absolute;
    bottom: 0;
    width: 100%;
    line-height: 2em;
    text-align: center;
    /*border-bottom-left-radius: var(--bs-border-radius);
    border-bottom-right-radius: var(--bs-border-radius);*/
}

.bildmitbildunterschrift img {
    display: block;
    width: 100%;
    
}

img.gallery_pix {
    border-radius: 0px;
}</style><?php
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

# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("admin_gallery", $plugin_path);


$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='gallery'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}

// -- NEWS INFORMATION -- //
include_once("./includes/plugins/gallery/gallery_functions.php");
$galclass = new \webspell\Gallery;

$filepath = $plugin_path."images/thumb/";
$filelargepath = $plugin_path."images/large/";
$filethumbpath = $plugin_path."images/thumb/";

if (isset($_GET[ 'part' ])) {
    $part = $_GET[ 'part' ];
} else {
    $part = '';
}
if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if (isset($_POST[ 'admin_settings_save' ])) {

        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
                    "UPDATE " . PREFIX . "plugins_gallery_settings 
                    SET 
                    maxusergalleries='" . ($_POST[ 'maxusergalleries' ] * 1024 * 1024) . "',
                    groups='" . $_POST[ 'groups' ] . "',
                    publicadmin='" . isset($_POST[ 'publicadmin' ]) . "',
                    usergalleries='" . isset($_POST[ 'usergalleries' ]) . "'

                    WHERE gallerysetID='" . $_POST[ 'gallerysetID' ] . "'"
                );
        
            redirect("admincenter.php?site=admin_gallery&action=admin_gallery_settings", "", 0);
        } else {
            redirect("admincenter.php?site=admin_gallery&action=admin_gallery_settings", $plugin_language[ 'transaction_invalid' ], 3);
        }


} elseif (isset($_POST[ 'admin_settings_number_of_images' ])) {

    $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) { 
        safe_query(
                    "UPDATE " . PREFIX . "plugins_gallery 
                    SET 
                    number_of_images='" . $_POST[ 'number_of_images' ] . "'
                    WHERE galleryID='" . $_POST[ 'galleryID' ] . "'"
                );
        
            redirect("admincenter.php?site=admin_gallery&galleryID=".$_POST[ 'galleryID' ]."", "", 0);
        } else {
            redirect("admincenter.php?site=admin_gallery&galleryID=".$_POST[ 'galleryID' ]."", $plugin_language[ 'transaction_invalid' ], 3);
        } 


} elseif (isset($_POST[ 'admin_settings_images_per_page' ])) {

    $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) { 
        safe_query(
                    "UPDATE " . PREFIX . "plugins_gallery 
                    SET 
                    images_per_page='" . $_POST[ 'images_per_page' ] . "'
                    WHERE galleryID='" . $_POST[ 'galleryID' ] . "'"
                );
        
            redirect("admincenter.php?site=admin_gallery&galleryID=".$_POST[ 'galleryID' ]."", "", 0);
        } else {
            redirect("admincenter.php?site=admin_gallery&galleryID=".$_POST[ 'galleryID' ]."", $plugin_language[ 'transaction_invalid' ], 3);
        }       
/*} elseif (isset($_POST[ 'sortieren' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $sort = $_POST[ 'sort' ];
        if (is_array($sort)) {
            foreach ($sort as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE " . PREFIX . "plugins_gallery_pictures SET sort='$sorter[1]' WHERE picID='$sorter[0]' ");
                redirect("admincenter.php?site=admin_gallery", "", 0);
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
*/
} elseif (isset($_POST[ 'sortieren_groups' ])) {
    if(isset($_POST[ 'sortcat' ])) { 
        $sortcat = $_POST[ 'sortcat' ]; 
    } else { 
        $sortcat="";
    }

    $sortlinks = $_POST[ 'sortlinks' ];

    if (is_array($sortcat) AND !empty($sortcat)) {
        foreach ($sortcat as $sortstring) {
            $sorter = explode("-", $sortstring);
            safe_query("UPDATE " . PREFIX . "plugins_gallery_groups SET sort='$sorter[1]' WHERE groupID='$sorter[0]' ");
        }
    }
    if (is_array($sortlinks)) {
        foreach ($sortlinks as $sortstring) {
            $sorter = explode("-", $sortstring);
            safe_query("UPDATE " . PREFIX . "plugins_gallery SET sort='$sorter[1]' WHERE galleryID='$sorter[0]' ");
        }
    }

} elseif (isset($_POST[ 'saveedit_set' ])) {
    #$dir = './../includes/plugins/gallery/images/';
    $dir = $plugin_path."images/";
    $pictures = array();

        #if(isset($_POST['comment'])) $comment = $_POST['comment'];
        if(isset($_POST['name'])) $name = $_POST['name'];
        if(isset($_POST['pictures'])) $pictures = $_POST['pictures'];
        $comment = $_POST['comment'];        
        $comments = $_POST[ 'comments' ];        
        $picID = $_POST[ 'picID' ];

    $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
            if (checkforempty(array('name'))) {

                if ($_POST[ 'name' ]) {
                                $insertname = $_POST[ 'name' ];
                            } else {
                                $insertname = $picture[ 'name' ];
                            }
                safe_query(
                "UPDATE
                    `" . PREFIX . "plugins_gallery_pictures` 
                    SET 
                    `name` = '" . $insertname . "',
                    `comment` = '" . $comment . "',
                    `comments` = '" . $comments . "'
                    WHERE 
                    `picID` = '" . $picID . "'"
                );
                #######################
                $id = $_POST[ 'picID' ];
 
        $errors = array();
 
        $upload = new \webspell\HttpUpload('picture');
        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
 
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation =  getimagesize($upload->getTempFile());
 
                    if (is_array($imageInformation)) {
                        switch ($imageInformation[ 2 ]) {
                            case 1:
                                $endung = '.gif';
                                break;
                            case 3:
                                $endung = '.png';
                                break;
                            default:
                                $endung = '.jpg';
                                break;
                        }
                        $file = $id.$endung;

                        if ($_POST[ 'name' ]) {
                                $insertname = $_POST[ 'name' ];
                            } else {
                                $insertname = $picture[ 'name' ];
                            }

                        if ($upload->saveAs($filelargepath . $file, true)) {
                                @chmod($file, $new_chmod);
                                safe_query(
                                "UPDATE " . PREFIX . "plugins_gallery_pictures SET name='" . $insertname . "' WHERE picID='" . $_POST[ 'picID' ] . "'"
                            );
                            }    
 
                                                    
                            #@copy($dir . 'large/' . $id . $endung);
                            $galclass->saveThumb($dir . 'large/' . $id . $endung, $dir . 'thumb/' . $id . '.jpg');


                       
                    } else {
                        $errors[] = $plugin_language[ 'broken_image' ];
                    }
                } else {
                    $errors[] = $plugin_language[ 'unsupported_image_type' ];
                }
            } else {
                $errors[] = $upload->translateError();
            }
        }
            ######################
            } else {
                echo $plugin_language[ 'information_incomplete' ];
            }
        } else {
            echo $plugin_language[ 'transaction_invalid' ];
        }


            
} elseif ($action == "edit_pic") {
    $galclass = new \webspell\Gallery;

echo '<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> ' . $plugin_language[ 'gallery' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_gallery" class="white">'.$plugin_language['gallery'].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit' ] . '</li>
                </ol>
            </nav>  
            <div class="card-body">';
 
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_gallery_pictures WHERE picID='" . $_GET[ 'picID' ] . "'"
        )
    );

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();


    if (!empty($ds[ 'picID' ])) {
        $pic = '<img class="img-fluid img-thumbnail" style="width: 100%; max-width: 300px" src="../' . $filepath . $ds[ 'picID' ] . '.jpg" alt="">';
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
 
echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_gallery" enctype="multipart/form-data">

    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['picture'].':</label>
        <div class="col-sm-8">
            <span class="text-muted small"><em>'.$pic.'</em></span>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['pic_upload_info'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="btn btn-info" name="picture" type="file" size="40" /></em></span>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['pic_name'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="form-control" type="text" name="name" size="60" maxlength="255" value="' . getinput($ds[ 'name' ]) . '" /></em></span>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['comment'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="form-control" type="text" name="comment" size="60" maxlength="255" value="' . getinput($ds[ 'comment' ]) . '" /></em></span>
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language[ 'comments' ] . ':</label>
        <div class="col-sm-3"><span class="text-muted small"><em>
            <select class="form-select" name="comments">'.$comments.'</select></em></span>
        </div>
    </div>


    <div class="mb-3 row">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="picID" value="'.$ds['picID'].'" />
            <button class="btn btn-warning" type="submit" name="saveedit_set"  />'.$plugin_language['edit'].'</button>
        </div>
    </div>
</form>
</div></div>';
} elseif ($action == "delete_set") {
    
    $galclass = new \webspell\Gallery;

    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT
                `galleryID`
            FROM
                `" . PREFIX . "plugins_gallery_pictures`
            WHERE
                `picID` = '" . (int)$_GET['picID'] . "'"
        )
    );

    
        $ds = mysqli_fetch_array(
            safe_query(
                "SELECT
                    `galleryID`
                FROM
                    `" . PREFIX . "plugins_gallery_pictures`
                WHERE `picID` = '" . (int)$_GET['picID'] . "'"
            )
        );

        $dir = './includes/plugins/gallery/images/';

        //delete thumb

        @unlink($dir . 'thumb/' . $_GET['picID'] . '.jpg');

        //delete original

        if (file_exists($dir . 'large/' . $_GET['picID'] . '.jpg')) {
            @unlink($dir . 'large/' . $_GET['picID'] . '.jpg');
        } elseif (file_exists($dir . 'large/' . $_GET['picID'] . '.gif')) {  
            @unlink($dir . 'large/' . $_GET['picID'] . '.gif');
        } else {
            @unlink($dir . 'large/' . $_GET['picID'] . '.png');
        }

        //delete database entry

        safe_query(
            "DELETE FROM
                `" . PREFIX . "plugins_gallery_pictures`
            WHERE
                `picID` = '" . (int)$_GET['picID'] . "'"
        );
        
    echo $plugin_language[ 'delete_pic' ];
    redirect('admincenter.php?site=admin_gallery&galleryID=' . $_GET['galleryID'] . '', "", 2); return false;

}

if ($part == "groups") {
    if (isset($_POST[ 'save' ])) {
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
            if (checkforempty(array('name'))) {
                safe_query(
                    "INSERT INTO " . PREFIX . "plugins_gallery_groups ( name, sort ) values( '" . $_POST[ 'name' ] . "', '1' ) "
                );
            } else {
                echo $plugin_language[ 'information_incomplete' ];
            }
        } else {
            echo $plugin_language[ 'transaction_invalid' ];
        }
    } elseif (isset($_POST[ 'saveedit' ])) {
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
            if (checkforempty(array('name'))) {
                safe_query(
                    "UPDATE " . PREFIX . "plugins_gallery_groups SET name='" . $_POST[ 'name' ] . "'
                    WHERE groupID='" . $_POST[ 'groupID' ] . "'"
                );
            } else {
                echo $plugin_language[ 'information_incomplete' ];
            }
        } else {
            echo $plugin_language[ 'transaction_invalid' ];
        }
    } elseif (isset($_POST[ 'sort' ])) {
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
            if (isset($_POST[ 'sortlist' ])) {
                if (is_array($_POST[ 'sortlist' ])) {
                    foreach ($_POST[ 'sortlist' ] as $sortstring) {
                        $sorter = explode("-", $sortstring);
                        safe_query(
                            "UPDATE " . PREFIX . "plugins_gallery_groups
                            SET sort='$sorter[1]'
                            WHERE groupID='$sorter[0]' "
                        );
                    }
                }
            }
        } else {
            echo $plugin_language[ 'transaction_invalid' ];
        }
    } elseif (isset($_GET[ 'delete' ])) {
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
            $db_result = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery WHERE groupID='" . $_GET[ 'groupID' ] . "'");
            $any = mysqli_num_rows($db_result);
            if ($any) {
                echo $plugin_language[ 'galleries_available' ] . '<br /><br />';
            } else {
                safe_query("DELETE FROM " . PREFIX . "plugins_gallery_groups WHERE groupID='" . $_GET[ 'groupID' ] . "'");
            }
        } else {
            echo $plugin_language[ 'transaction_invalid' ];
        }
    
    }

    if ($action == "add") {
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();
    
echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> '.$plugin_language['pic_gallery'].'
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery">'.$plugin_language['pic_gallery'].'</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="admincenter.php?site=admin_gallery&part=groups">'.$plugin_language['groups'].'</a></li>
            <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['add_group'].'</li>
          </ol>
        </nav>

<div class="card-body">';


echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_gallery&amp;part=groups">
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['group_name'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="form-control" type="text" name="name" size="60" /></em></span>
        </div>
    </div>
    <div class="mb-3 row">
        <div class="col-sm-offset-2 col-sm-8">
	       <input type="hidden" name="captcha_hash" value="'.$hash.'" />
            <button class="btn btn-success" type="submit" name="save" />'.$plugin_language['add_group'].'</button>
        </div>
    </div>
</form>
    </div>
</div>';
} elseif ($action == "edit_group") {
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_groups WHERE groupID='" . $_GET[ 'groupID' ] . "'");
        $ds = mysqli_fetch_array($ergebnis);

echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> '.$plugin_language['pic_gallery'].'
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery">'.$plugin_language['pic_gallery'].'</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="admincenter.php?site=admin_gallery&part=groups">'.$plugin_language['groups'].'</a></li>
            <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['edit_group'].'</li>
          </ol>
        </nav>

<div class="card-body">';


echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_gallery&amp;part=groups">
	<div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['group_name'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="form-control" type="text" name="name" value="'.getinput($ds['name']).'" /></em></span>
        </div>
    </div>
    <div class="mb-3 row">
        <div class="col-sm-offset-2 col-sm-8">
	       <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="groupID" value="'.$ds['groupID'].'" />
            <button class="btn btn-success" type="submit" name="saveedit" />'.$plugin_language['edit_group'].'</button>
        </div>
    </div>
    </form>
    </div>
</div>';
} else {
     echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> '.$plugin_language['pic_gallery'].'
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery">'.$plugin_language['pic_gallery'].'</a></li>
            <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['groups'].'</li>
          </ol>
        </nav>

        <div class="card-body">

        <div class="mb-3 row">
            <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
            <div class="col-md-8">
                <a href="admincenter.php?site=admin_gallery&amp;part=groups&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_group' ] . '</a>
            </div>
        </div>';


$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_groups ORDER BY sort");
		
    echo'<form method="post" name="ws_gallery" action="admincenter.php?site=admin_gallery&amp;part=groups">
    <table class="table table-striped">
        <thead>
            <th><b>'.$plugin_language['group_name'].'</b></th>
            <th><b>'.$plugin_language['actions'].'</b></th>
            <th><b>'.$plugin_language['sort'].'</b></th>
        </thead>';
      
		$n = 1;
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();
        while ($ds = mysqli_fetch_array($ergebnis)) {
            
            $list = '<select name="sortlist[]">';
            $counter = mysqli_num_rows($ergebnis);
            for ($i = 1; $i <= $counter; $i++) {
                $list .= '<option value="' . $ds[ 'groupID' ] . '-' . $i . '">' . $i . '</option>';
            }
            $list .= '</select>';
            $list = str_replace(
                'value="' . $ds[ 'groupID' ] . '-' . $ds[ 'sort' ] . '"',
                'value="' . $ds[ 'groupID' ] . '-' . $ds[ 'sort' ] . '" selected="selected"',
                $list
            );
            echo '<tr>
        <td>'.$ds['name'].'</td>
        <td><a href="admincenter.php?site=admin_gallery&amp;part=groups&amp;action=edit_group&amp;groupID='.$ds['groupID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_gallery&amp;part=groups&amp;delete=true&amp;groupID='.$ds['groupID'].'&amp;captcha_hash='.$hash.'">
            ' . $plugin_language['delete'] . '
            </button>
            <!-- Button trigger modal END-->

            <!-- Modal -->
            <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'pic_gallery' ] . '</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
                  </div>
                  <div class="modal-body"><p>' . $plugin_language['really_delete_group'] . '</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
                    <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal END -->      

        </td>
        <td>'.$list.'</td>
		 	</tr>';
      $n++;
		}
		echo'<tr>
      <td class="td_head" colspan="3" align="right"><input type="hidden" name="captcha_hash" value="'.$hash.'" /><button class="btn btn-primary" type="submit" name="sort" />'.$plugin_language['to_sort'].'</button></td>
      </tr>
    </table>
    </form></div></div>';
    }
} elseif ($part == "gallerys") {
    if (isset($_POST[ 'save' ])) {
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
            if (checkforempty(array('name'))) {
                safe_query(
                    "INSERT INTO " . PREFIX . "plugins_gallery ( name, date, groupID )
                    values( '" . $_POST[ 'name' ] . "', '" . time() . "', '" . $_POST[ 'group' ] . "' ) "
                );
                $id = mysqli_insert_id($_database);
            } else {
                echo $plugin_language[ 'information_incomplete' ];
            }
        } else {
            echo $plugin_language[ 'transaction_invalid' ];
        }
    } elseif (isset($_POST[ 'saveedit' ])) {
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
            if (checkforempty(array('name'))) {
                if (!isset($_POST[ 'group' ])) {
                    $_POST[ 'group' ] = 0;
                }
                safe_query(
                    "UPDATE " . PREFIX . "plugins_gallery SET name='" . $_POST[ 'name' ] . "', groupID='" .
                    $_POST[ 'group' ] . "' WHERE galleryID='" . $_POST[ 'galleryID' ] . "'"
                );
            } else {
                echo $plugin_language[ 'information_incomplete' ];
            }
        } else {
            echo $plugin_language[ 'transaction_invalid' ];
        }
    } elseif (isset($_POST[ 'saveftp' ])) {
        $dir = './includes/plugins/gallery/images/';
        $dir_up = './includes/plugins/gallery/images/pic_update/';
        
        $pictures = array();
        if(isset($_POST['comment'])) $comment = $_POST['comment'];
        if(isset($_POST['name'])) $name = $_POST['name'];
        if(isset($_POST['pictures'])) $pictures = $_POST['pictures'];
        $i = 0;
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
            foreach ($pictures as $picture) {
                $typ = getimagesize($dir_up . $picture);
                switch ($typ[ 2 ]) {
                    case 1:
                        $typ = '.gif';
                        break;
                    case 2:
                        $typ = '.jpg';
                        break;
                    case 3:
                        $typ = '.png';
                        break;
                }
                if($name[$i]) $insertname = $name[$i];
                    else $insertname = $picture;
                safe_query(
                    "INSERT INTO " . PREFIX .
                    "plugins_gallery_pictures ( galleryID, name, comment, dateupl, comments) VALUES ('" . $_POST[ 'galleryID' ] .
                    "', '" . $insertname . "', '" . $comment[ $i ] . "', '" . time() . "', '" . $_POST[ 'comments' ] . "' )"
                );
                $insertid = mysqli_insert_id($_database);
                copy($dir_up . $picture, $dir . 'large/' . $insertid . $typ);
                $galclass->saveThumb($dir . 'large/' . $insertid . $typ, $dir . 'thumb/' . $insertid . '.jpg');
                @unlink($dir_up . $picture);
                $i++;
            }
        } else {
            echo $plugin_language[ 'transaction_invalid' ];
        }

    

    } elseif (isset($_POST[ 'saveform' ])) {
        $dir = './includes/plugins/gallery/images/';
        $picture = $_FILES[ 'picture' ];
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
            //TODO: should be loaded from root language folder
            $_language->readModule('formvalidation', true);

            $upload = new \webspell\HttpUpload('picture');
            if ($upload->hasFile()) {
                if ($upload->hasError() === false) {
                    $mime_types = array('image/jpeg', 'image/png', 'image/gif');
                    if ($upload->supportedMimeType($mime_types)) {
                        $imageInformation = getimagesize($upload->getTempFile());
                        if (is_array($imageInformation)) {
                            switch ($imageInformation[2]) {
                                case 1:
                                    $endung = '.gif';
                                    break;
                                case 3:
                                    $endung = '.png';
                                    break;
                                default:
                                    $endung = '.jpg';
                                    break;
                            }

                            if ($_POST[ 'name' ]) {
                                $insertname = $_POST[ 'name' ];
                            } else {
                                $insertname = $picture[ 'name' ];
                            }

                            safe_query(
                                "INSERT INTO " . PREFIX ."plugins_gallery_pictures (
                                    galleryID,
                                    name,
                                    comment,
                                    dateupl,
                                    comments
                                ) VALUES (
                                    '" . $_POST[ 'galleryID' ] ."',
                                    '" . $insertname . "',
                                    '" . $_POST[ 'comment' ] . "',
                                    '" . time() . "',
                                    '" . $_POST[ 'comments' ] . "'
                                )"
                            );

                            $insertid = mysqli_insert_id($_database);

                            $filepath = $dir . 'large/';
                            $file = $insertid . $endung;

                            if ($upload->saveAs($filepath . $file, true)) {
                                @chmod($filepath . $file, $new_chmod);
                                $galclass->saveThumb($filepath . $file, $dir . 'thumb/' . $insertid . '.jpg');
                            }
                        } else {
                            $errors[] = $plugin_language['broken_image'];
                        }
                    } else {
                        $errors[] = $plugin_language['unsupported_image_type'];
                    }
                } else {
                    $errors[] = $upload->translateError();
                }
            }
			if(isset($error)) {
				if (count($errors)) {
					$errors = array_unique($errors);
					echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
				}
			}
        } else {
            echo $plugin_language[ 'transaction_invalid' ];
        }
    } elseif (isset($_GET[ 'delete' ])) {
        //SQL
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
            if (safe_query("DELETE FROM " . PREFIX . "plugins_gallery WHERE galleryID='" . $_GET[ 'galleryID' ] . "'")) {
                //FILES
                $ergebnis = safe_query(
                    "SELECT picID FROM " . PREFIX . "plugins_gallery_pictures WHERE galleryID='" .
                    $_GET[ 'galleryID' ] . "'"
                );
                while ($ds = mysqli_fetch_array($ergebnis)) {
                    @unlink('../images/thumb/' . $ds[ 'picID' ] . '.jpg'); //thumbnails
                    $path = '../images/large/';
                    if (file_exists($path . $ds[ 'picID' ] . '.jpg')) {
                        $path = $path . $ds[ 'picID' ] . '.jpg';
                    } elseif (file_exists($path . $ds[ 'picID' ] . '.png')) {
                        $path = $path . $ds[ 'picID' ] . '.png';
                    } else {
                        $path = $path . $ds[ 'picID' ] . '.gif';
                    }
                    @unlink($path); //large
                    #safe_query(
                    #    "DELETE FROM " . PREFIX . "comments WHERE parentID='" . $ds[ 'picID' ] .
                    #    "' AND type='ga'"
                    #);
                }
                safe_query("DELETE FROM " . PREFIX . "plugins_gallery_pictures WHERE galleryID='" . $_GET[ 'galleryID' ] . "'");
            }
        } else {
            echo $plugin_language[ 'transaction_invalid' ];
        }    
    }

if ($action == "add") {
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_groups");
        $any = mysqli_num_rows($ergebnis);
        if ($any) {
            $groups = '<select class="form-select" name="group">';
            while ($ds = mysqli_fetch_array($ergebnis)) {
                $groups .= '<option value="' . $ds[ 'groupID' ] . '">' . getinput($ds[ 'name' ]) . '</option>';
            }
            $groups .= '</select>';
            $CAPCLASS = new \webspell\Captcha;
            $CAPCLASS->createTransaction();
            $hash = $CAPCLASS->getHash();

    echo'<script>
    <!--
    function chkFormular() {
        if (document.getElementById("name").value == "") {
            alert("'.$plugin_language["lang_you_have_to_name"].'");
            document.getElementById("name").focus();
            return false;
        }
    }
    -->
    </script>';
	    
echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> '.$plugin_language['pic_gallery'].'
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery">'.$plugin_language['pic_gallery'].'</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="admincenter.php?site=admin_gallery&amp;part=gallerys">'.$plugin_language['gallery'].'</a></li>
            <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['add_gallery'].'</li>
          </ol>
        </nav>

    <div class="card-body">';

echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;action=upload" onsubmit="return chkFormular();" enctype="multipart/form-data">

    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['gallery_name'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="form-control" type="text" name="name" id="name" size="60" /></em></span>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['group'].':</label>
        <div class="col-sm-8">
            <span class="text-muted small"><em>'.$groups.'</em></span>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['pic_upload'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <select class="form-select" name="upload">
                <option value="ftp">'.$plugin_language['ftp'].'</option>
                <option value="form">'.$plugin_language['formular'].'</option>
            </select></em></span>
        </div>
    </div>
    <div class="mb-3 row">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" name="captcha_hash" value="'.$hash.'" />
            <input class="btn btn-success" type="submit" name="save" value="'.$plugin_language['add_gallery'].'" />
        </div>
    </div>
   </form>
	    <br /><span class="text-muted small"><em>'.$plugin_language['ftp_info'].' "'.$hp_url.'/includes/plugins/gallery/images"</em></span></div></div>';
	} else {
        echo '<br />' . $plugin_language[ 'need_group' ];
    }
} elseif ($action == "edit") {
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_groups");
        $groups = '<select class="form-select" name="group">';
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $groups .= '<option value="' . $ds[ 'groupID' ] . '">' . getinput($ds[ 'name' ]) . '</option>';
        }
        $groups .= '</select>';
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery WHERE galleryID='" . $_GET[ 'galleryID' ] . "'");
        $ds = mysqli_fetch_array($ergebnis);
        $groups = str_replace(
            'value="' . $ds[ 'groupID' ] . '"',
            'value="' . $ds[ 'groupID' ] . '" selected="selected"',
            $groups
        );

echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> '.$plugin_language['pic_gallery'].'
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery">'.$plugin_language['pic_gallery'].'</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="admincenter.php?site=admin_gallery&amp;part=gallerys">'.$plugin_language['gallery'].'</a></li>
            <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['edit_gallery'].'</li>
          </ol>
        </nav>

        <div class="card-body">';

echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_gallery&amp;part=gallerys">

    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['gallery_name'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="form-control" type="text" name="name" value="'.getinput($ds['name']).'" /></em></span>
        </div>
    </div>';
    if($ds['userID'] != 0) echo '
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['usergallery_of'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <a href="../index.php?site=profile&amp;id='.$userID.'" target="_blank">'.getnickname($ds['userID']).'</a></em></span>
        </div>
    </div>';
    else echo '
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['group'].':</label>
        <div class="col-sm-8">
            <span class="text-muted small"><em>'.$groups.'</em></span>
        </div>
    </div>';
    echo'
    <div class="mb-3 row">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="galleryID" value="'.$ds['galleryID'].'" />
            <input class="btn btn-success" type="submit" name="saveedit" value="'.$plugin_language['edit_gallery'].'" />
        </div>
    </div>

    </form></div></div>';

} elseif ($action == "upload") {
        echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> '.$plugin_language['pic_gallery'].'
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery">'.$plugin_language['pic_gallery'].'</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery&part=gallerys">'.$plugin_language['gallery'].'</a></li>
            <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['upload'].'</li>
          </ol>
        </nav>

        <div class="card-body">';

        $dir = './includes/plugins/gallery/images/pic_update/';
        if (isset($_POST[ 'upload' ])) {
            $upload_type = $_POST[ 'upload' ];
        } elseif (isset($_GET[ 'upload' ])) {
            $upload_type = $_GET[ 'upload' ];
        } else {
            $upload_type = null;
        }
        if (isset($_POST[ 'galleryID' ])) {
            $id = $_POST[ 'galleryID' ];
        } elseif (isset($_GET[ 'galleryID' ])) {
            $id = $_GET[ 'galleryID' ];
        }
        if ($upload_type == "ftp") {
            $CAPCLASS = new \webspell\Captcha;
            $CAPCLASS->createTransaction();
            $hash = $CAPCLASS->getHash();

    echo'<script>
    <!--
    function chkFormular() {
        if (document.getElementById("no_pic").value == "") {
            alert("'.$plugin_language["lang_you_have_to_no_pic"].'");
            document.getElementById("no_pic").focus();
            return false;
        }
    }
    -->
    </script>';

    echo '<form method="post" action="admincenter.php?site=admin_gallery&amp;part=gallerys" onsubmit="return chkFormular();" enctype="multipart/form-data">
            <table width="100%" border="0" cellspacing="1" cellpadding="3">
                <tr>
                  <td>';
            $pics = array();
            $picdir = opendir($dir);
            while (false !== ($file = readdir($picdir))) {
                if ($file != "." && $file != "..") {
                    if (is_file($dir . $file)) {
                        if ($info = getimagesize($dir . $file)) {
                            if ($info[ 2 ] == 1 || $info[ 2 ] == 2 || $info[ 2 ] == 3) {
                                $pics[ ] = $file;
                            }
                        }
                    }
                }
            }
            closedir($picdir);
            natcasesort($pics);
            reset($pics);

           echo'<!--<form method="post" action="admincenter.php?site=admin_gallery&amp;part=gallerys" onsubmit="return chkFormular();" enctype="multipart/form-data">-->
		      <table width="100%" border="0" cellspacing="1" cellpadding="3">
		        <tr>
		          <td>';
		
			$pics = Array();
			$picdir = opendir($dir);
			while (false !== ($file = readdir($picdir))) {
				if ($file != "." && $file != "..") {
					if(is_file($dir.$file)) {
						if($info = getimagesize($dir.$file)) {
							if($info[2]==1 OR $info[2]==2 || $info[2]==3) $pics[] = $file;
						}
					}
				}
			}
			closedir($picdir);
			natcasesort ($pics);
			reset ($pics);
					
		    echo '<table class="table">
		        <tr>
		          <td><b>'.$plugin_language['actions'].'</b></td>
		          <td><b>'.$plugin_language['filename'].'</b></td>
		          <td><b>'.$plugin_language['name'].'</b></td>
		          <td><b>'.$plugin_language['comment'].'</b></td>
		        </tr>';
          
		
			foreach ($pics as $val) {
                if (is_file($dir . $val)) {
				echo'<tr>
                    <td><input class="form-check-input" type="checkbox" value="'.$val.'" name="pictures[]" id="pictures" checked="checked" />&nbsp;<img class="img-fluid img-thumbnail" style="width: 114px" src="../includes/plugins/gallery/images/pic_update/' . $val . '" alt=""></td>
                    <td><a href=".'.$dir.$val.'" target="_blank" name="picture" id="picture">'.$val.'</a></td>
                    <td><input class="form-control" type="text" name="name[]" size="40" /></td>
                    <td><input class="form-control" type="text" name="comment[]" size="40" /></td>
                  </tr>';
                }
            } 

        if (!empty($pics)) {
            $only = '</table></td>
                  </tr>
                  <tr>
                    <td><br /><b>'.$plugin_language['visitor_comments'].'</b> &nbsp;
                    <select class="form-select" name="comments">
                      <option value="0">'.$plugin_language['disable_comments'].'</option>
                      <option value="1">'.$plugin_language['enable_user_comments'].'</option>
                      <option value="2" selected="selected">'.$plugin_language['enable_visitor_comments'].'</option>
                    </select></td>
                  </tr>';
        } else {
            $only = '</table></td>
                  </tr>
                  <tr>
                    <td><br /><input class="form-control" type="text" name="no_pic" id="no_pic"  size="60"  disabled/></td>
                  </tr>';
        }			

			echo ''.$only.'
                  
		          <tr>
		            <td>('.$plugin_language['ftp_info'].')<br /><input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="galleryID" value="'.$id.'" />
		            <input class="btn btn-primary" type="submit" name="saveftp" value="'.$plugin_language['upload'].'" /></td>
		          </tr>
		        </table>
		        </form></div></div>';
        
    } elseif ($upload_type == "form") {
            $CAPCLASS = new \webspell\Captcha;
            $CAPCLASS->createTransaction();
            $hash = $CAPCLASS->getHash();

echo'<script>
    <!--
    function chkFormular() {
        if (document.getElementById("picture").value == "") {
            alert("'.$plugin_language["lang_you_have_to_picture"].'");
            document.getElementById("picture").focus();
            return false;
        }
    }
    -->
    </script>';

	echo'<form method="post" action="admincenter.php?site=admin_gallery&amp;part=gallerys" enctype="multipart/form-data" onsubmit="return chkFormular();" enctype="multipart/form-data">
		<table class="table">
        <tr>
          <td><b>'.$plugin_language['name'].'</b></td>
          <td><input class="form-control" type="text" name="name" id="name" size="60" /></td>
        </tr>
        <tr>
          <td><b>'.$plugin_language['comment'].'</b></td>
          <td><input class="form-control" type="text" name="comment" size="60" maxlength="255" /></td>
        </tr>
        <tr>
          <td><b>'.$plugin_language['visitor_comments'].'</b></td>
          <td><select class="form-select" name="comments">
            <option value="0">'.$plugin_language['disable_comments'].'</option>
            <option value="1">'.$plugin_language['enable_user_comments'].'</option>
            <option value="2" selected="selected">'.$plugin_language['enable_visitor_comments'].'</option>
          </select></td>
        </tr>
        <tr>
          <td><b>'.$plugin_language['picture'].'</b></td>
          <td><input class="btn btn-info" name="picture" type="file" id="picture" size="40" /></td>
        </tr>
        <tr>
          <td><input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="galleryID" value="'.$id.'" /></td>
          <td><input class="btn btn-primary" type="submit" name="saveform" value="'.$plugin_language['upload'].'" /></td>
        </tr>
      </table>
      </form></div></div>';
		}
} elseif ($part == "gallerys") {
	
	echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> '.$plugin_language['pic_gallery'].'
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="admincenter.php?site=admin_gallery">'.$plugin_language['pic_gallery'].'</a></li>
            <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['gallery'].'</li>
          </ol>
        </nav>

        <div class="card-body">

            <div class="mb-3 row">
                <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
                <div class="col-md-8">
                    <a href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_gallery' ] . '</a>
                </div>
            </div>';

    echo'<form method="post" name="ws_gallery" action="admincenter.php?site=admin_gallery&amp;part=gallerys">
		<table class="table">
            <thead>
                <th><b>'.$plugin_language['gallery_name'].'</b></th>
                <th></th>
                <th align="center"><b>'.$plugin_language['actions'].'</b></th>
            </thead>';

		$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_groups ORDER BY sort");
        while ($ds = mysqli_fetch_array($ergebnis)) {
            echo'<tr>
                    <td class="table-secondary" colspan="3"><b>' . getinput($ds[ 'name' ]) . '</b></td>
                </tr>';
            $galleries = safe_query(
                "SELECT * FROM " . PREFIX .
                "plugins_gallery WHERE groupID='$ds[groupID]' AND userID='0' ORDER BY sort"
            );
            $CAPCLASS = new \webspell\Captcha;
            $CAPCLASS->createTransaction();
            $hash = $CAPCLASS->getHash();
            $i = 1;
            while ($db = mysqli_fetch_array($galleries)) {
                
                echo '<tr>
          <td><a href="../index.php?site=gallery&amp;galleryID='.$db['galleryID'].'" target="_blank">'.getinput($db['name']).'</a></td>
          <td align="center">

          
          <a href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;action=upload&amp;upload=form&amp;galleryID='.$db['galleryID'].'" class="btn btn-primary" type="button" data-toggle="tooltip" data-html="true" title="' . $plugin_language[ 'tooltip_7' ]. '" >'.$plugin_language['add_img'].' ('.$plugin_language['per_form'].')</a>


         <a href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;action=upload&amp;upload=ftp&amp;galleryID='.$db['galleryID'].'" class="btn btn-primary" type="button" data-toggle="tooltip" data-html="true" title="' . $plugin_language[ 'tooltip_8' ]. '" >'.$plugin_language['add_img'].' ('.$plugin_language['per_ftp'].')</a>   

        </td>
        <td><a href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;action=edit&amp;galleryID='.$db['galleryID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;delete=true&amp;galleryID='.$db['galleryID'].'&amp;captcha_hash='.$hash.'">
        ' . $plugin_language['delete'] . '
        </button>
        <!-- Button trigger modal END-->

        <!-- Modal -->
        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'pic_gallery' ] . '</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
              </div>
              <div class="modal-body"><p>' . $plugin_language['really_delete_gallery'] . '</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
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
		echo'</table></form></div></div><br />';

 
    echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> '.$plugin_language['usergalleries'].'
        </div>
                        <div class="card-body">';

		$ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_gallery WHERE userID!='0'");
		
    echo'<form method="post" name="ws_gallery" action="admincenter.php?site=admin_gallery&amp;part=gallerys">
    <table class="table table-striped">
        <thead>
            <th><b>'.$plugin_language['gallery_name'].'</b></th>
            <th><b>'.$plugin_language['usergallery_of'].'</b></th>
            <th><b>'.$plugin_language['actions'].'</b></th>
        </thead>';
      
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();
        $i = 1;
        while ($ds = mysqli_fetch_array($ergebnis)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }
            echo '<tr>
        <td><a href="../index.php?site=gallery&amp;galleryID='.$ds['galleryID'].'" target="_blank">'.getinput($ds['name']).'</a></td>
        <td><a href="../index.php?site=profile&amp;id='.$userID.'" target="_blank">'.getnickname($ds['userID']).'</a></td>
        <td><a href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;action=edit&amp;galleryID='.$ds['galleryID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_gallery&amp;part=gallerys&amp;delete=true&amp;galleryID='.$ds['galleryID'].'&amp;captcha_hash='.$hash.'">
            ' . $plugin_language['delete'] . '
            </button>
            <!-- Button trigger modal END-->

             <!-- Modal -->
        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'gallery' ] . '</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
              </div>
              <div class="modal-body"><p>' . $plugin_language['really_delete_gallery'] . '</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
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
		echo'</table></form>';
    }


} elseif ($action == "admin_gallery_settings") {
 
    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_settings");
    $dx = mysqli_fetch_array($settings);

    $da = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='squads'"));
    if (@$da[ 'modulname' ] != 'squads') {    
        $usergalleries = '<div class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-triangle" style="font-size: 1rem; color: red;"></i> '.$plugin_language[ 'no_plugin_squads' ].'</div>';
    } else {
        if ($dx[ 'usergalleries' ]) {
            $usergalleries = '<input class="form-check-input" type="checkbox" name="usergalleries" value="1" checked="checked" />';
        } else {
            $usergalleries = '<input class="form-check-input" type="checkbox" name="usergalleries" value="1" />';
        }        
    }

    if ($dx[ 'publicadmin' ]) {
        $publicadmin = '<input class="form-check-input" type="checkbox" name="publicadmin" value="1" checked="checked" />';
    } else {
        $publicadmin = '<input class="form-check-input" type="checkbox" name="publicadmin" value="1" />';
    }    

 
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    
echo'<form method="post" action="admincenter.php?site=admin_gallery&action=admin_gallery_settings">

    <div class="card">
        <div class="card-header">
            <i class="bi bi-paragraph"></i> '.$plugin_language[ 'title' ].'</div>
                        
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admincenter.php?site=admin_gallery">'.$plugin_language['pic_gallery'].'</a></li>
                    <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['title'].'</li>
                </ol>
            </nav> 
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['groups'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_6' ].'">
                                <input class="form-control" name="groups" type="text" value="'.getinput($dx['groups']).'" size="35"></em></span>
                            </div>
                        </div>

                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['space_user'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_3' ].'"><input class="form-control" type="text" name="maxusergalleries" value="'.getinput($dx['maxusergalleries']/(1024*1024)).'" size="35"  /></em></span>
                            </div>
                        </div>                        
                        
                    </div>

                    <div class="col-md-6">
                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['allow_usergalleries'].':
                            </div>

                            <div class="col-md-6 form-check form-switch" style="padding: 0px 43px;">
                                <span data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_4' ].'">'.$usergalleries.'</span>
                            </div>
                        </div>

                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['public_admin'].':
                            </div>

                            <div class="col-md-6 form-check form-switch" style="padding: 0px 43px;">
                                <span data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_5' ].'">'.$publicadmin.'</span>
                            </div>
                        </div>
                        
                    </div>

                <br>
                <div class="mb-3 row">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
                        <input type="hidden" name="gallerysetID" value="'.$dx['gallerysetID'].'" />
                        
                        <button class="btn btn-warning" type="submit" name="admin_settings_save">' . $plugin_language['update'] . '</button>
                    </div>
                </div>

            </div>
        </div>
    </form>';


} elseif (isset($_GET[ 'galleryID' ])) {
    $pics_per_row = 2;
    $galclass = new \webspell\Gallery;

    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT `name`,`number_of_images` FROM `" . PREFIX . "plugins_gallery` WHERE `galleryID` = '" . $_GET[ 'galleryID' ] . "'"
        )
    );
    
    $title = $ds[ 'name' ];
    $numberofimages = $ds[ 'number_of_images' ];

    $galleryID = (int)$_GET[ 'galleryID' ];
    if ($galclass->getGroupIdByGallery($_GET[ 'galleryID' ])) {
        $group =
            '<a href="index.php?site=gallery&amp;groupID=' . $galclass->getGroupIdByGallery($_GET[ 'galleryID' ]) .
            '" class="titlelink">' . $galclass->getGroupName($galclass->getGroupIdByGallery($_GET[ 'galleryID' ])) .
            '</a>';
    } else {
        $group = '<a href="index.php?site=gallery&amp;groupID=0" class="titlelink">' .
            $plugin_language[ 'usergalleries' ] .
            '</a> &gt;&gt; <a href="index.php?site=profile&amp;action=galleries&amp;id=' .
            $galclass->getGalleryOwner($_GET[ 'galleryID' ]) . '" class="titlelink">' .
            getnickname($galclass->getGalleryOwner($_GET[ 'galleryID' ])) . '</a>';
    }

    $gallery_pictures=$numberofimages;

    $pics = mysqli_num_rows(safe_query("SELECT picID FROM ".PREFIX."plugins_gallery_pictures WHERE galleryID='".$_GET['galleryID']."' ORDER BY sort"));
    $pages = ceil($pics/$gallery_pictures);
    
    if(!isset($_GET['page'])) {
        $page = 1;
    }else{
        $page = $_GET['page'];
    }    

    if($pages > 1) {
        $pagelink = makepagelink("admincenter.php?site=gallery&amp;galleryID=".$_GET['galleryID'], $page, $pages);
    }else{
        $pagelink = '';
    }

    if($page == "1") {
        $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_gallery_pictures WHERE galleryID='".$_GET['galleryID']."' ORDER BY picID LIMIT 0, ".$gallery_pictures);
    }
    else {
        $start = $page * $gallery_pictures - $gallery_pictures;
        $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_gallery_pictures WHERE galleryID='".$_GET['galleryID']."' ORDER BY picID LIMIT ".$start.", ".$gallery_pictures);
    }

   echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> '.$plugin_language['pic_gallery'].'
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery">'.$plugin_language['pic_gallery'].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['pic_gallery'].'</li>
            </ol>
        </nav>

        <div class="card-body">
        <form method="post" action="admincenter.php?site=admin_gallery">';

    $ergebnis_title = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_groups WHERE groupID='" . $galclass->getGroupIdByGallery($_GET[ 'galleryID' ]) .
            "'");
        while ($ds = mysqli_fetch_array($ergebnis_title)) {
            
            $db = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_gallery WHERE galleryID='".$_GET['galleryID']."'")); 
            $CAPCLASS = new \webspell\Captcha;
            $CAPCLASS->createTransaction();
            $hash = $CAPCLASS->getHash();

            if (isset($db[ 'galleryID' ])) {
                $db[ 'count' ] = mysqli_num_rows(safe_query("SELECT `picID` FROM`" . PREFIX . "plugins_gallery_pictures` WHERE `galleryID` = '" . (int)$db[ 'galleryID' ] . "'"));
                }
            $count=$db[ 'count' ];
            echo'<h4>'.getinput($ds['name']).' / '.getinput($db['name']).'</h4>

            <div class="mb-3 row">
                <label for="inputPassword" class="col-sm-2 col-form-label">'.$plugin_language['pictures'].':</label>
                <div class="col-sm-1"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_1' ].'">
                    <input class="form-control" name="number_of_images" type="text" value="'.getinput($db[ 'number_of_images' ]).'" size="35"></em>
                </div>

                <div class="col-sm-1">
                    <input type="hidden" name="captcha_hash" value="'.$hash.'" />
                    <input type="hidden" name="galleryID" value="'.$db['galleryID'].'" />
                    <button class="btn btn-warning" type="submit" name="admin_settings_number_of_images">' . $plugin_language['update'] . '</button>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="inputPassword" class="col-sm-2 col-form-label">'.$plugin_language['images_per_page'].':</label>
                <div class="col-sm-1"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_9' ].'">
                    <input class="form-control" name="images_per_page" type="text" value="'.getinput($db[ 'images_per_page' ]).'" size="35"></em>
                </div>

                <div class="col-sm-1">
                    <input type="hidden" name="captcha_hash" value="'.$hash.'" />
                    <input type="hidden" name="galleryID" value="'.$db['galleryID'].'" />
                    <button class="btn btn-warning" type="submit" name="admin_settings_images_per_page">' . $plugin_language['update'] . '</button>
                </div>
            </div> <hr> 

            <small>'.$pages.' '.$plugin_language[ 'page_s' ].' / '.$plugin_language[ 'pictures' ].':  '.$count.'</small><br>                ';
        }

        $picture=$db['images_per_page'];
        echo'<style>@media (min-width:576px){
            .card-columns-ad{
                -webkit-column-count:'.$picture.';
                -moz-column-count:'.$picture.';
                column-count:'.$picture.';
                -webkit-column-gap:1.25rem;
                -moz-column-gap:1.25rem;
                column-gap:1.25rem;
                orphans:1;widows:1;
            }
        }</style>';

    echo'<div class="col-md-6">
        <div class="card-columns-ad">';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $anz = mysqli_num_rows($ergebnis);
    if ($anz) {
        $i = 1;

        while ($pic = mysqli_fetch_array($ergebnis)) {

            #$dir = "../".$plugin_path."images/thumb/".$pic[ 'picID' ];
            $dir = ".".$galclass->getThumbFile($pic[ 'picID' ]);

            $pic['name'] = $pic['name'];

            $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_settings");
            $db = mysqli_fetch_array($settings);



            echo'<div class="col card bildmitbildunterschrift">
                    <a class="" href="../index.php?site=gallery&amp;picID='.$pic['picID'].'">
                    <img class="img-fluid gallery_pix" src="'.$dir.'" alt="'.$pic[ 'name' ].'"></a>
                        <span>
                            <b>'.$plugin_language[ 'pic_name' ].':</b> '.$pic[ 'name' ].'<br>

                            <!--<select name="sort[]">';
                            for ($j = 1; $j <= $anz; $j++) {
                                if ($pic[ 'sort' ] == $j) {
                                    echo '<option value="' . $pic['picID'] . '-' . $j . '" selected="selected">' . $j .
                                        '</option>';
                                } else {
                                    echo '<option value="' . $pic['picID'] . '-' . $j . '">' . $j . '</option>';
                                }
                            }
                            echo '</select>-->


                            <a href="admincenter.php?site=admin_gallery&amp;action=edit_pic&amp;picID=' . $pic['picID'] . '" class="btn btn-warning"
                            style="--bs-btn-padding-y: .15rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .55rem;"
                            type="button">' . $plugin_language[ 'name_edit' ] . '</a>

                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-danger"style="--bs-btn-padding-y: .15rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .55rem;" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_gallery&amp;action=delete_set&amp;picID=' . $pic[ 'picID' ] . '&amp;galleryID=' . $pic['galleryID'] . '&amp;captcha_hash=' . $hash . '">
                                ' . $plugin_language['delete'] . '
                            </button>
                            <!-- Button trigger modal END-->

                        </span>

                </div>
                <div class="col" style="margin-top: -49px;margin-bottom: -45px;">
                
            </div>';
            echo'<!-- Modal -->
            <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'pic_gallery' ] . '</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
                  </div>
                  <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
                    <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal END -->';
            $i++;

        }

    } else {
        echo $plugin_language['no_thumb'];
    }

    echo'</div></div>';
    if($pages>1) echo $pagelink;
    echo'</form></div></div>';

} elseif ($action == "") {

    echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-images"></i> '.$plugin_language['pic_gallery'].'
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_gallery">'.$plugin_language['pic_gallery'].'</a></li>
            <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
          </ol>
        </nav>

        <div class="card-body">
        <form method="post" action="admincenter.php?site=admin_gallery">
        <div class="mb-3 row">
            <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
            <div class="col-md-8">
                <a href="admincenter.php?site=admin_gallery&part=gallerys" class="btn btn-primary" type="button">' . $plugin_language[ 'new_gallery_pic' ] . '</a>
                <a href="admincenter.php?site=admin_gallery&part=groups" class="btn btn-primary" type="button">' . $plugin_language[ 'groups' ] . '</a>
                <a href="admincenter.php?site=admin_gallery&action=admin_gallery_settings" class="btn btn-primary" type="button">' . $plugin_language[ 'title' ] . '</a>
            </div>
        </div>';

    echo'<table class="table">
    <thead>
      <th style="width: 50%"><b>' . $plugin_language['pic_gallery'] . '</b></th>
      <th><b>' . $plugin_language['actions'] . '</b></th>
      <th><b>' . $plugin_language['sort'] . '</b></th>
    </thead>';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_gallery_groups` ORDER BY sort");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(groupID) as cnt FROM `" . PREFIX . "plugins_gallery_groups`"));
    $anz = $tmp[ 'cnt' ];

        while ($ds = mysqli_fetch_array($ergebnis)) {

            $list = '<select name="sortcat[]">';
                for ($j = 1; $j <= $anz; $j++) {
                    $list .= '<option value="' . $ds[ 'groupID' ] . '-' . $j . '">' . $j . '</option>';
                }
                $list .= '</select>';
                $list = str_replace(
                    'value="' . $ds[ 'groupID' ] . '-' . $ds[ 'sort' ] . '"',
                    'value="' . $ds[ 'groupID' ] . '-' . $ds[ 'sort' ] . '" selected="selected"',
                    $list
                );

            echo'<thead>
                    <th class="table-secondary"><b>' . getinput($ds[ 'name' ]) . '</b></th><th class="table-secondary"></th>
                    <th class="table-secondary">'.$list.'</th>
                </thead>';

        $galleries = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery WHERE groupID='" . $ds[ 'groupID' ] . "' AND userID='0' ORDER BY sort");
        $tmp = mysqli_fetch_assoc(
            safe_query("SELECT count(groupID) as cnt FROM " . PREFIX . "plugins_gallery WHERE groupID='" . $ds[ 'groupID' ] . "'"));
        $anzlinks = $tmp[ 'cnt' ];
            
            $i = 1;
            while ($db = mysqli_fetch_array($galleries)) {

                $linklist = '<select name="sortlinks[]">';
                for ($j = 1; $j <= $anzlinks; $j++) {
                    $linklist .= '<option value="' . $db[ 'galleryID' ] . '-' . $j . '">' . $j . '</option>';
                }
                $linklist .= '</select>';
                $linklist = str_replace(
                    'value="' . $db[ 'galleryID' ] . '-' . $db[ 'sort' ] . '"',
                    'value="' . $db[ 'galleryID' ] . '-' . $db[ 'sort' ] . '" selected="selected"',
                    $linklist
                );
                
                echo'<tr>
                        <td>&nbsp;&nbsp;- '.getinput($db['name']).'</td>
                        <td><a class="btn btn-warning" type="button" href="admincenter.php?site=admin_gallery&galleryID='.$db['galleryID'].'">' . $plugin_language[ 'edit' ] . '</a></td>
                        <td>'.$linklist.'</td>
                    </tr>';    
            }
        }
        echo'<tr>
      <td colspan="3" align="right"><input type="hidden" name="captcha_hash" value="'.$hash.'" />
      <button class="btn btn-primary" type="submit" name="sortieren_groups" />'.$plugin_language['to_sort'].'</button></td>
    </tr>
  </table>
  </form>';

echo'</div></div>';
}


?>