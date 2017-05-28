<?php

/**
 * Description of Cep
 *
 * @author hudsonmartins
 * @version 0.0.1
 */

interface iCep {
    public function getVersion();
    public function exists($dados);
    public function find($dados, $toJson = true);
    public function inserir($dados, $toJson = true);
}

class DMCep extends ADODB_Active_Record {
    
}

class Cep extends DMCep implements iCep {
    private $sVersion = "0.0.1";
    private $dbconn  = null;
    private $SQLText = array("findAllByCEP"=>"select
                                                e.id,
                                                e.cep,
                                                e.ibge,
                                                e.endereco,
                                                e.bairro,
                                                e.id_cidade,
                                                e.id_uf,
                                                c.nome,
                                                uf.estado
                                              from endereco e, cidades c, estados uf
                                              where c.id  = e.id_cidade
                                                and uf.id = e.id_uf 
                                                and e.cep = ?");
    
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
        $SQL    = (object) $this->SQLText;
        $dsCEP  = $this->dbconn->Execute($SQL->findAllByCEP, array($dados["d"]["endereco"][0]["cep"]));
        
        if ($dsCEP->RecordCount() > 0){
            $message = array("r" => array("COD"=> "200", 
                                          "MSG"=> htmlspecialchars($GLOBALS['message'][200]),
                                          "ID" => $dsCEP->fields["id"],
                                          "CEP"=> $dsCEP->fields["cep"]));
            return $message;
        }
        else {
            $message = array("r" => array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201])));
            return $message;
        }
        $dsCEP->Close();
    }
    
    public function find($dados, $toJson = true) {
        $SQL    = (object) $this->SQLText;
        $rJson  = (bool) $toJson;
        $dsCEP  = $this->dbconn->Execute($SQL->findAllByCEP, array($dados["d"]["endereco"][0]["cep"]));
        
        if ($rJson){
            header("Content-type: application/json;charset=utf-8");
            if ($dsCEP->RecordCount() > 0){
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
                echo TGetJSON::getJSONData("r", $dsCEP->GetArray());
            }
            else {
                $message = array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201]));
                echo TGetJSON::getJSON("r", $message);
            }
            $dsCEP->Close();
        }
    }
    
    public function inserir($dados, $toJson = true) {
        $SQL    = (object) $this->SQLText;
        $rJson  = (bool) $toJson;
        
        $c = (object) (new Cidade)->exists($dados);
        if ($c->r["COD"] == "200"){
            $e = (object) (new Estados)->exists($dados);
            $idCid = $c->r["ID"];
            
            ADODB_Active_Record::SetDatabaseAdapter($this->dbconn);
            $dsINSCep            = new DMCep("endereco");
            $dsINSCep->cep       = $dados["d"]["endereco"][0]["cep"];
            $dsINSCep->ibge      = $dados["d"]["endereco"][0]["ibge"];
            $dsINSCep->endereco  = $dados["d"]["endereco"][0]["endereco"];
            $dsINSCep->bairro    = $dados["d"]["endereco"][0]["bairro"];
            $dsINSCep->id_cidade = $idCid;
            $dsINSCep->id_uf     = $e->r["ID"];
            $dsINSCep->Save();

            $dbCEP = $this->dbconn->Execute($SQL->findAllByCEP, array($dados["d"]["endereco"][0]["cep"]));
            $idCEP = $dbCEP->fields["id"];
            $cep   = $dbCEP->fields["cep"];
            $dbCEP->Close();
            
            $message = array("r" => array("COD"=> "202", 
                                          "MSG"=> htmlspecialchars($GLOBALS['message'][202]),
                                          "ID" => $idCEP,
                                          "CEP"=> $cep));
            return $message;
        }
        else {
            $message = array("r" => array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201])));
            return $message;
        }
    }
}
