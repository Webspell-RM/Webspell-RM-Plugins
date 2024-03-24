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
$plugin_language = $pm->plugin_language("tsviewer", $plugin_path);

$title = $plugin_language[ 'title' ]; #sc_datei Info

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='tsviewer'");
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
    
      $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_tsviewer");
    $ds = mysqli_fetch_array($ergebnis);  

    $tsadress = $_POST[ 'tsadress' ];
        
       safe_query(
            "UPDATE
                `" . PREFIX . "plugins_tsviewer`
            SET
                `tsadress` = '" . $tsadress . "'
            "
        );
        $id = mysqli_insert_id($_database);
    }

    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_tsviewer");
    $ds = mysqli_fetch_array($settings);

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-telephone-fill" aria-hidden="true"></i> ' . $plugin_language[ 'ts3viewer' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_tsviewer">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">';



 echo'<form method="post" action="admincenter.php?site=admin_tsviewer" class="form-horizontal">

    <div class="form-group">
    <label class="col-sm-2 control-label"><b>TSViewer ID:</b></label>
    <div class="col-sm-10">
    <input class="form-control" name="tsadress" type="text" value="'.$ds[ 'tsadress' ].'"/>
      
    </div>
    </div>
    
  <div class="form-group">  
<label class="col-sm-2 control-label"></label>
    <div class="col-sm-10">
    <button class="btn btn-success" type="submit" name="submit">' . $plugin_language[ 'update' ] . '</button>
  </div>
    </div>      
    </form>
    <br>
    <div class="alert alert-danger" style="text-align: center">' . $plugin_language[ 'info' ] . '</div>
    </div>

</div>
';













?>