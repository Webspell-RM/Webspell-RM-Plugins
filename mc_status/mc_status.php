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

require 'MinecraftPing.php';
require 'MinecraftPingException.php';

# Sprachdateien aus dem Plugin-Ordner laden
	$pm = new plugin_manager(); 
	$plugin_language = $pm->plugin_language("mc_status", $plugin_path);

		$data_array = array(); 
		$data_array['$title']=$plugin_language['mc_status'];
    	$data_array['$subtitle']='Minecraft';
		$template = $GLOBALS["_template"]->loadTemplate("mc_status","title", $data_array, $plugin_path);
    	echo $template;
	
	 $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_mc_status WHERE displayed = '1'");
	if (mysqli_num_rows($ergebnis)) {

		while ($ds = mysqli_fetch_array($ergebnis)) {



	$Query = "";
	try
	{
		$Query = new MinecraftPing( $ds['name'], $ds['port'] );

		// var_dump ( $Query->Query() );
		$arr = $Query->Query();


        $status = "online";
        $version = $arr['version']['name'];
        $ponline = $arr['players']['online'];
        $pmax = $arr['players']['max'];

	}
	catch( MinecraftPingException $e )
	{
		//echo $e->getMessage();   <-- Fängt die Errormeldung des Query ab
        
        # Wird angezeigt wenn der Server offline ist anstelle der Errormeldung
        $status = "offline";
        $version = "-";
        $ponline = "-";
        $pmax = "-"; # Hier muss die Max-Spielerzahl eingetragen werden. Manuell, über eine Adminseite etc.
	}
	finally
	{
		if( $Query )
		{
			$Query->Close();
		}
	}

$name = $ds['name'];
		
			$data_array = array();
			$data_array['$xname'] = $name;
			$data_array['$ponline'] = $ponline;
			$data_array['$pmax'] = $pmax;
			$data_array['$version'] = $version;
			$data_array['$status'] = $status;

			$data_array['$lang_adresse']=$plugin_language['adresse'];
			$data_array['$lang_version']=$plugin_language['version'];
			$data_array['$lang_player']=$plugin_language['player'];

			$online = $GLOBALS["_template"]->loadTemplate("mc_status","content", $data_array, $plugin_path);
			echo $online;
		}

		} else {
			echo $plugin_language['no_stream'];
		}

		$online = $GLOBALS["_template"]->loadTemplate("mc_status","foot", $data_array, $plugin_path);
			echo $online;