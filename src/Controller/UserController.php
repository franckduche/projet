<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use TellMe\Adapter\UserAdapter;


/**
 * Description of UserController
 *
 * @author Francky
 */
class UserController extends BaseController {
    
    public function loginAction(Request $request, Application $app)
    {
        if(!$this->isConnected($app)) {
            // Form creation
            $form = $app['form.factory']->createBuilder('form')
                ->add('nickname', 'text', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
                ))
                ->add('password', 'password', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
                ))
                ->getForm();

            $form->handleRequest($request);

            // Form handling
            if ($form->isValid()) {
                $data = $form->getData();

                $userAdapter = new UserAdapter($app['db']);
                $user = $userAdapter->findByNickname($data['nickname']);

                // Check data
                if ($user == null || $data['password'] != $user->getPassword()) {
                    return $app['twig']->render('login.twig', array('form' => $form->createView()));
                } else {
                    $app['session']->set('user', array('id' => $user->getId(), 'nickname' => $user->getNickname()));

                    // redirect to homepage
                    return $app->redirect($app['url_generator']->generate('homepage'));
                }
            }

            // display the form
            return $app['twig']->render('login.twig', array('form' => $form->createView()));
        } else {
            return $app->redirect($app['url_generator']->generate('homepage'));
        }
    }
    
    public function logoutAction(Request $request, Application $app)
    {
        $app['session']->set('user', null);
        return $app->redirect($app['url_generator']->generate('login'));
    }
    
    public function profileAction(Request $request, Application $app)
    {
        if($this->isConnected($app)) {
            
            $userAdapter = new UserAdapter($app['db']);
            $userSession = $app['session']->get('user');
            
            // Add friend form
            $form = $app['form.factory']->createBuilder('form')
                ->add('nickname', 'text', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
                ))
                ->getForm();

            $form->handleRequest($request);
            
            // Form handling
            if ($form->isValid()) {
                $data = $form->getData();
                $userToAdd = $userAdapter->findByNickname($data['nickname']);
                $app['session']->getFlashBag()->add('message', $userToAdd ? 'Friend request sent' : 'Nickname not found');
                if ($userToAdd != null) {
                    $userAdapter->createFriendship($userSession['id'], $userToAdd->getId());
                }
            }
              
            $user = $userAdapter->findById($userSession['id'], 'friendlist');
            $pendingFriends = $userAdapter->getFriendRequestedList($userSession['id'], 'friendlist');
            $friendsToAccept = $userAdapter->getFriendToAcceptList($userSession['id'], 'friendlist');
            
            return $app['twig']->render('profile.twig', array(
                'user' => $user,
                'pendingFriends' => $pendingFriends,
                'friendsToAccept' => $friendsToAccept,
                'form' => $form->createView()
            ));
        } else {
            return $app->redirect($app['url_generator']->generate('login'));
        }
    }
    
    public function registerAction(Request $request, Application $app)
    {
        if(!$this->isConnected($app)) {
            // Form creation
            $form = $app['form.factory']->createBuilder('form')
                ->add('phoneNumber', 'text', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 10)))
                ))
                ->add('nickname', 'text', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
                ))
                ->add('password', 'password', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
                ))
                ->add('passwordCheck', 'password', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
                ))
                ->getForm();

            $form->handleRequest($request);

            // Form handling
            if ($form->isValid()) {
                $data = $form->getData();

                if ($data['password'] === $data['passwordCheck']) {
                    unset($data['passwordCheck']);
                    
                    // creates new user
                    $userAdapter = new UserAdapter($app['db']);
                    $user = $userAdapter->create($data);

                    // connects him
                    $app['session']->set('user', array('id' => $user->getId(), 'nickname' => $user->getNickname()));

                    // redirect to homepage
                    return $app->redirect($app['url_generator']->generate('homepage'));
                } else {
                    // display the form
                    return $app['twig']->render('register.twig', array('form' => $form->createView()));
                }
            }

            // display the form
            return $app['twig']->render('register.twig', array('form' => $form->createView()));
        } else {
            return $app->redirect($app['url_generator']->generate('homepage'));
        }
    }
    
    public function acceptFriendAction(Request $request, Application $app, $userId1, $accepted)
    {
        if($this->isConnected($app)) {
            $app['session']->getFlashBag()->add('message', $accepted ? 'Friend accepted' : 'Friend request declined');
            $userAdapter = new UserAdapter($app['db']);
            $userSession = $app['session']->get('user');
            $userAdapter->acceptFriend($userId1, $userSession['id'], $accepted);
            return $app->redirect($app['url_generator']->generate('profile'));
        } else {
            return $app->redirect($app['url_generator']->generate('login'));
        }
    }
}
