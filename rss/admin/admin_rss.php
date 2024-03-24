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
$plugin_language = $pm->plugin_language("rss", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='rss'");
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
                            <i class="bi bi-rss" style="font-size: 1rem;"></i> ' . $plugin_language[ 'title' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_rss">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_rss' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';


    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

echo '<form method="post" id="post" name="post" action="admincenter.php?site=admin_rss" enctype="multipart/form-data" onsubmit="return chkFormular();">
 <div class="row">
     <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'rss_name' ] . '</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="name" size="60" maxlength="255" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['rss_address_id'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="rss_id" size="60" maxlength="255" /></em></span>
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
    <label class="col-sm-2 control-label">'.$plugin_language['rss_num'].':</label>
    <div class="col-sm-2"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="rss_num" size="80" maxlength="255" /></em></span>
    </div>
    </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-success" type="submit" name="save"  />' . $plugin_language[ 'add_rss' ] . '</button>
    </div>
  </div>
  </div>
</form></div></div>';
} elseif ($action == "edit") {


    echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-rss" style="font-size: 1rem;"></i> ' . $plugin_language[ 'title' ] . '</div>
                        
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_rss">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_rss' ] . '</li>
                </ol>
            </nav> 
<div class="card-body">';


    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_rss WHERE rssID='" . $_GET[ "rssID" ] ."'"
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

echo '<form method="post" id="post" name="post" action="admincenter.php?site=admin_rss" enctype="multipart/form-data" onsubmit="return chkFormular();">
<div class="row">
 
     <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'rss_name' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="name" size="80" maxlength="255" value="' . getinput($ds[ 'rss_name' ]) . '" /></em></span>
    </div>
   </div>
   <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['rss_address_id'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="rss_id" size="80" maxlength="255" value="' . getinput($ds[ 'rss_id' ]) . '" /></em></span>
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
    <label class="col-sm-2 control-label">'.$plugin_language['rss_num'].':</label>
    <div class="col-sm-2"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="rss_num" size="80" maxlength="255" value="' . getinput($ds[ 'rss_num' ]) . '" /></em></span>
    </div>
  </div> 
  </div>
    <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="rssID" value="' . $ds[ 'rssID' ] . '" />
        <button class="btn btn-warning" type="submit" name="saveedit"  />' . $plugin_language[ 'edit_rss' ] . '</button>
    </div></div></div>
</form>
</div></div>';

} elseif ($action == "settings") {

echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-gear" style="font-size: 1rem;"></i> '.$plugin_language['rss_setting'].'
        </div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_rss">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>
        <div class="card-body">';


    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_rss_settings");
    $ds = mysqli_fetch_array($settings);
if ($ds['rssupdown']) {
        $type = '<div class="col-md-4 form-check form-switch" style="padding: 0px 43px;">
            <input class="form-check-input" type="radio" name="rssupdown" value="1" checked="checked" />&nbsp;&nbsp;' . $plugin_language['rss_up'] . '</div>
        <div class="col-md-8">
            <div class="col-md-6">'.$plugin_language['rssspeed'].':</div>
                 <div class="col-md-6"><span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'rssspeed' ].'"><input class="form-control" type="text" name="rss_speed" value="'.$ds['rss_speed'].'" size="35"></em></span>
            </div>
        </div>

        <div class="col-md-4 form-check form-switch" style="padding: 0px 43px;">
            <input class="form-check-input" type="radio" name="rssupdown" value="0" />&nbsp;&nbsp;' . $plugin_language['rss_down'].'
        </div>
        <div class="col-md-8">
            <div class="col-md-6">'.$plugin_language['rssheight'].':</div>
                 <div class="col-md-6"><span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'rssheight' ].'"><input class="form-control" type="text" name="rss_height" value="'.$ds['rss_height'].'" size="35"></em></span>
            </div>
        </div>
		
		<div class="col-md-4 form-check form-switch" style="padding: 0px 43px;">
            
        </div>
        <div class="col-md-8">
            <div class="col-md-6">'.$plugin_language['rssletters'].':</div>
                 <div class="col-md-6"><span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'rssletters' ].'"><input class="form-control" type="text" name="rss_letters" value="'.$ds['rss_letters'].'" size="35"></em></span>
            </div>
        </div>
		';
    } else {
        $type = '<div class="col-md-4 form-check form-switch" style="padding: 0px 43px;">
            <input class="form-check-input" type="radio" name="rssupdown" value="1" />&nbsp;&nbsp;' . $plugin_language['rss_up'] . '</div>
        <div class="col-md-8">
            <div class="col-md-6">'.$plugin_language['rssspeed'].':</div>
                 <div class="col-md-6"><span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'rssspeed' ].'"><input class="form-control" type="text" name="rss_speed" value="'.$ds['rss_speed'].'" size="35"></em></span>
            </div>
        </div>
		
        <div class="col-md-4 form-check form-switch" style="padding: 0px 43px;">
                 <input class="form-check-input" type="radio" name="rssupdown" value="0" checked="checked" />&nbsp;&nbsp;' . $plugin_language['rss_down'].'
        </div>
        <div class="col-md-8">
            <div class="col-md-6">'.$plugin_language['rssheight'].':</div>
                 <div class="col-md-6"><span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'rssheight' ].'"><input class="form-control" type="text" name="rss_height" value="'.$ds['rss_height'].'" size="35"></em></span>
            </div>
        </div>
		<div class="col-md-4 form-check form-switch" style="padding: 0px 43px;">
                 
        </div>
        <div class="col-md-8">
            <div class="col-md-6">'.$plugin_language['rssletters'].':</div>
                 <div class="col-md-6"><span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'rssletters' ].'"><input class="form-control" type="text" name="rss_letters" value="'.$ds['rss_letters'].'" size="35"></em></span>
            </div>
        </div>
		';
    }
            
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    
echo'<form method="post" action="admincenter.php?site=admin_rss" enctype="multipart/form-data" onsubmit="return chkFormular();">
    <div class="row">
        <div class="col-md-3">
            '.$plugin_language['rss_direction'].':
        </div>
        <div class="col-md-6 row">
            '.$type.'
        </div>
    </div>       
    <br>
    <div class="mb-3">
        <input type="hidden" name="captcha_hash" value="'.$hash.'"> 
        <button class="btn btn-primary" type="submit" name="rssupdown_settings_save">'.$plugin_language['edit_rss'].'</button>
    </div>

</form>
</div>
</div>';

} elseif (isset($_POST[ 'sortieren' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $sort = $_POST[ 'sort' ];
        if (is_array($sort)) {
            foreach ($sort as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE " . PREFIX . "plugins_rss SET sort='$sorter[1]' WHERE rssID='$sorter[0]' ");
                redirect("admincenter.php?site=admin_rss", "", 0);
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "save" ])) {
    $rss_name = $_POST[ "name" ];
    $rss_id = $_POST[ "rss_id" ];
    $rss_num = $_POST[ "rss_num" ];

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
            "INSERT INTO " . PREFIX . "plugins_rss (rssID, rss_name, rss_id, displayed, displayedw,  rss_num, sort) values('', '" . $rss_name . "', '" . $rss_id . "', '" . $displayed . "', '" . $displayedw . "','" . $rss_num . "','1')"
        );

        $id = mysqli_insert_id($_database);
        $errors = array();
        $_language->readModule('formvalidation', true);

        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($_language->module['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_rss", "", 0);
        }
    } else {
        echo $_language->module[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "saveedit" ])) {
    $rss_name = $_POST[ "name" ];
    $rss_id = $_POST[ "rss_id" ];
    $rss_num = $_POST[ "rss_num" ];
    
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
            "UPDATE " . PREFIX . "plugins_rss SET rss_name='" . $rss_name . "', rss_id='" . $rss_id . "', displayed='" . $displayed . "', displayedw='" . $displayedw . "', rss_num='" . $rss_num . "' WHERE rssID='" .
            $_POST[ "rssID" ] . "'"
        );

        $id = $_POST[ 'rssID' ];

        $errors = array();

        $_language->readModule('formvalidation', true);

        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($_language->module['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_rss", "", 0);
        }
    } else {
		$_language->readModule('formvalidation', true);       
	   echo $_language->module[ 'transaction_invalid' ];
    }
} elseif (isset($_GET[ "delete" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_rss WHERE rssID='" . $_GET[ "rssID" ] . "'");
        $data = mysqli_fetch_assoc($get);

        if (safe_query("DELETE FROM " . PREFIX . "plugins_rss WHERE rssID='" . $_GET[ "rssID" ] . "'")) {
           
            redirect("admincenter.php?site=admin_rss", "", 0);
        } else {
            redirect("admincenter.php?site=admin_rss", "", 0);
        }
    } else {
        print_r($plugin_language); return false;
        $_language->readModule('formvalidation', true);  
        echo $_language->module[ 'transaction_invalid' ];
    }
}

if (isset($_POST[ 'rssupdown_settings_save' ])) {  

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE
                " . PREFIX . "plugins_rss_settings
            SET
                rssupdown='" . $_POST[ 'rssupdown' ] . "', 
                rss_speed='" . $_POST[ 'rss_speed' ] . "',
                rss_letters='" . $_POST[ 'rss_letters' ] . "',
                rss_height='" . $_POST[ 'rss_height' ] . "'"
        );
        
        redirect("admincenter.php?site=admin_rss", "", 0);
    } else {
        redirect("admincenter.php?site=admin_rss", $plugin_language[ 'transaction_invalid' ], 3);
    }
}

if ($action == "") {


echo'<div class="card">
            <div class="card-header">
            <i class="bi bi-rss" style="font-size: 1rem;"></i> ' . $plugin_language[ 'title' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_rss">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
            <div class="card-body">
            <div class="form-group row">
                <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
                <div class="col-md-8">
                  <a href="admincenter.php?site=admin_rss&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_rss' ] . '</a>      
                  <a href="admincenter.php?site=admin_rss&amp;action=settings" class="btn btn-primary" type="button"><i class="bi bi-gear"></i> ' . $plugin_language[ 'rss_setting' ] . '</a>
                </div>
              </div>
            <div class="table-responsive">
                <form method="post" action="admincenter.php?site=admin_rss">
            
            <table class="table">
            <thead>
            <tr>
            <th width="19%" class="title"><b>' . $plugin_language[ 'rss_name' ] . '</b></th>
            <th width="19%" class="title"><b>' . $plugin_language[ 'rss_address_id' ] . '</b></th>
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

    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_rss ORDER BY sort");
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
            
                $rss_name = $ds[ 'rss_name' ];
                $rss_id = $ds[ 'rss_id' ];
            
            echo '<tr>
            <td width="19%" class="' . $td . '">' . $rss_name . '</td>

            <td width="19%" class="' . $td . '">' . $rss_id . '</td>

            <td width="15%" class="' . $td . '">' . $displayed . '</td>

            <td width="15%" class="' . $td . '">' . $displayedw . '</td>
                        
            <td width="10%" class="' . $td . '" >

            <a class="btn btn-warning" href="admincenter.php?site=admin_rss&amp;action=edit&amp;rssID=' . $ds[ 'rssID' ] .
                '" class="input">' . $plugin_language[ 'edit' ] . '</a>
                
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_rss&amp;delete=true&amp;rssID='.$ds['rssID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
 
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
            </td>
            <td width="8%" class="' . $td . '" align="center"><select name="sort[]">';
            for ($j = 1; $j <= $anz; $j++) {
                if ($ds[ 'sort' ] == $j) {
                    echo '<option value="' . $ds[ 'rssID' ] . '-' . $j . '" selected="selected">' . $j .
                        '</option>';
                } else {
                    echo '<option value="' . $ds[ 'rssID' ] . '-' . $j . '">' . $j . '</option>';
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
