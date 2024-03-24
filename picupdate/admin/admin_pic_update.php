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

$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("pic_update", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='picupdate'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}

// -- NEWS INFORMATION -- //
include_once("./includes/plugins/picupdate/picupdate_functions.php");
$galclass = new \webspell\Gallery;
 
$filepath = $plugin_path."images/thumb/";
$filelargepath = $plugin_path."images/large/";
$filethumbpath = $plugin_path."images/thumb/";
 
if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}
 
if ($action == "add") {
    echo '<div class="card">
            <div class="card-header">
                ' . $plugin_language[ 'screens' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_pic_update">' . $plugin_language[ 'screens' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_pic' ] . '</li>
                </ol>
            </nav>  
                        <div class="card-body">';
 
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_pic_update" enctype="multipart/form-data">
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['icon'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="btn btn-info" name="screens" type="file" size="40" /> <small>(' . $plugin_language[ 'pic_upload_info' ] . ')</small></em></span>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['pic_name'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
            <input class="form-control" type="text" name="title" size="60" maxlength="255" /></em></span>
        </div>
    </div>
    <div class="mb-3 row">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" name="captcha_hash" value="'.$hash.'" />
            <button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['add_pic'].'</button>
        </div>
    </div>
</form>
</div>
</div>';
} elseif ($action == "edit") {

    echo '<div class="card">
            <div class="card-header">
                ' . $plugin_language[ 'screens' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_pic_update">' . $plugin_language[ 'screens' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_pic' ] . '</li>
                </ol>
            </nav>  
                        <div class="card-body">';
 
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_pic_update WHERE picupdateID='" . intval($_GET['picupdateID']) ."'"
        )
    );
    if (!empty($ds[ 'screens' ])) {
        $pic = '<img class="img-thumb1nail" style="width: 100%; max-width: 600px" src="../' . $filelargepath . $ds[ 'screens' ] . '" alt="">';
    } else {
        $pic = $plugin_language[ 'no_upload' ];
    }
 
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_pic_update" enctype="multipart/form-data">
    <input type="hidden" name="picupdateID" value="' . $ds['picupdateID'] . '" />
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['present_icon'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>'.$pic.'</em></span>
        </div>
      </div>
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['icon'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
          <input class="btn btn-info" name="screens" type="file" size="40" /></em></span>
        </div>
      </div>
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">'.$plugin_language['pic_name'].':</label>
        <div class="col-sm-8"><span class="text-muted small"><em>
          <input class="form-control" type="text" name="title" size="60" maxlength="255" value="' . getinput($ds[ 'title' ]) . '" /></em></span>
        </div>
      </div>
    <div class="mb-3 row">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" name="captcha_hash" value="'.$hash.'" />
            <button class="btn btn-warning" type="submit" name="saveedit"  />'.$plugin_language['edit_pic'].'</button>
        </div>
      </div>
</form>
</div>
</div>';
} elseif (isset($_POST[ "save" ])) {
    $dir = $plugin_path."images/";

    $title = $_POST[ 'title' ];    
 
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    safe_query("INSERT INTO `".PREFIX."plugins_pic_update` (title) values ('".$title."')");
               
        $id = mysqli_insert_id($_database);
 
        $errors = array();
 
        $upload = new \webspell\HttpUpload('screens');
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
 
                        if ($upload->saveAs($filelargepath . $file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_pic_update SET screens ='" . $file . "' WHERE picupdateID='" . $id . "'"
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
        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_pic_update", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "saveedit" ])) {

    $dir = $plugin_path."images/";


    $title = $_POST[ "title" ];
    
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        
        safe_query(
            "UPDATE " . PREFIX . "plugins_pic_update SET title='" . $title . "' WHERE picupdateID='" .
            $_POST[ "picupdateID" ] . "'"
        );
 
        $id = $_POST[ 'picupdateID' ];
 
        $errors = array();
 
        $upload = new \webspell\HttpUpload('screens');
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

                        if ($upload->saveAs($filelargepath . $file, true)) {
                                @chmod($file, $new_chmod);
                                safe_query(
                                "UPDATE " . PREFIX . "plugins_pic_update SET screens ='" . $file . "' WHERE picupdateID='" . $id . "'"
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
        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_pic_update", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_GET[ "delete" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_pic_update WHERE picupdateID='" . $_GET[ "picupdateID" ] . "'");
        $data = mysqli_fetch_assoc($get);
 
        if (safe_query("DELETE FROM " . PREFIX . "plugins_pic_update WHERE picupdateID='" . $_GET[ "picupdateID" ] . "'")) {
            $dir = './includes/plugins/picupdate/images/';

        //delete thumb

        @unlink($dir . 'thumb/' . $_GET['picupdateID'] . '.jpg');

        //delete original

        if (file_exists($dir . 'large/' . $_GET['picupdateID'] . '.jpg')) {
            @unlink($dir . 'large/' . $_GET['picupdateID'] . '.jpg');
        } elseif (file_exists($dir . 'large/' . $_GET['picupdateID'] . '.gif')) {  
            @unlink($dir . 'large/' . $_GET['picupdateID'] . '.gif');
        } else {
            @unlink($dir . 'large/' . $_GET['picupdateID'] . '.png');
        }

        //delete database entry

            redirect("admincenter.php?site=admin_pic_update", "", 0);
        } else {
            redirect("admincenter.php?site=admin_pic_update", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} else {
    echo '<div class="card">
            <div class="card-header">
                            <i class="bi bi-images"></i> ' . $plugin_language[ 'screens' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_pic_update">' . $plugin_language[ 'screens' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_pic' ] . '</li>
                </ol>
            </nav>  
            <div class="card-body">


    <div class="mb-3 row">
        <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
        <div class="col-md-8">
            <a href="admincenter.php?site=admin_pic_update&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_pic' ] . '</a>
        </div>
    </div>';
 
    echo '<form method="post" action="admincenter.php?site=admin_pic_update">
    <table id="plugini" class="table table-striped table-bordered" style="width:100%">
    <thead>
    <th><b>ID</b></th>
      <th><b>' . $plugin_language[ 'pic_name' ] . '</b></th>
      <th><b>' . $plugin_language[ 'icon' ] . '</b></th>
      <th><b>' . $plugin_language[ 'pattern' ] . '</b></th>
      <th><b>' . $plugin_language[ 'actions' ] . '</b></th>
    </thead>';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_pic_update");
    $anz = mysqli_num_rows($qry);
    if ($anz) {
        $i = 1;
        while ($ds = mysqli_fetch_array($qry)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }
 
            $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings"));
            $hpurl = $dx[ 'hpurl' ];

            $title = $ds[ 'title' ];
    
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($title);
            $title = $translate->getTextByLanguage($title);
            
            echo '<tr>
            <td><b>' . $ds['picupdateID'] . '</b> </td>
            <td class="' . $td . '">' . $title . '</td>
            <td class="' . $td . '"><img class="img-thumbnail" style="width: 100%; max-width: 250px" align="center" src="/' . $filethumbpath . $ds[ 'picupdateID' ] . '.jpg" alt="{img}" /></td>
            <td>
                <table class="table table-bordered">
                    <tr>
                        <td style="width: 105px">HTML:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td><input class="form-control" type="text" name="addpic" size="70" value="&lt;img src=&quot;'. $dx['hpurl'].'/' . $filelargepath . $ds[ 'screens' ] . '&quot;&gt;"></td>
                    </tr><tr>
                        <td>cheditor code:&nbsp;</td>
                        <td><input class="form-control" type="text" name="addpic" size="70" value="/' . $filelargepath . $ds[ 'screens' ] . '"></td>
                    </tr><tr>
                        <td>Link:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td><input class="form-control" type="text" name="addpic" size="70" value="'. $dx['hpurl'].'/' . $filelargepath . $ds[ 'screens' ] . '"></td>

                    </tr>
                </table>        
            </td>   
            <td class="' . $td . '"><a href="admincenter.php?site=admin_pic_update&amp;action=edit&amp;picupdateID=' . $ds[ 'picupdateID' ] . '" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_pic_update&amp;delete=true&amp;picupdateID=' . $ds[ 'picupdateID' ] . '&amp;captcha_hash=' . $hash . '">
            ' . $plugin_language['delete'] . '
        </button>
        <!-- Button trigger modal END-->

    </td>

        <!-- Modal -->
        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'screens' ] . '</h5>
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

</tr>';
            $i++;
        }
    } else {
        echo '<tr><td class="td1" colspan="6">' . $plugin_language[ 'no_entries' ] . '</td></tr>';
    }
 
    echo '</table>
</form></div></div>';
}

    ?>