<?php

namespace Omnipay\JudoPay;

use Omnipay\Common\AbstractGateway;
use Judopay;

/**
 * WorldPay Gateway
 *
 * @link http://www.worldpay.com/support/kb/bg/htmlredirect/rhtml.html
 */
class Gateway extends AbstractGateway
{

    public $judopay;

    public function getName()
    {
        return 'JudoPay';
    }

    public function getDefaultParameters()
    {
        return array(
            'apiToken' => '',
            'apiSecret' => '',
            'judoId' => '',
            'useProduction' => false
        );
    }

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

    public function getUseProduction()
    {
        return $this->getParameter('useProduction');
    }

    public function setUseProduction($value)
    {
        return $this->setParameter('useProduction', $value);
    }

    public function authorize(array $parameters = array())
    {

        echo "Before authorize<br />";
        $this->judopay = new Judopay(
            array(
                'apiToken' => 'jwmXGbpb87xvDM4B',
                'apiSecret' => '601dc0a93d2752f5041bdb9a53dc1bf0b4e8ef0f1b03f737416fcf3be1a20b7d',
                'judoId' => '100826-205',
                'useProduction' => false
            )
        );

        echo "After authorize<br />";

        $payment = $this->judopay->getModel('Payment');
        $payment->setAttributeValues(
            array(
                'judoId' => '100826-205',
                'yourConsumerReference' => '12345',
                'yourPaymentReference' => '12345',
                'amount' => 1.01,
                'currency' => 'GBP',
                'cardNumber' => '4976000000003436',
                'expiryDate' => '12/22',
                'cv2' => 452
            )
        );

        try {
            $response = $payment->create();
            if ($response['result'] === 'Success') {
                echo 'Payment succesful';
            } else {
                echo 'There were some problems while processing your payment';
            }
        } catch (\Judopay\Exception\ValidationError $e) {
            echo $e->getSummary();
        } catch (\Judopay\Exception\ApiException $e) {
            echo $e->getSummary();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return "TEST";
        //return $this->createRequest('\Omnipay\JudoPay\Message\PreAuthorization', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\WorldPay\Message\CompletePurchaseRequest', $parameters);
    }
}
