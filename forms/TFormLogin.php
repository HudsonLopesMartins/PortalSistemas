<?php

include_once './libs/app.widgets/bs/TBootstrapCommon.class.php';
include_once './libs/app.widgets/bs/TBootstrapEdit.class.php';
include_once './libs/app.widgets/bs/TBootstrapPanel.class.php';
include_once './libs/app.widgets/bs/TBootstrapPanelFooter.class.php';
include_once './libs/app.widgets/bs/TBootstrapGrid.class.php';
include_once './libs/app.widgets/bs/TBootstrapGridCell.class.php';
include_once './libs/app.widgets/bs/TBootstrapButton.class.php';
include_once './libs/app.widgets/bs/TBootstrapForm.class.php';
include_once './libs/app.widgets/bs/TBootstrapSelect.class.php';

interface iFormLogin {
    public function addFileJS($file);
    public function addJS($script);
    public function open();
}

/**
 * Description of TFormLogin
 *
 * @author hudsonmartins
 */
class TFormLogin implements iFormLogin {
    private $dirFileJS = null;
    private $scriptJS  = null;
    
    public function __construct() {

    }
    
    public function addJS($script) {
        $this->scriptJS = "<script type='text/javascript'>{$script}</script>";
    }
    
    public function addFileJS($file) {
        $this->dirFileJS[] = "<script src='{$file}' type='text/javascript'></script>";
    }
    
    public function open() {
        if ($this->dirFileJS){
            foreach ($this->dirFileJS as $key => $value) {
                echo $value;
            }
        }
        
        if (!empty($this->scriptJS)){
            echo $this->scriptJS;
        }
        
        $ttEdit     = new TTypeEdit();

        $edtUsuario = new TBootstrapEdit("edtUsuario", $ttEdit->get()->email, "Email", "form-control");
        $edtSenha   = new TBootstrapEdit("edtSenha", $ttEdit->get()->password, "Senha", "form-control");
        //$edtUsuario->setPlaceholder("Informe o seu Email");
        //$edtSenha->setPlaceholder("Informe a sua Senha");
        
        $formLogin  = new TBootstrapForm();
        $formLogin->addItem($edtUsuario);
        $formLogin->addItem($edtSenha);
        
        $panelPrincipal = new TBootstrapPanel();
        $panelPrincipal->setTitle("Sistema de Solicitações - Login", TRUE);
        $panelPrincipal->addItem($formLogin);
        $panelPrincipal->addItem("<div class='col-md-12'>"
                               . "<div class='col-md-6'><a href='#' id='lnkEnviarNovaSenha'>Esqueci minha Senha</a></div>"
                               . "<div class='col-md-6'><a href='#' id='lnkEfetuarCadastro'>Efetuar Cadastro</a></div>"
                               . "</div>");
        
        $btnConfirmar = new TBootstrapButton("btnConfirmar", "Confirmar");
        $btnCancelar  = new TBootstrapButton("btnCancelar", "Cancelar");
        $btnConfirmar->addImage("glyphicon-ok");
        $btnCancelar->addImage("glyphicon-remove");
        
        $ftPanelPrincipal = new TBootstrapPanelFooter();
        $ftPanelPrincipal->addItem($btnConfirmar);
        $ftPanelPrincipal->addItem($btnCancelar);
        
        $panelPrincipal->setFooter($ftPanelPrincipal);
        
        $btnEfetuarLogin = new TBootstrapButton("btnEfetuarLogin", "Efetuar Login", "btn-primary");
        $btnEfetuarLogin->addImage("glyphicon-log-in");
        $ddlEmpresa   = new TBootstrapSelect("ddlEmpresa", false, "form-control");
        $ddlEmpresa->addItem("0", "Selecione uma Empresa");
        //$e = (object) (new Plano)->findAll(false);
        //for($i = 0; $i < count($p->r); $i++){
        //    $ddlEmpresa->addItem($e->r[$i]["id"], $e->r[$i]["nomefantasia"]);
        //}
        $formEmpresa = new TBootstrapForm();
        $formEmpresa->addItem($ddlEmpresa);
        
        $ftPanelEmpresa = new TBootstrapPanelFooter();
        $ftPanelEmpresa->addItem($btnEfetuarLogin);
        
        $panelEmpresa = new TBootstrapPanel();
        $panelEmpresa->addItem($formEmpresa);
        $panelEmpresa->setFooter($ftPanelEmpresa);
        
        $areaPrincipal = new TBootstrapGrid();
        $areaLogin     = new TBootstrapGridCell();

        $areaLogin->setWidth("col-md-4");
        $areaLogin->setPos("col-md-offset-4");
        $areaLogin->addItem($panelPrincipal);
        $areaLogin->addItem($panelEmpresa);
        //$areaLogin->style = "position:absolute; top:100px";

        $areaPrincipal->addItem($areaLogin);
        echo $areaPrincipal->show();
    }
}
