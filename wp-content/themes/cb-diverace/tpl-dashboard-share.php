<?php
/*
Template Name: Dashboard Share page
Template Post Type: post, page
*/

global $post, $wpdb;

$userID = '';
if(is_user_logged_in()){        
    $current_user = wp_get_current_user();
    $userID = $current_user->ID;                

}else{
    wp_redirect( home_url() ); exit;
}

get_header('dashboard');
?>

<?php
if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        //get_template_part( 'content', 'page' );
        ?>
        <section class="content">
            <div class="container-fluid">      
                <div class="row">
                    <div class="col-xl-7 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">Share</h2>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="share_cover">
                                        <div class="share_card">
                                            <div class="howitwork_cover">
                                                <h3>How it Works</h3>
                                                <p>Duis et ipsum a massa finibus consectetur. Mauris efficitur varius diam, in ultrices orci cursus auctor quis dignissim nibh, vestibulum luctus nunc.</p>
                                                <div class="howitwork_bg">
                                                    <div class="field_wrap">
                                                        <label>Your Invite Code</label>
                                                        <input type="text" name="Your Invite Code" value="" class="hiw_input" placeholder="psum a massa">
                                                    </div>
                                                    <div class="field_wrap mt-4">
                                                        <label>Your Share Link</label>
                                                        <input type="text" name="Your Invite Code" value="" class="hiw_input" placeholder="Vestibulum luctus nunc sed vehicula">
                                                    </div>
                                                </div>
                                                <a href="#" class="copy_link_btn">Copy Link</a>
                                                <a href="#" class="update_code_btn">Update Code</a>
                                            </div>
                                            <div class="share_via_cover mt-5">
                                                <h3>Share Via</h3>
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                                        <div class="share_via_bg">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                                        <div class="share_via_bg">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                                        <div class="share_via_bg">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="coupon_code_cover mt-5">
                                                <h3>Coupon Code</h3>
                                                <p>You can use the below coupon code for new trip.</p>
                                                <?php
                                                    $coupon_args = array(
                                                        'posts_per_page' => -1,
                                                        'post_type' => 'coupons',
                                                        'orderby' => 'date',
                                                        'order'   => 'DESC',                                          
                                                    );
                                                $coupon_data  = get_posts($coupon_args);
                                                /*echo "<pre>";
                                                print_r($coupon_data);*/
                                                if(count($coupon_data) != 0){
                                                    foreach ($coupon_data as $couponData) {
                                                        $coupon_ID = $couponData->ID;
                                                        $start_date= get_field('coupon_validity_start_date',$couponData->ID);
                                                        $end_date= get_field('coupon_validity_end_date',$couponData->ID);
                                                        $coupon_data_code= get_field('coupon_code',$couponData->ID);
                                                        $coupon_percentage= get_field('coupon_discount_percentage',$couponData->ID);
                                                        $coupon_status= get_field('coupon_status',$couponData->ID);
                                                        $today_date = date("d/m/Y");
                                                        
                                                        $today_date = str_replace('/', '-', $today_date);
                                                        $start_date = str_replace('/', '-', $start_date);
                                                        $end_date = str_replace('/', '-', $end_date);
                                                        
                                                        $curdate=strtotime($today_date);
                                                        
                                                        $mydate=strtotime($start_date);
                                                        $enddate=strtotime($end_date);
                                                        if($coupon_status == 1 ) {
                                                            if (($curdate >= $mydate) && ($today_date <= $enddate)){
                                                                    $coupon_code_is_valid = "Coupon code is valid.";
                                                                }
                                                                else{
                                                                    $coupon_code_is_valid = "Coupon code was expired.";
                                                                }
                                                        }
                                                        break;
                                                    }
                                                }
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="coupon_code_bg">
                                                            <div class="coupon_code"><?php echo $coupon_data_code;?></div>
                                                            <div class="coupon_valid_msg"><?php echo $coupon_code_is_valid;?></div>

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
                </div>        
            </div>
        </section>
        <?php
        }
    }
?>

<?php 
get_footer('dashboard'); ?>
