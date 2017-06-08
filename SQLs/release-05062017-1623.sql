-- MySQL Workbench Synchronization
-- Generated: 2017-06-05 16:22
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Hudson Martins

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `dbcloud`.`useradmin` 
ADD INDEX `idxEmailUserAdm` (`login` ASC, `id_empresa` ASC, `ativo` ASC);

ALTER TABLE `dbcloud`.`usuario` 
ADD INDEX `idxEmailUser` (`email` ASC, `id_empresa` ASC, `ativo` ASC);

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
