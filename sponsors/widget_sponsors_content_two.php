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
\__________________________________________________________________*/
# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("sponsors", $plugin_path);


$filepath = $plugin_path."images/";
$_language->readModule('sponsors');


$filepath = $plugin_path."images/";

$data_array = array();
$data_array['$title_two']=$plugin_language['sponsors_two'];
$template = $GLOBALS["_template"]->loadTemplate("sponsors_two","head_head", $data_array, $plugin_path);
echo $template;

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_sponsors WHERE displayed='1' ORDER BY sort");
if (mysqli_num_rows($ergebnis)) {
    echo '<div class="container-fluid">
            <div class="row" id="sponsors_two">
                <div class="sponsors_two">';
                
    $i = 1;
    while ($ds = mysqli_fetch_array($ergebnis)) {

        if (!empty($ds[ 'banner_small' ])) {
        $pic = '<img style="height: 60px" src="' . $filepath . $ds[ 'banner_small' ] . '" class="img-fluid" alt="Responsive image">';
        } else {
        $pic = '<img src="' . $filepath . '"no-image.jpg" class="img-fluid" alt="Responsive image">';
        }
        

        $url = str_replace('', '', $ds['url']);
        
        if ($ds['url'] != '') {
            if (stristr($ds['url'], "https://")) {
                $link = '<a href="' . htmlspecialchars($ds['url']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?sponsorID=' . $ds['sponsorID'] . '\', 1000})"  target="_blank" rel="nofollow">' . $pic . '</a>';//https
            } else {
                $link = '<a href="http://' . htmlspecialchars($ds['url']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?sponsorID=' . $ds['sponsorID'] . '\', 1000})"  target="_blank" rel="nofollow">' . $pic . '</a>';//http
            }
        } else {
            $link = '';
        }

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
    
        $data_array = array();
        $data_array['$pic'] = $pic;        
        $data_array['$link'] = $link;
        
        $template = $GLOBALS["_template"]->loadTemplate("sponsors_two","content", $data_array, $plugin_path);
        echo $template;
        echo $script;
        
        $i++;
    }
    echo '</div></div></div>';
    $data_array = array();
    $template = $GLOBALS["_template"]->loadTemplate("sponsors_two","foot_foot", $data_array, $plugin_path);
    echo $template;
} else {
    echo $plugin_language['no_sponsors'];
}



?>