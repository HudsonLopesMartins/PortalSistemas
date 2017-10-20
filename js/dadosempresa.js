$(function() {
    var iLat        = 0;
    var iLng        = 0;
    var execUpdates = [{
                "d": {
                    "pendencia": "0",
                    "insert":[{
                        "fones": []
                    }],
                    "delete":[{
                        "fones": []
                    }]
                }
    }];
    /*
    var itemIndex  = 0;
    var panelIndex = [
        "formcadastro",
        "formmapa"
    ];
    */

    $('#edtCEP').mask('99.999-999', {placeholder:'_'});
    $('#edtCnpj').mask('99.999.999/9999-99', {placeholder:'_'});
    $('#edtFone').mask('(99)9.9999-9999', {placeholder:'_'});
    $("#grdContatos").DataTable({
        language: {
            url: "./libs/datatables/pt-BR/Portuguese-Brasil.json"
        },
        scrollY:        "90px",
        scrollCollapse: true,
        paging:         false,
        ordering:       false,
        searching:      false
    });
    
    
    //Usando o maplace 92
    $('#gmap').height($(window).height() - 370);

    var data = [{
            //map_div: '#gmap',
            //show_markers: false,
            lat: 0,
            lon: 0,
            //title: 'Sua Localização Atual',
            //html: [
            //        '<h3>Teste</h3>',
            //        '<p>Fone: (88) 9.9999.9999</p>',
            //        '<p>Endereco: Rua teste, 2929</p>'
            //      ].join(''),
            zoom: 16
    }];

    var mapa = new Maplace({
        map_div: '#gmap',
        listeners: {
            click: function(map, event){
                var r = confirm("A localização selecionada está de acordo com o endereço informado?");
                if (r === true){
                    setPosition(map.latLng.lat(), map.latLng.lng());
                }
                else {
                    alert("Ok, a localização atual não será modificada.");
                }
            }
        }
    });

    function setPosition(iLat, iLng){
        var d = [{
            lat: iLat,
            lon: iLng,
            //title: 'Sua Localização Atual',
            //html: [
            //        '<h3>Teste</h3>',
            //        '<p>Fone: (88) 9.9999.9999</p>',
            //        '<p>Endereco: Rua teste, 2929</p>'
            //      ].join(''),
            zoom: 16
        }];

        d[0].lat = iLat;
        d[0].lon = iLng;
        
        $("#hdLat").val(iLat);
        $("#hdLng").val(iLng);

        mapa.SetLocations(d, true);
    }

    function showPosition(position){
        iLat = position.coords.latitude;
        iLng = position.coords.longitude;

        data[0].lat = iLat;
        data[0].lon = iLng;
        
        $("#hdLat").val(iLat);
        $("#hdLng").val(iLng);

        mapa.SetLocations(data, true);
    }
    
    /*
    function setPanelItem(panelitem){
        $('.carousel').carousel(panelIndex.indexOf(panelitem));
        itemIndex = panelIndex.indexOf(panelitem);
    }
    
    function getPanelItem(){
        return itemIndex;
    }
    */
    
    function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }

    setPosition($("#hdLat").val(), $("#hdLng").val());
   
    /**
    $("#edtEmail").focusout(function(){
        if (!isEmail($(this).val())){
            alert("Aviso!\n" + "O Email informado não é válido.");
        }
    });
    */
   
    function findPosAddress(addr){
        var iLat = 0;
        var iLng = 0;
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address': addr}, function(results, status) {
            if (status === "OK"){
                iLat = results[0].geometry.location.lat();
                iLng = results[0].geometry.location.lng();
                
                setPosition(iLat, iLng);
            }
            else {
                alert("Não foi possível localizar o endereço. Motivo: " + status);
            }
        });
    }
    
    function findAddress(addr){
        var iLat = 0;
        var iLng = 0;
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address': addr}, function(results, status) {
            if (status === "OK"){
                var sAddress = results[0].formatted_address;
                var sE1 = sAddress.split("-");
                var sE2 = sE1[1].split(",");
                var sE3 = sE1[2].split(",");

                $("#edtEndereco").val($.trim(sE1[0]));
                $("#edtBairro").val($.trim(sE2[0]));
                $("#edtCidade").val($.trim(sE2[1]));
                $("#edtUF").val($.trim(sE3[0]));
                
                iLat = results[0].geometry.location.lat();
                iLng = results[0].geometry.location.lng();
                
                setPosition(iLat, iLng);
            }
            else {
                alert("Não foi possível localizar o endereço. Motivo: " + status);
            }
        });
    }
    
    $("#ddlTipoPessoa").change(function(){
        var tipo = $(this).val();
        $('#edtCnpj').val("");
        switch (tipo){
            case "N":
            case "J":
                //alert("Pessoa Juridica");
                $('#edtCnpj').prop("placeholder", "CNPJ");
                $('#edtCnpj').mask('99.999.999/9999-99', {placeholder:'_'});
                break;
            case "F":
                //alert("Pessoa Fisica");
                $('#edtCnpj').prop("placeholder", "CPF");
                $('#edtCnpj').mask('999.999.999-99', {placeholder:'_'});
                break;
            default:
                $('#edtCnpj').prop("placeholder", "CNPJ");
                $('#edtCnpj').mask('99.999.999/9999-99', {placeholder:'_'});
                break;
        }
    });
    
    $("#ddlTipoContato").change(function(){
        var tipo = $(this).val();
        switch (tipo){
            case "RSDL":
            case "CMCL":
            case "FAXC":
            case "FAXR":
                $('#edtFone').mask('(99)9999-9999', {placeholder:'_'});
                break;
            case "CELL":
                $('#edtFone').mask('(99)9.9999-9999', {placeholder:'_'});
                break;
            default:
                $('#edtFone').mask('(99)9999-9999', {placeholder:'_'});
                break;
        }
    });
    
    $('#btnConfirmar').click(function(){
        if (!isEmail($("#edtEmail").val())){
            alert("Aviso!\n" + "O Email informado não é válido.");
            $("#edtEmail").focus();
        }
        else {
            var dados = [{
                "d": {
                    "empresa": [{
                        "id": $("#hdIde").val(),
                        "razaosocial": $("#edtRazaoSocial").val(),
                        "nomefantasia": $("#edtNomeFantasia").val(),
                        "cnpj": $("#edtCnpj").val(),
                        "numero": $("#edtNumero").val(),
                        "complemento": $("#edtComplemento").val(),
                        "site": $("#edtSite").val(),
                        "email": $("#edtEmail").val(),
                        "lat": $("#hdLat").val(),
                        "lng": $("#hdLng").val()
                    }],
                    "endereco":[{
                        "cep": $("#edtCEP").val(),
                        "ibge": $("#hdIBGE").val(),
                        "endereco": $("#edtEndereco").val(),
                        "bairro": $("#edtBairro").val(),
                        "uf": $("#edtUF").val()
                    }],
                    "cidade":[{
                        "cidade": $("#edtCidade").val(),
                    }]
                }
            }];

            $.post("./include/TJson.class.php", ({
                                                    className: "Empresa",
                                                    methodName: "editar",
                                                    params: dados
                                                }), 
            function(rs){
                if (rs.r[0].COD === "203"){
                    alert(rs.r[0].MSG);
                }
            }, "json")
            .fail(function(jqXHR, status, error){
                var msg = "Erro ao Salvar Alterações!\r\n" + 
                          "- Mensagens \r\n" +
                          "XHR: " + jqXHR.reponseXML + "\r\n" + 
                          "Status: " + status + "\r\n" +
                          "Error Type: " + error;
                alert(msg);
            });
        }
    });

    $('#btnFechar').click(function(){
        window.open('./app.php?v=appsmenu', '_self');
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
                    
                    findPosAddress(jsonCEP.logradouro + ", " + jsonCEP.bairro + ", " +
                                   jsonCEP.localidade + ", " + jsonCEP.uf);
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
    
    $('#btnLocalizarMapa').click(function(){
        var addr = prompt("Informe o endereço completo", "rua, bairro, cidade, estado");
        if (addr !== null) {
            findAddress(addr);
            $("#edtNumero").focus();
        }
    });
    
    $('#btnAddContatos').click(function(){
        var tagPrincipal  = "&nbsp;";
        var fonePrincipal = 0;
        if ($.trim($("#edtFone").val()) === ""){
            alert("Informe o contato e tente novamente.");
            $("#edtFone").focus();
        }
        else {
            if ($("#chkFonePrincipal").is(":checked")) {
                fonePrincipal = 1;
                
                execUpdates[0].d.pendencia = "1";
                execUpdates[0].d.insert[0].fones.push({"id_empresa":$("#hdIde").val(), 
                                                       "id":0, 
                                                       "tipocontato":$("#ddlTipoContato option:selected").val(), 
                                                       "fone":$("#edtFone").val(), 
                                                       "principal":1});
            }
            else {
                execUpdates[0].d.pendencia = "1";
                execUpdates[0].d.insert[0].fones.push({"id_empresa":$("#hdIde").val(), 
                                                       "id":0, 
                                                       "tipocontato":$("#ddlTipoContato option:selected").val(), 
                                                       "fone":$("#edtFone").val(), 
                                                       "principal":0});
            }
            var chkSelect  = "<input type='hidden' name='hddTipoContato[]' id='hddTipoContato' value='" + $("#ddlTipoContato option:selected").val() + "' " +
                             "valuetext='" + $("#edtFone").val() + "' valuepr='" + fonePrincipal + "'>" + 
                             "<input type='checkbox' name='chkFone[]' id='chkFone' valueid='0' valueitem='" + $("#edtFone").val() + "'>";
                
            if (fonePrincipal === 1){
                var tagPrincipal  = "<span class='glyphicon glyphicon-tag' aria-hidden='true'></span>";
            }
         
            var t = $("#grdContatos").DataTable();
            t.row.add([
                    $("#ddlTipoContato option:selected").text(),
                    $("#edtFone").val(),
                    chkSelect,
                    tagPrincipal
            ]).draw();

            $("#ddlTipoContato").val("0000");
            $("#edtFone").val("");
            $("#ddlTipoContato").focus();
            $("#chkFonePrincipal").prop("checked", false);
        }
    });
    
    $('#btnRemoveContatos').click(function(){
        $("input[id=chkFone]").each(function(i){
            if ($(this).is(":checked")){
                if ($(this).attr("valueid") !== "0"){
                    execUpdates[0].d.pendencia = "1";
                    execUpdates[0].d.delete[0].fones.push({"id":$(this).attr("valueid"), "id_empresa":$("#hdIde").val()});
                }
                var t = $("#grdContatos").DataTable();
                t.row($(this).parents('tr')).remove().draw(false);
            }
        });
    });
    
    $('#btnRemoveTodos').click(function(){
       $("input[id=chkFone]").each(function(i){
            if ($(this).attr("valueid") !== "0"){
                execUpdates[0].d.pendencia = "1";
                execUpdates[0].d.delete[0].fones.push({"id":$(this).attr("valueid"), "id_empresa":$("#hdIde").val()});
            }
        });
        
        var t = $("#grdContatos").DataTable();
        //1. rows().remove(): irá remover todas as linhas
        //2. row().remove(): remove apenas uma linha específica, neste caso a ultima
        t.rows().remove().draw(false);
        //3. clear(): equivale ao exemplo 1
        //t.clear().draw(false);
    });
    
    $('#btnSalvarFone').click(function(){
        if (execUpdates[0].d.pendencia === "0"){
            alert("Não há alterações a serem executadas.");
        }
        else {
            $.post("./include/TJson.class.php", ({
                                                    className: "FoneEmpresa",
                                                    methodName: "execUpdates",
                                                    params: execUpdates
                                                }), 
            function(rs){
                if (rs.r[0].COD === "206"){
                    execUpdates[0].d.pendencia = "0";
                    execUpdates[0].d.delete[0].fones.splice(0);
                    execUpdates[0].d.insert[0].fones.splice(0);

                    alert(rs.r[0].MSG);
                }
            }, "json")
            .fail(function(jqXHR, status, error){
                var msg = "Erro ao Salvar Alterações!\r\n" + 
                          "- Mensagens \r\n" +
                          "XHR: " + jqXHR.reponseXML + "\r\n" + 
                          "Status: " + status + "\r\n" +
                          "Error Type: " + error;
                alert(msg);
            });    
        }
    });

});