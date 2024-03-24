<?php
/*пппппппппппппппппппппппппппппппппппппппппппппппппппппппппппппппппп\
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
$drpath = $_SERVER['DOCUMENT_ROOT'];
include('../../../system/sql.php');
include('../../../system/settings.php');

// -- FILES INFORMATION -- //
include_once("files_functions.php");
function download($file, $extern = 0)
{

    if (!$extern) {
        $filename = basename($file);

        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Description: File Transfer");

        header("Content-Disposition: attachment; filename=" . str_replace(' ', '_', $filename) . ";");
        header("Content-Length: " . filesize($file));
        header("Content-Transfer-Encoding: binary");

        @readfile($file); 
		header("Location: /includes/plugins/files/downloads/" . $filename . ""); 

        exit;
    } else {
        header("Location: " . $file);
    }
}

if (isset($_GET[ 'fileID' ])) {
    $fileID = intval($_GET[ 'fileID' ]);
}

include_once('../../../system/session.php');
include_once('../../../system/login.php');
include_once('../../../system/func/useraccess.php');
#systeminc('../../system/func/filesystem');

if (isset($fileID)) {
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_files WHERE fileID='$fileID' ");
    $dd = mysqli_fetch_array($ergebnis);

    $allowed = 0;
    switch ($dd[ 'accesslevel' ]) {
        case 0:
            $allowed = 1;
            break;
        case 1:
            if ($userID) {
                $allowed = 1;
            }
            break;
        case 2:
            if (isclanmember($userID)) {
                $allowed = 1;
            }
            break;
        default:
            $allowed = 0;
    }

    if ($allowed) {
        safe_query("UPDATE " . PREFIX . "plugins_files SET downloads=downloads+1 WHERE fileID='$fileID' ");

        if (isFileURL($dd[ 'file' ])) {
            download($dd[ 'file' ], 1);
        } else {
            download('/includes/plugins/files/downloads/' . $dd[ 'file' ]);
        }
    }
}

echo 'dada';