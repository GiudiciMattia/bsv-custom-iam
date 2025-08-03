<?php
/**
 * Plugin Name: Bsv Custom Iam
 * Description: Personalizzazioni area utente e login IAM.
 * Version: 1.1b
 * Author: Mattia Giudici
 * License: GPL2+
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/shortcodes.php';
require_once plugin_dir_path(__FILE__) . 'includes/hooks.php';
require_once plugin_dir_path(__FILE__) . 'includes/utilities.php';

function bsv_custom_iam_enqueue_assets() {
    wp_enqueue_style('bsv-custom-iam-css', plugin_dir_url(__FILE__) . 'assets/css/bsv-custom-iam.css', [], '1.0');
    wp_enqueue_script('bsv-custom-iam-js', plugin_dir_url(__FILE__) . 'assets/js/bsv-custom-iam.js', ['jquery'], '1.0', true);
}
add_action('wp_enqueue_scripts', 'bsv_custom_iam_enqueue_assets');

function bsv_iam_enqueue_styles() {
    if (has_shortcode(get_post()->post_content, 'bsv_reset_password')) {
        wp_enqueue_style(
            'bsv-custom-iam-style',
            plugin_dir_url(__FILE__) . 'assets/css/bsv-custom-iam.css',
            [],
            '1.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'bsv_iam_enqueue_styles');









function bsv_reset_password_shortcode() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/reset-password-form.php';
    return ob_get_clean();
}
add_shortcode('bsv_reset_password', 'bsv_reset_password_shortcode');
