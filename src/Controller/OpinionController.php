<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use TellMe\Adapter\OpinionAdapter;
use TellMe\Adapter\OpinionToAnswerAdapter;

/**
 * Description of OpinionController
 *
 * @author Francky
 */
class OpinionController extends BaseController {
    
    public function toAnswerAction(Request $request, Application $app)
    {
        if($this->isConnected($app)) {
            $userSession = $app['session']->get('user');
            $userId = $userSession['id'];

            $opinionAdapter = new OpinionAdapter($app['db']);
            $opinionToAnswerAdapter = new OpinionToAnswerAdapter($app['db'], $opinionAdapter);

            return $app['twig']->render('opinions-to-answer.twig', array(
                'opinionsToAnswer' => $opinionToAnswerAdapter->getOpinionToAnswerListByUserId($userId, 'full'),
            ));
        } else {
            return $app->redirect($app['url_generator']->generate('login'));
        }
    }
    
    public function myOpinionsAction(Request $request, Application $app)
    {
        if($this->isConnected($app)) {
            $userSession = $app['session']->get('user');
            $userId = $userSession['id'];
            
            $opinionAdapter = new OpinionAdapter($app['db']);
            
            return $app['twig']->render('opinions.twig', array(
                'opinions' => $opinionAdapter->getOpinionListByUserId($userId, 'full'),
            ));
        } else {
            return $app->redirect($app['url_generator']->generate('login'));
        }
    }
}
