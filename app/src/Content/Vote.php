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

}