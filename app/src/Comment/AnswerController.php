<?php

namespace Phpmvc\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class AnswerController implements \Anax\DI\IInjectionAware
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
    
    	
        $answer = new \Anax\Comments\Answer();
        $controller = 'answer';
        $answer->setDI($this->di);
        
        $comments = new \Anax\Comments\Comments();
        $comments->setDI($this->di);

        $all = $answer->findAll($pagekey);
        
        $user = new \Anax\Users\User();
        $user->setDI($this->di);
        
        $acronym = $user->getLoggedInUser();
        
        
        if ($user->isLoggedIn()) {
        
	 if ($this->getFormVisibility() == 'show-form' && $this->getFormId() == 'answer') {
        
	    $this->showFormAction($pagekey, $redirect, $acronym);
	    
	  }
	  
	  else {
	   $this->showHideForm($pagekey, $redirect);
	  }
        }
        
        else {
	  //$this->hideForm();
        }
        
       	$postformid = $this->getFormId();
       

 
        foreach ($all as $answer) {
        
	  $id = $answer->getProperties()['id'];
	  $commentformvisibility = null;
	  
	  if ($postformid == $id) {
	    $commentformvisibility = 'show-form';
	  }


	  $this->views->add('comment/answer', [
	      'id' => $id,
	      'answer' => $answer,
	      'pagekey'   => $pagekey,
	      'redirect'  => $redirect,
	      'controller' => $controller,
	      'user' => $user,
	  ]);
	  
	  $this->di->dispatcher->forward([
	  'controller' => 'comments',
	  'action'     => 'view',
	  'params'     => [$id, $commentformvisibility,'issues/id/'.$pagekey, 'answer', $id],
	  ]);

        
        }
        
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

        
    public function showFormAction($pagekey, $redirect, $acronym) 
    {
	$aform = new \Anax\HTMLForm\CFormAnswerAdd($pagekey, $redirect, $acronym);
	$aform->setDI($this->di);
	$aform->check();
	    
	$this->di->views->add('wgtotw/plain', [
	   'content' => $aform->getHTML(), 
	], 'main');
        
    }
    
    public function hideForm() 
    {
    
      $this->di->views->addString('', 'main');
    }

    public function showHideForm($pagekey, $redirect, $formid = 'answer') 
    {
	$this->di->views->add('comment/getanswerformhide', [
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
    
    public function setupAnswerAction() 
    {
      //$this->di->db->setVerbose();
 
      $this->di->db->dropTableIfExists('answer')->execute();
  
      $this->di->db->createTable(
	  'answer',
	  [
	      'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
	      'content' => ['text', 'not null'],
	      'name'    => ['varchar(80)'],
	      'pagekey' => ['varchar(80)'],
	      'timestamp' => ['datetime'],
	      'updated' => ['datetime'],
	      'ip'      => ['varchar(80)'],
	      'web'     => ['varchar(200)']
	      
	  ]
      )->execute();
      
      $this->di->db->insert(
	  'answer',
	  ['content', 'name', 'pagekey', 'timestamp', 'updated', 'ip', 'web']
      );
  
      $now = date('Y-m-d H:i:s');
  
      $this->di->db->execute([
	  'Jag har ett svar',
	  'admin',
	  '1',
	  $now,
	  null,
	  $this->di->request->getServer('REMOTE_ADDR'),
	  null
      ]);
      
	  $this->di->db->execute([
	  'Ett svar som inte är ett svar',
	  'maria',
	  '2',
	  $now,
	  null,
	  $this->di->request->getServer('REMOTE_ADDR'),
	  null
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
