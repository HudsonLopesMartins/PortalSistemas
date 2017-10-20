<?php

/**
 * Description of TFormGrupoAcesso
 *
 * @author hudsonmartins
 */

include_once './libs/interface/iFormsPage.inc.php';

include_once './include/message.inc.php';
require_once './libs/adodb5/adodb.inc.php';
include_once './class/Sistemas.class.php';

include_once './libs/app.widgets/TPage.class.php';
include_once './libs/app.widgets/bs/TBootstrapCommon.class.php';
include_once './libs/app.widgets/bs/TBootstrapPanel.class.php';
include_once './libs/app.widgets/bs/TBootstrapGrid.class.php';
include_once './libs/app.widgets/bs/TBootstrapGridCell.class.php';
include_once './libs/app.widgets/bs/TBootstrapButton.class.php';
include_once './libs/app.widgets/bs/TBootstrapCarousel.class.php';
include_once './libs/app.widgets/bs/TBootstrapEdit.class.php';
include_once './libs/app.widgets/bs/TBootstrapPanel.class.php';
include_once './libs/app.widgets/bs/TBootstrapPanelFooter.class.php';

class TFormGrupoAcesso extends TPage implements iPage {
    public function prepare($dados) {
        $this->loadScripts();
        
        /**
         * Formulário com a lista dos usuários
         */
        $btnGruposAtivos   = new TBootstrapButton("btnGruposAtivos", "Exibir Grupos Ativos");
        $btnGruposInativos = new TBootstrapButton("btnGruposInativos", "Exibir Grupos Inátivos");
        $btnFechar         = new TBootstrapButton("btnFechar", "Fechar", "btn-warning");
        $btnAdicionarGrupo = new TBootstrapButton("btnAdicionarGrupo", "Novo Grupo", "btn-primary");
        $btnConsultarGrupo = new TBootstrapButton("btnConsultarGrupo", "Consultar");
        $btnAdicionarGrupo->addImage("glyphicon-file");
        $btnConsultarGrupo->addImage("glyphicon-search");
        
        $itemBtnGrupo = new TBootstrapGridCell();
        $itemBtnGrupo->addItem("&nbsp;");
        $itemBtnGrupo->addItem($btnGruposAtivos);
        $itemBtnGrupo->addItem($btnGruposInativos);
        $itemBtnGrupo->addItem("&nbsp;&nbsp;|&nbsp;&nbsp;");
        $itemBtnGrupo->addItem($btnAdicionarGrupo);
        $itemBtnGrupo->addItem($btnConsultarGrupo);
        $itemBtnGrupo->addItem("&nbsp;&nbsp;|&nbsp;&nbsp;");
        $itemBtnGrupo->addItem($btnFechar);
        $itemBtnGrupo->addItem("<br><br>");
        $itemBtnGrupo->addItem("<input type='hidden' id='hdIDe' value='{$dados["d"]["empresa"][0]["id"]}'>");
        $itemBtnGrupo->addItem("<input type='hidden' id='hdIDu' value='{$dados["d"]["usuario"][0]["id"]}'>");
        
        
        $itemGridGrupo = new TBootstrapGridCell();
        $itemGridGrupo->setWidth("col-md-12");
        $itemGridGrupo->addItem("<table class='table table-striped' id='grdGrupo'>
                                        <thead>
                                            <tr>
                                                <th>grupo</th>
                                                <th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody id='grdListaUsuarios'></tbody>
                                    </table>");
        
        
        $formGrupo = new TBootstrapGrid();
        $formGrupo->addItem($itemBtnGrupo);
        $formGrupo->addItem($itemGridGrupo);
        
        $panelListaGrupo = new TBootstrapPanel();
        $panelListaGrupo->setTitle("Grupos", TRUE);
        $panelListaGrupo->addItem($formGrupo);
                
        /**
         * Formulário de Detalhes do Usuario
         */
        $ttEdit          = new TTypeEdit();
        $edtNomeGrupo         = new TBootstrapEdit("edtNomeGrupo", $ttEdit->get()->text, "", "form-control");
        
        $itemLnHorizontal = new TBootstrapGridCell();
        $itemLnHorizontal->setWidth("col-md-12");
        $itemLnHorizontal->addItem("<hr style='border: 0; height: 0; "
                                 . "border-top: 1px solid rgba(0, 0, 0, 0.1); "
                                 . "border-bottom: 1px solid rgba(255, 255, 255, 0.3);'>");

        $itemNomeGrupo = new TBootstrapGridCell();
        $itemNomeGrupo->setWidth("col-md-12");
        $itemNomeGrupo->addItem($edtNomeGrupo->show() . "<br>");

        $itemDlgStatus = new TBootstrapGridCell();
        $itemDlgStatus->setWidth("col-md-12");
        $itemDlgStatus->addItem("<div id='dlgStatus' class='alert alert-success' role='alert'>" . 
                                "<i class='fa fa-spinner fa-pulse fa-1x fa-fw'></i>" .
                                "<strong>Aguarde</strong>, carregando dados do registro selecionado...</div>");

        $formDetalhes = new TBootstrapGrid();
        $formDetalhes->addItem($itemDlgStatus);
        $formDetalhes->addItem($itemLnHorizontal);
        $formDetalhes->addItem($itemNomeGrupo);

        $panelDetalhesGrupo = new TBootstrapPanel();
        $panelDetalhesGrupo->setTitle("Grupo de Acesso", TRUE);
        $panelDetalhesGrupo->addItem($formDetalhes);
        $panelDetalhesGrupo->addItem("<input type='hidden' id='hdIDGrp'>");
        $panelDetalhesGrupo->addItem("<input type='hidden' id='hdGrpState'>");
        $panelDetalhesGrupo->addItem("<input type='hidden' id='hdFormState' value='i'>"); //i: Insert; e: Edit
        
        $btnSalvarDetalhes  = new TBootstrapButton("btnSalvarDetalhes", "Salvar Alterações", "btn-primary");
        $btnFecharDetalhes  = new TBootstrapButton("btnFecharDetalhes", "Fechar");
        $btnAlterarDetalhes = new TBootstrapButton("btnAlterarDetalhes", "Alterar Registro");

        $btnSalvarDetalhes->addImage("glyphicon-floppy-save");
        $btnFecharDetalhes->addImage("glyphicon-off");
        $btnAlterarDetalhes->addImage("glyphicon-pencil");

        $ftPanelDetalhes = new TBootstrapPanelFooter();
        $ftPanelDetalhes->addItem($btnSalvarDetalhes);
        $ftPanelDetalhes->addItem($btnFecharDetalhes);
        $ftPanelDetalhes->addItem("&nbsp;&nbsp;|&nbsp;&nbsp;");
        $ftPanelDetalhes->addItem($btnAlterarDetalhes);

        $panelDetalhesGrupo->setFooter($ftPanelDetalhes);

        /**
         * TabSheet
         */
        $tbGrupo = new TBootstrapCarousel("tbGrupo");
        $tbGrupo->addPageItem($panelListaGrupo, true);
        //$tbGrupo->addItem("<div class='row'><div class='col-md-10 col-md-offset-1'>" . 
        $tbGrupo->addItem("<div class='row'><div class='col-md-12'>" . 
                          $panelDetalhesGrupo->show() . 
                          "</div></div>");
        //$tbGrupo->addItem("<div class='row'><div class='col-md-6 col-md-offset-3'>" . 
        
        //$this->addItem($tbGrupo->show());
        $areaGrupos = new TBootstrapGridCell();
        $areaGrupos->setWidth("col-md-8");
        //$areaGrupos->setPos("col-md-offset-1");
        $areaGrupos->addItem($tbGrupo);
        
        $areaAppsEmpresa = new TBootstrapGridCell();
        $areaAppsEmpresa->setWidth("col-md-4");
        
        $s     = (object) (new Sistemas)->findAppByEmp($dados, false);
        $idApp = 0;
        $lstMenu = "<div class='btn-group-vertical col-md-12' role='group' aria-label='...'>";
        for($i = 0; $i < count($s->r); $i++){
            if ($i == 0){
                $lstMenu .= "<button type='button' class='btn btn-default active groupuserapp' appid='" . $s->r[$i]["id"] . "' appidemp='" . $s->r[$i]["id_empresa"] . "'>" . $s->r[$i]["sistema"] . "</button>";
                $idApp    = (int) $s->r[$i]["id"];
            }
            else {
                $lstMenu .= "<button type='button' class='btn btn-default groupuserapp' appid='" . $s->r[$i]["id"] . "' appidemp='" . $s->r[$i]["id_empresa"] . "'>" . $s->r[$i]["sistema"] . "</button>";
            }
        }
        $lstMenu .= "</div>";
        
        $areaAppsEmpresa->addItem("<div class='panel panel-default'>
                                    <div class='panel-heading'>
                                      <h3 class='panel-title'>Aplicativos</h3>
                                    </div>
                                    <div class='panel-body'>
                                        <!--
                                        <div class='btn-group-vertical col-md-12' role='group' aria-label='...'>
                                            <button type='button' class='btn btn-default'>Sistema 01</button>
                                            <button type='button' class='btn btn-default'>Sistema 02</button>
                                            <button type='button' class='btn btn-default'>Sistema 03</button>
                                        </div>
                                        -->
                                        {$lstMenu}
                                        <input type='hidden' id='hdIdAppEmp' value='{$idApp}'>
                                    </div>
                                  </div>");
        
        $areaPrincipal = new TBootstrapGrid();
        $areaPrincipal->addItem($areaAppsEmpresa);
        $areaPrincipal->addItem($areaGrupos);
        $this->addItem($areaPrincipal->show());
    }
}
