<?php

namespace Myvon\DeviceChanger;

class DeviceChanger
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var string
     */
    private $message;
    /**
     * @var int
     */
    private $lastError;

    /**
     * @var SenderInterface
     */
    private $sender;
    /**
     * @var StorageInterface
     */
    private $storage;

    const ERROR_NONE = 0;
    const ERROR_INVALID_RECIPIENT = 1;
    const ERROR_URL_GENERATOR = 2;
    const ERROR_NOT_RECEIVED = 3;
    const ERROR_NOT_SENT = 4;

    public function __construct(UrlGeneratorInterface $urlGenerator, SenderInterface $sender, StorageInterface $storage, string $message) {
        $this->urlGenerator = $urlGenerator;
        $this->message = $message;
        $this->sender = $sender;
        $this->storage = $storage;
    }

    /**
     * @return int
     */
    public function getLastError(): int
    {
        return $this->lastError;
    }

    public function send(string $recipient, array $parameters = [])
    {
        $this->lastError = self::ERROR_NONE;

        if(!$this->sender->isValid($recipient)) {
            $this->lastError = self::ERROR_INVALID_RECIPIENT;
            return false;
        }

        $uid =  uniqid();
        $url = $this->urlGenerator->getUrl($uid);

        if(!is_string($url) || empty($url)) {
            $this->lastError = self::ERROR_URL_GENERATOR;
            return false;
        }

        $message = str_replace('%url%', $url, $this->message);
        foreach($parameters as $key => $value) {
            $message = str_replace(sprintf("%%%s%%", $key), $value, $message);
        }

        if(!$this->sender->send($recipient, $message)) {
            $this->lastError = self::ERROR_NOT_SENT;
            return false;
        }

        return $uid;
    }

    public function check($uid) {
        return $this->storage->isReceived($uid);
    }

    public function fetch($uid) {
        if(!$this->check($uid)) {
            $this->lastError = self::ERROR_NOT_RECEIVED;
            return false;
        }

        return $this->storage->fetch($uid);
    }
}