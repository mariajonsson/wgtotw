<?php

namespace Anax\HTMLForm;

/**
 * Form to add comment
 *
 */
class CFormTagEdit extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

      private $redirect;
      private $id;

    /**
     * Constructor
     *
     */
    public function __construct($id, $tagname, $tagslug)
    {
    	
        parent::__construct(['id' => 'tag'], [
        	
            'tagname' => [
                'type'        => 'text',
                'label'       => 'Tagg',
                'required'    => true,
                'value'       => $tagname,
                'validation'  => ['not_empty'],
            ],
            
            'tagslug' => [
                'type'        => 'text',
                'label'       => 'Slug',
                'required'    => true,
                'value'       => $tagslug,
                'validation'  => ['not_empty'],
            ],
            
            'id' => [
                'type'        => 'hidden',
                'required'    => true,
                'value'       => $id,
            ],
        	
            
            'submit-tag' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
                'value'     => 'Uppdatera',
            ],
            
            'delete' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackDelete'],
                'value'     => 'Radera',
            ],
            
            'reset' => [
                'type'      => 'reset',
                //'callback'  => [$this, 'callbackReset'],
                'value'     => 'Återställ',
            ],
            
        ]);
        
         $this->id = $id;
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
        

        $saved = $this->newtag->save(array('id' => $this->Value('id'), 'tagname' => $this->Value('tagname'), 'tagslug' => $this->Value('tagslug')));
        
     
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

     public function callbackDelete()
    {
    	$this->newtag = new \Meax\Content\TagBasic();
        $this->newtag->setDI($this->di);
        
        $deleted = $this->newtag->delete($this->id);
        
        if($deleted) 
        {
        return true;
        }
        else return false;
    	
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
