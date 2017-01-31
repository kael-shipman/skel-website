<?php

$loader = require_once '../vendor/autoload.php';
$loader->add('Ks\\', __DIR__.'/../src');

$config = new \Ks\Config(__DIR__.'/../config');

$app = new \Ks\App($config, new \Ks\Db($config));
$app
  ->setCms(new \Ks\Cms($config))
  ->setRouter(new \Skel\Router())
  ->registerListener('Error', $app, 'prepareUiForError')
  //->registerListener('ComponentCreated', $app, 'debugComponent')
  ->getRouter()
    ->addRoute(new \Skel\Route('/{section}/*', $app, 'getPage', 'GET', 'page'))
    ->addRoute(new \Skel\Route('/', $app, 'getPage', 'GET', 'home'))
;

$response = $app->getResponse(\Skel\Request::createFromGlobals());
$response->send();

?>
