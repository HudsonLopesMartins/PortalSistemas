<?php
    include_once '../forms/TFormNovaSenha.php';

    $frmNovaSenha = new TFormNovaSenha();
    //$frmNovaSenha->addFileCSS("./libs/bootstrap/css/bootstrap.min.css");
    //$frmNovaSenha->addFileJS("./libs/bootstrap/js/bootstrap.min.js");
    $frmNovaSenha->addJS("
        $(function() {
            $('#btnEnviar').click(function(){
                alert('A senha será enviada para o email informado');
            });

            $('#btnCancelar').click(function(){
                window.open('./', '_self');
            });
        });
    ");
    $frmNovaSenha->open();
