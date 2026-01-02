<?php

namespace APCSS;

if( ! defined( 'ABSPATH' ) ) exit;

class Sanitizer {
    public static function clean_css( $css_content ) {
        $css_content = preg_replace( '#```[\s\S]*?```#', '', $css_content );
        $css_content = wp_strip_all_tags( $css_content );

        return trim( $css_content );
    }
}