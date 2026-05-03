<?php

namespace Omnipay\Judopay\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Judopay Save Card (no transaction).
 * Endpoint: POST transactions/savecard.
 */
class SaveCardRequest extends AbstractRequest
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
        return [
            'judoId'                => $this->getJudoId(),
            'yourConsumerReference' => $this->getYourConsumerReference(),
            'yourPaymentReference'  => $this->getYourPaymentReference(),
            'cardNumber'            => $this->getCard()->getNumber(),
            'expiryDate'            => $this->getCard()->getExpiryDate('m/y'),
            'cv2'                   => $this->getCard()->getCvv(),
        ];
    }

    public function sendData($data)
    {
        return $this->postTo('transactions/savecard', $data, SaveCardResponse::class);
    }
}
