<?php
    include_once '../forms/TFormCadastroLogin.php';
    
    $frmCadLogin = new TFormCadastroLogin();
    
    $frmCadLogin->addFileCSS("./libs/bootstrap/css/bootstrap.min.css");
    $frmCadLogin->addFileJS("./libs/bootstrap/js/bootstrap.min.js");
    
    $frmCadLogin->addJS("$(function() {"
                        . "  var dados = " . json_encode($_GET["dt3"]) . ";"
                        . "  $('#edtUsuarioLogin').val(dados[0].d.empresa[0].email);"
            
                        . "  $('#btnFinalizaCadLogin').click(function(){"
                        . "     dados[0].d.user = []; "
                        . "     dados[0].d.user.push({'pwd':$('#edtConfrLogin').val()});"
                        
                        . "     $.post('include/TJson.class.php', ({className: 'Empresa', methodName: 'inserir', params: dados}),"
                        . "     function(resultData){"
                        . "         if (resultData.r[0].COD === '202'){"
                        . "             alert(resultData.r[0].MSG);"
                        . "             window.open('./', '_self');"
                        . "         }"
                        . "         else {"
                        . "             alert(resultData.r[0].MSG);"
                        . "         }"
                        . "     }, 'json')"
                        . "     .fail(function(jqXHR, status, error){"
                        . "         var msg = 'Erro ao inserir registro!' + "
                        . "                   '- Mensagens ' +"
                        . "                   'XHR: ' + jqXHR.reponseXML + "
                        . "                   'Status: ' + status + "
                        . "                   'Error Type: ' + error; "
                        . "         alert(msg);"
                        . "     });"
                        
                        . "  });"
                        . "  $('#btnCancelarCadLogin').click(function(){"
                        . "     window.open('./', '_self');"
                        . "  });"
                        . "});");
    
    $frmCadLogin->prepare();
    $frmCadLogin->open();