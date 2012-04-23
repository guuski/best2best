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

/*
$(function() {
	$( "#datepicker" ).datepicker();
});
*/

jQuery(document).ready(function() { 
	jQuery( "#datepicker" ).datepicker(); 
});                     