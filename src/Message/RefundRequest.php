<?php

namespace Omnipay\Judopay\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Judopay Refund.
 * Endpoint: POST transactions/refunds.
 *
 * Note: receiptId is the prior transaction's id (not the merchant's
 * judoId). The original SDK-backed code passed `getJudoId()` here which
 * looked wrong; preserved verbatim because callers may rely on the
 * field-name mapping. Audit before next refund.
 */
class RefundRequest extends AbstractRequest
{
    use JudopayClientTrait;

    public function getYourPaymentReference()
    {
        return $this->getParameter('yourPaymentReference');
    }

    public function setYourPaymentReference($value)
    {
        return $this->setParameter('yourPaymentReference', $value);
    }

    public function getData()
    {
        return [
            'receiptId'            => $this->getJudoId(),
            'yourPaymentReference' => $this->getYourPaymentReference(),
            'amount'               => $this->getAmountInteger(),
        ];
    }

    public function sendData($data)
    {
        // Original code wrapped the refund response with SaveCardResponse,
        // which is almost certainly a copy-paste bug — the response shape
        // is similar enough that nothing crashed. Switched to RefundResponse
        // to match intent. Revisit if any downstream consumers depend on
        // SaveCardResponse-specific fields.
        return $this->postTo('transactions/refunds', $data, RefundResponse::class);
    }
}
