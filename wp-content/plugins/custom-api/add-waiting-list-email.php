<?php 
function send_email_for_waiting_list($last_insert_id,$vessel_id,$destination_id,$trip_date_id,$first_name,$last_name,$email,$phone_number){
	/*echo "last_insert_id__".$last_insert_id;	
	echo "--vessel_id__".$vessel_id;
	echo "--destination_id__".$destination_id;
	echo "--trip_date_id__".$trip_date_id;
	echo "--first_name__".$first_name;
	echo "--last_name__".$last_name;
	echo "--email__".$email;
	echo "--phone_number__".$phone_number;
	exit;*/
	$message="";
	
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$i=0;		
	
	$email_subject = "Your waiting list Deatils - DiveRACE";
	$order_title = "Your waiting list Deatils - DiveRACE";
	$current_date = date('j F, Y');
	
	
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
	$itternary_post_data_summary = $itternary_post_data_start_date." to ". $itternary_post_data_end_date." ".$itternary_post_data_total_DN." | & Days from &s$ ".$itternary_post_data_price;

	$vessel_title= get_the_title($vessel_id); 
	$destination_title= get_the_title($destination_id); 
	

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
                      <td align="center" style="padding:0;margin:0;padding-top:10px;padding-bottom:15px"><h1 style="margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:open sans-serif;font-size:26px !important;font-style:normal;font-weight:normal;color:#333333">Hello '.$first_name.',</h1></td> 
                     </tr> 
                     <tr style="border-collapse:collapse"> 
                      <td align="center" style="margin:0;padding-top:5px;padding-bottom:5px;padding-left:40px;padding-right:40px"><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif;line-height:21px;color:#333333">Thank you for choosing DiveRACE. <br></p><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif;line-height:21px;color:#333333">Your waiting list has added successfully. We will inform through email once the booking is available.</p></td> 
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
                      <td align="left" style="margin:0;padding-bottom:10px;padding-top:10px;padding-left:20px;padding-right:20px"><h4 style="margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:"trebuchet ms", helvetica, sans-serif;font-size:20px">Waiting Summery:</h4></td> 
                     </tr> 
                     <tr style="border-collapse:collapse"> 
                      <td align="left" style="padding:0;margin:0;padding-bottom:20px;padding-left:20px;padding-right:20px;border-bottom:1px solid #FFFFFF"> 
                       <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%" class="cke_show_border" cellspacing="1" cellpadding="1" border="0" align="left" role="presentation"> 
                         <tr style="border-collapse:collapse"> 
                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Booking For :</b> '.$order_title.'</td> 
                         </tr> 
                         <tr style="border-collapse:collapse"> 
                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Date:</b> '.$current_date.'</td> 
                         </tr> 
                         <tr style="border-collapse:collapse"> 
                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Vessel:</b> '.$vessel_title.'</td> 
                         </tr> 
                         <tr style="border-collapse:collapse"> 
                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Itinerary:</b> '.$destination_title.'</td> 
                         </tr> 
                         <tr style="border-collapse:collapse"> 
                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Trip Dates :</b> '.$itternary_post_data_summary.'</td> 
                         </tr> 
                       </table><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif;line-height:21px;color:#333333"><br></p></td> 
                     </tr> ';

		$message.= ' <tr style="border-collapse:collapse"> 
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

	wp_mail($email, $email_subject, $message, $headers);
	

}