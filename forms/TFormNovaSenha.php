<?php

include_once '../libs/app.widgets/bs/TBootstrapCommon.class.php';
include_once '../libs/app.widgets/bs/TBootstrapEdit.class.php';
include_once '../libs/app.widgets/bs/TBootstrapPanel.class.php';
include_once '../libs/app.widgets/bs/TBootstrapPanelFooter.class.php';
include_once '../libs/app.widgets/bs/TBootstrapGrid.class.php';
include_once '../libs/app.widgets/bs/TBootstrapGridCell.class.php';
include_once '../libs/app.widgets/bs/TBootstrapButton.class.php';
include_once '../libs/app.widgets/bs/TBootstrapForm.class.php';

interface iFormNovaSenha {
    public function addFileJS($file);
    public function addFileCSS($file);
    public function addJS($script);
    public function open();
}

/**
 * Description of TFormLogin
 *
 * @author hudsonmartins
 */
class TFormNovaSenha implements iFormNovaSenha {
    private $dirFileJS = null;
    private $scriptJS  = null;
    private $fCS       = null;
    
    public function __construct() {

    }
    
    public function addJS($script) {
        $this->scriptJS = "<script type='text/javascript'>{$script}</script>";
    }
    
    public function addFileJS($file) {
        $this->dirFileJS[] = "<script src='{$file}' type='text/javascript'></script>";
    }
    
    public function addFileCSS($file) {
            $this->fCS[] = "<link href='{$file}' rel='stylesheet'>";
    }
    
    public function open() {
        if ($this->fCS){
            foreach ($this->fCS as $key => $value) {
                echo $value;
            }
        }
        
        if ($this->dirFileJS){
            foreach ($this->dirFileJS as $key => $value) {
                echo $value;
            }
        }
        
        if (!empty($this->scriptJS)){
            echo $this->scriptJS;
        }
        
        $ttEdit   = new TTypeEdit();

        $edtEmail = new TBootstrapEdit("edtUsuario", $ttEdit->get()->email, "Email", "form-control");
        $edtEmail->setPlaceholder("Informe o seu Email");
        
        $formNovaSenha  = new TBootstrapForm();
        $formNovaSenha->addItem($edtEmail);
        
        $panelPrincipal = new TBootstrapPanel();
        $panelPrincipal->setTitle("Esqueci minha Senha", TRUE);
        $panelPrincipal->addItem("<div class='well'>"
                               . "<blockquote>"
                               . "<p>Informe o email cadastrado para que possamos enviar a nova senha</p>"
                               . "</blockquote>"
                               . "</div>");
        $panelPrincipal->addItem($formNovaSenha);
        
        $btnEnviar   = new TBootstrapButton("btnEnviar", "Enviar", "btn-primary");
        $btnCancelar = new TBootstrapButton("btnCancelar", "Cancelar");
        $btnEnviar->addImage("glyphicon-ok");
        $btnCancelar->addImage("glyphicon-remove");
        
        $ftPanelPrincipal = new TBootstrapPanelFooter();
        $ftPanelPrincipal->addItem($btnEnviar);
        $ftPanelPrincipal->addItem($btnCancelar);
        
        $panelPrincipal->setFooter($ftPanelPrincipal);
        
        $areaPrincipal = new TBootstrapGrid();
        $areaNovaSenha = new TBootstrapGridCell();

        $areaNovaSenha->setWidth("col-md-4");
        $areaNovaSenha->setPos("col-md-offset-4");
        $areaNovaSenha->addItem($panelPrincipal);
        //$areaNovaSenha->style = "position:absolute; top:100px";

        $areaPrincipal->addItem($areaNovaSenha);
        echo $areaPrincipal->show();
    }
}
