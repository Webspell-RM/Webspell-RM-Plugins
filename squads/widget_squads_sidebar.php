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
# 
# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("squads", $plugin_path);

    $data_array['$title']=$plugin_language[ 'title' ];
    $data_array['$subtitle']='Squads';

    $template = $GLOBALS["_template"]->loadTemplate("switchsquads","sc_title", $data_array, $plugin_path);
    echo $template;

$filepath = $plugin_path."images/squadicons/";

$ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_squads WHERE gamesquad = '1' ORDER BY sort");
if(mysqli_num_rows($ergebnis)) {
  #echo '<table width="100%" cellspacing="0" cellpadding="2">';
  $template = $GLOBALS["_template"]->loadTemplate("switchsquads","sc_content_head", $data_array, $plugin_path);
  echo $template; 
  $n=1;
  while($db=mysqli_fetch_array($ergebnis)) {

    echo'<ul class="list-group list-group-flush">';
    
    $n++;
    if(!empty($db['icon_small'])) $squadicon='<img src="../' . $filepath . $db['icon_small'].'" style="width: 100%;height: auto; margin:2px 0;" border="0" alt="'.getinput($db['name']).'" title="'.getinput($db['name']).'" />';
    else $squadicon='';
    $squadname=getinput($db['name']);
    $squadID=$db['squadID'];

    $data_array = array();
    $data_array['$squadID'] = $squadID;
    $data_array['$squadname'] = $squadname;
    $data_array['$squadicon'] = $squadicon;

    $template = $GLOBALS["_template"]->loadTemplate("switchsquads","sc_content", $data_array, $plugin_path);
    echo $template; 
    echo '</ul>'; 

    
  }
  #echo '</table>';
  $template = $GLOBALS["_template"]->loadTemplate("switchsquads","sc_content_foot", $data_array, $plugin_path);
  echo $template; 
  

  
} else {
        
        echo $plugin_language[ 'no_squads' ];
    }
