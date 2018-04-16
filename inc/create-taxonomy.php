<?php
/**
 * Created by PhpStorm.
 * User: imransayed
 * Date: 4/11/18
 * Time: 11:54 PM
 */

if ( ! function_exists( 'mti_create_artistprofile_cpt' ) ) {
	/**
	 * Register Custom Post Type Artist Profile.
	 * Post Type Key: artistprofile.
	 */
	function mti_create_artistprofile_cpt() {

		$labels = array(
			'name' => __( 'Artist Profiles', 'Post Type General Name', 'artist-profile' ),
			'singular_name' => __( 'Artist Profile', 'Post Type Singular Name', 'artist-profile' ),
			'menu_name' => __( 'Artist Profiles', 'artist-profile' ),
			'name_admin_bar' => __( 'Artist Profile', 'artist-profile' ),
			'archives' => __( 'Artist Profile Archives', 'artist-profile' ),
			'attributes' => __( 'Artist Profile Attributes', 'artist-profile' ),
			'parent_item_colon' => __( 'Parent Artist Profile:', 'artist-profile' ),
			'all_items' => __( 'All Artist Profiles', 'artist-profile' ),
			'add_new_item' => __( 'Add New Artist Profile', 'artist-profile' ),
			'add_new' => __( 'Add New', 'artist-profile' ),
			'new_item' => __( 'New Artist Profile', 'artist-profile' ),
			'edit_item' => __( 'Edit Artist Profile', 'artist-profile' ),
			'update_item' => __( 'Update Artist Profile', 'artist-profile' ),
			'view_item' => __( 'View Artist Profile', 'artist-profile' ),
			'view_items' => __( 'View Artist Profiles', 'artist-profile' ),
			'search_items' => __( 'Search Artist Profile', 'artist-profile' ),
			'not_found' => __( 'Not found', 'artist-profile' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'artist-profile' ),
			'featured_image' => __( 'Featured Image', 'artist-profile' ),
			'set_featured_image' => __( 'Set featured image', 'artist-profile' ),
			'remove_featured_image' => __( 'Remove featured image', 'artist-profile' ),
			'use_featured_image' => __( 'Use as featured image', 'artist-profile' ),
			'insert_into_item' => __( 'Insert into Artist Profile', 'artist-profile' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Artist Profile', 'artist-profile' ),
			'items_list' => __( 'Artist Profiles list', 'artist-profile' ),
			'items_list_navigation' => __( 'Artist Profiles list navigation', 'artist-profile' ),
			'filter_items_list' => __( 'Filter Artist Profiles list', 'artist-profile' ),
		);
		$args = array(
			'label' => __( 'Artist Profile', 'artist-profile' ),
			'description' => __( '', 'artist-profile' ),
			'labels' => $labels,
			'menu_icon' => 'dashicons-admin-post',
			'supports' => array('title', 'editor', 'author', 'custom-fields', ),
			'taxonomies' => array(),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 5,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'can_export' => true,
			'has_archive' => true,
			'hierarchical' => false,
			'exclude_from_search' => false,
			'show_in_rest' => true,
			'publicly_queryable' => true,
			'capability_type' => 'post',
		);
		register_post_type( 'artistprofile', $args );

	}
	add_action( 'init', 'mti_create_artistprofile_cpt', 0 );
}

if ( ! function_exists( 'mti_create_artistcategory_tax' ) ) {
	/**
	 * Register Taxonomy Artist Category
	 * Taxonomy Key: artistcategory
	 */
	function mti_create_artistcategory_tax() {

		$labels = array(
			'name'              => _x( 'Artist Categories', 'taxonomy general name', 'artist-category' ),
			'singular_name'     => _x( 'Artist Category', 'taxonomy singular name', 'artist-category' ),
			'search_items'      => __( 'Search Artist Categories', 'artist-category' ),
			'all_items'         => __( 'All Artist Categories', 'artist-category' ),
			'parent_item'       => __( 'Parent Artist Category', 'artist-category' ),
			'parent_item_colon' => __( 'Parent Artist Category:', 'artist-category' ),
			'edit_item'         => __( 'Edit Artist Category', 'artist-category' ),
			'update_item'       => __( 'Update Artist Category', 'artist-category' ),
			'add_new_item'      => __( 'Add New Artist Category', 'artist-category' ),
			'new_item_name'     => __( 'New Artist Category Name', 'artist-category' ),
			'menu_name'         => __( 'Artist Category', 'artist-category' ),
		);
		$args = array(
			'labels' => $labels,
			'description' => __( '', 'artist-category' ),
			'hierarchical' => true,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'show_in_rest' => false,
			'show_tagcloud' => true,
			'show_in_quick_edit' => true,
			'show_admin_column' => true,
		);
		register_taxonomy( 'artistcategory', array('artistprofile', ), $args );

		/**
		 * Register Taxonomy for City
		 */
		$labels = array(
			'name'              => _x( 'Cities', 'taxonomy general name', 'artist-city' ),
			'singular_name'     => _x( 'City', 'taxonomy singular name', 'artist-city' ),
			'search_items'      => __( 'Search Cities', 'artist-city' ),
			'all_items'         => __( 'All Cities', 'artist-city' ),
			'parent_item'       => __( 'Parent City', 'artist-city' ),
			'parent_item_colon' => __( 'Parent City:', 'artist-city' ),
			'edit_item'         => __( 'Edit City', 'artist-city' ),
			'update_item'       => __( 'Update City', 'artist-city' ),
			'add_new_item'      => __( 'Add New City', 'artist-city' ),
			'new_item_name'     => __( 'New City Name', 'artist-city' ),
			'menu_name'         => __( 'City', 'artist-city' ),
		);
		$args = array(
			'labels' => $labels,
			'description' => __( '', 'artist-city' ),
			'hierarchical' => false,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'show_in_rest' => false,
			'show_tagcloud' => true,
			'show_in_quick_edit' => true,
			'show_admin_column' => true,
		);
		register_taxonomy( 'city', array('artistprofile', ), $args );

	}
	add_action( 'init', 'mti_create_artistcategory_tax' );
}
