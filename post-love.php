add_action( 'wp_ajax_nopriv_post_love_add_love', 'post_love_add_love' );
add_action( 'wp_ajax_post_love_add_love', 'post_love_add_love' );

function post_love_add_love() {
	$love = get_post_meta( $_POST['post_id'], 'post_love', true );
	$love++;
	update_post_meta( $_POST['post_id'], 'post_love', $love );
	echo $love;
	die();
}