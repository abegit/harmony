$fixJq = jQuery.noConflict();
$fixJq( document ).on( 'click', '.love-button', function() {
	var post_id = $fixJq(this).data('id');
	$fixJq.ajax({
		url : postlove.ajax_url,
		type : 'post',
		data : {
			action : 'post_love_add_love',
			post_id : post_id
		},
		success : function( response ) {
			$fixJq('#love-count').html( response );
		}
	});

	return false;
})