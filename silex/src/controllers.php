<?php

use Symfony\Component\HttpFoundation\Request;
use Exception\JsonRepositoryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Index.
 */
$app->get('/', function (Request $request) use ($app) {
    $cache = $app['cache'];
    if ($cache->has('index')) {
        $out = $cache->get('index');
    } else {
        $out = ['data' => []];
        
        $directory = new DirectoryIterator($app['data.dir']);
        foreach ($directory as $info) {
            if ($info->isDir() && !$info->isDot()) {
                $dir = $info->getFilename();
                $out['links'][$dir] = $app['app.uri'] . '/' . $dir;
            }
        }
    }        
    
    $jsonOptions = $app['debug'] ? JSON_PRETTY_PRINT : null;
    return new JsonResponse(json_encode($out, $jsonOptions), 200, [
        'Content-Type' => 'application/vnd.api+json',
        'Cache-Control' => 'public, s-maxage=3600, must-revalidate',
    ], true);
});

/**
 * Route for a listing of models of the given type.
 * 
 * example: /facility?filter[acronym]=bses&fields[facility]=name
 * example: /facility?filter[administrative_cluster][cluster]=1&fields[facility]=name
 */
$app->get('/{model}', function (Request $request, $model) use ($app) {
    $repo = $app['json.repo.factory']->getRepo($model);
    $filter = $request->get('filter', []);
    
    $where = [];
    foreach ($filter as $property => $value) {
        $where[] = [$property, '==', $value];
    }
    
    try {    
        $items = $repo->query(['where' => $where]);
    } catch (JsonRepositoryException $e) {
        throw new NotFoundHttpException($e->getMessage());
    }
    
    $json = $app['encoder']->encodeData(
        $items,
        $app['json_api.param.parser']->parse()
    );
    
    return new JsonResponse($json, 200, [
        'Content-Type' => 'application/vnd.api+json', 
        'Cache-Control' => 'public, s-maxage=3600, must-revalidate',
    ], true);
})->bind('models');

/**
 * Route for the listing of a single model.
 * 
 * example: /facility/bses?fields[facility]=acronym,full_name
 */
$app->get('/{model}/{id}', function (Request $request, $model, $id) use ($app) {
    $repo = $app['json.repo.factory']->getRepo($model);
    
    try {
        $resource = $repo->find($id);
    } catch (JsonRepositoryException $e) {
        throw new NotFoundHttpException($e->getMessage());
    }
    
    $json = $app['encoder']
        ->encodeData($resource, $app['json_api.param.parser']->parse());
    
    return new JsonResponse($json, 200, ['Content-Type' => 'application/vnd.api+json', 'Cache-Control' => 'public, s-maxage=3600, must-revalidate'], true);
})->bind('model');

/**
 * Show related model(s)
 * 
 * example: /boe_cluster/A/facilities
 */
$app->get('{model}/{id}/{related}', function (Request $request, $model, $id, $related) use ($app) {
    $repo = $app['json.repo.factory']->getRepo($model);
    
    try {
        $resource = $repo->find($id);
    } catch (JsonRepositoryException $e) {
        throw new NotFoundHttpException($e->getMessage());
    }

    $schema = $app['schema.container']->getSchemaByResourceType($model);
    $relationships = $schema->getRelationships($resource, true, []);
    
    if (empty($relationships[$related]['data'])) {
        throw new NotFoundHttpException("Could not find related $related.");
    }
    
    return new JsonResponse($app['encoder']->encodeData(
        $relationships[$related]['data'],
        $app['json_api.param.parser']->parse()
        ), 200, [
            'Content-Type' => 'application/vnd.api+json', 
            'Cache-Control' => 'public, s-maxage=3600, must-revalidate'
        ], true);
})->bind('relationship');

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }
    
    $data = ['errors' => [[
        'status' => $code,
    ]]];
    
    return new JsonResponse($data, $code, [
        'Content-Type' => 'application/vnd.api+json', 
        'Cache-Control' => 'public, s-maxage=3600, must-revalidate',
    ]);
});
