<?php
/*
Plugin Name: AppPush add-on for BPFollow
Plugin URI: http://apppresser.com
Description: Integrates push notifications from AppPush when using BP Follow.  A push notification will occur when someone follows a user.
Text Domain: apppush-bpfollow
Domain Path: /languages
Version: 1.0.0
Author: AppPresser Team
Author URI: http://apppresser.com
License: MIT
*/

class AppPush_BPFollow {

	public function __construct() { }

	public function hooks() {
		add_action('bp_follow_start_following', array( $this, 'appp_bpfollow_push_notification' ) );
	}

	public function appp_bpfollow_push_notification( $follow ) {
		
		$leader = new WP_User($follow->leader_id);
		$follower = new WP_User($follow->follower_id);
	
		$appp_push = new AppPresser_Notifications_Update();
	
		// push notification
		$devices = $appp_push->get_devices_by_user_id( array($leader->ID) );

		if( !empty( $devices ) ) {
			$send_date = 'now';
			$content   = sprintf( __( 'Hey %s, %s just started following you', 'apppush-bpfollow' ), $leader->user_login, $follower->user_login);
			$badges = 0;
			$data = array();
			$custom_url = '';
			$custom_page = '';
			$segment = '';
			$target = '';
		
			$response = $appp_push->notification_send( $send_date, $content, $badges, $devices, $data, $custom_url, $custom_page, $segment, $target );
		}
	
	}
}

$appPush_BPFollow = new AppPush_BPFollow();
$appPush_BPFollow->hooks();
