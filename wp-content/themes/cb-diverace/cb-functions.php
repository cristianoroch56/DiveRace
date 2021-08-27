<?php

if( !\defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// add_action( 'admin_enqueue_scripts', 'cb_load_admin_assets' );
/**
 * Enqueue admin CSS and JS files.
 *
 * @since 1.0.0
 */
function cb_load_admin_assets() {

	$stylesheet_dir = get_stylesheet_directory_uri();

	$src = '/css/admin.css';
	wp_enqueue_style( 'cb-admin', $stylesheet_dir . $src, array(), VERSION );

	$src = '/js/admin.js';
	wp_enqueue_script( 'cb-admin', $stylesheet_dir . $src, array('jquery'), VERSION, true );

}

add_action( 'pre_ping', 'cb_disable_self_pings' );
/**
 * Prevent the child theme from being overwritten by a WordPress.org theme with the same name.
 *
 * See: http://wp-snippets.com/disable-self-trackbacks/
 *
 * @since 1.0.0
 *
 * @param mixed $links
 */
function cb_disable_self_pings(&$links) {

	foreach ( $links as $l => $link )
		if ( 0 === \mb_strpos( $link, home_url() ) )
			unset($links[$l]);

}

/**
 * Change WP JPEG compression (WP default is 90%).
 *
 * See: http://wpmu.org/how-to-change-jpeg-compression-in-wordpress/
 *
 * @since 1.0.0
 */
add_filter( 'jpeg_quality', 'cb_set_jpeg_quality' );
function cb_set_jpeg_quality() {

	return 80;

}

/**
 * Add new image sizes.
 *
 * See: http://wptheming.com/2014/04/features-wordpress-3-9/
 *
 * @since 1.0.0
 */
add_image_size( 'full-size', 1920, 1280, TRUE );
add_image_size( 'hero-size', 1920, 1080, TRUE );
add_image_size( 'large-size', 1280, 720, TRUE );
add_image_size( 'banner-size', 1280, 360, TRUE );
add_image_size( 'medium-size', 960, 540, TRUE );
add_image_size( 'small-size', 640, 360, TRUE );
add_image_size( 'experience-size', 280, 260, TRUE );
add_image_size( 'destination-size', 300, 320, TRUE );

add_image_size( 'gmap-popup-size', 425, 220, TRUE ); 
add_image_size( 'square-size', 500, 500, TRUE );

add_filter( 'upload_mimes', 'cb_enable_svg_uploads', 10, 1 );
/**
 * Enabled SVG uploads. Note that this could be a security issue, see: https://bjornjohansen.no/svg-in-wordpress.
 *
 * @since @since 1.0.0
 */
function cb_enable_svg_uploads($mimes) {

	$mimes['svg']  = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';

	return $mimes;

}

add_filter( 'image_size_names_choose', 'cb_image_size_names_choose' );
/**
 * Add new image sizes to media size selection menu.
 *
 * See: http://wpdaily.co/top-10-snippets/
 *
 * @since 1.0.0
 */
function cb_image_size_names_choose($sizes) {

	$sizes['full-size'] = __( 'Banner - Full', CHILD_THEME_TEXT_DOMAIN );
    $sizes['hero-size'] = __( 'Banner - Hero', CHILD_THEME_TEXT_DOMAIN );
	$sizes['large-size'] = __( 'Banner - Large', CHILD_THEME_TEXT_DOMAIN );
	$sizes['medium-size'] = __( 'Banner - Medium', CHILD_THEME_TEXT_DOMAIN );
	$sizes['small-size'] = __( 'Banner - Small', CHILD_THEME_TEXT_DOMAIN );
    $sizes['experience-size'] = __( 'Experience - Thumbnail', CHILD_THEME_TEXT_DOMAIN );
    $sizes['destination-size'] = __( 'Destination - Thumbnail', CHILD_THEME_TEXT_DOMAIN );
    $sizes['gmap-popup-size'] = __( 'Destination - Thumbnail', CHILD_THEME_TEXT_DOMAIN );

	return $sizes;

}

// Controls the featured image sizes in UABB
add_filter( 'uabb_blog_posts_featured_image_sizes', 'cb_image_size_names_uabb' );
function cb_image_size_names_uabb($sizes) {
    
    $sizes = array(
        'full' => __( 'Full', 'uabb' ),
        'large' => __( 'Large', 'uabb' ),
        'medium' => __( 'Medium', 'uabb' ),
        'thumbnail' => __( 'Thumbnail', 'uabb' ),
        'custom' => __( 'Custom', 'uabb' ),
        'full-size' => __( 'Banner - Full', 'uabb' ),
        'hero-size' => __( 'Banner - Hero', 'uabb' ),
        'size-large' => __( 'Banner - Large', 'uabb' ),
        'medium-size' => __( 'Banner - Large', 'uabb' ),
        'small-size' => __( 'Banner - Small', 'uabb' ),
        'experience-size' => __( 'Experience - Thumbnail', 'uabb' ),
        'destination-size' => __( 'Destination - Thumbnail', 'uabb' ),
    );
    
    return $sizes;
}

/*
 * Activate the Link Manager
 *
 * See: http://wordpressexperts.net/how-to-activate-link-manager-in-wordpress-3-5/
 *
 * @since 1.0.0
 */
// add_filter( 'pre_option_link_manager_enabled', '__return_true' );		// Activate

/*
 * Disable pingbacks
 *
 * See: http://wptavern.com/how-to-prevent-wordpress-from-participating-in-pingback-denial-of-service-attacks
 *
 * Still having pingback/trackback issues? This post might help: https://wordpress.org/support/topic/disabling-pingbackstrackbacks-on-pages#post-4046256
 *
 * @since 1.0.0
 */
add_filter( 'xmlrpc_methods', 'cb_remove_xmlrpc_pingback_ping' );
function cb_remove_xmlrpc_pingback_ping($methods) {

	unset($methods['pingback.ping']);

	return $methods;

}

/*
 * Disable XML-RPC
 *
 * See: https://wordpress.stackexchange.com/questions/78780/xmlrpc-enabled-filter-not-called
 *
 * @since 1.0.0
 */
if( \defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) exit;

/*
 * Automatically remove readme.html (and optionally xmlrpc.php) after a WP core update
 *
 * @since 1.0.0
 */
add_action( '_core_updated_successfully', 'cb_remove_files_on_upgrade' );
function cb_remove_files_on_upgrade() {

	if( \file_exists(ABSPATH . 'readme.html') )
		\unlink(ABSPATH . 'readme.html');

	if( \file_exists(ABSPATH . 'xmlrpc.php') )
		\unlink(ABSPATH . 'xmlrpc.php');

}

/*
 * Force secure cookie
 *
 * @since 1.0.0
 */
add_filter( 'secure_signon_cookie', '__return_true' );

/*
 * Prevent login with username (email only).
 *
 * @since 1.0.0
 */
remove_filter( 'authenticate', 'wp_authenticate_username_password', 20 );

/*
 * Prevent non-SSL HTTP origins.
 *
 * @since 1.0.0
 */
add_filter( 'allowed_http_origins', 'cb_allowed_http_origins' );
function cb_allowed_http_origins($allowed_origins) {

	$whitelisted_origins = array();
	foreach( $allowed_origins as $origin ) {
		$url = \parse_url($origin);
		if( 'https' !== $url['scheme'] )
			continue;

		$whitelisted_origins[] = $origin;
	}

	return $whitelisted_origins;

}

/*
 * Add ACF site options admin menu
 *
 * @since 1.0.0
 */
if( function_exists('acf_add_options_page') ){
	acf_add_options_page('Site Options');
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Strip API Settings',
		'menu_title'	=> 'Strip API Settings',
		'parent_slug'	=> 'theme-general-settings',
	));
}


// Gutenberg

// Add backend styles for Gutenberg.
add_action( 'enqueue_block_editor_assets', 'photographus_add_gutenberg_assets' );

/**
 * Load Gutenberg stylesheet.
 */
function photographus_add_gutenberg_assets() {
	// Load the theme styles within Gutenberg.
	wp_enqueue_style( 'diverace-gutenberg', get_theme_file_uri( '/style-editor.css' ), false );
}

add_action( 'after_setup_theme', 'cb_gutenberg_css' );
function cb_gutenberg_css(){
    
    // -- Responsive embeds
    add_theme_support( 'responsive-embeds' );
    
    // -- Wide Images
    add_theme_support( 'align-wide' );
    
    	// -- Disable custom font sizes
	add_theme_support( 'disable-custom-font-sizes' );

	// -- Editor Font Styles
	add_theme_support( 'editor-font-sizes', array(
		array(
			'name'      => __( 'small', 'ea_genesis_child' ),
			'shortName' => __( 'S', 'ea_genesis_child' ),
			'size'      => 14,
			'slug'      => 'small'
		),
		array(
			'name'      => __( 'regular', 'ea_genesis_child' ),
			'shortName' => __( 'M', 'ea_genesis_child' ),
			'size'      => 16,
			'slug'      => 'regular'
		),
		array(
			'name'      => __( 'large', 'ea_genesis_child' ),
			'shortName' => __( 'L', 'ea_genesis_child' ),
			'size'      => 20,
			'slug'      => 'large'
		),
	) );
    
    // -- Editor Color Palette
    add_theme_support( 'editor-color-palette', array(
        array(
            'name'  => __( 'Blue', 'ea_genesis_child' ),
            'slug'  => 'blue',
            'color'    => '#3396D8',
        ),
        array(
            'name'  => __( 'Grey', 'ea_genesis_child' ),
            'slug'  => 'ggrey',
            'color' => '#4F59609',
        ),
        array(
            'name'  => __( 'Dark Grey', 'ea_genesis_child' ),
            'slug'  => 'dark-grey',
            'color' => '#001121',
        ),
    ) );
 
}

add_action('wp_enqueue_scripts', 'diverace_vessel_scripts');
function diverace_vessel_scripts() {
    if ( is_singular( 'vessel' ) ) {
        wp_enqueue_script ( 'diverace-slick', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array(), '1.0.0', true );
        wp_enqueue_script ( 'diverace-magnific', '//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', array(), '1.0.0', true );
        wp_enqueue_script ( 'diverace-vessels', get_stylesheet_directory_uri() . '/scripts/cb-vessels.js', array(), '1.0.0', true );
        
        wp_enqueue_style ( 'diverace-slick', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css' );
        wp_enqueue_style ( 'diverace-slick-theme', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css' );
        wp_enqueue_style ( 'diverace-magnific', '//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css' );
    }
    
    if ( is_category( 'watch' ) || is_page( 34 ) ) {
        wp_enqueue_script ( 'diverace-magnific', '//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', array(), '1.0.0', true );
        wp_enqueue_script ( 'diverace-vessels', get_stylesheet_directory_uri() . '/scripts/cb-watch.js', array(), '1.0.0', true );
        
        wp_enqueue_style ( 'diverace-magnific', '//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css' );
    }
    
    wp_enqueue_script ( 'diverace-equal-height', get_stylesheet_directory_uri() . '/scripts/cb-equal-height.js', array(), '1.0.0', true );
	
	wp_enqueue_script ( 'diverace-mapapi-script', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDJbqVkRb6llQczHvPN_WnK4pGUGt30ykM', array(), '1.0.0', true );
    wp_enqueue_script ( 'diverace-map-script', get_stylesheet_directory_uri() . '/scripts/cb-custom-google-map.js', array(), '1.0.0', true );
    
    
}

// --------------------------------------------------------------
// Add WP search with shortcode
// --------------------------------------------------------------

function cb_search_form() {
    return get_search_form(false);
}
add_shortcode('display_search_form', 'cb_search_form');
