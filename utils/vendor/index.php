<?php
require_once 'email/swift_required.php';
Swift::registerAutoload();
 try {
    // Create the SMTP transport
    $transport = (new Swift_SmtpTransport('smtp.mailtrap.io', 2525))
        ->setUsername('1a2b3c4d5e6f7g')
        ->setPassword('1a2b3c4d5e6f7g');

    $mailer = new Swift_Mailer($transport);

    // Create a message
    $message = new Swift_Message();

    $message->setSubject('Thanks for choosing Our Hotel!');
    $message->setFrom(['confirmation@hotel.com' => 'Your Hotel']);
    $message->addTo('me@gmail.com','Me');
    // Add attachment
   $attachment = Swift_Attachment::fromPath('./confirmations/yourbooking.pdf');
    $message->attach($attachment);

    // Set the plain-text part
    $message->setBody('Hi there, we are happy to confirm your booking. Please check the document in the attachment.');
     // Set the HTML part
    $message->addPart('Hi there, we are happy to <br>confirm your booking.</br> Please check the document in the attachment.', 'text/html');
     // Send the message
    $result = $mailer->send($message);
} catch (Exception $e) {
  echo $e->getMessage();
}