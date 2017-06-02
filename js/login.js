$(function() {
    function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }
    
    $("#edtUsuario").focusout(function(){
        if (!isEmail($(this).val())){
            alert("Aviso!\n" + "O Email informado não é válido.");
        }
    });
    
    $('#btnConfirmar').click(function(){
        if (!isEmail($("#edtUsuario").val())){
            alert("Aviso!\n" + "O Email informado não é válido.");
            $("#edtUsuario").focus();
        }
        else {
            $("#ddlEmpresa").empty();
            $("#ddlEmpresa").append($("<option>", {value: "0", text: "Aguarde, carregando registros"}));
            
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
                                                    methodName: "findEmailToLogin",
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
        alert('Login cancelado pelo usuário');
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
                                                        methodName: "findUserByEmp",
                                                        params: dados
                                                    }), 
                function(rs){
                    if (rs.r[0].COD === "201"){
                        alert("AVISO!\n" + rs.r[0].MSG);
                    }
                    else {
                        var sEmp  = rs.r[0].nomefantasia;
                        var sUser = rs.r[0].nome;
                        var sTipo = rs.r[0].tipo;
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
                                         lng:        rs.r[0].lng
                                       }];
                        $.post("./include/TJson.class.php", ({className: "TSession", methodName: "setSValue", params: sSession}));
                        var messageWait = function(){
                            $('#app').html("<br><br><br><br><div class='row'><div class='col-md-6 col-md-offset-3'>" + 
                                           "<div class='alert alert-info' role='alert'>" + 
                                           "<h4>Aguarde...</h4>" +
                                           "<p><strong>Iniciando</strong>&nbsp; o carregando das preferências do usuário</div></p>" +
                                           "</div></div>");
                            return $("#app").fadeIn(4000).delay(4000).fadeOut();
                        };
                        $.when(messageWait()).done(function(){
                            $.get('./principal.php', function(rs){
                                $("#app").show();
                                $("#aEmp").text("Empresa: " + sEmp);
                                $("#aUser").text("Usuário Logado: " + sUser);
                                $("#aLogoff").show();
                                $("#aEmp").show();
                                $("#aUser").show();
                                if (sTipo === "ADMN"){
                                    $("#lnkGerenciamento").show();
                                }

                                $('#app').html(rs);
                            })
                            .fail(function(){
                                alert('Erro ao abrir formulário');
                            });
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
        $.get('./view/novasenha.php', function(rs){
            $("#lnkGerenciamento").hide();
            $('#app').html(rs);
        })
        .fail(function(){
            alert('Erro ao abrir formulário');
        });
    });
    
    $('#lnkEfetuarCadastro').click(function(){
        $.get('./view/cadastroempresa.php', function(rs){
            $('#app').html(rs);
        })
        .fail(function(){
            alert('Erro ao abrir formulário');
        });
    });
    
    $("#aLogoff").click(function(){
        $.post("./include/TJson.class.php", ({className: "TSession", methodName: "closeSession"}),
        function(){
            $("#aLogoff").hide();
            $("#aEmp").hide();
            $("#aUser").hide();
            $("#lnkGerenciamento").hide();
            
            $('#app').html("<br><br><br><br><div class='row'><div class='col-md-6 col-md-offset-3'>" + 
                           "<div class='alert alert-success ' role='alert'>" + 
                           "<h4>Logoff Concluido</h4>" +
                           "<p><strong>Ok!</strong>&nbsp;Logoff efetuado com sucesso. " + 
                           "<a href='./' class='alert-link'>Clique aqui para retornar ao login.</a></p>" + 
                           "</div></div></div>");
            //alert("Logoff efetuado com Sucesso.");
            //window.open("./login.php", "_self");
        })
        .fail(function(){
            $("#aLogoff").hide();
            $("#aEmp").hide();
            $("#aUser").hide();
            $("#lnkGerenciamento").hide();
            alert("ERRO!\nFalha ao finalizar a sessão.");
        });
    });
    
    $('#lnkListaUsuarios').click(function(){
        $.get('./view/usuarios.php', function(rs){
            $('#app').html(rs);
        })
        .fail(function(){
            alert('Erro ao abrir formulário');
        });
    });
});