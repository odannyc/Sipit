# Sipit

[![Build Status](https://travis-ci.org/odannyc/Sipit.svg?branch=master)](https://travis-ci.org/odannyc/Sipit)
[![Total Downloads](https://poser.pugx.org/odannyc/sipit/downloads)](https://packagist.org/packages/odannyc/sipit)
[![codecov](https://codecov.io/gh/odannyc/Sipit/branch/master/graph/badge.svg)](https://codecov.io/gh/odannyc/Sipit)


A SIP library to send OPTION pings to publicly open SIP devices out in the wild.

```php
use Sipit\SipitFactory as Sipit;

$destination_ip = '192.168.1.100';
$destination_port = 5060;

Sipit::ping($destination_ip, $destination_port); // 200
```

## Summary
**Sipit is currently under development, it is not ready for production**. Sipit is a simple pinging library for SIP devices. When you call the `Sipit::ping()` method it'll return the response code of the destination sip server. It's good for knowing if a SIP server is alive. A good example is if you're displaying the status of a SIP server, you can use this one method to check that. If an IP doesn't respond, it'll return `No Response`

## Installation
The best way to install this library is with **Composer**.

    composer require odannyc/Sipit

**Or**

```php
{
  "require": {
    "odannyc/Sipit": "*"
  }
}
```

## Usage
Right now Sipit only has one method, and that is to ping SIP devices, in the future I am planning to add more to it. A simple way to use Sipit is the ping method:

```php
use sipit\SipitFactory as Sipit;
    
Sipit::ping('192.168.1.10', 5060); // 200
```
