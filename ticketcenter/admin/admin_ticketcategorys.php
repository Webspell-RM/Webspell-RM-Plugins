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
    $plugin_language = $pm->plugin_language("ticketcategorys", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='twitter'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}

function generate_overview($ticketcats = '', $offset = '', $subcatID = 0) {

  global $plugin_language;
  $rubrics = safe_query("SELECT * FROM " . PREFIX . "plugins_tickets_categories WHERE subcatID = '" . $subcatID . "' ORDER BY name");
    
    $i=1;
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    
      
    while ($ds = mysqli_fetch_array($rubrics)) {
      if ($i%2) { $td = 'td1'; }
    else { $td = 'td2'; }
        
    $ticketcats .= '<tr>
        <td class="' . $td . '">' . $offset . getinput($ds[ 'name' ]) . '</td>
        <td class="' . $td . '">

             <a class="btn btn-warning btn-sm" href="admincenter.php?site=admin_ticketcategorys&amp;action=edit&amp;ticketcatID=' . $ds[ 'ticketcatID' ] . '" class="input">'.$plugin_language[ 'edit' ].'</a>

                <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_ticketcategorys&amp;delete=true&amp;ticketcatID=' . $ds[ 'ticketcatID' ] . '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'ticket_categories' ] . '</h5>
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
      </tr>';
        
        $i++;
  
    if(mysqli_num_rows(safe_query("SELECT * FROM " . PREFIX . "plugins_tickets_categories WHERE subcatID = '" . $ds[ 'ticketcatID' ] . "'"))) {
      $ticketcats .= generate_overview("", $offset.getinput($ds[ 'name' ])." &raquo; ", $ds[ 'ticketcatID' ]);
      }
  }
  
  return $ticketcats;
}

function delete_category($ticketcat){
  $rubrics = safe_query("SELECT ticketcatID FROM " . PREFIX . "plugins_tickets_categories WHERE subcatID = '" . $ticketcat . "' ORDER BY name");
  if(mysqli_num_rows($rubrics)){
    while($ds = mysqli_fetch_assoc($rubrics)){
      delete_category($ds[ 'ticketcatID' ]);
    }
  }
  safe_query("DELETE FROM " . PREFIX . "plugins_tickets_categories WHERE ticketcatID='" . $ticketcat . "'");
}

/* start processing */
  
if(isset($_POST['save'])) {
  if(mb_strlen($_POST['name'])>0){
    $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
      safe_query("INSERT INTO " . PREFIX . "plugins_tickets_categories ( name, subcatID ) values( '" . $_POST[ 'name' ] . "', '".$_POST[ 'subcat' ] . "' ) ");
    } else echo $_language->module[ 'transaction_invalid' ];
  }
  else{
    redirect("admincenter.php?site=admin_ticketcategorys&amp;action=add", $plugin_language[ 'enter_name' ], 3);
  }
}

elseif(isset($_POST[ 'saveedit' ])) {
  if(mb_strlen($_POST[ 'name' ])>0){
    $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
      safe_query("UPDATE " . PREFIX . "plugins_tickets_categories SET name='" . $_POST[ 'name' ] . "', subcatID = '" . $_POST[ 'subcat' ] . "' WHERE ticketcatID='" . $_POST[ 'ticketcatID' ] . "'");
    } else echo $_language->module[ 'transaction_invalid' ];
  }
  else{
    redirect("admincenter.php?site=admin_ticketcategorys&amp;action=edit&amp;ticketcatID=" . $_POST[ 'ticketcatID' ], $plugin_language[ 'enter_name' ], 3);
  }
}

elseif(isset($_GET[ 'delete' ])) {
  $ticketcatID = $_GET[ 'ticketcatID' ];
  $CAPCLASS = new \webspell\Captcha;
  if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        delete_category($ticketcatID);
  } else echo $_language->module[ 'transaction_invalid' ];
}

if(!isset($_GET[ 'action' ])) {
  $_GET[ 'action' ] = '';
}

if($_GET[ 'action' ] == "add") {
  
  function generate_options($ticketcats = '', $offset = '', $subcatID = 0) {
    $rubrics = safe_query("SELECT * FROM " . PREFIX . "plugins_tickets_categories WHERE subcatID = '" . $subcatID . "' ORDER BY name");
    while($dr = mysqli_fetch_array($rubrics)) {
      $ticketcats .= '<option value="' . $dr[ 'ticketcatID' ] . '">' . $offset . getinput($dr[ 'name' ]) . '</option>';
      if(mysqli_num_rows(safe_query("SELECT * FROM " . PREFIX . "plugins_tickets_categories WHERE subcatID = '" . $dr[ 'ticketcatID' ] . "'"))) {
        $ticketcats .= generate_options("", $offset."- ", $dr[ 'ticketcatID' ]);
      }
    }
    return $ticketcats;
  }
  $ticketcats = generate_options('<option value="0">' . $plugin_language[ 'main' ] . '</option>', '- ');
  $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
  
echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-ticket-detailed"></i> ' . $plugin_language[ 'ticket_categories' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_ticketcategorys">' . $plugin_language[ 'ticket_categories' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_category' ] . '</li>
                </ol>
            </nav>  
                        <div class="card-body">';

echo'<form method="post" action="admincenter.php?site=admin_ticketcategorys">
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td width="15%"><b>' . $plugin_language[ 'category_name' ] . ':</b></td>
      <td width="85%"><input class="form-control" type="text" name="name" size="60" /><br></td>
    </tr>
    <tr>
      <td><b>' . $plugin_language[ 'sub_category' ] . ':</b></td>
      
      <td><select class="form-control" name="subcat">' . $ticketcats . '</select></td>
    </tr>
    <tr>
      <td><input type="hidden" name="captcha_hash" value="' . $hash . '" /></td>
      </tr>
    <tr>
      <td><br><input class="btn btn-success" type="submit" name="save" value="' . $plugin_language[ 'add_category' ] . '" /></td>
    </tr>
  </table>
  </form></div></div>';
}

elseif($_GET[ 'action' ]== "edit") {

  $ticketcatID = $_GET[ 'ticketcatID' ];
  $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_tickets_categories WHERE ticketcatID=' $ticketcatID '");
  $ds = mysqli_fetch_array($ergebnis);

  function generate_options($ticketcats = '', $offset = '', $subcatID = 0) {
  
    global $ticketcatID;
    $rubrics = safe_query("SELECT * FROM " . PREFIX . "plugins_tickets_categories WHERE subcatID = '" . $subcatID . "' AND (ticketcatID !='" . $ticketcatID . "' AND subcatID !='" . $ticketcatID . "')  ORDER BY name");
    while($dr = mysqli_fetch_array($rubrics)) {
      $ticketcats .= '<option value="' . $dr[ 'ticketcatID' ] . '">' . $offset.getinput($dr[ 'name' ]) . '</option>';
      if(mysqli_num_rows(safe_query("SELECT * FROM " . PREFIX . "plugins_tickets_categories WHERE subcatID = '" . $dr[ 'ticketcatID' ] . "'"))) {
        $ticketcats .= generate_options("", $offset."- ", $dr[ 'ticketcatID' ]);
      }
    }
    return $ticketcats;
  }
  
  $ticketcats = generate_options('<option value="0">' . $plugin_language[ 'main' ] . '</option>', '- ');
  
  $ticketcats = str_replace('value="' . $ds[ 'subcatID' ] . '"', 'value="' . $ds[ 'subcatID' ] . '" selected="selected"', $ticketcats);
  $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-ticket-detailed"></i> ' . $plugin_language[ 'ticket_categories' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_ticketcategorys">' . $plugin_language[ 'ticket_categories' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_category' ] . '</li>
                </ol>
            </nav>  
                        <div class="card-body">';

 echo'<form method="post" action="admincenter.php?site=admin_ticketcategorys" enctype="multipart/form-data">
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td width="15%"><b>' . $plugin_language[ 'category_name' ] . ':</b></td>
      <td width="85%"><input class="form-control" type="text" name="name" size="60" value="' . getinput($ds[ 'name' ]) . '" /><br></td>
    </tr>
    <tr>
      <td><b>' . $plugin_language[ 'sub_category' ] . ':</b></td>
      <td><select class="form-control" name="subcat">' . $ticketcats . '</select></td>
    </tr>
    <tr>
      <td><input type="hidden" name="captcha_hash" value="' . $hash . '" /><input type="hidden" name="ticketcatID" value="' . $ds[ 'ticketcatID' ] . '" /></td>
      </tr>
    <tr>
      <td><br><input class="btn btn-warning" type="submit" name="saveedit" value="' . $plugin_language[ 'edit_category' ] . '" /></td>
    </tr>
  </table>
  </form></div></div>';
}

else {
  echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-ticket-detailed"></i> ' . $plugin_language[ 'ticket_categories' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_ticketcategorys">' . $plugin_language[ 'ticket_categories' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="form-group row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_ticketcategorys&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_category' ] . '</a>
    </div>
  </div>';
  

  echo '<div class="table-responsive">

       <table class="table">
        <thead>
    <tr>
      <th width="75%" class="title"><b>' . $plugin_language[ 'category_name' ] . '</b></th>
      <th width="25%" class="title"><b>' . $plugin_language[ 'actions' ] . '</b></th>
    </tr>
</thead>
        <tbody>';

  $overview = generate_overview();
  echo $overview;

echo '</tbody></table>
</div></div>';
}
?>