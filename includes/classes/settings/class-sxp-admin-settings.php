<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Settings
 * @version 1.0.0
 * @since   SaleXpresso v1.0.0
 */

namespace SaleXpresso\Settings;

use SaleXpresso\Abstracts\SXP_Settings_Tab;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Settings_Page
 *
 *
 * @see \WC_Admin_Settings
 *
 * @author SaleXpresso
 * @author WooCommerce
 */
class SXP_Admin_Settings {

	/**
	 * Setting pages.
	 *
	 * @var SXP_Settings_Tab[]
	 */
	private static $settings = array();

	/**
	 * Error messages.
	 *
	 * @var array
	 */
	private static $errors = array();

	/**
	 * Update messages.
	 *
	 * @var array
	 */
	private static $messages = array();

	/**
	 * Include the settings page classes.
	 */
	public static function get_settings_pages() {
		if ( empty( self::$settings ) ) {
			$settings = array();

			self::$settings = apply_filters( 'salexpresso_get_settings_pages', $settings );
		}

		return self::$settings;
	}

	/**
	 * Save the settings.
	 */
	public static function save() {
		global $current_tab;

		check_admin_referer( 'salexpresso-settings' );

		// Trigger actions.
		do_action( 'salexpresso_settings_save_' . $current_tab );
		do_action( 'salexpresso_update_options_' . $current_tab );
		do_action( 'salexpresso_update_options' );

		self::add_message( __( 'Your settings have been saved.', 'salexpresso' ) );
		
		// Clear any unwanted data and flush rules.
		update_option( 'woocommerce_queue_flush_rewrite_rules', 'yes' );
		
		do_action( 'salexpresso_settings_saved' );
	}

	/**
	 * Add a message.
	 *
	 * @param string $text Message.
	 */
	public static function add_message( $text ) {
		self::$messages[] = $text;
	}

	/**
	 * Add an error.
	 *
	 * @param string $text Message.
	 */
	public static function add_error( $text ) {
		self::$errors[] = $text;
	}

	/**
	 * Output messages + errors.
	 */
	public static function show_messages() {
		if ( count( self::$errors ) > 0 ) {
			foreach ( self::$errors as $error ) {
				echo '<div id="message" class="error inline"><p><strong>' . esc_html( $error ) . '</strong></p></div>';
			}
		} elseif ( count( self::$messages ) > 0 ) {
			foreach ( self::$messages as $message ) {
				echo '<div id="message" class="updated inline"><p><strong>' . esc_html( $message ) . '</strong></p></div>';
			}
		}
	}

	/**
	 * Settings page.
	 *
	 * Handles the display of the main woocommerce settings page in admin.
	 */
	public static function output() {
		global $current_section, $current_tab;

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.min' : '';

		do_action( 'salexpresso_settings_start' );

		wp_enqueue_script( 'woocommerce_settings', WC()->plugin_url() . '/assets/js/admin/settings' . $suffix . '.js', array( 'jquery', 'wp-util', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'iris', 'selectWoo' ), WC()->version, true );

		wp_localize_script(
			'woocommerce_settings',
			'woocommerce_settings_params',
			array(
				'i18n_nav_warning'                    => __( 'The changes you made will be lost if you navigate away from this page.', 'salexpresso' ),
				'i18n_moved_up'                       => __( 'Item moved up', 'salexpresso' ),
				'i18n_moved_down'                     => __( 'Item moved down', 'salexpresso' ),
				'i18n_no_specific_countries_selected' => __( 'Selecting no country / region to sell to prevents from completing the checkout. Continue anyway?', 'salexpresso' ),
			)
		);

		// Get tabs for the settings page.
		$tabs = apply_filters( 'salexpresso_settings_tabs_array', [] );

		include dirname( __FILE__ ) . '/views/html-admin-settings.php';
	}

	/**
	 * Get a setting from the settings API.
	 *
	 * @param string $option_name Option name.
	 * @param mixed  $default     Default value.
	 * @return mixed
	 */
	public static function get_option( $option_name, $default = '' ) {
		if ( ! $option_name ) {
			return $default;
		}

		// Array value.
		if ( strstr( $option_name, '[' ) ) {

			parse_str( $option_name, $option_array );

			// Option name is first key.
			$option_name = current( array_keys( $option_array ) );

			// Get value.
			$option_values = get_option( $option_name, '' );

			$key = key( $option_array[ $option_name ] );

			if ( isset( $option_values[ $key ] ) ) {
				$option_value = $option_values[ $key ];
			} else {
				$option_value = null;
			}
		} else {
			// Single value.
			$option_value = get_option( $option_name, null );
		}

		if ( is_array( $option_value ) ) {
			$option_value = wp_unslash( $option_value );
		} elseif ( ! is_null( $option_value ) ) {
			$option_value = stripslashes( $option_value );
		}

		return ( null === $option_value ) ? $default : $option_value;
	}

	/**
	 * Output admin fields.
	 *
	 * Loops though the woocommerce options array and outputs each field.
	 *
	 * @param array[] $options Opens array to output.
	 */
	public static function output_fields( $options ) {
		$value_defaults = [
			'id'          => '',
			'name'        => '',
			'class'       => '',
			'css'         => '',
			'default'     => '',
			'desc'        => '',
			'desc_tip'    => false,
			'placeholder' => false,
			'suffix'      => false,
		];
		foreach ( $options as $value ) {
			if ( ! isset( $value['type'] ) ) {
				continue;
			}
			
			$value = wp_parse_args( $value, $value_defaults );
			
			if ( ! isset( $value['title'] ) ) {
				$value['title'] = isset( $value['name'] ) ? $value['name'] : '';
			}
			
			if ( ! isset( $value['value'] ) ) {
				$value['value'] = self::get_option( $value['id'], $value['default'] );
			}

			// Custom attribute handling.
			$custom_attributes = array();

			if ( ! empty( $value['custom_attributes'] ) && is_array( $value['custom_attributes'] ) ) {
				foreach ( $value['custom_attributes'] as $attribute => $attribute_value ) {
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
				}
			}

			// Description handling.
			$field_description = self::get_field_description( $value );
			$description       = $field_description['description'];
			$tooltip_html      = $field_description['tooltip_html'];

			// Switch based on type.
			switch ( $value['type'] ) {

				// Section Titles.
				case 'title':
					if ( ! empty( $value['title'] ) ) {
						echo '<h2>' . esc_html( $value['title'] ) . '</h2>';
					}
					if ( ! empty( $value['desc'] ) ) {
						echo '<div id="' . esc_attr( sanitize_title( $value['id'] ) ) . '-description">';
						echo wp_kses_post( wpautop( wptexturize( $value['desc'] ) ) );
						echo '</div>';
					}
					echo '<table class="form-table">' . "\n\n";
					if ( ! empty( $value['id'] ) ) {
						do_action( 'salexpresso_settings_' . sanitize_title( $value['id'] ) );
					}
					break;

				// Section Ends.
				case 'sectionend':
					if ( ! empty( $value['id'] ) ) {
						do_action( 'salexpresso_settings_' . sanitize_title( $value['id'] ) . '_end' );
					}
					echo '</table>';
					if ( ! empty( $value['id'] ) ) {
						do_action( 'salexpresso_settings_' . sanitize_title( $value['id'] ) . '_after' );
					}
					break;

				// Standard text inputs and subtypes like 'number'.
				case 'text':
				case 'password':
				case 'datetime':
				case 'datetime-local':
				case 'date':
				case 'month':
				case 'time':
				case 'week':
				case 'number':
				case 'email':
				case 'url':
				case 'tel':
					$option_value = $value['value'];

					?><tr>
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<input
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								type="<?php echo esc_attr( $value['type'] ); ?>"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								value="<?php echo esc_attr( $option_value ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
								/><?php echo esc_html( $value['suffix'] ); ?> <?php echo $description; // WPCS: XSS ok. ?>
						</td>
					</tr>
					<?php
					break;

				// Color picker.
				case 'color':
					$option_value = $value['value'];

					?>
					<tr>
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">&lrm;
							<span class="colorpickpreview" style="background: <?php echo esc_attr( $option_value ); ?>">&nbsp;</span>
							<input
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								type="text"
								dir="ltr"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								value="<?php echo esc_attr( $option_value ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>colorpick"
								placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
								/>&lrm; <?php echo $description; // WPCS: XSS ok. ?>
								<div id="colorPickerDiv_<?php echo esc_attr( $value['id'] ); ?>" class="colorpickdiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;"></div>
						</td>
					</tr>
					<?php
					break;

				// Textarea.
				case 'textarea':
					$option_value = $value['value'];

					?>
					<tr>
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<?php echo $description; // WPCS: XSS ok. ?>

							<textarea
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
								><?php echo esc_textarea( $option_value ); // WPCS: XSS ok. ?></textarea>
						</td>
					</tr>
					<?php
					break;

				// Select boxes.
				case 'select':
				case 'multiselect':
					$option_value = $value['value'];

					?>
					<tr>
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<select
								name="<?php echo esc_attr( $value['id'] ); ?><?php echo ( 'multiselect' === $value['type'] ) ? '[]' : ''; ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
								<?php echo 'multiselect' === $value['type'] ? 'multiple="multiple"' : ''; ?>
								>
								<?php
								foreach ( $value['options'] as $key => $val ) {
									?>
									<option value="<?php echo esc_attr( $key ); ?>"
										<?php

										if ( is_array( $option_value ) ) {
											selected( in_array( (string) $key, $option_value, true ), true );
										} else {
											selected( $option_value, (string) $key );
										}

										?>
									><?php echo esc_html( $val ); ?></option>
									<?php
								}
								?>
							</select> <?php echo $description; // WPCS: XSS ok. ?>
						</td>
					</tr>
					<?php
					break;

				// Radio inputs.
				case 'radio':
					$option_value = $value['value'];

					?>
					<tr>
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<fieldset>
								<?php echo $description; // WPCS: XSS ok. ?>
								<ul>
								<?php
								foreach ( $value['options'] as $key => $val ) {
									?>
									<li>
										<label><input
											name="<?php echo esc_attr( $value['id'] ); ?>"
											value="<?php echo esc_attr( $key ); ?>"
											type="radio"
											style="<?php echo esc_attr( $value['css'] ); ?>"
											class="<?php echo esc_attr( $value['class'] ); ?>"
											<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
											<?php checked( $key, $option_value ); ?>
											/> <?php echo esc_html( $val ); ?></label>
									</li>
									<?php
								}
								?>
								</ul>
							</fieldset>
						</td>
					</tr>
					<?php
					break;

				// Checkbox input.
				case 'checkbox':
					$option_value     = $value['value'];
					$visibility_class = array();

					if ( ! isset( $value['hide_if_checked'] ) ) {
						$value['hide_if_checked'] = false;
					}
					if ( ! isset( $value['show_if_checked'] ) ) {
						$value['show_if_checked'] = false;
					}
					if ( 'yes' === $value['hide_if_checked'] || 'yes' === $value['show_if_checked'] ) {
						$visibility_class[] = 'hidden_option';
					}
					if ( 'option' === $value['hide_if_checked'] ) {
						$visibility_class[] = 'hide_options_if_checked';
					}
					if ( 'option' === $value['show_if_checked'] ) {
						$visibility_class[] = 'show_options_if_checked';
					}

					if ( ! isset( $value['checkboxgroup'] ) || 'start' === $value['checkboxgroup'] ) {
						?>
							<tr class="<?php echo esc_attr( implode( ' ', $visibility_class ) ); ?>">
								<th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ); ?></th>
								<td class="forminp forminp-checkbox">
									<fieldset>
						<?php
					} else {
						?>
							<fieldset class="<?php echo esc_attr( implode( ' ', $visibility_class ) ); ?>">
						<?php
					}

					if ( ! empty( $value['title'] ) ) {
						?>
							<legend class="screen-reader-text"><span><?php echo esc_html( $value['title'] ); ?></span></legend>
						<?php
					}

					?>
						<label for="<?php echo esc_attr( $value['id'] ); ?>">
							<input
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								type="checkbox"
								class="<?php echo esc_attr( isset( $value['class'] ) ? $value['class'] : '' ); ?>"
								value="1"
								<?php checked( $option_value, 'yes' ); ?>
								<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
							/> <?php echo $description; // WPCS: XSS ok. ?>
						</label> <?php echo $tooltip_html; // WPCS: XSS ok. ?>
					<?php

					if ( ! isset( $value['checkboxgroup'] ) || 'end' === $value['checkboxgroup'] ) {
						?>
									</fieldset>
								</td>
							</tr>
						<?php
					} else {
						?>
							</fieldset>
						<?php
					}
					break;
				// Single page selects.
				case 'single_select_page':
					$args = array(
						'name'             => $value['id'],
						'id'               => $value['id'],
						'sort_column'      => 'menu_order',
						'sort_order'       => 'ASC',
						'show_option_none' => ' ',
						'class'            => $value['class'],
						'echo'             => false,
						'selected'         => absint( $value['value'] ),
						'post_status'      => 'publish,private,draft',
					);

					if ( isset( $value['args'] ) ) {
						$args = wp_parse_args( $value['args'], $args );
					}

					?>
					<tr class="single_select_page">
						<th scope="row" class="titledesc">
							<label><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp">
							<?php echo str_replace( ' id=', " data-placeholder='" . esc_attr__( 'Select a page&hellip;', 'salexpresso' ) . "' style='" . $value['css'] . "' class='" . $value['class'] . "' id=", wp_dropdown_pages( $args ) ); // WPCS: XSS ok. ?> <?php echo $description; // WPCS: XSS ok. ?>
						</td>
					</tr>
					<?php
					break;

				// Single country selects.
				case 'single_select_country':
					$country_setting = (string) $value['value'];

					if ( strstr( $country_setting, ':' ) ) {
						$country_setting = explode( ':', $country_setting );
						$country         = current( $country_setting );
						$state           = end( $country_setting );
					} else {
						$country = $country_setting;
						$state   = '*';
					}
					?>
					<tr>
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp">
							<select name="<?php echo esc_attr( $value['id'] ); ?>" style="<?php echo esc_attr( $value['css'] ); ?>" data-placeholder="<?php esc_attr_e( 'Choose a country / region&hellip;', 'salexpresso' ); ?>" aria-label="<?php esc_attr_e( 'Country / Region', 'salexpresso' ); ?>" class="wc-enhanced-select">
								<?php WC()->countries->country_dropdown_options( $country, $state ); ?>
							</select> <?php echo $description; // WPCS: XSS ok. ?>
						</td>
					</tr>
					<?php
					break;

				// Country multiselects.
				case 'multi_select_countries':
					$selections = (array) $value['value'];

					if ( ! empty( $value['options'] ) ) {
						$countries = $value['options'];
					} else {
						$countries = WC()->countries->countries;
					}

					asort( $countries );
					?>
					<tr>
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp">
							<select multiple="multiple" name="<?php echo esc_attr( $value['id'] ); ?>[]" style="width:350px" data-placeholder="<?php esc_attr_e( 'Choose countries / regions&hellip;', 'salexpresso' ); ?>" aria-label="<?php esc_attr_e( 'Country / Region', 'salexpresso' ); ?>" class="wc-enhanced-select">
								<?php
								if ( ! empty( $countries ) ) {
									foreach ( $countries as $key => $val ) {
										echo '<option value="' . esc_attr( $key ) . '" ' . wc_selected( $key, $selections ) . '>' . esc_html( $val ) . '</option>'; // WPCS: XSS ok.
									}
								}
								?>
							</select> <?php echo ( $description ) ? $description : ''; // WPCS: XSS ok. ?> <br /><a class="select_all button" href="#"><?php esc_html_e( 'Select all', 'salexpresso' ); ?></a> <a class="select_none button" href="#"><?php esc_html_e( 'Select none', 'salexpresso' ); ?></a>
						</td>
					</tr>
					<?php
					break;

				// Days/months/years selector.
				case 'relative_date_selector':
					$periods = [
						'days'   => _x( 'Day(s)', 'Relative Time Period Option', 'salexpresso' ),
						'weeks'  => _x( 'Week(s)', 'Relative Time Period Option', 'salexpresso' ),
						'months' => _x( 'Month(s)', 'Relative Time Period Option', 'salexpresso' ),
						'years'  => _x( 'Year(s)', 'Relative Time Period Option', 'salexpresso' ),
					];
					$option_value = wc_parse_relative_date_option( $value['value'] );
					?>
					<tr>
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp">
						<input
								name="<?php echo esc_attr( $value['id'] ); ?>[number]"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								type="number"
								style="width: 80px;"
								value="<?php echo esc_attr( $option_value['number'] ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
								step="1"
								min="1"
								<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
							/>&nbsp;
							<select name="<?php echo esc_attr( $value['id'] ); ?>[unit]" style="width: auto;" aria-label="<?php echo esc_attr_x( 'Select time period', 'Select relative time period option.', 'salexpresso' ); ?>">
								<?php
								foreach ( $periods as $value => $label ) {
									echo '<option value="' . esc_attr( $value ) . '" ' . selected( $option_value['unit'], $value, false ) . '>' . esc_html( $label ) . '</option>';
								}
								?>
							</select> <?php echo ( $description ) ? $description : ''; // WPCS: XSS ok. ?>
						</td>
					</tr>
					<?php
					break;

				// Default: run an action.
				default:
					do_action( 'salexpresso_admin_field_' . $value['type'], $value );
					break;
			}
		}
	}

	/**
	 * Helper function to get the formatted description and tip HTML for a
	 * given form field. Plugins can call this when implementing their own custom
	 * settings types.
	 *
	 * @param  array $value The form field value array.
	 * @return array The description and tip as a 2 element array.
	 */
	public static function get_field_description( $value ) {
		$description  = '';
		$tooltip_html = '';

		if ( true === $value['desc_tip'] ) {
			$tooltip_html = $value['desc'];
		} elseif ( ! empty( $value['desc_tip'] ) ) {
			$description  = $value['desc'];
			$tooltip_html = $value['desc_tip'];
		} elseif ( ! empty( $value['desc'] ) ) {
			$description = $value['desc'];
		}

		if ( $description && in_array( $value['type'], array( 'textarea', 'radio' ), true ) ) {
			$description = '<p style="margin-top:0">' . wp_kses_post( $description ) . '</p>';
		} elseif ( $description && in_array( $value['type'], array( 'checkbox' ), true ) ) {
			$description = wp_kses_post( $description );
		} elseif ( $description ) {
			$description = '<p class="description">' . wp_kses_post( $description ) . '</p>';
		}

		if ( $tooltip_html && in_array( $value['type'], array( 'checkbox' ), true ) ) {
			$tooltip_html = '<p class="description">' . $tooltip_html . '</p>';
		} elseif ( $tooltip_html ) {
			$tooltip_html = wc_help_tip( $tooltip_html );
		}

		return array(
			'description'  => $description,
			'tooltip_html' => $tooltip_html,
		);
	}

	/**
	 * Save admin fields.
	 *
	 * Loops though the woocommerce options array and outputs each field.
	 *
	 * @param array $options Options array to output.
	 * @param array $data    Optional. Data to use for saving. Defaults to $_POST.
	 * @return bool
	 */
	public static function save_fields( $options, $data = null ) {
		if ( is_null( $data ) ) {
			$data = $_POST; // WPCS: input var okay, CSRF ok.
		}
		if ( empty( $data ) ) {
			return false;
		}

		// Options to update will be stored here and saved later.
		$update_options   = array();
		$autoload_options = array();

		// Loop options and get values to save.
		foreach ( $options as $option ) {
			if ( ! isset( $option['id'] ) || ! isset( $option['type'] ) || ( isset( $option['is_option'] ) && false === $option['is_option'] ) ) {
				continue;
			}

			// Get posted value.
			if ( strstr( $option['id'], '[' ) ) {
				parse_str( $option['id'], $option_name_array );
				$option_name  = current( array_keys( $option_name_array ) );
				$setting_name = key( $option_name_array[ $option_name ] );
				$raw_value    = isset( $data[ $option_name ][ $setting_name ] ) ? wp_unslash( $data[ $option_name ][ $setting_name ] ) : null;
			} else {
				$option_name  = $option['id'];
				$setting_name = '';
				$raw_value    = isset( $data[ $option['id'] ] ) ? wp_unslash( $data[ $option['id'] ] ) : null;
			}

			// Format the value based on option type.
			switch ( $option['type'] ) {
				case 'checkbox':
					$value = '1' === $raw_value || 'yes' === $raw_value ? 'yes' : 'no';
					break;
				case 'textarea':
					$value = wp_kses_post( trim( $raw_value ) );
					break;
				case 'multiselect':
				case 'multi_select_countries':
					$value = array_filter( array_map( 'wc_clean', (array) $raw_value ) );
					break;
				case 'image_width':
					$value = array();
					if ( isset( $raw_value['width'] ) ) {
						$value['width']  = wc_clean( $raw_value['width'] );
						$value['height'] = wc_clean( $raw_value['height'] );
						$value['crop']   = isset( $raw_value['crop'] ) ? 1 : 0;
					} else {
						$value['width']  = $option['default']['width'];
						$value['height'] = $option['default']['height'];
						$value['crop']   = $option['default']['crop'];
					}
					break;
				case 'select':
					$allowed_values = empty( $option['options'] ) ? array() : array_map( 'strval', array_keys( $option['options'] ) );
					if ( empty( $option['default'] ) && empty( $allowed_values ) ) {
						$value = null;
						break;
					}
					$default = ( empty( $option['default'] ) ? $allowed_values[0] : $option['default'] );
					$value   = in_array( $raw_value, $allowed_values, true ) ? $raw_value : $default;
					break;
				case 'relative_date_selector':
					$value = wc_parse_relative_date_option( $raw_value );
					break;
				default:
					$value = wc_clean( $raw_value );
					break;
			}

			/**
			 * Fire an action when a certain 'type' of field is being saved.
			 *
			 * @deprecated 2.4.0 - doesn't allow manipulation of values!
			 */
			if ( has_action( 'salexpresso_update_option_' . sanitize_title( $option['type'] ) ) ) {
				wc_deprecated_function( 'The woocommerce_update_option_X action', '2.4.0', 'salexpresso_admin_settings_sanitize_option filter' );
				do_action( 'salexpresso_update_option_' . sanitize_title( $option['type'] ), $option );
				continue;
			}

			/**
			 * Sanitize the value of an option.
			 *
			 * @since 2.4.0
			 */
			$value = apply_filters( 'salexpresso_admin_settings_sanitize_option', $value, $option, $raw_value );

			/**
			 * Sanitize the value of an option by option name.
			 *
			 * @since 2.4.0
			 */
			$value = apply_filters( "woocommerce_admin_settings_sanitize_option_$option_name", $value, $option, $raw_value );

			if ( is_null( $value ) ) {
				continue;
			}

			// Check if option is an array and handle that differently to single values.
			if ( $option_name && $setting_name ) {
				if ( ! isset( $update_options[ $option_name ] ) ) {
					$update_options[ $option_name ] = get_option( $option_name, array() );
				}
				if ( ! is_array( $update_options[ $option_name ] ) ) {
					$update_options[ $option_name ] = array();
				}
				$update_options[ $option_name ][ $setting_name ] = $value;
			} else {
				$update_options[ $option_name ] = $value;
			}

			$autoload_options[ $option_name ] = isset( $option['autoload'] ) ? (bool) $option['autoload'] : true;

			/**
			 * Fire an action before saved.
			 *
			 * @deprecated 2.4.0 - doesn't allow manipulation of values!
			 */
			do_action( 'salexpresso_update_option', $option );
		}

		// Save all options in our array.
		foreach ( $update_options as $name => $value ) {
			update_option( $name, $value, $autoload_options[ $name ] ? 'yes' : 'no' );
		}

		return true;
	}
}
// End of file class-sxp-admin-settings.php.
