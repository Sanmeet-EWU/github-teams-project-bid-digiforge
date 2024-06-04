<?php
/*
Plugin Name: Contact Form Notifier
Description: Notifies users to fill out a contact form after a specified time.
Version: 1.1
Author: DigiForge
*/

// Register activation and deactivation hooks
register_activation_hook(__FILE__, 'cf_notifier_activate');
register_deactivation_hook(__FILE__, 'cf_notifier_deactivate');

function cf_notifier_activate() {
    cf_notifier_create_db();
    add_option('cf_notifier_debug_mode', false);
}

function cf_notifier_deactivate() {
    wp_clear_scheduled_hook('cf_notifier_cron_hook');
    delete_option('cf_notifier_debug_mode');
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

// Shortcode for contact form
add_shortcode('cf_notifier_contact_form', 'cf_notifier_contact_form_shortcode');
function cf_notifier_contact_form_shortcode() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cf_notifier_contact'])) {
        // Verify nonce
        if (!isset($_POST['cf_notifier_nonce']) || !wp_verify_nonce($_POST['cf_notifier_nonce'], 'cf_notifier_nonce_action')) {
            echo '<p class="cf_notifier_error_message">Nonce verification failed. Please try again.</p>';
            return;
        }

        // Handle form submission
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $user_type = sanitize_text_field($_POST['user_type']);

        $debug_mode = get_option('cf_notifier_debug_mode');
        $delay = $debug_mode ? 1 : 5; // 1 minute for debug, 5 minutes for normal

        // Save form submission to database
        global $wpdb;
        $result = $wpdb->insert(
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

        if ($result) {
            echo '<p class="cf_notifier_success_message">Thank you for your submission. We will notify you shortly.</p>';
        } else {
            echo '<p class="cf_notifier_error_message">There was an error with your submission. Please try again.</p>';
        }
    } else {
        ob_start();
        ?>
        <div class="cf_notifier_chatbox" id="cf-notifier-chatbox">
            <div class="cf_notifier_message">
                <p>Hello! How can we assist you today?</p>
            </div>
            <div class="cf_notifier_buttons">
                <button id="cf-notifier-minimize-btn">-</button>
                <button id="cf-notifier-close-btn">×</button>
            </div>
            <div class="cf_notifier_form_container">
                <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" class="cf_notifier_form">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <select name="user_type" required>
                        <option value="">Select User Type</option>
                        <option value="current_client">Current Client</option>
                        <option value="prospective_client">Prospective Client</option>
                    </select>
                    <?php wp_nonce_field('cf_notifier_nonce_action', 'cf_notifier_nonce'); ?>
                    <input type="submit" name="cf_notifier_contact" value="Submit">
                </form>
            </div>
        </div>
        <script>
            document.getElementById('cf-notifier-close-btn').addEventListener('click', function() {
                document.getElementById('cf-notifier').style.display = 'none';
            });

            document.getElementById('cf-notifier-minimize-btn').addEventListener('click', function() {
                var chatbox = document.getElementById('cf-notifier');
                if (chatbox.classList.contains('minimized')) {
                    chatbox.classList.remove('minimized');
                } else {
                    chatbox.classList.add('minimized');
                }
            });
        </script>
        <?php
        return ob_get_clean();
    }
}

// Add admin menu
add_action('admin_menu', 'cf_notifier_add_admin_menu');
function cf_notifier_add_admin_menu() {
    add_menu_page('Contact Form Notifier', 'CF Notifier', 'manage_options', 'cf-notifier', 'cf_notifier_admin_page', 'dashicons-email', 6);
}

function cf_notifier_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cf_notifier_submissions';

    $submissions = $wpdb->get_results("SELECT * FROM $table_name");
    $debug_mode = get_option('cf_notifier_debug_mode');

    if (isset($_POST['cf_notifier_toggle_debug'])) {
        $debug_mode = !$debug_mode;
        update_option('cf_notifier_debug_mode', $debug_mode);
    }

    ?>
    <div class="wrap">
        <h1>Contact Form Notifier Submissions</h1>
        <form method="post" action="">
            <p>
                <input type="submit" name="cf_notifier_toggle_debug" value="Toggle Debug Mode" class="button button-primary" />
                Current Debug Mode: <?php echo $debug_mode ? 'Enabled' : 'Disabled'; ?>
            </p>
        </form>
        <table class="wp-list-table widefat striped">
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
                    <td><?php echo esc_html($submission->name); ?></td>
                    <td><?php echo esc_html($submission->email); ?></td>
                    <td><?php echo esc_html($submission->user_type); ?></td>
                    <td><?php echo esc_html($submission->notification_time); ?></td>
                    <td><?php echo esc_html($submission->notify_after); ?></td>
                    <td><?php echo $submission->notified ? 'Yes' : 'No'; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Enqueue styles
add_action('wp_enqueue_scripts', 'cf_notifier_enqueue_styles');
function cf_notifier_enqueue_styles() {
    wp_enqueue_style('cf-notifier-styles', plugin_dir_url(__FILE__) . 'cf-notifier-styles.css');
}
?>
