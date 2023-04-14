<?php
/**
 * Copyright © Hibrido. All rights reserved.
 * https://www.hibrido.com.br/
 */
declare(strict_types=1);

namespace Hibrido\RewriteAmastyDeliveryDate\Model;

use Amasty\Deliverydate\Api\Data\DeliverydateInterface;
use Magento\Checkout\Model\Session;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteRepository;

class DeliveryDateManagement
{
    const HB_AMASTY_DELIVERYDATE_DATE = 'hb_amasty_deliverydate_date';
    const HB_AMASTY_DELIVERYDATE_TINTERVAL_ID = 'hb_amasty_deliverydate_tinterval_id';
    const HB_AMASTY_DELIVERYDATE_COMMENT = 'hb_amasty_deliverydate_comment';

    /**
     * @var Session|Order
     */
    private Session $checkoutSession;

    /**
     * @var QuoteRepository
     */
    private QuoteRepository $quoteRepository;

    /**
     * @param Session $checkouSession
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(
        Session         $checkouSession,
        QuoteRepository $quoteRepository
    )
    {
        $this->checkoutSession = $checkouSession;
        $this->quoteRepository = $quoteRepository;
    }

    /** @param array $data */
    public function setDeliveryDate(array $data)
    {
        $quote = $this->getQuote();
        $quote->setData(self::HB_AMASTY_DELIVERYDATE_DATE, $data[DeliverydateInterface::DATE]);
        $quote->setData(self::HB_AMASTY_DELIVERYDATE_TINTERVAL_ID, $data[DeliverydateInterface::TINTERVAL_ID]);
        $quote->setData(self::HB_AMASTY_DELIVERYDATE_COMMENT, $data[DeliverydateInterface::COMMENT]);

        $this->quoteRepository->save($quote);
    }

    /** @return array */
    public function getDeliveryDate()
    {

        /** @var Quote $quote */
        $quote = $this->getQuote();

        return [
            'date' => $quote->getData('hb_amasty_deliverydate_date'),
            'tinterval_id' => $quote->getData('hb_amasty_deliverydate_tinterval_id'),
            'time' => $quote->getData('hb_amasty_deliverydate_time'),
            'comment' => $quote->getData('hb_amasty_deliverydate_comment')
        ];
    }

    /** @return Quote */
    private function getQuote()
    {
        $quoteId = $this->checkoutSession->getQuoteId();
        /** @var Quote $quote */
        return $this->quoteRepository->getActive($quoteId);
    }
}
