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
$plugin_language = $pm->plugin_language("server", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='servers'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}

if (isset($_POST[ 'save' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $provider = $_POST[ "provider" ];

        if (isset($_POST[ "displayed" ])) {
            $displayed = 1;
        } else {
            $displayed = 0;
        }
        if (!$displayed) {
            $displayed = 0;
        }


        safe_query(
            "INSERT INTO
                `" . PREFIX . "plugins_servers` (
                    `name`,
                    `ip`,
                    `provider`,
                    `game`,
                    `info`,
                    `displayed`,
                    `sort`
                )
                VALUES(
                    '" . $_POST[ 'name' ] . "',
                    '" . $_POST[ 'serverip' ] . "',
                    '" . $_POST[ 'provider' ] . "',
                    '" . $_POST[ 'game' ] . "',
                    '" . $_POST[ 'message' ] . "',
                    '" . $displayed . "', 
                    '1'
                    )"
        );
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'saveedit' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $provider = $_POST[ "provider" ];

        if (isset($_POST[ "displayed" ])) {
            $displayed = 1;
        } else {
            $displayed = 0;
        }

        safe_query(
            "UPDATE " . PREFIX . "plugins_servers SET name='" . $_POST[ 'name' ] . "', ip='" . $_POST[ 'serverip' ] .
            "', `provider` ='" . $_POST[ 'provider' ] . "', game='" . $_POST[ 'game' ] . "', info='" . $_POST[ 'message' ] . "', displayed='" . $displayed . "' WHERE serverID='" .
            $_POST[ 'serverID' ] . "'"
        );
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'sort' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (is_array($_POST[ 'sortlist' ])) {
            foreach ($_POST[ 'sortlist' ] as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE " . PREFIX . "plugins_servers SET sort='$sorter[1]' WHERE serverID='$sorter[0]' ");
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_GET[ 'delete' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        safe_query("DELETE FROM " . PREFIX . "plugins_servers WHERE serverID='" . $_GET[ 'serverID' ] . "'");
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
}

$games = '';
$gamesa = safe_query("SELECT tag, name FROM " . PREFIX . "plugins_games_pic ORDER BY name");
while ($dv = mysqli_fetch_array($gamesa)) {
    $games .= '<option value="' . $dv[ 'tag' ] . '">' . getinput($dv[ 'name' ]) . '</option>';
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

    if ($ds['provider'] ?? null) {
        $provider = '<option value="1" selected="selected">' . $plugin_language['ts_server'] .
                    '</option><option value="0">' . $plugin_language['game_server'] . '</option>';
    } else {
        $provider = '<option value="1">' . $plugin_language['ts_server'] .
                    '</option><option value="0" selected="selected">' . $plugin_language['game_server'] . '</option>';
    };

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-controller"></i> ' . $plugin_language[ 'servers' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_servers">' . $plugin_language[ 'servers' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_server' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';

echo '<script>
        function chkFormular() {
            if(!validbbcode(document.getElementById(\'message\').value, \'admin\')){
                return false;
            }
        }
    </script>';
  
	echo '<form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=admin_servers" onsubmit="return chkFormular();">
	 <div class="row">

<div class="col-md-6">

<div class="mb-3 row">
    <label class="col-sm-3 control-label">'.$plugin_language['provider'].':</label>
    <div class="col-sm-9"><span class="text-muted small"><em>
      <select id="provider" name="provider" class="form-select">'.$provider.'</select></em></span>
    </div>
  </div>

<div class="mb-3 row">
    <label class="col-sm-3 control-label">'.$plugin_language['server_name'].':</label>
    <div class="col-sm-9"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="name" size="60" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-3 control-label">'.$plugin_language['ip_port'].':</label>
    <div class="col-sm-9"><span class="text-muted small"><em>
    <input class="form-control" type="text" name="serverip" size="60" /></em></span>
    </div>
  </div>

  </div>
  

<div class="col-md-6">
  
<div class="mb-3 row">
    <label class="col-sm-3 control-label">'.$plugin_language['game'].':</label>
    <div class="col-sm-9"><span class="text-muted small"><em>
    <select class="form-select" name="game">'.$games.'</select></em></span>
    </div>
  </div>

<div class="mb-3 row">
    <label class="col-sm-3 control-label">' . $plugin_language[ 'is_displayed' ] . ':</label>
    <div class="col-sm-9 form-check form-switch" style="padding: 0px 43px;">
    <input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />
    </div>
  </div>  

</div>

<div class="col-md-12">
  <div class="mb-3 row">
   <div class="col-sm-12">
    <textarea class="ckeditor" id="ckeditor" name="message" rows="10" cols="" ></textarea>
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-12">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" />
		<button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['add_server'].'</button>
    </div>
  </div>

 </div>
  </form></div>
  </div>';
} elseif($action=="edit") {

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-controller"></i> ' . $plugin_language[ 'servers' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_servers">' . $plugin_language[ 'servers' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_server' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';

      $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $serverID = $_GET[ 'serverID' ];
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_servers WHERE serverID='" . $serverID . "'");
    $ds = mysqli_fetch_array($ergebnis);

    $games = str_replace(' selected="selected"', '', $games);
    $games = str_replace('value="' . $ds[ 'game' ] . '"', 'value="' . $ds[ 'game' ] . '" selected="selected"', $games);

    if ($ds[ 'displayed' ] == 1) {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />';
    } else {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" />';
    }

    if ($ds['provider'] == 1) {
        $provider = '<option value="1" selected="selected">' . $plugin_language['ts_server'] .
                    '</option><option value="0">' . $plugin_language['game_server'] . '</option>';
    } else {
        $provider = '<option value="1">' . $plugin_language['ts_server'] .
                    '</option><option value="0" selected="selected">' . $plugin_language['game_server'] . '</option>';
    };

    echo '<script>
        function chkFormular() {
            if(!validbbcode(document.getElementById(\'message\').value, \'admin\')){
                return false;
            }
        }
    </script>';

    
  echo '<form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=admin_servers" onsubmit="return chkFormular();">
<div class="row">

<div class="col-md-6">

<div class="mb-3 row">
    <label class="col-sm-3 control-label">'.$plugin_language['provider'].':</label>
    <div class="col-sm-9"><span class="text-muted small"><em>
      <select id="provider" name="provider" class="form-select">'.$provider.'</select></em></span>
    </div>
  </div>

   <div class="mb-3 row">
    <label class="col-sm-3 control-label">'.$plugin_language['server_name'].':</label>
    <div class="col-sm-9"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="name" value="'.getinput($ds['name']).'" /></em></span>
    </div>
  </div>
  
  <div class="mb-3 row">
    <label class="col-sm-3 control-label">'.$plugin_language['ip_port'].':</label>
    <div class="col-sm-9"><span class="text-muted small"><em>
		<input class="form-control" type="text" name="serverip" value="'.getinput($ds['ip']).'" /></em></span>
    </div>
  </div>

  </div>
  

<div class="col-md-6">

<div class="mb-3 row">
    <label class="col-sm-3 control-label">'.$plugin_language['game'].':</label>
    <div class="col-sm-9"><span class="text-muted small"><em>
    <select class="form-select" name="game">'.$games.'</select></em></span>
    </div>
  </div>

<div class="mb-3 row">
    <label class="col-sm-3 control-label">' . $plugin_language[ 'is_displayed' ] . ':</label>
    <div class="col-sm-9 form-check form-switch" style="padding: 0px 43px;">
    ' . $displayed . '
    </div>
  </div>  

</div>


  <div class="col-md-12">
   <div class="mb-3">
   <div class="col-sm-12">
      <textarea class="ckeditor" id="ckeditor" name="message" rows="10" cols="" >'.getinput($ds['info']).'</textarea>
    </div>
  </div>

  <div class="mb-3 row">
    <div class="col-sm-12">
		<input type="hidden" name="serverID" value="'.$serverID.'" /><input type="hidden" name="captcha_hash" value="'.$hash.'" />
		<button class="btn btn-warning" type="submit" name="saveedit"  />'.$plugin_language['edit_server'].'</button>
    </div>
  </div>

  </div>
  
  </form></div>
  </div>';
}

else {

  echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-controller"></i> ' . $plugin_language[ 'servers' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_servers">' . $plugin_language[ 'servers' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="mb-3 row row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_servers&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_server' ] . '</a>
    </div>
  </div>';

	$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_servers ORDER BY sort");
    $anz = mysqli_num_rows($ergebnis);
    if ($anz) {
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();
  
  echo'<form method="post" name="ws_servers" action="admincenter.php?site=admin_servers">
    <table class="table table-striped">
      <thead>
        <th><b>'.$plugin_language['game'].'</b></th>
        <th><b>'.$plugin_language['servers'].'</b></th>
        <th><b>' . $plugin_language[ 'is_displayed' ] . '</b></th>
        <th><b>'.$plugin_language['actions'].'</b></th>
        <th><b>'.$plugin_language['sort'].'</b></th>
      </thead>';

		$i = 1;
        while ($ds = mysqli_fetch_array($ergebnis)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }

            $list = '<select name="sortlist[]">';
            $counter = mysqli_num_rows($ergebnis);
            for ($n = 1; $n <= $counter; $n++) {
                $list .= '<option value="' . $ds[ 'serverID' ] . '-' . $n . '">' . $n . '</option>';
            }
            $list .= '</select>';
            $list = str_replace(
                'value="' . $ds[ 'serverID' ] . '-' . $ds[ 'sort' ] . '"',
                'value="' . $ds[ 'serverID' ] . '-' . $ds[ 'sort' ] . '" selected="selected"',
                $list
            );

            $ds[ 'displayed' ] == 1 ?
            $displayed = '<font color="green"><b>' . $plugin_language[ 'yes' ] . '</b></font>' :
            $displayed = '<font color="red"><b>' . $plugin_language[ 'no' ] . '</b></font>';

            $name = $ds[ 'name' ];
            $info = $ds[ 'info' ];

            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($name);
            $name = $translate->getTextByLanguage($name);
            $translate->detectLanguages($info);
            $info = $translate->getTextByLanguage($info);

        if(file_exists('./includes/plugins/games_pic/images/'.$ds['game'].'.jpg')){
            $gameicon='<img style="height: 100px" src="../includes/plugins/games_pic/images/'.$ds['game'].'.jpg" alt="">';
        } elseif(file_exists('./includes/plugins/games_pic/images/'.$ds['game'].'.jpeg')){
            $gameicon='<img style="height: 100px" src="../includes/plugins/games_pic/images/'.$ds['game'].'.jpeg" alt="">';
        } elseif(file_exists('./includes/plugins/games_pic/images/'.$ds['game'].'.png')){
            $gameicon='<img style="height: 100px" src="../includes/plugins/games_pic/images/'.$ds['game'].'.png" alt="">';
        } elseif(file_exists('./includes/plugins/games_pic/images/'.$ds['game'].'.gif')){
            $gameicon='<img style="height: 100px" src="../includes/plugins/games_pic/images/'.$ds['game'].'.gif" alt="">';
        } else{
           $gameicon='<img style="height: 100px" src="../includes/plugins/games_pic/images/no-image.jpg" alt="">';
        }
    
            
       echo '<tr>
        <td>'.$gameicon.'</td>

        <td><b>'.$name.'</b><br /> <b>IP:</b> '.$ds['ip'].'</td>
        <td>' . $displayed . '</td>
        <td><a href="admincenter.php?site=admin_servers&amp;action=edit&amp;serverID='.$ds['serverID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_servers&amp;delete=true&amp;serverID='.$ds['serverID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'servers' ] . '</h5>
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
        <td>'.$list.'</td>
      </tr>';
        
        $i++;
		}
		echo'<tr>
        <td colspan="5" class="td_head" align="right"><input type="hidden" name="captcha_hash" value="'.$hash.'" /><button class="btn btn-primary" type="submit" name="sort" />'.$plugin_language['to_sort'].'</button></td>
      </tr>
    </table>
    </form>';
	}
	else echo $plugin_language['admin_no_server'];
}
echo '</div></div>';
?>