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
$plugin_language = $pm->plugin_language("mc_status", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='mc_status'");
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

   $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-controller"></i> ' . $plugin_language[ 'mc_status' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_mc_status">' . $plugin_language[ 'mc_status' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_mc_status' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';

echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_mc_status" enctype="multipart/form-data">

    
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['name'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="name" size="60" placeholder="MC Status" /></em></span>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['port'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="port" size="60" placeholder="Port" /></em></span>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['displayed'].':</label>
    <div class="col-sm-10 form-check form-switch" style="padding: 0px 43px;">
      <input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />
    </div>
  </div>

  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
    <input type="hidden" name="captcha_hash" value="'.$hash.'" />
    <button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['add_mc_status'].'</button>
    </div>
  </div>

</form></div></div>';
} elseif ($action == "edit") {
$CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
  

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-controller"></i> ' . $plugin_language[ 'mc_status' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_mc_status">' . $plugin_language[ 'mc_status' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_mc_status' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';


$mcID = $_GET[ 'mcID' ];
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_mc_status WHERE mcID='$mcID'");
    $ds = mysqli_fetch_array($ergebnis);





    if ($ds[ 'displayed' ] == 1) {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />';
    } else {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" />';
    }

    

    
  echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_mc_status" enctype="multipart/form-data">

     

  
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['name'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="name" value="'.getinput($ds['name']).'" /></em></span>
    </div>
  </div>

<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['port'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="port" size="60" value="'.getinput($ds['port']).'" /></em></span>
    </div>
  </div>
  

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['displayed'].':</label>
    <div class="col-sm-10 form-check form-switch" style="padding: 0px 43px;">
     ' . $displayed . '
    </div>
  </div>

  
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
    <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="mcID" value="'.$mcID.'" />
    <button class="btn btn-warning" type="submit" name="saveedit"  />'.$plugin_language['edit_mc_status'].'</button>
    </div>
  </div>

  
  

  </form></div>
  </div>';
} elseif (isset($_POST[ 'sortieren' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $sort = $_POST[ 'sort' ];
        if (is_array($sort)) {
            foreach ($sort as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE " . PREFIX . "plugins_mc_status SET sort='$sorter[1]' WHERE mcID='$sorter[0]' ");
                redirect("admincenter.php?site=admin_mc_status", "", 0);
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "save" ])) {
    $name = $_POST[ "name" ];
    $port = $_POST[ "port" ];

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
            "INSERT INTO " . PREFIX . "plugins_mc_status (`mcID`, `name`,  `port`, `displayed`, `sort`) values('', '" . $name . "', '" . $port . "', '" . $displayed . "',  '1')"
        );

        $id = mysqli_insert_id($_database);

        $errors = array();

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation', true);

       
     

        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_mc_status", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "saveedit" ])) {
  $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
      $name = $_POST[ "name" ];
      
      $port = $_POST[ "port" ];
   if (isset($_POST[ "displayed" ])) {
            $displayed = 1;
        } else {
            $displayed = 0;
        }

   

    $mcID = (int)$_POST[ 'mcID' ];
    $id = $mcID;  

    safe_query(
            "UPDATE " . PREFIX . "plugins_mc_status SET 
            `name` ='" . $_POST[ 'name' ] . "', 
            `port` ='" . $_POST[ 'port' ] . "', 
            `displayed` ='" . $displayed . "'
            WHERE 
            `mcID` = '" . $mcID . "'" 
        );
    
$id = mysqli_insert_id($_database);

        $errors = array();

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation', true);

       
     

        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_mc_status", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
}  elseif (isset($_GET[ "delete" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_mc_status WHERE mcID='" . $_GET[ "mcID" ] . "'");
        $data = mysqli_fetch_assoc($get);

        if (safe_query("DELETE FROM " . PREFIX . "plugins_mc_status WHERE mcID='" . $_GET[ "mcID" ] . "'")) {
           
            redirect("admincenter.php?site=admin_mc_status", "", 0);
        } else {
            redirect("admincenter.php?site=admin_mc_status", "", 0);
        }
    } else {
    print_r($plugin_language); return false;
        $_language->readModule('formvalidation', true);  
        echo $plugin_language[ 'transaction_invalid' ];
    }
} else {

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-controller"></i> ' . $plugin_language[ 'mc_status' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_clan_rules">' . $plugin_language[ 'mc_status' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="mb-3 row row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_mc_status&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'add_mc_status' ] . '</a>
    </div>
  </div>';


echo'<form method="post" action="admincenter.php?site=admin_mc_status">

<table class="table table-striped">
    <thead>
        <tr>

            <th><b>' . $plugin_language[ 'name' ] . '</b></th>
            <th><b>' . $plugin_language[ 'port' ] . '</b></th>
            <th><b>' . $plugin_language[ 'displayed' ] . '</b></th>
            <th><b>' . $plugin_language[ 'actions' ] . '</b></th>
            <th><b>' . $plugin_language[ 'sort' ] . '</b></th>
            
        </tr>
        </thead>
        <tbody>
   
        ';




$CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_mc_status ORDER BY sort");
    $anz = mysqli_num_rows($qry);
    if ($anz) {
        $i = 1;
        while ($ds = mysqli_fetch_array($qry)) {
            
            $ds[ 'displayed' ] == 1 ?
            $displayed = '<font color="green"><b>' . $plugin_language[ 'yes' ] . '</b></font>' :
            $displayed = '<font color="red"><b>' . $plugin_language[ 'no' ] . '</b></font>';

            

                $name = getinput($ds[ 'name' ]);
           
                $port = getinput($ds[ 'port' ]);
           

            

            echo '<tr>
            <td>' . $name . '</td>
            
            <td>' . $port . '</td>
            <td>' . $displayed . '</td>
            
            <td><a href="admincenter.php?site=admin_mc_status&amp;action=edit&amp;mcID=' . $ds[ 'mcID' ] . '" class="hidden-xs hidden-sm btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_mc_status&amp;delete=true&amp;mcID=' . $ds[ 'mcID' ] .
                    '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'mc_status' ] . '</h5>
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
        <td><select name="sort[]">';
            for ($j = 1; $j <= $anz; $j++) {
                if ($ds[ 'sort' ] == $j) {
                    echo '<option value="' . $ds[ 'mcID' ] . '-' . $j . '" selected="selected">' . $j .
                        '</option>';
                } else {
                    echo '<option value="' . $ds[ 'mcID' ] . '-' . $j . '">' . $j . '</option>';
                }
            }
            echo '</select>
</td>
</tr>';
            $i++;
        }
    } else {
        echo '<tr><td colspan="6">' . $plugin_language[ 'no_entries' ] . '</td></tr>';
    }

   echo '<tr>
<td colspan="6" align="right"><input type="hidden" name="captcha_hash" value="' . $hash .
    '"><br><input class="btn btn-success" type="submit" name="sortieren" value="' . $plugin_language[ 'to_sort' ] . '" /></td>
</tr>
</tbody></table>
</form></div></div>';

}