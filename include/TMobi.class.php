<?php
/**
 * Description of TJSON
 *
 * @author hudson martins
 * @version 2013.07.29
 */
include_once 'config.inc.php';
include_once 'adodb/adodb-exceptions.inc.php';
require_once 'adodb/adodb.inc.php';
include_once 'adodb/tohtml.inc.php';
include_once 'adodb/adodb-time.inc.php';

function __autoload($classe){
    try {
        if (file_exists("{$classe}.class.php")){
            include_once "{$classe}.class.php";
        }
        else {
            if (file_exists("../dao/{$classe}.class.php")){
                include_once "../dao/{$classe}.class.php";
            }
            else {
                if (file_exists("../libs/app.widgets/{$classe}.class.php")){
                    include_once "../libs/app.widgets/{$classe}.class.php";
                }
                else {
                    $messageError = array("COD"=>"302", "MSG"=>"Arquivo {$classe} não encontrado.");
                    header("Content-type: application/json;charset=utf-8");
                    echo TGetJSON::getJSONm($messageError["COD"], $messageError["MSG"]);
                    //echo json_encode($messageError);
                }
            }
        }
    } catch (Exception $e) {
        $messageError = array("COD"=>"302", "MSG"=>"{$e->getMessage()}");
        header("Content-type: application/json;charset=utf-8");
        echo TGetJSON::getJSONm($messageError["COD"], $messageError["MSG"]);
        //echo json_encode($messageError);
    }
}

switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
    case 'POST':
        try {
            $j = file_get_contents("php://input");
            $p = json_decode($j, true);
            
            $DAOClass = $p["className"];
            $Method   = $p["methodName"];
            if (!empty($p["params"]))
              $params = $p["params"];
           
            header("Content-type: application/json;charset=utf-8");            
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
                $msg = array("R"=>array("COD"    =>"301",
                                        "MESSAGE"=>"O metodo {$Method}, nao existe na classe, {$DAOClass}"));
                echo json_encode($msg);
            }
        }
        catch (Exception $e) {
            $msg = array("R"=>array("COD"    =>"100",
                                    "MESSAGE"=>"Message Error: {$e->getMessage()}"));
            header("Content-type: application/json;charset=utf-8");
            echo json_encode($msg);
        }
        break;
    case 'GET':
        try {
            $j = file_get_contents("php://input");
            $p = json_decode($j, true);
            
            if (!empty($p["className"])){
                $DAOClass = $p["className"];
                $Method   = $p["methodName"];
                if (!empty($p["params"]))
                  $params = $p["params"];
            }
            else {
                $DAOClass = $_GET["className"];
                $Method   = $_GET["methodName"];
                if (!empty($_GET["params"]))
                  $params = $_GET["params"];
            }
            
            header("Content-type: application/json;charset=utf-8");
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
                $msg = array("R"=>array("COD"    =>"301",
                                        "MESSAGE"=>"O metodo {$Method}, nao existe na classe, {$DAOClass}"));
                echo json_encode($msg);
            }
        }
        catch (Exception $e) {
            $msg = array("R"=>array("COD"    =>"100",
                                    "MESSAGE"=>"Message Error: {$e->getMessage()}"));
            header("Content-type: application/json;charset=utf-8");
            echo json_encode($msg);
        }        
        break;
    default:
        $msg = array("R"=>array("COD"    =>"100",
                                "MESSAGE"=>"Erro de Requisicao."));
        header("Content-type: application/json;charset=utf-8");
        echo json_encode($msg);
        break;
}

?>
