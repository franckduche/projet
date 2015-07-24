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
    const TYPE = 'choice';
    const ADAPTER = 'ChoiceAdapter';
    
    public function getPictureList() {
        return $this->pictureList;
    }

    public function setPictureList($pictureList) {
        $this->pictureList = $pictureList;
    }
}
