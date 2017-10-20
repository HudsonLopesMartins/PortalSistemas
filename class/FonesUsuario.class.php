<?php
/**
 * Description of FonesUsuario
 *
 * @author hudsonmartins
 */

interface iFonesUsuario {
    public function getVersion();
    public function inserir($dados, $id_dadosusuario);
    public function findAll($dados, $toJson = true);
    public function execUpdates($dados, $toJson = true);
}

final class TQuerysFonesUsuario {
    //TQuerysFoneEmpresa::getQuery("findAllById")
    function __construct() {}
    public static function getQuery($queryname) {
        $query["findAll"]      = "select
                                    u.id  as id_usuario,
                                    du.id as id_dadosusuario,
                                    f.id  as id_fone,
                                    f.fone,
                                    f.tipo,
                                    (case f.tipo
                                         when '0000' then 'Diversos'
                                         when 'RSDL' then 'Residencial'
                                         when 'CMCL' then 'Comercial'
                                         when 'FAXC' then 'Fax Comercial'
                                         when 'FAXR' then 'Fax Residencial'
                                         when 'CELL' then 'Celular'
                                     else
                                         '-'
                                     end) as descricao,
                                     f.principal
                                  from usuario u, dadosusuario du, fonesusuarios f
                                  where f.id_usuario  = du.id
                                    and du.id_usuario = u.id
                                    and u.id          = ?
                                  order by f.principal desc";
        $query["findFone"]      = "select
                                    u.id  as id_usuario,
                                    du.id as id_dadosusuario,
                                    f.id  as id_fone,
                                    f.fone,
                                    f.tipo,
                                    (case f.tipo
                                         when '0000' then 'Diversos'
                                         when 'RSDL' then 'Residencial'
                                         when 'CMCL' then 'Comercial'
                                         when 'FAXC' then 'Fax Comercial'
                                         when 'FAXR' then 'Fax Residencial'
                                         when 'CELL' then 'Celular'
                                     else
                                         '-'
                                     end) as descricao,
                                     f.principal
                                  from usuario u, dadosusuario du, fonesusuarios f
                                  where f.id_usuario  = du.id
                                    and du.id_usuario = u.id
                                    and f.fone        = ?
                                    and u.id          = ?
                                   order by f.principal desc";
        return $query[$queryname];
    }
}

class DMFonesUsuario extends ADODB_Active_Record {}

class FonesUsuario extends DMFonesUsuario implements iFonesUsuario{
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
    
    public function inserir($dados, $id_dadosusuario) {
        try {
             /**
             * A Classe DMEmpresa irá acessar a tabela 'empresa' 
             * baseada na conexão setada na linha: ADODB_Active_Record::SetDatabaseAdapter($this->dbconn);
             */
            ADODB_Active_Record::SetDatabaseAdapter($this->dbconn);
            for ($x = 0; $x < count($dados["d"]["fones"]); $x++){
                $dsFones = new DMFonesUsuario("fonesusuarios");
                $dsFones->id_usuario = $id_dadosusuario;
                $dsFones->fone       = $dados["d"]["fones"][$x]["fone"];
                $dsFones->principal  = $dados["d"]["fones"][$x]["principal"];
                $dsFones->tipo       = $dados["d"]["fones"][$x]["tipocontato"];
                $dsFones->Save();
            }
            $message = array("r" => array("COD"=> "202", 
                                          "MSG"=> htmlspecialchars($GLOBALS['message'][202])));
            return $message;
        } catch (exception $exc) {
            $message = array("r" => array("COD"=> "101", 
                                          "MSG"=> htmlspecialchars($exc->getMessage())));
            return $message;
        }
    }
    
    public function findAll($dados, $toJson = true) {
        $rJson  = (bool) $toJson;        
        try {
            $dsFone = $this->dbconn->Execute(TQuerysFonesUsuario::getQuery("findAll"), 
                                             array($dados["d"]["usuario"][0]["id"]));
            if ($rJson){
                header("Content-type: application/json;charset=utf-8");
                if ($dsFone->RecordCount() > 0){
                    echo TGetJSON::getJSONData("r", $dsFone->GetArray());
                }
                else {
                    $messageError = array("COD"=>"201", "MSG"=>htmlspecialchars($GLOBALS['message'][201]));
                    echo TGetJSON::getJSON("r", $messageError);
                }
            }
            else {
                if ($dsFone->RecordCount() > 0){
                    return array("r" => $dsFone->GetArray());
                }
                else {
                    $messageError = array("COD"=>"201", "MSG"=>htmlspecialchars($GLOBALS['message'][201]));
                    return array("r" => $messageError);
                }
            }
            
            $this->dbconn->Close();
        } catch (exception $exc) {
            header("Content-type: application/json;charset=utf-8");
            $messageError = array("COD"=>"201", "MSG"=>htmlspecialchars($exc->getMessage()));
            echo TGetJSON::getJSON("r", $messageError);
        }
    }
    
    public function execUpdates($dados, $toJson = true) {
        $rJson  = (bool) $toJson;
        //$allOk  = 1;
        try {
            if (isset($dados["d"]["insert"][0]["fones"]) && count($dados["d"]["insert"][0]["fones"]) >= 1 ){
                /**
                 * A Classe DMEmpresa irá acessar a tabela 'empresa' 
                 * baseada na conexão setada na linha: ADODB_Active_Record::SetDatabaseAdapter($this->dbconn);
                 */
                ADODB_Active_Record::SetDatabaseAdapter($this->dbconn);
                for ($x = 0; $x < count($dados["d"]["insert"][0]["fones"]); $x++){
                    $dsFones = new DMFonesUsuario("fonesusuarios");
                    $dsFones->id_usuario = $dados["d"]["insert"][0]["fones"][$x]["id_dadosusuario"];
                    $dsFones->fone       = $dados["d"]["insert"][0]["fones"][$x]["fone"];
                    $dsFones->principal  = $dados["d"]["insert"][0]["fones"][$x]["principal"];
                    $dsFones->tipo       = $dados["d"]["insert"][0]["fones"][$x]["tipocontato"];
                    $dsFones->Save();
                }
            }
            
            if (isset($dados["d"]["delete"][0]["fones"]) && count($dados["d"]["delete"][0]["fones"]) >= 1 ){
                for ($x = 0; $x < count($dados["d"]["delete"][0]["fones"]); $x++){
                    $this->dbconn->execute("delete from fonesusuarios "
                                         . "where id         = {$dados["d"]["delete"][0]["fones"][$x]["id"]} "
                                         . "  and id_usuario = {$dados["d"]["delete"][0]["fones"][$x]["id_dadosusuario"]}");
                }
            }
            //header("Content-type: application/json;charset=utf-8"); 
            $message = array("COD"=> "206", 
                             "MSG"=> htmlspecialchars($GLOBALS['message'][206]));
            echo TGetJSON::getJSON("r", $message);
        } catch (exception $exc) {
            //header("Content-type: application/json;charset=utf-8");
            $message = array("COD"=> "406", 
                             "MSG"=> htmlspecialchars($exc->getMessage()));
            echo TGetJSON::getJSON("r", $message);
        }
    }
}
