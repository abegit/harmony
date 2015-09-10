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
	if( bp_is_members_directory() || bp_is_user_profile()) {
		wp_enqueue_style('love', plugins_url( '/love.css', __FILE__ ) );
	}
}

add_action( 'wp_enqueue_scripts', 'ajax_test_enqueue_scripts' );
function ajax_test_enqueue_scripts() {
	if( bp_is_members_directory() || bp_is_user_profile()) {
		wp_enqueue_style('love', plugins_url( '/style.css', __FILE__ ) );
	}
	wp_enqueue_script('love', plugins_url( '/love-basic-ajax.js', __FILE__ ), '1.0', true );
	wp_localize_script('love', 'postlove', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));
	wp_enqueue_script('parse', '//www.parsecdn.com/js/parse-1.6.0.min.js', '1.0', true);
	wp_enqueue_script('veri', plugins_url( '/veri.js', __FILE__ ), '1.0', true );

}


function bp_send_harmony_message($user1, $user2) {
global $bp;
//check_admin_referer(message_check”); // adjust if needed
$sender_id = $user1; // moderator id ?
$recip_id = $user2; // denied image user id ?
$nameofreciept = bp_get_profile_field_data( 'field=Name&user_id='.$user2 );
	if ( $thread_id = messages_new_message( array('sender_id' => $sender_id, 'subject' =>'You have a match!', 'content' => 'Let me introduce you to '.$nameofreciept , 'recipients' => $recip_id ) ) ) {
		// bp_core_add_message( __( 'Image Denied Message was sent.', 'buddypress' ) );
	} else {
		// bp_core_add_message( __( 'There was an error sending that Private Message.', 'buddypress' ), 'error' );
	}

}

add_action( 'wp_ajax_nopriv_post_love_add_love', 'post_love_add_love' );
add_action( 'wp_ajax_post_love_add_love', 'post_love_add_love' );

function post_love_add_love() {
	$love = bp_get_profile_field_data( 'field=handshake&user_id='.$_REQUEST['user_id'] );
	$otherlove = bp_get_profile_field_data( 'field=handshake&user_id='.$_REQUEST['matchid'] );

	$lovemaking = explode(',', $otherlove);

	$love .= ','. $_REQUEST['matchid'];
	xprofile_set_field_data( 'handshake', $_REQUEST['user_id'], $love );
	
	if ( in_array($_REQUEST['user_id'], $lovemaking) ) {
		bp_send_harmony_message($_REQUEST['user_id'], $_REQUEST['matchid']);
	}
	
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
		echo $_REQUEST['matchid'];
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
			$lovemaking = explode(',', $love);

			$love = ( empty( $love ) ) ? 0 : $love;
			if ( in_array(bp_displayed_user_id(), $lovemaking) ) {
				$past_wink_class = ' ok';
				$past_wink = 'ed';
			} else {
				$past_wink_class = '';
				$past_wink = '';
			}
			echo '<div class="generic-button'.$past_wink_class.'" id="love-count-'.bp_displayed_user_id().'" >';
			echo '<a class="wink-button" href="' . admin_url( 'admin-ajax.php?action=post_love_add_love&user_id=' . bp_loggedin_user_id(). '&matchid=' . bp_displayed_user_id() ) . '" data-id="' . bp_loggedin_user_id() . '" data-matchid="' . bp_displayed_user_id() . '">Court<span>' . $past_wink . '</span></a>';
			

			echo '</div>'; 
		}

}
add_action('bp_member_header_actions',  'post_love_display');
?>