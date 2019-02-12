<?php

namespace hotsaucejake\Coinigy;

use GuzzleHttp\Client;

class Coinigy
{
    protected $key;
    protected $secret;
    protected $api_url;
    protected $base_url;
    protected $public_url;
    protected $private_url;
    protected $client;

    /**
     * Client constructor.
     *
     * @param array $auth
     * @param array $urls
     */
    public function __construct(array $auth = null, array $urls = null)
    {
        if (! $auth) {
            $auth = config('laravel-coinigy.auth');
        }
        if (! $urls) {
            $urls = config('laravel-coinigy.urls');
        }

        $this->key = array_get($auth, 'key');
        $this->secret = array_get($auth, 'secret');

        $this->api_url = array_get($urls, 'api');
        $this->base_url = array_get($urls, 'base');
        $this->public_url = array_get($urls, 'public');
        $this->private_url = array_get($urls, 'private');

        $this->client = $this->client = new Client([
            'base_uri' => $this->api_url.$this->base_url,
        ]);
    }

    private function publicGetRequest($endpoint = 'status', array $parameters = [])
    {
        $response = $this->client->get($this->public_url.$endpoint);
        $result = json_decode($response->getBody()->getContents(), true);

        return $result['success'] ? $result['result'] : $result['error'];
    }

    private function privateRequest($endpoint = 'exchanges', array $parameters = [], $method = 'GET')
    {
    }

    /**
     * All Blockchains which Coinigy supports.
     *
     * @return array
     */
    public function chains()
    {
        return $this->publicGetRequest('chains');
    }

    /**
     * Convert one currency value to any other currency.
     *
     * @param string $fromCurrCode
     * @param string $toCurrCode
     * @return decimal
     */
    public function convert($fromCurrCode = 'BTC', $toCurrCode = 'ETH')
    {
        return $this->publicGetRequest('convert/'.$fromCurrCode.'/'.$toCurrCode);
    }

    /**
     * All Exchanges listed on Coinigy.
     *
     * @return array
     */
    public function exchanges()
    {
        return $this->publicGetRequest('exchanges');
    }

    /**
     * A specific exchange listed on Coinigy.
     *
     * @param string $exchCode
     * @return array
     */
    public function exchange($exchCode = 'BITS')
    {
        return $this->publicGetRequest('exchanges/'.$exchCode);
    }

    /**
     * All markets on a given exchange.
     *
     * @param string $exchCode
     * @return array
     */
    public function exchangeMarkets($exchCode = 'BITS')
    {
        return $this->publicGetRequest('exchanges/'.$exchCode.'/markets');
    }

    /**
     * All trading pairs listed on Coinigy.
     *
     * @return array
     */
    public function markets()
    {
        return $this->publicGetRequest('markets');
    }

    /**
     * Status of Coinigy v2 API.
     *
     * @return array
     */
    public function status()
    {
        return $this->publicGetRequest('status');
    }
}
