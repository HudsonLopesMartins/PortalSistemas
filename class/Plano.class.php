<?php

/**
 * Description of Plano
 *
 * @author hudsonmartins
 * @version 0.0.1
 */

interface iPlano {
    public function getVersion();
    public function exists($dados);
    public function find($dados, $toJson = true);
    public function findAll($toJson = true);
}

class Plano implements iPlano {
    private $sVersion = "0.0.1";
    private $dbconn  = null;
    private $SQLText = array("findAll"=>"select
                                           p.id,
                                           p.descricao,
                                           p.detalhe,
                                           p.valor,
                                           p.mes
                                         from plano p
                                         order by p.id");
    
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
    
    public function exists($dados) {
        $SQL     = (object) $this->SQLText;
        $dsPlano = $this->dbconn->Execute($SQL->findAll, array($dados["d"]["plano"][0]["id"]));
        
        if ($dsPlano->RecordCount() > 0){
            $message = array("r" => array("COD" => "200", 
                                          "MSG" => htmlspecialchars($GLOBALS['message'][200]),
                                          "ID"  => $dsPlano->fields["id"],
                                          "DESC"=> $dsPlano->fields["descricao"]));
            return $message;
        }
        else {
            $message = array("r" => array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201])));
            return $message;
        }
        $dsPlano->Close();
    }
    
    public function find($dados, $toJson = true) {
        $SQL     = (object) $this->SQLText;
        $rJson   = (bool) $toJson;
        $dsPlano = $this->dbconn->Execute($SQL->findAll, array($dados["d"]["plano"][0]["id"]));
        
        if ($rJson){
            header("Content-type: application/json;charset=utf-8");
            if ($dsPlano->RecordCount() > 0){
                /*
                $message = array("COD"=> "200", 
                                 "MSG"=> htmlspecialchars($GLOBALS['message'][200]),
                                 "ID" => $dsCEP->fields["id"],
                                 "CEP"=> $dsCEP->fields["cep"],
                                 "END"=> $dsCEP->fields["endereco"],
                                 "BRR"=> $dsCEP->fields["bairro"],
                                 "CID"=> $dsCEP->fields["id_cidade"],
                                 "UF" => $dsCEP->fields["uf"]);
                 * 
                 */
                echo TGetJSON::getJSONData("r", $dsPlano->GetArray());
            }
            else {
                $message = array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201]));
                echo TGetJSON::getJSON("r", $message);
            }
            $dsPlano->Close();
        }
    }
    
    public function findAll($toJson = true) {
        $SQL     = (object) $this->SQLText;
        $rJson   = (bool) $toJson;
        $dsPlano = $this->dbconn->Execute($SQL->findAll);
        
        if ($rJson){
            header("Content-type: application/json;charset=utf-8");
            if ($dsPlano->RecordCount() > 0){
                echo TGetJSON::getJSONData("r", $dsPlano->GetArray());
            }
            else {
                $message = array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201]));
                echo TGetJSON::getJSON("r", $message);
            }
        }
        else {
            if ($dsPlano->RecordCount() > 0){
                $message = array("r" => $dsPlano->GetArray());
                return $message;
            }
            else {
                $message = array("r" => array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201])));
                return $message;
            }
        }
        
        $dsPlano->Close();
    }
}
