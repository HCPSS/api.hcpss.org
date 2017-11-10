<?php

// configure your app for the production environment

//$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');
$app['app.root'] = __DIR__ . '/../';
$app['cache.dir'] = $app['app.root'] . 'var/cache';
$app['data.dir'] = $app['app.root'] . 'data';
$app['app.uri'] = 'http://localhost:8099/index.php';
