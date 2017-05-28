<?php

/**
 * Description of TFormPeriodoAssinatura
 *
 * @author hudsonmartins
 */
include_once '../include/config.inc.php';
include_once '../include/message.inc.php';

include_once '../libs/adodb5/adodb-exceptions.inc.php';
require_once '../libs/adodb5/adodb.inc.php';

include_once '../class/Plano.class.php';
include_once '../class/Sistemas.class.php';
    
include_once '../libs/interface/iFormsPage.inc.php';

include_once '../libs/app.widgets/TPage.class.php';
include_once '../libs/app.widgets/bs/TBootstrapCommon.class.php';
include_once '../libs/app.widgets/bs/TBootstrapEdit.class.php';
include_once '../libs/app.widgets/bs/TBootstrapPanel.class.php';
include_once '../libs/app.widgets/bs/TBootstrapPanelFooter.class.php';
include_once '../libs/app.widgets/bs/TBootstrapGrid.class.php';
include_once '../libs/app.widgets/bs/TBootstrapGridCell.class.php';
include_once '../libs/app.widgets/bs/TBootstrapButton.class.php';
include_once '../libs/app.widgets/bs/TBootstrapSelect.class.php';


class TFormPeriodoAssinatura extends TPage implements iPage{
    public function prepare(){
        $this->loadScripts();
        
        $ddlPeriodoAssinatura = new TBootstrapSelect("ddlPeriodoAssinatura", false, "form-control");
        
        $ddlPeriodoAssinatura->addItem("0", "Período da Assinatura");
        $p = (object) (new Plano)->findAll(false);
        for($i = 0; $i < count($p->r); $i++){
            $ddlPeriodoAssinatura->addItem($p->r[$i]["id"], $p->r[$i]["descricao"]);
        }
       
        $s = (object) (new Sistemas)->findAll(false);
        $lstCheck = "<ul class='list-group'>";
        for($i = 0; $i < count($s->r); $i++){
            $lstCheck .= "<li class='list-group-item'><label><input type='checkbox' value='" . $s->r[$i]["id"] . "' valuemonthprod='" . $s->r[$i]["valorassinatura"] . "'>&nbsp;&nbsp;&nbsp;" . $s->r[$i]["nome"] . "</label></li>";
        }
        $lstCheck .= "</ul>";
        
        $itemPeriodoAssinatura = new TBootstrapGridCell();
        $itemPeriodoAssinatura->setWidth("col-md-12");
        $itemPeriodoAssinatura->addItem($ddlPeriodoAssinatura->show() . "<br>");
        
        $itemSistemasAssinatura = new TBootstrapGridCell();
        $itemSistemasAssinatura->setWidth("col-md-12");
        $itemSistemasAssinatura->addItem($lstCheck);
        
        
        $formEscolhaAssinatura = new TBootstrapGrid();
        $formEscolhaAssinatura->addItem($itemPeriodoAssinatura);
        $formEscolhaAssinatura->addItem($itemSistemasAssinatura);
        
        $panelPrincipal = new TBootstrapPanel();
        $panelPrincipal->setTitle("Escolha do(s) Sistema(s)", TRUE);
        $panelPrincipal->addItem($formEscolhaAssinatura);
        
        $btnConfirmar   = new TBootstrapButton("btnContinuar", "Continuar... configurar usuário", "btn-primary");
        $btnCancelar    = new TBootstrapButton("btnCancelar", "Cancelar");
        
        $btnConfirmar->addImage("glyphicon-ok");
        $btnCancelar->addImage("glyphicon-remove");
        
        $ftPanelPrincipal = new TBootstrapPanelFooter();
        $ftPanelPrincipal->addItem($btnConfirmar);
        $ftPanelPrincipal->addItem($btnCancelar);
        
        $panelPrincipal->setFooter($ftPanelPrincipal);
        
        $areaCad = new TBootstrapGridCell();
        $areaCad->setWidth("col-md-6 ");
        $areaCad->setPos("col-md-offset-3");
        $areaCad->addItem($panelPrincipal);
        $areaCad->addItem("<br>"
                        . "<div class='well'>"
                        . " <div class='row'>"
                        . "     <div class='col-md-8'><b>Total mensal a ser pago:</b></div>"
                        . "     <div class='col-md-4'><p id='pValorTotal' class='text-right' style='font-size: 20px; font-weight: bold; color: rgb(198,0,0);'>R$ 0.00</p></div>" 
                        . " </div>"
                        . "</div>");
        
        $areaPrincipal = new TBootstrapGrid();
        $areaPrincipal->addItem($areaCad);
        
        //$this->addItem("<br><br><br>");
        $this->addItem($areaPrincipal->show());

    }
}
