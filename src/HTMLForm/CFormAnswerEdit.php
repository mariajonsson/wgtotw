<?php

namespace Anax\HTMLForm;

/**
 * Form to add comment
 *
 */
class CFormAnswerEdit extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    private $pagekey;
    private $redirect;

    /**
     * Constructor
     *
     */
    public function __construct($pagekey, $redirect, $acronym, $id, $answer)
    {
        parent::__construct(['id' => 'answer'], [
        	
            'answer' => [
                'type'        => 'textarea',
                'label'       => '<big><strong>Uppdatera ditt svar</strong></big>',
                'required'    => true,
                'value'       => $answer,
                'validation'  => ['not_empty'],
            ],
            
            'name' => [
                'type'        => 'hidden',
                'value'       => $acronym,
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
            'answerid' => [
                'type'        => 'hidden',
                'value'       => $id,
                'validation'  => ['not_empty'],
            ],
        
         
            'submitanswer' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
                'value'     => 'Uppdatera',
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
	if (!empty($_POST['submitanswer'])) {
	$this->newanswer = new \Anax\Comments\Answer();
        $this->newanswer->setDI($this->di);
        $saved = $this->newanswer->save(array('id' => $this->Value('answerid'), 'content' => $this->Value('answer'), 'name' => $this->Value('name'), 'pagekey' => $this->pagekey, 'updated' => $now));
    
       // $this->saveInSession = true;
        
        if($saved) 
        {
        return true;
        }
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
