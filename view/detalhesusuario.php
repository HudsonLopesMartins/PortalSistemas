<?php

include_once '../forms/TFormDetalhesUsuario.php';
include_once '../libs/app.widgets/bs/TBootstrapAlerts.class.php';

session_start();
if (!isset($_SESSION["status"])){
    echo "<div class='row'><div class='col-md-6 col-md-offset-3'>" . 
         TBootStrapAlerts::show("<p><strong>Erro!</strong>&nbsp;Efetue o login e tente novamente. <a href='./' class='alert-link'>Clique aqui para retornar.</a></p>", 
                                "warning", "<h4>Falha no Login</h4>") .
         "</div></div>";
}
else {
    $ide  = (int) $_GET["ide"];
    $idu  = (int) $_GET["idu"];
    $mode = $_GET["m"];
    
    $frmUser = new TFormDetalhesUsuario();

    $frmUser->addFileCSS("../libs/bootstrap/css/bootstrap.min.css");
    $frmUser->addFileJS("../libs/jquery/jquery.js");
    $frmUser->addFileJS("../libs/bootstrap/js/bootstrap.min.js");
    
    $js =   "$(function() {";

    switch ($mode) {
        case "view":
            $js .="  $('#btnSalvar').hide();";
            break;
        case "edit":
            $js .="  $('#btnSalvar').click(function(){"
                . "     var dados = [{"
                . "        'd': {"
                . "          'empresa': [{"
                . "            'id': " . $ide
                . "          }],"
                . "          'usuario': [{"
                . "            'id': " . $idu
                . "          }]"
                . "        }"
                . "     }];"
                . "     $.post('../include/TJson.class.php', ({ "
                . "                                            className: 'Usuario', "
                . "                                            methodName: 'viewDetalhesUsuario', "
                . "                                            params: dados "
                . "                                           }),  "
                . "     function(rs){ "
                . "        alert(rs);"
                . "        if (rs.r[0].COD === '201'){ "
                . "           alert('AVISO: ' + rs.r[0].MSG); "
                . "        } "
                . "        else { "
                . "           alert('AVISO: ' + rs.r[0].email_usuario); "
                . "        } "
                . "     }) "
                . "     .fail(function(jqXHR, status, error){ "
                . "        var msg = 'Erro ao carregar Registros! ' +  "
                . "                '\\nMensagens ' + "
                . "                '\\nXHR: ' + jqXHR.reponseXML + "
                . "                '\\nStatus: ' + status +  "
                . "                '\\nError Type: ' + error; "
                . "        alert(msg); "
                . "     }); "
                . "  });";
            break;
        default:
            break;
    }
    
    $js .=  "  $('#btnFechar').click(function(){"
          . "     window.close();"
          . "  });"
          . "});";
    
    $frmUser->addJS($js);
    $frmUser->prepare(array("d" => array("empresa" => array(0 => array("id"=>$ide)),
                                         "usuario" => array(0 => array("id"=>$idu)))));
    
    echo "<meta charset='UTF-8'>"
       . "<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />";
    
    $frmUser->open();
}
