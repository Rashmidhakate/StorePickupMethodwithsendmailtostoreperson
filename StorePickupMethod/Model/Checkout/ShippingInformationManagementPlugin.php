<?php

namespace Brainvire\StorePickupMethod\Model\Checkout;

use \Magento\Sales\Model\OrderFactory;                                                                                                                                                                                                     
class ShippingInformationManagementPlugin
{

    protected $quoteRepository;

    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Magento\Checkout\Model\Session $checkoutSession,
        OrderFactory $orderFactory
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->_checkoutSession = $checkoutSession;
        $this->orderFactory = $orderFactory;
    }                                               

    /**
     * 
     *
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        $extAttributes = $addressInformation->getShippingAddress()->getExtensionAttributes();
        $storeAddress = $extAttributes->getStoreAddress();
        if($storeAddress != "please select source"){
            $methodCode = $addressInformation->getShippingMethodCode();
            if($methodCode == 'storepickupmethod'){
                $quote = $this->quoteRepository->getActive($cartId);
                $quote->setStoreAddress($storeAddress);
            }
        }
    }
}