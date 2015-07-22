<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Model;

/**
 * Description of Poll
 *
 * @author Francky
 */
class Poll extends Opinion {
    private $picture;
    
    function __construct($id = null, $date = null, $comment = null, $picture = null) {
        parent::__construct($id, $date, $comment);
        $this->picture = $picture;
    }

    public function getPicture() {
        return $this->picture;
    }

    public function setPicture($picture) {
        $this->picture = $picture;
    }
}
