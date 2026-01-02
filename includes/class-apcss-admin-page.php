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
            'dashicons-code-standards'
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
            <h1>AI Powered Prompt to CSS</h1>
            <hr />
            <form method="post" id="appcss-form">
                <?php wp_nonce_field( 'apcss_generate' ); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            Target CSS Selectors <span class="required">*</span>
                        </th>
                        <td>
                            <input type="text"
                                name="apcss_selectors"
                                id="apcss_selectors"
                                required
                                class="regular-text",
                                placeholder=".featured-posts, .post-card, #main-nav" />
                            <p class="description">
                                Provide existing CSS selectors from your theme or page
                            </p>    
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            Describe the style <span class="required">*</span>
                        </th>
                        <td>
                           <textarea required name="apcss_prompt" id="apcss_prompt" rows="5" class="large-text" 
                                placeholder="Add something that I can convert to CSS. For example: 'Create a responsive Grid layout with 3 columns.'"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <?php submit_button( 'Generate CSS' ); ?>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <?php
        if( isset( $_POST['apcss_prompt'] ) && isset( $_POST['apcss_selectors'] ) && check_admin_referer( 'apcss_generate' ) ) {
            $prompt       = sanitize_textarea_field( wp_unslash( $_POST[ 'apcss_prompt' ] ) );
            $selectors    = sanitize_text_field( wp_unslash( $_POST[ 'apcss_selectors' ] ) );
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
            <hr />
            <form method="post" action="options.php">
                <?php
                settings_fields( 'apcss_settings' );
                $apcss_api_key = get_option( 'apcss_api_key' );
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">Enter your OpenAI API Key <span class="required">*</span></th>
                        <td>
                            <input 
                                type="password" 
                                name="apcss_api_key" 
                                id="apcss_api_key"
                                required
                                class="regular-text" />
                            <p class="description">
                                <?php echo $apcss_api_key ? '<p style="color: #059669; font-weight: 500;">API key is configured. You are good to go!</p>' : '<p style="color: #cc0000; font-weight: 500;">No API Key found. Enter a valid key to start.</p>'; ?>
                                Your API key is stored securely and never exposed.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">
                            <?php submit_button( 'Save API Key' ); ?>
                        </th>
                    </tr>
                </table>
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