<?php

namespace Omnipay\Judopay\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Judopay hosted Web Payment session create.
 *
 * Returns a `postUrl` (or similar) the caller redirects the customer to.
 * The customer enters card details on Judopay's hosted page; on
 * completion Judopay calls back to `successUrl` / `cancelUrl` and the
 * caller can fetch the receipt via TransactionRequest.
 *
 * Endpoint: POST webpayments/payments.
 */
class WebPaymentRequest extends AbstractRequest
{
    use JudopayClientTrait;

    public function getYourConsumerReference()
    {
        return $this->getParameter('yourConsumerReference');
    }

    public function setYourConsumerReference($value)
    {
        return $this->setParameter('yourConsumerReference', $value);
    }

    public function getYourPaymentReference()
    {
        return $this->getParameter('yourPaymentReference');
    }

    public function setYourPaymentReference($value)
    {
        return $this->setParameter('yourPaymentReference', $value);
    }

    public function getYourPaymentMetaData()
    {
        return $this->getParameter('yourPaymentMetaData');
    }

    public function setYourPaymentMetaData($value)
    {
        return $this->setParameter('yourPaymentMetaData', $value);
    }

    public function getSuccessUrl()
    {
        return $this->getParameter('successUrl');
    }

    public function setSuccessUrl($value)
    {
        return $this->setParameter('successUrl', $value);
    }

    public function getCancelUrl()
    {
        return $this->getParameter('cancelUrl');
    }

    public function setCancelUrl($value)
    {
        return $this->setParameter('cancelUrl', $value);
    }

    public function getData()
    {
        $this->validate('amount');

        return [
            'judoId'                => $this->getJudoId(),
            'yourConsumerReference' => $this->getYourConsumerReference(),
            'yourPaymentReference'  => $this->getYourPaymentReference(),
            'yourPaymentMetaData'   => $this->getYourPaymentMetaData(),
            'successUrl'            => $this->getSuccessUrl(),
            'cancelUrl'             => $this->getCancelUrl(),
            'amount'                => $this->getAmount(),
            'currency'              => $this->getCurrency(),
            'clientIpAddress'       => $this->getRealIpAddr(),
            'clientUserAgent'       => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ];
    }

    public function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // shared internet
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // proxy forward
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'] ?? '';
    }

    public function sendData($data)
    {
        return $this->postTo('webpayments/payments', $data, WebPaymentResponse::class);
    }
}
