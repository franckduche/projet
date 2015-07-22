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
    
    function __construct($id = null, $date = null, $userId = null, $opinionId = null, $answer = null) {
        $this->id = $id;
        $this->date = $date;
        $this->userId = $userId;
        $this->opinionId = $opinionId;
        $this->answer = $answer;
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


}
