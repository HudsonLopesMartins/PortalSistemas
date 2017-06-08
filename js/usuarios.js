$(function() {    
    $('#grdUsuarios').DataTable({
        language: {
            url: './libs/datatables/pt-BR/Portuguese-Brasil.json'
        },
        scrollY:        '310px',
        scrollCollapse: true,
        paging:         true,
        ordering:       false,
        searching:      true
    });
    
    function detalhesUsuario(ide, idu){
        var dados = [{
            'd': {
                'empresa': [{
                    'id': ide
                }],
                'usuario': [{
                    'idu': idu
                }]
            }
        }];
        window.open("./view/detalhesusuario.php?m=view&ide=" + ide + "&idu=" + idu, "_blank", "toolbar=0,titlebar=0,menubar=0,width=800,height=450");
    }
    
    $(document).on('click', '.view', function(){
        detalhesUsuario($(this).attr("ide"), $(this).attr("idu"));
        //setPanelItem("formcategoria");
        //alert("Exibir Detalhes para a empresa " + $(this).attr("ide") + " " +
        //      "e usuario " + $(this).attr("idu"));
    });

    $('#btnFechar').click(function(){
        window.open('./', '_self');
    });

    $('#btnUsersInativos').click(function(){
        var dados = [{
            'd': {
                'empresa': [{
                    'id': '1'
                }],
                'usuario': [{
                    'ativo': '0'
                }]
            }
        }];

        $.post('./include/TJson.class.php', ({
                                                className: 'Usuario',
                                                methodName: 'findUsersByEmp',
                                                params: dados
                                            }), 
        function(rs){
            if (rs.r[0].COD === '201'){
                alert('AVISO: ' + rs.r[0].MSG);
            }
            else {
                var t = $('#grdUsuarios').DataTable();
                t.clear().draw();
                $.each(rs.r, function(idx, value){
                    if (value.ativo === 1){
                        var controles = "<a href='#' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='view'><span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='edit'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='lock'><span class='glyphicon glyphicon-lock' aria-hidden='true'></span></a>";
                    }
                    else {
                        var controles = "<a href='#' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='view'><span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='edit'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='unlock'><span class='glyphicon glyphicon-ok-sign' aria-hidden='true'></span></a>";
                    }

                    t.row.add([
                        value.nome,
                        value.email,
                        value.grupo,
                        controles
                    ]).draw();
                });
            }
        }, 'json')
        .fail(function(jqXHR, status, error){
            var msg = 'Erro ao carregar Registros!\r\n' + 
                      '- Mensagens \r\n' +
                      'XHR: ' + jqXHR.reponseXML + '\r\n' + 
                      'Status: ' + status + '\r\n' +
                      'Error Type: ' + error;
            alert(msg);
        });

    });

    $('#btnUsersAtivos').click(function(){
        var dados = [{
            'd': {
                'empresa': [{
                    'id': '1'
                }],
                'usuario': [{
                    'ativo': '1'
                }]
            }
        }];

        $.post('./include/TJson.class.php', ({
                                                className: 'Usuario',
                                                methodName: 'findUsersByEmp',
                                                params: dados
                                            }), 
        function(rs){
            if (rs.r[0].COD === '201'){
                alert('AVISO: ' + rs.r[0].MSG);
            }
            else {
                var t = $('#grdUsuarios').DataTable();
                t.clear().draw();
                $.each(rs.r, function(idx, value){
                    if (value.ativo === 1){
                        var controles = "<a href='#' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='view'><span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='edit'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='lock'><span class='glyphicon glyphicon-lock' aria-hidden='true'></span></a>";
                    }
                    else {
                        var controles = "<a href='#' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='view'><span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='edit'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='unlock'><span class='glyphicon glyphicon-ok-sign' aria-hidden='true'></span></a>";
                    }

                    t.row.add([
                        value.nome,
                        value.email,
                        value.grupo,
                        controles
                    ]).draw();
                });
            }
        }, 'json')
        .fail(function(jqXHR, status, error){
            var msg = 'Erro ao carregar Registros!\r\n' + 
                      '- Mensagens \r\n' +
                      'XHR: ' + jqXHR.reponseXML + '\r\n' + 
                      'Status: ' + status + '\r\n' +
                      'Error Type: ' + error;
            alert(msg);
        });

    });
});