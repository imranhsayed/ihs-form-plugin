<?php
ob_start();
$user_id = get_current_user_id();
$city = ( get_user_meta( $user_id, 'city', true ) ) ? get_user_meta( $user_id, 'city', true ) : '';
$state = ( get_user_meta( $user_id, 'state', true ) ) ? get_user_meta( $user_id, 'state', true ) : '';
$zip = ( get_user_meta( $user_id, 'zip', true ) ) ? get_user_meta( $user_id, 'zip', true ) : '';
$phone = ( get_user_meta( $user_id, 'pfa_phone', true ) ) ? get_user_meta( $user_id, 'pfa_phone', true ) : '';
$user_id = get_current_user_id();
$post_id = get_user_meta( $user_id, 'pfa_custom_post', true );
$artist_post_status = get_post_status( $post_id );

$args_cat = array(
	'taxonomy'          => 'artistcategory',
	'hide_empty'        => false,
	'parent'            => 0,
);

$categories_obj = get_terms( $args_cat );

// Get existing cat name and sub cat name.
$post_id = ( get_user_meta( $user_id, 'pfa_custom_post', true ) ) ? get_user_meta( $user_id, 'pfa_custom_post', true ) : '';
$existing_cat_name = '';
$existing_sub_cat_name = '';
if ( $post_id ) {
	$existing_cat = wp_get_post_terms( $post_id, 'artistcategory', array( 'orderby' => 'name', 'parent' => 0 ) );
	$existing_cat_id = ( ! empty( $existing_cat ) ) ? $existing_cat[0]->term_id : '';
	$existing_sub_cat = wp_get_post_terms( $post_id, 'artistcategory', array( 'orderby' => 'name', 'parent' => $existing_cat_id ) );
	$existing_cat_name = ( ! empty( $existing_cat ) ) ? $existing_cat[0]->name : '';
	$existing_sub_cat_name = ( ! empty( $existing_sub_cat ) ) ? $existing_sub_cat[0]->name : '';

	$args_sub_cat = array(
		'taxonomy'          => 'artistcategory',
		'hide_empty'        => false,
		'parent'            => $existing_cat_id,
	);

	$sub_cat_obj = get_terms( $args_sub_cat );
}

// Get existing gender meta values
$gender = ( get_user_meta( $user_id, 'pfa_gender', true ) ) ? get_user_meta( $user_id, 'pfa_gender', true ) : '';
$checked_male = ( 'male' === $gender ) ? 'checked' : false;
$checked_female = ( 'female' === $gender ) ? 'checked' : false;

if ( empty( $checked_male ) && empty( $checked_female ) ) {
	$checked_male = 'checked';
}

// Get existing dob meta values
$dob = ( get_user_meta( $user_id, 'pfa_dob', true ) ) ? get_user_meta( $user_id, 'pfa_dob', true ) : '';
$dob_formatted = '';
if ( ! empty( $dob ) ) {
	$year = substr( $dob, 0, 4 );
	$month = substr( $dob, 4, 2 );
	$date = substr( $dob, 6, 2 );
	$dob_formatted = $date . '/' . $month . '/' . $year;
}

// Get Existing years of experience
$exp_yr = ( get_user_meta( $user_id, 'pfa_exp_yr', true ) ) ? get_user_meta( $user_id, 'pfa_exp_yr', true ) : '';

// Get Existing past work text
$past_wrk = ( get_user_meta( $user_id, 'pfa_past_wrk', true ) ) ? get_user_meta( $user_id, 'pfa_past_wrk', true ) : '';

// Get Existing about yourself text
$abt_u = ( get_user_meta( $user_id, 'pfa_abt_urslf', true ) ) ? get_user_meta( $user_id, 'pfa_abt_urslf', true ) : '';

// Get Existing What you expect from us
$expectations = ( get_user_meta( $user_id, 'pfa_expect', true ) ) ? get_user_meta( $user_id, 'pfa_expect', true ) : '';

// Get Existing about how did you get to know about us text
$info_src = ( get_user_meta( $user_id, 'pfa_info_src', true ) ) ? get_user_meta( $user_id, 'pfa_info_src', true ) : '';

// Get the Existing profile pic image
$user_id = get_current_user_id();
$attachment_id = ( get_user_meta( $user_id, 'pfa_prfl_img_post_id', true ) ) ? get_user_meta( $user_id, 'pfa_prfl_img_post_id', true ) : '';
$attachment_id = intval( $attachment_id );
$profile_pic_img =  wp_get_attachment_image( $attachment_id, array('700', '600'), "", array( "class" => "pfa-profile-page-prof-img" ) );

// Get the Existing Work related pic image
$user_id = get_current_user_id();
$attachment_id = ( get_user_meta( $user_id, 'pfa_wrk_img_post_id', true ) ) ? get_user_meta( $user_id, 'pfa_wrk_img_post_id', true ) : '';
$attachment_id = intval( $attachment_id );
$wrk_attach_name =  get_the_title( $attachment_id );
?>

<form id="mti-add-info-form" class="acf-form" action="" method="post" enctype="multipart/form-data">
	<div class="acf-label">
		<label for="acf-field_5a957d79d438f">We would like to know more about you</label>
	</div>
	<div class="acf-field acf-field-text acf-field-5a7a8de32468c" data-name="city" data-type="text" data-key="field_5a7a8de32468c" data-required="1">
		<div class="acf-label">
			<label for="pfa-profile-city" class="pfa-profile-city">City <span class="acf-required">*</span></label>
		</div>
		<div class="acf-input">
			<div class="acf-input-wrap"><input type="text" id="pfa-profile-city" class="" name="pfa-profile-city" value="<?php echo $city; ?>" placeholder="" /></div>	</div>
	</div>
	<div class="acf-field acf-field-text acf-field-5a7a8ed32468d" data-name="state" data-type="text" data-key="field_5a7a8ed32468d" data-required="1">
		<div class="acf-label">
			<label for="pfa-profile-state" class="pfa-profile-state">State <span class="acf-required">*</span></label>
		</div>
		<div class="acf-input">
			<div class="acf-input-wrap"><input type="text" id="pfa-profile-state" class="" name="pfa-profile-state" value="<?php echo $state; ?>" placeholder="" /></div>	</div>
	</div>
	<div class="acf-field acf-field-number acf-field-5a7a8f0b2468e" data-name="zip" data-type="number" data-key="field_5a7a8f0b2468e" data-required="1">
		<div class="acf-label">
			<label for="pfa-profile-zip" class="pfa-profile-zip">Zip <span class="acf-required">*</span></label>
		</div>
		<div class="acf-input">
			<div class="acf-input-wrap"><input type="number" id="pfa-profile-zip" class="" min="" max="" step="any" name="pfa-profile-zip" value="<?php echo $zip; ?>" placeholder="" /></div>	</div>
	</div>
	<div class="acf-field acf-field-number acf-field-5a7a8f3b2468f" data-name="phone" data-type="number" data-key="field_5a7a8f3b2468f" data-required="1">
		<div class="acf-label">
			<label for="pfa-profile-phone" class="pfa-profile-phone">Phone <span class="acf-required">*</span></label>
		</div>
		<div class="acf-input">
			<div class="acf-input-wrap"><input type="number" id="pfa-profile-phone" class="" min="" max="" step="any" name="pfa-profile-phone" value="<?php echo $phone; ?>" placeholder="" /></div>	</div>
	</div>
	<?php

	// Categories.
	$form_fields ='<div class="acf-field acf-field-select acf-field-5a9ece7fd9ad1" data-name="services" data-type="select" data-key="field_5a9ece7fd9ad1" data-required="1">
		<div class="acf-label">
			<label for="pfa-profile-category" class="pfa-profile-category">Services <span class="acf-required">*</span></label>
		</div>
		<div class="acf-input">
			<select id="pfa-profile-category" class="mti-artist-services" name="pfa-profile-category" data-ui="0" data-ajax="0" data-multiple="0" data-placeholder="Select" data-allow_null="0">';
				$form_fields .= '<option data-cat-slug="" value=""></option>';
				if ( ! empty( $categories_obj ) ) {
					foreach ( $categories_obj as $cat ) {
						$cat_id = $cat->term_id;
						$cat_name = $cat->name;
						$cat_slug = $cat->slug;
						$selected = ( $cat_name === $existing_cat_name ) ? 'selected' : '';
						$form_fields .= '<option data-cat-slug="' . $cat_slug . '" value="' . $cat_id . '" ' . $selected . '>' . $cat_name . '</option>';
					}
				}
				$form_fields .='</select>
		</div>
	</div>
	<!-- Sub Category -->
	<div class="acf-field acf-field-select acf-field-5a9ece7fd9ad1 mti-hide mti-sub-cat-profile-cont" data-name="services-sub" data-type="select" data-key="field_5a9ece7fd9ad1" data-required="1">
		<div class="acf-label">
			<label for="pfa-profile-sub-cat" class="pfa-profile-sub-cat">Sub Category<span class="acf-required">*</span></label>
		</div>
		<div class="acf-input">
			<select id="pfa-profile-sub-cat" class="mti-artist-sub-cat" name="pfa-profile-sub-cat" data-ui="0" data-ajax="0" data-multiple="0" data-placeholder="Select" data-allow_null="0">
				<option data-sub-cat-slug="" value=""></option>';
				if ( ! empty( $sub_cat_obj ) && ! empty( $existing_sub_cat_name ) ) {
					foreach ( $sub_cat_obj as $sub_cat ) {
					$sub_cat_id = $sub_cat->term_id;
					$sub_cat_name = $sub_cat->name;
					$sub_cat_slug = $sub_cat->slug;
					$selected = ( $sub_cat_name === $existing_sub_cat_name ) ? 'selected' : '';
					$form_fields .= '<option class="pfa-sub-cat-exists" data-cat-slug="' . $sub_cat_slug . '" value="' . $sub_cat_id . '" ' . $selected . '>' . $sub_cat_name . '</option>';
				}
				}

				$form_fields .='</select>
		</div>
	</div>
	<!-- Profile Pic -->
	<div class="acf-field acf-field-image acf-field-5a7a8fee24690" data-name="profile_pic" data-type="image" data-key="field_5a7a8fee24690">
		<div class="existing-prof-pic-cont">' . $profile_pic_img . '</div>
		<div class="acf-label">
			<label for="pfa-profile-pic">Profile pic</label>
		</div>
		<div class="acf-input">
			<div class="acf-image-uploader acf-cf" data-preview_size="medium" data-library="all" data-mime_types="" data-uploader="basic">
				<input name="acf[field_5a7a8fee24690]" value="" type="hidden" /><div class="view show-if-value acf-soh" style="max-width: 300px">
					<img data-name="image" src="" alt=""/>
					<ul class="acf-hl acf-soh-target">
						<li><a class="acf-icon -cancel dark" data-name="remove" href="#" title="Remove"></a></li>
					</ul>
				</div>
				<div class="view hide-if-value">
					<label class="acf-basic-uploader">
						<input type="file" name="pfa-profile-pic" id="pfa-profile-pic" />
					</label>

				</div>
			</div>
		</div>
	</div>
	<!-- Past Work Uploads -->
	<div class="acf-field pfa-past-work-wrapper acf-field-image acf-field-5a7a8fee24690" data-name="profile_pic" data-type="image" data-key="field_5a7a8fee24690">
		<div class="existing-wrk-attach-name-cont">' . $wrk_attach_name . '</div>
		<div class="acf-label">
			<label for="pfa-profile-pic">Past Work( Uploads pdf or img files of your past work )</label>
		</div>
		<div class="acf-input">
			<div class="acf-image-uploader acf-cf" data-preview_size="medium" data-library="all" data-mime_types="" data-uploader="basic">
				<input name="pfa-hidden-past-work" value="" type="hidden" /><div class="view show-if-value acf-soh" style="max-width: 300px">
					<img data-name="image" src="" alt=""/>
					<ul class="acf-hl acf-soh-target">
						<li><a class="acf-icon -cancel dark" data-name="remove" href="#" title="Remove"></a></li>
					</ul>
				</div>
				<div class="view hide-if-value pfa-past-work-container">
					<label class="acf-basic-uploader">
						<input type="file" name="pfa-past-work-pic" id="pfa-past-work-pic" />
					</label>
				</div>
			</div>
		</div>
	</div>
	<div class="acf-field acf-field-radio acf-field-5a95762cf5fe6" data-name="gender" data-type="radio" data-key="field_5a95762cf5fe6" data-required="1">
		<div class="acf-label">
			<label for="pfa-past-work-gender">Gender: <span class="acf-required">*</span></label>
		</div>
		<div class="acf-input">
			<input name="" type="hidden" />
			<ul class="acf-radio-list acf-bl" data-allow_null="0" data-other_choice="0">
				<li>
					<label>
						<input type="radio" id="pfa-past-work-gender-male" name="pfa-profile-gender" value="male" ' . $checked_male . '/>Male
					</label>
				</li>
				<li>
					<label>
						<input type="radio" id="pfa-past-work-gender-female" name="pfa-profile-gender" value="female" ' . $checked_female . '/>Female
					</label>
				</li>
			</ul>
		</div>
	</div>
	<div class="acf-field acf-field-date-picker acf-field-5a9577c1f5fe7" data-name="dob" data-type="date_picker" data-key="field_5a9577c1f5fe7">
		<div class="acf-label">
			<label for="pfa-profile-dob">DOB</label>
		</div>
		<div class="acf-input">
			<div class="acf-date-picker acf-input-wrap" data-date_format="dd/mm/yy" data-first_day="1">
			<input class="input" type="text" name="pfa-profile-dob" value="' . $dob . '"/></div>
		</div>
	</div>
	<div class="acf-field acf-field-message acf-field-5a95794af5fed" data-name="work_experience:" data-type="message" data-key="field_5a95794af5fed">
		<div class="acf-label">
			<label for="acf-field_5a95794af5fed">Work Experience:</label>
		</div>
		<div class="acf-input">
			<p>Share Details Regarding Your Work Experience.</p>
		</div>
	</div>
	<div class="acf-field acf-field-number acf-field-5a957a4ff5fee" data-name="no_of_years" data-type="number" data-key="field_5a957a4ff5fee" data-required="1">
		<div class="acf-label">
			<label for="pfa-past-work-exp" class="pfa-past-work-exp">No of years: <span class="acf-required">*</span></label>
		</div>
		<div class="acf-input">
			<div class="acf-input-wrap"><input type="number" id="pfa-past-work-exp" class="" min="" max="" step="any" name="pfa-past-work-exp" value="' . $exp_yr . '" placeholder="" /></div>	</div>
	</div>
	<div class="acf-field acf-field-textarea acf-field-5a957a6ff5fef" data-name="past_work" data-type="textarea" data-key="field_5a957a6ff5fef">
		<div class="acf-label">
			<label for="pfa-past-work-text">Past work:</label>
			<p class="description">Please share your past work or link to social media that we can show your work.</p>
		</div>
		<div class="acf-input">
			<textarea id="pfa-past-work-text" class="" name="pfa-past-work-text" placeholder="" rows="8" >' . $past_wrk . '</textarea>
		</div>
	</div>
	<div class="acf-field acf-field-textarea acf-field-5a957ae2f5ff0" data-name="tell_us_a_little_about_yourself" data-type="textarea" data-key="field_5a957ae2f5ff0">
		<div class="acf-label">
			<label for="pfa-profile-about">Tell us a little about yourself</label>
		</div>
		<div class="acf-input">
			<textarea id="pfa-profile-about" class="" name="pfa-profile-about" placeholder="" rows="8" >' . $abt_u . '</textarea>
		</div>
	</div>
	<div class="acf-field acf-field-textarea acf-field-5a957b06f5ff1" data-name="what_are_you_expecting_from_us" data-type="textarea" data-key="field_5a957b06f5ff1">
		<div class="acf-label">
			<label for="pfa-profile-expectation">What are you expecting from us?</label>
		</div>
		<div class="acf-input">
			<textarea id="pfa-profile-expectation" class="" name="pfa-profile-expectation" placeholder="" rows="8" >' . $expectations . '</textarea>	</div>
	</div>
	<div class="acf-field acf-field-text acf-field-5a957b1ff5ff2" data-name="how_did_you_get_to_know_about_us" data-type="text" data-key="field_5a957b1ff5ff2">
		<div class="acf-label">
			<label for="pfa-profile-source">How did you get to know about us?</label>
		</div>
		<div class="acf-input">
			<div class="acf-input-wrap"><input type="text" id="pfa-profile-source" class="" name="pfa-profile-source" value="' . $info_src . '" placeholder="" /></div>	</div>
	</div>
	<input name="current_user_id" type="hidden" value="' . get_current_user_id() . '">
	<input name="current_user_display_name" type="hidden" value="Imran Sayed">
	<div class="pfa-hide pfa-artist-post-status">' . $artist_post_status . '</div>
	<div class="acf-form-submit">
		<input type="submit" name="submit" class="mti-sub-button button button-primary button-large" value="Update Profile" /><span class="acf-spinner"></span>
	</div>';
	echo $form_fields;
	?>
</form>

<?php require_once 'update-user-profile.php'; ?>