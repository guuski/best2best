jQuery(document).ready(function(){
  
	var jq=jQuery;
	
	jq('#review-content').focus( function(){
		//jq('#new-review-options').animate({height:'40px'});
		jq('#review-content').animate({height:'200px'});
		//jq('#review-submit').prop('disabled', false);
	});

});


function vote(point, field) 
{
	var fieldname= jQuery(field).parent().parent()[0].id; 
	
	jQuery("input[name="+fieldname+"]").val(point);
	jQuery("#"+fieldname+" li.current-rating").css({'width':(point*25)+'px'});
	
	return false;					
}

jQuery(document).ready(function() { 
	jQuery( "#datepicker" ).datepicker(); 
});                     


/*
$(function() {
	$( "#datepicker" ).datepicker();
});
*/




/*
function Toggle() 
{
	$('.d').toggle();
}


function SelectOpt(rlName, value) 
{
	$('input[name="' + rlName + '"]').each(function () 
	{
		if ($(this).val() == value) 
		{
			$(this).attr('checked', true).parent().addClass('h');
		} 
		else 
		{
			$(this).attr('checked', false).parent().removeClass('h');
		}

	});

	$('#Selection').text("You selected : " + value);
}
*/