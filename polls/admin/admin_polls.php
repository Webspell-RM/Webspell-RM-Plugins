<?php

/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                  Webspell-RM      /                        /   /                                          *
 *                  -----------__---/__---__------__----__---/---/-----__---- _  _ -                         *
 *                   | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                          *
 *                  _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                          *
 *                               Free Content / Management System                                            *
 *                                           /                                                               *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         webspell-rm                                                                              *
 *                                                                                                           *
 * @copyright       2018-2023 by webspell-rm.de                                                              *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de                 *
 * @website         <https://www.webspell-rm.de>                                                             *
 * @forum           <https://www.webspell-rm.de/forum.html>                                                  *
 * @wiki            <https://www.webspell-rm.de/wiki.html>                                                   *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                         *
 *                  It's NOT allowed to remove this copyright-tag                                            *
 *                  <http://www.fsf.org/licensing/licenses/gpl.html>                                         *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                        *
 * @copyright       2005-2011 by webspell.org / webspell.info                                                *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 */
# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager();
$plugin_language = $pm->plugin_language("admin_polls", $plugin_path);

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "navigation_dashboard_links WHERE modulname='forum'");
while ($db = mysqli_fetch_array($ergebnis)) {
	$accesslevel = 'is' . $db['accesslevel'] . 'admin';

	if (!$accesslevel($userID) || mb_substr(basename($_SERVER['REQUEST_URI']), 0, 15) != "admincenter.php") {
		die($_lang['access_denied']);
	}
}

if (isset($_POST['save'])) {
	$CAPCLASS = new \webspell\Captcha;
	if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
		$runtime = strtotime($_POST['runtime']);

		safe_query(
			"INSERT INTO
				" . PREFIX . "plugins_polls (
					aktiv,
					titel,
					description,
					o1,
					o2,
					o3,
					o4,
					o5,
					o6,
					o7,
					o8,
					o9,
					o10,
					comments,
					laufzeit,
					intern,
					published
				)
			values(
				'1',
				'" . $_POST['title'] . "',
				'" . $_POST['description'] . "',
				'" . $_POST['op1'] . "',
				'" . $_POST['op2'] . "',
				'" . $_POST['op3'] . "',
				'" . $_POST['op4'] . "',
				'" . $_POST['op5'] . "',
				'" . $_POST['op6'] . "',
				'" . $_POST['op7'] . "',
				'" . $_POST['op8'] . "',
				'" . $_POST['op9'] . "',
				'" . $_POST['op10'] . "',
				'" . $_POST['comments'] . "',
				'" . $runtime . "',
				'" . $_POST['intern'] . "',
				'" . $_POST['published'] . "'
			)"
		);
		$id = mysqli_insert_id($_database);

		safe_query(
			"INSERT INTO
				" . PREFIX . "plugins_polls_votes (pollID, o1, o2, o3, o4, o5, o6, o7, o8, o9, o10)
				values( '$id', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0' )"
		);
		#redirect("admincenter.php?site=admin_polls", generateSuccessBox($plugin_language[ 'poll_saved' ], $plugin_language[ 'success' ],  true), 3);
		redirect("admincenter.php?site=admin_polls", "", 0);
	} else {
		redirect("admincenter.php?site=admin_polls&amp;action=add", generateErrorBox($plugin_language['transaction_invalid'], $plugin_language['alert'],  true), 3);
	}
} elseif (isset($_POST['saveedit'])) {
	$CAPCLASS = new \webspell\Captcha;
	if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
		$pollID = $_POST['pollID'];
		$runtime = strtotime($_POST['runtime']);

		if (!empty($_POST['reset'] == 1) !== false) {
			safe_query("DELETE FROM " . PREFIX . "plugins_polls WHERE pollID='$pollID'");
			safe_query("DELETE FROM " . PREFIX . "plugins_polls_votes WHERE pollID='$pollID'");

			safe_query(
				"INSERT INTO
					" . PREFIX . "plugins_polls (
						aktiv,
						titel,
						description,
						o1,
						o2,
						o3,
						o4,
						o5,
						o6,
						o7,
						o8,
						o9,
						o10,
						comments,
						laufzeit,
						intern,
						published
					)
				values(
					'1',
					'" . $_POST['title'] . "',
					'" . $_POST['description'] . "',
					'" . $_POST['op1'] . "',
					'" . $_POST['op2'] . "',
					'" . $_POST['op3'] . "',
					'" . $_POST['op4'] . "',
					'" . $_POST['op5'] . "',
					'" . $_POST['op6'] . "',
					'" . $_POST['op7'] . "',
					'" . $_POST['op8'] . "',
					'" . $_POST['op9'] . "',
					'" . $_POST['op10'] . "',
					'" . $_POST['comments'] . "',
					'" . $runtime . "',
					'" . $_POST['intern'] . "',
					'" . $_POST['published'] . "'
				)"
			);
			$id = mysqli_insert_id($_database);
			safe_query(
				"INSERT INTO
					" . PREFIX . "plugins_polls_votes (
						pollID,
						o1,
						o2,
						o3,
						o4,
						o5,
						o6,
						o7,
						o8,
						o9,
						o10
					)
					values(
						'" . (int)$id . "',
						'0',
						'0',
						'0',
						'0',
						'0',
						'0',
						'0',
						'0',
						'0',
						'0'
					)"
			);
		} else {
			safe_query(
				"UPDATE
					" . PREFIX . "plugins_polls
				SET
					titel='" . $_POST['title'] . "',
					description='" . $_POST['description'] . "',
					o1='" . $_POST['op1'] . "',
					o2='" . $_POST['op2'] . "',
					o3='" . $_POST['op3'] . "',
					o4='" . $_POST['op4'] . "',
					o5='" . $_POST['op5'] . "',
					o6='" . $_POST['op6'] . "',
					o7='" . $_POST['op7'] . "',
					o8='" . $_POST['op8'] . "',
					o9='" . $_POST['op9'] . "',
					o10='" . $_POST['op10'] . "',
					comments = '" . $_POST['comments'] . "',
					laufzeit = '" . $runtime . "',
					intern = '" . $_POST['intern'] . "',
					published = '" . $_POST['published'] . "'
				WHERE
					pollID='" . (int)$pollID . "'"
			);
		}
		#redirect("admincenter.php?site=admin_polls", $plugin_language[ 'success' ],  3);
		redirect("admincenter.php?site=admin_polls", "", 0);
	} else {
		redirect("admincenter.php?site=admin_polls", generateErrorBox($plugin_language['transaction_invalid'], $plugin_language['alert'],  true), 3);
	}
} elseif (isset($_GET['delete'])) {
	$CAPCLASS = new \webspell\Captcha;
	if ($CAPCLASS->checkCaptcha(0, $_GET['captcha_hash'])) {
		$pollID = intval($_GET['pollID']);
		safe_query("DELETE FROM " . PREFIX . "plugins_polls WHERE pollID = '" . $pollID . "'");
		safe_query("DELETE FROM " . PREFIX . "plugins_polls_votes WHERE pollID = '" . $pollID . "'");
		#redirect("admincenter.php?site=admin_polls", generateSuccessBox($plugin_language[ 'poll_deleted' ], $plugin_language[ 'success' ],  true), 3);
		redirect("admincenter.php?site=admin_polls", "", 0);
	} else {
		redirect("admincenter.php?site=admin_polls", generateErrorBox($plugin_language['transaction_invalid'], $plugin_language['alert'],  true), 3);
	}
} elseif (isset($_GET['reopen'])) {
	$CAPCLASS = new \webspell\Captcha;
	if ($CAPCLASS->checkCaptcha(0, $_GET['captcha_hash'])) {
		$pollID = intval($_GET['pollID']);
		safe_query("UPDATE " . PREFIX . "plugins_polls SET aktiv='1' WHERE pollID='" . $pollID . "'");
		#redirect("admincenter.php?site=admin_polls", generateSuccessBox($plugin_language[ 'poll_reopened' ], $plugin_language[ 'success' ],  true), 3);
		redirect("admincenter.php?site=admin_polls", "", 0);
	} else {
		redirect("admincenter.php?site=admin_polls", generateErrorBox($plugin_language['transaction_invalid'], $plugin_language['alert'],  true), 3);
	}
} elseif (isset($_GET['end'])) {
	$CAPCLASS = new \webspell\Captcha;
	if ($CAPCLASS->checkCaptcha(0, $_GET['captcha_hash'])) {
		$pollID = intval($_GET['pollID']);
		safe_query("UPDATE " . PREFIX . "plugins_polls SET aktiv='0' WHERE pollID='" . $pollID . "'");
		#redirect("admincenter.php?site=admin_polls", generateSuccessBox($plugin_language[ 'poll_ended' ], $plugin_language[ 'success' ],  true), 3);
		redirect("admincenter.php?site=admin_polls", "", 0);
	} else {
		redirect("admincenter.php?site=admin_polls", generateErrorBox($plugin_language['transaction_invalid'], $plugin_language['alert'],  true), 3);
	}
}

if (isset($_GET['action'])) {
	$action = $_GET['action'];
} else {
	$action = '';
}

if ($action == "add") {
	$CAPCLASS = new \webspell\Captcha;
	$CAPCLASS->createTransaction();
	$hash = $CAPCLASS->getHash();

	$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_polls");
	$ds = mysqli_fetch_array($ergebnis);

	if ($ds['published'] = "1") {
		$published = '<option value="1" selected="selected">' . $plugin_language['yes'] . '</option>
							<option value="0">' . $plugin_language['no'] . '</option>';
	} else {
		$published = '<option value="1">' . $plugin_language['yes'] . '</option>
							<option value="0" selected="selected">' . $plugin_language['no'] . '</option>';
	}

	$comments = '<option value="0">' . $plugin_language['disable_comments'] . '</option>
                     <option value="1">' . $plugin_language['enable_user_comments'] . '</option>
                     <option value="2" selected="selected">' . $plugin_language['enable_visitor_comments'] . '</option>';


	$data_array = array();
	$data_array['$hash'] = $hash;
	$data_array['$published'] = $published;
	$data_array['$comments'] = $comments;
	$data_array['$polls'] = $plugin_language['polls'];
	$data_array['$add_poll'] = $plugin_language['add_poll'];
	$data_array['$lang_title'] = $plugin_language['title'];
	$data_array['$lang_description'] = $plugin_language['description'];
	$data_array['$placeholder_title'] = $plugin_language['placeholder_title'];
	$data_array['$endingtime'] = $plugin_language['endingtime'];
	$data_array['$example'] = $plugin_language['example'];
	$data_array['$option'] = $plugin_language['option'];
	$data_array['$lang_comments'] = $plugin_language['comments'];
	$data_array['$disable_comments'] = $plugin_language['disable_comments'];
	$data_array['$enable_user_comments'] = $plugin_language['enable_user_comments'];
	$data_array['$enable_visitor_comments'] = $plugin_language['enable_visitor_comments'];
	$data_array['$lang_intern'] = $plugin_language['intern'];
	$data_array['$no'] = $plugin_language['no'];
	$data_array['$yes'] = $plugin_language['yes'];
	$data_array['$lang_published'] = $plugin_language['published'];

	$data_array['$yht_enter_title'] = $plugin_language['yht_enter_title'];
	$data_array['$yht_enter_2_options'] = $plugin_language['yht_enter_2_options'];
	$data_array['$yht_enter_2_options'] = $plugin_language['yht_enter_2_options'];


	$template = $GLOBALS["_template"]->loadTemplate("admin_polls", "add", $data_array, $plugin_path);
	echo $template;
} elseif ($action == "edit") {
	$pollID = $_GET['pollID'];
	$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_polls WHERE pollID='$pollID'"));

	$CAPCLASS = new \webspell\Captcha;
	$CAPCLASS->createTransaction();
	$hash = $CAPCLASS->getHash();


	$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_polls WHERE pollID='" . $pollID . "'");
	$ds = mysqli_fetch_array($ergebnis);

	$runtime = date("d.m.Y H:i", $ds['laufzeit']);
	$comments = '<option value="0">' . $plugin_language['disable_comments'] . '</option>
				 <option value="1">' . $plugin_language['enable_user_comments'] . '</option>
				 <option value="2">' . $plugin_language['enable_visitor_comments'] . '</option>';

	$comments =
		str_replace(
			'value="' . $dx['comments'] . '"',
			'value="' . $dx['comments'] . '" selected="selected"',
			$comments
		);

	if ($ds['intern'] == "1") {
		$intern = '<option value="1" selected="selected">' . $plugin_language['yes'] . '</option>
							<option value="0">' . $plugin_language['no'] . '</option>';
	} else {
		$intern = '<option value="1">' . $plugin_language['yes'] . '</option>
							<option value="0" selected="selected">' . $plugin_language['no'] . '</option>';
	}



	if ($ds['published'] == "1") {
		$published = '<option value="1" selected="selected">' . $plugin_language['yes'] . '</option>
							<option value="0">' . $plugin_language['no'] . '</option>';
	} else {
		$published = '<option value="1">' . $plugin_language['yes'] . '</option>
							<option value="0" selected="selected">' . $plugin_language['no'] . '</option>';
	}
	#$test=$_POST[ 'reset' ];
	print_r($comments);
	$input_title = getinput($ds['titel']);
	$description = getinput($ds['description']);
	$input_o1 = getinput($ds['o1']);
	$input_o2 = getinput($ds['o2']);
	$input_o3 = getinput($ds['o3']);
	$input_o4 = getinput($ds['o4']);
	$input_o5 = getinput($ds['o5']);
	$input_o6 = getinput($ds['o6']);
	$input_o7 = getinput($ds['o7']);
	$input_o8 = getinput($ds['o8']);
	$input_o9 = getinput($ds['o9']);
	$input_o10 = getinput($ds['o10']);

	$data_array = array();
	$data_array['$hash'] = $hash;
	$data_array['$input_o1'] = $input_o1;
	$data_array['$input_o2'] = $input_o2;
	$data_array['$input_o3'] = $input_o3;
	$data_array['$input_o4'] = $input_o4;
	$data_array['$input_o5'] = $input_o5;
	$data_array['$input_o6'] = $input_o6;
	$data_array['$input_o7'] = $input_o7;
	$data_array['$input_o8'] = $input_o8;
	$data_array['$input_o9'] = $input_o9;
	$data_array['$input_o10'] = $input_o10;
	$data_array['$input_title'] = $input_title;
	$data_array['$published'] = $published;
	$data_array['$intern'] = $intern;
	$data_array['$pollID'] = $pollID;
	$data_array['$runtime'] = $runtime;
	$data_array['$description'] = $description;
	$data_array['$comments'] = $comments;


	$data_array['$polls'] = $plugin_language['polls'];
	$data_array['$lang_title'] = $plugin_language['title'];
	$data_array['$lang_description'] = $plugin_language['description'];
	$data_array['$endingtime'] = $plugin_language['endingtime'];
	$data_array['$example'] = $plugin_language['example'];
	$data_array['$option'] = $plugin_language['option'];
	$data_array['$lang_comments'] = $plugin_language['comments'];
	$data_array['$disable_comments'] = $plugin_language['disable_comments'];
	$data_array['$enable_user_comments'] = $plugin_language['enable_user_comments'];
	$data_array['$enable_visitor_comments'] = $plugin_language['enable_visitor_comments'];
	$data_array['$lang_intern'] = $plugin_language['intern'];
	$data_array['$no'] = $plugin_language['no'];
	$data_array['$yes'] = $plugin_language['yes'];
	$data_array['$lang_published'] = $plugin_language['published'];
	$data_array['$edit_poll'] = $plugin_language['edit_poll'];
	$data_array['$reset_votes'] = $plugin_language['reset_votes'];

	$data_array['$yht_enter_title'] = $plugin_language['yht_enter_title'];
	$data_array['$yht_enter_2_options'] = $plugin_language['yht_enter_2_options'];
	$data_array['$yht_enter_2_options'] = $plugin_language['yht_enter_2_options'];

	$template = $GLOBALS["_template"]->loadTemplate("admin_polls", "edit", $data_array, $plugin_path);
	echo $template;
} else {

	$data_array = array();
	$data_array['$polls'] = $plugin_language['polls'];
	$data_array['$new_poll'] = $plugin_language['new_poll'];
	$data_array['$endingtime'] = $plugin_language['endingtime'];
	$data_array['$title'] = $plugin_language['title'];
	$data_array['$votes'] = $plugin_language['votes'];
	$data_array['$published'] = $plugin_language['published'];
	$data_array['$actions'] = $plugin_language['actions'];
	$data_array['$options'] = $plugin_language['options'];

	$template = $GLOBALS["_template"]->loadTemplate("admin_polls", "head", $data_array, $plugin_path);
	echo $template;

	$CAPCLASS = new \webspell\Captcha;
	$CAPCLASS->createTransaction();
	$hash = $CAPCLASS->getHash();

	$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_polls ORDER BY pollID DESC");
	$anz = mysqli_num_rows($ergebnis);
	if ($anz) {
		$i = 1;
		while ($ds = mysqli_fetch_array($ergebnis)) {
			if ($ds['aktiv']) {
				$actions = '<a href="admincenter.php?site=admin_polls&amp;end=true&amp;pollID=' . $ds['pollID'] . '&amp;captcha_hash=' . $hash . '" class="btn btn-info">' . $plugin_language['stop_poll'] . '</a>';
			} else {
				$actions = '<a href="admincenter.php?site=admin_polls&amp;reopen=true&amp;pollID=' . $ds['pollID'] . '&amp;captcha_hash=' . $hash . '" class="btn btn-success">' . $plugin_language['reopen_poll'] . '</a>';
			}
			$votes = safe_query("SELECT * FROM " . PREFIX . "plugins_polls_votes WHERE pollID='" . $ds['pollID'] . "'");
			$dv = mysqli_fetch_array($votes);
			$gesamtstimmen = $dv['o1'] + $dv['o2'] + $dv['o3'] + $dv['o4'] + $dv['o5'] + $dv['o6'] + $dv['o7'] + $dv['o8'] + $dv['o9'] + $dv['o10'];
			$button_edit = '<a href="admincenter.php?site=admin_polls&amp;action=edit&amp;pollID=' . $ds['pollID'] . '" class="btn btn-warning">' . $plugin_language['edit'] . '</a>';

			$button_delete = '<!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_polls&amp;delete=true&amp;pollID=' . $ds['pollID'] . '&amp;captcha_hash=' . $hash . '">
    ' . $plugin_language['delete'] . '
    </button>
    <!-- Button trigger modal END-->

     <!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language['polls'] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language['close'] . '"></button>
      </div>
      <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language['close'] . '</button>
        <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal END -->

';


			$laufzeit = getformatdatetime($ds['laufzeit']);
			$pollID = $ds['pollID'];
			$title = $ds['titel'];
			$ds['published'] == 1 ? $published = '<span class="text-green">' . $plugin_language['yes'] . '</span>' : $published = '<span class="text-red">' . $plugin_language['no'] . '</span>';


			$data_array = array();
			$data_array['$actions'] = $actions;
			$data_array['$button_delete'] = $button_delete;
			$data_array['$button_edit'] = $button_edit;
			$data_array['$laufzeit'] = $laufzeit;
			$data_array['$published'] = $published;
			$data_array['$gesamtstimmen'] = $gesamtstimmen;
			$data_array['$pollID'] = $pollID;
			$data_array['$title'] = $title;

			$template = $GLOBALS["_template"]->loadTemplate("admin_polls", "content", $data_array, $plugin_path);
			echo $template;
			$i++;
		}
	} else {
		$data_array = array();
		$data_array['$no_entries'] = $plugin_language['no_entries'];

		$template = $GLOBALS["_template"]->loadTemplate("admin_polls", "content-no", $data_array, $plugin_path);
		echo $template;
	}

	$template = $GLOBALS["_template"]->loadTemplate("admin_polls", "foot", $data_array, $plugin_path);
	echo $template;
}
