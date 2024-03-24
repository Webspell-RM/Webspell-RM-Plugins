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
    $plugin_language = $pm->plugin_language("faq", $plugin_path);

$_language->readModule('faq');

$data_array = array();
$data_array['$title']=$plugin_language[ 'title' ];
$data_array['$subtitle']='FAQ';
$template = $GLOBALS["_template"]->loadTemplate("faq","head", $data_array, $plugin_path);
echo $template;


if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

    $template = $GLOBALS["_template"]->loadTemplate("faq","body_head", $data_array, $plugin_path);
    echo $template;
    
    $faqcats = safe_query("SELECT * FROM `" . PREFIX . "plugins_faq_categories` ORDER BY `sort`");
    $anzcats = mysqli_num_rows($faqcats);
    
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($anzcats);
        $anzcats = $translate->getTextByLanguage($anzcats);


        $data_array = array();
        $data_array['$anzcats'] = $anzcats;
        $data_array['$faqs']=$plugin_language[ 'faqs' ];
        $data_array['$categories']=$plugin_language[ 'categories' ];

        $template = $GLOBALS["_template"]->loadTemplate("faq","category_head_head", $data_array, $plugin_path);
        echo $template;
        
        $i = 1;
        while ($ds = mysqli_fetch_array($faqcats)) {
            $anzfaqs =
                mysqli_num_rows(
                    safe_query(
                        "SELECT
                            `faqID`
                        FROM
                            `" . PREFIX . "plugins_faq`
                        WHERE
                            `faqcatID` = '" . (int)$ds[ 'faqcatID' ] . "'"
                    )
                );
            
            $description = $ds[ 'description' ];
            
            $faqcatname = '<a href="index.php?site=faq&amp;action=faqcat&amp;faqcatID=' . $ds[ 'faqcatID' ] . '">' .
                $ds[ 'faqcatname' ] . '</a>';

    
            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($faqcatname);
            $faqcatname = $translate->getTextByLanguage($faqcatname);

            $translate->detectLanguages($description);
            $description = $translate->getTextByLanguage($description);
            
            $data_array = array();
            $data_array['$faqcatname'] = $faqcatname;
            $data_array['$anzfaqs'] = $anzfaqs;
            $data_array['$description'] = $description;

            $data_array['$faqs']=$plugin_language[ 'faqs' ];

            $template = $GLOBALS["_template"]->loadTemplate("faq","category", $data_array, $plugin_path);
            echo $template;
            
            $i++;

        }

        $template = $GLOBALS["_template"]->loadTemplate("faq","category_foot_foot", $data_array, $plugin_path);
        echo $template;

#============================
            
    @$faqcatID = $_GET[ 'faqcatID' ];
    $get = safe_query("SELECT faqcatname FROM " . PREFIX . "plugins_faq_categories WHERE faqcatID='" . (int)$faqcatID . "'");
    $dc = mysqli_fetch_assoc($get);
    @$faqcatname = $dc[ 'faqcatname' ];

    $faqcat = safe_query(
        "SELECT
            `question`,
            `faqID`,
            `sort`
        FROM
            `" . PREFIX . "plugins_faq`
        WHERE
            `faqcatID` = '" . (int)$faqcatID . "'
        ORDER BY
            sort"
    );


    if (mysqli_num_rows($faqcat)) {

        $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($faqcatname);
            $faqcatname = $translate->getTextByLanguage($faqcatname);

            
        $data_array = array();
        $data_array['$faqcatname'] = $faqcatname;
        $data_array['$faqcatID'] = $faqcatID;

        $data_array['$categories']=$plugin_language[ 'categories' ];

        $template = $GLOBALS["_template"]->loadTemplate("faq","question_breadcrumb", $data_array, $plugin_path);
        echo $template;
 #=============================================================================================================================================================       
        
       $i = 1;
        while ($ds = mysqli_fetch_array($faqcat)) {
            
            $sort = $ds[ 'sort' ];            
            $question = '' . $ds[ 'question' ] . '';            
            $faqID = $ds[ 'faqID' ];

            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($question);
            $question = $translate->getTextByLanguage($question);
            

        $data_array = array();        
        $data_array['$question'] = $question;
        $data_array['$faqID'] = $faqID;

        $data_array['$categories']=$plugin_language[ 'categories' ];
            
        $template = $GLOBALS["_template"]->loadTemplate("faq","question_content_head_head", $data_array, $plugin_path);
        echo $template;

  #======================================================================      

        $data_array = array();        
        $data_array['$faqID'] = $faqID;

        $template = $GLOBALS["_template"]->loadTemplate("faq","question_content_head", $data_array, $plugin_path);
        echo $template;
#======================================================================      

    $faqcatID = intval($_GET[ 'faqcatID' ]);
    $get = safe_query(
        "SELECT
            `faqcatname`
        FROM
            `" . PREFIX . "plugins_faq_categories`
        WHERE
            `faqcatID` = '" . (int)$faqcatID . "'"
    );
    $dc = mysqli_fetch_assoc($get);
    $faqcatname = $dc[ 'faqcatname' ];

    $faq = safe_query(
        "SELECT
            `faqcatID`,
            `date`,
            `question`,
            `answer`
        FROM
            `" . PREFIX . "plugins_faq`
        WHERE
            `faqID` = '" . (int)$faqID . "'"
    );
    if (mysqli_num_rows($faq)) {
        $ds = mysqli_fetch_array($faq);

            $date = getformatdate($ds[ 'date' ]);
            $answer = $ds[ 'answer' ];

            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($answer);
            $answer = $translate->getTextByLanguage($answer);
           
        $data_array = array();
        $data_array['$answer'] = $answer;
        $data_array['$date'] = $date;

        $data_array['$saved_on']=$plugin_language[ 'saved_on' ];
        $data_array['$lang_answer']=$plugin_language[ 'answer' ];

        $template = $GLOBALS["_template"]->loadTemplate("faq","question_content", $data_array, $plugin_path);
        echo $template;

        $data_array = array();
        $template = $GLOBALS["_template"]->loadTemplate("faq","question_content_foot", $data_array, $plugin_path);
        echo $template;

        $i++;    
    } 
}                 
     
        $data_array = array();
        $template = $GLOBALS["_template"]->loadTemplate("faq","question_content_foot_foot", $data_array, $plugin_path);
        echo $template;

        $template = $GLOBALS["_template"]->loadTemplate("faq","body_foot", $data_array, $plugin_path);
        echo $template;

} else {
    echo $plugin_language[ 'no_categories' ];
    $data_array = array();
        $template = $GLOBALS["_template"]->loadTemplate("faq","body_foot_foot", $data_array, $plugin_path);
        echo $template;
}
