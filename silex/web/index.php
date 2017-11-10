<?php

use Symfony\Component\HttpFoundation\Request;

ini_set('display_errors', 0);

require_once __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../src/app.php';
require __DIR__.'/../config/prod.php';
require __DIR__.'/../src/controllers.php';

Request::setTrustedProxies(array('*'));
$app['http_cache']->run();

//$app->run();
