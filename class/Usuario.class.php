<?php

/**
 * Description of Usuario
 *
 * @author hudson
 * @version 0.0.1
 */

interface iUsuario {
    public function getVersion();
    public function inserir($dados, $toJson = true);
    public function localizarEmpresasUsuario($dados, $toJson = true);
    public function localizarDadosLogin($dados, $toJson = true);
    public function findUsersByEmp($dados, $toJson = true);
    public function findBySession($dados, $toJson = true);
    public function inserirLogAcesso($dados, $toJson = true);
    public function findByLogin($dados, $toJson = true);
    public function findUserByEmail($dados, $toJson = true);
    public function changePwd($dados, $toJson = true);
    public function viewDetalhesUsuario($dados, $toJson = true);
    public function editarDetalhesUsuario($dados, $toJson = true);
}

final class TQuerysUsuario {
    function __construct() {}
    public static function getQuery($queryname) {
        $query["localizarEmpresasUsuario"] = "select 
                                                u.id, u.id_empresa, u.id_grupo,
                                                u.email, u.nome, e.nomefantasia
                                              from usuario u, empresa e
                                              where e.id    = u.id_empresa
                                                and u.email = ? 
                                                and u.pass  = ?";
        $query["localizarDadosLogin"] = "select
                                                u.id, u.id_empresa, u.id_grupo,
                                                u.email, u.nome, u.chpwd as mdsnh, 
                                                u.ativo, g.nome as grupo,
                                                e.razaosocial, e.nomefantasia,
                                                e.lat, e.lng, u.tipo
                                              from usuario u, empresa e, grupoacesso g
                                              where e.id         = u.id_empresa
                                                and e.ativo      = 0
                                                and g.id         = u.id_grupo
                                                and g.ativo      = 1
                                                and u.email      = ?
                                                and u.pass       = ?
                                                and u.id_empresa = ?
                                                and u.ativo      = 1";
        
        $query["viewDetalhesUsuario"] = "select
                                                id_empresa, id_grupo, id_usuario, id_dadousuario,
                                                id_endereco, id_cidade, id_uf, grupo,
                                                email_usuario, nome_usuario, ramal, nome_completo,
                                                cpf, endereco, numero, complemento, bairro,
                                                email_pessoal, lat, lng, cep,
                                                nome_cidade, sigla, tipo,
                                                datacadastro, ativo, is_adm
                                              from vwdetalheusuario
                                              where id_empresa = ?
                                                and id_usuario = ?";
        
        $query["findUsersByEmp"]      = "select
                                                  u.id as id_usuario, 
                                                  u.id_grupo,
                                                  u.id_empresa, 
                                                  u.nome,
                                                  u.email,
                                                  g.nome as grupo,
                                                  e.razaosocial,
                                                  e.nomefantasia,
                                                  e.lat,
                                                  e.lng,
                                                  u.chpwd as mdsnh, 
                                                  u.tipo,
                                                  u.ativo
                                                from usuario u, empresa e, grupoacesso g
                                                where e.id         = u.id_empresa
                                                  and e.ativo      = 0
                                                  and g.id         = u.id_grupo
                                                  and g.ativo      = 1
                                                  and u.id_empresa = ?
                                                  and u.ativo      = ?";
        
        return $query[$queryname];
    }
}

class DMUsuario extends ADODB_Active_Record {
    
}

class Usuario extends DMUsuario implements iUsuario{
    private $sVersion = "0.0.1";
    private $dbconn  = null;
    private $SQLText = array("findByLogin"=>"select
                                                 u.id as id_usuario, 
                                                 e.id as id_empresa, 
                                                 e.id_endereco,
                                                 en.id_cidade,
                                                 u.email, 
                                                 u.ativo, 
                                                 u.administrador,
                                                 e.razaosocial,
                                                 e.nomefantasia, 
                                                 e.cnpj,
                                                 en.cep, 
                                                 en.ibge,
                                                 en.endereco, 
                                                 en.bairro, cid.nome as cidade, 
                                                 en.uf,
                                                 e.numero, e.complemento, 
                                                 e.site, e.email, 
                                                 e.latitude, e.longitude
                                               from usuario u
                                               inner join empresa e on
                                                     e.id = u.id_empresa
                                               inner join endereco en on 
                                                     en.id  = e.id_endereco  
                                                 and en.cep = e.cep_endereco  
                                               inner join cidades cid on 
                                                     cid.id = en.id_cidade 
                                               inner join estados uf on 
                                                     uf.sigla = en.uf
                                               where u.id = ?",
                                               /*
                                               where u.email = ?
                                                 and u.senha = ?");
                                                * 
                                                */
                             "newPwdSQL"=>"select
                                             SUBSTRING(md5((DATE_FORMAT(NOW(), '%d-%m-%Y %T'))), 1, 8) as nova_senha, 
                                             NOW() as data_solicitacao, 
                                             (DATE_FORMAT(NOW(), '%d-%m-%Y %T')) data_hora_br");
    
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
    
    function inserir($dados, $toJson = true) {
        $rJson = (bool) $toJson;
        ADODB_Active_Record::SetDatabaseAdapter($this->dbconn);
        try {
            /**
             * A Classe DMUsuario irá acessar a tabela 'usuario' 
             * baseada na conexão setada na linha: ADODB_Active_Record::SetDatabaseAdapter($this->dbconn);
             */
            $dsUsuario = new DMUsuario("usuario");
            $dsUsuario->id_empresa   = $dados["d"]["empresa"][0]["id"];
            $dsUsuario->id_grupo     = $dados["d"]["grupoacesso"][0]["id"]; // Aqui deverá ser sempre o id do grupo inativo
            $dsUsuario->email        = $dados["d"]["dadosusuario"][0]["emailpessoal"];
            $dsUsuario->pass         = "";
            $dsUsuario->nome         = $dados["d"]["dadosusuario"][0]["nome"];;
            $dsUsuario->tipo         = "DFLT";
            $dsUsuario->chpwd        = 1;
            $dsUsuario->datacadastro = date('Y-m-d');
            $dsUsuario->ativo        = 0;
            $dsUsuario->Save();

            $ok = $dsUsuario->Save();
            if ($rJson){
                header("Content-type: application/json;charset=utf-8");
                if (!$ok) {
                    $msgErro = $dsUsuario->ErrorMsg();
                    $message = array("COD"=>"402", "MSG"=>$GLOBALS['INFO_MSG'][402] . " ERROR: " . $msgErro);
                }
                else {
                    $message = array("COD"=>"202", "MSG"=>$GLOBALS['INFO_MSG'][202]);
                }
                echo TGetJSON::toJSON($message["COD"], $message["MSG"]);
            }
            
        } catch (exception $exc) {
            var_dump($exc);
        }
    }
    
    public function localizarEmpresasUsuario($dados, $toJson = true) {
        $rJson  = (bool) $toJson;
        /**
         * isMd5: 0 - Ira criptogafar dentro da classe.
         *        1 - Já vem criptografada.
         */
        $isMd5  = (int) $dados["usuario"]["crp"];
        $sEmail = $dados["usuario"]["email"];
        
        switch ($isMd5) {
            case 1:
                $sPass = $dados["usuario"]["senha"];
                break;
            default:
                $sPass = md5($dados["usuario"]["senha"]);
                break;
        }        
        
        try {
            $dsUsuario = $this->dbconn->Execute(TQuerysUsuario::getQuery("localizarEmpresasUsuario"), array($sEmail, $sPass));
            if ($rJson){
                header("Content-type: application/json;charset=utf-8");
                if ($dsUsuario->RecordCount() > 0){
                    echo TGetJSON::getJSONData("r", $dsUsuario->GetArray());
                }
                else {
                    $messageError = array("COD"=>"201", "MSG"=>"Usuário ou Senha inválidos.");
                    echo TGetJSON::getJSON("r", $messageError);
                }
            }
            $dsUsuario->Close();
            $this->dbconn->Close();
        } catch (exception $exc) {
            header("Content-type: application/json;charset=utf-8");
            echo json_encode($exc);
        }
    }

    public function localizarDadosLogin($dados, $toJson = true) {
        $rJson  = (bool) $toJson;
        
        /**
         * isMd5: 0 - Ira criptogafar dentro da classe.
         *        1 - Já vem criptografada.
         */
        $isMd5  = (int) $dados["usuario"]["crp"];
        $sEmail = $dados["usuario"]["email"];
        $sIdEmp = $dados["usuario"]["ide"];
        
        switch ($isMd5) {
            case 1:
                $sPass = $dados["usuario"]["pass"];
                break;
            default:
                $sPass = md5($dados["usuario"]["senha"]);
                break;
        }        
        
        try {
            $dsUsuario = $this->dbconn->Execute(TQuerysUsuario::getQuery("localizarDadosLogin"), array($sEmail, $sPass, $sIdEmp));
            if ($rJson){
                header("Content-type: application/json;charset=utf-8");
                if ($dsUsuario->RecordCount() > 0){
                    echo TGetJSON::getJSONData("r", $dsUsuario->GetArray());
                }
                else {
                    $messageError = array("COD"=>"201", "MSG"=>"Usuário ou Senha inválidos.");
                    echo TGetJSON::getJSON("r", $messageError);
                }
            }
            $dsUsuario->Close();
            $this->dbconn->Close();
        } catch (exception $exc) {
            header("Content-type: application/json;charset=utf-8");
            echo json_encode($exc);
        }
    }
    
    public function findUsersByEmp($dados, $toJson = true) {
        $rJson  = (bool) $toJson;

        try {
            if (isset($dados["d"]["usuario"][0]["ativo"])){
                $ativo = $dados["d"]["usuario"][0]["ativo"];
            }
            else {
                $ativo = 1;
            }
            $dsUsuario = $this->dbconn->Execute(TQuerysUsuario::getQuery("findUsersByEmp"), 
                                                array($dados["d"]["empresa"][0]["id"], 
                                                      $ativo));
            if ($rJson){
                header("Content-type: application/json;charset=utf-8");
                if ($dsUsuario->RecordCount() > 0){
                    echo TGetJSON::getJSONData("r", $dsUsuario->GetArray());
                }
                else {
                    $messageError = array("COD"=>"201", "MSG"=>htmlspecialchars($GLOBALS['message'][201]));
                    echo TGetJSON::getJSON("r", $messageError);
                }
            }
            $dsUsuario->Close();
            $this->dbconn->Close();
        } catch (exception $exc) {
            header("Content-type: application/json;charset=utf-8");
            echo json_encode($exc);
        }
    }
    
    public function findBySession($dados, $toJson = true) {
        $SQL   = (object) $this->SQLText;
        $rJson = (bool) $toJson;
        
        try {
            $dsLogin = $this->dbconn->Execute($SQL->findBySession, 
                                              array($dados["usuario"]["id_usuario"], 
                                                    $dados["usuario"]["data_acesso"],
                                                    $dados["usuario"]["chave"]));
            if ($rJson){
                if ($dsLogin->RecordCount() > 0){
                    header("Content-type: application/json;charset=utf-8");
                    echo json_encode($dsLogin->GetArray());
                }
                else {
                    $messageError = array("COD"=>"201", "MSG"=>"Usuário ou Senha inválidos.");
                    header("Content-type: application/json;charset=utf-8");
                    echo TGetJSON::toJSON($messageError["COD"], $messageError["MSG"]);
                }
            }
            $this->dbconn->Close();
        } catch (exception $exc) {
            header("Content-type: application/json;charset=utf-8");
            echo json_encode($exc);
        }
    }

    public function inserirLogAcesso($dados, $toJson = true) {
        $rJson = (bool) $toJson;
        ADODB_Active_Record::SetDatabaseAdapter($this->dbconn);
        try {
            $dsLogAccess = new DMUsuario("logacesso");
            $dsLogAccess->id_empresa    = $dados["usuario"]["id_empresa"];
            $dsLogAccess->id_usuario    = $dados["usuario"]["id_usuario"];
            $dsLogAccess->data_acesso   = $dados["usuario"]["acesso"];
            $dsLogAccess->chave         = $dados["usuario"]["chave"];
            $dsLogAccess->ativo         = 1;

            $ok = $dsLogAccess->Save();            
            if ($rJson){
                header("Content-type: application/json;charset=utf-8");
                if (!$ok) {
                    $msgErro = $dsLogAccess->ErrorMsg();
                    $message = array("COD"=>"402", "MSG"=>$GLOBALS['INFO_MSG'][402] . " ERROR: " . $msgErro);
                }
                else {
                    $message = array("COD"=>"202", "MSG"=>$GLOBALS['INFO_MSG'][202]);
                }
                echo TGetJSON::toJSON($message["COD"], $message["MSG"]);
            }
            
        } catch (exception $exc) {
            var_dump($exc);
        }
    }
    
    /**
     * 
     * @param array $dados
     * @param json $toJson
     * @link http://localhost/project/nutrado/libs/include/TJSON.class.php?className=Usuario&methodName=findByLogin&params[0][usuario][iduser]=1&params[1]=1 link para teste
     */
    public function findByLogin($dados, $toJson = true) {
        $SQL   = (object) $this->SQLText;
        $rJson = (bool) $toJson;
        $isMd5 = (boolean) false;
        
        /**
         * isMd5: 0 - Ira criptogafar dentro da classe.
         *        1 - Já irá vim criptografada.

        if (!isset($dados["usuario"]["crp"])){
            $isMd5 = (boolean) false;
        }
        else {
            $isMd5 = (boolean) $dados["usuario"]["crp"];
        }
         */
        
        try {
            /*
            $sEmail = $dados["usuario"]["email"];
            
            if ($isMd5){
                $sPass = md5($dados["usuario"]["senha"]);
            }
            else {
                $sPass = $dados["usuario"]["senha"];
            }
            */
            $dsLogin = $this->dbconn->Execute($SQL->findByLogin, 
                                              array($dados["usuario"]["iduser"]));
            if ($rJson){
                if ($dsLogin->RecordCount() > 0){
                    header("Content-type: application/json;charset=utf-8");
                    echo json_encode($dsLogin->GetArray());
                }
                else {
                    $messageError = array("COD"=>"201", "MSG"=>"Não foram encontrados registros.");
                    header("Content-type: application/json;charset=utf-8");
                    echo TGetJSON::toJSON($messageError["COD"], $messageError["MSG"]);
                }
            }
            else {
                header("Content-type: text/xml");
                $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\"?><dsUsuario/>", null, false);
                TGetXML::toXML($xml, $dsLogin->GetArray(), true);
                echo $xml->asXML();
            }
            $this->dbconn->Close();
        } catch (exception $exc) {
            header("Content-type: application/json;charset=utf-8");
            echo json_encode($exc);
        }
    }
    
    private function sendEmail($email, $subject, $message){
        require_once '../PHPMailer/PHPMailerAutoload.php';
        $mail = new PHPMailer;

        $mail->Host       = SMTPHOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTPUSER;          // SMTP username
        $mail->Password   = SMTPPASS;          // SMTP password
        //$mail->SMTPSecure = 'tls';           // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 587;               // TCP port to connect to

        $mail->setFrom(EMAILFROM, NAMEFROM);
        
        //$mail->addAddress($email, $usuario); // Add a recipient
        $mail->addAddress($email);             // Name is optional
        $mail->isHTML(true);                   // Set email format to HTML

        $mail->Subject = $subject;             // Assunto
        $mail->Body    = $message;             // Mensagem do email
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
            return "Erro ao enviar a mensagem. Mailer Error: " . $mail->ErrorInfo;
        } else {
            return "Uma nova senha foi enviada para o email informado.";
        }
    }
    
    /**
     * change Password
     * @param array $dados
     * @param boolean $toJson
     * @link http://localhost/project/nutrado/libs/include/TJSON.class.php?className=Usuario&methodName=chPwd&params[0][usuario][iduser]=1&params[0][usuario][idemp]=1&params[1]=1 link para testar a funcao
     */
    private function chPwd($dados, $toJson = true) {
        $SQL       = (object) $this->SQLText;
        $rJson     = (bool) $toJson;
        $whereSQL  = "id = {$dados["usuario"]["iduser"]} and id_empresa = {$dados["usuario"]["idemp"]}";
        $emailUser = $dados["usuario"]["email"];
        $newPass   = "";

        try {
            $dsNewPwd = $this->dbconn->Execute($SQL->newPwdSQL);

            $newPass                      = substr($dsNewPwd->fields["nova_senha"], 0, 8);
            $fieldValue["senha"]          = md5($dsNewPwd->fields["nova_senha"]);
            $fieldValue["chpwd"]          = 1;
            $fieldValue["data_alteracao"] = $dsNewPwd->fields["data_solicitacao"];
            $ok = (bool) $this->dbconn->AutoExecute("usuario", $fieldValue, "UPDATE", $whereSQL);

            if ($rJson){
                header("Content-type: application/json;charset=utf-8");
                if ($ok == (bool) false) {
                    $message = array("COD"=>"402", "MSG"=> htmlspecialchars("Não foi possível gerar uma nova senha."));
                }
                else {
                    $emailOk = $this->sendEmail($emailUser, "Nova Senha Solicitada", "Sua nova senha tempor&aacute;ria &eacute;: {$newPass}");
                    $message = array("COD"=>"203", "MSG"=>$emailOk);
                }
                header("Content-type: application/json;charset=utf-8");
                echo json_encode($message);
                //echo TGetJSON::toJSON($message["COD"], $message["MSG"]);
            }
            $this->dbconn->Close();
        } catch (exception $exc) {
            header("Content-type: application/json;charset=utf-8");
            echo json_encode($exc);
        }
    }

    /**
     * 
     * @param array $dados
     * @param boolean $toJson
     * @link http://localhost/project/nutrado/libs/include/TJSON.class.php?className=Usuario&methodName=findUserByEmail&params[0][usuario][email]=hudson@bol.com.br&params[1]=1 link para teste da funcao
     */
    public function findUserByEmail($dados, $toJson = true) {
        $SQL   = (object) $this->SQLText;
        $rJson = (bool) $toJson;
        
        try {
            $dsUser = $this->dbconn->Execute($SQL->findEmail,
                                              array($dados["usuario"]["email"]));
            if ($rJson){
                if ($dsUser->RecordCount() > 0){
                    $this->chPwd(array("usuario"=>array("iduser"=>$dsUser->fields["id"],
                                                        "idemp" =>$dsUser->fields["id_empresa"],
                                                        "email" =>$dsUser->fields["email"])));
                }
                else {
                    header("Content-type: application/json;charset=utf-8");
                    $message = array("COD"=>"201", "MSG"=>"Email não cadastrado.");
                    echo TGetJSON::toJSON($message["COD"], $message["MSG"]);
                }
            }
            else {
                header("Content-type: text/xml");
                $message = array("COD"=>"201", "MSG"=>"Email não cadastrado.");
                $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\"?><dsUsuario/>", null, false);
                TGetXML::toXML($xml, $message, true);
                echo $xml->asXML();
            }
            $this->dbconn->Close();
        } catch (exception $exc) {
            header("Content-type: application/json;charset=utf-8");
            echo json_encode($exc);
        }
    }
    
    public function changePwd($dados, $toJson = true) {
        $rJson     = (bool) $toJson;
        $whereSQL  = "email = '{$dados["usuario"]["email"]}'";

        try {
            if ($dados["usuario"]["crp"] == "1"){
                $pwd = $dados["usuario"]["pass"];
            }
            else {
                $pwd = md5($dados["usuario"]["pass"]);
            }

            $fieldValue["senha"]          = $pwd;
            $fieldValue["chpwd"]          = 0;
            $fieldValue["data_alteracao"] = date("Y/m/d H:i:s");
            $ok = $this->dbconn->AutoExecute("usuario", $fieldValue, "UPDATE", $whereSQL);

            if ($rJson){
                if ($ok){
                    header("Content-type: application/json;charset=utf-8");
                    $message = array("COD"=>"202", "MSG"=> htmlspecialchars("Senha alterada com sucesso."));
                    echo TGetJSON::getJSON("f", $message);
                }
                else {
                    header("Content-type: application/json;charset=utf-8");
                    $message = array("COD"=>"402", "MSG"=> htmlspecialchars("Não foi possível alterar a senha."));
                    echo TGetJSON::getJSON("f", $message);
                }
            }
            $this->dbconn->Close();

        } catch (exception $exc) {
            header("Content-type: application/json;charset=utf-8");
            $message = array("COD"=>"402", "MSG"=> htmlspecialchars("Não foi possível alterar a senha. Erro: {$exc}"));
            echo TGetJSON::getJSON("f", $message);
        }
    }
    
    public function viewDetalhesUsuario($dados, $toJson = true) {
        $rJson  = (bool) $toJson;

        try {
            $dsDetalheUsuario = $this->dbconn->Execute(TQuerysUsuario::getQuery("viewDetalhesUsuario"), 
                                                       array($dados["d"]["empresa"][0]["id"], 
                                                             $dados["d"]["usuario"][0]["id"]));
            if ($rJson){
                header("Content-type: application/json;charset=utf-8");
                if ($dsDetalheUsuario->RecordCount() > 0){
                    echo TGetJSON::getJSONData("r", $dsDetalheUsuario->GetArray());
                    //print_r($dsDetalheUsuario->GetArray());
                }
                else {
                    $messageError = array("COD"=>"201", "MSG"=>htmlspecialchars($GLOBALS['message'][201]));
                    echo TGetJSON::getJSON("r", $messageError);
                }
            }
            else {
                if ($dsDetalheUsuario->RecordCount() > 0){
                    $message = array("r" => $dsDetalheUsuario->GetArray());
                    return $message;
                }
                else {
                    $message = array("r" => array("COD"=>"201", "MSG"=> htmlspecialchars($GLOBALS['message'][201])));
                    return $message;
                }
            }
            $dsDetalheUsuario->Close();
            $this->dbconn->Close();
        } catch (exception $exc) {
            header("Content-type: application/json;charset=utf-8");
            echo json_encode($exc);
        }
    }
    
    public function editarDetalhesUsuario($dados, $toJson = true) {
        $rJson     = (bool) $toJson;
        $idCep     = 0;
        $cep       = 0;
        $whereSQL  = "id = {$dados["d"]["dadosusuario"][0]["id"]} and id_empresa = {$dados["d"]["empresa"][0]["id"]}";
        $whereSQLu = "id = {$dados["d"]["usuario"][0]["id"]} and id_empresa = {$dados["d"]["empresa"][0]["id"]}";

        try {
            $c = (object) (new Cep)->exists($dados);
            if ($c->r["COD"] == "200"){
                $idCep = $c->r["ID"];
                $cep   = $c->r["CEP"];
            }
            else {
                $insC = (object) (new Cep)->inserir($dados);
                $idCep = $insC->r["ID"];
                $cep   = $insC->r["CEP"];
            }
            
            $fieldValue["id_endereco"]   = $idCep;
            $fieldValue["cep_endereco"]  = $cep;            
            $fieldValue["nome"]          = $dados["d"]["dadosusuario"][0]["nome"];
            $fieldValue["cpf"]           = $dados["d"]["dadosusuario"][0]["cpf"];
            $fieldValue["numero"]        = $dados["d"]["dadosusuario"][0]["numero"];
            $fieldValue["complemento"]   = $dados["d"]["dadosusuario"][0]["complemento"];
            $fieldValue["email"]         = $dados["d"]["dadosusuario"][0]["emailpessoal"];
            $fieldValue["lat"]           = $dados["d"]["dadosusuario"][0]["lat"];
            $fieldValue["lng"]           = $dados["d"]["dadosusuario"][0]["lng"];
            $fieldValue["dataalteracao"] = date("Y/m/d");
            
            $ok = $this->dbconn->AutoExecute("dadosusuario", $fieldValue, "UPDATE", $whereSQL);

            if ($rJson){
                if ($ok){
                    $uFieldValue["nome"] = $dados["d"]["usuario"][0]["nome"];
                    $this->dbconn->AutoExecute("usuario", $uFieldValue, "UPDATE", $whereSQLu);
                    
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
}
