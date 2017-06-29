<?php

include_once './forms/TFormAppsMenu.php';
$frmApps = new TFormAppsMenu();
if (is_int((int) $idempresa)){
    $id_empresa = (int) $idempresa;
}
$frmApps->prepare(array("d" => array("empresa" => array(0 => array("id"=>$id_empresa)))));
$frmApps->open();