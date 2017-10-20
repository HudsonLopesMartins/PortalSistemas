$(function() {
    //var windowMap;
    var itemIndex  = 0;
    var panelIndex = [
        "formlistagrupos",
        "formdetalhesgrupo"
    ];
    
    var allEditsFormUsuario  = $([]).add($("#edtNomeGrupo"));
    var allHiddenFormUsuario = $([]).add($("#hdIDGrp"));
    
    $("#dlgStatus").hide();
    $('#grdGrupo').DataTable({
        language: {
            url: './libs/datatables/pt-BR/Portuguese-Brasil.json'
        },
        scrollY:        '310px',
        scrollCollapse: true,
        paging:         true,
        ordering:       false,
        searching:      true
    });
    
    function setPanelItem(panelitem){
        $('.carousel').carousel(panelIndex.indexOf(panelitem));
        $('.carousel').carousel('pause');
        itemIndex = panelIndex.indexOf(panelitem);
    }
    
    function getPanelItem(){
        return itemIndex;
    }
    
    function detalhesGrupo(ide, idapp, idg, ativo){
        var dados = [{
            'd': {
                'empresa': [{
                    'id': ide
                }],
                'appempresa': [{
                    'id': idapp
                }],
                'grupoacesso': [{
                    'id': idg,
                    'ativo': ativo
                }]
            }
        }];
    
        setPanelItem("formdetalhesgrupo");
        
        $("#dlgStatus").show();
        var messageWait = function(){
            return $("#dlgStatus").fadeIn(3000).delay(3000).fadeOut();
        };
        $.when(messageWait()).done(function(){
            $.post("./include/TJson.class.php", ({
                                                    className: "GrupoAcesso",
                                                    methodName: "localizar",
                                                    params: dados
                                                }), 
            function(rs){
                if (rs.r[0].COD === "201"){
                    alert("AVISO: " + rs.r[0].MSG);
                }
                else {
                    $("#edtNomeGrupo").val(rs.r[0].nome);
                    $("#hdIDGrp").val(rs.r[0].id_grupo);
                    $("#hdGrpState").val(rs.r[0].ativo);
                    $("#hdFormState").val("e");
                }
            }, "json")
            .fail(function(jqXHR, status, error){
                var msg = "Erro ao carregar Registros!\r\n" + 
                          "- Mensagens \r\n" +
                          "XHR: " + jqXHR.reponseXML + "\r\n" + 
                          "Status: " + status + "\r\n" +
                          "Error Type: " + error;
                alert(msg);
            });

        });
        $("#btnSalvarDetalhes").hide();
    }
    
    function editarBloqueio(ide, idg, idap, ativo){
        var dados = [{
            'd': {
                'empresa': [{
                    'id': ide
                }],
                'appempresa': [{
                    'id': idap
                }],
                'grupoacesso': [{
                    'id': idg,
                    'ativo': ativo
                }]
            }
        }];

        $.post('./include/TJson.class.php', ({
                                                className: 'GrupoAcesso',
                                                methodName: 'editarBloqueio',
                                                params: dados
                                            }), 
        function(rs){
            alert(rs.r[0].MSG);
        }, 'json')
        .fail(function(jqXHR, status, error){
            var msg = 'Erro ao Editar Registro!\r' + 
                      '- Mensagens \r' +
                      'XHR: ' + jqXHR.reponseXML + '\r' + 
                      'Status: ' + status + '\r' +
                      'Error Type: ' + error;
            alert(msg);
        });
    }
        
    $(document).on('click', '.view', function(){
        var idE   = $(this).attr("ide");
        var idG   = $(this).attr("idg");
        var At    = parseInt($(this).attr("ativo"));
        var idApp = $("#hdIdAppEmp").val();
        
        allEditsFormUsuario.val("");
        allHiddenFormUsuario.val("");
        
        detalhesGrupo(idE, idApp, idG, At);
    });
    
    $(document).on('click', '.unlock', function(){
        var idE   = $(this).attr("ide");
        var idG   = $(this).attr("idg");
        var At    = 0;
        var idApp = $("#hdIdAppEmp").val();
        
        editarBloqueio(idE, idG, idApp, At);
    });
    
    $(document).on('click', '.lock', function(){
        var idE   = $(this).attr("ide");
        var idG   = $(this).attr("idg");
        var At    = 1;
        var idApp = $("#hdIdAppEmp").val();
        
        editarBloqueio(idE, idG, idApp, At);
    });
    
    $(document).on('click', '.groupuserapp', function(){
        var idApp = $(this).attr("appid");
        
        $("#hdIdAppEmp").val(idApp);
        $('.groupuserapp').removeClass('active');
        //$(this).addClass('active');
        
        $(this).button('toggle');
        //setPanelItem("formcategoria");
    });

    $('#btnFechar').click(function(){
        window.open('./app.php?v=appsmenu', '_self');
    });
    
    $("#btnFecharDetalhes").click(function(){
        allEditsFormUsuario.val("");
        allHiddenFormUsuario.val("");
        setPanelItem("formlistagrupos");
    });
    
    $('#btnGruposInativos').click(function(){
        var hasAppEmp = parseInt($('#hdIdAppEmp').val());
        if (hasAppEmp === 0){
            alert("Por favor, selecione um aplicativo ao lado e tente novamente.");
        }
        else {
            var dados = [{
                'd': {
                    'empresa': [{
                        'id': $('#hdIDe').val()
                    }],
                    'appempresa': [{
                        'id': $('#hdIdAppEmp').val()
                    }],
                    'grupoacesso': [{
                        'ativo': '0'
                    }]
                }
            }];

            $.post('./include/TJson.class.php', ({
                                                    className: 'GrupoAcesso',
                                                    methodName: 'findAll',
                                                    params: dados
                                                }), 
            function(rs){
                if (rs.r[0].COD === '201'){
                    alert('AVISO: ' + rs.r[0].MSG);
                }
                else {
                    var t = $('#grdGrupo').DataTable();
                    t.clear().draw();
                    $.each(rs.r, function(idx, value){
                            var controles = "<a href='#' title='Editar Grupo' ide='" + value.id_empresa + "' idg='" + value.id_grupo + "' ativo='0' class='view'>" + 
                                            "<i class='fa fa-pencil-square-o' aria-hidden='true'></i></span></a>" + 
                                            "&nbsp;&nbsp;&nbsp;&nbsp;" +
                                            "<a href='#' title='Desbloquear Grupo' ide='" + value.id_empresa + "' idg='" + value.id_grupo + "' class='unlock'>" + 
                                            "<i class='fa fa-unlock' aria-hidden='true'></i></a>";
                        t.row.add([
                            value.nome,
                            controles
                        ]).draw();
                    });
                }
            }, 'json')
            .fail(function(jqXHR, status, error){
                var msg = 'Erro ao carregar Registros!\r' + 
                          '- Mensagens \r' +
                          'XHR: ' + jqXHR.reponseXML + '\r' + 
                          'Status: ' + status + '\r' +
                          'Error Type: ' + error;
                alert(msg);
            });    
        }
    });

    $('#btnGruposAtivos').click(function(){
        var hasAppEmp = parseInt($('#hdIdAppEmp').val());
        if (hasAppEmp === 0){
            alert("Por favor, selecione um aplicativo ao lado e tente novamente.");
        }
        else {
            var dados = [{
                'd': {
                    'empresa': [{
                        'id': $('#hdIDe').val()
                    }],
                    'appempresa': [{
                        'id': $('#hdIdAppEmp').val()
                    }],
                    'grupoacesso': [{
                        'ativo': '1'
                    }]
                }
            }];

            $.post('./include/TJson.class.php', ({
                                                    className: 'GrupoAcesso',
                                                    methodName: 'findAll',
                                                    params: dados
                                                }), 
            function(rs){
                if (rs.r[0].COD === '201'){
                    alert('AVISO: ' + rs.r[0].MSG);
                }
                else {
                    var t = $('#grdGrupo').DataTable();
                    t.clear().draw();
                    $.each(rs.r, function(idx, value){
                        var controles = "<a href='#' title='Editar Grupo' ide='" + value.id_empresa + "' idg='" + value.id_grupo + "' ativo='1' class='view'>" + 
                                        "<i class='fa fa-pencil-square-o' aria-hidden='true'></i></span></a>" + 
                                        "&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' title='Bloquear Grupo' ide='" + value.id_empresa + "' idg='" + value.id_grupo + "' class='lock'>" + 
                                        "<i class='fa fa-lock' aria-hidden='true'></i></a>";

                        t.row.add([
                            value.nome,
                            controles
                        ]).draw();
                    });
                }
            }, 'json')
            .fail(function(jqXHR, status, error){
                var msg = 'Erro ao carregar Registros!\r' + 
                          '- Mensagens \r' +
                          'XHR: ' + jqXHR.reponseXML + '\r' + 
                          'Status: ' + status + '\r' +
                          'Error Type: ' + error;
                alert(msg);
            });            
        }

    });
    
    $("#btnAdicionarGrupo").click(function(){
        setPanelItem("formdetalhesgrupo");
        $("#hdFormState").val("i"); 
        $("#hdGrpState").val("0");
    });
    
    $("#btnAlterarDetalhes").click(function(){
        $("#btnSalvarDetalhes").show();
        $("#edtNomeGrupo").focus();
        
        setStateModeForm("smfEdit");
    });
    
    $("#btnSalvarDetalhes").click(function(){
        var state = $("#hdFormState").val();
        var dados = [{
            'd': {
                'empresa': [{
                    'id': $('#hdIDe').val()
                }],
                'appempresa': [{
                    'id': $('#hdIdAppEmp').val()
                }],
                'grupoacesso': [{
                    'id': $("#hdIDGrp").val(),
                    'nome': $('#edtNomeGrupo').val(),
                    'ativo': $("#hdGrpState").val()
                }]
            }
        }];

        if (state === "e"){
            $.post('./include/TJson.class.php', ({
                                                    className: 'GrupoAcesso',
                                                    methodName: 'editar',
                                                    params: dados
                                                }), 
            function(rs){
                $("#hdFormState").val("i");
                alert(rs.r[0].MSG);
            }, 'json')
            .fail(function(jqXHR, status, error){
                var msg = 'Erro ao Editar Registro!\r' + 
                          '- Mensagens \r' +
                          'XHR: ' + jqXHR.reponseXML + '\r' + 
                          'Status: ' + status + '\r' +
                          'Error Type: ' + error;
                alert(msg);
            });
        }
        else {
            alert(JSON.stringify(dados));
            $.post('./include/TJson.class.php', ({
                                                    className: 'GrupoAcesso',
                                                    methodName: 'inserir',
                                                    params: dados
                                                }), 
            function(rs){
                $("#hdFormState").val("i");
                alert(rs.r[0].MSG);
            }, 'json')
            .fail(function(jqXHR, status, error){
                var msg = 'Erro ao Inserir Registro!\r' + 
                          '- Mensagens \r' +
                          'XHR: ' + jqXHR.reponseXML + '\r' + 
                          'Status: ' + status + '\r' +
                          'Error Type: ' + error;
                alert(msg);
            });            
        }
    });
});