<!DOCTYPE html>
<?php
    include_once './include/config.inc.php';
    include_once './include/message.inc.php';

    include_once './libs/app.widgets/bs/TBootstrapAlerts.class.php';
    
    include_once './forms/TFormLogin.php';
?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>::<?php echo APPTITLE . " - " . APPSUBTITLE; ?>::</title>
        <script src="https://maps.google.com/maps/api/js?key=AIzaSyAx6ljuc3jhDPSJ1qLI4JUGF3BZx0UPfR8&signed_in=true"></script>
        
        <?php 
            echo $cHead["css"]["bootstrap"]["default"];
            //echo $cHead["css"]["font"];
            //echo $cHead["css"]["leaflet"];
            echo $cHead["js"]["jquery"];
            echo $cHead["js"]["bootstrap"];
            echo $cHead["js"]["maplace"];
            //echo $cHead["js"]["leaflet"];
        ?>
        <link href="libs/css/index.css" rel="stylesheet">
        <script>
            $(function() {
                $("#aLogoff").hide();
                $("#aEmp").hide();
                $("#aUser").hide();
                $("#lnkGerenciamento").hide();
            });
        </script>
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Brand</a>
                </div>
                
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#" id="aEmp">Empresa</a></li>
                        <li><a href="#" id="aUser">Usuário Logado</a></li>
                        <li class="dropdown" id="lnkGerenciamento">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Gerenciamento <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#" id="lnkListaUsuarios"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> &nbsp;&nbsp;Usuários</a></li>
                                <li><a href="#"><span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span> &nbsp;&nbsp;Grupo de Ususários</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> &nbsp;&nbsp;Dados Cadastrais</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> &nbsp;&nbsp;Logar-se como Administrador</a></li>
                            </ul>
                        </li>
                        <li><a href="#" id="aLogoff">Efetuar Logoff</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="app" class="container-fluid">
        <?php
            session_start();
            if (!isset($_SESSION["status"])){
                $frmLogin = new TFormLogin();
                $frmLogin->addFileJS("./js/login.js");
                $frmLogin->open();
            }
            else {
                //unset($_SESSION["status"]);
                //echo TBootStrapAlerts::show("Usuário logado com sucesso", "success", "Ok!");
                //header("location:principal.php");
                
                $frmLogin = new TFormLogin();
                $frmLogin->addFileJS("./js/login.js");
                $frmLogin->open();
            }
        ?>
        </div>
    </body>
</html>