<?php 
function send_email_on_order_place($order_id,$user_id){
	//echo "mail sent".$order_id;
	/*return $order_id;*/
	//echo $order_id.", ".$user_id;
	$message="";
	$user_data = get_user_by('id',$user_id);
	if ($user_data) {
		$user_name = $user_data->first_name;
		$user_email = $user_data->user_email;
	}
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$i=0;		
	$order_data = [];
	$order_ID ="";
	$order_title ="";
	$order_bow ="";
	$order_gallery_image ="";
	$order_beds ="";
	$order_bathrooms="";
	$order_price="";
	$args = array('post_type' => 'orders','p' => $order_id);
	$order_datas= get_posts($args);

	foreach($order_datas as $order_list){
		$order_ID = $order_list->ID;
		$order_title =$order_list->post_title;
		$order_date = date('j F, Y');
		$vessel_id = get_field('vessel_id',$order_ID);
		$country_id = get_field('country_id',$order_ID);
		$destination_id = get_field('destination_id',$order_ID);
		$trip_date_id = get_field('trip_date_id',$order_ID);
		$total_person = get_field('total_person',$order_ID);
		$coupon_data_coupon_id = get_field('coupon_data_coupon_id',$order_ID);
		$coupon_data_coupon_code = get_field('coupon_data_coupon_code',$order_ID);
		$agent_data_agent_id = get_field('agent_data_agent_id',$order_ID);
		$agent_data_agent_code = get_field('agent_data_agent_code',$order_ID);
		$payble_amount = get_field('payble_amount',$order_ID);
		//	 $order_meta_user_ID = get_field('user_id',$order_ID);
		 	//echo $user_id."<br/>";
		$country = get_term_by('id', $country_id, 'country');
		$itternary_post_data_price = get_field('diverace_itinerary_price',$trip_date_id);
		$itternary_post_data_total_DN =get_field('diverace_itinerary_total_days_and_nights',$trip_date_id);

		$start_date = get_field('dive_start_date',$trip_date_id);
		$date_start_date = str_replace('/', '-', $start_date );
		$end_date = get_field('dive_end_date',$trip_date_id);
		$date_end_date = str_replace('/', '-', $end_date );
		$today_date = date("d-m-Y");
		$curdate=strtotime($today_date);
		$startdate=strtotime($date_start_date);
		$enddate=strtotime($date_end_date);
		$itternary_post_data_start_date = date("j F", strtotime($date_start_date));
		$itternary_post_data_end_date = date("j F", strtotime($date_end_date));
		$itternary_post_data_summary = $itternary_post_data_start_date." to ". $itternary_post_data_end_date." ".$itternary_post_data_total_DN." | & Days from &s ".$itternary_post_data_price;

		$vessel_title= get_the_title($vessel_id); 
		$destination_title= get_the_title($destination_id); 
		$country_name= $country->name; 
		$itternary_price= (int)$itternary_post_data_price; 
		$itternary_total_DN= $itternary_post_data_total_DN; 
		$itternary_start_date= $itternary_post_data_start_date; 
		$itternary_end_date= $itternary_post_data_end_date; 
		$itternary_date_summary= $itternary_post_data_summary; 
		// $coupon_title= get_the_title($coupon_data_coupon_id); 
		$coupon_data_coupon_code= $coupon_data_coupon_code; 
		// $order_data[$i]['agent_title']= get_the_title($agent_data_agent_id); 

		$coupon_data ="";
		if($coupon_data_coupon_code != ""){
			$coupon_data = $coupon_data_coupon_code;
		}else{
			$coupon_data = "N/A";
		}

		$agent_data_agent_code= $agent_data_agent_code; 
		$aget_code_data = "";

		if($agent_data_agent_code != ""){
			$aget_code_data = $agent_data_agent_code;
		}else{
			$aget_code_data = "N/A";
		}


		$payble_amount= (int)$payble_amount; 
	 	$total_person= (int)$total_person; 

		$message .='<table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;"> 
	     <tr style="border-collapse:collapse"> 
	      <td valign="top" style="padding:0;margin:0"> 
	       <table cellpadding="0" cellspacing="0" class="es-header" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top;"> 
	         <tr style="border-collapse:collapse"> 
	          <td align="center" style="padding:0;margin:0"> 
	           <table class="es-header-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FEF5E4;width:600px " cellspacing="0" cellpadding="0" bgcolor="#fef5e4" align="center"> 
	             <tr style="border-collapse:collapse"> 
	              <td align="left" bgcolor="#cccccc" style="margin:0;padding-top:5px;padding-bottom:5px;padding-left:15px;padding-right:15px;background-color:#CCCCCC"> 
	               <table cellspacing="0" cellpadding="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
	                 <tr style="border-collapse:collapse"> 
	                  <td class="es-m-p0r" valign="top" align="center" style="padding:0;margin:0;width:570px"> 
	                   <table width="100%" cellspacing="0" cellpadding="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
	                     <tr style="border-collapse:collapse"> 
	                      <td class="es-m-p0l es-m-txt-c" align="center" style="padding:0;margin:0;padding-left:15px;font-size:0px"><a href="'.site_url().'" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:open sans-serif;font-size:14px;text-decoration:underline;color:#999999"><img src="'.site_url().'/wp-content/uploads/2020/05/logo@2x.png" alt="DiveRACE" title="DiveRACE" width="73" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td> 
	                     </tr> 
	                   </table></td> 
	                 </tr> 
	               </table></td> 
	             </tr> 
	           </table></td> 
	         </tr> 
	       </table> 
	       <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%"> 
	         <tr style="border-collapse:collapse"> 
	          <td align="center" style="padding:0;margin:0"> 
	           <table class="es-content-body" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px"> 
	             <tr style="border-collapse:collapse"> 
	              <td align="left" style="margin:0;padding-top:10px;padding-bottom:10px;padding-left:20px;padding-right:20px"> 
	               <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
	                 <tr style="border-collapse:collapse"> 
	                  <td valign="top" align="center" style="padding:0;margin:0;width:560px"> 
	                   <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;border-radius:0px" width="100%" cellspacing="0" cellpadding="0" role="presentation"> 
	                     <tr class="es-visible-simple-html-only" style="border-collapse:collapse"> 
	                      <td align="center" style="padding:0;margin:0;padding-top:10px;padding-bottom:15px"><h1 style="margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:open sans-serif;font-size:26px !important;font-style:normal;font-weight:normal;color:#333333">Hello '.$user_name.',</h1></td> 
	                     </tr> 
	                     <tr style="border-collapse:collapse"> 
	                      <td align="center" style="margin:0;padding-top:5px;padding-bottom:5px;padding-left:40px;padding-right:40px"><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif;line-height:21px;color:#333333">Thank you for choosing DiveRACE. <br></p><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif;line-height:21px;color:#333333">Your order has been completed. The details of your booking are here.</p></td> 
	                     </tr> 
	                   </table></td> 
	                 </tr> 
	               </table></td> 
	             </tr> 
	             <tr style="border-collapse:collapse"> 
	              <td align="left" style="padding:0;margin:0;padding-left:20px;padding-right:20px"> 
	               <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
	                 <tr style="border-collapse:collapse"> 
	                  <td valign="top" align="center" style="padding:0;margin:0;width:560px"> 
	                   <table width="100%" cellspacing="0" cellpadding="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
	                     <tr style="border-collapse:collapse"> 
	                      <td align="center" style="padding:0;margin:0;padding-bottom:10px;font-size:0"> 
	                       <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;border-bottom:1px solid #0B5394;background:none 0% 0% repeat scroll#FFFFFF;height:1px;width:100%;margin:0px"></td> 
	                         </tr> 
	                       </table></td> 
	                     </tr> 
	                   </table></td> 
	                 </tr> 
	               </table></td> 
	             </tr> 
	           </table></td> 
	         </tr> 
	       </table> 
	       <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%"> 
	         <tr style="border-collapse:collapse"> 
	          <td align="center" style="padding:0;margin:0"> 
	           <table class="es-content-body" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px"> 
	             <tr style="border-collapse:collapse"> 
	              <td align="left" style="padding:0;margin:0"> 
	               <table cellspacing="0" cellpadding="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
	                 <tr style="border-collapse:collapse"> 
	                  <td align="left" style="padding:0;margin:0;width:600px"> 
	                   <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;background-color:#FFFFFF;" width="100%" cellspacing="0" cellpadding="0" bgcolor="#fff" role="presentation"> 
	                     <tr style="border-collapse:collapse"> 
	                      <td align="left" style="margin:0;padding-bottom:10px;padding-top:20px;padding-left:20px;padding-right:20px"><h4 style="margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:"trebuchet ms", helvetica, sans-serif;font-size:20px">Order Summery:</h4></td> 
	                     </tr> 
	                     <tr style="border-collapse:collapse"> 
	                      <td align="left" style="padding:0;margin:0;padding-bottom:20px;padding-left:20px;padding-right:20px;border-bottom:1px solid #FFFFFF"> 
	                       <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%" class="cke_show_border" cellspacing="1" cellpadding="1" border="0" align="left" role="presentation"> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Order :</b> '.$order_title.'</td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Order Date:</b> '.$order_date.'</td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Vessel:</b> '.$vessel_title.'</td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Country:</b> '.$country_name.'</td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Itinerary:</b> '.$destination_title.'</td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Trip Dates :</b> '.$itternary_post_data_summary.'</td> 
	                         </tr> 
	                       </table><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif;line-height:21px;color:#333333"><br></p></td> 
	                     </tr> ';

			$cabin_list = get_post_meta($order_ID,"cabin_list",true);
			if($cabin_list!=0){
				$message.='<tr style="border-collapse:collapse"> 
		                      <td align="left" style="padding:0;margin:0;padding-bottom:20px;padding-left:20px;padding-right:20px;border-bottom:1px solid #FFFFFF"><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:open sans-serif;line-height:24px;color:#000000;padding-top:10px"><strong>Cabin Details</strong></p> 
		                       <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%" class="cke_show_border" cellspacing="1" cellpadding="1" border="0" align="left" role="presentation">';
				for ($cab=0; $cab < $cabin_list; $cab++) { 		   		
					$cabinID = get_post_meta($order_ID,"cabin_list_".$cab."_cabinID",true);			 
					$cabin_types = get_post_meta($order_ID,"cabin_list_".$cab."_cabin_types",true);			    	
					$selected_seats = get_post_meta($order_ID,"cabin_list_".$cab."_selected_seats",true);	
					$message.='<tr style="border-collapse:collapse"> 
					              <td style="padding:0;margin:0;font-size:14px;line-height:21px">- '.get_the_title($cabinID).'</td> 
					         </tr> ';			 		
				}  
				$message.='</table><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif;line-height:21px;color:#333333"><br></p></td> </tr>'	;
			}

			if( have_rows('courses_data',$order_ID) ){
				$count=0;
				$message.='<tr style="border-collapse:collapse"> 
		                      <td align="left" style="padding:0;margin:0;padding-bottom:20px;padding-left:20px;padding-right:20px;border-bottom:1px solid #FFFFFF"><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:open sans-serif;line-height:24px;color:#000000;padding-top:10px"><b>Course Details</b></p> 
		                       <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%" class="cke_show_border" cellspacing="1" cellpadding="1" border="0" align="left" role="presentation">';

				while( have_rows('courses_data',$order_ID) ) {
					the_row();
					$courses_id = get_sub_field('courses_id',$order_ID);
					$courses_person = get_sub_field('courses_person',$order_ID);
						// $order_data[$i]['courses_data'][$cb_count]["courses_id"] = $courses_id;
						$message.=' <tr style="border-collapse:collapse"> 
		                      <td style="padding:0;margin:0;font-size:14px;line-height:21px">- '.get_the_title($courses_id).'</td> 
		                      <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Person:</b> '.$courses_person.'</td> 
		                      <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Price :</b> '.get_post_meta($courses_id,"course_price",true).'</td> 
		                     </tr>';
						$count++;
				}
				
				$message.='</table><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif;line-height:21px;color:#333333"><br></p></td> 
		                     </tr> '	;
			}


			if( have_rows('rental_equipment_data',$order_ID) ){
				$count=0;
				$message.='<tr style="border-collapse:collapse"> 
					<td align="left" style="padding:0;margin:0;padding-bottom:20px;padding-left:20px;padding-right:20px;border-bottom:1px solid #FFFFFF"><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:open sans-serif;line-height:24px;color:#000000;padding-top:10px"><b>Rental Equipment Details</b></p> 
					<table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%" class="cke_show_border" cellspacing="1" cellpadding="1" border="0" align="left" role="presentation"> ';

				while( have_rows('rental_equipment_data',$order_ID) ) {
					the_row();
					$rental_equipment_id = get_sub_field('rental__equipment_id',$order_ID);
					$rental_equipment_person = get_sub_field('rental__equipment_person',$order_ID);

					$message.=' <tr style="border-collapse:collapse"> 
		                      <td style="padding:0;margin:0;font-size:14px;line-height:21px">- '.get_the_title($rental_equipment_id).'</td> 
		                      <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Person:</b> '.$rental_equipment_person.'</td> 
		                      <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Price :</b> '.get_post_meta($rental_equipment_id,"rental_equipment_price",true).'</td> 
		                     </tr>';

					$count++;
				}

				$message.='</table><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif;line-height:21px;color:#333333"><br></p></td> 
		                     </tr> ';

			}

			$i++;

			$message.= '<tr style="border-collapse:collapse"> 
							<td align="left" style="padding:0;margin:0;padding-bottom:20px;padding-left:20px;padding-right:20px;border-bottom:1px solid #FFFFFF"><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:open sans-serif;line-height:24px;color:#000000;padding-top:10px"><b>Person Details</b></p> 
							<table class="cke_show_border" cellspacing="1" cellpadding="1" border="0" align="left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%" role="presentation"> 
							 <tr style="border-collapse:collapse"> 
							  <td style="padding:0;margin:0;font-size:14px;line-height:21px">No. of Pax : '.$total_person.'</td> 
							 </tr> 
							</table><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif;line-height:21px;color:#333333"><br></p></td> 
	                     </tr> 

	                     <tr style="border-collapse:collapse"> 
							<td align="center" style="padding:0;margin:0;padding-bottom:10px;font-size:0"> 
							<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
							 <tr style="border-collapse:collapse"> 
							  <td style="padding:0;margin:0;border-bottom:1px solid #0B5394;background:none 0% 0% repeat scroll#FFFFFF;height:1px;width:100%;margin:0px"></td> 
							 </tr> 
							</table></td> 
	                     </tr> 
	                   </table></td> 
	                 </tr> 
	               </table></td> 
	             </tr> 
	           </table></td> 
	         </tr> 
	       </table> 
	       <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%"> 
	         <tr style="border-collapse:collapse"> 
	          <td align="center" style="padding:0;margin:0"> 
	           <table class="es-content-body" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px"> 
	             <tr style="border-collapse:collapse"> 
	              <td align="left" style="margin:0;padding-top:5px;padding-left:20px;padding-bottom:30px;padding-right:40px"> 
	               <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
	                 <tr style="border-collapse:collapse"> 
	                  <td valign="top" align="center" style="padding:0;margin:0;width:540px"> 
	                   <table width="100%" cellspacing="0" cellpadding="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
	                     <tr style="border-collapse:collapse"> 
	                      <td align="right" style="padding:0;margin:0"> 
	                       <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:500px" class="cke_show_border" cellspacing="1" cellpadding="1" border="0" align="right" role="presentation"> 

	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;text-align:right;font-size:15px;line-height:23px">Applied Coupon Code:</td> 
	                          <td style="padding:0;margin:0;text-align:right;font-size:14px;line-height:21px"><strong>'.$coupon_data.'</strong></td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;text-align:right;font-size:15px;line-height:23px">Applied Agent Code:</td> 
	                          <td style="padding:0;margin:0;text-align:right;font-size:14px;line-height:21px"><strong>'.$aget_code_data.'</strong></td> 
	                         </tr> 
	                      

	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;text-align:right;font-size:18px;line-height:27px"><strong>Order Total:</strong></td> 
	                          <td style="padding:0;margin:0;text-align:right;font-size:18px;line-height:27px;color:#006400"><strong>'.$payble_amount.'/-</strong></td> 
	                         </tr> 
	                       </table><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif;line-height:21px;color:#333333"><br></p></td> 
	                     </tr> 
	                   </table></td> 
	                 </tr> 
	               </table></td> 
	             </tr> 
	           </table></td> 
	         </tr> 
	       </table> 
	       <table cellpadding="0" cellspacing="0" class="es-footer" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top"> 
	         <tr style="border-collapse:collapse"> 
	          <td align="center" style="padding:0;margin:0"> 
	           <table class="es-footer-body" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FEF5E4;width:600px"> 
	             <tr style="border-collapse:collapse"> 
	              <td align="left" bgcolor="#cccccc" style="padding:0;margin:0;background-color:#CCCCCC"> 
	               <table cellspacing="0" cellpadding="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
	                 <tr style="border-collapse:collapse"> 
	                  <td class="es-m-p0r" valign="top" align="center" style="padding:0;margin:0;width:600px"> 
	                   <table width="100%" cellspacing="0" cellpadding="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
	                     <tr style="border-collapse:collapse"> 
	                      <td class="es-m-p0l es-m-txt-c" align="center" style="padding:0;margin:0;font-size:0px"><a href="'.site_url().'" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:open sans-serif;font-size:14px;text-decoration:underline;color:#333333"><img src="'.site_url().'/wp-content/uploads/2020/05/logo@2x.png" alt="DiveRace" title="DiveRace" width="68" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td> 
	                     </tr> 
	                     <tr style="border-collapse:collapse"> 
	                      <td class="es-m-txt-c" align="center" style="padding:0;margin:0;padding-top:5px"><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif;line-height:21px;color:#333333">©'.date('Y').' DiveRACE. All rights rederved.</p></td> 
	                     </tr> 
	                   </table></td> 
	                 </tr> 
	               </table></td> 
	             </tr> 
	           </table></td> 
	         </tr> 
	       </table></td> 
	     </tr> 
	   </table> ';

		//echo $message;

		wp_mail($user_email, 'Order Successfully Placed', $message,$headers);
	}
	/*end foreach*/

}