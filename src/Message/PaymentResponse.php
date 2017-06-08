<?php

namespace Transportersio\OmnipayJudopay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Judopay Purchase Response
 */
class PaymentResponse extends AbstractResponse implements RedirectResponseInterface
{

    public function isSuccessful()
    {
        if ($this->data['result'] == 'Success') {
            return true;
        } else {
            return false;
        }
    }

    public function isRedirect()
    {
        return (isset($this->getRequest()->getParameters()['returnUrl'])
            && $this->getRequest()->getParameters()['returnUrl'] != '') ? true : false;
    }

    public function getRedirectUrl()
    {
        return (isset($this->getRequest()->getParameters()['returnUrl'])
            && $this->getRequest()->getParameters()['returnUrl'] != '')
            ? $this->getRequest()->getParameters()['returnUrl'] : false;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return $this->getData();
    }
}
