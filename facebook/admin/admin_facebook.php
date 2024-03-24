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
$plugin_language = $pm->plugin_language("facebook", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='facebook'");
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

if ($action == "add") {

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-facebook"></i> ' . $plugin_language[ 'title' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_facebook">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_facebook' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';


    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

echo '<form method="post" id="post" name="post" action="admincenter.php?site=admin_facebook" enctype="multipart/form-data" onsubmit="return chkFormular();">
 <div class="row">
     <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'facebook_name' ] . '</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="name" size="60" maxlength="255" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['facebook_address_id'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="facebook_id" size="60" maxlength="255" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'facebook_title' ] . '</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="title" size="60" maxlength="255" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'facebook_height' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="facebook_height" size="60" maxlength="255" /></em></span>
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
        <button class="btn btn-success" type="submit" name="save"  />' . $plugin_language[ 'add_facebook' ] . '</button>
    </div>
  </div>
  </div>
</form></div></div>';
} elseif ($action == "edit") {


    echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-facebook"></i> ' . $plugin_language[ 'title' ] . '</div>
                        
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_facebook">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_facebook' ] . '</li>
                </ol>
            </nav> 
<div class="card-body row">
<div class="col-8">';


    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_facebook_content WHERE fbID='" . $_GET[ "fbID" ] ."'"
        )
    );
    

    if ($ds[ 'displayed' ] == 1) {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />';
    } else {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" />';
    }

    

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $data_array = array();

echo '<form method="post" id="post" name="post" action="admincenter.php?site=admin_facebook" enctype="multipart/form-data" onsubmit="return chkFormular();">


     <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'facebook_name' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="name" size="80" maxlength="255" value="' . getinput($ds[ 'facebook_name' ]) . '" /></em></span>
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['facebook_address_id'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="facebook_id" size="80" maxlength="255" value="' . getinput($ds[ 'facebook_id' ]) . '" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'facebook_title' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="title" size="80" maxlength="255" value="' . getinput($ds[ 'facebook_title' ]) . '" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'facebook_height' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="facebook_height" size="80" maxlength="255" value="' . getinput($ds[ 'facebook_height' ]) . '" /></em></span>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'is_displayed' ] . ':</label>
  <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
  ' . $displayed . '
    </div>
  </div>
  </div>
  <div class="col d-flex justify-content-center">
    <div data-placeholder data-visible>
        <iframe src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2F' . getinput($ds[ 'facebook_name' ]) . '%2Fposts%2F' . getinput($ds[ 'facebook_id' ]) . '&show_text=true&width=500" width="300" height="' . getinput($ds[ 'facebook_height' ]) . '" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe></div></div>

<div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="fbID" value="' . $ds[ 'fbID' ] . '" />
        <button class="btn btn-warning" type="submit" name="saveedit"  />' . $plugin_language[ 'edit_facebook' ] . '</button>
    </div>
  </div>


</form>
</div></div>';
} elseif (isset($_POST[ 'sortieren' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $sort = $_POST[ 'sort' ];
        if (is_array($sort)) {
            foreach ($sort as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE " . PREFIX . "plugins_facebook_content SET sort='$sorter[1]' WHERE fbID='$sorter[0]' ");
                redirect("admincenter.php?site=admin_facebook", "", 0);
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "save" ])) {
    $facebook_name = $_POST[ "name" ];
    $facebook_id = $_POST[ "facebook_id" ];
    $facebook_title = $_POST[ "title" ];
    $facebook_height = $_POST[ "facebook_height" ];

    if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    if (!$displayed) {
        $displayed = 0;
    }
    

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "INSERT INTO " . PREFIX . "plugins_facebook_content (fbID, facebook_name, facebook_id, facebook_title, facebook_height, displayed, sort) values('', '" . $facebook_name . "', '" . $facebook_id . "', '" . $facebook_title . "', '" . $facebook_height . "','" . $displayed . "', '1')"
        );

        $id = mysqli_insert_id($_database);

        $errors = array();

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation', true);

       
     

        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($_language->module['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_facebook", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "saveedit" ])) {
    $facebook_name = $_POST[ "name" ];
    $facebook_id = $_POST[ "facebook_id" ];
    $facebook_title = $_POST[ "title" ];
    $facebook_height = $_POST[ "facebook_height" ];
    
    
    if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        

        safe_query(
            "UPDATE " . PREFIX . "plugins_facebook_content SET facebook_name='" . $facebook_name . "', facebook_id='" . $facebook_id .
            "', facebook_title='" . $facebook_title . "', facebook_height='" . $facebook_height . "', displayed='" . $displayed . "' WHERE fbID='" .
            $_POST[ "fbID" ] . "'"
        );

        $id = $_POST[ 'fbID' ];

        $errors = array();

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation', true);

        

        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($_language->module['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_facebook", "", 0);
        }
    } else {
		$_language->readModule('formvalidation', true);       
	   echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_GET[ "delete" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_facebook_content WHERE fbID='" . $_GET[ "fbID" ] . "'");
        $data = mysqli_fetch_assoc($get);

        if (safe_query("DELETE FROM " . PREFIX . "plugins_facebook_content WHERE fbID='" . $_GET[ "fbID" ] . "'")) {
           
            redirect("admincenter.php?site=admin_facebook", "", 0);
        } else {
            redirect("admincenter.php?site=admin_facebook", "", 0);
        }
    } else {
		print_r($plugin_language); return false;
        $_language->readModule('formvalidation', true);  
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'facebook_settings_save' ])) {  

   
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE
                " . PREFIX . "plugins_facebook_content_settings
            SET
                
                facebook='" . $_POST[ 'facebook' ] . "' "
        );
        
        redirect("admincenter.php?site=admin_facebook&action=admin_facebook_settings", "", 0);
    } else {
        redirect("admincenter.php?site=admin_facebook&action=admin_facebook_settings", $plugin_language[ 'transaction_invalid' ], 3);
    }
} elseif (isset($_POST[ 'facebook_save' ])) {
    
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_facebook");
        $ds = mysqli_fetch_array($ergebnis);
        
        if(@$_POST['radio1']=="fb1_activ") {
            $fb1_activ = 1;
            $fb2_activ = 0;
            $fb3_activ = 0;
            $fb4_activ = 0;
        } elseif(@$_POST['radio1']=="fb2_activ") {
            $fb1_activ = 0;
            $fb2_activ = 1;
            $fb3_activ = 0;
            $fb4_activ = 0;
        } elseif(@$_POST['radio1']=="fb3_activ") {
            $fb1_activ = 0;
            $fb2_activ = 0;
            $fb3_activ = 1;
            $fb4_activ = 0; 
        } elseif(@$_POST['radio1']=="fb4_activ") {
            $fb1_activ = 0;
            $fb2_activ = 0;
            $fb3_activ = 0;
            $fb4_activ = 1;    
        } else {
            $fb1_activ = 0;
            $fb2_activ = 0;
            $fb3_activ = 0;
            $fb4_activ = 0;
        }
        
    
        safe_query(
            "UPDATE
                `" . PREFIX . "plugins_facebook`
            SET
                `fb1_activ` = '" . $fb1_activ . "',
                `fb2_activ` = '" . $fb2_activ . "',
                `fb3_activ` = '" . $fb3_activ . "',
                `fb4_activ` = '" . $fb4_activ . "'
            "
        );
        $id = mysqli_insert_id($_database);
}




if ($action == "facebook_setting") {
    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_facebook");
    $ds = mysqli_fetch_array($settings);

if ($ds[ 'fb1_activ' ] == '1') {
        $fb1_activ = '<input id="fb1_activ" type="radio" name="radio1" value="fb1_activ" checked="checked" />';
    } else {
        $fb1_activ = '<input id="fb1_activ" type="radio" name="radio1" value="fb1_activ">';
    }

    if ($ds[ 'fb2_activ' ] == '1') {
        $fb2_activ = '<input id="fb2_activ" type="radio" name="radio1" value="fb2_activ" checked="checked" />';
    } else {
        $fb2_activ = '<input id="fb2_activ" type="radio" name="radio1" value="fb2_activ">';
    }

    if ($ds[ 'fb3_activ' ] == '1') {
        $fb3_activ = '<input id="fb3_activ" type="radio" name="radio1" value="fb3_activ" checked="checked" />';
    } else {
        $fb3_activ = '<input id="fb3_activ" type="radio" name="radio1" value="fb3_activ">';
    }

    if ($ds[ 'fb4_activ' ] == '1') {
        $fb4_activ = '<input id="fb4_activ" type="radio" name="radio1" value="fb4_activ" checked="checked" />';
    } else {
        $fb4_activ = '<input id="fb4_activ" type="radio" name="radio1" value="fb4_activ">';
    }


echo'<div class="card">
            <div class="card-header"> <i class="bi bi-facebook"></i> ' . $plugin_language[ 'title' ] . '</div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_facebook" class="white">' . $plugin_language[ 'title' ] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
  </ol>
</nav>
     <div class="card-body">

    <form method="post" action="admincenter.php?site=admin_facebook" class="form-horizontal">

    
    <div class="row">
    <div class="col-md-3">
    <label for="fb1_activ">'.$plugin_language['fb1_activ'].'</label>
    '.$fb1_activ.'<br>
    <img class="img-thumbnail" style="width: 100%; max-width: 250px" align="center" src="/includes/plugins/facebook/images/fb1.png" alt="{img}" />
    </div>


    <div class="col-md-3">
    <label for="fb2_activ">'.$plugin_language['fb2_activ'].'</label>
    '.$fb2_activ.'<br>
    <img class="img-thumbnail" style="width: 100%; max-width: 250px" align="center" src="/includes/plugins/facebook/images/fb2.png" alt="{img}" />
    </div>
    

    
    <div class="col-md-3">
    <label for="fb3_activ">'.$plugin_language['fb3_activ'].'</label>
    '.$fb3_activ.'<br>
    <img class="img-thumbnail" style="width: 100%; max-width: 250px" align="center" src="/includes/plugins/facebook/images/fb3.png" alt="{img}" />
    </div>


    <div class="col-md-3">
    <label for="fb4_activ">'.$plugin_language['fb4_activ'].'</label>
    '.$fb4_activ.'<br>
    <img class="img-thumbnail" style="width: 100%; max-width: 250px" align="center" src="/includes/plugins/facebook/images/fb4.png" alt="{img}" />
    </div>
    </div>

  <div class="col-md-12">
    <button class="btn btn-warning" type="submit" name="facebook_save">'.$plugin_language['update'].'</button>
      </div> 
         </div>
</div>
    </form>
    </div>
</div>';
















}elseif ($action == "") {


echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-facebook"></i> ' . $plugin_language[ 'title' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_facebook">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="form-group row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_facebook&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_facebook' ] . '</a>      
      <a href="admincenter.php?site=settings&action=social_setting" class="btn btn-primary" data-toggle="tooltip" data-html="true" title="'.$plugin_language['tooltip_1'].'">' . $plugin_language[ 'social_setting' ] . '</a>
      <a href="admincenter.php?site=admin_facebook&action=facebook_setting" class="btn btn-primary" data-toggle="tooltip" data-html="true" title="'.$plugin_language['tooltip_2'].'">' . $plugin_language[ 'facebook_setting' ] . '</a>
    </div>
  </div>

<div class="table-responsive">
    
    <form method="post" action="admincenter.php?site=admin_facebook">

<table class="table">
        <thead>
        <tr>
            <th width="19%" class="title"><b>' . $plugin_language[ 'facebook_name' ] . '</b></th>
            <th width="19%" class="title"><b>' . $plugin_language[ 'facebook_address_id' ] . '</b></th>
            <th width="19%" class="title"><b>' . $plugin_language[ 'facebook_title' ] . '</b></th>
            <th width="19%" class="title"><b>' . $plugin_language[ 'facebook_height' ] . '</b></th>
            <th width="15%" class="title"><b>' . $plugin_language[ 'is_displayed' ] . '</b></th>
            <th width="10%" class="title"><b>' . $plugin_language[ 'actions' ] . '</b></th>
            <th width="8%" class="title"><b>' . $plugin_language[ 'sort' ] . '</b></th>
            
        </tr>
        </thead>
        <tbody>
   
       
        ';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_facebook_content ORDER BY sort");
    $anz = mysqli_num_rows($qry);
    if ($anz) {
        $i = 1;
        while ($ds = mysqli_fetch_array($qry)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }

            $ds[ 'displayed' ] == 1 ?
            $displayed = '<font color="green"><b>' . $plugin_language[ 'yes' ] . '</b></font>' :
            $displayed = '<font color="red"><b>' . $plugin_language[ 'no' ] . '</b></font>';
            

            
                $facebook_name = $ds[ 'facebook_name' ];
                $facebook_id = $ds[ 'facebook_id' ];
                $facebook_title = $ds[ 'facebook_title' ];
                $facebook_height = $ds[ 'facebook_height' ];
           
                #$title = getinput($ds[ 'title' ]);
           
            #$title = $ds[ 'title' ];
    
            #$translate = new multiLanguage(detectCurrentLanguage());
            #$translate->detectLanguages($title);
            #$title = $translate->getTextByLanguage($title);

            #$facebook_address = $ds[ 'facebook_address' ];
            
            echo '<tr>
            <td width="19%" class="' . $td . '">' . $facebook_name . '</td>

            <td width="19%" class="' . $td . '">' . $facebook_id . '</td>
            
            <td width="19%" class="' . $td . '">' . $facebook_title . '</td>

            <td width="19%" class="' . $td . '">' . $facebook_height . '</td>

            <td width="15%" class="' . $td . '">' . $displayed . '</td>
            
            <td width="10%" class="' . $td . '" >

            <a class="btn btn-warning" href="admincenter.php?site=admin_facebook&amp;action=edit&amp;fbID=' . $ds[ 'fbID' ] .
                '" class="input">' . $plugin_language[ 'edit' ] . '</a>
                
                    <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_facebook&amp;delete=true&amp;fbID='.$ds['fbID'].'&amp;captcha_hash='.$hash.'">
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
      <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
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
				<td width="8%" class="' . $td . '" align="center"><select name="sort[]">';
            for ($j = 1; $j <= $anz; $j++) {
                if ($ds[ 'sort' ] == $j) {
                    echo '<option value="' . $ds[ 'fbID' ] . '-' . $j . '" selected="selected">' . $j .
                        '</option>';
                } else {
                    echo '<option value="' . $ds[ 'fbID' ] . '-' . $j . '">' . $j . '</option>';
                }
            }
            echo '</select>
</td>
</tr>';
            $i++;
        }
        
    } else {
        echo '<tr><td class="td1" colspan="7">' . $plugin_language[ 'no_entries' ] . '</td></tr>';
    }

    echo '<tr>
<td class="td_head" colspan="7" align="right"><input type="hidden" name="captcha_hash" value="' . $hash .
    '"><br><input class="btn btn-success" type="submit" name="sortieren" value="' . $plugin_language[ 'to_sort' ] . '" /></td>
</tr>
</tbody></table>
</form></div></div>';
}
