<?php do_action( 'fl_content_close' ); ?>

	</div><!-- .fl-page-content -->
	<?php

	do_action( 'fl_after_content' );

	if ( FLTheme::has_footer() ) :

		?>
	<footer class="fl-page-footer-wrap"<?php FLTheme::print_schema( ' itemscope="itemscope" itemtype="https://schema.org/WPFooter"' ); ?>  role="contentinfo">
		<?php

		do_action( 'fl_footer_wrap_open' );
		do_action( 'fl_before_footer_widgets' );

		FLTheme::footer_widgets();

		do_action( 'fl_after_footer_widgets' );
		do_action( 'fl_before_footer' );

		FLTheme::footer();

		do_action( 'fl_after_footer' );
		do_action( 'fl_footer_wrap_close' );

		?>
	</footer>
	<?php endif; ?>
	<?php do_action( 'fl_page_close' ); ?>
</div><!-- .fl-page -->
<script type="text/javascript">
(function($) {
	
	jQuery(window).load(function () {		
		$( "#iternaries_data_click .iternaries-dynamic-id").first().trigger( "click");
		$( "#iternaries_data_click .iternaries-dynamic-id").first().parent().addClass('current-itineraries-active');		
	});
	
	$(".iternaries-dynamic-id").click(function(event){
     	var iternaries_id = $(this).data("id");
     	
     	$( ".iternaries-dynamic-data").removeClass('current-itineraries-active');   	
     	$(this).first().parent().addClass('current-itineraries-active');       	
     	//$( "#iternaries_data_click .iternaries-dynamic-id").parent().addClass('current-itineraries-active');   	
	    
	    $.ajax({
			type : "post",
			dataType: "json",
			url : "<?php echo admin_url('admin-ajax.php'); ?>",
			data : {
				action: 'iternariesdata_pass',
				iternaries_id:iternaries_id,
			},
			cache: false,
			success: function(response) {                	
				if(response.response == "success" && response.schedule_data !="" && response.schedule_data !="\n"){				
					$('#schedule_data .fl-html').html(response.schedule_data);	
				}else{$('#schedule_data .fl-html').html("<p>No Data</p>");}

				if(response.response == "success" && response.included_data !="" && response.included_data !="\n"){				
					$('#included_data .fl-html').html(response.included_data);	
				}else{$('#included_data .fl-html').html("<p>No Data</p>");}


				if(response.response == "success" && response.additional_data !="" && response.additional_data !="\n"){				
					$('#additional_data .fl-html').html(response.additional_data);	
				}else{$('#additional_data .fl-html').html("<p>No Data</p>");}


				if(response.response == "success" && response.dates_data !="" && response.dates_data !="\n"){				
					$('#dates_data .fl-html').html(response.dates_data);	
				}else{$('#dates_data .fl-html').html("<p>No Data</p>");}

				if(response.response == "success" && response.trips_data !="" && response.trips_data !="\n"){				
					$('#dive_site_data .fl-html').html(response.trips_data);	
				}else{$('#dive_site_data .fl-html').html("<p>No Data</p>");}
			}
	        });       	
	     	//$('#schedule_data .fl-html').text("[get-destination-schedule  itinerary-id="+iternaries_id+"]");
		});

	jQuery(".iternaries-dynamic-data .featured-post-meta .iternaries-expand-info").click(function(evt){

		if(jQuery(this).parents().hasClass('current-itineraries-active')) {
			jQuery('.departure-location-wrapper').toggleClass('show');       
		}
		
		
    });

})(jQuery);
</script>


<?php

wp_footer();

do_action( 'fl_body_close' );

FLTheme::footer_code();

?>
</body>
</html>
