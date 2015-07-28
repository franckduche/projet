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
 * Session provider
 */
$app->register(new Silex\Provider\SessionServiceProvider());
$app['session.storage.options'] = array (
    'cookie_lifetime' => 31500000
);
$app->before(function ($request) {
    $request->getSession()->start();
});

/*
 * Translation provider
 */
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('en'),
));

/*
 * Validation provider
 */
$app->register(new Silex\Provider\ValidatorServiceProvider());

/*
 * Form provider
 */
$app->register(new Silex\Provider\FormServiceProvider());

/*
 * Routes
 */
$app->get('/', 'TellMe\Controller\OpinionController::toAnswerAction')
        ->bind('homepage');

$app->match('/register', 'TellMe\Controller\UserController::registerAction')
        ->bind('register');

$app->match('/login', 'TellMe\Controller\UserController::loginAction')
        ->bind('login');

$app->get('/logout', 'TellMe\Controller\UserController::logoutAction')
        ->bind('logout');

$app->get('/profile', 'TellMe\Controller\UserController::profileAction')
        ->bind('profile');

$app->get('/opinions', 'TellMe\Controller\OpinionController::myOpinionsAction')
        ->bind('opinions');





$app->get('/test', 'TellMe\Controller\HelloController::testAction')
        ->bind('test');

$app->get('/hello', 'TellMe\Controller\HelloController::greetAllAction');

$app->get('/hello/{nickname}', 'TellMe\Controller\HelloController::greetAction')
        ->assert('name', '\w+')
        ->bind('hello');

$app->run();
