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
$plugin_language = $pm->plugin_language("files", $plugin_path);

// -- FILES INFORMATION -- //
$drpath = $_SERVER['DOCUMENT_ROOT'];
include_once("./includes/plugins/files/files_functions.php");
include_once(''.$drpath.'/system/func/useraccess.php');

function unit_to_size($num = '0', $unit = 'kb') {
    switch($unit) {
        case 'b': (float)$size = (float)$num; break;
        case 'kb': (float)$size = (float)$num * 1024; break;
        case 'mb': (float)$size = (float)$num * 1024 * 1024; break;
        case 'gb': (float)$size = (float)$num * 1024 * 1024 * 1024; break;
        case 'tb': (float)$size = (float)$num * 1024 * 1024 * 1024 * 1024; break;
        default: (float)$size = (float)$num; break;
    }
    return $size;
}


$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='files'");
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

function generate_overview($filecats = '', $offset = '', $subcatID = 0)
{

    global $plugin_language;
    $rubrics =
        safe_query("SELECT * FROM " . PREFIX . "plugins_files_categories WHERE subcatID = '" . $subcatID . "' ORDER BY name");

    $i = 1;
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    while ($ds = mysqli_fetch_array($rubrics)) {
        if ($i % 2) {
            $td = 'td1';
        } else {
            $td = 'td2';
        }
                
        $filecats .= '<tr>
        <td>'.$offset.getinput($ds['name']).'</td>
        <td class="text-right"><a href="admincenter.php?site=admin_files&action=admin_files_categories_edit&amp;filecatID='.$ds['filecatID'].'" class="hidden-xs hidden-sm btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_files&amp;files_categories_delete=true&amp;filecatID='.$ds['filecatID'].'&amp;captcha_hash='.$hash.'">
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
        </tr>';
          
        $i++;

        if (mysqli_num_rows(safe_query(
            "SELECT * FROM " . PREFIX . "plugins_files_categories WHERE subcatID = '" . $ds[ 'filecatID' ] . "'"))) {
            $filecats .= generate_overview("", $offset . " - ", $ds[ 'filecatID' ]).'</td></tr>';
        }
    }

    return $filecats;
}

function delete_category($filecat)
{
    $rubrics = safe_query(
        "SELECT filecatID FROM " . PREFIX . "plugins_files_categories WHERE subcatID = '" . $filecat .
        "' ORDER BY name"
    );
    if (mysqli_num_rows($rubrics)) {
        while ($ds = mysqli_fetch_assoc($rubrics)) {
            delete_category($ds[ 'filecatID' ]);
        }
    }
    safe_query("DELETE FROM " . PREFIX . "plugins_files_categories WHERE filecatID='" . $filecat . "'");
    $files = safe_query("SELECT * FROM " . PREFIX . "plugins_files WHERE filecatID='" . $filecat . "'");
    while ($ds = mysqli_fetch_array($files)) {
        if (stristr($ds[ 'file' ], "http://") || stristr($ds[ 'file' ], "ftp://")) {
            @unlink('../downloads/' . $ds[ 'file' ]); #>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> muss geprüft werden
        }
    }
    safe_query("DELETE FROM " . PREFIX . "plugins_files WHERE filecatID='" . $filecat . "'");
}

if ($action == "save") {
    
        if(isset($_POST['unit'])){ $unit = $_POST['unit']; }else{ $unit = ''; }
        if(isset($_POST['poster'])){ $poster = $_POST['poster']; }else{ $poster = ''; }
        if(isset($_POST['filecat'])){ $filecat = $_POST['filecat']; }else{ $filecat = ''; }
        if(isset($_POST['filename'])){ $filename = $_POST['filename']; }else{ $filename = ''; }
        if(isset($_POST['fileurl'])){ $fileurl = $_POST['fileurl']; }else{ $fileurl = ''; }
        if(isset($_POST['filesize'])){ $filesize = unit_to_size($_POST[ 'filesize' ], $_POST[ 'unit' ]); }else{ $filesize= '0'; }
        if(isset($_POST['info'])){ $info = $_POST['info']; }else{ $info = ''; }
        if(isset($_POST['accesslevel'])){ $accesslevel = $_POST['accesslevel']; }else{ $accesslevel = ''; }
        if(isset($_POST['mirror1'])){ $mirror1 = $_POST['mirror1']; }else{ $mirror1 = ''; }
        if(isset($_POST['mirror2'])){ $mirror2 = $_POST['mirror2']; }else{ $mirror2 = ''; }


        // MIRRORS

        $mirrors = array();
        if (isFileURL($mirror1)) {
            $mirrors[] = $mirror1;
        }
        if (isFileURL($mirror2)) {
            $mirrors[] = $mirror2;
        }
        $mirrors = implode("||", $mirrors);

        $error = array();

        $_language->readModule('formvalidation', true);

        $upload = new \webspell\HttpUpload('upfile');

        $filepath = $plugin_path."downloads/";

        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $des_file = $filepath . $upload->getFileName();
                if (file_exists($des_file)) {
                    $des_file = $filepath . time() . "_" . $upload->getFileName();
                }
                if ($upload->saveAs($des_file, false)) {
                    @chmod($des_file, $new_chmod);
                    $file = basename($des_file);
                    $filesize = $upload->getSize();
                } else {
                    $error[] = $plugin_language[ 'file_already_exists' ];
                }

            } else {
                echo generateErrorBox($upload->translateError());
            }

        } elseif (!empty($fileurl)) {
            $file = $fileurl;
        } else {
            $error[] = $plugin_language[ 'no_file_uploaded' ];
        }


        if (count($error)) {
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $error);
        } else {
            if (
                safe_query(
                    "INSERT INTO
                        `" . PREFIX . "plugins_files` (
                            `filecatID`,
                            `poster`,
                            `date`,
                            `update`,
                            `filename`,
                            `filesize`,
                            `info`,
                            `file`,
                            `mirrors`,
                            `downloads`,
                           `accesslevel`,
                            `votes`,
                            `points`,
                            `rating`
                        )
                        VALUES (
                            '" . $filecat . "',
                            '" . $poster . "',
                            '" . time() . "',
                            '" . time() . "',
                            '" . $filename . "',
                            '" . $filesize . "',
                            '" . $info . "',
                            '" . $file . "',
                            '" . $mirrors . "',
                            '0',
                             '" . $accesslevel . "',
                            '0',
                            '0',
                            '0'
                        )"
                )
            ) {
                redirect("
                    admincenter.php?site=admin_files&amp;file=" . mysqli_insert_id($_database), 
                    generateSuccessBox($plugin_language[ 'file_created' ]),
                    "3");
            } else {
                redirect("admincenter.php?site=admin_files", generateSuccessBox($plugin_language[ 'file_not_created' ]), "3");
            }
        
    }
} elseif ($action == "saveedit") {
    
        $fileID = $_POST[ 'fileID' ];
        $upfile = $_FILES[ 'upfile' ];
        $filecat = $_POST[ 'filecat' ];
        $filename = $_POST[ 'filename' ];
        $fileurl = $_POST[ 'fileurl' ];
        $filesize = unit_to_size($_POST[ 'filesize' ], $_POST[ 'unit' ]);
        $info = $_POST[ 'info' ];
        $accesslevel = $_POST[ 'accesslevel' ];
        $mirror1 = $_POST[ 'mirror2' ];
        $mirror2 = $_POST[ 'mirror3' ];
        unset($file);

        // MIRRORS
        $mirrors = array();
        if (isFileURL($mirror1)) {
            $mirrors[] = $mirror1;
        }
        if (isFileURL($mirror2)) {
            $mirrors[] = $mirror2;
        }
        $mirrors = implode("||", $mirrors);

        $error = array();

        $_language->readModule('formvalidation', true);

        $upload = new \webspell\HttpUpload('upfile');

        $filepath = $plugin_path."downloads/";

        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $des_file = $filepath . $upload->getFileName();
                if (file_exists($des_file)) {
                    $des_file = $filepath . time() . "_" . $upload->getFileName();
                }
                if ($upload->saveAs($des_file)) {
                    @chmod($des_file, $new_chmod);
                    $file = basename($des_file);
                    $filesize = $upload->getSize();
                }
            } else {
                echo generateErrorBox($upload->translateError());
            }

        } elseif (isFileURL($fileurl)) {
            $file = $fileurl;
        }

        if (count($error)) {
            echo generateErrorBoxFromArray($_language->module['errors_there'], $fehler);
        } else {
            safe_query(
                "UPDATE
                    `" . PREFIX . "plugins_files`
                SET
                    `filecatID` = '" . $filecat . "',
                    `mirrors` = '" . $mirrors . "',
                    `filename` = '" . $filename . "',
                    `filesize` = '" . $filesize . "',
                    `info` = '" . $info . "',
                    `update` = '" . time() . "',
                    `accesslevel` = '" . $accesslevel . "'
                WHERE
                    `fileID` = '" . (int)$fileID."'"
            );

            if (isset($file)) {
                safe_query(
                    "UPDATE `" . PREFIX . "plugins_files` SET `file` = '" . $file . "' WHERE `fileID` = '" . (int)$fileID."'"
                );
            }
            redirect(
                "admincenter.php?site=admin_files&amp;file=" . (int)$fileID,
                generateSuccessBox($plugin_language[ 'successful' ])
            );
        }
    
    
} elseif ($action == "delete") {
    if (!isfilesadmin($userID)) {
        echo generateErrorBox($plugin_language[ 'no_access' ]);
    } else {
        $file = (int)$_GET['linkID'];

        if ($file) {
            $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_files` WHERE `fileID` = '".$file."'");
            if (mysqli_num_rows($ergebnis)) {
                $ds = mysqli_fetch_array($ergebnis);

                if (isFileURL($ds[ 'file' ]) === false) {
                    @unlink(''.$plugin_path.'/downloads/' . $ds[ 'file' ] .'');
                }
                safe_query("DELETE FROM `" . PREFIX . "plugins_files` WHERE `fileID` = '" . (int)$file."'");
                redirect("admincenter.php?site=admin_files", generateSuccessBox($plugin_language[ 'file_deleted' ]), "3");
            } else {
                redirect("admincenter.php?site=admin_files", generateErrorBox($plugin_language[ 'file_not_deleted' ]), "3");
            }
        } else {
            redirect("admincenter.php?site=admin_files", generateErrorBox($plugin_language[ 'cant_delete_without_fileID' ]), "3");
        }
    }
} elseif (isset($_POST[ 'sortieren' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $sort = $_POST[ 'sort' ];
        if (is_array($sort)) {
            foreach ($sort as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE " . PREFIX . "plugins_files SET sort='$sorter[1]' WHERE fileID='$sorter[0]' ");
                redirect("admincenter.php?site=admin_files", "", 0);
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'cat_sortieren' ])) {
    

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $sort = $_POST[ 'sort' ];
        if (is_array($sort)) {
            foreach ($sort as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE " . PREFIX . "plugins_files_categories SET sort='$sorter[1]' WHERE filecatID='" . $sorter[0] . "'");
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }    
} elseif ($action == "new") {

            $data_array = array();
            $data_array['$title']=$plugin_language['files'];
            $data_array['$files_cl']=$plugin_language['files_cl'];
            $template = $GLOBALS["_template"]->loadTemplate("admin_files","details_head", $data_array, $plugin_path);
            echo $template;
        
            $filecats = generateFileCategoryOptions();

            $access = '<option value="0">' . $plugin_language[ 'all' ] . '</option><option value="1">' .
            $plugin_language[ 'registered' ] . '</option><option value="2">' . $plugin_language[ 'clanmember' ] .
            '</option>';

        
        if ($filecats == '') {
            redirect('admincenter.php?site=admin_files', $plugin_language[ 'first_create_file-category' ], '3');
        } else {
            
            $data_array = array();
            $data_array['$userID'] = $userID;
            $data_array['$filecats'] = $filecats;
            $data_array['$access'] = $access;

            $data_array['$filename']=$plugin_language['filename'];
            $data_array['$category_cl']=$plugin_language['category_cl'];
            $data_array['$info_description']=$plugin_language['info_description'];
            $data_array['$accesslevel']=$plugin_language['accesslevel'];
            $data_array['$file-upload']=$plugin_language['file-upload'];
            $data_array['$extern-link']=$plugin_language['extern-link'];
            $data_array['$file-size_e']=$plugin_language['file-size_e'];
            $data_array['$mirror']=$plugin_language['mirror'];
            $data_array['$upload']=$plugin_language['upload'];
            $data_array['$you_have_to_enter_filename']=$plugin_language['you_have_to_enter_filename'];
            $data_array['$you_have_to_enter_file']=$plugin_language['you_have_to_enter_file'];

            $template = $GLOBALS["_template"]->loadTemplate("admin_files","new", $data_array, $plugin_path);
            echo $template;
            
        }
    

            $template = $GLOBALS["_template"]->loadTemplate("admin_files","details_foot", $data_array, $plugin_path);
            echo $template;

} elseif ($action == "edit") {

            $data_array = array();
            $data_array['$title']=$plugin_language['files'];
            $data_array['$files_cl']=$plugin_language['files_cl'];
            $template = $GLOBALS["_template"]->loadTemplate("admin_files","details_head", $data_array, $plugin_path);
            echo $template;

    $fileID = intval($_GET[ 'fileID' ]);
    if ($fileID) {
        
            

            $filecats = generateFileCategoryOptions();

            $file = mysqli_fetch_array(
                safe_query("SELECT * FROM `" . PREFIX . "plugins_files` WHERE `fileID` = '" . (int)$fileID."'")
            );
            $filecats = str_replace(
                'value="' . $file[ 'filecatID' ] . '"',
                'value="' . $file[ 'filecatID' ] . '" selected="selected"',
                $filecats
            );
            $accessmenu = '<option value="0">' . $plugin_language[ 'all' ] . '</option><option value="1">' .
                $plugin_language[ 'registered' ] . '</option><option value="2">' .
                $plugin_language[ 'clanmember' ] . '</option>';
            $access = str_replace(
                'value="' . $file[ 'accesslevel' ] . '"',
                'value="' . $file[ 'accesslevel' ] . '" selected="selected"',
                $accessmenu
            );

             $sizeinfo = strtolower(detectfilesize($file[ 'filesize' ]));
            $sizeinfo = explode(" ", $sizeinfo);

            $filesize = $sizeinfo[ 0 ];
            $description = htmlspecialchars($file[ 'info' ]);
            $name = htmlspecialchars($file[ 'filename' ]);
            $unit = '
                <option value="b">Byte</option>
                <option value="kb">KByte</option>
                <option value="mb">MByte</option>
                <option value="gb">GByte</option>
                <option value="tb">TByte</option>';

            switch($sizeinfo[1]) {
                case 'byte': $unit = str_replace('value="b"','value="b" selected="selected"', $unit); break;
                case 'kb': $unit = str_replace('value="kb"','value="kb" selected="selected"', $unit); break;
                case 'mb': $unit = str_replace('value="mb"','value="mb" selected="selected"', $unit); break;
                case 'gb': $unit = str_replace('value="gb"','value="gb" selected="selected"', $unit); break;
                case 'tb': $unit = str_replace('value="tb"','value="tb" selected="selected"', $unit); break;
            }
            $extern = '';
            if (isFileURL($file[ 'file' ])) {
                $extern = $file[ 'file' ];
            }
            // FILE-MIRRORS (remember: the primary mirror is still the uploaded or external file!)
            $mirror2 = "";
            $mirror3 = "";
            $mirrors = $file[ 'mirrors' ];
            if ($mirrors) {
                if (stristr($mirrors, "||")) {
                    $secondarymirror = explode("||", $mirrors);
                    $mirror2 = $secondarymirror[ 0 ];
                    $mirror3 = $secondarymirror[ 1 ];
                } else {
                    $mirror2 = $mirrors;
                }
            }
            $fileinfo = $file[ 'info' ];


            $data_array = array();
            $data_array['$name'] = $name;
            $data_array['$filecats'] = $filecats;
            $data_array['$description'] = $description;
            $data_array['$access'] = $access;
            $data_array['$extern'] = $extern;
            $data_array['$unit'] = $unit;
            $data_array['$mirror2'] = $mirror2;
            $data_array['$mirror3'] = $mirror3;
            $data_array['$fileID'] = $fileID;
            $data_array['$filesize'] = $filesize;

            $data_array['$filename']=$plugin_language['filename'];
            $data_array['$category_cl']=$plugin_language['category_cl'];
            $data_array['$info_description']=$plugin_language['info_description'];
            $data_array['$accesslevel']=$plugin_language['accesslevel'];
            $data_array['$file-upload']=$plugin_language['file-upload'];
            $data_array['$extern-link']=$plugin_language['extern-link'];
            $data_array['$file-size_e']=$plugin_language['file-size_e'];
            $data_array['$mirror']=$plugin_language['mirror'];
            $data_array['$update']=$plugin_language['update'];
            $data_array['$you_have_to_enter_filename']=$plugin_language['you_have_to_enter_filename'];

            $template = $GLOBALS["_template"]->loadTemplate("admin_files","edit", $data_array, $plugin_path);
            echo $template;            
        
    } else {
        redirect("admincenter.php?site=admin_files", generateErrorBox($plugin_language[ 'cant_edit_without_fileID' ]), "3");
    }

            $template = $GLOBALS["_template"]->loadTemplate("admin_files","details_foot", $data_array, $plugin_path);
            echo $template;

}
# admin_files_categories

if (isset($_POST[ 'files_categories_save' ])) {
    
    if (mb_strlen($_POST[ 'name' ]) > 0) {
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
            safe_query(
                "INSERT INTO " . PREFIX . "plugins_files_categories ( name, subcatID ) values( '" . $_POST[ 'name' ] .
                "', '" . $_POST[ 'subcat' ] . "' ) "
            );
        } else {
            echo $plugin_language[ 'transaction_invalid' ];
        }
    } else {
        redirect("admincenter.php?site=admin_files&action=admin_files_categories&amp;action=add", $plugin_language[ 'enter_name' ], 3);
    }
} elseif (isset($_POST[ 'files_categories_saveedit' ])) {

    if (mb_strlen($_POST[ 'name' ]) > 0) {
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
            safe_query(
                "UPDATE " . PREFIX . "plugins_files_categories SET name='" . $_POST[ 'name' ] . "', subcatID = '" .
                $_POST[ 'subcat' ] . "' WHERE filecatID='" . $_POST[ 'filecatID' ] . "'"
            );
        } else {
            echo $plugin_language[ 'transaction_invalid' ];
        }
    } else {
        redirect(
            "admincenter.php?site=admin_files&action=admin_files_categories&amp;action=edit&amp;filecatID=" . $_POST[ 'filecatID' ],
            $plugin_language[ 'enter_name' ],
            3
        );
    }
    
} elseif (isset($_GET[ 'files_categories_delete' ])) {
   
   $filecatID = $_GET[ 'filecatID' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        delete_category($filecatID);
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    } 

} elseif (isset($_POST[ 'files_settings_save' ])) {

 $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE
                " . PREFIX . "plugins_files_settings
            SET
                sc_files='" . intval($_POST[ 'sc_files' ]) . "' "
        );
        
        redirect("admincenter.php?site=admin_files&action=admin_files_settings", "", 0);
    } else {
        redirect("admincenter.php?site=admin_files&action=admin_files_settings", $plugin_language[ 'transaction_invalid' ], 3);
    }

} 


if ($action == "admin_files_settings") {
    
    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_files_settings");
    $ds = mysqli_fetch_array($settings);

    $sc_files = "<option value='1'>" . $plugin_language[ 'files_top' ] . "</option>
                <option value='2'>" .
        $plugin_language[ 'files_latest' ] . "</option>";
    $sc_files = str_replace(
        "value='" . $ds[ 'sc_files' ] . "'",
        "value='" . $ds[ 'sc_files' ] . "' selected='selected'",
        $sc_files
    );

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    
    echo'<form method="post" action="admincenter.php?site=admin_files&action=admin_files_settings">
        <div class="card">
            <div class="card-header"> <i class="bi bi-images"></i> '.$plugin_language['settings' ] . '
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_files">'.$plugin_language['title' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_files&action=admin_files_settings">'.$plugin_language['settings' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>

            <div class="card-body">
            <div class="row">
                    <div class="col-md-6">
                        <div class="row bt"><div class="col-md-6">'.$plugin_language['files_settings' ] . ':</div>
                        <div class="col-md-6">
                        <select class="form-select" name="sc_files" onmouseover="showWMTT("id62")"onmouseout="hideWMTT()">'.$sc_files.'</select>
                        </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="mb-3 row">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="filessetID" value="'.$ds['filessetID'].'" />
                    <button class="btn btn-warning" type="submit" name="files_settings_save">'.$plugin_language['edit' ] . '</button>
                    </div>
                </div>
                
            </div>
        </div>
    </form>';


# admin_files_categories

} elseif ($action == "admin_files_categories_add") {
   
$filecats = generateFileCategoryOptions('<option value="0">' . $plugin_language[ 'main' ] . '</option>', '- ');
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
  
   echo'<div class="card">
  <div class="card-header">
                            <i class="bi bi-download"></i> '.$plugin_language['files_categories'].'
                        </div>
             <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_files">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_files&action=admin_files_categories">'.$plugin_language['files_categories'].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['add_category'].'</li>
                </ol>
            </nav>
            <div class="card-body">';

    echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_files&action=admin_files_categories">
    <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="name" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['sub_category'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
     <select class="form-select" name="subcat">'.$filecats.'</select></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="hidden" name="captcha_hash" value="'.$hash.'" />
      <button class="btn btn-success" type="submit" name="files_categories_save" />'.$plugin_language['add_category'].'</button>
    </div>
  </div>
  </form></div>
  </div>';

} elseif ($action == "admin_files_categories_edit") {

 

 
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_files_categories WHERE filecatID='" . $_GET[ 'filecatID' ] . "'");
    $ds = mysqli_fetch_array($ergebnis);

    $filecats = generateFileCategoryOptions('<option value="0">' . $plugin_language[ 'main' ] . '</option>', '- ');

    $filecats = str_replace(
        'value="' . $ds[ 'subcatID' ] . '"',
        'value="' . $ds[ 'subcatID' ] . '" selected="selected"',
        $filecats
    );
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    
  echo'<div class="card">
  <div class="card-header">
                            <i class="bi bi-download"></i> '.$plugin_language['files_categories'].'
                        </div>
        
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_files">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_files&action=admin_files_categories">'.$plugin_language['files_categories'].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['edit_category'].'</li>
                </ol>
            </nav>
            <div class="card-body">';
  
  echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_files&action=admin_files_categories" enctype="multipart/form-data">
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="name" value="'.getinput($ds['name']).'" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['sub_category'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
     <select class="form-select" name="subcat">'.$filecats.'</select></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="filecatID" value="'.$ds['filecatID'].'" />
      <button class="btn btn-success" type="submit" name="files_categories_saveedit" />'.$plugin_language['edit_category'].'</button>
    </div>
  </div>
  </form></div>
  </div>';  
   
} elseif ($action == "admin_files_categories") {

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

  echo'<div class="card">
  <div class="card-header">
                            <i class="bi bi-download"></i> '.$plugin_language['files_categories'].'
                        </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_files">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_files&action=admin_files_categories">'.$plugin_language['files_categories'].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['add_category'].'</li>
                </ol>
            </nav>
                        <div class="card-body">
            <div class="mb-3 row row">
              <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
              <div class="col-md-8">
              <a href="admincenter.php?site=admin_files&action=admin_files_categories_add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_category' ] . '</a>
              <a href="admincenter.php?site=admin_files&action=admin_files_categories_sort" class="btn btn-primary" type="button">' . $plugin_language[ 'files_categories' ] . ' ' . $plugin_language[ 'to_sort' ] . '</a>
              </div>
            </div>';
  
    echo'<table class="table">
    <thead>
      <th style="width: 80%"><b>'.$plugin_language['category_name'].'</b></th>
      <th class="text-right"><b>'.$plugin_language['actions'].'</b></th>
    </thead>';

     $overview = generate_overview();
    echo $overview;

    echo'</table>';

echo '</div>
  </div>';


} elseif ($action == "admin_files_categories_sort") {

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

  echo'<div class="card">
  <div class="card-header">
                            <i class="bi bi-download"></i> '.$plugin_language['files_categories'].'
                        </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_files">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_files&action=admin_files_categories">'.$plugin_language['files_categories'].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['files_categories'].' ' . $plugin_language[ 'to_sort' ] . '</li>
                </ol>
            </nav>
                        <div class="card-body">';
  
    echo'<form method="post" action="admincenter.php?site=admin_files&action=admin_files_categories_sort">
    <table class="table">
    <thead>
      <th style="width: 90%"><b>'.$plugin_language['category_name'].' Sort</b></th>
      <th class="text-right"><b>'.$plugin_language['sort'].'</b></th>
    </thead>';

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_files_categories WHERE `subcatID` = '0' ORDER BY sort");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(filecatID) as cnt FROM " . PREFIX . "plugins_files_categories"));
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
                
        echo'<tr>
        
        <td>'.getinput($ds['name']).'</td>
        
        <td><select name="sort[]">';
        
    for ($n = 1; $n <= $anz; $n++) {
            if ($ds[ 'sort' ] == $n) {
                echo '<option value="' . $ds[ 'filecatID' ] . '-' . $n . '" selected="selected">' . $n . '</option>';
            } else {
                echo '<option value="' . $ds[ 'filecatID' ] . '-' . $n . '">' . $n . '</option>';
            }
        }
    
        echo'</select></td>
        </tr>';
          
        $i++;
    }

    echo '<tr>
<td class="td_head" colspan="2" align="right"><input type="hidden" name="captcha_hash" value="' . $hash .
    '"><input class="btn btn-primary" type="submit" name="cat_sortieren" value="' . $plugin_language[ 'to_sort' ] . '" /></td>
</tr>';

    echo'</table></form>';

echo '</div>
  </div>';

} elseif ($action == "") {

echo '<div class="card">
            <div class="card-header">
                            <i class="bi bi-download"></i> ' . $plugin_language[ 'title' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_files">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>      
                        <div class="card-body">

<div class="mb-3 row row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_files&amp;action=new" class="btn btn-primary" type="button">' . $plugin_language[ 'new_file' ] . '</a>
      <a href="admincenter.php?site=admin_files&action=admin_files_categories" class="btn btn-primary" type="button">' . $plugin_language[ 'new_category' ] . '</a>
      <a href="admincenter.php?site=admin_files&action=admin_files_settings" class="btn btn-primary" type="button">' . $plugin_language[ 'settings' ] . '</a>
    </div>
  </div>';


    echo'<form method="post" action="admincenter.php?site=admin_files">
  <table class="table">
    <thead>
      <th style="width: 70%"><b>' . $plugin_language['link'] . '</b></th>
      <th><b>' . $plugin_language['actions'] . '</b></th>
      <th><b>' . $plugin_language['sort'] . '</b></th>
    </thead>';

    $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_files_categories`");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(filecatID) as cnt FROM `" . PREFIX . "plugins_files_categories`"));
    $anz = $tmp[ 'cnt' ];

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    while ($ds = mysqli_fetch_array($ergebnis)) {

    echo '<tr>
            <td class="table-secondary" colspan="3"><b>' . $ds[ 'name' ] . '</b>
            </td>
        </tr>';

        $link = safe_query("SELECT * FROM `" . PREFIX . "plugins_files` WHERE `filecatID` = '$ds[filecatID]'");  
        $tmp = mysqli_fetch_assoc(
            safe_query(
                "SELECT count(fileID) as cnt FROM `" . PREFIX . "plugins_files` WHERE `filecatID` = '$ds[filecatID]'"
            )
        );
        $anzfile = $tmp[ 'cnt' ];

        $i = 1;
        while ($db = mysqli_fetch_array($link)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }

            $filename = $db[ 'filename' ];

            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($filename);
            $filename = $translate->getTextByLanguage($filename);
                
            echo '<tr>
        <td>&nbsp;&nbsp;- '.$filename.'</td>
        <td><a href="admincenter.php?site=admin_files&amp;action=edit&amp;fileID=' . $db[ 'fileID' ] . '" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_files&amp;action=delete&amp;linkID=' .
                        $db[ 'fileID' ] . '&amp;captcha_hash=' . $hash . '">
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
      <div class="modal-body"><p>' . $plugin_language['really_delete_file'] . '</p>
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
        <td><select name="sort[]">';
            for ($j = 1; $j <= $anzfile; $j++) {
                if ($db[ 'sort' ] == $j) {
                    echo '<option value="' . $db[ 'fileID' ] . '-' . $j . '" selected="selected">' . $j .
                    '</option>';
                } else {
                    echo '<option value="' . $db[ 'fileID' ] . '-' . $j . '">' . $j . '</option>';
                }
            }
            echo '</select></td></tr>';
      
      $i++;
        }
    }

    echo'<tr>
      <td colspan="3" align="right"><input type="hidden" name="captcha_hash" value="'.$hash.'" />
      <button class="btn btn-primary" type="submit" name="sortieren" />'.$plugin_language['to_sort'].'</button></td>
    </tr>
  </table>
  </form>';
}
echo '</div></div>';
