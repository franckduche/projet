<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Adapter;

use Doctrine\DBAL\Connection;

/**
 * Description of BaseAdapter
 *
 * @author Francky
 */
abstract class BaseAdapter {
    
    protected $conn;
    protected $tableName;
    
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }
}
