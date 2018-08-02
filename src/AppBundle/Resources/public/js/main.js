jQuery (function($) {

    var today = new Date();
    var invalideDates = ['5-1', '11-1', '12-25'];

	$.datepicker.setDefaults($.datepicker.regional["fr"]);
    $('.picker').datepicker({
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
    	},
        onSelect: function (date) {
            var selected = $('.picker').datepicker("getDate");

            if (selected > today)
            {
                $('#appbundle_order_wholeDay_0').attr('disabled', false);
                $('#appbundle_order_wholeDay_1').attr('checked', false);
            }
            else if (today.getHours() >= 14)
            {
                $('#appbundle_order_wholeDay_0').attr('disabled', true);
                $('#appbundle_order_wholeDay_1').attr('checked', true);
            }
        }
    });

    var currentDate = today.getMonth()+1 + '-' + today.getDate();

    if ($.inArray(currentDate, invalideDates) > -1 || today.getDay() == 2) 
    {
        today.setDate(today.getDate() + 1);
        var nextDay = today.getMonth()+1 + '-' + today.getDate();

        if ($.inArray(nextDay, invalideDates) > -1 || today.getDay() == 2)
        {
            $('.picker').datepicker("option", "minDate", 2);
        }
        else
        {
            $('.picker').datepicker("option", "minDate", 1);
        }
    }
    else
    {
        if (today.getHours() >= 14 && today.getHours() < 19)
        {
            $('.picker').datepicker("option", "minDate", 0);
            $('#appbundle_order_wholeDay_0').attr('disabled', true);
            $('#appbundle_order_wholeDay_1').attr('checked', true);
        }
        else if (today.getHours() >= 19 && today.getHours() <= 23)
        {
            $('.picker').datepicker("option", "minDate", 1);
        }
        else
        {
            $('.picker').datepicker("option", "minDate", 0);
        }
    }
});