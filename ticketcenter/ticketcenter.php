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
    $_lang = $pm->plugin_language("ticketcenter", $plugin_path);

$data_array = array();
$data_array['$title']=$_lang['title'];    
$data_array['$subtitle']='Ticketcenter';

$template = $GLOBALS["_template"]->loadTemplate("ticket_show","title", $data_array, $plugin_path);
echo $template;
 
 
if(isset($_GET['action'])) $action = $_GET['action'];
 
else $action = "";
 
if(isset($_GET['ticketID'])) $ticketID = $_GET['ticketID'];
else $ticketID = "";
 
//checklogin
if (!$userID) {
    echo ''.$_lang['login'].'';
}
 
// neues Ticket ersellen
elseif($action=="newticket") {
    echo '<nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?site=ticketcenter">'.$_lang['ticketcenter'].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$_lang['new_ticket'].'</li>
                </ol>
            </nav>';
    function generate_options($ticketcats = '', $offset = '', $subcatID = 0) {
        $rubrics = safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter_categories WHERE subcatID = '".$subcatID."' ORDER BY name");
        while($dr = mysqli_fetch_array($rubrics)) {
            $ticketcats .= '<option value="'.$dr['ticketcatID'].'">'.$offset.htmlspecialchars($dr['name']).'</option>';
            if(mysqli_num_rows(safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter_categories WHERE subcatID = '".$dr['ticketcatID']."'"))) {
                $ticketcats .= generate_options("", $offset."- ", $dr['ticketcatID']);
            }
        }
        return $ticketcats;
    }
    $ticketcats = generate_options();
    
 
            $data_array= array();
            $data_array['$ticketcats'] = $ticketcats;
            $data_array['$userID'] = $userID;
            $data_array['$new_ticket']=$_lang['new_ticket'];
            $data_array['$category']=$_lang['category'];
            $data_array['$priority']=$_lang['priority'];
            $data_array['$low']=$_lang['low'];
            $data_array['$normal']=$_lang['normal'];
            $data_array['$high']=$_lang['high'];
            $data_array['$ticketname']=$_lang['ticketname'];
            $data_array['$info']=$_lang['info'];
            $data_array['$pn_antwort']=$_lang['pn_antwort'];
            $data_array['$mail_antwort']=$_lang['mail_antwort'];
            $data_array['$create_ticket']=$_lang['create_ticket'];

            
 
    $template = $GLOBALS["_template"]->loadTemplate("ticket_show","new", $data_array, $plugin_path);
    echo $template;
 
    }
 
// Ticket speichern
elseif($action == "save") {
    $_language->readModule('ticketcenter');
    $poster =   $_POST['poster'];
    $ticketcat =  $_POST['ticketcat'];
    $ticketname =  $_POST['ticketname'];
    $ticketinfo =  $_POST['message'];
    $priority =  $_POST['priority'];
    if (isset($_POST['notify'])) $notify = $_POST['notify'];
    else $notify = 0;

    if(is_array($notify)){
        $notify = array_sum($notify);
    }

    $date = time();
    $qry = "INSERT INTO
                     " . PREFIX . "plugins_ticketcenter (
                            ticketcatID,
                            poster,
                            date,
                            ticketname,
                            ticketinfo,
                            priority,
                            notify
                             )
                             VALUES (
                             '" . $ticketcat . "',
                              '" . $poster . "',
                               '" . $date . "',
                                '" . $ticketname . "',
                                 '" . $ticketinfo . "'
                                 , '" . $priority . "',
                                  '" . $notify . "'
                                  )"
    ;


    if(safe_query($qry)) {
        redirect("index.php?site=ticketcenter", $_lang['ticket_createt'], "1");
    }
    else  redirect("index.php?site=ticketcenter", $_lang['error_repeat'], "1");
}
 
// Ticket wieder öffnen
elseif($action=="reopen") {
    $poster = $_POST['poster'];
    $ticketID = $_POST['ticketID'];
    $ticketinfo = '<br><p class="text-center text-success"><b>'.$_lang['ticket_reopened'].'</b></p>';
        
    $date = time();
 
    $qry = "INSERT INTO " . PREFIX . "plugins_ticketcenter ( masterticketID, poster, date, ticketinfo, admin ) VALUES ( '" . $ticketID . "', '" . $poster . "', '" . $date . "', '".$ticketinfo."', '0' )";
    if(safe_query($qry)) {

        


        safe_query("UPDATE " . PREFIX . "plugins_ticketcenter SET ticketstatus = '1', userarchiv = '0', adminarchiv = '0' WHERE ticketID='$ticketID'");
        redirect('index.php?site=ticketcenter&amp;action=show&amp;ticketID='.$ticketID , $_lang['ticket_reopened_re'] ,1);
    }
    else  redirect('index.php?site=ticketcenter&amp;action=show&amp;ticketID='.$ticketID , $_lang['error_repeat'] ,3);
}
 
// Ticket archivieren
elseif($action == "archive") {
 
    $poster = $_POST[ 'poster' ];
    $ticketID = $_POST[ 'ticketID' ];
 
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter WHERE ticketID='$ticketID'");
    $eh = mysqli_fetch_array($ergebnis);
 
    if($poster == $eh['poster'])  safe_query("UPDATE " . PREFIX . "plugins_ticketcenter SET userarchiv = '1' WHERE ticketID='$ticketID'");
    elseif($poster == $eh['admin']) safe_query("UPDATE " . PREFIX . "plugins_ticketcenter SET adminarchiv = '1' WHERE ticketID='$ticketID'");
 
    if($ergebnis) {
        redirect('index.php?site=ticketcenter&amp;action=show&amp;ticketID='.$ticketID , $_lang['ticket_archived'] ,1);
    }
    else  redirect('index.php?site=ticketcenter&amp;action=show&amp;ticketID='.$ticketID , $_lang['error_repeat'] ,3);
}
 
// Ticketantwort speichern
elseif($action == "saveantwort") {
    $poster =   $_POST['poster'];
    $ticketID =  $_POST['ticketID'];
    $ticketinfo =  $_POST['message'];
    if (isset($_POST['closeticket'])) $closeticket = $_POST['closeticket'];
    else $closeticket = false;
 
    $date = time();
 
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter WHERE ticketID='$ticketID'");
    $eh = mysqli_fetch_array($ergebnis);
 
    if($poster != $eh['poster'] && $eh['notify'] != 0){
        $sendpn  = false;
        $sendmail  = false;
 
        if($eh['notify'] == 1){
            $sendpn  = true;
        }
        elseif($eh['notify'] == 2){
            $sendmail  = true;
        }
        elseif($eh['notify'] == 3){
            $sendpn  = true;
            $sendmail  = true;
        }
        if($eh['notify'] != 0){
            $ticketname = $eh['ticketname'];
            $receiver = $eh['poster'];
            $signatur_pn = '<a href="index.php?site=ticketcenter&action=show&ticketID='.$ticketID.'">Ticketcenter</a>';
            $signatur_mail = 'http://'.$hp_url.'/index.php?site=ticketcenter&action=show&ticketID='.$ticketID;
 
            if ($closeticket == 1 && $ticketinfo == ''){
                $header =  $_lang['mail_head_close'];
                $message =  str_replace(array('%username%', '%ticketname%'), array(stripslashes(getnickname($receiver)), $ticketname), $_lang['mail_body_close']);
            }
            elseif ($closeticket == 1 && $ticketinfo != ''){
                $header =  $_lang['mail_head_text'];
                $message =  str_replace(array('%username%', '%ticketname%'), array(stripslashes(getnickname($receiver)), $ticketname), $_lang['mail_body_text_close']);
            }
            elseif ($closeticket == 0){
                $header =  $_lang['mail_head_text'];
                $message =  str_replace(array('%username%', '%ticketname%'), array(stripslashes(getnickname($receiver)), $ticketname), $_lang['mail_body_text']);
            }
        }
        if($sendpn) {
            sendmessage($receiver, $header, $message.$signatur_pn, $userID);
        }
        if($sendmail) {
            $ergebnis = safe_query("SELECT email FROM " . PREFIX . "user WHERE userID='$receiver'");
            $ds = mysqli_fetch_array($ergebnis);
            $email = $ds['email'];
            mail($email, $header, $message.$signatur_mail, "From:".$admin_email."\nContent-type: text/plain; charset=utf-8\n");
        }
    }
 
    if($closeticket == 1){
        safe_query("UPDATE " . PREFIX . "plugins_ticketcenter SET ticketstatus = '2' WHERE ticketID='$ticketID'");
        if($ticketinfo == '')  $ticketinfo = '<br><p class="text-center text-danger"><b>'.$_lang['ticket_closed'].'</b></p>';
        else $ticketinfo =   $ticketinfo.'<br><p class="text-center text-danger"><b>'.$_lang['ticket_closed'].'</b></p>';
    }
 
    $qry = "INSERT INTO " . PREFIX . "plugins_ticketcenter ( masterticketID, poster, date, ticketinfo, admin ) VALUES ( '".$ticketID."', '".$poster."', '".$date."', '".$ticketinfo."', '0')";
    if(safe_query($qry)) {
        redirect('index.php?site=ticketcenter&amp;action=show&amp;ticketID='.$ticketID , $_lang['reply_saved'] ,1);
    }
    else redirect('index.php?site=ticketcenter&amp;action=show&amp;ticketID='.$ticketID , $_lang['error_repeat'] ,3);
 
}
 
// Einzelticket anzeigen
elseif(($action == "show") && ($ticketID != "")) {
 
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter WHERE ticketID='$ticketID'");
    $eh = mysqli_fetch_array($ergebnis);
 
    if(($eh['poster'] == $userID) || (isfeedbackadmin($userID))){
       
        // Headabfrage
        $ticketID = $eh['ticketID'];
        $ticketname = $eh['ticketname'];
        $notify = $eh['notify'];
        $ticketstatus = $eh['ticketstatus'];
        $ticketpriority = $eh['priority'];

       
        if ($notify == 0) {
            $notify = $_lang['inaktiv'];
        }
        elseif ($notify == 1) {    
            $notify = $_lang['aktiv_pn'];
        }
        elseif ($notify == 2) {
            $notify = $_lang['aktiv_mail'];
        }
        elseif ($notify == 3) {
            $notify = $_lang['aktiv_pn_mail'];
        }
 
        $ticketstatus = $eh['ticketstatus'];

        if($eh['admin'] !== '0') {
          $ticketadmin = '<a href="index.php?site=profile&amp;id='.$eh['admin'].'">'.getnickname($eh['admin']).'</a>';
        } else {
          $ticketadmin = '';
        }
 
        if($ticketstatus == 0){
            $ticketstatus = '<spam class="text-success">'.$_lang['open'].'</spam>';
            $ticketadmin = '';
        }
        elseif($ticketstatus == 1){
            $ticketstatus = '<spam class="text-warning">'.$_lang['process'].'</spam>';
        }
        elseif($ticketstatus == 2){
            $ticketstatus = '<spam class="text-danger">'.$_lang['closed'].'</spam>';
        }
 
        if ($ticketpriority == 1) {
            $ticketpriority = '<spam class="text-success">'.$_lang['low'].'</spam>';
        }
        elseif ($ticketpriority == 2) {
            $ticketpriority = '<spam class="text-warning">'.$_lang['normal'].'</spam>';
        }
        elseif ($ticketpriority == 3) {
            $ticketpriority = '<spam class="text-danger">'.$_lang['high'].'</spam>';
        }
 
        $poster = '<a href="index.php?site=profile&amp;id='.$eh['poster'].'">'.getnickname($eh['poster']).'</a>';
 
        $ticketcatID = $eh['ticketcatID'];
        $cat = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter_categories WHERE ticketcatID='".$ticketcatID."'"));
        $category = '<nobr>'.$cat['name'].'</nobr>';
        $cat_id = $cat['subcatID'];
 
        while($data_array['$cat_id !'] = 0) {
            $subcat = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter_categories WHERE ticketcatID='".$cat_id."'"));
            $category = '<nobr>'.$subcat['name'].'</nobr> &raquo; '.$category;
            $cat_id = $subcat['subcatID'];
        }
 
        if(($eh['poster'] == $userID && $eh['userarchiv'] == 0) || ($eh['admin'] == $userID && $eh['adminarchiv'] == 0)){
            echo '<nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?site=ticketcenter">'.$_lang['ticketcenter'].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$_lang['show_ticket'].'</li>
                </ol>
            </nav>';
        }
        elseif(($eh['poster'] == $userID && $eh['userarchiv'] == 1) ||($eh['admin'] == $userID && $eh['admin'] == 1)){
            echo '<nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?site=ticketcenter">'.$_lang['ticketcenter'].'</a></li>
                <li class="breadcrumb-item"><a href="index.php?site=ticketcenter&amp;action=archiv">'.$_lang['show_archiv'].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$_lang['show_ticket'].'</li>
                </ol>
            </nav>';
        }
        elseif($eh['admin'] == 0){
            echo '<nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?site=ticketcenter">'.$_lang['ticketcenter'].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$_lang['show_open_ticket'].'</li>
                </ol>
            </nav>';
        }

        
 
        $data_array= array();
            $data_array['$tickedid']=$_lang['tickedid'];
            $data_array['$categori']=$_lang['categori'];
            $data_array['$userinfo']=$_lang['userinfo'];
            $data_array['$ticketcreator']=$_lang['ticketcreator'];
            $data_array['$priority']=$_lang['priority'];
            $data_array['$ticketstate']=$_lang['ticketstate'];
            $data_array['$tickname']=$_lang['tickname'];
          	$data_array['$tickadmin']=$_lang['tickadmin'];
            $data_array['$ticketID'] = $ticketID;
            $data_array['$ticketdate'] = date("d.m.y H:i", $eh['date']);
            $data_array['$ticketname'] = $ticketname;
            $data_array['$ticketadmin'] = $ticketadmin;
            $data_array['$ticketstatus'] = $ticketstatus;
            $data_array['$notify'] = $notify;
            $data_array['$ticketpriority'] = $ticketpriority;
            $data_array['$category'] = $category;
            $data_array['$poster'] = $poster;

       $template = $GLOBALS["_template"]->loadTemplate("ticket_show","head", $data_array, $plugin_path);
       echo $template;
        
        // Contentabfrage / Schleife,
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter WHERE ticketID='$ticketID' OR masterticketID='$ticketID' ORDER BY date ASC");
        $n = 0;
        while($ds=mysqli_fetch_array($ergebnis)) {
            $n++;
            $poster = '<a href="index.php?site=profile&amp;id='.$ds['poster'].'">'.getnickname($ds['poster']).'</a>';
            $data_array = array();
            $data_array['$ticketID'] = $ticketID;
            $data_array['$ticketdate'] = '#'.$n.' | '.date("d.m.y H:i", $ds['date']);
            $data_array['$ticketname'] = $ds['ticketname'];
            $data_array['$ticketadmin'] = $ds['admin'];
            $data_array['$ticketinfo'] = $ds['ticketinfo'];
            $data_array['$autor'] = $poster;
            $data_array['$userID'] = $userID;
            $data_array['$reply']=$_lang['reply'];
            $data_array['$ticket_archivet']=$_lang['ticket_archivet'];
            $data_array['$open_ticket']=$_lang['open_ticket'];
 
 
            if ($ds['poster'] == $eh['admin']){
                $data_array['$bgcolor'] = '#dc3545';
            }
            elseif  ($ds['poster'] == $eh['poster']){
                $data_array['$bgcolor'] = '#28a745';
            }
 
 
            $template = $GLOBALS["_template"]->loadTemplate("ticket_show","content_area", $data_array, $plugin_path);
            echo $template;
 
        }
 
        // Foot
 
 
        $adminaction = '';
        $closeticket = '';
 
        if((isfeedbackadmin($userID)) && ($eh['admin'] == 0) && ($eh['poster'] != $userID)) {
            $data_array['$adminaction'] = '<tr><td colspan="4" align="center"><br/><a class="btn btn-primary" type="button" href="index.php?site=ticketcenter&amp;action=take&amp;ticketID='.$ticketID.'"><b>'.$_lang['take_ticket'].'</b></a></td></tr>';
                    } else {
			$data_array['$adminaction'] = " ";
		}
 
        $template = $GLOBALS["_template"]->loadTemplate("ticket_show","footer", $data_array, $plugin_path);
        echo $template;
 
        if ($eh[ 'admin' ] == $userID && $eh[ 'ticketstatus' ] == 2 && $eh[ 'adminarchiv' ] != 1) {

 
            $template = $GLOBALS["_template"]->loadTemplate("ticket_show","archive_footer", $data_array, $plugin_path);
            echo $template;
        }
        if ($eh['admin'] == $userID && $eh['ticketstatus'] == 2) {
 
            $template = $GLOBALS["_template"]->loadTemplate("ticket_show","reopen_footer", $data_array, $plugin_path);
            echo $template;
        }
 
        if ($eh['poster'] == $userID && $eh['ticketstatus'] == 2 && $eh['userarchiv'] != 1) {
            
 
           $template = $GLOBALS["_template"]->loadTemplate("ticket_show","archive_footer", $data_array, $plugin_path);
            echo $template;
        }
 
        if (($eh['admin'] == $userID || $eh['poster'] == $userID) && ($eh['ticketstatus'] != 2 )) {
            $data_array['$closeticket'] = '<input type="checkbox" id="closeticket" name="closeticket" value="1">'.$_lang['close_ticket'].'';
 
            $template = $GLOBALS["_template"]->loadTemplate("ticket_show","antwort_footer", $data_array, $plugin_path);
            echo $template;
        }
    }
    else echo $_lang['not_your_ticket'];
}
 
// Ticket übernehmen
elseif(($action == "take") && ($ticketID != "")) {
    if((isfeedbackadmin($userID))){
        safe_query("UPDATE " . PREFIX . "plugins_ticketcenter SET admin = '".$userID."', ticketstatus = '1' WHERE ticketID='$ticketID'");
        redirect('index.php?site=ticketcenter&amp;action=show&amp;ticketID='.$ticketID , $_lang['ticket_taken'],1);
    }
    else redirect('index.php?site=ticketcenter&amp;action=show&amp;ticketID='.$ticketID , $_lang['error_repeat'] ,3);
}
 
// archivierte Tickets anzeigen
elseif($action == "archiv"){
 
    echo '<nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?site=ticketcenter">'.$_lang['ticketcenter'].'</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$_lang['show_archiv'].'</li>
                </ol>
            </nav>';
 
    // Admins sehen ihre archivierten Tickets
   
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter WHERE admin='".$userID."' AND masterticketID='0' AND  adminarchiv ='1' ORDER BY date DESC");
    if(mysqli_num_rows($ergebnis)) {
 
        $data_array= array();
        $data_array['$my_admin_tickets']=$_lang['my_admin_tickets'];
        $data_array['$priority']=$_lang['priority'];
        $data_array['$date']=$_lang['date'];
        $data_array['$ticketname']=$_lang['ticketname'];
        $data_array['$ticketstate']=$_lang['ticketstate'];
               
        $template = $GLOBALS["_template"]->loadTemplate("ticket_show","admin_head", $data_array, $plugin_path);
        echo $template;
       
       
        $n=1;
        while($ds = mysqli_fetch_array($ergebnis)) {
            
            $ticketcatID = $ds['ticketcatID'];
            $ticketid = $ds['ticketID'];
            $ticketname = $ds['ticketname'];
            $ticketstatus = $ds['ticketstatus'];
            $cat = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter_categories WHERE ticketcatID='".$ticketcatID."'"));
            $category = '<nobr>'.$cat['name'].'</nobr>';
 
            $cat_id = $cat['subcatID'];
            while($cat_id != 0) {
                $subcat = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter_categories WHERE ticketcatID='".$cat_id."'"));
                $category = '<nobr>'.$subcat['name'].'</nobr> &raquo; '.$category;
                $cat_id = $subcat['subcatID'];
            }
            $ticketpriority = $ds['priority'];
            
            if ($ticketpriority == 1) {
            $ticketpriority = '<spam class="text-success">'.$_lang['low'].'</spam>';
            }
            elseif ($ticketpriority == 2) {
            $ticketpriority = '<spam class="text-warning">'.$_lang['normal'].'</spam>';
            }
            elseif ($ticketpriority == 3) {
            $ticketpriority = '<spam class="text-danger">'.$_lang['high'].'</spam>';
            }
           
            $ticketlink = '<a href="index.php?site=ticketcenter&amp;action=show&amp;ticketID='.$ticketid.'">'.$ticketname.'</a>';
           
            if($ticketstatus == 0){
                $ticketstatus = '<spam class="text-success">'.$_lang['open'].'</spam>';
                $ticketadmin = '';
            }
            elseif($ticketstatus == 1){
                $ticketstatus = '<spam class="text-warning">'.$_lang['process'].'</spam>';
            }
            elseif($ticketstatus == 2){
                $ticketstatus = '<spam class="text-danger">'.$_lang['closed'].'</spam>';
            }
            $poster = '<a href="index.php?site=profile&amp;id='.$ds['poster'].'">'.getnickname($ds['poster']).'</a>';
           
            $data_array = array();
            $data_array['$ticketid'] = $ticketid;
            $data_array['$ticketdate'] = date("d.m.y", $ds['date']);
            $data_array['$ticketname'] = $ticketname;
            $data_array['$ticketpriority'] = $ticketpriority;
            $data_array['$ticketstatus'] = $ticketstatus;
            $data_array['$ticketlink'] = $ticketlink;
           
            $template = $GLOBALS["_template"]->loadTemplate("ticket_show","admin_content_area", $data_array, $plugin_path);
            echo $template;
           
            $n++;
        }

        $template = $GLOBALS["_template"]->loadTemplate("ticket_show","admin_footer", $data_array, $plugin_path);
        echo $template;
    }
 
    // eigende archivierten Tickets anzeigen


 
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter WHERE poster='$userID' AND masterticketID='0' AND userarchiv='1' ORDER BY ticketstatus ASC, date DESC");
    if(mysqli_num_rows($ergebnis)) {

        $data_array= array();
        $data_array['$my_tickets']=$_lang['my_tickets'];
        $data_array['$ticketadmin']=$_lang['ticketadmin'];
        $data_array['$date']=$_lang['date'];
        $data_array['$ticketname']=$_lang['ticketname'];
        $data_array['$ticketstate']=$_lang['ticketstate'];
   
        $template = $GLOBALS["_template"]->loadTemplate("ticket_show","own_head", $data_array, $plugin_path);
        echo $template;
       
        $n=1;
        while($ds = mysqli_fetch_array($ergebnis)) {
               
           
            $ticketid = $ds['ticketID'];
            $ticketname = $ds['ticketname'];
            $ticketstatus = $ds['ticketstatus'];
            $ticketlink = '<a href="index.php?site=ticketcenter&amp;action=show&amp;ticketID='.$ticketid.'">'.$ticketname.'</a>';
 
            if($ds['admin'] !== '0') {
              $ticketadmin = '<a href="index.php?site=profile&amp;id='.$ds['admin'].'">'.getnickname($ds['admin']).'</a>';
            } else {
              $ticketadmin = '';
            }

            if($ticketstatus == 0){
                $ticketstatus = '<spam class="text-success">'.$_lang['open'].'</spam>';
                $ticketadmin = '';
            }
            elseif($ticketstatus == 1){
                $ticketstatus = '<spam class="text-warning">'.$_lang['process'].'</spam>';
            }
            elseif($ticketstatus == 2){
                $ticketstatus = '<spam class="text-danger">'.$_lang['closed'].'</spam>';
            }
           
            $data_array = array();
            $data_array['$ticketid'] = $ticketid;
            $data_array['$ticketdate'] = date("d.m.y", $ds['date']);
            $data_array['$ticketname'] = $ticketname;
            $data_array['$ticketstatus'] = $ticketstatus;
            $data_array['$ticketlink'] = $ticketlink;
            $data_array['$ticketadmin'] = $ticketadmin;
 
            $template = $GLOBALS["_template"]->loadTemplate("ticket_show","own_content", $data_array, $plugin_path);
            echo $template;
 
            $n++;
        }
        
        $template = $GLOBALS["_template"]->loadTemplate("ticket_show","own_footer", $data_array, $plugin_path);
        echo $template;
    }
}
 
//Tickets anzeigen
 
else{
 
    echo '<nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?site=ticketcenter">'.$_lang['ticketcenter'].'</a></li>
            </ol>
          </nav>';
    echo '<div class="btn-group"><input class="btn btn-primary" type="button" onclick=window.open("index.php?site=ticketcenter&amp;action=newticket","_self") value="' . $_lang[ 'create_new_ticket' ] . '">&nbsp;';
    if((mysqli_num_rows(safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter WHERE admin='".$userID."' and adminarchiv='1'"))) || (mysqli_num_rows(safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter WHERE poster='".$userID."' and userarchiv='1'")))){
        echo ' <input type="button" onclick=window.open("index.php?site=ticketcenter&amp;action=archiv","_self") value="' . $_lang[ 'show_archiv' ] . '" class="btn btn-primary">';
    }

    echo'</div><br><br>';
  #end
   
    // Admins sehen alle offenen Tickets
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter WHERE ticketstatus='0' AND poster<>'".$userID."' AND masterticketID='0' ORDER BY priority DESC, date DESC");
    if(isfeedbackadmin($userID) && mysqli_num_rows($ergebnis)) {
 
        $data_array= array();
        $data_array['$open_tickets']=$_lang['open_tickets'];
        $data_array['$priority']=$_lang['priority'];
        $data_array['$date']=$_lang['date'];
        $data_array['$category']=$_lang['category'];
        $data_array['$ticketname']=$_lang['ticketname'];
 
        $template = $GLOBALS["_template"]->loadTemplate("ticket_show","open_head", $data_array, $plugin_path);
        echo $template;
 
        $n=1;
        while($ds = mysqli_fetch_array($ergebnis)) {
            
            $ticketcatID = $ds['ticketcatID'];
            $cat = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter_categories WHERE ticketcatID='".$ticketcatID."'"));
            $category = '<nobr>'.$cat['name'].'</nobr>';
 
            $cat_id = $cat['subcatID'];
            while($cat_id != 0) {
                $subcat = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter_categories WHERE ticketcatID='".$cat_id."'"));
                $category = '<nobr>'.$subcat['name'].'</nobr> &raquo; '.$category;
                $cat_id  = $subcat['subcatID'];
 
            }
            $ticketpriority = $ds['priority'];
            if ($ticketpriority == 1) {
            $ticketpriority = '<spam class="text-success">'.$_lang['low'].'</spam>';
            }
            elseif ($ticketpriority == 2) {
            $ticketpriority = '<spam class="text-warning">'.$_lang['normal'].'</spam>';
            }
            elseif ($ticketpriority == 3) {
            $ticketpriority = '<spam class="text-danger">'.$_lang['high'].'</spam>';
            }
           
            $ticketlink = '<a href="index.php?site=ticketcenter&amp;action=show&amp;ticketID='.$ds['ticketID'].'">'.$ds['ticketname'].'</a>';
            $poster = '<a href="index.php?site=profile&amp;id='.$ds['poster'].'">'.getnickname($ds['poster']).'</a>';
           
            $data_array = array();
            $data_array['$ticketid'] = $ds['ticketID'];
            $data_array['$ticketpriority'] = $ticketpriority;
            $data_array['$ticketdate'] = date("d.m.y", $ds['date']);
            $data_array['$ticketname'] = $ds['ticketname'];
            $data_array['$ticketstatus'] = $ds['ticketstatus'];
            $data_array['$ticketlink'] = $ticketlink;
            $data_array['$poster'] = $poster;
            $data_array['$category'] = $category;
            $data_array['$cat_id'] = $cat_id;
 
            $template = $GLOBALS["_template"]->loadTemplate("ticket_show","open_content", $data_array, $plugin_path);
            echo $template;
 
            $n++;
        }
        
        $template = $GLOBALS["_template"]->loadTemplate("ticket_show","open_footer", $data_array, $plugin_path);
        echo $template;
    }
 
    // Admins sehen ihren angenommen Tickets
 
        if($adminticket = mysqli_num_rows(safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter WHERE admin='".$userID."' AND adminarchiv ='0'"))){
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter WHERE admin='".$userID."' AND masterticketID='0' AND adminarchiv ='0' ORDER BY priority DESC, date DESC");
 
        $data_array= array();
        $data_array['$my_admin_tickets']=$_lang['my_admin_tickets'];
        $data_array['$priority']=$_lang['priority'];
        $data_array['$date']=$_lang['date'];
        $data_array['$ticketname']=$_lang['ticketname'];
        $data_array['$ticketstate']=$_lang['ticketstate'];
 
        $template = $GLOBALS["_template"]->loadTemplate("ticket_show","admin_head", $data_array, $plugin_path);
        echo $template;
       
 
        $n=1;
        while($ds = mysqli_fetch_array($ergebnis)) {
            
            $ticketcatID = $ds['ticketcatID'];
            $cat = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter_categories WHERE ticketcatID='".$ticketcatID."'"));
            $category = '<nobr>'.$cat['name'].'</nobr>';
 
            $cat_id = $cat['subcatID'];
            while($cat_id != 0) {
                $subcat = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter_categories WHERE ticketcatID='".$cat_id."'"));
                $category = '<nobr>'.$subcat['name'].'</nobr> &raquo; '.$category;
                $cat_id = $subcat['subcatID'];
            }
            $ticketname = $ds['ticketname'];
            $ticketstatus = $ds['ticketstatus'];
            $ticketlink = '<a href="index.php?site=ticketcenter&amp;action=show&amp;ticketID='.$ds['ticketID'].'">'.$ticketname.'</a>';
            $ticketpriority = $ds['priority'];
            if ($ticketpriority == 1) {
            $ticketpriority = '<spam class="text-success">'.$_lang['low'].'</spam>';
            }
            elseif ($ticketpriority == 2) {
            $ticketpriority = '<spam class="text-warning">'.$_lang['normal'].'</spam>';
            }
            elseif ($ticketpriority == 3) {
            $ticketpriority = '<spam class="text-danger">'.$_lang['high'].'</spam>';
            }
           
            if($ticketstatus == 0){
                $ticketstatus = '<spam class="text-success">'.$_lang['open'].'</spam>';
                $ticketadmin = '';
            }
            elseif($ticketstatus == 1){
                $ticketstatus = '<spam class="text-warning">'.$_lang['process'].'</spam>';
            }
            elseif($ticketstatus == 2){
                $ticketstatus = '<spam class="text-danger">'.$_lang['closed'].'</spam>';
            }
            $poster='<a href="index.php?site=profile&amp;id='.$ds['poster'].'">'.getnickname($ds['poster']).'</a>';

            $data_array = array();
            $data_array['$ticketid'] = $ds['ticketID'];
            $data_array['$ticketpriority'] = $ticketpriority;
            $data_array['$ticketdate'] = date("d.m.y", $ds['date']);
            $data_array['$ticketname'] = $ticketname;
            $data_array['$ticketstatus'] = $ticketstatus;
            $data_array['$ticketlink'] = $ticketlink;
            $data_array['$poster'] = $poster;
 
            $template = $GLOBALS["_template"]->loadTemplate("ticket_show","admin_content_area", $data_array, $plugin_path);
            echo $template;
 
            $n++;
        }
        
        $template = $GLOBALS["_template"]->loadTemplate("ticket_show","admin_footer", $data_array, $plugin_path);
        echo $template;
    }
 
    // eigende Tickets anzeigen
   $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_ticketcenter WHERE poster='".$userID."' AND masterticketID='0' AND userarchiv='0' ORDER BY ticketstatus ASC, date DESC");
    if(mysqli_num_rows($ergebnis)) {
 
        $data_array= array();
        $data_array['$my_tickets']=$_lang['my_tickets'];
        $data_array['$date']=$_lang['date'];
        $data_array['$ticketname']=$_lang['ticketname'];
        $data_array['$ticketadmin']=$_lang['ticketadmin'];
        $data_array['$ticketstate']=$_lang['ticketstate'];
 
        

        $template = $GLOBALS["_template"]->loadTemplate("ticket_show","own_head", $data_array, $plugin_path);
        echo $template;
 
        $n=1;
        while($ds = mysqli_fetch_array($ergebnis)) {
 
           
            $ticketid = $ds['ticketID'];
            $ticketdate = date("d.m.y", $ds['date']);
            $ticketname = $ds['ticketname'];
            $ticketlink = '<a href="index.php?site=ticketcenter&amp;action=show&amp;ticketID='.$ticketid.'">'.$ticketname.'</a>';
            $ticketstatus = $ds['ticketstatus'];
            if($ds['admin'] !== '0') {
              $ticketadmin = '<a href="index.php?site=profile&amp;id='.$ds['admin'].'">'.getnickname($ds['admin']).'</a>';
            } else {
              $ticketadmin = '';
            }
            if($ticketstatus == 0){
                $ticketstatus = '<spam class="text-success">'.$_lang['open'].'</spam>';
                $ticketadmin = '';
            }
            elseif($ticketstatus == 1){
                $ticketstatus = '<spam class="text-warning">'.$_lang['process'].'</spam>';
            }
            elseif($ticketstatus == 2){
                $ticketstatus = '<spam class="text-danger">'.$_lang['closed'].'</spam>';
            }
 
            $data_array = array();
            $data_array['$ticketid'] = $ticketid;
            $data_array['$ticketdate'] = $ticketdate;
            $data_array['$ticketname'] = $ticketname;
            $data_array['$ticketlink'] = $ticketlink;
            $data_array['$ticketstatus'] = $ticketstatus;
            $data_array['$ticketadmin'] = $ticketadmin;
           
       
            $template = $GLOBALS["_template"]->loadTemplate("ticket_show","own_content", $data_array, $plugin_path);
            echo $template;
           
            $n++;
        }
        
        $template = $GLOBALS["_template"]->loadTemplate("ticket_show","own_footer", $data_array, $plugin_path);
        echo $template;
    }
}
?>