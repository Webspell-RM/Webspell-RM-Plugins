<?php
/*¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯\
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
\¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*/

    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("gallery", $plugin_path);
	global $userID;	

	#$filepath = $plugin_path."images/thumb/";
	$filepath = $plugin_path."images/large/";

		$plugin_data= array();
    	$plugin_data['$title']=$plugin_language['gallery'];
    	$plugin_data['$subtitle']='Gallery';
    
		$template = $GLOBALS["_template"]->loadTemplate("gallery","widget_head", $plugin_data, $plugin_path);
        echo $template;


	$ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_gallery_groups");

	while($ds = mysqli_fetch_array($ergebnis)) {
		#$ergebnis = safe_query("SELECT galleryID, gallerycatID, name, info, banner FROM " . PREFIX . "plugins_gallery_pictures WHERE (banner <> '') AND (displayed = '1') ORDER BY RAND() LIMIT 1");
		$gallerys = mysqli_num_rows(safe_query("SELECT galleryID FROM ".PREFIX."plugins_gallery WHERE groupID='".$ds['groupID']."'"));
		 $db = mysqli_fetch_array(safe_query("SELECT * FROM ".PREFIX."plugins_gallery WHERE groupID='".$ds['groupID']."' ORDER BY galleryID DESC LIMIT 0,5"));
		 $pics = mysqli_num_rows(safe_query("SELECT picID FROM ".PREFIX."plugins_gallery as gal, ".PREFIX."plugins_gallery_pictures as pic WHERE gal.groupID='".$ds['groupID']."' AND gal.galleryID=pic.galleryID"));


	$ergebnis = safe_query("SELECT picID, name, views FROM " . PREFIX . "plugins_gallery_pictures WHERE (picID <> '') ORDER BY RAND() LIMIT 1");


		if (mysqli_num_rows($ergebnis)) {

			while ($dx = mysqli_fetch_array($ergebnis)) {

				//Datum ausgabe	
		$monate = array(1=>"Januar", 2=>"Februar", 3=>"März",  4=>"April", 5=>"Mai", 6=>"Juni", 7=>"Juli", 8=>"August", 9=>"September", 10=>"Oktober", 11=>"November", 12=>"Dezember");
		@$monat = date("n", $db['date']);
		@$day = date("d.", $db['date']);
		@$ger_monat = $monate[$monat];
		@$year = date("Y", $db['date']);
		//Datum ausgabe	ende

			$views = $dx['views'];
			$name = $dx['name'];
			

            if (file_exists('./includes/plugins/gallery/images/large/' . $dx[ 'picID' ] . '.jpg')) {
            	$file = './includes/plugins/gallery/images/large/' . $dx[ 'picID' ] . '.jpg';
	        } elseif (file_exists('./includes/plugins/gallery/images/large/' . $dx[ 'picID' ] . '.gif')) {
	            $file = './includes/plugins/gallery/images/large/' . $dx[ 'picID' ] . '.gif';
	        } elseif (file_exists('./includes/plugins/gallery/images/large/' . $dx[ 'picID' ] . '.png')) {
	            $file = './includes/plugins/gallery/images/large/' . $dx[ 'picID' ] . '.png';
	        } else {
	            $file = '';
	        }

	        if ($dx[ 'picID' ]) {
                $banner = '<a class="thumbnail" href="index.php?site=gallery&picID='.$dx[ 'picID' ].'">
                    <img class="img-fluid" style="max-width: 100%;border-radius: var(--bs-border-radius);"                         
                         src="' . $file . '"
                         alt="' . $dx[ 'name' ] . '"></a>';
            } else {
                $banner = '';
            }

            

			$data_array = array();
			$data_array['$name'] = $name;
			$data_array['$banner'] = $banner;

			$data_array['$gallerys'] = $gallerys;
			$data_array['$pics'] = $pics;

			$data_array['$day'] = $day;
        	$data_array['$ger_monat'] = $ger_monat;
        	$data_array['$year'] = $year;
        	#$data_array['$views'] = $views;
        	$data_array['$views'] = $views;

			$data_array['$lang_title'] = $plugin_language['gallery'];
	        $data_array['$lang_galleries'] = $plugin_language['galleries'];
	        $data_array['$lang_pictures'] = $plugin_language['pictures'];
	        $data_array['$lang_date'] = $plugin_language['date'];
	        $data_array['$lang_views'] = $plugin_language['views'];

        	$template = $GLOBALS["_template"]->loadTemplate("gallery","widget_gallery", $data_array, $plugin_path);
        	echo $template;
		

	}
   
		} else {
			
			echo'<div class="card"><div class="row" style="margin-top: 25px;margin-bottom: 0px;margin-left: 0px;margin-right: 0px"><div class="col-md text-center">Images not found<br><br></div></div></div>';  
	}
}
	?>

	