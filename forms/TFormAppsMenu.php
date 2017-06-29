<?php

/**
 * Description of TFormAppsMenu
 *
 * @author hudsonmartins
 */

//include_once './include/config.inc.php';
include_once './include/message.inc.php';

//include_once './libs/adodb5/adodb-exceptions.inc.php';
require_once './libs/adodb5/adodb.inc.php';

//include_once './class/Plano.class.php';
include_once './class/Sistemas.class.php';

include_once './libs/app.widgets/TPage.class.php';
include_once './libs/app.widgets/bs/TBootstrapCommon.class.php';
include_once './libs/app.widgets/bs/TBootstrapPanel.class.php';
include_once './libs/app.widgets/bs/TBootstrapGrid.class.php';
include_once './libs/app.widgets/bs/TBootstrapGridCell.class.php';

class TFormAppsMenu extends TPage implements iPage {
    public function prepare($dados) {
        $this->loadScripts();
       
        $s = (object) (new Sistemas)->findAppByEmp($dados, false);
        $lstMenu = "<div class='list-group'>";
        for($i = 0; $i < count($s->r); $i++){
            $lstMenu .= "<a href='#' class='list-group-item openapp' appid='" . $s->r[$i]["id"] . "' appidemp='" . $s->r[$i]["id_empresa"] . "'>" . $s->r[$i]["sistema"] . "</a>";
        }
        $lstMenu .= "</div>";
        
        $itemSistemasAssinatura = new TBootstrapGridCell();
        $itemSistemasAssinatura->setWidth("col-md-12");
        $itemSistemasAssinatura->addItem($lstMenu);
        
        
        $formEscolhaAssinatura = new TBootstrapGrid();
        $formEscolhaAssinatura->addItem($itemSistemasAssinatura);
        
        $panelPrincipal = new TBootstrapPanel();
        $panelPrincipal->setTitle("Aplicativos", TRUE);
        $panelPrincipal->addItem($formEscolhaAssinatura);
        
        $areaMenu = new TBootstrapGridCell();
        $areaMenu->setWidth("col-md-6 ");
        $areaMenu->setPos("col-md-offset-3");
        $areaMenu->addItem($panelPrincipal);
        
        $areaPrincipal = new TBootstrapGrid();
        $areaPrincipal->addItem($areaMenu);
        
        $this->addItem($areaPrincipal->show());
    }
}
