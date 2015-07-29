<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Adapter;

/**
 * Description of PictureAdapter
 *
 * @author Francky
 */
class PictureAdapter extends BaseAdapter {
    
    protected $tableName = 'picture';
    
    public function create($data)
    {
        $this->conn->insert($this->tableName, $data);
        
        return $this->conn->lastInsertId();
    }
}
