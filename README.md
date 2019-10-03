# Sipit

[![Build Status](https://travis-ci.org/odannyc/Sipit.svg?branch=master)](https://travis-ci.org/odannyc/Sipit)
[![Total Downloads](https://poser.pugx.org/odannyc/sipit/downloads)](https://packagist.org/packages/odannyc/sipit)
[![StyleCI](https://styleci.io/repos/60649114/shield)](https://styleci.io/repos/60649114)


A SIP library to send OPTION pings to publicly open SIP devices out in the wild. SIP (Session Initiation Protocol) is a protocol used in VoIP communications allowing users to make voice and video calls.

```php
use Sipit\SipitFactory as Sipit;

$destination_ip = '192.168.1.100';
$destination_port = 5060;

Sipit::ping($destination_ip, $destination_port);
```

## Summary
Sipit is a simple pinging library for SIP devices. When you call the `Sipit::ping()` method it'll return an array of information including the request and response. It's good for knowing if a SIP server is alive. A good example is if you're displaying the status of a SIP server, you can use this one method to check that. If an endpoint doesn't respond, it'll return an empty array for the response section.

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
Right now Sipit only has one method, and that is to ping SIP devices, in the future I am planning to add more to it. A simple way to use Sipit is the ping method:

```php
use sipit\SipitFactory as Sipit;

Sipit::ping('192.168.1.10', 5060);
```
The response will look like this:

```php
Array
(
    [request_full] => Array
        (
            [OPTIONS sip] => OPTIONS sip:192.168.1.10:5060 SIP/2.0
            [Via] => Via: SIP/2.0/UDP 192.168.1.121:5090;rport;branch=z9hG4bK572601
            [From] => From: <sip:ping@sipit.com>;tag=10877
            [To] => To: <sip:192.168.1.10:5060>
            [Call-ID] => Call-ID: callid:1d6a6668d796af55d63b1c712602c34b;@192.168.1.121
            [CSeq] => CSeq: 20 OPTIONS
            [Contact] => Contact: <sip:ping@192.168.1.121:5090>
            [Max-Forwards] => Max-Forwards: 70
            [User-Agent] => User-Agent: SIPIT
            [Content-Length] => Content-Length: 0
        )

    [response_code] => 403
    [response_message] => Forbidden
    [response_concat] => 403 Forbidden
    [response_full] => Array
        (
            [SIP/2.0 403 Forbidden] => SIP/2.0 403 Forbidden
            [Via] => Via: SIP/2.0/UDP 192.168.1.121:5090;received=192.168.1.121;branch=z9hG4bK572601;rport=5090
            [From] => From: <sip:ping@sipit.com>;tag=10877
            [To] => To: <sip:192.168.1.10:5060>;tag=aprqngfrt-qra2n33000081
            [Call-ID] => Call-ID: callid:1d6a6668d796af55d63b1c712602c34b;@192.168.1.121
            [CSeq] => CSeq: 20 OPTIONS
        )

)
```
