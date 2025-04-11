<?php
/*-----------------------------------------------------------------\
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
\------------------------------------------------------------------*/

# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("cashbox", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='cashbox'");
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

if (isset($_POST[ 'save' ]) && $_POST[ 'save' ]) {
    
    $date = time();
    $paydate = strtotime($_POST[ 'date' ]);

    safe_query(
        "INSERT INTO
            " . PREFIX . "plugins_cashbox (
                `date`,
                `paydate`,
                `usedfor`,
                `info`,
                `totalcosts`,
                `usercosts`,
                `squad`,
                `konto`
        )
        VALUES (
            '$date',
            '$paydate',
            '" . $_POST[ 'usedfor' ] . "',
            '" . $_POST[ 'info' ] . "',
            '" . $_POST[ 'euro' ] . "',
            '" . $_POST[ 'usereuro' ] . "',
            '" . $_POST[ 'squad' ] . "',
            '" . $_POST[ 'konto' ] . "'
        )"
    );
    $id = mysqli_insert_id($_database);

    header("Location: admincenter.php?site=admin_cashbox&id=$id");
} elseif (isset($_POST[ 'saveedit' ]) && $_POST[ 'saveedit' ]) {
    
    $date = time();
    $paydate = strtotime($_POST[ 'date' ]);

    $id = (int)$_POST[ 'id' ];

    safe_query(
        "UPDATE
            " . PREFIX . "plugins_cashbox
        SET
            date = '" . $date . "',
            paydate ='" . $paydate . "',
            usedfor = ' " . $_POST[ 'usedfor' ] . "',
            info = '" . $_POST[ 'info' ] . "',
            totalcosts = '" . $_POST[ 'euro' ] . "',
            squad = '" . ((int)$_POST[ 'squad' ]) . "',
            konto = '" . $_POST[ 'konto' ] . "',
            usercosts= ' " . $_POST[ 'usereuro' ] . "'
        WHERE
            cashID='".$id."'"
    );

    header("Location: admincenter.php?site=admin_cashbox");


} elseif (isset($_GET[ "delete" ]) && $_GET[ 'delete' ]) {
    $id = (int)$_GET[ 'id' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("DELETE FROM " . PREFIX . "plugins_cashbox WHERE cashID='".$id."'");
        $get = safe_query("DELETE FROM " . PREFIX . "plugins_cashbox_payed WHERE cashID='".$id."'");
        
 
        
    } else {
            redirect("admincenter.php?site=admin_cashbox", "", 0);
    }
    

redirect("admincenter.php?site=admin_cashbox", "", 0);


} elseif (isset($_GET[ "costdelete" ]) && $_GET[ 'costdelete' ]) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $get = safe_query("SELECT * FROM " . PREFIX . "plugins_cashbox_payed WHERE cashID='" . $_GET[ "cashID" ] . "' AND userID='".$_GET[ "userID" ] . "'");
        $data = mysqli_fetch_assoc($get);

        if (safe_query("DELETE FROM " . PREFIX . "plugins_cashbox_payed WHERE cashID='" . $_GET[ "cashID" ] . "' AND userID='".$_GET[ "userID" ] . "'")) {
           
            redirect("admincenter.php?site=admin_cashbox", "", 0);
        } else {
            redirect("admincenter.php?site=admin_cashbox", "", 0);
        }
    } else {
        print_r($plugin_language); return false;
        $_language->readModule('formvalidation', true);  
        echo $_language->module[ 'transaction_invalid' ];
    }






} elseif (isset($_POST[ 'pay' ]) && $_POST[ 'pay' ]) {
    
    $payid = $_POST[ 'payid' ];
    $costs = isset($_POST[ 'costs' ]);
    $id = (int)$_POST[ 'id' ];

    $date = time();
    foreach ($payid as $usID => $costs) {
        if ($costs != "") {
            $usID = (int)$usID;
            if (mysqli_num_rows(
                safe_query(
                    "SELECT
                            payedID
                        FROM
                            " . PREFIX . "plugins_cashbox_payed
                        WHERE
                            userID='$usID'AND
                            cashID='".$id."'"
                )
            )
            ) {
                safe_query(
                    "UPDATE
                        " . PREFIX . "plugins_cashbox_payed
                    SET
                        costs='$costs'
                    WHERE
                        userID='$usID' AND
                        cashID='".$id."'"
                );
            } else {
                safe_query(
                    "INSERT INTO
                        " . PREFIX . "plugins_cashbox_payed (
                            `cashID`,
                            `userID`,
                            `costs`,
                            `date`,
                            `payed`
                        )
                        VALUES (
                            '$id',
                            '$usID',
                            '$costs',
                            '$date',
                            '1'
                        )"
                );
            }
        }
    }

    #header("Location: admincenter.php?site=admin_cashbox&id=$id");
    redirect("admincenter.php?site=admin_cashbox", "", 1);
}


    if (isset($_GET[ 'action' ]) && $_GET[ 'action' ] == "new") {
        
        $anz = 0;
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "user ORDER BY nickname");
        while ($du = mysqli_fetch_array($ergebnis)) {
            if (isclanmember($du[ 'userID' ])) {
                $anz++;
            }
        }

        if (isset($_GET[ 'euro' ]) == "") {
            $euro = "0.00";
        }
        if (isset($_GET[ 'usereuro' ]) == "") {
            $usereuro = "0.00";
        }
        $squads = '<option value="0">' . $plugin_language[ 'each_squad' ] . '</option>' . getsquads();

        

echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-cash-coin"></i> ' . $plugin_language[ 'cash_box' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_cashbox">' . $plugin_language[ 'cash_box' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_payment' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';


echo '
<script>
    <!--
    function chkFormular() {
        if (document.getElementById("usedfor").value === "") {
            alert("%enter_usage%");
            document.getElementById("usedfor").focus();

            return false;
        }

        if (document.getElementById("day").value === "") {
            alert("%enter_date%");
            document.getElementById("day").focus();

            return false;
        }

        if (document.getElementById("month").value === "") {
            alert("%enter_date%");
            document.getElementById("month").focus();

            return false;
        }

        if (document.getElementById("year").value === "") {
            alert("%enter_date%");
            document.getElementById("year").focus();

            return false;
        }

        if (
                document.getElementById("euro").value === "" ||
                document.getElementById("euro").value == "0.00"
        ) {
            alert("%enter_total_costs%");
            document.getElementById("euro").focus();

            return false;
        }

        if (
                document.getElementById("usereuro").value == "" ||
                document.getElementById("usereuro").value == "0.00"
        ) {
            alert("%enter_costs_member%");
            document.getElementById("usereuro").focus();

            return false;
        }

    }

    function berechnen() {
        var anz = document.getElementById("anz").value,
            total = document.getElementById("euro").value,
            user = total / anz,
            rounduser = Math.round(user);

        document.getElementById("usereuro").value = rounduser;

        document.getElementById("usereuro").focus();
    }
    -->
</script>



<form name="post" method="post" action="admincenter.php?site=admin_cashbox" onsubmit="return chkFormular()" role="form">
<div class="row">
    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language[ 'squad' ] . '</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <select name="squad" id="squad" class="form-control">' . $squads . '</select>
    </div>
  </div>

    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language[ 'usage' ] . '</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input name="usedfor" type="text" id="usedfor" value="" size="55" class="form-control">
    </div>
  </div>

    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language[ 'info' ] . '</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <textarea name="info" cols="50" rows="7" id="info" class="form-control"></textarea>
    </div>
  </div>

    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language[ 'bank_account' ] . '</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <textarea name="konto" cols="50" rows="4" id="konto" class="form-control"></textarea>
    </div>
  </div>

    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language[ 'pay_until' ] . '</label>
    <div class="col-sm-8"><span class="text-muted small"><em>

        <input type="date" name="date" id="date" placeholder="yyyy-mm-dd" class="form-control">
    </div>
  </div>

    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language[ 'total_costs' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input type="text" dir="rtl" name="euro" id="euro" size="8" value="' . $euro . '" class="form-control">
    </div>
  </div>

    <div class="mb-3 row">
        <label class="col-sm-2 control-label">' . $plugin_language[ 'costs_member' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>

        <div class="input-group">
            <input type="text" dir="rtl" name="usereuro" id="usereuro" size="8" value="' . $usereuro . '" class="form-control">
            <span class="input-group-btn">
                <input type="button" name="calculate" value="' . $plugin_language[ 'calculate' ] . '" onclick="berechnen()" class="btn btn-info">
            </span>
        </div>
    </div>
  </div>

    <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="anz" id="anz" value="' . $anz . '">
        <input type="submit" name="save" value="' . $plugin_language[ 'submit' ] . '" class="btn btn-primary">
     </div>
      </div>

</form>
</div></div></div>';


 } elseif (isset($_GET[ 'action' ]) && $_GET[ 'action' ] == "edit") {
        

        echo '<h2>' . $plugin_language[ 'cash_box' ] . '</h2>';

        $id = (int)$_GET[ 'id' ];
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_cashbox WHERE cashID='$id'");
        $ds = mysqli_fetch_array($ergebnis);

        $date = date("Y-m-d", $ds[ 'paydate' ]);

        $info = getinput($ds[ 'info' ]);
        $usage = getinput($ds[ 'usedfor' ]);
        $bank_account = getinput($ds[ 'konto' ]);

        $anz = 0;
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "user ORDER BY nickname");

        while ($du = mysqli_fetch_array($ergebnis)) {
            if (isclanmember($du[ 'userID' ])) {
                $anz++;
            }
        }
        $squads = '<option value="0">' . $plugin_language[ 'each_squad' ] . '</option>' . getsquads();
        $squads = str_replace(
            'value="' . $ds[ 'squad' ] . '"',
            'value="' . $ds[ 'squad' ] . '" selected="selected"',
            $squads
        );

        
echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-cash-coin"></i> ' . $plugin_language[ 'cash_box' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_cashbox">' . $plugin_language[ 'cash_box' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_payment' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';

    echo '<script>
    <!--
    function chkFormular() {
        if (document.post.usedfor.value === "") {
            alert("%enter_usage%");
            document.post.usedfor.focus();

            return false;
        }

        if (document.post.day.value === "") {
            alert("%enter_date%");
            document.post.day.focus();

            return false;
        }

        if (document.post.month.value === "") {
            alert("%enter_date%");
            document.post.month.focus();

            return false;
        }

        if (document.post.year.value === "") {
            alert("%enter_date%");
            document.post.year.focus();

            return false;
        }

        if (document.post.euro.value === "" || document.post.euro.value == "0.00") {
            alert("%enter_total_costs%");
            document.post.euro.focus();

            return false;
        }

        if (document.post.usereuro.value === "" || document.post.usereuro.value == "0.00") {
            alert("%enter_costs_member%");
            document.post.usereuro.focus();

            return false;
        }

    }

    function berechnen() {
        var anz = document.getElementById("anz").value,
            total = document.getElementById("euro").value,
            user = total / anz,
            rounduser = Math.round(user);

        document.getElementById("usereuro").value = rounduser;
        document.getElementById("usereuro").focus();
    }

    -->
</script>

<form name="post" method="post" action="admincenter.php?site=admin_cashbox" onsubmit="return chkFormular()">
<div class="row">
    <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'squad' ] . '</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <select name="squad" id="squad" class="form-control">' . $squads . '</select>
    </div>
  </div>

    <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'usage' ] . '</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input name="usedfor" type="text" id="usedfor" value="' . $usage . '" size="55" class="form-control">
    </div>
  </div>

    <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'info' ] . '</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <textarea name="info" cols="50" rows="7" id="info" class="form-control">' . $info . '</textarea>
    </div>
  </div>

    <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'bank_account' ] . '</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <textarea name="konto" cols="50" rows="4" id="konto" class="form-control">' . $bank_account . '</textarea>
    </div>
  </div>

    <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'pay_until' ] . '</label>
    <div class="col-sm-8"><span class="text-muted small"><em>

        <input type="date" name="date" id="date" placeholder="yyyy-mm-dd" value="' . $date . '" class="form-control">
    </div>
  </div>

    <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'total_costs' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
        <input type="text" dir="rtl" name="euro" id="euro" size="8" value="' . getinput($ds['totalcosts']) . '" class="form-control">
    </div>
  </div>

    <div class="mb-3 row">
    <label class="col-sm-2 control-label">' . $plugin_language[ 'costs_member' ] . ':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>

        <div class="input-group">
            <input type="text" dir="rtl" name="usereuro" id="usereuro" size="8"  value="' . getinput($ds['usercosts']) . '"
                class="form-control">
            <span class="input-group-btn">
                <input type="button" name="calculate" value="' . $plugin_language[ 'calculate' ] . '" onclick="berechnen()" class="btn btn-info">
            </span>
        </div>
    </div>
    </div>

    <div class="mb-3 row">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="anz" id="anz" value="' . $anz . '">
        <input type="hidden" name="id" value="' . $id . '">
        <input type="submit" name="saveedit" value="' . $plugin_language[ 'submit' ] . '" class="btn btn-primary">
    </div>
    </div>

</form>
</div></div></div>';


    } else {

        echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-cash-coin"></i> ' . $plugin_language[ 'cash_box' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_cashbox">' . $plugin_language[ 'cash_box' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="mb-3 row row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_cashbox&amp;action=new" class="btn btn-primary" type="button">' . $plugin_language[ 'add_payment' ] . '</a>
    </div>
  </div>

';

echo '<form method="post" action="admincenter.php?site=admin_cashbox">';

        function print_cashbox($squadID, $id)
        {
        
        global $userID, $myclanname, $plugin_path, $plugin_language;

        # Sprachdateien aus dem Plugin-Ordner laden
        $pm = new plugin_manager(); 
        $plugin_language = $pm->plugin_language("cashbox", $plugin_path);

        

            if($id) {
                $squadergebnis=safe_query("SELECT squad FROM ".PREFIX."plugins_cashbox WHERE cashID='".$id."'");
                $dv=mysqli_fetch_array($squadergebnis);
                @$squadID = $dv['squad'];
            }

            
             $costs_squad = '';
            if (@$squadID == 0) {
                $usersquad = $myclanname;
            } else {

                $ergebnis_squad =
                    safe_query(
                        "SELECT *
                        FROM
                            " . PREFIX . "plugins_cashbox_payed,
                            " . PREFIX . "plugins_cashbox
                        WHERE
                            " . PREFIX . "plugins_cashbox_payed.payed='1' AND
                            " . PREFIX . "plugins_cashbox_payed.cashID=" . PREFIX . "plugins_cashbox.cashID AND
                            " . PREFIX . "plugins_cashbox.squad = '" . (int)$squadID."'"
                    );
                $anz_squad = mysqli_num_rows($ergebnis_squad);
                $costs_squad = 0.00;
                if ($anz_squad) {
                    while ($dss = mysqli_fetch_array($ergebnis_squad)) {
                        $costs_squad += $dss[ 'costs' ];
                    }
                }
                $ergebnis_squad = safe_query("SELECT * FROM " . PREFIX . "plugins_cashbox WHERE squad='".(int)$squadID."'");
                $anz_squad = mysqli_num_rows($ergebnis_squad);
                if ($anz_squad) {
                    while ($dss = mysqli_fetch_array($ergebnis_squad)) {
                        $costs_squad -= $dss[ 'totalcosts' ];
                    }
                }

                if ($costs_squad < 0) {
                $fontcolor = "text-danger";
                } else {
                $fontcolor = "text-success";
                }

                $costs_squad = ' (<strong class="'.$fontcolor.'">' . $costs_squad . '</strong>)';
                $usersquad = $plugin_language[ 'squad' ] . ": " . getsquadname($squadID);
            }



            $ergebnis = safe_query(
                "SELECT *
                FROM " . PREFIX . "plugins_cashbox
                WHERE squad='" . $squadID . "'
                ORDER BY paydate
                DESC LIMIT 0,1"
            );

            echo '<div class="card">
            <div class="card-body"><div class="row"><div class="col-md-6"><strong>' . $usersquad . $costs_squad . '</strong>';


            if (mysqli_num_rows($ergebnis)) {
                $ds = mysqli_fetch_array($ergebnis);
                if (!$id) {
                    $id = $ds[ 'cashID' ];
                }

               

                $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_cashbox WHERE cashID='$id'");
                $ds = mysqli_fetch_array($ergebnis);
                $date = getformatdate($ds[ 'date' ]);
                $paydate = getformatdate($ds[ 'paydate' ]);

                $bezahlen = safe_query("SELECT * FROM " . PREFIX . "plugins_cashbox_payed WHERE cashID='$id' AND payed='1' ");
                $payed = mysqli_num_rows($bezahlen);
                $konto = $ds[ 'konto' ];
                $info = $ds[ 'info' ];

                $usage = $ds[ 'usedfor' ];
                
                $data_array = array();
                $data_array['$usage'] = $usage;
                $data_array['$date'] = $date;
                $data_array['$totalcosts'] = $ds['totalcosts'];
                $data_array['$usercosts'] = $ds['usercosts'];
                $data_array['$paydate'] = $paydate;
                $data_array['$konto'] = $konto;
                $data_array['$info'] = $info;
                
                $data_array['$lang_saved_on']=$plugin_language[ 'saved_on' ];
                $data_array['$lang_total_costs']=$plugin_language[ 'total_costs' ];
                $data_array['$lang_costs_member']=$plugin_language[ 'costs_member' ];
                $data_array['$lang_pay_until']=$plugin_language[ 'pay_until' ];
                $data_array['$lang_bank_account']=$plugin_language[ 'bank_account' ];

                $template = $GLOBALS["_template"]->loadTemplate("cashbox","admin_usage", $data_array, $plugin_path);
                echo $template;

                

            echo '</div><div class="col-md-6">';



                $members = array();
                $ergebnis = safe_query("SELECT * FROM " . PREFIX . "user ORDER BY nickname");
                while ($du = mysqli_fetch_array($ergebnis)) {
                    if ($squadID == 0) {
                        if (isclanmember($du[ 'userID' ])) {
                            $members[ ] = $du[ 'userID' ];
                        }
                    } else {
                        if (issquadmember($du[ 'userID' ], $squadID)) {
                            $members[ ] = $du[ 'userID' ];
                        }
                    }
                }

                $data_array = array();
                $data_array['$lang_member']=$plugin_language[ 'member' ];
                $data_array['$lang_paid']=$plugin_language[ 'paid' ];
                $data_array['$lang_amount']=$plugin_language[ 'amount' ];

                $template = $GLOBALS["_template"]->loadTemplate("cashbox","admin_head", $data_array, $plugin_path);
                echo $template;

                if (count($members)) {
                        $CAPCLASS = new \webspell\Captcha;
                        $CAPCLASS->createTransaction();
                        $hash = $CAPCLASS->getHash();

                    foreach ($members as $usID) {
                        $ergebnis = safe_query(
                            "SELECT
                                *
                            FROM
                                " . PREFIX . "plugins_cashbox_payed
                            WHERE
                                userID='$usID' AND
                                cashID='" . (int)$id . "'"
                        );
                        $du = mysqli_fetch_array($ergebnis);
                        @$user = '<a href="index.php?site=profile&amp;id=' . $usID . '">
                                <strong>' . getnickname($usID) . '</strong>
                            </a>';
                        if (@$du[ 'payed' ]) {
                            $paydate = getformatdate($du[ 'date' ]);
                            $payed =
                                '<span class="text-success">' .
                                    $plugin_language[ 'paid' ] . ': ' . $paydate .
                                '</span>';
                        } else {
                            $payed =
                                '<span class="text-danger">' .
                                    $plugin_language[ 'not_paid' ] . '
                                </span>';
                        }

                        if(iscashadmin($userID)) {
                            if(@$du['costs']) {
                                
                                $costs=$du['costs'];
                            }
                            else {
                                $costs="";
                                
                            }
                            $payment='<input type="text" size="7" name="payid['.$usID.']" value="'.$costs.'" dir="rtl" class="form-control"/>';
                        }
                        else {
                            if($du['costs']) {
                                $costs='<strong>' . $du[ 'costs' ] . '</strong>';
                                
                            }
                            else {
                                $costs='<span class="text-danger">0.00</span>';
                                
                            }
                            $payment=$costs;
                        }

                        $delete = ' &#8364; <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_cashbox&amp;costdelete=true&amp;cashID=' . $ds[ 'cashID' ] . '&amp;userID=' . $usID . '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['payment_delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'cash_box' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body text-center"><p>' . $plugin_language['really_delete'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['payment_delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->';

                        $data_array = array();
                        $data_array['$user'] = $user;
                        $data_array['$payed'] = $payed;
                        $data_array['$payment'] = $payment;
                        $data_array['$delete'] = $delete;
                        $template = $GLOBALS["_template"]->loadTemplate("cashbox","admin_content", $data_array, $plugin_path);
                        echo $template;
                    }
                }

                $admin='<input type="hidden" name="id" value="'.$id.'" /><input  class="btn btn-success" type="submit" name="pay" value="'.$plugin_language['update'].'" />';
                $data_array = array();
                $data_array['$admin'] = $admin;
                $template = $GLOBALS["_template"]->loadTemplate("cashbox","admin_foot", $data_array, $plugin_path);
                echo $template;



               #---------------------------------------------------------





    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();


echo '<div class="mb-3 row">
    <div class="col-sm-offset-9 col-sm-3 right-text">
      <a class="btn btn-warning" href="admincenter.php?site=admin_cashbox&action=edit&id=' . $ds[ 'cashID' ] .
                    '"" class="input">' . $plugin_language[ 'edit' ] . '</a>

           <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_cashbox&amp;delete=true&amp;id=' . $ds[ 'cashID' ] . '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'cash_box' ] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
      </div>
      <div class="modal-body text-center"><p>' . $plugin_language['really_delete'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->


    </div>
  </div>


</form>';

} else {
               
                echo $plugin_language[ 'no_entries' ];
            }

        echo '</div><div class="col-md-12">';
        $all = safe_query(
                    "SELECT
                        *
                    FROM
                        " . PREFIX . "plugins_cashbox
                    WHERE
                        squad='" . $squadID . "'
                    ORDER BY
                        paydate DESC"
                );
                while ($ds = mysqli_fetch_array($all)) {
                    echo
                        '&#8226; <a href="admincenter.php?site=admin_cashbox&amp;id=' . $ds[ 'cashID' ] . '&amp;squad=' . $squadID .
                        '"><strong>' . $ds[ 'usedfor' ] . '</strong></a><br>';
                }
        echo '</div></div></div></div>';        
        }

        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_cashbox_payed WHERE payed='1'");
        $anz = mysqli_num_rows($ergebnis);
        $costs = 0.00;
        if ($anz) {
            while ($ds = mysqli_fetch_array($ergebnis)) {
                $costs += $ds[ 'costs' ];
            }
        }

        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_cashbox ");
        $anz = mysqli_num_rows($ergebnis);
        if ($anz) {
            while ($ds = mysqli_fetch_array($ergebnis)) {
                $costs -= $ds[ 'totalcosts' ];
            }
        }

        if ($costs < 0) {
            $fontcolor = "text-danger";
        } else {
            $fontcolor = "text-success";
        }

        $costs = ' (<strong class="'.$fontcolor.'">' . $costs . '</strong>)';

        

        $data_array = array();
        $data_array['$costs'] = $costs;
        $data_array['$lang_total_amount']=$plugin_language[ 'total_amount' ];
        $template = $GLOBALS["_template"]->loadTemplate("cashbox","admin_top", $data_array, $plugin_path);
        echo $template;

        if (!isset($_GET[ 'id' ])) {
            print_cashbox(0, 0);
            if (iscashadmin($userID)) {
                $squadergebnis = safe_query("SELECT squadID FROM " . PREFIX . "plugins_squads");
            } else {
                $squadergebnis =
                    safe_query("SELECT squadID FROM " . PREFIX . "squads_members WHERE userID='" . (int)$userID."'");
            }
            while ($da = mysqli_fetch_array($squadergebnis)) {
                print_cashbox($da[ 'squadID' ], 0);
            }
        } else {
            $id = (int)$_GET[ 'id' ];
            if (isset($_GET[ 'squad' ])) {
                $get_squad = (int)$_GET[ 'squad' ];
            } else {
                $get_squad = 0;
            }
            if ($get_squad == 0) {
                print_cashbox(0, $id);
            } else {
                print_cashbox(0, 0);
            }
            if (iscashadmin($userID)) {
                $squadergebnis = safe_query("SELECT squadID FROM " . PREFIX . "plugins_squads");
            } else {
                $squadergebnis =
                    safe_query("SELECT squadID FROM " . PREFIX . "squads_members WHERE userID='" . (int)$userID."'");
            }
            while ($da = mysqli_fetch_array($squadergebnis)) {
                if ($get_squad == $da[ 'squadID' ]) {
                    print_cashbox($da[ 'squadID' ], $id);
                } else {
                    print_cashbox($da[ 'squadID' ], 0);
                }
            }
        }
    }
