<?php
/**
 * Description of TBootstrapSelect
 *
 * @author hudsonmartins
 */

interface iBootstrapGroupCheckButton {
    public function addItem($value, $text, $selected = false);
    public function show();
}

class TOrientetaion {
    private $t = array("horizontal" => "btn-group", 
                       "vertical"   => "btn-group-vertical");

    function __construct() {
        
    }
    
    public function get() {
        $tp = (object) $this->t;
        return $tp;
    }
}

class TBootstrapGroupCheckButton extends TBootstrapCommon implements iBootstrapGroupCheckButton {
    private $items = null;
    
    public function __construct($orientation = "btn-group-vertical", $formattButtons = true) {
        parent::__construct("div");
        
        if (!empty($orientation)){
            $this->class = $orientation;
        }
        
        if ($formattButtons == (bool) true){
            $this->addAttr("data-toggle", "buttons");
        }
    }
    
    public function addItem($value, $text, $styleButton = "btn-default", $checked = false) {
        if ($checked == (bool) true){
            $this->items[] = "<label class='btn {$styleButton}'><input type='checkbox' autocomplete='off' value='{$value}' checked> {$text}</label>";
        }
        else {
            $this->items[] = "<label class='btn {$styleButton}'><input type='checkbox' autocomplete='off' value='{$value}'> {$text}</label>";
        }
    }
    
    public function show() {
        try {
            $p  = $this->prepare();
            if ($this->items){
                foreach ($this->items as $item) {
                    if (is_object($item)){
                        $p .= $item->show();
                    }                
                    else if ((is_string($item)) or (is_numeric($item))){
                        $p .= $item;
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
