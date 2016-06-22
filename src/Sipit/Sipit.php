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

/**
 * A simple class to be able to send OPTIONS ping
 * packets to publicly available SIP devices.
 */
class Sipit
{
    /* All Class Variables */

    // Source vars
    protected $srcIp;
    protected $srcPort;

    //Destination vars
    protected $dstIp;
    protected $dstPort;

    // Socket
    protected $socket;

    // Reponse
    protected $response;
    protected $parsedResponse;

    // Request Data
    protected $request;

    /* Method Variables */
    protected $method = 'OPTIONS';
    protected $userAgent = 'SIPIT';

    /**
     * Used to ping an IP address on a specified port number.
     *
     * @param string $dstIp   The Ip address of the request.
     * @param int    $dstPort The port number of the request.
     * @param bool   $verify  Do we need to verify the format of the port and ip address?
     *
     * @return int
     */
    public function __construct($dstIp = '', $dstPort = 5060, $verify = true)
    {
        /* If verify is true, make sure ip and port are formatted correctly */
        if ($verify) {
            Helper::verifyEndpointAndPort($dstIp, $dstPort);
        }

        /* Convert domain/url to ip, usually by dns */
        $dstIp = Helper::convertEndpointToIp($dstIp);

        /* Convert port number if possible */
        $dstPort = Helper::convertPortNumber($dstPort);

        /* Set the destination port */
        $this->dstPort = $dstPort;
        $this->dstIp = $dstIp;

        /* Set source params */
        $this->setSrcIp();
        $this->setSrcPort();

        /* Build request data */
        $this->buildRequest();

        /* Create UDP socket */
        $this->buildSocket();

        /* Send Request */
        $this->sendRequest();

        /* Read & parse response */
        $this->readResponse();
        $this->parseResponse();

        /* Close the connection to the socket */
        $this->closeConnection();
    }

    /**
     * Used to build the request data.
     *
     * @return string
     */
    protected function buildRequest()
    {
        /* We need to build some stuff before the actual request */
        /* Building the MD5 has first also know as the Call ID */
        $callId = $this->buildCallId();

        $data = "{$this->method} sip:{$this->dstIp}:{$this->dstPort} SIP/2.0\r\n";
        $data .= "Via: SIP/2.0/UDP {$this->srcIp}:{$this->srcPort};rport;branch=z9hG4bK572601\r\n";
        $data .= "From: <sip:ping@sipit.com>;tag=10877\r\n";
        $data .= "To: <sip:{$this->dstIp}:{$this->dstPort}>\r\n";
        $data .= "Call-ID: {$callId}@{$this->srcIp}\r\n";
        $data .= "CSeq: 20 OPTIONS\r\n";
        $data .= "Contact: <sip:ping@{$this->srcIp}:{$this->srcPort}>\r\n";
        $data .= "Max-Forwards: 70\r\n";
        $data .= "User-Agent: {$this->userAgent}\r\n";
        $data .= 'Content-Length: 0';
        $data .= "\r\n";
        $data .= "\r\n";

        $this->request = $data;
    }

    /**
     * Builds the call ID needed for the request.
     *
     * @return string Call Id hash
     */
    protected function buildCallId()
    {
        $callId = 'callid:'.md5(uniqid()).';';

        return $callId;
    }

    /**
     * Used to get the IP address of the person using it.
     *
     * @return string
     */
    protected function setSrcIp()
    {
        // running in a web server
        if (isset($_SERVER['SERVER_ADDR'])) {
            $srcIp = $_SERVER['SERVER_ADDR'];
        } else { // running in the command line
            $addr = gethostbynamel(php_uname('n'));
            $addr = $addr[0];
            if (!isset($addr) || substr($addr, 0, 3) == '127') {
                throw new Exception('Failed to obtain IP address to bind.');
            }
            $srcIp = $addr;
        }

        $this->srcIp = $srcIp;
    }

    /**
     * Sets the source port.
     *
     * @return none
     */
    protected function setSrcPort()
    {
        $srcPort = 5090;
        $this->srcPort = $srcPort;
    }

    /**
     * Builds the socket for the request.
     *
     * @return none
     */
    protected function buildSocket()
    {
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_bind($this->socket, $this->srcIp, $this->srcPort);
        socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 1, 'usec' => 0]);
        socket_set_option($this->socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => 1, 'usec' => 0]);
    }

    /**
     * Sends the request to the destination.
     *
     * @return none
     */
    protected function sendRequest()
    {
        socket_sendto($this->socket, $this->request, strlen($this->request), 0, $this->dstIp, $this->dstPort);
    }

    /**
     * Reads the response from the destination.
     *
     * @return none
     */
    protected function readResponse()
    {
        $emptystr = '';
        $zeroint = 0;
        socket_recvfrom($this->socket, $this->response, 10000, 0, $emptystr, $zeroint);
    }

    /**
     * Parses the response. Usually might equal to 200,400 and other SIP responses.
     */
    protected function parseResponse()
    {
        if (!empty($this->response)) {
            preg_match('/^SIP\/2\.0 ([0-9]{3})/', $this->response, $this->parsedResponse);
            $this->parsedResponse = $this->parsedResponse[1];
        } else {
            $this->parsedResponse = 'No Response';
        }
    }

    /**
     * Closes the connection to the socket.
     */
    protected function closeConnection()
    {
        socket_close($this->socket);
    }

    /**
     * Just returns parsed response.
     *
     * @return int The response of the connection
     */
    public function getParsedResponse()
    {
        return $this->parsedResponse;
    }

    /**
     * Returns the request that was sent.
     *
     * @return string The request that was sent to the endpoint
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns the response in a none formatted way.
     *
     * @return string The response of the request
     */
    public function getBareResponse()
    {
        return $this->response;
    }
}
