<?php

test('Test that only valid phone numbers works', function() {
    $ovhApi = Mockery::mock(\Ovh\Api::class);

    $sender = new \Myvon\DeviceChanger\OvhSmsSender($ovhApi,"", "");

    expect($sender->isValid("test"))->toBeFalse();
    expect($sender->isValid("0600000000"))->toBeFalse();
    expect($sender->isValid("+33659825552"))->toBeTrue();
});


test('Test that it send the sms', function() {
    $ovhApi = Mockery::mock(\Ovh\Api::class);
    $ovhApi->shouldReceive("post")
        ->with("/sms/test/jobs", Mockery::any())
        ->andReturnUsing(function($url, $parameters) {
            if($parameters['message'] === "test_false") {
                return ['validReceivers' => []];
            }

            return ['validReceivers' => [10]];

        });

    $sender = new \Myvon\DeviceChanger\OvhSmsSender($ovhApi,"", "test");

    expect($sender->send("test", "test"))->toBeTrue();
    expect($sender->send("test", "test_false"))->toBeFalse();
});

