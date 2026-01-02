<?php

/**
 * Plugin Name: AI Prompt to CSS
 * Plugin URI: https://github.com/emfluenceindia/ai-prompt-to-css
 * Description: Converts natural text into a clean, modern CSS
 * Version: 1.0.0
 * 
 * Author: Subrata Sarkar
 * Author URI: https://profiles.wordpress.org/subrataemfluence
 * 
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * 
 * Text Domain: ai-prompt-to-css
 * Prefix: apcss
 * 
 * Domain Path: /languages
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