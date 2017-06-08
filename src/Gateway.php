<?php

namespace Transportersio\OmnipayJudopay;

use Omnipay\Common\AbstractGateway;
use Judopay;

/**
 * Judopay Gateway
 *
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
        return $this->createRequest('\Transportersio\OmnipayJudopay\Message\PreAuthorizationRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Transportersio\OmnipayJudopay\Message\WebPaymentRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Transportersio\OmnipayJudopay\Message\TransactionRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Transportersio\OmnipayJudopay\Message\RefundRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('\Transportersio\OmnipayJudopay\Message\VoidRequest', $parameters);
    }

    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Transportersio\OmnipayJudopay\Message\RegisteringCardRequest', $parameters);
    }

    public function updateCard(array $parameters = array())
    {
        return $this->createRequest('\Transportersio\OmnipayJudopay\Message\SaveCardRequest', $parameters);
    }
}
