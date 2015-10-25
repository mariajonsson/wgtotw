<?php
namespace Anax\Users;
 
/**
 * A controller for users and admin related events.
 *
 */
class UsersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
 
/**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->users = new \Anax\Users\User();
    $this->users->setDI($this->di);
    

}

/**
 * List all users.
 *
 * @return void
 */
public function listAction()
{
 
    $all = $this->users->findAll();
    
    $this->theme->setTitle("Användare");
    $this->views->add('users/list-all', [
        'users' => $all,
        'title' => "Alla användare",
    ], 'main');
    $this->views->add('users/adminmenu', [], 'sidebar');
}

/**
 * List user with id.
 *
 * @param int $id of user to display
 *
 * @return void
 */
public function idAction($id = null)
{
    $user = $this->users->find($id);
    $acronym = $this->users->getAcronym($id);
    
 
    $this->theme->setTitle("Användare");
    $this->views->add('users/view', [
        'user' => $user,
    ], 'main');
    $this->views->add('users/adminmenu', [], 'sidebar');
    
    
    $this->dispatcher->forward([
        'controller' => 'issues',
        'action'     => 'list-by-user',
        'params'     => [$acronym],
    ]);
}

/**
 * Add new user.
 *
 * @param string $acronym of user to add.
 *
 * @return void
 */
public function addAction($acronym = null)
{

    
    $form = new \Anax\HTMLForm\CFormUserAdd();
    $form->setDI($this->di);
    $status = $form->check();
    
    $info = $this->di->fileContent->get('users-addinfo.md');
    $info = $this->di->textFilter->doFilter($info, 'shortcode, markdown');
  
    $this->di->theme->setTitle("Lägg till användare");
    $this->di->views->add('default/page', [
        'title' => "Lägg till användare",
        'content' => $form->getHTML(), 
        
        ], 'main');
    $this->views->add('theme/info', [
	'content' => $info,
	'class'   => 'user-instructions',
	'links'   => array(
		      ['text' => 'Till huvudmeny', 
		       'href' => $this->url->create('users')]
        ),
     ], 'sidebar');
    

}

/**
 * Update user.
 *
 * @param $id of user to update.
 *
 * @return void
 */
public function updateAction($id = null)
{

    if (!isset($id)) {
        die("Missing id");
    }
    
    $user = $this->users->find($id);
    $name = $user->getProperties()['name'];
    $acronym = $user->getProperties()['acronym'];
    $email = $user->getProperties()['email'];
    $active = $user->getProperties()['active'];
    $deleted = $user->getProperties()['deleted'];
    $created = $user->getProperties()['created'];
    
    $form = new \Anax\HTMLForm\CFormUserUpdate($id, $acronym, $name, $email, $active, $created);
    $form->setDI($this->di);
    $status = $form->check();
    
    $info = $this->di->fileContent->get('users-editinfo.md');
    $info = $this->di->textFilter->doFilter($info, 'shortcode, markdown');
    
    $this->di->theme->setTitle("Redigera användare");
    $this->di->views->add('default/page', [
        'title' => "Redigera användare",
        'content' => "<h4>".$user->getProperties()['acronym']." 
(id ".$user->getProperties()['id'].")</h4>".$form->getHTML()
        ]);
    $this->views->add('theme/info', [
	'content' => $info,
	'class'   => 'user-instructions',
	'links'   => array(
		      ['text' => 'Till huvudmeny', 
		       'href' => $this->url->create('users')]),
      ], 'sidebar');
    

}


public function insertUserAction($acronym, $email=null, $name=null)
{

    if (!isset($acronym)) {
        die("Missing acronym");
    }
    $now = gmdate('Y-m-d H:i:s');

    $this->users->save([
        'acronym' => $acronym,
        'email' => $email,
        'name' => $acronym,
        'password' => password_hash($acronym, PASSWORD_DEFAULT),
        'created' => $now,
        'active' => $now,
    ]);

}

/**
 * Delete user.
 *
 * @param integer $id of user to delete.
 *
 * @return void
 */
public function deleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
 
    $res = $this->users->delete($id);
 
    $url = $this->url->create('users');
    $this->response->redirect($url);
}


/**
 * Delete (soft) user.
 *
 * @param integer $id of user to delete.
 *
 * @return void
 */
public function activateAction($id = null,$route1=null,$route2=null)
{
    if (!isset($id)) {
        die("Missing id");
    }
    
    $route1 = isset($route1) ? $route1:'users';
    
    $route2 = isset($route2) ? "/".$route2:null;
 
    $now = gmdate('Y-m-d H:i:s');
 
    $user = $this->users->find($id);
    
    if ($user->deleted != null) {
      $user->deleted = null;
    }
    elseif ($user->active == null) { 
      $user->active = $now;
    }
    else {
      $user->active = null;
    }
    $user->save();
 
    $url = $this->url->create($route1.$route2);
    $this->response->redirect($url);
}


/**
 * Delete (soft) user.
 *
 * @param integer $id of user to delete.
 *
 * @return void
 */
public function softDeleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
 
    $now = gmdate('Y-m-d H:i:s');
 
    $user = $this->users->find($id);
 
    $user->deleted = $now;
    $user->save();
 
    $url = $this->url->create('users/id/' . $id);
    $this->response->redirect($url);
}

/**
 * List all active and not deleted users.
 *
 * @return void
 */
public function activeAction()
{
    $all = $this->users->query()
        ->where('active IS NOT NULL')
        ->andWhere('deleted is NULL')
        ->execute();
 
    $this->theme->setTitle("Aktiva användare");
    $this->views->add('users/list-all', [
        'users' => $all,
        'title' => "Aktiva användare",
    ], 'main');
    $this->views->add('users/adminmenu', [], 'sidebar');

}


public function inactiveAction()
{
    $all = $this->users->query()
        ->where('active IS NULL')
        ->andWhere('deleted is NULL')
        ->execute();
 
    $this->theme->setTitle("Inaktiva användare");
    $this->views->add('users/list-all', [
        'users' => $all,
        'title' => "Inaktiva användare",
    ], 'main');
    $this->views->add('users/adminmenu', [], 'sidebar');
}

public function discardedAction()
{
    $all = $this->users->query()
        ->where('deleted is NOT NULL')
        ->execute();
 
    $this->theme->setTitle("Papperskorgen");
    $this->views->add('users/list-deleted', [
        'users' => $all,
        'title' => "Papperskorgen",
    ], 'main');
    $this->views->add('users/adminmenu', [], 'sidebar');
}

public function resetUsersAction()
{

    //$this->db->setVerbose();
 
    $this->db->dropTableIfExists('user')->execute();
 
    $this->db->createTable(
        'user',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'acronym' => ['varchar(20)', 'unique', 'not null'],
            'email' => ['varchar(80)'],
            'name' => ['varchar(80)'],
            'password' => ['varchar(255)'],
            'created' => ['datetime'],
            'updated' => ['datetime'],
            'deleted' => ['datetime'],
            'active' => ['datetime'],
            'gravatar' => ['varchar(255)'],
        ]
    )->execute();
    
    $this->db->insert(
        'user',
        ['acronym', 'email', 'name', 'password', 'created', 'active', 'gravatar']
    );
 
    $now = date('Y-m-d H:i:s');
 
    $this->db->execute([
        'admin',
        'admin@dbwebb.se',
        'Administrator',
        password_hash('admin', PASSWORD_DEFAULT),
        $now,
        $now,
        'http://www.gravatar.com/avatar/' . md5(strtolower(trim('admin@dbwebb.se'))) . '.jpg'
    ]);
 
    $this->db->execute([
        'doe',
        'doe@dbwebb.se',
        'John/Jane Doe',
        password_hash('doe', PASSWORD_DEFAULT),
        $now,
        $now,
        'http://www.gravatar.com/avatar/' . md5(strtolower(trim('doe@dbwebb.se'))) . '.jpg'
     ]);
     
         $this->db->execute([
        'maria',
        'choklad@post.utfors.se',
        'Maria',
        password_hash('maria', PASSWORD_DEFAULT),
        $now,
        null,
        'http://www.gravatar.com/avatar/' . md5(strtolower(trim('choklad@post.utfors.se'))) . '.jpg'
     ]);
     
     $this->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'list',
        //'params'     => [],
    ]);
    
    
}

}