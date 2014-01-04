var tracker = {}

tracker.landingInit = function ()
{
    $('#trackitBtn').click(function(e){
        tracker.checkTrackForm(this);
    });
}

tracker.checkTrackForm = function (b)
{
    if ($('#trackitForm #url').val() == '')
    {
        global.renderAlert("Please enter a url!");
        $('#trackitForm #url').focus();
        $('#trackitForm #url').effect(global.effect);
        return false;
    }

    $(b).attr('disabled', 'disabled');

    $.post("/tracker/addurl", $('#trackitForm').serialize(), function(data){
        if (data.status == 'SUCCESS')
        {
            global.renderAlert(data.msg, 'alert-success');

            // clears track url input
            $('#trackitForm #url').val('');
        }
        else
        {
            global.renderAlert(data.msg, 'alert-danger');
        }

        $(b).removeAttr('disabled');

    }, 'json');
}
