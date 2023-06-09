<?php

require '../src/DummyGenerator.php';
require '../src/DeviceChanger.php';
require '../src/FileStorage.php';
require '../src/DummySender.php';

$generator = new \Myvon\DeviceChanger\DummyGenerator("http://localhost/upload.php");
$storage = new \Myvon\DeviceChanger\FileStorage('./upload/');
$sender = new \Myvon\DeviceChanger\DummySender();

$deviceChanger = new \Myvon\DeviceChanger\DeviceChanger(
    $generator,
    $sender,
    $storage,
    "Pour continuer, veuillez vous rendre à l'adresse %url% (envoyé à %phone%)"
);

if(!($uid = $deviceChanger->send("+33600000000", ['phone' => '+33600000000']))) {
    echo sprintf('Une erreur est survenue, code d\'erreur %d', $deviceChanger->getLastError()).PHP_EOL;
    exit;
}

echo sprintf('Message envoyé à %s :', $sender->getRecipient()).PHP_EOL;
echo sprintf('%s', $sender->getMessage()).PHP_EOL;
echo sprintf("Créez un dossier ./upload/%s/ pour continuer", $uid).PHP_EOL;

while(!$deviceChanger->check($uid));

if($deviceChanger->check($uid)) {
    echo sprintf("Le dossier %s a été créé", $deviceChanger->fetch($uid)).PHP_EOL;
} else {
    echo 'oops'.PHP_EOL;
}