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
$plugin_language = $pm->plugin_language("joinus", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='joinus'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}



if (isset($_POST[ 'submit' ])) {
    $title = $_POST[ 'title' ];
    $text = $_POST[ 'message' ];

    if (isset($_POST[ "show" ])) {
            $show = 0;
        } else {
            $show = 1;
        }

        if (isset($_POST[ "terms_of_use" ])) {
            $terms_of_use = 0;
        } else {
            $terms_of_use = 1;
        }


    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (mysqli_num_rows(safe_query("SELECT * FROM " . PREFIX . "plugins_join_us"))) {
            safe_query("UPDATE " . PREFIX . "plugins_join_us 
                SET 
                `show` = '" . $show . "',
                `terms_of_use` = '" . $terms_of_use . "',
                `title` = '" . $title . "',
                `text` = '" . $text . "'");
        } else {
            safe_query("INSERT INTO " . PREFIX . "plugins_join_us (show,terms_of_use,title, text) values( '" . $show . "','" . $terms_of_use . "','" . $title . "', '" . $text . "') ");
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
}

echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-graph-up"></i> '. $plugin_language[ 'title_join_us' ] .'</div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">'. $plugin_language['title_join_us' ] .'</li>
    <li class="breadcrumb-item active" aria-current="page">new & edit</li>
  </ol>
</nav>

            <div class="card-body">';    


    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_join_us");
    $ds = mysqli_fetch_array($settings);
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

        if ($ds[ 'show' ] == 0) {
        $show = '<input class="form-check-input" type="checkbox" name="show" value="0" checked="checked" />';
        } else {
        $show = '<input class="form-check-input" type="checkbox" name="show" value="0" />';
        }

        if ($ds[ 'terms_of_use' ] == 0) {
        $terms_of_use = '<input class="form-check-input" type="checkbox" name="terms_of_use" value="0" checked="checked" />';
        } else {
        $terms_of_use = '<input class="form-check-input" type="checkbox" name="terms_of_use" value="0" />';
        }

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
    

echo'<form method="post" id="post" name="post" action="admincenter.php?site=admin_joinus" enctype="multipart/form-data" onsubmit="return chkFormular();">


            <div class="mb-3 row">
                <label for="select-squad" class="col-md-2 col-form-label">'.$plugin_language['admin_info'].'</label>

                <div class="col-md-4 form-check form-switch" style="padding: 0px 43px;">
                    '.$show.'
                </div>
            </div>

            <div class="mb-3 row">
                <label for="select-squad" class="col-md-2 col-form-label">'.$plugin_language['terms_of_use'].'</label>

                <div class="col-md-4 form-check form-switch" style="padding: 0px 43px;">
                    '.$terms_of_use.'
                </div>
            </div>
               


 <script type="text/javascript">
        function chkFormular() {
            if(!validbbcode(document.getElementById("message").value, "admin")){
                return false;
            }
        }
    </script>

    

    <div class="mb-3 row">
    <label for="title" class="col-md-2 col-form-label">'.$plugin_language['widget_title'].':</label>
    <div class="col-md-12">
      <input class="form-control" type="text" name="title" size="60" maxlength="255" value="' . $title . '" />
    </div>
    </div>

    <div class="mb-3 row">
    <label for="message" class="col-md-2 col-form-label">'.$plugin_language['description'].':</label>
    <div class="col-md-12">
      <textarea class="ckeditor" id="ckeditor" name="message"  rows="25" cols="" style="width: 100%;">'.$text.'</textarea>
    </div>
    </div>

    




            <input type="hidden" name="captcha_hash" value="'.$hash .'"> 
            <button class="btn btn-warning" type="submit" name="submit">'.$plugin_language['update'] .'</button>
    </form>
    </div></div>
   ';
