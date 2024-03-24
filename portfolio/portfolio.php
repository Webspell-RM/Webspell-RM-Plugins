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
    $_lang = $pm->plugin_language("portfolio", $plugin_path);

$filepath = $plugin_path."images/";

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

    $data_array = array();
    $data_array['$title']=$_lang['portfolio'];
    $data_array['$subtitle']='Portfolio';

    $template = $GLOBALS["_template"]->loadTemplate("portfolio","title_head", $data_array, $plugin_path);
    echo $template;

if ($action == "show" && is_numeric($_GET[ 'portfoliocatID' ])) {

    $portfolioID = $_GET[ 'portfolioID' ];
    $getcat = safe_query("SELECT * FROM " . PREFIX . "plugins_portfolio  WHERE portfolioID='$portfolioID'");
    $ds = mysqli_fetch_array($getcat);
    $catname = $ds[ 'catname' ];

    $linkcat = safe_query("SELECT * FROM " . PREFIX . "plugins_portfolio WHERE portfolioID='$portfolioID' ORDER BY catname");

    if (mysqli_num_rows($linkcat)) {
        $data_array = array();
        $data_array['$catname'] = $catname;

        
    } 
} else {

    
    $cats = safe_query("SELECT * FROM " . PREFIX . "plugins_portfolio_categories ORDER BY catname");
    if (mysqli_num_rows($cats)) {
        
        $anzcats = mysqli_num_rows(safe_query("SELECT portfoliocatID FROM " . PREFIX . "plugins_portfolio_categories"));

        $data_array = array();
        $data_array['$anzcats'] = $anzcats;
        $data_array['$lang_all']=$_lang['all'];

        $template = $GLOBALS["_template"]->loadTemplate("portfolio","category", $data_array, $plugin_path);
        echo $template;

        while ($ds = mysqli_fetch_array($cats)) {
            $anzportfolio = mysqli_num_rows(
                safe_query(
                    "SELECT
                        portfolioID
                    FROM
                        " . PREFIX . "plugins_portfolio
                    WHERE
                        portfoliocatID='" . $ds[ 'portfoliocatID' ] . "'"
                )
            );

            $catname = $ds['catname'];
            $portfoliocatID = $ds['portfoliocatID'];

            $portfoliocatname =
                '<button class="btn btn-primary filter-button" role="button" data-filter="'.$ds['portfoliocatID'].'" style="margin-top: 5px;">' .
                $catname . '<small> (Portfolio: ' . $anzportfolio .')</small></button>';


            
            #$portfoliocatname =
            #    '<button class="btn btn-primary filter-button" data-filter="'.$ds['portfoliocatID'].'" >' .
            #    $ds[ 'catname' ] . ' ( ' . $anzportfolio .' )</button>';


            $data_array = array();
            $data_array['$portfoliocatname'] = $portfoliocatname;
            $data_array['$anzportfolio'] = $anzportfolio;
            $data_array['$portfoliocatID'] = $portfoliocatID;
            $data_array['$catname'] = $catname;


            $template = $GLOBALS["_template"]->loadTemplate("portfolio","content", $data_array, $plugin_path);
            echo $template;

        }

        $template = $GLOBALS["_template"]->loadTemplate("portfolio","foot", $data_array, $plugin_path);
        echo $template;

        $data_array= array();
        
        $template = $GLOBALS["_template"]->loadTemplate("portfolio","cat_all", $data_array, $plugin_path);
        echo $template;

        $template = $GLOBALS["_template"]->loadTemplate("portfolio","cat_head", $data_array, $plugin_path);
            echo $template;
        
        $n=1;

       #$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_portfolio ORDER BY portfolioID");
       $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_portfolio ORDER BY RAND()");

       
        while($ds=mysqli_fetch_array($ergebnis)) {

            $query = safe_query(
            "SELECT
                portfolioID
            FROM
                " . PREFIX . "plugins_portfolio 
            "
        );

            if ($ds[ 'banner' ]) {
                $banner = '<a class="" href="#" data-image-id="" data-toggle="modal" data-title=""
                   data-image="' . $filepath . $ds[ 'banner' ] . '"
                   data-target="#image-gallery">
                    <img class=""
                         src="' . $filepath . $ds[ 'banner' ] . '"
                         alt="' . $ds[ 'name' ] . '"></a>';
            } else {
                $banner = '';
            }

            $pic = $ds[ 'banner' ];

            $data_array = array();
            $data_array['$name'] = $ds['name'];
            $data_array['$text'] = $ds['text'];
            $data_array['$url'] = $ds['url'];
            $data_array['$pic'] = $pic;
            $data_array['$portfolioID'] = $ds['portfolioID'];
            $data_array['$portfoliocatID'] = $ds['portfoliocatID'];
            $data_array['$banner'] = $banner;

            #$cat = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_portfolio_categories WHERE portfoliocatID = '". $ds['portfoliocatID']."'"));
            #$data_array['$catname'] = $cat['catname']; #der name $portfoliocatname von der DB portfolio_categorys in die html übermitteln passend zum Bild 

            $cat = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_portfolio_categories WHERE portfoliocatID = '". $ds['portfoliocatID']."' ORDER BY catname"));
            $data_array['$catname'] = $cat['catname']; 

            $template = $GLOBALS["_template"]->loadTemplate("portfolio","cat", $data_array, $plugin_path);
            echo $template;

             $n++;

    }

            $template = $GLOBALS["_template"]->loadTemplate("portfolio","cat_foot", $data_array, $plugin_path);
            echo $template;


    } else {
        
        echo $_lang[ 'no_categories' ];
    }
}
