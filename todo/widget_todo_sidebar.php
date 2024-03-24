<?php
/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2015 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#                                                                        #
##########################################################################
*/

# Sprachdateien aus dem Plugin-Ordner laden
	$pm = new plugin_manager(); 
	$plugin_language = $pm->plugin_language("todo", $plugin_path);


$data_array=array();
$data_array['$link'] = 'todo';
$data_array['$title'] = $plugin_language[ 'todo' ];
$data_array['$subtitle']='Todo';

$headtemp = $GLOBALS["_template"]->loadTemplate("todo", "title", $data_array, $plugin_path);
echo $headtemp;


$q = safe_query("SELECT * FROM `".PREFIX."plugins_todo` WHERE open=1 AND displayed = '1' ORDER BY sort DESC LIMIT 0,5");
if(mysqli_num_rows($q)) {

	$headtemp = $GLOBALS["_template"]->loadTemplate("todo", "widget_sc_ul-start", array(), $plugin_path);
	echo $headtemp;

	while($r=mysqli_fetch_array($q)) {
		$title = $r['title'];
		

		$translate = new multiLanguage(detectCurrentLanguage());
		$translate->detectLanguages($title);
        $title = $translate->getTextByLanguage($title);

		if($r['percent']<5) {
			$bar_class = "bg-dark";
		} elseif($r['percent'] >=6 && $r['percent']<49) {
			$bar_class = "bg-danger";
		} elseif($r['percent'] >=50 && $r['percent']<74) {
			$bar_class = "bg-warning text-dark";
		} elseif($r['percent'] >=75 && $r['percent']<100) {
			$bar_class = "bg-info text-dark";
		} else {
			$bar_class = "bg-success";
		}
		$data_array = array();
		$data_array['$title'] = $title;
		$data_array['$percent'] = $r['percent']."%";
		$data_array['$bar_class'] = $bar_class;
		$data_array['$date'] = date("d.m.Y", $r['date']);
		$data_array['$nickname'] = getnickname($r['userID']);
	    $resultemp = $GLOBALS["_template"]->loadTemplate("todo", "sc_content", $data_array, $plugin_path);
        echo $resultemp;	
	} 

	$foottemp = $GLOBALS["_template"]->loadTemplate("todo", "widget_sc_ul-end", array(), $plugin_path);
	echo $foottemp;
} else {
	echo $plugin_language[ 'no_todo' ];
}

?>

