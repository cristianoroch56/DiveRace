<?php
/*
Template Name: Dashboard Payment Methods Page
Template Post Type: post, page
*/

global $post, $wpdb;


https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js

require_once ABSPATH . WPINC . '/load.php';
   
define('STRIP_PATH', 'wp-content/themes/cb-diverace/stripe-php-master/');

$strip_init_file =  'init.php';
$strip_lib_file =  '/lib/Stripe.php';
require_once ABSPATH . STRIP_PATH . $strip_init_file ;
require_once ABSPATH . STRIP_PATH . $strip_lib_file ; 

$api_mode_live__test = get_field('api_mode_live__test', 'option');
if($api_mode_live__test == true){
    $strip_api_key = get_field('live_api_key', 'option');
} else {
    $strip_api_key = get_field('test_api_key', 'option');
}
$stripe = new \Stripe\StripeClient([
    "api_key" => $strip_api_key
]);

/*$stripe = new \Stripe\StripeClient([
    "api_key" => "sk_test_tmSXqKQ79z10qzjVAw9LSmew",    // Test API Key
    //"api_key" => "pk_live_q06XKrLKHVnlmk8ozxXNjhRo",    // live API Key            
]);*/



$userID = '';
if(is_user_logged_in()){        
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;                

    $stripe_customer_id = get_the_author_meta('stripe_customer_id', $user_id);     

    if(isset($_POST['savestripcard'])) {
                
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;  
        $user_name = $current_user->user_login; 
        $user_email = $current_user->user_email;           
        $stripe_customer_id = get_the_author_meta('stripe_customer_id', $user_id);     

        $card_number = esc_html($_POST['card_number']);
        $card_expiry_date = esc_html($_POST['card_expiry_date']);
        $card_expiry_date_final = explode('/',$card_expiry_date);
        $card_cvc = esc_html($_POST['card_cvc']);              
        $user_description = 'Test Customer - Diverace';

        if(!empty($stripe_customer_id)){
            $stripe_customer_id = $stripe_customer_id;
        } else{
            $CreateCustomerStrip = $stripe->customers->create([
                'description' => $user_description,
                'email' => $user_email,
                'name' => $user_description,
            ]);
            
            $stripe_customer_id = $CreateCustomerStrip['id'];

            if(!empty($stripe_customer_id)){
                update_user_meta( $user_id, 'stripe_customer_id', $stripe_customer_id );
            }               
        }

        $error = '';
        try {
            $strip_token = $stripe->tokens->create([
                'card' => [
                    'number' => $card_number,
                    'exp_month' => $card_expiry_date_final[0],
                    'exp_year' => $card_expiry_date_final[1],
                    'cvc' => $card_cvc,
                ],
            ]);

        } catch(Stripe_CardError $e) {
          $error = $e->getMessage();
        } catch (Stripe_InvalidRequestError $e) {
          // Invalid parameters were supplied to Stripe's API
          $error = $e->getMessage();
        } catch (Stripe_AuthenticationError $e) {
          // Authentication with Stripe's API failed
          $error = $e->getMessage();
        } catch (Stripe_ApiConnectionError $e) {
          // Network communication with Stripe failed
          $error = $e->getMessage();
        } catch (Stripe_Error $e) {
          // Display a very generic error to the user, and maybe send
          // yourself an email
          $error = $e->getMessage();
        } catch (Exception $e) {
          // Something else happened, completely unrelated to Stripe
          $error = $e->getMessage();
        }
        if(!empty($error)){
            $message = $error;
            $mType = 'error';
        } else{
            $token_id = $strip_token['id'];

            $add_new_card = $stripe->customers->createSource(
                $stripe_customer_id,
                ['source' => $token_id]
            );
            
            $new_card_id = $add_new_card['id'];            

            if(!empty($new_card_id)){
                $message = 'Your card has been added successfully.';
                $mType = 'success';
            } else{
                $message = 'Somthing want wrong.';
                $mType = 'error';
            }

        }
    }

    if(isset($_POST['delete_stripcard'])) {              
        $card_id_is = esc_html($_POST['card_id_is']);
        $delete_card = $stripe->customers->deleteSource(
            $stripe_customer_id,
            $card_id_is,
            []
        );
        $delete_card_id = $delete_card['id']; 
        if(!empty($delete_card_id)){
            $message = 'Your card is deleted successfully.';
            $mType = 'success';
        } else{
            $message = 'Somthing want wrong.';
            $mType = 'error';
        }
    }

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
                <!-- Main row -->
                <div class="row">
                    <!-- Left col -->
                    <div class="col-xl-7 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">
                                    Payment Methods
                                </h2>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="billing_cover">
                                        <div class="billing_card">
                                            <div class="save_payment_cover">
                                                <h3>Saved Payment Sources</h3>
                                                <div class="payment_bg">
                                                <?php
                                                    $current_user = wp_get_current_user();
                                                    $user_id = $current_user->ID;                

                                                    $stripe_customer_id = get_the_author_meta('stripe_customer_id', $user_id); 
                                                    if($stripe_customer_id){
                                                        $list_all_cards = $stripe->customers->allSources(
                                                            $stripe_customer_id,
                                                            ['object' => 'card']
                                                        );   
                                                        $record_data = '';   
                                                    } else{
                                                        $list_all_cards=[];
                                                        $record_data = 'record_data';
                                                    }
                                                    
                                                    /*echo '<pre>';
                                                    print_r($list_all_cards);*/

                                                    ?>
                                                    <ul>
                                                        <li class="head_li">
                                                            Payment Source
                                                            <span>Expires</span>       
                                                        </li>
                                                       
                                                        <?php  
                                                            if(count($list_all_cards)){  
                                                                for($i=0; $i < count($list_all_cards['data']); $i++ )
                                                                { ?>
                                                                    <form class="strip-deletecard-form" id="stripdeletecard_<?php echo $i;?>" action="" method="POST" enctype="multipart/form-data">
                                                                        <li class="sub_li">
                                                                        <?php 

                                                                        $card_id = $list_all_cards['data'][$i]['id'];
                                                                        $card_brand = $list_all_cards['data'][$i]['brand'];
                                                                        $card_last_degits = $list_all_cards['data'][$i]['last4'];

                                                                        $card_exp_month = $list_all_cards['data'][$i]['exp_month'];
                                                                        $card_exp_year = $list_all_cards['data'][$i]['exp_year'];
                                                                        
                                                                            echo $card_brand; ?> (<?php echo $card_last_degits; ?>)
                                                                            <span><?php echo $card_exp_month; ?> / <?php echo $card_exp_year; ?></span>
                                                                            
                                                                            
                                                                            <input type="hidden" name="card_id_is" value="<?php echo $card_id;?>">

                                                                            <input type="submit" id="delete_stripcard_<?php echo $i;?>" name="delete_stripcard" value="Delete Card" class="delete_card_btn" data-cardid="<?php echo $card_id;?>" />

                                                                        </li>                                        
                                                                    </form>                    
                                                                <?php }
                                                                } else{
                                                                    echo '<li class="sub_li '.$record_data.'">
                                                                    <span>Card not found</span>
                                                                    </li>';
                                                                }
                                                            ?>
                                                        
                                                    </ul>
                                                </div>
                                            </div>

                                            

                                            <div class="new_card_cover mt-5">
                                                <?php if(isset($message) && !empty($message)){ ?>
                                                    <div class="notification <?php echo esc_attr($mType); ?> clearfix">
                                                        <div class="noti-icon fas"> </div>
                                                        <p><?php echo esc_html($message); ?></p>
                                                    </div>
                                                <?php } ?>
                                                

                                                <form class="strip-addcard-form" id="savestripcard" action="" method="POST" enctype="multipart/form-data">
                                                    <h3>Add New Card</h3>
                                                    <div class="add_card_bg">
                                                        <div class="row">
                                                            <div class="col-12 field_wrap">
                                                                <label>Card Number</label>
                                                                <input type="text" id="card_number" name="card_number" value="" class="hiw_input" placeholder="Card number" data-inputmask="'mask': '9999 9999 9999 9999'">
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mt-4">
                                                                <label>Expiry Date</label>
                                                                <input type="text" id="card_expiry_date" name="card_expiry_date" value="" class="hiw_input" placeholder="MM / YY" data-inputmask="'mask': '99/99'">
                                                            </div>
                                                            <div class="col-sm-6 field_wrap mt-4">
                                                                <label class="text-center">CVC</label>
                                                                <input type="text" id="card_cvc" name="card_cvc" value="" class="hiw_input" placeholder="CVC" data-inputmask="'mask': '999'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <input type="submit" name="savestripcard" value="Save Details" class="save_detail_btn" />

                                                </form>                       
                                            </div>
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

<?php 
get_footer('dashboard'); ?>
