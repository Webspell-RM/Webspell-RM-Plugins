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
\¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*/

# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("sponsors", $plugin_path);

    $_language->readModule('sponsors');


    if(isset($_GET['action'])) $action = $_GET['action'];
else $action = '';

if($action=="show"){
    $sponsorID = $_GET['sponsorID'];
    if(isset($sponsorID)){

    $plugin_data = array();
    $plugin_data['$title']=$plugin_language['sponsors'];
    $plugin_data['$subtitle']='Sponsors';

    $template = $GLOBALS["_template"]->loadTemplate("sponsors","head", $plugin_data, $plugin_path);
    echo $template;

    $filepath = $plugin_path."images/";


    $get=safe_query("SELECT * FROM ".PREFIX."plugins_sponsors WHERE sponsorID='".$sponsorID."' LIMIT 0,1");
        $ds=mysqli_fetch_array($get);

 
        $sponsorID = $ds[ 'sponsorID' ];


if (!empty($ds[ 'banner' ])) {
        $pic = '<img class="img-fluid" style="width: 100%; max-width: 150px"  src="' . $filepath . $ds[ 'banner' ] . '" alt="">';
        } else {
        $pic = '<img id="img-upload" class="img-thumbnail" style="width: 100%; max-width: 150px" src="' . $filepath . 'no-image.jpg" alt="">';
        }
        

        $url = str_replace('', '', $ds['url']);
        $sponsor = $ds['name'];
        
        if ($ds['url'] != '') {
            if (stristr($ds['url'], "https://")) {
                $link = '<a href="' . htmlspecialchars($ds['url']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?sponsorID=' . $ds['sponsorID'] . '\', 1000})"  target="_blank" rel="nofollow">' . $url . '</a>';//https
            } else {
                $link = '<a href="http://' . htmlspecialchars($ds['url']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?sponsorID=' . $ds['sponsorID'] . '\', 1000})"  target="_blank" rel="nofollow">' . $url . '</a>';//http
            }
        } else {
            $link = $_language->module[ 'n_a' ];
        }


        $info = $ds['info'];
        
        $script = '<script type="text/javascript"> 
        window.addEventListener("load", function(){
    var box'.$ds['sponsorID'].' = document.getElementById("box_'.$ds['sponsorID'].'")
    box'.$ds['sponsorID'].'.addEventListener("touchstart", function(e){
        setTimeout(function(){window.location.href="\'includes\'modules\'out.php?sponsorID=' . $ds['sponsorID'] . '", 200}) 
        e.preventDefault()
    }, false)
    box'.$ds['sponsorID'].'.addEventListener("touchmove", function(e){
        e.preventDefault()
    }, false)
    box'.$ds['sponsorID'].'.addEventListener("touchend", function(e){
                window.open("'.$ds['url'].'", "_blank")
        e.preventDefault()
    }, false)
}, false)   
</script>'; 

        
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($info);
        $info = $translate->getTextByLanguage($info);
    
        $data_array = array();
        $data_array['$sponsor'] = $sponsor;
        $data_array['$sponsorID'] = $ds['sponsorID'];
        $data_array['$pic'] = $pic;
        $data_array['$info'] = $info;
        $data_array['$link'] = $link;
        
        $template = $GLOBALS["_template"]->loadTemplate("sponsors","content", $data_array, $plugin_path);
        echo $template;
        echo $script;


}

} else {
       
if ($action == "") {

if(isset($_GET['page'])) $page=(int)$_GET['page'];
  else $page = 1;        


    $plugin_data = array();
    $plugin_data['$title']=$plugin_language['sponsors'];
    $plugin_data['$subtitle']='Sponsors';

    $template = $GLOBALS["_template"]->loadTemplate("sponsors","head", $plugin_data, $plugin_path);
    echo $template;

    $filepath = $plugin_path."images/";

  $alle=safe_query("SELECT sponsorID FROM ".PREFIX."plugins_sponsors WHERE displayed = '1'");
  $gesamt = mysqli_num_rows($alle);
  $pages=1;

  
    $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_sponsors_settings");
        $dn = mysqli_fetch_array($settings);

    
        $max = $dn[ 'sponsors' ];
        if (empty($max)) {
        $max = 10;
        }
 

  for ($n=$max; $n<=$gesamt; $n+=$max) {
    if($gesamt>$n) $pages++;
  }


  if($pages>1) $page_link = makepagelink("index.php?site=sponsors", $page, $pages);
    else $page_link='';

  if ($page == "1") {
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_sponsors WHERE displayed = '1' ORDER BY sort LIMIT 0,$max");
    $n=1;
  }
  else {
    $start=$page*$max-$max;
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_sponsors WHERE displayed = '1' ORDER BY sort LIMIT $start,$max");
    $n = ($gesamt+1)-$page*$max+$max;
  } 



$ds = safe_query("SELECT * FROM `" . PREFIX . "plugins_sponsors` ORDER BY `sort`");
    $anzcats = mysqli_num_rows($ds);
    if ($anzcats) {

    
   $n=1;
if (mysqli_num_rows($ergebnis)) {
    while ($ds = mysqli_fetch_array($ergebnis)) {

        if (!empty($ds[ 'banner' ])) {
        $pic = '<img class="img-fluid" style="width: 100%; max-width: 150px"  src="' . $filepath . $ds[ 'banner' ] . '" alt="">';
        } else {
        $pic = '<img id="img-upload" style="width: 100%; max-width: 150px" src="' . $filepath . 'no-image.jpg" alt="">';
        }
        

        $url = str_replace('', '', $ds['url']);
        $sponsor = $ds['name'];
        
        if ($ds['url'] != '') {
            if (stristr($ds['url'], "https://")) {
                $link = '<a href="' . htmlspecialchars($ds['url']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?sponsorID=' . $ds['sponsorID'] . '\', 1000})"  target="_blank" rel="nofollow">' . $url . '</a>';//https
            } else {
                $link = '<a href="http://' . htmlspecialchars($ds['url']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?sponsorID=' . $ds['sponsorID'] . '\', 1000})"  target="_blank" rel="nofollow">' . $url . '</a>';//http
            }
        } else {
            $link = 'n/a';
        }

        $info = $ds['info'];
        
        $script = '<script type="text/javascript"> 
        window.addEventListener("load", function(){
    var box'.$ds['sponsorID'].' = document.getElementById("box_'.$ds['sponsorID'].'")
    box'.$ds['sponsorID'].'.addEventListener("touchstart", function(e){
        setTimeout(function(){window.location.href="\'includes\'modules\'out.php?sponsorID=' . $ds['sponsorID'] . '", 200}) 
        e.preventDefault()
    }, false)
    box'.$ds['sponsorID'].'.addEventListener("touchmove", function(e){
        e.preventDefault()
    }, false)
    box'.$ds['sponsorID'].'.addEventListener("touchend", function(e){
                window.open("'.$ds['url'].'", "_blank")
        e.preventDefault()
    }, false)
}, false)   
</script>'; 

        
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($info);
        $info = $translate->getTextByLanguage($info);
    
        $data_array = array();
        $data_array['$sponsor'] = $sponsor;
        $data_array['$sponsorID'] = $ds['sponsorID'];
        $data_array['$pic'] = $pic;
        $data_array['$info'] = $info;
        $data_array['$link'] = $link;
        
        $template = $GLOBALS["_template"]->loadTemplate("sponsors","content", $data_array, $plugin_path);
        echo $template;
		echo $script;
        
$n++;
}
}

    } else {
        echo $plugin_language['no_sponsors'];
    
    }
  
    if($pages>1) echo $page_link;
    }

}
?>