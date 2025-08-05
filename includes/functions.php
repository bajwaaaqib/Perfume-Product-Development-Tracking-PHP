<?php

function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

// Simple email sender using PHP mail()
// For local dev, it writes the link to mail_log.txt (simulate sending)
function sendInviteEmail($email, $token) {
    $link = "http://localhost/perfume-dashboard/set_password.php?token=$token";
    $message = "You have been invited to join the Perfume Dashboard.\nSet your password here:\n$link";
    
    // For localhost testing: write link to mail_log.txt
    file_put_contents(__DIR__ . '/../mail_log.txt', "Invite for $email: $link\n", FILE_APPEND);
    
    // On live server, you can replace below with mail() or PHPMailer
    // mail($email, "Invitation to Perfume Dashboard", $message);
    
    return true;
}
