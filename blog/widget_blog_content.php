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
// -- NEWS INFORMATION -- //
include_once("blog_functions.php");

	$data_array = array();
	$data_array['$title']=$plugin_language['blog'];
    $data_array['$subtitle']='Blog';
	$template = $GLOBALS["_template"]->loadTemplate("blog_widget","blogs_title", $data_array, $plugin_path);
    echo $template;
	
	$qry = safe_query("SELECT * FROM ".PREFIX."plugins_blog  ORDER BY blogID DESC LIMIT 0,2");
	$anz = mysqli_num_rows($qry);
	if($anz) {		

	$template = $GLOBALS["_template"]->loadTemplate("blog_widget","blogs_head", array(), $plugin_path);
    echo $template;
	$n=1;
	while($ds = mysqli_fetch_array($qry)) {

		$comments = '';

        if ($ds[ 'comments' ]) {
            if ($ds[ 'blogID' ]) {
                $anzcomments = getanzblogcomments($ds[ 'blogID' ], 'bl');
                $replace = array('$anzcomments', '$url', '$lastposter', '$lastdate');
                @$vars = array(
                    $anzcomments,
                    'index.php?site=videos&action=watch&blogID=' . $ds[ 'blogID' ],
                    html_entity_decode(getlastblogcommentposter($ds[ 'blogID' ], 'bl')),
                    getformatdatetime(getlastblogcommentdate($ds[ 'blogID' ], 'bl'))
                );

                switch ($anzcomments) {
                    case 0:
                        $comments = str_replace($replace, $vars, $plugin_language[ 'no_comment_widget' ]);
                        break;
                    case 1:
                        $comments = str_replace($replace, $vars, $plugin_language[ 'comment_widget' ]);
                        break;
                    default:
                        $comments = str_replace($replace, $vars, $plugin_language[ 'comments_widget' ]);
                        break;
                }
            }
        } else {
            $comments = $plugin_language[ 'off_comments_widget' ];
        }

		$blogID = $ds['blogID'];
		$headline = $ds['headline'];
		$msg = $ds['msg'];
		$visits = $ds['visits'];
		$date = date("d", $ds['date']);
		$date2 = date("m", $ds['date']);

		$nickname = getnickname($ds['userID']);

		$translate = new multiLanguage(detectCurrentLanguage());
		$translate->detectLanguages($msg);
    	$msg = $translate->getTextByLanguage($msg);
    	$translate->detectLanguages($headline);
    	$headline = $translate->getTextByLanguage($headline);
		
		$maxblogchars = 80;
		if(mb_strlen($headline)>$maxblogchars) {
			$headline=mb_substr($headline, 0, $maxblogchars);
			$headline.='...';
		}

		$maxblogchars = 228;
		if(mb_strlen($msg)>$maxblogchars) {
			$msg=mb_substr($msg, 0, $maxblogchars);
			$msg.='...';
		}

		$data_array = array();
	    $data_array['$date'] = $date;
        $data_array['$date2'] = $date2;
	    $data_array['$visits'] = $visits;
	    $data_array['$headline'] = $headline;
		$data_array['$blogID'] = $blogID;
		$data_array['$nickname'] = $nickname;
		$data_array['$msg'] = $msg;
		$data_array['$comments'] = $comments;
		
		$data_array['$by'] = $plugin_language['by'];
		$data_array['$comment1'] = $plugin_language['comment1'];

	    $template = $GLOBALS["_template"]->loadTemplate("blog_widget","blogs_content", $data_array, $plugin_path);
    	echo $template;
		$n++;
	}
		$template = $GLOBALS["_template"]->loadTemplate("blog_widget","blogs_foot", array(), $plugin_path);
    	echo $template;
}
else {
	echo $plugin_language['no_entry'];
	echo'</div></div>';
}

?>