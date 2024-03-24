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
OpponentSelect = new Ajax();
function GetOpponentSelect(game) {
  if(game) {
    OpponentSelect.open("POST", '../includes/plugins/clanwars/_functions.php', true);
    OpponentSelect.onreadystatechange = ShowOpponentSelect;
    OpponentSelect.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    OpponentSelect.send("action=getopponentselect&game=" + game);
  }
}
function ShowOpponentSelect() {
  if(OpponentSelect.readyState == 4) {
    document.getElementById("opponentselect").innerHTML = OpponentSelect.responseText;
  }  else  {
    document.getElementById("opponentselect").innerHTML = 'Opponent werden gesucht...';
  }
}

OppTagSelect = new Ajax();
function GetOppTagSelect(game) {
  if(game) {
    OppTagSelect.open("POST", '../includes/plugins/clanwars/_functions.php', true);
    OppTagSelect.onreadystatechange = ShowOppTagSelect;
    OppTagSelect.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    OppTagSelect.send("action=getopptagselect&game=" + game);
  }
}
function ShowOppTagSelect() {
  if(OppTagSelect.readyState == 4) {
    document.getElementById("opptagselect").innerHTML = OppTagSelect.responseText;
  }  else  {
    document.getElementById("opptagselect").innerHTML = 'Opponenttag werden gesucht...';
  }
}

OppHPSelect = new Ajax();
function GetOppHPSelect(game) {
  if(game) {
    OppHPSelect.open("POST", '../includes/plugins/clanwars/_functions.php', true);
    OppHPSelect.onreadystatechange = ShowOppHPSelect;
    OppHPSelect.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    OppHPSelect.send("action=getopphpselect&game=" + game);
  }
}
function ShowOppHPSelect() {
  if(OppHPSelect.readyState == 4) {
    document.getElementById("opphpselect").innerHTML = OppHPSelect.responseText;
  }  else  {
    document.getElementById("opphpselect").innerHTML = 'GegnerHP wird gesucht...';
  }
}


OppleagueSelect = new Ajax();
function GetOppleagueSelect(game) {
  if(game) {
    OppleagueSelect.open("POST", '../includes/plugins/clanwars/_functions.php', true);
    OppleagueSelect.onreadystatechange = ShowOppleagueSelect;
    OppleagueSelect.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    OppleagueSelect.send("action=getoppleagueselect&game=" + game);
  }
}
function ShowOppleagueSelect() {
  if(OppleagueSelect.readyState == 4) {
    document.getElementById("oppleagueselect").innerHTML = OppleagueSelect.responseText;
  }  else  {
    document.getElementById("oppleagueselect").innerHTML = 'Liga wird gesucht...';
  }
}


OppleaguehpSelect = new Ajax();
function GetOppleaguehpSelect(game) {
  if(game) {
    OppleaguehpSelect.open("POST", '../includes/plugins/clanwars/_functions.php', true);
    OppleaguehpSelect.onreadystatechange = ShowOppleaguehpSelect;
    OppleaguehpSelect.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    OppleaguehpSelect.send("action=getoppleaguehpselect&game=" + game);
  }
}
function ShowOppleaguehpSelect() {
  if(OppleaguehpSelect.readyState == 4) {
    document.getElementById("oppleaguehpselect").innerHTML = OppleaguehpSelect.responseText;
  }  else  {
    document.getElementById("oppleaguehpselect").innerHTML = 'LigaHP wird gesucht...';
  }
}
