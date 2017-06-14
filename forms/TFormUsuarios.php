<?php

/**
 * Description of TFormsUsuarios
 *
 * @author hudsonmartins
 */

include_once '../libs/interface/iFormsPage.inc.php';

include_once '../libs/app.widgets/TPage.class.php';
include_once '../libs/app.widgets/bs/TBootstrapCommon.class.php';
include_once '../libs/app.widgets/bs/TBootstrapPanel.class.php';
include_once '../libs/app.widgets/bs/TBootstrapGrid.class.php';
include_once '../libs/app.widgets/bs/TBootstrapGridCell.class.php';
include_once '../libs/app.widgets/bs/TBootstrapButton.class.php';

class TFormUsuarios extends TPage implements iPage {
    public function prepare($dados) {
        $this->loadScripts();
       
        
        $btnUsersAtivos   = new TBootstrapButton("btnUsersAtivos", "Usuários Ativos");
        $btnUsersInativos = new TBootstrapButton("btnUsersInativos", "Usuários Inátivos");
        $btnFechar        = new TBootstrapButton("btnFechar", "Fechar", "btn-primary");
        
        $itemBtnUsuarios = new TBootstrapGridCell();
        $itemBtnUsuarios->addItem("&nbsp;");
        $itemBtnUsuarios->addItem($btnUsersAtivos);
        $itemBtnUsuarios->addItem($btnUsersInativos);
        $itemBtnUsuarios->addItem("&nbsp;&nbsp;|&nbsp;&nbsp;");
        $itemBtnUsuarios->addItem($btnFechar);
        $itemBtnUsuarios->addItem("<br><br>");
        $itemBtnUsuarios->addItem("<input type='hidden' id='hdIDe' value='{$dados["d"]["empresa"][0]["id"]}'>");
        $itemBtnUsuarios->addItem("<input type='hidden' id='hdIDu' value='{$dados["d"]["usuario"][0]["id"]}'>");
        
        
        $itemGridUsuarios = new TBootstrapGridCell();
        $itemGridUsuarios->setWidth("col-md-12");
        $itemGridUsuarios->addItem("<table class='table table-striped' id='grdUsuarios'>
                                        <thead>
                                            <tr>
                                                <th>nome</th>
                                                <th>email</th>
                                                <th>grupo</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody id='grdListaUsuarios'></tbody>
                                    </table>");
        
        
        $formUsuarios = new TBootstrapGrid();
        $formUsuarios->addItem($itemBtnUsuarios);
        $formUsuarios->addItem($itemGridUsuarios);
        
        $panelPrincipal = new TBootstrapPanel();
        $panelPrincipal->setTitle("Usuários", TRUE);
        $panelPrincipal->addItem($formUsuarios);
        
        $areaUsuarios = new TBootstrapGridCell();
        $areaUsuarios->setWidth("col-md-10 ");
        $areaUsuarios->setPos("col-md-offset-1");
        $areaUsuarios->addItem($panelPrincipal);
        
        $areaPrincipal = new TBootstrapGrid();
        $areaPrincipal->addItem($areaUsuarios);
        
        $this->addItem($areaPrincipal->show());
    }
}
