<?php

include_once '../forms/TFormAppsMenu.php';

$frmApps = new TFormAppsMenu();
//$frmApps->addFileCSS("./libs/bootstrap/css/bootstrap.min.css");
//$frmApps->addFileJS("./libs/bootstrap/js/bootstrap.min.js");

if (is_int((int) $_GET["id_empresa"])){
    $id_empresa = (int) $_GET["id_empresa"];
}

$frmApps->prepare(array("d" => array("empresa" => array(0 => array("id"=>$id_empresa)))));
$frmApps->open();