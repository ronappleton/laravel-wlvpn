<?php

namespace RonAppleton\WLVPN\Enums;

use RonAppleton\WLVPN\Exceptions\WLVPNResponseException;

class Response extends \SplEnum
{
    const SUCCESS = 200;
    const NO_CONTENT = 204;
    const BAD_REQUEST = 400;
    const UNAUTHORISED = 401;
    const NOT_FOUND = 404;
    const NO_LONGER_EXISTS = 410;
    const TOO_MANY_REQUESTS = 429;
    const INTERNAL_SERVER_ERROR = 500;
    const BAD_GATEWAY = 502;
    const SERVICE_UNAVAILABLE = 503;

    public static function valid(\GuzzleHttp\Psr7\Response $response)
    {
        if (in_array($response->getStatusCode(), self::getConstList())) {
            return true;
        }

        throw new WLVPNResponseException('Unknown WLVPN Response Status Code' . $response->getStatusCode());
    }
}
