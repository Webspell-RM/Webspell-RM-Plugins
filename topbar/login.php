<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*  
 *                                    Webspell-RM      /                        /   /                                                 *
 *                                    -----------__---/__---__------__----__---/---/-----__---- _  _ -                                *
 *                                     | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                                 *
 *                                    _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                                 *
 *                                                 Free Content / Management System                                                   *
 *                                                             /                                                                      *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         Webspell-RM                                                                                                       *
 *                                                                                                                                    *
 * @copyright       2018-2022 by webspell-rm.de <https://www.webspell-rm.de>                                                          *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de <https://www.webspell-rm.de/forum.html>  *
 * @WIKI            webspell-rm.de <https://www.webspell-rm.de/wiki.html>                                                             *
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                                                  *
 *                  It's NOT allowed to remove this copyright-tag <http://www.fsf.org/licensing/licenses/gpl.html>                    *
 *                                                                                                                                    *
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                                                 *
 * @copyright       2005-2018 by webspell.org / webspell.info                                                                         *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 */

# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("login", $plugin_path);

echo'
<div id="modal-login" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">        
                <h4 class="modal-title">Login </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
      ';

if ($loggedin) {
    #if ($userID && !isset($_GET[ 'userID' ]) && !isset($_POST[ 'userID' ])) {

        #Zur Seite zurÃ¼ck vor dem login
        #if(isset($_SESSION['HTTP_REFERER']) && !empty($_SESSION['HTTP_REFERER'])) {
            #ob_start();
            #if($_SESSION['HTTP_REFERER'] == 'index.php?site=login') {
                #header( 'Location: index.php?site=news');
            #} elseif ($_SESSION['HTTP_REFERER'] != "") {
                #header( 'Location: index.php');
            #} else {
                #header( 'Location: ' . $_SESSION['HTTP_REFERER'] );
            #}
            #ob_end_clean();
            #exit( 1 );
        #} else {
            #print '<html><head><script type="text/javascript">history.back();</script></head><body /></html>';
            #exit( 1 );
        #}

    #} else {
        echo $plugin_language[ 'you_have_to_be_logged_in' ];
    #}
} else {
    GLOBAL $logo,$theme_name,$themes;
    //set sessiontest variable (checks if session works correctly)
    $_SESSION[ 'ws_sessiontest' ] = true;
    $data_array=array();
    $data_array['$_modulepath'] = substr(MODULE, 0, -1);
    $data_array['$login_titel'] = $plugin_language[ 'login_titel' ];
    $data_array['$login'] = $plugin_language[ 'login' ];
    $data_array['$lang_register'] = $plugin_language[ 'register' ];
    $data_array['$cookie_title'] = $plugin_language[ 'cookie_title' ];
    $data_array['$cookie_text'] = $plugin_language[ 'cookie_text' ];
    $data_array['$register_now'] = $plugin_language[ 'register_now' ];
    $data_array['$lost_password'] = $plugin_language[ 'lost_password' ];
    $data_array['$have_an_account'] = $plugin_language['have_an_account'];
    $data_array['$info1'] = $plugin_language['info1'];
    $data_array['$info2'] = $plugin_language['info2'];
    $data_array['$reg'] = $plugin_language['reg'];
    $data_array['$forgotten_your_login'] = $plugin_language['forgotten_your_login'];
    $data_array['$info_login'] = $plugin_language['info_login'];
    $data_array['$enter_your_email'] = $plugin_language['enter_your_email'];
    $data_array['$enter_password'] = $plugin_language['enter_password'];
    $data_array['$need_account'] = $plugin_language['need_account'];

    $template = $GLOBALS["_template"]->loadTemplate("login","content", $data_array, $plugin_path);
    echo $template;
}
echo'
            </div>
        </div>
    </div>
</div>';