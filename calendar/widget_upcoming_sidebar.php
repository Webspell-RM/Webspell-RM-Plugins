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
    $plugin_language = $pm->plugin_language("calendar", $plugin_path);

	$plugin_data= array();
    $plugin_data['$title']=$plugin_language['events'];
    $plugin_data['$subtitle']='Events';
    
    $template = $GLOBALS["_template"]->loadTemplate("calendar","head", $plugin_data, $plugin_path);
    echo $template;
$now=time();
$cats = safe_query("SELECT * FROM " . PREFIX . "plugins_calendar WHERE date>= $now");
    if (mysqli_num_rows($cats)) {    

	$template = $GLOBALS["_template"]->loadTemplate("calendar","widget_head", array(), $plugin_path);
    echo $template;

$now=time();
$ergebnis=safe_query("SELECT * FROM ".PREFIX."plugins_calendar WHERE date>= $now ORDER BY date LIMIT 0,5");
$n=1;

while($ds=mysqli_fetch_array($ergebnis)) {
	if($ds['type'] == "c") {
		$date=date("d.m.Y", $ds['date']);
		$upsquad=getsquadname($ds['squad']);

		$upurl='index.php?site=calendar&amp;tag='.date("d", $ds['date']).'&amp;month='.date("m", $ds['date']).'&amp;year='.date("Y", $ds['date']);

		$opponent=$ds['opponent'];
		
		$data_array = array();
		$data_array['$opponent'] = $opponent;
		$data_array['$date'] = $date;
		$data_array['$upurl'] = $upurl;
		$template = $GLOBALS["_template"]->loadTemplate("calendar","upcomingactions", $data_array, $plugin_path);
        echo $template;

    } elseif($ds['type'] == "e") {
		$date=date("d.m.Y", $ds['date']);
		$upsquad=getsquadname($ds['squad']);

		$upurl='index.php?site=calendar&amp;tag='.date("d", $ds['date']).'&amp;month='.date("m", $ds['date']).'&amp;year='.date("Y", $ds['date']);

		$opponent=$ds['opponent'];
		
		$data_array = array();
		$data_array['$opponent'] = $opponent;
		$data_array['$date'] = $date;
		$data_array['$upurl'] = $upurl;
		$template = $GLOBALS["_template"]->loadTemplate("calendar","upcomingactions", $data_array, $plugin_path);
        echo $template;

    }elseif ($ds['type'] == "t") {

    	$date=date("d.m.Y", $ds['date']);   

		$upurl='index.php?site=calendar&amp;tag='.date("d", $ds['date']).'&amp;month='.date("m", $ds['date']).'&amp;year='.date("Y", $ds['date']);
		
		$data_array = array();
		$data_array['$opponent'] = $plugin_language['headtrain'];
		$data_array['$date'] = $date;
		$data_array['$upurl'] = $upurl;
		$template = $GLOBALS["_template"]->loadTemplate("calendar","upcomingactions", $data_array, $plugin_path);
        echo $template;

	} elseif ($ds['type'] == "d") {

		$date=date("d.m.Y", $ds['date']);
		$upurl='index.php?site=calendar&amp;tag='.date("d", $ds['date']).'&amp;month='.date("m", $ds['date']).'&amp;year='.date("Y", $ds['date']);
		$short=$ds['short'];
		
		$data_array = array();
		$data_array['$short'] = $short;
		$data_array['$date'] = $date;
		$data_array['$upurl'] = $upurl;
		$template = $GLOBALS["_template"]->loadTemplate("calendar","upcomingevent", $data_array, $plugin_path);
        echo $template;
	}
	else {
		$date=date("d.m.Y", $ds['date']);
		$upurl='index.php?site=calendar&amp;tag='.date("d", $ds['date']).'&amp;month='.date("m", $ds['date']).'&amp;year='.date("Y", $ds['date']);
		$short=$ds['short'];
		
		$data_array = array();
		$data_array['$short'] = $short;
		$data_array['$date'] = $date;
		$data_array['$upurl'] = $upurl;
		$template = $GLOBALS["_template"]->loadTemplate("calendar","upcomingevent", $data_array, $plugin_path);
        echo $template;
	}
  $n++;
}
	$template = $GLOBALS["_template"]->loadTemplate("calendar","widget_foot", array(), $plugin_path);
    echo $template;
$anzahl='';

} else {
        
        echo $plugin_language[ 'no_event' ];
    }
?>
