<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
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
$_lang = $pm->plugin_language("gallery", $plugin_path);

$settings = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_settings");
$db = mysqli_fetch_array($settings);
$pic_side_by_side = $db['port_img_per_page'];

$lim = @$db['port_max_img'];

$data_array = array();
$data_array['$title'] = $_lang['portfolio'];
$data_array['$subtitle'] = 'Portfolio';

$template = $GLOBALS["_template"]->loadTemplate("gallery", "portfolio_title_head", $data_array, $plugin_path);
echo $template;



$cats = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery WHERE displayed_port = '1' AND userID = '0' ORDER BY name");
if (mysqli_num_rows($cats)) {

    $anzcats = mysqli_num_rows(safe_query("SELECT galleryID FROM " . PREFIX . "plugins_gallery WHERE displayed_port = '1' AND userID = '0'"));

    $allportfoliocatname = '<li data-filter="*">ALL</li>';

    $data_array = array();
    $data_array['$anzcats'] = $anzcats;
    $data_array['$lang_all'] = $_lang['all'];
    $data_array['$allportfoliocatname'] = $allportfoliocatname;

    $template = $GLOBALS["_template"]->loadTemplate("gallery", "portfolio_cat_all_head", $data_array, $plugin_path);
    echo $template;

    while ($dx = mysqli_fetch_array($cats)) {

        $anzportfolio = mysqli_num_rows(
            safe_query(
                "SELECT
                        *
                    FROM
                        " . PREFIX . "plugins_gallery_pictures 
                    WHERE
                        galleryID='" . $dx['galleryID'] . "'"
            )
        );



        $catname = $dx['name'];
        $portfoliocatID = $dx['galleryID'];

        $portfoliocatname = '<li data-filter=".filter-' . $portfoliocatID . '" value="' . $catname . '">' . $catname . '</li>';



        $data_array = array();
        $data_array['$portfoliocatname'] = $portfoliocatname;
        $data_array['$anzportfolio'] = $anzportfolio;
        $data_array['$portfoliocatID'] = $portfoliocatID;
        $data_array['$catname'] = $catname;


        $template = $GLOBALS["_template"]->loadTemplate("gallery", "portfolio_cat_button", $data_array, $plugin_path);
        echo $template;
    }


    $template = $GLOBALS["_template"]->loadTemplate("gallery", "portfolio_cat_all_foot", $data_array, $plugin_path);
    echo $template;

    $template = $GLOBALS["_template"]->loadTemplate("gallery", "portfolio_content_head", $data_array, $plugin_path);
    echo $template;



    #Anzahl der Bilder muss noch weiter getestet werden
    $result = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_pictures WHERE displayed_port = '1'");
    $anzahl = mysqli_num_rows($result);
    #echo "Anzahl Daten in Tabelle: ".$anzahl." <br>"; 

    /*if (isset($_POST['pic'])){
            $id=htmlspecialchars($_POST['pic']);
        } else {
            $id='';
        }

        if($id=$id){
            $gall= "WHERE galleryID= ".$id." AND displayed_port = '1' ORDER BY RAND() LIMIT $lim";
        }else{
            #$gall="WHERE displayed_port = '1' ORDER BY RAND() LIMIT $lim";
            $gall="WHERE displayed_port = '1' ORDER BY RAND()";
        }*/

    #$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_pictures $gall");
    #$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_pictures WHERE displayed_port = '1' AND pic_video = ".$media." ORDER BY RAND() LIMIT $anzahl");
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_pictures WHERE displayed_port = '1' ORDER BY RAND() LIMIT $anzahl");

    while ($dd = mysqli_fetch_array($ergebnis)) {


        $media = $dd['pic_video'];
        if ($media == '0') {
            if (file_exists('./includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.jpg')) {
                $file = './includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.jpg';
            } elseif (file_exists('./includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.gif')) {
                $file = './includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.gif';
            } elseif (file_exists('./includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.png')) {
                $file = './includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.png';
            } elseif (file_exists('./includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.webp')) {
                $file = './includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.webp';
            } elseif (file_exists('./includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.avif')) {
                $file = './includes/plugins/gallery/images/thumb/' . $dd['picID'] . '.avif';
            } else {
                $file = '';
            }

            if (file_exists('./includes/plugins/gallery/images/large/' . $dd['picID'] . '.jpg')) {
                $file_1 = './includes/plugins/gallery/images/large/' . $dd['picID'] . '.jpg';
            } elseif (file_exists('./includes/plugins/gallery/images/large/' . $dd['picID'] . '.gif')) {
                $file_1 = './includes/plugins/gallery/images/large/' . $dd['picID'] . '.gif';
            } elseif (file_exists('./includes/plugins/gallery/images/large/' . $dd['picID'] . '.png')) {
                $file_1 = './includes/plugins/gallery/images/large/' . $dd['picID'] . '.png';
            } elseif (file_exists('./includes/plugins/gallery/images/large/' . $dd['picID'] . '.webp')) {
                $file_1 = './includes/plugins/gallery/images/large/' . $dd['picID'] . '.webp';
            } elseif (file_exists('./includes/plugins/gallery/images/large/' . $dd['picID'] . '.avif')) {
                $file_1 = './includes/plugins/gallery/images/large/' . $dd['picID'] . '.avif';
            } else {
                $file_1 = '';
            }
        } else {

            $videoID = $dd['youtube'];
            $name = $dd['name'];
            $preview = 'https://img.youtube.com/vi/' . $videoID . '/hqdefault.jpg';
            $video = '<img class="card-img-top" src="' . $preview . '" alt="' . $name . '" style="border-top-left-radius: 0px;border-top-right-radius: 0px;">';
        }

        if (isset($_POST['name'])) {
            $name = htmlspecialchars($_POST['name']);
        } else {
            $name = '';
        }


        #$bergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery_pictures WHERE galleryID= '12' AND displayed_port = '1' ORDER BY RAND() LIMIT 3");

        #while($aa=mysqli_fetch_array($bergebnis)) {


        $aergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_gallery WHERE galleryID = " . $dd['galleryID'] . "");

        while ($db = mysqli_fetch_array($aergebnis)) {


            if ($media == '0') {
                $banner = '<div class="col-' . $pic_side_by_side . ' portfolio-item filter-' . $db['galleryID'] . '">
                                    <div class="portfolio-wrap card">
                                        <img src="' . $file . '" class="img-fluid" alt="">
                                        <div class="portfolio-info">
                                            <h4>' . $db['name'] . '</h4>
                                            <p>' . $dd['name'] . '</p>
                                            <a href="index.php?site=gallery&picID=' . $dd['picID'] . '" title="LINK zur Galerie" data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                            <a data-fancybox data-src="' . $file_1 . '"  data-caption="' . $db['name'] . '" title="LINK zum BILD" class="details-link"><i class="bi bi-link-45deg"></i></a>
                                        </div>
                                    </div>
                                </div>';
            } else {

                $banner = '<div class="col-' . $pic_side_by_side . ' portfolio-item filter-' . $db['galleryID'] . '">
                                    <div class="portfolio-wrap card">
                                    ' . $video . '
                                    <div class="portfolio-info">
                                            <h4>' . $db['name'] . '</h4>
                                            <p>' . $dd['name'] . '</p>
                                            <a href="index.php?site=gallery&picID=' . $dd['picID'] . '" title="LINK zur Galerie" data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                            
                                        </div>
                                    </div>
                                </div>';
            }
        }

        $data_array = array();
        $data_array['$portfolioID'] = $dd['picID'];
        $data_array['$banner'] = $banner;

        $template = $GLOBALS["_template"]->loadTemplate("gallery", "portfolio_content", $data_array, $plugin_path);
        echo $template;
    }
    $template = $GLOBALS["_template"]->loadTemplate("gallery", "portfolio_content_foot", $data_array, $plugin_path);
    echo $template;
}
