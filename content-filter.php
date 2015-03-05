add_filter( 'the_content', 'post_love_display', 99 );
function post_love_display( $content ) {
	$love_text = '';

	if ( is_single() ) {
		
		$love = get_post_meta( get_the_ID(), 'post_love', true );
		$love = ( empty( $love ) ) ? 0 : $love;

		$love_text = '<p class="love-received"><a class="love-button" href="" data-id="' . get_the_ID() . '">give love</a><span id="love-count">' . $love . '</span></p>'; 
	
	}

	return $content . $love_text;

}