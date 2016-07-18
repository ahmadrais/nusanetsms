<?php
namespace NusanetSms\Http;

use NusanetSms\Exception\NusanetSmsException;

class Request
{
    const NUSANET_SMS_API_URI = 'https://api-sms.nusa.net.id';

    protected $nsHttpRequestHeaders;
    protected $nsHttpRequestBody;
    protected $nsHttpUriPath;
    protected $lastHttpResponseCode;

    public function __construct($nsHttpUriPath, $nsHttpRequestHeaders, $nsHttpRequestBody)
    {
        $this->nsHttpRequestHeaders = $nsHttpRequestHeaders;
        $this->nsHttpRequestBody    = $nsHttpRequestBody;
        $this->nsHttpUriPath        = $nsHttpUriPath;
    }

    public function getUriUrl()
    {
        return static::NUSANET_SMS_API_URI;
    }

    public function makeHttpRequest()
    {
        $nsUriUrl = $this->getUriUrl();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $nsUriUrl . $this->nsHttpUriPath);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->nsHttpRequestHeaders);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->nsHttpRequestBody);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        $response = curl_exec($curl);
        $httpResponseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $this->lastHttpResponseCode = $httpResponseCode;
        if (curl_error($curl)) {
            $response = json_encode(array(
                'type'   => 'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
                'title'  => 'Internal Server Error',
                'status' => 500,
                'detail' => 'Internal Server Error',
            ));
        }
        curl_close($curl);
        return $response;
    }

    public function getLastHttpResponseCode()
    {
        return $this->lastHttpResponseCode;
    }
}
