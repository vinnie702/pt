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
