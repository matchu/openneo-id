<?php
class Pwnage_User extends PwnageCore_DbObject {
  static $table = 'users';
  static $columns = array('name', 'password_hash', 'secret', 'email');
  static $accessible_columns = array('name', 'password', 'password_confirmation',
    'email');
  static $validations = array(
    'confirmed' => array('password'),
    'length_under' => array('name' => 20, 'email' => 50),
    'matches' => array('email' => '^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$'),
    'present' => array('name', 'password', 'password_confirmation', 'email'),
    'unique' => array('name', 'email'),
  );
  public $name;
  protected $password;
  protected $password_confirmation;
  public $email;
  
  public function __construct($data=array()) {
    if($data) {
      foreach(self::$accessible_columns as $accessible_column) {
        $this->$accessible_column = $data[$accessible_column];
      }
    }
  }
  
  protected function beforeSave() {
    if(isset($this->password)) {
      $secret_chars = range('A','z');
      $secret_chars_count = count($secret_chars);
      $this->secret = '';
      for($i=0;$i<32;$i++) {
        $this->secret .= $secret_chars[mt_rand(0, $secret_chars_count-1)];
      }
      $this->password_hash = $this->getHashForPassword($this->password);
    }
  }
  
  protected function getHashForPassword($password) {
    return hash_hmac('sha256', $password, $this->secret);
  }
  
  public function getRemoteAuthorizationData() {
    return array(
      'name' => $this->name
    );
  }
  
  public function isValid() {
    return parent::isValid(self::$validations, self::$table);
  }
  
  public function passwordMatches($password) {
    return $this->getHashForPassword($password) == $this->password_hash;
  }
  
  public function save() {
    return parent::save(self::$table, self::$columns);
  }
  
  static function findByLoginData($data) {
    $user = self::first(array(
      'select' => 'password_hash, secret, name', // name for sending remote auth
      'where' => array('name = ?', $data['name'])
    ));
    if(!$user) {
      throw new Pwnage_LoginUsernameNotFound;
    }
    if(!$user->passwordMatches($data['password'])) {
      throw new Pwnage_LoginPasswordIncorrect;
    }
    return $user;
  }
  
  static function first($options) {
    return parent::first($options, self::$table, __CLASS__);
  }
}

class Pwnage_LoginUsernameNotFound extends Exception {}
class Pwnage_LoginPasswordIncorrect extends Exception {}
?>
