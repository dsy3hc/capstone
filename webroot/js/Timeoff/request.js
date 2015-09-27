$(document).ready(function() {
    $('#options-table tbody').sortable({
        helper: dragHelper,
        update: update,
        start: function(e, ui) {
            $(this).attr('data-previndex', ui.item.index());
        }
    }).disableSelection();

    $('#submit-btn').click(function() {
        var events = $('#calendar').fullCalendar('clientEvents');
        for (var i = 0; i < events.length; i++) {
            $("input[name='start_date_" + (i+1) + "']").val(events[i].start.format());
            $("input[name='end_date_" + (i+1) + "']").val(events[i].end.format());
        }
    });

    $('#start-all-day').change(function() {
        $('#start-time-options').toggleClass('hidden', $(this).prop('checked'));
    });

    $('#end-all-day').change(function() {
        $('#end-time-options').toggleClass('hidden', $(this).prop('checked'));
    });
});

var dragHelper = function(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
        $(this).attr('background-color', $(this).attr('background-color'));
    });
    return ui;
};

var update = function(event, ui) {
    var newIndex = ui.item.index();
    var oldIndex = $(this).attr('data-previndex');
    $(this).removeAttr('data-previndex');
    console.log("From " + oldIndex + " to " + newIndex);

    var events = $('#calendar').fullCalendar('clientEvents');
    var tmp = events[oldIndex];
    // delete the item that was dragged
    events.splice(oldIndex, 1);
    // insert the item back into the array at its new position
    events.splice(newIndex, 0, tmp);

    // update the ids for the events
    for (var i = 0; i < events.length; i++) {
        var newId = i + 1;
        events[i].id = newId;
        events[i].title = "Option " + newId;
        events[i].className = "event option" + newId;
        $('#calendar').fullCalendar('updateEvent', events[i]);
    }

    refreshOptionsTable();
};

var options = 1;

var allowSelection = true;

// overrides
var selectFunction = function(start, end) {
    if (options > 3) {
        alert("You can't add any more options");
        return;
    }

    var event = {
        id: options,
        title: "Option " + options,
        start: start,
        end: end,
        className: "event option" + options
    };

    $('#calendar').fullCalendar('renderEvent', event, true);
    var calEvent = getCalEvent(event);

    refreshOptionsTable();

    options++;
    loadModal(calEvent);
    showModal();
};

// populates table with events from the calendar
function refreshOptionsTable() {
    var events = $('#calendar').fullCalendar('clientEvents');
    events.sort(eventSorter);
    $('#options-table > tbody').empty();
    for (var i = 0; i < events.length; i++) {
        $('#options-table > tbody').append('<tr class="option' + (i+1) + '">' +
        '<td>' + events[i].start.format('MM/DD/YYYY h:mm a') + '</td>' +
        '<td>' + events[i].end.format('MM/DD/YYYY h:mm a') + '</td>' +
        '</tr>');
    }

    // hide table if there aren't any options
    $('#table-container').toggleClass('hidden', events.length === 0);
}

// initializes the modal with an event object in the given mode
function loadModal(event) {
    $('#modal-save').off().click(function() {
        updateEvent(event);
        hideModal();
    });
    $('#modal-delete').off().click(function() {
        removeEvent(event);
        hideModal();
    });

    console.log("start: " + event.start.format('MM/DD/YYYY h:mm a'));
    console.log("end: " + event.end.format('MM/DD/YYYY h:mm a'));

    var startsAtMidnight = (event.start.format('HH:mm:ss') === '00:00:00');
    $('#start-all-day').prop('checked', startsAtMidnight);
    $('#start-time-options').toggleClass('hidden', startsAtMidnight);
    $('#start-day-picker').datepicker('setDate', new Date(event.start.format('MM/DD/YYYY')));


    var endsAtMidnight = (event.end.format('HH:mm:ss') === '00:00:00');
    $('#end-all-day').prop('checked', endsAtMidnight);
    $('#end-time-options').toggleClass('hidden', endsAtMidnight);
    if (endsAtMidnight) {
        // When an event ends on an 'all day', the end time is technically 12am
        // the following day. When displaying the end date, we need to subtract
        // 1 day so that the user isn't confused.

        // we need to make a copy of the end time so that we don't subtract a day
        // from the actual time.
        var tmp = moment(event.end);
        tmp.subtract(1, 'day');
        $('#end-day-picker').datepicker('setDate', new Date(tmp.format('MM/DD/YYYY')));
    } else {
        $('#end-day-picker').datepicker('setDate', new Date(event.end.format('MM/DD/YYYY')));
    }

    $("#start-hour").val(event.start.format('hh'));
    $("#start-min").val(event.start.format('mm'));
    $("#start-meridian").val(event.start.format('a'));

    $("#end-hour").val(event.end.format('hh'));
    $("#end-min").val(event.end.format('mm'));
    $("#end-meridian").val(event.end.format('a'));
}

function updateEvent(event) {
    // rather than update the current event, we have to delete the current
    // one and add a new (updated) event

    // make a copy of the event
    var calEvent = {
        id: event.id,
        title: event.title,
        className: event.className
    };

    calEvent.start = getTime('start');
    calEvent.end = getTime('end');

    if ($('#start-all-day').prop('checked')) {
        calEvent.start.hour(0).minute(0).second(0).millisecond(0);
        calEvent.allDay = true;
    }

    if ($('#end-all-day').prop('checked')) {
        calEvent.end.add(1, 'day').hour(0).minute(0).second(0).millisecond(0);
    }

    $('#calendar').fullCalendar('removeEvents', event.id);
    $('#calendar').fullCalendar('renderEvent', calEvent);

    refreshOptionsTable();
}

function removeEvent(event) {
    $('#calendar').fullCalendar('removeEvents', event.id);
    options--;
    var events = $('#calendar').fullCalendar('clientEvents');
    events.sort(eventSorter);

    for (var i = 0; i < events.length; i++) {
        var newId = i + 1;
        events[i].id = newId;
        events[i].title = "Option " + newId;
        events[i].className = "event option" + newId;
        $('#calendar').fullCalendar('updateEvent', events[i]);
    }
    refreshOptionsTable();
}

// overrides the default eventClick function for the calendar
function eventClick(calEvent, jsEvent, view) {
    loadModal(calEvent);
    showModal();
}

function getCalEvent(event) {
    return $('#calendar').fullCalendar('clientEvents', event.id)[0];
}

function eventSorter(a, b) {
    if (a.id < b.id)
        return -1;
    if (a.id > b.id)
        return 1;
    return 0;
}

function getTime(input) {
    var time = null;
    if (input === 'start') {
        time = {
            day: $('#start-day-picker').datepicker('getDate').toDateString(),
            hours: $("#start-hour").val(),
            minutes: $("#start-min").val(),
            meridian: $("#start-meridian").val()
        }
    }
    else if (input === 'end') {
        time = {
            day: $('#end-day-picker').datepicker('getDate').toDateString(),
            hours: $("#end-hour").val(),
            minutes: $("#end-min").val(),
            meridian: $("#end-meridian").val()
        }
    }

    var date = new Date(time.day + " " + time.hours + ":" + time.minutes + " " + time.meridian);

    return moment(date.toISOString());
}