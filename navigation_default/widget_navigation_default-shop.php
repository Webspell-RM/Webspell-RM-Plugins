<style type="text/css">.full-width.dropdown {
    position: static;
}
.full-width.dropdown > .dropdown-menu {
    left: 0;
    right: 0;
    position: absolute;
}
.full-width.dropdown > .dropdown-menu > li > a {
   white-space: normal; 
}

.fill-width.dropdown {
    position: static;
}
.fill-width.dropdown > .dropdown-menu {
    left: auto;
    position: absolute;
}
.fill-width.dropdown > .dropdown-menu > li > a {
   white-space: normal; 
}
</style><?php
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
 * @copyright       2018-2024 by webspell-rm.de <https://www.webspell-rm.de>                                                          *
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
$plugin_language = $pm->plugin_language("navigation", $plugin_path);
GLOBAL $logo,$theme_name,$themes,$tpl,$loggedin,$index_language,$modRewrite,$action,$modulname,$firstname,$userID;

$pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("shop", $plugin_path);
    GLOBAL $logo,$theme_name,$themes,$tpl,$loggedin,$index_language,$modRewrite,$action,$firstname,$userID;

    $id = $userID;

        $cos_query = safe_query("select * FROM " . PREFIX . "user where userID = '".$id."'");
        $cos_row = mysqli_fetch_array($cos_query);

$ergebnis=safe_query("SELECT * FROM ".PREFIX."settings_themes WHERE active = 1");
$ds=mysqli_fetch_array($ergebnis);
?>

<!-- ======= Header ======= -->
<header id="header" class="sticky-top d-flex align-items-center">
    <div class="container d-flex justify-content-between">
        <div class="logo">
            <a href="#"><img class="img-fluid" src="../includes/themes/<?php echo $theme_name; ?>/images/<?php echo $ds[ 'logo_pic' ]; ?>" alt=""></a>
        </div>
        <div class="box">
            <span class="webspell"><?php echo $ds[ 'logotext1' ];?></span>
            <span class="slogan"><?php echo $ds[ 'logotext2' ];?></span>
        </div>
        <nav id="navbar" class="navbar <?php echo $ds[ 'nav_text_alignment' ];?>">
            <ul>
                <?php include("./includes/modules/navigation.php"); ?>
                <?php $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_widgets WHERE modulname='topbar' AND themes_modulname='default' AND position='via_navigation_widget'"));
                if (@$dx['activate'] == "0") {
                    if($loggedin) {
                        include("./includes/modules/navigation_login.php");
                        #angemeldet MIT plugin';   
                    } else {
                        #no login MIT plugin'; 
                    }
                } else {
                    if($loggedin) {
                        include("./includes/modules/navigation_login.php");
                        include("./includes/modules/language_flag.php");
                        #angemeldet OHNE plugin';   
                    } else {
                        include("./includes/modules/navigation_login.php");
                        include("./includes/modules/language_flag.php");  
                        #no login OHNE plugin'; 
                    }                    
                }?>                        



                   
<li class="nav-item dropdown fill-width">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Full Width</a>








 <ul class="dropdown-menu dropdown-menu-end fade-down" style="background-color: #FFF;margin-top: -10px">
        

        <li class="nav-item">

                <a class="nav-link" href="index.php?site=shop" style="height: 45px;"><?php echo $plugin_language['view_all']?></a>
                <?php
                $category_query = safe_query("select * FROM " . PREFIX . "plugins_shop_categories_tbl");
                while ($category_row = mysqli_fetch_array($category_query)) {
                ?>
                <a class="nav-link" href="index.php?site=shop&category=<?=$category_row['cat_id'];?>" style="height: 45px;"><?=$category_row['category_name'];?></a>
                <?php
                }
                ?>
                <?php
                    $cnt_seller_query = safe_query("select count(userID) as userID FROM " . PREFIX . "user");
                    $cnt_seller_row = mysqli_fetch_array($cnt_seller_query);
                    $cnt_cart_query = safe_query("select count(cart_id) as cart_id FROM " . PREFIX . "plugins_shop_carts_tbl where userID = '".$cos_row['userID']."' and cart_status = 'Pending'");
                    $cnt_cart_row = mysqli_fetch_array($cnt_cart_query);
                    $cnt_wish_query = safe_query("select count(wish_id) as wish_id FROM " . PREFIX . "plugins_shop_wishlists_tbl where userID = '".$cos_row['userID']."'");
                    $cnt_wish_row = mysqli_fetch_array($cnt_wish_query);
                ?>

        
      </ul>
                </li>   








                
      <?php  if (!$userID && empty($email) && empty($acc_type)) {
    echo '';
} else { ?>
                <!--<a class="nav-link active" href="index.php?site=shop&action=costumers" title="Home"><i class="fa fa-home fa-fw"></i></a>-->
            <li class="nav-item"><a class="nav-link nav-head" href="index.php?site=shop&action=cos-checkout" title="Checkouts"> | <i class="fa fa-shopping-bag fa-fw"></i><span class="badge pull-right" style="margin-top: 4px;"><?=$cnt_cart_row['cart_id'];?></span></a></li>
            <li class="nav-item"><a class="nav-link nav-head" href="index.php?site=shop&action=cos-order" title="Orders"><i class="fa fa-calendar-plus fa-fw"></i></a></li>
            <li class="nav-item"><a class="nav-link nav-head" href="#" title="Messages"><i class="fa fa-envelope fa-fw"></i></a></li>


            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Hi, <?php echo $cos_row['firstname'];?> <?php echo $cos_row['lastname'];?></a>
                 <ul class="dropdown-menu dropdown-menu-end fade-down">
                <li><a class="dropdown-item" >
                    <img src="./includes/plugins/shop/profile-photos/<?=$cos_row['profile_photo'];?>" class="img-responsive img-rounded margin-top margin-bottom" style="height: 100px; width: auto; margin: auto;">
                  </li> 
                  <li class="divider"></li>
                  <li><a class="dropdown-item" href="index.php?site=shop&action=cos-profile"><i class="fa fa-user fa-fw"></i> Profile</a></li>
                  <li><a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-accountsettings"><i class="fa fa-cog fa-fw"></i> Account Settings</a></li>
                  <li><a class="dropdown-item" href="index.php?site=logout"><i class="fa fa-sign-out-alt fa-fw"></i> Log Out</a></li>
                </ul>
            </li>


            </ul>
        <?php } ?>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->
    </div><?php include "./includes/plugins/shop/modals/account-settings.php";?>
</header><!-- End Header -->
