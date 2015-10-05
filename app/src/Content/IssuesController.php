<?php
namespace Meax\Content;
 
/**
 * A controller for content and admin related events.
 *
 */
class IssuesController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    
/**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->content = new \Meax\Content\Issues();
    $this->content->setDI($this->di);
    
    $this->contenttags = new \Meax\Content\ContentTag();
    $this->contenttags->setDI($this->di);
    
    $this->tags = new \Meax\Content\TagBasic();
    $this->tags->setDI($this->di);
    
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
    $this->views->add('contenttags/list-all', [
        'content' => $all,
        'title' => "All content",
    ], 'main');

}


/**
 * List all content.
 *
 * @return void
 */
public function listByTagAction($tagid)
{
 
    $all = $this->contenttags->findPostsByTag($tagid, 'tagbasic', 'issues');
    
    $this->theme->setTitle("Content");
    $this->views->add('content/list-all', [
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
    $tags = $this->contenttags->findTagsByPost($id, 'tagbasic', 'issues');
 
    $this->theme->setTitle("Content");
    $this->views->add('contenttags/view', [
        'controller' => 'issues',
        'post' => $post,
        'tags' => $tags
    ], 'main');
    
    $this->di->dispatcher->forward([
        'controller' => 'comments',
        'action'     => 'view',
        'params'     => [$id, 'show-form','comments'],
    ]);

}

/**
 * Add new content.
 * Shows a view with a add content form 
 *
 * @return void
 */
public function addAction()
{

    $this->di->theme->setTitle("Add content");
    $this->di->views->add('content/add', [
        'title' => "Add content",
             
        ], 'main');

}


/**
 * 
 * Checks if a form has been posted to add or edit content
 * 
 */

public function postFormAction() 
{
    
    $now = date('Y-m-d H:i:s');
    $saved = false;
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
        else 
        {
        $published = $_POST['publisheddate'];
        }
        
    $saved = $this->content->save(array('id' => $_POST['id'], 'title' => $_POST['title'], 'url' => $_POST['url'], 'slug' => $_POST['slug'], 'acronym' => $_POST['acronym'], 'created' => $now, 'data' => $_POST['data'], 'filter' => $_POST['filter'], 'type' => $_POST['type'], 'published' => $published));
    }
    
    if ($saved) {
      $this->dispatcher->forward([
        'controller' => 'issues',
        'action'     => 'list',

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
    $this->di->views->add('content/edit', [
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
 
    $this->content->delete($id);
 
    $url = $this->url->create('issues/list');
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
 
    $url = $this->url->create('issues/id/' . $id);
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
 
    $url = $this->url->create('issues/id/' . $id);
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
    $this->views->add('content/list-all', [
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
    $this->views->add('content/list-all', [
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
    $this->views->add('content/list-deleted', [
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
 
    $this->db->dropTableIfExists('issues')->execute();
 
    $this->db->createTable(
        'issues',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'title' => ['varchar(100)', 'not null'],
            'slug' => ['varchar(100)', 'unique'],
            'data' => ['text'],
            'acronym' => ['varchar(20)'],
            'created' => ['datetime'],
            'updated' => ['datetime'],
            'deleted' => ['datetime'],
            'published' => ['datetime'],
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
        'issues',
        ['title', 'slug', 'data', 'acronym', 'created', 'published']
    );
 
    $now = date('Y-m-d H:i:s');
 
    $this->db->execute([
        'Vilket akvarellpapper är bäst?',
        'vilket-akvarellpapper-ar-bast',
        'Jag ska göra en målning i akvarell och undrar vilket papper som är bäst.',
        'user',
        $now,
        $now
    ]);
 
    $this->db->execute([
        'Hur spänna duk på ram',
        'hur-spanna-duk-pa-ram',
        'Jag har tänkt att göra målardukar själv istället för att köpa färdiga. Hur gör jag det?',
        'user',
        $now,
        $now
     ]);
     
     $this->db->execute([
        'Saknar inspiration',
        'saknar-inspiration',
        'Hur gör man för att få inspiration? Jag känner mig kreativ men har inga idéer.',
        'user',
        $now,
        $now
     ]);
     
         
    
}

/**
 * 
 * Performs both setupAction and autoPopulateAction
 * 
 */

  public function setupPopulateAction() 
  {
  
    $this->setupContentAction();
    $this->autoPopulateAction();
    
    $this->dispatcher->forward([
        'controller' => 'issues',
        'action'     => 'list',

    ]);
    
  
  }

}
