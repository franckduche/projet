<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use TellMe\Adapter\UserAdapter;
use TellMe\Adapter\OpinionAdapter;
use TellMe\Adapter\OpinionToAnswerAdapter;
use TellMe\Adapter\PollAdapter;
use TellMe\Adapter\PictureAdapter;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use TellMe\Model\Poll;

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
            
            $opinions = $opinionAdapter->getOpinionListByUserId($userId, 'full');
            
            $opinionsAnswers = array();
            foreach($opinions as $opinion) {
                $opinionAnswers = array('total' => 0);
                $opinionToAnswerAdapter = new OpinionToAnswerAdapter($app['db']);
                $opinionToAnswerList = $opinionToAnswerAdapter->findByOpinionId($opinion->getId());
                foreach($opinionToAnswerList as $opinionToAnswer) {
                    $opinionAnswers['total']++;
                    if(array_key_exists($opinionToAnswer->getAnswer(), $opinionAnswers)) {
                        $opinionAnswers[$opinionToAnswer->getAnswer()]++;
                    } else {
                        $opinionAnswers[$opinionToAnswer->getAnswer()] = 1;
                    }
                }
                $opinionsAnswers[] = $opinionAnswers;
            }
            
            return $app['twig']->render('opinions.twig', array(
                'opinions' => $opinions,
                'opinionsAnswers' =>$opinionsAnswers
            ));
        } else {
            return $app->redirect($app['url_generator']->generate('login'));
        }
    }
    
    public function pollAction(Request $request, Application $app)
    {
        if($this->isConnected($app)) {
            // Get friends
            $user = $app['session']->get('user');
            $userAdapter = new UserAdapter($app['db']);
            $friendList = $userAdapter->getFriendList($user['id'], 'full');
            $friends = array();
            foreach($friendList as $friend) {
                $friends[$friend->getId()] = $friend->getNickname();
            }
            
            // Form creation
            $form = $app['form.factory']->createBuilder('form')
                ->add('comment', 'text', array(
                    'constraints' => array(new Assert\NotBlank())
                ))
                ->add('file', 'file', array(
                    'constraints' => array(new Assert\Image())
                ))
                ->add('friends', 'choice', array(
                    'choices' => $friends,
                    'multiple' => true,
                    'expanded' => true
                ))
                ->getForm();

            $form->handleRequest($request);

            // Form handling
            if ($form->isValid()) {
                $data = $form->getData();
                
                // File handling
                $file = $data['file'];
                $filepath = uniqid(). '.' .$file->getClientOriginalExtension();
                try {
                    $file->move(__DIR__.'/../../web/pictures', $filepath); 
                } catch(FileException $e) {
                    $filepath = null;
                }

                // Create opinion
                $formatedData = array(
                    'userId' => $user['id'],
                    'date' => date('Y-m-d'),
                    'comment' => $data['comment'],
                    'type' => Poll::TYPE
                );
                $opinionAdapter = new OpinionAdapter($app['db']);
                $opinionId = $opinionAdapter->create($formatedData);
                
                // Create poll
                $pollAdapter = new PollAdapter($app['db']);
                $pollAdapter->create(array(
                    'opinionId' => $opinionId
                ));
                
                // Create picture
                $pictureData = array(
                    'filename' => $file->getClientOriginalName(),
                    'filepath' => $filepath,
                    'opinionId' => $opinionId
                );
                $pictureAdapter = new PictureAdapter($app['db']);
                $pictureAdapter->create($pictureData);
                
                // Create opinionToAnswer
                $opinionToAnswerAdapter = new OpinionToAnswerAdapter($app['db']);
                foreach($data['friends'] as $friendId) {
                    $opinionToAnswerAdapter->create(array(
                        'userId' => $friendId,
                        'opinionId' => $opinionId
                    ));
                }
                
                return $app->redirect($app['url_generator']->generate('opinions'));
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
