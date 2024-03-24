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
\__________________________________________________________________*/
# Sprachdateien aus dem Plugin-Ordner laden
$pm = new plugin_manager(); 
$plugin_language = $pm->plugin_language("planning", $plugin_path);

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='planning'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}


// Save added project
if (isset($_POST[ 'save' ])) {
    $name = $_POST[ 'name' ];
    #$rdate = date("Y-m-d H:i:s", strtotime($_POST['rdate']));
    $date = strtotime($_POST['date']);
    $progress = $_POST[ 'progress' ];
    $link = $_POST[ 'link' ]; 

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('name', 'date', 'progress'))) {
            safe_query("INSERT INTO " . PREFIX . "plugins_planning ( name, date, progress, link ) values ( '".$name."', '".$date."', '".$progress."', '".$link."' )");
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} 
// Save edited project
elseif (isset($_POST[ 'saveedit' ])) {
    $name = $_POST[ 'name' ];
    #$rdate = date("Y-m-d H:i:s", strtotime($_POST['rdate']));
    $date = strtotime($_POST['date']);
    $progress = $_POST[ 'progress' ];
    $link = $_POST[ 'link' ]; 
    $planID = $_POST[ 'planID' ];

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (checkforempty(array('name', 'date', 'progress'))) {
            safe_query("UPDATE " . PREFIX . "plugins_planning SET name='$name', date='$date', progress='$progress', link='$link' WHERE planID='$planID' ");
        } else {
            echo $plugin_language[ 'information_incomplete' ];
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} 
//  delete project
elseif (isset($_GET[ 'delete' ])) {
    $planID = $_GET[ 'planID' ];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        safe_query("DELETE FROM " . PREFIX . "plugins_planning WHERE planID='$planID'");
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
}


// If Button "Add" is pressed new windows goes open to add project
if (isset($_GET[ 'action' ])) {
    if ($_GET[ 'action' ] == "add") {
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

        echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-upload"></i> ' . $plugin_language[ 'planning' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_planning">' . $plugin_language[ 'planning' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';

        echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_planning" enctype="multipart/form-data">
        
            <div class="form-group">
                <label class="col-sm-2 control-label">' . $plugin_language[ 'projectname' ] . ':</label>
                <div class="col-sm-8">
                <input class="form-control" type="text" name="name" size="60">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">' . $plugin_language[ 'releasedate' ] . ':</label>
                <div class="col-sm-8">
                <input id="date" class="form-control" placeholder="yyyy-mm-dd" name="date" type="date">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">' . $plugin_language[ 'progress' ] . ':</label>
                <div class="col-sm-8">
                <input class="form-control" type="number" name="progress" min="0" max="100" size="60">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">' . $plugin_language[ 'link_to_project' ] . ':</label>
                <div class="col-sm-8">
                <input class="form-control" type="url" name="link" size="60">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                <input type="hidden" name="captcha_hash" value="' . $hash . '" />
                <button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['add'].'</button>
                </div>
            </div>
            
        </form>
       </div></div>';
    } elseif ($_GET[ 'action' ] == "edit") {
        $planID = (int)$_GET[ 'planID' ];

        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_planning WHERE planID='$planID'");
        $ds = mysqli_fetch_array($ergebnis);

        #$rdate = date("Y-m-d", strtotime($ds[ 'rdate' ]));
         $date = date("Y-m-d", $ds[ 'date' ]);

        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

        echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-upload"></i> ' . $plugin_language[ 'planning' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_planning">' . $plugin_language[ 'planning' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';

        echo '<form class="form-horizontal" method="post" action="admincenter.php?site=admin_planning" enctype="multipart/form-data">


           <div class="form-group">
                <label class="col-sm-2 control-label">' . $plugin_language[ 'projectname' ] . ':</label>
                <div class="col-sm-8">
                <input class="form-control" type="text" name="name" size="255" value="' . getinput($ds[ 'name' ]) . '">
                </div>
            </div> 

            <div class="form-group">
                <label class="col-sm-2 control-label">' . $plugin_language[ 'releasedate' ] . ':</label>
                <div class="col-sm-8">
                <input name="date" type="date" value="'.$date.'" placeholder="yyyy-mm-dd" class="form-control">
                </div>
            </div> 

            <div class="form-group">
                <label class="col-sm-2 control-label">' . $plugin_language[ 'progress' ] . ':</label>
                <div class="col-sm-8">
                <input class="form-control" type="number" name="progress" min="0" max="100" size="3" value="' . getinput($ds[ 'progress' ]) . '">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">' . $plugin_language[ 'link_to_project' ] . ':</label>
                <div class="col-sm-8">
                <input class="form-control" type="url" name="link" size="255" value="' . getinput($ds[ 'link' ]) . '">
                </div>
            </div>
        

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                <input type="hidden" name="captcha_hash" value="' . $hash . '">
                <input type="hidden" name="planID" value="' . getforminput($planID) . '" /> 
                <button class="btn btn-warning" type="submit" name="saveedit"  />'.$plugin_language['edit'].'</button>
                </div>
            </div>
        </form>
       </div></div>';
	}
} else {

    echo'<div class="card">
            <div class="card-header">
                            <i class="bi bi-paragraph"></i> ' . $plugin_language[ 'planning' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_planning">' . $plugin_language[ 'planning' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="form-group row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_planning&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'add' ] . '</a>
    </div>
  </div>';

    
    echo
        '<form method="post" action="admincenter.php?site=admin_planning">

	<table class="table table-striped">
    <thead>
        <th><b>' . $plugin_language[ 'projectname' ] . '</b></th>
        <th><b>' . $plugin_language[ 'releasedate' ] . '</b></th>
        <th><b>' . $plugin_language[ 'progress' ] . '</b></th>
        <th><b>' . $plugin_language[ 'link_to_project' ] . '</b></th>
        <th><b>' . $plugin_language[ 'actions' ] . '</b></th>
        </thead>';

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();


    $qry = safe_query("SELECT * FROM " . PREFIX . "plugins_planning ORDER by `date`");
    $anz = mysqli_num_rows($qry);
    if ($anz) {
        $i = 1;
        while ($ds = mysqli_fetch_array($qry)) {    

            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }

        $date = getformatdate($ds[ 'date' ]);
        echo '
            <tr>
                <td class="' . $td . '">' . getinput($ds[ 'name' ]) . '</td>
                <td class="' . $td . '">'. $date .'</td>
                <td class="' . $td . '">' . getinput($ds[ 'progress' ]) . '%</td>
                <td class="' . $td . '">' . getinput($ds[ 'link' ]) . '</td>
                <td class="' . $td . '">
                        <a href="admincenter.php?site=admin_planning&amp;action=edit&amp;planID=' .$ds[ 'planID' ] . '" class="btn btn-warning">' . $plugin_language[ 'edit' ] . '</a>
                        <!-- Button trigger modal START-->
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=admin_planning&amp;delete=true&amp;planID='. $ds[ 'planID' ]. '&amp;captcha_hash='.$hash.'">
                            ' . $plugin_language[ 'delete' ] . '
                            </button>
                        	<!-- Button trigger modal END-->
                        
                             <!-- Modal -->
                        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">' . $plugin_language[ 'planning' ] . '</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $plugin_language[ 'close' ] . '"></button>
                              </div>
                              <div class="modal-body"><p>' . $plugin_language['really_delete'] . '</p>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $plugin_language[ 'close' ] . '</button>
                                <a class="btn btn-danger btn-ok">' . $plugin_language['delete'] . '</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- Modal END -->
                </td>
            </tr>
        ';
      $i++;
        }
    } else {
        echo '<tr><td class="td1" colspan="7">' . $plugin_language[ 'no_entries' ] . '</td></tr>';
    }
    // Close table
    echo '
        </table></form></div></div>';
}
?>