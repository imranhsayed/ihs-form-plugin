<?php
/**
 * IHS Form Main File.
 *
 * @package IHS Form
 */

/*
Plugin Name:  IHS Form
Plugin URI:   http://imransayed.com/ihs-form
Description:  This plugin detects your location and makes certain functions available which will return the City, State, Country and Address.
Version:      1.0.0
Author:       Imran Sayed
Author URI:   https://profiles.wordpress.org/gsayed786
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  ihs-form
Domain Path:  /languages
*/

/* Include the Custom functions file */
require_once 'custom-functions.php';

/**
 * Register the [ihs_profile_form] shortcode
 *
 * @return {string} div element that will contain the address
 */
function ihs_profile_form_func() {
	require_once 'inc/profile-form.php';
}
add_shortcode( 'ihs_profile_form', 'ihs_profile_form_func' );
