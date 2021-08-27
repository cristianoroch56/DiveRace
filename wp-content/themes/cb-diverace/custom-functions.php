<?php
// Add a custom user role
$result = add_role ( 'agent', __ ( 'Agent' ),
        array ( 'read'              => true, // true allows this capability
            'edit_posts'        => true, // Allows user to edit their own posts
            'edit_pages'        => true, // Allows user to edit pages
            'edit_others_posts' => true, // Allows user to edit others posts not just their own
            'create_posts'      => true, // Allows user to create new posts
            'manage_categories' => true, // Allows user to manage post categories
            'publish_posts'     => true, // Allows the user to publish, otherwise posts stays in draft mode
            'edit_themes'       => false, // false denies this capability. User can’t edit your theme
            'install_plugins'   => false, // User cant add new plugins
            'update_plugin'     => false, // User can’t update any plugins
            'update_core'       => false // user cant perform core updates
        ) );

/* ===== remove view_action from custom posttype of "orders" ===== */
add_filter ( 'page_row_actions', 'remove_view_action_custom_posttype_of_orders_actions', 10, 2 );

function remove_view_action_custom_posttype_of_orders_actions($actions, $post) {
    if( $post->post_type == "orders" ) {
        unset ( $actions[ 'view' ] );
    }
    return $actions;
}

/* ===== Orders trash from admin panel ===== */
add_action ( 'wp_trash_post', 'wp_trash_custom_post_type_of_orders' );

function wp_trash_custom_post_type_of_orders($post_id) {
    $post_type   = get_post_type ( $post_id );
    $post_status = get_post_status ( $post_id );
    if( $post_type == 'orders' && in_array ( $post_status, array ( 'publish', 'draft', 'future' ) ) ) {
        global $wpdb;
        $order_table_name   = $wpdb->prefix . 'custom_order_details';
        $order_trash_status = 'Yes';
        $wpdb->query ( $wpdb->prepare ( "UPDATE $order_table_name SET order_trash='$order_trash_status' WHERE order_id=$post_id" ) );
    }
}

/* ===== Orders permanently delete from admin panel ===== */
add_action ( 'before_delete_post', 'wp_permanently_delete_custom_post_type_of_orders' );

function wp_permanently_delete_custom_post_type_of_orders($post_id) {

    // We check if the global post type isn't ours and just return
    global $post_type;
    $post_type   = get_post_type ( $post_id );
    $post_status = get_post_status ( $post_id );
    if( $post_type == 'orders' && in_array ( $post_status, array ( 'trash' ) ) ) {
        global $wpdb;
        $order_table_name = $wpdb->prefix . 'custom_order_details';
        $wpdb->query ( $wpdb->prepare ( "DELETE FROM $order_table_name WHERE order_id=$post_id" ) );
    }
}

/* ===== Orders untrash from bin in admin panel===== */
add_action ( 'untrash_post', 'wp_untrash_custom_post_type_of_orders' );

function wp_untrash_custom_post_type_of_orders($post_id) {
    $post_type   = get_post_type ( $post_id );
    $post_status = get_post_status ( $post_id );
    if( $post_type == 'orders' && in_array ( $post_status, array ( 'trash' ) ) ) {
        global $wpdb;
        $order_table_name   = $wpdb->prefix . 'custom_order_details';
        $order_trash_status = 'No';
        $wpdb->query ( $wpdb->prepare ( "UPDATE $order_table_name SET order_trash='$order_trash_status' WHERE order_id=$post_id" ) );
    }
}

/* ===== Add the custom columns to the "orders" post type ===== */
add_filter ( 'manage_orders_posts_columns', 'set_custom_edit_orders_columns' );

function set_custom_edit_orders_columns($columns) {
    //unset( $columns['author'] );
    $columns[ 'payble_amount' ] = __ ( 'Total Amount' );
    $columns[ 'booking_date' ]  = __ ( 'Booking Date' );
    $columns[ 'agent_code' ]    = __ ( 'Agent Code' );
    $columns[ 'order_status' ]  = __ ( 'Order Status' );
    return $columns;
}

/* ===== Add the data to the custom columns for the "orders" post type ===== */
add_action ( 'manage_orders_posts_custom_column', 'custom_orders_column', 10, 2 );

function custom_orders_column($column, $post_id) {
    switch ($column) {

        case 'payble_amount' :
            $payble_amount = get_field ( 'payble_amount', $post_id );
            if( is_string ( $payble_amount ) )
                echo $payble_amount;
            else
                _e ( '' );
            break;

        case 'booking_date' :
            echo get_the_date ( "Y-m-d H:i:s", $post_id );
            break;

        case 'agent_code' :
            $agent_code = get_field ( 'agent_data_agent_code', $post_id );
            if( is_string ( $agent_code ) )
                echo $agent_code;
            else
                _e ( '' );
            break;

        case 'order_status' :
            $order_status = get_field ( 'order_status', $post_id );
            if( is_string ( $order_status ) )
                echo $order_status;
            else
                _e ( '' );
            break;
    }
}

/* ===== Add the custom columns to the "Invoice" post type ===== */
add_filter ( 'manage_invoice_posts_columns', 'set_custom_edit_invoice_columns' );

function set_custom_edit_invoice_columns($columns) {
    //unset( $columns['author'] );
    $columns[ 'inv_order_id' ]   = __ ( 'Order Id' );
    $columns[ 'booking_date' ]   = __ ( 'Booking Date' );
    $columns[ 'inv_agent_name' ] = __ ( 'Agent Code' );
    $columns[ 'inv_coupon' ]     = __ ( 'Coupon Code' );

    return $columns;
}

/* ===== Add the data to the custom columns for the "orders" post type ===== */
add_action ( 'manage_invoice_posts_custom_column', 'custom_invoice_column', 10, 2 );

function custom_invoice_column($column, $post_id) {
    switch ($column) {

        case 'inv_order_id' :
            $order_id = get_field ( 'inv_order_id', $post_id );
            if( is_string ( $order_id ) )
                echo $order_id;
            else
                _e ( '' );
            break;

        case 'booking_date' :
            echo get_the_date ( "Y-m-d H:i:s", $post_id );
            break;

        case 'inv_agent_name' :
            $agent_name = get_field ( 'inv_agent_name', $post_id );
            if( is_string ( $agent_name ) )
                echo $agent_name;
            else
                _e ( '' );
            break;

        case 'inv_coupon' :
            $coupon = get_field ( 'inv_coupon', $post_id );
            if( is_string ( $coupon ) )
                echo $coupon;
            else
                _e ( '' );
            break;
    }
}

/* ===== Add the custom columns to the "agent_code" post type ===== */
add_filter ( 'manage_agent_code_posts_columns', 'set_custom_edit_agent_code_columns' );

function set_custom_edit_agent_code_columns($columns) {
    //unset( $columns['author'] );
    $columns[ 'agnet_name' ]                = __ ( 'Agnet Name' );
    $columns[ 'agent_code' ]                = __ ( 'Agent Code' );
    $columns[ 'agent_discount_percentage' ] = __ ( 'Agent Discount ' );
    $columns[ 'agent_status' ]              = __ ( 'Agent Status' );
    return $columns;
}

/* ===== Add the data to the custom columns for the "agent_code" post type ===== */
add_action ( 'manage_agent_code_posts_custom_column', 'custom_agent_code_column', 10, 2 );

function custom_agent_code_column($column, $post_id) {
    switch ($column) {

        case 'agnet_name' :
            $agnet_id  = get_field ( 'agnet_name', $post_id );
            $user_info = get_userdata ( $agnet_id );
            $username  = $user_info->user_login;

            if( ! empty ( $agnet_id ) )
                echo $username;
            else
                _e ( '' );
            break;

        case 'agent_code' :
            $agent_code = get_field ( 'agent_code', $post_id );
            if( is_string ( $agent_code ) )
                echo $agent_code;
            else
                _e ( '' );
            break;

        case 'agent_discount_percentage' :
            $agent_discount = get_field ( 'agent_discount_percentage', $post_id );
            if( is_string ( $agent_discount ) )
                echo $agent_discount;
            else
                _e ( '' );
            break;

        case 'agent_status' :
            $agent_status = get_field ( 'agent_status', $post_id );
            if( $agent_status == 1 )
                echo "Enable";
            else
                _e ( 'Disable' );
            break;
    }
}

/* add_action( 'trash_post', 'my_func' );
  function my_func( $postid ){
  echo "post_id__".$postid;
  // We check if the global post type isn't ours and just return
  global $post_type;
  if ( $post_type != 'agent_code' ) return;

  // My custom stuff for deleting my custom post type here
  } */

/* add_filter( 'page_row_actions', 'remove_row_actions_post', 10, 2 );
  function remove_row_actions_post( $actions, $post ) {
  if ($post->post_type =="orders"){
  unset( $actions['view'] );
  }
  return $actions;
  } */

//   MAKE ACF FILED unique code not work
//https://support.advancedcustomfields.com/forums/topic/accept-only-unique-values/

/* add_filter('acf/load_field/name=hidden_post_id', 'make_hidden_post_id_readonly');
  function make_field_readonly($field) {
  // sets readonly attribute for field
  $field['readonly'] = 1;
  return field;
  }

  add_filter('acf/load_value/name=hidden_post_id', 'set_hidden_post_id_value'), 10, 3);
  function set_hidden_post_id_value($value, $post_id, $field) {
  // set the value of the field to the current post id
  return $post_id;
  }

  add_action('admin_head', 'hide_hidden_post_id');
  function hide_hidden_post_id() {
  // the field key for the post id field
  ?>
  <style type="text/css">
  div[data-key="field_5f6adad264b82"] {
  display: none;
  }
  </style>
  <?php
  }

  add_filter('acf/validate_value/name=agent_code', 'require_unique', 10, 4);
  function require_unique($valid, $value, $field, $input) {
  if (!$valid) {
  return $valid;
  }
  // get the post id
  // using field key of post id field
  $post_id = $_POST['acf']['field_5f6adad264b82'];
  // query existing posts for matching value
  $args = array(
  'post_type' => 'agent_code',
  'posts_per_page' = 1, // only need to see if there is 1
  'post_status' => 'publish, draft, trash',
  // don't include the current post
  'post__not_in' => array($post_id),
  'meta_query' => array(
  array(
  'key' => $field['agent_code'],
  'value' => $value
  )
  )
  );
  $query = new WP_Query($args);
  if (count($query->posts)){
  $valid = 'This Value is not Unique';
  }
  return $valid;
  } */

function orders_post_types_admin_reorder($wp_query) {
    if( is_admin () ) {
        // Get the post type from the query
        $post_type = $wp_query->query[ 'post_type' ];
        if( $post_type == 'orders' ) {
            $wp_query->set ( 'orderby', 'date' );
            $wp_query->set ( 'order', 'DESC' );
        }
    }
}

add_filter ( 'pre_get_posts', 'orders_post_types_admin_reorder' );

// --------- Start To register your Google API key, please use one of the following methods. ---------
// Method 1: Filter.
function diverace_acf_google_map_api($api) {
    $api[ 'key' ] = 'AIzaSyDJbqVkRb6llQczHvPN_WnK4pGUGt30ykM';
    return $api;
}

add_filter ( 'acf/fields/google_map/api', 'diverace_acf_google_map_api' );

// Method 2: Setting.
function diverace_acf_init() {
    acf_update_setting ( 'google_api_key', 'AIzaSyDJbqVkRb6llQczHvPN_WnK4pGUGt30ykM' );
}

add_action ( 'acf/init', 'diverace_acf_init' );

// --------- End To register your Google API key, please use one of the following methods. ---------

/* ===== After user login redirect based on user role - dashboard-code */
function redirect_user_on_role() {
    global $current_user;
    get_currentuserinfo ();
    $user_role = $current_user->roles;
    //If login user role is Subscriber
    if( $current_user->user_level == 0 ) {
        wp_redirect ( home_url ( 'dashboard-index' ) );
        exit;
    }elseif( $user_role == 'agent' ) {
        wp_redirect ( home_url ( 'dashboard-index' ) );
        exit;
    }
}

add_action ( 'admin_init', 'redirect_user_on_role' );

/* ===== Add Strip API Key */

/* function diverace_strip_api_init() {
  require_once ABSPATH . WPINC . '/load.php';

  define('STRIP_PATH', 'wp-content/themes/cb-diverace/stripe-php-master/');

  $strip_init_file =  'init.php';
  $strip_lib_file =  '/lib/Stripe.php';
  require_once ABSPATH . STRIP_PATH . $strip_init_file ;
  require_once ABSPATH . STRIP_PATH . $strip_lib_file ;
  }
  add_action('init', 'diverace_strip_api_init'); */

// Post type orders add extra filter based on order status callback query function
add_filter ( 'parse_query', 'prefix_parse_filter' );

function prefix_parse_filter($query) {
    global $pagenow;
    $current_page = isset ( $_GET[ 'post_type' ] ) ? $_GET[ 'post_type' ] : '';

    if( is_admin () &&
            'orders' == $current_page &&
            'edit.php' == $pagenow &&
            isset ( $_GET[ 'status' ] ) &&
            $_GET[ 'status' ] != '' ) {

        $order_status                      = $_GET[ 'status' ];
        $query->query_vars[ 'meta_key' ]     = 'order_status';
        $query->query_vars[ 'meta_value' ]   = $order_status;
        $query->query_vars[ 'meta_compare' ] = '=';
    }
}

// Add extra dropdowns on post type Orders add extra filter based on order status
add_action ( 'restrict_manage_posts', 'add_extra_tablenav' );

function add_extra_tablenav($post_type) {
    global $wpdb;

    // Ensure this is the correct Post Type
    if( $post_type !== 'orders' )
        return;

    // Grab the results from the DB
    $query   = $wpdb->prepare ( '
        SELECT DISTINCT pm.meta_value FROM %1$s pm
        LEFT JOIN %2$s p ON p.ID = pm.post_id
        WHERE pm.meta_key = "%3$s" 
        AND p.post_status = "%4$s" 
        AND p.post_type = "%5$s"
        ORDER BY "%6$s"',
            $wpdb->postmeta,
            $wpdb->posts,
            'order_status', // Your meta key - change as required
            'publish', // Post status - change as required
            $post_type,
            'order_status'
    );
    $results = $wpdb->get_col ( $query );

    // Ensure there are options to show 
    if( empty ( $results ) )
        return;

    // get selected option if there is one selected
    if( isset ( $_GET[ 'order_status' ] ) && $_GET[ 'order_status' ] != '' ) {
        $selectedName = $_GET[ 'order_status' ];
    } else {
        $selectedName = -1;
    }

    // Grab all of the options that should be shown 
    $options[] = sprintf ( '<option value="-1">%1$s</option>', __ ( 'All Order Status', 'your-text-domain' ) );
    foreach ( $results as $result ) :
        if( $result == $selectedName ) {
            $options[] = sprintf ( '<option value="%1$s" selected>%2$s</option>', esc_attr ( $result ), $result );
        } else {
            $options[] = sprintf ( '<option value="%1$s">%2$s</option>', esc_attr ( $result ), $result );
        }
    endforeach;

    // Output the dropdown menu 
    echo '<select class="" id="order_status" name="status">';
    echo join ( "\n", $options );
    echo '</select>';
}

gravity_form_enqueue_scripts ( 4, true );

// Testimonials submit to save on custom posty type of testimonials
add_action ( 'gform_after_submission_3', 'set_post_content_after_submission', 10, 2 );

function set_post_content_after_submission($entry, $form) {
    global $wpdb;
    $user_id                  = get_current_user_id ();
    $testimonials_to_order_id = $_POST[ 'testimonials_to_order_id' ];
    $already_credited         = get_post_meta ( $testimonials_to_order_id, 'testimonial_user_credit', true );

    if( empty ( $already_credited ) || $already_credited != 'yes' ) {
        $already_credited;
        //getting post
        $post     = get_post ( $entry[ 'post_id' ] );
        /* echo '<pre>';
          print_r($post); */
        $randomid = rand ( 1000, 100000 );

        $post_id                  = $post->ID;
        $testi_ttle               = 'Testimonial-' . $user_id . '-' . $testimonials_to_order_id;
        $testi_content            = $_POST[ 'input_5' ];
        $testimonials_to_order_id = $_POST[ 'testimonials_to_order_id' ];

        //inserting post data
        $last_post_id = wp_insert_post ( array (
            'post_title'   => $testi_ttle,
            'post_type'    => 'testimonials', // post type is = testimonials
            'post_content' => $testi_content,
            'post_status'  => 'draft',
                ) );

        //echo $last_post_id; exit;
        $key         = 'user_credit';
        $single      = true;
        $user_credit = get_user_meta ( $user_id, $key, $single );
        if( empty ( $user_credit ) ) {
            $user_credit = get_user_meta ( $user_id, $key, $single );
            $user_credit = (int) $user_credit + (int) (100);
        } else {
            $user_credit = (int) $user_credit + (int) (100);
        }

        update_post_meta ( $testimonials_to_order_id, 'testimonial_user_credit', 'yes' );
        update_user_meta ( $user_id, 'user_credit', $user_credit );
        update_post_meta ( $last_post_id, 'testimonial_user_id', $user_id );
    } else {
        $already_credited = get_user_meta ( $user_id, 'testimonial_user_credit', true );
    }
}

// ======== Start Below function is used to admin add cabin for single order. 21-05-2021 ========
add_action ( 'save_post', 'custom_admin_save_order_cabins' );

function custom_admin_save_order_cabins($post_id) {
    global $wpdb;

    if( ! is_admin () )
        return false;

    if( $_REQUEST[ 'post_type' ] != 'orders' )
        return false;

    if( $_REQUEST[ 'post_ID' ] != '' ) {
        $order_id = $_REQUEST[ 'post_ID' ];
        $pax_data = $_POST[ 'acf' ][ 'field_5f6c38d56ff4c' ];
        foreach ( $pax_data as $pax_key => $pax_value ) {
            foreach ( $pax_value as $cabin_key => $cabin_value ) {
                if( $cabin_key == 'field_604f550c45451' ) {
                    $pax_cabin_data_key = 'pax_data_' . str_replace ( 'row-', '', $pax_key ) . '_pax_cabin_id';
                    $cabins[]           = $cabin_value[ 0 ];
                    update_post_meta ( $order_id, $pax_cabin_data_key, $cabin_value[ 0 ] );
                }
            }
        }

        $all_cabins       = implode ( ',', $cabins );
        // Cabins store in custom order table.
        $order_table_name = $wpdb->prefix . 'custom_order_details';
        $wpdb->update (
                $order_table_name,
                array (
                    'cabin_id' => $all_cabins
                ),
                array (
                    'order_id' => $order_id,
                )
        );
    }
}

// ======== End Below function is used to admin add cabin for single order. 21-05-2021 ========

/* ======= Below function is used to generate user_referral_code =======
 * https://diverace.chillybin.biz/?add_user_referral_code=1
 */
add_action ( 'parse_request', 'my_custom_url_handler' );

function my_custom_url_handler() {
    if( $_GET[ "add_user_referral_code" ] == '1' ) {

        $wp_user_query = new WP_User_Query ( array ( 'role' => 'Subscriber' ) );
        $users         = $wp_user_query->get_results ();

        // Check all users results
        if( ! empty ( $users ) ) {
            //echo "Total_".count($users);
            // loop trough each author
            foreach ( $users as $user ) {
                //echo "uid_".$user->ID;
                $bytes                = random_bytes ( 4 );
                $random_referral_code = bin2hex ( $bytes );
                //add_user_meta( $user->id, 'user_referral_code', $random_referral_code, true );
                update_user_meta ( $user->id, 'user_referral_code', $random_referral_code );
                //$user_referral_code = get_user_meta( $user->id, 'user_referral_code', true );                 
            }
        }
    }
}
