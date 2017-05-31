<?php

include_once '../forms/TFormAppsMenu.php';

$frmApps = new TFormAppsMenu();
$frmApps->prepare(array("d" => array("empresa" => array(0 => array("id"=>$_GET["id_empresa"])))));
$frmApps->open();