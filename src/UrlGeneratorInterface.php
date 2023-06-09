<?php

namespace Myvon\DeviceChanger;
interface UrlGeneratorInterface
{
    public function getUrl(string $uid);
}