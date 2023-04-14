<?php
/**
 * Copyright © Hibrido. All rights reserved.
 * https://www.hibrido.com.br/
 */
declare(strict_types=1);

namespace Hibrido\RewriteAmastyDeliveryDate\Rewrite\Plugin\Amasty\Deliverydate\Plugin\Checkout;

use Amasty\Deliverydate\Api\Data\DeliverydateInterface;
use Amasty\Deliverydate\Helper\Data;
use Magento\Checkout\Api\Data\ShippingInformationExtension;
use Magento\Checkout\Model\ShippingInformationManagement as ModelShippingInformationManagement;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Hibrido\RewriteAmastyDeliveryDate\Model\DeliveryDateManagement;

class ShippingInformationManagement
{
    /**
     * @var Data
     */
    private Data $amHelper;

    /**
     * @var DeliveryDateManagement
     */
    private DeliveryDateManagement $deliveryDateManagement;

    /**
     * @param Data $amHelper
     * @param DeliveryDateManagement $DeliveryDateManagement
     */
    public function __construct(
        Data                   $amHelper,
        DeliveryDateManagement $deliveryDateManagement
    )
    {
        $this->amHelper = $amHelper;
        $this->deliveryDateManagement = $deliveryDateManagement;
    }

    /**
     * @param ModelShippingInformationManagement $subject
     * @param \Closure $proceed
     * @param $cartId
     * @param ShippingInformationInterface $addressInformation
     * @return mixed
     */
    public function aroundSaveAddressInformation(
        ModelShippingInformationManagement $subject,
        \Closure                           $proceed,
                                           $cartId,
        ShippingInformationInterface       $addressInformation
    )
    {
        $extAttributes = $addressInformation->getExtensionAttributes();
        if ($extAttributes instanceof ShippingInformationExtension) {
            $data = [DeliverydateInterface::DATE => $extAttributes->getAmdeliverydateDate()];

            if ($this->amHelper->isDeliveryTimeEnabled()) {
                $data[DeliverydateInterface::TINTERVAL_ID] = $extAttributes->getAmdeliverydateTime();
            }

            if ($this->amHelper->isDeliveryCommentEnabled()) {
                $data[DeliverydateInterface::COMMENT] = $extAttributes->getAmdeliverydateComment();
            }

            $this->deliveryDateManagement->setDeliveryDate($data);
        }

        return $proceed($cartId, $addressInformation);
    }
}
