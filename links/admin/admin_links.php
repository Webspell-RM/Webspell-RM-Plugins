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
$plugin_language = $pm->plugin_language("admin_links", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='links'");
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

   $filepath = $plugin_path."images/";
 

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
    
        $filepath = $plugin_path."images/";
        if (file_exists($filepath . $_GET['linkID'] . '.gif')) {
            @unlink($filepath . $_GET['linkID'] . '.gif');
        }
        if (file_exists($filepath . $_GET['linkID'] . '.jpg')) {
            @unlink($filepath . $_GET['linkID'] . '.jpg');
        }
        if (file_exists($filepath . $_GET['linkID'] . '.png')) {
            @unlink($filepath . $_GET['linkID'] . '.png');
        }

        safe_query("DELETE FROM " . PREFIX . "plugins_links WHERE linkID='" . $_GET[ "linkID" ] . "'");


            redirect("admincenter.php?site=admin_links", "", 0);
    }
   

} elseif (isset($_POST[ 'sortieren' ])) {
    $sortlinks = $_POST[ 'sortlinks' ];

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (is_array($sortlinks)) {
            foreach ($sortlinks as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE `" . PREFIX . "plugins_links` SET `sort` = '$sorter[1]' WHERE `linkID` = '" . $sorter[0] . "'");
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
        $url = $_POST[ "url" ];


        if (isset($_POST[ "displayed" ])) {
            $displayed = 1;
        } else {
            $displayed = 0;
        }
        
        
        safe_query(
            "INSERT INTO
                `" . PREFIX . "plugins_links` (
                        `linkcatID`,
                        `date`,
                        `question`,
                        `answer`,
                        `poster`,
                        `url`,
                        `displayed`,
                        `sort`
                    )
                VALUES (
                    '$linkscat',
                    '" . time() . "',
                    '$question',
                    '$answer',
                    '" . $userID . "',
                    '" . $url . "',
                    '" . $displayed . "',
                    '1'


                )"
        );
        $id = mysqli_insert_id($_database);
        \webspell\Tags::setTags('links', $id, $_POST[ 'tags' ]);

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
                                    "UPDATE " . PREFIX . "plugins_links
                                    SET banner='" . $file . "' WHERE linkID='" . $id . "'"
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
    $linkID = $_POST[ 'linkID' ];
    $url = $_POST[ 'url' ];


        if (isset($_POST[ "displayed" ])) {
            $displayed = 1;
        } else {
            $displayed = 0;
        }
        

        $linkID = (int)$_POST[ 'linkID' ];
        $id = $linkID;

        

        safe_query(
            "UPDATE
                `" . PREFIX . "plugins_links`
                SET
                    `linkcatID` = '" . $linkscat . "',
                    `date` = '" . time() . "',
                    `question` = '" . $question . "',
                    `answer` = '" . $answer . "',
                    `url` = '" . $url . "',
                    `poster` = '" . $userID . "',
                    `displayed` = '" . $displayed . "'
                WHERE
                    `linkID` = '" . $linkID . "'"
        );

        \webspell\Tags::setTags('links', $id, $_POST[ 'tags' ]);

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
                                    "UPDATE " . PREFIX . "plugins_links
                                    SET banner='" . $file . "' WHERE linkID='" . $id . "'"
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
                " . PREFIX . "plugins_links_settings
            SET
                links='" . $_POST[ 'links' ] . "',
                linkchars='" . $_POST[ 'linkchars' ] . "' "
        );
        
        redirect("admincenter.php?site=admin_links&action=admin_links_settings", "", 0);
    } else {
        redirect("admincenter.php?site=admin_links&action=admin_links_settings", $plugin_language[ 'transaction_invalid' ], 3);
    }
}

if ($action == "add") {

        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

        $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_links_categories` ORDER BY `sort`");
        $linkscats = '<select class="form-control" name="linkscat">';
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $linkscats .= '<option value="' . $ds[ 'linkcatID' ] . '">' . getinput($ds[ 'linkcatname' ]) . '</option>';
        }
        $linkscats .= '</select>';

        if (isset($_GET[ 'answer' ])) {
            echo '<span style="color: red">' . $plugin_language[ 'no_category_selected' ] . '</span>';
            $question = $_GET[ 'question' ];
            $answer = $_GET[ 'answer' ];
        } else {
            $question = "";
            $answer = "";
        }        

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'links' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_links">' . $plugin_language[ 'links' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['add_links'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">';


    echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_links" enctype="multipart/form-data">
     <div class="row">
	 <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category'].'</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      '.$linkscats.'</em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['name'].'</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
		<input class="form-control" type="text" name="question" value="'.$question.'" size="97" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['tags'].'</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
		<input class="form-control" type="text" name="tags" value="" size="97" /></em></span>
    </div>
  </div>
   <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <textarea class="ckeditor" id="ckeditor" name="message" rows="10" cols="" >'.$answer.'</textarea></em></span>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['homepage'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input type="url" name="url" class="form-control" id="input-url" value=""></em></span>
    </div>
  </div>

   <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['banner'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input class="btn btn-info" name="banner" type="file" size="40" /> <small>(max. 1000x500)</small></em></span>
    </div>
  </div>

<div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'is_displayed' ] . ':</label>
  <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
  <input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />
    </div>
  </div>

<div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" />
		<button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['add_links'].'</button>
    </div>
  </div>
  </div>
    </form></div>
  </div>';

} elseif ($action == "edit") {
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

        $linkID = $_GET[ 'linkID' ];
        $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_links` WHERE `linkID` = '$linkID'");
        $ds = mysqli_fetch_array($ergebnis);

        $linkscategory = safe_query("SELECT * FROM `" . PREFIX . "plugins_links_categories` ORDER BY `sort`");
        $linkscats = '<select class="form-select" name="linkscat">';
        while ($dc = mysqli_fetch_array($linkscategory)) {
            $selected = '';
            if ($dc[ 'linkcatID' ] == $ds[ 'linkcatID' ]) {
                $selected = ' selected="selected"';
            }
            $linkscats .= '<option value="' . $dc[ 'linkcatID' ] . '"' . $selected . '>' . getinput($dc[ 'linkcatname' ]) .
                '</option>';
        }
        $linkscats .= '</select>';

        $tags = \webspell\Tags::getTags('links', $linkID);        

        $url = htmlspecialchars($ds[ 'url' ]);

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
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'links' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_links">' . $plugin_language[ 'links' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['edit_links'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">';

   echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_links" enctype="multipart/form-data">
    <div class="row">
	 <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      '.$linkscats.'
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
		<input class="form-control" type="text" name="question" value="'.getinput($ds['question']).'" size="97" /></em></span>
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'tags' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
		<input class="form-control" type="text" name="tags" value="' . $tags . '" size="97" /></em></span>
	</div>
  </div>

<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <textarea class="ckeditor" id="ckeditor" name="message" rows="10" cols="" >'.getinput($ds['answer']).'</textarea></em></span>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['homepage'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input type="text" class="form-control" name="url" value="'.getinput($ds['url']).'" /></em></span>
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
    <label class="col-sm-2 control-label">' . $plugin_language[ 'is_displayed' ] . ':</label>
  <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
  ' . $displayed . '
    </div>
  </div>

  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="linkID" value="'.$linkID.'" />
		<button class="btn btn-warning" type="submit" name="saveedit"  />'.$plugin_language['edit_links'].'</button>
    </div>
  </div>

  </div>
    </form></div>
  </div>';
	
}

if (isset($_POST[ 'links_categorys_save' ])) {

$linkcatname = $_POST[ 'linkcatname' ];
    $description = $_POST[ 'message' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('linkcatname'))) {
            safe_query(
                "INSERT INTO
                    " . PREFIX . "plugins_links_categories (
                        linkcatname,
                        description,
                        sort
                    )
                    VALUES (
                        '$linkcatname',
                        '$description',
                        '1'
                    )"
            );
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
  
} elseif (isset($_POST[ 'links_categorys_saveedit' ])) { 

$linkcatname = $_POST[ 'linkcatname' ];
    $description = $_POST[ 'message' ];
    $linkcatID = $_POST[ 'linkcatID' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('linkcatname'))) {
            safe_query(
                "UPDATE " . PREFIX .
                "plugins_links_categories SET linkcatname='$linkcatname', description='$description' WHERE linkcatID='$linkcatID' "
            );
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    } 

} elseif (isset($_GET[ 'links_categorys_delete' ])) {  

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        


        $ds = mysqli_fetch_array(
        safe_query(
            "SELECT
                `linkID`
            FROM
                `" . PREFIX . "plugins_links`
            WHERE
                `linkcatID` = '" . (int)$_GET['linkcatID'] . "'"
        )
    );

    
    $ergebnis = safe_query(
                    "SELECT linkID FROM " . PREFIX . "plugins_links WHERE linkcatID='" .
                    $_GET[ 'linkcatID' ] . "'"
                );
    while ($ds = mysqli_fetch_array($ergebnis)) {
    
        $filepath = $plugin_path."images/";
        if (file_exists($filepath . $ds[ 'linkID' ] . '.gif')) {
            @unlink($filepath . $ds[ 'linkID' ] . '.gif');
        }
        if (file_exists($filepath . $ds[ 'linkID' ] . '.jpg')) {
            @unlink($filepath . $ds[ 'linkID' ] . '.jpg');
        }
        if (file_exists($filepath . $ds[ 'linkID' ] . '.png')) {
            @unlink($filepath . $ds[ 'linkID' ] . '.png');
        }
    }

        safe_query("DELETE FROM " . PREFIX . "plugins_links_categories WHERE linkcatID='" . $_GET[ 'linkcatID' ] . "'");
        safe_query("DELETE FROM " . PREFIX . "plugins_links WHERE linkcatID='" . $_GET[ 'linkcatID' ] . "'");

    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }

}


if ($action == "admin_links_categorys_add") {

    $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'links_categorys' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_links">' . $plugin_language[ 'links' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_links&action=admin_links_categorys">' . $plugin_language[ 'links_categorys' ] . '</a></li>
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
    
    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_links&action=admin_links_categorys" id="post" name="post" enctype="multipart/form-data" onsubmit="return chkFormular();">
    <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="linkcatname" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
     <textarea class="ckeditor" id="ckeditor" rows="10" cols="" name="message"></textarea></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
     <input type="hidden" name="captcha_hash" value="'.$hash.'" /><button class="btn btn-success" type="submit" name="links_categorys_save" />'.$plugin_language['add_category'].'</button>
    </div>
  </div>
    </form>
    </div>
  </div>';

} elseif ($action == "admin_links_categorys_edit") {
    $linkcatID = $_GET[ 'linkcatID' ];

        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_links_categories WHERE linkcatID='$linkcatID'");
        $ds = mysqli_fetch_array($ergebnis);

        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'links_categorys' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_links">' . $plugin_language[ 'links' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_links&action=admin_links_categorys">' . $plugin_language[ 'links_categorys' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['edit_category'].'</li>
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
    
    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_links&action=admin_links_categorys" id="post" name="post" onsubmit="return chkFormular();">
    <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="linkcatname" value="'.getinput($ds['linkcatname']).'" /></em></span>
    </div>
  </div>
 <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
     <textarea class="ckeditor" id="ckeditor" rows="10" cols="" name="message">'.getinput($ds['description']).'</textarea></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
     <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="linkcatID" value="'.$linkcatID.'" /><button class="btn btn-success" type="submit" name="links_categorys_saveedit" />'.$plugin_language['edit_category'].'</button>
    </div>
  </div>
    </form>
    </div>
  </div>';

} elseif ($action == "admin_links_categorys") {

    echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'links_categorys' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_links">' . $plugin_language[ 'links' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_links&action=admin_links_categorys">' . $plugin_language[ 'links_categorys' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="mb-3 row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_links&action=admin_links&action=admin_links_categorys_add" class="btn btn-primary">' . $plugin_language[ 'new_category' ] . '</a>
    </div>
  </div>';


echo'<form method="post" action="admincenter.php?site=admin_links&action=admin_links_categorys">
  <table class="table table-striped">
    <thead>
      <th><b>'.$plugin_language['links_categorys'].'</b></th>
      <th width="" class="title"><b>' . $plugin_language['description'] . '</b></th>
      <th><b>'.$plugin_language['actions'].'</b></th>
      <th><b>'.$plugin_language['sort'].'</b></th>
    </thead>';

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_links_categories ORDER BY sort");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(linkcatID) as cnt FROM " . PREFIX . "plugins_links_categories"));
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
        
            $linkcatname = $ds[ 'linkcatname' ];
            $description = $ds[ 'description' ];

            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($linkcatname);
            $linkcatname = $translate->getTextByLanguage($linkcatname);

            $translate->detectLanguages($description);
            $description = $translate->getTextByLanguage($description);
            
            $data_array = array();
            $data_array['$linkcatname'] = $linkcatname;
            $data_array['$description'] = $description;
  
        echo '<tr>
            <td class="' . $td . '"><b>' . $linkcatname . '</b></td>
            <td class="' . $td . '">' . $description . '</td>
      <td><a href="admincenter.php?site=admin_links&action=admin_links_categorys_edit&amp;linkcatID='.$ds['linkcatID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_links&action=admin_links_categorys&amp;links_categorys_delete=true&amp;linkcatID='.$ds['linkcatID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'links_categorys' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete_cat'] . '</p>
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
      <td><select name="sortlinkscat[]">';
        
    for ($n = 1; $n <= $anz; $n++) {
            if ($ds[ 'sort' ] == $n) {
                echo '<option value="' . $ds[ 'linkcatID' ] . '-' . $n . '" selected="selected">' . $n . '</option>';
            } else {
                echo '<option value="' . $ds[ 'linkcatID' ] . '-' . $n . '">' . $n . '</option>';
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

echo '</div></div>';

} elseif ($action == "admin_links_settings") {
 
    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_links_settings");
    $ds = mysqli_fetch_array($settings);
    
  $maxshownlinks = $ds[ 'links' ];
if (empty($maxshownlinks)) {
    $maxshownlinks = 10;
}

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    
echo'<form method="post" action="admincenter.php?site=admin_links&action=admin_links_settings">
        <div class="card">
            <div class="card-header">
                '.$plugin_language[ 'settings' ].'
            </div>

            <div class="card-body">


            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_links">' . $plugin_language[ 'links' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_links&action=admin_links_settings">' . $plugin_language[ 'settings' ] . '</a></li>
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
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_1' ].'"><input class="form-control" type="text" name="links" value="'.$ds['links'].'" size="35"></em></span>
                            </div>
                        </div>

                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['max_content'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_2' ].'"><input class="form-control" type="text" name="linkchars" value="'.$ds['linkchars'].'" size="35"></em></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        
                    </div>
               </div>
                <br>
             <div class="mb-3">
            <input type="hidden" name="captcha_hash" value="'.$hash.'"> 
            <button class="btn btn-primary" type="submit" name="links_settings_save">'.$plugin_language['update'].'</button>
            </div>

            </div>
            </div>
    </form>';

} elseif ($action == "") {    

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-link"></i> ' . $plugin_language[ 'title' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_links">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="mb-3 row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_links&amp;action=add" class="btn btn-primary">' . $plugin_language[ 'new_links' ] . '</a>
      <a href="admincenter.php?site=admin_links&action=admin_links_categorys" class="btn btn-primary">' . $plugin_language[ 'new_category' ] . '</a>
      <a href="admincenter.php?site=admin_links&action=admin_links_settings" class="btn btn-primary" type="button">' . $plugin_language[ 'settings' ] . '</a>
    </div>
  </div>';


    echo'<form method="post" action="admincenter.php?site=admin_links">
  <table class="table table-striped">
    <thead>
      <th width="" class="title"><b>' . $plugin_language['links'] . '</b></th>
      <th width="" class="title"><b>' . $plugin_language['name'] . '</b></th>
      <th width="15%" class="title"><b>' . $plugin_language[ 'is_displayed' ] . '</b></th>
      <th width="20%" class="title"><b>' . $plugin_language['actions'] . '</b></th>
      <th width="8%" class="title"><b>' . $plugin_language['sort'] . '</b></th>
    </thead>';

	$ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_links_categories` ORDER BY `sort`");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(linkcatID) as cnt FROM `" . PREFIX . "plugins_links_categories`"));
    $anz = $tmp[ 'cnt' ];

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    while ($ds = mysqli_fetch_array($ergebnis)) {

            $linkcatname = $ds[ 'linkcatname' ];
            $description = $ds[ 'description' ];
            
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($linkcatname);
            $linkcatname = $translate->getTextByLanguage($linkcatname);

            $translate->detectLanguages($description);
            $description = $translate->getTextByLanguage($description);
            
            $data_array = array();
            $data_array['$linkcatname'] = $linkcatname;
            $data_array['$description'] = $description;


        echo '<tr>
            <td class="td_head">
                <b>' . $linkcatname . '</b></td><td class="td_head" colspan="4">
                <small>' . $description . '</small>
            </td>
        </tr>';

       $links = safe_query("SELECT * FROM `" . PREFIX . "plugins_links` WHERE `linkcatID` = $ds[linkcatID] ORDER BY `sort`");
        $tmp = mysqli_fetch_assoc(
            safe_query(
                "SELECT count(linkID) as cnt FROM `" . PREFIX . "plugins_links` WHERE `linkcatID` = $ds[linkcatID]"
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
        <td><a href="admincenter.php?site=admin_links&amp;action=edit&amp;linkID=' . $db[ 'linkID' ] . '" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_links&amp;delete=true&amp;linkID='.$db['linkID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'title' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete_links'] . '</p>
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
        <td><select name="sortlinks[]">';
            for ($j = 1; $j <= $anzlinks; $j++) {
                if ($db[ 'sort' ] == $j) {
                    echo '<option value="' . $db[ 'linkID' ] . '-' . $j . '" selected="selected">' . $j .
                    '</option>';
                } else {
                    echo '<option value="' . $db[ 'linkID' ] . '-' . $j . '">' . $j . '</option>';
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