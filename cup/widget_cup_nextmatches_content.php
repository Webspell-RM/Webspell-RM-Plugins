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
$plugin_language = $pm->plugin_language("cup", $plugin_path);

include('./includes/plugins/cup/cup_abfragen-org.php');

?> 
<div class="card">
           

            <div class="card-body">
                <div class="col-md-12"><h2 class="text-center"><? echo $plugin_language['nextmatches'] ?></h2></div>
    <div class="col-md-12 text-center"><? echo $plugin_language['whoisplayingagainstwhom'] ?></div>  



                <div class="table-responsive">
<marquee scrollamount="3" direction="up" height="200px" onmouseover="this.setAttribute('scrollamount', 0, 0);" onmouseout="this.setAttribute('scrollamount', 3, 0);">
    
<table class="table table-sm">
    <tr valign="middle" align="center">
        <td colspan="3" style="color:#000;border:solid 1px #ccc;background:#fff;"><b><? echo $plugin_language['nextmatches'] ?></b></td>
    </tr>
    <tr>
        <td colspan="3" align="center" style="font-weight:bold;"><a href="index.php?site=cup"><? echo $plugin_language['group1'] ?></a>

              </td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan1_id)) {
                echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan1_clantag . '</span>';
            } else {
                echo $plugin_language['free_space'];
            }
            ?>
        </td>
        <td align="center" width="30%"><?php echo $clan1_eg; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan2_eg; ?></td>
        <td bgcolor="#adafae" align="center" width="30%">
		    <?php
            if (isset($clan2_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan2_clantag . '</span>';
			} else {
				echo $plugin_language['free_space'];
			}
			?>
        </td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">
		    <?php
			if (isset($clan3_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan3_clantag . '</span>';
			} else {
				echo $plugin_language['free_space'];
			}
			?>
        </td>
        <td align="center" width="30%"><?php echo $clan3_eg; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan4_eg; ?></td>
        <td bgcolor="#adafae" align="center" width="30%">
            <?php
            if (isset($clan4_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan4_clantag . '</span>';
            } else {
				echo $plugin_language['free_space'];
            }
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="3" align="center" style="font-weight:bold;"><a href="index.php?site=cup"><? echo $plugin_language['group2'] ?></a></td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan5_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan5_clantag . '</span>';
            } else {
				echo $plugin_language['free_space'];
			}
            ?>
        </td>
        <td align="center" width="30%"><?php echo $clan5_eg; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan6_eg; ?></td>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan6_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan6_clantag . '</span>';
            } else {
				echo $plugin_language['free_space'];
            }
            ?>
        </td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan7_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan7_clantag . '</span>';
            } else {
				echo $plugin_language['free_space'];
            }
            ?>
        </td>
        <td align="center" width="30%"><?php echo $clan7_eg; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan8_eg; ?></td>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan8_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan8_clantag . '</span>';
            } else {
				echo $plugin_language['free_space'];
            }
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="3" align="center" style="font-weight:bold;"><a href="index.php?site=cup"><? echo $plugin_language['group3'] ?></a></td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan9_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan9_clantag . '</span>';
            } else {
				echo $plugin_language['free_space'];
            }
            ?>
        </td>
        <td align="center" width="30%"><?php echo $clan9_eg; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan10_eg; ?></td>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan10_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan10_clantag . '</span>';
            } else {
				echo $plugin_language['free_space'];
            }
            ?>
        </td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan11_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan11_clantag . '</span>';
            } else {
				echo $plugin_language['free_space'];
            }
            ?>
        </td>
        <td align="center" width="30%"><?php echo $clan11_eg; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan12_eg; ?></td>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan12_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan12_clantag .'</span>';
            } else {
				echo $plugin_language['free_space'];
            }
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="3" align="center" style="font-weight:bold;"><a href="index.php?site=cup"><? echo $plugin_language['group4'] ?></a></td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan13_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan13_clantag . '</span>';
            } else {
				echo $plugin_language['free_space'];
            }
            ?>
        </td>
        <td align="center" width="30%"><?php echo $clan13_eg; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan14_eg; ?></td>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan14_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan14_clantag . '</span>';
            } else {
				echo $plugin_language['free_space'];
            }
            ?>
        </td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan15_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan15_clantag . '</span>';
			} else {
				echo $plugin_language['free_space'];
            }
            ?>
        </td>
        <td align="center" width="30%"><?php echo $clan15_eg; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan16_eg; ?></td>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan16_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan16_clantag . '</span>';
            } else {
				echo $plugin_language['free_space'];
            }
            ?>
        </td>
    </tr>
	<?php
    if (isset($clan_vor_1_id)) {
	?>
    <tr>
        <td colspan="3" align="center" style="font-weight:bold;"><a href="index.php?site=cup"><? echo $plugin_language['quarterfinals'] ?></a></td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan_vor_1_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan_vor_1_clantag . '</span>';
            } else {
				echo 'n/a';
            }
            ?>
        </td>
        <td align="center" width="30%"><?php echo $clan_vor_1_ev; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan_vor_2_ev; ?></td>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan_vor_2_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan_vor_2_clantag . '</span>';
            } else {
				echo 'n/a';
            }
            ?>
        </td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan_vor_3_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan_vor_3_clantag . '</span>';
            } else {
				echo 'n/a';
            }
            ?>
        </td>
        <td align="center" width="30%"><?php echo $clan_vor_3_ev; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan_vor_4_ev; ?></td>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan_vor_4_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan_vor_4_clantag . '</span>';
            } else {
				echo 'n/a';
            }
            ?>
        </td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan_vor_5_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan_vor_5_clantag . '</span>';
            } else {
				echo 'n/a';
            }
            ?>
        </td>
        <td align="center" width="30%"><?php echo $clan_vor_5_ev; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan_vor_6_ev; ?></td>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan_vor_6_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan_vor_6_clantag . '</span>';
            } else {
				echo 'n/a';
            }
            ?>
        </td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan_vor_7_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan_vor_7_clantag . '</span>';
            } else {
				echo 'n/a';
            }
            ?>
        </td>
        <td align="center" width="30%"><?php echo $clan_vor_7_ev; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan_vor_8_ev; ?></td>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan_vor_8_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan_vor_8_clantag . '</span>';
            } else {
				echo 'n/a';
            }
            ?>
        </td>
    </tr>
	<?php
    } else {
	?>
    <tr>
        <td colspan="3" align="center" style="font-weight:bold;"><a href="index.php?site=cup"><? echo $plugin_language['quarterfinals'] ?></a></td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">n/a</td>
		<td align="center" width="30%"><?php echo $clan_vor_7_ev; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan_vor_8_ev; ?>xxxxxxxxxxxx</td>
		<td bgcolor="#adafae" align="center" width="30%">n/a</td>
    </tr>
	<?php
    }
    if (isset($clan_halb_1_id)) {
	?>
    <tr>
        <td colspan="3" align="center" style="font-weight:bold;"><a href="index.php?site=cup"><? echo $plugin_language['semifinals'] ?></a></td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan_halb_1_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan_halb_1_clantag . '</span>';
            } else {
				echo 'n/a';
            }
            ?>
        </td>
        <td align="center" width="30%"><?php echo $clan_halb_1_eh; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan_halb_2_eh; ?></td>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan_halb_2_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan_halb_2_clantag . '</span>';
            } else {
				echo 'n/a';
            }
            ?>
        </td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan_halb_3_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan_halb_3_clantag . '</span>';
            } else {
				echo 'n/a';
            }
            ?>
        </td>
        <td align="center" width="30%"><?php echo $clan_halb_3_eh; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan_halb_4_eh; ?></td>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan_halb_4_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan_halb_4_clantag . '</span>';
            } else {
				echo 'n/a';
            }
            ?>
        </td>
    </tr>
	<?php
    } else {
	?>
    <tr>
        <td colspan="3" align="center" style="font-weight:bold;"><a href="index.php?site=cup"><? echo $plugin_language['semifinals'] ?></a></td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">n/a</td>
		<td align="center" width="30%"><? echo $plugin_language['vs'] ?></td>
		<td bgcolor="#adafae" align="center" width="30%">n/a</td>
    </tr>
	<?php
    }
    if (isset($clan_fin_1_id)) {
	?>
    <tr>
        <td colspan="3" align="center" style="font-weight:bold;"><a href="index.php?site=cup"><? echo $plugin_language['final'] ?></a></td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan_fin_1_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan_fin_1_clantag . '</span>';
            } else {
				echo 'n/a';
            }
            ?>
        </td>
        <td align="center" width="30%"><?php echo $clan_fin_1_ef; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan_fin_2_ef; ?></td>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan_fin_2_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan_fin_2_clantag . '</span>';
            } else {
				echo 'n/a';
            }
            ?>
        </td>
    </tr>
	<?php
    } else {
	?>
    <tr>
        <td colspan="3" align="center" style="font-weight:bold;"><a href="index.php?site=cup"><? echo $plugin_language['final'] ?></a></td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">n/a</td>
		<td align="center" width="30%"><? echo $plugin_language['vs'] ?></td>
		<td bgcolor="#adafae" align="center" width="30%">n/a</td>
    </tr>
	<?php
    }
    if (isset($clan_platz3_1_id)) {
	?>
    <tr>
        <td colspan="3" align="center" style="font-weight:bold;"><a href="index.php?site=cup"><? echo $plugin_language['3rdplacematch'] ?></a></td>
    </tr>
    <tr>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan_platz3_1_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan_platz3_1_clantag . '</span>';
            } else {
				echo 'n/a';
            }
            ?>
        </td>
        <td align="center" width="30%"><?php echo $clan_platz3_1_ep3; ?> &nbsp; <b><? echo $plugin_language['vs'] ?></b> &nbsp; <?php echo $clan_platz3_2_ep3; ?></td>
        <td bgcolor="#adafae" align="center" width="30%">
			<?php
            if (isset($clan_platz3_2_id)) {
				echo '<span style="color:#000; font-weight:bold; font-family:Arial; font-size:11px" href="?cup">' . $clan_platz3_2_clantag . '</span>';
            } else {
				echo 'n/a';
            }
            ?>
        </td>
    </tr>
	<?php
    } else {
	?>
    <tr>
        <td colspan="3" align="center" style="font-weight:bold;"><a href="index.php?site=cup"><? echo $plugin_language['3rdplacematch'] ?></a></td>
    </tr>
    <tr>
		<td bgcolor="#adafae" align="center" width="30%">n/a</td>
		<td align="center" width="30%"><? echo $plugin_language['vs'] ?></td>
		<td bgcolor="#adafae" align="center" width="30%">n/a</td>
    </tr>
	<?php
    }
	?>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    
</table>
</marquee></div>
<div align="center"><a href="index.php?site=cup"><? echo $plugin_language['tothetournament'] ?></a></div>
</div></div>
