<?php

namespace Anax\HTMLForm;

/**
 * Form to add comment
 *
 */
class CFormCommentAdd extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    private $pagekey;
    private $redirect;

    /**
     * Constructor
     *
     */
    public function __construct($pagekey, $redirect)
    {
        parent::__construct([], [
        	
        	'content' => [
                'type'        => 'textarea',
                'label'       => 'Kommentar',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
            'name' => [
                'type'        => 'text',
                'label'       => 'Namn',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
            'mail' => [
                'type'        => 'text',
                'label'       => 'E-post',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
            ],
            
            'web' => [
            	'type'        => 'text',
            	'label'       => 'Hemsida',
            	//'validation'  => ['web_adress'],
            ],  
            
            'submit' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
                'value'     => 'Spara',
            ],
            'reset' => [
                'type'      => 'reset',
                //'callback'  => [$this, 'callbackReset'],
                'value'     => 'Återställ',
            ],
            
        ]);
        
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
        
	$this->newcomment = new \Anax\Comments\Comments();
        $this->newcomment->setDI($this->di);
        $saved = $this->newcomment->save(array('content' => $this->Value('content'), 'mail' => $this->Value('mail'), 'name' => $this->Value('name'), 'pagekey' => $this->pagekey, 'timestamp' => $now, 'ip' => $this->di->request->getServer('REMOTE_ADDR'), 'web' => $this->Value('web'), 'gravatar' => 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->Value('mail')))) . '.jpg'));
    
       // $this->saveInSession = true;
        
        if($saved) 
        {
        return true;
        }
        else return false;
    }

     /**
     * Callback reset
     *
     */
    public function callbackReset()
    {
         $this->redirectTo($this->redirect);
    }


    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        $this->AddOutput("<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it</i></p>");
        return false;
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
        //$this->redirectTo('comments/edit');
    }
}
