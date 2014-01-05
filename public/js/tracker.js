var tracker = {}

tracker.landingInit = function ()
{
    $('#trackitBtn').click(function(e){
        tracker.checkTrackForm(this);
    });

    tracker.getTrackedItems();

}

tracker.getTrackedItems = function ()
{
    global.ajaxLoader('#trackingItemDisplay');

    $.get("/tracker/gettrackeditems", function(data){
        $('#trackingItemDisplay').html(data);
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

            tracker.getTrackedItems();
        }
        else
        {
            global.renderAlert(data.msg, 'alert-danger');
        }

        $(b).removeAttr('disabled');

    }, 'json');
}

tracker.grabInfo = function (b, id)
{
    $(b).attr('disabled', 'disabled');
    $(b).find('i').addClass('fa-spin');

    $.post("/grabber/grabinfo", { id: id, pt_token: global.CSRF_hash }, function(data){
        if (data.status == 'SUCCESS')
        {
            global.renderAlert(data.msg, 'alert-success', 'trackItemAlert_' + id);
            $(b).removeAttr('disabled');
            $(b).find('i').removeClass('fa-spin');
            return true;
        }
        else if (data.status == 'ALERT')
        {
            global.renderAlert(data.msg, undefined, 'trackItemAlert_' + id);
            $(b).removeAttr('disabled');
            $(b).find('i').removeClass('fa-spin');
            return false;
        }
        else if (data.status == 'ERROR')
        {
            global.renderAlert(data.msg, 'alert-danger', 'trackItemAlert_' + id);
            $(b).removeAttr('disabled');
            $(b).find('i').removeClass('fa-spin');
            return false;
        }
    }, 'json');
}