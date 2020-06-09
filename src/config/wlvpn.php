<?php

return [
    /*
      |--------------------------------------------------------------------------
      | WLVPN Account Group Id
      |--------------------------------------------------------------------------
      |
      | This is the account group you have setup within you white label vpn
      | reseller account
      |
      */
  'acct_group_id' => 0,

    /*
      |--------------------------------------------------------------------------
      | WLVPN Close Account
      |--------------------------------------------------------------------------
      |
      | WLVPN can be provided with a date upon which to close account
      |
      */
  'close_date' => [
    'close_accounts' => true,
    'close_date' => Carbon\Carbon::now()->addMonth()->format('Y-m-d'),
  ],
];
