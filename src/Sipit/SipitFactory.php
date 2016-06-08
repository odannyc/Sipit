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

/**
 * Factory class for Sipit class.
 */
class SipitFactory
{
    public static function ping($dstIp = '', $dstPort = 5060)
    {
        $result = new Sipit($dstIp, $dstPort);

        return $result->getParsedResponse();
    }
}