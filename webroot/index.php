<?php 
/**
 * This is a Anax pagecontroller.
 *
 */

// Get environment & autoloader and the $app-object.
require __DIR__.'/config_with_app.php'; 


 $app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN); 

$app->theme->configure(ANAX_APP_PATH . 'config/theme-wgtotw.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_wgtotw.php');

$di->set('CommentsController', function() use ($di) {
    $controller = new Phpmvc\Comment\CommentsController();
    $controller->setDI($di);
    return $controller;
});

$di->setShared('db', function() {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/config_mysql.php');
    $db->connect();
    return $db;
});

$di->set('UsersController', function() use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});

$di->set('UserLoginController', function() use ($di) {
    $controller = new \Anax\Users\UserLoginController();
    $controller->setDI($di);
    return $controller;
});

$di->set('IssuesController', function() use ($di) {
    $controller = new \Meax\Content\IssuesController();
    $controller->setDI($di);
    return $controller;
});

$di->set('AnswerController', function() use ($di) {
    $controller = new \Phpmvc\Comment\AnswerController();
    $controller->setDI($di);
    return $controller;
});

$di->set('TagBasicController', function() use ($di) {
    $controller = new \Meax\Content\TagBasicController();
    $controller->setDI($di);
    return $controller;
});

$di->set('ContentTagController', function() use ($di) {
    $controller = new \Meax\Content\ContentTagController();
    $controller->setDI($di);
    return $controller;
});


$di->set('form', '\Mos\HTMLForm\CForm');

$app->session();

$app->router->add('', function() use ($app) {
 
    
    $app->theme->setTitle("Me");
 
   
    $app->views->add('wgtotw/plain', [
        'content' => 'Allt om att skapa konst!',
    ], 'flash');
    
    $app->dispatcher->forward([
    'controller' => 'issues',
    'action'     => 'list-latest',
    'params'     => [10],
    ]);
    
    $app->dispatcher->forward([
    'controller' => 'content-tag',
    'action'     => 'list-most-used',
    'params'     => ['tagid', 15],
    ]);
    
    $app->dispatcher->forward([
    'controller' => 'users',
    'action'     => 'list-most-active',

    ]);
    

 
});

$app->router->add('about', function() use ($app) {
  
    $app->theme->setTitle("Om oss");
  
    $app->views->add('default/page', [
	'title' => 'Om oss',
        'content' => 'Hello',
    ]);
    

});
 

$app->router->add('users', function() use ($app) {
    $app->dispatcher->forward([
    'controller' => 'users',
    'action'     => 'list',
    ]);
});

$app->router->add('login', function() use ($app) {

    $app->dispatcher->forward([
    'controller' => 'user-login',
    'action'     => 'show-login',
    ]);
});

$app->router->add('tags', function() use ($app) {
    $app->dispatcher->forward([
    'controller' => 'tag-basic',
    'action'     => 'list',
    ]);
});


$app->router->add('setup', function() use ($app) {
		
	$app->db->setVerbose();	
 
	$app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'reset-users',
    ]);	
		
	$app->dispatcher->forward([
        'controller' => 'issues',
        'action'     => 'setup-populate',
    ]);
    
    $app->dispatcher->forward([
        'controller' => 'tag-basic',
        'action'     => 'setup-content',
    ]);
    
    $app->dispatcher->forward([
        'controller' => 'tag-basic',
        'action'     => 'auto-populate',
    ]);
    
    $app->dispatcher->forward([
        'controller' => 'content-tag',
        'action'     => 'setup-content',
    ]);
    
    $app->dispatcher->forward([
        'controller' => 'answer',
        'action'     => 'setup-answer',
    ]);
    
    $app->dispatcher->forward([
        'controller' => 'comments',
        'action'     => 'setup-comment',
    ]);
});
/*

$app->router->add('setup-comments', function() use ($app) {
 
    $app->theme->setTitle("Återställ kommentarer");
    $app->views->add('comment/setup');
    
  
});

$app->router->add('delete-comments', function() use ($app) {
 
    $app->theme->setTitle("Radera kommentarer");
    $app->views->add('comment/delete');
    
  
});
*/
$app->router->add('issues', function() use ($app) {
		$app->dispatcher->forward([
        'controller' => 'issues',
        'action'     => 'list',
    ]);
});


$app->router->add('setup-content', function() use ($app) {
 
    $app->theme->setTitle("Återställ innehåll");
    $app->views->add('content/setup', [
        'controller' => 'content',
        'title' => "Återställ databas",
    ], 'main');

});


 
$app->router->handle();
$app->theme->render();
