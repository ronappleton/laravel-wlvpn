<?php

namespace RonAppleton\WLVPN;

use RonAppleton\WLVPN\Services\VPN;

class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return VPN::class;
    }
}
