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
        'userinfo' => $this->users
    ], 'main');
    

    $this->views->add('users/usermenu', [], 'sidebar');
   
}

/**
 * List most active users.
 *
 * @return void
 */
public function listMostActiveAction()
{
    //$this->db->setVerbose();
    $answers = $this->users->findMostAnswers();
    $issues = $this->users->findMostIssues();
    $comments = $this->users->findMostComments();
    $users = $this->users->findMostActive();
    

    
    $this->di->views->add('wgtotw/plain', [
        'content' => '<h3>Användare med mest aktivitet</h3>', 
        
    ], 'sidebar');
    
    $this->views->add('users/list-most-active', [
       'title' => "Mest aktiva totalt",
       'users'  => $users,
       'activity' => 'totalt',
    ], 'sidebar');
    
    $this->views->add('users/list-most-active', [
       'title' => "Flest frågor",
       'users'  => $issues,
       'activity' => 'frågor',
    ], 'sidebar');
    
    $this->views->add('users/list-most-active', [
       'title' => "Flest svar",
       'users'  => $answers,
       'activity' => 'svar',
    ], 'sidebar');
    
    $this->views->add('users/list-most-active', [
       'title' => "Flest kommentarer",
       'users'  => $comments,
       'activity' => 'kommentarer',
    ], 'sidebar'); 

}


public function listUserScoresAction($id) 
{

    $user = $this->users->find($id);
    $answers = $this->users->findNumAnswers($user->acronym);
    $issues = $this->users->findNumIssues($user->acronym);
    $comments = $this->users->findNumComments($user->acronym);
    
    $arank = $this->users->getUserRank($id, 'answer', 'answer', 'name');
    $irank = $this->users->getUserRank($id, 'issues', 'issues', 'acronym');
    $crank = $this->users->getUserRank($id, 'comments', 'comments', 'name');
    
    $this->vote = new \Meax\Content\Vote();
    $this->vote->setDI($this->di);
    
    $avote = $this->vote->getNumVotes($id, 'answer');
    $ivote = $this->vote->getNumVotes($id, 'issues');
    $cvote = $this->vote->getNumVotes($id, 'comments');
    
    $this->views->add('users/list-user-scores', [
       'title' => "Ranking",
       'user'  => $user,
       'answer' => $answers[0]->total,
       'issues' => $issues[0]->total,
       'comments' => $comments[0]->total,
       'arank' => $arank,
       'irank' => $irank,
       'crank' => $crank,
       'ivote' => $ivote,
       'avote' => $avote,
       'cvote' => $cvote,
    ], 'main');


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
    
    if(empty($user)) {
	
	$url = $this->url->create('users/no-such-user');
	$this->response->redirect($url);
	
	}
    $acronym = $this->users->getAcronym($id);
    
    
 
    $this->theme->setTitle("Användare");
    $this->views->add('users/view', [
        'user' => $user,
        'userinfo'   => $this->users,
    ], 'main');
    $this->views->add('users/usermenu', [], 'sidebar');
    
    $this->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'list-user-scores',
        'params'     => [$id],

    ]);
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

$this->di->theme->setTitle("Lägg till användare");
    
        
    $form = new \Anax\HTMLForm\CFormUserAdd();
    $form->setDI($this->di);
    $status = $form->check();
    
    $info = $this->di->fileContent->get('users-addinfo.md');
    $info = $this->di->textFilter->doFilter($info, 'shortcode, markdown');
  
    
    $this->di->views->add('default/page', [
        'title' => "Lägg till användare",
        'content' => $form->getHTML(), 
        
        ], 'main');
    $this->views->add('wgtotw/info', [
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
$this->di->theme->setTitle("Redigera användare");
if ($this->users->isLoggedIn()) {

    $user = $this->users->find($id);
     if(empty($user)) {
	
	$url = $this->url->create('users/no-such-user');
	$this->response->redirect($url);
	
    }
    
    $name = $user->getProperties()['name'];
    $acronym = $user->getProperties()['acronym'];
    $email = $user->getProperties()['email'];
    $active = $user->getProperties()['active'];
    $deleted = $user->getProperties()['deleted'];
    $created = $user->getProperties()['created'];
    
    if ($this->users->getLoggedInUser() == $acronym || $this->users->getLoggedInUser() == '') {
    
    $form = new \Anax\HTMLForm\CFormUserUpdate($id, $acronym, $name, $email, $active, $created);
    $form->setDI($this->di);
    $status = $form->check();
    
    $info = $this->di->fileContent->get('users-editinfo.md');
    $info = $this->di->textFilter->doFilter($info, 'shortcode, markdown');
    
    
    $this->di->views->add('default/page', [
        'title' => "Redigera användare",
        'content' => "<h4>".$user->getProperties()['acronym']." 
(id ".$user->getProperties()['id'].")</h4>".$form->getHTML()
        ]);
    $this->views->add('wgtotw/info', [
	'content' => $info,
	'class'   => 'user-instructions',
	'links'   => array(
		      ['text' => 'Till huvudmeny', 
		       'href' => $this->url->create('users')]),
      ], 'sidebar');
      
      }
      
      else {
      
      $this->views->add('users/loginedituser-message', [
    ], 'flash'); 
     }
    }
 
  else {
     $this->views->add('users/login-message', [
    ], 'flash'); 
     }
  
  
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
         'userinfo' => $this->users
    ], 'main');
    $this->views->add('users/usermenu', [], 'sidebar');

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
         'userinfo' => $this->users
    ], 'main');
    $this->views->add('users/usermenu', [], 'sidebar');
}

public function discardedAction()

{
    if ($this->users->isLoggedIn()) {
    
    $all = $this->users->query()
        ->where('deleted is NOT NULL')
        ->execute();
 
    $this->theme->setTitle("Papperskorgen");
    $this->views->add('users/list-deleted', [
        'users' => $all,
        'title' => "Papperskorgen",
    ], 'main');
    $this->views->add('users/usermenu', [], 'sidebar');
    
    }
    
    else {
    
    $this->views->add('users/login-admin-message', [
    ], 'flash');
    
    }
}

public function resetUsersAction()
{


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
    
 
    }
    
    public function autoPopulateAction() 
    {
    
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
        $now,
        'http://www.gravatar.com/avatar/' . md5(strtolower(trim('choklad@post.utfors.se'))) . '.jpg'
     ]);
     
    
    
}

  public function setupPopulateAction() 
  {
  
    $this->resetUsersAction();
    $this->autoPopulateAction();
    
  
  }

  public function noSuchUserAction()
  {
  
  $this->theme->setTitle("Fel");
    $this->views->add('default/error', [
	'title' => "Fel",
	'content' => "Användaren finns inte",
    ], 'main');
    $this->views->add('users/usermenu', [], 'sidebar');
  }

}