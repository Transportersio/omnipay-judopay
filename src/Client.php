<?php

namespace Omnipay\Judopay;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;

/**
 * Direct REST client for Judopay's Partner API.
 *
 * Replaces the legacy `judopay/judopay-sdk` package, which Judopay
 * deprecated and stopped maintaining. The endpoints, authentication
 * (HTTP Basic with apiToken:apiSecret), and `api-version` header
 * convention are documented at:
 *
 *     https://docs.judopay.com/migrating-from-php-sdk-to-direct-api-integration
 *     https://docs.judopay.com/api-reference
 *
 * Migration notes:
 *  - `transactions/preauths` and `transactions/payments` accept the same
 *    payload as the old SDK Preauth / Payment models.
 *  - `transactions/refunds` requires { receiptId, amount } where receiptId
 *    is the prior transaction's identifier returned in `receiptId`.
 *  - Voids: in API v6.0+ the dedicated `transactions/voids` endpoint accepts
 *    { receiptId } to cancel a preauth that hasn't been collected.
 *  - Card-on-file: `transactions/registercard` and `transactions/savecard`.
 *  - Hosted/web payment: `webpayments/payments`, `webpayments/preauths`,
 *    GET `webpayments/{reference}` to look up a returned session.
 *
 * Errors: Judopay returns 4xx with { errorType, errorMessage, modelErrors }.
 * We surface those as a structured array on the response so the caller
 * can render them — same shape the legacy SDK exposed via
 * `\Judopay\Exception\ValidationError::getSummary()`.
 */
class Client
{
    /** @var string */
    private $baseUrl;

    /** @var string */
    private $apiToken;

    /** @var string */
    private $apiSecret;

    /** @var string */
    private $apiVersion;

    /** @var Guzzle|null */
    private $guzzle;

    public function __construct(string $apiToken, string $apiSecret, bool $useProduction, string $apiVersion = '6.0', ?Guzzle $guzzle = null)
    {
        $this->apiToken   = $apiToken;
        $this->apiSecret  = $apiSecret;
        $this->apiVersion = $apiVersion;
        $this->baseUrl    = $useProduction
            ? 'https://api.judopay.com/'
            : 'https://api-sandbox.judopay.com/';
        $this->guzzle     = $guzzle;
    }

    /**
     * POST a JSON body to a Judopay endpoint and return the decoded
     * response as an associative array (matches what the SDK returned).
     *
     * @param string $resourcePath e.g. 'transactions/preauths'
     * @param array  $data         Request body
     * @return array               Response payload (always associative, never null)
     * @throws \RuntimeException   On 4xx/5xx — exception message is the
     *                             best-effort errorMessage from the response,
     *                             and the full response body is in $e->details.
     */
    public function post(string $resourcePath, array $data): array
    {
        return $this->request('POST', $resourcePath, $data);
    }

    public function get(string $resourcePath): array
    {
        return $this->request('GET', $resourcePath);
    }

    public function delete(string $resourcePath): array
    {
        return $this->request('DELETE', $resourcePath);
    }

    private function request(string $method, string $resourcePath, ?array $body = null): array
    {
        $client = $this->guzzle ?: new Guzzle();
        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
                'Api-Version'  => $this->apiVersion,
                'Authorization'=> 'Basic ' . base64_encode($this->apiToken . ':' . $this->apiSecret),
                'User-Agent'   => 'omnipay-judopay/2.x (Transporters.io)',
            ],
            'http_errors' => false,
            'timeout'     => 30,
        ];
        if ($body !== null) {
            $options['json'] = $body;
        }

        try {
            $response = $client->request($method, $this->baseUrl . ltrim($resourcePath, '/'), $options);
        } catch (ConnectException $e) {
            throw new \RuntimeException('Judopay connection failed: ' . $e->getMessage(), 0, $e);
        } catch (BadResponseException $e) {
            // http_errors=false should prevent these, but keep for safety.
            throw new \RuntimeException('Judopay HTTP error: ' . $e->getMessage(), 0, $e);
        }

        $rawBody = (string) $response->getBody();
        $decoded = $rawBody === '' ? [] : (json_decode($rawBody, true) ?: []);

        $status = $response->getStatusCode();
        if ($status >= 400) {
            $msg = $decoded['errorMessage'] ?? ('HTTP ' . $status);
            $ex = new \RuntimeException("Judopay API error: {$msg}");
            $ex->details = $decoded;
            throw $ex;
        }

        return $decoded;
    }
}
