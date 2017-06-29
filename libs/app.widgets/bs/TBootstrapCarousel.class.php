<?php

/**
 * Description of TBootstrapCarousel
 *
 * @author hudsonmartins
 */

interface iBootstrapCarousel {
    public function addPageItem(TBootstrapPanel $item, $active = false);
    public function show();
}

class TBootstrapCarousel extends TBootstrapCommon implements iBootstrapCarousel {
    private $data_ride      = "carousel";
    private $data_interval  = "false";
    private $itemPageActive;
    
    public function __construct($id, $ride = "carousel", $interval = "false") {
        parent::__construct("div");
        $this->id    = $id;
        $this->class = "carousel";
        
        $this->addAttr("data_ride", $ride);
        $this->addAttr("data_interval", $interval);
    }
    
    public function addPageItem(TBootstrapPanel $item, $active = false) {
        parent::addNode($item);
        $this->itemPageActive[] = $active;
    }

    public function addItem($item, $active = false) {
        parent::addNode($item);
        $this->itemPageActive[] = $active;
    }
    
    public function show() {
        try {
            $p        = $this->prepare();
            $p       .= "<div class='carousel-inner' role='listbox'>";
            $pageItem = "";
            
            if ($this->node){
                foreach ($this->node as $key => $nodekey) {
                    if ($this->itemPageActive[$key] == true){
                        $pageItem .= "<div class='item active'>";
                    }
                    else{
                        $pageItem .= "<div class='item'>";
                    }
                    
                    if (is_object($nodekey)){
                        $pageItem .= $nodekey->show();
                    }
                    else if ((is_string($nodekey)) or (is_numeric($nodekey))){
                        $pageItem .= $nodekey;
                    }
                    $pageItem .= "</div>";
                }
            }
            $p .= $pageItem;
            $p .= $this->close();
            return $p;
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
