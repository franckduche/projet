<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Adapter;

use TellMe\Model\User;
use Doctrine\DBAL\Connection;

/**
 * Description of UserAdapter
 *
 * @author Francky
 */
class UserAdapter extends BaseAdapter {
    
    protected $tableName = 'user';
    protected $FriendListTableName = 'friendlist';
    protected $opinionAdapter;
    
    public function __construct(Connection $conn, OpinionAdapter $opinionAdapter = null) {
        parent::__construct($conn);
        $this->opinionAdapter = $opinionAdapter;
    }

    public function findAll($hydrate = false)
    {
        $stmt = $this->conn->executeQuery('SELECT * FROM ' . $this->tableName);
        $usersArray = array();
        
        while ($line = $stmt->fetch()) {
            $user = (new User)->fromArray($line);
            if ($hydrate) {
                $user->setFriendList($this->getFriendList($user, $hydrate));
                $user->setOpinionList($this->opinionAdapter->getOpinionListByUserId($user, $hydrate));
            }
            $usersArray[] = $user;
        }
        
        return $usersArray;
    }
    
    public function findById($id, $hydrate = false)
    {
        $stmt = $this->conn->executeQuery(
                'SELECT * FROM ' . $this->tableName . ' WHERE id = ?',
                array($id),
                array(\PDO::PARAM_INT)
            );
        
        if ($line = $stmt->fetch()) {
            $user = (new User)->fromArray($line);
            if ($hydrate) {
                $user->setFriendList($this->getFriendList($user, $hydrate));
            }
        }
        
        return $user;
    }
    
    public function getFriendList(User $user, $hydrate = false)
    {
        $friendIdList = array();

        // Get in first order
        $stmt1 = $this->conn->executeQuery(
                'SELECT * FROM ' . $this->FriendListTableName . ' WHERE userId1 = ?',
                array($user->getId()),
                array(\PDO::PARAM_INT)
            );
        
        while ($line = $stmt1->fetch()) {
            $friendIdList[] = $line['userId2'];
        }
        
        // Get in second order
        $stmt2 = $this->conn->executeQuery(
                'SELECT * FROM ' . $this->FriendListTableName . ' WHERE userId2 = ?',
                array($user->getId()),
                array(\PDO::PARAM_INT)
            );
        
        while ($line = $stmt2->fetch()) {
            $friendIdList[] = $line['userId1'];
        }
        
        asort($friendIdList);
        
        // Hydrate if necessary
        if ($hydrate == 'full') {
            $friendIdListCopy = $friendIdList;
            $friendIdList = array();
            
            foreach ($friendIdListCopy as $friendId){
                $friendIdList[] = $this->findById($friendId);
            }
        }
        
        return $friendIdList;
    }
}
