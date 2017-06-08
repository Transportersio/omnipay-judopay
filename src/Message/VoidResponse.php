<?php

namespace Transportersio\OmnipayJudopay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Judopay Purchase Response
 */
class VoidResponse extends AbstractResponse implements RedirectResponseInterface
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
        return true;
    }

    public function getRedirectUrl()
    {
        return true;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }
}
