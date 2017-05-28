<!DOCTYPE html>
<?php
    include_once './include/config.inc.php';
    include_once './libs/app.widgets/bs/TBootstrapAlerts.class.php';

    echo "<br><br><br><br>";
    
    session_start();
    //$_SESSION["status"] = "on";
    if (!isset($_SESSION["status"])){
        //unset($_SESSION["status"]);
        //header("location:index.php");
        echo TBootStrapAlerts::show("Não foi possível efetuar o login", "alert", "Erro!");
    }
    else {
        echo TBootStrapAlerts::show("Usuário logado com sucesso", "success", "Ok!");
        //header("location:principal.php");
    }
?>