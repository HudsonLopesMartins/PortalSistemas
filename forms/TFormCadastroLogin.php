<?php

/**
 * Description of TFormCadastroLogin
 *
 * @author hudsonmartins
 */

include_once '../libs/interface/iFormsPage.inc.php';

include_once '../libs/app.widgets/TPage.class.php';
include_once '../libs/app.widgets/bs/TBootstrapCommon.class.php';
include_once '../libs/app.widgets/bs/TBootstrapEdit.class.php';
include_once '../libs/app.widgets/bs/TBootstrapPanel.class.php';
include_once '../libs/app.widgets/bs/TBootstrapPanelFooter.class.php';
include_once '../libs/app.widgets/bs/TBootstrapGrid.class.php';
include_once '../libs/app.widgets/bs/TBootstrapGridCell.class.php';
include_once '../libs/app.widgets/bs/TBootstrapButton.class.php';
//include_once '../libs/app.widgets/bs/TBootstrapForm.class.php';


class TFormCadastroLogin extends TPage implements iFormsPage{
    public function prepare() {
        $this->loadScripts();
        
        $ttEdit          = new TTypeEdit();
        $edtUsuarioLogin = new TBootstrapEdit("edtUsuarioLogin", $ttEdit->get()->text, "", "form-control");
        $edtPassLogin    = new TBootstrapEdit("edtPassLogin", $ttEdit->get()->password, "", "form-control");
        $edtConfrLogin   = new TBootstrapEdit("edtConfrLogin", $ttEdit->get()->password, "", "form-control");
        
        $edtUsuarioLogin->setPlaceholder("Usuário");
        $edtPassLogin->setPlaceholder("Informe a Senha");
        $edtConfrLogin->setPlaceholder("Redigite a Senha para Confirmar");
        
        $itemEdtUsuarioLogin = new TBootstrapGridCell();
        $itemEdtUsuarioLogin->setWidth("col-md-12");
        $itemEdtUsuarioLogin->addItem($edtUsuarioLogin->show() . "<br>");
        
        $itemEdtPassLogin = new TBootstrapGridCell();
        $itemEdtPassLogin->setWidth("col-md-12");
        $itemEdtPassLogin->addItem($edtPassLogin->show() . "<br>");
        
        $itemEdtConfrLogin = new TBootstrapGridCell();
        $itemEdtConfrLogin->setWidth("col-md-12");
        $itemEdtConfrLogin->addItem($edtConfrLogin->show() . "<br>");
        
        $formLogin = new TBootstrapGrid();
        $formLogin->addItem($itemEdtUsuarioLogin);
        $formLogin->addItem($itemEdtPassLogin);
        $formLogin->addItem($itemEdtConfrLogin);
        
        $panelLogin = new TBootstrapPanel();
        $panelLogin->setTitle("Cadastro do Login", TRUE);
        $panelLogin->addItem($formLogin);
        
        $btnFinalizar   = new TBootstrapButton("btnFinalizaCadLogin", "Finalizar", "btn-primary");
        $btnCancelar    = new TBootstrapButton("btnCancelarCadLogin", "Cancelar");
        
        $btnFinalizar->addImage("glyphicon-ok");
        $btnCancelar->addImage("glyphicon-remove");
        
        $ftPanelLogin = new TBootstrapPanelFooter();
        $ftPanelLogin->addItem($btnFinalizar);
        $ftPanelLogin->addItem($btnCancelar);
        
        $panelLogin->setFooter($ftPanelLogin);
        
        $areaCadLogin = new TBootstrapGridCell();
        $areaCadLogin->setWidth("col-md-6 col-md-offset-3");
        $areaCadLogin->addItem($panelLogin);
        
        //$this->addItem("<br><br><br>");
        $this->addItem($areaCadLogin->show());
    }
}
