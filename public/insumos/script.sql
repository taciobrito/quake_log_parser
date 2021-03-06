-- MySQL Script generated by MySQL Workbench
-- Mon May 27 11:24:24 2019
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema quake_log_parser
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema quake_log_parser
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `quake_log_parser` DEFAULT CHARACTER SET utf8 ;
USE `quake_log_parser` ;

-- -----------------------------------------------------
-- Table `quake_log_parser`.`games`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `quake_log_parser`.`games` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(255) NULL,
  `start` TIME NULL,
  `end` TIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `quake_log_parser`.`players`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `quake_log_parser`.`players` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `quake_log_parser`.`means_of_death`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `quake_log_parser`.`means_of_death` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `quake_log_parser`.`kills`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `quake_log_parser`.`kills` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `killed_at` TIME NULL,
  `type_killer` ENUM('player', 'world') NULL DEFAULT 'player',
  `game_id` INT NOT NULL,
  `means_of_death_id` INT NOT NULL,
  `player_killed_id` INT NOT NULL,
  `player_killer_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_kills_games1_idx` (`game_id` ASC),
  INDEX `fk_kills_means_of_death1_idx` (`means_of_death_id` ASC),
  INDEX `fk_kills_players1_idx` (`player_killed_id` ASC),
  INDEX `fk_kills_players2_idx` (`player_killer_id` ASC),
  CONSTRAINT `fk_kills_games1`
    FOREIGN KEY (`game_id`)
    REFERENCES `quake_log_parser`.`games` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_kills_means_of_death1`
    FOREIGN KEY (`means_of_death_id`)
    REFERENCES `quake_log_parser`.`means_of_death` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_kills_players1`
    FOREIGN KEY (`player_killed_id`)
    REFERENCES `quake_log_parser`.`players` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_kills_players2`
    FOREIGN KEY (`player_killer_id`)
    REFERENCES `quake_log_parser`.`players` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `quake_log_parser`.`game_player`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `quake_log_parser`.`game_player` (
  `game_id` INT NOT NULL,
  `player_id` INT NOT NULL,
  PRIMARY KEY (`game_id`, `player_id`),
  INDEX `fk_games_has_players_players1_idx` (`player_id` ASC),
  INDEX `fk_games_has_players_games1_idx` (`game_id` ASC),
  CONSTRAINT `fk_games_has_players_games1`
    FOREIGN KEY (`game_id`)
    REFERENCES `quake_log_parser`.`games` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_games_has_players_players1`
    FOREIGN KEY (`player_id`)
    REFERENCES `quake_log_parser`.`players` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
