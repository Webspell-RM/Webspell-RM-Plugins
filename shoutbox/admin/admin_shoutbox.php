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
$plugin_language = $pm->plugin_language("admin_shoutbox", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='shoutbox'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}

if (isset($_POST[ 'shoutbox_settings_save' ])) {  

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
            "UPDATE
                " . PREFIX . "plugins_shoutbox_settings
            SET
                
                max_shoutbox_post='" . $_POST[ 'max_shoutbox_post' ] . "',
                `displayed`= '" . $displayed . "' "
        );
        
        redirect("admincenter.php?site=admin_shoutbox", "", 0);
    } else {
        redirect("admincenter.php?site=admin_shoutbox", $plugin_language[ 'transaction_invalid' ], 3);
    }


}




    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_shoutbox_settings");
    $ds = mysqli_fetch_array($settings);

    
  $maxshoutboxpost = $ds[ 'max_shoutbox_post' ];
if (empty($maxshoutboxpost)) {
    $maxshoutboxpost = 10;
}

if ($ds[ 'displayed' ] == 1) {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" checked="checked" />';
        } else {
        $displayed = '<input class="form-check-input" type="checkbox" name="displayed" value="1" />';
        }    

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    
echo'    <form method="post" action="admincenter.php?site=admin_shoutbox">
        <div class="card">
            <div class="card-header">
                '.$plugin_language[ 'settings' ].'
            </div>

            <div class="card-body">
                <div class="col-md-10 form-group"><a href="admincenter.php?site=admin_shoutbox" class="white">'.$plugin_language['title'].'</a> &raquo; Edit</div>
<div class="col-md-2 form-group"></div><br><br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['max_posts'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-right text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_1' ].'"><input class="form-control" name="max_shoutbox_post" type="text" value="'.$ds[ 'max_shoutbox_post' ].'" size="35"></em></span>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="row bt">
                            <div class="col-md-6">
                                '.$plugin_language['no_reg_post'].':
                            </div>

                            <div class="col-md-6">
                                <span class="pull-left text-muted small"><em data-toggle="tooltip" title="'.$plugin_language[ 'tooltip_2' ].'" data-placement="left">
    
    <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
    '.$displayed.'
    </div>
  </em></span>
                            </div>
                        </div>

                        
                    </div>
               </div>
                <br>
 <div class="form-group">
<input type="hidden" name="captcha_hash" value="'.$hash.'"> 
<button class="btn btn-primary" type="submit" name="shoutbox_settings_save">'.$plugin_language['update'].'</button>
</div>

        

 </div>
            </div>
       
        
    </form>';