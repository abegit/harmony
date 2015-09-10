$fixJq = jQuery.noConflict();
$fixJq( document ).on( 'click', '.wink-button', function() {
	var user_id = $fixJq(this).data('id');
	var matchid = $fixJq(this).data('matchid');
	Parse.Push.send({
			  where: new Parse.Query(Parse.Installation),
			  data: {
			    alert: matchid
			  }
			}, {
			  success: function() {
			    alert( 'push successful' );
			  },
			  error: function(error) {
			    // Handle error
			  }
	});
	$fixJq.ajax({
		url : postlove.ajax_url,
		type : 'post',
		data : {
			action : 'post_love_add_love',
			user_id : user_id,
			matchid : matchid
		},
		success : function( response ) {

			$inventedthis = '#love-count-'+response;
			$fixJq($inventedthis).addClass( 'ok' );
			$fixJq($inventedthis+' span').html( 'ed' );
			// alert( response );
		}
	});

	return false;
})