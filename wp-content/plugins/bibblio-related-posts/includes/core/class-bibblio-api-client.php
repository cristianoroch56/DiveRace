<?php
/**
 * Bibblio API Client
 *
 * @category   Bibblio_Api_Client
 * @package    Bibblio_Related_Posts
 * @subpackage Bibblio_Related_Posts/includes
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

/**
 * This is used to communicate with Bibblio API through http protocol. Read more http://docs.bibblio.apiary.io/
 */
class Bibblio_Api_Client {
	/**
	 * Production API base url
	 *
	 * @var string
	 */
	const ENDPOINT_PROD = 'https://api.bibblio.org/v1';

	/**
	 * Development|Debug API base url
	 *
	 * @var string
	 */
	const ENDPOINT_DEBUG = 'https://private-anon-b09227977a-bibblio.apiary-proxy.com/v1';

	/**
	 * Production mode is enabled
	 *
	 * @access protected
	 * @var    Bibblio_Api_Client    $prod_mode    Turn ON production mode
	 */
	protected $prod_mode = true;

	/**
	 * Credentials for retrieving access token every time
	 *
	 * @access protected
	 * @var    Bibblio_Api_Client    $credentials    Array with client ID & client Secret
	 */
	protected $credentials = null;

	/**
	 * Access Token value
	 *
	 * @access protected
	 * @var    Bibblio_Api_Client $token   Token for authorization in Bibblio API
	 */
	protected $token = null;

	/**
	 * Time when then access token will be expired
	 *
	 * @access protected
	 * @var    Bibblio_Api_Client    $tokenExpires    Access token will be expired at (timestamp)
	 */
	protected $token_expires_at = null;

	/**
	 * Bibblio_Api_Client constructor.
	 *
	 * @param string $client_id     Bibblio Client Id.
	 * @param string $client_secret Bibblio Secret.
	 * @param bool   $prod_mode     deprecated?.
	 * @param string $token         deprecated?.
	 * @param string $expired_at     deprecated?.
	 */
	public function __construct( $client_id, $client_secret, $prod_mode = false, $token = null, $expired_at = null ) {
		$this->credentials = array(
			'client_id'     => $client_id,
			'client_secret' => $client_secret,
		);
		$this->get_available_token();
	}

	/**
	 * Makes GET api requests to Bibblio
	 *
	 * @param string $path     Endpoint URI.
	 * @param string $options  Additional options (eg: headers / method).
	 * @throws BibblioException Generic API exception.
	 */
	public function bibblio_get( $path, $options = [] ) {
		$url      = $this->get_endpoint() . $path;
		$args     = array_merge( $this->get_default_options(), $options );
		$response = wp_remote_get( $url, $args );

		if ( is_wp_error( $response ) ) {
			throw new BibblioException( $response->get_error_message() );
		} elseif ( 200 !== $response['response']['code'] ) {
			throw new BibblioException( 'Error ' . $response['response']['code'] );
		} else {
			return json_decode( $response['body'], true );
		}
	}

	/**
	 * Makes POST api requests to Bibblio
	 *
	 * @param string $path     Endpoint URI.
	 * @param object $data     Data to be sent to the Bibblio endpoint.
	 * @param string $options  Additional options (eg: headers / method).
	 * @throws BibblioException Generic API exception.
	 */
	public function bibblio_post( $path, $data = [], $options = [] ) {
		$url = $this->get_endpoint() . $path;

		$args                            = $this->get_default_options();
		$args['headers']['Content-Type'] = 'application/json';
		$args['method']                  = 'POST';
		$args                            = array_merge( $args, $options );

		$data = ( ! is_string( $data ) ) ? wp_json_encode( $data ) : $data;

		$args = array_merge(
			$args,
			array(
				'body' => $data,
			)
		);

		$response = wp_remote_post( $url, $args );

		if ( is_wp_error( $response ) ) {
			throw new BibblioException( $response->get_error_message() );
		} elseif ( ( 200 !== $response['response']['code'] ) && ( 201 !== $response['response']['code'] ) ) {
			throw new BibblioException( 'Error HTTP code: "' . $response['response']['code'] . '" - ' . $response['body'] );
		} else {
			return json_decode( $response['body'], true );
		}
	}

	/**
	 * Wrapper that makes PUT api requests to Bibblio
	 *
	 * @param string $path     Endpoint URI.
	 * @param object $data     Data to be sent to the Bibblio endpoint.
	 * @param string $options  Additional options (eg: headers / method).
	 * @throws BibblioException Generic API exception.
	 */
	public function bibblio_put( $path, $data = [], $options = [] ) {
		$options = array_merge(
			$options,
			array(
				'method' => 'PUT',
			)
		);
		return $this->bibblio_post( $path, $data, $options );
	}

	/**
	 * Wrapper that makes DELETE api requests to Bibblio
	 *
	 * @param string $path     Endpoint URI.
	 * @param string $options  Additional options (eg: headers / method).
	 * @throws BibblioException Generic API exception.
	 */
	public function bibblio_delete( $path, $options = [] ) {
		$url = $this->get_endpoint() . $path;

		$args                            = $this->get_default_options();
		$args['headers']['Content-Type'] = 'application/json';
		$args['method']                  = 'DELETE';
		$args                            = array_merge( $args, $options );

		$response = wp_remote_post( $url, $args );

		if ( is_wp_error( $response ) ) {
			throw new BibblioException( $response->get_error_message() );
		} else {
			return true;
		}
	}

	/**
	 * Gets all Recommendation Keys that exist for the account.
	 *
	 * @return array
	 * @throws BibblioException Generic API exception.
	 */
	public function get_recommendation_keys() {
		return $this->bibblio_get( '/recommendation-keys', $options );
	}

	/**
	 * Creates a Recommendation Key for the account.
	 *
	 * @return string | null
	 * @throws BibblioException Generic API exception.
	 */
	public function create_recommendation_key() {
		$json = $this->bibblio_post( '/recommendation-keys' );
		return $json['recommendationKey'];
	}

	/**
	 * Creates a Content Item in Bibblio
	 *
	 * @param  object         $data    The Content Item data to be persisted.
	 * @param  string|integer $post_id The Post's WordPress ID.
	 * @return string
	 * @throws BibblioException Generic API exception.
	 */
	public function create_content_item( $data, $post_id ) {
		if ( $post_id ) {
			$content_item_id = trim( (string) get_post_meta( $post_id, 'bibblio_content_item_id', true ) );
			if ( $content_item_id ) {
				return $content_item_id;
			}
		}

		return $this->bibblio_post( '/content-items', $data );
	}

	/**
	 * Retrieves a Content Item from Bibblio by the customUniqueIdentifier
	 *
	 * @param  string $custom_unique_identifier The custom unique identifier of the post.
	 * @return string
	 * @throws BibblioException Generic API exception.
	 */
	public function get_content_item_id_by_custom_unique_identifier( $custom_unique_identifier ) {
		$content_items = $this->bibblio_get( '/content-items/?customUniqueIdentifier=' . $custom_unique_identifier );
		if ( $content_items['results'] && $content_items['results'][0] ) {
			return $content_items['results'][0]['contentItemId'];
		} else {
			return false;
		}
	}

	/**
	 * Updates a Content Item in Bibblio
	 *
	 * @param  string $content_item_id The Content Item ID to be updated.
	 * @param  object $data          The Content Item data.
	 * @return string
	 * @throws BibblioException Generic API exception.
	 */
	public function update_content_item( $content_item_id, $data ) {
		return $this->bibblio_put( '/content-items/' . $content_item_id, $data );
	}

	/**
	 * Updates account preferences in Bibblio
	 *
	 * @param  object $data          The Account Preference data.
	 * @return string
	 * @throws BibblioException Generic API exception.
	 */
	public function update_account_preferences( $data ) {
		return $this->bibblio_put( '/account/preferences/', $data );
	}

	/**
	 * Gets account preferences from Bibblio
	 *
	 * @return string
	 * @throws BibblioException Generic API exception.
	 */
	public function get_account_preferences() {
		return $this->bibblio_get( '/account/preferences/' );
	}

	/**
	 * Deletes a Content Item in Bibblio
	 *
	 * @param  string $content_item_id The Content Item ID to be deleted updated.
	 * @return boolean
	 * @throws BibblioException Generic API exception.
	 */
	public function delete_content_item( $content_item_id ) {
		$this->bibblio_delete( '/content-items/' . $content_item_id );
		return true;
	}

	/**
	 * Deletes a Catalogue (and all of its Content Items) in Bibblio
	 *
	 * @param  string $catalogue_id The Catalogue ID to be deleted.
	 * @return boolean
	 * @throws BibblioException Generic API exception.
	 */
	public function delete_catalogue( $catalogue_id ) {
		$args = array(
			'timeout'  => 60,
			'blocking' => false,
		);

		$this->bibblio_delete( '/catalogues/' . $catalogue_id . '?deleteAllContentItems=true', $args );
		return true;
	}

	/**
	 * Retrieve analytics and statistics for account
	 *
	 * @return array
	 * @throws BibblioException Generic API exception.
	 */
	public function get_monthly_usage() {
		$json = $this->bibblio_get( '/analytics/recommendations/usage' );
		return $json['results'];
	}

	/**
	 * Retrieve pricing plan info, including usage limits.
	 *
	 * @return array
	 * @throws BibblioException Generic API exception.
	 */
	public function get_account_plan() {
		try {
			$json = $this->bibblio_get( '/account/plan' );
			if ( isset( $json['plan'] ) ) {
				return $json['plan'];
			}
		} catch ( Exception $e ) {
			$this->record_exception( $e );
		}
	}

	/**
	 * Retrieve the number of content items stored with Bibblio
	 *
	 * @return integer The number of content items stored with Bibblio.
	 * @throws BibblioException Generic API exception.
	 */
	public function count_content_item() {
		try {
			$json = $this->bibblio_get( '/content-items/count' );
			if ( isset( $json['count'] ) ) {
				return (int) $json['count'];
			}
		} catch ( Exception $e ) {
			$this->record_exception( $e );
		}
	}

	/**
	 * Retrieve pricing plan info, including usage limits.
	 *
	 * @return array
	 */
	public function get_content_limit() {
		if ( 0 === $this->get_account_plan()['plan']['limits']['total']['content-items'] ) {
			$content_limit = 999999;
		} else {
			$content_limit = $this->get_account_plan()['plan']['limits']['total']['content-items'];
		}

		return $content_limit;
	}

	/**
	 * Returns available token for this time. Based on instance properties $token & $tokenExpires.
	 * If time for token is out - execute new request for obtaining available token
	 *
	 * @throws BibblioException Generic API exception.
	 */
	public function get_available_token() {
		if ( ! $this->token ) {
			$this->token_expires_at = Bibblio_Related_Posts_Configs::get( 'token_expires_at' );
			$this->token            = Bibblio_Related_Posts_Configs::get( 'token' );
		}

		$token_usable = ( ( (int) $this->token_expires_at - time() ) > 30 );

		if ( ! $this->token_expires_at || ! $token_usable ) {
			if ( $this->credentials['client_id'] && $this->credentials['client_secret'] ) {
				$url  = $this->get_endpoint() . '/token';
				$args = array(
					'headers' => array(
						'Content-Type' => 'application/x-www-form-urlencoded',
					),
					'body'    => 'client_id=' . $this->credentials['client_id'] . '&client_secret=' . $this->credentials['client_secret'],
				);

				$token_expires_base_time = time();
				$response                = wp_remote_post( $url, $args );

				if ( is_wp_error( $response ) ) {
					throw new BibblioException( $response->get_error_message() );
				} elseif ( 200 !== $response['response']['code'] ) {
					throw new BibblioException( 'Error ' . $response['response']['code'] );
				} else {
					$json                   = json_decode( $response['body'], true );
					$this->token            = $json['access_token'];
					$this->token_expires_at = $token_expires_base_time + $json['expires_in'];

					Bibblio_Related_Posts_Configs::set( 'token_expires_at', $this->token_expires_at );
					Bibblio_Related_Posts_Configs::set( 'token', $this->token );
				}
			}
		}

		return $this->token;
	}

	/**
	 * Retrieve endpoint for API based on mode of work (production or not)
	 *
	 * @return string
	 */
	protected function get_endpoint() {
		return ( $this->prod_mode ) ? static::ENDPOINT_PROD : static::ENDPOINT_DEBUG;
	}

	/**
	 * Create necessary options (adding auth header)
	 *
	 * @param  string $headers Additional headers.
	 * @return array
	 */
	protected function get_default_options( $headers = array() ) {
		$token                               = $this->get_available_token();
		$options['headers']                  = $headers;
		$options['headers']['Authorization'] = 'Bearer ' . $token;
		$options['timeout']                  = 20;
		return $options;
	}

	/**
	 * Records the most recent exception (for debugging purposes)
	 *
	 * @param  exception $exception The exception to log.
	 */
	protected function record_exception( $exception ) {
		$this->record_error( $exception->getMessage() );
	}

	/**
	 * Records the most recent error (for debugging purposes)
	 *
	 * @param  string $error The error message to log.
	 */
	protected function record_error( $error ) {
		$msg = date( 'd M Y H:i:s' ) . ' - ' . $error;
		Bibblio_Related_Posts_Configs::set( 'last_error', $msg );
	}
}
