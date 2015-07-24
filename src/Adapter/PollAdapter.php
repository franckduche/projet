<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Adapter;

use TellMe\Model\Poll;
use TellMe\Model\Picture;

/**
 * Description of PollAdapter
 *
 * @author Francky
 */
class PollAdapter extends BaseAdapter {
    
    protected $pictureTableName = 'picture';

    public function fill(Poll &$poll)
    {
        $stmt = $this->conn->executeQuery(
                'SELECT * FROM ' . $this->pictureTableName . ' WHERE opinionId = ?',
                array($poll->getId()),
                array(\PDO::PARAM_INT)
            );
        
        if ($line = $stmt->fetch()) {
            $picture = (new Picture)->fromArray($line);
            $poll->setPicture($picture);
        }
    }
}
