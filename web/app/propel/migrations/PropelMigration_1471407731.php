<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1471407731.
 * Generated on 2016-08-17 12:22:11 
 */
class PropelMigration_1471407731
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

CREATE TABLE `emp_time_reject`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `time_in` DATETIME,
    `time_out` DATETIME,
    `ip_add` VARCHAR(45),
    `date` DATETIME,
    `emp_acc_acc_id` INTEGER,
    `manhours` FLOAT,
    `overtime` FLOAT,
    `check_ip` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `fk_emp_time_emp_acc1_idx` (`emp_acc_acc_id`),
    CONSTRAINT `fk_emp_time_emp_acc1`
        FOREIGN KEY (`emp_acc_acc_id`)
        REFERENCES `emp_acc` (`id`)
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

DROP TABLE IF EXISTS `emp_time_reject`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}