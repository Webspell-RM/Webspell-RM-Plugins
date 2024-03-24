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
$plugin_language = $pm->plugin_language("game_server", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='servers'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}
//------------------------------------------------------------------------------------------------------------+
  require "./includes/plugins/game_server/func/lgsl_config.php";


//------------------------------------------------------------------------------------------------------------+
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Live Game Server List Admin</title>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
    <meta http-equiv='content-style-type' content='text/css' />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='func/styles/<?php echo $lgsl_config['style'];?>' type='text/css' />
  </head>
  <body>
    <div id='admin_page'>
      

<?php

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-controller"></i> ' . $plugin_language[ 'servers' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_game_server">' . $plugin_language[ 'servers' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">';
//------------------------------------------------------------------------------------------------------------+
  #if (defined("LGSL_ADMIN"))
  #{
    global $output;
    $output = "";
    require "./includes/plugins/game_server/func/lgsl_admin.php";
    echo $output;
 
//------------------------------------------------------------------------------------------------------------+
  echo '</div></div>';
?>
    </div>
  </body>
</html>