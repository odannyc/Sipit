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
class Helper
{
    /* This regex checks to see if string has 2 words joined by a period. So at the bare minimum: sample.com */
    private static $urlRegex = '/\w+\.[a-zA-Z]{1,10}$/';

    /* Needs to be 4 octects with period in between. Octects need to be 1-255 */
    private static $ipRegex = '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/';

    /**
     * Takes in an IP address and makes sure its formatted correctly.
     *
     * @throws Exception
     */
    public static function verifyEndpointFormat($endpoint)
    {
        $endpointType = self::urlOrIp($endpoint);

        if ($endpoint == '') {
            throw new Exception('IP Address not provided.');
        }
        if ($endpointType == 'none') {
            throw new Exception('Endpoint not in correct format.');
        }
    }

    /**
     * Checks to see if the endpoint is an ip address or url.
     *
     * @param string $endpoint
     *
     * @return string
     */
    public static function urlOrIp($endpoint)
    {
        if (preg_match(self::$urlRegex, $endpoint)) {
            return 'url';
        } elseif (preg_match(self::$ipRegex, $endpoint)) {
            return 'ip';
        } else {
            return 'none';
        }
    }

    /**
     * Takes in a Port number and makes sure its formatted correctly.
     *
     * @throws Exception
     */
    public static function verifyPortFormat($port)
    {
        if (empty($port)) {
            $port = 5060;
        }
        if (is_int($port) && ($port < 1024 || $port > 65535)) {
            throw new Exception('Port number not valid. Must be between 1024 and 65535.');
        }
        if (is_string($port) && !((int) $port)) {
            throw new Exception('Port number needs to be an integer.');
        }
    }

    /**
     * Simple helper to run two methods, verify IP and Port.
     */
    public static function verifyEndpointAndPort($ip, $port)
    {
        self::verifyEndpointFormat($ip);
        self::verifyPortFormat($port);
    }

    /**
     * Converts a url/domain to an IP address.
     *
     * @param string $endpoint The endpoint to convert.
     *
     * @return string Ip address of url/domain.
     */
    public static function convertEndpointToIp($endpoint)
    {
        if (self::urlOrIp($endpoint) == 'url') {
            $ip = gethostbyname($endpoint);
        } else {
            $ip = $endpoint;
        }

        if (self::urlOrIp($ip) != 'ip') {
            throw new Exception('Unable to convert domain to IP address.');
        } elseif (self::urlOrIp($ip) == 'ip') {
            return $ip;
        }
    }

    /**
     * If the port number entered was entered as an empty string, null or empty convert to 5060.
     *
     * @param type $port
     *
     * @return type
     */
    public static function convertPortNumber($port)
    {
        if (empty($port)) {
            return 5060;
        } else {
            return $port;
        }
    }
}
