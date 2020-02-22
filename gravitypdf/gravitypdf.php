<?php
	add_filter( 'gfpdf_field_class', 'decode_wysiwgy_gravitypdf_4_0' , 10, 3 );	
	
	/*
	* Add Gravity PDF 4.0 support
	*/
	function decode_wysiwgy_gravitypdf_4_0( $class, $field, $entry ) {
		$ITSG_GF_WYSIWYG_CKEditor = new ITSG_GF_WYSIWYG_CKEditor();
		if ( $ITSG_GF_WYSIWYG_CKEditor->is_wysiwyg_ckeditor( $field ) ) {
			require_once( plugin_dir_path( __FILE__ ).'WYSIWYG_Textarea_Field.php' );
			$class = new GFPDF\Helper\Fields\WYSIWYG_Textarea_Field( $field, $entry, GPDFAPI::get_form_class(), GPDFAPI::get_misc_class() );
		}
		return $class;
	}