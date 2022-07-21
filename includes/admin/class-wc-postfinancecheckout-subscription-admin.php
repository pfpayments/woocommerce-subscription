<?php
if (!defined('ABSPATH')) {
	exit();
}
/**
 * PostFinance Checkout WooCommerce
 *
 * This WooCommerce plugin enables to process payments with PostFinance Checkout (https://postfinance.ch/en/business/products/e-commerce/postfinance-checkout-all-in-one.html).
 *
 * @author customweb GmbH (http://www.customweb.com/)
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache Software License (ASL 2.0)
 */
/**
 * WC_PostFinanceCheckout_Subscription_Admin  class
 */
class WC_PostFinanceCheckout_Subscription_Admin {
	
	/**
	 * The single instance of the class.
	 *
	 * @var WC_PostFinanceCheckout_Subscription_Admin
	 */
	protected static $_instance = null;

	/**
	 * Main WooCommerce PostFinanceCheckout Plugin Admin Instance.
	 *
	 * Ensures only one instance of WC_PostFinanceCheckout_Subscription_Admin is loaded or can be loaded.
	 *
	 * @return WC_PostFinanceCheckout_Subscription_Admin - Main instance.
	 */
	public static function instance(){
		if (self::$_instance === null) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * WC PostFinanceCheckout Admin Constructor.
	 */
	protected function __construct(){
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes(){
	    require_once (WC_POSTFINANCECHECKOUT_SUBSCRIPTION_ABSPATH . 'includes/admin/class-wc-postfinancecheckout-subscription-admin-notices.php');
	}

	private function init_hooks(){		
		add_action('admin_init', array(
			$this,
			'handle_modules_active'
		));

	}
	
	public function handle_modules_active(){
		// Subscription plugin or base plugin not activated
	    if (!is_plugin_active('woocommerce-subscriptions/woocommerce-subscriptions.php') || !is_plugin_active('woo-postfinancecheckout/woocommerce-postfinancecheckout.php'))
		{
			// Deactivate myself
		    deactivate_plugins(WC_POSTFINANCECHECKOUT_SUBSCRIPTION_PLUGIN_BASENAME);
			add_action('admin_notices', array(
				'WC_PostFinanceCheckout_Subscription_Admin_Notices',
				'plugin_deactivated'
			));
		}
		
	}
	
}

WC_PostFinanceCheckout_Subscription_Admin::instance();
