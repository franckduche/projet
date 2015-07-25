<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use TellMe\Model\User;
use TellMe\Adapter\UserAdapter;
use TellMe\Adapter\OpinionAdapter;

/**
 * Description of OpinionController
 *
 * @author Francky
 */
class OpinionController {
    
    public function toAnswerAction(Request $request, Application $app)
    {
        // TODO add subscription and login
        $userId = 1;
        
        $opinionAdapter = new OpinionAdapter($app['db']);
        $userAdapter = new UserAdapter($app['db'], $opinionAdapter);
        
        return $app['twig']->render('opinions-to-answer.twig', array(
            'user' => $userAdapter->findById($userId, 'full'),
        ));
    }
}
