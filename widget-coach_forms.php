<?php
// Include this at the beginning of the file
include_once 'logging.php';
include_once plugin_dir_path( __FILE__ ) . 'create-table_coach_forms.php';
class CoachingFormWidget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'CoachingFormWidget', // Base ID of your widget
            'Coaching Form Widget', // Widget name will appear in UI
            array( 'description' => 'A custom form for user input' ) // Widget description
        );
        logMessage("CoachingFormWidget constructor called!");
    }

    // Creating widget front-end
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );

        // Before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];

        // This is where you'll place the form (discussed in the next step)
        // Display the form here
        $this->display_form();

        echo $args['after_widget'];
    }

    // Widget Backend 
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = 'New title';
        }
        // Widget admin form
        ?>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">Title:</label> 
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php 
    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }

    // Function to display the form on the front end
    public function display_form() {
        ?>
        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
            <p>
                <label for="mcw_name">Name:</label>
                <input class="widefat" id="mcw_name" name="mcw_name" type="text">
            </p>
            <p>
                <label for="mcw_message">Message:</label>
                <textarea class="widefat" id="mcw_message" name="mcw_message"></textarea>
            </p>
            <p>
                <input class="button button-primary" type="submit" value="Submit">
            </p>
        </form>
        <?php
        $this->handle_form_submission(); // Handle form submission
    }

    // Inside handle_form_submission(), modify to insert data into your custom table
    private function handle_form_submission() {
        logMessage('Handling form submission...');
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mcw_name']) && !empty($_POST['mcw_message'])) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'coach_forms';

            // Sanitize user input
            $name = sanitize_text_field($_POST['mcw_name']);
            $message = sanitize_textarea_field($_POST['mcw_message']);

        //    // check for sql injection   
        //     $name = $wpdb->prepare($name);
        //     $message = $wpdb->prepare($message);

            // Insert data into the table
            $wpdb->insert(
                $table_name,
                array(
                    'name' => $name,
                    'message' => $message,
                    'time' => current_time('mysql'),
                ),
                array('%s', '%s', '%s')
            );

            echo '<p>Thank you for your submission, ' . esc_html($name) . '!</p>';
        }
    }
}

function coaching_form_widget_shortcode($atts) {
    // The widget's frontend form display logic goes here
    // For demonstration, we'll instantiate the widget and call a modified display method
    $widget = new CoachingFormWidget();
    ob_start(); // Start output buffering to capture the widget output
    $widget->display_form(); // Assuming display_form() is made public or accessible via a shortcode handler
    $output = ob_get_clean(); // Get the widget output and clear the buffer
    return $output;
}

// Register the shortcode with WordPress
add_shortcode('coaching_form', 'coaching_form_widget_shortcode');
