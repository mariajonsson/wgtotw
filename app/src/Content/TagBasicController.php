<?php
namespace Meax\Content;
 
/**
 * A controller for content and admin related events.
 *
 */
class TagBasicController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    
 
/**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->content = new \Meax\Content\TagBasic();
    $this->content->setDI($this->di);
    $this->user = new \Anax\Users\User();
    $this->user->setDI($this->di);
}

/**
 * List all content.
 *
 * @return void
 */
public function listAction()
{
 
    $all = $this->content->findAll();
    $acronym = $this->user->getLoggedInUser();
    
    $this->theme->setTitle("Taggar");
    
     if ($this->user->isLoggedIn()) {

     $this->views->add('tags/new-tag', [
    ], 'flash');
    }
    
    $this->views->add('tags/list-all', [
        'content' => $all,
        'title' => "Taggar",
        'loggedinuser' => $acronym,
    ], 'main');

}


/**
 * List content with id.
 *
 * @param int $id of content post to display
 *
 * @return void
 */
public function idAction($id = null)
{
    $post = $this->content->find($id);
    
    if(empty($post)) {
	
	$url = $this->url->create('tag-basic/invalid-dbresult');
	$this->response->redirect($url);
	
	}
 
    $this->theme->setTitle("Content");
    $this->views->add('tags/view', [
        'controller' => 'tag-basic',
        'post' => $post,
    ], 'main');

}

/**
 * Add new content.
 *
 * 
 *
 * @return void
 */
public function addAction()
{

    $acronym = $this->user->getLoggedInUser();
    

    $this->di->theme->setTitle("Skapa tagg");
    
     if ($this->user->isLoggedIn()) {
    $form = new \Anax\HTMLForm\CFormTagAdd('');
	$form->setDI($this->di);
	$form->check();
	    
	$this->di->views->add('default/page', [
	   'title' => 'Skapa ny tagg',
	   'content' => $form->getHTML(), 
	], 'main');
     }
     else {
     $this->views->add('users/login-message', [
    ], 'flash'); 
     }

}



/**
 * Update content.
 *
 * @param $id of content to update.
 *
 * @return void
 */
public function updateAction($id = null)
{

     $acronym = $this->user->getLoggedInUser();
     
     if ($acronym == 'admin') {

	if(empty($id)) {
	$url = $this->url->create('issues/invalid-input').'?url='.$this->di->request->getCurrentUrl();
	$this->response->redirect($url);
	
	}
    
     $tag = $this->content->find($id);
     
      if(empty($tag)) {
	
	$url = $this->url->create('issues/invalid-dbresult');
	$this->response->redirect($url);
	
	}
     
     $name = $tag->getProperties()['tagname'];
      $slug = $tag->getProperties()['tagslug'];
    

    $this->di->theme->setTitle("Redigera tagg");
    
        $form = new \Anax\HTMLForm\CFormTagEdit($id, $name, $slug);
	$form->setDI($this->di);
	$form->check();
	    
	$this->di->views->add('default/page', [
	   'title' => 'Redigera tagg',
	   'content' => $form->getHTML(), 
	], 'main');
     }
     else {
     $this->views->add('users/login-admin-message', [
    ], 'flash'); 
     }


}


/**
 * Delete content.
 *
 * @param integer $id of content to delete.
 *
 * @return void
 */
public function deleteAction($id = null)
{
    if(empty($id)) {
	$url = $this->url->create('issues/invalid-input').'?url='.$this->di->request->getCurrentUrl();
	$this->response->redirect($url);
	
	}
 
    $res = $this->content->delete($id);
 
    $url = $this->url->create('tag-basic/list');
    $this->response->redirect($url);
}




/**
 * Setup table.
 *
 * @return void
 */

public function setupContentAction()
{

    //$this->db->setVerbose();
 
    $this->db->dropTableIfExists('tagbasic')->execute();
 
    $this->db->createTable(
        'tagbasic',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'tagname' => ['varchar(20)', 'not null', 'unique'],
            'tagslug' => ['varchar(25)', 'not null', 'unique']
        ]
    )->execute();
    
       
    }
    
    /**
    * Populate the database with some test data.
    *
    * @return void
    */
    
    public function autoPopulateAction()
    {
    

    $this->db->insert(
        'tagbasic',
        ['tagname', 'tagslug']
    );
 

    $this->db->execute([
        'teckning',
        'teckning'
    ]);
 
    $this->db->execute([
        'måleri',
        'måleri'
     ]);
     
     $this->db->execute([
        'verktyg',
        'verktyg'
     ]);
     
      $this->db->execute([
        'foto',
        'foto'
    ]);
 
    $this->db->execute([
        'grafik',
        'grafik'
     ]);
     
     $this->db->execute([
        'ljud',
        'ljud'
     ]);
     
      $this->db->execute([
        'papper',
        'papper'
    ]);
 
    $this->db->execute([
        'pennor',
        'pennor'
     ]);
     
     $this->db->execute([
        'penslar',
        'penslar'
     ]);
     
     $this->db->execute([
        'akvarell',
        'akvarell'
     ]);
     
     $this->db->execute([
        'olja',
        'olja'
     ]);
     
      $this->db->execute([
        'akryl',
        'akryl'
    ]);
 
    $this->db->execute([
        'skulptur',
        'skulptur'
     ]);
     
     $this->db->execute([
        'digitalt',
        'digitalt'
     ]);
     
     $this->db->execute([
        'tusch',
        'tusch'
    ]);
 
    $this->db->execute([
        'skriva',
        'skriva'
     ]);
     
     $this->db->execute([
        'idéer',
        'ideer'
     ]);
     
     $this->db->execute([
        'kreativitet',
        'kreativitet'
     ]);
     
     $this->db->execute([
        'inspiration',
        'inspiration'
     ]);
     
      $this->db->execute([
        'material',
        'material'
    ]);
 
    $this->db->execute([
        'blyerts',
        'blyerts'
     ]);
     
     $this->db->execute([
        'pastell',
        'pastell'
     ]);
         
    
}

  public function setupPopulateAction() 
  {
  
    $this->setupContentAction();
    $this->autoPopulateAction();
    
  
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