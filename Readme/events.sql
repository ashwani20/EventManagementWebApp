
-- -----------------------------------------------------
-- Table `venue`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `venue` ;

CREATE TABLE IF NOT EXISTS `venue` (
  `idvenue` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `capacity` INT NULL,
  PRIMARY KEY (`idvenue`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `event` ;

CREATE TABLE IF NOT EXISTS `event` (
  `idevent` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `datestart` DATETIME NOT NULL,
  `dateend` DATETIME NOT NULL,
  `numberallowed` INT NOT NULL,
  `venue` INT NOT NULL,
  PRIMARY KEY (`idevent`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  INDEX `venue_fk_idx` (`venue` ASC))
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `session`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `session` ;

CREATE TABLE IF NOT EXISTS `session` (
  `idsession` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `numberallowed` INT NOT NULL,
  `event` INT NOT NULL,
  `startdate` DATETIME NOT NULL,
  `enddate` DATETIME NOT NULL,
  PRIMARY KEY (`idsession`))
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `attendee`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `attendee` ;

CREATE TABLE IF NOT EXISTS `attendee` (
  `idattendee` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `role` INT NULL,
  PRIMARY KEY (`idattendee`),
  INDEX `role_idx` (`role` ASC))
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `manager_event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `manager_event` ;

CREATE TABLE IF NOT EXISTS `manager_event` (
  `event` INT NOT NULL,
  `manager` INT NOT NULL,
	PRIMARY KEY (`event`, `manager`))
ENGINE = MyISAM;

-- -----------------------------------------------------
-- Table `attendee_event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `attendee_event` ;

CREATE TABLE IF NOT EXISTS `attendee_event` (
  `event` INT NOT NULL,
  `attendee` INT NOT NULL,
  `paid` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`event`, `attendee`))
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `attendee_session`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `attendee_session` ;

CREATE TABLE IF NOT EXISTS `attendee_session` (
  `session` INT NOT NULL,
  `attendee` INT NOT NULL,
  PRIMARY KEY (`session`, `attendee`))
ENGINE = MyISAM;

-- -----------------------------------------------------
-- Table `role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roles` ;
DROP TABLE IF EXISTS `role` ;

CREATE TABLE IF NOT EXISTS `role` (
  `idrole` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idrole`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = MyISAM;

INSERT INTO `role` (`name`) values ('admin'),('event manager'),('attendee');

