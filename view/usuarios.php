<?php

include_once '../forms/TFormUsuarios.php';
include_once '../libs/app.widgets/bs/TBootstrapAlerts.class.php';
    
session_start();
if (!isset($_SESSION["status"])){
    echo "<div class='row'><div class='col-md-6 col-md-offset-3'>" . 
         TBootStrapAlerts::show("<p><strong>Erro!</strong>&nbsp;Efetue o login e tente novamente. <a href='./' class='alert-link'>Clique aqui para retornar.</a></p>", 
                                "warning", "<h4>Falha no Login</h4>") .
         "</div></div>";
}
else {
    $frmUser = new TFormUsuarios();

    $frmUser->addFileCSS("./libs/bootstrap/css/bootstrap.min.css");
    $frmUser->addFileJS("./libs/bootstrap/js/bootstrap.min.js");

    $frmUser->addFileCSS("./libs/datatables/datatables.min.css");
    $frmUser->addFileJS("./libs/datatables/datatables.min.js");

    $frmUser->addFileJS("./js/usuarios.js");
    $frmUser->prepare(array("d" => array("empresa" => array(0 => array("id"=>$_SESSION["id_empresa"])),
                                         "usuario" => array(0 => array("id"=>$_SESSION["id_usuario"])))));
    $frmUser->open();
}
