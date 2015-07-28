<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
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
    
    public function pollAction(Request $request, Application $app)
    {
        if($this->isConnected($app)) {
            // Form creation
            $form = $app['form.factory']->createBuilder('form')
                ->add('comment', 'text', array(
                    'constraints' => array(new Assert\NotBlank())
                ))
                ->add('file', 'file', array(
                    'constraints' => array(new Assert\Image())
                ))
                ->getForm();

            $form->handleRequest($request);

            // Form handling
            if ($form->isValid()) {
                $data = $form->getData();

                $userAdapter = new OpinionAdapter($app['db']);

                // Check data
                if (false) {
                    return $app['twig']->render('opinion/poll-add-form.twig', array('form' => $form->createView()));
                } else {
                    // add opinion
                    
                    
                    // redirect to opinions
                    return $app->redirect($app['url_generator']->generate('opinions'));
                }
            }

            // display the form
            return $app['twig']->render('opinion/poll-add-form.twig', array('form' => $form->createView()));
        } else {
            return $app->redirect($app['url_generator']->generate('login'));
        }
    }
    
    public function choiceAction(Request $request, Application $app)
    {
        if($this->isConnected($app)) {
            die;
        } else {
            return $app->redirect($app['url_generator']->generate('login'));
        }
    }
}
