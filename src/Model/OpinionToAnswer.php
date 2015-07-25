<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Model;

/**
 * Description of OpinionToAnswer
 *
 * @author Francky
 */
class OpinionToAnswer {
    private $id;
    private $date;
    private $userId;
    private $opinionId;
    private $answer;
    
    private $opinion;
    private $user;
    
    function __construct($id = null, $date = null, $userId = null, $opinionId = null, $answer = null) {
        $this->id = $id;
        $this->date = $date;
        $this->userId = $userId;
        $this->opinionId = $opinionId;
        $this->answer = $answer;
    }
	
    public static function fromArray(array $array = array())
    {
        return new self($array['id'], $array['date'], $array['userId'],
                $array['opinionId'], $array['answer']);
    }

    public function getId() {
        return $this->id;
    }

    public function getDate() {
        return $this->date;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getOpinionId() {
        return $this->opinionId;
    }

    public function getAnswer() {
        return $this->answer;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setOpinionId($opinionId) {
        $this->opinionId = $opinionId;
    }

    public function setAnswer($answer) {
        $this->answer = $answer;
    }

    public function getOpinion() {
        return $this->opinion;
    }

    public function getUser() {
        return $this->user;
    }

    public function setOpinion($opinion) {
        $this->opinion = $opinion;
    }

    public function setUser($user) {
        $this->user = $user;
    }
}
