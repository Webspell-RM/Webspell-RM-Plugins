
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script type="text/javascript">
//when document is ready
$(function(){
  
  //add row
  $('a#addRow').on('click',function(){
    
    var newTr=$('table#rows tr:last').clone();//create clone
    newTr.addClass("new-row");//add class new-tr as You wanted
    $('table#rows tbody').append(newTr);//append clone

  });

  //remove last row button
  $('a#removeRow').on("click",function(){

     if ($('table#rows tbody tr').length>1)//be sure that is more then one
     $('table#rows tbody').find("tr:last").remove();//remove last tr
  });

  //remove current row ( X in row )
  $('table#rows tbody').on("click",function(e){

     if ($(e.target).hasClass('removeCurrentRow') && $('table#rows tbody tr').length>1)//if this is a element and we have more then 1 rows
     $(e.target).parent().parent().remove();//go to parent - parent (tr) and remove         

  });

});

</script>

<?php
/*-----------------------------------------------------------------\
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
\------------------------------------------------------------------*/

$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("clanwars", $plugin_path);

$title = $plugin_language[ 'title' ]; #sc_datei Info

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='clanwars'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}
 
$filepath = $plugin_path."images/";
$filescreenpath = $plugin_path."images/clanwar-screens/";
 
if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}



$ani = '<optgroup label="Attention Seekers">
          <option value="bounce">bounce</option>
          <option value="flash">flash</option>
          <option value="pulse">pulse</option>
          <option value="rubberBand">rubberBand</option>
          <option value="shake">shake</option>
          <option value="swing">swing</option>
          <option value="tada">tada</option>
          <option value="wobble">wobble</option>
          <option value="jello">jello</option>
          <option value="heartBeat">heartBeat</option>
        </optgroup>

        <optgroup label="Bouncing Entrances">
          <option value="bounceIn">bounceIn</option>
          <option value="bounceInDown">bounceInDown</option>
          <option value="bounceInLeft">bounceInLeft</option>
          <option value="bounceInRight">bounceInRight</option>
          <option value="bounceInUp">bounceInUp</option>
        </optgroup>

        <optgroup label="Bouncing Exits">
          <option value="bounceOut">bounceOut</option>
          <option value="bounceOutDown">bounceOutDown</option>
          <option value="bounceOutLeft">bounceOutLeft</option>
          <option value="bounceOutRight">bounceOutRight</option>
          <option value="bounceOutUp">bounceOutUp</option>
        </optgroup>

        <optgroup label="Fading Entrances">
          <option value="fadeIn">fadeIn</option>
          <option value="fadeInDown">fadeInDown</option>
          <option value="fadeInDownBig">fadeInDownBig</option>
          <option value="fadeInLeft">fadeInLeft</option>
          <option value="fadeInLeftBig">fadeInLeftBig</option>
          <option value="fadeInRight">fadeInRight</option>
          <option value="fadeInRightBig">fadeInRightBig</option>
          <option value="fadeInUp">fadeInUp</option>
          <option value="fadeInUpBig">fadeInUpBig</option>
        </optgroup>

        <optgroup label="Fading Exits">
          <option value="fadeOut">fadeOut</option>
          <option value="fadeOutDown">fadeOutDown</option>
          <option value="fadeOutDownBig">fadeOutDownBig</option>
          <option value="fadeOutLeft">fadeOutLeft</option>
          <option value="fadeOutLeftBig">fadeOutLeftBig</option>
          <option value="fadeOutRight">fadeOutRight</option>
          <option value="fadeOutRightBig">fadeOutRightBig</option>
          <option value="fadeOutUp">fadeOutUp</option>
          <option value="fadeOutUpBig">fadeOutUpBig</option>
        </optgroup>

        <optgroup label="Flippers">
          <option value="flip">flip</option>
          <option value="flipInX">flipInX</option>
          <option value="flipInY">flipInY</option>
          <option value="flipOutX">flipOutX</option>
          <option value="flipOutY">flipOutY</option>
        </optgroup>

        <optgroup label="Lightspeed">
          <option value="lightSpeedIn">lightSpeedIn</option>
          <option value="lightSpeedOut">lightSpeedOut</option>
        </optgroup>

        <optgroup label="Rotating Entrances">
          <option value="rotateIn">rotateIn</option>
          <option value="rotateInDownLeft">rotateInDownLeft</option>
          <option value="rotateInDownRight">rotateInDownRight</option>
          <option value="rotateInUpLeft">rotateInUpLeft</option>
          <option value="rotateInUpRight">rotateInUpRight</option>
        </optgroup>

        <optgroup label="Rotating Exits">
          <option value="rotateOut">rotateOut</option>
          <option value="rotateOutDownLeft">rotateOutDownLeft</option>
          <option value="rotateOutDownRight">rotateOutDownRight</option>
          <option value="rotateOutUpLeft">rotateOutUpLeft</option>
          <option value="rotateOutUpRight">rotateOutUpRight</option>
        </optgroup>

        <optgroup label="Sliding Entrances">
          <option value="slideInUp">slideInUp</option>
          <option value="slideInDown">slideInDown</option>
          <option value="slideInLeft">slideInLeft</option>
          <option value="slideInRight">slideInRight</option>

        </optgroup>
        <optgroup label="Sliding Exits">
          <option value="slideOutUp">slideOutUp</option>
          <option value="slideOutDown">slideOutDown</option>
          <option value="slideOutLeft">slideOutLeft</option>
          <option value="slideOutRight">slideOutRight</option>
          
        </optgroup>
        
        <optgroup label="Zoom Entrances">
          <option value="zoomIn">zoomIn</option>
          <option value="zoomInDown">zoomInDown</option>
          <option value="zoomInLeft">zoomInLeft</option>
          <option value="zoomInRight">zoomInRight</option>
          <option value="zoomInUp">zoomInUp</option>
        </optgroup>
        
        <optgroup label="Zoom Exits">
          <option value="zoomOut">zoomOut</option>
          <option value="zoomOutDown">zoomOutDown</option>
          <option value="zoomOutLeft">zoomOutLeft</option>
          <option value="zoomOutRight">zoomOutRight</option>
          <option value="zoomOutUp">zoomOutUp</option>
        </optgroup>

        <optgroup label="Specials">
          <option value="hinge">hinge</option>
          <option value="jackInTheBox">jackInTheBox</option>
          <option value="rollIn">rollIn</option>
          <option value="rollOut">rollOut</option>
        </optgroup>';


#================================== screen Anfang ===============================

if (isset($_POST[ 'submit' ])) {
    $_language->readModule('formvalidation', true);

    $screen = new \webspell\HttpUpload('screen');

    if ($screen->hasFile()) {
        if ($screen->hasError() === false) {
            $file = $_POST[ "cwID" ] . '_' . time() . "." .$screen->getExtension();
            $new_name = $filescreenpath . $file;
            if ($screen->saveAs($new_name)) {
                @chmod($new_name, $new_chmod);
                $ergebnis = safe_query("SELECT screens FROM " . PREFIX . "plugins_clanwars WHERE cwID='" . $_POST[ "cwID" ]."'");
                $dx = mysqli_fetch_array($ergebnis);
                $screens = explode('|', $dx[ 'screens' ]);
                $screens[ ] = $file;
                $screens_string = implode('|', $screens);

                $ergebnis = safe_query(
                    "UPDATE
                    " . PREFIX . "plugins_clanwars
                    SET
                        screens='" . $screens_string . "'
                    
                WHERE `cwID` = '" . $_POST[ "cwID" ] . "'"
                );
            }
        }
    }
    header("Location: admincenter.php?site=admin_clanwars&action=edit&cwID=" . $_POST[ "cwID" ] . "");



#================================== screen Ende ===============================



#================================== screen Anfang ===============================
} elseif (isset($_POST[ 'subadd' ])) {
    $_language->readModule('formvalidation', true);

    $screen = new \webspell\HttpUpload('screen');


    if ($screen->hasFile()) {
        if ($screen->hasError() === false) {
            $file = $_POST[ "cwID" ] . '_' . time() . "." .$screen->getExtension();
            $new_name = $filescreenpath . $file;
            if ($screen->saveAs($new_name)) {
                @chmod($new_name, $new_chmod);
                $ergebnis = safe_query("SELECT screens FROM " . PREFIX . "plugins_clanwars WHERE cwID='" . $_POST[ "cwID" ]."'");
                $ds = mysqli_fetch_array($ergebnis);
                $screens = explode('|', $ds[ 'screens' ]);
                $screens[ ] = $file;
                $screens_string = implode('|', $screens);
$CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {

        safe_query(
        "INSERT INTO
            " . PREFIX . "plugins_clanwars (
                screens,
                date,
                poster
            )
            VALUES (
            '" . $screens_string . "',
                '" . time() . "',
                '" . $userID . "'
                )"
    );
    $cwID = mysqli_insert_id($_database);



            }
        }
   }
}
    header("Location: admincenter.php?site=admin_clanwars&action=edit&cwID=" . $cwID . "");



#================================== screen Ende ===============================
 
} elseif ($action == "add") {

  $hometeam = "";

  $ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_clanwars"
        )
    );
  @$ani_title = str_replace('value="' . $ds['ani_title'] . '"', 'value="' . $ds['ani_title'] . '" selected="selected"', $ani);

  $squads = getgamesquads();

  $gamesquads = safe_query("SELECT * FROM " . PREFIX . "plugins_squads WHERE gamesquad='1'");
        while ($ds = mysqli_fetch_array($gamesquads)) {
            $hometeam .= '<option value="0">' . $ds[ 'name' ] . '</option>';
            $squadmembers =
                safe_query("SELECT * FROM " . PREFIX . "plugins_squads_members WHERE squadID='$ds[squadID]'");
            while ($dm = mysqli_fetch_array($squadmembers)) {
                $hometeam .= '<option value="' . $dm[ 'userID' ] . '">&nbsp; - ' . getnickname($dm[ 'userID' ]) .
                    '</option>';
            }
            $hometeam .= '<option value="0" disabled="disabled">-----</option>';
        }

        $gamesa=safe_query("SELECT tag, name FROM ".PREFIX."plugins_games_pic ORDER BY name");
        while($dv=mysqli_fetch_array($gamesa)) {
            @$games.='<option value="'.$dv['tag'].'">'.$dv['name'].'</option>';
        }

   
  $opperg = safe_query("SELECT DISTINCT (opponent) FROM " . PREFIX . "plugins_clanwars");
  $opps = '<option value="0" selected="selected">' . $plugin_language[ 'select' ] .'</option>';
  while($ds2 = mysqli_fetch_array($opperg)) {
    $opps .= '<option value="'. $ds2['opponent'] .'">' . $ds2['opponent'] . '</option>';
  }
  $oppss=str_replace('selected="selected"', "", $opps);
  $oppss=str_replace('value="'.$opps.'"', 'value="'.$opps.'" selected="selected"', $opps);
  if($oppss!=="") $oppselect='<select name="oppsi" class="form-control" onchange="GetOpponentSelect(this.value) , GetOppHPSelect(this.value) , GetOppleagueSelect(this.value) , GetOppleaguehpSelect(this.value) , GetOppTagSelect(this.value);">'.$opps.'</select>';
  else $oppselect='<select name="squad" class="form-control" onchange="GetOpponentSelect(this.value) , GetOppHPSelect(this.value) , GetOppleagueSelect(this.value) , GetOppleaguehpSelect(this.value) , GetOppTagSelect(this.value);">'.$opps.'</select>';

  echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-stars"></i> ' . $plugin_language[ 'clanwars' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_clanwars">' . $plugin_language[ 'clanwars' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_clanwar' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';

$CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();


echo'
<script>
    <!--
    function chkFormular() {
        if (!validbbcode(document.getElementById("message").value)) {

            return false;
        }
        squad_box = document.getElementById("squad");
        if (squad_box.options.length) {
            if (squad_box.options[squad_box.selectedIndex].value == "") {

                return false;
            }
        }
        else {

            return false;
        }
    }
    -->
</script>';

echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_clanwars" enctype="multipart/form-data" onsubmit="return chkFormular();">
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['home_team'].':</label>
    <div class="col-sm-8">
      <select name="squad" class="form-select">'.$squads.'</select>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['game'].':</label>
    <div class="col-sm-8"><select name="game" class="form-select">'.$games.'</select>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['opponent_team'].':</label>
    <div class="col-sm-8">
      '.$oppselect.'
    </div>
  </div>


  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['date'].':</label>
    <div class="col-sm-8">
      <input name="date" type="date" placeholder="yyyy-mm-dd" class="form-control" required="required">
    </div>
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['time'].':</label>
    <div class="col-sm-8">
      <input name="time" type="time" placeholder="hh:mm" class="form-control" required="required">
    </div>
  </div>



  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['opponent_upload_info'].':</label>
    <div class="col-sm-3">
      <input name="opplogo" class="btn btn-info" type="file" id="imgInp" size="40" /> 
      <small>(' . $plugin_language[ 'opponent_upload_info' ] . ')</small>
    </div>
    <div class="col-sm-2">
      <img id="img-upload" src="../includes/plugins/clanwars/images/no-image.jpg" width="150px" height="150px"/>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['opponent_team'].':</label>
    <div class="col-sm-8">
      <div id="opponentselect">
        <span class="text-muted small">
          <input class="form-control" id="opponentselect" type="text" name="opponent" size="60" maxlength="255" value="" required="required"/>
        </span>
      </div>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['opponent_tag'].':</label>
    <div class="col-sm-8">
      <div id="opptagselect">
        <span class="text-muted small">
          <input class="form-control" id="opptagselect" type="text" name="opptag" size="60" maxlength="255" value="" required="required"/>
        </span>
      </div>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['opponent_homepage'].':</label>
    <div class="col-sm-8">
      <div id="opphpselect">
        <input class="form-control" id="opphpselect" type="text" name="opphp" size="60" maxlength="255" />
      </div>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['league'].':</label>
    <div class="col-sm-8">
      <div id="oppleagueselect"> 
        <input class="form-control" id="oppleagueselect" type="text" name="league" size="60" maxlength="255" />
      </div>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['league_link'].':</label>
    <div class="col-sm-8">
      <div id="oppleaguehpselect"> 
        <input class="form-control" id="oppleaguehpselect" type="text" name="linkpage" size="60" maxlength="255" />
      </div>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['league_match_link'].':</label>
    <div class="col-sm-8">
      <div id="oppleaguehpselect"> 
        <input class="form-control" id="oppleaguehpselect" type="text" name="leaguehp" size="60" maxlength="255" />
      </div>
    </div>
  </div>

  

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['server'].':</label>
    <div class="col-sm-8">
      <div id="oppleaguehpselect"> 
        <input class="form-control" id="oppleaguehpselect" type="text" name="server" size="60" maxlength="255" />
      </div>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['hltv'].':</label>
    <div class="col-sm-8">
      <div id="oppleaguehpselect"> 
        <input class="form-control" id="oppleaguehpselect" type="text" name="hltv" size="60" maxlength="255" />
      </div>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['home_team'].':</label>
    <div class="col-sm-8">      <select name="hometeam[]" multiple="multiple" class="form-control">
                    '.$hometeam.'
                </select>
    </div>
    
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['opponent_team'].':</label>
    <div class="col-sm-8">      <input name="oppteam" type="text" class="form-control">
    </div>
    
  </div>



<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['result'].':</label>
    <div class="col-sm-8">
   <table class="table" id="maplist">
                    <tr>
                        <th width="15%">'.$plugin_language['map'].' #'.$plugin_language['id'].':</th>
                        <th width="25%">'.$plugin_language['map'].':</th>
                        <th width="20%">'.$plugin_language['score_home'].':</th>
                        <th width="20%">'.$plugin_language['score_opponent'].':</th>
                        <th width="25%">';?>

                        <a id="addRow" href='' onclick='return false;'><span class="badge text-bg-success" style="font-size: 20px"> + </span></a>
                        <a id="removeRow" href='' onclick='return false;'><span class="badge text-bg-danger" style="font-size: 20px"> - </span></a>
                        <?php echo'</th>
                    </tr>
                </table>

<table id="rows">
<tbody>
        <tr>
            <td width="15%">
                <input class="form-control" type="hidden" name="map_id[]">'.$plugin_language['map'].' #'.$plugin_language['id'].'
            </td>
            <td width="25%">
                <input class="form-control" type="text" name="map_name[]" size="35">
            </td>
            <td width="20%">
                <input class="form-control" type="text" name="map_result_home[]" size="3">
            </td>
            <td width="20%">
                <input class="form-control" type="text" name="map_result_opp[]" size="3">
            </td>
            <td width="25%">
                
            </td></tr>
    </tbody>
</table>

</div>
</div>                   
 
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-8">
      <textarea class="mceNoEditor form-control" id="report" rows="5" cols="" name="report" style="width: 100%;"></textarea>
    </div>
  </div>

  <div class="mb-3 row">
    <label for="ani_title" class="col-lg-2 control-label">'.$plugin_language['title-ani'].':</label>
    <div class="col-lg-3">
      <select id="ani_title" name="ani_title" class="form-control">'.$ani_title.'</select>
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
        <button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['add_clanwar'].'</button>
    </div>
  </div>
</form>
</div></div>';
} elseif ($action == "edit") {

   $cwID = $_GET[ 'cwID' ];

  echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-stars"></i> ' . $plugin_language[ 'clanwars' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_clanwars">' . $plugin_language[ 'clanwars' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_clanwar' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';


$ds = mysqli_fetch_array(
        safe_query(
            "SELECT * FROM " . PREFIX . "plugins_clanwars WHERE cwID='" . intval($_GET['cwID']) ."'"
        )
    );
    if (!empty($ds[ 'opplogo' ])) {
        $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath . $ds[ 'opplogo' ] . '" alt="">';
    } else {
        $pic = $plugin_language[ 'no_upload' ];
    }
 
    if ($ds[ 'displayed' ] == 1) {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />';
    } else {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" />';
    }

    $gamesa=safe_query("SELECT tag, name FROM ".PREFIX."plugins_games_pic ORDER BY name");
        while($dv=mysqli_fetch_array($gamesa)) {
            @$games.='<option value="'.$dv['tag'].'">'.$dv['name'].'</option>';
        }

    $games=str_replace('value="'.$ds['game'].'"', 'value="'.$ds['game'].'" selected="selected"', $games);

    $date = date("Y-m-d", $ds[ 'date' ]);
    $time = getformattime($ds[ 'time' ]);

    $ani_title = str_replace('value="' . $ds['ani_title'] . '"', 'value="' . $ds['ani_title'] . '" selected="selected"', $ani); 
      
    $squads = getgamesquads();

    $theHomeScore = $ds[ 'homescore' ];
    $theOppScore = $ds[ 'oppscore' ];

    $maps = "";
    
        // map-output, v1.0
        $map = unserialize($ds[ 'maps' ]);
        $theHomeScore = unserialize($ds[ 'homescore' ]);
        $theOppScore = unserialize($ds[ 'oppscore' ]);
        $i = 0;
        $counter = count($map);
        for ($i = 0; $i < $counter; $i++) {
            $maps .= '<tr>
            <td width="15%">
                <input class="form-control" type="hidden" name="map_id[]" value="' . $i . '">' . $plugin_language[ 'map'].' #' . ($i + 1) . '
            </td>
            <td width="25%">
                <input class="form-control" type="text" name="map_name[]" value="' . getinput($map[ $i ]) . '" size="35">
            </td>
            <td width="20%">
                <input class="form-control" type="text" name="map_result_home[]" value="' . $theHomeScore[ $i ] . '" size="3">
            </td>
            <td width="20%">
                <input class="form-control" type="text" name="map_result_opp[]" value="' . $theOppScore[ $i ] . '" size="3">
            </td>
            <td width="25%">
                <input class="form-check-input" type="checkbox" name="delete[' . $i . ']" value="1"> ' . $plugin_language[ 'delete' ] . '
            </td></tr>';
        }

        $hometeam = "";

        $gamesquads = safe_query("SELECT * FROM `" . PREFIX . "plugins_squads` WHERE `gamesquad` = '1'");
        while ($dq = mysqli_fetch_array($gamesquads)) {
            $hometeam .= '<option value="0">' . $dq[ 'name' ] . '</option>';
            $squadmembers = safe_query(
                "SELECT
                    *
                FROM
                    `" . PREFIX . "plugins_squads_members`
                WHERE
                    `squadID` = '$dq[squadID]'"
            );
            while ($dm = mysqli_fetch_array($squadmembers)) {
                $hometeam .= '<option value="' . $dm[ 'userID' ] . '">&nbsp; - ' . getnickname($dm[ 'userID' ]) .
                    '</option>';
            }
            $hometeam .= '<option value="0">&nbsp;</option>';
        }

        if (!empty($ds[ 'hometeam' ])) {
            $array = unserialize($ds[ 'hometeam' ]);
            foreach ($array as $id) {
                if (!empty($id)) {
                    $hometeam =
                        str_replace('value="' . $id . '"', 'value="' . $id . '" selected="selected"', $hometeam);
                }
            }
        }

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    echo '<script>
    <!--
    function chkFormular() {
        if (!validbbcode(document.getElementById("message").value)) {

            return false;
        }
        squad_box = document.getElementById("squad");
        if (squad_box.options.length) {
            if (squad_box.options[squad_box.selectedIndex].value === "") {

                return false;
            }
        } else {

            return false;
        }
    }
    -->
</script>

<form class="form-horizontal" method="post" action="admincenter.php?site=admin_clanwars" enctype="multipart/form-data" onsubmit="return chkFormular();">
<input type="hidden" name="cwID" value="' . $ds['cwID'] . '" />

<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['home_team'].':</label>
    <div class="col-sm-10"><select name="squad" class="form-select">'.$squads.'</select>
    </div>
  </div>


  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['game'].':</label>
    <div class="col-sm-10"><select name="game" class="form-select">'.$games.'</select>
    </div>
  </div>

<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['date'].':</label>
    <div class="col-sm-10">
    <input name="date" type="date" value="'.$date.'" placeholder="yyyy-mm-dd" class="form-control">
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['time'].':</label>
    <div class="col-sm-10">
    <input name="time" type="time" value="'.$time.'" placeholder="hh:mm" class="form-control">
    </div>
  </div>



  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['opponent_upload_info'].':</label>
    <div class="col-sm-4">
      <input name="opplogo" class="btn btn-info" type="file" id="imgInp" size="40" /> 
      <small>(' . $plugin_language[ 'opponent_upload_info' ] . ')</small>
    </div>
    <div class="col-sm-2">
      '.$pic.'
    </div>
  </div>

 
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['opponent_tag'].':</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="opptag" size="60" maxlength="255" value="' . getinput($ds[ 'opptag' ]) . '" />
    </div>
    
  </div>
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['opponent_team'].':</label>
    <div class="col-sm-10">      <input class="form-control" type="text" name="opponent" size="60" maxlength="255" value="' . getinput($ds[ 'opponent' ]) . '" />
    </div>
    
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['home_team'].':</label>
    <div class="col-sm-10">      <select name="hometeam[]" multiple="multiple" class="form-control">
                    '.$hometeam.'
                </select>
    </div>
    
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['opponent_team'].':</label>
    <div class="col-sm-10">      <input class="form-control" type="text" name="oppteam" size="60" maxlength="255" value="' . getinput($ds[ 'oppteam' ]) . '" />
    </div>
    
  </div>
 
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['opponent_homepage'].':</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="opphp" size="60" value="' . getinput($ds[ 'opphp' ]) . '" />
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['league'].':</label>
    <div class="col-sm-10">      <input class="form-control" type="text" name="league" size="60" maxlength="255" value="' . getinput($ds[ 'league' ]) . '" />
    </div>
    
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['league_link'].':</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="linkpage" size="60" value="' . getinput($ds[ 'linkpage' ]) . '" />
    </div>
  </div>
 
<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['league_match_link'].':</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="leaguehp" size="60" value="' . getinput($ds[ 'leaguehp' ]) . '" />
    </div>
  </div>

  

<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['server'].':</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="server" size="60" value="' . getinput($ds[ 'server' ]) . '" />
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['hltv'].':</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="hltv" size="60" value="' . getinput($ds[ 'hltv' ]) . '" />
    </div>
  </div>
  


<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['result'].':</label>
    <div class="col-sm-10">

    <br> 

    <table class="table" id="maplist">
                                <tr>
                                    <td width="15%">' . $plugin_language[ 'map'].' #' . $plugin_language[ 'id'].':</td>
                                    <td width="25%">' . $plugin_language[ 'map'].':</td>
                                    <td width="20%">'.$plugin_language['score_home'].':</td>
                                    <td width="20%">'.$plugin_language['score_opponent'].':</td>
                                    <td width="25%">';?>

                                    <a id="addRow" href='' onclick='return false;'><span class="badge text-bg-success" style="font-size: 20px"> + </span></a>
                                    <a id="removeRow" href='' onclick='return false;'><span class="badge text-bg-danger" style="font-size: 20px"> - </span></a>
                                    <?php echo'</td>
                                </tr>
                            </table>
                            <table id="rows">
                            <tbody>
                                    '.$maps.'
                                </tbody>
                            </table>
 </div> 
 </div>                            

<div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['description'].':</label>
    <div class="col-sm-10">
      <textarea class="mceNoEditor form-control" id="report" rows="5" cols="" name="report" style="width: 100%;">'. getinput($ds[ 'report' ]) .'</textarea>
    </div>
  </div>


    <div class="mb-3 row">
        <label for="ani_title" class="col-lg-2 control-label">'.$plugin_language['title-ani'].':</label>

        <div class="col-lg-3">
            <select id="ani_title" name="ani_title" class="form-control">'.$ani_title.'</select>
        </div>
    </div>

    


<!-- ================================ screen Anfang======================================================== -->


<div class="mb-3 row row">
    <label class="col-sm-2 control-label">'.$plugin_language['banner'].':</label>
    <div class="col-sm-2"><span class="text-muted small"><em>
      <input class="btn btn-info" type="file" id="imgInp" name="screen"> <small>(max. 1000x500)</small>
        </em></span>
        
    </div><div class="col-sm-1"></div>
        <div class="col-sm-2">
      <img id="img-upload" src="../includes/plugins/clanwars/images/clanwar-screens/no_screenshots.jpg" height="50px"/>
    </div>

    <div class="col-sm-2"><input class="btn btn-success" type="submit" name="submit" value="' . $plugin_language[ 'upload' ] . '"></div>
  </div>
';
            

    $ergebnis = safe_query("SELECT screens FROM " . PREFIX . "plugins_clanwars WHERE cwID='" . $cwID."'");
    $db = mysqli_fetch_array($ergebnis);
    $screens = array();
    if (!empty($db[ 'screens' ])) {
        $screens = explode("|", $db[ 'screens' ]);
    }
    if (is_array($screens)) {
        foreach ($screens as $screen) {
            if ($screen != "") {

echo'

<div class="mb-3 row row">
<label class="col-sm-2 control-label">'.$plugin_language['banner'].':</label>
    <div class="col-sm-1">
    <a href="../' . $filescreenpath . $screen . '" target="_blank"><img class="img-fluid" style="height="150px" src="../' . $filescreenpath . $screen . '" alt="" /></a>
</div><div class="col-sm-8"><!--' . $screen . '<br>-->
<input class="form-control" type="text" name="pic" size="50"
                value="../' . $filescreenpath . $screen . '">
 </div><div class="col-sm-1">
                <!--<input class="btn btn-success" type="button" onclick="AddCodeFromWindow(\'[img]' . $filescreenpath . $db[ 'screens' ] . '[/img] \')"
                    value="' . $plugin_language[ 'add_to_message' ] . '">-->

                

                <input class="btn btn-danger" type="button" onclick="MM_confirm(
                        \'' . $plugin_language[ 'screen_delete' ] . '\',
                        \'admincenter.php?site=admin_clanwars&amp;action=picdelete&amp;cwID=' . $cwID . '&amp;file=' . basename($screen) . '\'
                    )" value="' . $plugin_language[ 'delete' ] . '">

                    
        </div>
    </div>


<hr>
';
            }
        }
    }

echo '


<!-- =============================  screen Ende =========================================================== -->

<div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'is_displayed' ] . ':</label>
  <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
  ' . $displayed . '
    </div>
  </div>
<div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-warning" type="submit" name="saveedit"  />'.$plugin_language['edit'].'</button>
    </div>
  </div>
</form>
</div></div>';


} elseif (isset($_POST[ "save" ])) {
  
    if (isset($_POST[ 'hometeam' ])) {
        $hometeam = $_POST[ 'hometeam' ];
    } else {
        $hometeam = array();
    }
    if (isset($_POST[ 'squad' ])) {
        $squad = $_POST[ 'squad' ];
    } else {
        $squad = '';
    }

   $squad = $_POST[ "squad" ];
   $game = $_POST[ "game" ];
    $date = strtotime($_POST['date']);
    $time = strtotime($_POST['time']);
    $opptag = $_POST[ "opptag" ];
    $opponent = $_POST[ "opponent" ];
    $opphp = $_POST[ "opphp" ];
    $oppteam = $_POST[ 'oppteam' ];
    $league = $_POST[ "league" ];
    $leaguehp = $_POST[ "leaguehp" ];
    @$linkpage = $_POST[ "linkpage" ];
    $server = $_POST[ 'server' ];
    $hltv = $_POST[ 'hltv' ];
    $ani_title = $_POST[ "ani_title" ];
    $report = $_POST[ "report" ];


    
    if (isset($_POST[ 'displayed' ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }
    if (!$displayed) {
        $displayed = 0;
    }


    // v1.0 -- EXTENDED CLANWAR RESULTS
    if (isset($_POST[ 'map_name' ])) {
        $maplist = $_POST[ 'map_name' ];
    }
    if (isset($_POST[ 'map_result_home' ])) {
        $homescr = $_POST[ 'map_result_home' ];
    }
    if (isset($_POST[ 'map_result_opp' ])) {
        $oppscr = $_POST[ 'map_result_opp' ];
    }

    $maps = array();
    if (!empty($maplist)) {
        if (is_array($maplist)) {
            foreach ($maplist as $map) {
                $maps[ ] = stripslashes($map);
            }
        }
    }
    $backup_theMaps = serialize($maps);
    $theMaps = $_database->escape_string($backup_theMaps);

    $scores = array();
    if (!empty($homescr)) {
        if (is_array($homescr)) {
            foreach ($homescr as $result) {
                $scores[ ] = $result;
            }
        }
    }
    $theHomeScore = serialize($scores);

    $results = array();
    if (!empty($oppscr)) {
        if (is_array($oppscr)) {
            foreach ($oppscr as $result) {
                $results[ ] = $result;
            }
        }
    }
    $theOppScore = serialize($results);

    $team = array();
    if (is_array($hometeam)) {
        foreach ($hometeam as $player) {
            if (!in_array($player, $team)) {
                $team[ ] = $player;
            }
        }
    }
    $home_string = serialize($team);
 
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
    safe_query("INSERT INTO `".PREFIX."plugins_clanwars` (
      squad,
      date,
      time,
      game,
      opptag,
      opponent,
      opphp,
      maps,
      hometeam,
      oppteam,
      league,
      leaguehp,
      linkpage,
      server,
      hltv,
      homescore,
      oppscore,
      ani_title,
      report, 
      displayed) values (
      '".$squad."',
      '" . $date . "',
      '" . $time . "',
      '" . $game . "',
      '".$opptag."',
      '".$opponent."',
      '".$opphp."',
      '" . $theMaps . "',
      '". $home_string . "',
      '". $oppteam . "',
      '".$league."',
      '".$leaguehp."',
      '".$linkpage."',
      '". $server . "',
      '". $hltv . "',
      '". $theHomeScore . "',
      '". $theOppScore . "',
      '".$ani_title."',
      '".$report."', 
      '".intval($displayed)."')");
               
        $id = mysqli_insert_id($_database);
 
        $errors = array();
 
        $upload = new \webspell\HttpUpload('opplogo');
        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
 
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation =  getimagesize($upload->getTempFile());
 
                    if (is_array($imageInformation)) {
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
 
                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_clanwars SET opplogo='" . $file . "' WHERE cwID='" . $id . "'"
                            );
                        }
                    } else {
                        $errors[] = $plugin_language[ 'broken_image' ];
                    }
                } else {
                    $errors[] = $plugin_language[ 'unsupported_image_type' ];
                }
            } else {
                $errors[] = $upload->translateError();
            }
        }
        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_clanwars", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ "saveedit" ])) {

if (isset($_POST[ 'hometeam' ])) {
        $hometeam = $_POST[ 'hometeam' ];
    } else {
        $hometeam = array();
    }

    $squad = $_POST[ "squad" ];
    $date = strtotime($_POST['date']);
    $time = strtotime($_POST['time']);
    $game = $_POST[ "game" ];
    $opptag = $_POST[ "opptag" ];
    $opponent = $_POST[ "opponent" ];
    $opphp = $_POST[ "opphp" ];
    $oppteam = $_POST[ 'oppteam' ];
    $league = $_POST[ "league" ];
    $leaguehp = $_POST[ "leaguehp" ];
    $linkpage = $_POST[ "linkpage" ];
    $server = $_POST[ 'server' ];
    $hltv = $_POST[ 'hltv' ];
    @$maplist = $_POST[ 'map_name' ];
    @$homescr = $_POST[ 'map_result_home' ];
    @$oppscr = $_POST[ 'map_result_opp' ];
    $ani_title = $_POST[ "ani_title" ];
    $report = $_POST[ "report" ];
    if (isset($_POST[ 'delete' ])) {
        $delete = $_POST[ 'delete' ];
    } else {
        $delete = array();
    }
    
    if (isset($_POST[ "displayed" ])) {
        $displayed = 1;
    } else {
        $displayed = 0;
    }




// v1.0 -- MAP-REMOVAL
    $theMaps = array();
    $theHomeScore = array();
    $theOppScore = array();

    if (is_array($maplist)) {
        foreach ($maplist as $key => $map) {
            if (!isset($delete[ $key ])) {
                $theMaps[ ] = stripslashes($map);
                $theHomeScore[ ] = $homescr[ $key ];
                $theOppScore[ ] = $oppscr[ $key ];
            }
        }
    }
    $theMaps = $_database->escape_string(serialize($theMaps));

    $theHomeScore = serialize($theHomeScore);
    $theOppScore = serialize($theOppScore);

    $team = array();
    if (is_array($hometeam)) {
        foreach ($hometeam as $player) {
            if (!in_array($player, $team)) {
                $team[ ] = $player;
            }
        }
    }
    $home_string = serialize($team);

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE " . PREFIX . "plugins_clanwars SET 
            squad = '" . $squad . "',
            date = '" . $date . "',
            time = '" . $time . "',
            game = '" . $game . "',
            opptag = '" . $opptag . "',
            opponent = '" . $opponent . "',
            opphp = '" . $opphp . "',
            oppteam = '" . $oppteam . "',
            hometeam = '". $home_string . "',
            maps = '" . $theMaps . "',
            league = '" . $league . "',
            leaguehp = '" . $leaguehp . "',
            linkpage = '" . $linkpage . "',
            server = '" . $server . "',
            hltv = '" . $hltv . "',
            homescore = '" . $theHomeScore . "',
            oppscore = '" . $theOppScore . "',
            ani_title = '" . $ani_title . "',
            report = '" . $report ."', 
            displayed = '" . $displayed . "' WHERE cwID='" .
            $_POST[ "cwID" ] . "'"
        );
 
        $id = $_POST[ 'cwID' ];
 
        $errors = array();
 
        $upload = new \webspell\HttpUpload('opplogo');
        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
 
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation =  getimagesize($upload->getTempFile());
 
                    if (is_array($imageInformation)) {
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
 
                        if ($upload->saveAs($filepath.$file, true)) {
                            @chmod($file, $new_chmod);
                            safe_query(
                                "UPDATE " . PREFIX . "plugins_clanwars SET opplogo='" . $file . "' WHERE cwID='" . $id . "'"
                            );
                        }
                    } else {
                        $errors[] = $plugin_language[ 'broken_image' ];
                    }
                } else {
                    $errors[] = $plugin_language[ 'unsupported_image_type' ];
                }
            } else {
                $errors[] = $upload->translateError();
            }
        }
        if (count($errors)) {
            $errors = array_unique($errors);
            echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
        } else {
            redirect("admincenter.php?site=admin_clanwars", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_GET[ "delete" ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_clanwars WHERE cwID='" . $_GET[ "cwID" ] . "'");
        $data = mysqli_fetch_assoc($get);
 
        if (safe_query("DELETE FROM " . PREFIX . "plugins_clanwars WHERE cwID='" . $_GET[ "cwID" ] . "'")) {
            @unlink($filepath.$data['carousel_pic']);
            redirect("admincenter.php?site=admin_clanwars", "", 0);
        } else {
            redirect("admincenter.php?site=admin_clanwars", "", 0);
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif ($action == "picdelete") {

    $file = basename($_GET[ 'file' ]);
    if (file_exists($filescreenpath . $file)) {
        @unlink($filescreenpath . $file);
    }

    $ergebnis = safe_query("SELECT screens FROM " . PREFIX . "plugins_clanwars WHERE cwID=" . $_GET[ "cwID" ]."");
    $db = mysqli_fetch_array($ergebnis);
    
    $screens = explode("|", $db[ 'screens' ]);
    foreach ($screens as $pic) {
        if ($pic != $file) {
            $newscreens[ ] = $pic;
        }
    }
    if (is_array($newscreens)) {
        $newscreens_string = implode("|", $newscreens);
    }

    safe_query("UPDATE " . PREFIX . "plugins_clanwars SET screens='".$newscreens_string."' WHERE cwID=" . $_GET[ "cwID" ]."");
       
    header("Location: admincenter.php?site=admin_clanwars&action=edit&cwID=" . $_GET[ "cwID" ]."");
    
} else {

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-stars"></i> ' . $plugin_language[ 'clanwars' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_clanwars">' . $plugin_language[ 'clanwars' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="mb-3 row row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_clanwars&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_clanwar' ] . '</a>
    </div>
  </div>';


 
    echo '<form method="post" action="admincenter.php?site=admin_clanwars">
    <table id="plugini" class="table table-striped table-bordered">
    <thead>
      <th><b>'.$plugin_language['game'].'</b></th>
      <th><b>'.$plugin_language['date'].'</b></th>
      <th><b>'.$plugin_language['time'].'</b></th>
      <th><b>'.$plugin_language['opponent_team'].'</b></th>
      <th><b>'.$plugin_language['opponent_logo'].'</b></th>
      <th><b>'.$plugin_language['result'].'</b></th>
      <th class="hidden-xs hidden-sm"><b>'.$plugin_language['is_displayed'].'</b></th>
      <th><b>'.$plugin_language['actions'].'</b></th>
      
    </thead>';

   $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
 
    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_clanwars");
    $anz = mysqli_num_rows($qry);
    if ($anz) {
        $i = 1;
        while ($ds = mysqli_fetch_array($qry)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }

            $scoreHome = unserialize($ds[ 'homescore' ]);
            $scoreOpp = unserialize($ds[ 'oppscore' ]);
            $homescr = array_sum($scoreHome);
            $oppscr = array_sum($scoreOpp);

            $date = getformatdate($ds[ 'date' ]);
            $time = getformattime($ds[ 'time' ]);
 
            $ds[ 'displayed' ] == 1 ?
            $displayed = '<font color="green"><b>' . $plugin_language[ 'yes' ] . '</b></font>' :
            $displayed = '<font color="red"><b>' . $plugin_language[ 'no' ] . '</b></font>';
           
            if (stristr($ds[ 'opphp' ], 'http://')) {
                $opponent = '<a href="' . getinput($ds[ 'opphp' ]) . '" target="_blank">' . getinput($ds[ 'opponent' ]) . '</a>';
            } else {
                $opponent = '<a href="http://' . getinput($ds[ 'opphp' ]) . '" target="_blank">' . getinput($ds[ 'opponent' ]) .
                '</a>';
            }

            $opponent = $ds[ 'opponent' ];
            
            if ($homescr > $oppscr) {
                $results = '<p class="text-success">' . $homescr . ':' . $oppscr . '</p>';
            } elseif ($homescr < $oppscr) {
                $results = '<p class="text-danger">' . $homescr . ':' . $oppscr . '</p>';
            } else {
                $results = '<p class="text-warning">' . $homescr . ':' . $oppscr . '</p>';
            }
    
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($opponent);
            $opponent = $translate->getTextByLanguage($opponent);
            
            if($ds[ 'opplogo' ] == '') {
              $opppic = '<img class="img-thumbnail" style="width: 100px;" align="center" src="../includes/plugins/clanwars/images/no-image.jpg" alt="{img}" />';
            } else {
              $opppic = '<img class="img-thumbnail" style="width: 100px;" align="center" src="../includes/plugins/clanwars/images/'.$ds[ 'opplogo' ] . '" alt="{img}" />';
            }

        /*    $filepath = "../images/games/";
        #$gameicon = ''.$filepath.'' . $ds[ 'game' ] . '';

        if(file_exists('../includes/plugins/clanwars/images/games/'.$ds['game'].'.jpg')){
            $gameicon='../includes/plugins/clanwars/images/'.$ds['game'].'.jpg';
        } elseif(file_exists('/images/games/'.$ds['game'].'.jpeg')){
            $gameicon='../includes/plugins/clanwars/images/'.$ds['game'].'.jpeg';
        } elseif(file_exists('/images/games/'.$ds['game'].'.png')){
            $gameicon='../includes/plugins/clanwars/images/'.$ds['game'].'.png';
        } elseif(file_exists('/images/games/'.$ds['game'].'.gif')){
            $gameicon='../includes/plugins/clanwars/images/'.$ds['game'].'.gif';
        } else{
           $gameicon='../includes/plugins/clanwars/images/no-image.jpg';
        }*/

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

        print_r($ds['game']);

            echo '<tr>
           <td class="' . $td . '">'. $gameicon .'</td>
           <td class="' . $td . '">'. $date .'</td>
           <td class="' . $td . '">'. $time .'</td>
           <td class="' . $td . '">'. $opponent .'</td>
           <td class="' . $td . '">'. $opppic .'</td>
           <td class="' . $td . '">'. $results .'</td>
           <td class="' . $td . '">'. $displayed .'</td>
           <td class="' . $td . '"><a href="admincenter.php?site=admin_clanwars&amp;action=edit&amp;cwID=' . $ds[ 'cwID' ] .
                '" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <input class="btn btn-danger" type="button" onclick="MM_confirm(\'' . $plugin_language['really_delete'] . '\', \'admincenter.php?site=admin_clanwars&amp;delete=true&amp;cwID=' . $ds[ 'cwID' ] .
                    '&amp;captcha_hash=' . $hash . '\')" value="' . $plugin_language['delete'] . '" />

      
     </td>

</tr>';
            $i++;
        }
    } else {
        echo '<tr><td class="td1" colspan="8">' . $plugin_language[ 'no_entries' ] . '</td></tr>';
    }
 
    echo '
</table>
</form></div></div>';
}

    ?>
