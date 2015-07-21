<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use TellMe\Model\User;

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
}
