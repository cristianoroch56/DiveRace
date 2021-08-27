<?php
/**
 * Used for managing aspects of the Widget (eg: recommendation key)
 *
 * @package    Bibblio_Related_Posts
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

$bibblio_support     = new Bibblio_Related_Posts_Support();
$selected_post_types = $bibblio_support->get_selected_post_types();
$post_types          = $bibblio_support->get_all_post_types();

if ( ! $this->wizard_finished() || $selected_post_types ) {
	?>
<!-- "Choose how to handle your posts" -->
<form class="form form-panel" method="post" action="<?php echo esc_html( Bibblio_Related_Posts::$admin_path ); ?>">
	<input type="hidden" name="method" value="save_plugin_settings">

		<?php
		if ( ! $this->wizard_finished() ) {
			?>
	<h4>Choose how to handle your posts</h4>
			<?php
		} else {
			?>
		<h4>Handling future posts</h4>
			<?php
		}
		?>

	<?php
	if ( ! $this->wizard_finished() ) {
		?>
	<div class="tab_section">
		<div class="tab_section_label"><?php esc_html_e( 'Allow existing posts to be recommended', 'bibblio_text' ); ?></div>
		<div class="form-item">
			<label class="checkbox checkbox_flat-rounded">
				<input type="checkbox" name="add_previous" checked="checked">
				<span></span>
				<div></div>
			</label>
		</div>
	</div>
		<?php
	}
	?>

	<div class="tab_section">
		<div class="tab_section_label"><?php esc_html_e( 'Automatically allow future posts to be recommended', 'bibblio_text' ); ?></div>
		<div class="form-item">
			<label class="checkbox checkbox_flat-rounded">
				<?php $auto_add = Bibblio_Related_Posts_Configs::get( 'auto_add' ); ?>
				<input type="checkbox" name="auto_add" <?php echo ( $auto_add || is_null( $auto_add ) ) ? 'checked="checked"' : ''; ?>>
				<span></span>
				<div></div>
			</label>
		</div>
	</div>

	<?php
	if ( ! $bibblio_support->has_custom_types() ) {
		?>
<input type="hidden" name="custom_post_types[]" value="post">
		<?php
	} else {
		if ( ! $this->wizard_finished() ) {
			require 'select-post-types.php';
		}
	}
	?>

	<div class="tab_section">
		<div class="tab_section_label label-empty">&nbsp;</div>
			<div class="form-item">
			<?php
			if ( ! $this->wizard_finished() ) {
				echo '<input type="submit" value="' . esc_attr( 'Next >', 'bibblio_text' ) . '" class="form_submit button button-slider-align">';
			} else {
				echo '<input type="submit" value="' . esc_attr( 'Save', 'bibblio_text' ) . '" class="form_submit button button-slider-align">';
			}
			?>
		</div>
	</div>
	<?php wp_nonce_field( 'bibblio_save_plugin_options', 'bibblio_save_plugin_options_nonce' ); ?>

	<?php if ( ! $this->wizard_finished() ) { ?>
	<div class="help-panel">
		<h4><i class="fa fa-info-circle fa-2x fa-fw color-wpblue" aria-hidden="true" style="vertical-align: sub;"></i> Need help?</h4>
		<p>Find out more about handling your posts</p>
		<p><a href="<?php echo esc_url( 'https://www.bibblio.org/support/wordpress/guide#go-wp-started' ); ?>" target="_blank" class="help-cta">Read the plugin guide</a></p>
	</div>
	<?php } ?>

</form>

	<?php
}
?>

<?php if ( $bibblio_support->has_custom_types() && $this->wizard_finished() ) { ?>
<!-- Choose post types to be recommended -->
<form class="form form-panel" method="post" action="<?php echo esc_html( Bibblio_Related_Posts::$admin_path ); ?>">
	<input type="hidden" name="method" value="save_selected_post_types">

	<?php require 'select-post-types.php'; ?>

	<?php
			// check if all the custom post types are selected.
			$num_custom_post_types = count( $post_types );
			$num_selected          = count( array_intersect( $post_types, $selected_post_types ) );

	if ( $num_selected !== $num_custom_post_types ) {
		?>
	<div id="import-reminder" class="information-box alert" style="display: none;width: 600px;margin-left: 10px"><strong>Note:</strong> Once you save your choice, you must click “Import published posts” (above) to index your existing custom posts.</div>
	<div class="tab_section">
	<div class="tab_section_label label-empty">&nbsp;</div>
	<div class="form-item">
	<input type="submit" value="<?php echo esc_attr( 'Save', 'bibblio_text' ); ?>" class="form_submit button button-slider-align">
	</div>
	</div>
		<?php
	}
	?>

	<?php wp_nonce_field( 'bibblio_selected_post_types', 'bibblio_selected_post_types_nonce' ); ?>
</form>
<?php } ?>
