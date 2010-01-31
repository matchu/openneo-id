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
    <p>
      Remember - <strong>do not use your Neopets password here!</strong>
      We don't expect any security issues, but if anything happens, we want
      there to be absolutely minimal risk.
    </p>
    <form action="{path to=signup}" method="POST">
{if isset($user) && !$user->isValid()}
      <div class="form-errors">
        <h1>Whoops!</h1>
        <ul>
{section name=error loop=$errors}
          <li>
            {$errors[error]->getMessage()}
          </li>
{/section}
        </ul>
      </div>
{/if}
      <ol class="fields">
        <li>
          <label for="name">Name</label>
          <input id="name" type="text" name="user[name]" maxlength="20"
            value="{$user->name|escape}" />
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
            maxlength="50" value="{$user->email|escape}"/>
        </li>
      </ol>
      <input type="submit" />
    </form>
    <a href="{path to=login}">Log in</a>
  </body>
</html>
