<?php

namespace Myvon\DeviceChanger;

if(!interface_exists('Myvon\DeviceChanger\StorageInterface')) {
    require './StorageInterface.php';
}
class FileStorage implements StorageInterface
{
    /**
     * @var string
     */
    private $dir;

    public function __construct(string $dir) {
        $this->dir = $dir;
    }

    public function getDirectory(string $uid): string
    {
        return sprintf('%s/%s/', $this->dir, $uid);
    }

    public function isReceived(string $uid): bool
    {
        $dir = $this->getDirectory($uid);

        if(file_exists($dir) && is_dir($dir)) {
            return true;
        }

        return false;
    }

    public function fetch(string $uid)
    {
        if(!$this->isReceived($uid)) {
            return false;
        }
        return $this->getDirectory($uid);
    }
}