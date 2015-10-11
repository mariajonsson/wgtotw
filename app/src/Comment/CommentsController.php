<?php

namespace Phpmvc\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    private $errormessage;

    /**
     * View all comments.
     *
     * @return void
     */
    public function viewAction($pagekey = null, $formvisibility = null, $redirect='')
    {
    	$undourl = '<p><a href="'.$this->di->get('url')->create($redirect).'">Ångra</p>';
    	
    	$form1 = new \Anax\HTMLForm\CFormCommentUndo($redirect);
	    $form1->setDI($this->di);
	    $form1->check();
	    $undourl = $form1->getHTML();
    	
        $comments = new \Anax\Comments\Comments();
        $controller = 'comments';
        $comments->setDI($this->di);

        $all = $comments->findAll($pagekey);
        
        $user = new \Anax\Users\User();
        $user->setDI($this->di);
        
        $formvisibility = $user->isLoggedIn() == true ? 'show-form' : null;
               
        switch ($formvisibility) {
        
        case 'show-form':
	    
	    $form = new \Anax\HTMLForm\CFormCommentAdd($pagekey, $redirect);
	    $form->setDI($this->di);
	    $form->check();
	    
	    $this->di->views->add('default/page', [
	    'title' => "Lägg till kommentar",
	    'content' => $form->getHTML().$undourl, 
	    ], 'main');
        break;
        	
        default:
        
	  $this->di->views->addString('Logga in för att kommentera', 'main');
         
	break;
        }

        $this->views->add('comment/comments', [
            'comments' => $all,
            'pagekey'   => $pagekey,
            'redirect'  => $redirect,
            'controller' => $controller,
        ]);
    }
        
        


    
    /**
    *
    * Edit a comment
    *
    * @param string $pagekey selects the array with the page-id.
    * @param $id selects the comment to edit.
    *
    */      
    public function editAction($pagekey, $id, $redirect='')
    {
 
    	//$undourl = '<p><a href="'.$this->di->get('url')->create($redirect).'">Ångra</p>';
    	
    	$form1 = new \Anax\HTMLForm\CFormCommentUndo($redirect);
	$form1->setDI($this->di);
	$form1->check();
	$undourl = $form1->getHTML();
	    
        $comments = new \Anax\Comments\Comments();
        $controller = 'comments';
        $comments->setDI($this->di);
        
        $comment = $comments->findComment($pagekey, $id);
        $comment = (is_object($comment[0])) ? get_object_vars($comment[0]) : $comment;

        $form = new \Anax\HTMLForm\CFormCommentEdit($id, $comment['content'], $comment['name'], $comment['web'], $comment['mail'], $pagekey, $redirect);
	$form->setDI($this->di);
	$form->check();
        
        $this->theme->setTitle("Redigera kommentar");
        
        $this->di->views->add('default/page', [
	    'title' => "Redigera kommentar",
	    'content' => '<h4>Kommentarid #'.$id.'</h4>'.$form->getHTML().$undourl, 
	    ], 'main');
        
    }
    
    public function setupCommentAction() 
    {
      //$this->di->db->setVerbose();
 
      $this->di->db->dropTableIfExists('comments')->execute();
  
      $this->di->db->createTable(
	  'comments',
	  [
	      'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
	      'content' => ['text', 'not null'],
	      'mail'   => ['varchar(80)'],
	      'name'    => ['varchar(80)'],
	      'pagekey' => ['varchar(80)'],
	      'timestamp' => ['datetime'],
	      'updated' => ['datetime'],
	      'ip'      => ['varchar(80)'],
	      'web'     => ['varchar(200)'],
	      'gravatar' => ['varchar(200)']
	      
	  ]
      )->execute();
      
      $this->di->db->insert(
	  'comments',
	  ['content', 'mail', 'name', 'pagekey', 'timestamp', 'updated', 'ip', 'web', 'gravatar']
      );
  
      $now = date('Y-m-d H:i:s');
  
      $this->di->db->execute([
	  'En första kommentar',
	  'admin@dbwebb.se',
	  'Administrator',
	  'comment-page',
	  $now,
	  null,
	  $this->di->request->getServer('REMOTE_ADDR'),
	  null,
	  'http://www.gravatar.com/avatar/' . md5(strtolower(trim('admin@dbwebb.se'))) . '.jpg'
      ]);
      
	  $this->di->db->execute([
	  'Hej!',
	  'admin@dbwebb.se',
	  'Maria',
	  'me-page',
	  $now,
	  null,
	  $this->di->request->getServer('REMOTE_ADDR'),
	  null,
	  'http://www.gravatar.com/avatar/' . md5(strtolower(trim('admin@dbwebb.se'))) . '.jpg'
      ]);
      
	  $this->di->theme->setTitle("Kommentarer");
	  $this->di->views->add('comment/index');
	  $formvisibility = $this->di->request->getPost('form');
	  $this->di->dispatcher->forward([
        'controller' => 'comments',
        'action'     => 'view',
        'params'     => ['comment-page', $formvisibility,'comment'],
    ]);
    
    }



    /**
     * Remove all comments.
     *
     * @return void
     */
    public function removeAllAction()
    {
        $comments = new \Anax\Comments\Comments();
        $comments->setDI($this->di);
                
        $comments->deleteAll();
        
        $this->theme->setTitle("Raderat");
        
        $this->di->views->add('default/page', [
	    'title' => "Raderat",
	    'content' => 'Kommentarer har tagits bort.', 
	    ], 'main');
        
        
    }
    

}
