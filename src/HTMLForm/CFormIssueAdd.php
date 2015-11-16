<?php

namespace Anax\HTMLForm;

/**
 * Form to add comment
 *
 */
class CFormIssueAdd extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

      private $redirect;

    /**
     * Constructor
     *
     */
    public function __construct($redirect, $acronym, $tags)
    {
    	
        parent::__construct(['id' => 'issue'], [
        	
        	'title' => [
                'type'        => 'text',
                'label'       => 'Rubrik',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
        	
            'data' => [
                'type'        => 'textarea',
                'label'       => 'Ställ fråga',
                'required'    => true,
                'validation'  => ['not_empty'],
                'description' => 'Du kan använda <a href="http://daringfireball.net/projects/markdown/basics">markdown</a> för att formatera texten'
            ],
           
            
            'name' => [
                'type'        => 'hidden',
                'value'       => $acronym,
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            

            'taglist' => [
            	'type'        => 'checkbox-multiple',
            	'values'       => $tags,
            	'checked'     => [],
            ],
         
            'submit-issue' => [
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
	
	$this->newissue = new \Meax\Content\Issues();
        $this->newissue->setDI($this->di);
        $saved = $this->newissue->save(array('title' => $this->Value('title'), 'acronym' => $this->Value('name'), 'created' => $now, 'data' => $this->Value('data'), 'published' => $now));
    
        $id = $this->newissue->findLast();
       // $this->saveInSession = true;
       
       $this->contenttag = new \Meax\Content\ContentTag();
       $this->contenttag->setDI($this->di);
       
       $this->tag = new \Meax\Content\TagBasic();
       $this->tag->setDI($this->di);
       
       if(!empty($_POST['taglist'])) {
       
       foreach($_POST['taglist'] as $key => $tagname)
       {
       	   if ($id !=null) {
       	   $tagid = $this->tag->getIdForName($tagname);
       	   $this->contenttag->create(array('tagid' => $tagid, 'contentid' => $id));
       	   }
       }
       
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
        $this->redirectTo('issues');
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
