<?php
namespace NusanetSms\Sender;

use NusanetSms\Http\Request;
use NusanetSms\Exception\NusanetSmsException;
use NusanetSms\Helper\HelperHttpRequest;

class SmsSender
{
    const NUSANET_SMS_URL_PATH = '/v1/sms';

    protected $smsData;
    protected $accessToken;

    public function __construct($accessToken, $smsData)
    {
        if (!is_array($smsData)) {
            throw new NusanetSmsException('Malformed sms data');
        }
        $this->accessToken = $accessToken;
        $this->smsData     = $smsData;
    }

    public function getSmsUriPath()
    {
        return static::NUSANET_SMS_URL_PATH;
    }

    public function sendSms()
    {
        $nsHelperHttpRequest = new HelperHttpRequest();
        $sendSmsHttpHeader   = $nsHelperHttpRequest
            ->setupSendSmsHttpRequestHeader($this->accessToken);
        $sendSmsHttpBody     = json_encode($this->smsData);
        $sendSmsUriPath      = $this->getSmsUriPath();
        $nsSmsRequest        = new Request($sendSmsUriPath, $sendSmsHttpHeader, $sendSmsHttpBody);
        return $nsSmsRequest->makeHttpRequest();
    }
}
