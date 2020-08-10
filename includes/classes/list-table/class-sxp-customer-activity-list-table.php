<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Customer
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\List_Table;

use Exception;
use SaleXpresso\Analytics\SXP_Analytics_User_Data;
use SaleXpresso\SXP_List_Table;
use Automattic\WooCommerce\Admin\API\Reports\TimeInterval;
use Automattic\WooCommerce\Admin\API\Reports\Customers\DataStore as CustomerReportDataStore;
use WC_DateTime;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Customer_List_Table
 *
 * @package SaleXpresso\Customer
 */
class SXP_Customer_Activity_List_Table extends SXP_List_Table {
	
	/**
	 * User Data.
	 * @var SXP_Analytics_User_Data
	 */
	protected $analytics;
	
	/**
	 * UserID.
	 * @var int
	 */
	protected $user_id;
	
	/**
	 * Host Name.
	 *
	 * @var string
	 */
	protected $host;
	
	/**
	 * SXP_Customer_List_Table constructor.
	 *
	 * @param array|string $args List table params.
	 */
	public function __construct( $args = array() ) {
		
		parent::__construct(
			[
				'singular'  => __( 'activity', 'salexpresso' ),
				'plural'    => __( 'activities', 'salexpresso' ),
				'ajax'      => false,
				'screen'    => isset( $args['screen'] ) ? $args['screen'] : null,
				'tab'       => '',
				'tfoot'     => false,
				'table_top' => false,
				'table_pagination' => true,
				'table_bottom' => true,
			]
		);
		
		$this->items = [];
		$this->host = sxp_get_host_name( site_url() );
	}
	
	/**
	 * Get column info.
	 *
	 * @return array
	 */
	protected function get_column_info() {
		// $_column_headers is already set / cached.
		if ( isset( $this->_column_headers ) && is_array( $this->_column_headers ) ) {
			// Back-compat for list tables that have been manually setting $_column_headers for horse reasons.
			// In 4.3, we added a fourth argument for primary column.
			$column_headers = array( array(), array(), array(), $this->get_primary_column_name() );
			foreach ( $this->_column_headers as $key => $value ) {
				$column_headers[ $key ] = $value;
			}
			
			return $column_headers;
		}
		
		$columns = $this->get_columns();
		$hidden  = [];
		
		$_sortable = $this->get_sortable_columns();
		
		$sortable = [];
		foreach ( $_sortable as $id => $data ) {
			if ( empty( $data ) ) {
				continue;
			}
			
			$data = (array) $data;
			if ( ! isset( $data[1] ) ) {
				$data[1] = false;
			}
			
			$sortable[ $id ] = $data;
		}
		
		$this->_column_headers = [
			$columns,
			$hidden,
			$sortable,
			$this->get_primary_column_name(),
		];
		
		return $this->_column_headers;
	}
	
	/**
	 * Set Data.
	 *
	 * @param int $user_id User ID.
	 * @param SXP_Analytics_User_Data $analytics Analytics Class.
	 */
	public function set_data( $user_id, SXP_Analytics_User_Data $analytics ) {
		$this->analytics = $analytics;
		$this->user_id = $user_id;
	}
	
	/**
	 * Prepares the list of items for displaying.
	 *
	 * @param SXP_Analytics_User_Data
	 *
	 * @return void
	 */
	public function prepare_items() {
		$order_by    = 'ID';
		$sort_order  = 'ASC';
		$per_page    = 20;
		$offset      = ( 1 - $this->get_pagenum() ) * $per_page;
		$sessions    = $this->analytics->get_sessions_for_list_table( $order_by, $sort_order, $per_page, $offset );
		$this->items = $sessions['result'];
		$this->set_pagination_args( [
			'total_items' => $sessions['total'],
			'per_page'    => $per_page,
		] );
	}
	
	/**
	 * Default Column Callback.
	 *
	 * @param array  $item Term.
	 * @param string $column_name Column Slug.
	 *
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'session':
				return sprintf( esc_html__( 'Session ID: %s', 'salexpresso' ), $item->session_id );
				break;
			case 'referrer':
				return sxp_get_acquired_via( (array) $item );
				break;
			case 'action_count' :
				return isset( $item->total ) ? $item->total : 0;
				break;
			case 'created' :
				return gmdate( 'm D, Y', strtotime( $item->created ) );
				break;
			default:
				return $this->column_default_filtered( $item, $column_name );
		}
	}
	
	/**
	 * Generates content for a single row of the table.
	 *
	 * @param object|array $item The current item.
	 * @return void
	 */
	public function single_row( $item ) {
		echo '<tr class="has-fold">';
		$this->single_row_columns( $item );
		echo '</tr>';
		echo '<tr class="fold">';
		$this->session_details( $item );
		echo '</tr>';
	}
	
	/**
	 * Show session details.
	 *
	 * @param array|object $item single item.
	 */
	private function session_details( $item ) {
		$columns = count( $this->get_column_info()[0] );
		echo '<td colspan="' . $columns . '">';
		$sessions = $this->analytics->get_sessions( $item->session_id, [ 'page-view', 'event' ], 'id', 'ASC', -1 );
		$i = 0;
		$prev_entry_created = 0;
		foreach ( $sessions as $session ) {
			$i++;
			$meta = json_decode( $session->session_meta );
		echo '<div class="sxp-fold-content session-item-id-'. $session->id .'">';
			echo '<div class="sxp-table-viewed" aria-details="'. esc_attr__( 'Session Event Type', 'salexpresso' ) .'">';
				switch ( $session->type ) {
					case 'event':
						if ( 'add-to-cart' === $session->event ) {
							echo '<i data-feather="plus-circle" aria-hidden="true"></i>';
							echo '<span class="serial">' . $i . '.</span>';
							$pos = sxp_array_multi_search( 'product_id', 'label', $meta->event );
							$prod_link = sprintf(
								'<a class="product" href="%s" target="_blank">%s</a>',
								esc_url( get_edit_post_link( $meta->event[ $pos ]->value ) ),
								esc_html( get_the_title( $meta->event[ $pos ]->value ) )
							);
							printf(
								esc_html__( 'Added %s to cart', 'salexpresso' ),
								$prod_link
							);
							
						} else if ( 'remove-from-cart' === $session->event ) {
							echo '<i data-feather="x-octagon" aria-hidden="true"></i>';
							echo '<span class="serial">' . $i . '.</span>';
							$pos = sxp_array_multi_search( 'product_id', 'label', $meta->event );
							$prod_link = sprintf(
								'<a class="product" href="%s" target="_blank">%s</a>',
								esc_url( get_edit_post_link( $meta->event[ $pos ]->value ) ),
								esc_html( get_the_title( $meta->event[ $pos ]->value ) )
							);
							printf(
								esc_html__( 'Removed %s from cart', 'salexpresso' ),
								$prod_link
							);
						} else if ( 'undo-remove-from-cart' === $session->event ) {
							echo '<i data-feather="plus-circle" aria-hidden="true"></i>';
							echo '<span class="serial">' . $i . '.</span>' . esc_html__( 'Undo Last Removal', 'salexpresso' );
						} else if ( 'checkout-completed' === $session->event ) {
							echo '<i data-feather="shopping-cart" aria-hidden="true"></i>';
							echo '<span class="serial">' . $i . '.</span>';
							$gateway = false;
							$pos = sxp_array_multi_search( 'gateway_id', 'label', $meta->event );
							if ( isset( $meta->event[ $pos ], $meta->event[ $pos ]->value ) ) {
								$gateway = sxp_get_wc_payment_gateways( 'cod' );
								if ( $gateway ) {
									printf( esc_html__( 'Completed checkout with %s', 'salexpresso' ), esc_html( $gateway->get_title() ) );
								}
							}
							if ( ! $gateway ) {
								esc_html_e( 'Completed checkout', 'salexpresso' );
							}
						} else if ( 'order-received' === $session->event ) {
							echo '<i data-feather="shopping-bag" aria-hidden="true"></i>';
							echo '<span class="serial">' . $i . '.</span>' . esc_html__( 'Order Received', 'salexpresso' );
						} else {
							$output = '<i data-feather="alert-octagon" aria-hidden="true"></i> ';
							$event = $session->event;
							$event = str_replace( [ '-', '_' ], ' ', $event );
							$event = ucwords( $event );
							$output .= sprintf( esc_html__( 'Event: %s', 'salexpresso' ), esc_html( $event ) );
							echo apply_filters( 'salexpresso_activity_list_table_unknown_event_type', $output );
						}
						break;
					case 'page-view':
					default:
					echo '<i data-feather="eye" aria-hidden="true"></i>';
					echo '<span class="serial">' . $i . '.</span>' . esc_html__( 'Viewed', 'salexpresso' );
					if ( isset( $meta->wp_object ) ) {
						$wp_obj = $meta->wp_object;
						if ( 'wp-post' === $wp_obj->type ) {
							$title = get_the_title( $wp_obj->id );
							if ( 'product' === $wp_obj->subtype ) {
								$pat = __( '<a href="%s" class="product" target="_blank">%s</a> Product', 'salexpresso' );
							} else if ( 'post' === $wp_obj->subtype ) {
								$pat = __( '<a href="%s" class="product" target="_blank">%s</a> Post', 'salexpresso' );
							} else {
								$pat = __( '<a href="%s" class="product" target="_blank">%s</a> Page', 'salexpresso' );
							}
							
							printf( $pat, esc_url( get_edit_post_link( $wp_obj->id ) ), esc_html( $title ) );
						} else if ( 'wp-term' === $wp_obj->type ) {
							$term = get_term_by( 'id', $wp_obj->id, $wp_obj->subtype );
							$tax  = get_taxonomy( $wp_obj->subtype );
							if ( $term ) {
								printf(
									__( '<a href="%s" class="product" target="_blank">%s</a> %s', 'salexpresso' ),
									esc_url( get_edit_term_link( $term->term_id, $wp_obj->subtype ) ),
									$term->name,
									$tax->label
								);
							}
						} else if ( 'wp-taxonomy' === $wp_obj->type ) {
							$tax  = get_taxonomy( $wp_obj->subtype );
							if ( $tax ) {
								printf(
									__( '%s Archive', 'salexpresso' ),
									$tax->label
								);
							}
						} else if ( 'wp-archive' === $wp_obj->type ) {
							$post_type = get_post_type_object( $wp_obj->subtype );
							if ( $post_type ) {
								printf(
									__( '<a href="%s" class="product" target="_blank">%s</a> Archive', 'salexpresso' ),
									esc_url( get_post_type_archive_link( $wp_obj->subtype ) ),
									$post_type->label
								);
							}
						} else if ( 'wp-user' === $wp_obj->type ) {
							$user = get_user_by( 'id', $wp_obj->id );
							if ( $user ) {
								printf(
									__( 'Author archive of <a href="%s" class="product" target="_blank">%s</a>', 'salexpresso' ),
									esc_url( get_edit_user_link( $wp_obj->id ) ),
									$user->display_name
								);
							}
						}
					} else {
						
						printf(
							'<a href="%s" class="product" target="_blank">%s</a> Path',
							esc_url( site_url( $session->path ) ),
							$session->path
						);
					}
						break;
				}
			echo '</div>';
			echo '<div class="duration">';
			// $entry_created = $this->analytics->get_page_leave_time( $session->session_id, $session->page_id );
			$entry_created = $session->created ? strtotime( $session->created ) : false;
			if ( $prev_entry_created && $entry_created ) {
				/* translators: %s: Human-readable time difference. */
				printf( __( '<time title="%s">%s later</time>', 'salexpresso' ), $session->created, human_time_diff( $prev_entry_created, $entry_created ) );
			} else {
				printf( __( '<time title="%s">––</time>', 'salexpresso' ), $session->created );
			}
			$prev_entry_created = $entry_created;
			echo '</div>';
		echo '</div><!-- end .sxp-fold-content -->';
		}
		echo '</td>';
	}
	
	/**
	 * Get Default Columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return [
			'session'     => __( 'All Sessions', 'salexpresso' ),
			'referrer'    => __( 'Source', 'salexpresso' ),
			'action_count' => __( 'Action', 'salexpresso' ),
			'created'     => __( 'Timestamp', 'salexpresso' ),
		];
	}
	
	/**
	 * Get Sortable Columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return [
			'session'     => [ 'session' ],
			'referrer'    => [ 'referrer' ],
			'event_count' => [ 'event_count' ],
			'created'     => [ 'created' ],
		];
	}
}

// End of file class-sxp-customer-activity-list-table.php.
