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
	$plugin_language = $pm->plugin_language("blog", $plugin_path);

	$data_array = array();
	$data_array['$title']=$plugin_language['blog'];
    $data_array['$subtitle']='Blog';
	$template = $GLOBALS["_template"]->loadTemplate("blog","title", $data_array, $plugin_path);
    echo $template;
	
	$qry = safe_query("SELECT * FROM ".PREFIX."plugins_blog WHERE blogID!=0 ORDER BY blogID DESC LIMIT 0,5");
	$anz = mysqli_num_rows($qry);
	if($anz) {
		

	$template = $GLOBALS["_template"]->loadTemplate("blog","widget_blogs_head", array(), $plugin_path);
    echo $template;
	$n=1;
	while($ds = mysqli_fetch_array($qry)) {

		$blogID = $ds['blogID'];
		$headline = $ds['headline'];
		$visits = $ds['visits'];
		$date = date("d.m.Y", $ds['date']);
		$msg = $ds['msg'];

		$time = date("H:i", $ds['date']);
		$day = date("d", $ds['date']);
        $year = date("Y", $ds['date']);

        $monate = array(1=>$plugin_language[ 'jan' ],
                            2=>$plugin_language[ 'feb' ],
                            3=>$plugin_language[ 'mar' ],
                            4=>$plugin_language[ 'apr' ],
                            5=>$plugin_language[ 'mar' ],
                            6=>$plugin_language[ 'jun' ],
                            7=>$plugin_language[ 'jul' ],
                            8=>$plugin_language[ 'aug' ],
                            9=>$plugin_language[ 'sep' ],
                           10=>$plugin_language[ 'oct' ],
                           11=>$plugin_language[ 'nov' ],
                           12=>$plugin_language[ 'dec' ]);

        $monat = date("n", $ds['date']);
		$nickname = getnickname($ds['userID']);

		$translate = new multiLanguage(detectCurrentLanguage());
    	$translate->detectLanguages($headline);
    	$headline = $translate->getTextByLanguage($headline);
		
		$maxblogchars = 25;
		if(mb_strlen($headline)>$maxblogchars) {
			$headline=mb_substr($headline, 0, $maxblogchars);
			$headline.='...';
		}

		$maxblogchars = 110;
		if(mb_strlen($msg)>$maxblogchars) {
			$msg=mb_substr($msg, 0, $maxblogchars);
			$msg.='...';
		}
		
		
		
		$title1 = '<a href="index.php?site=blog&action=show&blogID='.$blogID.'" data-toggle="tooltip" data-bs-html="true" title="'.$headline.'<br>by '.$nickname.'<br>'.$date.'">'.$headline.'</a>';

		$data_array = array();
	    $data_array['$date'] = $date;
	    $data_array['$visits'] = $visits;
	    $data_array['$title'] = $title1;
		$data_array['$blogID'] = $blogID;
		$data_array['$nickname'] = $nickname;
		$data_array['$text'] = $msg;
		$data_array['$day'] = $day;
        $data_array['$date2'] = $monate[$monat];
        $data_array['$year'] = $year;

	    $template = $GLOBALS["_template"]->loadTemplate("blog","widget_content", $data_array, $plugin_path);
    	echo $template;
		$n++;
	}
		$template = $GLOBALS["_template"]->loadTemplate("blog","widget_blogs_foot", array(), $plugin_path);
    	echo $template;
}
else {
	echo $plugin_language['no_entry'];
}

?>