<?php
/*
Plugin Name: CKEditor WYSIWYG for Gravity Forms
Description: Use the CKEditor WYSIWYG in your Gravity Forms
Version: 1.15.1
Author: Adrian Gordon
Author URI: http://www.itsupportguides.com
License: GPL2
Text Domain: gravity-forms-wysiwyg-ckeditor

------------------------------------------------------------------------
Copyright 2015 Adrian Gordon

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*/

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

load_plugin_textdomain( 'gravity-forms-wysiwyg-ckeditor', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

// include upload handler for image upload feature -- extends default classes
if ( !class_exists( 'UploadHandler' ) && version_compare( phpversion(), '5.4', '>=' ) ) {
	require_once( plugin_dir_path( __FILE__ ).'UploadHandler.php' );
}

if ( class_exists( 'UploadHandler' ) ) {
	class ITSG_GFCKEDITOR_UploadHandler extends UploadHandler {
		public function post( $print_response = true ) {
			$upload = $this->get_upload_data( $this->options['param_name'] );
			// Parse the Content-Disposition header, if available:
			$content_disposition_header = $this->get_server_var( 'HTTP_CONTENT_DISPOSITION' );
			$file_name = $content_disposition_header ?
			rawurldecode(preg_replace(
			'/(^[^"]+")|("$)/',
			'',
			$content_disposition_header
			)) : null;
			// Parse the Content-Range header, which has the following form:
			// Content-Range: bytes 0-524287/2000000
			$content_range_header = $this->get_server_var( 'HTTP_CONTENT_RANGE' );
			$content_range = $content_range_header ?
			preg_split('/[^0-9]+/', $content_range_header ) : null;
			$size =  $content_range ? $content_range[3] : null;
			$files = array();
			if ( $upload ) {
				if ( is_array( $upload['tmp_name'] ) ) {
					// param_name is an array identifier like "files[]",
					// $upload is a multi-dimensional array:
					foreach ( $upload['tmp_name'] as $index => $value ) {
						$files[] = $this->handle_file_upload (
							$upload['tmp_name'][$index],
							$file_name ? $file_name : $upload['name'][$index],
							$size ? $size : $upload['size'][$index],
							$upload['type'][$index],
							$upload['error'][$index],
							$index,
							$content_range
						);
					}
				} else {
					// param_name is a single object identifier like "file",
					// $upload is a one-dimensional array:
					$files[] = $this->handle_file_upload(
						isset( $upload['tmp_name'] ) ? $upload['tmp_name'] : null,
						$file_name ? $file_name : ( isset($upload['name'] ) ?
								$upload['name'] : null ),
						$size ? $size : (isset($upload['size']) ?
								$upload['size'] : $this->get_server_var( 'CONTENT_LENGTH' ) ),
						isset( $upload['type'] ) ?
								$upload['type'] : $this->get_server_var( 'CONTENT_TYPE' ),
						isset( $upload['error'] )  ? $upload['error'] : null,
						null,
						$content_range
					);
				}
			}
			$CKEditorFuncNum = $this->options['CKEditorFuncNum'];
			$download_url = $files[0]->url;
			$response = "<script>
var l = '".$download_url."';
window.parent.CKEDITOR.tools.callFunction(
'" . $CKEditorFuncNum . "',
l,
'".  $files[0]->error ."'
);
</script>";
			return $this->generate_response( $response, $print_response );
		}

		public function generate_response( $content, $print_response = true ) {
			$this->response = $content;
			$this->body( $content );
			return $content;
		}

		protected function trim_file_name( $file_path, $name, $size, $type, $error, $index, $content_range ) {
			$name = apply_filters( 'itsg_gf_ckeditor_filename', $name, $file_path, $size, $type, $error, $index, $content_range );

			$exclude_characters = array(
				'\\',
				'/',
				':',
				';',
				'*',
				'?',
				'!',
				'"',
				'`',
				"'",
				'<',
				'>',
				'{',
				'}',
				'[',
				']',
				',',
				'|'
				);
			$exclude_characters = ( array )apply_filters( 'itsg_gf_ckeditor_filename_exclude_characters', $exclude_characters );
			$replace_character = ( string )apply_filters( 'itsg_gf_ckeditor_filename_replace_characters', '' );
			$name = str_replace( $exclude_characters, $replace_character, $name );

			return $name;
		}
	} // END ITSG_GFCKEDITOR_UploadHandler
}

add_action( 'admin_notices', array( 'ITSG_GF_WYSIWYG_CKEditor', 'admin_warnings' ), 20 );
load_plugin_textdomain( 'itsg_gf_wysiwyg_ckeditor', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

require_once( plugin_dir_path( __FILE__ ).'gf_wysiwyg_ckeditor_settings.php' );

if ( !class_exists( 'ITSG_GF_WYSIWYG_CKEditor' ) ) {
    class ITSG_GF_WYSIWYG_CKEditor {
	private static $name = 'CKEditor WYSIWYG for Gravity Forms';
    private static $slug = 'gravity-forms-wysiwyg-ckeditor';

        /*
         * Construct the plugin object
         */
        public function __construct() {
			// register plugin functions through 'gform_loaded' -
			// this delays the registration until Gravity Forms has loaded, ensuring it does not run before Gravity Forms is available.
            add_action( 'gform_loaded', array( $this, 'register_actions' ) );
		} // END __construct

		/*
         * Register plugin functions
         */
		function register_actions() {
		// register actions
            if ( self::is_gravityforms_installed() ) {
				$ckeditor_settings = self::get_options();

				// addon framework
				require_once( plugin_dir_path( __FILE__ ).'gf-wysiwyg-ckeditor-addon.php' );

				//start plug in

				add_action( 'wp_ajax_itsg_gf_wysiwyg_ckeditor_upload', array( $this, 'itsg_gf_wysiwyg_ckeditor_upload' ) );
				add_action( 'wp_ajax_nopriv_itsg_gf_wysiwyg_ckeditor_upload', array( $this, 'itsg_gf_wysiwyg_ckeditor_upload' ) );

				add_filter( 'gform_save_field_value', array( $this, 'save_field_value' ), 10, 4 );
				add_action( 'gform_field_standard_settings', array( $this, 'ckeditor_field_settings' ), 10, 2 );
				add_filter( 'gform_tooltips', array( $this, 'ckeditor_field_tooltips' ) );
				add_action( 'gform_field_css_class', array( $this, 'ckeditor_field_css_class' ), 10, 3 );
				add_filter( 'gform_field_content',  array( $this, 'ckeditor_field_content' ), 10, 5 );
				add_filter( 'gform_counter_script', array( $this, 'ckeditor_counter_script_js' ), 10, 4 );
				add_filter( 'gform_merge_tag_filter', array( $this, 'decode_wysiwyg_frontend_confirmation' ), 10, 5 );
				add_filter( 'gform_entry_field_value', array( $this, 'decode_wysiwyg_backend_and_gravitypdf' ), 10, 4 );
				add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_action_links' ) );

				add_filter( 'gform_field_validation', array( $this, 'ckeditor_dont_count_spaces' ), 10, 4 );

				add_filter( 'gform_entries_column_filter', array( $this,  'entries_column_filter' ), 10, 5 );

				if ( self::is_minimum_php_version() ) {
					require_once( plugin_dir_path( __FILE__ ).'gravitypdf/gravitypdf.php' );
				}

				// patch to allow JS and CSS to load when loading forms through wp-ajax requests
				add_action( 'gform_enqueue_scripts', array( $this, 'enqueue_scripts' ), 90, 2 );

				if ( 'gf_settings' == RGForms::get('page') ) {
					// add settings page
					RGForms::add_settings_page( 'WYSIWYG CKEditor', array( 'ITSG_GF_WYSIWYG_ckeditor_settings_page', 'settings_page' ), self::get_base_url() . '/images/user-registration-icon-32.png' );

					if ( ( 'WYSIWYG+CKEditor' == RGForms::get('subview') || 'WYSIWYG CKEditor' == RGForms::get('subview') ) && !self::is_minimum_php_version() ) {
						add_action( 'admin_notices', array( $this, 'admin_warnings_minimum_php_version'), 20 );
					}

				}

				if ( $ckeditor_settings['enable_upload_image'] && self::is_minimum_php_version() ) {
					// handles the change upload path settings
					add_filter( 'gform_upload_path', array( $this, 'change_upload_path' ), 10, 2 );
				}
			}
		} // END register_actions

		public function ckeditor_dont_count_spaces( $result, $value, $form, $field ) {
			//if ( $this->is_wysiwyg_ckeditor( $field ) || $field->useRichTextEditor ) {
				if ( ! is_numeric( $field['maxLength'] ) ) {
					return $result;
				}

				$value = strip_tags( $value );

				$ckeditor_settings = ITSG_GF_WYSIWYG_CKEditor::get_options();

				if ( rgar( $ckeditor_settings, 'enable_count_spaces' ) ) {
					$value = preg_replace( '/\r|\n/', '' , $value ); // remove line breaks for the purpose of the character count
				} else {
					$value = preg_replace( '/\r|\n|\s|&nbsp;/', '' , $value ); // remove line breaks and spaces for the purpose of the character count
				}

				// decode HTML entities so they are counted correctly
				if ( function_exists( 'mb_convert_encoding' ) ) {
					$value =  mb_convert_encoding( $value, 'UTF-8', 'HTML-ENTITIES' );
				} else {
					$value =  preg_replace( '/&.*?;/', 'x', $value ); // multi-byte characters converted to X
				}

				if ( GFCommon::safe_strlen( $value ) > $field['maxLength'] ) {
					$result['is_valid']  = false;
					$result['message'] = empty( $field['errorMessage'] ) ? esc_html_x( 'The text entered exceeds the maximum number of characters.', 'Same as Gravity Forms (slug: gravityforms) plugin','gravity-forms-wysiwyg-ckeditor' ) : $field['errorMessage'];
				} elseif ( ! ( $field['isRequired'] && GFCommon::safe_strlen( $value ) == 0 ) ) {
					$result['is_valid']  = true;
				}
			//}
			return $result;
		}

	/**
	 * BEGIN: patch to allow JS and CSS to load when loading forms through wp-ajax requests
	 *
	 */

		/*
         * Enqueue JavaScript to footer
         */
		public function enqueue_scripts( $form, $is_ajax ) {
			if ( $this->requires_scripts( $form, $is_ajax ) ) {
				$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';

				$ckeditor_settings = ITSG_GF_WYSIWYG_CKEditor::get_options();

				if ( rgar( $ckeditor_settings, 'enable_count_spaces' ) ) {
					wp_deregister_script( 'gform_textarea_counter' ); // deregister default textarea count script - the default script counts spaces
					wp_enqueue_script( 'gform_textarea_counter', plugins_url( "/js/jquery.textareaCounter.plugin{$min}.js", __FILE__ ) );
				}

				wp_register_script( 'itsg_gf_ckeditor', plugins_url( "/js/itsg_gf_ckeditor{$min}.js", __FILE__ ),  array( 'jquery', 'gform_textarea_counter', 'ITSG_gf_wysiwyg_ckeditor_js', 'ITSG_gf_wysiwyg_ckeditor_jquery_adapter' ) );

				wp_enqueue_script( 'ITSG_gf_wysiwyg_ckeditor_js', plugins_url( "/ckeditor/ckeditor.js", __FILE__ ) );
				wp_enqueue_script( 'ITSG_gf_wysiwyg_ckeditor_jquery_adapter', plugins_url( "ckeditor/adapters/jquery.js", __FILE__ ) );

				// Localize the script with new data
				$this->localize_scripts( $form, $is_ajax );

			}
		} // END datepicker_js

		public function requires_scripts( $form, $is_ajax ) {
			if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX && ! GFCommon::is_form_editor() && is_array( $form ) ) {
				foreach ( $form['fields'] as $field ) {
					if ( $this->is_wysiwyg_ckeditor( $field ) ) {
						return true;
					}
				}
			}

			return false;
		} // END requires_scripts

		function localize_scripts( $form, $is_ajax ) {
			// Localize the script with new data
			$form_id = $form['id'];
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
					$extra_plugins .= ',oembed,widget,dialog';
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
				'editor_height' => ! rgar( $ckeditor_settings, 'setting_editor_height' ) ? '200' : intval( rgar( $ckeditor_settings, 'setting_editor_height' ) ),
				'enable_scayt' => ! rgar( $ckeditor_settings, 'enable_scayt' ) ? false : true,
				'scayt_language' => ! rgar( $ckeditor_settings, 'setting_scayt_language' ) ? 'en_US' : rgar( $ckeditor_settings, 'setting_scayt_language' ),
				'link_target' => ! rgar( $ckeditor_settings, 'setting_link_target' ) || 'current_window' == rgar( $ckeditor_settings, 'setting_link_target' ) ? '' : '_blank',
				'skin' => ! rgar( $ckeditor_settings, 'setting_editor_skin' ) ? 'moono-lisa' :  rgar( $ckeditor_settings, 'setting_editor_skin' ),
			);

			wp_localize_script( 'itsg_gf_ckeditor', 'itsg_gf_ckeditor_settings', $settings_array );

			// Enqueued script with localized data.
			wp_enqueue_script( 'itsg_gf_ckeditor' );

		} // END localize_scripts

	/**
	 * END: patch to allow JS and CSS to load when loading forms through wp-ajax requests
	 *
	 */

		function entries_column_filter( $value, $form_id, $field_id, $entry, $query_string ) {
			$form = GFAPI::get_form( $form_id );
			foreach ( $form['fields'] as $field ) {
				if ( $field->id == $field_id && $this->is_wysiwyg_ckeditor( $field ) ) {
					return  substr( wp_kses_post( htmlspecialchars_decode( strip_tags( $value, '<strong><a><u><i>' ) ) ), 0, 140);
				}
			}
			return $value;
		}

		function itsg_gf_wysiwyg_ckeditor_upload() {
			$CKEditorFuncNum = isset( $_GET['CKEditorFuncNum'] ) ? $_GET['CKEditorFuncNum'] : null;
			if ( is_null( $CKEditorFuncNum ) ) {
				die( "<script>
				window.parent.CKEDITOR.tools.callFunction(
				'',
				'',
				'ERROR: Failed to pass CKEditorFuncNum');
				</script>" );
			}

			$form_id = isset( $_GET['form_id'] ) ? $_GET['form_id'] : null;

			if ( is_null( $form_id ) ) {
				die( "<script>
				window.parent.CKEDITOR.tools.callFunction('',
				'',
				'ERROR: Failed to get form_id');
				</script>" );
			}

			// get target path - also responsible for creating directories if path doesnt exist
			$target = GFFormsModel::get_file_upload_path( $form_id, null );
			$target_path = pathinfo( $target['path'] );
			$target_url = pathinfo( $target['url'] );

			// get Ajax Upload options
			$ckeditor_settings = self::get_options();

			// calculate file size in KB from MB
			$file_size = $ckeditor_settings['setting_upload_filesize'];
			$file_size_kb = $file_size * 1024 * 1024;

			// push options to upload handler
			$options = array(
				'paramName' => 'upload',
				'param_name' => 'upload',
				'CKEditorFuncNum' => $CKEditorFuncNum,
				'upload_dir' => $target_path['dirname'].'/',
				'upload_url' => $target_url['dirname'].'/',
				'image_versions' => array(
					'' => array(
					'max_width' => empty( $ckeditor_settings['setting_upload_filewidth'] ) ? null : $ckeditor_settings['setting_upload_filewidth'],
					'max_height' => empty( $ckeditor_settings['setting_upload_fileheight'] ) ? null : $ckeditor_settings['setting_upload_fileheight'],
					'jpeg_quality' => empty( $ckeditor_settings['setting_upload_filejpegquality'] ) ? null : $ckeditor_settings['setting_upload_filejpegquality']
					)
				),
				'accept_file_types' => empty( $ckeditor_settings['setting_upload_filetype'] ) ? '/(\.|\/)(png|tif|jpeg|jpg|gif)$/i' : '/(\.|\/)('.$ckeditor_settings['setting_upload_filetype'].')$/i',
				'max_file_size' => empty( $file_size_kb ) ? null : $file_size_kb
			);

			if ( class_exists( 'ITSG_GFCKEDITOR_UploadHandler' ) ) {
				// initialise the upload handler and pass the options
				$upload_handler = new ITSG_GFCKEDITOR_UploadHandler( $options );
			}

			// terminate the function
			die();
		} // END itsg_gf_wysiwyg_ckeditor_upload

		/*
		 *   Changes the upload path for Gravity Form uploads.
		 *   Changes made by this function will be seen when the Gravity Forms function  GFFormsModel::get_file_upload_path() is called.
		 *   The default upload path applied by this function matches the default for Gravity forms:
		 *   /gravity_forms/{form_id}-{hashed_form_id}/{month}/{year}/
		 */
		function change_upload_path( $path_info, $form_id ) {
			$ckeditor_settings = self::get_options();
			$file_dir = $ckeditor_settings['setting_upload_filedir'];

			if ( 0 != strlen( $file_dir ) ) {
				// Generate the yearly and monthly dirs
				$time            = current_time( 'mysql' );
				$y               = substr( $time, 0, 4 );
				$m               = substr( $time, 5, 2 );

				// removing leading forward slash, if present
				if( '/' == $file_dir[0] ) {
					$file_dir = ltrim( $file_dir, '/' );
				}

				// remove leading forward slash, if present
				if( '/' == substr( $file_dir, -1 ) ) {
					$file_dir = rtrim( $file_dir, '/' );
				}

				// if {form_id} keyword used, replace with current form id
				if ( false !== strpos( $file_dir, '{form_id}' ) ) {
					$file_dir = str_replace( '{form_id}', $form_id, $file_dir );
				}

				// if {hashed_form_id} keyword used, replace with hashed current form id
				if ( false !== strpos( $file_dir, '{hashed_form_id}' ) ) {
					$file_dir = str_replace( '{hashed_form_id}', wp_hash( $form_id), $file_dir );
				}

				// if {year} keyword used, replace with current year
				if ( false !== strpos($file_dir,'{year}') ) {
					$file_dir = str_replace( '{year}', $y, $file_dir );
				}

				// if {month} keyword used, replace with current month
				if ( false !== strpos( $file_dir, '{month}' ) ) {
					$file_dir = str_replace( '{month}', $m, $file_dir );
				}

				// if {user_id} keyword used, replace with current user id
				if ( false !== strpos( $file_dir, '{user_id}' ) ) {
					if ( isset( $_POST['entry_user_id'] ) ) {
						$entry_user_id = $_POST['entry_user_id'];
						$file_dir = str_replace( '{user_id}', $entry_user_id, $file_dir );
					} else {
						$user_id = get_current_user_id() ? get_current_user_id() : '0';
						$file_dir = str_replace( '{user_id}', $user_id, $file_dir );
					}
				}

				// if {hashed_user_id} keyword used, replace with hashed current user id
				if ( false !== strpos( $file_dir, '{hashed_user_id}' ) ) {
					if ( isset( $_POST['entry_user_id'] ) ) {
						$entry_user_id = $_POST['entry_user_id'];
						$hashed_entry_user_id = wp_hash( $entry_user_id );
						$file_dir = str_replace( '{hashed_user_id}', $hashed_entry_user_id, $file_dir );
					} else {
						$hashed_user_id = wp_hash( is_user_logged_in() ? get_current_user_id() : '0' );
						$file_dir = str_replace( '{hashed_user_id}', $hashed_user_id, $file_dir );
					}
				}

				$upload_dir = wp_upload_dir(); // get WordPress upload directory information - returns an array

				$path_info['path']	= $upload_dir['basedir'].'/'.$file_dir.'/';  // set the upload path
				$path_info['url']	= $upload_dir['baseurl'].'/'.$file_dir.'/';  // set the upload URL
			}
			return $path_info;
		} // END change_upload_path

		/*
		 *   Converts php.ini memory limit string to bytes.
		 *   For example, 2MB would convert to 2097152
		 */
		public static function return_bytes( $val ) {
			$val = trim( $val );
			$last = strtolower( $val[ strlen( $val ) -1 ] );

			switch( $last ) {
				case 'g':
					$val *= 1024;
				case 'm':
					$val *= 1024;
				case 'k':
					$val *= 1024;
			}
			return $val;
		} // END return_bytes

		/*
		 *   Determines the maximum upload file size.
		 *   Retrieves three values from php.ini and returns the smallest.
		 */
		public static function max_file_upload_in_bytes() {
			//select maximum upload size
			$max_upload = self::return_bytes( ini_get( 'upload_max_filesize' ) );
			//select post limit
			$max_post = self::return_bytes( ini_get( 'post_max_size' ) );
			//select memory limit
			$memory_limit = self::return_bytes( ini_get( 'memory_limit' ) );
			// return the smallest of them, this defines the real limit
			return min( $max_upload, $max_post, $memory_limit );
		} // END max_file_upload_in_bytes

		/*
         * Add 'Settings' link to plugin in WordPress installed plugins page
         */
		function plugin_action_links( $links ) {

			$action_links = array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=gf_settings&subview=WYSIWYG+CKEditor' ) . '" >' . __( 'Settings', 'gravity-forms-wysiwyg-ckeditor' ) . '</a>',
			);

			return array_merge( $action_links, $links );
		} // END plugin_action_links

		/*
		 *   Handles the plugin options.
		 *   Default values are stored in an array.
		 */
		public static function get_options() {
			$defaults = array(
				'enable_in_form_editor' => 'on',
				'enable_bold' => 'on',
				'enable_italic' => 'on',
				'enable_underline' => 'on',
				'enable_pastetext' => 'on',
				'enable_pastefromword' => 'on',
				'enable_numberedlist' => 'on',
				'enable_bulletedlist' => 'on',
				'enable_outdent' => 'on',
				'enable_indent' => 'on',
				'enable_link' => 'on',
				'enable_unlink' => 'on',
				'enable_format' => 'on',
				'enable_font' => 'on',
				'enable_fontsize' => 'on',
				'setting_upload_filesize' => '2',
				'setting_upload_filetype' => 'png|tif|jpeg|jpg|gif',
				'setting_upload_filedir' => '/gravity_forms/{form_id}-{hashed_form_id}/{month}/{year}/',
				'setting_upload_filejpegquality' => '75',
				'setting_upload_filewidth' => '786',
				'setting_upload_fileheight' => '786',
				'enable_upload_image' => 'off',
				'setting_editor_height' => '200',
				'setting_scayt_language' => 'en_US',
				'setting_link_target' => 'current_window',
				'setting_editor_skin' => 'moono-lisa',
			);

			$options = wp_parse_args( get_option( 'ITSG_gf_wysiwyg_ckeditor_settings' ), $defaults );

			return $options;
		} // END get_options

		/*
         * Customises 'Paragraph Text' field output to
		 *  1. apply 'gform_wysiwyg_ckeditor' class to ckeditor fields in the wp-admin
		 *  2. include character limit details and CSS class for admin area
         */
		public function ckeditor_field_content( $content, $field, $value, $lead_id, $form_id ){
			if ( $this->is_wysiwyg_ckeditor( $field ) ) {
				if ( is_admin() ){
					$content = str_replace( "class='", "class='gform_wysiwyg_ckeditor ", $content );
				} else {
					$label = rgar( $field, 'label' );
					$limit = ( '' == rgar( $field, 'maxLength' ) ? 'unlimited' : rgar( $field, 'maxLength' ) );
					$content = str_replace( "<textarea ", "<textarea data-maxlen='".$limit."' ", $content);
				}
			}
			return $content;
		} // END ckeditor_field_content

		/*
         * Customises character limit count down for NON-CKEditor fields to match what CKEditor provides
		 * - note that these fields DO count spaces, where as CKEDitor does NOT count spaces.
		 * Output: Characters: [number of characters in field]/[limit on field]
         */
		public function ckeditor_counter_script_js( $script, $form_id, $input_id, $max_length ){
			$field_id_number = substr( $input_id, strrpos($input_id, '_') + 1);
			$form = GFFormsModel::get_form_meta( $form_id );
			$field = GFFormsModel::get_field( $form, $field_id_number );

			if ( $this->is_wysiwyg_ckeditor( $field ) ) {
				return "";
			} else {
				$script = "jQuery('#{$input_id}').textareaCount(" .
							"    {" .
							"    'maxCharacterSize': {$max_length}," .
							"    'originalStyle': 'ginput_counter'," .
							"    'displayFormat' : '" .  esc_js( __( 'Characters', 'gravity-forms-wysiwyg-ckeditor' ) ) . ": #input/$max_length'" .
							"    });";
				return $script;
			}
		} // END ckeditor_counter_script_js

		/*
         * Applies CSS class to 'Paragraph text' fields when CKEditor is enabled
         */
		public function ckeditor_field_css_class( $classes, $field, $form ) {
			if ( $this->is_wysiwyg_ckeditor( $field ) ) {
				 $classes .= ' gform_wysiwyg_ckeditor';
			}
            return $classes;
        } // END ckeditor_field_css_class

		/*
         * Applies 'Enable WYSIWYG CKEditor' option to 'Paragraph Text' field
         */
		public function ckeditor_field_settings($position, $form_id) {
			if ( 25 == $position ) {
				?>
				<li class="wysiwyg_field_setting_wysiwyg_ckeditor field_setting" style="display:list-item;">
					<input type="checkbox" id="field_enable_wysiwyg_ckeditor"/>
					<label for="field_enable_wysiwyg_ckeditor" class="inline">
						<?php _e( 'Enable WYSIWYG (CKEditor)', 'gravity-forms-wysiwyg-ckeditor' ); ?>
					</label>
					<?php gform_tooltip( 'form_field_enable_wysiwyg_ckeditor' ) ?><br/>
				</li>
			<?php
			}
		} // END ckeditor_field_settings

		/*
         * Tooltip for field in form editor
         */
		public function ckeditor_field_tooltips( $tooltips ){
			$tooltips['form_field_enable_wysiwyg_ckeditor'] = '<h6>'.__( 'Enable WYSIWYG', 'gravity-forms-wysiwyg-ckeditor' ).'</h6>'.__( 'Check this box to turn this field into a WYSIWYG editor, using CKEditor.', 'gravity-forms-wysiwyg-ckeditor' );
			return $tooltips;
		} // END ckeditor_field_tooltips

		/*
         * Checks if field is CKEditor enabled
         */
		public function is_wysiwyg_ckeditor( $field ) {
			$field_type = self::get_type( $field );
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
         * Get field type
         */
		private static function get_type( $field ) {
			$type = '';
			if ( isset( $field['type'] ) ) {
				$type = $field['type'];
				if ( 'post_custom_field' == $type ) {
					if ( isset( $field['inputType'] ) ) {
						$type = $field['inputType'];
					}
				}
				return $type;
			}
		} // END get_type

		/*
         * Modifies the value before saved to the database - removes line spaces
         */
		public function save_field_value( $value, $lead, $field, $form ) {
			if ( $this->is_wysiwyg_ckeditor( $field ) ) {
				$value = rgpost( "input_{$field['id']}" );
				$value = preg_replace( "/\r|\n/", "", $value );
			}
			return $value;
		} // END save_field_value

		/*
         * Warning message if Gravity Forms is installed and enabled
         */
		public static function admin_warnings() {
			if ( !self::is_gravityforms_installed() ) {
				printf(
					'<div class="error"><h3>%s</h3><p>%s</p><p>%s</p></div>',
						__( 'Warning', 'gravity-forms-wysiwyg-ckeditor' ),
						sprintf ( __( 'The plugin %s requires Gravity Forms to be installed.', 'gravity-forms-wysiwyg-ckeditor' ), '<strong>'.self::$name.'</strong>' ),
						sprintf ( esc_html__( 'Please %sdownload the latest version of Gravity Forms%s and try again.', 'gravity-forms-wysiwyg-ckeditor' ), '<a href="https://www.e-junkie.com/ecom/gb.php?cl=54585&c=ib&aff=299380" target="_blank" >', '</a>' )
				);
			}
		} // END admin_warnings

		/*
         * Warning message if Gravity Forms is installed and enabled
         */
		public static function admin_warnings_minimum_php_version() {
				printf(
					'<div class="error"><h3>%s</h3><p>%s</p><p>%s</p></div>',
						__( 'Warning', 'gravity-forms-wysiwyg-ckeditor' ),
						sprintf( __( 'The <strong>image upload</strong> feature requires a minimum of PHP version 5.4.', 'gravity-forms-wysiwyg-ckeditor' ) ),
						sprintf( __( 'You are running an PHP version %s. Contact your web hosting provider to update.', 'gravity-forms-wysiwyg-ckeditor' ), phpversion() )
				);
		} // END admin_warnings_minimum_php_version

		/*
         * Check if GF is installed
         */
        private static function is_gravityforms_installed() {
			return class_exists( 'GFCommon' );
        } // END is_gravityforms_installed

		/*
         * Check if PHP version is at least 5.4
         */
        private static function is_minimum_php_version() {
			return version_compare( phpversion(), '5.4', '>=' );
        } // END is_minimum_php_version

		/*
         * Check if Gravity Forms - Data Persistence Reloaded is installed
         */
        private function is_dpr_installed() {
            return function_exists( 'ri_gfdp_ajax' );
        } // END is_dpr_installed

		/*
         * Get plugin url
         */
		 private function get_base_url(){
			return plugins_url( null, __FILE__ );
		} // END get_base_url

		/*
         * decodes the value before being displayed in the front end confirmation - for gravity wiz better pre-confirmation
         */
		public function decode_wysiwyg_frontend_confirmation( $value, $merge_tag, $modifier, $field, $raw_value ) {
			if ( $this->is_wysiwyg_ckeditor( $field ) ) {
				return wp_kses_post( $raw_value );
			}
			return $value;
		} // END decode_wysiwyg_frontend_confirmation

		/*
         * decodes the value before being displayed in the entry editor and Gravity PDF 3.x
         */
		public function decode_wysiwyg_backend_and_gravitypdf( $value, $field, $lead, $form ) {
			if ( $this->is_wysiwyg_ckeditor( $field ) ) {
				return  wp_kses_post( htmlspecialchars_decode( $value ) );
			}
			return $value;
		} // END decode_wysiwyg_backend_and_gravitypdf

    }
    $ITSG_GF_WYSIWYG_CKEditor = new ITSG_GF_WYSIWYG_CKEditor();
}