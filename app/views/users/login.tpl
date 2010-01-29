<!DOCTYPE html>
<html>
  <head>
    <title>OpenNeo - Login</title>
    <link type="text/css" rel="stylesheet" href="/assets/css/main.css" />
  </head>
  <body>
    <h1>OpenNeo ID</h1>
    {insert name=flashes}
    <p>
      Log in with your OpenNeo username and password below.
    </p>
    <form action="{path to=login}" method="POST">
{if isset($login_error)}
      <div class="form-errors">
        <h1>No luck!</h1>
{if $login_error == 'username'}
          We have no user by this name. Would you like to
          <a href="{path to=signup}">sign up</a> with it?
{else}
          That's not the right password for this user. Try again?
{/if}
      </div>
{/if}
      <ol class="fields">
        <li>
          <label for="name">Name</label>
          <input id="name" type="text" name="user[name]"
            value="{$username|escape}"
            {if empty($username) || (isset($login_error) && $login_error == 'username')}autofocus {/if}/>
        </li>
        <li>
          <label for="password">Password</label>
          <input id="password" type="password"
            name="user[password]"
            {if isset($login_error) && $login_error == 'password'}autofocus {/if}/>
        </li>
      </ol>
      <input type="submit" />
    </form>
    <a href="{path to=signup}">Sign up</a>
  </body>
</html>
