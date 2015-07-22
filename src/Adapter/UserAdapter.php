<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Adapter;

use TellMe\Model\User;

/**
 * Description of UserAdapter
 *
 * @author Francky
 */
class UserAdapter extends BaseAdapter {
    
    protected $tableName = 'user';
    
    public function findAll()
    {
        $stmt = $this->conn->executeQuery('SELECT * FROM ' . $this->tableName);
        $usersArray = array();
        
        while ($line = $stmt->fetch()) {
            $usersArray[] = (new User)->fromArray($line);
        }
        
        return $usersArray;
    }
}
