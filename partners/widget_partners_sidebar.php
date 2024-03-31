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
    $plugin_language = $pm->plugin_language("partners", $plugin_path);

    $plugin_data= array();
    $plugin_data['$title']=$plugin_language['title'];
    $plugin_data['$subtitle']='Partners';
    
    $template = $GLOBALS["_template"]->loadTemplate("partners","head", $plugin_data, $plugin_path);
    echo $template;

    $template = $GLOBALS["_template"]->loadTemplate("partners","widget_head_head", $plugin_data, $plugin_path);
    echo $template;

$ergebnis = safe_query(
    "SELECT
        *
    FROM
        " . PREFIX . "plugins_partners
    WHERE
        displayed = '1'
    ORDER BY
        sort"
);
if (mysqli_num_rows($ergebnis)) {
    while ($db = mysqli_fetch_array($ergebnis)) {
        $filepath = $plugin_path."images/";
        $partnerID = $db[ 'partnerID' ];
        $banner = $db[ 'banner' ];
        $alt = htmlspecialchars($db[ 'name' ]);
        $title = htmlspecialchars($db[ 'name' ]);
        $img = '/includes/plugins/partners/images/' . $db[ 'banner' ];
        $name = $db[ 'name' ];
        $img_str = '<img class="img-fluid" style="height: 35px" src="' . $filepath . $db[ 'banner' ] . '" alt="' . $alt . '" data-toggle="tooltip" data-bs-html="true" title="' . $title . '">';
        if (is_file($img) && file_exists($img)) {
            $text = $img_str;
        } else {
            $text = $name;
        }
        
        if ($db['url'] != '') {
            if (stristr($db['url'], "https://")) {
                $link = '<a href="' . htmlspecialchars($db['url']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?partnerID=' . $db['partnerID'] . '\', 1000})"  target="_blank" rel="nofollow">' . $img_str . '</a>';//https
            } else {
                $link = '<a href="http://' . htmlspecialchars($db['url']) . '" onclick="setTimeout(function(){window.location.href=\'../includes/modules/out.php?partnerID=' . $db['partnerID'] . '\', 1000})"  target="_blank" rel="nofollow">' . $img_str . '</a>';//http
            }
        } else {
            $link = $img_str;
        }
        
        $script = '<script> 
        window.addEventListener("load", function(){
    var boy'.$db['partnerID'].' = document.getElementById("box_'.$db['partnerID'].'")
    boy'.$db['partnerID'].'.addEventListener("touchstart", function(e){
        setTimeout(function(){window.location.href="out.php?partnerID=' . $db['partnerID'] . '", 200}) 
        e.preventDefault()
    }, false)
    boy'.$db['partnerID'].'.addEventListener("touchmove", function(e){
        e.preventDefault()
    }, false)
    boy'.$db['partnerID'].'.addEventListener("touchend", function(e){
                window.open("'.$db['url'].'", "_blank")
        e.preventDefault()
    }, false)
}, false)   
</script>';
        
        $data_array = array();
        $data_array['$partnerID'] = $partnerID;
        $data_array['$link'] = $link;
        $data_array['$script'] = $script;
        $data_array['$title'] = $title;
        
        
        $template = $GLOBALS["_template"]->loadTemplate("partners","widget_content", $data_array, $plugin_path);
        echo $template;
    }
    
    $template = $GLOBALS["_template"]->loadTemplate("partners","widget_foot_foot", $data_array, $plugin_path);
    echo $template;
}
