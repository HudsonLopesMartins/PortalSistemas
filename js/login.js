$(function() {
    function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }
    
    $('#btnCarregarEmpresas').click(function(){
        if (!isEmail($("#edtUsuario").val())){
            alert("Aviso!\n" + "O Email informado não é válido.");
            $("#edtUsuario").focus();
        }
        else {
            $("#ddlEmpresa").empty();
            $("#ddlEmpresa").append($("<option>", {value: "0", text: "Aguarde, carregando registros..."}));
            
            var dados = [{
                    usuario: {
                        email: $("#edtUsuario").val(),
                        senha: $("#edtSenha").val(),
                        crp  : 1
                    },
                    toJson: true
            }];

            $.post("./include/TJson.class.php", ({
                                                    className: "Usuario",
                                                    methodName: "localizarEmpresasUsuario",
                                                    params: dados
                                                }), 
            function(rs){
                if (rs.r[0].COD === "201"){
                    $("#ddlEmpresa").empty();
                    $("#ddlEmpresa").append($("<option>", {value: "0", text: "Não foi possível carregar registros"}));
                    alert("AVISO: " + rs.r[0].MSG);
                }
                else {
                    $("#ddlEmpresa").empty();
                    $("#ddlEmpresa").append($("<option>", {value: "0", text: "Selecione uma Empresa"}));
                    $.each(rs.r, function(idx, value){
                        $("#ddlEmpresa").append($("<option>", {value: value.id_empresa, text: value.nomefantasia}));
                    });
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
        }
    });

    $('#btnCancelar').click(function(){
        $('#edtUsuario').val('');
        $('#edtSenha').val('');
        $('#ddlEmpresa').val('0');
        
        $('#edtUsuario').focus();
    });
    
    $('#btnEfetuarLogin').click(function(){
        if ($("#ddlEmpresa").val() === "0"){
            alert("AVISO!\n" + "Informe os dados corretamente para efetuar o login.");
        }
        else {
            if (!isEmail($("#edtUsuario").val())){
                alert("Aviso!\n" + "O Email informado não é válido.");
                $("#edtUsuario").focus();
            }
            else {
                var dados = [{
                        usuario: {
                            email: $("#edtUsuario").val(),
                            pass:  $("#edtSenha").val(),
                            ide:   $("#ddlEmpresa").val(),
                            crp:   1
                        },
                        toJson: true
                }];

                $.post("./include/TJson.class.php", ({
                                                        className: "Usuario",
                                                        methodName: "localizarDadosLogin",
                                                        params: dados
                                                    }), 
                function(rs){
                    if (rs.r[0].COD === "201"){
                        alert("AVISO!\n" + rs.r[0].MSG);
                    }
                    else {
                        var sSession = [{
                                         id_empresa: rs.r[0].id_empresa,
                                         id_grupo:   rs.r[0].id_grupo,
                                         id_usuario: rs.r[0].id,
                                         email:      rs.r[0].email,
                                         nome:       rs.r[0].nome,
                                         grupo:      rs.r[0].grupo,
                                         status:     "on",
                                         mdsnh:      rs.r[0].mdsnh,
                                         lat:        rs.r[0].lat,
                                         lng:        rs.r[0].lng,
                                         nfantasia:  rs.r[0].nomefantasia,
                                         nusuario:   rs.r[0].nome,
                                         tusuario:   rs.r[0].tipo
                                       }];
                        $.post("./include/TJson.class.php", ({className: "TSession", methodName: "setSValue", params: sSession}));
                        var messageWait = function(){
                            $('#app').html("<br><br><br><br>" + 
                                           "<div class='row'><div class='col-md-4 col-md-offset-4'>" + 
                                           "<div class='alert alert-info' role='alert'>" + 
                                           "<h4>Aguarde...</h4>" +
                                           "<p><i class='fa fa-spinner fa-pulse fa-1x fa-fw'></i><strong>Iniciando</strong>&nbsp;o carregamento das preferências do usuário</div></p>" +
                                           "</div></div>");
                            return $("#app").fadeIn(4000).delay(4000).fadeOut();
                        };
                        $.when(messageWait()).done(function(){
                            window.open("./principal.php?v=appsmenu", "_self");
                            /*
                            $.get('./principal.php', function(rs){
                                $("#app").fadeIn();
                                $('#app').html(rs);
                            })
                            .fail(function(){
                                alert('Erro ao abrir formulário');
                                window.open("./", "_self");
                            });
                            */
                        });
                    }
                }, "json")
                .fail(function(jqXHR, status, error){
                    var msg = "Erro ao carregar página!\r\n" + 
                              "- Mensagens \r\n" +
                              "XHR: " + jqXHR.reponseXML + "\r\n" + 
                              "Status: " + status + "\r\n" +
                              "Error Type: " + error;
                    alert(msg);
                });
            }
        }
    });
    
    $('#lnkEnviarNovaSenha').click(function(){
        window.open("./index.php?v=novasenha", "_self");
    });
    
    $('#lnkEfetuarCadastro').click(function(){
        window.open("./index.php?v=cadastroempresa&libs=dtables", "_self");
        /*
        $.get('./view/cadastroempresa.php', function(rs){
            $('#app').html(rs);
        })
        .fail(function(){
            alert('Erro ao abrir formulário');
        });
        */
    });
    
});