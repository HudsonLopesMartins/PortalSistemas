<?php

include_once './include/config.inc.php';
include_once './libs/app.widgets/TSession.class.php';
include_once './libs/app.widgets/bs/TBootstrapAlerts.class.php';

//include_once './libs/app.widgets/bs/TBootstrapCommon.class.php';
//include_once './libs/app.widgets/bs/TBootstrapCarousel.class.php';

$sessao = new TSession();
$st     = $sessao->get("status") ? "on": "off";

if ($st == "off"){
    ?>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Usuário Inválido</title>
        <link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet"> 
        <script src="libs/jquery/jquery.js" type="text/javascript"></script> 
        <script src="libs/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
        <link href="libs/css/index.css" rel="stylesheet">
        <script>
            $(function() {
                var messageWait = function(){
                    $('#app').html("<br><br><br><br><div class='row'><div class='col-md-4 col-md-offset-4'>" + 
                                   "<div class='alert alert-warning' role='alert'>" + 
                                   "<h4>Erro no Login</h4>" +
                                   "<p><strong>Aguarde...</strong> Retornando ao formulário de acesso</div></p>" +
                                   "</div></div>");
                    return $("#app").fadeIn(4000).delay(4000).fadeOut();
                };
                $.when(messageWait()).done(function(){
                    window.open('./', '_self');
                });
            });
        </script>
    </head>
    <body>
        <div id="app" class="container-fluid"></div>
    </body>
    <?php
}
else {
    $nfantasia = !empty($sessao->get("nfantasia")) ? $sessao->get("nfantasia"): "";
    $nusuario  = !empty($sessao->get("nusuario"))  ? $sessao->get("nusuario") : "";
    $tusuario  = !empty($sessao->get("tusuario"))  ? $sessao->get("tusuario") : "DFLT";
    $idempresa = !empty($sessao->get("id_empresa"))  ? $sessao->get("id_empresa") : "0";
    $idusuario = !empty($sessao->get("id_usuario"))  ? $sessao->get("id_usuario") : "0";
    ?>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>::<?php echo APPTITLE . " - " . APPSUBTITLE; ?>::</title>
        <script src="https://maps.google.com/maps/api/js?key=AIzaSyAx6ljuc3jhDPSJ1qLI4JUGF3BZx0UPfR8&signed_in=true"></script>
        
        <?php
            echo $cHead["css"]["bootstrap"]["default"];
            echo $cHead["css"]["font"];
            //echo $cHead["css"]["leaflet"];
            
            echo $cHead["js"]["jquery"];
            echo $cHead["js"]["bootstrap"];
            //echo $cHead["js"]["maplace"];
            //echo $cHead["js"]["leaflet"];
            if (isset($_GET["libs"])){
                $lbs = filter_input(INPUT_GET, "libs");
                switch ($lbs) {
                    case "dtables":
                        echo $cHead["css"]["datatables"]["bootstrap"];
                        echo $cHead["js"]["datatables"]["jquery"];
                        echo $cHead["js"]["datatables"]["bootstrap"];
                        break;
                    default:
                        break;
                }
            }
            if (isset($_GET["v"]) && ($_GET["v"] == "usuarios")){
                echo $cHead["js"]["maskedit"];
            }
        ?>
        <link href="./libs/css/index.css" rel="stylesheet">
        <script>
            $(function() {
                var sTipo = '<?php echo $tusuario; ?>';
                if (sTipo !== "ADMN"){
                    $("#lnkGerenciamento").hide();
                }
                
                $("#aLogoff").click(function(){
                    $.post("./include/TJson.class.php", ({className: "TSession", methodName: "closeSession"}));
                    var messageWait = function(){
                        $('#app').html("<br><br><br><br><div class='row'><div class='col-md-4 col-md-offset-4'>" + 
                                       "<div class='alert alert-info' role='alert'>" + 
                                       "<h4>Logoff</h4>" +
                                       "<p><i class='fa fa-spinner fa-pulse fa-1x fa-fw'></i>" +
                                       "<strong>Aguarde...</strong> Finalizando a aplicação</div></p>" +
                                       "</div></div>");
                        return $("#app").fadeIn(4000).delay(4000).fadeOut();
                    };
                    $.when(messageWait()).done(function(){
                        window.open('./', '_self');
                    });
                });
                
                $('#lnkListaUsuarios').click(function(){
                    window.open('./principal.php?v=usuarios&libs=dtables', '_self');
                });
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
                    <a class="navbar-brand" href="#"><?php echo APPTITLE; ?></a>
                </div>
                
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#" id="aEmp">Empresa: <?php echo $nfantasia; ?></a></li>
                        <li><a href="#" id="aUser"><span class="glyphicon glyphicon-user"></span> <?php echo $nusuario; ?></a></li>
                        <li class="dropdown" id="lnkGerenciamento">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Gerenciamento <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#" id="lnkListaUsuarios"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> &nbsp;&nbsp;Usuários</a></li>
                                <li><a href="#"><span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span> &nbsp;&nbsp;Grupo de Ususários</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> &nbsp;&nbsp;Dados Cadastrais</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> &nbsp;&nbsp;Adquirir Novos Aplicativos</a></li>
                                <!--
                                <li role="separator" class="divider"></li>
                                <li><a href="#"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> &nbsp;&nbsp;Logar-se como Administrador</a></li>
                                -->
                            </ul>
                        </li>
                        <li><a href="#" id="aLogoff"><span class="glyphicon glyphicon-log-out"></span> Logoff</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="app" class="container-fluid">
            <?php
                if (isset($_GET["v"])){
                    $view = filter_input(INPUT_GET, "v");
                }
                
                try {
                    if (file_exists("./view/{$view}.php")){
                        include_once "./view/{$view}.php";
                    }
                    else {
                        echo (new TBootStrapAlerts)->show("<p><strong>Erro!</strong>&nbsp;Não foi possível carregar o formulário informado.</p>", 
                                                          "warning", "<h4>Erro</h4>");
                    }
                } catch (Exception $e) {
                    echo  (new TBootStrapAlerts)->show("<p><strong>Erro!</strong>&nbsp;Não foi possível carregar o formulário informado.<br>" . 
                                                       "Mensagem: {$e->getMessage()}</p>", 
                                                       "warning", "<h4>Erro</h4>");
                }
            ?>
        </div>
    </body>
    <?php
    //(new TSession())->closeSession();
}
