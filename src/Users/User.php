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
  
//->andWhere('password = ' . $password)

  public function isLoggedIn() {

    // Check if user is loggedin.
    $loggedinacronym = $this->di->session->get('user')['acronym'] ? $this->di->session->get('user')['acronym'] : null;
    
    if($loggedinacronym) {
     
      return true;
      
    }
    else {
      return false;
      
    }
    }
 
}