$(function() {
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
    
    $('#btnContinuar').click(function(){
        alert(JSON.stringify(dados));
        /**
        $.get('./view/cadastrologin.php', { em: $("#edtEmail").val(), ide: resultData.r[0].IDE }, function(rs){
            $('#app').html(rs);
        })
        .fail(function(){
            alert('Erro ao abrir formulário');
        });
    
        
        $.post("include/TJson.class.php", ({className: "Empresa", methodName: "inserir", params: dados}),
        function(resultData){
            if (resultData.r[0].COD === "202"){
                alert(resultData.r[0].MSG);
            }
            else {
                alert(resultData.r[0].MSG);
            }
        }, "json")
        .fail(function(jqXHR, status, error){
            var msg = "Erro ao inserir registro!\r\n" + 
                      "- Mensagens \r\n" +
                      "XHR: " + jqXHR.reponseXML + "\r\n" + 
                      "Status: " + status + "\r\n" +
                      "Error Type: " + error;
            alert(msg);
        });
        */
    });

    $('#btnCancelar').click(function(){
        window.open('./', '_self');
        //setPanelItem("formmapa");
    });
});