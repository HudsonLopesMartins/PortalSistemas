<?php
/**
 * Description of TJSON
 *
 * @author hudson martins
 * @version 2017.03.30
 */
include_once './config.inc.php';
include_once './message.inc.php';

require_once '../libs/adodb5/adodb-active-record.inc.php';
include_once '../libs/adodb5/adodb-exceptions.inc.php';
require_once '../libs/adodb5/adodb.inc.php';
include_once '../libs/adodb5/tohtml.inc.php';
include_once '../libs/adodb5/adodb-time.inc.php';

function __autoload($classe){
    try {
        if (file_exists("{$classe}.class.php")){
            include_once "{$classe}.class.php";
        }
        else {
            if (file_exists("../class/{$classe}.class.php")){
                include_once "../class/{$classe}.class.php";
            }
            else {
                if (file_exists("../libs/app.widgets/{$classe}.class.php")){
                    include_once "../libs/app.widgets/{$classe}.class.php";
                }
                else {
                    $messageError = array("COD"=>"302", "MSG"=>"Arquivo {$classe} não encontrado.");
                    header("Content-type: application/json;charset=utf-8");
                    echo TGetJSON::toJSON($messageError["COD"], $messageError["MSG"]);
                }
            }
        }
    } catch (Exception $e) {
        $messageError = array("COD"=>"302", "MSG"=>"{$e->getMessage()}");
        header("Content-type: application/json;charset=utf-8");
        echo TGetJSON::toJSON($messageError["COD"], $messageError["MSG"]);
    }
}

switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
    case 'POST':
        try {
            $DAOClass = $_POST["className"];
            $Method   = $_POST["methodName"];
            $params   = $_POST["params"];
            
            $object = new $DAOClass;
            if (method_exists($object, $Method)){
                if (isset($params)){
                    call_user_func_array(array($object, $Method), $params);
                }
                else {
                    call_user_func(array($object, $Method));
                }
            }
            else {
                header("Content-type: application/json");
                echo TGetJSON::toJSON("301", "O metodo {$Method} nao existe na classe {$DAOClass}");
            }
        }
        catch (Exception $e) {
            header("Content-type: application/json");
            echo TGetJSON::toJSON("100", "Message Error: {$e->getMessage()}");
        }
        break;
    case 'GET':
        try {
            header("Content-type: application/json;charset=utf-8");
            $DAOClass = $_GET["className"];
            $object = new $DAOClass;
            if (method_exists($object, $_GET["methodName"])){
                if (isset($_GET["params"])){
                    call_user_func_array(array($object, $_GET["methodName"]), $_GET["params"]);
                }
                else {
                    call_user_func(array($object, $_GET["methodName"]));
                }
            }
            else {
                echo TGetJSON::toJSON("301", "O metodo {$_GET["methodName"]} nao existe na classe {$DAOClass}");
            }
        }
        catch (Exception $e) {
            echo TGetJSON::toJSON("100", "Message Error: {$e->getMessage()}");
        }        
        break;
    default:
        header("Content-type: application/json;charset=utf-8");
        echo TGetJSON::toJSON("100", "Message Error: {$e->getMessage()}");
        break;
}

?>
