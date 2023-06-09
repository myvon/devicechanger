<?php

test('test url are generated correctly', function() {
    $generator = new \Myvon\DeviceChanger\DummyGenerator("http://localhost/upload.php");

    expect($generator->getUrl("test"))->toEqual("http://localhost/upload.php?uid=test");

    $generator = new \Myvon\DeviceChanger\DummyGenerator("http://localhost/upload.php?test=ok");

    expect($generator->getUrl("test"))->toEqual("http://localhost/upload.php?test=ok&uid=test");

    $generator = new \Myvon\DeviceChanger\DummyGenerator("http://localhost/upload.php?uid=test2");

    expect($generator->getUrl("test"))->toEqual("http://localhost/upload.php?uid=test");
});