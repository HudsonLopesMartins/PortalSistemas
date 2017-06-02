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

-- Copiando estrutura para tabela dbcloud.dadosusuario
CREATE TABLE IF NOT EXISTS `dadosusuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_endereco` int(11) NOT NULL,
  `cep_endereco` varchar(15) COLLATE utf8_bin NOT NULL,
  `nome` varchar(50) COLLATE utf8_bin NOT NULL,
  `cpf` varchar(16) COLLATE utf8_bin NOT NULL,
  `numero` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `complemento` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(90) COLLATE utf8_bin DEFAULT NULL,
  `lat` decimal(11,9) NOT NULL DEFAULT '0.000000000',
  `lng` decimal(11,9) NOT NULL DEFAULT '0.000000000',
  `datacadastro` date DEFAULT NULL,
  `ativo` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idxCpfUsuario` (`cpf`,`ativo`),
  KEY `idxNomeUsuario` (`nome`,`ativo`),
  KEY `idxEmailUsuario` (`email`,`ativo`),
  KEY `fkUsuarioLogin_idx` (`id_usuario`),
  KEY `fkUsuarioEmp_idx` (`id_empresa`),
  KEY `idxCepUsuario` (`cep_endereco`,`ativo`),
  KEY `fkUsuarioCep_idx` (`cep_endereco`,`id_endereco`),
  CONSTRAINT `fkUsuarioCep` FOREIGN KEY (`cep_endereco`, `id_endereco`) REFERENCES `endereco` (`cep`, `id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fkUsuarioEmp` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fkUsuarioLogin` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Copiando dados para a tabela dbcloud.dadosusuario: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `dadosusuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `dadosusuario` ENABLE KEYS */;

-- Copiando estrutura para tabela dbcloud.fonesusuarios
CREATE TABLE IF NOT EXISTS `fonesusuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `fone` varchar(16) COLLATE utf8_bin DEFAULT NULL,
  `principal` tinyint(4) NOT NULL DEFAULT '0',
  `tipo` char(4) COLLATE utf8_bin NOT NULL DEFAULT '0000' COMMENT 'RSDL - Residencial\nCMCL - Comercial\nFAXC - Fax Comercial\nFAXR - Fax Residencial\nCELL - Celular',
  PRIMARY KEY (`id`),
  KEY `idxDetalheUsuario` (`id_usuario`),
  CONSTRAINT `fkDetalheUsuario` FOREIGN KEY (`id_usuario`) REFERENCES `dadosusuario` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Copiando dados para a tabela dbcloud.fonesusuarios: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `fonesusuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `fonesusuarios` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
