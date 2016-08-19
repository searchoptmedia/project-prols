<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1471403969.
 * Generated on 2016-08-17 11:19:29 
 */
class PropelMigration_1471403969
{

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `emp_leave`;

DROP TABLE IF EXISTS `list_leave_type`;

CREATE TABLE `emp_request`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `request` VARCHAR(100),
    `status` VARCHAR(45),
    `date_started` DATETIME,
    `date_ended` DATETIME,
    `emp_acc_id` INTEGER,
    `list_request_type_id` INTEGER,
    `admin_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `fk_emp_request_emp_acc1_idx` (`emp_acc_id`),
    INDEX `fk_emp_request_emp_acc2_idx` (`admin_id`),
    INDEX `fk_emp_request_list_request_type1_idx` (`list_request_type_id`),
    CONSTRAINT `fk_emp_request_emp_acc1`
        FOREIGN KEY (`emp_acc_id`)
        REFERENCES `emp_acc` (`id`),
    CONSTRAINT `fk_emp_request_list_request_type1`
        FOREIGN KEY (`list_request_type_id`)
        REFERENCES `list_request_type` (`id`),
    CONSTRAINT `fk_emp_request_emp_acc2`
        FOREIGN KEY (`admin_id`)
        REFERENCES `emp_acc` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `list_request_type`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `request_type` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `emp_request`;

DROP TABLE IF EXISTS `list_request_type`;

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
    INDEX `fk_emp_leave_list_leave_type1_idx` (`list_leave_type_id`),
    INDEX `fk_emp_leave_emp_acc2_idx` (`admin_id`),
    CONSTRAINT `fk_emp_leave_emp_acc1`
        FOREIGN KEY (`emp_acc_id`)
        REFERENCES `emp_acc` (`id`),
    CONSTRAINT `fk_emp_leave_emp_acc2`
        FOREIGN KEY (`admin_id`)
        REFERENCES `emp_acc` (`id`),
    CONSTRAINT `fk_emp_leave_list_leave_type1`
        FOREIGN KEY (`list_leave_type_id`)
        REFERENCES `list_leave_type` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `list_leave_type`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `leave_type` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}