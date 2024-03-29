var welcome = {}

welcome.indexInit = function ()
{

    $('#compareBtn').click(function(e){

        window.location = '/welcome/compare';

        $(this).attr('disabled', 'disabled');
        $(this).find('i').removeClass('fa-random');
        $(this).find('i').addClass('fa-spinner');
        $(this).find('i').addClass('fa-spin');
    });
}

welcome.compareInit = function ()
{
    $('#signupFree').removeAttr('disabled');
    $('#signupPremium').removeAttr('disabled');

    $('#signupFree').click(function(e){

        var campaignTxt = '?type=2';

        if ($('#utm_campaign').exists())
        {
            campaignTxt += "&utm_campaign=" + $('#utm_campaign').val();
        }

        window.location = 'https://bms.cgisolution.com/register/index/' + global.company + campaignTxt;

        $(this).attr('disabled', 'disabled');
        $(this).find('i').removeClass('fa-pencil');
        $(this).find('i').addClass('fa-spinner');
        $(this).find('i').addClass('fa-spin');
    });

    $('#signupPremium').click(function(e){
        var campaignTxt = '?type=1';

        if ($('#utm_campaign').exists())
        {
            campaignTxt += "&utm_campaign=" + $('#utm_campaign').val();
        }

        window.location = 'https://bms.cgisolution.com/register/index/' + global.company + campaignTxt;

        $(this).attr('disabled', 'disabled');
        $(this).find('i').removeClass('fa-pencil');
        $(this).find('i').addClass('fa-spinner');
        $(this).find('i').addClass('fa-spin');
    });
}

welcome.loginInit = function ()
{
    $('#submitFPBtn').click(function(e){
        welcome.checkForgotPasswordForm();
    });
}
/*
welcome.checkLoginForm = function ()
{
    if ($('#email').val()  == '')
    {
        global.renderAlert("Please enter an e-mail address!");
        $('#email').focus();
        $('#email').effect(global.effect);
        return false;
    }


    return false;
}
*/

welcome.register = function (company)
{
    var campaignTxt = '';

    if ($('#utm_campaign').exists())
    {
        campaignTxt = "?utm_campaign=" + $('#utm_campaign').val();
    }

    window.location = 'https://bms.cgisolution.com/register/index/' + company + campaignTxt;
}


welcome.contactusInit = function ()
{
    $('#submitBtn').click(function(e){
        welcome.checkForm();
    });
}


welcome.resetpasswordInit = function ()
{
    $('#submitBtn').click(function(e){
        welcome.checkResetPasswdForm();
    });
}



welcome.checkForm = function ()
{
    if ($('#name').val() == '')
    {
        global.renderAlert('Please enter your name.');
        $('#name').effect(global.effect);
        $('#name').focus();
        return false;
    }

    if ($('#email').val() == '' && $('#phone').val() == '')
    {
        global.renderAlert('Please enter an E-mail address or phone number so we may contact you back..');
        $('#email').effect(global.effect);
        $('#phone').effect(global.effect);
        $('#phone').focus();
        return false;
    }

    if ($('#message').val() == '')
    {
        global.renderAlert('Please enter a message. We Can\'t read minds yet =[');
        $('#message').effect(global.effect);
        $('#message').focus();
        return false;
    }

    $('#submitBtn').attr('disabled', 'disabled');

    $.post("/welcome/sendcontactus", $('#contactusForm').serialize(), function(data){
            if (data.status == 'SUCCESS')
            {
                global.renderAlert(data.msg, 'alert-success');
            }
            else if (data.status == 'ALERT')
            {
                global.renderAlert(data.msg);
                $('#submitBtn').removeAttr('disabled');
            }
            else if (data.status == 'ERROR')
            {
                global.renderAlert(data.msg, 'alert-danger');
                $('#submitBtn').removeAttr('disabled');
            }
    }, 'json');


}


welcome.checkForgotPasswordForm = function ()
{
    if ($('#fpEmail').val() == '')
    {
        global.renderAlert('Please enter an email address!', undefined, 'passwordAlert')
        $('#fpEmail').effect(global.effect);
        $('#fpEmail').focus();
        return false;
    }

    // clears alerts
    global.renderAlert('', undefined, 'passwordAlert');

        $.post("/welcome/forgotpassword", { fpEmail: $('#fpEmail').val(), pt_token:global.CSRF_hash  }, function(data){

            if (data.status == 'SUCCESS')
            {
                $('#passwordModal').modal('hide');
                $('#fpEmail').val(''); // clears email value

                global.renderAlert(data.msg, 'alert-success');
            }
            else if (data.status == 'ALERT')
            {
                global.renderAlert(data.msg, 'alert-error', 'passwordAlert');
                return false;
            }
            else
            {
                global.renderAlert(data.msg, 'alert-danger', 'passwordAlert');
                return false;
            }
    }, 'json');
}


welcome.checkResetPasswdForm = function ()
{
    if ($('#password').val() == '')
    {
        global.renderAlert("Please enter a password!");
        $('#password').focus();
        $('#password').effect(global.effect);
        return false;
    }

    if ($('#confirmPassword').val() == '')
    {
        global.renderAlert("Please confirm the password!");
        $('#confirmPassword').focus();
        $('#confirmPassword').effect(global.effect);
        return false;
    }

    if ($('#password').val() !== $('#confirmPassword').val())
    {
        global.renderAlert("Passwords do not match!");
        $('#password').focus();
        $('#password').effect(global.effect);
        return false;
    }

    $('#submitBtn').attr('disabled', 'disabled');

    $.post("/welcome/processpasswordrequest", $('#resetPasswordForm').serialize(), function(data){
            if (data.status == 'SUCCESS')
            {
                window.location = "/welcome/login?site-success=" + escape(data.msg);
            }
            else if (data.status == 'ALERT')
            {
                global.renderAlert(data.msg);
                $('#submitBtn').removeAttr('disabled');
                return false;
            }
            else
            {
                global.renderAlert(data.msg, 'alert-error');
                $('#submitBtn').removeAttr('disabled');
                return false;
            }

    }, 'json');
}
