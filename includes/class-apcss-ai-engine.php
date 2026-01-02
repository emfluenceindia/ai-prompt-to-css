<?php

namespace APCSS;

if( ! defined( 'ABSPATH' ) ) exit;

class AI_Engine {
    public static function generate_css( $prompt ) {
        $api_key = get_option( 'apcss_api_key' );
        if( ! $api_key ) return;

        $body = array(
            'model' => 'gpt-4o-mini',
            'messages' => array(
                array(
                    'role'    => 'system',
                    'content' => 'You are a CSS generator. 
                    Return ONLY valid CSS rules. 
                    Do NOT include explanations, markdown, or comments. 
                    If selectors are provided, use them exactly. 
                    If hover behavior is requested, use :hover. 
                    Output must contain at least one CSS rule.'
                ),
                array(
                    'role'    => 'user',
                    'content' => $prompt
                )
            ),
        );

        $response = wp_remote_post(
            'https://api.openai.com/v1/chat/completions',
            array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $api_key,
                    'Content-Type'  => 'application/json',
                ),
                'body'              => wp_json_encode( $body ),
                'timeout'           => 30
            )
        );

        if( is_wp_error( $response ) ) return '';

        $data = json_decode( wp_remote_retrieve_body( $response ), true );

        return Sanitizer::clean_css( $data['choices'][0]['message']['content'] ?? '' );
    }
}