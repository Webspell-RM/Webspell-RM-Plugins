<?php
global $str,$modulname,$version;
$modulname='game_server';
$version='0.1';
$str='Game Server';
echo "<div class='card'><div class='card-header'>$str Database Installation</div><div class='card-body'>";
#######################################################################################################################################
$transaction = '';

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_game_server` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `c_port` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `q_port` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `s_port` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `zone` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `cache` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `cache_time` text COLLATE utf8_unicode_ci NOT NULL,
PRIMARY KEY (`id`)
) AUTO_INCREMENT=4
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_game_server` (`id`, `type`, `ip`, `c_port`, `q_port`, `s_port`, `zone`, `disabled`, `comment`, `status`, `cache`, `cache_time`) VALUES
(1, 'callofdutyuo', '85.14.228.228', '28960', '28960', '10', '0', 0, '', 1, 'YTo1OntzOjE6ImIiO2E6Nzp7czo0OiJ0eXBlIjtzOjEyOiJjYWxsb2ZkdXR5dW8iO3M6MjoiaXAiO3M6MTM6Ijg1LjE0LjIyOC4yMjgiO3M6NjoiY19wb3J0IjtzOjU6IjI4OTYwIjtzOjY6InFfcG9ydCI7czo1OiIyODk2MCI7czo2OiJzX3BvcnQiO3M6MjoiMTAiO3M6Njoic3RhdHVzIjtzOjE6IjEiO3M6NzoicGVuZGluZyI7aTowO31zOjE6Im8iO2E6NTp7czo3OiJyZXF1ZXN0IjtzOjM6InNlcCI7czoyOiJpZCI7czoxOiIxIjtzOjQ6InpvbmUiO3M6MToiMCI7czo3OiJjb21tZW50IjtzOjA6IiI7czo4OiJsb2NhdGlvbiI7czoyOiJERSI7fXM6MToicyI7YTo3OntzOjQ6ImdhbWUiO3M6MjA6IkNvRDpVbml0ZWQgT2ZmZW5zaXZlIjtzOjQ6Im5hbWUiO3M6NTE6IltEaWUtaXJyZS1UcnVwcGUuZGVdIEhlYWRxdWFydGVycyAgYnkgbmd6LXNlcnZlci5kZSI7czozOiJtYXAiO3M6MTU6Im1wX3N0Y29tZWR1bW9udCI7czo3OiJwbGF5ZXJzIjtzOjE6IjAiO3M6MTA6InBsYXllcnNtYXgiO3M6MjoiMjAiO3M6ODoicGFzc3dvcmQiO3M6MToiMCI7czoxMDoiY2FjaGVfdGltZSI7czoxMDoiMTY1OTE5MzIwMSI7fXM6MToiZSI7YToyNzp7czo2OiIuYWRtaW4iO3M6MTI6IltNQHJpdXMuRGlUXSI7czo2OiIuZW1haWwiO3M6Mjk6Im1hcml1c2thc3BlcnNreTE5ODZAZ21haWwuY29tIjtzOjk6Ii5sb2NhdGlvbiI7czo3OiJHZXJtYW55IjtzOjg6Ii53ZWJzaXRlIjtzOjMwOiJodHRwOi8vd3d3LkRpZS1pcnJlLVRydXBwZS5kZS8iO3M6NzoiZnNfZ2FtZSI7czoxNToiRGllLWlycmUtVHJ1cHBlIjtzOjEwOiJnX2dhbWV0eXBlIjtzOjI6ImhxIjtzOjE3OiJnX3RpbWVvdXRzYWxsb3dlZCI7czoxOiIwIjtzOjg6ImdhbWVuYW1lIjtzOjIwOiJDb0Q6VW5pdGVkIE9mZmVuc2l2ZSI7czo3OiJtYXBuYW1lIjtzOjE1OiJtcF9zdGNvbWVkdW1vbnQiO3M6ODoicHJvdG9jb2wiO3M6MjoiMjIiO3M6MTU6InNjcl9hbGxvd19qZWVwcyI7czoxOiIxIjtzOjE1OiJzY3JfYWxsb3dfdGFua3MiO3M6MToiMSI7czoxNjoic2NyX2ZyaWVuZGx5ZmlyZSI7czoxOiIzIjtzOjExOiJzY3Jfa2lsbGNhbSI7czoxOiIwIjtzOjEyOiJzaG9ydHZlcnNpb24iO3M6NDoiMS41MSI7czoxNzoic3ZfYWxsb3dhbm9ueW1vdXMiO3M6MToiMCI7czoxNToic3ZfZmxvb2Rwcm90ZWN0IjtzOjE6IjEiO3M6MTE6InN2X2hvc3RuYW1lIjtzOjUxOiJbRGllLWlycmUtVHJ1cHBlLmRlXSBIZWFkcXVhcnRlcnMgIGJ5IG5nei1zZXJ2ZXIuZGUiO3M6MTM6InN2X21heGNsaWVudHMiO3M6MjoiMjAiO3M6MTA6InN2X21heHBpbmciO3M6MToiMCI7czoxMDoic3ZfbWF4cmF0ZSI7czo1OiIyMDAwMCI7czoxMDoic3ZfbWlucGluZyI7czoxOiIwIjtzOjE3OiJzdl9wcml2YXRlY2xpZW50cyI7czoxOiIzIjtzOjEzOiJzdl9wdW5rYnVzdGVyIjtzOjE6IjAiO3M6Nzoic3ZfcHVyZSI7czoxOiIxIjtzOjU6InBzd3JkIjtzOjE6IjAiO3M6MzoibW9kIjtzOjE6IjEiO31zOjE6InAiO2E6MDp7fX0=', '1659193201_1659193201_1659193201'),
(2, 'ts3', '213.202.206.189', '10145', '10011', '0', '0', 0, '', 1, 'YTo1OntzOjE6ImIiO2E6Nzp7czo0OiJ0eXBlIjtzOjM6InRzMyI7czoyOiJpcCI7czoxNToiMjEzLjIwMi4yMDYuMTg5IjtzOjY6ImNfcG9ydCI7czo1OiIxMDE0NSI7czo2OiJxX3BvcnQiO3M6NToiMTAwMTEiO3M6Njoic19wb3J0IjtzOjE6IjAiO3M6Njoic3RhdHVzIjtzOjE6IjEiO3M6NzoicGVuZGluZyI7aTowO31zOjE6Im8iO2E6NTp7czo3OiJyZXF1ZXN0IjtzOjE6InMiO3M6MjoiaWQiO3M6MToiMyI7czo0OiJ6b25lIjtzOjE6IjAiO3M6NzoiY29tbWVudCI7czowOiIiO3M6ODoibG9jYXRpb24iO3M6MjoiREUiO31zOjE6InMiO2E6Nzp7czo0OiJnYW1lIjtzOjM6InRzMyI7czo0OiJuYW1lIjtzOjI0OiLCoFtEaWUtaXJyZS1UcnVwcGUuZGVdwqAiO3M6MzoibWFwIjtzOjM6InRzMyI7czo3OiJwbGF5ZXJzIjtzOjE6IjUiO3M6MTA6InBsYXllcnNtYXgiO3M6MjoiMTUiO3M6ODoicGFzc3dvcmQiO3M6MToiMCI7czoxMDoiY2FjaGVfdGltZSI7czoxMDoiMTY1OTE4MjU1NiI7fXM6MToiZSI7YTo2OntzOjg6InBsYXRmb3JtIjtzOjU6IkxpbnV4IjtzOjQ6Im1vdGQiO3M6NjY6IldpbGxrb21tZW4gYXVmIGVpbmVtIFRlYW1zcGVhayAzIFNlcnZlciB2b24gVW5pdGVkLUdhbWVzZXJ2ZXIuZGUhISI7czo2OiJ1cHRpbWUiO3M6MTI6IjM3ZCAyMTozMzoyNSI7czo2OiJiYW5uZXIiO3M6MjU6Imh0dHBzOi8vd3d3Lm5nei1zZXJ2ZXIuZGUiO3M6MTM6ImNoYW5uZWxzY291bnQiO3M6MToiNSI7czo3OiJ2ZXJzaW9uIjtzOjI2OiIzLjEzLjUgW0J1aWxkOiAxNjIxMjM5MjE2XSI7fXM6MToicCI7YToxMzp7aTowO2E6Mjp7czo0OiJuYW1lIjtzOjg6IlVua25vd240IjtzOjc6ImNvdW50cnkiO3M6MDoiIjt9aToxO2E6Mjp7czo0OiJuYW1lIjtzOjg6IlVua25vd24zIjtzOjc6ImNvdW50cnkiO3M6MDoiIjt9aToyO2E6Mjp7czo0OiJuYW1lIjtzOjc6IlVua25vd24iO3M6NzoiY291bnRyeSI7czowOiIiO31pOjM7YToyOntzOjQ6Im5hbWUiO3M6MTE6IkdhbWVUcmFja2VyIjtzOjc6ImNvdW50cnkiO3M6MDoiIjt9aTo0O2E6Mjp7czo0OiJuYW1lIjtzOjk6IlVua25vd24xMSI7czo3OiJjb3VudHJ5IjtzOjA6IiI7fWk6NTthOjI6e3M6NDoibmFtZSI7czo5OiJVbmtub3duMTAiO3M6NzoiY291bnRyeSI7czowOiIiO31pOjY7YToyOntzOjQ6Im5hbWUiO3M6ODoiVW5rbm93bjkiO3M6NzoiY291bnRyeSI7czowOiIiO31pOjc7YToyOntzOjQ6Im5hbWUiO3M6ODoiVW5rbm93bjgiO3M6NzoiY291bnRyeSI7czowOiIiO31pOjg7YToyOntzOjQ6Im5hbWUiO3M6ODoiVW5rbm93bjciO3M6NzoiY291bnRyeSI7czowOiIiO31pOjk7YToyOntzOjQ6Im5hbWUiO3M6ODoiVW5rbm93bjIiO3M6NzoiY291bnRyeSI7czowOiIiO31pOjEwO2E6Mjp7czo0OiJuYW1lIjtzOjg6IlVua25vd242IjtzOjc6ImNvdW50cnkiO3M6MDoiIjt9aToxMTthOjI6e3M6NDoibmFtZSI7czo4OiJVbmtub3duNSI7czo3OiJjb3VudHJ5IjtzOjA6IiI7fWk6MTI7YToyOntzOjQ6Im5hbWUiO3M6ODoiVW5rbm93bjEiO3M6NzoiY291bnRyeSI7czowOiIiO319fQ==', '1659182556_1659182556_1659180733'),
(3, 'discord', '9ndVavw2', '1', '1', '0', '0', 0, '', 1, 'YTo1OntzOjE6ImIiO2E6Nzp7czo0OiJ0eXBlIjtzOjc6ImRpc2NvcmQiO3M6MjoiaXAiO3M6ODoiOW5kVmF2dzIiO3M6NjoiY19wb3J0IjtzOjE6IjEiO3M6NjoicV9wb3J0IjtzOjE6IjEiO3M6Njoic19wb3J0IjtzOjE6IjAiO3M6Njoic3RhdHVzIjtzOjE6IjEiO3M6NzoicGVuZGluZyI7aTowO31zOjE6Im8iO2E6NTp7czo3OiJyZXF1ZXN0IjtzOjE6InMiO3M6MjoiaWQiO3M6MToiOSI7czo0OiJ6b25lIjtzOjE6IjAiO3M6NzoiY29tbWVudCI7czowOiIiO3M6ODoibG9jYXRpb24iO3M6MjoiWFgiO31zOjE6InMiO2E6Nzp7czo0OiJnYW1lIjtzOjc6ImRpc2NvcmQiO3M6NDoibmFtZSI7czoxMzoid2ViU1BFTEwgfCBSTSI7czozOiJtYXAiO3M6NzoiZGlzY29yZCI7czo3OiJwbGF5ZXJzIjtzOjI6IjE1IjtzOjEwOiJwbGF5ZXJzbWF4IjtzOjI6Ijg1IjtzOjg6InBhc3N3b3JkIjtzOjE6IjAiO3M6MTA6ImNhY2hlX3RpbWUiO3M6MTA6IjE2NTkxODI1NTciO31zOjE6ImUiO2E6Mjp7czoyOiJpZCI7czoxODoiNDI4OTk1NjE4OTcxNTgyNDczIjtzOjc6Imludml0ZXIiO3M6MTI6IlQtU2V2ZW4jNzIzOCI7fXM6MToicCI7YToxMzp7aTowO2E6Mzp7czo0OiJuYW1lIjtzOjE0OiJBbGJlcnRfbnVyX1J1bSI7czo2OiJzdGF0dXMiO3M6Njoib25saW5lIjtzOjQ6ImdhbWUiO3M6MjE6Ikxhd24gTW93aW5nIFNpbXVsYXRvciI7fWk6MTthOjM6e3M6NDoibmFtZSI7czo4OiJBcm1pbml1cyI7czo2OiJzdGF0dXMiO3M6NDoiaWRsZSI7czo0OiJnYW1lIjtzOjI6Ii0tIjt9aToyO2E6Mzp7czo0OiJuYW1lIjtzOjEwOiJkb29naWUxOTgwIjtzOjY6InN0YXR1cyI7czo2OiJvbmxpbmUiO3M6NDoiZ2FtZSI7czoyOiItLSI7fWk6MzthOjM6e3M6NDoibmFtZSI7czo3OiJHYXVubGV0IjtzOjY6InN0YXR1cyI7czo2OiJvbmxpbmUiO3M6NDoiZ2FtZSI7czo0NjoiTWluZWNyYWZ0OiBTdG9yeSBNb2RlIC0gU2Vhc29uIFR3byAtIEVwaXNvZGUgMSI7fWk6NDthOjM6e3M6NDoibmFtZSI7czo2OiJLYW1vaHkiO3M6Njoic3RhdHVzIjtzOjQ6ImlkbGUiO3M6NDoiZ2FtZSI7czo3OiJWYWxoZWltIjt9aTo1O2E6Mzp7czo0OiJuYW1lIjtzOjc6IktSWVNUNEwiO3M6Njoic3RhdHVzIjtzOjY6Im9ubGluZSI7czo0OiJnYW1lIjtzOjI6Ii0tIjt9aTo2O2E6Mzp7czo0OiJuYW1lIjtzOjQ6Ik1FRTYiO3M6Njoic3RhdHVzIjtzOjY6Im9ubGluZSI7czo0OiJnYW1lIjtzOjI6Ii0tIjt9aTo3O2E6Mzp7czo0OiJuYW1lIjtzOjE0OiJQc1ljaE94SGFtU3RlciI7czo2OiJzdGF0dXMiO3M6NDoiaWRsZSI7czo0OiJnYW1lIjtzOjE3OiJhbHQ6ViBNdWx0aXBsYXllciI7fWk6ODthOjM6e3M6NDoibmFtZSI7czoxMzoiUmFwdG9yQmx1YmJlciI7czo2OiJzdGF0dXMiO3M6NDoiaWRsZSI7czo0OiJnYW1lIjtzOjI6Ii0tIjt9aTo5O2E6Mzp7czo0OiJuYW1lIjtzOjEyOiJTYWxvbWFuIEthbmUiO3M6Njoic3RhdHVzIjtzOjQ6ImlkbGUiO3M6NDoiZ2FtZSI7czoyOiItLSI7fWk6MTA7YTozOntzOjQ6Im5hbWUiO3M6NzoiVC1TZXZlbiI7czo2OiJzdGF0dXMiO3M6Njoib25saW5lIjtzOjQ6ImdhbWUiO3M6MjoiLS0iO31pOjExO2E6Mzp7czo0OiJuYW1lIjtzOjc6IlVya2dyaW0iO3M6Njoic3RhdHVzIjtzOjQ6ImlkbGUiO3M6NDoiZ2FtZSI7czoyOiItLSI7fWk6MTI7YTozOntzOjQ6Im5hbWUiO3M6MTA6IlZpcGVyL01hcmMiO3M6Njoic3RhdHVzIjtzOjY6Im9ubGluZSI7czo0OiJnYW1lIjtzOjI6Ii0tIjt9fX0=', '1659182557_1659182557_1659181943');");

$transaction .= addtable("CREATE TABLE IF NOT EXISTS`" . PREFIX . "plugins_game_server_settings_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `modulname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `themes_modulname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `widgetname` varchar(255) NOT NULL DEFAULT '',
  `widgetdatei` varchar(255) NOT NULL DEFAULT '',
  `activated` int(1) DEFAULT 1,
  `sort` int(11) DEFAULT 1,
PRIMARY KEY (`id`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci");

$transaction .= add_insert_table("INSERT IGNORE INTO `" . PREFIX . "plugins_game_server_settings_widgets` (`id`, `position`, `modulname`, `themes_modulname`, `widgetname`, `widgetdatei`, `activated`, `sort`) VALUES
('1', 'navigation_widget', 'navigation', 'default', 'Navigation', 'widget_navigation', 1, 1),
('2', 'footer_widget', 'footer', 'default', 'Footer Easy', 'widget_footer_easy', 1, 1)");

## SYSTEM #####################################################################################################################################

$transaction .= add_insert_plugin("INSERT IGNORE INTO `" . PREFIX . "settings_plugins` (`pluginID`, `name`, `modulname`, `info`, `admin_file`, `activate`, `author`, `website`, `index_link`, `hiddenfiles`, `version`, `path`, `status_display`, `plugin_display`, `widget_display`, `delete_display`, `sidebar`) VALUES
('', 'Game Server', 'game_server', '{[de]}Mit diesem Plugin könnt ihr eure Game Server anzeigen lassen.{[en]}With this plugin you can display your Game Server.{[it]}Con questo plugin puoi visualizzare i tuoi Game Server.', 'admin_game_server', 1, 'T-Seven', 'https://webspell-rm.de', 'game_server', '', '0.1', 'includes/plugins/game_server/', 1, 1, 1, 1, 'deactivated')");

## NAVIGATION #####################################################################################################################################

$transaction .= add_insert_navi_dashboard("INSERT IGNORE INTO `".PREFIX."navigation_dashboard_links` (`linkID`, `catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) VALUES
('', 10, '{[de]}Game Server{[en]}Game Server{[it]}Game Server', 'game_server', 'admincenter.php?site=admin_game_server', 'page', 1)");


$transaction .= add_insert_navigation("INSERT IGNORE INTO `".PREFIX."navigation_website_sub` (`snavID`, `mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`, `themes_modulname`) VALUES
('', 3, '{[de]}Game Server{[en]}Game Server{[it]}Game Server', 'game_server', 'index.php?site=game_server', 1, 1, 'default')");

#######################################################################################################################################
echo "</div></div>";

  
 ?>