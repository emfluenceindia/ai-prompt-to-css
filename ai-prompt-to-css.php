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
 * OpenAI API Key: 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
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