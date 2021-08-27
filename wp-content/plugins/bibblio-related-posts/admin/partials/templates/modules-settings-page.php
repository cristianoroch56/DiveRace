<?php
/**
 * Page for editing related content modules
 *
 * @package    Bibblio_Related_Posts
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

$modules = Bibblio_Related_Posts_Configs::get( 'modules' );
?>
<div class="container bibblio_container">
	<ul class="tab tab_wizard">
		<li class="tab_item"><?php esc_html_e( 'My Posts', 'bibblio_text' ); ?></li>
		<li class="tab_item tab_item_active"><?php esc_html_e( 'My Module', 'bibblio_text' ); ?></li>
		<li class="tab_item"><?php esc_html_e( 'Add Module', 'bibblio_text' ); ?></li>
	</ul>
	<div class="tab tab_modules">
		<div class="module module_create">
			<div class="module_content">
				<div class="form module_actions">
					<?php wp_nonce_field( 'bibblio_create_module', 'bibblio_create_module_nonce' ); ?>
					<div class="module_actions_step module_actions_step_1">
						<span class="module_actions_text hidden-devices"><?php esc_html_e( '1. Create your module below...', 'bibblio_text' ); ?></span>
						<p class="module_actions_text visible-devices"><?php esc_html_e( '1. As you\'re using a small screen, simply name your module and click \'All done\'. You can refine the shape and design later when you\'re on a larger device.', 'bibblio_text' ); ?></p>
					</div>
					<div class="module_actions_step module_actions_step_2">
						<span class="module_actions_text"><?php esc_html_e( '2.', 'bibblio_text' ); ?></span>
						<input type="text" id="modules-name" class="form_input module_input" name="modulesName" placeholder="<?php esc_html_e( 'Give it a name', 'bibblio_text' ); ?>">
					</div>
					<div class="module_actions_step module_actions_step_3">
							<span class="module_actions_text"><?php esc_html_e( '3.', 'bibblio_text' ); ?></span><span id="manage-tracking" class="module_actions_text_link" onclick="toggleModuleTracking()">Module Settings ▼</span>
					</div>
					<div class="module_actions_step module_actions_step_4 text-right">
						<span class="module_actions_text"><?php esc_html_e( '4.', 'bibblio_text' ); ?></span>
						<button id="module-create" class="module_button_create button"><?php esc_html_e( 'Next', 'bibblio_text' ); ?></button>
					</div>
				</div>
				<?php require plugin_dir_path( __FILE__ ) . 'parts/module-setting-layer.php'; ?>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function() {
		renderDemoContentModule('setupWizard');

		// setup module save button
		jQuery('#module-create').click(function(e) {
			createModule(e);
		});
	});
</script>
