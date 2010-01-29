<?php

class CreateUsers extends Ruckusing_BaseMigration {

	public function up() {
    $this->execute('CREATE TABLE users ('.
      'id INT UNSIGNED NOT NULL AUTO_INCREMENT, '.
      'name VARCHAR(20) NOT NULL, '.
      'password_hash CHAR(40), '. // allow null for future users of other auth systems
      'email VARCHAR(50) NOT NULL, '.
      'PRIMARY KEY (id)'.
    ')');
	}//up()

	public function down() {
    $this->drop_table('users');
	}//down()
}
?>
