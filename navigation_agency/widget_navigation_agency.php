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
    GLOBAL $site,$logo,$theme_name,$themes,$tpl,$loggedin,$index_language,$modRewrite;

    $ergebnis=safe_query("SELECT * FROM ".PREFIX."settings_themes WHERE active = 1");
        $ds=mysqli_fetch_array($ergebnis);

        $ergebnis_site=safe_query("SELECT * FROM " . PREFIX . "settings_module WHERE modulname='".@$_GET['site']."'");
        $dx=mysqli_fetch_array($ergebnis_site);
        
        if (@$dx[ 'modulname' ] != 'startpage' && @$dx[ 'modulname' ] != '') {
            ?>
            <style type="text/css">#navigation_modul {margin-top: 0px;margin-bottom: 0px;}</style>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
            <div class="container-lg" style="padding-bottom: 5px;margin-bottom: -10px;">
                <span class="logo">
                    <a class="navbar-brand" href="#">
                        <img class="float-left bg-primary img-fluid" style="max-width:100%;height: 55px;" src="../includes/themes/<?php echo $theme_name; ?>/images/<?php echo $ds[ 'logo_pic' ]; ?>" alt="">
                    </a>
                </span>
                <div class="box">
                    <span class="webspell"><?php echo $ds[ 'logotext1' ];?></span>
                    <span class="slogan"><?php echo $ds[ 'logotext2' ];?></span>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarColor01">
                    <ul class="navbar-nav <?php echo $ds[ 'nav_text_alignment' ];?>">
                        <?php include("./includes/modules/navigation.php"); ?>
                        <?php include("./includes/modules/navigation_login.php"); ?>
                        <?php include("./includes/modules/language_flag.php"); ?>        
                    </ul>
                </div>
            </div>
        </nav>
        <?php
        } else {
        ?>

          <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
            <div class="container logo-box">
                <span class="logo"><a class="navbar-brand" href="#"><img class="float-left bg-primary img-fluid" style="max-width:100%;height: 55px;" src="../includes/themes/<?php echo $theme_name; ?>/images/<?php echo $ds[ 'logo_pic' ]; ?>" alt=""></span>
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
                        <?php include("./includes/modules/language_flag.php"); ?>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Masthead-->
        <header class="masthead">
            <div class="container">
                <div class="masthead-subheading">Welcome To Our Studio!</div>
                <div class="masthead-heading text-uppercase">It's Nice To Meet You</div>
                <a class="btn btn-primary btn-xl text-uppercase" href="#">Tell Me More</a>
            </div>
        </header>

<?php
        } ?>