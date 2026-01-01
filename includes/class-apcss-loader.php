<?php
namespace APCSS;

if( ! defined( 'ABSPATH' ) ) exit;

final class Loader {
    public static function init() {
        require_once APCSS_PATH . 'includes/class-apcss-admin-page.php';
        require_once APCSS_PATH . 'includes/class-apcss-ai-engine.php';
        require_once APCSS_PATH . 'includes/class-apcss-css-injector.php';
        require_once APCSS_PATH . 'includes/class-apcss-sanitizer.php';

        Admin_Page::init();
        CSS_Injector::init();
    }
}