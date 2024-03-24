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

$ergebnis = safe_query("SELECT * FROM `".PREFIX."settings_social_media`");
if(mysqli_num_rows($ergebnis)){
    while ($ds = mysqli_fetch_array($ergebnis)) {

        

echo' <ul class="social">
                <li>
        <a>    
            <i style="width:30px;height:30px;" class="bi bi-facebook"></i>
          <div><iframe src="https://www.facebook.com/v2.9/plugins/page.php?app_id=&amp;channel=https%3A%2F%2Fstaticxx.facebook.com%2Fx%2Fconnect%2Fxd_arbiter%2F%3Fversion%3D46%23cb%3Df33b176a314ff3e%26domain%3Dtest.webspell-rm.de%26origin%3Dhttp%253A%252F%252Ftest.webspell-rm.de%252Ff21675ab3fc542%26relation%3Dparent.parent&amp;container_width=300&amp;hide_cover=false&amp;href='.$ds['facebook'].'&amp;locale=de_DE&amp;sdk=joey&amp;tabs=timeline%2Cevents%2Cmessages&amp;width=380" width="300" height="500" style="border:none;overflow:hidden;margin-bottom: -13px;" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
          </div>      
        </a> 
        </li>
        <li>
        <a>    
            <i style="width:30px;height:30px;" class="bi bi-discord"></i>
          <div><iframe style="margin-bottom: -13px;" src="https://discordapp.com/widget?id='.$ds['discord'].'&theme=dark" width="300" height="500" allowtransparency="true" frameborder="0"></iframe></div>       
        </a> 
        </li>
        <li>
        <a>    
            <i style="width:30px;height:30px;" class="bi bi-server"></i>
          <div><iframe style="margin-bottom: -13px;" src="https://cache.gametracker.com/components/html0/?host='.$ds['gametracker'].'&bgColor=333333&fontColor=CCCCCC&titleBgColor=222222&titleColor=FF9900&borderColor=555555&linkColor=FFCC00&borderLinkColor=222222&showMap=0&currentPlayersHeight=100&showCurrPlayers=1&topPlayersHeight=100&showTopPlayers=1&showBlogs=0&width=300" frameborder="0" scrolling="no" width="300" height="412"></iframe></div>     
        </a> 
        </li>
            </ul>';

}
}
