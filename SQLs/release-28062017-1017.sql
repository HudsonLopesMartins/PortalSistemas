-- MySQL Workbench Synchronization
-- Generated: 2017-06-28 10:13
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Hudson Martins

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER SCHEMA `dbcloud`  DEFAULT COLLATE utf8_general_ci ;

ALTER TABLE `dbcloud`.`cidades` 
COLLATE = utf8_general_ci ;

ALTER TABLE `dbcloud`.`estados` 
COLLATE = utf8_general_ci ;

ALTER TABLE `dbcloud`.`validadeplano` 
COLLATE = utf8_general_ci ;

ALTER TABLE `dbcloud`.`sistemas` 
CHANGE COLUMN `valorassinatura` `valorassinatura` REAL NOT NULL DEFAULT 0 COMMENT 'valor a ser cobrado/aluguel' ;

ALTER TABLE `dbcloud`.`dadosusuario` 
ADD COLUMN `dataalteracao` DATE NULL DEFAULT NULL AFTER `datacadastro`;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

