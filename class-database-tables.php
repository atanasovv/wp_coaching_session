<?php

include_once ('config.php');
include_once("logging.php");
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require_once('helpers/common_helper.php');

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class coach_Upgrade
 *
 * Handle any installation upgrade or install tasks
 */


 
 class DatabaseTables {
    public function __construct() {
        register_activation_hook( __FILE__, array( $this, 'install' ) );
    }
   
    // Create the custom tables
    function create_database(){
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        // Create the Clients table
        $table_clients = get_table_full_name(TABLE_CO_CLIENTS);
        logMessage("Creating table {$table_clients}");
        $sql_clients = "CREATE TABLE $table_clients (
            ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id bigint(20) UNSIGNED NOT NULL,
            additional_info text NOT NULL,
            PRIMARY KEY  (ID),
            FOREIGN KEY  (ID) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE
        ) $charset_collate;";
        dbDelta($sql_clients);

        // Create the Forms table
        $table_forms = get_table_full_name(TABLE_CO_FORMS);
        logMessage("Creating table {$table_forms}");
        $sql_forms = "CREATE TABLE $table_forms (
            ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            content longtext NOT NULL,
            PRIMARY KEY  (ID)
        ) $charset_collate;";
        dbDelta($sql_forms);

        // Create the Sessions table
        $table_session = get_table_full_name(TABLE_CO_SESSION);
        logMessage("Creating table {$table_session}");
        $sql_sessions = "CREATE TABLE $table_session (
            ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            client_id bigint(20) UNSIGNED NOT NULL,
            session_date datetime DEFAULT '0001-01-01 00:00:00' NOT NULL,
            status varchar(50) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            session_number int NOT NULL,
            PRIMARY KEY  (ID),
            FOREIGN KEY  (ID) REFERENCES $table_clients(ID) ON DELETE CASCADE
        ) $charset_collate;";    
        dbDelta($sql_sessions);

        // Create the Questions table
        $table_questions = get_table_full_name(TABLE_CO_QUESTIONS);
        $sql_questions = "CREATE TABLE $table_questions (
            ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            form_id bigint(20) UNSIGNED NOT NULL,
            question_text text NOT NULL,
            question_type varchar(50) NOT NULL,
            PRIMARY KEY  (ID),
            FOREIGN KEY  (ID) REFERENCES $table_forms(ID) ON DELETE CASCADE
        ) $charset_collate;";    
        dbDelta($sql_questions);
    

    }
// Drop custom tables
    function drop_database(){
        global $wpdb;
        $table_clients = get_table_full_name(TABLE_CO_CLIENTS);
        $table_forms = get_table_full_name(TABLE_CO_FORMS);
        $table_sessions = get_table_full_name(TABLE_CO_SESSION);
        $wpdb->query("DROP TABLE IF EXISTS $table_sessions");
        $wpdb->query("DROP TABLE IF EXISTS $table_forms");
        $wpdb->query("DROP TABLE IF EXISTS $table_clients");
        $wpdb->query("DROP TABLE IF EXISTS $table_questions");


    }



 }