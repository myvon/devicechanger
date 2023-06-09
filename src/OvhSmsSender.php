<?php

namespace Myvon\DeviceChanger;

use Ovh\Api;

if(!interface_exists('Myvon\DeviceChanger\SenderInterface')) {
    require './SenderInterface.php';
}

class OvhSmsSender implements SenderInterface
{
    /**
     * @var Api
     */
    private $ovh;
    /**
     * @var string
     */
    private $sender;
    /**
     * @var string
     */
    private $ovhSmsService;

    public function __construct(Api $ovh, string $sender, string $ovhSmsService) {
        $this->ovh = $ovh;
        $this->sender = $sender;
        $this->ovhSmsService = $ovhSmsService;
    }

    public function isValid(string $recipient): bool
    {
        if (strlen($recipient) < 10 || strlen($recipient) > 15) {
            return false;
        }

        if (substr($recipient, 0, 1) !== '+') {
            return false;
        }

        $number = substr($recipient, 1);
        if (!ctype_digit($number)) {
            return false;
        }

        return true;
    }

    public function send(string $recipient, string $message): bool
    {
        $sms = $this->ovh->post(sprintf("/sms/%s/jobs", $this->ovhSmsService), [
            'charset' => 'UTF-8',
            'message' => $message,
            'noStopClause' => true,
            'receivers' => [$recipient],
            'sender' => $this->sender,
        ]);

        return count($sms['validReceivers']) === 1;
    }
}