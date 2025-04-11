<link href="/includes/plugins/game_server/func/styles/default.css" rel="stylesheet"><?php
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
$plugin_language = $pm->plugin_language("game_server", $plugin_path);


if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}



if($action=="show"){

  $plugin_data= array();
  $plugin_data['$title']=$plugin_language['title'];
  $plugin_data['$subtitle']='Servers on COD:UO v1.51';

  $template = $GLOBALS["_template"]->loadTemplate("game_server","head", $plugin_data, $plugin_path);
  echo $template;


require ("./includes/plugins/game_server/func/lgsl_class.php");
  global $output, $server, $title, $misc, $lgsl_config;




//------------------------------------------------------------------------------------------------------------//
// THIS CONTROLS HOW THE PLAYER FIELDS ARE DISPLAYED

  $fields_show  = array("name", "score", "kills", "deaths", "team", "ping", "bot", "time"); // ORDERED FIRST
  $fields_hide  = array("teamindex", "pid", "pbguid"); // REMOVED
  $fields_other = TRUE; // FALSE TO ONLY SHOW FIELDS IN $fields_show

//------------------------------------------------------------------------------------------------------------//
// GET THE SERVER DETAILS AND PREPARE IT FOR DISPLAY

  global $lgsl_server_id;
  if ($lgsl_config['preloader']) {
    $lgsl_server_id = isset($_GET["s"]) ? (int) $_GET["s"] : null;
  }
  $lgsl_server_ip = isset($_GET["ip"]) ? $_GET["ip"] : "";
  $lgsl_server_port = isset($_GET["port"]) ? (int) $_GET["port"] : "";

  $server = lgsl_query_cached("", $lgsl_server_ip, $lgsl_server_port, "", "", "sep", $lgsl_server_id);

  echo'<div class="card">
    <div class="card-body">';

  if ($server) {

    $title .= " | {$server['s']['name']}";
    $fields = lgsl_sort_fields($server, $fields_show, $fields_hide, $fields_other);
    $server = lgsl_sort_players($server);
    $server = lgsl_sort_extras($server);
    $misc   = lgsl_server_misc($server);
    $server = lgsl_server_html($server);

  //------------------------------------------------------------------------------------------------------------//

    $output .= "
    <div style='margin-bottom:15px;'>
      <!--<div class='spacer'></div>-->";

  //------------------------------------------------------------------------------------------------------------//
  // SHOW THE STANDARD INFO

    $c_port = ($server['b']['c_port'] > 1 ? $server['b']['c_port'] : '--');
    $q_port = ($server['b']['q_port'] > 1 ? $server['b']['q_port'] : '--');

  if(file_exists('./includes/plugins/squads/images/games/'.$server['b']['type'].'.jpg')){
    $gameicon='../includes/plugins/squads/images/games/'.$server['b']['type'].'.jpg';
  } elseif(file_exists('./includes/plugins/squads/images/games/'.$server['b']['type'].'.jpeg')){
    $gameicon='../includes/plugins/squads/images/games/'.$server['b']['type'].'.jpeg';
  } elseif(file_exists('./includes/plugins/squads/images/games/'.$server['b']['type'].'.png')){
    $gameicon='../includes/plugins/squads/images/games/'.$server['b']['type'].'.png';
  } elseif(file_exists('./includes/plugins/squads/images/games/'.$server['b']['type'].'.gif')){
    $gameicon='../includes/plugins/squads/images/games/'.$server['b']['type'].'.gif';
  } else{
   $gameicon='../includes/plugins/squads/images/games/no-image.jpg';
  }
$output .= "

<style type='text/css'>
.gametracker {
    display: block;
    margin-left: auto;
    margin-right: auto }
.table {border-color: #333}    
</style>


      <div class='row'>
            <div class='col-md-8 text-start'>
                <h4>{$server['s']['name']}</h4>
            </div>
            <div class='col-md-4 text-end'>
                <small>{$lgsl_config['text']['lst']}: " . Date($lgsl_config['text']['tzn'], $server['s']['cache_time']) . "</small>
            </div>
      </div>



      <div class='row' style='margin-bottom:15px;margin-top:15px;'>
            <div class='col-md-3'>
                <img class='img-fluid' style='height:200px' src='$gameicon' alt='game'>
            </div>
            <div class='col-md-9'>


              <div class='details_info'>
                <div class='details_info_column text-center'>
                  <a id='gamelink' href='{$misc['software_link']}'>{$lgsl_config['text']['slk']}</a>
                  <div class='details_info_row'>
                    <div class='details_info_scolumn'>
                      <div class='details_info_srow'>
                        <div class='details_info_ceil'>{$lgsl_config['text']['sts']}:</div><div class='details_info_ceil'>{$lgsl_config['text'][$misc['text_status']]}</div></div>
                      <div class='details_info_srow'>
                        <div class='details_info_ceil'>{$lgsl_config['text']['adr']}:</div><div class='details_info_ceil'>{$server['b']['ip']}</div></div>
                      <div class='details_info_srow'>
                        <div class='details_info_ceil'>{$lgsl_config['text']['cpt']}:</div><div class='details_info_ceil'>{$c_port}</div></div>
                      <div class='details_info_srow'>
                        <div class='details_info_ceil'>{$lgsl_config['text']['qpt']}:</div><div class='details_info_ceil'>{$q_port}</div></div></div>
                    <div class='details_info_scolumn'>
                      <div class='details_info_srow'>
                        <div class='details_info_ceil'>{$lgsl_config['text']['typ']}:</div><div class='details_info_ceil'>{$server['b']['type']}</div></div>
                      <div class='details_info_srow'>
                        <div class='details_info_ceil'>{$lgsl_config['text']['gme']}:</div><div class='details_info_ceil'>{$server['s']['game']}</div></div>
                      <div class='details_info_srow'>
                        <div class='details_info_ceil'>{$lgsl_config['text']['map']}:</div><div class='details_info_ceil'>{$server['s']['map']}</div></div>
                      <div class='details_info_srow'>
                        <div class='details_info_ceil'>{$lgsl_config['text']['plr']}:</div><div class='details_info_ceil'>{$server['s']['players']} / {$server['s']['playersmax']}</div></div>
                    </div>
                  </div>
                  
                </div>
                <div class='details_info_column zone{$server['o']['zone']}' style='background-image: url({$misc['image_map']});'>
                  <span class='details_location_image' style='background-image: url({$misc['icon_location']});' title='{$misc['text_location']}'></span>
                  <span class='details_password_image zone{$server['o']['zone']}' style='background-image: url({$misc['image_map_password']});' title='{$lgsl_config['text']['map']}: {$server['s']['map']}'></span>
                  <span class='details_game_image' style='background-image: url({$misc['icon_game']});' title='{$misc['text_type_game']}'></span>
                </div>
              </div>
            </div>
      </div>";


  //------------------------------------------------------------------------------------------------------------//
  // SHOW THE PLAYERS

    $output .= "<div class='row'>
    <div class='ca1rd col-6' id='deta1ils_playerlist'>";

    if (empty($server['p']) || !is_array($server['p'])) {
      $output .= "<div class='noinfo'>{$lgsl_config['text']['npi']}</div>";
    } else {
      $output .= "
      <table  class='table table-striped'>
      <thead>
        <tr>";

        foreach ($fields as $field) {
          $field = ucfirst($lgsl_config['text'][substr(strtolower($field), 0, 3)]);
          $output .= "<td> {$field} </td>";
        }

        $output .= "
        </tr></thead>
  <tbody>";

        foreach ($server['p'] as $player_key => $player) {
          $output .= "
          <tr>";

          foreach ($fields as $field) {
            $output .= "<td> {$player[$field]} </td>";
          }

          $output .= "
          </tr>";
        }

      $output .= "
      </tbody></table>";
    }

    $output .= "
    </div>";

  //------------------------------------------------------------------------------------------------------------//

    #$output .= "<div class='spacer'></div>";

  //------------------------------------------------------------------------------------------------------------//

    $g = "ip={$server['b']['ip']}&port={$server['b']['c_port']}";
    if ($lgsl_config['history']) {
      $output .= "<div style='overflow-x: auto;'><img src='charts.php?{$g}' alt='{$server["s"]["name"]}' style='border-radius: 6px;' id='chart' /></div>";
    }

    if ($lgsl_config['image_mod']) {
      if (extension_loaded('gd')) {
        $p = str_replace('lgsl_files/', '', lgsl_url_path()) . ($lgsl_config["direct_index"] ? 'index.php' : '');
        $output .= "
        <details>
          <summary style='margin-bottom: 12px;'>
            {$lgsl_config['text']['cts']}
          </summary>
          <div>
            <div style='overflow-x: auto;'><img src='userbar.php?{$g}' alt='{$server["s"]["name"]}'/></div>
            <textarea onClick='this.select();'>[url={$p}?{$g}][img]{$p}userbar.php?{$g}[/img][/url]</textarea><br /><br />

            <div style='overflow-x: auto;'><img src='userbar.php?{$g}&t=2' alt='{$server["s"]["name"]}'/></div>
            <textarea onClick='this.select();'>[url={$p}?{$g}][img]{$p}userbar.php?{$g}&t=2[/img][/url]</textarea><br /><br />

            <img src='userbar.php?{$g}&t=3' alt='{$server["s"]["name"]}'/><br />
            <textarea onClick='this.select();'>[url={$p}?{$g}][img]{$p}userbar.php?{$g}&t=3[/img][/url]</textarea>
          </div>
        </details>
        <div class='spacer'></div>
        <style>
          textarea {
            width: 32em;
            height: 2.3em;
            word-break: break-all;
          }
          @media (max-width: 414px){
            textarea { width: 98.5% !important; }
          }
          details[open] div {
            animation: spoiler 1s;
          }
          @keyframes spoiler {
            0%   {opacity: 0;}
            100% {opacity: 1;}
          }
        </style>";
      }
      else {$output .= "<div id='invalid_server_id'> Error while trying to display userbar: GD library not loaded (see php.ini) </div>";}
    }

  

  //------------------------------------------------------------------------------------------------------------//

    #$output .= "<div class='spacer'></div>";

  //------------------------------------------------------------------------------------------------------------//
  // SHOW THE SETTINGS

    if (empty($server['e']) || !is_array($server['e'])) {
      $output .= "<div class='noinfo'>{$lgsl_config['text']['nei']} </div>";
    } else {
      $hide_options = count($server['e']) > 10;
      if ($hide_options) {
         $output .= "<div class='ca1rd col-6'>
         
        <details>
          <summary style='margin-bottom: 0px;'>
          {$lgsl_config['text']['ctb']}<hr>
          </summary>
         ";
      }
      $output .= "
      <table class='table table-striped' style='margin-top: -16px;'>
      <thead>
        <tr>
          <th>{$lgsl_config['text']['ehs']} </th>
          <th> {$lgsl_config['text']['ehv']} </th>
        </tr>
        </thead>
        <tbody>";

      foreach ($server['e'] as $field => $value) {
        /*$value = preg_replace('/((https*:\/\/|https*:\/\/www\.|www\.)[\w\d\.\-\/=$?​]*)/i', "<a href='$1' target='_blank'>$1</a>", $value);*/
        #$value = preg_replace('/((https*:\/\/|https*:\/\/www\.|www\.)[\w\d\.\-\/=$?​]*)/i', "<a href='$1' target='_blank'>$1</a>", $value);
        $value = preg_replace('/((https*:\/\/|https*:\/\/www\.|www\.)[\w\d\.\-\/=$?​]*)/i', "$1", $value);
        $output .= "
        <tr>
          <td> {$field} </td>
          <td> {$value} </td>
        </tr>";
      }

      $output .= "
      </tbody>
</table>";
      if ($hide_options) {
        $output .= "
       
        </details> </div>";
      }
    }

  //------------------------------------------------------------------------------------------------------------//

    #$output .= "<div class='spacer'></div>";

    $output .= "
    </div></div>";
  }
  else {
    $output .= "<div id='invalid_server_id'> {$lgsl_config['text']['mid']} </div>";
  }


if ($lgsl_config['preloader']) {
  echo $output;
}


} else {


  $s = isset($_GET['s']) ? $_GET['s'] : null;
  $ip = isset($_GET['ip']) ? $_GET['ip'] : null;
  $port = isset($_GET['port']) ? $_GET['port'] : null;
  


  $plugin_data= array();
  $plugin_data['$title']=$plugin_language['title'];
  $plugin_data['$subtitle']='Servers on COD:UO v1.51';

  $template = $GLOBALS["_template"]->loadTemplate("game_server","head", $plugin_data, $plugin_path);
  echo $template;



echo'<div class="card">
    <div class="card-body">';


 require ("./includes/plugins/game_server/func/lgsl_class.php");
global $output;

  $type = (isset($_GET['type']) ? $_GET['type'] : '');
  $game = (isset($_GET['game']) ? $_GET['game'] : '');
  $page = ($lgsl_config['pagination_mod'] && isset($_GET['page']) ? (int)$_GET['page'] : 1);

  $uri = $_SERVER['REQUEST_URI'];
  
  if ($lgsl_config['preloader']) {
    $uri = $_SERVER['HTTP_REFERER'];
  }

  $server_list = lgsl_query_group(array("type" => $type, "game" => $game, "page" => $page));
  $server_list = lgsl_sort_servers($server_list);

//------------------------------------------------------------------------------------------------------------+
  if (count($server_list) == 0 && $page < 2) {
    $output .= "<div id='back_to_servers_list'><a href='./admin.php'>ADD YOUR FIRST SERVER</a></div>";
  }

  $output .= "
  <table id='plugini' class='table table-striped'>
  <thead>
    <tr>
    <th class='status_cell'>{$lgsl_config['text']['sts']}:</th>
      <th class='status_cell'>{$lgsl_config['text']['sts']}:</th>
      <th class='servername_cell'>{$lgsl_config['text']['tns']}:</th>
      <th class='connectlink_cell'>{$lgsl_config['text']['adr']}:</th>
      <th class='map_cell'>{$lgsl_config['text']['map']}:</th>
      <th class='players_cell'>{$lgsl_config['text']['plr']}:</th>
      <th class='details_cell'>{$lgsl_config['text']['dtl']}:</th>
    </tr>
    </thead>
  <tbody>";

  foreach ($server_list as $server)
  {
    $misc    = lgsl_server_misc($server);
    $server  = lgsl_server_html($server);
    $percent = strval($server['s']['players'] == 0 || $server['s']['playersmax'] == 0 ? 0 : floor($server['s']['players']/$server['s']['playersmax']*100));
    $lastupd = Date($lgsl_config['text']['tzn'], (int)$server['s']['cache_time']);
    $gamelink= lgsl_build_link_params($uri, array("game" => $server['s']['game']));

    $output .= "
    <tr class='server_{$misc['text_status']}'>

      <td class='status_cell'>
      <img alt='{$misc['name_filtered']}' src='{$misc['icon_game']}' title='{$misc['text_type_game']}' class='game_icon' />
      </td>
      <td class='status_cell'>
        <span title='{$lgsl_config['text'][$misc['text_status']]} | {$lgsl_config['text']['lst']}: {$lastupd}' class='status_icon_{$misc['text_status']}'></span>
        <!--<a href='{$gamelink}'>
          <img alt='{$misc['name_filtered']}' src='{$misc['icon_game']}' title='{$misc['text_type_game']}' class='game_icon' />
        </a>-->
        <img alt='{$misc['text_location']}' src='{$misc['icon_location']}' title='{$misc['text_location']}' class='contry_icon' />
      </td>

      <td >
        <div class='servername_nolink'>
          {$misc['name_filtered']}
        </div>
        <div class='servername_link'>
          <!--<a href='".lgsl_link($server['b']['ip'], $server['b']['c_port'])."'>
            {$misc['name_filtered']}
          </a>-->
        </div>
      </td>

      <td title='{$lgsl_config['text']['slk']}' class='connectlink_cell'>
        <!--<a href='{$misc['software_link']}'>-->
          {$misc['connect_filtered']}
        <!--</a>-->
      </td>

      

      <td class='map_cell' data-path='{$misc['image_map']}'>
        {$server['s']['map']}
      </td>

      <td class='players_cell'>
      <span class='players_numeric'>{$server['s']['players']}/{$server['s']['playersmax']}</span>
        <!--<div class='outer_bar'>
          <div class='inner_bar' style='width:{$percent}%;'>
            <span class='players_numeric'></span>
            <span class='players_percent{$percent}'>{$percent}%</span>
          </div>
        </div>-->

        <div class='progress' style='height: 4px;'>
  <div class='progress-bar inner_bar' role='progressbar' aria-label='Example with label' style='width: {$percent}%;' aria-valuenow='{$percent}' aria-valuemin='0' aria-valuemax='100'></div>
</div>
      </td>

      <td class='details_cell'>";

      if ($lgsl_config['locations']) {
        $output .= "
        <a href='".lgsl_location_link($server['o']['location'])."' target='_blank' class='contry_link'>
          <!--<img alt='{$misc['text_location']}' src='{$misc['icon_location']}' title='{$misc['text_location']}' class='contry_icon' />-->
        </a>";
      }

      $output .= "
        <!--<a href='".lgsl_link($server['b']['ip'], $server['b']['c_port'])."' class='details_icon' title='{$lgsl_config['text']['vsd']}'></a>-->
        <a href='index.php?site=game_server&action=show&".lgsl_link($server['b']['ip'], $server['b']['c_port'])."' class='details_icon' title='{$lgsl_config['text']['vsd']}'></a>
      </td>

    </tr>";
  }

  $output .= "
  </tbody></table>";

  if ($lgsl_config['pagination_mod'] && ((int)(count($server_list) / $lgsl_config['pagination_lim']) > 0 || $page > 1)) {
    $output .= "
      <div id='pages'>
        " . ($page > 1 ? "<a href='" . lgsl_build_link_params("index.php?site=game_server", array("page" => $page - 1)) . "'> < </a>" : "") . "
        <span>{$lgsl_config['text']['pag']} {$page}</span>
        " . (count($server_list) < $lgsl_config['pagination_lim'] ?
            "" :
            (isset($_GET['page']) ?
                "<a href='" . lgsl_build_link_params("index.php?site=game_server", array("page" => $page + 1)) . "'> > </a>" :
                "<a href='" . lgsl_build_link_params("index.php?site=game_server", array("page" => 2)) ."'> > </a>")) . "
      </div>
      ";
  }

//------------------------------------------------------------------------------------------------------------+

  if ($lgsl_config['list']['totals']) {
    $total = lgsl_group_totals($server_list);

    $output .= "
    <div id='totals'>
        <div> {$lgsl_config['text']['tns']}: {$total['servers']}    </div>
        <div> {$lgsl_config['text']['tnp']}: {$total['players']}    </div>
        <div> {$lgsl_config['text']['tmp']}: {$total['playersmax']} </div>
    </div>";
  }


  echo $output;

}

echo'</div></div>';

if (isset($lgsl_config['scripts'])) {
        foreach ($lgsl_config['scripts'] as $script) {
          echo "<script src='includes/plugins/game_server/func/scripts/{$script}'></script>";
        }
      }
