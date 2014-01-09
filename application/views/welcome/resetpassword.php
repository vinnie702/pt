<?php if(!defined('BASEPATH')) die('Direct access not allowed'); ?>

<h1><i class='fa fa-key'></i> Reset Password</h1>


<p class='lead'>Please create a new password for your account.</p>

<?php

    $attr = array
        (
            'name' => 'resetPasswordForm',
            'id' => 'resetPasswordForm',
            'class' => 'form-horizontal'
        );

echo form_open('/intranet/login', $attr);
?>

    <input type='hidden' name='requestID' id='requestID' value='<?=$requestID?>'>

    <div class="form-group">
        <label class='col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label' for=''>E-mail</label>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 controls">
            <label><?=$email?></label>
        </div> <!-- .controls -->
    </div> <!-- .form-group -->

    <div class="form-group">
        <label class='col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label' for='password'>New Password</label>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 controls">
                <input type='password' class='form-control' name='password' id='password' placeholder='&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;'>
        </div> <!-- .controls -->
    </div> <!-- .form-group -->

    <div class="form-group">
        <label class='col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label' for='confirmPassword'>Confirm Password</label>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 controls">
            <input type='password' class='form-control' name='confirmPassword' id='confirmPassword' placeholder='&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;'>
        </div> <!-- .controls -->
    </div> <!-- .form-group -->
<hr>

    <button type='button' class='btn btn-primary' id='submitBtn'>Submit</button>

</form>
