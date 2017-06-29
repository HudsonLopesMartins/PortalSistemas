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
        
        //$ttEdit     = new TTypeEdit();
        //$edtUsuario = new TBootstrapEdit("edtUsuario", $ttEdit->get()->email, "Email", "form-control");
        /**
        $edtSenha   = new TBootstrapEdit("edtSenha", $ttEdit->get()->password, "Senha " . 
                                         "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . 
                                         "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . 
                                         "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . 
                                         "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . 
                                         "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . 
                                         "&nbsp;&nbsp;&nbsp;" . 
                                         "<a href='#' id='lnkEnviarNovaSenha'>Esqueci minha Senha</a>", "form-control");
        */
        //$edtSenha   = new TBootstrapEdit("edtSenha", $ttEdit->get()->password, "Senha", "form-control");
        
        $formLogin  = new TBootstrapForm();
        $formLogin->addItem("<div class='input-group margin-bottom-sm'>"
                          . "   <span class='input-group-addon'><i class='fa fa-envelope-o fa-fw'></i></span>"
                          . "   <input type='email' id='edtUsuario' class='form-control' placeholder='Email'>"
                          . "</div><br>");
        $formLogin->addItem("<div class='input-group margin-bottom-sm'>"
                          . "   <span class='input-group-addon'><i class='fa fa-key fa-fw'></i></span>"
                          . "   <input type='password' id='edtSenha' class='form-control' placeholder='Senha'>"
                          . "</div><br>");
        
        $panelPrincipal = new TBootstrapPanel();
        $panelPrincipal->setTitle("<div class='panel-title'>Login</div>" . 
                                  "<div style='float:right; font-size: 85%; position: relative; top:-10px'>" . 
                                  "<a href='#' id='lnkEnviarNovaSenha'>Esqueci minha Senha</a></div>");
        $panelPrincipal->addItem($formLogin);
        
        $btnCarregarEmpresas = new TBootstrapButton("btnCarregarEmpresas", "Carregar Empresas");
        $btnCancelar         = new TBootstrapButton("btnCancelar", "Cancelar");
        $btnCarregarEmpresas->addImage("glyphicon-ok");
        $btnCancelar->addImage("glyphicon-remove");
        
        $ftPanelPrincipal = new TBootstrapPanelFooter();
        $ftPanelPrincipal->addItem($btnCarregarEmpresas);
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
        $areaLogin->addItem("<div class='well'>"
                          . " <div class='row'>"
                          . "     <div class='col-md-12'><p class='text-center'>"
                          . "Novo aqui? <a href='#' id='lnkEfetuarCadastro'>Crie um novo cadastro</a>.</p></div>" 
                          . " </div>"
                          . "</div>");

        $areaPrincipal->addItem($areaLogin);
        echo $areaPrincipal->show();
    }
}
