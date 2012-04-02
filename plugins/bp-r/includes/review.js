jQuery(document).ready(function(){
  
var jq=jQuery;

jq('#review-content').focus( function(){
		//jq('#new-review-options').animate({height:'40px'});
		jq('#review-content').animate({height:'200px'});
		//jq('#review-submit').prop('disabled', false);
	});

});


