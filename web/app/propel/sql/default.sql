
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- emp_acc
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `emp_acc`;

CREATE TABLE `emp_acc`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(45) NOT NULL,
    `password` VARCHAR(45) NOT NULL,
    `timestamp` DATETIME NOT NULL,
    `ip_add` VARCHAR(45) NOT NULL,
    `status` VARCHAR(45) NOT NULL,
    `email` VARCHAR(45) NOT NULL,
    `role` VARCHAR(45) NOT NULL,
    `key` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- emp_approval
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `emp_approval`;

CREATE TABLE `emp_approval`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `request` VARCHAR(45) NOT NULL,
    `status` VARCHAR(45) NOT NULL,
    `date` DATETIME NOT NULL,
    `ip_add` VARCHAR(45) NOT NULL,
    `emp_time_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_emp_approval_emp_time1_idx` (`emp_time_id`),
    CONSTRAINT `fk_emp_approval_emp_time1`
        FOREIGN KEY (`emp_time_id`)
        REFERENCES `emp_time` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- emp_contact
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `emp_contact`;

CREATE TABLE `emp_contact`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `contact` VARCHAR(45) NOT NULL,
    `emp_profile_id` INTEGER NOT NULL,
    `list_cont_types_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_emp_contact_emp_profile1_idx` (`emp_profile_id`),
    INDEX `fk_emp_contact_list_cont_types1_idx` (`list_cont_types_id`),
    CONSTRAINT `fk_emp_contact_emp_profile1`
        FOREIGN KEY (`emp_profile_id`)
        REFERENCES `emp_profile` (`id`),
    CONSTRAINT `fk_emp_contact_list_cont_types1`
        FOREIGN KEY (`list_cont_types_id`)
        REFERENCES `list_cont_types` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- emp_leave
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `emp_leave`;

CREATE TABLE `emp_leave`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `request` VARCHAR(100) NOT NULL,
    `status` VARCHAR(45) NOT NULL,
    `date_started` DATETIME NOT NULL,
    `date_ended` DATETIME NOT NULL,
    `emp_acc_id` INTEGER NOT NULL,
    `list_leave_type_id` INTEGER NOT NULL,
    `admin_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_emp_leave_emp_acc1_idx` (`emp_acc_id`),
    INDEX `fk_emp_leave_emp_acc2_idx` (`admin_id`),
    INDEX `fk_emp_leave_list_leave_type1_idx` (`list_leave_type_id`),
    CONSTRAINT `fk_emp_leave_emp_acc1`
        FOREIGN KEY (`emp_acc_id`)
        REFERENCES `emp_acc` (`id`),
    CONSTRAINT `fk_emp_leave_list_leave_type1`
        FOREIGN KEY (`list_leave_type_id`)
        REFERENCES `list_leave_type` (`id`),
    CONSTRAINT `fk_emp_leave_emp_acc2`
        FOREIGN KEY (`admin_id`)
        REFERENCES `emp_acc` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- emp_profile
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `emp_profile`;

CREATE TABLE `emp_profile`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `emp_acc_acc_id` INTEGER NOT NULL,
    `fname` VARCHAR(45) NOT NULL,
    `lname` VARCHAR(45) NOT NULL,
    `mname` VARCHAR(45) NOT NULL,
    `bday` DATETIME NOT NULL,
    `address` VARCHAR(45) NOT NULL,
    `gender` VARCHAR(10) NOT NULL,
    `img_path` VARCHAR(45) NOT NULL,
    `date_joined` DATETIME NOT NULL,
    `emp_num` VARCHAR(45) NOT NULL,
    `list_dept_id` INTEGER NOT NULL,
    `list_pos_id` INTEGER NOT NULL,
    `status` VARCHAR(45) NOT NULL,
    `profile_status` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_emp_profile_emp_acc_idx` (`emp_acc_acc_id`),
    INDEX `FI_emp_profile_list_dept1` (`list_dept_id`),
    INDEX `FI_emp_profile_list_pos1` (`list_pos_id`),
    CONSTRAINT `fk_emp_profile_emp_acc`
        FOREIGN KEY (`emp_acc_acc_id`)
        REFERENCES `emp_acc` (`id`),
    CONSTRAINT `fk_emp_profile_list_dept1`
        FOREIGN KEY (`list_dept_id`)
        REFERENCES `list_dept` (`id`),
    CONSTRAINT `fk_emp_profile_list_pos1`
        FOREIGN KEY (`list_pos_id`)
        REFERENCES `list_pos` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- emp_time
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `emp_time`;

CREATE TABLE `emp_time`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `time_in` DATETIME NOT NULL,
    `time_out` DATETIME NOT NULL,
    `ip_add` VARCHAR(45) NOT NULL,
    `date` DATETIME NOT NULL,
    `emp_acc_acc_id` INTEGER NOT NULL,
    `manhours` DATETIME NOT NULL,
    `overtime` DATETIME NOT NULL,
    `check_ip` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_emp_time_emp_acc1_idx` (`emp_acc_acc_id`),
    CONSTRAINT `fk_emp_time_emp_acc1`
        FOREIGN KEY (`emp_acc_acc_id`)
        REFERENCES `emp_acc` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- list_cont_types
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `list_cont_types`;

CREATE TABLE `list_cont_types`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `contact_type` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- list_dept
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `list_dept`;

CREATE TABLE `list_dept`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `dept_names` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- list_holidays
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `list_holidays`;

CREATE TABLE `list_holidays`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `date` DATETIME NOT NULL,
    `name` VARCHAR(45) NOT NULL,
    `type` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- list_ip
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `list_ip`;

CREATE TABLE `list_ip`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `allowed_ip` VARCHAR(45) NOT NULL,
    `status` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- list_leave_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `list_leave_type`;

CREATE TABLE `list_leave_type`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `leave_type` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- list_pos
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `list_pos`;

CREATE TABLE `list_pos`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `pos_names` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
