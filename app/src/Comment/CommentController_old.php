<?php

namespace Phpmvc\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentController implements \Anax\DI\IInjectionAware
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
        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $all = $comments->findAll($pagekey);
        
        
        switch ($formvisibility) {
        
        case 'show-form':
        	$this->views->add('comment/form', [
        		'mail'      => null,
        		'web'       => null,
        		'name'      => null,
        		'content'   => null,
        		'output'    => null,
        		'pagekey'   => $pagekey,
        		'redirect'  => $redirect
        	]);
        	break;
        	
        default:
        	$this->views->add('comment/formhide', [
        		'pagekey'   => $pagekey,
        		'redirect'  => $redirect,
        	]);

        	break;
        }

        $this->views->add('comment/comments', [
            'comments' => $all,
            'pagekey'   => $pagekey,
            'redirect'  => $redirect,
        ]);
    }
        
        
     /**
     * Validate input
     *
     * 
     *
     * @return true or false.
     */
    public function validateCommentAction()
    {
	$validator = new \EmailValidator\Validator();
        
        $isemail = $validator->isEmail($this->request->getPost('mail'));
        $isname = preg_match("/^[a-zA-Z ]*$/",$this->request->getPost('name'));
        $isurl = (($this->request->getPost('web')) !== '') ? preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $this->request->getPost('web')):true;
        
        $this->errormessage = !$isemail ? '<br>* E-postadress.':'';
	$this->errormessage .= !$isname ? '<br>* Namn får endast innehålla bokstäver och mellanslag.':'';
	$this->errormessage .= !$isurl ? '<br>* Fältet för hemsida måste innehålla en korrekt url eller vara tomt.':'';
	
	return ($isemail && $isname && $isurl);
    
    }
    
     /**
     * Re-enter form after failed validation
     *
     * @param string $view, which view to include
     * @param string $pagekey, which page-id to select comments
     * @param $id which comment id to edit, if any
     *
     * @return void.
     */
    public function editFormAction($view, $pagekey, $id=null) 
    {
    	  
      $this->theme->setTitle("Felaktiga uppgifter");
        
      $this->views->add('comment/'.$view, [
      'mail'      => $this->request->getPost('mail'),
      'web'       => $this->request->getPost('web'),
      'name'      => $this->request->getPost('name'),
      'content'   => $this->request->getPost('content'),
      'pagekey'   => $pagekey,
      'id'        => $id,
      'redirect'  => $this->request->getPost('redirect'),
      'output'    => 'Du har fyllt i felaktiga uppgifter.<br>'.$this->errormessage.' <br><br>Fyll i formuläret igen eller <a href="'.$this->request->getPost("redirect").'">gå tillbaka</a> till sidan du kom från.'
      ]);
    
    
    }

    
    
    /**
     * Add a comment.
     *
     * @return void
     */
    public function addAction($pagekey)
    {
        $isPosted = $this->request->getPost('doCreate');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $isvalid = $this->validateCommentAction();
        
        if ($isvalid) {
                
        $comment = [
            'content'   => $this->request->getPost('content'),
            'name'      => $this->request->getPost('name'),
            'web'       => $this->request->getPost('web'),
            'mail'      => $this->request->getPost('mail'),
            'gravatar'  => 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->request->getPost('mail')))) . '.jpg',
            'timestamp' => time(),
            'ip'        => $this->request->getServer('REMOTE_ADDR'),
            
        ];
        //$pagekey = $this->request->getPost('pagekey');

        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $comments->add($comment,$pagekey);
        
        $this->response->redirect($this->request->getPost('redirect'));
        }
        
        else {
        
	$this->editFormAction('form', $pagekey);        
        
        }

        
    }
    
     /**
     * Save a comment.
     *
     * @param $pagekey selects page-id.
     * @param $id selects the comment to save.
     *
     * @return void
     */
    public function saveAction($pagekey, $id)
    {
        $isPosted = $this->request->getPost('doSave');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }
        
        $isvalid = $this->validateCommentAction();
        
        if ($isvalid) {

        $editedComment = [
            'content'   => $this->request->getPost('content'),
            'name'      => $this->request->getPost('name'),
            'web'       => $this->request->getPost('web'),
            'mail'      => $this->request->getPost('mail'),
            'gravatar'  => 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->request->getPost('mail')))) . '.jpg',
            'timestamp' => time(),
            'ip'        => $this->request->getServer('REMOTE_ADDR'),
        ];
        
        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);
        
        $comments->editComment($editedComment, $pagekey, $id);

        $this->response->redirect($this->request->getPost('redirect'));
        
        }
        
        else {
        
	$this->editFormAction('edit', $pagekey, $id);
        
        
        }
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
        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $comment = $comments->findComment($pagekey, $id);

        $this->theme->setTitle("Redigera kommentar");

        $this->views->add('comment/edit', [
            'redirect'  => $redirect,
            'content'   => $comment['content'],
            'name'      => $comment['name'],
            'web'       => $comment['web'],
            'mail'      => $comment['mail'],
            'output'    => null,
            'id'        => $id,
            'pagekey'   => $pagekey,
        ]);
    }


    /**
     * Delete a comment.
     *
     * @param string $pagekey is a key that selects the array for the page-id.
     * @param $id selects the comment to delete
     *
     * @return void
     */
    public function deleteAction($pagekey, $id)
    {
        $isPosted = $this->request->getPost('doDelete');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $comments->deleteComment($pagekey, $id);

        $this->response->redirect($this->request->getPost('redirect'));
    }

    /**
     * Remove all comments.
     *
     * @return void
     */
    public function removeAllAction()
    {
        $isPosted = $this->request->getPost('doRemoveAll');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $comments->deleteAll();

        $this->response->redirect($this->request->getPost('redirect'));
    }
    

}
