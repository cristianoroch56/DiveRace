<?php
/**
 * Used for managing aspects of the Widget (eg: recommendation key)
 *
 * @package    Bibblio_Related_Posts
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

if ( $this->wizard_finished() ) {
	?>
<form class="form form-panel" method="post" action="<?php echo esc_html( Bibblio_Related_Posts::$admin_path ); ?>">
	<input type="hidden" name="method" value="save_widget_settings">
	<?php wp_nonce_field( 'bibblio_save_widget_options', 'bibblio_save_widget_options_nonce' ); ?>

	<div class="tab_section">
		<h4><?php esc_html_e( 'Managing Recommendation Keys', 'bibblio_text' ); ?></h4>
		<div class="tab_section_label"><?php esc_html_e( 'This is your recommendation key for ', 'bibblio_text' ); ?></div>
		<div class="form-item"><input type="" name="recsKey" class="module_input" value="<?php echo esc_html( $bibblio->get_recommendation_key() ); ?>"></div>
	</div>

	<div class="tab_section">
		<div class="tab_section_label label-empty">&nbsp;</div>
		<div class="form-item">
			<input type="submit" value="<?php echo esc_attr( 'Save', 'bibblio_text' ); ?>" class="form_submit button button-slider-align">
		</div>
	</div>
	<p>Go to <a href="https://developer.bibblio.org/admin/account">Explorer</a>, log in, click on "My Account", "Credentials" and then "Manage my keys" to add or revoke Recommendation Keys.</p>
</form>
	<?php
}
?>
