<?php

declare(strict_types=1);

namespace RonAppleton\WLVPN\Services;

use GuzzleHttp\Client;
use RonAppleton\WLVPN\Enums\Response;
use RonAppleton\WLVPN\Exceptions\WLVPNResponseException;

class VPN
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $endpoint = 'https://api.wlvpn.com/v2/';


    public function __construct()
    {
        $this->client = new Client(
          [
            'auth' => [
              'api-key',
              env('WLVPN_API_KEY', ''),
            ],
          ]
        );
    }

    /**
     * Create account is a POST request
     *
     * @param int $userId
     * @param string $password
     *
     * @param int|null $acctGroupId
     * @param null $closeData
     * @return json
     * @throws WLVPNResponseException
     */
    public function createAccount(int $userId, string $password, int $acctGroupId = null, $closeData = null)
    {
        $data = [
          'cust_user_id' => $userId,
          'cust_password' => $password,
          'acct_group_id' => $acctGroupId ?? config('wlvpn.acct_group_id', 0),
        ];

        if (config('wlvpn.close_date.close_accounts', true)) {
            $data['close_date'] = $closeData ?? config('wlvpn.close_date.close_date');
        }

        $result = $this->client->request('POST', $this->endpoint.'customers', ['json' => $data]);

        if (Response::valid($result) && $result->getStatusCode() === Response::SUCCESS) {
            return json_decode($result->getBody()->getContents());
        }
    }

    /**
     * Update account is a PUT request
     *
     * @param int $accountId
     * @param int $accountStatus
     * @param string $password
     * @param string|null $closeDate
     * 
     * @return mixed
     * @throws WLVPNResponseException
     */
    public function updateAccount(int $accountId, int $accountStatus, string $password = null, string $closeDate = null)
    {
        $data = [
            "acct_status_id" => $accountStatus,
            "acct_group_id" => config('wlvpn.acct_group_id', 0),
        ];

        if ($password) {
            $data['cust_password'] = $password;
        }

        if (config('wlvpn.close_date.close_accounts', true)) {
            $data['close_date'] = $closeDate ?? config('wlvpn.close_date.close_date');
        }

        $result = $this->client->request('PUT', $this->endpoint.'customers/'.$accountId, ['json' => $data]);

        if (Response::valid($result) && $result->getStatusCode() === Response::SUCCESS) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * @param int $accountId
     * @param string $startDate
     * @param array $metrics
     * @param string $endDate
     *
     * @return mixed
     * @throws WLVPNResponseException
     */
    public function usageReport(int $accountId, string $startDate, array $metrics, string $endDate)
    {
        $data = [
          'start_date' => $startDate,
          'metrics' => $metrics,
        ];

        if ($endDate) {
            $data['end_date'] = $endDate;
        }

        $endpoint = $this->endpoint.'customers/'.$accountId.'/usage-report';

        $result = $this->client->request('POST', $endpoint, ['json' => $data]);

        if (Response::valid($result) && $result->getStatusCode() === Response::SUCCESS) {
            return json_decode($result->getBody()->getContents());
        }
    }

    /**
     * Check Username is a get request
     *
     * @param string $username
     *
     * @return json
     * @throws WLVPNResponseException
     */
    public function checkUsername(string $username)
    {
        $result = $this->client->request('GET', $this->endpoint.'/username_exists/'.$username);

        if (Response::valid($result) && $result->getStatusCode() === Response::SUCCESS) {
            return json_decode($result->getBody()->getContents());
        }
    }

    /**
     * Create limitation is a POST request
     *
     * @param int    $accountId
     * @param string $type
     * @param mixed  $value
     *
     * @return bool
     * @throws WLVPNResponseException
     */
    public function createLimitation(int $accountId, string $type = 'rate-limit', $value)
    {
        $data = [
          'limitations' => [
            'type' => $type,
            'value' => $value,
          ],
        ];

        $endpoint = $this->endpoint.'customers/'.$accountId.'/limitations';

        $result = $this->client->request('POST', $endpoint, ['json' => $data]);

        if (Response::valid($result) && $result->getStatusCode() === Response::NO_CONTENT) {
            return true;
        }

        return false;
    }

    /**
     * Update limitations is a PUT request
     *
     * @param int    $accountId
     * @param string $type
     * @param mixed  $value
     *
     * @return bool
     * @throws WLVPNResponseException
     */
    public function updateLimitation(int $accountId, string $type = 'rate-limit', $value)
    {
        $data = [
          'limitations' => [
            'type' => $type,
            'value' => $value,
          ],
        ];

        $endpoint = $this->endpoint.'customers/'.$accountId.'/limitations';

        $result = $this->client->request('PUT', $endpoint, ['json' => $data]);

        if (Response::valid($result) && $result->getStatusCode() === Response::NO_CONTENT) {
            return true;
        }

        return false;
    }

    /**
     * Delete limitations is a DELETE request
     *
     * @param int $accountId
     *
     * @return bool
     * @throws WLVPNResponseException
     */
    public function deleteLimitation(int $accountId): bool
    {
        $endpoint = $this->endpoint.'customers/'.$accountId.'/limitations';

        $result = $this->client->request('DELETE', $endpoint);

        if (Response::valid($result) && $result->getStatusCode() === Response::NO_CONTENT) {
            return true;
        }

        return false;
    }

    /**
     * List servers is a GET request
     *
     * @return json
     * @throws WLVPNResponseException
     */
    public function listServers()
    {
        $endpoint = $this->endpoint . 'servers';

        $result = $this->client->request('GET', $endpoint);

        if (Response::valid($result) && $result->getStatusCode() === Response::SUCCESS) {
            return json_decode($result->getBody()->getContents());
        }
    }
}
