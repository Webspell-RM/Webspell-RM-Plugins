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
$plugin_language = $pm->plugin_language("admin_projectlist", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='projectlist'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}

$filepath = $plugin_path."images/";

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if (isset($_GET[ 'delete' ])) {
    $projectlistID = $_GET[ 'projectlistID' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        safe_query(
            "DELETE FROM
                `" . PREFIX . "plugins_projectlist`
            WHERE
                `projectlistID` = '" . $projectlistID . "'"
        );
        \webspell\Tags::removeTags('links', $projectlistID);
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }

} elseif (isset($_GET[ 'delete' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_projectlist WHERE projectlistID='" . $_GET[ "projectlistID" ] . "'");
        $data = mysqli_fetch_assoc($get);

        if (safe_query("DELETE FROM " . PREFIX . "plugins_projectlist WHERE projectlistID='" . $_GET[ "projectlistID" ] . "'")) {
           
            redirect("admincenter.php?site=admin_projectlist", "", 0);
        } else {
            redirect("admincenter.php?site=admin_projectlist", "", 0);
        }
    } else {
        print_r($plugin_language); return false;
        $_language->readModule('formvalidation', true);  
        echo $plugin_language[ 'transaction_invalid' ];
    }

 } elseif ($action == "delete") {   
 $filepath = $plugin_path."images/";
 

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
    
 
    $projectlistID = $_GET[ 'projectlistID' ];
    safe_query("DELETE FROM " . PREFIX . "plugins_projectlist WHERE projectlistID='$projectlistID'");
    
    $filepath = $plugin_path."images/";
    if (file_exists($filepath . $projectlistID . '.gif')) {
        @unlink($filepath . $projectlistID . '.gif');
    }
    if (file_exists($filepath . $projectlistID . '.jpg')) {
        @unlink($filepath . $projectlistID . '.jpg');
    }
    if (file_exists($filepath . $projectlistID . '.png')) {
        @unlink($filepath . $projectlistID . '.png');
    }
            redirect("admincenter.php?site=admin_projectlist", "", 0);
    }
   

} elseif (isset($_POST[ 'sortieren' ])) {
    $sortlinks = $_POST[ 'sortlinks' ];

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (is_array($sortlinks)) {
            foreach ($sortlinks as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE `" . PREFIX . "plugins_projectlist` SET `sort` = '$sorter[1]' WHERE `projectlistID` = '" . $sorter[0] . "'");
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'save' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $linkscat = $_POST[ 'linkscat' ];
        $question = $_POST[ 'question' ];
        $answer = $_POST[ 'message' ];
        $status = $_POST[ "status" ];
        $prozent = $_POST[ "prozent" ];
        #$date = date("Y-m-d", strtotime($_POST['date']));


        if (isset($_POST[ "displayed" ])) {
            $displayed = 1;
        } else {
            $displayed = 0;
        }


        safe_query(
            "INSERT INTO
                `" . PREFIX . "plugins_projectlist` (
                        `projectlistcatID`,
                        `date`,
                        `question`,
                        `answer`,
                        `status`,
                        `poster`,
                        `prozent`,
                        `displayed`,
                        `sort`
                    )
                VALUES (
                    '$linkscat',
                    '" . time() . "',
                    '$question',
                    '$answer',
                    '$status',
                    '" . $userID . "',
                    '" . $prozent . "',
                    '" . $displayed . "',
                    '1'


                )"
        );
        $id = mysqli_insert_id($_database);
        #\webspell\Tags::setTags('links', $id, $_POST[ 'tags' ]);

        $filepath = $plugin_path."images/";

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation',true, true);
        
        $upload = new \webspell\HttpUpload('banner');

        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation =  getimagesize($upload->getTempFile());

                    if (is_array($imageInformation)) {
                        if ($imageInformation[0] < 1921 && $imageInformation[1] < 1081) {
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

                            if (file_exists($filepath . $id . '.gif')) {
                                unlink($filepath . $id . '.gif');
                            }
                            if (file_exists($filepath . $id . '.jpg')) {
                                unlink($filepath . $id . '.jpg');
                            }
                            if (file_exists($filepath . $id . '.png')) {
                                unlink($filepath . $id . '.png');
                            }

                            if ($upload->saveAs($filepath.$file)) {
                                @chmod($filepath.$file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_projectlist
                                    SET banner='" . $file . "' WHERE projectlistID='" . $id . "'"
                                );
                            }
                        } else {
                            echo generateErrorBox(sprintf($plugin_language[ 'image_too_big' ], 1920, 1080));
                        }
                    } else {
                        echo generateErrorBox($plugin_language[ 'broken_image' ]);
                    }
                } else {
                    echo generateErrorBox($plugin_language[ 'unsupported_image_type' ]);
                }
            } else {
                echo  generateErrorBox($upload->translateError());
            }
        }
    } else {
        echo  $plugin_language[ 'transaction_invalid' ];
    }


} elseif (isset($_POST[ 'saveedit' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {

    $linkscat = $_POST[ 'linkscat' ];
    $question = $_POST[ 'question' ];
    $answer = $_POST[ 'message' ];
    $projectlistID = $_POST[ 'projectlistID' ];
    $prozent = $_POST[ 'prozent' ];
    $status = $_POST[ 'status' ];
    #$date = date("Y-m-d", strtotime($_POST['date']));
    #$date = $_POST['date'];
    $date = strtotime($_POST['date']);


        if (isset($_POST[ "displayed" ])) {
            $displayed = 1;
        } else {
            $displayed = 0;
        }
        

        $projectlistID = (int)$_POST[ 'projectlistID' ];
        $id = $projectlistID;

        

        safe_query(
            "UPDATE
                `" . PREFIX . "plugins_projectlist`
                SET
                    `projectlistcatID` = '" . $linkscat . "',
                    `date` = '" . $date . "',
                    `question` = '" . $question . "',
                    `prozent` = '" . $prozent . "',
                    `status` = '" . $status . "',
                    `answer` = '" . $answer . "',
                    `poster` = '" . $userID . "',
                    `displayed` = '" . $displayed . "'
                WHERE
                    `projectlistID` = '" . $projectlistID . "'"
        );

        #\webspell\Tags::setTags('links', $id, $_POST[ 'tags' ]);

        $filepath = $plugin_path."/images/";

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation', true, true);

        $upload = new \webspell\HttpUpload('banner');

        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation =  getimagesize($upload->getTempFile());

                    if (is_array($imageInformation)) {
                        if ($imageInformation[0] < 1921 && $imageInformation[1] < 1081) {
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

                            if (file_exists($filepath . $id . '.gif')) {
                                unlink($filepath . $id . '.gif');
                            }
                            if (file_exists($filepath . $id . '.jpg')) {
                                unlink($filepath . $id . '.jpg');
                            }
                            if (file_exists($filepath . $id . '.png')) {
                                unlink($filepath . $id . '.png');
                            }

                            if ($upload->saveAs($filepath.$file)) {
                                @chmod($filepath.$file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_projectlist
                                    SET banner='" . $file . "' WHERE projectlistID='" . $id . "'"
                                );
                            }
                        } else {
                            echo generateErrorBox(sprintf($plugin_language[ 'image_too_big' ], 1920, 1080));
                        }
                    } else {
                        echo generateErrorBox($plugin_language[ 'broken_image' ]);
                    }
                } else {
                    echo generateErrorBox($plugin_language[ 'unsupported_image_type' ]);
                }
            } else {
                echo generateErrorBox($upload->translateError());
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }


} elseif (isset($_POST[ 'links_settings_save' ])) {  

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE
                " . PREFIX . "plugins_projectlist_settings
            SET
                projectlist='" . $_POST[ 'projectlist' ] . "',
                projectlistchars='" . $_POST[ 'projectlistchars' ] . "' "
        );
        
        redirect("admincenter.php?site=admin_projectlist&action=admin_projectlist_settings", "", 0);
    } else {
        redirect("admincenter.php?site=admin_projectlist&action=admin_projectlist_settings", $plugin_language[ 'transaction_invalid' ], 3);
    }
}

if ($action == "add") {
    #if ($_GET[ 'action' ] == "add") {

        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

        $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_projectlist_categories` ORDER BY `sort`");
        $linkscats = '<select class="form-select" name="linkscat">';
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $linkscats .= '<option value="' . $ds[ 'projectlistcatID' ] . '">' . getinput($ds[ 'projectlistcatname' ]) . '</option>';
        }
        $linkscats .= '</select>';

        $status = '<option value="currently_under_construction">' . $plugin_language[ 'currently_under_construction' ] . '</option>
        				<option value="alpha_test">' . $plugin_language[ 'alpha_test' ] . '</option>
                <option value="beta_test">' . $plugin_language[ 'beta_test' ] . '</option>
                <option value="work_complete">' . $plugin_language[ 'work_complete' ] . '</option>';
            #$status = str_replace('value="' . $ds['status'] . '"', 'value="' . $ds['status'] . '" selected="selected"', $status);

        #$date = date("Y-m-d", strtotime($ds[ 'date' ]));

        if (isset($_GET[ 'answer' ])) {
            echo '<span style="color: red">' . $plugin_language[ 'no_category_selected' ] . '</span>';
            $question = $_GET[ 'question' ];
            $answer = $_GET[ 'answer' ];
        } else {
            $question = "";
            $answer = "";
        }

        $prozent = "";

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'projectlist' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_projectlist">' . $plugin_language[ 'projectlist' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['new_project'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">';


    echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_projectlist" enctype="multipart/form-data">
     <div class="row">
	 <div class="mb-3 row row">
    <label class="col-sm-2 control-label">'.$plugin_language['category'].'</label>
    <div class="col-sm-8">
      '.$linkscats.'
    </div>
  </div>
  <div class="mb-3 row row">
    <label class="col-sm-2 control-label">'.$plugin_language['name'].'</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
		<input class="form-control" type="text" name="question" value="'.$question.'" size="97" /></em></span>
    </div>
  </div>



  <div class="mb-3 row row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'percent' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <input class="form-control" type="text" name="prozent" value="'.$prozent.'" size="97" /></em></span>
	</div>
  </div>

<div class="mb-3 row row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'status' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
						<select class="form-control" name="status">
             '.$status.'
            </select>
            </em></span>
	</div>
  </div>
<div class="mb-3 row row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'expected_completion' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <input id="bday" class="form-control" placeholder="yyyy-mm-dd" value="'.$date.'" name="date" type="date">
            (DD.MM.YY<i>, zum Bsp.: 27.04.10</i>)</em></span>
	</div>
  </div>


   <div class="mb-3 row row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <textarea class="ckeditor" id="ckeditor" name="message" rows="10" cols="" >'.$answer.'</textarea></em></span>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['banner'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input class="btn btn-info" name="banner" type="file" size="40" /> <small>(max. 1000x500)</small></em></span>
    </div>
  </div>

<div class="mb-3 row row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'is_displayed' ] . ':</label>
  <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
  <input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />
    </div>
  </div>

<div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" />
		<button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['new_project'].'</button>
    </div>
  </div>
  </div>
    </form></div>
  </div>';
#}
} elseif ($action == "edit") {
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

        $projectlistID = $_GET[ 'projectlistID' ];
        $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_projectlist` WHERE `projectlistID` = '$projectlistID'");
        $ds = mysqli_fetch_array($ergebnis);

        $linkscategory = safe_query("SELECT * FROM `" . PREFIX . "plugins_projectlist_categories` ORDER BY `sort`");
        $linkscats = '<select class="form-select" name="linkscat">';
        while ($dc = mysqli_fetch_array($linkscategory)) {
            $selected = '';
            if ($dc[ 'projectlistcatID' ] == $ds[ 'projectlistcatID' ]) {
                $selected = ' selected="selected"';
            }
            $linkscats .= '<option value="' . $dc[ 'projectlistcatID' ] . '"' . $selected . '>' . getinput($dc[ 'projectlistcatname' ]) .
                '</option>';
        }
        $linkscats .= '</select>';

        

        $status = '<option value="currently_under_construction">' . $plugin_language[ 'currently_under_construction' ] . '</option>
        				<option value="alpha_test">' . $plugin_language[ 'alpha_test' ] . '</option>
                <option value="beta_test">' . $plugin_language[ 'beta_test' ] . '</option>
                <option value="work_complete">' . $plugin_language[ 'work_complete' ] . '</option>';
            $status =
                str_replace('value="' . $ds['status'] . '"', 'value="' . $ds['status'] . '" selected="selected"', $status);

       $date = date("Y-m-d", $ds[ 'date' ]);

        if (!empty($ds[ 'banner' ])) {
        $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath . $ds[ 'banner' ] . '" alt="">';
    } else {
        $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath . 'no-image.jpg" alt="">';
    }

    if ($ds[ 'displayed' ] == '1') {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />';
    } else {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" />';
    }  


echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'projectlist' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_projectlist">' . $plugin_language[ 'projectlist' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['edit_project'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">';

   echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_projectlist" enctype="multipart/form-data">
    <div class="row">
	 <div class="mb-3 row row">
    <label class="col-sm-2 control-label">'.$plugin_language['category'].':</label>
    <div class="col-sm-8">
      '.$linkscats.'
    </div>
  </div>
  <div class="mb-3 row row">
    <label class="col-sm-2 control-label">'.$plugin_language['name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
		<input class="form-control" type="text" name="question" value="'.getinput($ds['question']).'" size="97" /></em></span>
    </div>
  </div>

<div class="mb-3 row row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'percent' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <input class="form-control" type="text" name="prozent" value="'.getinput($ds['prozent']).'" size="97" /></em></span>
	</div>
  </div>

<div class="mb-3 row row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'status' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
						<select class="form-control" name="status">
						'.$status.'
              
            </select>
            </em></span>
	</div>
  </div>
<div class="mb-3 row row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'expected_completion' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <input name="date" type="date" value="'.$date.'" placeholder="yyyy-mm-dd" class="form-control">
            (DD.MM.YY<i>, zum Bsp.: 27.04.10</i>)</em></span>
	</div>
  </div>

<div class="mb-3 row row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <textarea class="ckeditor" id="ckeditor" name="message" rows="10" cols="" >'.getinput($ds['answer']).'</textarea></em></span>
    </div>
  </div>


   <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['current_banner'].':</label>
    <div class="col-sm-10">
      '.$pic.'
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['banner'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input class="btn btn-info" name="banner" type="file" size="40" /> <small>(max. 1000x500)</small></em></span>
    </div>
  </div>

  <div class="mb-3 row row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'is_displayed' ] . ':</label>
  <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
  ' . $displayed . '
    </div>
  </div>

  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="projectlistID" value="'.$projectlistID.'" />
		<button class="btn btn-warning" type="submit" name="saveedit"  />'.$plugin_language['edit'].'</button>
    </div>
  </div>

  </div>
    </form></div>
  </div>';
	
} 
    


if (isset($_POST[ 'projectlist_categorys_save' ])) {

		$CAPCLASS = new \webspell\Captcha;
if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
	safe_query("INSERT INTO ".PREFIX."plugins_projectlist_categories ( projectlistcatname, description, sort ) values( '".$_POST['projectlistcatname']."','".$_POST['description']."', '1' ) ");
	
	$id = mysqli_insert_id($_database);

        $filepath = $plugin_path."images/rubriken/";

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation',true, true);
        
        $upload = new \webspell\HttpUpload('banner');

        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation =  getimagesize($upload->getTempFile());

                    if (is_array($imageInformation)) {
                        if ($imageInformation[0] < 1921 && $imageInformation[1] < 1081) {
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

                            if (file_exists($filepath . $id . '.gif')) {
                                unlink($filepath . $id . '.gif');
                            }
                            if (file_exists($filepath . $id . '.jpg')) {
                                unlink($filepath . $id . '.jpg');
                            }
                            if (file_exists($filepath . $id . '.png')) {
                                unlink($filepath . $id . '.png');
                            }

                            if ($upload->saveAs($filepath.$file)) {
                                @chmod($filepath.$file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_projectlist_categories
                                    SET banner='" . $file . "' WHERE projectlistcatID='" . $id . "'"
                                );
                            }
                        } else {
                            echo generateErrorBox(sprintf($plugin_language[ 'image_too_big' ], 1920, 1080));
                        }
                    } else {
                        echo generateErrorBox($plugin_language[ 'broken_image' ]);
                    }
                } else {
                    echo generateErrorBox($plugin_language[ 'unsupported_image_type' ]);
                }
            } else {
                echo  generateErrorBox($upload->translateError());
            }
        }
        } else {
        echo $plugin_language[ 'transaction_invalid' ];
}




/*$projectlistcatname = $_POST[ 'projectlistcatname' ];
    $description = $_POST[ 'message' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('projectlistcatname'))) {
            safe_query(
                "INSERT INTO
                    " . PREFIX . "plugins_projectlist_categorys (
                        projectlistcatname,
                        description,
                        sort
                    )
                    VALUES (
                        '$projectlistcatname',
                        '$description',
                        '1'
                    )"
            );
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }*/
  
} elseif (isset($_POST[ 'projectlist_categorys_saveedit' ])) { 
	$CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {

    $projectlistcatname = $_POST[ 'projectlistcatname' ];
    $description = $_POST[ 'description' ];
    
    		$projectlistcatID = (int)$_POST[ 'projectlistcatID' ];
        $id = $projectlistcatID;

        

        safe_query(
            "UPDATE
                `" . PREFIX . "plugins_projectlist_categories`
                SET
                    `projectlistcatname` = '" . $projectlistcatname . "',
                    `description` = '" . $description . "'
                WHERE
                    `projectlistcatID` = '" . $projectlistcatID . "'"
        );







	
	
$filepath = $plugin_path."/images/rubriken/";

        ///TODO: should be loaded from root language folder
        $_language->readModule('formvalidation', true, true);

        $upload = new \webspell\HttpUpload('banner');

        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation =  getimagesize($upload->getTempFile());

                    if (is_array($imageInformation)) {
                        if ($imageInformation[0] < 1921 && $imageInformation[1] < 1081) {
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

                            if (file_exists($filepath . $id . '.gif')) {
                                unlink($filepath . $id . '.gif');
                            }
                            if (file_exists($filepath . $id . '.jpg')) {
                                unlink($filepath . $id . '.jpg');
                            }
                            if (file_exists($filepath . $id . '.png')) {
                                unlink($filepath . $id . '.png');
                            }

                            if ($upload->saveAs($filepath.$file)) {
                                @chmod($filepath.$file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_projectlist_categories
                                    SET banner='" . $file . "' WHERE projectlistcatID='" . $id . "'"
                                );
                            }
                        } else {
                            echo generateErrorBox(sprintf($plugin_language[ 'image_too_big' ], 1920, 1080));
                        }
                    } else {
                        echo generateErrorBox($plugin_language[ 'broken_image' ]);
                    }
                } else {
                    echo generateErrorBox($plugin_language[ 'unsupported_image_type' ]);
                }
            } else {
                echo generateErrorBox($upload->translateError());
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }

/*$projectlistcatname = $_POST[ 'projectlistcatname' ];
    $description = $_POST[ 'message' ];
    $projectlistcatID = $_POST[ 'projectlistcatID' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('projectlistcatname'))) {
            safe_query(
                "UPDATE " . PREFIX .
                "plugins_projectlist_categorys SET projectlistcatname='$projectlistcatname', description='$description' WHERE projectlistcatID='$projectlistcatID' "
            );
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    } */

} elseif (isset($_GET[ 'projectlist_categorys_delete' ])) {  

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        safe_query("DELETE FROM " . PREFIX . "plugins_projectlist_categories WHERE projectlistcatID='" . $_GET[ 'projectlistcatID' ] . "'");
        safe_query("DELETE FROM " . PREFIX . "plugins_projectlist WHERE projectlistcatID='" . $_GET[ 'projectlistcatID' ] . "'");
    } else {
        print_r($plugin_language); return false;
        $_language->readModule('formvalidation', true);  
        echo $plugin_language[ 'transaction_invalid' ];
    }

}




if ($action == "admin_projectlist_categorys_add") {

    $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'links_categorys' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_projectlist">' . $plugin_language[ 'projectlist' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_projectlist&action=admin_projectlist_categorys">' . $plugin_language[ 'links_categorys' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['add_category'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">';

echo '<script language="JavaScript" type="text/javascript">
                    <!--
                        function chkFormular() {
                            if(!validbbcode(document.getElementById(\'message\').value, \'admin\')){
                                return false;
                            }
                        }
                    -->
                </script>';
    
    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_projectlist&action=admin_projectlist_categorys" id="post" name="post" enctype="multipart/form-data" onsubmit="return chkFormular();">
    <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="projectlistcatname" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
     <textarea class="ckeditor" id="ckeditor" rows="10" cols="" name="description"></textarea></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['banner'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input class="btn btn-info" name="banner" type="file" size="40" /> <small>(max. 1000x500)</small></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
     <input type="hidden" name="captcha_hash" value="'.$hash.'" /><button class="btn btn-success" type="submit" name="projectlist_categorys_save" />'.$plugin_language['add_category'].'</button>
    </div>
  </div>
    </form>
    </div>
  </div>';

} elseif ($action == "admin_projectlist_categorys_edit") {
    
    $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

        $projectlistcatID = $_GET[ 'projectlistcatID' ];
        $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_projectlist_categories` WHERE `projectlistcatID` = '$projectlistcatID'");
        $ds = mysqli_fetch_array($ergebnis);


        
        $filepath = $plugin_path."images/rubriken/";

        

        if (!empty($ds[ 'banner' ])) {
        $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath . $ds[ 'banner' ] . '" alt="">';
    } else {
        $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath . 'no-image.jpg" alt="">';
    }

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'links_categorys' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_projectlist">' . $plugin_language[ 'projectlist' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_projectlist&action=admin_projectlist_categorys">' . $plugin_language[ 'links_categorys' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['edit_category'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">';

echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_projectlist&action=admin_projectlist_categorys" enctype="multipart/form-data">
    <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="projectlistcatname" value="'.getinput($ds['projectlistcatname']).'" /></em></span>
    </div>
  </div>
 <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
     <textarea class="ckeditor" id="ckeditor" rows="10" cols="" name="description">'.getinput($ds['description']).'</textarea></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['current_banner'].':</label>
    <div class="col-sm-10">
      '.$pic.'
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['banner'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input class="btn btn-info" name="banner" type="file" size="40" /> <small>(max. 1000x500)</small></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="projectlistcatID" value="'.$projectlistcatID.'" />
		<button class="btn btn-warning" type="submit" name="projectlist_categorys_saveedit"  />'.$plugin_language['edit_category'].'</button>
    </div>
  </div>

    </form>
    </div>
  </div>';
    


} elseif ($action == "admin_projectlist_categorys") {

    echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'links_categorys' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_projectlist">' . $plugin_language[ 'projectlist' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_projectlist&action=admin_projectlist_categorys">' . $plugin_language[ 'links_categorys' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="mb-3 row row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_projectlist&action=admin_projectlist&action=admin_projectlist_categorys_add" class="btn btn-primary">' . $plugin_language[ 'new_category' ] . '</a>
    </div>
  </div>';


echo'<form method="post" action="admincenter.php?site=admin_projectlist&action=admin_projectlist_categorys">
  <table class="table table-striped">
    <thead>
      <th><b>'.$plugin_language['links_categorys'].'</b></th>
      <th width="" class="title"><b>' . $plugin_language['description'] . '</b></th>
      <th><b>'.$plugin_language['actions'].'</b></th>
      <th><b>'.$plugin_language['sort'].'</b></th>
    </thead>';

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_projectlist_categories ORDER BY sort");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(projectlistcatID) as cnt FROM " . PREFIX . "plugins_projectlist_categories"));
    $anz = $tmp[ 'cnt' ];

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
        
            $projectlistcatname = $ds[ 'projectlistcatname' ];
            $description = $ds[ 'description' ];

            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($projectlistcatname);
            $projectlistcatname = $translate->getTextByLanguage($projectlistcatname);

            $translate->detectLanguages($description);
            $description = $translate->getTextByLanguage($description);
            
            $data_array = array();
            $data_array['$projectlistcatname'] = $projectlistcatname;
            $data_array['$description'] = $description;
  
        echo '<tr>
            <td class="' . $td . '"><b>' . $projectlistcatname . '</b></td>
            <td class="' . $td . '">' . $description . '</td>
      <td><a href="admincenter.php?site=admin_projectlist&action=admin_projectlist_categorys_edit&amp;projectlistcatID='.$ds['projectlistcatID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_projectlist&action=admin_projectlist_categorys&amp;projectlist_categorys_delete=true&amp;projectlistcatID='.$ds['projectlistcatID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'links_categorys' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete_cat'] . '</p>
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
      <td><select name="sortlinkscat[]">';
        
    for ($n = 1; $n <= $anz; $n++) {
            if ($ds[ 'sort' ] == $n) {
                echo '<option value="' . $ds[ 'projectlistcatID' ] . '-' . $n . '" selected="selected">' . $n . '</option>';
            } else {
                echo '<option value="' . $ds[ 'projectlistcatID' ] . '-' . $n . '">' . $n . '</option>';
            }
        }
    
        echo'</select></td>
    </tr>';
    
    $i++;
    }
    
    echo'<tr>
      <td class="td_head" colspan="4" align="right"><input type="hidden" name="captcha_hash" value="'.$hash.'" /><input class="btn btn-primary" type="submit" name="sortieren" value="'.$plugin_language['to_sort'].'" /></td>
    </tr>
  </table>
  </form>';
#}
echo '</div></div>';

} elseif ($action == "admin_projectlist_settings") {

 
    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_projectlist_settings");
    $ds = mysqli_fetch_array($settings);

    
  $maxshownlinks = $ds[ 'projectlist' ];
if (empty($maxshownlinks)) {
    $maxshownlinks = 10;
}

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    
echo'<form method="post" action="admincenter.php?site=admin_projectlist&action=admin_projectlist_settings">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-card-list"></i> '.$plugin_language[ 'settings' ].'
            </div>

            <div class="card-body">


            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_projectlist">' . $plugin_language[ 'projectlist' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_projectlist&action=admin_projectlist_settings">' . $plugin_language[ 'settings' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  

                
                <div class="row">
                    <div class="col-md-6">
                        

                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['max_links'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_1' ].'"><input class="form-control" type="text" name="projectlist" value="'.$ds['projectlist'].'" size="35"></em></span>
                            </div>
                        </div>

                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['max_content'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_2' ].'"><input class="form-control" type="text" name="projectlistchars" value="'.$ds['projectlistchars'].'" size="35"></em></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        
                    </div>
               </div>
                <br>
             <div class="mb-3 row">
             <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" name="captcha_hash" value="'.$hash.'"> 
            <button class="btn btn-primary" type="submit" name="links_settings_save">'.$plugin_language['update'].'</button>
            </div></div>

            </div>
            </div>
    </form>';


} elseif ($action == "") {    

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i>  ' . $plugin_language[ 'title' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_projectlist">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="mb-3 row row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_projectlist&amp;action=add" class="btn btn-primary">' . $plugin_language[ 'new_entry' ] . '</a>
      <a href="admincenter.php?site=admin_projectlist&action=admin_projectlist_categorys" class="btn btn-primary">' . $plugin_language[ 'new_category' ] . '</a>
      <a href="admincenter.php?site=admin_projectlist&action=admin_projectlist_settings" class="btn btn-primary" type="button">' . $plugin_language[ 'settings' ] . '</a>
    </div>
  </div>';


    echo'<form method="post" action="admincenter.php?site=admin_projectlist">
  <table class="table table-striped">
    <thead>
      <th width="" class="title"><b>' . $plugin_language[ 'projectlist' ] . '</b></th>
      <th width="" class="title"><b>' . $plugin_language['name'] . '</b></th>
      <th width="15%" class="title"><b>' . $plugin_language[ 'is_displayed' ] . '</b></th>
      <th width="20%" class="title"><b>' . $plugin_language['actions'] . '</b></th>
      <th width="8%" class="title"><b>' . $plugin_language['sort'] . '</b></th>
    </thead>';

	$ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_projectlist_categories` ORDER BY `sort`");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(projectlistcatID) as cnt FROM `" . PREFIX . "plugins_projectlist_categories`"));
    $anz = $tmp[ 'cnt' ];

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    while ($ds = mysqli_fetch_array($ergebnis)) {

            $projectlistcatname = $ds[ 'projectlistcatname' ];
            $description = $ds[ 'description' ];
            
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($projectlistcatname);
            $projectlistcatname = $translate->getTextByLanguage($projectlistcatname);

            $translate->detectLanguages($description);
            $description = $translate->getTextByLanguage($description);
            
            $data_array = array();
            $data_array['$projectlistcatname'] = $projectlistcatname;
            $data_array['$description'] = $description;


        echo '<tr>
            <td class="td_head">
                <b>' . $projectlistcatname . '</b></td><td class="td_head" colspan="4">
                <small>' . $description . '</small>
            </td>
        </tr>';

       $links = safe_query("SELECT * FROM `" . PREFIX . "plugins_projectlist` WHERE `projectlistcatID` = $ds[projectlistcatID] ORDER BY `sort`");
        $tmp = mysqli_fetch_assoc(
            safe_query(
                "SELECT count(projectlistID) as cnt FROM `" . PREFIX . "plugins_projectlist` WHERE `projectlistcatID` = $ds[projectlistcatID]"
            )
        );
        $anzlinks = $tmp[ 'cnt' ];

        $i = 1;
        while ($db = mysqli_fetch_array($links)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }

             $db[ 'displayed' ] == 1 ?
            $displayed = '<font color="green"><b>' . $plugin_language[ 'yes' ] . '</b></font>' :
            $displayed = '<font color="red"><b>' . $plugin_language[ 'no' ] . '</b></font>'; 

            $question = $db[ 'question' ];
            
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($question);
            $question = $translate->getTextByLanguage($question);
            
            $data_array = array();
            $data_array['$question'] = $question;


            echo '<tr>
        <td colspan="2"><b>- '.$question.'</b></td>
        <td>' . $displayed . '</td>
        <td><a href="admincenter.php?site=admin_projectlist&amp;action=edit&amp;projectlistID=' . $db[ 'projectlistID' ] . '" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_projectlist&amp;delete=true&amp;projectlistID='.$db['projectlistID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'title' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete_project'] . '</p>
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
        <td><select name="sortlinks[]">';
            for ($j = 1; $j <= $anzlinks; $j++) {
                if ($db[ 'sort' ] == $j) {
                    echo '<option value="' . $db[ 'projectlistID' ] . '-' . $j . '" selected="selected">' . $j .
                    '</option>';
                } else {
                    echo '<option value="' . $db[ 'projectlistID' ] . '-' . $j . '">' . $j . '</option>';
                }
            }
            echo '</select></td></tr>';
      
      $i++;
		}
        
	}

	echo'<tr>
      <td class="td_head" colspan="5" align="right"><input type="hidden" name="captcha_hash" value="'.$hash.'" />
      <button class="btn btn-primary" type="submit" name="sortieren" />'.$plugin_language['to_sort'].'</button></td>
    </tr>
  </table>
  </form>';
}
echo '</div></div>';

?>