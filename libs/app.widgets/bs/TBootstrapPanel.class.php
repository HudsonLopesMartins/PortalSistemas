<?php

interface iBootstrapPanel {
    public function setTitle($text, $isTitle = FALSE, $head = "h3");
    public function setFooter(TBootstrapPanelFooter $f);
    public function setClass($class);
    public function addItem($component);
    public function show();
}

/**
 * Description of TBootstrapPanel
 *
 * @author hudsonmartins
 */
class TBootstrapPanel extends TBootstrapCommon implements iBootstrapPanel {
    private $dTitle = null;
    private $dFoot  = null;
    
    public function __construct($sClass = "panel panel-default") {
        parent::__construct("div");
        $this->class = $sClass;
    }
    
    public function setTitle($text, $isTitle = FALSE, $head = "h3") {
        $isT = (bool) $isTitle;
        if ($isT){
            $this->dTitle = "<div class='panel-heading'><{$head} class='panel-title'>{$text}</{$head}></div>";
        }
        else {
            $this->dTitle = "<div class='panel-heading'>{$text}</div>";
        }
    }
    
    public function setFooter(TBootstrapPanelFooter $f) {
        $this->dFoot = $f;
    }
    
    public function setClass($class) {
        $this->class = $class;
    }
    
    public function addItem($item) {
        parent::addNode($item);
    }
    
    public function show() {
        try {
            $p          = $this->prepare();
            if (!is_null($this->dTitle)){
                $p     .= $this->dTitle;
            }
            $panelBody  = "<div class='panel-body'>";
            
            if ($this->node){
                foreach ($this->node as $nodekey) {
                    if (is_object($nodekey)){
                        $panelBody .= $nodekey->show();
                    }                
                    else if ((is_string($nodekey)) or (is_numeric($nodekey))){
                        $panelBody .= $nodekey;
                    }
                }
            }
            $panelBody  .= "</div>";
            $p .= $panelBody;
            
            if ($this->dFoot){
                $panelFooter = $this->dFoot->show();
                $p .= $panelFooter;
            }
            
            $p .= $this->close();
            return $p;
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
