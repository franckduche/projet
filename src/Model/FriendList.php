<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Model;

/**
 * Description of FriendList
 *
 * @author Francky
 */
class FriendList {
    private $id;
    
    private $userList;
    
    function __construct($id = null) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserList() {
        return $this->userList;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUserList($userList) {
        $this->userList = $userList;
    }
}
