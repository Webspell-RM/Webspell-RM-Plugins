<?php

//------------------------------------------------------------------------------------------------------------+
//[ PREPARE CONFIG - DO NOT CHANGE OR MOVE THIS ]

  global $lgsl_config; $lgsl_config = array();

//------------------------------------------------------------------------------------------------------------+
//[ FEED: 0=OFF 1=CURL OR FSOCKOPEN 2=FSOCKOPEN ONLY / LEAVE THE URL ALONE UNLESS YOU KNOW WHAT YOUR DOING ]

  $lgsl_config['feed']['method'] = 0;
  $lgsl_config['feed']['url']    = "";

//------------------------------------------------------------------------------------------------------------+
//[ ADDITIONAL FILES ]

  #$lgsl_config['style'] = "default.css"; 
  $lgsl_config['scripts'] = [];

//------------------------------------------------------------------------------------------------------------+
//[ SHOW LOCATION FLAGS: 0=OFF 1=GEO-IP "GB"=MANUALLY SET COUNTRY CODE FOR SPEED ]

  $lgsl_config['locations'] = 1;

//------------------------------------------------------------------------------------------------------------+
//[ SHOW TOTAL SERVERS AND PLAYERS AT BOTTOM OF LIST: 0=OFF 1=ON ]

  $lgsl_config['list']['totals'] = 1;

//------------------------------------------------------------------------------------------------------------+
//[ SORTING OPTIONS ]

  $lgsl_config['sort']['servers'] = "id";   // OPTIONS: id  type  zone  players  status
  $lgsl_config['sort']['players'] = "name"; // OPTIONS: name  score time

//------------------------------------------------------------------------------------------------------------+
//[ ZONE SIZING: HEIGHT OF PLAYER BOX DYNAMICALLY CHANGES WITH THE NUMBER OF PLAYERS ]

  $lgsl_config['zone']['width']     = "360"; // images will be cropped unless also resized to match
  $lgsl_config['zone']['line_size'] = "19";  // player box height is this number multiplied by player names
  $lgsl_config['zone']['height']    = "300"; // player box height limit

//------------------------------------------------------------------------------------------------------------+
//[ ZONE GRID: NUMBER=WIDTH OF GRID - INCREASE FOR HORIZONTAL ZONE STACKING ]

  $lgsl_config['grid'][1] = 1;
  $lgsl_config['grid'][2] = 1;
  $lgsl_config['grid'][3] = 1;
  $lgsl_config['grid'][4] = 1;
  $lgsl_config['grid'][5] = 1;
  $lgsl_config['grid'][6] = 1;
  $lgsl_config['grid'][7] = 1;
  $lgsl_config['grid'][8] = 1;

//------------------------------------------------------------------------------------------------------------+
//[ ZONE SHOWS PLAYER NAMES: 0=HIDE 1=SHOW ]

  $lgsl_config['players'][1] = 1;
  $lgsl_config['players'][2] = 1;
  $lgsl_config['players'][3] = 1;
  $lgsl_config['players'][4] = 1;
  $lgsl_config['players'][5] = 1;
  $lgsl_config['players'][6] = 1;
  $lgsl_config['players'][7] = 1;
  $lgsl_config['players'][8] = 1;

//------------------------------------------------------------------------------------------------------------+
//[ ZONE RANDOMISATION: NUMBER=MAX RANDOM SERVERS TO BE SHOWN ]

  $lgsl_config['random'][0] = 0;
  $lgsl_config['random'][1] = 0;
  $lgsl_config['random'][2] = 0;
  $lgsl_config['random'][3] = 0;
  $lgsl_config['random'][4] = 0;
  $lgsl_config['random'][5] = 0;
  $lgsl_config['random'][6] = 0;
  $lgsl_config['random'][7] = 0;
  $lgsl_config['random'][8] = 0;

//------------------------------------------------------------------------------------------------------------+
// [ HIDE OFFLINE SERVERS: 0=HIDE 1=SHOW

  $lgsl_config['hide_offline'][0] = 0;
  $lgsl_config['hide_offline'][1] = 0;
  $lgsl_config['hide_offline'][2] = 0;
  $lgsl_config['hide_offline'][3] = 0;
  $lgsl_config['hide_offline'][4] = 0;
  $lgsl_config['hide_offline'][5] = 0;
  $lgsl_config['hide_offline'][6] = 0;
  $lgsl_config['hide_offline'][7] = 0;
  $lgsl_config['hide_offline'][8] = 0;

//------------------------------------------------------------------------------------------------------------+
//[ e107 VERSION: TITLES - OTHER VERSIONS ARE SET BY THE CMS ]

  $lgsl_config['title'][0] = "Live Game Server List";
  $lgsl_config['title'][1] = "Game Server";
  $lgsl_config['title'][2] = "Game Server";
  $lgsl_config['title'][3] = "Game Server";
  $lgsl_config['title'][4] = "Game Server";
  $lgsl_config['title'][5] = "Game Server";
  $lgsl_config['title'][6] = "Game Server";
  $lgsl_config['title'][7] = "Game Server";
  $lgsl_config['title'][8] = "Game Server";


//------------------------------------------------------------------------------------------------------------+
//[ HOSTING FIXES ]

  $lgsl_config['direct_index'] = 0;  // 1=link to index.php instead of the folder
  $lgsl_config['no_realpath']  = 0;  // 1=do not use the realpath function
  $lgsl_config['url_path']     = "/../includes/plugins/game_server/func/"; // full url to /lgsl_files/ for when auto detection fails

//------------------------------------------------------------------------------------------------------------+
//[ ADVANCED SETTINGS ]

  $lgsl_config['management']    = 1;         // 1=show advanced management in the admin by default
  $lgsl_config['host_to_ip']    = 1;         // 1=show the servers ip instead of its hostname
  $lgsl_config['public_add']    = 1;         // 1=servers require approval OR 2=servers shown instantly
  $lgsl_config['public_feed']   = 1;         // 1=feed requests can add new servers to your list
  $lgsl_config['cache_time']    = 0;        // seconds=time before a server needs updating
  $lgsl_config['autoreload']    = false;     // true=reloads page when cache_time is passed
  $lgsl_config['history']       = false;     // true=enable server tracking (history of past 24 hours)
  $lgsl_config['live_time']     = 60;         // seconds=time allowed for updating servers per page load
  $lgsl_config['timeout']       = 0;         // 1=gives more time for servers to respond but adds loading delay
  $lgsl_config['retry_offline'] = 0;         // 1=repeats query when there is no response but adds loading delay
  $lgsl_config['cms']           = "sa";      // sets which CMS specific code to use
  $lgsl_config['image_mod']     = false;     // true=show userbar in server's details
  $lgsl_config['pagination_mod']= false;      // true=using pagination
  $lgsl_config['pagination_lim']= 200;        // limit per page
  $lgsl_config['preloader']     = true;      // true=using ajax to faster loading page
  $lgsl_config['disabled_types']= false;     // allow to exclude some protocols (games) from list. usage: $lgsl_config['disabled_types']= array('warsowold', 'halflifewon', 'test');


//------------------------------------------------------------------------------------------------------------+
//[ TRANSLATION ]

  include("languages/german.php");                 // sets LGSL language

//------------------------------------------------------------------------------------------------------------+
