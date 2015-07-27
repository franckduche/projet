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
            $user = $userAdapter->findById($userSession['id'], 'friendlist');
            return $app['twig']->render('profile.twig', array('user' => $user));
        } else {
            return $app->redirect($app['url_generator']->generate('login'));
        }
    }
}
