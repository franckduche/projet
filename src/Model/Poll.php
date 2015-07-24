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
    const TYPE = 'poll';
    const ADAPTER = 'PollAdapter';

    public function getPicture() {
        return $this->picture;
    }

    public function setPicture($picture) {
        $this->picture = $picture;
    }
}
