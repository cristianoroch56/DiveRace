<?php
/**
 * Login form
 *
 * @package    Bibblio_Related_Posts
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

if ( isset( $this->admin_error['server_error'] ) ) {
	if ( isset( $this->admin_error['ssl_error'] ) && $this->admin_error['ssl_error'] ) {
		?>
<div class="notice notice-error">
	<p>The server encountered an SSL error when attempting to communicate with Bibblio's API. This could be due to a common issue with PHP not including, or having outdated, "CA root certificates". Please see <a href="http://php.net/manual/en/function.curl-setopt.php#110457">http://php.net/manual/en/function.curl-setopt.php#110457</a> for information on installing/updating them.</p>
</div>
		<?php
	} else {
		?>
<div class="notice notice-error">
	<p>The server encountered an error when attempting to communicate with Bibblio's API.</p>
</div>
		<?php
	}
}
?>

<form class="form form-panel form-login" autocomplete="off" method="post" action="<?php echo esc_html( Bibblio_Related_Posts::$admin_path ); ?>">
	<input type="hidden" name="method" value="login_credentials_action">
	<?php wp_nonce_field( 'bibblio_login_form', 'bibblio_login_form_nonce' ); ?>
	<div class="form-row">
		<label class="form_label"><?php esc_html_e( 'Client ID', 'bibblio_text' ); ?></label>
		<input type="text" name="client_id" class="form_input" value="<?php echo ( $this->user_logged_in() ? esc_html( Bibblio_Related_Posts_Configs::get( 'client_id' ) ) : '' ); ?>" >
	</div>
	<div class="form-row">
		<label class="form_label"><?php esc_html_e( 'Client Secret', 'bibblio_text' ); ?></label>
		<input type="text" name="client_secret" class="form_input" value="<?php echo ( $this->user_logged_in() ? esc_html( Bibblio_Related_Posts_Configs::get( 'client_secret' ) ) : '' ); ?>">
	</div>
	<div class="form-row error_div">
		<?php echo ( isset( $this->admin_error['login_failed'] ) ) ? esc_html_e( 'Incorrect Client ID or Client Secret', 'bibblio_text' ) : ''; ?>
	</div>
	<div class="form-row text-center">
		<?php echo '<input type="submit" value="' . esc_attr( 'Let\'s go!', 'bibblio_text' ) . '" class="form_submit button">'; ?>
	</div>
</form>
