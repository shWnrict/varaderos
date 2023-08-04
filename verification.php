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
        </div>


