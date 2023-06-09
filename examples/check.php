<?php

require '../src/DummyGenerator.php';
require '../src/DeviceChanger.php';
require '../src/FileStorage.php';
require '../src/DummySender.php';

$generator = new \Myvon\DeviceChanger\DummyGenerator("http://localhost/upload.php");
$storage = new \Myvon\DeviceChanger\FileStorage('./upload');
$sender = new \Myvon\DeviceChanger\DummySender();

$deviceChanger = new \Myvon\DeviceChanger\DeviceChanger(
    $generator,
    $sender,
    $storage,
    "Pour continuer, veuillez vous rendre à l'adresse %url% (envoyé à %phone%)"
);

$uid = $_GET['uid'];

if(!$deviceChanger->check($uid)) {
    echo 'ko';
} else {
    echo $deviceChanger->fetch($uid);
}

