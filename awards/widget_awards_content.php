<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
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
    $plugin_language = $pm->plugin_language("sc_awards", $plugin_path);


echo' <div class="card">
    <div class="card-body">

        <div class="container">
            <div class="row">
                <div class="col-md-12"><h2 class="text-center">OUR AWARDS</h2>
                </div>
                <div class="col-md-12 text-center">ALL AWARDS OF ALL GAMES</div>  
            </div>
        </div>
        <br><br>
        ';    


 echo '<div id="awards-bar" class="con1tainer">';
$filepath = $plugin_path."images/";
$ergebnis = safe_query(
    "SELECT
        *
    FROM
        " . PREFIX . "plugins_awards"
);




if (mysqli_num_rows($ergebnis)) {
echo'<section class="customer-logos slider owl-ca1rousel owl-theme">';
   
    while ($db = mysqli_fetch_array($ergebnis)) {

        $filepath = $plugin_path."images/";
        $awardID = $db[ 'awardID' ];
        $banner = $db[ 'banner' ];
        $img = '/includes/plugins/awards/images/' . $db[ 'banner' ];
        $img_str = '<img src="' . $filepath . $db[ 'banner' ] . '" alt="" title="">';
        $link = '<a href="/index.php?site=awards&action=details&awardID='.$db['awardID'].'" target="_self">' . $img_str . '</a>';


        $data_array = array();
        $data_array['$awardID'] = $awardID;
        $data_array['$link'] = $link;
        
echo'<div class="slide">'.$link.'</div>';


    }
    echo'</section>';
}
echo'
</div>
</div>
</div>
';
?>