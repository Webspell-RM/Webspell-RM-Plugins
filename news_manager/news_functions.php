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

function getanznewscomments($id, $type)
{
    return mysqli_num_rows(
        safe_query(
            "SELECT commentID FROM `" . PREFIX . "plugins_news_comments` WHERE `parentID` = " . (int)$id . " AND type='$type'"
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
                `" . PREFIX . "plugins_news_comments`
            WHERE
                `parentID` = " . (int)$id . " AND
                `type` = '$type'
            ORDER BY
                `date` DESC
            LIMIT 0,1"
        )
    ;
    if(mysqli_num_rows($ds ) > '0') {
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
                `" . PREFIX . "plugins_news_comments`
            WHERE
                `parentID` = " . (int)$id . " AND
                `type` = '$type'
            ORDER BY
                `date` DESC
            LIMIT 0,1"
        )
    ;
    if(mysqli_num_rows($ds) > '0') {
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
            "SELECT * FROM `" . PREFIX . "plugins_news_comments_recomment` WHERE `parentID` = " . (int)$id . " AND type='$type'"
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
                `" . PREFIX . "plugins_news_comments_recomment`
            WHERE
                `parentID` = " . (int)$id . " AND
                `type` = '$type'
            ORDER BY
                `datetime` DESC
            LIMIT 0,1"
        )
    ;
    if(mysqli_num_rows($ds ) > '0') {
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
                `" . PREFIX . "plugins_news_comments_recomment`
            WHERE
                `recoID` = " . (int)$id . " AND
                `type` = '$type'
            ORDER BY
                `datetime` DESC
            LIMIT 0,1"
        )
    ;
    if(mysqli_num_rows($ds) > '0') {
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
            "SELECT rubric FROM `" . PREFIX . "plugins_news_rubrics` WHERE `rubricID` = " . (int)$rubricID
        )
    );
    return $ds['rubric'];
}

function getnewsrubricpic($rubricID)
{
    $ds = 
        safe_query(
            "SELECT pic FROM `" . PREFIX . "plugins_news_rubrics` WHERE `rubricID` = " . (int)$rubricID
        )
    ;
    if(mysqli_num_rows($ds ) > '0') {
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