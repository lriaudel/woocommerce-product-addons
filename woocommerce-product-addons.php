<?php
/**
 * Plugin Name: WooCommerce Product Add-ons
 * Plugin URI: https://woocommerce.com/products/product-add-ons/
 * Description: Add extra options to products which your customers can select from, when adding to the cart, with an optional fee for each extra option. Add-ons can be checkboxes, a select box, or custom text input.
 * Version: 2.9.0
 * Author: WooCommerce
 * Author URI: https://woocommerce.com
 * Requires at least: 3.8
 * Tested up to: 4.8
 * WC tested up to: 3.1
 * Copyright: © 2009-2017 WooCommerce.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Woo: 18618:147d0077e591e16db9d0d67daeb8c484
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Required functions.
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Plugin updates.
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '147d0077e591e16db9d0d67daeb8c484', '18618' );

if ( is_woocommerce_active() ) {
	define( 'WC_PRODUCT_ADDONS_VERSION', '2.9.0' );

	/**
	 * Main class.
	 */
	class WC_Product_Addons {

		protected $groups_controller;

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
			add_action( 'plugins_loaded', array( $this, 'init_classes' ) );
			add_action( 'init', array( $this, 'init_post_types' ), 20 );
			add_action( 'init', array( $this, 'setup_notices' ) );
			add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
			register_activation_hook( __FILE__, array( $this, 'install' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		}

		/**
		 * Localisation.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'woocommerce-product-addons', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Initializes plugin classes.
		 *
		 * @version 2.9.0
		 */
		public function init_classes() {
			// Core (models)
			include_once( dirname( __FILE__ ) . '/includes/groups/class-product-addon-group-validator.php' );
			include_once( dirname( __FILE__ ) . '/includes/groups/class-product-addon-global-group.php' );
			include_once( dirname( __FILE__ ) . '/includes/groups/class-product-addon-product-group.php' );
			include_once( dirname( __FILE__ ) . '/includes/groups/class-product-addon-groups.php' );

			// Admin
			if ( is_admin() ) {
				$this->init_admin();
			}

			// Front-side and legacy AJAX
			include_once( dirname( __FILE__ ) . '/includes/class-product-addon-display.php' );
			include_once( dirname( __FILE__ ) . '/includes/class-product-addon-cart.php' );
			include_once( dirname( __FILE__ ) . '/includes/class-product-addon-ajax.php' );

			// Handle WooCommerce 3.0 compatibility.
			if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0.0', '<' ) ) {
				include_once( dirname( __FILE__ ) . '/includes/legacy/class-product-addon-display-legacy.php' );
				include_once( dirname( __FILE__ ) . '/includes/legacy/class-product-addon-cart-legacy.php' );
				include_once( dirname( __FILE__ ) . '/includes/legacy/class-wc-addons-ajax.php' );

				$GLOBALS['Product_Addon_Display'] = new Product_Addon_Display_Legacy();
				$GLOBALS['Product_Addon_Cart']    = new Product_Addon_Cart_Legacy();
				new WC_Addons_Ajax();
			} else {
				$GLOBALS['Product_Addon_Display'] = new Product_Addon_Display();
				$GLOBALS['Product_Addon_Cart']    = new Product_Addon_Cart();
				new Product_Addon_Cart_Ajax();
			}
		}

		/**
		 * Initializes plugin admin.
		 */
		protected function init_admin() {
			include_once( dirname( __FILE__ ) . '/admin/class-product-addon-admin.php' );

			// Handle WooCommerce 3.0 compatibility.
			if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0.0', '<' ) ) {
				include_once( dirname( __FILE__ ) . '/admin/legacy/class-product-addon-admin-legacy.php' );

				$GLOBALS['Product_Addon_Admin'] = new Product_Addon_Admin_Legacy();
			} else {
				$GLOBALS['Product_Addon_Admin'] = new Product_Addon_Admin();
			}
		}

		/**
		 * Init post types used for addons.
		 */
		public function init_post_types() {
			register_post_type( 'global_product_addon',
				array(
					'public'              => false,
					'show_ui'             => false,
					'capability_type'     => 'product',
					'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'hierarchical'        => false,
					'rewrite'             => false,
					'query_var'           => false,
					'supports'            => array( 'title' ),
					'show_in_nav_menus'   => false,
				)
			);

			register_taxonomy_for_object_type( 'product_cat', 'global_product_addon' );
		}

		/**
		 * Initialize the REST API
		 *
		 * @since 2.9.0
		 * @param WP_Rest_Server $wp_rest_server
		 */
		public function rest_api_init( $wp_rest_server ) {
			require_once( dirname( __FILE__ ) . '/includes/api/wc-product-add-ons-groups-controller-v1.php' );
			$this->groups_controller = new WC_Product_Add_Ons_Groups_Controller();
			$this->groups_controller->register_routes();
		}

		/**
		 * Plugin action links
		 */
		public function action_links( $links ) {
			$plugin_links = array(
				'<a href="https://support.woocommerce.com/">' . __( 'Support', 'woocomerce-product-addons' ) . '</a>',
				'<a href="https://docs.woocommerce.com/document/product-add-ons/">' . __( 'Documentation', 'woocomerce-product-addons' ) . '</a>',
			);
			return array_merge( $plugin_links, $links );
		}

		/**
		 * Setup notices.
		 */
		public function setup_notices() {
			add_action( 'admin_notices', array( $this, 'customizer_notice' ) );
		}

		/**
		 * Installation.
		 * Runs on activation. Assigns a notice message to a WordPress option.
		 */
		public function install() {
			$notices   = get_option( 'wpa_activation_notice', array() );
			$notices[] = '<p><strong>' . __( 'Thanks for installing WooCommerce Product Add-ons.', 'woocommerce-product-addons' ) . '</strong></p><p>' . sprintf( __( 'Before diving in, we highly recommend getting to grips with %1$sper product add-ons%2$s and %3$sglobal add-ons%2$s in the %4$sdocumentation%2$s.', 'woocommerce-product-addons' ), '<a href="https://docs.woocommerce.com/document/product-add-ons/#section-1">', '</a>', '<a href="https://docs.woocommerce.com/document/product-add-ons/#section-2">', '<a href="https://docs.woocommerce.com/document/product-add-ons/">' ) . '</p><p>' . sprintf( __( 'Ready to get started? %sAdd a global add-on%s.','woocommerce-product-addons' ), '<a href="' . esc_url( admin_url() ) . 'edit.php?post_type=product&page=global_addons">', '</a>' ) . '</p>';
			update_option( 'wpa_activation_notice', $notices );
		}

		/**
		 * Admin notice.
		 * Checks the notice setup in install(). If it exists display it then delete the option so it's not displayed again.
		 */
		public function customizer_notice() {
			$notices = get_option( 'wpa_activation_notice' );
			if ( $notices = get_option( 'wpa_activation_notice' ) ) {
				foreach ( $notices as $notice ) {
					echo '<div class="notice is-dismissible updated">' . $notice . '</div>';
				}
				delete_option( 'wpa_activation_notice' );
			}
		}
	}

	new WC_Product_Addons();

	/**
	 * Gets addons assigned to a product by ID.
	 *
	 * @param  int    $post_id ID of the product to get addons for.
	 * @param  string $prefix for addon field names. Defaults to postid.
	 * @param  bool   $inc_parent Set to false to not include parent product addons.
	 * @param  bool   $inc_global Set to false to not include global addons.
	 * @return array
	 */
	function get_product_addons( $post_id, $prefix = false, $inc_parent = true, $inc_global = true ) {
		if ( ! $post_id ) {
			return array();
		}

		$addons     = array();
		$raw_addons = array();

		if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0.0', '<' ) ) {
			$product_terms  = apply_filters( 'get_product_addons_product_terms', wp_get_post_terms( $post_id, 'product_cat', array( 'fields' => 'ids' ) ), $post_id );
			$exclude        = get_post_meta( $post_id, '_product_addons_exclude_global', true );
			$product_addons = array_filter( (array) get_post_meta( $post_id, '_product_addons', true ) );
		} else {
			$product        = wc_get_product( $post_id );
			$product_terms  = apply_filters( 'get_product_addons_product_terms', wc_get_object_terms( $product->get_id(), 'product_cat', 'term_id' ), $product->get_id() );
			$exclude        = $product->get_meta( '_product_addons_exclude_global' );
			$product_addons = array_filter( (array) $product->get_meta( '_product_addons' ) );
		}

		// Product Parent Level Addons.
		if ( $inc_parent && $parent_id = wp_get_post_parent_id( $post_id ) ) {
			$raw_addons[10]['parent'] = apply_filters( 'get_parent_product_addons_fields', get_product_addons( $parent_id, $parent_id . '-', false, false ), $post_id, $parent_id );
		}

		// Product Level Addons.
		$raw_addons[10]['product'] = apply_filters( 'get_product_addons_fields', $product_addons, $post_id );

		// Global level addons (all products).
		if ( '1' !== $exclude && $inc_global ) {
			$args = array(
				'posts_per_page'   => -1,
				'orderby'          => 'meta_value',
				'order'            => 'ASC',
				'meta_key'         => '_priority',
				'post_type'        => 'global_product_addon',
				'post_status'      => 'publish',
				'suppress_filters' => true,
				'meta_query' => array(
					array(
						'key'   => '_all_products',
						'value' => '1',
					),
				),
			);

			$global_addons = get_posts( $args );

			if ( $global_addons ) {
				foreach ( $global_addons as $global_addon ) {
					$priority                                     = get_post_meta( $global_addon->ID, '_priority', true );
					$raw_addons[ $priority ][ $global_addon->ID ] = apply_filters( 'get_product_addons_fields', array_filter( (array) get_post_meta( $global_addon->ID, '_product_addons', true ) ), $global_addon->ID );
				}
			}

			// Global level addons (categories).
			if ( $product_terms ) {
				$args = apply_filters( 'get_product_addons_global_query_args', array(
					'posts_per_page'   => -1,
					'orderby'          => 'meta_value',
					'order'            => 'ASC',
					'meta_key'         => '_priority',
					'post_type'        => 'global_product_addon',
					'post_status'      => 'publish',
					'suppress_filters' => true,
					'tax_query'        => array(
						array(
							'taxonomy'         => 'product_cat',
							'field'            => 'id',
							'terms'            => $product_terms,
							'include_children' => false,
						),
					),
				), $product_terms );

				$global_addons = get_posts( $args );

				if ( $global_addons ) {
					foreach ( $global_addons as $global_addon ) {
						$priority                                     = get_post_meta( $global_addon->ID, '_priority', true );
						$raw_addons[ $priority ][ $global_addon->ID ] = apply_filters( 'get_product_addons_fields', array_filter( (array) get_post_meta( $global_addon->ID, '_product_addons', true ) ), $global_addon->ID );
					}
				}
			}
		}

		ksort( $raw_addons );

		foreach ( $raw_addons as $addon_group ) {
			if ( $addon_group ) {
				foreach ( $addon_group as $addon ) {
					$addons = array_merge( $addons, $addon );
				}
			}
		}

		// Generate field names with unqiue prefixes.
		if ( ! $prefix ) {
			$prefix = apply_filters( 'product_addons_field_prefix', "{$post_id}-", $post_id );
		}

		// Let's avoid exceeding the suhosin default input element name limit of 64 characters.
		$max_addon_name_length = 45 - strlen( $prefix );

		// If the product_addons_field_prefix filter results in a very long prefix, then
		// go ahead and enforce sanity, exceed the default suhosin limit, and just use
		// the prefix and the field counter for the input element name.
		if ( $max_addon_name_length < 0 ) {
			$max_addon_name_length = 0;
		}

		$addon_field_counter = 0;

		foreach ( $addons as $addon_key => $addon ) {
			if ( empty( $addon['name'] ) ) {
				unset( $addons[ $addon_key ] );
				continue;
			}
			if ( empty( $addons[ $addon_key ]['field-name'] ) ) {
				$addon_name = substr( $addon['name'], 0, $max_addon_name_length );
				$addons[ $addon_key ]['field-name'] = sanitize_title( $prefix . $addon_name . '-' . $addon_field_counter );
				$addon_field_counter++;
			}
		}

		return apply_filters( 'get_product_addons', $addons );
	}

	/**
	 * Display prices according to shop settings.
	 *
	 * @version 2.8.2
	 *
	 * @param  float      $price     Price to display.
	 * @param  WC_Product $cart_item Product from cart.
	 *
	 * @return float
	 */
	function get_product_addon_price_for_display( $price, $cart_item = null ) {
		$product = ! empty( $GLOBALS['product'] ) && is_object( $GLOBALS['product'] ) ? clone $GLOBALS['product'] : null;

		if ( '' === $price || '0' == $price ) {
			return;
		}

		if ( ( is_cart() || is_checkout() ) && null !== $cart_item ) {
			$product = wc_get_product( $cart_item->get_id() );
		}

		if ( is_object( $product ) ) {
			// Support new wc_get_price_excluding_tax() and wc_get_price_excluding_tax() functions.
			if ( function_exists( 'wc_get_price_excluding_tax' ) ) {
				$display_price = get_product_addon_tax_display_mode() === 'incl' ? wc_get_price_including_tax( $product, array( 'qty' => 1, 'price' => $price ) ) : wc_get_price_excluding_tax( $product, array( 'qty' => 1, 'price' => $price ) );
			} else {
				$display_price = get_product_addon_tax_display_mode() === 'incl' ? $product->get_price_including_tax( 1, $price ) : $product->get_price_excluding_tax( 1, $price );
			}
		} else {
			$display_price = $price;
		}

		return $display_price;
	}

	/**
	 * Return tax display mode depending on context.
	 *
	 * @return string
	 */
	function get_product_addon_tax_display_mode() {
		if ( is_cart() || is_checkout() ) {
			return get_option( 'woocommerce_tax_display_cart' );
		}

		return get_option( 'woocommerce_tax_display_shop' );
	}
}
