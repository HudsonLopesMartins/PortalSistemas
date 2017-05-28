<?php

interface iBootstrapGridCell {
    public function setWidth($width);
    public function setPos($pos);
    public function addItem($item);
    public function show();
}

/**
 * @todo Concluir os demais tamanhos
 */
class TWidthMd {
    private $t = array("col-1"  => "col-md-1", 
                       "col-2"  => "col-md-2",
                       "col-3"  => "col-md-3",
                       "col-4"  => "col-md-4",
                       "col-5"  => "col-md-5",
                       "col-6"  => "col-md-6",
                       "col-7"  => "col-md-7",
                       "col-8"  => "col-md-8",
                       "col-9"  => "col-md-9",
                       "col-10" => "col-md-10",
                       "col-11" => "col-md-11",
                       "col-12" => "col-md-12");

    function __construct() {
        
    }
    
    public function get() {
        $tp = (object) $this->t;
        return $tp;
    }
}

/**
 * Description of TBootstrapGridCell
 *
 * @author hudsonmartins
 */
class TBootstrapGridCell extends TBootstrapCommon implements iBootstrapGridCell{
    private $sWidth;
    private $sPos;
    
    public function __construct() {
        parent::__construct("div");
        $this->class = "col-md-1";
    }
    
    public function setWidth($width) {
        $this->sWidth = $width;
    }
    
    public function setPos($pos) {
        $this->sPos = $pos;
    }
    
    public function addItem($item) {
        parent::addNode($item);
    }
    
    public function show() {
        try {
            /*
            if (!empty($this->sWidth)){
                $this->class .= " " . $this->sWidth;
            }
            if (!empty($this->sPos)){
                $this->class .= " " . $this->sPos;
            }
            if (!empty($this->sAttr)){
                for ($i = 0; $i < count($this->sAttr); $i++){
                    $this->class .= " " . $this->sAttr[$i];
                }
            }
             * 
             */
            $this->class = $this->sWidth . " " . $this->sPos;
            
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
