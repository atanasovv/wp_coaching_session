<?php
include_once("logging.php");

# Create table

function get_all_fables(){
  $tables = array();
  $all_constants = get_defined_constants(true);
  if (isset($all_constants['user'])) {
    foreach ($all_constants['user'] as $key => $value) {
      if (strpos($key, 'TABLE_') === 0) {
        logMessage("Found table: " . $key . " = " . $value);
        $tables[] = $value;
      }
    }
  }
  return $tables;
}

function create_table($table_suffix) {
  global $wpdb;
  $table_name = $wpdb->prefix . $table_suffix;
  logMessage("Creating table: " . $table_name);
  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    name tinytext NOT NULL,
    message text NOT NULL,
    time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    PRIMARY KEY  (id)
  ) $charset_collate;";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
  if ($wpdb->last_error) {
    logMessage("Error dropping table: " . $wpdb->last_error);
}
logMessage("Table created: " . $table_name);
}

function my_custom_plugin_create_table() {

    $tables = get_all_fables();
    foreach ($tables as $table) {
      create_table($table);
    }

    
}

function my_custom_plugin_remove_table() {
  $tables = get_all_fables();
    foreach ($tables as $table) {
      global $wpdb;
      $table_name = $wpdb->prefix . $table;
      logMessage("dropping table: " . $table_name);
      $sql = "DROP TABLE IF EXISTS $table_name";
      $wpdb->query($sql);
    }
}
