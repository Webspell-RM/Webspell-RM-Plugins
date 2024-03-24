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
$plugin_language = $pm->plugin_language("userlist", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='userlist'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}

if (isset($_POST[ 'submit' ])) {
    $users_list = $_POST[ "users_list" ];
    $users_online = $_POST[ "users_online" ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query("UPDATE `" . PREFIX . "plugins_userlist` SET users_list='" . $_POST[ 'users_list' ] . "', users_online='" . $_POST[ 'users_online' ] . "'");
        
        
        redirect("admincenter.php?site=admin_userlist", "", 0);
    } else {
        redirect("admincenter.php?site=admin_userlist", $plugin_language[ 'transaction_invalid' ], 3);
    }
} else {
$ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_userlist`");
    $ds = mysqli_fetch_array($ergebnis);
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();


    echo '<div class="card">
            <div class="card-header"> <i class="bi bi-person-fills"></i> ' . $plugin_language[ 'registered_users' ] . '
            </div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page"><a href="admincenter.php?site=admin_userlist" class="white">' . $plugin_language[ 'registered_users' ] . '</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
  </ol>
</nav>

            <div class="card-body">

<div class="row">
<div class="col-md-12">
<form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=admin_userlist" onsubmit="return chkFormular();">



                        <div class="mb-3 row bt">
                            <div class="col-md-4">
                                ' . $plugin_language['max_registered_users'] . ':
                            </div>

                            <div class="col-md-2">
                                <span class="pull text-muted small"><em data-toggle="tooltip" title="' . $plugin_language[ 'tooltip_1' ] . '"><input class="form-control" type="text" name="users_list" value="' . $ds['users_list'] . '" size="35"></em></span>
                            </div>
                        </div>

                           <div class="mb-3 row bt">
                            <div class="col-md-4">'.$plugin_language['max_users_online'] .'</label>

                        </div>

                            <div class="col-md-2">
                                <span class="pull text-muted small"><em data-toggle="tooltip" data-html="true" title="'.$plugin_language[ 'tooltip_1' ] .'"><input class="form-control" type="text" name="users_online" value="'.$ds['users_online'] .'" size="35"></em></span>
                        </div>
                    </div>
 
<input type="hidden" name="captcha_hash" value="' . $hash . '"> 
<button class="btn btn-warning" type="submit" name="submit"  />' . $plugin_language['update'] . '</button>

</form></div></div></div></div>';
}
?>

