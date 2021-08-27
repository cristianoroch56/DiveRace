<?php
/**
 * First screen shown when the plug in activated (prior to completing the setup wizard)
 *
 * @package    Bibblio_Related_Posts
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

?>
	<div class="container bibblio_container get_started wrap">
		<h1 class="wp-heading"><?php esc_html_e( 'Get Started', 'bibblio_text' ); ?></h1>
		<h2>You're a couple of steps away from putting a related posts widget on your site</h2>
		<div class="form-panel">
			<div class="tab_half">
				<h2 class="color-orange">Step 1</h2>
				<h3>Create a Bibblio account</h3>
				<p>Your site needs to talk to Bibblio to get related posts, so let's spin up an account.</p>
				<div class="form-panel">
					<div class="text-center"><a class="form_signUp button" href="<?php echo esc_url( 'https://www.bibblio.org/plans?wp=1' ); ?>" target="_blank"><?php esc_html_e( 'Create an account', 'bibblio_text' ); ?></a></div>
					<div class="form-footer text-center">See you back here in a bit!</div>
				</div>
				<p><strong>Signed up already?</strong> Good job! Jump to step 2...</p>
			</div>

			<div class="tab_half">
				<h2 class="color-orange">Step 2</h2>

				<h3>Add your Bibblio credentials here...</h3>
				<p>Once signed into Bibblio you can <a href="https://developer.bibblio.org/credentials" target="_blank">grab your credentials</a> and complete this step.</p>
				<?php require plugin_dir_path( __FILE__ ) . 'parts/login-form.php'; ?>
			</div>

			<div class="clear"></div>
		</div>
		<div style="text-align: center;">
			<div class="help-panel">
				<h4>Need help with your setup?</h4>
				<a href="<?php echo esc_url( 'https://www.bibblio.org/support/wordpress/guide#go-wp-started' ); ?>" target="_blank" class="help-cta">Read the plugin guide</a>
			</div>
			<div class="help-panel">
				<h4>Want to contact us?</h4>
				<a href="<?php echo esc_url( 'https://www.bibblio.org/support/wordpress/contact' ); ?>" target="_blank" class="help-cta">Speak to Bibblio Support</a>
			</div>
		</div>
	</div>
