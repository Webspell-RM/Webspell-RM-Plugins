<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                  Webspell-RM      /                        /   /                                          *
 *                  -----------__---/__---__------__----__---/---/-----__---- _  _ -                         *
 *                   | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                          *
 *                  _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                          *
 *                               Free Content / Management System                                            *
 *                                           /                                                               *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         webspell-rm                                                                              *
 *                                                                                                           *
 * @copyright       2018-2023 by webspell-rm.de                                                              *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de                 *
 * @website         <https://www.webspell-rm.de>                                                             *
 * @forum           <https://www.webspell-rm.de/forum.html>                                                  *
 * @wiki            <https://www.webspell-rm.de/wiki.html>                                                   *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                         *
 *                  It's NOT allowed to remove this copyright-tag                                            *
 *                  <http://www.fsf.org/licensing/licenses/gpl.html>                                         *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                        *
 * @copyright       2005-2011 by webspell.org / webspell.info                                                *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
*/
# www.ZENITH-Developments.de 
if(!function_exists('zntdev_recomments_get')) {
    function zntdev_recomments_get($id, $uid) {
        try {
            $output = "";
            $q = safe_query("SELECT * FROM  `" . PREFIX . "plugins_news_manager_comments_recomment` WHERE `comment_id`='" . $id . "' ORDER BY `recoID` DESC");
            
$lang = detectCurrentLanguage();
            if ($lang == 'de') {
                 $delete = 'Dein Reply löschen';
                 $minutes = ' Minuten';
                 $onehourago = ' vor 1 Stunde';
                 $overonehour = 'über 1 Stunde';
                 $hours = ' vor Stunden';
                 $days = ' Tage';
                 $alotoftime= ' viel Zeit';
                 $reply= 'Reply';
                 $reComment= 'Antwort zum Kommentar';
            }elseif ($lang == 'en') {
                 $delete = 'Delete';
                 $minutes = ' minutes';
                 $onehourago = ' 1 hour ago';
                 $overonehour = ' over 1 hour';
                 $hours = ' hours';
                 $days = ' days';
                 $alotoftime= ' a lot of time';
                 $reply= 'Reply';
                 $reComment= 're-Comment';
            }elseif ($lang == 'it') {
                 $delete = 'Cancella';
                 $minutes = ' Minuti fa';
                 $onehourago = ' 1 Ora fa';
                 $overonehour = ' Oltre 1 ora fa';
                 $hours = ' Ore fa';
                 $days = ' Giorni';
                 $alotoftime= ' Molto tempo fa';
                 $reply= 'Reply';
                 $reComment= 'Replica';
            }

            while ($r = mysqli_fetch_array($q)) {
                $x = mktime(date("H"), date("i"), date("s"), date("n"), date("j"), date("Y"));
                $y = $x - $r['datetime'];
                $y = ceil($y / 60);
                if ($y < 60) {
                    $z = $y . $minutes;
                } elseif ($y > 60 AND $y < 90) {
                    $z = $onehourago;
                } elseif ($y > 90 AND $y < 120) {
                    $z = $overonehour;
                } elseif ($y > 150 AND $y < 1440) {
                    $z = ceil($y / 60) . $hours;
                } elseif ($y >= 1440) {
                    $z = ceil($y / 24 / 60) . $days;
                } else {
                    $z = $alotoftime;
                }       

                $recomm_admin = "";
                
                if (isfeedbackadmin($uid)) {
                    $recomm_admin = '<a class="badge text-bg-danger btn-sm float-end" href="index.php?site=news_comments&amp;recom=rem&rid=' . $r['recoID'] . '"> ' . $delete . '</a>';
                }

                $output .= '<section class="border-top">
                <div class="card-body row">
                    <div class="col-md-1"></div>
                    <div class="col-md-1 text-center" style="background-color: rgba(0,0,0,0.12);"><i class="bi bi-chat-left-quote" style="font-size: 2rem;">'.$reply.'</i></div>
                     <div class="col-md-10"><span style="font-size: 16px;font-weight: 900;">'.$reComment.':</span> 
                        ' . $r['comment'] . '<hr>
                        <img style="height:25px;width:25px" class="img-fluid rounded-circle" src="./images/avatars/' . getavatar($r['user_id']) . '"> by ' . getnickname($r['user_id']) . '
                                            | <i class="bi bi-calendar"></i> ' . $z . ' ' . $recomm_admin . '                
                    </div>
                </div>                
                </section><br>';
            }
            return $output;
        } Catch (Exception $e) {
            if (DEBUG === "ON") {
                echo $e->message();
            }
        }
    }
}