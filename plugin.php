<?php 

/**
 * Plugin Name: Ajax Test
 * Plugin URI: http://danielpataki.com
 * Description: This is a plugin that allows us to test Ajax functionality in WordPress
 * Version: 1.0.0
 * Author: Daniel Pataki
 * Author URI: http://danielpataki.com
 * License: GPL2
 */

add_action( 'wp_enqueue_scripts', 'post_love_assets' );
function post_love_assets() {
	if( is_single() ) {
		wp_enqueue_style( 'love', plugins_url( '/love.css', __FILE__ ) );
	}
}

add_action( 'wp_enqueue_scripts', 'ajax_test_enqueue_scripts' );
function ajax_test_enqueue_scripts() {
	if( is_single() ) {
		wp_enqueue_style( 'love', plugins_url( '/style.css', __FILE__ ) );
	}
	wp_enqueue_script( 'love', plugins_url( '/love-basic-ajax.js', __FILE__ ), '1.0', true );
	wp_localize_script( 'love', 'postlove', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));
}


add_action( 'wp_ajax_nopriv_post_love_add_love', 'post_love_add_love' );
add_action( 'wp_ajax_post_love_add_love', 'post_love_add_love' );

function post_love_add_love() {
	$love = bp_get_profile_field_data( 'field=Name&user_id='.$_REQUEST['user_id'] );
	$love .= ' '. $_REQUEST['matchid'];
	
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
		echo $love;
		die();
	}
	else {
		wp_redirect( get_permalink( $_REQUEST['user_id'] ) );
		exit();
	}
}
add_filter( 'the_content', 'post_love_display', 99 );
function post_love_display( $content ) {
	$love_text = '';

	if ( is_single() ) {
		
		$love = bp_get_profile_field_data( 'field=Name&user_id='.bp_loggedin_user_id() );
		$love = ( empty( $love ) ) ? 0 : $love;

		$love_text = '<p class="love-received"><a class="love-button" href="' . admin_url( 'admin-ajax.php?action=post_love_add_love&user_id=' . bp_loggedin_user_id(). '&matchid=' . bp_loggedin_user_id() ) . '" data-id="' . bp_loggedin_user_id() . '" data-matchid="' . bp_loggedin_user_id() . '">give love</a><span id="love-count">' . $love . '</span></p>'; 
	
	}

	return $content . $love_text;

}
?>