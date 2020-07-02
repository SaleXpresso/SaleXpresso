<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Customer
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso\Customer;

use SaleXpresso\SXP_Admin_Menus;
use SaleXpresso\Abstracts\SXP_Admin_Page;
use SaleXpresso\Rules\SXP_Rules_Group_Action;
use WP_Term;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Customers_Page
 *
 * @package SaleXpresso\Customer
 */
class SXP_Customer_Group_Page extends SXP_Admin_Page {
	
	/**
	 * Add new button url for current page.
	 *
	 * @var string
	 */
	protected $add_new_url = '';
	
	/**
	 * List table instance.
	 *
	 * @var SXP_Customer_Group_List_Table
	 */
	private $list_table;
	
	/**
	 * The Term That is being edited.
	 *
	 * @var WP_Term
	 */
	private $term = false;
	
	/**
	 * SXP_Customer_List constructor.
	 *
	 * @param string $plugin_page Current page slug.
	 */
	public function __construct( $plugin_page = null ) {
		$this->list_table = SXP_Admin_Menus::get_instance()->get_list_table();
		
		$this->set_current_action();
		
		if ( 'edit' === $this->current_action && ! isset( $_GET['id'] ) ) {
			wp_safe_redirect( wp_get_referer() );
			die();
		}
		
		if ( 'edit' === $this->current_action ) {
			$this->term = get_term( absint( $_GET['id'] ), 'user_group' );
			if ( is_wp_error( $this->term ) ) {
				$this->set_flash_message( $this->term->get_error_message(), 'error', true );
			}
		}
		
		if ( 'add-new' === $this->current_action ) {
			$this->term = new WP_Term( (object) [] );
		}
		
		$this->titles = [
			'actions' => [
				'add-new' => esc_html__( 'New Customer Group', 'salexpresso' ),
				'edit'    => esc_html__( 'Edit Customer Group', 'salexpresso' ),
			]
		];
		
		parent::__construct( $plugin_page );
		
		if ( isset( $_POST['action'] ) && in_array( $_POST['action'], [ 'add-user-group', 'edit-user-group' ] ) ) {
			$term_id = 0;
			$page    = esc_url( admin_url( 'admin.php?page=sxp-customer-group' ) );
			if ( isset( $_POST['term_id'] ) ) {
				$term_id = absint( $_POST['term_id'] );
			}
			if ( ! isset( $_POST['name'] ) || ( isset( $_POST['name'] ) && empty( $_POST['name'] ) ) ) {
				$this->set_flash_message( esc_html__( 'Name is missing', 'salexpresso' ), 'error', true );
				wp_safe_redirect( $page );
				die();
			}
			
			$name = sanitize_text_field( $_POST['name'] );
			
			if ( $term_id ) {
				$id = sxp_update_user_group( $term_id, [ 'name' => $name ] );
			} else {
				$id = sxp_add_user_group( $name );
				if ( ! is_wp_error( $id ) ) {
					$term_id = $id['term_id'];
				}
			}
			
			if ( is_wp_error( $id ) ) {
				$message = $term_id ? esc_html__( 'There was an error updating group. %s', 'salexpresso' ) : esc_html__( 'There was an error creating new group. %s', 'salexpresso' );
				$this->set_flash_message( sprintf( $message, $id->get_error_message() ), 'error', true );
				wp_safe_redirect( $page );
				die();
			}
			
			if ( isset( $_POST['sxp_rule'] ) && is_array( $_POST['sxp_rule'] ) ) {
				$rules = [];
				foreach ( $_POST['sxp_rule'] as $rule ) {
					if ( ! isset( $rule['compare'], $rule['operator'], $rule['values'] ) ) {
						continue;
					}
					if ( empty( $rule['compare'] ) || empty( $rule['operator'] ) || empty( $rule['values'] ) ) {
						continue;
					}
					
					$rules[] = [
						'relation' => 'AND',
						'compare'  => sanitize_text_field( $rule['compare'] ),
						'operator' => sanitize_text_field( $rule['operator'] ),
						'values'   => sanitize_text_field( $rule['values'] ),
					];
				}
				
				if ( $term_id && ! empty( $rules ) ) {
					$save = sxp_save_term_rules( $term_id, $rules );
					if ( is_wp_error( $save ) ) {
						$this->set_flash_message( sprintf( esc_html__( 'Error Saving Rules. %s'), $save->get_error_message() ), 'error', true );
					}
				}
			}
			
			$message = $term_id ? esc_html__( 'Group Updated', 'salexpresso' ) : esc_html__( 'Group Created.', 'salexpresso' );
			$this->set_flash_message( $message, 'success', true );
			
			wp_safe_redirect( $page );
			die();
		}
	}
	
	/**
	 * Page Actions.
	 */
	public function page_actions() {
		if ( in_array( $this->current_action, [ 'edit', 'add-new' ] ) ) {
			return;
		}
		add_screen_option(
			'per_page',
			[
				'label'   => 'Number of items per page:',
				'option'  => 'customer_groups_per_page',
			]
		);
	}
	
	/**
	 * Set Tabs and tab content.
	 */
	protected function set_tabs() {}
	
	/**
	 * Render Filter section
	 */
	protected function render_page_filter() {
		if ( in_array( $this->current_action, [ 'edit', 'add-new' ] ) ) {
			return;
		}
		?>
		<div class="sxp-filter-wrapper">
			<div class="sxp-filter-default">
				<nav class="vg-nav vg-nav-lg">
					<ul>
						<li class="dropdown">
							<a href="#">Sort by Name</a>
							<ul class="left">
								<li>
									<a href="#">Location</a>
								</li>
								<li class="dropdown">
									<a href="#">Customer Type</a>
									<ul class="left">
										<li>
											<a href="#">Another page</a>
										</li>
										<li>
											<a href="#">Any page</a>
										</li>
									</ul>
								</li>
								<li>
									<a href="#">Orders</a>
								</li>
								<li>
									<a href="#">Revenue</a>
								</li>
								<li>
									<a href="#">First Order</a>
								</li>
							</ul>
						</li>
					</ul>
				</nav>
			</div><!-- end .sxp-filter-default -->
			<div class="sxp-filter-date-range">
				<div id="sxp-date-range" tabindex="0" aria-label="filter by date">
					<span></span>
				</div>
			</div><!-- end .sxp-filter-date-range-->
			<div class="sxp-screen-options">
				<a href="#">
					<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDEzQzEyLjU1MjMgMTMgMTMgMTIuNTUyMyAxMyAxMkMxMyAxMS40NDc3IDEyLjU1MjMgMTEgMTIgMTFDMTEuNDQ3NyAxMSAxMSAxMS40NDc3IDExIDEyQzExIDEyLjU1MjMgMTEuNDQ3NyAxMyAxMiAxM1oiIHN0cm9rZT0iIzdEN0RCMyIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPHBhdGggZD0iTTE5IDEzQzE5LjU1MjMgMTMgMjAgMTIuNTUyMyAyMCAxMkMyMCAxMS40NDc3IDE5LjU1MjMgMTEgMTkgMTFDMTguNDQ3NyAxMSAxOCAxMS40NDc3IDE4IDEyQzE4IDEyLjU1MjMgMTguNDQ3NyAxMyAxOSAxM1oiIHN0cm9rZT0iIzdEN0RCMyIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPHBhdGggZD0iTTUgMTNDNS41NTIyOCAxMyA2IDEyLjU1MjMgNiAxMkM2IDExLjQ0NzcgNS41NTIyOCAxMSA1IDExQzQuNDQ3NzIgMTEgNCAxMS40NDc3IDQgMTJDNCAxMi41NTIzIDQuNDQ3NzIgMTMgNSAxM1oiIHN0cm9rZT0iIzdEN0RCMyIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+Cg==" alt="more">
				</a>
			</div>
		</div><!-- end .sxp-filter-wrapper-->
		<div class="clearfix"></div>
		<?php
	}
	
	/**
	 * Render the customer list
	 */
	protected function render_page_content() {
		switch ( $this->current_action ) {
			case 'add-new':
			case 'edit':
				$this->render_form();
				break;
			default:
				$this->list_table->display();
				break;
		}
	}
	
	protected function render_form() {
		$conditions = SXP_Rules_Group_Action::get_instance()->get_conditions();
		$operators = SXP_Rules_Group_Action::get_instance()->get_operators();
		$rules = sxp_get_term_rules( $this->term );
		if ( empty( $rules ) ) {
			$rules[] = [
				'operator' => '',
				'condition' => '',
				'values' => [],
			];
		}
		?>
		<div class="sxp-rule-wrapper">
			<form action="<?php echo esc_url( admin_url( 'admin.php?page=' . $this->page_slug ) ) ?>" method="post" class="sxp-form">
				<?php if ( ! $this->term->term_id ) { ?>
				<input type="hidden" name="action" value="add-user-group">
				<?php } else { ?>
				<input type="hidden" name="action" value="edit-user-group">
				<input type="hidden" name="term_id" value="<?php echo $this->term->term_id; ?>">
				<?php } ?>
				<div class="form-top">
					<div class="section">
						<h4 class="header"><?php esc_html_e( 'Customer Group Name', 'salexpresso' ); ?> <i data-feather="info"></i></h4>
						<!-- /.header -->
						<input type="text" class="title-edit" name="name" value="<?php echo esc_attr( $this->term->name ); ?>">
						<!-- /.title-edit -->
					</div>
					<!-- /.section -->
				</div>
				<!-- /.form-top -->
				<div class="clearfix"></div>
				<div class="form-body">
					<div class="section sxp-rule-container">
						<h4 class="header">Create Rule <i data-feather="info"></i></h4>
						<div class="sxp-rules-wrapper">
							<div class="sxp-rules">
								<?php
								$idx = 1;
								foreach( $rules as $rule ) {
									if ( ! isset( $rule['condition'], $rule['operator'], $rule['values'] ) ) {
										continue;
									}
								?>
								<div class="sxp-rule-single rule_<?php echo $idx; ?>">
									<label for="rule_compare_<?php echo $idx; ?>" class="screen-reader-text"><?php esc_html_e('Select Condition To Check', 'salexpresso'); ?> </label>
									<select id="rule_compare_<?php echo $idx; ?>" name="sxp_rule[<?php echo $idx; ?>][compare]">
										<option value=""><?php esc_html_e( 'Select Condition', 'salexpresso' ); ?></option>
										<?php
										foreach ( $conditions as $slug => $data ) {
											printf( '<option value="%s"%s>%s</option>', esc_attr( $slug ), selected( $rule['condition'], $slug, false ), esc_html( $data['label'] ) );
										}
										?>
									</select>
									<label for="rule_operator_<?php echo $idx; ?>" class="screen-reader-text"><?php __('Select Comparison Operator', 'salexpresso'); ?> </label>
									<select id="rule_operator_<?php echo $idx; ?>" name="sxp_rule[<?php echo $idx; ?>][operator]">
										<option value=""><?php esc_html_e( 'Select Operator', 'salexpresso' ); ?></option>
										<?php
										foreach ( $operators as $slug => $data ) {
											printf( '<option value="%s"%s>%s</option>', esc_attr( $slug ), selected( $rule['operator'], $slug, false ), esc_html( $data['label'] ) );
										}
										?>
									</select>
									<label for="rule_values_<?php echo $idx; ?>" class="screen-reader-text"><?php esc_html_e( 'Value to compare', 'salexpresso' ); ?></label>
									<input type="text" id="rule_values_<?php echo $idx; ?>" name="sxp_rule[<?php echo $idx; ?>][values]" placeholder="<?php esc_attr_e( 'Value to compare', 'salexpresso' ); ?>" value="<?php
									if ( is_array( $rule['values'] ) ) {
										echo esc_attr( implode( ',', $rule['values'] ) );
									} else {
										echo esc_attr( $rule['values'] );
									}
									?>">
								</div><!-- end .sxp-rule-single -->
								<?php
									$idx++;
								}
								?>
							</div><!-- end .sxp-rules -->
						</div><!-- end .sxp-rules-wrapper -->
					</div>
					<!-- /.section sxp-rule-container -->
				</div>
				<!-- /.form-body -->
				<div class="clearfix"></div>
				<div class="form-bottom">
					<div class="sxp-rule-bottom">
						<div class="sxp-rule-add-btn sxp-btn-link">
							<script type="text/template" class="rule_ui_template">
								<div class="sxp-rule-single rule___IDX__">
									<label for="rule_compare___IDX__" class="screen-reader-text"><?php esc_html_e('Select Condition To Check', 'salexpresso'); ?> </label>
									<select id="rule_compare___IDX__" name="sxp_rule[__IDX__][compare]">
										<option value=""><?php esc_html_e( 'Select Condition', 'salexpresso' ); ?></option>
										<?php
										foreach ( $conditions as $slug => $data ) {
											printf( '<option value="%s">%s</option>', esc_attr( $slug ), esc_html( $data['label'] ) );
										}
										?>
									</select>
									<label for="rule_operator___IDX__" class="screen-reader-text"><?php __('Select Comparison Operator', 'salexpresso'); ?> </label>
									<select id="rule_operator___IDX__" name="sxp_rule[__IDX__][operator]">
										<option value=""><?php esc_html_e( 'Select Operator', 'salexpresso' ); ?></option>
										<?php
										foreach ( $operators as $slug => $data ) {
											printf( '<option value="%s">%s</option>', esc_attr( $slug ), esc_html( $data['label'] ) );
										}
										?>
									</select>
									<label for="rule_values___IDX__" class="screen-reader-text"><?php esc_html_e( 'Value to compare', 'salexpresso' ); ?></label>
									<input type="text" id="rule_values___IDX__" name="sxp_rule[__IDX__][values]" placeholder="<?php esc_attr_e( 'Value to compare', 'salexpresso' ); ?>" value="">
								</div><!-- end .sxp-rule-single -->
							</script>
							<a href="#" class="sxp-btn sxp-btn-link"><i data-feather="plus"></i> <?php esc_html_e( 'Add Condition', 'salexpresso' ); ?></a>
							<script>
								(function($){
									var template = $('.rule_ui_template').text(),
										wrapper = $('.sxp-rules'),
										length = wrapper.find('.sxp-rule-single').length || 1;
									if ( '' !== template ) {
										template.trim();
									}
									if ( '' === template ) {
										return;
									}
									
									$(document).on('click', '.sxp-rule-add-btn .sxp-btn-link', function( e ) {
										e.preventDefault();
										length += 1;
										wrapper.append( template.replace( /__IDX__/g, length ) );
									});
								})(jQuery);
							</script>
						</div><!-- end .sxp-customer-rule-add-btn -->
					</div><!-- end .sxp-customer-rule-bottom -->
					<div class="save-wrapper">
						<a class="sxp-btn sxp-btn-cancel" href="#" onclick="return confirm( '<?php esc_attr_e( 'Are You Sure?\nAny changes you made will be lost.', 'salexpresso' ); ?>' ) ? history.back() : false;">Cancel</a>
						<input type="submit" class="sxp-btn sxp-btn-primary btn-save" value="<?php esc_attr_e( 'Save New Customer Type', 'salexpresso' ); ?>">
					</div><!-- end .sxp-customer-rule-save-wrapper -->
				</div>
				<!-- /.form-bottom -->
			</form>
			<!-- /.sxp-form -->
		</div><!-- end .sxp-rule-wrapper -->
		<?php
	}
}

// End of file class-sxp-customer-group-page.php.
