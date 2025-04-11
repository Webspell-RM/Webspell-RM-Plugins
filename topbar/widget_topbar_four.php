<style>
                       /* .head-boxes {display: none;}*/
.figure {  
  
}

.overlay {margin-bottom: 10px;
height: 350px;}

.figure-img {
    object-fit: cover; 
    width: 100%; 
    height: 350px;
    /*filter: grayscale(100%);*/
    transition: all 0.4s ease 0s;
}

.figure-caption {
  position: absolute;
  left: 18%;
  top: 70px;
}

.noheadcol_title {
  color: #FFF; 
  font-size: 70px;
  text-transform: uppercase;
}

.noheadcol_text {
  color: #FFF;
  font-size: 13px;
  width: 350px;
}

.noheadcol_link {
  color: #FFF; 
  font-size: 20px;
  text-transform: uppercase;
}

.noheadcol_no_pic {
background-color: rgba(var(--bs-body-secondary-color), 0.7);
padding: 15px 0 0 0;
  min-height: 65px;
  margin-top: 0px;
}
.noheadcol_title_no_pic {
  color: rgba(var(--bs-body-color),1); 
  font-size: 30px;
  text-transform: uppercase;
}

.noheadcol_link_no_pic {
  color: rgba(var(--bs-body-color),1); 
  font-size: 20px;
  text-transform: uppercase;
}

@media (max-width: 767px) {
.figure-caption {
  position: absolute;
  left: 18%;
  top: 25px;
}

.noheadcol_title {
  color: #FFF; 
  font-size: 30px;
}

.noheadcol_text {
  display: none;

}

.noheadcol_link {
  color: #FFF; 
  font-size: 25px;
  top: 10px;
}

}  


@media (max-width: 575px) {
.figure-caption {
  position: absolute;
  left: 18%;
  top: 10px;
}

.noheadcol_title {
  color: #FFF; 
  font-size: 20px;
}

.noheadcol_text {
  display: none;

}

.noheadcol_link {
  color: #FFF; 
  font-size: 15px;
  top: 10px;
}

}  
                        </style>


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
$plugin_language = $pm->plugin_language("topbar", $plugin_path);

$_language->readModule('index');
$index_language = $_language->module;


parse_str($_SERVER['QUERY_STRING'], $qs_arr);
            $getsite = 'startpage'; #Wird auf der Startseite angezeigt index.php
        if(isset($qs_arr['site'])) {
          $getsite = $qs_arr['site'];
        }
echo'

    ';
            if(file_exists('./includes/plugins/'.$getsite.'/images/'.$getsite.'_headelement.jpg')){
                $pic='<!--<style>
                        .head-boxes {display: none;}
                        </style>-->
                        <figure class="overlay">
                        <img src="./includes/plugins/'.$getsite.'/images/'.$getsite.'_headelement.jpg" class="figure-img im1g-fluid" alt="...">
                        <figcaption class="figure-caption"><p class="animated fadeInUp noheadcol_title">'.$getsite.'</p>
                        <p class="noheadcol_text">info</p>
                        <p class="noheadcol_link"><a href="#">Home</a> / '.$getsite.'</p></figcaption>
                        </figure>';
            } elseif(file_exists('./includes/plugins/'.$getsite.'/images/'.$getsite.'_headelement.jpeg')){
                $pic='<!--<style>
                        .head-boxes {display: none;}
                        </style>-->
                        <figure class="overlay">
                        <img src="./includes/plugins/'.$getsite.'/images/'.$getsite.'_headelement.jpeg" class="figure-img im1g-fluid" alt="...">
                        <figcaption class="figure-caption"><p class="animated fadeInUp noheadcol_title">'.$name.'</p>
                        <p class="noheadcol_text">'.$info.'</p>
                        <p class="noheadcol_link"><a href="#">Home</a> / '.$name.'</p></figcaption>
                        </figure>';
            } elseif(file_exists('./includes/plugins/'.$getsite.'/images/'.$getsite.'_headelement.png')){
                $pic='<!--<style>
                        .head-boxes {display: none;}
                        </style>-->
                        <figure class="overlay">
                        <img src="./includes/plugins/'.$getsite.'/images/'.$getsite.'_headelement.png" class="figure-img im1g-fluid" alt="...">
                        <figcaption class="figure-caption"><p class="animated fadeInUp noheadcol_title">'.$name.'</p>
                        <p class="noheadcol_text">'.$info.'</p>
                        <p class="noheadcol_link"><a href="#">Home</a> / '.$name.'</p></figcaption>
                        </figure>';
            } elseif(file_exists('./includes/plugins/'.$getsite.'/images/'.$getsite.'_headelement.gif')){
                $pic='<!--<style>
                        .head-boxes {display: none;}
                        </style>-->
                        <figure class="overlay">
                        <img src="./includes/plugins/'.$getsite.'/images/'.$getsite.'_headelement.gif" class="figure-img im1g-fluid" alt="...">
                        <figcaption class="figure-caption"><p class="animated fadeInUp noheadcol_title">'.$name.'</p>
                        <p class="noheadcol_text">'.$info.'</p>
                        <p class="noheadcol_link"><a href="#">Home</a> / '.$name.'</p></figcaption>
                        </figure>';
            } else{
                $pic='<!--<style>
                        .head-boxes {display: none;}
                        </style>-->
                        <div class="container-fluid noheadcol_no_pic">
                            <div class="container">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="noheadcol_title_no_pic">'.$name.'</p>
                                    <p class="noheadcol_link_no_pic"><a href="#">Home</a> / '.$getsite.'</p>
                                </div>
                            </div>
                        </div>';
                
            }
                $head_elements = $pic;
        
        echo''.$head_elements.'';

?>