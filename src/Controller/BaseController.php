<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Controller;

use Silex\Application;

/**
 * Description of BaseController
 *
 * @author Francky
 */
class BaseController {
    
    protected function isConnected(Application $app)
    {
        return null !== $app['session']->get('user');
    }
}
