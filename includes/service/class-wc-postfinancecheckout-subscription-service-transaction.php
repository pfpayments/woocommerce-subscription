<?php

if (!defined('ABSPATH')) {
	exit(); // Exit if accessed directly.
}

/**
 * This service provides functions to deal with PostFinance Checkout transactions.
 */
class WC_PostFinanceCheckout_Subscription_Service_Transaction extends WC_PostFinanceCheckout_Service_Transaction {

	/**
	 * Creates a transaction for the given order.
	 *
	 * @param WC_Order $order
	 * @param int $token_id
	 * @return \PostFinanceCheckout\Sdk\Model\Transaction
	 */
	public function create_transaction_by_renewal_order(WC_Order $order, $order_total, $token_id){
	    $space_id = get_option(WooCommerce_PostFinanceCheckout::CK_SPACE_ID);
	    $create_transaction = new \PostFinanceCheckout\Sdk\Model\TransactionCreate();
	    $create_transaction->setCustomersPresence(\PostFinanceCheckout\Sdk\Model\CustomersPresence::VIRTUAL_PRESENT);
		$space_view_id = get_option(WooCommerce_PostFinanceCheckout::CK_SPACE_VIEW_ID);
		if (is_numeric($space_view_id)) {
			$create_transaction->setSpaceViewId($space_view_id);
		}
	    $create_transaction->setToken($token_id);
	    $this->assemble_order_transaction_data($order, $create_transaction);
	    $this->set_modified_order_line_items($order, $order_total, $create_transaction);
	    
	    $create_transaction = apply_filters('wc_postfinancecheckout_subscription_create_transaction', $create_transaction, $order);
	    $transaction = $this->get_transaction_service()->create($space_id, $create_transaction);
	    $this->update_transaction_info($transaction, $order);
	    return $transaction;
	}
	
	/**
	 * Creates a transaction for the given order.
	 *
	 * @param WC_Order $order
	 * @param int $token_id
	 * @return \PostFinanceCheckout\Sdk\Model\Transaction
	 */
	public function update_transaction_by_renewal_order(WC_Order $order, $order_total, $token_id, \PostFinanceCheckout\Sdk\Model\Transaction $transaction){
	    $last = new \PostFinanceCheckout\Sdk\VersioningException();
	    for ($i = 0; $i < 5; $i++) {
	        try {
	            $pending_transaction = new \PostFinanceCheckout\Sdk\Model\TransactionPending();
	            $pending_transaction->setId($transaction->getId());
	            $pending_transaction->setVersion($transaction->getVersion());
	            $pending_transaction->setToken($token_id);
	            $this->assemble_order_transaction_data($order, $pending_transaction);
	            $this->set_modified_order_line_items($order, $order_total, $pending_transaction);
	            $pending_transaction = apply_filters('wc_postfinancecheckout_subscription_update_transaction', $pending_transaction, $order);
	            return $this->get_transaction_service()->update($transaction->getLinkedSpaceId(), $pending_transaction);
	        }
	        catch (\PostFinanceCheckout\Sdk\VersioningException $e) {
	            $last = $e;
	        }
	    }
	    throw $last;
	}
	
	protected function set_modified_order_line_items(WC_Order $order, $order_total, \PostFinanceCheckout\Sdk\Model\AbstractTransactionPending $transaction){
	    $raw_items = WC_PostFinanceCheckout_Service_Line_Item::instance()->get_raw_items_from_order($order);
	    $cleaned = WC_PostFinanceCheckout_Helper::instance()->cleanup_line_items($raw_items, $order_total, $order->get_currency());
	    $transaction->setLineItems($cleaned);
	    
	}
	
	
	public function process_transaction_without_user_interaction($space_id, $transaction_id){
	    return $this->get_transaction_service()->processWithoutUserInteraction($space_id, $transaction_id);
	}
	
	
	
	
	protected function set_order_line_items(WC_Order $order, \PostFinanceCheckout\Sdk\Model\AbstractTransactionPending $transaction){
	}
	

}