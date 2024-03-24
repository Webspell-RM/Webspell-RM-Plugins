<link rel=preconnect href=https://fonts.googleapis.com>
<link rel=preconnect href=https://fonts.gstatic.com crossorigin>

<link href="https://fonts.googleapis.com/css2?display=optional&amp;family=Bebas+Neue&amp;family=Cinzel%3Awght%40600" rel=preload as=style onload="this.onload=null;this.rel='stylesheet'">

<noscript><link href="https://fonts.googleapis.com/css2?display=optional&amp;family=Bebas+Neue&amp;family=Cinzel%3Awght%40600" rel=stylesheet></noscript>

<?php
/*-----------------------------------------------------------------\
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
\------------------------------------------------------------------*/

# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("navigation", $plugin_path);
    GLOBAL $logo,$theme_name,$themes,$tpl,$loggedin,$index_language,$modRewrite,$action;


    $ergebnis=safe_query("SELECT * FROM ".PREFIX."settings_themes WHERE active = 1");
        $ds=mysqli_fetch_array($ergebnis);
    ?>

	<nav class="navbar navbar-expand-lg navbar-dark bg-pr1imary fixed-top">
  <div class="container-fluid">
    <span class="logo"><a class="navbar-brand" href="#"><img class="float-left bg-primary img-fluid" style="max-width:100%;height: 75px;" src="../includes/themes/<?php echo $theme_name; ?>/images/<?php echo $ds[ 'logo_pic' ]; ?>" alt=""></span>
        <div class="box">
            <span class="webspell"><?php echo $ds[ 'logotext1' ];?></span>
            <span class="slogan"><?php echo $ds[ 'logotext2' ];?></span>
        </div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor01">
				<ul class="navbar-nav <?php echo $ds[ 'nav_text_alignment' ];?>">
					<!--<li class="nav-item px-4 py-2 text-center active">
						<a class="nav-link mx-2" aria-current=page href=#>
							<div class=style1>FIXTURES</div>
							<div class=style2>SEASON 1</div>
						</a>
					</li>
					<li class="nav-item dropdown px-4 py-2 text-center position-relative hasSubMenu">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Our Services</a>
                        <ul class="customSubMenu">
                            <li class=subLink><a class="dropdown-item" href="#">Web designing</a></li>
                            <li><a class="dropdown-item" href="#">Web Development</a></li>
                            <li><a class="dropdown-item" href="#">SEO Analysis</a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item" href="#">Explore More</a></li>
                        </ul>
                    </li>
					<li class="nav-item px-4 py-2 text-center position-relative hasSubMenu">
						<a class="nav-link mx-2" aria-current=page href=#>
							<div class=style1>Squad</div>
							<div class=style2>YEAR 2022</div>
						</a>
							
							<div class=customSubMenu>
									<div class=subLink>
										<a href=#><i class="fa-solid fa-futbol me-1"></i>Masko Rudio</a>
									</div>
									<div class=subLink>
										<a href=#><i class="fa-solid fa-futbol me-1"></i>Feban Alex</a>
									</div>
									<div class=subLink>
										<a href=#><i class="fa-solid fa-futbol me-1"></i>Gomez Andres</a>
									</div>
									<div class=subLink>
										<a href=#><i class="fa-solid fa-futbol me-1"></i>Xobile! Bix</a>
									</div>
									<div class=subLink>
										<a href=#><i class="fa-solid fa-futbol me-1"></i>Andrew Posta</a>
									</div>
							</div>
					</li>		
					<li class="nav-item px-4 py-2 text-center">
						<a class="nav-link mx-2" aria-current=page href=#>
							<div class=style1>MANAGER</div>
							<div class=style2>Mr. G</div>
						</a>
					</li>
					<li class="nav-item px-4 py-2 text-center">
						<a class="nav-link mx-2" aria-current=page href=#>
							<div class=style1>PLAYERS</div>
							<div class=style2>CAMPAIGN</div>
						</a>
					</li>	
					<li class="nav-item px-4 py-2 text-center">
						<a class="nav-link mx-2" aria-current=page href=#>
							<div class=style1>CONTACT</div>
							<div class=style2>OFFICE</div>
						</a>
					</li>-->
					<?php include("./includes/modules/navigation.php"); ?>
                    <?php include("./includes/modules/navigation_login.php"); ?>
                    <?php include("./includes/modules/language_flag.php"); ?>
				</ul>
			</div>
		</div>
	</nav>


