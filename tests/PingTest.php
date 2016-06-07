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

use Sipit\SipitFactory as Sipit;
use PHPUnit_Framework_TestCase;

/**
 * Used to test Pings to different IP addresses.
 */
class PingTest extends PHPUnit_Framework_TestCase {

	/**
	 * Test to live IP, needs to return 200 OK status
	 * @return assertion
	 */
	public function testPingToLiveIp() {
		$dstIp = '184.154.184.90';
		$dstPort = 5060;
		$res = Sipit::ping($dstIp, $dstPort);

		$this->assertEquals($res, 200);
	}

	/**
	 * Test to an IP that is NOT a VoIP server.
	 * @return assertion
	 */
	public function testPingToDeadIP() {
		$dstIp = '184.15.184.93';
		$dstPort = 5060;
		$res = Sipit::ping($dstIp, $dstPort);

		$this->assertEquals($res, 'No Response');
	}
}