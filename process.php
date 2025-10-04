<?php
require 'config.php';
session_start();

// Generate CSRF Token
if (empty($_SESSION['__csrf'])) {
    $_SESSION['__csrf'] = bin2hex(random_bytes(32));
}

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF Token
    if (!hash_equals($_SESSION['__csrf'], $_POST['__csrf'])) {
        header('Location: index.php?InvalidCSRF=1');
        exit;
    }

    // Sanitize and validate input
    $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject']), ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars(trim($_POST['message']), ENT_QUOTES, 'UTF-8');

    // Validate all fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        header('Location: index.php?EmptyFields=1');
        exit;
    }

    if (!$email) {
        header('Location: index.php?InvalidEmail=1');
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'humayun11998@gmail.com';
        $mail->Password = 'mvmlfituyitfxgyu';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->CharSet = 'UTF-8';

        // Recipients
        $mail->setFrom('humayun11998@gmail.com', 'Portfolio Contact Form');
        $mail->addAddress('humayun11998@gmail.com', 'Humayun');
        $mail->addReplyTo($email, $name);

        // Insert data into Database
        $stmt = $conn->prepare('INSERT INTO contact_tbl (contact_name, contact_email, contact_subject, contact_message) VALUES (:cname, :cemail, :csubject, :cmessage)');
        $stmt->bindParam(':cname', $name);
        $stmt->bindParam(':cemail', $email);
        $stmt->bindParam(':csubject', $subject);
        $stmt->bindParam(':cmessage', $message);
        $result = $stmt->execute();

        if ($result) {
            // Content
            $mail->isHTML(true);
            $mail->Subject = "New Contact: $subject";

            // HTML Email Body
            $mail->Body = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f5f5f5; padding: 20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; max-width: 600px; border: 1px solid #dddddd;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #2c3e50; padding: 25px 30px; border-bottom: 3px solid #3498db;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 22px; font-weight: normal;">You Have a New Message</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 30px;">
                            
                            <p style="margin: 0 0 20px 0; color: #333333; font-size: 15px; line-height: 1.5;">
                                You have received a new message from your portfolio contact form.
                            </p>
                            
                            <!-- Contact Information -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 12px 15px; background-color: #f8f9fa; border-left: 3px solid #3498db;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td width="80" style="color: #555555; font-size: 14px; font-weight: bold;">Name:</td>
                                                <td style="color: #333333; font-size: 14px;">' . $name . '</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr><td style="height: 1px; background-color: #e0e0e0;"></td></tr>
                                <tr>
                                    <td style="padding: 12px 15px; background-color: #f8f9fa; border-left: 3px solid #3498db;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td width="80" style="color: #555555; font-size: 14px; font-weight: bold;">Email:</td>
                                                <td style="color: #3498db; font-size: 14px;">
                                                    <a href="mailto:' . $email . '" style="color: #3498db; text-decoration: none;">' . $email . '</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr><td style="height: 1px; background-color: #e0e0e0;"></td></tr>
                                <tr>
                                    <td style="padding: 12px 15px; background-color: #f8f9fa; border-left: 3px solid #3498db;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td width="80" style="color: #555555; font-size: 14px; font-weight: bold;">Subject:</td>
                                                <td style="color: #333333; font-size: 14px;">' . $subject . '</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Message Section -->
                            <div style="margin: 25px 0;">
                                <p style="margin: 0 0 10px 0; color: #555555; font-size: 14px; font-weight: bold;">Message:</p>
                                <div style="background-color: #f8f9fa; border: 1px solid #e0e0e0; border-left: 3px solid #3498db; padding: 15px; border-radius: 3px;">
                                    <p style="margin: 0; color: #333333; font-size: 14px; line-height: 1.6; white-space: pre-wrap;">' . nl2br($message) . '</p>
                                </div>
                            </div>
                            
                            <!-- Reply Button -->
                            <div style="margin: 30px 0 20px 0; text-align: center;">
                                <a href="mailto:' . $email . '?subject=Re: ' . rawurlencode($subject) . '" style="display: inline-block; padding: 12px 35px; background-color: #3498db; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: bold; border-radius: 3px;">Reply to Message</a>
                            </div>
                            
                            <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 25px 0;">
                            
                            <p style="margin: 0; color: #999999; font-size: 12px; text-align: center;">
                                Received on ' . date('F j, Y \a\t g:i A') . '
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 30px; background-color: #f8f9fa; border-top: 1px solid #e0e0e0;">
                            <p style="margin: 0; color: #777777; font-size: 12px; text-align: center;">
                                This is an automated message from your portfolio contact form.
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
            ';

            // Plain Text Alternative
            $mail->AltBody = "NEW CONTACT FORM SUBMISSION\n\n" .
                "You have received a new message from your portfolio contact form.\n\n" .
                "CONTACT DETAILS\n" .
                "---------------\n" .
                "Name: $name\n" .
                "Email: $email\n" .
                "Subject: $subject\n\n" .
                "MESSAGE\n" .
                "-------\n" .
                "$message\n\n" .
                "Received on " . date('F j, Y \a\t g:i A') . "\n\n" .
                "Reply to: $email";

            $mail->send();

            // Success redirect
            header('Location: index.php?MessageSent=1');
            exit;
        } else {
            throw new Exception('Database insert failed');
        }
    } catch (Exception $e) {
        // Log error (recommended)
        error_log("Contact Form Error: " . $e->getMessage());

        // User-friendly error redirect
        header('Location: index.php?SendFailed=1');
        exit;
    }
} else {
    // If not POST request, redirect
    header('Location: index.php');
    exit;
}
?>