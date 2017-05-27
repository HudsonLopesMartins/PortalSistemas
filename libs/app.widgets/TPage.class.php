<?php

interface iPage {
   public function addFileJS($file);
   public function addJS($script);
   //protected function loadScripts();
   public function open();
}

class TPage implements iPage{
    private $p   = "";
    private $sJS = "";
    private $fJS = "";
    private $fCS = "";

    public function __construct() {
    }

    public function addFileJS($file) {
            $this->fJS[] = "<script src='{$file}' type='text/javascript'></script>";
    }

    public function addJS($script){
            $this->sJS = "<script type='text/javascript'>{$script}</script>";
    }
    
    public function addFileCSS($file) {
            $this->fCS[] = "<link href='{$file}' rel='stylesheet'></script>";
    }

    protected function addItem($item){
            $this->p .= $item;
    }

    protected function loadScripts() {
        if ($this->fCS){
            foreach ($this->fCS as $key => $value) {
                $this->addItem($value);
            }
        }
        
        if ($this->fJS){
            foreach ($this->fJS as $key => $value) {
                $this->addItem($value);
            }
        }

        if (!empty($this->sJS)){
            $this->addItem($this->sJS);
        }
    }

    public function open(){
            echo $this->p;
    }
}