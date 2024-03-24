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
$plugin_language = $pm->plugin_language("ts3viewer", $plugin_path);

$title = $plugin_language[ 'title' ]; #sc_datei Info

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='ts3viewer'");
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

if (isset($_POST[ 'submit' ])) {
    
      $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_ts3viewer");
    $ds = mysqli_fetch_array($ergebnis);  

    $ts3_name = $_POST[ 'ts3_name' ];
    $ts3_ip = $_POST[ 'ts3_ip' ];
    $ts3_port = $_POST[ 'ts3_port' ];
    $ts3_qport = $_POST[ 'ts3_qport' ];
        
       safe_query(
            "UPDATE
                `" . PREFIX . "plugins_ts3viewer`
            SET
                `ts3_name` = '" . $ts3_name . "',
                `ts3_ip` = '" . $ts3_ip . "',
                `ts3_port` = '" . $ts3_port . "',
                `ts3_qport` = '" . $ts3_qport . "'
            "
        );
        $id = mysqli_insert_id($_database);
    }

    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_ts3viewer");
    $ds = mysqli_fetch_array($settings);

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-telephone-fill" aria-hidden="true"></i> ' . $plugin_language[ 'ts3viewer' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_ts3viewer">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">';


  echo'<form method="post" action="admincenter.php?site=admin_ts3viewer" class="form-horizontal">

    <div class="mb-3 row">
    <label class="col-sm-2 control-label"><b>' . $plugin_language[ 'name' ] . ':</b></label>
    <div class="col-sm-10">
    <input class="form-control" name="ts3_name" type="text" value="'.$ds[ 'ts3_name' ].'"/>
      
    </div>
    </div>
    <div class="mb-3 row">
    <label class="col-sm-2 control-label"><b>Teamspeak IP:</b></label>
    <div class="col-sm-10">
    <input class="form-control" name="ts3_ip" type="text" value="'.$ds[ 'ts3_ip' ].'"/>
      
    </div>
    </div>
    <div class="mb-3 row">
    <label class="col-sm-2 control-label"><b>' . $plugin_language[ 'port' ] . ':</b></label>
    <div class="col-sm-10">
    <input class="form-control" name="ts3_port" type="text" value="'.$ds[ 'ts3_port' ].'"/>
      
    </div>
    </div>
    <div class="mb-3 row">
    <label class="col-sm-2 control-label"><b>' . $plugin_language[ 'QPort' ] . ':</b></label>
    <div class="col-sm-10">
    <input class="form-control" name="ts3_qport" type="text" value="'.$ds[ 'ts3_qport' ].'"/>
      
    </div>
    </div>
    
  <div class="mb-3 row">  
<label class="col-sm-2 control-label"></label>
    <div class="col-sm-10">
    <button class="btn btn-success" type="submit" name="submit">' . $plugin_language[ 'update' ] . '</button>
  </div>
    </div>      
    </form>
   

</div>
';













?>