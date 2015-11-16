<?php
/**
 * Config-file for Anax, theme related settings, return it all as array.
 *
 */
return [

    /**
     * Settings for Which theme to use, theme directory is found by path and name.
     *
     * path: where is the base path to the theme directory, end with a slash.
     * name: name of the theme is mapped to a directory right below the path.
     */
    'settings' => [
        'path' => ANAX_INSTALL_PATH . 'theme/',
        'name' => 'anax-wgtotw',
    ],

    
    /** 
     * Add default views.
     */
    'views' => [
        ['region' => 'header', 'template' => 'wgtotw/header', 'data' => [
	    'siteTitle' => "Allt om att skapa konst",
            'siteTagline' => "kreativitet & konst",], 'sort' => -1],
        ['region' => 'footer', 'template' => 'wgtotw/footer', 'data' => [], 'sort' => -1],
		    [
        'region' => 'navbar', 
        'template' => [
            'callback' => function() {
                return $this->di->navbar->create();
            },
        ], 
        'data' => [], 
        'sort' => -1
    ],
    ],


    /** 
     * Data to extract and send as variables to the main template file.
     */
    'data' => [

        // Language for this page.
        'lang' => 'sv',

        // Append this value to each <title>
        'title_append' => ' | wgtotw',

        // Stylesheets
        // 'stylesheets' => ['css/style_me.css', 'css/navbar_me.css', 'css/form.css', 'css/comments.css'],
        'stylesheets' => ['css/anax-wgtotw/style.php'],

        // Inline style
        'style' => null,
        
        //class for the html element
        'htmlclass' => 'bright',

        // Favicon
        'favicon' => 'favicon.ico',

        // Path to modernizr or null to disable
        'modernizr' => 'js/modernizr.js',

        // Path to jquery or null to disable
        'jquery' => '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js',

        // Array with javscript-files to include
        'javascript_include' => [],

        // Use google analytics for tracking, set key or null to disable
        'google_analytics' => null,
    ],
];

