var tracker = {}

tracker.landingInit = function ()
{
    $('#trackitBtn').click(function(e){
        tracker.checkTrackForm(this);
    });

    // tracker.getTrackedItems();

    // $('#trackSearchForm #q').keyup().change(function(){
    $('#trackSearchForm #q').on('keyup keypress blur focus change', function(){
        console.log('q changed' + $(this).val());
        tracker.getTrackedItems($(this).val());
    });

    $('#gridViewBtn').click(function(e){
        tracker.getTrackedItems();
    });

    $('#tblViewBtn').click(function(e){
        tracker.getTrackedItemsTbl();
    });

    if ($('#viewType').val() == 2)
    {
        tracker.getTrackedItemsTbl();
    }
    else
    {
        tracker.getTrackedItems();
    }

}

tracker.detailsInit = function ()
{

}

tracker.getTrackedItems = function (q)
{
    // global.ajaxLoader('#trackingItemDisplay');
    tracker.toggleLeftSearch(true);

    var search = (q == undefined || q == '') ? '' : '?q=' + escape(q);

    $('#gridViewBtn').attr('disabled', 'disabled');

    $.get("/tracker/gettrackeditems" + search, function(data){
    
        $('#tblViewBtn').removeAttr('disabled');
        $('#trackingItemDisplay').html(data);
    });
}

tracker.getTrackedItemsTbl = function ()
{
    $('#tblViewBtn').attr('disabled', 'disabled');

    tracker.toggleLeftSearch(false);

    $.get("/tracker/gettrackeditemstbl", function(data){
    
        $('#gridViewBtn').removeAttr('disabled');
        $('#trackingItemDisplay').html(data);
        
        if ($('#mainDisplayTbl').exists())
        {
            $('#mainDisplayTbl').dataTable({
                "bStateSave": true
            });
        }

    });
}

tracker.toggleLeftSearch = function (visible)
{
    if (visible == false)
    {
        $('#leftSearchContainer').hide(global.hideEffect);
    }
    else
    {
        $('#leftSearchContainer').show(global.showEffect);
    }
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
        else if (data.status == 'ALERT')
        {
            global.renderAlert(data.msg);
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
        
        var alertDisplay = ($('#trackItemAlert_' + id).exists()) ? 'trackItemAlert_' + id : undefined;

        if (data.status == 'SUCCESS')
        {
            // global.renderAlert(data.msg, 'alert-success', alertDisplay);
            // $(b).removeAttr('disabled');
            $(b).find('i').removeClass('fa-spin');
            
            // reload page
            window.location = '/tracker/details/' + id + '?site-success=' + escape(data.msg);
            
            return true;
        }
        else if (data.status == 'ALERT')
        {
            global.renderAlert(data.msg, undefined, alertDisplay);
            $(b).removeAttr('disabled');
            $(b).find('i').removeClass('fa-spin');
            return false;
        }
        else if (data.status == 'ERROR')
        {

            global.renderAlert(data.msg, 'alert-danger', alertDisplay);
            $(b).removeAttr('disabled');
            $(b).find('i').removeClass('fa-spin');
            return false;
        }
    }, 'json');
}

tracker.viewDetails = function (id)
{
    window.location = '/tracker/details/' + id;
}

tracker.unassignItem = function (b, id)
{
    if (confirm("Are you sure you wish to remove this item?"))
    {
        $(b).attr('disabled', 'disabled');

        $.post("/tracker/unassign", { id: id, pt_token: global.CSRF_hash }, function(data){
            if (data.status == 'SUCCESS')
            {
                global.renderAlert(data.msg, 'alert-success');

                tracker.getTrackedItems();

                return true;
            }
            else if (data.status == 'ERROR')
            {
                global.renderAlert(data.msg, 'alert-danger');
                $(b).removeAttr('disabled');
                return false;
            }
        }, 'json');
    }
}

tracker.assignItem = function (b, id)
{
    $(b).attr('disabled', 'disabled');

    $.post("/tracker/assign", { id: id, pt_token: global.CSRF_hash }, function(data){
        if (data.status == 'SUCCESS')
        {
            global.renderAlert(data.msg, 'alert-success');
            return true;
        }
        else
        {
            global.renderAlert(data.msg, 'alert-danger');
            $(b).removeAttr('disabled');
            return false;
        }
    }, 'json');
}

tracker.reportItem = function (b, id)
    {
        if (confirm("Are you sure you wish to report this item?"))
        {
            $(b).attr('disabled', 'disabled');

            $.post("/tracker/report", { trackingItemID: id, pt_token: global.CSRF_hash }, function(data){
                if (data.status == 'SUCCESS')
                {
                    global.renderAlert(data.msg, 'alert-success');
                    return true;
                }
                else
                {
                    global.renderAlert(data.msg, 'alert-danger');
                    $(b).removeAttr('disabled');
                    return false;
                }
            }, 'json');
        }
    }
