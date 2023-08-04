<?php require_once('config.php'); ?>

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

<!-- New Modal for Verification -->
<div class="modal fade" id="verificationModal" tabindex="-1" role="dialog" aria-labelledby="verificationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="verificationModalLabel">Verify Your Email Address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Please enter the verification code that was sent to your email address.</p>
        <input type="text" class="form-control" id="verification-code" placeholder="Verification Code">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submit-verification-btn">Verify</button>
      </div>
    </div>
  </div>
</div>


<script>
$(document).ready(function() {
  $('#submit-verification-btn').click(function() {
    var verificationCode = $('#verification-code').val();
    $.ajax({
      url: '/verify-email',
      type: 'POST',
      data: {
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
        $('#verificationModal').modal('show');
    });

        function validateEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        }
    });

</script>
