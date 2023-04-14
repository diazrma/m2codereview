<?php
/**
 * Copyright © Hibrido. All rights reserved.
 * https://www.hibrido.com.br/
 */
declare(strict_types=1);

namespace Hibrido\RewriteAmastyDeliveryDate\Rewrite\Plugin;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderManagementInterface;
use Amasty\Deliverydate\Model\DeliverydateFactory;
use Magento\Sales\Model\OrderFactory;
use Hibrido\RewriteAmastyDeliveryDate\Model\DeliveryDateManagement;

/**
 * Class OrderManagement
 */
class OrderManagement
{

    /**
     * @var OrderFactory
     */
    private OrderFactory $orderFactory;

    /**
     * @var DeliverydateFactory
     */
    private DeliverydateFactory $deliverydateFactory;

    /**
     * @var DeliveryDateManagement
     */
    private DeliveryDateManagement $deliveryDateManagement;

    /**
     * @param DeliverydateFactory $deliverydateFactoryer
     * @param DeliveryDateManagement $DeliveryDateManagement
     * @param OrderFactory $orderFactory
     */
    public function __construct(
        DeliverydateFactory    $deliverydateFactory,
        DeliveryDateManagement $deliveryDateManagement,
        OrderFactory           $orderFactory
    )
    {
        $this->deliverydateFactory = $deliverydateFactory;
        $this->deliveryDateManagement = $deliveryDateManagement;
        $this->orderFactory = $orderFactory;
    }

    /**
     * @param OrderManagementInterface $subject
     * @param OrderInterface $result
     * @return OrderInterface
     * @throws \Exception
     */
    public function afterPlace(
        OrderManagementInterface $subject,
        OrderInterface           $result
    )
    {
        $orderId = $result->getIncrementId();

        $data = $this->deliveryDateManagement->getDeliveryDate();

        if ($orderId) {
            if (is_array($data) && !is_null($data['date'])) {
                $order = $this->orderFactory->create()->loadByIncrementId($orderId);
                /** @var \Amasty\Deliverydate\Model\Deliverydate $deliveryDate */
                $deliveryDate = $this->deliverydateFactory->create();
                $deliveryDate->prepareForSave($data, $order);
                $deliveryDate->validate($order);
                $deliveryDate->save();
            }
        }
        return $result;
    }
}
