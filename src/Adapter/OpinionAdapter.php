<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Adapter;

/**
 * Description of OpinionAdapter
 *
 * @author Francky
 */
class OpinionAdapter extends BaseAdapter {
    
    protected $tableName = 'opinion';
    
    public function findById($id, $hydrate = false)
    {
        $stmt = $this->conn->executeQuery(
                'SELECT * FROM ' . $this->tableName . ' WHERE id = ?',
                array($id),
                array(\PDO::PARAM_INT)
            );
        
        if ($line = $stmt->fetch()) {
            $class = "TellMe\\Model\\".ucfirst($line['type']);
            $opinion = (new $class())->fromArray($line);
            if ($hydrate) {
                // Filling with additionnal informations of subclass
                $adapterClass = "TellMe\\Adapter\\".ucfirst($class::ADAPTER);
                $adapter = new $adapterClass($this->conn);
                $adapter->fill($opinion);
                
                // Setting the owner of the opinion
                $userAdapter = new UserAdapter($this->conn);
                $opinion->setOwner($userAdapter->findById($opinion->getUserId()));
            }
        }
        
        return $opinion;
    }
    
    public function getOpinionListByUserId($userId, $hydrate = false)
    {
        $opinionList = array();
        
        $stmt = $this->conn->executeQuery(
                'SELECT * FROM ' . $this->tableName . ' WHERE userId = ?',
                array($userId),
                array(\PDO::PARAM_INT)
            );
        
        while ($line = $stmt->fetch()) {
            $opinionList[] = $line['id'];
        }
        
        // Hydrate if necessary
        if ($hydrate == 'full') {
            $opinionListCopy = $opinionList;
            $opinionList = array();
            
            foreach ($opinionListCopy as $opinionId){
                $opinionList[] = $this->findById($opinionId, $hydrate);
            }
        }
        
        return $opinionList;
    }
    
    public function create($data)
    {
        $this->conn->insert($this->tableName, $data);
        
        return $this->conn->lastInsertId();
    }
}
