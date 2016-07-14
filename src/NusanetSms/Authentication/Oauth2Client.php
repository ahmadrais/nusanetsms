<?php
namespace NusanetSms\Authentication;

use NusanetSms\Http\Request;
use NusanetSms\Exception\NusanetSmsException;
use NusanetSms\Helper\HelperHttpRequest;
use NusanetSms\NusanetSmsApp;

class Oauth2Client
{
    const NUSANET_SMS_OAUTH_PATH = '/v1/oauth2';

    protected $nsAuthClientId;
    protected $nsAauthClientSecret;

    public function __construct($nsApp)
    {
        if (!$nsApp instanceof NusanetSmsApp) {
            throw new NusanetSmsException('Oauth2Client membutuhkan construct NusanetSmsApp');
        }
        $this->nsAuthClientId     = $nsApp->getClientId();
        $this->nsAuthClientSecret = $nsApp->getClientSecret();
    }

    public function getNusanetSmsOauthPath()
    {
        return static::NUSANET_SMS_OAUTH_PATH;
    }

    public function getOauth2Client()
    {
        $nsHelperHttpRequest = new HelperHttpRequest();
        $nsOauth2ClientRequestHttpBody = $nsHelperHttpRequest
            ->setupOauth2ClientHttpRequestBody(
                $this->nsAuthClientId, $this->nsAuthClientSecret);
        $nsOauth2ClientRequestHttpHeaders = $nsHelperHttpRequest
            ->setupOauth2ClientHttpRequestHeader();
        $nsOauth2ClientRequestUriPath = $this->getNusanetSmsOauthPath();
        $nsRequest = new Request($nsOauth2ClientRequestUriPath, $nsOauth2ClientRequestHttpHeaders,
            $nsOauth2ClientRequestHttpBody);
        $response = $nsRequest->makeHttpRequest();
        if ($nsRequest->getLastHttpResponseCode() != 200 || !is_string($response)) {
            throw new NusanetSmsException($response);
        }
        return $response;
    }
}
