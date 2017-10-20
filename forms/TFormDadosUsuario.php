<?php


include_once './include/message.inc.php';
include_once './libs/adodb5/adodb-active-record.inc.php';
require_once './libs/adodb5/adodb.inc.php';

include_once './class/Usuario.class.php';
include_once './class/FonesUsuario.class.php';

include_once './libs/app.widgets/TPage.class.php';
include_once './libs/app.widgets/bs/TBootstrapCommon.class.php';
include_once './libs/app.widgets/bs/TBootstrapEdit.class.php';
include_once './libs/app.widgets/bs/TBootstrapPanel.class.php';
include_once './libs/app.widgets/bs/TBootstrapPanelFooter.class.php';
include_once './libs/app.widgets/bs/TBootstrapGrid.class.php';
include_once './libs/app.widgets/bs/TBootstrapGridCell.class.php';
include_once './libs/app.widgets/bs/TBootstrapButton.class.php';
include_once './libs/app.widgets/bs/TBootstrapSelect.class.php';
include_once './libs/app.widgets/bs/TBootstrapCarousel.class.php';

/**
 * Description of TFormDadosUsuario
 *
 * @author hudsonmartins
 */
class TFormDadosUsuario extends TPage implements iPage {
    public function prepare($dados) {
        $this->loadScripts();
        
        $ttEdit          = new TTypeEdit();

        $edtGrupoAcesso  = new TBootstrapEdit("edtGrupoAcesso", $ttEdit->get()->text, "", "form-control");
        $edtEmail        = new TBootstrapEdit("edtEmail", $ttEdit->get()->email, "", "form-control");
        $edtRamal        = new TBootstrapEdit("edtRamal", $ttEdit->get()->text, "", "form-control");
        
        $edtNome         = new TBootstrapEdit("edtNome", $ttEdit->get()->text, "", "form-control");
        $edtCPF          = new TBootstrapEdit("edtCPF", $ttEdit->get()->text, "", "form-control");
        
        $edtBairro       = new TBootstrapEdit("edtBairro", $ttEdit->get()->text, "", "form-control");
        $edtNumero       = new TBootstrapEdit("edtNumero", $ttEdit->get()->text, "", "form-control");
        $edtComplemento  = new TBootstrapEdit("edtComplemento", $ttEdit->get()->text, "", "form-control");

        $edtCidade       = new TBootstrapEdit("edtCidade", $ttEdit->get()->text, "", "form-control");
        $edtUF           = new TBootstrapEdit("edtUF", $ttEdit->get()->text, "", "form-control");
        
        $edtEmailPessoal = new TBootstrapEdit("edtEmailPessoal", $ttEdit->get()->email, "", "form-control");
        
        $edtGrupoAcesso->setPlaceholder("Grupo de Acesso");
        $edtRamal->setPlaceholder("Ramal");
        $edtEmail->setPlaceholder("Email");
        
        $edtNome->setPlaceholder("Nome Completo");
        $edtCPF->setPlaceholder("CPF");
        
        $edtNumero->setPlaceholder("Número");
        
        $edtBairro->setPlaceholder("Bairro");
        $edtComplemento->setPlaceholder("Complemento");
        
        $edtCidade->setPlaceholder("Cidade");
        $edtUF->setPlaceholder("Estado");
        
        $edtEmailPessoal->setPlaceholder("Email Pessoal");
        
        $edtGrupoAcesso->disabled = "disabled";
        $edtEmail->disabled       = "disabled";
        $edtBairro->disabled      = "disabled";
        $edtCidade->disabled      = "disabled";
        $edtUF->disabled          = "disabled";
        
        /**
         * Carregar os dados da Empresa
         */
        $e = (object) (new Usuario)->viewDetalhesUsuario($dados, false);
        $edtGrupoAcesso->value  = $e->r[0]["grupo"];
        $edtEmail->value        = $e->r[0]["email_usuario"];
        $edtRamal->value        = $e->r[0]["ramal"];
        
        $edtNome->value         = $e->r[0]["nome_completo"];
        $edtCPF->value          = $e->r[0]["cpf"];
        
        $edtBairro->value       = $e->r[0]["bairro"];
        $edtNumero->value       = $e->r[0]["numero"];
        $edtComplemento->value  = $e->r[0]["complemento"];

        $edtCidade->value       = $e->r[0]["cidade"];
        $edtUF->value           = $e->r[0]["uf"];
        
        $edtEmailPessoal->value = $e->r[0]["email_pessoal"];
        //
        
        $itemGrupoAcesso = new TBootstrapGridCell();
        $itemGrupoAcesso->setWidth("col-md-12");
        $itemGrupoAcesso->addItem($edtGrupoAcesso->show() . "<br>");
        
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
        //$itemEndereco->addItem($edtEndereco->show() . "<br>");
        $itemEndereco->addItem("<div class='input-group'>"
                             . "<input type='text' class='form-control' id='edtEndereco' placeholder='Endereço' value='{$e->r[0]["endereco"]}' disabled>"
                             . "<span class='input-group-btn'>"
                             . "  <button type='button' class='btn btn-default' id='btnLocalizarMapa'>"
                             . "    <i class='fa fa-map-o' aria-hidden='true'></i> Consultar"
                             . "  </button>"
                             . "</span>"
                             . "</div><br>");

        $itemNumero = new TBootstrapGridCell();
        $itemNumero->setWidth("col-md-3");
        $itemNumero->addItem($edtNumero->show() . "<br>");

        $itemBairro = new TBootstrapGridCell();
        $itemBairro->setWidth("col-md-12");
        $itemBairro->addItem($edtBairro->show() . "<br>");

        $itemComplemento = new TBootstrapGridCell();
        $itemComplemento->setWidth("col-md-7");
        $itemComplemento->addItem($edtComplemento->show() . "<br>");

        $itemCep = new TBootstrapGridCell();
        $itemCep->setWidth("col-md-5");
        //$itemCep->addItem($edtCEP->show() . "<br>");
        $itemCep->addItem("<div class='input-group'>"
                . "<input type='text' class='form-control' id='edtCEP' placeholder='00.000-000' maxlength='10' value='{$e->r[0]["cep"]}'>"
                . "<span class='input-group-btn'>"
                . "  <button type='button' class='btn btn-default' id='btnConsultarCEP'>"
                . "    <i class='fa fa-search' aria-hidden='true'></i> Consultar"
                . "  </button>"
                . "</span>"
                . "</div><br>");

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
        $formDetalhes->addItem($itemGrupoAcesso);
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
        
        $panelPrincipal = new TBootstrapPanel();
        $panelPrincipal->setTitle("Dados Cadastrais do Usuário", TRUE);
        $panelPrincipal->addItem($formDetalhes);
        $panelPrincipal->addItem("<input type='hidden' id='hdIde' value='{$e->r[0]["id_empresa"]}'>");
        $panelPrincipal->addItem("<input type='hidden' id='hdIdg' value='{$e->r[0]["id_grupo"]}'>");
        $panelPrincipal->addItem("<input type='hidden' id='hdIdu' value='{$e->r[0]["id_usuario"]}'>");
        $panelPrincipal->addItem("<input type='hidden' id='hdIdDu' value='{$e->r[0]["id_dadousuario"]}'>");
        $panelPrincipal->addItem("<input type='hidden' id='hdIBGE' value='{$e->r[0]["ibge"]}'>");
        $panelPrincipal->addItem("<input type='hidden' id='hdLat' value='{$e->r[0]["lat"]}'>");
        $panelPrincipal->addItem("<input type='hidden' id='hdLng' value='{$e->r[0]["lng"]}'>");
        
        $btnConfirmar    = new TBootstrapButton("btnConfirmar", "Salvar", "btn-primary");
        $btnCancelar     = new TBootstrapButton("btnFechar", "Fechar", "btn-warning");
        $btnAlterarLogin = new TBootstrapButton("btnAlterarLogin", "Alterar Login");
        
        $btnConfirmar->addImage("glyphicon-ok");
        $btnCancelar->addImage("glyphicon-remove");
        $btnAlterarLogin->addImage("glyphicon-pencil");
        
        $ftPanelPrincipal = new TBootstrapPanelFooter();
        $ftPanelPrincipal->addItem($btnConfirmar);
        $ftPanelPrincipal->addItem($btnCancelar);
        $ftPanelPrincipal->addItem("&nbsp;|&nbsp;");
        $ftPanelPrincipal->addItem($btnAlterarLogin);
        
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
        
        $f = (object) (new FonesUsuario)->findAll($dados, false);
        
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
        
        if (!isset($f->r["COD"])){
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
                                .  "                           valueid='{$f->r[$i]["id_fone"]}'"
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

        $areaUsuario = new TBootstrapGrid();
        $areaUsuario->addItem($areaCad);
        $areaUsuario->addItem($areaMapa);
        
        
        /**
         * Form Edição de Senha
         */
        $itemLnHorizontal = new TBootstrapGridCell();
        $itemLnHorizontal->setWidth("col-md-12");
        $itemLnHorizontal->addItem("<hr style='border: 0; height: 0; "
                                 . "border-top: 1px solid rgba(0, 0, 0, 0.1); "
                                 . "border-bottom: 1px solid rgba(255, 255, 255, 0.3);'>");
        $edtEmailLogin  = new TBootstrapEdit("edtEmailLogin", $ttEdit->get()->email, "Email", "form-control");
        $edtSenhaAntiga = new TBootstrapEdit("edtSenhaAntiga", $ttEdit->get()->password, "Senha Antiga", "form-control");
        $edtNovaSenha   = new TBootstrapEdit("edtNovaSenha", $ttEdit->get()->password, "Nova Senha", "form-control");
        $edtCheckSenha  = new TBootstrapEdit("edtCheckSenha", $ttEdit->get()->password, "Confirme a Senha", "form-control");

        $edtEmailLogin->disabled = "disabled";
        $edtEmailLogin->value    = $e->r[0]["email_usuario"];
        
        $itemEmailLogin = new TBootstrapGridCell();
        $itemEmailLogin->setWidth("col-md-12");
        $itemEmailLogin->addItem($edtEmailLogin);
        
        $itemSenhaAntiga = new TBootstrapGridCell();
        $itemSenhaAntiga->setWidth("col-md-12");
        $itemSenhaAntiga->addItem($edtSenhaAntiga);
        
        $itemSenhaNova = new TBootstrapGridCell();
        $itemSenhaNova->setWidth("col-md-12");
        $itemSenhaNova->addItem($edtNovaSenha);
        
        $itemCheckSenha = new TBootstrapGridCell();
        $itemCheckSenha->setWidth("col-md-12");
        $itemCheckSenha->addItem($edtCheckSenha);
        
        $formEditaLogin = new TBootstrapGrid();
        $formEditaLogin->addItem($itemEmailLogin);
        $formEditaLogin->addItem($itemLnHorizontal);
        $formEditaLogin->addItem($itemSenhaAntiga);
        $formEditaLogin->addItem($itemSenhaNova);
        $formEditaLogin->addItem($itemCheckSenha);
        
        $panelLoginUsuario = new TBootstrapPanel();
        $panelLoginUsuario->setTitle("Dados Login", TRUE);
        $panelLoginUsuario->addItem($formEditaLogin);
        
        $btnSalvarLoginUsuario  = new TBootstrapButton("btnSalvarLogin", "Salvar Edição", "btn-primary");
        $btnFecharLoginUsuario  = new TBootstrapButton("btnFecharLoginUsuario", "Fechar");
        
        $btnSalvarLoginUsuario->addImage("glyphicon-floppy-save");
        $btnFecharLoginUsuario->addImage("glyphicon-off");

        $ftPanelLoginUsuario = new TBootstrapPanelFooter();
        $ftPanelLoginUsuario->addItem($btnSalvarLoginUsuario);
        $ftPanelLoginUsuario->addItem($btnFecharLoginUsuario);
       
        $panelLoginUsuario->setFooter($ftPanelLoginUsuario);
        
        /**
         * TabSheet
         */
        $tbDadosUsuario = new TBootstrapCarousel("DadosUsuario");
        $tbDadosUsuario->addItem($areaUsuario, true);
        $tbDadosUsuario->addItem("<div class='row'>"
                               . "<div class='col-md-6 col-md-offset-3'>" . 
                                 $panelLoginUsuario->show()
                               . "</div></div>");
        
        $areaTbDadosusuario = new TBootstrapGridCell();
        $areaTbDadosusuario->addItem($tbDadosUsuario);

        
        $areaPrincipal = new TBootstrapGrid();
        $areaPrincipal->addItem($areaTbDadosusuario);

        $this->addItem($areaPrincipal->show());
    }
}
