<?php

namespace WP_Business_Reviews_Bundle\Includes;

class Assets {

    private $url;
    private $version;
    private $suffix;

    private static $css_asserts = array(
        'brb-admin-css'    => 'css/brb-admin',
        'rplg-builder-css' => 'css/rplg-builder',
        'rplg-css'         => 'css/rplg',
        'swiper-css'       => 'css/swiper.min',
        'brb-main-css'     => 'css/brb-main',
    );

    private static $js_asserts = array(
        'brb-wpac-js'      => 'js/wpac',
        'rplg-admin-js'    => 'js/rplg-admin',
        'rplg-builder-js'  => 'js/rplg-builder',
        'brb-wpac-time-js' => 'js/wpac-time',
        'blazy-js'         => 'js/blazy.min',
        'swiper-js'        => 'js/swiper.min',
        'rplg-js'          => 'js/rplg',
        'brb-main-js'      => 'js/brb-main',
    );

    public function __construct($url, $version) {
        $this->url     = $url;
        $this->version = $version;
        $this->suffix  = '';
    }

    public function register() {
        if (is_admin()) {
            add_action('admin_enqueue_scripts', array($this, 'register_styles'));
            add_action('admin_enqueue_scripts', array($this, 'register_scripts'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        } else {
            add_action('wp_enqueue_scripts', array($this, 'register_styles'));
            add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
            $brb_demand_assets = get_option('brb_demand_assets');
            if (!$brb_demand_assets || $brb_demand_assets != 'true') {
                add_action('wp_enqueue_scripts', array($this, 'enqueue_public_styles'));
                add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts'));
            }
            add_filter('script_loader_tag', array($this, 'add_async'), 10, 2);
        }
    }

    function add_async($tag, $handle) {
        $js_asserts = array(
            'brb-wpac-js'      => 'js/wpac',
            'rplg-admin-js'    => 'js/rplg-admin',
            'rplg-builder-js'  => 'js/rplg-builder',
            'brb-wpac-time-js' => 'js/wpac-time',
            'blazy-js'         => 'js/blazy.min',
            'swiper-js'        => 'js/swiper.min',
            'rplg-js'          => 'js/rplg',
            'brb-main-js'      => 'js/brb-main',
        );
        if (isset($handle) && array_key_exists($handle, $js_asserts)) {
            return str_replace(' src', ' defer="defer" src', $tag);
        }
        return $tag;
    }

    public function register_styles() {
        $this->register_styles_loop(array('brb-admin-css', 'rplg-builder-css', 'rplg-css', 'swiper-css', 'brb-main-css'));
    }

    public function register_scripts() {
        $this->register_scripts_loop(array('brb-wpac-js', 'rplg-admin-js', 'rplg-builder-js', 'brb-wpac-time-js', 'blazy-js', 'swiper-js', 'rplg-js', 'brb-main-js'));
    }

    public function enqueue_admin_styles() {
        wp_enqueue_style('brb-admin-css');
        wp_enqueue_style('rplg-builder-css');
        wp_enqueue_style('rplg-css');
        wp_enqueue_style('swiper-css');
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('brb-wpac-js');
        wp_enqueue_script('rplg-admin-js');
        wp_localize_script('rplg-builder-js', 'BRB_VARS', array(
            'wordpress'      => true,
            'googleAPIKey'   => get_option('brb_google_api_key'),
            'yelpAPIKey'     => get_option('brb_yelp_api_key'),
            'settingsUrl'    => admin_url('admin.php?page=brb-settings'),
            'handlerUrl'     => admin_url('options-general.php?page=brb'),
            'BRB_ASSETS_URL' => BRB_ASSETS_URL,
        ));
        wp_enqueue_script('rplg-builder-js');
        wp_enqueue_script('blazy-js');
        wp_enqueue_script('swiper-js');
        wp_enqueue_script('rplg-js');
    }

    public function enqueue_public_styles() {
        $brb_minified_assets = get_option('brb_minified_assets');
        if (!$brb_minified_assets || $brb_minified_assets != 'true') {
            wp_enqueue_style('rplg-css');
            wp_enqueue_style('swiper-css');
        } else {
            wp_enqueue_style('brb-main-css');
        }
    }

    public function enqueue_public_scripts() {
        $brb_minified_assets = get_option('brb_minified_assets');
        if (!$brb_minified_assets || $brb_minified_assets != 'true') {
            wp_enqueue_script('brb-wpac-time-js');
            wp_enqueue_script('blazy-js');
            wp_enqueue_script('swiper-js');
            wp_enqueue_script('rplg-js');
        } else {
            wp_enqueue_script('brb-main-js');
        }
    }

    public function get_public_styles() {
        $brb_minified_assets = get_option('brb_minified_assets');
        if (!$brb_minified_assets || $brb_minified_assets != 'true') {
            return array(
                $this->get_css_assert('rplg-css'),
                $this->get_css_assert('swiper-css'),
            );
        } else {
            return array($this->get_css_assert('brb-main-css'));
        }
    }

    public function get_public_scripts() {
        $brb_minified_assets = get_option('brb_minified_assets');
        if (!$brb_minified_assets || $brb_minified_assets != 'true') {
            return array(
                $this->get_js_assert('brb-wpac-time-js'),
                $this->get_js_assert('blazy-js'),
                $this->get_js_assert('swiper-js'),
                $this->get_js_assert('rplg-js'),
            );
        } else {
            return array($this->get_js_assert('brb-main-js'));
        }
    }

    private function register_styles_loop($styles) {
        foreach ($styles as $style) {
            wp_register_style($style, $this->get_css_assert($style), array(), $this->version);
        }
    }

    private function register_scripts_loop($scripts) {
        foreach ($scripts as $script) {
            wp_register_script($script, $this->get_js_assert($script), array(), $this->version);
        }
    }

    private function get_css_assert($assert) {
        return $this->url . self::$css_asserts[$assert] . $this->suffix . '.css';
    }

    private function get_js_assert($assert) {
        return $this->url . self::$js_asserts[$assert] . $this->suffix . '.js';
    }

}