<?php
/**
 * WordPress Widget renderer
 *
 * @category   Bibblio_Related_Posts_Widget
 * @package    Bibblio_Related_Posts
 * @subpackage Bibblio_Related_Posts/includes
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

/**
 * This class defines all code necessary to render the WordPress widget for related posts
 */
class Bibblio_Related_Posts_Widget extends WP_Widget {
	/**
	 * Sets the widget settings
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_bibblio_recent_posts',
			'description' => esc_html__( 'Displays relevant Post suggestions from across your site.' ),
		);

		parent::__construct( 'bibblio_Related_Posts', esc_html__( 'Bibblio Related Posts' ), $widget_ops );
	}

	/**
	 * Renders the widget
	 *
	 * @param  array $args     Widget arguments.
	 * @param  array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		if ( is_single() && ( get_post_meta( get_the_ID(), 'bibblio_content_item_id', true ) ) && Bibblio_Related_Content_Module::get_recommendation_key() ) {
			$id_key  = get_post_meta( get_the_ID(), 'bibblio_content_item_id', true );
			$select  = ( isset( $instance['select'] ) ? json_decode( $instance['select'], true ) : '' );
			$modules = Bibblio_Related_Posts_Configs::get( 'modules' );
			if ( ! is_array( $modules ) ) {
				$modules = [];
			}

			if ( ( count( $select ) > 0 ) && ! empty( $select ) ) {
				$title = apply_filters( 'widget_title', $instance['title'] );
				// before and after widget arguments are defined by themes.
				echo wp_kses_post( $args['before_widget'] );

				if ( ! empty( $title ) ) {
					echo wp_kses_post( $args['before_title'] ) . esc_html( $title ) . wp_kses_post( $args['after_title'] );
				}

				$live_item = array();
				foreach ( $modules as $module ) {
					if ( $select['name'] === $module['name'] ) {
						if ( $select['classes'] !== $module['name'] ) {
							$live_item = $module;
						}
						$live_item['classes']            = $module['classes'] . ( ! empty( $module['styleContainer'] ) ? ' bib--' . $module['styleContainer'] : '' );
						$live_item['queryStringParams']  = isset( $module['queryParams'] ) ? $module['queryParams'] : '{}';
						$live_item['recommendationType'] = isset( $module['recommendationType'] ) ? $module['recommendationType'] : '';
					}
				}
			}

			if ( $live_item && isset( $live_item['classes'] ) ) {
				$options = array(
					'recommendationKey'   => Bibblio_Related_Content_Module::get_recommendation_key(),
					'recommendationType'  => $live_item['recommendationType'],
					'contentItemId'       => $id_key,
					'classes'             => $live_item['classes'],
					'queryStringParams'   => $live_item['queryStringParams'],
					'hideUntilRecsLoaded' => true,
				);

				$html = Bibblio_Related_Content_Module::get_module_html( $options );

				$allowed_tags           = wp_kses_allowed_html( 'post' );
				$allowed_tags['script'] = [];
				$allowed_tags['div']    = [ 'id' => 1 ];
				echo wp_kses( $html, $allowed_tags );
			}

			echo wp_kses_post( $args['after_widget'] );
		}
	}

	/**
	 * Renders the widget config form
	 *
	 * @param  array $instance Saved values from database.
	 */
	public function form( $instance ) {
		$title       = isset( $instance['title'] ) ? $instance['title'] : '';
		$select      = isset( $instance['select'] ) ? $instance['select'] : '';
		$select_name = json_decode( $select, true );
		$modules     = Bibblio_Related_Posts_Configs::get( 'modules' );
		if ( ! is_array( $modules ) ) {
			$modules = [];
		}

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'bibblio_text' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				type="text"
				value="<?php echo esc_attr( $title ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'select' ) ); ?>"><?php esc_html_e( 'Module type:', 'bibblio_text' ); ?></label>
			<select class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'select' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'select' ) ); ?>">
				<?php
				if ( ! empty( $modules ) ) {
					foreach ( $modules as $module ) {
						$value    = wp_json_encode( $module, true );
						$selected = ( $select_name['name'] === $module['name'] ) ? 'selected="selected"' : '';
						?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php echo esc_html( $selected ); ?>>
							<?php echo esc_attr( $module['name'] ); ?>
						</option>
						<?php
					}
				}
				?>
			</select>
		</p>
		<?php
	}
}
?>
