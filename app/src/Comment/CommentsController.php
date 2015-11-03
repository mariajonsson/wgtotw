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
    public function viewAction($pagekey = null, $formvisibility = null, $redirect='', $pagetype = null, $formid = null)
    {

	$controller = 'comments';
        
        $comments = new \Anax\Comments\Comments();
        $comments->setDI($this->di);
        $all = $comments->findAll($pagekey, $pagetype);
        
        $user = new \Anax\Users\User();
        $user->setDI($this->di);
        $acronym = $user->getLoggedInUser();

             
        if ($user->isLoggedIn()) {
        
	  if ($this->getFormVisibility() == 'show-form' && $formid == $this->getFormId()) {
	  
      
	    $this->showFormAction($pagekey, $redirect, $acronym, $pagetype, $formid);
	  }
	  
	  else {
	   $this->showHideForm($pagekey, $redirect, $formid);
	  }
        }
        
        else {
	  $this->hideForm();
        }

        $this->views->add('comment/comments', [
            'comments' => $all,
            'pagekey'   => $pagekey,
            'redirect'  => $redirect,
            'controller' => $controller,
            'user' => $user,
        ]);
    }
       
    public function getFormVisibility() 
    {
	$formvisibility = $this->di->request->getGet('form');
	return $formvisibility;

    }
    
    public function getFormId() 
    {
      $formid = $this->di->request->getGet('formid');
      return $formid;
    } 

        
    public function showFormAction($pagekey, $redirect, $acronym, $pagetype, $formid) 
    {
	$undourl = '<p><a href="'.$this->di->get('url')->create($redirect).'">Ångra</p>';    
	$form = new \Anax\HTMLForm\CFormCommentAdd($pagekey, $redirect, $acronym, $pagetype, $formid);
	$form->setDI($this->di);
	$form->check();
	
	$this->di->views->add('wgtotw/plain', [
	'content' => $form->getHTML().$undourl, 
	], 'main');
        
    }
    

    
    public function hideForm() 
    {
    
      $this->di->views->addString('Logga in för att kommentera', 'main');
    }

    public function showHideForm($pagekey, $redirect, $formid) 
    {
	$this->di->views->add('comment/getformhide', [
	'redirect' => $redirect,
	'pagekey' => $pagekey,
	'formid' => $formid,
	], 'main');
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
	      'name'    => ['varchar(80)'],
	      'pagekey' => ['integer'],
	      'pagetype' => ['varchar(80)'],
	      'timestamp' => ['datetime'],
	      'updated' => ['datetime'],
	      'ip'      => ['varchar(80)']
	      
	  ]
      )->execute();
      
      $this->di->db->insert(
	  'comments',
	  ['content', 'name', 'pagekey', 'pagetype', 'timestamp', 'updated', 'ip']
      );
  
      $now = date('Y-m-d H:i:s');
  
      $this->di->db->execute([
	  'En första kommentar',
	  'admin',
	  '1',
	  'issues',
	  $now,
	  null,
	  $this->di->request->getServer('REMOTE_ADDR')
      ]);
      
	  $this->di->db->execute([
	  'Hej!',
	  'maria',
	  '2',
	  'answer',
	  $now,
	  null,
	  $this->di->request->getServer('REMOTE_ADDR')
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
