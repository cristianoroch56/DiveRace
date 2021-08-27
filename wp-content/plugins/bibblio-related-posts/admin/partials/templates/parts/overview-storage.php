<?php
/**
 * Renders the "Storage" half of the Overview page
 *
 * @category   Bibblio_Related_Posts_Overview
 * @package    Bibblio_Related_Posts
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

$content_item_usage = isset( $content_item_usage ) ? $content_item_usage : 0;

?>
<div class="analytics-panel analytics-storage clearfix">
	<h3 class="analytics-title text-center">
		<?php esc_html_e( 'Post Storage', 'bibblio_text' ); ?>
	</h3>
	<div class="analytics-container">
		<div class="thermometer-container">
			<div class="yaxis">
				<h4 class="yaxis-top" id="limit-plan">
					<?php echo ( absint( $content_item_limit ) === 0 ) ? '∞' : esc_attr( $content_item_limit ); ?>
				</h4>
				<h4 class="yaxis-bottom">0</h4>
			</div>
			<div class="thermometer">
				<div class="thermometer-mercury bgcolor-<?php echo esc_html( $storage_colour ); ?>"
					style="height: <?php echo esc_html( $storage_percent ) . '%'; ?>;"></div>
			</div>
		</div>
		<div class="values-container">
			<h3 class="current-value color-<?php echo esc_html( $storage_colour ); ?>"
				id="items-stored" <?php echo ( $storage_percent >= 85 ) ? 'style="margin-top: 0"' : ''; ?>><?php echo esc_attr( $content_item_usage ); ?></h3>
			<p id="items-stored-label" class="color-<?php echo esc_html( $storage_colour ); ?>"><?php esc_html_e( 'posts stored', 'bibblio_text' ); ?></p>
			<div id="content-limit-reached-warning" <?php echo ( $storage_percent >= 85 ) && ( 100 !== $storage_percent ) ? '' : 'style="display: none"'; ?>>
				<h5>You're approaching your storage limit</h5>
				<p><a href="https://developer.bibblio.org/upgrade"><strong>Add more space now</strong></a> to ensure new posts continue to be imported to Bibblio</p>
			</div>
			<div id="content-limit-reached-error" <?php echo ( 100 === $storage_percent ) ? '' : 'style="display: none"'; ?>>
					<h5>You've reached your storage limit</h5>
					<p><a href="https://developer.bibblio.org/upgrade"><strong>Add more space now</strong></a> to continue importing new posts to Bibblio</p>
			</div>
		</div>
	</div>
</div>
<span id="storage-refresh-notice" class="refresh-notice" style="display: none">Refreshing...</span>

<script>
	var contentItemUsage = '<?php echo esc_attr( $content_item_usage ); ?>';
	var contentItemLimit = '<?php echo esc_attr( $content_item_limit ); ?>';
	var storagePercent = '<?php echo esc_attr( $storage_percent ); ?>';
</script>
