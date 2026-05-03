<?php

namespace Omnipay\Judopay\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Judopay Pre-Authorization
 *
 * Reserves funds without immediate capture. Endpoint: POST transactions/preauths.
 * Capture later via a Collection (omnipay capture()) or release via Void.
 */
class PreAuthorizationRequest extends AbstractRequest
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

    public function getData()
    {
        $this->validate('amount');

        return [
            'judoId'                => $this->getJudoId(),
            'yourConsumerReference' => $this->getYourConsumerReference(),
            'yourPaymentReference'  => $this->getYourPaymentReference(),
            'amount'                => $this->getAmountInteger(),
            'currency'              => $this->getCurrency(),
            'cardNumber'            => $this->getCard()->getNumber(),
            'expiryDate'            => $this->getCard()->getExpiryDate('m/y'),
            'cv2'                   => $this->getCard()->getCvv(),
        ];
    }

    public function sendData($data)
    {
        return $this->postTo('transactions/preauths', $data, PreAuthorizationResponse::class);
    }
}
