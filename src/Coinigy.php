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

    private function publicGetRequest($endpoint = 'status')
    {
        $response = $this->client->get($this->public_url.$endpoint);
        $result = json_decode($response->getBody()->getContents(), true);

        return $result['success'] ? $result['result'] : $result['error'];
    }

    private function privateGetRequest($endpoint = 'exchanges', $body = '')
    {
        $timestamp = time();
        $sign_request = $this->key.$timestamp.'GET'.$this->base_url.$this->private_url.$endpoint.$body;
        $sign = hash_hmac('sha256', $sign_request, $this->secret);

        $response = $this->client->get($this->private_url.$endpoint, [
            'headers' => [
                'X-API-KEY' => $this->key,
                'X-API-TIMESTAMP' => $timestamp,
                'X-API-SIGN' => $sign,
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result['success'] ? $result['result'] : $result['error'];
    }

    /*
     ***************************************************************************
     * PUBLIC
     ***************************************************************************
     *
     * Available to anyone, even without a Coinigy Subscription.
     *
     */

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

    /*
     ***************************************************************************
     * PRIVATE - Exchanges & Markets
     ***************************************************************************
     *
     * Supported exchanges and trading pairs.
     *
     */

    /**
     * All Exchanges listed on Coinigy.
     *
     * @return array
     */
    public function getExchanges()
    {
        return $this->privateGetRequest('exchanges');
    }

    /**
     * A specific exchange listed on Coinigy.
     *
     * @param string $exchCode
     * @return array
     */
    public function getExchange($exchCode = 'BITS')
    {
        return $this->privateGetRequest('exchanges/'.$exchCode);
    }

    /**
     * Info about a given currency.
     *
     * @param string $exchCode
     * @param string $baseCurrCode
     * @return array
     */
    public function getCurrency($exchCode = 'BITS', $baseCurrCode = 'BTC')
    {
        return $this->privateGetRequest('exchanges/'.$exchCode.'/'.$baseCurrCode);
    }

    /**
     * Trading Pair detail info for a given trading pair.
     *
     * @param string $exchCode
     * @param string $baseCurrCode
     * @param string $quoteCurrCode
     * @return array
     */
    public function getExchangeMarket($exchCode = 'BITS', $baseCurrCode = 'BTC', $quoteCurrCode = 'USD')
    {
        return $this->privateGetRequest('exchanges/'.$exchCode.'/markets/'.$baseCurrCode.'/'.$quoteCurrCode);
    }

    /**
     * All trading pairs that are no longer actively traded on a given exchange
     *
     * @param string $exchCode
     * @return array
     */
    public function getExchangeDeadMarkets($exchCode = 'BITS')
    {
        return $this->privateGetRequest('exchanges/'.$exchCode.'/markets/dead');
    }

    /**
     * All traiding pairs listed on Coinigy
     *
     * @return array
     */
    public function getMarkets()
    {
        return $this->privateGetRequest('markets');
    }

    /**
     * All trading pairs that are no longer actively traded
     *
     * @return array
     */
    public function getDeadMarkets()
    {
        return $this->privateGetRequest('markets/dead');
    }
}
