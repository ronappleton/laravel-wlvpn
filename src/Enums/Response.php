<?php

declare(strict_types=1);

namespace RonAppleton\WLVPN\Enums;

use Guzzle\Http\Message\Response;
use RonAppleton\WLVPN\Exceptions\WLVPNResponseException;

class Response
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

    public static function valid(Response $response): bool
    {
        if (in_array($response->getStatusCode(), self::getConstList(), true)) {
            return true;
        }

        throw new WLVPNResponseException('Unknown WLVPN Response Status Code' . $response->getStatusCode());
    }
}
