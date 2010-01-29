<?php

class AddSecretToUsers extends Ruckusing_BaseMigration {

	public function up() {
    $this->execute('ALTER TABLE users '.
      'ADD COLUMN secret CHAR(32) NOT NULL, '.
      'MODIFY COLUMN password_hash CHAR(64) NOT NULL'
    );
	}//up()

	public function down() {
    $this->remove_column('users', 'secret');
	}//down()
}
?>
