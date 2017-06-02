<?php
    include_once '../forms/TFormCadastroEmpresa.php';

    $frmProfile = new TFormCadastroEmpresa();
    
    //$frmProfile->addFileCSS("./libs/bootstrap/css/bootstrap.min.css");
    //$frmProfile->addFileJS("./libs/bootstrap/js/bootstrap.min.js");
    
    $frmProfile->addFileCSS("./libs/datatables/datatables.min.css");
    
    $frmProfile->addFileJS("./libs/datatables/datatables.min.js");
    $frmProfile->addFileJS("./libs/jquery/jquery.maskedinput.min.js");
    $frmProfile->addFileJS("./js/cadastroempresa.js");
    
    $frmProfile->prepare();
    $frmProfile->open();