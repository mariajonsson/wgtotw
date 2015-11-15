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
  
  
  public function ShowLoginAction($message=null) {
  
    $this->loggedin = $this->users->isLoggedIn();
    
    $message = $this->getMessage($message);
  
    if(!$this->loggedin) {
    
    
    
     $form = new \Anax\HTMLForm\CFormLogin();
     $form->setDI($this->di);
     $form->check();
      $this->theme->setTitle("Logga in");
      
      $this->views->add('wgtotw/plain', [
        'content' => $message,
      ], 'flash');

      $this->views->add('default/page', [
        'content' => $form->getHTML(),
        'title' => "Logga in",
      ], 'main');
    }
    if($this->loggedin){
    
    $this->theme->setTitle("Logga ut");
    
    $this->views->add('wgtotw/plain', [
        'content' => $message,
      ], 'flash');

      $this->views->add('default/page', [
        'content' => '<a href="'.$this->di->get('url')->create('user-login/logout').'">Logga ut</a>',
        'title' => "Logga ut",
      ], 'main');
    }
  
  }
  
  public function getMessage($message=null) {
  
  switch ($message) {
  
  case 'success':
  
  $message = "Du loggades in";
  
  break;
  
  case 'fail':
  
  $message = "Du loggades inte in";
  
  break;
  
  case 'out':
  
  $message = "Du loggades ut";
  
  break;
  
  default: 
  
  $message = null;
  
  }
  
  return $message;
  
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
    
    $url = $this->url->create('user-login/show-login/out');
    $this->response->redirect($url);

    
  }


}


