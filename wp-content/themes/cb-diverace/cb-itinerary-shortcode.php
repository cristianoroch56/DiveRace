<?php



/*========Ajax function ===========*/

function iternariesdata_pass_function() {
    $jason_respons = array(); 
    $post_link = $_POST['iternaries_id'];
    $schedule_data =  do_shortcode("[get-destination-schedule itinerary-id=".$post_link."]");
    $included_data =  do_shortcode("[get-destination-included itinerary-id=".$post_link."]");
    $additional_data =  do_shortcode("[get-destination-additional itinerary-id=".$post_link."]");
    $dates_data =  do_shortcode("[get-destination-dates itinerary-id=".$post_link."]");
    $trips_data =  do_shortcode("[get-destination-trips itinerary-id=".$post_link."]");

   
   /* if($post_data !=""){   */     
         $jason_respons["response"] = 'success';
         $jason_respons["schedule_data"] = $schedule_data;
         $jason_respons["included_data"] = $included_data;
         $jason_respons["additional_data"] = $additional_data;
         $jason_respons["dates_data"] = $dates_data;
         $jason_respons["trips_data"] = $trips_data;
       //  array('response'=>'success','mydata'=>$post_data);
   /* }
*/
    echo( json_encode($jason_respons));  
    exit;    

}
add_action( 'wp_ajax_iternariesdata_pass', 'iternariesdata_pass_function' );    // If called from admin panel
add_action( 'wp_ajax_nopriv_iternariesdata_pass', 'iternariesdata_pass_function' ); 



/*=====destination-schedule====*/
function get_destination_schedule( $atts ) {
    ob_start();
    $itinerary_id = $atts['itinerary-id'];
    ?>

    <?php if( have_rows('diverace_itinerary_schedule',$itinerary_id) ): ?>
        <div class="row custom_schedule_data grid-container">
        <?php
        while( have_rows('diverace_itinerary_schedule',$itinerary_id) ) : the_row();
            $day = get_sub_field('day',$itinerary_id);
            $activities = get_sub_field('activities',$itinerary_id);?>
           <div class="col-md-4 grid-item">
                <div class="single-item-box">
                    <div class="day-icon">
                       <img src="<?php echo get_theme_file_uri().'/images/ic_calendar_time.png'?>" alt="Day Icon">
                    </div>
                    <h5> <?php  echo  $day; ?></h5>
                    <div class="description">
                        <?php echo $activities;?>
                    </div>
                </div>
            </div>

            <?php
        endwhile;
    ?>
    </div>
    <?php 
    endif;
    ?>
    <?php 
    $schedule_data = ob_get_clean();
        return $schedule_data;
}

add_shortcode( 'get-destination-schedule', 'get_destination_schedule' );



/*====get-destination-included===*/

function get_destination_included( $atts ) {
    ob_start();
    $itinerary_id = $atts['itinerary-id'];
    ?>

    <?php if( have_rows('diverace_itinerary_included',$itinerary_id) ): ?>
    <div class="row custom-destination-included grid-container">
        <?php
        while( have_rows('diverace_itinerary_included',$itinerary_id) ) : the_row();
           
            $item = get_sub_field('item',$itinerary_id);?>
           <div class="col-md-4 grid-item">
                <div class="item-description">
                    <div class="img-sec">
                        <img src="<?php echo get_theme_file_uri().'/images/icon-tick.png'?>" alt="Tick Icon">
                    </div>  
                    <div class="desc-content">  
                        <?php echo $item;?>
                    </div>    
                </div>
            </div>

            <?php
        endwhile;
    ?>
    </div>
    <?php 
    endif;
    ?>
    <?php 
    $included_data = ob_get_clean();
        return $included_data;
}
add_shortcode( 'get-destination-included', 'get_destination_included' );


/*====get-destination-additional===*/

function get_destination_additional( $atts ) {
    ob_start();
    $itinerary_id = $atts['itinerary-id'];
    ?>

    <?php if( have_rows('diverace_itinerary_additional',$itinerary_id) ): ?>
    <div class="row custom-destination-included grid-container">
        <?php
        while( have_rows('diverace_itinerary_additional',$itinerary_id) ) : the_row();
           
            $item = get_sub_field('item',$itinerary_id);?>
           <div class="col-md-4 grid-item">
                <div class="item-description">   
                    <div class="img-sec">
                        <img src="<?php echo get_theme_file_uri().'/images/icon-cross.png'?>" alt="Tick Icon">
                    </div>
                    <div class="desc-content">
                        <?php 
                            if (strpos($item, 'see pricing') !== false) {
                                echo str_replace('see pricing','<a href="javascript:void(0);" data-modal="custompricepopup-01" class="show-price-box">see pricing</a>',$item);                                
                            } else{
                                echo $item;    
                            }                           
                        ?>
                    </div>    
                </div>
            </div>

            <?php
        endwhile;
    ?>
    </div>

    <!-- Start Price Popup modal -->
        <!-- <div class="uabb-modal uabb-drag-fix uabb-modal-content uabb-modal-custom uabb-effect-1 uabb-aspect-ratio-16_9 uabb-show" id="modal-custompricepopup01" data-content="content" style="top: 10.5px; transform: none;">
            <div class="uabb-content ">
                <span class="uabb-modal-close uabb-close-custom-popup-top-right" style="top: 0px;">
                    <i class="uabb-close-icon fas fa-times" aria-hidden="true"></i>
                </span>
                
                <div class="uabb-modal-title-wrap">
                    <h4 class="uabb-modal-title">Equipment Rental</h4>
                </div>
                <div class="uabb-modal-text uabb-modal-content-data uabb-text-editor fl-clearfix">
                    <table>
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Rental Cost</th>
                                <th>Lost Equipment charges</th>
                            </tr>   
                        </thead>
                        <tbody>
                            <tr>
                                <td>Buoyancy Control Device</td>
                                <td>10 SGD per day</td>
                                <td>450 SGD</td>
                            </tr>
                            <tr>
                                <td>Regulator</td>
                                <td>10 SGD per day</td>
                                <td>700 SGD</td>
                            </tr>
                            <tr>
                                <td>Mask</td>
                                <td>5 SGD per day</td>
                                <td>50 SGD</td>
                            </tr>
                            <tr>
                                <td>Fins</td>
                                <td>5 SGD per day</td>
                                <td>180 SGD</td>
                            </tr>
                            <tr>
                                <td>Wetsuit</td>
                                <td>10 SGD per day</td>
                                <td>180 SGD</td>
                            </tr>
                            <tr>
                                <td>15L Aluminium Tank</td>
                                <td>100 SGD per day</td>
                                <td>300 SGD</td>
                            </tr>
                            <tr>
                                <td>Underwater torch</td>
                                <td>15 SGD per day</td>
                                <td>120 SGD</td>
                            </tr>
                            <tr>
                                <td>Dive Computer</td>
                                <td>15 SGD per day</td>
                                <td>450 SGD</td>
                            </tr>
                            <tr>
                                <td>Full Set (Mask, Fins, Wetsuit, BCD, Reg)</td>
                                <td>450 SGD per day</td>
                                <td>N.A.</td>
                            </tr>
                            <tr>
                                <td>Nitrox Fill</td>
                                <td>15 SGD per fill</td>
                                <td>N.A.</td>
                            </tr>
                            <tr>
                                <td>2lb Lead weights</td>
                                <td>Free of Change</td>
                                <td>10 SGD per price</td>
                            </tr>
                            <tr>
                                <td>Weight Belt</td>
                                <td>Free of Change</td>
                                <td>12 SGD</td>
                            </tr>                   
                        </tbody>
                    </table>
                </div>

            </div>
        </div> -->
    <!-- End Price Popup modal -->
    <?php 
    endif;
    ?>
    <?php 
    $additional_data = ob_get_clean();
        return $additional_data;
}
add_shortcode( 'get-destination-additional', 'get_destination_additional' );




/*====get-destination-dates avilable===*/

function get_destination_dates_available( $atts ) {
    ob_start();
    $itinerary_id = $atts['itinerary-id'];
    ?>
    <?php
    $featured_posts = get_field('diverace_itinerary_dates_available',$itinerary_id);
    $diverace_itinerary_price = get_field('diverace_itinerary_price',$itinerary_id);
    $dive_total_day_night = get_field('diverace_itinerary_total_days_and_nights',$itinerary_id);
    
    $dive_start_date = get_field('dive_start_date',$itinerary_id);
    $dive_start_date1 =str_replace('/','-',$dive_start_date);
    $dive_start_date_new = date('Y-m-d',strtotime($dive_start_date1));    
    $final_start_date_final = date("j M", strtotime($dive_start_date_new)); 
   
    $startdate_exp1 = new DateTime($dive_start_date_new);
    $week_of_start_date = $startdate_exp1->format("W");
   

    $dive_end_date = get_field('dive_end_date',$itinerary_id);
    $dive_end_date1 =str_replace('/','-',$dive_end_date);
    $dive_end_date_new = date('Y-m-d',strtotime($dive_end_date1));    
    $final_end_date_final = date("j M", strtotime($dive_end_date_new)); 
    $count = 0;
    
    $totalrow = count($featured_posts);
    
    
    if( $featured_posts ): ?>
    <div class="row custom-destination-included date-available-wrapper grid-container">
        <?php 
        $i = 1;
        foreach( $featured_posts as $post ): 
            $count = $count + 1;

            if( $i==5 ){
                break;
            }
            // Setup this post for WP functions (variable must be named $post).
            $permalink = get_permalink( $post->ID );
            $title = get_the_title( $post->ID );
             ?>
            <div class="<?php echo 'AA_'.$totalrow.' ';?><?php if($totalrow < 4) { echo 'col-3'; } else { echo 'col'; } ?> grid-item item_count_<?php echo $i;?>">
                <div class="date-item-box">
                    <div class="img-sec">
                        <img src="<?php echo get_theme_file_uri().'/images/ic_calendar.png'?>" alt="Tick Icon">
                    </div>
                    <div class="trip-week-title post_id_is<?php echo $post->ID?>">
                        <?php echo 'Week '.$week_of_start_date;?>
                    </div>
                    <div class="date-availables">
                        <?php echo $final_start_date_final.' - '.$final_end_date_final;?>
                    </div>  
                    <div class="date-btn-section">
                        <a href="<?php echo $permalink; ?>" class="fl-button btn-lets-go">Let's go</a>
                    </div>  
                    <!-- <a href="<?php echo $permalink; ?>"><h5><?php echo $title; ?></h5></a> -->            
                </div>
            </div>

        <?php 
            $i++;
            endforeach;

            if($count == 4){ ?>
                <div class="col grid-item item_content_center item_count_<?php echo $i;?>">
                    <div class="date-item-box">                        
                        <div class="img-sec-wrap">
                            <img src="<?php echo get_theme_file_uri().'/images/plus_icon.png'?>" alt="Tick Icon">
                        </div>  
                        <a href="<?php echo home_url().'/trip/';?>" class="click_to_other_trips"> Other Trip Dates</a>
                    </div>
                </div>    
            <?php  } ?>
        
    </div>

        <?php 
        wp_reset_postdata(); ?>
    <?php endif; ?>

    <?php 
    $dates_data = ob_get_clean();
        return $dates_data;
}
add_shortcode( 'get-destination-dates', 'get_destination_dates_available' );

/*====diverace_itinerary_dive_sites===*/

function get_destination_dive_sites( $atts ) {
    ob_start();
    $itinerary_id = $atts['itinerary-id'];
    ?>
    <?php
    $featured_posts = get_field('diverace_itinerary_dive_sites',$itinerary_id);

    if( $featured_posts ): ?>
    <div class="row custom-destination-included grid-container the-divet-sites">                   
            
            <?php 
                $lat_lng_arr=[]; 
                foreach( $featured_posts as $post ): 

                    // Setup this post for WP functions (variable must be named $post).
                    $permalink = get_permalink( $post->ID );
                    $title = get_the_title( $post->ID );
                    $post_content = get_post_field('post_content', $post->ID);
                    $feature_img = get_the_post_thumbnail($post->ID, 'gmap-popup-size');                    

                    $location = get_field('address_of_trip', $post->ID);
                    
                    $post_lat = $location['lat'];
                    $post_lng = $location['lng'];
                    $post_html = "";
                    $post_html = '<div class="dive_sites_popup_wrapper">';
                        $post_html .='<div class="feature_img">'.$feature_img.'</div>';
                        $post_html .='<h5 class="post_title">'.$title.'</h5>';
                        $post_html .='<div class="post_content">'.$post_content.'</div>';
                    $post_html .="<div>";                                        
                    $post_html_arr[]=[$post_lat,$post_lng,$post_html];
                    ?>
                    <!-- <div class="col-md-4 grid-item">
                        <a href="<?php echo $permalink; ?>"><h5><?php echo $title; ?></h5></a>                        
                    </div> -->

            <?php endforeach; ?>

            <div id="map" style="width: 100%; height: 650px;"></div> 
            
            <script type="text/javascript">
                var map_marker=[];
                //initMap @return  object The map instance. 
                <?php foreach($post_html_arr as $key=>$posthtml){ ?>
                    map_marker[<?php echo $key; ?>]=['<?php echo $posthtml[0]; ?>','<?php echo $posthtml[1]; ?>','<?php echo $posthtml[2] ?>','<?php echo $posthtml[3] ?>'];
       
                <?php } ?>

                
                // Render maps on page load.
                jQuery(document).ready(function(){
                    initMap(map_marker)
                });             
            </script>
           
    

        </div>
        <?php 
        wp_reset_postdata(); ?>
    <?php endif; ?>

    <?php 
    $dates_data = ob_get_clean();
        return $dates_data;
}
add_shortcode( 'get-destination-trips', 'get_destination_dive_sites' );