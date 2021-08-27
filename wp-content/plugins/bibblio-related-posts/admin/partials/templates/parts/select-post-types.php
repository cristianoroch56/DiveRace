<?php
/**
 * Lets the user select custom post types to use the plugin with
 *
 * @category   Bibblio_Related_Posts_Activator
 * @package    Bibblio_Related_Posts
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

if ( ! $this->wizard_finished() ) {
	?>
<br/><h4>Choose post types to be recommended</h4>
	<?php
} else {
	?>
<h4>Post types to be recommended</h4>
	<?php
}

foreach ( $post_types as $p_type ) {
	$label = get_post_type_object( $p_type )->label;
	$count = wp_count_posts( $p_type )->publish;

	$labels[ $p_type ] = $label;
	$counts[ $p_type ] = $count;
}

arsort( $counts );
?>

<div class="post-types">
	<div class="posts-section posts-standard">
		<h5>Standard Posts</h5>
		<div class="tab_section">
			<div class="tab_section_label"><span class="post-type-label"><?php echo esc_html( $labels['post'] ) . '</span> <span class="post-type-quantity">' . absint( $counts['post'] ) . '</span>'; ?></div>
			<div class="form-item">
				<?php
				if ( ! in_array( 'post', $selected_post_types, true ) ) {
					$post_checked = ( ! $this->wizard_finished() ) ? ' checked="checked"' : '';
					?>
				<label class="checkbox checkbox_flat-rounded">
					<input type="checkbox" name="custom_post_types[]" value="post"<?php echo esc_html( $post_checked ); ?>>
					<span></span>
					<div></div>
				</label>
					<?php
				} else {
					?>
				<span class="post-type-status added">Selected</span>
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<div class="posts-section posts-custom">
		<h5>Custom Posts</h5>
		<div class="information-box" style="width: 600px;margin-left: 10px"><strong>Note:</strong> Any custom post types you select may be recommended alongside your standard posts.</div>

		<?php
		// already selected post types.
		foreach ( $counts as $p_type => $count ) {
			if ( 'post' !== $p_type ) {
				if ( in_array( $p_type, $selected_post_types, true ) ) {
					?>
			<div class="tab_section">
				<div class="tab_section_label"><span class="post-type-label"><?php echo esc_html( $labels[ $p_type ] ) . '</span> <span class="post-type-quantity">' . absint( $count ) . '</span>'; ?></div>
				<div class="form-item"><span class="post-type-status added">Selected</span></div>
			</div>
					<?php
				}
			}
		}
		?>

		<?php
		// NOT selected post types.
		foreach ( $counts as $p_type => $count ) {
			if ( 'post' !== $p_type ) {
				if ( ! in_array( $p_type, $selected_post_types, true ) ) {
					?>
			<div class="tab_section">
				<div class="tab_section_label"><span class="post-type-label"><?php echo esc_html( $labels[ $p_type ] ) . '</span> <span class="post-type-quantity">' . absint( $count ) . '</span>'; ?></div>
				<div class="form-item">
					<label class="checkbox checkbox_flat-rounded">
						<input type="checkbox" name="custom_post_types[]" value="<?php echo esc_attr( $p_type ); ?>">
						<span></span>
						<div></div>
					</label>
				</div>
			</div>
					<?php
				}
			}
		}
		?>
	</div>
</div>
