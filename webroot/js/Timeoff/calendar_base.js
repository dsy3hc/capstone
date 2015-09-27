// calendar is not selectable unless previously overridden
if (typeof allowSelection === 'undefined') {
    allowSelection = false;
}

//
if (typeof selectFunction === 'undefined') {
    selectFunction = null;
}

if (typeof eventClick === 'undefined') {
    eventClick = null;
}

if (typeof loading === 'undefined') {
    loading = null;
}

$(document).ready(function() {
    // default event object that all other event objects extend
    var defaultEventSource = {
        error: function() {
            alert('there was an error while fetching events!');
        },
        className: 'event'
    };
    var calData = $('#calendar-data');
    var eventSources = $.map(calData.data("calendar-event-sources"), function(eventSource) {
        return $.extend({}, defaultEventSource, eventSource);
    });

    display_approve = calData.data("calendar-display-approve");

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next',
            center: 'title',
            right: 'month, agendaWeek, agendaDay'
        },
        minTime: "05:00:00",
        height: "auto",
        eventClick: eventClick,
        eventSources: eventSources,
        selectable: allowSelection,
        select: selectFunction,
        loading: loading
    });

    $('#modal-close').click(function() {
        hideModal();
    });

});

function hideModal() {
    $('#modal').hide();
}

function showModal() {
    $('#modal').show();
    scrollToModal();
}

function scrollToModal() {
    $('html, body').animate({
        scrollTop: $("#calendar").offset().top
    }, 750);
}

// closes the modal when user clicks outside of it
// http://stackoverflow.com/a/7385673
$(document).mouseup(function (e) {
    var modal = $("#modal");
    var picker = $('#ui-datepicker-div');

    if (!modal.is(e.target)                     // didn't click on modal AND
        && modal.has(e.target).length === 0     // didn't click on child of modal AND
        && !picker.is(e.target)                 // didn't click on datepicker AND
        && picker.has(e.target).length === 0    // didn't click on child of datepicker AND
        && modal.is(':visible'))                // modal is visible
    {
        hideModal();
    }
});
