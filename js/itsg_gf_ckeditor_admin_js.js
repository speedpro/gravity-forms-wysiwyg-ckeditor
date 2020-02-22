jQuery(document).bind("gform_load_field_settings", function (event, field, form) {
	var field_type = field['type'];
	if ('post_content' == field_type  || 'textarea' == field_type || ('post_custom_field' == field_type  && 'textarea' == field['inputType'])) {

		var $wysiwyg_container = jQuery(".wysiwyg_field_setting_wysiwyg_ckeditor");

		$wysiwyg_container.show();

		var enable_wysiwyg_ckeditor = ( typeof field['enable_wysiwyg_ckeditor'] != 'undefined' && field['enable_wysiwyg_ckeditor'] != '' ) ? field['enable_wysiwyg_ckeditor'] : false;

		if ( enable_wysiwyg_ckeditor != false ) {
			//check the checkbox if previously checked
			$wysiwyg_container.find("input:checkbox").attr("checked", "checked");
			jQuery('#field_rich_text_editor').prop("disabled", true);
			ToggleRichTextEditor( false );
		} else {
			$wysiwyg_container.find("input:checkbox").removeAttr("checked");
			jQuery('#field_rich_text_editor').prop("disabled", false);
		}

		if( has_entry(field.id) || field.useRichTextEditor ){
			$wysiwyg_container.find("input:checkbox").prop("disabled", true);
			jQuery('#field_rich_text_editor').prop("disabled", false);
		} else{
			$wysiwyg_container.find("input:checkbox").prop("disabled", false);
		}
	}
});

// handles when the 'Enable WYSIWYG (CKEditor)' tick box is used in a field in the form editor

jQuery(".wysiwyg_field_setting_wysiwyg_ckeditor input").click(function () {
	if (jQuery(this).is(":checked")) {
		SetFieldProperty('enable_wysiwyg_ckeditor', 'true');
		jQuery( 'li#field_' + field.id + ' .ginput_container' ).addClass( 'gform_wysiwyg_ckeditor' );
		jQuery('#field_rich_text_editor').prop("disabled", true);
	} else {
		SetFieldProperty('enable_wysiwyg_ckeditor', '');
		jQuery( 'li#field_' + field.id + ' .ginput_container' ).removeClass( 'gform_wysiwyg_ckeditor' );
		jQuery('#field_rich_text_editor').prop("disabled", false);
	}
});


jQuery("input#field_rich_text_editor").click(function () {
	if (jQuery(this).is(":checked")) {
		jQuery("input:checkbox#field_enable_wysiwyg_ckeditor").prop("disabled", true);
	} else {
		jQuery("input:checkbox#field_enable_wysiwyg_ckeditor").prop("disabled", false);
	}
});