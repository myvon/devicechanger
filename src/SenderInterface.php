<?php

namespace Myvon\DeviceChanger;

interface SenderInterface
{
    public function isValid(string $recipient): bool;
    public function send(string $recipient, string $message): bool;
}