<?php

namespace Anax\HTMLForm;

/**
 * Form to edit comment
 *
 */
class CFormCommentEdit extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    private $id; 
    private $pagekey;
    private $redirect;

    /**
     * Constructor
     *
     */
    public function __construct($id, $content, $name, $web, $mail, $pagekey, $redirect)
    {
        parent::__construct([], [
        	
        	'content' => [
                'type'        => 'textarea',
                'label'       => 'Kommentar',
                'value'       =>  $content,
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
            'name' => [
                'type'        => 'text',
                'label'       => 'Namn',
                'value'       =>  $name,
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
            'mail' => [
                'type'        => 'text',
                'label'       => 'E-post',
                'value'       =>  $mail,
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
            ],
            
            'web' => [
            	'type'        => 'text',
            	'label'       => 'Hemsida',
            	'value'       =>  $web,
            	'validation'  => ['web_adress'],
            ],  
            
            'submit' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
                'value'     => 'Spara',
            ],
            'reset' => [
                'type'      => 'reset',
                'value'     => 'Återställ',
            ],
            
            'delete' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackDelete'],
                'value'     => 'Radera',
            ],
            
        ]);
        
        $this->id = $id;
        $this->pagekey = $pagekey;
        $this->redirect = $redirect;
    }



    /**
     * Customise the check() method.
     *
     * @param callable $callIfSuccess handler to call if function returns true.
     * @param callable $callIfFail    handler to call if function returns true.
     */
    public function check($callIfSuccess = null, $callIfFail = null)
    {
        return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {

        $now = date('Y-m-d H:i:s');
        
        $this->comment = new \Anax\Comments\Comments();
        $this->comment->setDI($this->di);
        $saved = $this->comment->save(array('id' => $this->id, 'content' => $this->Value('content'), 'mail' => $this->Value('mail'), 'name' => $this->Value('name'), 'pagekey' => $this->pagekey, 'updated' => $now, 'ip' => $this->di->request->getServer('REMOTE_ADDR'), 'web' => $this->Value('web'), 'gravatar' => 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->Value('mail')))) . '.jpg'));
    
	//$this->saveInSession = true;
        
        if($saved) 
        {
        return true;
        }
        else return false;
    }
    
    public function callbackDelete()
    {
    	$this->comment = new \Anax\Comments\Comments();
        $this->comment->setDI($this->di);
        
        $deleted = $this->comment->delete($this->id);
        
        if($deleted) 
        {
        return true;
        }
        else return false;
    	
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
         $this->redirectTo($this->redirect);
    }


    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
        
    }
}
