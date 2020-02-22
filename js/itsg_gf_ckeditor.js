function itsg_gf_wysiwyg_ckeditor_function(self){
	if ( '1' == itsg_gf_ckeditor_settings.is_form_editor ) {
		// destroy any existing CKEditor instances
		for( i in CKEDITOR.instances ) {
			CKEDITOR.instances[i].destroy();
		}
	}
	(function( $ ) {
		'use strict';
		var ckeditor_fields = itsg_gf_ckeditor_settings.ckeditor_fields;
		for ( var key in ckeditor_fields ) {

			// skip loop if the property is from prototype
			if ( !ckeditor_fields.hasOwnProperty( key ) ) continue;

			var obj = ckeditor_fields[ key ];

			var field_id = key;

			console.log( 'gravity-forms-wysiwyg-ckeditor :: field_id: ' + field_id );

			var toolbar_source =  obj[ 'source' ];
			var toolbar_basicstyles = obj[ 'basicstyles' ];
			var toolbar_clipboard = obj[ 'clipboard' ];
			var toolbar_paragraph = obj[ 'paragraph' ];
			var toolbar_links = obj[ 'links' ];
			var toolbar_document = obj[ 'document' ];
			var toolbar_editing = obj[ 'editing' ];
			var toolbar_insert = obj[ 'insert' ];
			var toolbar_styles = obj[ 'styles' ];
			var toolbar_colors = obj[ 'colors' ];
			var toolbar_tools = obj[ 'tools' ];
			var toolbar_about = obj[ 'about' ];

			var extra_plugins = itsg_gf_ckeditor_settings.extra_plugins;

			var remove_plugins = itsg_gf_ckeditor_settings.remove_plugins;

			var editor_height = itsg_gf_ckeditor_settings.editor_height;

			var enable_scayt = itsg_gf_ckeditor_settings.enable_scayt ? true : false;
			var scayt_language = itsg_gf_ckeditor_settings.scayt_language;

			var editor_skin = itsg_gf_ckeditor_settings.editor_skin;

			var enable_count_spaces = itsg_gf_ckeditor_settings.enable_count_spaces ? true : false;

			$(function(){
				$('.gform_wrapper .gform_wysiwyg_ckeditor:not(.wysiwyg_exclude) textarea:not([disabled="disabled"],.wysiwyg_exclude), .gform_wrapper .gform_page:not([style="display:none;"],.wysiwyg_exclude) .gform_wysiwyg_ckeditor:not(.wysiwyg_exclude) textarea:not([disabled=disabled],.wysiwyg_exclude), #field_settings textarea:not([disabled=disabled],#gfield_bulk_add_input,#field_calculation_formula,.wysiwyg_exclude,#field_placeholder_textarea), .gf_entry_wrap .postbox .gform_wysiwyg_ckeditor:not(.wysiwyg_exclude) textarea:not([disabled=disabled],.wysiwyg_exclude)').each(function() {
					$(this).ckeditor(CKEDITOR.tools.extend( {
						scayt_autoStartup : enable_scayt,
						scayt_sLang : scayt_language,
						scayt_disableOptionsStorage : 'lang',
						height : editor_height + 'px',
						linkShowTargetTab : false,
						skin : editor_skin,
						extraPlugins :
							extra_plugins
						,
						removePlugins :
							remove_plugins
						,
						wordcount : {
							showParagraphs : false,
							showWordCount: false,
							showCharCount: true,
							maxCharCount: $(this).attr('data-maxlen'),
							hardLimit: true,
							countSpacesAsChars: enable_count_spaces,
						},
						toolbar: [
							{ name: 'source',
								items: toolbar_source
							},
							{ name: 'basicstyles',
								items: toolbar_basicstyles
							},
							{ name: 'clipboard',
								items: toolbar_clipboard
							},
							{ name: 'paragraph',
								items: toolbar_paragraph
							},
							{ name: 'links',
								items: toolbar_links
							},
							{ name: 'document',
								items: toolbar_document
							},
							{ name: 'editing',
								items: toolbar_editing
							},
							{ name: 'insert',
							items: toolbar_insert
							},
							'/',
							{ name: 'styles',
								items: toolbar_styles
							},
							{ name: 'colors',
								items: toolbar_colors
							},
							{ name: 'tools',
								items: toolbar_tools
							},
							{ name: 'about',
								items: toolbar_about
							}],
							allowedContent: true,
							filebrowserImageUploadUrl : itsg_gf_ckeditor_settings.admin_url + '?action=itsg_gf_wysiwyg_ckeditor_upload&form_id=' + itsg_gf_ckeditor_settings.form_id
					}));

CKEDITOR.on( 'instanceReady', function( ev ) {
		ev.editor.on( 'paste', function( evt ) {    
            evt.data['html'] = '<!-- class="Mso" -->'+evt.data['html'];
		}, null, null, 9); // 9 so we treat the paste data before the pastefromword plugin
		ev.editor.on( 'paste', function( evt ) {  
			// remove empty paragraphs
            evt.data['html'] = evt.data['html'].replace(/<p>\s*<\/p>/g, '');
		}, null, null, 11);
	}); 



					CKEDITOR.on( 'dialogDefinition', function( event ) {
						var dialogName = event.data.name;
						var dialogDefinition = event.data.definition;
						event.data.definition.resizable = CKEDITOR.DIALOG_RESIZE_NONE;

						if ( dialogName == 'link' ) {
							var infoTab = dialogDefinition.getContents( 'info' );
							infoTab.remove( 'protocol' );
							//dialogDefinition.removeContents( 'target' ); // hidden in config and configured for default target
							var informationTab = dialogDefinition.getContents('target');
							var targetField = informationTab.get('linkTargetType');
							targetField['default'] = itsg_gf_ckeditor_settings.link_target;

							dialogDefinition.removeContents( 'advanced' );
						}

						if ( dialogName == 'image' ) {
							dialogDefinition.removeContents( 'advanced' );
							dialogDefinition.removeContents( 'Link' );
							var infoTab = dialogDefinition.getContents( 'info' );
							infoTab.remove( 'txtBorder' );
							infoTab.remove( 'txtHSpace' );
							infoTab.remove( 'txtVSpace' );
							infoTab.remove( 'txtWidth' );
							infoTab.remove( 'txtHeight' );
							infoTab.remove( 'ratioLock' );
							// infoTab.remove( 'htmlPreview' ); -- currently disabled, causes 'TypeError: this.preview is null' error

							if ( '1' == itsg_gf_ckeditor_settings.is_minimum_php_version && '1' == itsg_gf_ckeditor_settings.enable_upload_image ) {
								// handle default tab
								var dialog = event.data.definition;
								var oldOnShow = dialog.onShow;
								dialog.onShow = function() {
									oldOnShow.apply(this, arguments);
									if ( this.imageEditMode === false ) {
										this.selectPage('Upload');
									}
								};
							}
						}
					});

					if ( '1' == itsg_gf_ckeditor_settings.is_form_editor ) {
						for (var i in CKEDITOR.instances) {
							// wrap in half second timeout provides performance improvement by stopping 'change' event from firing multiple times
							setTimeout(function(){
								CKEDITOR.instances[i].on('change', function(event) {
									if (event.sender.name == 'field_description') {
										SetFieldDescription(this.getData());
									} else if (event.sender.name  == 'field_content') {
										SetFieldProperty('content', this.getData());
									} else if (event.sender.name  == 'field_default_value_textarea') {
										SetFieldProperty('defaultValue', this.getData());
									} else if (event.sender.name  == 'infobox_more_info_field') {
										SetFieldProperty('infobox_more_info_field', this.getData());
									} else {
										// TO DO: better handling for non-standard fields
										CKEDITOR.instances[i].updateElement();
										$( CKEDITOR.instances.gwtermsofservice_terms.element.$ ).change();
									}
								});
							},500);
							CKEDITOR.instances[i].on('loaded', function(event) {
								if (event.sender.name == 'field_description') {
									SetFieldDescription(this.getData());
								} else if (event.sender.name  == 'field_content') {
									SetFieldProperty('content', this.getData());
								} else if (event.sender.name  == 'field_default_value_textarea') {
									SetFieldProperty('defaultValue', this.getData());
								} else if (event.sender.name  == 'infobox_more_info_field') {
									SetFieldProperty('infobox_more_info_field', this.getData());
								} else {
									CKEDITOR.instances[i].updateElement();
								}
									this.setData(this.getData())
							});
						}
					}

					if ( '1' == itsg_gf_ckeditor_settings.is_dpr_installed && '1' !== itsg_gf_ckeditor_settings.is_admin ) {
						var changed;
						for (var i in CKEDITOR.instances) {
							CKEDITOR.instances[i].on('change', function() {
								CKEDITOR.instances[i].updateElement();
								changed = true;
							});
						}
					}
				});
			});
		}
	}(jQuery));
}

// runs the main function when the page loads
if ( '1' == itsg_gf_ckeditor_settings.is_form_editor ) {
	// runs the main function when field settings have been opened in the form editor

	jQuery(document).bind('gform_load_field_settings', function($) {
		// wrap in half second timeout provides perceived performance improvement by delaying the CKeditor load until the field settings has loaded
		// currently commented out due to issues during initial testing
		//setTimeout(function(){
			itsg_gf_wysiwyg_ckeditor_function(jQuery(this));
		//},500);
	});

	// destroy all existing CKEditor instances when fileupload field is switched between single and multi upload in form editor
	jQuery('input#field_multiple_files').on('change', function(event) {
		for(name in CKEDITOR.instances) {
			CKEDITOR.instances[name].destroy(true);
		}
	});

	// destroy all existing CKEditor instances when product field is switched between field type in form editor - hooks into existing StartChangeProductType function.
	// backup original StartChangeProductType
	var StartChangeProductTypeCK = StartChangeProductType;
	StartChangeProductType = function(field) {
		// destroy all CKEditor instances
		for(name in CKEDITOR.instances) {
			CKEDITOR.instances[name].destroy(true);
		}
		// call original StartChangeProductType
		StartChangeProductTypeCK(field);
	};

	// destroy all existing CKEditor instances when shipping field is switched between field type in form editor - hooks into existing StartChangeShippingType function.
	// backup original StartChangeShippingType
	var StartChangeShippingTypeCK = StartChangeShippingType;
	StartChangeShippingType = function(field) {
	// destroy all CKEditor instances
	for(name in CKEDITOR.instances) {
		CKEDITOR.instances[name].destroy(true);
	}
	// call original StartChangeShippingType
	StartChangeShippingTypeCK(field);
	};

	// destroy all existing CKEditor instances when option field is switched between field type in form editor - hooks into existing StartChangeInputType function.
	// backup original StartChangeInputType
	var StartChangeInputTypeCK = StartChangeInputType;
	StartChangeInputType = function(field) {
		// destroy all CKEditor instances
		for(name in CKEDITOR.instances) {
			CKEDITOR.instances[name].destroy(true);
		}
	// call original StartChangeInputType
	StartChangeInputTypeCK(field);
	};
} else if ( '1' == itsg_gf_ckeditor_settings.is_entry_detail ) {
	// runs the main function when the page loads -- entry editor -- configures any existing upload fields
	jQuery(document).ready( function($) {
		itsg_gf_wysiwyg_ckeditor_function( jQuery(this) );
	});
} else {
	jQuery( document ).bind( 'gform_post_render', function($) {
		itsg_gf_wysiwyg_ckeditor_function( jQuery(this) );
	});
	gform.addAction('gform_post_conditional_logic_field_action', function (formId, action, targetId, defaultValues, isInit) {
		if ( 'show' ==  action  ) {
			itsg_gf_wysiwyg_ckeditor_function();
		}
	});
}