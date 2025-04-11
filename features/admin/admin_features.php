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
$plugin_language = $pm->plugin_language("admin_features", $plugin_path);
global $get_test;

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='features_one'");
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

if (isset($_POST[ 'submit_one' ])) {
    $title_one = $_POST[ "title_one" ];
    $text_one = $_POST[ 'message_one' ];

    $title_two = $_POST[ "title_two" ];
    $text_two = $_POST[ 'message_two' ];

    $title_three = $_POST[ "title_three" ];
    $text_three = $_POST[ 'message_three' ];

    $title_four = $_POST[ "title_four" ];
    $text_four = $_POST[ 'message_four' ];

    $title_five = $_POST[ "title_five" ];
    $text_five = $_POST[ 'message_five' ];

    $title_six = $_POST[ "title_six" ];
    $text_six = $_POST[ 'message_six' ];

    $features_box_chars = $_POST[ 'features_box_chars' ];

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
      
        if (mysqli_num_rows(safe_query("SELECT * FROM " . PREFIX . "plugins_features"))) {
            safe_query("UPDATE " . PREFIX . "plugins_features SET 
                title_one='" . $title_one . "', 
                text_one='" . $text_one . "', 
                title_two='" . $title_two . "', 
                text_two='" . $text_two . "', 
                title_three='" . $title_three . "', 
                text_three='" . $text_three . "', 
                title_four='" . $title_four . "', 
                text_four='" . $text_four . "',
                title_five='" . $title_five . "', 
                text_five='" . $text_five . "', 
                title_six='" . $title_six . "', 
                text_six='" . $text_six . "', 
                features_box_chars='" . $features_box_chars . "' ");
        } else {
            safe_query("INSERT INTO " . PREFIX . "plugins_features (
                title_one, 
                text_one, 
                title_two, 
                text_two, 
                title_three, 
                text_three, 
                title_four, 
                text_four, 
                title_five, 
                text_five, 
                title_six, 
                text_six, 
                features_box_chars) values ( 
                '" . $title_one . "', 
                '" . $text_one . "', 
                '" . $title_two . "', 
                '" . $text_two . "', 
                '" . $title_three . "', 
                '" . $text_three . "', 
                '" . $title_four . "', 
                '" . $text_four . "', 
                '" . $title_five . "', 
                '" . $text_five . "', 
                '" . $title_six . "', 
                '" . $text_six . "', 
                '" . $features_box_chars . "' ");
        }
        redirect("admincenter.php?site=admin_features", "", 1);
        echo $plugin_language[ 'transaction_done' ];
    } else {
      redirect("admincenter.php?site=admin_features", "", 1);
        echo $plugin_language[ 'transaction_invalid' ];
    }


}elseif (isset($_POST[ 'submit_two' ])) {
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

} elseif ($action == "edit_features_box_one") {

  
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_features");
    $ds = mysqli_fetch_array($ergebnis);

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    if(!empty($ds[ 'title_one'])){
        $title_one = $ds[ 'title_one' ];
    }else{
        $title_one = '';
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

    if(!empty($ds[ 'text_three'])){
        $text_three = $ds[ 'text_three' ];
    }else{
        $text_three = '';
    }

    if(!empty($ds[ 'title_four'])){
        $title_four = $ds[ 'title_four' ];
    }else{
        $title_four = '';
    }

    if(!empty($ds[ 'text_four'])){
        $text_four = $ds[ 'text_four' ];
    }else{
        $text_four = '';
    }

    if(!empty($ds[ 'title_five'])){
        $title_five = $ds[ 'title_five' ];
    }else{
        $title_five = '';
    }

    if(!empty($ds[ 'text_five'])){
        $text_five = $ds[ 'text_five' ];
    }else{
        $text_five = '';
    }

    if(!empty($ds[ 'title_six'])){
        $title_six = $ds[ 'title_six' ];
    }else{
        $title_six = '';
    }

    if(!empty($ds[ 'text_six'])){
        $text_six = $ds[ 'text_six' ];
    }else{
        $text_six = '';
    }

    if(!empty($ds[ 'features_box_chars'])){
        $features_box_chars = $ds[ 'features_box_chars' ];
    }else{
        $features_box_chars = '';
    }

echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-person-vcard"></i> ' . $plugin_language[ 'features_box' ] . '</div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'features_box' ] . '</li>
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

    <form method="post" id="post" name="post" action="admincenter.php?site=admin_features_one" enctype="multipart/form-data" onsubmit="return chkFormular();">
    <h4>' . $plugin_language[ 'box_one' ] . '</h4>
    <div class="mb-3 row">
    <label for="title" class="col-md-2 col-form-label">' . $plugin_language[ 'title_head' ] . ':</label>
    <div class="col-md-12">
      <input type="text" class="form-control" name="title_one"  value="'.$title_one.'">
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

    <div class="mb-3">
    <label for="exampleFormControlTextarea1" class="form-label">' . $plugin_language[ 'description' ] . '</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" name="message_three" rows="3">'.$text_three.'</textarea>
    </div>

    <hr>
    <h4>' . $plugin_language[ 'box_four' ] . '</h4>

    <div class="mb-3 row">
    <label for="title" class="col-md-2 col-form-label">' . $plugin_language[ 'title_head' ] . ':</label>
    <div class="col-md-12">
      <input type="text" class="form-control" name="title_four"  value="'.$title_four.'">
    </div>
    </div>

    <div class="mb-3">
    <label for="exampleFormControlTextarea1" class="form-label">' . $plugin_language[ 'description' ] . '</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" name="message_four" rows="3">'.$text_four.'</textarea>
    </div>

    <hr>
    <h4>' . $plugin_language[ 'box_five' ] . '</h4>

    <div class="mb-3 row">
    <label for="title" class="col-md-2 col-form-label">' . $plugin_language[ 'title_head' ] . ':</label>
    <div class="col-md-12">
      <input type="text" class="form-control" name="title_five"  value="'.$title_five.'">
    </div>
    </div>

    <div class="mb-3">
    <label for="exampleFormControlTextarea1" class="form-label">' . $plugin_language[ 'description' ] . '</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" name="message_five" rows="3">'.$text_five.'</textarea>
    </div>

    <hr>
    <h4>' . $plugin_language[ 'box_six' ] . '</h4>

    <div class="mb-3 row">
    <label for="title" class="col-md-2 col-form-label">' . $plugin_language[ 'title_head' ] . ':</label>
    <div class="col-md-12">
      <input type="text" class="form-control" name="title_six"  value="'.$title_six.'">
    </div>
    </div>

    <div class="mb-3">
    <label for="exampleFormControlTextarea1" class="form-label">' . $plugin_language[ 'description' ] . '</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" name="message_six" rows="3">'.$text_six.'</textarea>
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
          <button class="btn btn-success" type="submit" name="submit_one"  />' . $plugin_language[ 'update' ] . '</button>
    </div>
    
</div>

</form>
 
  <div>
  </div></div>';

} elseif ($action == "edit_features_box_two") {

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
            <i class="bi bi-person-vcard-fill"></i> ' . $plugin_language[ 'features_box' ] . '</div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'features_box' ] . '</li>
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
          <button class="btn btn-success" type="submit" name="submit_two"  />' . $plugin_language[ 'update' ] . '</button>
    </div>
    
</div>

</form>
 
  <div>
  </div></div>';

}else{
echo'<div class="card">
        <div class="card-header">
            <i class="bi bi-person-vcard"></i> ' . $plugin_language[ 'features_box' ] . '</div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'features_box' ] . '</li>
    <li class="breadcrumb-item active" aria-current="page">new & edit</li>
  </ol>
</nav>

<div class="card-body">

<div class="table-responsive">
    

<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">'.$plugin_language['name'].'</th>
      <th scope="col">'.$plugin_language['actions'].'</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Features Box One Content<br><img src="/includes/plugins/features/images/widget_features_box_one_content.jpg" class="img-fluid" alt="Box One" style="width: 350px"></td>
      <td><a href="admincenter.php?site=admin_features&amp;action=edit_features_box_one" class="btn btn-warning" type="button">' . $plugin_language[ 'update' ] . '</a></td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Features Box Two Content<br><img src="/includes/plugins/features/images/widget_features_box_two_content.jpg" class="img-fluid" alt="Box One" style="width: 350px"></td>
      <td><a href="admincenter.php?site=admin_features&amp;action=edit_features_box_two" class="btn btn-warning" type="button">' . $plugin_language[ 'update' ] . '</a></td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td colspan="2">Features Box Three Content</td>
    </tr>
    <tr>
      <th scope="row">4</th>
      <td colspan="2">Features Box Four Content</td>
    </tr>
  </tbody>
</table>
</div>

</div></div>';

}
?>