<?php

require_once __DIR__.'/../vendor/autoload.php';

use TellMe\Model\User;

$app = new Silex\Application();
$app['debug'] = true;

/*
 *  View handler
 */
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

/*
 * Routes
 */
$app->get('/', function () use ($app) {
    return $app->redirect('/hello');
});

$app->get('/hello', function () {
    return 'Hello!';
});

$app->get('/hello/{nickname}', 'TellMe\Controller\HelloController::greetAction')
    ->assert('name', '\w+');

/*
$app
    ->get('/documents', 'Propilex\Controller\DocumentController::listAction')
    ->bind('document_list');
$app
    ->get('/documents/{id}', 'Propilex\Controller\DocumentController::getAction')
    ->assert('id', '\d+')
    ->bind('document_get');
$app
    ->post('/documents', 'Propilex\Controller\DocumentController::postAction')
    ->bind('document_post');
$app
    ->put('/documents/{id}', 'Propilex\Controller\DocumentController::putAction')
    ->assert('id', '\d+')
    ->bind('document_put');
$app
    ->delete('/documents/{id}', 'Propilex\Controller\DocumentController::deleteAction')
    ->assert('id', '\d+')
    ->bind('document_delete');
*/

$app->run();
