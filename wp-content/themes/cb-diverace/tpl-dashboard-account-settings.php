<?php
/*
  Template Name: Dashboard Account Settings Page
  Template Post Type: post, page
 */

global $post, $wpdb;

$userID = '';
if( is_user_logged_in () ) {
    $current_user                      = wp_get_current_user ();
    $userID                            = $current_user->ID;
    $current_user_email                = $current_user->user_email;
// User Name
    $user_fname                        = get_the_author_meta ( 'first_name', $userID );
    $user_lname                        = get_the_author_meta ( 'last_name', $userID );
// User contact meta
    $user_email                        = get_the_author_meta ( 'user_email', $userID );
    $user_phone_number                 = get_field ( 'user_phone_number', 'user_' . $userID );
    $user_age                          = get_field ( 'user_age', 'user_' . $userID );
    $user_gender                       = get_field ( 'user_gender', 'user_' . $userID );
// User Social links
//$user_facebook = get_the_author_meta('facebook', $userID);    
//$user_twitter = get_the_author_meta('twitter', $userID);    
    $user_linkedin                     = get_the_author_meta ( 'linkedin', $userID );
    $user_instagram                    = get_the_author_meta ( 'instagram', $userID );
//$user_pinterest = get_the_author_meta('pinterest', $userID);
// User BIO
    $user_desc                         = get_the_author_meta ( 'description', $userID );
// User documents
// User address Details
    $address1                          = get_field ( 'user_address_line_1', 'user_' . $userID );
    $address2                          = get_field ( 'user_address_line_2', 'user_' . $userID );
    $usercity                          = get_field ( 'user_city', 'user_' . $userID );
    $userstate                         = get_field ( 'user_state', 'user_' . $userID );
    $usecontry                         = get_field ( 'user_country', 'user_' . $userID );
    $usepincode                        = get_field ( 'user_postcode', 'user_' . $userID );
    $date_of_birth                     = get_the_author_meta ( 'date_of_birth', $userID );
    $passport_number                   = get_field ( 'passport_number', 'user_' . $userID );
    $country_passport                  = get_field ( 'country_passport', 'user_' . $userID );
    $nationality_of_passport_holder    = get_field ( 'nationality_of_passport_holder', 'user_' . $userID );
    $date_of_expiry                    = get_field ( 'date_of_expiry', 'user_' . $userID );
    $issuing_authority                 = get_field ( 'issuing_authority', 'user_' . $userID );
    $nricfin_number                    = get_field ( 'nricfin_number', 'user_' . $userID );
//    $user_image        = get_field ( 'user_image', 'user_' . $userID );
    $user_image                        = get_user_meta ( $userID, 'user_image', true );
//$get_user_nricfin_upload_front_doc = get_field('nricfin_upload_front_doc', 'user_'. $userID );
    $get_user_nricfin_upload_front_doc = get_user_meta ( $userID, 'nricfin_upload_front_doc', true );
//$get_user_nricfin_upload_back_doc = get_field('nricfin_upload_back_doc', 'user_'. $userID );
    $get_user_nricfin_upload_back_doc  = get_user_meta ( $userID, 'nricfin_upload_back_doc', true );
//$get_user_passport_document = get_field('user_passport_document', 'user_'. $userID );
    $get_user_passport_document        = get_user_meta ( $userID, 'user_passport_document', true );
//$get_user_immigration_document = get_field('user_immigration_document', 'user_'. $userID );
    $get_user_immigration_document     = get_user_meta ( $userID, 'user_immigration_document', true );
//$get_user_visa_document = get_field('user_visa_document', 'user_'. $userID );
    $get_user_visa_document            = get_user_meta ( $userID, 'user_visa_document', true );
    $immigration_document_description  = get_field ( 'immigration_document_description', 'user_' . $userID );
    $visa_document_description         = get_field ( 'visa_document_description', 'user_' . $userID );
// Check profileupdate button click 
    if( isset ( $_POST[ 'profileupdate' ] ) ) {

        wp_cache_delete ( $userID, 'user_image' );
        wp_cache_delete ( $userID, 'user_phone_number' );
        wp_cache_delete ( $userID, 'user_age' );
        wp_cache_delete ( $userID, 'user_gender' );
        wp_cache_delete ( $userID, 'user_address_line_1' );
        wp_cache_delete ( $userID, 'user_address_line_2' );
        wp_cache_delete ( $userID, 'user_city' );
        wp_cache_delete ( $userID, 'user_state' );
        wp_cache_delete ( $userID, 'user_country' );
        wp_cache_delete ( $userID, 'user_postcode' );

        wp_cache_delete ( $userID, 'date_of_birth' );
        wp_cache_delete ( $userID, 'passport_number' );
        wp_cache_delete ( $userID, 'country_passport' );
        wp_cache_delete ( $userID, 'nationality_of_passport_holder' );
        wp_cache_delete ( $userID, 'date_of_expiry' );
        wp_cache_delete ( $userID, 'issuing_authority' );

        wp_cache_delete ( $userID, 'user_passport_document' );
        wp_cache_delete ( $userID, 'user_immigration_document' );
        wp_cache_delete ( $userID, 'user_visa_document' );

        wp_cache_delete ( $userID, 'immigration_document_description' );
        wp_cache_delete ( $userID, 'visa_document_description' );

        wp_cache_delete ( $userID, 'nricfin_number' );
        wp_cache_delete ( $userID, 'nricfin_upload_front_doc' );
        wp_cache_delete ( $userID, 'nricfin_upload_back_doc' );

        $message = 'Your profile has been successfully updated.';
        $mType   = 'success';
        /* if($message == 'Your profile has been successfully updated.'){
          $redirect_url = home_url().'/dashboard-account-settings/';
          header('Location: '.$redirect_url.'');
          } */

        $first_name                       = esc_html ( $_POST[ 'first_name_hidden' ] );
        $last_name                        = esc_html ( $_POST[ 'last_name_hidden' ] );
        $user_email                       = esc_html ( $_POST[ 'user_email_hidden' ] );
        $user_phone_number                = esc_html ( $_POST[ 'user_phone_number_hidden' ] );
//        $user_image                       = esc_html ( $_POST[ 'user_image_hidden' ] );
        $user_age                         = esc_html ( $_POST[ 'user_age_hidden' ] );
        $user_gender                      = esc_html ( $_POST[ 'user_gender' ] );
        $address1                         = esc_html ( $_POST[ 'user_address_line_1_hidden' ] );
        $address2                         = esc_html ( $_POST[ 'user_address_line_2_hidden' ] );
        $usercity                         = esc_html ( $_POST[ 'user_city_hidden' ] );
        $userstate                        = esc_html ( $_POST[ 'user_state_hidden' ] );
        $usecontry                        = esc_html ( $_POST[ 'user_country_hidden' ] );
        $usepincode                       = esc_html ( $_POST[ 'user_postcode_hidden' ] );
        $date_of_birth                    = esc_html ( $_POST[ 'date_of_birth' ] );
        $passport_number                  = esc_html ( $_POST[ 'passport_number_hidden' ] );
        $country_passport                 = esc_html ( $_POST[ 'country_passport_hidden' ] );
        $nationality_of_passport_holder   = esc_html ( $_POST[ 'nationality_of_passport_holder_hidden' ] );
        $date_of_expiry                   = esc_html ( $_POST[ 'date_of_expiry' ] );
        $issuing_authority                = esc_html ( $_POST[ 'issuing_authority_hidden' ] );
//$facebook_url = esc_html($_POST['facebook_url']);
//$twitter_url = esc_html($_POST['twitter_url']);
        $linkedin_url                     = esc_html ( $_POST[ 'linkedin_url' ] );
        $instagram_url                    = esc_html ( $_POST[ 'instagram_url' ] );
//$pinterest_url = esc_html($_POST['pinterest_url']);
        $current_password                 = sanitize_text_field ( $_POST[ 'current_password' ] );
        $new_password                     = sanitize_text_field ( $_POST[ 'new_password' ] );
        $confirm_new_password             = sanitize_text_field ( $_POST[ 'confirm_new_password' ] );
        $immigration_document_description = esc_html ( $_POST[ 'immigration_document_description_hidden' ] );
        $visa_document_description        = esc_html ( $_POST[ 'visa_document_description_hidden' ] );
        $nricfin_number                   = esc_html ( $_POST[ 'nricfin_number_hidden' ] );

        update_user_meta ( $userID, 'first_name', $first_name );
        update_user_meta ( $userID, 'last_name', $last_name );

//        update_user_meta ( $userID, 'user_image', $user_image );
        update_user_meta ( $userID, 'user_phone_number', $user_phone_number );
        update_user_meta ( $userID, 'user_age', $user_age );
        update_user_meta ( $userID, 'user_gender', $user_gender );

//update_user_meta( $userID, 'facebook', $facebook_url );
//update_user_meta( $userID, 'twitter', $twitter_url );
        update_user_meta ( $userID, 'linkedin', $linkedin_url );
        update_user_meta ( $userID, 'instagram', $instagram_url );

//update_user_meta( $userID, 'pinterest', $pinterest_url );
// Changes in user address 		
        update_user_meta ( $userID, 'user_address_line_1', $address1 );
        update_user_meta ( $userID, 'user_address_line_2', $address2 );
        update_user_meta ( $userID, 'user_city', $usercity );
        update_user_meta ( $userID, 'user_state', $userstate );
        update_user_meta ( $userID, 'user_country', $usecontry );
        update_user_meta ( $userID, 'user_postcode', $usepincode );

// Changes in user Passport Details 	
        update_user_meta ( $userID, 'date_of_birth', $date_of_birth );
        update_user_meta ( $userID, 'passport_number', $passport_number );
        update_user_meta ( $userID, 'country_passport', $country_passport );
        update_user_meta ( $userID, 'nationality_of_passport_holder', $nationality_of_passport_holder );
        update_user_meta ( $userID, 'date_of_expiry', $date_of_expiry );
        update_user_meta ( $userID, 'issuing_authority', $issuing_authority );

// Changes to the Immigration and VISA Documents Description
        update_user_meta ( $userID, 'immigration_document_description', $immigration_document_description );
        update_user_meta ( $userID, 'visa_document_description', $visa_document_description );

// Changes in user NRIC/FIN section 
        update_user_meta ( $userID, 'nricfin_number', $nricfin_number );

        if( isset ( $user_email ) && is_email ( $user_email ) ) {
            if( email_exists ( $user_email ) && $current_user_email != $user_email ) {
                if( $current_user_email == $user_email ) {
                    wp_update_user ( array ( 'ID' => $userID, 'user_email' => $user_email ) );
                } else {
                    $message = "That E-mail is registered to other user. Use different email address.";
                    $mType   = 'error';
                }
            } else {
                wp_update_user ( array ( 'ID' => $userID, 'user_email' => $user_email ) );
            }
        } else {
            $message = "Please enter a valid email id.";
            $mType   = 'error';
        }


        $userdata          = get_userdata ( $userID );
        $existing_password = $userdata->data->user_pass;
        $user              = get_user_by ( 'ID', $userID );

        if( ! empty ( $current_password ) ) {
            if( $user && wp_check_password ( $current_password, $existing_password, $userID ) ) {
                if( $new_password ) {
                    if( strlen ( $new_password ) < 5 || strlen ( $new_password ) > 15 ) {
                        $message = "Your password must be between 5 and 15 letters long";
                        $mType   = 'error';
                    }
//elseif( $password == $confirm_new_password ) {
                    elseif( isset ( $new_password ) && $new_password != $confirm_new_password ) {
                        $message = esc_html__ ( 'New password is not match with confirm password.' );
                        $mType   = 'error';
                    } elseif( isset ( $new_password ) && ! empty ( $new_password ) ) {
                        $lpCUser = wp_get_current_user ();
                        $update  = wp_set_password ( $new_password, $userID );
                        wp_set_auth_cookie ( $lpCUser->ID );
                        wp_set_current_user ( $lpCUser->ID );
                        do_action ( 'wp_login', $lpCUser->user_login, $lpCUser );
                        $message = esc_html__ ( 'Your profile has been updated successfully.', 'wp-rest-user' );
                        $mType   = esc_html__ ( 'success', 'wp-rest-user' );
                    }
                }
            } else {
                $message = "Your password is not match with current password. Enter valid password.";
                $mType   = 'error';
            }
        }


// These files need to be included as dependencies when on the front end.
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

// Let WordPress handle the upload.
        $user_image                = media_handle_upload ( 'user_image', 0 );
        $user_passport_document    = media_handle_upload ( 'user_passport_document', 0 );
        $user_immigration_document = media_handle_upload ( 'user_immigration_document', 0 );
        $user_visa_document        = media_handle_upload ( 'user_visa_document', 0 );

        /* echo '<pre>';
          print_r($user_passport_document);
          exit; */
//NRIC/FIN Details Upload documents
        $user_nricfin_upload_front_doc = media_handle_upload ( 'nricfin_upload_front_doc', 0 );
        $user_nricfin_upload_back_doc  = media_handle_upload ( 'nricfin_upload_back_doc', 0 );

        if( ! empty ( $user_image ) ) {
            if( is_wp_error ( $user_image ) ) {
                
            } else {
                update_user_meta ( $userID, 'user_image', $user_image );
                $message = esc_html__ ( 'Your profile image has been uploded successfully.', 'wp-rest-user' );
                $mType   = esc_html__ ( 'success', 'wp-rest-user' );
                if( $message == 'Your profile image has been uploded successfully.' ) {
                    $redirect_url = home_url () . '/dashboard-account-settings/';
                    header ( 'Location: ' . $redirect_url . '' );
                }
            }
        }
        if( ! empty ( $user_passport_document ) ) {
            if( is_wp_error ( $user_passport_document ) ) {
                
            } else {
                update_user_meta ( $userID, 'user_passport_document', $user_passport_document );
                $message = esc_html__ ( 'Your document has been uploded successfully.', 'wp-rest-user' );
                $mType   = esc_html__ ( 'success', 'wp-rest-user' );
                if( $message == 'Your document has been uploded successfully.' ) {
                    $redirect_url = home_url () . '/dashboard-account-settings/';
                    header ( 'Location: ' . $redirect_url . '' );
                }
            }
        }
        if( ! empty ( $user_immigration_document ) ) {
            if( is_wp_error ( $user_immigration_document ) ) {
                
            } else {
                update_user_meta ( $userID, 'user_immigration_document', $user_immigration_document );
                $message = esc_html__ ( 'Your document has been uploded successfully.', 'wp-rest-user' );
                $mType   = esc_html__ ( 'success', 'wp-rest-user' );
                if( $message == 'Your document has been uploded successfully.' ) {
                    $redirect_url = home_url () . '/dashboard-account-settings/';
                    header ( 'Location: ' . $redirect_url . '' );
                }
            }
        }
        if( ! empty ( $user_visa_document ) ) {
            if( is_wp_error ( $user_visa_document ) ) {
                
            } else {
                update_user_meta ( $userID, 'user_visa_document', $user_visa_document );
                $message = esc_html__ ( 'Your document has been uploded successfully.', 'wp-rest-user' );
                $mType   = esc_html__ ( 'success', 'wp-rest-user' );
                if( $message == 'Your document has been uploded successfully.' ) {
                    $redirect_url = home_url () . '/dashboard-account-settings/';
                    header ( 'Location: ' . $redirect_url . '' );
                }
            }
        }
        if( ! empty ( $user_nricfin_upload_front_doc ) ) {
            if( is_wp_error ( $user_nricfin_upload_front_doc ) ) {
                
            } else {
                update_user_meta ( $userID, 'nricfin_upload_front_doc', $user_nricfin_upload_front_doc );
                $message = esc_html__ ( 'Your document has been uploded successfully.', 'wp-rest-user' );
                $mType   = esc_html__ ( 'success', 'wp-rest-user' );
                if( $message == 'Your document has been uploded successfully.' ) {
                    $redirect_url = home_url () . '/dashboard-account-settings/';
                    header ( 'Location: ' . $redirect_url . '' );
                }
            }
        }
        if( ! empty ( $user_nricfin_upload_back_doc ) ) {
            if( is_wp_error ( $user_nricfin_upload_back_doc ) ) {
                
            } else {
                update_user_meta ( $userID, 'nricfin_upload_back_doc', $user_nricfin_upload_back_doc );
                $message = esc_html__ ( 'Your document has been uploded successfully.', 'wp-rest-user' );
                $mType   = esc_html__ ( 'success', 'wp-rest-user' );
                if( $message == 'Your document has been uploded successfully.' ) {
                    $redirect_url = home_url () . '/dashboard-account-settings/';
                    header ( 'Location: ' . $redirect_url . '' );
                }
            }
        }
    }
} else {
    wp_redirect ( home_url () );
    exit;
}

get_header ( 'dashboard' );
?>

<?php
if( have_posts () ) {
    while ( have_posts () ) {
        the_post ();
//get_template_part( 'content', 'page' );
        ?>
        <section class="content">
            <div class="container-fluid">
                <!-- Main row -->
                <div class="row">
                    <!-- Left col -->
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title float-none text-center mb-4">
                                    Account Settings
                                </h2> 
                                <?php
                                if( is_user_logged_in () ) {
                                    if( $user_fname != "" ) {
                                        $hasCompletedfirst_name = 1;
                                    }
                                    if( $last_name != "" ) {
                                        $hasCompletedlast_name = 1;
                                    }
                                    if( $user_email != "" ) {
                                        $hasCompleteduser_email = 1;
                                    }
                                    if( $user_phone_number != "" ) {
                                        $hasCompleteduser_phone_number = 1;
                                    }
                                    if( $user_image != "" ) {
                                        $hasCompleteduser_image = 1;
                                    }
                                    if( $user_age != "" ) {
                                        $hasCompleteduser_age = 1;
                                    }
                                    if( $user_gender != "" ) {
                                        $hasCompleteduser_gender = 1;
                                    }
                                    if( $address1 != "" ) {
                                        $hasCompletedaddress1 = 1;
                                    }
                                    if( $address2 != "" ) {
                                        $hasCompletedaddress2 = 1;
                                    }
                                    if( $usercity != "" ) {
                                        $hasCompletedusercity = 1;
                                    }
                                    if( $userstate != "" ) {
                                        $hasCompleteduserstate = 1;
                                    }
                                    if( $usecontry != "" ) {
                                        $hasCompletedusecontry = 1;
                                    }
                                    if( $usepincode != "" ) {
                                        $hasCompletedusepincode = 1;
                                    }
                                    if( $date_of_birth != "" ) {
                                        $hasCompleteddate_of_birth = 1;
                                    }
                                    if( $passport_number != "" ) {
                                        $hasCompletedpassport_number = 1;
                                    }
                                    if( $country_passport != "" ) {
                                        $hasCompletedcountry_passport = 1;
                                    }
                                    if( $nationality_of_passport_holder != "" ) {
                                        $hasCompletednationality_of_passport_holder = 1;
                                    }
                                    if( $date_of_expiry != "" ) {
                                        $hasCompleteddate_of_expiry = 1;
                                    }
                                    if( $issuing_authority != "" ) {
                                        $hasCompletedissuing_authority = 1;
                                    }
                                    if( $get_user_immigration_document != "" ) {
                                        $hasCompletedget_user_immigration_document = 1;
                                    }
                                    if( $immigration_document_description != "" ) {
                                        $hasCompletedimmigration_document_description = 1;
                                    }
                                    if( $visa_document_description != "" ) {
                                        $hasCompletedvisa_document_description = 1;
                                    }
                                    if( $nricfin_number != "" ) {
                                        $hasCompletednricfin_number = 1;
                                    }
                                    if( $get_user_passport_document != "" ) {
                                        $hasCompletedget_user_passport_document = 1;
                                    }
                                    if( $get_user_nricfin_upload_front_doc != "" ) {
                                        $hasCompletedget_user_nricfin_upload_front_doc = 1;
                                    }
                                    if( $get_user_nricfin_upload_back_doc != "" ) {
                                        $hasCompletedget_user_nricfin_upload_back_doc = 1;
                                    }
                                    if( $get_user_visa_document != "" ) {
                                        $hasCompletedget_user_visa_document = 1;
                                    }


                                    $percentage       = ($hasCompletedfirst_name + $hasCompletedlast_name + $hasCompleteduser_email + $hasCompleteduser_phone_number + $hasCompleteduser_image + $hasCompleteduser_age + $hasCompleteduser_gender + $hasCompletedaddress1 + $hasCompletedaddress2 + $hasCompletedusercity + $hasCompleteduserstate + $hasCompletedusecontry + $hasCompletedusepincode + $hasCompleteddate_of_birth + $hasCompletedpassport_number + $hasCompletedcountry_passport + $hasCompletednationality_of_passport_holder + $hasCompleteddate_of_expiry + $hasCompletedissuing_authority + $hasCompletedimmigration_document_description + $hasCompletednricfin_number + $hasCompletedget_user_passport_document + $hasCompletedget_user_nricfin_upload_front_doc + $hasCompletedget_user_nricfin_upload_back_doc + $hasCompletedget_user_visa_document + $hasCompletedget_user_immigration_document + $hasCompletedvisa_document_description ) / 26 * 100;
                                    $final_percentage = round ( $percentage, 2 );
                                    echo "<div class='profile-progress-wrapper'>Your percentage of profile completenes is <span class='profile-percentage'>" . $final_percentage . "% </span></div>";

                                    echo "<div class='profile-progress-bar'>
                                            
                                            <div style='width:" . $final_percentage . "%; background-color:#54a2d7; height:13px;    border-radius: 30px'></div></div><br>";
                                }
                                ?> 
                                <table class="tbl_account_progress">
                                    <tr>
                                        <td class="td_head">
                                    <multiline><strong >1. Account</strong></multiline>
                                    </td>
                                    <td class="td_content">
                                    <multiline>
                                        <strong>
                                            <?php
                                            $acc_per              = ($hasCompletedfirst_name + $hasCompletedlast_name + $hasCompleteduser_image + $hasCompleteduser_email + $hasCompleteduser_phone_number + $hasCompleteduser_age + $hasCompleteduser_gender) / 6 * 100;
                                            echo $final_acc_per        = round ( $acc_per, 2 ) . ' %';
                                            ?>
                                        </strong>
                                    </multiline>
                                    </td>
                                    </tr>
                                    <tr>
                                        <td class="td_head">
                                    <multiline><strong>2. Address</strong></multiline>
                                    </td>
                                    <td class="td_content">
                                    <multiline>
                                        <strong>
                                            <?php
                                            $add_per              = ($hasCompletedaddress1 + $hasCompletedaddress2 + $hasCompletedusercity + $hasCompleteduserstate + $hasCompletedusecontry + $hasCompletedusepincode) / 6 * 100;
                                            echo $final_add_per        = round ( $add_per, 2 ) . ' %';
                                            ?>
                                        </strong>
                                    </multiline>
                                    </td>
                                    </tr>
                                    <tr>
                                        <td class="td_head">
                                    <multiline><strong>3. Passport Details</strong></multiline>
                                    </td>
                                    <td class="td_content">
                                    <multiline>
                                        <strong>
                                            <?php
                                            $pass_det_per         = ($hasCompleteddate_of_birth + $hasCompletedpassport_number + $hasCompletedcountry_passport + $hasCompletednationality_of_passport_holder + $hasCompleteddate_of_expiry + $hasCompletedissuing_authority + $hasCompletedimmigration_document_description) / 7 * 100;
                                            echo $final_pass_det_per   = round ( $pass_det_per, 2 ) . ' %';
                                            ?>
                                        </strong>
                                    </multiline>
                                    </td>
                                    </tr>
                                    <tr>    
                                        <td class="td_head">
                                    <multiline><strong>4. NRIC/FIN</strong></multiline>
                                    </td>
                                    <td class="td_content">
                                    <multiline>
                                        <strong>
                                            <?php
                                            $nric_det_per         = ($hasCompletednricfin_number + $hasCompletedget_user_nricfin_upload_front_doc + $hasCompletedget_user_nricfin_upload_back_doc) / 3 * 100;
                                            echo $final_nric_det_per   = round ( $nric_det_per, 2 ) . ' %';
                                            ?>
                                        </strong>
                                    </multiline>
                                    </td>
                                    </tr>
                                    <tr>
                                        <td class="td_head">
                                    <multiline><strong>5. Upload Documents</strong></multiline>
                                    </td>
                                    <td class="td_content">
                                    <multiline>
                                        <strong>
                                            <?php
                                            $upload_det_per       = ($hasCompletedget_user_visa_document + $hasCompletedget_user_immigration_document + $hasCompletedvisa_document_description ) / 3 * 100;
                                            echo $final_upload_det_per = round ( $upload_det_per, 2 ) . ' %';
                                            ?>    
                                        </strong>
                                    </multiline>
                                    </td>
                                    </tr>
                                </table>

                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="account_cover">
                                        <div class="account_card">
                                            <?php if( isset ( $message ) && ! empty ( $message ) ) { ?>
                                                <div class="notification <?php echo esc_attr ( $mType ); ?> clearfix">
                                                    <div class="noti-icon fas"> </div>
                                                    <p><?php echo esc_html ( $message ); ?></p>
                                                </div>
                                                <script>setTimeout(function () {
                                                        window.location.href = "https://diverace.chillybin.biz/dashboard-account-settings/";
                                                    }, 1);
                                                </script>
                                            <?php } ?>


                                            <form class="user-profile-form" id="profileupdate" action="" method="POST" enctype="multipart/form-data">


                                                </br></br>

                                                <div class="account_detail_cover">
                                                    <h3>Account Details</h3>
                                                    <div class="">
                                                        <div class="row">
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>First Name <span class="field_is_required">*</span></label>
                                                                <input type="text" id="first_name" name="first_name" required value="<?php
                                                                if( ! empty ( $user_fname ) ) {
                                                                    echo substr ( $user_fname, 0, 1 ) . '*******';
                                                                }
                                                                ?>" class="hiw_input" placeholder="Enter First Name">
                                                                <input type="hidden" id="first_name_hidden" name="first_name_hidden" required value="<?php echo $user_fname; ?>" class="hiw_input" >
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Last Name <span class="field_is_required">*</span></label>
                                                                <input type="text" id="last_name" name="last_name" required value="<?php
                                                                if( ! empty ( $user_lname ) ) {
                                                                    echo substr ( $user_lname, 0, 1 ) . '*******';
                                                                }
                                                                ?>" class="hiw_input" placeholder="Enter Last Name">
                                                                <input type="hidden" id="last_name_hidden" name="last_name_hidden" required value="<?php echo $user_lname; ?>" class="hiw_input" >
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Email <span class="field_is_required">*</span></label>
                                                                <input type="text" id="user_email" name="user_email" required value="<?php
                                                                if( ! empty ( $user_email ) ) {
                                                                    echo substr ( $user_email, 0, 1 ) . '*******';
                                                                }
                                                                ?>" class="hiw_input" placeholder="Enter Email">
                                                                <input type="hidden" id="user_email_hidden" name="user_email_hidden" required value="<?php echo $user_email; ?>" class="hiw_input" >
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Phone number <span class="field_is_required">*</span></label>
                                                                <input type="text" id="user_phone_number" name="user_phone_number" required value="<?php
                                                                if( ! empty ( $user_phone_number ) ) {
                                                                    echo substr ( $user_phone_number, 0, 2 ) . '*******';
                                                                }
                                                                ?>" class="hiw_input" placeholder="Enter Phone Number">
                                                                <input type="hidden" id="user_phone_number_hidden" name="user_phone_number_hidden" required value="<?php echo $user_phone_number; ?>" class="hiw_input" >
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Age <span class="field_is_required">*</span></label>
                                                                <div class="form-group">
                                                                    <div class="input-group">

                                                                        <input type="text" id="user_age" name="user_age" value="<?php
                                                                        if( ! empty ( $user_age ) ) {
                                                                            echo substr ( $user_age, 0, 2 ) . '/**/****';
                                                                        }
                                                                        ?>"  class="form-control hiw_input" placeholder="Enter Age (Select Date of Birth) ">
                                                                        <input type="hidden"  id="user_age_hidden" name="user_age_hidden" value="<?php echo $user_age ?>" class="form-control hiw_input" placeholder="Enter Age (Select Date of Birth) ">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4 radio-btn-wrapper">
                                                                <label>Gender <span class="field_is_required">*</span></label>
                                                                <div class="radio_btn_selection_section">
                                                                    <label><input type="radio" id="male" class="lp_assign_data" name="user_gender" value="male" <?php
                                                                        if( $user_gender == 'male' ) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?> ><span class="gender_label">Male </span></label>
                                                                    <label><input type="radio" id="female" class="lp_assign_data" name="user_gender" value="female" <?php
                                                                        if( $user_gender == 'female' ) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="gender_label">Female </span></label>
                                                                </div>    
                                                            </div> 
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>User Profile Image</label>        
                                                                <input class="hiw_input" name="user_image" type="file" id="user_image" multiple="false"/>
                                                                <?php
                                                                $get_user_image_url = wp_get_attachment_url ( $user_image );
                                                                $image_extention    = pathinfo ( $get_user_image_url, PATHINFO_EXTENSION );

                                                                if( ! empty ( $get_user_image_url ) ) {
                                                                    if( $image_extention == 'pdf' ) {
                                                                        $pdf_icon     = get_theme_file_uri () . '/images/pdf_file_icon.png';
                                                                        $user_img_url = $get_user_image_url;
                                                                        echo '<a href="' . $user_img_url . '" target="_balnk"><img src="' . $pdf_icon . '" width="100" /></a>';
                                                                    } else {
                                                                        $user_img_url = $get_user_image_url;
                                                                        echo '<a href="' . $user_img_url . '" target="_balnk"><img src="' . $user_img_url . '" width="100" /></a>';
                                                                    }
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                    </div>                                                    
                                                </div>
                                                <div class="account_detail_cover mt-5"  >
                                                    <h3>Address</h3>
                                                    <div class="">
                                                        <div class="row">
                                                            <div class="col-sm-12 field_wrap mb-4">
                                                                <label>Address Line 1</label>
                                                                <input type="text" id="user_address_line_1" name="user_address_line_1" value="<?php
                                                                if( ! empty ( $address1 ) ) {
                                                                    echo substr ( $address1, 0, 1 ) . '*******';
                                                                }
                                                                ?>"  class="hiw_input" placeholder="Enter Address Line 1">
                                                                <input type="hidden" id="user_address_line_1_hidden" name="user_address_line_1_hidden" value="<?php echo $address1; ?>" class="hiw_input" >
                                                            </div>
                                                            <div class="col-sm-12 field_wrap mb-4">
                                                                <label>Address Line 2</label>
                                                                <input type="text" id="user_address_line_2" name="user_address_line_2"  value="<?php
                                                                if( ! empty ( $address2 ) ) {
                                                                    echo substr ( $address2, 0, 1 ) . '*******';
                                                                }
                                                                ?>" class="hiw_input" placeholder="Enter Address Line 2">
                                                                <input type="hidden" id="user_address_line_2_hidden" name="user_address_line_2_hidden" value="<?php echo $address2; ?>" class="hiw_input" >
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>City</label>
                                                                <input type="text" id="user_city" name="user_city" value="<?php
                                                                if( ! empty ( $usercity ) ) {
                                                                    echo substr ( $usercity, 0, 1 ) . '*******';
                                                                }
                                                                ?>" class="hiw_input" placeholder="city">
                                                                <input type="hidden" id="user_city_hidden" name="user_city_hidden" value="<?php echo $usercity; ?>" class="hiw_input" >
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>State</label>
                                                                <input type="text" id="user_state" name="user_state"  value="<?php
                                                                if( ! empty ( $userstate ) ) {
                                                                    echo substr ( $userstate, 0, 1 ) . '*******';
                                                                }
                                                                ?>" class="hiw_input" placeholder="state">
                                                                <input type="hidden" id="user_state_hidden" name="user_state_hidden" value="<?php echo $userstate; ?>" class="hiw_input" >
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Country</label>
                                                                <input type="text" id="user_country" name="user_country" value="<?php
                                                                if( ! empty ( $usecontry ) ) {
                                                                    echo substr ( $usecontry, 0, 1 ) . '*******';
                                                                }
                                                                ?>"   class="hiw_input" placeholder="country">
                                                                <input type="hidden" id="user_country_hidden" name="user_country_hidden" value="<?php echo $usecontry; ?>" class="hiw_input" >
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Postcode</label>
                                                                <input type="text" id="user_postcode" name="user_postcode" value="<?php
                                                                if( ! empty ( $usepincode ) ) {
                                                                    echo substr ( $usepincode, 0, 2 ) . '*******';
                                                                }
                                                                ?>" class="hiw_input" placeholder="Postcode">
                                                                <input type="hidden" id="user_postcode_hidden" name="user_postcode_hidden" value="<?php echo $usepincode; ?>" class="hiw_input" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>	

                                                <div class="account_detail_cover mt-5"  >
                                                    <h3>Passport Details</h3>
                                                    <div class="">
                                                        <div class="row">
                                                            <div class="col-sm-12 field_wrap mb-4">
                                                                <label>Date of Birth</label>
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <input type="text" id="date_of_birth" name="date_of_birth" value="<?php echo $date_of_birth; ?>" class="form-control hiw_input" placeholder="Date of Birth">
                                                                        <!--<input type="hidden" id="date_of_birth_hidden" name="date_of_birth_hidden" value="<?php echo $date_of_birth ?>" class="form-control hiw_input" placeholder="Date of Birth">-->
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 field_wrap mb-4">
                                                                <label>Passport Number</label>
                                                                <input type="text" id="passport_number" name="passport_number" value="<?php
                                                                if( ! empty ( $passport_number ) ) {
                                                                    echo substr ( $passport_number, 0, 1 ) . '*******';
                                                                }
                                                                ?>" class="hiw_input" placeholder="Passport Number">
                                                                <input type="hidden" id="passport_number_hidden" name="passport_number_hidden" value="<?php echo $passport_number; ?>" class="hiw_input" >
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Country Passport</label>
                                                                <input type="text" id="country_passport" name="country_passport" value="<?php
                                                                if( ! empty ( $country_passport ) ) {
                                                                    echo substr ( $country_passport, 0, 1 ) . '*******';
                                                                }
                                                                ?>"   class="hiw_input" placeholder="Country Passport">
                                                                <input type="hidden" id="country_passport_hidden" name="country_passport_hidden" value="<?php echo $country_passport; ?>" class="hiw_input" >
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Nationality of passport holder</label>
                                                                <input type="text" id="nationality_of_passport_holder" name="nationality_of_passport_holder" value="<?php
                                                                if( ! empty ( $nationality_of_passport_holder ) ) {
                                                                    echo substr ( $nationality_of_passport_holder, 0, 1 ) . '*******';
                                                                }
                                                                ?>" class="hiw_input" placeholder="Nationality of passport holder">
                                                                <input type="hidden" id="nationality_of_passport_holder_hidden" name="nationality_of_passport_holder_hidden" value="<?php echo $nationality_of_passport_holder; ?>" class="hiw_input" >
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Date of expiry</label>
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <input type="text" id="date_of_expiry" name="date_of_expiry" value="<?php echo $date_of_expiry ?>" class="form-control hiw_input" placeholder="Date of expiry">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>	
                                                            </div>

                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Issuing authority</label>
                                                                <input type="text" id="issuing_authority" name="issuing_authority" value="<?php
                                                                if( ! empty ( $issuing_authority ) ) {
                                                                    echo substr ( $issuing_authority, 0, 1 ) . '*******';
                                                                }
                                                                ?>" class="hiw_input" placeholder="Issuing authority">
                                                                <input type="hidden" id="issuing_authority_hidden" name="issuing_authority_hidden" value="<?php echo $issuing_authority; ?>" class="hiw_input" >
                                                            </div>

                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Passport Document</label>        
                                                                <input class="hiw_input" name="user_passport_document" type="file" id="user_passport_document" multiple="false"/>
                                                                <?php
                                                                /* echo '<pre>';
                                                                  print_r($get_user_passport_document); */
                                                                $get_user_passport_document_url = wp_get_attachment_url ( $get_user_passport_document );
                                                                $passport_document_extention    = pathinfo ( $get_user_passport_document_url, PATHINFO_EXTENSION );

                                                                if( ! empty ( $get_user_passport_document_url ) ) {
                                                                    if( $passport_document_extention == 'pdf' ) {
                                                                        $pdf_icon              = get_theme_file_uri () . '/images/pdf_file_icon.png';
                                                                        $user_passport_img_url = $get_user_passport_document_url;
                                                                        echo '<a href="' . $user_passport_img_url . '" target="_balnk"><img src="' . $pdf_icon . '" width="100" /></a>';
                                                                    } else {
                                                                        $user_passport_img_url = $get_user_passport_document_url;
                                                                        echo '<a href="' . $user_passport_img_url . '" target="_balnk"><img src="' . $user_passport_img_url . '" width="100" /></a>';
                                                                    }
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>	

                                                <div class="account_detail_cover mt-5"  >
                                                    <h3>NRIC/FIN Details</h3>
                                                    <div class="">
                                                        <div class="row">
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>NRIC/FIN Number</label>
                                                                <input type="text" id="nricfin_number" name="nricfin_number" value="<?php
                                                                if( ! empty ( $nricfin_number ) ) {
                                                                    echo substr ( $nricfin_number, 0, 1 ) . '*******';
                                                                }
                                                                ?>" class="hiw_input" placeholder="NRIC/FIN Number">
                                                                <input type="hidden" id="nricfin_number_hidden" name="nricfin_number_hidden" value="<?php echo $nricfin_number; ?>" class="hiw_input" >
                                                            </div>

                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>NRIC/FIN Upload Front Document</label>
                                                                <input class="hiw_input" name="nricfin_upload_front_doc" type="file" id="nricfin_upload_front_doc" multiple="false"/>
                                                                <?php
                                                                $get_user_nricfin_upload_front_url = wp_get_attachment_url ( $get_user_nricfin_upload_front_doc );
                                                                $nricfin_upload_front_extention    = pathinfo ( $get_user_nricfin_upload_front_url, PATHINFO_EXTENSION );
                                                                if( ! empty ( $get_user_nricfin_upload_front_url ) ) {
                                                                    if( $nricfin_upload_front_extention == 'pdf' ) {
                                                                        $pdf_icon                      = get_theme_file_uri () . '/images/pdf_file_icon.png';
                                                                        $user_nricfin_upload_front_url = $get_user_nricfin_upload_front_url;
                                                                        echo '<a href="' . $user_nricfin_upload_front_url . '" target="_balnk"><img src="' . $pdf_icon . '" width="100" /></a>';
                                                                    } else {
                                                                        $user_nricfin_upload_front_url = $get_user_nricfin_upload_front_url;
                                                                        echo '<a href="' . $user_nricfin_upload_front_url . '" target="_balnk"><img src="' . $user_nricfin_upload_front_url . '" width="100" /></a>';
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>NRIC/FIN Upload Back Document</label>        
                                                                <input class="hiw_input" name="nricfin_upload_back_doc" type="file" id="nricfin_upload_back_doc" multiple="false"/>
                                                                <?php
                                                                $get_user_nricfin_upload_back_url = wp_get_attachment_url ( $get_user_nricfin_upload_back_doc );
                                                                $nricfin_upload_back_extention    = pathinfo ( $get_user_nricfin_upload_back_url, PATHINFO_EXTENSION );
                                                                if( ! empty ( $get_user_nricfin_upload_back_url ) ) {
                                                                    if( $nricfin_upload_back_extention == 'pdf' ) {
                                                                        $pdf_icon                     = get_theme_file_uri () . '/images/pdf_file_icon.png';
                                                                        $user_nricfin_upload_back_url = $get_user_nricfin_upload_back_url;
                                                                        echo '<a href="' . $user_nricfin_upload_back_url . '" target="_balnk"><img src="' . $pdf_icon . '" width="100" /></a>';
                                                                    } else {
                                                                        $user_nricfin_upload_back_url = $get_user_nricfin_upload_back_url;
                                                                        echo '<a href="' . $user_nricfin_upload_back_url . '" target="_balnk"><img src="' . $user_nricfin_upload_back_url . '" width="100" /></a>';
                                                                    }
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>            

                                                <div class="account_detail_cover mt-5" style="display:none">
                                                    <h3>Additional Info</h3>
                                                    <div class="">
                                                        <div class="row">
                                                            <!-- <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Facebook</label>
                                                                <input type="text" name="facebook_url" value="<?php echo $user_facebook; ?>" class="hiw_input" placeholder="Enter Facebook URL">
                                                            </div> 
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Twitter</label>
                                                                <input type="text" name="twitter_url" value="<?php echo $user_twitter; ?>" class="hiw_input" placeholder="Enter Twitter URL">
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Linkedin</label>
                                                                <input type="text" name="linkedin_url" value="<?php echo $user_linkedin; ?>" class="hiw_input" placeholder="Enter Linkedin URL">
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Instagram</label>
                                                                <input type="text" name="instagram_url" value="<?php echo $user_instagram; ?>" class="hiw_input" placeholder="Enter Instagram URL">
                                                            </div> -->
                                                            <!-- <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Pinterest</label>
                                                                <input type="text" name="pinterest_url" value="<?php echo $user_pinterest; ?>" class="hiw_input" placeholder="Enter Pinterest URL">
                                                            </div> -->                                                        
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="account_detail_cover mt-5">
                                                    <h3>Change Password</h3>
                                                    <div class="">
                                                        <div class="row">
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Old Password</label>
                                                                <input type="password" id="current_password" name="current_password" value="" class="hiw_input" placeholder="Enter Your Current Password"> 
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>New Password</label>
                                                                <input type="password" id="new_password" name="new_password" value="" class="hiw_input" placeholder="Enter New Password">
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mb-4">
                                                                <label>Confirm Password</label>
                                                                <input type="password" id="confirm_new_password" name="confirm_new_password" value="" class="hiw_input" placeholder="Enter Confirm Password"> 
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="account_detail_cover mt-5">
                                                    <h3>Upload Documents</h3>
                                                    <div class="">
                                                        <div class="row">

                                                            <div class="col-sm-12 field_wrap mb-4">
                                                                <label>Immigration Document</label>
                                                                <input class="hiw_input" name="user_immigration_document" type="file" id="user_immigration_document" multiple="false"/>

                                                                <?php
                                                                $get_user_immigration_document_url = wp_get_attachment_url ( $get_user_immigration_document );
                                                                $immigration_document_extention    = pathinfo ( $get_user_immigration_document_url, PATHINFO_EXTENSION );

                                                                if( ! empty ( $get_user_immigration_document_url ) ) {
                                                                    if( $immigration_document_extention == 'pdf' ) {
                                                                        $pdf_icon              = get_theme_file_uri () . '/images/pdf_file_icon.png';
                                                                        $user_passport_img_url = $get_user_immigration_document_url;
                                                                        echo '<a href="' . $user_passport_img_url . '" target="_balnk"><img src="' . $pdf_icon . '" width="100" /></a>';
                                                                    } else {
                                                                        $user_passport_img_url = $get_user_immigration_document_url;
                                                                        echo '<a href="' . $user_passport_img_url . '" target="_balnk"><img src="' . $user_passport_img_url . '" width="100" /></a>';
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                            <div class="col-sm-12 field_wrap mb-4">
                                                                <label>Immigration Document Description</label>
                                                                <input type="text" id="immigration_document_description" name="immigration_document_description" value="<?php
                                                                if( ! empty ( $immigration_document_description ) ) {
                                                                    echo substr ( $immigration_document_description, 0, 2 ) . '*******';
                                                                }
                                                                ?>" class="hiw_input" placeholder="Enter Description">
                                                                <input type="hidden" id="immigration_document_description_hidden" name="immigration_document_description_hidden" value="<?php echo $immigration_document_description; ?>" class="hiw_input" >
                                                            </div>

                                                            <div class="col-sm-12 field_wrap mb-4">
                                                                <label>Visa Document</label>
                                                                <input class="hiw_input" name="user_visa_document" type="file" id="user_visa_document" multiple="false"/>
                                                                <?php
                                                                $get_user_visa_document_url = wp_get_attachment_url ( $get_user_visa_document );
                                                                $visa_document_extention    = pathinfo ( $get_user_visa_document_url, PATHINFO_EXTENSION );

                                                                if( ! empty ( $get_user_visa_document_url ) ) {
                                                                    if( $visa_document_extention == 'pdf' ) {
                                                                        $pdf_icon          = get_theme_file_uri () . '/images/pdf_file_icon.png';
                                                                        $user_visa_doc_url = $get_user_visa_document_url;
                                                                        echo '<a href="' . $user_visa_doc_url . '" target="_balnk"><img src="' . $pdf_icon . '" width="100" /></a>';
                                                                    } else {
                                                                        $user_visa_doc_url = $get_user_visa_document_url;
                                                                        echo '<a href="' . $user_visa_doc_url . '" target="_balnk"><img src="' . $get_user_visa_doc_url . '" width="100" /></a>';
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                            <div class="col-sm-12 field_wrap mb-4">
                                                                <label>Visa Document Description</label>
                                                                <input type="text" id="visa_document_description" name="visa_document_description" value="<?php
                                                                if( ! empty ( $visa_document_description ) ) {
                                                                    echo substr ( $visa_document_description, 0, 2 ) . '*******';
                                                                }
                                                                ?>" class="hiw_input" placeholder="Enter Description">
                                                                <input type="hidden" id="visa_document_description_hidden" name="visa_document_description_hidden" value="<?php echo $visa_document_description; ?>" class="hiw_input" >
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <input type="submit" name="profileupdate" value="Save Details" class="save_detail_btn" />

                                                </div>
                                            </form>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.Left col -->
                </div>
                <!-- /.row (main row) -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <?php
    }
}
?>

<?php get_footer ( 'dashboard' ); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/js/bootstrap-datepicker.min.js"></script>