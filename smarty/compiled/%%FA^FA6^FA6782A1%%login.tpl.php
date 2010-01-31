<?php /* Smarty version 2.6.22, created on 2010-01-30 13:44:23
         compiled from users/login.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('insert', 'flashes', 'users/login.tpl', 9, false),array('function', 'path', 'users/login.tpl', 13, false),array('modifier', 'escape', 'users/login.tpl', 29, false),)), $this); ?>
<!DOCTYPE html>
<html>
  <head>
    <title>OpenNeo - Login</title>
    <link type="text/css" rel="stylesheet" href="/assets/css/main.css" />
  </head>
  <body>
    <h1>OpenNeo ID</h1>
    <?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'flashes')), $this); ?>

    <p>
      Log in with your OpenNeo username and password below.
    </p>
    <form action="<?php echo smarty_function_path(array('to' => 'login'), $this);?>
" method="POST">
<?php if (isset ( $this->_tpl_vars['login_error'] )): ?>
      <div class="form-errors">
        <h1>No luck!</h1>
<?php if ($this->_tpl_vars['login_error'] == 'username'): ?>
          We have no user by this name. Would you like to
          <a href="<?php echo smarty_function_path(array('to' => 'signup'), $this);?>
">sign up</a> with it?
<?php else: ?>
          That's not the right password for this user. Try again?
<?php endif; ?>
      </div>
<?php endif; ?>
      <ol class="fields">
        <li>
          <label for="name">Name</label>
          <input id="name" type="text" name="user[name]"
            value="<?php echo ((is_array($_tmp=$this->_tpl_vars['username'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"
            <?php if (empty ( $this->_tpl_vars['username'] ) || ( isset ( $this->_tpl_vars['login_error'] ) && $this->_tpl_vars['login_error'] == 'username' )): ?>autofocus <?php endif; ?>/>
        </li>
        <li>
          <label for="password">Password</label>
          <input id="password" type="password"
            name="user[password]"
            <?php if (isset ( $this->_tpl_vars['login_error'] ) && $this->_tpl_vars['login_error'] == 'password'): ?>autofocus <?php endif; ?>/>
        </li>
      </ol>
      <input type="submit" />
    </form>
    <a href="<?php echo smarty_function_path(array('to' => 'signup'), $this);?>
">Sign up</a>
  </body>
</html>