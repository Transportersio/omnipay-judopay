<?php

namespace Omnipay\WorldPay\Message;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    protected function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $formData = array('number' => '4976000000003436', 'expiryMonth' => '12', 'expiryYear' => '2022', 'cvv' => '452');

        $this->request->initialize(
            array(
                'yourConsumerReference' => '12345',
                'yourPaymentReference' => '12345',
                'yourPaymentMetaData' => array(),
                'amount' => '10.00',
                'card' => $formData,
                'returnUrl' => 'https://example.com/return'
            )
        );
    }

    public function testGetData()
    {
        $this->request->initialize(
            array(
                'judoId' => '123-456',
                'yourConsumerReference' => '12345',
                'yourPaymentReference' => '12345',
                'amount' => '10.00',
                'currency' => 'GBP',
                'cardNumber' => '4976000000003436',
                'expiryDate' => '12/22',
                'cv2' => '452'
            )
        );

        $data = $this->request->getData();

        $this->assertSame('123-456', $data['judoId']);
        $this->assertSame('12345', $data['yourConsumerReference']);
        $this->assertSame('12345', $data['yourPaymentReference']);
        $this->assertSame('10.00', $data['amount']);
        $this->assertSame('GBP', $data['currency']);
        $this->assertSame('4976000000003436', $data['cardNumber']);
        $this->assertSame('12/22', $data['expiryDate']);
        $this->assertSame('452', $data['cv2']);
    }

    public function testGetDataTestMode()
    {
        $this->request->setTestMode(true);

        $data = $this->request->getData();

        $this->assertSame(100, $data['testMode']);
    }
}
