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
    $plugin_language = $pm->plugin_language("partners", $plugin_path);

$_language->readModule('partners');

$filepath = $plugin_path."images/";

if(isset($_GET['action'])) $action = $_GET['action'];
else $action = '';

if($action=="show"){
    $partnerID = $_GET['partnerID'];
    if(isset($partnerID)){


    $plugin_data = array();
    $plugin_data['$title']=$plugin_language['partners'];
    $plugin_data['$subtitle']='Partners';
    $template = $GLOBALS["_template"]->loadTemplate("partners","head", $plugin_data, $plugin_path);
    echo $template;

    $get=safe_query("SELECT * FROM ".PREFIX."plugins_partners WHERE partnerID='".$partnerID."' ORDER BY `sort` LIMIT 0,1");
        $db=mysqli_fetch_array($get);

 
        $partnerID = $db[ 'partnerID' ];
        $alt = htmlspecialchars($db[ 'name' ]);
        $title = htmlspecialchars($db[ 'name' ]);
        
        if (!empty($db[ 'banner' ])) {
        $pic = '<img class="img-fluid" src="../' . $filepath . $db[ 'banner' ] . '" alt="">';
        } else {
        $pic = '<img class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath . 'no-image.jpg" alt="">';
        }

        $name = $db[ 'name' ];
        
        if ($db['url'] != '') {
            if (stristr($db['url'], "https://")) {
                $link = '<a class="url-link" href="' . htmlspecialchars($db['url']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?partnerID=' . $db['partnerID'] . '\', 1000})"  target="_blank" rel="nofollow"><i class="bi bi-house" style="font-size: 2rem;"></i></a>';//https
            } else {
                $link = '<a class="url-link" href="http://' . htmlspecialchars($db['url']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?partnerID=' . $db['partnerID'] . '\', 1000})"  target="_blank" rel="nofollow"><i class="bi bi-house" style="font-size: 2rem;"></i></a>';//http
            }
        } else {
            $link = $_language->module[ 'n_a' ];
        }



        $info = $db['info'];
        $script = '<script> 
        window.addEventListener("load", function(){
    var box'.$db['partnerID'].' = document.getElementById("box_'.$db['partnerID'].'")
    box'.$db['partnerID'].'.addEventListener("touchstart", function(e){
        setTimeout(function(){window.location.href="../includes/modules/out.php?partnerID=' . $db['partnerID'] . '", 200}) 
        e.preventDefault()
    }, false)
    box'.$db['partnerID'].'.addEventListener("touchmove", function(e){
        e.preventDefault()
    }, false)
    box'.$db['partnerID'].'.addEventListener("touchend", function(e){
                window.open("'.$db['url'].'", "_blank")
        e.preventDefault()
    }, false)
}, false)   
</script>'; 

        $data_array = array();
        if($db['facebook'] != '') $data_array['$facebook'] = '<a class="facebook" href="'.$db['facebook'].'" target="_blank"><i class="bi bi-facebook" style="font-size: 2rem;"></i></a>';
        else $data_array['$facebook'] = '';
        if($db['twitter'] != '') $data_array['$twitter'] = '<a class="twitter" href="'.$db['twitter'].'" target="_blank"><i class="bi bi-twitter-x" style="font-size: 2rem;"></i></a>';
        else $data_array['$twitter'] = '';
        $data_array['$partnerID'] = $partnerID;
        $data_array['$link'] = $link;
        $data_array['$script'] = $script;
        $data_array['$title'] = $title;
        $data_array['$pic'] = $pic;
        $data_array['$info'] = $info;
        
        $template = $GLOBALS["_template"]->loadTemplate("partners","content", $data_array, $plugin_path);
        echo $template;

        
  
}
       
} else {
       
if ($action == "") {

if(isset($_GET['page'])) $page=(int)$_GET['page'];
  else $page = 1; 


$filepath = $plugin_path."images/";

    $plugin_data = array();
    $plugin_data['$title']=$plugin_language['partners'];
    $plugin_data['$subtitle']='Partners';
    $template = $GLOBALS["_template"]->loadTemplate("partners","head", $plugin_data, $plugin_path);
    echo $template;

            
$alle=safe_query("SELECT partnerID FROM ".PREFIX."plugins_partners WHERE displayed = '1'");
  $gesamt = mysqli_num_rows($alle);
  $pages=1;

  
  $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_partners_settings");
        $dn = mysqli_fetch_array($settings);

    
        $max = $dn[ 'partners' ];
        if (empty($max)) {
        $max = 10;
        }
 

  for ($n=$max; $n<=$gesamt; $n+=$max) {
    if($gesamt>$n) $pages++;
  }


  if($pages>1) $page_link = makepagelink("index.php?site=partners", $page, $pages);
    else $page_link='';

  if ($page == "1") {
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_partners WHERE displayed = '1' ORDER BY sort ASC LIMIT 0,$max");
    $n=1;
  }
  else {
    $start=$page*$max-$max;
    $ergebnis = safe_query("SELECT * FROM ".PREFIX."plugins_partners WHERE displayed = '1' ORDER BY sort ASC LIMIT $start,$max");
    $n = ($gesamt+1)-$page*$max+$max;
  } 



$db = safe_query("SELECT * FROM `" . PREFIX . "plugins_partners` ORDER BY `sort`");
    $anzcats = mysqli_num_rows($db);
    if ($anzcats) {

    
   $n=1;
if (mysqli_num_rows($ergebnis)) {
    while ($db = mysqli_fetch_array($ergebnis)) {
        $partnerID = $db[ 'partnerID' ];
        $alt = htmlspecialchars($db[ 'name' ]);
        $title = htmlspecialchars($db[ 'name' ]);
        
        if (!empty($db[ 'banner' ])) {
        $pic = '<img class="img-fluid" src="../' . $filepath . $db[ 'banner' ] . '" alt="">';
        } else {
        $pic = '<img class="img-thumbnail" style="width: 100%; max-width: 150px" src="../' . $filepath . 'no-image.jpg" alt="">';
        }

        $name = $db[ 'name' ];
        
        if ($db['url'] != '') {
            if (stristr($db['url'], "https://")) {
                $link = '<a class="url-link" href="' . htmlspecialchars($db['url']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?partnerID=' . $db['partnerID'] . '\', 1000})"  target="_blank" rel="nofollow"><i class="bi bi-house" style="font-size: 2rem;"></i></a>';//https
            } else {
                $link = '<a class="url-link" href="http://' . htmlspecialchars($db['url']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?partnerID=' . $db['partnerID'] . '\', 1000})"  target="_blank" rel="nofollow"><i class="bi bi-house" style="font-size: 2rem;"></i></a>';//http
            }
        } else {
            $link = $_language->module[ 'n_a' ];
        }


        $info = $db['info'];
        $script = '<script> 
        window.addEventListener("load", function(){
    var box'.$db['partnerID'].' = document.getElementById("box_'.$db['partnerID'].'")
    box'.$db['partnerID'].'.addEventListener("touchstart", function(e){
        setTimeout(function(){window.location.href="../includes/modules/out.php?partnerID=' . $db['partnerID'] . '", 200}) 
        e.preventDefault()
    }, false)
    box'.$db['partnerID'].'.addEventListener("touchmove", function(e){
        e.preventDefault()
    }, false)
    box'.$db['partnerID'].'.addEventListener("touchend", function(e){
                window.open("'.$db['url'].'", "_blank")
        e.preventDefault()
    }, false)
}, false)   
</script>'; 

        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($info);
        $info = $translate->getTextByLanguage($info);

        $data_array = array();
        if($db['facebook'] != '') $data_array['$facebook'] = '<a class="facebook" href="'.$db['facebook'].'" target="_blank"><i class="bi bi-facebook" style="font-size: 2rem;"></i></a>';
        else $data_array['$facebook'] = '';
        if($db['twitter'] != '') $data_array['$twitter'] = '<a class="twitter" href="'.$db['twitter'].'" target="_blank"><i class="bi bi-twitter-x" style="font-size: 2rem;"></i></a>';
        else $data_array['$twitter'] = '';
        $data_array['$partnerID'] = $partnerID;
        $data_array['$link'] = $link;
        $data_array['$script'] = $script;
        $data_array['$title'] = $title;
        $data_array['$pic'] = $pic;
        $data_array['$info'] = $info;
        
        $template = $GLOBALS["_template"]->loadTemplate("partners","content", $data_array, $plugin_path);
        echo $template;

$n++;
}
if($pages>1) echo $page_link;
}

#echo '<div class="row"><br><br></div>';

    } else {
        echo $plugin_language['no_partners'];    
  }
  #echo'<br>';
}

}
?>