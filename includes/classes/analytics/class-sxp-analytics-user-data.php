<?php
/**
 * Session Handler
 *
 * @package SaleXpresso
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso\Analytics;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Session.
 *
 */
class SXP_Analytics_User_Data {
	
	/**
	 * WP User ID.
	 *
	 * @var int
	 */
	private $user;
	
	/**
	 * User's Cookie ID Meta.
	 *
	 * @var string
	 */
	protected $cookie_meta = '_sxp_cookie_id';
	
	/**
	 * User Cookie ID.
	 *
	 * @var string
	 */
	protected $cookie_id;
	
	/**
	 * SXP_Analytics_Data constructor.
	 *
	 * @param int $user_id User Id
	 */
	public function __construct( $user_id = null ) {
		$this->user = absint( $user_id );
		$this->cookie_id = get_user_meta( $this->user, $this->cookie_meta, true );
	}
	
	/**
	 * Get User's cookie id.
	 *
	 * @return bool|string
	 */
	public function get_cookie_id() {
		if ( empty( $this->cookie_id ) ) {
			return false;
		}
		return $this->cookie_id;
	}
	
	/**
	 * Get User's Sessions.
	 *
	 * @param string $order_by Order by column.
	 * @param string $order    Sort Order.
	 * @param int    $limit    Limit.
	 * @param int    $offset   Offset.
	 *
	 * @return object[]|array
	 */
	public function get_sessions_for_list_table( $order_by = 'ID', $order = 'ASC', $limit = 20, $offset = 0 ) {
		global $wpdb;
		
		$order = sxp_sql_order_by( $order_by, $order );
		$limit = sxp_sql_limit_offset( $limit, $offset );
		$type = sxp_sql_where_in( 'type', [ 'page-view', 'event' ], 'AND' );
		
		$total = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT count( DISTINCT session_id ) FROM {$wpdb->sxp_analytics} WHERE 1=1 {$type} AND visitor_id = %s",
				$this->get_cookie_id()
			)
		);
		
		$session_ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT session_id FROM {$wpdb->sxp_analytics} WHERE 1=1 {$type} AND visitor_id = %s {$order} {$limit}",
				$this->get_cookie_id()
			)
		);
		
		return [
			'result' => array_map( [ $this, 'get_session' ], $session_ids ),
			'total' => $total,
		];
	}
	
	public function get_session( $session_id, $type = 'page-leave', $include = false ) {
		global $wpdb;
		$type = sxp_sql_where_in( 'type', $type, 'AND', $include );
		
		return $wpdb->get_row(
			$wpdb->prepare(
				"SELECT *, count(*) as total FROM {$wpdb->sxp_analytics} WHERE 1=1 {$type} AND session_id = %s ORDER BY ID ASC LIMIT 1",
				$session_id
			)
		);
	}
	
	public function get_sessions( $session_id, $type = 'all', $order_by = 'ID', $order = 'ASC', $limit = 20, $offset = 0 ) {
		global $wpdb;
		
		$type  = sxp_sql_where_in( 'type', $type, 'AND' );
		$order = sxp_sql_order_by( $order_by, $order );
		$limit = sxp_sql_limit_offset( $limit, $offset );
		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->sxp_analytics} WHERE 1=1 {$type} AND session_id = %s {$order} {$limit}",
				$session_id
			)
		);
	}
	
	public function get_on_page_duration( $session_id, $page_id ) {
		global $wpdb;
		$duration = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT duration FROM {$wpdb->sxp_analytics} WHERE session_id = %s AND page_id = %s AND type = 'page-leave'",
				$session_id,
				$page_id
			)
		);
		return $duration ? absint( $duration ) : false;
	}
	
	/**
	 * Get Page Leave time.
	 *
	 * @param string $session_id
	 * @param string $page_id
	 * @param string $type
	 * @param bool   $gmt
	 *
	 * @return false|int|string|null
	 */
	public function get_page_leave_time( $session_id, $page_id, $type = 'timestamp', $gmt = false ) {
		global $wpdb;
		$field = $gmt ? 'created_gmt' : 'created';
		$time = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT {$field} FROM {$wpdb->sxp_analytics} WHERE session_id = %s AND page_id = %s AND type = 'page-leave'",
				$session_id,
				$page_id
			)
		);
		if ( ! $time ) {
			return null;
		} else {
			return 'timestamp' === $type ? strtotime( $time ) : $time;
		}
	}
	
	/**
	 * Get User's Session Count.
	 *
	 * @return int
	 */
	public function get_user_session_count() {
		global $wpdb;
		
		$result = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT( DISTINCT( session_id ) ) as total FROM $wpdb->sxp_analytics WHERE visitor_id = %s",
				$this->get_cookie_id()
			)
		);
		
		return absint( $result );
	}
	
	/**
	 * Get User's Event Count.
	 *
	 * @param string|string[] $type Type to count.
	 * @param bool $include Include or exclude tye Types.
	 *
	 * @return int
	 */
	public function get_user_action_count( $type = 'all', $include = true ) {
		global $wpdb;
		
		$type = sxp_sql_where_in( 'type', $type, 'AND', $include );
		
		$result = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT( * ) as total FROM {$wpdb->sxp_analytics} WHERE 1=1 {$type} AND visitor_id = %s",
				$this->get_cookie_id()
			)
		);
		
		return absint( $result );
	}
	
	/**
	 * Get User's Last Activity (Session Created) Date.
	 *
	 * @param string $type Optional.
	 * @param bool $gmt Optional.
	 *
	 * @return int|string|null
	 */
	public function get_user_last_active( $type = 'timestamp', $gmt = false ) {
		global $wpdb;
		$field = $gmt ? 'created_gmt' : 'created';
		$result = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT {$field} FROM {$wpdb->sxp_analytics} WHERE visitor_id = %s ORDER BY created DESC LIMIT 1;",
				$this->get_cookie_id()
			)
		);
		
		if ( ! $result ) {
			return null;
		} else {
			return 'timestamp' === $type ? strtotime($result ) : $result;
		}
	}
}
// End of file class-sxp-analytics-user-data.php.
