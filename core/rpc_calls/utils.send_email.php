<?php
/*
 * rpc helper for PHPMailer 
 */

$rpc = array(array(

/* receiver as array */

'mail_to' => array (
  'type'     => 'array',
  'required' => true,
  'origin'   => array (
    'variable:$_buffer["mail_to"]',
)),


/* subject */

'subject' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_buffer["subject"]'
)),


/* message */

'message' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_buffer["message"]'
)),


/* message in HTML */

'message_html' => array (
  'type'     => 'string',
  'required' => false,
  'origin'   => array (
    'variable:$_buffer["message_html"]',
    'code:echo(nl2br(htmlentities($_buffer["message"])))'
)),


/* sender */

'sender' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_buffer["sender"]',
    'variable:$_->settings["mail_sender"]'
)),


/* sender name */

'sender_name' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_buffer["sender_name"]',
    'variable:$_->settings["mail_sender_name"]',
    'variable:$_buffer["sender"]'
)),


/* smtp_server */

'smtp_server' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_buffer["smtp_server"]',
    'variable:$_->settings["mail_smtp_server"]',
)),


/* smtp_user_name */

'smtp_user' => array (
  'type'     => 'string',
  'required' => false,
  'origin'   => array (
    'variable:$_buffer["smtp_user"]',
    'variable:$_->settings["mail_smtp_user"]'
)),


/* smtp_user_name */

'smtp_pass' => array (
  'type'     => 'string',
  'required' => false,
  'origin'   => array (
    'variable:$_buffer["smtp_pass"]',
    'variable:$_->settings["mail_smtp_pass"]'
)),

),

/* rpc function */
  
function(&$_, $_buffer, &$_output) use (&$self)
{
  require_once("../core/3rd/PHPMailer/class.phpmailer.php");

  $mail             = new PHPMailer();

  /* server stuff */

  $mail->IsSMTP();
  $mail->Host       = $_buffer["smtp_server"];
  $mail->SMTPDebug  = 0;                    // enables SMTP debug information
                                            // 1 = errors and messages
                                            // 2 = messages only
  $mail->SMTPAuth   = true;
//$mail->Port       = 26;
  $mail->Username   = $_buffer["smtp_user"];
  $mail->Password   = $_buffer["smtp_pass"];

  
  /* message stuff */

  $mail->SetFrom      ($_buffer["sender"], $_buffer["sender_name"]);
//$mail->AddReplyTo   ("name@yourdomain.com","First Last");
  $mail->Subject    = $_buffer["subject"];
  
  $mail->AltBody    = $_buffer["message"];
  $body             = eregi_replace("[\]",'',$_buffer["message_html"]);
  $mail->MsgHTML      ($_buffer["message_html"]);

  foreach($_buffer["mail_to"] as $receiver) {
    $mail->AddAddress($receiver, $receiver);
  }

//$mail->AddAttachment("images/phpmailer.gif");

  if(!$mail->Send()) {
    $_output['STDERR'] = array(
      'signal'    => 'EMAIL_SEND_FAILED',
      'call'      => array($self['name']),
      'info'      => $mail->ErrorInfo);
      
    return FALSE;  
  }

  return TRUE; 
});
?>
