<?php
namespace Meax\Content;
 
/**
 * A controller for content and admin related events.
 *
 */
class ContentTagController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    
 
/**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->content = new \Meax\Content\ContentTag();
    $this->content->setDI($this->di);
}

/**
 * List all content.
 *
 * @return void
 */
public function listAction()
{
 
    $all = $this->content->findAll();
    
    $this->theme->setTitle("Content");
    $this->views->add('tags/list-all', [
        'content' => $all,
        'title' => "All content",
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

    $this->di->theme->setTitle("Add content");
    $this->di->views->add('tags/add', [
        'title' => "Add content",
             
        ], 'main');

}


public function postFormAction() 
{
    
    $now = date('Y-m-d H:i:s');
    $published = null;
    
    if (!empty($_POST['submit-add'])) {
    $published = !empty($_POST['published'])?$now:null;
    $saved = $this->content->save(array('title' => $_POST['title'], 'url' => $_POST['url'], 'slug' => $_POST['slug'], 'acronym' => $_POST['acronym'], 'created' => $now, 'data' => $_POST['data'], 'filter' => $_POST['filter'], 'type' => $_POST['type'], 'published' => $published));
    }
    else if (!empty($_POST['submit-edit'])) {
        if ($_POST['publisheddate'] == null && !empty($_POST['published'])) {
	    $published = $now;
        }
        else if ($_POST['publisheddate'] != null && empty($_POST['published'])) {
	    $published = null;
        }
        else $published = $_POST['publisheddate'];
   
    $saved = $this->content->save(array('id' => $_POST['id'], 'title' => $_POST['title'], 'url' => $_POST['url'], 'slug' => $_POST['slug'], 'acronym' => $_POST['acronym'], 'created' => $now, 'data' => $_POST['data'], 'filter' => $_POST['filter'], 'type' => $_POST['type'], 'published' => $published));
    }
    
    if ($saved) {
      $this->dispatcher->forward([
        'controller' => 'tag-basic',
        'action'     => 'list',
        //'params'     => [],
      ]);
    }
    else {
    $this->di->theme->setTitle("Error");
    $this->di->views->add('default/page', [
        'title' => "Something went wrong",
        'content' => "The data wasn't saved"     
        ], 'main');
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

    $this->db->setVerbose();
 
    $this->db->dropTableIfExists('contenttag')->execute();
 
    $this->db->createTable(
        'contenttag',
        [
            'tagid' => ['integer', 'not null'],
            'contentid' => ['integer', 'not null']
        ]
    )->execute();
    
       
    }
    


}