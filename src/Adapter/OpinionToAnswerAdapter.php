<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Adapter;

use TellMe\Model\OpinionToAnswer;
use Doctrine\DBAL\Connection;

/**
 * Description of OpinionToAnswerAdapter
 *
 * @author Francky
 */
class OpinionToAnswerAdapter extends BaseAdapter {
    
    protected $tableName = 'opiniontoanswer';
    protected $opinionAdapter;
    
    public function __construct(Connection $conn, OpinionAdapter $opinionAdapter = null) {
        parent::__construct($conn);
        $this->opinionAdapter = $opinionAdapter;
    }
    
    public function getOpinionToAnswerListByUserId($userId, $hydrate = false)
    {
        $opinionToAnswerList = array();
        
        $stmt = $this->conn->executeQuery(
                'SELECT * FROM ' . $this->tableName . ' WHERE userId = ?',
                array($userId),
                array(\PDO::PARAM_INT)
            );
        
        while ($line = $stmt->fetch()) {
            $opinionToAnswer = (new OpinionToAnswer)->fromArray($line);
            $opinionToAnswerList[] = $opinionToAnswer;
        }

        // Hydrate if necessary
        if ($hydrate == 'full') {
            foreach ($opinionToAnswerList as $opinionToAnswer){
                $opinionToAnswer->setOpinion($this->opinionAdapter->findById($opinionToAnswer->getOpinionId(), $hydrate));
            }
        }
        
        return $opinionToAnswerList;
    }
    
    public function create($data)
    {
        $this->conn->insert($this->tableName, $data);
        
        return $this->conn->lastInsertId();
    }
}
