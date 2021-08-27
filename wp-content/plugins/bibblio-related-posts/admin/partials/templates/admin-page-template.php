<?php
/**
 * Main plugin page, showing the overview and module builder tabs etc
 *
 * @category   Bibblio_Related_Posts_Admin
 * @package    Bibblio_Related_Posts
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

/*
* Includes template files for the overview page and its calculations.
*/

try {
	$force_refresh = false;
	require 'parts' . DIRECTORY_SEPARATOR . 'overview-calculations.php';

	$modules = Bibblio_Related_Posts_Configs::get( 'modules' );
	if ( ! is_array( $modules ) ) {
		$modules = [];
	}

	$show_ssl_error = false;
	if ( isset( $bibblio->error['init_error'] ) && Bibblio_Related_Posts_Admin::is_ssl_error( $bibblio->error['init_error'] ) ) {
		$show_ssl_error = true;
	}
} catch ( BibblioException $e ) {
	exit;
} catch ( Exception $e ) {
	exit;
}

// ensure there is always a recommendation key stored in WordPress for widget/shortcode/etc to use.
$bibblio->get_recommendation_key();

?>
<div class="notice notice-error" <?php echo ( $show_ssl_error ) ? '' : 'style="display: none"'; ?>>
	<p>The server encountered an SSL error when attempting to communicate with Bibblio's API. This could be due to a common issue with PHP not including, or having outdated, "CA root certificates". Please see <a href="http://php.net/manual/en/function.curl-setopt.php#110457">http://php.net/manual/en/function.curl-setopt.php#110457</a> for information on installing/updating them.</p>
</div>

<div id="overview-update-error" class="notice notice-error" style="display: none">
	<p>The server encountered an error when attempting to communicate with Bibblio's API.</p>
</div>

<div id="overview-update-offline-warning" class="notice notice-warning" style="display: none">
	<p>You appear to be offline. Overview graphs cannot be updated.</p>
</div>

<div id="overview-update-auth-error" class="notice notice-error" style="display: none">
	<p>Your client ID or client secret seem to have changed and Bibblio cannot be accessed.</p>
</div>

<div class="container bibblio_container">
	<?php wp_nonce_field( 'bibblio_tabs', 'bibblio_tabs_nonce' ); ?>
	<ul class="tab tab_admin">
		<a class="tab_item_a" href="<?php echo esc_url( 'https://www.bibblio.org/support/wordpress/troubleshooting' ); ?>" target="_blank"><?php esc_html_e( 'Troubleshooting', 'bibblio_text' ); ?></a>
		<a class="tab_item_a" href="<?php echo esc_url( 'https://www.bibblio.org/support/wordpress/guide' ); ?>" target="_blank"><?php esc_html_e( 'User guide', 'bibblio_text' ); ?></a>
		<li class="tab_item tab_admin_item tab_item_active" data-type="tab1">
			<?php esc_html_e( 'Overview', 'bibblio_text' ); ?>
		</li>
		<li class="tab_item tab_admin_item" data-type="tab2">
			<?php esc_html_e( 'Settings', 'bibblio_text' ); ?>
		</li>
		<li class="tab_item tab_admin_item hidden-devices" data-type="tab3">
			<?php esc_html_e( 'Modules', 'bibblio_text' ); ?>
		</li>
		<li class="tab_item tab_admin_item" data-type="tab4">
			<?php esc_html_e( 'Support', 'bibblio_text' ); ?>
		</li>
	</ul>

	<div id="tab1" class="tab tabs tab_admin_content">
		<div class="overview-section">
            <div class="overview-header clearfix">
                <img src="<?php echo esc_html( plugin_dir_url( __FILE__ ) ); ?>../../images/related-posts-by-bibblio-logo-reverse.png" alt="Related Posts by Bibblio" class="overview-header-logo">
                <div class="overview-header-feature">
                    <div class="overview-header-feature-title">New module builder - new features!</div>
                    <a href="javascript:clickTab(2)"><img src="<?php echo esc_html( plugin_dir_url( __FILE__ ) ); ?>../../images/module-text-only.png" alt="Text-only"><img src="<?php echo esc_html( plugin_dir_url( __FILE__ ) ); ?>../../images/module-tall-ratio.png" alt="Tall ratio"><img src="<?php echo esc_html( plugin_dir_url( __FILE__ ) ); ?>../../images/module-hide-title.png" alt="Hide title"><img src="<?php echo esc_html( plugin_dir_url( __FILE__ ) ); ?>../../images/module-invert-text.png" alt="Invert text"><img src="<?php echo esc_html( plugin_dir_url( __FILE__ ) ); ?>../../images/module-hover-effects.png" alt="Hover effects"></a>
                </div>
            </div>
		</div>
		<div class="overview-section overview-charts clearfix">
			<div class="tab_overview tab_overview_storage">
				<?php require 'parts/overview-storage.php'; ?>
			</div>

			<div class="tab_overview tab_overview_rec">
				<?php require 'parts/overview-recommendations.php'; ?>
			</div>
			<div>&nbsp;<br>
				<p class="text-center"><a href="https://developer.bibblio.org/login" target="_blank" style="vertical-align:middle;"><i class="fa fa-area-chart fa-2x fa-fw"></i></a> &nbsp; See your full analytics, including module clicks and click-through rate, on your <a href="https://developer.bibblio.org/login" target="_blank">Bibblio Dashboard</a></p>
			</div>
		</div>

		<div class="overview-section overview-actions">
			<div class="overview-action-item">
				<div class="editorial-point">
					<a href="javascript:clickTab(1)" class="editorial-illustrations">
						<span>
							<i class="fa fa-cog fa-4x fa-fw" aria-hidden="true"></i>
						</span>
						<h2 class="section-title"><?php esc_html_e( 'Settings', 'bibblio_text' ); ?></h2>
						<p><?php esc_html_e( 'Control how your posts are handled and more', 'bibblio_text' ); ?></p>
					</a>
				</div>
			</div>
			<div class="overview-action-item">
				<div class="editorial-point">
					<a href="javascript:clickTab(2)" class="editorial-illustrations">
						<span>
							<i class="fa fa-th-large fa-4x fa-fw" aria-hidden="true"></i>
						</span>
						<h2 class="section-title"><?php esc_html_e( 'Modules', 'bibblio_text' ); ?></h2>
						<p><?php esc_html_e( 'Add, edit and delete your related posts modules', 'bibblio_text' ); ?></p>
					</a>
				</div>
			</div>
			<div class="overview-action-item">
				<div class="editorial-point">
					<a href="javascript:clickTab(3)" class="editorial-illustrations">
						<span>
							<i class="fa fa-info fa-4x fa-fw" aria-hidden="true"></i>
						</span>
						<h2 class="section-title"><?php esc_html_e( 'Support', 'bibblio_text' ); ?></h2>
						<p><?php esc_html_e( 'Check out the user guide, troubleshoot or contact us', 'bibblio_text' ); ?></p>
					</a>
				</div>
			</div>
		</div>
	</div>

	<div id="tab2" class="tab tabs tab_admin_content" style="display: none;">
		<h3 class="tab_title">
			<?php esc_html_e( 'Posts', 'bibblio_text' ); ?>
		</h3>

		<?php if ( 0 !== $posts_count ) { ?>
		<div class="tab_admin_addMyPrevPosts">
			<form class="form form-panel" method="post" action="<?php echo esc_html( Bibblio_Related_Posts::$admin_path ); ?>">

				<h4><?php esc_html_e( 'Handling existing posts', 'bibblio_text' ); ?></h4>

				<div class="tab_section">
					<p class="tab_section_label">
						<?php esc_html_e( 'Allow existing posts to be recommended', 'bibblio_text' ); ?>
					</p>
					<div class="form-item">
						<?php
						if ( Bibblio_Related_Posts_Admin::importing_old_posts() ) {
							?>
							<?php wp_nonce_field( 'cancel_importing', 'bibblio_cancel_importing_nonce' ); ?>
							<input type="hidden" name="method" value="cancel_importing">
							<p>Currently importing...</p>
							<input type="submit" value="<?php echo esc_attr( 'Cancel import', 'bibblio_text' ); ?>" class="form_submit button button-slider-align">
							<?php
						} else {
							?>
							<?php wp_nonce_field( 'add_previous', 'bibblio_add_previous_nonce' ); ?>
							<input type="hidden" name="method" value="add_previous">
							<input type="submit" value=" <?php echo esc_attr( 'Import published posts', 'bibblio_text' ); ?>" class="form_submit button button-slider-align">
							<?php
						}

						echo '<div class="progress-bar-container ';
						if ( $indexed_count === $posts_count ) {
							echo 'progress-complete';
						}
						echo '"><div class="progress-bar" style="width:' . esc_attr( $indexed_percent ) . '%"></div></div>';
						echo '<div class="import-progress-label">' . number_format( $indexed_count ) . ' of ' . number_format( $posts_count ) . ' imported</div>';
						?>
					</div>
				</div>

				<div class="tab_section">
					<p class="tab_section_label">
						<?php esc_html_e( 'Prioritize recency. Posts published before the time period you select are less likely to appear as recommendations', 'bibblio_text' ); ?>
					</p>
					<div class="form-item">

						<?php wp_nonce_field( 'bibblio_recency_slider', 'bibblio_recency_slider_nonce' ); ?>
						<?php wp_nonce_field( 'bibblio_get_recency', 'bibblio_get_recency_nonce' ); ?>

						<?php

						$recency_value = $bibblio->get_recency_value();
						$recency_value = ( $recency_value ) ? $recency_value : 0;
						?>

						<div class="">
							<input class="input-range" id="recencySlider" value="<?php echo( esc_attr( $recency_value ) ); ?>" type="range" min="0" max="10" step="1"/>
							<div class="recency-labels">
								<div class="recency-label">OFF</div>
								<div class="recency-label">2</div>
								<div class="recency-label">1</div>
								<div class="recency-label">180</div>
								<div class="recency-label">90</div>
								<div class="recency-label">30</div>
								<div class="recency-label">14</div>
								<div class="recency-label">7</div>
								<div class="recency-label">4</div>
								<div class="recency-label">2</div>
								<div class="recency-label">1</div>
							</div>
							<div class="recency-labels clearfix">
								<div class="recency-label-type">years</div>
								<div class="recency-label-type">days</div>
							</div>
						</div>
					</div>
					<div class="pull-left color-green" id="recencySaved" style="display:none" ><strong>Saved</strong></div>
				</div>

				<div class="information-box" style="width: 600px;">
					<strong>Note:</strong>
					<?php esc_html_e( 'Any change you make will begin to take effect as new items are indexed. You should see the results in full roughly 24 hours after any new items have been added.', 'bibblio_text' ); ?>
				</div>

			</form>
		</div>
		<?php } ?>

		<?php require plugin_dir_path( __FILE__ ) . 'parts/plugin-options-form.php'; ?>

<?php

		/*
		<h3 class="tab_title">
			<?php //_e('Widgets', 'bibblio_text'); ?>
		</h3>
		<?php include plugin_dir_path(__FILE__) . 'parts/widget-settings-form.php'; ?>
		*/
?>

		<h3 class="tab_title">
			<?php esc_html_e( 'Account', 'bibblio_text' ); ?>
		</h3>
		<div class="tab_section tab_overview_logout">
			<form class="form form-panel" method="post" action="<?php echo esc_html( Bibblio_Related_Posts::$admin_path ); ?>">
				<h4><?php esc_html_e( 'Disconnecting', 'bibblio_text' ); ?></h4>
								<div class="tab_section_label">
										<?php esc_html_e( 'Log out of the Bibblio plugin, detaching all of your posts from Bibblio', 'bibblio_text' ); ?>

								</div>

				<div class="form-item">
					<input type="hidden" name="method" value="disconnect">
					<?php wp_nonce_field( 'disconnect', 'bibblio_disconnect_nonce' ); ?>
					<input type="submit" value=" <?php echo esc_html_e( 'Disconnect', 'bibblio_text' ); ?>" class="button button-warning buttonLogout">
				</div>
								<div style="clear: both;">&nbsp;</div>
								<div class="information-box alert" style="width: 600px;">
										<strong>Warning:</strong>
										<?php esc_html_e( 'Disconnection will apply to all of your environments.', 'bibblio_text' ); ?><br /><br />
										<?php esc_html_e( 'E.g. If you disconnect the widget from your development environment it will instantly disconnect from your live environment too.', 'bibblio_text' ); ?><br /><br />
										<?php esc_html_e( 'It will make all of your modules disappear, and detach all of your posts and their recommendations from Bibblio. You will need to reimport your posts again to revert this.', 'bibblio_text' ); ?><br /><br />
										<a href="<?php echo esc_url( 'https://www.bibblio.org/support/wordpress/guide#go-disconnecting-widget' ); ?>" target="_blank">More information</a>
								</div>
			</form>
		</div>
	</div>

	<div id="tab3" class="tab tabs tab_admin_content" style="display: none;">
			<?php wp_nonce_field( 'bibblio_update_modules', 'bibblio_update_module_nonce' ); ?>
			<h3 class="tab_title">
					<?php esc_html_e( 'What is a module?', 'bibblio_text' ); ?>
			</h3>
			<div class="tab-description-container clearfix">
				<div class="tab-description-col50pc">A module contains related posts that can sit on any part of your posts. You can design how they look, influence what's suggested and use tracking codes to monitor performance.</div>
				<div class="tab-description-col50pc">Once saved, you can <strong>add the module automatically</strong> to the end of your posts using the dropdown below, or alternatively <a href="<?php echo esc_html( admin_url() ) . 'widgets.php'; ?>" title="Go to: Appearance > Widgets"><strong>add it as a widget</strong></a> or use a <a href="https://www.bibblio.org/support/wordpress/guide#go-wp-shortcodes" target="_blank" title="User guide: Inserting Shortcodes"><strong>shortcode</strong></a>.</div>
			</div>

			<h3 class="tab_title">
					<?php esc_html_e( 'My Modules', 'bibblio_text' ); ?>
			</h3>

			<div class="myModules module-showcase-layout">
				<div id="create-module" class="myModules_item">
					<div class="myModules_item_type myModules_item_type_plus" data-modifier="">
						<i class="fa fa-plus fa-2x" aria-hidden="true"></i>
					</div>
					<div class="myModules_item_text"><?php esc_html_e( 'Create a new module', 'bibblio_text' ); ?></div>
				</div>
			</div>

			<div class="clear"></div>

			<?php if ( $this->wizard_finished() ) { ?>
			<div class="tab_section" style="margin-top: 15px;border: 1px solid rgba(57,181,74,0.5); padding: 20px 25px;background-color:#FFFFFF">
				<div class="form-row">
					<div class="tab_section_label"><?php esc_html_e( 'Automatically feature one module at the end of my posts:', 'bibblio_text' ); ?></div>
					<div class="form-item">

						<?php wp_nonce_field( 'bibblio_append_module', 'bibblio_append_module_nonce' ); ?>

						<select id="append-module-select" name="appended_rcm" style="min-width: 230px;">
							<option value="">None</option>
							<?php
								$modules      = Bibblio_Related_Posts_Configs::get( 'modules' );
								$appended_rcm = Bibblio_Related_Posts_Configs::get( 'appended_rcm' );
							foreach ( $modules as $module ) {
								if ( $appended_rcm && ( $appended_rcm === $module['name'] ) ) {
									$selected = 'selected="selected"';
								} else {
									$selected = '';
								}
								echo '<option value="' . esc_attr( $module['name'] ) . '" ' . esc_html( $selected ) . '>' . esc_html( $module['name'] ) . '</option>';
							}
							?>
						</select>
					</div>
				</div>
				<div class="clear"></div>
				<div class="form-row">
					<div class="tab_section_label"><?php esc_html_e( 'And add the following title to this module:', 'bibblio_text' ); ?></div>
					<div class="form-item">
						<?php $auto_append_header = ( Bibblio_Related_Posts_Configs::get( 'auto_appended_module_header' ) ) ? Bibblio_Related_Posts_Configs::get( 'auto_appended_module_header' ) : ''; ?>
						<input type="text" id="module_name" name="auto_append_module_name" class="form_input" value="<?php echo esc_attr( $auto_append_header ); ?>" style="min-width: 230px; padding:4px 5px">
					</div>
					<div class="form-item">
						<input type="button" value="Save" id="module-append" class="form_submit tab_admin_addMyPrevPosts_button_update button pull-left" data-save-label="<?php esc_html_e( 'Save', 'bibblio_text' ); ?>">
						<input type="button" id="msg-append-saving" value="Saving..." class="form_submit button pull-left" style="display: none" disabled>
						<span id="msg-append-saved" class="module_actions_text pull-left" style="display: none; color: #39B54A;margin-left: 10px">Saved!</span>
					</div>
				</div>
			</div>
			<?php } ?>

			<div class="clear"></div>

			<div class="module_actions" style="display: none">
				<div class="module_name pull-left">
					<div class="module_content">
						<span class="module_actions_text">My Module Name</span>
						<input type="text" name="modulesName" id="modules-name" class="tab_admin_addMyPrevPosts_button_modulesName" value="">
						<span id="manage-tracking" class="module_actions_text_link" onclick="toggleModuleTracking()">Module Settings ▼</span>
					</div>
				</div>

				<div class="module module_saved">
					<input type="button" value="<?php esc_html_e( 'Delete', 'bibblio_text' ); ?>" id="module-delete" class="form_submit tab_admin_addMyPrevPosts_button_delete button button-warning  pull-right">
					<input type="button" value="" id="module-save" class="form_submit tab_admin_addMyPrevPosts_button_update button pull-right" data-save-label="<?php esc_html_e( 'Save', 'bibblio_text' ); ?>" data-update-label="<?php esc_html_e( 'Update', 'bibblio_text' ); ?>">
					<input type="button" id="msg-module-saving" value="Saving..." class="form_submit button pull-right" style="display: none" disabled>
					<span id="msg-module-saved" class="module_actions_text pull-right" style="display: none">Saved!</span>
				</div>
			</div>

			<div class="module module_create" style="display: none">
				<?php require plugin_dir_path( __FILE__ ) . 'parts/module-setting-layer.php'; ?>
			</div>

			<div class="module_placeholder"></div>
	</div>

	<div id="tab4" class="tab tabs tab_admin_content" style="display: none;">
		<h2><?php esc_html_e( 'Support', 'bibblio_text' ); ?></h2>
		<div class="form-panel">
			<div class="information-panel-editorial">
				<h3>Important info when getting started</h3>
				<p>A <strong>minimum of five posts</strong> are required on your site to be able to display related content.</p>
				<p>It takes <strong>at least 5-10 minutes</strong> to initially index all of your posts. Once done, the module will display on your site. Please be a little patient - grab a coffee, have a stretch!</p>
				<p>If nothing is appearing, double-check you have added the module to your posts. You can automatically feature a module at the end of your posts by going to the <a href="javascript:clickTab(2)">Modules</a> section, selecting it from the dropdown and clicking <strong>Save</strong>.</p>
				<hr class="hr-half hr-margin" />
				<h3><?php esc_html_e( 'All the documentation you need', 'bibblio_text' ); ?></h3>
				<p>Our <strong>User Guide</strong> gives you a complete overview of our plugin:</p>
				<ul>
					<li><a href="https://www.bibblio.org/support/wordpress/guide" target="_blank">User guide - English</a></li>
					<li><a href="https://www.bibblio.org/support/wordpress/guia-es" target="_blank">Guía del usuario - Español</a></li>
					<li><a href="https://www.bibblio.org/support/wordpress/guia-pt-br" target="_blank">Guia do usuário - Português (Brasil)</a></li>
				</ul><br>

				<p>If you are having problems, we have a <strong>Troubleshooting Guide</strong>:</p>
				<ul>
					<li><a href="https://www.bibblio.org/support/wordpress/troubleshooting" target="_blank">Troubleshooting Guide - English</a></li>
				</ul>

				<hr class="hr-half hr-margin" />
				<h3><?php esc_html_e( 'Contact Support', 'bibblio_text' ); ?></h3>
				<?php wp_nonce_field( 'bibblio_support', 'bibblio_support_nonce' ); ?>
				<p><?php esc_html_e( 'If you continue to have difficulties or would like to offer feedback and suggestions, feel free to get in contact with the Bibblio support team.', 'bibblio_text' ); ?></p><br />
				<div class="tab_section">
					<p class="tab_section_label label-short">
					<label class="form_label"><?php esc_html_e( 'Full Name', 'bibblio_text' ); ?></label></p>
					<div class="form-item">
					<input type="text" name="full_name" id="support-name" class="form_input nameField" required>
					</div>
				</div>
				<div class="tab_section">
					<p class="tab_section_label label-short">
					<label class="form_label"><?php esc_html_e( 'Email Address', 'bibblio_text' ); ?></label></p>
					<div class="form-item">
					<input type="text" name="email" id="support-email" class="form_input emailField" required>
					</div>
				</div>
				<div class="tab_section">
					<p class="tab_section_label label-short">
					<label class="form_label"><?php esc_html_e( 'Company', 'bibblio_text' ); ?></label></p>
					<div class="form-item">
					<input type="text" name="email" id="support-company" class="form_input companyField" required>
					</div>
				</div>
				<div class="tab_section">
					<p class="tab_section_label label-short">
					<label class="form_label"><?php esc_html_e( 'Your Message', 'bibblio_text' ); ?></label></p>
					<div class="form-item">
					<textarea name="content" id="support-message" class="form_input contentField" rows="10" required></textarea>
					</div>
				</div>
				<div class="tab_section">
					<div class="text-center">
						<input type="submit" id="support-submit" value="<?php esc_html_e( 'Send', 'bibblio_text' ); ?>" class="form_support_submit button form_submit">
						<p class="successMessage" style="display: none"><?php esc_html_e( 'Message sent successfully', 'bibblio_text' ); ?></p>
					</div>
				</div>
				<br />
			</div>
		</div>
	</div>
</div>

<script>
// catch any errors we may have introduced
window.onerror = function(message, file, line, col, error) {
	console.error("Error occured: " + error.message);
	return false;
};
window.addEventListener("error", function(e) {
	console.error("Error occured: " + e.error.message);
	return false;
});

var bibModules = <?php echo wp_json_encode( $modules ); ?>;

jQuery(document).ready(function() {

	// setup navigation and module tiles
	bibblioAdminNav();

<?php
if ( isset( $_GET['tab'], $_GET['bibblio_tabs_nonce'] ) // Input var okay.
			&& wp_verify_nonce( sanitize_key( $_POST['bibblio_tabs_nonce'] ), 'bibblio_tabs_nonce' ) ) { // Input var okay.
	if ( 'settings' === $_GET['tab'] ) { // Input var okay.
		echo 'jQuery(".tab_admin .tab_item")[1].click();';
	}

	if ( 'modules' === $_GET['tab'] ) { // Input var okay.
		echo 'jQuery(".tab_admin .tab_item")[2].click();';
	}

	if ( 'support' === $_GET['tab'] ) { // Input var okay.
		echo 'jQuery(".tab_admin .tab_item")[3].click();';
	}
}
?>

	setModuleTileOnClicks();
	getRecency();

	jQuery('.posts-custom .checkbox_flat-rounded').on('click', function() {
		jQuery('#import-reminder').fadeIn(250);
	});

	// setup module save button
	jQuery('#module-save').click(function(e) {
		saveModule(e);
	});

	jQuery('#module-delete').click(function(e) {
		deleteModule(e);
	});

	jQuery('#module-append').click(function(e) {
		appendModule(e);
	});

	renderDemoContentModule();
	redrawModuleUiElements();

	// set recency preference

	jQuery('#recencySlider').mouseup(function(e) {
		setRecency(e);
	});

	// setup the Overview page refresh
<?php
	// update immediately if this is the first time the Overview page is shown.
if ( ! isset( $get_monthly_usage[0]['total'] ) ) {
	echo '  updateOverviewPage();';
} else {
	echo '  setTimeout(updateOverviewPage, 3000);';
}
?>
	setInterval(updateOverviewPage, 60000);

	jQuery('#support-submit').click(function(e) {
		sendSupportEmail(e);
	});
});
</script>
