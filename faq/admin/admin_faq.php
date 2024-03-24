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
$plugin_language = $pm->plugin_language("admin_faq", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='faq'");
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

if (isset($_GET[ 'delete' ])) {
    $faqID = $_GET[ 'faqID' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        safe_query(
            "DELETE FROM
                `" . PREFIX . "plugins_faq`
            WHERE
                `faqID` = '" . $faqID . "'"
        );
        \webspell\Tags::removeTags('faq', $faqID);
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'sortieren' ])) {
    $sortfaq = $_POST[ 'sortfaq' ];

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (is_array($sortfaq)) {
            foreach ($sortfaq as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE `" . PREFIX . "plugins_faq` SET `sort` = '$sorter[1]' WHERE `faqID` = '" . $sorter[0] . "'");
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'save' ])) {
    $faqcat = $_POST[ 'faqcat' ];
    $question = $_POST[ 'question' ];
    $answer = $_POST[ 'message' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('question', 'message'))) {
            if ($faqcat == "") {
                redirect('admincenter.php?site=admin_faq', $plugin_language[ 'no_faq_selected' ], 3);
                exit;
            }
            safe_query(
                "INSERT INTO
                    `" . PREFIX . "plugins_faq` (
                        `faqcatID`,
                        `date`,
                        `question`,
                        `answer`,
                        `sort`
                    )
                VALUES (
                    '$faqcat',
                    '" . time() . "',
                    '$question',
                    '$answer',
                    '1'
                )"
            );
            $id = mysqli_insert_id($_database);
            \webspell\Tags::setTags('faq', $id, $_POST[ 'tags' ]);
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'saveedit' ])) {
    $faqcat = $_POST[ 'faqcat' ];
    $question = $_POST[ 'question' ];
    $answer = $_POST[ 'message' ];
    $faqID = $_POST[ 'faqID' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('question', 'message'))) {
            safe_query(
                "UPDATE
                    `" . PREFIX . "plugins_faq`
                SET
                    `faqcatID` = '$faqcat',
                    `date` = '" . time() . "',
                    `question` = '$question',
                    `answer` = '$answer'
                WHERE
                    `faqID` = '$faqID'"
            );
            \webspell\Tags::setTags('faq', $faqID, $_POST[ 'tags' ]);
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
}

#if (isset($_GET[ 'action' ])) {

if ($action == "add") {
    if ($_GET[ 'action' ] == "add") {
        $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_faq_categories` ORDER BY `sort`");
        $faqcats = '<select class="form-select" name="faqcat">';
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $faqcats .= '<option value="' . $ds[ 'faqcatID' ] . '">' . getinput($ds[ 'faqcatname' ]) . '</option>';
        }
        $faqcats .= '</select>';

        if (isset($_GET[ 'answer' ])) {
            echo '<span style="color: red">' . $plugin_language[ 'no_category_selected' ] . '</span>';
            $question = $_GET[ 'question' ];
            $answer = $_GET[ 'answer' ];
        } else {
            $question = "";
            $answer = "";
        }

        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'faq' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_faq">' . $plugin_language[ 'faq' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['add_faq'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">';


        echo '<script>
            function chkFormular() {
                if(!validbbcode(document.getElementById(\'message\').value, \'admin\')){
                    return false;
                }
            }
        </script>';
		
    echo'<form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=admin_faq" onsubmit="return chkFormular();">
     <div class="row">
	 <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category'].'</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      '.$faqcats.'</em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['faq'].'</label>
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
    <div class="col-sm-offset-2 col-sm-10">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" />
		<button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['add_faq'].'</button>
    </div>
  </div>
  </div>
    </form></div>
  </div>';
}
} elseif ($action == "edit") {
        $faqID = $_GET[ 'faqID' ];

        $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_faq` WHERE `faqID` = '$faqID'");
        $ds = mysqli_fetch_array($ergebnis);

        $faqcategory = safe_query("SELECT * FROM `" . PREFIX . "plugins_faq_categories` ORDER BY `sort`");
        $faqcats = '<select class="form-select" name="faqcat">';
        while ($dc = mysqli_fetch_array($faqcategory)) {
            $selected = '';
            if ($dc[ 'faqcatID' ] == $ds[ 'faqcatID' ]) {
                $selected = ' selected="selected"';
            }
            $faqcats .= '<option value="' . $dc[ 'faqcatID' ] . '"' . $selected . '>' . getinput($dc[ 'faqcatname' ]) .
                '</option>';
        }
        $faqcats .= '</select>';

        $tags = \webspell\Tags::getTags('faq', $faqID);

        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

        $tags = \webspell\Tags::getTags('faq', $faqID);

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'faq' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_faq">' . $plugin_language[ 'faq' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['edit_faq'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">';

        echo '<script>
            function chkFormular() {
                if(!validbbcode(document.getElementById(\'message\').value, \'admin\')){
                    return false;
                }
            }
        </script>';
		
    echo '<form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=admin_faq" onsubmit="return chkFormular();">
    <div class="row">
	 <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      '.$faqcats.'
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['faq'].':</label>
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
    <div class="col-sm-offset-2 col-sm-10">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="faqID" value="'.$faqID.'" />
		<button class="btn btn-warning" type="submit" name="saveedit"  />'.$plugin_language['edit_faq'].'</button>
    </div>
  </div>

  </div>
    </form></div>
  </div>';
	
} 
    

# admin_faq_categories

if (isset($_POST[ 'faq_categories_save' ])) {

$faqcatname = $_POST[ 'faqcatname' ];
    $description = $_POST[ 'message' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('faqcatname'))) {
            safe_query(
                "INSERT INTO
                    " . PREFIX . "plugins_faq_categories (
                        faqcatname,
                        description,
                        sort
                    )
                    VALUES (
                        '$faqcatname',
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
  
} elseif (isset($_POST[ 'faq_categories_saveedit' ])) { 

$faqcatname = $_POST[ 'faqcatname' ];
    $description = $_POST[ 'message' ];
    $faqcatID = $_POST[ 'faqcatID' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('faqcatname'))) {
            safe_query(
                "UPDATE " . PREFIX .
                "plugins_faq_categories SET faqcatname='$faqcatname', description='$description' WHERE faqcatID='$faqcatID' "
            );
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    } 

} elseif (isset($_GET[ 'faq_categories_delete' ])) {  

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        safe_query("DELETE FROM " . PREFIX . "plugins_faq_categories WHERE faqcatID='" . $_GET[ 'faqcatID' ] . "'");
        safe_query("DELETE FROM " . PREFIX . "plugins_faq WHERE faqcatID='" . $_GET[ 'faqcatID' ] . "'");
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }



}



if ($action == "admin_faq_categories_add") {

    $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'faq_categories' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_faq">' . $plugin_language[ 'faq' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_faq&action=admin_faq_categories">' . $plugin_language[ 'faq_categories' ] . '</a></li>
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
    
    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_faq&action=admin_faq_categories" id="post" name="post" enctype="multipart/form-data" onsubmit="return chkFormular();">
    <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="faqcatname" /></em></span>
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
     <input type="hidden" name="captcha_hash" value="'.$hash.'" /><button class="btn btn-success" type="submit" name="faq_categories_save" />'.$plugin_language['add_category'].'</button>
    </div>
  </div>
    </form>
    </div>
  </div>';

} elseif ($action == "admin_faq_categories_edit") {
    $faqcatID = $_GET[ 'faqcatID' ];

        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_faq_categories WHERE faqcatID='$faqcatID'");
        $ds = mysqli_fetch_array($ergebnis);

        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'faq_categories' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_faq">' . $plugin_language[ 'faq' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_faq&action=admin_faq_categories">' . $plugin_language[ 'faq_categories' ] . '</a></li>
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
    
    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_faq&action=admin_faq_categories" id="post" name="post" onsubmit="return chkFormular();">
    <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="faqcatname" value="'.getinput($ds['faqcatname']).'" /></em></span>
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
     <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="faqcatID" value="'.$faqcatID.'" />
     <button class="btn btn-warning" type="submit" name="faq_categories_saveedit" />'.$plugin_language['edit_category'].'</button>
    </div>
  </div>
    </form>
    </div>
  </div>';
    


} elseif ($action == "admin_faq_categories") {

    echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'faq_categories' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_faq">' . $plugin_language[ 'faq' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_faq&action=admin_faq_categories">' . $plugin_language[ 'faq_categories' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="mb-3 row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_faq&action=admin_faq&action=admin_faq_categories_add" class="btn btn-primary">' . $plugin_language[ 'new_category' ] . '</a>
    </div>
  </div>';


echo'<form method="post" action="admincenter.php?site=admin_faq&action=admin_faq_categories">
  <table class="table table-striped">
    <thead>
      <th style="width: 70%;"><b>'.$plugin_language['faq_categories'].'</b></th>
      <th><b>'.$plugin_language['actions'].'</b></th>
      <th><b>'.$plugin_language['sort'].'</b></th>
    </thead>';

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_faq_categories ORDER BY sort");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(faqcatID) as cnt FROM " . PREFIX . "plugins_faq_categories"));
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
        
            $faqcatname = $ds[ 'faqcatname' ];
            $description = $ds[ 'description' ];

            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($faqcatname);
            $faqcatname = $translate->getTextByLanguage($faqcatname);

            $translate->detectLanguages($description);
            $description = $translate->getTextByLanguage($description);
            
            $data_array = array();
            $data_array['$faqcatname'] = $faqcatname;
            $data_array['$description'] = $description;
  
        echo '<tr>
            <td class="' . $td . '"><b>' . $faqcatname . '</b>
            <br />' . $description . '</td>
      <td><a href="admincenter.php?site=admin_faq&action=admin_faq_categories_edit&amp;faqcatID='.$ds['faqcatID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

<!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_faq&action=admin_faq_categories&amp;faq_categories_delete=true&amp;faqcatID='.$ds['faqcatID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'faq_categories' ] . '</h5>
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
      <td><select name="sortfaqcat[]">';
        
    for ($n = 1; $n <= $anz; $n++) {
            if ($ds[ 'sort' ] == $n) {
                echo '<option value="' . $ds[ 'faqcatID' ] . '-' . $n . '" selected="selected">' . $n . '</option>';
            } else {
                echo '<option value="' . $ds[ 'faqcatID' ] . '-' . $n . '">' . $n . '</option>';
            }
        }
    
        echo'</select></td>
    </tr>';
    
    $i++;
    }
    echo'<tr>
      <td class="td_head" colspan="3" align="right"><input type="hidden" name="captcha_hash" value="'.$hash.'" /><input class="btn btn-primary" type="submit" name="sortieren" value="'.$plugin_language['to_sort'].'" /></td>
    </tr>
  </table>
  </form>';
#}
echo '</div></div>';


} elseif ($action == "") {    

        echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-youtube"></i> ' . $plugin_language[ 'faq' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_faq">' . $plugin_language[ 'faq' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="mb-3 row row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_faq&amp;action=add" class="btn btn-primary">' . $plugin_language[ 'new_faq' ] . '</a>

      <a href="admincenter.php?site=admin_faq&action=admin_faq_categories" class="btn btn-primary">' . $plugin_language[ 'new_category' ] . '</a>
    </div>
  </div>';



	echo'<form method="post" action="admincenter.php?site=admin_faq">
  <table class="table table-striped">
    <thead>
      <th style="width: 70%;"><b>' . $plugin_language['faq'] . '</b></th>
      <th><b>' . $plugin_language['actions'] . '</b></th>
      <th><b>' . $plugin_language['sort'] . '</b></th>
    </thead>';

	$ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_faq_categories` ORDER BY `sort`");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(faqcatID) as cnt FROM `" . PREFIX . "plugins_faq_categories`"));
    $anz = $tmp[ 'cnt' ];

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    while ($ds = mysqli_fetch_array($ergebnis)) {

            $faqcatname = $ds[ 'faqcatname' ];
            $description = $ds[ 'description' ];
            
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($faqcatname);
            $faqcatname = $translate->getTextByLanguage($faqcatname);

            $translate->detectLanguages($description);
            $description = $translate->getTextByLanguage($description);
            
            $data_array = array();
            $data_array['$faqcatname'] = $faqcatname;
            $data_array['$description'] = $description;


        echo '<tr>
            <td class="td_head" colspan="3">
                <b>' . $faqcatname . '</b><br>
                <small>' . $description . '</small>
            </td>
        </tr>';

        $faq = safe_query("SELECT * FROM `" . PREFIX . "plugins_faq` WHERE `faqcatID` = '$ds[faqcatID]' ORDER BY `sort`");
        $tmp = mysqli_fetch_assoc(
            safe_query(
                "SELECT count(faqID) as cnt FROM `" . PREFIX . "plugins_faq` WHERE `faqcatID` = '$ds[faqcatID]'"
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

            $question = $db[ 'question' ];
            
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($question);
            $question = $translate->getTextByLanguage($question);
            
            $data_array = array();
            $data_array['$question'] = $question;


            echo '<tr>
        <td><b>- '.$question.'</b></td>
        <td><a href="admincenter.php?site=admin_faq&amp;action=edit&amp;faqID='.$db['faqID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>


<!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_faq&amp;delete=true&amp;faqID='.$db['faqID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'faq' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete_faq'] . '</p>
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
        <td><select name="sortfaq[]">';
            for ($j = 1; $j <= $anzfaq; $j++) {
                if ($db[ 'sort' ] == $j) {
                    echo '<option value="' . $db[ 'faqID' ] . '-' . $j . '" selected="selected">' . $j .
                    '</option>';
                } else {
                    echo '<option value="' . $db[ 'faqID' ] . '-' . $j . '">' . $j . '</option>';
                }
            }
            echo '</select></td></tr>';
      
      $i++;
		}
	}

	echo'<tr>
      <td class="td_head" colspan="3" align="right"><input type="hidden" name="captcha_hash" value="'.$hash.'" /><button class="btn btn-primary" type="submit" name="sortieren" />'.$plugin_language['to_sort'].'</button></td>
    </tr>
  </table>
  </form>';
}
echo '</div></div>';

?>