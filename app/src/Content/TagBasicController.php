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
    
    $this->theme->setTitle("Taggar");
    
     if ($this->user->isLoggedIn()) {

     $this->views->add('tags/new-tag', [
    ], 'flash');
    }
    
    $this->views->add('tags/list-all', [
        'content' => $all,
        'title' => "Taggar",
    ], 'main');

}

/*
public function listMostUsedAction($val, $num)
{
  $all = $this->content->findMostUsed($val, $num);
    echo var_dump($all);
    $this->views->add('tags/list-most-used', [
        'content' => $all,
        'subtitle' => "Populäraste taggarna",

    ], 'sidebar');

}*/

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

    if (!isset($id)) {
        die("Missing id");
    }
    
    $content = $this->content->find($id);
    $title = $content->getProperties()['title'];
    $url = $content->getProperties()['url'];
    $slug = $content->getProperties()['slug'];
    $data = $content->getProperties()['data'];
    $acronym = $content->getProperties()['acronym'];
    $filter = $content->getProperties()['filter'];
    $type = $content->getProperties()['type'];
    $deleted = $content->getProperties()['deleted'];
    $published = $content->getProperties()['published'];
    
    $this->di->theme->setTitle("Edit content");
    $this->di->views->add('tags/edit', [
        'header' => "Edit content",
        'title' => $title,
        'url' => $url,
        'slug' => $slug,
        'data' => $data,
        'acronym' => $acronym,
        'filter' => $filter,
        'type' => $type,
        'deleted' => $deleted,
        'published' => $published,
        'id' => $id,
        
        ]);

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
    if (!isset($id)) {
        die("Missing id");
    }
 
    $res = $this->content->delete($id);
 
    $url = $this->url->create('tag-basic/list');
    $this->response->redirect($url);
}


/**
 * Undo soft delete.
 *
 * @param integer $id of content to undo delete.
 *
 * @return void
 */
 

public function undoDeleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
 
    $content = $this->content->find($id);
    
    $content->deleted = null;
    $content->save();
 
    $url = $this->url->create('tag-basic/id/' . $id);
    $this->response->redirect($url);
}


/**
 * Delete (soft) content.
 *
 * @param integer $id of content to delete.
 *
 * @return void
 */
public function softDeleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
 
    $now = gmdate('Y-m-d H:i:s');
 
    $content = $this->content->find($id);
 
    $content->deleted = $now;
    $content->save();
 
    $url = $this->url->create('tag-basic/id/' . $id);
    $this->response->redirect($url);
}

/**
 * List all published and not deleted content.
 *
 * @return void
 */
public function publishedAction()
{
    $all = $this->content->query()
        ->where('published IS NOT NULL')
        ->andWhere('deleted is NULL')
        ->execute();
 
    $this->theme->setTitle("Published content");
    $this->views->add('tags/list-all', [
        'content' => $all,
        'title' => "Published content",
    ], 'main');

}

/**
 * List all unpublished and not deleted content.
 *
 * @return void
 */
public function unpublishedAction()
{
    $all = $this->content->query()
        ->where('published IS NULL')
        ->andWhere('deleted is NULL')
        ->execute();
 
    $this->theme->setTitle("Unpublished content");
    $this->views->add('tags/list-all', [
        'content' => $all,
        'title' => "Unpublished content",
    ], 'main');

}

/**
 * List all soft-deleted content.
 *
 * @return void
 */

public function discardedAction()
{
    $all = $this->content->query()
        ->where('deleted is NOT NULL')
        ->execute();
 
    $this->theme->setTitle("Trash");
    $this->views->add('tags/list-deleted', [
        'users' => $all,
        'title' => "Trash",
    ], 'main');

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