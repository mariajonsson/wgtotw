<?php
namespace Meax\Content;

class VoteController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

  private $loggedin;

  
  /**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->vote = new \Meax\Content\Vote();
    $this->vote->setDI($this->di);

}
  
  
  public function voteAction($userid, $contentid, $contenttype, $vote, $pagekey) 
  {
  
  $rank = ($vote == 'up') ? 1 : null;
  $rank = ($vote == 'down') ? -1 : $rank;
  
  //check if vote exists
  //  $this->vote->voteExists($userid, $contentid, $contenttype, $vote);
    
    $this->vote->save(['userid' => $userid, 'contentid' => $contentid, 'contenttype' => $contenttype, 'vote' => $rank]);
    
    $redirect = $this->url->create("issues/id").'/'.$pagekey;
        $this->response->redirect($redirect);
    
  }


  
  public function setupVoteAction() 
    {
      //$this->di->db->setVerbose();
 
      $this->di->db->dropTableIfExists('vote')->execute();
  
      $this->di->db->createTable(
	  'vote',
	  [
	      'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
	      'userid' => ['integer', 'not null'],
	      'contentid'    => ['integer', 'not null'],
	      'contenttype' => ['varchar(80)', 'not null'],
	      'vote' => ['integer']
	      	      
	  ]
      )->execute();
      
      $this->di->db->insert(
	  'vote',
	  ['userid', 'contentid', 'contenttype', 'vote']
      );
  
      $this->di->db->execute([
	  '1',
	  '1',
	  'answer',
	  '1'
      ]);
      
	  $this->di->db->execute([
	  '2',
	  '1',
	  'answer',
	  '1'
      ]);
      
	  
    
    }

}


