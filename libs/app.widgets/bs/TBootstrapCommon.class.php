<?php

/**
 * Description of TBootstrapCommon
 *
 * @author hudsonmartins
 * @version 0.0.1
 */
class TBootstrapCommon {
    private $tag    = null;
    private $attr   = null;
    protected $node = null;
    
    public function __construct($tag) {
        $this->tag = $tag;
    }
    
    public function __set($name, $value) {
        $this->attr[$name] = $value;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    protected function addAttr($name, $value) {
        $this->attr[$name] = $value;
    }


    protected function addNode($child) {
        $this->node[] = $child;
    }
    
    protected function getId(){
        return $this->id;
    }
    
    protected function prepare() {
        if (empty($this->tag)){
            throw new Exception("O componente não foi definido corretamente.");
        }
        else{
            $p = "<{$this->tag}";
            if ($this->attr){
                foreach ($this->attr as $key => $value) {
                    $p .= ' ' . $key . '="' . $value . '"';
                }        
            }
            $p .= '>';
        }
        return $p;
    }
    
    protected function close() {
        return "</{$this->tag}>\n";
    }
    
    protected function setAttr($attr, $value){
        try{
            $this->__set($attr, $value);
        }
        catch (Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}
