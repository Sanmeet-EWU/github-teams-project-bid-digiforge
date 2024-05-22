<?php
/*
Plugin Name: Contact Form Notifier
Description: Notifies users to fill out a contact form after a specified time.
Version: 1.0
Author: Kaimana Kahalekai
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
        'interval' => 0, // 5 minutes
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
    global $wpdb;
    $table_name = $wpdb->prefix . 'cf_notifier_submissions';

    $submissions = $wpdb->get_results("SELECT * FROM $table_name");

    ?>
    <div class="wrap">
        <h1>Contact Form Notifier Settings</h1>
        <h2>Submissions</h2>
        <table class="wp-list-table widefat striped cf-notifier-submission-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Notification Time</th>
                    <th>Notify After (minutes)</th>
                    <th>Notified</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $submission) : ?>
                    <tr>
                        <td><?php echo $submission->name; ?></td>
                        <td><?php echo $submission->email; ?></td>
                        <td><?php echo $submission->user_type; ?></td>
                        <td><?php echo $submission->notification_time; ?></td>
                        <td><?php echo $submission->notify_after; ?></td>
                        <td><?php echo $submission->notified ? 'Yes' : 'No'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
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

        echo '<p class="cf_notifier_success_message">Thank you for your submission. We will notify you shortly.</p>';
    } else {
        ob_start();
        ?>
        <div class="cf_notifier_chatbox" id="cf-notifier-chatbox">
            <div class="cf_notifier_message">
                <p>Hello! How can we assist you today?</p>
            </div>
            <div class="cf_notifier_form_container">
                <form method="post" action="" class="cf_notifier_form">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <select name="user_type" required>
                        <option value="">Select User Type</option>
                        <option value="current_client">Current Client</option>
                        <option value="prospective_client">Prospective Client</option>
                    </select>
                    <input type="submit" name="cf_notifier_contact" value="Submit">
                </form>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    document.getElementById('cf-notifier-chatbox').classList.add('open');
                }, 300000); // 300000 ms = 5 minutes
            });
        </script>
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

// Enqueue styles
add_action('wp_enqueue_scripts', 'cf_notifier_enqueue_styles');
function cf_notifier_enqueue_styles() {
    wp_enqueue_style('cf-notifier-styles', plugin_dir_url(__FILE__) . 'cf-notifier-styles.css');
}
?>
