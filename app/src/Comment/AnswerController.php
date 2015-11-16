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
    public function viewAction($pagekey = null, $formvisibility = null, $redirect='', $issueposter=null)
    {
	
        $answer = new \Anax\Comments\Answer();
        $controller = 'answer';
        $answer->setDI($this->di);
        
        $comments = new \Anax\Comments\Comments();
        $comments->setDI($this->di);

        $all = $answer->findAll($pagekey);
        
        $user = new \Anax\Users\User();
        $user->setDI($this->di);
        
        $vote = new \Meax\Content\Vote();
        $vote->setDI($this->di);
        
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
	      'issueposter' => $issueposter,
	      'user' => $user,
	      'vote' => $vote,
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
    
    
    public function acceptAction($answerid, $issueid)
    {
	$this->newanswer = new \Anax\Comments\Answer();
        $this->newanswer->setDI($this->di);
        
        $this->newanswer->setAllAcceptNull($issueid);
        $this->newanswer->save(array('accepted' => true, 'id' => $answerid));
        
        $redirect = $this->url->create("issues/id").'/'.$issueid;
        $this->response->redirect($redirect);
    
    }
    
    public function unAcceptAction($answerid, $issueid)
    {
	$this->newanswer = new \Anax\Comments\Answer();
        $this->newanswer->setDI($this->di);
        $saved = $this->newanswer->save(array('accepted' => null, 'id' => $answerid));
        
        $redirect = $this->url->create("issues/id").'/'.$issueid;
        $this->response->redirect($redirect);
    
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
 
	if(!is_numeric($pagekey) || !is_numeric($id)) {
	
	$url = $this->url->create('answer/invalid-input?url='.$this->di->request->getCurrentUrl());
	$this->response->redirect($url);
	
	}
	    
        $answers = new \Anax\Comments\Answer();
        $answers->setDI($this->di);
        
        $answer = $answers->findAnswer($pagekey, $id);
        
        if(empty($answer)) {
	
	$url = $this->url->create('answer/invalid-dbresult');
	$this->response->redirect($url);
	
	}
        
        $user = new \Anax\Users\User();
        $user->setDI($this->di);
       

        $form = new \Anax\HTMLForm\CFormAnswerEdit($pagekey, 'issues/id/'.$pagekey, $answer[0]->name, $id, $answer[0]->content);
	$form->setDI($this->di);
	$form->check();
        
        $this->theme->setTitle("Redigera svar");
        
        if ($user->isLoggedIn()) {
	  if ($user->getLoggedInUser() == $answer[0]->name) {
	  
	  $this->di->views->add('default/page', [
	      'title' => "Redigera svar",
	      'content' => '<h4>Svarsid #'.$id.'</h4>'.$form->getHTML(), 
	      ], 'main');
	  
	  $issue = new \Meax\Content\Issues();
	  $issue->setDI($this->di);
	  
	  $content = $issue->find($pagekey);
	  
	  if(empty($content)) {
	
	  $url = $this->url->create('answer/invalid-dbresult');
	  $this->response->redirect($url);
	
	  }
	  
	  $title = $content->getProperties()['title'];
	  $data = $content->getProperties()['data'];
	  
	  $this->di->views->add('contenttags/reply-issue', [
	      'title' => $title, 
	      'data'  => $data,
	      ], 'main');
	      
	  }
	 
	 else {
	 $this->views->add('users/loginedit-message', [
	  ], 'flash'); 
	 
	 }
	 }
	 
	 else {
	 $this->views->add('users/login-message', [
	  ], 'flash'); 
	 }
        
    }
    
    public function setupAnswerAction() 
    {
 
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
	      'accepted'     => ['boolean']
	      
	  ]
      )->execute();
      
      $this->di->db->insert(
	  'answer',
	  ['content', 'name', 'pagekey', 'timestamp', 'updated', 'ip', 'accepted']
      );
  
      $now = date('Y-m-d H:i:s');
  
      $this->di->db->execute([
	  'Fabriano är absolut bäst',
	  'admin',
	  '1',
	  $now,
	  null,
	  $this->di->request->getServer('REMOTE_ADDR'),
	  true
      ]);
      
	  $this->di->db->execute([
	  'Googla!',
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
    
  public function invalidInputAction()
  {
  
  $this->theme->setTitle("Fel");
    $this->views->add('default/error', [
	'title' => "Något blev fel",
	'content' => "Information saknas för att kunna visa sidan:<br>".$this->di->request->getGet('url'),
    ], 'main');
  }
    
     public function invalidDbresultAction()
  {
  
  $this->theme->setTitle("Fel");
    $this->views->add('default/error', [
	'title' => "Något blev fel",
	'content' => "En sökning i databasen gav inga resultat",
    ], 'main');
  }

}
