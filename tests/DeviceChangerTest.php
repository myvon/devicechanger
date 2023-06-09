<?php

beforeEach(function() {
    $this->generator = new \Myvon\DeviceChanger\DummyGenerator("http://localhost/upload.php");
    $this->sender = new \Myvon\DeviceChanger\DummySender();
    $this->storage = new \Myvon\DeviceChanger\FileStorage("./uploads");
    $this->deviceChanger = new Myvon\DeviceChanger\DeviceChanger($this->generator, $this->sender, $this->storage, "Test %url% with %parameter%");
});

test("Test that it can send the link", function() {
    $uid = $this->deviceChanger->send("+336000000000");

    expect($uid)->toBeString();
    expect($this->deviceChanger->getLastError())->toBe(\Myvon\DeviceChanger\DeviceChanger::ERROR_NONE);
});

test("Test that error works", function() {
    $generator = Mockery::mock(\Myvon\DeviceChanger\DummyGenerator::class);
    $sender = Mockery::mock(\Myvon\DeviceChanger\DummySender::class);

    $sender->shouldReceive("isValid")->withAnyArgs()->andReturnUsing(function($recipient) {
         if($recipient === "test_false") {
             return false;
         }

         return true;
    });

    $sender->shouldReceive("send")->withAnyArgs()->andReturnFalse();

    $generator->shouldReceive("getUrl")->withAnyArgs()->andReturnFalse();

    $deviceChanger = new \Myvon\DeviceChanger\DeviceChanger($generator, $this->sender, $this->storage, "test");

    $uid = $deviceChanger->send("test");
    expect($uid)->toBeFalse();
    expect($deviceChanger->getLastError())->toBe(\Myvon\DeviceChanger\DeviceChanger::ERROR_URL_GENERATOR);


    $deviceChanger = new \Myvon\DeviceChanger\DeviceChanger($this->generator, $sender, $this->storage, "test");
    $uid = $deviceChanger->send("test_false");
    expect($uid)->toBeFalse();
    expect($deviceChanger->getLastError())->toBe(\Myvon\DeviceChanger\DeviceChanger::ERROR_INVALID_RECIPIENT);

    $uid = $deviceChanger->send("test");
    expect($uid)->toBeFalse();
    expect($deviceChanger->getLastError())->toBe(\Myvon\DeviceChanger\DeviceChanger::ERROR_NOT_SENT);
    $deviceChanger->fetch("test");
    expect($deviceChanger->getLastError())->toBe(\Myvon\DeviceChanger\DeviceChanger::ERROR_NOT_RECEIVED);

});