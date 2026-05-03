<?php

namespace Omnipay\Judopay\Message;

use Omnipay\Judopay\Client;

/**
 * Shared accessors and Client factory for the Judopay omnipay messages.
 *
 * Each Request class in this package needs identical apiToken / apiSecret /
 * judoId / testMode getters/setters and an identical "build a Judopay
 * client and POST to a resource path" sendData() body. This trait
 * collapses them.
 */
trait JudopayClientTrait
{
    public function getApiToken()
    {
        return $this->getParameter('apiToken');
    }

    public function setApiToken($value)
    {
        return $this->setParameter('apiToken', $value);
    }

    public function getApiSecret()
    {
        return $this->getParameter('apiSecret');
    }

    public function setApiSecret($value)
    {
        return $this->setParameter('apiSecret', $value);
    }

    public function getJudoId()
    {
        return $this->getParameter('judoId');
    }

    public function setJudoId($value)
    {
        return $this->setParameter('judoId', $value);
    }

    /**
     * Build a configured Client instance from the apiToken/apiSecret/testMode
     * parameters that are populated on every Request via the Gateway.
     */
    protected function client(): Client
    {
        return new Client(
            (string) $this->getApiToken(),
            (string) $this->getApiSecret(),
            ! ((bool) $this->getTestMode()),
            '6.0'
        );
    }

    /**
     * Wrap a POST to Judopay so each concrete Request class is just
     *   `return $this->postTo('transactions/preauths', $data);`
     * and can ignore the boilerplate exception swallowing the SDK had.
     *
     * Failure mode: returns a SimpleResponse-like array shaped with
     * 'result' => 'Failure' and an 'errorMessage' key, which the
     * downstream Response classes already understand.
     */
    protected function postTo(string $resourcePath, array $data, string $responseClass)
    {
        try {
            $response = $this->client()->post($resourcePath, $data);
        } catch (\RuntimeException $e) {
            $response = [
                'result'        => 'Failure',
                'errorMessage'  => $e->getMessage(),
                'errorDetails'  => isset($e->details) ? $e->details : null,
            ];
        }
        return $this->response = new $responseClass($this, $response);
    }
}
