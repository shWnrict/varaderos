<?php require_once('config.php'); ?>

<style>
    #uni_modal .modal-content>.modal-footer,
    #uni_modal .modal-content>.modal-header {
        display: none;
    }

    .modal-login {
      display: inline!important;
      background-color:#fff; 
      color: #D72C01 ;
      border-radius: 10px;
    }
</style>

<div class="container-fluid modal-login">
    <div class="row">
        <h3 class="float-right">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </h3>
        <div class="col-lg-12">
            <h3 class="text-center">Login</h3>
            <hr>
            <form action="" id="login-form">
                <div class="form-group">
                    <label for="email" class="control-label">Email</label>
                    <input type="email" class="form-control form" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password" class="control-label">Password</label>
                    <input type="password" class="form-control form" name="password" required>
                </div>
                <div class="form-group d-flex justify-content-between">
                    <!-- <a href="javascript:void(0)" id="create_account">Create Account</a> -->
                    <a href="signup-user.php" id="create_account">Create Account</a>
                    <button type="submit" class="btn btn-primary btn-flat">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function() {
        // $(document).on('click', '#create_account', function() {
        //     $('#loginModal .modal-content').load('registration.php', function() {
        //         $('#loginModal').modal('show');
        //     });
        // });
        $('#login-form').submit(function(e) {
            e.preventDefault();
            start_loader();
            if ($('.err-msg').length > 0)
                $('.err-msg').remove();
            $.ajax({
                url: _base_url_ + "classes/Login.php?f=login_user",
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                error: err => {
                    console.log(err);
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success: function(resp) {
                    if (typeof resp === 'object' && resp.status === 'success') {
                        alert_toast("Login Successfully", 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else if (resp.status === 'incorrect') {
                        var _err_el = $('<div>')
                        _err_el.addClass("alert alert-danger err-msg").text("Incorrect Credentials.");
                        $('#login-form').prepend(_err_el);
                        end_loader();
                    } else {
                        console.log(resp);
                        alert_toast("An error occurred", 'error');
                        end_loader();
                    }
                }
            });
        });
    });
</script>
