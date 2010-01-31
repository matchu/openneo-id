<?php
class OpenneoAuthServer {
  const remoteBaseDomain = 'openneo.net';
  const remoteAuthorizePath = '/users/authorize';
  const sessionKey = 'openneo_auth_destination';
  const returnUrl = true;
  private $params;
  private $initial_request;
  static $required_configs = array('valid_apps', 'secret');
  static $required_session_params = array('app', 'path', 'session_id');
  static $required_user_attributes = array('id', 'name');
  
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
  
  protected function getParams() {
    if(!isset($this->params)) {
      $this->params = $this->getSessionData();
    }
    return $this->params;
  }
  
  protected function getSessionData() {
    return $_SESSION[self::sessionKey];
  }
  
  public function initSession() {
    $this->setSessionData($this->getParams());
  }
  
  public function redirect($return_url=false) {
    $session_data = $this->getSessionData();
    $path = $session_data['path'];
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
    foreach(self::$required_user_attributes as $required_user_attribute) {
      if(!isset($user_data[$required_user_attribute])) {
        throw new OpenneoAuth_MissingUserDataError(
          "\$$required_user_attribute is a required user attribute"
        );
      }
    }
    $session_data = $this->getSessionData();
    $url = $this->getRemoteHost().self::remoteAuthorizePath;
    $post_data = array(
      'session_id' => $session_data['session_id'],
      'source' => $this->config['short_name'],
      'user' => $user_data,
    );
    $post_data['signature'] =
      OpenneoAuthSignatory::sign($post_data, $this->config['secret']);
    $post_string = http_build_query($post_data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    $data = curl_exec($ch);
    $info = curl_getinfo($ch);
    if($info['http_code'] != 200) {
      throw new OpenneoAuth_RemoteAuthorizationError(
        "$url returned status ${info['http_code']}: '$data'"
      );
    }
  }
  
  public function sessionExists() {
    return isset($_SESSION[self::sessionKey]);
  }
  
  public function setParams($given_params) {
    $params = array();
    foreach(self::$required_session_params as $required_session_param) {
      if(!isset($given_params[$required_session_param])) return false;
      $params[$required_session_param] = $given_params[$required_session_param];
    }
    if(in_array($params['app'], $this->config['valid_apps'])) {
      $this->params = $params;
      return true;
    } else {
      return false;
    }
  }
  
  protected function setSessionData($data) {
    $_SESSION[self::sessionKey] = $data;
  }
}

class OpenneoAuth_MissingUserDataError extends Exception {}
class OpenneoAuth_MissingConfigError extends Exception {}
class OpenneoAuth_RemoteAuthorizationError extends Exception {}
?>
