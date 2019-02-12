<?php

namespace hotsaucejake\Coinigy;

class Coinigy
{
    protected $key;
    protected $secret;
    protected $base_url;
    protected $public_url;
    protected $private_url;
    protected $curl;

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

        $this->base_url = array_get($urls, 'base');
        $this->public_url = array_get($urls, 'public');
        $this->private_url = array_get($urls, 'private');
    }

    private function publicRequest($method, array $parameters = [])
    {
        $options = [
            'http' => [
                'method' => 'GET',
                'timeout' => 10,
            ],
        ];

        $url = $this->public_url.$method.'?'.http_build_query(array_filter($parameters));

        $result = json_decode(file_get_contents($url, false, stream_context_create($options)), true);

        return $result['success'] ? $result['result'] : $result['error'];
    }

    public function chains()
    {
        return $this->publicRequest('chains');
    }

    public function exchanges()
    {
        return $this->publicRequest('exchanges');
    }

    public function markets()
    {
        return $this->publicRequest('markets');
    }

    public function status()
    {
        return $this->publicRequest('status');
    }
}
