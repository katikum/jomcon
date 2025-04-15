$(function () {
    $("#contactForm").submit(function() {
        var phone = $("input#phone").val();
        if (phone.length != 10) {
            $("p#phonevalid").html("Phone must have 10 digits!");
            return false;
        } else {
            if (phone.charAt(0) != "0" || (phone.charAt(1) != "1" && phone.charAt(1) != "7")) {
                $("p#phonevalid").html("Phone must start with \"07\" or \"01\"!");
                return false;
            }            
            for (var i=2; i<10; i++) {            
                if (isNaN(parseInt(phone.charAt(i)))) {
                    $("p#phonevalid").html("Phone must be numbers only!");
                    return false;
                }
            }
        }
        return true;
    });
});
