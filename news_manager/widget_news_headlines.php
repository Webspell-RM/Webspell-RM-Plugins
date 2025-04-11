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
# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager();
$plugin_language = $pm->plugin_language("news", $plugin_path);

// -- COMMENTS INFORMATION -- //
include_once("news_functions.php");

$data_array = array();
$data_array['$headlines-title'] = $plugin_language['headlines-title'];
$template = $GLOBALS["_template"]->loadTemplate("widget_news_headlines", "head", $data_array, $plugin_path);
echo $template;


if (isset($rubricID) && $rubricID) {
    $only = "AND rubric='" . $rubricID . "'";
} else {
    $only = '';
}

$settings = safe_query("SELECT * FROM " . PREFIX . "plugins_news_manager_settings");
$ds = mysqli_fetch_array($settings);


$maxheadlines = $ds['headlines'];
if (empty($maxheadlines)) {
    $maxheadlines = 4;
}

$ergebnis = safe_query(
    "SELECT
        *
    FROM
        " . PREFIX . "plugins_news_manager ORDER BY date DESC 
    LIMIT 0," . $maxheadlines
);
if (mysqli_num_rows($ergebnis)) {
    #echo '<div class="container"><ul class="nav">';
    $n = 1;
    while ($ds = mysqli_fetch_array($ergebnis)) {
        //$date1 = date("M", $ds['date']);
        $date2 = date("j", $ds['date']);
        #$date = getformatdate($ds[ 'date' ]);

        $monate = array(
            1 => $plugin_language['jan'],
            2 => $plugin_language['feb'],
            3 => $plugin_language['mar'],
            4 => $plugin_language['apr'],
            5 => $plugin_language['may'],
            6 => $plugin_language['jun'],
            7 => $plugin_language['jul'],
            8 => $plugin_language['aug'],
            9 => $plugin_language['sep'],
            10 => $plugin_language['oct'],
            11 => $plugin_language['nov'],
            12 => $plugin_language['dec']
        );

        $monat = date('n', $ds['date']);


        $time = getformattime($ds['date']);
        $news_id = $ds['newsID'];


        $message_array = array();
        $query =
            safe_query(
                "SELECT
                    *
                FROM
                    " . PREFIX . "plugins_news_manager"
            );
        while ($qs = mysqli_fetch_array($query)) {
            $message_array[] = array(
                'headline' => $qs['headline'],
                'message' => $qs['content'],
            );
        }

        $headline = $ds['headline'];
        $headline = $headline;

        $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_news_manager_settings");
        $dx = mysqli_fetch_array($settings);


        if (empty($maxheadlinechars)) {
            $maxheadlinechars = $dx['headlineschars'];
        }

        if (mb_strlen($headline) > $maxheadlinechars) {
            $headline = mb_substr($headline, 0, $maxheadlinechars);
            $headline .= '...';
        }



        $rubrikname = getnewsrubricname($ds['rubric']);
        $rubrikname_link = getinput($rubrikname);
        $rubricpic_name = getnewsrubricpic($ds['rubric']);
        $rubricpic = $plugin_path . '/images/news-rubrics/' . $rubricpic_name;

        if (!file_exists($rubricpic) || $rubricpic_name == '') {
            $rubricpic = '';
        } else {
            $rubricpic = '<img src="' . $rubricpic . '" alt="" class="img-fluid">';
        }

        $line = '<a class="badge bg-primary" href="index.php?site=news_manager&action=news_contents&newsID=' . $ds['newsID'] . '" >READMORE</a>';

        $data_array = array();
        #$data_array['$date'] = $date;
        $data_array['$time'] = $time;
        $data_array['$news_id'] = $news_id;
        $data_array['$line'] = $line;
        $data_array['$headline'] = $headline;
        $data_array['$rubricpic'] = $rubricpic;
        //$data_array['$date1'] = $date1;
        $data_array['$date1'] = $monate[$monat];
        $data_array['$date2'] = $date2;

        $template = $GLOBALS["_template"]->loadTemplate("widget_news_headlines", "content", $data_array, $plugin_path);
        echo $template;
        $n++;
    }
    #echo '</ul></div>';
    unset($rubricID);
}
$data_array = array();
$template = $GLOBALS["_template"]->loadTemplate("widget_news_headlines", "foot", $data_array, $plugin_path);
echo $template;
