<?php

include_once '../forms/TFormUsuarios.php';

$frmUser = new TFormUsuarios();

$frmUser->addFileCSS("./libs/bootstrap/css/bootstrap.min.css");
$frmUser->addFileJS("./libs/bootstrap/js/bootstrap.min.js");
            
$frmUser->addFileCSS("./libs/datatables/datatables.min.css");
$frmUser->addFileJS("./libs/datatables/datatables.min.js");

$frmUser->addFileJS("./js/usuarios.js");
$frmUser->prepare();
$frmUser->open();
