<?php


beforeEach(function() {
    $this->storage = new \Myvon\DeviceChanger\FileStorage("./uploads");

    if(!file_exists("./uploads")) {
        mkdir("./uploads");
    }
});

afterAll(function() {
    if(file_exists(realpath("./uploads"))) {
        rmdir("./uploads");
    }
});

test('Test that it can check if the files are received', function() {
    $uid = uniqid();

    expect($this->storage->isReceived($uid))->toBeFalse();

    $dir = $this->storage->getDirectory($uid);
    mkdir($dir);
    expect($this->storage->isReceived($uid))->toBeTrue();

    rmdir($dir);
});

test('Test that it return the correct path', function() {
    $uid = uniqid();

    expect($this->storage->fetch($uid))->toBeFalse();

    $dir = $this->storage->getDirectory($uid);
    mkdir($dir);
    expect($this->storage->fetch($uid))->toEqual($dir);

    rmdir($dir);
});