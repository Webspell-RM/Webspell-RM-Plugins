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
\¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*/

# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("admin_cup", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='faq'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}

include('./includes/plugins/cup/cup_abfragen-org.php');

$filename = 'install_cup.php';

?>
  
			    <?php
                $page = (isset($_GET['page'])?$_GET['page']:'');
				switch($page) {
					
					######################  Teams  ####################
					case 'teams':
					if(isset($_REQUEST['add'])) {
						$CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
						$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams");
						#$id = $_POST['cupID'];
						$teamid = $_POST['teamid'];
						$clantag = $_POST['clantag'];
						$name = $_POST['name'];
						$gruppe = $_POST['gruppe'];
						$anordnung = $_POST['anordnung'];
						$hp = $_POST['hp'];
						$color = $_POST['color'];

						safe_query(
            "INSERT INTO
                `" . PREFIX . "plugins_cup_teams` (
                    `name`,
                    `clantag`,
                    `gruppe`,
                    `anordnung`,
                    `hp`,
                    `color`
                )
                VALUES (
                    '$name',
                    '$clantag',
                    '$gruppe',
                    '$anordnung',
                    '$hp',
                    '$color'
                )"
        );

						$id = mysqli_insert_id($_database);

        $filepath = $plugin_path."images/team/";

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation',true, true);
        
        $upload = new \webspell\HttpUpload('banner');

        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation =  getimagesize($upload->getTempFile());

                    if (is_array($imageInformation)) {
                        if ($imageInformation[0] < 55 && $imageInformation[1] < 301) {
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

                            if (file_exists($filepath . $id . '.gif')) {
                                unlink($filepath . $id . '.gif');
                            }
                            if (file_exists($filepath . $id . '.jpg')) {
                                unlink($filepath . $id . '.jpg');
                            }
                            if (file_exists($filepath . $id . '.png')) {
                                unlink($filepath . $id . '.png');
                            }

                            if ($upload->saveAs($filepath.$file)) {
                                @chmod($filepath.$file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_cup_teams
                                    SET banner='" . $file . "' WHERE cupID='" . $id . "'"
                                );
                            }
                        } else {
                            echo generateErrorBox(sprintf($plugin_language[ 'image_too_big' ], 54, 30));
                        }
                    } else {
                        echo generateErrorBox($plugin_language[ 'broken_image' ]);
                    }
                } else {
                    echo generateErrorBox($plugin_language[ 'unsupported_image_type' ]);
                }
            } else {
                echo  generateErrorBox($upload->translateError());
            }
        }
						#safe_query("INSERT INTO `" . PREFIX . "plugins_cup_teams` VALUES ('" . $teamid . "','" . $clantag . "','" . $name . "','" . $gruppe . "','" . $anordnung . "','" . $hp . "','','','','','','','','','','','','" . $color . "'");
						#echo 'Team wurde hinzugefügt';
						redirect('admincenter.php?site=admin_cup&page=teams', $plugin_language[ 'team_add' ], 3);
					}

}

					if(isset($_REQUEST['setedit'])) {
						$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams");
						$id = $_POST['cupID'];
						$cupID = (int)$_POST[ 'cupID' ];
						$teamid = $_POST['teamid'];
						$clantag = $_POST['clantag'];
						$name = $_POST['name'];
						$gruppe = $_POST['gruppe'];
						$anordnung = $_POST['anordnung'];
						$hp = $_POST['hp'];
						$color = $_POST['color'];



 		$id = mysqli_insert_id($_database);

        $filepath = $plugin_path."images/team/";

        //TODO: should be loaded from root language folder
        $_language->readModule('formvalidation',true, true);
        
        $upload = new \webspell\HttpUpload('banner');

        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation =  getimagesize($upload->getTempFile());

                    if (is_array($imageInformation)) {
                        if ($imageInformation[0] < 55 && $imageInformation[1] < 301) {
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

                            if (file_exists($filepath . $id . '.gif')) {
                                unlink($filepath . $id . '.gif');
                            }
                            if (file_exists($filepath . $id . '.jpg')) {
                                unlink($filepath . $id . '.jpg');
                            }
                            if (file_exists($filepath . $id . '.png')) {
                                unlink($filepath . $id . '.png');
                            }

                            if ($upload->saveAs($filepath.$file)) {
                                @chmod($filepath.$file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_cup_teams
                                    SET banner='" . $file . "' WHERE cupID='" . $cupID . "'"
                                );
                            }
                        } else {
                            echo generateErrorBox(sprintf($plugin_language[ 'image_too_big' ], 54, 30));
                        }
                    } else {
                        echo generateErrorBox($plugin_language[ 'broken_image' ]);
                    }
                } else {
                    echo generateErrorBox($plugin_language[ 'unsupported_image_type' ]);
                }
            } else {
                echo  generateErrorBox($upload->translateError());
            }
        }


						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET name='" . $name . "', clantag='" . $clantag . "', teamid='" . $teamid . "', hp='" . $hp . "', gruppe='" . $gruppe . "', anordnung='" . $anordnung . "', color='" . $color . "' WHERE cupID='" . $cupID . "'");
                        echo '<div class="alert alert-success" role="alert">'.$plugin_language[ 'entryischanged' ].'</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup&page=teams">';
					}

                    
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='del') {
						$CAPCLASS = new \webspell\Captcha;
    					if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
						$cupID = $_GET['cupID'];
						safe_query("DELETE FROM `" . PREFIX . "plugins_cup_teams` WHERE cupID='" . $cupID . "'");
						$CAPCLASS = new \webspell\Captcha;
    					}
					}
					if(isset($_REQUEST['dummy'])) {
						/* db_query("INSERT INTO prefix_cup_config SET id='1', gruppe='ja', tablebg1='#e1e1e1', tablebg2='#ebebeb', headbg='#ffffff', headfont='#000000', fieldbg='#ffffff', fieldbor='#cccccc', font1='#000000', font2='#2BB4FE', preis1='Preis1', preis2='Preis2', preis3='Preis3' ON DUPLICATE KEY UPDATE gruppe='ja', tablebg1='#e1e1e1', tablebg2='#ebebeb', headbg='#ffffff', headfont='#000000', fieldbg='#ffffff', fieldbor='#cccccc', font1='#000000', font2='#2BB4FE', preis1='Preis1', preis2='Preis2', preis3='Preis3'"); */
						safe_query("INSERT INTO `" . PREFIX . "plugins_cup_teams` (`cupID`, `teamid`, `clantag`, `name`, `gruppe`, `anordnung`, `hp`, `viertel`, `halb`, `finale`, `p1`, `p2`, `p3`, `eg`, `ev`, `eh`, `ef`, `ep3`, `color`, `banner`) VALUES
						    (1, 0, 'RM', 'Webspell RM', 1, 1, 'https://www.Webspell-RM.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '#a2b9bc', '1.png'),
                            (2, 0, 'df', 'Die Front', 1, 2, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '#b2ad7f', '2.png'),
                            (3, 0, '-HT-', 'Harrington Team', 1, 3, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '#878f99', '3.png'),
                            (4, 0, '=WT=', 'wilbury team 0', 1, 4, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '#6b5b95', '4.png'),
                            (5, 0, 'KB', 'Kaos Bande', 2, 5, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '#d6cbd3', '5.png'),
                            (6, 0, '#LT#', 'LazyTeam', 2, 6, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '#eca1a6', '6.png'),
                            (7, 0, 'DH', 'dahirinis', 2, 7, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '#bdcebe', '7.png'),
                            (8, 0, 'RC', 'Roxbury Clan', 2, 8, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '#ada397', '8.png'),
                            (9, 0, 'TA', 'Team Austria', 3, 9, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '#b9936c', '9.png'),
                            (10, 0, 'RF', 'Roflmao', 3, 10, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '#dac292', '10.png'),
                            (11, 0, 'FC', 'flashchecker', 3, 11, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '#e6e2d3', '11.png'),
                            (12, 0, 'DW', 'Dawutz', 3, 12, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '#c4b7a6', '12.png'),
                            (13, 0, 'TW', 'Team Wax', 4, 13, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '#92a8d1', '13.png'),
                            (14, 0, '=???=', 'Pretenders', 4, 14, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '#034f84', '14.png'),
                            (15, 0, '=HH=', 'HullaHups', 4, 15, 'http://unserehp.de', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '#f7cac9', '15.png'),
                            (16, 0, 'BA', 'Black Angel Team', 4, 16, 'https://blackangelteam.net', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '#c67c16', '16.png');");
						
                        redirect('/admin/admincenter.php?site=admin_cup&page=teams', "<div class='alert alert-success' role='alert'>".$plugin_language[ 'dummiesused' ]."</div>", 3);
					}
					if(isset($_REQUEST['dummydel'])) {
						safe_query('TRUNCATE TABLE `' . PREFIX . 'plugins_cup_teams`');
                        redirect('/admin/admincenter.php?site=admin_cup&page=teams', "<div class='alert alert-danger' role='alert'>".$plugin_language[ 'dummiesdeleted' ]."</div>", 3);
					}			
					// Halb
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='halb') {
						$id = $_GET['id'];
						$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE cupID='" . $id . "'");
						$dc = mysqli_fetch_array($get);
						$halb = $dc['halb'];
						$halb = 1;
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET halb='" . $halb . "' WHERE cupID='" . $id . "'");
                        echo '<div class="alert alert-success" role="alert">'.$plugin_language[ 'pleasewait' ].'</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='loseh') {
						$id = $_GET['id'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET halb='0' WHERE cupID='" . $id . "'");
					}
					// Finale
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='finale') {
						$id = $_GET['id'];
						$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE cupID='" . $id . "'");
						$dc = mysqli_fetch_array($get);
						$finale = $dc['finale'];
						$finale = 1;
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET finale='" . $finale . "' WHERE cupID='" . $id . "'");
					}
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='losef') {
						$id = $_GET['id'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET finale='0' WHERE cupID='" . $id . "'");
					}
					// Platz1
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='p1') {
						$id = $_GET['id'];
						$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE cupID='" . $id . "'");
						$dc = mysqli_fetch_array($get);
						$p1 = $dc['p1'];
						$p1 = 1;
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET p1='" . $p1 . "' WHERE cupID='" . $id . "'");
					}
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='losep1') {
						$id = $_GET['id'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET p1='0' WHERE cupID='" . $id . "'");
					}
					// Platz3
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='p3') {
						$id = $_GET['id'];
						$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE cupID='" . $id . "'");
						$dc = mysqli_fetch_array($get);
						$p3 = $dc['p3'];
						$p3 = 1;
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET p3='" . $p3 . "' WHERE cupID='" . $id . "'");
					}
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='losep3') {
						$id = $_GET['id'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET p3='0' WHERE cupID='" . $id . "'");
					}
					

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'title' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_cup">' . $plugin_language[ 'cup' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['cup_teams'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">';
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
echo'




					<script type="text/javascript" src="/includes/plugins/cup/js/cCore.js"></script>
  <form action="admincenter.php?site=admin_cup&page=teams" method="post" enctype="multipart/form-data">
  <div class="col-sm-12 row">
<div class="col-sm-6 row">
   <div class="mb-3 row">
    <label class="col-sm-3 control-label">' . $plugin_language[ 'team_name' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
		<input class="form-control" type="text" name="name" size="97" /></em></span>
	</div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-3 control-label">' . $plugin_language[ 'clantag' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
		<input class="form-control" type="text" name="clantag" size="97" /></em></span>
	</div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-3 control-label">' . $plugin_language[ 'teamid' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em data-toggle="tooltip" title="' . $plugin_language[ 'tooltip_1' ] . '">
		<input class="form-control" type="text" name="teamid" size="97" /></em></span>
	</div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-3 control-label">' . $plugin_language[ 'homepage' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
		<input class="form-control" type="text" name="hp" size="97" /></em></span>
	</div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-3 control-label">' . $plugin_language[ 'gruppe' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em data-toggle="tooltip" data-placement="top" title="' . $plugin_language[ 'tooltip_2' ] . '">
		<input class="form-control" type="text" name="gruppe" size="97" /></em></span>
	</div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-3 control-label">' . $plugin_language[ 'arrangement' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em data-toggle="tooltip" data-placement="top" title="' . $plugin_language[ 'tooltip_2' ] . '">
		<input class="form-control" type="text" name="anordnung" size="97" /></em></span>
	</div>
  </div>
    <div class="mb-3 row">
    <label class="col-sm-3 control-label">' . $plugin_language[ 'color' ] . ':</label>
    
<div class="col-sm-8">

    <div id="cp76" class="input-group colorpicker-component col-sm-8">
            <input class="form-control" type="text" name="color" size="97" /><span class="input-group-text input-group-addon"><i class="bi bi-palette"></i></span> 
    </div>

</div>

  </div>
  <div class="mb-3 row">
    <label class="col-sm-3 control-label">'.$plugin_language['banner'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="btn btn-info" name="banner" type="file" size="40" /> <small>(max. 54x30)</small></em></span>
    </div>
  </div>
  
<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" />
		<button class="btn btn-success" type="submit" name="add"  />'.$plugin_language['add_cup'].'</button>
    </div>
  </div>


</div>
<div class="col-sm-6 row">

<div class="mb-3 row">
    <label class="col-sm-3 control-label">' . $plugin_language[ 'add_dummy' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" />
		<button class="btn btn-success" type="submit" name="dummy"  />'.$plugin_language['add_dummy'].'</button>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-3 control-label">' . $plugin_language[ 'del_dummy' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small">
    <input name="dummydel" type="submit"  class="btn btn-danger" type="button" onclick="MM_confirm(\'' . $plugin_language['really_delete_dummy'] . '\')"" value="'.$plugin_language['del_dummy'].'" />
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-8"><span class="text-muted small">
    
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-8"><span class="text-muted small">
    
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-8"><span class="text-muted small">
    
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-8"><span class="text-muted small">
    
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-8"><span class="text-muted small">
    
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-8"><span class="text-muted small">
    
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-8"><span class="text-muted small">
    
    </div>
  </div>


 </div>
</div>
</form>
<div class="col-sm-12">
<hr>
</div>
  ';
  ?>

					<?php
                    
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='edit') {
						$cupID = $_GET['cupID'];
						$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE cupID='" . $cupID . "'");
						$dc = mysqli_fetch_array($get);
						$CAPCLASS = new \webspell\Captcha;
    					$CAPCLASS->createTransaction();
    					$hash = $CAPCLASS->getHash();



						echo "<form action='admincenter.php?site=admin_cup&page=teams' method='post' enctype='multipart/form-data'>";
						#echo "<input type='hidden' value='$dc[id]' name='id' />";
						echo'<div class="card card-body alert alert-warning"><table class="table table-striped">
                        <thead>
                            <th><b>' . $plugin_language['name'] . '</b></th>
                            <th><b>' . $plugin_language['clantag'] . '</b></th>
                            <th><b>' . $plugin_language['gruppe'] . '</b></th>
                            <th><b>' . $plugin_language['arrangement'] . '</b></th>
                            <th><b>' . $plugin_language['teamid'] . '</b></th>
                        </thead>';
                        echo '<tr><td>
                        <input class="form-control" name="name" type="text" size="20" value="'.$dc['name'].'"">
                        </td><td>
                        <input class="form-control" name="clantag" type="text" size="10" value="'.$dc['clantag'].'">
                        </td><td>
                        <input class="form-control" name="gruppe" type="text" size="1" value="'.$dc['gruppe'].'"">
                        </td><td>
                        <input class="form-control" name="anordnung" type="text" size="1" value="'.$dc['anordnung'].'"">
                        </td><td>
                        <input class="form-control" name="teamid" type="text" size="2" value="'.$dc['teamid'].'"">
                        </td><td></tr><tr></table><table class="table table-striped">
                        <thead>
                            <th><b>' . $plugin_language['homepage'] . '</b></th>
                            <th><b>' . $plugin_language['color'] . '</b></th>
                            <th><b>' . $plugin_language['banner'] . '</b></th>
                            <th><b>' . $plugin_language['actions'] . '</b></th>
                        </thead><tr><td>
                        <input class="form-control" name="hp" type="text" size="20" value="'.$dc['hp'].'"">
                        </td><td>
                        <div id="cp77" class="input-group colorpicker-component col-md-7">
                        <input type="text" value="'.$dc['color'].'" class="form-control" name="color" /><span class="input-group-text input-group-addon"><i class="bi bi-palette"></i></span> 
                        </div>                        
                        </td><td>';
                        if ($dc['banner']) {
                            $cuppic = '<div class="col-md-2 btn btn-info border border-secondary" style="margin: 5px;margin-left: 10px;"><img src="/includes/plugins/cup/images/team/'.$dc['banner'].'" alt=""></div>';
                        } else {
                            $cuppic = '<div class="col-md-2 btn btn-info border border-secondary" style="margin: 5px;margin-left: 10px;">n/a</div>';
                        }
                        echo''.$cuppic.' 
                        <input class="btn btn-info" name="banner" type="file" size="40" /> <small>(max. 54x30)</small>
                        </td><td>
                        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
                        <input type="hidden" name="cupID" value="'.$dc['cupID'].'" />
                        <button class="btn btn-warning" type="submit" name="setedit"  />'.$plugin_language['save'].'</button>
                        </td></tr>
                        </table></form></div>
                        <br><hr>';
					}











					$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams ORDER BY anordnung ASC");
					echo'<table class="table table-striped">
    <thead>
      <th><b>' . $plugin_language['name'] . '</b></th>
      <th><b>' . $plugin_language['clantag'] . '</b></th>
      <th><b>' . $plugin_language['gruppe'] . '</b></th>
      <th><b>' . $plugin_language['arrangement'] . '</b></th>
      <th><b>' . $plugin_language['teamid'] . '</b></th>
      <th><b>' . $plugin_language['homepage'] . '</b></th>
      <th><b>' . $plugin_language['color'] . '</b></th>
      <th><b>' . $plugin_language['banner'] . '</b></th>
      <th><b>' . $plugin_language['actions'] . '</b></th>
    </thead>';
					

					$CAPCLASS = new \webspell\Captcha;
    				$CAPCLASS->createTransaction();
    				$hash = $CAPCLASS->getHash();
					while($dc=mysqli_fetch_array($get)) {
						echo '<tr><td>
						'.$dc['name'].'
						</td><td>
						'.$dc['clantag'].'
						</td><td>
						'.$dc['gruppe'].'
						</td><td>
						'.$dc['anordnung'].'
						</td><td>
						'.$dc['teamid'].'
						</td><td>
						<a href="'.$dc['hp'].'" target="_blank">'.$dc['hp'].'</a>
						</td><td>
                        <input type="color" class="form-control form-control-color" id="exampleColorInput" value="'.$dc['color'].'" title="Choose your color">
						</td><td>';
                        if ($dc['banner']) {
                            $cuppic = '<div class="col-md-6 btn btn-info border border-secondary" style="margin: 5px;margin-left: 10px;"><img src="/includes/plugins/cup/images/team/'.$dc['banner'].'" alt=""></div>';
                        } else {
                            $cuppic = '<div class="col-md-6 btn btn-info border border-secondary" style="margin: 5px;margin-left: 10px;">n/a</div>';
                        }
		                echo''.$cuppic.'</td><td>
						<a href="admincenter.php?site=admin_cup&page=teams&do=edit&cupID='.$dc['cupID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>
						

<!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_cup&page=teams&do=del&cupID='.$dc['cupID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'cup_teams' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete_team'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->



						';
					}
					echo '</table>';
					break;
echo'</div><div>';					
					##################  Config  ####################
					case 'config': 
					if(isset($_REQUEST['add'])) {
						$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_config");
						#$id = $_POST['id'];
						$gruppe = $_POST['gruppe'];
                        $register = $_POST['register'];
                        $turnier = $_POST['turnier'];
						$preis1 = $_POST['preis1'];
						$preis2 = $_POST['preis2'];
						$preis3 = $_POST['preis3'];
						/* db_query("INSERT INTO prefix_cup_config VALUES ('".$id."','".$gruppe."','".$tablebg1."','".$tablebg2."','".$headbg."','".$headfont."','".$fieldbg."','".$fieldbor."','".$font1."','".$font2."')"); */
						safe_query("INSERT INTO `" . PREFIX . "plugins_cup_config` SET id='1', gruppe='" . $gruppe . "', register='" . $register . "', turnier='" . $turnier . "', preis1='" . $preis1 . "', preis2='" . $preis2 . "', preis3='" . $preis3 . "' ON DUPLICATE KEY UPDATE gruppe='" . $gruppe . "', register='" . $register . "', turnier='" . $turnier . "', preis1='" . $preis1 . "', preis2='" . $preis2 . "', preis3='" . $preis3 . "'");
						echo '<h3>' . $plugin_language[ 'configsaved' ] . '</h3>';
					}
					if(isset($_REQUEST['addori'])) {
						safe_query("INSERT INTO `" . PREFIX . "plugins_cup_config` SET id='1', gruppe='ja', register='ja', turnier='ja', preis1='Preis1', preis2='Preis2', preis3='Preis3' ON DUPLICATE KEY UPDATE gruppe='ja', register='ja', turnier='ja', preis1='Preis1', preis2='Preis2', preis3='Preis3'");
						echo '<h3>' . $plugin_language[ 'originalrestored' ] . '</h3>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup&page=config">';
					}
					
					echo' <div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'title' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_cup">' . $plugin_language[ 'cup' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['cup_config'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">';
                    	
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
?>
                    <form action="admincenter.php?site=admin_cup&page=config" method="post" enctype="multipart/form-data">
                        <table>
                            <tr>
                                <td><? echo $plugin_language[ 'groupdisplay' ] ?>:</td>
                                <td align="left">
                                    <select class="form-select" name="gruppe">
                                     <? if ($gruppe == 'ja') {
                                          $gruppe = $plugin_language[ 'yes' ];
                                          $gruppevalue = 'ja';
                                        
                                        } 
                                        if ($gruppe == 'nein') {
                                          $gruppe = $plugin_language[ 'no' ];
                                          $gruppevalue = 'nein';
                                        }
                                     ?>
                                        <option selected value="<?php echo $gruppevalue; ?>"><?php echo $gruppe; ?></option>
                                        <option value="ja"><? echo $plugin_language[ 'yes' ] ?></option>
                                        <option value="nein"><? echo $plugin_language[ 'no' ] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><? echo $plugin_language[ 'logindisplay' ] ?>:</td>
                                <td align="left">
                                    <select class="form-select" name="register">
                                     <? if ($register == 'ja') {
                                          $register = $plugin_language[ 'yes' ];
                                          $registervalue = 'yes';
                                        } 
                                        if ($register == 'nein') {
                                          $register = $plugin_language[ 'no' ];
                                          $registervalue = 'nein';
                                        }
                                     ?>
                                        <option selected value="<?php echo $gruppevalue; ?>"><?php echo $register; ?></option>
                                        <option value="ja"><? echo $plugin_language[ 'yes' ] ?></option>
                                        <option value="nein"><? echo $plugin_language[ 'no' ] ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><? echo $plugin_language[ 'tournamentdisplay' ] ?>:</td>
                                <td align="left">
                                    <select class="form-select" name="turnier">
                                     <? if ($turnier == 'ja') {
                                          $turnier = $plugin_language[ 'yes' ];
                                          $turniervalue = 'ja';
                                        } 
                                        if ($turnier == 'nein') {
                                          $turnier = $plugin_language[ 'no' ];
                                          $turniervalue = 'nein';
                                        }
                                     ?>
                                        <option selected value="<?php echo $turniervalue; ?>"><?php echo $turnier; ?></option>
                                        <option value="ja"><? echo $plugin_language[ 'yes' ] ?></option>
                                        <option value="nein"><? echo $plugin_language[ 'no' ] ?></option>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td><? echo $plugin_language[ 'prize1stplace' ] ?></td>
                                <td colspan="2"><input class="form-control" name="preis1" type="text" size="20" value="<?php echo $preis1; ?>" /></td>
                            </tr>
                            <tr>
                                <td><? echo $plugin_language[ 'prize2stplace' ] ?></td>
                                <td colspan="2"><input class="form-control" name="preis2" type="text" size="20" value="<?php echo $preis2; ?>" /></td>
                            </tr>
                            <tr>
                                <td><? echo $plugin_language[ 'prize3stplace' ] ?></td>
                                <td colspan="2"><input class="form-control" name="preis3" type="text" size="20" value="<?php echo $preis3; ?>" /></td>
                            </tr>
                            <tr>
                                <td colspan="3"><br><hr><br></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="hidden" name="captcha_hash" value="'.$hash.'" />
                        <input type="hidden" name="cupID" value="'.$dc['cupID'].'" />
                        <button class="btn btn-warning" type="submit" name="add"  /><? echo $plugin_language[ 'save' ] ?></button></td>
                                <td>
                                    <button class="btn btn-danger" type="submit" name="addori"  onClick="return confirm('<? echo $plugin_language[ 'reallydefault' ] ?>');" /><? echo $plugin_language[ 'restorestandard' ] ?></button></td>
                            </tr>
                        </table>
                    </form>
</div></div>
					<?php


                    break;
                    
                    #################  Turnierbaum  #################	
                    default:
					// Viertel
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='viertel') {
						$id = $_GET['id'];
						$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE cupID='" . $id . "'");
						$dc = mysqli_fetch_array($get);
						$viertel = $dc['viertel'];
						$viertel = 1;
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET viertel='" . $viertel . "' WHERE cupID='" . $id . "'");
						echo '<div class="alert alert-success" role="alert">'.$plugin_language[ 'pleasewait' ].'</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='losev') {
						$id = $_GET['id'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET viertel='0' WHERE cupID='" . $id . "'");
						echo '<div class="alert alert-success" role="alert">'.$plugin_language[ 'pleasewait' ].'</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					// Halb
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='halb') {
						$id = $_GET['id'];
						$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE cupID='" . $id . "'");
						$dc = mysqli_fetch_array($get);
						$halb = $dc['halb'];
						$halb = 1;
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET halb='" . $halb . "' WHERE cupID='" . $id . "'");
						echo '<div class="alert alert-success" role="alert">'.$plugin_language[ 'pleasewait' ].'</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='loseh') {
						$id = $_GET['id'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET halb='0' WHERE cupID='" . $id . "'");
						echo '<div class="alert alert-success" role="alert">'.$plugin_language[ 'pleasewait' ].'</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					// Finale
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='finale') {
						$id = $_GET['id'];
						$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE cupID='" . $id . "'");
						$dc = mysqli_fetch_array($get);
						$finale = $dc['finale'];
						$finale = 1;
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET finale='" . $finale . "' WHERE cupID='" . $id . "'");
						echo '<div class="alert alert-success" role="alert">'.$plugin_language[ 'pleasewait' ].'</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='losef') {
						$id = $_GET['id'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET finale='0' WHERE cupID='" . $id . "'");
						echo '<div class="alert alert-success" role="alert">'.$plugin_language[ 'pleasewait' ].'</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					// Platz1
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='p1') {
						$id = $_GET['id'];
						$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE cupID='" . $id . "'");
						$dc = mysqli_fetch_array($get);
						$p1 = $dc['p1'];
						$p1 = 1;
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET p1='" . $p1 . "' WHERE cupID='" . $id . "'");
						echo '<div class="alert alert-success" role="alert">'.$plugin_language[ 'pleasewait' ].'</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='losep1') {
						$id = $_GET['id'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET p1='0' WHERE cupID='" . $id . "'");
						echo '<div class="alert alert-success" role="alert">'.$plugin_language[ 'pleasewait' ].'</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					// Platz3
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='p3') {
						$id = $_GET['id'];
						$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE cupID='" . $id . "'");
						$dc = mysqli_fetch_array($get);
						$p3 = $dc['p3'];
						$p3 = 1;
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET p3='" . $p3 . "' WHERE cupID='" . $id . "'");
						echo '<div class="alert alert-success" role="alert">'.$plugin_language[ 'pleasewait' ].'</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['do']) && $_REQUEST['do']=='losep3') {
						$id = $_GET['id'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET p3='0' WHERE cupID='" . $id . "'");
						echo '<div class="alert alert-success" role="alert">'.$plugin_language[ 'pleasewait' ].'</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					
					########### Ergebniseingabe  Gruppe ##############
					if(isset($_REQUEST['eg1'])) {
						$scoreg1 = $_POST['scoreg1'];
						$scoreg2 = $_POST['scoreg2'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eg='" . $scoreg1 . "' WHERE anordnung=1");
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eg='" . $scoreg2 . "' WHERE anordnung=2");
						echo '<div class="alert alert-success" role="alert">' . $plugin_language[ 'resultentered' ] . '' . $plugin_language[ 'resultentered' ] . '</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['eg2'])) {
						$scoreg1 = $_POST['scoreg1'];
						$scoreg2 = $_POST['scoreg2'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eg='" . $scoreg1 . "' WHERE anordnung=3");
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eg='" . $scoreg2 . "' WHERE anordnung=4");
						echo '<div class="alert alert-success" role="alert">' . $plugin_language[ 'resultentered' ] . '</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['eg3'])) {
						$scoreg1 = $_POST['scoreg1'];
						$scoreg2 = $_POST['scoreg2'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eg='" . $scoreg1 . "' WHERE anordnung=5");
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eg='" . $scoreg2 . "' WHERE anordnung=6");
						echo '<div class="alert alert-success" role="alert">' . $plugin_language[ 'resultentered' ] . '</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['eg4'])) {
						$scoreg1 = $_POST['scoreg1'];
						$scoreg2 = $_POST['scoreg2'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eg='" . $scoreg1 . "' WHERE anordnung=7");
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eg='" . $scoreg2 . "' WHERE anordnung=8");
						echo '<div class="alert alert-success" role="alert">' . $plugin_language[ 'resultentered' ] . '</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['eg5'])) {
						$scoreg1 = $_POST['scoreg1'];
						$scoreg2 = $_POST['scoreg2'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eg='" . $scoreg1 . "' WHERE anordnung=9");
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eg='" . $scoreg2 . "' WHERE anordnung=10");
						echo '<div class="alert alert-success" role="alert">' . $plugin_language[ 'resultentered' ] . '</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['eg6'])) {
						$scoreg1 = $_POST['scoreg1'];
						$scoreg2 = $_POST['scoreg2'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eg='" . $scoreg1 . "' WHERE anordnung=11");
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eg='" . $scoreg2 . "' WHERE anordnung=12");
						echo '<div class="alert alert-success" role="alert">' . $plugin_language[ 'resultentered' ] . '</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['eg7'])) {
						$scoreg1 = $_POST['scoreg1'];
						$scoreg2 = $_POST['scoreg2'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eg='" . $scoreg1 . "' WHERE anordnung=13");
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eg='" . $scoreg2 . "' WHERE anordnung=14");
						echo '<div class="alert alert-success" role="alert">' . $plugin_language[ 'resultentered' ] . '</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['eg8'])) {
						$scoreg1 = $_POST['scoreg1'];
						$scoreg2 = $_POST['scoreg2'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eg='" . $scoreg1 . "' WHERE anordnung=15");
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eg='" . $scoreg2 . "' WHERE anordnung=16");
						echo '<div class="alert alert-success" role="alert">' . $plugin_language[ 'resultentered' ] . '</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					
					########### Ergebniseingabe  Viertelfinale ##############
					if(isset($_REQUEST['ev1'])) {
						$scorev1 = $_POST['scorev1'];
						$scorev2 = $_POST['scorev2'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET ev='" . $scorev1 . "' WHERE viertel=1 && (anordnung=1 || anordnung=2)");
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET ev='" . $scorev2 . "' WHERE viertel=1 && (anordnung=3 || anordnung=4)");
						echo '<div class="alert alert-success" role="alert">' . $plugin_language[ 'resultentered' ] . '</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['ev2'])) {
						$scorev1 = $_POST['scorev1'];
						$scorev2 = $_POST['scorev2'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET ev='" . $scorev1 . "' WHERE viertel=1 && (anordnung=5 || anordnung=6)");
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET ev='" . $scorev2 . "' WHERE viertel=1 && (anordnung=7 || anordnung=8)");
						echo '<div class="alert alert-success" role="alert">' . $plugin_language[ 'resultentered' ] . '</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['ev3'])) {
						$scorev1 = $_POST['scorev1'];
						$scorev2 = $_POST['scorev2'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET ev='" . $scorev1 . "' WHERE viertel=1 && (anordnung=9 || anordnung=10)");
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET ev='" . $scorev2 . "' WHERE viertel=1 && (anordnung=11 || anordnung=12)");
						echo '<div class="alert alert-success" role="alert">' . $plugin_language[ 'resultentered' ] . '</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['ev4'])) {
						$scorev1 = $_POST['scorev1'];
						$scorev2 = $_POST['scorev2'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET ev='" . $scorev1 . "' WHERE viertel=1 && (anordnung=13 || anordnung=14)");
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET ev='" . $scorev2 . "' WHERE viertel=1 && (anordnung=15 || anordnung=16)");
						echo '<div class="alert alert-success" role="alert">' . $plugin_language[ 'resultentered' ] . '</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					
					########### Ergebniseingabe  Halbfinale ##############
					if(isset($_REQUEST['eh1'])) {
						$scoreh1 = $_POST['scoreh1'];
						$scoreh2 = $_POST['scoreh2'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eh='" . $scoreh1 . "' WHERE halb=1 && gruppe=1");
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eh='" . $scoreh2 . "' WHERE halb=1 && gruppe=2");
						echo '<div class="alert alert-success" role="alert">' . $plugin_language[ 'resultentered' ] . '</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					if(isset($_REQUEST['eh2'])) {
						$scoreh1 = $_POST['scoreh1'];
						$scoreh2 = $_POST['scoreh2'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eh='" . $scoreh1 . "' WHERE halb=1 && gruppe=3");
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET eh='" . $scoreh2 . "' WHERE halb=1 && gruppe=4");
						echo '<div class="alert alert-success" role="alert">' . $plugin_language[ 'resultentered' ] . '</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					
					########### Ergebniseingabe   Finale ##############
					if(isset($_REQUEST['ef1'])) {
						$scoref1 = $_POST['scoref1'];
						$scoref2 = $_POST['scoref2'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET ef='" . $scoref1 . "' WHERE finale=1 && (gruppe=1 || gruppe=2)");
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET ef='" . $scoref2 . "' WHERE finale=1 && (gruppe=3 || gruppe=4)");
						echo '<div class="alert alert-success" role="alert">' . $plugin_language[ 'resultentered' ] . '</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}
					
					########### Ergebniseingabe  Platz 3  ##############
					if(isset($_REQUEST['ep3'])) {
						$scorep31 = $_POST['scorep31'];
						$scorep32 = $_POST['scorep32'];
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET ep3='" . $scorep31 . "' WHERE finale=0 && (gruppe=1 || gruppe=2)");
						safe_query("UPDATE `" . PREFIX . "plugins_cup_teams` SET ep3='" . $scorep32 . "' WHERE finale=0 && (gruppe=3 || gruppe=4)");
						echo '<div class="alert alert-success" role="alert">' . $plugin_language[ 'resultentered' ] . '</div>';
						echo '<meta http-equiv="refresh" content="1; URL=admincenter.php?site=admin_cup">';
					}

					echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-card-list"></i> ' . $plugin_language[ 'title' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_cup">' . $plugin_language[ 'cup' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['cup_teams'].'</li>
                </ol>
            </nav>  
                        <div class="card-body">


<div class="mb-3 row row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_cup&page=teams" class="btn btn-primary">' . $plugin_language[ 'cup_teams' ] . '</a>

      <a href="admincenter.php?site=admin_cup&page=config" class="btn btn-primary">' . $plugin_language[ 'cup_config' ] . '</a>
    </div>
  </div>';
					?>


                    <div class="table-responsive">
  <table class="table borderless">
  	<style type="text/css">
        .borderless td, .borderless th {
    border: none;
}

    </style>
<thead>
                        <tr valign="middle" align="center">
                            <th><b><? echo $plugin_language[ 'preliminary_rounds' ] ?></b></th>
                            <th><b><? echo $plugin_language[ 'quarterfinals' ] ?></b></th>
                            <th><b><? echo $plugin_language[ 'semifinals' ] ?></b></th>
                            <th><b><? echo $plugin_language[ 'final' ] ?></b></th>
                            <th><b><? echo $plugin_language[ 'winner' ] ?></b></th>
                        </tr>
                        </thead>
						<tbody>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="5"><? echo $plugin_language[ 'group1' ] ?>:</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="Bbody" bgcolor="<?=$clan1_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php
							if (isset($clan_vor_1_name)) {
								echo $clan1_name;
								echo '';
							}
							elseif (isset($clan1_name)) {
								echo $clan1_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=viertel&id=".$clan1_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
							} else {
								echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
							}
							?>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center">
                                <form action="admincenter.php?site=admin_cup" method="post" name="eg1">
                                    <input name="scoreg1" type="text" size="1" maxlength="2" value="<?php echo $clan1_eg; ?>" /> <? echo $plugin_language[ 'to' ] ?> 
                                    <input name="scoreg2" type="text" size="1" maxlength="2" value="<?php echo $clan2_eg; ?>" />
                                    <button class="btn btn-success" type="submit" name="eg1" value="Submit" data-toggle="tooltip" data-placement="top" title="<?php echo  $plugin_language[ 'tooltip_4' ] ?>"><i class="bi bi-arrow-right-circle-fill"></i> <?php echo  $plugin_language[ 'result_edit' ] ?></button>
                                </form>
                            </td>
                            <td class="Bbody" bgcolor="<?=$clan_vor_1_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php
							if (isset($clan_halb_1_name)) {
								echo $clan_vor_1_name;
								echo "";
							} elseif (isset($clan_vor_1_name)) {
								echo $clan_vor_1_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=halb&id=".$clan_vor_1_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a> | <a class='btn btn-danger' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_5' ]. "' href='admincenter.php?site=admin_cup&do=losev&id=".$clan_vor_1_id."'> ".$plugin_language[ 'win_undone' ] ." <i class='bi bi-arrow-left-circle-fill'></i></a>";
							} else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
							}
							?>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="Bbody" bgcolor="<?=$clan2_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php
                            if (isset($clan_vor_1_name)) {
								echo $clan2_name;
								echo "";
                            } elseif (isset($clan2_name)) {
								echo $clan2_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=viertel&id=".$clan2_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td align="center">
                                <form action="admincenter.php?site=admin_cup" method="post" name="ev1">
                                    <input name="scorev1" type="text" size="1" maxlength="2" value="<?php echo $clan_vor_1_ev; ?>" /> <? echo $plugin_language[ 'to' ] ?> 
                                    <input name="scorev2" type="text" size="1" maxlength="2" value="<?php echo $clan_vor_2_ev; ?>" />
                                    <button class="btn btn-success" type="submit" name="ev1" value="Submit" data-toggle="tooltip" data-placement="top" title="<?php echo  $plugin_language[ 'tooltip_4' ] ?>"><i class="bi bi-arrow-right-circle-fill"></i> <?php echo  $plugin_language[ 'result_edit' ] ?></button>
                                </form>
                            </td>
                            <td class="Bbody" bgcolor="<?=$clan_halb_1_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php
                            if (isset($clan_fin_1_name)) {
								echo $clan_halb_1_name;
								echo "";
                            } elseif (isset($clan_halb_1_name)) {
								echo $clan_halb_1_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=finale&id=".$clan_halb_1_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a> | <a class='btn btn-danger' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_5' ]. "' href='admincenter.php?site=admin_cup&do=loseh&id=".$clan_halb_1_id."'> ".$plugin_language[ 'win_undone' ] ." <i class='bi bi-arrow-left-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="Bbody" bgcolor="<?=$clan3_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php
                            if (isset($clan_vor_2_name)) {
								echo $clan3_name;
								echo "";
                            } elseif (isset($clan3_name)) {
								echo $clan3_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=viertel&id=".$clan3_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-left:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center">
                                <form action="admincenter.php?site=admin_cup" method="post" name="eg2">
                                    <input name="scoreg1" type="text" size="1" maxlength="2" value="<?php echo $clan3_eg; ?>" /> <? echo $plugin_language[ 'to' ] ?> 
                                    <input name="scoreg2" type="text" size="1" maxlength="2" value="<?php echo $clan4_eg; ?>" />
                                    <button class="btn btn-success" type="submit" name="eg2" value="Submit" data-toggle="tooltip" data-placement="top" title="<?php echo  $plugin_language[ 'tooltip_4' ] ?>"><i class="bi bi-arrow-right-circle-fill"></i> <?php echo  $plugin_language[ 'result_edit' ] ?></button>
                                </form>
                            </td>
                            <td class="Bbody" bgcolor="<?=$clan_vor_2_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php
                            if (isset($clan_halb_1_name)) {
								echo $clan_vor_2_name;
								echo "";
							} elseif (isset($clan_vor_2_name)) {
								echo $clan_vor_2_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=halb&id=".$clan_vor_2_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a> | <a class='btn btn-danger' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_5' ]. "' href='admincenter.php?site=admin_cup&do=losev&id=".$clan_vor_2_id."'> ".$plugin_language[ 'win_undone' ] ." <i class='bi bi-arrow-left-circle-fill'></i></a>";
							} else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
							}
							?>
                            </td>
                            <td>&nbsp;</td>
                            <td style="border-left:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="Bbody" bgcolor="<?=$clan4_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php
                            if (isset($clan_vor_2_name)) {
								echo $clan4_name;
								echo "";
							} elseif (isset($clan4_name)) {
								echo $clan4_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=viertel&id=".$clan4_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
							} else {
								echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
							}
							?>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-left:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-left:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><? echo $plugin_language[ 'group2' ] ?>:</td>
                            <td>&nbsp;</td>
                            <td align="center">
                                <form action="admincenter.php?site=admin_cup" method="post" name="eh1">
                                    <input name="scoreh1" type="text" size="1" maxlength="2" value="<?php echo $clan_halb_1_eh; ?>" /> <? echo $plugin_language[ 'to' ] ?> 
                                    <input name="scoreh2" type="text" size="1" maxlength="2" value="<?php echo $clan_halb_2_eh; ?>" />
                                    <button class="btn btn-success" type="submit" name="eh1" value="Submit" data-toggle="tooltip" data-placement="top" title="<?php echo  $plugin_language[ 'tooltip_4' ] ?>"><i class="bi bi-arrow-right-circle-fill"></i> <?php echo  $plugin_language[ 'result_edit' ] ?></button>
                                </form>
                            </td>
                            <td class="Bbody" bgcolor="<?=$clan_fin_1_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php
							if (isset($clan_winner_name)) {
								echo $clan_fin_1_name;
								echo "";
							} elseif (isset($clan_fin_1_name)) {
								echo $clan_fin_1_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=p1&id=".$clan_fin_1_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a> | <a class='btn btn-danger' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_5' ]. "' href='admincenter.php?site=admin_cup&do=losef&id=".$clan_fin_1_id."'> ".$plugin_language[ 'win_undone' ] ." <i class='bi bi-arrow-left-circle-fill'></i></a>";
							} else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
							}
							?>
							<td>&nbsp;</td>
                            </td>
                        </tr>   
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-left:solid 1px #000;border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="Bbody" bgcolor="<?=$clan5_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php
                            if (isset($clan_vor_3_name)) {
								echo $clan5_name;
								echo "";
                            } elseif (isset($clan5_name)) {
								echo $clan5_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=viertel&id=".$clan5_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-left:solid 1px #000;border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center">
                                <form action="admincenter.php?site=admin_cup" method="post" name="eg3">
                                    <input name="scoreg1" type="text" size="1" maxlength="2" value="<?php echo $clan5_eg; ?>" /> <? echo $plugin_language[ 'to' ] ?> 
                                    <input name="scoreg2" type="text" size="1" maxlength="2" value="<?php echo $clan6_eg; ?>" />
                                    <button class="btn btn-success" type="submit" name="eg3" value="Submit" data-toggle="tooltip" data-placement="top" title="<?php echo  $plugin_language[ 'tooltip_4' ] ?>"><i class="bi bi-arrow-right-circle-fill"></i> <?php echo  $plugin_language[ 'result_edit' ] ?></button>
                                </form>
                            </td>
                            <td class="Bbody" bgcolor="<?=$clan_vor_3_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php
                            if (isset($clan_halb_2_name)) {
								echo $clan_vor_3_name;
								echo "";
                            } elseif (isset($clan_vor_3_name)) {
								echo $clan_vor_3_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=halb&id=".$clan_vor_3_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a> | <a class='btn btn-danger' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_5' ]. "' href='admincenter.php?site=admin_cup&do=losev&id=".$clan_vor_3_id."'> ".$plugin_language[ 'win_undone' ] ." <i class='bi bi-arrow-left-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td>&nbsp;</td>
                            <td style="border-left:solid 1px #000;border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="Bbody" bgcolor="<?=$clan6_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php
                            if (isset($clan_vor_3_name)) {
								echo $clan6_name;
								echo "";
                            } elseif (isset($clan6_name)) {
								echo $clan6_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=viertel&id=".$clan6_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-left:solid 1px #000;border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td align="center">
                                <form action="admincenter.php?site=admin_cup" method="post" name="ev2">
                                    <input name="scorev1" type="text" size="1" maxlength="2" value="<?php echo $clan_vor_3_ev; ?>" /> <? echo $plugin_language[ 'to' ] ?> 
                                    <input name="scorev2" type="text" size="1" maxlength="2" value="<?php echo $clan_vor_4_ev; ?>" />
                                    <button class="btn btn-success" type="submit" name="ev2" value="Submit" data-toggle="tooltip" data-placement="top" title="<?php echo  $plugin_language[ 'tooltip_4' ] ?>"><i class="bi bi-arrow-right-circle-fill"></i> <?php echo  $plugin_language[ 'result_edit' ] ?></button>
                                </form>
                            </td>
                            <td class="Bbody" bgcolor="<?=$clan_halb_2_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php
                            if (isset($clan_fin_1_name)) {
								echo $clan_halb_2_name;
								echo "";
                            } elseif (isset($clan_halb_2_name)) {
								echo $clan_halb_2_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=finale&id=".$clan_halb_2_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a> | <a class='btn btn-danger' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_5' ]. "' href='admincenter.php?site=admin_cup&do=loseh&id=".$clan_halb_2_id."'> ".$plugin_language[ 'win_undone' ] ." <i class='bi bi-arrow-left-circle-fill'></i></a>";
                            } else {
                                echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="Bbody" bgcolor="<?=$clan7_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php
                            if (isset($clan_vor_4_name)) {
								echo $clan7_name;
								echo "";
                            } elseif (isset($clan7_name)) {
								echo $clan7_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=viertel&id=".$clan7_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center">
                                <form action="admincenter.php?site=admin_cup" method="post" name="eg4">
                                    <input name="scoreg1" type="text" size="1" maxlength="2" value="<?php echo $clan7_eg; ?>" /> <? echo $plugin_language[ 'to' ] ?> 
                                    <input name="scoreg2" type="text" size="1" maxlength="2" value="<?php echo $clan8_eg; ?>" />
                                    <button class="btn btn-success" type="submit" name="eg4" value="Submit" data-toggle="tooltip" data-placement="top" title="<?php echo  $plugin_language[ 'tooltip_4' ] ?>"><i class="bi bi-arrow-right-circle-fill"></i> <?php echo  $plugin_language[ 'result_edit' ] ?></button>
                                </form>
                            </td>
                            <td class="Bbody" bgcolor="<?=$clan_vor_4_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
                            <?php
                            if (isset($clan_halb_2_name)) {
								echo $clan_vor_4_name;
								echo "";
                            } elseif (isset($clan_vor_4_name)) {
								echo $clan_vor_4_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=halb&id=".$clan_vor_4_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a> | <a class='btn btn-danger' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_5' ]. "' href='admincenter.php?site=admin_cup&do=losev&id=".$clan_vor_4_id."'> ".$plugin_language[ 'win_undone' ] ." <i class='bi bi-arrow-left-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td>&nbsp;</td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                           <td class="Bbody" bgcolor="<?=$clan8_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php
                            if (isset($clan_vor_4_name)) {
								echo $clan8_name;
								echo "";
                            } elseif (isset($clan8_name)) {
								echo $clan8_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=viertel&id=".$clan8_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><? echo $plugin_language[ 'group3' ] ?>:</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="center">
                                <form action="admincenter.php?site=admin_cup" method="post" name="ef1">
                                    <input name="scoref1" type="text" size="1" maxlength="2" value="<?php echo $clan_fin_1_ef; ?>" /> <? echo $plugin_language[ 'to' ] ?> 
                                    <input name="scoref2" type="text" size="1" maxlength="2" value="<?php echo $clan_fin_2_ef; ?>" />
                                    <button class="btn btn-success" type="submit" name="ef1" value="Submit" data-toggle="tooltip" data-placement="top" title="<?php echo  $plugin_language[ 'tooltip_4' ] ?>"><i class="bi bi-arrow-right-circle-fill"></i> <?php echo  $plugin_language[ 'result_edit' ] ?></button>
                                </form>
                            </td>
                            <td class="Bbody" bgcolor="<?=$clan_winner_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php 
                            if (isset($clan_winner_name)) {
								echo $clan_winner_name; 
								echo "  <a class='btn btn-danger' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_5' ]. "' href='admincenter.php?site=admin_cup&do=losep1&id=".$clan_winner_id."'> ".$plugin_language[ 'win_undone' ] ." <i class='bi bi-arrow-left-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                            }
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="Bbody" bgcolor="<?=$clan9_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
                            <?php
                            if (isset($clan_vor_5_name)) {
								echo $clan9_name;
								echo "";
                            } elseif (isset($clan9_name)) {
								echo $clan9_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=viertel&id=".$clan9_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center">
                                <form action="admincenter.php?site=admin_cup" method="post" name="eg5">
                                    <input name="scoreg1" type="text" size="1" maxlength="2" value="<?php echo $clan9_eg; ?>" /> <? echo $plugin_language[ 'to' ] ?> 
                                    <input name="scoreg2" type="text" size="1" maxlength="2" value="<?php echo $clan10_eg; ?>" />
                                    <button class="btn btn-success" type="submit" name="eg5" value="Submit" data-toggle="tooltip" data-placement="top" title="<?php echo  $plugin_language[ 'tooltip_4' ] ?>"><i class="bi bi-arrow-right-circle-fill"></i> <?php echo  $plugin_language[ 'result_edit' ] ?></button>
                                </form>
                            </td>
                            <td class="Bbody" bgcolor="<?=$clan_vor_5_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
                            <?php
                            if (isset($clan_halb_3_name)) {
								echo $clan_vor_5_name;
								echo "";
                            } elseif (isset($clan_vor_5_name)) {
								echo $clan_vor_5_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=halb&id=".$clan_vor_5_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a> | <a class='btn btn-danger' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_5' ]. "' href='admincenter.php?site=admin_cup&do=losev&id=".$clan_vor_5_id."'> ".$plugin_language[ 'win_undone' ] ." <i class='bi bi-arrow-left-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td>&nbsp;</td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="Bbody" bgcolor="<?=$clan10_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
                            <?php
                            if (isset($clan_vor_5_name)) {
								echo $clan10_name;
								echo "";
							} elseif (isset($clan10_name)) {
								echo $clan10_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=viertel&id=".$clan10_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td align="center">
                                <form action="admincenter.php?site=admin_cup" method="post" name="ev3">
                                    <input name="scorev1" type="text" size="1" maxlength="2" value="<?php echo $clan_vor_5_ev; ?>" /> <? echo $plugin_language[ 'to' ] ?> 
                                    <input name="scorev2" type="text" size="1" maxlength="2" value="<?php echo $clan_vor_6_ev; ?>" />
                                    <button class="btn btn-success" type="submit" name="ev3" value="Submit" data-toggle="tooltip" data-placement="top" title="<?php echo  $plugin_language[ 'tooltip_4' ] ?>"><i class="bi bi-arrow-right-circle-fill"></i> <?php echo  $plugin_language[ 'result_edit' ] ?></button>
                                </form>
                            </td>
                            <td class="Bbody" bgcolor="<?=$clan_halb_3_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
                            <?php
                            if (isset($clan_fin_2_name)) {
								echo $clan_halb_3_name;
								echo "";
                            } elseif (isset($clan_halb_3_name)) {
								echo $clan_halb_3_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=finale&id=".$clan_halb_3_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a> | <a class='btn btn-danger' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_5' ]. "' href='admincenter.php?site=admin_cup&do=loseh&id=".$clan_halb_3_id."'> ".$plugin_language[ 'win_undone' ] ." <i class='bi bi-arrow-left-circle-fill'></i></a>";
							} else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
							}
							?>
							</td>
							<td style="border-right:solid 1px #000;">&nbsp;</td>
							<td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="Bbody" bgcolor="<?=$clan11_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php
                            if (isset($clan_vor_6_name)) {
								echo $clan11_name;
								echo "";
                            } elseif (isset($clan11_name)) {
								echo $clan11_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=viertel&id=".$clan11_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-left:solid 1px #000;border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center">
                                <form action="admincenter.php?site=admin_cup" method="post" name="eg6">
                                    <input name="scoreg1" type="text" size="1" maxlength="2" value="<?php echo $clan11_eg; ?>" /> <? echo $plugin_language[ 'to' ] ?> 
                                    <input name="scoreg2" type="text" size="1" maxlength="2" value="<?php echo $clan12_eg; ?>" />
                                    <button class="btn btn-success" type="submit" name="eg6" value="Submit" data-toggle="tooltip" data-placement="top" title="<?php echo  $plugin_language[ 'tooltip_4' ] ?>"><i class="bi bi-arrow-right-circle-fill"></i> <?php echo  $plugin_language[ 'result_edit' ] ?></button>
                                </form>
                            </td>
                            <td class="Bbody" bgcolor="<?=$clan_vor_6_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
                            <?php
                            if (isset($clan_halb_3_name)) {
								echo $clan_vor_6_name;
								echo "";
                            } elseif (isset($clan_vor_6_name)){
								echo $clan_vor_6_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=halb&id=".$clan_vor_6_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a> | <a class='btn btn-danger' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_5' ]. "' href='admincenter.php?site=admin_cup&do=losev&id=".$clan_vor_6_id."'> ".$plugin_language[ 'win_undone' ] ." <i class='bi bi-arrow-left-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                            }
							?>
                            </td>
                            <td>&nbsp;</td>
                            <td style="border-left:solid 1px #000;border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="Bbody" bgcolor="<?=$clan12_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
                            <?php
                            if (isset($clan_vor_6_name)) {
								echo $clan12_name;
								echo "";
                            } elseif (isset($clan12_name)) {
								echo $clan12_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=viertel&id=".$clan12_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-left:solid 1px #000;border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-left:solid 1px #000;border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><? echo $plugin_language[ 'group4' ] ?>:</td>
                            <td>&nbsp;</td>
                            <td align="center">
                                <form action="admincenter.php?site=admin_cup" method="post" name="eh2">
                                    <input name="scoreh1" type="text" size="1" maxlength="2" value="<?php echo $clan_halb_3_eh; ?>" /> <? echo $plugin_language[ 'to' ] ?> 
                                    <input name="scoreh2" type="text" size="1" maxlength="2" value="<?php echo $clan_halb_4_eh; ?>" />
                                    <button class="btn btn-success" type="submit" name="eh2" value="Submit" data-toggle="tooltip" data-placement="top" title="<?php echo  $plugin_language[ 'tooltip_4' ] ?>"><i class="bi bi-arrow-right-circle-fill"></i> <?php echo  $plugin_language[ 'result_edit' ] ?></button>
                                </form>
                            </td>
                            <td class="Bbody" bgcolor="<?=$clan_fin_2_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
                            <?php
                            if (isset($clan_winner_name)) {
								echo $clan_fin_2_name;
								echo "";
                            } elseif (isset($clan_fin_2_name)) {
								echo $clan_fin_2_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=p1&id=".$clan_fin_2_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a> | <a class='btn btn-danger' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_5' ]. "' href='admincenter.php?site=admin_cup&do=losef&id=".$clan_fin_2_id."'> ".$plugin_language[ 'win_undone' ] ." <i class='bi bi-arrow-left-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                            }
                            ?>
                            <td>&nbsp;</td>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-left:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="Bbody" bgcolor="<?=$clan13_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
                            <?php
                            if (isset($clan_vor_7_name)) {
								echo $clan13_name;
								echo "";
                            } elseif (isset($clan13_name)) {
								echo $clan13_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=viertel&id=".$clan13_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-left:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center">
                                <form action="admincenter.php?site=admin_cup" method="post" name="eg7">
                                    <input name="scoreg1" type="text" size="1" maxlength="2" value="<?php echo $clan13_eg; ?>" /> <? echo $plugin_language[ 'to' ] ?> 
                                    <input name="scoreg2" type="text" size="1" maxlength="2" value="<?php echo $clan14_eg; ?>" />
                                    <button class="btn btn-success" type="submit" name="eg7" value="Submit" data-toggle="tooltip" data-placement="top" title="<?php echo  $plugin_language[ 'tooltip_4' ] ?>"><i class="bi bi-arrow-right-circle-fill"></i> <?php echo  $plugin_language[ 'result_edit' ] ?></button>
                                </form>
                            </td>
                            <td class="Bbody" bgcolor="<?=$clan_vor_7_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
                            <?php
                            if (isset($clan_halb_4_name)) {
								echo $clan_vor_7_name;
								echo "";
                            } elseif (isset($clan_vor_7_name)) {
								echo $clan_vor_7_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=halb&id=".$clan_vor_7_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a> | <a class='btn btn-danger' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_5' ]. "' href='admincenter.php?site=admin_cup&do=losev&id=".$clan_vor_7_id."'> ".$plugin_language[ 'win_undone' ] ." <i class='bi bi-arrow-left-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td>&nbsp;</td>
                            <td style="border-left:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="Bbody" bgcolor="<?=$clan14_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
                            <?php
                            if (isset($clan_vor_7_name)) {
								echo $clan14_name;
								echo "";
                            } elseif (isset($clan14_name)) {
								echo $clan14_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=viertel&id=".$clan14_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="border-left:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td align="center">
                                <form action="admincenter.php?site=admin_cup" method="post" name="ev4">
                                    <input name="scorev1" type="text" size="1" maxlength="2" value="<?php echo $clan_vor_7_ev; ?>" /> <? echo $plugin_language[ 'to' ] ?> 
                                    <input name="scorev2" type="text" size="1" maxlength="2" value="<?php echo $clan_vor_8_ev; ?>" />
                                    <button class="btn btn-success" type="submit" name="ev4" value="Submit" data-toggle="tooltip" data-placement="top" title="<?php echo  $plugin_language[ 'tooltip_4' ] ?>"><i class="bi bi-arrow-right-circle-fill"></i> <?php echo  $plugin_language[ 'result_edit' ] ?></button>
                                </form>
                            </td>
                            <td class="Bbody" bgcolor="<?=$clan_halb_4_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php
                            if (isset($clan_fin_2_name)) {
								echo $clan_halb_4_name;
								echo "";
                            } elseif (isset($clan_halb_4_name)) {
								echo $clan_halb_4_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=finale&id=".$clan_halb_4_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a> | <a class='btn btn-danger' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_5' ]. "' href='admincenter.php?site=admin_cup&do=loseh&id=".$clan_halb_4_id."'> ".$plugin_language[ 'win_undone' ] ." <i class='bi bi-arrow-left-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td>&nbsp;</td> 
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="Bbody" bgcolor="<?=$clan15_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
                            <?php
                            if (isset($clan_vor_8_name)) {
								echo $clan15_name;
								echo "";
                            } elseif (isset($clan15_name)) {
								echo $clan15_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=viertel&id=".$clan15_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td style="border-right:solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center">
                                <form action="admincenter.php?site=admin_cup" method="post" name="eg8">
                                    <input name="scoreg1" type="text" size="1" maxlength="2" value="<?php echo $clan15_eg; ?>" /> <? echo $plugin_language[ 'to' ] ?> 
                                    <input name="scoreg2" type="text" size="1" maxlength="2" value="<?php echo $clan16_eg; ?>" />
                                    <button class="btn btn-success" type="submit" name="eg8" value="Submit" data-toggle="tooltip" data-placement="top" title="<?php echo  $plugin_language[ 'tooltip_4' ] ?>"><i class="bi bi-arrow-right-circle-fill"></i> <?php echo  $plugin_language[ 'result_edit' ] ?></button>
                                </form>
                            </td>
                            <td class="Bbody" bgcolor="<?=$clan_vor_8_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
                            <?php
                            if (isset($clan_halb_4_name)) {
								echo $clan_vor_8_name;
								echo "";
                            } elseif (isset($clan_vor_8_name)) {
								echo $clan_vor_8_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=halb&id=".$clan_vor_8_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a> | <a class='btn btn-danger' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_5' ]. "' href='admincenter.php?site=admin_cup&do=losev&id=".$clan_vor_8_id."'> ".$plugin_language[ 'win_undone' ] ." <i class='bi bi-arrow-left-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="Bbody" bgcolor="<?=$clan16_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
                            <?php
                            if (isset($clan_vor_8_name)) {
								echo $clan16_name;
								echo "";
                            } elseif (isset($clan16_name)) {
								echo $clan16_name;
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=viertel&id=".$clan16_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                            }
                            ?>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="center" colspan="2"><? echo $plugin_language[ '3rdplacematch' ] ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="Bbody" bgcolor="<?=$clan_platz3_1_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
                            <?php 
                            if (isset($clan_platz3_winner_name)) {
								echo $clan_platz3_1_name; 
								echo "";
                            } elseif (isset($clan_fin_1_name)) {
								echo $clan_platz3_1_name; 
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=p3&id=".$clan_platz3_1_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
                            } else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                            }
							?>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="center">
                                <form action="admincenter.php?site=admin_cup" method="post" name="ep3">
                                    <input name="scorep31" type="text" size="1" maxlength="2" value="<?php echo $clan_platz3_1_ep3; ?>" /> <? echo $plugin_language[ 'to' ] ?> 
                                    <input name="scorep32" type="text" size="1" maxlength="2" value="<?php echo $clan_platz3_2_ep3; ?>" />
                                    <button class="btn btn-success" type="submit" name="ep3" value="Submit" data-toggle="tooltip" data-placement="top" title="<?php echo  $plugin_language[ 'tooltip_4' ] ?>"><i class="bi bi-arrow-right-circle-fill"></i> <?php echo  $plugin_language[ 'result_edit' ] ?></button>
                                </form>
                            </td>
                            <td class="Bbody" bgcolor="<?=$clan_platz3_winner_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php 
							if (isset($clan_platz3_winner_name)) {
								echo $clan_platz3_winner_name; 
								echo "  <a class='btn btn-danger' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_5' ]. "' href='admincenter.php?site=admin_cup&do=losep3&id=".$clan_platz3_winner_id."'> ".$plugin_language[ 'win_undone' ] ." <i class='bi bi-arrow-left-circle-fill'></i></a>";
							} else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
							}
							?>
							<td>&nbsp;</td>
							</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
							<td>&nbsp;</td>
							<td class="Bbody" bgcolor="<?=$clan_platz3_2_bg?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
							<?php 
							if (isset($clan_platz3_winner_name)) {
								echo $clan_platz3_2_name; 
								echo "";
							} elseif (isset($clan_fin_2_name)) {
								echo $clan_platz3_2_name; 
								echo "  <a class='btn btn-success' data-toggle='tooltip' data-placement='top' title='".$plugin_language[ 'tooltip_3' ]. "' href='admincenter.php?site=admin_cup&do=p3&id=".$clan_platz3_2_id."'> ".$plugin_language[ 'win_edit' ] ." <i class='bi bi-arrow-right-circle-fill'></i></a>";
							} else {
								echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
							}
							?>
							</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
                        </tr>
                        <tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
                        </tr><tbody>
                    </table></div></div>
                </div>
					<?php
                    }
				
				?>
