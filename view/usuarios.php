<?php

include_once './forms/TFormUsuarios.php';
include_once './libs/app.widgets/bs/TBootstrapAlerts.class.php';
    
$frmUser = new TFormUsuarios();
//$frmUser->addFileCSS("./libs/datatables/datatables.min.css");
//$frmUser->addFileJS("./libs/datatables/datatables.min.js");
$frmUser->addFileJS("./js/usuarios.js");
$frmUser->prepare(array("d" => array("empresa" => array(0 => array("id"=>$idempresa)),
                                     "usuario" => array(0 => array("id"=>$idusuario)))));
$frmUser->open();
