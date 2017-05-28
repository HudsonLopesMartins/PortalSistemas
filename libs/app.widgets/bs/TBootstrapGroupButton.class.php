<?php

interface iBootstrapGroupButton {
    public function addButton(TBootstrapButton $button);
    public function show();
}

/**
 * Description of TBootstrapButton
 *
 * @author hudsonmartins
 */
class TBootstrapGroupButton extends TBootstrapCommon implements iBootstrapGroupButton{
    private $sClass = "";

    public function __construct() {
        parent::__construct("div");
        $this->role       = "group";
        $this->sClass     = "btn-group";
        //$this->aria-label = "...";
    }
    
    public function addButton(TBootstrapButton $button) {
        parent::addNode($button);
    }

    public function show() {
        try {
            $this->class = $this->sClass;
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
