$(function() {
    var iLat = 0;
    var iLng = 0;
    
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
            zoom: 14
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
            zoom: 18
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

    function getPosition(){
        if (navigator.geolocation){
            navigator.geolocation.getCurrentPosition(showPosition);
        }
        else {
            alert('A Geolocalização não é suportado por este navegador.');
        }
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

    getPosition();
   
    /**
    $("#edtEmail").focusout(function(){
        if (!isEmail($(this).val())){
            alert("Aviso!\n" + "O Email informado não é válido.");
        }
    });
    */
    
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
            var fone = [];

            $("input[id=hddTipoContato]").each(function(i){
                if ($(this).val() !== ""){
                    fone.push({"tipocontato":$(this).val(), "fone":$(this).attr("valuetext"), "principal":$(this).attr("valuepr")});
                }
            });

            var dados = [{
                "d": {
                    "empresa": [{
                        "razaosocial": $("#edtRazaoSocial").val(),
                        "nomefantasia": $("#edtNomeFantasia").val(),
                        "cnpj": $("#edtCnpj").val(),
                        "numero": $("#edtNumero").val(),
                        "complemento": $("#edtComplemento").val(),
                        "site": $("#edtSite").val(),
                        "email": $("#edtEmail").val(),
                        "lat": $("#hdLat").val(),
                        "lon": $("#hdLng").val()
                    }],
                    "endereco":[{
                        "cep": $("#edtCEP").val(),
                        "ibge": $("#hdIBGE").val(),
                        "endereco": $("#edtEndereco").val(),
                        "bairro": $("#edtBairro").val(),
                        "uf": $("#edtUF").val()
                    }],
                    "plano":[{
                        "op": "1"
                    }],
                    "cidade":[{
                        "cidade": $("#edtCidade").val(),
                    }],
                    "fones": fone
                }
            }];

            if (confirm("Os dados informados estão corretos?") == true){
                window.open("./index.php?v=assinaturas&dt2=" + encodeURIComponent(JSON.stringify(dados)), "_self");
                /*
                $.get('./view/assinaturas.php', { dt2: dados }, function(rs){
                    $('#app').html(rs);
                })
                .fail(function(){
                    alert('Erro ao abrir formulário');
                });
                */
            }
        }
    });

    $('#btnCancelar').click(function(){
        window.open('./', '_self');
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
    
    $('#btnAddContatos').click(function(){
        var fonePrincipal = 0;
        if ($.trim($("#edtFone").val()) === ""){
            alert("Informe o contato e tente novamente.");
            $("#edtFone").focus();
        }
        else {
            if ($("#chkFonePrincipal").is(":checked")) {
                fonePrincipal = 1;
            }
            var chkSelect  = "<input type='hidden' name='hddTipoContato[]' id='hddTipoContato' value='" + $("#ddlTipoContato option:selected").val() + "' " +
                             "valuetext='" + $("#edtFone").val() + "' valuepr='" + fonePrincipal + "'>" + 
                             "<input type='checkbox' name='chkFone[]' id='chkFone' valueitem='" + $("#edtFone").val() + "'>";
         
            var t = $("#grdContatos").DataTable();
            t.row.add([
                    $("#ddlTipoContato option:selected").text(),
                    $("#edtFone").val(),
                    chkSelect
            ]).draw();

            $("#ddlTipoContato").val("0000");
            $("#edtFone").val("");
            $("#ddlTipoContato").focus();
            $("#chkFonePrincipal").prop("checked", false);
        }
    });

    $('#btnRetirarMarcador').click(function(){
        mapa.RemoveLocations(0, true);
        /*
        if (navigator.geolocation){
            navigator.geolocation.getCurrentPosition(function(pos){
                var d = [{
                    //map_div: '#gmap',
                    show_markers: false,
                    lat: pos.coords.latitude,
                    lon: pos.coords.longitude,
                    //title: 'Sua Localização Atual',
                    //html: [
                    //        '<h3>Teste</h3>',
                    //        '<p>Fone: (88) 9.9999.9999</p>',
                    //        '<p>Endereco: Rua teste, 2929</p>'
                    //      ].join(''),
                    zoom: 14
                }];
            
                //d[0].lat = pos.coords.latitude;
                //d[0].lon = pos.coords.longitude;
                
                $("#hdLat").val(pos.coords.latitude);
                $("#hdLng").val(pos.coords.longitude);

                mapa.SetLocations(d, true);
            });
        }
        else {
            alert('A Geolocalização não é suportado por este navegador');
        }
        */
    });

});