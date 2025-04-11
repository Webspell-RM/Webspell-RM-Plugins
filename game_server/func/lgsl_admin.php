<?php

 /*----------------------------------------------------------------------------------------------------------\
 |                                                                                                            |
 |                      [ LIVE GAME SERVER LIST ] [ � RICHARD PERRY FROM GREYCUBE.COM ]                       |
 |                                                                                                            |
 |    Released under the terms and conditions of the GNU General Public License Version 3 (http://gnu.org)    |
 |                                                                                                            |
 \-----------------------------------------------------------------------------------------------------------*/

//------------------------------------------------------------------------------------------------------------+

  #if (!defined("LGSL_ADMIN")) { exit("DIRECT ACCESS ADMIN FILE NOT ALLOWED"); }

  require "lgsl_class.php";

  #global $lgsl_database;

  #lgsl_database();
  $lgsl_type_list     = lgsl_type_list(); asort($lgsl_type_list);
  $lgsl_protocol_list = lgsl_protocol_list();

  $id        = 0;
  $last_type = "source";
  $zone_list = array(0,1,2,3,4,5,6,7,8);

//------------------------------------------------------------------------------------------------------------+

  if (!function_exists("fsockopen") && !$lgsl_config['feed']['method'])
  {
    if ((function_exists("curl_init") && function_exists("curl_setopt") && function_exists("curl_exec")))
    {
      $output = "<div class='center'><br /><br /><b>FSOCKOPEN IS DISABLED - YOU MUST ENABLE THE FEED OPTION</b><br /><br /></div>".lgsl_help_info(); return;
    }
    else
    {
      $output = "<div class='center'><br /><br /><b>FSOCKOPEN AND CURL ARE DISABLED - LGSL WILL NOT WORK ON THIS HOST</b><br /><br /></div>".lgsl_help_info(); return;
    }
  }


//------------------------------------------------------------------------------------------------------------+

  if (!empty($_POST['lgsl_save_1']) || !empty($_POST['lgsl_save_2']))
  {
    if (!empty($_POST['lgsl_save_1']))
    {
      // LOAD SERVER CACHE INTO MEMORY
      $db = array();
      $ergebnis =
        safe_query("SELECT * FROM " . PREFIX . "plugins_game_server");
      while ($mysqli_row = mysqli_fetch_array($ergebnis))    
      {
        $db["{$mysqli_row['type']}:{$mysqli_row['ip']}:{$mysqli_row['q_port']}"] = array($mysqli_row['status'], $mysqli_row['cache'], $mysqli_row['cache_time']);
      }
    }

    // EMPTY SQL TABLE
    $mysqli_result = safe_query("TRUNCATE  " . PREFIX . "plugins_game_server") or die(mysqli_error($lgsl_database));

    // CONVERT ADVANCED TO NORMAL DATA FORMAT
    if (!empty($_POST['lgsl_management']))
    {
      $form_lines = explode("\r\n", trim($_POST['form_list']));

      foreach ($form_lines as $form_key => $form_line)
      {
        list($_POST['form_type']    [$form_key],
             $_POST['form_ip']      [$form_key],
             $_POST['form_c_port']  [$form_key],
             $_POST['form_q_port']  [$form_key],
             $_POST['form_s_port']  [$form_key],
             $_POST['form_zone']    [$form_key],
             $_POST['form_disabled'][$form_key],
             $_POST['form_comment'] [$form_key]) = explode(":", "{$form_line}:::::::");
      }
    }

    foreach ($_POST['form_type'] as $form_key => $not_used)
    {
      // COMMENTS LEFT IN THEIR NATIVE ENCODING WITH JUST HTML SPECIAL CHARACTERS CONVERTED
      $_POST['form_comment'][$form_key] = lgsl_htmlspecialchars($_POST['form_comment'][$form_key]);

      $type       = trim($_POST['form_type']    [$form_key]);
      $ip         = trim($_POST['form_ip']      [$form_key]);
      @$c_port     = intval(trim($_POST['form_c_port']  [$form_key]));
      @$q_port     = intval(trim($_POST['form_q_port']  [$form_key]));
      @$s_port     = intval(trim($_POST['form_s_port']  [$form_key]));
      $zone       = trim($_POST['form_zone']    [$form_key]);
      $disabled   = isset($_POST['form_disabled'][$form_key]) ? intval(trim($_POST['form_disabled'][$form_key])) : "0";
      $comment    = trim($_POST['form_comment'] [$form_key]);

      // CACHE INDEXED BY TYPE:IP:Q_PORT SO IF THEY CHANGE THE CACHE IS IGNORED
      list($status, $cache, $cache_time) = isset($db["{$type}:{$ip}:{$q_port}"]) ? $db["{$type}:{$ip}:{$q_port}"] : array("0", "", "");

      $status     = $status;
      $cache      = $cache;
      $cache_time = $cache_time;

      // THIS PREVENTS PORTS OR WHITESPACE BEING PUT IN THE IP
      $ip = trim($ip);
      if (strpos($ip, ':') !== false) {
        $c_port = explode(":", $ip)[1];
        $ip = explode(":", $ip)[0];
      }

      list($c_port, $q_port, $s_port) = lgsl_port_conversion($type, $c_port, $q_port, $s_port);

      // DISCARD SERVERS WITH AN EMPTY IP AND AUTO DISABLE SERVERS WITH SOMETHING WRONG
      if     (!$ip)                               { continue; }
      elseif ($c_port < 1 || $c_port > 99999)     { $disabled = 1; $c_port = 0; }
      elseif ($q_port < 1 || $q_port > 99999)     { $disabled = 1; $q_port = 0; }
      elseif (!isset($lgsl_protocol_list[$type])) { $disabled = 1; }

      

     safe_query(
            "INSERT INTO
                `" . PREFIX . "plugins_game_server` (`type`,`ip`,`c_port`,`q_port`,`s_port`,`zone`,`disabled`,`comment`,`status`,`cache`,`cache_time`) VALUES ('{$type}','{$ip}','{$c_port}','{$q_port}','{$s_port}','{$zone}','{$disabled}','{$comment}','{$status}','{$cache}','{$cache_time}')"
        );
      
    }
  }

//------------------------------------------------------------------------------------------------------------+



//------------------------------------------------------------------------------------------------------------+

  if (!empty($_POST['lgsl_map_image_paths'])) {
    if(!empty($_POST['lgsl_map_image_upload'])) {
      $ext = strtolower(pathinfo($_FILES['map']['name'], PATHINFO_EXTENSION));
      if ($ext === "jpg" || $ext === "gif") {
        $uploadfolder = preg_replace("/[^a-z0-9_\/]/", "_", strtolower("includes/plugins/game_server/func/maps/{$_POST['lgsl_map_upload_path']}/"));
        $uploadfile = preg_replace("/[^a-z0-9_]/", "_", strtolower($_POST['lgsl_map_upload_file'])) . ".{$ext}";
        if (!file_exists($uploadfolder . $uploadfile)) {
          mkdir($uploadfolder, 0755, true);
        }
        if (move_uploaded_file($_FILES['map']['tmp_name'], $uploadfolder . $uploadfile)) {
          echo "Image {$uploadfile} uploaded successfully.\n";
        } else {
          echo "File not uploaded. Something wrong.\n";
        }
      } else {
        echo "Allowed only .jpg and .gif extensions.\n";
      }
    }
    $server_list = lgsl_query_group( array( "request" => "s" ) );

    $output .= "";
    

    foreach ($server_list as $server) {
      if (!$server['b']['status']) { continue; }

      $image_map = lgsl_image_map($server['b']['status'], $server['b']['type'], $server['s']['game'], $server['s']['map'], FALSE);

      $output .= "<div class='card card-body'>
      <div style='padding-bottom: 5px;' class='row'>
        <div style='display: inline-block;' class='col-md-2'>
          <img src='{$image_map}' width='160' height='auto' />
        </div>
        <div style='display: inline-block;vertical-align: super;' class='col-md-10'>
          <div>{$lgsl_config['text']['map']}: {$server['s']['map']}</div>
          <div>Link: <a href='{$image_map}' target='_blank'>{$image_map}</a></div>
          <form action='admincenter.php?site=admin_game_server' method='post' enctype='multipart/form-data'>
            Select image to upload:
            <input type='file' name='map' id='map' / class='btn btn-info'>
            <input type='hidden' name='lgsl_map_upload_path' value='{$server['b']['type']}/{$server['s']['game']}' />
            <input type='hidden' name='lgsl_map_upload_file' value='{$server['s']['map']}' />
            <input type='hidden' name='lgsl_management' value='{$_POST['lgsl_management']}' />
            <input type='hidden' name='lgsl_map_image_paths' value='true' />
            <input type='submit' class='btn btn-success' name='lgsl_map_image_upload' value='Upload Image' />
          </form>
        </div>
      </div></div>";
    }

    $output .= "
    <form method='post' action='' style='padding: 15px;'>
      <input type='hidden' name='lgsl_management' value='{$_POST['lgsl_management']}' />
      <input type='submit button' class='btn btn-success' name='lgsl_return' value='RETURN TO ADMIN' />
    </form>";

    return;
  }


//------------------------------------------------------------------------------------------------------------+

  $output .= "
  <form method='post' action=''>
    <div style='text-align:center; overflow:auto'>
      <table class='table table-striped'>
        <tr>
          <th style='text-align:center; white-space:nowrap'>[ ID ]                           </th>
          <th style='text-align:center; white-space:nowrap'>[ Game Type | Query Protocol ]   </th>
          <th style='text-align:center; white-space:nowrap'>[ IP ]                           </th>
          <th style='text-align:center; white-space:nowrap'>[ {$lgsl_config['text']['cpt']} ]</th>
          <th style='text-align:center; white-space:nowrap'>[ {$lgsl_config['text']['qpt']} ]</th>
          <th style='text-align:center; white-space:nowrap'>[ Software Port ]                </th>
          <th style='text-align:center; white-space:nowrap'>[ Zones ]                        </th>
          <th style='text-align:center; white-space:nowrap'>[ {$lgsl_config['text']['dsb']} ]</th>
          <th style='text-align:center; white-space:nowrap'>[ Comment ]                      </th>
        </tr>";

//---------------------------------------------------------+

      $ergebnis =
        safe_query("SELECT * FROM " . PREFIX . "plugins_game_server ORDER BY `id` ASC");
      while ($mysqli_row = mysqli_fetch_array($ergebnis))    
      {
        $id = $mysqli_row['id']; // ID USED AS [] ONLY RETURNS TICKED CHECKBOXES
        $disabled = ($mysqli_row['type'] == 'discord' ? 'disabled' : '');

        $output .= "
        <tr>
          <td>
            <a href='".lgsl_link($id)."' style='text-decoration:none' target='_blank'>{$id}</a>
          </td>
          <td>
            <select class='form-select' name='form_type[{$id}]'>";
//---------------------------------------------------------+
            foreach ($lgsl_type_list as $type => $description)
            {
              $output .= "
              <option ".($type == $mysqli_row['type'] ? "selected='selected'" : "")." value='{$type}'>{$description}</option>";
            }

            if (!isset($lgsl_type_list[$mysqli_row['type']]))
            {
              $output .= "
              <option selected='selected' value='".lgsl_string_html($mysqli_row['type'])."'>".lgsl_string_html($mysqli_row['type'])."</option>";
            }
//---------------------------------------------------------+
            $output .= "
            </select>
          </td>
          <td class='center'><input class='form-control' type='text' name='form_ip[{$id}]'       value='".lgsl_string_html($mysqli_row['ip'])."' size='15' maxlength='255' /></td>
          <td class='center'><input class='form-control' type='number' name='form_c_port[{$id}]' value='".lgsl_string_html($mysqli_row['c_port'])."' min='0' max='65536' {$disabled} /></td>
          <td class='center'><input class='form-control' type='number' name='form_q_port[{$id}]' value='".lgsl_string_html($mysqli_row['q_port'])."' min='0' max='65536' {$disabled} /></td>
          <td class='center'><input class='form-control' type='number' name='form_s_port[{$id}]' value='".lgsl_string_html($mysqli_row['s_port'])."' min='0' max='65536' {$disabled} /></td>
          <td>
            <select name='form_zone[$id]'>";
//---------------------------------------------------------+
            foreach ($zone_list as $zone)
            {
              $output .= "
              <option ".($zone == $mysqli_row['zone'] ? "selected='selected'" : "")." value='{$zone}'>{$zone}</option>";
            }

            if (!isset($zone_list[$mysqli_row['zone']]))
            {
              $output .= "
              <option selected='selected' value='".lgsl_string_html($mysqli_row['zone'])."'>".lgsl_string_html($mysqli_row['zone'])."</option>";
            }
//---------------------------------------------------------+
//---------------------------------------------------------+
            $output .= "
            </select>
          </td>
          <td class='center form-switch' style='padding: 0px 43px;'><input class='form-check-input' type='checkbox' name='form_disabled[{$id}]' value='1' ".(empty($mysqli_row['disabled']) ? "" : "checked='checked'")." /></td>
          <td class='center'><input class='form-control' type='text'     name='form_comment[{$id}]'  value='{$mysqli_row['comment']}' size='20' maxlength='255' /></td>
        </tr>";

        $last_type = $mysqli_row['type']; // SET LAST TYPE ( $mysqli_row EXISTS ONLY WITHIN THE LOOP )
      }
//---------------------------------------------------------+
        $id ++; // NEW SERVER ID CONTINUES ON FROM LAST

        $output .= "
        <tr>
          <td>NEW<a href='https://github.com/tltneon/lgsl/wiki/Supported-Games,-Query-protocols,-Default-ports' target='_blank' id='new_q' style='position: absolute;background: #fff;border-radius: 10px;width: 14px;height: 14px;border: 2px solid;margin-top: 7px;' title='How to choose query protocol?'>?</a></td>
          <td>
            <select name='form_type[{$id}]'>";
//---------------------------------------------------------+
            foreach ($lgsl_type_list as $type => $description)
            {
              $output .= "
              <option ".($type == $last_type ? "selected='selected'" : "")." value='{$type}'>{$description}</option>";
            }
//---------------------------------------------------------+
            $output .= "
            </select>
          </td>
          <td class='center'><input class='form-control' type='text'   name='form_ip[{$id}]'     value=''  size='15' maxlength='255' /></td>
          <td class='center'><input class='form-control' type='number' name='form_c_port[{$id}]' value=''  min='0' max='65536'   /></td>
          <td class='center'><input class='form-control' type='number' name='form_q_port[{$id}]' value=''  min='0' max='65536'   /></td>
          <td class='center'><input class='form-control' type='number' name='form_s_port[{$id}]' value='0' min='0' max='65536'   /></td>
          <td>
            <select name='form_zone[{$id}]'>";
//---------------------------------------------------------+
            foreach ($zone_list as $zone)
            {
              $output .= "
              <option value='{$zone}'>{$zone}</option>";
            }
//---------------------------------------------------------+
            $output .= "
            </select>
          </td>
          <td class='center form-switch' style='padding: 0px 43px;'><input class='form-check-input' type='checkbox' name='form_disabled[{$id}]' value='' /></td>
          <td class='center'><input class='form-control' type='text'     name='form_comment[{$id}]'  value='' size='20' maxlength='255' /></td>
        </tr>
      </table>

      <input type='hidden' name='lgsl_management' value='0' /><br><br>
      <table style='text-align:center;margin:auto'>
        <tr>
          <td><input class='form-control' type='submit' name='lgsl_save_1'          value='".$lgsl_config['text']['skc']."' />&nbsp; </td>
          <td><input class='form-control' type='submit' name='lgsl_save_2'          value='".$lgsl_config['text']['srh']."' />&nbsp; </td>
          <td><input class='form-control' type='submit' name='lgsl_map_image_paths' value='".$lgsl_config['text']['mip']."' />&nbsp; </td>
          
        </tr>
      </table>
    </div>
  </form>";

  $output .= lgsl_help_info();

//------------------------------------------------------------------------------------------------------------+

  function lgsl_help_info()
  {
    global $lgsl_config;
    return "
    <div style='text-align:center; line-height:1em; font-size:1em;'>
     
      <br /><br />
      {$lgsl_config['text']['faq']}
      <br /><br />
      <table cellspacing='10' cellpadding='0' style='border:1px solid; margin:auto; text-align:left'>
        <tr>
          <td> <a href='http://php.net/fsockopen'>FSOCKOPEN</a> </td>
          <td> {$lgsl_config['text']['enb']}: ".(function_exists("fsockopen") ? $lgsl_config['text']['yes'] : $lgsl_config['text']['nno'])." </td>
          <td> ( {$lgsl_config['text']['fso']} ) </td>
        </tr>
        <tr>
          <td> <a href='http://php.net/curl'>CURL</a> </td>
          <td> {$lgsl_config['text']['enb']}: ".((function_exists("curl_init") && function_exists("curl_setopt") && function_exists("curl_exec")) ? $lgsl_config['text']['yes'] : $lgsl_config['text']['nno'])." </td>
          <td> ( {$lgsl_config['text']['crl']} ) </td>
        </tr>
        <tr>
          <td> <a href='http://php.net/mbstring'>MBSTRING</a> </td>
          <td> {$lgsl_config['text']['enb']}: ".(function_exists("mb_convert_encoding") ? $lgsl_config['text']['yes'] : $lgsl_config['text']['nno'])." </td>
          <td> ( {$lgsl_config['text']['mbs']} ) </td>
        </tr>
        <tr>
          <td> <a href='http://php.net/bzip2'>BZIP2</a> </td>
          <td> {$lgsl_config['text']['enb']}: ".(function_exists("bzdecompress") ? $lgsl_config['text']['yes'] : $lgsl_config['text']['nno'])." </td>
          <td> ( {$lgsl_config['text']['bz2']} ) </td>
        </tr>
        <tr>
          <td> <a href='http://php.net/gd2'>GD</a> </td>
          <td> {$lgsl_config['text']['enb']}: ".(extension_loaded("gd") ? $lgsl_config['text']['yes'] : $lgsl_config['text']['nno'])." </td>
          <td> ( {$lgsl_config['text']['gd2']} ) </td>
        </tr>
        <tr>
          <td> <a href='http://php.net/zlib'>ZLIB</a> </td>
          <td> {$lgsl_config['text']['enb']}: ".(function_exists("gzuncompress") ? $lgsl_config['text']['yes'] : $lgsl_config['text']['nno'])." </td>
          <td> ( {$lgsl_config['text']['zli']} ) </td>
        </tr>
      </table>
      <br /><br />
      <br /><br />
    </div>";
  }

//------------------------------------------------------------------------------------------------------------+

  function lgsl_stripslashes_deep($value)
  {
    $value = is_array($value) ? array_map('lgsl_stripslashes_deep', $value) : stripslashes($value);
    return $value;
  }

//------------------------------------------------------------------------------------------------------------+

  function lgsl_htmlspecialchars($string)
  {
    // PHP4 COMPATIBLE WAY OF CONVERTING SPECIAL CHARACTERS WITHOUT DOUBLE ENCODING EXISTING ENTITIES
    $string = str_replace("\x05\x06", "", $string);
    $string = preg_replace("/&([a-z\d]{2,7}|#\d{2,5});/i", "\x05\x06$1", $string);
    $string = htmlspecialchars($string, ENT_QUOTES);
    $string = str_replace("\x05\x06", "&", $string);

    return $string;
  }

//------------------------------------------------------------------------------------------------------------+
