<?php
/*
  Template Name: Dashboard Add-Ons page
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
                                    Add-Ons
                                </h2>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="adons_cover">
                                        <div class="adons_card">
                                            <div>
                                                <h3>Course</h3>
                                                <div class="row">
                                                    <?php
                                                    $user = new WP_User ( $userID );
                                                    if( ! empty ( $user->roles ) && is_array ( $user->roles ) ) {
                                                        foreach ( $user->roles as $role ) {
                                                            if( $role == 'agent' ) {
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
                                                            } elseif( $role == 'subscriber' || $role ='administrator') {
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
                                                            } else {
                                                                echo'<h1>Hello,</h1>';
                                                            }
                                                        }
                                                    }

                                                    $allOrdersCourse = new WP_Query ( $args_order_history_course );
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

                                                            $itinery_id   = get_field ( 'itinery_id', $order_id );
                                                            $itinery_name = get_the_title ( $itinery_id );

                                                            //Start New code here for all course of current upcoming trips. (trip date is in feature)
                                                            $pax_list = get_post_meta ( $order_id, "pax_data", true );
                                                            for ( $paxd = 0; $paxd < $pax_list; $paxd ++ ) {
                                                                $course_id    = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_course_id", true );
                                                                $course_title = get_the_title ( $course_id );
                                                                $course_slug  = get_post_field ( 'post_name', $course_id );
                                                                $course_url   = home_url () . '/course/' . $course_slug;

                                                                $course_price = get_field ( 'course_price', $course_id );
                                                                if( $curdate < $dive_start_date_strtotime ) {
                                                                    ?>
                                                                    <div class="col-xl-4 col-lg-6 col-sm-6 course_order_id<?php echo $orderdata[ 'order_id' ]; ?>">
                                                                        <p><strong>Trip Order ID: #<?php echo '</strong> - ' . $itinery_name; ?></p>
                                                                        <img src="<?php echo FL_CHILD_THEME_URL . '/dashboard-assets/images/cart.svg'; ?>" class="cart_img">
                                                                        <div class="course_detail">
                                                                            <p class="custom_course_title"><?php echo $course_title; ?></p>
                                                                            <span><?php echo $course_price; ?>SGD per pax</span>
                                                                            <a href="<?php echo home_url () . '/book-now/#/update_order/' . $order_id; ?>" class="add_cart_btn mr-3">Add to Cart</a>
                                                                            <a class="course_link add_cart_btn"target="_blank" href="<?php echo $course_url; ?>">See More</a>

                                                                        </div>

                                                                    </div>
                                                                    <?
                                                                }
                                                            }
                                                            //End New code here for all course
                                                        }
                                                    } else {
                                                        ?>
                                                        <div class="not-found-msg pl-3">
                                                            <h6>Course Not Found</h6>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>					         
                                                </div>
                                            </div>

                                            <hr/>

                                            <div class="mt-4">
                                                <h3>Rental Equipments</h3>
                                                <div class="row">
                                                    <?php
                                                    $args_order_history_rental_equipment = array (
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
                                                    $allOrdersEquipment                  = new WP_Query ( $args_order_history_rental_equipment );
                                                    if( $allOrdersEquipment->have_posts () ) {
                                                        while ( $allOrdersEquipment->have_posts () ) {
                                                            $allOrdersEquipment->the_post ();
                                                            $order_id = get_the_ID ();

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

                                                            $itinery_id   = get_field ( 'itinery_id', $order_id );
                                                            $itinery_name = get_the_title ( $itinery_id );

                                                            //Start New code here for all equipments of current upcoming trips. (trip date is in feature)
                                                            $pax_list = get_post_meta ( $order_id, "pax_data", true );
                                                            for ( $paxd = 0; $paxd < $pax_list; $paxd ++ ) {
                                                                $pax_equip_list = get_post_meta ( $order_id, "rental_equipment_id", true );

                                                                for ( $paxequip = 0; $paxequip < $pax_list; $paxequip ++ ) {
                                                                    $equip_id               = get_post_meta ( $order_id, "pax_data_" . $paxd . "_pax_rental_equipment_data_" . $paxequip . '_rental_equipment_id', true );
                                                                    $rental_equipment_title = get_the_title ( $equip_id );
                                                                    $rental_equipment_price = get_field ( 'rental_equipment_price', $equip_id );
                                                                    $rental_equipment_slug  = get_post_field ( 'post_name', $equip_id );
                                                                    $rental_equipment_url   = home_url () . '/Rental Equipment/' . $rental_equipment_slug;

                                                                    if( $curdate < $dive_start_date_strtotime ) {
                                                                        if( ! empty ( $equip_id ) ) {
                                                                            ?>
                                                                            <div class="col-xl-4 col-lg-6 col-sm-6 course_order_id<?php echo $orderdata[ 'order_id' ]; ?>">
                                                                                <p><strong>Trip Order ID: #<?php echo '</strong> - ' . $itinery_name; ?></p>
                                                                                <img src="<?php echo FL_CHILD_THEME_URL . '/dashboard-assets/images/cart.svg'; ?>" class="cart_img">
                                                                                <div class="course_detail">
                                                                                    <p class="custom_course_title"><?php echo $rental_equipment_title; ?></p>
                                                                                    <span><?php echo $rental_equipment_price; ?>SGD per pax</span>
                                                                                    <a href="<?php echo home_url () . '/book-now/#/update_order/' . $order_id; ?>" class="add_cart_btn mr-3">Add to Cart</a>  
                                                                                    <a class="course_link add_cart_btn"target="_blank" href="<?php echo $rental_equipment_url; ?>">See More</a>
                                                                                </div>


                                                                            </div>
                                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            //End New Code for all equipments 
                                                        }
                                                    } else {
                                                        ?>	
                                                        <div class="not-found-msg pl-3">
                                                            <h6>Rental Equipments Not Found</h6>
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
                    </div>	      
                </div>	    
            </div>
        </section>
        <?php
    }
}
?>

<?php get_footer ( 'dashboard' ); ?>
