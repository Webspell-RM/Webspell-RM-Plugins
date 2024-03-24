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
$plugin_language = $pm->plugin_language("server_rules", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='server_rules'");
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
                            <i class="bi bi-paragraph"></i> ' . $plugin_language[ 'server_rules' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_server_rules">' . $plugin_language[ 'server_rules' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_server_rules' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';


    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

echo '<form method="post" id="post" name="post" action="admincenter.php?site=admin_server_rules" enctype="multipart/form-data"
    onsubmit="return chkFormular();">

<div class="row">
     <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'server_rules_name' ] . '</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="title" size="60" maxlength="255" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <textarea class="ckeditor" id="ckeditor" rows="10" cols="" name="message" style="width: 100%;"></textarea></em></span>
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
        <button class="btn btn-success" type="submit" name="save"  />' . $plugin_language[ 'add_server_rules' ] . '</button>
    </div>
  </div>
  </div>
</form></div></div>';
} elseif ($action == "edit") {


    echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-paragraph"></i> ' . $plugin_language[ 'server_rules' ] . '</div>
                        
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_server_rules">' . $plugin_language[ 'server_rules' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_server_rules' ] . '</li>
                </ol>
            </nav> 
<div class="card-body">';


    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_server_rules WHERE server_rulesID='" . $_GET[ "server_rulesID" ] ."'"
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

echo '<form method="post" id="post" name="post" action="admincenter.php?site=admin_server_rules"
    enctype="multipart/form-data" onsubmit="return chkFormular();">

<div class="row">
     <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'server_rules_name' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="title" size="80" maxlength="255" value="' . getinput($ds[ 'title' ]) . '" /></em></span>
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <textarea class="ckeditor" id="ckeditor" rows="10" cols="" name="message" style="width: 100%;">' . getinput($ds[ 'text' ]) . '</textarea></em></span>
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
        <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="server_rulesID" value="' . $ds[ 'server_rulesID' ] . '" />
        <button class="btn btn-warning" type="submit" name="saveedit"  />' . $plugin_language[ 'edit_server_rules' ] . '</button>
    </div>
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
                safe_query("UPDATE " . PREFIX . "plugins_server_rules SET sort='$sorter[1]' WHERE server_rulesID='$sorter[0]' ");
                redirect("admincenter.php?site=admin_server_rules", "", 0);
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "save" ])) {
    $title = $_POST[ "title" ];
    
    $text = $_POST[ "message" ];
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
            "INSERT INTO " . PREFIX . "plugins_server_rules (server_rulesID, title, text, date, poster, displayed, sort) values('', '" . $title . "', '" . $text . "', '" . time() . "', '" . $userID . "', '" . $displayed . "', '1')"
        );

        $id = mysqli_insert_id($_database);

        $errors = array();

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation', true);

       
     

        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($_language->module['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_server_rules", "", 0);
        }
    } else {
        echo $_language->module[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "saveedit" ])) {
    $title = $_POST[ "title" ];
    
    $text = $_POST[ "message" ];
    if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        

        safe_query(
            "UPDATE " . PREFIX . "plugins_server_rules SET title='" . $title . "', text='" . $text .
            "', date='" . time() . "', poster='" . $userID . "', displayed='" . $displayed . "' WHERE server_rulesID='" .
            $_POST[ "server_rulesID" ] . "'"
        );

        $id = $_POST[ 'server_rulesID' ];

        $errors = array();

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation', true);

        

        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($_language->module['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_server_rules", "", 0);
        }
    } else {
		$_language->readModule('formvalidation', true);       
	   echo $_language->module[ 'transaction_invalid' ];
    }
} elseif (isset($_GET[ "delete" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_server_rules WHERE server_rulesID='" . $_GET[ "server_rulesID" ] . "'");
        $data = mysqli_fetch_assoc($get);

        if (safe_query("DELETE FROM " . PREFIX . "plugins_server_rules WHERE server_rulesID='" . $_GET[ "server_rulesID" ] . "'")) {
           
            redirect("admincenter.php?site=admin_server_rules", "", 0);
        } else {
            redirect("admincenter.php?site=admin_server_rules", "", 0);
        }
    } else {
		print_r($plugin_language); return false;
        $_language->readModule('formvalidation', true);  
        echo $_language->module[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'server_rules_settings_save' ])) {  

   
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE
                " . PREFIX . "plugins_server_rules_settings
            SET
                
                server_rules='" . $_POST[ 'server_rules' ] . "' "
        );
        
        redirect("admincenter.php?site=admin_server_rules&action=admin_server_rules_settings", "", 0);
    } else {
        redirect("admincenter.php?site=admin_server_rules&action=admin_server_rules_settings", $plugin_language[ 'transaction_invalid' ], 3);
    }
}  





if ($action == "admin_server_rules_settings") {

 
    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_server_rules_settings");
    $ds = mysqli_fetch_array($settings);

    
  $maxshownserver_rules = $ds[ 'server_rules' ];
if (empty($maxshownserver_rules)) {
    $maxshownserver_rules = 10;
}


    

    

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    
echo'    <form method="post" action="admincenter.php?site=admin_server_rules&action=admin_server_rules_settings">
        <div class="card">
            <div class="card-header">
                '.$plugin_language[ 'settings' ].'
            </div>

            <div class="card-body">


            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_server_rules">' . $plugin_language[ 'server_rules' ] . '</a></li>
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_server_rules&action=admin_server_rules_settings">' . $plugin_language[ 'settings' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  

                
                <div class="row">
                    <div class="col-md-6">
                        

                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['max_server_rules'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip' ].'"><input class="form-control" type="text" name="server_rules" value="'.$ds['server_rules'].'" size="35"></em></span>
                            </div>
                        </div>

                        

                        
                    </div>

                    <div class="col-md-6">
                        
                    </div>
               </div>
                <br>
 <div class="form-group">
<input type="hidden" name="captcha_hash" value="'.$hash.'"> 
<button class="btn btn-primary" type="submit" name="server_rules_settings_save">'.$plugin_language['update'].'</button>
</div>

        

 </div>
            </div>
       
        
    </form>';

} elseif ($action == "") {


echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-paragraph"></i> ' . $plugin_language[ 'title' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_server_rules">' . $plugin_language[ 'server_rules' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="form-group row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_server_rules&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_server_rules' ] . '</a>      
      <a href="admincenter.php?site=admin_server_rules&action=admin_server_rules_settings" class="btn btn-primary" type="button">' . $plugin_language[ 'settings' ] . '</a>
    </div>
  </div>

<div class="table-responsive">
    
    <form method="post" action="admincenter.php?site=admin_server_rules">

<table class="table">
        <thead>
        <tr>

            <th width="29%" class="title"><b>' . $plugin_language[ 'server_rules' ] . '</b></th>
            <th width="15%" class="title"><b>' . $plugin_language[ 'is_displayed' ] . '</b></th>
            <th width="20%" class="title"><b>' . $plugin_language[ 'actions' ] . '</b></th>
            <th width="8%" class="title"><b>' . $plugin_language[ 'sort' ] . '</b></th>
            
        </tr>
        </thead>
        <tbody>
   
       
        ';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_server_rules ORDER BY sort");
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
            

            
                $title = getinput($ds[ 'title' ]);
           
                $title = getinput($ds[ 'title' ]);
           
            $title = $ds[ 'title' ];
    
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($title);
            $title = $translate->getTextByLanguage($title);
            
            echo '<tr>
            <td width="29%" class="' . $td . '">' . $title . '</td>
            
            <td width="15%" class="' . $td . '">' . $displayed . '</td>
            
            <td width="20%" class="' . $td . '" >

            <a class="btn btn-warning" href="admincenter.php?site=admin_server_rules&amp;action=edit&amp;server_rulesID=' . $ds[ 'server_rulesID' ] .
                '" class="input">' . $plugin_language[ 'edit' ] . '</a>

                <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_server_rules&amp;delete=true&amp;server_rulesID=' . $ds[ 'server_rulesID' ] . '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'server_rules' ] . '</h5>
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
                   
                    </td>
				<td width="8%" class="' . $td . '" align="center"><select name="sort[]">';
            for ($j = 1; $j <= $anz; $j++) {
                if ($ds[ 'sort' ] == $j) {
                    echo '<option value="' . $ds[ 'server_rulesID' ] . '-' . $j . '" selected="selected">' . $j .
                        '</option>';
                } else {
                    echo '<option value="' . $ds[ 'server_rulesID' ] . '-' . $j . '">' . $j . '</option>';
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
