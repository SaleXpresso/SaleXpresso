<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\Abstracts;

use SaleXpresso\Interfaces\SXP_Admin_Page_Interface;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Admin_Page
 *
 * @package SaleXpresso
 */
abstract class SXP_Admin_Page implements SXP_Admin_Page_Interface {
	
	/**
	 * Page Slug.
	 * Default to current slug or global admin $page
	 *
	 * @var string
	 */
	protected $page_slug = '';
	
	/**
	 * Hook Slug
	 * Default to current page slug with prefix removed
	 * 
	 * @var string
	 */
	protected $hook_slug = '';
	
	/**
	 * Flag for showing page title action link
	 *
	 * @var bool
	 */
	protected $show_page_title_action = false;
	
	/**
	 * Add new button url
	 *
	 * @var string
	 */
	protected $add_new_url = '';
	
	/**
	 * Add new button label
	 *
	 * @var string
	 */
	protected $add_new_label = '';
	
	/**
	 * Page Tabs
	 *
	 * @var array
	 */
	protected $tabs = [];
	
	/**
	 * Enable JS Tab Plugin for this page.
	 * Some page loads lot of data, we can disable tab js on those page with this property
	 *
	 * @var bool
	 */
	protected $js_tabs = false;
	
	/**
	 * Current Tab
	 *
	 * @var string
	 */
	protected $current_tab = '';
	
	/**
	 * List of flash messages to show.
	 *
	 * @var array
	 */
	protected $flash_messages = [];
	
	/**
	 * SXP_Admin_Page constructor.
	 *
	 * @param string $plugin_page Plugin Page slug.
	 *
	 * @return void
	 */
	public function __construct( $plugin_page = null ) {
		$this->set_page_slug( $plugin_page );
		$this->set_tabs();
		$this->set_active_tab();
		$this->get_flash_messages();
		$this->add_new_url   = admin_url( 'admin.php?page=' . $this->page_slug );
		$this->add_new_label = __( 'Add New', 'salexpresso' );
		// admin init.
		add_action( 'admin_init', [ $this, 'actions' ] );
		// Save flash notices for showing on next page load.
		add_action( 'shutdown', [ $this, 'save_flash_message' ] );
	}
	
	/**
	 * Init Actions
	 *
	 * @hooked admin_init
	 * @return void
	 */
	public function actions() {
	}
	
	/**
	 * Set page slug for current page (class instance)
	 *
	 * @param string $plugin_page Plugin Page slug.
	 *
	 * @return void
	 */
	private function set_page_slug( $plugin_page = null ) {
		if ( is_string( $plugin_page ) && ! empty( $plugin_page ) ) {
			$this->page_slug = $plugin_page;
		} else {
			$__class         = str_replace( [ 'sxp_', '_page' ], [ 'sxp-', '' ], strtolower( __CLASS__ ) );
			$__class         = explode( '\\', $__class );
			$this->page_slug = end( $__class );
		}
		$this->hook_slug = str_replace( 'sxp-', '', $this->page_slug );
	}
	
	/**
	 * Get the page slug
	 *
	 * @return string
	 */
	public function get_page_slug() {
		return $this->page_slug;
	}
	
	/**
	 * Check if current page is active.
	 *
	 * @return bool
	 */
	public function is_active_page() {
		return ( ! isset( $_GET['page'] ) || ( isset( $_GET['page'] ) && $this->get_page_slug() !== $_GET['page'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}
	
	/**
	 * Set Tabs and tab content.
	 */
	protected function set_tabs() {
		/**
		 * Filters page tab list.
		 *
		 * @param array $tabs
		 */
		$this->tabs = apply_filters( "salexpresso_admin_{$this->hook_slug}_page_tabs", [] );
	}
	
	/**
	 * Set Current Tab
	 *
	 * @return void
	 */
	private function set_active_tab() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$tabs = array_keys( $this->tabs );
		if (
			$this->has_tabs() &&
			isset( $_GET['page'], $_GET['tab'] ) &&
			$this->page_slug === $_GET['page'] &&
			in_array( $_GET['tab'], $tabs )
		) {
			$this->current_tab = sanitize_text_field( $_GET['tab'] );
		} elseif ( is_array( $this->tabs ) ) {
			$this->current_tab = $tabs[0];
		}
		// phpcs:enable
	}
	
	/**
	 * Get Active Tab
	 *
	 * @return string
	 */
	public function get_active_tab() {
		return $this->current_tab;
	}
	
	/**
	 * Check if tab is active.
	 *
	 * @param string $tab Tab slug to check.
	 *
	 * @return bool
	 */
	public function is_active_tab( $tab ) {
		return $tab === $this->current_tab;
	}
	
	/**
	 * Get Page title
	 *
	 * @return void
	 */
	public function render_page_title() {
		$title = get_admin_page_title();
		?>
		<h1 class="wp-heading-inline"><?php echo wp_kses(
			/**
			 * Filters admin page title
			 *
			 * @param string $title
			 */
			apply_filters( "salexpresso_admin_{$this->hook_slug}_page_title", $title ),
			[
				'br'     => [],
				'em'     => [],
				'strong' => [],
				'span'   => [],
				'code'   => [],
			]
		); ?></h1>
		<?php
	}
	
	/**
	 * Render page title action (add new button)
	 */
	public function render_page_title_action() {
		if ( false === $this->show_page_title_action ) {
			return;
		}
		if ( empty( $this->add_new_url ) || empty( $this->add_new_label ) ) {
			return;
		}
		?><a href="<?php echo esc_url( $this->add_new_url ); ?>" class="page-title-action"><?php echo esc_html( $this->add_new_label ); ?></a><?php
	}
	
	/**
	 * Render the page header section.
	 *
	 * @return void
	 */
	protected function render_page_header() {
		$this->set_flash_message( 'test' );
		?>
		<div class="sxp-page-header">
			<?php
			do_action( "salexpresso_admin_after_{$this->hook_slug}_page_title", $this );
			$this->render_page_title();
			$this->render_page_filter();
			$this->render_page_title_action();
			do_action( "salexpresso_admin_after_{$this->hook_slug}_page_title", $this );
			if ( apply_filters( "salexpresso_admin_print_{$this->hook_slug}_page_wp_header_end", true ) ) {
				?><hr class="wp-header-end"><?php
			}
			$this->render_flash_messages();
			$this->render_page_tab_navs();
			?>
		</div>
		<?php
	}
	
	/**
	 * Check if current page has multiple tabs
	 * This will return false if the page contains only 1 tab.
	 *
	 * @return bool
	 */
	protected function has_tabs() {
		return ( is_array( $this->tabs ) && count( $this->tabs ) > 1 );
	}
	
	/**
	 * Format class names for class attribute;
	 *
	 * @param array           $classes Generated class names.
	 * @param string[]|string $class   User provide class name.
	 *
	 * @return array
	 */
	private function class_names( $classes = [], $class = '' ) {
		
		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		} else {
			// Ensure that we always coerce class to being an array.
			$class = array();
		}
		
		$classes = array_filter( $classes );
		$classes = array_unique( $classes );
		$classes = array_map( 'esc_attr', $classes );
		
		return [ $classes, $class ];
	}
	
	/**
	 * Generate page wrapper class for the page
	 *
	 * @param string[]|string $class Class names to add with the generated class names.
	 */
	private function wrapper_classes( $class = '' ) {
		$classes = [ 'sxp-wrapper', $this->page_slug ];
		if ( $this->has_tabs() ) {
			$classes[] = 'sxp-has-tabs';
		}
		
		$classes = $this->class_names( $classes, $class );
		/**
		 * Filters the list of CSS body class names for the current content wrapper
		 *
		 * @param string[] $classes An array of content wrapper class names.
		 * @param string[] $class   An array of additional class names added to the content wrapper.
		 */
		$classes = apply_filters( "salexpresso_admin_{$this->hook_slug}_page_class", $classes[0], $classes[1] );
		// Classes escaped, see self::class_names.
		echo 'class="' . implode( ' ', $classes ) . '"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	
	/**
	 * Generate content wrapper class for the page
	 *
	 * @param string[]|string $class Class names to add with the generated class names.
	 */
	protected function content_classes( $class = '' ) {
		$classes = $this->class_names( [ 'sxp-content-wrapper' ], $class );
		/**
		 * Filters the list of CSS body class names for the current content wrapper
		 *
		 * @param string[] $classes An array of content wrapper class names.
		 * @param string[] $class   An array of additional class names added to the content wrapper.
		 */
		$classes = apply_filters( "salexpresso_admin_{$this->hook_slug}_page_content_class", $classes[0], $classes[1] );
		// Classes escaped, see self::class_names.
		echo 'class="' . implode( ' ', $classes ) . '"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	
	/**
	 * Print HTML Content for the page
	 *
	 * @return void
	 */
	public function render() {
		?>
		<div <?php $this->wrapper_classes( 'wrap' ); ?>>
			<?php
			do_action( "salexpresso_admin_before_{$this->hook_slug}_page_header", $this );
			$this->render_page_header();
			do_action( "salexpresso_admin_after_{$this->hook_slug}_page_header", $this );
			do_action( "salexpresso_admin_before_{$this->hook_slug}_page_content_wrapper", $this );
			$this->render_page_content();
			do_action( "salexpresso_admin_after_{$this->hook_slug}_page_content_wrapper", $this );
			?>
		</div>
		<?php
	}

	/**
	 * Render Filter section
	 */
	protected function render_page_filter() {
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
			<div class="sxp-clearfix"></div>
		<?php
	}
	/**
	 * Render Page Content Area
	 */
	protected function render_page_content() {
		?>
		<div <?php $this->content_classes(); ?>>
			<?php
			if ( $this->has_tabs() ) {
				$this->render_page_tab_contents();
			} else {
				if ( is_array( $this->tabs ) ) {
					$keys = array_keys( $this->tabs );
					$this->exec_tab_cb( $this->tabs[ $keys[0] ]['content'], $keys[0] );
				}
			}
			?>
		</div>
		<?php
	}
	
	/**
	 * Render page navigation (tab header)
	 *
	 * @return void
	 */
	protected function render_page_tab_navs() {
		// Print tab header if tab is more then 1.
		?>
		<div class="sxp-header-nav">
			<?php do_action( "salexpresso_admin_before_{$this->hook_slug}_page_header_nav", $this ); ?>
			<?php if ( $this->has_tabs() ) { ?>
			<ul class="sxp-tabs-nav">
				<?php
				
				foreach ( $this->tabs as $tab_slug => $tab ) {
					$tab_slug = sanitize_title( $tab_slug );
					if ( ! isset( $tab['label'] ) || '' === $tab['label'] ) {
						// Continue to next tab if label is not set or empty.
						// This will allow creating hidden tabs.
						continue;
					}
					if ( isset( $tab['icon'] ) ) {
						if ( strpos( 'dashicons', $tab['icon'] ) ) {
							$tab['icon'] = 'dashicons dashicons' . sxp_str_replace_trim( [ 'dashicons' ], '', $tab['icon'] );
						}
					}
					
					$tab_url        = add_query_arg(
						[
							'page' => $this->page_slug,
							'tab'  => $tab_slug,
						],
						admin_url( 'admin.php' )
					);
					$tab_class      = 'tab-item tab-' . $tab_slug;
					$label_class    = '';
					$data_attribute = '';
					if ( isset( $tab['icon'] ) ) {
						$tab_class .= ' has-icon';
					}
					if ( isset( $tab['icon'], $tab['show_label'] ) && $tab['show_label'] ) {
						$label_class = 'screen-reader-text';
						$tab_class  .= ' no-label';
					}
					if ( $this->is_active_tab( $tab_slug ) ) {
						$tab_class .= ' is-active';
					}
					
					if ( $this->js_tabs ) {
						$data_attribute = ' data-target="sxp-' . esc_attr( $tab_slug ) . '-tab"';
					}
					?>
					<li class="<?php echo esc_attr( $tab_class ); ?>">
						<a class="tab-item-link" href="<?php echo esc_url( $tab_url ); ?>"<?php echo $data_attribute; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<?php if ( isset( $tab['icon'] ) ) { ?>
							<span class="<?php echo esc_attr( $tab['icon'] ); ?>" aria-hidden="true"></span>
							<?php } ?>
							<span class="<?php echo esc_attr( $label_class ); ?>"><?php echo esc_html( $tab['label'] ); ?></span>
						</a>
					</li>
				<?php } ?>
			</ul>
			<?php } ?>
			<?php do_action( "salexpresso_admin_after_{$this->hook_slug}_page_header_nav", $this ); ?>
		</div>
		<?php
	}
	
	/**
	 * Render page tab content
	 *
	 * @return void
	 */
	protected function render_page_tab_contents() {
		if ( $this->has_tabs() ) {
			?>
			<div class="sxp-tabs-content">
				<?php
				foreach ( ( $this->js_tabs ) ? $this->tabs : [ $this->current_tab => $this->tabs[ $this->current_tab ] ] as $tab_slug => $tab ) {
					$tab_slug = sanitize_title( $tab_slug );
					if ( ! isset( $tab['content'] ) ) {
						continue;
					}
					$tab_class = 'tab-content tab-content-' . $tab_slug;
					if ( $this->is_active_tab( $tab_slug ) ) {
						$tab_class .= ' is-active';
					}
					?>
					<div id="sxp-<?php echo esc_attr( $tab_slug ); ?>-tab" class="<?php echo esc_attr( $tab_class ); ?>"><?php $this->exec_tab_cb( $tab, $tab_slug ); ?></div>
					<?php
				}
				?>
			</div>
			<?php
		}
	}
	
	/**
	 * Execute tab content callback.
	 *
	 * @param array  $tab      Tab data.
	 * @param string $tab_slug Tab slug/id.
	 */
	protected function exec_tab_cb( $tab, $tab_slug ) {
		do_action( "salexpresso_admin_before_{$this->hook_slug}_page_content", $this );
		if ( is_callable( $tab['content'] ) ) {
			$cb = $tab['content'];
			unset( $tab['content'] );
			call_user_func( $cb, $tab_slug );
		} elseif ( has_action( "salexpresso_admin_{$this->hook_slug}_page_{$tab_slug}_tab_content" ) ) {
			if ( 0 === did_action( "salexpresso_admin_{$this->hook_slug}_page_{$tab_slug}_tab_content" ) ) {
				do_action( "salexpresso_admin_{$this->hook_slug}_page_{$tab_slug}_tab_content" );
			}
		} else {
			echo wp_kses_post( $tab['content'] );
		}
		do_action( "salexpresso_admin_after_{$this->hook_slug}_page_content", $this );
	}
	
	/**
	 * Sanitize Flash Message args.
	 *
	 * @param string         $message       Message content to show.
	 * @param string         $type          [optional] Message type.
	 *                                      Accepts 'notice', 'success', 'updated', 'error',
	 *                                      'warning', 'info'.
	 * @param bool           $dismissible   [optional] Enable dismiss button.
	 * @param bool           $alt           [optional] Render with the alternative style.
	 * @param array[]|string $allowed_html  An array of allowed HTML elements and attributes, or a
	 *                                      context name such as 'post'.
	 *
	 * @return array|bool
	 */
	protected function sanitize_notice_data( $message, $type = 'notice', $dismissible = true, $alt = false, $allowed_html = 'post' ) {
		
		if ( ! is_string( $message ) || empty( $message ) ) {
			return false;
		}
		$message = wp_kses( $message, $allowed_html );
		
		if ( 'updated' === $type ) {
			$type = 'success';
		}
		// Set Default type.
		if ( 'notice' === $type || ! in_array( $type, [ 'success', 'error', 'warning', 'info' ] ) ) {
			$type = '';
		}
		
		return [
			'alt'            => (bool) $alt,
			'type'           => $type,
			'content'        => wp_kses( $message, $allowed_html ),
			'is_dismissible' => (bool) $dismissible,
		];
	}
	
	/**
	 * Set Flash message for current page.
	 *
	 * @param string         $message       Message content to show.
	 * @param string         $type          [optional] Message type.
	 *                                      Accepts 'notice', 'success', 'updated', 'error',
	 *                                      'warning', 'info'.
	 * @param bool           $dismissible   [optional] Enable dismiss button.
	 * @param bool           $alt           [optional] Render with the alternative style.
	 * @param array[]|string $allowed_html  An array of allowed HTML elements and attributes, or a
	 *                                      context name such as 'post'.
	 *
	 * @return bool
	 */
	public function set_flash_message( $message, $type = 'notice', $dismissible = true, $alt = false, $allowed_html = 'post' ) {
		$notice = $this->sanitize_notice_data( $message, $type, $dismissible, $alt, $allowed_html );
		if ( $notice ) {
			$this->flash_messages[] = $notice;
			return true;
		}
		return false;
	}
	
	/**
	 * Get cache/transient key for flash message
	 *
	 * @return string
	 */
	private function get_flash_message_key() {
		return 'salexpresso_admin_' . $this->hook_slug . '_page_flash_message';
	}
	
	/**
	 * Save flash messages for showing after page load.
	 *
	 * @return void
	 */
	public function save_flash_message() {
		$this->flash_messages = array_filter( $this->flash_messages );
		if ( ! empty( $this->flash_messages ) ) {
			set_transient( $this->get_flash_message_key(), $this->flash_messages, 30 );
		}
	}
	
	/**
	 * Get saved flash messages.
	 *
	 * @return array
	 */
	protected function get_flash_messages() {
		if ( empty( $this->flash_messages ) ) {
			$this->flash_messages = get_transient( $this->get_flash_message_key() );
			if ( ! $this->flash_messages ) {
				$this->flash_messages = [];
			}
		}
		return $this->flash_messages;
	}
	
	/**
	 * Flush flash message cache, including saved messages
	 *
	 * @return void
	 */
	protected function flush_flash_messages() {
		$this->flash_messages = [];
		delete_transient( $this->get_flash_message_key() );
	}
	
	/**
	 * Render Flash messages
	 *
	 * @return void
	 */
	protected function render_flash_messages() {
		if ( is_array( $this->flash_messages ) && ! empty( $this->flash_messages ) ) {
			foreach ( $this->flash_messages as $message ) {
				$classes = [ 'notice' ];
				if ( in_array( $message['type'], [ 'notice', 'success', 'error', 'warning', 'info' ] ) ) {
					$classes[] = 'notice-' . $message['type'];
				}
				if ( $message['is_dismissible'] ) {
					$classes[] = 'is-dismissible';
				}
				if ( $message['alt'] ) {
					$classes[] = 'notice-alt';
				}
				?>
				<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
					<?php echo wpautop( $message['content'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php
			}
		}
		// Remove all flash message after rendering.
		$this->flush_flash_messages();
	}
}

// End of file class-sxp-admin-page.php.
