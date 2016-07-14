<?php

/**
 * required autoload nusanetSms Library
 *
 */

if (file_exists('./vendor/autoload.php')) {
    require_once('./vendor/autoload.php');
} else {
    require_once('./src/NusanetSms/autoload.php');
}

/*
 * initialize clientId & clientSecret nusanetSms
 */

$nusanetSmsCredential = array(
    'nsClientId' => 'ganti dengan nusanetSms Client Id',
    'nsClientSecret' => 'ganti dengan nusanetSms Client Secret',
);


/*
 * instance nusanetSms main class
 */
$nusanetSms = new NusanetSms\NusanetSms($nusanetSmsCredential);

/*
 * ambil access token yang telah disimpaan jika ada
 * access token akan expired 1 jam setelah di request
 * jika tidak ada access token, maka silahkan request access token baru
 */
$accessToken = 'jika ada akses token tersimpan';
if (!$accessToken) {
   $oauthClientData = $nusanetSms->getOauth2Client();
   $accessToken  = $nusanetSms->getDefaultAccessToken();
}

/*
 * contoh data kirim multiple sms
 */
$smsData = array(
   array(
       'text' => 'Hello From the API, Sending Multiple',
       'destination' => '08xxxxxx'
   ),
   array(
       'text' => 'Hello From the API, Sending from Api testing',
       'destination' => '628xxxx'
   ),
);

/*
 * contoh data kirim satu sms
 */
 $smsData = array(
    'text' => 'Hello From the API, Sending Multiple',
    'destination' => '+628xxxxx'
);

/*
 * kirim sms
*/
$response = $nusanetSms->sendSms($smsData, $accessToken);

/*
 * contoh response jika sms success terkirim dalam format json
 *  {
 *      "type":"http:\/\/www.w3.org\/Protocols\/rfc2616\/rfc2616-sec10.html",
 *      "title":"success",
 *      "status":200,
 *      "detail":"200||2||2||sms on sending process"
 *   }
 */

/*
 * contoh response jika sms gagal terkirim dala format json
 *  {
 *      "type":"http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
 *      "title":"Bad Request",
 *      "status":400,
 *      "detail":"202||0||0||malformed sms message"
 *  }
 **/
