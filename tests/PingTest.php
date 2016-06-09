<?php

/*
 * This file is part of the test sipit package.
 *
 * Danny Carrillo <odannycx@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests;

use PHPUnit_Framework_TestCase;
use Sipit\SipitFactory as Sipit;

/**
 * Used to test Pings to different IP addresses.
 */
class PingTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test to live IP, needs to return 200 OK status.
     *
     * @return assertion
     */
    public function testPingToLiveIp()
    {
        $dstIp = '184.154.184.90';
        $dstPort = 5060;
        $res = Sipit::ping($dstIp, $dstPort);

        $this->assertEquals($res, 200);
    }

    /**
     * Test to an IP that is NOT a VoIP server.
     *
     * @return assertion
     */
    public function testPingToDeadIP()
    {
        $dstIp = '184.15.184.93';
        $dstPort = 5060;
        $res = Sipit::ping($dstIp, $dstPort);

        $this->assertEquals($res, 'No Response');
    }

    /**
     * Sends a ping to a domain name instead of an IP address.
     *
     * @return assertion
     */
    public function testPingToDomainName()
    {
        $dstIp = 'sip.dylphone.com';
        $dstPort = 5060;
        $res = Sipit::ping($dstIp, $dstPort);

        $this->assertEquals($res, 200);
    }

    /**
     * Just sends a ping to an endpoint where verify is false.
     *
     * @return assertion
     */
    public function testWithVerifyFalse()
    {
        $dstIp = 'sip.dylphone.com';
        $dstPort = 5060;
        $res = Sipit::ping($dstIp, $dstPort, false);

        $this->assertEquals($res, 200);
    }

    /**
     * Sends an empty string as a port number.
     *
     * @return assertion
     */
    public function testPingWithEmptyPort()
    {
        $dstIp = 'sip.dylphone.com';
        $dstPort = '';
        $res = Sipit::ping($dstIp, $dstPort);

        $this->assertEquals($res, 200);
    }
}
