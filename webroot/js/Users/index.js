$('document').ready(function () {
    $('#filter-select').change(function() {
        var params = {
            filter: $("option:selected", this).val()
        };
        window.location.search = jQuery.param(params);
    });
});
