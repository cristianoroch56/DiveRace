<?php
/**
 * Provides most helper functionality and accessors for the plugin
 *
 * @category   Bibblio_Related_Posts_Support
 * @package    Bibblio_Related_Posts
 * @subpackage Bibblio_Related_Posts/includes
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

if ( ! function_exists( 'get_the_post_thumbnail_url' ) ) {
	/**
	 * Provides backwards compatibility to WordPress 4.0
	 *
	 * @param  string $post Post ID.
	 * @param  string $size Size.
	 * @return string
	 */
	function get_the_post_thumbnail_url( $post, $size ) {
		$post_thumbnail_id = get_post_thumbnail_id( $post, $size );
		if ( ! $post_thumbnail_id ) {
			return false;
		}
		return wp_get_attachment_image_url( $post_thumbnail_id );
	}
}

/**
 * Provides most helper functionality and accessors for the plugin
 */
class Bibblio_Related_Posts_Support {

	/**
	 * Bibblio API object
	 *
	 * @var object
	 */
	public $api_object = null;

	/**
	 * Errors that my have occurred
	 *
	 * @var array
	 */
	public $error = array();

	/**
	 * Instantiates a Bibblio API object
	 */
	public function bibblio_init() {
		try {
			$this->api_object = new Bibblio_Api_Client(
				Bibblio_Related_Posts_Configs::get( 'client_id' ),
				Bibblio_Related_Posts_Configs::get( 'client_secret' )
			);
		} catch ( BibblioException $e ) {
			$this->error['init_error'] = $e->getMessage();
		}

		$this->update_fixes();
	}


	/**
	 * Fixes settings or data require for plugin updates.
	 */
	public function update_fixes() {
		// get the list of update fixes that have been applied.
		$update_fixes = Bibblio_Related_Posts_Configs::get( 'update_fixes' );
		if ( ! $update_fixes ) {
			$update_fixes = [];
		}

		// add "whitelabel" styles to existing modules if not already present (and not already fixed).
		if ( ! isset( $update_fixes['module-whitelabel-class'] ) ) {
			$modules = Bibblio_Related_Posts_Configs::get( 'modules' );

			if ( $modules ) {
				foreach ( $modules as $i => $module ) {
					$modules[ $i ]['classes'] = trim( $modules[ $i ]['classes'] );

					// add the class if not already present.
					if ( ! is_int( strpos( $modules[ $i ]['classes'], 'bib--white-label' ) ) ) {
						$modules[ $i ]['classes'] = $modules[ $i ]['classes'] . ' bib--white-label';
					}
				}

				Bibblio_Related_Posts_Configs::set( 'modules', $modules );
			}

			$update_fixes['module-whitelabel-class'] = true;
		}

		Bibblio_Related_Posts_Configs::set( 'update_fixes', $update_fixes );
	}

	/**
	 * Returns the Bibblio Recommendation Key setting, or creates one if one is not set
	 *
	 * @return string Bibblio Recommendation Key
	 */
	public function get_recommendation_key() {
		$recommendation_key = Bibblio_Related_Posts_Configs::get( 'recommendation_key' );
		if ( ! $recommendation_key ) {
			try {
				$recommendation_key = $this->api_object->create_recommendation_key();
				Bibblio_Related_Posts_Configs::set( 'recommendation_key', $recommendation_key );
			} catch ( BibblioException $e ) {
				$this->error['get_recommendation_key'] = $e->getMessage();
			}
		}
		return $recommendation_key;
	}

	/**
	 * Returns the Bibblio Account Plan setting, or fetches it if not set
	 *
	 * @param  boolean $force_update Fetches the data from Bibblio rather than WP settings.
	 * @return object
	 */
	public function get_account_plan( $force_update = false ) {
		$account_plan = Bibblio_Related_Posts_Configs::get( 'account_plan' );
		if ( ! $account_plan || $force_update ) {
			if ( $this->api_object ) {
				$account_plan = $this->api_object->get_account_plan();
				if ( $account_plan ) {
					Bibblio_Related_Posts_Configs::set( 'account_plan', $account_plan );
					return $account_plan;
				}
			}
		} else {
			return $account_plan;
		}
	}

	/**
	 * Returns the count of Content Items stored with Bibblio from settings, or looked up from the Bibblio API
	 *
	 * @param  boolean $force_update Fetches the data from Bibblio rather than WP settings.
	 * @return integer
	 */
	public function get_content_item_count( $force_update = false ) {
		$content_item_count = Bibblio_Related_Posts_Configs::get( 'content_item_count' );

		// $content_item_count starts off as 0, which is false'y, so have to specifically check for it :P.
		if ( ( ! $content_item_count && ( 0 !== $content_item_count ) ) || $force_update ) {
			if ( $this->api_object ) {
				$content_item_count = $this->api_object->count_content_item();
				if ( is_int( $content_item_count ) ) {
					Bibblio_Related_Posts_Configs::set( 'content_item_count', $content_item_count );
					return $content_item_count;
				} else {
					return 0;
				}
			} else {
				return 0;
			}
		} else {
			return (int) $content_item_count;
		}
	}

	/**
	 * Returns the number of Content Items remaining that can be stored in the Bibblio account
	 *
	 * @param  boolean $force_update Fetches the data from Bibblio rather than WP settings.
	 * @return integer
	 */
	public function get_free_space( $force_update = false ) {
		$account_plan       = $this->get_account_plan( $force_update );
		$content_item_count = $this->get_content_item_count( $force_update );

		if ( 0 === (int) $account_plan['limits']['total']['content-items'] ) {
			$free_space = 999999;
		} else {
			$free_space = $account_plan['limits']['total']['content-items'] - $content_item_count;
		}

		return ( $account_plan['limits']['total']['content-items'] - $content_item_count );
	}

	/**
	 * Returns the recommendation data for the Bibblio account
	 *
	 * @param  boolean $force_update Fetches the data from Bibblio rather than WP settings.
	 * @return integer
	 */
	public function get_recommendation_usage( $force_update = false ) {
		$recommendation_usage = Bibblio_Related_Posts_Configs::get( 'recommendation_usage' );
		if ( ! $recommendation_usage || $force_update ) {
			if ( $this->api_object ) {
				$recommendation_usage = $this->api_object->get_monthly_usage();
				if ( $recommendation_usage ) {
					Bibblio_Related_Posts_Configs::set( 'recommendation_usage', $recommendation_usage );
					return $recommendation_usage;
				}
			}
		} else {
			return $recommendation_usage;
		}
	}

	/**
	 * Returns a month number for month number
	 *
	 * @param  integer $month_num Number of month.
	 * @return string
	 */
	public function get_month_name( $month_num ) {
		return date( 'F', mktime( 0, 0, 0, $month_num, 10 ) );
	}

	/**
	 * Scheduled the importer in WordPress's cronjobs
	 */
	public function schedule_importer() {
		// unflag any "failed to import" content items, so they're re-tried.
		delete_post_meta_by_key( '_bibblio_ingestion_error' );
		delete_post_meta_by_key( '_bibblio_ingestion_error_type' );

		if ( $this->count_posts_to_import() > 0 ) {
			Bibblio_Related_Posts_Configs::set( 'add_previous', true );
			if ( ! wp_next_scheduled( 'bibblio_new_import_event' ) ) {
				wp_clear_scheduled_hook( 'bibblio_new_import_event' );
				wp_schedule_event( time() + 5, 'five_minute', 'bibblio_new_import_event' );
			}
		} else {
			$this->bibblio_cancel_importing();
		}
	}

	/**
	 * Get the WP Posts to be imported
	 *
	 * @param  integer $limit Number of Posts to return.
	 * @return array
	 */
	public function get_posts_to_import( $limit = 100 ) {
		$posts     = [];
		$limit     = (int) $limit;
		$num_posts = $limit;

		// loop through each post type we're interested in.
		foreach ( $this->get_selected_post_types() as $post_type ) {
			$num_posts = $limit - count( $posts );

			// fetch only the count of posts without a content item id or import error.
			$args = array(
				'posts_per_page' => $num_posts,
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'meta_query'     => array( // slow query ok.
					array(
						'key'     => 'bibblio_content_item_id',
						'value'   => '',
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => '_bibblio_ingestion_error',
						'value'   => '',
						'compare' => 'NOT EXISTS',
					),
				),
			);

			$query = new WP_Query( $args );
			$posts = array_merge( $posts, $query->posts );

			// truncate the return data if it somehow exceeds the number of posts we wanted.
			if ( count( $posts ) > $limit ) {
				$posts = array_slice( $posts, 0, $limit );
			}

			// stop processing if we have enough posts.
			if ( count( $posts ) === $limit ) {
				return $posts;
			}
		}

		// return all the posts we could gc_collect_cycles().
		return $posts;
	}

	/**
	 * Count the WP Posts to be imported
	 *
	 * @return integer
	 */
	public function count_posts_to_import() {
		$count = 0;

		// loop through each post type we're interested in.
		foreach ( $this->get_selected_post_types() as $post_type ) {
			// fetch only the count of posts without a content item id or import error.
			$args = array(
				'posts_per_page' => -1,
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'meta_query'     => array( // slow query ok.
					array(
						'key'     => 'bibblio_content_item_id',
						'value'   => '',
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => '_bibblio_ingestion_error',
						'value'   => '',
						'compare' => 'NOT EXISTS',
					),
				),
				'fields'         => 'ids',
				'no_found_rows'  => true,
			);

			$query  = new WP_Query( $args );
			$count += $query->post_count;
		}

		return $count;
	}

	/**
	 * Consider a WP Posts being saved for ingestion in to Bibblio
	 *
	 * @param  integer $post_id WordPress Post ID.
	 * @return integer
	 */
	public function bibblio_handle_post_save( $post_id ) {
		// When a user is not changing the ingestion status or the post status (to published) do nothing.
		if ( isset( $_GET['bibblio_bulk_ingest'] ) && ( '-1' === $_GET['bibblio_bulk_ingest'] ) && isset( $_GET['_status'] ) && ( 'publish' !== $_GET['_status'] ) ) { // Input var ok, CSRF ok.

			return $post_id;
		}
		if ( isset( $_POST['bibblio_additional_custom_box'], $_POST['bibblio_meta_box_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['bibblio_meta_box_nonce'] ), 'bibblio_meta_box_nonce' ) && '' !== $_POST['bibblio_additional_custom_box'] ) {
			wp_verify_nonce( sanitize_key( $_POST['bibblio_meta_box_nonce'] ), 'bibblio_additional_custom_box' ); // Input var okay.
		}
		// actions that update a post without submitting the form fail permission and nonce checks and do NOT provide a metabox "to-be-ingested" value!
		// eg: scheduled post becomes published, or post becomes untrashed
		// Bulk editing sends though bibblio bulk ingest.
		$form_submitted = ( $_POST || isset( $_GET['bibblio_bulk_ingest'] ) ) ? true : false; // Input var okay.

		// if a user is submitting a post creation/update: .
		if ( $form_submitted ) {
			// check the user's permissions.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}

			// check that we have a valid nonce.
			if ( ! isset( $_POST['bibblio_meta_box_nonce'] ) && ! isset( $_GET['bibblio_bulk_edit_fields_nonce'] ) ) { // Input var okay.
				return $post_id;
			} else {
				if ( isset( $_POST['bibblio_meta_box_nonce'] ) && ( ! wp_verify_nonce( sanitize_key( $_POST['bibblio_meta_box_nonce'] ), 'bibblio_additional_custom_box' ) ) ) {  // Input var okay.
					return $post_id;
				}
				if ( isset( $_GET['bibblio_bulk_edit_fields_nonce'] ) && ( ! wp_verify_nonce( sanitize_key( $_GET['bibblio_bulk_edit_fields_nonce'] ), 'bibblio_bulk_edit_fields_nonce' ) ) ) {  // Input var okay.
					return $post_id;
				}
			}

			// store the user's "import" choice (regardless of post status).
			if ( $_POST ) {
				$import_checked = ( isset( $_POST['bibblio_import_checkbox'] ) ) ? true : false; // Input var okay.
			} elseif ( isset( $_GET['bibblio_bulk_ingest'] ) ) { // Input var okay.

				if ( 'true' === $_GET['bibblio_bulk_ingest'] ) { // Input var okay.
					$import_checked = true;
				} elseif ( 'false' === $_GET['bibblio_bulk_ingest'] ) { // Input var okay.
					$import_checked = false;
				} elseif ( '-1' === $_GET['bibblio_bulk_ingest'] ) { // Input var okay.
					$import_checked = ( get_post_meta( $post_id, '_bibblio_ingest', true ) ) ? true : false;
				} else {
					return $post_id;
				}
			} else {
				return $post_id;
			}

			if ( $import_checked ) {
				add_post_meta( $post_id, '_bibblio_ingest', $import_checked, true );
			} else {
				delete_post_meta( $post_id, '_bibblio_ingest' );
			}
		} else {
			// lookup what the user's choice was for the ingestion of this post.
			$import_checked = ( get_post_meta( $post_id, '_bibblio_ingest', true ) ) ? true : false;
		}

		$post            = get_post( $post_id );
		$published       = ( 'publish' === $post->post_status ) ? true : false;
		$content_item_id = $this->get_content_item_id( $post_id );

		// if set to import (and published?), then validate title+body and insert or update.
		if ( $import_checked && $published ) {
			// clear any existing import errors.
			delete_post_meta( $post_id, '_bibblio_ingestion_error' );
			delete_post_meta( $post_id, '_bibblio_ingestion_error_type' );

			// check that we have enough post info to import in to Bibblio.
			if ( empty( $post->post_title ) && empty( $post->post_content ) ) {
				// flag the post as having failed to import (missing required fields).
				add_post_meta( $post_id, '_bibblio_ingestion_error', 'true', true );
				add_post_meta( $post_id, '_bibblio_ingestion_error_type', 'title and content missing', true );
				return $post_id;
			}

			if ( empty( $post->post_title ) ) {
				// flag the post as having failed to import (missing required fields).
				add_post_meta( $post_id, '_bibblio_ingestion_error', 'true', true );
				add_post_meta( $post_id, '_bibblio_ingestion_error_type', 'title missing', true );
				return $post_id;
			}

			if ( empty( $post->post_content ) ) {
				// flag the post as having failed to import (missing required fields).
				add_post_meta( $post_id, '_bibblio_ingestion_error', 'true', true );
				add_post_meta( $post_id, '_bibblio_ingestion_error_type', 'content missing', true );
				return $post_id;
			}

			$content_item = $this->build_content_item( $post_id, $post );

			try {
				$this->bibblio_init();
				if ( isset( $this->error['init_error'] ) ) {
					return $post_id;
				}

				if ( $content_item_id ) {
					$this->update_post_to_bibblio( $post_id, $content_item_id, $content_item );
				} else {
					$this->add_post_to_bibblio( $post_id, $content_item );
				}
			} catch ( BibblioException $e ) {
				return $post_id;
			}
		} else {
			// check to see if this item was ingested since this code started running.
			$content_item_id = $this->get_content_item_id( $post_id );

			// if we already have a content item id, and the "import" box gets unchecked, delete from Bibblio.
			if ( $content_item_id ) { // Input var okay.
				try {
					$this->bibblio_init();
					if ( isset( $this->error['init_error'] ) ) {
						return $post_id;
					}
					$this->delete_post_from_bibblio( $post_id, $content_item_id );
				} catch ( BibblioException $e ) {
					return $post_id;
				}

				return $post_id;
			}
		}

		return $post_id;
	}

	/**
	 * Returns either the WordPress Post's "concept" or "excerpt", shortening as necessary
	 *
	 * @param  string $content WordPress Post concept.
	 * @param  string $excerpt WordPress Post excerpt.
	 * @return string
	 */
	private function make_description( $content, $excerpt ) {
		$ellipsis            = '...';
		$char_limit          = 100;
		$full_description    = ( ! empty( $excerpt ) ) ? $excerpt : $content;
		$trimmed_description = substr( $full_description, 0, $char_limit );
		$append_ellipsis     = ( strlen( $full_description ) > $char_limit ) ? true : false;
		$description         = ( $append_ellipsis ) ? ( $trimmed_description . $ellipsis ) : $trimmed_description;
		return $description;
	}

	/**
	 * Returns the "featured" image, or first occurring image with a "medium" size, of a post.
	 *
	 * @param  integer $post_id   WordPress Post id.
	 * @param  string  $post_body The content of the post.
	 * @return string
	 */
	private function get_post_image( $post_id, $post_body ) {
		// use the featured image if it's set.
		$feature_image = get_the_post_thumbnail_url( $post_id, 'medium' );
		if ( $feature_image ) {
			return $feature_image;
		}

		$image_url = null;                // returned if no image can be found.
		return $image_url;
	}

	/**
	 * Builds a Bibblio API compliant object for insertion
	 *
	 * @param  integer $post_id     WordPress Post id.
	 * @param  array   $post_params Submitted form fields.
	 * @return object
	 */
	public function build_content_item( $post_id, $post_params ) {
		$date_created = get_the_date( 'Y-m-d\TH:i:s\.000\Z', $post_id );
		$image_url    = $this->get_post_image( $post_id, $post_params->post_content );
		$author_name  = get_the_author_meta( 'user_nicename', get_post_field( 'post_author', $post_id ) );
		$post_url     = get_permalink( $post_id );
		$content      = sanitize_textarea_field( strip_shortcodes( wp_unslash( $post_params->post_content ) ) );
		$excerpt      = sanitize_textarea_field( strip_shortcodes( wp_unslash( $post_params->post_excerpt ) ) );
		$description  = $this->make_description( $content, $excerpt );

		$content_item = array(
			'name'                   => sanitize_text_field( strip_shortcodes( wp_unslash( $post_params->post_title ) ) ),
			'url'                    => $post_url,
			'text'                   => $content,
			'description'            => $description,
			'keywords'               => [],
			'image'                  => array(
				'contentUrl' => $image_url,
			),
			'moduleImage'            => array(
				'contentUrl' => $image_url,
			),
			'dateCreated'            => $date_created,
			'datePublished'          => $date_created,
			'author'                 => array(
				'name' => $author_name,
			),
			'customUniqueIdentifier' => 'wp-post-' . $post_id . '-' . get_the_guid( $post_id ),
		);

		return $content_item;
	}

	/**
	 * Handles Add/Update API exceptions
	 *
	 * @param  integer $post_id      WordPress Post id.
	 * @param  object  $e            WP Post Exception.
	 * @throws BibblioException      An exception returned by the API class.
	 */
	public function handle_insert_update_error( $post_id, $e ) {
		// flag the post as having failed to import.
		if ( $e->getMessage() === 'Error HTTP code: "403"' ) {
			add_post_meta( $post_id, '_bibblio_ingestion_error', 'true', true );
			add_post_meta( $post_id, '_bibblio_ingestion_error_type', 'auth failed or limit reached', true );
		} else {
			// (possibly an API/networking error).
			add_post_meta( $post_id, '_bibblio_ingestion_error', 'true', true );
			add_post_meta( $post_id, '_bibblio_ingestion_error_type', 'server', true );
		}
		throw new BibblioException( $e->getMessage() );
	}

	/**
	 * Adds a post to Bibblio
	 *
	 * @param  integer $post_id      WordPress Post id.
	 * @param  object  $content_item Bibblio formatted content item.
	 * @throws BibblioException      An exception returned by the API class.
	 * @return string|boolean
	 */
	public function add_post_to_bibblio( $post_id, $content_item ) {
		if ( $this->api_object ) {
			try {
				$response = $this->api_object->create_content_item( $content_item, $post_id );
				if ( isset( $response['contentItemId'] ) && ! empty( $response['contentItemId'] ) ) {
					Bibblio_Related_Posts_Configs::set( 'catalogue_id', $response['catalogueId'] );
					add_post_meta( $post_id, 'bibblio_content_item_id', $response['contentItemId'], true );
					return $response['contentItemId'];
				}
			} catch ( BibblioException $e ) {
				// if there's a custom unique identifier conflict, fetch the the content item id from Bibblio.
				if ( is_int( strpos( $e->getMessage(), 'customUniqueIdentifier must be unique or null' ) ) ) {
					$content_item_id = $this->api_object->get_content_item_id_by_custom_unique_identifier( $content_item['customUniqueIdentifier'] );
					if ( $content_item_id ) {
						add_post_meta( $post_id, 'bibblio_content_item_id', $content_item_id, true );
						delete_post_meta( $post_id, '_bibblio_ingestion_error' );
						delete_post_meta( $post_id, '_bibblio_ingestion_error_type' );
						try {
							$this->update_post_to_bibblio( $post_id, $content_item_id, $content_item );
							return $content_item_id;
						} catch ( BibblioException $e ) {
							// this is the update error.
							$this->handle_insert_update_error( $post_id, $e );
						}
					} else {
						// this is the initial insert error.
						$this->handle_insert_update_error( $post_id, $e );
					}
				} else {
					// this is the initial insert error.
					$this->handle_insert_update_error( $post_id, $e );
				}
			}
		}
		return false;
	}

	/**
	 * Updates post to Bibblio
	 *
	 * @param  integer $post_id         WordPress Post id.
	 * @param  string  $content_item_id Bibblio Content Item Id.
	 * @param  object  $content_item    Bibblio formatted content item.
	 * @return string|boolean
	 */
	public function update_post_to_bibblio( $post_id, $content_item_id, $content_item ) {
		try {
			$this->api_object->update_content_item( $content_item_id, $content_item );
			return true;
		} catch ( BibblioException $e ) {

			// the item might have been deleted from Bibblio, so re-add it.
			if ( is_int( strpos( $e->getMessage(), 'Error HTTP code: "404"' ) ) ) {
				try {
					delete_post_meta( $post_id, 'bibblio_content_item_id' );
					$this->add_post_to_bibblio( $post_id, $content_item );
				} catch ( BibblioException $e ) {
					if ( is_int( strpos( $e->getMessage(), 'Error HTTP code: "403"' ) ) ) {
						add_post_meta( $post_id, '_bibblio_ingestion_error', 'true', true );
						add_post_meta( $post_id, '_bibblio_ingestion_error_type', 'auth failed or limit reached', true );
					} else {
						add_post_meta( $post_id, '_bibblio_ingestion_error', 'true', true );
						add_post_meta( $post_id, '_bibblio_ingestion_error_type', 'server', true );
					}
				}
			} else {
				if ( is_int( strpos( $e->getMessage(), 'Error HTTP code: "403"' ) ) ) {
					add_post_meta( $post_id, '_bibblio_ingestion_error', 'true', true );
					add_post_meta( $post_id, '_bibblio_ingestion_error_type', 'auth failed or limit reached', true );
				} else {
					add_post_meta( $post_id, '_bibblio_ingestion_error', 'true', true );
					add_post_meta( $post_id, '_bibblio_ingestion_error_type', 'server', true );
				}
			}
		}
		return false;
	}

	/**
	 * Updates account preferences to Bibblio
	 *
	 * @param  object $account_preference    Bibblio account preference.
	 * @return string|boolean
	 */
	public function update_account_preference_to_bibblio( $account_preference ) {
		try {
			$this->api_object->update_account_preferences( $account_preference );
			return true;
		} catch ( BibblioException $e ) {
			return false;
		}
		return false;
	}

	/**
	 * Updates account preferences to Bibblio
	 *
	 * @return string|boolean
	 */
	public function get_account_preference_from_bibblio() {
		try {
			return $this->api_object->get_account_preferences();
		} catch ( BibblioException $e ) {
			return null;
		}
		return null;
	}

	/**
	 * Deletes a post from Bibblio
	 *
	 * @param  integer $post_id         WordPress Post id.
	 * @param  string  $content_item_id Bibblio Content Item Id.
	 */
	public function delete_post_from_bibblio( $post_id, $content_item_id ) {
		delete_post_meta( $post_id, 'bibblio_content_item_id' );
		delete_post_meta( $post_id, '_bibblio_ingestion_error' );
		delete_post_meta( $post_id, '_bibblio_ingestion_error_type' );

		try {
			$this->api_object->delete_content_item( $content_item_id );
		} catch ( BibblioException $e ) {
			$this->error['delete_error'] = $e->getMessage();
		}
	}

	/**
	 * Checks if this instance of the cronjob (based on $importer_id) is allowed to import posts.
	 *
	 * @param  integer $importer_id  A random number identifying this cronjob.
	 */
	public function should_index( $importer_id ) {
		// if the user clicked "Cancel import".
		if ( ! Bibblio_Related_Posts_Configs::get_uncached( 'add_previous' ) ) {
			self::debug( 'Existing / previous posts are not selected for import - cancelling.' );
			return false;
		}

		// clear the "importer_id" if processing hasn't happened recently.
		$importer_last_processed = Bibblio_Related_Posts_Configs::get_uncached( 'importer_last_processed' );
		if ( ! $importer_last_processed || ( time() - $importer_last_processed ) > 150 ) { // 2.5 minutes
			Bibblio_Related_Posts_Configs::set( 'importer_id', false );
		}

		// check if this cronjob is allowed to import.
		$running_importer = (int) Bibblio_Related_Posts_Configs::get_uncached( 'importer_id' );
		if ( $running_importer && ( $running_importer !== $importer_id ) ) {
			self::debug( 'Another importer seems to be running - cancelling.' );
			return false;
		}

		return true;
	}

	/**
	 * Importer that is run by cron and ingests old posts in to Bibblio
	 */
	public function import_all_posts() {
		$errors     = 0;
		$batch_size = 100; // number of posts to import per cronjob run.

		$this->bibblio_init();
		$posts_to_import = ( Bibblio_Related_Posts_Configs::get_uncached( 'add_previous' ) ) ? $this->count_posts_to_import() : 0;

		if ( $posts_to_import > 0 ) {
			self::debug( 'Found ' . $posts_to_import . ' posts to import.' );
			$posts_batch = $this->get_posts_to_import( $batch_size );

			self::debug( 'Created post batch-size of ' . count( $posts_batch ) . ' to import.' );
			$importer_id = wp_rand( 0, getrandmax() );

			foreach ( $posts_batch as $post ) {
				if ( self::should_index( $importer_id ) ) {
					// mark this cronjob as the one allowed to index.
					Bibblio_Related_Posts_Configs::set( 'importer_id', $importer_id );
					Bibblio_Related_Posts_Configs::set( 'importer_last_processed', time() );

					// skip any posts that are missing data Bibblio requires.
					if ( empty( $post->post_title ) && empty( $post->post_content ) ) {
						// flag the post as having failed to import (missing required fields).
						add_post_meta( $post->ID, '_bibblio_ingestion_error', 'true', true );
						add_post_meta( $post->ID, '_bibblio_ingestion_error_type', 'title and content missing', true );
						continue;
					}

					if ( empty( $post->post_title ) ) {
						// flag the post as having failed to import (missing required fields).
						add_post_meta( $post->ID, '_bibblio_ingestion_error', 'true', true );
						add_post_meta( $post->ID, '_bibblio_ingestion_error_type', 'title missing', true );
						continue;
					}

					if ( empty( $post->post_content ) ) {
						// flag the post as having failed to import (missing required fields).
						add_post_meta( $post->ID, '_bibblio_ingestion_error', 'true', true );
						add_post_meta( $post->ID, '_bibblio_ingestion_error_type', 'content missing', true );
						continue;
					}

					try {
						self::debug( ' * importing "' . $post->post_title . '"' );

						$content_item = $this->build_content_item( $post->ID, $post );

						// ensure the post still doesn't have a content item id, before importing.
						if ( ! $this->get_content_item_id( $post->ID ) ) {
							$this->add_post_to_bibblio( $post->ID, $content_item );
						}
					} catch ( BibblioException $e ) {
						// stop and cancel the cronjob if too many errors occur.
						$errors++;

						self::debug( 'Error importing post:' );
						self::debug( $e );

						if ( $errors > ( count( $posts_batch ) / 3 ) ) {
							self::debug( 'Too many import errors - cancelling.' );
							$this->bibblio_cancel_importing();
							return;
						}
					}
				} else {
					// stop processing posts and terminate the cronjob.
					echo 'Looks like there\'s already a cronjob running!';
					exit;
				}
			}

			self::debug( 'Finished importing batch of posts.' );

			// clear this cronjob's lock on indexing so another cronjob can startup.
			Bibblio_Related_Posts_Configs::set( 'importer_id', false );
			Bibblio_Related_Posts_Configs::set( 'importer_last_processed', false );

			// if there's nothing left to import.
			if ( $this->count_posts_to_import() === 0 ) {
				self::debug( 'Nothing left to import, cancelling cronjob.' );
				$this->bibblio_cancel_importing();
			}
		} else {
			// if there's nothing left to import or importing has been disabled.
			self::debug( 'Nothing to import, cancelling cronjob.' );
			$this->bibblio_cancel_importing();
		}
	}

	/**
	 * Deletes a post from Bibblio when a Post is deleted/trashed in WordPress
	 *
	 * @param  integer $post_id         WordPress Post id.
	 */
	public function bibblio_delete_post( $post_id ) {
		$content_item_id = $this->get_content_item_id( $post_id );
		if ( $content_item_id ) {
			$this->bibblio_init();

			if ( isset( $this->error['init_error'] ) ) {
				return $post_id;
			}

			try {
				$this->api_object->delete_content_item( $content_item_id );
				delete_post_meta( $post_id, 'bibblio_content_item_id' );
			} catch ( BibblioException $e ) {
				$this->error['delete_error'] = $e->getMessage();
			}

			if ( isset( $this->error['delete_error'] ) && ( '404:Not Found' === $this->error['delete_error'] ) ) {
				delete_post_meta( $post_id, 'bibblio_content_item_id' );
			}
		}

		return $post_id;
	}

	/**
	 * Deletes all data from Bibblio and related Post metadata
	 */
	public function delete_all_content() {
		$this->bibblio_init();

		if ( isset( $this->error['init_error'] ) ) {
			return;
		}

		// delete the catalogue (and its content items) from Bibblio.
		$delete_catalogue = Bibblio_Related_Posts_Configs::get( 'catalogue_id' );
		if ( $delete_catalogue ) {
			$this->api_object->delete_catalogue( $delete_catalogue );
		}

		// delete all Bibblio metadata for the content items.
		delete_post_meta_by_key( 'bibblio_content_item_id' );
	}

	/**
	 * Adds a custom cron schedule to WordPress
	 *
	 * @param  array $schedules WordPress cron schedules.
	 */
	public function add_custom_cron_time( $schedules ) {
		$schedules['five_minute'] = array(
			'interval' => 300,
			'display'  => esc_html__( '5 minutes', 'bibblio_text' ),
		);
		return $schedules;
	}

	/**
	 * Schedules the cron importer when users chooses to import their previous posts.
	 */
	public function bibblio_add_previous_posts() {
		$this->schedule_importer();
	}

	/**
	 * Defines a new WordPress cronjob for importing posts
	 */
	public function bibblio_new_cron_event() {
		if ( Bibblio_Related_Posts_Configs::get( 'add_previous' ) ) {
			if ( ! wp_next_scheduled( 'bibblio_new_import_event' ) ) {
				wp_clear_scheduled_hook( 'bibblio_new_import_event' );
				wp_schedule_event( time() + 5, 'five_minute', 'bibblio_new_import_event' );
			}
		}
	}

	/**
	 * The cronjob to handle WordPress imports and import error handling
	 */
	public function import_posts() {
		set_time_limit( 0 );

		if ( $this->count_posts_to_import() > 0 ) {
			self::debug( 'Starting import of posts...' );
			$this->import_all_posts();
		} else {
			self::debug( 'No posts found to import, cancelling.' );
			$this->bibblio_cancel_importing();
		}
	}

	/**
	 * Cancels the WordPress Importer
	 */
	public function bibblio_cancel_importing() {
		wp_clear_scheduled_hook( 'bibblio_new_import_event' );
		Bibblio_Related_Posts_Configs::set( 'add_previous', false );
		Bibblio_Related_Posts_Configs::set( 'importer_id', false );
		Bibblio_Related_Posts_Configs::set( 'importer_last_processed', false );
	}

	/**
	 * Returns all (usable) Custom Post Types in WordPress
	 */
	public function get_all_post_types() {
		$all_post_types = get_post_types(
			array(
				'public'   => true,
				'_builtin' => false,
			)
		);

		// "_builtin" above removes "pages" and "posts", but we want to keep default posts.
		$all_post_types[] = 'post';

		return $all_post_types;
	}

	/**
	 * Determines whether a site has custom post types or not
	 */
	public function has_custom_types() {
		return ( $this->get_all_post_types() === [ 'post' ] ) ? false : true;
	}

	/**
	 * Returns all (usable) Custom Post Types in WordPress
	 */
	public function get_selected_post_types() {
		if ( $this->has_custom_types() ) {
			$selected = Bibblio_Related_Posts_Configs::get( 'selected_post_types' );
			$selected = ( $selected ) ? $selected : [];
			$exclude  = [ 'page', 'attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset' ];
			$selected = array_diff( $selected, $exclude );
			$selected = array_unique( $selected );
		} else {
			$selected = [ 'post' ];
		}

		return $selected;
	}

	/**
	 * Returns the count of all published posts across all selected post types
	 */
	public function count_published_posts() {
		$total = 0;
		foreach ( $this->get_selected_post_types() as $post_type ) {
			$total += wp_count_posts( $post_type )->publish;
		}
		return $total;
	}

	/**
	 * Returns all posts, across all selected post types, with import errors
	 *
	 * @param string $order Direction to sort posts in.
	 * @return array
	 */
	public function get_posts_with_import_error( $order = 'ASC' ) {
		$error_posts = [];

		// loop through each post type we're interested in.
		foreach ( $this->get_selected_post_types() as $post_type ) {
			$args = array(
				'posts_per_page' => -1,
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => $order,
			);

			$query = new WP_Query( $args );
			if ( $query->have_posts() ) {
				foreach ( $query->posts as $post ) {
					// only return posts without a bibblio content item id and those that have not failed import.
					if ( get_post_meta( $post->ID, '_bibblio_ingestion_error', true ) ) {
						$error_posts[] = $post;
					}
				}
			}
		}

		return $error_posts;
	}

	/**
	 * Returns a count of all posts, across all selected post types, with import errors
	 *
	 * @return integer
	 */
	public function count_posts_with_import_error() {
		$count = 0;

		// loop through each post type we're interested in.
		foreach ( $this->get_selected_post_types() as $post_type ) {
			// fetch only the count of posts.
			$args = array(
				'posts_per_page' => -1,
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'meta_key'       => '_bibblio_ingestion_error', // slow query ok.
				'meta_query'     => array( // slow query ok.
					array(
						'key'     => '_bibblio_ingestion_error',
						'value'   => 'true',
						'compare' => '=',
					),
				),
				'fields'         => 'ids',
				'no_found_rows'  => true,
			);

			$query  = new WP_Query( $args );
			$count += $query->post_count;
		}

		return $count;
	}

	/**
	 * Returns the Bibblio content item id for a given post id
	 *
	 * @param  integer $post_id WordPress Post ID.
	 * @return string
	 */
	public function get_content_item_id( $post_id ) {
		return trim( (string) get_post_meta( $post_id, 'bibblio_content_item_id', true ) );
	}


	/**
	 * Returns the HTML (and JS) needed for displaying a Bibblio Related Content Module
	 *
	 * @param  array $options Module settings to be used.
	 * @return string
	 */
	public function get_module_html( $options ) {
		// refactored to a static method to avoid refreshing access token.
		// this function is provided for backward compatibility only - use the method below!
		return Bibblio_Related_Content_Module::get_module_html( $options );
	}

	/**
	 * Returns recency setting from Bibblio's API
	 *
	 * @return integer
	 */
	public function get_recency_value() {
		$default_recency_value = 0;

		if ( is_null( Bibblio_Related_Posts_Configs::get( 'recency_preference' ) ) ) {
			$bibblio_support = new Bibblio_Related_Posts_Support();
			$bibblio_support->bibblio_init();
			$account_preferences = $bibblio_support->get_account_preference_from_bibblio();

			// if we got an api response...
			if ( $account_preferences ) {
				// and there is a recency setting, save it...
				if ( isset( $account_preferences['recencyBoost'] ) ) {
					$recency_value = ( $account_preferences['recencyBoost'] ) ? $account_preferences['recencyBoost'] : 5;

				} else {
					// else default it to the default value.
					$recency_value = $default_recency_value;
				}

				Bibblio_Related_Posts_Configs::set( 'recency_preference', (int) $recency_value );
				return (int) $recency_value;

			} else {
				// ensure the slider will start at the correct value.
				return $default_recency_value;
			}
		} else {
			// use the existing saved value.
			return Bibblio_Related_Posts_Configs::get( 'recency_preference' );
		}

		return $default_recency_value;
	}

	/**
	 * Output debug text (if enabled)
	 *
	 * @param  text $text Debug message.
	 */
	public static function debug( $text ) {
		// removed for release build.
	}
}
