jQuery(document).ready(function () {

    //Personal Details field
    jQuery(".personordermodal").on('click', function () {
        popupid = (jQuery(this).attr('data-id'));
        console.log(popupid);
        
        //User Name 
        user_name_full_string = jQuery('#' + popupid + ' #first_name_person_hidden').val();
        user_name_sub_string = user_name_full_string.slice(0, 1);
        jQuery('#' + popupid + ' #first_name_edit_person_detail').on('focus', function () {
            jQuery('#' + popupid + ' #first_name_edit_person_detail').val(user_name_full_string);
            user_name_valaa = jQuery('#' + popupid + ' #first_name_person_hidden').val();
            jQuery('#' + popupid + ' #first_name_edit_person_detail').val(user_name_valaa);
        });
        jQuery('#' + popupid + ' #first_name_edit_person_detail').on('change', function () {
            user_name_val = jQuery(this).val();
            jQuery('#' + popupid + ' #first_name_person_hidden').val(user_name_val);
            jQuery('#' + popupid + ' #first_name_edit_person_detail').val(user_name_val);
            user_name_fisrt_char = user_name_val.slice(0, 1);
            jQuery('#' + popupid + ' #first_name_edit_person_detail').val(user_name_fisrt_char + '*******');
        });
        jQuery('#' + popupid + ' #first_name_edit_person_detail').on('focusout', function () {
            user_name_first_char_1 = jQuery('#' + popupid + ' #first_name_person_hidden').val();
            user_name_first_char_2 = user_name_first_char_1.slice(0, 1);
            jQuery('#' + popupid + ' #first_name_edit_person_detail').val(user_name_first_char_2 + '*******');
        });
        //User Email
        user_email_full_string = jQuery('#' + popupid + ' #email_person_hidden').val();
        user_email_sub_string = user_email_full_string.slice(0, 1);
        jQuery('#' + popupid + ' #email_person_detail').on('focus', function () {
            jQuery('#' + popupid + ' #email_person_detail').val(user_email_full_string);
            user_email_valaa = jQuery('#' + popupid + ' #email_person_hidden').val();
            jQuery('#' + popupid + ' #email_person_detail').val(user_email_valaa);
        });
        jQuery('#' + popupid + ' #email_person_detail').on('change', function () {
            user_email_val = jQuery(this).val();
            jQuery('#' + popupid + ' #email_person_hidden').val(user_email_val);
            jQuery('#' + popupid + ' #email_person_detail').val(user_email_val);
            user_email_fisrt_char = user_email_val.slice(0, 1);
            jQuery('#' + popupid + ' #email_person_detail').val(user_email_fisrt_char + '*******');
        });
        jQuery('#' + popupid + ' #email_person_detail').on('focusout', function () {
            user_email_first_char_1 = jQuery('#' + popupid + ' #email_person_hidden').val();
            user_email_first_char_2 = user_email_first_char_1.slice(0, 1);
            jQuery('#' + popupid + ' #email_person_detail').val(user_email_first_char_2 + '*******');
        });

        //User Phone
        user_phone_full_string = jQuery('#' + popupid + ' #ph_person_hidden').val();
        user_phone_sub_string = user_phone_full_string.slice(0, 1);
        jQuery('#' + popupid + ' #ph_person_detail').on('focus', function () {
            jQuery('#' + popupid + ' #ph_person_detail').val(user_phone_full_string);
            user_phone_valaa = jQuery('#' + popupid + ' #ph_person_hidden').val();
            jQuery('#' + popupid + ' #ph_person_detail').val(user_phone_valaa);
        });
        jQuery('#' + popupid + ' #ph_person_detail').on('change', function () {
            user_phone_val = jQuery(this).val();
            jQuery('#' + popupid + ' #ph_person_hidden').val(user_phone_val);
            jQuery('#' + popupid + ' #ph_person_detail').val(user_phone_val);
            user_phone_fisrt_char = user_phone_val.slice(0, 1);
            jQuery('#' + popupid + ' #ph_person_detail').val(user_phone_fisrt_char + '*******');
        });
        jQuery('#' + popupid + ' #ph_person_detail').on('focusout', function () {
            user_phone_first_char_1 = jQuery('#' + popupid + ' #ph_person_hidden').val();
            user_phone_first_char_2 = user_phone_first_char_1.slice(0, 1);
            jQuery('#' + popupid + ' #ph_person_detail').val(user_phone_first_char_2 + '*******');
        });
        //User Immigration Description
        user_immigration_desc_full_string = jQuery('#' + popupid + ' #immigration_desc_hidden').val();
        user_immigration_desc_sub_string = user_immigration_desc_full_string.slice(0, 1);
        jQuery('#' + popupid + ' #immigration_desc').on('focus', function () {
            jQuery('#' + popupid + ' #immigration_desc').val(user_immigration_desc_full_string);
            user_immigration_desc_valaa = jQuery('#' + popupid + ' #immigration_desc_hidden').val();
            jQuery('#' + popupid + ' #immigration_desc').val(user_immigration_desc_valaa);
        });
        jQuery('#' + popupid + ' #immigration_desc').on('change', function () {
            user_immigration_desc_val = jQuery(this).val();
            jQuery('#' + popupid + ' #immigration_desc_hidden').val(user_immigration_desc_val);
            jQuery('#' + popupid + ' #immigration_desc').val(user_immigration_desc_val);
            user_immigration_desc_fisrt_char = user_immigration_desc_val.slice(0, 1);
            jQuery('#' + popupid + ' #immigration_desc').val(user_immigration_desc_fisrt_char + '*******');
        });
        jQuery('#' + popupid + ' #immigration_desc').on('focusout', function () {
            user_immigration_desc_first_char_1 = jQuery('#' + popupid + ' #immigration_desc_hidden').val();
            user_immigration_desc_first_char_2 = user_immigration_desc_first_char_1.slice(0, 1);
            jQuery('#' + popupid + ' #immigration_desc').val(user_immigration_desc_first_char_2 + '*******');
        });

        //User Visa Document Desc
        user_visa_desc_full_string = jQuery('#' + popupid + ' #user_visa_desc_hidden').val();
        user_visa_desc_sub_string = user_visa_desc_full_string.slice(0, 1);
        jQuery('#' + popupid + ' #user_visa_desc').on('focus', function () {
            jQuery('#' + popupid + ' #user_visa_desc').val(user_visa_desc_full_string);
            user_visa_desc_valaa = jQuery('#' + popupid + ' #user_visa_desc_hidden').val();
            jQuery('#' + popupid + ' #user_visa_desc').val(user_visa_desc_valaa);
        });
        jQuery('#' + popupid + ' #user_visa_desc').on('change', function () {
            user_visa_desc_val = jQuery(this).val();
            jQuery('#' + popupid + ' #user_visa_desc_hidden').val(user_visa_desc_val);
            jQuery('#' + popupid + ' #user_visa_desc').val(user_visa_desc_val);
            user_visa_desc_fisrt_char = user_visa_desc_val.slice(0, 1);
            jQuery('#' + popupid + ' #user_visa_desc').val(user_visa_desc_fisrt_char + '*******');
        });
        jQuery('#' + popupid + ' #user_visa_desc').on('focusout', function () {
            user_visa_desc_first_char_1 = jQuery('#' + popupid + ' #user_visa_desc_hidden').val();
            user_visa_desc_first_char_2 = user_visa_desc_first_char_1.slice(0, 1);
            jQuery('#' + popupid + ' #user_visa_desc').val(user_visa_desc_first_char_2 + '*******');
        });
    });

    //form validate of medical statememt//
    jQuery(document).on('click', "#save_medical_state_btn", function () {
        //alert(jQuery(this).closest("form[id]"[0].id));
        var form_id = jQuery(this).closest("form[id]").attr('id');
        if (jQuery('#' + form_id).valid()) {
            jQuery('#' + form_id).submit();
        }

    });

    jQuery(document).on('click', "#save_detail_lability_release_btn", function () {
        //alert(jQuery(this).closest("form[id]"[0].id));
        var formid = jQuery(this).closest("form[id]").attr('id');
        if (jQuery('#' + formid).valid()) {
            jQuery('#' + formid).submit();
        }

    });

    jQuery(document).on('click', "#save_persondoc__btn", function () {
        //alert(jQuery(this).closest("form[id]"[0].id));
        var formcommon = jQuery(this).closest("form[id]").attr('id');
        if (jQuery('#' + formcommon).valid()) {
            jQuery('#' + formcommon).submit();
        }

    });


    // ---------- vacion radion button based show hide next div ----------  
    jQuery('input[name=pmsddoc_user_ms_take_vacin').on('change', function () {
        var form_id_vacin = jQuery(this).closest("form[id]").attr('id');
        console.log(form_id_vacin);
        var get_val = $(this).val();
        console.log(get_val);
        if (get_val == 'No') {
            jQuery('#' + form_id_vacin + ' .vacineted_user').hide();
        } else {
            jQuery('#' + form_id_vacin + ' .vacineted_user').show();
        }
    });

    // ms_replay_document
    jQuery('input[name=pmsddoc_user_ms_replay').on('change', function () {
        var form_id_vacin = jQuery(this).closest("form[id]").attr('id');
        console.log(form_id_vacin);
        var get_val = $(this).val();
        console.log(get_val);
        if (get_val == 'No') {
            jQuery('#' + form_id_vacin + ' .ms_replay_document').hide();
        } else {
            jQuery('#' + form_id_vacin + ' .ms_replay_document').show();
        }
    });


    jQuery('.card-slider').slick({
        dots: true,
        arrows: false,
        slidesToShow: 1,
        infinite: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 1
                }
            },
        ]
    });



    jQuery('.sidebar .nav-sidebar li.nav-item a.nav-link').on('click', function () {
        jQuery('.sidebar .nav-sidebar li.nav-item a.nav-link').removeClass('active');
        jQuery(this).addClass('active');
    });

    jQuery("#card_expiry_date, #card_number, #card_cvc").inputmask();

    jQuery('#save_stripcard').on('click', function (evt) {
        event.preventDefault();
    });

    jQuery(".user-profile-form .save_detail_btn").on('click', function () {
        var form = jQuery(".user-profile-form");
        form.validate({
            ignore: [],
            // debug: false,
            rules: {
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
                user_email: {
                    required: true,
                },
                user_phone_number: {
                    required: true,
                },
                user_age: {
                    required: true,
                },
                user_gender: {
                    required: true,
                },
            },
            /*messages : {
             first_name: {
             required: "First name is required.",
             },
             last_name: {
             required: "Last name is required.",
             },
             }*/
        });
    });



    // Start Below code is for edit filed on dashboard-account-settings page (filed******)
    //first_name field
    first_name_full_string = jQuery("#first_name_hidden").val();
    first_name_sub_string = first_name_full_string.slice(0, 1);
    jQuery("#first_name").on('focus', function () {
        jQuery("#first_name").val(first_name_full_string);
        first_name_valaa = jQuery("#first_name_hidden").val();
        jQuery("#first_name").val(first_name_valaa);
    });
    jQuery("#first_name").on('change', function () {
        first_name_val = jQuery(this).val();
        jQuery("#first_name_hidden").val(first_name_val);
        jQuery("#first_name").val(first_name_val);
        first_name_fisrt_char = first_name_val.slice(0, 1);
        jQuery("#first_name").val(first_name_fisrt_char + '*******');
    });
    jQuery("#first_name").on('focusout', function () {
        first_name_fisrt_char_1 = jQuery("#first_name_hidden").val();
        first_name_fisrt_char_2 = first_name_fisrt_char_1.slice(0, 1);
        jQuery("#first_name").val(first_name_fisrt_char_2 + '*******');
    });

    //last_name field
    last_name_full_string = jQuery("#last_name_hidden").val();
    last_name_sub_string = last_name_full_string.slice(0, 1);
    jQuery("#last_name").on('focus', function () {
        jQuery("#last_name").val(last_name_full_string);
        last_name_valaa = jQuery("#last_name_hidden").val();
        jQuery("#last_name").val(last_name_valaa);
    });
    jQuery("#last_name").on('change', function () {
        last_name_val = jQuery(this).val();
        jQuery("#last_name_hidden").val(last_name_val);
        jQuery("#last_name").val(last_name_val);
        last_name_fisrt_char = last_name_val.slice(0, 1);
        jQuery("#last_name").val(last_name_fisrt_char + '*******');
    });
    jQuery("#last_name").on('focusout', function () {
        last_name_fisrt_char_1 = jQuery("#last_name_hidden").val();
        last_name_fisrt_char_2 = last_name_fisrt_char_1.slice(0, 1);
        jQuery("#last_name").val(last_name_fisrt_char_2 + '*******');
    });

    //user_email field
    user_email_full_string = jQuery("#user_email_hidden").val();
    user_email_sub_string = user_email_full_string.slice(0, 1);
    jQuery("#user_email").on('focus', function () {
        jQuery("#user_email").val(user_email_full_string);
        user_email_valaa = jQuery("#user_email_hidden").val();
        jQuery("#user_email").val(user_email_valaa);
    });
    jQuery("#user_email").on('change', function () {
        user_email_val = jQuery(this).val();
        jQuery("#user_email_hidden").val(user_email_val);
        jQuery("#user_email").val(user_email_val);
        user_email_fisrt_char = user_email_val.slice(0, 1);
        jQuery("#user_email").val(user_email_fisrt_char + '*******');
    });
    jQuery("#user_email").on('focusout', function () {
        user_email_fisrt_char_1 = jQuery("#user_email_hidden").val();
        user_email_fisrt_char_2 = user_email_fisrt_char_1.slice(0, 1);
        jQuery("#user_email").val(user_email_fisrt_char_2 + '*******');
    });

    //user_phone_number field
    user_phone_number_full_string = jQuery("#user_phone_number_hidden").val();
    user_phone_number_sub_string = user_phone_number_full_string.slice(0, 2);
    jQuery("#user_phone_number").on('focus', function () {
        jQuery("#user_phone_number").val(user_phone_number_full_string);
        user_phone_number_valaa = jQuery("#user_phone_number_hidden").val();
        jQuery("#user_phone_number").val(user_phone_number_valaa);
    });
    jQuery("#user_phone_number").on('change', function () {
        user_phone_number_val = jQuery(this).val();
        jQuery("#user_phone_number_hidden").val(user_phone_number_val);
        jQuery("#user_phone_number").val(user_phone_number_val);
        user_phone_number_fisrt_char = user_phone_number_val.slice(0, 2);
        jQuery("#user_phone_number").val(user_phone_number_fisrt_char + '*******');
    });
    jQuery("#user_phone_number").on('focusout', function () {
        user_phone_number_fisrt_char_1 = jQuery("#user_phone_number_hidden").val();
        user_phone_number_fisrt_char_2 = user_phone_number_fisrt_char_1.slice(0, 2);
        jQuery("#user_phone_number").val(user_phone_number_fisrt_char_2 + '*******');
    });


    //user_address_line_1 field
    user_address_line_1_full_string = jQuery("#user_address_line_1_hidden").val();
    user_address_line_1_sub_string = user_address_line_1_full_string.slice(0, 1);
    jQuery("#user_address_line_1").on('focus', function () {
        jQuery("#user_address_line_1").val(user_address_line_1_full_string);
        user_address_line_1_valaa = jQuery("#user_address_line_1_hidden").val();
        jQuery("#user_address_line_1").val(user_address_line_1_valaa);
    });
    jQuery("#user_address_line_1").on('change', function () {
        user_address_line_1_val = jQuery(this).val();
        jQuery("#user_address_line_1_hidden").val(user_address_line_1_val);
        jQuery("#user_address_line_1").val(user_address_line_1_val);
        user_address_line_1_fisrt_char = user_address_line_1_val.slice(0, 1);
        jQuery("#user_address_line_1").val(user_address_line_1_fisrt_char + '*******');
    });
    jQuery("#user_address_line_1").on('focusout', function () {
        user_address_line_1_fisrt_char_1 = jQuery("#user_address_line_1_hidden").val();
        user_address_line_1_fisrt_char_2 = user_address_line_1_fisrt_char_1.slice(0, 1);
        jQuery("#user_address_line_1").val(user_address_line_1_fisrt_char_2 + '*******');
    });

    //user_address_line_2 field
    user_address_line_2_full_string = jQuery("#user_address_line_2_hidden").val();
    user_address_line_2_sub_string = user_address_line_2_full_string.slice(0, 1);
    jQuery("#user_address_line_2").on('focus', function () {
        jQuery("#user_address_line_2").val(user_address_line_2_full_string);
        user_address_line_2_valaa = jQuery("#user_address_line_2_hidden").val();
        jQuery("#user_address_line_2").val(user_address_line_2_valaa);
    });
    jQuery("#user_address_line_2").on('change', function () {
        user_address_line_2_val = jQuery(this).val();
        jQuery("#user_address_line_2_hidden").val(user_address_line_2_val);
        jQuery("#user_address_line_2").val(user_address_line_2_val);
        user_address_line_2_fisrt_char = user_address_line_2_val.slice(0, 1);
        jQuery("#user_address_line_2").val(user_address_line_2_fisrt_char + '*******');
    });
    jQuery("#user_address_line_2").on('focusout', function () {
        user_address_line_2_fisrt_char_1 = jQuery("#user_address_line_2_hidden").val();
        user_address_line_2_fisrt_char_2 = user_address_line_2_fisrt_char_1.slice(0, 1);
        jQuery("#user_address_line_2").val(user_address_line_2_fisrt_char_2 + '*******');
    });

    //user_city field
    user_city_full_string = jQuery("#user_city_hidden").val();
    user_city_sub_string = user_city_full_string.slice(0, 1);
    jQuery("#user_city").on('focus', function () {
        jQuery("#user_city").val(user_city_full_string);
        user_city_valaa = jQuery("#user_city_hidden").val();
        jQuery("#user_city").val(user_city_valaa);
    });
    jQuery("#user_city").on('change', function () {
        user_city_val = jQuery(this).val();
        jQuery("#user_city_hidden").val(user_city_val);
        jQuery("#user_city").val(user_city_val);
        user_city_fisrt_char = user_city_val.slice(0, 1);
        jQuery("#user_city").val(user_city_fisrt_char + '*******');
    });
    jQuery("#user_city").on('focusout', function () {
        user_city_fisrt_char_1 = jQuery("#user_city_hidden").val();
        user_city_fisrt_char_2 = user_city_fisrt_char_1.slice(0, 1);
        jQuery("#user_city").val(user_city_fisrt_char_2 + '*******');
    });

    //user_state field
    user_state_full_string = jQuery("#user_state_hidden").val();
    user_state_sub_string = user_state_full_string.slice(0, 1);
    jQuery("#user_state").on('focus', function () {
        jQuery("#user_state").val(user_state_full_string);
        user_state_valaa = jQuery("#user_state_hidden").val();
        jQuery("#user_state").val(user_state_valaa);
    });
    jQuery("#user_state").on('change', function () {
        user_state_val = jQuery(this).val();
        jQuery("#user_state_hidden").val(user_state_val);
        jQuery("#user_state").val(user_state_val);
        user_state_fisrt_char = user_state_val.slice(0, 1);
        jQuery("#user_state").val(user_state_fisrt_char + '*******');
    });
    jQuery("#user_state").on('focusout', function () {
        user_state_fisrt_char_1 = jQuery("#user_state_hidden").val();
        user_state_fisrt_char_2 = user_state_fisrt_char_1.slice(0, 1);
        jQuery("#user_state").val(user_state_fisrt_char_2 + '*******');
    });

    //user_country field
    user_country_full_string = jQuery("#user_country_hidden").val();
    user_country_sub_string = user_country_full_string.slice(0, 1);
    jQuery("#user_country").on('focus', function () {
        jQuery("#user_country").val(user_country_full_string);
        user_country_valaa = jQuery("#user_country_hidden").val();
        jQuery("#user_country").val(user_country_valaa);
    });
    jQuery("#user_country").on('change', function () {
        user_country_val = jQuery(this).val();
        jQuery("#user_country_hidden").val(user_country_val);
        jQuery("#user_country").val(user_country_val);
        user_country_fisrt_char = user_country_val.slice(0, 1);
        jQuery("#user_country").val(user_country_fisrt_char + '*******');
    });
    jQuery("#user_country").on('focusout', function () {
        user_country_fisrt_char_1 = jQuery("#user_country_hidden").val();
        user_country_fisrt_char_2 = user_country_fisrt_char_1.slice(0, 1);
        jQuery("#user_country").val(user_country_fisrt_char_2 + '*******');
    });

    //user_postcode field
    user_postcode_full_string = jQuery("#user_postcode_hidden").val();
    user_postcode_sub_string = user_postcode_full_string.slice(0, 2);
    jQuery("#user_postcode").on('focus', function () {
        jQuery("#user_postcode").val(user_postcode_full_string);
        user_postcode_valaa = jQuery("#user_postcode_hidden").val();
        jQuery("#user_postcode").val(user_postcode_valaa);
    });
    jQuery("#user_postcode").on('change', function () {
        user_postcode_val = jQuery(this).val();
        jQuery("#user_postcode_hidden").val(user_postcode_val);
        jQuery("#user_postcode").val(user_postcode_val);
        user_postcode_fisrt_char = user_postcode_val.slice(0, 2);
        jQuery("#user_postcode").val(user_postcode_fisrt_char + '*******');
    });
    jQuery("#user_postcode").on('focusout', function () {
        user_postcode_fisrt_char_1 = jQuery("#user_postcode_hidden").val();
        user_postcode_fisrt_char_2 = user_postcode_fisrt_char_1.slice(0, 2);
        jQuery("#user_postcode").val(user_postcode_fisrt_char_2 + '*******');
    });


    //Personal Details field
    jQuery(".ordermodal").on('click', function () {
        console.log("user_email_full_string");
    });
    jQuery("#PersonOrderModal-1-1596").on('click', function () {
        console.log("in");
        user_email_full_string = jQuery("#first_name_person_hidden").val();
        console.log(user_email_full_string);
        user_email_sub_string = user_email_full_string.slice(0, 1);
        jQuery("#first_name_edit_person_detail").on('focus', function () {
            alert("in");
            jQuery("#first_name_edit_person_detail").val(user_email_full_string);
            user_email_valaa = jQuery("#first_name_person_hidden").val();
            jQuery("#first_name_edit_person_detail").val(user_email_valaa);
        });
        jQuery("#first_name_edit_person_detail").on('change', function () {
            alert("in1");
            user_email_val = jQuery(this).val();
            jQuery("#first_name_person_hidden").val(user_email_val);
            jQuery("#first_name_edit_person_detail").val(user_email_val);
            user_email_fisrt_char = user_email_val.slice(0, 1);
            jQuery("#first_name_edit_person_detail").val(user_email_fisrt_char + '*******');
        });
        jQuery("#first_name_edit_person_detail").on('focusout', function () {
            alert("in2");
            user_email_fisrt_char_1 = jQuery("#first_name_person_hidden").val();
            user_email_fisrt_char_2 = user_email_fisrt_char_1.slice(0, 1);
            jQuery("#first_name_edit_person_detail").val(user_email_fisrt_char_2 + '*******');
        });
    });

    // =============== Start Passport Details ===============
    //date_of_birth field

    //passport_number field
    passport_number_full_string = jQuery("#passport_number_hidden").val();
    passport_number_sub_string = passport_number_full_string.slice(0, 1);
    jQuery("#passport_number").on('focus', function () {
        jQuery("#passport_number").val(passport_number_full_string);
        passport_number_valaa = jQuery("#passport_number_hidden").val();
        jQuery("#passport_number").val(passport_number_valaa);
    });
    jQuery("#passport_number").on('change', function () {
        passport_number_val = jQuery(this).val();
        jQuery("#passport_number_hidden").val(passport_number_val);
        jQuery("#passport_number").val(passport_number_val);
        passport_number_fisrt_char = passport_number_val.slice(0, 1);
        jQuery("#passport_number").val(passport_number_fisrt_char + '*******');
    });
    jQuery("#passport_number").on('focusout', function () {
        passport_number_fisrt_char_1 = jQuery("#passport_number_hidden").val();
        passport_number_fisrt_char_2 = passport_number_fisrt_char_1.slice(0, 1);
        jQuery("#passport_number").val(passport_number_fisrt_char_2 + '*******');
    });

    //country_passport field
    country_passport_full_string = jQuery("#country_passport_hidden").val();
    country_passport_sub_string = country_passport_full_string.slice(0, 1);
    jQuery("#country_passport").on('focus', function () {
        jQuery("#country_passport").val(country_passport_full_string);
        country_passport_valaa = jQuery("#country_passport_hidden").val();
        jQuery("#country_passport").val(country_passport_valaa);
    });
    jQuery("#country_passport").on('change', function () {
        country_passport_val = jQuery(this).val();
        jQuery("#country_passport_hidden").val(country_passport_val);
        jQuery("#country_passport").val(country_passport_val);
        country_passport_fisrt_char = country_passport_val.slice(0, 1);
        jQuery("#country_passport").val(country_passport_fisrt_char + '*******');
    });
    jQuery("#country_passport").on('focusout', function () {
        country_passport_fisrt_char_1 = jQuery("#country_passport_hidden").val();
        country_passport_fisrt_char_2 = country_passport_fisrt_char_1.slice(0, 1);
        jQuery("#country_passport").val(country_passport_fisrt_char_2 + '*******');
    });

    //nationality_of_passport_holder field
    nationality_of_passport_holder_full_string = jQuery("#nationality_of_passport_holder_hidden").val();
    nationality_of_passport_holder_sub_string = nationality_of_passport_holder_full_string.slice(0, 1);
    jQuery("#nationality_of_passport_holder").on('focus', function () {
        jQuery("#nationality_of_passport_holder").val(nationality_of_passport_holder_full_string);
        nationality_of_passport_holder_valaa = jQuery("#nationality_of_passport_holder_hidden").val();
        jQuery("#nationality_of_passport_holder").val(nationality_of_passport_holder_valaa);
    });
    jQuery("#nationality_of_passport_holder").on('change', function () {
        nationality_of_passport_holder_val = jQuery(this).val();
        jQuery("#nationality_of_passport_holder_hidden").val(nationality_of_passport_holder_val);
        jQuery("#nationality_of_passport_holder").val(nationality_of_passport_holder_val);
        nationality_of_passport_holder_fisrt_char = nationality_of_passport_holder_val.slice(0, 1);
        jQuery("#nationality_of_passport_holder").val(nationality_of_passport_holder_fisrt_char + '*******');
    });
    jQuery("#nationality_of_passport_holder").on('focusout', function () {
        nationality_of_passport_holder_fisrt_char_1 = jQuery("#nationality_of_passport_holder_hidden").val();
        nationality_of_passport_holder_fisrt_char_2 = nationality_of_passport_holder_fisrt_char_1.slice(0, 1);
        jQuery("#nationality_of_passport_holder").val(nationality_of_passport_holder_fisrt_char_2 + '*******');
    });

    //issuing_authority field
    issuing_authority_full_string = jQuery("#issuing_authority_hidden").val();
    issuing_authority_sub_string = issuing_authority_full_string.slice(0, 1);
    jQuery("#issuing_authority").on('focus', function () {
        jQuery("#issuing_authority").val(issuing_authority_full_string);
        issuing_authority_valaa = jQuery("#issuing_authority_hidden").val();
        jQuery("#issuing_authority").val(issuing_authority_valaa);
    });
    jQuery("#issuing_authority").on('change', function () {
        issuing_authority_val = jQuery(this).val();
        jQuery("#issuing_authority_hidden").val(issuing_authority_val);
        jQuery("#issuing_authority").val(issuing_authority_val);
        issuing_authority_fisrt_char = issuing_authority_val.slice(0, 1);
        jQuery("#issuing_authority").val(issuing_authority_fisrt_char + '*******');
    });
    jQuery("#issuing_authority").on('focusout', function () {
        issuing_authority_fisrt_char_1 = jQuery("#issuing_authority_hidden").val();
        issuing_authority_fisrt_char_2 = issuing_authority_fisrt_char_1.slice(0, 1);
        jQuery("#issuing_authority").val(issuing_authority_fisrt_char_2 + '*******');
    });
    // =============== End Passport Details ===============

    // =============== Start NRIC/FIN Details ===============
    //nricfin_number field
    nricfin_number_full_string = jQuery("#nricfin_number_hidden").val();
    nricfin_number_sub_string = nricfin_number_full_string.slice(0, 1);
    jQuery("#nricfin_number").on('focus', function () {
        jQuery("#nricfin_number").val(nricfin_number_full_string);
        nricfin_number_valaa = jQuery("#nricfin_number_hidden").val();
        jQuery("#nricfin_number").val(nricfin_number_valaa);
    });
    jQuery("#nricfin_number").on('change', function () {
        nricfin_number_val = jQuery(this).val();
        jQuery("#nricfin_number_hidden").val(nricfin_number_val);
        jQuery("#nricfin_number").val(nricfin_number_val);
        nricfin_number_fisrt_char = nricfin_number_val.slice(0, 1);
        jQuery("#nricfin_number").val(nricfin_number_fisrt_char + '*******');
    });
    jQuery("#nricfin_number").on('focusout', function () {
        nricfin_number_fisrt_char_1 = jQuery("#nricfin_number_hidden").val();
        nricfin_number_fisrt_char_2 = nricfin_number_fisrt_char_1.slice(0, 1);
        jQuery("#nricfin_number").val(nricfin_number_fisrt_char_2 + '*******');
    });
    // =============== End NRIC/FIN Details Details ===============	

    // =============== Start Document Description Details ===============
    //immigration_document_description field
    immigration_document_description_full_string = jQuery("#immigration_document_description_hidden").val();
    immigration_document_description_sub_string = immigration_document_description_full_string.slice(0, 1);
    jQuery("#immigration_document_description").on('focus', function () {
        jQuery("#immigration_document_description").val(immigration_document_description_full_string);
        immigration_document_description_valaa = jQuery("#immigration_document_description_hidden").val();
        jQuery("#immigration_document_description").val(immigration_document_description_valaa);
    });
    jQuery("#immigration_document_description").on('change', function () {
        immigration_document_description_val = jQuery(this).val();
        jQuery("#immigration_document_description_hidden").val(immigration_document_description_val);
        jQuery("#immigration_document_description").val(immigration_document_description_val);
        immigration_document_description_fisrt_char = immigration_document_description_val.slice(0, 1);
        jQuery("#immigration_document_description").val(immigration_document_description_fisrt_char + '*******');
    });
    jQuery("#immigration_document_description").on('focusout', function () {
        immigration_document_description_fisrt_char_1 = jQuery("#immigration_document_description_hidden").val();
        immigration_document_description_fisrt_char_2 = immigration_document_description_fisrt_char_1.slice(0, 1);
        jQuery("#immigration_document_description").val(immigration_document_description_fisrt_char_2 + '*******');
    });
    //visa_document_description field
    visa_document_description_full_string = jQuery("#visa_document_description_hidden").val();
    visa_document_description_sub_string = visa_document_description_full_string.slice(0, 1);
    jQuery("#visa_document_description").on('focus', function () {
        jQuery("#visa_document_description").val(visa_document_description_full_string);
        visa_document_description_valaa = jQuery("#visa_document_description_hidden").val();
        jQuery("#visa_document_description").val(visa_document_description_valaa);
    });
    jQuery("#visa_document_description").on('change', function () {
        visa_document_description_val = jQuery(this).val();
        jQuery("#visa_document_description_hidden").val(visa_document_description_val);
        jQuery("#visa_document_description").val(visa_document_description_val);
        visa_document_description_fisrt_char = visa_document_description_val.slice(0, 1);
        jQuery("#visa_document_description").val(visa_document_description_fisrt_char + '*******');
    });
    jQuery("#visa_document_description").on('focusout', function () {
        visa_document_description_fisrt_char_1 = jQuery("#visa_document_description_hidden").val();
        visa_document_description_fisrt_char_2 = visa_document_description_fisrt_char_1.slice(0, 1);
        jQuery("#visa_document_description").val(visa_document_description_fisrt_char_2 + '*******');
    });
    // =============== End Document Description Details ===============


    // End below code is for edit filed on dashboard-account-settings page (filed******)

    // Start Datetimepicker JS 
    jQuery("#user_age").datepicker({
        format: "dd/mm/yyyy",
        duration: "fast",
        autoclose: true,
    });
    jQuery("#user_age_hidden").datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        duration: "fast"
    });
    //user_age field datepicker
    user_age_full_string = jQuery("#user_age_hidden").val();
    user_age_sub_string = user_age_full_string.slice(0, 2);
    jQuery("#user_age").on('focus', function () {
        jQuery("#user_age").val(user_age_full_string);
        user_age_valaa = jQuery("#user_age_hidden").val();
        jQuery("#user_age").val(user_age_valaa);
    });
    jQuery("#user_age").on('change', function () {
        user_age_val = jQuery(this).val();
        jQuery("#user_age_hidden").val(user_age_val);
        jQuery("#user_age").val(user_age_val);
        user_age_fisrt_char = user_age_val.slice(0, 2);
        jQuery("#user_age").val(user_age_fisrt_char + '/**/****');
    });
    jQuery("#user_age").on('focusout', function () {
        user_age_fisrt_char_1 = jQuery("#user_age_hidden").val();
        user_age_fisrt_char_2 = user_age_fisrt_char_1.slice(0, 2);
        jQuery("#user_age").val(user_age_fisrt_char_2 + '/**/****');
    });


//    birth_date_full_string = jQuery("#date_of_birth_hidden").val();
//    birth_date_sub_string = birth_date_full_string.slice(0, 2);
//    jQuery("#date_of_birth").on('focus', function () {
//        jQuery("#date_of_birth").val(birth_date_full_string);
//        birth_date_valaa = jQuery("#date_of_birth_hidden").val();
//        jQuery("#date_of_birth").val(birth_date_valaa);
//    });
//    jQuery("#date_of_birth").on('change', function () {
//        birth_date_val = jQuery(this).val();
//        jQuery("#date_of_birth_hidden").val(birth_date_val);
//        jQuery("#date_of_birth").val(birth_date_val);
//        birth_date_fisrt_char = birth_date_val.slice(0, 2);
//        jQuery("#date_of_birth").val(birth_date_fisrt_char + '/**/****');
//    });
//    jQuery("#date_of_birth").on('focusout', function () {
//        birth_date_fisrt_char_1 = jQuery("#date_of_birth_hidden").val();
//        birth_date_fisrt_char_2 = birth_date_fisrt_char_1.slice(0, 2);
//        jQuery("#date_of_birth").val(birth_date_fisrt_char_2 + '/**/****');
//    });

    jQuery("#date_of_birth").datepicker({
        format: 'dd/mm/yyyy',
        duration: "fast",
        autoclose: true,
    });
    jQuery("#date_of_birth_hidden").datepicker({
        format: 'dd/mm/yyyy',
        duration: "fast",
        autoclose: true,
    });

    jQuery("#date_of_expiry").datetimepicker({
        format: 'DD/MM/YYYY'
    });
    // End Datetimepicker JS 


    /*====== ------ PDPA matters for order's members ------ ====== */

//END

});
