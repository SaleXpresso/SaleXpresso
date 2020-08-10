<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Customer
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\Customer;

use Exception;
use SaleXpresso\Analytics\SXP_Analytics_User_Data;
use WC_Customer;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Customer_Profile_Table
 *
 * @package SaleXpresso\Customer
 */
class SXP_Customer_Profile_Table {
	
	/**
	 * The Customer.
	 *
	 * @var WC_Customer
	 */
	protected $customer;
	
	/**
	 * The Customer ID.
	 *
	 * @var int
	 */
	protected $user_id;
	
	/**
	 * Analytics.
	 *
	 * @var SXP_Analytics_User_Data
	 */
	protected $analytics;
	
	/**
	 * SXP_Customer_List_Table constructor.
	 *
	 * @param WC_Customer $customer
	 */
	public function __construct( WC_Customer $customer ) {
		
		$this->customer    = $customer;
		$this->user_id = $customer->get_id();
		$this->analytics = new SXP_Analytics_User_Data( $this->user_id );
		
		$this->display();
	}
	
	/**
	 * Render Profile.
	 *
	 * @return void
	 */
	public function display() {
		if ( ! $this->customer ) {
			?>
			<div class="sxp-profile-wrapper">
				<div class="sxp-profile-info" style="max-width: 100%;flex: 100%;">
					<p><?php esc_html_e( 'Customer Not Found', 'salexpresso' ); ?></p>
				</div>
				<!-- /.sxp-profile-info -->
			</div>
			<?php
			return;
		}
		$tags           = sxp_get_user_tags( $this->customer->get_id() );
		$group          = sxp_get_user_group( $this->customer->get_id() );
		$histories      = $this->get_histories();
		$tag_selected   = [];
		$group_selected = [];
		?>
		<div class="sxp-tag-wrapper">
			<ul class="sxp-tag">
			<?php
			if ( is_array( $tags ) ) {
				foreach ( $tags as $tag ) {
					$tag_selected[] = $tag->term_id;
					printf(
						'<li data-id="%s" data-taxonomy="%s"><a href="#%s">%s</a> <!-- <i data-feather="x"></i> --></li>',
						esc_attr( $tag->term_id ),
					'user_tag',
					esc_url( $tag->term_id ),
					esc_html( $tag->name )
					);
					?>
				<?php }
			} else {
				?>
				<li><a><?php esc_html_e( 'No Tag', 'salexpresso' ); ?></a></li>
				<?php
			}
			$tabs = $this->get_profile_tabs();
			?>
			</ul>
			<div class="sxp-tag-add">
				<a href="#sxp-tag-modal" rel="modal:open"><i data-feather="plus-circle"></i> <?php esc_html_e( 'Add tag', 'salexpresso' ); ?></a>
			</div>
		</div><!-- end .sxp-profile-tag -->
		<div class="sxp-profile-wrapper">
			<div class="sxp-profile-info">
				<div class="sxp-profile-profile">
					<?php
					if ( ! empty( $group ) && ! is_wp_error( $group ) ) {
						$color = sxp_get_term_background_color( $group );
						$group_selected[] = $group->term_id;
						?>
						<div class="sxp-profile-type" style="background: <?php echo esc_attr( $color ); ?>;"><?php echo esc_html( $group->name ); ?></div>
						<?php
					}
					?>
					<div class="sxp-profile-info-wrapper">
						<div class="sxp-profile-thumb">
							<a href="<?php echo esc_url( get_edit_profile_url( $this->user_id ) ); ?>">
								<?php echo get_avatar( $this->customer->get_email(), 72, '', $this->customer->get_display_name() ); ?>
							</a>
						</div>
						<h3><a href="<?php echo esc_url( get_edit_profile_url( $this->user_id ) ); ?>"><?php echo esc_html( $this->customer->get_display_name() ); ?></a></h3>
						<p><a href="mailto:<?php echo esc_url( $this->customer->get_email() ); ?>"><?php echo esc_html( $this->customer->get_email() ); ?></a></p>
						<p><?php echo esc_html( $this->customer->get_billing_address_1() ); ?></p>
					</div><!-- end .sxp-profile-info-wrapper -->
					<div class="sxp-profile-info-history">
						<?php
						if ( ! empty( $histories ) ) {
							foreach ( $histories as $history ) {
								if ( ! isset( $history['label'], $history['data'] ) ) {
									continue;
								}
								printf(
									'<div class="history"><p>%s</p><p>%s</p></div><!-- end .history -->',
									esc_html( $history['label'] ),
									esc_html( $history['data'] )
								);
							}
						}
						?>
					</div><!-- end .profile-info-history -->
				</div>
			</div><!-- end .sxp-profile-info -->
			<div class="sxp-profile-details">
				<?php if ( is_array( $tabs ) ) {
					$tab_ids = array_keys( $tabs );
					if ( isset( $_GET['tab'] ) && in_array( $_GET['tab'], $tab_ids ) ) {
						$current_tab = sanitize_text_field( $_GET['tab'] );
					} else {
						$current_tab = $tab_ids[0];
					}
				?>
					<nav class="nav-bar horizontal-scroll-bar">
						<span class="scroll scroll-backward">
							<a href="#" class="d-none">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" role="img" class="icon ">
								<path d="M21.5265 8.77171C22.1578 8.13764 22.1578 7.10962 21.5265 6.47555C20.8951 5.84148 19.8714 5.84148 19.24 6.47555L11.9999 13.7465L4.75996 6.47573C4.12858 5.84166 3.10492 5.84166 2.47354 6.47573C1.84215 7.10979 1.84215 8.13782 2.47354 8.77188L10.8332 17.1671C10.8408 17.1751 10.8486 17.183 10.8565 17.1909C11.0636 17.399 11.313 17.5388 11.577 17.6103C11.5834 17.6121 11.5899 17.6138 11.5964 17.6154C12.132 17.7536 12.7242 17.6122 13.1435 17.1911C13.1539 17.1807 13.1641 17.1702 13.1742 17.1596L21.5265 8.77171Z"></path>
								</svg>
							</a>
						</span>
						<span class="scroll scroll-forward">
							<a href="#" class="d-none">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" role="img" class="icon ">
								<path d="M21.5265 8.77171C22.1578 8.13764 22.1578 7.10962 21.5265 6.47555C20.8951 5.84148 19.8714 5.84148 19.24 6.47555L11.9999 13.7465L4.75996 6.47573C4.12858 5.84166 3.10492 5.84166 2.47354 6.47573C1.84215 7.10979 1.84215 8.13782 2.47354 8.77188L10.8332 17.1671C10.8408 17.1751 10.8486 17.183 10.8565 17.1909C11.0636 17.399 11.313 17.5388 11.577 17.6103C11.5834 17.6121 11.5899 17.6138 11.5964 17.6154C12.132 17.7536 12.7242 17.6122 13.1435 17.1911C13.1539 17.1807 13.1641 17.1702 13.1742 17.1596L21.5265 8.77171Z"></path>
								</svg>
							</a>
						</span>
						
						<ul class="tabs">
							<?php
							foreach ( $tabs as $tab_id => $tab ) {
								if ( ! isset( $tab['label'] ) ) {
									continue;
								}
								?>
								<li class="tab-link item<?php echo ( $tab_id === $current_tab ) ? ' current' : ''; ?>" data-tab="<?php echo esc_attr( $tab_id ); ?>">
									<a href="#"><?php
										echo esc_html( $tab['label'] );
										if ( isset( $tab['notification'] ) ) {
											?>
											<span class="number-bubble"><?php echo esc_html( $tab['notification'] ); ?></span>
											<?php
										}
									?></a>
								</li>
							<?php } ?>
						</ul>
					</nav>
					<?php
					
					foreach ( $tabs as $tab_id => $tab ) {
						if ( ! isset( $tab['label'] ) ) {
							continue;
						}
						?>
				<div id="<?php echo esc_attr( $tab_id ); ?>" class="tab-content<?php echo ( $tab_id === $current_tab ) ? ' current' : ''; ?>">
						<?php
						if( isset( $tab['content'] ) && is_callable( $tab['content'] ) ) {
							call_user_func( $tab['content'], $this->customer );
						} else if ( has_action( "salexpresso_customer_profile_tab_{$tab_id}" ) ) {
							do_action( "salexpresso_customer_profile_tab_{$tab_id}", $this->customer );
						} else {
							esc_html_e( 'No Data', 'salexpresso' );
						}
						?>
				</div><!-- end .tab-content -->
					<?php
					}
					?>
				<?php } ?>
			</div><!-- end .sxp-profile-details -->
		</div><!-- end .sxp-profile-wrapper -->
		<div id="sxp-tag-modal" class="modal">
			<div class="sxp-modal-content">
				<h4><?php esc_html_e( 'Add a tag', 'salexpresso' ); ?></h4>
				<label for="sxp-tag-select" class="screen-reader-text"><?php __('Tag dropdown select', 'salexpresso'); ?></label>
				<select name="tag" id="sxp-tag-select" class="sxp-modal selectize" multiple>
					<option value=""><?php esc_html_e( 'Select Tag', 'salexpresso' ); ?></option>
					<?php
					$tags = sxp_get_all_user_tags();
					if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) {
						foreach ( $tags as $tag ) {
							printf(
								'<option value="%s"%s>%s</option>',
								esc_attr( $tag->slug ),
								selected( in_array( $tag->term_id, $tag_selected ), true, false ),
								esc_html( $tag->name )
							);
						}
					}
					?>
				</select>
				<a href="#" class="sxp-btn sxp-btn-primary"><?php esc_html_e( 'Add Tag', 'salexpresso' ); ?></a>
			</div>
		</div>
		<script>
			( function( $ ) {
				$(document).on( 'click', '#sxp-tag-modal a.sxp-btn', function ( event ) {
					event.preventDefault();
					const selected = $('#sxp-tag-select option:selected');
					if ( selected.length ) {
						let data = [];
						selected.each( function() {
							let opt = $(this);
							if ( isNaN( opt.val() ) ) {
								data.push( opt.text() );
							} else {
								data.push( opt.val() );
							}
						} );
						wp.ajax.post( 'salexpresso_set_user_tag', {
							_wpnonce: '<?php echo wp_create_nonce( 'customer-profile-set-tag' ); ?>',
							user_id: '<?php echo $this->user_id; ?>',
							tags: data
						} )
							.then( r => {
								if ( r ) {
									if ( r.hasOwnProperty( 'message' ) ) {
										alert( r.message );
									} else if ( 'string' === typeof r ) {
										alert( r );
									}
								}
								window.location.reload();
							} )
							.fail( e => {
								if ( e.hasOwnProperty( 'statusText' ) ) {
									alert( e.hasOwnProperty( 'statusText' ) );
								} else if ( 'string' === typeof e ) {
									alert( e );
								} else {
									alert( 'UNKNOWN ERROR' );
								}
							} );
					}
				} );
			} )( jQuery );
		</script>
		<?php
	}
	
	/**
	 * Get tab list to display.
	 * @return array
	 */
	private function get_profile_tabs() {
		$tabs = apply_filters(
			'salexpresso_customer_profile_tabs',
			[
				'activity'       => [
					'label'        => __( 'Activity', 'salexpresso' ),
					'content'      => [ $this, 'display_activity' ],
					'notification' => '',
					'priority'     => 10,
				],
				/*'orders'         => [
					'label'        => __( 'Orders', 'salexpresso' ),
					'content'      => [ $this, 'display_orders' ],
					'notification' => '',
					'priority'     => 20,
				],
				'products'       => [
					'label'        => __( 'Products', 'salexpresso' ),
					'content'      => [ $this, 'display_products' ],
					'notification' => '',
					'priority'     => 30,
				],
				'active-cart'    => [
					'label'        => __( 'Active Cart', 'salexpresso' ),
					'content'      => [ $this, 'display_active_cart' ],
					'notification' => '',
					'priority'     => 40,
				],
				'searches'       => [
					'label'        => __( 'Searches', 'salexpresso' ),
					'content'      => [ $this, 'display_searches' ],
					'notification' => '',
					'priority'     => 50,
				],
				'recommendation' => [
					'label'        => __( 'Recommendation', 'salexpresso' ),
					'content'      => [ $this, 'display_recommendation' ],
					'notification' => '',
					'priority'     => 60,
				],
				'discount'       => [
					'label'        => __( 'Discount', 'salexpresso' ),
					'content'      => [ $this, 'display_discount' ],
					'notification' => '',
					'priority'     => 70,
				],
				'campaign'       => [
					'label'        => __( 'Campaign', 'salexpresso' ),
					'content'      => [ $this, 'display_campaign' ],
					'notification' => '',
					'priority'     => 80,
				],*/
			]
		);
		
		return wp_list_sort( $tabs, 'priority', 'ASC', TRUE );
	}
	
	private function display_activity() {
		$activity = _sxp_get_list_table( 'SaleXpresso\List_Table\SXP_Customer_Activity_List_Table' );
		$activity->set_data( $this->user_id, $this->analytics );
		$activity->prepare_items();
		$activity->display();
	}
	
	private function display_orders() {
		?>
		<table class="wp-list-table widefat sxp-table sxp-customer-profile-table">
			<thead>
			<tr>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Order Id</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">products</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Date</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Revenue</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Net Profit</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Status</a></th>
			</tr>
			</thead>
			<tbody id="the-list">
			<tr>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Order Id">
					<a href="#" class="order-number">200083726</a>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Products">
					<ul class="product-list multiple">
						<li><a href="#"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product thumb"></a></li>
						<li><a href="#"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product thumb"></a></li>
						<li><a href="#"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product thumb"></a></li>
						<li><a href="#"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product thumb"></a></li>
					</ul>
					<div class="product-number">9 Items</div>
				</td>
				<td data-colname="Date">Jan 20, 2020</td>
				<td data-colname="Revenue">$5739.2</td>
				<td data-colname="Net Profit">$87.03</td>
				<td data-colname="status">
					<div class="sxp-status sxp-status-success">Completed</div>
				</td>
			</tr>
			<tr>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Order Id">
					<a href="#" class="order-number">200083726</a>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Products">
					<ul class="product-list multiple">
						<li><a href="#"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product thumb"></a></li>
						<li><a href="#"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product thumb"></a></li>
						<li><a href="#"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product thumb"></a></li>
					</ul>
					<div class="product-number">9 Items</div>
				</td>
				<td data-colname="Date">Jan 20, 2020</td>
				<td data-colname="Revenue">$5739.2</td>
				<td data-colname="Net Profit">$87.03</td>
				<td data-colname="status">
					<div class="sxp-status sxp-status-info">Refunded</div>
				</td>
			</tr>
			<tr>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Order Id">
					<a href="#" class="order-number">200083726</a>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Products">
					<ul class="product-list multiple">
						<li><a href="#"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product thumb"></a></li>
						<li><a href="#"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product thumb"></a></li>
					</ul>
					<div class="product-number">9 Items</div>
				</td>
				<td data-colname="Date">Jan 20, 2020</td>
				<td data-colname="Revenue">$5739.2</td>
				<td data-colname="Net Profit">$87.03</td>
				<td data-colname="status">
					<div class="sxp-status sxp-status-success">Completed</div>
				</td>
			</tr>
			<tr>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Order Id">
					<a href="#" class="order-number">200083726</a>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Products">
					<ul class="product-list">
						<li><a href="#"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product thumb"></a></li>
					</ul>
					<div class="product-number">9 Items</div>
				</td>
				<td data-colname="Date">Jan 20, 2020</td>
				<td data-colname="Revenue">$5739.2</td>
				<td data-colname="Net Profit">$87.03</td>
				<td data-colname="status">
					<div class="sxp-status sxp-status-danger">Canceled</div>
				</td>
			</tr>
			</tbody>
		</table>
		<?php
	}
	
	private function display_products() {
		?>
		<table class="wp-list-table widefat sxp-table sxp-customer-profile-table">
			<thead>
			<tr>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Products</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Quantity</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Revenue</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Net Profit</a></th>
			</tr>
			</thead>
			<tbody id="the-list">
			<tr>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Products">
					<div class="product-wrap">
						<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
						<div class="product-name">Premium Miniket Rice</div>
					</div><!--product-wrap -->
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="quantity">66</td>
				<td data-colname="revenue">$806.87</td>
				<td data-colname="net-profit">$67.34</td>
			</tr>
			<tr>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Products">
					<div class="product-wrap">
						<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
						<div class="product-name">Premium Miniket Rice</div>
					</div><!--product-wrap -->
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="quantity">66</td>
				<td data-colname="revenue">$806.87</td>
				<td data-colname="net-profit">$67.34</td>
			</tr>
			<tr>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Products">
					<div class="product-wrap">
						<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
						<div class="product-name">Premium Miniket Rice</div>
					</div><!--product-wrap -->
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="quantity">66</td>
				<td data-colname="revenue">$806.87</td>
				<td data-colname="net-profit">$67.34</td>
			</tr>
			<tr>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Products">
					<div class="product-wrap">
						<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
						<div class="product-name">Premium Miniket Rice</div>
					</div><!--product-wrap -->
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="quantity">66</td>
				<td data-colname="revenue">$806.87</td>
				<td data-colname="net-profit">$67.34</td>
			</tr>
			<tr>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Products">
					<div class="product-wrap">
						<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
						<div class="product-name">Premium Miniket Rice</div>
					</div><!--product-wrap -->
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="quantity">66</td>
				<td data-colname="revenue">$806.87</td>
				<td data-colname="net-profit">$67.34</td>
			</tr>
			</tbody>
		</table>
		<?php
	}
	
	private function display_active_cart() {
		?>
		<table class="wp-list-table widefat sxp-table sxp-customer-profile-table sxp-active-cart-table">
			<thead>
			<tr>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Sl.</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">All Products</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Unit Price</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">QTY</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Amount</a></th>
			</tr>
			</thead>
			<tbody id="the-list">
			<tr>
				<td>1.</td>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="All Products">
					<div class="product-wrap">
						<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
						<div class="product-details">
							<div class="product-name">Premium Miniket Rice</div>
							<div class="product-sku">OC063-0001</div>
						</div>
					</div><!-- end .product-wrap -->
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Unit Price">$61.87</td>
				<td data-colname="QTY">x 2</td>
				<td data-colname="Amount">$123.74</td>
			</tr>
			<tr>
				<td>2.</td>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="All Products">
					<div class="product-wrap">
						<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
						<div class="product-details">
							<div class="product-name">Premium Miniket Rice</div>
							<div class="product-sku">OC063-0001</div>
						</div>
					</div><!-- end .product-wrap -->
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Unit Price">$61.87</td>
				<td data-colname="QTY">x 2</td>
				<td data-colname="Amount">$123.74</td>
			</tr>
			<tr>
				<td>3.</td>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="All Products">
					<div class="product-wrap">
						<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
						<div class="product-details">
							<div class="product-name">Premium Miniket Rice</div>
							<div class="product-sku">OC063-0001</div>
						</div>
					</div><!-- end .product-wrap -->
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Unit Price">$61.87</td>
				<td data-colname="QTY">x 2</td>
				<td data-colname="Amount">$123.74</td>
			</tr>
			</tbody>
			<tfoot>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>Sub Total</td>
				<td>$5,641.01</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>Tax Included</td>
				<td>+$550.00</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>Shipping Charge</td>
				<td>+$250.00</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>Discount Received</td>
				<td>-$1,500.00</td>
			</tr>
			<tr>
				<td><i data-feather="file-text"></i> Place Order</td>
				<td><i data-feather="mail"></i> Send Mail</td>
				<td></td>
				<td>Grand Total</td>
				<td>$4,941.01‬</td>
			</tr>
			</tfoot>
		</table>
		<?php
	}
	
	private function display_searches() {
		?>
		<table class="wp-list-table widefat sxp-table sxp-customer-profile-table">
			<thead>
			<tr>
				<td id="cb" class="manage-column column-cb check-column">
					<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
					<input id="cb-select-all-1" type="checkbox">
				</td>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Search Queries</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">#Searhes</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Sessions</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Stock</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Lifetime Purchase</a></th>
			</tr>
			</thead>
			<tbody id="the-list">
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Search Queries">
					<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
					<div class="product-name">Premium Miniket Rice</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="#Searches">9</td>
				<td data-colname="#Sesssions">4</td>
				<td data-colname="Stock">37</td>
				<td data-colname="Lifetime Purchase">59</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Search Queries">
					<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
					<div class="product-name">Premium Miniket Rice</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="#Searches">9</td>
				<td data-colname="#Sesssions">4</td>
				<td data-colname="Stock">37</td>
				<td data-colname="Lifetime Purchase">59</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td  class="title column-title has-row-actions column-primary page-title" data-colname="Search Queries">
					<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
					<div class="product-name">Premium Miniket Rice</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="#Searches">9</td>
				<td data-colname="#Sesssions">4</td>
				<td data-colname="Stock">37</td>
				<td data-colname="Lifetime Purchase">59</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Search Queries">
					<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
					<div class="product-name">Premium Miniket Rice</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="#Searches">9</td>
				<td data-colname="#Sesssions">4</td>
				<td data-colname="Stock">37</td>
				<td data-colname="Lifetime Purchase">59</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Search Queries">
					<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
					<div class="product-name">Premium Miniket Rice</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="#Searches">9</td>
				<td data-colname="#Sesssions">4</td>
				<td data-colname="Stock">37</td>
				<td data-colname="Lifetime Purchase">59</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Search Queries">
					<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
					<div class="product-name">Premium Miniket Rice</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="#Searches">9</td>
				<td data-colname="#Sesssions">4</td>
				<td data-colname="Stock">37</td>
				<td data-colname="Lifetime Purchase">59</td>
			</tr>
			</tbody>
		</table>
		<?php
	}
	
	private function display_recommendation() {
		?>
		<table class="wp-list-table widefat sxp-table sxp-customer-profile-table">
			<thead>
			<tr>
				<td id="cb" class="manage-column column-cb check-column">
					<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
					<input id="cb-select-all-1" type="checkbox">
				</td>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Product</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Regular Price</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Discount Price</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Created</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Status</a></th>
			</tr>
			</thead>
			<tbody id="the-list">
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Product">
					<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
					<div class="product-name">Premium Miniket Rice</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Regular Price">$61</td>
				<td data-colname="Discount Price">$29</td>
				<td data-colname="Created">May 2, 2019</td>
				<td data-colname="Status">
					<div class="sxp-status sxp-status-success">Seen</div>
				</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Product">
					<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
					<div class="product-name">Premium Miniket Rice</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Regular Price">$61</td>
				<td data-colname="Discount Price">$29</td>
				<td data-colname="Created">May 2, 2019</td>
				<td data-colname="Status">
					<div class="sxp-status sxp-status-success">Seen</div>
				</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Product">
					<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
					<div class="product-name">Premium Miniket Rice</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Regular Price">$61</td>
				<td data-colname="Discount Price">$29</td>
				<td data-colname="Created">May 2, 2019</td>
				<td data-colname="Status">
					<div class="sxp-status sxp-status-success">Seen</div>
				</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Product">
					<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
					<div class="product-name">Premium Miniket Rice</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Regular Price">$61</td>
				<td data-colname="Discount Price">$29</td>
				<td data-colname="Created">May 2, 2019</td>
				<td data-colname="Status">
					<div class="sxp-status sxp-status-danger">Not Seen</div>
				</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Product">
					<div class="product-thumb"><img src="<?php echo esc_url( sxp_get_plugin_uri( 'assets/images/egg.png' ) ); ?>" alt="Product"></div>
					<div class="product-name">Premium Miniket Rice</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Regular Price">$61</td>
				<td data-colname="Discount Price">$29</td>
				<td data-colname="Created">May 2, 2019</td>
				<td data-colname="Status">
					<div class="sxp-status sxp-status-success">Seen</div>
				</td>
			</tr>
			</tbody>
		</table>
		<?php
	}
	
	private function display_discount() {
		?>
		<table class="wp-list-table widefat sxp-table sxp-customer-profile-table">
			<thead>
			<tr>
				<td id="cb" class="manage-column column-cb check-column">
					<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
					<input id="cb-select-all-1" type="checkbox">
				</td>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Coupon Codes</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Discount</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Usage</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Saved</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Validity</a></th>
			</tr>
			</thead>
			<tbody id="the-list">
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td class="title column-title has-row-actions column-primary page-title sxp-discount-coupon" data-colname="Coupon Codes">
					<div class="sxp-status sxp-status-success">20XVJ372U</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Discount">4%</td>
				<td data-colname="Usage">Used 1 time</td>
				<td data-colname="Saved">$44.19</td>
				<td data-colname="Validity">22 days left</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td class="title column-title has-row-actions column-primary page-title sxp-discount-coupon" data-colname="Coupon Codes">
					<div class="sxp-status sxp-status-default">20XVJ372U</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Discount">7%</td>
				<td data-colname="Usage">Used 1 time</td>
				<td data-colname="Saved">$44.19</td>
				<td data-colname="Validity">-</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td class="title column-title has-row-actions column-primary page-title sxp-discount-coupon" data-colname="Coupon Codes">
					<div class="sxp-status sxp-status-default">20XVJ372U</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Discount">Flat 10%</td>
				<td data-colname="Usage" class="not-used">Not used yet</td>
				<td data-colname="Saved">-</td>
				<td data-colname="Validity">-</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td class="title column-title has-row-actions column-primary page-title sxp-discount-coupon" data-colname="Coupon Codes">
					<div class="sxp-status sxp-status-success">20XVJ372U</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Discount">9%</td>
				<td data-colname="Usage" class="not-used">Not used yet</td>
				<td data-colname="Saved">-</td>
				<td data-colname="Validity">9 days left</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td class="title column-title has-row-actions column-primary page-title sxp-discount-coupon" data-colname="Coupon Codes">
					<div class="sxp-status sxp-status-success">20XVJ372U</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Discount">12%</td>
				<td data-colname="Usage">Used 3 times</td>
				<td data-colname="Saved">$44.19</td>
				<td data-colname="Validity">11 days left</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td class="title column-title has-row-actions column-primary page-title sxp-discount-coupon" data-colname="Coupon Codes">
					<div class="sxp-status sxp-status-success">20XVJ372U</div>
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Discount">11%</td>
				<td data-colname="Usage">Used 5 times</td>
				<td data-colname="Saved">$44.19</td>
				<td data-colname="Validity">12 days left</td>
			</tr>
			</tbody>
		</table>
		<?php
	}
	
	private function display_campaign() {
		?>
		<table class="wp-list-table widefat sxp-table sxp-customer-profile-table">
			<thead>
			<tr>
				<td id="cb" class="manage-column column-cb check-column">
					<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
					<input id="cb-select-all-1" type="checkbox">
				</td>
				<th scope="col" class="manage-column column-title column-primary sortable desc"></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Campaign Name</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Status</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Type</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Views</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Clicks</a></th>
				<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#">Revenue</a></th>
			</tr>
			</thead>
			<tbody id="the-list">
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td data-colname="Switch">
					<label class="sxp-switch">
						<input type="checkbox">
						<span class="sxp-slider round"></span>
					</label>
				</td>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Campaign Name">
					Best Birthday sale
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Status">
					<div class="sxp-status sxp-status-success">Active</div>
				</td>
				<td data-colname="Type">
					<div class="sxp-type"><i data-feather="mail"></i> Email</div>
				</td>
				<td data-colname="Views">80</td>
				<td data-colname="Clicks">61</td>
				<td data-colname="Revenue">$867.12</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td data-colname="Switch">
					<label class="sxp-switch">
						<input type="checkbox" checked>
						<span class="sxp-slider round"></span>
					</label>
				</td>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Campaign Name">
					Best Birthday sale
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Status">
					<div class="sxp-status sxp-status-info">Scheduled</div>
				</td>
				<td data-colname="Type">
					<div class="sxp-type"><i data-feather="smartphone"></i> SMS</div>
				</td>
				<td data-colname="Views">-</td>
				<td data-colname="Clicks">-</td>
				<td data-colname="Revenue">-</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td data-colname="Switch">
					<label class="sxp-switch">
						<input type="checkbox">
						<span class="sxp-slider round"></span>
					</label>
				</td>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Campaign Name">
					Best Birthday sale
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Status">
					<div class="sxp-status sxp-status-primary">Completed</div>
				</td>
				<td data-colname="Type">
					<div class="sxp-type"><i data-feather="mail"></i> Email</div>
				</td>
				<td data-colname="Views">80</td>
				<td data-colname="Clicks">61</td>
				<td data-colname="Revenue">$867.12</td>
			</tr>
			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1"></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				</th>
				<td data-colname="Switch">
					<label class="sxp-switch">
						<input type="checkbox" checked>
						<span class="sxp-slider round"></span>
					</label>
				</td>
				<td class="title column-title has-row-actions column-primary page-title" data-colname="Campaign Name">
					Best Birthday sale
					<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
				</td>
				<td data-colname="Status">
					<div class="sxp-status sxp-status-default">Disabled</div>
				</td>
				<td data-colname="Type">
					<div class="sxp-type"><i data-feather="smartphone"></i> SMS</div>
				</td>
				<td data-colname="Views">80</td>
				<td data-colname="Clicks">61</td>
				<td data-colname="Revenue">$867.12</td>
			</tr>
			</tbody>
		</table>
		<?php
	}
	
	/**
	 * Customer History.
	 *
	 * @return array
	 */
	private function get_histories() {
		return [
			'acquired_via' => [
				'label' => __( 'Acquired via', 'salexpresso' ),
				'data'  => $this->get_acquired_via(),
			],
			'revenue'      => [
				'label' => __( 'Revenue', 'salexpresso' ),
				'data'  => wc_get_customer_total_spent( $this->user_id ),
			],
			'sessions'     => [
				'label' => __( 'Sessions', 'salexpresso' ),
				'data'  => $this->analytics->get_user_session_count(),
			],
			'orders'       => [
				'label' => __( 'Orders', 'salexpresso' ),
				'data'  => $this->customer->get_order_count(),
			],
			'actions'      => [
				'label' => __( 'Actions', 'salexpresso' ),
				'data'  => $this->analytics->get_user_action_count( 'page-leave', false ),
			],
			'first_order'  => [
				'label' => __( 'First Order', 'salexpresso' ),
				'data'  => $this->get_first_order_date(),
			],
			'last_order'   => [
				'label' => __( 'Last Order', 'salexpresso' ),
				'data'  => $this->get_last_order_date(),
			],
			'last_active'  => [
				'label' => __( 'Last Active', 'salexpresso' ),
				'data'  => $this->get_last_active(),
			],
		];
	}
	
	/**
	 * Get Acquired via.
	 *
	 * @return string
	 */
	private function get_acquired_via() {
		return get_user_meta( $this->user_id, '__acquired_via', true );
	}
	
	/**
	 * Get First Order Date.
	 *
	 * @return false|string
	 */
	private function get_first_order_date() {
		$date = get_user_meta( $this->user_id, '_first_order_date', true );
		return $date ? gmdate( 'd M, Y', strtotime( $date ) ) : '';
	}
	
	/**
	 * Get Last Order Date.
	 *
	 * @return false|string
	 */
	private function get_last_order_date() {
		$date = get_user_meta( $this->user_id, '_last_order_date', true );
		return $date ? gmdate( 'd M, Y', strtotime( $date ) ) : '';
	}
	
	/**
	 * Get the last activity date.
	 *
	 * @return false|string
	 */
	private function get_last_active() {
		$last_active = $this->analytics->get_user_last_active();
		return $last_active ? gmdate( 'd M, Y', $last_active ) : '';
	}
}

// End of file class-sxp-profile-list-table.php.
