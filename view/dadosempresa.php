<?php
    include_once './forms/TFormDadosEmpresa.php';

    $frmDadosEmpresa = new TFormDadosEmpresa();
    $frmDadosEmpresa->addFileJS("./js/dadosempresa.js");
    
    $frmDadosEmpresa->prepare(array("d" => array("empresa" => array(0 => array("id"=>$idempresa)))));
    $frmDadosEmpresa->open();