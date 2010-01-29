<?php
$map = PwnageCore_RouteManager::getInstance();

$map->connect('/', array(
  'controller' => 'users',
  'action' => 'welcome',
  'name' => 'root'
));

$map->connect('/login', array(
  'controller' => 'users',
  'action' => 'login',
  'name' => 'login'
));

$map->connect('/signup', array(
  'controller' => 'users',
  'action' => 'signup',
  'name' => 'signup'
));
?>
