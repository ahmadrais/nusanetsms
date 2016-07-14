<?php
namespace NusanetSms;

use NusanetSms\Exception\NusanetSmsException;

class NusanetSmsApp
{
    protected $nsClientId;
    protected $nsClientSecret;

    public function __construct($nsClientId, $nsClientSecret)
    {
        if (!is_string($nsClientId)) {
            throw new NusanetSmsException('nusnsetSmsClientId harus string');
        }
        if (!is_string($nsClientSecret)) {
            throw new NusanetSmsException('nusnsetSmsClientSecret harus string');
        }
        $this->nsClientId = $nsClientId;
        $this->nsClientSecret = $nsClientSecret;
    }

    public function getClientId()
    {
        return $this->nsClientId;
    }

    public function getClientSecret()
    {
        return $this->nsClientSecret;
    }
}
