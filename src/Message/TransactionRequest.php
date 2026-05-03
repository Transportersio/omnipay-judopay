<?php

namespace Omnipay\Judopay\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Judopay Web Payment lookup.
 *
 * Looks up a previously-created hosted-payment session by its reference,
 * to discover whether the customer completed the flow and to retrieve
 * the receipt/transaction data.
 *
 * Endpoint: GET webpayments/{reference}.
 *
 * Note: the SDK called this `WebPayments\Transaction::find($reference)`.
 * Param/getter names use UpperCamelCase (`Reference`, `ReceiptId`,
 * `CardToken`) for backwards-compat with existing callers.
 */
class TransactionRequest extends AbstractRequest
{
    use JudopayClientTrait;

    public function getReceiptId()
    {
        return $this->getParameter('ReceiptId');
    }

    public function setReceiptId($value)
    {
        return $this->setParameter('ReceiptId', $value);
    }

    public function getCardToken()
    {
        return $this->getParameter('CardToken');
    }

    public function setCardToken($value)
    {
        return $this->setParameter('CardToken', $value);
    }

    public function getReference()
    {
        return $this->getParameter('Reference');
    }

    public function setReference($value)
    {
        return $this->setParameter('Reference', $value);
    }

    public function getData()
    {
        return [
            'ReceiptId' => $this->getReceiptId(),
            'CardToken' => $this->getCardToken(),
            'Reference' => $this->getReference(),
        ];
    }

    public function sendData($data)
    {
        try {
            $response = $this->client()->get('webpayments/' . rawurlencode((string) $data['Reference']));
        } catch (\RuntimeException $e) {
            $response = [
                'result'        => 'Failure',
                'errorMessage'  => $e->getMessage(),
                'errorDetails'  => isset($e->details) ? $e->details : null,
            ];
        }
        return $this->response = new TransactionResponse($this, $response);
    }
}
