<?php

namespace Myvon\DeviceChanger;

interface StorageInterface
{
    public function isReceived(string $uid): bool;
    public function fetch(string $uid);
}