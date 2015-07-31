<?php

require_once __DIR__.'/../vendor/autoload.php';

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
        /*'dbhost' => 'sql2.olympe.in',
        'dbname' => 'zonzlhno',
        'user' => 'zonzlhno',
        'password' => 'franck',*/
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

$app->match('/profile', 'TellMe\Controller\UserController::profileAction')
        ->bind('profile');

$app->get('/friend/{userId1}/{accepted}', 'TellMe\Controller\UserController::acceptFriendAction')
        ->assert('userId1', '\d+')
        ->assert('accepted', '\d+')
        ->bind('accept_friend');

$app->get('/opinions', 'TellMe\Controller\OpinionController::myOpinionsAction')
        ->bind('opinions');

$app->match('/poll', 'TellMe\Controller\OpinionController::pollAction')
        ->bind('poll');

$app->get('/answer/{opinionAnswerId}/{answer}', 'TellMe\Controller\OpinionController::answerAction')
        ->assert('opinionAnswerId', '\d+')
        ->assert('answer', '\d+')
        ->bind('answer');

$app->match('/choice', 'TellMe\Controller\OpinionController::choiceAction')
        ->bind('choice');





$app->get('/test', 'TellMe\Controller\HelloController::testAction')
        ->bind('test');

$app->get('/hello', 'TellMe\Controller\HelloController::greetAllAction');

$app->get('/hello/{nickname}', 'TellMe\Controller\HelloController::greetAction')
        ->assert('name', '\w+')
        ->bind('hello');

$app->run();
