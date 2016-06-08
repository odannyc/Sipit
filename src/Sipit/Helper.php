<?php

/*
 * This file is part of the Sipit package.
 *
 * Danny Carrillo <odannycx@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sipit;
use Exception;

/* Class for some helper methods 
 * for the Sipit application
 */
class Helper {
	public static function verifyIpFormat($ip) {
		if ($ip == '') {
			throw new Exception('IP Address not provided.');
		}
		if (!preg_match('/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/', $ip)) {
			throw new Exception('IP address not in the correct format.');
		}
	}
	public static function verifyPortFormat($port) {
		if ($port != '' && ((int)$port < 1024 || (int)$port > 65535)) {
			throw new Exception('Port number not valid. Must be between 1024 and 65535.');
		}
	}
	public static function verifyIpAndPort($ip,$port) {
		self::verifyIpFormat($ip);
		self::verifyPortFormat($port);
	}
}