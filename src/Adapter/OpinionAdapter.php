<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Adapter;

use TellMe\Model\User;

/**
 * Description of OpinionAdapter
 *
 * @author Francky
 */
class OpinionAdapter extends BaseAdapter {
    
    protected $tableName = 'opinion';
    protected $opinionToAnswerTableName = 'opiniontoanswer';
    
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
                $adapterClass = "TellMe\\Adapter\\".ucfirst($class::ADAPTER);
                $adapter = new $adapterClass($this->conn);
                $adapter->fill($opinion);
            }
        }
        
        return $opinion;
    }
    
    public function getOpinionListByUserId(User $user, $hydrate = false)
    {
        $opinionList = array();
        
        $stmt = $this->conn->executeQuery(
                'SELECT * FROM ' . $this->tableName . ' WHERE userId = ?',
                array($user->getId()),
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
    
    public function getOpinionToAnswerListByUserId(User $user, $hydrate = false)
    {
        $opinionList = array();
        
        $stmt = $this->conn->executeQuery(
                'SELECT * FROM ' . $this->opinionToAnswerTableName . ' WHERE userId = ?',
                array($user->getId()),
                array(\PDO::PARAM_INT)
            );
        
        while ($line = $stmt->fetch()) {
            $opinionList[] = array($line['opinionId'], $line['answer']);
        }

        // Hydrate if necessary
        if ($hydrate == 'full') {
            $opinionListCopy = $opinionList;
            $opinionList = array();
            
            foreach ($opinionListCopy as $opinionId){
                $opinionList[] = array($this->findById($opinionId[0], $hydrate), $opinionId[1]);
            }
        }
        
        return $opinionList;
    }
}
