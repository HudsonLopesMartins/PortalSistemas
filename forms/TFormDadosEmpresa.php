<?php


include_once './include/message.inc.php';
include_once './libs/adodb5/adodb-active-record.inc.php';
require_once './libs/adodb5/adodb.inc.php';

include_once './class/Empresa.class.php';
include_once './class/FoneEmpresa.class.php';

include_once './libs/app.widgets/TPage.class.php';
include_once './libs/app.widgets/bs/TBootstrapCommon.class.php';
include_once './libs/app.widgets/bs/TBootstrapEdit.class.php';
include_once './libs/app.widgets/bs/TBootstrapPanel.class.php';
include_once './libs/app.widgets/bs/TBootstrapPanelFooter.class.php';
include_once './libs/app.widgets/bs/TBootstrapGrid.class.php';
include_once './libs/app.widgets/bs/TBootstrapGridCell.class.php';
include_once './libs/app.widgets/bs/TBootstrapButton.class.php';
include_once './libs/app.widgets/bs/TBootstrapSelect.class.php';

/**
 * Description of TFormCadastroEmpresa
 *
 * @author hudsonmartins
 */
class TFormDadosEmpresa extends TPage implements iPage {
    public function prepare($dados) {
        $this->loadScripts();
        
        $ttEdit          = new TTypeEdit();

        $edtRazaoSocial  = new TBootstrapEdit("edtRazaoSocial", $ttEdit->get()->text, "", "form-control");
        $edtNomeFantasia = new TBootstrapEdit("edtNomeFantasia", $ttEdit->get()->text, "", "form-control");
        
        $ddlTipoPessoa   = new TBootstrapSelect("ddlTipoPessoa", false, "form-control");
        $edtCnpj         = new TBootstrapEdit("edtCnpj", $ttEdit->get()->text, "", "form-control");
        
        //$edtEndereco     = new TBootstrapEdit("edtEndereco", $ttEdit->get()->text, "", "form-control");
        $edtBairro       = new TBootstrapEdit("edtBairro", $ttEdit->get()->text, "", "form-control");
        $edtNumero       = new TBootstrapEdit("edtNumero", $ttEdit->get()->text, "", "form-control");
        $edtComplemento  = new TBootstrapEdit("edtComplemento", $ttEdit->get()->text, "", "form-control");

        $edtCidade       = new TBootstrapEdit("edtCidade", $ttEdit->get()->text, "", "form-control");
        $edtUF           = new TBootstrapEdit("edtUF", $ttEdit->get()->text, "", "form-control");
        
        $edtSite         = new TBootstrapEdit("edtSite", $ttEdit->get()->text, "", "form-control");
        $edtEmail        = new TBootstrapEdit("edtEmail", $ttEdit->get()->email, "", "form-control", "Email usado no Login");
        
        $edtRazaoSocial->setPlaceholder("Razão Social");
        $edtNomeFantasia->setPlaceholder("Nome Fantasia");
        $edtCnpj->setPlaceholder("CNPJ");
        
        $ddlTipoPessoa->addItem("N", "----");
        $ddlTipoPessoa->addItem("F", "Pessoa Física");
        $ddlTipoPessoa->addItem("J", "Pessoa Jurídica", true);
        
        //$edtEndereco->setPlaceholder("Endereço");
        $edtBairro->setPlaceholder("Bairro");
        $edtNumero->setPlaceholder("Numero");
        $edtComplemento->setPlaceholder("Complemento");
        $edtCidade->setPlaceholder("Cidade");
        $edtUF->setPlaceholder("Estado");
        
        $ddlTipoPessoa->disabled = "disabled";
        //$edtEndereco->disabled   = "disabled";
        $edtBairro->disabled     = "disabled";
        $edtCidade->disabled     = "disabled";
        $edtUF->disabled         = "disabled";
        $edtEmail->disabled      = "disabled";
        
        $edtSite->setPlaceholder("Página Eletrônica/Site");
        $edtEmail->setPlaceholder("Endereço Eletrônico/Email");
        
        /**
         * Carregar os dados da Empresa
         */
        $e = (object) (new Empresa)->findAllById($dados, false);
        $edtRazaoSocial->value  = $e->r[0]["razaosocial"];
        $edtNomeFantasia->value = $e->r[0]["nomefantasia"];
        
        $edtCnpj->value         = $e->r[0]["cnpj"];
        
        //$edtEndereco->value     = $e->r[0]["endereco"];
        $edtBairro->value       = $e->r[0]["bairro"];
        $edtNumero->value       = $e->r[0]["numero"];
        $edtComplemento->value  = $e->r[0]["complemento"];

        $edtCidade->value       = $e->r[0]["cidade"];
        $edtUF->value           = $e->r[0]["uf"];
        
        $edtSite->value         = $e->r[0]["site"];
        $edtEmail->value        = $e->r[0]["email"];
        //
        
        $itemRazaoSocial = new TBootstrapGridCell();
        $itemRazaoSocial->setWidth("col-md-12");
        $itemRazaoSocial->addItem($edtRazaoSocial->show() . "<br>");
        
        $itemNomeFantasia = new TBootstrapGridCell();
        $itemNomeFantasia->setWidth("col-md-12");
        $itemNomeFantasia->addItem($edtNomeFantasia->show() . "<br>");
        
        $itemTipo = new TBootstrapGridCell();
        $itemTipo->setWidth("col-md-4");
        $itemTipo->addItem($ddlTipoPessoa->show() . "<br>");
        
        $itemCnpj = new TBootstrapGridCell();
        $itemCnpj->setWidth("col-md-8");
        $itemCnpj->addItem($edtCnpj->show() . "<br>");

        $itemEndereco = new TBootstrapGridCell();
        $itemEndereco->setWidth("col-md-12");
        //$itemEndereco->addItem($edtEndereco->show() . "<br>");
        $itemEndereco->addItem("<div class='input-group'>"
                             . "<input type='text' class='form-control' id='edtEndereco' placeholder='Endereço' value='{$e->r[0]["endereco"]}' disabled>"
                             . "<span class='input-group-btn'>"
                             . "  <button type='button' class='btn btn-default' id='btnLocalizarMapa'>"
                             . "    <i class='fa fa-map-o' aria-hidden='true'></i> Consultar"
                             . "  </button>"
                             . "</span>"
                             . "</div><br>");
        
        $itemBairro = new TBootstrapGridCell();
        $itemBairro->setWidth("col-md-8");
        $itemBairro->addItem($edtBairro->show() . "<br>");
        
        $itemNumero = new TBootstrapGridCell();
        $itemNumero->setWidth("col-md-4");
        $itemNumero->addItem($edtNumero->show() . "<br>");
        
        $itemCEP = new TBootstrapGridCell();
        $itemCEP->setWidth("col-md-12");
        $itemCEP->addItem("<div class='input-group'>"
                        . "<input type='text' class='form-control' id='edtCEP' placeholder='00.000-000' maxlength='10' value='{$e->r[0]["cep"]}'>"
                        . "<span class='input-group-btn'>"
                        . "  <button type='button' class='btn btn-default' id='btnConsultarCEP'>"
                        . "    <i class='fa fa-search' aria-hidden='true'></i> Consultar"
                        . "  </button>"
                        . "</span>"
                        . "</div><br>");
        
        $itemComplemento = new TBootstrapGridCell();
        $itemComplemento->setWidth("col-md-12");
        $itemComplemento->addItem($edtComplemento->show() . "<br>");

        $itemCidade = new TBootstrapGridCell();
        $itemCidade->setWidth("col-md-9");
        $itemCidade->addItem($edtCidade->show() . "<br>");
        
        $itemUF = new TBootstrapGridCell();
        $itemUF->setWidth("col-md-3");
        $itemUF->addItem($edtUF->show() . "<br>");
        
        $itemSite = new TBootstrapGridCell();
        $itemSite->setWidth("col-md-12");
        $itemSite->addItem($edtSite->show() . "<br>");
        
        $itemEmail = new TBootstrapGridCell();
        $itemEmail->setWidth("col-md-12");
        $itemEmail->addItem($edtEmail->show() . "<br>");
        
        $formCad = new TBootstrapGrid();
        $formCad->addItem($itemRazaoSocial);
        $formCad->addItem($itemNomeFantasia);
        $formCad->addItem($itemTipo);
        $formCad->addItem($itemCnpj);
        $formCad->addItem($itemCEP);
        
        $formCad->addItem($itemEndereco);
        $formCad->addItem($itemNumero);
        $formCad->addItem($itemBairro);
        $formCad->addItem($itemCidade);
        $formCad->addItem($itemUF);
        $formCad->addItem($itemComplemento);
        $formCad->addItem($itemSite);
        $formCad->addItem($itemEmail);
        
        $panelPrincipal = new TBootstrapPanel();
        $panelPrincipal->setTitle("Dados Cadastrais", TRUE);
        $panelPrincipal->addItem($formCad);
        $panelPrincipal->addItem("<input type='hidden' id='hdIde' value='{$e->r[0]["id_empresa"]}'>");
        $panelPrincipal->addItem("<input type='hidden' id='hdIBGE' value='{$e->r[0]["ibge"]}'>");
        
        $btnConfirmar   = new TBootstrapButton("btnConfirmar", "Salvar", "btn-primary");
        $btnCancelar    = new TBootstrapButton("btnFechar", "Fechar", "btn-warning");
        
        $btnConfirmar->addImage("glyphicon-ok");
        $btnCancelar->addImage("glyphicon-remove");
        
        $ftPanelPrincipal = new TBootstrapPanelFooter();
        $ftPanelPrincipal->addItem($btnConfirmar);
        $ftPanelPrincipal->addItem($btnCancelar);
        
        $panelPrincipal->setFooter($ftPanelPrincipal);
        
        $areaCad = new TBootstrapGridCell();
        $areaCad->setWidth("col-md-6");
        $areaCad->addItem($panelPrincipal);
        
        $areaMapa = new TBootstrapGridCell();
        $areaMapa->setWidth("col-md-6");
        
        $panelMapa = new TBootstrapPanel();
        $panelMapa->setId("pnlMap");
        $panelMapa->setTitle("Localização", TRUE);
        $panelMapa->addItem("<div id='gmap' class='col-md-12'></div>");
        $panelMapa->addItem("<input type='hidden' id='hdLat' value='{$e->r[0]["lat"]}'>");
        $panelMapa->addItem("<input type='hidden' id='hdLng' value='{$e->r[0]["lng"]}'>");
        
        /*
        $btnRetirarMarcador = new TBootstrapButton("btnRetirarMarcador", "Limpar os Marcadores", "btn-warning");
        $ftPanelMapa = new TBootstrapPanelFooter();
        $ftPanelMapa->addItem($btnRetirarMarcador);
        $panelMapa->setFooter($ftPanelMapa);
         * 
         */
        
        
        $ddlTipoContato  = new TBootstrapSelect("ddlTipoContato", false, "form-control");
        $edtFone         = new TBootstrapEdit("edtFone", $ttEdit->get()->text, "", "form-control");
        
        $edtFone->setPlaceholder("Informe o Telefone");
        
        $ddlTipoContato->addItem("0000", "Telefone");
        $ddlTipoContato->addItem("RSDL", "Residêncial");
        $ddlTipoContato->addItem("CMCL", "Comercial");
        $ddlTipoContato->addItem("CELL", "Celular");
        $ddlTipoContato->addItem("FAXC", "Fax Comercial");
        $ddlTipoContato->addItem("FAXR", "Fax Residêncial");

        $itemTipoContato = new TBootstrapGridCell();
        $itemTipoContato->setWidth("col-md-4");
        $itemTipoContato->addItem($ddlTipoContato->show() . "<br>");
        
        $itemFone = new TBootstrapGridCell();
        $itemFone->setWidth("col-md-5");
        $itemFone->addItem($edtFone->show() . "<br>");
        
        $itemFonePrincipal = new TBootstrapGridCell();
        $itemFonePrincipal->setWidth("col-md-3");
        $itemFonePrincipal->addItem("<div class='checkbox'>"
                                  . "  <label>"
                                  . "    <input type='checkbox' id='chkFonePrincipal' value='1'>Marcador"
                                  . "  </label>"
                                  . "</div><br>");
        
        $f = (object) (new FoneEmpresa)->findAll($dados, false);
        
        $grdContatos = "<table class='table table-striped' id='grdContatos'>"
                     . "<thead>"
                     . "<tr>"
                     . "    <th>Tipo</th>"
                     . "    <th>Contato</th>"
                     . "    <th><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></th>"
                     . "    <th>Fone Principal</th>"
                     . "</tr>"
                     . "</thead>"
                     . "<tbody id='grdListaContato'>";
        $itmGridContato = "";
        for($i = 0; $i < count($f->r); $i++){
            $itmGridContato .= "<tr>"
                            .  "  <td>{$f->r[$i]["descricao"]}</td>"
                            .  "  <td>{$f->r[$i]["fone"]}</td>"
                    
                            .  "  <td>"
                            .  "    <input type='hidden' name='hddTipoContato[]' "
                            .  "                         id='hddTipoContato' "
                            .  "                         value='{$f->r[$i]["tipo"]}' "
                            .  "                         valuetext='{$f->r[$i]["fone"]}'"
                            .  "                         valuepr='{$f->r[$i]["principal"]}'>"
                            .  "    <input type='checkbox' name='chkFone[]' "
                            .  "                           id='chkFone' "
                            .  "                           valueid='{$f->r[$i]["id"]}'"
                            .  "                           valueitem='{$f->r[$i]["fone"]}'>"
                            .  "  </td>";
            if ($f->r[$i]["principal"] == 1){
                $itmGridContato .= "<td><span class='glyphicon glyphicon-tag' aria-hidden='true'></span></td>";
            }
            else {
                $itmGridContato .= "<td>&nbsp;</td>";
            }
            $itmGridContato .= "</tr>";
        }
        
        $grdContatos .= $itmGridContato . "</tbody></table>" . "<br>";
        
        $itemGrid = new TBootstrapGridCell();
        $itemGrid->setWidth("col-md-12");
        $itemGrid->addItem($grdContatos);
        
        $formCadContatos = new TBootstrapGrid();
        $formCadContatos->addItem($itemTipoContato);
        $formCadContatos->addItem($itemFone);
        $formCadContatos->addItem($itemFonePrincipal);
        $formCadContatos->addItem($itemGrid);
        
        $btnAddContatos    = new TBootstrapButton("btnAddContatos", "Adicionar Novo");
        $btnRemoveContatos = new TBootstrapButton("btnRemoveContatos", "Remover fone(s)");
        $btnRemoveTodos    = new TBootstrapButton("btnRemoveTodos", "Remover Todos");
        $btnSalvarFone     = new TBootstrapButton("btnSalvarFone", "Salvar", "btn-primary");
        $btnAddContatos->addImage("glyphicon-phone-alt");
        $btnRemoveContatos->addImage("glyphicon-trash");
        $btnRemoveTodos->addImage("glyphicon-asterisk");
        $btnSalvarFone->addImage("glyphicon-ok");
        
        $ftPanelContatos = new TBootstrapPanelFooter();
        $ftPanelContatos->addItem($btnAddContatos);
        $ftPanelContatos->addItem($btnRemoveContatos);
        $ftPanelContatos->addItem("&nbsp;|&nbsp;");
        $ftPanelContatos->addItem($btnRemoveTodos);
        $ftPanelContatos->addItem("&nbsp;|&nbsp;");
        $ftPanelContatos->addItem($btnSalvarFone);
        
        $panelContatos = new TBootstrapPanel();
        $panelContatos->setId("pnlContaos");
        $panelContatos->setTitle("Contatos", TRUE);
        $panelContatos->addItem($formCadContatos);
        $panelContatos->setFooter($ftPanelContatos);
        
        
        $areaMapa->addItem($panelContatos);
        $areaMapa->addItem($panelMapa);

        
        //$tabForms      = new TBootstrapCarousel("tabForms");
        //$tabForms->addPageItem($panelPrincipal, true);
        //$tabForms->addPageItem($panelMapa);
        
        $areaPrincipal = new TBootstrapGrid();
        $areaPrincipal->addItem($areaCad);
        $areaPrincipal->addItem($areaMapa);

        $this->addItem($areaPrincipal->show());
    }
}
