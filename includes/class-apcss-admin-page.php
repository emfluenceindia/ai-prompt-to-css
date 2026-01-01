<?php

namespace APCSS;

if( ! defined( 'ABSPATH' ) ) exit;

class Admin_Page {
    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'menu' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'assets' ] );
        add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
    }

    public static function menu() {
        add_menu_page(
            __( 'AI Prompt to CSS', 'ai-prompt-to-css' ),
            __( 'AI Prompt to CSS', 'ai-prompt-to-css' ),
            'manage_options',
            'apcss',
            [ __CLASS__, 'render' ],
            'dashicon-admin-customizer'
        );

        add_submenu_page(
            'apcss',
            'Settings',
            'Settings',
            'manage_options',
            'apcss-settings',
            [ __CLASS__, 'render_settings_page' ]
        );
    }

    public static function render() { ?>
        <div class="wrap">
            <h1>Prompt -> CSS</h1>
            <form method="post" id="appcss-form">
                <?php wp_nonce_field( 'apcss_generate' ); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            Target CSS Selectors
                        </th>
                        <td>
                            <input type="text"
                                name="apcss_selectors"
                                class="regular-text",
                                placeholder=".featured-posts, .post-card, #main-nav" />
                            <p class="description">
                                Provide existing CSS selectors from your theme or page
                            </p>    
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            Describe the style
                        </th>
                        <td>
                           <textarea name="apcss_prompt" id="apcss_prompt" rows="5" class="large-text" 
                                placeholder="Add something that I can convert to CSS. For example: 'Create a responsive Grid layout with 3 columns.'"></textarea>
                        </td>
                    </tr>
                </table>
                <?php submit_button( 'Generate CSS' ); ?>
            </form>
        </div>

        <?php
        if( isset( $_POST['apcss_prompt'] ) && check_admin_referer( 'apcss_generate' ) ) {
            $prompt = sanitize_textarea_field( wp_unslash( $_POST[ 'apcss_prompt' ] ) );
            $selectors = sanitize_text_field( $_POST[ 'apcss_selectors' ] );

            $final_prompt = self::build_prompt( $prompt, $selectors );

            $css    = AI_Engine::generate_css( $final_prompt );
            CSS_Injector::inject( $css, $final_prompt );
        }
    }

    public static function build_prompt( $prompt, $selectors ) {
        $system_hint  = "Generate clean, minimal CSS only. ";
        $system_hint .= "Use modern CSS (flexbox or grid). ";
        $system_hint .= "Do not include font-family unless explicitly requested. ";
        $system_hint .= "Avoid !important.";

        if( ! empty( $selectors ) ) {
            return "Target the following existing CSS selectors:\n"
                . $selectors
                . "\n\nTask:\n"
                . $prompt;
        }

        return $prompt;
    }

    /**
     * Renders a text field for storing OpenAI API Key in wp_options table.
     *
     * @return void
     */
    public static function render_settings_page() { ?>
        <div class="wrap" id="apcss-app">
            <h1>AI Prompt to CSS Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields( 'apcss_settings' ); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">OpenAI API Key</th>
                        <td>
                            <input 
                                type="password" 
                                name="apcss_api_key" 
                                id="apcss_api_key"
                                value="<?php echo esc_attr( get_option( 'apcss_api_key' ) ); ?>"
                                class="regular-text" />
                            <p class="description">
                                Your API key is stored securely and never exposed.
                            </p>    
                        </td>
                    </tr>
                </table>
                <?php submit_button( 'Save Key' ); ?>
            </form>
        </div>
    <?php }

    public static function assets( $hook ) {
        if( $hook !== 'toplevel_page_apcss' ) return;

        wp_enqueue_style(
            'apcss-admin',
            APCSS_URL . 'assets/admin.css',
            array(),
            APCSS_VERSION
        );

        wp_enqueue_script(
            'apcss-admin',
            APCSS_URL . 'assets/admin.js',
            array(),
            APCSS_VERSION,
            true
        );
    }

    public static function register_settings() {
        register_setting(
            'apcss_settings',
            'apcss_api_key',
            array(
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field'
            ),
        );
    }
}