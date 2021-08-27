<?php
/*
  Plugin Name: Custom API
  Description: Create custom custom API plugin made by WappNet
  Version: 1
  Author: https://wappnet.com/
  Author URI: https://wappnet.com/
 */

// ============== Start Add Strip API Data ============= //
require_once ABSPATH . WPINC . '/load.php';
define ( 'STRIP_PATH_API', 'wp-content/themes/cb-diverace/stripe-php-master/' );
$strip_init_file = 'init.php';
$strip_lib_file  = '/lib/Stripe.php';
require_once ABSPATH . STRIP_PATH_API . $strip_init_file;
require_once ABSPATH . STRIP_PATH_API . $strip_lib_file;
// ============== Start Add Strip API Data ============= //

global $wpdb;
include 'orderplace-email.php';
include 'orderupdated-email.php';
include 'send-register-mailto-user.php';
include 'send-register-mailto-admin.php';
include 'send-forgot-password-mailto-user.php';
include 'add-waiting-list-email.php';
include 'invoice-email.php';

function create_custom_booking_function() {

    global $wpdb;
    $table_name      = $wpdb->prefix . "custom_order_details";
    global $charset_collate;
    $charset_collate = $wpdb->get_charset_collate ();
    global $db_version;
    global $create_sql;
    if( $wpdb->get_var ( "SHOW TABLES LIKE '" . $table_name . "'" ) != $table_name ) {
        $create_sql = "CREATE TABLE $table_name (
               id int(11) NOT NULL AUTO_INCREMENT,
        user_id int(11) ,
        cabin_id int(11) ,
        tripDate_id int(11) ,
        cabin_type varchar(250) ,
        cabin_seat varchar(250) ,
        order_date datetime ,           
        order_id int(11) ,
        status varchar(250) NOT NULL,
        order_trash varchar(250) NOT NULL DEFAULT 'No',
        coupon_id int(11) ,
        agent_id int(11) ,
        coupon_code varchar(250) ,
        agent_code varchar(250) ,
        PRIMARY KEY (id))";
    }

    require_once(ABSPATH . "wp-admin/includes/upgrade.php");
    dbDelta ( $create_sql );
}

add_action ( 'init', 'create_custom_booking_function' );

header ( 'Access-Control-Allow-Headers: *' );
header ( 'Access-Control-Allow-Origin: *' );
header ( 'Access-Control-Allow-Methods: *' );
header ( 'Access-Control-Allow-Credentials: true' );
//header( 'Access-Control-Allow-Origin: ' . esc_url_raw( site_url() ) );
/* function add_cors_http_header(){
  header( 'Access-Control-Allow-Headers: *');
  header( 'Access-Control-Allow-Origin: *' );
  header( 'Access-Control-Allow-Methods: *' );
  header( 'Access-Control-Allow-Credentials: true' );
  }
  add_action('init','add_cors_http_header'); */


add_action ( 'rest_api_init', function () {
    register_rest_route ( 'custom-api/v1', 'wp_login_api', [
        'methods'  => 'POST',
        'callback' => 'wp_login_api',
    ] );
    register_rest_route ( 'custom-api/v1', 'wp_singup_api', [
        'methods'  => 'POST',
        'callback' => 'wp_singup_api',
    ] );
    register_rest_route ( 'custom-api/v1', 'forgot_password', [
        'methods'  => 'POST',
        'callback' => 'forgot_password',
    ] );
    register_rest_route ( 'custom-api/v1', 'forgot_password', [
        'methods'  => 'POST',
        'callback' => 'forgot_password',
    ] );
    register_rest_route ( 'custom-api/v1', 'get_user_data', [
        'methods'  => 'POST',
        'callback' => 'get_user_data',
    ] );
    register_rest_route ( 'custom-api/v1', 'update_user_profile', [
        'methods'  => 'POST',
        'callback' => 'update_user_profile',
    ] );
    register_rest_route ( 'custom-api/v1', 'update_user_password', [
        'methods'  => 'POST',
        'callback' => 'update_user_password',
    ] );
    register_rest_route ( 'custom-api/v1', 'get_vessel_data', [
        'methods'  => 'GET',
        'callback' => 'get_vessel_data',
    ] );
    register_rest_route ( 'custom-api/v1', 'get_country_data', [
        'methods'  => 'POST',
        'callback' => 'get_country_data',
    ] );

    register_rest_route ( 'custom-api/v1', 'get_itinerary_data_from_vessel_data', [
        'methods'  => 'POST',
        'callback' => 'get_itinerary_data_from_vessel_data',
    ] );
    register_rest_route ( 'custom-api/v1', 'get_destination_data', [
        'methods'  => 'POST',
        'callback' => 'get_destination_data',
    ] );
    register_rest_route ( 'custom-api/v1', 'get_itinerary_data', [
        'methods'  => 'POST',
        'callback' => 'get_itinerary_data',
    ] );

    register_rest_route ( 'custom-api/v1', 'get_itinerary_areas_from_country_data', [
        'methods'  => 'POST',
        'callback' => 'get_itinerary_areas_from_country_data',
    ] );
    register_rest_route ( 'custom-api/v1', 'get_date_from_itinerary_data', [
        'methods'  => 'POST',
        'callback' => 'get_date_from_itinerary_data',
    ] );
    register_rest_route ( 'custom-api/v1', 'get_itinerary_schedule_data', [
        'methods'  => 'POST',
        'callback' => 'get_itinerary_schedule_data',
    ] );
    register_rest_route ( 'custom-api/v1', 'get_pax_data', [
        'methods'  => 'GET',
        'callback' => 'get_pax_data',
    ] );
    register_rest_route ( 'custom-api/v1', 'get_cabins_from_vessel_id_data', [
        'methods'  => 'POST',
        'callback' => 'get_cabins_from_vessel_id_data',
    ] );
    register_rest_route ( 'custom-api/v1', 'get_courses_data', [
        'methods'  => 'GET',
        'callback' => 'get_courses_data',
    ] );
    register_rest_route ( 'custom-api/v1', 'get_rental_equipment_data', [
        'methods'  => 'GET',
        'callback' => 'get_rental_equipment_data',
    ] );
    // get_coupon_data & get_agent_data code merge
    register_rest_route ( 'custom-api/v1', 'get_coupon_data', [
        'methods'  => 'POST',
        'callback' => 'get_coupon_data',
    ] );
    /* register_rest_route('custom-api/v1', 'get_agent_data',[
      'methods' => 'POST',
      'callback' => 'get_agent_data',
      ]); */

    register_rest_route ( 'custom-api/v1', 'add_order_data', [
        'methods'  => 'POST',
        'callback' => 'add_order_data',
    ] );
    register_rest_route ( 'custom-api/v1', 'add_waiting_list', [
        'methods'  => 'POST',
        'callback' => 'add_waiting_list',
    ] );
    register_rest_route ( 'custom-api/v1', 'edit_courses_data_from_order_id', [
        'methods'  => 'POST',
        'callback' => 'edit_courses_data_from_order_id',
    ] );
    register_rest_route ( 'custom-api/v1', 'edit_rental_equipment_data_from_order_id', [
        'methods'  => 'POST',
        'callback' => 'edit_rental_equipment_data_from_order_id',
    ] );
    register_rest_route ( 'custom-api/v1', 'order_summery_data_from_order_id', [
        'methods'  => 'POST',
        'callback' => 'order_summery_data_from_order_id',
    ] );
    register_rest_route ( 'custom-api/v1', 'view_order_data_from_user_id', [
        'methods'  => 'POST',
        'callback' => 'view_order_data_from_user_id',
    ] );
    register_rest_route ( 'custom-api/v1', 'save_order_data_from_user_id', [
        'methods'  => 'POST',
        'callback' => 'save_order_data_from_user_id',
    ] );
    register_rest_route ( 'custom-api/v1', 'send_email_on_order_placed', [
        'methods'  => 'POST',
        'callback' => 'send_email_on_order_placed',
    ] );
    register_rest_route ( 'custom-api/v1', 'wp_userbalance_api', [
        'methods'  => 'POST',
        'callback' => 'wp_userbalance_api',
    ] );
    register_rest_route ( 'custom-api/v1', 'order_cancle_from_user_id', [
        'methods'  => 'POST',
        'callback' => 'order_cancle_from_user_id',
    ] );
    register_rest_route ( 'custom-api/v1', 'get_all_pending_payment_orders_of_10_percent_pay_send_to_zapier', [
        'methods'  => 'GET',
        'callback' => 'get_all_pending_payment_orders_of_10_percent_pay_send_to_zapier',
    ] );
    register_rest_route ( 'custom-api/v1', 'get_all_pending_payment_orders_of_50_percent_pay_send_to_zapier', [
        'methods'  => 'GET',
        'callback' => 'get_all_pending_payment_orders_of_50_percent_pay_send_to_zapier',
    ] );
    register_rest_route ( 'custom-api/v1', 'get_all_complete_payment_orders_send_to_zapier', [
        'methods'  => 'GET',
        'callback' => 'get_all_complete_payment_orders_send_to_zapier',
    ] );
    register_rest_route ( 'custom-api/v1', 'get_all_orders_to_trip_is_completed_send_to_zapier', [
        'methods'  => 'GET',
        'callback' => 'get_all_orders_to_trip_is_completed_send_to_zapier',
    ] );

    // below ass strip apis
    register_rest_route ( 'custom-api/v1', 'strip_add_card_api', [
        'methods'  => 'POST',
        'callback' => 'strip_add_card_api',
    ] );
    register_rest_route ( 'custom-api/v1', 'strip_card_listing_api', [
        'methods'  => 'POST',
        'callback' => 'strip_card_listing_api',
    ] );
    register_rest_route ( 'custom-api/v1', 'strip_card_remove_api', [
        'methods'  => 'POST',
        'callback' => 'strip_card_remove_api',
    ] );
} );

//Login API
function wp_login_api($request) {

    $response   = array ();
    $parameters = $request->get_params ();

    $username = sanitize_text_field ( $parameters[ 'username' ] );
    $password = sanitize_text_field ( $parameters[ 'password' ] );

    // Error Handling.
    $error = new WP_Error();
    if( empty ( $username ) ) {

        $response[ 'status' ]  = false;
        $response[ 'data' ]    = [];
        $response[ 'message' ] = 'Username field is required';

        return $response;
    }
    if( empty ( $password ) ) {

        $response[ 'status' ]  = false;
        $response[ 'data' ]    = [];
        $response[ 'message' ] = 'Password field is required';

        return $response;
    }

    $user = wp_authenticate ( $username, $password );

    // If user found
    if( ! is_wp_error ( $user ) ) {
        $response[ 'status' ] = true;

        //$response['user'] = $user;
        $user_id = $user->ID;

        $key         = 'user_credit';
        $single      = true;
        $user_credit = get_user_meta ( $user_id, $key, $single );

        $user_phone_number  = get_user_meta ( $user_id, 'user_phone_number', true );
        $user_age           = get_user_meta ( $user_id, 'user_age', true );
        $stripe_customer_id = get_the_author_meta ( 'stripe_customer_id', $user_id );

        if( $user_credit != "" ) {
            $user_credit = get_user_meta ( $user_id, $key, $single );
        } else {
            $user_credit = 0;
        }

        if( $user_phone_number != "" ) {
            $user_phone_number = get_user_meta ( $user_id, 'user_phone_number', true );
        } else {
            $user_phone_number = '';
        }

        if( $user_age != "" ) {
            $user_age = get_user_meta ( $user_id, 'user_age', true );
        } else {
            $user_age = '';
        }

        if( $stripe_customer_id != "" ) {
            $stripe_customer_id = get_the_author_meta ( 'stripe_customer_id', $user_id );
            //$user_age = get_user_meta($user_id, 'stripe_customer_id',true); 
        } else {
            $stripe_customer_id = '';
        }

        $user->data->user_credit        = (int) $user_credit;
        $user->data->user_phone_number  = $user_phone_number;
        $user->data->user_age           = $user_age;
        $user->data->stripe_customer_id = $stripe_customer_id;
        //$user->data['stripe_customer_id'] = $stripe_customer_id ;
        $response[ 'user' ]             = $user;
        /* echo '<pre>';
          print_r($user); */
        /* echo "<pre>";
          print_r($user->data->user_login);
          die(); */
    } else {
        // If user not found
        $response[ 'status' ]  = false;
        $response[ 'data' ]    = [];
        $response[ 'message' ] = 'User not found. Check credentials';
    }


    unset ( $response[ 'user' ]->data->user_pass );
    unset ( $response[ 'user' ]->data->user_activation_key );

    return $response;
}

// Get user credit balance 

function wp_userbalance_api($request) {

    $response   = array ();
    $parameters = $request->get_params ();

    $user_id = sanitize_text_field ( $parameters[ 'user_id' ] );
    if( $user_id ) {


        $key         = 'user_credit';
        $single      = true;
        $user_credit = get_user_meta ( $user_id, $key, $single );
        if( $user_credit != "" ) {
            $user_credit = get_user_meta ( $user_id, $key, $single );
        } else {
            $user_credit = 0;
        }
        $response[ 'status' ]      = true;
        $response[ 'user_credit' ] = $user_credit;
        return $response;
    }
}

// wp_singup_api
function wp_singup_api($request) {

    $response   = array ();
    $parameters = $request->get_params ();

    $firstname = sanitize_text_field ( $parameters[ 'firstname' ] );
    $lastname  = sanitize_text_field ( $parameters[ 'lastname' ] );
    /* $username = sanitize_text_field( $parameters['username'] ); */

    $lower_str    = strtolower ( $firstname );
    $new_username = str_replace ( ' ', '', $lower_str );

    $email            = sanitize_text_field ( $parameters[ 'email' ] );
    $password         = sanitize_text_field ( $parameters[ 'password' ] );
    $confirm_password = sanitize_text_field ( $parameters[ 'confirm_password' ] );
    //$role = 'subscriber';
    //$role = sanitize_text_field($parameters['role']);	
    $role             = "subscriber";
    // Error Handling.
    $error            = new WP_Error();
    if( empty ( $firstname ) ) {
        $response[ 'status' ]         = false;
        $response[ 'data' ][ 'data' ] = [];
        $response[ 'message' ]        = 'First Name field is required';

        return $response;
    }
    if( empty ( $lastname ) ) {
        $response[ 'status' ]  = false;
        $response[ 'data' ]    = [];
        $response[ 'message' ] = 'Last Name field is required';

        return $response;
    }
    /* if ( empty( $username ) ) {
      $response['status'] = false;
      $response['data'] = [];
      $response['message'] = 'Username field is required';

      return $response;
      } */
    $error = new WP_Error();
    if( empty ( $email ) ) {
        $response[ 'status' ]  = false;
        $response[ 'data' ]    = [];
        $response[ 'message' ] = 'Email field is required';

        return $response;
    }
    if( empty ( $password ) ) {
        $response[ 'status' ]  = false;
        $response[ 'data' ]    = [];
        $response[ 'message' ] = 'Password field is required';
        return $response;
    }
    if( empty ( $confirm_password ) ) {
        $response[ 'status' ]  = false;
        $response[ 'data' ]    = [];
        $response[ 'message' ] = 'Confirm Password field is required';
        return $response;
    }

    if( $password != $confirm_password ) {
        $response[ 'status' ]  = false;
        $response[ 'data' ]    = [];
        $response[ 'message' ] = 'Your password and confirmation password do not match.';
        return $response;
    }

    if( empty ( $role ) ) {
        $response[ 'status' ]  = false;
        $response[ 'data' ]    = [];
        $response[ 'message' ] = 'Role field is required';
        return $response;
    } else {
        if( $GLOBALS[ 'wp_roles' ]->is_role ( $role ) ) {
            if( $role == 'administrator' || $role == 'editor' || $role == 'author' ) {
                //$error->add(406, __("Role field 'role' is not a permitted. Only 'contributor', 'subscriber' and your custom roles are allowed.", 'wp_rest_user'), array('status' => 400));
                //return $error;

                $response[ 'status' ]  = false;
                $response[ 'data' ]    = [];
                $response[ 'message' ] = 'Role field "role" is not a permitted. Only "contributor", "subscriber" and your custom roles are allowed.';
                return $response;
            }
        } else {
            //$error->add(405, __("Role field 'role' is not a valid. Check your User Roles from Dashboard.", 'wp_rest_user'), array('status' => 400));
            //return $error;
            $response[ 'status' ]  = false;
            $response[ 'data' ]    = [];
            $response[ 'message' ] = "Role field 'role' is not a valid. Check your User Roles from Dashboard.";
            return $response;
        }
    }

    $user_id = username_exists ( $new_username );
    //$user_id = email_exists($email);
    /* if(!$user_name_exists){
      $response['status'] = false;
      $response['data'] = [];
      $response['message'] = __("Username is already exists, please enter another firstname", "wp-rest-user");
      return $response;
      }
     */ if( ! $user_id && email_exists ( $email ) == false ) {
        $user_id = wp_create_user ( $new_username, $password, $email );
        if( ! is_wp_error ( $user_id ) ) {
            // Ger User Meta Data (Sensitive, Password included. DO NOT pass to front end.)
            $user                 = get_user_by ( 'id', $user_id );
            $user->set_role ( $role );
            $response[ 'status' ] = true;

            $user_id     = $user->ID;
            $key         = 'user_credit';
            $single      = true;
            $user_credit = get_user_meta ( $user_id, $key, $single );

            wp_update_user ( [
                'ID'         => $user_id, // this is the ID of the user you want to update.
                'first_name' => $firstname,
                'last_name'  => $lastname,
            ] );
            if( $user_credit != "" ) {
                $user_credit = get_user_meta ( $user_id, $key, $single );
            } else {
                $user_credit = 0;
            }
            $user->data->user_credit = (int) $user_credit;
            $response[ 'status' ]    = true;
            $response[ 'user' ]      = $user;

            $blogusers = get_user_by ( 'id', 1 );
            foreach ( $blogusers as $user ) {
                $admin_email = $user->user_email;
                $user_login  = $user->user_login;
            }
            // Admin email notification
            //send_register_mailto_admin(1,$username,$email);
            /* $subjects = 'New user registred.';
              $message_body_admin .= 'Hello '.$user_login.',<br/><br/>';
              $message_body_admin .= '<b>TNew user registred on site.</b>';
              $message_body_admin .= '<b>Username: </b>'.$username;
              $message_body_admin .= '<br/><b>Email: </b>'.$email;
              $header = array('Content-Type: text/html; charset=UTF-8');
              $admin_email_successful = wp_mail($admin_email, $subjects ,$message_body_admin, $header );
              if ($admin_email_successful) {
              $response['admin_email'] = true;
              } else {
              $response['admin_email'] = false;
              $response['admin_email_message'] = __("Failed to send email. Check your WordPress Hosting Email Settings.", "wp-rest-user");
              } */


            //user email
            /* $subject = 'Registred Successfully';
              $message_body .= 'Hello '.$firstname.',<br/><br/>';
              $message_body .= '<b>Thank you for joining DiveRACE.</b>';
              $message_body .= '<b>Username: </b>'.$username;
              $headers = array('Content-Type: text/html; charset=UTF-8');
              $email_successful = wp_mail($email, $subject ,$message_body, $headers );

              if ($email_successful) {
              $response['user_email'] = true;
              } else {
              $response['user_email'] = false;
              $response['user_email_message'] = __("Failed to send email. Check your WordPress Hosting Email Settings.", "wp-rest-user");
              } */

            //user email
            send_register_mailto_user ( $user_id );
        } else {
            return $user_id;
        }
    } else if( $user_id ) {

        //$error->add(406, __("Username already exists, please enter another username", 'wp-rest-user'), array('status' => 400));
        //return $error;
        $response[ 'status' ]  = false;
        $response[ 'data' ]    = [];
        $response[ 'message' ] = __ ( "Firstname already exists, please enter another firstname", "wp-rest-user" );
        return $response;
    } else {
        /* $error->add(406, __("Email already exists, please try 'Reset Password'", 'wp-rest-user'), array('status' => 400));
          return $error; */

        $response[ 'status' ]  = false;
        $response[ 'data' ]    = [];
        $response[ 'message' ] = __ ( "Username and Email Id already exists.", "wp-rest-user" );
        return $response;
    }

    unset ( $response[ 'user' ]->data->user_pass );
    unset ( $response[ 'user' ]->data->user_activation_key );
    return $response;
}

/**
  // ForgotPassword
 */
function forgot_password($request) {

    $response   = array ();
    $parameters = $request->get_params ();
    $useremail  = sanitize_text_field ( $parameters[ 'useremail' ] );
    $user       = get_user_by ( 'email', $useremail );
    $user_ID    = $user->ID;
    $error      = new WP_Error();
    $data       = [];

    if( ! empty ( $user_ID ) ) {
        //echo $useremail;
        //send_forgote_password_mailto_user($useremail);
        $key     = get_password_reset_key ( $user );
        $rp_link = '<a href="' . site_url () . "/wp-login.php?action=rp&key=$key&login=" . rawurlencode ( $user->user_login ) . '">' . site_url () . "/wp-login.php?action=rp&key=$key&login=" . rawurlencode ( $user->user_login ) . '';
        /* echo $rp_link;
          die(); */

        function wpdocs_set_html_mail_content_type() {
            return 'text/html';
        }

        $headers = array ( 'Content-Type: text/html; charset=UTF-8' );
        $message .= '
		    <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;"> 
		        <tr style="border-collapse:collapse"> 
		          	<td valign="top" style="padding:0;Margin:0"> 
						<table cellpadding="0" cellspacing="0" class="es-header" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top;"> 
						 <tr style="border-collapse:collapse"> 
						  <td align="center" style="padding:0;Margin:0"> 
						   <table class="es-header-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FEF5E4;width:600px " cellspacing="0" cellpadding="0" bgcolor="#fef5e4" align="center"> 
						     <tr style="border-collapse:collapse"> 
						      <td align="left" bgcolor="#cccccc" style="Margin:0;padding-top:5px;padding-bottom:5px;padding-left:15px;padding-right:15px;background-color:#CCCCCC"> 
						       <table cellspacing="0" cellpadding="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
						         <tr style="border-collapse:collapse"> 
						          <td class="es-m-p0r" valign="top" align="center" style="padding:0;Margin:0;width:570px"> 
						           <table width="100%" cellspacing="0" cellpadding="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
						             <tr style="border-collapse:collapse"> 
						              <td class="es-m-p0l es-m-txt-c" align="center" style="padding:0;Margin:0;padding-left:15px;font-size:0px"><a href="http://68.183.80.245/diverace/#/" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:open sans-serif, sans-serif;font-size:14px;text-decoration:underline;color:#999999"><img src="' . site_url () . '/wp-content/uploads/2020/05/logo@2x.png" alt="DiveRACE" title="DiveRACE" width="73" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td> 
						             </tr> 
						           </table></td> 
						         </tr> 
						       </table></td> 
						     </tr> 
						   </table></td> 
						 </tr> 
						</table> 

			            <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%"> 
			             <tr style="border-collapse:collapse"> 
			              <td align="center" style="padding:0;Margin:0"> 
			               <table class="es-content-body" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px"> 
			                 <tr style="border-collapse:collapse"> 
			                  <td align="left" style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:20px;padding-right:20px"> 
			                   <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
			                     <tr style="border-collapse:collapse"> 
			                      <td valign="top" align="center" style="padding:0;Margin:0;width:560px"> 
			                       <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;border-radius:0px" width="100%" cellspacing="0" cellpadding="0" role="presentation"> 
			                         <tr class="es-visible-simple-html-only" style="border-collapse:collapse"> 
			                          <td align="center" style="padding:0;Margin:0;padding-top:10px;padding-bottom:15px"><h1 style="Margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:open sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#333333">Hello ' . $user_name . ',</h1></td> 
			                         </tr> 
			                         <tr style="border-collapse:collapse"> 
			                          <td align="center" style="Margin:0;padding-top:5px;padding-bottom:5px;padding-left:40px;padding-right:40px"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif, sans-serif;line-height:21px;color:#333333">New user registred on site. <br></p><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif, sans-serif;line-height:21px;color:#333333">Click here in order to reset your password:<br><br>' . $rp_link . '</p></td> 
			                         </tr> 
			                       </table></td> 
			                     </tr> 
			                   </table></td> 
			                 </tr>             
			               </table></td> 
			             </tr> 
			            </table> 

			            <table cellpadding="0" cellspacing="0" class="es-footer" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top"> 
			             <tr style="border-collapse:collapse"> 
			              <td align="center" style="padding:0;Margin:0"> 
			               <table class="es-footer-body" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FEF5E4;width:600px"> 
			                 <tr style="border-collapse:collapse"> 
			                  <td align="left" bgcolor="#cccccc" style="padding:0;Margin:0;background-color:#CCCCCC"> 
			                   <table cellspacing="0" cellpadding="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
			                     <tr style="border-collapse:collapse"> 
			                      <td class="es-m-p0r" valign="top" align="center" style="padding:0;Margin:0;width:600px"> 
			                       <table width="100%" cellspacing="0" cellpadding="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
			                         <tr style="border-collapse:collapse"> 
			                          <td class="es-m-p0l es-m-txt-c" align="center" style="padding:0;Margin:0;font-size:0px"><a href="http://68.183.80.245/diverace" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:open sans-serif, sans-serif;font-size:14px;text-decoration:underline;color:#333333"><img src="' . site_url () . '/wp-content/uploads/2020/05/logo@2x.png" alt="DiveRace" title="DiveRace" width="68" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td> 
			                         </tr> 
			                         <tr style="border-collapse:collapse"> 
			                          <td class="es-m-txt-c" align="center" style="padding:0;Margin:0;padding-top:5px"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif, sans-serif;line-height:21px;color:#333333">©' . date ( 'Y' ) . ' DiveRACE. All rights rederved.</p></td> 
			                         </tr> 
			                       </table></td> 
			                     </tr> 
			                   </table></td> 
			                 </tr> 
			               </table></td> 
			             </tr> 
			           </table>
		           </td> 
		        </tr> 
		    </table> ';
        //echo $message;
        //wp_mail($user_email, 'Order Successfully Placed', $message,$headers);


        add_filter ( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
        $email_successful       = wp_mail ( $useremail, 'Reset password', $message, $headers );
        // Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
        remove_filter ( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
        /* if ($email_successful) {      */
        $data[ 'status' ]       = true;
        $data[ 'data' ][ 'id' ] = $user_ID;
        $data[ 'message' ]      = __ ( "Reset Password link has been sent to your email. Please check your email.", "wp-rest-user" );
        return $data;
    } else {
        $data[ 'status' ]  = false;
        $data[ 'data' ]    = [];
        $data[ 'message' ] = 'Does not valid user email.';
        return $data;
    }


    //send_forgote_password_mailto_user($useremail);
    // ==============================================================

    /* if ($email_successful) { */
    /* $data[$i]['id'] = $user_ID; */
    /* $data['status'] = true;
      $data['data']['id'] = $user_ID;
      $data['message'] = __("Reset Password link has been sent to your email.", "wp-rest-user"); */
    /* } else {
      //$error->add(402, __("Failed to send Reset Password email. Check your WordPress Hosting Email Settings.", 'wp-rest-user'), array('status' => 402));
      $data['status'] = false;
      $data['data']['status'] = false;
      $data['data']['message'] = __("Failed to send Reset Password email. Check your WordPress Hosting Email Settings.", "wp-rest-user");
      return $data;
      } */
    /* if(!empty($data)){
      //return ['status'=>true,'data'=>$data];
      return $data;
      }
      else{
      //return ['status'=>false ,'data'=>[]];
      return $data;
      } */

    //return $response;	
}

/**
  // --------------- API for Get User data from User ID ---------------
 */
function get_user_data($request) {
    $response   = array ();
    $parameters = $request->get_params ();
    $user_id    = sanitize_text_field ( $parameters[ 'user_id' ] );
    $data       = [];
    if( $user_id ) {
        $userdata = get_userdata ( $user_id );
        $userid   = $userdata->data->ID;

        if( $userdata ) {
            $userid                           = $userdata->data->ID;
            $user_data                        = [];
            $user_data[ 'user_id' ]           = $userid;
            $user_data[ 'first_name' ]        = get_the_author_meta ( 'first_name', $userid );
            $user_data[ 'last_name' ]         = get_the_author_meta ( 'last_name', $userid );
            $user_data[ 'user_phone_number' ] = get_the_author_meta ( 'user_phone_number', $userid );
            $user_data[ 'user_age' ]          = get_the_author_meta ( 'user_age', $userid );
            $user_data[ 'user_gender' ]       = get_the_author_meta ( 'user_gender', $userid );
            $user_data[ 'facebook_url' ]      = get_the_author_meta ( 'facebook', $userid );
            $user_data[ 'twitter_url' ]       = get_the_author_meta ( 'twitter', $userid );
            $user_data[ 'linkedin_url' ]      = get_the_author_meta ( 'linkedin', $userid );
            $user_data[ 'instagram_url' ]     = get_the_author_meta ( 'instagram', $userid );
            $user_data[ 'pinterest_url' ]     = get_the_author_meta ( 'pinterest', $userid );
            $data[ 'status' ]                 = true;
            $data[ 'data' ]                   = $user_data;
            $data[ 'message' ]                = __ ( "Get User profile details.", "wp-rest-user" );
            return $data;
        } else {
            $data[ 'status' ]  = false;
            $data[ 'data' ]    = $data;
            $data[ 'message' ] = 'User Not Found!';
            return $data;
        }
    } else {
        $data[ 'status' ]  = false;
        $data[ 'data' ]    = $data;
        $data[ 'message' ] = 'Does not valid user id.';
        return $data;
    }
}

/**
  // --------------- API for User Profile Update ---------------
 */
function update_user_profile($request) {
    $response          = array ();
    $parameters        = $request->get_params ();
    $user_id           = sanitize_text_field ( $parameters[ 'user_id' ] );
    $first_name        = sanitize_text_field ( $parameters[ 'first_name' ] );
    $last_name         = sanitize_text_field ( $parameters[ 'last_name' ] );
    $user_phone_number = sanitize_text_field ( $parameters[ 'user_phone_number' ] );
    $user_age          = sanitize_text_field ( $parameters[ 'user_age' ] );
    $user_gender       = sanitize_text_field ( $parameters[ 'user_gender' ] );
    $facebook_url      = $parameters[ 'facebook_url' ];
    $twitter_url       = sanitize_text_field ( $parameters[ 'twitter_url' ] );
    $linkedin_url      = sanitize_text_field ( $parameters[ 'linkedin_url' ] );
    $instagram_url     = sanitize_text_field ( $parameters[ 'instagram_url' ] );
    $pinterest_url     = sanitize_text_field ( $parameters[ 'pinterest_url' ] );

    if( $user_id ) {
        $userdata = get_userdata ( $user_id );
        $userid   = $userdata->data->ID;

        if( $userdata ) {
            $userid = $userdata->data->ID;

            if( ! empty ( $first_name ) ) {
                update_user_meta ( $userid, 'first_name', $first_name );
            }
            if( ! empty ( $last_name ) ) {
                update_user_meta ( $userid, 'last_name', $last_name );
            }
            if( ! empty ( $user_phone_number ) ) {
                update_user_meta ( $userid, 'user_phone_number', $user_phone_number );
            }
            if( ! empty ( $user_age ) ) {
                update_user_meta ( $userid, 'user_age', $user_age );
            }
            if( ! empty ( $user_gender ) ) {
                update_user_meta ( $userid, 'user_gender', $user_gender );
            }
            if( ! empty ( $facebook_url ) ) {
                update_user_meta ( $userid, 'facebook', $facebook_url );
            }
            if( ! empty ( $twitter_url ) ) {
                update_user_meta ( $userid, 'twitter', $twitter_url );
            }
            if( ! empty ( $linkedin_url ) ) {
                update_user_meta ( $userid, 'linkedin', $linkedin_url );
            }
            if( ! empty ( $instagram_url ) ) {
                update_user_meta ( $userid, 'instagram', $instagram_url );
            }
            if( ! empty ( $pinterest_url ) ) {
                update_user_meta ( $userid, 'pinterest', $pinterest_url );
            }
            $facebook_url           = get_the_author_meta ( 'facebook', $userid );
            $data[ 'status' ]       = true;
            $data[ 'data' ][ 'id' ] = $userid;
            $data[ 'message' ]      = __ ( "Your profile has been successfully updated.", "wp-rest-user" );
            return $data;
        } else {
            $data[ 'status' ]  = false;
            $data[ 'data' ]    = [];
            $data[ 'message' ] = 'User Not Found!';
            return $data;
        }
    } else {
        $data[ 'status' ]  = false;
        $data[ 'data' ]    = [];
        $data[ 'message' ] = 'Does not valid user id.';
        return $data;
    }
}

/**
  // --------------- API for User Profile Password Update ---------------
 */
function update_user_password($request) {
    $response             = array ();
    $parameters           = $request->get_params ();
    $user_id              = sanitize_text_field ( $parameters[ 'user_id' ] );
    $current_password     = sanitize_text_field ( $parameters[ 'current_password' ] );
    $new_password         = sanitize_text_field ( $parameters[ 'new_password' ] );
    $confirm_new_password = sanitize_text_field ( $parameters[ 'confirm_new_password' ] );

    if( empty ( $user_id ) ) {
        $data[ 'status' ]  = false;
        $data[ 'data' ]    = [];
        $data[ 'message' ] = 'User id field is required';
        return $data;
    }
    if( empty ( $current_password ) ) {
        $data[ 'status' ]  = false;
        $data[ 'data' ]    = [];
        $data[ 'message' ] = 'Current password field is required.';
        return $data;
    }
    if( empty ( $new_password ) ) {
        $data[ 'status' ]  = false;
        $data[ 'data' ]    = [];
        $data[ 'message' ] = 'New password field is required.';
        return $data;
    }
    if( empty ( $confirm_new_password ) ) {
        $data[ 'status' ]  = false;
        $data[ 'data' ]    = [];
        $data[ 'message' ] = 'Confirm new password field is required.';
        return $data;
    }

    if( isset ( $user_id ) ) {
        $userdata = get_userdata ( $user_id );
        $userid   = $userdata->data->ID;

        if( $userdata && isset ( $user_id ) && $user_id == $userid ) {
            $existing_password = $userdata->data->user_pass;
            $user              = get_user_by ( 'ID', $user_id );

            if( $user && wp_check_password ( $current_password, $existing_password, $userid ) ) {

                if( $new_password ) {
                    if( strlen ( $new_password ) < 5 || strlen ( $new_password ) > 15 ) {
                        $data[ 'status' ]       = false;
                        $data[ 'data' ][ 'id' ] = $userid;
                        $data[ 'message' ]      = 'Your password must be between 5 and 15 letters long.';
                        return $data;
                    } elseif( isset ( $new_password ) && $new_password != $confirm_new_password ) {
                        $data[ 'status' ]       = false;
                        $data[ 'data' ][ 'id' ] = $userid;
                        $data[ 'message' ]      = 'New password is not match with confirm password. please enter valid confirm password';
                        return $data;
                    } elseif( isset ( $new_password ) && ! empty ( $new_password ) ) {
                        $lpCUser                = wp_get_current_user ();
                        $update                 = wp_set_password ( $new_password, $userid );
                        wp_set_auth_cookie ( $lpCUser->ID );
                        wp_set_current_user ( $lpCUser->ID );
                        do_action ( 'wp_login', $lpCUser->user_login, $lpCUser );
                        $data[ 'status' ]       = true;
                        $data[ 'data' ][ 'id' ] = $userid;
                        $data[ 'message' ]      = 'Your password is updated successfully.';
                        return $data;
                    }
                }
            } else {
                $data[ 'status' ]       = false;
                $data[ 'data' ][ 'id' ] = $userid;
                $data[ 'message' ]      = 'Your password is not match with current password. Please enter valid password.';
                return $data;
            }
        } else {
            $data[ 'status' ]       = false;
            $data[ 'data' ][ 'id' ] = $userid;
            $data[ 'message' ]      = 'Existing user id is not valid id.';
            return $data;
        }
    }
}

// Step 1 API vessel data
function get_vessel_data() {
    $args = [
        'numberposts' => 99999,
        'post_type'   => 'vessel',
    ];

    $posts                 = get_posts ( $args );
    $data                  = [];
    $vessel_ID             = "";
    $vessel_title          = "";
    $vessel_featured_image = "";
    $vessel_vessels_icons  = "";

    $i = 0;

    foreach ( $posts as $post ) {
        $vessel_ID             = $post->ID;
        $vessel_title          = $post->post_title;
        $vessel_featured_image = get_the_post_thumbnail_url ( $post->ID, 'full' );
        $vessel_vessels_icons  = get_field ( 'vessels_icons', $post->ID );

        $data[ $i ][ 'id' ]             = $vessel_ID;
        $data[ $i ][ 'title' ]          = $vessel_title;
        $data[ $i ][ 'featured_image' ] = $vessel_featured_image;
        $data[ $i ][ 'vessels_icons' ]  = $vessel_vessels_icons;
        $i ++;
    }
    if( ! empty ( $data ) ) {
        return [ 'status' => true, 'data' => $data ];
    } else {
        return [ 'status' => false, 'data' => [] ];
    }
}

// Step 2 API country data
function get_country_data() {
    $args = array (
        'taxonomy'   => 'country',
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => 1,
    );

    $posts         = get_categories ( $args );
    $data          = [];
    $country_ID    = "";
    $country_title = "";
    $country_img   = "";
    $i             = 0;
    foreach ( $posts as $post ) {
        $country_ID    = $post->term_id;
        $country_title = $post->name;
        $country_img   = get_field ( 'country_image', 'country_' . $post->term_id );

        /* if(!empty($country_ID) && !empty($country_title)){ */

        $data[ $i ][ 'id' ]          = $country_ID;
        $data[ $i ][ 'title' ]       = $country_title;
        $data[ $i ][ 'country_img' ] = $country_img;

        $i ++;
        /* 	} */
    }
    if( ! empty ( $data ) ) {
        return [ 'status' => true, 'data' => $data ];
    } else {
        return [ 'status' => false, 'data' => [] ];
    }
}

// New Step 2 API get_destination_data data ========== get 02-09-2021
function get_destination_data() {
    $args = array (
        'posts_per_page' => -1,
        'post_type'      => 'destination',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $destination_posts = get_posts ( $args );
    $data              = [];
    $destination_ID    = "";
    $destination_title = "";
    $i                 = 0;
    foreach ( $destination_posts as $post ) {
        $destination_ID    = $post->ID;
        $destination_title = $post->post_title;
        if( ! empty ( $destination_ID ) && ! empty ( $destination_title ) ) {
            $data[ $i ][ 'id' ]    = $destination_ID;
            $data[ $i ][ 'title' ] = $destination_title;

            $destination_itineraries_relation                   = get_field ( 'diverace_destination_itineraries', $destination_ID );
            $destination_post_data[ 'destination_itineraries' ] = $destination_itineraries_relation;

            $new_data = [];
            $j        = 0;
            foreach ( $destination_post_data[ 'destination_itineraries' ] as $post ) {
                $itternary_post_data = get_post ( $post );

                if( $itternary_post_data->post_status == 'publish' ) {

                    $itternary_ID    = $itternary_post_data->ID;
                    $itternary_title = $itternary_post_data->post_title;

                    if( ! empty ( $itternary_ID ) && ! empty ( $itternary_title ) ) {
                        $itternary_price    = get_field ( 'diverace_itinerary_price', $itternary_ID );
                        $itternary_total_DN = get_field ( 'diverace_itinerary_total_days_and_nights', $itternary_ID );

                        $start_date           = get_field ( 'dive_start_date', $itternary_ID );
                        $date_start_date      = str_replace ( '/', '-', $start_date );
                        $end_date             = get_field ( 'dive_end_date', $itternary_ID );
                        $date_end_date        = str_replace ( '/', '-', $end_date );
                        $today_date           = date ( "d-m-Y" );
                        $curdate              = strtotime ( $today_date );
                        $startdate            = strtotime ( $date_start_date );
                        $enddate              = strtotime ( $date_end_date );
                        $itternary_start_date = date ( "j F", strtotime ( $date_start_date ) );
                        $itternary_end_date   = date ( "j F", strtotime ( $date_end_date ) );

                        $itternary_start_date_year = date ( "Y-m-d", strtotime ( $date_start_date ) );
                        $itternary_end_date_year   = date ( "Y-m-d", strtotime ( $date_end_date ) );

                        $itternary_summary = $itternary_start_date . " to " . $itternary_end_date . " " . $itternary_post_data_total_DN . " from S$ " . $itternary_post_data_price;

                        $new_data_inner = [
                            'itinerary_id'                => $itternary_ID,
                            'itinerary_title'             => $itternary_title,
                            'itinerary_price'             => $itternary_price,
                            'itinerary_total_days_nights' => $itternary_total_DN,
                            'dive_start_date'             => $itternary_start_date,
                            'dive_start_date_year'        => $itternary_start_date_year,
                            'dive_end_date'               => $itternary_end_date,
                            'dive_end_date_year'          => $itternary_end_date_year,
                            'summary'                     => $itternary_summary
                        ];
                    }
                }
                $new_data[ $j ] = $new_data_inner;
                $j ++;
            }
            $data[ $i ][ 'itinerary_data' ] = $new_data;

            $i ++;
        }
    }
    if( ! empty ( $data ) ) {
        return [ 'status' => true, 'data' => $data ];
    } else {
        return [ 'status' => false, 'data' => [] ];
    }
}

// New Step 2.1 API get_itinerary_data data ========== get 02-10-2021
function get_itinerary_data() {
    $args = array (
        'posts_per_page' => -1,
        'post_type'      => 'itinerary',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $itinerary_posts = get_posts ( $args );
    $data            = [];
    $itinerary_ID    = "";
    $itinerary_title = "";
    $i               = 0;
    foreach ( $itinerary_posts as $post ) {
        $itinerary_ID    = $post->ID;
        $itinerary_title = sanitize_text_field ( $post->post_title );
        if( ! empty ( $itinerary_ID ) && ! empty ( $itinerary_title ) ) {
            $data[ $i ][ 'id' ]                          = $itinerary_ID;
            $data[ $i ][ 'title' ]                       = $itinerary_title;
            $data[ $i ][ 'itternary_price' ]             = get_field ( 'diverace_itinerary_price', $itinerary_ID );
            $data[ $i ][ 'itinerary_total_days_nights' ] = sanitize_text_field ( get_field ( 'diverace_itinerary_total_days_and_nights', $itinerary_ID ) );

            $start_date                      = get_field ( 'dive_start_date', $itinerary_ID );
            $date_start_date                 = str_replace ( '/', '-', $start_date );
            $end_date                        = get_field ( 'dive_end_date', $itinerary_ID );
            $date_end_date                   = str_replace ( '/', '-', $end_date );
            $today_date                      = date ( "d-m-Y" );
            $curdate                         = strtotime ( $today_date );
            $startdate                       = strtotime ( $date_start_date );
            $enddate                         = strtotime ( $date_end_date );
            $data[ $i ][ 'dive_start_date' ] = date ( "j F", strtotime ( $date_start_date ) );
            $data[ $i ][ 'dive_end_date' ]   = date ( "j F", strtotime ( $date_end_date ) );

            $data[ $i ][ 'dive_start_date_year' ] = date ( "Y-m-d", strtotime ( $date_start_date ) );
            $data[ $i ][ 'dive_end_date_year' ]   = date ( "Y-m-d", strtotime ( $date_end_date ) );

            $data[ $i ][ 'summary' ] = $data[ $i ][ 'dive_start_date' ] . " to " . $data[ $i ][ 'dive_end_date' ] . " " . $data[ $i ][ 'itinerary_total_days_nights' ] . " from S$ " . $data[ $i ][ 'itternary_price' ];

            $itineraries_destination_relation = get_field ( 'destinations', $itinerary_ID );

            $new_data = [];
            $j        = 0;
            foreach ( $itineraries_destination_relation as $post ) {
                $destination_post_data = get_post ( $post );

                if( $destination_post_data->post_status == 'publish' ) {

                    $destination_ID    = $destination_post_data->ID;
                    $destination_title = $destination_post_data->post_title;

                    if( ! empty ( $destination_ID ) && ! empty ( $destination_title ) ) {

                        $new_data_inner = [
                            'itinerary_id'    => $destination_ID,
                            'itinerary_title' => $destination_title,
                        ];
                    }
                }
                $new_data[ $j ] = $new_data_inner;
                $j ++;
            }
            $data[ $i ][ 'destination_data' ] = $new_data;

            $i ++;
        }
    }
    if( ! empty ( $data ) ) {
        return [ 'status' => true, 'data' => $data ];
    } else {
        return [ 'status' => false, 'data' => [] ];
    }
}

// New Step 2.3 API get_itinerary_data_from_vessel_data data ========== get 26-02-2021 New
function get_itinerary_data_from_vessel_data($request) {
    global $wpdb;
    $parameters      = $request->get_params ();
    $vessel_id       = sanitize_text_field ( $parameters[ 'vessel_id' ] );
    $trip_start_date = strtotime ( $parameters[ 'trip_start_date' ] );
    $trip_end_date   = strtotime ( $parameters[ 'trip_end_date' ] );

    $curent_date = date ( "Y-m-d" );

    $data = [];
    $i    = 0;
    if( ! empty ( $vessel_id ) ) {
        $data[ $i ][ 'vessel_id' ]    = $vessel_id;
        $data[ $i ][ 'vessel_title' ] = get_the_title ( $vessel_id );

        $itineraries_from_vessel_relation = get_field ( 'list_of_all_itineraries', $vessel_id );
        $new_data                         = [];
        $j                                = 0;
        foreach ( $itineraries_from_vessel_relation as $key => $post ) {
            $itineraries_post_data = get_post ( $post );

            if( $itineraries_post_data->post_status == 'publish' ) {

                $itternary_ID       = $itineraries_post_data->ID;
                $itternary_title    = $itineraries_post_data->post_title;
                $itternary_price    = get_field ( 'diverace_itinerary_price', $itternary_ID );
                $itternary_total_DN = get_field ( 'diverace_itinerary_total_days_and_nights', $itternary_ID );

                $start_date           = get_field ( 'dive_start_date', $itternary_ID );
                $date_start_date      = str_replace ( '/', '-', $start_date );
                $end_date             = get_field ( 'dive_end_date', $itternary_ID );
                $date_end_date        = str_replace ( '/', '-', $end_date );
                $today_date           = date ( "d-m-Y" );
                $curdate              = strtotime ( $today_date );
                $startdate            = strtotime ( $date_start_date );
                $enddate              = strtotime ( $date_end_date );
                $itternary_start_date = date ( "j F", strtotime ( $date_start_date ) );
                $itternary_end_date   = date ( "j F", strtotime ( $date_end_date ) );

                $itternary_start_date_year = date ( "Y-m-d", strtotime ( $date_start_date ) );
                $itternary_end_date_year   = date ( "Y-m-d", strtotime ( $date_end_date ) );

                $itternary_summary = $itternary_start_date . " to " . $itternary_end_date . " " . $itternary_post_data_total_DN . " from S$ " . $itternary_post_data_price;

                // check relation of destination with trips
                $itineraries_destination_relation = get_field ( 'destinations', $itternary_ID );
                $destination_data                 = [];
                $cabin_order_arr                  = [];
                $k                                = 0;
                foreach ( $itineraries_destination_relation as $destination_post ) {
                    $destination_post_data = get_post ( $destination_post );

                    if( $destination_post_data->post_status == 'publish' ) {

                        $destination_ID    = $destination_post_data->ID;
                        $destination_title = $destination_post_data->post_title;

                        if( ! empty ( $destination_ID ) && ! empty ( $destination_title ) ) {

                            $destination_inner_data = [
                                'destination_id'    => $destination_ID,
                                'destination_title' => $destination_title,
                            ];
                        }
                    }
                    $destination_data[ $k ] = $destination_inner_data;
                    $k ++;
                }


                if( ! empty ( $itternary_ID ) && ! empty ( $itternary_title ) ) {
                    $new_itternary_data = [
                        'itinerary_id'                => $itternary_ID,
                        'itinerary_title'             => $itternary_title,
                        'itinerary_price'             => $itternary_price,
                        'itinerary_total_days_nights' => $itternary_total_DN,
                        'dive_start_date'             => $itternary_start_date,
                        'dive_start_date_year'        => $itternary_start_date_year,
                        'dive_end_date'               => $itternary_end_date,
                        'dive_end_date_year'          => $itternary_end_date_year,
                        'destination_title'           => $itternary_start_date . ' - ' . $itternary_end_date . ' (' . $itternary_total_DN . ')',
                        'summary'                     => $itternary_summary,
                        'destination_data'            => $destination_data,
                    ];
                }
            }
            $new_data[ $j ] = $new_itternary_data;
            $j ++;
        }

        $data[ $i ][ 'itineraries_data' ] = $new_data;

        $i ++;
    }

    if( ! empty ( $data ) ) {

        $data_arr = [];
        if( ! empty ( $trip_start_date ) || ! empty ( $trip_end_date ) ) {
            $data_arr[ 0 ][ 'vessel_id' ]    = $data[ 0 ][ 'vessel_id' ];
            $data_arr[ 0 ][ 'vessel_title' ] = $data[ 0 ][ 'vessel_title' ];
            $itineraries_data                = [];
            $data_arr                        = $data;
            foreach ( $data_arr[ 0 ][ 'itineraries_data' ] as $key => $value ) {

                /* ===== Start Checked cabin booked seats when date is not empty ===== */
                foreach ( $value[ 'destination_data' ] as $des_key => $des_value ) {
                    //echo 'Des_id__'.$des_value['destination_id'];						

                    $total_seats        = 0;
                    $total_booked_seats = 0;
                    if( have_rows ( 'diverace_vessel_cabins', $vessel_id ) ) {
                        while ( have_rows ( 'diverace_vessel_cabins', $vessel_id ) ) {
                            the_row ();
                            $cabins_IDs = get_sub_field ( 'cabins' );

                            foreach ( $cabins_IDs as $cabins ) {
                                $all_cabins         = $cabins_IDs;
                                $vessel_cabin_price = get_field ( 'cabin_price', $cabins );
                                $cabin_persons      = get_field ( 'for_how_many_persons', $cabins );

                                if( $cabin_persons == '2pax_spot' ) {
                                    $total_seats ++;
                                    $total_seats ++;
                                } else {
                                    $total_seats ++;
                                }

                                // check cabin seats from custom table
                                $order_table_name = $wpdb->prefix . 'custom_order_details';
                                $results_sql      = $wpdb->get_results ( "SELECT * FROM " . $order_table_name . " WHERE vessel_id = " . $vessel_id . " AND destination_id = " . $des_value[ 'destination_id' ] . " AND tripDate_id = " . $value[ 'itinerary_id' ] . " AND cabin_id = " . $cabins . " AND order_trash = 'NO'" );
                                /* if($value['itinerary_id']==138){
                                  echo "tripDate_id-> ".$value['itinerary_id'];
                                  echo "vessel_id-> ".$vessel_id;
                                  echo "destination_id-> ".$des_value['destination_id'];
                                  echo "cabin_id-> ".$cabins;
                                  print_r($results_sql); die();
                                  } */
                                if( $results_sql ) {
                                    if( $results_sql[ 0 ]->cabin_type == '2pax' && $results_sql[ 0 ]->cabin_seat == 'both' ) {
                                        $total_booked_seats ++;
                                        $total_booked_seats ++;
                                    } else {
                                        $total_booked_seats ++;
                                    }
                                }
                            }
                        }
                    } // If diverace_vessel_cabins End
                    //echo " total_seats --> ".$total_seats;					
                    //echo " total_booked_seats --> ".$total_booked_seats;					
                    $remaining_seats                                                                                    = $total_seats - $total_booked_seats;
                    $data_arr[ 0 ][ 'itineraries_data' ][ $key ][ 'destination_data' ][ $des_key ][ 'total_seats' ]     = $total_seats;
                    $data_arr[ 0 ][ 'itineraries_data' ][ $key ][ 'destination_data' ][ $des_key ][ 'remaining_seats' ] = $remaining_seats;

                    if( $total_seats == $total_booked_seats ) {
                        $data_arr[ 0 ][ 'itineraries_data' ][ $key ][ 'destination_data' ][ $des_key ][ 'is_booked' ] = 'yes';
                    } else {
                        $data_arr[ 0 ][ 'itineraries_data' ][ $key ][ 'destination_data' ][ $des_key ][ 'is_booked' ] = 'no';
                    }
                }
                /* ===== End Checked cabin booked seats when date is not empty ===== */

                if( ! empty ( $trip_end_date ) ) {
                    if( $trip_start_date <= strtotime ( $value[ 'dive_start_date_year' ] ) && $trip_end_date >= strtotime ( $value[ 'dive_start_date_year' ] ) ) {
                        $itineraries_data[ $key ] = $value;
                    } else {
                        unset ( $data_arr[ 0 ][ 'itineraries_data' ][ $key ] );
                    }
                } else {
                    if( $trip_start_date <= strtotime ( $value[ 'dive_start_date_year' ] ) ) {
                        $itineraries_data[ $key ] = $value;
                    } else {
                        unset ( $data_arr[ 0 ][ 'itineraries_data' ][ $key ] );
                    }
                }
                if( strtotime ( $curent_date ) >= strtotime ( $value[ 'dive_start_date_year' ] ) ) {
                    unset ( $data_arr[ 0 ][ 'itineraries_data' ][ $key ] );
                }
            }
            //$data_arr[0]['itineraries_data'] = array_values($itineraries_data);
        } else {
            $data_arr = $data;

            foreach ( $data_arr[ 0 ][ 'itineraries_data' ] as $key => $value ) {
                if( strtotime ( $curent_date ) >= strtotime ( $value[ 'dive_start_date_year' ] ) ) {
                    unset ( $data_arr[ 0 ][ 'itineraries_data' ][ $key ] );
                } else {

                    /* ===== Start Checked cabin booked seats for not selecte any date ===== */
                    foreach ( $value[ 'destination_data' ] as $des_key => $des_value ) {
                        //echo 'Des_id__'.$des_value['destination_id'];						
                        $total_seats        = 0;
                        $total_booked_seats = 0;
                        if( have_rows ( 'diverace_vessel_cabins', $vessel_id ) ) {
                            while ( have_rows ( 'diverace_vessel_cabins', $vessel_id ) ) {
                                the_row ();
                                $cabins_IDs = get_sub_field ( 'cabins' );

                                foreach ( $cabins_IDs as $cabins ) {
                                    $all_cabins         = $cabins_IDs;
                                    $vessel_cabin_price = get_field ( 'cabin_price', $cabins );
                                    $cabin_persons      = get_field ( 'for_how_many_persons', $cabins );

                                    if( $cabin_persons == '2pax_spot' ) {
                                        $total_seats ++;
                                        $total_seats ++;
                                    } else {
                                        $total_seats ++;
                                    }

                                    // check cabin seats from custom table
                                    $order_table_name = $wpdb->prefix . 'custom_order_details';
                                    $results_sql      = $wpdb->get_results ( "SELECT * FROM " . $order_table_name . " WHERE vessel_id = " . $vessel_id . " AND destination_id = " . $des_value[ 'destination_id' ] . " AND tripDate_id = " . $value[ 'itinerary_id' ] . " AND cabin_id = " . $cabins . " AND order_trash = 'NO'" );
                                    /* if($value['itinerary_id']==138){
                                      echo "tripDate_id-> ".$value['itinerary_id'];
                                      echo "vessel_id-> ".$vessel_id;
                                      echo "destination_id-> ".$des_value['destination_id'];
                                      echo "cabin_id-> ".$cabins;
                                      print_r($results_sql); die();
                                      } */
                                    if( $results_sql ) {
                                        if( $results_sql[ 0 ]->cabin_type == '2pax' && $results_sql[ 0 ]->cabin_seat == 'both' ) {
                                            $total_booked_seats ++;
                                            $total_booked_seats ++;
                                        } else {
                                            $total_booked_seats ++;
                                        }
                                    }
                                }
                            }
                        } // If diverace_vessel_cabins End
                        //echo " total_seats --> ".$total_seats;					
                        //echo " total_booked_seats --> ".$total_booked_seats;					
                        $remaining_seats                                                                                    = $total_seats - $total_booked_seats;
                        $data_arr[ 0 ][ 'itineraries_data' ][ $key ][ 'destination_data' ][ $des_key ][ 'total_seats' ]     = $total_seats;
                        $data_arr[ 0 ][ 'itineraries_data' ][ $key ][ 'destination_data' ][ $des_key ][ 'remaining_seats' ] = $remaining_seats;
                        if( $total_seats == $total_booked_seats ) {
                            $data_arr[ 0 ][ 'itineraries_data' ][ $key ][ 'destination_data' ][ $des_key ][ 'is_booked' ] = 'yes';
                        } else {
                            $data_arr[ 0 ][ 'itineraries_data' ][ $key ][ 'destination_data' ][ $des_key ][ 'is_booked' ] = 'no';
                        }
                    }
                    /* ===== End Checked cabin booked seats for not selecte any date ===== */
                }
            }
        }

        // Array sorting based on date
        usort ( $data_arr[ 0 ][ 'itineraries_data' ], function ($a, $b) {
            $datetime1 = strtotime ( $a[ 'dive_start_date_year' ] );
            $datetime2 = strtotime ( $b[ 'dive_start_date_year' ] );
            return $datetime1 - $datetime2;
        } );

        return [ 'status' => true, 'data' => $data_arr ];
    } else {
        return [ 'status' => false, 'data' => [] ];
    }
}

// Step 2.2 API destination data from country id
function get_itinerary_areas_from_country_data($request) {

    $parameters = $request->get_params ();
    $country_id = sanitize_text_field ( $parameters[ 'country_id' ] );

    $destination_args = array (
        'posts_per_page' => -1,
        'post_type'      => 'destination',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'tax_query'      => array (
            array (
                'taxonomy' => 'country',
                'field'    => 'term_id',
                'terms'    => $country_id
            )
        )
    );

    $destination_posts = get_posts ( $destination_args );
    $data              = [];
    $destination_ID    = "";
    $destination_title = "";
    $i                 = 0;
    foreach ( $destination_posts as $post ) {
        $destination_ID    = $post->ID;
        $destination_title = $post->post_title;
        if( ! empty ( $destination_ID ) && ! empty ( $destination_title ) ) {
            $data[ $i ][ 'id' ]    = $destination_ID;
            $data[ $i ][ 'title' ] = $destination_title;
            $i ++;
        }
    }
    if( ! empty ( $data ) ) {
        return [ 'status' => true, 'data' => $data ];
    } else {
        return [ 'status' => false, 'data' => [] ];
    }
}

// Step 2.3 API itinerary data from destination id
function get_date_from_itinerary_data($request) {
    global $wpdb;
    $parameters     = $request->get_params ();
    $destination_id = sanitize_text_field ( $parameters[ 'destination_id' ] );

    $destination_post_data = get_post ( $destination_id, ARRAY_A );

    $destination_itineraries_relation                   = get_field ( 'diverace_destination_itineraries', $destination_id );
    $destination_post_data[ 'destination_itineraries' ] = $destination_itineraries_relation;

    $itternary_post_data_ID         = "";
    $itternary_post_data_title      = "";
    $itternary_post_data_price      = "";
    $itternary_post_data_total_DN   = "";
    $itternary_post_data_start_date = "";
    $itternary_post_data_end_date   = "";
    $itternary_post_data_summary    = "";

    $data = [];
    $i    = 0;
    foreach ( $destination_post_data[ 'destination_itineraries' ] as $post ) {

        $itternary_post_data = get_post ( $post );

        if( $itternary_post_data->post_status == 'publish' ) {
            $itternary_post_data_ID = $itternary_post_data->ID;

            $results = $wpdb->get_results ( "SELECT tripDate_id FROM custom_order_details  WHERE tripDate_id=" . $itternary_post_data_ID . " AND order_trash = 'NO'" );

            $itternary_post_data_title      = $itternary_post_data->post_title;
            $itternary_post_data_price      = get_field ( 'diverace_itinerary_price', $itternary_post_data->ID );
            $itternary_post_data_total_DN   = get_field ( 'diverace_itinerary_total_days_and_nights', $itternary_post_data->ID );
            $start_date                     = get_field ( 'dive_start_date', $itternary_post_data->ID );
            $date_start_date                = str_replace ( '/', '-', $start_date );
            $end_date                       = get_field ( 'dive_end_date', $itternary_post_data->ID );
            $date_end_date                  = str_replace ( '/', '-', $end_date );
            $today_date                     = date ( "d-m-Y" );
            $curdate                        = strtotime ( $today_date );
            $startdate                      = strtotime ( $date_start_date );
            $enddate                        = strtotime ( $date_end_date );
            $itternary_post_data_start_date = date ( "j F", strtotime ( $date_start_date ) );
            $itternary_post_data_end_date   = date ( "j F", strtotime ( $date_end_date ) );

            $itternary_post_data_start_date_year = date ( "Y-m-d", strtotime ( $date_start_date ) );
            $itternary_post_data_end_date_year   = date ( "Y-m-d", strtotime ( $date_end_date ) );

            $itternary_post_data_summary = $itternary_post_data_start_date . " to " . $itternary_post_data_end_date . " " . $itternary_post_data_total_DN . " from S$ " . $itternary_post_data_price;

            //if($results[$i]->tripDate_id != $itternary_post_data_ID){
            if( $curdate < $startdate && $curdate < $enddate ) {
                $data[ $i ][ 'id' ]                       = $itternary_post_data_ID;
                $data[ $i ][ 'title' ]                    = $itternary_post_data_title;
                $data[ $i ][ 'diverace_itinerary_price' ] = $itternary_post_data_price;
                $data[ $i ][ 'dive_total_days_nights' ]   = $itternary_post_data_total_DN;
                $data[ $i ][ 'dive_start_date' ]          = $itternary_post_data_start_date;
                $data[ $i ][ 'dive_start_date_year' ]     = $itternary_post_data_start_date_year;
                $data[ $i ][ 'dive_end_date' ]            = $itternary_post_data_end_date;
                $data[ $i ][ 'dive_end_date_year' ]       = $itternary_post_data_end_date_year;
                $data[ $i ][ 'summary' ]                  = $itternary_post_data_summary;
                $i ++;
            } else {

                /* if(!empty($itternary_post_data_price)&& !empty($itternary_post_data_total_DN) && !empty($itternary_post_data_start_date) && !empty($itternary_post_data_end_date) ){
                 */
                /* $data[$i]['id']=  $itternary_post_data_ID;
                  $data[$i]['title']=  $itternary_post_data_title;
                  $data[$i]['diverace_itinerary_price']= $itternary_post_data_price;
                  $data[$i]['dive_total_days_nights']= $itternary_post_data_total_DN;
                  $data[$i]['dive_start_date']= $itternary_post_data_start_date;
                  $data[$i]['dive_end_date']= $itternary_post_data_end_date;
                  $data[$i]['summary']= $itternary_post_data_summary;
                  $i++; */

                /* } */
            }
            //	}
        }
    }
    if( ! empty ( $data ) ) {
        return [ 'status' => true, 'data' => $data ];
    } else {
        return [ 'status' => false, 'data' => [], 'message' => "Expired" ];
    }
}

// Step 3.1 API country data
function get_pax_data() {

    $args = array (
        'taxonomy'   => 'pax',
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => 1,
    );

    $posts = get_categories ( $args );
    $data  = [];
    $i     = 0;

    $pax_ID    = "";
    $pax_title = "";
    foreach ( $posts as $post ) {
        $pax_ID    = $post->term_id;
        $pax_title = $post->name;

        if( ! empty ( $pax_ID ) ) {
            $data[ $i ][ 'id' ] = $pax_ID;
        }
        if( ! empty ( $pax_title ) ) {
            $data[ $i ][ 'title' ] = $pax_title;
        }


        //$data[$i]['country_img'] = get_field('country_image','country_'.$post->term_id);

        $i ++;
    }
    if( ! empty ( $data ) ) {
        return [ 'status' => true, 'data' => $data ];
    } else {
        return [ 'status' => false, 'data' => [] ];
    }
}

// Step 3.3 API destination data from country id
function get_cabins_from_vessel_id_data($request) {
    global $wpdb;
    $parameters     = $request->get_params ();
    $vessel_id      = sanitize_text_field ( $parameters[ 'vessel_id' ] );
    $destination_id = sanitize_text_field ( $parameters[ 'vessel_id' ] );

    $tripDate_type              = sanitize_text_field ( $parameters[ 'tripDate_id' ] );
    /* $order_id = sanitize_text_field( $parameters['order_id'] );
      $cabin_data = get_post_meta($order_id,"cabin_data",true); */
    $i                          = 0;
    $count                      = 0;
    $vessel_cabin_data          = [];
    $vessel_cabin_ID            = "";
    $vessel_cabin_title         = "";
    $vessel_cabin_bow           = "";
    $vessel_cabin_gallery_image = "";
    $vessel_cabin_beds          = "";
    $vessel_cabin_bathrooms     = "";
    $vessel_cabin_price         = "";

    $total_seats        = 0;
    $total_booked_seats = 0;

    if( have_rows ( 'diverace_vessel_cabins', $vessel_id ) ) {
        while ( have_rows ( 'diverace_vessel_cabins', $vessel_id ) ) {
            the_row ();
            $cabins_IDs = get_sub_field ( 'cabins' );
            /* print_r($cabins_IDs);
              exit; */
            foreach ( $cabins_IDs as $cabins ) {
                $all_cabins         = $cabins_IDs;
                $vessel_cabin_price = get_field ( 'cabin_price', $cabins );
                $cabin_persons      = get_field ( 'for_how_many_persons', $cabins );

                if( $cabin_persons == '2pax_spot' ) {
                    $total_seats ++;
                    $total_seats ++;
                } else {
                    $total_seats ++;
                }

                $order_table_name = $wpdb->prefix . 'custom_order_details';
                $results          = $wpdb->get_results ( "SELECT * FROM " . $order_table_name . " WHERE vessel_id = " . $vessel_id . " AND destination_id = " . $destination_id . " AND tripDate_id = " . $tripDate_type . " AND cabin_id = " . $cabins . " AND order_trash = 'NO'" );

                //echo "cabinID from start-> ".$cabins .", ";
                //$order_table_name = $wpdb->prefix.'custom_order_details';
                //$results = $wpdb->get_results( "SELECT * FROM ".$order_table_name." WHERE cabin_id = ".$cabins." AND tripDate_id = ".$tripDate_type." AND order_trash = 'NO'");
                //print_r($results);
                if( count ( $results ) == 0 ) {
                    /*
                      foreach($results as $keys => $values) {
                      $cabin_seats_value  = $values->cabin_seat;
                      }
                      echo "seats-> ".$cabin_seats_value.", <br>"; */
                    //echo "<br/> cabin_result ID -> ".$cabins .", ";
                    $args         = array (
                        'post_type'  => 'cabin',
                        'p'          => $cabins,
                        'meta_query' => array (
                            array (
                                'key'     => 'for_how_many_persons',
                                'value'   => $pax_person,
                                'compare' => 'LIKE'
                            )
                        )
                    );
                    $cabins_datas = get_posts ( $args );
                    foreach ( $cabins_datas as $cabins_data ) {
                        $vessel_cabin_ID            = $cabins_data->ID;
                        $vessel_cabin_title         = $cabins_data->post_title;
                        $vessel_cabin_bow           = get_field ( 'bow', $cabins_data->ID );
                        $vessel_cabin_gallery_image = get_field ( 'gallery', $cabins_data->ID );
                        $vessel_cabin_beds          = get_field ( 'beds', $cabins_data->ID );
                        $vessel_cabin_bathrooms     = get_field ( 'bathrooms', $cabins_data->ID );
                        $vessel_cabin_price         = get_field ( 'cabin_price', $cabins_data->ID );
                        $cabin_persons              = get_field ( 'for_how_many_persons', $cabins_data->ID );
                        //$cabin_pax_seats = get_field('cabin_pax_seats',$cabins_data->ID);		

                        if( ! empty ( $vessel_cabin_price ) && ! empty ( $vessel_cabin_title ) ) {
                            /* if($cabin_pax_seats == "full" || $cabin_pax_seats == "both"){}
                              else{ */
                            if( ! empty ( $vessel_cabin_ID ) ) {
                                $vessel_cabin_data[ $i ][ 'id' ] = $vessel_cabin_ID;
                            }
                            if( $vessel_cabin_title ) {
                                $vessel_cabin_data[ $i ][ 'title' ] = $vessel_cabin_title;
                            }
                            if( $vessel_cabin_bow ) {
                                $vessel_cabin_data[ $i ][ 'bow' ] = $vessel_cabin_bow;
                            }
                            if( $vessel_cabin_gallery_image ) {
                                $vessel_cabin_data[ $i ][ 'gallery_image' ] = $vessel_cabin_gallery_image;
                            }
                            if( $vessel_cabin_beds ) {
                                $vessel_cabin_data[ $i ][ 'beds' ] = $vessel_cabin_beds;
                            }
                            if( $vessel_cabin_bathrooms ) {
                                $vessel_cabin_data[ $i ][ 'bathrooms' ] = $vessel_cabin_bathrooms;
                            }
                            if( $vessel_cabin_price ) {
                                $vessel_cabin_data[ $i ][ 'cabin_price' ] = $vessel_cabin_price;
                            }
                            if( $cabin_persons ) {
                                $vessel_cabin_data[ $i ][ 'pax_for_persons' ] = $cabin_persons;
                            }
                            /* if(!empty($cabin_seats_value))
                              {
                              $vessel_cabin_data[$i]['seat']= $cabin_seats_value;
                              }else{ */
                            $vessel_cabin_data[ $i ][ 'seat' ] = "empty";
                            /* } */
                            $i ++;
                            /* } */
                        }
                    }
                    continue;
                }

                foreach ( $results as $keys => $values ) {
                    $cabin_seats_value = $values->cabin_seat;
                    if( $cabin_seats_value != "both" && count ( $results ) < 2 ) {
                        $args         = array (
                            'post_type'  => 'cabin',
                            'p'          => $cabins,
                            'meta_query' => array (
                                array (
                                    'key'     => 'for_how_many_persons',
                                    'value'   => $pax_person,
                                    'compare' => 'LIKE'
                                )
                            )
                        );
                        $cabins_datas = get_posts ( $args );
                        foreach ( $cabins_datas as $cabins_data ) {
                            $vessel_cabin_ID            = $cabins_data->ID;
                            $vessel_cabin_title         = $cabins_data->post_title;
                            $vessel_cabin_bow           = get_field ( 'bow', $cabins_data->ID );
                            $vessel_cabin_gallery_image = get_field ( 'gallery', $cabins_data->ID );
                            $vessel_cabin_beds          = get_field ( 'beds', $cabins_data->ID );
                            $vessel_cabin_bathrooms     = get_field ( 'bathrooms', $cabins_data->ID );
                            $vessel_cabin_price         = get_field ( 'cabin_price', $cabins_data->ID );
                            $cabin_persons              = get_field ( 'for_how_many_persons', $cabins_data->ID );
                            //$cabin_pax_seats = get_field('cabin_pax_seats',$cabins_data->ID);		

                            if( ! empty ( $vessel_cabin_price ) && ! empty ( $vessel_cabin_title ) ) {
                                /* if($cabin_pax_seats == "full" || $cabin_pax_seats == "both"){}
                                  else{ */
                                if( ! empty ( $vessel_cabin_ID ) ) {
                                    $vessel_cabin_data[ $i ][ 'id' ] = $vessel_cabin_ID;
                                }
                                if( $vessel_cabin_title ) {
                                    $vessel_cabin_data[ $i ][ 'title' ] = $vessel_cabin_title;
                                }
                                if( $vessel_cabin_bow ) {
                                    $vessel_cabin_data[ $i ][ 'bow' ] = $vessel_cabin_bow;
                                }
                                if( $vessel_cabin_gallery_image ) {
                                    $vessel_cabin_data[ $i ][ 'gallery_image' ] = $vessel_cabin_gallery_image;
                                }
                                if( $vessel_cabin_beds ) {
                                    $vessel_cabin_data[ $i ][ 'beds' ] = $vessel_cabin_beds;
                                }
                                if( $vessel_cabin_bathrooms ) {
                                    $vessel_cabin_data[ $i ][ 'bathrooms' ] = $vessel_cabin_bathrooms;
                                }
                                if( $vessel_cabin_price ) {
                                    $vessel_cabin_data[ $i ][ 'cabin_price' ] = $vessel_cabin_price;
                                }
                                if( $cabin_persons ) {
                                    $vessel_cabin_data[ $i ][ 'pax_for_persons' ] = $cabin_persons;
                                }
                                if( ! empty ( $cabin_seats_value ) ) {
                                    $vessel_cabin_data[ $i ][ 'seat' ] = $cabin_seats_value;
                                } else {
                                    $vessel_cabin_data[ $i ][ 'seat' ] = "empty";
                                }
                                $i ++;
                                /* } */
                            }
                        }
                        $count ++;
                    } else {
                        
                    }
                }
            }
        }
    }

    if( ! empty ( $vessel_cabin_data ) ) {
        return [ 'status' => true, 'data' => $vessel_cabin_data ];
    } else {
        return [ 'status' => false, 'data' => [], ];
    }
}

// Step 4.1 API course data from course id
function get_courses_data() {

    $course_args       = array (
        'posts_per_page' => -1,
        'post_type'      => 'course',
        'orderby'        => 'date',
        'order'          => 'DESC'
    );
    $course_post_data  = get_posts ( $course_args );
    $i                 = 0;
    $data              = [];
    $course_post_ID    = "";
    $course_post_title = "";
    $course_post_image = "";
    $course_post_price = "";
    foreach ( $course_post_data as $post ) {

        $course_post_ID    = $post->ID;
        $course_post_title = $post->post_title;
        $course_post_image = get_the_post_thumbnail_url ( $post->ID, 'full' );
        $course_post_price = get_field ( 'course_price', $post->ID );
        if( ! empty ( $course_post_title ) && ! empty ( $course_post_price ) ) {
            if( ! empty ( $course_post_ID ) ) {
                $data[ $i ][ 'id' ] = $course_post_ID;
            }
            if( ! empty ( $course_post_title ) ) {
                $data[ $i ][ 'title' ] = $course_post_title;
            }
            if( ! empty ( $course_post_image ) ) {
                $data[ $i ][ 'featured_image' ] = $course_post_image;
            }
            if( ! empty ( $course_post_price ) ) {
                $data[ $i ][ 'course_price' ] = $course_post_price;
            }
            $i ++;
        }
    }

    //return $course_post_data;
    if( ! empty ( $data ) ) {
        return [ 'status' => true, 'data' => $data ];
    } else {
        return [ 'status' => false, 'data' => [] ];
    }
}

// Step 4.2 API course data from Rental Equipment
function get_rental_equipment_data() {

    $rental_equipment_args = array (
        'posts_per_page' => -1,
        'post_type'      => 'rental_equipment',
        'orderby'        => 'date',
        'order'          => 'DESC'
    );
    $rental_equipment_data = get_posts ( $rental_equipment_args );
    $i                     = 0;
    $data                  = [];

    $rental_equipment_ID    = "";
    $rental_equipment_title = "";
    $rental_equipment_price = "";
    foreach ( $rental_equipment_data as $post ) {
        $rental_equipment_ID               = $post->ID;
        $rental_equipment_title            = $post->post_title;
        $rental_equipment_term             = get_field ( 'rental_equipment_term', $post->ID );
        $rental_equipment_price            = get_field ( 'rental_equipment_price', $post->ID );
        $rental_equipment_size_based_on_x  = get_field ( 'rental_equipment_size_based_on_x', $post->ID );
        $rental_equipment_size_based_on_us = get_field ( 'rental_equipment_size_based_on_us', $post->ID );

        if( ! empty ( $rental_equipment_title ) && ! empty ( $rental_equipment_price ) ) {

            if( ! empty ( $rental_equipment_ID ) ) {
                $data[ $i ][ 'id' ] = $rental_equipment_ID;
            }
            if( ! empty ( $rental_equipment_title ) ) {
                $data[ $i ][ 'title' ] = $rental_equipment_title;
            }
            if( ! empty ( $rental_equipment_price ) ) {
                $data[ $i ][ 'price' ] = $rental_equipment_price;
            }
            if( ! empty ( $rental_equipment_term ) ) {
                $data[ $i ][ 'rental_equipment_term' ] = $rental_equipment_term;
            }
            $data[ $i ][ 'rental_equipment_size' ] = [];
            if( ! empty ( $rental_equipment_size_based_on_x ) ) {
                $field                                 = get_field_object ( 'field_60225f1075968' );
                $choices                               = $field[ 'choices' ];
                $data[ $i ][ 'rental_equipment_size' ] = array_values ( $choices );
            }
            if( ! empty ( $rental_equipment_size_based_on_us ) ) {
                $field                                 = get_field_object ( 'field_6022734c1eac8' );
                $choices                               = $field[ 'choices' ];
                $data[ $i ][ 'rental_equipment_size' ] = array_values ( $choices );
            }

            $i ++;
        }
    }

    //return $course_post_data;
    if( ! empty ( $data ) ) {
        return [ 'status' => true, 'data' => $data ];
    } else {
        return [ 'status' => false, 'data' => [] ];
    }
}

// Step 4.2 API Get discount code API // get_coupon_data & get_agent_data code merge 28/05/2021
function get_coupon_data($request) {
    global $wpdb;
    $parameters  = $request->get_params ();
    $coupon_code = sanitize_text_field ( $parameters[ 'coupon_code' ] );
    $user_id     = sanitize_text_field ( $parameters[ 'user_id' ] );
    $coupon_args = array (
        'posts_per_page' => -1,
        'post_type'      => 'coupons',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => array (
            array (
                'key'     => 'coupon_code',
                'value'   => $coupon_code,
                'compare' => '='
            )
        )
    );

    $i                = 0;
    $data             = [];
    $order_table_name = $wpdb->prefix . 'custom_order_details';
    $results          = $wpdb->get_results ( "SELECT * FROM " . $order_table_name . " WHERE user_id = " . $user_id . " AND coupon_code = '" . $coupon_code . "' AND order_trash = 'NO'" );

    if( count ( $results ) != 0 ) {
        $data[ 'coupon_status' ] = false;
        $data[ 'message' ]       = "Sorry You have already applied this code.";
        return [ 'status' => false, 'data' => $data ];
    }

    $coupon_data = get_posts ( $coupon_args );

    if( count ( $coupon_data ) != 0 ) {
        foreach ( $coupon_data as $post ) {
            $start_date        = get_field ( 'coupon_validity_start_date', $post->ID );
            $end_date          = get_field ( 'coupon_validity_end_date', $post->ID );
            $coupon_ID         = $post->ID;
            $coupon_data_code  = get_field ( 'coupon_code', $post->ID );
            $coupon_percentage = get_field ( 'coupon_discount_percentage', $post->ID );
            $coupon_status     = get_field ( 'coupon_status', $post->ID );
            $today_date        = date ( "d/m/Y" );

            $today_date = str_replace ( '/', '-', $today_date );
            $start_date = str_replace ( '/', '-', $start_date );
            $end_date   = str_replace ( '/', '-', $end_date );
            $curdate    = strtotime ( $today_date );
            $mydate     = strtotime ( $start_date );
            $enddate    = strtotime ( $end_date );

            if( $coupon_status == 1 ) {
                if( ($curdate >= $mydate) && ($today_date <= $enddate) ) {
                    $data[ 'id' ]                         = $post->ID;
                    $data[ 'coupon_code' ]                = $coupon_data_code;
                    $data[ 'coupon_status' ]              = $coupon_status;
                    $data[ 'coupon_discount_percentage' ] = $coupon_percentage;
                    $data[ 'message' ]                    = "Promocode applied successfully !";
                } else {
                    $data[ 'coupon_status' ] = false;
                    $data[ 'message' ]       = "Promocode was expired!";
                }
            } else {
                $data[ 'coupon_status' ] = false;
                $data[ 'message' ]       = "Invalid promocode !";
            }
        }
    } else {
        /* $data['coupon_status'] = false;
          $data['message'] = "Invalid promocode !"; */

        $agent_args = array (
            'posts_per_page' => -1,
            'post_type'      => 'agent_code',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => array (
                array (
                    'key'     => 'agent_code',
                    'value'   => $coupon_code,
                    'compare' => '='
                )
            )
        );
        $agent_data = get_posts ( $agent_args );
        /* echo "TREE<pre>";
          print_r($agent_data); */
        $agent_data = get_posts ( $agent_args );
        $i          = 0;
        $data       = [];

        if( count ( $agent_data ) != 0 ) {
            foreach ( $agent_data as $post ) {
                $agent_ID         = $post->ID;
                $agent_data_code  = get_field ( 'agent_code', $post->ID );
                $agent_percentage = get_field ( 'agent_discount_percentage', $post->ID );
                $agent_status     = get_field ( 'agent_status', $post->ID );
                if( $agent_status == 1 ) {
                    $data[ 'id' ]                         = $agent_ID;
                    $data[ 'coupon_code' ]                = $agent_data_code;
                    $data[ 'coupon_status' ]              = $agent_status;
                    $data[ 'coupon_discount_percentage' ] = $agent_percentage;
                    $data[ 'message' ]                    = "Agent Code applied successfully !";
                } else {
                    $data[ 'agent_status' ] = false;
                    $data[ 'message' ]      = "Invalid Agent Code !";
                }
            }
        } else {
            $data[ 'coupon_status' ] = false;
            $data[ 'message' ]       = "Invalid Coupon Code !";
        }
    }

    if( ! empty ( $data ) ) {
        return [ 'status' => true, 'data' => $data ];
    } else {
        return [ 'status' => false, 'data' => [] ];
    }
}

// Step 4.3 Agent Code data
/* function get_agent_data($request){
  global $wpdb;
  $parameters = $request->get_params();
  $agent_code = sanitize_text_field( $parameters['agent_code'] );
  $user_id = sanitize_text_field( $parameters['user_id'] );
  $agent_args = array(
  'posts_per_page' => -1,
  'post_type' => 'agent_code',
  'orderby' => 'date',
  'order'   => 'DESC' ,
  'meta_query' => array(
  array(
  'key' => 'agent_code',
  'value' => $agent_code,
  'compare' => '='
  )
  )
  );
  $agent_data  = get_posts($agent_args);
  $i=0;
  $data=[];

  if(count($agent_data) != 0){
  foreach ($agent_data as $post) {
  $agent_ID = $post->ID;
  $agent_data_code= get_field('agent_code',$post->ID);
  $agent_percentage= get_field('agent_discount_percentage',$post->ID);
  $agent_status= get_field('agent_status',$post->ID);
  if($agent_status == 1 ) {
  $data['id']= $agent_ID;
  $data['agent_code']= $agent_data_code;
  $data['agent_status'] = $agent_status ;
  $data['agent_discount_percentage']= $agent_percentage;
  $data['message'] = "Agent Code applied successfully !";
  }
  else{
  $data['agent_status'] = false;
  $data['message'] = "Invalid Agent Code !";
  }

  }
  }
  else{
  $data['agent_status'] = false;
  $data['message'] = "Invalid Agent Code !";
  }
  //return $course_post_data;
  if(!empty($data)) {
  return ['status'=>true,'data'=>$data];
  }
  else{
  return ['status'=>false ,'data'=>[]];
  }
  } */




function edit_courses_data_from_order_id($request) {
    wp_reset_postdata ();
    $parameters = $request->get_params ();
    $order_ids  = sanitize_text_field ( $parameters[ 'order_id' ] );
    $user_id    = sanitize_text_field ( $parameters[ 'user_id' ] );

    $courses_data    = get_post_meta ( $order_ids, "courses_data", true );
    $total_person    = get_post_meta ( $order_ids, "total_person", true );
    /* $args = array(
      'post__in' => array($order_ids),
      'post_type' => 'orders',
      ); */
    $course_ids_list = array ();
    //$order_post_data = get_posts($args);
    for ( $i = 0; $i < $courses_data; $i ++ ) {
        $meta_key_id                              = "courses_data_" . $i . "_courses_id";
        $meta_key_person                          = "courses_data_" . $i . "_courses_person";
        $courses_meta_data_id                     = get_post_meta ( $order_ids, $meta_key_id, true );
        $courses_meta_data_person                 = get_post_meta ( $order_ids, $meta_key_person, true );
        $course_ids_list[ $courses_meta_data_id ] = $courses_meta_data_person;
    }
    //$userID = get_post_meta($order_ids,"user_id",true);

    /* print_r($course_ids_list);
      die(); */
    $course_args       = array (
        'posts_per_page' => -1,
        'post_type'      => 'course',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    $course_post_data  = get_posts ( $course_args );
    $i                 = 0;
    $data              = [];
    $course_post_ID    = "";
    $course_post_title = "";
    $course_post_image = "";
    $course_post_price = "";
    if( ! empty ( $user_id ) ) {
        foreach ( $course_post_data as $post ) {
            $course_post_ID    = $post->ID;
            $course_post_title = $post->post_title;
            $course_post_image = get_the_post_thumbnail_url ( $post->ID, 'full' );
            $course_post_price = get_field ( 'course_price', $post->ID );
            if( ! empty ( $course_post_title ) && ! empty ( $course_post_price ) ) {
                /* if($course_ids_list[$course_post_ID] != $total_person){ */
                if( ! empty ( $course_post_ID ) ) {
                    $data[ $i ][ 'id' ] = $course_post_ID;
                }
                if( ! empty ( $course_post_title ) ) {
                    $data[ $i ][ 'title' ] = $course_post_title;
                }
                if( ! empty ( $course_post_image ) ) {
                    $data[ $i ][ 'featured_image' ] = $course_post_image;
                }
                if( ! empty ( $course_post_price ) ) {
                    $data[ $i ][ 'course_price' ] = (int) $course_post_price;
                }
                if( array_key_exists ( $course_post_ID, $course_ids_list ) ) {
                    $data[ $i ][ 'booked_course' ] = (int) $course_ids_list[ $course_post_ID ];
                    $data[ $i ][ 'total_person' ]  = (int) $total_person;
                } else {
                    $data[ $i ][ 'booked_course' ] = 0;
                    $data[ $i ][ 'total_person' ]  = (int) $total_person;
                }
                $i ++;
                /* } */
            }
        }
    }

    //return $course_post_data;
    if( ! empty ( $data ) ) {
        return [ 'status' => true, 'data' => $data, 'message' => "records found" ];
    } else {
        return [ 'status' => false, 'data' => [], 'message' => "records not found !" ];
    }
}

//Rental Equipment Data Edit API 

function edit_rental_equipment_data_from_order_id($request) {
    wp_reset_postdata ();
    $parameters = $request->get_params ();
    $order_ids  = sanitize_text_field ( $parameters[ 'order_id' ] );
    $user_id    = sanitize_text_field ( $parameters[ 'user_id' ] );

    $rental_equipment_data = get_post_meta ( $order_ids, "rental_equipment_data", true );
    $total_person          = get_post_meta ( $order_ids, "total_person", true );

    $rental_equipment_ids_list = array ();
    //$order_post_data = get_posts($args);
    for ( $i = 0; $i < $rental_equipment_data; $i ++ ) {
        $meta_key_id                                                 = "rental_equipment_data_" . $i . "_rental__equipment_id";
        $meta_key_person                                             = "rental_equipment_data_" . $i . "_rental__equipment_person";
        $rental_equipment_meta_data_id                               = get_post_meta ( $order_ids, $meta_key_id, true );
        $rental_equipment_meta_data_person                           = get_post_meta ( $order_ids, $meta_key_person, true );
        $rental_equipment_ids_list[ $rental_equipment_meta_data_id ] = $rental_equipment_meta_data_person;
    }
    //$user_id = get_post_meta($order_ids,"user_id",true);
    /* echo "<pre>";
      print_r($rental_equipment_ids_list);
      die(); */
    $rental_equipment_args = array (
        'posts_per_page' => -1,
        'post_type'      => 'rental_equipment',
        'orderby'        => 'date',
        'order'          => 'DESC'
    );
    $rental_equipment_data = get_posts ( $rental_equipment_args );
    $i                     = 0;
    $data                  = [];

    $rental_equipment_ID    = "";
    $rental_equipment_title = "";
    $rental_equipment_price = "";

    if( ! empty ( $user_id ) ) {
        foreach ( $rental_equipment_data as $post ) {
            $rental_equipment_ID    = $post->ID;
            $rental_equipment_title = $post->post_title;
            $rental_equipment_term  = get_field ( 'rental_equipment_term', $post->ID );
            $rental_equipment_price = get_field ( 'rental_equipment_price', $post->ID );
            if( ! empty ( $rental_equipment_title ) && ! empty ( $rental_equipment_price ) ) {
                /* if($course_ids_list[$course_post_ID] != $total_person){ */
                if( ! empty ( $rental_equipment_ID ) ) {
                    $data[ $i ][ 'id' ] = $rental_equipment_ID;
                }
                if( ! empty ( $rental_equipment_title ) ) {
                    $data[ $i ][ 'title' ] = $rental_equipment_title;
                }
                if( ! empty ( $rental_equipment_term ) ) {
                    $data[ $i ][ 'rental_equipment_term' ] = $rental_equipment_term;
                }
                if( ! empty ( $rental_equipment_price ) ) {
                    $data[ $i ][ 'rental_equipment_price' ] = (int) $rental_equipment_price;
                }
                if( array_key_exists ( $rental_equipment_ID, $rental_equipment_ids_list ) ) {
                    $data[ $i ][ 'booked_rental_equipment' ] = (int) $rental_equipment_ids_list[ $rental_equipment_ID ];
                    $data[ $i ][ 'total_person' ]            = (int) $total_person;
                } else {
                    $data[ $i ][ 'booked_rental_equipment' ] = 0;
                    $data[ $i ][ 'total_person' ]            = 0;
                }
                $i ++;
                /* }	 */
            }
        }
    }

    //return $course_post_data;
    if( ! empty ( $data ) ) {
        return [ 'status' => true, 'data' => $data, 'message' => "records found" ];
    } else {
        return [ 'status' => false, 'data' => [], 'message' => "records not found !" ];
    }
}

// Step 4.3 Agent Code data
/* function get_user_credit_data($request){

  $parameters = $request->get_params();
  $user_id = sanitize_text_field( $parameters['user_id'] );
  $the_user = get_user_by( 'id', $user_id ); // 54 is a user ID

  $key = 'user_credit';
  $single = true;
  $user_credit = get_user_meta( $user_id, $key, $single );

  $i=0;
  $data=[];
  if(!empty($the_user)){
  if($user_credit != ""){
  $data['user_status'] = true;
  $data['user_id']= $user_id;
  $data['user_credit'] = $user_credit ;
  $data['message'] = "Here is user Credit!";
  }else{
  $data['user_status'] = false;
  $data['message'] = "Opps, Sorry you don't have any credits!";
  }
  }else{
  $data['user_status'] = false;
  $data['message'] = "Not Valid user!";
  }

  //return $course_post_data;
  if(!empty($data)) {
  return ['status'=>true,'data'=>$data];
  }
  else{
  return ['status'=>false ,'data'=>[]];
  }
  } */


//Insert Inro Order Data
function add_order_data($request) {
    global $wpdb;
    $parameters = $request->get_params ();

    $order_data = json_decode ( file_get_contents ( "php://input" ), true );

    $user_id        = $order_data[ 'user_id' ];
    $user_role      = $order_data[ 'user_role' ]; // new field 050621
    $vessel_id      = $order_data[ 'vessel_type' ];
    $country_id     = $order_data[ 'country_type' ];
    $destination_id = $order_data[ 'itineraryArea_type' ];
    $trip_date_id   = $order_data[ 'tripDate_type' ];

    $total_person = $order_data[ 'passenger' ];
    $coupon_id    = $order_data[ 'coupon_id' ];
    $coupon_code  = $order_data[ 'coupon_code' ];
    //$agent_id = $order_data['agent_id'];
    //$agent_code = $order_data['agent_code'];

    $order_currency      = $order_data[ 'default_currency' ]; // new field 050621  // order_currency
    $payble_amount       = $order_data[ 'final_payble_amount' ];
    $partial_amount      = $order_data[ 'partial_amount' ];
    $partial_amount_type = $order_data[ 'partial_amount_type' ];
    if( $partial_amount_type == '100' ) {
        $remaining_amount     = intval ( 0 );
        $pass_amount_on_strip = $payble_amount;
    } else {
        $remaining_amount     = $payble_amount - $partial_amount;
        $pass_amount_on_strip = $payble_amount - $partial_amount;
    }


    $card_id = $order_data[ 'transaction_data' ][ 'card_id' ];

    $pax_data = $order_data[ 'pax' ];

    // Remove it as per new phase - 06-05-2021
    //$cabin_data = $order_data['cabin_types'];
    //$courses_data = $order_data['courses_types'];
    //$rental_equipment_data = $order_data['rental_equipment_types'];
    //$user_credit_used = $order_data['user_credit'];
    $user_credit_used = "";

    if( $card_id != "" ) {
        //$randomid = rand(100,1000); 
        //======== Start booking id YYYYMMDD_numberofbookingsfortoday ========
        $today_date         = date ( 'Ymd' );
        $current_year       = date ( 'Y' );
        $current_month      = date ( 'm' );
        $current_day        = date ( 'd' );
        $args_order_history = array (
            'post_type'      => 'orders',
            'post_status'    => 'publish',
            'posts_per_page' => 5,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'date_query'     => array (
                'relation' => 'OR',
                array ( // returns posts created today
                    'year'  => $current_year,
                    'month' => $current_month,
                    'day'   => $current_day,
                ),
            ),
        );

        $final_total_order_today = 0;
        $allOrders               = new WP_Query ( $args_order_history );
        if( $allOrders->have_posts () ) {
            $count_total_order_today = $allOrders->found_posts;
            $final_total_order_today = $count_total_order_today + 1;
        } else {
            $final_total_order_today = 1;
        }
        //======== Start booking id YYYYMMDD_numberofbookingsfortoday ========

        $Order_name = $today_date . '_' . $final_total_order_today;
        $post_id    = wp_insert_post ( array (
            'post_title'  => $Order_name,
            'post_status' => 'publish',
            'post_type'   => 'orders',
                ) );
    }

    if( $post_id ) {

        if( ! $user_id == "" ) {
            add_post_meta ( $post_id, 'user_id', $user_id );
        }
        if( ! $vessel_id == "" ) {
            add_post_meta ( $post_id, 'vessel_id', $vessel_id );
        }
        if( ! $country_id == "" ) {
            add_post_meta ( $post_id, 'country_id', $country_id );
        }
        if( ! $destination_id == "" ) {
            add_post_meta ( $post_id, 'destination_id', $destination_id );
        }
        if( ! $trip_date_id == "" ) {
            add_post_meta ( $post_id, 'trip_date_id', $trip_date_id );
        }
        if( ! $total_person == "" ) {
            add_post_meta ( $post_id, 'total_person', $total_person );
        }
        if( ! $coupon_id == "" ) {
            add_post_meta ( $post_id, 'coupon_data_coupon_id', $coupon_id );
        }
        if( ! $coupon_code == "" ) {
            add_post_meta ( $post_id, 'coupon_data_coupon_code', $coupon_code );
        }
        //if(!$agent_id == ""){add_post_meta($post_id, 'agent_data_agent_id', $agent_id); }	
        //if(!$agent_code == ""){add_post_meta($post_id, 'agent_data_agent_code', $agent_code); }	

        if( ! $order_currency == "" ) {
            add_post_meta ( $post_id, 'order_currency', $order_currency );
        }
        if( ! $payble_amount == "" ) {
            add_post_meta ( $post_id, 'payble_amount', $payble_amount );
        }
        if( ! $partial_amount == "" ) {
            add_post_meta ( $post_id, 'partial_amount', $partial_amount );
        }
        if( ! $partial_amount_type == "" ) {
            add_post_meta ( $post_id, 'partial_amount_type', $partial_amount_type );
        }
        if( ! $remaining_amount == "" ) {
            add_post_meta ( $post_id, 'remaining_amount', $remaining_amount );
        }

        //if(!$transaction_id == ""){add_post_meta($post_id, 'transaction_data_transaction_id', $transaction_id); }	
        if( ! $card_id == "" ) {
            add_post_meta ( $post_id, 'transaction_data_card_id', $card_id );
        }
        //if(!$client_ip == ""){add_post_meta($post_id, 'transaction_data_client_ip', $client_ip); }	
        //if(!$transaction_data_payment_date == ""){add_post_meta($post_id, 'transaction_data_payment_date', $transaction_data_payment_date); }	
        //if(!$transaction_data_client_email == ""){add_post_meta($post_id, 'transaction_data_client_email', $transaction_data_client_email); }
        if( ! $user_credit_used == "" ) {
            add_post_meta ( $post_id, 'user_credit_used', $user_credit_used );
        }

        $user_credit_data = get_user_meta ( $user_id, "user_credit", true );

        if( $user_credit_used != "" || $user_credit_data == "" ) {
            $final_amount = $user_credit_data - $user_credit_used;
            update_user_meta ( $user_id, 'user_credit', $final_amount );
        }
        $order_status = '';
        if( $partial_amount_type == 100 ) {
            $order_status = 'Completed';
            add_post_meta ( $post_id, 'order_status', "Completed" );
        } else {
            $order_status = 'Pending';
            add_post_meta ( $post_id, 'order_status', "Pending" );
        }


        $pax_count     = 0;
        $pax_data_cout = count ( $pax_data );
        add_post_meta ( $post_id, 'pax_data_pax_type', "Solo" );
        add_post_meta ( $post_id, '_pax_data_pax_type', "field_5f6c38e76ff4d" );
        add_post_meta ( $post_id, 'pax_data_selected_gender', "male" );
        add_post_meta ( $post_id, '_pax_data_selected_gender', "field_5f6c392f6ff4e" );

        add_post_meta ( $post_id, 'pax_data_pax_name', "name" );
        add_post_meta ( $post_id, '_pax_data_pax_name', "field_5ffe80462ca18" );

        add_post_meta ( $post_id, 'pax_data_pax_email', "email" );
        add_post_meta ( $post_id, '_pax_data_pax_email', "field_5ffe812ced146" );

        add_post_meta ( $post_id, 'pax_data_pax_phone_number', "phone_number" );
        add_post_meta ( $post_id, '_pax_data_pax_phone_number', "field_600aa69c2c944" );

        add_post_meta ( $post_id, 'pax_data_pax_age', "age" );
        add_post_meta ( $post_id, '_pax_data_pax_age', "field_5ffe815aed147" );

        add_post_meta ( $post_id, 'pax_data_pax_person_details', "person_details" );
        add_post_meta ( $post_id, '_pax_data_pax_person_details', "field_600abe6465123" );

        // Start New Code 15-03-21
        add_post_meta ( $post_id, 'pax_data_pax_cabin_id', "cabin_id" );
        add_post_meta ( $post_id, '_pax_data_pax_cabin_id', "field_604f550c45451" );

        add_post_meta ( $post_id, 'pax_data_pax_cabin_type', "cabin_type" );
        add_post_meta ( $post_id, '_pax_data_pax_cabin_type', "field_604f572c45453" );

        add_post_meta ( $post_id, 'pax_data_pax_course_id', "course_id" );
        add_post_meta ( $post_id, '_pax_data_pax_course_id', "field_604f57aa45454" );

        /* add_post_meta($post_id, 'pax_data_pax_rental_equipment_data', "rental_equipment_id");
          add_post_meta($post_id, '_pax_data_pax_rental_equipment_data', "field_604f584e45456");

          add_post_meta($post_id, 'pax_data_pax_rental_equipment_data', "rental_equipment_size");
          add_post_meta($post_id, '_pax_data_pax_rental_equipment_data', "field_604f587045457"); */
        // End New Code 15-03-21	

        add_post_meta ( $post_id, '_pax_data', "field_5f6c38d56ff4c" );
        add_post_meta ( $post_id, 'pax_data', $pax_data_cout );

        $personcount    = 1;
        $cabin_type_all = [];

        foreach ( $pax_data as $pax ) {

            foreach ( $pax as $key => $value ) {
                $here_data    = "";
                $genedr_count = 1;
                if( $key == "type" ) {
                    $pax_type  = "pax_data_" . $pax_count . "_pax_type";
                    $pax_type2 = "_pax_data_" . $pax_count . "_pax_type";
                    $pax_value = $value;

                    if( ! $pax_value == "" ) {
                        add_post_meta ( $post_id, $pax_type, $pax_value );
                    }
                    if( ! $pax_type2 == "" ) {
                        add_post_meta ( $post_id, $pax_type2, "field_5f6c4d72056f3" );
                    }
                }
                if( $key == "name" ) {
                    $pax_name       = "pax_data_" . $pax_count . "_pax_name";
                    $pax_name2      = "_pax_data_" . $pax_count . "_pax_name";
                    $pax_name_value = $value;

                    if( ! $pax_name_value == "" ) {
                        add_post_meta ( $post_id, $pax_name, $pax_name_value );
                    }
                    if( ! $pax_name2 == "" ) {
                        add_post_meta ( $post_id, $pax_name2, "field_5ffe80462ca18" );
                    }
                }
                if( $key == "email" ) {
                    $pax_email       = "pax_data_" . $pax_count . "_pax_email";
                    $pax_email2      = "_pax_data_" . $pax_count . "_pax_email";
                    $pax_email_value = $value;

                    if( ! $pax_email_value == "" ) {
                        add_post_meta ( $post_id, $pax_email, $pax_email_value );
                    }
                    if( ! $pax_email2 == "" ) {
                        add_post_meta ( $post_id, $pax_email2, "field_5ffe812ced146" );
                    }
                }

                if( $key == "phone_number" ) {
                    $pax_phone       = "pax_data_" . $pax_count . "_pax_phone_number";
                    $pax_phone2      = "_pax_data_" . $pax_count . "_pax_phone_number";
                    $pax_phone_value = $value;

                    if( ! $pax_phone_value == "" ) {
                        add_post_meta ( $post_id, $pax_phone, $pax_phone_value );
                    }
                    if( ! $pax_phone2 == "" ) {
                        add_post_meta ( $post_id, $pax_phone2, "field_600aa69c2c944" );
                    }
                }

                if( $key == "age" ) {
                    $pax_age       = "pax_data_" . $pax_count . "_pax_age";
                    $pax_age2      = "_pax_data_" . $pax_count . "_pax_age";
                    $pax_age_value = $value;

                    if( ! $pax_age_value == "" ) {
                        add_post_meta ( $post_id, $pax_age, $pax_age_value );
                    }
                    if( ! $pax_age2 == "" ) {
                        add_post_meta ( $post_id, $pax_age2, "field_5ffe815aed147" );
                    }
                }

                if( $key == "cabin_id" ) {

                    $pax_cabin_id       = "pax_data_" . $pax_count . "_pax_cabin_id";
                    $pax_cabin_id2      = "_pax_data_" . $pax_count . "_pax_cabin_id";
                    $pax_cabin_id_value = $value;

                    if( ! $pax_cabin_id_value == "" ) {
                        add_post_meta ( $post_id, $pax_cabin_id, $pax_cabin_id_value );
                    }
                    if( ! $pax_cabin_id2 == "" ) {
                        add_post_meta ( $post_id, $pax_cabin_id2, "field_604f550c45451" );
                    }
                }

                if( $key == "cabin_type" ) { // OK 111111111111
                    $pax_cabin_type       = "pax_data_" . $pax_count . "_pax_cabin_type";
                    $pax_cabin_type2      = "_pax_data_" . $pax_count . "_pax_cabin_type";
                    $pax_cabin_type_value = $value;

                    array_push ( $cabin_type_all, $pax_cabin_type_value );

                    if( ! $pax_cabin_type_value == "" ) {
                        add_post_meta ( $post_id, $pax_cabin_type, $pax_cabin_type_value );
                    }
                    if( ! $pax_cabin_type2 == "" ) {
                        add_post_meta ( $post_id, $pax_cabin_type2, "field_604f572c45453" );
                    }
                }

                if( $key == "course_id" ) {
                    $pax_course_id       = "pax_data_" . $pax_count . "_pax_course_id";
                    $pax_course_id2      = "_pax_data_" . $pax_count . "_pax_course_id";
                    $pax_course_id_value = $value;

                    if( ! $pax_course_id_value == "" ) {
                        add_post_meta ( $post_id, $pax_course_id, $pax_course_id_value );
                    }
                    if( ! $pax_course_id2 == "" ) {
                        add_post_meta ( $post_id, $pax_course_id2, "field_604f57aa45454" );
                    }
                }

                if( $key == "equipments" ) {
                    $pax_equip_count = 0;

                    foreach ( $pax[ 'equipments' ] as $equipkey => $equipvalue ) {

                        // ===== Total pax_rental_equipment_data data store for each person. =====
                        $pax_equipment_data       = "pax_data_" . $pax_count . "_pax_rental_equipment_data";
                        $pax_equipment_data2      = "_pax_data_" . $pax_count . "_pax_rental_equipment_data";
                        $pax_equipment_data_value = count ( $pax[ 'equipments' ] );
                        update_post_meta ( $post_id, $pax_equipment_data, $pax_equipment_data_value );
                        update_post_meta ( $post_id, $pax_equipment_data2, "field_604f580045455" );

                        // ===== Start if $equipkey == 'rental_equipment_id' then call =====
                        $pax_equipment_id       = "pax_data_" . $pax_count . "_pax_rental_equipment_data_" . $pax_equip_count . "_rental_equipment_id";
                        $pax_equipment_id2      = "_pax_data_" . $pax_count . "_pax_rental_equipment_data_" . $pax_equip_count . "_rental_equipment_id";
                        $pax_equipment_id_value = $equipvalue[ 'id' ];

                        if( ! $pax_equipment_id_value == "" ) {
                            add_post_meta ( $post_id, $pax_equipment_id, $pax_equipment_id_value );
                        }
                        if( ! $pax_equipment_id2 == "" ) {
                            add_post_meta ( $post_id, $pax_equipment_id2, "field_604f584e45456" );
                        }

                        // ===== Start if $equipkey == 'rental_equipment_size' then call =====
                        $pax_equipment_size       = "pax_data_" . $pax_count . "_pax_rental_equipment_data_" . $pax_equip_count . "_rental_equipment_size";
                        $pax_equipment_size2      = "_pax_data_" . $pax_count . "_pax_rental_equipment_data_" . $pax_equip_count . "_rental_equipment_size";
                        $pax_equipment_size_value = $equipvalue[ 'size' ];

                        if( ! empty ( $pax_equipment_size_value ) ) {
                            add_post_meta ( $post_id, $pax_equipment_size, $pax_equipment_size_value );
                        } else {
                            $pax_equipment_size_value = "";
                            add_post_meta ( $post_id, $pax_equipment_size, $pax_equipment_size_value );
                        }
                        if( ! empty ( $pax_equipment_size2 ) ) {
                            add_post_meta ( $post_id, $pax_equipment_size2, "field_604f587045457" );
                        } else {
                            $pax_equipment_size2 = "";
                            add_post_meta ( $post_id, $pax_equipment_size2, $pax_equipment_size2 );
                        }

                        $pax_equip_count ++;
                    }
                }


                if( $key == "name" ) {
                    $pax_person_details       = "pax_data_" . $pax_count . "_pax_person_details";
                    $pax_person_details2      = "_pax_data_" . $pax_count . "_pax_person_details";
                    $pax_person_details_value = 'Person' . $personcount;

                    if( ! $pax_person_details_value == "" ) {
                        add_post_meta ( $post_id, $pax_person_details, $pax_person_details_value );
                    }
                    if( ! $pax_person_details2 == "" ) {
                        add_post_meta ( $post_id, $pax_person_details2, "field_600abe6465123" );
                    }
                }

                if( $key == "gender" ) {
                    $gendet_full_data    = count ( $value );
                    $selected_gender     = "pax_data_" . $pax_count . "_selected_gender";
                    $pax_selected_gender = "_pax_data_" . $pax_count . "_selected_gender";
                    foreach ( $value as $key => $genders ) {
                        $here_data .= $genders;
                        if( $genedr_count < $gendet_full_data ) {
                            $here_data .= ",";
                        }
                        $genedr_count ++;
                    }

                    if( ! $pax_selected_gender == "" ) {
                        add_post_meta ( $post_id, $pax_selected_gender, "field_5f6c392f6ff4e" );
                    }
                    if( ! $here_data == "" ) {
                        add_post_meta ( $post_id, $selected_gender, $here_data );
                    }
                }
            }

            $pax_count ++;
            $personcount ++;
        }

        // Strip 
        $api_mode_live__test = get_field ( 'api_mode_live__test', 'option' );
        if( $api_mode_live__test == true ) {
            $strip_api_key = get_field ( 'live_api_key', 'option' );
        } else {
            $strip_api_key = get_field ( 'test_api_key', 'option' );
        }
        $stripe           = new \Stripe\StripeClient ( [
            "api_key" => $strip_api_key
                ] );
        $user_description = 'Test Customer - Diverace';

        $stripe_customer_id = get_the_author_meta ( 'stripe_customer_id', $user_id );
        $stripe_charge_id   = "";
        try {
            $int_amount        = (sprintf ( '%.2f', $pass_amount_on_strip )) * 100;
            $chargeObjectStrip = $stripe->charges->create ( [
                'amount'      => $int_amount,
                'currency'    => $order_currency,
                'source'      => $card_id,
                'description' => $user_description,
                'customer'    => $stripe_customer_id
                    ] );
            $stripe_charge_id  = $chargeObjectStrip[ 'id' ];
            if( ! empty ( $stripe_charge_id ) ) {
                add_post_meta ( $post_id, 'stripe_charge_id', $stripe_charge_id );
            }
        } catch (\Exception $e) {
            $error = $e;
        }

        /* echo '<pre>';
          print_r($chargeObjectStrip);
          exit; */
        /* $stripe_charge_id = $chargeObjectStrip['id'];
          if(!empty($stripe_charge_id)){
          add_post_meta($post_id, 'stripe_charge_id', $stripe_charge_id);
          } */
        //echo "stripe_charge_id".$stripe_charge_id;
        //exit;

        /* if(!empty($stripe_customer_id)){
          echo "IN IF";
          $stripe_customer_id = $stripe_customer_id;
          } else{
          if(!empty($stripe_charge_id)){
          add_post_meta($post_id, 'stripe_charge_id', $stripe_charge_id);
          }
          } */

        /* echo "<br>";
          echo "DB_stripe_charge_id__".$stripe_charge_id = get_post_meta($post_id,"stripe_charge_id",true);
          exit; */

        // Save custom order table data
        $final_cabin_type_all_array = implode ( ",", $cabin_type_all );

        $order_table_name = $wpdb->prefix . 'custom_order_details';
        $date             = date ( 'Y-m-d H:i:s' );
        $wpdb->insert (
                $order_table_name,
                array (
                    'id'             => $id,
                    'user_id'        => $user_id,
                    'vessel_id'      => $vessel_id,
                    //'cabin_id' => $cabin_id_value,
                    'cabin_type'     => $final_cabin_type_all_array, //$cabin_type_value,
                    //'cabin_seat' => $selected_seat_value,
                    'destination_id' => $destination_id,
                    'tripDate_id'    => $trip_date_id,
                    'order_date'     => $date,
                    'order_id'       => $post_id,
                    'status'         => $order_status,
                    'order_trash'    => "No",
                    'coupon_id'      => $coupon_id,
                    'agent_id'       => $agent_id,
                    'coupon_code'    => $coupon_code,
                    'agent_code'     => $agent_code,
                )
        );

        //send_email_on_order_placed($post_id);
        if( $post_id != "" && $user_id != "" ) {
            send_email_on_order_place ( $post_id, $user_id );
        }


        $args_invoice_history      = array (
            'post_type'   => 'invoice',
            'post_status' => 'publish',
            'orderby'     => 'date',
            'order'       => 'DESC',
        );
        $final_total_invoice_today = 0;
        $allInvoice                = new WP_Query ( $args_invoice_history );
        if( $allInvoice->have_posts () ) {
            $count_total_invoice_today = $allInvoice->found_posts;
            $final_total_invoice_today = $count_total_invoice_today + 1;
        } else {
            $final_total_invoice_today = 1;
        }

        $invoice_title   = 'INV_' . $Order_name . '_' . $post_id.'_'.$final_total_invoice_today;
        $invoice_id      = wp_insert_post ( array (
            'post_title'  => $invoice_title,
            'post_status' => 'publish',
            'post_type'   => 'invoice',
                ) );
        $trip_start_date = get_field ( 'dive_start_date', $trip_date_id );
        $trip_end_date   = get_field ( 'dive_end_date', $trip_date_id );
        $user_infomation = get_userdata ( $user_id );
        $user_fname      = get_the_author_meta ( 'first_name', $user_id );
        $user_lname      = get_the_author_meta ( 'last_name', $user_id );
        $user_phoneno    = get_the_author_meta ( 'user_phone_number', $user_id );
        $user_email_id   = $user_infomation->user_email;
        update_field ( 'inv_order_id', $Order_name, $invoice_id );
        update_field ( 'inv_user_name', $user_fname . ' ' . $user_lname, $invoice_id );
        update_field ( 'inv_user_email', $user_email_id, $invoice_id );
        update_field ( 'inv_user_phone_no', $user_phoneno, $invoice_id );
        update_field ( 'inv_vessel_name', get_the_title ( $vessel_id ), $invoice_id );
        update_field ( 'inv_loaction', get_the_title ( $destination_id ), $invoice_id );
        update_field ( 'inv_trip_name', get_the_title ( $trip_date_id ), $invoice_id );
        update_field ( 'inv_trip_date', $trip_start_date . '-' . $trip_end_date, $invoice_id );
        update_field ( 'inv_no_of_passenger', $total_person, $invoice_id );
        update_field ( 'inv_coupon', get_the_title($coupon_id), $invoice_id );
        update_field ( 'inv_agent_name', get_the_title($agent_id), $invoice_id );
        update_field ( 'inv_payment_method', $post_id, $invoice_id );
        update_field ( 'inv_total_amount', $payble_amount, $invoice_id );
        update_field ( 'inv_pending_amount', $remaining_amount, $invoice_id );
        update_field ( 'inv_complete_payment', $partial_amount_type . '%', $invoice_id );
//        if( $invoice_id != "" && $user_id != "" ) {
            send_invoice_on_order_place ( $invoice_id, $user_id );
//        }
        //add_post_meta($post_id, 'courses', $courses);
        $response        = array (
            'message'             => 'OK ',
            'order_id'            => $post_id,
            'destination_id'      => $destination_id,
            'order_title'         => $Order_name,
            'order_stripe_charge' => $stripe_charge_id,
        );
    }
    //------ Start Call "zapier" webook URL after purchase is complete ------//
    $curl            = curl_init ();
    curl_setopt_array ( $curl, array (
        CURLOPT_URL            => 'https://hooks.zapier.com/hooks/catch/9651424/onfhadr/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'GET',
    ) );
    $zapier_response = curl_exec ( $curl );
    curl_close ( $curl );
    //echo $zapier_response;
    //------ End Call "zapier" webook URL after purchase is complete ------//

    if( ! empty ( $response ) ) {
        return [ 'status' => true, 'data' => $response, 'message' => 'Order Placed successfully.' ];
    } else {
        return [ 'status' => false, 'data' => [], 'message' => 'Opps! Someting went Wrong.', ];
    }
}

// -------------- Insert data to the waiting_list 02-03-2021 --------------
function add_waiting_list($request) {
    global $wpdb;

    $parameters     = $request->get_params ();
    $response       = [];
    $vessel_id      = sanitize_text_field ( $parameters[ 'vessel_id' ] );
    $destination_id = sanitize_text_field ( $parameters[ 'destination_id' ] );
    $trip_date_id   = sanitize_text_field ( $parameters[ 'trip_date_id' ] );
    $first_name     = sanitize_text_field ( $parameters[ 'first_name' ] );
    $last_name      = sanitize_text_field ( $parameters[ 'last_name' ] );
    $email          = sanitize_text_field ( $parameters[ 'email' ] );
    $phone_number   = sanitize_text_field ( $parameters[ 'phone_number' ] );

    if( ( ! empty ( $vessel_id )) && ( ! empty ( $destination_id )) && ( ! empty ( $trip_date_id )) && ( ! empty ( $first_name )) && ( ! empty ( $last_name )) && ( ! empty ( $email )) ) {
        $order_table_name = $wpdb->prefix . 'order_waiting';
        $date             = date ( 'Y-m-d H:i:s' );
        $wpdb->insert (
                $order_table_name,
                array (
                    'vessel_id'      => $vessel_id,
                    'destination_id' => $destination_id,
                    'tripDate_id'    => $trip_date_id,
                    'first_name'     => $first_name,
                    'last_name'      => $last_name,
                    'email'          => $email,
                    'phone_number'   => $phone_number,
                    'create_date'    => $date,
                )
        );
    }
    if( $wpdb->insert_id ) {
        $last_insert_id = $wpdb->insert_id;
        if( $last_insert_id ) {
            /* $headers = array('Content-Type: text/html; charset=UTF-8');
              $toemail = 'vijay.wappnet@gmail.com';
              $email_subject = 'Your waiting list Deatils - DiveRACE';

              $message .='<table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;">
              <tr style="border-collapse:collapse">
              <td valign="top" style="padding:0;margin:0">'.$first_name.'</td>
              <td valign="top" style="padding:0;margin:0">'.$last_name.'</td>
              <td valign="top" style="padding:0;margin:0">'.$email.'</td>'; */
            //wp_mail($toemail, $email_subject, $message, $headers);
            send_email_for_waiting_list ( $last_insert_id, $vessel_id, $destination_id, $trip_date_id, $first_name, $last_name, $email, $phone_number );
        }
        return [ 'status' => true, 'data' => $response, 'message' => 'Your waiting list has added successfully. We will inform through email once the booking is available.' ];
    } else {
        return [ 'status' => false, 'data' => [], 'message' => "Opps! Someting went Wrong." ];
    }
}

// Step 3.3 API View Order Data from user id
function view_order_data_from_user_id($request) {

    $parameters = $request->get_params ();
    $user_id    = sanitize_text_field ( $parameters[ 'user_id' ] );

    $i                   = 0;
    $order_data          = [];
    $order_ID            = "";
    $order_title         = "";
    $order_bow           = "";
    $order_gallery_image = "";
    $order_beds          = "";
    $order_bathrooms     = "";
    $order_price         = "";

    $args        = array ( 'post_type' => 'orders', 'posts_per_page' => -1 );
    $order_datas = get_posts ( $args );
    foreach ( $order_datas as $order_list ) {
        $order_ID                = $order_list->ID;
        $order_title             = $order_list->post_title;
        $vessel_id               = get_field ( 'vessel_id', $order_list->ID );
        $country_id              = get_field ( 'country_id', $order_list->ID );
        $destination_id          = get_field ( 'destination_id', $order_list->ID );
        $trip_date_id            = get_field ( 'trip_date_id', $order_list->ID );
        $total_person            = get_field ( 'total_person', $order_list->ID );
        $coupon_data_coupon_id   = get_field ( 'coupon_data_coupon_id', $order_list->ID );
        $coupon_data_coupon_code = get_field ( 'coupon_data_coupon_code', $order_list->ID );
        //$agent_data_agent_id = get_field('agent_data_agent_id',$order_list->ID);
        //$agent_data_agent_code = get_field('agent_data_agent_code',$order_list->ID);
        $payble_amount           = get_field ( 'payble_amount', $order_list->ID );

        $order_meta_user_ID = get_field ( 'user_id', $order_list->ID );
        if( $order_meta_user_ID == $user_id ) {
            //echo $user_id."<br/>";
            $country                      = get_term_by ( 'id', $country_id, 'country' );
            $itternary_post_data_price    = get_field ( 'diverace_itinerary_price', $trip_date_id );
            $itternary_post_data_total_DN = get_field ( 'diverace_itinerary_total_days_and_nights', $trip_date_id );

            $start_date                     = get_field ( 'dive_start_date', $trip_date_id );
            $date_start_date                = str_replace ( '/', '-', $start_date );
            $end_date                       = get_field ( 'dive_end_date', $trip_date_id );
            $date_end_date                  = str_replace ( '/', '-', $end_date );
            $today_date                     = date ( "d-m-Y" );
            $curdate                        = strtotime ( $today_date );
            $startdate                      = strtotime ( $date_start_date );
            $enddate                        = strtotime ( $date_end_date );
            $itternary_post_data_start_date = date ( "j F", strtotime ( $date_start_date ) );
            $itternary_post_data_end_date   = date ( "j F", strtotime ( $date_end_date ) );
            $itternary_post_data_summary    = $itternary_post_data_start_date . " to " . $itternary_post_data_end_date . " " . $itternary_post_data_total_DN . " | & Days from &s " . $itternary_post_data_price;

            $order_data[ $i ][ 'id' ]                      = $order_list->ID;
            $order_data[ $i ][ 'order_title' ]             = $order_title;
            $order_data[ $i ][ 'vessel_title' ]            = get_the_title ( $vessel_id );
            $order_data[ $i ][ 'itinery_title' ]           = get_the_title ( $destination_id );
            $order_data[ $i ][ 'country_title' ]           = $country->name;
            $order_data[ $i ][ 'itternary_price' ]         = (int) $itternary_post_data_price;
            $order_data[ $i ][ 'itternary_total_DN' ]      = $itternary_post_data_total_DN;
            $order_data[ $i ][ 'itternary_start_date' ]    = $itternary_post_data_start_date;
            $order_data[ $i ][ 'itternary_end_date' ]      = $itternary_post_data_end_date;
            $order_data[ $i ][ 'itternary_date_summary' ]  = $itternary_post_data_summary;
            $order_data[ $i ][ 'coupon_title' ]            = get_the_title ( $coupon_data_coupon_id );
            $order_data[ $i ][ 'coupon_data_coupon_code' ] = $coupon_data_coupon_code;
            //$order_data[$i]['agent_title']= get_the_title($agent_data_agent_id); 
            //$order_data[$i]['agent_data_agent_code']= $agent_data_agent_code; 
            $order_data[ $i ][ 'payble_amount' ]           = (int) $payble_amount;
            $order_data[ $i ][ 'total_person' ]            = (int) $total_person;
            /* echo "coupon_data_coupon_id -> ".$coupon_data_coupon_id."<br/>";
              echo "coupon_data_coupon_code -> ".$coupon_data_coupon_code."<br/>"; */

            /* $cabin_list = get_post_meta($order_list->ID,"cabin_list",true);
              for ($cab=0; $cab < $cabin_list; $cab++) {
              $cabinID = get_post_meta($order_list->ID,"cabin_list_".$cab."_cabinID",true);
              $cabin_types = get_post_meta($order_list->ID,"cabin_list_".$cab."_cabin_types",true);
              $selected_seats = get_post_meta($order_list->ID,"cabin_list_".$cab."_selected_seats",true);
              $order_data[$i]['cabin_data'][$cab]["cabin_title"] = get_the_title($cabinID);
              $order_data[$i]['cabin_data'][$cab]["cabin_type"] = $cabin_types;
              $order_data[$i]['cabin_data'][$cab]['selected_seat'] = $selected_seats;

              } */

            $cabin_list = get_post_meta ( $order_list->ID, "cabin_list", true );
            for ( $cab = 0; $cab < $cabin_list; $cab ++ ) {
                $cabinID        = get_post_meta ( $order_list->ID, "cabin_list_" . $cab . "_cabinID", true );
                $cabin_types    = get_post_meta ( $order_list->ID, "cabin_list_" . $cab . "_cabin_types", true );
                $selected_seats = get_post_meta ( $order_list->ID, "cabin_list_" . $cab . "_selected_seats", true );
                $person_details = get_post_meta ( $order_list->ID, "cabin_list_" . $cab . "_person_details", true );

                $order_data[ $i ][ 'cabin_data' ][ $cab ][ "cabin_title" ]    = get_the_title ( $cabinID );
                $order_data[ $i ][ 'cabin_data' ][ $cab ][ "cabin_type" ]     = $cabin_types;
                $order_data[ $i ][ 'cabin_data' ][ $cab ][ 'selected_seat' ]  = $selected_seats;
                $order_data[ $i ][ 'cabin_data' ][ $cab ][ 'person_details' ] = $person_details;
                //print_r($order_data[$i]['cabin_data'][$cab]['person_details']); die();
                $new_persons                                                  = [];
                foreach ( $order_data[ $i ][ 'cabin_data' ][ $cab ][ 'person_details' ] as $key => $person_val ) {
                    //echo $person_val. '<br>';

                    $pax_list = get_post_meta ( $order_list->ID, "pax_data", true );
                    for ( $paxd = 0; $paxd < $pax_list; $paxd ++ ) {

                        $person_gender       = get_post_meta ( $order_list->ID, "pax_data_" . $paxd . "_selected_gender", true );
                        $person_name         = get_post_meta ( $order_list->ID, "pax_data_" . $paxd . "_pax_name", true );
                        $person_email        = get_post_meta ( $order_list->ID, "pax_data_" . $paxd . "_pax_email", true );
                        $person_phone_number = get_post_meta ( $order_list->ID, "pax_data_" . $paxd . "_pax_phone_number", true );
                        $person_age          = get_post_meta ( $order_list->ID, "pax_data_" . $paxd . "_pax_age", true );
                        $person_number       = get_post_meta ( $order_list->ID, "pax_data_" . $paxd . "_pax_person_details", true );
                        if( $person_val == $person_number ) {
                            $person_detail = [
                                'selected_gender'     => $person_gender,
                                'person_name'         => $person_name,
                                'person_email'        => $person_email,
                                'person_phone_number' => $person_phone_number,
                                'person_age'          => $person_age,
                                'person_number'       => $person_number,
                            ];
                        }
                    }
                    $new_persons[ $key ] = $person_detail;
                }

                $order_data[ $i ][ 'cabin_data' ][ $cab ][ 'persons' ] = $new_persons;
            }

            /* 	if( have_rows('cabin_list',$order_list->ID) ): $cb_count=0; 
              while( have_rows('cabin_list',$order_list->ID) ) : the_row();
              $cabin_ids = get_sub_field('cabinID');
              $cabin_types = get_sub_field('cabin_types',$order_list->ID);
              $selected_seats = get_sub_field('selected_seats',$order_list->ID);

              $order_data[$i]['cabin_data'][$cb_count]["cabin_title"] = get_the_title($cabin_ids);
              $order_data[$i]['cabin_data'][$cb_count]["cabin_type"] = $cabin_types;
              $order_data[$i]['cabin_data'][$cb_count]['selected_seat'] = $selected_seats;
              $cb_count++;
              endwhile;
              endif; */

            if( have_rows ( 'courses_data', $order_list->ID ) ): $count = 0;
                while ( have_rows ( 'courses_data', $order_list->ID ) ) : the_row ();
                    $courses_id                                                       = get_sub_field ( 'courses_id', $order_list->ID );
                    $courses_person                                                   = get_sub_field ( 'courses_person', $order_list->ID );
                    // $order_data[$i]['courses_data'][$cb_count]["courses_id"] = $courses_id;
                    $order_data[ $i ][ 'courses_data' ][ $count ][ 'courses_title' ]  = get_the_title ( $courses_id );
                    $order_data[ $i ][ 'courses_data' ][ $count ][ 'courses_person' ] = (int) $courses_person;
                    $count ++;
                endwhile;
            endif;

            if( have_rows ( 'rental_equipment_data', $order_list->ID ) ): $count = 0;
                while ( have_rows ( 'rental_equipment_data', $order_list->ID ) ) : the_row ();
                    $rental_equipment_id                                                                = get_sub_field ( 'rental__equipment_id', $order_list->ID );
                    $rental_equipment_person                                                            = get_sub_field ( 'rental__equipment_person', $order_list->ID );
                    $order_data[ $i ][ 'rental_equipment_data' ][ $count ][ 'rental_equipment_title' ]  = get_the_title ( $rental_equipment_id );
                    $order_data[ $i ][ 'rental_equipment_data' ][ $count ][ 'rental_equipment_person' ] = (int) $rental_equipment_person;
                    $count ++;
                endwhile;
            endif;

            $i ++;
            //echo $i;
        }/* end if user id */
    }/* end foreach */

    if( ! empty ( $order_data ) ) {
        return [ 'status' => true, 'data' => $order_data, 'message' => "records found" ];
    } else {
        return [ 'status' => false, 'data' => [], 'message' => "records not found !" ];
    }
    /* print_r($order_datas);
      die(); */
}

// update user data and addons
//Insert Inro Order Data
function save_order_data_from_user_id($request) {

    $parameters = $request->get_params ();

    $order_data = json_decode ( file_get_contents ( "php://input" ), true );

    $post_id                       = $order_data[ 'order_id' ];
    $user_id                       = $order_data[ 'user_id' ];
    $courses_data                  = $order_data[ 'courses' ];
    $rental_equipment_data         = $order_data[ 'rental_equipment' ];
    $payble_amount                 = $order_data[ 'final_payble_amount' ];
    //$transaction_id = $order_data['transaction_data']['transaction_id'];
    $card_id                       = $order_data[ 'transaction_data' ][ 'card_id' ];
    //$client_ip = $order_data['transaction_data']['client_ip'];
    //$created = $order_data['transaction_data']['created'];
    $transaction_data_payment_date = date ( "d/m/Y", $created );
    //$transaction_data_client_email = $order_data['transaction_data']['email'];	
    $user_credit_used              = $order_data[ 'user_credit' ];

    // for payable amount
    $old_payble_amounts = get_post_meta ( $post_id, "payble_amount", true );
    $payble_amounts     = $old_payble_amounts + $payble_amount;

    update_post_meta ( $post_id, 'payble_amount', $payble_amounts );
    //update_post_meta($post_id, 'transaction_data_transaction_id', $transaction_id); 	
    update_post_meta ( $post_id, 'transaction_data_card_id', $card_id );
    //update_post_meta($post_id, 'transaction_data_client_ip', $client_ip); 	
    //update_post_meta($post_id, 'transaction_data_payment_date', $transaction_data_payment_date); 	
    //update_post_meta($post_id, 'transaction_data_client_email', $transaction_data_client_email); 
    update_post_meta ( $post_id, 'user_credit_used', $user_credit_used );

    $user_credit_data = get_user_meta ( $user_id, "user_credit", true );
    if( $user_credit_used != "" || $user_credit_data == "" ) {
        $final_amount = $user_credit_data - $user_credit_used;
        update_user_meta ( $user_id, 'user_credit', $final_amount );
    }
    /*    $courses_data_count = 0;
      $courses_data_count = count($courses_data); */
    $post_data_count = get_post_meta ( $post_id, "courses_data", true );
    // temp setting
    $post_data_count = 1;
    if( $post_data_count > 0 ) {
        //delete_post_meta($post_id, 'images');
        delete_post_meta ( $post_id, 'courses_data_courses_id' );
        delete_post_meta ( $post_id, '_courses_data_courses_id' );
        delete_post_meta ( $post_id, 'courses_data_courses_person' );
        delete_post_meta ( $post_id, '_courses_data_courses_person' );
        delete_post_meta ( $post_id, '_courses_data' );
        delete_post_meta ( $post_id, 'courses_data' );
        for ( $del_count = 0; $del_count < $post_data_count; $del_count ++ ) {
            delete_post_meta ( $post_id, "courses_data_" . $del_count . "_courses_id" );
            delete_post_meta ( $post_id, "_courses_data_" . $del_count . "_courses_id" );

            delete_post_meta ( $post_id, "courses_data_" . $del_count . "_courses_person" );
            delete_post_meta ( $post_id, "_courses_data_" . $del_count . "_courses_person" );
        }
    }


    /* $rental_equipment_data_count = 0;
      $rental_equipment_data_count = count($rental_equipment_data); */
    $rental_post_data_count = get_post_meta ( $post_id, "rental_equipment_data", true );
    // temp setting 
    $rental_post_data_count = 1;
    if( $rental_post_data_count > 0 ) {
        //delete_post_meta($post_id, 'images');
        delete_post_meta ( $post_id, 'rental_equipment_data_rental__equipment_id' );
        delete_post_meta ( $post_id, '_rental_equipment_data_rental__equipment_id' );
        delete_post_meta ( $post_id, 'rental_equipment_data_rental__equipment_person' );
        delete_post_meta ( $post_id, '_rental_equipment_data_rental__equipment_person' );
        delete_post_meta ( $post_id, '_rental_equipment_data' );
        delete_post_meta ( $post_id, 'rental_equipment_data' );

        for ( $re_count = 0; $re_count < $rental_post_data_count; $re_count ++ ) {
            delete_post_meta ( $post_id, "rental_equipment_data_" . $re_count . "_rental__equipment_id" );
            delete_post_meta ( $post_id, "_rental_equipment_data_" . $re_count . "_rental__equipment_id" );

            delete_post_meta ( $post_id, "rental_equipment_data_" . $re_count . "_rental__equipment_person" );
            delete_post_meta ( $post_id, "_rental_equipment_data_" . $re_count . "_rental__equipment_person" );
        }
    }



    $courses_data_count = 0;
    $courses_data__cout = count ( $courses_data );

    add_post_meta ( $post_id, 'courses_data_courses_id', "395" );
    add_post_meta ( $post_id, '_courses_data_courses_id', "field_5f6c47700da06" );
    add_post_meta ( $post_id, 'courses_data_courses_person', "394" );
    add_post_meta ( $post_id, '_courses_data_courses_person', "field_5f6c47820da07" );
    add_post_meta ( $post_id, '_courses_data', "field_5f6c47480da05" );
    add_post_meta ( $post_id, 'courses_data', $courses_data__cout );

    foreach ( $courses_data as $courses ) {
        foreach ( $courses as $key => $value ) {
            //echo "key :-> ".$key. "=====> Value:- > " .$value."<br/>";	courses_data_0_courses_id

            if( $key == "id" ) {
                $courses_data_id_meta_field  = "courses_data_" . $courses_data_count . "_courses_id";
                $courses_data_id_meta_field1 = "_courses_data_" . $courses_data_count . "_courses_id";
                $courses_data_id_value       = $value;
            }
            if( $key == "booked_course" ) {
                $courses_person_meta_field  = "courses_data_" . $courses_data_count . "_courses_person";
                $courses_person_meta_field1 = "_courses_data_" . $courses_data_count . "_courses_person";
                $courses_person_value       = $value;
            }
        }

        if( ! $courses_data_id_value == "" ) {
            add_post_meta ( $post_id, $courses_data_id_meta_field, $courses_data_id_value );

            add_post_meta ( $post_id, $courses_data_id_meta_field1, "field_5f6c514207e45" );
        }
        if( ! $courses_person_value == "" ) {
            add_post_meta ( $post_id, $courses_person_meta_field, $courses_person_value );
            add_post_meta ( $post_id, $courses_person_meta_field1, "field_5f6c47820da07" );
        }

        $courses_data_count ++;
    }

    $rental_equipment_data_count = 0;
    $rental_equipment_data__cout = count ( $rental_equipment_data );

    add_post_meta ( $post_id, 'rental_equipment_data_rental__equipment_id', "387" );
    add_post_meta ( $post_id, '_rental_equipment_data_rental__equipment_id', "field_5f6c47a20da09" );
    add_post_meta ( $post_id, 'rental_equipment_data_rental__equipment_person', "2" );
    add_post_meta ( $post_id, '_rental_equipment_data_rental__equipment_person', "field_5f6c47a20da0a" );
    add_post_meta ( $post_id, '_rental_equipment_data', "field_5f6c47a20da08" );
    add_post_meta ( $post_id, 'rental_equipment_data', $rental_equipment_data__cout );

    foreach ( $rental_equipment_data as $rental_equipment ) {
        foreach ( $rental_equipment as $key => $value ) {

            if( $key == "id" ) {
                $rental_equipment_id_meta_field  = "rental_equipment_data_" . $rental_equipment_data_count . "_rental__equipment_id";
                $rental_equipment_id_meta_field1 = "_rental_equipment_data_" . $rental_equipment_data_count . "_rental__equipment_id";
                $rental_equipment_id_value       = $value;
            }
            if( $key == "booked_rental_equipment" ) {
                $rental_equipment_person_meta_field  = "rental_equipment_data_" . $rental_equipment_data_count . "_rental__equipment_person";
                $rental_equipment_person_meta_field1 = "_rental_equipment_data_" . $rental_equipment_data_count . "_rental__equipment_person";
                $rental_equipment_person_value       = $value;
            }
        }
        if( ! $rental_equipment_id_value == "" ) {
            add_post_meta ( $post_id, $rental_equipment_id_meta_field, $rental_equipment_id_value );
            add_post_meta ( $post_id, $rental_equipment_id_meta_field1, "field_5f6c47a20da09" );
        }
        if( ! $rental_equipment_person_value == "" ) {
            add_post_meta ( $post_id, $rental_equipment_person_meta_field, $rental_equipment_person_value );
            add_post_meta ( $post_id, $rental_equipment_person_meta_field1, "field_5f6c47a20da0a" );
        }

        $rental_equipment_data_count ++;
    }

    //echo $rental_post_data_count;
    if( $post_id != "" && $user_id != "" ) {
        send_email_on_order_place ( $post_id, $user_id );
    }
    $response = array ( 'order_id' => $post_id );

    if( ! empty ( $response ) ) {
        return [ 'status' => true, 'data' => $response, 'message' => 'Order updated successfully.' ];
    } else {
        return [ 'status' => false, 'data' => [], 'message' => 'Opps! Someting went Wrong.' ];
    }
}

// Step 3.3 API destination data from country id
function order_summery_data_from_order_id($request) {

    $parameters          = $request->get_params ();
    $user_id             = sanitize_text_field ( $parameters[ 'user_id' ] );
    $order_id            = sanitize_text_field ( $parameters[ 'order_id' ] );
    $i                   = 0;
    $order_data          = [];
    $order_ID            = "";
    $order_title         = "";
    $order_bow           = "";
    $order_gallery_image = "";
    $order_beds          = "";
    $order_bathrooms     = "";
    $order_price         = "";
    //echo $user_id."<br/>";echo $order_id."<br/>";
    $args                = array ( 'post_type' => 'orders', 'posts_per_page' => -1 );
    $order_datas         = get_posts ( $args );
    //print_r($order_datas);
    foreach ( $order_datas as $order_list ) {

        $order_ID                = $order_list->ID;
        $order_title             = $order_list->post_title;
        $vessel_id               = get_field ( 'vessel_id', $order_list->ID );
        $country_id              = get_field ( 'country_id', $order_list->ID );
        $destination_id          = get_field ( 'destination_id', $order_list->ID );
        $trip_date_id            = get_field ( 'trip_date_id', $order_list->ID );
        $total_person            = get_field ( 'total_person', $order_list->ID );
        $coupon_data_coupon_id   = get_field ( 'coupon_data_coupon_id', $order_list->ID );
        $coupon_data_coupon_code = get_field ( 'coupon_data_coupon_code', $order_list->ID );
        //$agent_data_agent_id = get_field('agent_data_agent_id',$order_list->ID);
        //$agent_data_agent_code = get_field('agent_data_agent_code',$order_list->ID);
        $payble_amount           = get_field ( 'payble_amount', $order_list->ID );

        $order_meta_user_ID = get_field ( 'user_id', $order_list->ID );

        $key         = 'user_credit';
        $single      = true;
        $user_credit = get_user_meta ( $order_meta_user_ID, $key, $single );
        //echo $order_meta_user_ID."<br/>";echo $order_ID."<br/>";

        if( $order_meta_user_ID == $user_id && $order_id == $order_ID ) {
            //echo $user_id."<br/>";echo $order_id."<br/>";
            $country                        = get_term_by ( 'id', $country_id, 'country' );
            $itternary_post_data_price      = get_field ( 'diverace_itinerary_price', $trip_date_id );
            $itternary_post_data_total_DN   = get_field ( 'diverace_itinerary_total_days_and_nights', $trip_date_id );
            $itternary_post_data_start_date = get_field ( 'dive_start_date', $trip_date_id );
            $itternary_post_data_end_date   = get_field ( 'dive_end_date', $trip_date_id );
            $itternary_post_data_summary    = $itternary_post_data_start_date . " to " . $itternary_post_data_end_date . " " . $itternary_post_data_total_DN . " | & Days from &s " . $itternary_post_data_price;

            $order_data[ $i ][ 'id' ]                      = $order_list->ID;
            $order_data[ $i ][ 'order_title' ]             = $order_title;
            $order_data[ $i ][ 'vessel_title' ]            = get_the_title ( $vessel_id );
            $order_data[ $i ][ 'itinery_title' ]           = get_the_title ( $destination_id );
            $order_data[ $i ][ 'country_title' ]           = $country->name;
            $order_data[ $i ][ 'itternary_price' ]         = (int) $itternary_post_data_price;
            $order_data[ $i ][ 'itternary_total_DN' ]      = $itternary_post_data_total_DN;
            $order_data[ $i ][ 'itternary_start_date' ]    = $itternary_post_data_start_date;
            $order_data[ $i ][ 'itternary_end_date' ]      = $itternary_post_data_end_date;
            $order_data[ $i ][ 'itternary_date_summary' ]  = $itternary_post_data_summary;
            $order_data[ $i ][ 'coupon_title' ]            = get_the_title ( $coupon_data_coupon_id );
            $order_data[ $i ][ 'coupon_data_coupon_code' ] = $coupon_data_coupon_code;
            //$order_data[$i]['agent_title']= get_the_title($agent_data_agent_id); 
            //$order_data[$i]['agent_data_agent_code']= $agent_data_agent_code; 
            $order_data[ $i ][ 'payble_amount' ]           = $payble_amount;
            $order_data[ $i ][ 'total_person' ]            = (int) $total_person;
            $order_data[ $i ][ 'user_credit' ]             = $user_credit;

            $cabin_list = get_post_meta ( $order_list->ID, "cabin_list", true );
            for ( $cab = 0; $cab < $cabin_list; $cab ++ ) {
                $cabinID        = get_post_meta ( $order_list->ID, "cabin_list_" . $cab . "_cabinID", true );
                $cabin_types    = get_post_meta ( $order_list->ID, "cabin_list_" . $cab . "_cabin_types", true );
                $selected_seats = get_post_meta ( $order_list->ID, "cabin_list_" . $cab . "_selected_seats", true );
                $person_details = get_post_meta ( $order_list->ID, "cabin_list_" . $cab . "_person_details", true );

                $order_data[ $i ][ 'cabin_data' ][ $cab ][ "cabin_title" ]    = get_the_title ( $cabinID );
                $order_data[ $i ][ 'cabin_data' ][ $cab ][ "cabin_type" ]     = $cabin_types;
                $order_data[ $i ][ 'cabin_data' ][ $cab ][ 'selected_seat' ]  = $selected_seats;
                $order_data[ $i ][ 'cabin_data' ][ $cab ][ 'person_details' ] = $person_details;
                //print_r($order_data[$i]['cabin_data'][$cab]['person_details']); die();
                $new_persons                                                  = [];
                foreach ( $order_data[ $i ][ 'cabin_data' ][ $cab ][ 'person_details' ] as $key => $person_val ) {
                    //echo $person_val. '<br>';

                    $pax_list = get_post_meta ( $order_list->ID, "pax_data", true );
                    for ( $paxd = 0; $paxd < $pax_list; $paxd ++ ) {

                        $person_gender       = get_post_meta ( $order_list->ID, "pax_data_" . $paxd . "_selected_gender", true );
                        $person_name         = get_post_meta ( $order_list->ID, "pax_data_" . $paxd . "_pax_name", true );
                        $person_email        = get_post_meta ( $order_list->ID, "pax_data_" . $paxd . "_pax_email", true );
                        $person_phone_number = get_post_meta ( $order_list->ID, "pax_data_" . $paxd . "_pax_phone_number", true );
                        $person_age          = get_post_meta ( $order_list->ID, "pax_data_" . $paxd . "_pax_age", true );
                        $person_number       = get_post_meta ( $order_list->ID, "pax_data_" . $paxd . "_pax_person_details", true );
                        if( $person_val == $person_number ) {
                            $person_detail = [
                                'selected_gender'     => $person_gender,
                                'person_name'         => $person_name,
                                'person_email'        => $person_email,
                                'person_phone_number' => $person_phone_number,
                                'person_age'          => $person_age,
                                'person_number'       => $person_number,
                            ];
                        }
                    }
                    $new_persons[ $key ] = $person_detail;
                }

                $order_data[ $i ][ 'cabin_data' ][ $cab ][ 'persons' ] = $new_persons;
            }


            if( have_rows ( 'courses_data', $order_list->ID ) ): $count = 0;
                while ( have_rows ( 'courses_data', $order_list->ID ) ) : the_row ();
                    $courses_id                                                       = get_sub_field ( 'courses_id', $order_list->ID );
                    $courses_person                                                   = get_sub_field ( 'courses_person', $order_list->ID );
                    $order_data[ $i ][ 'courses_data' ][ $count ][ 'courses_id' ]     = $courses_id;
                    $order_data[ $i ][ 'courses_data' ][ $count ][ 'courses_title' ]  = get_the_title ( $courses_id );
                    $order_data[ $i ][ 'courses_data' ][ $count ][ 'courses_person' ] = (int) $courses_person;
                    $count ++;
                endwhile;
            endif;

            if( have_rows ( 'rental_equipment_data', $order_list->ID ) ): $count = 0;
                while ( have_rows ( 'rental_equipment_data', $order_list->ID ) ) : the_row ();
                    $rental_equipment_id                                                                = get_sub_field ( 'rental__equipment_id', $order_list->ID );
                    $rental_equipment_person                                                            = get_sub_field ( 'rental__equipment_person', $order_list->ID );
                    $order_data[ $i ][ 'rental_equipment_data' ][ $count ][ 'rental_equipment_title' ]  = get_the_title ( $rental_equipment_id );
                    $order_data[ $i ][ 'rental_equipment_data' ][ $count ][ 'rental_equipment_person' ] = (int) $rental_equipment_person;
                    $count ++;
                endwhile;
            endif;

            $i ++;
            //echo $i;
        }/* end if user id */
    }/* end foreach */

    if( ! empty ( $order_data ) ) {
        return [ 'status' => true, 'data' => $order_data, 'message' => "records found" ];
    } else {
        return [ 'status' => false, 'data' => [], 'message' => "records not found !" ];
    }
    /* print_r($order_datas);
      die(); */
}

/* function send_email_on_order_placed(){

  send_email_on_order_place(677,49);
  //send_email_on_order_updated(672,49);
  } */

// Step 3.4 API for order cancle from user
function order_cancle_from_user_id($request) {
    global $wpdb;
    $parameters = $request->get_params ();
    $user_id    = sanitize_text_field ( $parameters[ 'user_id' ] );
    $order_id   = sanitize_text_field ( $parameters[ 'order_id' ] );
    $order_data = [];
    if( ! empty ( $user_id ) && ! empty ( $order_id ) ) {
        $order_status = get_field ( 'order_status', $order_id );

        $vessel_id      = get_field ( 'vessel_id', $order_id );
        $destination_id = get_field ( 'destination_id', $order_id );
        $trip_date_id   = get_field ( 'trip_date_id', $order_id );

        $start_date      = get_field ( 'dive_start_date', $trip_date_id );
        $trip_start_date = str_replace ( '/', '-', $start_date );

        $trip_start_date_year = date ( "Y-m-d", strtotime ( $trip_start_date ) );
        $trip_start_time      = strtotime ( $trip_start_date_year );

        $today_date = date ( "Y-m-d" );
        $today_time = strtotime ( $today_date );

        $time_different = $trip_start_time - $today_time;

        if( $time_different <= 86400 ) {
            $order_data[ 'message' ] = 'Your order cancellation is before 24 hours only.';
        } else {
            if( $order_status != 'Cancelled' ) {
                $order_cancelled = 'Cancelled';
                update_post_meta ( $order_id, 'order_status', $order_cancelled );

                $order_table_name = $wpdb->prefix . 'custom_order_details';

                $wpdb->update (
                        $order_table_name,
                        array (
                            'status' => $order_cancelled
                        ),
                        array (
                            'order_id'    => $order_id,
                            'user_id'     => $user_id,
                            'order_trash' => 'NO'
                        )
                );

                $order_data[ 'user_id' ]        = $user_id;
                $order_data[ 'order_id' ]       = $order_id;
                $order_data[ 'vessel_id' ]      = $vessel_id;
                $order_data[ 'destination_id' ] = $destination_id;
                $order_data[ 'tripDate_id' ]    = $trip_date_id;
                $order_data[ 'order_status' ]   = $order_cancelled;
                $order_data[ 'message' ]        = 'Your order has been successfully cancelled.';
                return [ 'status' => true, 'data' => $order_data ];
            } else {
                return [ 'status' => false, 'data' => [], 'message' => "Your order already cancelled " ];
            }
        }
    }
    if( ! empty ( $order_data ) ) {
        return [ 'status' => true, 'data' => $order_data ];
    } else {
        return [ 'status' => false, 'data' => [], 'message' => "records not found !" ];
    }
}

// Step 4.1 API for get all Pending orders of 10 percent pay to send zapier
function get_all_pending_payment_orders_of_10_percent_pay_send_to_zapier() {
    $args = array (
        'post_type'      => 'orders',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => array (
            'relation' => 'AND',
            array (
                'key'     => 'order_status',
                'value'   => 'Pending',
                'compare' => '=',
            ),
        ),
    );

    $order_datas = get_posts ( $args );

    $i          = 0;
    $order_data = [];

    foreach ( $order_datas as $order_list ) {

        $order_id    = $order_list->ID;
        $order_title = $order_list->post_title;

        $user_id           = get_field ( 'user_id', $order_id );
        $user_info         = get_userdata ( $user_id );
        $user_first_name   = get_the_author_meta ( 'first_name', $user_id );
        $user_last_name    = get_the_author_meta ( 'last_name', $user_id );
        $user_phone_number = get_the_author_meta ( 'user_phone_number', $user_id );
        $user_email        = $user_info->user_email;

        $vessel_id        = get_field ( 'vessel_id', $order_id );
        $destination_id   = get_field ( 'destination_id', $order_id );
        $trip_date_id     = get_field ( 'trip_date_id', $order_id );
        $vessel_name      = get_the_title ( $vessel_id );
        $destination_name = get_the_title ( $destination_id );
        $trip_name        = get_the_title ( $trip_date_id );

        $payble_amount       = get_field ( 'payble_amount', $order_id );
        $partial_amount      = get_field ( 'partial_amount', $order_id );
        $partial_amount_type = get_field ( 'partial_amount_type', $order_id );
        $remaining_amount    = get_field ( 'remaining_amount', $order_id );
        $order_status        = get_field ( 'order_status', $order_id );

        $trip_start_date = get_field ( 'dive_start_date', $trip_date_id );
        $trip_end_date   = get_field ( 'dive_end_date', $trip_date_id );

        if( ! empty ( $order_id ) ) {
            if( $partial_amount_type == '10' ) {
                $order_data[ $i ][ 'order_id' ]    = $order_id;
                $order_data[ $i ][ 'order_title' ] = $order_title;

                $order_data[ $i ][ 'user_first_name' ]   = $user_first_name;
                $order_data[ $i ][ 'user_last_name' ]    = $user_last_name;
                $order_data[ $i ][ 'user_email' ]        = $user_email;
                $order_data[ $i ][ 'user_phone_number' ] = $user_phone_number;

                $order_data[ $i ][ 'vessel_name' ]      = $vessel_name;
                $order_data[ $i ][ 'destination_name' ] = $destination_name;
                $order_data[ $i ][ 'trip_name' ]        = $trip_name;

                $order_data[ $i ][ 'trip_start_date' ] = $trip_start_date;
                $order_data[ $i ][ 'trip_end_date' ]   = $trip_end_date;

                $order_data[ $i ][ 'payble_amount' ]       = $payble_amount;
                $order_data[ $i ][ 'partial_amount' ]      = $partial_amount;
                $order_data[ $i ][ 'partial_amount_type' ] = $partial_amount_type;
                $order_data[ $i ][ 'remaining_amount' ]    = $remaining_amount;
                $order_data[ $i ][ 'order_status' ]        = $order_status;
            }
        }

        $i ++;
    }

    if( ! empty ( $order_data ) ) {
        return [ 'status' => true, 'data' => $order_data ];
    } else {
        return [ 'status' => false, 'data' => [], 'message' => "records not found !" ];
    }
}

// Step 4.2 API for get all Pending orders of 50 percent pay to send zapier
function get_all_pending_payment_orders_of_50_percent_pay_send_to_zapier() {
    $args = array (
        'post_type'      => 'orders',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => array (
            'relation' => 'AND',
            array (
                'key'     => 'order_status',
                'value'   => 'Pending',
                'compare' => '=',
            ),
        ),
    );

    $order_datas = get_posts ( $args );

    $i          = 0;
    $order_data = [];

    foreach ( $order_datas as $order_list ) {

        $order_id    = $order_list->ID;
        $order_title = $order_list->post_title;

        $user_id           = get_field ( 'user_id', $order_id );
        $user_info         = get_userdata ( $user_id );
        $user_first_name   = get_the_author_meta ( 'first_name', $user_id );
        $user_last_name    = get_the_author_meta ( 'last_name', $user_id );
        $user_phone_number = get_the_author_meta ( 'user_phone_number', $user_id );
        $user_email        = $user_info->user_email;

        $vessel_id        = get_field ( 'vessel_id', $order_id );
        $destination_id   = get_field ( 'destination_id', $order_id );
        $trip_date_id     = get_field ( 'trip_date_id', $order_id );
        $vessel_name      = get_the_title ( $vessel_id );
        $destination_name = get_the_title ( $destination_id );
        $trip_name        = get_the_title ( $trip_date_id );

        $payble_amount       = get_field ( 'payble_amount', $order_id );
        $partial_amount      = get_field ( 'partial_amount', $order_id );
        $partial_amount_type = get_field ( 'partial_amount_type', $order_id );
        $remaining_amount    = get_field ( 'remaining_amount', $order_id );
        $order_status        = get_field ( 'order_status', $order_id );

        $trip_start_date = get_field ( 'dive_start_date', $trip_date_id );
        $trip_end_date   = get_field ( 'dive_end_date', $trip_date_id );

        if( ! empty ( $order_id ) ) {
            if( $partial_amount_type == '50' ) {
                $order_data[ $i ][ 'order_id' ]    = $order_id;
                $order_data[ $i ][ 'order_title' ] = $order_title;

                $order_data[ $i ][ 'user_first_name' ]   = $user_first_name;
                $order_data[ $i ][ 'user_last_name' ]    = $user_last_name;
                $order_data[ $i ][ 'user_email' ]        = $user_email;
                $order_data[ $i ][ 'user_phone_number' ] = $user_phone_number;

                $order_data[ $i ][ 'vessel_name' ]      = $vessel_name;
                $order_data[ $i ][ 'destination_name' ] = $destination_name;
                $order_data[ $i ][ 'trip_name' ]        = $trip_name;

                $order_data[ $i ][ 'trip_start_date' ] = $trip_start_date;
                $order_data[ $i ][ 'trip_end_date' ]   = $trip_end_date;

                $order_data[ $i ][ 'payble_amount' ]       = $payble_amount;
                $order_data[ $i ][ 'partial_amount' ]      = $partial_amount;
                $order_data[ $i ][ 'partial_amount_type' ] = $partial_amount_type;
                $order_data[ $i ][ 'remaining_amount' ]    = $remaining_amount;
                $order_data[ $i ][ 'order_status' ]        = $order_status;
            }
        }

        $i ++;
    }

    if( ! empty ( $order_data ) ) {
        return [ 'status' => true, 'data' => $order_data ];
    } else {
        return [ 'status' => false, 'data' => [], 'message' => "records not found !" ];
    }
}

// Step 4.3 API for get all Complete orders to send zapier
function get_all_complete_payment_orders_send_to_zapier() {
    $args = array (
        'post_type'      => 'orders',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => array (
            'relation' => 'AND',
            array (
                'key'     => 'order_status',
                'value'   => 'Completed',
                'compare' => '=',
            ),
        ),
    );

    $order_datas = get_posts ( $args );

    $i          = 0;
    $order_data = [];

    foreach ( $order_datas as $order_list ) {

        $order_id    = $order_list->ID;
        $order_title = $order_list->post_title;

        $user_id           = get_field ( 'user_id', $order_id );
        $user_info         = get_userdata ( $user_id );
        $user_first_name   = get_the_author_meta ( 'first_name', $user_id );
        $user_last_name    = get_the_author_meta ( 'last_name', $user_id );
        $user_phone_number = get_the_author_meta ( 'user_phone_number', $user_id );
        $user_email        = $user_info->user_email;

        $vessel_id        = get_field ( 'vessel_id', $order_id );
        $destination_id   = get_field ( 'destination_id', $order_id );
        $trip_date_id     = get_field ( 'trip_date_id', $order_id );
        $vessel_name      = get_the_title ( $vessel_id );
        $destination_name = get_the_title ( $destination_id );
        $trip_name        = get_the_title ( $trip_date_id );

        $payble_amount       = get_field ( 'payble_amount', $order_id );
        $partial_amount      = get_field ( 'partial_amount', $order_id );
        $partial_amount_type = get_field ( 'partial_amount_type', $order_id );
        $remaining_amount    = get_field ( 'remaining_amount', $order_id );
        $order_status        = get_field ( 'order_status', $order_id );

        $trip_start_date = get_field ( 'dive_start_date', $trip_date_id );
        $trip_end_date   = get_field ( 'dive_end_date', $trip_date_id );

        if( ! empty ( $order_id ) && $order_status == 'Completed' ) {
            $order_data[ $i ][ 'order_id' ]    = $order_id;
            $order_data[ $i ][ 'order_title' ] = $order_title;

            $order_data[ $i ][ 'user_first_name' ]   = $user_first_name;
            $order_data[ $i ][ 'user_last_name' ]    = $user_last_name;
            $order_data[ $i ][ 'user_email' ]        = $user_email;
            $order_data[ $i ][ 'user_phone_number' ] = $user_phone_number;

            $order_data[ $i ][ 'vessel_name' ]      = $vessel_name;
            $order_data[ $i ][ 'destination_name' ] = $destination_name;
            $order_data[ $i ][ 'trip_name' ]        = $trip_name;

            $order_data[ $i ][ 'trip_start_date' ] = $trip_start_date;
            $order_data[ $i ][ 'trip_end_date' ]   = $trip_end_date;

            $order_data[ $i ][ 'payble_amount' ]       = $payble_amount;
            $order_data[ $i ][ 'partial_amount' ]      = $partial_amount;
            $order_data[ $i ][ 'partial_amount_type' ] = $partial_amount_type;
            $order_data[ $i ][ 'remaining_amount' ]    = $remaining_amount;
            $order_data[ $i ][ 'order_status' ]        = $order_status;
        }

        $i ++;
    }

    if( ! empty ( $order_data ) ) {
        return [ 'status' => true, 'data' => $order_data ];
    } else {
        return [ 'status' => false, 'data' => [], 'message' => "records not found !" ];
    }
}

// Step 4.4 API for get all orders to trip is completed send zapier
function get_all_orders_to_trip_is_completed_send_to_zapier() {
    $args = array (
        'post_type'      => 'orders',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => array (
            'relation' => 'AND',
            array (
                'key'     => 'order_status',
                'value'   => 'Trip Completed',
                'compare' => '=',
            ),
        ),
    );

    $order_datas = get_posts ( $args );

    $i          = 0;
    $order_data = [];

    foreach ( $order_datas as $order_list ) {

        $order_id    = $order_list->ID;
        $order_title = $order_list->post_title;

        $user_id           = get_field ( 'user_id', $order_id );
        $user_info         = get_userdata ( $user_id );
        $user_first_name   = get_the_author_meta ( 'first_name', $user_id );
        $user_last_name    = get_the_author_meta ( 'last_name', $user_id );
        $user_phone_number = get_the_author_meta ( 'user_phone_number', $user_id );
        $user_email        = $user_info->user_email;

        $vessel_id        = get_field ( 'vessel_id', $order_id );
        $destination_id   = get_field ( 'destination_id', $order_id );
        $trip_date_id     = get_field ( 'trip_date_id', $order_id );
        $vessel_name      = get_the_title ( $vessel_id );
        $destination_name = get_the_title ( $destination_id );
        $trip_name        = get_the_title ( $trip_date_id );

        $payble_amount       = get_field ( 'payble_amount', $order_id );
        $partial_amount      = get_field ( 'partial_amount', $order_id );
        $partial_amount_type = get_field ( 'partial_amount_type', $order_id );
        $remaining_amount    = get_field ( 'remaining_amount', $order_id );
        $order_status        = get_field ( 'order_status', $order_id );

        $trip_start_date = get_field ( 'dive_start_date', $trip_date_id );
        $trip_end_date   = get_field ( 'dive_end_date', $trip_date_id );

        if( ! empty ( $order_id ) && $order_status == 'Trip Completed' ) {
            $order_data[ $i ][ 'order_id' ]    = $order_id;
            $order_data[ $i ][ 'order_title' ] = $order_title;

            $order_data[ $i ][ 'user_first_name' ]   = $user_first_name;
            $order_data[ $i ][ 'user_last_name' ]    = $user_last_name;
            $order_data[ $i ][ 'user_email' ]        = $user_email;
            $order_data[ $i ][ 'user_phone_number' ] = $user_phone_number;

            $order_data[ $i ][ 'vessel_name' ]      = $vessel_name;
            $order_data[ $i ][ 'destination_name' ] = $destination_name;
            $order_data[ $i ][ 'trip_name' ]        = $trip_name;

            $order_data[ $i ][ 'trip_start_date' ] = $trip_start_date;
            $order_data[ $i ][ 'trip_end_date' ]   = $trip_end_date;

            $order_data[ $i ][ 'payble_amount' ]       = $payble_amount;
            $order_data[ $i ][ 'partial_amount' ]      = $partial_amount;
            $order_data[ $i ][ 'partial_amount_type' ] = $partial_amount_type;
            $order_data[ $i ][ 'remaining_amount' ]    = $remaining_amount;
            $order_data[ $i ][ 'order_status' ]        = $order_status;
        }

        $i ++;
    }

    if( ! empty ( $order_data ) ) {
        return [ 'status' => true, 'data' => $order_data ];
    } else {
        return [ 'status' => false, 'data' => [], 'message' => "records not found !" ];
    }
}

// Step 5.1 API for Strip strip_add_card_api for Add new card for existing customer
function strip_add_card_api($request) {
    $api_mode_live__test = get_field ( 'api_mode_live__test', 'option' );
    if( $api_mode_live__test == true ) {
        $strip_api_key = get_field ( 'live_api_key', 'option' );
    } else {
        $strip_api_key = get_field ( 'test_api_key', 'option' );
    }
    $stripe = new \Stripe\StripeClient ( [
        "api_key" => $strip_api_key
            ] );

    $response   = array ();
    $parameters = $request->get_params ();

    $user_id          = sanitize_text_field ( $parameters[ 'user_id' ] );
    //$card_number = esc_html($parameters['card_number']);
    //$card_expiry_date = esc_html($parameters['card_expiry_date']);
    //$card_expiry_date_final = explode('/',$card_expiry_date);
    //$card_cvc = esc_html($parameters['card_cvc']);              
    $user_description = 'Test Customer - Diverace';

    $enter_token_id = esc_html ( $parameters[ 'token' ] );

    $stripe_customer_id = get_the_author_meta ( 'stripe_customer_id', $user_id );

    if( ! empty ( $stripe_customer_id ) ) {
        $stripe_customer_id = $stripe_customer_id;
    } else {
        $CreateCustomerStrip = $stripe->customers->create ( [
            'description' => $user_description,
            'email'       => $user_email,
            'name'        => $user_description,
                ] );

        $stripe_customer_id = $CreateCustomerStrip[ 'id' ];

        if( ! empty ( $stripe_customer_id ) ) {
            update_user_meta ( $user_id, 'stripe_customer_id', $stripe_customer_id );
        }
    }

    $error = '';
    try {
        /* $strip_token = $stripe->tokens->create([
          'card' => [
          'number' => $card_number,
          'exp_month' => $card_expiry_date_final[0],
          'exp_year' => $card_expiry_date_final[1],
          'cvc' => $card_cvc,
          ],
          ]); */
    } catch (Stripe_CardError $e) {
        $error = $e->getMessage ();
    } catch (Stripe_InvalidRequestError $e) {
        // Invalid parameters were supplied to Stripe's API
        $error = $e->getMessage ();
    } catch (Stripe_AuthenticationError $e) {
        // Authentication with Stripe's API failed
        $error = $e->getMessage ();
    } catch (Stripe_ApiConnectionError $e) {
        // Network communication with Stripe failed
        $error = $e->getMessage ();
    } catch (Stripe_Error $e) {
        // Display a very generic error to the user, and maybe send
        // yourself an email
        $error = $e->getMessage ();
    } catch (Exception $e) {
        // Something else happened, completely unrelated to Stripe
        $error = $e->getMessage ();
    }
    if( ! empty ( $error ) ) {
        $message = $error;
        $mType   = 'error';
    } else {
        $token_id = $enter_token_id;

        $add_new_card = $stripe->customers->createSource (
                $stripe_customer_id,
                [ 'source' => $token_id ]
        );

        $new_card_id = $add_new_card[ 'id' ];
    }
    if( ! empty ( $new_card_id ) ) {
        return [ 'status' => true, 'data' => $new_card_id, 'msg' => 'Your card has been added successfully.' . $message ];
    } else {
        return [ 'status' => false, 'data' => '', 'msg' => $message ];
    }
}

// Step 5.2 API for Strip strip_card_listing_api for Add new card for existing customer
function strip_card_listing_api($request) {
    $api_mode_live__test = get_field ( 'api_mode_live__test', 'option' );
    if( $api_mode_live__test == true ) {
        $strip_api_key = get_field ( 'live_api_key', 'option' );
    } else {
        $strip_api_key = get_field ( 'test_api_key', 'option' );
    }
    $stripe = new \Stripe\StripeClient ( [
        "api_key" => $strip_api_key
            ] );

    $response   = array ();
    $parameters = $request->get_params ();

    $user_id = sanitize_text_field ( $parameters[ 'user_id' ] );

    $stripe_customer_id = get_the_author_meta ( 'stripe_customer_id', $user_id );

    if( ! empty ( $stripe_customer_id ) ) {
        $list_all_cards = $stripe->customers->allSources (
                $stripe_customer_id,
                [ 'object' => 'card' ]
        );
        $all_cards      = $list_all_cards->data;
        return [ 'status' => true, 'msg' => "Record Found.", 'data' => $all_cards ];
    } else {
        $record_data = 'record_data';
        return [ 'status' => false, 'data' => "Record Not Found", 'message' => 'Something went wrong.' ];
    }
}

// Step 5.2 API for Strip strip_card_remove_api for Add new card for existing customer
function strip_card_remove_api($request) {
    $api_mode_live__test = get_field ( 'api_mode_live__test', 'option' );
    if( $api_mode_live__test == true ) {
        $strip_api_key = get_field ( 'live_api_key', 'option' );
    } else {
        $strip_api_key = get_field ( 'test_api_key', 'option' );
    }
    $stripe = new \Stripe\StripeClient ( [
        "api_key" => $strip_api_key
            ] );

    $response   = array ();
    $parameters = $request->get_params ();

    $user_id            = sanitize_text_field ( $parameters[ 'user_id' ] );
    $card_id_is         = sanitize_text_field ( $parameters[ 'card_id' ] );
    $stripe_customer_id = get_the_author_meta ( 'stripe_customer_id', $user_id );

    if( ! empty ( $stripe_customer_id ) ) {
        $delete_card = $stripe->customers->deleteSource (
                $stripe_customer_id,
                $card_id_is,
                []
        );

        $delete_card_id = $delete_card[ 'id' ];
        if( ! empty ( $delete_card_id ) ) {
            return [ 'status' => true, 'msg' => "Your card is deleted successfully.", 'data' => $delete_card_id ];
        } else {
            return [ 'status' => true, 'msg' => "Card Found.", 'data' => "" ];
        }
    } else {
        return [ 'status' => false, 'data' => "Record Not Found", 'message' => 'Something went wrong.' ];
    }
}
