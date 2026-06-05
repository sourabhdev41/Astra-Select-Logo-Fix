<?php
/**
 * Plugin Name: Astra Logo Switcher Fix
 * Plugin URI: https://nrwone.in
 * Description: Restores WordPress custom logo upload option for Astra theme and fixes missing select image issue.
 * Version: 1.0
 * Author: NRW India
 * Author URI: https://nrwone.in
 */

if (!defined('ABSPATH')) {
    exit;
}

// Ensure custom logo support exists
function nrw_astra_logo_fix_setup() {
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'nrw_astra_logo_fix_setup');

// Force logo output in Astra header
function nrw_force_logo_display() {
    if (function_exists('the_custom_logo') && has_custom_logo()) {
        echo '<div class="nrw-logo">';
        the_custom_logo();
        echo '</div>';
    }
}
add_action('astra_site_branding_content', 'nrw_force_logo_display', 5);

// Add fallback logo uploader in Customizer
function nrw_logo_customizer($wp_customize) {
    $wp_customize->add_setting('nrw_extra_logo');

    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize,
        'nrw_extra_logo_control',
        array(
            'label' => 'Upload Logo (Fallback)',
            'section' => 'title_tagline',
            'settings' => 'nrw_extra_logo',
        )
    ));
}
add_action('customize_register', 'nrw_logo_customizer');

// Display fallback logo if no main logo
function nrw_display_fallback_logo() {
    if (has_custom_logo()) return;

    $logo = get_theme_mod('nrw_extra_logo');
    if ($logo) {
        echo '<img src="' . esc_url($logo) . '" style="max-width:150px;">';
    }
}
add_action('astra_site_branding_content', 'nrw_display_fallback_logo', 6);