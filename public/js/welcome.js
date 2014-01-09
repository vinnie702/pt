var welcome = {}

welcome.loginInit = function ()
{

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
    window.location = 'https://bms.cgisolution.com/register/index/' + company;
}


welcome.contactusInit = function ()
{
    $('#submitBtn').click(function(e){
        welcome.checkForm();
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
