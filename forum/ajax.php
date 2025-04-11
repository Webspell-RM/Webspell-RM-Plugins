<?php
include_once("../../../system/sql.php");
include_once("../../../system/settings.php");
//include_once("../../../system/functions.php");

if ($_GET['action'] == 'votebox') {
	global $loggedin;
	$loggedin = $_GET['loggedin'];
	$userID = $_GET['userID'];


	if ($loggedin) {

		$topic = $_GET['topic'];

		if (!mysqli_num_rows(safe_query("SELECT * FROM " . PREFIX . "plugins_forum_votes WHERE userID=" . $userID . " AND topicID=" . $topic . ""))) {

			if ($_GET['v'] == 1) {
				$value1 = 1;
			} else {
				$value1 = 0;
			}
			if ($_GET['v'] == 2) {
				$value2 = 1;
			} else {
				$value2 = 0;
			}
			if ($_GET['v'] == 3) {
				$value3 = 1;
			} else {
				$value3 = 0;
			}
			if ($_GET['v'] == 4) {
				$value4 = 1;
			} else {
				$value4 = 0;
			}
			if ($_GET['v'] == 5) {
				$value5 = 1;
			} else {
				$value5 = 0;
			}

			safe_query("INSERT INTO " . PREFIX . "plugins_forum_votes (topicID, userID, value1, value2, value3, value4, value5) VALUES ('$topic', '$userID', '$value1', '$value2', '$value3', '$value4', '$value5')");


			$ergebnis2 = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_poll WHERE poll='1' AND topicID='$_GET[topic]'");
			while ($dd = mysqli_fetch_array($ergebnis2)) {

				$erga = mysqli_fetch_array(safe_query("SELECT count(*) FROM " . PREFIX . "plugins_forum_votes WHERE value1>0 AND topicID='$_GET[topic]'"));
				$ergb = mysqli_fetch_array(safe_query("SELECT count(*) FROM " . PREFIX . "plugins_forum_votes WHERE value2>0 AND topicID='$_GET[topic]'"));
				$ergc = mysqli_fetch_array(safe_query("SELECT count(*) FROM " . PREFIX . "plugins_forum_votes WHERE value3>0 AND topicID='$_GET[topic]'"));
				$ergd = mysqli_fetch_array(safe_query("SELECT count(*) FROM " . PREFIX . "plugins_forum_votes WHERE value4>0 AND topicID='$_GET[topic]'"));
				$erge = mysqli_fetch_array(safe_query("SELECT count(*) FROM " . PREFIX . "plugins_forum_votes WHERE value5>0 AND topicID='$_GET[topic]'"));

				$ergf = mysqli_fetch_array(safe_query("SELECT count(*) FROM " . PREFIX . "plugins_forum_votes WHERE topicID='$_GET[topic]'"));
				$gesamt = $ergf['count(*)'];

				$title = $dd['title'];

				if (!empty($dd['value1'])) {
					$bg = "class=\"forum_poll_bg1\"";
					$value = $dd['value1'];
					$points = $erga['count(*)'];
					$bar = $points / $gesamt * 100;
					$bar = round($bar, 2);
					$barimg = 'bar1.png';

					//$template = $GLOBALS["_template"]->loadTemplate("forum_topic","vote", $data_array, $plugin_path);
					//echo $template;

				} else {
					$value1 = '';
				}

				if (!empty($dd['value2'])) {
					$bg = "class=\"forum_pol2_bg1\"";
					$value = $dd['value2'];
					$points = $ergb['count(*)'];
					$bar = $points / $gesamt * 100;
					$bar = round($bar, 2);
					$barimg = 'bar2.png';

					//$template = $GLOBALS["_template"]->loadTemplate("forum_topic","vote", $data_array, $plugin_path);
					//echo $template;
				} else {
					$value2 = '';
				}

				if (!empty($dd['value3'])) {
					$bg = "class=\"forum_poll_bg1\"";
					$value = $dd['value3'];
					$points = $ergc['count(*)'];
					$bar = $points / $gesamt * 100;
					$bar = round($bar, 2);
					$barimg = 'bar3.png';

					//$template = $GLOBALS["_template"]->loadTemplate("forum_topic","vote", $data_array, $plugin_path);
					//echo $template;
				} else {
					$value3 = '';
				}

				if (!empty($dd['value4'])) {
					$bg = "class=\"forum_pol2_bg1\"";
					$value = $dd['value4'];
					$points = $ergd['count(*)'];
					$bar = $points / $gesamt * 100;
					$bar = round($bar, 2);
					$barimg = 'bar4.png';

					//$template = $GLOBALS["_template"]->loadTemplate("forum_topic","vote", $data_array, $plugin_path);
					//echo $template;

				} else {
					$value4 = '';
				}

				if (!empty($dd['value5'])) {
					$bg = "class=\"forum_poll_bg1\"";
					$value = $dd['value5'];
					$points = $erge['count(*)'];
					$bar = $points / $gesamt * 100;
					$bar = round($bar, 2);
					$barimg = 'bar5.png';

					//$template = $GLOBALS["_template"]->loadTemplate("forum_topic","vote", $data_array, $plugin_path);
					//echo $template;
				} else {
					$value5 = '';
				}

				//$template = $GLOBALS["_template"]->loadTemplate("forum_topic","topic_poll", $data_array, $plugin_path);
				//echo $template;
				echo 'Vote true';
			}
		} else {
			echo 'You have already voted for this poll!';
		}
	} else {
		echo 'Access denied.';
	}
} elseif ($_GET['action'] == 'thankbox') {
	global $loggedin;
	$loggedin = $_GET['loggedin'];
	$userID = $_GET['userID'];

	if ($loggedin) {
		$postID = $_GET['topic'];
		$ds = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_forum_posts WHERE postID='" . $postID . "'"));

		if ($_GET['v'] == 1) {
			$ds = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_forum_posts WHERE postID='" . $postID . "'"));
			safe_query("UPDATE " . PREFIX . "plugins_forum_posts SET thank='" . $ds['thank'] . "#" . $userID . "' WHERE postID='" . $postID . "'");
		} else {
			$ds = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "plugins_forum_posts WHERE postID='" . $postID . "'"));
			$user = explode("#", $ds['thank']);
			foreach ($user as $del) {
				if ($del != $userID) $newuser[] = $del;
			}
			if (is_array($newuser)) $newuser_string = implode("#", $newuser);
			safe_query("UPDATE " . PREFIX . "plugins_forum_posts SET thank='" . $newuser_string . "' WHERE postID='" . $postID . "'");
			echo 'saved';
		}
	} else {
		echo 'Access denied.';
	}
}
