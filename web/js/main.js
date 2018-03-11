jQuery(function($){

	$.datepicker.setDefaults( $.datepicker.regional["fr"] );
    $('.picker').datepicker({
    	minDate: 0,
    	beforeShowDay: function(date){
    		if(date.getDay() == 2){
    			return [false, ""];	
    		}else{
    			return [true, ""];
    		}
    	}
    });
});