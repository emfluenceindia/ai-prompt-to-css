<?php

namespace APCSS;

if( ! defined( 'ABSPATH' ) ) exit;

class Admin_Page {
    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'menu' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'assets' ] );
        add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
        add_action( 'admin_post_apcss_generate_css', [ __CLASS__, 'handle_generation' ] );
    }

    public static function menu() {
        add_menu_page(
            __( 'AI Prompt to CSS', 'ai-prompt-to-css' ),
            __( 'AI Prompt to CSS', 'ai-prompt-to-css' ),
            'manage_options',
            'ai-prompt-to-css',
            [ __CLASS__, 'render' ],
            'dashicons-code-standards'
        );

        add_submenu_page(
            'ai-prompt-to-css',
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
            <?php if( isset( $_GET[ 'apcss_success' ] ) ) { ?>
                <div class="notice notice-sucess">
                    <p><?php echo esc_html__( 'CSS successfully added to Additional CSS.', 'ai-prompt-to-css' ); ?></p>
                </div>
            <?php } ?>

            <?php if( isset( $_GET[ 'apcss_error' ] ) ) { ?>
                <div class="notice notice-error">
                    <p><?php echo esc_html__( 'CSS generation failed!', 'ai-prompt-to-css' ); ?></p>
                </div>
            <?php } ?>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="appcss-form">
                <input type="hidden" name="action" value="apcss_generate_css" />
            
                <?php wp_nonce_field( 'apcss_generate' ); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <?php echo esc_html__( 'Target CSS Selectors', 'ai-prompt-to-css' ); ?> <span class="required">*</span>
                        </th>
                        <td>
                            <input type="text"
                                name="apcss_selectors"
                                id="apcss_selectors"
                                required
                                class="regular-text",
                                placeholder=".wp-block-page-list a, .featured-posts, .post-card, #main-nav" />
                            <p class="description">
                                <?php echo esc_html__( 'Provide existing CSS selectors from your theme or page', 'ai-prompt-to-css' ); ?>
                            </p>    
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo esc_html__( 'Describe the style', 'ai-prompt-to-css' ); ?> <span class="required">*</span>
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
            <?php
            // Fetch current custom CSS block from table and show a readonly manner below
            $stored_css = get_posts(
                array(
                    'post_type'      => 'custom_css',
                    'title'          => get_stylesheet(),
                    'posts_per_page' => 1
                )
            );

            $css_content = ! empty( $stored_css ) ? $stored_css[0]->post_content : '';
            ?>
            <table class="form-table">
                <tr>
                    <th>
                        <?php echo esc_html__( 'AI Prompt Custon CSS Content', 'ai-prompt-to-css' ); ?>
                    </th>
                    <td>
                        <textarea rows="30" name="apcss-custom-css-content" id="apcss-custom-css-content" class="large-text apcss-css-content-box" readonly><?php echo esc_attr( $css_content ); ?></textarea>
                    </td>
                </tr>
            </table>
        </div>

        <?php
        if( isset( $_POST['apcss_prompt'] ) && isset( $_POST['apcss_selectors'] ) && check_admin_referer( 'apcss_generate' ) ) {
            $prompt       = sanitize_textarea_field( wp_unslash( $_POST[ 'apcss_prompt' ] ) );
            $selectors    = sanitize_text_field( wp_unslash( $_POST[ 'apcss_selectors' ] ) );
            $final_prompt = self::build_prompt( $prompt, $selectors );

            $css          = AI_Engine::generate_css( $final_prompt );
            CSS_Injector::inject( $css, $final_prompt );
        }
    }

    public static function handle_generation() {
        if( ! current_user_can( 'edit_theme_options' ) || ! check_admin_referer( 'apcss_generate' ) ) {
            wp_die( 'Unauthorized' );
        }

        $selector = sanitize_text_field( 
            wp_unslash( $_POST[ 'apcss_selectors' ] ?? '' ) 
        );

        $prompt   = sanitize_text_field( 
            wp_unslash( $_POST[ 'apcss_prompt' ] ?? '' ) 
        );

        if( empty( $selector ) || empty ( $prompt ) ) {
            wp_safe_redirect( add_query_arg( 'apcss_error', 1, wp_get_referer() ) );
            exit;
        }

        $css = AI_Engine::generate_css( "Generate CSS for selector {$selector} . {$prompt}" );

        CSS_Injector::inject( $css, $prompt );

        wp_safe_redirect( add_query_arg( 'apcss_success', 1, wp_get_referer() ) );
        exit;
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
            <h1><?php echo esc_html__( 'AI Prompt to CSS Settings', 'ai-prompt-to-css' ); ?></h1>
            <hr />
            <form method="post" action="options.php">
                <?php
                settings_fields( 'apcss_settings' );
                $apcss_api_key = get_option( 'apcss_api_key' );
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php echo esc_html__( 'Enter your OpenAI API Key', 'ai-prompt-to-css' ); ?> <span class="required">*</span></th>
                        <td>
                            <input 
                                type="password" 
                                name="apcss_api_key" 
                                id="apcss_api_key"
                                required
                                class="regular-text" />
                            <p class="description">
                                <?php echo $apcss_api_key ? '<p style="color: #059669; font-weight: 500;">' . esc_html__( 'API key is configured. You are good to go!', 'ai-prompt-to-css' ) . '</p>' : '<p style="color: #cc0000; font-weight: 500;">' . esc_html__( 'No API Key found! Enter a valid key to start.', 'ai-prompt-to-css' ) . '</p>'; ?>
                                <?php echo esc_html__( 'Your API key is stored securely and never exposed.', 'ai-prompt-to-css' ); ?>
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

    public static function assets() {
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