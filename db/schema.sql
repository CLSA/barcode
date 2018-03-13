-- MySQL Script generated by MySQL Workbench
-- Tue 13 Mar 2018 02:51:21 PM EDT
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='';

-- -----------------------------------------------------
-- Schema barcode
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema barcode
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `barcode` DEFAULT CHARACTER SET utf8 ;
USE `barcode` ;

-- -----------------------------------------------------
-- Table `barcode`.`interview`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `barcode`.`interview` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `update_timestamp` TIMESTAMP NOT NULL,
  `create_timestamp` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 10000000
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
