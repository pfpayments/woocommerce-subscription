<?php
if (!defined('ABSPATH')) {
	exit(); // Exit if accessed directly.
}
/**
 * This service provides functions to deal with chargeflows
 */
class WC_PostFinanceCheckout_Subscription_Service_ChargeFlow extends WC_PostFinanceCheckout_Service_Abstract {
    
    /**
     * The transaction API service.
     *
     * @var \PostFinanceCheckout\Sdk\Service\ChargeFlowService
     */
    private $chargeflow_service;
    
    
    /**
     * Returns the transaction API service.
     *
     * @return \PostFinanceCheckout\Sdk\Service\ChargeFlowService
     */
    protected function get_chargeflow_service(){
        if ($this->chargeflow_service === null) {
            $this->chargeflow_service = new \PostFinanceCheckout\Sdk\Service\ChargeFlowService(WC_PostFinanceCheckout_Helper::instance()->get_api_client());
        }
        return $this->chargeflow_service;
    }
    
    public function apply_chargeflow_on_transaction($space_id, $transaction_id){
        return $this->get_chargeflow_service()->applyFlow($space_id, $transaction_id);
    }
}