<?php

namespace Myvon\DeviceChanger;

if(!interface_exists('Myvon\DeviceChanger\UrlGeneratorInterface')) {
    require './UrlGeneratorInterface.php';
}
class DummyGenerator implements UrlGeneratorInterface
{
    /**
     * @var string
     */
    private $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }
    public function getUrl(string $uid): string
    {
        $parsedUrl = parse_url($this->url);

        list($url) = explode('?', $this->url);
        $query = [];
        if(isset($parsedUrl['query']) && !empty($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $query);
        }

        $query['uid'] = $uid;

        return sprintf('%s?%s', $url, http_build_query($query));
    }
}