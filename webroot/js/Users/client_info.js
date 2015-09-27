var client_info;

$(document).ready(function() {
    clientInfo =  $('#clientInfo');
    var select = $('#role-id');

    toggleClientInfo(select);

    select.change(function() {
        toggleClientInfo($(this));
    });

    $( '[title="date"]' ).datepicker({
        altFormat: "yy-mm-dd",
        altField: "[name='expiration date']"
    });
});

function toggleClientInfo(select) {
    if (select.val() == 2) {
        showClientInfo();
    }
    else {
        hideClientInfo();
    }
}

function hideClientInfo() {
    clientInfo.addClass('hidden');
    $('#clientid').val('');
    $('#cat-disability-num').val('');
    $('#expiration-date').val('');
}

function showClientInfo() {
    clientInfo.removeClass('hidden');
}
