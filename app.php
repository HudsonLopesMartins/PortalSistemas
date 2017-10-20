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
        <?php 
            echo $cHead["css"]["bootstrap"]["default"];
            echo $cHead["js"]["jquery"];
            echo $cHead["js"]["bootstrap"];
            echo $cHead["css"]["font"];
        ?>
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
    $nfantasia  = !empty($sessao->get("nfantasia"))   ? $sessao->get("nfantasia")   : "";
    $nusuario   = !empty($sessao->get("nusuario"))    ? $sessao->get("nusuario")    : "";
    $tusuario   = !empty($sessao->get("tusuario"))    ? $sessao->get("tusuario")    : "DFLT";
    $idempresa  = !empty($sessao->get("id_empresa"))  ? $sessao->get("id_empresa")  : "0";
    $idusuario  = !empty($sessao->get("id_usuario"))  ? $sessao->get("id_usuario")  : "0";
    $iddusuario = !empty($sessao->get("id_dusuario")) ? $sessao->get("id_dusuario") : "0";
    $chPwd      = !empty($sessao->get("mdsnh"))       ? $sessao->get("mdsnh")       : "0";
    ?>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>::<?php echo APPTITLE . " - " . APPSUBTITLE; ?>::</title>
        <!--
        <script src="https://maps.google.com/maps/api/js?key=AIzaSyAx6ljuc3jhDPSJ1qLI4JUGF3BZx0UPfR8&signed_in=true"></script>
        -->
        
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
                    case "cal":
                        //echo $cHead["js"]["xtras"];
                        echo $cHead["css"]["minicalendario"];
                        echo $cHead["js"]["minicalendario"];
                        break;
                    default:
                        break;
                }
            }
            
            if (isset($_GET["v"])){
                switch ($_GET["v"]) {
                    case "dadosempresa":
                    case "dadosusuario":
                        echo "<script src='https://maps.google.com/maps/api/js?key=AIzaSyAx6ljuc3jhDPSJ1qLI4JUGF3BZx0UPfR8&signed_in=true'></script> \r\n";
                        echo $cHead["js"]["maplace"];
                        echo $cHead["js"]["maskedit"];
                        break;
                    case "usuarios":
                        echo $cHead["js"]["maskedit"];
                        break;
                default:
                    break;
                }
            }
            
            if (isset($_GET["subapp"])){
                switch ($_GET["subapp"]) {
                    case "novocliente":
                    case "cliente":
                        echo "<script src='https://maps.google.com/maps/api/js?key=AIzaSyAx6ljuc3jhDPSJ1qLI4JUGF3BZx0UPfR8&signed_in=true'></script> \r\n";
                        echo $cHead["js"]["maplace"];
                        echo $cHead["js"]["maskedit"];
                        break;
                default:
                    break;
                }
            }
            
        ?>
        <link href="./libs/css/index.css" rel="stylesheet">
        <script>
            $(function() {
                var chPwd = parseInt('<?php echo $chPwd; ?>');
                var sTipo = '<?php echo $tusuario; ?>';
                if (sTipo === "DFLT"){
                    $("#lnkGerenciamento").hide();
                }
                
                if (chPwd === 1){
                    alert("Altere sua senha clicando sobre o seu usuário e em seguida sobre o botão [ Alterar Login ].");
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
                
                $(document).on('click', '.mniprincipal', function(){
                    var appname  = $(this).attr("viewname");
                    var libsname = $(this).attr("libsname");

                    if (libsname === undefined || 
                        libsname == null || 
                        libsname == "" || 
                        libsname.length <= 0){
                        window.open('./app.php?v=' + appname, '_self');
                    }
                    else {
                        window.open('./app.php?v=' + appname + '&libs=' + libsname, '_self');
                    }
                });
                
                $(document).on('click', '.mniedituser', function(){
                    var appname  = "dadosusuario";
                    var libsname = "dtables";
                    
                    window.open('./app.php?v=' + appname + '&libs=' + libsname, '_self');
                    
                    //var idu = <?php echo $idusuario; ?>;
                    //var ide = <?php echo $idempresa; ?>;
                    //alert("Usuário a ser editado: " + idu);
                    
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
                        <li><a href="#" id="aUser" class="mniedituser"><span class="glyphicon glyphicon-user"></span> <?php echo $nusuario; ?></a></li>
                        <li class="dropdown" id="lnkGerenciamento">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Gerenciamento <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#" class="mniprincipal" viewname="usuarios" libsname="dtables">
                                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span> &nbsp;&nbsp;Usuários</a>
                                </li>
                                <li>
                                    <a href="#" class="mniprincipal" viewname="grupoacesso" libsname="dtables">
                                        <span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span> &nbsp;&nbsp;Grupo de Ususários</a>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li>
                                    <a href="#" class="mniprincipal" viewname="dadosempresa" libsname="dtables"
                                       ><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> &nbsp;&nbsp;Dados Cadastrais</a>
                                </li>
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
                    $dirapps = array(
                                    "view", 
                                    "apps.view/gestaoagenda"
                               );
                    foreach ($dirapps as $value) {
                        if (file_exists("./{$value}/{$view}.php")){
                            include_once "./{$value}/{$view}.php";
                        }
                        /**
                        else {
                            echo (new TBootStrapAlerts)->show("<p><strong>Erro!</strong>&nbsp;Não foi possível carregar o formulário informado.</p>", 
                                                              "warning", "<h4>Erro</h4>");
                        }
                         * 
                         */
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