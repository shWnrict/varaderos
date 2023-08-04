<?php
require_once('config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST["verify-btn"])) {
    $name = $_POST["firstname"] . " " . $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Generate verification code
    $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

    // Check if email address already exists
    $query = "SELECT * FROM email_acc WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "Email address already exists.";
    } else {
        // Send verification email
        $mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'shawnteves8@gmail.com'; // Replace with your Gmail SMTP username
            $mail->Password = 'mhzxdlyyfdinyotv'; // Replace with your Gmail SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Set email details
            $mail->setFrom('shawnteves8@gmail.com', 'Varadero Spirits'); // Replace with your desired FROM name and email
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = 'Email verification';
            $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        // Add verification code to database
        $query = "INSERT INTO email_acc (email, verification_code) VALUES ('$email', '$verification_code')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Success
            echo "Verification code sent successfully.";
        } else {
            // Error
            echo "Failed to send verification code.";
        }
    }
}

?>

<style>
    #uni_modal .modal-content>.modal-footer,
    #uni_modal .modal-content>.modal-header {
        display: none;
    }
</style>

<div class="container-fluid">
    <form action="" id="registration">
        <div class="row">
            <h3 class="text-center">Create New Account
                <span class="float-right">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </span>
            </h3>
            <hr>
        </div>
        <div class="row  align-items-center h-100">
            <div class="col-lg-5 border-right">
                <div class="form-group">
                    <label for="firstname" class="control-label">Firstname</label>
                    <input type="text" class="form-control form-control-sm form" name="firstname" required>
                </div>
                <div class="form-group">
                    <label for="lastname" class="control-label">Lastname</label>
                    <input type="text" class="form-control form-control-sm form" name="lastname" required>
                </div>
                <div class="form-group">
                    <label for="contact" class="control-label">Contact</label>
                    <input type="text" class="form-control form-control-sm form" name="contact" required>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="form-group">
                    <label for="default_delivery_address" class="control-label">Default Delivery Address</label>
                    <textarea class="form-control form" rows="3" name="default_delivery_address"></textarea>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label">Email</label>
                    <input type="text" class="form-control form-control-sm form" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password" class="control-label">Password</label>
                    <input type="password" class="form-control form-control-sm form" name="password" required>
                </div>
                <div class="form-group d-flex justify-content-between">
                    <a href="javascript:void(0)" id="login-show">Already Registered</a>
                    <button type="button" class="btn btn-primary btn-flat" id="verify-btn">Register</button>
                </div>
            </div>
        </div>
    </form>
</div>


<script>
    $(function() {
        $('#login-show').click(function() {
            $('#loginModal .modal-content').load('login.php', function() {
                $('#loginModal').modal('show');
            });
        });

        $('#verify-btn').click(function() {
        var email = $('[name="email"]').val();
        if (!validateEmail(email)) {
            alert_toast("Invalid email address. Please enter a valid email.", 'error');
            return;
        }
        $('#loginModal .modal-content').load('verification.php', function() {
                $('#loginModal').modal('show');
            });
    });
        function validateEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        }
    $(document).ready(function() {
        $('#submit-verification-btn').click(function() {
            var verificationCode = $('#verification-code').val();
                $.ajax({
            url: 'verification.php',
            type: 'POST',
            data: {
                email: email,
                verificationCode: verificationCode
            },
            success: function(data) {
                if (data.status === 'success') {
                $('#verificationModal').modal('hide');
                alert('Your email address has been verified.');
                } else {
                alert(data.msg);
                }
            },
            error: function(error) {
                console.log(error);
            }
            });
        });
    });   
});

</script>
