<?php
/*
  Template Name: Dashboard Index page
  Template Post Type: post, page
 */

global $post, $wpdb;

$userID = '';
if( is_user_logged_in () ) {
    $current_user = wp_get_current_user ();
    $userID       = $current_user->ID;
    $user_name    = $current_user->display_name;

//$get_user_passport_document = get_user_meta($userID, 'user_passport_document',true);
} else {
    wp_redirect ( home_url () );
    exit;
}

get_header ( 'dashboard' );
// ======person detail tab=========
// User Name
$user_fname = get_the_author_meta ( 'first_name', $userID );

$user_lname = get_the_author_meta ( 'last_name', $userID );

// User contact meta
$user_email        = get_the_author_meta ( 'person_detail_email', $userID );
$user_phone_number = get_field ( 'user_phone_number', 'user_' . $userID );
$user_age          = get_field ( 'user_age', 'user_' . $userID );
$user_gender       = get_field ( 'user_gender', 'user_' . $userID );

if( isset ( $_POST[ 'persondetailupload' ] ) ) {

    // echo"<pre>";
    // print_r($_POST);

    $pd_order_person_number = $_POST[ 'pd_order_person_number' ];
    $pd_order_person_number = intval ( $pd_order_person_number ) - intval ( 1 );

    $pd_total_person    = $_POST[ 'pd_total_person' ];
    $pd_person_order_id = $_POST[ 'pd_person_order_id' ];

    $first_name = esc_html ( $_POST[ 'first_name_person_detail' ] );

    $user_email        = esc_html ( $_POST[ 'person_detail_email' ] );
    $user_phone_number = esc_html ( $_POST[ 'person_detail_ph_number' ] );

    $user_gender = esc_html ( $_POST[ 'persondetailgender' ] );

    //fisrt name
    $pax_user_pd_doc_1     = "pax_data_" . $pd_order_person_number . "_pax_name";
    $pax_user_pd_doc_2     = "_pax_data_" . $pd_order_person_number . "_pax_name";
    $pax_user_pd_doc_value = $first_name;
    if( ! $pax_user_pd_doc_value == "" ) {
        update_post_meta ( $pd_person_order_id, $pax_user_pd_doc_1, $pax_user_pd_doc_value );
    }
    if( ! $pax_user_pd_doc_2 == "" ) {
        update_post_meta ( $pd_person_order_id, $pax_user_pd_doc_2, "field_5ffe80462ca18" );
    }

    //email
    $pax_user_email_doc_1        = "pax_data_" . $pd_order_person_number . "_pax_email";
    $pax_user_email_doc_2        = "_pax_data_" . $pd_order_person_number . "_pax_email";
    $pax_user_email_pd_doc_value = $user_email;
    if( ! $pax_user_email_pd_doc_value == "" ) {
        update_post_meta ( $pd_person_order_id, $pax_user_email_doc_1, $pax_user_email_pd_doc_value );
    }
    if( ! $pax_user_email_doc_2 == "" ) {
        update_post_meta ( $pd_person_order_id, $pax_user_email_doc_2, "field_5ffe812ced146" );
    }


    //phone number
    $pax_user_phone_doc_1        = "pax_data_" . $pd_order_person_number . "_pax_phone_number";
    $pax_user_phone_doc_2        = "_pax_data_" . $pd_order_person_number . "_pax_phone_number";
    $pax_user_phone_pd_doc_value = $user_phone_number;
    if( ! $pax_user_phone_pd_doc_value == "" ) {
        update_post_meta ( $pd_person_order_id, $pax_user_phone_doc_1, $pax_user_phone_pd_doc_value );
    }
    if( ! $pax_user_phone_doc_2 == "" ) {
        update_post_meta ( $pd_person_order_id, $pax_user_phone_doc_2, "field_600aa69c2c944" );
    }


    //gender
    $pax_user_gender_doc_1        = "pax_data_" . $pd_order_person_number . "_selected_gender";
    $pax_user_gender_doc_2        = "_pax_data_" . $pd_order_person_number . "_selected_gender";
    $pax_user_gender_pd_doc_value = $user_gender;
    if( ! $pax_user_gender_pd_doc_value == "" ) {
        update_post_meta ( $pd_person_order_id, $pax_user_gender_doc_1, $pax_user_gender_pd_doc_value );
    }
    if( ! $pax_user_gender_doc_2 == "" ) {
        update_post_meta ( $pd_person_order_id, $pax_user_gender_doc_2, "field_5f6c392f6ff4e" );
    }
}



/* ======== persondocuploded CODE ========== */
if( isset ( $_POST[ 'persondocuploded' ] ) ) {

    $order_person_number = $_POST[ 'order_person_number' ];
    $order_person_number = intval ( $order_person_number ) - intval ( 1 );

    $total_persons   = $_POST[ 'total_persons' ];
    $person_order_id = $_POST[ 'person_order_id' ];

    $user_passport_document    = $_POST[ 'user_passport_document' ];
    $user_immigration_document = $_POST[ 'user_immigration_document' ];
    $user_visa_document        = $_POST[ 'user_visa_document' ];

    $user_immigration_desc = $_POST[ 'user_immigration_desc' ];
    $user_visa_desc        = $_POST[ 'user_visa_desc' ];

    // These files need to be included as dependencies when on the front end.
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );

    // user_medical_clearance_doc multiple file uploads.
    if( $_FILES ) {
        $files = $_FILES[ "user_medical_clearance_doc" ];

        $medical_documents_array = [];
        $medical_file_url_array  = [];
        $i                       = 0;
        foreach ( $files[ 'name' ] as $key => $value ) {

            $pax_medical_clr_data  = "pax_data_documents_" . $order_person_number . "_pddoc_user_medical_clearance_documents";
            $pax_medical_clr_data2 = "_pax_data_documents_" . $order_person_number . "_pddoc_user_medical_clearance_documents";
            $pax_medical_clr_value = count ( $files[ 'name' ] );
            update_post_meta ( $person_order_id, $pax_medical_clr_data, $pax_medical_clr_value );
            update_post_meta ( $person_order_id, $pax_medical_clr_data2, "field_60e54a8f108b0" );

            if( $files[ 'name' ][ $key ] ) {
                $file = array (
                    'name'     => $files[ 'name' ][ $key ],
                    'type'     => $files[ 'type' ][ $key ],
                    'tmp_name' => $files[ 'tmp_name' ][ $key ],
                    'error'    => $files[ 'error' ][ $key ],
                    'size'     => $files[ 'size' ][ $key ]
                );

                if( ($file[ 'size' ] > 2097152 ) ) {
                    $message = 'File too large. File must be less than 2 megabytes.';
                    echo '<script type="text/javascript">alert("' . $message . '");</script>';
                    return false;
                } elseif( ($file[ 'type' ] != ".doc") && ($file[ 'type' ] != ".docx") && ($file[ 'type' ] != "application/msword") && ($file[ 'type' ] != "application/vnd.openxmlformats-officedocument.wordprocessingml.document") && ($file[ 'type' ] != "application/vnd.ms-excel") && ($file[ 'type' ] != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") && ($file[ 'type' ] != ".csv") ) {
                    $message = 'Invalid file type. Only PDF, JPG,JPEG types are accepted.';
                    echo '<script type="text/javascript">alert("' . $message . '");</script>';
                    return false;
                } else {
                    $_FILES[ "upload_medical_files" ] = $file;
                    $medical_attachment_id            = media_handle_upload ( "upload_medical_files", 0 );
                    $medical_file_url                 = wp_get_attachment_url ( $medical_attachment_id );
                    array_push ( $medical_documents_array, $medical_attachment_id );
                    array_push ( $medical_file_url_array, $medical_file_url );
                    $medicalfile_urls                 = implode ( ",", $medical_documents_array );

                    $pax_medical_clr_file_data  = "pax_data_documents_" . $order_person_number . "_pddoc_user_medical_clearance_documents_" . $i . "_medicalclearance_doc";
                    $pax_medical_clr_file_data2 = "_pax_data_documents_" . $order_person_number . "_pddoc_user_medical_clearance_documents_" . $i . "_medicalclearance_doc";
                    $pax_medical_clr_file_value = $medical_attachment_id;
                    update_post_meta ( $person_order_id, $pax_medical_clr_file_data, $pax_medical_clr_file_value );
                    update_post_meta ( $person_order_id, $pax_medical_clr_file_data2, "field_60e5508012253" );
                }
            }
            $i ++;
        }
    }


    // Let WordPress handle the upload.
    $user_passport_document    = media_handle_upload ( 'user_passport_document', 0 );
    $user_immigration_document = media_handle_upload ( 'user_immigration_document', 0 );
    $user_visa_document        = media_handle_upload ( 'user_visa_document', 0 );

    update_post_meta ( $person_order_id, 'pax_data_documents', $total_persons );
    update_post_meta ( $person_order_id, '_pax_data_documents', "field_60dc373a3c20c" );

    //passport document
    $pax_user_pass_doc_1     = "pax_data_documents_" . $order_person_number . "_pddoc_user_passport_document";
    $pax_user_pass_doc_2     = "_pax_data_documents_" . $order_person_number . "_pddoc_user_passport_document";
    $pax_user_pass_doc_value = $user_passport_document;
    if( ! $pax_user_pass_doc_value == "" ) {
        update_post_meta ( $person_order_id, $pax_user_pass_doc_1, $pax_user_pass_doc_value );
    }
    if( ! $pax_user_pass_doc_2 == "" ) {
        update_post_meta ( $person_order_id, $pax_user_pass_doc_2, "field_60dc37843c21a" );
    }

    //immigration document
    $pax_user_immig_doc_1     = "pax_data_documents_" . $order_person_number . "_pddoc_user_immigration_document";
    $pax_user_immig_doc_2     = "_pax_data_documents_" . $order_person_number . "_pddoc_user_immigration_document";
    $pax_user_immig_doc_value = $user_immigration_document;
    if( ! $pax_user_immig_doc_value == "" ) {
        update_post_meta ( $person_order_id, $pax_user_immig_doc_1, $pax_user_immig_doc_value );
    }
    if( ! $pax_user_immig_doc_2 == "" ) {
        update_post_meta ( $person_order_id, $pax_user_immig_doc_2, "field_60dc37fb3c21b" );
    }

    //Visa document
    $pax_user_visa_doc_1     = "pax_data_documents_" . $order_person_number . "_pddoc_user_visa_document";
    $pax_user_visa_doc_2     = "_pax_data_documents_" . $order_person_number . "_pddoc_user_visa_document";
    $pax_user_visa_doc_value = $user_visa_document;
    if( ! $pax_user_visa_doc_value == "" ) {
        update_post_meta ( $person_order_id, $pax_user_visa_doc_1, $pax_user_visa_doc_value );
    }
    if( ! $pax_user_visa_doc_2 == "" ) {
        update_post_meta ( $person_order_id, $pax_user_visa_doc_2, "field_60dc381c3c21c" );
    }

    //Immigration_document_description
    $pax_user_immi_doc_desc1  = "pax_data_documents_" . $order_person_number . "_pddoc_user_immigration_document_description";
    $pax_user_immi_doc_desc2  = "_pax_data_documents_" . $order_person_number . "_pddoc_user_immigration_document_description";
    $pax_user_immi_doc_value1 = $user_immigration_desc;
    if( ! $pax_user_immi_doc_value1 == "" ) {
        update_post_meta ( $person_order_id, $pax_user_immi_doc_desc1, $pax_user_immi_doc_value1 );
    }
    if( ! $pax_user_immi_doc_desc2 == "" ) {
        update_post_meta ( $person_order_id, $pax_user_immi_doc_desc2, "field_60dc692fd78e8" );
    }

    // Visa_document_description
    $pax_user_visa_doc_desc1  = "pax_data_documents_" . $order_person_number . "_pddoc_user_visa_document_description";
    $pax_user_visa_doc_desc2  = "_pax_data_documents_" . $order_person_number . "_pddoc_user_visa_document_description";
    $pax_user_visa_doc_value2 = $user_visa_desc;
    if( ! $pax_user_visa_doc_value2 == "" ) {
        update_post_meta ( $person_order_id, $pax_user_visa_doc_desc1, $pax_user_visa_doc_value2 );
    }
    if( ! $pax_user_visa_doc_desc2 == "" ) {
        update_post_meta ( $person_order_id, $pax_user_visa_doc_desc2, "field_60dc69c95cb1c" );
    }
}


/* <<<<< ================== labilityreleasedocuploded ==================== >>>> */

if( isset ( $_POST[ 'labilityreleasedocuploded' ] ) ) {

    $lr_order_person_number = $_POST[ 'lr_order_person_number' ];
    $lr_order_person_number = intval ( $lr_order_person_number ) - intval ( 1 );

    $lr_total_persons   = $_POST[ 'lr_total_persons' ];
    $lr_person_order_id = $_POST[ 'lr_person_order_id' ];

    $user_lability_release_document = $_POST[ 'user_lability_release_document' ];

    // Let WordPress handle the upload.
    $user_lability_release_document = media_handle_upload ( 'user_lability_release_document', 0 );

    update_post_meta ( $lr_person_order_id, 'pax_lability_release_data', $lr_total_persons );
    update_post_meta ( $lr_person_order_id, '_pax_lability_release_data', "field_60f155d4bd764" );

    //_lr_pax_person_number Upload
    $pax_user_lr_person_number_1     = "pax_lability_release_data_" . $lr_order_person_number . "_lr_pax_person_number";
    $pax_user_lr_person_number_2     = "_pax_lability_release_data_" . $lr_order_person_number . "_lr_pax_person_number";
    $pax_user_lr_person_number_value = 'Person' . $lr_order_person_number;
    if( ! $pax_user_lr_person_number_value == "" ) {
        update_post_meta ( $lr_person_order_id, $pax_user_lr_person_number_1, $pax_user_lr_person_number_value );
    }
    if( ! $pax_user_lr_person_number_2 == "" ) {
        update_post_meta ( $lr_person_order_id, $pax_user_lr_person_number_2, "field_60f155d4bd765" );
    }

    //user_lability_release_document Upload
    $pax_user_lr_doc_1     = "pax_lability_release_data_" . $lr_order_person_number . "_plrddoc_user_lability_release_document";
    $pax_user_lr_doc_2     = "_pax_lability_release_data_" . $lr_order_person_number . "_plrddoc_user_lability_release_document";
    $pax_user_lr_doc_value = $user_lability_release_document;
    if( ! $pax_user_lr_doc_value == "" ) {
        update_post_meta ( $lr_person_order_id, $pax_user_lr_doc_1, $pax_user_lr_doc_value );
    }
    if( ! $pax_user_lr_doc_2 == "" ) {
        update_post_meta ( $lr_person_order_id, $pax_user_lr_doc_2, "field_60f155d4bd766" );
    }
}

/* <<<<< ================== medicalstatementdocuploded ==================== >>>> */

if( isset ( $_POST[ 'medicalstatementdocuploded' ] ) ) {

    $ms_order_person_number = $_POST[ 'ms_order_person_number' ];
    $ms_order_person_number = intval ( $ms_order_person_number ) - intval ( 1 );

    $ms_total_persons   = $_POST[ 'ms_total_persons' ];
    $ms_person_order_id = $_POST[ 'ms_person_order_id' ];

    $user_medical_statement_document        = $_POST[ 'user_medical_statement_document' ];
    $user_ms_replay_document                = $_POST[ 'user_ms_replay_document' ];
    $user_medical_statement_reply           = $_POST[ 'pmsddoc_user_ms_replay' ];
    $pmsddoc_user_ms_ari_symptoms           = $_POST[ 'pmsddoc_user_ms_ari_symptoms' ];
    $pmsddoc_user_ms_returned_from_overseas = $_POST[ 'pmsddoc_user_ms_returned_from_overseas' ];
    $pmsddoc_user_ms_contact_covid_19       = $_POST[ 'pmsddoc_user_ms_contact_covid_19' ];
    $pmsddoc_user_ms_take_vacin             = $_POST[ 'pmsddoc_user_ms_take_vacin' ];
    $pmsddoc_user_ms_given_vacin_name       = $_POST[ 'pmsddoc_user_ms_given_vacin_name' ];

    // Let WordPress handle the upload.
    $user_medical_statement_document = media_handle_upload ( 'user_medical_statement_document', 0 );

    update_post_meta ( $ms_person_order_id, 'pax_medical_statement_data', $ms_total_persons );
    update_post_meta ( $ms_person_order_id, '_pax_medical_statement_data', 'field_60f663714d3f2' );

    //_ms_pax_person_number 
    $pax_user_ms_person_number_1     = "pax_medical_statement_data_" . $ms_order_person_number . "_ms_pax_person_number";
    $pax_user_ms_person_number_2     = "_pax_medical_statement_data_" . $ms_order_person_number . "_ms_pax_person_number";
    $pax_user_ms_person_number_value = 'Person' . $ms_order_person_number;
    if( ! $pax_user_ms_person_number_value == "" ) {
        update_post_meta ( $ms_person_order_id, $pax_user_ms_person_number_1, $pax_user_ms_person_number_value );
    }
    if( ! $pax_user_ms_person_number_2 == "" ) {
        update_post_meta ( $ms_person_order_id, $pax_user_ms_person_number_2, "field_60f663714d3f3" );
    }

    //_pmsddoc_user_ms_document Upload
    $user_ms_replay_document = media_handle_upload ( 'user_ms_replay_document', 0 );

    $pax_user_ms_reply_doc_1     = "pax_medical_statement_data_" . $ms_order_person_number . "_pmsddoc_user_ms_document_reply";
    $pax_user_ms_reply_doc_2     = "_pax_medical_statement_data_" . $ms_order_person_number . "_pmsddoc_user_ms_document_reply";
    $pax_user_ms_reply_doc_value = $user_ms_replay_document;
    if( ! $pax_user_ms_reply_doc_value == "" ) {
        update_post_meta ( $ms_person_order_id, $pax_user_ms_reply_doc_1, $pax_user_ms_reply_doc_value );
    }
    if( ! $pax_user_ms_reply_doc_2 == "" ) {
        update_post_meta ( $ms_person_order_id, $pax_user_ms_reply_doc_2, "field_6107ffe400c6a" );
    }

    //_pmsddoc_user_ms_document Upload
    $pax_user_ms_doc_1     = "pax_medical_statement_data_" . $ms_order_person_number . "_pmsddoc_user_ms_document";
    $pax_user_ms_doc_2     = "_pax_medical_statement_data_" . $ms_order_person_number . "_pmsddoc_user_ms_document";
    $pax_user_ms_doc_value = $user_medical_statement_document;
    if( ! $pax_user_ms_doc_value == "" ) {
        update_post_meta ( $ms_person_order_id, $pax_user_ms_doc_1, $pax_user_ms_doc_value );
    }
    if( ! $pax_user_ms_doc_2 == "" ) {
        update_post_meta ( $ms_person_order_id, $pax_user_ms_doc_2, "field_60f663714d3f4" );
    }


    //_pmsddoc_user_ms_replay 
    $pax_user_ms_reply_1     = "pax_medical_statement_data_" . $ms_order_person_number . "_pmsddoc_user_ms_replay";
    $pax_user_ms_reply_2     = "_pax_medical_statement_data_" . $ms_order_person_number . "_pmsddoc_user_ms_replay";
    $pax_user_ms_reply_value = $user_medical_statement_reply;
    if( ! $pax_user_ms_reply_value == "" ) {
        update_post_meta ( $ms_person_order_id, $pax_user_ms_reply_1, $pax_user_ms_reply_value );
    }
    if( ! $pax_user_ms_reply_2 == "" ) {
        update_post_meta ( $ms_person_order_id, $pax_user_ms_reply_2, "field_60f663c84d3f5" );
    }

    //_pmsddoc_user_ms_ari_symptoms 
    $pmsddoc_user_ms_ari_symptoms_1     = "pax_medical_statement_data_" . $ms_order_person_number . "_pmsddoc_user_ms_ari_symptoms";
    $pmsddoc_user_ms_ari_symptoms_2     = "_pax_medical_statement_data_" . $ms_order_person_number . "_pmsddoc_user_ms_ari_symptoms";
    $pmsddoc_user_ms_ari_symptoms_value = $pmsddoc_user_ms_ari_symptoms;
    if( ! $pmsddoc_user_ms_ari_symptoms_value == "" ) {
        update_post_meta ( $ms_person_order_id, $pmsddoc_user_ms_ari_symptoms_1, $pmsddoc_user_ms_ari_symptoms_value );
    }
    if( ! $pmsddoc_user_ms_ari_symptoms_2 == "" ) {
        update_post_meta ( $ms_person_order_id, $pmsddoc_user_ms_ari_symptoms_2, "field_60f6648a4d3f7" );
    }

    //_pmsddoc_user_ms_returned_from_overseas 
    $pmsddoc_user_ms_returned_from_overseas_1     = "pax_medical_statement_data_" . $ms_order_person_number . "_pmsddoc_user_ms_returned_from_overseas";
    $pmsddoc_user_ms_returned_from_overseas_2     = "_pax_medical_statement_data_" . $ms_order_person_number . "_pmsddoc_user_ms_returned_from_overseas";
    $pmsddoc_user_ms_returned_from_overseas_value = $pmsddoc_user_ms_returned_from_overseas;
    if( ! $pmsddoc_user_ms_returned_from_overseas_value == "" ) {
        update_post_meta ( $ms_person_order_id, $pmsddoc_user_ms_returned_from_overseas_1, $pmsddoc_user_ms_returned_from_overseas_value );
    }
    if( ! $pmsddoc_user_ms_returned_from_overseas_2 == "" ) {
        update_post_meta ( $ms_person_order_id, $pmsddoc_user_ms_returned_from_overseas_2, "field_60f664ba4d3fa" );
    }

    //_pmsddoc_user_ms_contact_covid_19 
    $pmsddoc_user_ms_contact_covid_19_1     = "pax_medical_statement_data_" . $ms_order_person_number . "_pmsddoc_user_ms_contact_covid_19";
    $pmsddoc_user_ms_contact_covid_19_2     = "_pax_medical_statement_data_" . $ms_order_person_number . "_pmsddoc_user_ms_contact_covid_19";
    $pmsddoc_user_ms_contact_covid_19_value = $pmsddoc_user_ms_contact_covid_19;
    if( ! $pmsddoc_user_ms_contact_covid_19_value == "" ) {
        update_post_meta ( $ms_person_order_id, $pmsddoc_user_ms_contact_covid_19_1, $pmsddoc_user_ms_contact_covid_19_value );
    }
    if( ! $pmsddoc_user_ms_contact_covid_19_2 == "" ) {
        update_post_meta ( $ms_person_order_id, $pmsddoc_user_ms_contact_covid_19_2, "field_60f664d14d3fb" );
    }

    $pmsddoc_user_ms_take_vacin_1     = "pax_medical_statement_data_" . $ms_order_person_number . "_pmsddoc_user_ms_take_vacin";
    $pmsddoc_user_ms_take_vacin_2     = "_pax_medical_statement_data_" . $ms_order_person_number . "_pmsddoc_user_ms_take_vacin";
    $pmsddoc_user_ms_take_vacin_value = $pmsddoc_user_ms_take_vacin;
    if( ! $pmsddoc_user_ms_take_vacin_value == "" ) {
        update_post_meta ( $ms_person_order_id, $pmsddoc_user_ms_take_vacin_1, $pmsddoc_user_ms_take_vacin_value );
    }
    if( ! $pmsddoc_user_ms_take_vacin_2 == "" ) {
        update_post_meta ( $ms_person_order_id, $pmsddoc_user_ms_take_vacin_2, "field_60f665094d3fc" );
    }
    $pmsddoc_user_ms_given_vacin_name_1     = "pax_medical_statement_data_" . $ms_order_person_number . "_pmsddoc_user_ms_given_vacin_name";
    $pmsddoc_user_ms_given_vacin_name_2     = "_pax_medical_statement_data_" . $ms_order_person_number . "_pmsddoc_user_ms_given_vacin_name";
    $pmsddoc_user_ms_given_vacin_name_value = $pmsddoc_user_ms_given_vacin_name;
    if( ! $pmsddoc_user_ms_given_vacin_name_value == "" ) {
        update_post_meta ( $ms_person_order_id, $pmsddoc_user_ms_given_vacin_name_1, $pmsddoc_user_ms_given_vacin_name_value );
    }
    if( ! $pmsddoc_user_ms_given_vacin_name_2 == "" ) {
        update_post_meta ( $ms_person_order_id, $pmsddoc_user_ms_given_vacin_name_2, "field_60f665394d3fe" );
    }
}
?>

<?php
if( have_posts () ) {
    while ( have_posts () ) {
        the_post ();
        //get_template_part( 'content', 'page' );
        ?>
        <section class="content">
            <div class="container-fluid">               
                <div class="row">
                    <!-- Left col -->
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">
                                    Dashboard <?php //echo get_theme_file_uri();                           ?>
                                </h2>
                                <?php
                                if( ! empty ( $userID ) ) {
                                    $user_referral_code = get_user_meta ( $userID, 'user_referral_code', true );
                                }
                                if( $user_referral_code ) {
                                    ?>
                                    <div class="display_user_referral_code">
                                        <div class="text-center">This is your referral code: <?php echo $user_referral_code; ?></div>  
                                    </div> 
                                <?php }
                                ?>                           
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="upcoming_trip">
                                        <?php
                                        $user = new WP_User ( $userID );
                                        if( ! empty ( $user->roles ) && is_array ( $user->roles ) ) {
                                            foreach ( $user->roles as $role ) {
                                                if( $role == 'agent' ) {
                                                    $args_order_history = array (
                                                        'post_type'      => 'orders',
                                                        'post_status'    => 'publish',
                                                        'posts_per_page' => -1,
                                                        'orderby'        => 'date',
                                                        'meta_query'     => array (
                                                            'relation' => 'AND',
                                                            array (
                                                                'key'     => 'user_id',
                                                                'value'   => $userID,
                                                                'compare' => '=',
                                                            ),
                                                        ),
                                                    );
                                                } elseif( $role == 'subscriber' || $role ='administrator' ) {
                                                    $args_order_history = array (
                                                        'post_type'      => 'orders',
                                                        'post_status'    => 'publish',
                                                        'posts_per_page' => -1,
                                                        'orderby'        => 'date',
                                                        'meta_query'     => array (
                                                            'relation' => 'AND',
                                                            array (
                                                                'key'     => 'user_id',
                                                                'value'   => $userID,
                                                                'compare' => '=',
                                                            ),
                                                        ),
                                                    );
                                                } else {
                                                    echo'<h1>Hello,</h1>';
                                                }
                                            }
                                        }

                                        $allOrders = new WP_Query ( $args_order_history );
                                        if( $allOrders->have_posts () ) {
                                            ?>
                                            <h3>Upcoming Trips</h3>
                                            <?php
                                            while ( $allOrders->have_posts () ) {
                                                $allOrders->the_post ();
                                                $order_id    = get_the_ID ();
                                                $order_title = get_the_title ();

                                                $order_publish_date = get_the_date ( 'd M Y', $order_id );
                                                $today_date         = date ( "Y-m-d" );
                                                $curdate            = strtotime ( $today_date );

                                                $vessel_id   = get_field ( 'vessel_id', $order_id );
                                                $vessel_name = get_the_title ( $vessel_id );

                                                $vessel_feature_img = get_the_post_thumbnail_url ( $vessel_id, 'thumbnail' );

                                                $country_id   = get_field ( 'country_id', $order_id );
                                                $country      = get_term_by ( 'id', $country_id, 'country' );
                                                $country_name = $country->name;

                                                $itinery_id   = get_field ( 'destination_id', $order_id );
                                                $itinery_name = get_the_title ( $itinery_id );

                                                $trip_date_id   = get_field ( 'trip_date_id', $order_id );
                                                $trip_date_name = get_the_title ( $trip_date_id );

                                                $dive_start_date     = get_field ( 'dive_start_date', $trip_date_id );
                                                $dive_start_date1    = str_replace ( '/', '-', $dive_start_date );
                                                $dive_start_date_new = date ( 'Y-m-d', strtotime ( $dive_start_date1 ) );

                                                $dive_start_date_strtotime = strtotime ( $dive_start_date_new );

                                                $final_start_date_final = date ( "j M Y", strtotime ( $dive_start_date_new ) );

                                                $dive_end_date        = get_field ( 'dive_end_date', $trip_date_id );
                                                $dive_end_date1       = str_replace ( '/', '-', $dive_end_date );
                                                $dive_end_date_new    = date ( 'Y-m-d', strtotime ( $dive_end_date1 ) );
                                                $final_end_date_final = date ( "j M Y", strtotime ( $dive_end_date_new ) );

                                                $startdate_exp1     = new DateTime ( $dive_start_date_new );
                                                $week_of_start_date = $startdate_exp1->format ( "W" );

                                                $total_person        = get_field ( 'total_person', $order_id );
                                                $payble_amount       = get_field ( 'payble_amount', $order_id );
                                                $payble_amount_final = number_format ( $payble_amount, 0, '.', ',' );

                                                $order_status = get_field ( 'order_status', $order_id );

                                                $i          = 0;
                                                $order_data = [];

                                                $cabin_list = get_post_meta ( $order_id, "cabin_list", true );
                                                for ( $cab = 0; $cab < $cabin_list; $cab ++ ) {
                                                    $cabinID        = get_post_meta ( $order_id, "cabin_list_" . $cab . "_cabinID", true );
                                                    $cabin_types    = get_post_meta ( $order_id, "cabin_list_" . $cab . "_cabin_types", true );
                                                    $selected_seats = get_post_meta ( $order_id, "cabin_list_" . $cab . "_selected_seats", true );
                                                    $person_details = get_post_meta ( $order_id, "cabin_list_" . $cab . "_person_details", true );

                                                    $order_data[ $cab ][ "cabin_title" ]    = get_the_title ( $cabinID );
                                                    $order_data[ $cab ][ "cabin_type" ]     = $cabin_types;
                                                    $order_data[ $cab ][ 'selected_seat' ]  = $selected_seats;
                                                    $order_data[ $cab ][ 'person_details' ] = $person_details;
                                                    //print_r($order_data[$i]['cabin_data'][$cab]['person_details']); die();
                                                    $new_persons                            = [];
                                                    foreach ( $order_data[ $cab ][ 'person_details' ] as $key => $person_val ) {
                                                        //echo $person_val. '<br>';

                                                        $pax_list = get_post_meta ( $order_id, "pax_data", true );
                                                        for ( $paxd = 0; $paxd < $pax_list; $paxd ++ ) {

                                                            $person_gender       = get_post_meta ( $order_id, "pax_data_" . $paxd . "_selected_gender", true );
                                                            $person_name         = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_name", true );
                                                            $person_email        = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_email", true );
                                                            $person_phone_number = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_phone_number", true );
                                                            $person_age          = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_age", true );
                                                            $person_number       = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_person_details", true );
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

                                                    $order_data[ $cab ][ 'persons' ] = $new_persons;
                                                }
                                                $i ++;
                                                /* echo '<pre>';
                                                  print_r($order_data); */

                                                // courses_data order wise
                                                $course_list = get_post_meta ( $order_id, "courses_data", true );
                                                for ( $cour = 0; $cour < $course_list; $cour ++ ) {
                                                    $courses_id                                       = get_post_meta ( $order_id, "courses_data_" . $cour . "_courses_id", true );
                                                    $order_data[ $cour ][ "order_id" ]                = $order_id;
                                                    $order_data[ $cour ][ "course_id" ]               = $courses_id;
                                                    $order_data[ $cour ][ "course_title" ]            = get_the_title ( $courses_id );
                                                    $order_data[ $cour ][ "excerpt_course_overview" ] = get_field ( 'course_overview', $courses_id );
                                                }
                                                // courses_data order wise
                                                // rental_equipment order wise
                                                $ren_equip_list = get_post_meta ( $order_id, "rental_equipment_data", true );
                                                for ( $equip = 0; $equip < $ren_equip_list; $equip ++ ) {
                                                    $equip_id                                         = get_post_meta ( $order_id, "rental_equipment_data_" . $equip . "_rental__equipment_id", true );
                                                    $order_data[ $equip ][ "order_id" ]               = $order_id;
                                                    $order_data[ $equip ][ "rental_equipment_id" ]    = $equip_id;
                                                    $order_data[ $equip ][ "rental_equipment_title" ] = get_the_title ( $equip_id );
                                                    $order_data[ $equip ][ "rental_equipment_image" ] = get_the_post_thumbnail_url ( $equip_id, 'small-size' );
                                                    $order_data[ $equip ][ "rental_equipment_price" ] = get_field ( 'rental_equipment_price', $equip_id );
                                                }
                                                // rental_equipment order wise

                                                if( $curdate < $dive_start_date_strtotime ) {
                                                    ?>
                                                    <div class="trip_card">
                                                        <ul class="trip_ul">
                                                            <li class="trip_li">
                                                                <!-- <img src="images/avatar2.png" class="" alt="User Image" width="50px"> -->
                                                                <img src="<?php echo $vessel_feature_img; ?>" class="User Image" width="100px">
                                                            </li>
                                                            <li class="trip_li">
                                                                <p>Vessel</p>
                                                                <ul>
                                                                    <li><?php echo $vessel_name; ?> </li>
                                                                </ul>
                                                            </li>
                                                            <li class="trip_li">
                                                                <p>Trip</p>
                                                                <ul>
                                                                    <li><?php echo $country_name ?></li>
                                                                    <li><?php echo $itinery_name; ?></li>
                                                                    <li><?php echo $final_start_date_final . ' - ' . $final_end_date_final; ?><br/>(<?php echo $week_of_start_date . ' weeks'; ?>)</li>
                                                                </ul>
                                                            </li>
                                                            <li class="trip_li">
                                                                <p>Cabin</p>
                                                                <ul>
                                                                    <?php
                                                                    foreach ( $order_data as $key => $orderdata ) {
                                                                        if( $orderdata[ 'cabin_type' ] == 'solo' ) {
                                                                            $cabin_type_is = 'Solo Spot';
                                                                        } else {
                                                                            $cabin_type_is = '2 Pax Cabin';
                                                                        }
                                                                        $persons_data = $orderdata[ 'persons' ];

                                                                        echo '<li>' . $cabin_type_is . '</li>';
                                                                        if( $persons_data ) {
                                                                            echo '<li>';
                                                                            foreach ( $persons_data as $personsdata ) {
                                                                                echo '<span class="custom-comma">' . $personsdata[ 'selected_gender' ] . '</span>';
                                                                            }
                                                                            echo '</li>';
                                                                        }
                                                                        echo '<li class="custom-cabin-title">' . $cabin_title . '</li>';
                                                                    }

                                                                    $pax_list = get_post_meta ( $order_id, "pax_data", true );
                                                                    for ( $paxd = 0; $paxd < $pax_list; $paxd ++ ) {
                                                                        $cabin_id    = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_cabin_id", true );
                                                                        $cabin_title = get_the_title ( $cabin_id );
                                                                        echo '<li class="custom-cabin-title">' . $cabin_title . '</li>';
                                                                    }
                                                                    ?>
                                                                </ul>
                                                            </li>
                                                            <li class="trip_li">
                                                                <p>Total Cost</p>
                                                                <ul>
                                                                    <li>
                                                                        <h5>$<?php echo $payble_amount_final; ?></h5>
                                                              <!-- <p>($S 600 x 2)</p> -->
                                                                        <p>Total Persons: <?php echo $total_person; ?></p>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        </ul>

                                                        <a class="ordermodal" data-toggle="modal" data-target="#OrderModal-<?php echo $order_id; ?>" >Trip Details<img src="<?php echo FL_CHILD_THEME_URL . '/dashboard-assets/images/caret_right.svg'; ?>"></a>

                                                        <!-- Modal -->
                                                        <div id="OrderModal-<?php echo $order_id; ?>" class="main_order_detail_modal modal fade" role="dialog" dataid="OrderModal-<?php echo $order_id; ?>">

                                                            <div class="modal-dialog modal-lg">
                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Trip Details</h4>
                                                                        <button type="button" class="close" data-dismiss="modal"><a class="md-close"><i class="fas fa-times"></i></a></button> 
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p><strong>Order id: </strong><?php echo $order_title; ?> </p>
                                                                        <hr>
                                                                        <p><strong>Vessel Name: </strong><?php echo $vessel_name; ?> </p>
                                                                        <p>
                                                                            <?php
                                                                            if( ! empty ( $country_name ) ) {
                                                                                echo '<strong>Country: </strong>' . $country_name . '<br>';
                                                                                ?>
                                                                            <?php } ?>    
                                                                            <?php echo '<strong>Destination Place: </strong>' . $itinery_name; ?><br>
                                                                            <?php echo '<strong>Trip Date: </strong>' . $final_start_date_final . ' - ' . $final_end_date_final; ?> (<?php echo $week_of_start_date . ' weeks'; ?>)<br>
                                                                        </p>

                                                                        <p><strong>Total Persons:</strong> <?php echo $total_person; ?> </p>

                                                                        <p style="margin-bottom: 0;"><strong>Person Details: </strong>
                                                                        <hr style="margin-top:0px;">
                                                                        <?php
                                                                        /* $paxDataDocs = get_post_meta($order_id,"pax_data_documents",true);
                                                                          for ($paxpd=0; $paxpd < $paxDataDocs; $paxpd++) {
                                                                          echo "AAA_".$get_person_passport_document = get_post_meta($order_id,"pax_data_documents_".$paxpd."_pddoc_user_passport_document",true);
                                                                          } */

                                                                        $pax_list = get_post_meta ( $order_id, "pax_data", true );
                                                                        $j        = 1;
                                                                        for ( $paxd = 0; $paxd < $pax_list; $paxd ++ ) {

                                                                            $cabin_id = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_cabin_id", true );
                                                                            if( $cabin_id ) {
                                                                                $cabin_title = get_the_title ( $cabin_id );
                                                                            }

                                                                            $person_name         = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_name", true );
                                                                            $person_gender       = get_post_meta ( $order_id, "pax_data_" . $paxd . "_selected_gender", true );
                                                                            $person_email        = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_email", true );
                                                                            $person_phone_number = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_phone_number", true );
                                                                            $person_course_id    = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_course_id", true );
                                                                            if( $person_course_id ) {
                                                                                $person_course_title = get_the_title ( $person_course_id );
                                                                            }


                                                                            $person_passport_document = get_post_meta ( $order_id, "pax_data_documents_" . $paxd . "_pddoc_user_passport_document", true );
                                                                            $passport_document_url    = wp_get_attachment_url ( $person_passport_document, $user_passport_document, true );

                                                                            $person_immigration_document      = get_post_meta ( $order_id, "pax_data_documents_" . $paxd . "_pddoc_user_immigration_document", true );
                                                                            $immigration_document_url         = wp_get_attachment_url ( $person_immigration_document, $user_immigration_document, true );
                                                                            $person_immigration_document_desc = get_post_meta ( $order_id, "pax_data_documents_" . $paxd . "_pddoc_user_immigration_document_description", true );

                                                                            $person_visa_document      = get_post_meta ( $order_id, "pax_data_documents_" . $paxd . "_pddoc_user_visa_document", true );
                                                                            $visa_document_url         = wp_get_attachment_url ( $person_visa_document, $user_visa_document, true );
                                                                            $person_visa_document_desc = get_post_meta ( $order_id, "pax_data_documents_" . $paxd . "_pddoc_user_visa_document_description", true );

                                                                            $pax_equip_list           = get_post_meta ( $order_id, "rental_equipment_id", true );
                                                                            $person_equip_title_array = [];
                                                                            for ( $paxequip = 0; $paxequip < $pax_list; $paxequip ++ ) {
                                                                                $equip_id = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_rental_equipment_data_" . $paxequip . '_rental_equipment_id', true );
                                                                                if( ! empty ( $equip_id ) ) {
                                                                                    $person_equip_title = get_the_title ( $equip_id );
                                                                                    array_push ( $person_equip_title_array, $person_equip_title );
                                                                                }
                                                                            }
                                                                            $paxmedicallist         = get_post_meta ( $order_id, "rental_equipment_id", true );
                                                                            $personmedicalurl_array = [];
                                                                            for ( $paxmedical = 0; $paxmedical < $pax_list; $paxmedical ++ ) {
                                                                                $medical_id = get_post_meta ( $order_id, "pax_data_documents_" . $paxd . "_pddoc_user_medical_clearance_documents_" . $paxmedical . '_medicalclearance_doc', true );
                                                                                if( ! empty ( $medical_id ) ) {
                                                                                    $person_medical_url = wp_get_attachment_url ( $medical_id );
                                                                                    array_push ( $personmedicalurl_array, $person_medical_url );
                                                                                }
                                                                            }

                                                                            //lability_release_document_url

                                                                            $lability_release_document     = get_post_meta ( $order_id, "pax_lability_release_data_" . $paxd . "_plrddoc_user_lability_release_document", true );
                                                                            $lability_release_document_url = wp_get_attachment_url ( $lability_release_document, $user_lability_release_document, true );

                                                                            // medical statement get all dat in form
                                                                            $person_ms_document     = get_post_meta ( $order_id, "pax_medical_statement_data_" . $paxd . "_pmsddoc_user_ms_document", true );
                                                                            $person_ms_document_url = wp_get_attachment_url ( $person_ms_document, $user_medical_statement_document, true );

                                                                            // medical statement reply document get all data in form
                                                                            $person_ms_reply_document     = get_post_meta ( $order_id, "pax_medical_statement_data_" . $paxd . "_pmsddoc_user_ms_document_reply", true );
                                                                            $person_ms_reply_document_url = wp_get_attachment_url ( $person_ms_reply_document, $user_ms_replay_document, true );

                                                                            $pmsddoc_user_ms_ari_symptoms = get_post_meta ( $order_id, "pax_medical_statement_data_" . $paxd . "_pmsddoc_user_ms_replay", true );
                                                                            $pmsddoc_user_ms_replay       = get_post_meta ( $order_id, "pax_medical_statement_data_" . $paxd . "_pmsddoc_user_ms_ari_symptoms", true );

                                                                            $pmsddoc_user_ms_returned_from_overseas = get_post_meta ( $order_id, "pax_medical_statement_data_" . $paxd . "_pmsddoc_user_ms_returned_from_overseas", true );
                                                                            $pmsddoc_user_ms_contact_covid_19       = get_post_meta ( $order_id, "pax_medical_statement_data_" . $paxd . "_pmsddoc_user_ms_contact_covid_19", true );
                                                                            $pmsddoc_user_ms_take_vacin             = get_post_meta ( $order_id, "pax_medical_statement_data_" . $paxd . "_pmsddoc_user_ms_take_vacin", true );
                                                                            $pmsddoc_user_ms_given_vacin_name       = get_post_meta ( $order_id, "pax_medical_statement_data_" . $paxd . "_pmsddoc_user_ms_given_vacin_name", true );
                                                                            ?>
                                                                            <div class="single_cabin_detail">
                                                                                <span class="single_person_wise_title">
                                                                                    Person <?php echo $j; ?>
                                                                                </span>                          
                                                                            </div>
                                                                            <div class="single_person_wise_wrapper">
                                                                                <?php
                                                                                if( ! empty ( $person_name ) ) {
                                                                                    echo '<strong>Full Name: </strong>' . $person_name . '<br>';
                                                                                }
                                                                                if( ! empty ( $person_email ) ) {
                                                                                    echo '<strong>Email: </strong>' . $person_email . '<br>';
                                                                                }
                                                                                if( ! empty ( $person_phone_number ) ) {
                                                                                    echo '<strong>Phone number: </strong>' . $person_phone_number . '<br>';
                                                                                }
                                                                                if( ! empty ( $person_gender ) ) {
                                                                                    echo '<strong>Gender: </strong>' . $person_gender . '<br>';
                                                                                }
                                                                                if( ! empty ( $cabin_title ) ) {
                                                                                    echo '<strong>Cabin Name: </strong>' . $cabin_title . '<br>';
                                                                                }
                                                                                if( ! empty ( $person_course_title ) ) {
                                                                                    echo '<strong>Course: </strong>' . $person_course_title . '<br>';
                                                                                }
                                                                                if( ! empty ( $all_equipments ) ) {
                                                                                    echo '<strong>Equipment : </strong>' . $all_equipments . '<br>';
                                                                                }

                                                                                if( ! empty ( $passport_document_url ) ) {
                                                                                    $pdf_icon = get_theme_file_uri () . '/images/pdf_file_icon.png';

                                                                                    echo '<strong>Passport Document : </strong><a href="' . $passport_document_url . '" target="_blank",class="pass_doc_pdf" style="    float: none;"><img src="' . $pdf_icon . '" width="100px" style="  width: 40px !important;" /></a><br>';
                                                                                }

                                                                                if( ! empty ( $immigration_document_url ) ) {
                                                                                    $pdf_icon = get_theme_file_uri () . '/images/pdf_file_icon.png';

                                                                                    echo '<strong>Immigration Document : </strong><a href="' . $immigration_document_url . '" target="_blank",class="pass_doc_pdf" style="    float: none;"><img src="' . $pdf_icon . '" width="100px" style="  width: 40px !important;" /></a><br>';
                                                                                }
                                                                                if( ! empty ( $person_immigration_document_desc ) ) {
                                                                                    echo '<strong>Immigration Document Description : </strong>' . $person_immigration_document_desc . '<br>';
                                                                                }

                                                                                if( ! empty ( $visa_document_url ) ) {
                                                                                    $pdf_icon = get_theme_file_uri () . '/images/pdf_file_icon.png';

                                                                                    echo '<strong>Visa Document : </strong><a href="' . $visa_document_url . '" target="_blank",class="pass_doc_pdf" style="    float: none;"><img src="' . $pdf_icon . '" width="100px" style="  width: 40px !important;" /></a><br>';
                                                                                }
                                                                                if( ! empty ( $person_visa_document_desc ) ) {
                                                                                    echo '<strong>Visa Document Description : </strong>' . $person_visa_document_desc . '<br>';
                                                                                }

                                                                                if( ! empty ( $personmedicalurl_array ) ) {
                                                                                    $pdf_icon = get_theme_file_uri () . '/images/pdf_file_icon.png';
                                                                                    echo '<strong>Medical Clearance Documents : </strong>';
                                                                                    foreach ( $personmedicalurl_array as $medi_key => $medi_value ) {
                                                                                        echo '<a class="medical_file_number_' . $medi_key . '" href="' . $medi_value . '" target="_blank",class="pass_doc_pdf" style="    float: none;"><img src="' . $pdf_icon . '" width="100px" style="  width: 40px !important;" /></a>';
                                                                                    }
                                                                                }
                                                                                ?>

                                                                                <a class="personordermodal" data-toggle="modal" data-id="PersonOrderModal-<?php echo $j . '-' . $order_id; ?>" data-target="#PersonOrderModal-<?php echo $j . '-' . $order_id; ?>" >edit<img src="<?php echo FL_CHILD_THEME_URL . '/dashboard-assets/images/caret_right.svg'; ?>"></a>

                                                                                <!-- Start Person Order Modal -->
                                                                                <?php //$j.''.$order_id;   ?>

                                                                                <div id="PersonOrderModal-<?php echo $j . '-' . $order_id; ?>" class="person_order_detail_modal modal fade" role="dialog">

                                                                                    <div class="modal-dialog modal-lg">
                                                                                        <!-- Modal content-->
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h4 class="modal-title">Person Document Upload Details</h4>

                                                                                                <button type="button" class="close" data-dismiss="modal"><a class="md-close"><i class="fas fa-times"></i></a></button> 

                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <div class="row">
                                                                                                    <div class="col-12">
                                                                                                        <div class="card mt-3 tab-card">
                                                                                                            <div class="card-header tab-card-header">
                                                                                                                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                                                                                                                    <li class="nav-item">
                                                                                                                        <a class="nav-link active" data-toggle="tab" href="#one<?php echo $j . '' . $order_id; ?>" role="tab" aria-selected="true">Common Form</a>
                                                                                                                    </li>
                                                                                                                    <li class="nav-item">
                                                                                                                        <a class="nav-link" data-toggle="tab" href="#two<?php echo $j . '' . $order_id; ?>" role="tab" aria-selected="false">Lability Release</a>
                                                                                                                    </li>
                                                                                                                    <li class="nav-item">
                                                                                                                        <a class="nav-link" data-toggle="tab" href="#three<?php echo $j . '' . $order_id; ?>" role="tab" aria-selected="false">Medical Statement</a>
                                                                                                                    </li>
                                                                                                                    <li class="nav-item">
                                                                                                                        <a class="nav-link" data-toggle="tab" href="#four<?php echo $j . '' . $order_id; ?>" role="tab" aria-selected="false">Person Details </a>
                                                                                                                    </li>
                                                                                                                </ul>
                                                                                                            </div>

                                                                                                            <div class="tab-content">
                                                                                                                <div class="tab-pane fade show active p-3" id="one<?php echo $j . '' . $order_id; ?>" role="tabpanel">
                                                                                                                    <h4 class="card-title w-100 mb-4">Common Form </h4>
                                                                                                                    <form class="persondocumentsform" id="persondocumentsform<?php echo $j . '-' . $order_id; ?>" action="" method="POST" enctype="multipart/form-data">

                                                                                                                        <input type="hidden" id="order_person_number_<?php echo $j; ?>" name="order_person_number" value="<?php echo $j; ?>" >
                                                                                                                        <input type="hidden" id="person_order_id_<?php echo $j; ?>" name="person_order_id" value="<?php echo $order_id; ?>" >

                                                                                                                        <div class="col-md-12 form-group">
                                                                                                                            <label>Passport Document</label><?php
                                                                                if( ! empty ( $passport_document_url ) ) {
                                                                                    $pdf_icon = get_theme_file_uri () . '/images/pdf_file_icon.png';
                                                                                    echo '<a href="' . $passport_document_url . '" target="_blank",class="pass_doc_pdf" style="float: none;"><img src="' . $pdf_icon . '" width="100px" style="  width: 40px !important;" /></a><br>';
                                                                                }
                                                                                ?> 
                                                                                                                            <?php //if(!empty($passport_document_url)){ echo ""; } else { echo "required"; }   ?>
                                                                                                                            <input name="user_passport_document"class="form-control required" type="file" multiple="false"/>

                                                                                                                        </div>
                                                                                                                        <div class="col-sm-12 field_wrap mb-4">
                                                                                                                            <label> Immigration Document</label> <?php
                                                                                                    if( ! empty ( $immigration_document_url ) ) {
                                                                                                        $pdf_icon = get_theme_file_uri () . '/images/pdf_file_icon.png';
                                                                                                        echo '<a href="' . $immigration_document_url . '" target="_blank",class="pass_doc_pdf" style="float: none;"><img src="' . $pdf_icon . '" width="100px" style="  width: 40px !important;" /></a><br>';
                                                                                                    }
                                                                                                                            ?>
                                                                                                                            <?php //if(!empty($immigration_document_url)){ echo ""; } else { echo "required"; }   ?>       
                                                                                                                            <input name="user_immigration_document"
                                                                                                                                   class="form-control required" type="file" multiple="false"/>

                                                                                                                        </div>
                                                                                                                        <div class="col-sm-12 field_wrap mb-4">
                                                                                                                            <label> Immigration Document Description</label>     
                                                                                                                            <input id="immigration_desc" name="user_immigration_desc"class="form-control required immigration_desc" type="textarea" value="<?php
                                                                                                    if( ! empty ( $person_immigration_document_desc ) ) {
                                                                                                        echo substr ( $person_immigration_document_desc, 0, 1 ) . '*******';
                                                                                                    }
                                                                                                                            ?>"/>
                                                                                                                                   <?php if( ! empty ( $person_immigration_document_desc ) ) { ?>
                                                                                                                                <input type="hidden" id="immigration_desc_hidden" name="immigration_desc_hidden" required="" value="<?php echo $person_immigration_document_desc; ?>" class="hiw_input immigration_desc_hidden">
                                                                                                                            <?php } ?>
                                                                                                                        </div>
                                                                                                                        <div class="col-sm-12 field_wrap mb-4 form-group">
                                                                                                                            <label> Visa Document<?php
                                                                                                                            if( ! empty ( $visa_document_url ) ) {
                                                                                                                                $pdf_icon = get_theme_file_uri () . '/images/pdf_file_icon.png';
                                                                                                                                echo '<a href="' . $visa_document_url . '" target="_blank",class="pass_doc_pdf" style="    float: none;"><img src="' . $pdf_icon . '" width="100px" style="  width: 40px !important;" /></a><br>';
                                                                                                                            }
                                                                                                                            ?></label>       
                                                                                                                                <?php //if(!empty($visa_document_url)){ echo ""; } else { echo "required"; }  ?>
                                                                                                                            <input name="user_visa_document" class="form-control required"type="file" multiple="false"/>

                                                                                                                        </div>
                                                                                                                        <div class="col-sm-12 field_wrap mb-4">
                                                                                                                            <label> Visa Document Description</label>      
                                                                                                                            <input id="user_visa_desc" name="user_visa_desc"class="form-control required user_visa_desc" type="textarea" value="<?php
                                                                                                        if( ! empty ( $person_visa_document_desc ) ) {
                                                                                                            echo substr ( $person_visa_document_desc, 0, 1 ) . '*******';
                                                                                                        }
                                                                                                                                ?>"/>
                                                                                                                                   <?php if( ! empty ( $person_visa_document_desc ) ) { ?>
                                                                                                                                <input type="hidden" id="user_visa_desc_hidden" name="user_visa_desc_hidden" required="" value="<?php echo $person_visa_document_desc; ?>" class="hiw_input user_visa_desc_hidden">
                                                                                                                            <?php } ?>
                                                                                                                        </div>

                                                                                                                        <div class="col-md-12 form-group">
                                                                                                                            <label>Medical clearance Documents  <?php
                                                                                                                            if( ! empty ( $personmedicalurl_array ) ) {
                                                                                                                                $pdf_icon = get_theme_file_uri () . '/images/pdf_file_icon.png';
                                                                                                                                foreach ( $personmedicalurl_array as $medi_key => $medi_value ) {
                                                                                                                                    echo '<a class="medical_file_number_' . $medi_key . '" href="' . $medi_value . '" target="_blank",class="pass_doc_pdf" style="    float: none;"><img src="' . $pdf_icon . '" width="100px" style="  width: 40px !important;" /></a>';
                                                                                                                                }
                                                                                                                            }
                                                                                                                            ?></label>
                                                                                                                            <input name="user_medical_clearance_doc[]"class="form-control <?php
                                                                                                        if( ! empty ( $medi_key ) ) {
                                                                                                            echo "";
                                                                                                        } else {
                                                                                                            echo "required";
                                                                                                        }
                                                                                                                            ?>" type="file" multiple="multiple" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, .doc, .docx, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document" />

                                                                                                                        </div>

                                                                                                                        <div> 
                                                                                                                            <input type="hidden" name="total_persons" value="<?php echo $pax_list; ?>">           
                                                                                                                            <input type="submit" name="persondocuploded" id="save_persondoc__btn" value="Save Details" class="save_detail_btn" />
                                                                                                                        </div>
                                                                                                                    </form>                
                                                                                                                </div>
                                                                                                                <div class="tab-pane fade p-3" id="two<?php echo $j . '' . $order_id; ?>" role="tabpanel">
                                                                                                                    <h4 class="card-title w-100 mb-4">General Trip Lability Release </h4>
                                                                                                                    <form class="labilityreleaseform" id="labilityreleaseform" action="" method="POST" enctype="multipart/form-data">

                                                                                                                        <input type="hidden" id="lr_order_person_number_<?php echo $j; ?>" name="lr_order_person_number" value="<?php echo $j; ?>" >
                                                                                                                        <input type="hidden" id="lr_person_order_id_<?php echo $j; ?>" name="lr_person_order_id" value="<?php echo $order_id; ?>" >
                                                                                                                        <label>Person : <?php echo $j; ?></label>

                                                                                                                        <div class="col-md-12 form-group">
                                                                                                                            <label>Passport Document</label><?php
                                                                                                    if( ! empty ( $lability_release_document_url ) ) {
                                                                                                        $pdf_icon = get_theme_file_uri () . '/images/pdf_file_icon.png';
                                                                                                        echo '<a href="' . $lability_release_document_url . '" target="_blank",class="pass_doc_pdf" style="    float: none;"><img src="' . $pdf_icon . '" width="100px" style="  width: 40px !important;" /></a><br>';
                                                                                                    }
                                                                                                                            ?> 
                                                                                                                            <?php //if(!empty($lability_release_document_url)){ echo ""; } else { echo "required"; }   ?>
                                                                                                                            <input name="user_lability_release_document"class="form-control required" type="file" multiple="false"  />

                                                                                                                        </div>
                                                                                                                        <div> 
                                                                                                                            <input type="hidden" name="lr_total_persons" value="<?php echo $pax_list; ?>">           
                                                                                                                            <input type="submit" name="labilityreleasedocuploded" id="save_detail_lability_release_btn"value="Save Details" class="save_detail_btn" />
                                                                                                                        </div>
                                                                                                                    </form>              
                                                                                                                </div>

                                                                                                                <div class="tab-pane fade p-3" id="three<?php echo $j . '' . $order_id; ?>" role="tabpanel">
                                                                                                                    <h4 class="card-title w-100 mb-4">Medical Statement </h4>

                                                                                                                    <form class="medicalstatementuserform" id="medicalstatementform<?php echo $j . '-' . $order_id; ?>" action="" method="POST" enctype="multipart/form-data">

                                                                                                                        <input type="hidden" id="ms_order_person_number_<?php echo $j; ?>" name="ms_order_person_number" value="<?php echo $j; ?>" >
                                                                                                                        <input type="hidden" id="ms_person_order_id_<?php echo $j; ?>" name="ms_person_order_id" value="<?php echo $order_id; ?>" >
                                                                                                                        <label>Person : <?php echo $j; ?></label>

                                                                                                                        <div class="col-md-12 form-group">
                                                                                                                            <label>Medical Statement Document</label> <?php
                                                                                                    if( ! empty ( $person_ms_document_url ) ) {
                                                                                                        $pdf_icon = get_theme_file_uri () . '/images/pdf_file_icon.png';
                                                                                                        echo '<a href="' . $person_ms_document_url . '" target="_blank",class="pass_doc_pdf" style="    float: none;"><img src="' . $pdf_icon . '" width="100px" style="  width: 40px !important;" /></a><br>';
                                                                                                    }
                                                                                                                            ?>    <?php //if(!empty($person_ms_document_url)){ echo ""; } else { echo "required"; }   ?>
                                                                                                                            <input name="user_medical_statement_document" class="form-control" type="file" multiple="false"/>
                                                                                                                            <br>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-12 form-group">
                                                                                                                            <label>If the medical statement has any reply ?</label>

                                                                                                                            <div>  <label class="formmsfont">Yes         
                                                                                                                                    <input type="radio" class="<?php
                                                                                                    if( ! empty ( $pmsddoc_user_ms_replay ) ) {
                                                                                                        echo "";
                                                                                                    } else {
                                                                                                        echo "required";
                                                                                                    }
                                                                                                                            ?>" name="pmsddoc_user_ms_replay" value="Yes" <?php
                                                                                                                                           if( $pmsddoc_user_ms_replay == "Yes" ) {
                                                                                                                                               echo 'checked';
                                                                                                                                           } else {
                                                                                                                                               echo "";
                                                                                                                                           }
                                                                                                                                           ?> /></label>&ensp;
                                                                                                                                <label class="formmsfont">No
                                                                                                                                    <input type="radio" class="<?php
                                                                                                                   if( ! empty ( $pmsddoc_user_ms_replay ) ) {
                                                                                                                       echo "";
                                                                                                                   } else {
                                                                                                                       echo "required";
                                                                                                                   }
                                                                                                                                           ?>" name="pmsddoc_user_ms_replay" value="No" <?php
                                                                                                                                           if( $pmsddoc_user_ms_replay == "No" ) {
                                                                                                                                               echo "checked";
                                                                                                                                           } else {
                                                                                                                                               echo "";
                                                                                                                                           }
                                                                                                                                           ?> /></label>


                                                                                                                            </div>
                                                                                                                            <label id="pmsddoc_user_ms_replay-error" class="error" for="pmsddoc_user_ms_replay"></label>
                                                                                                                        </div>

                                                                                                                        <?php //if($pmsddoc_user_ms_replay == "Yes") {     ?>
                                                                                                                        <div class="col-md-12 form-group ms_replay_document" <?php if( ! empty ( $person_ms_reply_document_url ) ) { ?> style="display: block;" <?php } else { ?> style="display: none;" <?php } ?>>
                                                                                                                            <label>Please download a medical statement template, fill it up by the doctor and then upload into the system. (Please click on Pdf icon then download the medical statement template.)
                                                                                                                                <?php
                                                                                                                                $msreply_document_url = get_theme_file_uri () . '/images/DR-Medical-Progress-Template-10541_PDF.pdf';
                                                                                                                                echo '<a href="' . $msreply_document_url . '" target="_blank",class="pass_doc_pdf" style="    float: none;"><img src="' . $pdf_icon . '" width="100px" style="  width: 40px !important;" /></a><br>';
                                                                                                                                ?>
                                                                                                                            </label>
                                                                                                                            <?php
                                                                                                                            if( ! empty ( $person_ms_reply_document_url ) ) {
                                                                                                                                $pdf_icon = get_theme_file_uri () . '/images/pdf_file_icon.png';
                                                                                                                                echo '<a href="' . $person_ms_reply_document_url . '" target="_blank",class="pass_doc_pdf" style="    float: none;"><img src="' . $pdf_icon . '" width="100px" style="  width: 40px !important;" /></a><br>';
                                                                                                                            }
                                                                                                                            ?>

                                                                                                                            <input name="user_ms_replay_document" class="form-control" type="file" multiple="false"/>
                                                                                                                            <br>
                                                                                                                        </div>   

                                                                                                                        <?php //}    ?>

                                                                                                                        <div class="col-md-12 form-group">
                                                                                                                            <label>Are you currently having any ARI symptoms?</label>  
                                                                                                                            <div>  <label class="formmsfont">Yes           
                                                                                                                                    <input type="radio" class="<?php
                                                                                                                        if( ! empty ( $pmsddoc_user_ms_ari_symptoms ) ) {
                                                                                                                            echo "";
                                                                                                                        } else {
                                                                                                                            echo "required";
                                                                                                                        }
                                                                                                                        ?>" name="pmsddoc_user_ms_ari_symptoms" value="Yes"<?php
                                                                                                                                           if( $pmsddoc_user_ms_ari_symptoms == "Yes" ) {
                                                                                                                                               echo "checked";
                                                                                                                                           } else {
                                                                                                                                               echo "";
                                                                                                                                           }
                                                                                                                                           ?> /></label>&ensp;
                                                                                                                                <label class="formmsfont">No
                                                                                                                                    <input type="radio" class="<?php
                                                                                                                   if( ! empty ( $pmsddoc_user_ms_ari_symptoms ) ) {
                                                                                                                       echo "";
                                                                                                                   } else {
                                                                                                                       echo "required";
                                                                                                                   }
                                                                                                                                           ?>" name="pmsddoc_user_ms_ari_symptoms" value="No"<?php
                                                                                                                                           if( $pmsddoc_user_ms_ari_symptoms == "No" ) {
                                                                                                                                               echo "checked";
                                                                                                                                           } else {
                                                                                                                                               echo "";
                                                                                                                                           }
                                                                                                                                           ?> /></label>
                                                                                                                            </div>
                                                                                                                            <label id="pmsddoc_user_ms_ari_symptoms-error" class="error" for="pmsddoc_user_ms_ari_symptoms"></label>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-12 form-group">
                                                                                                                            <label>Have you travelled to or returned from overseas in the last 14 days?</label> 
                                                                                                                            <div>  <label class="formmsfont">Yes           
                                                                                                                                    <input type="radio" class="<?php
                                                                                                                   if( ! empty ( $pmsddoc_user_ms_returned_from_overseas ) ) {
                                                                                                                       echo "";
                                                                                                                   } else {
                                                                                                                       echo "required";
                                                                                                                   }
                                                                                                                                           ?>" name="pmsddoc_user_ms_returned_from_overseas" value="Yes"<?php
                                                                                                                                           if( $pmsddoc_user_ms_returned_from_overseas == "Yes" ) {
                                                                                                                                               echo "checked";
                                                                                                                                           } else {
                                                                                                                                               echo "";
                                                                                                                                           }
                                                                                                                                           ?> /></label>&ensp;
                                                                                                                                <label class="formmsfont">No
                                                                                                                                    <input type="radio" class="<?php
                                                                                                                   if( ! empty ( $pmsddoc_user_ms_returned_from_overseas ) ) {
                                                                                                                       echo "";
                                                                                                                   } else {
                                                                                                                       echo "required";
                                                                                                                   }
                                                                                                                                           ?>" name="pmsddoc_user_ms_returned_from_overseas" value="No"<?php
                                                                                                                                           if( $pmsddoc_user_ms_returned_from_overseas == "No" ) {
                                                                                                                                               echo "checked";
                                                                                                                                           } else {
                                                                                                                                               echo "";
                                                                                                                                           }
                                                                                                                                           ?>/></label>
                                                                                                                            </div> 
                                                                                                                            <label id="pmsddoc_user_ms_returned_from_overseas-error" class="error" for="pmsddoc_user_ms_returned_from_overseas"></label>

                                                                                                                        </div>
                                                                                                                        <div class="col-md-12 form-group">
                                                                                                                            <label> Have you been in *close contact with a case of COVID-19 infection in the last 14 days?</label>  
                                                                                                                            <div>  <label class="formmsfont">Yes           
                                                                                                                                    <input type="radio" class="<?php
                                                                                                                   if( ! empty ( $pmsddoc_user_ms_contact_covid_19 ) ) {
                                                                                                                       echo "";
                                                                                                                   } else {
                                                                                                                       echo "required";
                                                                                                                   }
                                                                                                                                           ?>" name="pmsddoc_user_ms_contact_covid_19" value="Yes"<?php
                                                                                                                                           if( $pmsddoc_user_ms_contact_covid_19 == "Yes" ) {
                                                                                                                                               echo "checked";
                                                                                                                                           } else {
                                                                                                                                               echo "";
                                                                                                                                           }
                                                                                                                                           ?>/></label>&ensp;
                                                                                                                                <label class="formmsfont">No
                                                                                                                                    <input type="radio" class="<?php
                                                                                                                   if( ! empty ( $pmsddoc_user_ms_contact_covid_19 ) ) {
                                                                                                                       echo "";
                                                                                                                   } else {
                                                                                                                       echo "required";
                                                                                                                   }
                                                                                                                                           ?>" name="pmsddoc_user_ms_contact_covid_19" value="No"<?php
                                                                                                                                           if( $pmsddoc_user_ms_contact_covid_19 == "No" ) {
                                                                                                                                               echo "checked";
                                                                                                                                           } else {
                                                                                                                                               echo "";
                                                                                                                                           }
                                                                                                                                           ?>/></label>
                                                                                                                            </div>
                                                                                                                            <label id="pmsddoc_user_ms_contact_covid_19-error" class="error" for="pmsddoc_user_ms_contact_covid_19"></label>
                                                                                                                        </div>

                                                                                                                        <div class="col-md-12 form-group user_vacineted_month">
                                                                                                                            <label> Have you been vaccinated within the past 12 months?</label>  
                                                                                                                            <div>  <label name="dropwrap">Yes           
                                                                                                                                    <input type="radio" class="<?php
                                                                                                                   if( ! empty ( $pmsddoc_user_ms_take_vacin ) ) {
                                                                                                                       echo "";
                                                                                                                   } else {
                                                                                                                       echo "required";
                                                                                                                   }
                                                                                                                                           ?>" name="pmsddoc_user_ms_take_vacin" value="Yes"<?php
                                                                                                                                           if( $pmsddoc_user_ms_take_vacin == "Yes" ) {
                                                                                                                                               echo "checked";
                                                                                                                                           } else {
                                                                                                                                               echo "";
                                                                                                                                           }
                                                                                                                                           ?>/></label>&ensp;
                                                                                                                                <label name="dropwrapno">No
                                                                                                                                    <input type="radio" class="<?php
                                                                                                                   if( ! empty ( $pmsddoc_user_ms_take_vacin ) ) {
                                                                                                                       echo "";
                                                                                                                   } else {
                                                                                                                       echo "required";
                                                                                                                   }
                                                                                                                                           ?>" name="pmsddoc_user_ms_take_vacin" value="No"<?php
                                                                                                                                           if( $pmsddoc_user_ms_take_vacin == "No" ) {
                                                                                                                                               echo "checked";
                                                                                                                                           } else {
                                                                                                                                               echo "";
                                                                                                                                           }
                                                                                                                                           ?>/></label>
                                                                                                                            </div>
                                                                                                                            <label id="pmsddoc_user_ms_take_vacin-error" class="error" for="pmsddoc_user_ms_take_vacin"></label>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-12 form-group vacineted_user" style="<?php
                                                                                                                   if( $pmsddoc_user_ms_take_vacin == "No" ) {
                                                                                                                       echo 'display:none';
                                                                                                                   }
                                                                                                                                           ?>">
                                                                                                                            <label> What vaccination was given?</label>  
                                                                                                                            <select class="form-control <?php
                                                                                                if( ! empty ( $pmsddoc_user_ms_given_vacin_name ) ) {
                                                                                                    echo "";
                                                                                                } else {
                                                                                                    echo "required";
                                                                                                }
                                                                                                                                           ?>" name="pmsddoc_user_ms_given_vacin_name" selected="<?php echo $pmsddoc_user_ms_given_vacin_name; ?>">
                                                                                                                                <option value="">Select Option</option>
                                                                                                                                <option value="Covishield" selected="selected" <?php echo $pmsddoc_user_ms_given_vacin_name == 'Covishield' ? ' selected="selected"' : ''; ?> >Covishield</option>
                                                                                                                                <option value="Cowaxin" <?php $pmsddoc_user_ms_given_vacin_name == 'Cowaxin' ? ' selected="selected"' : ''; ?> >Cowaxin</option>
                                                                                                                            </select>
                                                                                                                            <label id="pmsddoc_user_ms_given_vacin_name-error" class="error" for="pmsddoc_user_ms_given_vacin_name"></label>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-12">
                                                                                                                            <input type="hidden" name="ms_total_persons" value="<?php echo $pax_list; ?>">           
                                                                                                                            <input type="submit" name="medicalstatementdocuploded" value="Save Details" id="save_medical_state_btn" class="save_detail_btn" />
                                                                                                                        </div>
                                                                                                                    </form>


                                                                                                                </div>
                                                                                                                <div class="tab-pane fade p-3" id="four<?php echo $j . '' . $order_id; ?>" role="tabpanel">
                                                                                                                    <h4 class="card-title w-100 mb-4">Person Details </h4>
                                                                                                                    <form class="persondetailsform" id="persondetailsform<?php echo $j . '-' . $order_id; ?>" action="" method="POST" enctype="multipart/form-data">

                                                                                                                        <input type="hidden" id="pd_order_person_number_<?php echo $j; ?>" name="pd_order_person_number" value="<?php echo $j; ?>" >
                                                                                                                        <input type="hidden" id="pd_person_order_id_<?php echo $j; ?>" name="pd_person_order_id" value="<?php echo $order_id; ?>" >
                                                                                                                        <label>Person : <?php echo $j; ?></label>
                                                                                                                        <div class="col-md-12 form-group">
                                                                                                                            <label>Full Name</label>
                                                                                                                            <?php //if(!empty($lability_release_document_url)){ echo ""; } else { echo "required"; }     ?>
                                                                                                                            <input id="first_name_edit_person_detail" name="first_name_person_detail"class="form-control required fn_person_detail" type="textarea" value="<?php
                                                                                                                            if( ! empty ( $person_name ) ) {
                                                                                                                                echo substr ( $person_name, 0, 1 ) . '*******';
                                                                                                                            }
                                                                                                                            ?>"/> 
                                                                                                                            <input type="hidden" id="first_name_person_hidden" name="first_name_person_hidden" required value="<?php echo $person_name; ?>" class="hiw_input fn_person_detail_hidden" >                                                                                                                                                                                                                                    

                                                                                                                        </div>
                                                                                                                        <div class="col-md-12 form-group">
                                                                                                                            <label>Email</label>

                                                                                                                            <?php //if(!empty($lability_release_document_url)){ echo ""; } else { echo "required"; }   ?>
                                                                                                                            <input id="email_person_detail" name="person_detail_email"class="form-control required" type="textarea" value="<?php
                                                                                                                            if( ! empty ( $person_email ) ) {
                                                                                                                                echo substr ( $person_email, 0, 1 ) . '*******';
                                                                                                                            }
                                                                                                                            ?>"/>
                                                                                                                            <input type="hidden" id="email_person_hidden" name="email_person_hidden" required value="<?php echo $person_email; ?>" class="hiw_input" > 
                                                                                                                        </div>
                                                                                                                        <div class="col-md-12 form-group">
                                                                                                                            <label>Phone number</label>
                                                                                                                            <?php //if(!empty($lability_release_document_url)){ echo ""; } else { echo "required"; }     ?>
                                                                                                                            <input id="ph_person_detail" name="person_detail_ph_number"class="form-control required" type="textarea" value="<?php
                                                                                                                            if( ! empty ( $person_phone_number ) ) {
                                                                                                                                echo substr ( $person_phone_number, 0, 1 ) . '*******';
                                                                                                                            }
                                                                                                                            ?>"/>
                                                                                                                            <input type="hidden" id="ph_person_hidden" name="phone_person_hidden" required value="<?php echo $person_phone_number; ?>" class="hiw_input" >   
                                                                                                                        </div>
                                                                                                                        <div class="col-md-12 form-group">
                                                                                                                            <label>Gender</label>  
                                                                                                                            <div>  <label class="formmsfont">Male           
                                                                                                                                    <input type="radio" class="form-group required" name="persondetailgender" value="male"<?php
                                                                                                           if( $person_gender == "male" ) {
                                                                                                               echo "checked";
                                                                                                           } else {
                                                                                                               echo "";
                                                                                                           }
                                                                                                                            ?> /></label>&ensp;
                                                                                                                                <label class="formmsfont">Female
                                                                                                                                    <input type="radio" class="form-group required" name="persondetailgender" value="female"<?php
                                                                                                            if( $person_gender == "female" ) {
                                                                                                                echo "checked";
                                                                                                            } else {
                                                                                                                echo "";
                                                                                                            }
                                                                                                                            ?> /></label>
                                                                                                                            </div>
                                                                                                                            <label id="pmsddoc_user_ms_ari_symptoms-error" class="error" for="pmsddoc_user_ms_ari_symptoms"></label>
                                                                                                                        </div>
                                                                                                                        <div> 

                                                                                                                            <input type="hidden" name="pd_total_person" value="<?php echo $pax_list; ?>">           
                                                                                                                            <input type="submit" name="persondetailupload" id="person_detail_upload_btn" value="Save Details" class="save_detail_btn" />
                                                                                                                        </div>
                                                                                                                    </form>              
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>                
                                                                                <!-- Start Person Order Modal -->            

                                                                            </div>
                                                                            <?php
                                                                            $j ++;
                                                                        }
                                                                        ?>
                                                                        </p>

                                                                        <div style="margin-bottom: 0;">
                                                                            <strong>Other Details:</strong>
                                                                            <hr style="margin-top:0px;">
                                                                        </div>  

                                                                        <div style="margin-bottom: 0;">
                                                                            <p><strong>Total Amount:</strong> <span class="total_amount"> $ <?php echo $payble_amount_final; ?> </span></p>
                                                                            <p><strong>Order Status:</strong> <span class="order_status"><?php echo $order_status; ?> </span></p> 
                                                                        </div>    

                                                                    </div>                      
                                                                </div> <!-- End Modal content-->
                                                            </div>
                                                        </div>

                                                    </div> <!-- trip_card main div end here -->
                                                    <?php
                                                }
                                                //echo '<br>----------<br><br>';
                                            }
                                        } else {
                                            ?>                                                
                                            <div class="not-found-msg">
                                                <h3>Upcoming Trips Not Found</h3>
                                            </div>
                                        <?php }
                                        ?>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.Left col -->

                    <!-- right col -->
                    <div class="col-xl-0 col-lg-12" style="display:none">
                        <div class="card add_on_cover">
                            <div class="card-header">
                                <h2 class="card-title">
                                    Add-Ons
                                </h2>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="card-slider">
                                        <?php
                                        $args_order_history_course = array (
                                            'post_type'      => 'orders',
                                            'post_status'    => 'publish',
                                            'posts_per_page' => -1,
                                            'orderby'        => 'date',
                                            'meta_query'     => array (
                                                'relation' => 'AND',
                                                array (
                                                    'key'     => 'user_id',
                                                    'value'   => $userID,
                                                    'compare' => '=',
                                                ),
                                            ),
                                        );
                                        $allOrdersCourse           = new WP_Query ( $args_order_history_course );
                                        if( $allOrdersCourse->have_posts () ) {
                                            while ( $allOrdersCourse->have_posts () ) {
                                                $allOrdersCourse->the_post ();
                                                $order_id    = get_the_ID ();
                                                $order_title = get_the_title ();

                                                $order_publish_date = get_the_date ( 'd M Y', $order_id );
                                                $today_date         = date ( "Y-m-d" );
                                                $curdate            = strtotime ( $today_date );

                                                $trip_date_id   = get_field ( 'trip_date_id', $order_id );
                                                $trip_date_name = get_the_title ( $trip_date_id );

                                                $dive_start_date     = get_field ( 'dive_start_date', $trip_date_id );
                                                $dive_start_date1    = str_replace ( '/', '-', $dive_start_date );
                                                $dive_start_date_new = date ( 'Y-m-d', strtotime ( $dive_start_date1 ) );

                                                $dive_start_date_strtotime = strtotime ( $dive_start_date_new );

                                                $i          = 0;
                                                $order_data = [];

                                                $course_list = get_post_meta ( $order_id, "courses_data", true );
                                                for ( $cour = 0; $cour < $course_list; $cour ++ ) {
                                                    $courses_id                                       = get_post_meta ( $order_id, "courses_data_" . $cour . "_courses_id", true );
                                                    $order_data[ $cour ][ "order_id" ]                = $order_id;
                                                    $order_data[ $cour ][ "course_id" ]               = $courses_id;
                                                    $order_data[ $cour ][ "course_title" ]            = get_the_title ( $courses_id );
                                                    $order_data[ $cour ][ "course_image" ]            = get_the_post_thumbnail_url ( $courses_id, 'small-size' );
                                                    $order_data[ $cour ][ "excerpt_course_overview" ] = wp_trim_words ( get_field ( 'course_overview', $courses_id ), 15, '...' );
                                                }
                                                foreach ( $order_data as $key => $orderdata ) {
                                                    if( $curdate < $dive_start_date_strtotime ) {
                                                        ?>
                                                        <div class="card course_order_id<?php echo $orderdata[ 'order_id' ]; ?>">
                                                            <div class="product-link">
                                                                <div class="product_image">
                                                                    <img src="<?php echo $orderdata[ 'course_image' ]; ?>" alt="Small succulent with long, spikey leaves in a mug-like planter.">
                                                                </div>
                                                                <div class="product_detail">
                                                                    <h3 class="course_id_<?php echo $orderdata[ 'course_id' ]; ?>"><?php echo $orderdata[ 'course_title' ]; ?></h3>
                                                                    <p><?php echo $orderdata[ 'excerpt_course_overview' ]; ?></p>
                                                                </div>
                                                                <a href="<?php echo home_url () . '/book-now/#/update_order/' . $orderdata[ 'order_id' ]; ?>" class="add_cart_btn">Add to Cart</a>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                            }
                                        } else {
                                            ?>
                                            <div class="not-found-msg">
                                                <h3>Add-Ons Not Found</h3>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- right col -->
                </div>              
            </div>
        </section>
        <?php
    }
}
?>
<?php get_footer ( 'dashboard' ); ?>


