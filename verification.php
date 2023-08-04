<!DOCTYPE html>
<html>
<head>
    <!-- Add any required CSS and JavaScript dependencies here -->
</head>
<body>
    <form method="POST" id="emailVerificationForm">
        <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" required>
        <input type="text" name="verification_code" placeholder="Enter verification code" required />
        <input type="submit" name="verify_email" value="Verify Email">
    </form>

    <script>
        $(function() {
            $('#emailVerificationForm').submit(function(e) {
                e.preventDefault();
                start_loader();
                if ($('.err-msg').length > 0)
                    $('.err-msg').remove();
                $.ajax({
                    url: 'verify_email.php',
                    method: "POST",
                    data: $(this).serialize(),
                    dataType: "html",
                    error: err => {
                        console.log(err);
                        alert_toast("An error occurred", 'error');
                        end_loader();
                    },
                    success: function(response) {
                        // Display the verification result in a modal or alert
                        // For simplicity, let's assume there's a modal with ID "verificationModal"
                        $('#verificationModal .modal-body').html(response);
                        $('#verificationModal').modal('show');
                        end_loader();
                    }
                });
            });
        });
    </script>
</body>
</html>
