<?php
declare(strict_types=1);

namespace Asgrim\RandomArtist\ArtistListFetcher;

use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Request;
use Zend\Diactoros\Uri;

final class LastFmArtistListFetcher implements ArtistListFetcher
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var string
     */
    private $apiBaseUrl;

    /**
     * @var string
     */
    private $apiKey;

    public function __construct(HttpClient $httpClient, string $apiBaseUrl, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiBaseUrl = $apiBaseUrl;
        $this->apiKey = $apiKey;
    }

    public function fetchArtistList(string $username) : array
    {
        $response = $this->httpClient->sendRequest($this->prepareRequest($username, 1000));

        return array_map(function (array $response) {
            return $response['name'];
        }, json_decode($response->getBody()->getContents(), true)['topartists']['artist']);
    }

    private function prepareRequest(string $username, int $limit) : RequestInterface
    {
        return new Request(
            (new Uri($this->apiBaseUrl))
                ->withQuery(http_build_query([
                    'method' => 'user.gettopartists',
                    'user' => $username,
                    'api_key' => $this->apiKey,
                    'format' => 'json',
                    'period' => 'overall',
                    'limit' => $limit,
                ])),
            'GET'
        );
    }
}
