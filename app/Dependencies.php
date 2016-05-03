<?php

$injector = new \Auryn\Injector;

$injector->alias('Http\Request', 'Http\HttpRequest');
$injector->share('Http\HttpRequest');
$injector->define('Http\HttpRequest', [
  ':get'      => $_GET,
  ':post'     => $_POST,
  ':cookies'  => $_COOKIE,
  ':files'    => $_FILES,
  ':server'   => $_SERVER,
]);

$injector->alias('Http\Response', 'Http\HttpResponse');
$injector->share('Http\HttpResponse');

$injector->define('Crawler\Parser\FilesParser', [
  ':fileFolder' => __DIR__ . '/../checkfiles',
]);

$injector->alias('Crawler\Parser\Files', 'Crawler\Parser\FilesParser');
$injector->share('Crawler\Parser\FilesParser');

return $injector;
