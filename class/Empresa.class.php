<?php

/**
 * Description of Empresa
 *
 * @author hudson
 * @version 0.0.1
 */
interface iEmpresa {
    public function getVersion();
    public function inserir($dados, $toJson = true);
    public function findAllByEmail($dados, $toJson = true);
    public function findBySession($dados, $toJson = true);
    public function inserirLogAcesso($dados, $toJson = true);
    public function findByLogin($dados, $toJson = true);
    public function findUserByEmail($dados, $toJson = true);
    public function changePwd($dados, $toJson = true);
}

class DMEmpresa extends ADODB_Active_Record {}

class Empresa extends DMEmpresa implements iEmpresa{
    private $sVersion = "0.0.1";
    private $dbconn  = null;
    private $SQLText = array("findByCnpj"=>"select
                                              e.id,
                                              e.id_endereco,
                                              e.cep_endereco,
                                              e.razaosocial,
                                              e.nomefantasia,
                                              e.cnpj,
                                              en.endereco,
                                              e.numero,
                                              en.bairro,
                                              e.complemento,
                                              u.sigla,
                                              e.site,
                                              e.lat,
                                              e.lng,
                                              e.datacadastro,
                                              e.ativo
                                            from empresa e, endereco en, cidades c, estados u
                                            where en.id  = e.id_endereco
                                              and c.id   = en.id_cidade
                                              and u.id   = en.id_uf
                                              and e.cnpj = ?",
                             "findEmail"=>"select
                                             u.id, 
                                             u.id_empresa, 
                                             u.email, 
                                             u.login
                                           from usuario u, empresa e
                                           where e.id            = u.id_empresa
                                             and u.email         = ?
                                             and u.ativo         = 1
                                             and u.administrador = 1",
                             "findAllByEmail"=>"select
                                                  u.id, 
                                                  u.id_empresa, 
                                                  u.email, 
                                                  u.login, 
                                                  u.senha, 
                                                  u.ativo, 
                                                  u.administrador,
                                                  u.chpwd as mdsnh,
                                                  e.razaosocial,
                                                  e.nomefantasia,
                                                  e.latitude as lat,
                                                  e.longitude as lng
                                                from usuario u, empresa e
                                                where e.id = u.id_empresa
                                                  and u.email = ?
                                                  and u.senha = ?
                                                  and u.ativo = 1
                                                  and u.administrador = 1",
                             "findBySession"=>"select 
                                                 la.id, 
                                                 la.id_empresa, 
                                                 la.id_usuario, 
                                                 la.data_acesso, 
                                                 la.chave, 
                                                 la.ativo 
                                               from logacesso la
                                               where
                                                     la.id_usuario  = ? 
                                                 and la.data_acesso = ?
                                                 and la.chave       = ?
                                                 and la.ativo       = 1",
                             "findByLogin"=>"select
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
                             "findAllByCEP"=>"select
                                                e.id,
                                                e.cep,
                                                e.ibge,
                                                e.endereco,
                                                e.bairro,
                                                e.id_cidade,
                                                e.uf,
                                                e.estado
                                              from endereco e
                                              where e.cep = ?",
                             "findCity"=>"select
                                            c.id,
                                            c.id_estado,
                                            c.nome,
                                            c.capital
                                          from cidades c
                                          where c.nome = ?
                                          order by c.capital",
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
        $SQL   = (object) $this->SQLText;
        ADODB_Active_Record::SetDatabaseAdapter($this->dbconn);
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
            
            /**
             * A Classe DMEmpresa irá acessar a tabela 'empresa' 
             * baseada na conexão setada na linha: ADODB_Active_Record::SetDatabaseAdapter($this->dbconn);
             */
            $dsEmpresa = new DMEmpresa("empresa");
            $dsEmpresa->razaosocial   = $dados["d"]["empresa"][0]["razaosocial"];
            $dsEmpresa->nomefantasia  = $dados["d"]["empresa"][0]["nomefantasia"];
            $dsEmpresa->cnpj          = $dados["d"]["empresa"][0]["cnpj"];
            
            $dsEmpresa->id_endereco   = $idCep;
            $dsEmpresa->cep_endereco  = $cep;
            
            $dsEmpresa->id_plano      = $dados["d"]["plano"][0]["op"];
            
            $dsEmpresa->numero        = $dados["d"]["empresa"][0]["numero"];
            $dsEmpresa->complemento   = $dados["d"]["empresa"][0]["complemento"];
            $dsEmpresa->site          = $dados["d"]["empresa"][0]["site"];
            $dsEmpresa->email         = $dados["d"]["empresa"][0]["email"];
            $dsEmpresa->lat           = $dados["d"]["empresa"][0]["lat"];
            $dsEmpresa->lng           = $dados["d"]["empresa"][0]["lon"];
            $dsEmpresa->datacadastro  = date('Y-m-d'); //date("Y/m/d H:i:s");
            $dsEmpresa->ativo         = false;

            $ok         = $dsEmpresa->Save();
            
            $dsEmp      = $this->dbconn->Execute($SQL->findByCnpj, array($dados["d"]["empresa"][0]["cnpj"]));
            $id_empresa = $dsEmp->fields["id"];
            $dsEmp->Close();
            
            if ($rJson){
                header("Content-type: application/json;charset=utf-8");
                if (!$ok) {
                    $msgErro = $dsEmpresa->ErrorMsg();
                    $message = array("COD"=>"402", "MSG"=>$GLOBALS['message'][402] . " ERROR: " . $msgErro, "IDE" => "0");
                }
                else {
                    for ($s = 0; $s < count($dados["d"]["sistemas"]); $s++){
                        $dsSistemasEmpresa = new DMEmpresa("sistemas_empresa");
                        $dsSistemasEmpresa->id_empresa = $id_empresa;
                        $dsSistemasEmpresa->id_sistema = $dados["d"]["sistemas"][$s]["sid"];
                        $dsSistemasEmpresa->Save();
                    }
                    $dsUserAdmin = new DMEmpresa("useradmin");
                    $dsUserAdmin->id_empresa = $id_empresa;
                    $dsUserAdmin->login      = $dados["d"]["empresa"][0]["email"];
                    $dsUserAdmin->senha      = $dados["d"]["user"][0]["pwd"];
                    $dsUserAdmin->ativo      = 1;
                    $dsUserAdmin->Save();
                    
                    $dsValidadePlano = new DMEmpresa("validadeplano");
                    $dsValidadePlano->id_empresa   = $id_empresa;
                    $dsValidadePlano->id_plano     = $dados["d"]["plano"][0]["op"];
                    $dsValidadePlano->datacadastro = date('Y-m-d'); //date("Y/m/d H:i:s");
                    $dsValidadePlano->Save();
                    
                    /**
                     * @todo Criar a classe com a função de captura do ultimo registro para a empresa informada
                     */
                    $dsGrupoUsuario = new DMEmpresa("grupoacesso");
                    $dsGrupoUsuario->id_empresa = $id_empresa;
                    $dsGrupoUsuario->nome       = "Administrador";
                    $dsGrupoUsuario->ativo      = true;
                    $dsGrupoUsuario->Save();
                    
                    $dsUsuario               = new DMEmpresa("usuario");
                    $dsUsuario->id_empresa   = $id_empresa;
                    $dsUsuario->id_grupo     = $dsGrupoUsuario->id;
                    $dsUsuario->email        = $dados["d"]["empresa"][0]["email"];
                    $dsUsuario->pass         = $dados["d"]["user"][0]["pwd"];
                    $dsUsuario->nome         = "administrador";
                    $dsUsuario->tipo         = "ADMN";
                    $dsUsuario->chpwd        = 0;
                    $dsUsuario->datacadastro = date('Y-m-d');
                    $dsUsuario->ativo        = 1;
                    $dsUsuario->Save();
                    
                    $rFone = (object) (new FoneEmpresa)->inserir($dados, $id_empresa);
                    if ($rFone->r["COD"] == "202"){
                        $message = array("COD"=>"202", 
                                         "MSG"=>$GLOBALS['message'][202] . 
                                                "\nApós a confirmação do pagamento estaremos enviando o email com a liberação de acesso " .
                                                "e instruções iniciais de uso.", 
                                         "IDE" => $id_empresa);
                    }
                    else {
                        $message = array("COD"=>"202", "MSG"=>$GLOBALS['message'][202] . "\nObs.: Apenas os telefones não foram salvos.");
                    }
                }
                echo TGetJSON::getJSON("r", $message);
            }
        } catch (exception $exc) {
            $message = array("COD"=>"101", "MSG"=>$exc->getMessage());
            echo TGetJSON::getJSON("r", $message);
        }
    }
    
    public function findAllByEmail($dados, $toJson = true) {
        $SQL    = (object) $this->SQLText;
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
            $dsUsuario = $this->dbconn->Execute($SQL->findAllByEmail, 
                                               array($sEmail, $sPass));
            if ($rJson){
                if ($dsUsuario->RecordCount() > 0){
                    session_start();
                    $_SESSION["razaosocial"] = $dsUsuario->fields["razaosocial"];
                    header("Content-type: application/json;charset=utf-8");
                    echo json_encode($dsUsuario->GetArray());
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
}
