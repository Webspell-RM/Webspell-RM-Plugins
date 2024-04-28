<style type="text/css">
.p2 {margin-top: 5px;
margin-bottom: 10px;}
</style><?php
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
$_lang = $pm->plugin_language("files", $plugin_path);

// -- FILES INFORMATION -- //
include_once("files_functions.php");

##############################################    

    $data_array = array();
    $data_array['$title']=$_lang['files'];
    $data_array['$subtitle']='Files';

    $template = $GLOBALS["_template"]->loadTemplate("files","title_head", $data_array, $plugin_path);
    echo $template;

try {
    $get = mysqli_fetch_assoc(safe_query("SELECT * FROM `".PREFIX."settings_recaptcha`"));
    $webkey = $get['webkey'];
    $seckey = $get['seckey'];
    if ($get['activated']=="1") { $recaptcha=1; } else { $recaptcha=0; }
} Catch (EXCEPTION $e) {
    $recaptcha=0;
}

function get_all_sub_cats($parent, $start = 0)
{
    $end = 0;
    if ($start == 1) {
        $cat_query = "( filecatID='" . $parent . "' ";
    } else {
        $cat_query = "";
    }
    $get_catIDs = safe_query(
        "SELECT
            `filecatID`
        FROM
            `" . PREFIX . "plugins_files_categories`
        WHERE
            `subcatID` = '" . (int)$parent."'"
    );

    if (mysqli_num_rows($get_catIDs)) {
        while ($dc = mysqli_fetch_assoc($get_catIDs)) {
            $cat_query .= " || filecatID='" . $dc[ 'filecatID' ] . "'";
            $more = mysqli_num_rows(
                safe_query(
                    "SELECT
                        `filecatID`
                    FROM
                        `" . PREFIX . "plugins_files_categories`
                    WHERE
                        `subcatID` = '" . (int)$dc[ 'filecatID' ]."'"
                )
            );
            if ($more > 0) {
                $cat_query .= get_all_sub_cats($dc[ 'filecatID' ], 0);
            }
        }
    }
    if ($start == 1) {
        $cat_query .= ")";
    }
    return $cat_query;
}


function unit_to_size($num, $unit) {
    switch($unit) {
        case 'b': $size=$num; break;
        case 'kb': $size=$num*1024; break;
        case 'mb': $size=$num*1024*1024; break;
        case 'gb': $size=$num*1024*1024*1024; break;
    }
    return $size;
}   


if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = "";
}




if (isset($_GET[ 'cat' ])) {
    $accesslevel = 1;
    if (isclanmember($userID)) {
        $accesslevel = 2;
    }
    

    // CATEGORY
    $catID = intval($_GET[ 'cat' ]);
    $cat = mysqli_fetch_array(
        safe_query(
            "SELECT
                `filecatID`,
                `name`,
                `subcatID`
            FROM
                `" . PREFIX . "plugins_files_categories`
            WHERE `filecatID` = '" . $catID."'"
        )
    );
    $category = $cat[ 'name' ];

    $cat_id = $cat[ 'subcatID' ];
    while ($cat_id != 0) {
        $subcat = mysqli_fetch_array(
            safe_query(
                "SELECT
                    `filecatID`,
                    `name`,
                    `subcatID`
                FROM
                    `" . PREFIX . "plugins_files_categories`
                WHERE
                    `filecatID` = '" . (int)$cat_id."'"
            )
        );
        $category = '<a href="index.php?site=files&amp;cat=' . $subcat[ 'filecatID' ] . '" class="titlelink">' .
            $subcat[ 'name' ] . '</a><li class="breadcrumb-item">'.$category.'</li>';
        $cat_id = $subcat[ 'subcatID' ];
    }

    unset($n);

    // SUBCATEGORIES

    $subcats = safe_query(
        "SELECT
            *
        FROM
            `" . PREFIX . "plugins_files_categories`
        WHERE
            `subcatID` = '" . (int)$cat[ 'filecatID' ] . "'
        ORDER BY
            name"
    );
    if (mysqli_num_rows($subcats)) {
        $data_array = array();
        $data_array['$category'] = $category;

        $data_array['$category_cl']=$_lang['category_cl'];
        $data_array['$subcategories']=$_lang['subcategories'];
        $data_array['$files_cl']=$_lang['files_cl'];
        $data_array['$downloads_cl']=$_lang['downloads_cl'];
        $data_array['$dl']=$_lang['dl'];

        $template = $GLOBALS["_template"]->loadTemplate("files","category_head", $data_array, $plugin_path);
        echo $template;
        
        $template = $GLOBALS["_template"]->loadTemplate("files","subcat_list_head", $data_array, $plugin_path);
        echo $template;
        
        while ($subcat = mysqli_fetch_array($subcats)) {
            $catname = '<a href="index.php?site=files&amp;cat=' . $subcat[ 'filecatID' ] . '"><b>' . $subcat[ 'name' ] .
                '</b></a>';
            $downloads = 0;
            $sub_cat_qry = get_all_sub_cats($subcat[ 'filecatID' ], 1);
            $query =
                safe_query(
                    "SELECT
                        `downloads`
                    FROM
                        `" . PREFIX . "plugins_files`
                    WHERE
                        `filecatID`='".$subcat[ 'filecatID' ]."' AND
                        `accesslevel` <= " . (int)$accesslevel . "
                    ORDER BY 
                    
                        `fileID` DESC"
                ); 
            $cat_file_total = mysqli_num_rows($query);
            while ($ds = mysqli_fetch_array($query)) {
                $downloads += $ds[ 'downloads' ];
            }
            $subcategories =
                mysqli_num_rows(
                    safe_query(
                        "SELECT
                            `filecatID`
                        FROM
                            `" . PREFIX . "plugins_files_categories`
                        WHERE `subcatID` = '" . (int)$subcat[ 'filecatID' ] . "'"
                    )
                );

            $data_array = array();
            $data_array['$catname'] = $catname;
            $data_array['$subcategories'] = $subcategories;
            $data_array['$cat_file_total'] = $cat_file_total;
            $data_array['$downloads'] = $downloads;

            $template = $GLOBALS["_template"]->loadTemplate("files","subcat_list", $data_array, $plugin_path);
            echo $template;
            
        }
        $template = $GLOBALS["_template"]->loadTemplate("files","subcat_list_foot", $data_array, $plugin_path);
        echo $template;
        
    }

    // FILES
    $files = safe_query(
        "SELECT
            *
        FROM
            `" . PREFIX . "plugins_files`
        WHERE
            `filecatID` = '" . (int)$cat[ 'filecatID' ] . "' AND
            `accesslevel` <= " . (int)$accesslevel . "
        ORDER BY `sort`"
    );
    if (mysqli_num_rows($files)) {
        $data_array = array();
        $data_array['$category'] = $category;

        $data_array['$files_cl']=$_lang['files_cl'];
        $data_array['$filename']=$_lang['filename'];
        $data_array['$description']=$_lang['description'];
        $data_array['$downloads_cl']=$_lang['downloads_cl'];
        $data_array['$dl']=$_lang['dl'];

        $data_array['$file_info'] = $_lang[ 'file_info' ];

        $template = $GLOBALS["_template"]->loadTemplate("files","category_list_head", $data_array, $plugin_path);
        echo $template;
        
        $n = 0;

        while ($file = mysqli_fetch_array($files)) {
            $n++;
            
            $fileid = $file[ 'fileID' ];
            
            
            $filename = $file[ 'filename' ];
            $filename = $filename;
            $fileinfo = $file[ 'info' ];



            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($filename);
            $filename = $translate->getTextByLanguage($filename);
            $translate->detectLanguages($fileinfo);
            $fileinfo = $translate->getTextByLanguage($fileinfo);
            
            $filename = '<a href="index.php?site=files&amp;file=' . $fileid . '"><b>' . $filename . '</b></a>';
            @$filesize = $file[ 'filesize' ];
            @$fileload = $file[ 'downloads' ];
            $filedate = getformatdatetime($file[ 'date' ]);
            #$traffic = @$filesize * @$fileload;
            

            if (!$userID && $file[ 'accesslevel' ] >= 1) {
                $link = '(R)';
            } else {
                $link = '<a href="/includes/plugins/files/download.php?fileID=' . $fileid . '">
                <i class="bi bi-download"></i>
                </a>';
            }

            if(strlen($fileinfo)>100) {
            $fileinfo = substr($fileinfo, 0, 100).'<a href="index.php?site=files&amp;file=' . $fileid . '"><b>[...]</b></a>';
            }

            
            $data_array = array();
            $data_array['$filename'] = $filename;
            $data_array['$fileinfo'] = $fileinfo;
            $data_array['$fileload'] = $fileload;
            
            $data_array['$link'] = $link;

            

            $template = $GLOBALS["_template"]->loadTemplate("files","category_list", $data_array, $plugin_path);
            echo $template;
            
        }
        $template = $GLOBALS["_template"]->loadTemplate("files","category_list_foot", $data_array, $plugin_path);
        echo $template;
        
    }
    if (!isset($n)) {
        echo $_lang[ 'cant_display_empty_cat' ];
    }


 #########################

} elseif (isset($_GET[ 'file' ])) {
    

    // FILE-INFORMATION
    $file = mysqli_fetch_array(
        safe_query(
            "SELECT
                *
            FROM
                `" . PREFIX . "plugins_files`
            WHERE
                `fileID` = '" . (int)$_GET[ 'file' ]."'"
        )
    );
    if ($file[ 'accesslevel' ] == 2 && !isclanmember($userID)) {
        die($_lang[ 'no_access' ]);
    }

    $fileID = $file[ 'fileID' ];

    $filename = $file[ 'filename' ];
    $filename = $filename;
    $fileinfo = $file[ 'info' ];

    $translate = new multiLanguage(detectCurrentLanguage());
    $translate->detectLanguages($filename);
    $filename = $translate->getTextByLanguage($filename);
    $translate->detectLanguages($fileinfo);
    $fileinfo = $translate->getTextByLanguage($fileinfo);
            
    $filesize = $file[ 'filesize' ];
    if (!$filesize) {
        $filesize = 0;
    }
    $downloads = $file[ 'downloads' ];
    if (!$downloads) {
        $downloads = 0;
    }
    @$traffic = detectfilesize($filesize * $downloads);
    $filesize = detectfilesize($file[ 'filesize' ]);

    $accesslevel = 0;
    if ($userID) {
        $accesslevel = 1;
    }
    if (isclanmember($userID)) {
        $accesslevel = 2;
    }
    if ($file[ 'accesslevel' ] <= $accesslevel) {
        $reportlink = '<a class="btn btn-danger" href="index.php?site=files&amp;action=report&amp;link=' . $file[ 'fileID' ] . '">' .
        $_lang[ 'report_dead_link' ] . '</a>';
    } else {
        $reportlink = '';
    }

    $date = getformatdate($file[ 'date' ]);
    $update = getformatdate($file[ 'update' ]);

    // FILE-AUTHOR
    $uploader =' <a href="index.php?site=profile&amp;id=' .
    $file[ 'poster' ] . '">' . getnickname($file[ 'poster' ]) . '</a>';

    // FILE-CATEGORY
    $cat = mysqli_fetch_array(
        safe_query(
            "SELECT
                *
            FROM
                `" . PREFIX . "plugins_files_categories`
            WHERE
                `filecatID` = '" . (int)$file[ 'filecatID' ]."'"
        )
    );
    $category = '<li class="breadcrumb-item"><a href="index.php?site=files&amp;cat=' . $cat[ 'filecatID' ] . '" class="titlelink">' .
        $cat[ 'name' ] . '</a></li>';
    $categories = '<a href="index.php?site=files&amp;cat=' . $cat[ 'filecatID' ] . '"><strong>' .
        $cat[ 'name' ] . '</strong></a>';

    $cat_id = $cat[ 'subcatID' ];
    while ($cat_id != 0) {
        $subcat = mysqli_fetch_array(
            safe_query(
                "SELECT
                    `filecatID`,
                    `name`,
                    `subcatID`
                FROM
                    `" . PREFIX . "plugins_files_categories`
                WHERE
                    `filecatID` = '" . (int)$cat_id."'"
            )
        );
        $category = '<li class="breadcrumb-item"><a href="index.php?site=files&amp;cat=' . $subcat[ 'filecatID' ] . '" class="titlelink">' .
            $subcat[ 'name' ] . '</a></li>' . $category;
        $categories =
            '<a href="index.php?site=files&amp;cat=' . $subcat[ 'filecatID' ] . '"><b>' . $subcat[ 'name' ] .
            '</b></a> &raquo ' . $categories;
        $cat_id = $subcat[ 'subcatID' ];
    }

    // FILE-MIRRORS (remember: the primary mirror is still the uploaded or external file!)
    $mirrors = $file[ 'mirrors' ];
    if ($mirrors) {
        if (stristr($mirrors, "||")) {
            $secondarymirror = explode("||", $mirrors);
            $mirrorlist = '<a href="' . $secondarymirror[ 0 ] . '" target="_blank">' .
                $_lang[ 'download_via_mirror' ] . ' #2</a><br><a href="' . $secondarymirror[ 1 ] .
                '" target="_blank">' . $_lang[ 'download_via_mirror' ] . ' #3</a>';
        } else {
            $mirrorlist =
                '&#8226; <a href="' . $mirrors . '" target="_blank">' . $_lang[ 'download_via_mirror' ] .
                ' #2</a>';
        }
    } else {
        $mirrorlist = $_lang[ 'no_mirrors' ];
    }

    if ($file[ 'accesslevel' ] && !$userID) {
        $mirrorlist = '<i>' . $_lang[ 'download_registered_only' ] . '</i>';
    }

     //rateform

     


    // RATING
    $rating = $file[ 'rating' ];
    $rating ? $rating = $rating . ' / 10' : $rating = '0 / 10';
    $ratings = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    for ($i = 0; $i < $file[ 'rating' ]; $i++) {
        $ratings[ $i ] = 1;
    }
    
    $ratingpic = '';
    foreach ($ratings as $pic) {
        $ratingpic .= '<img src="/includes/plugins/files/images/rating_' . $pic . '.png" width="21" height="21" alt="">';
    }
    if ($loggedin) {
        $getfiles = safe_query("SELECT `files` FROM `" . PREFIX . "user` WHERE `userID` = '" . (int)$userID."'");
        $found = false;
        if (mysqli_num_rows($getfiles)) {
            $ga = mysqli_fetch_array($getfiles);
            if ($ga[ 'files' ] != "") {
                $string = $ga[ 'files' ];
                $array = explode(":", $string);
                $anzarray = count($array);
                for ($i = 0; $i < $anzarray; $i++) {
                    if ($array[ $i ] == $file[ 'fileID' ]) {
                        $found = true;
                    }
                }
            }
        }
        if ($found) {
            $rateform = "<i>" . $_lang[ 'you_have_already_rated' ] . "</i>";
        } else {
            $rateform = '<form method="post" name="rating_file' . $file[ 'fileID' ] .
                '" action="index.php?site=files_rating" role="form">
            <div class="input-group">
                <select name="rating" class="form-control">
                    <option>0 - ' . $_lang[ 'poor' ] . '</option>
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                    <option>6</option>
                    <option>7</option>
                    <option>8</option>
                    <option>9</option>
                    <option>10 - ' . $_lang[ 'perfect' ] . '</option>
                </select>

                <span class="input-group-btn">
                    <input type="submit" name="Submit" value="' . $_lang[ 'rate' ] .
                        '" class="btn btn-primary">
                </span>
            </div>
            <input type="hidden" name="userID" value="' . $userID . '">
            <input type="hidden" name="type" value="fi">
            <input type="hidden" name="id" value="' . $file[ 'fileID' ] . '">
        </form>';
        }
    } else {
        $rateform = '<i>' . $_lang[ 'rate_have_to_reg_login' ] . '</i>';
    }
    
    $votes = $file[ 'votes' ];


    $accesslevel = 0;
    if ($userID) {
        $accesslevel = 1;
    }
    if (isclanmember($userID)) {
        $accesslevel = 2;
    }

    if ($file[ 'accesslevel' ] <= $accesslevel) {
        $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($filename);
            $filename = $translate->getTextByLanguage($filename);
            
        $link = '<a href="/includes/plugins/files/download.php?fileID=' . $fileID .
            '" class="btn btn-lg btn-success"><i class="bi bi-download"></i> ' .
            str_replace('%filename%', $filename, $_lang[ 'download_now' ]) . '</a>';
    } else {
        $link = '<i>' . $_lang[ 'download_registered_only' ] . '</i>';
    }

    #$update = $file[ 'update' ];

    if ($update == '01.01.1970') {
        $update = 'n/a';
    }

    $data_array = array();
    $data_array['$category'] = $category;
    $data_array['$filename'] = $filename;
    $data_array['$fileinfo'] = $fileinfo;
    $data_array['$categories'] = $categories;
    $data_array['$uploader'] = $uploader;
    $data_array['$date'] = $date;
    $data_array['$update'] = $update;
    
    $data_array['$filesize'] = $filesize;
    $data_array['$downloads'] = $downloads;
    $data_array['$traffic'] = $traffic;
    $data_array['$reportlink'] = $reportlink;
    $data_array['$link'] = $link;
    $data_array['$mirrorlist'] = $mirrorlist;
    $data_array['$ratingpic'] = $ratingpic;
    $data_array['$rating'] = $rating;
    $data_array['$rateform'] = $rateform;
    $data_array['$votes'] = $votes;

    $data_array['$file_name']=$_lang['filename'];
    $data_array['$files_cl']=$_lang['files_cl'];
    $data_array['$info_description']=$_lang['info_description'];
    $data_array['$category_cl']=$_lang['category_cl'];
    $data_array['$uploader_dl']=$_lang['uploader'];
    $data_array['$uploaded_on']=$_lang['uploaded_on'];
    $data_array['$size']=$_lang['size'];
    $data_array['$downloads_cl']=$_lang['downloads_cl'];
    $data_array['$traffic_cl']=$_lang['traffic_cl'];
    $data_array['$mirrors']=$_lang['mirrors'];
    $data_array['$update_on']=$_lang['update_on'];
    $data_array['$rating']=$_lang['rating'];
    $data_array['$lang_votes'] = $_lang['votes'];
    $data_array['$rate_now'] = $_lang[ 'rate_now' ];
    $data_array['$download_direct'] = $_lang[ 'download_direct' ];
    $data_array['$file_info'] = $_lang[ 'file_info' ];

    
    $template = $GLOBALS["_template"]->loadTemplate("files","display", $data_array, $plugin_path);
    echo $template;

} elseif ($action == "report") {
    if ($loggedin) {
    // DEAD-LINK TICKET SYSTEM
    $mode = 'deadlink';
    $type = 'files';
    $id = getforminput($_GET[ 'link' ]);
    $referer = $hp_url . '/index.php?site=files&amp;fileID=' . $id;

    
        $type = 'files';
        $captcha_form = "";
        $type_ = "";
        

        $data_array = array();
        $data_array['$type_'] = $type_;
        $data_array['$id'] = $id;
        $data_array['$mode'] = $mode;
        $data_array['$type'] = $type;
        $data_array['$referer'] = $referer;

        $data_array['$report_dead_link'] = $_lang['report_dead_link'];
        $data_array['$leave_description'] = $_lang['leave_description'];
        $data_array['$report'] = $_lang['report'];
        $data_array['$reset'] = $_lang['reset'];
        
        $template = $GLOBALS["_template"]->loadTemplate("files","report_deadlink_loggedin", $data_array, $plugin_path);
        echo $template;

} else {
// DEAD-LINK TICKET SYSTEM
    $mode = 'deadlink';
    $type = 'files';
    $id = getforminput($_GET[ 'link' ]);
    $referer = $hp_url . '/index.php?site=files&amp;fileID=' . $id;

    
        $type = 'files';
        $captcha_form = "";
        $type_ = "";
        
        $CAPCLASS = new \webspell\Captcha;
        $captcha = $CAPCLASS->createCaptcha();
        $hash = $CAPCLASS->getHash();
        $CAPCLASS->clearOldCaptcha();
            

        if($recaptcha=="0") {
            $CAPCLASS = new \webspell\Captcha;
            $captcha = $CAPCLASS->createCaptcha();
            $hash = $CAPCLASS->getHash();
            $CAPCLASS->clearOldCaptcha();
            $_captcha = '
                    <span class="input-group-addon captcha-img">'.$captcha.'</span>
                    <input type="number" name="captcha" class="form-control" id="input-security-code">
                    <input name="captcha_hash" type="hidden" value="'.$hash.'">
            ';
            } else {
                $_captcha = '<div class="g-recaptcha" style="width: 70%; float: left;" data-sitekey="'.$webkey.'"></div>';
            }    
        

        $data_array = array();
        $data_array['$type_'] = $type_;
        $data_array['$id'] = $id;
        $data_array['$mode'] = $mode;
        $data_array['$type'] = $type;
        $data_array['$referer'] = $referer;
        $data_array['$_captcha'] = $_captcha;

        $data_array['$security_code'] = $_lang[ 'security_code' ];
        $data_array['$report_dead_link'] = $_lang['report_dead_link'];
        $data_array['$leave_description'] = $_lang['leave_description'];
        $data_array['$report'] = $_lang['report'];
        $data_array['$reset'] = $_lang['reset'];
        
        $template = $GLOBALS["_template"]->loadTemplate("files","report_deadlink_notloggedin", $data_array, $plugin_path);
        echo $template;
}

    #} else {
    #    redirect("index.php?site=files", $_language->module[ 'cant_report_without_fileID' ], "3");
    #}
} else {

 ##############   

    $accesslevel = 1;
    $adminactions = '';
    if (isclanmember($userID)) {
        $accesslevel = 2;
    }
    
// STATS

    // categories in database Startseite sortiert
    $catQry = safe_query(
        "SELECT
            *
        FROM
            `" . PREFIX . "plugins_files_categories`
        WHERE
            `subcatID` = '0'
         ORDER BY `sort`"
    );

    $totalcats = mysqli_num_rows($catQry);
    if ($totalcats) {

// files in database
        $fileQry = safe_query("SELECT * FROM `" . PREFIX . "plugins_files`");
        $totalfiles = mysqli_num_rows($fileQry);
        if ($totalfiles) {
            $hddspace = 0;
            $traffic = 0;
            // total traffic caused by downloads
            while ($file = mysqli_fetch_array($fileQry)) {
                $filesize = $file[ 'filesize' ];
                $fileload = $file[ 'downloads' ];
                @$hddspace += $filesize;
                @$traffic += $filesize * $fileload;
                }
            $traffic = detectfilesize($traffic);
            $hddspace = detectfilesize($hddspace);

            // last uploaded file
            $filedata =
                mysqli_fetch_array(
                    safe_query(
                        "SELECT
                            *
                        FROM
                            `" . PREFIX . "plugins_files`
                        WHERE
                            `accesslevel` <= " . $accesslevel . "
                        ORDER BY
                            date DESC
                        LIMIT 0,1"
                    )
                );
            $filename = $filedata[ 'filename' ];
            if (mb_strlen($filename) > 40) {
                $translate = new multiLanguage(detectCurrentLanguage());
                $translate->detectLanguages($filename);
                $filename = $translate->getTextByLanguage($filename);
                
                $filename = mb_substr($filename, 0, 40);
                $filename .= '...';
            }
            $lastfile = '<a href="index.php?site=files&amp;file=' . $filedata[ 'fileID' ] . '" >' . $filename .
                '</a>';
        } else {
            $traffic = 'n/a';
            $hddspace = 'n/a';
            $lastfile = 'n/a';
        }

#============================================================

// TOP 5 FILES
        $getlist = safe_query("SELECT sc_files FROM " . PREFIX . "plugins_files_settings");
$ds = mysqli_fetch_array($getlist);

    if ($ds[ 'sc_files' ] == 1) {
        $list = "downloads";
        $poplist = '<h4>'.$_lang[ 'top_5_downloads' ].'</h4>';
    } else {
        $list = "date";
        $poplist = '<h4>'.$_lang[ 'last_5_downloads' ].'</h4>';
    }

    $accesslevel = 1;

        $top5qry = safe_query(
            "SELECT
                *
            FROM
                " . PREFIX . "plugins_files
            WHERE
                accesslevel<=" . $accesslevel . "
            ORDER BY
                " . $list . " DESC
            LIMIT 0,5"
        );
        
        $top5 = '<strong>' . $poplist . '</strong><ul class="list-group list-group-flush">';
        if(!isset($n)) { $n=1; }
        while ($file = mysqli_fetch_array($top5qry)) {

            


            $filename = $file[ 'filename' ];
            if (mb_strlen($filename) > 40) {
                $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($filename);
            $filename = $translate->getTextByLanguage($filename);
            $filename = mb_substr($filename, 0, 40);
            $filename .= '...';
            }
            $filename =
                '<a href="index.php?site=files&amp;file=' . $file[ 'fileID' ] . '"><strong>' .
                $filename . '</strong></a>';
            if ($file[ 'downloads' ] != '0') {
                $top5 .=
                    '<li class="list-group-item"><b>' . $n . '.</b> ' . $filename .
                    '
                        <span class="badge bg-secondary float-end">' . $file[ 'downloads' ] . '</span>  </li>';
            }
            $n++;
        }
        $top5 .= '</ul>';

        $data_array = array();
        $data_array['$totalfiles'] = $totalfiles;
        $data_array['$totalcats'] = $totalcats;
        $data_array['$hddspace'] = $hddspace;
        $data_array['$traffic'] = $traffic;
        $data_array['$lastfile'] = $lastfile;
        $data_array['$top5'] = $top5;

        $data_array['$statistic']=$_lang['statistic'];
        $data_array['$files_cl']=$_lang['files_cl'];
        $data_array['$categories_cl']=$_lang['categories_cl'];
        $data_array['$database_cl']=$_lang['database_cl'];
        $data_array['$traffic_cl']=$_lang['traffic_cl'];
        $data_array['$last_uploaded_file']=$_lang['last_uploaded_file'];
        $data_array['$file_categories']=$_lang['file_categories'];
        $data_array['$category_cl']=$_lang['category_cl'];
        $data_array['$subcategories']=$_lang['subcategories'];
        #$data_array['$downloads_cl']=$_lang['downloads_cl'];        
        $data_array['$dl']=$_lang['dl'];

        
        
    $template = $GLOBALS["_template"]->loadTemplate("files","overview_head", $data_array, $plugin_path);
    echo $template;
###########################################################################################
if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = "";
}

        if ($totalcats) {
            while ($cat = mysqli_fetch_array($catQry)) {
                // cat-information
                $catID = $cat[ 'filecatID' ];
                $sub_cat_qry = get_all_sub_cats($catID, 1);
                $catname = '<a href="index.php?site=files&amp;cat=' . $catID . '"><strong>' .
                    $cat[ 'name' ] . '</strong></a>';
                $subcategories =
                    mysqli_num_rows(
                        safe_query(
                            "SELECT
                                `filecatID`
                            FROM
                                `" . PREFIX . "plugins_files_categories`
                            WHERE
                                " . $sub_cat_qry
                        )
                    ) - 1;

// get all files associated to the catID
                $catFileQry =
                    safe_query(
                        "SELECT
                            *
                        FROM
                            `" . PREFIX . "plugins_files`
                        WHERE
                            " . $sub_cat_qry . " AND
                            `accesslevel` <= " . (int)$accesslevel . "
                        ORDER BY
                            `fileID` DESC"
                    );
                $catFileTotal = mysqli_num_rows($catFileQry);
                if ($catFileTotal || $subcategories) {
                    $traffic = 0;
                    $downloads = 0;
                    $size = 0;
                    while ($file = mysqli_fetch_array($catFileQry)) {
                        
                        $filename = $file[ 'filename' ];
                        $filesize = $file[ 'filesize' ];
                        $fileload = $file[ 'downloads' ];
                        @$traffic += $filesize * $fileload;
                        $downloads += $fileload;
                        @$size += $file[ 'filesize' ];
                    }
                    $size = detectfilesize($size);
                    $traffic = detectfilesize($traffic);

                    // last uploaded file in category
                    $filedata =
                        mysqli_fetch_array(
                            safe_query(
                                "SELECT
                                    *
                                FROM
                                    `" . PREFIX . "plugins_files`
                                WHERE
                                    " . $sub_cat_qry . "
                                ORDER BY
                                    date DESC
                                LIMIT 0,1"
                            )
                        );

                    if(!isset($filedata[ 'fileID' ])) {
                        $fileID = '';
                    }else{
                        $fileID = $filedata[ 'fileID' ];
                    }
                    
                    if(!isset($filedata[ 'filename' ])) {
                        $filename = '';
                    }else{
                        $filename = $filedata[ 'filename' ];
                    }
                    
                    if (mb_strlen($filename) > 20) {
                        $filename = mb_substr($filename, 0, 20);
                        $filename .= '...';
                    }

                    $lastfile_cat = '<a href="index.php?site=files&amp;file=' . $fileID . '" title="' . $filename . '">' . $filename . '</a>';

                    // output
                    $data_array = array();
                    $data_array['$catname'] = $catname;
                    $data_array['$subcategories'] = $subcategories;
                    $data_array['$catFileTotal'] = $catFileTotal;
                    $data_array['$downloads'] = $downloads;

                    $template = $GLOBALS["_template"]->loadTemplate("files","category", $data_array, $plugin_path);
                    echo $template;
                    
                }
            }
        }

    $template = $GLOBALS["_template"]->loadTemplate("files","overview_foot", array(), $plugin_path);
    echo $template;
} else {
        echo ($_lang[ 'no_categories_and_files' ]);
    }
}
