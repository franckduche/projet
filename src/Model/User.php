<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Model;

/**
 * Description of User
 *
 * @author Francky
 */
class User {
    private $id;
    private $phoneNumber;
    private $nickname;
    private $password;
    
    function __construct($phoneNumber, $nickname, $password, $id = null) {
        $this->id = $id;
        $this->phoneNumber = $phoneNumber;
        $this->nickname = $nickname;
        $this->password = $password;
    }
	
    public static function fromArray(array $array = array())
    {
        return new self($array['phoneNumber'], $array['nickname'],
                $array['password'], $array['id']);
    }

    public function getId() {
        return $this->id;
    }

    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    public function getNickname() {
        return $this->nickname;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;
    }

    public function setNickname($nickname) {
        $this->nickname = $nickname;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

}
