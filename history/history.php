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
	$plugin_language = $pm->plugin_language("history", $plugin_path);


if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}


if($action=="show"){
    $historyID = $_GET['historyID'];
    if(isset($historyID)){


	#title
	$_language->readModule('history');
	$plugin_data= array();
    $plugin_data['$title']=$plugin_language['history'];
    $plugin_data['$subtitle']='History';

    $template = $GLOBALS["_template"]->loadTemplate("history","head", $plugin_data, $plugin_path);
    echo $template;


    $data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("history","show_header", $data_array, $plugin_path);
    echo $template;

	$get=safe_query("SELECT * FROM ".PREFIX."plugins_history WHERE historyID='".$historyID."' LIMIT 0,1");
        $ds=mysqli_fetch_array($get);

 
        $historyID = $ds[ 'historyID' ];


		$poster = '<a href="index.php?site=profile&amp;id=' . $ds[ 'poster' ] . '">
            <strong>' . getnickname($ds[ 'poster' ]) . '</strong>
        </a>';

			$title = $ds[ 'title' ];
			$text = $ds[ 'text' ];
    
    		$translate = new multiLanguage(detectCurrentLanguage());
    		$translate->detectLanguages($title);
    		$title = $translate->getTextByLanguage($title);
    		$translate->detectLanguages($text);
    		$text = $translate->getTextByLanguage($text);
    
    		$data_array = array();
			$data_array['$title'] = $title;
			$data_array['$text'] = $text;
			$data_array['$date'] = $date;
        	$data_array['$poster'] = $poster;
        	$data_array['$info']= $plugin_language[ 'info' ];
			$data_array['$stand']= $plugin_language[ 'stand' ];

        $history = $GLOBALS["_template"]->loadTemplate("history","show_content_area", $data_array, $plugin_path);
        echo $history;
		
	} else {
		echo $plugin_language['no_history'];
	} 
	$data_array = array();
    	$template = $GLOBALS["_template"]->loadTemplate("history","show_foot", $data_array, $plugin_path);
    	echo $template;   	



} else {

if ($action == "") {

if(isset($_GET['page'])) $page=(int)$_GET['page'];
  else $page = 1;    	

	#title
	$_language->readModule('history');
	$plugin_data= array();
    $plugin_data['$title']=$plugin_language['history'];
    $plugin_data['$subtitle']='History';

    
	$template = $GLOBALS["_template"]->loadTemplate("history","head", $plugin_data, $plugin_path);
    echo $template;

	$alle=safe_query("SELECT historyID FROM ".PREFIX."plugins_history WHERE displayed = '1'");
  	$gesamt = mysqli_num_rows($alle);
  	$pages=1;

  
  	$settings = safe_query("SELECT * FROM " . PREFIX . "plugins_history_settings");
        $dn = mysqli_fetch_array($settings);

    
        $max = $dn[ 'history' ];
        if (empty($max)) {
        $max = 1;
        }
 

  	for ($n=$max; $n<=$gesamt; $n+=$max) {
    	if($gesamt>$n) $pages++;
  	}


  	if($pages>1) $page_link = makepagelink("index.php?site=history", $page, $pages);
    	else $page_link='';

  	if ($page == "1") {
    	$ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_history WHERE displayed = '1' ORDER BY `sort` LIMIT 0,$max");
    	$n=1;
  	}
  	else {
    	$start=$page*$max-$max;
   		$ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_history WHERE displayed = '1' ORDER BY `sort` LIMIT $start,$max");
    	$n = ($gesamt+1)-$page*$max+$max;
  	} 



$ds = safe_query("SELECT * FROM `" . PREFIX . "plugins_history` ORDER BY `sort`");
	$anzcats = mysqli_num_rows($ds);
    if ($anzcats) {

	function random_hex_color() {
	    return sprintf("%02X%02X%02X", mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
	}
		
	$align = "right";
	$data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("history","header", $data_array, $plugin_path);
    echo $template;

        $n=1; 
        while ($ds = mysqli_fetch_array($ergebnis)) {


			$poster = '<a href="index.php?site=profile&amp;id=' . $ds[ 'poster' ] . '"><strong>' . getnickname($ds[ 'poster' ]) . '</strong></a>';

			$title = $ds[ 'title' ];
			$text = $ds[ 'text' ];
			$time = getformatdatetime($ds[ 'date' ]);
    
    		$translate = new multiLanguage(detectCurrentLanguage());
    		$translate->detectLanguages($title);
    		$title = $translate->getTextByLanguage($title);
    		$translate->detectLanguages($text);
    		$text = $translate->getTextByLanguage($text);

			$historyID = [];

			for ($i = 0; $i < 1; $i++) {
			    if (!isset($i[$historyID])) {
			        $historyID[$i] = random_hex_color();
			    }



    #echo"<div class=\" col text-" . $align . "\">" . $title . "<br />".$text."</div><hr>";

    				/* echo'<div class="timeline '.$align.'">
        <div class="ca1rd" style="border: 1px solid #'.$historyID[$i].'">
          <div class="card-body p-4">
            <h3>'.$title.'</h3>
            <p class="mb-0">'.$text.'</p>
          </div>
        </div>
      </div>';*/

    
	       	if ($align == "left") {
	         	$align = "right";
	         	$border="success";
	       	} else {
	         	$align = "left";
	         	$border="danger";
	       	} 

    		
    		$data_array = array();
    		$data_array['$clanrulesID'] = $historyID[$i];
    		$data_array['$align'] = $align;
			$data_array['$title'] = $title;
			$data_array['$text'] = $text;
        	$data_array['$date'] = $time;
        	$data_array['$poster'] = $poster;
        	$data_array['$info']= $plugin_language[ 'info' ];
			$data_array['$stand']= $plugin_language[ 'stand' ];

			
		
	        $template = $GLOBALS["_template"]->loadTemplate("history","content_area", $data_array, $plugin_path);
	        echo $template;
			}        
        	$n++;
        
        }

        $template = $GLOBALS["_template"]->loadTemplate("history","foot", $data_array, $plugin_path);
        echo $template;
        
		 } else {
        echo $plugin_language['no_history'];
   
	
  }
  
  echo'<br>';
   if($pages>1) echo $page_link;

}
}

?>