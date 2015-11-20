<?php

namespace Meax\Content;
 
/**
 * Model for Content.
 *
 */
class Vote extends \Anax\MVC\CDatabaseModel
{



public function getRank($contentid, $type) {
    
    $matches = $this->db->select('*, coalesce(SUM(vote), 0) AS rank')
             ->from($this->getSource())
             ->where('contentid = ?')
             ->andWhere('contenttype = ?')
             ->executeFetchAll([$contentid, $type]);
             
    return $matches[0]->rank;

    }
    
public function getVote($userid, $contentid, $contenttype) {

//$this->db->setVerbose();
  $voted = $this->db->select()
	    ->from($this->getSource())
	    ->where('contentid = ?')
             ->andWhere('contenttype = ?')
             ->andWhere('userid = ?')
             ->executeFetchAll([$contentid, $contenttype, $userid]);
  if(!empty($voted)) {
  
      return $voted[0]->vote;
   
  }
  else return false;

}

public function getVoteId($userid, $contentid, $contenttype) {

//$this->db->setVerbose();
  $voted = $this->db->select()
	    ->from($this->getSource())
	    ->where('contentid = ?')
             ->andWhere('contenttype = ?')
             ->andWhere('userid = ?')
             ->executeFetchAll([$contentid, $contenttype, $userid]);
  if(!empty($voted)) {
  
      return $voted[0]->id;
   
  }
  else return null;

}

public function notAlreadyVotedUp($userid, $contentid, $contenttype) {


  $vote = $this->getVote($userid, $contentid, $contenttype);
  
  
  if ($vote == 1) {
    return false;
  }
  else {
  	return true;
  }
  

}

public function notAlreadyVotedDown($userid, $contentid, $contenttype) {

  $vote = $this->getVote($userid, $contentid, $contenttype);
  
  if ($vote == -1) {
    return false;
  }
  else {
  	return true;
  }
  

}

    public function getNumVotes($userid, $contenttype) {
    
    //$this->db->setVerbose();
    
    $user = $this->db->select('userid, coalesce(count(contenttype), 0) as '.$contenttype.'')
	    ->from($this->getSource())
	    ->where('userid = ?')
	    ->andWhere('contenttype = ?')
	    ->groupBy('userid')
	    ->executeFetchAll([$userid, $contenttype]);
    if(!empty($user)) {
    
    if(!empty($user[0]->$contenttype)) {
  
      return $user[0]->$contenttype;
      }
   
  }
  else return 0;
    
    }
    
    public function getNumVotesAll($contenttype, $lim=0) {
    
    //$this->db->setVerbose();
    
    $user = $this->db->select('userid, coalesce(count(contenttype), 0) as '.$contenttype.'')
	    ->from($this->getSource())
	    ->andWhere('contenttype = ?')
	    ->groupBy('userid')
	    ->limit('?')
	    ->executeFetchAll([$userid, $contenttype, $lim]);
    if(!empty($user)) {
    
     return $user;

   
  }
  else return null;
    
    }

}