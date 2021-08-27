<?php

if( !\defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_shortcode('get-cabins-plan', 'cb_diverace_get_cabin_plan');
function cb_diverace_get_cabin_plan() {
    ob_start();

    global $post;
	?>
	<div class="vessel-cabin-plan">
		
		<?php
		// Check rows exists.
		if( have_rows('diverace_vessel_cabins') ): ?>
		
			<div class="vessel-cabins">
				
			<?php
    		// Loop through rows.
    		while( have_rows('diverace_vessel_cabins') ) : the_row();

    			$vessel_post_id = get_the_ID();     			
    			?>
			
				<div class="vessel-cabin-floor">
				
					<?php
					
	        		// Load sub field value.
	        		$image = get_sub_field('floorplan');
					$size = 'full'; // (thumbnail, medium, large, full or custom size)
					$cabins = get_sub_field('cabins');
					
					if( $image ) { ?>					
						<div class="cabin-floorplan">							
						<?php 
    						echo wp_get_attachment_image( $image, $size );

    						if ($cabins) { ?>
								<div class="cabin-hotspots">								
									<?php
									foreach ($cabins as $cabin) { 										
										$cabin_id = strtolower(get_field('id', $cabin));
										?>										
										<a href="#cabin-<?php echo $cabin_id; ?>" class="hotspot hotspot-<?php echo $cabin_id; ?>"><?php echo get_field('id', $cabin); ?></a>
										
									<?php
									}
									?>
								</div>

								<div class="cabin-popups">										
								<?php
									foreach ($cabins as $cabin) { ?>
									
										<div id="cabin-<?php echo strtolower(get_field('id', $cabin)); ?>" class="white-popup mfp-hide">
											
											<div class="vessel-cabin-inner">
											
												<?php 
												$images = get_field('gallery', $cabin);

												$size = 'small-size'; // (thumbnail, medium, large, full or custom size)
												if( $images ): ?>
												    <div class="cabin-gallery gallery-cabin-<?php echo strtolower(get_field('id', $cabin)); ?>">
												        <?php foreach( $images as $image_id ): 
												        		/*echo '<pre>';
																print_r($image_id);*/
												        	?>									        	
												               <a href="javascript:void(0);<?php //echo $image_id['url']; ?>">
												                     <img src="<?php echo $image_id['sizes']['small-size']; ?>" alt="<?php echo $image_id['name']; ?>" />
												                </a>								            
												            <?php //echo wp_get_attachment_image( $image_id, $size ); ?>
												        <?php endforeach; ?>
													</div>
												<?php endif; ?>
												
												<?php
												$title = get_the_title($cabin);
												$bow = get_field('bow', $cabin);
												if ($title) { ?>
													<div class="cabin-title">
														<h4><?php echo $title; if ($bow) echo '<span>(' . $bow . ' side bow)</span>'; ?></h4>
													</div>
												<?php } ?>
												
												<?php
												$beds = get_field('beds', $cabin);
												$bathrooms = get_field('bathrooms', $cabin);
												$cabin_persons = get_field('for_how_many_persons',$cabin);
												?>
												
												<div class="cabin-amenities">
												
													<?php
													if ($beds) { ?>
														<div class="cabin-beds" data-vesselpostid="<?php echo $vessel_post_id;?>" data-cabinpostid="<?php echo $cabin;?>" data-cabinpersons="<?php echo $cabin_persons;?>" data-siteurl="<?php echo get_site_url(); ?>">
															<p class="icon-bedroom"><?php echo $beds; ?></p>
														</div>
													<?php }
													
													if ($bathrooms) { ?>
														<div class="cabin-baths" data-vesselpostid="<?php echo $vessel_post_id;?>" data-cabinpostid="<?php echo $cabin;?>" data-cabinpersons="<?php echo $cabin_persons;?>" data-siteurl="<?php echo get_site_url(); ?>">
															<p class="icon-bathroom"><?php echo $bathrooms; ?></p>
														</div>
													<?php } ?>
													
												</div>
												
											</div>
											
										</div>
									
									<?php	
									}
									?>								
								</div>
								
							<?php } ?>
													
						</div>
						
					<?php
					}
					?>					
					
				</div>
				
    		<?php
			// End loop.
    		endwhile;
			?>
			
			</div>
			
		<?php
		// No value.
		endif;
		?>
		
    </div>
    <?php
    
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
}