<?php
/*
  Template Name: Dashboard Testimonial Page
  Template Post Type: post, page
 */

global $post, $wpdb;

$userID = '';
if( is_user_logged_in () ) {
    $current_user = wp_get_current_user ();
    $user_id      = $current_user->ID;
    $user         = new WP_User ( $user_id );
    if( ! empty ( $user->roles ) && is_array ( $user->roles ) ) {
        foreach ( $user->roles as $role ) {
            if( $role == 'agent' ) {
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
                        array (
                            'key'     => 'user_id',
                            'value'   => $user_id,
                            'compare' => '=',
                        ),
                    ),
                );
            } elseif( $role == 'subscriber' || $role = 'administrator' ) {
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
                        array (
                            'key'     => 'user_id',
                            'value'   => $user_id,
                            'compare' => '=',
                        ),
                    ),
                );
            }
        }
    }
    $order_datas = get_posts ( $args );

    $i          = 0;
    $order_data = [];

    foreach ( $order_datas as $order_list ) {
        /* echo '<pre>';
          print_r($order_list); */
        if( ! empty ( $user_id ) ) {
            $order_id = $order_list->ID;

            $order_title                                   = $order_list->post_title;
            $user_id                                       = get_field ( 'user_id', $order_id );
            $order_already_credited                        = get_post_meta ( $order_id, 'testimonial_user_credit', true );
            $order_data[ $i ][ 'trip_completed_order_id' ] = $order_id;
            $order_data[ $i ][ 'trip_completed_user_id' ]  = $user_id;
            $order_already_credited;

            if( ! empty ( $order_already_credited ) ) {
                $order_data[ $i ][ 'trip_already_credited' ] = $order_already_credited;
            } else {
                $order_data[ $i ][ 'trip_already_credited' ] = "";
            }
        }
        $i ++;
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
                    <div class="col-xl-7 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">
                                    Testimonials form for completed trips. 
                                </h2>
                            </div>
                            <?php
                            if( ! empty ( $order_data ) ) {
                                //echo 'YYYY'; 
                                /* echo '<pre>';
                                  print_r($order_data); */

                                $j = 0;
                                foreach ( $order_data as $orderdata ) {
                                    /* echo '<pre>';
                                      print_r($orderdata); */
                                    if( empty ( $orderdata[ 'trip_already_credited' ] ) ) {
                                        //echo $orderdata['trip_completed_order_id'];

                                        if( $orderdata[ 'trip_already_credited' ] != 'yes' ) {
                                            /* echo '<pre>';
                                              print_r($order_data); */

                                            $testimonials_to_order_id = $orderdata[ 'trip_completed_order_id' ];
                                            $trip_already_credited    = $orderdata[ 'trip_already_credited' ];
                                            ?>
                                            <div class="card-body">
                                                <div class="tab-content p-0">
                                                    <div class="billing_cover">
                                                        <div class="billing_card">
                                                            <div class="save_payment_cover account_detail_cover">
                                                                <div class="field_wrap">
                                                                    <?php
                                                                    echo do_shortcode ( '[gravityform id="3" title="false" description="false" ajax="true"]' );
                                                                    ?>
                                                                </div>
                                                            </div>

                                                            <div class="new_card_cover mt-5">
                                                                <?php if( isset ( $message ) && ! empty ( $message ) ) { ?>
                                                                    <div class="notification <?php echo esc_attr ( $mType ); ?> clearfix">
                                                                        <div class="noti-icon fas"> </div>
                                                                        <p><?php echo esc_html ( $message ); ?></p>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script type="text/javascript">
                                                jQuery(document).on('gform_post_render', function (event, form_id, current_page) {
                                                    var add_extra_filed = jQuery('<input type="hidden" name="testimonials_to_order_id" class="testimonials_to_order_id" value=<?php echo $testimonials_to_order_id; ?>>');
                                                    //alert(jQuery('.testimonials_to_order_id').val());
                                                    jQuery("#gform_3").append(add_extra_filed);
                                                });
                                            </script>                                             
                                            <?php
                                            break;
                                        }
                                    } else {
                                        /* echo "Not EMPTY";
                                          ?>
                                          <div class="card-body">
                                          <div class="tab-content p-0">
                                          <h5>As per your last trip you already filled up testimonial.</h5>
                                          </div>
                                          </div>
                                          <?php
                                          break; */
                                    }

                                    $j ++;
                                }
                                ?> 

                                <?php
                            } else {
                                //echo 'NOOO';
                                ?> 
                                <div class="card-body">
                                    <div class="tab-content p-0">
                                        <h5>When any trip is completed then testimonial form is display.</h5>
                                    </div>
                                </div>
                            <?php } ?> 
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
