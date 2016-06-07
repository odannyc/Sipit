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

/* Class for some helper methods 
 * for the Sipit application
 */
class Helper {
	public static function verifyIpFormat($Ip) {
		if ($Ip == '') {
			return false;
		}
		if (!preg_match('/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/', $Ip)) {
			return false;
		}
		return true;
	}
}