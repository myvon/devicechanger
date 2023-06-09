# devicechanger

You want your user to send you data through another device (for example a phone) without leaving your page ? This small library help you to !

# How it works

This package allow to send a link through any method and check if the user has sent the data needed.

# Installation

You can install the package via composer:
```bash
composer require myvon/devicechanger
```

# Creating the file your user is sent to

You first need to create the file where your user will be redirected to send the required data.

You can check the `examples/upload.php` file to see an example of a file where the user is invited tu upload a file
# Choosing an url generator

The second step is to choose a way to generate the url to the file. Two generator are provided by default:  
- the `Myvon\DeviceChanger\DummyGenerator` which simply return the url with the correct query parameters
- the `Myvon\DeviceChanger\BitlyUrlGenerator` which uses the Bitly service to generate a tiny url and reduce message size (usefull for sms)

For the dummy generator, simply provide the public url to the file :
```php
use Myvon\DeviceChanger\DummyGenerator;


$generator = new \Myvon\DeviceChanger\DummyGenerator("http://localhost/upload.php");
```

For the bitly generator, you'll need to provide the public url and your bitly access token :
```php
use Myvon\DeviceChanger\DummyGenerator;


$generator = new \Myvon\DeviceChanger\BitlyUrlGenerator("http://localhost/upload.php", "myBitlyAccessToken");
```

## Creating your own generator

You can create your own generator by implementing the `Myvon\DeviceChanger\UrlGeneratorInterface` interface. 
You'll simply need to implement the `public function getUrl(string $uid)` method which return the generated url, or false if the generation failed.

# Choosing a channel to send the link

After choosing the generator, you will need to choose through which channel you send the link. Two senders are provided by default:  
- The `Myvon\DeviceChanger\DummyGenerator` which will simply save the message and the recipient and always success. He is mainly used to test the library
- The `Myvon\DeviceChanger\OvhSmsGenerator` which send the link by sms using OVH. Be advised that the phone number must start with the country code (+33 for france)

To use the OvhSmsGenerator you'll to provide : 
- An `Ovh\Api` instance (which is provided by the official PHP OVH Api available at https://github.com/ovh/php-ovh)
- The sender username you configured in the OVH manager
- The sms service name  (starting by `sms-`) provided by OVH. 

```php
$sender = new \Myvon\DeviceChanger\OvhSmsSender($ovhApi, "MY COMPANY", "sms-xxxxxx-1");
```

## Creating a custom channel
You can create a custom channel by implementing the `Myvon\DeviceChanger\SenderInterface` interface. 
You'll need to implement the two following methods:  
- `public function isValid(string $recipient): bool;` which check if the recipient is valid (for example if it is a valid email if you send by mail or a valid phone number for sms channel)
- `public function send(string $recipient, string $message): bool;` which send the $message to the $recipient

# Specify the storage

The last step to use the library is to specify how the data are stored and how to check to they have been received.

For now the only storage provided by default is the `Myvon\DeviceChanger\FileStorage` which use the filesystem to store the data (mainly used for upload).

You'll simply need to provide the directory where the data will be stored:  
```php
$storage = new \Myvon\DeviceChanger\FileStorage('./upload');
```

## Adding custom storage

If you need to create a custom storage you can do it by implementing the `Myvon\DeviceChanger\StorageInterface` interface.
You'll need to implement the two following methods:
- `public function isReceived(string $uid): bool;` which check if the data are received
- `public function fetch(string $uid);` which return the received data (or a way to access them)

# Sending the link

You can finally use the `Myvon\DeviceChanger\DeviceChanger` class to send the link and check if the data are received.

You will need to provide : 
- A class that implements the `Myvon\DeviceChanger\UrlGeneratorInterface` interface
- A class that implements the `Myvon\DeviceChanger\SenderInterface` interface
- A class that implements the `Myvon\DeviceChanger\StorageInterface` interface
- The message sent to the user

The message can be personalized with parameters (see the `send` method bellow) and must contain "%url%" which will be replaced by the generated url.

```php
$deviceChanger = new \Myvon\DeviceChanger\DeviceChanger(
    $generator,
    $sender,
    $storage,
    "Pour continuer, veuillez vous rendre à l'adresse %url% (envoyé à %phone%)"
);
```

The parameters must follow this pattern : "%myparameter%"

You can then send the message:  

```php
if(($uid = $deviceChanger->send("+33600000000"))) {
    // Success
}
```

You can provide your parameters as second argument to the method :

```php
$uid = $deviceChanger->send("+33600000000", [
'myparameter' => 'my value',
]); // %myparameter% will be replace by 'my value'
```

# Check if the data are received

Now the link has been sent, you can check if your user has submitted the data :

```php

if($deviceChanger->check($uid)) {
    // Data have been received
}
```

To retrieve the data :

```php
$data  = $devicechanger->fetch($uid); // Return false if data not received
``` 

Note that the `Myvon\DeviceChanger\FileStorage` class will return the full path to the directory where the file(s) have been received.

# Examples 

You can check the following examples:  
- `examples/check.php` which check if the data are received for a given UID then return the data if they have been received or `KO` otherwise.
- `examples/example.php` which contain a full example in one page using the DummyGenerator and the DummySender (can be run in cli)
- `examples/upload.php` which is an example of the file the user is redirected to. It contain a simple upload form allowing the user to upload a file

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
