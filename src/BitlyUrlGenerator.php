<?php

namespace Myvon\DeviceChanger;

if(!interface_exists('Myvon\DeviceChanger\UrlGeneratorInterface')) {
    require './UrlGeneratorInterface.php';
}

class BitlyUrlGenerator extends DummyGenerator implements UrlGeneratorInterface
{
    /**
     * @var string
     */
    private $accessToken;

    public function __construct(string $url, string $accessToken)
    {
        $this->url = $url;
        $this->accessToken = $accessToken;
        parent::__construct($url);
    }

    protected function callBitly($url)
    {
        $api_url = "https://api-ssl.bitly.com/v4/bitlinks";
        $token = $this->accessToken;

        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["long_url" => $url]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            "Content-Type: application/json"
        ]);

        $arr_result = json_decode(curl_exec($ch));

        return $arr_result->link;
    }

    public function getUrl(string $uid): string
    {
        return $this->callBitly(parent::getUrl($uid));
    }
}