/*#################################################################\
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
\##################################################################*/
// Create new XMLHttpRequest
function Ajax() {
	var AJAX = null;
	if(window.XMLHttpRequest) AJAX = new XMLHttpRequest();
	else if(window.ActiveXObject) {
		try {
			AJAX = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(ex) {
			try {
				AJAX = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(ex) {
			}
		}
	}
	return AJAX;
}

// Create Map-SelectMenu
MapSelect = new Ajax();

function GetMapSelect(game) {
	if(game) {
		MapSelect.open("POST", 'includes/plugins/fightus/fightus_functions.php', true);
		MapSelect.onreadystatechange = ShowMapSelect;
		MapSelect.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		MapSelect.send("action=getmapselect&game=" + game);
	}
}
function ShowMapSelect() {
	if(MapSelect.readyState == 4) {
		document.getElementById("mapselect").innerHTML = MapSelect.responseText;
	}
	else {
		document.getElementById("mapselect").innerHTML = 'Matching maps are being searched...';
	}

}
// Create Home-Map-SelectMenu
MapsSelect = new Ajax();

function GetMapsSelect(game) {
	if(game) {
		MapsSelect.open("POST", 'includes/plugins/fightus/fightus_functions.php', true);
		MapsSelect.onreadystatechange = ShowMapsSelect;
		MapsSelect.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		MapsSelect.send("action=getmapsselect&game=" + game);
	}
}
function ShowMapsSelect() {
	if(MapsSelect.readyState == 4) {
		document.getElementById("mapsselect").innerHTML = MapsSelect.responseText;
	}
	else {
		document.getElementById("mapsselect").innerHTML = 'Matching maps are being searched...';
	}

}
// Create Spiel-SelectMenu
MSelect = new Ajax();

function GetMSelect(game) {
	if(game) {
		MSelect.open("POST", 'includes/plugins/fightus/fightus_functions.php', true);
		MSelect.onreadystatechange = ShowMSelect;
		MSelect.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		MSelect.send("action=getspielselect&game=" + game);
	}
}
function ShowMSelect() {
	if(MSelect.readyState == 4) {
		document.getElementById("mselect").innerHTML = MSelect.responseText;
	}
	else {
		document.getElementById("mselect").innerHTML = 'is wanted...';
	}

}
// Create Spiel-SelectMenu
GameSelect = new Ajax();

function GetGameSelect(game) {
	if(game) {
		GameSelect.open("POST", 'includes/plugins/fightus/fightus_functions.php', true);
		GameSelect.onreadystatechange = ShowGameSelect;
		GameSelect.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		GameSelect.send("action=getgameselect&game=" + game);
	}
}
function ShowGameSelect() {
	if(GameSelect.readyState == 4) {
		document.getElementById("gameselect").innerHTML = GameSelect.responseText;
	}
	else {
		document.getElementById("gameselect").innerHTML = 'Searching for game type...';
	}

}

// Create Match-SelectMenu
MatchSelect = new Ajax();

function GetMatchSelect(Match) {
	if(Match) {
		MatchSelect.open("POST", 'includes/plugins/fightus/fightus_functions.php', true);
		MatchSelect.onreadystatechange = ShowMatchSelect;
		MatchSelect.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		MatchSelect.send("action=getmatchselect&game=" + Match);
	}
}
function ShowMatchSelect() {
	if(MatchSelect.readyState == 4) {
		document.getElementById("matchselect").innerHTML = MatchSelect.responseText;
	}
	else {
		document.getElementById("matchselect").innerHTML = 'Match type is being searched...';
	}

}
