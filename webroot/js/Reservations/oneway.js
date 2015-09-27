$(document).ready(function() {
    $("#one_way").click(function() {
        if($("#one_way").is(":checked") == true) {
            $("#returnTime").toggleClass('hidden', true);
	    $("#willcall").toggleClass('hidden', true);
	    $("#returnTime_label").toggleClass('hidden', true);
        } else {
	    $("#returnTime_label").toggleClass('hidden', false);
	    if($("#medical").is(":checked") == true) {
	        $("#willcall").toggleClass('hidden', false);
	    }
    	    if($("#willcall-box").is(":checked") == false) {
		$("#returnTime").toggleClass('hidden', false);
	    }
	}
    });
});
