<?php if(!defined('BASEPATH')) die('Direct access not allowed'); ?>

<input type='hidden' id='token' value='<?=$this->security->get_csrf_hash()?>'>
<div class='row'>

<div class='col-lg-6 col-lg-offset-3'>

<div class='panel panel-default'>
<div class='panel-heading'><i class='fa fa-sign-in'></i> Login</div>

<div class='panel-body'>

<?php if(!empty($msg)) : ?>
<div class='alert'><?=$msg?></div>
<?php endif; ?>


<?php

    if (!empty($_COOKIE['rememberEmail']))
    {
        $emaiml = $_COOKIE['rememberEmail'];
        $remCheck = 'checked';
    }


    $attr = array
        (
            'name' => 'loginForm',
            'id' => 'loginForm',
            'method' => 'post',
            'class' => 'form-horizontal'
        );

echo form_open('/welcome/login', $attr);
?>
<input type='hidden' name='ref' value='<?=$_GET['ref']?>'>


<div class="form-group">
    <label class='col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label' for='email'>E-mail Address</label>
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 controls">
        <input type='text' class='form-control' name='email' id='email' autocomplete="off" value="<?=$email?>">
    </div> <!-- .controls -->
</div> <!-- .form-group -->


<div class="form-group">
    <label class='col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label' for='password'>Password</label>
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 controls">
        <input type='password'  name='password' id='password' class='form-control' autocomplete="off">

        <a href='javascript:void(0);' data-toggle='modal' data-target='#passwordModal'>Forgot Password?</a>
    </div> <!-- .controls -->
</div> <!-- .form-group -->


<?php
/*
<div class="form-group">
    <label class='col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label' for='rememberMe'>&nbsp;</label>
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 controls">
        <label class='checkbox'>
            <input type='hidden' name='rememberMe' value='0'>
            <input type='checkbox' name='rememberMe' id='rememberMe' value='1' <?=$remCheck?>> Remember Me
        </label>
    </div> <!-- .controls -->
</div> <!-- .form-group -->
 */
?>
    </div> <!-- .panel-body -->

    <div class='panel-footer'>

        <button type='submit' id='loginBtn' class='btn btn-primary'><i class='fa fa-sign-in'></i> Login</button>
    </div>

<!--
<div class='row'>

    <div class='col-lg-9 col-offset-3'>
        <button type='submit' id='loginBtn' class='btn btn-primary'>Login</button>
    </div>

</div>
//-->


</form>

</div> <!-- panel -->

</div> <!-- col -->
</div> <!-- row -->




<!-- forgot password modal //-->
<div id='passwordModal' class='modal fade'>
    <div class='modal-dialog'>
        <div class='modal-content'>

    <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
        <h2><i class='fa fa-question'></i> Forgot Password</h2>
    </div> <!-- .modal-header //-->

    <div class='modal-body'>
        <div id='passwordAlert'></div>

        <p class='lead'>Forgot your password? Please enter your e-mail address below.</p>

        <input type='text' class='form-control' name='fpEmail' id='fpEmail' value="" placeholder="email@domain.com">

    </div> <!-- .modal-body //-->

    <div class='modal-footer'>
        <button class='btn btn-default`' data-dismiss='modal' aria-hidden='true' id='cancelFPBtn'>Cancel</button>
        <button type='button' class='btn btn-primary' aria-hidden='true' id='submitFPBtn'>Submit</button>
    </div> <!-- .modal-footer //-->

        </div> <!-- .modal-content -->
    </div> <!-- .modal-dialog -->
</div> <!-- .modal -->




