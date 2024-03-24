<?php
/*-----------------------------------------------------------------\
| _  _  ___  ___  ___  ___  ___  __  __    ___   __  __     |
|( \/\/ )(  _)(  ,)/ __)(  ,\(  _)(  )  (  )  (  ,) (  \/  )    |
| \  /  ) _) ) ,\\__ \ ) _/ ) _) )(__  )(__  )  \  )  (     |
|  \/\/  (___)(___/(___/(_)  (___)(____)(____)  (_)\_)(_/\/\_)    |
|             ___      ___              |
|            |__ \    / _ \               |
|             ) |    | | | |              |
|            / /     | | | |              |
|             / /_   _   | |_| |              |
|            |____| (_)   \___/               |
\___________________________________________________________________/
/                                   \
|    Copyright 2005-2018 by webspell.org / webspell.info    |
|    Copyright 2018-2019 by webspell-rm.de            |
|                                   |
|    - Script runs under the GNU GENERAL PUBLIC LICENCE     |
|    - It's NOT allowed to remove this copyright-tag      |
|    - http://www.fsf.org/licensing/licenses/gpl.html       |
|                                   |
|         Code based on WebSPELL Clanpackage          |
|         (Michael Gruber - webspell.at)          |
\___________________________________________________________________/
/                                   \
|           WEBSPELL RM Version 2.0             |
|       For Support, Mods and the Full Script visit       |
|             webspell-rm.de                |
\------------------------------------------------------------------*/
# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("cup", $plugin_path);

include('./includes/plugins/cup/cup_abfragen.php');

$title = 'Webspell-RM :: Cup';
$hmenu = 'Turnier';
$gruppenanzeige = $gruppe;    // 1=Ja 0=Nein
$registeranzeige = $register;    // 1=Ja 0=Nein
$tunieranzeige = $turnier;    // 1=Ja 0=Nein
$filepath = $plugin_path."images/team/";

if (isset($_GET[ 'action' ])) {
  $action = $_GET[ 'action' ];
} else {
  $action = '';
}

if (isset($_POST[ 'save' ])) {  
  $CAPCLASS = new \webspell\Captcha;
  if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    #$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams");
    $temp =  array();
    for($i=0;$i<16;$i++){
      $zahl = rand(1, 16); 
      while(in_array($zahl,$temp)){
        $zahl = rand(1, 16); 
      }
      $temp[] = $zahl;
    }

    #safe_query("SELECT anordnung FROM  `" . PREFIX . "plugins_cup_teams`  JOIN (SELECT (RAND(1) * (SELECT MAX( 16 ) FROM  anordnung )) AS randID) AS randTable WHERE  cupID=randTable.randID LIMIT 1");
    $temp = array();
    for($i=0;$i<4;$i++){
      $zahl1 = rand(1, 4); 
      while(in_array($zahl1,$temp)){
        $zahl1 = rand(1, 4); 
      }
      $temp[] = $zahl1;
    }

    $teamid = "0";
    $clantag = $_POST['clantag'];
    $name = $_POST['name'];
    $gruppe = $zahl1;
    $anordnung = $zahl;
    $hp = $_POST['hp'];
    safe_query("INSERT INTO `" . PREFIX . "plugins_cup_teams` VALUES ('','" . $teamid . "','" . $clantag . "','" . $name . "','" . $gruppe . "','" . $anordnung . "','" . $hp . "','','','','','','','','','','','','','')");

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
  } else {
    echo  $plugin_language[ 'transaction_invalid' ];
  }
   redirect('index.php?site=cup', $plugin_language[ 'team_add' ], 3);
}        

if ($action == 'teams') {
  if (!$userID) {
    echo generateAlert($plugin_language['no_access'], 'alert-danger');
  } else {
    echo'
      <div class="head-boxes">
        <span class="head-boxes-head">' . $plugin_language[ 'title' ] . '</span>
      <h2 class="head-h2">
        <span class="head-boxes-title">' . $plugin_language[ 'title' ] . '</span>
      </h2>
        <p class="head-boxes-foot">Cup</p>
      </div>
      <div class="card">
        <div class="card-body">
    ';
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    echo'
          <div class="col-md-12"><h2><i class="bi bi-box-arrow-in-right"></i> ' . $plugin_language[ 'add_cup' ] . '</h2></div>
            <script type="text/javascript" src="/includes/plugins/cup/js/cCore.js"></script>
            <form action="index.php?site=cup&action=teams" method="post" enctype="multipart/form-data">
              <div class="mb-3 row">
                <label class="col-sm-3 control-label">' . $plugin_language[ 'team_name' ] . ':</label>
                <div class="col-sm-8"><span class="text-muted small"><em>
                  <input class="form-control" type="text" name="name" size="97" /></em></span>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-sm-3 control-label">' . $plugin_language[ 'clantag' ] . ':</label>
                <div class="col-sm-8">
                  <span class="text-muted small"><em>
                    <input class="form-control" type="text" name="clantag" size="97" /></em>
                  </span>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-sm-3 control-label">' . $plugin_language[ 'homepage' ] . ':</label>
                <div class="col-sm-8">
                  <span class="text-muted small"><em>
                    <input class="form-control" type="text" name="hp" size="97" /></em>
                  </span>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-sm-3 control-label">'.$plugin_language['banner'].':</label>
                <div class="col-sm-8">
                  <span class="text-muted small"><em>
                    <input class="btn btn-info" name="banner" type="file" size="40" /> <small>(max. 54x30)</small></em>
                  </span>
                </div>
              </div>
              <!--<div class="mb-3 row">
                <label class="col-sm-3 control-label">' . $plugin_language[ 'gruppe' ] . ':</label>
                <div class="col-sm-8">
                  <span class="text-muted small"><em data-toggle="tooltip" data-placement="top" title="' . $plugin_language[ 'tooltip_2' ] . '">
                    <input class="form-control" type="text" name="gruppe" size="97" /> / 
                    <input class="form-control" type="text" name="anordnung" size="97" /></em>
                  </span>
                </div>
              </div>-->
              <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                  <input type="hidden" name="captcha_hash" value="'.$hash.'" />
                  <button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['add_cup'].'</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    ';
  }
} else {
  echo'
    <script type="text/javascript" src="./includes/plugins/cup/js/cCore.js"></script>
   
      <div class="head-boxes">
        <span class="head-boxes-head">'.$plugin_language['title'].'</span>
      <h2 class="head-h2">
        <span class="head-boxes-title">'.$plugin_language['title'].'</span>
      </h2>
        <p class="head-boxes-foot">Cup</p>
      </div>
    <div class="card">
      <div class="card-body">
        <!-- GRUPPENANZEIGE START -->
  ';
  if($gruppenanzeige == 'ja' ) {
      echo'
        <div class="table-responsive">
          <table class="table">
            <tr valign="middle" align="center">
              <th><b>'.$plugin_language['group1'].'</b></th>
              <th><b>'.$plugin_language['group2'].'</b></th>
              <th><b>'.$plugin_language['group3'].'</b></th>
              <th><b>'.$plugin_language['group4'].'</b></th>
            </tr>
            <tr>
              <td>
                '.display_groups('1').'
              </td>
              <td>
                '.display_groups('5').'
              </td>
              <td>
                '.display_groups('9').'
              </td>
              <td>
                '.display_groups('13').'
              </td>
            </tr>
            <tr>
              <td>
                '.display_groups('2').'
              </td>
              <td>
                '.display_groups('6').'
              </td>
              <td>
                '.display_groups('10').'
              </td>
              <td>
                '.display_groups('14').'
              </td>
            </tr>
            <tr>
              <td>
                '.display_groups('3').'
              </td>
              <td>
                '.display_groups('7').'
              </td>
              <td>
                '.display_groups('11').'
              </td>
              <td>
                '.display_groups('15').'
              </td>
            </tr>
            <tr>
              <td>
                '.display_groups('4').'
              </td>
              <td>
                '.display_groups('8').'
              </td>
              <td>
                '.display_groups('12').'
              </td>
              <td>
                '.display_groups('16').'
              </td>
            </tr>
          </table>
        </div>
        <!-- GRUPPENANZEIGE ENDE -->
      ';
  } else { 
    echo ''; 
  }

  if($registeranzeige == 'ja' && $userID) {
    echo'
        <div>
          <a href="index.php?site=cup&action=teams" class="btn btn-primary">'.$plugin_language['add_cup'].'</a><br><br>
        </div>
    ';
  }
  if($tunieranzeige == 'ja' ) {
  ?>
        <!-- TURNIERBAUM START -->
        <div class="table-responsive">
          <table class="table table-sm borderless">
            <style type="text/css">
              .borderless td, .borderless th {
                border: none;
              }
              hr.style_cup {
                margin-top: 1rem;
                margin-bottom: 1rem;
                border: 0;
                border-top: 1px solid;
                margin-left: -5px;
                margin-right: -5px;
              }
            </style>
          <thead>
            <tr>
              <th class="text-center" colspan="9" align="center" style="border-bottom:solid 1px;"><b><? echo $plugin_language['tournament_tree']?></b></th>
            </tr>
            <tr valign="middle" align="center">
              <th><b><? echo $plugin_language['preliminary_rounds']?></b></th>
              <th><b></b></th>
              <th><b><? echo $plugin_language['quarterfinals']?></b></th>
              <th><b></b></th>
              <th><b><? echo $plugin_language['semifinals']?></b></th>
              <th><b></b></th>
              <th><b><? echo $plugin_language['final']?></b></th>
              <th><b></b></th>
              <th><b><? echo $plugin_language['winner']?></b></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" class="Bbody"><h5><? echo $plugin_language['group1'] ?>:</h5></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="Bbody" bgcolor="<?php echo getanordnung('1','color');?>" style="margin-right: -2px; border:solid 1px;border-right:solid 1px;border-left:solid 1px ;">
                <?php if (getanordnung('1','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getanordnung('1','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getanordnung('1','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b><?php echo getanordnung('1','hp');?>',CAPTION,'<? echo $plugin_language[ 'preliminary_rounds' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getanordnung('1','banner');?>' style='width: 54px;height: 30px;' class='border'></a> 
                <a href='<?php echo getanordnung('1','hp');?>' target='_blank'><?php echo getanordnung('1','clantag');?></a>
                <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="center" style="padding: 10px;border-right:solid 1px;"><?php echo getanordnung('1','eg');?> &nbsp; <b>:</b> &nbsp; <?php echo getanordnung('2','eg');?></td>
              <td><hr class="style_cup"></td>
              <td class="Bbody" bgcolor="<?php echo getviertel('1','color','1','2');?>" style="border:solid 1px;border-right:solid 1px;">
                <?php if (getviertel('1','cupID','1','2')) {?>
                 <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getviertel('1','banner','1','2');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getviertel('1','name','1','2');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b><?php echo getviertel('1','hp','1','2');?>',CAPTION,'<? echo $plugin_language[ 'quarterfinals' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getviertel('1','banner','1','2');?>' style='width: 54px;height: 30px;' class='border'></a> 
                <a href='<?php echo getviertel('1','hp','1','2');?>' target='_blank'><?php echo getviertel('1','clantag','1','2');?></a>
                <?php
                } else {
                  echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="Bbody" bgcolor="<?php echo getanordnung('2','color');?>" style="border:solid 1px; border-right:solid 1px;">
                <?php if (getanordnung('2','cupID')) {?>
                  <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getanordnung('2','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getanordnung('2','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getanordnung('2','hp');?>',CAPTION,'<? echo $plugin_language[ 'preliminary_rounds' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getanordnung('2','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                  <a href='<?php echo getanordnung('2','hp');?>' target='_blank'><?php echo getanordnung('2','clantag');?></a>
                <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td align="center" style="padding: 10px;border-right:solid 1px;"><?php echo getviertel('1','ev','1','2'); ?> &nbsp; <b>:</b> &nbsp; <?php echo getviertel('1','ev','3','4'); ?></td>
              <td><hr class="style_cup"></td>
              <td class="Bbody" bgcolor="<?php echo gethalb('1','color','1','1')?>" style="border:solid 1px;border-right:solid 1px;">
                <?php if (gethalb('1','cupID','1','1')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo gethalb('1','banner','1','1')?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo gethalb('1','name','1','1');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo gethalb('1','hp','1','1'); ?>',CAPTION,'<? echo $plugin_language[ 'semifinals' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo gethalb('1','banner','1','1')?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo gethalb('1','hp','1','1')?>' target='_blank'><?php echo gethalb('1','clantag','1','1')?></a>
                <?php
                } else {
                  echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>  
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="Bbody" bgcolor="<?php echo getanordnung('3','color');?>" style="border:solid 1px; border-right:solid 1px;">
                <?php if (getanordnung('3','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getanordnung('3','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getanordnung('3','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getanordnung('3','hp');?>',CAPTION,'<? echo $plugin_language[ 'preliminary_rounds' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getanordnung('3','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getanordnung('3','hp');?>' target='_blank'><?php echo getanordnung('3','clantag');?></a>
                <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>

              <td align="center" style="padding: 10px;border-right:solid 1px;"><?php echo getanordnung('3','eg');?> &nbsp; <b>:</b> &nbsp; <?php echo getanordnung('4','eg');?></td>
              <td><hr class="style_cup"></td>
              <td class="Bbody" bgcolor="<?php echo getviertel('1','color','3','4');?>" style="border:solid 1px;border-right:solid 1px;">
                <?php if (getviertel('1','cupID','3','4')) {?>
                 <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getviertel('1','banner','3','4');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getviertel('1','name','3','4');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b><?php echo getviertel('1','hp','3','4');?>',CAPTION,'<? echo $plugin_language[ 'quarterfinals' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getviertel('1','banner','3','4');?>' style='width: 54px;height: 30px;' class='border'></a> 
                <a href='<?php echo getviertel('1','hp','3','4');?>' target='_blank'><?php echo getviertel('1','clantag','3','4');?></a>
                <?php
                } else {
                  echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="Bbody" bgcolor="<?php echo getanordnung('4','color');?>" style="border:solid 1px; border-right:solid 1px;">
                <?php if (getanordnung('4','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getanordnung('4','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getanordnung('4','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getanordnung('4','hp');?>',CAPTION,'<? echo $plugin_language[ 'preliminary_rounds' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getanordnung('4','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getanordnung('4','hp');?>' target='_blank'><?php echo getanordnung('4','clantag');?></a>
                <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" class="Bbody"><h5><? echo $plugin_language['group2'] ?>:</h5></td>
              
              
              <td align="center" style="padding: 10px;border-right:solid 1px;"><?php echo gethalb('1','eh','1','1'); ?> &nbsp; <b>:</b> &nbsp; <?php echo gethalb('1','eh','1','2'); ?></td>

              <td><hr class="style_cup"></td>
              <td class="Bbody" bgcolor="<?php echo getfinal('1','color','1','1')?>" style="border:solid 1px;">
                <?php if (getfinal('1','cupID','1','1')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getfinal('1','banner','1','1')?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getfinal('1','name','1','1');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getfinal('1','hp','1','1'); ?>',CAPTION,'Finale:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getfinal('1','banner','1','1')?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getfinal('1','hp','1','1')?>' target='_blank'><?php echo getfinal('1','clantag','1','1')?></a>
                <?php
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
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="Bbody" bgcolor="<?php echo getanordnung('5','color');?>" style="border:solid 1px; border-right:solid 1px;">
                <?php if (getanordnung('5','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getanordnung('5','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getanordnung('5','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getanordnung('5','hp');?>',CAPTION,'<? echo $plugin_language[ 'preliminary_rounds' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getanordnung('5','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getanordnung('5','hp');?>' target='_blank'><?php echo getanordnung('5','clantag');?></a>
                <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="center" style="padding: 10px;border-right:solid 1px;"><?php echo getanordnung('5','eg');?> &nbsp; <b>:</b> &nbsp; <?php echo getanordnung('6','eg');?></td>
              <td><hr class="style_cup"></td>
              <td class="Bbody" bgcolor="<?php echo getviertel('1','color','5','6');?>" style="border:solid 1px;border-right:solid 1px;">
                <?php if (getviertel('1','cupID','5','6')) {?>
                 <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getviertel('1','banner','5','6');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getviertel('1','name','5','6');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b><?php echo getviertel('1','hp','5','6');?>',CAPTION,'<? echo $plugin_language[ 'quarterfinals' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getviertel('1','banner','5','6');?>' style='width: 54px;height: 30px;' class='border'></a> 
                <a href='<?php echo getviertel('1','hp','5','6');?>' target='_blank'><?php echo getviertel('1','clantag','5','6');?></a>
                <?php
                } else {
                  echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="Bbody" bgcolor="<?php echo getanordnung('6','color');?>" style="border:solid 1px; border-right:solid 1px;">
                <?php if (getanordnung('6','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getanordnung('6','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getanordnung('6','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getanordnung('6','hp');?>',CAPTION,'<? echo $plugin_language[ 'preliminary_rounds' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getanordnung('6','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getanordnung('6','hp');?>' target='_blank'><?php echo getanordnung('6','clantag');?></a>
                <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td align="center" style="padding: 10px;border-right:solid 1px;"><?php echo getviertel('1','ev','5','6'); ?> &nbsp; <b>:</b> &nbsp; <?php echo getviertel('1','ev','7','8'); ?></td>
              <td><hr class="style_cup"></td>
              <td class="Bbody" bgcolor="<?php echo gethalb('1','color','1','2')?>" style="border:solid 1px;border-right:solid 1px;">
                <?php if (gethalb('1','cupID','1','2')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo gethalb('1','banner','1','2')?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo gethalb('1','name','1','2');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo gethalb('1','hp','1','2'); ?>',CAPTION,'<? echo $plugin_language[ 'semifinals' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo gethalb('1','banner','1','2')?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo gethalb('1','hp','1','2')?>' target='_blank'><?php echo gethalb('1','clantag','1','2')?></a>
                <?php
                } else {
                  echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td> 
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="Bbody" bgcolor="<?php echo getanordnung('7','color');?>" style="border:solid 1px; border-right:solid 1px;">  
                <?php if (getanordnung('7','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getanordnung('7','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getanordnung('7','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getanordnung('7','hp');?>',CAPTION,'<? echo $plugin_language[ 'preliminary_rounds' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getanordnung('7','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getanordnung('7','hp');?>' target='_blank'><?php echo getanordnung('7','clantag');?></a>
                <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="center" style="padding: 10px;border-right:solid 1px;"><?php echo getanordnung('7','eg');?> &nbsp; <b>:</b> &nbsp; <?php echo getanordnung('8','eg');?></td>
              <td><hr class="style_cup"></td>
              <td class="Bbody" bgcolor="<?php echo getviertel('1','color','7','8');?>" style="border:solid 1px;border-right:solid 1px;">
                <?php if (getviertel('1','cupID','7','8')) {?>
                 <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getviertel('1','banner','7','8');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getviertel('1','name','7','8');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b><?php echo getviertel('1','hp','7','8');?>',CAPTION,'<? echo $plugin_language[ 'quarterfinals' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getviertel('1','banner','7','8');?>' style='width: 54px;height: 30px;' class='border'></a> 
                <a href='<?php echo getviertel('1','hp','7','8');?>' target='_blank'><?php echo getviertel('1','clantag','7','8');?></a>
                <?php
                } else {
                  echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="Bbody" bgcolor="<?php echo getanordnung('8','color');?>" style="border:solid 1px; border-right:solid 1px;">
                <?php if (getanordnung('8','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getanordnung('8','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getanordnung('8','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getanordnung('8','hp');?>',CAPTION,'<? echo $plugin_language[ 'preliminary_rounds' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getanordnung('8','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getanordnung('8','hp');?>' target='_blank'><?php echo getanordnung('8','clantag');?></a>
                <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="Bbody"><h5><? echo $plugin_language['group3'] ?>:</h5></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td align="center" style="padding: 10px;border-right:solid 1px;"><?php echo getfinal('1','ef','1','2'); ?> &nbsp; <b>:</b> &nbsp; <?php echo getfinal('1','ef','3','4'); ?></td>
              <td><hr class="style_cup"></td>
              <td class="Bbody" bgcolor="<?php echo getwinner('1','color');?>" style="border:solid 1px; border-right:solid 1px;">
                <?php if (getwinner('1','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/icons/admin/medal_g.png width=16px height=16px border=0px><img src=/includes/plugins/cup/images/team/<?php echo getwinner('1','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getwinner('1','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getwinner('1','hp');?><br><b><? echo $plugin_language[ 'position' ] ?>:</b> <?php echo getwinner('1','preis1');?><?php echo $preis1; ?>',CAPTION,'<? echo $plugin_language[ 'winner' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/icons/admin/medal_g.png' width=16px height=16px border=0px><img src='/includes/plugins/cup/images/team/<?php echo getwinner('1','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getwinner('1','hp');?>' target='_blank'><?php echo getwinner('1','clantag');?></a>
                <?php
                } else {
                   echo "<div style=\"margin-top:6px\"><img src='/includes/plugins/cup/images/icons/admin/medal_g.png' width=16px height=16px border=0px><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                }
                ?>
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td class="Bbody" bgcolor="<?php echo getplace_2('1','color');?>" style="border:solid 1px;">
                <?php if (getplace_2('1','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/icons/admin/medal_s.png width=16px height=16px border=0px><img src=/includes/plugins/cup/images/team/<?php echo getplace_2('1','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getplace_2('1','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getplace_2('1','hp');?><br><b><? echo $plugin_language[ 'position' ] ?>:</b> <?php echo $preis2; ?>',CAPTION,'<? echo $plugin_language[ 'place2' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/icons/admin/medal_s.png' width=16px height=16px border=0px><img src='/includes/plugins/cup/images/team/<?php echo getplace_2('1','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getplace_2('1','hp');?>' target='_blank'><?php echo getplace_2('1','clantag');?></a>
                <?php
                } else {
                   echo "<div style=\"margin-top:6px\"><img src='/includes/plugins/cup/images/icons/admin/medal_s.png' width=16px height=16px border=0px><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                }
                ?>
              </td>
            </tr>
            <tr>
              <td class="Bbody" bgcolor="<?php echo getanordnung('9','color');?>" style="border:solid 1px; border-right:solid 1px;">
                <?php if (getanordnung('9','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getanordnung('9','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getanordnung('9','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getanordnung('9','hp');?>',CAPTION,'<? echo $plugin_language[ 'preliminary_rounds' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getanordnung('9','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getanordnung('9','hp');?>' target='_blank'><?php echo getanordnung('9','clantag');?></a>
                <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td class="Bbody" bgcolor="<?php echo getplace_3('1','color');?>" style="border:solid 1px;">
                <?php if (getplace_3('1','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/icons/admin/medal_b.png width=16px height=16px border=0px><img src=/includes/plugins/cup/images/team/<?php echo getplace_3('1','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getplace_3('1','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getplace_3('1','hp');?><br><b><? echo $plugin_language[ 'position' ] ?>:</b> <?php echo $preis3; ?>',CAPTION,'<? echo $plugin_language[ 'place3' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/icons/admin/medal_b.png' width=16px height=16px border=0px><img src='/includes/plugins/cup/images/team/<?php echo getplace_3('1','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getplace_2('1','hp');?>' target='_blank'><?php echo getplace_3('1','clantag');?></a>
                <?php
                } else {
                   echo "<div style=\"margin-top:6px\"><img src='/includes/plugins/cup/images/icons/admin/medal_b.png' width=16px height=16px border=0px><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                }
                ?>
              </td>
            </tr>
            <tr>
              <td align="center" style="padding: 10px;border-right:solid 1px;"><?php echo getanordnung('9','eg');?> &nbsp; <b>:</b> &nbsp; <?php echo getanordnung('10','eg');?></td>
              <td><hr class="style_cup"></td>
              <td class="Bbody" bgcolor="<?php echo getviertel('1','color','9','10');?>" style="border:solid 1px;border-right:solid 1px;">
                <?php if (getviertel('1','color','9','10')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getviertel('1','banner','9','10')?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getviertel('1','name','9','10');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getviertel('1','hp','9','10'); ?>',CAPTION,'<? echo $plugin_language[ 'quarterfinals' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getviertel('1','banner','9','10')?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getviertel('1','hp','9','10')?>' target='_blank'><?php echo getviertel('1','clantag','9','10')?></a>
                <?php
                } else {
                  echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>

            </tr>
            <tr>
              <td class="Bbody" bgcolor="<?php echo getanordnung('10','color');?>" style="border:solid 1px; border-right:solid 1px;">
                <?php if (getanordnung('10','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getanordnung('10','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getanordnung('10','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getanordnung('10','hp');?>',CAPTION,'<? echo $plugin_language[ 'preliminary_rounds' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getanordnung('10','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getanordnung('10','hp');?>' target='_blank'><?php echo getanordnung('10','clantag');?></a>
                <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td align="center" style="padding: 10px;border-right:solid 1px;"><?php echo getviertel('1','ev','9','10'); ?> &nbsp; <b>:</b> &nbsp; <?php echo getviertel('1','ev','11','12'); ?></td>
              <td><hr class="style_cup"></td>
              <td class="Bbody" bgcolor="<?php echo gethalb('1','color','1','3')?>" style="border:solid 1px;border-right:solid 1px;">
                <?php if (gethalb('1','cupID','1','3')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo gethalb('1','banner','1','3')?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo gethalb('1','name','1','3');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo gethalb('1','hp','1','3'); ?>',CAPTION,'<? echo $plugin_language[ 'semifinals' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo gethalb('1','banner','1','3')?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo gethalb('1','hp','1','3')?>' target='_blank'><?php echo gethalb('1','clantag','1','3')?></a>
                <?php
                } else {
                  echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="Bbody" bgcolor="<?php echo getanordnung('11','color');?>" style="border:solid 1px; border-right:solid 1px;">
                <?php if (getanordnung('11','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getanordnung('11','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getanordnung('11','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getanordnung('11','hp');?>',CAPTION,'<? echo $plugin_language[ 'preliminary_rounds' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getanordnung('11','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getanordnung('11','hp');?>' target='_blank'><?php echo getanordnung('11','clantag');?></a>
                <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="center" style="padding: 10px;border-right:solid 1px;"><?php echo getanordnung('11','eg');?> &nbsp; <b>:</b> &nbsp; <?php echo getanordnung('12','eg');?></td>
              <td><hr class="style_cup"></td>
              <td class="Bbody" bgcolor="<?php echo getviertel('1','color','11','12');?>" style="border:solid 1px;border-right:solid 1px;">
                <?php if (getviertel('1','color','11','12')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getviertel('1','banner','11','12')?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getviertel('1','name','11','12');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getviertel('1','hp','11','12'); ?>',CAPTION,'<? echo $plugin_language[ 'quarterfinals' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getviertel('1','banner','11','12')?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getviertel('1','hp','11','12')?>' target='_blank'><?php echo getviertel('1','clantag','11','12')?></a>
                <?php
                } else {
                  echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="Bbody" bgcolor="<?php echo getanordnung('12','color');?>" style="border:solid 1px; border-right:solid 1px;">
                <?php if (getanordnung('12','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getanordnung('12','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getanordnung('12','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getanordnung('12','hp');?>',CAPTION,'<? echo $plugin_language[ 'preliminary_rounds' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getanordnung('12','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getanordnung('12','hp');?>' target='_blank'><?php echo getanordnung('12','clantag');?></a>
                <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" class="Bbody"><h5><? echo $plugin_language['group4'] ?>:</h5></td>
              <td align="center" style="padding: 10px;border-right:solid 1px;"><?php echo gethalb('1','eh','1','3'); ?> &nbsp; <b>:</b> &nbsp; <?php echo gethalb('1','eh','1','4'); ?></td>
              <td><hr class="style_cup"></td>
              <td class="Bbody" bgcolor="<?php echo getfinal('1','color','3','4')?>" style="border:solid 1px;">
                <?php if (getfinal('1','cupID','3','4')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getfinal('1','banner','3','4')?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getfinal('1','name','3','4');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getfinal('1','hp','3','4'); ?>',CAPTION,'Finale:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getfinal('1','banner','3','4')?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getfinal('1','hp','3','4')?>' target='_blank'><?php echo getfinal('1','clantag','3','4')?></a>
                <?php
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
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="Bbody" bgcolor="<?php echo getanordnung('13','color');?>" style="border:solid 1px; border-right:solid 1px;">
                <?php if (getanordnung('13','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getanordnung('13','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getanordnung('13','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getanordnung('13','hp');?>',CAPTION,'<? echo $plugin_language[ 'preliminary_rounds' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getanordnung('13','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getanordnung('13','hp');?>' target='_blank'><?php echo getanordnung('13','clantag');?></a>
                <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="center" style="padding: 10px;border-right:solid 1px;"><?php echo getanordnung('13','eg');?> &nbsp; <b>:</b> &nbsp; <?php echo getanordnung('14','eg');?></td>
              <td><hr class="style_cup"></td>
              <td class="Bbody" bgcolor="<?php echo getviertel('1','color','13','14')?>" style="border:solid 1px;border-right:solid 1px;">
                <?php if (getviertel('1','clantag','13','14')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getviertel('1','banner','13','14')?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getviertel('1','name','13','14');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getviertel('1','hp','13','14'); ?>',CAPTION,'<? echo $plugin_language[ 'quarterfinals' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getviertel('1','banner','13','14')?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getviertel('1','hp','13','14')?>' target='_blank'><?php echo getviertel('1','clantag','13','14')?></a>
                <?php
                } else {
                  echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="Bbody" bgcolor="<?php echo getanordnung('14','color');?>" style="border:solid 1px; border-right:solid 1px;">
                <?php if (getanordnung('14','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getanordnung('14','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getanordnung('14','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getanordnung('14','hp');?>',CAPTION,'<? echo $plugin_language[ 'preliminary_rounds' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getanordnung('14','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getanordnung('14','hp');?>' target='_blank'><?php echo getanordnung('14','clantag');?></a>
                <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td align="center" style="padding: 10px;border-right:solid 1px;"><?php echo getviertel('1','ev','13','14'); ?> &nbsp; <b>:</b> &nbsp; <?php echo getviertel('1','ev','15','16'); ?></td>
              <td><hr class="style_cup"></td>
              <td class="Bbody" bgcolor="<?php echo gethalb('1','color','1','4')?>" style="border:solid 1px;border-right:solid 1px;">
                <?php if (gethalb('1','cupID','1','4')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo gethalb('1','banner','1','4')?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo gethalb('1','name','1','4');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo gethalb('1','hp','1','4'); ?>',CAPTION,'<? echo $plugin_language[ 'semifinals' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo gethalb('1','banner','1','4')?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo gethalb('1','hp','1','4')?>' target='_blank'><?php echo gethalb('1','clantag','1','4')?></a>
                <?php
                } else {
                  echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td> 
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="Bbody" bgcolor="<?php echo getanordnung('15','color');?>" style="border:solid 1px; border-right:solid 1px;">
                <?php if (getanordnung('15','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getanordnung('15','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getanordnung('15','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getanordnung('15','hp');?>',CAPTION,'<? echo $plugin_language[ 'preliminary_rounds' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getanordnung('15','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getanordnung('15','hp');?>' target='_blank'><?php echo getanordnung('15','clantag');?></a>
                <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td style="border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="center" style="padding: 10px;border-right:solid 1px;"><?php echo getanordnung('15','eg');?> &nbsp; <b>:</b> &nbsp; <?php echo getanordnung('16','eg');?></td>
              <td><hr class="style_cup"></td>
              <td class="Bbody" bgcolor="<?php echo getviertel('1','color','15','16')?>" style="border:solid 1px;border-right:solid 1px;">
                <?php if (getviertel('1','cupID','15','16')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getviertel('1','banner','15','16')?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getviertel('1','name','15','16');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getviertel('1','hp','15','16'); ?>',CAPTION,'<? echo $plugin_language[ 'quarterfinals' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getviertel('1','banner','15','16')?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getviertel('1','hp','15','16')?>' target='_blank'><?php echo getviertel('1','clantag','15','16')?></a>
                <?php
                } else {
                  echo "<div style=\"margin-top:6px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>

              <td class="Bbody" bgcolor="<?php echo getanordnung('16','color');?>" style="border:solid 1px; border-right:solid 1px;">
                <?php if (getanordnung('16','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getanordnung('16','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getanordnung('16','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getanordnung('16','hp');?>',CAPTION,'<? echo $plugin_language[ 'preliminary_rounds' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getanordnung('16','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getanordnung('16','hp');?>' target='_blank'><?php echo getanordnung('16','clantag');?></a>
                <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>".$plugin_language[ 'free_space' ]."</span></div>";
                }
                ?>
              </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              
              <td align="center" colspan="3" style="border-left:solid 1px;border-top:solid 1px;border-right:solid 1px;"><b><? echo $plugin_language[ '3rdplacematch' ] ?></b></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td class="Bbody" bgcolor="<?php echo getplace3('1','color','1','2')?>" style="border:solid 1px;border-right:solid 1px;">
              <?php if (getplace3('1','cupID','1','2')) {?>
              <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getplace3('1','banner','1','2')?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getplace3('1','name','1','2');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getplace3('1','hp','1','2'); ?>',CAPTION,'<? echo $plugin_language[ '3rdplacematch' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getplace3('1','banner','1','2')?>' style='width: 54px;height: 30px;' class='border'></a>
              <a href='<?php echo getplace3('1','hp','1','2')?>' target='_blank'><?php echo getplace3('1','clantag','1','2')?></a>
              <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
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
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td align="center" style="padding: 10px;border-right:solid 1px;"><?php echo getplace3('1','ep3','1','2'); ?> &nbsp; <b>:</b> &nbsp; <?php echo getplace3('1','ep3','3','4'); ?></td>
              <td><hr class="style_cup"></td>
              <td class="Bbody" bgcolor="<?php echo getplace_3('1','color');?>" style="border:solid 1px;">
                <?php if (getplace_3('1','cupID')) {?>
                <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/icons/admin/medal_b.png width=16px height=16px border=0px><img src=/includes/plugins/cup/images/team/<?php echo getplace_3('1','banner');?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getplace_3('1','name');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getplace_3('1','hp');?><br><b><? echo $plugin_language[ 'position' ] ?>:</b> <?php echo $preis3; ?>',CAPTION,'<? echo $plugin_language[ 'place3' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/icons/admin/medal_b.png' width=16px height=16px border=0px><img src='/includes/plugins/cup/images/team/<?php echo getplace_3('1','banner');?>' style='width: 54px;height: 30px;' class='border'></a>
                <a href='<?php echo getplace_2('1','hp');?>' target='_blank'><?php echo getplace_3('1','clantag');?></a>
                <?php
                } else {
                   echo "<div style=\"margin-top:6px\"><img src='/includes/plugins/cup/images/icons/admin/medal_b.png' width=16px height=16px border=0px><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
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
              <td class="Bbody" bgcolor="<?php echo getplace3('1','color','3','4')?>" style="border:solid 1px;border-right:solid 1px;">
              <?php if (getplace3('1','cupID','3','4')) {?>
              <a href="javascript:void(0)" onmouseover="return coolTip('<img src=/includes/plugins/cup/images/team/<?php echo getplace3('1','banner','3','4')?> width=54 height=30><br><b><? echo $plugin_language[ 'team_name' ] ?>:</b> <?php echo getplace3('1','name','3','4');?><br><b><? echo $plugin_language[ 'homepage' ] ?>:</b> <?php echo getplace3('1','hp','3','4'); ?>',CAPTION,'<? echo $plugin_language[ '3rdplacematch' ] ?>:',CAPCOLOR,'#ff0000',BGCOLOR,'#000000',FGCOLOR,'#f7f7f7')" onmouseout="nd()"><img src='/includes/plugins/cup/images/team/<?php echo getplace3('1','banner','3','4')?>' style='width: 54px;height: 30px;' class='border'></a>
              <a href='<?php echo getplace3('1','hp','3','4')?>' target='_blank'><?php echo getplace3('1','clantag','3','4')?></a>
              <?php
                } else {
                  echo "<div style=\"padding: 4px;margin-top:2px\"><span>". $plugin_language[ 'game_is_still_pending' ]."</span></div>";
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
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td align="center" colspan="3" style="border-left:solid 1px;border-bottom:solid 1px;border-right:solid 1px;">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </tbody>
        </table>
      </div>
<?php
  }
  echo'
      </div>
    </div>
    <!-- TURNIERBAUM ENDE -->
  ';
}
?>