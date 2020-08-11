<?php
/**
 * List Table
 *
 * @package SaleXpresso
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_List_Table
 * 
 * @see \WP_List_Table
 */
class SXP_List_Table {
	
	/**
	 * The current list of items.
	 *
	 * @var array
	 */
	public $items;
	
	/**
	 * Various information about the current table.
	 *
	 * @var array
	 */
	protected $_args; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore
	
	/**
	 * Various information needed for displaying the pagination.
	 *
	 * @var array
	 */
	protected $_pagination_args = array(); // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore
	
	/**
	 * The current screen.
	 *
	 * @var object
	 */
	protected $screen;
	
	/**
	 * The current screen + tab id.
	 *
	 * @var string
	 */
	protected $screen_id;
	
	/**
	 * Current Page URL
	 *
	 * @var string
	 */
	protected $current_url;
	
	/**
	 * Cached bulk actions.
	 *
	 * @var array
	 */
	private $_actions; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore
	
	/**
	 * List of table action button.
	 *
	 * @var [][]
	 */
	private $table_actions;
	
	/**
	 * Cached pagination output.
	 *
	 * @var string
	 */
	private $_pagination; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore
	
	/**
	 * The view switcher modes.
	 *
	 * @var array
	 */
	protected $modes = array();
	
	/**
	 * Stores the value returned by ->get_column_info().
	 *
	 * @var array
	 */
	protected $_column_headers; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore
	
	/**
	 * {@internal Missing Summary}
	 *
	 * @var array
	 */
	protected $compat_fields = array( '_args', '_pagination_args', 'screen', '_actions', '_pagination' );
	
	/**
	 * {@internal Missing Summary}
	 *
	 * @var array
	 */
	protected $compat_methods = array(
		'set_pagination_args',
		'get_views',
		'get_bulk_actions',
		'bulk_actions',
		'row_actions',
		'months_dropdown',
		'view_switcher',
		'comments_bubble',
		'get_items_per_page',
		'pagination',
		'get_sortable_columns',
		'get_column_info',
		'get_table_classes',
		'display_tablenav',
		'extra_tablenav',
		'single_row_columns',
	);
	
	/**
	 * Unique String for common ID
	 *
	 * @var string
	 */
	protected $uid = '';
	
	/**
	 * Constructor.
	 *
	 * The child class should call this constructor from its own constructor to override
	 * the default $args.
	 *
	 * @param array|string $args {
	 *     Array or string of arguments.
	 *
	 *     @type string $plural   Plural value used for labels and the objects being listed.
	 *                            This affects things such as CSS class-names and nonces used
	 *                            in the list table, e.g. 'posts'. Default empty.
	 *     @type string $singular Singular label for an object being listed, e.g. 'post'.
	 *                            Default empty
	 *     @type bool   $ajax     Whether the list table supports Ajax. This includes loading
	 *                            and sorting data, for example. If true, the class will call
	 *                            the _js_vars() method in the footer to provide variables
	 *                            to any scripts handling Ajax events. Default false.
	 *     @type string $screen   String containing the hook name used to determine the current
	 *                            screen. If left null, the current screen will be automatically set.
	 *                            Default null.
	 *     @type string $tab      String containing the tab slug, use to determine the current tab
	 *                            of the screen. If left null, the current screen will be
	 *                            automatically set.
	 *                            Default null.
	 * }
	 */
	public function __construct( $args = array() ) {
		$args         = wp_parse_args(
			$args,
			[
				'plural'    => '',
				'singular'  => '',
				'ajax'      => false,
				'screen'    => null,
				'tab'       => null,
				'thead'     => true,
				'tfoot'     => true,
				'table_top' => true,
				'table_pagination' => true,
				'table_bottom' => false,
			]
		);
		$this->screen = convert_to_screen( $args['screen'] );
		
		if ( is_null( $this->screen_id ) ) {
			$this->screen_id = $this->screen->id;
		}
		
		$this->uid = wp_unique_id( $this->screen_id . '_list_table_' );
		
		$removable_query_args   = wp_removable_query_args();
		$removable_query_args[] = 'paged';
		$current_url            = '';
		if ( isset( $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'] ) ) {
			$current_url = set_url_scheme( 'http://' . sanitize_text_field( $_SERVER['HTTP_HOST'] ) . sanitize_text_field( $_SERVER['REQUEST_URI'] ) );
		}
		$this->current_url = remove_query_arg( $removable_query_args, $current_url );
		
		add_filter( "manage_{$this->screen_id}_columns", [ $this, 'get_columns' ], 0 );
		
		if ( ! $args['plural'] ) {
			$args['plural'] = $this->screen->base;
		}
		
		$args['plural']   = sanitize_key( $args['plural'] );
		$args['singular'] = sanitize_key( $args['singular'] );
		
		$this->_args = $args;
		
		if ( $args['ajax'] ) {
			// may be we need to use wp enqueue script [ list-table ].
			add_action( 'admin_footer', [ $this, '_js_vars' ] );
		}
		
		if ( empty( $this->modes ) ) {
			$this->modes = array(
				'list'    => esc_html__( 'List View', 'salexpresso' ),
				'excerpt' => esc_html__( 'Excerpt View', 'salexpresso' ),
			);
		}
	}
	
	/**
	 * Make private properties readable for backward compatibility.
	 *
	 * @param string $name Property to get.
	 * @return mixed Property.
	 */
	public function __get( $name ) {
		if ( in_array( $name, $this->compat_fields ) ) {
			return $this->$name;
		}
	}
	
	/**
	 * Make private properties settable for backward compatibility.
	 *
	 * @param string $name  Property to check if set.
	 * @param mixed  $value Property value.
	 * @return mixed Newly-set property.
	 */
	public function __set( $name, $value ) {
		if ( in_array( $name, $this->compat_fields ) ) {
			return $this->$name = $value;
		}
	}
	
	/**
	 * Make private properties checkable for backward compatibility.
	 *
	 * @param string $name Property to check if set.
	 * @return bool Whether the property is set.
	 */
	public function __isset( $name ) {
		if ( in_array( $name, $this->compat_fields ) ) {
			return isset( $this->$name );
		}
	}
	
	/**
	 * Make private properties un-settable for backward compatibility.
	 *
	 * @param string $name Property to unset.
	 */
	public function __unset( $name ) {
		if ( in_array( $name, $this->compat_fields ) ) {
			unset( $this->$name );
		}
	}
	
	/**
	 * Make private/protected methods readable for backward compatibility.
	 *
	 * @param string $name      Method to call.
	 * @param array  $arguments Arguments to pass when calling.
	 * @return mixed|bool Return value of the callback, false otherwise.
	 */
	public function __call( $name, $arguments ) {
		if ( in_array( $name, $this->compat_methods ) ) {
			return $this->$name( ...$arguments );
		}
		return false;
	}
	
	/**
	 * Checks the current user's permissions
	 *
	 * @abstract
	 */
	public function ajax_user_can() {
		die( 'function SaleXpresso\SXP_List_Table::ajax_user_can() must be overridden in a subclass.' );
	}
	
	/**
	 * Prepares the list of items for displaying.
	 *
	 * @uses WP_List_Table::set_pagination_args()
	 *
	 * @abstract
	 */
	public function prepare_items() {
		die( 'function SaleXpresso\SXP_List_Table::prepare_items() must be overridden in a subclass.' );
	}
	
	/**
	 * An internal method that sets all the necessary pagination arguments
	 *
	 * @param array|string $args Array or string of arguments with information about the pagination.
	 */
	protected function set_pagination_args( $args ) {
		$args = wp_parse_args(
			$args,
			array(
				'total_items' => 0,
				'total_pages' => 0,
				'per_page'    => 0,
			)
		);
		
		if ( ! $args['total_pages'] && $args['per_page'] > 0 ) {
			$args['total_pages'] = ceil( $args['total_items'] / $args['per_page'] );
		}
		
		// Redirect if page number is invalid and headers are not already sent.
		if ( ! headers_sent() && ! wp_doing_ajax() && $args['total_pages'] > 0 && $this->get_pagenum() > $args['total_pages'] ) {
			wp_safe_redirect( add_query_arg( 'paged', $args['total_pages'] ) );
			exit;
		}
		
		$this->_pagination_args = $args;
	}
	
	/**
	 * Access the pagination args.
	 *
	 * @param string $key Pagination argument to retrieve. Common values include 'total_items',
	 *                    'total_pages', 'per_page', or 'infinite_scroll'.
	 * @return int Number of items that correspond to the given pagination argument.
	 */
	public function get_pagination_arg( $key ) {
		if ( 'page' === $key ) {
			return $this->get_pagenum();
		}
		
		if ( isset( $this->_pagination_args[ $key ] ) ) {
			return $this->_pagination_args[ $key ];
		}
	}
	
	/**
	 * Whether the table has items to display or not
	 *
	 * @return bool
	 */
	public function has_items() {
		return ! empty( $this->items );
	}
	
	/**
	 * Message to be displayed when there are no items
	 */
	public function no_items() {
		esc_html_e( 'No items found.', 'salexpresso' );
	}
	
	/**
	 * Displays the search box.
	 *
	 * @param string $text     The 'submit' button label.
	 * @param string $input_id ID attribute value for the search input field.
	 */
	public function search_box( $text = '', $input_id = '' ) {
		if ( empty( $_REQUEST['q'] ) && ! $this->has_items() ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		
		$input_id = $input_id . '-search-input';
		
		if ( ! empty( $_REQUEST['orderby'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			echo '<input type="hidden" name="orderby" value="' . esc_attr( sanitize_text_field( $_REQUEST['orderby'] ) ) . '" />'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		if ( ! empty( $_REQUEST['order'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			echo '<input type="hidden" name="order" value="' . esc_attr( sanitize_text_field( $_REQUEST['order'] ) ) . '" />'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		if ( ! empty( $_REQUEST['post_mime_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( sanitize_text_field( $_REQUEST['post_mime_type'] ) ) . '" />'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		if ( ! empty( $_REQUEST['detached'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			echo '<input type="hidden" name="detached" value="' . esc_attr( sanitize_text_field( $_REQUEST['detached'] ) ) . '" />'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		?>
		<div class="sxp-customer-search">
			<label for="sxp-admin-search" class="screen-reader-text"><?php esc_html_e('Search Customer', 'salexpresso'); ?></label>
			<input type="search" id="sxp-admin-search" name="s" value="<?php _admin_search_query(); ?>" placeholder="<?php esc_html_e( 'Search', 'salexpresso' ); ?>">
		</div><!-- end .sxp-customer-search -->
		<?php
	}
	
	/**
	 * Get an associative array ( id => link ) with the list
	 * of views available on this table.
	 *
	 * @return array
	 */
	protected function get_views() {
		return array();
	}
	
	/**
	 * Display the list of views available on this table.
	 */
	public function views() {
		$views = $this->get_views();
		/**
		 * Filters the list of available list table views.
		 *
		 * The dynamic portion of the hook name, `$this->screen->id`, refers
		 * to the ID of the current screen, usually a string.
		 *
		 * @param string[] $views An array of available list table views.
		 */
		$views = apply_filters( "views_{$this->screen_id}", $views );
		
		if ( empty( $views ) ) {
			return;
		}
		
		$this->screen->render_screen_reader_content( 'heading_views' );
		
		echo "<ul class='subsubsub'>\n";
		foreach ( $views as $class => $view ) {
			$views[ $class ] = "\t<li class='$class'>$view";
		}
		echo implode( " |</li>\n", $views ) . "</li>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	
	/**
	 * Get an associative array ( option_name => option_title ) with the list
	 * of bulk actions available on this table.
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {
		return array();
	}
	
	/**
	 * Display the bulk actions dropdown.
	 */
	protected function bulk_actions() {
		if ( is_null( $this->_actions ) ) {
			$this->_actions = $this->get_bulk_actions();
			/**
			 * Filters the list table Bulk Actions drop-down.
			 *
			 * The dynamic portion of the hook name, `$this->screen->id`, refers
			 * to the ID of the current screen, usually a string.
			 *
			 * This filter can currently only be used to remove bulk actions.
			 *
			 * @param string[] $actions An array of the available bulk actions.
			 */
			$this->_actions = apply_filters( "bulk_actions-{$this->screen_id}", $this->_actions ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		}
		
		if ( empty( $this->_actions ) ) {
			return;
		}
		$nonce_action = get_class( $this );
		?>
		<div class="sxp-bulk-actions">
			<?php
			foreach ( $this->_actions as $action => $label ) {
				list( $label, $icon ) = (array) $label;
				$class                = 'sxp-list-table-action';
				if ( empty( $icon ) ) {
					$class .= ' no-icon';
				}
				
				printf(
					'<a href="#" class="%s" data-action="%s" data-nonce="%s"><i data-feather="%s" aria-hidden="true"></i> %s <span class="screen-reader-text">%s</span></a>',
					esc_attr( $class ),
					esc_attr( $action ),
					wp_create_nonce( $nonce_action . '_' . $action ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					esc_attr( $icon ),
					esc_html( $label ),
					esc_html_x( 'Selected', 'Bulk Action Label Screen Reader', 'salexpresso' )
				);
			}
			?>
		</div>
		<?php
	}
	
	/**
	 * Get the current action selected from the bulk actions dropdown.
	 *
	 * @return string|false The action name or False if no action was selected
	 */
	public function current_action() {
		// Actual action is assigned for the ajax action itself.
		if ( isset( $_REQUEST['_action'] ) && ! empty( $_REQUEST['_action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return sanitize_text_field( $_REQUEST['_action'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		
		return false;
	}
	
	/**
	 * Generates the required HTML for a list of row action links.
	 *
	 * @param string[] $actions        An array of action links.
	 * @param bool     $always_visible Whether the actions should be always visible.
	 * @return string The HTML for the row actions.
	 */
	protected function row_actions( $actions, $always_visible = false ) {
		$action_count = count( $actions );
		$i            = 0;
		
		if ( ! $action_count ) {
			return '';
		}
		
		$out = '<div class="' . ( $always_visible ? 'row-actions visible' : 'row-actions' ) . '">';
		foreach ( $actions as $action => $link ) {
			++$i;
			$sep  = ( $i == $action_count ) ? '' : $sep = ' | ';
			$out .= "<span class='$action'>$link$sep</span>";
		}
		$out .= '</div>';
		
		$out .= '<button type="button" class="toggle-row"><span class="screen-reader-text">' . __( 'Show more details', 'salexpresso' ) . '</span></button>';
		
		return $out;
	}
	
	/**
	 * Displays a dropdown for filtering items in the list table by month.
	 *
	 * @global \wpdb      $wpdb      WordPress database abstraction object.
	 * @global \WP_Locale $wp_locale WordPress date and time locale object.
	 *
	 * @param string $post_type The post type.
	 */
	protected function months_dropdown( $post_type ) {
		global $wpdb, $wp_locale;
		
		/**
		 * Filters whether to remove the 'Months' drop-down from the post list table.
		 *
		 * @param bool   $disable   Whether to disable the drop-down. Default false.
		 * @param string $post_type The post type.
		 */
		if ( apply_filters( 'disable_months_dropdown', false, $post_type ) ) {
			return;
		}
		
		$extra_checks = "AND post_status != 'auto-draft'";
		if ( ! isset( $_GET['post_status'] ) || 'trash' !== $_GET['post_status'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$extra_checks .= " AND post_status != 'trash'";
		} elseif ( isset( $_GET['post_status'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$extra_checks = $wpdb->prepare( ' AND post_status = %s', sanitize_text_field( $_GET['post_status'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery
		$months = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month FROM $wpdb->posts
					WHERE post_type = %s {$extra_checks}
					ORDER BY post_date DESC",
				$post_type
			)
		);
		// phpcs:enable
		
		/**
		 * Filters the 'Months' drop-down results.
		 *
		 * @param object[] $months    Array of the months drop-down query results.
		 * @param string   $post_type The post type.
		 */
		$months = apply_filters( 'months_dropdown_results', $months, $post_type );
		
		$month_count = count( $months );
		
		if ( ! $month_count || ( 1 == $month_count && 0 == $months[0]->month ) ) {
			return;
		}
		
		$m = isset( $_GET['m'] ) ? (int) $_GET['m'] : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		?>
		<label for="filter-by-date" class="screen-reader-text"><?php esc_html_e( 'Filter by date', 'salexpresso' ); ?></label>
		<select name="m" id="filter-by-date">
			<option<?php selected( $m, 0 ); ?> value="0"><?php esc_html_e( 'All dates', 'salexpresso' ); ?></option>
			<?php
			foreach ( $months as $arc_row ) {
				if ( 0 == $arc_row->year ) {
					continue;
				}
				
				$month = zeroise( $arc_row->month, 2 );
				$year  = $arc_row->year;
				
				printf(
					"<option %s value='%s'>%s</option>\n",
					selected( $m, $year . $month, false ),
					esc_attr( $arc_row->year . $month ),
					
					sprintf(
						/* translators: 1: Month name, 2: 4-digit year. */
						esc_html__( '%1$s %2$d', 'salexpresso' ),
						esc_html( $wp_locale->get_month( $month ) ),
						esc_html( $year )
					)
				);
			}
			?>
		</select>
		<?php
	}
	
	/**
	 * Display a view switcher
	 *
	 * @param string $current_mode Current view Mode the list table.
	 */
	protected function view_switcher( $current_mode ) {
		?>
		<input type="hidden" name="mode" value="<?php echo esc_attr( $current_mode ); ?>" />
		<div class="view-switch">
			<?php
			foreach ( $this->modes as $mode => $title ) {
				$classes      = array( 'view-' . $mode );
				$aria_current = '';
				
				if ( $current_mode === $mode ) {
					$classes[]    = 'current';
					$aria_current = ' aria-current="page"';
				}
				printf(
					"<a href='%s' class='%s' id='view-switch-%s'%s><span class='screen-reader-text'>%s</span></a>\n",
					esc_attr( $mode ),
					$aria_current, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					esc_url( add_query_arg( 'mode', $mode ) ),
					esc_attr( implode( ' ', $classes ) ),
					esc_html( $title )
				);
			}
			?>
		</div>
		<?php
	}
	
	/**
	 * Display a comment count bubble
	 *
	 * @param int $post_id          The post ID.
	 * @param int $pending_comments Number of pending comments.
	 */
	protected function comments_bubble( $post_id, $pending_comments ) {
		$approved_comments = get_comments_number();
		
		$approved_comments_number = number_format_i18n( $approved_comments );
		$pending_comments_number  = number_format_i18n( $pending_comments );
		
		$approved_only_phrase = sprintf(
		/* translators: %s: Number of comments. */
			_n( '%s comment', '%s comments', $approved_comments, 'salexpresso' ),
			$approved_comments_number
		);
		
		$approved_phrase = sprintf(
		/* translators: %s: Number of comments. */
			_n( '%s approved comment', '%s approved comments', $approved_comments, 'salexpresso' ),
			$approved_comments_number
		);
		
		$pending_phrase = sprintf(
		/* translators: %s: Number of comments. */
			_n( '%s pending comment', '%s pending comments', $pending_comments, 'salexpresso' ),
			$pending_comments_number
		);
		
		// No comments at all.
		if ( ! $approved_comments && ! $pending_comments ) {
			printf(
				'<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">%s</span>',
				esc_html__( 'No comments', 'salexpresso' )
			);
			// Approved comments have different display depending on some conditions.
		} elseif ( $approved_comments ) {
			printf(
				'<a href="%s" class="post-com-count post-com-count-approved"><span class="comment-count-approved" aria-hidden="true">%s</span><span class="screen-reader-text">%s</span></a>',
				esc_url(
					add_query_arg(
						array(
							'p'              => $post_id,
							'comment_status' => 'approved',
						),
						admin_url( 'edit-comments.php' )
					)
				),
				esc_html( $approved_comments_number ),
				$pending_comments ? esc_html( $approved_phrase ) : esc_html( $approved_only_phrase )
			);
		} else {
			printf(
				'<span class="post-com-count post-com-count-no-comments"><span class="comment-count comment-count-no-comments" aria-hidden="true">%s</span><span class="screen-reader-text">%s</span></span>',
				esc_html( $approved_comments_number ),
				$pending_comments ? esc_html__( 'No approved comments', 'salexpresso' ) : esc_html__( 'No comments', 'salexpresso' )
			);
		}
		
		if ( $pending_comments ) {
			printf(
				'<a href="%s" class="post-com-count post-com-count-pending"><span class="comment-count-pending" aria-hidden="true">%s</span><span class="screen-reader-text">%s</span></a>',
				esc_url(
					add_query_arg(
						[
							'p'              => $post_id,
							'comment_status' => 'moderated',
						],
						admin_url( 'edit-comments.php' )
					)
				),
				esc_html( $pending_comments_number ),
				esc_html( $pending_phrase)
			);
		} else {
			printf(
				'<span class="post-com-count post-com-count-pending post-com-count-no-pending"><span class="comment-count comment-count-no-pending" aria-hidden="true">%s</span><span class="screen-reader-text">%s</span></span>',
				esc_html( $pending_comments_number ),
				$approved_comments ? esc_html__( 'No pending comments', 'salexpresso' ) : esc_html__( 'No comments', 'salexpresso' )
			);
		}
	}
	
	/**
	 * Get the current page number
	 *
	 * @return int
	 */
	public function get_pagenum() {
		$pagenum = isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		
		if ( isset( $this->_pagination_args['total_pages'] ) && $pagenum > $this->_pagination_args['total_pages'] ) {
			$pagenum = $this->_pagination_args['total_pages'];
		}
		
		return max( 1, $pagenum );
	}
	
	/**
	 * Get number of items to display on a single page.
	 *
	 * @param string $option  Option name for item per page.
	 * @param int    $default Default value for items per page.
	 * @return int
	 */
	protected function get_items_per_page( $option, $default = 20 ) {
		$per_page = (int) get_user_option( $option );
		if ( empty( $per_page ) || $per_page < 1 ) {
			$per_page = $default;
		}
		
		/**
		 * Filters the number of items to be displayed on each page of the list table.
		 *
		 * The dynamic hook name, $option, refers to the `per_page` option depending
		 * on the type of list table in use. Possible values include: 'edit_comments_per_page',
		 * 'sites_network_per_page', 'site_themes_network_per_page', 'themes_network_per_page',
		 * 'users_network_per_page', 'edit_post_per_page', 'edit_page_per_page',
		 * 'edit_{$post_type}_per_page', etc.
		 *
		 * @param int $per_page Number of items to be displayed. Default 20.
		 */
		return (int) apply_filters( "{$option}", $per_page );
	}
	
	/**
	 * Display the pagination.
	 *
	 * @return void
	 */
	protected function pagination() {
		if ( empty( $this->_pagination_args ) ) {
			return;
		}
		
		$total_pages     = $this->_pagination_args['total_pages'];
		$infinite_scroll = false;
		if ( isset( $this->_pagination_args['infinite_scroll'] ) ) {
			$infinite_scroll = $this->_pagination_args['infinite_scroll'];
		}
		
		if ( $total_pages > 1 ) {
			$this->screen->render_screen_reader_content( 'heading_pagination' );
		}
		
		$pagination_links_class = 'pagination-links';
		if ( ! empty( $infinite_scroll ) ) {
			$pagination_links_class .= ' hide-if-js';
		}
		
		if ( $total_pages ) {
			$page_class = $total_pages < 2 ? ' one-page' : '';
		} else {
			$page_class = ' no-pages';
		}
		
		$links  = paginate_links( $this->get_pagination_args( [ 'total' => $total_pages ] ) );
		$output = '';
		if ( ! empty( $links ) ) {
			$output .= "\n<ul class='sxp-pagination {$pagination_links_class}'>\n\t<li>";
			$output .= join( "</li>\n\t<li>", $links );
			$output .= "</li>\n</ul>\n";
		}
		
		$this->_pagination = "<div class='sxp-pagination tablenav-pages{$page_class}'>$output</div>";
		
		echo $this->_pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	
	/**
	 * Get Pagination args for paginate links.
	 *
	 * @see paginate_links()
	 * @param string|array $args {
	 *     Optional. Array or string of arguments for generating paginated links for archives.
	 *
	 *     @type string $base               Base of the paginated url. Default empty.
	 *     @type string $format             Format for the pagination structure. Default empty.
	 *     @type int    $total              The total amount of pages. Default is the value WP_Query's
	 *                                      `max_num_pages` or 1.
	 *     @type int    $current            The current page number. Default is 'paged' query var or 1.
	 *     @type string $aria_current       The value for the aria-current attribute. Possible values are 'page',
	 *                                      'step', 'location', 'date', 'time', 'true', 'false'. Default is 'page'.
	 *     @type bool   $show_all           Whether to show all pages. Default false.
	 *     @type int    $end_size           How many numbers on either the start and the end list edges.
	 *                                      Default 1.
	 *     @type int    $mid_size           How many numbers to either side of the current pages. Default 2.
	 *     @type bool   $prev_next          Whether to include the previous and next links in the list. Default true.
	 *     @type bool   $prev_text          The previous page text. Default '&laquo; Previous'.
	 *     @type bool   $next_text          The next page text. Default 'Next &raquo;'.
	 *     @type string $type               Controls format of the returned value. Possible values are 'plain',
	 *                                      'array' and 'list'. Default is 'plain'.
	 *     @type array  $add_args           An array of query args to add. Default false.
	 *     @type string $add_fragment       A string to append to each link. Default empty.
	 *     @type string $before_page_number A string to appear before the page number. Default empty.
	 *     @type string $after_page_number  A string to append after the page number. Default empty.
	 * }
	 *
	 * @return array
	 */
	private function get_pagination_args( $args ) {
		$defaults = [
			'base'               => add_query_arg( [ 'paged' => '%#%' ], $this->current_url ), // http://example.com/all_posts.php%_% : %_% is replaced by format (below).
			'format'             => 'paged=%#%', // ?page=%#% : %#% is replaced by the page number.
			'total'              => count( $this->items ),
			'current'            => $this->get_pagenum(),
			'aria_current'       => 'page',
			'show_all'           => false,
			'prev_next'          => true,
			'prev_text'          => '<img alt="' . esc_attr__( '&laquo; Previous', 'salexpresso' ) . '" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTE0IDhMMyA4IiBzdHJva2U9IiM3RDdEQjMiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxwYXRoIGQ9Ik02IDEyTDIgOEw2IDQiIHN0cm9rZT0iIzdEN0RCMyIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+Cg=="/>',
			'next_text'          => '<img alt="' . esc_attr__( 'Next &raquo;', 'salexpresso' ) . '" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIgOEwxMyA4IiBzdHJva2U9IiM3RDdEQjMiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxwYXRoIGQ9Ik0xMCA0TDE0IDhMMTAgMTIiIHN0cm9rZT0iIzdEN0RCMyIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+Cg=="/>',
			'end_size'           => 4,
			'mid_size'           => 2,
			'type'               => 'array',
			'add_args'           => array(), // Array of query args to add.
			'add_fragment'       => '',
			'before_page_number' => '',
			'after_page_number'  => '',
		];
		return apply_filters( "manage_{$this->screen_id}_pagination_args", wp_parse_args( $args, $defaults ) );
	}
	
	/**
	 * Get a list of columns. The format is:
	 * 'internal-name' => 'Title'
	 *
	 * @abstract
	 */
	public function get_columns() {
		die( 'function SaleXpresso\SXP_List_Table::get_columns() must be overridden in a subclass.' );
	}
	
	/**
	 * Get a list of sortable columns. The format is:
	 * 'internal-name' => 'orderby'
	 * or
	 * 'internal-name' => array( 'orderby', true )
	 *
	 * The second format will make the initial sorting order be descending
	 *
	 * @return array
	 */
	protected function get_sortable_columns() {
		return array();
	}
	
	/**
	 * Gets the name of the default primary column.
	 *
	 * @return string Name of the default primary column, in this case, an empty string.
	 */
	protected function get_default_primary_column_name() {
		$columns = $this->get_columns();
		$column  = '';
		
		if ( empty( $columns ) ) {
			return $column;
		}
		
		// We need a primary defined so responsive views show something,
		// so let's fall back to the first non-checkbox column.
		foreach ( $columns as $col => $column_name ) {
			if ( 'cb' === $col ) {
				continue;
			}
			
			$column = $col;
			break;
		}
		
		return $column;
	}
	
	/**
	 * Public wrapper for WP_List_Table::get_default_primary_column_name().
	 *
	 * @return string Name of the default primary column.
	 */
	public function get_primary_column() {
		return $this->get_primary_column_name();
	}
	
	/**
	 * Gets the name of the primary column.
	 *
	 * @return string The name of the primary column.
	 */
	protected function get_primary_column_name() {
		$columns = get_column_headers( $this->screen );
		$default = $this->get_default_primary_column_name();
		
		// If the primary column doesn't exist,
		// fall back to the first non-checkbox column.
		if ( ! isset( $columns[ $default ] ) ) {
			$default = self::get_default_primary_column_name();
		}
		
		/**
		 * Filters the name of the primary column for the current list table.
		 *
		 * @param string $default Column name default for the specific list table, e.g. 'name'.
		 * @param string $context Screen ID for specific list table, e.g. 'plugins'.
		 */
		$column = apply_filters( 'list_table_primary_column', $default, $this->screen_id );
		
		if ( empty( $column ) || ! isset( $columns[ $column ] ) ) {
			$column = $default;
		}
		
		return $column;
	}
	
	/**
	 * Get a list of all, hidden and sortable columns, with filter applied
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
		
		$columns = get_column_headers( $this->screen );
		$hidden  = get_hidden_columns( $this->screen );
		
		$sortable_columns = $this->get_sortable_columns();
		/**
		 * Filters the list table sortable columns for a specific screen.
		 *
		 * The dynamic portion of the hook name, `$this->screen->id`, refers
		 * to the ID of the current screen, usually a string.
		 *
		 * @param array $sortable_columns An array of sortable columns.
		 */
		$_sortable = apply_filters( "manage_{$this->screen_id}_sortable_columns", $sortable_columns );
		
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
	 * Return number of visible columns
	 *
	 * @return int
	 */
	public function get_column_count() {
		list ( $columns, $hidden ) = $this->get_column_info();
		$hidden                    = array_intersect( array_keys( $columns ), array_filter( $hidden ) );
		return count( $columns ) - count( $hidden );
	}
	
	/**
	 * Print column headers, accounting for hidden and sortable columns.
	 *
	 * @staticvar int $cb_counter
	 *
	 * @param bool $with_id Whether to set the id attribute or not.
	 */
	public function print_column_headers( $with_id = true ) {
		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();
		
		if ( isset( $_GET['orderby'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$current_orderby = sanitize_text_field( $_GET['orderby'] );  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		} else {
			$current_orderby = '';
		}
		
		if ( isset( $_GET['order'] ) && 'desc' === $_GET['order'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$current_order = 'desc';
		} else {
			$current_order = 'asc';
		}
		
		if ( ! empty( $columns['cb'] ) ) {
			static $cb_counter = 1;
			$columns['cb']     = '<label class="screen-reader-text" for="cb-select-all-' . $cb_counter . '">' . esc_html__( 'Select All', 'salexpresso' ) . '</label><input id="cb-select-all-' . esc_attr( $cb_counter ) . '" type="checkbox" />';
			$cb_counter ++;
		}
		
		foreach ( $columns as $column_key => $column_display_name ) {
			$class = array( 'manage-column', "column-$column_key" );
			
			if ( in_array( $column_key, $hidden ) ) {
				$class[] = 'hidden';
			}
			
			if ( 'cb' === $column_key ) {
				$class[] = 'check-column';
			} elseif ( in_array( $column_key, array( 'posts', 'comments', 'links' ) ) ) {
				$class[] = 'num';
			}
			
			if ( $column_key === $primary ) {
				$class[] = 'column-primary';
			}
			
			if ( isset( $sortable[ $column_key ] ) ) {
				list( $orderby, $desc_first ) = $sortable[ $column_key ];
				
				if ( $current_orderby === $orderby ) {
					$order   = 'asc' === $current_order ? 'desc' : 'asc';
					$class[] = 'sorted';
					$class[] = $current_order;
				} else {
					$order   = $desc_first ? 'desc' : 'asc';
					$class[] = 'sortable';
					$class[] = $desc_first ? 'asc' : 'desc';
				}
				
				$column_display_name = '<a href="' . esc_url( add_query_arg( compact( 'orderby', 'order' ), $this->current_url ) ) . '"><span>' . $column_display_name . '</span></a>';
			}
			
			$tag   = ( 'cb' === $column_key ) ? 'td' : 'th';
			$scope = ( 'th' === $tag ) ? 'scope="col"' : '';
			$id    = $with_id ? "id='$column_key'" : '';
			
			if ( ! empty( $class ) ) {
				$class = "class='" . join( ' ', $class ) . "'";
			}
			
			echo "<$tag $scope $id $class>$column_display_name</$tag>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
	
	/**
	 * Displays the table.
	 */
	public function display() {
		global $plugin_page;
		$singular = $this->_args['singular'];
		$this->screen->render_screen_reader_content( 'heading_list' );
		?>
		<form action="<?php echo esc_url( $this->current_url ); ?>" method="get">
			<input type="hidden" name="page" value="<?php echo esc_attr( $plugin_page ); ?>">
			<div class="sxp-list-table">
				<?php
				if ( $this->_args['table_top'] ) {
					$this->display_tablenav( 'top' );
				}
				?>
				<div class="clearfix"></div>
				<table id="<?php echo esc_attr( $this->uid ); ?>" class="wp-list-table <?php echo esc_attr( implode( ' ', $this->get_table_classes() ) ); ?>">
					<?php if ( $this->_args['thead'] ) { ?>
						<thead>
						<tr>
							<?php $this->print_column_headers(); ?>
						</tr>
						</thead>
					<?php } ?>
					<tbody id="the-list"<?php echo $singular ? " data-wp-lists='list:" . esc_attr( $singular ) . "'" : ''; ?>>
					<?php $this->display_rows_or_placeholder(); ?>
					</tbody>
					<?php if ( $this->_args['tfoot'] ) { ?>
						<tfoot>
						<tr>
							<?php $this->print_column_headers( false ); ?>
						</tr>
						</tfoot>
					<?php } ?>
				</table>
				<div class="clearfix"></div>
				<?php
				if ( $this->_args['table_pagination'] ) {
					$this->display_tablenav( 'pagination' );
				}
				if ( $this->_args['table_bottom'] ) {
					$this->display_tablenav( 'bottom' );
				}
				?>
			</div>
		</form>
		<?php
	}
	
	/**
	 * Get a list of CSS classes for the WP_List_Table table tag.
	 *
	 * @return string[] Array of CSS classes for the table tag.
	 */
	protected function get_table_classes() {
		return [ 'sxp-table', 'widefat', $this->_args['plural'], $this->_args['tab'], $this->screen_id ];
	}
	
	/**
	 * Generate the table navigation above or below the table
	 *
	 * @param string $which Table top or bottom position.
	 */
	protected function display_tablenav( $which ) {
		if ( 'top' === $which ) {
			?>
			<div class="sxp-list-table-top">
				<?php $this->search_box( 'Search', 'search' ); ?>
				<div class="sxp-customer-btn-wrapper">
					<?php $this->table_actions(); ?>
				</div>
				<?php $this->extra_tablenav( $which ); ?>
			</div><!-- end .sxp-customer-top-wrapper -->
			<div class="clearfix"></div>
			<!-- /.clearfix -->
			<?php
		}
		if ( 'pagination' === $which ) {
			?>
			<div class="sxp-pagination-wrapper">
				<?php $this->pagination(); ?>
			</div><!-- end .sxp-pagination-wrapper -->
			<?php
		}
		if ( 'bottom' === $which ) {
			if ( $this->has_items() ) {
				?>
				<div class="sxp-bulk-action-container hidden">
					<div class="sxp-row-select">
						<a href="#" class="sxp-remove-selected" aria-label="<?php esc_attr_e( 'Remove selection', 'salexpresso' ); ?>">
							<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzYiIGhlaWdodD0iMzYiIHZpZXdCb3g9IjAgMCAzNiAzNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIyIDE0TDE0IDIyIiBzdHJva2U9IiM3RDdEQjMiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxwYXRoIGQ9Ik0xNCAxNEwyMiAyMiIgc3Ryb2tlPSIjN0Q3REIzIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K" alt="<?php esc_attr_e( 'Remove selection', 'salexpresso' ); ?>" aria-hidden="true">
						</a>
						<span class="sxp-selected"><span>0</span> <?php esc_html_e( 'Rows Selected', 'salexpresso' ); ?></span>
					</div>
					<?php $this->bulk_actions(); ?>
				</div><!-- end .sxp-bulk-action-container -->
				<script>
					( function ( $ ) {
						let config = {
							errors : {
								server_500: 'Server Error Contact Your System Admin',
								server_404: 'Resource not found',
								server_403: 'You doesn\'t have the permission to perform this request.',
								server_0: 'Client Error', // Unknown.
							},
						};
						function sxpListTable() {
							const wrapperClass = '.sxp-list-table';
							let balkWrapper = '.sxp-bulk-action-container';
							const actionsSelector = ' .sxp-list-table-action';
							const selectedCount = $( balkWrapper + ' .sxp-selected span' );
							balkWrapper = $( balkWrapper );
							const manageCheckBoxSelector = 'thead .column-cb input[type="checkbox"]';
							const manageCbPartialChecked = 'partial-checked';
							const checkedSelector = ' tbody .check-column input[type="checkbox"]:checked';
							let checkedBoxes;
							// handle back button.
							$(wrapperClass).each( function () {
								const self = $( this );
								const checkboxes = self.find( '.check-column input[type="checkbox"]' );
								checkboxes.on( 'change', function() {
									checkedBoxes = self.find( checkedSelector );
									const checked = checkedBoxes.length;
									const manageCb = self.find( manageCheckBoxSelector );
									selectedCount.text( checked );
									if ( checked ) {
										if ( ( checked + 1 ) !== checkboxes.length ) {
											manageCb.addClass( manageCbPartialChecked );
										} else {
											manageCb.removeClass( manageCbPartialChecked );
										}
										if ( ! balkWrapper.is( ':visible' ) ) {
											balkWrapper.show()
										}
									} else {
										manageCb.removeClass( manageCbPartialChecked );
										if ( balkWrapper.is( ':visible' ) ) {
											balkWrapper.hide()
										}
									}
								} ).trigger( 'change' );
								self.find( '.sxp-remove-selected' ).on( 'click', function ( event ) {
									event.preventDefault();
									//checkboxes.removeAttr( 'checked' );
									checkboxes.prop( 'checked', false );
									selectedCount.text( 0 );
									balkWrapper.hide();
									checkedBoxes = undefined;
								} );
								const getValues = el => {
									let selected = [];
									return el && el.each( function () {
										selected.push( $( this ).val() );
									} ), selected;
								};
								self.find( actionsSelector ).on( 'click', function ( event ) {
									event.preventDefault();
									const el = $(this),
										action = el.data( 'action' ),
										_wpnonce = el.data( 'nonce' );
									if ( event ) {
										let selected = getValues( checkedBoxes );
										wp.ajax
											.post( 'sxp_list_table', { _action: action, _wpnonce, selected, list_args } )
											.then( response => {
												if( response.hasOwnProperty( 'reload' ) ) {
													window.location = response.reload;
												}
												if( response.hasOwnProperty( 'message' ) ) {
													alert( response.message );
												}
											} )
											.fail( error => {
												if ( 'string' === typeof error ) {
													alert( error );
												} else {
													if ( error.hasOwnProperty( 'statusText' ) && error.hasOwnProperty( 'status' ) ) {
														if ( config.errors.hasOwnProperty( 'server_' + error.status )  ) {
															alert( config.errors[ 'server_' + error.status ] ) ;
														} else {
															alert( error.statusText );
														}
													}
												}
											} );
									}
								} );
							} );
						}
						sxpListTable();
					} )( jQuery );
				</script>
			<?php
			}
		}
	}
	
	/**
	 * Table Action Buttons.
	 *
	 * @return array
	 */
	protected function get_table_actions() {
		return [];
	}
	
	/**
	 * Render Table Action.
	 *
	 * @return void
	 */
	protected function table_actions() {
		$this->table_actions = $this->get_table_actions();
		if ( ! is_array( $this->table_actions ) || empty( $this->table_actions ) ) {
			return;
		}
		foreach ( $this->table_actions as $action ) {
			if ( ! isset( $action['link'], $action['icon'], $action['label'] ) ) {
				continue;
			}
			if ( ! isset( $action['type'] ) || ( isset( $action['type'] ) && empty( $action['type'] ) ) ) {
				$action['type'] = 'default';
			}
			printf(
				'<a href="%s" class="sxp-customer-type-btn sxp-btn sxp-btn-%s"><i data-feather="%s"></i> %s</a>',
				esc_url( $action['link'] ),
				esc_attr( $action['type'] ),
				esc_attr( $action['icon'] ),
				esc_html( $action['label'] )
			);
		}
	}
	
	/**
	 * Extra controls to be displayed between bulk actions and pagination
	 *
	 * @param string $which Table top or bottom position.
	 */
	protected function extra_tablenav( $which ) {
	}
	
	/**
	 * Generate the tbody element for the list table.
	 */
	public function display_rows_or_placeholder() {
		if ( $this->has_items() ) {
			$this->display_rows();
		} else {
			echo '<tr class="no-items"><td class="colspanchange" colspan="' . $this->get_column_count() . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$this->no_items();
			echo '</td></tr>';
		}
	}
	
	/**
	 * Generate the table rows.
	 *
	 * @return void
	 */
	public function display_rows() {
		foreach ( $this->items as $item ) {
			$this->single_row( $item );
		}
	}
	
	/**
	 * Generates content for a single row of the table.
	 *
	 * @param object|array $item The current item.
	 * @return void
	 */
	public function single_row( $item ) {
		echo '<tr>';
		$this->single_row_columns( $item );
		echo '</tr>';
	}
	
	/**
	 * Default Column Renderer Callback.
	 *
	 * @param object|array $item        Item to render.
	 * @param string       $column_name Column Name.
	 *
	 * @return string
	 */
	protected function column_default( $item, $column_name ) {
		return $this->column_default_filtered( $item, $column_name );
	}
	
	/**
	 * Filter Column Item Use in case of column output not handled.
	 * Helper method.
	 *
	 * @param object|array $item        Item to render.
	 * @param string       $column_name Column Name.
	 *
	 * @return string
	 */
	protected function column_default_filtered( $item, $column_name ) {
		return apply_filters( "manage_{$this->screen->id}_{$column_name}_column_content", '', $item );
	}
	
	/**
	 * CB Column Renderer.
	 *
	 * @param object|array $item Item to render.
	 *
	 * @return string
	 */
	protected function column_cb( $item ) {
		return '';
	}
	
	/**
	 * Generates the columns for a single row of the table
	 *
	 * @param object|array $item The current item.
	 * @return void
	 */
	protected function single_row_columns( $item ) {
		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();
		
		foreach ( $columns as $column_name => $column_display_name ) {
			$classes = "$column_name column-$column_name";
			if ( $primary === $column_name ) {
				$classes .= ' has-row-actions column-primary';
			}
			
			if ( in_array( $column_name, $hidden ) ) {
				$classes .= ' hidden';
			}
			
			// Comments column uses HTML in the display name with screen reader text.
			// Instead of using esc_attr(), we strip tags to get closer to a user-friendly string.
			$data = 'data-colname="' . wp_strip_all_tags( $column_display_name ) . '"';
			
			$attributes = "class='$classes' $data";
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			if ( 'cb' === $column_name ) {
				echo '<th scope="row" class="check-column">';
				echo $this->column_cb( $item );
				echo '</th>';
			} elseif ( method_exists( $this, '_column_' . $column_name ) ) {
				echo call_user_func( [ $this, '_column_' . $column_name ], $item, $classes, $data, $primary );
			} elseif ( method_exists( $this, 'column_' . $column_name ) ) {
				echo "<td $attributes>";
				echo call_user_func( [ $this, 'column_' . $column_name ], $item );
				echo $this->handle_row_actions( $item, $column_name, $primary );
				echo '</td>';
			} else {
				echo "<td $attributes>";
				echo $this->column_default( $item, $column_name );
				echo $this->handle_row_actions( $item, $column_name, $primary );
				echo '</td>';
			}
			// phpcs:enable
		}
	}
	
	/**
	 * Generates and display row actions links for the list table.
	 *
	 * @param object|array $item        The item being acted upon.
	 * @param string       $column_name Current column name.
	 * @param string       $primary     Primary column name.
	 *
	 * @return string The row actions HTML, or an empty string if the current column is not the primary column.
	 */
	protected function handle_row_actions( $item, $column_name, $primary ) {
		return $column_name === $primary ? '<button type="button" class="toggle-row"><span class="screen-reader-text">' . __( 'Show more details', 'salexpresso' ) . '</span></button>' : '';
	}
	
	/**
	 * Handle an incoming ajax request (called from admin-ajax.php)
	 */
	public function ajax_response() {
		
		$action = $this->current_action();
		$cb     = [ $this, 'ajax_response_' . str_replace( [ '-' ], '_', $action ) ];
		if ( is_callable( $cb ) ) {
			call_user_func( $cb );
		} else {
			/**
			 * Handle bulk action request if method not implemented.
			 * Developer needs to terminate execution using die() or exit()
			 * otherwise the response header status will bet set to 501 (Not Implemented).
			 *
			 * @param SXP_List_Table $list_class
			 */
			do_action( 'sxp_list_bulk_' . get_class( $this ) . '_' . $action, $this );
		}
		// No Response handler implemented.
		wp_die( -1, 501 );
	}
	
	/**
	 * List table ajax response handler.
	 *
	 * @return void
	 */
	protected function ajax_fetch_list() {
		$this->prepare_items();
		
		ob_start();
		if ( ! empty( $_REQUEST['no_placeholder'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$this->display_rows();
		} else {
			$this->display_rows_or_placeholder();
		}
		
		$response = [ 'rows' => ob_get_clean() ];
		
		if ( isset( $this->_pagination_args['total_items'] ) ) {
			$response['total_items_i18n'] = sprintf(
			/* translators: Number of items. */
				_n( '%s item', '%s items', $this->_pagination_args['total_items'], 'salexpresso' ),
				number_format_i18n( $this->_pagination_args['total_items'] )
			);
		}
		
		if ( isset( $this->_pagination_args['total_pages'] ) ) {
			$response['total_pages']      = $this->_pagination_args['total_pages'];
			$response['total_pages_i18n'] = number_format_i18n( $this->_pagination_args['total_pages'] );
		}
		
		wp_send_json_success( $response );
		die();
	}
	
	/**
	 * Send required variables to JavaScript land
	 *
	 * @return void
	 */
	public function _js_vars() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		$args = [
			'class'  => get_class( $this ),
			'screen' => [
				'id'   => $this->screen_id,
				'base' => $this->screen->base,
			],
		];
		
		printf( "<script type='text/javascript'>list_args = %s;</script>\n", wp_json_encode( $args ) );
	}
}

// End of file class-sxp-list-table.php.
