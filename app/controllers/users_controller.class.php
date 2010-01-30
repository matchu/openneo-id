<?php
require_once PWNAGE_ROOT.'/pwnage/lib/spyc.php';

class Pwnage_UsersController extends PwnageCore_Controller {
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
    if($this->initSession()) {
      $this->redirectToRoute('login');
    }
  }
  
  private function authorizeAsUser($user) {
    $auth = $this->getOpenneoAuthServer();
    try {
      $auth->sendUserData($user->getRemoteAuthorizationData());
      $this->redirect($auth->redirect(OpenneoAuthServer::returnUrl));
    } catch(OpenneoAuthServer_RemoteAuthorizationError $e) {
      $this->setFlash('users/remote_authorization_error', 'error');
    }
  }
  
  protected function checkForSession() {
    $auth = $this->getOpenneoAuthServer();
    if(!$auth->sessionExists()) {
      if($this->initSession()) {
        $this->redirectToRoute('login');
      } else {
        $this->redirectToRoute('root');
      }
    }
  }
  
  private function getOpenneoAuthServer() {
    if(!isset($this->openneo_auth)) {
      $this->openneo_auth = new OpenneoAuthServer(
        Spyc::YAMLLoad(PWNAGE_ROOT.'/config/openneo_auth.yml')
      );
    }
    return $this->openneo_auth;
  }
  
  private function initSession() {
    return $this->getOpenneoAuthServer()->initSession($this->get);
  }
}
?>
