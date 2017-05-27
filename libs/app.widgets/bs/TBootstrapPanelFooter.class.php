<?php

interface iBootstrapPanelFooter {
    public function addItem($component);
    public function show();
}

/**
 * Description of TBootstrapPanel
 *
 * @author hudsonmartins
 */
class TBootstrapPanelFooter extends TBootstrapCommon implements iBootstrapPanelFooter {

    
    public function __construct() {
        parent::__construct("div");
        $this->class = "panel-footer";
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
