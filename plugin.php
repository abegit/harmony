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
	if( bp_is_members_directory() ) {
		wp_enqueue_style( 'love', plugins_url( '/love.css', __FILE__ ) );
	}
}

add_action( 'wp_enqueue_scripts', 'ajax_test_enqueue_scripts' );
function ajax_test_enqueue_scripts() {
	if( bp_is_members_directory() ) {
		wp_enqueue_style( 'love', plugins_url( '/style.css', __FILE__ ) );
	}
	wp_enqueue_script( 'love', plugins_url( '/love-basic-ajax.js', __FILE__ ), '1.0', true );
	wp_localize_script( 'love', 'postlove', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));
}


function bp_send_image_denied_message($user1, $user2) {
global $bp;
//check_admin_referer(message_check”); // adjust if needed
$sender_id = $user1; // moderator id ?
$recip_id = $user2; // denied image user id ?
	if ( $thread_id = messages_new_message( array('sender_id' => $sender_id, 'subject' =>'Hello Partner', 'content' => 'How do you do', 'recipients' => $recip_id ) ) ) {
		bp_core_add_message( __( 'Image Denied Message was sent.', 'buddypress' ) );
	} else {
		bp_core_add_message( __( 'There was an error sending that Private Message.', 'buddypress' ), 'error' );
	}

}

add_action( 'wp_ajax_nopriv_post_love_add_love', 'post_love_add_love' );
add_action( 'wp_ajax_post_love_add_love', 'post_love_add_love' );

function post_love_add_love() {
	$love = bp_get_profile_field_data( 'field=handshake&user_id='.$_REQUEST['user_id'] );
	$love .= ','. $_REQUEST['matchid'];
	xprofile_set_field_data( 'handshake', $_REQUEST['user_id'], $love );
	bp_send_image_denied_message($_REQUEST['user_id'], $_REQUEST['matchid']);
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
		echo $love;
		die();
	}
	else {
		wp_redirect( get_permalink( $_REQUEST['user_id'] ) );
		exit();
	}
}
function post_love_display( $content ) {
	$love_text = '';

		if (bp_loggedin_user_id()) {
			$love = bp_get_profile_field_data( 'field=handshake&user_id='.bp_loggedin_user_id() );
			$love = ( empty( $love ) ) ? 0 : $love;
			echo '<p class="love-received">';
			echo '<a class="love-button" href="' . admin_url( 'admin-ajax.php?action=post_love_add_love&user_id=' . bp_loggedin_user_id(). '&matchid=' . bp_get_member_user_id() ) . '" data-id="' . bp_loggedin_user_id() . '" data-matchid="' . bp_get_member_user_id() . '">'.bp_get_member_user_id().'</a>';
			echo '<span id="love-count">' . $love . '</span>';
			echo '</p>'; 
		}

}
add_action('bp_directory_members_item',  'post_love_display');
?>