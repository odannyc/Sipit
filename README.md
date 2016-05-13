# sipit
A SIP library to send OPTION pings to publicly open SIP devices out in the wild.

```php
use sipit\sipit;

$destination_ip = '192.168.1.100';
$destination_port = 5060;

sipit::ping($destination_ip, $destination_port); // 200
```

## Summary
**Sipit is currently under development, it is not ready for production**. Sipit is a simple pinging library for SIP devices. When you call the `sipit::ping()` method it'll return the response code of the destination sip server. It's good for knowing if a SIP server is alive. A good example is if you're displaying the status of a SIP server, you can use this one method to check that.

## Installation
The best way to install this library is with **Composer**.

    composer require odannyc/sipit

**Or**

```php
{
  "require": {
    "odannyc/sipit": "*"
  }
}
```

## Usage
Right now sipit only has one method, and that is to ping SIP devices, in the future I am planning to add more to it. A simple way to use sipit is the ping method:

```php
use sipit\sipit;
    
sipit::ping('192.168.1.10', 5060); // 200
```
