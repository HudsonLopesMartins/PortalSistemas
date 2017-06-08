<?php

/**
 * Description of TFormDetalhesUsuario
 *
 * @author hudsonmartins
 */

include_once '../include/config.inc.php';
include_once '../include/message.inc.php';

include_once '../libs/adodb5/adodb-exceptions.inc.php';
include_once '../libs/adodb5/adodb-active-record.inc.php';
require_once '../libs/adodb5/adodb.inc.php';

include_once '../class/Usuario.class.php';

include_once '../libs/app.widgets/TPage.class.php';
include_once '../libs/app.widgets/bs/TBootstrapCommon.class.php';
include_once '../libs/app.widgets/bs/TBootstrapEdit.class.php';
include_once '../libs/app.widgets/bs/TBootstrapPanel.class.php';
include_once '../libs/app.widgets/bs/TBootstrapGrid.class.php';
include_once '../libs/app.widgets/bs/TBootstrapGridCell.class.php';
include_once '../libs/app.widgets/bs/TBootstrapButton.class.php';
include_once '../libs/app.widgets/bs/TBootstrapPanel.class.php';
include_once '../libs/app.widgets/bs/TBootstrapPanelFooter.class.php';
include_once '../libs/app.widgets/bs/TBootstrapAlerts.class.php';


class TFormDetalhesUsuario extends TPage implements iPage {
    public function prepare($dados) {
        $this->loadScripts();
        $ttEdit          = new TTypeEdit();
        
        $edtEmail        = new TBootstrapEdit("edtEmail", $ttEdit->get()->email, "", "form-control");
        $edtRamal        = new TBootstrapEdit("edtRamal", $ttEdit->get()->text, "", "form-control");
        
        $edtNome         = new TBootstrapEdit("edtNome", $ttEdit->get()->text, "", "form-control");
        $edtCPF          = new TBootstrapEdit("edtCPF", $ttEdit->get()->text, "", "form-control");
        
        $edtEndereco     = new TBootstrapEdit("edtEndereco", $ttEdit->get()->text, "", "form-control");
        $edtBairro       = new TBootstrapEdit("edtBairro", $ttEdit->get()->text, "", "form-control");
        $edtNumero       = new TBootstrapEdit("edtNumero", $ttEdit->get()->text, "", "form-control");
        $edtComplemento  = new TBootstrapEdit("edtComplemento", $ttEdit->get()->text, "", "form-control");
        $edtCEP          = new TBootstrapEdit("edtCEP", $ttEdit->get()->text, "", "form-control");

        $edtCidade       = new TBootstrapEdit("edtCidade", $ttEdit->get()->text, "", "form-control");
        $edtUF           = new TBootstrapEdit("edtUF", $ttEdit->get()->text, "", "form-control");
        
        $edtEmailPessoal = new TBootstrapEdit("edtEmailPessoal", $ttEdit->get()->email, "", "form-control");
        
        $edtRamal->setPlaceholder("Ramal");
        $edtEmail->setPlaceholder("Email");
        
        $edtNome->setPlaceholder("Nome Completo");
        $edtCPF->setPlaceholder("CPF");
        
        $edtEndereco->setPlaceholder("Endereço");
        $edtNumero->setPlaceholder("Número");
        
        $edtBairro->setPlaceholder("Bairro");
        $edtComplemento->setPlaceholder("Complemento");
        $edtCEP->setPlaceholder("CEP");
        
        $edtCidade->setPlaceholder("Cidade");
        $edtUF->setPlaceholder("Estado");
        
        $edtEmailPessoal->setPlaceholder("Email Pessoal");
        
        $u = (object) (new Usuario)->viewDetalhesUsuario($dados, false);
        if (!isset($u->r["COD"])){
            $edtRamal->value        = $u->r[0]["ramal"];
            $edtEmail->value        = $u->r[0]["email_usuario"];
            $edtNome->value         = $u->r[0]["nome_usuario"];
            $edtCPF->value          = $u->r[0]["cpf"];
            $edtEndereco->value     = utf8_encode($u->r[0]["endereco"]);
            $edtNumero->value       = $u->r[0]["numero"];
            $edtBairro->value       = $u->r[0]["bairro"];
            $edtComplemento->value  = $u->r[0]["complemento"];
            $edtCEP->value          = $u->r[0]["cep"];
            $edtCidade->value       = $u->r[0]["nome_cidade"];
            $edtUF->value           = $u->r[0]["sigla"];
            $edtEmailPessoal->value = $u->r[0]["email_pessoal"];


            $itemEmail = new TBootstrapGridCell();
            $itemEmail->setWidth("col-md-9");
            $itemEmail->addItem($edtEmail->show() . "<br>");

            $itemRamal = new TBootstrapGridCell();
            $itemRamal->setWidth("col-md-3");
            $itemRamal->addItem($edtRamal->show() . "<br>");

            $itemNome = new TBootstrapGridCell();
            $itemNome->setWidth("col-md-12");
            $itemNome->addItem($edtNome->show() . "<br>");

            $itemCPF = new TBootstrapGridCell();
            $itemCPF->setWidth("col-md-12");
            $itemCPF->addItem($edtCPF->show() . "<br>");

            $itemEndereco = new TBootstrapGridCell();
            $itemEndereco->setWidth("col-md-9");
            $itemEndereco->addItem($edtEndereco->show() . "<br>");

            $itemNumero = new TBootstrapGridCell();
            $itemNumero->setWidth("col-md-3");
            $itemNumero->addItem($edtNumero->show() . "<br>");

            $itemBairro = new TBootstrapGridCell();
            $itemBairro->setWidth("col-md-12");
            $itemBairro->addItem($edtBairro->show() . "<br>");

            $itemComplemento = new TBootstrapGridCell();
            $itemComplemento->setWidth("col-md-8");
            $itemComplemento->addItem($edtComplemento->show() . "<br>");

            $itemCep = new TBootstrapGridCell();
            $itemCep->setWidth("col-md-4");
            $itemCep->addItem($edtCEP->show() . "<br>");

            /*
            $itemCEP = new TBootstrapGridCell();
            $itemCEP->setWidth("col-md-12");
            $itemCEP->addItem("<div class='input-group'>"
                            . "<input type='text' class='form-control' id='edtCEP' placeholder='00.000-000' maxlength='10'>"
                            . "<span class='input-group-btn'>"
                            . "  <button type='button' class='btn btn-default' id='btnConsultarCEP'>Consultar</button>"
                            . "</span>"
                            . "</div><br>");
            */

            $itemCidade = new TBootstrapGridCell();
            $itemCidade->setWidth("col-md-10");
            $itemCidade->addItem($edtCidade->show() . "<br>");

            $itemUf = new TBootstrapGridCell();
            $itemUf->setWidth("col-md-2");
            $itemUf->addItem($edtUF->show() . "<br>");

            $itemEmailPessoal = new TBootstrapGridCell();
            $itemEmailPessoal->setWidth("col-md-12");
            $itemEmailPessoal->addItem($edtEmailPessoal->show() . "<br>");
            
            $formDetalhes = new TBootstrapGrid();
            $formDetalhes->addItem($itemEmail);
            $formDetalhes->addItem($itemRamal);
            $formDetalhes->addItem($itemNome);
            $formDetalhes->addItem($itemCPF);
            $formDetalhes->addItem($itemEndereco);
            $formDetalhes->addItem($itemNumero);
            $formDetalhes->addItem($itemBairro);
            $formDetalhes->addItem($itemComplemento);
            $formDetalhes->addItem($itemCep);
            $formDetalhes->addItem($itemCidade);
            $formDetalhes->addItem($itemUf);
            $formDetalhes->addItem($itemEmailPessoal);

            $panelDetalhes = new TBootstrapPanel();
            $panelDetalhes->setTitle("Detalhes do Usuário", TRUE);
            $panelDetalhes->addItem($formDetalhes);
            $panelDetalhes->addItem("<input type='hidden' id='hdIDEmp' value='{$u->r[0]["id_empresa"]}'>");
            $panelDetalhes->addItem("<input type='hidden' id='hdIDGrp' value='{$u->r[0]["id_grupo"]}'>");
            $panelDetalhes->addItem("<input type='hidden' id='hdIDUsr' value='{$u->r[0]["id_usuario"]}'>");
            $panelDetalhes->addItem("<input type='hidden' id='hdIDDUs' value='{$u->r[0]["id_dadousuario"]}'>");
            $panelDetalhes->addItem("<input type='hidden' id='hdIDEnd' value='{$u->r[0]["id_endereco"]}'>");
            $panelDetalhes->addItem("<input type='hidden' id='hdIDCid' value='{$u->r[0]["id_cidade"]}'>");
            $panelDetalhes->addItem("<input type='hidden' id='hdIDEst' value='{$u->r[0]["id_uf"]}'>");
            $panelDetalhes->addItem("<input type='hidden' id='hdLat' value='{$u->r[0]["lat"]}'>");
            $panelDetalhes->addItem("<input type='hidden' id='hdLng' value='{$u->r[0]["lng"]}'>");

            $btnSalvar = new TBootstrapButton("btnSalvar", "Salvar Alterações", "btn-primary");
            $btnFechar = new TBootstrapButton("btnFechar", "Fechar");

            $btnSalvar->addImage("glyphicon-floppy-save");
            $btnFechar->addImage("glyphicon-off");

            $ftPanelDetalhes = new TBootstrapPanelFooter();
            $ftPanelDetalhes->addItem($btnSalvar);
            $ftPanelDetalhes->addItem($btnFechar);

            $panelDetalhes->setFooter($ftPanelDetalhes);
            
            $this->addItem($panelDetalhes->show());
        }
        else {
            $itemAviso = new TBootstrapGridCell();
            $itemAviso->setWidth("col-md-12");
            $itemAviso->addItem("<div class='row'><div class='col-md-12'>" . 
                                TBootStrapAlerts::show("<p><strong>Aviso!</strong>&nbsp;Este usuário não possui detalhes pois o mesmo é um administrador.</p>", 
                                "warning", "<h4>Atenção</h4>") .
                                "</div></div>");
            
            $formDetalhes = new TBootstrapGrid();
            $formDetalhes->addItem($itemAviso);

            $panelDetalhes = new TBootstrapPanel();
            $panelDetalhes->setTitle("Detalhes do Usuário", TRUE);
            $panelDetalhes->addItem($formDetalhes);
            
            $btnSalvar = new TBootstrapButton("btnSalvar", "Salvar Alterações", "btn-primary");
            $btnFechar = new TBootstrapButton("btnFechar", "Fechar");

            $btnSalvar->addImage("glyphicon-floppy-save");
            $btnFechar->addImage("glyphicon-off");

            $ftPanelDetalhes = new TBootstrapPanelFooter();
            $ftPanelDetalhes->addItem($btnSalvar);
            $ftPanelDetalhes->addItem($btnFechar);

            $panelDetalhes->setFooter($ftPanelDetalhes);
            
            $this->addItem($panelDetalhes->show());
        }
    }
}
