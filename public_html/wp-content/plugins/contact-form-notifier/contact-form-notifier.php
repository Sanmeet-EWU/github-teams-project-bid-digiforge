<?php
/*
Plugin Name: Contact Form Notifier
Description: Notifies users to fill out a contact form after a specified time.
Version: 1.0
*/

// Register activation and deactivation hooks
register_activation_hook(__FILE__, 'cf_notifier_activate');
register_deactivation_hook(__FILE__, 'cf_notifier_deactivate');

function cf_notifier_activate() {
    if (!wp_next_scheduled('cf_notifier_cron_hook')) {
        wp_schedule_event(time(), 'cf_notifier_interval', 'cf_notifier_cron_hook');
    }
    cf_notifier_create_db();
}

function cf_notifier_deactivate() {
    wp_clear_scheduled_hook('cf_notifier_cron_hook');
}

// Add custom interval for cron job
add_filter('cron_schedules', 'cf_notifier_custom_cron_interval');
function cf_notifier_custom_cron_interval($schedules) {
    $schedules['cf_notifier_interval'] = array(
        'interval' => 300, // 5 minutes
        'display' => __('Every 5 Minutes')
    );
    return $schedules;
}

add_action('cf_notifier_cron_hook', 'cf_notifier_send_notifications');

function cf_notifier_send_notifications() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cf_notifier_submissions';

    $results = $wpdb->get_results("
        SELECT * FROM $table_name
        WHERE TIMESTAMPDIFF(MINUTE, notification_time, NOW()) >= notify_after
        AND notified = 0
    ");

    foreach ($results as $result) {
        // Send notification email
        wp_mail($result->email, 'Reminder to fill out the contact form', 'Please complete the contact form.');

        // Mark as notified
        $wpdb->update(
            $table_name,
            ['notified' => 1],
            ['id' => $result->id]
        );
    }
}

// Add settings page
add_action('admin_menu', 'cf_notifier_add_admin_menu');
function cf_notifier_add_admin_menu() {
    add_options_page('Contact Form Notifier', 'Contact Form Notifier', 'manage_options', 'cf-notifier', 'cf_notifier_options_page');
}

function cf_notifier_options_page() {
    ?>
    <div class="wrap">
        <h1>Contact Form Notifier Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('cf_notifier_settings_group');
            do_settings_sections('cf-notifier');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'cf_notifier_settings_init');
function cf_notifier_settings_init() {
    register_setting('cf_notifier_settings_group', 'cf_notifier_settings');

    add_settings_section(
        'cf_notifier_settings_section',
        __('Notification Settings', 'cf-notifier'),
        null,
        'cf-notifier'
    );

    add_settings_field(
        'cf_notifier_delay',
        __('Notification Delay (minutes)', 'cf-notifier'),
        'cf_notifier_delay_render',
        'cf-notifier',
        'cf_notifier_settings_section'
    );
}

function cf_notifier_delay_render() {
    $options = get_option('cf_notifier_settings');
    ?>
    <input type="number" name="cf_notifier_settings[cf_notifier_delay]" value="<?php echo isset($options['cf_notifier_delay']) ? $options['cf_notifier_delay'] : '5'; ?>" min="1" max="5" />
    <?php
}

// Shortcode for contact form
add_shortcode('cf_notifier_contact_form', 'cf_notifier_contact_form_shortcode');
function cf_notifier_contact_form_shortcode() {
    if ($_POST && isset($_POST['cf_notifier_contact'])) {
        // Handle form submission
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $user_type = sanitize_text_field($_POST['user_type']);
        $delay = get_option('cf_notifier_settings')['cf_notifier_delay'];

        // Save form submission to database
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'cf_notifier_submissions',
            [
                'name' => $name,
                'email' => $email,
                'user_type' => $user_type,
                'notification_time' => current_time('mysql', 1),
                'notify_after' => $delay,
                'notified' => 0
            ]
        );

        echo '<p>Thank you for your submission. We will notify you shortly.</p>';
    } else {
        ob_start();
        ?>
        <form method="post" action="">
            <label for="name">Name:</label>
            <input type="text" name="name" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <br>
            <label for="user_type">User Type:</label>
            <select name="user_type">
                <option value="current_client">Current Client</option>
                <option value="prospective_client">Prospective Client</option>
            </select>
            <br>
            <input type="submit" name="cf_notifier_contact" value="Submit">
        </form>
        <?php
        return ob_get_clean();
    }
}

// Create database table for form submissions
function cf_notifier_create_db() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cf_notifier_submissions';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        email text NOT NULL,
        user_type tinytext NOT NULL,
        notification_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        notify_after int NOT NULL,
        notified boolean DEFAULT 0 NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
?>
