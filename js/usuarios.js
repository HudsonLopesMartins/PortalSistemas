$(function() {
    var windowMap;
    var itemIndex  = 0;
    var panelIndex = [
        "formlistausuarios",
        "formdetalhesusuario",
        "formloginusuario"
    ];
    
    var allEditsFormUsuario = $([]).add($("#edtNome"))
                                   .add($("#edtCPF"))
                                   .add($("#edtEndereco"))
                                   .add($("#edtNumero"))
                                   .add($("#edtBairro"))
                                   .add($("#edtComplemento"))
                                   .add($("#edtCEP"))
                                   .add($("#edtCidade"))
                                   .add($("#edtUF"))
                                   .add($("#edtEmailPessoal"))
                                   .add($("#edtEmailLogin"))
                                   .add($("#edtSenhaAntiga"))
                                   .add($("#edtNovaSenha"))
                                   .add($("#edtCheckSenha"));
    var allHiddenFormUsuario = $([]).add($("#hdIDGrp"))
                                    .add($("#hdIDu"))
                                    .add($("#hdIDDUs"))
                                    .add($("#hdIDEnd"))
                                    .add($("#hdIDCid"))
                                    .add($("#hdIDEst"))
                                    .add($("#hdLat"))
                                    .add($("#hdLng"))
                                    .add($("#hdIBGE"));
    $("#ddlCidade").hide();
    $("#ddlUF").hide();

    $("#dlgStatus").hide();
    $('#edtCEP').mask('99.999-999', {placeholder:'_'});
    $('#edtCPF').mask('999.999.999-99', {placeholder:'_'});

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
    
    function setPanelItem(panelitem){
        $('.carousel').carousel(panelIndex.indexOf(panelitem));
        $('.carousel').carousel('pause');
        itemIndex = panelIndex.indexOf(panelitem);
    }
    
    function getPanelItem(){
        return itemIndex;
    }
    
    function carregarUF(){
        var dados = [{
                'toJson': true
            }];
        $.post('./include/TJson.class.php', ({
                                                className: 'Estados',
                                                methodName: 'getAllOrdBySigla',
                                                params: dados
                                            }), 
        function(rs){
            if (rs.r[0].COD === '201'){
                alert('AVISO: ' + rs.r[0].MSG);
            }
            else {
                $("#ddlUF").empty();
                $("#ddlUF").append($("<option>", {value: "0", text: "Estados"}));
                $.each(rs.r, function(idx, value){
                    $("#ddlUF").append($("<option>", {value: value.id, text: value.sigla}));
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
    
    function carregarCidades(sigla, selectid){
        var id = 0;
        if(selectid !== 0){
            id = parseInt(selectid);
        }
        
        $("#ddlCidade").empty();
        $("#ddlCidade").append($("<option>", {value: "0", text: "Aguarde, carregando a lista de Cidades"}));
        
        var dados = [{
                'd':{
                    'estado': [{
                            'uf': sigla
                    }]
                },
                'toJson': true
            }];
        $.post('./include/TJson.class.php', ({
                                                className: 'Cidade',
                                                methodName: 'findAllByUf',
                                                params: dados
                                            }), 
        function(rs){
            if (rs.r[0].COD === '201'){
                alert('AVISO: ' + rs.r[0].MSG);
            }
            else {
                $("#ddlCidade").empty();
                $("#ddlCidade").append($("<option>", {value: "0", text: "Selecione uma Cidade"}));
                $.each(rs.r, function(idx, value){
                    $("#ddlCidade").append($("<option>", {value: value.id, text: value.nome}));
                });
                $("#ddlCidade").val(id);
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
    
    function carregarGruposLogin(idemp, selectid){
        var id = 0;
        if(selectid !== 0){
            id = parseInt(selectid);
        }
        
        $("#ddlGrupoLogin").empty();
        $("#ddlGrupoLogin").append($("<option>", {value: "0", text: "Aguarde, carregando a lista dos Grupos"}));
        
        var dados = [{
                'd':{
                    'estado': [{
                            'uf': idemp
                    }]
                },
                'toJson': true
            }];
        /**
         * 
        $.post('./include/TJson.class.php', ({
                                                className: 'Cidade',
                                                methodName: 'findAllByUf',
                                                params: dados
                                            }), 
        function(rs){
            if (rs.r[0].COD === '201'){
                alert('AVISO: ' + rs.r[0].MSG);
            }
            else {
                $("#ddlGrupoLogin").empty();
                $("#ddlGrupoLogin").append($("<option>", {value: "0", text: "Selecione um Grupo"}));
                $.each(rs.r, function(idx, value){
                    $("#ddlGrupoLogin").append($("<option>", {value: value.id, text: value.nome}));
                });
                $("#ddlGrupoLogin").val(id);
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
        */
    }
    
    function detalhesUsuario(ide, idu){

        var dados = [{
            'd': {
                'empresa': [{
                    'id': ide
                }],
                'usuario': [{
                    'id': idu
                }]
            }
        }];
    
        $("#ddlCidade").hide();
        $("#ddlUF").hide();
        
        $("#edtCidade").show();
        $("#edtUF").show();
    
        setPanelItem("formdetalhesusuario");
        
        $("#dlgStatus").show();
        var messageWait = function(){
            return $("#dlgStatus").fadeIn(3000).delay(3000).fadeOut();
        };
        $.when(messageWait()).done(function(){
            $.post("./include/TJson.class.php", ({
                                                    className: "Usuario",
                                                    methodName: "viewDetalhesUsuario",
                                                    params: dados
                                                }), 
            function(rs){
                if (rs.r[0].COD === "201"){
                    alert("AVISO: " + rs.r[0].MSG);
                }
                else {
                    //$("#edtRamal").val(rs.r[0].ramal);
                    //$("#edtEmail").val(rs.r[0].email_usuario);
                    $("#edtNome").val(rs.r[0].nome_completo);
                    $("#edtCPF").val(rs.r[0].cpf);
                    $("#edtEndereco").val(rs.r[0].endereco);
                    $("#edtNumero").val(rs.r[0].numero);
                    $("#edtBairro").val(rs.r[0].bairro);
                    $("#edtComplemento").val(rs.r[0].complemento);
                    $("#edtCEP").val(rs.r[0].cep);
                    $("#edtCidade").val(rs.r[0].nome_cidade);
                    $("#edtUF").val(rs.r[0].sigla);
                    $("#edtEmailPessoal").val(rs.r[0].email_pessoal);

                    //$("#hdIDe").val(rs.r[0].id_empresa);
                    $("#hdIDGrp").val(rs.r[0].id_grupo);
                    $("#hdIDu").val(rs.r[0].id_usuario);
                    $("#hdIDDUs").val(rs.r[0].id_dadousuario);
                    $("#hdIDEnd").val(rs.r[0].id_endereco);
                    $("#hdIDCid").val(rs.r[0].id_cidade);
                    $("#hdIDEst").val(rs.r[0].id_uf);
                    $("#hdLat").val(rs.r[0].lat);
                    $("#hdLng").val(rs.r[0].lng);
                    $("#hdIBGE").val(rs.r[0].ibge);
                    
                    $("#hdFormState").val("e");
                    
                    $("#ddlUF").val(rs.r[0].id_uf);
                    carregarCidades($("#ddlUF option:selected").text(), rs.r[0].id_cidade);
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
        //window.open("./view/detalhesusuario.php?m=" + mode + "&ide=" + ide + 
        //            "&idu=" + idu, "_blank", "toolbar=0,titlebar=0,menubar=0,width=1000,height=600");
    }
    
    function loginUsuario(ide, idu){

        var dados = [{
            'd': {
                'empresa': [{
                    'id': ide
                }],
                'usuario': [{
                    'id': idu
                }]
            }
        }];
    
        setPanelItem("formloginusuario");
        
        $("#dlgStatusLogin").show();
        var messageWait = function(){
            return $("#dlgStatusLogin").fadeIn(3000).delay(3000).fadeOut();
        };
        $.when(messageWait()).done(function(){
            $.post("./include/TJson.class.php", ({
                                                    className: "Usuario",
                                                    methodName: "viewDetalhesUsuario",
                                                    params: dados
                                                }), 
            function(rs){
                if (rs.r[0].COD === "201"){
                    alert("AVISO: " + rs.r[0].MSG);
                }
                else {
                    $("#edtEmailLogin").val(rs.r[0].email_usuario);
                    $("#hdIDGrp").val(rs.r[0].id_grupo);
                    $("#hdIDu").val(rs.r[0].id_usuario);
                    
                    carregarGruposLogin(0, 0);
                    
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
    }
    
    carregarUF();
    
    $(document).on('click', '.view', function(){
        var idE = $(this).attr("ide");
        var idU = $(this).attr("idu");
        
        allEditsFormUsuario.val("");
        allHiddenFormUsuario.val("");
        
        detalhesUsuario(idE, idU);
        //setPanelItem("formcategoria");
    });
    
    $(document).on('click', '.login', function(){
        var idE = $(this).attr("ide");
        var idU = $(this).attr("idu");
        
        loginUsuario(idE, idU);
        //setPanelItem("formloginusuario");
    });

    $('#btnFechar').click(function(){
        window.open('./principal.php?v=appsmenu', '_self');
        /*
        $.get('./principal.php', function(rs){
            $('#app').html(rs);
        })
        .fail(function(){
            alert('Erro ao abrir formulário');
        });
        */
    });
    
    $("#btnFecharDetalhes").click(function(){
        allEditsFormUsuario.val("");
        allHiddenFormUsuario.val("");
        setPanelItem("formlistausuarios");
    });
    
    $("#btnFecharLoginUsuario").click(function(){
        setPanelItem("formlistausuarios");
    });

    $('#btnUsersInativos').click(function(){
        var dados = [{
            'd': {
                'empresa': [{
                    'id': $('#hdIDe').val()
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
                    if (value.tipo === "DFLT"){
                        var controles = "<a href='#' title='Vizualizar os Detalhes' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='view'>" + 
                                        "<i class='fa fa-address-book' aria-hidden='true'></i></span></a>" + 
                                        "&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' title='Vizualizar dados do Login' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='login'>" + 
                                        "<i class='fa fa-key' aria-hidden='true'></i></a>" + 
                                        "&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' title='Desbloquear Usuário' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='unlock'>" + 
                                        "<i class='fa fa-unlock' aria-hidden='true'></i></a>" +
                                        "&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' title='Tornar Usuário Administrador' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='up'>" + 
                                        "<i class='fa fa-universal-access' aria-hidden='true'></i></a>";
                    }
                    else {
                        var controles = "<a href='#' title='Vizualizar os Detalhes' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='view'>" + 
                                        "<i class='fa fa-address-book' aria-hidden='true'></i></span></a>" + 
                                        "&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' title='Vizualizar dados do Login' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='login'>" + 
                                        "<i class='fa fa-key' aria-hidden='true'></i></a>" + 
                                        "&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' title='Desbloquear Usuário' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='unlock'>" + 
                                        "<i class='fa fa-unlock' aria-hidden='true'></i></a>" +
                                        "&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' title='Retirar Permissões de Administrador' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='down'>" + 
                                        "<i class='fa fa-user' aria-hidden='true'></i></a>";
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
            var msg = 'Erro ao carregar Registros!\r' + 
                      '- Mensagens \r' +
                      'XHR: ' + jqXHR.reponseXML + '\r' + 
                      'Status: ' + status + '\r' +
                      'Error Type: ' + error;
            alert(msg);
        });

    });

    $('#btnUsersAtivos').click(function(){
        var dados = [{
            'd': {
                'empresa': [{
                    'id': $('#hdIDe').val()
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
                    if (value.tipo === "DFLT"){
                        var controles = "<a href='#' title='Vizualizar os Detalhes' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='view'>" + 
                                        "<i class='fa fa-address-book' aria-hidden='true'></i></span></a>" + 
                                        "&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' title='Vizualizar dados do Login' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='login'>" + 
                                        "<i class='fa fa-key' aria-hidden='true'></i></a>" + 
                                        "&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' title='Bloquear Usuário' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='lock'>" + 
                                        "<i class='fa fa-lock' aria-hidden='true'></i></a>" +
                                        "&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' title='Tornar Usuário Administrador' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='up'>" + 
                                        "<i class='fa fa-universal-access' aria-hidden='true'></i></a>";
                    }
                    else {
                        var controles = "<a href='#' title='Vizualizar os Detalhes' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='view'>" + 
                                        "<i class='fa fa-address-book' aria-hidden='true'></i></span></a>" + 
                                        "&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' title='Vizualizar dados do Login' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='login'>" + 
                                        "<i class='fa fa-key' aria-hidden='true'></i></a>" + 
                                        "&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' title='Bloquear Usuário' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='lock'>" + 
                                        "<i class='fa fa-lock' aria-hidden='true'></i></a>" +
                                        "&nbsp;&nbsp;&nbsp;&nbsp;" +
                                        "<a href='#' title='Retirar Permissões de Administrador' ide='" + value.id_empresa + "' idu='" + value.id_usuario + "' class='down'>" + 
                                        "<i class='fa fa-user' aria-hidden='true'></i></a>";
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
            var msg = 'Erro ao carregar Registros!\r' + 
                      '- Mensagens \r' +
                      'XHR: ' + jqXHR.reponseXML + '\r' + 
                      'Status: ' + status + '\r' +
                      'Error Type: ' + error;
            alert(msg);
        });
    });
    
    $("#btnAlterarDetalhes").click(function(){
        $("#btnSalvarDetalhes").show();
        $("#edtEmail").focus();
        
        $("#ddlCidade").show();
        $("#ddlUF").show();
        
        $("#edtCidade").hide();
        $("#edtUF").hide();
        
        setStateModeForm("smfEdit");
    });
    
    $("#btnSalvarDetalhes").click(function(){
        var state = $("#hdFormState").val();
        var dados = [{
            'd': {
                'empresa': [{
                        'id': $('#hdIDe').val()
                }],
                'usuario':[{
                        'id':   $("#hdIDu").val(),
                        'nome': $("#edtNome").val()
                }],
                'dadosusuario': [{
                        'id':           $("#hdIDDUs").val(),
                        'nome':         $("#edtNome").val(),
                        'cpf':          $("#edtCPF").val(),
                        'numero':       $("#edtNumero").val(),
                        'complemento':  $("#edtComplemento").val(),
                        'emailpessoal': $("#edtEmailPessoal").val(),
                        'lat':          $("#hdLat").val(),
                        'lng':          $("#hdLng").val()
                }],
                'endereco':[{
                        'cep':      $("#edtCEP").val(),
                        'ibge':     $("#hdIBGE").val(),
                        'endereco': $("#edtEndereco").val(),
                        'bairro':   $("#edtBairro").val(),
                        'uf':       $("#edtUF").val()
                }],
                'cidade':[{
                        'cidade': $("#edtCidade").val()
                }]
            }
        }];
    
        //window.open("./index.php?v=assinaturas&dt2=" + encodeURIComponent(JSON.stringify(dados)), "_self", "width=600,height=300");
        if (state === "e"){
            $.post('./include/TJson.class.php', ({
                                                    className: 'Usuario',
                                                    methodName: 'editarDetalhesUsuario',
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
    });
    
    $('#btnConsultarCEP').click(function(){
        var cep = $("#edtCEP").val();
        if (cep !== ""){
            cep = cep.replace(".", "");
            $.getJSON("http://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(jsonCEP){
                if (!("erro" in jsonCEP)){
                    $("#edtEndereco").val(jsonCEP.logradouro);
                    $("#edtBairro").val(jsonCEP.bairro);
                    $("#edtCidade").val(jsonCEP.localidade);
                    $("#edtUF").val(jsonCEP.uf);
                    $("#hdIBGE").val(jsonCEP.ibge);
                    
                    $("#edtNumero").focus();
                }
                else {
                    $("#edtCEP").val("");
                    $("#hdIBGE").val("");
                    alert("CEP não localizado.");
                }
            });
        }
        else {
            $("#edtCEP").val("");
            $("#hdIBGE").val("");
        }
    });
    
    $("#ddlUF").change(function(){
        var s = $("#ddlUF option:selected").text();
        $("#hdIDEst").val($("#ddlUF option:selected").val());
        $("#edtUF").val(s);
        carregarCidades(s, 0);
    });
    
    $("#ddlCidade").change(function(){
        var c = $("#ddlCidade option:selected").text();
        $("#hdIDCid").val($("#ddlCidade option:selected").val());
        $("#edtCidade").val(c);
    });
    
    $("#btnTeste").click(function(){
        windowMap = window.open("", "myWindow", "width=600,height=300");
        windowMap.document.write("<button type='button' onclick='window.close(); document.getElementById(\"btnFecharJanela\").value = document.getElementById(\"firstname\").value)'>Click Me!</button>");
        windowMap.document.write("<input type='text' id='firstname'>");
        
        //var t = windowMap.parent.document.getElementById("firstname");
        //alert(t.value);
    });

    $("#btnFecharJanela").click(function(){
        var t = windowMap.parent.document.getElementById("firstname");
        alert(t.value);
        
        windowMap.close();
    });
});