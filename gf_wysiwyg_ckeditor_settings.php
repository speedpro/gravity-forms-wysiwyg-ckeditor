<?php

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( !class_exists( 'ITSG_GF_WYSIWYG_CKEditor_Settings_Page' ) ) {
	class ITSG_GF_WYSIWYG_CKEditor_Settings_Page {

	/*
    * Settings page
    */
	 public static function settings_page(){

		   $ckeditor_settings = ITSG_GF_WYSIWYG_CKEditor::get_options();

			$is_submit = rgpost( 'itsg_gf_wysiwyg_ckeditor_settings_submit' );

			if( $is_submit ){
			/* ENABLE IN FORM EDITOR */
				$ckeditor_settings['enable_in_form_editor'] = rgpost( 'in_form_editor' );
				$ckeditor_settings['enable_remove_elementspath'] = rgpost( 'remove_elementspath' );
			/* SOURCE */
				$ckeditor_settings['enable_source'] = rgpost( 'source' );
			/* BASIC STYLES */
				$ckeditor_settings['enable_bold'] = rgpost( 'bold' );
				$ckeditor_settings['enable_italic'] = rgpost( 'italic' );
				$ckeditor_settings['enable_underline'] = rgpost( 'underline' );
				$ckeditor_settings['enable_strike'] = rgpost( 'strike' );
				$ckeditor_settings['enable_subscript'] = rgpost( 'subscript' );
				$ckeditor_settings['enable_superscript'] = rgpost( 'superscript' );
				$ckeditor_settings['enable_removeformat'] = rgpost( 'removeformat' );
			/* CLIPBOARD */
				$ckeditor_settings['enable_cut'] = rgpost( 'cut' );
				$ckeditor_settings['enable_copy'] = rgpost( 'copy' );
				$ckeditor_settings['enable_paste'] = rgpost( 'paste' );
				$ckeditor_settings['enable_pastetext'] = rgpost( 'pastetext' );
				$ckeditor_settings['enable_pastefromword'] = rgpost( 'pastefromword' );
				$ckeditor_settings['enable_undo'] = rgpost( 'undo' );
				$ckeditor_settings['enable_redo'] = rgpost( 'redo' );
			/* PARAGRAPH */
				$ckeditor_settings['enable_numberedlist'] = rgpost( 'numberedlist' );
				$ckeditor_settings['enable_bulletedlist'] = rgpost( 'bulletedlist' );
				$ckeditor_settings['enable_outdent'] = rgpost( 'outdent' );
				$ckeditor_settings['enable_indent'] = rgpost( 'indent' );
				$ckeditor_settings['enable_blockquote'] = rgpost( 'blockquote' );
				$ckeditor_settings['enable_creatediv'] = rgpost( 'creatediv' );
				$ckeditor_settings['enable_justifyleft'] = rgpost( 'justifyleft' );
				$ckeditor_settings['enable_justifycenter'] = rgpost( 'justifycenter' );
				$ckeditor_settings['enable_justifyright'] = rgpost( 'justifyright' );
				$ckeditor_settings['enable_justifyblock'] = rgpost( 'justifyblock' );
				$ckeditor_settings['enable_bidiltr'] = rgpost( 'bidiltr' );
				$ckeditor_settings['enable_bidirtl'] = rgpost( 'bidirtl' );
				$ckeditor_settings['enable_language'] = rgpost( 'language' );
			/* LINKS */
				$ckeditor_settings['enable_link'] = rgpost( 'link' );
				$ckeditor_settings['setting_link_target'] = rgpost( 'link_target' );
				$ckeditor_settings['enable_unlink'] = rgpost( 'unlink' );
				$ckeditor_settings['enable_anchor'] = rgpost( 'anchor' );
				$ckeditor_settings['enable_oembed'] = rgpost( 'oembed' );
			/* DOCUMENT */
				$ckeditor_settings['enable_preview'] = rgpost( 'preview' );
				$ckeditor_settings['enable_print'] = rgpost( 'print' );
			/* EDITING */
				$ckeditor_settings['enable_find'] = rgpost( 'find' );
				$ckeditor_settings['enable_replace'] = rgpost( 'replace' );
				$ckeditor_settings['enable_selectall'] = rgpost( 'selectall' );
				$ckeditor_settings['enable_scayt'] = rgpost( 'scayt' );
				$ckeditor_settings['setting_scayt_language'] = rgpost( 'scayt_language' );
			/* INSERT */
				$ckeditor_settings['enable_image'] = rgpost( 'image' );
			/* IMAGE ULOAD */
				$ckeditor_settings['enable_upload_image'] = rgpost( 'upload_image' );
				$ckeditor_settings['setting_upload_filesize'] = rgpost( 'upload_filesize' );
				$ckeditor_settings['setting_upload_filetype'] = rgpost( 'upload_filetype' );
				$ckeditor_settings['setting_upload_filedir'] = rgpost( 'upload_filedir' );
				$ckeditor_settings['setting_upload_filejpegquality'] = rgpost( 'upload_filejpegquality' );
				$ckeditor_settings['setting_upload_filewidth'] = rgpost( 'upload_filewidth' );
				$ckeditor_settings['setting_upload_fileheight'] = rgpost( 'upload_fileheight' );
				$ckeditor_settings['enable_flash'] = rgpost( 'flash' );
				$ckeditor_settings['enable_table'] = rgpost( 'table' );
				$ckeditor_settings['enable_horizontalrule'] = rgpost( 'horizontalrule' );
				$ckeditor_settings['enable_smiley'] = rgpost( 'smiley' );
				$ckeditor_settings['enable_specialchar'] = rgpost( 'specialchar' );
				$ckeditor_settings['enable_pagebreak'] = rgpost( 'pagebreak' );
				$ckeditor_settings['enable_iframe'] = rgpost( 'iframe' );
			/* STYLES */
				$ckeditor_settings['enable_styles'] = rgpost( 'styles' );
				$ckeditor_settings['enable_format'] = rgpost( 'format' );
				$ckeditor_settings['enable_font'] = rgpost( 'font' );
				$ckeditor_settings['enable_fontsize'] = rgpost( 'fontsize' );
			/* COLOURS */
				$ckeditor_settings['enable_textcolor'] = rgpost( 'textcolor' );
				$ckeditor_settings['enable_bgcolor'] = rgpost( 'bgcolor' );
			/* TOOLS */
				$ckeditor_settings['enable_maximize'] = rgpost( 'maximize' );
				$ckeditor_settings['enable_showblocks'] = rgpost( 'showblocks' );
			/* ABOUT */
				$ckeditor_settings['enable_about'] = rgpost( 'about' );
			/* LAYOUT */
				$ckeditor_settings['setting_editor_height'] = rgpost( 'editor_height' );
				$ckeditor_settings['setting_editor_skin'] = rgpost( 'editor_skin' );
				$ckeditor_settings['enable_count_spaces'] = rgpost( 'count_spaces' );

				update_option( 'itsg_gf_wysiwyg_ckeditor_settings', $ckeditor_settings );
			}

			?>

			<form method="post">
				<?php wp_nonce_field( 'update', 'itsg_gf_wysiwyg_ckeditor_update' ) ?>
				<input type="hidden" value="1" name="itsg_gf_wysiwyg_ckeditor_settings_submit" />
				<h3><?php _e( 'WYSIWYG CKEditor settings', 'gravity-forms-wysiwyg-ckeditor' ) ?></h3>
				<h4><?php _e( 'Form editor settings', 'gravity-forms-wysiwyg-ckeditor' ) ?></h4>
				<ul>
					<li>
						<input type="checkbox" id="in_form_editor" name="in_form_editor" <?php echo rgar( $ckeditor_settings, 'enable_in_form_editor' ) ? "checked='checked'" : "" ?>  >
						<label for="in_form_editor"><?php _e( 'Enable in form editor', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
					</li>
					<li>
						<input type="checkbox" id="remove_elementspath" name="remove_elementspath" <?php echo rgar( $ckeditor_settings, 'enable_remove_elementspath' ) ? "checked='checked'" : "" ?>  >
						<label for="remove_elementspath"><?php _e( 'Remove elements path (body p)', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
					</li>
				</ul>
				<h4><?php _e( 'Toolbar settings', 'gravity-forms-wysiwyg-ckeditor' ) ?></h4>
				<fieldset>
					<legend><?php _e( 'Source', 'gravity-forms-wysiwyg-ckeditor' ) ?></legend>
						 <div>
							<ul>
							   <li>
								   <input type="checkbox" id="source" name="source" <?php echo rgar( $ckeditor_settings, 'enable_source' ) ? "checked='checked'" : "" ?>  >
								   <label for="source"><?php _e( 'Source code', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
							   </li>
							</ul>
						 </div>
					</fieldset>
				<fieldset>
				<legend><?php _e( 'Basic styles', 'gravity-forms-wysiwyg-ckeditor' ) ?></legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="bold" name="bold" <?php echo rgar( $ckeditor_settings, 'enable_bold' ) ? "checked='checked'" : "" ?> >
							<label for="bold"><?php _e( 'Bold', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="italic" name="italic" <?php echo rgar( $ckeditor_settings, 'enable_italic' ) ? "checked='checked'" : "" ?> >
							<label for="italic"><?php _e( 'Italic', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="underline" name="underline" <?php echo rgar( $ckeditor_settings, 'enable_underline' ) ? "checked='checked'" : "" ?> >
							<label for="underline"><?php _e( 'Underline', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="strike" name="strike" <?php echo rgar( $ckeditor_settings, 'enable_strike' ) ? "checked='checked'" : "" ?> >
							<label for="strike"><?php _e( 'Strike', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="subscript" name="subscript" <?php echo rgar( $ckeditor_settings, 'enable_subscript' ) ? "checked='checked'" : "" ?> >
							<label for="subscript"><?php _e( 'Subscript', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="superscript" name="superscript" <?php echo rgar( $ckeditor_settings, 'enable_superscript' ) ? "checked='checked'" : "" ?> >
							<label for="superscript"><?php _e( 'Superscript', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="removeformat" name="removeformat" <?php echo rgar( $ckeditor_settings, 'enable_removeformat' ) ? "checked='checked'" : "" ?> >
							<label for="removeformat"><?php _e( 'Remove format', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend><?php _e( 'Clipboard', 'gravity-forms-wysiwyg-ckeditor' ) ?></legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="cut" name="cut" <?php echo rgar( $ckeditor_settings, 'enable_cut' ) ? "checked='checked'" : "" ?> >
							<label for="cut"><?php _e( 'Cut', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="copy" name="copy" <?php echo rgar( $ckeditor_settings, 'enable_copy' ) ? "checked='checked'" : "" ?> >
							<label for="copy"><?php _e( 'Copy', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="paste" name="paste" <?php echo rgar( $ckeditor_settings, 'enable_paste' ) ? "checked='checked'" : "" ?> >
							<label for="paste"><?php _e( 'Paste', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="pastetext" name="pastetext" <?php echo rgar( $ckeditor_settings, 'enable_pastetext' ) ? "checked='checked'" : "" ?> >
							<label for="pastetext"><?php _e( 'Paste as text', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="pastefromword" name="pastefromword" <?php echo rgar( $ckeditor_settings, 'enable_pastefromword' ) ? "checked='checked'" : "" ?> >
							<label for="pastefromword"><?php _e( 'Paste from word', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="undo" name="undo" <?php echo rgar( $ckeditor_settings, 'enable_undo' ) ? "checked='checked'" : "" ?> >
							<label for="undo"><?php _e( 'Undo', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="redo" name="redo" <?php echo rgar( $ckeditor_settings, 'enable_redo' ) ? "checked='checked'" : "" ?> >
							<label for="redo"><?php _e( 'Redo', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend><?php _e( 'Paragraph', 'gravity-forms-wysiwyg-ckeditor' ) ?></legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="numberedlist" name="numberedlist" <?php echo rgar( $ckeditor_settings, 'enable_numberedlist' ) ? "checked='checked'" : "" ?> >
							<label for="numberedlist"><?php _e( 'Numbered list', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="bulletedlist" name="bulletedlist" <?php echo rgar( $ckeditor_settings, 'enable_bulletedlist' ) ? "checked='checked'" : "" ?> >
							<label for="bulletedlist"><?php _e( 'Bulleted list', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="outdent" name="outdent" <?php echo rgar( $ckeditor_settings, 'enable_outdent' ) ? "checked='checked'" : "" ?> >
							<label for="outdent"><?php _e( 'Outdent', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="indent" name="indent" <?php echo rgar( $ckeditor_settings, 'enable_indent' ) ? "checked='checked'" : "" ?> >
							<label for="indent"><?php _e( 'Indent', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="blockquote" name="blockquote" <?php echo rgar( $ckeditor_settings, 'enable_blockquote' ) ? "checked='checked'" : "" ?> >
							<label for="blockquote"><?php _e( 'Block quote', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="creatediv" name="creatediv" <?php echo rgar( $ckeditor_settings, 'enable_creatediv' ) ? "checked='checked'" : "" ?> >
							<label for="creatediv"><?php _e( 'Create div', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="justifyleft" name="justifyleft" <?php echo rgar( $ckeditor_settings, 'enable_justifyleft' ) ? "checked='checked'" : "" ?> >
							<label for="justifyleft"><?php _e( 'Justify left', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="justifycenter" name="justifycenter" <?php echo rgar( $ckeditor_settings, 'enable_justifycenter' ) ? "checked='checked'" : "" ?> >
							<label for="justifycenter"><?php _e( 'Justify center', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="justifyright" name="justifyright" <?php echo rgar( $ckeditor_settings, 'enable_justifyright' ) ? "checked='checked'" : "" ?> >
							<label for="justifyright"><?php _e( 'Justify right', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="justifyblock" name="justifyblock" <?php echo rgar( $ckeditor_settings, 'enable_justifyblock' ) ? "checked='checked'" : "" ?> >
							<label for="justifyblock"><?php _e( 'Justify block', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="bidiltr" name="bidiltr" <?php echo rgar( $ckeditor_settings, 'enable_bidiltr' ) ? "checked='checked'" : "" ?> >
							<label for="bidiltr"><?php _e( 'Bidirectional - left to right', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="bidirtl" name="bidirtl" <?php echo rgar( $ckeditor_settings, 'enable_bidirtl' ) ? "checked='checked'" : "" ?> >
							<label for="bidirtl"><?php _e( 'Bidirectional - right to left', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="language" name="language" <?php echo rgar( $ckeditor_settings, 'enable_language' ) ? "checked='checked'" : "" ?> >
							<label for="language"><?php _e( 'Language', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend><?php _e( 'Links', 'gravity-forms-wysiwyg-ckeditor' ) ?></legend>
					 <div>
						<ul>
							<li>
								<input type="checkbox" id="link" name="link" <?php echo rgar( $ckeditor_settings, 'enable_link' ) ? "checked='checked'" : "" ?> >
								<label for="link"><?php _e( 'Link', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
							</li>
							<li>
								<label for="link_target"><?php _e( 'Link target', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
								<p class="instructions"><?php _e( 'This option allows you to specify the link target when text is linked.', 'gravity-forms-wysiwyg-ckeditor' ) ?></p>
								<select name="link_target">
									<option value="current_window" <?php echo 'current_window' == rgar( $ckeditor_settings, 'setting_link_target' ) ? "selected='selected'" : "" ?>><?php _e( 'Open in current window', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="new_window" <?php echo 'new_window' == rgar( $ckeditor_settings, 'setting_link_target' ) ? "selected='selected'" : "" ?>><?php _e( 'Open in new window', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
								</select>
							</li>
						   <li>
								<input type="checkbox" id="unlink" name="unlink" <?php echo rgar( $ckeditor_settings, 'enable_unlink' ) ? "checked='checked'" : "" ?> >
								<label for="unlink"><?php _e( 'Unlink', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
								<input type="checkbox" id="anchor" name="anchor" <?php echo rgar( $ckeditor_settings, 'enable_anchor' ) ? "checked='checked'" : "" ?> >
								<label for="anchor"><?php _e( 'Anchor', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
								<input type="checkbox" id="oembed" name="oembed" <?php echo rgar( $ckeditor_settings, 'enable_oembed' ) ? "checked='checked'" : "" ?> >
								<label for="oembed"><?php _e( 'Media (oEmbed) Plugin', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend><?php _e( 'Document', 'gravity-forms-wysiwyg-ckeditor' ) ?></legend>
					 <div>
						<ul>
						   <li>
								<input type="checkbox" id="preview" name="preview" <?php echo rgar( $ckeditor_settings, 'enable_preview' ) ? "checked='checked'" : "" ?> >
								<label for="preview"><?php _e( 'Preview', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
								<input type="checkbox" id="print" name="print" <?php echo rgar( $ckeditor_settings, 'enable_print' ) ? "checked='checked'" : "" ?> >
								<label for="print"><?php _e( 'Print', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
							</li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend><?php _e( 'Editing', 'gravity-forms-wysiwyg-ckeditor' ) ?></legend>
					 <div>
						<ul>
						   <li>
								<input type="checkbox" id="find" name="find" <?php echo rgar( $ckeditor_settings, 'enable_find' ) ? "checked='checked'" : "" ?> >
								<label for="find"><?php _e( 'Find', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
								<input type="checkbox" id="replace" name="replace" <?php echo rgar( $ckeditor_settings, 'enable_replace' ) ? "checked='checked'" : "" ?> >
								<label for="replace"><?php _e( 'Replace', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
							</li>
							<li>
								<input type="checkbox" id="selectall" name="selectall" <?php echo rgar( $ckeditor_settings, 'enable_selectall' ) ? "checked='checked'" : "" ?> >
								<label for="selectall"><?php _e( 'Select all', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
							</li>
							<li>
							  <input type="checkbox" id="scayt" name="scayt" <?php echo rgar( $ckeditor_settings, 'enable_scayt' ) ? "checked='checked'" : "" ?> >
							  <label for="scayt"><?php _e( 'Spell check as you type (SCAYT)', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
							</li>
							<li>
								<label for="scayt_language"><?php _e( 'Spell check language', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
								<select name="scayt_language">
									<option value="en_US" <?php echo 'en_US' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'American English', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="pt_BR" <?php echo 'pt_BR' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'Brazilian Portuguese', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="en_GB" <?php echo 'en_GB' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'British English', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="en_CA" <?php echo 'en_CA' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'Canadian English', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="fr_CA" <?php echo 'fr_CA' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'Canadian French', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="da_DK" <?php echo 'da_DK' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'Danish', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="nl_NL" <?php echo 'nl_NL' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'Dutch', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="fi_FI" <?php echo 'fi_FI' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'Finnish', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="fr_FR" <?php echo 'fr_FR' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'French', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="de_DE" <?php echo 'de_DE' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'German', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="el_GR" <?php echo 'el_GR' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'Greek', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="it_IT" <?php echo 'it_IT' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'Italian', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="la_VA" <?php echo 'la_VA' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'Latin', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="nb_NO" <?php echo 'nb_NO' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'Norwegian Bokmål', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="pt_PT" <?php echo 'pt_PT' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'Portuguese', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="es_ES" <?php echo 'es_ES' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'Spanish', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="sv_SE" <?php echo 'sv_SE' == rgar( $ckeditor_settings, 'setting_scayt_language' ) ? "selected='selected'" : "" ?>><?php _e( 'Swedish', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
								</select>
							</li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend><?php _e( 'Insert', 'gravity-forms-wysiwyg-ckeditor' ) ?></legend>
					 <div>
						<ul>
						   <li>
								<input type="checkbox" id="image" name="image" <?php echo rgar( $ckeditor_settings, 'enable_image' ) ? "checked='checked'" : "" ?> >
								<label for="image"><?php _e( 'Image', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
								<input type="checkbox" id="upload_image" name="upload_image" <?php echo rgar( $ckeditor_settings, 'enable_upload_image' ) ? "checked='checked'" : "" ?> >
								<label for="upload_image"><?php _e( 'Image upload', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						    <li>
							<div id="upload_image_settings">
								<p><strong><?php _e( 'Image upload settings', 'gravity-forms-wysiwyg-ckeditor') ?></strong></p>
								<label for="upload_filesize" style="display: block; width: 200px;font-weight: 800;"><?php _e( 'Maximum file size (MB)', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
								<?php
								$server_upload_limit_bytes = ITSG_GF_WYSIWYG_CKEditor::max_file_upload_in_bytes();
								$server_upload_limit_megabytes = $server_upload_limit_bytes / 1024 / 1024;
								?>
								<input type="number" min="0" max="<?php echo $server_upload_limit_megabytes; ?>" id="upload_filesize" name="upload_filesize" value="<?php echo rgar( $ckeditor_settings, 'setting_upload_filesize' ) ?>" >
								<p class="instructions"><?php _e( 'This is the maximum file size that can be uploaded in megabytes (MB). Note that this cannot be larger than the maximum as defined in your servers configuration.', 'gravity-forms-wysiwyg-ckeditor' ) ?></p>
								<p><?php printf( __( 'Your servers maximum upload file size is currently configured as %s MB.', 'gravity-forms-wysiwyg-ckeditor' ),  $server_upload_limit_megabytes ) ?> </p>
								<br>
								<label for="upload_filetype" style="display: block; width: 200px;font-weight: 800;"><?php _e( "Accepted file types", 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
								<input type="text" id="upload_filetype" name="upload_filetype" value="<?php echo rgar( $ckeditor_settings, 'setting_upload_filetype' ) ?>" >
								<p class="instructions"><?php _e( "Specify the file types that can be uploaded. These need to be the file extension separated with the vertical bar character '|'.", 'gravity-forms-wysiwyg-ckeditor' ) ?></p>
								<br>
								<label for="upload_filedir" style="display: block; width: 200px;font-weight: 800;"><?php _e( 'Upload file dir', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
								<input style="min-width: 500px;" type="text" id="upload_filedir" name="upload_filedir" value="<?php echo rgar( $ckeditor_settings, 'setting_upload_filedir' ) ?>" >
								<p class="instructions"><?php _e( 'This setting allows you to control where uploaded images are saved to on your server.', 'gravity-forms-wysiwyg-ckeditor' ) ?></p>
								<?php
								// Generate the yearly and monthly dirs
								$time            = current_time( 'mysql' );
								$y               = substr( $time, 0, 4 );
								$m               = substr( $time, 5, 2 );

								$wp_upload_dir = wp_upload_dir();
								$base_dir = $wp_upload_dir['basedir'];

								?>
								 <div>
									<p><?php _e( 'Keywords supported are:', 'gravity-forms-wysiwyg-ckeditor' ) ?>
										<br>{form_id} - <?php _e( 'for example', 'gravity-forms-wysiwyg-ckeditor' ) ?> '1'
										<br>{hashed_form_id} - <?php _e( 'for example', 'gravity-forms-wysiwyg-ckeditor' ) ?>  '<?php echo wp_hash(1);?>'
										<br>{user_id} - <?php _e( 'for example', 'gravity-forms-wysiwyg-ckeditor' ) ?>  '<?php echo get_current_user_id();?>' (<?php _e("note - if no user is logged in, this will be '0'", 'gravity-forms-wysiwyg-ckeditor' ) ?>)
										<br>{hashed_user_id} - <?php _e( 'for example', 'gravity-forms-wysiwyg-ckeditor' ) ?>  '<?php echo wp_hash(get_current_user_id() );?>' (<?php _e( 'note - if no user is logged in, this will be', 'gravity-forms-wysiwyg-ckeditor' ) ?> '<?php echo wp_hash(0);?>')
										<br>{year} - <?php _e( 'for example', 'gravity-forms-wysiwyg-ckeditor' ) ?>  '<?php echo $y;?>'
										<br>{month}	- <?php _e( 'for example', 'gravity-forms-wysiwyg-ckeditor' ) ?>  '<?php echo $m;?>'
									</p>
									<p><?php _e( 'If you set this field to', 'gravity-forms-wysiwyg-ckeditor' ) ?>
										<br><strong>/gravity_forms/{form_id}-{hashed_form_id}/{year}/{month}/</strong>
										<br><?php _e( 'Files will be uploaded to', 'gravity-forms-wysiwyg-ckeditor' ) ?>
										<br><strong><?php echo $base_dir . '/gravity_forms/1-' . wp_hash(1) .'/' . $y . '/' . $m ; ?></strong>
									</p>
								</div>
								<br>
								<label for="upload_filejpegquality" style="display: block; width: 200px;font-weight: 800;"><?php _e( 'Image JPEG quality', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
								<input type="text" id="upload_filejpegquality" name="upload_filejpegquality" value="<?php echo rgar( $ckeditor_settings, 'setting_upload_filejpegquality' ) ?>" >
								<p class="instructions"><?php _e( 'Uploaded images are processed before being saved. The JPEG quality controls the amount of compression applied.', 'gravity-forms-wysiwyg-ckeditor' ) ?></p>
								<br>
								<label for="upload_filewidth" style="display: block; width: 200px;font-weight: 800;"><?php _e( "Image file width", 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
								<input type="text" id="upload_filewidth" name="upload_filewidth" value="<?php echo rgar( $ckeditor_settings, 'setting_upload_filewidth' ) ?>" >
								<p class="instructions"><?php _e( 'Uploaded images can be reduced in size before being saved. This setting allows you to specify the MAXIMUM width for images. If the image will only be changed if it is larger.', 'gravity-forms-wysiwyg-ckeditor' ) ?></p>
								<br>
								<label for="upload_fileheight" style="display: block; width: 200px;font-weight: 800;"><?php _e( 'Image file height', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
								<input type="text" id="upload_fileheight" name="upload_fileheight" value="<?php echo rgar( $ckeditor_settings, 'setting_upload_fileheight' ) ?>" >
								<p class="instructions"><?php _e( 'Uploaded images can be reduced in size before being saved. This setting allows you to specify the MAXIMUM height for images. If the image will only be changed if it is larger.', 'gravity-forms-wysiwyg-ckeditor' ) ?></p>
							</div>
						   </li>
						   <li>
							  <input type="checkbox" id="flash" name="flash" <?php echo rgar( $ckeditor_settings, 'enable_flash' ) ? "checked='checked'" : "" ?> >
							  <label for="flash"><?php _e( 'Flash', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
							</li>
							<li>
							<input type="checkbox" id="table" name="table" <?php echo rgar( $ckeditor_settings, 'enable_table' ) ? "checked='checked'" : "" ?> >
							<label for="table"><?php _e( 'Table', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							  <input type="checkbox" id="horizontalrule" name="horizontalrule" <?php echo rgar( $ckeditor_settings, 'enable_horizontalrule' ) ? "checked='checked'" : "" ?> >
							  <label for="horizontalrule"><?php _e( 'Horizontal rule', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
							</li>
							<li>
							<input type="checkbox" id="smiley" name="smiley" <?php echo rgar( $ckeditor_settings, 'enable_smiley' ) ? "checked='checked'" : "" ?> >
							<label for="smiley"><?php _e( 'Smiley', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							  <input type="checkbox" id="specialchar" name="specialchar" <?php echo rgar( $ckeditor_settings, 'enable_specialchar' ) ? "checked='checked'" : "" ?> >
							  <label for="specialchar"><?php _e( 'Special character', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
							</li>
							<li>
							<input type="checkbox" id="pagebreak" name="pagebreak" <?php echo rgar( $ckeditor_settings, 'enable_pagebreak' ) ? "checked='checked'" : "" ?> >
							<label for="pagebreak"><?php _e( 'Page break', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							<input type="checkbox" id="iframe" name="iframe" <?php echo rgar( $ckeditor_settings, 'enable_iframe' ) ? "checked='checked'" : "" ?> >
							<label for="iframe"><?php _e( 'iframe', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend><?php _e( 'Styles', 'gravity-forms-wysiwyg-ckeditor' ) ?></legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="styles" name="styles" <?php echo rgar( $ckeditor_settings, 'enable_styles' ) ? "checked='checked'" : "" ?> >
							<label for="styles"><?php _e( 'Styles', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							  <input type="checkbox" id="format" name="format" <?php echo rgar( $ckeditor_settings, 'enable_format' ) ? "checked='checked'" : "" ?> >
							  <label for="format"><?php _e( 'Format', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
							</li>
							<li>
							<input type="checkbox" id="font" name="font" <?php echo rgar( $ckeditor_settings, 'enable_font' ) ? "checked='checked'" : "" ?> >
							<label for="font"><?php _e( 'Font', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							  <input type="checkbox" id="fontsize" name="fontsize" <?php echo rgar( $ckeditor_settings, 'enable_fontsize' ) ? "checked='checked'" : "" ?> >
							  <label for="fontsize"><?php _e( 'Font size', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
							</li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend><?php _e( 'Colours', 'gravity-forms-wysiwyg-ckeditor' ) ?></legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="textcolor" name="textcolor" <?php echo rgar( $ckeditor_settings, 'enable_textcolor' ) ? "checked='checked'" : "" ?> >
							<label for="textcolor"><?php _e( 'Text colour', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							  <input type="checkbox" id="bgcolor" name="bgcolor" <?php echo rgar( $ckeditor_settings, 'enable_bgcolor' ) ? "checked='checked'" : "" ?> >
							  <label for="bgcolor"><?php _e( 'Background colour', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
							</li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend><?php _e( 'Tools', 'gravity-forms-wysiwyg-ckeditor' ) ?></legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="maximize" name="maximize" <?php echo rgar( $ckeditor_settings, 'enable_maximize' ) ? "checked='checked'" : "" ?> >
							<label for="maximize"><?php _e( 'Maximize', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						   <li>
							  <input type="checkbox" id="showblocks" name="showblocks" <?php echo rgar( $ckeditor_settings, 'enable_showblocks' ) ? "checked='checked'" : "" ?> >
							  <label for="showblocks"><?php _e( 'Show blocks', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
							</li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend><?php _e( 'About', 'gravity-forms-wysiwyg-ckeditor' ) ?></legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="about" name="about" <?php echo rgar( $ckeditor_settings, 'enable_about' ) ? "checked='checked'" : "" ?> >
							<label for="about"><?php _e( 'About CKEditor', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						</ul>
					 </div>
				</fieldset>
				<h4><?php _e( 'Form editor settings', 'gravity-forms-wysiwyg-ckeditor' ) ?></h4>
					<div>
						<ul>
							<li>
								<label for="editor_height" style="display: block; width: 200px;font-weight: 800;"><?php _e( 'Editor height', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
								<input type="text" id="editor_height" name="editor_height" value="<?php echo rgar( $ckeditor_settings, 'setting_editor_height' ) ?>" >
								<p class="instructions"><?php _e( 'Default editor height in pixels (px). Editor can be resized by the user.', 'gravity-forms-wysiwyg-ckeditor' ) ?></p>
							</li>
							<li>
								<label for="editor_skin"><?php _e( 'Editor skin', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
								<p class="instructions"><?php _e( 'This option allows you to select the skin used by CKEditor. Note that options only include Moona and Moona-Lisa (the default themes provided with CKEditor).', 'gravity-forms-wysiwyg-ckeditor' ) ?></p>
								<select name="editor_skin">
									<option value="moono" <?php echo 'moono' == rgar( $ckeditor_settings, 'setting_editor_skin' ) ? "selected='selected'" : "" ?>><?php _e( 'Moono', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
									<option value="moono-lisa" <?php echo 'moono-lisa' == rgar( $ckeditor_settings, 'setting_editor_skin' ) ? "selected='selected'" : "" ?>><?php _e( 'Moono-Lisa', 'gravity-forms-wysiwyg-ckeditor' ) ?></option>
								</select>
							</li>
							<li>
								<input type="checkbox" id="count_spaces" name="count_spaces" <?php echo rgar( $ckeditor_settings, 'enable_count_spaces' ) ? "checked='checked'" : "" ?> >
								<label for="count_spaces"><?php _e( 'Count spaces as characters', 'gravity-forms-wysiwyg-ckeditor' ) ?></label>
						   </li>
						</ul>

					 </div>

				<input type="submit" name="save settings" value="<?php _e( 'Save Settings', 'gravity-forms-wysiwyg-ckeditor' ) ?>" class="button-primary" style="margin-top:40px;" />
			</form>

		   <?php

		}
	}
}