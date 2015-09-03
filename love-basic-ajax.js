$fixJq = jQuery.noConflict();
$fixJq( document ).on( 'click', '.love-button', function() {
	var user_id = $fixJq(this).data('id');
	var matchid = $fixJq(this).data('matchid');
	$fixJq.ajax({
		url : postlove.ajax_url,
		type : 'post',
		data : {
			action : 'post_love_add_love',
			user_id : user_id,
			matchid : matchid
		},
		success : function( response ) {
			$fixJq('#love-count').html( response );
			alert( response );
		}
	});

	return false;
})