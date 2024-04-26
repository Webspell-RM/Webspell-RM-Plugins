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
$plugin_language = $pm->plugin_language("admin_forum", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='forum'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}


if (isset($_POST[ 'savemods' ])) {
    $boardID = $_POST[ 'boardID' ];
    if (isset($_POST[ 'mods' ])) {
        $mods = $_POST[ 'mods' ];
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
            safe_query("DELETE FROM " . PREFIX . "plugins_forum_moderators WHERE boardID='$boardID'");
            if (is_array($mods)) {
                foreach ($mods as $id) {
                    safe_query(
                        "INSERT INTO
                            `" . PREFIX . "plugins_forum_moderators` (
                                `boardID`,
                                `userID`
                            )
                            values (
                                '$boardID',
                                '$id'
                            ) "
                    );
                }
            }
        } else {
            echo $plugin_language[ 'transaction_invalid' ];
        }
    } else {
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
            safe_query(
                "DELETE FROM
                    `" . PREFIX . "plugins_forum_moderators`
                WHERE
                    `boardID` = '" .$boardID . "'"
            );
        } else {
            echo $plugin_language[ 'transaction_invalid' ];
        }
    }
} elseif (isset($_GET[ 'delete' ])) {
    $boardID = $_GET[ 'boardID' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        safe_query(
            "DELETE FROM
                `" . PREFIX . "plugins_forum_posts`
            WHERE
                `boardID` = '" . $boardID . "'"
        );
        safe_query(
            "DELETE
                `topics`.*,
                `moved`.*
            FROM
                `" . PREFIX . "plugins_forum_topics` AS `topics`
            LEFT JOIN
                `" . PREFIX . "plugins_forum_topics` AS `moved`
                ON (`topics`.`topicID` = `moved`.`moveID`)
            WHERE
                `topics`.`boardID` = '" . $boardID . "'"
        );
        safe_query(
            "DELETE FROM
                `" . PREFIX . "plugins_forum_boards`
            WHERE
                `boardID` = '" . $boardID . "' "
        );
        safe_query(
            "DELETE FROM
                `" . PREFIX . "plugins_forum_moderators`
            WHERE
                `boardID` = '" . $boardID . "' "
        );
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_GET[ 'delcat' ])) {
    $catID = $_GET[ 'catID' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE
                `" . PREFIX . "plugins_forum_boards`
            SET
                `category` = '0'
            WHERE
                `category` = '" . $catID . "'"
        );
        safe_query(
            "DELETE FROM
                `" . PREFIX . "plugins_forum_categories`
            WHERE
                `catID` = '" . (int)$catID . "'"
        );

    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }

} elseif (isset($_GET[ 'user_forum_rights_delete' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $id=$_GET['groups'];

        safe_query(
            "UPDATE
                `" . PREFIX . "plugins_forum_user_forum_groups`
            SET
                `$id` = '0'
            WHERE
                `userID` = '" . $_GET[ 'userID' ] . "'"
        );

       redirect("admincenter.php?site=admin_forum&action=admin_forum_group-users&amp;jump=show&amp;users=".$_GET['users']."&amp;groups=".$_GET['groups']."", "", 0);
    } else {
        redirect("admincenter.php?site=admin_squads", $plugin_language[ 'transaction_invalid' ], 3);
    }

} elseif (isset($_GET[ 'user_forum_rights_add' ])) {
$CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
$id=$_GET['groups'];

        safe_query(
            "UPDATE
                `" . PREFIX . "plugins_forum_user_forum_groups`
            SET
                `$id` = '1'
            WHERE
                `userID` = '" . $_GET[ 'userID' ] . "'"
        );

       redirect("admincenter.php?site=admin_forum&action=admin_forum_group-users&amp;jump=show&amp;users=".$_GET['users']."&amp;groups=".$_GET['groups']."", "", 0);
} else {
        redirect("admincenter.php?site=admin_squads", $plugin_language[ 'transaction_invalid' ], 3);
    }



} elseif (isset($_POST[ 'sortieren' ])) {
    $sortcat = $_POST[ 'sortcat' ];
    $sortboards = $_POST[ 'sortboards' ];
    if (isset($_POST[ "hideboards" ])) {
        $hideboards = $_POST[ 'hideboards' ];
    } else {
        $hideboards = "";
    }

    if (is_array($sortcat)) {
        foreach ($sortcat as $sortstring) {
            $sorter = explode("-", $sortstring);
            safe_query("UPDATE " . PREFIX . "plugins_forum_categories SET sort='$sorter[1]' WHERE catID='$sorter[0]' ");
        }
    }
    if (is_array($sortboards)) {
        foreach ($sortboards as $sortstring) {
            $sorter = explode("-", $sortstring);
            safe_query("UPDATE " . PREFIX . "plugins_forum_boards SET sort='$sorter[1]' WHERE boardID='$sorter[0]' ");
        }
    }
} elseif (isset($_POST[ 'save' ])) {
    $kath = $_POST[ 'kath' ];
    $name = $_POST[ 'name' ];
    $boardinfo = $_POST[ 'boardinfo' ];
    if (isset($_POST[ 'readgrps' ])) {
        $readgrps = implode(";", $_POST[ 'readgrps' ]);
    } else {
        $readgrps = '';
    }
    if (isset($_POST[ 'writegrps' ])) {
        $writegrps = implode(";", $_POST[ 'writegrps' ]);
    } else {
        $writegrps = '';
    }

    if ($kath == "") {
        $kath = 0;
    }
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "INSERT INTO
                `" . PREFIX . "plugins_forum_boards` (
                    `category`,
                    `name`,
                    `info`,
                    `readgrps`,
                    `writegrps`,
                    `sort`
                )
                values(
                    '$kath',
                    '$name',
                    '$boardinfo',
                    '$readgrps',
                    '$writegrps',
                    '1'
                )"
        );
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'savecat' ])) {
    $catname = $_POST[ 'catname' ];
    $catinfo = $_POST[ 'catinfo' ];
    if (isset($_POST[ 'readgrps' ])) {
        $readgrps = implode(";", $_POST[ 'readgrps' ]);
    } else {
        $readgrps = '';
    }
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "INSERT INTO
                `" . PREFIX . "plugins_forum_categories` (
                    `readgrps`,
                    `name`,
                    `info`,
                    `sort`
                )
                VALUES (
                    '" . $readgrps . "',
                    '" .$catname . "',
                    '" . $catinfo . "',
                    '1')"
        );
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'saveedit' ])) {
    $kath = $_POST[ 'kath' ];
    $name = $_POST[ 'name' ];
    $boardinfo = $_POST[ 'boardinfo' ];
    $boardID = $_POST[ 'boardID' ];
    if (isset($_POST[ 'readgrps' ])) {
        $readgrps = implode(";", $_POST[ 'readgrps' ]);
    } else {
        $readgrps = '';
    }
    if (isset($_POST[ 'writegrps' ])) {
        $writegrps = implode(";", $_POST[ 'writegrps' ]);
    } else {
        $writegrps = '';
    }

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE
                " . PREFIX . "plugins_forum_boards
            SET
                category='$kath',
                name='$name',
                info='$boardinfo',
                readgrps='$readgrps',
                writegrps='$writegrps'
            WHERE
                boardID='$boardID'"
        );
        safe_query(
            "UPDATE
                `" . PREFIX . "plugins_forum_topics`
            SET
                `readgrps` = '" . $readgrps . "',
                `writegrps` = '" . $writegrps . "'
            WHERE
                `boardID` = '" . $boardID . "'"
        );
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'saveeditcat' ])) {
    $catname = $_POST[ 'catname' ];
    $catinfo = $_POST[ 'catinfo' ];
    $catID = $_POST[ 'catID' ];
    if (isset($_POST[ 'readgrps' ])) {
        $readgrps = implode(";", $_POST[ 'readgrps' ]);
    } else {
        $readgrps = '';
    }
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE
                " . PREFIX . "plugins_forum_categories
            SET
                `readgrps` = '" . $readgrps . "',
                `name` = '" . $catname . "',
                `info` = '" . $catinfo . "'
            WHERE
                `catID` = '" . $catID . "'"
        );
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
}

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if ($action == "mods") {
    echo '<div class="card">
            <div class="card-header">
                '.$plugin_language['boards'].'
            </div>
            <div class="card-body">
    <a href="admincenter.php?site=admin_forum" class="white">' . $plugin_language[ 'boards' ] .
        '</a> &raquo; ' . $plugin_language[ 'moderators' ] . '<br><br>';

    $boardID = $_GET[ 'boardID' ];

    $moderators = safe_query("SELECT * FROM `" . PREFIX . "user_groups` WHERE `moderator` = '1'");
    $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_forum_boards` WHERE `boardID` = '" . $boardID . "'");
    $ds = mysqli_fetch_array($ergebnis);

    echo $plugin_language[ 'choose_moderators' ] . ' <span class="text-muted small"><em>' . $ds[ 'name' ] . '</em></span><br><br>';

    echo '<form method="post" action="admincenter.php?site=admin_forum">
  <span class="text-muted small"><em><select class="form-control" name="mods[]" multiple="multiple" size="10">';

    while ($dm = mysqli_fetch_array($moderators)) {
        $nick = getnickname($dm[ 'userID' ]);
        $ismod = mysqli_num_rows(
            safe_query(
                "SELECT
                    *
                FROM
                    `" . PREFIX . "plugins_forum_moderators`
                WHERE
                    `boardID` = '" . $boardID . "'
                AND
                    `userID` = '" . $dm[ 'userID' ] . "'"
            )
        );
        if ($ismod) {
            echo '<option value="' . $dm[ 'userID' ] . '" selected="selected">' . $nick . '</option>';
        } else {
            echo '<option value="' . $dm[ 'userID' ] . '">' . $nick . '</option>';
        }
    }
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo '</select></em></span><br /><br />
    <input type="hidden" name="captcha_hash" value="' . $hash . '" />
    <input type="hidden" name="boardID" value="' . $boardID . '" />
    <input class="btn btn-success" type="submit" name="savemods" value="' . $plugin_language[ 'select_moderators' ] . '" />
    </form>
    </div>
  </div>';
} elseif ($action == "add") {
    echo '<div class="card">
            <div class="card-header">
                '.$plugin_language['boards'].'
            </div>
            <div class="card-body">
    <a href="admincenter.php?site=admin_forum" class="white">' . $plugin_language[ 'boards' ] .
        '</a> &raquo; ' . $plugin_language[ 'add_board' ] . '<br><br>';

    $ergebnis = safe_query(
        "SELECT * FROM `" . PREFIX . "plugins_forum_categories` ORDER BY `sort`"
    );
    $cats = '<select class="form-control" name="kath">';
    while ($ds = mysqli_fetch_array($ergebnis)) {
        $cats .= '<option value="' . $ds[ 'catID' ] . '">' . getinput($ds[ 'name' ]) . '</option>';
    }
    $cats .= '</select>';

    $sql = safe_query("SELECT * FROM `" . PREFIX . "plugins_forum_groups`");
    $groups = '';
    while ($db = mysqli_fetch_array($sql)) {
        $groups .= '<option value="' . $db[ 'fgrID' ] . '">' . getinput($db[ 'name' ]) . '</option>';
    }
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    echo '<script>
    <!--
    function unselect_all(select_id) {
        select_element = document.getElementById(select_id);
        for(var i = 0; i < select_element.length; i++) {
            select_element.options[i].selected = false;
        }
    }
    -->
    </script>';
    
  echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_forum">
   <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      '.$cats.'</em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['boardname'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="name" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['boardinfo'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="boardinfo" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['read_right'].':<br><span class="text-muted small"><em><a href="javascript:unselect_all(\'readgrps\');">'.$plugin_language['unselect_all'].'</a><br /><br />
      '.$plugin_language['read_right_info_board'].'</em></span></label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <select class="form-control" id="readgrps" name="readgrps[]" multiple="multiple" size="10">
        <option value="user">'.$plugin_language['registered_users'].'</option>
        '.$groups.'
      </select></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['write_right'].':<br><span class="text-muted small"><em><a href="javascript:unselect_all(\'writegrps\');">'.$plugin_language['unselect_all'].'</a><br /><br />
      '.$plugin_language['write_right_info_board'].'</em></span></label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <select class="form-control" id="writegrps" name="writegrps[]" multiple="multiple" size="10">
        <option value="user" selected="selected">'.$plugin_language['registered_users'].'</option>
        '.$groups.'
      </select></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-success" type="submit" name="save" />'.$plugin_language['add_board'].'</button>
    </div>
  </div>
  </form>
  </div>
  </div>';
} elseif ($action == "edit") {
    echo '<div class="card">
            <div class="card-header">
                '.$plugin_language['boards'].'
            </div>
            <div class="card-body">
    <a href="admincenter.php?site=admin_forum" class="white">' . $plugin_language[ 'boards' ] .
        '</a> &raquo; ' . $plugin_language[ 'edit_board' ] . '</h4';

    $boardID = $_GET[ 'boardID' ];

    $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_forum_boards` WHERE `boardID` = '$boardID'");
    $ds = mysqli_fetch_array($ergebnis);

    $category = safe_query("SELECT * FROM `" . PREFIX . "plugins_forum_categories` ORDER BY `sort`");
    $cats = '<select class="form-control" name="kath">';
    while ($dc = mysqli_fetch_array($category)) {
        if ($ds[ 'category' ] == $dc[ 'catID' ]) {
            $selected = " selected=\"selected\"";
        } else {
            $selected = "";
        }
        $cats .= '<option value="' . $dc[ 'catID' ] . '"' . $selected . '>' . getinput($dc[ 'name' ]) . '</option>';
    }
    $cats .= '</select>';

    $groups = array();
    $sql = safe_query("SELECT * FROM `" . PREFIX . "plugins_forum_groups`");
    while ($db = mysqli_fetch_array($sql)) {
        $groups[ $db[ 'fgrID' ] ] = $db[ 'name' ];
    }

    $readgrps = '';
    $writegrps = '';

    $grps = explode(";", $ds[ 'readgrps' ]);
    if (in_array('user', $grps)) {
        $readgrps .= '<option value="user" selected="selected">' . $plugin_language[ 'registered_users' ] .
            '</option>';
    } else {
        $readgrps .= '<option value="user">' . $plugin_language[ 'registered_users' ] . '</option>';
    }
    foreach ($groups as $fgrID => $name) {
        if (in_array($fgrID, $grps)) {
            $selected = ' selected="selected"';
        } else {
            $selected = '';
        }
        $readgrps .= '<option value="' . $fgrID . '"' . $selected . '>' . getinput($name) . '</option>';
    }

    $grps = explode(";", $ds[ 'writegrps' ]);
    if (in_array('user', $grps)) {
        $writegrps .= '<option value="user" selected="selected">' . $plugin_language[ 'registered_users' ] .
            '</option>';
    } else {
        $writegrps .= '<option value="user">' . $plugin_language[ 'registered_users' ] . '</option>';
    }
    foreach ($groups as $fgrID => $name) {
        if (in_array($fgrID, $grps)) {
            $selected = ' selected="selected"';
        } else {
            $selected = '';
        }
        $writegrps .= '<option value="' . $fgrID . '"' . $selected . '>' . getinput($name) . '</option>';
    }
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo '<script>
    <!--
    function unselect_all(select_id) {
        select_element = document.getElementById(select_id);
        for(var i = 0; i < select_element.length; i++) {
            select_element.options[i].selected = false;
        }
    }
    -->
    </script>';
    
  echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_forum">
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      '.$cats.'</em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['boardname'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="name" value="'.getinput($ds['name']).'" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['boardinfo'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="boardinfo" value="'.getinput($ds['info']).'" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['read_right'].':<br><span class="text-muted small"><em><a href="javascript:unselect_all(\'readgrps\');">'.$plugin_language['unselect_all'].'</a><br /><br />
      '.$plugin_language['read_right_info_board'].'</em></span></label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <select class="form-control" id="readgrps" name="readgrps[]" multiple="multiple" size="10">'.$readgrps.'</select></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['write_right'].':<br><span class="text-muted small"><em><a href="javascript:unselect_all(\'writegrps\');">'.$plugin_language['unselect_all'].'</a><br /><br />
      '.$plugin_language['write_right_info_board'].'</em></span></label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <select class="form-control" id="writegrps" name="writegrps[]" multiple="multiple" size="10">'.$writegrps.'</select></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="boardID" value="'.$boardID.'" />
        <button class="btn btn-warning" type="submit" name="saveedit" />'.$plugin_language['edit_board'].'</button>
    </div>
  </div>
  </form>
  </div>
  </div>';
} elseif ($action == "addcat") {
    echo '<div class="card">
            <div class="card-header">
                '.$plugin_language['boards'].'
            </div>
            <div class="card-body">
    <a href="admincenter.php?site=admin_forum" class="white">' . $plugin_language[ 'boards' ] .
        '</a> &raquo; ' . $plugin_language[ 'add_category' ] . '<br><br>';

    $sql = safe_query("SELECT * FROM `" . PREFIX . "plugins_forum_groups`");
    $groups = '<select class="form-control" id="readgrps" name="readgrps[]" multiple="multiple" size="10">
  <option value="user">' . $plugin_language[ 'registered_users' ] . '</option>';
    while ($db = mysqli_fetch_array($sql)) {
        $groups .= '<option value="' . $db[ 'fgrID' ] . '">' . getinput($db[ 'name' ]) . '</option>';
    }
    $groups .= '</select>';
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    echo '<script>
    <!--
    function unselect_all(select_id) {
        select_element = document.getElementById(select_id);
        for(var i = 0; i < select_element.length; i++) {
            select_element.options[i].selected = false;
        }
    }
    -->
    </script>';
    
  echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_forum" enctype="multipart/form-data">
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="catname" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category_info'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="catinfo" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['read_right'].':<br><span class="text-muted small"><em><a href="javascript:unselect_all(\'readgrps\');">'.$plugin_language['unselect_all'].'</a><br /><br />
      '.$plugin_language['right_info_category'].'</em></span></label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      '.$groups.'</em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-success" type="submit" name="savecat" />'.$plugin_language['add_category'].'</button>
    </div>
  </div>
  </form>
  </div>
  </div>';
} elseif ($action == "editcat") {
    echo '<div class="card">
            <div class="card-header">
                '.$plugin_language['boards'].'
            </div>
            <div class="card-body">
    <a href="admincenter.php?site=admin_forum" class="white">' . $plugin_language[ 'boards' ] .
        '</a> &raquo; ' . $plugin_language[ 'edit_category' ] . '<br><br>';

    $catID = $_GET[ 'catID' ];

    $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_forum_categories` WHERE `catID` = '$catID'");
    $ds = mysqli_fetch_array($ergebnis);

    $usergrps = explode(";", $ds[ 'readgrps' ]);
    $sql = safe_query("SELECT * FROM `" . PREFIX . "plugins_forum_groups`");
    $groups = '<select class="form-control" id="readgrps" name="readgrps[]" multiple="multiple" size="10">';
    if (in_array('user', $usergrps)) {
        $groups .= '<option value="user" selected="selected">' . $plugin_language[ 'registered_users' ] . '</option>';
    } else {
        $groups .= '<option value="user">' . $plugin_language[ 'registered_users' ] . '</option>';
    }
    while ($db = mysqli_fetch_array($sql)) {
        if (in_array($db[ 'fgrID' ], $usergrps)) {
            $selected = ' selected="selected"';
        } else {
            $selected = '';
        }
        $groups .= '<option value="' . $db[ 'fgrID' ] . '" ' . $selected . '>' . getinput($db[ 'name' ]) . '</option>';
    }
    $groups .= '</select>';
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    echo '<script>
    <!--
    function unselect_all(select_id) {
        select_element = document.getElementById(select_id);
        for(var i = 0; i < select_element.length; i++) {
            select_element.options[i].selected = false;
        }
    }
    -->
    </script>';
    
  echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_forum">
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="catname" value="'.getinput($ds['name']).'" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['category_info'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="catinfo" value="'.getinput($ds['info']).'" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['read_right'].':<br><span class="text-muted small"><em><a href="javascript:unselect_all(\'readgrps\');">'.$plugin_language['unselect_all'].'</a><br /><br />
      '.$plugin_language['right_info_category'].'</em></span></label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      '.$groups.'</em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="catID" value="'.$catID.'" />
        <button class="btn btn-warning" type="submit" name="saveeditcat" />'.$plugin_language['edit_category'].'</button>
    </div>
  </div>
  </form>
  </div>
  </div>';


} elseif ($action == "") {

    echo'<div class="card">
            <div class="card-header">
                '.$plugin_language['boards'].'
            </div>
            <div class="card-body">';
    echo'
    <a href="admincenter.php?site=admin_forum" class="btn btn-primary disabled" type="button">' . $plugin_language[ 'boards' ] . '</a>
    <a href="admincenter.php?site=admin_forum&action=admin_forum_groups" class="btn btn-primary" type="button">' . $plugin_language[ 'groups' ] . '</a>
    <a href="admincenter.php?site=admin_forum&action=admin_forum_group-users" class="btn btn-primary" type="button">' . $plugin_language[ 'group_users' ] . '</a>
    <a href="admincenter.php?site=admin_forum&action=admin_forum_ranks" class="btn btn-primary" type="button">' . $plugin_language[ 'user_ranks' ] . '</a><br /><br />'; 

    echo'
    <a href="admincenter.php?site=admin_forum&amp;action=addcat" class="btn btn-primary" type="button">' . $plugin_language[ 'new_category' ] . '</a>
    <a href="admincenter.php?site=admin_forum&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_board' ] . '</a><br /><br />'; 

    echo'<form method="post" action="admincenter.php?site=admin_forum">
  <table class="table">
    <thead>
      <th><b>'.$plugin_language['boardname'].'</b></th>
      <th><b>'.$plugin_language['mods'].'</b></th>
      <th><b>'.$plugin_language['actions'].'</b></th>
      <th><b>'.$plugin_language['sort'].'</b></th>
    </thead>';

    $ergebnis = safe_query("SELECT * FROM `" . PREFIX . "plugins_forum_categories` ORDER BY `sort`");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(catID) as cnt FROM `" . PREFIX . "plugins_forum_categories`"));
    $anz = $tmp[ 'cnt' ];

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    while ($ds = mysqli_fetch_array($ergebnis)) {
        echo '<tr class="table-secondary">
          <td><b>'.getinput($ds['name']).'</b><br /><small>'.getinput($ds['info']).'</small></td>
          <td></td>
          <td>

          <a href="admincenter.php?site=admin_forum&amp;action=editcat&amp;catID='.$ds['catID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>
        
        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_forum&amp;delcat=true&amp;catID='.$ds['catID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'boards' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete_category'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->

      </td>
          <td><select name="sortcat[]">';
      
        for ($n = 1; $n <= $anz; $n++) {
            if ($ds[ 'sort' ] == $n) {
                echo '<option value="' . $ds[ 'catID' ] . '-' . $n . '" selected="selected">' . $n . '</option>';
            } else {
                echo '<option value="' . $ds[ 'catID' ] . '-' . $n . '">' . $n . '</option>';
            }
        }

        echo '</select></td>
        </tr>';

        $boards = safe_query(
            "SELECT * FROM `" . PREFIX . "plugins_forum_boards` WHERE `category` = '" . $ds[ 'catID' ] . "' ORDER BY `sort`"
        );
        $tmp = mysqli_fetch_assoc(
            safe_query(
                "SELECT count(boardID) as cnt FROM `" . PREFIX . "plugins_forum_boards` WHERE `category` = '$ds[catID]'"
            )
        );
        $anzboards = $tmp[ 'cnt' ];

        $i = 1;
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();
        while ($db = mysqli_fetch_array($boards)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }

            echo '<tr>
            <td class="'.$td.'">'.$db['name'].'<br /><small>'.$db['info'].'</small></td>
            <td class="'.$td.'">

<a href="admincenter.php?site=admin_forum&amp;action=mods&amp;boardID='.$db['boardID'].'" class="btn btn-primary" type="button">' . $plugin_language[ 'mods' ] . '</a>

            </td>
            <td class="'.$td.'">

            <a href="admincenter.php?site=admin_forum&amp;action=edit&amp;boardID='.$db['boardID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_forum&amp;delete=true&amp;boardID='.$db['boardID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'boards' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete_board'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->
      
      </td>
            <td class="'.$td.'"><select name="sortboards[]">';

            for ($j = 1; $j <= $anzboards; $j++) {
                if ($db[ 'sort' ] == $j) {
                    echo '<option value="' . $db[ 'boardID' ] . '-' . $j . '" selected="selected">' . $j . '</option>';
                } else {
                    echo '<option value="' . $db[ 'boardID' ] . '-' . $j . '">' . $j . '</option>';
                }
            }

            echo '</select></td>
          </tr>';

            $i++;
        }
    }

    $boards = safe_query("SELECT * FROM `" . PREFIX . "plugins_forum_boards` WHERE `category`='0' ORDER BY `sort`");
    $tmp = mysqli_fetch_assoc(
        safe_query(
            "SELECT count(boardID) as cnt FROM `" . PREFIX . "plugins_forum_boards` WHERE `category` = '0'"
        )
    );
    $anzboards = $tmp[ 'cnt' ];
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    while ($db = mysqli_fetch_array($boards)) {


        echo'<tr bgcolor="#dcdcdc">
      <td bgcolor="#FFFFFF"><b>'.getinput($db['name']).'</b></td>
      <td bgcolor="#FFFFFF">

      
      <a href="admincenter.php?site=admin_forum&amp;action=mods&amp;boardID='.$db['boardID'].'" class="btn btn-primary" type="button">' . $plugin_language[ 'mods' ] . '</a>
      </td>
      <td bgcolor="#FFFFFF">

      <a href="admincenter.php?site=admin_forum&amp;action=edit&amp;boardID='.$db['boardID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_forum&amp;delete=true&amp;boardID='.$db['boardID'].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'boards' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->
      
      </td>
      <td bgcolor="#FFFFFF"><select name="sort[]">';

        for ($n = 1; $n <= $anzboards; $n++) {
            if ($ds[ 'sort' ] == $n) {
                echo '<option value="' . $db[ 'boardID' ] . '-' . $n . '" selected="selected">' . $n . '</option>';
            } else {
                echo '<option value="' . $db[ 'boardID' ] . '-' . $n . '">' . $n . '</option>';
            }
        }
            echo '</select></td></tr>';
    }
    
  echo'<tr>
      <td colspan="5" align="right"><input class="btn btn-primary" type="submit" name="sortieren" value="'.$plugin_language['to_sort'].'" /></td>
    </tr>
  </table>
  </form>';

echo '</div></div>';
}


/*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>admin_forum_groups<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/


/*¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯\
|                      admin_forum_groups                           |
\------------------------------------------------------------------*/


if ($action == "admin_forum_groups_delete") {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        if (!$_GET[ 'fgrID' ]) {
            die('missing fgrID... <a href="admincenter.php?site=admin_forum&amp;action=admin_forum_groups">back</a>');
        }
        safe_query("ALTER TABLE " . PREFIX . "plugins_forum_user_forum_groups DROP `" . $_GET[ 'fgrID' ] . "`");
        safe_query("DELETE FROM " . PREFIX . "plugins_forum_groups WHERE fgrID='" . $_GET[ 'fgrID' ] . "'");

        redirect("admincenter.php?site=admin_forum&amp;action=admin_forum_groups", "", 0);
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }

} elseif ($action == "admin_forum_groups_save") {
    if (!$_POST[ 'name' ]) {
        die('<b>' . $plugin_language[ 'error_group' ] .
            '</b><br /><br /><a href="admincenter.php?site=admin_forum&amp;action=admin_forum_groups_add">&laquo; ' .
            $plugin_language[ 'back' ] . '</a>');
    }
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query("INSERT INTO " . PREFIX . "plugins_forum_groups ( name ) values( '" . $_POST[ 'name' ] . "' ) ");
        $id = mysqli_insert_id($_database);
        if (!safe_query("ALTER TABLE " . PREFIX . "plugins_forum_user_forum_groups ADD `" . $id . "` INT( 1 ) NOT NULL ; ")) {
            safe_query("ALTER TABLE " . PREFIX . "plugins_forum_user_forum_groups DROP `" . $id . "`");
            safe_query("ALTER TABLE " . PREFIX . "plugins_forum_user_forum_groups ADD `" . $id . "` INT( 1 ) NOT NULL ; ");
        }

        redirect("admincenter.php?site=admin_forum&amp;action=admin_forum_groups", "", 0);
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif ($action == "admin_forum_groups_saveedit") {
    $name = $_POST[ 'name' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE " . PREFIX . "plugins_forum_groups SET name='" . $name . "' WHERE fgrID='" . $_POST[ 'fgrID' ] .
            "'"
        );
        redirect("admincenter.php?site=admin_forum&amp;action=admin_forum_groups", "", 0);
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif ($action == "admin_forum_groups_add") {
    echo '<div class="card">
            <div class="card-header">
                '.$plugin_language['groups'].'
            </div>
            <div class="card-body">
    <a href="admincenter.php?site=admin_forum&amp;action=admin_forum_groups" class="white">' . $plugin_language[ 'groups' ] .
        '</a> &raquo; ' . $plugin_language[ 'add_group' ] . '<br><br>';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
  
  echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_forum&action=admin_forum_groups_save">
   <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['group_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="name" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-8">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-success" type="submit" name="admin_forum_groups_save" />'.$plugin_language['add_group'].'</button>
    </div>
  </div>
  </form>
  </div>
  </div>';
} elseif ($action == "admin_forum_groups_edit") {
    echo '<div class="card">
            <div class="card-header">
                '.$plugin_language['groups'].'
            </div>
            <div class="card-body">
    <a href="admincenter.php?site=admin_forum&action=admin_forum_groups" class="white">' . $plugin_language[ 'groups' ] .
        '</a> &raquo; ' . $plugin_language[ 'edit_group' ] . '<br><br>';

    if (!$_GET[ 'fgrID' ]) {
        die('<b>' . $plugin_language[ 'error_groupid' ] .
            '</b><br /><br /><a href="admincenter.php?site=admin_forum&action=admin_forum_groups">&laquo; ' . $plugin_language[ 'back' ] . '</a>');
    }
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_groups WHERE fgrID='" . $_GET[ 'fgrID' ] . "'");
    $ds = mysqli_fetch_array($ergebnis);

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
  
  echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_forum&action=admin_forum_groups_saveedit">
   <div class="mb-3 row">
    <label class="col-sm-2 control-label">'.$plugin_language['group_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="name" value="'.getinput($ds["name"]).'" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-8">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" /><input name="fgrID" type="hidden" value="'.$ds["fgrID"].'" />
        <button class="btn btn-warning" type="submit" name="admin_forum_groups_saveedit" />'.$plugin_language['edit_group'].'</button>
    </div>
  </div>
  </form>
  </div>
  </div>';

} elseif ($action == "admin_forum_groups") {  
    
  echo'<div class="card">
            <div class="card-header">
                '.$plugin_language['groups'].'
            </div>
            <div class="card-body">';
  echo'
    <a href="admincenter.php?site=admin_forum" class="btn btn-primary" type="button">' . $plugin_language[ 'boards' ] . '</a>
    <a href="admincenter.php?site=admin_forum&action=admin_forum_groups" class="btn btn-primary disabled" type="button">' . $plugin_language[ 'groups' ] . '</a>
    <a href="admincenter.php?site=admin_forum&action=admin_forum_group-users" class="btn btn-primary" type="button">' . $plugin_language[ 'group_users' ] . '</a>
    <a href="admincenter.php?site=admin_forum&action=admin_forum_ranks" class="btn btn-primary" type="button">' . $plugin_language[ 'user_ranks' ] . '</a><br /><br />'; 

    
  echo'<a href="admincenter.php?site=admin_forum&action=admin_forum_groups_add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_group' ] . '</a><br /><br />';

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_groups ORDER BY fgrID");
    
  echo'<table class="table table-striped">
    <thead>
      <th><b>'.$plugin_language['group_name'].'</b></th>
      <th><b>'.$plugin_language['actions'].'</b></th>
    </thead>';
  
  $i = 1;
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    while ($ds = mysqli_fetch_array($ergebnis)) {
        if ($i % 2) {
            $td = 'td1';
        } else {
            $td = 'td2';
        }

        echo '<tr>
      <td><b>'.getinput($ds['name']).'</b></td>
      <td><a href="admincenter.php?site=admin_forum&action=admin_forum_groups_edit&amp;fgrID='.$ds["fgrID"].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_forum&amp;action=admin_forum_groups_delete&amp;fgrID='.$ds["fgrID"].'&amp;captcha_hash='.$hash.'">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'groups' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete_groups'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->

    </td>
        </tr>';
      
      $i++;
    }

    echo'</table></div></div>';

}

/*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>admin_forum_group-users<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/


/*¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯\
|                      admin_forum_group-users                      |
\------------------------------------------------------------------*/

if ($action == "admin_forum_group-users") {

if (isset($_GET[ 'jump' ])) {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    if ($_GET[ 'jump' ] == 'show') {
            $anz_users_page = 5;
    }
    if (isset($_REQUEST[ 'page' ])) {
        $page = (int)$_REQUEST[ 'page' ];
    } else {
        $page = 1;
    }
    
    if(isset($_GET['users'])) {
        $_POST['users'] = explode("-", $_GET['users']);
    }
    if(!isset($_POST['users'])){
        $_POST['users'] = array();
    }
    if(is_null($_POST['users'])){
        $_POST['users'] = array();
    }

    if(isset($_GET['groups'])) {
        $_POST['groups'] = explode("-", $_GET['groups']);
    }
    if(isset($_GET['addfield'])) {
        $_POST['addfield'] = $_GET['addfield'];
    }
    $users = array();
    if (in_array(0, $_POST[ 'users' ])) {
        $query = safe_query("SELECT userID FROM `" . PREFIX . "plugins_squads_members`");
        while ($ds = mysqli_fetch_array($query)) {
            if (!in_array($ds[ 'userID' ], $users)) {
                $users[ ] = $ds[ 'userID' ];
            }
        }
    }
    if (in_array(1, $_POST[ 'users' ])) {
        $query = safe_query(
            "SELECT userID
            FROM `" . PREFIX . "user_groups`
            WHERE (
                page='1' OR
                forum='1' OR
                user='1' OR
                news='1' OR
                clanwars='1' OR
                feedback='1'
                OR super='1'
                OR gallery='1'
                OR cash='1'
                OR files='1'
            )"
        );
        while ($ds = mysqli_fetch_array($query)) {
            if (!in_array($ds[ 'userID' ], $users)) {
                $users[ ] = $ds[ 'userID' ];
            }
        }
    }
    if (in_array(2, $_POST[ 'users' ])) {
        $query = safe_query("SELECT userID FROM `" . PREFIX . "user_groups` WHERE super='1'");
        while ($ds = mysqli_fetch_array($query)) {
            if (!in_array($ds[ 'userID' ], $users)) {
                $users[ ] = $ds[ 'userID' ];
            }
        }
    }
    if (in_array(3, $_POST[ 'users' ])) {
        $fgrID = mysqli_fetch_array(safe_query(
            "SELECT fgrID FROM `" . PREFIX . "plugins_forum_groups` WHERE name = '" .
            $_POST[ 'addfield' ] . "'"
        ));
        if (!$fgrID[ 'fgrID' ]) {
            echo '<b>' . $plugin_language[ 'error_group' ] .
                '</b><br><br><a href="admincenter.php?site=admin_forum&action=admin_forum_group-users">&laquo; ' .
                $plugin_language[ 'back' ] . '</a>'; return false;
        }
        $query = safe_query(
            "SELECT userID FROM `" . PREFIX . "plugins_forum_user_forum_groups` WHERE `" . $fgrID[ 'fgrID' ] . "` = '1'"
        );
        while ($ds = mysqli_fetch_array($query)) {
            if (!in_array($ds[ 'userID' ], $users)) {
                $users[ ] = $ds[ 'userID' ];
            }
        }
    }
    
    if (in_array(4, $_POST[ 'users' ])) {
        $query = safe_query("SELECT userID FROM `" . PREFIX . "user`");
        while ($ds = mysqli_fetch_array($query)) {
            if (!in_array($ds[ 'userID' ], $users)) {
                $users[ ] = $ds[ 'userID' ];
            }
        }
    }
    $groups = array();
    if (isset($_POST[ 'groups' ])) {
        $grps = $_POST[ 'groups' ];
    } else {
        $grps = array(1);
    }

    $sql = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_groups");
    while ($ds = mysqli_fetch_array($sql)) {
        if (in_array($ds[ 'fgrID' ], $grps)) {
            $groups[ ] = array('fgrID' => $ds[ 'fgrID' ], 'name' => getinput($ds[ 'name' ]));
        }
    }

    $groups_anz = count($groups);
    $anz_users = count($users);

echo'<div class="card">
        <div class="card-header">
            ' . $plugin_language[ 'group_users' ] . '
        </div>
        <div class="card-body">
        <a href="admincenter.php?site=admin_forum&action=admin_forum_group-users" class="white">'.$plugin_language['group_users'].'</a> &raquo; '.$plugin_language['edit_group_users'].'<br><br>';
    for ($i = 0; $i < $groups_anz; $i++) {
        echo'<div class="alert alert-success" role="alert">
            <h4>'.$plugin_language[implode($_POST['users'])].'</h4>
            <h5>' . $plugin_language[ 'forum_name' ] . ': <b>' . $groups[ $i ][ 'name' ] . '</b></h5>
        </div>'; 
    }   

echo '<form method="post" name="form" action="admincenter.php?site=admin_forum&action=admin_forum_group-users&amp;jump=show&amp;users='.implode("-", $_POST['users']).'&amp;groups='.implode("-", $_POST['groups']).'">';
echo'<div class="table-responsive">

<table id="plugini" class="table table-striped table-bordered" style="width:100%">    
        <thead>';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
        
echo'<tr>
        <th>
            <b>' . $plugin_language[ 'group_users' ] . '</b>
        </th><th>
            <b>' . $plugin_language[ 'status' ] . '</b>
        </th>
        <th>
            <b>' . $plugin_language[ 'actions' ] . '</b>
        </th>
        </tr>
        </thead>
        <tbody>';

    $n = 1;
    $skip = $anz_users_page * ($page - 1);
    for ($z = $skip; $z < ($skip + $anz_users_page) && $z < $anz_users; $z++) {
echo'<tr>
        <td>
            ' . strip_tags(stripslashes(getnickname($users[ $z ]))) . '
        </td>';

        for ($i = 0; $i < $groups_anz; $i++) {
     
        if (isinusergrp($groups[ $i ][ 'fgrID' ], $users[ $z ])) {
            $usersID=strip_tags($users[ $z ]);
            $usergrp = '<font class="text-success">' . $plugin_language['on' ] . '</font>';
        } else {
            $usergrp = '<i><font class="text-danger">' . $plugin_language['off' ] . '</i></font>';
        }

    $referer = "admincenter.php?site=admin_forum&action=admin_forum_group-users&amp;jump=show&amp;users=".implode($_POST['users'])."&amp;groups=".implode($_POST['groups'])."";
    
    echo'<td>
            ' . $usergrp . '
        </td>
        <td>
            <a type="button" class="btn btn-success" href="admincenter.php?site=admin_forum&amp;user_forum_rights_add&amp;users='.implode($_POST['users']).'&amp;userID='.$users[ $z ].'&amp;groups='.implode($_POST['groups']).'&amp;captcha_hash=' . $hash . '">'.$plugin_language['add_rights'].'</a>

            <!--<a type="button" class="btn btn-warning" href="admincenter.php?site=user_rights&amp;action=edit&amp;id='.$users[ $z ] . '&amp;ref=' . urlencode($referer) . '" type="button">' . $plugin_language[ 'edit' ] . '</a>-->
            
            <!-- Button trigger modal -->
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_forum&amp;user_forum_rights_delete=true&amp;users='.implode($_POST['users']).'&amp;userID='.$users[ $z ].'&amp;groups='.implode($_POST['groups']).'&amp;captcha_hash=' . $hash . '">
                ' . $plugin_language['delete_rights'] . '
                </button>
                <!-- Button trigger modal END-->

                </td>
                <!-- Modal -->
            <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'user_rights' ] . '</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
                  </div>
                  <div class="modal-body"><p>' . $plugin_language['really_rights_delete'] . '</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
                    <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal END -->';
    
        }
echo '</tr>';
        $n++;

    }
    
    echo '</tbody></table>
      </form>
      </div>
      </div>';

} else {  

    $groups = '';
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_groups");
    $selector = 0;
    while ($ds = mysqli_fetch_array($ergebnis)) {
        if ($selector == 0) {
            $groups .=
                "\t\t" . '<option value="' . $ds[ 'fgrID' ] . '" selected="selected">' . getinput($ds[ 'name' ]) .
                '</option>' . "\n";
        } else {
            $groups .= "\t\t" . '<option value="' . $ds[ 'fgrID' ] . '">' . getinput($ds[ 'name' ]) . '</option>' .
                "\n";
        }
        $selector = 1;
    }

    echo '<div class="card">
            <div class="card-header">
                ' . $plugin_language[ 'group_users' ] . '
            </div>
            <div class="card-body">';
    echo'
    <a href="admincenter.php?site=admin_forum" class="btn btn-primary" type="button">' . $plugin_language[ 'boards' ] . '</a>
    <a href="admincenter.php?site=admin_forum&action=admin_forum_groups" class="btn btn-primary" type="button">' . $plugin_language[ 'groups' ] . '</a>
    <a href="admincenter.php?site=admin_forum&action=admin_forum_group-users" class="btn btn-primary disabled" type="button">' . $plugin_language[ 'group_users' ] . '</a>
    <a href="admincenter.php?site=admin_forum&action=admin_forum_ranks" class="btn btn-primary" type="button">' . $plugin_language[ 'user_ranks' ] . '</a><br /><br />'; 

   echo '<script type="text/javascript">
  /*<![CDATA[*/
    function checkForFilter(select){
        if(select.options[4].selected == true){
            document.getElementById(\'addfield\').style.display = \'block\';
            document.getElementById(\'addfield\').focus();
        } else {
            document.getElementById(\'addfield\').style.display = \'none\';
        }
    }
  /*]]>*/
  </script>
  <form method="post" name="post" action="admincenter.php?site=admin_forum&action=admin_forum_group-users&amp;jump=show">
  <table class="table table-striped">
    <thead>
      <th><b>'.$plugin_language['groups'].'</b></th>
      <th><b>'.$plugin_language['user_filter'].'</b></th>
    </thead>
    <tr>
      <td class="td1" valign="top">
      <select class="form-control" style="height:170px" name="groups[]" multiple="multiple">
        '.$groups.'
      </select>
      </td>
      <td class="td1" valign="top">
      <select class="form-control" style="height:170px" name="users[]" multiple="multiple" onchange="checkForFilter(this);">
        <option value="4">'.$plugin_language['filter_registered'].'</option>
        <option value="0">'.$plugin_language['filter_clanmember'].'</option>
        <option value="1">'.$plugin_language['filter_anyadmin'].'</option>
        <option value="2">'.$plugin_language['filter_superadmin'].'</option>
        <!--<option value="3">'.$plugin_language['users_from_group'].'</option>-->
      </select>
      <!--<div id="addfield" style="display:none;">
      <input name="addfield" style="width:170px; margin-top:5px;" type="text" />
      '.$plugin_language['filter'].'
      </div>-->
      </td>
    </tr>
      <tr>
      <td class="td_head" colspan="2" align="right">
        <button class="btn btn-primary" type="submit" />'.$plugin_language['show'].'</button>
      </td>
    </tr>
  </table>
  </form>';

echo '</div></div>';

}

}

/*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>admin_forum_ranks<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/


/*¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯\
|                      admin_forum_ranks                            |
\------------------------------------------------------------------*/

if (isset($_GET[ 'admin_forum_ranks_delete' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $rankID = (int)$_GET[ 'rankID' ];
        safe_query("UPDATE " . PREFIX . "user SET special_rank='0' WHERE special_rank='" . $rankID . "'");
        safe_query("DELETE FROM " . PREFIX . "plugins_forum_ranks WHERE rankID='" . $rankID . "'");
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'admin_forum_ranks_save' ])) {
    $name = $_POST[ 'name' ];
    $max = $_POST[ 'max' ];
    $min = $_POST[ 'min' ];

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('min', 'max')) || isset($_POST['special'])) {
            if ($max == "MAX") {
                $maximum = 2147483647;
            } else {
                $maximum = $max;
            }

            safe_query(
                "INSERT INTO
                    `" . PREFIX . "plugins_forum_ranks` (
                        `rank`,
                        `postmin`,
                        `postmax`,
                        `special`
                    )
                    VALUES (
                        '$name',
                        '$min',
                        '$maximum',
                        '".isset($_POST['special'])."'
                    )"
            );
            $id = mysqli_insert_id($_database);

            #$filepath = "images/icons/ranks/";
            $filepath = $plugin_path."images/icons/ranks/";

            $errors = array();

            //TODO: should be loaded from root language folder
            $_language->readModule('formvalidation', true);

            $upload = new \webspell\HttpUpload('rank');
            if ($upload->hasFile()) {
                if ($upload->hasError() === false) {
                    $mime_types = array('image/jpeg','image/png','image/gif');

                    if ($upload->supportedMimeType($mime_types)) {
                        $imageInformation = getimagesize($upload->getTempFile());

                        if (is_array($imageInformation)) {
                            switch ($imageInformation[ 2 ]) {
                                case 1:
                                    $endung = '.gif';
                                    break;
                                case 3:
                                    $endung = '.png';
                                    break;
                                default:
                                    $endung = '.jpg';
                                    break;
                            }
                            $file = $name . $endung;

                            if ($upload->saveAs($filepath . $file, true)) {
                                @chmod($filepath . $file, $new_chmod);
                                safe_query(
                                    "UPDATE " . PREFIX . "plugins_forum_ranks SET pic='".$file."' WHERE rankID='".$id."'"
                                );
                            }
                        } else {
                            $errors[] = $plugin_language['broken_image'];
                        }
                    } else {
                        $errors[] = $plugin_language['unsupported_image_type'];
                    }
                } else {
                    $errors[] = $upload->translateError();
                }
            }
            if (count($errors)) {
                $errors = array_unique($errors);
                echo generateErrorBoxFromArray($plugin_language['errors_there'], $errors);
            }
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'admin_forum_ranks_saveedit' ])) {
    $rank = $_POST[ 'rank' ];
    $min = $_POST[ 'min' ];
    $max = $_POST[ 'max' ];

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('min', 'max'))) {
            $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_ranks ORDER BY rankID");
            $anz = mysqli_num_rows($ergebnis);
            if ($anz) {
                while ($ds = mysqli_fetch_array($ergebnis)) {
                    if ($ds[ 'rank' ] != "Administrator" && $ds[ 'rank' ] != "Moderator") {
                        $id = $ds[ 'rankID' ];
                        if ($ds[ 'special' ] != 1) {
                            $minimum = $min[$id];
                            if ($max[ $id ] == "MAX") {
                                $maximum = 2147483647;
                            } else {
                                $maximum = $max[ $id ];
                            }
                        } else {
                            $maximum = 0;
                            $minimum = 0 ;
                        }
                        safe_query(
                            "UPDATE
                                " . PREFIX . "plugins_forum_ranks
                            SET
                                rank='".$rank[$id]."',
                                postmin='".$minimum."',
                                postmax='".$maximum."'
                            WHERE rankID='$id'"
                        );
                    }
                }
            }
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
}

if ($action == "admin_forum_ranks_add") {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    echo'<div class="card">
            <div class="card-header">
                '.$plugin_language['user_ranks'].'
            </div>
            <div class="card-body">
  <a href="admincenter.php?site=admin_forum&action=admin_forum_ranks" class="white">'.$plugin_language['user_ranks'].'</a> &raquo; '.$plugin_language['add_rank'].'<br><br>';

    echo '<script type="text/javascript">
  function HideFields(state){
    if(state == true){
        document.getElementById(\'max\').style.display = "none";
        document.getElementById(\'min\').style.display = "none";
    }
    else{
        document.getElementById(\'max\').style.display = "";
        document.getElementById(\'min\').style.display = "";
    }
  }
  </script>';

  echo'<form class="form-horizontal" method="post" action="admincenter.php?site=admin_forum&action=admin_forum_ranks" enctype="multipart/form-data">
  <div class="row">

<div class="col-md-6">

    <div class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['rank_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="name" size="60" /></em></span>
    </div>
  </div>

  <div class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['rank_icon'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input name="rank" type="file" size="40" /></em></span>
    </div>
  </div>  

  </div>

<div class="col-md-6">

    <div id="min" class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['min_posts'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="min" size="4" /></em></span>
    </div>
  </div>
  <div id="max" class="mb-3 row">
    <label class="col-sm-4 control-label">'.$plugin_language['max_posts'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input class="form-control" type="text" name="max" size="4" /></em></span>
    </div>
  </div>
  <div class="mb-3 row">
    <label class="col-sm-4 control-label">' . $plugin_language[ 'special_rank' ] . ':</label>
    <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
        <input class="form-check-input" type="checkbox" name="special" onchange="javascript:HideFields(this.checked);" value="1" />
    </div>
  </div>

  </div>
  <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-8">
        <input type="hidden" name="captcha_hash" value="'.$hash.'" />
        <button class="btn btn-success" type="submit" name="admin_forum_ranks_save" />'.$plugin_language['add_rank'].'</button>
    </div>
  </div>
</form>
  </div>
  </div>';

} elseif ($action == "admin_forum_ranks") {
    
    echo'<div class="card">
            <div class="card-header">
                '.$plugin_language['user_ranks'].'
            </div>
            <div class="card-body">';
  echo'
    <a href="admincenter.php?site=admin_forum" class="btn btn-primary" type="button">' . $plugin_language[ 'boards' ] . '</a>
    <a href="admincenter.php?site=admin_forum&action=admin_forum_groups" class="btn btn-primary" type="button">' . $plugin_language[ 'groups' ] . '</a>
    <a href="admincenter.php?site=admin_forum&action=admin_forum_group-users" class="btn btn-primary" type="button">' . $plugin_language[ 'group_users' ] . '</a>
    <a href="admincenter.php?site=admin_forum&action=admin_forum_ranks" class="btn btn-primary disabled" type="button">' . $plugin_language[ 'user_ranks' ] . '</a><br /><br />'; 

    
  echo'<a href="admincenter.php?site=admin_forum&action=admin_forum_ranks_add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_rank' ] . '</a><br /><br />';
    
  echo'<form method="post" action="admincenter.php?site=admin_forum&action=admin_forum_ranks">
  <table class="table table-striped">
    <thead>
      <th class="hidden-xs"><b>'.$plugin_language['rank_icon'].'</b></th>
      <th><b>'.$plugin_language['rank_name'].'</b></th>
      <th><b>' . $plugin_language[ 'special_rank' ] . '</b></th>
      <th><b>'.$plugin_language['min_posts'].'</b></th>
      <th><b>'.$plugin_language['max_posts'].'</b></th>
      <th><b>'.$plugin_language['actions'].'</b></th>
      </thead>';

    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_ranks ORDER BY postmax");
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
    $i = 1;
    while ($ds = mysqli_fetch_array($ergebnis)) {
        $filepath = $plugin_path."images/icons/ranks/";
        
        if ($ds[ 'rank' ] == "Administrator" || $ds[ 'rank' ] == "Moderator") {
            echo '<tr>
            <td class="hidden-xs" align="center"><img src="../' . $filepath . $ds[ 'pic' ] . '" alt=""></td>
            <td><span class="text-muted small"><em>' . $ds[ 'rank' ] . '</em></span></td>
            <td align="center">x</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>';
        } else {
            if (mb_strlen(trim($ds[ 'postmax' ])) > 8) {
                $max = "MAX";
            } else {
                $max = $ds[ 'postmax' ];
            }

            $user_list = "";
            $min = '<input class="form-control" type="text" name="min['.$ds['rankID'].']" value="'.$ds['postmin'].'" size="6" dir="rtl" />';
            $max = '<input class="form-control" type="text" name="max['.$ds['rankID'].']" value="'.$max.'" size="6" dir="rtl" />';

            if ($ds['special']==1) {
                $get = safe_query(
                    "SELECT
                        nickname,
                        userID
                    FROM
                        `".PREFIX."user`
                    WHERE
                        special_rank = '" . $ds['rankID'] . "'"
                );
                $user_list = array();
                while ($user = mysqli_fetch_assoc($get)) {
                    $user_list[] = '<a href="admincenter.php?site=members&amp;action=edit&amp;id=' .
                        $user['userID'] . '">' . $user['nickname'] . '</a>';
                }
                $user_list = "<br/><small>" . $plugin_language['used_for'] . ": " .
                    implode(", ", $user_list) . "</small>";
                $min = "";
                $max = "";
            }
            $filepath = $plugin_path."images/icons/ranks/";
             echo '<tr>
            <td  class="hidden-xs" align="center"><img src="../' . $filepath . $ds[ 'pic' ] . '" alt=""></td>
            <td><span class="text-muted small"><em><input class="form-control" type="text" name="rank[' . $ds[ 'rankID' ] . ']" value="' .
                getinput($ds[ 'rank' ]) . '" size="30" />'.$user_list.'</em></span></td>
            <td align="center"><span class="text-muted small"><em>' . (($ds[ 'special' ]==1) ? "x" : "") . '</em></span></td>

            <td align="center"><span class="text-muted small"><em>'.$min.'</em></span></td>
            <td align="center"><span class="text-muted small"><em>'.$max.'</em></span></td>
            <td align="center">
                
                <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_forum&action=admin_forum_ranks&amp;admin_forum_ranks_delete=true&amp;rankID=' .
                $ds[ 'rankID' ] . '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'user_ranks' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete_rang'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->

                </td>
          </tr>';
        }
        $i++;
    }
    echo '<tr>
      <td class="td_head" colspan="6" align="right"><input type="hidden" name="captcha_hash" value="' . $hash .
        '"><input class="btn btn-primary" type="submit" name="admin_forum_ranks_saveedit" value="' . $plugin_language[ 'update' ] . '" /></td>
    </tr>
  </table>
  </form></div></div>';
}

?>