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
		
		// Don't track dashboard or disabled role or user IDs.
		if ( is_admin() || sxp_exclude_user_from_session_tracking() ) {
			return;
		}
		
		$this->_session_id_cookie  = 'wp_sxp_session_id_' . COOKIEHASH;
		$this->_customer_id_cookie = 'wp_sxp_customer_id_' . COOKIEHASH;
		
		$this->script_name  = 'sxp-session.js';
		$this->track_action = 'collect';
		
		/**
		 * new request Random session and identification generated.
		 * browse .. browse .. browse ..
		 * User logged in.
		 *  * update the session id if identification is changed.
		 *  * current session will be owned by this user (identification updated).
		 * and the session id will have this new identification.
		 * if user logged out. keep the same session and identification ..
		 * but expire the session id only.
		 *
		 * SO...
		 *
		 * on logout expire the session id cookie only.
		 *
		 */
		
		// clear session & customer id cookie on logout.
		add_action( 'wp_logout', [ $this, 'forget_session' ], -1 );
		// nonce_user_logged_out
		
		$this->get_session_cookie();
		
		$this->set_session();
		
		$this->setup_tracking_script();
	}
	
	/**
	 * Return true if the current user has an active session, i.e. a cookie to retrieve values.
	 *
	 * @return bool
	 */
	public function has_session() {
		return ! empty( $this->_session_id ) || ! empty( $this->_customer_id ) || is_user_logged_in();
	}
	
	/**
	 * Forget Session Cookies
	 * @return void
	 */
	public function forget_session() {
		if ( $this->has_session() ) {
			
			$_past = time() -YEAR_IN_SECONDS;
			
			sxp_setcookie( $this->_session_id_cookie, '', $_past, $this->use_secure_cookie(), true );
//			sxp_setcookie( $this->_customer_id_cookie, '', $_past, $this->use_secure_cookie(), true );
			
			$this->_session_id  = '';
//			$this->_customer_id = '';
		}
	}
	
	/**
	 * Set Session.
	 *
	 * @return void
	 */
	private function set_session() {
		$this->set_session_id();
		$this->set_customer_id();
	}
	
	/**
	 * Set Session ID Cookie.
	 *
	 * @param bool $reset Optional. Reset current session id if set to true.
	 *
	 * @return void
	 */
	private function set_session_id( $reset = false ) {
		if ( $reset ) {
			$this->_session_id = '';
		}
		if ( empty( $this->_session_id ) ) {
			$this->_is_unique = true;
			$this->_session_id = sxp_get_uuid_v4();
			// set cookie.
			sxp_setcookie( $this->_session_id_cookie, $this->encode_cookie_value( $this->_session_id ), 0, $this->use_secure_cookie(), true );
		}
	}
	
	/**
	 * Set Customer ID cookie.
	 * @return void
	 */
	private function set_customer_id() {
		
		// Flag if session_id needs to be changed.
		
		$is_customer_id_changed = false;
		
		// Cookie empty. Generate New one.
		if( empty( $this->_customer_id ) ) {
			$this->_customer_id = sxp_get_uuid_v4();
		}
		
		if ( is_user_logged_in() ) {
			
			$this->_user_id = get_current_user_id();
			// Get saved cookie id.
			$saved_cookie_id = get_user_meta( $this->_user_id, '_sxp_cookie_id', true );
			
			// User already have cookie and browser cookie doesn't match
			if ( ! empty( $saved_cookie_id ) && $saved_cookie_id !== $this->_customer_id ) {
				$this->_customer_id = $saved_cookie_id;
				$is_customer_id_changed = true;
			}
			
			// User doesn't have cookie id, set current browser cookie id as user's cookie id.
			if ( empty( $saved_cookie_id ) || ! is_string( $saved_cookie_id ) ) {
				update_user_meta( $this->_user_id, '_sxp_cookie_id', $this->_customer_id );
			}
		}
		
		if ( $is_customer_id_changed ) {
			$this->set_session_id( true );
		}
		
		sxp_setcookie( $this->_customer_id_cookie, $this->encode_cookie_value( $this->_customer_id ), time() + $this->get_customer_id_expiration(), $this->use_secure_cookie(), true );
	}
	
	/**
	 * Get the session cookie, if set. Otherwise return false.
	 *
	 * Session cookies without a customer ID are invalid.
	 *
	 * @return void
	 */
	public function get_session_cookie() {
		$cc_value = isset( $_COOKIE[ $this->_customer_id_cookie ] ) ? $this->clean_cookie( $_COOKIE[ $this->_customer_id_cookie ] ) : false; // phpcs:ignore
		$cc_value = $this->decode_cookie_value( $cc_value );
		if ( $cc_value ) {
			$this->_customer_id = $cc_value;
		}
		
		
		$sc_value  = isset( $_COOKIE[ $this->_session_id_cookie ] ) ? $this->clean_cookie( $_COOKIE[ $this->_session_id_cookie ] ) : false; // phpcs:ignore
		$sc_value = $this->decode_cookie_value( $sc_value );
		if ( $sc_value ) {
			$this->_session_id = $sc_value;
		}
	}
	
	/**
	 * Encode cookie value with as string with hmac validation hash.
	 *
	 * @param string $value Value to encode.
	 *
	 * @return string
	 */
	private function encode_cookie_value( $value ) {
		$secret = sxp_get_uuid_v4();
		$hash = $value . '|' . $secret;
		$hash = hash_hmac( 'md5', $hash, wp_hash( $hash ) );
		return $value . '||' . $secret . '||' . $hash;
	}
	
	/**
	 * Validate Cookie with hMac Hash and return the value..
	 *
	 * @param string $cookie cookie value to validate.
	 *
	 * @return bool|mixed
	 */
	private function decode_cookie_value( $cookie ) {
		if ( ! empty( $cookie ) || is_string( $cookie ) ) {
			list( $value, $secret, $cookie_hash ) = explode( '||', $cookie );
			// Validate hash.
			$cc_hash = $value .  '|' . $secret;
			$cc_hash = hash_hmac( 'md5', $cc_hash, wp_hash( $cc_hash ) );
			if ( ! empty( $cookie_hash ) && hash_equals( $cc_hash, $cookie_hash ) ) {
				return $value;
			}
		}
		return false;
	}
	
	/**
	 * Clean Cookie.
	 *
	 * @param string $input Cookie.
	 *
	 * @return string
	 */
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
		
		switch ( $expiration['unit'] ) {
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
		
		// if custom is positive.
		if ( $multiply ) {
			return ( $expiration['number'] * $multiply );
		}
		
		// Return default.
		return ( 2 * YEAR_IN_SECONDS );
	}
	
	/**
	 * Setup the actions.
	 *
	 * @return void
	 */
	private function setup_tracking_script() {
		add_action( 'salexpresso_api_' . sanitize_key( $this->script_name ), [ $this, 'get_tracking_script' ] );
		add_action( 'salexpresso_api_' . $this->track_action , [ $this, 'tracking_collect' ] );
		// Other plugin should not remove this.
		// remove_filter fn needs correct priority value, only option is to use remove_all_filters.
		add_action( 'wp_head', [ $this, 'print_tracking_script' ], ( mt_rand( 1, 0xffff ) * -1 ) );
		// Using wp_body_open is risky. it's not a required action hook.
		add_action( 'wp_footer', [ $this, 'print_tracking_noscript' ], mt_rand( 1, 0xffff ) );
		
		add_action( 'woocommerce_thankyou', function ( $order ) {
			$order = wc_get_order( $order );
			if ( ! $order || 'shop_order_refund' === $order->get_type() ) {
				return;
			}
			
			?>
			<script>
				if ( 'function' === typeof sxpEvent ) {
					sxpEvent( 'order-received', [ {
						label: 'order_id',
						value: '<?php echo (int) $order->get_id(); ?>',
					}, {
						label: 'order_status',
						value: '<?php echo esc_attr( $order->get_status() ); ?>'
					} ] );
				}
			</script>
			<?php
		}, 10, 1 );
	}
	
	/**
	 * Prints the tracking script.
	 * @return void
	 */
	public function print_tracking_script() {
		$tracker_file = sxp_api_link( $this->script_name );
//		wp_register_script( 'sxp-analytics', $tracker_file, [], false, false );
		$queried_object = $this->get_queried_object_data();
		$data = [
			'wp_object_type' => $queried_object['types'],
			'wp_object_id'   => $queried_object['id'],
		];
		?>
		<script>
			var sxpSessionOnPageData = <?php echo wp_json_encode( $data ); ?>;
		</script>
		<script src="<?php echo esc_url( $tracker_file ); ?>?ver=<?php echo self::VERSION ?>"></script>
		<?php
		
//		wp_enqueue_script( 'sxp-analytics' );
	}
	
	/**
	 * Prints the tracking script.
	 * @return void
	 */
	public function print_tracking_noscript() {
		$queried_object = $this->get_queried_object_data();
		$no_script      = sxp_api_link( $this->track_action );
		$queries        = array();
		parse_str( $_SERVER['QUERY_STRING'], $queries );
		$queries = sxp_deep_clean( $queries );
		$queries = array_filter( $queries );
		$queries = array_unique( $queries );
		$queries = array_merge(
			$queries,
			[
				'mode'     => 'standalone',
				'type'     => 'page-view',
				'referrer' => isset( $_SERVER['HTTP_REFERER'] ) ? sanitize_text_field( $_SERVER['HTTP_REFERER'] ) : '',
				'path'     => isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( $_SERVER['REQUEST_URI'] ) : '',
				'meta'     => [
					'object_type' => $queried_object['types'],
					'object_id'   => $queried_object['id'],
				],
			]
		);
		$no_script = add_query_arg( $queries, $no_script );
		?>
		<noscript>
			<img src="<?php echo esc_url( $no_script ); ?>" data-url_raw="<?php echo $no_script; ?>" alt="">
		</noscript>
		<?php
	}
	
	/**
	 * Get WP Object Type
	 * @return array
	 */
	private function get_queried_object_data() {
		$queried = get_queried_object();
		$type    = '';
		$subtype = '';
		$data    = [ 'id' => (int) get_queried_object_id() ];
		
		if ( $queried instanceof WP_Taxonomy ) {
			$type = 'wp-taxonomy';
			$subtype = $queried->name;
		} else if ( $queried instanceof WP_Term ) {
			$type = 'wp-term';
			$subtype = $queried->taxonomy;
		} else if ( $queried instanceof WP_Post_Type ) {
			$type = 'wp-archive';
			$subtype = $queried->name;
		} else if ( $queried instanceof WP_Post ) {
			$type = 'wp-post';
			$subtype = $queried->post_type;
		} else if ( $queried instanceof WP_User ) {
			$type = 'wp-user';
		}
		
		$data['types'] = [ 'type' => $type, 'subtype' => $subtype ];
		
		return apply_filters( 'salexpresso_tracker_queried_object_data', $data );
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
			'{{VERSION}}'           => self::VERSION,
			'{{SITE_URL}}'          => site_url(),
			'{{api_url}}'           => sxp_api_link( $this->track_action ),
			'{{tracker}}'           => $this->script_name,
			"'{{tracker_options}}'" => (object) [
				'mode'        => '',
				'ignorePages' => sxp_csv_string_to_array( get_option( 'salexpresso_st_exclude_paths' ), 'trim' ),
				'autoCollect' => true,
				'recordDnt'   => true,
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
			
			if ( $this->_user_id ) {
				sxp_update_user_acquired_via( $this->_user_id, $this->_is_unique, $data );
			}
			
			// insert the data into db.
			$now = current_time( 'mysql' );
			$data['created'] = get_date_from_gmt( $now );
			$data['created_gmt'] = $now;
			
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
		
		$standalone = false;
		
		if ( isset( $_REQUEST['mode'] ) && 'standalone' === $_REQUEST['mode'] ) {
			$standalone = true;
			foreach ( [ 'source', 'medium', 'campaign', 'term', 'content' ] as $k ) {
				if ( isset( $_REQUEST[ 'utm_' . $k ] ) ) {
					$defaults[ $k ] = sanitize_text_field( $_REQUEST[ 'utm_' . $k ] );
					unset( $_REQUEST[ 'utm_' . $k ] );
				}
			}
			unset( $_REQUEST['mode'] );
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
		
		if ( $standalone ) {
			$data['session_meta']['standalone'] = 1;
		}
		
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
					$allowed = [ 'wp-taxonomy', 'wp-term', 'wp-archive', 'wp-post', 'wp-user' ];
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
			
			$data['session_meta'] = $meta;
		}
		
		unset( $data['meta'] );
		
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
}
// End of file class-sxp-tracker.php.
