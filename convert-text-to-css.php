<?php

/**
 * Plugin Name: Convert text to CSS
 * Plugin URI: https://github.com/emfluenceindia/convert-text-to-css
 * Description: Converts human readable styling description into a clean, modern CSS block
 * Version: 1.0.0
 * 
 * Author: Subrata Sarkar
 * Author URI: https://subratasarkar.com
 * 
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * 
 * Text Domain: convert-text-to-css
 * Domain Path: /languages
 * 
 * Prefix: apcss
 */

if( ! defined( 'ABSPATH' ) ) exit;

define( 'APCSS_VERSION', '1.0.0' );
define( 'APCSS_PATH', plugin_dir_path( __FILE__ ) );
define( 'APCSS_URL', plugin_dir_url( __FILE__ ) );

require_once APCSS_PATH . 'includes/class-apcss-loader.php';

add_action( 'plugins_loaded', 'apcss_loader_init' );
function apcss_loader_init() {
    \APCSS\Loader::init();
}

add_action( 'wp_enqueue_scripts', 'apcss_render_custom_css' );
function apcss_render_custom_css() {
    if( ! function_exists( 'wp_get_custom_css' ) ) return;

    $custom_css = wp_get_custom_css( get_stylesheet() );
    if( empty( trim( $custom_css ) ) ) return;

    wp_register_style( 'apcss-generated-css', false, array(), '1.0.0', 'screen'  );
    wp_enqueue_style( 'apcss-generated-css' );
    wp_add_inline_style( 'apcss-generated-css', $custom_css );
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'apcss_settings_link' );
function apcss_settings_link( $links ) {
    $settings_link = '<a href="' . admin_url( 'admin.php?page=apcss-settings' ) . '">' . esc_html__( 'Settings', 'convert-text-to-css' ) . '</a>';
    array_unshift( $links, $settings_link );

    return $links;
}