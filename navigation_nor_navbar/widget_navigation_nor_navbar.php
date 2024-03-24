<?php
/*-----------------------------------------------------------------\
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
\------------------------------------------------------------------*/

# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("navigation", $plugin_path);
    GLOBAL $logo,$theme_name,$themes,$tpl,$loggedin,$index_language,$modRewrite;

    GLOBAL $site,$logo,$theme_name,$themes,$tpl,$loggedin,$index_language,$modRewrite;

    $ergebnis=safe_query("SELECT * FROM ".PREFIX."settings_themes WHERE active = 1");
        $ds=mysqli_fetch_array($ergebnis);

        $ergebnis_site=safe_query("SELECT * FROM " . PREFIX . "settings_module WHERE modulname='".@$_GET['site']."'");
        $dx=mysqli_fetch_array($ergebnis_site);


if (@$dx[ 'modulname' ] != 'startpage' && @$dx[ 'modulname' ] != '') {
            ?>
            <style type="text/css">#navigation_modul {margin-top: 0px;margin-bottom: 0px;}</style>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="main_Nav">
  <div class="container logo-box">
                <span class="logo"><a class="navbar-brand" href="#"><img class="float-left bg-primary img-fluid" style="max-width:100%;height: 75px;" src="../includes/themes/<?php echo $theme_name; ?>/images/<?php echo $ds[ 'logo_pic' ]; ?>" alt=""></span>
                <div class="box">
                    <span class="webspell"><?php echo $ds[ 'logotext1' ];?></span>
                    <span class="slogan"><?php echo $ds[ 'logotext2' ];?></span>
               </div>
            </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="bi bi-list ms-1"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav <?php echo $ds[ 'nav_text_alignment' ];?>">
                        <?php include("./includes/modules/navigation.php"); ?>
                        <?php include("./includes/modules/navigation_login.php"); ?>
                        <?php include("./includes/modules/language.php");?>
                    </ul>
                </div>
            </div>
</nav>
        <?php
        } else {
        ?> 
        
<div class="backcolor-white d-none d-lg-block" style="height: 150px">
<div class="container backcolor-white">
    <div class="headx-left backcolor-white">
        <div class="container">
    <span class="logo"><a class="navbar-brand" href="#"><img class="float-left bg-primary img-fluid" style="max-width:100%;height: 75px;" src="../includes/themes/<?php echo $theme_name; ?>/images/<?php echo $ds[ 'logo_pic' ]; ?>" alt=""></span>
        <div class="box">
            <span class="webspell"><?php echo $ds[ 'logotext1' ];?></span>
            <span class="slogan"><?php echo $ds[ 'logotext2' ];?></span>
        </div>
    </a></span></div></div>
    <div class="headx-right hidden-xs backcolor-white row" >
        <div class="col-sm-6"><?php include(MODULE."language.php");?></div><div class="col-sm-6" style="margin-top: 20px;">
        <a href="http://www.webspell-rm.de" target="_blank"><i class="bi bi-facebook" style="font-size: 1.5rem;"></i></a> &nbsp;
        <a href="http://www.webspell-rm.de" target="_blank"><i class="bi bi-twitter" style="font-size: 1.5rem;"></i></a> &nbsp;
        <a href="http://www.webspell-rm.de" target="_blank"><i class="bi bi-instagram" style="font-size: 1.5rem;"></i></a> &nbsp;
        <a href="http://www.webspell-rm.de" target="_blank"><i class="bi bi-youtube" style="font-size: 1.5rem;"></i><i class="bi bi-youtube" style="font-size: 1.5rem;"></i></a> &nbsp;</div>
        <div class="col-sm-2"></div><div class="col-sm-10"><br />            
        <?php 
        $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='newsletter'"));
                    if (@$dx[ 'modulname' ] != 'newsletter') {
                    $test = '';
                    } else {
                    get_widget('newsletter','plugin_widget3');
                    
                    };
                    ?></div>
    </div>
</div>
</div>
    <section class="py-5 header text-center masthead d-none d-lg-block">
        
    <div class="container py-5 text-white" style="height: 520px">
        <header class="mas1thead py-5">
            <div class="hea1dx-img hidden-xs">
                <div class="container">
                    <div class="male5c size-250 font-bold headx-img-pro fl mato100 color-blue backcolor-white">THE Webspell-RM IS ONLINE!</div>
                    <div class="male5c size-100 font-lato-normal headx-img-pro cl fl mato10 backcolor-blue color-white">super powerful, responsive features, easy to adjust</div>
                    <div class="male5c size-100 font-lato-normal headx-img-pro cl fl mato10 backcolor-blue color-white">one of the easiest content management systems on earth</div>
                    <div class="male5c size-100 font-lato-normal headx-img-pro cl fl mato10 backcolor-blue color-white">wonderful bootstrap or photoshop templates</div>
                    <div class="male5c size-100 font-lato-normal headx-img-pro cl fl mato10 backcolor-blue color-white">lots of Add-ons and modifications for all types of websites</div>
                    <div class="male5c size-100 font-lato-normal headx-img-pro cl fl mato10 backcolor-blue color-white">a community behind you for all issues and problems</div>
                </div>
            </div>
        </header>
    </div>
</section>


<!-- Sticky navbar-->
<header class="header sticky-top">


    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container logo-box">
             
            <span class="logo"><a class="navbar-brand" href="#"><img class="float-left bg-primary img-fluid" style="max-width:100%;height: 75px;" src="../includes/themes/<?php echo $theme_name; ?>/images/<?php echo $ds[ 'logo_pic' ]; ?>" alt=""></span>
                </a>
             
            <button class="navbar-toggler bg-info" type="button" data-bs-toggle="collapse" data-bs-target="#mob-navbar" aria-label="Toggle">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mob-navbar">
                <ul class="navbar-nav mb-2 mb-lg-0 mx-auto">
                    <?php include("./includes/modules/navigation.php"); ?>
                    <?php include("./includes/modules/navigation_login.php"); ?>
                    <?php #include("./includes/modules/language.php"); ?>
                </ul>
            </div>
        </div>
    </nav>


    

<!--<nav class="navbar navbar-expand-lg navbar-default bg-primary fixed-top">-->

</header>

<?php
        } ?>