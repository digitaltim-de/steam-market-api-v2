<?php

namespace SteamApi\Requests;

use SteamApi\Engine\Request;
use SteamApi\Interfaces\RequestInterface;

class SearchItems extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/market/search/render?appid=%s&start=%s&count=%s&query=%s%s&norender=1";

    private $appId;
    private $start = 0;
    private $count = 100;
    private $query = '';
    private $exact = false;
    private $filters = '';
    private $method = 'GET';

    public function __construct($appId, $options = [])
    {
        $this->appId = $appId;
        $this->setOptions($options);
    }

    public function getUrl(): string
    {
        return sprintf(self::URL, $this->appId, $this->start, $this->count, $this->query,
            !empty($this->filters) ? '&' . $this->filters : '');
    }

    public function call($proxy = [], $detailed = false)
    {
        return $this->steamHttpRequest($proxy, $detailed);
    }

    public function getRequestMethod(): string
    {
        return $this->method;
    }

    private function setOptions($options)
    {
        $this->start = isset($options['start']) ? $options['start'] : $this->start;
        $this->count = isset($options['count']) ? $options['count'] : $this->count;
        $this->exact = isset($options['exact']) ? $options['exact'] : $this->exact;

        $this->query = isset($options['query']) ?
            ($this->exact ? sprintf('"%s"', rawurlencode($options['query'])) : rawurlencode($options['query'])) :
            $this->query;

        $this->filters = isset($options['filters']) ? http_build_query($options['filters']) : $this->filters;
    }

    public function getHeaders(): array
    {
        return [];
    }
}