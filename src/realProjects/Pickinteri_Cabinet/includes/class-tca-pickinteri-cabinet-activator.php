<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    TCA_Pickinteri_Cabinet
 * @subpackage TCA_Pickinteri_Cabinet/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    TCA_Pickinteri_Cabinet
 * @subpackage TCA_Pickinteri_Cabinet/includes
 */
class TCA_Pickinteri_Cabinet_Activator {

    /**
     * Method to run during plugin activation.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        self::_dbtables_create();
        self::add_visitor_user_type();
        self::add_designer_user_type();
        self::add_vendor_user_type();
    }

    /**
     * Add visitor user type.
     *
     * @since    1.0.0
     */
    protected static function add_visitor_user_type()
    {
        remove_role('pickinteri_Visitor');

        add_role(
            'pickinteri_Visitor',
            __('Pickinteri Visitor', 'tca_pickinteri_cabinet'),
            [
                'read' => true,
                // 'edit_files' => true,
                // 'upload_files' => true,
            ]
        );
    }

    /**
     * Add designer user type.
     *
     * @since    1.0.0
     */
    protected static function add_designer_user_type()
    {
        remove_role('pickinteri_Designer');

        add_role(
            'pickinteri_Designer',
            __('Pickinteri Designer', 'tca_pickinteri_cabinet'),
            [
                'read' => true,
                // 'edit_files' => true,
                // 'upload_files' => true,
            ]
        );
    }

    /**
     * Add vendor user type.
     *
     * @since    1.0.0
     */
    protected static function add_vendor_user_type()
    {
        remove_role('pickinteri_Vendor');

        add_role(
            'pickinteri_Vendor',
            __('Pickinteri Vendor', 'tca_pickinteri_cabinet'),
            [
                'read' => true,
                // 'edit_files' => true,
                // 'upload_files' => true,
            ]
        );
    }

    /**
     * Create necessary database tables.
     *
     * @since    1.0.0
     */
    private static function _dbtables_create()
    {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset_collate = $wpdb->get_charset_collate();

        $tables = [
            "CREATE TABLE IF NOT EXISTS `pcab_directories` (
                `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `topic` VARCHAR(100) NOT NULL,
                `value` VARCHAR(100) NOT NULL,
                PRIMARY KEY (`ID`),
                INDEX `value_by_topic` (`topic`, `value`)
            ) ENGINE = MyISAM $charset_collate;",

            "CREATE TABLE IF NOT EXISTS `pcab_users` (
                `ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `user_login` VARCHAR(60) NOT NULL,
                `user_pass` VARCHAR(255) NOT NULL,
                `user_email` VARCHAR(100) NOT NULL,
                `user_registered` DATETIME NOT NULL DEFAULT current_timestamp(),
                `pcab_type` ENUM('designer','vendor','shop','visitor', 'admin') NOT NULL,
                `user_fname` VARCHAR(100) NOT NULL,
                `user_lname` VARCHAR(100),
                `user_phones` JSON NOT NULL,
                `activity_status` ENUM('pending','approved') NOT NULL DEFAULT 'pending',
                `suspend_status` BOOLEAN NOT NULL DEFAULT FALSE,
                `favorited` BIGINT NOT NULL DEFAULT '0' COMMENT 'added to favorite',
                PRIMARY KEY (`ID`),
                UNIQUE `user_email` (`user_email`),
                INDEX `pcab_type` (`pcab_type`),
                INDEX (`favorited`)
            ) ENGINE = MyISAM $charset_collate;",

            "CREATE TABLE IF NOT EXISTS `pcab_usermeta` (
                `umeta_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `user_id` BIGINT(20) NOT NULL,
                `meta_key` VARCHAR(255) NOT NULL,
                `meta_value` LONGTEXT,
                PRIMARY KEY (`umeta_id`),
                INDEX `user_id` (`user_id`),
                INDEX `meta_key` (`meta_key`),
                FOREIGN KEY (`user_id`) REFERENCES `pcab_users` (`ID`)
            ) ENGINE = MyISAM $charset_collate;",

            "CREATE TABLE IF NOT EXISTS `pcab_business` ( 
                `ID` BIGINT(20) NOT NULL AUTO_INCREMENT ,
                `user_id` BIGINT(20) NOT NULL ,
                `business_name` VARCHAR(255) NOT NULL , 
                `business_email` VARCHAR(100) NOT NULL , 
                `business_phones` JSON , 
                PRIMARY KEY (`ID`), 
                    INDEX (`business_name`),
                    INDEX (`user_id`)
            ) ENGINE = MyISAM $charset_collate;",

            "CREATE TABLE IF NOT EXISTS `pcab_businessmeta` ( 
                `bmeta_id` BIGINT(20) NOT NULL AUTO_INCREMENT , 
                `business_id` BIGINT(20) NOT NULL , 
                `meta_key` VARCHAR(255) NOT NULL , 
                `meta_value` LONGTEXT , 
                PRIMARY KEY (`bmeta_id`) , 
                    INDEX `business_id` (`business_id`) ,
                    INDEX `meta_key` (`meta_key`) ,
                UNIQUE (`bmeta_id`, `business_id`) ,
                FOREIGN KEY (`business_id`)  REFERENCES `pcab_businesses` (`ID`)
            ) ENGINE = MyISAM $charset_collate ;",

            "CREATE TABLE IF NOT EXISTS `pcab_businessaliases` ( 
                `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT , 
                `business_id` BIGINT(20) NOT NULL , 
                `alias` VARCHAR(100) NOT NULL , 
                PRIMARY KEY (`ID`), 
                    INDEX `business_id` (`business_id`), 
                UNIQUE (`alias`)
            ) ENGINE = MyISAM $charset_collate;",

            "CREATE TABLE IF NOT EXISTS `pcab_categories` ( 
                `ID` BIGINT(20) NOT NULL AUTO_INCREMENT ,
                `category_name` VARCHAR(100) NOT NULL , 
                `category_type` ENUM('designer','vendor', 'shop') NOT NULL ,
                `search_type` ENUM('city','region','area', 'business_adress_city') NOT NULL DEFAULT 'region' , 
                `activity_status` ENUM('pending','approved') NOT NULL DEFAULT 'approved' ,
                PRIMARY KEY (`ID`),
                    INDEX (`category_name`),
                    INDEX (`activity_status`)
            ) ENGINE = MyISAM $charset_collate;",

            "CREATE TABLE IF NOT EXISTS `pcab_reviews` ( 
                `ID` BIGINT(20) NOT NULL AUTO_INCREMENT , 
                `project_id` BIGINT(20) NOT NULL , 
                `user_id` BIGINT(20) NOT NULL ,
                `business_id` BIGINT(20) UNSIGNED NULL ,
                `services_id` JSON NULL ,
                `reviewer_email` VARCHAR(100) NOT NULL , 
                `reviewer_name` VARCHAR(100) NULL , 
                `time_sent` DATETIME NOT NULL DEFAULT current_timestamp() ,
                `time_filled` DATETIME NULL , 
                `status_by_user` ENUM('pending','approved') NOT NULL DEFAULT 'pending' , 
                `activity_status` ENUM('pending','approved') NOT NULL DEFAULT 'pending' , 
                PRIMARY KEY (`ID`), 
                    INDEX (`time_sent`), 
                    INDEX (`time_filled`), 
                    INDEX (`project_id`), 
                    INDEX (`user_id`),
                    INDEX (`business_id`)
            ) ENGINE = MyISAM $charset_collate;",

            // Note: add all another tables
        ];

        foreach ($tables as $sql) {
            $wpdb->query($sql);
        }

        // Insert default user
        $wpdb->insert(
            'pcab_users',
            [
                'ID' => 0,
                'user_login' => '-no-login-',
                'user_pass' => '$2y$10$0aCFbLSNMyHm5Obt4XFoPe66SkYOCaDMWOiAM4u9eN0LdZtQs.4ES',
                'pcab_type' => 'vendor',
                'activity_status' => 'pending'
            ],
            ['%d', '%s', '%s', '%s', '%s']
        );
    }
}