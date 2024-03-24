<?php
@include_once("../../../system/sql.php");
@include_once("../../../system/settings.php");

function curl_json2array($url){
    $ssl = 1;
    if (substr($url, 0, 7) == "http://") { $ssl=0; } else { $ssl=1;}  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    $output = curl_exec($ch);
    curl_close($ch);
    if($output != "NULL") {
        return '';
    }
}
$plugin_language = $pm->plugin_language("admin_user_awards", $plugin_path);
$getuser = safe_query("SELECT * FROM " . PREFIX . "user");
if (mysqli_num_rows($getuser)) {
    $n = 0;
    while ($ds = mysqli_fetch_array($getuser)) {
        $url = ''.$hp_url.'/index.php?site=profile&id='.$ds['userID'].'';
        $result = curl_json2array($url);
        $n++;
    }
}
echo '
<div class=\'alert alert-success\' role=\'alert\'>'.$plugin_language[ 'update_user_ok' ].' </div>
';

?>