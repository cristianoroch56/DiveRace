<?php

function send_invoice_on_order_place($invoice_id, $user_id) {
    $message   = "";
    $user_data = get_user_by ( 'id', $user_id );
    if( $user_data ) {
        $user_name  = $user_data->first_name;
        $user_email = $user_data->user_email;
    }
    $headers         = array ( 'Content-Type: text/html; charset=UTF-8' );
    $i               = 0;
    $args            = array ( 'post_type' => 'invoice', 'p' => $invoice_id );
    $invoice_details = get_posts ( $args );

    foreach ( $invoice_details as $invoice_list ) {
        $invoice_ID       = $invoice_list->ID;
        $invoice_title    = $invoice_list->post_title;
        $invoice_date     = date ( 'j F, Y' );
        $order_id         = get_field ( 'inv_order_id', $invoice_ID );
        $user_name        = get_field ( 'inv_user_name', $invoice_ID );
        $user_email       = get_field ( 'inv_user_email', $invoice_ID );
        $user_phone_no    = get_field ( 'inv_user_phone_no', $invoice_ID );
        $vessel_name      = get_field ( 'inv_vessel_name', $invoice_ID );
        $loaction         = get_field ( 'inv_loaction', $invoice_ID );
        $trip_name        = get_field ( 'inv_trip_name', $invoice_ID );
        $trip_date        = get_field ( 'inv_trip_date', $invoice_ID );
        $no_of_passenger  = get_field ( 'inv_no_of_passenger', $invoice_ID );
        $coupon           = get_field ( 'inv_coupon', $invoice_ID );
        $agent_name       = get_field ( 'inv_agent_name', $invoice_ID );
        $payment_method   = get_field ( 'inv_payment_method', $invoice_ID );
        $total_amount     = get_field ( 'inv_total_amount', $invoice_ID );
        $pending_amount   = get_field ( 'inv_pending_amount', $invoice_ID );
        $complete_payment = get_field ( 'inv_complete_payment', $invoice_ID );

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
	                      <td class="es-m-p0l es-m-txt-c" align="center" style="padding:0;margin:0;padding-left:15px;font-size:0px"><a href="' . site_url () . '" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:open sans-serif;font-size:14px;text-decoration:underline;color:#999999"><img src="' . site_url () . '/wp-content/uploads/2020/05/logo@2x.png" alt="DiveRACE" title="DiveRACE" width="73" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td> 
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
	                      <td align="center" style="padding:0;margin:0;padding-top:10px;padding-bottom:15px"><h1 style="margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:open sans-serif;font-size:26px !important;font-style:normal;font-weight:normal;color:#333333">Hello ' . $user_name . ',</h1></td> 
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
	                      <td align="left" style="margin:0;padding-bottom:10px;padding-top:20px;padding-left:20px;padding-right:20px"><h4 style="margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:"trebuchet ms", helvetica, sans-serif;font-size:20px">Invoice Details:</h4></td> 
	                     </tr> 
	                     <tr style="border-collapse:collapse"> 
	                      <td align="left" style="padding:0;margin:0;padding-bottom:20px;padding-left:20px;padding-right:20px;border-bottom:1px solid #FFFFFF"> 
	                       <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%" class="cke_show_border" cellspacing="1" cellpadding="1" border="0" align="left" role="presentation"> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Order Id:</b> ' . $order_id . '</td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>User Name:</b> ' . $user_name . '</td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>User Email:</b> ' . $user_email . '</td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>User Phone No:</b> ' . $user_phone_no . '</td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Vessel Name:</b> ' . $vessel_name . '</td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Location:</b> ' . $loaction . '</td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Trip Name:</b> ' . $trip_name . '</td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Trip Date:</b> ' . $trip_date . '</td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>No of Passenger:</b> ' . $no_of_passenger . '</td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Total Amount:</b> ' . $total_amount . '</td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Pending Amount:</b> ' . $pending_amount . '</td> 
	                         </tr> 
	                         <tr style="border-collapse:collapse"> 
	                          <td style="padding:0;margin:0;font-size:14px;line-height:21px"><b>Complete Paymeny:</b> ' . $complete_payment . '</td> 
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
	                      <td class="es-m-p0l es-m-txt-c" align="center" style="padding:0;margin:0;font-size:0px"><a href="' . site_url () . '" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:open sans-serif;font-size:14px;text-decoration:underline;color:#333333"><img src="' . site_url () . '/wp-content/uploads/2020/05/logo@2x.png" alt="DiveRace" title="DiveRace" width="68" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td> 
	                     </tr> 
	                     <tr style="border-collapse:collapse"> 
	                      <td class="es-m-txt-c" align="center" style="padding:0;margin:0;padding-top:5px"><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif;line-height:21px;color:#333333">©' . date ( 'Y' ) . ' DiveRACE. All rights rederved.</p></td> 
	                     </tr> 
	                   </table></td> 
	                 </tr> 
	               </table></td> 
	             </tr> 
	           </table></td> 
	         </tr> 
	       </table></td> 
	     </tr> 
	   </table> ' ;
                
                
               

//        echo $message;

        wp_mail ( $user_email, 'Invoice Successfully Placed', $message, $headers );
    }
    /* end foreach */
}
