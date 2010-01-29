<?php /* Smarty version 2.6.22, created on 2010-01-24 22:58:50
         compiled from users/create.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'path', 'users/create.tpl', 13, false),array('modifier', 'escape', 'users/create.tpl', 30, false),)), $this); ?>
<!DOCTYPE html>
<html>
  <head>
    <title>OpenNeo - Sign up</title>
    <link type="text/css" rel="stylesheet" href="/assets/css/main.css" />
  </head>
  <body>
    <h1>Sign up for OpenNeo ID</h1>
    <p>
      Just tell us your preferred login details, and your e-mail address.
      That's all we need.
    </p>
    <form action="<?php echo smarty_function_path(array('to' => 'signup'), $this);?>
" method="POST">
<?php if (isset ( $this->_tpl_vars['user'] ) && ! $this->_tpl_vars['user']->isValid()): ?>
      <div class="form-errors">
        <h1>Whoops!</h1>
        <ul>
<?php unset($this->_sections['error']);
$this->_sections['error']['name'] = 'error';
$this->_sections['error']['loop'] = is_array($_loop=$this->_tpl_vars['errors']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['error']['show'] = true;
$this->_sections['error']['max'] = $this->_sections['error']['loop'];
$this->_sections['error']['step'] = 1;
$this->_sections['error']['start'] = $this->_sections['error']['step'] > 0 ? 0 : $this->_sections['error']['loop']-1;
if ($this->_sections['error']['show']) {
    $this->_sections['error']['total'] = $this->_sections['error']['loop'];
    if ($this->_sections['error']['total'] == 0)
        $this->_sections['error']['show'] = false;
} else
    $this->_sections['error']['total'] = 0;
if ($this->_sections['error']['show']):

            for ($this->_sections['error']['index'] = $this->_sections['error']['start'], $this->_sections['error']['iteration'] = 1;
                 $this->_sections['error']['iteration'] <= $this->_sections['error']['total'];
                 $this->_sections['error']['index'] += $this->_sections['error']['step'], $this->_sections['error']['iteration']++):
$this->_sections['error']['rownum'] = $this->_sections['error']['iteration'];
$this->_sections['error']['index_prev'] = $this->_sections['error']['index'] - $this->_sections['error']['step'];
$this->_sections['error']['index_next'] = $this->_sections['error']['index'] + $this->_sections['error']['step'];
$this->_sections['error']['first']      = ($this->_sections['error']['iteration'] == 1);
$this->_sections['error']['last']       = ($this->_sections['error']['iteration'] == $this->_sections['error']['total']);
?>
          <li>
            <?php echo $this->_tpl_vars['errors'][$this->_sections['error']['index']]->getMessage(); ?>

          </li>
<?php endfor; endif; ?>
        </ul>
      </div>
<?php endif; ?>
      <ol class="fields">
        <li>
          <label for="name">Name</label>
          <input id="name" type="text" name="user[name]" maxlength="20"
            value="<?php echo ((is_array($_tmp=$this->_tpl_vars['user']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
        </li>
        <li>
          <label for="password">Password</label>
          <input id="password" type="password" name="user[password]" />
        </li>
        <li>
          <label for="password_confirmation">Confirm password</label>
          <input id="password_confirmation" type="password"
            name="user[password_confirmation]" />
        </li>
        <li>
          <label for="email">E-mail address</label>
          <input id="email" type="email" name="user[email]"
            maxlength="50" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['user']->email)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/>
        </li>
      </ol>
      <input type="submit" />
    </form>
    <a href="<?php echo smarty_function_path(array('to' => 'login'), $this);?>
">Log in</a>
  </body>
</html>