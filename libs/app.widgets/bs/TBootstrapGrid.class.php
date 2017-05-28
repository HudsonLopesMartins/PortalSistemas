<?php

interface iBootstrapGrid {
    public function setClass($sClass);
    public function addItem(TBootstrapGridCell $obj);
    public function show();
}

/**
 * Description of TBootstrapGrid
 *
 * @author hudsonmartins
 */
class TBootstrapGrid extends TBootstrapCommon implements iBootstrapGrid{
    
    public function __construct() {
        parent::__construct("div");
        $this->class = "row";
    }
    
    public function setClass($sClass) {
        $this->class = $sClass;
    }
    
    public function addItem(TBootstrapGridCell $obj) {
        parent::addNode($obj);
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
