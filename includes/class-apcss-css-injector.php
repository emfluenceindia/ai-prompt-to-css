<?php

namespace APCSS;

if( ! defined( 'ABSPATH' ) ) exit;

class CSS_Injector {

    const MARKER_START = "/* AI Prompt to CSS START */";
    const MARKER_END   = "/* AI Prompt to CSS END */";
    
    public static function init() {}

    public static function inject( $css, $prompt ) {
        if( empty ( $css ) ) return;

        $existing = wp_get_custom_css();
        $block    = self::MARKER_START . "\n";
        $block   .= "/* Prompt: {$prompt} */\n";
        $block   .= $css . "\n";
        $block   .= self::MARKER_END;

        $cleaned = preg_replace(
            '/' . preg_quote( self::MARKER_START, '/' ) . '.*?' . preg_quote( self::MARKER_END, '/' ) . '/s', 
            '', $existing
        );

        wp_update_custom_css_post( trim( $cleaned ) . "\n\n" . $block );
    }
}