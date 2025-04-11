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
function getuserforumtopics($userID)
{
    return (
        mysqli_num_rows(
            safe_query(
                "SELECT `topicID` FROM `" . PREFIX . "plugins_forum_topics` WHERE `userID` = " . (int)$userID
            )
        )
    );
}

function getuserforumposts($userID)
{
    return (
        mysqli_num_rows(
            safe_query(
                "SELECT `postID` FROM `" . PREFIX . "plugins_forum_posts` WHERE `poster` = " . (int)$userID
            )
        )
    );
}

function getboardname($boardID)
{
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT `name` FROM `" . PREFIX . "plugins_forum_boards` WHERE `boardID` = " . (int)$boardID
        )
    );
    return $ds['name'];
}

function getcategoryname($catID)
{
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT `name` FROM `" . PREFIX . "plugins_forum_categories` WHERE `catID` = " . (int)$catID
        )
    );
    return $ds['name'];
}

function gettopicname($topicID)
{
    $ds = mysqli_fetch_array(safe_query("SELECT topic FROM " . PREFIX . "plugins_forum_topics WHERE topicID='$topicID'"));
    return $ds['topic'];
}

function getmoderators($boardID)
{
    $moderatoren = safe_query("SELECT * FROM `" . PREFIX . "plugins_forum_moderators` WHERE `boardID` = " . (int)$boardID);
    $moderators = '';
    $j = 1;
    while ($dm = mysqli_fetch_array($moderatoren)) {
        $username = getnickname($dm['userID']);
        if ($j > 1) {
            $moderators .= ', <a href="index.php?site=profile&amp;id=' . $dm['userID'] . '">' . $username . '</a>';
        } else {
            $moderators = '<a href="index.php?site=profile&amp;id=' . $dm['userID'] . '">' . $username . '</a>';
        }
        $j++;
    }
    return $moderators;
}

function getlastpost($topicID)
{
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT
                `postID`
            FROM
                `" . PREFIX . "plugins_forum_posts`
            WHERE
                `topicID` = " . (int)$topicID . "
            ORDER BY
                `postID` DESC
            LIMIT 0,1"
        )
    );
    return $ds['postID'];
}

function getboardid($topicID)
{
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT `boardID` FROM `" . PREFIX . "plugins_forum_topics` WHERE `topicID` = " . (int)$topicID . " LIMIT 0,1"
        )
    );
    return $ds['boardID'];
}

/*function usergrpexists($fgrID)
{
    return (
        mysqli_num_rows(
            safe_query(
                "SELECT `fgrID` FROM `" . PREFIX . "plugins_forum_groups` WHERE `fgrID` = " . (int)$fgrID
            )
        ) > 0
    );
}*/

function boardexists($boardID)
{
    return (
        mysqli_num_rows(
            safe_query(
                "SELECT `name` FROM `" . PREFIX . "plugins_forum_boards` WHERE `boardID` = " . (int)$boardID
            )
        ) > 0
    );
}
