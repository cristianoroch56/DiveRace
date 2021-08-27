<?php	
/*
Plugin Name: Custom API
Description: Create custom custom API plugin made by WappNet
Version: 1
Author: https://wappnet.com/
Author URI: https://wappnet.com/
*/

global $wpdb;
include 'orderplace-email.php';
include 'orderupdated-email.php';
include 'send-register-mailto-user.php';
include 'send-register-mailto-admin.php';
include 'send-forgot-password-mailto-user.php';
function create_custom_booking_function() {

    global $wpdb;
    $table_name = $wpdb->prefix. "custom_order_details";
    global $charset_collate;
    $charset_collate = $wpdb->get_charset_collate();
    global $db_version;
    global $create_sql;
    if( $wpdb->get_var("SHOW TABLES LIKE '" . $table_name . "'") !=  $table_name)
    { 
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
    dbDelta( $create_sql );


}
add_action( 'init', 'create_custom_booking_function');




header( 'Access-Control-Allow-Headers: *');
header( 'Access-Control-Allow-Origin: *' );
header( 'Access-Control-Allow-Methods: *' );
header( 'Access-Control-Allow-Credentials: true' );
//header( 'Access-Control-Allow-Origin: ' . esc_url_raw( site_url() ) );
/*function add_cors_http_header(){
	header( 'Access-Control-Allow-Headers: *');
    header( 'Access-Control-Allow-Origin: *' );
    header( 'Access-Control-Allow-Methods: *' );
    header( 'Access-Control-Allow-Credentials: true' );
}
add_action('init','add_cors_http_header');*/


add_action('rest_api_init', function(){
	register_rest_route('custom-api/v1', 'wp_login_api',[
		'methods' => 'POST',
    	'callback' => 'wp_login_api',    	
	]);
	register_rest_route('custom-api/v1', 'wp_singup_api',[
		'methods' => 'POST',
    	'callback' => 'wp_singup_api',    	
	]);
	register_rest_route('custom-api/v1', 'forgot_password',[
		'methods' => 'POST',
    	'callback' => 'forgot_password',    	
	]);
	register_rest_route('custom-api/v1', 'get_vessel_data',[
		'methods' => 'GET',
    	'callback' => 'get_vessel_data',
	]);
	register_rest_route('custom-api/v1', 'get_country_data',[
		'methods' => 'POST',
    	'callback' => 'get_country_data',
	]);	
	register_rest_route('custom-api/v1', 'get_itinerary_areas_from_country_data',[
		'methods' => 'POST',
    	'callback' => 'get_itinerary_areas_from_country_data',
	]);
	register_rest_route('custom-api/v1', 'get_date_from_itinerary_data',[
		'methods' => 'POST',
    	'callback' => 'get_date_from_itinerary_data',
	]);	
	register_rest_route('custom-api/v1', 'get_itinerary_schedule_data',[
		'methods' => 'POST',
    	'callback' => 'get_itinerary_schedule_data',
	]);	
	register_rest_route('custom-api/v1', 'get_pax_data',[
		'methods' => 'GET',
    	'callback' => 'get_pax_data',
	]);	
	register_rest_route('custom-api/v1', 'get_cabins_from_vessel_id_data',[
		'methods' => 'POST',
    	'callback' => 'get_cabins_from_vessel_id_data',
	]);	
	register_rest_route('custom-api/v1', 'get_courses_data',[
		'methods' => 'GET',
    	'callback' => 'get_courses_data',
	]);
	register_rest_route('custom-api/v1', 'get_rental_equipment_data',[
		'methods' => 'GET',
    	'callback' => 'get_rental_equipment_data',
	]);
	register_rest_route('custom-api/v1', 'get_coupon_data',[
		'methods' => 'POST',
    	'callback' => 'get_coupon_data',
	]);
	register_rest_route('custom-api/v1', 'get_agent_data',[
		'methods' => 'POST',
    	'callback' => 'get_agent_data',
	]);
	register_rest_route('custom-api/v1', 'add_order_data',[
		'methods' => 'POST',
    	'callback' => 'add_order_data',
	]);
	register_rest_route('custom-api/v1', 'edit_courses_data_from_order_id',[
		'methods' => 'POST',
    	'callback' => 'edit_courses_data_from_order_id',
	]);
	register_rest_route('custom-api/v1', 'edit_rental_equipment_data_from_order_id',[
		'methods' => 'POST',
    	'callback' => 'edit_rental_equipment_data_from_order_id',
	]);
	register_rest_route('custom-api/v1', 'order_summery_data_from_order_id',[
		'methods' => 'POST',
    	'callback' => 'order_summery_data_from_order_id',
	]);
	register_rest_route('custom-api/v1', 'view_order_data_from_user_id',[
		'methods' => 'POST',
    	'callback' => 'view_order_data_from_user_id',
	]);
	register_rest_route('custom-api/v1', 'save_order_data_from_user_id',[
		'methods' => 'POST',
    	'callback' => 'save_order_data_from_user_id',
	]);
	register_rest_route('custom-api/v1', 'send_email_on_order_placed',[
		'methods' => 'POST',
    	'callback' => 'send_email_on_order_placed',
	]);
	register_rest_route('custom-api/v1', 'wp_userbalance_api',[
		'methods' => 'POST',
    	'callback' => 'wp_userbalance_api',
	]);

	

});

//Login API
function wp_login_api( $request ){

	$response = array();
	$parameters = $request->get_params();

	$username = sanitize_text_field( $parameters['username'] );
	$password = sanitize_text_field( $parameters['password'] );

	// Error Handling.
	$error = new WP_Error();
	if ( empty( $username ) ) {
	
		$response['status'] = false;
		$response['data'] = [];
		$response['message'] = 'Username field is required';

		return $response;
	}
	if ( empty( $password ) ) {
		
		$response['status'] = false;
		$response['data'] = [];
		$response['message'] = 'Password field is required';

		return $response;
	}

    $user = wp_authenticate( $username, $password  );

	// If user found
	if ( ! is_wp_error( $user ) ) {
		$response['status'] = true;

		//$response['user'] = $user;
        $user_id = $user->ID;
        
		$key = 'user_credit';
		$single = true;
		$user_credit = get_user_meta( $user_id, $key, $single ); 

		
		$user_phone_number = get_user_meta($user_id, 'user_phone_number',true);
		$user_age = get_user_meta($user_id, 'user_age',true);
		

		if($user_credit !=""){
			$user_credit = get_user_meta( $user_id, $key, $single ); 
		}else{
			$user_credit = 0;
		}

		if($user_phone_number !=""){
			$user_phone_number = get_user_meta($user_id, 'user_phone_number',true); 
		}else{
			$user_phone_number = '';
		}

		if($user_age !=""){
			$user_age = get_user_meta($user_id, 'user_age',true); 
		}else{
			$user_age = '';
		}

		$user->data->user_credit = (int)$user_credit ;
		$user->data->user_phone_number = $user_phone_number ;
		$user->data->user_age = $user_age ;
		$response['user'] = $user;

		/*echo "<pre>";
		print_r($user->data->user_login);
		die();*/		
			
	} else {
		// If user not found
		$response['status'] = false;
		$response['data'] = [];
		$response['message'] = 'User not found. Check credentials';

	}
	

	unset($response['user']->data->user_pass);
	unset($response['user']->data->user_activation_key);

	return $response;
		
}
// Get user credit balance 

function wp_userbalance_api( $request ){

     $response = array();
	$parameters = $request->get_params();

	$user_id = sanitize_text_field( $parameters['user_id'] );
	if($user_id){


		$key = 'user_credit';
		$single = true;
		$user_credit = get_user_meta( $user_id, $key, $single ); 
		if($user_credit !=""){
			$user_credit = get_user_meta( $user_id, $key, $single ); 
		}else{
			$user_credit = 0;
		}
		$response['status'] = true;
		$response['user_credit'] = $user_credit;
		return $response;


	} 

}
// wp_singup_api
function wp_singup_api( $request ){

	$response = array();
	$parameters = $request->get_params();
	
	$firstname = sanitize_text_field( $parameters['firstname'] );
	$lastname = sanitize_text_field( $parameters['lastname'] );
	/*$username = sanitize_text_field( $parameters['username'] );*/

	$lower_str = strtolower($firstname);
	$new_username = str_replace(' ', '', $lower_str);


	$email = sanitize_text_field( $parameters['email'] );
	$password = sanitize_text_field( $parameters['password'] );
	$confirm_password = sanitize_text_field( $parameters['confirm_password'] );
	//$role = 'subscriber';
	//$role = sanitize_text_field($parameters['role']);	
	$role = "subscriber";
	// Error Handling.
	$error = new WP_Error();
	if ( empty( $firstname ) ) {
		$response['status'] = false;
		$response['data']['data'] = [];
		$response['message'] = 'First Name field is required';

		return $response;
	}
	if ( empty( $lastname ) ) {
		$response['status'] = false;
		$response['data'] = [];
		$response['message'] = 'Last Name field is required';

		return $response;
	}
	/*if ( empty( $username ) ) {
		$response['status'] = false;
		$response['data'] = [];
		$response['message'] = 'Username field is required';

		return $response;
	}*/
	$error = new WP_Error();
	if ( empty( $email ) ) {
		$response['status'] = false;
		$response['data'] = [];
		$response['message'] = 'Email field is required';

		return $response;
	}
	if ( empty( $password ) ) {
		$response['status'] = false;
		$response['data'] = [];
		$response['message'] = 'Password field is required';
		return $response;
	}
	if ( empty( $confirm_password ) ) {
		$response['status'] = false;
		$response['data'] = [];
		$response['message'] = 'Confirm Password field is required';
		return $response;
	}
	
	if ( $password != $confirm_password ) {
		$response['status'] = false;
		$response['data'] = [];
		$response['message'] = 'Your password and confirmation password do not match.';
		return $response;
	}
	
	if (empty($role)) {				
		$response['status'] = false;
		$response['data'] = [];
		$response['message'] = 'Role field is required';
		return $response;		
	} else {
		if ($GLOBALS['wp_roles']->is_role($role)) {
			if ( $role == 'administrator' || $role == 'editor' || $role == 'author') {
				//$error->add(406, __("Role field 'role' is not a permitted. Only 'contributor', 'subscriber' and your custom roles are allowed.", 'wp_rest_user'), array('status' => 400));
				//return $error;

				$response['status'] = false;
				$response['data'] = [];
				$response['message'] = 'Role field "role" is not a permitted. Only "contributor", "subscriber" and your custom roles are allowed.';
				return $response;
			}
		} else {
			//$error->add(405, __("Role field 'role' is not a valid. Check your User Roles from Dashboard.", 'wp_rest_user'), array('status' => 400));
			//return $error;
				$response['status'] = false;
				$response['data'] = [];
				$response['message'] = "Role field 'role' is not a valid. Check your User Roles from Dashboard.";
				return $response;
		}
	}

	//$user_id = username_exists($new_username);
	$user_id = email_exists($email);
	if (!$user_id && email_exists($email) == false) {
		$user_id = wp_create_user($new_username, $password, $email);
		if (!is_wp_error($user_id)) {
			// Ger User Meta Data (Sensitive, Password included. DO NOT pass to front end.)
			$user = get_user_by('id', $user_id);
			$user->set_role($role);
			$response['status'] = true;
		
	        $user_id = $user->ID;
			$key = 'user_credit';
			$single = true;
			$user_credit = get_user_meta( $user_id, $key, $single ); 

			wp_update_user([
			    'ID' => $user_id, // this is the ID of the user you want to update.
			    'first_name' => $firstname,
			    'last_name' => $lastname,
			]);
			if($user_credit !=""){
				$user_credit = get_user_meta( $user_id, $key, $single ); 
			}else{
				$user_credit =0;
			}
			$user->data->user_credit = (int)$user_credit ;
			$response['status'] = true;
			$response['user'] = $user;

			$blogusers = get_user_by('id', 1);
		    foreach ($blogusers as $user) {
		        $admin_email =  $user->user_email;
		        $user_login =  $user->user_login;
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
		}*/

		      
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
			}*/

			 //user email
			send_register_mailto_user($user_id);

		} else {
			return $user_id;
		}
	}
	 else if ($user_id) {

		//$error->add(406, __("Username already exists, please enter another username", 'wp-rest-user'), array('status' => 400));
		//return $error;
		$response['status'] = false;
		$response['data'] = [];
		$response['message'] = __("Username already exists, please enter another username", "wp-rest-user");
		return $response;

	}else {
		/*$error->add(406, __("Email already exists, please try 'Reset Password'", 'wp-rest-user'), array('status' => 400));
		return $error;*/

		$response['status'] = false;
		$response['data'] = [];
		$response['message'] = __("Username and Email Id already exists.", "wp-rest-user");
		return $response;
	}

	unset($response['user']->data->user_pass);
	unset($response['user']->data->user_activation_key);
	return $response;		
}
/**
// ForgotPassword
*/
function forgot_password($request) {

	$response = array();
	$parameters = $request->get_params();	
	$useremail = sanitize_text_field( $parameters['useremail'] );
	$user = get_user_by( 'email', $useremail );
	$user_ID = $user->ID;	
	$error = new WP_Error();
	$data = [];
	
	if ( !empty( $user_ID ) ) {
		//echo $useremail;
		//send_forgote_password_mailto_user($useremail);
		$key = get_password_reset_key($user);
	    $rp_link = '<a href="' . site_url() . "/wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login) . '">' . site_url() . "/wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login) . '';
	    /*echo $rp_link;
	    die();*/
	    function wpdocs_set_html_mail_content_type() {
	      return 'text/html';
	    }


		$headers = array('Content-Type: text/html; charset=UTF-8');
		$message .='
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
						              <td class="es-m-p0l es-m-txt-c" align="center" style="padding:0;Margin:0;padding-left:15px;font-size:0px"><a href="http://68.183.80.245/diverace/#/" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:open sans-serif, sans-serif;font-size:14px;text-decoration:underline;color:#999999"><img src="'.site_url().'/wp-content/uploads/2020/05/logo@2x.png" alt="DiveRACE" title="DiveRACE" width="73" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td> 
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
			                          <td align="center" style="padding:0;Margin:0;padding-top:10px;padding-bottom:15px"><h1 style="Margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:open sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#333333">Hello '.$user_name.',</h1></td> 
			                         </tr> 
			                         <tr style="border-collapse:collapse"> 
			                          <td align="center" style="Margin:0;padding-top:5px;padding-bottom:5px;padding-left:40px;padding-right:40px"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif, sans-serif;line-height:21px;color:#333333">New user registred on site. <br></p><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif, sans-serif;line-height:21px;color:#333333">Click here in order to reset your password:<br><br>' . $rp_link.'</p></td> 
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
			                          <td class="es-m-p0l es-m-txt-c" align="center" style="padding:0;Margin:0;font-size:0px"><a href="http://68.183.80.245/diverace" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:open sans-serif, sans-serif;font-size:14px;text-decoration:underline;color:#333333"><img src="'.site_url().'/wp-content/uploads/2020/05/logo@2x.png" alt="DiveRace" title="DiveRace" width="68" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td> 
			                         </tr> 
			                         <tr style="border-collapse:collapse"> 
			                          <td class="es-m-txt-c" align="center" style="padding:0;Margin:0;padding-top:5px"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif, sans-serif;line-height:21px;color:#333333">©'.date('Y').' DiveRACE. All rights rederved.</p></td> 
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


    	add_filter('wp_mail_content_type', 'wpdocs_set_html_mail_content_type');
    	$email_successful = wp_mail($useremail, 'Reset password', $message, $headers);
    	// Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
    	remove_filter('wp_mail_content_type', 'wpdocs_set_html_mail_content_type');
    	/*if ($email_successful) {      */
		$data['status'] = true;
		$data['data']['id'] = $user_ID;
		$data['message'] = __("Reset Password link has been sent to your email. Please check your email.", "wp-rest-user");
		return $data;
	} else{
		$data['status'] = false;
		$data['data'] = [];
		$data['message'] = 'Does not valid user email.';
		return $data;		
	}
	
	
	//send_forgote_password_mailto_user($useremail);
	// ==============================================================

	/*if ($email_successful) {*/
		/*$data[$i]['id'] = $user_ID;*/
		/*$data['status'] = true;
        $data['data']['id'] = $user_ID;
		$data['message'] = __("Reset Password link has been sent to your email.", "wp-rest-user");*/
	/*} else {
		//$error->add(402, __("Failed to send Reset Password email. Check your WordPress Hosting Email Settings.", 'wp-rest-user'), array('status' => 402));
		$data['status'] = false;
		$data['data']['status'] = false;
		$data['data']['message'] = __("Failed to send Reset Password email. Check your WordPress Hosting Email Settings.", "wp-rest-user");
		return $data;
	}*/
	/*if(!empty($data)){
		//return ['status'=>true,'data'=>$data];
		return $data;
	}
	else{
		//return ['status'=>false ,'data'=>[]];
		return $data;
	}*/

	//return $response;	
}

// Step 1 API vessel data
function get_vessel_data(){
	$args = [
		'numberposts' => 99999,
		'post_type' => 'vessel',
	];

	$posts  = get_posts($args);
	$data = [];
	$vessel_ID="";
	$vessel_title="";
	$vessel_featured_image="";
	$vessel_vessels_icons="";

	$i=0;

	foreach ($posts as $post) {
		$vessel_ID = $post->ID;
		$vessel_title = $post->post_title;
		$vessel_featured_image = get_the_post_thumbnail_url($post->ID,'full');
		$vessel_vessels_icons = get_field('vessels_icons', $post->ID);

		$data[$i]['id'] = $vessel_ID;
		$data[$i]['title'] = $vessel_title;
		$data[$i]['featured_image'] = $vessel_featured_image;
		$data[$i]['vessels_icons'] = $vessel_vessels_icons;
		$i++;		
	}
	if(!empty($data)){
		return ['status'=>true,'data'=>$data];
	}
	else{
		return ['status'=>false ,'data'=>[]];
	}
	
}

// Step 2 API country data
function get_country_data(){

	 $args = array(
               'taxonomy' => 'country',
               'orderby' => 'name',
               'order'   => 'ASC',
       		   'hide_empty' => 1,
           );

	$posts  = get_categories($args);	
	$data = [];
	$country_ID="";
	$country_title="";
	$country_img="";
	$i=0;
	foreach ($posts as $post) {
		$country_ID=$post->term_id;
		$country_title=$post->name;
		$country_img= get_field('country_image','country_'.$post->term_id);

/*		if(!empty($country_ID) && !empty($country_title)){*/

			$data[$i]['id'] = $country_ID;
			$data[$i]['title'] = $country_title;
			$data[$i]['country_img'] = $country_img;

		$i++;	
	/*	}*/
	
	}
	if(!empty($data)){
		return ['status'=>true,'data'=>$data];
	}
	else{
		return ['status'=>false ,'data'=>[]];
	}
}

// Step 2.2 API destination data from country id
function get_itinerary_areas_from_country_data( $request ){
	
	$parameters = $request->get_params();
	$country_id = sanitize_text_field( $parameters['country_id'] );
		
	$destination_args = array(
		'posts_per_page' => -1,
		'post_type' => 'destination',
		'orderby' => 'date',
        'order'   => 'DESC', 
        'tax_query' => array(
		    array(
		    'taxonomy' => 'country',
		    'field' => 'term_id',
		    'terms' => $country_id 
		     )
		  )      
	);

	$destination_posts  = get_posts($destination_args);
	$data = [];
	$destination_ID="";
	$destination_title="";
	$i=0;
	foreach ($destination_posts as $post) {
		$destination_ID=$post->ID;
		$destination_title=$post->post_title;
		if(!empty($destination_ID) && !empty($destination_title)){
			$data[$i]['id'] = $destination_ID;
			$data[$i]['title'] = $destination_title;
			$i++;	
		}

			
	}
	if(!empty($data)){
		return ['status'=>true,'data'=>$data];
	}
	else{
		return ['status'=>false ,'data'=>[]];
	}

}


// Step 2.3 API itinerary data from destination id
function get_date_from_itinerary_data( $request ){
	global $wpdb;
	$parameters = $request->get_params();
	$destination_id = sanitize_text_field( $parameters['destination_id'] );
		
	$destination_post_data  = get_post($destination_id, ARRAY_A);

	$destination_itineraries_relation = get_field('diverace_destination_itineraries', $destination_id);
	$destination_post_data['destination_itineraries']=$destination_itineraries_relation;

	

	$itternary_post_data_ID ="";
	$itternary_post_data_title ="";
	$itternary_post_data_price ="";
	$itternary_post_data_total_DN ="";
	$itternary_post_data_start_date ="";
	$itternary_post_data_end_date ="";
	$itternary_post_data_summary ="";


	$data = [];
	$i=0;
	foreach($destination_post_data['destination_itineraries'] as $post)
	{
	
	$itternary_post_data= get_post($post);
		
	if ($itternary_post_data->post_status == 'publish') {
		$itternary_post_data_ID = $itternary_post_data->ID;

		$results = $wpdb->get_results( "SELECT tripDate_id FROM custom_order_details  WHERE tripDate_id=".$itternary_post_data_ID." AND order_trash = 'NO'");
		
		$itternary_post_data_title =$itternary_post_data->post_title;
		$itternary_post_data_price = get_field('diverace_itinerary_price',$itternary_post_data->ID);
		$itternary_post_data_total_DN =get_field('diverace_itinerary_total_days_and_nights',$itternary_post_data->ID);
		$start_date = get_field('dive_start_date',$itternary_post_data->ID);
		$date_start_date = str_replace('/', '-', $start_date );
		$end_date = get_field('dive_end_date',$itternary_post_data->ID);
		$date_end_date = str_replace('/', '-', $end_date );
		$today_date = date("d-m-Y");
		$curdate=strtotime($today_date);
		$startdate=strtotime($date_start_date);
		$enddate=strtotime($date_end_date);
		$itternary_post_data_start_date = date("j F", strtotime($date_start_date));
		$itternary_post_data_end_date = date("j F", strtotime($date_end_date));

		$itternary_post_data_start_date_year = date("Y-m-d", strtotime($date_start_date));
		$itternary_post_data_end_date_year = date("Y-m-d", strtotime($date_end_date));

		$itternary_post_data_summary = $itternary_post_data_start_date." to ". $itternary_post_data_end_date." ".$itternary_post_data_total_DN." from S$ "        .$itternary_post_data_price;
		
			//if($results[$i]->tripDate_id != $itternary_post_data_ID){
				if($curdate < $startdate && $curdate < $enddate ){ 
					$data[$i]['id']=  $itternary_post_data_ID;
					$data[$i]['title']=  $itternary_post_data_title;
					$data[$i]['diverace_itinerary_price']= $itternary_post_data_price;
					$data[$i]['dive_total_days_nights']= $itternary_post_data_total_DN;
					$data[$i]['dive_start_date']= $itternary_post_data_start_date;
					$data[$i]['dive_start_date_year']= $itternary_post_data_start_date_year;
					$data[$i]['dive_end_date']= $itternary_post_data_end_date;
					$data[$i]['dive_end_date_year']= $itternary_post_data_end_date_year;
					$data[$i]['summary']= $itternary_post_data_summary;
						$i++;
				}
				else{

					/*if(!empty($itternary_post_data_price)&& !empty($itternary_post_data_total_DN) && !empty($itternary_post_data_start_date) && !empty($itternary_post_data_end_date) ){
						*/
							/*$data[$i]['id']=  $itternary_post_data_ID;
							$data[$i]['title']=  $itternary_post_data_title;
							$data[$i]['diverace_itinerary_price']= $itternary_post_data_price;
							$data[$i]['dive_total_days_nights']= $itternary_post_data_total_DN;
							$data[$i]['dive_start_date']= $itternary_post_data_start_date;
							$data[$i]['dive_end_date']= $itternary_post_data_end_date;
							$data[$i]['summary']= $itternary_post_data_summary;
								$i++;*/
												 	
				      /* }*/
			        }
		      //	}

		} 
	}
	if(!empty($data)){
		return ['status'=>true,'data'=>$data];
	}
	else{
		return ['status'=>false ,'data'=>[],'message' => "Expired"];
	}

}

// Step 3.1 API country data
function get_pax_data(){

	 $args = array(
               'taxonomy' => 'pax',
               'orderby' => 'name',
               'order'   => 'ASC',
       		   'hide_empty' => 1,
           );

	$posts  = get_categories($args);	
	$data = [];
	$i=0;

	$pax_ID ="";
	$pax_title ="";
	foreach ($posts as $post) {
		$pax_ID =$post->term_id;
		$pax_title = $post->name;

		  if(!empty($pax_ID)){$data[$i]['id'] = $pax_ID;}
		  if(!empty($pax_title)){$data[$i]['title'] =$pax_title;}
		
		
		//$data[$i]['country_img'] = get_field('country_image','country_'.$post->term_id);

		$i++;		
	}
	if(!empty($data)){
		return ['status'=>true,'data'=>$data];
	}
	else{
		return ['status'=>false ,'data'=>[]];
	}
}


// Step 3.3 API destination data from country id
function get_cabins_from_vessel_id_data( $request ){
	global $wpdb;
	$parameters = $request->get_params();
	$vessel_id = sanitize_text_field( $parameters['vessel_id'] );

	
	$tripDate_type = sanitize_text_field( $parameters['tripDate_id'] );
	/*$order_id = sanitize_text_field( $parameters['order_id'] );
	$cabin_data = get_post_meta($order_id,"cabin_data",true);*/
	$i=0;		
	$count=0;		
	$vessel_cabin_data = [];
	$vessel_cabin_ID ="";
	$vessel_cabin_title ="";
	$vessel_cabin_bow ="";
	$vessel_cabin_gallery_image ="";
	$vessel_cabin_beds ="";
	$vessel_cabin_bathrooms="";
	$vessel_cabin_price="";

	if( have_rows('diverace_vessel_cabins', $vessel_id) ):
 		while( have_rows('diverace_vessel_cabins', $vessel_id) ): the_row(); 
			$cabins_IDs = get_sub_field('cabins'); 
			foreach($cabins_IDs as $cabins){

			//echo "cabinID from start-> ".$cabins .", ";
			$order_table_name = $wpdb->prefix.'custom_order_details';
			$results = $wpdb->get_results( "SELECT * FROM ".$order_table_name." WHERE cabin_id = ".$cabins." AND tripDate_id = ".$tripDate_type." AND order_trash = 'NO'");
				//print_r($results);
				 if(count($results)==0){
				/*
				foreach($results as $keys => $values) {
					$cabin_seats_value  = $values->cabin_seat;
				}
				echo "seats-> ".$cabin_seats_value.", <br>";*/
						//echo "<br/> cabin_result ID -> ".$cabins .", ";
						$args = array(
							'post_type' => 'cabin',
					    	'p' => $cabins, 
						     'meta_query' => array(
						        array(
						            'key' => 'for_how_many_persons',
						            'value' => $pax_person,
						            'compare' => 'LIKE'
						        )
						    )
						);
							$cabins_datas= get_posts($args);	
							foreach($cabins_datas as $cabins_data){
									$vessel_cabin_ID = $cabins_data->ID;
									$vessel_cabin_title =$cabins_data->post_title;
									$vessel_cabin_bow = get_field('bow',$cabins_data->ID);
									$vessel_cabin_gallery_image  = get_field('gallery',$cabins_data->ID);
									$vessel_cabin_beds = get_field('beds',$cabins_data->ID);
									$vessel_cabin_bathrooms = get_field('bathrooms',$cabins_data->ID);
									$vessel_cabin_price = get_field('cabin_price',$cabins_data->ID);
									$cabin_persons = get_field('for_how_many_persons',$cabins_data->ID);
									//$cabin_pax_seats = get_field('cabin_pax_seats',$cabins_data->ID);		

									if(!empty($vessel_cabin_price) && !empty($vessel_cabin_title)) {
										/*if($cabin_pax_seats == "full" || $cabin_pax_seats == "both"){}
										else{*/
										if(!empty($vessel_cabin_ID)){ $vessel_cabin_data[$i]['id']= $vessel_cabin_ID;}
									       if($vessel_cabin_title){ $vessel_cabin_data[$i]['title']= $vessel_cabin_title;}
									       if($vessel_cabin_bow){ $vessel_cabin_data[$i]['bow']= $vessel_cabin_bow;}
									       if($vessel_cabin_gallery_image){ $vessel_cabin_data[$i]['gallery_image']= $vessel_cabin_gallery_image;}
									       if($vessel_cabin_beds){ $vessel_cabin_data[$i]['beds']= $vessel_cabin_beds ; }
									       if($vessel_cabin_bathrooms){ $vessel_cabin_data[$i]['bathrooms']= $vessel_cabin_bathrooms ; }
									       if($vessel_cabin_price){ $vessel_cabin_data[$i]['cabin_price']= $vessel_cabin_price;}
									       if($cabin_persons){ $vessel_cabin_data[$i]['pax_for_persons']= $cabin_persons;}
									       /*if(!empty($cabin_seats_value))
									       	{ 
									       		$vessel_cabin_data[$i]['seat']= $cabin_seats_value;
									       	}else{*/
									       		$vessel_cabin_data[$i]['seat']= "empty";
									       	/*}*/
										$i++;
										/*}*/
								      
								       
								     }       
							
								} 
								continue;
					}

				foreach($results as $keys => $values) {
					$cabin_seats_value  = $values->cabin_seat;
						if($cabin_seats_value != "both" && count($results)<2){
							$args = array(
								'post_type' => 'cabin',
						    	'p' => $cabins, 
							     'meta_query' => array(
							        array(
							            'key' => 'for_how_many_persons',
							            'value' => $pax_person,
							            'compare' => 'LIKE'
							        )
							    )
							);
						$cabins_datas= get_posts($args);	
						foreach($cabins_datas as $cabins_data){
								$vessel_cabin_ID = $cabins_data->ID;
								$vessel_cabin_title =$cabins_data->post_title;
								$vessel_cabin_bow = get_field('bow',$cabins_data->ID);
								$vessel_cabin_gallery_image  = get_field('gallery',$cabins_data->ID);
								$vessel_cabin_beds = get_field('beds',$cabins_data->ID);
								$vessel_cabin_bathrooms = get_field('bathrooms',$cabins_data->ID);
								$vessel_cabin_price = get_field('cabin_price',$cabins_data->ID);
								$cabin_persons = get_field('for_how_many_persons',$cabins_data->ID);
								//$cabin_pax_seats = get_field('cabin_pax_seats',$cabins_data->ID);		

								if(!empty($vessel_cabin_price) && !empty($vessel_cabin_title)) {
									/*if($cabin_pax_seats == "full" || $cabin_pax_seats == "both"){}
									else{*/
									if(!empty($vessel_cabin_ID)){ $vessel_cabin_data[$i]['id']= $vessel_cabin_ID;}
								       if($vessel_cabin_title){ $vessel_cabin_data[$i]['title']= $vessel_cabin_title;}
								       if($vessel_cabin_bow){ $vessel_cabin_data[$i]['bow']= $vessel_cabin_bow;}
								       if($vessel_cabin_gallery_image){ $vessel_cabin_data[$i]['gallery_image']= $vessel_cabin_gallery_image;}
								       if($vessel_cabin_beds){ $vessel_cabin_data[$i]['beds']= $vessel_cabin_beds ; }
								       if($vessel_cabin_bathrooms){ $vessel_cabin_data[$i]['bathrooms']= $vessel_cabin_bathrooms ; }
								       if($vessel_cabin_price){ $vessel_cabin_data[$i]['cabin_price']= $vessel_cabin_price;}
								       if($cabin_persons){ $vessel_cabin_data[$i]['pax_for_persons']= $cabin_persons;}
								       if(!empty($cabin_seats_value))
								       	{ 
								       		$vessel_cabin_data[$i]['seat']= $cabin_seats_value;
								       	}else{
								       		$vessel_cabin_data[$i]['seat']= "empty";
								       	}
									$i++;
									/*}*/
							      
							       
							     }       
						
							} 
							$count++;
						}else{}
				} 


			}

		endwhile;
	endif;
		
	if(!empty($vessel_cabin_data)){
		return ['status'=>true,'data'=>$vessel_cabin_data];
	}
	else{
		return ['status'=>false ,'data'=>[]];
	}

}


// Step 4.1 API course data from course id
function get_courses_data(){

	$course_args = array(
		'posts_per_page' => -1,
		'post_type' => 'course',
		'orderby' => 'date',
        'order'   => 'DESC'    
	);
	$course_post_data  = get_posts($course_args);
	$i=0;
	$data=[];	
	$course_post_ID ="";
	$course_post_title ="";
	$course_post_image ="";
	$course_post_price="";
	foreach ($course_post_data as $post) {

	$course_post_ID =$post->ID;
	$course_post_title =$post->post_title;
	$course_post_image =get_the_post_thumbnail_url($post->ID,'full');
	$course_post_price= get_field('course_price',$post->ID);
if(!empty($course_post_title) && !empty($course_post_price)) {
	if(!empty($course_post_ID)) {$data[$i]['id'] = $course_post_ID;}
	if(!empty($course_post_title)) {$data[$i]['title'] = $course_post_title;}
	if(!empty($course_post_image)) {$data[$i]['featured_image'] = $course_post_image;}
	if(!empty($course_post_price)) {$data[$i]['course_price'] = $course_post_price;}
	$i++;	
}	
	}
		
	//return $course_post_data;
	if(!empty($data)) {
		return ['status'=>true,'data'=>$data];
	}
	else{
		return ['status'=>false ,'data'=>[]];
	}	
}


// Step 4.2 API course data from Rental Equipment
function get_rental_equipment_data(){

	$rental_equipment_args = array(
		'posts_per_page' => -1,
		'post_type' => 'rental_equipment',
		'orderby' => 'date',
        'order'   => 'DESC'    
	);
	$rental_equipment_data  = get_posts($rental_equipment_args);
	$i=0;
	$data=[];

	$rental_equipment_ID ="";
	$rental_equipment_title ="";
	$rental_equipment_price="";
	foreach ($rental_equipment_data as $post) {



	$rental_equipment_ID =$post->ID;
	$rental_equipment_title =$post->post_title;
	$rental_equipment_term= get_field('rental_equipment_term',$post->ID);
	$rental_equipment_price= get_field('rental_equipment_price',$post->ID);
if(!empty($rental_equipment_title) && !empty($rental_equipment_price)) {
	if(!empty($rental_equipment_ID)) {$data[$i]['id'] = $rental_equipment_ID;}
	if(!empty($rental_equipment_title)) {$data[$i]['title'] = $rental_equipment_title;}
	if(!empty($rental_equipment_term)) {$data[$i]['rental_equipment_term'] = $rental_equipment_term;}
	if(!empty($rental_equipment_price)) {$data[$i]['rental_equipment_price'] = (int)$rental_equipment_price;}
		$i++;	
}	
	}
		
	//return $course_post_data;
	if(!empty($data)) {
		return ['status'=>true,'data'=>$data];
	}
	else{
		return ['status'=>false ,'data'=>[]];	
	}	
}



// Step 4.2 API course data from Rental Equipment
function get_coupon_data($request){
global $wpdb;
$parameters = $request->get_params();
$coupon_code = sanitize_text_field( $parameters['coupon_code'] );
$user_id = sanitize_text_field( $parameters['user_id'] );
	$coupon_args = array(
		'posts_per_page' => -1,
		'post_type' => 'coupons',
		'orderby' => 'date',
        'order'   => 'DESC' ,
	    'meta_query' => array(
        array(
            'key' => 'coupon_code',
            'value' => $coupon_code,
            'compare' => '='
        )
    )
	);
	$coupon_data  = get_posts($coupon_args);
	/*echo "TREE<pre>";
	print_r($coupon_data);*/
	$i=0;
	$data=[];
$order_table_name = $wpdb->prefix.'custom_order_details';
/*echo "uid->. ".$user_id."<br/>";
echo "coupon_code->. ".$coupon_code."<br/>";*/
$results = $wpdb->get_results("SELECT * FROM ".$order_table_name." WHERE user_id = ".$user_id." AND coupon_code = '".$coupon_code."' AND order_trash = 'NO'");

//print_r($results);
if(count($results)!=0){
	
	$data['coupon_status']= false;					
	$data['message'] = "Sorry You have already applied this code.";
	return ['status'=>false ,'data'=>$data];	
}


	if(count($coupon_data) != 0){
		foreach ($coupon_data as $post) {
	        $start_date= get_field('coupon_validity_start_date',$post->ID);
	        $end_date= get_field('coupon_validity_end_date',$post->ID);
			$coupon_ID = $post->ID;
		    $coupon_data_code= get_field('coupon_code',$post->ID);	        
		    $coupon_percentage= get_field('coupon_discount_percentage',$post->ID);
		    $coupon_status= get_field('coupon_status',$post->ID);
			$today_date = date("d/m/Y");
			/*echo "<pre>";
			print_r($start_date);
			echo "<pre>";*/
			$today_date = str_replace('/', '-', $today_date);
			$start_date = str_replace('/', '-', $start_date);
			$end_date = str_replace('/', '-', $end_date);
			$curdate=strtotime($today_date);
			$mydate=strtotime($start_date);
			$enddate=strtotime($end_date);

			/*echo $curdate ." Today date ==> ".$today_date."<br/>";
			echo $mydate ." start_date date ==> ".$start_date."<br/>";
			echo $enddate ." end date ==> ".$end_date."<br/>";
			if (($today_date >= $start_date) && ($today_date <= $end_date)){
				echo $today_date;

			die();
			}*/

					
			if($coupon_status == 1 ) {
				if (($curdate >= $mydate) && ($today_date <= $enddate)){
					    $data['id']= $post->ID;						    
					    $data['coupon_code']= $coupon_data_code;						    
						$data['coupon_status'] = $coupon_status ;   
						$data['coupon_discount_percentage']= $coupon_percentage;					
						$data['message'] = "Promocode applied successfully !";
					}
					else{
					     $data['coupon_status'] = false;
					     $data['message'] = "Promocode was expired!";
					}
			}
			else{
				$data['coupon_status'] = false;
				$data['message'] = "Invalid promocode !";
			}
					
		}
	}
	else{
		 $data['coupon_status'] = false;
		 $data['message'] = "Invalid promocode !";
	}	

		
	//return $course_post_data;
	if(!empty($data)) {
		return ['status'=>true,'data'=>$data];
	}
	else{
		return ['status'=>false ,'data'=>[]];	
	}	
}



// Step 4.3 Agent Code data
function get_agent_data($request){
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
	/*echo "TREE<pre>";
	print_r($agent_data);*/
	$i=0;
	$data=[];
/*$results = $wpdb->get_results("SELECT * FROM ".$order_table_name." WHERE user_id = ".$user_id." AND agent_code = '".$agent_code."' AND order_trash = 'NO'");*/

//print_r($results);
/*if(count($results)!=0){
return ['status'=>false ,'data'=>[],'message' => 'Sorry You have already applied this code.'];	
}*/
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
}




function edit_courses_data_from_order_id($request){
wp_reset_postdata();
$parameters = $request->get_params();
$order_ids = sanitize_text_field( $parameters['order_id'] );
$user_id = sanitize_text_field( $parameters['user_id'] );

$courses_data = get_post_meta($order_ids,"courses_data",true);
$total_person = get_post_meta($order_ids,"total_person",true);
/*$args = array(
    'post__in' => array($order_ids),
    'post_type' => 'orders',
);*/
$course_ids_list = array();
//$order_post_data = get_posts($args);
for ($i=0; $i<$courses_data ; $i++) { 
	$meta_key_id = "courses_data_".$i."_courses_id";
	$meta_key_person = "courses_data_".$i."_courses_person";
	$courses_meta_data_id = get_post_meta($order_ids,$meta_key_id,true);
	$courses_meta_data_person = get_post_meta($order_ids,$meta_key_person,true);
	$course_ids_list[$courses_meta_data_id]= $courses_meta_data_person;
}
//$userID = get_post_meta($order_ids,"user_id",true);

/*print_r($course_ids_list);
die();*/
$course_args = array(
		'posts_per_page' => -1,
		'post_type' => 'course',
		'orderby' => 'date',
        'order'   => 'DESC' ,
	);
	$course_post_data  = get_posts($course_args);
	$i=0;
	$data=[];	
	$course_post_ID ="";
	$course_post_title ="";
	$course_post_image ="";
	$course_post_price="";
	if(!empty($user_id)){
		foreach ($course_post_data as $post) {
			$course_post_ID =$post->ID;
			$course_post_title =$post->post_title;
			$course_post_image =get_the_post_thumbnail_url($post->ID,'full');
			$course_post_price= get_field('course_price',$post->ID);
			if(!empty($course_post_title) && !empty($course_post_price) ) {
				/*if($course_ids_list[$course_post_ID] != $total_person){*/
					if(!empty($course_post_ID)) {$data[$i]['id'] = $course_post_ID;}
					if(!empty($course_post_title)) {$data[$i]['title'] = $course_post_title;}
					if(!empty($course_post_image)) {$data[$i]['featured_image'] = $course_post_image;}
					if(!empty($course_post_price)) {$data[$i]['course_price'] = (int)$course_post_price;}
						if (array_key_exists($course_post_ID, $course_ids_list)){ 
							$data[$i]['booked_course'] = (int)$course_ids_list[$course_post_ID]; 
							$data[$i]['total_person'] = (int)$total_person; 
						}else{
							$data[$i]['booked_course'] = 0; 
							$data[$i]['total_person'] = (int)$total_person; 
						}
						$i++;	
				/*}*/	
			}
		}
	}
			
		//return $course_post_data;
		if(!empty($data)) {
		return ['status'=>true,'data'=>$data,'message'=>"records found"];
	}
	else{
		return ['status'=>false ,'data'=>[],'message'=>"records not found !"];	
		
	}
}

//Rental Equipment Data Edit API 

function edit_rental_equipment_data_from_order_id($request){
wp_reset_postdata();
$parameters = $request->get_params();
$order_ids = sanitize_text_field( $parameters['order_id'] );
$user_id = sanitize_text_field( $parameters['user_id'] );

$rental_equipment_data = get_post_meta($order_ids,"rental_equipment_data",true);
$total_person = get_post_meta($order_ids,"total_person",true);

$rental_equipment_ids_list = array();
//$order_post_data = get_posts($args);
for ($i=0; $i<$rental_equipment_data ; $i++) { 
	$meta_key_id = "rental_equipment_data_".$i."_rental__equipment_id";
	$meta_key_person = "rental_equipment_data_".$i."_rental__equipment_person";
	$rental_equipment_meta_data_id = get_post_meta($order_ids,$meta_key_id,true);
	$rental_equipment_meta_data_person = get_post_meta($order_ids,$meta_key_person,true);
	$rental_equipment_ids_list[$rental_equipment_meta_data_id]= $rental_equipment_meta_data_person;
}
//$user_id = get_post_meta($order_ids,"user_id",true);
/*echo "<pre>";
print_r($rental_equipment_ids_list);
die();*/
$rental_equipment_args = array(
		'posts_per_page' => -1,
		'post_type' => 'rental_equipment',
		'orderby' => 'date',
        'order'   => 'DESC'    
	);
	$rental_equipment_data  = get_posts($rental_equipment_args);
	$i=0;
	$data=[];

	$rental_equipment_ID ="";
	$rental_equipment_title ="";
	$rental_equipment_price="";

	if(!empty($user_id)){
		foreach ($rental_equipment_data as $post) {
			$rental_equipment_ID =$post->ID;
			$rental_equipment_title =$post->post_title;
			$rental_equipment_term= get_field('rental_equipment_term',$post->ID);
			$rental_equipment_price= get_field('rental_equipment_price',$post->ID);
			if(!empty($rental_equipment_title) && !empty($rental_equipment_price)) {
				/*if($course_ids_list[$course_post_ID] != $total_person){*/
					if(!empty($rental_equipment_ID)) {$data[$i]['id'] = $rental_equipment_ID;}
					if(!empty($rental_equipment_title)) {$data[$i]['title'] = $rental_equipment_title;}
					if(!empty($rental_equipment_term)) {$data[$i]['rental_equipment_term'] = $rental_equipment_term;}
					if(!empty($rental_equipment_price)) {$data[$i]['rental_equipment_price'] = (int)$rental_equipment_price;}
					if (array_key_exists($rental_equipment_ID, $rental_equipment_ids_list)){ 
						$data[$i]['booked_rental_equipment'] = (int)$rental_equipment_ids_list[$rental_equipment_ID];  
						$data[$i]['total_person'] = (int)$total_person; 
					}else{
						$data[$i]['booked_rental_equipment'] = 0; 
						$data[$i]['total_person'] = 0; 
					}
				$i++;	
				/*}	*/
			}
		}
	}
		
	//return $course_post_data;
	if(!empty($data)) {
		return ['status'=>true,'data'=>$data,'message'=>"records found"];
	}
	else{
		return ['status'=>false ,'data'=>[],'message'=>"records not found !"];	
		
	}	
}
// Step 4.3 Agent Code data
/*function get_user_credit_data($request){

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
}*/


//Insert Inro Order Data
function add_order_data($request){
 	global $wpdb;
	$parameters = $request->get_params();

	$order_data = json_decode(file_get_contents("php://input"), true);


	$user_id = $order_data['user_id'];
	$vessel_id = $order_data['vessel_type'];
	$country_id = $order_data['country_type'];
	$itinery_id = $order_data['itineraryArea_type'];
	$trip_date_id = $order_data['tripDate_type'];
	$total_person = $order_data['passenger'];
	$coupon_id = $order_data['coupon_id'];
	$coupon_code = $order_data['coupon_code'];
	$agent_id = $order_data['agent_id'];
	$agent_code = $order_data['agent_code'];
	
	$payble_amount = $order_data['final_payble_amount'];
	$partial_amount = $order_data['partial_amount'];
	$partial_amount_type = $order_data['partial_amount_type'];
	$remaining_amount = $payble_amount - $partial_amount;

	$transaction_id = $order_data['transaction_data']['transaction_id'];
	$card_id = $order_data['transaction_data']['card_id'];
	$client_ip = $order_data['transaction_data']['client_ip'];
	$created = $order_data['transaction_data']['created'];
	$transaction_data_payment_date = date("d/m/Y",$created);
	$transaction_data_client_email = $order_data['transaction_data']['email'];
	$pax_data = $order_data['pax'];
	$cabin_data = $order_data['cabin_types'];
	$courses_data = $order_data['courses_types'];
	$rental_equipment_data = $order_data['rental_equipment_types'];
	$user_credit_used = $order_data['user_credit'];

	foreach ($cabin_data as $cabinlist) {

		$order_table_name = $wpdb->prefix.'custom_order_details';
		$results = $wpdb->get_results( "SELECT * FROM ".$order_table_name." WHERE cabin_id = ".$cabinlist['id']." AND tripDate_id = ".$trip_date_id." AND order_trash = 'NO'");	
		if(count($results)!=0){

		foreach($results as $keys => $values) {
			 $cabin_seats_value  = $values->cabin_seat;
				if($cabin_seats_value == "both" || count($results)>=2){
					return ['status'=>false ,'data'=>[],'message' => 'Sorry cabin is not available. Please try again.',];	
				}
			}
		
		}
	}


	if($transaction_id !="" && $card_id !="" ){
		$randomid = rand(100,1000); 
		$Order_name  = "TRIP#".$user_id.$vessel_id.$country_id.$randomid;
		$post_id = wp_insert_post( array(
		    'post_title'        => $Order_name,
		    'post_status'       => 'publish',
		    'post_type'     => 'orders',

		) );
	}

	if( $post_id ) {  

		if(!$user_id == ""){ add_post_meta($post_id, 'user_id', $user_id); }
		if(!$vessel_id == ""){add_post_meta($post_id, 'vessel_id', $vessel_id); }	
		if(!$country_id == ""){add_post_meta($post_id, 'country_id', $country_id); }	
		if(!$itinery_id == ""){add_post_meta($post_id, 'itinery_id', $itinery_id); }	
		if(!$trip_date_id == ""){add_post_meta($post_id, 'trip_date_id', $trip_date_id); }
		if(!$total_person == ""){add_post_meta($post_id, 'total_person', $total_person); }
		if(!$coupon_id == ""){add_post_meta($post_id, 'coupon_data_coupon_id', $coupon_id); }
		if(!$coupon_code == ""){add_post_meta($post_id, 'coupon_data_coupon_code', $coupon_code); }
		if(!$agent_id == ""){add_post_meta($post_id, 'agent_data_agent_id', $agent_id); }	
		if(!$agent_code == ""){add_post_meta($post_id, 'agent_data_agent_code', $agent_code); }	
		
		if(!$payble_amount == ""){add_post_meta($post_id, 'payble_amount', $payble_amount); }	
		if(!$partial_amount == ""){add_post_meta($post_id, 'partial_amount', $partial_amount); }	
		if(!$partial_amount_type == ""){add_post_meta($post_id, 'partial_amount_type', $partial_amount_type); }	
		if(!$remaining_amount == ""){add_post_meta($post_id, 'remaining_amount', $remaining_amount); }	

		if(!$transaction_id == ""){add_post_meta($post_id, 'transaction_data_transaction_id', $transaction_id); }	
		if(!$client_ip == ""){add_post_meta($post_id, 'transaction_data_card_id', $card_id); }	
		if(!$client_ip == ""){add_post_meta($post_id, 'transaction_data_client_ip', $client_ip); }	
		if(!$transaction_data_payment_date == ""){add_post_meta($post_id, 'transaction_data_payment_date', $transaction_data_payment_date); }	
		if(!$transaction_data_client_email == ""){add_post_meta($post_id, 'transaction_data_client_email', $transaction_data_client_email); }
		add_post_meta($post_id, 'user_credit_used', $user_credit_used);

		$user_credit_data = get_user_meta( $user_id, "user_credit", true ); 

		if($user_credit_used != "" || $user_credit_data == ""){
			$final_amount = $user_credit_data - $user_credit_used;
			update_user_meta( $user_id, 'user_credit',$final_amount);	
		}
		$order_status = '';
		if($partial_amount_type == 100){
			$order_status = 'Completed';
			add_post_meta($post_id, 'order_status', "Completed");	
		} else{
			$order_status = 'Pending';
			add_post_meta($post_id, 'order_status', "Pending");
		}
		
	
		$pax_count = 0;
		$pax_data_cout = count($pax_data);
		add_post_meta($post_id, 'pax_data_pax_type', "Solo");
		add_post_meta($post_id, '_pax_data_pax_type', "field_5f6c38e76ff4d");
		add_post_meta($post_id, 'pax_data_selected_gender', "male");
		add_post_meta($post_id, '_pax_data_selected_gender', "field_5f6c392f6ff4e");

		add_post_meta($post_id, 'pax_data_pax_name', "name");
		add_post_meta($post_id, '_pax_data_pax_name', "field_5ffe80462ca18");

		add_post_meta($post_id, 'pax_data_pax_email', "email");
		add_post_meta($post_id, '_pax_data_pax_email', "field_5ffe812ced146");

		add_post_meta($post_id, 'pax_data_pax_phone_number', "phone_number");
		add_post_meta($post_id, '_pax_data_pax_phone_number', "field_600aa69c2c944");

		add_post_meta($post_id, 'pax_data_pax_age', "age");
		add_post_meta($post_id, '_pax_data_pax_age', "field_5ffe815aed147");

		add_post_meta($post_id, 'pax_data_pax_person_details', "person_details");
		add_post_meta($post_id, '_pax_data_pax_person_details', "field_600abe6465123");

		add_post_meta($post_id, '_pax_data', "field_5f6c38d56ff4c");
		add_post_meta($post_id, 'pax_data', $pax_data_cout);  
		
		$personcount = 1;

		foreach ($pax_data as $pax) {
			
			foreach ($pax as $key => $value) {		
				$here_data ="";
				$genedr_count = 1;
				if($key == "type"){
					$pax_type = "pax_data_".$pax_count."_pax_type";
					$pax_type2 = "_pax_data_".$pax_count."_pax_type";
					$pax_value = $value;	

					if(!$pax_value == ""){ add_post_meta($post_id, $pax_type, $pax_value); }
					if(!$pax_type2 == ""){ add_post_meta($post_id, $pax_type2, "field_5f6c4d72056f3"); }		
				}
				if($key == "name"){
					$pax_name = "pax_data_".$pax_count."_pax_name";
					$pax_name2 = "_pax_data_".$pax_count."_pax_name";
					$pax_name_value = $value;

					if(!$pax_name_value == ""){ add_post_meta($post_id, $pax_name, $pax_name_value); }
					if(!$pax_name2 == ""){ add_post_meta($post_id, $pax_name2, "field_5ffe80462ca18"); }

				}
				if($key == "email"){
					$pax_email = "pax_data_".$pax_count."_pax_email";
					$pax_email2 = "_pax_data_".$pax_count."_pax_email";
					$pax_email_value = $value;

					if(!$pax_email_value == ""){ add_post_meta($post_id, $pax_email, $pax_email_value); }
					if(!$pax_email2 == ""){ add_post_meta($post_id, $pax_email2, "field_5ffe812ced146"); }
				}
				
				if($key == "phone_number"){
					$pax_phone = "pax_data_".$pax_count."_pax_phone_number";
					$pax_phone2 = "_pax_data_".$pax_count."_pax_phone_number";
					$pax_phone_value = $value;

					if(!$pax_phone_value == ""){ add_post_meta($post_id, $pax_phone, $pax_phone_value); }
					if(!$pax_phone2 == ""){ add_post_meta($post_id, $pax_phone2, "field_600aa69c2c944"); }
				}

				if($key == "age"){
					$pax_age = "pax_data_".$pax_count."_pax_age";
					$pax_age2 = "_pax_data_".$pax_count."_pax_age";
					$pax_age_value = $value;

					if(!$pax_age_value == ""){ add_post_meta($post_id, $pax_age, $pax_age_value); }
					if(!$pax_age2 == ""){ add_post_meta($post_id, $pax_age2, "field_5ffe815aed147"); }
				}
				
				if($key == "name"){					
					$pax_person_details = "pax_data_".$pax_count."_pax_person_details";
					$pax_person_details2 = "_pax_data_".$pax_count."_pax_person_details";
					$pax_person_details_value = 'Person'.$personcount;
					
					if(!$pax_person_details_value == ""){ add_post_meta($post_id, $pax_person_details, $pax_person_details_value); }
					if(!$pax_person_details2 == ""){ add_post_meta($post_id, $pax_person_details2, "field_600abe6465123"); }
									
				}

				if($key == "gender"){
					$gendet_full_data = count($value);
					$selected_gender = "pax_data_".$pax_count."_selected_gender";	
					$pax_selected_gender = "_pax_data_".$pax_count."_selected_gender";	
					foreach ($value as  $key => $genders) {
						$here_data .= $genders;
						if($genedr_count < $gendet_full_data){
							$here_data .= ",";	
						}
						$genedr_count++;
					}	
					/*echo $pax_type ."====> ". $pax_value ." <br/>";				
					echo $selected_gender ."====> ". $here_data ." <br/>";	*/
					if(!$pax_selected_gender == ""){ add_post_meta($post_id, $pax_selected_gender, "field_5f6c392f6ff4e"); }
					if(!$here_data == ""){ add_post_meta($post_id, $selected_gender, $here_data); }
						
				}

				/*if($key == "name"){
					$pax_name = "pax_data_".$pax_count."_pax_name";
					$pax_name2 = "_pax_data_".$pax_count."_pax_name";
					$pax_name_value = $value;	
					if(!$pax_name_value == ""){ add_post_meta($post_id, $pax_name, $pax_name_value); }
					if(!$pax_name2 == ""){ add_post_meta($post_id, $pax_name2, "field_5ffe80462ca18"); }		
				}*/
				/*if($key == "email"){
					$pax_email = "pax_data_".$pax_count."_pax_email";
					$pax_email2 = "_pax_data_".$pax_count."_pax_email";
					$pax_email_value = $value;	
					if(!$pax_email_value == ""){ add_post_meta($post_id, $pax_email, $pax_email_value); }
					if(!$pax_email2 == ""){ add_post_meta($post_id, $pax_email2, "field_5ffe812ced146"); }		
				}
				if($key == "age"){
					$pax_age = "pax_data_".$pax_count."_pax_age";
					$pax_age2 = "_pax_data_".$pax_count."_pax_age";
					$pax_age_value = $value;	
					if(!$pax_age_value == ""){ add_post_meta($post_id, $pax_age, $pax_age_value); }
					if(!$pax_age2 == ""){ add_post_meta($post_id, $pax_age2, "field_5ffe815aed147"); }		
				}*/
				
				
			}

			$pax_count++;
			$personcount++;
		}

        
		$cabin_data_count = 0;
		$cabin_data_cout = count($cabin_data);
		add_post_meta($post_id, 'cabin_list_cabinID', "261");
		add_post_meta($post_id, '_cabin_list_cabinID', "field_5f6c39df6ff50");
		add_post_meta($post_id, 'cabin_list_cabin_types', "Solo");
		add_post_meta($post_id, '_cabin_list_cabin_types', "field_5f6c39ca6ff4f");
		add_post_meta($post_id, 'cabin_list_selected_seats', "Solo");
		add_post_meta($post_id, '_cabin_list_selected_seats', "field_5f6c3a6f6ff52");

		add_post_meta($post_id, 'cabin_list_person_details', "p1");
		add_post_meta($post_id, '_cabin_list_person_details', "field_5feee6bad6af4");

		add_post_meta($post_id, '_cabin_list', "field_5f6c38d56ff4c");
		add_post_meta($post_id, 'cabin_list', $cabin_data_cout);  
		
		foreach ($cabin_data as $cabin) {
			foreach ($cabin as $key => $value) {	
				//echo "key :-> ".$key. "=====> Value:- > " .$value."<br/>";	
				
				if($key == "id"){
					 $cabin_id_meta_field = "cabin_list_".$cabin_data_count."_cabinID";
					 $cabin_id_meta_field1 = "_cabin_list_".$cabin_data_count."_cabinID";
					 $cabin_id_value = $value;			
				}
				if($key == "type"){
					 $cabin_type_meta_field = "cabin_list_".$cabin_data_count."_cabin_types";
					 $cabin_type_meta_field1 = "_cabin_list_".$cabin_data_count."_cabin_types";
					 $cabin_type_value = $value;			
				}
				if($key == "seat"){			
					 $selected_seat_meta_field = "cabin_list_".$cabin_data_count."_selected_seats";
					 $selected_seat_meta_field1 = "_cabin_list_".$cabin_data_count."_selected_seats";
					 $selected_seat_value = $value;
				}
				if($key == "person"){			
					 $person_details_meta_field = "cabin_list_".$cabin_data_count."_person_details";
					 $person_details_meta_field1 = "_cabin_list_".$cabin_data_count."_person_details";
					 $person_details_value = $value;
				}
				

				
			}

			
				if(!$cabin_id_value == ""){ 
					add_post_meta($post_id, $cabin_id_meta_field, $cabin_id_value);
					//add_post_meta($cabin_id_value, "cabin_pax_seats", $selected_seat_value);
					add_post_meta($post_id, $cabin_id_meta_field1, "field_5f6c39df6ff50");
				 }
				if(!$cabin_type_value == ""){ 
					add_post_meta($post_id, $cabin_type_meta_field, $cabin_type_value); 
					/*if($cabin_type_value == "2pax"){
						$cabin_has_value = get_post_meta($cabin_id_value, "cabin_pax_seats", true);
						if($cabin_has_value == "" && $selected_seat_value == "left")
						{					
							add_post_meta($cabin_id_value, "cabin_pax_seats", $selected_seat_value);
						}else{
							update_post_meta( $cabin_id_value, 'cabin_pax_seats', "both");
						}
					}else{
						add_post_meta($cabin_id_value, "cabin_pax_seats", "both");
					}*/
					

					add_post_meta($post_id, $cabin_type_meta_field1, "field_5f6c3a426ff51"); 
				}
				if($selected_seat_value !=""){
					add_post_meta($post_id, $selected_seat_meta_field, $selected_seat_value);
					add_post_meta($post_id, $selected_seat_meta_field1, "field_5f6c3a6f6ff52"); 
				}
				if($person_details_value !=""){
					add_post_meta($post_id, $person_details_meta_field, $person_details_value);
					add_post_meta($post_id, $person_details_meta_field1, "field_5feee6bad6af4"); 
				}
			
				$order_table_name = $wpdb->prefix.'custom_order_details';
				$date = date('Y-m-d H:i:s');
				$wpdb->insert(
				    $order_table_name,
				        array(    
				            'id' => $id,
				            'user_id' => $user_id,
				            'cabin_id' => $cabin_id_value,
				            'cabin_type' => $cabin_type_value,
				            'cabin_seat' => $selected_seat_value,
				            'tripDate_id' => $trip_date_id,
				            'order_date' => $date,
				            'order_id' => $post_id,
				            'status' => $order_status,
				            'order_trash' => "No",
				            'coupon_id' => $coupon_id,
				            'agent_id' => $agent_id,
				            'coupon_code' => $coupon_code,
				            'agent_code' => $agent_code,


				        ) 
				    );
			$cabin_data_count++;
		}



		$courses_data_count = 0;
		$courses_data__cout = count($courses_data);

		add_post_meta($post_id, 'courses_data_courses_id', "395");
		add_post_meta($post_id, '_courses_data_courses_id', "field_5f6c47700da06");
		add_post_meta($post_id, 'courses_data_courses_person', "394");
		add_post_meta($post_id, '_courses_data_courses_person', "field_5f6c47820da07");
		add_post_meta($post_id, '_courses_data', "field_5f6c47480da05");
		add_post_meta($post_id, 'courses_data', $courses_data__cout);  
		
		foreach ($courses_data as $courses) {
			foreach ($courses as $key => $value) {	
				//echo "key :-> ".$key. "=====> Value:- > " .$value."<br/>";	courses_data_0_courses_id
				
				if($key == "id"){
					 $courses_data_id_meta_field = "courses_data_".$courses_data_count."_courses_id";
					 $courses_data_id_meta_field1 = "_courses_data_".$courses_data_count."_courses_id";
					 $courses_data_id_value = $value;			
				}
				if($key == "person"){
					 $courses_person_meta_field = "courses_data_".$courses_data_count."_courses_person";
					 $courses_person_meta_field1 = "_courses_data_".$courses_data_count."_courses_person";
					 $courses_person_value = $value;			
				}

				
			}
				/*echo $courses_data_id_meta_field." ===> ".$courses_data_id_value."<br/>";
				echo $courses_person_meta_field." ===> ".$courses_person_value."<br/>";*/

				if(!$courses_data_id_value == ""){  
					add_post_meta($post_id, $courses_data_id_meta_field, $courses_data_id_value); 
					add_post_meta($post_id, $courses_data_id_meta_field1, "field_5f6c514207e45"); 
				}
				if(!$courses_person_value == ""){  
					add_post_meta($post_id, $courses_person_meta_field, $courses_person_value); 
					add_post_meta($post_id, $courses_person_meta_field1, "field_5f6c47820da07"); 
				}
				
			$courses_data_count++;
		}

		$rental_equipment_data_count = 0;
		$rental_equipment_data__cout = count($rental_equipment_data);

		add_post_meta($post_id, 'rental_equipment_data_rental__equipment_id', "387");
		add_post_meta($post_id, '_rental_equipment_data_rental__equipment_id', "field_5f6c47a20da09");
		add_post_meta($post_id, 'rental_equipment_data_rental__equipment_person', "2");
		add_post_meta($post_id, '_rental_equipment_data_rental__equipment_person', "field_5f6c47a20da0a");
		add_post_meta($post_id, '_rental_equipment_data', "field_5f6c47a20da08");
		add_post_meta($post_id, 'rental_equipment_data', $rental_equipment_data__cout); 
		
		foreach ($rental_equipment_data as $rental_equipment) {
			foreach ($rental_equipment as $key => $value) {	
				//echo "key :-> ".$key. "=====> Value:- > " .$value."<br/>";	courses_data_0_courses_id
				
				if($key == "id"){
					 $rental_equipment_id_meta_field = "rental_equipment_data_".$rental_equipment_data_count."_rental__equipment_id";
					 $rental_equipment_id_meta_field1 = "_rental_equipment_data_".$rental_equipment_data_count."_rental__equipment_id";
					 $rental_equipment_id_value = $value;			
				}
				if($key == "person"){
					 $rental_equipment_person_meta_field = "rental_equipment_data_".$rental_equipment_data_count."_rental__equipment_person";
					 $rental_equipment_person_meta_field1 = "_rental_equipment_data_".$rental_equipment_data_count."_rental__equipment_person";
					 $rental_equipment_person_value = $value;			
				}

				
			}
				/*echo $rental_equipment_id_meta_field." ===> ".$rental_equipment_id_value."<br/>";
				echo $rental_equipment_person_meta_field." ===> ".$rental_equipment_person_value."<br/>";*/

				if(!$rental_equipment_id_value == ""){ 
					add_post_meta($post_id, $rental_equipment_id_meta_field, $rental_equipment_id_value); 
					add_post_meta($post_id, $rental_equipment_id_meta_field1, "field_5f6c47a20da09"); 
				}
				if(!$rental_equipment_person_value == ""){ 
					add_post_meta($post_id, $rental_equipment_person_meta_field, $rental_equipment_person_value); 
					add_post_meta($post_id, $rental_equipment_person_meta_field1, "field_5f6c47a20da0a"); 
				}
				
			$rental_equipment_data_count++;
		}


		/*$user_info = get_userdata($user_id);

		$user_name = $user_info->first_name;
		$user_email = $user_info->user_email;
		$message = "";
		$message .= "Hello ".$user_name.",<br/>";
		$message .= "Your Order ID : <b>".$Order_name."</b><br/>";
		$message .= "Total amount you paid : <b>".$payble_amount."</b><br/>";

		$message .= "Vessel : <b>".get_the_title($vessel_id)."</b>.<br/>";
		$message .= "Trip : <b>".get_the_title($trip_date_id)."<br/>";
		//wp_mail($user_email, 'Order Successfully Placed', $message);

		echo $message;


		die();*/

		//send_email_on_order_placed($post_id);
		if($post_id !="" && $user_id != ""){
			send_email_on_order_place($post_id,$user_id);
		}

		//add_post_meta($post_id, 'courses', $courses);
		$response = array(
			'message' => 'OK ',
			'order_id' => $post_id,
			'order_title' => $Order_name,

		);
	}



	if(!empty($response)) {
		return ['status'=>true,'data'=>$response,'message' => 'Order Placed successfully.'];
	}
	else{
		return ['status'=>false ,'data'=>[],'message' => 'Opps! Someting went Wrong.',];	
	}	
}


// Step 3.3 API destination data from country id
function view_order_data_from_user_id( $request ){
	
	$parameters = $request->get_params();
	$user_id = sanitize_text_field( $parameters['user_id'] );
	$i=0;		
	$order_data = [];
	$order_ID ="";
	$order_title ="";
	$order_bow ="";
	$order_gallery_image ="";
	$order_beds ="";
	$order_bathrooms="";
	$order_price="";

	$args = array('post_type' => 'orders','posts_per_page' => -1);
	$order_datas= get_posts($args);
	foreach($order_datas as $order_list){
		 $order_ID = $order_list->ID;
		 $order_title =$order_list->post_title;
		 $vessel_id = get_field('vessel_id',$order_list->ID);
		 $country_id = get_field('country_id',$order_list->ID);
		 $itinery_id = get_field('itinery_id',$order_list->ID);
		 $trip_date_id = get_field('trip_date_id',$order_list->ID);
		 $total_person = get_field('total_person',$order_list->ID);
		 $coupon_data_coupon_id = get_field('coupon_data_coupon_id',$order_list->ID);
		 $coupon_data_coupon_code = get_field('coupon_data_coupon_code',$order_list->ID);
		 $agent_data_agent_id = get_field('agent_data_agent_id',$order_list->ID);
		 $agent_data_agent_code = get_field('agent_data_agent_code',$order_list->ID);
		 $payble_amount = get_field('payble_amount',$order_list->ID);


		 $order_meta_user_ID = get_field('user_id',$order_list->ID);
		 if($order_meta_user_ID == $user_id){
		 	//echo $user_id."<br/>";
		 	$country = get_term_by('id', $country_id, 'country');
			$itternary_post_data_price = get_field('diverace_itinerary_price',$trip_date_id);
			$itternary_post_data_total_DN =get_field('diverace_itinerary_total_days_and_nights',$trip_date_id);

			$start_date = get_field('dive_start_date',$trip_date_id);
			$date_start_date = str_replace('/', '-', $start_date );
			$end_date = get_field('dive_end_date',$trip_date_id);
			$date_end_date = str_replace('/', '-', $end_date );
			$today_date = date("d-m-Y");
			$curdate=strtotime($today_date);
			$startdate=strtotime($date_start_date);
			$enddate=strtotime($date_end_date);
			$itternary_post_data_start_date = date("j F", strtotime($date_start_date));
			$itternary_post_data_end_date = date("j F", strtotime($date_end_date));
			$itternary_post_data_summary = $itternary_post_data_start_date." to ". $itternary_post_data_end_date." ".$itternary_post_data_total_DN." | & Days from &s ".$itternary_post_data_price;

		 	 $order_data[$i]['id']= $order_list->ID; 
		 	 $order_data[$i]['order_title']= $order_title; 
		 	 $order_data[$i]['vessel_title']= get_the_title($vessel_id); 
		 	 $order_data[$i]['itinery_title']= get_the_title($itinery_id); 
		 	 $order_data[$i]['country_title']= $country->name; 
		 	 $order_data[$i]['itternary_price']= (int)$itternary_post_data_price; 
		 	 $order_data[$i]['itternary_total_DN']= $itternary_post_data_total_DN; 
		 	 $order_data[$i]['itternary_start_date']= $itternary_post_data_start_date; 
		 	 $order_data[$i]['itternary_end_date']= $itternary_post_data_end_date; 
		 	 $order_data[$i]['itternary_date_summary']= $itternary_post_data_summary; 
		 	 $order_data[$i]['coupon_title']= get_the_title($coupon_data_coupon_id); 
		 	 $order_data[$i]['coupon_data_coupon_code']= $coupon_data_coupon_code; 
		 	 $order_data[$i]['agent_title']= get_the_title($agent_data_agent_id); 
		 	 $order_data[$i]['agent_data_agent_code']= $agent_data_agent_code; 
		 	 $order_data[$i]['payble_amount']= (int)$payble_amount; 
		 	 $order_data[$i]['total_person']= (int)$total_person; 
		 	/*echo "coupon_data_coupon_id -> ".$coupon_data_coupon_id."<br/>";
		 	echo "coupon_data_coupon_code -> ".$coupon_data_coupon_code."<br/>";*/
		 	
		 	/*$cabin_list = get_post_meta($order_list->ID,"cabin_list",true);
		 	for ($cab=0; $cab < $cabin_list; $cab++) { 		   		
		 		 $cabinID = get_post_meta($order_list->ID,"cabin_list_".$cab."_cabinID",true);			 
		    	$cabin_types = get_post_meta($order_list->ID,"cabin_list_".$cab."_cabin_types",true);			    	
		   	 	$selected_seats = get_post_meta($order_list->ID,"cabin_list_".$cab."_selected_seats",true);		   
			 		$order_data[$i]['cabin_data'][$cab]["cabin_title"] = get_the_title($cabinID);
			    	$order_data[$i]['cabin_data'][$cab]["cabin_type"] = $cabin_types;
			    	$order_data[$i]['cabin_data'][$cab]['selected_seat'] = $selected_seats;	
		    			 		
		 	}*/  	

		 	$cabin_list = get_post_meta($order_list->ID,"cabin_list",true);
		 	for ($cab=0; $cab < $cabin_list; $cab++) { 		   		
		 		$cabinID = get_post_meta($order_list->ID,"cabin_list_".$cab."_cabinID",true);			 
		    	$cabin_types = get_post_meta($order_list->ID,"cabin_list_".$cab."_cabin_types",true);			    	
		   	 	$selected_seats = get_post_meta($order_list->ID,"cabin_list_".$cab."_selected_seats",true);
		   	 	$person_details = get_post_meta($order_list->ID,"cabin_list_".$cab."_person_details",true);

			 		$order_data[$i]['cabin_data'][$cab]["cabin_title"] = get_the_title($cabinID);
			    	$order_data[$i]['cabin_data'][$cab]["cabin_type"] = $cabin_types;
			    	$order_data[$i]['cabin_data'][$cab]['selected_seat'] = $selected_seats;
			    	$order_data[$i]['cabin_data'][$cab]['person_details'] = $person_details;
			    	//print_r($order_data[$i]['cabin_data'][$cab]['person_details']); die();
			    	$new_persons=[];
			    	foreach($order_data[$i]['cabin_data'][$cab]['person_details'] as $key=>$person_val){
			    		//echo $person_val. '<br>';

			    		$pax_list = get_post_meta($order_list->ID,"pax_data",true);
					 	for ($paxd=0; $paxd < $pax_list; $paxd++) { 		   		
					 		
					 		$person_gender = get_post_meta($order_list->ID,"pax_data_".$paxd."_selected_gender",true);
					 		$person_name = get_post_meta($order_list->ID,"pax_data_".$paxd."_pax_name",true);
					    	$person_email = get_post_meta($order_list->ID,"pax_data_".$paxd."_pax_email",true);		   
					   	 	$person_phone_number = get_post_meta($order_list->ID,"pax_data_".$paxd."_pax_phone_number",true);
					   	 	$person_age = get_post_meta($order_list->ID,"pax_data_".$paxd."_pax_age",true);		   
					   	 	$person_number = get_post_meta($order_list->ID,"pax_data_".$paxd."_pax_person_details",true);		   
							if($person_val == $person_number){
				    			$person_detail = [
				    				'selected_gender'=>$person_gender, 
				    				'person_name'=>$person_name,
				    				'person_email'=>$person_email,
				    				'person_phone_number'=>$person_phone_number,
				    				'person_age'=>$person_age,
				    				'person_number'=>$person_number,
				    			];
				    		}	 		
					 	}
					 	$new_persons[$key]=$person_detail;
			    	}
					
			    	$order_data[$i]['cabin_data'][$cab]['persons']=$new_persons;
		    			 		
		 	}
			 	
 			/*	if( have_rows('cabin_list',$order_list->ID) ): $cb_count=0; 
		    	while( have_rows('cabin_list',$order_list->ID) ) : the_row();
			   		 $cabin_ids = get_sub_field('cabinID');		
			    	 $cabin_types = get_sub_field('cabin_types',$order_list->ID);	
			    		$selected_seats = get_sub_field('selected_seats',$order_list->ID);
			    	
			    	$order_data[$i]['cabin_data'][$cb_count]["cabin_title"] = get_the_title($cabin_ids);
			    	$order_data[$i]['cabin_data'][$cb_count]["cabin_type"] = $cabin_types;
			    	$order_data[$i]['cabin_data'][$cb_count]['selected_seat'] = $selected_seats;
			    	$cb_count++;
		    	endwhile;
		    endif;*/
		
		 	if( have_rows('courses_data',$order_list->ID) ): $count=0;
		    	while( have_rows('courses_data',$order_list->ID) ) : the_row();
		   		$courses_id = get_sub_field('courses_id',$order_list->ID);
		    	$courses_person = get_sub_field('courses_person',$order_list->ID);
			   // $order_data[$i]['courses_data'][$cb_count]["courses_id"] = $courses_id;
		    	$order_data[$i]['courses_data'][$count]['courses_title'] = get_the_title($courses_id);
		    	$order_data[$i]['courses_data'][$count]['courses_person'] = (int)$courses_person;
		    	$count++;
		    	endwhile;
		    endif;

		
		 	if( have_rows('rental_equipment_data',$order_list->ID) ): $count=0;
		    	while( have_rows('rental_equipment_data',$order_list->ID) ) : the_row();
		   		$rental_equipment_id = get_sub_field('rental__equipment_id',$order_list->ID);
		    	$rental_equipment_person = get_sub_field('rental__equipment_person',$order_list->ID);
		    	$order_data[$i]['rental_equipment_data'][$count]['rental_equipment_title'] = get_the_title($rental_equipment_id);
		    	$order_data[$i]['rental_equipment_data'][$count]['rental_equipment_person'] = (int)$rental_equipment_person;
		    	$count++;
		    	endwhile;
		    endif;

		   $i++;
	//echo $i;
		  }/*end if user id*/


		}/*end foreach*/

 if(!empty($order_data)) {
	return ['status'=>true,'data'=>$order_data,'message'=>"records found"];
}
else{
		return ['status'=>false ,'data'=>[],'message'=>"records not found !"];	
}
/*print_r($order_datas);
die();*/
	
	
		
}



// update user data and addons

//Insert Inro Order Data
function save_order_data_from_user_id($request){

    $parameters = $request->get_params();

    $order_data = json_decode(file_get_contents("php://input") , true);

   
    $post_id = $order_data['order_id'];
    $user_id = $order_data['user_id'];
    $courses_data = $order_data['courses'];
    $rental_equipment_data = $order_data['rental_equipment'];
    $payble_amount = $order_data['final_payble_amount'];
	$transaction_id = $order_data['transaction_data']['transaction_id'];
	$card_id = $order_data['transaction_data']['card_id'];
	$client_ip = $order_data['transaction_data']['client_ip'];
	$created = $order_data['transaction_data']['created'];
	$transaction_data_payment_date = date("d/m/Y",$created);
	$transaction_data_client_email = $order_data['transaction_data']['email'];	
	$user_credit_used = $order_data['user_credit'];

 
    // for payable amount
    $old_payble_amounts =  get_post_meta($post_id ,"payble_amount",true);	
    $payble_amounts =  $old_payble_amounts + $payble_amount;
     
	update_post_meta($post_id, 'payble_amount',  $payble_amounts ); 	
	update_post_meta($post_id, 'transaction_data_transaction_id', $transaction_id); 	
	update_post_meta($post_id, 'transaction_data_card_id', $card_id); 	
	update_post_meta($post_id, 'transaction_data_client_ip', $client_ip); 	
	update_post_meta($post_id, 'transaction_data_payment_date', $transaction_data_payment_date); 	
	update_post_meta($post_id, 'transaction_data_client_email', $transaction_data_client_email); 
	update_post_meta($post_id, 'user_credit_used', $user_credit_used);

	$user_credit_data = get_user_meta( $user_id, "user_credit", true ); 
	if($user_credit_used != "" || $user_credit_data == ""){
		$final_amount = $user_credit_data - $user_credit_used;
		update_user_meta( $user_id, 'user_credit',$final_amount);	
	}
	/*    $courses_data_count = 0;
	    $courses_data_count = count($courses_data);*/
	$post_data_count =  get_post_meta($post_id,"courses_data",true);
	// temp setting
	$post_data_count = 1;
	if($post_data_count > 0){
		//delete_post_meta($post_id, 'images');
		delete_post_meta($post_id, 'courses_data_courses_id');
		delete_post_meta($post_id, '_courses_data_courses_id');
		delete_post_meta($post_id, 'courses_data_courses_person');
		delete_post_meta($post_id, '_courses_data_courses_person');
		delete_post_meta($post_id, '_courses_data');
		delete_post_meta($post_id, 'courses_data'); 
		for ($del_count=0; $del_count < $post_data_count ; $del_count++) { 		
			delete_post_meta($post_id, "courses_data_" . $del_count . "_courses_id"); 
			delete_post_meta($post_id, "_courses_data_" . $del_count . "_courses_id"); 

			delete_post_meta($post_id, "courses_data_" . $del_count . "_courses_person"); 
			delete_post_meta($post_id, "_courses_data_" . $del_count . "_courses_person"); 
		}
	}


	/*$rental_equipment_data_count = 0;
	$rental_equipment_data_count = count($rental_equipment_data);*/
	$rental_post_data_count =  get_post_meta($post_id,"rental_equipment_data",true);
	// temp setting 
	$rental_post_data_count = 1;
	if($rental_post_data_count > 0){
		//delete_post_meta($post_id, 'images');
		delete_post_meta($post_id, 'rental_equipment_data_rental__equipment_id');
		delete_post_meta($post_id, '_rental_equipment_data_rental__equipment_id');
		delete_post_meta($post_id, 'rental_equipment_data_rental__equipment_person');
		delete_post_meta($post_id, '_rental_equipment_data_rental__equipment_person');
		delete_post_meta($post_id, '_rental_equipment_data');
		delete_post_meta($post_id, 'rental_equipment_data'); 


		for ($re_count=0; $re_count < $rental_post_data_count ; $re_count++) { 		
			delete_post_meta($post_id, "rental_equipment_data_" . $re_count . "_rental__equipment_id"); 
			delete_post_meta($post_id, "_rental_equipment_data_" . $re_count . "_rental__equipment_id"); 

			delete_post_meta($post_id, "rental_equipment_data_" . $re_count . "_rental__equipment_person"); 
			delete_post_meta($post_id, "_rental_equipment_data_" . $re_count . "_rental__equipment_person"); 
		}
	}



	$courses_data_count = 0;
	$courses_data__cout = count($courses_data);


	add_post_meta($post_id, 'courses_data_courses_id', "395");
	add_post_meta($post_id, '_courses_data_courses_id', "field_5f6c47700da06");
	add_post_meta($post_id, 'courses_data_courses_person', "394");
	add_post_meta($post_id, '_courses_data_courses_person', "field_5f6c47820da07");
	add_post_meta($post_id, '_courses_data', "field_5f6c47480da05");
	add_post_meta($post_id, 'courses_data', $courses_data__cout);  

	foreach ($courses_data as $courses) {
		foreach ($courses as $key => $value) {	
			//echo "key :-> ".$key. "=====> Value:- > " .$value."<br/>";	courses_data_0_courses_id
			
			if($key == "id"){
				 $courses_data_id_meta_field = "courses_data_".$courses_data_count."_courses_id";
				 $courses_data_id_meta_field1 = "_courses_data_".$courses_data_count."_courses_id";
				 $courses_data_id_value = $value;			
			}
			if($key == "booked_course"){
				 $courses_person_meta_field = "courses_data_".$courses_data_count."_courses_person";
				 $courses_person_meta_field1 = "_courses_data_".$courses_data_count."_courses_person";
				 $courses_person_value = $value;			
			}

			
		}		

			if(!$courses_data_id_value == ""){  
				 add_post_meta($post_id, $courses_data_id_meta_field, $courses_data_id_value); 

				 add_post_meta($post_id, $courses_data_id_meta_field1, "field_5f6c514207e45"); 



			}
			if(!$courses_person_value == ""){  
				add_post_meta($post_id, $courses_person_meta_field, $courses_person_value); 
				add_post_meta($post_id, $courses_person_meta_field1, "field_5f6c47820da07"); 
			}
			
		$courses_data_count++;
	}

	$rental_equipment_data_count = 0;
	$rental_equipment_data__cout = count($rental_equipment_data);

			add_post_meta($post_id, 'rental_equipment_data_rental__equipment_id', "387");
			add_post_meta($post_id, '_rental_equipment_data_rental__equipment_id', "field_5f6c47a20da09");
			add_post_meta($post_id, 'rental_equipment_data_rental__equipment_person', "2");
			add_post_meta($post_id, '_rental_equipment_data_rental__equipment_person', "field_5f6c47a20da0a");
			add_post_meta($post_id, '_rental_equipment_data', "field_5f6c47a20da08");
			add_post_meta($post_id, 'rental_equipment_data', $rental_equipment_data__cout); 


	foreach ($rental_equipment_data as $rental_equipment) {
		foreach ($rental_equipment as $key => $value) {	
			
			if($key == "id"){
				 $rental_equipment_id_meta_field = "rental_equipment_data_".$rental_equipment_data_count."_rental__equipment_id";
				 $rental_equipment_id_meta_field1 = "_rental_equipment_data_".$rental_equipment_data_count."_rental__equipment_id";
				 $rental_equipment_id_value = $value;			
			}
			if($key == "booked_rental_equipment"){
				 $rental_equipment_person_meta_field = "rental_equipment_data_".$rental_equipment_data_count."_rental__equipment_person";
				 $rental_equipment_person_meta_field1 = "_rental_equipment_data_".$rental_equipment_data_count."_rental__equipment_person";
				 $rental_equipment_person_value = $value;			
			}

			
		}
			if(!$rental_equipment_id_value == ""){ 
				add_post_meta($post_id, $rental_equipment_id_meta_field, $rental_equipment_id_value); 
				add_post_meta($post_id, $rental_equipment_id_meta_field1, "field_5f6c47a20da09"); 
			}
			if(!$rental_equipment_person_value == ""){ 
				add_post_meta($post_id, $rental_equipment_person_meta_field, $rental_equipment_person_value); 
				add_post_meta($post_id, $rental_equipment_person_meta_field1, "field_5f6c47a20da0a"); 
			}
			
		$rental_equipment_data_count++;
	}
		
		//echo $rental_post_data_count;
	if($post_id !="" && $user_id != ""){
		send_email_on_order_place($post_id,$user_id);
	}
	$response = array('order_id' => $post_id);

	if (!empty($response))
	{
	    return ['status' => true, 'data' => $response, 'message' => 'Order updated successfully.'];
	}
	else
	{
	    return ['status' => false, 'data' => [], 'message' => 'Opps! Someting went Wrong.'];
	}
}


// Step 3.3 API destination data from country id
function order_summery_data_from_order_id( $request ){
	
	$parameters = $request->get_params();
	$user_id = sanitize_text_field( $parameters['user_id'] );
	$order_id = sanitize_text_field( $parameters['order_id'] );
	$i=0;		
	$order_data = [];
	$order_ID ="";
	$order_title ="";
	$order_bow ="";
	$order_gallery_image ="";
	$order_beds ="";
	$order_bathrooms="";
	$order_price="";
	//echo $user_id."<br/>";echo $order_id."<br/>";
	$args = array('post_type' => 'orders','posts_per_page' => -1);
	$order_datas= get_posts($args);
	//print_r($order_datas);
	foreach($order_datas as $order_list){
		
		$order_ID = $order_list->ID;
		$order_title =$order_list->post_title;
		$vessel_id = get_field('vessel_id',$order_list->ID);
		$country_id = get_field('country_id',$order_list->ID);
		$itinery_id = get_field('itinery_id',$order_list->ID);
		$trip_date_id = get_field('trip_date_id',$order_list->ID);
		$total_person = get_field('total_person',$order_list->ID);
		$coupon_data_coupon_id = get_field('coupon_data_coupon_id',$order_list->ID);
		$coupon_data_coupon_code = get_field('coupon_data_coupon_code',$order_list->ID);
		$agent_data_agent_id = get_field('agent_data_agent_id',$order_list->ID);
		$agent_data_agent_code = get_field('agent_data_agent_code',$order_list->ID);
		$payble_amount = get_field('payble_amount',$order_list->ID);

		$order_meta_user_ID = get_field('user_id',$order_list->ID);
		
		$key = 'user_credit';
		$single = true;
		$user_credit = get_user_meta( $order_meta_user_ID, $key, $single ); 
		//echo $order_meta_user_ID."<br/>";echo $order_ID."<br/>";
		
		if($order_meta_user_ID == $user_id && $order_id ==  $order_ID){
		 	//echo $user_id."<br/>";echo $order_id."<br/>";
		 	$country = get_term_by('id', $country_id, 'country');
			$itternary_post_data_price = get_field('diverace_itinerary_price',$trip_date_id);
			$itternary_post_data_total_DN =get_field('diverace_itinerary_total_days_and_nights',$trip_date_id);
			$itternary_post_data_start_date =get_field('dive_start_date',$trip_date_id);
			$itternary_post_data_end_date =get_field('dive_end_date',$trip_date_id);
			$itternary_post_data_summary = $itternary_post_data_start_date." to ". $itternary_post_data_end_date." ".$itternary_post_data_total_DN." | & Days from &s ".$itternary_post_data_price;
			
		 	 $order_data[$i]['id']= $order_list->ID; 
		 	 $order_data[$i]['order_title']= $order_title; 
		 	 $order_data[$i]['vessel_title']= get_the_title($vessel_id); 
		 	 $order_data[$i]['itinery_title']= get_the_title($itinery_id); 
		 	 $order_data[$i]['country_title']= $country->name; 
		 	 $order_data[$i]['itternary_price']= (int)$itternary_post_data_price; 
		 	 $order_data[$i]['itternary_total_DN']= $itternary_post_data_total_DN; 
		 	 $order_data[$i]['itternary_start_date']= $itternary_post_data_start_date; 
		 	 $order_data[$i]['itternary_end_date']= $itternary_post_data_end_date; 
		 	 $order_data[$i]['itternary_date_summary']= $itternary_post_data_summary; 
		 	 $order_data[$i]['coupon_title']= get_the_title($coupon_data_coupon_id); 
		 	 $order_data[$i]['coupon_data_coupon_code']= $coupon_data_coupon_code; 
		 	 $order_data[$i]['agent_title']= get_the_title($agent_data_agent_id); 
		 	 $order_data[$i]['agent_data_agent_code']= $agent_data_agent_code; 
		 	 $order_data[$i]['payble_amount']=  $payble_amount; 
		 	 $order_data[$i]['total_person']= (int)$total_person; 
		 	 $order_data[$i]['user_credit']= $user_credit; 

       
		 	$cabin_list = get_post_meta($order_list->ID,"cabin_list",true);
		 	for ($cab=0; $cab < $cabin_list; $cab++) { 		   		
		 		$cabinID = get_post_meta($order_list->ID,"cabin_list_".$cab."_cabinID",true);			 
		    	$cabin_types = get_post_meta($order_list->ID,"cabin_list_".$cab."_cabin_types",true);			    	
		   	 	$selected_seats = get_post_meta($order_list->ID,"cabin_list_".$cab."_selected_seats",true);
		   	 	$person_details = get_post_meta($order_list->ID,"cabin_list_".$cab."_person_details",true);

			 		$order_data[$i]['cabin_data'][$cab]["cabin_title"] = get_the_title($cabinID);
			    	$order_data[$i]['cabin_data'][$cab]["cabin_type"] = $cabin_types;
			    	$order_data[$i]['cabin_data'][$cab]['selected_seat'] = $selected_seats;
			    	$order_data[$i]['cabin_data'][$cab]['person_details'] = $person_details;
			    	//print_r($order_data[$i]['cabin_data'][$cab]['person_details']); die();
			    	$new_persons=[];
			    	foreach($order_data[$i]['cabin_data'][$cab]['person_details'] as $key=>$person_val){
			    		//echo $person_val. '<br>';

			    		$pax_list = get_post_meta($order_list->ID,"pax_data",true);
					 	for ($paxd=0; $paxd < $pax_list; $paxd++) { 		   		
					 		
					 		$person_gender = get_post_meta($order_list->ID,"pax_data_".$paxd."_selected_gender",true);
					 		$person_name = get_post_meta($order_list->ID,"pax_data_".$paxd."_pax_name",true);
					    	$person_email = get_post_meta($order_list->ID,"pax_data_".$paxd."_pax_email",true);		   
					   	 	$person_phone_number = get_post_meta($order_list->ID,"pax_data_".$paxd."_pax_phone_number",true);
					   	 	$person_age = get_post_meta($order_list->ID,"pax_data_".$paxd."_pax_age",true);		   
					   	 	$person_number = get_post_meta($order_list->ID,"pax_data_".$paxd."_pax_person_details",true);		   
							if($person_val == $person_number){
				    			$person_detail = [
				    				'selected_gender'=>$person_gender, 
				    				'person_name'=>$person_name,
				    				'person_email'=>$person_email,
				    				'person_phone_number'=>$person_phone_number,
				    				'person_age'=>$person_age,
				    				'person_number'=>$person_number,
				    			];
				    		}	 		
					 	}
					 	$new_persons[$key]=$person_detail;
			    	}
					
			    	$order_data[$i]['cabin_data'][$cab]['persons']=$new_persons;
		    			 		
		 	}

		
		 	if( have_rows('courses_data',$order_list->ID) ): $count=0;
		    	while( have_rows('courses_data',$order_list->ID) ) : the_row();
		   		$courses_id = get_sub_field('courses_id',$order_list->ID);
		    	$courses_person = get_sub_field('courses_person',$order_list->ID);
		    	$order_data[$i]['courses_data'][$count]['courses_id'] = $courses_id;
		    	$order_data[$i]['courses_data'][$count]['courses_title'] = get_the_title($courses_id);
		    	$order_data[$i]['courses_data'][$count]['courses_person'] = (int)$courses_person;
		    	$count++;
		    	endwhile;
		    endif;

		
		 	if( have_rows('rental_equipment_data',$order_list->ID) ): $count=0;
		    	while( have_rows('rental_equipment_data',$order_list->ID) ) : the_row();
		   		$rental_equipment_id = get_sub_field('rental__equipment_id',$order_list->ID);
		    	$rental_equipment_person = get_sub_field('rental__equipment_person',$order_list->ID);
		    	$order_data[$i]['rental_equipment_data'][$count]['rental_equipment_title'] = get_the_title($rental_equipment_id);
		    	$order_data[$i]['rental_equipment_data'][$count]['rental_equipment_person'] = (int)$rental_equipment_person;
		    	$count++;
		    	endwhile;
		    endif;

		   	$i++;
			//echo $i;
		}/*end if user id*/


	}/*end foreach*/

	if(!empty($order_data)) {
		return ['status'=>true,'data'=>$order_data,'message'=>"records found"];
	}
	else{
		return ['status'=>false ,'data'=>[],'message'=>"records not found !"];	
	}
	/*print_r($order_datas);
	die();*/
		
}


/*function send_email_on_order_placed(){

	send_email_on_order_place(677,49);
	//send_email_on_order_updated(672,49);
}*/
