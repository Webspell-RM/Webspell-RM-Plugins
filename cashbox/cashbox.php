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

    $data_array = array();
    $data_array['$title'] = $plugin_language['cash_box'];    
    $data_array['$subtitle']='Cash_Box';

    $template = $GLOBALS["_template"]->loadTemplate("cashbox","title_head", $data_array, $plugin_path);
    echo $template;

if (!isclanmember($userID) && !iscashadmin($userID)) {
    echo $plugin_language[ 'clanmembers_only' ];
} else {


        function print_cashbox($squadID, $id)
        {
            global $plugin_path,$plugin_language,$members;

            

    # Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("cashbox", $plugin_path);

    global $userID, $myclanname;

            if($id) {
                $squadergebnis=safe_query("SELECT squad FROM ".PREFIX."plugins_cashbox WHERE cashID='".$id."'");
                $dv=mysqli_fetch_array($squadergebnis);
                $squadID = $dv['squad'];
            }

            
             $costs_squad = '';
            if ($squadID == 0) {
                $usersquad = $plugin_language[ 'clan' ] . ": " . $myclanname . " " . $plugin_language[ 'total_clan' ];
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

                $costs_squad = ' (<strong class="'.$fontcolor.'">' . $costs_squad . '</strong> €)';
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
                $data_array['$info'] = $info;
                $data_array['$usage'] = $usage;
                $data_array['$date'] = $date;
                $data_array['$totalcosts'] = $ds['totalcosts'];
                $data_array['$usercosts'] = $ds['usercosts'];
                $data_array['$paydate'] = $paydate;
                $data_array['$konto'] = $konto;
                
                $data_array['$lang_saved_on']=$plugin_language[ 'saved_on' ];
                $data_array['$lang_total_costs']=$plugin_language[ 'total_costs' ];
                $data_array['$lang_costs_member']=$plugin_language[ 'costs_member' ];
                $data_array['$lang_pay_until']=$plugin_language[ 'pay_until' ];
                $data_array['$lang_bank_account']=$plugin_language[ 'bank_account' ];

                $template = $GLOBALS["_template"]->loadTemplate("cashbox","usage", $data_array, $plugin_path);
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

                $template = $GLOBALS["_template"]->loadTemplate("cashbox","head", $data_array, $plugin_path);
                echo $template;

                if (count($members)) {
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

                        if (iscashadmin($userID)) {
                            if (@$du[ 'costs' ]) {
                                
                                $costs = '<b>'.$du[ 'costs' ].' €<b>';
                            } else {
                                $costs = '<b>0.00 €<b>';
                                
                            }
                            $payment = $costs;

                        } else {
                            if ($du[ 'costs' ]) {
                                $costs = '<span class="text-success">'.$du[ 'costs' ].' €</span>';
                                
                            } else {
                                $costs = '<span class="text-danger">0.00 €</span>';
                                
                            }
                            $payment = $costs;
                        }


                        $data_array = array();
                        $data_array['$user'] = $user;
                        $data_array['$payed'] = $payed;
                        $data_array['$payment'] = $payment;
                        $template = $GLOBALS["_template"]->loadTemplate("cashbox","content", $data_array, $plugin_path);
                        echo $template;
                    }
                }

                
                $data_array = array();
                
                $template = $GLOBALS["_template"]->loadTemplate("cashbox","foot", $data_array, $plugin_path);
                echo $template;
               
            } else {
               
                echo $plugin_language[ 'no_entries' ];
            }

            echo '</div></div></div></div>';

           # }

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

        $costs = ' (<strong class="'.$fontcolor.'">' . $costs . '</strong> €)';

        $data_array = array();
        $data_array['$costs'] = $costs;
        $data_array['$lang_total_amount']=$plugin_language[ 'total_amount' ];
        $template = $GLOBALS["_template"]->loadTemplate("cashbox","top", $data_array, $plugin_path);
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
                    print_cashbox($da[ 'squadID' ], $id);
                }
            }
        }
 #   }
}