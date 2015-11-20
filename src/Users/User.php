<?php

namespace Anax\Users;
 
/**
 * Model for Users.
 *
 */
class User extends \Anax\MVC\CDatabaseModel
{

  public function verify($acronym, $password) {
  
  $verified = null;
  $user = $this->verifyAcronym($acronym);
  
  if ($user != null) {
  
    $verified = $this->verifyPassword($acronym, $password);  
    if ($verified) {
  
      return $user;
  
    }
  }	
 
 else return null;
  
  }
  
  public function verifyAcronym($acronym) {
  
  
  $verified = $this->query()
      ->where('acronym = ?')
        ->execute([$acronym]);
  
  return $verified;
  }
  
  public function verifyPassword($acronym, $password) {
  
 
  
  $user = $this->query()
      ->where('acronym = ?')
        ->execute([$acronym]);
        
  
  $password2 = $user[0]->password;      
        
  $verify = password_verify ($password, $password2);    
  return $verify;
  }
  


  public function isLoggedIn() {

    // Check if user is loggedin.
    $loggedinuser = $this->di->session->get('user') ? $this->di->session->get('user') : null;
    
    if($loggedinuser) {
     
      return true;
      
    }
    else {
      return false;
      
    }
    }
    
    public function getLoggedInUser() {
    
    $loggedinacronym = null;
    
    if ($this->isLoggedIn()) {
      $loggedinacronym = $this->di->session->get('user')['acronym'];
      
    }
    
    return $loggedinacronym;
    
    }
    
    public function getAcronym($id) {
      $acronym = $this->query('acronym')
	->where('id = ?')
        ->execute([$id]);
    
      return $acronym[0]->acronym;
    
    }
 
    public function getEmail($id) {
      $email = $this->query('email')
	->where('id = ?')
        ->execute([$id]);
    
      return $email[0]->email;
    
    }
    
    public function getGravatarForAcronym($acronym) {
     
     if($this->verifyAcronym($acronym))
      {
     $gravatar = $this->query('gravatar')
	->where('acronym = ?')
        ->execute([$acronym]);
    
      return $gravatar[0]->gravatar;
       }
      
      else {
      	return null;
      	
      }
    
    }
    
    public function getIdForAcronym($acronym) {
    
      if($this->verifyAcronym($acronym))
      {
      $id = $this->query('id')
	->where('acronym = ?')
        ->execute([$acronym]);
    
      return $id[0]->id;
      }
      
      else {
      	return null;
      	
      }
      
    
    }
    
    public function findMostAnswers() {
    
    $user = $this->db->select('t0.*, t1.name as name1, COUNT(t0.acronym) AS total')
             ->from($this->getSource(). ' AS t0')
             ->join('answer AS t1', 't0.acronym = t1.name')
             ->groupBy('t0.acronym')
             ->orderBy('total DESC')
             ->limit(3)
             ->executeFetchAll();
             
    return $user;

    }
    
     public function findNumAnswers($acronym) {
    
    $user = $this->db->select('t0.*, t1.name as name1, coalesce(COUNT(t0.acronym), 0) AS total')
             ->from($this->getSource(). ' AS t0')
             ->join('answer AS t1', 't0.acronym = t1.name')
             ->where('t0.acronym = ?')
             ->executeFetchAll([$acronym]);
             
    return $user;

    }
    
    public function findMostIssues() {
    
    $user = $this->db->select('t0.*, t1.acronym as name1, COUNT(t0.acronym) AS total')
             ->from($this->getSource(). ' AS t0')
             ->join('issues AS t1', 't0.acronym = t1.acronym')
             ->groupBy('t0.acronym')
             ->orderBy('total DESC')
             ->limit(3)
             ->executeFetchAll();
             
    return $user;

    }
    
      public function findNumIssues($acronym) {
    
    $user = $this->db->select('t0.*, t1.acronym as name1, coalesce(COUNT(t0.acronym), 0) AS total')
             ->from($this->getSource(). ' AS t0')
             ->join('issues AS t1', 't0.acronym = t1.acronym')
             ->where('t0.acronym = ?')
             ->executeFetchAll([$acronym]);
             
    return $user;

    }
    public function findMostComments() {
    
    $user = $this->db->select('t0.*, t1.name as name1, COUNT(t0.acronym) AS total')
             ->from($this->getSource(). ' AS t0')
             ->join('comments AS t1', 't0.acronym = t1.name')
             ->groupBy('t0.acronym')
             ->orderBy('total DESC')
             ->limit(3)
             ->executeFetchAll();
             
    return $user;

    }
    
    public function findNumComments($acronym) {
    
    $user = $this->db->select('t0.*, t1.name as name1, coalesce(COUNT(t0.acronym), 0) AS total')
             ->from($this->getSource(). ' AS t0')
             ->join('comments AS t1', 't0.acronym = t1.name')
             ->where('t0.acronym = ?')
             ->executeFetchAll([$acronym]);
             
    return $user;

    }
    
    public function findMostActive() {
    
    /*$sql = "SELECT user.*,  coalesce(count1, 0) as totansw, coalesce(count2, 0) as totiss, coalesce(count3, 0) as totcom, sum(coalesce(count1, 0)+coalesce(count2, 0)+coalesce(count3, 0)) as total
FROM wgtotw_user user
left join 
	(SELECT wgtotw_answer.name, count(wgtotw_answer.name) AS count1 
 	from wgtotw_answer group by wgtotw_answer.name)
	answercount on user.acronym = answercount.name 
left join 
	(select wgtotw_issues.acronym, count(wgtotw_issues.acronym) AS count2 
     from wgtotw_issues group by wgtotw_issues.acronym)
	issuecount on user.acronym =issuecount.acronym
left join 
	(select wgtotw_comments.name, count(wgtotw_comments.name) AS count3 
     from wgtotw_comments group by wgtotw_comments.name)
	commentcount on user.acronym =commentcount.name
group by user.acronym
order by total DESC
limit 3";*/

	$sql = "SELECT user.*,  coalesce(count1, 0) as totansw, coalesce(count2, 0) as totiss, coalesce(count3, 0) as totcom, coalesce(count4, 0) as totvote, sum(coalesce(count1, 0)+coalesce(count2, 0)+coalesce(count3, 0)+coalesce(count4, 0)) as total
FROM wgtotw_user user
left join 
	(SELECT wgtotw_answer.name, count(wgtotw_answer.name) AS count1 
 	from wgtotw_answer group by wgtotw_answer.name)
	answercount on user.acronym = answercount.name 
left join 
	(select wgtotw_issues.acronym, count(wgtotw_issues.acronym) AS count2 
     from wgtotw_issues group by wgtotw_issues.acronym)
	issuecount on user.acronym =issuecount.acronym
left join 
	(select wgtotw_comments.name, count(wgtotw_comments.name) AS count3 
     from wgtotw_comments group by wgtotw_comments.name)
	commentcount on user.acronym =commentcount.name
left join 
	(select wgtotw_vote.userid, count(wgtotw_vote.userid) AS count4 
     from wgtotw_vote group by wgtotw_vote.userid)
	votecount on user.id =votecount.userid
group by user.acronym
order by total DESC
limit 3";

$user = $this->db->executeFetchAll($sql);

return $user;
    
    }
    
    public function getUserRank($userid, $contenttype, $table, $acronymcolname) {
    //$this->db->setVerbose();
    $user = $this->db->select('wgtotw_vote.*, type.id as tid, user.id as uid, 
user.acronym as uname, type.'.$acronymcolname.' as tname, coalesce(sum(coalesce(vote, 0)), 0) as total')
    		->from('user as user, wgtotw_'.$table.' as type, wgtotw_vote')         
             ->where('contentid = type.id')
             ->andWhere('contenttype = ?')
             ->andWhere('user.acronym = type.'.$acronymcolname)
             ->andWhere('user.id = ?')
             ->groupBy('user.id')
             ->executeFetchAll([$contenttype, $userid]);
             
             
         if (!empty($user)) {
         	 
         	 return $user[0]->total;
         }
         else {
         	 return '0';
         }

    }
    

 
}