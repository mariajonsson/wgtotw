<?php
namespace Anax\Users;

class UserLoginController implements \Anax\DI\IInjectionAware
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
    $this->users = new \Anax\Users\User();
    $this->users->setDI($this->di);

}
  
  
  public function ShowLoginAction() {
  
    $this->loggedin = $this->users->isLoggedIn();
  
    if(!$this->loggedin) {
    
     $form = new \Anax\HTMLForm\CFormLogin();
     $form->setDI($this->di);
     $form->check();
      $this->theme->setTitle("Logga in");

      $this->views->add('default/page', [
        'content' => $form->getHTML(),
        'title' => "Logga in",
      ], 'main');
    }
    if($this->loggedin){
    
    $this->theme->setTitle("Logga ut");

      $this->views->add('default/page', [
        'content' => '<a href="'.$this->di->get('url')->create('user-login/logout').'">Logga ut</a>',
        'title' => "Logga in",
      ], 'main');
    }
  
  }
  
  

  public function LoginAction($acronym, $password) {
  
  $verified = $this->users->verify($acronym, $password);
    
  if($verified) {
    
    $_SESSION['user']->acronym = $acronym;
    $_SESSION['user']->name = $verified->name;
    $_SESSION['user']->id = $verified->id;
    $this->loggedin = true;
    
  }
  
  }
  

  
  
  public function LogoutAction() {
    unset($_SESSION['user']);
    $this->loggedin = false;

    
  }


}


