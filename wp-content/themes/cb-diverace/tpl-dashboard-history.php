<?php
/*
  Template Name: Dashboard History page
  Template Post Type: post, page
 */

global $post, $wpdb;

$userID = '';
if( is_user_logged_in () ) {
    $current_user = wp_get_current_user ();
    $userID       = $current_user->ID;
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
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">
                                    History
                                </h2>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="history">
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
                                                } elseif( $role == 'subscriber' ) {
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
                                                    
                                                }
                                            }
                                        }


                                        $allOrders = new WP_Query ( $args_order_history );

                                        if( $allOrders->have_posts () ) {
                                            while ( $allOrders->have_posts () ) {
                                                $allOrders->the_post ();
                                                $order_id     = get_the_ID ();
                                                $order_title  = get_the_title ();
                                                $order_status = get_field ( 'order_status', $order_id );

                                                $order_publish_date = get_the_date ( 'd M Y', $order_id );
                                                $today_date         = date ( "Y-m-d" );
                                                $curdate            = strtotime ( $today_date );

                                                $vessel_id   = get_field ( 'vessel_id', $order_id );
                                                $vessel_name = get_the_title ( $vessel_id );

                                                $country_id   = get_field ( 'country_id', $order_id );
                                                $country      = get_term_by ( 'id', $country_id, 'country' );
                                                $country_name = $country->name;

                                                $itinery_id   = get_field ( 'itinery_id', $order_id );
                                                $itinery_name = get_the_title ( $itinery_id );

                                                $trip_date_id   = get_field ( 'trip_date_id', $order_id );
                                                $trip_date_name = get_the_title ( $trip_date_id );

                                                $dive_start_date        = get_field ( 'dive_start_date', $trip_date_id );
                                                $dive_start_date1       = str_replace ( '/', '-', $dive_start_date );
                                                $dive_start_date_new    = date ( 'Y-m-d', strtotime ( $dive_start_date1 ) );
                                                $final_start_date_final = date ( "j M Y", strtotime ( $dive_start_date_new ) );

                                                $dive_end_date                  = get_field ( 'dive_end_date', $trip_date_id );
                                                $dive_end_date1                 = str_replace ( '/', '-', $dive_end_date );
                                                $dive_end_date_new              = date ( 'Y-m-d', strtotime ( $dive_end_date1 ) );
                                                $final_end_date_final           = date ( "j M Y", strtotime ( $dive_end_date_new ) );
                                                $final_end_date_final_strtotime = strtotime ( $final_end_date_final );

                                                $startdate_exp1     = new DateTime ( $dive_start_date_new );
                                                $week_of_start_date = $startdate_exp1->format ( "W" );

                                                $total_person        = get_field ( 'total_person', $order_id );
                                                $payble_amount       = get_field ( 'payble_amount', $order_id );
                                                $payble_amount_final = number_format ( $payble_amount, 0, '.', ',' );

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
                                                ?>
                                                <?php if( $curdate > $final_end_date_final_strtotime ) { ?>
                                                    <div class="history_card">
                                                        <h3><?php echo $order_publish_date; ?></h3>
                                                        <ul class="history_ul">
                                                            <li class="history_li">
                                                                <img src="<?php echo FL_CHILD_THEME_URL . '/dashboard-assets/images/date_time.svg'; ?>" class="User Image" width="50px">
                                                            </li>
                                                            <li class="history_li">
                                                                <p>Vessel</p>
                                                                <ul>
                                                                    <li><?php echo $vessel_name; ?></li>
                                                                </ul>
                                                            </li>
                                                            <li class="history_li">
                                                                <p>history</p>
                                                                <ul>
                                                                    <li><?php echo $country_name ?></li>
                                                                    <li><?php echo $itinery_name; ?></li>
                                                                    <li><?php echo $final_start_date_final . ' - ' . $final_end_date_final; ?><br/>(<?php echo $week_of_start_date . ' weeks'; ?>)</li>
                                                                </ul>
                                                            </li>
                                                            <li class="history_li">
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
                                                                        echo '<li class="custom-cabin-title">' . $orderdata[ 'cabin_title' ] . '</li>';
                                                                    }
                                                                    ?>            
                                                                </ul>
                                                            </li>
                                                            <li class="history_li">
                                                                <p>Total Cost</p>
                                                                <ul>
                                                                    <li>
                                                                        <h5>$S <?php echo $payble_amount_final; ?></h5>
                                                                        <!-- <p>($S 600 x 2)</p> -->
                                                                        <p>Total Persons: <?php echo $total_person; ?></p>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                        <a data-toggle="modal" data-target="#OrderModal-<?php echo $order_id; ?>" >Trip Details<img src="<?php echo FL_CHILD_THEME_URL . '/dashboard-assets/images/caret_right.svg'; ?>"></a>

                                                        <!-- Modal -->
                                                        <div id="OrderModal-<?php echo $order_id; ?>" class="modal fade" role="dialog">
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
                                                                        $pax_list = get_post_meta ( $order_id, "pax_data", true );
                                                                        $j        = 1;
                                                                        for ( $paxd = 0; $paxd < $pax_list; $paxd ++ ) {
                                                                            $cabin_id = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_cabin_id", true );

                                                                            $cabin_title         = get_the_title ( $cabin_id );
                                                                            $person_name         = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_name", true );
                                                                            $person_gender       = get_post_meta ( $order_id, "pax_data_" . $paxd . "_selected_gender", true );
                                                                            $person_email        = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_email", true );
                                                                            $person_phone_number = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_phone_number", true );
                                                                            $person_course_id    = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_course_id", true );
                                                                            $person_course_title = get_the_title ( $person_course_id );

                                                                            $pax_equip_list           = get_post_meta ( $order_id, "rental_equipment_id", true );
                                                                            $person_equip_title_array = [];
                                                                            for ( $paxequip = 0; $paxequip < $pax_list; $paxequip ++ ) {
                                                                                $equip_id = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_rental_equipment_data_" . $paxequip . '_rental_equipment_id', true );
                                                                                if( ! empty ( $equip_id ) ) {
                                                                                    $person_equip_title = get_the_title ( $equip_id );
                                                                                    array_push ( $person_equip_title_array, $person_equip_title );
                                                                                }
                                                                            }
                                                                            $all_equipments = implode ( ',', $person_equip_title_array );
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
                                                                                ?>
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
                                                                            <p><strong>Total Amount:</strong> <span class="total_amount"> $S <?php echo $payble_amount_final; ?> </span></p>
                                                                            <p><strong>Order Status:</strong> <span class="order_status"><?php echo $order_status; ?> </span></p> 
                                                                        </div>    

                                                                    </div>			            
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>



                                                    <?php
                                                } else {
                                                    ?>
                                                    <!-- <div class="not-found-msg">
                                                        <h3>Record Not Found</h3>
                                                    </div> -->
                                                    <?php
                                                }
                                                //echo '<br>----------<br><br>';
                                            }
                                        } else {
                                            ?>                                                
                                            <div class="not-found-msg">
                                                <h3>Record Not Found</h3>
                                            </div>
                                            <?php
                                        }
                                        ?>    
                                    </div>    
                                </div>    
                            </div>    
                        </div>
                    </div>          
                </div>
            </div>
        </section>
        <?php
    }
}
?>

<?php get_footer ( 'dashboard' ); ?>
