<?php

if ( ! function_exists( 'pfa_insert_or_update_user_meta' ) ) {
	function pfa_insert_or_update_user_meta( $user_id, $meta_key, $meta_value ) {
		// Add City in the user meta field under the meta_key name ='city'
		$meta_key_not_exists = add_user_meta( $user_id, $meta_key, $meta_value, true );

		// If meta key already exists then just update the meta value for and return true
		if ( ! $meta_key_not_exists ) {
			update_user_meta( $user_id, $meta_key, $meta_value );
			return true;
		}
	}
}

if ( ! function_exists( 'pfa_insert_profile_city' ) ) {
	/**
	 * Inserts City data into database.
	 *
	 * @param $city
	 */
	function pfa_insert_profile_city( $city, $post_id, $user_id ) {

		if ( ! empty( $city ) ) {
			// Add the City in the Post Database first.
			$term_name = $city;
			$taxonomy = 'city';
			$term_obj = wp_insert_term( $term_name, $taxonomy );

			// If that city name already exists in the database.
			if ( is_wp_error( $term_obj ) ) {
				$name = $term_name;
				$term = get_term_by( 'name', $name, $taxonomy );
				$term_id = $term->term_id;
			} else {

				// If that city name does not exist in the database.
				$term_id = $term_obj['term_id'];
			}
			$term_array = array( $term_id );
			wp_set_post_terms( $post_id, $term_array, $taxonomy );
			pfa_insert_or_update_user_meta( $user_id, 'city', $city );
		}
	}
}

if ( ! function_exists( 'pfa_insert_profile_category' ) ) {
	/**
	 * Inserts City data into database.
	 *
	 * @param $city
	 */
	function pfa_insert_profile_cat_and_sub_cat( $cat_id, $sub_cat_id, $post_id ) {

		// Add the City in the Post Database first.
		$taxonomy = 'artistcategory';
		$term_array = array( $cat_id, $sub_cat_id );
		wp_set_post_terms( $post_id, $term_array, $taxonomy );
	}
}

if ( ! function_exists( 'pfa_set_new_post_for_user' ) ) {
	function pfa_set_new_post_for_user( $user_id, $user_display_name, $post_status ) {
		echo $user_display_name;
		$my_post = array(
			'post_author' => $user_id,
			'post_title'   => sanitize_text_field( $user_display_name ),
			'post_status'   => 'pending',
			'post_content'   => 'test',
			'post_name' => sanitize_text_field( $user_display_name ),
			'post_type' => 'artistprofile'
		);
		$post_id = wp_insert_post( $my_post ); // It will return the new inserted $post_id
		echo 'test';
		return $post_id;
	}
}

if ( ! function_exists( 'pfa_update_existing_post_for_user' ) ) {
	function pfa_update_existing_post_for_user( $post_id, $user_display_name, $post_status ) {
		$my_post = array(
			'ID'           => $post_id,
			'post_title'   => sanitize_text_field( $user_display_name ),
			'post_status'   => $post_status,
			'post_content'   => '',
			'post_name' => sanitize_text_field( $user_display_name )
		);
		wp_update_post( $my_post, false );
		# returns post id on success
		return $post_id;
	}
}

if ( ! function_exists( 'pfa_handle_profile_media' ) ) {
	function pfa_handle_profile_media( $post_id, $user_id ) {

		// Profile Pic
		if( ! empty( $_FILES ) && ! empty( $_POST['submit'] ) ){
			if ( ! empty( $_FILES['pfa-profile-pic'] ) ) {
				$inserted_file_obj = pfa_save_profile_media( 'pfa-profile-pic' );
				$file_path = ( ! empty( $inserted_file_obj ) ) ? $inserted_file_obj['file'] : '';

				// If any new file is inserted only then add the file path.
				if ( ! empty( $file_path ) ) {
					// Get existing attach post id for this user first, delete img from the uploads folder and the existing post attach from wp_posts and then save the new one.
//					unlink( $file_path);
					$profile_attach_post_id = $phone = ( get_user_meta( $user_id, 'pfa_prfl_img_post_id', true ) ) ? get_user_meta( $user_id, 'pfa_prfl_img_post_id', true ) : '';
					wp_delete_post( $profile_attach_post_id, true );
					pfa_update_post_with_attach( $file_path, $post_id, $user_id, 'profile-pic' );
				}
			}

			if ( ! empty( $_FILES['pfa-past-work-pic'] ) ) {
				$inserted_file_obj = pfa_save_profile_media( 'pfa-past-work-pic' );
				$file_path = ( ! empty( $inserted_file_obj ) ) ? $inserted_file_obj['file'] : '';

				// If any new file is inserted only then add the file path.
				if ( ! empty( $file_path ) ) {
					// Get existing attach post id for this user first, delete img from the uploads folder and the existing post attach from wp_posts and then save the new one.
//					unlink( $file_path );
					$wrk_attach_post_id = $phone = ( get_user_meta( $user_id, 'pfa_wrk_img_post_id', true ) ) ? get_user_meta( $user_id, 'pfa_wrk_img_post_id', true ) : '';
					wp_delete_post( $wrk_attach_post_id, true );
					pfa_update_post_with_attach( $file_path, $post_id, $user_id, 'work-attach' );
				}
			}
		}
		unset( $_FILES );
	}
}

if ( ! function_exists( 'pfa_move_attach_to_upload_dir' ) ) {
	function pfa_move_attach_to_upload_dir( $file_input_name ) {
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		$uploadedfile = $_FILES[ $file_input_name ];
		
		$upload_overrides = array( 'test_form' => false );

		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );


		if ( $movefile && ! isset( $movefile['error'] ) ) {
//			return "File is valid, and was successfully uploaded.\n";
			return $movefile;
		} else {
			/**
			 * Error generated by _wp_handle_upload()
			 * @see _wp_handle_upload() in wp-admin/includes/file.php
			 */
			return $movefile['error'];
		}
	}
}

if ( ! function_exists( 'pfa_save_profile_media' ) ) {
	function pfa_save_profile_media( $file_input_name ) {

		if ( empty( $_FILES[ $file_input_name ]['name'] ) ) {
			return;
		}
		
		$errors= array();
		$file_name = ( ! empty( $_FILES[ $file_input_name ]['name'] ) ) ? $_FILES[ $file_input_name ]['name'] : '';
		$file_size = ( ! empty( $_FILES[ $file_input_name ]['size'] ) ) ? $_FILES[ $file_input_name ]['size'] : '';
		$file_tmp = ( ! empty( $_FILES[ $file_input_name ]['tmp_name'] ) ) ? $_FILES[ $file_input_name ]['tmp_name'] : '';
		$file_type = ( ! empty( $_FILES[ $file_input_name ]['type'] ) ) ? $_FILES[ $file_input_name ]['type'] : '';
		$file_ext_arr = explode( '/', $file_type );
		$file_ext = ( ! empty( $file_ext_arr[1] ) ) ? $file_ext_arr[1] : '';

		$expensions= array( "jpeg", "jpg", "png", "pdf" );

		// Check if the file has the required format.
		if( false === in_array( $file_ext, $expensions ) ){
			$errors[]="extension not allowed, please choose a JPEG or PNG file.";
		}

		// Check if the file has the required size . Below unit is in Bytes ( 2097152 = 2 Mb )
		if( $file_size > 2097152 ){
			$errors[]='File size must be excately 2 MB';
		}

		// If there are no errors and the file is of the type and size we permit.
		if ( empty( $errors ) ) {
			$inserted_file_obj = pfa_move_attach_to_upload_dir( $file_input_name );
			return $inserted_file_obj;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'pfa_update_post_with_attach' ) ) {
	function pfa_update_post_with_attach( $filename, $post_id, $user_id, $pic_type ) {
		// $filename should be the path to a file in the upload directory.
//		$filename = '/path/to/uploads/2013/03/filename.jpg';

// The ID of the post this attachment is for.
		$parent_post_id = $post_id;

// Check the type of file. We'll use this as the 'post_mime_type'.
		$filetype = wp_check_filetype( basename( $filename ), null );

// Get the path to the upload directory.
		$wp_upload_dir = wp_upload_dir();

// Prepare an array of post data for the attachment.
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		if ( 'profile-pic' === $pic_type ) {
			// Insert the attachment.
			$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
			pfa_insert_or_update_user_meta( $user_id, 'pfa_prfl_img_post_id', $attach_id );
		} else if ( 'work-attach' === $pic_type ) {
			// Insert the attachment.
			$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
			pfa_insert_or_update_user_meta( $user_id, 'pfa_wrk_img_post_id', $attach_id );
		}

// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
	}
}


if ( isset( $_POST['submit'] ) && 'Update Profile' == $_POST['submit'] ) {

	$city = ( ! empty( $_POST['pfa-profile-city'] ) ) ? sanitize_text_field( $_POST['pfa-profile-city'] ) : '';
	$state =( ! empty( $_POST['pfa-profile-state'] ) ) ? sanitize_text_field( $_POST['pfa-profile-state'] ) : '';
	$zip = ( ! empty( $_POST['pfa-profile-zip'] ) ) ? intval( absint( $_POST['pfa-profile-zip'] ) ) : '';
	$phone = ( ! empty( $_POST['pfa-profile-phone'] ) ) ? intval( absint( $_POST['pfa-profile-phone'] ) ) : '';
	$cat_id = ( ! empty( $_POST['pfa-profile-category'] ) ) ? intval( absint( $_POST['pfa-profile-category'] ) ) : '';
	$sub_cat_id = ( ! empty( $_POST['pfa-profile-sub-cat'] ) ) ? sanitize_text_field( $_POST['pfa-profile-sub-cat'] ) : '';
	$gender = ( ! empty( $_POST['pfa-profile-gender'] ) ) ? sanitize_text_field( $_POST['pfa-profile-gender'] ) : '' ;
	$work_exp = ( ! empty( $_POST['pfa-past-work-exp'] ) ) ? sanitize_text_field( $_POST['pfa-past-work-exp'] ) : '';
	$dob = ( ! empty( $_POST['pfa-profile-dob'] ) ) ? sanitize_text_field( $_POST['pfa-profile-dob'] ) : '';
	$no_of_years =( ! empty( $_POST['pfa-past-work-exp'] ) ) ? intval( absint( $_POST['pfa-past-work-exp'] ) ) : '';
	$past_work_text = ( ! empty( $_POST['pfa-past-work-text'] ) ) ? sanitize_text_field( $_POST['pfa-past-work-text'] ) : '';
	$about_text = ( ! empty( $_POST['pfa-profile-about'] ) ) ? sanitize_text_field( $_POST['pfa-profile-about'] ) : '';
	$expectation = ( ! empty( $_POST['pfa-profile-expectation'] ) ) ? sanitize_text_field( $_POST['pfa-profile-expectation'] ) : '';
	$source = ( ! empty( $_POST['pfa-profile-source'] ) ) ? sanitize_text_field( $_POST['pfa-profile-source'] ) : '';

//	$user_id = ( $_POST['current_user_id'];
//	$user_display_name = $_POST['current_user_display_name'];

	$user_id = get_current_user_id();

	// If $post_id has a value means this user already has a custom artist post type.
	$post_id = get_user_meta( $user_id, 'pfa_custom_post', true );
	$user_display_name = wp_get_current_user()->display_name;
	$post_status = 'pending';
	// Check if the post id exists , if it doesn't then set its value to empty string.
	if ( 'publish' === get_post_status( $post_id ) ) {
		$post_id = ( 'publish' === get_post_status( $post_id ) ) ? $post_id : '';
		$post_status = 'publish';
	} else if ( 'pending' === get_post_status( $post_id ) ) {
		$post_id = ( 'pending' === get_post_status( $post_id ) ) ? $post_id : '';
		$post_status = 'pending';
	}

	if ( $post_id ) {
		// The custom post already exists , Just update it .
		pfa_update_existing_post_for_user( $post_id, $user_display_name, $post_status );
	} else {
		// Custom post does not exist for this user
		$post_id = pfa_set_new_post_for_user( $user_id, $user_display_name, 'pending' );
		$meta_existed = pfa_insert_or_update_user_meta( $user_id, 'pfa_custom_post', $post_id );
	}

	// Insert Data for the user
	pfa_insert_profile_city( $city, $post_id, $user_id );
	pfa_insert_or_update_user_meta( $user_id, 'state', $state );
	pfa_insert_or_update_user_meta( $user_id, 'zip', $zip );
	pfa_insert_or_update_user_meta( $user_id, 'pfa_phone', $phone );
	pfa_insert_profile_cat_and_sub_cat( $cat_id, $sub_cat_id, $post_id );
	pfa_insert_or_update_user_meta( $user_id, 'pfa_gender', $gender );
	pfa_insert_or_update_user_meta( $user_id, 'pfa_dob', $dob );
	pfa_insert_or_update_user_meta( $user_id, 'pfa_exp_yr', $no_of_years );
	pfa_insert_or_update_user_meta( $user_id, 'pfa_past_wrk', $past_work_text );
	pfa_insert_or_update_user_meta( $user_id, 'pfa_abt_urslf', $about_text );
	pfa_insert_or_update_user_meta( $user_id, 'pfa_expect', $expectation );
	pfa_insert_or_update_user_meta( $user_id, 'pfa_info_src', $source );
	pfa_handle_profile_media( $post_id, $user_id );

	if ( isset( $_POST['submit'] ) && 'Update Profile' == $_POST['submit'] ) {
		// Once everything is done redirect the user back to the same page
		$location = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		wp_safe_redirect( $location );
		exit;
	}
}