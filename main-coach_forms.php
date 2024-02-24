<?php
/**
 * Plugin Name: Coach Forms
 * Description: This adds forms dedicated to coaching sessions
 * Version: 1.0
 */

// Include the necessary files
include_once 'create-table_coach_forms.php';
include_once 'widget-coach_forms.php';
include_once 'logging.php';

// Activation hook to create the custom table
register_activation_hook(__FILE__, 'my_custom_plugin_create_table');

// Register and load the widget
function my_load_widget() {
    logMessage('Loading widget....');
    register_widget('CoachingFormWidget');
}
add_action('widgets_init', 'my_load_widget');

// Deactivation hook to remove the custom table
register_deactivation_hook(__FILE__, 'my_custom_plugin_remove_table');

