// overrides the default eventClick function for the calendar
function eventClick(calEvent, jsEvent, view) {
    var date_format = 'MM/DD/YY h:mm a';
    var start = moment(calEvent.start).format(date_format);
    var end = moment(calEvent.end).format(date_format);

    $('#modal-name').html(calEvent.title);
    $('#modal-start').html(start);
    $('#modal-end').html(end);
    $('#modal-comments').html(calEvent.comments);

    var view_link = $('#modal-view-request');
    var base_url = view_link.attr('href').match(/.+\/view/);
    view_link.attr('href', base_url + '/' + calEvent.id);

    if (calEvent.pending === true && display_approve === 1) {
        var approve_form = $('#modal-buttons').find("form");
        var action = approve_form.attr("action").match(/.+\/approve/);
        approve_form.attr("action", action + '/' + calEvent.id + '/' + calEvent.option);
        $('#modal-approve-row').show();
    }
    else {
        $('#modal-approve-row').hide();
    }

    showModal();
}