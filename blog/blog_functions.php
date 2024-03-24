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

function getanzblogcomments($id, $type)
{
    return mysqli_num_rows(
        safe_query(
            "SELECT commentID FROM `" . PREFIX . "plugins_blog_comments` WHERE `parentID` = " . (int)$id . " AND type='$type'"
        )
    );
}

function getlastblogcommentposter($id, $type)
{
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT
                `userID`,
                `nickname`
            FROM
                `" . PREFIX . "plugins_blog_comments`
            WHERE
                `parentID` = " . (int)$id . " AND
                `type` = '$type'
            ORDER BY
                `date` DESC
            LIMIT 0,1"
        )
    );
    if ($ds['userID']) {
        return getnickname($ds['userID']);
    }

    return htmlspecialchars($ds['nickname']);
}

function getlastblogcommentdate($id, $type)
{
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT
                `date`
            FROM
                `" . PREFIX . "plugins_blog_comments`
            WHERE
                `parentID` = " . (int)$id . " AND
                `type` = '$type'
            ORDER BY
                `date` DESC
            LIMIT 0,1"
        )
    );
    return $ds['date'];
}

