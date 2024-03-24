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
$plugin_language = $pm->plugin_language("admin_todo", $plugin_path);

$title = $plugin_language[ 'todo' ]; #sc_datei Info

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='todo'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($_lang[ 'access_denied' ]);
}
}

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if ($action == "add") {

  @$progress .= "<option value='0'>Nobody</option>";
  $squadmemberqry = safe_query("SELECT `userID` FROM `".PREFIX."squads_members`");
  while($sq = mysqli_fetch_array($squadmemberqry)) {
   $progress .= "<option value='" . $sq[ 'userID' ] . "'>".getnickname($sq[ 'userID' ])."</option>";
  }


    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

     echo '<script>
    <!--
    function chkFormular() {
        if(!validbbcode(document.getElementById(\'message\').value, \'admin\')) {
           return false;
       }
   }
-->
</script>';

    echo '<div class="card">
              <div class="card-header">
                            <i class="bi bi-list-ol"></i> '.$plugin_language['todo'].'
                        </div>
             <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_todo">'.$plugin_language['todo'].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['add_todo'].'</li>
                </ol>
            </nav>
            <div class="card-body">

    <form method="post" id="post" name="post" action="admincenter.php?site=admin_todo" enctype="multipart/form-data"
    onsubmit="return chkFormular();">
<table width="100%" border="0" cellspacing="1" cellpadding="3">
    
<tr>
  <td><b>' . $plugin_language[ 'todo_name' ] . ':</b></td>
  <td><input class="form-control" type="text" name="title" size="60" maxlength="255" /></td>
</tr>

<tr colspan="2"><td>&nbsp;</td></tr>
<tr>
  <td><b>' . $plugin_language[ 'progress_by' ] . ':</b></td>
  <td>
  <select name="progress_by" class="form-control">

  ' . $progress . ' 

  </select></td>
</tr>

<tr>
  <td colspan="2">
  <hr>
    <b>' . $plugin_language[ 'description' ] . ':</b>
    
  <br /><textarea class="ckeditor" id="ckeditor" rows="20" cols="" name="message" style="width: 100%;"></textarea><br>
</td>
</tr>

<tr>
  <td><b>' . $plugin_language[ 'percent' ] . ':</b></td>
  <td><input class="form-control" type="text" name="percent" size="3" /></td>
</tr>
<tr>
  <td><b>' . $plugin_language[ 'open' ] . ':</b></td>
  <td><input type="checkbox" name="open" value="1" checked="checked" /></td>
</tr>
<tr>
  <td><b>' . $plugin_language[ 'is_displayed' ] . ':</b></td>
  <td><input type="checkbox" name="displayed" value="1" checked="checked" /></td>
</tr>

<tr>
  <td><input type="hidden" name="captcha_hash" value="' . $hash . '" /></td>
  </tr>
<tr>
  <td><input class="btn btn-success" type="submit" name="save" value="' . $plugin_language[ 'add_todo' ] . '" /></td>
</tr>
</table>
</form></div></div>';
} elseif ($action == "edit") {


    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_todo WHERE todoID='" . $_GET[ "todoID" ] ."'"
        )
    );
    

    if ($ds[ 'open' ] == 1) {
        $open = '<input type="checkbox" name="open" value="1" checked="checked" />';
    } else {
        $open = '<input type="checkbox" name="open" value="1" />';
    }


    if ($ds[ 'displayed' ] == 1) {
        $displayed = '<input type="checkbox" name="displayed" value="1" checked="checked" />';
    } else {
        $displayed = '<input type="checkbox" name="displayed" value="1" />';
    }

  @$progress .= "<option value='0'>Nobody</option>";
  $squadmemberqry = safe_query("SELECT `userID` FROM `".PREFIX."squads_members`");
  while($sq = mysqli_fetch_array($squadmemberqry)) {
    
    if($ds[ 'progress_by' ] == $sq[ 'userID' ]) { 
      $progress .= "<option value='" . $sq[ 'userID' ] . "' selected='selected'>".getnickname($sq[ 'userID' ])."</option>";
    } else {
      $progress .= "<option value='" . $sq[ 'userID' ] . "'>".getnickname($sq[ 'userID' ])."</option>";
    } 
  }

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

     echo '<script>
    <!--
    function chkFormular() {
        if(!validbbcode(document.getElementById(\'message\').value, \'admin\')) {
           return false;
       }
   }
-->
</script>';

    echo '<div class="card">
              <div class="card-header">
                            <i class="bi bi-list-ol"></i> '.$plugin_language['todo'].'
                        </div>
             <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_todo">'.$plugin_language['todo'].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['edit_todo'].'</li>
                </ol>
            </nav>
            <div class="card-body">

    <form method="post" id="post" name="post" action="admincenter.php?site=admin_todo"
    enctype="multipart/form-data" onsubmit="return chkFormular();">
<input type="hidden" name="todoID" value="' . $ds[ 'todoID' ] . '" />
<table width="100%" border="0" cellspacing="1" cellpadding="2">
    
<tr>
  <td><b>' . $plugin_language[ 'todo_name' ] . ':</b></td>
  <td><input class="form-control" type="text" name="title" size="60" maxlength="255" value="' . getinput($ds[ 'title' ]) . '" /></td>
</tr>

<tr colspan="2"><td>&nbsp;</td></tr>
<tr>
  <td><b>' . $plugin_language[ 'progress_by' ] . ':</b></td>
  <td>
  <select name="progress_by" class="form-control">
   
 ' . $progress . ' 

 </select></td>
</tr>

<tr>
  <td colspan="2">
  <hr>
    <b>' . $plugin_language[ 'description' ] . ':</b>
    
  <br /><textarea class="ckeditor" id="ckeditor" rows="20" cols="" name="message" style="width: 100%;">' . getinput($ds[ 'text' ]) .
        '</textarea><br>
</td>
</tr>
<tr>
  <td><b>' . $plugin_language[ 'percent' ] . ':</b></td>
 <td><input class="form-control" type="text" name="percent" size="3" maxlength="255" value="' . getinput($ds[ 'percent' ]) . '" /></td>
</tr>
<tr>
  <td><b>' . $plugin_language[ 'open' ] . ':</b></td>
  <td>' . $open . '</td>
</tr>
<tr>
  <td><b>' . $plugin_language[ 'is_displayed' ] . ':</b></td>
  <td>' . $displayed . '</td>
</tr>

<tr>
  <td><input type="hidden" name="captcha_hash" value="' . $hash . '" /></td>
  </tr>

<tr>
  <td><input class="btn btn-warning" type="submit" name="saveedit" value="' . $plugin_language[ 'edit_todo' ] . '" /></td>
</tr>
</table>
</form>
</div></div>';
} elseif (isset($_POST[ 'sortieren' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $sort = $_POST[ 'sort' ];
        if (is_array($sort)) {
            foreach ($sort as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE " . PREFIX . "plugins_todo SET sort='$sorter[1]' WHERE todoID='$sorter[0]' ");
                redirect("admincenter.php?site=admin_todo", "", 0);
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "save" ])) {
    $title = $_POST[ "title" ];
    

    
    $text = $_POST[ "message" ];
    $percent = $_POST[ "percent" ];
    $progress_by = $_POST[ 'progress_by' ];

    if (isset($_POST[ "open" ])) {
        $open = 1;
    } else {
        $open = 0;
    }
    if (!$open) {
        $open = 0;
    }


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
            "INSERT INTO " . PREFIX .
            "plugins_todo (todoID, date, title, text, percent, userID, progress_by, open, displayed, sort) values('', '".time()."', '" . $title . "', '" . $text . "', '" . $percent . "', '".$userID."', '" . $progress_by . "', '" . $open . "', '" . $displayed . "', '1')"
        );

        $id = mysqli_insert_id($_database);

        $errors = array();

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation', true);

       
     

        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($_language->module['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_todo", "", 0);
        }
    } else {
        echo $_language->module[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "saveedit" ])) {
    $title = $_POST[ "title" ];
    
    $text = $_POST[ "message" ];
    $percent = $_POST[ "percent" ];
    $progress_by = $_POST[ 'progress_by' ];

    if (isset($_POST[ "open" ])) {
        $open = 1;
    } else {
        $open = 0;
    }

    if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        

        safe_query(
            "UPDATE " . PREFIX . "plugins_todo SET title='" . $title . "', text='" . $text . "', percent='" . $percent . "', open='" . $open . "', displayed='" . $displayed . "', progress_by='" . $progress_by ."' WHERE todoID='" .
            $_POST[ "todoID" ] . "'"
        );

        $id = $_POST[ 'todoID' ];

        $errors = array();

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation', true);

        

        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($_language->module['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_todo", "", 0);
        }
    } else {
    $_language->readModule('formvalidation', true);       
     echo $_language->module[ 'transaction_invalid' ];
    }
} elseif (isset($_GET[ "delete" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_todo WHERE todoID='" . $_GET[ "todoID" ] . "'");
        $data = mysqli_fetch_assoc($get);

        if (safe_query("DELETE FROM " . PREFIX . "plugins_todo WHERE todoID='" . $_GET[ "todoID" ] . "'")) {
           
            redirect("admincenter.php?site=admin_todo", "", 0);
        } else {
            redirect("admincenter.php?site=admin_todo", "", 0);
        }
    } else {
    print_r($plugin_language); return false;
        $_language->readModule('formvalidation', true);  
        echo $_language->module[ 'transaction_invalid' ];
    }
} else {
echo'<div class="card">
  <div class="card-header">
                            <i class="bi bi-list-ol"></i> ' . $plugin_language[ 'todo' ] . '
                        </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_todo">' . $plugin_language[ 'todo' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>
                        <div class="card-body">
            <div class="form-group row">
              <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
              <div class="col-md-8">
              <a href="admincenter.php?site=admin_todo&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new' ] . '</a>
              </div>
            </div>';


#------------------------------------------------------------------------------------------------------------
#Geordnet nach offen und noch nicht fertig
echo '<div class="card">
         <div class="alert alert-warning">
        <h3 class="panel-title">'.$plugin_language[ 'first_order' ].'</h3>
        </div>
              <div class="card-body">


    <div class="table-responsive">
    
    <form method="post" action="admincenter.php?site=admin_todo">

<table class="table">
        <thead>
        <tr>

            <th width="30%" class="title"><b>' . $plugin_language[ 'title' ] . '</b></th>
            <th width="15%" class="title"><b>' . $plugin_language[ 'percent' ] . '</b></th>
            <th width="15%" class="title"><b>' . $plugin_language[ 'is_displayed' ] . '</b></th>
            <th width="25%" class="title"><b>' . $plugin_language[ 'actions' ] . '</b></th>
            <th width="15%" class="title" align="center"><b>' . $plugin_language[ 'sort' ] . '</b></th>
            
        </tr>
        </thead>
        <tbody>';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_todo WHERE `percent` < '100' AND `open` = '1' ORDER BY sort");
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
            
            $title = $ds[ 'title' ];
            
            $translate = new multiLanguage(detectCurrentLanguage());
            $title = $ds['title'];
            $title = $ds['title'];
    
          $translate->detectLanguages($title);
          $title = $translate->getTextByLanguage($title);
                
          $percent = getinput($ds[ 'percent' ]);
            

            echo '<tr>
            <td width="40%" class="' . $td . '">' . $title . '</td>

            <td width="15%" class="' . $td . '">' . $percent . ' %</td>
            
            <td width="15%" class="' . $td . '">' . $displayed . '</td>
            
            <td width="15%" class="' . $td . '" >
                    <a class="btn btn-warning" href="admincenter.php?site=admin_todo&amp;action=edit&amp;todoID=' . $ds[ 'todoID' ] .
                '" title="'.$plugin_language[ 'edit' ].'" class="input">' . $plugin_language[ 'edit' ] . '</a>


<!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_todo&amp;delete=true&amp;todoID=' . $ds[ 'todoID' ] .
                    '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'first_order' ] . '</h5>
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
        <td width="15%" class="' . $td . '" align="right"><select name="sort[]">';
            for ($j = 1; $j <= $anz; $j++) {
                if ($ds[ 'sort' ] == $j) {
                    echo '<option value="' . $ds[ 'todoID' ] . '-' . $j . '" selected="selected">' . $j .
                        '</option>';
                } else {
                    echo '<option value="' . $ds[ 'todoID' ] . '-' . $j . '">' . $j . '</option>';
                }
            }
            echo '</select>
</td>
</tr>';
            $i++;
        }
    } else {
        echo '<tr><td class="td1" colspan="5">' . $plugin_language[ 'no_entries' ] . '</td></tr>';
    }

    echo '<tr>
<td class="td_head" colspan="5" align="right"><input type="hidden" name="captcha_hash" value="' . $hash .
    '"><br><input class="btn btn-success" type="submit" name="sortieren" value="' . $plugin_language[ 'to_sort' ] . '" /></td>
</tr>
</tbody></table>
</form></div></div></div>';



#------------------------------------------------------------------------------------------------------------
#Geordnet nach offen und fertig
echo '<div class="card">
         <div class="alert alert-success">
        <h3 class="panel-title">'.$plugin_language[ 'second_order' ].'</h3>
        </div>
              <div class="card-body">

    <div class="table-responsive">
    
    <form method="post" action="admincenter.php?site=admin_todo">

<table class="table">
        <thead>
        <tr>

            <th width="30%" class="title"><b>' . $plugin_language[ 'title' ] . '</b></th>
            <th width="15%" class="title"><b>' . $plugin_language[ 'percent' ] . '</b></th>
            <th width="15%" class="title"><b>' . $plugin_language[ 'is_displayed' ] . '</b></th>
            <th width="25%" class="title"><b>' . $plugin_language[ 'actions' ] . '</b></th>
            <th width="15%" class="title" align="center"><b>' . $plugin_language[ 'sort' ] . '</b></th>
            
        </tr>
        </thead>
        <tbody>';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_todo WHERE `percent` = '100' AND `open` = '1' ORDER BY sort");
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
            
            $title = $ds[ 'title' ];
            
            $translate = new multiLanguage(detectCurrentLanguage());
            $title = $ds['title'];
            $title = $ds['title'];
    
          $translate->detectLanguages($title);
          $title = $translate->getTextByLanguage($title);
                
          $percent = getinput($ds[ 'percent' ]);
            

            echo '<tr>
            <td width="40%" class="' . $td . '">' . $title . '</td>

            <td width="15%" class="' . $td . '">' . $percent . ' %</td>
            
            <td width="15%" class="' . $td . '">' . $displayed . '</td>
            
            <td width="15%" class="' . $td . '" >
                    <a class="btn btn-warning" href="admincenter.php?site=admin_todo&amp;action=edit&amp;todoID=' . $ds[ 'todoID' ] .
                '" title="'.$plugin_language[ 'edit' ].'" class="input">' . $plugin_language[ 'edit' ] . '</a>


<!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_todo&amp;delete=true&amp;todoID=' . $ds[ 'todoID' ] .
                    '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'second_order' ] . '</h5>
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
        <td width="15%" class="' . $td . '" align="right"><select name="sort[]">';
            for ($j = 1; $j <= $anz; $j++) {
                if ($ds[ 'sort' ] == $j) {
                    echo '<option value="' . $ds[ 'todoID' ] . '-' . $j . '" selected="selected">' . $j .
                        '</option>';
                } else {
                    echo '<option value="' . $ds[ 'todoID' ] . '-' . $j . '">' . $j . '</option>';
                }
            }
            echo '</select>
</td>
</tr>';
            $i++;
        }
    } else {
        echo '<tr><td class="td1" colspan="5">' . $plugin_language[ 'no_entries' ] . '</td></tr>';
    }

    echo '<tr>
<td class="td_head" colspan="5" align="right"><input type="hidden" name="captcha_hash" value="' . $hash .
    '"><br><input class="btn btn-success" type="submit" name="sortieren" value="' . $plugin_language[ 'to_sort' ] . '" /></td>
</tr>
</tbody></table>
</form></div></div></div>';



#------------------------------------------------------------------------------------------------------------
#Geordnet nach offen und noch nicht fertig
echo '<div class="card">
         <div class="alert alert-danger">
        <h3 class="panel-title">'.$plugin_language[ 'third_order' ].'</h3>
        </div>
              <div class="card-body">
  
    <div class="table-responsive">
    
    <form method="post" action="admincenter.php?site=admin_todo">

<table class="table">
        <thead>
        <tr>

            <th width="30%" class="title"><b>' . $plugin_language[ 'title' ] . '</b></th>
            <th width="10%" class="title"><b>' . $plugin_language[ 'percent' ] . '</b></th>
            <th width="15%" class="title"><b>' . $plugin_language[ 'is_displayed' ] . '</b></th>
            <th width="30%" class="title"><b>' . $plugin_language[ 'actions' ] . '</b></th>
            <th width="10%" class="title" align="center"><b>' . $plugin_language[ 'sort' ] . '</b></th>
            
        </tr>
        </thead>
        <tbody>';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_todo WHERE `open` = '0' ORDER BY sort");
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
            
            $title = $ds[ 'title' ];
            
            $translate = new multiLanguage(detectCurrentLanguage());
            $title = $ds['title'];
            $title = $ds['title'];
    
          $translate->detectLanguages($title);
          $title = $translate->getTextByLanguage($title);
                
          $percent = getinput($ds[ 'percent' ]);

           
      echo '<tr>
            <td width="30%" class="' . $td . '">' . $title . '</td>

            <td width="15%" class="' . $td . '">' . $percent . ' %</td>
            
            <td width="15%" class="' . $td . '">' . $displayed . '</td>
            
            <td width="30%" class="' . $td . '" >

            <a class="btn btn-warning" href="admincenter.php?site=admin_todo&amp;action=edit&amp;todoID=' . $ds[ 'todoID' ] .
                '" title="'.$plugin_language[ 'edit' ].'" class="input">' . $plugin_language[ 'edit' ] . '</a>

                
<!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_todo&amp;delete=true&amp;todoID=' . $ds[ 'todoID' ] .
                    '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'third_order' ] . '</h5>
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
        <td width="10%" class="' . $td . '" align="right"><select name="sort[]">';
            for ($j = 1; $j <= $anz; $j++) {
                if ($ds[ 'sort' ] == $j) {
                    echo '<option value="' . $ds[ 'todoID' ] . '-' . $j . '" selected="selected">' . $j .
                        '</option>';
                } else {
                    echo '<option value="' . $ds[ 'todoID' ] . '-' . $j . '">' . $j . '</option>';
                }
            }
            echo '</select>
</td>
</tr>';
            $i++;
        }
    } else {
        echo '<tr><td class="td1" colspan="5">' . $plugin_language[ 'no_entries' ] . '</td></tr>';
    }

    echo '<tr>
<td class="td_head" colspan="5" align="right"><input type="hidden" name="captcha_hash" value="' . $hash .
    '"><br><input class="btn btn-success" type="submit" name="sortieren" value="' . $plugin_language[ 'to_sort' ] . '" /></td>
</tr>
</tbody></table>
</form></div></div></div>';
}


?>