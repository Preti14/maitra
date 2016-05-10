<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
function is_logged_in() {
    // Get current CodeIgniter instance
    $CI =& get_instance();
    // We need to use $CI->session instead of $this->session
    $user = $CI->session->all_userdata();
	$user_session=$user["logged_in"];
	 $referer = base64_encode(current_url());
    if (!isset($user_session)) { redirect(site_url().''); } else { return true; }
}
*/
