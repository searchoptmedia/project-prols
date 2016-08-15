<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1470139254.
 * Generated on 2016-08-02 20:00:54 
 */
class PropelMigration_1470139254
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

CREATE INDEX `fk_emp_leave_emp_acc2_idx` ON `emp_leave` (`admin_id`);

ALTER TABLE `emp_leave` ADD CONSTRAINT `fk_emp_leave_emp_acc2`
    FOREIGN KEY (`admin_id`)
    REFERENCES `emp_acc` (`id`);

DROP INDEX `status` ON `emp_profile`;

DROP INDEX `status_id` ON `emp_profile`;

DROP INDEX `status_id_2` ON `emp_profile`;

ALTER TABLE `emp_profile` CHANGE `gender` `gender` VARCHAR(10) NOT NULL;

ALTER TABLE `emp_profile` CHANGE `status` `status` VARCHAR(45) NOT NULL;

ALTER TABLE `emp_profile` CHANGE `profile_status` `profile_status` INTEGER NOT NULL;

ALTER TABLE `emp_profile` ADD CONSTRAINT `fk_emp_profile_list_dept1`
    FOREIGN KEY (`list_dept_id`)
    REFERENCES `list_dept` (`id`);

ALTER TABLE `emp_profile` ADD CONSTRAINT `fk_emp_profile_list_pos1`
    FOREIGN KEY (`list_pos_id`)
    REFERENCES `list_pos` (`id`);

ALTER TABLE `emp_time` CHANGE `time_in` `time_in` DATETIME NOT NULL;

ALTER TABLE `emp_time` CHANGE `time_out` `time_out` DATETIME NOT NULL;

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

ALTER TABLE `emp_leave` DROP FOREIGN KEY `fk_emp_leave_emp_acc2`;

DROP INDEX `fk_emp_leave_emp_acc2_idx` ON `emp_leave`;

ALTER TABLE `emp_profile` DROP FOREIGN KEY `fk_emp_profile_list_dept1`;

ALTER TABLE `emp_profile` DROP FOREIGN KEY `fk_emp_profile_list_pos1`;

ALTER TABLE `emp_profile` CHANGE `gender` `gender` CHAR(10) NOT NULL;

ALTER TABLE `emp_profile` CHANGE `status` `status` VARCHAR(11) NOT NULL;

ALTER TABLE `emp_profile` CHANGE `profile_status` `profile_status` INTEGER(20) NOT NULL;

CREATE INDEX `status` ON `emp_profile` (`status`);

CREATE INDEX `status_id` ON `emp_profile` (`status`);

CREATE INDEX `status_id_2` ON `emp_profile` (`status`);

ALTER TABLE `emp_time` CHANGE `time_in` `time_in` DATETIME;

ALTER TABLE `emp_time` CHANGE `time_out` `time_out` DATETIME;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}