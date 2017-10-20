<?php

/**
 * Description of GrupoAcesso
 *
 * @author hudsonmartins
 * @version 0.0.1
 */

interface iGrupoAcesso {
    public function getVersion();
    public function exists($dados);
    public function findAllByEmp($dados, $toJson = true);
    public function findAll($dados, $toJson = true);
    public function localizar($dados, $toJson = true);
    public function inserir($dados, $toJson = true);
    public function editar($dados, $toJson = true);
    public function editarBloqueio($dados, $toJson = true);
}

final class TQuerysGrupoAcesso {
    function __construct() {}
    public static function getQuery($queryname) {
        $query["findAllByEmp"] = "select 
                                    ga.id, ga.id_empresa, ga.nome, ga.ativo
                                  from grupoacesso ga
                                  where ga.id_empresa          = ?
                                    and ga.id_sistemas_empresa = ?";
        $query["findAll"]      = "select 
                                    ga.id as id_grupo, ga.id_empresa, ga.nome, ga.ativo
                                  from grupoacesso ga
                                  where ga.id_empresa          = ?
                                    and ga.id_sistemas_empresa = ?
                                    and ga.ativo               = ?";
        
        $query["findOne"]      = "select 
                                    ga.id as id_grupo, ga.id_empresa, ga.nome, ga.ativo
                                  from grupoacesso ga
                                  where ga.id_empresa          = ?
                                    and ga.id_sistemas_empresa = ?
                                    and ga.id                  = ?
                                    and ga.ativo               = ?";
        
        return $query[$queryname];
    }
}

class DMGrupoAcesso extends ADODB_Active_Record {
    
}

class GrupoAcesso extends DMGrupoAcesso implements iGrupoAcesso {
    private $sVersion = "0.0.1";
    private $dbconn  = null;
    
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
        $dsGrupo = $this->dbconn->Execute(TQuerysGrupoAcesso::getQuery("findAllByEmp"), array($dados["d"]["empresa"][0]["id"]));
        
        if ($dsGrupo->RecordCount() > 0){
            $message = array("r" => array("COD" => "200", 
                                          "MSG" => htmlspecialchars($GLOBALS['message'][200]),
                                          "ID"  => $dsGrupo->fields["id"],
                                          "DESC"=> $dsGrupo->fields["nome"]));
            return $message;
        }
        else {
            $message = array("r" => array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201])));
            return $message;
        }
    }
    
    public function findAllByEmp($dados, $toJson = true) {
        $rJson   = (bool) $toJson;
        $dsGrupo = $this->dbconn->Execute(TQuerysGrupoAcesso::getQuery("findAllByEmp"), 
                                          array($dados["d"]["empresa"][0]["id"],
                                                $dados["d"]["appempresa"][0]["id"]));
        
        if ($rJson){
            header("Content-type: application/json;charset=utf-8");
            if ($dsGrupo->RecordCount() > 0){
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
                echo TGetJSON::getJSONData("r", $dsGrupo->GetArray());
            }
            else {
                $message = array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201]));
                echo TGetJSON::getJSON("r", $message);
            }
            $dsGrupo->Close();
        }
    }
    
    public function findAll($dados, $toJson = true) {
        $rJson   = (bool) $toJson;
        $dsGrupo = $this->dbconn->Execute(TQuerysGrupoAcesso::getQuery("findAll"), 
                                          array($dados["d"]["empresa"][0]["id"],
                                                $dados["d"]["appempresa"][0]["id"],
                                                $dados["d"]["grupoacesso"][0]["ativo"]));
        
        if ($rJson){
            header("Content-type: application/json;charset=utf-8");
            if ($dsGrupo->RecordCount() > 0){
                echo TGetJSON::getJSONData("r", $dsGrupo->GetArray());
            }
            else {
                $message = array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201]));
                echo TGetJSON::getJSON("r", $message);
            }
            $dsGrupo->Close();
        }
    }
    
    public function localizar($dados, $toJson = true) {
        $rJson   = (bool) $toJson;
        $dsGrupo = $this->dbconn->Execute(TQuerysGrupoAcesso::getQuery("findOne"), 
                                          array($dados["d"]["empresa"][0]["id"],
                                                $dados["d"]["appempresa"][0]["id"],
                                                $dados["d"]["grupoacesso"][0]["id"],
                                                $dados["d"]["grupoacesso"][0]["ativo"]));
        
        if ($rJson){
            header("Content-type: application/json;charset=utf-8");
            if ($dsGrupo->RecordCount() > 0){
                echo TGetJSON::getJSONData("r", $dsGrupo->GetArray());
            }
            else {
                $message = array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201]));
                echo TGetJSON::getJSON("r", $message);
            }
            $dsGrupo->Close();
        }
    }
    
    public function inserir($dados, $toJson = true) {
        $rJson = (bool) $toJson;
        ADODB_Active_Record::SetDatabaseAdapter($this->dbconn);
        try {
            $dsGrupo = new DMGrupoAcesso("grupoacesso");
            $dsGrupo->id_empresa          = $dados["d"]["empresa"][0]["id"];
            $dsGrupo->id_sistemas_empresa = $dados["d"]["appempresa"][0]["id"];
            $dsGrupo->nome                = $dados["d"]["grupoacesso"][0]["nome"];
            $dsGrupo->ativo               = 0;
            $dsGrupo->Save();

            $ok = $dsGrupo->Save();
            if ($rJson){
                header("Content-type: application/json;charset=utf-8");
                if (!$ok) {
                    $msgErro = $dsGrupo->ErrorMsg();
                    $message = array("COD"=>"402", "MSG"=>$GLOBALS['message'][402] . " ERROR: " . $msgErro);
                }
                else {
                    $message = array("COD"=>"202", "MSG"=>$GLOBALS['message'][202]);
                }
                echo TGetJSON::getJSON("r", $message);
            }
            
        } catch (exception $exc) {
            var_dump($exc);
        }
    }
    
    public function editar($dados, $toJson = true) {
        $rJson     = (bool) $toJson;
        $whereSQL  = "id_empresa              = {$dados["d"]["empresa"][0]["id"]} " . 
                     "and id_sistemas_empresa = {$dados["d"]["appempresa"][0]["id"]} " .
                     "and id                  = {$dados["d"]["grupoacesso"][0]["id"]} " .
                     "and ativo               = {$dados["d"]["grupoacesso"][0]["ativo"]} ";
        
        try {
            $fieldValue["nome"] = $dados["d"]["grupoacesso"][0]["nome"];
            $ok = $this->dbconn->AutoExecute("grupoacesso", $fieldValue, "UPDATE", $whereSQL);

            if ($rJson){
                if ($ok){
                    header("Content-type: application/json;charset=utf-8");
                    $message = array("COD"=>"203", "MSG"=> htmlspecialchars($GLOBALS['message'][203]));
                    echo TGetJSON::getJSON("r", $message);
                }
                else {
                    header("Content-type: application/json;charset=utf-8");
                    $message = array("COD"=>"403", "MSG"=> htmlspecialchars($GLOBALS['message'][403]));
                    echo TGetJSON::getJSON("r", $message);
                }
            }
            $this->dbconn->Close();
        } catch (exception $e) {
            header("Content-type: application/json;charset=utf-8");
            echo TGetJSON::getJSON("r", $e->getMessage());
        }
    }
    
    public function editarBloqueio($dados, $toJson = true) {
        $rJson     = (bool) $toJson;
        $whereSQL  = "id_empresa              = {$dados["d"]["empresa"][0]["id"]} " . 
                     "and id_sistemas_empresa = {$dados["d"]["appempresa"][0]["id"]} " .
                     "and id                  = {$dados["d"]["grupoacesso"][0]["id"]} " .
                     "and ativo               = {$dados["d"]["grupoacesso"][0]["ativo"]} ";
        try {
            $fieldValue["ativo"] = -1;
            if ($dados["d"]["grupoacesso"][0]["ativo"] == '0'){
                $fieldValue["ativo"] = 1;
            }
            else {
                $fieldValue["ativo"] = 0;
            }
            
            $ok = $this->dbconn->AutoExecute("grupoacesso", $fieldValue, "UPDATE", $whereSQL);

            if ($rJson){
                if ($ok){
                    header("Content-type: application/json;charset=utf-8");
                    $message = array("COD"=>"203", "MSG"=> htmlspecialchars($GLOBALS['message'][203]));
                    echo TGetJSON::getJSON("r", $message);
                }
                else {
                    header("Content-type: application/json;charset=utf-8");
                    $message = array("COD"=>"403", "MSG"=> htmlspecialchars($GLOBALS['message'][403]));
                    echo TGetJSON::getJSON("r", $message);
                }
            }
            $this->dbconn->Close();
        } catch (exception $e) {
            header("Content-type: application/json;charset=utf-8");
            $message = array("COD"=>"100", "MSG"=> htmlspecialchars($e->getMessage()));
            echo TGetJSON::getJSON("r", $message);
        }
    }
}
