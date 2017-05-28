<?php

include_once 'config.inc.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'dao/DAOConnect.class.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'dao/DAOAnexaArquivo.class.php';

$head = "<html>
         <head>
         <title></title>
         <link href='../../style/bootstrap/bootstrap.css' rel='stylesheet'>
         <link rel='stylesheet' href='../../style/styleform.css' type='text/css'></link>
         </head> 
         <body>";

$msgDialog  = null;
$classMsg   = "alert alert-error";
$extFile    = explode('.', $_FILES['txtAnexaArquivo']['name']);
$dirUPLOAD  = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . UPLOAD_PATH;
$fileSource = $_FILES['txtAnexaArquivo']['tmp_name'];
$fileDestin = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . UPLOAD_PATH . DIRECTORY_SEPARATOR . 
              $_POST["hddIdMembro"] . DIRECTORY_SEPARATOR . 
              $_POST["hddIdMembro"] . "-" . date("dmY-His") . '.' . $extFile[1];
$fileSaveDB = $_POST["hddIdMembro"] . '-' . date("dmY-His") . '.' . $extFile[1];

echo "&nbsp;<br><br><br><br><br>";

try {
    /**
     * Verifica se existe a pasta upload
     * Se não existir, cria, caso existe passa para o proximo passo
     */
    if (!file_exists($dirUPLOAD)){
        mkdir($dirUPLOAD);
    }
    
    /**
     * Verifica se existe a pasta com o ID do membro
     * Se não existir, cria, caso existe passa para o proximo passo
     */
    if (!file_exists($dirUPLOAD . DIRECTORY_SEPARATOR . $_POST["hddIdMembro"])){
        mkdir($dirUPLOAD . DIRECTORY_SEPARATOR . $_POST["hddIdMembro"]);
    }
} 
catch (Exception $e) {
    $msgDialog = $e->getMessage();
}

if (move_uploaded_file($fileSource, $fileDestin)){
    $uploadERROR = $_FILES['txtAnexaArquivo']['error'];
    # Erros Informados
    # ----------------
    # 0 - UPLOAD_ERR_OK
    # 1 - UPLOAD_ERR_INI_SIZE
    # 2 - UPLOAD_ERR_FORM_SIZE
    # 3 - UPLOAD_ERR_PARTIAL
    # 4 - UPLOAD_ERR_NO_FILE    
    switch ($uploadERROR) {
        case 0:
            $dsAnexaArquivo = new DAOAnexaArquivo();
            call_user_func_array(array($dsAnexaArquivo, "setFile"), 
                                 array($_POST["hddIdMembro"], $fileSaveDB, 0, "I"));
                
            $msgDialog = $GLOBALS["INFO_MSG"][402];
            $classMsg  = "alert alert-success";
            break;
        case 1 : 
            $msgDialog = "O arquivo possui um tamanho maior que o permitido.\n";
            $classMsg  = "alert alert-error";
            break;
        case 2 : 
            $msgDialog = "O arquivo possui um tamanho maior que o permitido.\n";
            $classMsg  = "alert alert-error";
            break;
        case 3 : 
            $msgDialog = "O arquivo foi copiado parcialmente. Tente fazer o upload outra vez.\n";
            $classMsg  = "alert alert-error";
            break;
        case 4 : 
            $msgDialog = "Arquivo n&atilde;o encontrado, tente outra vez.\n";
            $classMsg  = "alert alert-error";
            break;
        default:
            $msgDialog = "";
            break;
    }
    echo $head .
         "<div align='center' class='{$classMsg}'>" . 
         "<button type='button' id='btnCloseDialog' class='close' data-dismiss='alert' onclick='javascript:self.close();'>&times;</button>" .
         "<strong>Ok!</strong> " .
         $msgDialog .
         "</div></body></html>";
}
else {
    echo $head .
         "<div align='center' class='{$classMsg}'>" . 
         "<button type='button' id='btnCloseDialog' class='close' data-dismiss='alert' onclick='javascript:self.close();'>&times;</button>" .
         "<strong>Aviso!</strong> " .
         $GLOBALS["ERROR_MSG"][401] . ": " . $msgDialog .
         "</div></body></html>";
}

?>