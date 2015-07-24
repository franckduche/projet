<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Model;

/**
 * Description of Opinion
 *
 * @author Francky
 */
abstract class Opinion {
    protected $id;
    protected $date;
    protected $comment;
    protected $type;
    
    protected $owner;
    protected $userList;
    
    function __construct($id = null, $date = null, $comment = null, $type = null) {
        $this->id = $id;
        $this->date = $date;
        $this->comment = $comment;
        $this->type = $type;
    }
	
    public static function fromArray(array $array = array())
    {
        $class = get_called_class();
        return new $class($array['id'], $array['date'], $array['comment'],
                $array['type']);
    }

    public function getId() {
        return $this->id;
    }

    public function getDate() {
        return $this->date;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }
    
    public function getOwner() {
        return $this->owner;
    }

    public function getUserList() {
        return $this->userList;
    }

    public function setOwner($owner) {
        $this->owner = $owner;
    }

    public function setUserList($userList) {
        $this->userList = $userList;
    }
    
    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }
}
