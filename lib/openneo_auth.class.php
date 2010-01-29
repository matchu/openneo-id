<?php
class OpenneoAuth {
  const remoteBaseDomain = 'openneo.net';
  const remoteAuthorizePath = '/users/authorize';
  const sessionKey = 'openneo_auth_destination';
  const returnUrl = true;
  private $config;
  static $required_configs = array('valid_apps', 'secret');
  static $required_session_params = array('app', 'path', 'session_id');
  
  public function __construct($config) {
    foreach(self::$required_configs as $required_config) {
      if(!isset($config[$required_config])) {
        throw new OpenneoAuth_MissingConfigException(
          "Required OpenneoAuth config $required_config missing"
        );
      }
    }
    $this->config = $config;
  }
  
  protected function getRemoteHost() {
    $session_data = $this->getSessionData();
    return 'http://'.$session_data['app'].'.'.self::remoteBaseDomain;
  }
  
  protected function getSessionData() {
    return $_SESSION[self::sessionKey];
  }
  
  public function initSession($given_params) {
    $params = array();
    foreach(self::$required_session_params as $required_session_param) {
      if(!isset($given_params[$required_session_param])) return false;
      $params[$required_session_param] = $given_params[$required_session_param];
    }
    if(in_array($params['app'], $this->config['valid_apps'])) {
      $this->setSessionData($params);
      return true;
    } else {
      return false;
    }
  }
  
  public function redirect($return_url=false) {
    $session_data = $this->getSessionData();
    $path = $destination['path'];
    if(substr($path, 0, 1) != '/') $path = '/'.$path;
    $location = $this->getRemoteHost().$path;
    session_destroy();
    if($return_url) {
      return $location;
    } else {
      header("Location: $location");
      die();
    }
  }
  
  public function sendUserData($user_data) {
    $session_data = $this->getSessionData();
    $url = $this->getRemoteHost().self::remoteAuthorizePath;
    $post_string = http_build_query(array(
      'session_id' => $session_data['session_id'],
      'user' => $user_data
    ));
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    $data = curl_exec($ch);
    $info = curl_getinfo($ch);
    if($info['http_code'] != 200) {
      throw new OpenneoAuth_RemoteAuthorizationError(
        "$url returned status ${info['http_code']}"
      );
    }
  }
  
  public function sessionExists() {
    return isset($_SESSION[self::sessionKey]);
  }
  
  protected function setSessionData($data) {
    $_SESSION[self::sessionKey] = $data;
  }
}

class OpenneoAuth_MissingConfigError extends Exception {}
class OpenneoAuth_RemoteAuthorizationError extends Exception {}
?>
