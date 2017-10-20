<?php

include_once './forms/TFormGrupoAcesso.php';
include_once './libs/app.widgets/bs/TBootstrapAlerts.class.php';
    
$frmGrupo = new TFormGrupoAcesso();
$frmGrupo->addFileJS("./js/grupoacesso.js");
$frmGrupo->prepare(array("d" => array("empresa" => array(0 => array("id"=>$idempresa)),
                                     "usuario" => array(0 => array("id"=>$idusuario)))));
$frmGrupo->open();