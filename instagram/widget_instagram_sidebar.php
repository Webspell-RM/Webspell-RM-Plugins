<script async src="//www.instagram.com/embed.js"></script>
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
\__________________________________________________________________*/
# Sprachdateien aus dem Plugin-Ordner laden
	$pm = new plugin_manager(); 
	$plugin_language = $pm->plugin_language("instagram", $plugin_path);

	$plugin_data= array();
    $plugin_data['$title']=$plugin_language['title'];
    $plugin_data['$subtitle']='instagram';
    $template = $GLOBALS["_template"]->loadTemplate("instagram","widget_head", $plugin_data, $plugin_path);
    echo $template;

    $sql = safe_query("SELECT * FROM ".PREFIX."plugins_instagram WHERE (igID <> '') AND displayed = '1' ORDER BY RAND() LIMIT 1");
    echo'<div class="card" style="background-color: transparent;"><div class="row" style="margin-right:0;margin-left:0;margin-top: 10px;">';
    while($ds = mysqli_fetch_array($sql)) {
    	$instagram_name = $ds['instagram_name'];
        $instagram_id = $ds['instagram_id'];
        #$instagram_title = $ds['instagram_title'];
        $instagram_height = $ds['instagram_height'];
        $insta_height = ($instagram_height - '195');

        if(isset($_COOKIE['im_instagram'])) {
           $instagram = '<div class="col-sm d-flex justify-content-center"><div data-service="instagram" data-title="instagram" style="height: '.$insta_height.'px; width: 300px" data-widget>
           <div data-placeholder data-visible style="transform: scale(0.9);margin-top: -34px!important;" class="d-flex justify-content-center">
           <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/reel/' . $instagram_id . '/?utm_source=ig_embed&amp;utm_campaign=loading"/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14" style="background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px;padding:0"></blockquote> </div></div></div>';
       } else {
           $instagram = '<div class="col-sm"><div data-service="instagram" data-title="instagram" style="height: '.$insta_height.'px; width: 300px" data-widget>
           <div data-placeholder data-visible>
           <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/reel/' . $instagram_id . '/?utm_source=ig_embed&amp;utm_campaign=loading"/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14" style="background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px;padding:0;"></blockquote>

           </div></div></div>';
       }            

        $data_array = array();
        $data_array['$instagram'] = $instagram;
        $template = $GLOBALS["_template"]->loadTemplate("instagram","content", $data_array, $plugin_path);
        echo $template;
    }
    echo'</div></div>';

?>