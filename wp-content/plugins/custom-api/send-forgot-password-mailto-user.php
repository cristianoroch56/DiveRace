<?php 

function send_forgote_password_mailto_user($useremail){
  //echo "mail sent".$order_id;
  /*return $order_id;*/
  //echo $order_id.", ".$user_id;
$message="";
/*$user_data = get_user_by('id',$user_id);
if ($user_data) {
  $user_name = $user_data->first_name;
  $user_email = $user_data->user_email;
}*/
$user = get_user_by( 'email', $useremail );
$user_ID = $user->ID; 
$key = get_password_reset_key($user);
    $rp_link = '<a href="' . site_url() . "/wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login) . '">' . site_url() . "/wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login) . '';
    /*echo $rp_link;
    die();*/
    function wpdocs_set_html_mail_content_type() {
      return 'text/html';
    }


$headers = array('Content-Type: text/html; charset=UTF-8');
$message .='
 <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;"> 
     <tr style="border-collapse:collapse"> 
      <td valign="top" style="padding:0;Margin:0"> 
       <table cellpadding="0" cellspacing="0" class="es-header" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top;"> 
         <tr style="border-collapse:collapse"> 
          <td align="center" style="padding:0;Margin:0"> 
           <table class="es-header-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FEF5E4;width:600px " cellspacing="0" cellpadding="0" bgcolor="#fef5e4" align="center"> 
             <tr style="border-collapse:collapse"> 
              <td align="left" bgcolor="#cccccc" style="Margin:0;padding-top:5px;padding-bottom:5px;padding-left:15px;padding-right:15px;background-color:#CCCCCC"> 
               <table cellspacing="0" cellpadding="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
                 <tr style="border-collapse:collapse"> 
                  <td class="es-m-p0r" valign="top" align="center" style="padding:0;Margin:0;width:570px"> 
                   <table width="100%" cellspacing="0" cellpadding="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
                     <tr style="border-collapse:collapse"> 
                      <td class="es-m-p0l es-m-txt-c" align="center" style="padding:0;Margin:0;padding-left:15px;font-size:0px"><a href="http://68.183.80.245/diverace/#/" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:open sans-serif, sans-serif;font-size:14px;text-decoration:underline;color:#999999"><img src="'.site_url().'/wp-content/uploads/2020/05/logo@2x.png" alt="DiveRACE" title="DiveRACE" width="73" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td> 
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
          <td align="center" style="padding:0;Margin:0"> 
           <table class="es-content-body" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px"> 
             <tr style="border-collapse:collapse"> 
              <td align="left" style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:20px;padding-right:20px"> 
               <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
                 <tr style="border-collapse:collapse"> 
                  <td valign="top" align="center" style="padding:0;Margin:0;width:560px"> 
                   <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;border-radius:0px" width="100%" cellspacing="0" cellpadding="0" role="presentation"> 
                     <tr class="es-visible-simple-html-only" style="border-collapse:collapse"> 
                      <td align="center" style="padding:0;Margin:0;padding-top:10px;padding-bottom:15px"><h1 style="Margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:open sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#333333">Hello '.$user_name.',</h1></td> 
                     </tr> 
                     <tr style="border-collapse:collapse"> 
                      <td align="center" style="Margin:0;padding-top:5px;padding-bottom:5px;padding-left:40px;padding-right:40px"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif, sans-serif;line-height:21px;color:#333333">New user registred on site. <br></p><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif, sans-serif;line-height:21px;color:#333333">Click here in order to reset your password:<br><br>' . $rp_link.'</p></td> 
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
          <td align="center" style="padding:0;Margin:0"> 
           <table class="es-footer-body" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FEF5E4;width:600px"> 
             <tr style="border-collapse:collapse"> 
              <td align="left" bgcolor="#cccccc" style="padding:0;Margin:0;background-color:#CCCCCC"> 
               <table cellspacing="0" cellpadding="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
                 <tr style="border-collapse:collapse"> 
                  <td class="es-m-p0r" valign="top" align="center" style="padding:0;Margin:0;width:600px"> 
                   <table width="100%" cellspacing="0" cellpadding="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
                     <tr style="border-collapse:collapse"> 
                      <td class="es-m-p0l es-m-txt-c" align="center" style="padding:0;Margin:0;font-size:0px"><a href="http://68.183.80.245/diverace" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:open sans-serif, sans-serif;font-size:14px;text-decoration:underline;color:#333333"><img src="'.site_url().'/wp-content/uploads/2020/05/logo@2x.png" alt="DiveRace" title="DiveRace" width="68" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td> 
                     </tr> 
                     <tr style="border-collapse:collapse"> 
                      <td class="es-m-txt-c" align="center" style="padding:0;Margin:0;padding-top:5px"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:open sans-serif, sans-serif;line-height:21px;color:#333333">©'.date('Y').' DiveRACE. All rights rederved.</p></td> 
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

//wp_mail($user_email, 'Order Successfully Placed', $message,$headers);


    add_filter('wp_mail_content_type', 'wpdocs_set_html_mail_content_type');
    $email_successful = wp_mail($useremail, 'Reset password', $message, $headers);
    // Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
    remove_filter('wp_mail_content_type', 'wpdocs_set_html_mail_content_type');
  /*if ($email_successful) {      */
      $data['status'] = true;
      $data['data']['id'] = $user_ID;
      $data['message'] = __("Reset Password link has been sent to your email.", "wp-rest-user");
    /*} else {
      $data['status'] = false;
      $data['data']['status'] = false;
      $data['data']['message'] = __("Failed to send Reset Password email. Check your WordPress Hosting Email Settings.", "wp-rest-user");
      return $data;
    }
*/




  }
