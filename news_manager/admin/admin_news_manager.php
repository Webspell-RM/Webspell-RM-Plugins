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
\------------------------------------------------------------------*/

# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("admin_news", $plugin_path);

// -- COMMENTS INFORMATION -- //
include_once('./includes/plugins/news_manager/news_functions.php');
#print_r($_SERVER['DOCUMENT_ROOT']);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='news'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}

$filepath = $plugin_path."images/news-pic/";

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

# admin_links
if (isset($_POST[ 'submit' ])) {

    #================================== screen Anfang ===============================
$_language->readModule('formvalidation', true);

    $screen = new \webspell\HttpUpload('screen');

    if ($screen->hasFile()) {
        if ($screen->hasError() === false) {
            $file = $_POST[ "newsID" ] . '_' . time() . "." .$screen->getExtension();
            $new_name = $filepath . $file;
            if ($screen->saveAs($new_name)) {
                @chmod($new_name, $new_chmod);
                $ergebnis = safe_query("SELECT screens FROM " . PREFIX . "plugins_news WHERE newsID='" . $_POST[ "newsID" ]."'");
                $dx = mysqli_fetch_array($ergebnis);
                $screens = explode('|', $dx[ 'screens' ]);
                $screens[ ] = $file;
                $screens_string = implode('|', $screens);

                $ergebnis = safe_query(
                    "UPDATE
                    " . PREFIX . "plugins_news
                    SET
                        screens='" . $screens_string . "'
                    
                WHERE `newsID` = '" . $_POST[ "newsID" ] . "'"
                );
            }
        }
    }
    header("Location: admincenter.php?site=admin_news_manager&action=edit&newsID=" . $_POST[ "newsID" ] . "");



#================================== screen Ende ===============================

    

} elseif (isset($_POST[ 'subadd' ])) {
    #================================== screen Anfang ===============================
    $_language->readModule('formvalidation', true);

    $screen = new \webspell\HttpUpload('screen');


    if ($screen->hasFile()) {
        if ($screen->hasError() === false) {
            $file = $_POST[ "newsID" ] . '_' . time() . "." .$screen->getExtension();
            $new_name = $filepath . $file;
            if ($screen->saveAs($new_name)) {
                @chmod($new_name, $new_chmod);
                $ergebnis = safe_query("SELECT screens FROM " . PREFIX . "plugins_news WHERE newsID='" . $_POST[ "newsID" ]."'");
                $ds = mysqli_fetch_array($ergebnis);
                $screens = explode('|', $ds[ 'screens' ]);
                $screens[ ] = $file;
                $screens_string = implode('|', $screens);
$CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {

        safe_query(
        "INSERT INTO
            " . PREFIX . "plugins_news (
                screens,
                date,
                poster
            )
            VALUES (
            '" . $screens_string . "',
                '" . time() . "',
                '" . $userID . "'
                )"
    );
    $newsID = mysqli_insert_id($_database);



            }
        }
   }
}
    header("Location: admincenter.php?site=admin_news_manager&action=edit&newsID=" . $newsID . "");



#================================== screen Ende ===============================



    
# News add
 }elseif (isset($_POST[ "news_save" ])) { 

    $filepath = $plugin_path."images/news-pic/";
 
    $headline = $_POST[ "headline" ];
    $content = $_POST[ "message" ];
    $content = str_replace('\r\n', "\n", $content);
    
    $link1 = strip_tags($_POST[ 'link1' ]);
    $url1 = strip_tags($_POST[ 'url1' ]);
    
    if (isset($_POST[ "window1" ])) {
        $window1 = 1;
    } else {
        $window1 = 0;
    }
    if (!$window1) {
        $window1 = 0;
    }

    $link2 = strip_tags($_POST[ 'link2' ]);
    $url2 = strip_tags($_POST[ 'url2' ]);
    
    if (isset($_POST[ "window2" ])) {
        $window2 = 1;
    } else {
        $window2 = 0;
    }
    if (!$window2) {
        $window2 = 0;
    }


    if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    if (!$displayed) {
        $displayed = 0;
    }

    if (isset($_POST[ 'rubric' ])) {
        $rubric = $_POST[ 'rubric' ];
    } else {
        $rubric = 0;
    }

    $comments = $_POST[ 'comments' ];

$CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "INSERT INTO
                    `" . PREFIX . "plugins_news` (
                    `rubric`,
                    `date`,
                    `poster`,
                    `headline`,
                    `content`,
                    `link1`,
                    `url1`,
                    `window1`,
                    `link2`,
                    `url2`,
                    `window2`,
                    `displayed`,
                    `comments`
                )
                VALUES (
                    '" . $rubric . "',
                    '" . time() . "',
                    '" . $userID . "',
                    '" . $headline . "',
                    '" . $content . "',
                    '" . $link1 . "',
                    '" . $url1 . "',
                    '" . $window1 . "',
                    '" . $link2 . "',
                    '" . $url2 . "',
                    '" . $window2 . "',
                    '" . $displayed . "',
                    '" . $comments . "'
                )"
        );
    
    $id = mysqli_insert_id($_database);
    \webspell\Tags::setTags('news', $id, $_POST[ 'tags' ]);
    generate_rss2();

        $upload = new \webspell\HttpUpload('screen');
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
 
                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_news SET screens='" . $file . "' WHERE newsID='" . $id . "'"
                            );
                        }
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

    $rubrics = '';
    $newsrubrics = safe_query("SELECT rubricID, rubric FROM " . PREFIX . "plugins_news_rubrics ORDER BY rubric");
    while ($dr = mysqli_fetch_array($newsrubrics)) {
        $rubrics .= '<option value="' . $dr[ 'rubricID' ] . '">' . $dr[ 'rubric' ] . '</option>';
    }

}
}elseif (isset($_POST[ "saveedit" ])) { 

    $filepath = $plugin_path."images/news-pic/";

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $newsID = $_POST[ 'newsID' ];
    $headline = $_POST[ "headline" ];
    $content = $_POST[ "message" ];

    if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    if (!$displayed) {
        $displayed = 0;
    }

    $date = "";

    if (isset($_POST[ 'rubric' ])) {
        $rubric = $_POST[ 'rubric' ];
    } else {
        $rubric = 0;
    }

    $link1 = strip_tags($_POST[ 'link1' ]);
    $url1 = strip_tags($_POST[ 'url1' ]);
    
    if (isset($_POST[ "window1" ])) {
        $window1 = 1;
    } else {
        $window1 = 0;
    }
    if (!$window1) {
        $window1 = 0;
    }

    $link2 = strip_tags($_POST[ 'link2' ]);
    $url2 = strip_tags($_POST[ 'url2' ]);
    
    if (isset($_POST[ "window2" ])) {
        $window2 = 1;
    } else {
        $window2 = 0;
    }
    if (!$window2) {
        $window2 = 0;
    }

    $comments = $_POST[ 'comments' ];

    $date = strtotime($_POST['date']);
    


            $ergebnis = safe_query(
                "UPDATE
                    `" . PREFIX . "plugins_news`
                SET
                    `rubric` = '" . $rubric . "',
                    `headline` = '" . $headline . "',
                    `date` = '" . $date . "',
                    `content`='" . $_POST[ 'message' ] . "',
                    `link1`='" . $link1 . "',
                    `url1`='" . $url1 . "',
                    `window1`='" . $window1 . "',
                    `link2`='" . $link2 . "',
                    `url2`='" . $url2 . "',
                    `window2`='" . $window2 . "',
                    `displayed`= '" . $displayed . "',
                    `comments`='" . $comments . "'
                WHERE `newsID` = '" . $_POST[ "newsID" ] . "'"
            );

            \webspell\Tags::setTags('news', $newsID, $_POST[ 'tags' ]);
            

  generate_rss2();
  header("Location: admincenter.php?site=admin_news_manager");
 
}

}










# admin_links_categories

if (isset($_POST[ 'news_categories_save' ])) {
$filepath = $plugin_path."images/news-rubrics/";
$CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('name'))) {
            safe_query("INSERT INTO " . PREFIX . "plugins_news_rubrics ( rubric ) values( '" . $_POST[ 'name' ] . "' ) ");
            $id = mysqli_insert_id($_database);

            

            $errors = array();

            //TODO: should be loaded from root language folder
            $_language->readModule('formvalidation', true);

            $upload = new \webspell\HttpUpload('pic');
            if ($upload->hasFile()) {
                if ($upload->hasError() === false) {
                    $mime_types = array('image/jpeg','image/png','image/gif');

                    if ($upload->supportedMimeType($mime_types)) {
                        $imageInformation = getimagesize($upload->getTempFile());

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
                            $file = $id . $endung;

                            if ($upload->saveAs($filepath . $file, true)) {
                                @chmod($filepath . $file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_news_rubrics
                                    SET pic='" . $file . "' WHERE rubricID='" . $id . "'"
                                );
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
            if (count($errors)) {
                $errors = array_unique($errors);
                echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
            }
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
    

} elseif (isset($_POST[ 'news_categories_saveedit' ])) {


$filepath = $plugin_path."images/news-rubrics/";
$CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('name'))) {
            safe_query(
                "UPDATE
                    `" . PREFIX . "plugins_news_rubrics`
                SET
                    `rubric` = '" . $_POST[ 'name' ] . "'
                WHERE
                    `rubricID` = '" . $_POST[ 'rubricID' ] . "'"
            );

            $id = $_POST[ 'rubricID' ];
            

            $errors = array();

            //TODO: should be loaded from root language folder
            $_language->readModule('formvalidation', true);

            $upload = new \webspell\HttpUpload('pic');
            if ($upload->hasFile()) {
                if ($upload->hasError() === false) {
                    $mime_types = array('image/jpeg','image/png','image/gif');

                    if ($upload->supportedMimeType($mime_types)) {
                        $imageInformation = getimagesize($upload->getTempFile());

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
                            $file = $id . $endung;

                            if ($upload->saveAs($filepath . $file, true)) {
                                @chmod($filepath . $file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_news_rubrics
                                    SET pic='" . $file . "' WHERE rubricID='" . $id . "'"
                                );
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
            if (count($errors)) {
                $errors = array_unique($errors);
                echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
            }
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
   
} elseif (isset($_POST[ 'news_settings_save' ])) {  

   
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE
                " . PREFIX . "plugins_news_settings
            SET
                
                admin_news='" . $_POST[ 'admin_news' ] . "',
                news='" . $_POST[ 'news' ] . "',
                newsarchiv='" . $_POST[ 'newsarchiv' ] . "',
                headlines='" . $_POST[ 'headlines' ] . "',
                newschars='" . $_POST[ 'newschars' ] . "',
                headlineschars='" . $_POST[ 'headlineschars' ] . "',
                topnewschars='" . $_POST[ 'topnewschars' ] . "',
                feedback='" . $_POST[ 'feedback' ] . "',
                switchen='" . $_POST[ 'switchen' ] . "' "
        );
        
        redirect("admincenter.php?site=admin_news_manager&action=admin_news_settings", "", 0);
    } else {
        redirect("admincenter.php?site=admin_news_manager&action=admin_news_settings", $plugin_language[ 'transaction_invalid' ], 3);
    }


} elseif (isset($_GET[ 'delete' ])) {
    
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {

        $newsID = $_GET[ 'newsID' ];
        $dg = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_news WHERE newsID='" . $_GET[ 'newsID' ] . "'"));
        $screens = array();
        if (!empty($dg[ 'screens' ])) {
            $screens = explode("|", $dg[ 'screens' ]);
            foreach ($screens as $screen) {
                if ($screen != "") {   
                    if (file_exists($filepath . $screen . '')) {
                        @unlink($filepath . $screen . '');
                    }
                }
            }
        }
        safe_query("DELETE FROM " . PREFIX . "plugins_news WHERE newsID='" . $_GET[ 'newsID' ] . "'");
        \webspell\Tags::removeTags('news', $newsID);
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }

} elseif (isset($_GET[ 'delete_cat' ])) {

    $filepath = $plugin_path."images/news-rubrics/";
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $rubricID = (int)$_GET[ 'rubricID' ];
        
        safe_query("DELETE FROM " . PREFIX . "plugins_news_rubrics WHERE rubricID='$rubricID'");
        if (file_exists($filepath . $rubricID . '.gif')) {
            @unlink($filepath . $rubricID . '.gif');
        }
        if (file_exists($filepath . $rubricID . '.jpg')) {
            @unlink($filepath . $rubricID . '.jpg');
        }
        if (file_exists($filepath . $rubricID . '.png')) {
            @unlink($filepath . $rubricID . '.png');
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }

}


#===================================================================================================================


if ($action == "add") {

$CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();


        $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_news`");
        $ds = mysqli_fetch_array($ergebnis);

        $rubriccategory = safe_query("SELECT * FROM `" . PREFIX . "plugins_news_rubrics` ORDER BY `rubric`");
        $rubriccats = '<select class="form-select" id="rubric" name="rubric">';
        while ($dc = mysqli_fetch_array($rubriccategory)) {
            $selected = '';
            $rubriccats .= '<option value="' . $dc[ 'rubricID' ] . '"' . $selected . '>' . getinput($dc[ 'rubric' ]) .
                '</option>';
            }
            $rubriccats .= '</select>';

        if (isset($_POST[ "displayed" ])) {
            $displayed = 1;
        } else {
            $displayed = 0;
        }

    $url1 = "http://";
    $url2 = "http://";
    
    $link1 = '';
    $link2 = '';
    
    $window1 = '<input class="form-check-input" name="window1" type="checkbox" value="1">';
    $window2 = '<input class="form-check-input" name="window2" type="checkbox" value="1">';

    $comments = '<option value="0">' . $plugin_language[ 'no_comments' ] . '</option><option value="1">' .
        $plugin_language[ 'user_comments' ] . '</option><option value="2" selected="selected">' .
        $plugin_language[ 'visitor_comments' ] . '</option>';

#$tags = \webspell\Tags::getTags('news', $newsID);
echo '<script>
        function chkFormular() {
            if(!validbbcode(document.getElementById(\'message\').value, \'admin\')){
                return false;
            }
        }
    </script>';

  echo'<div class="card">
  <div class="card-header">
                            <i class="bi bi-newspaper"></i> ' . $plugin_language['news'] . '
                        </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_news_manager">' . $plugin_language[ 'news' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['new_post'].'</li>
                </ol>
            </nav> 
    <div class="card-body">
<script>
    function chkFormular() {
        if (document.getElementById(\'rubric\').value === "") {
            alert("Keine katogorie erstellt!");
            document.getElementById(\'rubric\').focus();

            return false;
        }
    }
    </script>
    <form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=admin_news_manager" onsubmit="return chkFormular();" enctype="multipart/form-data">

  
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['rubric'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      '.$rubriccats.'
    </div>
  </div>

<!-- ================================ screen Anfang======================================================== -->



  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['banner'].':</label>
    <div class="col-sm-4">
      <input class="btn btn-info" name="screen" type="file" id="imgInp" size="40" /> 
      <small>(max. 1000x500)</small>
    </div>
    <div class="col-sm-2">
      <img id="img-upload" src="../includes/plugins/news_manager/images/news-pic/no-image.jpg" height="50px"/>
    </div>
  </div>
';
 

echo'<hr>';
echo '
<!-- =============================  screen Ende =========================================================== -->

 <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['link'].' 1:</label>
    <div class="col-sm-3">
      <input class="form-control" name="link1" type="text">
    </div>
    <div class="col-sm-3">
      <input class="form-control" name="url1" type="text" placeholder="http://">
      </div>
      <div class="col-sm-2 form-check form-switch" style="padding: 0px 43px;">
      '.$window1.'&nbsp;&nbsp;'.$plugin_language['new_window'].'
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['link'].' 2:</label>
    <div class="col-sm-3">
      <input class="form-control" name="link2" type="text">
    </div>
    <div class="col-sm-3">
      <input class="form-control" name="url2" type="text" placeholder="http://">
      </div>
      <div class="col-sm-2 form-check form-switch" style="padding: 0px 43px;">
      '.$window2.'&nbsp;&nbsp;'.$plugin_language['new_window'].'
    </div>
  </div>
   
<hr>

<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['headline'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" class="form-control" name="headline" size="60" required/></em></span>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['tags'].'</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="tags" value="" size="97" /></em></span>
    </div>
  </div>

<div class="mb-3 row">
   <label class="col-sm-2 control-label">'.$plugin_language['text'].':</label>
    <div class="col-sm-8">
      <textarea name="message" id="ckeditor" cols="30" rows="15" class="ckeditor"></textarea>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['is_displayed'].':</label>
    <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
      <input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />
    </div>
  </div>

<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['comments'].':</label>
    <div class="col-sm-3">
      <select class="form-select" name="comments">'.$comments.'</select>
      </div>
  </div>


  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
    <input type="hidden" name="captcha_hash" value="'.$hash.'" />
    <button class="btn btn-success" type="submit" name="news_save"  />'.$plugin_language['save_news'].'</button>
    </div>
  </div>

  </form></div>
  </div>';    
    
   
} elseif ($action == "edit") {

    $filepath = $plugin_path."images/news-pic/";

  $newsID = intval($_GET[ 'newsID' ]);
$newsID = $_GET[ 'newsID' ];

$CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

        $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_news` WHERE `newsID` = '$newsID'");
        $ds = mysqli_fetch_array($ergebnis);

        $rubriccategory = safe_query("SELECT * FROM `" . PREFIX . "plugins_news_rubrics` ORDER BY `rubric`");
        $rubriccats = '<select class="form-select" name="rubric">';
        while ($dc = mysqli_fetch_array($rubriccategory)) {
            $selected = '';
            if ($dc[ 'rubricID' ] == $ds[ 'rubric' ]) {
                $selected = ' selected="selected"';
            }
            $rubriccats .= '<option value="' . $dc[ 'rubricID' ] . '"' . $selected . '>' . getinput($dc[ 'rubric' ]) .
                '</option>';
            }
            $rubriccats .= '</select>';

        if ($ds[ 'displayed' ] == 1) {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />';
        } else {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" />';
        }


    $link1 = getinput($ds[ 'link1' ]);
    $link2 = getinput($ds[ 'link2' ]);
    

    $url1 = "http://";
    $url2 = "http://";
    

    if ($ds[ 'url1' ] != "http://") {
        $url1 = $ds[ 'url1' ];
    }
    if ($ds[ 'url2' ] != "http://") {
        $url2 = $ds[ 'url2' ];
    }
    

    if ($ds[ 'window1' ]) {
        $window1 = '<input class="form-check-input" name="window1" type="checkbox" value="1" checked="checked">';
    } else {
        $window1 = '<input class="form-check-input" name="window1" type="checkbox" value="1">';
    }

    if ($ds[ 'window2' ]) {
        $window2 = '<input class="form-check-input" name="window2" type="checkbox" value="1" checked="checked">';
    } else {
        $window2 = '<input class="form-check-input" name="window2" type="checkbox" value="1">';
    }

    $comments = '<option value="0">' . $plugin_language[ 'no_comments' ] . '</option><option value="1">' .
        $plugin_language[ 'user_comments' ] . '</option><option value="2">' .
        $plugin_language[ 'visitor_comments' ] . '</option>';
    $comments =
        str_replace(
            'value="' . $ds[ 'comments' ] . '"',
            'value="' . $ds[ 'comments' ] . '" selected="selected"',
            $comments
        );

    $tags = \webspell\Tags::getTags('news', $newsID); 
    #\webspell\Tags::setTags('news', $newsID, $_POST[ 'tags' ]);


    $date = date("Y-m-d", $ds[ 'date' ]);

    #$tags = getinput($ds[ 'tags' ]);

    $data_array = array();
    $data_array['$link1'] = $link1;
    $data_array['$url1'] = $url1;
    $data_array['$window1'] = $window1;
    $data_array['$link2'] = $link2;
    $data_array['$url2'] = $url2;
    $data_array['$window2'] = $window2;
    #$data_array['$tags'] = $tags;



   echo '<script>
        function chkFormular() {
            if(!validbbcode(document.getElementById(\'message\').value, \'admin\')){
                return false;
            }
        }
    </script>';

  echo'<div class="card">
  <div class="card-header">
                            <i class="bi bi-newspaper"></i> ' . $plugin_language['news'] . '
                        </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_news_manager">' . $plugin_language[ 'news' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['edit_news'].'</li>
                </ol>
            </nav> 
    <div class="card-body">';

    echo'<form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=admin_news_manager&action=edit&newsID=' . $newsID.'"" onsubmit="return chkFormular();" enctype="multipart/form-data">


  <input type="hidden" name="newsID" value="'.$ds['newsID'].'" />
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['rubric'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      '.$rubriccats.'
    </div>
  </div>


<!-- ================================ screen Anfang======================================================== -->


<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['banner'].':</label>
    <div class="col-sm-3"><span class="text-muted small"><em>
      <input class="btn btn-info" type="file" id="imgInp" name="screen"> <small>(max. 1000x500)</small>
        </em></span>
        
    </div><div class="col-sm-2"></div>
        <div class="col">
      <img id="img-upload" src="../includes/plugins/news_manager/images/news-pic/no-image.jpg" height="50px"/>
    </div>

    <div class="col-sm-3"><input class="btn btn-success" type="submit" name="submit" value="' . $plugin_language[ 'upload' ] . '"></div>
  </div>
';
            

    $ergebnis = safe_query("SELECT screens FROM " . PREFIX . "plugins_news WHERE newsID='" . $newsID."'");
    $db = mysqli_fetch_array($ergebnis);
    $screens = array();
    if (!empty($db[ 'screens' ])) {
        $screens = explode("|", $db[ 'screens' ]);
    }
    if (is_array($screens)) {
        foreach ($screens as $screen) {
            if ($screen != "") {

echo'

<div class="mb-3 row">
<label class="col-sm-2 control-label">'.$plugin_language['current_banner'].':</label>
    <div class="col-sm-1">
        <a href="../' . $filepath . $screen . '" target="_blank"><img class="img-fluid" style="height="50px" src="../' . $filepath . $screen . '" alt="" /></a>
    </div>
    <div class="col">' . $screen . '<br>
        <input type="text" name="pic" size="100" value="../' . $filepath . $screen . '">

    </div>
    <div class="col-sm-3">
        <input class="hidden-xs hidden-sm btn btn-danger" type="button" onclick="MM_confirm(
                        \'' . $plugin_language[ 'delete' ] . '\',
                        \'admincenter.php?site=admin_news_manager&amp;action=picdelete&amp;newsID=' . $newsID . '&amp;file=' . basename($screen) . '\'
                    )" value="' . $plugin_language[ 'delete' ] . '">                    
    </div>
</div>


<hr>
';
            }
        }
    }

echo '


<!-- =============================  screen Ende =========================================================== -->


<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['link'].' 1:</label>
    <div class="col-sm-3">
      <input class="form-control" name="link1" type="text" value="'.getinput($ds['link1']).'">
    </div>
    <div class="col-sm-3">
      <input class="form-control" name="url1" type="text" value="'.getinput($ds['url1']).'" placeholder="http://">
      </div>
      <div class="col-sm-2 form-check form-switch" style="padding: 0px 43px;">
      '.$window1.'&nbsp;&nbsp;'.$plugin_language['new_window'].'
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['link'].' 2:</label>
    <div class="col-sm-3">
      <input class="form-control" name="link2" type="text" value="'.getinput($ds['link2']).'">
    </div>
    <div class="col-sm-3">
      <input class="form-control" name="url2" type="text" value="'.getinput($ds['url2']).'" placeholder="http://">
      </div>
      <div class="col-sm-2 form-check form-switch" style="padding: 0px 43px;">
      '.$window2.'&nbsp;&nbsp;'.$plugin_language['new_window'].'
    </div>
  </div>
   
<hr>
 
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['headline'].':</label>
    <div class="col-sm-8">
      <input class="form-control" type="text" name="headline" maxlength="255" size="5" value="'.getinput($ds['headline']).'" />
    </div>
  </div>

<div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'tags' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="tags" value="'.$tags.'" size="97" /></em></span>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['text'].':</label>
    <div class="col-sm-8">
       <textarea name="message" id="ckeditor" cols="30" rows="15" class="ckeditor">'. getinput($ds[ 'content' ]) .' </textarea>
    </div>
  </div>

  <div class="mb-3 row">
        <label for="bday" class="col-sm-2 control-label">'.$plugin_language['publication_setting'].':</label>
            <div class="col-lg-2">
            <input name="date" type="date" value="'.$date.'" placeholder="yyyy-mm-dd" class="form-control">
        </div>
    </div>

   <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['is_displayed'].':</label>
    <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
    '.$displayed.'
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['comments'].':</label>
    <div class="col-sm-3">
      <select class="form-select" name="comments">'.$comments.'</select>
      </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
    <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="newsID" value="'.$newsID.'" />
    <button class="btn btn-warning" type="submit" name="saveedit"  />'.$plugin_language['save_news'].'</button>

        
    </div>
  </div>
  </form>
  </div>
  </div>'; 

} elseif ($action == "picdelete") {

    $file = basename($_GET[ 'file' ]);
    if (file_exists($filepath . $file)) {
        @unlink($filepath . $file);
    }

    $ergebnis = safe_query("SELECT screens FROM " . PREFIX . "plugins_news WHERE newsID=" . $_GET[ "newsID" ]."");
    $db = mysqli_fetch_array($ergebnis);
    
    $screens = explode("|", $db[ 'screens' ]);
    foreach ($screens as $pic) {
        if ($pic != $file) {
            $newscreens[ ] = $pic;
        }
    }
    if (is_array($newscreens)) {
        $newscreens_string = implode("|", $newscreens);
    }

    safe_query("UPDATE " . PREFIX . "plugins_news SET screens='".$newscreens_string."' WHERE newsID=" . $_GET[ "newsID" ]."");
       
    header("Location: admincenter.php?site=admin_news_manager&action=edit&newsID=" . $_GET[ "newsID" ]."");
    
} elseif ($action == "") {


if(isset($_GET['page'])) $page=(int)$_GET['page'];
  else $page = 1;

    echo'<div class="card">
  <div class="card-header">
                            <i class="bi bi-newspaper"></i> ' . $plugin_language['news'] . '
                        </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_news_manager">' . $plugin_language[ 'news' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav> 
    <div class="card-body">';
    
    echo'<div class="mb-3 row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_news_manager&action=admin_news_categories" class="btn btn-primary" type="button">' . $plugin_language[ 'news_rubrics' ] . '</a>
      <a href="admincenter.php?site=admin_news_manager&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_post' ] . '</a>
      <a href="admincenter.php?site=admin_news_manager&action=admin_news_settings" class="btn btn-primary" type="button">' . $plugin_language[ 'settings' ] . '</a>
    </div>
  </div>';

        $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_news_settings");
        $dx = mysqli_fetch_array($settings);

    
        $maxadminnews = $dx[ 'news' ];
        if (empty($maxshownnews)) {
        $maxadminnews = 10;
        }

    $alle=safe_query("SELECT newsID FROM ".PREFIX."plugins_news");
  $gesamt = mysqli_num_rows($alle);
  $pages=1;

        $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_news_settings");
        $dn = mysqli_fetch_array($settings);

    
        $max = $dn[ 'admin_news' ];
        if (empty($max)) {
        $max = 10;
        }

 

  for ($n=$max; $n<=$gesamt; $n+=$max) {
    if($gesamt>$n) $pages++;
  }

  if($pages>1) $page_link = makepagelink("admincenter.php?site=admin_news_manager", $page, $pages);
    else $page_link='';

  if ($page == "1") {
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_news ORDER BY date DESC LIMIT 0,$max");
    $n=1;
  }
  else {
    $start=$page*$max-$max;
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_news ORDER BY date DESC LIMIT $start,$max");
    $n = ($gesamt+1)-$page*$max+$max;
  } 

    

     echo'<table class="table table-striped">
    <thead>
      <th><b>' . $plugin_language['date'] . '</b></th>
      <th><b>' . $plugin_language['rubric'] . '</b></th>
      <th><b>' . $plugin_language['headline'] . '</b></th>
      <th><b>' . $plugin_language['is_displayed'] . '</b></th>
      <th><b>' . $plugin_language['actions'] . '</b></th>
    </thead>';

$ds = safe_query("SELECT * FROM `" . PREFIX . "plugins_news` ORDER BY `date`");
    
   $n=1;

        while ($db = mysqli_fetch_array($ergebnis)) { 

            $CAPCLASS = new \webspell\Captcha;
            $CAPCLASS->createTransaction();
            $hash = $CAPCLASS->getHash();
        
        $rubrikname = getnewsrubricname($db[ 'rubric' ]);
        $rubric = $db['rubric'];
        $date = getformatdate($db[ 'date' ]);

            $db[ 'displayed' ] == 1 ?
            $displayed = '<font color="green"><b>' . $plugin_language[ 'yes' ] . '</b></font>' :
            $displayed = '<font color="red"><b>' . $plugin_language[ 'no' ] . '</b></font>';
            
            

        echo '<tr>
        <td>'.$date.'</td>
        <td>'.$rubrikname.'</td>
        <td>'.$db['headline'].'</td>
        <td>'.$displayed.'</td>
        
        <td><a href="admincenter.php?site=admin_news_manager&amp;action=edit&amp;newsID='.$db['newsID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        
<!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_news_manager&amp;delete=true&amp;newsID='.$db['newsID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'news' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete_news'] . '</p>
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
      
      
      $n++;
        } 
     
  echo '</table>';
  if($pages>1) echo $page_link;
  
  #}

echo '</div></div>';



} elseif ($action == "admin_news_settings") {

 
    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_news_settings");
    $ds = mysqli_fetch_array($settings);

    
  $maxshownnews = $ds[ 'news' ];
if (empty($maxshownnews)) {
    $maxshownnews = 10;
}
$maxnewsarchiv = $ds[ 'newsarchiv' ];
if (empty($maxnewsarchiv)) {
    $maxnewsarchiv = 20;
}
$maxheadlines = $ds[ 'headlines' ];
if (empty($maxheadlines)) {
    $maxheadlines = 2;
}
$maxheadlinechars = $ds[ 'headlineschars' ];
if (empty($maxheadlinechars)) {
    $maxheadlinechars = 2;
}
$maxtopnewschars = $ds[ 'topnewschars' ];
if (empty($maxtopnewschars)) {
    $maxtopnewschars = 200;
} 
$maxnewschars = $ds[ 'newschars' ];
if (empty($maxnewschars)) {
    $maxnewschars = 200;
}
$maxfeedback = $ds[ 'feedback' ];
if (empty($maxfeedback)) {
    $maxfeedback = 5;
}  
    
#$switchen = $ds['switchen']    
            $switchen = '<option value="12">' . $plugin_language['big'] . '</option>
                         <option value="6">' . $plugin_language['two'] . '</option>
                         <option value="4">' . $plugin_language['three'] . '</option>
                         <option value="3">' . $plugin_language['four'] . '</option>';
            $switchen =
                str_replace('value="' . $ds['switchen'] . '"', 'value="' . $ds['switchen'] . '" selected="selected"', $switchen);    

    

    

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    
echo'    <form method="post" action="admincenter.php?site=admin_news_manager&action=admin_news_settings">
        <div class="card">
            <div class="card-header">
                '.$plugin_language[ 'settings' ].'
            </div>

            <div class="card-body">
                <div class="col-md-10 form-group"><a href="admincenter.php?site=admin_news_manager" class="white">'.$plugin_language['title'].'</a> &raquo; <a href="admincenter.php?site=admin_news_manager&action=admin_news_settings" class="white">'.$plugin_language['settings'].'</a> &raquo; Edit</div>
<div class="col-md-2 form-group"></div><br><br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['max_admin'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_1' ].'"><input class="form-control" name="admin_news" type="text" value="'.$ds[ 'admin_news' ].'" size="35"></em></span>
                            </div>
                        </div>

                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['max_news'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_2' ].'"><input class="form-control" type="text" name="news" value="'.$ds['news'].'" size="35"></em></span>
                            </div>
                        </div>

                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['max_archiv'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_3' ].'"><input class="form-control" type="text" name="newsarchiv" value="'.$ds['newsarchiv'].'" size="35" ></em></span>
                            </div>
                        </div>

                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['max_headlines'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_4' ].'"><input class="form-control" type="text" name="headlines" value="'.$ds['headlines'].'" size="35"></em></span>
                            </div>
                        </div>

                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['news_position'].':
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <select id="switchen" name="switchen" class="form-select">'.$switchen.'</select>
                                </div>
                            </div>
                        </div>

                        
                    </div>

                    <div class="col-md-6">
                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['max_length_news'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_5' ].'"><input class="form-control" type="text" name="newschars" value="'.$ds['newschars'].'" size="35"></em></span>
                            </div>
                        </div>

                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['max_length_headlines'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_6' ].'"><input class="form-control" type="text" name="headlineschars" value="'.$ds['headlineschars'].'" size="35"></em></span>
                            </div>
                        </div>

                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['max_length_topnews'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_7' ].'"><input class="form-control" type="text" name="topnewschars" value="'.$ds['topnewschars'].'" size="35"></em></span>
                            </div>
                        </div>

                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['comments'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_8' ].'"><input class="form-control" type="text" name="feedback" value="'.$ds['feedback'].'" size="35"></em></span>
                            </div>
                        </div>
                    </div>
               </div>
                <br>
 <div class="form-group">
<input type="hidden" name="captcha_hash" value="'.$hash.'"> 
<button class="btn btn-primary" type="submit" name="news_settings_save">'.$plugin_language['update'].'</button>
</div>

        

 </div>
            </div>
       
        
    </form>';

# admin_links_categories

} elseif ($action == "admin_news_categories_add") {
   $filepath = $plugin_path."images/news-rubrics/";
$CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
  echo'<div class="card">
  <div class="card-header">
                            <i class="bi bi-newspaper"></i> ' . $plugin_language['news_rubrics'] . '
                        </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_news_manager">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_news_manager&action=admin_news_categories">' . $plugin_language[ 'news_rubrics' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['add_rubric'].'</li>
                </ol>
            </nav> 
    <div class="card-body">';

    echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_news_manager&action=admin_news_categories" enctype="multipart/form-data">
        <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['rubric_name'].':</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" name="name"  />
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['picture_upload'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
     <p class="form-control-static"><input class="btn btn-info" name="pic" type="file" size="40" /></p></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="hidden" name="captcha_hash" value="'.$hash.'" /><button class="btn btn-success" type="submit" name="news_categories_save" />'.$plugin_language['add_rubric'].'</button>
    </div>
  </div>
  </form></div></div>';

} elseif ($action == "admin_news_categories_edit") {
$filepath = $plugin_path."images/news-rubrics/";
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    echo '<div class="card">
  <div class="card-header">
                            <i class="bi bi-newspaper"></i> ' . $plugin_language['news_rubrics'] . '
                        </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_news_manager">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_news_manager&action=admin_news_categories">' . $plugin_language[ 'news_rubrics' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['edit_rubric'].'</li>
                </ol>
            </nav> 
    <div class="card-body">';

    
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_news_rubrics WHERE rubricID='" . intval($_GET['rubricID']) ."'"
        )
    );

    if (!empty($ds[ 'pic' ])) {
        $pic = '<img class="img-thumbnail" style="width: 100%; max-width: 600px" src="../' . $filepath . $ds[ 'pic' ] . '" alt="">';
    } else {
        $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath . 'no-image.jpg" alt="">';
    }

    echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_news_manager&action=admin_news_categories" enctype="multipart/form-data">
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['rubric_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="name" value="'.getinput($ds['rubric']).'" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['picture'].':</label>
    <div class="col-sm-8">
      <p class="form-control-static">' . $pic . '</p>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['picture_upload'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
     <p class="form-control-static"><input class="btn btn-info" name="pic" type="file" size="40" /></p></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
     <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="rubricID" value="'.$ds['rubricID'].'" />
     <button class="btn btn-warning" type="submit" name="news_categories_saveedit" />'.$plugin_language['edit_rubric'].'</button>
    </div>
  </div>
  </form></div></div>';  
   
} elseif ($action == "admin_news_categories") {

   
echo'<div class="card">
  <div class="card-header">
                            <i class="bi bi-newspaper"></i> ' . $plugin_language['news_rubrics'] . '
                        </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_news_manager">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_news_manager&action=admin_news_categories">' . $plugin_language[ 'news_rubrics' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav> 
    <div class="card-body">

<div class="mb-3 row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_news_manager&action=admin_news_categories_add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_rubric' ] . '</a>
    </div>
  </div>

<div class="row">
<div class="col-md-12"><br />';

    

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_news_rubrics ORDER BY rubric");
    $filepath = $plugin_path."images/news-rubrics/";
  echo'<table class="table table-striped">
    <thead>
      <tr>
      <th><b>'.$plugin_language['rubric_name'].':</b></th>
      <th><b>'.$plugin_language['picture'].':</b></th>
      <th><b>'.$plugin_language['actions'].':</b></th>
        </tr></thead>
          <tbody>';
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

        if (!empty($ds[ 'pic' ])) {
        $pic = '<img class="img-thumbnail" style="width: 100%; max-width: 600px" src="../' . $filepath . $ds[ 'pic' ] . '" alt="">';
    } else {
        $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath . 'no-image.jpg" alt="">';
    }
    
        echo'<tr>
      <td>'.getinput($ds['rubric']).'</td>
      <td>'.$pic.'</td>
      <td><a href="admincenter.php?site=admin_news_manager&action=admin_news_categories_edit&amp;rubricID='.$ds['rubricID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

<!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_news_manager&action=admin_news_categories&amp;delete_cat=true&amp;rubricID='.$ds['rubricID'].'&amp;captcha_hash='.$hash.'">
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
    </tr>';
      
      $i++;
    }
    echo'</tbody></table>';

echo '</div></div></div></div>';

}
?>