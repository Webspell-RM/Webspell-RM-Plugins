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
    $plugin_language = $pm->plugin_language("search", $plugin_path);

        $data_array = array();
        $data_array['$title'] = $plugin_language[ 'search' ];
        $data_array['$subtitle']='Search';
        $template = $GLOBALS["_template"]->loadTemplate("search","head", $data_array, $plugin_path);
        echo $template;

        $CAPCLASS = new \webspell\Captcha;
        $captcha = $CAPCLASS->createCaptcha();
        $hash = $CAPCLASS->getHash();
        $CAPCLASS->clearOldCaptcha();

        

        $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='faq' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'faq') {
        $faq = '';
    } else {
        $faq = '<input name="faq" type="hidden" value="true">';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='wiki' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'wiki') {
        $wiki = '';
    } else {
        $wiki = '<input name="wiki" type="hidden" value="true">';
    }    
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='news_manager' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'news_manager') {
        $news = '';
    } else {
        $news = '<input name="news" type="hidden" value="true">';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='forum' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'forum') {
        $forum = '';
    } else {
        $forum = '<input name="forum" type="hidden" value="true">';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='files' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'files') {
        $files = '';
    } else {
        $files = '<input name="files" type="hidden" value="true">';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='articles' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'articles') {
        $articles = '';
    } else {
        $articles = '<input name="articles" type="hidden" value="true">';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='about_us' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'about_us') {
        $about_us = '';
    } else {
        $about_us = '<input name="about_us" type="hidden" value="true">';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='blog' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'blog') {
        $blog = '';
    } else {
        $blog = '<input name="blog" type="hidden" value="true">';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='history' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'history') {
        $history = '';
    } else {
        $history = '<input name="history" type="hidden" value="true">';
    } 
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='partners' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'partners') {
        $partners = '';
    } else {
        $partners = '<input name="partners" type="hidden" value="true">';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='todo' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'todo') {
        $todo = '';
    } else {
        $todo = '<input name="todo" type="hidden" value="true">';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='sponsors' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'sponsors') {
        $sponsors = '';
    } else {
        $sponsors = '<input name="sponsors" type="hidden" value="true">';
    }

$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='planning' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'planning') {
        $planning = '';
    } else {
        $planning = '<input name="planing" type="hidden" value="true">';
    }    
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='links' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'links') {
        $links = '';
    } else {
        $links = '<input name="links" type="hidden" value="true">';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='gallery' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'gallery') {
        $gallery = '';
    } else {
        $gallery = '<input name="gallery" type="hidden" value="true">';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='clan_rules' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'clan_rules') {
        $clan_rules = '';
    } else {
        $clan_rules = '<input name="clan_rules" type="hidden" value="true">';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='server_rules' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'server_rules') {
        $server_rules = '';
    } else {
        $server_rules = '<input name="server_rules" type="hidden" value="true">';
    } 
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='servers' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'servers') {
        $servers = '';
    } else {
        $servers = '<input name="servers" type="hidden" value="true">';
    }
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='calendar' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'calendar') {
        $events = '';
    } else {
        $events = '<input name="calendar" type="hidden" value="true">';
    }             
$dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='projectlist' AND activate= '1'"));
if (@$dx[ 'modulname' ] != 'projectlist') {
        $projectlist = '';
    } else {
        $projectlist = '<input name="projectlist" type="hidden" value="true">';
    }  

        
        $data_array = array();
        $data_array['$hash'] = $hash;
        $data_array['$td_d'] = date("d");
        $data_array['$td_m'] = date("m");
        $data_array['$td_Y'] = date("Y");

        /*#$data_array['$text'] = $text;*/
        $data_array['$faq'] = $faq;
        $data_array['$wiki'] = $wiki;
        $data_array['$news'] = $news;
        $data_array['$forum'] = $forum;
        $data_array['$files'] = $files;
        $data_array['$articles'] = $articles;
        $data_array['$about_us'] = $about_us;
        $data_array['$blog'] = $blog;
        $data_array['$history'] = $history;
        $data_array['$partners'] = $partners;
        $data_array['$todo'] = $todo;
        $data_array['$sponsors'] = $sponsors;
        $data_array['$planning'] = $planning;
        $data_array['$links'] = $links;
        $data_array['$gallery'] = $gallery;
        $data_array['$clan_rules'] = $clan_rules;
        $data_array['$server_rules'] = $server_rules;
        $data_array['$servers'] = $servers;
        $data_array['$events'] = $events;
        $data_array['$links'] = $links;
        $data_array['$projectlist'] = $projectlist;

        #$data_array = array();
        $data_array['$search_term']=$plugin_language['search_term'];
        $data_array['$search']=$plugin_language['search'];
        
        $template = $GLOBALS["_template"]->loadTemplate("search","quicksearch", $data_array, $plugin_path);
        echo $template;
