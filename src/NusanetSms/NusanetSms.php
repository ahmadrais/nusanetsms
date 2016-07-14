<?php
namespace NusanetSms;

use NusanetSms\Authentication\Oauth2Client;
use NusanetSms\Exception\NusanetSmsException;
use NusanetSms\NusanetSmsApp;
use NusanetSms\Http\Request;
use NusanetSms\Sender\SmsSender;

class NusanetSms
{
    const NUSANET_SMS_CLIENT_ID     = '';
    const NUSANET_SMS_CLIENT_SECRET = '';
    const NUSANET_SMS_LIBRARY_VER   = '1.0.0';

    protected $nsDefaultAccessToken;
    protected $nsDefaultRefreshToken;
    protected $nsDefaultAccessTokenExpired;
    protected $nsOauth2Client;
    protected $nsApp;

    public function __construct($nsConfig = array())
    {
        $nsConfig = array_merge(array(
            'nsClientId'     => static::NUSANET_SMS_CLIENT_ID,
            'nsClientSecret' => static::NUSANET_SMS_CLIENT_SECRET,
        ), $nsConfig);
        if (!$nsConfig['nsClientId']) {
            throw new NusanetSmsException('Untuk menjalankan nusaneSms library dibutuhkan clientId');
        }
        if (!$nsConfig['nsClientSecret']) {
            throw new NusanetSmsException('Untuk menjalankan nusanet sms library dibutuhkan clientSecret');
        }
        $this->nsApp = new NusanetSmsApp($nsConfig['nsClientId'], $nsConfig['nsClientSecret']);
    }

    public function getNusanetSmsLibraryVersion()
    {
        return static::NUSANET_SMS_LIBRARY_VER;
    }

    public function getNusanetSmsApp()
    {
        return $this->nsApp;
    }

    public function getOauth2Client()
    {
        if (!$this->nsOauth2Client instanceof Oauth2Client) {
            $nsOauth2Client = new Oauth2Client($this->getNusanetSmsApp());
            $requestTime = time();
            $response = json_decode($nsOauth2Client->getOauth2Client());
            if (
                is_object($response) &&
                property_exists($response, 'accessToken') &&
                property_exists($response, 'expiredIn') &&
                property_exists($response, 'tokenType') &&
                property_exists($response, 'scope') &&
                property_exists($response, 'refreshToken')) {

                $this->setDefaultAccessToken($response->accessToken);
                $this->setDefaultRefreshToken($response->refreshToken);
                $this->setDefaultAccessTokenExpired(date('Y-m-d H:i:s', $requestTime + $response->expiredIn));
                $this->setOauth2Client($response);
            }
        }
        return $this->nsOauth2Client;
    }

    public function setOauth2Client($nsOauth2Client)
    {
        $this->nsOauth2Client = $nsOauth2Client;
    }

    public function setDefaultAccessToken($accessToken)
    {
        $this->nsDefaultAccessToken = $accessToken;
    }

    public function getDefaultAccessToken()
    {
        return $this->nsDefaultAccessToken;
    }

    public function setDefaultRefreshToken($refreshToken)
    {
        $this->nsDefaultRefreshToken = $refreshToken;
    }

    public function getDefaultRefreshToken()
    {
        return $this->nsDefaultRefreshToken;
    }

    public function setDefaultAccessTokenExpired($expired)
    {
        $this->nsDefaultAccessTokenExpired = $expired;
    }

    public function getDefaultAccessTokenExpired()
    {
        return $this->nsDefaultAccessTokenExpired;
    }

    public function isAccessTokenExpired()
    {
        return (time() > strtotime($this->getDefaultAccessTokenExpired)) ? true : false;
    }

    public function sendSms($smsData, $accessToken = null)
    {
        $accessToken = ($accessToken) ?: $this->getDefaultAccessToken();
        $smsSender   = new SmsSender($accessToken, $smsData);
        return $smsSender->sendSms();
    }
}
