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

$_language->readModule('index');

$index_language = $_language->module;

$cookievalue = 'false';
if (isset($_COOKIE['ws_cookie'])) {
    $cookievalue = 'accepted';
}
header('X-UA-Compatible: IE=edge,chrome=1');

/* Components & themes css */
    echo $components_css;
    echo $theme_css;
/* Components & themes css END*/

// Verbindung zur Datenbank herstellen (falls nicht bereits verbunden)
#require_once 'db_connection.php';

// Theme-Daten abrufen
$result = safe_query("SELECT * FROM ".PREFIX."theme_settings WHERE id=1");
if(mysqli_num_rows($result) > 0) {
    $settings = mysqli_fetch_assoc($result);
} else {
    $settings = ['primary_color' => '#ffffff', 'secondary_color' => '#000000', 'background_color' => '#ffffff', 'font_family' => 'Arial, sans-serif', 'font_size' => '16px', 'text_color' => '#000000'];
}

$primary_color = htmlspecialchars($settings['primary_color'], ENT_QUOTES, 'UTF-8');
$secondary_color = htmlspecialchars($settings['secondary_color'], ENT_QUOTES, 'UTF-8');
$background_color = htmlspecialchars($settings['background_color'], ENT_QUOTES, 'UTF-8');
$font_family = htmlspecialchars($settings['font_family'], ENT_QUOTES, 'UTF-8');
$font_size = htmlspecialchars($settings['font_size'], ENT_QUOTES, 'UTF-8');
$text_color = htmlspecialchars($settings['text_color'], ENT_QUOTES, 'UTF-8');
?>

<style>
    :root {
        --primary-color: <?php echo $primary_color; ?>;
        --secondary-color: <?php echo $secondary_color; ?>;
        --background-color: <?php echo $background_color; ?>;
        --font-family: <?php echo $font_family; ?>;
        --font-size: <?php echo $font_size; ?>;
        --text-color: <?php echo $text_color; ?>;
    }
    
    body {
        background-color: var(--background-color);
        /*color: var(--secondary-color);*/
        font-family: var(--font-family);
        font-size: var(--font-size);
        color: var(--text-color);
    }
</style>
?>
<!DOCTYPE html>
<html class="h-100" lang="<?php echo $_language->language ?>">

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="keywords" content="<?php echo $keywords; ?>">
    <meta name="robots" content="all">
    <meta name="abstract" content="Anpasser an Webspell-RM">
    <meta name="copyright" content="Copyright &copy; 2018-2025 by webspell-rm.de">
    <meta name="author" content="webspell-rm.de">
    <meta name="revisit-After" content="1days">
    <meta name="distribution" content="global">
    <link rel="SHORTCUT ICON" href="./includes/expansion/<?php echo $theme_name; ?>/images/favicon.ico">

    <!-- Head & Title include -->
    <title><?= get_sitetitle(); ?></title>

    <base href="<?php echo $rewriteBase; ?>">

    <link type="application/rss+xml" rel="alternate" href="tmp/rss.xml" title="<?php echo $myclanname; ?> - RSS Feed">
    <link type="text/css" rel="stylesheet" href="./components/cookies/css/cookieconsent.css" media="print" onload="this.media='all'">
    <link type="text/css" rel="stylesheet" href="./components/cookies/css/iframemanager.css" media="print" onload="this.media='all'">
    <?php
    $lang = $_language->language; // Language Variable setzen         
    /* Components & themes css */
    #echo $components_css;
    #echo $theme_css;
    /* Components & themes css END*/
    /* Plugin-Manager  css */
    echo '<!--Plugin css-->';
    echo ($_pluginmanager->plugin_loadheadfile_css());
    echo '<!--Plugin css END-->' . chr(0x0D) . chr(0x0A);
    echo '<!--Widget css-->' . chr(0x0D);
    echo ($_pluginmanager->plugin_loadheadfile_widget_css());
    #echo get_plugin_loadheadfile_widget_css($css);
    echo '<!--Widget css END-->' . chr(0x0D) . chr(0x0A);
    /* Plugin-Manager  css END*/
    ?>
    <link type="text/css" rel="stylesheet" href="./includes/expansion/<?php echo $theme_name; ?>/css/stylesheet.css" />
</head>

<body>

    <div class="d-flex flex-column sticky-footer-wrapper"> <!-- flex -->
        <?php echo get_lock_modul(); ?>
        <!-- Header Modul-->
        <?php echo get_header_modul(); ?>
        <!-- Navigation Modul -->
        <?php echo get_navigation_modul(); ?>
        <!-- Content Head Modul-->
        <?php echo get_content_head_modul(); ?>
        <!-- content Head Modul End-->
        <main class="flex-fill"> <!-- flex -->
            <div class="container"> <!-- container -->
                <div class="row"> <!-- row -->
                    <!-- left column linke Spalte -->

                    <?php echo get_left_side_modul(); ?>

                    <!-- left column linke Spalte END -->
                    <!-- main content area -->
                    <div class="col">
                        <!-- above main content area -->
                        <?php echo get_content_up_modul(); ?>
                        <!-- main content area -->
                        <?php echo get_mainContent(); ?>
                        <!-- below main content area -->
                        <?php echo get_content_down_modul(); ?>
                    </div>
                    <!-- main content area END -->
                    <!-- right column rechte Spalte -->

                    <?php echo get_right_side_modul(); ?>

                    <!-- right column rechte Spalte END -->
                </div> <!-- row End -->
            </div> <!-- container-content End -->
        </main>
        <!-- Content Foot Modul -->
        <?php echo get_content_foot_modul(); ?>
        <!-- Footer Modul -->
        <?php echo get_footer_modul(); ?>
    </div> <!-- flex END -->
    <!-- scroll to top feature -->
    <div class="scroll-top-wrapper">
        <span class="scroll-top-inner">
            <i class="bi bi-arrow-up-circle" style="font-size: 2rem;"></i>
        </span>
    </div>
    <!-- scroll to top feature END -->
    <div class="cookies-wrapper">
        <span class="cookies-top-inner">
            <i class="bi bi-gear" style="font-size: 2rem;" data-cc="c-settings" data-toggle="tooltip" data-bs-title="Cookie settings"></i>
        </span>
    </div>

    <!-- recaptcha-->
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <?php
    /*  Components & themes js */
    echo $components_js;
    echo $theme_js;
    /*  Components & themes css / js  END */
    /* Plugin-Manager  js */
    echo '<!--Plugin js-->';
    echo ($_pluginmanager->plugin_loadheadfile_js());
    echo '<!--Plugin js END-->' . chr(0x0D) . chr(0x0A);
    echo '<!--Widget js-->' . chr(0x0D);
    echo ($_pluginmanager->plugin_loadheadfile_widget_js());
    echo '<!--Widget js END-->' . chr(0x0D) . chr(0x0A);
    /* Plugin-Manager  js END */
    /* ckeditor */
    echo get_editor();
    /* ckeditor END*/
    ?>
    <!-- Cookies Abfrage -->
    <script defer src="./components/cookies/js/iframemanager.js"></script>
    <script defer src="./components/cookies/js/cookieconsent.js"></script>
    <script defer src="./components/cookies/js/cookieconsent-init.js"></script>
    <script defer src="./components/cookies/js/app.js"></script>
    <!-- Language recognition for DataTables -->
    <script>
        const LangDataTables = <? echo "'$_language->language'"; ?>
    </script>
    <script type="text/javascript">
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>

</html>