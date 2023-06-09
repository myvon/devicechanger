<?php

namespace Myvon\DeviceChanger;

if(!interface_exists('Myvon\DeviceChanger\SenderInterface')) {
    require './SenderInterface.php';
}
class DummySender implements SenderInterface
{
    private $message;
    private $recipient;

    public function send(string $recipient, string $message): bool
    {
        $this->message = $message;
        $this->recipient = $recipient;
        return true;
    }

    public function isValid(string $recipient): bool
    {
        return true;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getRecipient()
    {
        return $this->recipient;
    }


}