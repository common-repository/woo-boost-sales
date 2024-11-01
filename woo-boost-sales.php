<?php
/**
 * Plugin Name: Boost Sales for WooCommerce
 * Plugin URI: https://villatheme.com/extensions/woocommerce-boost-sales/
 * Description: Increases sales from every order by using Up-sell and Cross-sell techniques for your online store.
 * Version: 1.2.12
 * Author: VillaTheme
 * Author URI: http://villatheme.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: woo-boost-sales
 * Copyright 2017-2024 VillaTheme.com. All rights reserved.
 * Requires Plugins: woocommerce
 * Tested up to: 6.5
 * WC tested up to: 8.9
 * Requires PHP: 7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VI_WOO_BOOSTSALES_VERSION', '1.2.12' );


/**
 * Class VI_WOO_BOOSTSALES
 */
class VI_WOO_BOOSTSALES {
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'install' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		add_action( 'before_woocommerce_init', [ $this, 'custom_order_tables_declare_compatibility' ] );
	}

	public function init() {
		if (!function_exists('is_plugin_active')){
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		if ( is_plugin_active( 'woocommerce-boost-sales/woocommerce-boost-sales.php' ) ) {
			return;
		}
		$include_dir = plugin_dir_path( __FILE__ ) . 'includes/';
		if ( ! class_exists( 'VillaTheme_Require_Environment' ) ) {
			include_once $include_dir . 'support.php';
		}

		$environment = new \VillaTheme_Require_Environment( [
				'plugin_name'     => 'Boost Sales for WooCommerce',
				'php_version'     => '7.0',
				'wp_version'      => '5.0',
				'require_plugins' => [
					[
						'slug' => 'woocommerce',
						'name' => 'WooCommerce',
						'required_version' => '7.0',
					]
				]
			]
		);

		if ( $environment->has_error() ) {
			return;
		}

		require_once $include_dir . "define.php";
	}

	public function custom_order_tables_declare_compatibility() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}

	/**
	 * When active plugin Function will be call
	 */
	public function install() {
		$json_data = '{"enable_mobile":"1","enable_upsell":"1","show_with_category":"1","sort_product":"4","crosssell_enable":"1","crosssell_display_on":"0","enable_cart_page":"1","cart_page_option":"1","enable_checkout_page":"1","checkout_page_option":"1","crosssell_description":"Hang on! We have this offer just for you!","coupon_desc":"SWEET! Add more products and get {discount_amount} off on your entire order!","enable_thankyou":"1","message_congrats":"You have successfully reached the goal, and a {discount_amount} discount will be applied to your order.","text_btn_checkout":"Checkout now","button_color":"#111111","button_bg_color":"#bdbdbd","init_delay":"3,10","enable_cross_sell_open":"1","icon":"0","custom_gift_image":"0","icon_color":"#555555","icon_bg_color":"#ffffff","icon_position":"0","bg_color_cross_sell":"#ffffff","bg_image_cross_sell":"0","text_color_cross_sell":"#9e9e9e","price_text_color_cross_sell":"#111111","save_price_text_color_cross_sell":"#111111","item_per_row":"4","limit":"8","select_template":"1","message_bought":"Frequently bought with {name_product}","coupon_position":"0","text_color_discount":"#111111","process_color":"#111111","process_background_color":"#bdbdbd","custom_css":"","key":""}';
		if ( ! get_option( '_woocommerce_boost_sales', '' ) ) {
			update_option( '_woocommerce_boost_sales', json_decode( $json_data, true ) );
		}
	}
}

new VI_WOO_BOOSTSALES();