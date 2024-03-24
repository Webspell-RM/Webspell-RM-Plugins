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
$plugin_language = $pm->plugin_language("admin_portfolio", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='videos'");
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

# admin_portfolio
if (isset($_POST[ 'save' ])) {
    if (!ispageadmin($userID) || !isnewsadmin($userID)) {
        echo generateAlert($plugin_language['no_access'], 'alert-danger');
    } else {
        safe_query(
            "INSERT INTO
                " . PREFIX . "plugins_portfolio (
                    portfoliocatID,
                    name,
                    text,
                    url
                )
            values (
                '" . (int)$_POST[ 'cat' ] . "',
                '" . strip_tags($_POST[ 'name' ]) . "',
                '" . $_POST[ 'text' ] . "',
                '" . $_POST[ 'url' ] . "'
            ) "
        );

        
        $filepath = $plugin_path."images/";

        $_language->readModule('formvalidation', true);

        $upload = new \webspell\HttpUpload('banner');

        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation =  getimagesize($upload->getTempFile());

                    if (is_array($imageInformation)) {
                        if ($imageInformation[0] < 2561 && $imageInformation[1] < 1441) {
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

                            $id = mysqli_insert_id($_database);
                            $file = $id.$endung;

                            if ($upload->saveAs($filepath.$file)) {
                                @chmod($filepath.$file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_portfolio SET banner='" . $file . "' WHERE portfolioID='" . $id . "'"
                                );
                            }
                        } else {
                            echo generateErrorBox(sprintf($plugin_language[ 'image_too_big' ], 2560, 1440));
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
    }
} elseif (isset($_POST[ 'saveedit' ])) {
    if (!ispageadmin($userID) || !isnewsadmin($userID)) {
        echo generateAlert($plugin_language['no_access'], 'alert-danger');
    } else {
        safe_query(
            "UPDATE
                " . PREFIX . "plugins_portfolio
            SET
                portfoliocatID='" . $_POST[ 'cat' ] . "',
                name='" . strip_tags($_POST[ 'name' ]) . "',
                text='" . $_POST[ 'text' ] . "',
                url='" . $_POST[ 'url' ] . "'
            WHERE
                portfolioID='" . $_POST[ 'portfolioID' ] . "'"
        );

        
        $filepath = $plugin_path."images/";
        $id = $_POST[ 'portfolioID' ];

        $_language->readModule('formvalidation', true);

        $upload = new \webspell\HttpUpload('banner');
        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation =  getimagesize($upload->getTempFile());

                    if (is_array($imageInformation)) {
                        if ($imageInformation[0] < 2561 && $imageInformation[1] < 1441) {
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

                            if ($upload->saveAs($filepath.$file)) {
                                @chmod($filepath.$file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_portfolio SET banner='" . $file . "' WHERE portfolioID='" . $id . "'"
                                );
                            }
                        } else {
                            echo generateErrorBox(sprintf($plugin_language[ 'image_too_big' ], 2560, 1440));
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
    }

}



if (isset($_GET[ 'delete' ])) {

   $filepath = $plugin_path."images/";
 

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
    
        $filepath = $plugin_path."images/";
        if (file_exists($filepath . $_GET['portfolioID'] . '.gif')) {
            @unlink($filepath . $_GET['portfolioID'] . '.gif');
        }
        if (file_exists($filepath . $_GET['portfolioID'] . '.jpg')) {
            @unlink($filepath . $_GET['portfolioID'] . '.jpg');
        }
        if (file_exists($filepath . $_GET['portfolioID'] . '.png')) {
            @unlink($filepath . $_GET['portfolioID'] . '.png');
        }

        safe_query("DELETE FROM " . PREFIX . "plugins_portfolio WHERE portfolioID='" . $_GET[ "portfolioID" ] . "'");


            redirect("admincenter.php?site=admin_portfolio", "", 0);
    }

} elseif (isset($_POST[ 'portfolio_categories_save' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('catname'))) {
            safe_query("INSERT INTO " . PREFIX . "plugins_portfolio_categories ( catname ) values( '" . $_POST[ 'catname' ] . "' ) ");
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'portfolio_categories_saveedit' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('catname'))) {
            safe_query(
                "UPDATE " . PREFIX . "plugins_portfolio_categories SET catname='" . $_POST[ 'catname' ] . "' WHERE portfoliocatID='" .
                $_POST[ 'portfoliocatID' ] . "'"
            );
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_GET[ 'portfolio_categories_delete' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        


        $ds = mysqli_fetch_array(
        safe_query(
            "SELECT
                `portfolioID`
            FROM
                `" . PREFIX . "plugins_portfolio`
            WHERE
                `portfoliocatID` = '" . (int)$_GET['portfoliocatID'] . "'"
        )
    );

    
    $ergebnis = safe_query(
                    "SELECT portfolioID FROM " . PREFIX . "plugins_portfolio WHERE portfoliocatID='" .
                    $_GET[ 'portfoliocatID' ] . "'"
                );
    while ($ds = mysqli_fetch_array($ergebnis)) {
    
        $filepath = $plugin_path."images/";
        if (file_exists($filepath . $ds[ 'portfolioID' ] . '.gif')) {
            @unlink($filepath . $ds[ 'portfolioID' ] . '.gif');
        }
        if (file_exists($filepath . $ds[ 'portfolioID' ] . '.jpg')) {
            @unlink($filepath . $ds[ 'portfolioID' ] . '.jpg');
        }
        if (file_exists($filepath . $ds[ 'portfolioID' ] . '.png')) {
            @unlink($filepath . $ds[ 'portfolioID' ] . '.png');
        }
    }

        safe_query("DELETE FROM " . PREFIX . "plugins_portfolio_categories WHERE portfoliocatID='" . $_GET[ 'portfoliocatID' ] . "'");
        safe_query("DELETE FROM " . PREFIX . "plugins_portfolio WHERE portfoliocatID='" . $_GET[ 'portfoliocatID' ] . "'");

    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
}



if ($action == "add") {
    if (ispageadmin($userID) || isnewsadmin($userID)) {
        $rubrics = safe_query("SELECT * FROM " . PREFIX . "plugins_portfolio_categories ORDER BY catname");
        $linkcats = '';
        while ($dr = mysqli_fetch_array($rubrics)) {
            $linkcats .= '<option value="' . $dr[ 'portfoliocatID' ] . '">' . htmlspecialchars($dr[ 'catname' ]) . '</option>';
        }
        
        $data_array = array();
        $data_array['$linkcats'] = $linkcats;
        $data_array['$portfolio']=$plugin_language['portfolio'];
        $data_array['$new_portfolio']=$plugin_language['new_portfolio'];
        $data_array['$portfoliorubric']=$plugin_language['portfoliorubric'];
        $data_array['$portfolioname']=$plugin_language['portfolioname'];
        $data_array['$portfoliotext']=$plugin_language['portfoliotext'];
        $data_array['$homepage']=$plugin_language['homepage'];
        $data_array['$banner']=$plugin_language['banner'];
        $data_array['$save_portfolio']=$plugin_language['save_portfolio'];

        
        $template = $GLOBALS["_template"]->loadTemplate("admin_portfolio","new", $data_array, $plugin_path);
        echo $template;


    } else {
        redirect(
            'admincenter.php?site=admin_portfolio',
            generateAlert($plugin_language[ 'no_access' ], 'alert-danger')
        );
    }
} elseif ($action == "edit") {
    $portfolioID = $_GET[ 'portfolioID' ];
    if (ispageadmin($userID) || isnewsadmin($userID)) {
        $ds = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_portfolio WHERE portfolioID='$portfolioID'"));

        $name = htmlspecialchars($ds[ 'name' ]);
        $text = htmlspecialchars($ds[ 'text' ]);
        $effects = htmlspecialchars($ds[ 'effects' ]);
        $url = htmlspecialchars($ds[ 'url' ]);
        
        $newsrubrics = safe_query("SELECT * FROM " . PREFIX . "plugins_portfolio_categories ORDER BY catname");
        if (mysqli_num_rows($newsrubrics)) {
            $linkcats = '';
            while ($dr = mysqli_fetch_array($newsrubrics)) {
                if ($ds[ 'portfoliocatID' ] == $dr[ 'portfoliocatID' ]) {
                    $portfoliocatID = $dr[ 'portfoliocatID' ];
                    $linkcats .= '<option value="' . $dr[ 'portfoliocatID' ] . '" selected>' .
                        htmlspecialchars($dr[ 'catname' ]) . '</option>';
                } else {
                    $linkcats .= '<option value="' . $dr[ 'portfoliocatID' ] . '">' . htmlspecialchars($dr[ 'catname' ]) .
                        '</option>';
                }
            }
        } else {
            $linkcats = '<option>' . $plugin_language[ 'no_categories' ] . '</option>';
        }

        $linkcats = str_replace(" selected", "", $linkcats);
        $linkcats =
            str_replace(
                'value="' . $ds[ 'portfoliocatID' ] . '"',
                'value="' . $ds[ 'portfoliocatID' ] . '" selected',
                $linkcats
            );
        if (!empty($ds[ 'banner' ])) {
        $pic = '<img class="img-thumbnail" style="width: 100%; max-width: 600px" src="../' . $filepath . $ds[ 'banner' ] . '" alt="">';
    } else {
        $pic = $plugin_language[ 'no_upload' ];
    }    
        
        $data_array = array();
        $data_array['$linkcats'] = $linkcats;
        $data_array['$name'] = $name;
        $data_array['$text'] = $text;
        $data_array['$url'] = $url;
        $data_array['$pic'] = $pic;
        $data_array['$portfolioID'] = $portfolioID;

        $data_array['$portfolio']=$plugin_language['portfolio'];
        $data_array['$edit_portfolio']=$plugin_language['edit_portfolio'];
        $data_array['$portfoliorubric']=$plugin_language['portfoliorubric'];
        $data_array['$portfolioname']=$plugin_language['portfolioname'];
        $data_array['$portfoliotext']=$plugin_language['portfoliotext'];
        $data_array['$homepage']=$plugin_language['homepage'];
        $data_array['$banner']=$plugin_language['banner'];
        $data_array['$update_portfolio']=$plugin_language['update_portfolio'];

        
        $template = $GLOBALS["_template"]->loadTemplate("admin_portfolio","edit", $data_array, $plugin_path);
        echo $template;

    } else {
        redirect(
            'admincenter.php?site=admin_portfolio',
            generateAlert($plugin_language[ 'no_access' ], 'alert-danger')
        );
    }
} elseif ($action == "") {

 echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-images"></i> ' . $plugin_language[ 'portfolio' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_portfolio">' . $plugin_language[ 'portfolio' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="form-group row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_portfolio&amp;action=add" class="btn btn-primary">' . $plugin_language[ 'new_portfolio' ] . '</a>
      <a href="admincenter.php?site=admin_portfolio&action=admin_portfolio_categories" class="btn btn-primary">' . $plugin_language[ 'new_category' ] . '</a>
    </div>
  </div>';




	echo'<form method="post" action="admincenter.php?site=admin_portfolio">
  <table class="table table-striped">
    <thead>
      <th><b>' . $plugin_language['portfolio'] . '</b></th>
      <th><b>' . $plugin_language['banner'] . '</b></th>
      <th><b>' . $plugin_language['actions'] . '</b></th>
      </thead>';

	$ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_portfolio_categories`");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(portfoliocatID) as cnt FROM `" . PREFIX . "plugins_portfolio_categories`"));
    $anz = $tmp[ 'cnt' ];

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    while ($ds = mysqli_fetch_array($ergebnis)) {
        echo '<tr>
            <td class="td_head" colspan="4">
                <b>' . $ds[ 'catname' ] . '</b><br>
                <small>' . $ds[ 'description' ] . '</small>
            </td>
        </tr>';

        $faq = safe_query("SELECT * FROM `" . PREFIX . "plugins_portfolio` WHERE `portfoliocatID` = '$ds[portfoliocatID]' ORDER BY `sort`");
        $tmp = mysqli_fetch_assoc(
            safe_query(
                "SELECT count(portfolioID) as cnt FROM `" . PREFIX . "plugins_portfolio` WHERE `portfoliocatID` = '$ds[portfoliocatID]'"
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
            

            echo '<tr>
        <td class="' . $td . '"><b>- '.getinput($db['name']).'</b></td>
        <td class="' . $td . '"><img class="img-thumbnail" style="width: 100px; max-width: 250px" align="center" src="../' . $filepath . $db[ 'banner' ] . '" alt="{img}" /></td>
        <td class="' . $td . '"><a href="admincenter.php?site=admin_portfolio&amp;action=edit&amp;portfolioID='.$db['portfolioID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_portfolio&amp;delete=true&amp;portfolioID='.$db['portfolioID'].'&amp;captcha_hash='.$hash.'">
        ' . $plugin_language['delete'] . '
        </button>
        <!-- Button trigger modal END-->

        <!-- Modal -->
        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'portfolio' ] . '</h5>
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
		}
	}

	echo'
  </table>
  </form>';
#}
echo '</div></div>';

# admin_portfolio_categories

} elseif ($action == "admin_portfolio_categories_add") {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

   echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-images"></i> '.$plugin_language['portfolio_categories'].'</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_portfolio">' . $plugin_language[ 'portfolio' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_portfolio&action=admin_portfolio_categories">' . $plugin_language[ 'portfolio_categories' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['add_category'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">';

    echo '<form method="post" action="admincenter.php?site=admin_portfolio&action=admin_portfolio_categories">
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td width="25%"><b>' . $plugin_language[ 'category_name' ] . ':</b></td>
      <td width="75%"><input class="form-control" type="text" name="catname" size="60" /></td>
    </tr>
    <tr>
      <td><input type="hidden" name="captcha_hash" value="' . $hash . '" /></td>
      </tr>
    <tr>
      <td><br><input class="btn btn-success" type="submit" name="portfolio_categories_save" value="' . $plugin_language[ 'add_category' ] . '" /></td>
    </tr>
  </table>
  </form></div></div>';


} elseif ($action == "admin_portfolio_categories_edit") {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-images"></i> '.$plugin_language['portfolio_categories'].'</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_portfolio">' . $plugin_language[ 'portfolio' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_portfolio&action=admin_portfolio_categories">' . $plugin_language[ 'portfolio_categories' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['edit_category'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">';


    $ergebnis =
        safe_query("SELECT * FROM " . PREFIX . "plugins_portfolio_categories WHERE portfoliocatID='" . $_GET[ 'portfoliocatID' ] . "'");
    $ds = mysqli_fetch_array($ergebnis);

    echo '<form method="post" action="admincenter.php?site=admin_portfolio&action=admin_portfolio_categories">
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td width="25%"><b>' . $plugin_language[ 'category_name' ] . ':</b></td>
      <td width="75%"><input class="form-control" type="text" name="catname" value="' . getinput($ds[ 'catname' ]) . '" size="60" /></td>
    </tr>
    <tr>
      <td><input type="hidden" name="captcha_hash" value="' . $hash .
        '" /><input type="hidden" name="portfoliocatID" value="' . $ds[ 'portfoliocatID' ] . '" /></td>
        </tr>
    <tr>
      <td><br><input class="btn btn-warning" type="submit" name="portfolio_categories_saveedit" value="' . $plugin_language[ 'edit_category' ] . '" /></td>
    </tr>
  </table>
  </form></div></div>';
} elseif ($action == "admin_portfolio_categories") {


     echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-images"></i> '.$plugin_language['portfolio_categories'].'</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_portfolio">' . $plugin_language[ 'portfolio' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_portfolio&action=admin_portfolio_categories">' . $plugin_language[ 'portfolio_categories' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

     <div class="form-group row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_portfolio&action=admin_portfolio_categories_add" class="btn btn-primary">' . $plugin_language[ 'new_category' ] . '</a>
    </div>
  </div>';


$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_portfolio_categories ORDER BY catname");

    
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
            <a class="btn btn-warning btn-sm" href="admincenter.php?site=admin_portfolio&action=admin_portfolio_categories_edit&amp;portfoliocatID=' . $ds[ 'portfoliocatID' ] .
                '" >' . $plugin_language[ 'edit' ] . '</a>

            

<!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_portfolio&action=admin_portfolio_categories&amp;portfolio_categories_delete=true&amp;portfoliocatID=' . $ds[ 'portfoliocatID' ] .
            '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     


        </td>

        <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'portfolio_categories' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_categories_delete'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->


              
        </tr>';

        $i++;
    }
    echo '</tbody></table>
    </div></div>';
}
?>