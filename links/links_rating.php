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
\¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*/

# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("gallery", $plugin_path);

if (!$userID) {
    die($plugin_language[ 'no_access' ]);
}

$rating = $_POST[ 'rating' ];
settype($rating, "integer");
if ($rating > 10 || $rating < 0) {
    die($plugin_language[ 'just_rate_between_0_10' ]);
}
$type = $_POST[ 'type' ];
$id = $_POST[ 'id' ];


$table = "links";
$key = "linkID";

if (isset($table) && isset($key)) {
    $getlinks = safe_query(
        "SELECT
            links
        FROM
            " . PREFIX . "user
        WHERE
            userID='" . (int)$userID."'"
    );
    if (mysqli_num_rows($getlinks)) {
        $ga = mysqli_fetch_array($getlinks);
        $go = false;
        if ($ga[ $table ] == "") {
            $array = array();
            $go = true;
        } else {
            $string = $ga[ $table ];
            $array = explode(":", $string);
            if (!in_array($id, $array)) {
                $go = true;
            }
        }
        // Only vote, if isn't voted
        if ($go === true) {
            safe_query(
                "UPDATE
                    " . PREFIX . "plugins_links
                SET
                    votes=votes+1,
                    points=points+" . $rating . "
                WHERE
                linkID = '" . (int)$id."'"
            );
            $ergebnis = safe_query(
                "SELECT votes, points FROM " . PREFIX . "plugins_links WHERE linkID = '" . (int)$id."'"
            );
            $ds = mysqli_fetch_array($ergebnis);
            $rate = round($ds[ 'points' ] / $ds[ 'votes' ]);
            safe_query(
                "UPDATE " . PREFIX . "plugins_links SET rating='" . $rate . "' WHERE linkID='" . (int)$id."'"
            );
            $array[] = $id;
            $string_new = implode(":", $array);
            safe_query("UPDATE ".PREFIX."user SET links='".$string_new."' WHERE userID='".(int)$userID."'");
            
        }
    }

    switch ($table) {
        case "links":
            $table = "links&action=content&linkID=" . $id;
            break;
        
    }

    header("Location: index.php?site=links&action=content&linkID=" . $id."");
} else {
    header("Location: index.php");
}
