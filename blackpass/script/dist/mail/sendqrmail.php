<?php
header('Content-type: application/json; charset=utf-8');
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require 'vendor/autoload.php';
function enviarmail($w,$x,$y,$z){
$html = "<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<meta http-equiv='X-UA-Compatible' content='IE=edge' />
<style type='text/css'>
    /* CLIENT-SPECIFIC STYLES */
    #outlook a{padding:0;} /* Force Outlook to provide a 'view in browser' message */
    .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */
    body, table, td, a{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
    table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */

    /* RESET STYLES */
    body{margin:0; padding:0;}
    table{border-collapse:collapse !important;}
    body{height:100% !important; margin:0; padding:0; width:100% !important;}

    /* iOS BLUE LINKS */
    .appleBody a {color:#68440a; text-decoration: none;}
    .appleFooter a {color:#999999; text-decoration: none;}

    /* MOBILE STYLES */
    @media screen and (max-width: 525px) {

        /* ALLOWS FOR FLUID TABLES */
        table[class='wrapper']{
          width:100% !important;
        }

        /* ADJUSTS LAYOUT OF LOGO IMAGE */
        td[class='logo']{
          text-align: left;
          padding: 20px 0 20px 0 !important;
        }

        td[class='logo'] img{
          margin:0 auto!important;
        }

        /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
        td[class='mobile-hide']{
          display:none;}

        
        /* FULL-WIDTH TABLES */
        table[class='responsive-table']{
          width:100%!important;
        }

        /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
        td[class='padding']{
          padding: 10px 5% 15px 5% !important;
        }

        td[class='padding-copy']{
          padding: 10px 5% 10px 5% !important;
          text-align: center;
        }

        td[class='padding-meta']{
          padding: 30px 5% 0px 5% !important;
          text-align: center;
        }

        td[class='no-pad']{
          padding: 0 0 20px 0 !important;
        }

        td[class='no-padding']{
          padding: 0 !important;
        }

        td[class='section-padding']{
          padding: 50px 15px 50px 15px !important;
        }

        td[class='section-padding-bottom-image']{
          padding: 50px 15px 0 15px !important;
        }

        /* ADJUST BUTTONS ON MOBILE */
        td[class='mobile-wrapper']{
            padding: 10px 5% 15px 5% !important;
        }

        table[class='mobile-button-container']{
            margin:0 auto;
            width:100% !important;
        }

        a[class='mobile-button']{
            width:80% !important;
            padding: 15px !important;
            border: 0 !important;
            font-size: 16px !important;
        }

    }
</style>
</head>
<body style='margin: 0; padding: 0;'>

<!-- HEADER -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#ffffff'>
		
            <!-- HIDDEN PREHEADER TEXT -->
            <div style='display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;'>
               
            </div>
            <div align='center' style='padding: 0px 15px 0px 15px;'>
			
                <table border='0' cellpadding='0' cellspacing='0' width='500' class='wrapper'>
                    <!-- LOGO/PREHEADER TEXT -->
                    <tr>
                        <td style='padding: 20px 0px 30px 0px;' >
                            <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                <tr>
                                    <td  align='left'>
                                       <a href='http://app.24hopen.com/ve/blackpass/pages/sign-in.html' target='_blank'>
										 <img  src='http://app.24hopen.com/ve/manager/img/logo2.jpg'  >
									  </a> 
                                    </td>

                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
<!-- ONE COLUMN SECTION -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#ffffff' align='center' style='padding: 0px 15px 70px 15px;' class='section-padding'>
            <table border='0' cellpadding='0' cellspacing='0' width='500' class='responsive-table'>
                <tr>
                    <td>
                        <!-- HERO IMAGE -->
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td>
                                    <!-- COPY -->
                                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                        <tr>
                                            <td align='left' style='font-size: 25px; font-family: Helvetica, Arial, sans-serif; color: #333333; padding-top: 30px;' class='padding-copy'>
											<p style='text-align: left'>Hola $x !</p></td>
                                        </tr>
                                        <tr>
                                            <td align='left' style='padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;' class='padding-copy'>
											<p style='text-align: left'>Bienvenido a Black Pass VIP.</p>
											<p style='text-align: left'>Adjunto encontraras tu tarjeta virtual Blacl Pass VIP</p>
											<p style='text-align: left'>Saludos Cordiales</p>
											</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align='center'>
                                    <!-- BULLETPROOF BUTTON -->
                                    <table width='100%' border='0' cellspacing='0' cellpadding='0' class='mobile-button-container'>
                                        <tr>
                                            <td align='center' style='padding: 25px 0 0 0;' class='padding-copy'>
                                                <table border='0' cellspacing='0' cellpadding='0' class='responsive-table'>
                                                    <tr>
                                                        <td align='center'><a href='http://app.24hopen.com/ve/blackpass/pages/sign-in.html' target='_blank' style='font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #000000; border-top: 15px solid #000000; border-bottom: 15px solid #000000; border-left: 25px solid #000000; border-right: 25px solid #000000; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;' class='mobile-button'>Acceso &rarr;</a></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>


<!-- FOOTER -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#ffffff' align='center' style='padding: 20px 0px;'>
            <!-- UNSUBSCRIBE COPY -->
            <table width='500' border='0' cellspacing='0' cellpadding='0' align='center' class='responsive-table'>
                <tr>
                    <td align='center' style='font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666;'>
                       <a href='https://www.itmedia.com.ve/'> <span class='appleFooter' style='color:#666666;'>Derechos Reservados  <span style='color:#00B2B2;font-weight:bold;'>ITMEDIA</span></span></a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>";
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                    //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host = 'mail.itmediaserver.com'; //Set the SMTP server to send through
    $mail->SMTPAuth = true; //Enable SMTP authentication
    $mail->Username = 'blackpass@itmediaserver.com'; //SMTP username
    $mail->Password = 'h?VjTaHycT9H'; //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('blackpass@itmediaserver.com', 'Black Pass VIP');
    $mail->addAddress($w, $x);     //Add a recipient
   // $mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addReplyTo('blackpass@itmediaserver.com', 'Black Pass VIP');
        //$mail->addCC('cc@example.com');
        $mail->addBCC('skarlin@itmedia.com.ve');

    //Attachments
    $mail->addAttachment('files/temp/'.$z);         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Tarjeta Virtual Black Pass VIP';
    $mail->Body    = $html;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    return array(1,"OK");
} catch (Exception $e) {
    return array(0,$mail->ErrorInfo);
}
}
function enviarmail2($w,$x,$y,$l){
$asunto = "";
if($l==1){
	$asunto = "Bienvenido a Black Pass VIP";
}else{
	$asunto = "Recuperación de contraseña Black Pass VIP";
	$asunto = utf8_decode($asunto);
}
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
$html = "<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<meta http-equiv='X-UA-Compatible' content='IE=edge' />
<style type='text/css'>
    /* CLIENT-SPECIFIC STYLES */
    #outlook a{padding:0;} /* Force Outlook to provide a 'view in browser' message */
    .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */
    body, table, td, a{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
    table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */

    /* RESET STYLES */
    body{margin:0; padding:0;}
    table{border-collapse:collapse !important;}
    body{height:100% !important; margin:0; padding:0; width:100% !important;}

    /* iOS BLUE LINKS */
    .appleBody a {color:#68440a; text-decoration: none;}
    .appleFooter a {color:#999999; text-decoration: none;}

    /* MOBILE STYLES */
    @media screen and (max-width: 525px) {

        /* ALLOWS FOR FLUID TABLES */
        table[class='wrapper']{
          width:100% !important;
        }

        /* ADJUSTS LAYOUT OF LOGO IMAGE */
        td[class='logo']{
          text-align: left;
          padding: 20px 0 20px 0 !important;
        }

        td[class='logo'] img{
          margin:0 auto!important;
        }

        /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
        td[class='mobile-hide']{
          display:none;}

        
        /* FULL-WIDTH TABLES */
        table[class='responsive-table']{
          width:100%!important;
        }

        /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
        td[class='padding']{
          padding: 10px 5% 15px 5% !important;
        }

        td[class='padding-copy']{
          padding: 10px 5% 10px 5% !important;
          text-align: center;
        }

        td[class='padding-meta']{
          padding: 30px 5% 0px 5% !important;
          text-align: center;
        }

        td[class='no-pad']{
          padding: 0 0 20px 0 !important;
        }

        td[class='no-padding']{
          padding: 0 !important;
        }

        td[class='section-padding']{
          padding: 50px 15px 50px 15px !important;
        }

        td[class='section-padding-bottom-image']{
          padding: 50px 15px 0 15px !important;
        }

        /* ADJUST BUTTONS ON MOBILE */
        td[class='mobile-wrapper']{
            padding: 10px 5% 15px 5% !important;
        }

        table[class='mobile-button-container']{
            margin:0 auto;
            width:100% !important;
        }

        a[class='mobile-button']{
            width:80% !important;
            padding: 15px !important;
            border: 0 !important;
            font-size: 16px !important;
        }

    }
</style>
</head>
<body style='margin: 0; padding: 0;'>

<!-- HEADER -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#ffffff'>
		
            <!-- HIDDEN PREHEADER TEXT -->
            <div style='display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;'>
               
            </div>
            <div align='center' style='padding: 0px 15px 0px 15px;'>
			
                <table border='0' cellpadding='0' cellspacing='0' width='500' class='wrapper'>
                    <!-- LOGO/PREHEADER TEXT -->
                    <tr>
                        <td style='padding: 20px 0px 30px 0px;' >
                            <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                <tr>
                                    <td  align='left'>
                                       <a href='http://app.24hopen.com/ve/blackpass/pages/sign-in.html' target='_blank'>
										 <img  src='http://app.24hopen.com/ve/manager/img/logo2.jpg'  >
									  </a> 
                                    </td>

                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>

<!-- ONE COLUMN SECTION -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#ffffff' align='center' style='padding: 0px 15px 70px 15px;' class='section-padding'>
            <table border='0' cellpadding='0' cellspacing='0' width='500' class='responsive-table'>
                <tr>
                    <td>
                        <!-- HERO IMAGE -->
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td>
                                    <!-- COPY -->
                                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                        <tr>
                                            <td align='left' style='font-size: 25px; font-family: Helvetica, Arial, sans-serif; color: #333333; padding-top: 30px;' class='padding-copy'>
											<p style='text-align: left'>Hola $x !</p></td>
                                        </tr>
                                        <tr>
                                            <td align='left' style='padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;' class='padding-copy'>
											<p style='text-align: left'>$asunto</p>
											<p style='text-align: left'>Datos de Acceso:</p>
											<p style='text-align: left'>Login: $w</p>
											<p style='text-align: left'>Password: $y </p>
											
											<p style='text-align: left'>Saludos Cordiales</p>
											</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align='center'>
                                    <!-- BULLETPROOF BUTTON -->
                                    <table width='100%' border='0' cellspacing='0' cellpadding='0' class='mobile-button-container'>
                                        <tr>
                                            <td align='center' style='padding: 25px 0 0 0;' class='padding-copy'>
                                                <table border='0' cellspacing='0' cellpadding='0' class='responsive-table'>
                                                    <tr>
                                                        <td align='center'><a href='http://app.24hopen.com/ve/blackpass/pages/sign-in.html' target='_blank' style='font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #000000; border-top: 15px solid #000000; border-bottom: 15px solid #000000; border-left: 25px solid #000000; border-right: 25px solid #000000; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;' class='mobile-button'>Acceso &rarr;</a></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>


<!-- FOOTER -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#ffffff' align='center' style='padding: 20px 0px;'>
            <!-- UNSUBSCRIBE COPY -->
            <table width='500' border='0' cellspacing='0' cellpadding='0' align='center' class='responsive-table'>
                <tr>
                    <td align='center' style='font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666;'>
                       <a href='https://www.itmedia.com.ve/'> <span class='appleFooter' style='color:#666666;'>Derechos Reservados  <span style='color:#00B2B2;font-weight:bold;'>ITMEDIA</span></span></a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>";

try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
   $mail->isSMTP();                                            //Send using SMTP
    $mail->Host = 'mail.itmediaserver.com'; //Set the SMTP server to send through
    $mail->SMTPAuth = true; //Enable SMTP authentication
    $mail->Username = 'blackpass@itmediaserver.com'; //SMTP username
    $mail->Password = 'h?VjTaHycT9H'; //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
     $mail->setFrom('blackpass@itmediaserver.com', 'Black Pass VIP');
    $mail->addAddress($w, $x);     //Add a recipient
   // $mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addReplyTo('blackpass@itmediaserver.com', 'Black Pass VIP');
        //$mail->addCC('cc@example.com');
    $mail->addBCC('skarlin@itmedia.com.ve');

    //Attachments
    //$mail->addAttachment('files/temp/'.$z);         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
    //Content
	
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $asunto;
    $mail->Body    = $html;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    return array(1,"OK");
} catch (Exception $e) {
    return array(0,$mail->ErrorInfo);
}
}
function enviarmailqr($w,$x,$y,$z){
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
$html = "<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<meta http-equiv='X-UA-Compatible' content='IE=edge' />
<style type='text/css'>
    /* CLIENT-SPECIFIC STYLES */
    #outlook a{padding:0;} /* Force Outlook to provide a 'view in browser' message */
    .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */
    body, table, td, a{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
    table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */

    /* RESET STYLES */
    body{margin:0; padding:0;}
    table{border-collapse:collapse !important;}
    body{height:100% !important; margin:0; padding:0; width:100% !important;}

    /* iOS BLUE LINKS */
    .appleBody a {color:#68440a; text-decoration: none;}
    .appleFooter a {color:#999999; text-decoration: none;}

    /* MOBILE STYLES */
    @media screen and (max-width: 525px) {

        /* ALLOWS FOR FLUID TABLES */
        table[class='wrapper']{
          width:100% !important;
        }

        /* ADJUSTS LAYOUT OF LOGO IMAGE */
        td[class='logo']{
          text-align: left;
          padding: 20px 0 20px 0 !important;
        }

        td[class='logo'] img{
          margin:0 auto!important;
        }

        /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
        td[class='mobile-hide']{
          display:none;}

        
        /* FULL-WIDTH TABLES */
        table[class='responsive-table']{
          width:100%!important;
        }

        /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
        td[class='padding']{
          padding: 10px 5% 15px 5% !important;
        }

        td[class='padding-copy']{
          padding: 10px 5% 10px 5% !important;
          text-align: center;
        }

        td[class='padding-meta']{
          padding: 30px 5% 0px 5% !important;
          text-align: center;
        }

        td[class='no-pad']{
          padding: 0 0 20px 0 !important;
        }

        td[class='no-padding']{
          padding: 0 !important;
        }

        td[class='section-padding']{
          padding: 50px 15px 50px 15px !important;
        }

        td[class='section-padding-bottom-image']{
          padding: 50px 15px 0 15px !important;
        }

        /* ADJUST BUTTONS ON MOBILE */
        td[class='mobile-wrapper']{
            padding: 10px 5% 15px 5% !important;
        }

        table[class='mobile-button-container']{
            margin:0 auto;
            width:100% !important;
        }

        a[class='mobile-button']{
            width:80% !important;
            padding: 15px !important;
            border: 0 !important;
            font-size: 16px !important;
        }

    }
</style>
</head>
<body style='margin: 0; padding: 0;'>

<!-- HEADER -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#ffffff'>
		
            <!-- HIDDEN PREHEADER TEXT -->
            <div style='display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;'>
               
            </div>
            <div align='center' style='padding: 0px 15px 0px 15px;'>
			
                <table border='0' cellpadding='0' cellspacing='0' width='500' class='wrapper'>
                    <!-- LOGO/PREHEADER TEXT -->
                    <tr>
                        <td style='padding: 20px 0px 30px 0px;' >
                            <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                <tr>
                                    <td  align='left'>
                                       <a href='http://app.24hopen.com/ve/blackpass/pages/sign-in.html' target='_blank'>
										 <img  src='http://app.24hopen.com/ve/manager/img/logo2.jpg'  >
									  </a> 
                                    </td>

                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>

<!-- ONE COLUMN SECTION -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#ffffff' align='center' style='padding: 0px 15px 70px 15px;' class='section-padding'>
            <table border='0' cellpadding='0' cellspacing='0' width='500' class='responsive-table'>
                <tr>
                    <td>
                        <!-- HERO IMAGE -->
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td>
                                    <!-- COPY -->
                                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                        <tr>
                                            <td align='left' style='font-size: 25px; font-family: Helvetica, Arial, sans-serif; color: #333333; padding-top: 30px;' class='padding-copy'>
											<p style='text-align: left'>Hola $x !</p></td>
                                        </tr>
                                        <tr>
                                            <td align='left' style='padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;' class='padding-copy'>
											<p style='text-align: left'>Bienvenido a Black Pass VIP.</p>
											<p style='text-align: left'>Adjunto encontrarás tu tarjeta virtual</p>
											<p style='text-align: left'>Saludos Cordiales</p>
											</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align='center'>
                                    <!-- BULLETPROOF BUTTON -->
                                    <table width='100%' border='0' cellspacing='0' cellpadding='0' class='mobile-button-container'>
                                        <tr>
                                            <td align='center' style='padding: 25px 0 0 0;' class='padding-copy'>
                                                <table border='0' cellspacing='0' cellpadding='0' class='responsive-table'>
                                                    <tr>
                                                        <td align='center'><a href='http://app.24hopen.com/ve/blackpass/pages/sign-in.html' target='_blank' style='font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #000000; border-top: 15px solid #000000; border-bottom: 15px solid #000000; border-left: 25px solid #000000; border-right: 25px solid #000000; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;' class='mobile-button'>Acceso &rarr;</a></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>


<!-- FOOTER -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#ffffff' align='center' style='padding: 20px 0px;'>
            <!-- UNSUBSCRIBE COPY -->
            <table width='500' border='0' cellspacing='0' cellpadding='0' align='center' class='responsive-table'>
                <tr>
                    <td align='center' style='font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666;'>
                       <a href='https://www.itmedia.com.ve/'> <span class='appleFooter' style='color:#666666;'>Derechos Reservados  <span style='color:#00B2B2;font-weight:bold;'>ITMEDIA</span></span></a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>";
try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
     $mail->Host = 'mail.itmediaserver.com'; //Set the SMTP server to send through
    $mail->SMTPAuth = true; //Enable SMTP authentication
    $mail->Username = 'blackpass@itmediaserver.com'; //SMTP username
    $mail->Password = 'h?VjTaHycT9H'; //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
   $mail->setFrom('blackpass@itmediaserver.com', 'Black Pass VIP');
    $mail->addAddress($w, $x);     //Add a recipient
   // $mail->addAddress('ellen@example.com');               //Name is optional
   $mail->addReplyTo('blackpass@itmediaserver.com', 'Black Pass VIP');
        //$mail->addCC('cc@example.com');
        $mail->addBCC('skarlin@itmedia.com.ve');

    //Attachments
    $mail->addAttachment('../files/temp/'.$z);         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
	$mail->Subject = 'Tarjeta Virtual Black Pass VIP';
    $mail->Body    = $html;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    return array(1,"OK");
} catch (Exception $e) {
    return array(0,$mail->ErrorInfo);
}
}

