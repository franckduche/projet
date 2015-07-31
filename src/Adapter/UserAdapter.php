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
    protected $opinionToAnswerAdapter;
    
    public function __construct(Connection $conn, OpinionAdapter $opinionAdapter = null, OpinionToAnswerAdapter $opinionToAnswerAdapter = null) {
        parent::__construct($conn);
        $this->opinionAdapter = $opinionAdapter;
        $this->opinionToAnswerAdapter = $opinionToAnswerAdapter;
    }

    public function findAll($hydrate = false)
    {
        $stmt = $this->conn->executeQuery('SELECT * FROM ' . $this->tableName);
        $usersArray = array();
        
        while ($line = $stmt->fetch()) {
            $user = (new User)->fromArray($line);
            if ($hydrate) {
                $user->setFriendList($this->getFriendList($user->getId(), $hydrate));
                $user->setOpinionList($this->opinionAdapter->getOpinionListByUserId($user->getId(), $hydrate));
                $user->setOpinionToAnswerList($this->opinionToAnswerAdapter->getOpinionToAnswerListByUserId($user->getId(), $hydrate));
            }
            $usersArray[] = $user;
        }
        
        return $usersArray;
    }
    
    public function findById($id, $hydrate = false)
    {
        $user = null;
        
        $stmt = $this->conn->executeQuery(
                'SELECT * FROM ' . $this->tableName . ' WHERE id = ?',
                array($id),
                array(\PDO::PARAM_INT)
            );
        
        if ($line = $stmt->fetch()) {
            $user = (new User)->fromArray($line);
            if ($hydrate == 'friendlist') {
                $user->setFriendList($this->getFriendList($user->getId(), $hydrate));
            }
            elseif ($hydrate) {
                $user->setFriendList($this->getFriendList($user->getId(), $hydrate));
                $user->setOpinionList($this->opinionAdapter->getOpinionListByUserId($user->getId(), $hydrate));
                $user->setOpinionToAnswerList($this->opinionToAnswerAdapter->getOpinionToAnswerListByUserId($user->getId(), $hydrate));
            }
        }
        
        return $user;
    }
    
    public function getFriendList($userId, $hydrate = false)
    {
        $friendIdList = array();

        // Get in first order
        $stmt1 = $this->conn->executeQuery(
                'SELECT * FROM ' . $this->FriendListTableName . ' WHERE userId1 = ? AND accepted = 1',
                array($userId),
                array(\PDO::PARAM_INT)
            );
        
        while ($line = $stmt1->fetch()) {
            $friendIdList[] = $line['userId2'];
        }
        
        // Get in second order
        $stmt2 = $this->conn->executeQuery(
                'SELECT * FROM ' . $this->FriendListTableName . ' WHERE userId2 = ? AND accepted = 1',
                array($userId),
                array(\PDO::PARAM_INT)
            );
        
        while ($line = $stmt2->fetch()) {
            $friendIdList[] = $line['userId1'];
        }
        
        asort($friendIdList);
        
        // Hydrate if necessary
        if ($hydrate == 'full' || $hydrate == 'friendlist') {
            $friendIdListCopy = $friendIdList;
            $friendIdList = array();
            
            foreach ($friendIdListCopy as $friendId){
                $friendIdList[] = $this->findById($friendId);
            }
        }
        
        return $friendIdList;
    }
    
    public function findByNickname($nickname, $hydrate = null)
    {
        $user = null;
        
        $stmt = $this->conn->executeQuery(
                'SELECT * FROM ' . $this->tableName . ' WHERE nickname = ?',
                array($nickname),
                array(\PDO::PARAM_STR)
            );
        
        if ($line = $stmt->fetch()) {
            $user = (new User)->fromArray($line);
            if ($hydrate) {
                $user->setFriendList($this->getFriendList($user->getId(), $hydrate));
                $user->setOpinionList($this->opinionAdapter->getOpinionListByUserId($user->getId(), $hydrate));
                $user->setOpinionToAnswerList($this->opinionToAnswerAdapter->getOpinionToAnswerListByUserId($user->getId(), $hydrate));
            }
        }
        
        return $user;
    }
    
    public function create($data)
    {
        $this->conn->insert($this->tableName, $data);
        
        $userId = $this->conn->lastInsertId();
        
        $user = $this->findById($userId);
        
        return $user;
    }
    
    public function getFriendRequestedList($userId, $hydrate = false)
    {
        $friendIdList = array();

        // Get lines where not accepted
        $stmt = $this->conn->executeQuery(
                'SELECT * FROM ' . $this->FriendListTableName . ' WHERE userId1 = ? AND accepted IS NULL',
                array($userId),
                array(\PDO::PARAM_INT)
            );
        
        while ($line = $stmt->fetch()) {
            $friendIdList[] = $line['userId2'];
        }
        
        // Hydrate if necessary
        if ($hydrate == 'full' || $hydrate == 'friendlist') {
            $friendIdListCopy = $friendIdList;
            $friendIdList = array();
            
            foreach ($friendIdListCopy as $friendId){
                $friendIdList[] = $this->findById($friendId);
            }
        }
        
        return $friendIdList;
    }
    
    public function getFriendToAcceptList($userId, $hydrate = false)
    {
        $friendIdList = array();

        // Get lines where not accepted
        $stmt = $this->conn->executeQuery(
                'SELECT * FROM ' . $this->FriendListTableName . ' WHERE userId2 = ? AND accepted IS NULL',
                array($userId),
                array(\PDO::PARAM_INT)
            );
        
        while ($line = $stmt->fetch()) {
            $friendIdList[] = $line['userId1'];
        }
        
        // Hydrate if necessary
        if ($hydrate == 'full' || $hydrate == 'friendlist') {
            $friendIdListCopy = $friendIdList;
            $friendIdList = array();
            
            foreach ($friendIdListCopy as $friendId){
                $friendIdList[] = $this->findById($friendId);
            }
        }
        
        return $friendIdList;
    }
    
    public function acceptFriend($userId1, $userId2, $accepted)
    {
        $this->conn->update(
            $this->FriendListTableName,
            array('accepted' => $accepted),
            array('userId1' => $userId1, 'userId2' => $userId2)
        );
    }
}
