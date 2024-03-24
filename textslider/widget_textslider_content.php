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

$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("textslider", $plugin_path);

$filepath = $plugin_path."images/";

$data_array = array();
$data_array['$title']=$plugin_language['title'];
$data_array['$subtitle']='Textslider';
$template = $GLOBALS["_template"]->loadTemplate("textslider","title", $data_array, $plugin_path);
echo $template; 

$template = $GLOBALS["_template"]->loadTemplate("textslider","head", $data_array, $plugin_path);
echo $template;        
 
$carousel = safe_query("SELECT * FROM " . PREFIX . "plugins_textslider WHERE (displayed = '1') ORDER BY sort");
echo '<div id="textsliderExampleControls" class="caro1usel caro1usel-plugin slide textslider" data-bs-ride="carousel">
<!-- Indicators -->
    <div class="carousel-indicators textslider-indicators">';
       if(mysqli_num_rows($carousel)) {
           for($i=0; $i<=(mysqli_num_rows($carousel)-1); $i++) {
               if($i==0) {
                    echo '<button type="button" data-bs-target="#textsliderExampleControls" data-bs-slide-to="'.$i.'" class="active" aria-current="true" aria-label="Slide 1"></button>';
               } else {
                    echo '<button type="button" data-bs-target="#textsliderExampleControls" data-bs-slide-to="'.$i.'" class="deactive" aria-label="Slide 1"></button>';
               }
           }       
       }    
echo'</div>
    <!-- Wrapper for slides -->
    <div class="carousel-inner">';
$x = 1;
if (mysqli_num_rows($carousel)) {
    while ($db = mysqli_fetch_array($carousel)) {
        $title=""; $link=""; $description="";
        if($x==1) { echo '<div class="carousel-item active">'; } else { echo '<div class="carousel-item">'; }
        if (!empty($db[ 'carousel_pic' ])) {
            $carousel_pic = '<img src="' . $filepath . $db[ 'carousel_pic' ] . '" alt="' . htmlspecialchars($db[ 'title' ]) .
                '" class="img-fluid img-mobile">';
        } else {
            $title = $db[ 'title' ];
        }
        $carouselID = $db[ 'carouselID' ];
        $title = $db[ 'title' ];
        $link = $db[ 'link' ];
        $ani_title = $db[ 'ani_title' ];
        $ani_link = $db[ 'ani_link' ];
        $ani_description = $db[ 'ani_description' ];
        $title = $db[ 'title' ];
        $description = $db[ 'description' ];
        
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($title);
        $title = $translate->getTextByLanguage($title);
        $translate->detectLanguages($description);
        $description = $translate->getTextByLanguage($description);

        $maxtextsliderchars = '560';
        if (empty($maxtextsliderchars)) {
        $maxtextsliderchars = 60;
        }  
        
        $description = preg_replace("/<div>/", "", $description);
        $description = preg_replace("/<p>/", "", $description);
        $description = preg_replace("/<strong>/", "", $description);
        $description = preg_replace("/<em>/", "", $description);
        $description = preg_replace("/<s>/", "", $description);
        $description = preg_replace("/<u>/", "", $description);
        $description = preg_replace("/<blockquote>/", "", $description);

        $description = preg_replace("//", "", substr( $description, 0, $maxtextsliderchars  ) ) . ' ... ';    
    
        $data_array = array();
        $data_array['$carouselID'] = $carouselID;
        $data_array['$carousel_pic'] = $carousel_pic;
        $data_array['$title'] = $title;
        $data_array['$ani_title'] = $ani_title;
        $data_array['$ani_link'] = $ani_link;
        $data_array['$ani_description'] = $ani_description;
        $data_array['$link'] = $link;
        $data_array['$description'] = $description;        
        
        $template = $GLOBALS["_template"]->loadTemplate("textslider","content", $data_array, $plugin_path);
        echo $template;

       echo '</div>'; $x++;
    }
echo '</div></div>';    
}
 

$template = $GLOBALS["_template"]->loadTemplate("textslider","foot", $data_array, $plugin_path);
echo $template; 
