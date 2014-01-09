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


    global.adjustNavbar();

    // executes function on resizing of window
    $(window).resize(function(){
        global.adjustNavbar();
    });

    $(window).load(function(){

        // re-adjusts nav bar once page is completely loaded
        global.adjustNavbar();

    });
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
    var header = "<i class='fa fa-exclamation-triangle'></i> Alert";

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


    if (type == 'alert-danger') header = "<i class='fa fa-times-circle-o'></i> Error";
    if (type == 'alert-info') header = "<i class='fa fa-exclamation-circle'></i> Information";
    if (type == 'alert-success') header = "<i class='fa fa-thumbs-up'></i> Success";

    $('#' + id).html("<div class='alert " + type + "'><button type='button' class='close' data-dismiss='alert'>&times;</button><h4>" + header + "</h4> " + msg +"</div>");
return true;

}


global.ajaxLoader = function(divId)
{
    var html = "<div class='row' style=\"margin:50px 0;\">" +
        "<div class='well col-lg-2 col-lg-offset-4 col-m-2 col-m-offset-4 col-s-2 col-s-offset-4 col-xs-2 col-xs-offset-4' align='center' style=\"min-width:150px;\">" +
        "<img src='/public/images/loader.gif'> Loading..." +
        "</div>" +
        "</div>";

    $(divId).html(html);
}


global.adjustNavbar = function ()
{
    if ($('#main-nav').exists())
    {
        var navWidth = $('#main-nav').outerWidth();

        var container = $('#main-nav').parent().parent().innerWidth();

        // var brand = $('#main-nav').parent().parent().find('.navbar-brand').outerWidth();

        // console.log($('#main-nav').parent().parent().attr('class'));

        // console.log('navwidth: ' + navWidth);
        // console.log('Brand width: ' + brand);
        // console.log('container Width: ' + container);

        var diff = container - (navWidth );

        $('#q').width(diff - 150);

    }

}

