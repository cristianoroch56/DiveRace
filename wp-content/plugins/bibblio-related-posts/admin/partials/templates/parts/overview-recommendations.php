<?php
/**
 * Renders the "Recommendations" half of the Overview page
 *
 * @category   Bibblio_Related_Posts_Overview
 * @package    Bibblio_Related_Posts
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

$recs_usage = isset( $recs_usage ) ? $recs_usage : 0;

$max_count_recommendations = 1;
if ( isset( $get_monthly_usage ) ) {
	foreach ( $get_monthly_usage as $val ) {
		if ( isset( $val['total'] ) && ( $max_count_recommendations < $val['total'] ) ) {
			$max_count_recommendations = $val['total'];
		}
	}
}
?>
<div class="analytics-panel analytics-recommendations clearfix">
	<h3 class="analytics-title text-center"><?php esc_html_e( 'Recommendations', 'bibblio_text' ); ?></h3>
	<div class="analytics-container">
		<div class="values-container values-recommendations">
			<h3 class="current-value color-<?php echo esc_html( $recs_colour ); ?>" id="monthlyRecommendations" <?php echo ( $recs_percent >= 85 ) ? 'style="margin-top: 0"' : ''; ?>><?php echo esc_attr( $get_monthly_usage[4]['total'] ); ?></h3>
			<p class="color-<?php echo esc_html( $recs_colour ); ?>" id="recommendation_text">
				<?php echo esc_html__( 'recommendations in ', 'bibblio_text' ) . esc_html( $bibblio->get_month_name( $get_monthly_usage[4]['month'] ) ); ?>
			</p>
			<div id="recommendation-limit-reached-warning" <?php echo ( ( $recs_percent >= 85 ) && ( 100 !== $recs_percent ) ) ? '' : 'style="display: none"'; ?>>
				<h5>You're approaching your monthly recommendation limit for this month</h5>
				<p><a href="https://developer.bibblio.org/upgrade"><strong>Increase it now!</strong></a></p>
			</div>
			<div id="recommendation-limit-reached-error" <?php echo ( 100 === $recs_percent ) ? '' : 'style="display: none"'; ?>>
				<h5>You've reached your recommendation limit for this month</h5>
				<p><a href="https://developer.bibblio.org/upgrade"><strong>Increase it now!</strong></a></p>
			</div>
		</div>
		<div class="barchart-container">
			<?php
			$column_number = 0;
			if ( ! empty( $get_monthly_usage ) ) {
				foreach ( $get_monthly_usage as $month_items ) {
					$total_label   = ( isset( $month_items['total'] ) && ( $month_items['total'] > 0 ) ) ? esc_attr( $month_items['total'] ) : '';
					$current_total = ( isset( $month_items['total'] ) ) ? ( ( $month_items['total'] / $max_count_recommendations ) * 100 ) : 0;
					$column_number++;
					?>
					<div class="barchart-column barchart-column-<?php echo esc_attr( $column_number ); ?>">
						<div class="barchart-bar-container">
							<div class="barchart-bar-value color-blue" style="bottom: <?php echo esc_attr( $current_total ); ?>%;"><?php echo esc_html( $total_label ); ?></div>
							<div class="barchart-bar bgcolor-blue" style="height: <?php echo esc_attr( $current_total ); ?>%;"></div>
						</div>
						<div class="barchart-bar-label"><?php echo ( isset( $month_items['label'] ) ) ? esc_attr( $month_items['label'] ) : ''; ?></div>
					</div>
					<?php
				}
			}
			?>
		</div>
	</div>
</div>
<span id="recs-refresh-notice" class="refresh-notice" style="display: none">Refreshing...</span>



<script>
	var recsUsage = '<?php echo esc_attr( $recs_usage ); ?>';
	var recsLimit = '<?php echo esc_attr( $recommendations_limit ); ?>';
	var recsPercent = '<?php echo esc_attr( $recs_percent ); ?>';
</script>
