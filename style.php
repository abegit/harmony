add_action( 'wp_enqueue_scripts', 'post_love_assets' );
function post_love_assets() {
	if( is_single() ) {
		wp_enqueue_style( 'love', plugins_url( '/love.css', __FILE__ ) );
	}

