<?php

/*
 * TellMe, developped by Franck DUCHE
 */

namespace TellMe\Model;

/**
 * Description of Picture
 *
 * @author Francky
 */
class Picture {
    private $id;
    private $filename;
    private $filepath;
    
    private $opinion;
    
    function __construct($id = null, $filename = null, $filepath = null) {
        $this->id = $id;
        $this->filename = $filename;
        $this->filepath = $filepath;
    }

    public function getId() {
        return $this->id;
    }

    public function getFilename() {
        return $this->filename;
    }

    public function getFilepath() {
        return $this->filepath;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setFilename($filename) {
        $this->filename = $filename;
    }

    public function setFilepath($filepath) {
        $this->filepath = $filepath;
    }
    
    public function getOpinion() {
        return $this->opinion;
    }

    public function setOpinion($opinion) {
        $this->opinion = $opinion;
    }
}
