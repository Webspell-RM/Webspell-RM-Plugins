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
$plugin_language = $pm->plugin_language("files", $plugin_path);

    $data_array = array();
    $data_array['$title']=$plugin_language['files'];
    $data_array['$subtitle']='Files';

    $template = $GLOBALS["_template"]->loadTemplate("sc_files","title_head", $data_array, $plugin_path);
    echo $template;

$getlist = safe_query("SELECT sc_files FROM " . PREFIX . "plugins_files_settings");
$ds = mysqli_fetch_array($getlist);

    if ($ds[ 'sc_files' ] == 1) {
        $list = "downloads";
    } else {
        $list = "date";
    }

    global $userID;
    $accesslevel = 1;
    if (isclanmember($userID)) {
        $accesslevel = 2;
    }

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_files WHERE accesslevel<=" . $accesslevel . " ORDER BY " . $list . " DESC LIMIT 0,5");

$n = 1;
if (mysqli_num_rows($ergebnis)) {
    $data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("sc_files","head_head", $data_array, $plugin_path);
    echo $template;
    
    while ($ds = mysqli_fetch_array($ergebnis)) {
        
        $fileID = $ds[ 'fileID' ];
        $count = $ds[ 'downloads' ];
        $filename = $ds[ 'filename' ];
        $max_filename = $ds[ 'filename' ];
        $text = $ds[ 'info' ];
        #$date = date("d.m.Y - h:i", $ds[ 'date' ]);
        $date = date("d.m.Y", $ds[ 'date' ]);

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
        $number = $n;        

        $maxblogchars = 25;
        if(mb_strlen($filename)>$maxblogchars) {
            $filename=mb_substr($filename, 0, $maxblogchars);
            $filename.='...';
        }

        $maxblogchars = 60;
        if(mb_strlen($text)>$maxblogchars) {
            $text=mb_substr($text, 0, $maxblogchars);
            $text.='...';
        }

        
        $title1 = '<a href="index.php?site=files&amp;file='.$fileID.'" class="titre" data-toggle="tooltip" data-bs-html="true" title="
        '.$max_filename.'<br>'.$date.'">'.$filename.'</a>';
        #if(strlen($filename)>23) {
        #    $file_name = substr($filename, 0, 23)."..";
        #}

        $data_array = array();
        $data_array['$count'] = $count;
        $data_array['$fileID'] = $fileID;
        $data_array['$date'] = $date;
        $data_array['$title'] = $title1;
        $data_array['$day'] = $day;
        $data_array['$date2'] = $monate[$monat];
        $data_array['$year'] = $year;		
        
        $data_array['$filename'] = $filename;
        $data_array['$max_filename'] = $max_filename;
        $data_array['$text'] = $text;

        $template = $GLOBALS["_template"]->loadTemplate("sc_files","content", $data_array, $plugin_path);
        echo $template;
        
        $n++;
    }
    
    $data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("sc_files","foot", $data_array, $plugin_path);
    echo $template;

} else {
    
    echo $plugin_language[ 'no_file_uploaded' ];
}
