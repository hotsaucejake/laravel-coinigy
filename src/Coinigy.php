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

    private function privateGetRequest($endpoint = 'exchanges', $params = [])
    {
        $timestamp = time();
        $sign_request = $this->key.$timestamp.'GET'.$this->base_url.$this->private_url.$endpoint;
        $sign = hash_hmac('sha256', $sign_request, $this->secret);

        $response = $this->client->get($this->private_url.$endpoint, [
            'headers' => [
                'X-API-KEY' => $this->key,
                'X-API-TIMESTAMP' => $timestamp,
                'X-API-SIGN' => $sign,
            ],
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
     * All trading pairs that are no longer actively traded on a given exchange.
     *
     * @param string $exchCode
     * @return array
     */
    public function getExchangeDeadMarkets($exchCode = 'BITS')
    {
        return $this->privateGetRequest('exchanges/'.$exchCode.'/markets/dead');
    }

    /**
     * All traiding pairs listed on Coinigy.
     *
     * @return array
     */
    public function getMarkets()
    {
        return $this->privateGetRequest('markets');
    }

    /**
     * All trading pairs that are no longer actively traded.
     *
     * @return array
     */
    public function getDeadMarkets()
    {
        return $this->privateGetRequest('markets/dead');
    }

    /*
     ***************************************************************************
     * PRIVATE - Exchange Data
     ***************************************************************************
     *
     * Trade history, order books, market data, news, etc.
     *
     */

    /**
     * Orderbook depth for a given trading pair.
     *
     * @param string $exchCode
     * @param string $baseCurrCode
     * @param string $quoteCurrCode
     * @return array
     */
    public function getOrderBookDepth($exchCode = 'BITS', $baseCurrCode = 'BTC', $quoteCurrCode = 'USD')
    {
        return $this->privateGetRequest('exchanges/'.$exchCode.'/markets/'.$baseCurrCode.'/'.$quoteCurrCode.'/depth');
    }

    /**
     * Price of last trade on a given market.
     *
     * @param string $exchCode
     * @param string $baseCurrCode
     * @param string $quoteCurrCode
     * @return array
     */
    public function getLastTrade($exchCode = 'BITS', $baseCurrCode = 'BTC', $quoteCurrCode = 'USD')
    {
        return $this->privateGetRequest('exchanges/'.$exchCode.'/markets/'.$baseCurrCode.'/'.$quoteCurrCode.'/last');
    }

    /**
     * OHLC candlestick data for a given trading pair and interval.
     *
     * @param string $exchCode
     * @param string $baseCurrCode
     * @param string $quoteCurrCode
     * @param string $period
     * @return array
     */
    public function getCandlestick($exchCode = 'BITS', $baseCurrCode = 'BTC', $quoteCurrCode = 'USD', $period = '1d', $params = ['StartDate' => '2019-02-11T17:02:38.623Z', 'EndDate' => '2019-02-12T17:02:38.623Z'])
    {
        // return $this->privateGetRequest('exchanges/'.$exchCode.'/markets/'.$baseCurrCode.'/'.$quoteCurrCode.'/ohlc/'.$period);
        // requires query string
        return false;
    }

    /**
     * Historical price ranges for a given trading pair.
     *
     * @param string $exchCode
     * @param string $baseCurrCode
     * @param string $quoteCurrCode
     * @return array
     */
    public function getRange($exchCode = 'BITS', $baseCurrCode = 'BTC', $quoteCurrCode = 'USD')
    {
        return $this->privateGetRequest('exchanges/'.$exchCode.'/markets/'.$baseCurrCode.'/'.$quoteCurrCode.'/range');
    }

    /**
     * 24-Hour Ticker data for a given trading pair.
     *
     * @param string $exchCode
     * @param string $baseCurrCode
     * @param string $quoteCurrCode
     * @return void
     */
    public function getTicker($exchCode = 'BITS', $baseCurrCode = 'BTC', $quoteCurrCode = 'USD')
    {
        return $this->privateGetRequest('exchanges/'.$exchCode.'/markets/'.$baseCurrCode.'/'.$quoteCurrCode.'/ticker');
    }

    /**
     * Recent trades for a given pair.
     *
     * @param string $exchCode
     * @param string $baseCurrCode
     * @param string $quoteCurrCode
     * @return array
     */
    public function getTrades($exchCode = 'BITS', $baseCurrCode = 'BTC', $quoteCurrCode = 'USD')
    {
        return $this->privateGetRequest('exchanges/'.$exchCode.'/markets/'.$baseCurrCode.'/'.$quoteCurrCode.'/trades');
    }

    /**
     * Trade history for a given trading pair.
     *
     * @param string $exchCode
     * @param string $baseCurrCode
     * @param string $quoteCurrCode
     * @return array
     */
    public function getTradeHistory($exchCode = 'BITS', $baseCurrCode = 'BTC', $quoteCurrCode = 'USD')
    {
        // return $this->privateGetRequest('exchanges/'.$exchCode.'/markets/'.$baseCurrCode.'/'.$quoteCurrCode.'/trades/history');
        // requires query string
        return false;
    }

    /**
     * Trade history for a given trading pair since a given ID.
     *
     * @param string $exchCode
     * @param string $baseCurrCode
     * @param string $quoteCurrCode
     * @param long $sinceMarketHistoryId
     * @return array
     */
    public function getTradeHistorySince($exchCode = 'BITS', $baseCurrCode = 'BTC', $quoteCurrCode = 'USD', $sinceMarketHistoryId)
    {
        return $this->privateGetRequest('exchanges/'.$exchCode.'/markets/'.$baseCurrCode.'/'.$quoteCurrCode.'/trades/history/'.$sinceMarketHistoryId);
    }

    /**
     * 24-Hour Ticker data for all trading pairs on a given exchange.
     *
     * @param string $exchCode
     * @return array
     */
    public function getExchangeTicker($exchCode = 'BITS')
    {
        return $this->privateGetRequest('exchanges/'.$exchCode.'/ticker');
    }

    /**
     * Ticker data for all trading pairs listed on Coinigy.
     *
     * @return array
     */
    public function getMarketTicker()
    {
        return $this->privateGetRequest('markets/ticker');
    }

    /**
     * Recent articles from Coinigy's news feed.
     *
     * @return array
     */
    public function news()
    {
        return $this->privateGetRequest('news');
    }

    /**
     * Search articles from Coinigy's news feed.
     *
     * @param string $searchTerm
     * @return array
     */
    public function newsSearch($searchTerm = 'bullbearanalytics.com')
    {
        return $this->privateGetRequest('news/'.$searchTerm);
    }
}
