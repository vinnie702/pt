var users = {}

users.loginas = function (b, user)
{
    $(b).attr('disabled', 'disabled');

    $.post("/welcome/loginas", { user: user, pt_token:global.CSRF_hash }, function(data){
        if (data.status == 'SUCCESS')
        {
            window.location = '/tracker/landing?site-success=' + escape(data.msg);
        }
        else
        {
            global.renderAlert(data.msg, 'alert-danger');
            $(b).removeAttr('disabled');
        }
    }, 'json');
}
