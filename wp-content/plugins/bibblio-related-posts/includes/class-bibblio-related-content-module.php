<?php
/**
 * Provides functionality for rendering the Related Content Module
 *
 * @category   Bibblio_Related_Content_module
 * @package    Bibblio_Related_Posts
 * @subpackage Bibblio_Related_Posts/includes
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

/**
 * Provides functionality for rendering the Related Content Module
 */
class Bibblio_Related_Content_Module {

	/**
	 * Returns the Bibblio Recommendation Key setting, or creates one if one is not set
	 *
	 * @return string Bibblio Recommendation Key
	 */
	public static function get_recommendation_key() {
		// see "get_recommendation_key()" in Bibblio_Related_Posts_Support for the function that makes an API call to retrieve key if needed (eg: when setting to WordPress options)
		// this method is intended for frontend use only, when rendering the RCM html and js.
		return Bibblio_Related_Posts_Configs::get( 'recommendation_key' );
	}

	/**
	 * Returns the HTML (and JS) needed for displaying a Bibblio Related Content Module
	 *
	 * @param  array $options Module settings to be used.
	 * @return string
	 */
	public static function get_module_html( $options ) {
		ob_start();
		$rand         = wp_rand( 1, 99999 );
		$query_params = ( $options['queryStringParams'] && ( '{}' !== $options['queryStringParams'] ) ) ? $options['queryStringParams'] : 'false';
		?>
		<div id="bib_related-sidebar<?php echo absint( $rand ); ?>"></div>
		<script>
			var loadRcm = function() {
				Bibblio.initRelatedContent({
					targetElementId: "bib_related-sidebar<?php echo absint( $rand ); ?>",
					recommendationKey: "<?php echo esc_js( $options['recommendationKey'] ); ?>",
					recommendationType: "<?php echo esc_js( $options['recommendationType'] ); ?>",
					contentItemId: "<?php echo esc_js( $options['contentItemId'] ); ?>",
					showRelatedBy: false,
					subtitleField: "description",
					queryStringParams: <?php echo wp_kses_post( $query_params ); ?>,
					styleClasses: "<?php echo esc_js( $options['classes'] ); ?>"
				}, {
					onRecommendationsRendered: function() {
						<?php if ( $options['hideUntilRecsLoaded'] ) { ?>
						jQuery("#bib_related-sidebar<?php echo absint( $rand ); ?>").closest('.widget_bibblio_recent_posts').fadeIn('fast');
						<?php } ?>
					}
				});
			};

			if (window.addEventListener) {
				window.addEventListener('load', loadRcm, false);
			} else if (window.attachEvent) {
				window.attachEvent('onload', loadRcm);
			}
		</script>
		<?php
			$html = ob_get_contents();
			ob_end_clean();

			return $html;
	}
}
