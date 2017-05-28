<?php

/**
 * App
 */

define("APPTITLE", "Peca Aqui");
define("APPSUBTITLE", "Sistema de Solicitações");
define("APPVERSION", "1.0");
define("APPDEVELOPER", "Hudson Martins");
define("UPLOAD_PATH", "upload");
define("APP_DIR", ".");

/**
 * Config. para envio de Email
 */

define("SMTPHOST", "localhost");
define("SMTPUSER", "root");
define("SMTPPASS", "omegasys");
define("EMAILFROM", "hudson.martins@nutrado.com.br");
define("NAMEFROM", "Hudson Martins - Analista Desenvolvedor");


/**
  * Config. de conexão
  */
define("DRV_MYSQL", "mysql");
define("DSN_MYSQL", "localhost");
define("UID_MYSQL", "root");
define("PWD_MYSQL", "");
define("DBN_MYSQL", "dbcloud");

/**
  * Configuração de conexão Remota
  
define("DRV_MYSQL", "mysql");
define("UID_MYSQL", "nutrado1");
define("PWD_MYSQL", "gcsa2015nutri");
define("DSN_MYSQL", "mysql01.nutrado1.hospedagemdesites.ws");
define("DBN_MYSQL", "nutrado1");
  */


/**
 * Config. Scripts (javascript) e Link (css)
 */
$cHead["js"]["jquery"]     = "<script src=\"libs/jquery/jquery.js\" type=\"text/javascript\"></script> \r\n";
$cHead["js"]["maskedit"]   = "<script src=\"libs/jquery/jquery.maskedinput.min.js\" type=\"text/javascript\"></script> \r\n";
$cHead["js"]["bootstrap"]  = "<script src=\"libs/bootstrap/js/bootstrap.min.js\" type=\"text/javascript\"></script> \r\n";

$cHead["js"]["leaflet"]    = "<script src=\"libs/leaflet/leaflet.js\" type=\"text/javascript\"></script> \r\n";
$cHead["js"]["maplace"]    = "<script src=\"libs/maplace/maplace.min.js\" type=\"text/javascript\"></script> \r\n";

$cHead["js"]["datatables"] = "<script src=\"libs/datatables/datatables.min.js\" type=\"text/javascript\"></script> \r\n";

$cHead["css"]["bootstrap"]["default"] = "<link href=\"libs/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\"> \r\n";
$cHead["css"]["bootstrap"]["united"]  = "<link href=\"libs/bootstrap/css/bootstrap-united.css\" rel=\"stylesheet\"> \r\n";
$cHead["css"]["bootstrap"]["paper"]   = "<link href=\"libs/bootstrap/css/bootstrap-paper.css\" rel=\"stylesheet\"> \r\n";
$cHead["css"]["bootstrap"]["darkly"]  = "<link href=\"libs/bootstrap/css/bootstrap-darkly.css\" rel=\"stylesheet\"> \r\n";

$cHead["css"]["font"]                 = "<link href=\"libs/font-awesome/css/font-awesome.min.css\" rel=\"stylesheet\"> \r\n";
$cHead["css"]["datatables"]           = "<link href=\"libs/datatables/datatables.min.css\" rel=\"stylesheet\"> \r\n";

$cHead["css"]["leaflet"]              = "<link href=\"libs/leaflet/leaflet.css\" rel=\"stylesheet\"> \r\n";

$cHead["css"]["app"]                  = "<link href=\"libs/css/app.css\" rel=\"stylesheet\"> \r\n";

/**
 * Configuração de Impressão
 *
define('FPDF_FONTPATH','fpdf/font/');
 */

/**
 * 
SET FOREIGN_KEY_CHECKS = 0; 

truncate table endereco;
truncate table fones;
truncate table empresa;
truncate table useradmin;
truncate table validadeplano;
truncate table sistemas_empresa;
truncate table grupoacesso;
truncate table usuario;

SET FOREIGN_KEY_CHECKS = 1; 
 */


?>
