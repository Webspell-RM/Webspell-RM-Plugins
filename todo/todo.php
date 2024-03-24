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


#heading (open)
$data_array=array();
$data_array['$open'] = $plugin_language[ 'open' ];
#$data_array['$class'] = 'list-group-item-warning';
$data_array['$class'] = '#ffeeba';
$opentemp = $GLOBALS["_template"]->loadTemplate("todo", "open-start", $data_array, $plugin_path);
echo $opentemp;
#items (open & not ready)
$q = safe_query("SELECT * FROM `".PREFIX."plugins_todo` WHERE open=1 AND percent<100 AND displayed = '1' ORDER BY sort");
if(mysqli_num_rows($q)) {
	while($r=mysqli_fetch_array($q)) {
		$translate = new multiLanguage(detectCurrentLanguage());

		$title = $r['title'];
		
        $translate->detectLanguages($title);
        $title = $translate->getTextByLanguage($title);
                
        $text = $r['text'];
		$translate->detectLanguages($text);
        $text = $translate->getTextByLanguage($text);
    
		if($r['percent']<5) {
			$perc_class = "text-dark";
		} elseif($r['percent'] >=6 && $r['percent']<49) {
			$perc_class = "text-danger";
		} elseif($r['percent'] >=50 && $r['percent']<74) {
			$perc_class = "text-warning";
		} elseif($r['percent'] >=75 && $r['percent']<100) {
			$perc_class = "text-info";
		} else {
			$perc_class = "text-success";
		}

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

		if ($r[ 'progress_by' ]) {
            $progress_by = '<span class="badge bg-success">' . getnickname($r[ 'progress_by' ]) .'</span>';
        } else {
            $progress_by = "<span class='badge bg-danger'>nobody</span>";
        }

        $data_array = array();
		$data_array['$title'] = $title;
		$data_array['$perc_class'] = $perc_class;
		$data_array['$percent'] = $r['percent']."%";
		$data_array['$bar_class'] = $bar_class;
		$data_array['$date'] = date("d.m.Y", $r['date']);
		$data_array['$assigned'] = $progress_by;
		$data_array['$text'] = $text;
		$data_array['$nickname'] = getnickname($r['userID']);
		$data_array['$progress_by'] = $plugin_language[ 'progress_by' ];
		$data_array['$class'] = '#ffeeba';

	    $resultemp = $GLOBALS["_template"]->loadTemplate("todo", "sc_li", $data_array, $plugin_path);
        echo $resultemp;	
	} 
} else {
		$data_array['$title'] = $plugin_language[ 'no_todo' ];
	    $resultemp = $GLOBALS["_template"]->loadTemplate("todo", "sc_nonums", $data_array, $plugin_path);
        echo $resultemp;	
	}
#items (end)
$foottemp = $GLOBALS["_template"]->loadTemplate("todo", "sc_ul-end", array(), $plugin_path);
echo $foottemp;
#open (end)
$data_array=array();
$opentemp2 = $GLOBALS["_template"]->loadTemplate("todo", "open-end", $data_array, $plugin_path);
echo $opentemp2;
#heading (finished)
$data_array=array();
$data_array['$open'] = $plugin_language[ 'finished' ];
#$data_array['$class'] = 'list-group-item-success';
$data_array['$class'] = '#c3e6cb';
$opentemp3 = $GLOBALS["_template"]->loadTemplate("todo", "open-start", $data_array, $plugin_path);
echo $opentemp3;
#items (open and ready)
$q2 = safe_query("SELECT * FROM `".PREFIX."plugins_todo` WHERE open=1 AND percent=100 AND displayed = '1' ORDER BY sort");
if(mysqli_num_rows($q2)) {
	while($r2=mysqli_fetch_array($q2)) {
		$translate = new multiLanguage(detectCurrentLanguage());
		$title = $r2['title'];
		
        $translate->detectLanguages($title);
        $title = $translate->getTextByLanguage($title);
                
        $text = $r2['text'];
		$translate->detectLanguages($text);
        $text = $translate->getTextByLanguage($text);
    
        if($r2['percent']<5) {
			$perc_class = "text-dark";
		} elseif($r2['percent'] >=6 && $r2['percent']<49) {
			$perc_class = "text-danger";
		} elseif($r2['percent'] >=50 && $r2['percent']<74) {
			$perc_class = "text-warning";
		} elseif($r2['percent'] >=75 && $r2['percent']<100) {
			$perc_class = "text-info";
		} else {
			$perc_class = "text-success";
		}

		if($r2['percent']<5) {
			$bar_class = "bg-dark";
		} elseif($r2['percent'] >=6 && $r2['percent']<49) {
			$bar_class = "bg-danger";
		} elseif($r2['percent'] >=50 && $r2['percent']<74) {
			$bar_class = "bg-warning text-dark";
		} elseif($r2['percent'] >=75 && $r2['percent']<100) {
			$bar_class = "bg-info text-dark";
		} else {
			$bar_class = "bg-success";
		}


		if ($r2[ 'progress_by' ]) {
            $progress_by = '<span class="badge bg-success">' . getnickname($r2[ 'progress_by' ]) .'</span>';
        } else {
            $progress_by = "<span class='badge bg-danger'>nobody</span>";
        }

		$data_array = array();
		$data_array['$title'] = $title;
		$data_array['$perc_class'] = $perc_class;
		$data_array['$percent'] = $r2['percent']."%";
		$data_array['$bar_class'] = $bar_class;
		$data_array['$date'] = date("d.m.Y", $r2['date']);
		$data_array['$assigned'] = $progress_by;
		$data_array['$text'] = $text;
		$data_array['$nickname'] = getnickname($r2['userID']);
		$data_array['$progress_by'] = $plugin_language[ 'progress_by' ];
		$data_array['$class'] = '#c3e6cb';

	    $resultemp2 = $GLOBALS["_template"]->loadTemplate("todo", "sc_li", $data_array, $plugin_path);
        echo $resultemp2;	
	} 
} else {
		$data_array['$title'] = $plugin_language[ 'no_todo' ];
	    $resultemp = $GLOBALS["_template"]->loadTemplate("todo", "sc_nonums", $data_array, $plugin_path);
        echo $resultemp;	
	}
#items (end (ready))
$foottemp2 = $GLOBALS["_template"]->loadTemplate("todo", "sc_ul-end", array(), $plugin_path);
echo $foottemp2;
#finished (end)
$data_array=array();
$opentemp4 = $GLOBALS["_template"]->loadTemplate("todo", "open-end", $data_array, $plugin_path);
echo $opentemp4;

# -- access to set todo as open / visible 
if(issuperadmin($userID)) {
	
	#heading (finished)
$data_array=array();
$data_array['$open'] = $plugin_language[ 'not_display' ];
#$data_array['$class'] = 'list-group-item-danger';
$data_array['$class'] = '#f5c6cb';
$opentemp3 = $GLOBALS["_template"]->loadTemplate("todo", "open-start", $data_array, $plugin_path);
echo $opentemp3;
#items (open and ready)
$q3 = safe_query("SELECT * FROM `".PREFIX."plugins_todo` WHERE open=0 AND displayed = '1' ORDER BY sort");
if(mysqli_num_rows($q3)) {
	while($r3=mysqli_fetch_array($q3)) {
$translate = new multiLanguage(detectCurrentLanguage());
		$title = $r3['title'];
		
        $translate->detectLanguages($title);
        $title = $translate->getTextByLanguage($title);
                
        $text = $r3['text'];
		$translate->detectLanguages($text);
        $text = $translate->getTextByLanguage($text);
    
		if($r3['percent']<5) {
			$perc_class = "text-dark";
		} elseif($r3['percent'] >=6 && $r3['percent']<49) {
			$perc_class = "text-danger";
		} elseif($r3['percent'] >=50 && $r3['percent']<74) {
			$perc_class = "text-warning";
		} elseif($r3['percent'] >=75 && $r3['percent']<100) {
			$perc_class = "text-info";
		} else {
			$perc_class = "text-success";
		}

		if($r3['percent']<5) {
			$bar_class = "bg-dark";
		} elseif($r3['percent'] >=6 && $r3['percent']<49) {
			$bar_class = "bg-danger";
		} elseif($r3['percent'] >=50 && $r3['percent']<74) {
			$bar_class = "bg-warning text-dark";
		} elseif($r3['percent'] >=75 && $r3['percent']<100) {
			$bar_class = "bg-info text-dark";
		} else {
			$bar_class = "bg-success";
		}
		
		if ($r3[ 'progress_by' ]) {
            $progress_by = '<span class="badge bg-success">' . getnickname($r3[ 'progress_by' ]) .'</span>';
        	} else {
            $progress_by = "<span class='badge bg-danger'>nobody</span>";
        }

			$data_array = array();
			$data_array['$title'] = $title;
			$data_array['$perc_class'] = $perc_class;
			$data_array['$percent'] = $r3['percent']."%";
			$data_array['$bar_class'] = $bar_class;
			$data_array['$date'] = date("d.m.Y", $r3['date']);
			$data_array['$text'] = $text;
			$data_array['$assigned'] = $progress_by;
			$data_array['$nickname'] = getnickname($r3['userID']);
			$data_array['$progress_by'] = $plugin_language[ 'progress_by' ];
			$data_array['$class'] = '#f5c6cb';
			
	    $resultemp3 = $GLOBALS["_template"]->loadTemplate("todo", "sc_li", $data_array, $plugin_path);
        echo $resultemp3;	
	} 
} else {
		$data_array['$title'] = $plugin_language[ 'no_todo' ];
	    $resultemp = $GLOBALS["_template"]->loadTemplate("todo", "sc_nonums", $data_array, $plugin_path);
        echo $resultemp;	
	}
#items (end (ready))
$foottemp4 = $GLOBALS["_template"]->loadTemplate("todo", "sc_ul-end", array(), $plugin_path);
echo $foottemp4;
#finished (end)
$data_array=array();
$opentemp5 = $GLOBALS["_template"]->loadTemplate("todo", "open-end", $data_array, $plugin_path);
echo $opentemp5;
	
	
}

?>

