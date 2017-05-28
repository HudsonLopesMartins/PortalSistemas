<?php

interface iBootstrapButton {
    public function addImage($img);
    public function show();
}

/**
 * Description of TBootstrapButton
 *
 * @author hudsonmartins
 */
class TBootstrapButton extends TBootstrapCommon implements iBootstrapButton{
    private $sClass   = "";
    private $sCaption = "";
    private $sImage   = "";
    public function __construct($id, $caption = "", $option = "btn-default", $size = null, $active = FALSE, $disabled = FALSE) {
        parent::__construct("button");
        $this->id       = $id;
        $this->type     = "button";
        //$this->value = $caption;
        
        if (!empty($caption)){
            $this->sCaption = $caption;
        }
        if ($disabled == TRUE){
            $this->disabled = "disabled";
        }
        
        $this->sClass = $option;
        if (!is_null($size)){
            $this->sClass .= " {$size}";
        }
        if ($active == TRUE){
            $this->sClass .= " active";
        }
    }
    
    public function addImage($img) {
        $this->sImage = $img;
    }
    
    public function show() {
        $this->class = "btn " . $this->sClass;
        try {
            $b  = $this->prepare();
            
            if (!empty($this->sImage)){
                $b .= "<span class='glyphicon {$this->sImage}' aria-hidden='true'></span> ";
            }
            
            if (!empty($this->sCaption)){
                $b .= $this->sCaption;
            }
            $b .= $this->close();
            return $b;
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
