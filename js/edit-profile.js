(function( $ ){
	'use strict';
	var profile = {
		emptyFields: false,
		init: function() {
			this.handleAjaxRequest();
			this.handleEvents();
			this.throwErrorOnMissingFields();
		},

		/**
		 * Prevents form from submitting and throws error popup if required fields are not filled.
		 */
		throwErrorOnMissingFields: function() {
			var postStatus = $( '.pfa-artist-post-status' ).text();
			// When edit additional profile form is submitted
			$( '#mti-add-info-form' ).on( 'submit', function( event ){
				profile.setErrorOnEmptyFields();
				if ( profile.emptyFields ) {
					// console.log( profile.emptyFields );
					event.preventDefault();
				} else {
					if ( 'publish' === postStatus ) {
						alerts.success(
							'Details Saved successfully','',{
								displayDuration: 3000
							});
					} else {
						alerts.info(
							'Profile Sent to Admin for Approval','',{
								displayDuration: 3000
							});
					}
				}
			} );
		},

		/**
		 * Sets the value of global variable emptyFields to true if any required fields are empty,
		 * and also turns their label red.
		 */
		setErrorOnEmptyFields: function() {
			var cityVal = $( '#pfa-profile-city' ).val(),
				stateVal = $( '#pfa-profile-state' ).val(),
				zipVal = $( '#pfa-profile-zip' ).val(),
				phoneVal = $( '#pfa-profile-phone' ).val(),
				catVal = $( '#pfa-profile-category' ).val(),
				subCatVal = $( '#pfa-profile-sub-cat' ).val(),
				yearsVal = $( '#pfa-past-work-exp' ).val();

			// If any of the below fields is empty throw an error and dont submit the form
			if( ! cityVal || ! stateVal || ! zipVal || ! phoneVal || ! catVal || ! subCatVal || ! yearsVal ) {
				profile.emptyFields = true;
				alerts.error(
					'Please fill required fields','',{
						displayDuration: 3000
					});
			} else {
				profile.emptyFields = false;
			}

			if ( phoneVal && ( 10 > phoneVal.length ) ) {
				alerts.error(
					'Please enter a valid mobile number','',{
						displayDuration: 3000
					});
			}

			profile.toggleRedClass( cityVal, '.pfa-profile-city' );
			profile.toggleRedClass( stateVal, '.pfa-profile-state' );
			profile.toggleRedClass( zipVal, '.pfa-profile-zip' );
			profile.toggleRedClass( phoneVal, '.pfa-profile-phone' );
			profile.toggleRedClass( catVal, '.pfa-profile-category' );
			profile.toggleRedClass( subCatVal, '.pfa-profile-sub-cat' );
			profile.toggleRedClass( yearsVal, '.pfa-past-work-exp' );
		},

		/**
		 * Adds a red class if the field is empty and vice-versa.
		 */
		toggleRedClass: function( elVal, labelSelector ) {
			if ( ! elVal ) {
				$( labelSelector ).addClass( 'ihs-profile-form-red' );
			} else {
				$( labelSelector ).removeClass( 'ihs-profile-form-red' );
			}
		},

		handleEvents: function() {
			var newInputField, removeBtn, isRemoveBtnPresent;
			newInputField = '<label class="acf-basic-uploader pfa-acf-basic-uploader">';
			newInputField += '<input type="file" name="pfa-past-work-pic[]" id="pfa-past-work-pic" />'
			newInputField += '</label>';

			removeBtn = '<br><div class="btn pfa-remove-past-work-input">Remove -</div>',
				$( '.pfa-add-past-work-input' ).on( 'click', function() {
					var pastWorkWrapper = $( '.pfa-past-work-wrapper' );
					$( '.pfa-past-work-container' ).append( newInputField );
					isRemoveBtnPresent = pastWorkWrapper.find( 'div' ).hasClass( 'pfa-remove-past-work-input' );
					if ( ! isRemoveBtnPresent ) {
						pastWorkWrapper.append( removeBtn );
						$( '.pfa-remove-past-work-input' ).on( 'click', profile.removeUploadEl );
					}
				} );
		},

		removeUploadEl: function() {
			var isAdditionalInputsAvailable =  $( '.pfa-past-work-wrapper' ).find( 'label' ).last().prev().hasClass( 'pfa-acf-basic-uploader' );
			$( '.pfa-acf-basic-uploader:last' ).remove();
			if( ! isAdditionalInputsAvailable ) {
				$( '.pfa-remove-past-work-input' ).remove();
			}
		},

		handleAjaxRequest: function() {
			var subCatAlreadyExists = $( '.mti-artist-sub-cat' ).find( 'option' ).hasClass( 'pfa-sub-cat-exists' ),
				catId;
			if ( subCatAlreadyExists ) {

				// Unhide the Sub categories options if it has pre existing value.
				$( '.mti-sub-cat-profile-cont' ).removeClass( 'mti-hide' );
			}

			// When services category is selected ..
			$( '.mti-artist-services' ).on( 'change', function () {
				console.log( 'yes' );
				subCatAlreadyExists = $( '.mti-artist-sub-cat' ).find( 'option' ).hasClass( 'pfa-sub-cat-exists' );
				if ( subCatAlreadyExists ) {
					catId = this.value;

					// Remove existing subcategory options on change and add the fresh ones
					$( '.pfa-sub-cat-exists' ).remove();
					profile.ajaxRequest( catId );
					profile.addLoader();
				} else {

					// Add the fresh options for sub cat
					catId = this.value;
					profile.ajaxRequest( catId );
					profile.addLoader();
				}
			} );
		},

		ajaxRequest: function ( catId ) {
			var request = $.post(
				catdata.ajax_url,   // this url till admin-ajax.php  is given by functions.php wp_localoze_script()
				{
					action: 'ihs_cat_change',
					security: catdata.ajax_nonce,
					catId: catId
				}
			);

			request.done( function ( response ) {
				if ( response.data.my_data.length ) {
					var subCatMarkUp = profile.getSubCategoryMarkUp( response.data.my_data );
					$( '.mti-artist-sub-cat' ).html( subCatMarkUp );
					$( '.mti-sub-cat-profile-cont' ).show();
				} else {
					$( '.mti-sub-cat-profile-cont' ).hide();
				}
				$( '.ihs-loading' ).remove();
			} );
		},

		getSubCategoryMarkUp: function( subCatArray ) {
			var markUp = '',
				subCatObj, subCatName, subCatSlug, subCatId;
			markUp += '<option data-sub-cat-slug="" value=""></option>';
			for( var i = 0; i < subCatArray.length; i++ ) {
				subCatObj = subCatArray[ i ];
				subCatName = subCatObj.name;
				subCatSlug = subCatObj.slug;
				subCatId = subCatObj.term_id;
				markUp += '<option data-sub-cat-slug="' + subCatSlug + '" value="' + subCatId + '">' + subCatName + '</option>';
			}
			return markUp;
		},

		addLoader: function() {
			var loader = '<div class="ihs-loading ihs-loaderA"></div>',
				body = $( 'body' );
			body.prepend( loader );
		}
	};

	// var isEditProfilePage = $( 'body' ).hasClass( 'page-template-edit_profile-php' );
	// if ( isEditProfilePage ) {
	// 	profile.init();
	// }

	profile.init();
})( jQuery );