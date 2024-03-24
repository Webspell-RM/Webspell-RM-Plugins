<?php
/*
 ########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2006 by webspell.org                                  #
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
 ########################################################################
*/

# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("memberslist", $plugin_path);



    $data_array = array();
    $data_array['$title']=$plugin_language[ 'title' ];
    $data_array['$subtitle']='Members List';
    $template = $GLOBALS["_template"]->loadTemplate("memberslist","head", $data_array, $plugin_path);
    echo $template;

$alle = safe_query("SELECT squadID FROM " . PREFIX . "plugins_squads");
    $gesamt = mysqli_num_rows($alle);
    $ergebnis =
            safe_query(
                "SELECT
                    *
                FROM
                    " . PREFIX . "plugins_squads"
            );

$anz=mysqli_num_rows($ergebnis);
if($anz) {


    
$settings = safe_query("SELECT * FROM " . PREFIX . "plugins_memberslist");
    $ds = mysqli_fetch_array($settings);
    
        $maxusers = $ds[ 'users' ];
        if (empty($maxusers)) {
        $maxusers = 10;
        }

$alle = safe_query("SELECT userID FROM ".PREFIX."plugins_squads_members group by userID");
$gesamt = mysqli_num_rows($alle);
$pages=1;
if(!isset($page)) $page = 1;
if(!isset($sort)) $sort="userID";
if(!isset($type)) $type = "ASC";
		
$max=$maxusers;
				
for ($n=$max; $n<=$gesamt; $n+=$max) {
    if($gesamt>$n) $pages++;
}
		 	
if($pages>1) $page_link = makepagelink("index.php?site=memberlist&sort=$sort&type=$type", $page, $pages);
	
if ($page == "1") {
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_squads_members group by userID ORDER BY $sort $type LIMIT 0,$max");
	if($type=="DESC") $n=$gesamt;
	else $n=1;
}
else {
    $start=$page*$max-$max;
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_squads_members group by userID ORDER BY $sort $type LIMIT $start,$max");
	if($type=="DESC") $n = ($gesamt)-$page*$max+$max;
	else $n = ($gesamt+1)-$page*$max+$max;
}

    $data_array = array();
    $data_array['$gesamt'] = $gesamt;
    $data_array['$registered_users']=$plugin_language[ 'registered_users' ];
    $data_array['$nickname']=$plugin_language[ 'nickname' ];
    $data_array['$contact']=$plugin_language[ 'contact' ];
    $data_array['$last_login']=$plugin_language[ 'last_login' ];
    $data_array['$squad']=$plugin_language[ 'squad' ];
    $data_array['$position']=$plugin_language[ 'position' ];
    $data_array['$activity']=$plugin_language[ 'activity' ];
    $data_array['$banner']=$plugin_language[ 'banner' ];

    $template = $GLOBALS["_template"]->loadTemplate("memberslist","header", $data_array, $plugin_path);
    echo $template;


    $alle = safe_query("SELECT userID FROM " . PREFIX . "user");
    $gesamt = mysqli_num_rows($alle);
    $ergebnis =
            safe_query(
                "SELECT
                    *
                FROM
                    " . PREFIX . "user"
            );


$anz=mysqli_num_rows($ergebnis);
if($anz) {  
   while ($ds = mysqli_fetch_array($ergebnis)) {
     
    $test = safe_query("SELECT * FROM " . PREFIX . "user where userID='".isclanmember($ds[ 'userID' ])."'");  
    while ($dm = mysqli_fetch_array($test)) {
    $n=1;
        if (isclanmember($dm[ 'userID' ])) {
            $nickname = '<a href="index.php?site=profile&amp;id=' . $ds[ 'userID' ] . '"><b>' . strip_tags($ds[ 'nickname' ]) . '</b></a>';
        } else {
            $nickname = '';
        }
            
        if (isclanmember($dm[ 'userID' ])) {
            $member = ' <i class="bi bi-person" style="color:#5cb85c;"></i>';
        } else {
            $member = '';
        }

        if ($getavatar = getavatar($ds[ 'userID' ])) {
            $avatar = './images/avatars/' . $getavatar . '';
        } else {
            $avatar = '';
        }

        $activity = '';
        $abc = safe_query("SELECT position,squadID,activity FROM " . PREFIX . "plugins_squads_members " . PREFIX . "user WHERE userID = ".$ds[ 'userID' ]."");         
        while ($dx = mysqli_fetch_array($abc)) {

            if ($dx[ 'activity' ] != '1') {
                $activity = '<span class="badge bg-warning">' . $plugin_language[ 'inactive' ] . '</span>';
            } elseif ($dx[ 'activity' ] != '0') {
                
                $activity = '<span class="badge bg-success">' . $plugin_language[ 'active' ] . '</span>';
            }
        }

        $data_array = array();
        $data_array['$nickname'] = $nickname;
        $data_array['$member'] = $member;
        $data_array['$avatar'] = $avatar;
        $data_array['$activity'] = $activity;        

        $template = $GLOBALS["_template"]->loadTemplate("memberslist","content_head", $data_array, $plugin_path);
        echo $template;
/*=================Abfrage Position Squad==================*/


$abc = safe_query("SELECT position,squadID,activity FROM " . PREFIX . "plugins_squads_members " . PREFIX . "user WHERE userID = ".$ds[ 'userID' ]."");
        
while ($dx = mysqli_fetch_array($abc)) {

        if ($dx['position'] != '') {
            $position = $dx[ 'position' ]; 
        } else {
            $position = "n/a";
        }        

        if (isset($_POST[ 'squadID' ])) {
            $onesquadonly = 'WHERE squadID="' . (int)$_POST[ 'squadID' ] . '"';
            $visible = "block";
        } elseif (isset($_GET[ 'squadID' ])) {
            $onesquadonly = 'WHERE squadID="' . (int)$_GET[ 'squadID' ] . '"';
            $visible = "block";
        } else {
            $visible = "none";
            $onesquadonly = '';
        }
        

    $xyz = safe_query("SELECT * FROM " . PREFIX . "plugins_squads WHERE squadID = ".$dx[ 'squadID' ]."");    
    while ($dy = mysqli_fetch_array($xyz)) {
        if ($dy['name'] != '') {
            $name = $dy[ 'name' ];    
        } else {
            $name = "n/a";
        }

		/*if(file_exists('images/squadicons/'.$dx[ 'squadID' ].'_small.jpg')){
            $pic=''.$dx[ 'squadID' ].'_small.jpg';
        } elseif(file_exists('images/squadicons/'.$dx[ 'squadID' ].'_small.jpeg')){
            $pic=''.$dx[ 'squadID' ].'_small.jpeg';
        } elseif(file_exists('images/squadicons/'.$dx[ 'squadID' ].'_small.png')){
            $pic=''.$dx[ 'squadID' ].'_small.png';
        } elseif(file_exists('images/squadicons/'.$dx[ 'squadID' ].'_small.gif')){
            $pic=''.$dx[ 'squadID' ].'_small.gif';
        } else{
           $pic='';
        }*/

        $squadID = $dx['squadID'];

        if(file_exists("./includes/plugins/squads/images/squadicons/".$squadID."_small.jpg")){
                    $pic="/includes/plugins/squads/images/squadicons/".$squadID."_small.jpg";
                } elseif(file_exists("./includes/plugins/squads/images/squadicons/".$squadID."_small.jpeg")){
                    $pic="/includes/plugins/squads/images/squadicons/".$squadID."_small.jpeg";
                } elseif(file_exists("./includes/plugins/squads/images/squadicons/".$squadID."_small.png")){
                    $pic="/includes/plugins/squads/images/squadicons/".$squadID."_small.png";
                } elseif(file_exists("./includes/plugins/squads/images/squadicons/".$squadID."_small.gif")){
                    $pic="/includes/plugins/squads/images/squadicons/".$squadID."_small.gif";
                } else{
                    $pic="/includes/plugins/squads/images/squadicons/no-image.jpg";
                }

        $banner = $pic;

        $data_array = array();
        $data_array['$position'] = $position;        
        $data_array['$name'] = $name;
        $data_array['$banner'] = $banner;

        $data_array['$lang_squad']=$plugin_language[ 'squad' ];
        $data_array['$lang_position']=$plugin_language[ 'position' ];

        $template = $GLOBALS["_template"]->loadTemplate("memberslist","position", $data_array, $plugin_path);
        echo $template;

    }
}

/*=================Abfrage Position Squad END==================*/
    
        
        if ($ds[ 'email_hide' ]) {
            $email = '';
        } else {
            $email = '<a class="badge bg-danger" href="mailto:' . mail_protect($ds[ 'email' ]) .
                '"><i class="bi bi-envelope" title="email"></i> email</a>';
        }

        if ($ds['homepage'] != '') {
            if (stristr($ds[ 'homepage' ], "https://")) {
                $homepage = '<a href="' . htmlspecialchars($ds[ 'homepage' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-house" style="font-size:18px;"></i></a>';//https
            } else {
                $homepage = '<a href="http://' . htmlspecialchars($ds[ 'homepage' ]) . '" target="_blank" rel="nofollow"><i class="bi bi-house" style="font-size:18px;"></i></a>';//http
            }
        } else {
            $homepage = "n/a";
        }

        $pm = '';
        
        if ($loggedin && $ds[ 'userID' ]) {
            $pm = '<a class="badge bg-success" href="index.php?site=messenger&amp;action=touser&amp;touser=' . $ds[ 'userID' ] .
                '"><i class="bi bi-messenger"></i> ' . $plugin_language[ 'message' ] . '</a>';            
        }else{
            $pm = '';
        }

        $lastlogindate = getformatdate($ds[ 'lastlogin' ]);
        $lastlogintime = getformattime($ds[ 'lastlogin' ]);
        $registereddate = getformatdate($ds[ 'registerdate' ]);
        $status = isonline($ds[ 'userID' ]);

        if ($status == "offline") {
            $login = $lastlogindate . ' - ' . $lastlogintime;
        } else {
            $login = '<span class="badge bg-success"> ' . $plugin_language[ 'now_on' ] . '</span>';
        }

        if ($ds[ 'userdescription' ]) {
            $userdescription = $dm[ 'userdescription' ];
        } else {
            $userdescription = $plugin_language[ 'no_description' ];
        }

        $profil = '<a  href="/index.php?site=profile&id='.$ds[ 'userID' ].'" class="badge bg-primary">' . $plugin_language[ 'profil' ] . ' <i class="bi bi-person"></i></a>';

        $data_array = array();
        $data_array['$email'] = $email;
        $data_array['$pm'] = $pm;
        $data_array['$homepage'] = $homepage;
        $data_array['$login'] = $login; 
        $data_array['$userdescription'] = $userdescription;
        $data_array['$profil'] = $profil;
        
        $template = $GLOBALS["_template"]->loadTemplate("memberslist","content_foot", $data_array, $plugin_path);
        echo $template;
        }$n++;
    }    

    $template = $GLOBALS["_template"]->loadTemplate("memberslist","foot", $data_array, $plugin_path);
    echo $template;

}
else {echo'no users';
}

}else {
    
    echo $plugin_language['no_team'];
    $template = $GLOBALS["_template"]->loadTemplate("widget_memberslist","foot", $data_array, $plugin_path);
    echo $template;
}