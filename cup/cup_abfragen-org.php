<?php
#   Copyright by mySEK.de; flanders
#	Support für das Modul gibt es auf www.mySEK.de

#defined ('main') or die ( 'no direct access' );

$version = '2.2';
$footer = 'Cupscript '.$version.' &copy; by <a href = "http://www.mySEK.de"><b>mySEK.de</b></a>';




$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 1"));
if (@$dx[ 'anordnung' ] != '1') {
$clan1_eg = "";
$clan1_bg = "";
} else {
$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 1");
$dc = mysqli_fetch_assoc($get);
@$clan1_name = $dc['name'];
@$clan1_id = $dc['cupID'];
@$clan1_teamid = $dc['teamid'];
#$clan1_hp = $dc['hp'];
@$clan1_clantag = $dc['clantag'];
@$clan1_eg = $dc['eg'];
@$clan1_bg = $dc['color'];

}

$clan2_name = "";
$clan2_eg = "";
$clan2_bg = "";

$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 2");
$dc = mysqli_fetch_assoc($get);
@$clan2_name = $dc['name'];
@$clan2_id = $dc['cupID'];
@$clan2_teamid = $dc['teamid'];
#$clan2_hp = $dc['hp'];
@$clan2_clantag = $dc['clantag'];
@$clan2_eg = $dc['eg'];
@$clan2_bg = $dc['color'];



$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 3"));
if (@$dx[ 'anordnung' ] != '3') {
$clan3_eg = "";
$clan3_bg = "";
} else {
$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 3");
$dc = mysqli_fetch_assoc($get);
@$clan3_name = $dc['name'];
@$clan3_id = $dc['cupID'];
@$clan3_teamid = $dc['teamid'];
#$clan3_hp = $dc['hp'];
@$clan3_clantag = $dc['clantag'];
@$clan3_eg = $dc['eg'];
@$clan3_bg = $dc['color'];

}

$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 4");
$dc = mysqli_fetch_assoc($get);
	
@$clan4_name = $dc['name'];
@$clan4_id = $dc['cupID'];
@$clan4_teamid = $dc['teamid'];
#$clan4_hp = $dc['hp'];
@$clan4_clantag = $dc['clantag'];
@$clan4_eg = $dc['eg'];
@$clan4_bg = $dc['color'];



$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 5");
$dc = mysqli_fetch_assoc($get);
	
@$clan5_name = $dc['name'];
@$clan5_id = $dc['cupID'];
@$clan5_teamid = $dc['teamid'];
#$clan5_hp = $dc['hp'];
@$clan5_clantag = $dc['clantag'];
@$clan5_eg = $dc['eg'];
@$clan5_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 6");
$dc = mysqli_fetch_assoc($get);
	
@$clan6_name = $dc['name'];
@$clan6_id = $dc['cupID'];
@$clan6_teamid = $dc['teamid'];
#$clan6_hp = $dc['hp'];
@$clan6_clantag = $dc['clantag'];
@$clan6_eg = $dc['eg'];
@$clan6_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 7");
$dc = mysqli_fetch_assoc($get);
	
@$clan7_name = $dc['name'];
@$clan7_id = $dc['cupID'];
@$clan7_teamid = $dc['teamid'];
#$clan7_hp = $dc['hp'];
@$clan7_clantag = $dc['clantag'];
@$clan7_eg = $dc['eg'];
@$clan7_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 8");
$dc = mysqli_fetch_assoc($get);
	
@$clan8_name = $dc['name'];
@$clan8_id = $dc['cupID'];
@$clan8_teamid = $dc['teamid'];
#$clan8_hp = $dc['hp'];
@$clan8_clantag = $dc['clantag'];
@$clan8_eg = $dc['eg'];
@$clan8_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 9");
$dc = mysqli_fetch_assoc($get);
	
@$clan9_name = $dc['name'];
@$clan9_id = $dc['cupID'];
@$clan9_teamid = $dc['teamid'];
#$clan9_hp = $dc['hp'];
@$clan9_clantag = $dc['clantag'];
@$clan9_eg = $dc['eg'];
@$clan9_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 10");
$dc = mysqli_fetch_assoc($get);
	
@$clan10_name = $dc['name'];
@$clan10_id = $dc['cupID'];
@$clan10_teamid = $dc['teamid'];
#$clan10_hp = $dc['hp'];
@$clan10_clantag = $dc['clantag'];
@$clan10_eg = $dc['eg'];
@$clan10_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 11");
$dc = mysqli_fetch_assoc($get);
	
@$clan11_name = $dc['name'];
@$clan11_id = $dc['cupID'];
@$clan11_teamid = $dc['teamid'];
#$clan11_hp = $dc['hp'];
@$clan11_clantag = $dc['clantag'];
@$clan11_eg = $dc['eg'];
@$clan11_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 12");
$dc = mysqli_fetch_assoc($get);
	
@$clan12_name = $dc['name'];
@$clan12_id = $dc['cupID'];
@$clan12_teamid = $dc['teamid'];
#$clan12_hp = $dc['hp'];
@$clan12_clantag = $dc['clantag'];
@$clan12_eg = $dc['eg'];
@$clan12_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 13");
$dc = mysqli_fetch_assoc($get);
	
@$clan13_name = $dc['name'];
@$clan13_id = $dc['cupID'];
@$clan13_teamid = $dc['teamid'];
#$clan13_hp = $dc['hp'];
@$clan13_clantag = $dc['clantag'];
@$clan13_eg = $dc['eg'];
@$clan13_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 14");
$dc = mysqli_fetch_assoc($get);
	
@$clan14_name = $dc['name'];
@$clan14_id = $dc['cupID'];
@$clan14_teamid = $dc['teamid'];
#$clan14_hp = $dc['hp'];
@$clan14_clantag = $dc['clantag'];
@$clan14_eg = $dc['eg'];
@$clan14_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 15");
$dc = mysqli_fetch_assoc($get);
	
@$clan15_name = $dc['name'];
@$clan15_id = $dc['cupID'];
@$clan15_teamid = $dc['teamid'];
#$clan15_hp = $dc['hp'];
@$clan15_clantag = $dc['clantag'];
@$clan15_eg = $dc['eg'];
@$clan15_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE anordnung = 16");
$dc = mysqli_fetch_assoc($get);
	
@$clan16_name = $dc['name'];
@$clan16_id = $dc['cupID'];
@$clan16_teamid = $dc['teamid'];
#$clan16_hp = $dc['hp'];
@$clan16_clantag = $dc['clantag'];
@$clan16_eg = $dc['eg'];
@$clan16_bg = $dc['color'];


// Viertelfinale

$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE viertel = 1 && (anordnung = 1 || anordnung = 2)");
$dc = mysqli_fetch_assoc($get);
	
@$clan_vor_1_name = $dc['name'];
@$clan_vor_1_id = $dc['cupID'];
@$clan_vor_1_teamid = $dc['teamid'];
#$clan_vor_1_hp = $dc['hp'];
@$clan_vor_1_clantag = $dc['clantag'];
@$clan_vor_1_ev = $dc['ev'];
@$clan_vor_1_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE viertel = 1 && (anordnung = 3 || anordnung = 4)");
$dc = mysqli_fetch_assoc($get);
	
@$clan_vor_2_name = $dc['name'];
@$clan_vor_2_id = $dc['cupID'];
@$clan_vor_2_teamid = $dc['teamid'];
#@$clan_vor_2_hp = $dc['hp'];
@$clan_vor_2_clantag = $dc['clantag'];
@$clan_vor_2_ev = $dc['ev'];
@$clan_vor_2_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE viertel = 1 && (anordnung = 5 || anordnung = 6)");
$dc = mysqli_fetch_assoc($get);
	
@$clan_vor_3_name = $dc['name'];
@$clan_vor_3_id = $dc['cupID'];
@$clan_vor_3_teamid = $dc['teamid'];
#@$clan_vor_3_hp = $dc['hp'];
@$clan_vor_3_clantag = $dc['clantag'];
@$clan_vor_3_ev = $dc['ev'];
@$clan_vor_3_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE viertel = 1 && (anordnung = 7 || anordnung = 8)");
$dc = mysqli_fetch_assoc($get);
	
@$clan_vor_4_name = $dc['name'];
@$clan_vor_4_id = $dc['cupID'];
@$clan_vor_4_teamid = $dc['teamid'];
#$clan_vor_4_hp = $dc['hp'];
@$clan_vor_4_clantag = $dc['clantag'];
@$clan_vor_4_ev = $dc['ev'];
@$clan_vor_4_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE viertel = 1 && (anordnung = 9 || anordnung = 10)");
$dc = mysqli_fetch_assoc($get);
	
@$clan_vor_5_name = $dc['name'];
@$clan_vor_5_id = $dc['cupID'];
@$clan_vor_5_teamid = $dc['teamid'];
#$clan_vor_5_hp = $dc['hp'];
@$clan_vor_5_clantag = $dc['clantag'];
@$clan_vor_5_ev = $dc['ev'];
@$clan_vor_5_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE viertel = 1 && (anordnung = 11 || anordnung = 12)");
$dc = mysqli_fetch_assoc($get);
	
@$clan_vor_6_name = $dc['name'];
@$clan_vor_6_id = $dc['cupID'];
@$clan_vor_6_teamid = $dc['teamid'];
#$clan_vor_6_hp = $dc['hp'];
@$clan_vor_6_clantag = $dc['clantag'];
@$clan_vor_6_ev = $dc['ev'];
@$clan_vor_6_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE viertel = 1 && (anordnung = 13 || anordnung = 14)");
$dc = mysqli_fetch_assoc($get);
	
@$clan_vor_7_name = $dc['name'];
@$clan_vor_7_id = $dc['cupID'];
@$clan_vor_7_teamid = $dc['teamid'];
#$clan_vor_7_hp = $dc['hp'];
@$clan_vor_7_clantag = $dc['clantag'];
@$clan_vor_7_ev = $dc['ev'];
@$clan_vor_7_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE viertel = 1 && (anordnung = 15 || anordnung = 16)");
$dc = mysqli_fetch_assoc($get);
	
@$clan_vor_8_name = $dc['name'];
@$clan_vor_8_id = $dc['cupID'];
@$clan_vor_8_teamid = $dc['teamid'];
#$clan_vor_8_hp = $dc['hp'];
@$clan_vor_8_clantag = $dc['clantag'];
@$clan_vor_8_ev = $dc['ev'];
@$clan_vor_8_bg = $dc['color'];


// Halbfinale
$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE halb = 1 && gruppe = 1");
$dc = mysqli_fetch_assoc($get);

@$clan_halb_1_name = $dc['name'];
@$clan_halb_1_id = $dc['cupID'];
@$clan_halb_1_teamid = $dc['teamid'];
#$clan_halb_1_hp = $dc['hp'];
@$clan_halb_1_clantag = $dc['clantag'];
@$clan_halb_1_eh = $dc['eh'];
@$clan_halb_1_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE halb = 1 && gruppe = 2");
$dc = mysqli_fetch_assoc($get);

@$clan_halb_2_name = $dc['name'];
@$clan_halb_2_id = $dc['cupID'];
@$clan_halb_2_teamid = $dc['teamid'];
#$clan_halb_2_hp = $dc['hp'];
@$clan_halb_2_clantag = $dc['clantag'];
@$clan_halb_2_eh = $dc['eh'];
@$clan_halb_2_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE halb = 1 && gruppe = 3");
$dc = mysqli_fetch_assoc($get);

@$clan_halb_3_name = $dc['name'];
@$clan_halb_3_id = $dc['cupID'];
@$clan_halb_3_teamid = $dc['teamid'];
#$clan_halb_3_hp = $dc['hp'];
@$clan_halb_3_clantag = $dc['clantag'];
@$clan_halb_3_eh = $dc['eh'];
@$clan_halb_3_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE halb = 1 && gruppe = 4");
$dc = mysqli_fetch_assoc($get);

@$clan_halb_4_name = $dc['name'];
@$clan_halb_4_id = $dc['cupID'];
@$clan_halb_4_teamid = $dc['teamid'];
#$clan_halb_4_hp = $dc['hp'];
@$clan_halb_4_clantag = $dc['clantag'];
@$clan_halb_4_eh = $dc['eh'];
@$clan_halb_4_bg = $dc['color'];


// Finale
$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE finale = 1 && (gruppe = 1 || gruppe = 2)");
$dc = mysqli_fetch_assoc($get);

@$clan_fin_1_name = $dc['name'];
@$clan_fin_1_id = $dc['cupID'];
@$clan_fin_1_teamid = $dc['teamid'];
#$clan_fin_1_hp = $dc['hp'];
@$clan_fin_1_clantag = $dc['clantag'];
@$clan_fin_1_ef = $dc['ef'];
@$clan_fin_1_bg = $dc['color'];


$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE finale = 1 && (gruppe = 3 || gruppe = 4)");
$dc = mysqli_fetch_assoc($get);

@$clan_fin_2_name = $dc['name'];
@$clan_fin_2_id = $dc['cupID'];
@$clan_fin_2_teamid = $dc['teamid'];
#$clan_fin_2_hp = $dc['hp'];
@$clan_fin_2_clantag = $dc['clantag'];
@$clan_fin_2_ef = $dc['ef'];
@$clan_fin_2_bg = $dc['color'];


// Winner
$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE p1 = 1");
$dc = mysqli_fetch_assoc($get);
$get = safe_query("SELECT preis1 FROM " . PREFIX . "plugins_cup_config");
$db = mysqli_fetch_assoc($get);

@$clan_winner_name = $dc['name'];
@$clan_winner_id = $dc['cupID'];
@$clan_winner_teamid = $dc['teamid'];
#$clan_winner_hp = $dc['hp'];
@$clan_winner_clantag = $dc['clantag'];
@$clan_winner_preis1 = $db['preis1'];
@$clan_winner_bg = $dc['color'];


// Platz 2
$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE finale = 1 AND p1 = 0");
$dc = mysqli_fetch_assoc($get);
$get = safe_query("SELECT preis2 FROM " . PREFIX . "plugins_cup_config");
$db = mysqli_fetch_assoc($get);

@$clan_platz2_name = $dc['name'];
@$clan_platz2_id = $dc['cupID'];
@$clan_platz2_teamid = $dc['teamid'];
#$clan_platz2_hp = $dc['hp'];
@$clan_platz2_clantag = $dc['clantag'];
@$clan_platz2_preis2 = $db['preis2'];
@$clan_platz2_bg = $dc['color'];


// Platz3
$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE halb = 1 AND finale = 0 && (gruppe = 1 || gruppe = 2)");
$dc = mysqli_fetch_assoc($get);
$get = safe_query("SELECT preis1 FROM " . PREFIX . "plugins_cup_config");
$db = mysqli_fetch_assoc($get);

@$clan_platz3_1_name = $dc['name'];
@$clan_platz3_1_id = $dc['cupID'];
@$clan_platz3_1_teamid = $dc['teamid'];
#$clan_platz3_1_hp = $dc['hp'];
@$clan_platz3_1_clantag = $dc['clantag'];
@$clan_platz3_1_ep3 = $dc['ep3'];
@$clan_platz3_1_preis3 = $db['preis1'];
@$clan_platz3_1_bg = $dc['color'];
   

$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE halb = 1 AND finale = 0 && (gruppe = 3 || gruppe = 4)");
$dc = mysqli_fetch_assoc($get);
$get = safe_query("SELECT preis3 FROM " . PREFIX . "plugins_cup_config");
$db = mysqli_fetch_assoc($get);

@$clan_platz3_2_name = $dc['name'];
@$clan_platz3_2_id = $dc['cupID'];
@$clan_platz3_2_teamid = $dc['teamid'];
#$clan_platz3_2_hp = $dc['hp'];
@$clan_platz3_2_clantag = $dc['clantag'];
@$clan_platz3_2_ep3 = $dc['ep3'];
@$clan_platz3_2_preis3 = $db['preis3'];
@$clan_platz3_2_bg = $dc['color'];


// Platz3 Winnner
$get = safe_query("SELECT *  FROM " . PREFIX . "plugins_cup_teams WHERE p3 = 1");
$dc = mysqli_fetch_assoc($get);
$get = safe_query("SELECT preis3 FROM " . PREFIX . "plugins_cup_config");
$db = mysqli_fetch_assoc($get);

@$clan_platz3_winner_name = $dc['name'];
@$clan_platz3_winner_id = $dc['cupID'];
@$clan_platz3_winner_teamid = $dc['teamid'];
#$clan_platz3_winner_hp = $dc['hp'];
@$clan_platz3_winner_clantag = $dc['clantag'];
@$clan_platz3_winner_preis3 = $db['preis3'];
@$clan_platz3_winner_bg = $dc['color'];


// Config
$get = safe_query("SELECT * FROM " . PREFIX . "plugins_cup_config");
$dx = mysqli_fetch_assoc($get);

@$gruppe = $dx['gruppe'];
@$register = $dx['register'];
@$turnier = $dx['turnier'];
@$preis1 = $dx['preis1'];
@$preis2 = $dx['preis2'];
@$preis3 = $dx['preis3'];

?>