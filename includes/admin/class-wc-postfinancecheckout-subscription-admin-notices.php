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
 * WC PostFinanceCheckout Subscription Admin Notices class
 */
class WC_PostFinanceCheckout_Subscription_Admin_Notices {

	public static function migration_failed_notices(){
	    require_once WC_POSTFINANCECHECKOUT_SUBSCRIPTION_ABSPATH.'views/admin-notices/migration-failed.php';
	}
	
	public static function plugin_deactivated(){
	    require_once WC_POSTFINANCECHECKOUT_SUBSCRIPTION_ABSPATH.'views/admin-notices/plugin-deactivated.php';
	}
}