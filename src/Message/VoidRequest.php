<?php

namespace Omnipay\Judopay\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Judopay Void (cancel a preauth before collection).
 *
 * Endpoint: POST transactions/voids.
 *
 * Note: the legacy SDK code called `getModel('Void')` — but the SDK has
 * never had a Void model class. That call would throw, so this code
 * path was effectively dead under the old wiring. Keeping the omnipay
 * void() entry-point and pointing it at the correct REST endpoint so
 * the action actually works after the SDK migration.
 */
class VoidRequest extends AbstractRequest
{
    use JudopayClientTrait;

    public function getData()
    {
        return [
            'judoId'    => $this->getJudoId(),
            'receiptId' => $this->getJudoId(),
            'amount'    => $this->getAmountInteger(),
        ];
    }

    public function sendData($data)
    {
        return $this->postTo('transactions/voids', $data, VoidResponse::class);
    }
}
