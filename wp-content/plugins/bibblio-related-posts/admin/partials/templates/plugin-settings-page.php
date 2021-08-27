<?php
/**
 * Container for the module settings form (eg: recommendation key)
 *
 * @package    Bibblio_Related_Posts
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

?>
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
</script>

<div class="container bibblio_container">
		<ul class="tab tab_wizard">
				<li class="tab_item tab_item_active"><?php esc_html_e( 'My Posts', 'bibblio_text' ); ?></li>
				<li class="tab_item"><?php esc_html_e( 'My Module', 'bibblio_text' ); ?></li>
				<li class="tab_item"><?php esc_html_e( 'Add Module', 'bibblio_text' ); ?></li>
		</ul>
		<div class="tab tab_post">
				<?php require plugin_dir_path( __FILE__ ) . 'parts/plugin-options-form.php'; ?>
		</div>
</div>

