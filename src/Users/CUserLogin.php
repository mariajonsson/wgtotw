<?php
namespace Anax\Users;

class UserLoginController extends implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
  private $acronym;
  private $name;
  private $id;
  private $userrole;
  private $password;
  private $authenticated;
  
  public function __construct($options) {
  	if (isset($_SESSION['user'])) {
  		parent::__construct($options);
  		$this->acronym = $_SESSION['user']->acronym;
  		$this->name = $_SESSION['user']->name;
  		$this->id = $_SESSION['user']->id;
  		$this->userrole = $_SESSION['user']->userrole;
  		$this->authenticated = $this->IsUserAuthenticated() ? true : false;
 		
  	}  
  	if (!isset($_SESSION['user'])) {
     parent::__construct($options);
     $this->authenticated = false;
    }
  }
  
  public function ShowLogin($type="status") {
  
  	  	if(!$this->authenticated){
  	  	  switch ($type) {
  	  	  	  
  	  	  case "login":
  	  	  $html = "<form method='post' action='login.php?'>";
  	  	  $html .= "<p><label>Användare</label><br>";
  	  	  $html .= "<input type='text' value='' name='name' /></p>";
  	  	  $html .= "<p><label>Lösenord</label><br>";
  	  	  $html .= "<input type='password' value='' name='acronym' /></p>";
  	  	  $html .= "<p><input type='submit' value='Login' name='login' /></p>";
  	  	  $html .= "</form>";
  	  	  $html .= "<p></p>";
  	  	  break;
  	  	  
  	  case "logout":
  	  	  $html = "<p>Du är INTE inloggad.</p> <p><a href='login.php'>Logga in</a></p>";
  	  	  break;
  	  	  
  	  	  case "status":
  	  	  $html = "<p>Du är INTE inloggad.</p>";
  	  	  break;
  	  	  
  	  	
  	  	  }
  	  	  
  	  }
  	  if($this->authenticated){
  	  	  
  	  	  switch ($type) {
  	  	  case "login":
			  $html = "<p>Du är inloggad som: " .$this->GetAcronym(). " (" .$this->GetName(). "). </p>";
			  $html .= "<p><a href='logout.php'>Gå till logout-sidan</a></p>";
			  break;
  	  	  
		  case "logout":
  	  	   $html = "<p>Du är inloggad som: " .$this->GetAcronym(). " (" .$this->GetName(). "). </p>";
  	  	  $html .= "<form method='post' action='logout.php'>";
  	  	  //$html .= "<p><label>Logga ut</label></p>";
  	  	  $html .= "<p><input type='Submit' name='logout' value='Logout' /></p>";
  	  	  $html .= "</form>";
  	  	  break;
  	  	  
  	  	case "status":
  	  	  $html = "<p>Du är inloggad som: " .$this->GetAcronym(). " (" .$this->GetName(). "). </p>";
  	  	  break;
  	  	   }
  	  }
  	  
  	  return $html;
  
  }
  
  

  public function Login($name, $acronym) {
  
  $sql = "SELECT acronym, name, id,userrole FROM USER WHERE acronym = ? AND password = md5(concat(?, salt));";
  $params = (array($acronym, $acronym));
  $res = parent::ExecuteSelectQueryAndFetchAll($sql, $params);
  //dump($res);
  
  if(isset($res[0])) {
    
    $this->acronym = $acronym;
    $this->name = $name;
    $this->id = $res[0]->id;
    $this->userrole = $res[0]->userrole;
    $this->authenticated = $this->IsUserAuthenticated() ? true : false;
    $_SESSION['user'] = $res[0];
    
    
  }
  header('Location: login.php');
  }
  
  
  public function Logout() {
    unset($_SESSION['user']);
    header('Location: logout.php');
    
  }

  public function IsUserAuthenticated() {

    // Check if user is authenticated.
    $acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
    
    if($acronym) {
      $this->acronym = $acronym;
      $this->name = $_SESSION['user']->name;
      
      return true;
      
    }
    else {
      return false;
      
    }


  }
  
  public function GetAcronym() {
    return $this->acronym;
  
  }

  public function GetName() {
    return $this->name;
  
  }
}


