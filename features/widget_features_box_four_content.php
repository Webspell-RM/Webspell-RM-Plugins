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
$plugin_language = $pm->plugin_language("features", $plugin_path);


	$filepath = "/includes/plugins/gallery/images/large/";

	$data_array = array();
	$template = $GLOBALS["_template"]->loadTemplate("features","box_four_head", $data_array, $plugin_path);
	echo $template;

	$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_about_us");
if (mysqli_num_rows($ergebnis)) {
    $ds = mysqli_fetch_array($ergebnis);
    $title = $ds[ 'title' ];
    $text = $ds[ 'text' ];
    
    $translate = new multiLanguage(detectCurrentLanguage());
    $translate->detectLanguages($title);
    $title = $translate->getTextByLanguage($title);
    $translate->detectLanguages($text);
    $text = $translate->getTextByLanguage($text);
    
    #$maxaboutchars = $ds[ 'aboutchars' ];
    if (empty($maxaboutchars)) {
	    $maxaboutchars = 160;
    }  
    
    $text = preg_replace("/<div>/", "", $text);
    $text = preg_replace("/<p>/", "", $text);
    $text = preg_replace("/<strong>/", "", $text);
    $text = preg_replace("/<em>/", "", $text);
    $text = preg_replace("/<s>/", "", $text);
    $text = preg_replace("/<u>/", "", $text);
    $text = preg_replace("/<blockquote>/", "", $text);

    $text = preg_replace("//", "", substr( $text, 0, $maxaboutchars  ) ) . ' ... ';

	$data_array = array();
	$data_array['$title'] = $title;
    $data_array['$text'] = $text;
    $data_array['$title_small'] = '';

	$template = $GLOBALS["_template"]->loadTemplate("features","box_four_content_about", $data_array, $plugin_path);
	echo $template;
} else {
	$data_array = array();
	$data_array['$title'] = '';
    $data_array['$text'] = '';
    $data_array['$title_small'] = '';
	$data_array['$no_about'] = $plugin_language['no_about'];
    $template = $GLOBALS["_template"]->loadTemplate("features","box_four_content_about_no", $data_array, $plugin_path);
	echo $template;
}
#################################

#########################
$ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_gallery_groups");
if (mysqli_num_rows($ergebnis)) {
	while($ds = mysqli_fetch_array($ergebnis)) {
	$gallerys = mysqli_num_rows(safe_query("SELECT galleryID FROM ".PREFIX."plugins_gallery WHERE groupID='".$ds['groupID']."'"));
	$db = mysqli_fetch_array(safe_query("SELECT * FROM ".PREFIX."plugins_gallery WHERE groupID='".$ds['groupID']."' ORDER BY galleryID DESC LIMIT 0,5"));
	$pics = mysqli_num_rows(safe_query("SELECT youtube FROM ".PREFIX."plugins_gallery as gal, ".PREFIX."plugins_gallery_pictures as pic WHERE gal.groupID='".$ds['groupID']."' AND gal.galleryID=pic.galleryID"));

	$ergebnis = safe_query("SELECT youtube, name, views FROM " . PREFIX . "plugins_gallery_pictures WHERE (youtube <> '') AND pic_video= '1' ORDER BY RAND() LIMIT 1");

		if (mysqli_num_rows($ergebnis)) {

			while ($dx = mysqli_fetch_array($ergebnis)) {
				$name = $dx['name'];
				if(mb_strlen($name) > 14) {
					$name = mb_substr($name, 0, 14);
					$name .= '...';
				}

				$videoID = $dx['youtube'];
					
				#if ($dx[ 'picID' ]) {
	                $preview = '<div class="ratio ratio-16x9">
  <iframe src="https://www.youtube.com/embed/'.$videoID.'?rel=0" title="YouTube video" allowfullscreen style="border-radius: var(--bs-border-radius);"></iframe>
</div>';
	            #} else {
	            #    $banner = '</div>';
	            #}

				$data_array = array();
		$data_array['$videoname'] = $name;
		$data_array['$preview'] = $preview;
        		$data_array['$lang_title_video'] = $plugin_language['title_video'];	

				$template = $GLOBALS["_template"]->loadTemplate("features","box_four_content_video", $data_array, $plugin_path);
				echo $template;
			}
		}
	}


/*
$ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_videos WHERE widget_displayed = '1' ORDER BY RAND() LIMIT 1");
if (mysqli_num_rows($ergebnis)) {
	$n=1;
	while($ds=mysqli_fetch_array($ergebnis)) {
		$videoID = $ds['youtube'];

		$preview = '<div class="ratio ratio-16x9">
  <iframe src="https://www.youtube.com/embed/'.$videoID.'?rel=0" title="YouTube video" allowfullscreen style="border-radius: var(--bs-border-radius);"></iframe>
</div>';
		
		$videoname = $ds['videoname'];
		$videoscatID = $ds['videoscatID'];
		$videosID = $ds['videosID'];
		
		if(mb_strlen($videoname) > 14) {
			$videoname = mb_substr($videoname, 0, 14);
			$videoname .= '...';
		}
		$cat = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_videos_categories WHERE videoscatID = '". $ds['videoscatID']."' ORDER BY catname"));
		
		$data_array = array();
		$data_array['$videoname'] = $videoname;
		$data_array['$preview'] = $preview;

		$data_array['$lang_title_video'] = $plugin_language['title_video'];			

		$template = $GLOBALS["_template"]->loadTemplate("features","box_four_content_video", $data_array, $plugin_path);
		echo $template;
	}*/
} else {
	$data_array = array();
	$data_array['$no_videos'] = $plugin_language['no_videos'];
    $template = $GLOBALS["_template"]->loadTemplate("features","box_four_content_video_no", $data_array, $plugin_path);
	echo $template;
}






#########################
$ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_gallery_groups");
if (mysqli_num_rows($ergebnis)) {
	while($ds = mysqli_fetch_array($ergebnis)) {
	$gallerys = mysqli_num_rows(safe_query("SELECT galleryID FROM ".PREFIX."plugins_gallery WHERE groupID='".$ds['groupID']."'"));
	$db = mysqli_fetch_array(safe_query("SELECT * FROM ".PREFIX."plugins_gallery WHERE groupID='".$ds['groupID']."' ORDER BY galleryID DESC LIMIT 0,5"));
	$pics = mysqli_num_rows(safe_query("SELECT picID FROM ".PREFIX."plugins_gallery as gal, ".PREFIX."plugins_gallery_pictures as pic WHERE gal.groupID='".$ds['groupID']."' AND gal.galleryID=pic.galleryID"));

	$ergebnis = safe_query("SELECT picID, name, views FROM " . PREFIX . "plugins_gallery_pictures WHERE (picID <> '') AND pic_video= '0' ORDER BY RAND() LIMIT 1");

		if (mysqli_num_rows($ergebnis)) {

			while ($dx = mysqli_fetch_array($ergebnis)) {
				$name = $dx['name'];
				if(mb_strlen($name) > 14) {
					$name = mb_substr($name, 0, 14);
					$name .= '...';
				}
					
				if ($dx[ 'picID' ]) {
	                $banner = '<a class="thumbnail" href="index.php?site=gallery&picID='.$dx[ 'picID' ].'">
		                    <img class="img-fluid" style="max-width: 100%; height:auto;border-radius: var(--bs-border-radius);"
		                         src="' . $filepath . $dx[ 'picID' ] . '.jpg"
		                         alt="' . $dx[ 'name' ] . '"></a>';
	            } else {
	                $banner = '</div>';
	            }

				$data_array = array();
				$data_array['$gallery_name'] = $name;
				$data_array['$banner'] = $banner;
	        	$data_array['$title'] = $title;
	        	$data_array['$text'] = $text;
        		$data_array['$lang_title_gallery'] = $plugin_language['title_gallery'];

				$template = $GLOBALS["_template"]->loadTemplate("features","box_four_content_gallery", $data_array, $plugin_path);
				echo $template;
			}
		}
	}
} else {
	$data_array = array();
	$data_array['$no_gallery'] = $plugin_language['no_gallery'];
    $template = $GLOBALS["_template"]->loadTemplate("features","box_four_content_gallery_no", $data_array, $plugin_path);
	echo $template;
}

	$data_array = array();
	$template = $GLOBALS["_template"]->loadTemplate("features","box_four_foot", $data_array, $plugin_path);
	echo $template;

	
