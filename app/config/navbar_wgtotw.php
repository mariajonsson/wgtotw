<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',
 
    // Here comes the menu strcture
    'items' => [

        
        // This is a menu item
        'home'  => [
            'text'  => 'Hem',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Hem',
            'mark-if-parent-of' => 'home',
            'class' => 'home'
        ],
 
        
        // This is a menu item
        'issues' => [
            'text'  =>'Frågor',
            'url'   => $this->di->get('url')->create('issues'),
            'title' => 'Frågor',
            'mark-if-parent-of' => 'issues',
            'class' => 'issues',
            
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'issue-add'  => [
                        'text'  => 'Lägg till fråga',
                        'url'   => $this->di->get('url')->create('issues/add'),
                        'title' => 'Lägg till fråga',
                        'class' => 'issues'
                    ],
                  ],
                ],
            
        ],

        // This is a menu item
        'tags' => [
            'text'  =>'Taggar',
            'url'   => $this->di->get('url')->create('tags'),
            'title' => 'Taggar',
            'class' => 'tags',
            
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'tags-add'  => [
                        'text'  => 'Lägg till tagg',
                        'url'   => $this->di->get('url')->create('tag-basic/add'),
                        'title' => 'Lägg till tagg',
                        'class' => 'tags'
                    ],
                  ],
                ],
        ],
        
        
        // This is a menu item
        'users' => [
            'text'  =>'Användare',
            'url'   => $this->di->get('url')->create('users'),
            'title' => 'Användare i databasen', 
            'mark-if-parent-of' => 'users',
            'class' => 'users',
            
            // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'all'  => [
                        'text'  => 'Alla användare',
                        'url'   => $this->di->get('url')->create('users/list'),
                        'title' => 'Visa alla användare',
                        'class' => 'users',
                    ],                    
                    // This is a menu item of the submenu
                    'active'  => [
                        'text'  => 'Aktiva',
                        'url'   => $this->di->get('url')->create('users/active'),
                        'title' => 'Visa aktiva användare',
                        'class' => 'users',
                    ],
                    
                    // This is a menu item of the submenu
                    'inactive'  => [
                        'text'  => 'Inaktiva',
                        'url'   => $this->di->get('url')->create('users/inactive'),
                        'title' => 'Visa inaktiva användare',
                        'class' => 'users',
                    ],
                    
                    // This is a menu item of the submenu
                    'add'  => [
                        'text'  => 'Lägg till',
                        'url'   => $this->di->get('url')->create('users/add'),
                        'title' => 'Lägg till användare',
                        'class' => 'users',
                    ],
                     /* This is a menu item of the submenu
                    'discarded'  => [
                        'text'  => 'Papperskorgen',
                        'url'   => $this->di->get('url')->create('users/discarded'),
                        'title' => 'Visa papperskorgen'
                    ],*/

                  ],
            ],
        ],
        


         // This is a menu item
        'about' => [
            'text'  =>'Om oss',
            'url'   => $this->di->get('url')->create('about'),
            'title' => 'Om oss',
            'class' => 'about',
        ],
        
        // This is a menu item
        'login' => [
            'text'  =>'Logga in/ut',
            'url'   => $this->di->get('url')->create('login'),
            'title' => 'Logga in/ut',
            'class' => 'login',
        ],
    ],
 


    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($url == $this->di->get('request')->getCurrentUrl(false)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];
