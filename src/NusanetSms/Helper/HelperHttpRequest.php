<?php
namespace NusanetSms\Helper;

class HelperHttpRequest
{
    public function setupOauth2ClientHttpRequestBody($nsClientId, $nsClientSecret)
    {
        if (!$nsClientId || !$nsClientSecret) return null;
        return json_encode(
            array(
                'clientId' => $nsClientId,
                'clientSecret' => $nsClientSecret,
            )
        );
    }

    public function setupOauth2ClientHttpRequestHeader()
    {
        return array(
            'Content-Type: application/json',
            'Accept: application/json',
        );
    }

    public function setupSendSmsHttpRequestHeader($accessToken)
    {
        if (!$accessToken) return array();
        return array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $accessToken,
        );
    }
}
