<?php
require_once(dirname(__FILE__) . '/../logging.php');

function get_table_full_name($table_suffix){
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    global $wpdb;
    $table_name = $wpdb->prefix .$table_suffix;
    logMessage("Returning table name: " . $table_name);
    return $table_name;
}