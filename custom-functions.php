<?php
/**
 * Edit Profile Page functions
 *
 * @package ihs-form
 */

require_once 'inc/create-taxonomy.php';


// Get Current User Name
//if ( wp_get_current_user()->display_name ) {
//	$user_name = wp_get_current_user()->display_name;
//}
$user_name = 'Imran Sayed';

if ( ! function_exists( 'ihs_profile_form_enqueue_scripts' ) ) {
	function ihs_profile_form_enqueue_scripts() {
		wp_enqueue_style( 'ihs_profile_form_style', plugins_url( 'ihs-form' ) . '/style.css' );
		wp_enqueue_script( 'ihs_profile_alert_js', plugins_url( 'ihs-form' ) . '/js/alert.js', array( 'jquery' ), '', true  );
		wp_enqueue_script( 'ihs_profile_form_js', plugins_url( 'ihs-form' ) . '/js/edit-profile.js', array( 'jquery' ), '', true  );
		wp_localize_script( 'ihs_profile_form_js', 'catdata', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ), // admin_url( 'admin-ajax.php' ) returns the url till admin-ajax.php file of wordpress.
			'ajax_nonce' => wp_create_nonce('ihs_edit_profile_nonce'),  // Create nonce and send it to js file in postdata.ajax_nonce.
		) );
	}

	add_action( 'wp_enqueue_scripts', 'ihs_profile_form_enqueue_scripts' );
}

if ( ! function_exists( 'ihs_display_sub_cat_on_cat_change' ) ) {
	function ihs_display_sub_cat_on_cat_change() {
		// if nonce verification fails die.
		if ( ! wp_verify_nonce( $_POST['security'], 'ihs_edit_profile_nonce' ) ) {
			wp_die();
		}

		$cat_id = $_POST['catId'];
		$args_sub_cat = array(
			'taxonomy'          => 'artistcategory',
			'hide_empty'        => false,
			'parent'            => $cat_id,
		);

		$sub_cat_obj = get_terms( $args_sub_cat );

		// Get existing cat name and sub cat name.
		$user_id = get_current_user_id();
		$post_id = ( get_user_meta( $user_id, 'pfa_custom_post', true ) ) ? get_user_meta( $user_id, 'pfa_custom_post', true ) : '';
		$existing_cat_name = '';
		$existing_sub_cat_name = '';
		if ( $post_id ) {
			$existing_cat = wp_get_post_terms( $post_id, 'artistcategory', array( 'orderby' => 'name', 'parent' => 0 ) );
			$existing_cat_id = ( ! empty( $existing_cat ) ) ? $existing_cat[0]->term_id : '';
			$existing_sub_cat = wp_get_post_terms( $post_id, 'artistcategory', array( 'orderby' => 'name', 'parent' => $existing_cat_id ) );
			$existing_cat_name = ( ! empty( $existing_cat ) ) ? $existing_cat[0]->name : '';
			$existing_sub_cat_name = ( ! empty( $existing_sub_cat ) ) ? $existing_sub_cat[0]->name : '';
		}

		wp_send_json_success( array(
			'my_data' => $sub_cat_obj,  // Always pass your data here that you want to access in js file.
			'existing_sub_cat_name' => $existing_sub_cat_name,
			'data_recieved_from_js' => $_POST,  // $_POST will contain the array of data object passed in js file second parameter. like action,name etc
		) );
	}
	add_action( 'wp_ajax_ihs_cat_change', 'ihs_display_sub_cat_on_cat_change' );
	add_action( 'wp_ajax_nopriv_ihs_cat_change', 'ihs_display_sub_cat_on_cat_change' );
}

