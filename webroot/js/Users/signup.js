$(document).ready(function() {
    $('#ridden-before-false').change(function() {
        $('#sorry-message').removeClass('hidden');
    });

    $('#ridden-before-true').change(function() {
        $('#sorry-message').addClass('hidden');
    });
});
