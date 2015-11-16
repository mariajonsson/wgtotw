<?php

namespace Anax\HTMLForm;

/**
 * Form to add comment
 *
 */
class CFormTagAdd extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

      private $redirect;

    /**
     * Constructor
     *
     */
    public function __construct($redirect)
    {
    	
        parent::__construct(['id' => 'tag'], [
        	
        	'tagname' => [
                'type'        => 'text',
                'label'       => 'Tagg',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
            'tagslug' => [
                'type'        => 'text',
                'label'       => 'Slug',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
        	
            
            'submit-tag' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
                'value'     => 'Posta',
            ],
            'reset' => [
                'type'      => 'reset',
                //'callback'  => [$this, 'callbackReset'],
                'value'     => 'Återställ',
            ],
            
        ]);
        
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
	
	$this->newtag = new \Meax\Content\TagBasic();
        $this->newtag->setDI($this->di);
        
        $exists = $this->newtag->findTag($this->Value('tagname'));  
        
        if ($exists) {
        $this->AddOutput("<p><i>En tagg med detta namn finns redan.</i></p>");
        return false;
        }
        else {
        $saved = $this->newtag->save(array('tagname' => $this->Value('tagname'), 'tagslug' => $this->Value('tagslug')));
        
        $id = $this->newtag->findLast();
       // $this->saveInSession = true;
        }
             
       
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
        $this->redirectTo('tags');
    }


    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        //$this->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
        $this->redirectTo();
    }
}
