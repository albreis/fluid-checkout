<?php
/**
 * WooCommerce Checkout Settings
 *
 * @package fluid-checkout
 * @version 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Settings_FluidCheckout_Checkout', false ) ) {
	return new WC_Settings_FluidCheckout_Checkout();
}

/**
 * WC_Settings_FluidCheckout_Checkout.
 */
class WC_Settings_FluidCheckout_Checkout extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'fc_checkout';
		$this->label = __( 'Fluid Checkout', 'fluid-checkout' );

		parent::__construct();

		$this->hooks();
	}


	/**
	 * Initialize hooks.
	 */
	public function hooks() {
		add_action( 'woocommerce_admin_field_fc_layout_selector', array( $this, 'output_field_type_fc_layout_seletor' ), 10 );
	}



	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array(
			''             => __( 'Checkout Options', 'fluid-checkout' ),
			'address'    => __( 'Address Fields', 'fluid-checkout' ),
			'advanced'     => __( 'Advanced', 'fluid-checkout' ),
		);

		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}



	/**
	 * Output the settings.
	 */
	public function output() {
		global $current_section;

		$settings = $this->get_settings( $current_section );

		WC_Admin_Settings::output_fields( $settings );
	}



	/**
	 * Save settings.
	 */
	public function save() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::save_fields( $settings );

		if ( $current_section ) {
			do_action( 'woocommerce_update_options_' . $this->id . '_' . $current_section );
		}
	}



	/**
	 * Get settings array.
	 *
	 * @param string $current_section Current section name.
	 * @return array
	 */
	public function get_settings( $current_section = '' ) {
		if ( 'advanced' === $current_section ) {
			$settings = apply_filters(
				'fc_checkout_'.$current_section.'_settings',
				array(
					array(
						'title' => __( 'Layout', 'fluid-checkout' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'fc_checkout_advanced_layout_options',
					),

					array(
						'title'         => __( 'Order Summary', 'fluid-checkout' ),
						'desc'          => __( 'Make the order summary stay visible while scrolling', 'fluid-checkout' ),
						'id'            => 'fc_enable_checkout_sticky_order_summary',
						'default'       => 'yes',
						'type'          => 'checkbox',
						'autoload'      => false,
					),

					array(
						'title'         => __( 'Progress bar', 'fluid-checkout' ),
						'desc'          => __( 'Make the checkout progress bar stay visible while scrolling', 'fluid-checkout' ),
						'desc_tip'      => __( 'Applies only to multi-step layouts.', 'fluid-checkout' ),
						'id'            => 'fc_enable_checkout_sticky_progress_bar',
						'default'       => 'yes',
						'type'          => 'checkbox',
						'autoload'      => false,
					),

					array(
						'title'         => __( 'Header and Footer', 'fluid-checkout' ),
						'desc'          => __( 'We recommend using the Fluid Checkout header and footer to avoid distractions at the checkout page. <a href="https://baymard.com/blog/cart-abandonment" target="_blank">Read the research about cart abandonment</a>.', 'fluid-checkout' ),
						'desc_tip'      => __( 'Controls whether to use the Fluid Checkout page header and footer of keep the currently active theme\'s.', 'fluid-checkout' ),
						'id'            => 'fc_hide_site_header_footer_at_checkout',
						'type'          => 'radio',
						'options'       => array(
							'yes'       => __( 'Use Fluid Checkout header and footer', 'fluid-checkout' ),
							'no'        => __( 'Use theme\'s page header and footer for the checkout page', 'fluid-checkout' ),
						),
						'default'       => 'yes',
						'autoload'      => false,
					),

					array(
						'type' => 'sectionend',
						'id'   => 'fc_checkout_advanced_layout_options',
					),

					array(
						'title' => __( 'Troubleshooting', 'fluid-checkout' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'fc_checkout_advanced_debug_options',
					),

					array(
						'title'         => __( 'Debug options', 'fluid-checkout' ),
						'desc'          => __( 'Load unminified assets', 'fluid-checkout' ),
						'desc_tip'      => __( 'Loading unminified assets affect the the website performance. Only use this option while troubleshooting.', 'fluid-checkout' ),
						'id'            => 'fc_load_unminified_assets',
						'default'       => 'no',
						'type'          => 'checkbox',
						'autoload'      => false,
					),

					array(
						'type' => 'sectionend',
						'id'   => 'fc_checkout_advanced_debug_options',
					),
				)
			);
		}
		else if ( 'address' === $current_section ) {
			$settings = apply_filters(
				'fc_checkout_'.$current_section.'_settings',
				array(
					array(
						'title' => __( 'Address Fields', 'fluid-checkout' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'fc_checkout_advanced_debug_options',
					),

					array(
						'title'         => __( 'Billing Address', 'fluid-checkout' ),
						'desc'          => __( 'Billing address same as the shipping address checked by default', 'fluid-checkout' ),
						'desc_tip'      => __( 'It is recommended to leave this option checked. The billing address at checkout will start with the option "Billing same as shipping" checked by default. This will reduce significantly the number of open input fields at the checkout, <a href="https://baymard.com/blog/checkout-flow-average-form-fields#3-default-billing--shipping-and-hide-the-fields-entirely" target="_blank">read the research</a>.', 'fluid-checkout' ),
						'id'            => 'fc_default_to_billing_same_as_shipping',
						'default'       => 'yes',
						'type'          => 'checkbox',
						'autoload'      => false,
					),

					array(
						'title'         => __( 'Shipping phone', 'fluid-checkout' ),
						'desc'          => __( 'Add a phone field to the shipping address form', 'fluid-checkout' ),
						'id'            => 'fc_shipping_phone_field_visibility',
						'options'       => array(
							'no'        => __( 'Hidden', 'fluid-checkout' ),
							'optional'  => __( 'Optional', 'fluid-checkout' ),
							'required'  => __( 'Required', 'fluid-checkout' ),
						),
						'default'       => 'no',
						'type'          => 'select',
						'autoload'      => false,
					),

					array(
						'type' => 'sectionend',
						'id'   => 'fc_checkout_advanced_debug_options',
					),
				)
			);
		}
		else {
			$settings = apply_filters(
				'fc_checkout_general_settings',
				array(
					array(
						'title' => __( 'Layout', 'fluid-checkout' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'fc_checkout_layout_options',
					),

					array(
						'title'             => __( 'Checkout Layout', 'fluid-checkout' ),
						'id'                => 'fc_checkout_layout',
						'type'              => 'fc_layout_selector',
						'options'           => FluidCheckout_Steps::instance()->get_allowed_checkout_layouts(),
						'default'           => 'multi-step',
						'autoload'          => false,
						'wrapper_class'     => 'fc-checkout-layout',
						'class'             => 'fc-checkout-layout__option',
					),

					array(
						'title'             => __( 'Optional fields', 'fluid-checkout' ),
						'desc'              => __( 'Hide optional fields behind a link button', 'fluid-checkout' ),
						'desc_tip'          => __( 'It is recommended to keep this options checked to reduce the number of open input fields, <a href="https://baymard.com/blog/checkout-flow-average-form-fields#1-address-line-2--company-name-can-safely-be-collapsed-behind-a-link" target="_blank">read the research</a>.', 'fluid-checkout' ),
						'id'                => 'fc_enable_checkout_hide_optional_fields',
						'default'           => 'yes',
						'type'              => 'checkbox',
						'checkboxgroup'     => 'start',
						'show_if_checked'   => 'option',
						'autoload'          => false,
					),
					array(
						'desc'              => __( 'Do not hide "Address line 2" fields behind a link button', 'fluid-checkout' ),
						'desc_tip'          => __( 'Recommended only whe most customers actually need the "Address line 2" field or when delivering perishable products.', 'fluid-checkout' ),
						'id'                => 'fc_hide_optional_fields_skip_address_2',
						'default'           => 'no',
						'type'              => 'checkbox',
						'checkboxgroup'     => 'end',
						'show_if_checked'   => 'yes',
						'autoload'          => false,
					),

					array(
						'title'             => __( 'Order Summary', 'fluid-checkout' ),
						'desc'              => __( 'Display an additional "Place order" and terms checkbox below the order summary in the sidebar.', 'fluid-checkout' ),
						'desc_tip'          => __( 'Recommended if most of the orders have only a few different products in the cart, and product variations do not take too much space on the order summary.', 'fluid-checkout' ),
						'id'                => 'fc_enable_checkout_place_order_sidebar',
						'default'           => 'no',
						'type'              => 'checkbox',
						'autoload'          => false,
					),

					array(
						'type' => 'sectionend',
						'id'   => 'fc_checkout_layout_options',
					),

					array(
						'title' => __( 'Features', 'fluid-checkout' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'fc_checkout_features_options',
					),

					array(
						'title'             => __( 'Coupon Codes', 'fluid-checkout' ),
						'desc'              => __( 'Show coupon codes as a substep of the payment step', 'fluid-checkout' ),
						'desc_tip'          => __( 'Only applicable if use of coupon codes are enabled in the WooCommerce settings.', 'fluid-checkout' ),
						'id'                => 'fc_enable_checkout_coupon_codes',
						'default'           => 'yes',
						'type'              => 'checkbox',
						'checkboxgroup'     => 'start',
						'show_if_checked'   => 'option',
						'autoload'          => false,
					),
					array(
						'desc'              => __( 'Display the coupon codes section title', 'woocommerce' ),
						'id'                => 'fc_display_coupon_code_section_title',
						'type'              => 'checkbox',
						'default'           => 'no',
						'checkboxgroup'     => 'end',
						'show_if_checked'   => 'yes',
						'autoload'          => false,
					),

					array(
						'title'             => __( 'Gift options', 'fluid-checkout' ),
						'desc'              => __( 'Display gift message and other gift options at the checkout page', 'fluid-checkout' ),
						'desc_tip'          => __( 'Allow customers to add a gift message and other gift related options to the order.', 'fluid-checkout' ),
						'id'                => 'fc_enable_checkout_gift_options',
						'default'           => 'no',
						'type'              => 'checkbox',
						'checkboxgroup'     => 'start',
						'show_if_checked'   => 'option',
						'autoload'          => false,
					),
					array(
						'desc'              => __( 'Display the gift message fields always expanded', 'woocommerce' ),
						'id'                => 'fc_default_gift_options_expanded',
						'type'              => 'checkbox',
						'default'           => 'no',
						'checkboxgroup'     => '',
						'show_if_checked'   => 'yes',
						'autoload'          => false,
					),
					array(
						'desc'              => __( 'Display the gift message as part of the order details table', 'woocommerce' ),
						'desc_tip'          => __( 'This option affects the order confirmation page (thank you page), order details at account pages, emails and packing slips.', 'woocommerce' ),
						'id'                => 'fc_display_gift_message_in_order_details',
						'type'              => 'checkbox',
						'default'           => 'no',
						'checkboxgroup'     => 'end',
						'show_if_checked'   => 'yes',
						'autoload'          => false,
					),

					array(
						'title'             => __( 'Checkout Widget Areas', 'fluid-checkout' ),
						'desc'              => __( 'Add widget areas to the checkout page', 'fluid-checkout' ),
						'desc_tip'          => __( 'These widget areas are used to add trust symbols on the checkout page.', 'fluid-checkout' ),
						'id'                => 'fc_enable_checkout_widget_areas',
						'default'           => 'yes',
						'type'              => 'checkbox',
						'autoload'          => false,
					),

					array(
						'type' => 'sectionend',
						'id'   => 'fc_checkout_features_options',
					),
				)
			);
		}

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
	}



	/**
	 * Output the layout selector setting field.
	 *
	 * @param   array  $value  Admin settings args values.
	 */
	public function output_field_type_fc_layout_seletor( $value ) {
		$option_value = $value['value'];
			?>
			<tr valign="top">
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
						<style>
							<?php
							foreach ( $value['options'] as $key => $val ) {
								$option_image_url = apply_filters( 'fc_checkout_layout_option_image_url', FluidCheckout::$directory_url . 'images/admin/fc-layout-'. esc_attr( $key ) .'.png', $key, $val );
								?>
								.forminp-fc_layout_selector .fc-checkout-layout__option[value="<?php echo esc_attr( $key ); ?>"]:after {
									background-image: url( <?php echo esc_url( $option_image_url ) ?> );
								}
								<?php
							}
							?>
						</style>
					</fieldset>
				</td>
			</tr>
		<?php
	}
}

return new WC_Settings_FluidCheckout_Checkout();