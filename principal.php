<!DOCTYPE html>
<?php
    //include_once './include/config.inc.php';
    include_once './libs/app.widgets/bs/TBootstrapAlerts.class.php';
    
    session_start();
    if (!isset($_SESSION["status"])){
        echo "<div class='row'><div class='col-md-6 col-md-offset-3'>" . 
             TBootStrapAlerts::show("<p><strong>Falha!</strong>&nbsp;Não foi possível efetuar o login</p>", 
                                    "warning", "<h4>Erro</h4>") .
             "</div></div>";
    }
    else {
        /*
        echo "<br><br><br><br><div class='row'><div class='col-md-6 col-md-offset-3'>" . 
             TBootStrapAlerts::show("<p><strong>Ok!</strong>&nbsp;Preferências carregadas com sucesso.</p>", 
                                    "success", "<h4>Sucesso</h4>") .
             "</div></div>";
        */
        echo "<link href=\"libs/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\"> \r\n";
        echo "<script src=\"libs/bootstrap/js/bootstrap.min.js\" type=\"text/javascript\"></script> \r\n";
        header("location:./view/appsmenu.php?id_empresa=" . $_SESSION["id_empresa"]);
    }
