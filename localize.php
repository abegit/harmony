add_action( 'wp_enqueue_scripts', 'ajax_test_enqueue_scripts' );
function ajax_test_enqueue_scripts() {
	if( is_single() ) {
		wp_enqueue_style( 'love', plugins_url( '/love.css', __FILE__ ) );
	}

	wp_enqueue_script( 'love', plugins_url( '/love.js', __FILE__ ), array('jquery'), '1.0', true );

	wp_localize_script( 'love', 'postlove', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));

}
