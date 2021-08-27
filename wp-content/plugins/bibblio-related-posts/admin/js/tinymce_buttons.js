(function() {
	tinymce.PluginManager.add(
		'bibblio_button_script', function( editor, url ) {
			editor.addButton(
				'bibblio_button', {
					text: bibblio_tinyMCE.button_name,
					icon: false,
				}
			);
		}
	);
})();

(function() {
	/* Register the buttons */
	tinymce.create(
		'tinymce.plugins.MyButtons', {
			init : function(editor, url) {

				modules = [];
				for (var i = 0; i < bibblio_tinyMCE.modules.length; i++) {
					modules.push(
						{
							text: bibblio_tinyMCE.modules[i].name,
							value: 'style="' + bibblio_tinyMCE.modules[i].classes + '" query_string_params="' + btoa(bibblio_tinyMCE.modules[i].queryParams) + '" recommendation_type="' + bibblio_tinyMCE.modules[i].recommendationType + '"'
						}
					);
				}

				/**
			 * Inserts shortcode content
			 */
				editor.addButton(
					'bibblio_button', {
						title : 'Insert Bibblio Related Posts Shortcode',
						image : url + '/../../images/bib-shortcode.png',
						onclick: function() {
							editor.windowManager.open(
								{
									title: bibblio_tinyMCE.button_title,
									body: [
										{
											type   : 'listbox',
											name   : 'listbox',
											label  : 'Select Shortcode',
											values : modules,
											value : 'test2' // Sets the default
										}
									],
									onsubmit: function( e ) {
										editor.insertContent( '[bibblio ' + e.data.listbox + ']' );
									}
								}
							);
						},
					}
				);

				editor.addCommand(
					'button_green_cmd', function() {
						var selected_text = ed.selection.getContent();
						var return_text = '';
						return_text = '<h1>' + selected_text + '</h1>';
						ed.execCommand( 'mceInsertContent', 0, return_text );
					}
				);
			},
			createControl : function(n, cm) {
				return null;
			},
		}
	);
	/* Start the buttons */
	tinymce.PluginManager.add( 'bibblio_button_script', tinymce.plugins.MyButtons );
})();
