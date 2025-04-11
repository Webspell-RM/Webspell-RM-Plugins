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
function resizeImages($html)
{
    $doc = new DOMDocument();
    libxml_use_internal_errors(true); // Evita warning per HTML non valido

    // Carica il contenuto HTML senza trasformare i caratteri speciali in entit� HTML
    $doc->loadHTML('<?xml encoding="utf-8" ?><meta charset="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    $images = $doc->getElementsByTagName('img');
    foreach ($images as $img) {
        $img->removeAttribute('width');
        $img->removeAttribute('height');
        $img->setAttribute('style', 'max-width:100%; height:auto;');
    }

    return $doc->saveHTML();
}


function closeOpenTags($html)
{
    $doc = new DOMDocument();
    libxml_use_internal_errors(true); // Evita warning per HTML non valido
    $doc->loadHTML('<?xml encoding="utf-8" ?><meta charset="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();
    return preg_replace('/^<!DOCTYPE.+?>/', '', $doc->saveHTML()); // Rimuove il DOCTYPE aggiunto automaticamente
}


function getanznewscomments($id, $type)
{
    return mysqli_num_rows(
        safe_query(
            "SELECT commentID FROM `" . PREFIX . "plugins_news_manager_comments` WHERE `parentID` = " . (int)$id . " AND type='$type'"
        )
    );
}

function getlastnewscommentposter($id, $type)
{
    $ds =
        safe_query(
            "SELECT
                `userID`,
                `nickname`
            FROM
                `" . PREFIX . "plugins_news_manager_comments`
            WHERE
                `parentID` = " . (int)$id . " AND
                `type` = '$type'
            ORDER BY
                `date` DESC
            LIMIT 0,1"
        );
    if (mysqli_num_rows($ds) > '0') {
        $ds = mysqli_fetch_array($ds);
        if ($ds['userID']) {
            return getnickname($ds['userID']);
        }
        return htmlspecialchars($ds['nickname']);
    }
    return '';
}

function getlastnewscommentdate($id, $type)
{
    $ds =
        safe_query(
            "SELECT
                `date`
            FROM
                `" . PREFIX . "plugins_news_manager_comments`
            WHERE
                `parentID` = " . (int)$id . " AND
                `type` = '$type'
            ORDER BY
                `date` DESC
            LIMIT 0,1"
        );
    if (mysqli_num_rows($ds) > '0') {
        $ds = mysqli_fetch_array($ds);
        return $ds['date'];
    }
    return '0';
}

#----------------------
function getanznewsrecomments($id, $type)
{
    return mysqli_num_rows(
        safe_query(
            "SELECT * FROM `" . PREFIX . "plugins_news_manager_comments_recomment` WHERE `parentID` = " . (int)$id . " AND type='$type'"
        )
    );
}

function getlastnewsrecommentposter($id, $type)
{
    $ds =
        safe_query(
            "SELECT
                `user_ID`
            FROM
                `" . PREFIX . "plugins_news_manager_comments_recomment`
            WHERE
                `parentID` = " . (int)$id . " AND
                `type` = '$type'
            ORDER BY
                `datetime` DESC
            LIMIT 0,1"
        );
    if (mysqli_num_rows($ds) > '0') {
        $ds = mysqli_fetch_array($ds);
        if ($ds['user_ID']) {
            return getnickname($ds['user_ID']);
        }
        #return htmlspecialchars($ds['nickname']);
    }
    return '';
}

function getlastnewsrecommentdate($id, $type)
{
    $ds =
        safe_query(
            "SELECT
                `datetime`
            FROM
                `" . PREFIX . "plugins_news_manager_comments_recomment`
            WHERE
                `recoID` = " . (int)$id . " AND
                `type` = '$type'
            ORDER BY
                `datetime` DESC
            LIMIT 0,1"
        );
    if (mysqli_num_rows($ds) > '0') {
        $ds = mysqli_fetch_array($ds);
        return $ds['datetime'];
    }
    return '0';
}
#----------------------------------

function getnewsrubricname($rubricID)
{
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT rubric FROM `" . PREFIX . "plugins_news_manager_rubrics` WHERE `rubricID` = " . (int)$rubricID
        )
    );
    return $ds['rubric'];
}

function getnewsrubricpic($rubricID)
{
    $ds =
        safe_query(
            "SELECT pic FROM `" . PREFIX . "plugins_news_manager_rubrics` WHERE `rubricID` = " . (int)$rubricID
        );
    if (mysqli_num_rows($ds) > '0') {
        $ds = mysqli_fetch_array($ds);
        return $ds['pic'];
    } else {
        return '';
    }
}

function isnewscommentposter($userID, $commID)
{
    if (empty($userID) || empty($commID)) {
        return false;
    }

    return (
        mysqli_num_rows(
            safe_query(
                "SELECT
                    commentID
                FROM
                    " . PREFIX . "plugins_videos_comments
                WHERE
                    `commentID` = " . (int)$commID . " AND
                    `userID` = " . (int)$userID
            )
        ) > 0
    );
}
