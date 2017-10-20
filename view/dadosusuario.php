<?php
    include_once './forms/TFormDadosUsuario.php';

    $frmDadosUsuario = new TFormDadosUsuario();
    $frmDadosUsuario->addFileJS("./js/dadosusuario.js");
    
    $frmDadosUsuario->prepare(array("d" => array("empresa"    => array(0 => array("id" => $idempresa)),
                                                 "appempresa" => array(0 => array("id" => 1)),
                                                 "usuario"    => array(0 => array("id" => $idusuario)) )));
    $frmDadosUsuario->open();