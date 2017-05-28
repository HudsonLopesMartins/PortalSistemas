<?php
/**
 * Description of FoneEmpresa
 *
 * @author hudsonmartins
 */

interface iFoneEmpresa {
    public function getVersion();
    public function inserir($dados, $id_empresa);
}

class DMFoneEmpresa extends ADODB_Active_Record {}

class FoneEmpresa extends DMFoneEmpresa implements iFoneEmpresa{
    private $sVersion = "0.0.1";
    private $dbconn  = null;
    private $SQLText = array("findAll"=>"select
                                           f.id,
                                           f.id_empresa,
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
                                            end) as descricao
                                         from fones f
                                         where
                                           f.id_empresa = ?
                                         order by f.principal desc",
                             "findFone"=>"select
                                            f.id,
                                            f.id_empresa,
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
                                             end) as descricao
                                          from fones f
                                          where f.fone       = ?
                                            and f.id_empresa = ?
                                          order by f.principal desc");
    
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
    
    public function inserir($dados, $id_empresa) {
        try {
             /**
             * A Classe DMEmpresa irá acessar a tabela 'empresa' 
             * baseada na conexão setada na linha: ADODB_Active_Record::SetDatabaseAdapter($this->dbconn);
             */
            ADODB_Active_Record::SetDatabaseAdapter($this->dbconn);
            for ($x = 0; $x < count($dados["d"]["fones"]); $x++){
                $dsFones = new DMFoneEmpresa("fones");
                $dsFones->id_empresa = $id_empresa;
                $dsFones->ddd        = '000';
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
}
