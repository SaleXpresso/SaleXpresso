<?php
/**
 * SaleXpresso Abandon Cart List Table.
 *
 * @package SaleXpresso\List_Table
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\List_Table;

use SaleXpresso\AbandonCart\SXP_Abandon_Cart;
use SaleXpresso\SXP_List_Table;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Abandon_Cart_List_Table
 *
 * @package SaleXpresso
 */
class SXP_Abandon_Cart_List_Table extends SXP_List_Table {
	
	/**
	 * Cart Statuses list.
	 * @var array
	 */
	private $statuses = [];
	/**
	 * SXP_Abandon_Cart_List_Table constructor.
	 *
	 * @param array|string $args Optional. List Table Options.
	 */
	public function __construct( $args = [] ) {
		parent::__construct( wp_parse_args( $args, [
			'singular' => __( 'Abandon Cart', 'salexpresso' ),
			'plural'   => __( 'Abandon Carts', 'salexpresso' ),
		] ) );
	}
	
	public function prepare_items() {
		global $wpdb;
		
		$order_by   = 'ID';
		$sort_order = 'ASC';
		$per_page   = $this->get_items_per_page( 'abandon_cart_per_page' );
		$offset     = absint( ( 1 - $this->get_pagenum() ) * $per_page );
		
		if ( isset( $_GET['orderby'] ) && ! empty( $_GET['orderby'] ) ) {
			$order_by = sanitize_text_field( $_GET['orderby'] );
		}
		if ( isset( $_GET['order'] ) && ! empty( $_GET['order'] ) ) {
			$sort_order = sanitize_text_field( $_GET['order'] );
		}
		
		$limit = sxp_sql_limit_offset( $per_page, $offset );
		$order = sxp_sql_order_by( $order_by, $sort_order );
		
		$total = $wpdb->get_var( "SELECT count( * ) FROM {$wpdb->sxp_abandon_cart}" );
		$this->items = $wpdb->get_results(
			"
			SELECT * FROM {$wpdb->sxp_abandon_cart}
			{$order}
			{$limit}
			"
		);
		
		$this->statuses = SXP_Abandon_Cart::get_instance()->get_cart_statuses();
		
		$this->set_pagination_args( [
			'total_items' => $total,
			'per_page'    => $per_page,
		] );
	}
	
	/**
	 * Default Column Callback.
	 *
	 * @param object $item Cart data.
	 * @param string $column_name Column Slug.
	 *
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		$item->cart_meta = maybe_unserialize( $item->cart_meta );
		switch ( $column_name ) {
			case 'customer':
				
				$url = '#';
				$state   = isset( $item->cart_meta['state'] ) ? $item->cart_meta['state'] : '';
				$country = isset( $item->cart_meta['country'] ) ? $item->cart_meta['country'] : '';
				$name = '';
				$address = '';
				
				if  ( isset( $item->cart_meta['first_name'], $item->cart_meta['last_name'] ) ) {
					$name = trim( $item->cart_meta['first_name'] . ' ' . $item->cart_meta['last_name'] );
				}
				if ( $item->email ) {
					$user = get_user_by( 'email', $item->email );
				} else {
					$uid = sxp_get_user_by_meta( '_sxp_cookie_id', $item->visitor_id, true );
					$user = get_user_by( 'id', $uid );
				}
				if ( $user ) {
					$url = add_query_arg(
						[
							'page'     => 'sxp-customer',
							'customer' => $user->ID,
							'tab'      => 'active-cart',
						],
						admin_url( 'admin.php' )
					);
				} else {
//					if ( $item->email ) {
//						$url = 'mailto:' . $item->email;
//					}
				}
				if ( ! empty( $state ) && ! empty( $country ) ) {
					$address = WC()->countries->get_formatted_address(
						[
							'state'   => $state,
							'country' => $country,
						],
						' '
					);
				}
				$extra = '';
				$url_attr = '';
				if ( '#' === $url ) {
					ob_start();
					$this->display_active_cart( $item->id );
					$extra = ob_get_clean();
					$url = '#sxp-abandon-cart-' . $item->id;
					$url_attr = ' rel="modal:open"';
				}
				
				return sprintf(
					'<div class="sxp-customer-desc">
							<div class="sxp-customer-desc-thumbnail">%s</div><!-- end .sxp-customer-desc-thumbnail -->
							<div class="sxp-customer-desc-details">
								<a href="%s"%s>%s</a>
								<p class="sxp-customer-desc-details-location">%s</p>
							</div><!-- end .sxp-customer-desc-detaisl -->
						</div><!-- end .sxp-customer-desc -->%s',
					sprintf( '<a href="%s"%s>%s</a>', $url, $url_attr, get_avatar( $item->email, 40, '', $name ) ),
					esc_url( $url ),
					$url_attr,
					esc_html( $name ? $name : $item->email ),
					$address,
					$extra
				);
				break;
			case 'email':
				return $item->email;
				break;
			case 'count':
				$item->cart_contents = maybe_unserialize( $item->cart_contents );
				return count( $item->cart_contents );
				break;
			case 'amount':
				return wc_price( $item->cart_total, [] );
				break;
			case 'status' :
				return isset( $this->statuses[ $item->status ] ) ? $this->statuses[ $item->status ] : ucfirst( $item->status );
				break;
			case 'created' :
				return gmdate( 'M d, Y', strtotime( $item->created ) );
				break;
			default:
				return $this->column_default_filtered( $item, $column_name );
		}
	}
	
	private function display_active_cart( $id ) {
		$active_cart = SXP_Abandon_Cart::get_instance()->get_abandon_cart_by_id( $id );
		if ( ! $active_cart ) {
			return;
		}
		
		$active_cart['cart_contents'] = maybe_unserialize( $active_cart['cart_contents'] );
		$active_cart['cart_meta'] = maybe_unserialize( $active_cart['cart_meta'] );
		
		?>
		<div class="sxp-profile-wrapper modal" style="display: none; max-width: 810px;" id="sxp-abandon-cart-<?php echo esc_html( $id ); ?>">
			<table class="wp-list-table widefat sxp-table sxp-customer-profile-table sxp-active-cart-table">
				<thead>
				<tr>
					<th scope="col" class="manage-column column-sl"><?php esc_html_e( 'Sl.', 'salexpresso' ); ?></th>
					<th scope="col" class="manage-column column-title column-primary"><?php esc_html_e( 'All Products', 'salexpresso' ); ?></th>
					<th scope="col" class="manage-column column-unit-price"><?php esc_html_e( 'Unit Price', 'salexpresso' ); ?></th>
					<th scope="col" class="manage-column column-qty"><?php esc_html_e( 'QTY', 'salexpresso' ); ?></th>
					<th scope="col" class="manage-column column-price"><?php esc_html_e( 'Amount', 'salexpresso' ); ?></th>
				</tr>
				</thead>
				<tbody id="the-list-<?php echo esc_html( $id ); ?>">
				<?php if ( ! empty( $active_cart ) && isset( $active_cart['cart_contents'] ) ) { ?>
					<?php
					$i = 1;
					foreach ( $active_cart['cart_contents'] as $cart_item ) {
						$pid = $cart_item['product_id'];
						$vid = $cart_item['variation_id'];
						$_product = false;
						if ( $vid ) {
							$_product = wc_get_product( $vid );
						} else if ( $pid ) {
							$_product = wc_get_product( $pid );
							//$_product->get_image()
						}
						if ( ! $_product ) {
							continue;
						}
						?>
						<tr>
							<td data-colname="<?php esc_attr_e( 'Sl.', 'salexpresso' ); ?>"><?php echo $i; ?>.</td>
							<td class="title column-title column-primary item-title" data-colname="<?php esc_attr_e( 'Product Name', 'salexpresso' ); ?>">
								<div class="product-wrap">
									<div class="product-thumb">
										<?php echo $_product->get_image(); // PHPCS: XSS ok. ?>
									</div>
									<div class="product-details">
										<div class="product-name"><?php echo $_product->get_name(); // PHPCS: XSS ok. ?></div>
										<div class="product-sku"><?php echo $_product->get_sku(); // PHPCS: XSS ok. ?></div>
										<div class="product-extr">
											<?php
											
											// Backorder notification.
											if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
												echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>';
											}
											?>
										</div>
										<!-- /.product-extr -->
									</div>
								</div><!-- end .product-wrap -->
								<button type="button" class="toggle-row"><span class="screen-reader-text"><?php esc_html_e( 'Show more details', 'salexpresso' ); ?></span></button>
							</td>
							<td data-colname="<?php esc_attr_e( 'Unit Price', 'salexpresso' ); ?>"><?php echo wc_price( $cart_item['line_subtotal'] ); // PHPCS: XSS ok. ?></td>
							<td data-colname="<?php esc_attr_e( 'QTY', 'salexpresso' ); ?>"><?php printf(
									esc_html_x( 'x %s', 'Active Cart Qty', 'salexpresso' ),
									$cart_item['quantity']
								); ?></td>
							<td data-colname="<?php esc_attr_e( 'Amount', 'salexpresso' ); ?>"><?php echo wc_price( $cart_item['line_total'] ); // PHPCS: XSS ok. ?></td>
						</tr>
						<?php $i++;
					} ?>
				<?php } else { ?>
					<tr>
						<td colspan="5">
							<p><?php esc_html_e( 'No Active Cart.', 'salexpresso' );  ?></p>
						</td>
					</tr>
				<?php } ?>
				</tbody>
				<tfoot>
				<?php if( isset( $active_cart['cart_meta']['subtotal'] ) ) { ?>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td><?php esc_html_e( 'Sub Total', 'salexpresso' ); ?></td>
						<td><?php echo wc_price( $active_cart['cart_meta']['subtotal'] ); // PHPCS: XSS ok. ?></td>
					</tr>
				<?php } ?>
				<?php if( isset( $active_cart['cart_meta']['discount'] ) ) { ?>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td><?php esc_html_e( 'Discount Received', 'salexpresso' ); ?></td>
						<td>-<?php echo wc_price( $active_cart['cart_meta']['discount'] ); // PHPCS: XSS ok. ?></td>
					</tr>
				<?php } ?>
				<?php if( isset( $active_cart['cart_meta']['shipping'] ) ) { ?>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td><?php esc_html_e( 'Shipping', 'salexpresso' ); ?></td>
						<td>+<?php echo wc_price( $active_cart['cart_meta']['shipping'] ); // PHPCS: XSS ok. ?></td>
					</tr>
				<?php } ?>
				<?php if( isset( $active_cart['cart_meta']['tax'] ) ) { ?>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td><?php esc_html_e( 'Tax Included', 'salexpresso' ); ?></td>
						<td><?php echo wc_price( $active_cart['cart_meta']['tax'] ); // PHPCS: XSS ok. ?></td>
					</tr>
				<?php } ?>
				<tr>
					<?php /*<td><i data-feather="file-text"></i> Place Order</td>
					<td><i data-feather="mail"></i> Send Mail</td>*/ ?>
					<td></td>
					<td></td>
					<td></td>
					<td><?php esc_html_e( 'Grand Total', 'salexpresso' ); ?></td>
					<td><?php echo $active_cart ? wc_price( $active_cart['cart_total'] ) : wc_price( 0 ); ?></td>
				</tr>
				</tfoot>
			</table>
		</div>
		<?php
	}
	
	/**
	 * CB Column Renderer.
	 *
	 * @param Object $item Data.
	 *
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<label class="screen-reader-text" for="abandon_cart_%1$s">%2$s</label>' .
			'<input type="checkbox" name="abandon_carts[]" id="abandon_cart_%1$s" class="%3$s" value="%1$s" />',
			$item->id,
			/* translators: %s: Abandon Cart ID. */
			sprintf( __( 'Select %s', 'salexpresso' ), $item->id ),
			'select-abandon-cart'
		);
	}
	
	/**
	 * Get Default Columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return [
			'cb'       => '<input type="checkbox" />',
			'customer' => __( 'Customer', 'salexpresso' ),
			'email'    => __( 'Email', 'salexpresso' ),
			'count'    => __( 'No. of Product', 'salexpresso' ),
			'amount'   => __( 'Amount', 'salexpresso' ),
			'status'   => __( 'Status', 'salexpresso' ),
			'created'  => __( 'Date', 'salexpresso' ),
		];
	}
	
	public function get_sortable_columns() {
		return [
			'email'   => [ 'email' ],
			'count'   => [ 'cart_count' ],
			'amount'  => [ 'cart_total' ],
			'status'  => [ 'status' ],
			'created' => [ 'created' ],
		];
	}
}
// End of file class-sxp-abandon-cart-list-table.php.
