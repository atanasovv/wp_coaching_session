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
   

    function create_database(){
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
    // Create the Clients table
        $table_clients = get_table_full_name(TABLE_CO_CLIENTS);
        $sql_clients = "CREATE TABLE $table_clients (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) UNSIGNED NOT NULL,
            additional_info text NOT NULL,
            PRIMARY KEY  (id),
            FOREIGN KEY  (user_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE
        ) $charset_collate;";
        dbDelta($sql_clients);

        // Create the Forms table
        $table_forms = get_table_full_name(TABLE_CO_FORMS);
        $sql_forms = "CREATE TABLE $table_forms (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            content longtext NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        dbDelta($sql_forms);

        // Create the Sessions table
        $table_sessions = get_table_full_name(TABLE_CO_SESSION);
        $sql_sessions = "CREATE TABLE $table_sessions (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            client_id mediumint(9) NOT NULL,
            form_id mediumint(9) NOT NULL,
            session_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            notes text NOT NULL,
            PRIMARY KEY  (id),
            FOREIGN KEY  (client_id) REFERENCES $table_clients(id) ON DELETE CASCADE,
            FOREIGN KEY  (form_id) REFERENCES $table_forms(id) ON DELETE CASCADE
        ) $charset_collate;";
        dbDelta($sql_sessions);

        // Create the Sessions table
        $table_session = get_table_full_name(TABLE_CO_SESSION);
        $sql_sessions = "CREATE TABLE $table_session (
            session_id mediumint(9) NOT NULL AUTO_INCREMENT,
            client_id bigint(20) UNSIGNED NOT NULL,
            session_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            status varchar(50) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            session_number int NOT NULL,
            PRIMARY KEY  (session_id),
            FOREIGN KEY  (client_id) REFERENCES {$wpdb->prefix}coaching_clients(id) ON DELETE CASCADE
        ) $charset_collate;";
    
        dbDelta($sql_sessions);
    

    }



 }