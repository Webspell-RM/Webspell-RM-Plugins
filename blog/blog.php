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

$filepath = $plugin_path."images/";

if(isset($_GET['action'])) $action = $_GET['action'];
else $action = '';

		$data_array = array();
        $data_array['$title']=$plugin_language['blog'];
        $data_array['$subtitle']='Blog';

       
		$template = $GLOBALS["_template"]->loadTemplate("blog","title", $data_array, $plugin_path);
    	echo $template;

if($action=='new') {
	if($loggedin) {
		$CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();



		$comments='<option value="0">'.$plugin_language['no_comments'].'</option>
		<option value="1">'.$plugin_language['user_comments'].'</option>
		<option value="2" selected="selected">'.$plugin_language['visitor_comments'].'</option>';
		
	    $data_array = array();
        $data_array['$userID'] = $userID;
        $data_array['$comments'] = $comments;
        $data_array['$hash'] = $hash;

        $data_array['$add_entry']=$plugin_language['add_entry'];
        $data_array['$title']=$plugin_language['title'];
        $data_array['$text']=$plugin_language['text'];
        $data_array['$save']=$plugin_language['save'];
        $data_array['$lang_banner'] = $plugin_language['banner'];
		
		

       
	    $template = $GLOBALS["_template"]->loadTemplate("blog","new", $data_array, $plugin_path);
    	echo $template;

	}
}
elseif($action=='edit') {
	if($loggedin) {
		$CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

		$blogID = (int)$_GET['blogID'];
		if(!ispageadmin($userID)) {
			$and = 'AND userID=$userID';
		}
		else {
			$and = '';
		}
		$get = safe_query("SELECT * FROM ".PREFIX."plugins_blog WHERE blogID='$blogID' ".$and." LIMIT 0,1");
		if(mysqli_num_rows($get)) {

		$ds = mysqli_fetch_array($get);
		$msg = $ds['msg'];
		$blog = $ds['blogID'];
		$banner = $ds[ 'banner' ];
		$comments='<option value="0">'.$plugin_language['no_comments'].'</option><option value="1">'.$plugin_language['user_comments'].'</option><option value="2" selected="selected">'.$plugin_language['visitor_comments'].'</option>';

		if (!empty($ds[ 'banner' ])) {
        $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath .'article/' . $banner . '" alt="">';
    } else {
        $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath . 'no-image.jpg" alt="">';
    }
			
			$data_array = array();
            $data_array['$headline'] = $ds['headline'];
            $data_array['$blogID'] = $blogID;
            $data_array['$msg'] = $msg;
            $data_array['$comments'] = $comments;
            $data_array['$pic'] = $pic;
            $data_array['$hash'] = $hash;

            $data_array['$edit_entry']=$plugin_language['edit_entry'];
            $data_array['$title']=$plugin_language['title'];
            $data_array['$text']=$plugin_language['text'];
            $data_array['$edit']=$plugin_language['edit'];
            $data_array['$lang_banner'] = $plugin_language['banner'];
            $data_array['$current_banner'] = $plugin_language['current_banner'];
			
			
            
			$template = $GLOBALS["_template"]->loadTemplate("blog","edit", $data_array, $plugin_path);
    		echo $template;

		}
		else echo $plugin_language['no_id'];
	}
}
elseif(isset($_POST['save'])) {
	$CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {

		$headline = $_POST['headline'];
		$msg = $_POST['message'];
		$user = $_POST['userID'];
		$comments = $_POST['comments'];
		$date = time();

		safe_query("INSERT INTO ".PREFIX."plugins_blog (date, userID, msg, headline, comments) VALUES ('".$date."', '".$user."', '".$msg."', '".$headline."', '".$comments."')");

		$id = mysqli_insert_id($_database);
        #\webspell\Tags::setTags('links', $id, $_POST[ 'tags' ]);

        $filepath = $plugin_path."images/article/";
	    //TODO: should be loaded from root language folder
        
        $upload = new \webspell\HttpUpload('banner');

        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation =  getimagesize($upload->getTempFile());

                    if (is_array($imageInformation)) {
                        if ($imageInformation[0] < 1921 && $imageInformation[1] < 1081) {
                            switch ($imageInformation[ 2 ]) {
                                case 1:
                                    $endung = '.gif';
                                    break;
                                case 3:
                                    $endung = '.png';
                                    break;
                                default:
                                    $endung = '.jpg';
                                    break;
                            }
                            $file = $id.$endung;

                            if (file_exists($filepath . $id . '.gif')) {
                                unlink($filepath . $id . '.gif');
                            }
                            if (file_exists($filepath . $id . '.jpg')) {
                                unlink($filepath . $id . '.jpg');
                            }
                            if (file_exists($filepath . $id . '.png')) {
                                unlink($filepath . $id . '.png');
                            }

                            if ($upload->saveAs($filepath.$file)) {
                                @chmod($filepath.$file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_blog
                                    SET banner='" . $file . "' WHERE blogID='" . $id . "'"
                                );
                            }
                        } else {
                            echo generateErrorBox(sprintf($plugin_language[ 'image_too_big' ], 1920, 1080));
                        }
                    } else {
                        echo generateErrorBox($plugin_language[ 'broken_image' ]);
                    }
                } else {
                    echo generateErrorBox($plugin_language[ 'unsupported_image_type' ]);
                }
            } else {
                echo  generateErrorBox($upload->translateError());
            }
        }
    } else {
        echo  $plugin_language[ 'transaction_invalid' ];
    }

	redirect("index.php?site=blog&amp;action=show&amp;blogID=".$id,'',1);
}
elseif(isset($_POST['saveedit'])){
	$CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {

	$headline = $_POST['headline'];
	$msg = $_POST['message'];
	$comments = $_POST['comments'];

	$blogID = (int)$_POST[ 'blogID' ];
        $id = $blogID;
	safe_query("UPDATE ".PREFIX."plugins_blog SET headline='".$headline."', msg='".$msg."', comments='".$comments."' WHERE blogID='".$blogID."'");

	$filepath = $plugin_path."images/article/";
	    //TODO: should be loaded from root language folder
        
        $upload = new \webspell\HttpUpload('banner');

        if ($upload->hasFile()) {
            if ($upload->hasError() === false) {
                $mime_types = array('image/jpeg','image/png','image/gif');
                if ($upload->supportedMimeType($mime_types)) {
                    $imageInformation =  getimagesize($upload->getTempFile());

                    if (is_array($imageInformation)) {
                        if ($imageInformation[0] < 1921 && $imageInformation[1] < 1081) {
                            switch ($imageInformation[ 2 ]) {
                                case 1:
                                    $endung = '.gif';
                                    break;
                                case 3:
                                    $endung = '.png';
                                    break;
                                default:
                                    $endung = '.jpg';
                                    break;
                            }
                            $file = $id.$endung;

                            if (file_exists($filepath . $id . '.gif')) {
                                unlink($filepath . $id . '.gif');
                            }
                            if (file_exists($filepath . $id . '.jpg')) {
                                unlink($filepath . $id . '.jpg');
                            }
                            if (file_exists($filepath . $id . '.png')) {
                                unlink($filepath . $id . '.png');
                            }

                            if ($upload->saveAs($filepath.$file)) {
                                @chmod($filepath.$file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_blog
                                    SET banner='" . $file . "' WHERE blogID='" . $blogID . "'"
                                );
                            }
                        } else {
                            echo generateErrorBox(sprintf($plugin_language[ 'image_too_big' ], 1920, 1080));
                        }
                    } else {
                        echo generateErrorBox($plugin_language[ 'broken_image' ]);
                    }
                } else {
                    echo generateErrorBox($plugin_language[ 'unsupported_image_type' ]);
                }
            } else {
                echo  generateErrorBox($upload->translateError());
            }
        }
    } else {
        echo  $plugin_language[ 'transaction_invalid' ];
    }
	redirect("index.php?site=blog&amp;action=show&amp;blogID=".$blogID,'',1);
}
elseif(isset($_GET['delete'])){
	$blogID = (int)$_GET['blogID'];
	$check = safe_query("SELECT * FROM ".PREFIX."plugins_blog WHERE blogID='".$blogID."'");
	$ds = mysqli_fetch_array($check);
	if(ispageadmin($userID) || ($loggedin && $userID==$ds['userID'])){
		safe_query("DELETE FROM ".PREFIX."plugins_blog WHERE blogID='".$blogID."'");
		redirect("index.php?site=blog&amp;user=".$ds['userID'],'',1);
	}
} elseif (isset($_POST[ 'saveeditcomment' ])) {
    
    if (!isfeedbackadmin($userID) && !isvideocommentposter($userID, $_POST[ 'commentID' ])) {
        die('No access');
    }
 
    $message = $_POST[ 'message' ];
    $author = $_POST[ 'authorID' ];
    $referer = urldecode($_POST[ 'referer' ]);
 
    // check if any admin edited the post
    if (safe_query(
        "UPDATE
                `" . PREFIX . "plugins_blog_comments`
            SET
                comments='" . $message . "'
            WHERE
                commentID='" . (int)$_POST[ 'commentID' ] . "'"
    )
    ) {
        header("Location: " . $referer);
    }
} elseif ($action == "editcomment") {
    # Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("comments", $plugin_path);

    $id = $_GET[ 'id' ];
    $referer = $_GET[ 'ref' ];
    
    if (isfeedbackadmin($userID) || isvideocommentposter($userID, $id)) {
        if (!empty($id)) {
            $dt = safe_query("SELECT * FROM " . PREFIX . "plugins_blog_comments WHERE commentID='" . (int)$id."'");
            if (mysqli_num_rows($dt)) {
                $ds = mysqli_fetch_array($dt);
                $poster = '<a href="index.php?site=profile&amp;id=' . $ds[ 'userID' ] . '"><b>' .
                    getnickname($ds[ 'userID' ]) . '</b></a>';
                $message = getinput($ds[ 'comments' ]);
                $message = preg_replace("#\n\[br\]\[br\]\[hr]\*\*(.+)#si", '', $message);
                $message = preg_replace("#\n\[br\]\[br\]\*\*(.+)#si", '', $message);
 
                $data_array = array();
                $data_array['$message'] = $message;
                $data_array['$authorID'] = $ds['userID'];
                $data_array['$id'] = $id;
                $data_array['$userID'] = $userID;
                $data_array['$referer'] = $referer;
               
                $data_array['$title_editcomment']=$plugin_language['title_editcomment'];
                $data_array['$edit_comment']=$plugin_language['edit_comment'];    
                
                $template = $GLOBALS["_template"]->loadTemplate("comments","edit", $data_array, $plugin_path);
                echo $template;
            } else {
                redirect($referer, $plugin_language[ 'no_database_entry' ], 2);
            }
        } else {
            redirect($referer, $plugin_language[ 'no_commentid' ], 2);
        }
    } else {
        redirect($referer, $plugin_language[ 'access_denied' ], 2);
    }

}elseif($action=="show"){
	$blogID = $_GET['blogID'];
	if(isset($blogID)){
		$get=safe_query("SELECT * FROM ".PREFIX."plugins_blog WHERE blogID='".$blogID."' LIMIT 0,1");
		safe_query("UPDATE ".PREFIX."plugins_blog SET visits=visits+1 WHERE blogID='".$blogID."'");
		$ds=mysqli_fetch_array($get);

		echo'<nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?site=blog">Blog</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.getnickname($ds['userID']).'\'s Blog</li>
                </ol>
            </nav>';
		
		if($loggedin) echo'<a class="btn btn-success" href="index.php?site=blog&amp;action=new"><i class="bi bi-plus"></i> '. $plugin_language[ 'new_entry' ] . '</a>';
		echo' <a class="btn btn-info" href="index.php?site=blog&amp;action=detail&amp;user='.$ds['userID'].'"><i class="bi bi-archive"></i> '.$plugin_language['showall_usr'].' '.getnickname($ds['userID']).'</a><br /><br />
		';
		
			$day = date("d", $ds['date']);

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

		$msg = $ds['msg'];
		$headline = $ds['headline'];
		if(ispageadmin($userID) || ($loggedin && $userID==$ds['userID'])) 
		$adm = '<a class="btn btn-warning" href="index.php?site=blog&amp;action=edit&blogID='.$ds['blogID'].'"><i class="bi bi-pencil"></i> '.$plugin_language['edit'].'</a> 
				<a class="btn btn-danger" href="javascript:void(0)" onclick="MM_confirm(\''.$plugin_language['rly_delete'].'\', \'index.php?site=blog&amp;delete=true&amp;blogID='.$ds['blogID'].'\')"><i class="bi bi-trash"></i> '.$plugin_language['delete'].'</a>';
		else $adm='';

			$banner=$ds['banner'];

			if($ds['banner']) {
				#$image="/includes/plugins/blog/images/article/".$banner;

			$image='
              <img class="card-img-top img-fluid"style="height: auto;width: 320px;" src="/includes/plugins/blog/images/article/'.$banner.'" alt="">
            ';
			}
			else {
				#$image="";
                $image='<div class="py-3 px-4 border-end text-center"><div class="card-img-wrapper overlay-rounded-top">
              <img class="card-img" style="height: 220px;width: auto;" src="/includes/plugins/blog/images/no-image.jpg" alt="">
            </div></div>';
			}


			$translate = new multiLanguage(detectCurrentLanguage());
    		$translate->detectLanguages($msg);
    		$msg = $translate->getTextByLanguage($msg);
    		$translate->detectLanguages($headline);
    		$headline = $translate->getTextByLanguage($headline);		
		
			$nickname = getnickname($ds['userID']);
		
	        $data_array = array();
	        $data_array['$adm'] = $adm;
	        $data_array['$day'] = $day;
        	$data_array['$date2'] = $monate[$monat];
	        $data_array['$nickname'] = $nickname;
	        $data_array['$msg'] = $msg;
	        $data_array['$headline'] = $headline;
	        $data_array['$image'] = $image;
			
			$data_array['$by'] = $plugin_language['by'];
			$data_array['$on'] = $plugin_language['on'];
        
			$template = $GLOBALS["_template"]->loadTemplate("blog","detail", $data_array, $plugin_path);
    		echo $template;

		$comments_allowed = $ds[ 'comments' ];
        if ($ds[ 'blogID' ]) {
            $parentID = $blogID;
            $type = "bl";
        }

        $referer = "index.php?site=blog&amp;action=show&amp;blogID=".(int)$_GET['blogID']."";

        include("blog_comments.php");

	}
	else{
		echo $plugin_language['enter_id'];
	}
	
}
elseif($action=="archiv"){
	if(isset($_GET['user'])) $user = (int)$_GET['user'];
	else $user = '';
	if($user) {	

    	echo'<nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?site=blog">Blog</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.getnickname($user).'\'s Blog '.$plugin_language['archive'].'</li>
                </ol>
            </nav>  
            <div class="section-content">';
		
	$search=" WHERE userID='$user' ";
	$msg=" von ".getnickname($user);

}else {

	echo'<nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?site=blog">Blog</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$plugin_language['archive'].'</li>
                </ol>
            </nav>  
            <div class="card">
    <div class="card-body">';

	$search="";
	$msg="";
}
	$get=safe_query("SELECT * FROM ".PREFIX."plugins_blog ".$search." ORDER BY date DESC");
	echo '<table class="table">
		<thead>
			<th width="30%">'.$plugin_language['date'].'</th>
			<th>Name</th>
			<th>User</th>
		</thead><tbody>';
	if(mysqli_num_rows($get)){
		while($ds=mysqli_fetch_array($get)){

		$headline = $ds['headline'];
		$translate = new multiLanguage(detectCurrentLanguage());
    	$translate->detectLanguages($headline);
    	$headline = $translate->getTextByLanguage($headline);
			
		$nickname = getnickname($ds['userID']);
		$date = date("H:i - d.m.Y", $ds['date']);

        $data_array = array();
        $data_array['$date'] = $date;
        $data_array['$nickname'] = $nickname;
        $data_array['$headline'] = $headline;
        $data_array['$blogID'] = $ds['blogID'];
		
		
		

            
		$template = $GLOBALS["_template"]->loadTemplate("blog","archiv", $data_array, $plugin_path);
    	echo $template;

		}
	}else{
		echo '<tr><td colspan="3">'.$plugin_language['no_entry'].''.$msg.'</td></tr>';
	}
	echo '</tbody></table></div></div>';

}else {
	if(isset($_GET['user'])) $user = (int)$_GET['user'];
	else $user = '';
	
	$settings = safe_query("SELECT * FROM " . PREFIX . "plugins_blog_settings");
    $dn = mysqli_fetch_array($settings);

	$max = $dn[ 'blog' ];
        if (empty($max)) {
        $max = 1;
    }
	if($user) {

	echo'<nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?site=blog">Blog</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.getnickname($user).'\'s Blog </li>
                </ol>
            </nav>';

		$search = "WHERE userID='$user' ";
		$msg=' '.$plugin_language['by'].' '.getnickname($user);
		$search2='&amp;user='.$user;
		if($loggedin) {
		echo'<a class="btn btn-success" href="index.php?site=blog&amp;action=new"><i class="bi bi-plus"></i> '. $plugin_language[ 'new_entry' ] . '</a>';
		}
	echo' <a class="btn btn-info" href="index.php?site=blog&amp;action=archiv"><i class="bi bi-archive"></i> '.$plugin_language['archive'].'</a>
	 <a class="btn btn-info" href="index.php?site=blog&amp;action=detail&amp;user='.$userID.'"><i class="bi bi-archive"></i> '.$plugin_language['showall_usr'].' '.getnickname($user).'</a><br /><br />
		';

	}else {	
		
		$search="";
		$msg='';
		$search2='';
		if($loggedin) {
			echo'<a class="btn btn-success" href="index.php?site=blog&amp;action=new"><i class="bi bi-plus"></i> '. $plugin_language[ 'new_entry' ] . '</a>';
		}
			echo' <a class="btn btn-info" href="index.php?site=blog&amp;action=archiv"><i class="bi bi-archive"></i> '.$plugin_language['archive'].'</a><br><br>';
		}

	echo'<div class="row">';

	$get=safe_query("SELECT * FROM ".PREFIX."plugins_blog ".$search." ORDER BY date DESC LIMIT 0,$max");	
	
	if(mysqli_num_rows($get)){
		while($ds=mysqli_fetch_array($get)){
			$blogID = $ds['blogID'];
			$date = date("d", $ds['date']);
			#$date2 = date("m", $ds['date']);

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
			$headline = $ds['headline'].'';
			$banner=$ds['banner'];

			if($ds['banner']) {
				$image="/includes/plugins/blog/images/article/".$banner;
			}
			else {
				$image="/includes/plugins/blog/images/no-image.jpg";
			}

            $maxblogchars = 20;
            if(mb_strlen($headline)>$maxblogchars) {
                $headline=mb_substr($headline, 0, $maxblogchars);
                $headline.='...';
            }			

			$nickname = getnickname($ds['userID']);

			$translate = new multiLanguage(detectCurrentLanguage());
    		$translate->detectLanguages($msg);
    		$msg = $translate->getTextByLanguage($msg);
    		$translate->detectLanguages($headline);
    		$headline = $translate->getTextByLanguage($headline);
		
        	$data_array = array();
        	$data_array['$date'] = $date;
        	$data_array['$date2'] = $monate[$monat];
        	$data_array['$nickname'] = $nickname;
        	#$data_array['$msg'] = $msg;
        	$data_array['$headline'] = $headline;
        	$data_array['$blogID'] = $blogID;
        	$data_array['$image'] = $image;
			
			$data_array['$by'] = $plugin_language['by'];
	        
			$template = $GLOBALS["_template"]->loadTemplate("blog","all_detail", $data_array, $plugin_path);
    		echo $template;

	}
echo'</div>';
}else {

	if($user) {
			echo getnickname($user).$plugin_language['no_entry'];
	}else echo $plugin_language['no_entry'];
	}
	
}

?>