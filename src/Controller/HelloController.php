<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use TellMe\Model\User;
use TellMe\Adapter\UserAdapter;

/**
 * Description of HelloController
 *
 * @author Francky
 */
class HelloController {
    
    public function greetAction(Request $request, Application $app, $nickname)
    {
        $user = new User('0606060606', $nickname, 'aoeu98,2uoe');

        return $app['twig']->render('hello.twig', array(
            'user' => $user,
        ));
    }
    
    public function greetAllAction(Request $request, Application $app)
    {
        $userAdapter = new UserAdapter($app['db']);

        return $app['twig']->render('hello-all.twig', array(
            'users' => $userAdapter->findAll(),
        ));
    }
}
