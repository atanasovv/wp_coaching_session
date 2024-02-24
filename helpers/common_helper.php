<?php
include_once("logging.php");
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

function get_table_full_name($table_suffix){
    global $wpdb;
    $table_name = $wpdb->table_suffix;
    return $table_name;
}