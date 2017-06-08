<?php

namespace Omnipay\WorldPay;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setApiToken('token');
        $this->gateway->setApiSecret('secret');
        $this->gateway->setJudoId('123-456');
        $this->gateway->setUseProduction(false);

        $formData = array(
            'number' => '4976000000003436',
            'expiryMonth' => '12',
            'expiryYear' => '2022',
            'cvv' => '452'
        );

        $this->options = array(
            'yourConsumerReference' => '12345',
            'yourPaymentReference' => '12345',
            'yourPaymentMetaData' => array(),
            'amount' => '10.00',
            'card' => $formData,
            'returnUrl' => 'https://example.com/return'
        );
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
    }

}
