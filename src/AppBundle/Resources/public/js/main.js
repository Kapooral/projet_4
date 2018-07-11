jQuery (function($) {

	$.datepicker.setDefaults($.datepicker.regional["fr"]);
    $('.picker').datepicker({
    	minDate: 0,
    	beforeShowDay: function(date) {
            
            var day = date.getDate();
            var month = date.getMonth()+1;
    		if (date.getDay() == 2)
            {
    			return [false, ""];	
    		}
            else if(month == 5 && day == 1)
            {
                return [false, ""];
            }
            else if(month == 11 && day == 1)
            {
                return [false, ""];
            }
            else if(month == 12 && day == 25)
            {
                return [false, ""];
            }
            else 
            {
    			return [true, ""];
    		}
    	}
    });
});