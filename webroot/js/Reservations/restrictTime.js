
var pickupTime = "pick up time";
var returnTime = "return_time";

$(document).ready(function() {
    var pickupMeridian =  $('[name="' + pickupTime + '[meridian]"]');
    var returnMeridian =  $('[name="' + returnTime + '[meridian]"]');
    restrictTime(pickupTime, pickupMeridian.val());
    restrictTime(returnTime, returnMeridian.val());

    pickupMeridian.change(function() {
        restrictTime(pickupTime, pickupMeridian.val());
    });

    returnMeridian.change(function() {
        restrictTime(returnTime, returnMeridian.val());
    });
});

function restrictTime(label, meridian) {
    if(meridian == 'am') {
        morningHours(label);
    } else {
        nightHours(label);
    }
}

function morningHours(label) {
    var optionsAsString = "";
    var hours = (new Date()).getHours();
    for(var i = 4; i < 11; i++) {
        var h = "" + (i+1);
        if(h < 10) {
            h = "0" + h;
        }
        optionsAsString += "<option value='" + h + "'";
        if(hours > 12 && i == 0) {
            optionsAsString += " selected ";
        } else if( (i+1) == hours ) {
            optionsAsString += " selected ";
        }
        optionsAsString += ">" + (i+1) + "</option>";
    }
    $('[name="' + label + '[hour]"]').find('option').remove().end().append(optionsAsString);
}

function nightHours(label) {
    var hours = (new Date()).getHours();
    var optionsAsString = "";
    var i = 11;
    var h = 12;
    optionsAsString += "<option value='" + h + "'";
    if(hours < 12 && i == 11) {
        optionsAsString += " selected ";
    } else if( (i+1) == (hours-12) ) {
        optionsAsString += " selected ";
    }
    optionsAsString += ">" + (i+1) + "</option>";
    for(var i = 0; i < 10; i++) {
        var h = "" + (i+1);
        if(h < 10) {
            h = "0" + h;
        }
        optionsAsString += "<option value='" + h + "'";
        if( (i+1) == (hours-12) ) {
            optionsAsString += " selected ";
        }
        optionsAsString += ">" + (i+1) + "</option>";
    }
    $('[name="' + label + '[hour]"]').find('option').remove().end().append(optionsAsString);
}
