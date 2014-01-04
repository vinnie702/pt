var global = {}

global.CSRF_token = 'token';
global.CSRF_hash = '';

global.showEffect = 'highlight';
global.hideEffect = 'highlight';

global.effect = 'highlight';

// jquery function to check if element exists;
jQuery.fn.exists = function(){ return this.length>0; }


jQuery.fn.cancelButton = function(location)
{
    this.click(function(e){
        if (confirm("Are you sure you wish to cancel?"))
        {
            window.location = location;
            this.setAttribute('disabled', 'disabled');
        }
    });
}

$(function(){
    // if hidden input exists for CSRF token, gets value
    if ($('#' + global.CSRF_token).exists())
    {
        global.CSRF_hash = $('#' + global.CSRF_token).val();
    }

    if ($('#viewCartBtn').exists())
    {
        $('#viewCartBtn').click(function(e){
            $(this).attr('disabled', 'disabled');
            window.location = '/cart';
        });
    }

    if ($('#q').exists())
    {
        // $('#q').css('width', '300px');
    }
});


/*
 * renders a site wide alert
 *
 * @param String msg - msg to be displayed
 * @param String type (optional) - type of message to be displayed, default is blank, alternate types: 'alert-success', 'alert-error' or 'alert-info'
 * @param String id (optional) - specifcy custom div ID to display the error
 */

global.renderAlert = function(msg, type, id)
{
    var header = "Alert!";

    if (id == undefined)
    {
        id = "site-alert";

        $("html, body").animate({ scrollTop: 0 }, "slow");
    }

    if (msg == '' || msg == undefined)
    {
        $("#" + id).html('');
        return;
    }

    if (type == undefined)
    {
        type = 'alert-warning';
    }


    // patch for bootstrap 3
    if (type == 'alert-error') type = 'alert-danger';

    //$("#" + id).html("<div class='ui-widget'><div class='ui-state-error ui-corner-all' style=\"padding: 0 .7em;\"><p><span class='ui-icon ui-icon-alert' style=\"float: left; margin-right: .3em;\"></span><strong>Alert:</strong> "+msg+"</p></div></div>");


    if (type == 'alert-danger') header = "Error!";
    if (type == 'alert-info') header = 'Information';
    if (type == 'alert-success') header = 'Success!';

    $('#' + id).html("<div class='alert " + type + "'><button type='button' class='close' data-dismiss='alert'>&times;</button><h4>" + header + "</h4> " + msg +"</div>");
return true;

}


