<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Adapter;

use TellMe\Model\Choice;
use TellMe\Model\Picture;

/**
 * Description of ChoiceAdapter
 *
 * @author Francky
 */
class ChoiceAdapter extends BaseAdapter {
    
    protected $tableName = 'choice';
    protected $pictureTableName = 'picture';
    
    public function fill(Choice &$choice)
    {
        $pictureList = array();
        
        $stmt = $this->conn->executeQuery(
                'SELECT * FROM ' . $this->pictureTableName . ' WHERE opinionId = ?',
                array($choice->getId()),
                array(\PDO::PARAM_INT)
            );
        
        while ($line = $stmt->fetch()) {
            $picture = (new Picture)->fromArray($line);
            $pictureList[] = $picture;
        }
        
        $choice->setPictureList($pictureList);
    }
    
    public function create($data)
    {
        $this->conn->insert($this->tableName, $data);
        
        return $this->conn->lastInsertId();
    }
}
