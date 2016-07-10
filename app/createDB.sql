-- MySQL Script generated by MySQL Workbench
-- 07/10/16 13:14:42
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema symfony
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema symfony
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `symfony` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `symfony` ;

-- -----------------------------------------------------
-- Table `symfony`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `email` VARCHAR(60) NOT NULL COMMENT '',
  `username` VARCHAR(25) NOT NULL COMMENT '',
  `password` VARCHAR(64) NOT NULL COMMENT '',
  `created` DATETIME NOT NULL COMMENT '',
  `updated` DATETIME NOT NULL COMMENT '',
  `logged` DATETIME NULL COMMENT '',
  `roles` VARCHAR(45) NULL COMMENT '',
  `is_active` TINYINT(1) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `symfony`.`circle`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`circle` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(256) NOT NULL COMMENT '',
  `user_id` INT NOT NULL COMMENT '',
  `date_create` DATETIME NULL COMMENT '',
  `date_update` DATETIME NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_circle_users1_idx` (`user_id` ASC)  COMMENT '',
  CONSTRAINT `fk_circle_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `symfony`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `symfony`.`sectors`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`sectors` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `begin_angle` FLOAT NULL COMMENT '',
  `end_angle` FLOAT NULL COMMENT '',
  `name` VARCHAR(45) NULL COMMENT '',
  `circle_id` INT NOT NULL COMMENT '',
  `parent_sector_id` INT NULL COMMENT '',
  `date_create` DATETIME NULL COMMENT '',
  `date_update` DATETIME NULL COMMENT '',
  `color` VARCHAR(10) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_sectors_circle1_idx` (`circle_id` ASC)  COMMENT '',
  CONSTRAINT `fk_sectors_circle1`
    FOREIGN KEY (`circle_id`)
    REFERENCES `symfony`.`circle` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `symfony`.`layers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`layers` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `begin_radius` FLOAT NULL COMMENT '',
  `end_radius` FLOAT NULL COMMENT '',
  `circle_id` INT NOT NULL COMMENT '',
  `date_create` DATETIME NULL COMMENT '',
  `date_update` DATETIME NULL COMMENT '',
  `color` VARCHAR(10) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_layers_circle1_idx` (`circle_id` ASC)  COMMENT '',
  CONSTRAINT `fk_layers_circle1`
    FOREIGN KEY (`circle_id`)
    REFERENCES `symfony`.`circle` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `symfony`.`labels`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`labels` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `date_create` DATETIME NULL COMMENT '',
  `date_update` DATETIME NULL COMMENT '',
  `angle` FLOAT NOT NULL COMMENT '',
  `radius` FLOAT NOT NULL COMMENT '',
  `sectors_id` INT NOT NULL COMMENT '',
  `layers_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_labels_sectors1_idx` (`sectors_id` ASC)  COMMENT '',
  INDEX `fk_labels_layers1_idx` (`layers_id` ASC)  COMMENT '',
  CONSTRAINT `fk_labels_sectors1`
    FOREIGN KEY (`sectors_id`)
    REFERENCES `symfony`.`sectors` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_labels_layers1`
    FOREIGN KEY (`layers_id`)
    REFERENCES `symfony`.`layers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `symfony`.`notes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`notes` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(1024) NULL COMMENT '',
  `text` LONGTEXT NULL COMMENT '',
  `users_id` INT NOT NULL COMMENT '',
  `date_create` DATETIME NULL COMMENT '',
  `date_update` DATETIME NULL COMMENT '',
  `labels_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_notes_users_idx` (`users_id` ASC)  COMMENT '',
  INDEX `fk_notes_labels1_idx` (`labels_id` ASC)  COMMENT '',
  CONSTRAINT `fk_notes_users`
    FOREIGN KEY (`users_id`)
    REFERENCES `symfony`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_notes_labels1`
    FOREIGN KEY (`labels_id`)
    REFERENCES `symfony`.`labels` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
