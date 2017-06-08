-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.1.21-MariaDB - Source distribution
-- OS do Servidor:               Linux
-- HeidiSQL Versão:              9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Copiando estrutura do banco de dados para dbcloud
CREATE DATABASE IF NOT EXISTS `dbcloud` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_bin */;
USE `dbcloud`;

-- Copiando estrutura para view dbcloud.vwdetalheusuario
-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `vwdetalheusuario` (
	`id_empresa` INT(11) NOT NULL,
	`id_grupo` INT(11) NOT NULL,
	`id_usuario` INT(11) NOT NULL,
	`id_dadousuario` INT(11) NOT NULL,
	`id_endereco` INT(11) NOT NULL,
	`id_cidade` INT(11) NOT NULL,
	`id_uf` INT(11) NOT NULL,
	`grupo` VARCHAR(45) NULL COLLATE 'utf8_bin',
	`email_usuario` VARCHAR(90) NOT NULL COLLATE 'utf8_bin',
	`nome_usuario` VARCHAR(45) NULL COLLATE 'utf8_bin',
	`ramal` VARCHAR(8) NULL COLLATE 'utf8_bin',
	`cpf` VARCHAR(16) NOT NULL COLLATE 'utf8_bin',
	`endereco` VARCHAR(50) NULL COLLATE 'utf8_bin',
	`numero` VARCHAR(10) NULL COLLATE 'utf8_bin',
	`complemento` VARCHAR(50) NULL COLLATE 'utf8_bin',
	`bairro` VARCHAR(50) NULL COLLATE 'utf8_bin',
	`email_pessoal` VARCHAR(90) NULL COLLATE 'utf8_bin',
	`lat` DECIMAL(11,9) NOT NULL,
	`lng` DECIMAL(11,9) NOT NULL,
	`cep` VARCHAR(15) NOT NULL COLLATE 'utf8_bin',
	`nome_cidade` VARCHAR(75) NOT NULL COLLATE 'utf8_general_ci',
	`sigla` CHAR(2) NOT NULL COLLATE 'utf8_general_ci',
	`tipo` CHAR(4) NOT NULL COMMENT 'DFLT - default\nADMN - administrador' COLLATE 'utf8_bin',
	`datacadastro` DATE NULL,
	`ativo` TINYINT(4) NOT NULL,
	`is_adm` INT(1) NOT NULL
) ENGINE=MyISAM;

-- Copiando estrutura para view dbcloud.vwdetalheusuario
-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `vwdetalheusuario`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vwdetalheusuario` AS select `u`.`id_empresa` AS `id_empresa`,`u`.`id_grupo` AS `id_grupo`,`u`.`id` AS `id_usuario`,`du`.`id` AS `id_dadousuario`,`en`.`id` AS `id_endereco`,`cid`.`id` AS `id_cidade`,`uf`.`id` AS `id_uf`,`ga`.`nome` AS `grupo`,`u`.`email` AS `email_usuario`,`u`.`nome` AS `nome_usuario`,`u`.`ramal` AS `ramal`,`du`.`cpf` AS `cpf`,`en`.`endereco` AS `endereco`,`du`.`numero` AS `numero`,`du`.`complemento` AS `complemento`,`en`.`bairro` AS `bairro`,`du`.`email` AS `email_pessoal`,`du`.`lat` AS `lat`,`du`.`lng` AS `lng`,`en`.`cep` AS `cep`,`cid`.`nome` AS `nome_cidade`,`uf`.`sigla` AS `sigla`,`u`.`tipo` AS `tipo`,`u`.`datacadastro` AS `datacadastro`,`u`.`ativo` AS `ativo`,((select `uadm`.`id` from `useradmin` `uadm` where ((`uadm`.`login` = `u`.`email`) and (`uadm`.`id_empresa` = `u`.`id_empresa`) and (`uadm`.`ativo` = `u`.`ativo`))) is not null) AS `is_adm` from ((((((`usuario` `u` join `empresa` `e`) join `grupoacesso` `ga`) join `dadosusuario` `du`) join `endereco` `en`) join `cidades` `cid`) join `estados` `uf`) where ((`e`.`id` = `u`.`id_empresa`) and (`ga`.`id` = `u`.`id_grupo`) and (`du`.`id_usuario` = `u`.`id`) and (`en`.`id` = `du`.`id_endereco`) and (`en`.`cep` = `du`.`cep_endereco`) and (`cid`.`id` = `en`.`id_cidade`) and (`uf`.`id` = `en`.`id_uf`)) order by `u`.`id_empresa`,`u`.`id_grupo`;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
