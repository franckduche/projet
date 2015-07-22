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
    
    function __construct($id = null, $date = null, $comment = null) {
        $this->id = $id;
        $this->date = $date;
        $this->comment = $comment;
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
}
