<?php

require_once __DIR__.'/../vendor/autoload.php';

use TellMe\Model\User;

$app = new Silex\Application();
$app['debug'] = true;

/*
 * Database connection
 */
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbhost' => 'localhost',
        'dbname' => 'tellme',
        'user' => 'root',
        'password' => '',
    ),
));

/*
 *  View handler
 */
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

/*
 * Url generator
 */
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

/*
 * Routes
 */
$app->get('/', 'TellMe\Controller\OpinionController::toAnswerAction')
        ->bind('homepage');

$app->get('/test', 'TellMe\Controller\HelloController::testAction')
        ->bind('test');

$app->get('/hello', 'TellMe\Controller\HelloController::greetAllAction');

$app->get('/hello/{nickname}', 'TellMe\Controller\HelloController::greetAction')
        ->assert('name', '\w+')
        ->bind('hello');

$app->run();
