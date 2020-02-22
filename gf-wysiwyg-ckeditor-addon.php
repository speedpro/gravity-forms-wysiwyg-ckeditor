<?php
/*
 *   Setup the settings page for configuring the options
 */
if ( class_exists( 'GFForms' ) ) {
	GFForms::include_addon_framework();
	class GFWYSIWYGCKEditor extends GFAddOn {
		protected $_version = '1.15.1';
		protected $_min_gravityforms_version = '2';
		protected $_slug = 'gravity-forms-wysiwyg-ckeditor';
		protected $_full_path = __FILE__;
		protected $_title = 'CKEditor WYSIWYG for Gravity Forms';
		protected $_short_title = 'CKEditor WYSIWYG';

		public function scripts() {
			$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';
			$version = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? mt_rand() : $this->_version;

			$scripts = array(
				array(
					'handle'    => 'itsg_gf_ckeditor',
					'src'       => $this->get_base_url() . "/js/itsg_gf_ckeditor{$min}.js",
					'version'   => $version,
					'deps'      => array( 'jquery', 'gform_textarea_counter', 'ITSG_gf_wysiwyg_ckeditor_js', 'ITSG_gf_wysiwyg_ckeditor_jquery_adapter' ),
					'enqueue'   => array( array( $this, 'requires_scripts' ) ),
					'in_footer' => true,
					'callback'  => array( $this, 'localize_scripts' ),
				),
				array(
					'handle'    => 'ITSG_gf_wysiwyg_ckeditor_js',
					'src'       => $this->get_base_url() . "/ckeditor/ckeditor.js",
					'version'   => $version,
					'deps'      => array( 'jquery' ),
					'enqueue'   => array( array( $this, 'requires_scripts' ) ),
					'in_footer' => true
				),
				array(
					'handle'    => 'ITSG_gf_wysiwyg_ckeditor_jquery_adapter',
					'src'       => $this->get_base_url() . "/ckeditor/adapters/jquery.js",
					'version'   => $version,
					'deps'      => array( 'jquery' ),
					'enqueue'   => array( array( $this, 'requires_scripts' ) ),
					'in_footer' => true
				),
				array(
					'handle'    => 'itsg_gf_ckeditor_admin_js',
					'src'       => $this->get_base_url() . "/js/itsg_gf_ckeditor_admin_js{$min}.js",
					'version'   => $version,
					'deps'      => array( 'jquery' ),
					'enqueue'   => array( GFCommon::is_form_editor() ),
					'in_footer' => true
				)
			);

			$ckeditor_settings = ITSG_GF_WYSIWYG_CKEditor::get_options();

			if ( ! rgar( $ckeditor_settings, 'enable_count_spaces' ) ) {
				wp_deregister_script( 'gform_textarea_counter' ); // deregister default textarea count script - the default script counts spaces

				array_push( $scripts, array(
							'handle'    => 'gform_textarea_counter',
							'src'       => $this->get_base_url() . "/js/jquery.textareaCounter.plugin{$min}.js",
							'version'   => $version,
							'deps'      => array( 'jquery' ),
							'enqueue'   => array( array( $this, 'requires_scripts' ) ),
							'in_footer' => true
						)
					);
			}

			 return array_merge( parent::scripts(), $scripts );
		} // END scripts

		public function styles() {
			$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';
			$version = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? mt_rand() : $this->_version;

			$styles = array(
				array(
					'handle'  => 'itsg_gf_ckeditor_admin_css',
					'src'     => $this->get_base_url() . "/css/itsg_gf_ckeditor_admin_css{$min}.css",
					'version' => $version,
					'media'   => 'screen',
					'enqueue' => array( GFCommon::is_form_editor() ),
				),
				array(
					'handle'  => 'itsg_gf_ckeditor_css',
					'src'     => $this->get_base_url() . "/css/itsg_gf_ckeditor_css{$min}.css",
					'version' => $version,
					'media'   => 'screen',
					'enqueue' => array( array( $this, 'requires_scripts' ) ),
				),
			);

			return array_merge( parent::styles(), $styles );
		} // END styles

		public function localize_scripts( $form, $is_ajax ) {
			// Localize the script with new data
			$form_id = $form['id'];
			$is_entry_detail = GFCommon::is_entry_detail();
			$is_form_editor = GFCommon::is_form_editor();
			$admin_url = admin_url( 'admin-ajax.php' );
			$ckeditor_settings = ITSG_GF_WYSIWYG_CKEditor::get_options();

			$extra_plugins = '';
			if ( ( ! $is_form_editor || ! $is_entry_detail ) || rgar( $ckeditor_settings, 'enable_oembed' ) ) {
				if ( ! $is_form_editor && ! $is_entry_detail ) {
					$extra_plugins .=  'wordcount,notification';
					if ( rgar( $ckeditor_settings, 'enable_oembed' ) ) {
						$extra_plugins .= ',oembed,widget,widgetselection,dialog';
					}
				}
			}

			$remove_plugins = '';
			if ( rgar( $ckeditor_settings, 'enable_remove_elementspath' ) ) {
				$remove_plugins .=  'elementspath';
			}

			$ckeditor_fields = array();

			$toolbar_settings = array(
				'source' => array(
					! rgar( $ckeditor_settings, 'enable_source' ) ?: 'Source'
				),
				'basicstyles' => array(
					! rgar( $ckeditor_settings, 'enable_bold' ) ?: 'Bold',
					! rgar( $ckeditor_settings, 'enable_italic' ) ?: 'Italic',
					! rgar( $ckeditor_settings, 'enable_underline' ) ?: 'Underline',
					! rgar( $ckeditor_settings, 'enable_strike' ) ?: 'Strike',
					! rgar( $ckeditor_settings, 'enable_subscript' ) ?: 'Subscript',
					! rgar( $ckeditor_settings, 'enable_superscript' ) ?: 'Superscript',
					! rgar( $ckeditor_settings, 'enable_removeformat' ) ?: '-',
					! rgar( $ckeditor_settings, 'enable_removeformat' ) ?: 'RemoveFormat',
				),
				'clipboard' => array(
					! rgar( $ckeditor_settings, 'enable_cut' ) ?: 'Cut',
					! rgar( $ckeditor_settings, 'enable_copy' ) ?: 'Copy',
					! rgar( $ckeditor_settings, 'enable_paste' ) ?: 'Paste',
					! rgar( $ckeditor_settings, 'enable_pastetext' ) ?: 'PasteText',
					! rgar( $ckeditor_settings, 'enable_pastefromword' ) ?: 'PasteFromWord',
					! rgar( $ckeditor_settings, 'enable_undo' ) ?: '-',
					! rgar( $ckeditor_settings, 'enable_undo' ) ?: 'Undo',
					! rgar( $ckeditor_settings, 'enable_redo' ) ?: 'Redo',
				),
				'paragraph' => array(
					! rgar( $ckeditor_settings, 'enable_numberedlist' ) ?: 'NumberedList',
					! rgar( $ckeditor_settings, 'enable_bulletedlist' ) ?: 'BulletedList',
					! rgar( $ckeditor_settings, 'enable_outdent' ) ?: 'Outdent',
					! rgar( $ckeditor_settings, 'enable_indent' ) ?: 'Indent',
					! rgar( $ckeditor_settings, 'enable_blockquote' ) ?: 'Blockquote',
					! rgar( $ckeditor_settings, 'enable_creatediv' ) ?: 'CreateDiv',
					! rgar( $ckeditor_settings, 'enable_justifyleft' ) ?: '-',
					! rgar( $ckeditor_settings, 'enable_justifyleft' ) ?: 'JustifyLeft',
					! rgar( $ckeditor_settings, 'enable_justifycenter' ) ?: 'JustifyCenter',
					! rgar( $ckeditor_settings, 'enable_justifyright' ) ?: 'JustifyRight',
					! rgar( $ckeditor_settings, 'enable_justifyblock' ) ?: 'JustifyBlock',
					! rgar( $ckeditor_settings, 'enable_bidiltr' ) ?: '-',
					! rgar( $ckeditor_settings, 'enable_bidiltr' ) ?: 'BidiLtr',
					! rgar( $ckeditor_settings, 'enable_bidirtl' ) ?: 'BidiRtl',
					! rgar( $ckeditor_settings, 'enable_language' ) ?: 'Language',
				),
				'links' => array(
					! rgar( $ckeditor_settings, 'enable_link' ) ?: 'Link',
					! rgar( $ckeditor_settings, 'enable_unlink' ) ?: 'Unlink',
					! rgar( $ckeditor_settings, 'enable_anchor' ) ?: 'Anchor',
					! rgar( $ckeditor_settings, 'enable_oembed' ) ?: 'oembed',
				),
				'document' => array(
					! rgar( $ckeditor_settings, 'enable_preview' ) ?: 'Preview',
					! rgar( $ckeditor_settings, 'enable_print' ) ?: 'Print',
				),
				'editing' => array(
					! rgar( $ckeditor_settings, 'enable_find' ) ?: 'Find',
					! rgar( $ckeditor_settings, 'enable_replace' ) ?: 'Replace',
					! rgar( $ckeditor_settings, 'enable_selectall' ) ?: '-',
					! rgar( $ckeditor_settings, 'enable_selectall' ) ?: 'SelectAll',
					! rgar( $ckeditor_settings, 'enable_scayt' ) ?: '-',
					! rgar( $ckeditor_settings, 'enable_scayt' ) ?: 'Scayt',
				),
				'insert' => array(
					! ( rgar( $ckeditor_settings, 'enable_image' ) || ( rgar( $ckeditor_settings, 'enable_upload_image' ) && $this->is_minimum_php_version() ) ) ?: 'Image',
					! rgar( $ckeditor_settings, 'enable_flash' ) ?: 'Flash',
					! rgar( $ckeditor_settings, 'enable_table' ) ?: 'Table',
					! rgar( $ckeditor_settings, 'enable_horizontalrule' ) ?: 'HorizontalRule',
					! rgar( $ckeditor_settings, 'enable_smiley' ) ?: 'Smiley',
					! rgar( $ckeditor_settings, 'enable_specialchar' ) ?: 'SpecialChar',
					! rgar( $ckeditor_settings, 'enable_pagebreak' ) ?: 'PageBreak',
					! rgar( $ckeditor_settings, 'enable_iframe' ) ?: 'Iframe',
				),
				'styles' => array(
					! rgar( $ckeditor_settings, 'enable_styles' ) ?: 'Styles',
					! rgar( $ckeditor_settings, 'enable_format' ) ?: 'Format',
					! rgar( $ckeditor_settings, 'enable_font' ) ?: 'Font',
					! rgar( $ckeditor_settings, 'enable_fontsize' ) ?: 'FontSize',
				),
				'colors' => array(
					! rgar( $ckeditor_settings, 'enable_textcolor' ) ?: 'TextColor',
					! rgar( $ckeditor_settings, 'enable_bgcolor' ) ?: 'BGColor',
				),
				'tools' => array(
					! rgar( $ckeditor_settings, 'enable_maximize' ) ?: 'Maximize',
					! rgar( $ckeditor_settings, 'enable_showblocks' ) ?: 'ShowBlocks',
				),
				'about' => array(
					! rgar( $ckeditor_settings, 'enable_about' ) ?: 'About',
				)
			);

			// setup CKEditor in the Form Editor (uses default settings)
			if ( $is_form_editor ) {
				$ckeditor_fields[0][ 'source' ] = $toolbar_settings[ 'source' ];
				$ckeditor_fields[0][ 'basicstyles' ] =  $toolbar_settings[ 'basicstyles' ];
				$ckeditor_fields[0][ 'clipboard' ] =  $toolbar_settings[ 'clipboard' ];
				$ckeditor_fields[0][ 'paragraph' ] =  $toolbar_settings[ 'paragraph' ];
				$ckeditor_fields[0][ 'links' ] =  $toolbar_settings[ 'links' ];
				$ckeditor_fields[0][ 'document' ] =  $toolbar_settings[ 'document' ];
				$ckeditor_fields[0][ 'editing' ] =  $toolbar_settings[ 'editing' ];
				$ckeditor_fields[0][ 'insert' ] =  $toolbar_settings[ 'insert' ];
				$ckeditor_fields[0][ 'styles' ] =  $toolbar_settings[ 'styles' ];
				$ckeditor_fields[0][ 'colors' ] =  $toolbar_settings[ 'colors' ];
				$ckeditor_fields[0][ 'tools' ] =  $toolbar_settings[ 'tools' ];
				$ckeditor_fields[0][ 'about' ] =  $toolbar_settings[ 'about' ];
			} else {
				if ( is_array( $form['fields'] ) ) {
					foreach ( $form['fields'] as $field ) {
						if ( $this->is_wysiwyg_ckeditor( $field ) ) {
							$field_id = $field['id'];
							$ckeditor_fields[ $field_id ][ 'source' ] = $toolbar_settings[ 'source' ] ;
							$ckeditor_fields[ $field_id ][ 'basicstyles' ] =  $toolbar_settings[ 'basicstyles' ];
							$ckeditor_fields[ $field_id ][ 'clipboard' ] =  $toolbar_settings[ 'clipboard' ];
							$ckeditor_fields[ $field_id ][ 'paragraph' ] =  $toolbar_settings[ 'paragraph' ];
							$ckeditor_fields[ $field_id ][ 'links' ] =  $toolbar_settings[ 'links' ];
							$ckeditor_fields[ $field_id ][ 'document' ] =  $toolbar_settings[ 'document' ];
							$ckeditor_fields[ $field_id ][ 'editing' ] =  $toolbar_settings[ 'editing' ];
							$ckeditor_fields[ $field_id ][ 'insert' ] =  $toolbar_settings[ 'insert' ];
							$ckeditor_fields[ $field_id ][ 'styles' ] =  $toolbar_settings[ 'styles' ];
							$ckeditor_fields[ $field_id ][ 'colors' ] =  $toolbar_settings[ 'colors' ];
							$ckeditor_fields[ $field_id ][ 'tools' ] =  $toolbar_settings[ 'tools' ];
							$ckeditor_fields[ $field_id ][ 'about' ] =  $toolbar_settings[ 'about' ];
						}
					}
				}
			}

			// filter to modify global settings on a per form basis
			//$ckeditor_fields = apply_filters( 'itsg_gf_ckeditor_fields', $ckeditor_fields, $form_id );

			$settings_array = array(
				'form_id' => GFCommon::is_entry_detail() ? $_GET['id'] : $form_id,
				'is_entry_detail' => $is_entry_detail ? $is_entry_detail : 0,
				'is_form_editor' => $is_form_editor ? $is_form_editor : 0,
				'is_admin' => is_admin() ? 1 : 0,
				'is_dpr_installed' => $this->is_dpr_installed() ? 1 : 0,
				'enable_upload_image' => rgar( $ckeditor_settings, 'enable_upload_image') ? 1 : 0,
				'is_minimum_php_version' => $this->is_minimum_php_version() ? 1 : 0,
				'ckeditor_fields' => $ckeditor_fields,
				'extra_plugins' => $extra_plugins,
				'remove_plugins' => $remove_plugins,
				'admin_url' => $admin_url,
				'editor_height' => ! rgar( $ckeditor_settings, 'setting_editor_height' ) || $is_form_editor ? '200' : intval( rgar( $ckeditor_settings, 'setting_editor_height' ) ),
				'enable_scayt' => ! rgar( $ckeditor_settings, 'enable_scayt' ) ? false : true,
				'scayt_language' => ! rgar( $ckeditor_settings, 'setting_scayt_language' ) ? 'en_US' : rgar( $ckeditor_settings, 'setting_scayt_language' ),
				'link_target' => ! rgar( $ckeditor_settings, 'setting_link_target' ) || 'current_window' == rgar( $ckeditor_settings, 'setting_link_target' ) ? '' : '_blank',
				'editor_skin' => ! rgar( $ckeditor_settings, 'setting_editor_skin' ) ? 'moono-lisa' :  rgar( $ckeditor_settings, 'setting_editor_skin' ),
				'enable_count_spaces' => rgar( $ckeditor_settings, 'enable_count_spaces' ) ? true :  false,
			);

			wp_localize_script( 'itsg_gf_ckeditor', 'itsg_gf_ckeditor_settings', $settings_array );

		} // END localize_scripts

		public function requires_scripts( $form, $is_ajax ) {
			$ckeditor_settings = ITSG_GF_WYSIWYG_CKEditor::get_options();

			if ( GFCommon::is_form_editor() ) {
				if ( 'on' == $ckeditor_settings['enable_in_form_editor'] ) {
					return true;
				} else {
					return false;
				}
			} else {
				if ( is_array( $form ) ) {
					foreach ( $form['fields'] as $field ) {
						if ( $this->is_wysiwyg_ckeditor( $field ) ) {
							return true;
						}
					}
				}
			}

			return false;
		} // END requires_scripts

		/*
         * Checks if field is CKEditor enabled
         */
		public function is_wysiwyg_ckeditor( $field ) {
			$field_type = $field->type;
			if ( 'post_content' == $field_type ||
				'textarea' == $field_type ||
				( 'post_custom_field' == $field_type && 'textarea' == $field['inputType'] ) ) {
				if ( 'true' == $field->enable_wysiwyg_ckeditor && true !== $field->useRichTextEditor ) {
					return true;
				}
			}
			return false;
		} // END is_wysiwyg_ckeditor

		/*
         * Check if Gravity Forms - Data Persistence Reloaded is installed
         */
        private function is_dpr_installed() {
            return function_exists( 'ri_gfdp_ajax' );
        } // END is_dpr_installed

		/*
         * Check if PHP version is at least 5.4
         */
        private static function is_minimum_php_version() {
			return version_compare( phpversion(), '5.4', '>=' );
        } // END is_minimum_php_version
    }
    new GFWYSIWYGCKEditor();
}