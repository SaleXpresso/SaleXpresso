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
use SaleXpresso\SXP_Post_Types;
use SaleXpresso\Rules\SXP_Rules_Group_Action;
use SaleXpresso\List_Table\SXP_Customer_Group_List_Table;
use WP_Error;
use WP_Taxonomy;
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
	protected $list_table;
	
	/**
	 * The Term That is being edited.
	 *
	 * @var WP_Term
	 */
	protected $term = false;
	
	/**
	 * The Taxonomy Slug.
	 *
	 * @var string
	 */
	protected $taxonomy_name = SXP_Post_Types::CUSTOMER_GROUP_TAX;
	
	/**
	 * The Taxonomy.
	 *
	 * @var WP_Taxonomy
	 */
	protected $taxonomy;
	
	/**
	 * SXP_Customer_List constructor.
	 *
	 * @param string $plugin_page Current page slug.
	 */
	public function __construct( $plugin_page = null ) {
		parent::__construct( $plugin_page );
		$this->taxonomy = get_taxonomy( $this->taxonomy_name );
		$this->init();
		$this->form_action_handler();
	}
	
	/**
	 * Init Page Actions.
	 *
	 * @return void
	 */
	protected function init() {
		$this->list_table = SXP_Admin_Menus::get_instance()->get_list_table();
		
		$this->set_current_action();
		
		if ( 'edit' === $this->current_action && ! isset( $_GET['id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			wp_safe_redirect( wp_get_referer() );
			die();
		}
		
		if ( 'edit' === $this->current_action ) {
			$this->term = get_term( absint( $_GET['id'] ), $this->taxonomy_name ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( is_wp_error( $this->term ) ) {
				$this->set_flash_message( $this->term->get_error_message(), 'error', true );
			}
		}
		
		if ( 'add-new' === $this->current_action ) {
			$this->term = new WP_Term( (object) [] );
		}
	}
	
	/**
	 * Handle The Term add/edit form post request.
	 *
	 * @return void
	 */
	protected function form_action_handler() {
		if ( ! empty( $this->get_current_action() ) && 'post' === $this->get_request_method() && in_array( $this->get_current_action(), [ 'add-' . $this->taxonomy_name, 'edit-' . $this->taxonomy_name ] ) ) {
			// Security check.
			check_admin_referer( $this->get_current_action() );
			
			$term_id = 0;
			$page    = esc_url( admin_url( 'admin.php?page=' . $this->page_slug ) );
			
			// Get posted data.
			if ( isset( $_POST['term_id'] ) ) {
				$term_id = absint( $_POST['term_id'] );
			}
			
			if ( ! isset( $_POST['name'] ) || ( isset( $_POST['name'] ) && empty( $_POST['name'] ) ) ) {
				$name = new WP_Error( 'term_name_missing', esc_html__( 'Term Name missing.', 'salexpresso' ) );
				$this->term_error_flash( $name, $term_id );
				wp_safe_redirect( $page );
				die();
			}
			
			$name = sanitize_text_field( $_POST['name'] );
			$id   = new WP_Error( 'invalid_taxonomy', __( 'Invalid Taxonomy.', 'salexpresso' ) );
			
			if ( $term_id ) {
				if ( is_callable( 'sxp_update_' . $this->taxonomy_name ) ) {
					// @see sxp_update_user_group
					$id = call_user_func( 'sxp_update_' . $this->taxonomy_name, $term_id, [ 'name' => $name ]);
				}
			} else {
				if ( is_callable( 'sxp_add_' . $this->taxonomy_name ) ) {
					// @see sxp_add_user_group
					$id = call_user_func( 'sxp_add_' . $this->taxonomy_name, $term_id, [ 'name' => $name ]);
					if ( ! is_wp_error( $id ) ) {
						$term_id = $id['term_id'];
					}
				}
			}
			
			if ( is_wp_error( $id ) ) {
				$this->term_error_flash( $id, $term_id );
				wp_safe_redirect( $page );
				die();
			}
			
			if ( isset( $_POST['sxp_rule'] ) && is_array( $_POST['sxp_rule'] ) ) {
				$rules = [];
				foreach ( $_POST['sxp_rule'] as $rule ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					if ( ! isset( $rule['compare'], $rule['operator'], $rule['values'] ) ) {
						continue;
					}
					
					if ( empty( $rule['compare'] ) || empty( $rule['operator'] ) || empty( $rule['values'] ) ) {
						continue;
					}
					
					$rules[] = [
						'relation'  => 'AND',
						'condition' => sanitize_text_field( $rule['condition'] ),
						'operator'  => sanitize_text_field( $rule['operator'] ),
						'values'    => sanitize_text_field( $rule['values'] ),
					];
				}
				
				if ( $term_id && ! empty( $rules ) ) {
					$save = sxp_save_term_rules( $term_id, $rules );
					if ( is_wp_error( $save ) ) {
						$this->set_flash_message( sprintf(
							/* translators: 1 Error message while saving the term. */
							esc_html__( 'Error Saving Rules. %s', 'salexpresso' ),
							$save->get_error_message()
						), 'error', true );
					}
				}
			}
			
			if ( $term_id ) {
				/* translators: 1 Taxonomy term that just been updated. */
				$message = esc_html__( '%s Updated', 'salexpresso' );
			} else {
				/* translators: 1 Taxonomy term that just been created. */
				$message = esc_html__( '%s Created.', 'salexpresso' );
			}
			$this->set_flash_message( sprintf( $message, $this->taxonomy->labels->singular_name ), 'success', true );
			
			wp_safe_redirect( $page );
			die();
		}
	}
	
	/**
	 * Set the flash error message.
	 *
	 * @param WP_Error $error   Error object.
	 * @param bool     $update      Updating or creating.
	 *
	 * @return void
	 */
	private function term_error_flash( $error, $update ) {
		if ( $update ) {
			/* translators: Error message of term update WP_Error Object. */
			$message = esc_html__( 'There was an error updating the term. %s', 'salexpresso' );
		} else {
			/* translators: Error message of term add WP_Error Object. */
			$message = esc_html__( 'There was an error creating new term. %s', 'salexpresso' );
		}
		$this->set_flash_message( sprintf( $message, $error->get_error_message() ), 'error', true );
	}
	
	/**
	 * Set Screen Option
	 *
	 * @return void
	 */
	public function page_actions() {
		if ( in_array( $this->current_action, [ 'edit', 'add-new' ] ) ) {
			return;
		}
		add_screen_option(
			'per_page',
			[
				'label'  => 'Number of items per page:',
				'option' => $this->taxonomy_name . '_per_page',
			]
		);
	}
	
	/**
	 * Set Tabs and tab content.
	 */
	protected function set_tabs() {
	}
	
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
				$this->list_table->prepare_items();
				$this->list_table->display();
				break;
		}
	}
	
	/**
	 * Render the taxonomy term edit form
	 *
	 * @return void
	 */
	protected function render_form() {
		$conditions = SXP_Rules_Group_Action::get_instance()->get_conditions();
		$operators  = SXP_Rules_Group_Action::get_instance()->get_operators();
		$rules      = sxp_get_term_rules( $this->term );
		if ( empty( $rules ) ) {
			$rules[] = [
				'operator'  => '',
				'condition' => '',
				'values'    => [],
			];
		}
		$action = ( ! $this->term->term_id ? 'add-' : 'edit-' ) . $this->taxonomy_name;
		?>
		<div class="sxp-rule-wrapper">
			<form action="<?php echo esc_url( admin_url( 'admin.php?page=' . $this->page_slug ) ); ?>" method="post" class="sxp-form">
				<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>">
				<input type="hidden" name="term_id" value="<?php echo absint( $this->term->term_id ); ?>">
				<?php wp_nonce_field( $action ); ?>
				<div class="form-top">
					<div class="section">
						<h4 class="header"><?php printf(
								/* translators: 1 Taxonomy Singular Name */
								esc_html__( 'Customer %s Name', 'salexpresso' ),
								esc_html( $this->taxonomy->labels->singular_name )
							); ?> <i data-feather="info"></i></h4><!-- /.header -->
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
								foreach ( $rules as $rule ) {
									if ( ! isset( $rule['condition'], $rule['operator'], $rule['values'] ) ) {
										continue;
									}
									?>
								<div class="sxp-rule-single rule_<?php echo esc_attr( $idx ); ?>">
									<label for="rule_compare_<?php echo esc_attr( $idx ); ?>" class="screen-reader-text"><?php esc_html_e('Select Condition To Check', 'salexpresso'); ?> </label>
									<select id="rule_compare_<?php echo esc_attr( $idx ); ?>" name="sxp_rule[<?php echo esc_attr( $idx ); ?>][condition]">
										<option value=""><?php esc_html_e( 'Select Condition', 'salexpresso' ); ?></option>
										<?php
										foreach ( $conditions as $slug => $data ) {
											printf( '<option value="%s"%s>%s</option>', esc_attr( $slug ), selected( $rule['condition'], $slug, false ), esc_html( $data['label'] ) );
										}
										?>
									</select>
									<label for="rule_operator_<?php echo esc_attr( $idx ); ?>" class="screen-reader-text"><?php __('Select Comparison Operator', 'salexpresso'); ?> </label>
									<select id="rule_operator_<?php echo esc_attr( $idx ); ?>" name="sxp_rule[<?php echo esc_attr( $idx ); ?>][operator]">
										<option value=""><?php esc_html_e( 'Select Operator', 'salexpresso' ); ?></option>
										<?php
										foreach ( $operators as $slug => $data ) {
											printf( '<option value="%s"%s>%s</option>', esc_attr( $slug ), selected( $rule['operator'], $slug, false ), esc_html( $data['label'] ) );
										}
										?>
									</select>
									<label for="rule_values_<?php echo esc_attr( $idx ); ?>" class="screen-reader-text"><?php esc_html_e( 'Value to compare', 'salexpresso' ); ?></label>
									<input type="text" id="rule_values_<?php echo esc_attr( $idx ); ?>" name="sxp_rule[<?php echo esc_attr( $idx ); ?>][values]" placeholder="<?php esc_attr_e( 'Value to compare', 'salexpresso' ); ?>" value="<?php
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
								( function( $ ) {
									var template = $( '.rule_ui_template' ).text(),
										wrapper = $( '.sxp-rules' ),
										length = wrapper.find( '.sxp-rule-single' ).length || 1
									if ( '' !== template ) {
										template.trim()
									}
									if ( '' === template ) {
										return
									}
									$( document ).on( 'click', '.sxp-rule-add-btn .sxp-btn-link', function( e ) {
										e.preventDefault()
										length += 1
										wrapper.append( template.replace( /__IDX__/g, length ) )
									} )
								} )( jQuery )
							</script>
						</div><!-- end .sxp-customer-rule-add-btn -->
					</div><!-- end .sxp-customer-rule-bottom -->
					<div class="save-wrapper">
						<a class="sxp-btn sxp-btn-cancel" href="#" onclick="return confirm( '<?php esc_attr_e( 'Are You Sure?\nAny changes you made will be lost.', 'salexpresso' ); ?>' ) ? history.back() : false;">Cancel</a>
						<input type="submit" class="sxp-btn sxp-btn-primary btn-save" value="<?php
						printf(
							/* translators: 1 Term name this just created or get updated. */
							$this->term->term_id ? esc_attr__( 'Update %s', 'salexpresso' ) : esc_attr__( 'Save New %s', 'salexpresso' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							esc_attr( $this->taxonomy->labels->singular_name )
						); ?>">
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
