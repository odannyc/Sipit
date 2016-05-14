<?php

/*
 * This file is part of the sipit package.
 *
 * Danny Carrillo <odannycx@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sipit;

use Exception;

/**
 * A simple class to be able to send OPTIONS ping
 * packets to publicly available SIP devices
 */
class sipit {

	/* All Class Variables */

	// Source vars
	protected static $srcIp;
	protected static $srcPort;

	//Destination vars
	protected static $dstIp;
	protected static $dstPort;

	// Socket
	protected static $socket;

	// Reponse
	protected static $response;

	// Request Data
	protected static $request;

	/* Method Variables */
	protected static $method = 'OPTIONS';
	protected static $userAgent = 'SIPIT';

	/**
	 * Used to ping an IP address on a specified port number
	 * @param string $ip_address 
	 * @param int $port 
	 * @return bool
	 */
	public static function ping($dstIp = '', $dstPort = 5060) {
		/* Check if ip address is supplied */
		if ($dstIp == '') {
			throw new Exception('IP Address not supplied.');
		} else {
			/* Set the destination port */
			self::$dstIp = $dstIp;
		}

		/* Set the destination port */
		if ($dstPort != '' && ((int)$dstPort < 1024 || (int)$dstPort > 65535)) {
			throw new Exception('Port number not valid. Must be between 1024 and 65535.');
		} else {
			/* Set the destination port */
			self::$dstPort = $dstPort;
		}

		/* Set source params */
		self::setSrcIp();
		self::setSrcPort();

		/* Build request data */
		self::buildRequest();

		/* Create UDP socket */
		self::buildSocket();

		/* Send Request */
		self::sendRequest();
	}

	/**
	 * Used to build the request data
	 * @return string
	 */
	protected static function buildRequest() {
		$data = self::$method . " sip:" . self::$dstIp . ":" . self::$dstPort . " SIP/2.0\r\n";
		$data .= "Via: SIP/2.0/UDP " . self::$srcIp . ":" . self::$srcPort . ";rport;branch=z9hG4bK572601\r\n";
		$data .= "From: <sip:ping@sipit.com>;tag=10877\r\n";
		$data .= "To: <sip:" . self::$dstIp . ":" . self::$dstPort . ">\r\n";
		$data .= "Call-ID: " . md5(uniqid()). "@" . self::$srcIp . "\r\n";
		$data .= "CSeq: 20 OPTIONS\r\n";
		$data .= "Contact: <sip:ping@" . self::$srcIp . ":" . self::$srcPort . ">\r\n";
		$data .= "Max-Forwards: 70\r\n";
		$data .= "User-Agent: " . self::$userAgent . "\r\n";
		$data .= "Content-Length: 0";
		$data .= "\r\n";
		$data .= "\r\n";
		
		self::$request = $data;
	}

	/**
	 * Used to get the IP address of the person using it.
	 * @return string
	 */
	protected static function setSrcIp() {
		// running in a web server
      	if (isset($_SERVER['SERVER_ADDR'])) {
        	$srcIp = $_SERVER['SERVER_ADDR'];
      	} else { // running in the command line
	        $addr = gethostbynamel(php_uname('n'));
	        $addr = $addr[0];
	        if (!isset($addr) || substr($addr,0,3) == '127') {
          		throw new Exception("Failed to obtain IP address to bind.");
	        }
	        $srcIp = $addr;
      	}

      	self::$srcIp = $srcIp;
	}

	/**
	 * Sets the source port
	 * @return none
	 */
	protected static function setSrcPort() {
		$srcPort = 5090;
		self::$srcPort = $srcPort;
	}

	/**
	 * Builds the socket for the request
	 * @return none
	 */
	protected static function buildSocket() {
		self::$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		socket_bind(self::$socket, self::$srcIp, self::$srcPort);
		socket_set_option(self::$socket, SOL_SOCKET, SO_RCVTIMEO, array("sec"=>10,"usec"=>0));
		socket_set_option(self::$socket, SOL_SOCKET, SO_SNDTIMEO, array("sec"=>5,"usec"=>0));
	}

	/**
	 * Sends the request to the destination
	 * @return none
	 */
	protected static function sendRequest() {
		socket_sendto(self::$socket, self::$request, strlen(self::$request), 0, self::$dstIp, self::$dstPort);
	}

	/**
	 * Reads the response from the destination
	 * @return none
	 */
	protected static function readResponse() {
		socket_recvfrom(self::$socket, self::$response, 10000, 0, '', 0);
	}

}