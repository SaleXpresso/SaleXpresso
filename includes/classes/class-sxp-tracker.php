<?php
/**
 * The Tracker.
 * This handles session tracking, analytical data collection and any other pixel tracking
 * for campaign or third-party things.
 *
 * @package SaleXpresso/Tracker
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso;

use PasswordHash;
use WP_Post;
use WP_Post_Type;
use WP_Taxonomy;
use WP_Term;
use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Tracker.
 *
 */
class SXP_Tracker {
	
	/**
	 * Tracker Version.
	 * @var string
	 */
	const VERSION = 'v1.0';
	
	/**
	 * In-case we have updated the version.
	 *
	 * @var array
	 */
	protected $versions = [];
	
	/**
	 * Singleton instance.
	 *
	 * @var SXP_Tracker
	 */
	protected static $instance;
	
	/**
	 * Cookie name used for the session ID.
	 *
	 * @var string cookie name
	 */
	protected $_session_id_cookie;
	/**
	 * Cookie name used for the user ID.
	 *
	 * @var string cookie name
	 */
	protected $_customer_id_cookie;
	
	/**
	 * Session ID.
	 * Customer Unique Session/Cookie ID.
	 *
	 * @var string
	 */
	protected $_session_id;
	
	/**
	 * Session ID.
	 * Customer Unique Session/Cookie ID.
	 *
	 * @var string
	 */
	protected $_customer_id;
	
	/**
	 * Current User ID.
	 *
	 * @var bool|int
	 */
	protected $_user_id = false;
	
	/**
	 * Is Unique visits flag.
	 *
	 * @var bool
	 */
	protected $_is_unique = false;
	
	/**
	 * Tracking Script Name.
	 * This should not be named as tracker.js or analytics.js to avoid ad-blockers.
	 *
	 * @var string
	 */
	protected $script_name;
	
	/**
	 * Spoofed Acton name to form tracking api endpoint.
	 * @var string
	 */
	protected $track_action;
	
	/**
	 * Valid Collection Types.
	 *
	 * @var array
	 */
	protected $collect_types = [
		'error',
		'page-leave',
		'page-view',
		'event'
	];
	
	/**
	 * Return Current instance of this class.
	 * Creates one if not created.
	 *
	 * @return SXP_Tracker
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		sxp_doing_it_wrong( __METHOD__, __( 'Cloning is forbidden.', 'salexpresso' ), '2.1' );
	}
	
	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		sxp_doing_it_wrong( __METHOD__, __( 'Unserializing instances of this class is forbidden.', 'salexpresso' ), '2.1' );
	}
	
	/**
	 * SXP_Tracker constructor.
	 */
	private function __construct() {
		
		// Don't track wp-admin/
		if ( is_admin() ) {
			return;
		}
		
		$this->_session_id_cookie = 'wp_sxp_session_id_' . COOKIEHASH;
		$this->_customer_id_cookie = 'wp_sxp_customer_id_' . COOKIEHASH;
		
		$this->script_name  = 'sxp-session.js';
		$this->track_action = 'collect';
		
		$this->get_session_cookie();
		
		$this->set_session();
		
		$this->setup_tracking_script();
	}
	
	/**
	 * Set Session.
	 *
	 * @return void
	 */
	private function set_session() {

		if( ! sxp_exclude_user_from_session_tracking() ) {
			
			if ( empty( $this->_session_id ) ) {
				$this->_is_unique = true;
				$this->_session_id = $this->generate_random_id();
				sxp_setcookie( $this->_session_id_cookie, $this->_session_id, 0, $this->use_secure_cookie(), true );
			}
			
			if( empty( $this->_customer_id ) ) {
				$this->_customer_id = $this->generate_random_id();
			}
			
			if ( is_user_logged_in() ) {
				
				$this->_user_id = get_current_user_id();
				// Get saved cookie id.
				$saved_cookie_id = get_user_meta( $this->_user_id, '_sxp_cookie_id', true );
				
				if ( ! empty( $saved_cookie_id ) && $saved_cookie_id !== $this->_customer_id ) {
					$this->_customer_id = $saved_cookie_id;
				}
				
				if ( empty( $saved_cookie_id ) ) {
					update_user_meta( $this->_user_id, '_sxp_cookie_id', $this->_customer_id );
				}
			}
			
			sxp_setcookie( $this->_customer_id_cookie, $this->_customer_id, time() + $this->get_customer_id_expiration(), $this->use_secure_cookie(), true );
		}
	}
	
	/**
	 * Get the session cookie, if set. Otherwise return false.
	 *
	 * Session cookies without a customer ID are invalid.
	 *
	 * @return void
	 */
	public function get_session_cookie() {
		$this->_customer_id = isset( $_COOKIE[ $this->_customer_id_cookie ] ) ? $this->clean_cookie( $_COOKIE[ $this->_customer_id_cookie ] ) : false; // phpcs:ignore
		$this->_session_id  = isset( $_COOKIE[ $this->_session_id_cookie ] ) ? $this->clean_cookie( $_COOKIE[ $this->_session_id_cookie ] ) : false; // phpcs:ignore
	}
	
	private function clean_cookie( $input ) {
		return sanitize_text_field( wp_unslash( $input ) );
	}
	
	/**
	 * Should the session cookie be secure?
	 *
	 * @return bool
	 */
	protected function use_secure_cookie() {
		return apply_filters( 'sxp_session_use_secure_cookie', sxp_site_is_https() && is_ssl() );
	}
	
	/**
	 * Get the session id.
	 * 
	 * @return string
	 */
	public function get_session_id() {
		return $this->_session_id;
	}
	
	/**
	 * Get the customer session id.
	 *
	 * @return string
	 */
	public function get_customer_id() {
		return $this->_customer_id;
	}
	
	/**
	 * Get Customer ID cookie expiration time in seconds.
	 *
	 * @return int
	 */
	private function get_customer_id_expiration() {
		$expiration = get_option( 'salexpresso_st_customer_id_expiration' );
		if ( empty( $expiration ) || ! isset( $expiration['number'], $expiration['unit'] ) ) {
			$expiration = [ 'number' => 2, 'unit' => 'years' ];
		}
		//'days'   => __( 'Day(s)', 'woocommerce' ),
		//							'weeks'  => __( 'Week(s)', 'woocommerce' ),
		//							'months' => __( 'Month(s)', 'woocommerce' ),
		//							'years'  => __( 'Year(s)', 'woocommerce' ),
		
		switch( $expiration['unit'] ) {
			case 'years':
				$multiply = YEAR_IN_SECONDS;
				break;
			case 'months':
				$multiply = MONTH_IN_SECONDS;
				break;
			case 'weeks':
				$multiply = WEEK_IN_SECONDS;
				break;
			case 'days':
			default:
				$multiply = DAY_IN_SECONDS;
				break;
		}
		if ( $multiply ) {
			return ( $expiration['number'] * $multiply );
		}
		return ( 2 * YEAR_IN_SECONDS );
	}
	
	/**
	 * Generate a unique customer ID for guests, or return user ID if logged in.
	 *
	 * Uses Portable PHP password hashing framework to generate a unique cryptographically strong ID.
	 *
	 * @return string
	 */
	public function generate_random_id() {
		if ( ! class_exists( 'PasswordHash' ) ) {
			require_once ABSPATH . 'wp-includes/class-phpass.php';
		}
		$pw_hash = new PasswordHash( 8, false );
		return md5( $pw_hash->get_random_bytes( 32 ) );
	}
	
	private function setup_tracking_script() {
		
		add_action( 'salexpresso_api_' . sanitize_key( $this->script_name ), [ $this, 'get_tracking_script' ] );
		add_action( 'salexpresso_api_' . $this->track_action , [ $this, 'tracking_collect' ] );
		add_action( 'wp_footer', [ $this, 'print_tracking_script' ] );
	}
	
	public function print_tracking_script() {
		$tracker_file = sxp_api_link( $this->script_name );
		wp_register_script( 'sxp-analytics', $tracker_file, [], false, true );
		
		wp_localize_script( 'sxp-analytics', 'sxpSessionOnPageData', [
			'wp_object_type' => $this->get_object_types(),
			'wp_object_id' => (int) get_queried_object_id(),
		] );
		wp_enqueue_script( 'sxp-analytics' );
		$no_script = sxp_api_link( $this->track_action );
		$no_script = add_query_arg(
			[
				'mode'     => 'standalone',
				'referrer' => isset( $_SERVER['HTTP_REFERER'] ) ? sanitize_text_field( $_SERVER['HTTP_REFERER'] ) : '',
				'path'     => isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( $_SERVER['REQUEST_URI'] ) : '',
			],
			$no_script
		);
		?>
		<noscript>
			<img src="<?php echo esc_url( $no_script ); ?>" alt="">
		</noscript>
		<?php
	}
	
	/**
	 * Get WP Object Type
	 * @return array
	 */
	private function get_object_types() {
		$queried_object = get_queried_object();
		$type = '';
		$subtype = '';
		
		if ( $queried_object instanceof WP_Taxonomy ) {
			$type = 'taxonomy-archive';
			$subtype = $queried_object->name;
		} else if ( $queried_object instanceof WP_Term ) {
			$type = 'term';
			$subtype = $queried_object->taxonomy;
		} else if ( $queried_object instanceof WP_Post_Type ) {
			$type = 'post-type-archive';
			$subtype = $queried_object->name;
		} else if ( $queried_object instanceof WP_Post ) {
			$type = 'wp_post';
			$subtype = $queried_object->post_type;
		} else if ( $queried_object instanceof WP_User ) {
			$type = 'wp_user';
		}
		
		return [
			'type'    => $type,
			'subtype' => $subtype,
		];
	}
	
	/**
	 * Handle response for tracking pixel request.
	 * @return void
	 */
	public function get_tracking_script() {
		$content = $this->prepare_tracking_script();
		header( 'Content-Length: ' . strlen( $content ) );
		header( 'Content-Type: application/javascript' );
		echo $content;
		die();
	}
	
	/**
	 * Prepare the tracker js output.
	 *
	 * @return string
	 */
	private function prepare_tracking_script() {
		
		$file = SXP_Assets::get_instance()->get_full_path( 'tracker.js' );
		$content = file_get_contents( $file );
		$analytics_opts = [
			'{{api_url}}'           => sxp_api_link( $this->track_action ),
			'{{VERSION}}'           => self::VERSION,
			'{{tracker}}'           => $this->script_name,
			"'{{tracker_options}}'" => (object) [
				'recordDnt' => true,
			],
		];
		
		foreach( $analytics_opts as $search => $replace ) {
			if ( ! is_string( $replace ) ) {
				$replace = wp_json_encode( $replace );
			}
			$content = str_replace( $search, $replace, $content );
		}
		
		$content = preg_replace( '/\/\/# sourceMappingURL.+$/', '', $content );
		
		return $content;
	}
	
	/**
	 * Handle Request on tracking collect endpoint.
	 *
	 * @return void
	 */
	public function tracking_collect() {
		// No Cache Header already set by api handler.
		/** @noinspection SpellCheckingInspection */
		$image = base64_decode( 'R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==' );
		header('Content-Type: image/gif');
		header( 'Content-Length: ' . strlen( $image ) );
		echo $image; // Let the browser have something to play with...
		// Handle the data...
		$data = $this->get_tracking_data();
		if ( $data ) {
			// possible types: error, page-leave, page-view, event,
			ob_start();
			/**
			 * Trigger Generic Action for any collect request.
			 *
			 * @param array $data
			 */
			do_action( 'salexpresso_analytics_collect', $data );
			
			if ( 'event' === $data['type'] ) {
				do_action( 'salexpresso_analytics_collect_event', $data );
				do_action( "salexpresso_analytics_collect_event_{$data['event']}", $data );
			}
			
			$this->update_user_acquired_via( $data );
			
			unset( $data['ref_host'] );
			// insert the data into db.
			$now = current_time( 'mysql' );
			$data['created'] = $now;
			$data['created_gmt'] = get_date_from_gmt( $now );
			
			if ( ! empty( $data['session_meta'] ) ) {
				$data['session_meta'] = wp_json_encode( $data['session_meta'] );
			}
			
			// Insert To analytics.
			global $wpdb;
			$wpdb->hide_errors();
			$wpdb->suppress_errors();
			$wpdb->insert( $wpdb->sxp_analytics, $data );
			
			ob_end_clean();
		}
		die();
	}
	
	/**
	 * Get Sanitized Tracking Data Set by pixel (or tracker js).
	 *
	 * @return array|bool
	 */
	private function get_tracking_data() {
		// Default data object, so we can validate and insert into the db easily.
		$defaults = [
			'session_id'      => '', // session id this should be unique for each session.
			'visitor_id'      => '', // user id, this should be unique for each user and guest.
			'page_id'         => '', // page id, this should be unique for each page.
			'duration'        => '', // int duration on page.
			'scrolled'        => '', // scroll position before exit the page.
			'path'            => '', // path user visiting (page slug).
			'viewport_width'  => '', // device viewport width.
			'viewport_height' => '', // device viewport height.
			'screen_width'    => '', // device screen width.
			'screen_height'   => '', // device screen height.
			'language'        => '', // language in browser's user-agent.
			'country'         => '', // country should be detected from the ip.
			'city'            => '', // same as country.
			'timezone'        => '', // time zone in browser's user-agent.
			'is_unique'       => '', // is a unique (new) visit.
			'is_organic'      => '', // is a organic visit.
			'source'          => '', // utm_source.
			'medium'          => '', // utm_medium.
			'campaign'        => '', // utm_campaign.
			'term'            => '', // utm_term.
			'content'         => '', // utm_content.
			'referrer'        => '', // the referrer.
			'ref_host'        => '', // the referrer hostname.
			'type'            => '', // collection type.
			'event'           => '', // event category.
			'session_meta'    => '', // extra meta.
			'bot'             => false, // is bot, this should be true if bot detected.
			'version'         => self::VERSION, // analytics version.
		];
		
		if ( isset( $_REQUEST['payload'] ) ) {
			$_REQUEST = wp_unslash( $_REQUEST );
			$payload = json_decode( $_REQUEST['payload'], true );
			if ( is_array( $payload ) && isset( $payload['type'], $payload['page_id'], $payload['version'] ) ) {
				unset( $_REQUEST['payload'] );
				$_REQUEST = array_merge( $payload, $_REQUEST );
			}
		}
		
		if ( isset( $_REQUEST['mode'] ) && 'standalone' === $_REQUEST['mode'] ) {
			foreach ( [ 'source', 'medium', 'campaign', 'term', 'content' ] as $k ) {
				if ( isset( $_REQUEST[ 'utm_' . $k ] ) ) {
					$defaults[ $k ] = sanitize_text_field( $_REQUEST[ 'utm_' . $k ] );
					unset( $_REQUEST[ 'utm_' . $k ] );
				}
			}
		}
		
		// parse the request data.
		$data = wp_parse_args( wc_clean( $_REQUEST ), $defaults );
		
		// Beacon send on page leave event.
		// And doesn't populate the $_REQUEST, $_GET and $_POST variables.
		// So if beacon data presents the it should have priority over the $_REQUEST variable.
		$payload = file_get_contents( 'php://input' );
		
		if ( ! empty( $payload ) ) {
			/** @noinspection PhpComposerExtensionStubsInspection */
			$payload = json_decode( $payload, true );
			if ( is_array( $payload ) && isset( $payload['type'] ) && in_array( $payload['type'], $this->collect_types ) ) {
				$payload = wc_clean( $payload );
				$data = wp_parse_args( $payload, $data );
			}
			unset( $payload );
		}
		
		if ( empty( $data['referrer'] ) && ( isset( $_SERVER['HTTP_REFERER'] ) && ! empty( $_SERVER['HTTP_REFERER'] ) ) ) {
			$data['referrer'] = sanitize_text_field( $_SERVER['HTTP_REFERER'] );
		}
		
		// dont take any raw meta...
		$data['session_meta'] = [];
		
		// Type must be a valid collection type.
		if ( ! in_array( $data['type'], $this->collect_types ) ) {
			return false;
		}
		
		// Event value must be present if collection type is event.
		if ( 'event' === $data['type'] && ( empty( $data['event'] ) || ! is_string( $data['event'] ) ) ) {
			return false;
		}
		
		if ( 'event' !== $data['type'] && ! empty( $data['event'] ) ) {
			$data['event'] = '';
		}
		
		if ( ! empty( $data['event'] ) ) {
			// sanitize to correct format for standalone request.
			$data['event'] = strtolower( $data['event'] );
			$data['event'] = preg_replace( '/[^a-z0-9]+/', '-', $data['event'] );
			$data['event'] = preg_replace( '/(^-|-$)/', '', $data['event'] );
		}
		
		if ( empty( $data['session_id'] ) || 32 !== strlen( $data['session_id'] ) ) {
			$data['session_id'] = $this->get_session_id();
		}
		
		if ( empty( $data['visitor_id'] ) || 32 !== strlen( $data['visitor_id'] ) ) {
			$data['visitor_id'] = $this->get_customer_id();
		}
		
		if ( empty( $data['page_id'] ) || 36 !== strlen( $data['page_id'] ) ) {
			$data['page_id'] = '';
		}
		
		$self_host = wp_parse_url( site_url(), PHP_URL_HOST );
		$ref_host  = wp_parse_url( $data['referrer'], PHP_URL_HOST );
		
		// Referral & affiliate detection.
		$referral  = false;
		$affiliate = false;
		
		// Parameters. No parameters means disabled.
		$_referral  = get_option( 'salexpresso_st_referral_parameter' );
		$_affiliate = get_option( 'salexpresso_st_affiliate_parameter' );
		
		if ( $_affiliate ) {
			if ( isset( $data[ $_affiliate ] ) && ! empty( $data[ $_affiliate ] ) ) {
				$affiliate = sanitize_text_field( $data[ $_affiliate ] );
			}
			unset( $data[ $_affiliate ] );
		}
		
		if ( $_referral && ! $affiliate ) {
			if ( $_affiliate !== $_referral ) {
				if ( isset( $data[ $_referral ] ) && ! empty( $data[ $_referral ] ) ) {
					$referral = sanitize_text_field( $data[ $_referral ] );
				}
			}
			
			unset( $data[ $_referral ] );
		}
		
		// @TODO improve is_organic logic...
		$data['is_organic'] = (int) (
			! empty( $ref_host )
			&& false === $affiliate
			&& false === $referral
			&& $self_host !== $ref_host
			&& empty( $data['source'] )
			&& empty( $data['campaign'] )
		);
		$data['is_unique']  = (int) $this->_is_unique;
		$data['bot']        = (int) $data['bot'];
		$data['ref_host']   = $ref_host;
		
		unset( $self_host );
		unset( $ref_host );
		unset( $_affiliate );
		unset( $_referral );
		
		if ( self::VERSION !== $data['version'] ) {
			if ( ! in_array( $data['version'], $this->versions ) ) {
				$data['version'] = self::VERSION;
			}
		}
		
		if ( isset( $data['meta'] ) && ! empty( $data['meta'] ) ) {
			$meta = [];
			if ( isset( $data['meta']['object_type'] ) && is_array ( $data['meta']['object_type'] ) ) {
				if ( isset( $data['meta']['object_type']['type'], $data['meta']['object_type']['subtype'] ) ) {
					$allowed = [ 'taxonomy-archive', 'term', 'post-type-archive', 'wp_post', 'wp_user' ];
					if ( in_array( $data['meta']['object_type']['type'], $allowed ) ) {
						$meta['wp_object'] = [
							'id'      => absint( $data['meta']['object_id'] ),
							'type'    => $data['meta']['object_type']['type'],
							'subtype' => $data['meta']['object_type']['subtype'],
						];
					}
				}
			}
			
			if ( 'event' === $data['type'] ) {
				if ( isset( $data['meta']['event'] ) ) {
					$meta['event'] = [];
					foreach( $data['meta']['event'] as $event_meta ) {
						if ( ! isset( $event_meta['label'], $event_meta['value'] ) ) {
							continue;
						}
						if ( strlen( $event_meta['label'] ) > 100 || strlen( $event_meta['value'] ) > 100 ) {
							continue;
						}
						$meta['event'][] = [
							'label' => esc_html( $event_meta['label'] ),
							'value' => esc_html( $event_meta['value'] ),
						];
					}
					
					if ( empty( $meta['event'] ) ) {
						unset( $meta['event'] );
					}
				}
			}
			
			unset( $data['meta'] );
			
			$data['session_meta'] = $meta;
		}
		
		if ( 'error' === $data['type'] && isset( $data['url'] ) && ! empty( $data['url'] ) ) {
			$error_url = esc_url_raw( $data['url'] );
			if ( $error_url ) {
				$data['session_meta']['error_page'] = $error_url;
			}
			unset( $data['url'] );
			unset( $error_url );
		}
		
		$data['session_meta']['ip'] = SXP_IP::get_ip();
		
		if ( false !== $affiliate ) {
			$data['session_meta']['affiliate'] = $affiliate;
		}
		
		if ( false !== $referral ) {
			$data['session_meta']['referral'] = $referral;
		}
		
		// remove data from the $_REQUEST variable.
		unset( $data['sxp-api'] );
		unset( $data['woocommerce-login-nonce'] );
		unset( $data['_wpnonce'] );
		unset( $data['woocommerce-reset-password-nonce'] );
		
		return $data;
	}
	
	/**
	 * Get User Acquired Via Info from raw data.
	 *
	 * @param array $data Raw Session Tracking Data.
	 *
	 * @return string
	 */
	private function get_acquired_via( $data ) {
		$acquired_via = esc_html_x( 'Direct Visit', 'Set User Acquired Via Meta', 'salexpresso' );
		if ( isset( $data['session_meta']['affiliate'] ) && ! empty( $data['session_meta']['affiliate'] ) ) {
			$acquired_via = $data['session_meta']['affiliate'];
			if( has_filter( 'salexpresso_acquired_via_affiliate_id' ) ) {
				$ref_host = apply_filters( 'salexpresso_acquired_via_affiliate_id', $acquired_via );
			} else {
				$acquired_via = sprintf(
					esc_html_x( 'Affiliate::%s', 'Set User Acquired Via Meta', 'salexpresso' ),
					$acquired_via
				);
			}
		} else if ( isset( $data['session_meta']['referral'] ) && ! empty( $data['session_meta']['referral'] ) ) {
			$acquired_via = $data['session_meta']['referral'];
			if( has_filter( 'salexpresso_acquired_via_referral_id' ) ) {
				$acquired_via = apply_filters( 'salexpresso_acquired_via_referral_id', $acquired_via );
			} else {
				$acquired_via = sprintf(
					esc_html_x( 'Referral::%s', 'Set User Acquired Via Meta', 'salexpresso' ),
					$acquired_via
				);
			}
		} else if ( ! empty( $data['source'] ) && ! empty( $data['campaign'] ) ) {
			$acquired_via = $data['campaign'] . '(' . $data['source'] . ')';
		} else if( isset( $data['ref_host'] ) && ! empty( $data['ref_host'] ) ) {
			$acquired_via = $data['ref_host'];
		}
		
		return $acquired_via;
	}
	
	/**
	 * Update user's acquired_via meta.
	 *
	 * @param array $data Session tracking data.
	 *
	 * @return void
	 */
	private function update_user_acquired_via( $data ) {
		if ( is_user_logged_in() ) {
			$_acquired_via = get_user_meta( $this->_user_id, 'acquired_via', true );
			$is_organic = 0;
			$acquired_via = '';
			if ( ! $_acquired_via ) {
				// doesn't have acquired_via.
				if ( ! $this->_is_unique ) {
					global $wpdb;
					// not unique.
					// find the first visit.
					/** @noinspection SqlResolve */
					$record = (array) $wpdb->get_row(
						$wpdb->prepare(
							"SELECT * FROM {$wpdb->sxp_analytics} WHERE user_id = %s ORDER BY created DESC LIMIT 1;",
							$this->_customer_id
						)
					);
					
					if ( ! empty( $record ) ) {
						$record['ref_host'] = isset( $record['referrer'] ) && ! empty( $record['referrer'] ) ? wp_parse_url( $record['referrer'], PHP_URL_HOST ) : '';
						$is_organic = isset( $record['is_organic'] ) ? $record['is_organic'] : 0;
						$record['session_meta'] = ! empty( $record['session_meta'] ) ? json_decode( $record['session_meta'], true ) : [];
						$acquired_via = $this->get_acquired_via( $record );
					} else {
						$acquired_via = $this->get_acquired_via( $data );
						$is_organic = $data['is_organic'];
					}
				} else {
					$acquired_via = $this->get_acquired_via( $data );
					$is_organic = $data['is_organic'];
				}
			} else {
				$_is_organic = (int) get_user_meta( $this->_user_id, 'is_organic', true );
				$total_spent = (float) wc_get_customer_total_spent( $this->_user_id );
				if ( $_is_organic && 0 == $total_spent && ! $data['is_organic'] ) {
					// ony update organic user if new visits isn't organic & user hasn't purchased anything yet.
					$acquired_via = $this->get_acquired_via( $data );
					$is_organic = $data['is_organic'];
				}
			}
			// update user meta.
			if ( ! empty( $acquired_via ) ) {
				if ( has_filter( 'salexpresso_update_user_acquired_via' ) ) {
					$_acquired_via = trim( apply_filters( 'salexpresso_update_user_acquired_via', $acquired_via, $data ) );
					if ( ! empty( $_acquired_via ) ) {
						$acquired_via = $_acquired_via;
						unset( $_acquired_via );
					}
				}
				update_user_meta( $this->_user_id, 'acquired_via', $acquired_via );
				update_user_meta( $this->_user_id, 'is_organic', $is_organic );
			}
		}
	}
}
// End of file class-sxp-tracker.php.
