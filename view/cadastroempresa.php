<?php
    include_once './forms/TFormCadastroEmpresa.php';

    $frmProfile = new TFormCadastroEmpresa();
    $frmProfile->addFileJS("./js/cadastroempresa.js");
    
    $frmProfile->prepare();
    $frmProfile->open();