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
$plugin_language = $pm->plugin_language("socialmedia", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='socialmedia'");
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

#if ($action == "social_setting") {
#==== Social Einstellungen=============#




if (isset($_POST[ 'facebook_save' ])) {
    
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_facebook");
        $ds = mysqli_fetch_array($ergebnis);
        
        if(@$_POST['radio1']=="fb1_activ") {
            $fb1_activ = 1;
            $fb2_activ = 0;
            $fb3_activ = 0;
            $fb4_activ = 0;
        } elseif(@$_POST['radio1']=="fb2_activ") {
            $fb1_activ = 0;
            $fb2_activ = 1;
            $fb3_activ = 0;
            $fb4_activ = 0;
        } elseif(@$_POST['radio1']=="fb3_activ") {
            $fb1_activ = 0;
            $fb2_activ = 0;
            $fb3_activ = 1;
            $fb4_activ = 0; 
        } elseif(@$_POST['radio1']=="fb4_activ") {
            $fb1_activ = 0;
            $fb2_activ = 0;
            $fb3_activ = 0;
            $fb4_activ = 1;    
        } else {
            $fb1_activ = 0;
            $fb2_activ = 0;
            $fb3_activ = 0;
            $fb4_activ = 0;
        }
        
    
        safe_query(
            "UPDATE
                `" . PREFIX . "plugins_facebook`
            SET
                `fb1_activ` = '" . $fb1_activ . "',
                `fb2_activ` = '" . $fb2_activ . "',
                `fb3_activ` = '" . $fb3_activ . "',
                `fb4_activ` = '" . $fb4_activ . "'
            "
        );
        $id = mysqli_insert_id($_database);

        redirect("admincenter.php?site=admin_socialmedia", '', 2);
}

if ($action == "") {




    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_facebook");
    $ds = mysqli_fetch_array($settings);

if ($ds[ 'fb1_activ' ] == '1') {
        $fb1_activ = '<input id="fb1_activ" type="radio" name="radio1" value="fb1_activ" checked="checked" />';
    } else {
        $fb1_activ = '<input id="fb1_activ" type="radio" name="radio1" value="fb1_activ">';
    }

    if ($ds[ 'fb2_activ' ] == '1') {
        $fb2_activ = '<input id="fb2_activ" type="radio" name="radio1" value="fb2_activ" checked="checked" />';
    } else {
        $fb2_activ = '<input id="fb2_activ" type="radio" name="radio1" value="fb2_activ">';
    }

    if ($ds[ 'fb3_activ' ] == '1') {
        $fb3_activ = '<input id="fb3_activ" type="radio" name="radio1" value="fb3_activ" checked="checked" />';
    } else {
        $fb3_activ = '<input id="fb3_activ" type="radio" name="radio1" value="fb3_activ">';
    }

    if ($ds[ 'fb4_activ' ] == '1') {
        $fb4_activ = '<input id="fb4_activ" type="radio" name="radio1" value="fb4_activ" checked="checked" />';
    } else {
        $fb4_activ = '<input id="fb4_activ" type="radio" name="radio1" value="fb4_activ">';
    }


echo'<div class="card">
            <div class="card-header"> <i class="bi bi-facebook"></i> ' . $plugin_language[ 'social_media' ] . '</div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
  <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'facebook' ] . '</li>
    <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
  </ol>
</nav>  

           
     <div class="card-body">

      <div class="form-group row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      
      <a href="admincenter.php?site=settings&action=social_setting" class="btn btn-primary" data-toggle="tooltip" data-html="true" title="'.$plugin_language['tooltip_1'].'">' . $plugin_language[ 'social_media_setting' ] . '</a>
    </div>
  </div>
  <br>

    <form method="post" action="admincenter.php?site=admin_socialmedia&action=facebook_setting" class="form-horizontal">

    
    <div class="row">
    <div class="col-md-3">
    <label for="fb1_activ">'.$plugin_language['fb1_activ'].'</label>
    '.$fb1_activ.'<br>
    <img class="img-thumbnail" style="width: 100%; max-width: 250px" align="center" src="/includes/plugins/socialmedia/images/fb1.png" alt="{img}" />
    </div>


    <div class="col-md-3">
    <label for="fb2_activ">'.$plugin_language['fb2_activ'].'</label>
    '.$fb2_activ.'<br>
    <img class="img-thumbnail" style="width: 100%; max-width: 250px" align="center" src="/includes/plugins/socialmedia/images/fb2.png" alt="{img}" />
    </div>
    

    
    <div class="col-md-3">
    <label for="fb3_activ">'.$plugin_language['fb3_activ'].'</label>
    '.$fb3_activ.'<br>
    <img class="img-thumbnail" style="width: 100%; max-width: 250px" align="center" src="/includes/plugins/socialmedia/images/fb3.png" alt="{img}" />
    </div>


    <div class="col-md-3">
    <label for="fb4_activ">'.$plugin_language['fb4_activ'].'</label>
    '.$fb4_activ.'<br>
    <img class="img-thumbnail" style="width: 100%; max-width: 250px" align="center" src="/includes/plugins/socialmedia/images/fb4.png" alt="{img}" />
    </div>
    </div>

  <div class="col-md-12">
    <button class="btn btn-warning" type="submit" name="facebook_save">'.$plugin_language['update'].'</button>
      </div> 
         </div>
</div>
    </form>
    </div>
</div>';




}