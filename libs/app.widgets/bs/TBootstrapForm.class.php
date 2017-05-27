<?php

interface iBootstrapForm {
    public function setTypeForm($t);
    public function addItem($item);
    public function show();
}

class TBootstrapFormType {
    private $t = array("inline"     => "form-inline", 
                       "horizontal" => "form-horizontal");

    function __construct() {
        
    }
    
    public function get() {
        $tp = (object) $this->t;
        return $tp;
    }

}

/**
 * Description of TBootstrapGrid
 *
 * @author hudsonmartins
 */
class TBootstrapForm extends TBootstrapCommon implements iBootstrapForm {
    private $sClass = "";
    
    public function __construct() {
        parent::__construct("form");
    }
    
    public function setTypeForm($t) {
        $this->class = $t;
    }
    
    public function addItem($item) {
        parent::addNode($item);
    }
    
    public function show() {
        try {
            $p = $this->prepare();
            if ($this->node){
                foreach ($this->node as $nodekey) {
                    if (is_object($nodekey)){
                        $p .= $nodekey->show();
                    }                
                    else if ((is_string($nodekey)) or (is_numeric($nodekey))){
                        $p .= $nodekey;
                    }
                }
            }

            $p .= $this->close();
            return $p;
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
