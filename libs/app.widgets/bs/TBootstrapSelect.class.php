<?php
/**
 * Description of TBootstrapSelect
 *
 * @author hudsonmartins
 */

interface iBootstrapSelect {
    public function addItem($value, $text, $selected = false);
    public function show();
}

class TBootstrapSelect extends TBootstrapCommon implements iBootstrapSelect {
    private $option = null;
    
    public function __construct($id, $multiple = false, $class = "") {
        parent::__construct("select");
        
        $this->id   = $id;
        $this->name = $id;
        
        if ($multiple == (bool) true){
            $this->multiple = "multiple";
        }
        
        if (!empty($class)){
            //$this->class = "form-control";
            $this->class = $class;
        }
    }
    
    public function addItem($value, $text, $selected = false) {
        if ($selected == (bool) true){
            $this->option[] = "<option value='{$value}' selected>{$text}</option>";
        }
        else {
            $this->option[] = "<option value='{$value}'>{$text}</option>";
        }
    }
    
    public function show() {
        try {
            $p = $this->prepare();
            if ($this->option){
                foreach ($this->option as $item) {
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
