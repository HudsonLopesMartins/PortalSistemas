<?php

/**
 * Description of Sistemas
 *
 * @author hudsonmartins
 * @version 0.0.1
 */

interface iSistemas {
    public function getVersion();
    public function exists($dados);
    public function find($dados, $toJson = true);
    public function findAll($toJson = true);
    public function findAppByEmp($dados, $toJson = true);
}

class Sistemas implements iSistemas {
    private $sVersion = "0.0.1";
    private $dbconn  = null;
    private $SQLText = array("findAll"=>"select
                                           s.id,
                                           s.nome,
                                           s.descricao,
                                           s.valorassinatura,
                                           s.versao
                                         from sistemas s
                                         order by s.id",
                             "findAppByEmp"=>"select
                                                se.id,
                                                se.id_empresa,
                                                se.id_sistema,
                                                e.nomefantasia, 
                                                s.nome as sistema
                                              from sistemas_empresa se, empresa e, sistemas s
                                              where e.id = se.id_empresa
                                                and s.id = se.id_sistema
                                                and se.id_empresa = ?");
    
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
        $SQL        = (object) $this->SQLText;
        $rJson      = (bool) $toJson;
        $dsSistemas = $this->dbconn->Execute($SQL->findAll, array($dados["d"]["sistemas"][0]["id"]));
        
        if ($rJson){
            header("Content-type: application/json;charset=utf-8");
            if ($dsSistemas->RecordCount() > 0){
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
                echo TGetJSON::getJSONData("r", $dsSistemas->GetArray());
            }
            else {
                $message = array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201]));
                echo TGetJSON::getJSON("r", $message);
            }
            $dsSistemas->Close();
        }
    }
    
    public function findAll($toJson = true) {
        $SQL        = (object) $this->SQLText;
        $rJson      = (bool) $toJson;
        $dsSistemas = $this->dbconn->Execute($SQL->findAll);
        
        if ($rJson){
            header("Content-type: application/json;charset=utf-8");
            if ($dsSistemas->RecordCount() > 0){
                echo TGetJSON::getJSONData("r", $dsSistemas->GetArray());
            }
            else {
                $message = array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201]));
                echo TGetJSON::getJSON("r", $message);
            }
        }
        else {
            if ($dsSistemas->RecordCount() > 0){
                $message = array("r" => $dsSistemas->GetArray());
                return $message;
            }
            else {
                $message = array("r" => array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201])));
                return $message;
            }
        }
        
        $dsSistemas->Close();
    }
    
    public function findAppByEmp($dados, $toJson = true) {
        $SQL   = (object) $this->SQLText;
        $rJson = (bool) $toJson;
        $dsApp = $this->dbconn->Execute($SQL->findAppByEmp, array($dados["d"]["empresa"][0]["id"]));
        
        if ($rJson){
            header("Content-type: application/json;charset=utf-8");
            if ($dsApp->RecordCount() > 0){
                echo TGetJSON::getJSONData("r", $dsApp->GetArray());
            }
            else {
                $message = array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201]));
                echo TGetJSON::getJSON("r", $message);
            }
        }
        else {
            if ($dsApp->RecordCount() > 0){
                $message = array("r" => $dsApp->GetArray());
                return $message;
            }
            else {
                $message = array("r" => array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201])));
                return $message;
            }
        }
        $dsApp->Close();
    }
}
