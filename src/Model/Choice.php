<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Model;

/**
 * Description of Choice
 *
 * @author Francky
 */
class Choice extends Opinion {
    private $pictureList;
    
    function __construct($id = null, $date = null, $comment = null, $pictureList = null) {
        parent::__construct($id, $date, $comment);
        $this->pictureList = $pictureList;
    }
    
    public function getPictureList() {
        return $this->pictureList;
    }

    public function setPictureList($pictureList) {
        $this->pictureList = $pictureList;
    }
}
