<?php

namespace Anax\Users;
 
/**
 * Model for Users.
 *
 */
class User extends \Anax\MVC\CDatabaseModel
{

  public function verify($acronym, $password) {
  
  
  $verified = $this->query()
      ->where('acronym = ?')
        ->execute([$acronym]);
  
  return $verified;
  }
  
  public function verifyAcronym($acronym) {
  
  
  $verified = $this->query()
      ->where('acronym = ?')
        ->execute([$acronym]);
  
  return $verified;
  }
  
//->andWhere('password = ' . $password)

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
 
}