<?php
/**
 * Shown after the setup wizard is completed
 *
 * @package    Bibblio_Related_Posts
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

?>
<div class="container bibblio_container">
	<ul class="tab tab_wizard">
		<li class="tab_item"><?php esc_html_e( 'My Posts', 'bibblio_text' ); ?></li>
		<li class="tab_item"><?php esc_html_e( 'My Module', 'bibblio_text' ); ?></li>
		<li class="tab_item tab_item_active"><?php esc_html_e( 'Add Module', 'bibblio_text' ); ?></li>
	</ul>
	<div class="tab tab_success">
		<div class="form form-panel form-panel" >
			<div class="comms-centred">
				<div style="border: 1px solid rgba(57,181,74,0.5); padding: 20px 25px;background-color: rgba(57,181,74,0.05);margin: 20px -20px 30px;">
					<h1 class="color-green">Now add the module to your posts...</h1>
					<p>Automatically add the module to the bottom of your post's content by switching it <strong>on</strong>, optionally editing the <strong>title</strong> that appears above the module, and clicking <strong>Save</strong>:</p><br>

					<div class="tab_section" style="border: 1px solid rgba(57,181,74,0.5); padding: 20px 25px;background-color:#FFFFFF">

						<div class="form-row">
							<div class="tab_section_label label-short label-level text-left" style="margin-bottom: 10px;width: 280px;"><?php esc_html_e( 'Automatically add this module to the end of your posts', 'bibblio_text' ); ?></div>
							<div class="form-item" style="width: 180px;">
								<label class="checkbox checkbox_flat-rounded" style="margin: 0 60px;">
									<input type="checkbox" id="append_module" name="append_module">
									<span></span>
									<div></div>
								</label>
							</div>
						</div>

						<div class="clear"></div>

						<div class="form-row">
							<div class="tab_section_label label-short text-left" style="margin-bottom: 20px;width: 280px;"><?php esc_html_e( 'Edit the title that appears above the module', 'bibblio_text' ); ?></div>
							<div class="form-item" style="width: 180px;">
								<input type="text" id="module_name" name="auto_append_module_name" class="form_input" value="Related" >
							</div>
						</div>

						<div class="clear"></div>

						<div class="form-row">
							<div>
								<input type="button" value="Save" id="save-append" class="form_submit button" data-save-label="<?php esc_html_e( 'Save', 'bibblio_text' ); ?>">
								<input type="button" id="msg-append-saving" value="Saving..." class="form_submit button" style="display: none" disabled>
								<div id="msg-append-saved" class="module_actions_text module_actions_text_saved_block" style="display: none;">Saved!<div class="color-blue small">Now please wait for the indexing to complete</div></div>
							</div>
						</div>

					</div>
					<p><small>N.B. If you've chosen a column layout, it may not display nicely beneath your post!<br> A column works best in a sidebar (see Widgets below).</small></p>
				</div>

				<div style="border: 1px solid rgba(39,170,225,0.5); padding: 20px 25px;background-color: rgba(39,170,225,0.05);margin: 30px -20px 50px;">

					<h1 class="color-blue">...and wait 5-10 minutes</h1>
					<p><strong>We are now indexing your content to determine how it is related.</strong><br>Please be patient during this initial indexing.</p>
					<p><img src="<?php echo esc_html( plugin_dir_url( __FILE__ ) ); ?>../../images/bibblio-brain.png" width="150" alt="The big digital brain" class="success-pic" style="margin: 10px 0;" /></p>
					<p>Also note, you need at least <strong>five posts live on your site</strong> for related posts to work, else there is nothing to recommend from and to.</p>
				</div>

				<h2 style="font-size: x-large; text-align:center">More Placement Options</h2>


				<h3>Widgets</h3>
				<p>You can also add modules to other areas of your posts, such as the sidebar or footer, by going to the Widgets page under Appearance.</p>
				<p><img src="<?php echo esc_html( plugin_dir_url( __FILE__ ) ); ?>../../images/bibblio-wp-widget-admin.jpg" width="100%" alt="Adding the plugin as a widget" /></p>
				<p><a href="<?php echo esc_html( admin_url() ) . 'widgets.php'; ?>" class="button">Go to the Widgets page</a></p>

				<hr class="hr-margin" />

				<h3>Shortcode</h3>
				<p>If you only want your module on specific posts, add it using a shortcode within the post editor.</p>

                <p><img src="<?php echo esc_html( plugin_dir_url( __FILE__ ) ); ?>../../images/bibblio-wp-editor-shortcode-gutenberg.png" width="100%" alt="Adding the plugin with a shortcode (ver 5)" /></p>
                <p><em>Use a classic block within WordPress version 5 (Gutenberg editor)</em></p>
                <p>&nbsp;</p>
                <p><img src="<?php echo esc_html( plugin_dir_url( __FILE__ ) ); ?>../../images/bibblio-wp-editor-shortcode.jpg" width="100%" alt="Adding the plugin with a shortcode (ver 4)" /></p>
                <p><em>Edit a post in Visual mode within WordPress version 4</em></p>
				<p><a href="<?php echo esc_html( admin_url() ) . 'edit.php'; ?>" class="button">Go to your posts</a></p>

				<hr class="hr-margin" />

				<div class="help-panel">
					<h4>For solutions to common display issues, see our troubleshooting guide</h4>
					<a href="<?php echo esc_url( 'https://www.bibblio.org/support/wordpress/troubleshooting' ); ?>" target="_blank" class="help-cta">Troubleshooting guide</a>
				</div>
				<p>&nbsp;</p>
			</div>
		</div>
	</div>
</div>

<?php
	// used by appendFirstRcm() function.
	wp_nonce_field( 'bibblio_append_first_rcm', 'bibblio_append_first_rcm_nonce' );

	// used by removeAppendedRcm() function.
	wp_nonce_field( 'bibblio_append_module', 'bibblio_append_module_nonce' );
?>

<script>
	jQuery(document).ready(function() {

		// append module save button
		jQuery('#save-append').click(function(e) {
			if ( jQuery( '#append_module' ).attr('checked') ) {
				appendFirstRcm();
			} else {
				removeAppendedRcm();
			}
		});

	});
</script>
