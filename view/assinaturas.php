<?php
    include_once './forms/TFormPeriodoAssinatura.php';
    
    $frmAssinatura = new TFormPeriodoAssinatura();
    $frmAssinatura->addJS("$(function() {"
                        . "  var dados      = " . $_GET["dt2"] . ";"
                        . "  var valorTotal = 0; "
                        
                        . "  function isEmpty(value){ "
                        . "     return (value == null || value.length === 0 || value == ''); "
                        . "  } "

                        . "  $('input[type=checkbox]').change(function(i){"
                        . "     if ($(this).is(':checked')){"
                        . "         valorTotal = parseFloat(valorTotal) + parseFloat($(this).attr('valuemonthprod'));"
                        . "         $('#pValorTotal').text('R$ ' + valorTotal.toFixed(2));"
                        . "     }"
                        . "     else {"
                        . "         valorTotal = parseFloat(valorTotal) - parseFloat($(this).attr('valuemonthprod'));"
                        . "         if (parseFloat(valorTotal) < 0){"
                        . "             valorTotal = 0;"
                        . "         }"
                        . "         $('#pValorTotal').text('R$ ' + valorTotal.toFixed(2));"
                        . "     }"
                        . "  });"
            
                        . "  $('#btnContinuar').click(function(){"
                        . "     var isOk      = true;"
                        . "     var haPeriodo = $('#ddlPeriodoAssinatura').val();"
                        . "     var haSistema;"
            
                        . "     dados[0].d.plano[0].op = $('#ddlPeriodoAssinatura').val();"
                        . "     dados[0].d.sistemas = []; "
                        . "     $('input[type=checkbox]').each(function(i){"
                        . "         if ($(this).is(':checked')){"
                        . "             dados[0].d.sistemas.push({'sid':$(this).val()});"
                        . "             haSistema = $(this).val();"
                        . "         }"
                        . "     });"

                        . "     if (isEmpty(haSistema) || haPeriodo === '0'){"
                        . "         isOk = false;"
                        . "         alert('Selecione um período e/ou um ou mais sistemas e tente novamente.');"
                        . "     }"

                        . "     if (isOk == true){"
                        . "         if (confirm('O período de assinatura e o(s) sistema(s) escolhido(s) estão corretos?') == true){"
                        . "             window.open('./index.php?v=cadastrologin&dt3=' + encodeURIComponent(JSON.stringify(dados)), '_self'); "
                        //. "             $.get('./view/cadastrologin.php', { dt3: dados }, function(rs){"
                        //. "                 $('#app').html(rs);"
                        //. "             })"
                        //. "             .fail(function(){"
                        //. "                 alert('Erro ao abrir formulário');"
                        //. "             });"
                        . "         }"
                        . "     }"
            
                        . "  });"
                        . "  $('#btnCancelar').click(function(){"
                        . "     window.open('./', '_self');"
                        . "  });"
                        . "});");
    //$frmAssinatura->addFileJS("./js/assinaturas.js");
    
    
    $frmAssinatura->prepare();
    $frmAssinatura->open();