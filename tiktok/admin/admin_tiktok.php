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
$plugin_language = $pm->plugin_language("tiktok", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='tiktok'");
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
                            <i class="bi bi-tiktok"></i> ' . $plugin_language[ 'title' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_tiktok">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_tiktok' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';


    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

echo '<form method="post" id="post" name="post" action="admincenter.php?site=admin_tiktok" enctype="multipart/form-data" onsubmit="return chkFormular();">
 <div class="row">
     <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'tiktok_name' ] . '</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="name" size="60" maxlength="255" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['tiktok_address_id'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="tiktok_id" size="60" maxlength="255" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'is_displayed' ] . ':</label>
  <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
  <input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'is_displayedw' ] . ':</label>
  <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
  <input class="form-check-input" type="checkbox" name="displayedw" value="1" checked="checked" />
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['tiktok_height'].':</label>
    <div class="col-sm-2"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="tiktok_height" size="80" maxlength="255" /></em></span>
    </div>
    </div>

<div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-success" type="submit" name="save"  />' . $plugin_language[ 'add_tiktok' ] . '</button>
    </div>
  </div>
  </div>
</form></div></div>';
} elseif ($action == "edit") {


    echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-tiktok"></i> ' . $plugin_language[ 'title' ] . '</div>
                        
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_tiktok">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_tiktok' ] . '</li>
                </ol>
            </nav> 
<div class="card-body row">
<div class="col-8">';


    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_tiktok WHERE tiktokID='" . $_GET[ "tiktokID" ] ."'"
        )
    );
    

    if ($ds[ 'displayed' ] == 1) {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />';
    } else {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" />';
    }
    
    if ($ds[ 'displayedw' ] == 1) {
        $displayedw = '<input class="form-check-input" type="checkbox" name="displayedw" value="1" checked="checked" />';
    } else {
        $displayedw = '<input class="form-check-input" type="checkbox" name="displayedw" value="1" />';
    }
    
    

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $data_array = array();

echo '<form method="post" id="post" name="post" action="admincenter.php?site=admin_tiktok" enctype="multipart/form-data" onsubmit="return chkFormular();">

 
     <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'tiktok_name' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="name" size="80" maxlength="255" value="' . getinput($ds[ 'tiktok_name' ]) . '" /></em></span>
    </div>
   </div>
   <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['tiktok_address_id'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="tiktok_id" size="80" maxlength="255" value="' . getinput($ds[ 'tiktok_id' ]) . '" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'is_displayed' ] . ':</label>
  <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
  ' . $displayed . '
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'is_displayedw' ] . ':</label>
  <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
  ' . $displayedw . '
    </div>
     </div>
  
    <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['tiktok_height'].':</label>
    <div class="col-sm-2"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="tiktok_height" size="80" maxlength="255" value="' . getinput($ds[ 'tiktok_height' ]) . '" /></em></span>
    </div>
    </div>
    </div>
    
    <div class="col d-flex justify-content-center">
    <div data-placeholder data-visible>
        <div style="width: 100%;height: 500px;margin-bottom: 35px;margin-left: -7px;">
        <iframe src="https://www.tiktok.com/embed/v2/' . getinput($ds[ 'tiktok_id' ]) . '?embedFrom=webapp_preview" frameborder="0" style="visibility: unset;width: 326px;height: ' . getinput($ds[ 'tiktok_height' ]) . 'px;transform: scale(0.7);transform-origin: top center;border: none;"></iframe>
    </div>
    </div></div>
    <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="tiktokID" value="' . $ds[ 'tiktokID' ] . '" />
        <button class="btn btn-warning" type="submit" name="saveedit"  />' . $plugin_language[ 'edit_tiktok' ] . '</button>
    </div></div>
</form>
</div></div>';
} elseif (isset($_POST[ 'sortieren' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $sort = $_POST[ 'sort' ];
        if (is_array($sort)) {
            foreach ($sort as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE " . PREFIX . "plugins_tiktok SET sort='$sorter[1]' WHERE tiktokID='$sorter[0]' ");
                redirect("admincenter.php?site=admin_tiktok", "", 0);
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "save" ])) {
    $tiktok_name = $_POST[ "name" ];
    $tiktok_id = $_POST[ "tiktok_id" ];
    $tiktok_height = $_POST[ "tiktok_height" ];

    if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    if (!$displayed) {
        $displayed = 0;
    }
    
    if (isset($_POST[ "displayedw" ])) {
        $displayedw = 1;
    } else {
        $displayedw = 0;
    }
    if (!$displayedw) {
        $displayedw = 0;
    }


    

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "INSERT INTO " . PREFIX . "plugins_tiktok (tiktokID, tiktok_name, tiktok_id, displayed, displayedw, tiktok_height, sort) values('', '" . $tiktok_name . "', '" . $tiktok_id . "', '" . $displayed . "', '" . $displayedw . "', '" . $tiktok_height . "', '1')"
        );

        $id = mysqli_insert_id($_database);

        $errors = array();

        
        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_tiktok", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "saveedit" ])) {
    $tiktok_name = $_POST[ "name" ];
    $tiktok_id = $_POST[ "tiktok_id" ];
    $tiktok_height = $_POST[ "tiktok_height" ];
    
    
    if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    
    if (isset($_POST[ "displayedw" ])) {
        $displayedw = 1;
    } else {
        $displayedw = 0;
    }
    
    
    
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        

        safe_query(
            "UPDATE " . PREFIX . "plugins_tiktok SET tiktok_name='" . $tiktok_name . "', tiktok_id='" . $tiktok_id . "', displayed='" . $displayed . "', displayedw='" . $displayedw . "', tiktok_height='" . $tiktok_height . "' WHERE tiktokID='" .
            $_POST[ "tiktokID" ] . "'"
        );
		
		

        $id = $_POST[ 'tiktokID' ];

        $errors = array();

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation', true);

        

        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_tiktok", "", 0);
        }
    } else {
		   
	   echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_GET[ "delete" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_tiktok WHERE tiktokID='" . $_GET[ "tiktokID" ] . "'");
        $data = mysqli_fetch_assoc($get);

        if (safe_query("DELETE FROM " . PREFIX . "plugins_tiktok WHERE tiktokID='" . $_GET[ "tiktokID" ] . "'")) {
           
            redirect("admincenter.php?site=admin_tiktok", "", 0);
        } else {
            redirect("admincenter.php?site=admin_tiktok", "", 0);
        }
    } else {
		print_r($plugin_language); return false;
        $_language->readModule('formvalidation', true);  
        echo $_language->module[ 'transaction_invalid' ];
    }
}




if ($action == "") {


echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-tiktok"></i> ' . $plugin_language[ 'title' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_tiktok">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="form-group row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_tiktok&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_tiktok' ] . '</a>      
      <a href="admincenter.php?site=settings&action=social_setting" class="btn btn-primary" data-toggle="tooltip" data-html="true" title="'.$plugin_language['tooltip_1'].'">' . $plugin_language[ 'social_setting' ] . '</a>
    </div>
  </div>

<div class="table-responsive">
    
    <form method="post" action="admincenter.php?site=admin_tiktok">

<table class="table">
        <thead>
        <tr>
            <th width="19%" class="title"><b>' . $plugin_language[ 'tiktok_name' ] . '</b></th>
            <th width="19%" class="title"><b>' . $plugin_language[ 'tiktok_address_id' ] . '</b></th>
            <th width="15%" class="title"><b>' . $plugin_language[ 'is_displayed' ] . '</b></th>
            <th width="15%" class="title"><b>' . $plugin_language[ 'is_displayedw' ] . '</b></th>
            <th width="10%" class="title"><b>' . $plugin_language[ 'actions' ] . '</b></th>
            <th width="8%" class="title"><b>' . $plugin_language[ 'sort' ] . '</b></th>
            
        </tr>
        </thead>
        <tbody>
   
       
        ';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_tiktok ORDER BY sort");
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

            $ds[ 'displayedw' ] == 1 ?
            $displayedw = '<font color="green"><b>' . $plugin_language[ 'yes' ] . '</b></font>' :
            $displayedw = '<font color="red"><b>' . $plugin_language[ 'no' ] . '</b></font>';
            
            
            
                $tiktok_name = $ds[ 'tiktok_name' ];
                $tiktok_id = $ds[ 'tiktok_id' ];
            
            echo '<tr>
            <td width="19%" class="' . $td . '">' . $tiktok_name . '</td>

            <td width="19%" class="' . $td . '">' . $tiktok_id . '</td>

            <td width="15%" class="' . $td . '">' . $displayed . '</td>

            <td width="15%" class="' . $td . '">' . $displayedw . '</td>
            
            <td width="10%" class="' . $td . '" >

            <a class="btn btn-warning" href="admincenter.php?site=admin_tiktok&amp;action=edit&amp;tiktokID=' . $ds[ 'tiktokID' ] .
                '" class="input">' . $plugin_language[ 'edit' ] . '</a>
                
                    <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_tiktok&amp;delete=true&amp;tiktokID='.$ds['tiktokID'].'&amp;captcha_hash='.$hash.'">
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
                    echo '<option value="' . $ds[ 'tiktokID' ] . '-' . $j . '" selected="selected">' . $j .
                        '</option>';
                } else {
                    echo '<option value="' . $ds[ 'tiktokID' ] . '-' . $j . '">' . $j . '</option>';
                }
            }
            echo '</select>
</td>
</tr>';
            $i++;
        }
        
    } else {
        echo '<tr><td class="td1" colspan="6">' . $plugin_language[ 'no_entries' ] . '</td></tr>';
    }

    echo '<tr>
<td class="td_head" colspan="6" align="right"><input type="hidden" name="captcha_hash" value="' . $hash .
    '"><br><input class="btn btn-success" type="submit" name="sortieren" value="' . $plugin_language[ 'to_sort' ] . '" /></td>
</tr>
</tbody></table>
</form></div></div>';
}
