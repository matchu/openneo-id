<?php
class Pwnage_UsersController extends PwnageCore_Controller {
  static $valid_apps = array('impress'); // when more apps are needed, add to list
  
  protected function __construct() {
    $this->addBeforeFilter(array('login', 'signup'), 'checkForSession');
    parent::__construct();
  }
  
  public function signup() {
    if(isset($this->post['user'])) {
      $user = new Pwnage_User($this->post['user']);
      if($user->isValid()) {
        $user->save();
        $this->authorizeAsUser($user);
      } else {
        $this->set('user', $user);
        $this->set('errors', $user->getErrors());
      }
    }
  }
  
  public function login() {
    if(isset($this->post['user'])) {
      try {
        $user = Pwnage_User::findByLoginData($this->post['user']);
        $this->authorizeAsUser($user);
      } catch(Pwnage_LoginUsernameNotFound $e) {
        $this->set('login_error', 'username');
      } catch(Pwnage_LoginPasswordIncorrect $e) {
        $this->set('login_error', 'password');
      }
      $this->set('username', $this->post['user']['name']);
    }
  }
  
  public function welcome() {
    if(isset($this->get['app']) && isset($this->get['destination'])) {
      $app = $this->get['app'];
      $destination = $this->get['destination'];
      if(in_array($app, self::$valid_apps)) {
        $_SESSION['app'] = $app;
        $_SESSION['destination'] = $this->get['destination'];
        $this->redirectToRoute('login');
      }
    }
  }
  
  private function authorizeAsUser($user) {
    //TODO: add callback to relevant app
    $path = $_SESSION['destination'];
    if(substr($path, 0, 1) != '/') $path = '/'.$path;
    $location = 'http://'.$_SESSION['app'].'.openneo.net'.$path;
    session_destroy();
    header("Location: $location");
  }
  
  protected function checkForSession() {
    if(!isset($_SESSION['app']) || !isset($_SESSION['destination'])) {
      $this->redirectToRoute('root');
    }
  }
}
?>
