$(document).ready(function() {
    $("#medical").click(function() {
	if($("#one_way").is(":checked") == false) {
            $("#willcall").toggleClass('hidden');
            if($("#willcall-box").is(":checked") == true && $("#willcall").is(":visible") == true) {
            	$("#returnTime").addClass('hidden');
            } else {
            	$("#returnTime").removeClass('hidden');
            }
	}
    });

    $("#willcall-box").click(function() {
        if($("#willcall-box").is(":checked") == true) {
            $("#returnTime").addClass('hidden');
            $("#willcall-msg").removeClass('hidden');
        } else {
            $("#returnTime").removeClass('hidden');
            $("#willcall-msg").addClass('hidden');
        }
    });
});
