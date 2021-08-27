<?php

if( !\defined( 'ABSPATH' ) ) exit; // Exit if accessed directly




if ( ! function_exists('cb_diverace_destinations') ) {

    // Register Custom Post Type
    function cb_diverace_destinations() {
    
        $labels = array(
            'name'                  => _x( 'Destinations', 'Post Type General Name', 'diverace' ),
            'singular_name'         => _x( 'Destination', 'Post Type Singular Name', 'diverace' ),
            'menu_name'             => __( 'Destinations', 'diverace' ),
            'name_admin_bar'        => __( 'Destination', 'diverace' ),
            'archives'              => __( 'Item Archives', 'diverace' ),
            'attributes'            => __( 'Item Attributes', 'diverace' ),
            'parent_item_colon'     => __( 'Parent Item:', 'diverace' ),
            'all_items'             => __( 'All Items', 'diverace' ),
            'add_new_item'          => __( 'Add New Item', 'diverace' ),
            'add_new'               => __( 'Add New', 'diverace' ),
            'new_item'              => __( 'New Item', 'diverace' ),
            'edit_item'             => __( 'Edit Item', 'diverace' ),
            'update_item'           => __( 'Update Item', 'diverace' ),
            'view_item'             => __( 'View Item', 'diverace' ),
            'view_items'            => __( 'View Items', 'diverace' ),
            'search_items'          => __( 'Search Item', 'diverace' ),
            'not_found'             => __( 'Not found', 'diverace' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'diverace' ),
            'featured_image'        => __( 'Featured Image', 'diverace' ),
            'set_featured_image'    => __( 'Set featured image', 'diverace' ),
            'remove_featured_image' => __( 'Remove featured image', 'diverace' ),
            'use_featured_image'    => __( 'Use as featured image', 'diverace' ),
            'insert_into_item'      => __( 'Insert into item', 'diverace' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'diverace' ),
            'items_list'            => __( 'Items list', 'diverace' ),
            'items_list_navigation' => __( 'Items list navigation', 'diverace' ),
            'filter_items_list'     => __( 'Filter items list', 'diverace' ),
        );
        $args = array(
            'label'                 => __( 'Destinations', 'diverace' ),
            'description'           => __( 'Destinations Post Type', 'diverace' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'taxonomies'            => array( 'country' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 30,
            'menu_icon'             => 'dashicons-palmtree',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'destination', $args );
    
    }
    add_action( 'init', 'cb_diverace_destinations', 0 );

}

if ( ! function_exists('cb_diverace_vessels') ) {

    // Register Custom Post Type
    function cb_diverace_vessels() {
    
        $labels = array(
            'name'                  => _x( 'Vessels', 'Post Type General Name', 'diverace' ),
            'singular_name'         => _x( 'Vessel', 'Post Type Singular Name', 'diverace' ),
            'menu_name'             => __( 'Vessels', 'diverace' ),
            'name_admin_bar'        => __( 'Vessel', 'diverace' ),
            'archives'              => __( 'Item Archives', 'diverace' ),
            'attributes'            => __( 'Item Attributes', 'diverace' ),
            'parent_item_colon'     => __( 'Parent Item:', 'diverace' ),
            'all_items'             => __( 'All Items', 'diverace' ),
            'add_new_item'          => __( 'Add New Item', 'diverace' ),
            'add_new'               => __( 'Add New', 'diverace' ),
            'new_item'              => __( 'New Item', 'diverace' ),
            'edit_item'             => __( 'Edit Item', 'diverace' ),
            'update_item'           => __( 'Update Item', 'diverace' ),
            'view_item'             => __( 'View Item', 'diverace' ),
            'view_items'            => __( 'View Items', 'diverace' ),
            'search_items'          => __( 'Search Item', 'diverace' ),
            'not_found'             => __( 'Not found', 'diverace' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'diverace' ),
            'featured_image'        => __( 'Featured Image', 'diverace' ),
            'set_featured_image'    => __( 'Set featured image', 'diverace' ),
            'remove_featured_image' => __( 'Remove featured image', 'diverace' ),
            'use_featured_image'    => __( 'Use as featured image', 'diverace' ),
            'insert_into_item'      => __( 'Insert into item', 'diverace' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'diverace' ),
            'items_list'            => __( 'Items list', 'diverace' ),
            'items_list_navigation' => __( 'Items list navigation', 'diverace' ),
            'filter_items_list'     => __( 'Filter items list', 'diverace' ),
        );
        $args = array(
            'label'                 => __( 'Vessels', 'diverace' ),
            'description'           => __( 'Vessels Post Type', 'diverace' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'taxonomies'            => array( ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 30,
            'menu_icon'             => 'dashicons-sos',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'vessel', $args );
    
    }
    add_action( 'init', 'cb_diverace_vessels', 0 );

}

if ( ! function_exists('cb_diverace_itineraries') ) {

    // Register Custom Post Type
    function cb_diverace_itineraries() {
    
        $labels = array(
            'name'                  => _x( 'Itineraries', 'Post Type General Name', 'diverace' ),
            'singular_name'         => _x( 'Itinerary', 'Post Type Singular Name', 'diverace' ),
            'menu_name'             => __( 'Itineraries', 'diverace' ),
            'name_admin_bar'        => __( 'Itinerary', 'diverace' ),
            'archives'              => __( 'Item Archives', 'diverace' ),
            'attributes'            => __( 'Item Attributes', 'diverace' ),
            'parent_item_colon'     => __( 'Parent Item:', 'diverace' ),
            'all_items'             => __( 'All Items', 'diverace' ),
            'add_new_item'          => __( 'Add New Item', 'diverace' ),
            'add_new'               => __( 'Add New', 'diverace' ),
            'new_item'              => __( 'New Item', 'diverace' ),
            'edit_item'             => __( 'Edit Item', 'diverace' ),
            'update_item'           => __( 'Update Item', 'diverace' ),
            'view_item'             => __( 'View Item', 'diverace' ),
            'view_items'            => __( 'View Items', 'diverace' ),
            'search_items'          => __( 'Search Item', 'diverace' ),
            'not_found'             => __( 'Not found', 'diverace' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'diverace' ),
            'featured_image'        => __( 'Featured Image', 'diverace' ),
            'set_featured_image'    => __( 'Set featured image', 'diverace' ),
            'remove_featured_image' => __( 'Remove featured image', 'diverace' ),
            'use_featured_image'    => __( 'Use as featured image', 'diverace' ),
            'insert_into_item'      => __( 'Insert into item', 'diverace' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'diverace' ),
            'items_list'            => __( 'Items list', 'diverace' ),
            'items_list_navigation' => __( 'Items list navigation', 'diverace' ),
            'filter_items_list'     => __( 'Filter items list', 'diverace' ),
        );
        $args = array(
            'label'                 => __( 'Itineraries', 'diverace' ),
            'description'           => __( 'Itineraries Post Type', 'diverace' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'taxonomies'            => array( 'itinerary-destination' ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 30,
            'menu_icon'             => 'dashicons-format-aside',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'itinerary', $args );
    
    }
    add_action( 'init', 'cb_diverace_itineraries', 0 );

}

if ( ! function_exists( 'cb_diverace_country' ) ) {

    // Register Custom Taxonomy
    function cb_diverace_country() {
    
        $labels = array(
            'name'                       => _x( 'Countries', 'Taxonomy General Name', 'diverace' ),
            'singular_name'              => _x( 'Country', 'Taxonomy Singular Name', 'diverace' ),
            'menu_name'                  => __( 'Countries', 'diverace' ),
            'all_items'                  => __( 'All Items', 'diverace' ),
            'parent_item'                => __( 'Parent Item', 'diverace' ),
            'parent_item_colon'          => __( 'Parent Item:', 'diverace' ),
            'new_item_name'              => __( 'New Item Name', 'diverace' ),
            'add_new_item'               => __( 'Add New Item', 'diverace' ),
            'edit_item'                  => __( 'Edit Item', 'diverace' ),
            'update_item'                => __( 'Update Item', 'diverace' ),
            'view_item'                  => __( 'View Item', 'diverace' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'diverace' ),
            'add_or_remove_items'        => __( 'Add or remove items', 'diverace' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'diverace' ),
            'popular_items'              => __( 'Popular Items', 'diverace' ),
            'search_items'               => __( 'Search Items', 'diverace' ),
            'not_found'                  => __( 'Not Found', 'diverace' ),
            'no_terms'                   => __( 'No items', 'diverace' ),
            'items_list'                 => __( 'Items list', 'diverace' ),
            'items_list_navigation'      => __( 'Items list navigation', 'diverace' ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
        );
        register_taxonomy( 'country', array( 'destination', 'itinerary' ), $args );
    
    }
    add_action( 'init', 'cb_diverace_country', 0 );

}

if ( ! function_exists('cb_diverace_trips') ) {
    
    // Register Custom Post Type
    function cb_diverace_trips() {
        
        $labels = array(
            'name'                  => _x( 'Trips', 'Post Type General Name', 'diverace' ),
            'singular_name'         => _x( 'Trip', 'Post Type Singular Name', 'diverace' ),
            'menu_name'             => __( 'Trips', 'diverace' ),
            'name_admin_bar'        => __( 'Trip', 'diverace' ),
            'archives'              => __( 'Item Archives', 'diverace' ),
            'attributes'            => __( 'Item Attributes', 'diverace' ),
            'parent_item_colon'     => __( 'Parent Item:', 'diverace' ),
            'all_items'             => __( 'All Items', 'diverace' ),
            'add_new_item'          => __( 'Add New Item', 'diverace' ),
            'add_new'               => __( 'Add New', 'diverace' ),
            'new_item'              => __( 'New Item', 'diverace' ),
            'edit_item'             => __( 'Edit Item', 'diverace' ),
            'update_item'           => __( 'Update Item', 'diverace' ),
            'view_item'             => __( 'View Item', 'diverace' ),
            'view_items'            => __( 'View Items', 'diverace' ),
            'search_items'          => __( 'Search Item', 'diverace' ),
            'not_found'             => __( 'Not found', 'diverace' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'diverace' ),
            'featured_image'        => __( 'Featured Image', 'diverace' ),
            'set_featured_image'    => __( 'Set featured image', 'diverace' ),
            'remove_featured_image' => __( 'Remove featured image', 'diverace' ),
            'use_featured_image'    => __( 'Use as featured image', 'diverace' ),
            'insert_into_item'      => __( 'Insert into item', 'diverace' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'diverace' ),
            'items_list'            => __( 'Items list', 'diverace' ),
            'items_list_navigation' => __( 'Items list navigation', 'diverace' ),
            'filter_items_list'     => __( 'Filter items list', 'diverace' ),
        );
        $args = array(
            'label'                 => __( 'Trips', 'diverace' ),
            'description'           => __( 'Trips Post Type', 'diverace' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'taxonomies'            => array( ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 30,
            'menu_icon'               => 'dashicons-location',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'trip', $args );
        
    }
    add_action( 'init', 'cb_diverace_trips', 0 );
    
}

if ( ! function_exists('cb_diverace_sites') ) {
    
    // Register Custom Post Type
    function cb_diverace_sites() {
        
        $labels = array(
            'name'                  => _x( 'Sites', 'Post Type General Name', 'diverace' ),
            'singular_name'         => _x( 'Site', 'Post Type Singular Name', 'diverace' ),
            'menu_name'             => __( 'Sites', 'diverace' ),
            'name_admin_bar'        => __( 'Site', 'diverace' ),
            'archives'              => __( 'Item Archives', 'diverace' ),
            'attributes'            => __( 'Item Attributes', 'diverace' ),
            'parent_item_colon'     => __( 'Parent Item:', 'diverace' ),
            'all_items'             => __( 'All Items', 'diverace' ),
            'add_new_item'          => __( 'Add New Item', 'diverace' ),
            'add_new'               => __( 'Add New', 'diverace' ),
            'new_item'              => __( 'New Item', 'diverace' ),
            'edit_item'             => __( 'Edit Item', 'diverace' ),
            'update_item'           => __( 'Update Item', 'diverace' ),
            'view_item'             => __( 'View Item', 'diverace' ),
            'view_items'            => __( 'View Items', 'diverace' ),
            'search_items'          => __( 'Search Item', 'diverace' ),
            'not_found'             => __( 'Not found', 'diverace' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'diverace' ),
            'featured_image'        => __( 'Featured Image', 'diverace' ),
            'set_featured_image'    => __( 'Set featured image', 'diverace' ),
            'remove_featured_image' => __( 'Remove featured image', 'diverace' ),
            'use_featured_image'    => __( 'Use as featured image', 'diverace' ),
            'insert_into_item'      => __( 'Insert into item', 'diverace' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'diverace' ),
            'items_list'            => __( 'Items list', 'diverace' ),
            'items_list_navigation' => __( 'Items list navigation', 'diverace' ),
            'filter_items_list'     => __( 'Filter items list', 'diverace' ),
        );
        $args = array(
            'label'                 => __( 'Sites', 'diverace' ),
            'description'           => __( 'Sites Post Type', 'diverace' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'taxonomies'            => array( ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 30,
            'menu_icon'             => 'dashicons-admin-site-alt2',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'site', $args );
        
    }
    add_action( 'init', 'cb_diverace_sites', 0 );
    
}

if ( ! function_exists('cb_diverace_courses') ) {

    // Register Custom Post Type
    function cb_diverace_courses() {
    
        $labels = array(
            'name'                  => _x( 'Courses', 'Post Type General Name', 'diverace' ),
            'singular_name'         => _x( 'Course', 'Post Type Singular Name', 'diverace' ),
            'menu_name'             => __( 'Courses', 'diverace' ),
            'name_admin_bar'        => __( 'Course', 'diverace' ),
            'archives'              => __( 'Item Archives', 'diverace' ),
            'attributes'            => __( 'Item Attributes', 'diverace' ),
            'parent_item_colon'     => __( 'Parent Item:', 'diverace' ),
            'all_items'             => __( 'All Items', 'diverace' ),
            'add_new_item'          => __( 'Add New Item', 'diverace' ),
            'add_new'               => __( 'Add New', 'diverace' ),
            'new_item'              => __( 'New Item', 'diverace' ),
            'edit_item'             => __( 'Edit Item', 'diverace' ),
            'update_item'           => __( 'Update Item', 'diverace' ),
            'view_item'             => __( 'View Item', 'diverace' ),
            'view_items'            => __( 'View Items', 'diverace' ),
            'search_items'          => __( 'Search Item', 'diverace' ),
            'not_found'             => __( 'Not found', 'diverace' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'diverace' ),
            'featured_image'        => __( 'Featured Image', 'diverace' ),
            'set_featured_image'    => __( 'Set featured image', 'diverace' ),
            'remove_featured_image' => __( 'Remove featured image', 'diverace' ),
            'use_featured_image'    => __( 'Use as featured image', 'diverace' ),
            'insert_into_item'      => __( 'Insert into item', 'diverace' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'diverace' ),
            'items_list'            => __( 'Items list', 'diverace' ),
            'items_list_navigation' => __( 'Items list navigation', 'diverace' ),
            'filter_items_list'     => __( 'Filter items list', 'diverace' ),
        );
        $args = array(
            'label'                 => __( 'Courses', 'diverace' ),
            'description'           => __( 'Courses Post Type', 'diverace' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'taxonomies'            => array( ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 30,
            'menu_icon'             => 'dashicons-welcome-learn-more',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'course', $args );
    
    }
    add_action( 'init', 'cb_diverace_courses', 0 );

}

if ( ! function_exists('cb_diverace_cabins') ) {

    // Register Custom Post Type
    function cb_diverace_cabins() {
    
        $labels = array(
            'name'                  => _x( 'Cabins', 'Post Type General Name', 'diverace' ),
            'singular_name'         => _x( 'Cabin', 'Post Type Singular Name', 'diverace' ),
            'menu_name'             => __( 'Cabins', 'diverace' ),
            'name_admin_bar'        => __( 'Cabin', 'diverace' ),
            'archives'              => __( 'Item Archives', 'diverace' ),
            'attributes'            => __( 'Item Attributes', 'diverace' ),
            'parent_item_colon'     => __( 'Parent Item:', 'diverace' ),
            'all_items'             => __( 'All Items', 'diverace' ),
            'add_new_item'          => __( 'Add New Item', 'diverace' ),
            'add_new'               => __( 'Add New', 'diverace' ),
            'new_item'              => __( 'New Item', 'diverace' ),
            'edit_item'             => __( 'Edit Item', 'diverace' ),
            'update_item'           => __( 'Update Item', 'diverace' ),
            'view_item'             => __( 'View Item', 'diverace' ),
            'view_items'            => __( 'View Items', 'diverace' ),
            'search_items'          => __( 'Search Item', 'diverace' ),
            'not_found'             => __( 'Not found', 'diverace' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'diverace' ),
            'featured_image'        => __( 'Featured Image', 'diverace' ),
            'set_featured_image'    => __( 'Set featured image', 'diverace' ),
            'remove_featured_image' => __( 'Remove featured image', 'diverace' ),
            'use_featured_image'    => __( 'Use as featured image', 'diverace' ),
            'insert_into_item'      => __( 'Insert into item', 'diverace' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'diverace' ),
            'items_list'            => __( 'Items list', 'diverace' ),
            'items_list_navigation' => __( 'Items list navigation', 'diverace' ),
            'filter_items_list'     => __( 'Filter items list', 'diverace' ),
        );
        $args = array(
            'label'                 => __( 'Cabins', 'diverace' ),
            'description'           => __( 'Cabins Post Type', 'diverace' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'taxonomies'            => array( ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 30,
            'menu_icon'             => 'dashicons-admin-home',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'cabin', $args );
    
    }
    add_action( 'init', 'cb_diverace_cabins', 0 );

}

if ( ! function_exists('cb_diverace_rentals') ) {

    // Register Custom Post Type
    function cb_diverace_rentals() {
    
        $labels = array(
            'name'                  => _x( 'Rentals', 'Post Type General Name', 'diverace' ),
            'singular_name'         => _x( 'Rental', 'Post Type Singular Name', 'diverace' ),
            'menu_name'             => __( 'Rentals', 'diverace' ),
            'name_admin_bar'        => __( 'Rental', 'diverace' ),
            'archives'              => __( 'Item Archives', 'diverace' ),
            'attributes'            => __( 'Item Attributes', 'diverace' ),
            'parent_item_colon'     => __( 'Parent Item:', 'diverace' ),
            'all_items'             => __( 'All Items', 'diverace' ),
            'add_new_item'          => __( 'Add New Item', 'diverace' ),
            'add_new'               => __( 'Add New', 'diverace' ),
            'new_item'              => __( 'New Item', 'diverace' ),
            'edit_item'             => __( 'Edit Item', 'diverace' ),
            'update_item'           => __( 'Update Item', 'diverace' ),
            'view_item'             => __( 'View Item', 'diverace' ),
            'view_items'            => __( 'View Items', 'diverace' ),
            'search_items'          => __( 'Search Item', 'diverace' ),
            'not_found'             => __( 'Not found', 'diverace' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'diverace' ),
            'featured_image'        => __( 'Featured Image', 'diverace' ),
            'set_featured_image'    => __( 'Set featured image', 'diverace' ),
            'remove_featured_image' => __( 'Remove featured image', 'diverace' ),
            'use_featured_image'    => __( 'Use as featured image', 'diverace' ),
            'insert_into_item'      => __( 'Insert into item', 'diverace' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'diverace' ),
            'items_list'            => __( 'Items list', 'diverace' ),
            'items_list_navigation' => __( 'Items list navigation', 'diverace' ),
            'filter_items_list'     => __( 'Filter items list', 'diverace' ),
        );
        $args = array(
            'label'                 => __( 'Rentals', 'diverace' ),
            'description'           => __( 'Rental Equipment Post Type', 'diverace' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'taxonomies'            => array( ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 30,
            'menu_icon'               => 'dashicons-admin-home',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'rental_equipment', $args );
    
    }
    add_action( 'init', 'cb_diverace_rentals', 0 );

}

if ( ! function_exists('cb_diverace_orders') ) {

    // Register Custom Post Type
    function cb_diverace_orders() {
    
        $labels = array(
            'name'                  => _x( 'Orders', 'Post Type General Name', 'diverace' ),
            'singular_name'         => _x( 'Order', 'Post Type Singular Name', 'diverace' ),
            'menu_name'             => __( 'Orders', 'diverace' ),
            'name_admin_bar'        => __( 'Order', 'diverace' ),
            'archives'              => __( 'Item Archives', 'diverace' ),
            'attributes'            => __( 'Item Attributes', 'diverace' ),
            'parent_item_colon'     => __( 'Parent Item:', 'diverace' ),
            'all_items'             => __( 'All Items', 'diverace' ),
            'add_new_item'          => __( 'Add New Item', 'diverace' ),
            'add_new'               => __( 'Add New', 'diverace' ),
            'new_item'              => __( 'New Item', 'diverace' ),
            'edit_item'             => __( 'Edit Item', 'diverace' ),
            'update_item'           => __( 'Update Item', 'diverace' ),
            'view_item'             => __( 'View Item', 'diverace' ),
            'view_items'            => __( 'View Items', 'diverace' ),
            'search_items'          => __( 'Search Item', 'diverace' ),
            'not_found'             => __( 'Not found', 'diverace' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'diverace' ),
            'featured_image'        => __( 'Featured Image', 'diverace' ),
            'set_featured_image'    => __( 'Set featured image', 'diverace' ),
            'remove_featured_image' => __( 'Remove featured image', 'diverace' ),
            'use_featured_image'    => __( 'Use as featured image', 'diverace' ),
            'insert_into_item'      => __( 'Insert into item', 'diverace' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'diverace' ),
            'items_list'            => __( 'Items list', 'diverace' ),
            'items_list_navigation' => __( 'Items list navigation', 'diverace' ),
            'filter_items_list'     => __( 'Filter items list', 'diverace' ),
        );
        $args = array(
            'label'                 => __( 'Orders', 'diverace' ),
            'description'           => __( 'Orders Post Type', 'diverace' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'taxonomies'            => array( ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 30,
            'menu_icon'               => 'dashicons-admin-home',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'orders', $args );
    
    }
    add_action( 'init', 'cb_diverace_orders', 0 );

}

if ( ! function_exists('cb_diverace_coupons') ) {

    // Register Custom Post Type
    function cb_diverace_coupons() {
    
        $labels = array(
            'name'                  => _x( 'Coupons', 'Post Type General Name', 'diverace' ),
            'singular_name'         => _x( 'Coupon', 'Post Type Singular Name', 'diverace' ),
            'menu_name'             => __( 'Coupons', 'diverace' ),
            'name_admin_bar'        => __( 'Coupon', 'diverace' ),
            'archives'              => __( 'Item Archives', 'diverace' ),
            'attributes'            => __( 'Item Attributes', 'diverace' ),
            'parent_item_colon'     => __( 'Parent Item:', 'diverace' ),
            'all_items'             => __( 'All Items', 'diverace' ),
            'add_new_item'          => __( 'Add New Item', 'diverace' ),
            'add_new'               => __( 'Add New', 'diverace' ),
            'new_item'              => __( 'New Item', 'diverace' ),
            'edit_item'             => __( 'Edit Item', 'diverace' ),
            'update_item'           => __( 'Update Item', 'diverace' ),
            'view_item'             => __( 'View Item', 'diverace' ),
            'view_items'            => __( 'View Items', 'diverace' ),
            'search_items'          => __( 'Search Item', 'diverace' ),
            'not_found'             => __( 'Not found', 'diverace' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'diverace' ),
            'featured_image'        => __( 'Featured Image', 'diverace' ),
            'set_featured_image'    => __( 'Set featured image', 'diverace' ),
            'remove_featured_image' => __( 'Remove featured image', 'diverace' ),
            'use_featured_image'    => __( 'Use as featured image', 'diverace' ),
            'insert_into_item'      => __( 'Insert into item', 'diverace' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'diverace' ),
            'items_list'            => __( 'Items list', 'diverace' ),
            'items_list_navigation' => __( 'Items list navigation', 'diverace' ),
            'filter_items_list'     => __( 'Filter items list', 'diverace' ),
        );
        $args = array(
            'label'                 => __( 'Coupons', 'diverace' ),
            'description'           => __( 'Coupons Post Type', 'diverace' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'taxonomies'            => array( ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 30,
            'menu_icon'               => 'dashicons-admin-home',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'coupons', $args );
    
    }
    add_action( 'init', 'cb_diverace_coupons', 0 );

}

if ( ! function_exists('cb_diverace_invoices') ) {

    // Register Custom Post Type
    function cb_diverace_invoices() {
    
        $labels = array(
            'name'                  => _x( 'Invoices', 'Post Type General Name', 'diverace' ),
            'singular_name'         => _x( 'Invoice', 'Post Type Singular Name', 'diverace' ),
            'menu_name'             => __( 'Invoices', 'diverace' ),
            'name_admin_bar'        => __( 'Invoice', 'diverace' ),
            'archives'              => __( 'Item Archives', 'diverace' ),
            'attributes'            => __( 'Item Attributes', 'diverace' ),
            'parent_item_colon'     => __( 'Parent Item:', 'diverace' ),
            'all_items'             => __( 'All Items', 'diverace' ),
            'add_new_item'          => __( 'Add New Item', 'diverace' ),
            'add_new'               => __( 'Add New', 'diverace' ),
            'new_item'              => __( 'New Item', 'diverace' ),
            'edit_item'             => __( 'Edit Item', 'diverace' ),
            'update_item'           => __( 'Update Item', 'diverace' ),
            'view_item'             => __( 'View Item', 'diverace' ),
            'view_items'            => __( 'View Items', 'diverace' ),
            'search_items'          => __( 'Search Item', 'diverace' ),
            'not_found'             => __( 'Not found', 'diverace' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'diverace' ),
            'featured_image'        => __( 'Featured Image', 'diverace' ),
            'set_featured_image'    => __( 'Set featured image', 'diverace' ),
            'remove_featured_image' => __( 'Remove featured image', 'diverace' ),
            'use_featured_image'    => __( 'Use as featured image', 'diverace' ),
            'insert_into_item'      => __( 'Insert into item', 'diverace' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'diverace' ),
            'items_list'            => __( 'Items list', 'diverace' ),
            'items_list_navigation' => __( 'Items list navigation', 'diverace' ),
            'filter_items_list'     => __( 'Filter items list', 'diverace' ),
        );
        $args = array(
            'label'                 => __( 'Invoices', 'diverace' ),
            'description'           => __( 'Invoice Post Type', 'diverace' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'taxonomies'            => array( ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 30,
            'menu_icon'               => 'dashicons-admin-home',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'invoice', $args );
    
    }
    add_action( 'init', 'cb_diverace_invoices', 0 );

}

if ( ! function_exists('cb_diverace_profiles') ) {

    // Register Custom Post Type
    function cb_diverace_priofiles() {
    
        $labels = array(
            'name'                  => _x( 'Profiles', 'Post Type General Name', 'diverace' ),
            'singular_name'         => _x( 'Profile', 'Post Type Singular Name', 'diverace' ),
            'menu_name'             => __( 'Profiles', 'diverace' ),
            'name_admin_bar'        => __( 'Profile', 'diverace' ),
            'archives'              => __( 'Item Archives', 'diverace' ),
            'attributes'            => __( 'Item Attributes', 'diverace' ),
            'parent_item_colon'     => __( 'Parent Item:', 'diverace' ),
            'all_items'             => __( 'All Items', 'diverace' ),
            'add_new_item'          => __( 'Add New Item', 'diverace' ),
            'add_new'               => __( 'Add New', 'diverace' ),
            'new_item'              => __( 'New Item', 'diverace' ),
            'edit_item'             => __( 'Edit Item', 'diverace' ),
            'update_item'           => __( 'Update Item', 'diverace' ),
            'view_item'             => __( 'View Item', 'diverace' ),
            'view_items'            => __( 'View Items', 'diverace' ),
            'search_items'          => __( 'Search Item', 'diverace' ),
            'not_found'             => __( 'Not found', 'diverace' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'diverace' ),
            'featured_image'        => __( 'Featured Image', 'diverace' ),
            'set_featured_image'    => __( 'Set featured image', 'diverace' ),
            'remove_featured_image' => __( 'Remove featured image', 'diverace' ),
            'use_featured_image'    => __( 'Use as featured image', 'diverace' ),
            'insert_into_item'      => __( 'Insert into item', 'diverace' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'diverace' ),
            'items_list'            => __( 'Items list', 'diverace' ),
            'items_list_navigation' => __( 'Items list navigation', 'diverace' ),
            'filter_items_list'     => __( 'Filter items list', 'diverace' ),
        );
        $args = array(
            'label'                 => __( 'Profiles', 'diverace' ),
            'description'           => __( 'Profile Post Type', 'diverace' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'taxonomies'            => array( ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 30,
            'menu_icon'               => 'dashicons-admin-home',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'profile', $args );
    
    }
    add_action( 'init', 'cb_diverace_profiles', 0 );

}
if ( ! function_exists('cb_diverace_agent_code') ) {

    // Register Custom Post Type
    function cb_diverace_agent_code() {
    
        $labels = array(
            'name'                  => _x( 'Agent Codes', 'Post Type General Name', 'diverace' ),
            'singular_name'         => _x( 'Agent Code', 'Post Type Singular Name', 'diverace' ),
            'menu_name'             => __( 'Agent Codes', 'diverace' ),
            'name_admin_bar'        => __( 'Agent Code', 'diverace' ),
            'archives'              => __( 'Item Archives', 'diverace' ),
            'attributes'            => __( 'Item Attributes', 'diverace' ),
            'parent_item_colon'     => __( 'Parent Item:', 'diverace' ),
            'all_items'             => __( 'All Items', 'diverace' ),
            'add_new_item'          => __( 'Add New Item', 'diverace' ),
            'add_new'               => __( 'Add New', 'diverace' ),
            'new_item'              => __( 'New Item', 'diverace' ),
            'edit_item'             => __( 'Edit Item', 'diverace' ),
            'update_item'           => __( 'Update Item', 'diverace' ),
            'view_item'             => __( 'View Item', 'diverace' ),
            'view_items'            => __( 'View Items', 'diverace' ),
            'search_items'          => __( 'Search Item', 'diverace' ),
            'not_found'             => __( 'Not found', 'diverace' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'diverace' ),
            'featured_image'        => __( 'Featured Image', 'diverace' ),
            'set_featured_image'    => __( 'Set featured image', 'diverace' ),
            'remove_featured_image' => __( 'Remove featured image', 'diverace' ),
            'use_featured_image'    => __( 'Use as featured image', 'diverace' ),
            'insert_into_item'      => __( 'Insert into item', 'diverace' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'diverace' ),
            'items_list'            => __( 'Items list', 'diverace' ),
            'items_list_navigation' => __( 'Items list navigation', 'diverace' ),
            'filter_items_list'     => __( 'Filter items list', 'diverace' ),
        );
        $args = array(
            'label'                 => __( 'Agent Codes', 'diverace' ),
            'description'           => __( 'Agent Codes Post Type', 'diverace' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'taxonomies'            => array( ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 30,
            'menu_icon'             => 'dashicons-tickets-alt',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'agent_code', $args );
    
    }
    add_action( 'init', 'cb_diverace_agent_code', 0 );

}


/*Custom Post type start*/
function cw_post_type_rental_Equipment() {
$supports = array(
'title', // post title
'editor', // post content
'author', // post author
'thumbnail', // featured images
'excerpt', // post excerpt
'custom-fields', // custom fields
'comments', // post comments
'revisions', // post revisions
'post-formats', // post formats
);
$labels = array(
'name' => _x('Rental Equipment', 'plural'),
'singular_name' => _x('Rental Equipment', 'singular'),
'menu_name' => _x('Rental Equipment', 'admin menu'),
'name_admin_bar' => _x('Rental Equipment', 'admin bar'),
'add_new' => _x('Add New', 'add new'),
'add_new_item' => __('Add New Rental Equipment'),
'new_item' => __('New Rental Equipment'),
'edit_item' => __('Edit Rental Equipment'),
'view_item' => __('View Rental Equipment'),
'all_items' => __('All Rental Equipment'),
'search_items' => __('Search Rental Equipment'),
'not_found' => __('No Rental Equipment found.'),
);
$args = array(
'supports' => $supports,
'labels' => $labels,
'public' => true,
'query_var' => true,
'rewrite' => array('slug' => 'Rental Equipment'),
'has_archive' => true,
'hierarchical' => false,
);
register_post_type('rental_equipment', $args);
}
add_action('init', 'cw_post_type_rental_Equipment');


function add_equipment_type_taxonomy_to_post(){

    //set the name of the taxonomy
    $taxonomy = 'equipment_type';
    //set the post types for the taxonomy
    $object_type = 'rental_equipment';
    
    //populate our array of names for our taxonomy
    $labels = array(
        'name'               => 'Equipment Type',
        'singular_name'      => 'Equipment Type',
        'search_items'       => 'Search Equipment Type',
        'all_items'          => 'All Equipment Type',
        'parent_item'        => 'Parent Equipment Type',
        'parent_item_colon'  => 'Parent Equipment Type:',
        'update_item'        => 'Update Equipment Type',
        'edit_item'          => 'Edit Equipment Type',
        'add_new_item'       => 'Add New Equipment Type', 
        'new_item_name'      => 'New Equipment Type Name',
        'menu_name'          => 'Equipment Type'
        );
    
    //define arguments to be used 
    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'show_ui'           => true,
        'how_in_nav_menus'  => true,
        'public'            => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'equipment_type')
    );
    
    //call the register_taxonomy function
    //register_taxonomy($taxonomy, $object_type, $args); 
}
add_action('init','add_equipment_type_taxonomy_to_post');
 function add_pax_taxonomy_to_post(){

    //set the name of the taxonomy
    $taxonomy = 'pax';
    //set the post types for the taxonomy
    $object_type = 'cabin';
    
    //populate our array of names for our taxonomy
    $labels = array(
        'name'               => 'Pax',
        'singular_name'      => 'Pax',
        'search_items'       => 'Search Pax',
        'all_items'          => 'All Pax',
        'parent_item'        => 'Parent Pax',
        'parent_item_colon'  => 'Parent Pax:',
        'update_item'        => 'Update Pax',
        'edit_item'          => 'Edit Pax',
        'add_new_item'       => 'Add New Pax', 
        'new_item_name'      => 'New Pax Name',
        'menu_name'          => 'Pax'
        );
    
    //define arguments to be used 
    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'show_ui'           => true,
        'how_in_nav_menus'  => true,
        'public'            => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'equipment_type')
    );
    
    //call the register_taxonomy function
   // register_taxonomy($taxonomy, $object_type, $args); 
}
add_action('init','add_pax_taxonomy_to_post');


// ============= Custom Post type of Testimonials start =================
if ( ! function_exists('cb_diverace_testimonials') ) {
    // Register Custom Post Type
    function cb_diverace_Testimonials() {
        $labels = array(
            'name'                  => _x( 'Testimonials', 'Post Type General Name', 'diverace' ),
            'singular_name'         => _x( 'Testimonial', 'Post Type Singular Name', 'diverace' ),
            'menu_name'             => __( 'Testimonials', 'diverace' ),
            'name_admin_bar'        => __( 'Order', 'diverace' ),
            'archives'              => __( 'Item Archives', 'diverace' ),
            'attributes'            => __( 'Item Attributes', 'diverace' ),
            'parent_item_colon'     => __( 'Parent Item:', 'diverace' ),
            'all_items'             => __( 'All Items', 'diverace' ),
            'add_new_item'          => __( 'Add New Item', 'diverace' ),
            'add_new'               => __( 'Add New', 'diverace' ),
            'new_item'              => __( 'New Item', 'diverace' ),
            'edit_item'             => __( 'Edit Item', 'diverace' ),
            'update_item'           => __( 'Update Item', 'diverace' ),
            'view_item'             => __( 'View Item', 'diverace' ),
            'view_items'            => __( 'View Items', 'diverace' ),
            'search_items'          => __( 'Search Item', 'diverace' ),
            'not_found'             => __( 'Not found', 'diverace' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'diverace' ),
            'featured_image'        => __( 'Featured Image', 'diverace' ),
            'set_featured_image'    => __( 'Set featured image', 'diverace' ),
            'remove_featured_image' => __( 'Remove featured image', 'diverace' ),
            'use_featured_image'    => __( 'Use as featured image', 'diverace' ),
            'insert_into_item'      => __( 'Insert into item', 'diverace' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'diverace' ),
            'items_list'            => __( 'Items list', 'diverace' ),
            'items_list_navigation' => __( 'Items list navigation', 'diverace' ),
            'filter_items_list'     => __( 'Filter items list', 'diverace' ),
        );
        $args = array(
            'label'                 => __( 'Testimonials', 'diverace' ),
            'description'           => __( 'Testimonials Post Type', 'diverace' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'taxonomies'            => array( ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 30,
            'menu_icon'             => 'dashicons-testimonial',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'Testimonials', $args );
    
    }
    add_action( 'init', 'cb_diverace_testimonials', 0 );

}