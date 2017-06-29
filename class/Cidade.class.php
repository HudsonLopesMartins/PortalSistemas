<?php


/**
 * Description of Cidade
 *
 * @author hudsonmartins
 * @version 0.0.1
 */

interface iCidade {
    public function getVersion();
    public function exists($dados);
    public function find($dados, $toJson = true);
    public function findAllByUf($dados, $toJson = true);
}

final class TQuerysCidade {
    function __construct() {}
    public static function getQuery($queryname) {
        $query["findCity"] = "select
                                c.id,
                                c.id_estado,
                                c.nome,
                                c.capital,
                                e.sigla as uf
                              from cidades c, estados e
                              where e.id   = c.id_estado
                                and c.nome = ?
                              order by c.capital desc, c.id";
        $query["findAllByUf"] = "select
                                   c.id,
                                   c.id_estado,
                                   c.nome,
                                   c.capital,
                                   e.sigla as uf
                                 from cidades c, estados e
                                 where e.id    = c.id_estado
                                   and e.sigla = ?
                                 order by c.capital desc, c.id";
        
        return $query[$queryname];
    }
}

class Cidade implements iCidade{
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
    
    public function exists($dados) {
        $SQL      = (object) $this->SQLText;
        $dsCidade = $this->dbconn->Execute(TQuerysCidade::getQuery("findCity"), 
                                           array($dados["d"]["cidade"][0]["cidade"]));
        
        if ($dsCidade->RecordCount() > 0){
            $message = array("r"=>array("COD"=>"200", 
                                        "MSG"=>$GLOBALS['message'][200], 
                                        "ID" =>$dsCidade->fields["id"],
                                        "NME"=>$dsCidade->fields["nome"]));
            return $message;
        }
        else {
            $message = array("r" => array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201])));
            return $message;
        }
        
        $dsCidade->Close();
    }
    
    public function find($dados, $toJson = true) {
        $rJson = (bool) $toJson;
        
        $dsCidade = $this->dbconn->Execute(TQuerysCidade::getQuery("findCity"), 
                                           array($dados["d"]["cidade"][0]["cidade"]));
        
        if ($rJson){
            header("Content-type: application/json;charset=utf-8");
            if ($dsCidade->RecordCount() > 0){
                $message = array("COD"=>"200", 
                                 "MSG"=>$GLOBALS['message'][200], 
                                 "ID" =>$dsCidade->fields["id"],
                                 "NME"=>$dsCidade->fields["nome"]);
                echo TGetJSON::getJSON("r", $message);
            }
            else {
                $message = array("r" => array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201])));
                echo TGetJSON::getJSON("r", $message);
            }
        }
        $dsCidade->Close();
    }
    
    public function findAllByUf($dados, $toJson = true) {
        $rJson = (bool) $toJson;
        
        $dsCidade = $this->dbconn->Execute(TQuerysCidade::getQuery("findAllByUf"),
                                           array($dados["d"]["estado"][0]["uf"]));
        
        if ($rJson){
            header("Content-type: application/json;charset=utf-8");
            if ($dsCidade->RecordCount() > 0){
                /*
                $message = array("COD"=>"200", 
                                 "MSG"=>$GLOBALS['message'][200], 
                                 "ID" =>$dsCidade->fields["id"],
                                 "NME"=>$dsCidade->fields["nome"]);
                 * 
                 */
                echo TGetJSON::getJSONData("r", $dsCidade->GetArray());
            }
            else {
                $message = array("r" => array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201])));
                echo TGetJSON::getJSON("r", $message);
            }
        }
        $dsCidade->Close();
    }
}
