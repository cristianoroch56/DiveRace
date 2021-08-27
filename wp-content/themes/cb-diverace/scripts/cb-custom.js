/*(function($) {
	$(".iternaries-dynamic-id").click(function(){
		console.log("tata");
     	alert($(this).data("id"));
	});
})(jQuery);
*/

jQuery(document).ready(function() {
	jQuery(document).on('click', '.cabin-amenities .cabin-beds', function(e){
		e.preventDefault();    
    	var $vessel_post_id = jQuery(this).data('vesselpostid');
    	var $cabin_post_id = jQuery(this).data('cabinpostid');
    	var $cabin_persons = jQuery(this).data('cabinpersons');
    	var $siteu_rl = jQuery(this).data('siteurl');
    	window.location.href = $siteu_rl+"/book-now/#/"+$vessel_post_id+"/"+$cabin_post_id+"/"+$cabin_persons;
	});

	jQuery(document).on('click', '.cabin-amenities .cabin-baths', function(e){
		e.preventDefault();    
    	var $vessel_post_id = jQuery(this).data('vesselpostid');
    	var $cabin_post_id = jQuery(this).data('cabinpostid');
    	var $cabin_persons = jQuery(this).data('cabinpersons');
    	var $siteu_rl = jQuery(this).data('siteurl');
    	window.location.href = $siteu_rl+"/book-now/#/"+$vessel_post_id+"/"+$cabin_post_id+"/"+$cabin_persons;
	});    

});


 