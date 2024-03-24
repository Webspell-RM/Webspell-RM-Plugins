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
$plugin_language = $pm->plugin_language("admin_history", $plugin_path);

$title = $plugin_language[ 'title' ]; #sc_datei Info

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='history'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}

if (isset($_POST[ 'submit' ])) {
    $title = $_POST[ 'title' ];
    $text = $_POST[ 'message' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (mysqli_num_rows(safe_query("SELECT * FROM " . PREFIX . "plugins_history"))) {
            safe_query("UPDATE " . PREFIX . "plugins_history SET title='" . $title . "',text='" . $text . "'");
        } else {
            safe_query("INSERT INTO " . PREFIX . "plugins_history (title, text) values( '" . $title . "', '" . $text . "') ");
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
}
$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_history");
$ds = mysqli_fetch_array($ergebnis);
$CAPCLASS = new \webspell\Captcha;
$CAPCLASS->createTransaction();
$hash = $CAPCLASS->getHash();


if(!empty($ds[ 'title'])){
  $title = $ds[ 'title' ];
}else{
  $title = '';
}

if(!empty($ds[ 'text'])){
  $text = $ds[ 'text' ];
}else{
  $text = '';
}

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-clock-history"></i> ' . $plugin_language[ 'history' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_history">' . $plugin_language[ 'title' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>
                        <div class="card-body">';

echo '<form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=admin_history" onsubmit="return chkFormular();">
  
 <div class="mb-3 row">
  <label class="col-md-2">' . $plugin_language['title_head'] . ':</label>

  <div class="col-md-12">
       <input class="form-control" type="text" name="title" size="60" maxlength="255" value="' . $title . '" />
      </div>
  </div>

 
<div class="mb-3 row">
 <label class="col-md-2"></label>

  <div class="col-md-12">
<textarea class="ckeditor" id="ckeditor" name="message"  rows="25" cols="" style="width: 100%;">'.$text.'</textarea>
</div>
  </div>

  <div class="mb-3 row">
    
    <div class="col-md-12">
  <input type="hidden" name="captcha_hash" value="'.$hash.'" />
  <button class="btn btn-warning" type="submit" name="submit" />'.$plugin_language['update'].'</button>

  </div>
 </div>
  
  </form>
  <div>
  </div>';
  
?>