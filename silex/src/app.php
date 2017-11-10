<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Provider\EncoderServiceProvider;
use Provider\ParameterServiceProvider;
use Provider\SchemaContainerServiceProvider;
use Provider\FileCacheServiceProvider;
use Provider\SchemaResolverServiceProvider;
use Provider\JsonRepoFactoryServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new SchemaResolverServiceProvider());
$app->register(new SchemaContainerServiceProvider());
$app->register(new EncoderServiceProvider());
$app->register(new ParameterServiceProvider());
$app->register(new FileCacheServiceProvider());
$app->register(new JsonRepoFactoryServiceProvider());
$app->register(new HttpCacheServiceProvider(), [
    'http_cache.cache_dir' => __DIR__.'/../var/cache/',
]);
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
});

return $app;
