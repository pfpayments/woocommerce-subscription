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
 * This is the autoloader for PostFinance Checkout Subscription classes.
 */
class WC_PostFinanceCheckout_Subscription_Autoloader {
	
	/**
	 * Path to the includes directory.
	 *
	 * @var string
	 */
	private $include_path = '';

	/**
	 * The Constructor.
	 */
	public function __construct(){
		spl_autoload_register(array(
			$this,
			'autoload' 
		));
		$this->include_path = WC_POSTFINANCECHECKOUT_SUBSCRIPTION_ABSPATH . 'includes/';
	}

	/**
	 * Take a class name and turn it into a file name.
	 *
	 * @param  string $class
	 * @return string
	 */
	private function get_file_name_from_class($class){
		return 'class-' . str_replace('_', '-', $class) . '.php';
	}

	/**
	 * Include a class file.
	 *
	 * @param  string $path
	 * @return bool successful or not
	 */
	private function load_file($path){
		if ($path && is_readable($path)) {
			include_once ($path);
			return true;
		}
		return false;
	}

	/**
	 * Auto-load WC PostFinanceCheckout classes on demand to reduce memory consumption.
	 *
	 * @param string $class
	 */
	public function autoload($class){
		$class = strtolower($class);
		
		if (0 !== strpos($class, 'wc_postfinancecheckout_subscription')) {
			return;
		}
		
		$file = $this->get_file_name_from_class($class);
		$path = '';
		
		if (strpos($class, 'wc_postfinancecheckout_subscription_service') === 0) {
			$path = $this->include_path . 'service/';
		}
		elseif (strpos($class, 'wc_postfinancecheckout_subscription_entity') === 0) {
			$path = $this->include_path . 'entity/';
		}
		elseif (strpos($class, 'wc_postfinancecheckout_subscription_provider') === 0) {
			$path = $this->include_path . 'provider/';
		}
		elseif (strpos($class, 'wc_postfinancecheckout_subscription_webhook') === 0) {
			$path = $this->include_path . 'webhook/';
		}
		elseif (strpos($class, 'wc_postfinancecheckout_subscription_admin') === 0) {
			$path = $this->include_path . 'admin/';
		}
		
		if (empty($path) || !$this->load_file($path . $file)) {
			$this->load_file($this->include_path . $file);
		}
		
		$this->load_file($this->include_path . $file);
	}
}

new WC_PostFinanceCheckout_Subscription_Autoloader();
