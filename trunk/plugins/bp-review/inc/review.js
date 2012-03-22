jQuery(document).ready(function(){
  
var jq=jQuery;

/*
jq('#new-review').focus( function(){
		jq('#new-review-options').animate({height:'40px'}); //30
		jq('form#review-form textarea').animate({height:'200px'}); //100
		//jq('form#review-form textarea').animate({width:'750px'});		
		jq('#review-submit').prop('disabled', false);
	});
*/
	jq('input#review-submit').click( function() {
		var button = jq(this);
		var form = button.parent().parent().parent().parent();

		form.children().each( function() {
			if ( jq.nodeName(this, 'textarea') || jq.nodeName(this, 'input') )
				jq(this).prop( 'disabled', true );
		});

		jq('div.error').remove();
		button.addClass('loading');
		button.prop('disabled', true);

				
		var content = jq('textarea#new-review').val();

		

		jq.post( ajaxurl, {
			 action: 'new_review',
			'cookie': encodeURIComponent(document.cookie),
			'_wpnonce_new_review': jq('input#_wpnonce_new_review').val(),
			'content': content
			
		},
		function(response) {

			form.children().each( function() {
				if ( jq.nodeName(this, 'textarea') || jq.nodeName(this, 'input') ) {
					jq(this).prop( 'disabled', false );
				}
			});
			
			if ( response[0] + response[1] == '-1' ) {
				form.prepend( response.substr( 2, response.length ) );
				jq( 'form#' + form.attr('id') + ' div.error').hide().fadeIn( 200 );
			} else {
				if ( 0 == jq('ul#review-list').length ) {
					jq('div.error').slideUp(100).remove();
					jq('div#message').slideUp(100).remove();
					jq('div#item-body').append( '<ul id="review-list" class="reviews-list item-list">' );
				}

				jq('ul#review-list').prepend(response);
				jq('ul#review-list li:first').addClass('new-update');

				

				jq('li.new-update').hide().slideDown( 300 );
				jq('li.new-update').removeClass( 'new-update' );
				jq('textarea#new-review').val('');
			}

			jq('#new-review-options').animate({height:'0px'});
			jq('form#review-form textarea').animate({height:'20px'});
			jq('#review-submit').prop('disabled', true).removeClass('loading');
		});

		return false;
	});


jq('a.review-update').live('click',function(){

var url=jq(this).attr('href');
var $li=jq(this).parent().parent().parent();
var nonce=get_var_in_url(url,'_wpnonce');
var review_id=get_var_in_url(url,'tid');
var action=get_var_in_url(url,'action');
jq('div.error').remove();
jq('div.success').remove();
jq('div.updated').remove();

jq.post(ajaxurl,{action:'update_review_status',
        'cookie': encodeURIComponent(document.cookie),
        '_wpnonce':nonce,
        'id':review_id,
        'current_action':action

        },
        function (response){
          
          if(response[0] + response[1] == '-1' ){

              response=response.substr( 2, response.length );//remove the -1 from begining
                $li.before(response); 
              
          }
          else{
               $li.before(response);
               $li.remove();
          }
       
              
          
        }
    

);

  return false;  
})

jq('a.review-delete').live('click',function(){

var url=jq(this).attr('href');
var $li=jq(this).parent().parent().parent();
console.log(url);
var nonce=get_var_in_url(url,'_wpnonce');
var review_id=get_var_in_url(url,'tid');

jq('div.error').remove();
jq('div.success').remove();
jq('div.updated').remove();

jq.post(ajaxurl,{action:'delete_review',
        'cookie': encodeURIComponent(document.cookie),
        '_wpnonce':nonce,
        'tid':review_id
         },
        function (response){            
          
          if(response[0] + response[1] == '-1' )
		  {              
              response=response.substr( 2, response.length );//remove the -1 from begining
                $li.before(response);               
          }
          else
		  {
               $li.before(response);
               $li.remove();
          }
       
              
          
        }
    

);

  return false;  
})

function get_var_in_url(url,name)
{
    var urla=url.split('?');
    var qvars=urla[1].split('&');

    for(var i=0;i<qvars.length;i++)
	{
        var qv=qvars[i].split('=');
        if(qv[0]==name)
            return qv[1];
		}
		return '';
}

});


