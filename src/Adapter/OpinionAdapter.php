<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Adapter;

use TellMe\Model\User;

/**
 * Description of OpinionAdapter
 *
 * @author Francky
 */
class OpinionAdapter extends BaseAdapter {
    
    protected $tableName = 'opinion';
    
    public function getOpinionListByUserId(User $user, $hydrate = false)
    {
        
    }
}
