<?php
declare(strict_types=1);

namespace Asgrim\RandomArtist\Spotify;

use Http\Client\HttpClient;
use League\OAuth2\Client\Provider\GenericProvider;
use Zend\Diactoros\Request;
use Zend\Diactoros\Uri;

final class SpotifyFetchArtistSpotifyUri implements FetchArtistSpotifyUri
{
    /**
     * @var GenericProvider
     */
    private $oauthProvider;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var string
     */
    private $searchBaseUri;

    public function __construct(GenericProvider $oauthProvider, HttpClient $httpClient, string $searchBaseUri)
    {
        $this->oauthProvider = $oauthProvider;
        $this->httpClient = $httpClient;
        $this->searchBaseUri = $searchBaseUri;
    }

    public function fetch(string $artistName) : string
    {
        $accessToken = $this->oauthProvider->getAccessToken('client_credentials');

        $response = $this->httpClient->sendRequest(
            (
                new Request(
                    (new Uri($this->searchBaseUri))
                        ->withQuery(http_build_query([
                            'type' => 'artist',
                            'q' => $artistName,
                        ])),
                    'GET'
                )
            )
            ->withHeader('Authorization', 'Bearer ' . $accessToken->getToken())
        );

        $payload = json_decode($response->getBody()->getContents(), true);

        $firstArtist = reset($payload['artists']['items']);

        return $firstArtist['uri'];
    }
}
