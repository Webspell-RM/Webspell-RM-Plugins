<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                  Webspell-RM      /                        /   /                                          *
 *                  -----------__---/__---__------__----__---/---/-----__---- _  _ -                         *
 *                   | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                          *
 *                  _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                          *
 *                               Free Content / Management System                                            *
 *                                           /                                                               *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         webspell-rm                                                                              *
 *                                                                                                           *
 * @copyright       2018-2023 by webspell-rm.de                                                              *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de                 *
 * @website         <https://www.webspell-rm.de>                                                             *
 * @forum           <https://www.webspell-rm.de/forum.html>                                                  *
 * @wiki            <https://www.webspell-rm.de/wiki.html>                                                   *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                         *
 *                  It's NOT allowed to remove this copyright-tag                                            *
 *                  <http://www.fsf.org/licensing/licenses/gpl.html>                                         *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                        *
 * @copyright       2005-2011 by webspell.org / webspell.info                                                *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
*/

# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("admin_features_box", $plugin_path);
global $get_test;

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='nor_box'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}

if (isset($_POST[ 'submit' ])) {
    $title_one = $_POST[ "title_one" ];
    $title_small_one = $_POST[ "title_small_one" ];
    $text_one = $_POST[ 'message_one' ];

    $title_two = $_POST[ "title_two" ];
    $title_small_two = $_POST[ "title_small_two" ];
    $text_two = $_POST[ 'message_two' ];

    $title_three = $_POST[ "title_three" ];
    $title_small_three = $_POST[ "title_small_three" ];
    $text_three = $_POST[ 'message_three' ];

    $features_box_chars = $_POST[ 'features_box_chars' ];

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
      
        if (mysqli_num_rows(safe_query("SELECT * FROM " . PREFIX . "plugins_features_box"))) {
            safe_query("UPDATE " . PREFIX . "plugins_features_box SET title_one='" . $title_one . "', title_small_one='" . $title_small_one . "', text_one='" . $text_one . "', title_two='" . $title_two . "', title_small_two='" . $title_small_two . "', text_two='" . $text_two . "', title_three='" . $title_three . "', title_small_three='" . $title_small_three . "', text_three='" . $text_three . "', features_box_chars='" . $features_box_chars . "' ");
        } else {
            safe_query("INSERT INTO " . PREFIX . "plugins_features_box (title_one, title_small_one, text_one, title_two, title_small_two, text_two, title_three, title_small_three, text_three, features_box_chars) values ( '" . $title_one . "', '" . $title_small_one . "', '" . $text_one . "', '" . $title_two . "', '" . $title_small_two . "', '" . $text_two . "''" . $title_three . "', '" . $title_small_three . "', '" . $text_three . "', '" . $features_box_chars . "' ");
        }
        redirect("admincenter.php?site=admin_features_box", "", 1);
        echo $plugin_language[ 'transaction_done' ];
    } else {
      redirect("admincenter.php?site=admin_features_box", "", 1);
        echo $plugin_language[ 'transaction_invalid' ];
    }
} else {

  
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_features_box");
    $ds = mysqli_fetch_array($ergebnis);

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    if(!empty($ds[ 'title_one'])){
        $title_one = $ds[ 'title_one' ];
    }else{
        $title_one = '';
    }

    if(!empty($ds[ 'title_small_one'])){
        $title_small_one = $ds[ 'title_small_one' ];
    }else{
        $title_small_one = '';
    }

    if(!empty($ds[ 'text_one'])){
        $text_one = $ds[ 'text_one' ];
    }else{
        $text_one = '';
    }

    if(!empty($ds[ 'title_two'])){
        $title_two = $ds[ 'title_two' ];
    }else{
        $title_two = '';
    }

    if(!empty($ds[ 'title_small_two'])){
        $title_small_two = $ds[ 'title_small_two' ];
    }else{
        $title_small_two = '';
    }

    if(!empty($ds[ 'text_two'])){
        $text_two = $ds[ 'text_two' ];
    }else{
        $text_two = '';
    }

    if(!empty($ds[ 'title_three'])){
        $title_three = $ds[ 'title_three' ];
    }else{
        $title_three = '';
    }

    if(!empty($ds[ 'title_small_three'])){
        $title_small_three = $ds[ 'title_small_three' ];
    }else{
        $title_small_three = '';
    }

    if(!empty($ds[ 'text_three'])){
        $text_three = $ds[ 'text_three' ];
    }else{
        $text_three = '';
    }

    if(!empty($ds[ 'features_box_chars'])){
        $features_box_chars = $ds[ 'features_box_chars' ];
    }else{
        $features_box_chars = '';
    }

echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-person-vcard-fill"></i> ' . $plugin_language[ 'nor_box' ] . '</div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'nor_box' ] . '</li>
    <li class="breadcrumb-item active" aria-current="page">new & edit</li>
  </ol>
</nav>

<div class="card-body">

     <script type="text/javascript">
        function chkFormular() {
            if(!validbbcode(document.getElementById("message").value, "admin")){
                return false;
            }
        }
    </script>

    <form method="post" id="post" name="post" action="admincenter.php?site=admin_features_box" enctype="multipart/form-data" onsubmit="return chkFormular();">
    <h4>' . $plugin_language[ 'box_one' ] . '</h4>
    <div class="mb-3 row">
    <label for="title" class="col-md-2 col-form-label">' . $plugin_language[ 'title_head' ] . ':</label>
    <div class="col-md-12">
      <input type="text" class="form-control" name="title_one"  value="'.$title_one.'">
    </div>
    </div>

    <div class="mb-3 row">
    <label for="title" class="col-md-2 col-form-label">' . $plugin_language[ 'title_head' ] . ':</label>
    <div class="col-md-12">
      <input type="text" class="form-control" name="title_small_one"  value="'.$title_small_one.'">
    </div>
    </div>

    <div class="mb-3">
    <label for="exampleFormControlTextarea1" class="form-label">' . $plugin_language[ 'description' ] . '</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" name="message_one" rows="3">'.$text_one.'</textarea>
    </div>

    <hr>
    <h4>' . $plugin_language[ 'box_two' ] . '</h4>
    <div class="mb-3 row">
    <label for="title" class="col-md-2 col-form-label">' . $plugin_language[ 'title_head' ] . ':</label>
    <div class="col-md-12">
      <input type="text" class="form-control" name="title_two"  value="'.$title_two.'">
    </div>
    </div>

    <div class="mb-3 row">
    <label for="title" class="col-md-2 col-form-label">' . $plugin_language[ 'title_head' ] . ':</label>
    <div class="col-md-12">
      <input type="text" class="form-control" name="title_small_two"  value="'.$title_small_two.'">
    </div>
    </div>

    <div class="mb-3">
    <label for="exampleFormControlTextarea1" class="form-label">' . $plugin_language[ 'description' ] . '</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" name="message_two" rows="3">'.$text_two.'</textarea>
    </div>
    <hr>
    <h4>' . $plugin_language[ 'box_three' ] . '</h4>

    <div class="mb-3 row">
    <label for="title" class="col-md-2 col-form-label">' . $plugin_language[ 'title_head' ] . ':</label>
    <div class="col-md-12">
      <input type="text" class="form-control" name="title_three"  value="'.$title_three.'">
    </div>
    </div>

    <div class="mb-3 row">
    <label for="title" class="col-md-2 col-form-label">' . $plugin_language[ 'title_head' ] . ':</label>
    <div class="col-md-12">
      <input type="text" class="form-control" name="title_small_three"  value="'.$title_small_three.'">
    </div>
    </div>

    <div class="mb-3">
    <label for="exampleFormControlTextarea1" class="form-label">' . $plugin_language[ 'description' ] . '</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" name="message_three" rows="3">'.$text_three.'</textarea>
    </div>

    <div class="mb-3 row">
    <label for="aboutchars" class="col-md-2 col-form-label">' . $plugin_language[ 'max_sc' ] . ':</label>
    <div class="col-md-12">
      <input type="text" class="form-control" name="features_box_chars"  value="'.$features_box_chars.'">
    </div>
    </div>


<div class="mb-3 row">
    
    <div class="col-md-12">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
          <button class="btn btn-success" type="submit" name="submit"  />' . $plugin_language[ 'update' ] . '</button>
    </div>
    
</div>

</form>
 
  <div>
  </div></div>';
}
?>