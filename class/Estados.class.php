<?php

/**
 * Description of Estados
 *
 * @author hudsonmartins
 * @version 0.0.1
 */

interface iEstados {
    public function getVersion();
    public function getAll($toJson = true);
    public function getAllOrdBySigla($toJson = true);
    public function find($dados, $toJson = true);
    public function exists($dados);
}

final class TQuerysEstados {
    function __construct() {}
    public static function getQuery($queryname) {
        $query["findAll"] = "select 
                               estados.id,
                               estados.estado,
                               estados.sigla,
                               estados.capital,
                               regiao.regiao
                             from estados, regiao
                             where regiao.id = estados.id_regiao
                             order by estados.id_regiao, estados.id";
        $query["findAllOrdBySigla"] = "select 
                               estados.id,
                               estados.estado,
                               estados.sigla,
                               estados.capital,
                               regiao.regiao
                             from estados, regiao
                             where regiao.id = estados.id_regiao
                             order by estados.sigla";
        $query["find"] = "select 
                            estados.id,
                            estados.estado,
                            estados.sigla,
                            estados.capital,
                            regiao.regiao
                          from estados, regiao
                          where regiao.id = estados.id_regiao
                            and estados.sigla = ?
                          order by estados.id_regiao, estados.id";
        
        return $query[$queryname];
    }
}

class Estados implements iEstados {
    private $sVersion       = "0.0.1";
    private $dbconn         = null;
    
    public function __construct() {
        $this->dbconn = ADONewConnection(DRV_MYSQL);
        $this->dbconn->charSet = 'utf8';
        $this->dbconn->Connect(DSN_MYSQL, UID_MYSQL, PWD_MYSQL, DBN_MYSQL);
        $this->dbconn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->dbconn->debug = false;
        $this->dbconn->execute("set names 'utf8'");       
        /**
         * ADODB_FETCH_NUM: $resultSet->fields[0]
         * ADODB_FETCH_ASSOC: $resultSet->fields['COLUNA']
         */
    }
    
    public function getVersion() {
        $messageError = array("COD"=>"VER", "MSG"=>"Versão da classe " . get_class($this) . ": " . $this->sVersion);
        header("Content-type: application/json;charset=utf-8");
        echo TGetJSON::toJSON($messageError["COD"], $messageError["MSG"]);
    }
    
    public function getAll($toJson = true) {
        $rJson = (bool) $toJson;
        
        $dsUf = $this->dbconn->Execute(TQuerysEstados::getQuery("findAll"));
        
        if ($rJson){
            header("Content-type: application/json;charset=utf-8");
            if ($dsUf->RecordCount() > 0){
                echo TGetJSON::getJSONData("r", $dsUf->GetArray());
            }
            else {
                $message = array("r" => array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201])));
                echo TGetJSON::getJSON("r", $message);
            }
        }
        $dsUf->Close();
    }
    
    public function getAllOrdBySigla($toJson = true) {
        $rJson = (bool) $toJson;
        
        $dsUf = $this->dbconn->Execute(TQuerysEstados::getQuery("findAllOrdBySigla"));
        if ($rJson){
            header("Content-type: application/json;charset=utf-8");
            if ($dsUf->RecordCount() > 0){
                echo TGetJSON::getJSONData("r", $dsUf->GetArray());
            }
            else {
                $message = array("r" => array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201])));
                echo TGetJSON::getJSON("r", $message);
            }
        }
        $dsUf->Close();
    }
    
    public function find($dados, $toJson = true) {
        $rJson = (bool) $toJson;
        
        $dsUf = $this->dbconn->Execute(TQuerysEstados::getQuery("find"), 
                                       array($dados["d"]["estado"][0]["uf"]));
        
        if ($rJson){
            header("Content-type: application/json;charset=utf-8");
            if ($dsUf->RecordCount() > 0){
                echo TGetJSON::getJSONData("r", $dsUf->GetArray());
            }
            else {
                $message = array("r" => array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201])));
                echo TGetJSON::getJSON("r", $message);
            }
        }
        $dsUf->Close();
    }
    
    public function exists($dados) {
        $dsUf = $this->dbconn->Execute(TQuerysEstados::getQuery("find"),
                                       array($dados["d"]["endereco"][0]["uf"]));
        
        if ($dsUf->RecordCount() > 0){
            $message = array("r" => array("COD"=> "200", 
                                          "MSG"=> htmlspecialchars($GLOBALS['message'][200]),
                                          "ID" => $dsUf->fields["id"],
                                          "EST"=> $dsUf->fields["estado"]));
            return $message;
        }
        else {
            $message = array("r" => array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201])));
            return $message;
        }
        $dsUf->Close();
    }
}
