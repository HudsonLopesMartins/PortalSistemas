<?php

interface iBootstrapEdit {
    public function setLabel($label);
    public function setText($text);
    public function setPlaceholder($text);
    public function setType($type = null);
    public function setClass($class);
    //public function getId();
    public function show();
}

class TTypeEdit {
    private $t = array("text"     => "text", 
                       "password" => "password", 
                       "email"    => "email",
                       "number"   => "number");

    function __construct() {
        
    }
    
    public function get() {
        $tp = (object) $this->t;
        return $tp;
    }

}

/**
 * Description of TBootstrapEdit
 *
 * @author hudsonmartins
 */
class TBootstrapEdit extends TBootstrapCommon implements iBootstrapEdit {
    private $sLabel = "";
    private $sID    = "";
    
    public function __construct($id, $type = "text", $label = "", $class = "") {
        parent::__construct("input");
        
        $this->id  = $id;
        $this->sID = $id;
        
        if (!empty($label)){
            $this->sLabel = $label;
        }

        if ($type == null){
            $this->Type = "text";
        }
        else {
            $this->type = $type;
        }
        
        if (!empty($class)){
            //$this->class = "form-control";
            $this->class = $class;
        }
    }
    
    public function setLabel($text) {
        $this->sLabel = $text;
        
    }
    
    public function setText($text) {
        $this->value = $text;
    }
    
    
    public function setPlaceholder($text) {
        $this->placeholder = $text;
    }
    
    public function setType($type = null) {
        $this->type = $type;
    }
    
    public function setClass($class) {
        $this->class = $class;
    }
    
    public function show() {
        $lbl = "";
        if (!empty($this->sLabel)){
            $lbl = "<div class='form-group'><label for='{$this->sID}'>{$this->sLabel}</label>";
        }
        if (!empty($this->sLabel)){
            $edit = $lbl . $this->prepare() . "</div>";
        }
        else {
            $edit = $lbl . $this->prepare();
        }
        return $edit;        
    }

}
