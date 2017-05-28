<?php

interface iBootstrapToolbarButton {
    public function addGroupButton(TBootstrapGroupButton $grp);
    public function show();
}

/**
 * Description of TBootstrapButton
 *
 * @author hudsonmartins
 */
class TBootstrapToolbarButton extends TBootstrapCommon implements iBootstrapToolbarButton{
    private $sClass = "";

    public function __construct() {
        parent::__construct("div");
        $this->role       = "toolbar";
        $this->sClass     = "btn-toolbar";
        //$this->aria-label = "...";
    }
    
    public function addGroupButton(TBootstrapGroupButton $grp) {
        parent::addNode($grp);
    }

    public function show() {
        try {
            $this->class = $this->sClass;
            $t = $this->prepare();
            if ($this->node){
                foreach ($this->node as $nodekey) {
                    if (is_object($nodekey)){
                        $t .= $nodekey->show();
                    }                
                    else if ((is_string($nodekey)) or (is_numeric($nodekey))){
                        $t .= $nodekey;
                    }
                }
            }

            $t .= $this->close();
            return $t;
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
