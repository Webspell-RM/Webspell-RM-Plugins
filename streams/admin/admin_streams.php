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
$plugin_language = $pm->plugin_language("streams", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='streams'");
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
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_streams");
    $ds = mysqli_fetch_array($ergebnis);

    if ($ds['provider'] ?? null) {
                $provider = '<option value="1" selected="selected">' . $plugin_language['stream'] .
                    '</option><option value="0">' . $plugin_language['video'] . '</option>';
            } else {
                $provider = '<option value="1">' . $plugin_language['stream'] .
                    '</option><option value="0" selected="selected">' . $plugin_language['video'] . '</option>';
            };

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-twitch"></i> ' . $plugin_language[ 'streams' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_streams">' . $plugin_language[ 'streams' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_streams' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';

echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_streams" enctype="multipart/form-data">

    
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['provider'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <select id="provider" name="provider" class="form-select">'.$provider.'</select></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['link'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="link" size="60" placeholder="'.$plugin_language['link'].'" /></em></span>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['displayed'].':</label>
    <div class="col-sm-10 form-check form-switch" style="padding: 0px 43px;">
      <input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['widget_displayed'].':</label>
    <div class="col-sm-10 form-check form-switch" style="padding: 0px 43px;">
      <input class="form-check-input" type="checkbox" name="widget_displayed" value="1" checked="checked" />
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['media_widget_displayed'].'</label>
    <div class="col-sm-10 form-check form-switch" style="padding: 0px 43px;">
      <input class="form-check-input" type="checkbox" name="media_widget_displayed" value="1" checked="checked" />
    </div>
  </div>

  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
    <input type="hidden" name="captcha_hash" value="'.$hash.'" />
    <button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['add_streams'].'</button>
    </div>
  </div>

</form></div></div>';
} elseif ($action == "edit") {
$CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
  

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-twitch"></i> ' . $plugin_language[ 'streams' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_streams">' . $plugin_language[ 'streams' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_streams' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';


$streamID = $_GET[ 'streamID' ];
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_streams WHERE streamID='$streamID'");
    $ds = mysqli_fetch_array($ergebnis);


    if ($ds[ 'displayed' ] == 1) {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />';
    } else {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" />';
    }

    if ($ds[ 'widget_displayed' ] == 1) {
        $widget_displayed = '<input class="form-check-input" type="checkbox" name="widget_displayed" value="1" checked="checked" />';
    } else {
        $widget_displayed = '<input class="form-check-input" type="checkbox" name="widget_displayed" value="1" />';
    }

    if ($ds[ 'media_widget_displayed' ] == 1) {
        $media_widget_displayed = '<input class="form-check-input" type="checkbox" name="media_widget_displayed" value="1" checked="checked" />';
    } else {
        $media_widget_displayed = '<input class="form-check-input" type="checkbox" name="media_widget_displayed" value="1" />';
    }

    if ($ds['provider'] == 1) {
                $provider = '<option value="1" selected="selected">' . $plugin_language['stream'] .
                    '</option><option value="0">' . $plugin_language['video'] . '</option>';
            } else {
                $provider = '<option value="1">' . $plugin_language['stream'] .
                    '</option><option value="0" selected="selected">' . $plugin_language['video'] . '</option>';
            };

    
  echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_streams" enctype="multipart/form-data">

     

  
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['provider'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <select id="provider" name="provider" class="form-select">'.$provider.'</select></em></span>
    </div>
  </div>

<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['link'].':</label>
    <div class="col-sm-10"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="link" size="60" value="'.getinput($ds['link']).'" /></em></span>
    </div>
  </div>
  

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['displayed'].':</label>
    <div class="col-sm-10 form-check form-switch" style="padding: 0px 43px;">
     ' . $displayed . '
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['widget_displayed'].':</label>
    <div class="col-sm-10 form-check form-switch" style="padding: 0px 43px;">
    '.$widget_displayed.'
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['media_widget_displayed'].'</label>
    <div class="col-sm-10 form-check form-switch" style="padding: 0px 43px;">
    '.$media_widget_displayed.'
    </div>
  </div>

  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
    <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="streamID" value="'.$streamID.'" />
    <button class="btn btn-success" type="submit" name="saveedit"  />'.$plugin_language['edit_streams'].'</button>
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
                safe_query("UPDATE " . PREFIX . "plugins_streams SET sort='$sorter[1]' WHERE streamID='$sorter[0]' ");
                redirect("admincenter.php?site=admin_streams", "", 0);
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "save" ])) {
    $provider = $_POST[ "provider" ];
    
    $link = $_POST[ "link" ];
    if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    if (!$displayed) {
        $displayed = 0;
    }

    if (isset($_POST[ "widget_displayed" ])) {
        $widget_displayed = 1;
    } else {
        $widget_displayed = 0;
    }
    if (!$widget_displayed) {
        $widget_displayed = 0;
    }

    if (isset($_POST[ "media_widget_displayed" ])) {
        $media_widget_displayed = 1;
    } else {
        $media_widget_displayed = 0;
    }
    if (!$media_widget_displayed) {
        $media_widget_displayed = 0;
    }

   
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "INSERT INTO " . PREFIX . "plugins_streams (`streamID`, `provider`,  `link`, `displayed`, `widget_displayed`, `media_widget_displayed`, `sort`) values('', '" . $provider . "', '" . $link . "', '" . $displayed . "', '" . $widget_displayed . "', '" . $media_widget_displayed . "', '1')"
        );

        $id = mysqli_insert_id($_database);

        $errors = array();

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation', true);

       
     

        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_streams", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "saveedit" ])) {
  $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
      $provider = $_POST[ "provider" ];
      
      $link = $_POST[ "link" ];
   if (isset($_POST[ "displayed" ])) {
            $displayed = 1;
        } else {
            $displayed = 0;
        }

    if (isset($_POST[ "widget_displayed" ])) {
            $widget_displayed = 1;
        } else {
            $widget_displayed = 0;
        }

    if (isset($_POST[ "media_widget_displayed" ])) {
            $media_widget_displayed = 1;
        } else {
            $media_widget_displayed = 0;
        } 

    $streamID = (int)$_POST[ 'streamID' ];
    $id = $streamID;  

    safe_query(
            "UPDATE " . PREFIX . "plugins_streams SET 
            `provider` ='" . $_POST[ 'provider' ] . "', 
            `link` ='" . $_POST[ 'link' ] . "', 
            `displayed` ='" . $displayed . "',
            `widget_displayed` ='" . $widget_displayed . "',
           `media_widget_displayed` ='" . $media_widget_displayed . "' 
            WHERE 
            `streamID` = '" . $streamID . "'" 
        );
    
$id = mysqli_insert_id($_database);

        $errors = array();

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation', true);

       
     

        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_streams", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
}  elseif (isset($_GET[ "delete" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_streams WHERE streamID='" . $_GET[ "streamID" ] . "'");
        $data = mysqli_fetch_assoc($get);

        if (safe_query("DELETE FROM " . PREFIX . "plugins_streams WHERE streamID='" . $_GET[ "streamID" ] . "'")) {
           
            redirect("admincenter.php?site=admin_streams", "", 0);
        } else {
            redirect("admincenter.php?site=admin_streams", "", 0);
        }
    } else {
    print_r($plugin_language); return false;
        $_language->readModule('formvalidation', true);  
        echo $plugin_language[ 'transaction_invalid' ];
    }
} else {

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-twitch"></i> ' . $plugin_language[ 'streams' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_streams">' . $plugin_language[ 'streams' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="mb-3 row row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_streams&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'add_streams' ] . '</a>
    </div>
  </div>';


echo'<form method="post" action="admincenter.php?site=admin_streams">

<table class="table table-striped">
    <thead>
        <tr>

            <th><b>' . $plugin_language[ 'provider' ] . '</b></th>
            <th><b>' . $plugin_language[ 'link' ] . '</b></th>
            <th><b>' . $plugin_language[ 'displayed' ] . '</b></th>
            <th><b>' . $plugin_language[ 'widget_displayed' ] . '</b></th>
            <th><b>' . $plugin_language[ 'media_widget_displayed_x' ] . '</b></th>
            <th><b>' . $plugin_language[ 'actions' ] . '</b></th>
            <th><b>' . $plugin_language[ 'sort' ] . '</b></th>
            
        </tr>
        </thead>
        <tbody>
   
        ';




$CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_streams ORDER BY sort");
    $anz = mysqli_num_rows($qry);
    if ($anz) {
        $i = 1;
        while ($ds = mysqli_fetch_array($qry)) {
            
            $ds[ 'displayed' ] == 1 ?
            $displayed = '<font color="green"><b>' . $plugin_language[ 'yes' ] . '</b></font>' :
            $displayed = '<font color="red"><b>' . $plugin_language[ 'no' ] . '</b></font>';

            $ds[ 'widget_displayed' ] == 1 ?
            $widget_displayed = '<font color="green"><b>' . $plugin_language[ 'yes' ] . '</b></font>' :
            $widget_displayed = '<font color="red"><b>' . $plugin_language[ 'no' ] . '</b></font>';
            
            $ds[ 'media_widget_displayed' ] == 1 ?
            $media_widget_displayed = '<font color="green"><b>' . $plugin_language[ 'yes' ] . '</b></font>' :
            $media_widget_displayed = '<font color="red"><b>' . $plugin_language[ 'no' ] . '</b></font>';

                #$provider = getinput($ds[ 'provider' ]);
           
                $link = getinput($ds[ 'link' ]);
           
        if ($ds[ 'provider' ]) {
            $pro = $plugin_language[ 'stream' ];
        } else {
            $pro = $plugin_language[ 'video' ];
        }
            
        $_GET['streamID']=$ds['streamID'];
            echo '<tr>
            <td>' . $pro . '</td>
            
            <td>' . $link . '</td>
            <td>' . $displayed . '</td>
            <td>' . $widget_displayed . '</td>
            <td>' . $media_widget_displayed . '</td>
            
            <td>

<a href="admincenter.php?site=admin_streams&amp;action=edit&amp;streamID=' . $ds[ 'streamID' ] . '" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="'.$ds[ 'streamID' ].'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->


                    </td>
        <td><select name="sort[]">';
            for ($j = 1; $j <= $anz; $j++) {
                if ($ds[ 'sort' ] == $j) {
                    echo '<option value="' . $ds[ 'streamID' ] . '-' . $j . '" selected="selected">' . $j .
                        '</option>';
                } else {
                    echo '<option value="' . $ds[ 'streamID' ] . '-' . $j . '">' . $j . '</option>';
                }
            }
            echo '</select>
</td>
</tr>';
            $i++;
        }
        echo'<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'streams' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
        <a type="button" class="btn btn-danger" type="button" href="admincenter.php?site=admin_streams&amp;delete=true&amp;streamID='.$_GET['streamID'].'&amp;captcha_hash='.$hash.'" />
            ' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->';

    } else {
        echo '<tr><td colspan="7">' . $plugin_language[ 'no_entries' ] . '</td></tr>';
    }

   echo '<tr>
<td colspan="7" align="right"><input type="hidden" name="captcha_hash" value="' . $hash .
    '"><br><input class="btn btn-success" type="submit" name="sortieren" value="' . $plugin_language[ 'to_sort' ] . '" /></td>
</tr>
</tbody></table>
</form></div></div>';

}