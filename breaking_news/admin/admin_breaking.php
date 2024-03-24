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
$plugin_language = $pm->plugin_language("breaking_news", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='breaking_news'");
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

if ($action == "add") {

  $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_breaking_news"
        )
    );

  echo '<div class="card">
    <div class="card-header">
                            <i class="bi bi-newspaper" style="font-size: 1rem;"></i> ' . $plugin_language[ 'carousel' ] . '
                        </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_breaking">'.$plugin_language['carousel'].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_carousel' ] . '</li>
                </ol>
            </nav>
                        <div class="card-body">';
 
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_breaking" enctype="multipart/form-data">
   
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="title" size="60" maxlength="255" /></em></span>
    </div>
  </div>
   <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_link'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="url" size="60" maxlength="255" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <textarea class="form-control" id="description" rows="5" cols="" name="description" style="width: 100%;"></textarea></em></span>
    </div>
  </div>

   <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['is_displayed'].':</label>
    <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
      <input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['add_carousel'].'</button>
    </div>
  </div>
</form>
</div></div>';
} elseif ($action == "edit") {
    echo '<div class="card">
    <div class="card-header">
                            <i class="bi bi-newspaper" style="font-size: 1rem;"></i> ' . $plugin_language[ 'carousel' ] . '
                        </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_breaking">'.$plugin_language['carousel'].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_carousel' ] . '</li>
                </ol>
            </nav>
                        <div class="card-body">';
 
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_breaking_news WHERE breakingnewsID='" . intval($_GET['breakingnewsID']) ."'"
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
 
    echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_breaking" enctype="multipart/form-data">
<input type="hidden" name="breakingnewsID" value="' . $ds['breakingnewsID'] . '" />


<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="title" size="60" maxlength="255" value="' . getinput($ds[ 'title' ]) . '" /></em></span>
    </div>
    
  </div>
 
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['carousel_link'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="url" size="60" value="' . getinput($ds[ 'url' ]) . '" /></em></span>
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <textarea class="form-control" id="description" rows="5" cols="" name="description" style="width: 100%;">' . getinput($ds[ 'description' ]) .
        '</textarea></em></span>
    </div>
  </div>

<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['is_displayed'].':</label>
    <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
      '.$displayed.'
    </div>
  </div>
<div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-warning" type="submit" name="saveedit"  />'.$plugin_language['edit_carousel'].'</button>
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
                safe_query("UPDATE " . PREFIX . "plugins_breaking_news SET sort='$sorter[1]' WHERE breakingnewsID='$sorter[0]' ");
                redirect("admincenter.php?site=admin_breaking", "", 0);
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "save" ])) {
    $title = $_POST[ 'title' ];
    $url = $_POST[ 'url' ];
    $description = $_POST[ 'description' ];
    if (isset($_POST[ 'displayed' ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    if (!$displayed) {
        $displayed = 0;
    }
 
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    safe_query("INSERT INTO `".PREFIX."plugins_breaking_news` (title, url, description, displayed, sort) values ('".$title."', '".$url."', '".$description."', '".intval($displayed)."','1')");
         
      $id = mysqli_insert_id($_database);
 
        $errors = array();

        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_breaking", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }       
        
} elseif (isset($_POST[ "saveedit" ])) {
    $title = $_POST[ "title" ];
    $url = $_POST[ "url" ];
    $description = $_POST[ "description" ];
    if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        
        if (!stristr($url, 'http://') && !stristr($url, 'https://')) {
            if($url != ''){
                $url = 'http://' . $url;
            }
        }

        #if (stristr($url, 'http://')) {
        #    $url = $url;
        #} else {
        #    $url = 'http://' . $url;
        #}
 
        safe_query(
            "UPDATE " . PREFIX . "plugins_breaking_news SET title='" . $title . "', url='" . $url . "', description='" . $description .
            "', displayed='" . $displayed . "' WHERE breakingnewsID='" .
            $_POST[ "breakingnewsID" ] . "'"
        );
        
        $id = $_POST[ 'breakingnewsID' ];
 
        $errors = array();
        
      if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_breaking", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_GET[ "delete" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_breaking_news WHERE breakingnewsID='" . $_GET[ "breakingnewsID" ] . "'");
        $data = mysqli_fetch_assoc($get);
 
        if (safe_query("DELETE FROM " . PREFIX . "plugins_breaking_news WHERE breakingnewsID='" . $_GET[ "breakingnewsID" ] . "'")) {
            @unlink($filepath.$data['carousel_pic']);
            redirect("admincenter.php?site=admin_breaking", "", 0);
        } else {
            redirect("admincenter.php?site=admin_breaking", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} else {
    echo '<div class="card">
    <div class="card-header">
                            <i class="bi bi-newspaper" style="font-size: 1rem;"></i> ' . $plugin_language[ 'carousel' ] . '
                        </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_breaking">'.$plugin_language['carousel'].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>
                        <div class="card-body">

<div class="mb-3 row row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_breaking&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_carousel' ] . '</a>
    </div>
  </div>';
 
    echo '<form method="post" action="admincenter.php?site=admin_breaking">
    <table class="table table-striped">
    <thead>
      <th><b>'.$plugin_language['name'].'</b></th>
      <th><b>'.$plugin_language['is_displayed'].'</b></th>
      <th><b>'.$plugin_language['actions'].'</b></th>
      <th><b>'.$plugin_language['sort'].'</b></th>
    </thead>';

   $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_breaking_news ORDER BY sort");
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
           
            if (stristr($ds[ 'url' ], 'http://') || stristr($ds[ 'url' ], 'https://')) {
        	} else {
            	$title = '<a href="http://' . getinput($ds[ 'url' ]) . '" target="_blank">' . getinput($ds[ 'title' ]) .
                '</a>';
        	}


        	#if (stristr($ds[ 'url' ], 'http://')) {
            #    $title = '<a href="' . getinput($ds[ 'url' ]) . '" target="_blank">' . getinput($ds[ 'title' ]) . '</a>';
            #} else {
            #    $title = '<a href="http://' . getinput($ds[ 'url' ]) . '" target="_blank">' . getinput($ds[ 'title' ]) .
            #    '</a>';
            #}

            $title = $ds[ 'title' ];
    
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($title);
            $title = $translate->getTextByLanguage($title);
            
            echo '<tr>
           <td class="' . $td . '">' . $title . '</td>
           <td class="' . $td . '">' . $displayed . '</td>
           <td class="' . $td . '"><a href="admincenter.php?site=admin_breaking&amp;action=edit&amp;breakingnewsID=' . $ds[ 'breakingnewsID' ] .
                '" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        

<!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_breaking&amp;delete=true&amp;breakingnewsID=' . $ds[ 'breakingnewsID' ] . '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'carousel' ] . '</h5>
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
      
<td class="' . $td . '" align="center"><select name="sort[]">';
            for ($j = 1; $j <= $anz; $j++) {
                if ($ds[ 'sort' ] == $j) {
                    echo '<option value="' . $ds[ 'breakingnewsID' ] . '-' . $j . '" selected="selected">' . $j .
                        '</option>';
                } else {
                    echo '<option value="' . $ds[ 'breakingnewsID' ] . '-' . $j . '">' . $j . '</option>';
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
    '"><input class="btn btn-primary" type="submit" name="sortieren" value="' . $plugin_language[ 'to_sort' ] . '" /></td>
</tr>
</table>
</form></div></div>';
}

    ?>