<?php

namespace APCSS;

if( ! defined( 'ABSPATH' ) ) exit;

class Sanitizer {
    public static function clean_css( $css_content ) {
        if( ! is_string( $css_content ) ) return;

        // Remove fenced code blocks
        $css_content = preg_replace( '#```[\s\S]*?```#', '', $css_content );

        // Remove <style> wrappers only
        $css_content = preg_replace( '#</?style[^>]*>#i', '', $css_content  );
        //$css_content = wp_strip_all_tags( $css_content );

        //Trim junk
        $css_content = trim( $css_content );

        // Must contain CSS rule(s)
        if( ! preg_match( '/\{.*?}/s', $css_content ) ) {
            return '';
        }

        return $css_content;
    }
}