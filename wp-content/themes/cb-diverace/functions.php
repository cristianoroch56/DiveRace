<?php

// Defines
define( 'FL_CHILD_THEME_DIR', get_stylesheet_directory() );
define( 'FL_CHILD_THEME_URL', get_stylesheet_directory_uri() );

// Classes
require_once 'classes/class-fl-child-theme.php';

// Actions
add_action( 'wp_enqueue_scripts', 'FLChildTheme::enqueue_scripts', 1000 );

// Custom
define( 'CHILD_THEME_NAME', 'DiveRACE' );
define( 'CHILD_THEME_URL', 'https://chillybin.com.sg' );
define( 'CHILD_THEME_TEXT_DOMAIN', 'cb-diverace' );
define( 'VERSION', time() );

require_once FL_CHILD_THEME_DIR . '/cb-admin.php';
require_once FL_CHILD_THEME_DIR . '/cb-branding.php';
require_once FL_CHILD_THEME_DIR . '/cb-functions.php';
require_once FL_CHILD_THEME_DIR . '/cb-post-types.php';
require_once FL_CHILD_THEME_DIR . '/cb-posts.php';
require_once FL_CHILD_THEME_DIR . '/cb-cabins.php';
require_once FL_CHILD_THEME_DIR . '/cb-itinerary-shortcode.php';
include 'custom-functions.php';
include 'custom-user-email.php';

include 'dashboard-function.php';		// use for user dashboard



